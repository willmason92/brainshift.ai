<?php

namespace App\Http\Controllers;

use App\Contracts\Adb2cServiceInterface;
use App\Contracts\UserRepositoryInterface;
use Faker\Provider\Internet;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\RedirectResponse;

class Adb2cController extends Controller
{
    /**
     * @param  Repository  $repository
     * @param  UserRepositoryInterface  $userRepository
     * @param  Adb2cServiceInterface  $adb2cService
     * @param  Request  $request
     */
    public function __construct(
        protected Repository $repository,
        protected UserRepositoryInterface $userRepository,
        protected Adb2cServiceInterface $adb2cService,
        protected Request $request
    ) {
    }

    /**
     * @return RedirectResponse
     */
    public function login()
    {
        return $this->adb2cService->login();
    }

    /**
     * @return RedirectResponse
     */
    public function callback()
    {
        $referrer = '';
        $userModel = $this->adb2cService->callback($referrer);

        if (! empty($userModel)) {
            Auth::login($userModel);
        }

        $referrer = (Auth::user()->hasRole(['Admin', 'SuperAdmin'])
            ? '/admin'
            : (empty($referrer)
                ? '/'
                : $referrer)
        );

        return redirect($referrer);
    }

    /**
     * @return RedirectResponse
     */
    public function newCallback($type)
    {

        $referrer = '';
        $userModel = $this->adb2cService->callback2($type, $referrer);

        if (! empty($userModel)) {
            Auth::login($userModel);
        }

        $referrer = (Auth::user()->hasRole(['Admin', 'SuperAdmin'])
            ? '/admin'
            : (empty($referrer)
                ? '/'
                : $referrer)
        );

        return redirect($referrer);
    }

    /**
     * @return RedirectResponse
     */
    public function register()
    {
        return $this->adb2cService->register();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function logout(Request $request)
    {

        //pick up any logout messages
        $msg = $this->request->session()->get('message');

        //Todo pick up referrer and somehow determin if its auth protected
        //Todo set logout redirtect to / if auth locked or previous position

        Auth::guard('web')->logout();
        $this->request->session()->flush();
        $this->request->session()->regenerate();

        //put the message into the new session after nuking the auth one above
        if ( !empty($msg) ){
            Session::put("logout-msg", $msg);
        }

        return Inertia::location($this->adb2cService->logout());
        //return redirect()->to($this->adb2cService->logout());
    }
}
