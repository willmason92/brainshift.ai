<?php

namespace App\Http\Controllers;

use App\Contracts\UserRepositoryInterface;
use App\Http\Requests\Users\DeleteUserRequest;
use App\Http\Requests\Users\GetUserRequest;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateContactRequest;
use App\Models\User;
use ESolution\DBEncryption\Traits\EncryptedAttribute;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    use EncryptedAttribute;

    /**
     * @param  UserRepositoryInterface  $userRepository
     */
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {
    }

    public function perms()
    {
        $perms = [];
        $user = Auth::user();

        $perms['user'] = $user;

        $permissions = $user->getAllPermissions();

        $perms['permissions'] = $permissions;

        return response()->json($perms);
    }

    public function tempLogin($email)
    {
        $user = User::where('email', $this->encryptAttribute($email))->first();
        if ($user) {
            Auth::login($user);

            return redirect('/');
        }
        abort(403);
    }

    public function tempLogout()
    {
        Auth::logout();

        return redirect('/');
    }

    /**
     * @param  StoreUserRequest  $request
     * @return JsonResponse
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        return response()->json([
            $this->userRepository->createUser($request->toArray()),
        ], Response::HTTP_CREATED);
    }

    /**
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        return response()->json([
            $this->userRepository->listUsers(),
        ], Response::HTTP_OK);
    }

    /**
     * @param  int  $id
     * @return JsonResponse
     */
    public function show(GetUserRequest $request): JsonResponse
    {
        return response()->json([
            $this->userRepository->getUser($request->id),
        ], Response::HTTP_OK);
    }

    /**
     * @param  int  $id
     * @return bool
     */
    public function delete(DeleteUserRequest $request): JsonResponse
    {
        return response()->json([
            $this->userRepository->deleteUser($request->id),
        ], Response::HTTP_NO_CONTENT);
    }

    /**
     * @param  int  $id
     * @param  array  $data
     * @return array
     */
    public function update(UpdateContactRequest $request): JsonResponse
    {
        return response()->json([
            $this->userRepository->updateUser($request->id, $request->toArray()),
        ], Response::HTTP_OK);
    }
}
