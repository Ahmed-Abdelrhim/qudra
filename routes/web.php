<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepartmentsController;
use App\Http\Controllers\Admin\DesignationsController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\PreRegisterController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TypesController;
use App\Http\Controllers\Admin\VisitorController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\EnvironmentController;
use App\Http\Controllers\PurchaseCodeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['installed']], function () {
    Auth::routes(['verify' => false]);
});
Route::group(['prefix' => 'install', 'as' => 'LaravelInstaller::', 'middleware' => ['web', 'install']], function () {
    Route::post('environment/saveWizard', [
        'as'   => 'environmentSaveWizard',
        'uses' => [EnvironmentController::class,'saveWizard'],
    ]);

    Route::get('purchase-code', [
        'as'   => 'purchase_code',
        'uses' => [PurchaseCodeController::class,'index'],
    ]);

    Route::post('purchase-code', [
        'as'   => 'purchase_code.check',
        'uses' => [PurchaseCodeController::class,'action'],
    ]);
});

Route::redirect('/index.php/', '/index.php/admin/dashboard')->middleware('backend_permission');
Route::redirect('/admin', '/index.php/admin/dashboard')->middleware('backend_permission');

Route::group(['prefix' => 'admin', 'middleware' => ['installed'], 'namespace' => 'Admin', 'as' => 'admin.'], function () {
    Route::get('login', [LoginController::class,'showLoginForm']);
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'installed','backend_permission'], 'namespace' => 'Admin', 'as' => 'admin.'], function () {

    Route::get('dashboard', [DashboardController::class,'index'])->name('dashboard.index');

    Route::get('profile', [ProfileController::class,'index'])->name('profile');
    Route::put('profile/update/{profile}', [ProfileController::class,'update'])->name('profile.update');
    Route::put('profile/change', [ProfileController::class,'change'])->name('profile.change');
    Route::resource('adminusers', AdminUserController::class);
    Route::get('get-adminusers', [AdminUserController::class,'getAdminUsers'])->name('adminusers.get-adminusers');
    Route::resource('role', RoleController::class);
    Route::post('role/save-permission/{id}', [RoleController::class,'savePermission'])->name('role.save-permission');

    //designations
    Route::resource('designations', DesignationsController::class);
    Route::get('get-designations', [DesignationsController::class,'getDesignations'])->name('designations.get-designations');
	
	//types
    Route::resource('types', TypesController::class);
    Route::get('get-types', [TypesController::class,'getTypes'])->name('types.get-types');

    //departments
    Route::resource('departments', DepartmentsController::class);
    Route::get('get-departments', [DepartmentsController::class,'getDepartments'])->name('departments.get-departments');

    //employee route
    Route::resource('employees', EmployeeController::class);
    Route::get('get-employees', [EmployeeController::class,'getEmployees'])->name('employees.get-employees');
    Route::get('employees/get-pre-registers/{id}', [EmployeeController::class,'getPreRegister'])->name('employees.get-pre-registers');
    Route::get('employees/get-visitors/{id}', [EmployeeController::class,'getVisitor'])->name('employees.get-visitors');
    Route::put('employees/check/{id}',[EmployeeController::class,'checkEmployee'])->name('employees.check');

    //pre-registers
    Route::resource('pre-registers', PreRegisterController::class);
    Route::get('get-pre-registers', [PreRegisterController::class,'getPreRegister'])->name('pre-registers.get-pre-registers');

    //visitors
    Route::resource('visitors', VisitorController::class);
    Route::get('get-visitors', [VisitorController::class,'getVisitor'])->name('visitors.get-visitors');

    Route::group(['prefix' => 'setting', 'as' => 'setting.'], function () {

        Route::get('/', [SettingController::class,'index'])->name('index');
        Route::post('/', [SettingController::class,'siteSettingUpdate'])->name('site-update');
        Route::get('sms', [SettingController::class,'smsSetting'])->name('sms');
        Route::post('sms', [SettingController::class,'smsSettingUpdate'])->name('sms-update');
        Route::get('email', [SettingController::class,'emailSetting'])->name('email');
        Route::post('email', [SettingController::class,'emailSettingUpdate'])->name('email-update');
        Route::get('notification', [SettingController::class,'notificationSetting'])->name('notification');
        Route::post('notification', [SettingController::class,'notificationSettingUpdate'])->name('notification-update');
        Route::get('emailtemplate', [SettingController::class,'emailTemplateSetting'])->name('email-template');
        Route::post('emailtemplate', [SettingController::class,'mailTemplateSettingUpdate'])->name('email-template-update');
        Route::get('homepage', [SettingController::class,'homepageSetting'])->name('homepage');
        Route::post('homepage', [SettingController::class,'homepageSettingUpdate'])->name('homepage-update');
    });
});

/*Multi step form*/

Route::group(['middleware' => ['installed']], function () {
    Route::group(['middleware' => ['frontend']], function () {
        Route::get('/', [CheckInController::class,'index'])->name('/');

        Route::get('/check-in', [
            'as' => 'check-in',
            'uses' => [CheckInController::class,'index']
        ]);

        Route::get('/check-in/create-step-one', [
            'as' => 'check-in.step-one',
            'uses' => [CheckInController::class,'createStepOne']
        ]);
        Route::post('/check-in/create-step-one', [
            'as' => 'check-in.step-one.next',
            'uses' => [CheckInController::class,'postCreateStepOne']
        ]);

        Route::get('/check-in/create-step-two', [
            'as' => 'check-in.step-two',
            'uses' => [CheckInController::class,'createStepTwo']
        ]);
        Route::post('/check-in/create-step-two', [
            'as' => 'check-in.step-two.next',
            'uses' => [CheckInController::class,'store']
        ]);

        Route::get('/check-in/show/{id}', [
            'as' => 'check-in.show',
            'uses' => [CheckInController::class,'show']
        ]);
        Route::get('/check-in/return', [
            'as' => 'check-in.return',
            'uses' => [CheckInController::class,'visitor_return']
        ]);
        Route::post('/check-in/return', [
            'as' => 'check-in.find.visitor',
            'uses' => [CheckInController::class,'find_visitor']
        ]);

        Route::get('/check-in/pre-registered', [
            'as' => 'check-in.pre.registered',
            'uses' => [CheckInController::class,'pre_registered']
        ]);
        Route::post('/check-in/pre-registered', [
            'as' => 'check-in.find.pre.visitor',
            'uses' => [CheckInController::class,'find_pre_visitor']
        ]);
    });
});

// APP_URL=http://food-express.test:82