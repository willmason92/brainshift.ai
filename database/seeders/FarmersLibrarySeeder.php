<?php

namespace Database\Seeders;

use App\Models\Colour;
use App\Models\Field;
use App\Models\Material;
use App\Models\PostType;
use App\Models\Sector;
use App\Models\Tag;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FarmersLibrarySeeder extends Seeder
{
    //[1 => 'Articles'],
    //[2 => 'Case Studies'],
    //[3 => 'Technical Document'],
    //[4 => 'Installation Video'],
    //[5 => 'Social Media Account'],
    //[6 => 'Media Partner'],

    private $_import_files = [
        'Articles' => 'database/data/farmerslibrary_articles.csv', //Articles
        'Case Studies' => 'database/data/farmerslibrary_casestudies.csv', //Case Studies
        'Technical Documents' => 'database/data/farmerslibrary_techdoc.csv', //Technical Document
        'Installation Videos' => 'database/data/farmerslibrary_videos.csv', //Installation Video
        'Social Media Accounts' => 'database/data/farmerslibrary_social.csv', //Social Media Account
        'Media Partners' => 'database/data/farmerslibrary_media.csv', //Media Partner
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //fetch the collections we need
        $pTypes = PostType::pluck('name', 'id')->toArray();
        $tags = Tag::pluck('name', 'id')->toArray();
        $superAdmin = User::whereEncrypted('email', '=', 'dan@cygnus.co.uk')->pluck('id')->first();
        $dateOffset = 0;

        //iterate each import file
        foreach ($this->_import_files as $type => $filepath) {
            try {
                //dump($filepath);
                $pType = array_search($type, $pTypes);
                //dump($pType);

                if (! file_exists(base_path($filepath)) || $pType === false) {
                    //dump("no file or pType");
                    continue; //no file or type!
                }

                if ($pType === 3) {
                    $colours = Colour::pluck('name', 'id');
                }

                $csvFile = fopen(base_path($filepath), 'r');
                $firstline = true;

                /* --Common Fields--
                 * 0 post_title
                 * 1 post_content
                 * 2 cover_image
                 * 3 tags -optional
                 * 4 date -optional
                 */
                while (($data = fgetcsv($csvFile, 10000, ',')) !== false) {
                    if (! $firstline) {
                        //required
                        if (empty($data[0]) || empty($data[1]) || empty($data[2])) {
                            //dump("no required");
                            continue;
                        }

                        //check cover image file
                        if (! file_exists(public_path()."/storage/farmers-library/cover/{$data[2]}")) {
                            //dump("no cover");
                            continue;
                        }

                        //fetch meta and apply required
                        $metaFields = Field::where('posttype_id', $pType)->orderBy('id')->pluck('type', 'id');
                        if (! empty($metaFields)) {
                            //Case Study Gallery, TechDoc Filename, Video URL, Social URL, Media URL
                            if ($pType >= 3 && empty($data[5])) {
                                continue;
                            }

                            //Case Study Location, Type
                            if ($pType === 3 && ! in_array($data[7], ['0', '1'])) {
                                continue;
                            }

                            //parse meta fields
                            $offset = 4;
                            $metaData = [];
                            foreach ($metaFields as $fId => $fieldType) {
                                $offset++;
                                $value = null;
                                //if file/files, do that first
                                switch ($fieldType) {
                                    case 3: //doc
                                    case 8: //video poster
                                        $sub = $fieldType === 8 ? 'video-posters' : 'documents';
//                                        dump(public_path()."/storage/farmers-library/{$sub}/{$data[$offset]}");
                                        if (file_exists(public_path()."/storage/farmers-library/{$sub}/{$data[$offset]}")) {
//                                            dump($data[$offset]);
                                            $metaData[$offset] = $data[$offset];
                                        } else {
                                            continue 2;
                                        }
                                        break;
                                    case 2: //Case Study Date
                                        $metaData[$offset] = null;
                                        if (! empty($data[$offset])) {
                                            $metaData[$offset] = Carbon::createFromFormat('d/m/Y', $data[$offset]);
                                        }
                                        break;
                                    case 4:
                                        //ToDo : Parse Location
                                        $metaData[$offset] = $data[$offset];
                                        break;
                                    case 5: //material
                                        $metaData[$offset] = null;
                                        if (! empty($colours[$data[$offset]])) {
                                            $material = Material::where('product_line', 0)
                                                ->where('colour', $data[$offset])
                                                ->first();
                                            if ($material) {
                                                $metaData[$offset] = json_encode([$material->id]);
                                            }
                                        }
                                        break;
                                    case 10: //gallery
                                        $files = [];
                                        foreach (str_getcsv($data[$offset]) as $file) {
                                            if (file_exists(public_path()."/storage/farmers-library/gallery/{$file}")) {
                                                $files[] = $file;
                                            } else {
                                                continue 3;
                                            }
                                        }
                                        if (empty($files)) {
                                            continue 2;
                                        } else {
                                            $metaData[$offset] = $files;
                                        }
                                        break;
                                    default:
                                        $metaData[$offset] = $data[$offset];
                                        break;
                                }
                            }
                        }
//                        dump($metaData);

                        //create slug
                        $slug = str_replace(' ', '-', preg_replace('/[^a-z0-9 ]/', '', strtolower($data[0])));
                        if (empty($slug)) {
                            continue;
                        }

                        // Cover Image
                        $image = DB::table('files')->insertGetId([
                            'filename' => $data[2],
                            'path' => 'farmers-library/cover',
                            'mimetype' => 0,
                        ]);

                        //date
                        $dateOffset++;
                        $date = (empty($data[4])
                            ? Carbon::now()->subDays(40)->addMinutes($dateOffset)
                            : Carbon::createFromFormat('d/m/Y', $data[4])
                        );

                        $postId = DB::table('posts')->insertgetId([
                            'post_title' => $data[0],
                            'post_content' => $data[1],
                            'post_type' => $pType,
                            'author' => $superAdmin,
                            'cover_image' => $image,
                            'uri_slug' => $slug,
                            'created_at' => $date,
                            'updated_at' => $date,
                        ]);
                        //dump($postId);

                        //add Post Tags
                        if (! empty($data[3])) {
                            $insertTags = [];
                            foreach (explode('|', $data['3']) as $a) {
                                if (($tId = array_search(trim($a), $tags)) !== false) {
                                    $insertTags[] = [
                                        'post_id' => $postId,
                                        'blog_tag_id' => $tId,
                                    ];
                                }
                            }
                            if (! empty($insertTags)) {
                                DB::table('post_tags')->insert($insertTags);
                            }
                        }

                        /* --Post Meta-- */
                        $offset = 5;
                        $data = [];
                        foreach ($metaFields as $fId => $fieldType) {
                            //add files
//                            dump($fId.' '.$fieldType);
//                            dump($metaData[$offset]);
                            switch ($fieldType) {
                                case 3: // Document
                                    if (! empty($metaData[$offset])) {
//                                        dump($metaData[$offset]);
                                        $metaData[$offset] = DB::table('files')->insertGetId([
                                            'filename' => $metaData[$offset],
                                            'path' => 'farmers-library/documents',
                                            'mimetype' => 1,
                                        ]);
//                                        dump($metaData[$offset]);
                                    }
                                    break;
                                case 8: // Poster
                                    if (! empty($metaData[$offset])) {
                                        $metaData[$offset] = DB::table('files')->insertGetId([
                                            'filename' => $metaData[$offset],
                                            'path' => 'farmers-library/video-posters',
                                            'mimetype' => 0,
                                        ]);
                                    }
                                    break;
                                case 10: // Gallery
                                    if (! empty($metaData[$offset])) {
                                        $files = [];
                                        foreach ($metaData[$offset] as $file) {
                                            $files[] = DB::table('files')->insertGetId([
                                                'filename' => $file,
                                                'path' => 'farmers-library/gallery',
                                                'mimetype' => 0,
                                            ]);
                                        }
                                        $metaData[$offset] = json_encode($files);
                                    }
                                    break;
                            }
                            //add post meta
                            if (! empty($metaData[$offset]) || ($pType === 3 && $fId === 3 && in_array($metaData[$offset], ['0', '1']))) {
                                $data[] = [
                                    'posts_id' => $postId,
                                    'fields_id' => $fId,
                                    'value' => $metaData[$offset],
                                ];
                            }
                            $offset++;
                        }
                        if (! empty($data)) {
//                            dump($data);
                            DB::table('post_metas')->insert($data);
                        }
                    }
                    $firstline = false;
                }

                fclose($csvFile);
            } catch (\Exception $x) {
//                dump($x);
                if ($csvFile) {
                    @fclose($csvFile);
                }
            }
        }
    }

    /* Common Columns
     * 0 = name
     * 1 = type
     * 2 = sector
     * 3 = email
     * 4 = logo
     * 5 = description
     * 6 = url
     * 7 = street
     * 8 = city
     * 9 = postcode
     * 10 = country
     * 11 = lat
     * 12 = lng
     */

//        //nationwide
//        $nationwide = (empty($data[7]) || empty($data[9]) || empty($data[11]) || empty($data[12]));
//
//        //expert type
//        if (($expertTypeId = array_search($data['1'], $eTypes)) === false) {
//            continue;
//        }
//
//        //sectors
//        $sectorIds = [];
//        if ($data['2'] == 'All') {
//            $sectorIds = array_keys($sectors);
//        } else {
//            foreach (explode('|', $data['2']) as $a) {
//                if (($sId = array_search(trim($a), $sectors)) !== false) {
//                    $sectorIds[] = $sId;
//                }
//            }
//        }
//        if (empty($sectorIds)) {
//            continue;
//        }
//
//        //logo
//        $logoId = DB::table('files')->insertGetId([
//            'filename' => $data[4],
//            'path' => 'experts/company-logo',
//            'mimetype' => 0,
//        ]);
//
//        $insertData = [
//            'user_id' => null,
//            'company_name' => $data[0],
//            'company_email' => empty($data[3]) ? null : $data[3],
//            'nationwide' => (int) $nationwide,
//            'description' => $data[5],
//            'region_id' => null,
//            'expert_url' => $data[6],
//            'logo' => $logoId,
//            'expert_type_id' => $expertTypeId,
//            'created_at' => now(),
//            'updated_at' => now(),
//        ];
//
//        $expertId = DB::table('experts')->insertGetId($insertData);
//
//        //sectors
//        $insertData = [];
//        foreach ($sectorIds as $sId) {
//            $insertData[] = [
//                'expert_id' => $expertId,
//                'user_id' => null,
//                'sector_id' => $sId,
//            ];
//        }
//
//        DB::table('sector_user_expert')->insert($insertData);
}
