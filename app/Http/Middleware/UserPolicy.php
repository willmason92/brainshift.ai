<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserPolicy
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $this->applyUserRolesPolicy($request)) {
            return response('You do not have sufficient rights to administer this user.', Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }

    /**
     * @param  Request  $request
     * @return bool
     */
    protected function applyUserRolesPolicy(Request $request): bool
    {
        $requestingUser = $this->getRequestingUser($request);
        $requestedUser = $this->getRequestedUser($request);

        if ($requestingUser->hasRole('SuperAdmin')) {
            return true;                                    //only superadmin can access for now
        }

        //block to be re-enabled when user rights fully defined

//        if ($request->routeIs('users/{id}')) {
//            if ($request->method() !== 'DELETE') {           //user can't delete themselves
//                return false;
//            }
//            if ($requestingUser !== $requestedUser) {        //user can't view other user's details
//                return false;
//            }
//
//            return true;
//        }

        return false;
    }

    /**
     * @param  Request  $request
     * @return User|null
     */
    protected function getRequestingUser(Request $request): ?User
    {
        return $request->user('web');
    }

    /**
     * @param  Request  $request
     * @return User|null
     */
    protected function getRequestedUser(Request $request): ?User
    {
        return User::find($request->route('id'));
    }
}
