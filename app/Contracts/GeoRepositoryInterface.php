<?php

namespace App\Contracts;

use App\Http\Requests\Regions\AssignPostcodeRequest;
use App\Http\Requests\Regions\UnAssignPostcodeRequest;
use App\Models\PostcodeMap;

interface GeoRepositoryInterface
{
    /**
     * @param  string  $postcode
     * @return mixed
     */
    public function searchOutcode(string $postcode);

    /**
     * @param  string  $postcode
     * @return string
     */
    public function extractOutcodeFromPostcode(string $postcode): string;

    /**
     * @param  string  $outcode
     * @return PostcodeMap
     */
    public function getRegionDataFromOutcode(string $outcode): PostcodeMap;

    /**
     * @param  AssignPostcodeRequest  $request
     * @return PostcodeMap
     */
    public function assignPostcodeToRegion(AssignPostcodeRequest $request): PostcodeMap;

    /**
     * @param  UnAssignPostcodeRequest  $request
     * @return PostcodeMap
     */
    public function unAssignPostcode(UnAssignPostcodeRequest $request): PostcodeMap;
}
