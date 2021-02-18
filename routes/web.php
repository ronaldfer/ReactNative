<?php

use Illuminate\Support\Facades\Route;
use Spatie\WelcomeNotification\WelcomesNewUsers;
use App\Http\Controllers\Auth\MyWelcomeController;
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

Route::get('/command',function(){
	 \Artisan::call('GetOneDriveProjects:cron');

    dd('cache clear successfully');
});
Route::get('/pallets','Admin\PalletsFileData\PalletsFileDataController@index');

// Route::get('/', 'HomeController@welcome');
Route::get('/signin', 'MicrosoftOnedrive\OnedriveAuth@signin');
Route::get('/callback', 'MicrosoftOnedrive\OnedriveAuth@callback');
Route::get('/signout', 'MicrosoftOnedrive\OnedriveAuth@signout');
// Route::get('/calendar', 'CalendarController@calendar');
// Route::get('/release/{id}', 'CalendarController@releaseFolder');

Route::get('pm-login', 'Auth\LoginController@showLoginForm')->name('login');
Route::get('admin', 'Auth\LoginController@showLoginForm')->name('admin-login');

Route::post('pm-login', 'Auth\LoginController@login');
Route::post('pm-admin', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('pm-register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('pm-register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

// Route::get('/', 'HomeController@index')->name('home');
Route::post('/create-project-manager', 'ProjectManager\ProjectManagerController@store')->name('save-project-manager');
Route::post('/create-company', 'Company\CompanyController@save')->name('save-company');
Route::post('/check-company-name','Company\CompanyController@checkCompanyName');
Route::get('/home', 'HomeController@index')->name('home');

/*common routing*/
Route::get('/admin/view-project-manager/{id}','Admin\ProjectManager\ProjectManagerController@show');

Route::get('/edit-my-profile','HomeController@changeMyProfile')->name('change-my-profile');
Route::post('/update-my-profile','HomeController@updateMyProfile')->name('update-my-profile');

Route::get('/change-password','HomeController@changePassword')->name('change-password');
Route::post('/update-password','HomeController@updatePassword')->name('update-password');

/*check email address*/
Route::post('/check-email-address','AjaxController@get_email');
/*check company name*/
// Route::get('/check-company-name/{id}','AjaxController@check_company_name');

/*view project release*/
Route::get('/view-project-release/{id}','Admin\ProjectsRelease\ProjectsReleaseController@show');

Route::get('/', 'HomeController@index')->name('home');

/*dowwnload file*/
Route::get('download-file1', 'HomeController@forceDonwload')->name('download-file');

Route::group(['middleware' => ['auth','checkRole:Admin']], function(){

	/*start microsoft api url*/
	Route::get('signin', 'MicrosoftOnedrive\OnedriveAuth@signin');
	Route::get('callback', 'MicrosoftOnedrive\OnedriveAuth@callback');
	Route::get('signout', 'MicrosoftOnedrive\OnedriveAuth@signout');
	// Route::get('calendar', 'CalendarController@calendar');
	// Route::get('release/{id}', 'CalendarController@releaseFolder');

	/*end microsoft api url*/



	/* start project manager details routing*/
	Route::post('/change-status', 'Admin\ProjectManager\ProjectManagerController@changeStatus');
	Route::get('/admin/all-project-manager', 'Admin\ProjectManager\ProjectManagerController@index')->name('admin.all-project-manager');

	Route::get('/admin/create-project-manager', 'Admin\ProjectManager\ProjectManagerController@create')->name('admin.create-project-manager');
	Route::post('/admin/save-project-manager', 'Admin\ProjectManager\ProjectManagerController@save')->name('admin.save-project-manager');

	Route::get('/admin/edit-project-manager/{id}','Admin\ProjectManager\ProjectManagerController@edit')->name('admin.edit-project-manager');
	Route::post('/admin/update-project-manager','Admin\ProjectManager\ProjectManagerController@update')->name('admin.update-project-manager');

	Route::get('/admin/delete-project-manager/{id}','Admin\ProjectManager\ProjectManagerController@destroy')->name('admin.delete-project-manager');
	Route::get('/admin/delete-company/{id}','Admin\Company\CompanyController@destroy')->name('admin.delete-company');
	/*start company details routing*/
	Route::get('/admin/all-company', 'Admin\Company\CompanyController@index')->name('admin.all-company');

	Route::get('/admin/create-company', 'Admin\Company\CompanyController@create')->name('admin.create-company');
	Route::post('/admin/save-company', 'Admin\Company\CompanyController@save')->name('admin.save-company');

	Route::get('/admin/edit-company/{id}','Admin\Company\CompanyController@edit')->name('admin.edit-company');
	Route::post('/admin/update-company','Admin\Company\CompanyController@update')->name('admin.update-company');

	Route::get('/admin/view-company/{id}','Admin\Company\CompanyController@show');

	/*project details routing*/
	Route::get('/admin/view-all-projects', 'Admin\Project\ProjectController@index')->name('admin.all-projects');

	Route::get('/admin/create-projects', 'Admin\Project\ProjectController@create')->name('admin.create-projects');
	Route::post('/admin/save-projects', 'Admin\Project\ProjectController@save')->name('admin.save-projects');

	Route::get('/admin/edit-projects/{id}', 'Admin\Project\ProjectController@edit')->name('admin.edit-projects');
	Route::post('/admin/update-projects', 'Admin\Project\ProjectController@update')->name('admin.update-projects');

	Route::get('/admin/view-projects/{id}','Admin\Project\ProjectController@show');
	Route::get('/admin/delete-projects/{id}', 'Admin\Project\ProjectController@destroy')->name('admin.delete-projects');

	Route::post('/check-project-job-number','Admin\Project\ProjectController@checkJobnumber');

	/*Projects release routing*/
	Route::get('/admin/projects-manager/projects-releases/{id}','Admin\ProjectsRelease\ProjectsReleaseController@index')->name('admin.projectManager.projectsReleases');

	/*download projects release*/
	Route::get('download-releases/{id}','Admin\ProjectsRelease\ProjectsReleaseController@downlaodRelease')->name('admin.download-release');

	/*Projects release notes routing*/
	Route::get('/admin/projects-manager/projects-releases/projects-releases-notes/{id}','Admin\ProjectsRelease\ProjectReleaseNotes\ProjectsReleaseNotesController@index')->name('admin.project-manager.projects-releases.project-release-note');

	/* get pm based on company id*/
	Route::get('/get-pm/{id}','AjaxController@get_company');

	/*export all projects*/
	Route::get('export-projects', 'Admin\Project\ProjectController@export')->name('export-projects');
});

/*Project Manager control portal */
Route::group(['middleware' =>['auth','web','checkRole:ProjectManager']], function(){

	Route::get('/pm/view-projects','ProjectManager\Projects\ProjectController@index')->name('pm.view-projects');
	/*pm get project release*/
	Route::get('/pm/view-project-release-list/{id}','ProjectManager\ProjectRelease\ProjectReleaseController@index')->name('pm.view-project-release');
	Route::get('/pm/view-projects-release-notes/{id}','ProjectManager\ProjectReleaseNotes\ProjectReleaseNotesController@index')->name('pm.project-release-note');

	/* Pm get plans */
	Route::get("/pm/view-projects-plans-notes/{id}","ProjectManager\ProjectReleaseNotes\ProjectReleaseNotesController@viewPlans")->name("pm.project-plans-note");
	/*create relase notes*/
	Route::post('/pm/create-release-notes','Admin\ProjectsRelease\ProjectReleaseNotes\ProjectsReleaseNotesController@store')->name('pm.create-release-notes');
	/*download release file*/
	/*download projects release*/
	Route::get('download-release/{id}','Admin\ProjectsRelease\ProjectsReleaseController@downlaodRelease')->name('pm.download-project-release-pdf');

});