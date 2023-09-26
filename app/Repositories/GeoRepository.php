<?php

namespace App\Repositories;

use App\Contracts\GeoRepositoryInterface;
use App\Http\Requests\Regions\AssignPostcodeRequest;
use App\Http\Requests\Regions\UnAssignPostcodeRequest;
use App\Models\PostcodeMap;

class GeoRepository implements GeoRepositoryInterface
{
    /**
     * @param  string  $postcode
     * @return void
     */
    public function searchOutcode(string $postcode): PostcodeMap
    {
        $outCode = $this->extractOutcodeFromPostcode($postcode);

        return $this->getRegionDataFromOutcode($outCode);
    }

    /**
     * @param  string  $postcode
     * @return string
     */
    public function extractOutcodeFromPostcode(string $postcode): string
    {
        $string = trim(str_replace(' ', '', strtoupper($postcode)));

        return substr($string, 0, -3);
    }

    /**
     * @param  string  $outcode
     * @return PostcodeMap
     */
    public function getRegionDataFromOutcode(string $outcode): PostcodeMap
    {
        return PostcodeMap::where('outcode', $outcode)->with('region')->first();
    }

    /**
     * @param  AssignPostcodeRequest  $request
     * @return PostcodeMap
     */
    public function assignPostcodeToRegion(AssignPostcodeRequest $request): PostcodeMap
    {
        $postcodeMap = PostcodeMap::where('outcode', $request->get('postcode'))->first();

        $postcodeMap->region_id = $request->get('region_id');

        $postcodeMap->save();

        return $postcodeMap;
    }

    /**
     * @param  UnAssignPostcodeRequest  $request
     * @return PostcodeMap
     */
    public function unAssignPostcode(UnAssignPostcodeRequest $request): PostcodeMap
    {
        $postcodeMap = PostcodeMap::where('outcode', $request->get('postcode'))->first();

        $postcodeMap->region_id = null;

        $postcodeMap->save();

        return $postcodeMap;
    }
}
