<?php

namespace App\Services;

use App\Contracts\Adb2cServiceInterface;
use App\Contracts\PermissionsRepositoryInterface;
use App\Contracts\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Manager\OAuth2\User as SocialiteUser;

class Adb2cService implements Adb2cServiceInterface
{
    private $serviceConfig = null;

    /**
     * @param  ConfigRepository  $configRepository
     * @param  UserRepositoryInterface  $userRepository
     * @param  PermissionsRepositoryInterface  $permissionsRepository
     */
    public function __construct(
        protected ConfigRepository $configRepository,
        protected UserRepositoryInterface $userRepository,
        protected PermissionsRepositoryInterface $permissionsRepository,
        protected Request $request
    ) {
        $this->serviceConfig = $this->configRepository->get('services.azureadb2c');
    }

    const LOGIN = 0;
    const PWRESET = 1;
    const REGISTER = 2;

    protected function buildProvider(int $type = 0)
    {
        $config = $this->serviceConfig;

        if ( $type === self::REGISTER ){
            $config['policy'] = env('ADB2C_REGISTER_POLICY');
        } elseif ( $type === self::PWRESET ){
            $config['policy'] = env('ADB2C_PWRESET_POLICY');
        }

        $config = new \SocialiteProviders\Manager\Config(
            $config['client_id'],
            $config['client_secret'],
            $config['redirect'],
            $config
        );
        if ( $type != 0 ) {
            //dd($config);

        }
        return Socialite::driver('azureadb2c')
            ->setConfig($config);
    }

    protected function setStateToSession(bool $reg = false)
    {
        $state = Str::uuid()->toString();
        $loginReferrer = '';
        $referrer = strtolower(request()->headers->get('referer'));
        $baseUrl = strtolower(Config::get('app.url'));
        if (strpos($referrer, $baseUrl) === 0) {
            $referrer = parse_url($referrer);
            $loginReferrer = $referrer['path'];
            //catch find-an-expert to retrigger the popup
            if (
                strpos($referrer['path'], '/find-an-expert/view/') == 0
                && ! empty($referrer['query'])
                && strpos($referrer['query'], 'contact=true') !== false
            ) {
                $loginReferrer .= '?contact=true';
            }
        }
        $stateInfo = [
            'policy' => $reg ? env('ADB2C_REGISTER_POLICY') : $this->serviceConfig['policy'],
            'referring_path' => $loginReferrer,
        ];
        Session::put($state, $stateInfo);

        return $state;
    }

    /**
     * @return RedirectResponse
     */
    public function login()
    {
        $state = $this->setStateToSession();

        $config = [
            'state' => $state,
            'scope' => $this->configRepository->get('services.azureadb2c.scope'),
            'salesorg' => $this->configRepository->get('services.azureadb2c.salesorg'),
            'epi_site' => $this->configRepository->get('services.azureadb2c.epi_site'),
            'response_type' => $this->configRepository->get('services.azureadb2c.response_type'),
            'response_mode' => $this->configRepository->get('services.azureadb2c.response_mode'),
            'append_policy_name_to_redirect_uri' => 'true',
            'application_host_name' => $this->configRepository->get('services.azureadb2c.application_host_name'),
            'nonce' => Str::uuid()->toString(),
        ];

        $driver = $this->buildProvider();

        return $driver->with($config)->redirect();
    }

    /**
     * @param string $returnReferrer
     * @return User|null
     */
    public function callback(string &$returnReferrer): ?User
    {
        //query state for policy
        $reg = false;
        $state = request()->post('state');
        if (! empty($state)) {
            $sessionState = Session::get($state);
            if (! empty($sessionState)) {
                if (! empty($sessionState['policy']) && $sessionState['policy'] === env('ADB2C_REGISTER_POLICY')) {
                    $reg = true;
                }
                if (! empty($sessionState['referring_path'])) {
                    $returnReferrer = $sessionState['referring_path'];
                }
                Session::remove($state);

                if(request()->post('error') == 'access_denied') {
                    return null;
                }
            }
        }

        $driver = $this->buildProvider($reg?2:0);
        $etexUser = $driver->user();

        $processedUser = $this->parseUserFromCallback($etexUser);
        $userModel = $this->userRepository->createOrUpdateUser($processedUser);
        $this->processRoleInformation($etexUser, $userModel);

        return $userModel;
    }


    /**
     * @param string $returnReferrer
     * @return User|null
     */
    public function callback2(string $mode, string &$returnReferrer): ?User
    {
        //query state for policy
        $reg = false;
        $state = request()->post('state');
        if (! empty($state)) {
            $sessionState = Session::get($state);
            if (! empty($sessionState)) {
                if (! empty($sessionState['referring_path'])) {
                    $returnReferrer = $sessionState['referring_path'];
                }
                Session::remove($state);

                if(request()->post('error') == 'access_denied') {
                    return null;
                }
            }
        }

        $type = ( strtolower($mode) === "register" ? 2 : ( strtolower($mode) === "password" ? 1 : 0 ) );
        $driver = $this->buildProvider($type);
        $etexUser = $driver->user();

        $processedUser = $this->parseUserFromCallback($etexUser);

        //if this is registreation attempt to update or create
        $userModel = $this->userRepository->createOrUpdateUser($processedUser, $type);

        $this->processRoleInformation($etexUser, $userModel);

        return $userModel;
    }

    /**
     * @return RedirectResponse
     */
    public function register(): RedirectResponse
    {
        $state = $this->setStateToSession(true);

        return $this->buildProvider(1)
            ->with([
                'state' => $state,
                'scope' => $this->configRepository->get('services.azureadb2c.scope'),
                'salesorg' => $this->configRepository->get('services.azureadb2c.salesorg'),
                'epi_site' => $this->configRepository->get('services.azureadb2c.epi_site'),
                'response_type' => 'code',
                'response_mode' => $this->configRepository->get('services.azureadb2c.response_mode'),
                'nonce' => Str::uuid()->toString(),
            ])
            ->redirect();
    }

    /**
     * @return string
     */
    public function logout(): string
    {
        return Socialite::driver('azureadb2c')->logout(URL::to('/'));
    }

    /**
     * @param  Socialite  $user
     * @return array
     */
    public function parseUserFromCallback(SocialiteUser $user): array
    {
        $userArray = [];
        $userArray['name'] = $user->getRaw()['firstName'].' '.$user->getRaw()['lastName'];
        $userArray['email'] = strtolower($user->getRaw()['emailaddress']);
        $userArray['token'] = $user->getId();

        return $userArray;
    }

    /**
     * @param  SocialiteUser  $user
     * @param  User  $userModel
     * @return void
     */
    protected function processRoleInformation(SocialiteUser $user, User $userModel): bool
    {
        $userRaw = $user->getRaw();
        $classification = array_key_exists('sfClassificationLevel1', $userRaw)
            ? $userRaw['sfClassificationLevel1']
            : 'NotFound';

        switch ($classification) {
            case 'Installer':
                $roleName = 'Installer';
                break;

            case 'NotFound':
                $roleName = 'Farmer';
                break;

            case 'Farmer':
                $roleName = 'Farmer';
                break;

            default:
                $roleName = 'Farmer';
                break;
        }
        if ($userModel->hasRole('Admin') || $userModel->hasRole('SuperAdmin')) {
            return true;
        }

        return $this->permissionsRepository->assignUserToRole($userModel, $roleName);
    }
}
