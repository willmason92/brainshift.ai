<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\UpdateSettingsRequest;
use App\Models\Settings as AppSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Config;
use Inertia\Inertia;
use Inertia\Response;

class SettingsManagementController extends Controller
{
    /**
     * Redirect back to login if user is unauthenticated
     *
     * BlogManagementController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Page to list all settings
     *
     * @return Response
     */
    public function index()
    {


        //env variables (read only)
        $data = [
            'auth.ADB2C_CLIENTID' => env('ADB2C_CLIENTID',''),
            'auth.ADB2C_CLIENTSECRET' => preg_replace(
                '/(?<=.{3}).(?=.{3})/',
                '*',
                env('ADB2C_CLIENTSECRET','')
            ),
            'auth.ADB2C_TENANT' => env('ADB2C_TENANT',''),

            'auth.ADB2C_POLICY' => env('ADB2C_POLICY',''),
            'auth.ADB2C_REDIRECTURI' => env('ADB2C_REDIRECTURI',''),
            'auth.ADB2C_REGISTER_POLICY' => env('ADB2C_REGISTER_POLICY',''),
            'auth.ADB2C_REGISTER_REDIRECTURI' => env('ADB2C_REGISTER_REDIRECTURI',''),
            'auth.ADB2C_LOGOUT_POLICY' => env('ADB2C_LOGOUT_POLICY',''),

            'auth.ADB2C_DOMAIN' => env('ADB2C_DOMAIN', ''),
            'auth.ADB2C_SALESORG' => env('ADB2C_SALESORG', ''),
            'auth.ADB2C_EPI_SITE' => env('ADB2C_EPI_SITE', ''),
            'auth.ADB2C_SCOPE' => env('ADB2C_SCOPE', ''),
            'auth.ADB2C_RESPONSE_TYPE' => env('ADB2C_RESPONSE_TYPE', ''),
            'auth.ADB2C_RESPONSE_MODE' => env('ADB2C_RESPONSE_MODE', ''),

            'email.SENDGRID_API_KEY' => env('SENDGRID_API_KEY',''),
            'email.SG_NEW_REQUEST_ID' => env('SG_NEW_REQUEST_ID',''),
            'email.SG_EDIT_PROJECT_ID' => env('SG_EDIT_PROJECT_ID',''),
            'email.SG_DELETE_USER_ID' => env('SG_DELETE_USER_ID',''),
            'email.SG_PUBLISH_PROJECT_ID' => env('SG_PUBLISH_PROJECT_ID',''),
            'email.SG_SHARE_SOLUTION_ID' => env('SG_SHARE_SOLUTION_ID',''),
            'email.SG_EDIT_USER_ID' => env('SG_EDIT_USER_ID','')
        ];

        return Inertia::render('Admin/SettingsManagement/Index', [
            'data' => array_merge(
                $data,
                AppSettings::where('key', 'not like', 'static_page.%')->pluck('value','key')->toArray(), //settable/saveable
            )
        ]);
    }

    /**
     *
     *
     * Method to update all settings
     *
     * @param UpdateSettingsRequest $request
     * @return RedirectResponse
     */
    public function updateSettings(UpdateSettingsRequest $request)
    {
        $validated = $request->validated();

        $keysToUpdate = [
            'contact.company_name',
            'contact.company_group',
            'contact.address1',
            'contact.city',
            'contact.country',
            'contact.postcode',
            'contact.vat_number',
            'contact.phone',
            'contact.footer_text',
            'contact.contact_form_url',
            'shop.materials_cta_link'
        ];

        foreach ($keysToUpdate as $key) {
            $keyParts = explode('.', $key);
            $firstPart = array_shift($keyParts);
            $formattedKey = $firstPart . '["' . implode('"]["', $keyParts) . '"]';
            AppSettings::where('key', $key)->update([
                'value' => $validated[$firstPart][$keyParts[0]],
            ]);
        }

        return redirect()->route('admin.dashboard')->with('message', [
            'type' => 'success',
            'msg' => 'The settings have been saved successfully',
        ]);
    }
}
