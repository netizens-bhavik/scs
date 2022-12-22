<?php


use App\Http\Controllers\EmailMaster;
use App\Http\Controllers\MomModeController;
use App\Http\Controllers\SystemStatsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\AssignLeads;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportMaster;
use App\Http\Controllers\MOMController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\CityController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransferClients;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\IndustryController;
use App\Http\Controllers\SoftCallController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotesMasterController;
use App\Http\Controllers\GmLeadsMailListController;

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

Route::get('/new-leads-cron', [GmLeadsMailListController::class, 'sendMail']);
Route::get('/reminder-mail', [NotesMasterController::class, 'reminder_mail']);
Route::get('/db_migrate', function () {
    Artisan::call('migrate:fresh --seed');
    return "Migrated";
});

Route::get('/schedule-followup-today', [EmailMaster::class, 'todays_followups'])->name('schedule-followup-today');


Route::group(['middleware' => 'prevent-back-history'], function () {

    Route::get('/', function () {
        return redirect('/login');
    });

    Route::get('/register', function () {
        return redirect('/login');
    });


    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller'])
        ->name('dashboard');

    Route::post(
        '/mom_stats',
        [SystemStatsController::class, 'mom_stats']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller'])
        ->name('dashboard.mom_stats');

    Route::post(
        '/get_followup_count',
        [SystemStatsController::class, 'get_followup_count']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller'])
        ->name('dashboard.get_followup_count');

    Route::post(
        '/get_mom_followups_data',
        [SystemStatsController::class, 'get_mom_followups_data']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller'])
        ->name('dashboard.get_mom_followups_data');

    Route::post(
        '/get_chart_data',
        [SystemStatsController::class, 'get_chart_data']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller'])
        ->name('dashboard.get_chart_data');

    Route::post(
        '/company_modal',
        [SystemStatsController::class, 'company_modal']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller'])
        ->name('dashboard.company_modal');

    Route::post(
        '/get_chart_data_view',
        [SystemStatsController::class, 'get_chart_data_view']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller'])
        ->name('dashboard.get_chart_data_view');

    Route::post(
        '/get_dashboard_country_users',
        [SystemStatsController::class, 'get_dashboard_country_users']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller'])
        ->name('dashboard.get_dashboard_country_users');


    Route::get(
        '/users',
        [DashboardController::class, 'user_master']
    )
        ->middleware([
            'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
            'permission:manage_users|manage_masters|user_view|user_add|user_edit|user_delete'
        ])
        ->name('user_master');

    Route::get(
        '/clients',
        [DashboardController::class, 'client_master']
    )
        ->middleware([
            'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
            'permission:manage_client|manage_masters|client_view|client_add|client_edit|client_delete'
        ])
        ->name('client_master');

    Route::get(
        '/countries',
        [DashboardController::class, 'country_master']
    )
        ->middleware([
            'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
            'permission:manage_country|manage_masters|country_view|country_add|country_edit|country_delete'
        ])
        ->name('country');

    Route::get(
        '/cities',
        [DashboardController::class, 'city_master']
    )
        ->middleware([
            'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
            'permission:manage_city|manage_masters|city_view|city_add|city_edit|city_delete'
        ])
        ->name('city_master');

    Route::get(
        '/industries',
        [DashboardController::class, 'industries_master']
    )
        ->middleware([
            'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
            'permission:manage_industries|manage_masters|industry_view|industry_add|industry_edit|industry_delete'
        ])
        ->name('industries_master');

    Route::get(
        '/mom_modes',
        [DashboardController::class, 'mom_modes']
    )
        ->middleware([
            'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
            'permission:manage_mom_mode|manage_masters|mom_mode_view|mom_mode_add|mom_mode_edit|mom_mode_delete'
        ])
        ->name('mom_modes');



    Route::get(
        '/import',
        [DashboardController::class, 'import_master']
    )
        ->middleware([
            'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
            'permission:mange_import_master|manage_soft_calling|soft_call_upload'
        ])
        ->name('import_master');

    Route::get(
        '/add_soft_call',
        [DashboardController::class, 'add_soft_call']
    )
        ->middleware([
            'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
            'permission:manage_masters|manage_soft_calling|soft_call_add|soft_call_view|soft_call_edit|soft_call_delete'
        ])
        ->name('add_soft_call');

    Route::get(
        '/incoming_dashboard',
        [DashboardController::class, 'incoming_dashboard']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_masters|manage_soft_calling|soft_call_incoming'])
        ->name('incoming_dashboard');

    Route::get(
        '/outgoing_dashboard',
        [DashboardController::class, 'outgoing_dashboard']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_masters|manage_soft_calling|soft_call_outgoing'])
        ->name('outgoing_dashboard');


    Route::get(
        '/assign_leads',
        [DashboardController::class, 'assign_leads']
    )->middleware([
        'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
        'permission:manage_masters|manage_soft_calling|soft_call_assign'
    ])
        ->name('assign_leads');


    Route::post(
        '/manage_users',
        [UserController::class, 'manage_users']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_masters|manage_users|user_add|user_edit'])
        ->name('manage_users');

    Route::post(
        '/permissions_check',
        [UserController::class, 'permissionsCheck']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_masters|manage_users|user_add|user_edit'])
        ->name('permissions_check');

    Route::get(
        '/change_password',
        [UserController::class, 'change_password']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller'])
        ->name('change_password');

    Route::post(
        '/update_password',
        [UserController::class, 'update_password']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller'])
        ->name('update_password');


    Route::post(
        '/get_users_list',
        [UserController::class, 'getUsersList']
    )
        ->middleware([
            'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
            'permission:manage_masters|manage_users|user_view|user_edit|user_delete'
        ])
        ->name('getUsersList');

    Route::post(
        '/add_user',
        [UserController::class, 'add_user']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_masters|manage_users|user_add|user_edit'])
        ->name('add_user');

    Route::post(
        '/report_to_user_list',
        [UserController::class, 'report_to_user_list']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_masters|manage_users|user_add|user_edit'])
        ->name('report_to_user_list');

    Route::post(
        '/edit_user',
        [UserController::class, 'edit_user']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_masters|manage_users|user_edit'])
        ->name('edit_user');

    Route::post(
        '/update_user',
        [UserController::class, 'update_user']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_masters|manage_users|user_edit'])
        ->name('update_user');

    Route::post(
        '/delete_user',
        [UserController::class, 'delete_user']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_masters|manage_users|user_delete'])
        ->name('delete_user');


    // Country Master Routes
    Route::post(
        '/get_country_list',
        [CountryController::class, 'getCountryList']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_country|manage_masters|country_view'])
        ->name('get_country_list');

    Route::post(
        '/manage_country',
        [CountryController::class, 'manageCountry']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_country|manage_masters|country_add|country_edit'])
        ->name('manage_country');

    Route::post(
        '/save_country',
        [CountryController::class, 'saveCountry']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_country|manage_masters|country_add|country_edit'])
        ->name('save_country');

    Route::post(
        '/delete_country',
        [CountryController::class, 'deleteCountry']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_country|manage_masters|country_delete'])
        ->name('delete_country');

    Route::post(
        '/get_industry_list',
        [IndustryController::class, 'getIndustryList']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_industries|manage_masters|industry_view|industry_edit|industry_delete'])
        ->name('get_industry_list');

    Route::post(
        '/manage_industry',
        [IndustryController::class, 'manageIndustry']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_industries|manage_masters|industry_add|industry_edit'])
        ->name('manage_industry');

    Route::post(
        '/save_industry',
        [IndustryController::class, 'saveIndustry']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_industries|manage_masters|industry_add|industry_edit'])
        ->name('save_industry');


    Route::post(
        '/delete_industry',
        [IndustryController::class, 'deleteIndustry']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_industries|manage_masters|industry_delete'])
        ->name('delete_industry');



    Route::post(
        '/get_mom_mode_list',
        [MomModeController::class, 'getMomModeList']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_mom_mode|manage_masters|mom_mode_view|mom_mode_edit|mom_mode_delete'])
        ->name('get_mom_mode_list');

    Route::post(
        '/manage_mom_mode',
        [MomModeController::class, 'manageMomMode']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_mom_mode|manage_masters|mom_mode_add|mom_mode_edit'])
        ->name('manage_mom_mode');

    Route::post(
        '/save_mom_mode',
        [MomModeController::class, 'saveMomMode']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_mom_mode|manage_masters|industry_add|mom_mode_edit'])
        ->name('save_mom_mode');


    Route::post(
        '/delete_mom_mode',
        [MomModeController::class, 'deleteMomMode']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_mom_mode|manage_masters|mom_mode_delete'])
        ->name('delete_mom_mode');




    //City master routes
    Route::post(
        '/get_city_list',
        [CityController::class, 'getCityList']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_city|manage_masters|city_view|city_delete|city_edit'])
        ->name('city_master');

    Route::post(
        '/manage_city',
        [CityController::class, 'manageCity']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_city|manage_masters|city_add|city_edit'])
        ->name('manage_city');

    Route::post(
        '/save_city',
        [CityController::class, 'saveCity']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_city|manage_masters|city_add|city_edit'])
        ->name('save_city');

    Route::post(
        '/delete_city',
        [CityController::class, 'deleteCity']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_city|manage_masters|city_delete'])
        ->name('delete_city');

    // Import Excel routes
    Route::post(
        '/import_data',
        [ImportController::class, 'importTempLeads']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_masters|manage_soft_calling|soft_call_upload'])
        ->name('import_data');

    //Client master routes
    Route::post(
        '/get_client_list',
        [ClientController::class, 'getClientList']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_client|manage_masters|client_view|client_edit|client_delete'])
        ->name('client_master');

    Route::post(
        '/manage_client',
        [ClientController::class, 'manageClient']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_client|manage_masters|client_add|client_edit'])
        ->name('manage_client');

    Route::post(
        '/get_city',
        [ClientController::class, 'getCity']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_client|manage_masters|client_add|client_edit'])
        ->name('get_city');

    Route::post(
        '/get_city_by_country_id',
        [DashboardController::class, 'get_city_by_country_id']
    )
        ->middleware(['auth'])
        ->name('get_city_by_country_id');

    Route::post(
        '/manage_add_edit_soft_calling',
        [SoftCallController::class, 'manage_add_edit_soft_calling']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_soft_calling|manage_masters|soft_call_add|soft_call_edit'])
        ->name('manage_add_edit_soft_calling');

    Route::post(
        '/outgoing_call_status',
        [SoftCallController::class, 'outgoing_call_status']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_soft_calling|manage_masters|soft_call_outgoing'])
        ->name('outgoing_call_status');

    Route::post(
        '/incoming_call_status',
        [SoftCallController::class, 'incoming_call_status']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_soft_calling|manage_masters|soft_call_incoming'])
        ->name('incoming_call_status');


    Route::post(
        '/save_client',
        [ClientController::class, 'saveClient']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_city|manage_client|manage_masters|client_add|client_edit'])
        ->name('save_client');

    Route::post(
        '/delete_contact_person',
        [ClientController::class, 'deleteContactPerson']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_client|manage_masters|client_add|client_delete'])
        ->name('delete_contact_person');

    Route::post(
        '/delete_client',
        [ClientController::class, 'deleteClient']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_client|manage_masters|client_add|client_delete'])
        ->name('delete_client');

    Route::post(
        '/search_temp_leads',
        [SoftCallController::class, 'search_temp_leads']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_soft_calling|manage_masters|soft_call_incoming'])
        ->name('search_temp_leads');

    Route::get(
        '/get-details/{id}',
        [SoftCallController::class, 'get_details']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_soft_calling|manage_masters|soft_call_incoming'])
        ->name('get-details');

    Route::post(
        '/get_temp_lead_list',
        [SoftCallController::class, 'get_temp_lead_list']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_soft_calling|manage_masters|soft_call_view|soft_call_edit|soft_call_delete'])
        ->name('get_temp_lead_list');

    Route::post(
        '/manage_soft_call_data',
        [SoftCallController::class, 'manage_soft_call_data']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_soft_calling|manage_masters|soft_call_add|soft_call_edit'])
        ->name('manage_soft_call_data');


    Route::post(
        '/delete_soft_call_data',
        [SoftCallController::class, 'delete_soft_call_data']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_soft_calling|manage_masters|soft_call_delete'])
        ->name('delete_soft_call_data');

    // BDM Lead routes
    Route::get(
        '/view_assinged_leads',
        [DashboardController::class, 'viewAssingedLeads']
    )
        ->middleware([
            'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
            'permission:manage_soft_calling|manage_masters|soft_call_view_assigned_leads'
        ])
        ->name('view_assinged_leads');

    Route::post(
        '/get_assigned_leads_list',
        [LeadController::class, 'getAssingedLeadsList']
    )
        ->middleware([
            'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
            'permission:manage_soft_calling|manage_masters|soft_call_view_assigned_leads'
        ])
        ->name('get_assigned_leads_list');

    Route::post(
        '/add_client_to_leads',
        [LeadController::class, 'AddClientToLead']
    )
        ->middleware([
            'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
            'permission:manage_soft_calling|manage_masters|soft_call_view_assigned_leads'
        ])
        ->name('add_client_to_leads');

    Route::post(
        '/add_mom_to_leads',
        [LeadController::class, 'AddMomToLead']
    )
        ->middleware([
            'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
            'permission:manage_soft_calling|manage_masters|soft_call_view_assigned_leads'
        ])
        ->name('add_mom_to_leads');

    // MOM routes
    Route::get(
        '/moms',
        [DashboardController::class, 'momMaster']
    )
        ->middleware([
            'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
            'permission:manage_masters|manage_mom|mom_view|mom_add|mom_edit|mom_delete'
        ])
        ->name('view_assinged_leads');

    Route::get(
        '/moms/{id}',
        [DashboardController::class, 'momMaster']
    )
        ->middleware([
            'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
            'permission:manage_masters|manage_mom|mom_view|mom_add|mom_edit|mom_delete'
        ])
        ->name('view_assinged_leads');

    Route::get(
        '/client_history', function () {
        return redirect()->back();
    })->name('client_history');


    Route::get(
        '/client_history/{id}',
        [DashboardController::class, 'client_history']
    )
        ->middleware([
            'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
            'permission:manage_masters|manage_mom|mom_view|mom_add|mom_edit|mom_delete'
        ])
        ->name('client_history');


    Route::post(
        '/get_mom_list',
        [MOMController::class, 'getMomList']
    )
        ->middleware([
            'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
            'permission:manage_masters|manage_mom|mom_view|mom_edit|mom_delete'
        ])
        ->name('get_mom_list');

    Route::post(
        '/manage_mom',
        [MOMController::class, 'manageMom']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller'])
        ->name('manage_mom');
    Route::post(
        '/delete_mom_job',
        [MOMController::class, 'deleteMomJob']
    )
        ->middleware([
            'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
            'permission:manage_masters|manage_mom|mom_edit|mom_delete'
        ])
        ->name('delete_mom_job');

    Route::post(
        '/save_mom',
        [MOMController::class, 'saveMOM']
    )
        ->middleware([
            'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
            'permission:manage_masters|manage_mom|mom_add|mom_edit'
        ])
        ->name('save_mom');

    Route::post(
        '/get_contact_persons',
        [MOMController::class, 'getContactPersons']
    )
        ->middleware([
            'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
            'permission:manage_masters|manage_mom|mom_add|mom_edit'
        ])
        ->name('get_contact_persons');

    Route::post(
        '/delete_mom',
        [MOMController::class, 'deleteMOM']
    )
        ->middleware([
            'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
            'permission:manage_masters|manage_mom|mom_delete'
        ])
        ->name('delete_mom');

    Route::get(
        '/clinet_jobs',
        [DashboardController::class, 'clinetJobs']
    )
        ->middleware([
            'auth', 'role:administrator|director|general manager|bde|bdm|softcaller'
        ])
        ->name('clinet_jobs');

    Route::post(
        '/get_client_job_list',
        [MOMController::class, 'getJobClientList']
    )
        ->middleware([
            'auth', 'role:administrator|director|general manager|bde|bdm|softcaller'
        ])
        ->name('get_client_list_for_jobs');

    Route::get(
        '/jobs/{id}',
        [MOMController::class, 'jobs']
    )
        ->middleware([
            'auth', 'role:administrator|director|general manager|bde|bdm|softcaller'
        ])
        ->name('jobs');

    Route::post(
        '/get_job_list',
        [MOMController::class, 'getJobList']
    )
        ->middleware([
            'auth', 'role:administrator|director|general manager|bde|bdm|softcaller'
        ])
        ->name('get_job_list');

    Route::post(
        '/change_job_status',
        [MOMController::class, 'changeJobStatus']
    )
        ->middleware([
            'auth', 'role:administrator|director|general manager|bde|bdm|softcaller'
        ])
        ->name('change_job_status');

    Route::get(
        '/transfer_client',
        [DashboardController::class, 'tranferClients']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller'])
        ->name('transfer_client');

    Route::post(
        '/transfer_clients',
        [TransferClients::class, 'transfer_clients']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller'])
        ->name('transfer_clients');

    Route::post('/get_transfer_to_user', [TransferClients::class, 'get_transfer_to_user'])
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller'])
        ->name('get_transfer_to_user');

    Route::post('/get_transfer_clients', [TransferClients::class, 'get_transfer_clients'])
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller'])
        ->name('get_transfer_clients');

    Route::post('/get_transfer_clients_list', [TransferClients::class, 'get_transfer_clients_list'])
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller'])
        ->name('get_transfer_clients_list');


    Route::get(
        '/notes',
        [DashboardController::class, 'notes_list']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller'])
        ->name('notes');

    Route::post(
        '/manage_notes',
        [NotesMasterController::class, 'manage_notes']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller'])
        ->name('manage_notes');

    Route::post(
        '/get_notes_list',
        [NotesMasterController::class, 'get_notes_list']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller'])
        ->name('get_notes_list');


    Route::post(
        '/save_notes',
        [NotesMasterController::class, 'save_notes']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller'])
        ->name('save_notes');

    Route::post(
        '/delete_notes',
        [NotesMasterController::class, 'delete_notes']
    )
        ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller'])
        ->name('delete_notes');


    Route::post(
        '/get_upcoming_assign_leads',
        [AssignLeads::class, 'get_upcoming_assign_leads']
    )
        ->middleware([
            'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
            'permission:manage_masters|manage_soft_calling|soft_call_assign'
        ])
        ->name('get_upcoming_assign_leads');

    Route::post(
        '/assign_leads',
        [AssignLeads::class, 'assign_leads']
    )
        ->middleware([
            'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
            'permission:manage_masters|manage_soft_calling|soft_call_assign'
        ])
        ->name('assign_leads');

    Route::get(
        '/mom_report',
        [ReportMaster::class, 'mom_report']
    )->middleware([
        'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
        'permission:manage_masters|manage_report|mom_report'
    ])->name('mom_report');

    Route::post(
        '/mom_report_data',
        [ReportMaster::class, 'mom_report_data']
    )->middleware([
        'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
    ])->name('mom_report_data');

    Route::post(
        '/get_mom_report_data',
        [ReportMaster::class, 'get_mom_report_data']
    )->middleware([
        'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
    ])->name('get_mom_report_data');

    Route::post(
        '/get_company_by_country',
        [ReportMaster::class, 'get_company_by_country']
    )->middleware([
        'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
    ])->name('get_company_by_country');

    Route::post(
        '/get_company_users',
        [ReportMaster::class, 'get_company_users']
    )->middleware([
        'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
    ])->name('get_company_users');

    Route::post(
        '/export_mom_data',
        [ReportMaster::class, 'export_mom_data']
    )->middleware([
        'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
    ])->name('export_mom_data');


    Route::post(
        '/mom_report_export',
        [ReportMaster::class, 'export_mom_data']
    )->middleware([
        'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
    ])->name('mom_report_export');


    Route::get(
        '/call_status_report',
        [ReportMaster::class, 'call_status_report']
    )->middleware([
        'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
        'permission:manage_masters|manage_report|call_status_report'
    ])->name('call_status_report');


    Route::post(
        '/call_status_report_export',
        [ReportMaster::class, 'call_status_report_export']
    )->middleware([
        'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
    ])->name('call_status_report_export');


    Route::post(
        '/call_status_report_country_user',
        [ReportMaster::class, 'call_status_report_country_user']
    )->middleware([
        'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
    ])->name('call_status_report_country_user');


    Route::post(
        '/call_status_report_table_view',
        [ReportMaster::class, 'call_status_report_table_view']
    )->middleware([
        'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
    ])->name('call_status_report_table_view');

    Route::post(
        '/get_call_status_report_data',
        [ReportMaster::class, 'get_call_status_report_data']
    )->middleware([
        'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
    ])->name('get_call_status_report_data');

    Route::get(
        '/call_status_uw_report',
        [ReportMaster::class, 'call_status_uw_report']
    )->middleware([
        'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
        'permission:manage_masters|manage_report|call_status_uw_report'
    ])->name('call_status_uw_report');

    Route::post(
        '/call_status_uw_report_export',
        [ReportMaster::class, 'call_status_uw_report_export']
    )->middleware([
        'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
    ])->name('call_status_uw_report_export');

    Route::post(
        '/call_status_uw_report_table_view',
        [ReportMaster::class, 'call_status_uw_report_table_view']
    )->middleware([
        'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
    ])->name('call_status_uw_report_table_view');

    Route::post(
        '/get_call_status_uw_report_data',
        [ReportMaster::class, 'get_call_status_uw_report_data']
    )->middleware([
        'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
    ])->name('get_call_status_uw_report_data');

    Route::get(
        '/client_report',
        [ReportMaster::class, 'client_report']
    )->middleware([
        'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
        'permission:manage_masters|manage_report|client_status_report'
    ])->name('client_report');

    Route::post(
        '/client_status_report_export',
        [ReportMaster::class, 'client_status_report_export']
    )->middleware([
        'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
    ])->name('client_status_report_export');

    Route::post(
        '/client_status_report_table_view',
        [ReportMaster::class, 'client_status_report_table_view']
    )->middleware([
        'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
    ])->name('client_status_report_table_view');

    Route::post(
        '/client_status_report_country_change',
        [ReportMaster::class, 'client_status_report_country_change']
    )->middleware([
        'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
    ])->name('client_status_report_country_change');

    Route::post(
        '/get_client_status_report_data',
        [ReportMaster::class, 'get_client_status_report_data']
    )->middleware([
        'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
    ])->name('get_client_status_report_data');

    Route::get(
        '/get_company_contact_person/{id}',
        [ReportMaster::class, 'get_company_contact_person']
    )->middleware([
        'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
    ])->name('get_company_contact_person');

    Route::get('/get_company_contact_person', function () {
        return redirect('/#');
    });

    Route::post(
        '/call_status_report_export_check',
        [ReportMaster::class, 'call_status_report_export_check']
    )->middleware([
        'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
    ])->name('call_status_report_export_check');

    Route::post(
        '/mom_report_export_check',
        [ReportMaster::class, 'mom_report_export_check']
    )->middleware([
        'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
    ])->name('mom_report_export_check');

    Route::post(
        '/call_status_uw_report_export_check',
        [ReportMaster::class, 'call_status_uw_report_export_check']
    )->middleware([
        'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
    ])->name('call_status_uw_report_export_check');


    Route::post(
        '/client_status_report_export_check',
        [ReportMaster::class, 'client_status_report_export_check']
    )->middleware([
        'auth', 'role:administrator|director|general manager|bde|bdm|softcaller',
    ])->name('client_status_report_export_check');


    Route::get('logout', function () {
        Auth::logout();
        return redirect('/');
    })->name('logout');
});

Route::get(
    '/export_data',
    [ExportController::class, 'exportTempLeads']
)
    ->middleware(['auth', 'role:administrator|director|general manager|bde|bdm|softcaller', 'permission:manage_masters|manage_soft_calling|soft_call_upload'])
    ->name('export_data');


require __DIR__ . '/auth.php';
