<?php

namespace App\Http\Controllers;

use App\Contracts\GeoRepositoryInterface;
use App\Http\Requests\Regions\AssignPostcodeRequest;
use App\Http\Requests\Regions\UnAssignPostcodeRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class GeoController extends Controller
{
    /**
     * @param  GeoRepositoryInterface  $geoRepository
     */
    public function __construct(protected GeoRepositoryInterface $geoRepository)
    {
    }

    /**
     * @param  AssignPostcodeRequest  $request
     * @return JsonResponse
     */
    public function assignPostcodeToRegion(AssignPostcodeRequest $request): JsonResponse
    {
        return response()->json([
            $this->geoRepository->assignPostcodeToRegion($request),
        ], Response::HTTP_OK);
    }

    /**
     * @param  AssignPostcodeRequest  $request
     * @return JsonResponse
     */
    public function unAssignPostcode(UnAssignPostcodeRequest $request): JsonResponse
    {
        return response()->json([
            $this->geoRepository->unAssignPostcode($request),
        ], Response::HTTP_OK);
    }
}
