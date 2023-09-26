<?php

namespace App\Contracts;

interface UserRepositoryInterface
{
    /**
     * @param  array  $data
     * @return array
     */
    public function createUser(array $data): array;

    /**
     * @return array
     */
    public function listUsers(): array;

    /**
     * @param  int  $id
     * @return array
     */
    public function getUser(int $id): array;

    /**
     * @param  int  $id
     * @return bool
     */
    public function deleteUser(int $id): bool;

    /**
     * @param  int  $id
     * @param  array  $data
     * @return array
     */
    public function updateUser(int $id, array $data): array;
}
