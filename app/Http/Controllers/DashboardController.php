<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MOMController;
use auth;
use App\Models\City;
use App\Models\User;
use App\Models\Client;
use App\Models\Country;
use App\Models\Industry;
use App\Models\TempLeads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\SystemLogController;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class DashboardController extends Controller
{
    public function index()
    {
        $selected_menu = 'dashboard';

        $user = FacadesAuth::user();
        $user_data = array();
        $user_data['user_id'] = $user->id;
        $user_data['user_name'] = $user->name;
        $user_data['user_email'] = $user->email;
        $user_data['user_role'] = $user->roles()->first()->name;

        $MOMController = new MOMController;
        $userIds = $MOMController->getHirarchyUser($user->id);
        $userIds[] = $user->id;

        $logged_user_role = FacadesAuth::user()->getRoleNames()->first();

        if ($logged_user_role == 'general manager') {
            $country_id = FacadesAuth::user()->country_id;
            $all_country = Country::where('id', $country_id)->get()->toArray();
        } else {
            $all_country = Country::all()->toArray();
        }

        //$all_users = User::whereIn('id', $userIds)->get()->toArray();
        $all_users = DB::table('users')
            ->whereIn('users.id', $userIds)
            ->leftJoin('countries', 'countries.id', '=', 'users.country_id')
            ->leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->leftJoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->select('users.*', 'countries.country_name as country_name', 'roles.name as role_name')
            ->get();

        $all_users = json_decode(json_encode($all_users), true);


        // $all_users = User::all()->toArray();

        return view('backend.index', compact('selected_menu', 'user_data', 'all_country', 'all_users'));
    }

    public function user_master()
    {
        $selected_menu = 'users';
        //select all users with roles where is_deleted=0
        $users = User::with('roles')->where('is_deleted', null)->get()->toArray();
        $country = Country::all()->toArray();
        $city = City::all()->toArray();
        $user_count = User::where('is_deleted', null)->count();

        $logData = array(
            'user_id' => auth()->user()->id,
            'action_type' => 'view',
            'module' => 'user',
            'description' => auth()->user()->name . ' viewed user master',
        );

        $storeLog = SystemLogController::addLog($logData);

        return view('backend.masters.user_master', compact('users', 'country', 'city', 'user_count', 'selected_menu'));
    }

    public function client_master()
    {
        $selected_menu = 'clients';
        $client_count = Client::where('is_deleted', null)->count();
        $logData = array(
            'user_id' => auth()->user()->id,
            'action_type' => 'view',
            'module' => 'client',
            'description' => auth()->user()->name . ' viewed client master',
        );

        $storeLog = SystemLogController::addLog($logData);
        return view('backend.masters.client_master', compact('client_count', 'selected_menu'));
    }

    public function city_master()
    {
        $selected_menu = 'cities';
        $city_count = City::where('is_deleted', null)->count();
        $logData = array(
            'user_id' => auth()->user()->id,
            'action_type' => 'view',
            'module' => 'city',
            'description' => auth()->user()->name . ' viewed city master',
        );
        $storeLog = SystemLogController::addLog($logData);
        return view('backend.masters.city_master', compact('city_count', 'selected_menu'));
    }

    public function country_master()
    {
        $selected_menu = 'countries';
        $country_count = Country::where('is_deleted', null)->count();
        $logData = array(
            'user_id' => auth()->user()->id,
            'action_type' => 'view',
            'module' => 'country',
            'description' => auth()->user()->name . ' viewed country master',
        );

        $storeLog = SystemLogController::addLog($logData);
        return view('backend.masters.country_master', compact('country_count', 'selected_menu'));
    }

    public function industries_master()
    {
        $selected_menu = 'industries';
        $industry_count = Industry::where('is_deleted', null)->count();
        $logData = array(
            'user_id' => auth()->user()->id,
            'action_type' => 'view',
            'module' => 'industry',
            'description' => auth()->user()->name . ' viewed industry master',
        );
        $storeLog = SystemLogController::addLog($logData);
        return view('backend.masters.industries_master', compact('industry_count', 'selected_menu'));
    }

    public function import_master()
    {
        $selected_menu = 'import';
        $logData = array(
            'user_id' => auth()->user()->id,
            'action_type' => 'view',
            'module' => 'import',
            'description' => auth()->user()->name . ' viewed import master',
        );
        $storeLog = SystemLogController::addLog($logData);
        return view('backend.soft_calling.import_master', compact('selected_menu'));
    }

    public function add_soft_call()
    {
        $selected_menu = 'add_soft_call';
        $logData = array(
            'user_id' => auth()->user()->id,
            'action_type' => 'view',
            'module' => 'softcall',
            'description' => auth()->user()->name . ' viewed add soft call master',
        );
        return view('backend.soft_calling.add_soft_call', compact('selected_menu'));
    }

    public function incoming_dashboard()
    {
        $selected_menu = 'incoming_dashboard';
        $logData = array(
            'user_id' => auth()->user()->id,
            'action_type' => 'view',
            'module' => 'incoming_dashboard',
            'description' => auth()->user()->name . ' viewed incoming dashboard',
        );
        $storeLog = SystemLogController::addLog($logData);
        return view('backend.soft_calling.incoming_dashboard', compact('selected_menu'));
    }

    function momMaster($id = null)
    {
        $mom_user_id = (int)$id ?? 0;
        $selected_menu = 'moms';
        $logData = array(
            'user_id' => auth()->user()->id,
            'action_type' => 'view',
            'module' => 'mom',
            'description' => auth()->user()->name . ' viewed mom master',
        );
        $storeLog = SystemLogController::addLog($logData);
        return view('backend.mom.mom_master', compact('selected_menu', 'mom_user_id'));
    }

    function client_history($id = null)
    {
        $client_id = (int)$id ?? 0;
        $selected_menu = 'client_history';

        $client_data = DB::table('clients')
            ->select('clients.*', 'users.name as user_name')
            ->leftJoin('users', 'users.id', '=', 'clients.manage_by')
            ->where('clients.id', $client_id)
            ->get()->toArray();


        if ($client_data) {
            $client_data = (array)$client_data;

            $client_mom_data = DB::table('moms')
                ->leftJoin('users as u2', 'u2.id', '=', 'moms.created_by')
                ->select('moms.*', 'u2.name as created_by_name')
                ->where('moms.client_id', $client_id)
                ->get()->toArray();
            $client_data['moms'] = $client_mom_data;

        } else {
            echo "No data found";
        }

        return view('backend.client_history.client_history', compact('selected_menu', 'client_data'));
    }

    public function outgoing_dashboard()
    {
        $current_date_time = date('Y-m-d H:i:s.000', time());
        $user_data = FacadesAuth::user()->toArray();
        $soft_caller_id = $user_data['id'];
        $calling_status_list = array();
        $calling_status_list[0] = "Not Called";
        $calling_status_list[1] = "Busy";
        $calling_status_list[2] = "Call Later";
        $calling_status_list[3] = "Called";
        $calling_status_list[4] = "Do Not Call Again";
        $calling_status_list[5] = "No Requirement";
        $calling_status_list[6] = "Not Reachable";
        $calling_status_list[7] = "Out OF Service";
        $calling_status_list[8] = "Ringing";
        $calling_status_list[9] = "Swich Off";
        $calling_status_list[10] = "Wrong Number";

        $temp_already_assign_lead = TempLeads::where('is_deleted', null)
            ->where('is_assigned', 1)
            ->where('tele_caller_id', $soft_caller_id)
            ->where('calling_status', '<>', '10')
            ->where('calling_status', '<>', '3')
            ->first();

        $priority_temp_leads = TempLeads::where('is_deleted', null)
            ->where('is_assigned', 0)
            ->where('calling_status', '<>', '3')
            ->where('calling_status', '<>', '10')
            ->whereDate('recalling_date', '<=', $current_date_time)
            ->whereTime('recalling_date', '<=', $current_date_time)
            ->orderBy('recalling_date', 'asc')
            ->first();

        if ($temp_already_assign_lead) {

            $temp_leads = TempLeads::where('id', $temp_already_assign_lead->id)->first();
            if ($temp_leads) {
                $temp_leads = $temp_leads->toArray();

                $company_country = Country::where('id', $temp_leads['company_country_id'])->first()->toArray();
                $company_city = City::where('id', $temp_leads['company_city_id'])->first()->toArray();
                $industry = Industry::where('id', $temp_leads['industry_id'])->first()->toArray();
                $last_called_by = User::where('id', $temp_leads['last_tele_caller_id'])->first();

                if ($last_called_by) {
                    $last_called_by = $last_called_by->toArray();
                    $temp_leads['last_called_by'] = $last_called_by['name'];
                } else {
                    $last_called_by = array();
                    $temp_leads['last_called_by'] = 'N/A';
                }

                $temp_leads['company_country'] = $company_country['country_name'];
                $temp_leads['country_code'] = $company_country['country_code'];
                $temp_leads['company_city'] = $company_city['city_name'];
                $temp_leads['industry'] = $industry['industry_name'];
                $temp_leads['calling_status'] = $calling_status_list[$temp_leads['calling_status']];
            } else {
                $temp_leads = [];
            }

            $selected_menu = 'outgoing_dashboard';

            $logData = array(
                'user_id' => auth()->user()->id,
                'action_type' => 'view',
                'module' => 'outgoing_dashboard',
                'description' => auth()->user()->name . ' viewed outgoing dashboard',
            );
            $storeLog = SystemLogController::addLog($logData);

            return view('backend.soft_calling.outgoing_dashboard', compact('selected_menu', 'temp_leads'));
        } elseif ($priority_temp_leads) {

            $temp_leads = TempLeads::where('id', $priority_temp_leads->id)->first();

            if ($temp_leads) {
                $temp_leads = $temp_leads->toArray();

                $temp_leads['is_assigned'] = 1;
                $temp_leads['tele_caller_id'] = $soft_caller_id;
                $company_country = Country::where('id', $temp_leads['company_country_id'])->first()->toArray();
                $company_city = City::where('id', $temp_leads['company_city_id'])->first()->toArray();
                $industry = Industry::where('id', $temp_leads['industry_id'])->first()->toArray();
                $last_called_by = User::where('id', $temp_leads['last_tele_caller_id'])->first();

                if ($last_called_by) {
                    $last_called_by = $last_called_by->toArray();
                    $temp_leads['last_called_by'] = $last_called_by['name'];
                } else {
                    $last_called_by = array();
                    $temp_leads['last_called_by'] = 'N/A';
                }

                $temp_leads['company_country'] = $company_country['country_name'];
                $temp_leads['country_code'] = $company_country['country_code'];
                $temp_leads['company_city'] = $company_city['city_name'];
                $temp_leads['industry'] = $industry['industry_name'];
                $temp_leads['calling_status'] = $calling_status_list[$temp_leads['calling_status']];

                $temp_leads_update = TempLeads::where('id', $temp_leads['id'])->update(
                    [
                        'is_assigned' => $temp_leads['is_assigned'],
                        'tele_caller_id' => $temp_leads['tele_caller_id']
                    ]
                );
            } else {
                $temp_leads = [];
            }

            $selected_menu = 'outgoing_dashboard';

            $logData = array(
                'user_id' => auth()->user()->id,
                'action_type' => 'view',
                'module' => 'outgoing_dashboard',
                'description' => auth()->user()->name . ' viewed outgoing dashboard',
            );
            $storeLog = SystemLogController::addLog($logData);

            return view('backend.soft_calling.outgoing_dashboard', compact('selected_menu', 'temp_leads'));
        } else {
            $temp_leads = TempLeads::where('is_deleted', null)
                ->where('is_assigned', 0)
                ->where('calling_status', '<>', '3')
                ->where('calling_status', '<>', '10')
                ->where('recalling_date', null)
                ->orderBy('created_at', 'asc')
                ->orderBy('updated_at', 'asc')
                ->first();

            if ($temp_leads) {
                $temp_leads = $temp_leads->toArray();

                $temp_leads['is_assigned'] = 1;
                $temp_leads['tele_caller_id'] = $soft_caller_id;

                $company_country = Country::where('id', $temp_leads['company_country_id'])->first()->toArray();
                $company_city = City::where('id', $temp_leads['company_city_id'])->first()->toArray();
                $industry = Industry::where('id', $temp_leads['industry_id'])->first()->toArray();
                $last_called_by = User::where('id', $temp_leads['last_tele_caller_id'])->first();

                if ($last_called_by) {
                    $last_called_by = $last_called_by->toArray();
                    $temp_leads['last_called_by'] = $last_called_by['name'];
                } else {
                    $last_called_by = array();
                    $temp_leads['last_called_by'] = 'N/A';
                }

                $temp_leads['company_country'] = $company_country['country_name'];
                $temp_leads['country_code'] = $company_country['country_code'];
                $temp_leads['company_city'] = $company_city['city_name'];
                $temp_leads['industry'] = $industry['industry_name'];
                $temp_leads['calling_status'] = $calling_status_list[$temp_leads['calling_status']];

                $temp_leads_update = TempLeads::where('id', $temp_leads['id'])->update(
                    [
                        'is_assigned' => $temp_leads['is_assigned'],
                        'tele_caller_id' => $temp_leads['tele_caller_id']
                    ]
                );
            } else {
                $temp_leads = [];
            }

            $selected_menu = 'outgoing_dashboard';
            $logData = array(
                'user_id' => auth()->user()->id,
                'action_type' => 'view',
                'module' => 'outgoing_dashboard',
                'description' => auth()->user()->name . ' viewed outgoing dashboard',
            );
            $storeLog = SystemLogController::addLog($logData);
            return view('backend.soft_calling.outgoing_dashboard', compact('selected_menu', 'temp_leads'));
        }
    }

    public function assign_leads()
    {

        $selected_menu = 'assign_leads';

        $logData = array(
            'user_id' => auth()->user()->id,
            'action_type' => 'view',
            'module' => 'assign_leads',
            'description' => auth()->user()->name . ' viewed assign leads',
        );
        $storeLog = SystemLogController::addLog($logData);

        return view('backend.soft_calling.assign_leads', compact('selected_menu'));
    }

    public function get_city_by_country_id(Request $request)
    {
        $country_id = $request->country_id;
        $selected_city_id = $request->selected_city_id;
        $cities = City::where('country_id', $country_id)->get()->toArray();
        $view = view('backend.soft_calling.city_dropdown', compact('cities', 'selected_city_id'))->render();
        return response()->json(['view' => $view]);
    }

    function viewAssingedLeads()
    {
        $selected_menu = 'view_assinged_leads';
        $logData = array(
            'user_id' => auth()->user()->id,
            'action_type' => 'view',
            'module' => 'view_assinged_leads',
            'description' => auth()->user()->name . ' viewed view assinged leads',
        );
        $storeLog = SystemLogController::addLog($logData);
        return view('backend.leads.view_assigned_leads', compact('selected_menu'));
    }

    public function clinetJobs()
    {
        $selected_menu = 'clinet_jobs';
        $logData = array(
            'user_id' => auth()->user()->id,
            'action_type' => 'view',
            'module' => 'client_jobs',
            'description' => auth()->user()->name . ' viewed client jobs',
        );
        return view('backend.mom.clinet_jobs', compact('selected_menu'));
    }

    public function tranferClients()
    {
        $selected_menu = 'transfer_client';
        $assign_from = User::with('roles')
            ->with('country')
            ->where('is_deleted', null)
            ->get()->toArray();

        $logData = array(
            'user_id' => auth()->user()->id,
            'action_type' => 'transfer',
            'module' => 'client',
            'description' => 'Viewed Transfer Clients List',
        );
        $storeLog = SystemLogController::addLog($logData);

        return view('backend/transfer_client', compact('assign_from', 'selected_menu'));
    }

    public function notes_list()
    {
        $selected_menu = 'notes';
        return view('backend/notes', compact('selected_menu'));
    }

    public function mom_modes()
    {
        $selected_menu = 'mom_modes';
        return view('backend.masters.mom_modes_master', compact('selected_menu'));
    }

}
