<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Adb2cController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\BlogManagementController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExpertManagementController;
use App\Http\Controllers\Admin\FarmerManagementController;
use App\Http\Controllers\Admin\InstallerProjectController;
use App\Http\Controllers\Admin\MaterialManagementController;
use App\Http\Controllers\Admin\RequestManagementController;
use App\Http\Controllers\Admin\InstallerManagementController;
use App\Http\Controllers\Admin\RegionManagementController;
use App\Http\Controllers\Admin\SettingsManagementController;
use App\Http\Controllers\Admin\StaticPageManagementController;
use App\Http\Controllers\FarmersLibraryController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FindAnExpertController;
use App\Http\Controllers\GeoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\ManageBlogController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ShedSolutionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('offline', function () {
    return view('vendor/laravelpwa/offline');
});

//Users
//Route::get('perms', [UsersController::class, 'perms'])->name('users.perms');
//Route::get('templogin/{email}', [UsersController::class, 'tempLogin'])->name('users.tempLogin');
//Route::get('templogout', [UsersController::class, 'tempLogout'])->name('users.tempLogout');

// ADB2C
Route::get('register', [Adb2cController::class, 'register'])->name('auth.register');
Route::get('login', [Adb2cController::class, 'login'])->name('auth.login');
Route::post('login/callback', [Adb2cController::class, 'callback'])->name('auth.callbackpost');
Route::post('auth/callback/{type}', [Adb2cController::class, 'newCallback'])->name('auth.callbackpost2');

Route::get('/', [HomeController::class, 'index'])->name('home');

//Route::middleware('auth')->group(function () {
Route::get('/home', [HomeController::class, 'index'])->name('authhome');
Route::get('logout', [Adb2cController::class, 'logout'])->name('auth.logout');

//Route::group(['middleware' => [\App\Http\Middleware\UserPolicy::class]], function () {
//    Route::post('users', [UsersController::class, 'store'])->name('users.store');
//    Route::get('users', [UsersController::class, 'list'])->name('users.list');
//    Route::get('users/{id}', [UsersController::class, 'show'])->name('users.show');
//    Route::delete('users/{id}', [UsersController::class, 'delete'])->name('users.delete');
//    Route::put('users/{id}', [UsersController::class, 'update'])->name('users.update');
//    Route::post('assign-role', [PermissionsController::class, 'assignRole'])->name('permissions.assign-role');
//    Route::post('revoke-role', [PermissionsController::class, 'revokeRole'])->name('permissions.revoke-role');
//    Route::post('assign-permission-role', [PermissionsController::class, 'assignPermissionRole'])->name('permissions.assign-permission-role');
//    Route::post('revoke-permission-role', [PermissionsController::class, 'revokePermissionRole'])->name('permissions.revoke-permission-role');
//});

//regions
Route::put('reassign_postcode', [GeoController::class, 'assignPostcodeToRegion'])->name('assign_postcode_region');
Route::put('unassign_postcode', [GeoController::class, 'unAssignPostcodeToRegion'])->name('unassign_postcode_region');

//Farmers Library
Route::prefix('farmers-library')
    ->name('farmers-library.')
    ->controller(FarmersLibraryController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/list', 'list')->name('list');
        Route::get('/{id}', 'view')->name('view');
        Route::post('/upload_cover', [FileController::class, 'upload_cover'])->name('upload_cover');
    });

// Shed Solutions
Route::prefix('shed-solution')
    ->name('shed-solution.')
    ->controller(ShedSolutionController::class)
    ->middleware('role:Installer|Farmer')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/save-shed-solution', 'saveShedSolution')->name('save-shed-solution');
        Route::get('/{shedSolutionId}/render', 'renderId')->name('render.id');
        Route::get('/render', 'renderSession')->name('render.session');
        Route::post('/store', 'store')->name('store');
        Route::post('/store-render/{id?}', 'storeRender')->name('store-render');
    });

//My Account
Route::prefix('my-account')
    ->name('my-account.')
    ->controller(AccountController::class)
    ->middleware('role:Installer|Farmer')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/profile', 'viewProfile')->name('profile');
        Route::post('/profile/save', 'updateProfile')->name('profile.save');
        Route::post('/edit-general', 'editGeneralSubmit')->name('edit-general.submit');
        Route::post('/delete-user', 'deleteUser')->name('delete-user');
        Route::get('/edit-general', 'editGeneral')->name('edit-general');
        Route::middleware('role:Installer')
            ->get('/edit-contact', 'editContact')
            ->name('edit-contact');
        Route::middleware('role:Installer')
            ->post('/edit-contact', 'saveContactPreferences')
            ->name('edit-contact.submit');
        Route::middleware('role:Installer')
            ->post('/profile/upload-logo', [FileController::class, 'uploadCompanyLogoImage'])
            ->name('upload.logo');
        Route::middleware('role:Installer')
            ->post('/profile/upload-gallery', [FileController::class, 'uploadInstallerProjectImage'])
            ->name('upload.gallery');
        Route::middleware('role:Installer')
            ->post('/profile/save-project', 'saveInstallerProject')
            ->name('installer-project.save');
        Route::middleware('role:Farmer|Installer')
            ->get('/shed-solutions', [ShedSolutionController::class, 'shedSolutions'])
            ->name('shed-solutions');
        Route::middleware('role:Farmer|Installer')
            ->get('/shed-solutions/{id}/view', [ShedSolutionController::class, 'shedSolutionDetail'])
            ->name('shed-solutions.view');
        Route::post('/shed-solutions/send', [ShedSolutionController::class, 'sendShedSolution'])
            ->name('send');
        Route::post('/shed-solutions/{id}/delete', [ShedSolutionController::class, 'delete'])->name('delete-shed-solution');
    });

Route::prefix('requests')
    ->name('requests.')
    ->controller(RequestController::class)
    ->middleware('permission:Installer_permission')
    ->middleware('role:Installer')
    ->group(function () {
        Route::get('/', 'index')->name('list');
        Route::get('/{requestId}/view', 'projectRequest')->name('view');
        Route::post('/{requestId}/update-status', 'updateRequestStatus')->name('update-request-status');
    });

Route::prefix('find-an-expert')
    ->name('find-an-expert.')
    ->controller(FindAnExpertController::class)
    ->group(function () {
        Route::get('/', 'index')->name('home');
        Route::get('/{expertType}', 'details')->name('details');
        Route::get('/view/{expert}', 'show')->name('show');
        Route::post('/{expert}/send-contact-requests', 'sendContactRequests')->name('send-contact-requests');
    });

Route::get('/installer-project/{projectId}', [FindAnExpertController::class, 'viewInstallerProject'])->name('projects.view');

Route::prefix('admin')
    ->name('admin.')
    ->middleware('role:SuperAdmin|Admin')
    ->group(function () {

        Route::post('/profile/upload-logo', [FileController::class, 'uploadCompanyLogoImage'])
            ->name('upload.logo');
        Route::get('/', [DashboardController::class, 'index'])
            ->name('dashboard');
        Route::group(['prefix' => '/manage-blog/', 'name' => 'blog.', 'middleware' => 'role:SuperAdmin|Admin'], function () {
            Route::match(['GET', 'POST'], '', [BlogManagementController::class, 'index'])
                ->name('blog.index');
            Route::get('add', [BlogManagementController::class, 'add'])
                ->name('blog.add');
            Route::post('/upload-image', [FileController::class, 'uploadPostImage'])
                ->name('upload-post-image');
            Route::get('{id}/view', [BlogManagementController::class, 'detail'])
                ->name('blog.view');
            Route::get('/add', [BlogManagementController::class, 'detail'])
                ->name('blog.add');
            Route::post('{id}/store-blog', [BlogManagementController::class, 'store'])
                ->name('.store-blog');
            Route::post('{id}/delete-blog', [BlogManagementController::class, 'permDeleteBlog'])
                ->name('.delete-blog');
            Route::post('/upload_cover', [FileController::class, 'upload_cover'])->name('upload_cover');
            Route::post('/upload_document', [FileController::class, 'uploadDocument'])->name('upload_document');
        });
        Route::group(['prefix' => '/manage-experts/', 'name' => 'manage-experts.', 'middleware' => 'role:SuperAdmin'], function () {
            Route::match(['GET', 'POST'], '', [ExpertManagementController::class, 'index'])
                ->name('manage-experts.index');
            Route::get('{id}/view', [ExpertManagementController::class, 'detail'])
                ->name('manage-experts.view');
            Route::get('add', [ExpertManagementController::class, 'detail'])
                ->name('manage-experts.add');
            Route::post('add-expert', [ExpertManagementController::class, 'store'])
                ->name('add-expert');
            Route::post('{id}/edit-expert', [ExpertManagementController::class, 'store'])
                ->name('edit-expert');
            Route::post('{id}/delete-expert', [ExpertManagementController::class, 'permDeleteExpert'])
                ->name('delete-expert');
        });
        Route::group(['prefix' => '/manage-admins/', 'name' => 'manage-admins.', 'middleware' => 'role:SuperAdmin'], function () {
            Route::match(['GET', 'POST'], '', [AdminManagementController::class, 'index'])
                ->name('manage-admins.index');
            Route::get('{id}/view', [AdminManagementController::class, 'detail'])
                ->name('manage-admins.view');
            Route::post('{id}/edit', [AdminManagementController::class, 'editRegion'])
                ->name('.edit-region');
            Route::get('/add', [AdminManagementController::class, 'detail'])
                ->name('manage-admins.add');
            Route::post('add-admin', [AdminManagementController::class, 'AddOrUpdateAdmin'])
                ->name('.add-admin');
            Route::post('{id}/edit-admin', [AdminManagementController::class, 'AddOrUpdateAdmin'])
                ->name('.edit-admin');
            Route::post('{id}/delete-admin', [AdminManagementController::class, 'permDeleteAdmin'])
                ->name('.delete-admin');
        });
        Route::group(['prefix' => '/manage-farmers/', 'name' => 'farmers.', 'middleware' => 'role:SuperAdmin|Admin'], function () {
            Route::match(['GET', 'POST'], '', [FarmerManagementController::class, 'index'])
                ->name('farmers.index');
            Route::get('{id}/view', [FarmerManagementController::class, 'detail'])
                ->name('farmers.view');
        });
        Route::group(['prefix' => '/manage-farmers/', 'name' => 'farmers.', 'middleware' => 'role:SuperAdmin'], function () {
            Route::post('{id}/edit-farmer', [FarmerManagementController::class, 'editFarmerDetails'])
                ->name('.edit-farmer');
            Route::post('{id}/delete-farmer', [FarmerManagementController::class, 'permDeleteFarmer'])
                ->name('.delete-farmer');
        });
        Route::group(['prefix' => '/manage-installers/', 'name' => 'installers.', 'middleware' => 'role:SuperAdmin|Admin'], function () {
            Route::match(['GET', 'POST'], '', [InstallerManagementController::class, 'index'])
                ->name('manage-installers.index');
            Route::get('{id}/view', [InstallerManagementController::class, 'detail'])
                ->name('manage-installers.view');
            Route::post('{id}/edit-installer', [InstallerManagementController::class, 'editInstallerDetails'])
                ->name('edit-installer');
        });
        Route::group(['prefix' => '/manage-installers/', 'name' => 'installers.', 'middleware' => 'role:SuperAdmin'], function () {
            Route::post('{id}/delete-installer', [InstallerManagementController::class, 'permDeleteInstaller'])
                ->name('delete-installer');
        });
        Route::group(['prefix' => '/requests/', 'name' => 'requests.', 'middleware' => 'role:SuperAdmin|Admin'], function () {
            Route::get('', [RequestManagementController::class, 'index'])
                ->name('requests.index');
            Route::get('{requestId}/view', [RequestManagementController::class, 'view'])
                ->name('requests.view');
            Route::post('{requestId}/update-status', [RequestManagementController::class, 'updateRequestStatus'])
                ->name('.update-status');
        });
        Route::group(['prefix' => '/installer-projects/', 'name' => 'installer-projects.', 'middleware' => 'role:SuperAdmin|Admin'], function () {
            Route::match(['GET', 'POST'], '', [InstallerProjectController::class, 'index'])
                ->name('installer-projects.index');
            Route::get('{id}/view', [InstallerProjectController::class, 'detail'])
                ->name('installer-projects.view');
            Route::post('{id}/edit', [InstallerProjectController::class, 'editInstallerProject'])
                ->name('.edit-project');
            Route::post('{id}/edit-project', [InstallerProjectController::class, 'editInstallerProject'])
                ->name('edit-project');
            Route::post('{id}/update-status', [InstallerProjectController::class, 'updatePublishedStatus'])
                ->name('update-project-status');
            Route::post('{id}/delete-project', [InstallerProjectController::class, 'permDeleteProject'])
                ->name('delete-project');
        });
        Route::group(['prefix' => '/manage-materials/', 'name' => 'manage-materials.', 'middleware' => 'role:SuperAdmin'], function () {
            Route::match(['GET', 'POST'], '', [MaterialManagementController::class, 'index'])
                ->name('manage-materials.index');
            Route::get('{id?}/edit', [MaterialManagementController::class, 'edit'])
                ->name('manage-materials.view');
            Route::get('/add', [MaterialManagementController::class, 'edit'])
                ->name('manage-materials.add');
            Route::post('/{id?}/edit-material', [MaterialManagementController::class, 'createOrUpdateMaterial'])
                ->name('edit-material');
            Route::post('/add-material', [MaterialManagementController::class, 'createOrUpdateMaterial'])
                ->name('add-material');
            Route::post('/{id}/delete-material', [MaterialManagementController::class, 'permDeleteMaterial'])
                ->name('delete-material');
            Route::post('/upload-image', [FileController::class, 'uploadMaterialImage'])
                ->name('upload-gallery');
        });
        Route::group(['prefix' => '/manage-regions/', 'name' => 'manage-regions.', 'middleware' => 'role:SuperAdmin'], function () {
            Route::match(['GET', 'POST'], '', [RegionManagementController::class, 'index'])
                ->name('manage-regions.index');
            Route::get('add', [RegionManagementController::class, 'add'])
                ->name('manage-regions.add');
            Route::get('{id}/view', [RegionManagementController::class, 'detail'])
                ->name('manage-regions.view');
            Route::post('/store-region', [RegionManagementController::class, 'addOrUpdateRegion'])
                ->name('add-region');
            Route::post('{id?}/edit-region', [RegionManagementController::class, 'addOrUpdateRegion'])
                ->name('edit-region');
            Route::post('{id}/delete-region', [RegionManagementController::class, 'permDeleteRegion'])
                ->name('delete-region');
        });
        Route::group(['prefix' => '/settings/', 'name' => 'settings.', 'middleware' => 'role:SuperAdmin'], function () {
            Route::match(['GET', 'POST'], '', [SettingsManagementController::class, 'index'])
                ->name('settings.index');
            Route::post('edit-settings', [SettingsManagementController::class, 'updateSettings'])
                ->name('.edit-settings');
        });

        Route::get('/blog/edit/{id}', [BlogManagementController::class, 'edit'])
            ->name('admin-blog-edit');

        Route::get('/blog/new/case-study', [BlogManagementController::class, 'new'])
            ->name('admin-blog-new-case-study');
        Route::group(['prefix' => '/manage-static-pages/', 'name' => 'static-page.', 'middleware' => 'role:SuperAdmin'], function () {
            Route::match(['GET', 'POST'], '', [StaticPageManagementController::class, 'index'])
                ->name('static-page.index');
            Route::get('{id}/edit', [StaticPageManagementController::class, 'edit'])
                ->name('static-page.edit');
            Route::post('{id}/edit-page', [StaticPageManagementController::class, 'updatePage'])
                ->name('.edit-page');
        });
    });

//Static Pages (ALWAYS LAST)
Route::get('/{id}', [PageController::class, 'view'])
    ->name('static_pages');
