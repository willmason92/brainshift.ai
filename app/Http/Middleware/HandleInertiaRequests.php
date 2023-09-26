<?php

namespace App\Http\Middleware;

use App\Models\Request as InstallerRequest;
use Facades\App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {

        $messaging = $request->session()->get('logout-msg');
        if ( empty($messaging) ){
            $request->session()->get('message');
        } else {
            Session::remove('logout-msg');
        }

        $share = array_merge(parent::share($request), [

            // get current url for redirect back after login
            'currentUrl' => url()->current(),
            'previousUrl' => url()->previous(),

            //locale info
            'locale' => App()->getLocale(),
            'fallbackLocale' => config('app.fallback_locale'),

            //footer component settings
            'company.name' => Settings::get('contact.company_name') ?: null,
            'company.group' => Settings::get('contact.company_group') ?: null,
            'company.vat_number' => Settings::get('contact.vat_number') ?: null,
            'company.contact.address1' => Settings::get('contact.address1') ?: null,
            'company.contact.city' => Settings::get('contact.city') ?: null,
            'company.contact.county' => Settings::get('contact.county') ?: null,
            'company.contact.postcode' => Settings::get('contact.postcode') ?: null,
            'company.contact.url' => Settings::get('contact.url') ?: null,
            'company.contact.phone' => Settings::get('contact.phone') ?: null,

            //file uploads
            'flash' => [
                'file-uploads' => fn () => $request->session()->get('file-uploads'),
                'message' => $messaging,
            ],

            'coverImage' => fn () => $request->session()->get('coverImage'),

            'gMapsApiKey' => env('GOOGLE_MAPS_API_KEY'),

        ]);

        //user/role
        $user = Auth::user();
        $installerAdds = [
            'installer.profile_complete' => false,
            'installer.new_request_count' => 0,
        ];
        if ($user && $user->hasRole('Installer')) {
            $user->load('expert');
            if (! empty($user->expert)) {
                $installerAdds['installer.profile_complete'] = true;
                $installerAdds[] = InstallerRequest::getNewRequestCount($user->expert->id);
            }
        }

        $share = array_merge($share, [
            'user' => ($user
                ? [
                    'name' => $user?->name,
                    'email' => $user?->email, //Todo : for testing
                    'phone' => $user?->phone, //Todo : for testing
                    'contact_by_email' => $user?->contact_by_email, //Todo : for testing
                    'roles' => array_map('strtolower', $user->getRoleNames()->toArray()),
                ]
                : null
            ),
        ], $installerAdds);

        return $share;
    }
}
