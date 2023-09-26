<?php

namespace App\Contracts;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use SocialiteProviders\Manager\OAuth2\User as SocialiteUser;

interface Adb2cServiceInterface
{
    /**
     * @return RedirectResponse
     */
    public function login();

    /**
     * @param string $returnReferrer
     * @return User|null
     */
    public function callback(string &$returnReferrer): ?User;

    /**
     * @return RedirectResponse
     */
    public function register(): RedirectResponse;

    /**
     * @return mixed
     */
    public function logout();

    /**
     * @param  SocialiteUser  $user
     * @return array
     */
    public function parseUserFromCallback(SocialiteUser $user): array;
}
