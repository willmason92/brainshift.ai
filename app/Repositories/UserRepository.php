<?php

namespace App\Repositories;

use App\Contracts\UserRepositoryInterface;
use App\Models\User;
use ESolution\DBEncryption\Traits\EncryptedAttribute;

class UserRepository implements UserRepositoryInterface
{
    use EncryptedAttribute;

    /**
     * @param  array  $userData
     * @return User
     */
    public function createOrUpdateUser(array $userData, $type = 0): User
    {
        $user = User::withTrashed()->where('email', $this->encryptAttribute($userData['email']))->first();

        if ( !empty($user->deleted_at) ) { abort(403); }

        if ($user) {
            $user->token = $userData['token'];
            $user->name = $userData['name'];
            $user->save();
        } elseif ($type === 2) {
            $user = User::create([
                'email' => strtolower($userData['email']),
                'token' => $userData['token'],
                'name' => $userData['name'],
            ]);
        }

        if ( empty($user) ) { abort(403); }

        return $user;
    }

    public function createUser(array $data): array
    {
        return User::create($data)->toArray();
    }

    /**
     * @return array
     */
    public function listUsers(): array
    {
        return User::all()->toArray();
    }

    /**
     * @param  int  $id
     * @return array
     */
    public function getUser(int $id): array
    {
        return User::findOrFail($id)->toArray();
    }

    /**
     * @param  int  $id
     * @return bool
     */
    public function deleteUser(int $id): bool
    {
        $user = User::findOrFail($id);

        return $user->delete();
    }

    /**
     * @param  int  $id
     * @param  array  $data
     * @return array
     */
    public function updateUser(int $id, array $data): array
    {
        $user = User::findOrFail($id);
        foreach ($data as $key => $value) {
            $user->$key = $value;
        }
        $user->save();

        return $user->toArray();
    }
}
