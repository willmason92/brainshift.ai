<?php

namespace App\Http\Controllers;

use App\Contracts\PermissionsRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Psr\Log\LoggerInterface;

class PermissionsController extends Controller
{
    /**
     * @param  Request  $request
     * @param  PermissionsRepositoryInterface  $permissionsRepository
     * @param  LoggerInterface  $logger
     */
    public function __construct(
        protected Request $request,
        protected PermissionsRepositoryInterface $permissionsRepository,
        protected LoggerInterface $logger
    ) {
    }

    /**
     * @return JsonResponse
     */
    public function assignPermissionRole(): JsonResponse
    {
        try {
            $permission = $this->request->get('permission');
            $role = $this->request->get('role');

            return response()->json([
                $this->permissionsRepository->assignPermissionToRole($permission, $role),
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());

            return response()->json([
                $exception->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @return JsonResponse
     */
    public function revokePermissionRole(): JsonResponse
    {
        try {
            $permission = $this->request->get('permission');
            $role = $this->request->get('role');

            return response()->json([
                $this->permissionsRepository->revokePermissionToRole($permission, $role),
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());

            return response()->json([
                $exception->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @return JsonResponse
     */
    public function assignRole(): JsonResponse
    {
        try {
            $role = $this->request->get('role');
            $userId = $this->request->get('user_id');

            return response()->json([
                $this->permissionsRepository->assignUserToRole($userId, $role),
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());

            return response()->json([
                $exception->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @return JsonResponse
     */
    public function revokeRole(): JsonResponse
    {
        try {
            $role = $this->request->get('role');
            $userId = $this->request->get('user_id');

            return response()->json([
                $this->permissionsRepository->revokeUserToRole($userId, $role),
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());

            return response()->json([
                $exception->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
