<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticleHowToUseAppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cover = DB::table('files')->insertGetId([
            'filename' => 'myeternit_app_landing-page_1920x560_v02.webp',
            'path' => 'farmers-library/cover',
            'mimetype' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('posts')->insertgetId([
            'post_title' => "How to use the app",
            'post_content' => '<h3 id="s1" style="text-align: center;" data-aura-rendered-by="12:242;a">iPad or iPhone</h3>
            <p style="text-align: center;" data-aura-rendered-by="12:242;a"><strong>1.</strong> Launch the <strong>Safari</strong>&nbsp;app. *Please note that this does not work from the &ldquo;Chrome&rdquo; app.</p>
            <p style="text-align: center;"><strong>2.</strong> Enter into the address field the URL <a href="https://brainshift.ai" target="_blank" rel="noopener">https://brainshift.ai</a> and tap <strong>Go</strong>.</p>
            <p style="text-align: center;"><strong>3.</strong> Tap the icon featuring an up-pointing arrow coming out of a box along the bottom of the Safari window to open a menu.</p>
            <p style="text-align: center;"><strong>4.</strong> Scroll down and tap <strong>Add to Home Screen</strong>. The "Add to Home Screen" dialog box will appear, with the icon and the name that will be used for this website.</p>
            <p style="text-align: center;"><strong>5.</strong> Tap <strong>Add</strong>. Safari will close automatically and you will be taken to where the icon is located on your Home Screen.</p>
            <p style="text-align: center;">&nbsp;</p>
            <h3 id="s1" style="text-align: center;" data-aura-rendered-by="12:242;a">Android device</h3>
            <p style="text-align: center;" data-aura-rendered-by="12:242;a"><strong>1.</strong> Launch the&nbsp;<strong>Chrome</strong>&nbsp;app. *Please note that this does not work from the device\'s native web browser app.</p>
            <p style="text-align: center;" data-aura-rendered-by="12:242;a"><strong>2.</strong> Enter into the address field the URL <a href="https://brainshift.ai" target="_blank" rel="noopener">https://brainshift.ai</a> and tap <strong>Go</strong>.</p>
            <p style="text-align: center;"><strong>3.</strong> Tap the menu icon (3 dots in upper right-hand corner) and tap <strong>Add to Home Screen</strong>.</p>
            <p style="text-align: center;"><strong>4.</strong> The "Add to Home Screen" dialog box will appear, with the icon and the name that will be used for this website.</p>
            <p style="text-align: center;"><strong>5.</strong> Tap <strong>Add</strong>. Chrome will then add the icon to your Home Screen in the background.</p>
            <p style="text-align: center;">&nbsp;</p>',
            'post_type' => 2,
            'author' => User::whereEncrypted('email', '=', 'dan@cygnus.co.uk')->pluck('id')->first(),
            'cover_image' => $cover,
            'uri_slug' => "how-to-use-the-app",
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
