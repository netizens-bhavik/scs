<?php

namespace App\Http\Controllers;

use App\Models\Mom;
use App\Models\City;
use App\Models\MomMode;
use App\Models\User;
use App\Models\Leads;
use App\Models\Client;
use App\Models\Country;
use App\Models\Industry;
use Illuminate\Http\Request;
use App\Models\ContactPerson;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MOMController;
use App\Http\Controllers\SystemLogController;

class LeadController extends Controller
{
    //
    function getAssingedLeadsList(Request $request)
    {

        $MOMController = new MOMController;
        $userIds = $MOMController->getHirarchyUser(Auth::user()->id);
        $userIds[] = Auth::user()->id;

        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndexArr = $request->get('order');
        $columnNameArr = $request->get('columns');
        $orderArr = $request->get('order');
        $searchArr = $request->get('search');

        $columnIndex = isset($columnIndexArr[0]['column']) ? $columnIndexArr[0]['column'] : ''; // Column index
        $columnName = !empty($columnIndex) ? $columnNameArr[$columnIndex]['data'] : 'updated_at'; // Column name
        $columnSortOrder = !empty($columnIndex) ? $orderArr[0]['dir'] : 'DESC'; // asc or desc
        $searchValue = $searchArr['value']; // Search value

        // Total records


        //dd($userIds);

        // Get records, also we have included search filter as well
        $records = DB::table('leads')
            ->leftJoin('temp_leads', 'leads.temp_lead_id', '=', 'temp_leads.id')
            ->leftJoin('users as u2', 'leads.bdm_id', '=', 'u2.id')
            ->leftJoin('countries', 'temp_leads.company_country_id', '=', 'countries.id')
            ->leftJoin('industries', 'temp_leads.industry_id', '=', 'industries.id')
            ->where('leads.is_deleted', '=', null)
            ->whereIn('leads.bdm_id', $userIds)
            ->where(function ($query) use ($searchValue) {
                $query->where('country_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('company_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('u2.name', 'like', '%' . $searchValue . '%')
                    ->orWhere('industry_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('address', 'like', '%' . $searchValue . '%')
                    ->orWhere('basic_requirement', 'like', '%' . $searchValue . '%');
            })
            ->select('leads.*', 'u2.name as assigned_to', 'temp_leads.contact_person_name', 'temp_leads.address', 'temp_leads.company_name', 'countries.country_name', 'industries.industry_name')
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get()->toArray();

        $totalRecords_data = DB::table('leads')
            ->leftJoin('temp_leads', 'leads.temp_lead_id', '=', 'temp_leads.id')
            ->leftJoin('users as u2', 'leads.bdm_id', '=', 'u2.id')
            ->leftJoin('countries', 'temp_leads.company_country_id', '=', 'countries.id')
            ->leftJoin('industries', 'temp_leads.industry_id', '=', 'industries.id')
            ->where('leads.is_deleted', '=', null)
            ->whereIn('leads.bdm_id', $userIds)
            ->where(function ($query) use ($searchValue) {
                $query->where('country_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('company_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('u2.name', 'like', '%' . $searchValue . '%')
                    ->orWhere('industry_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('address', 'like', '%' . $searchValue . '%')
                    ->orWhere('basic_requirement', 'like', '%' . $searchValue . '%');
            })
            ->select('leads.*', 'u2.name as assigned_to', 'temp_leads.contact_person_name', 'temp_leads.address', 'temp_leads.company_name', 'countries.country_name', 'industries.industry_name')
            ->get()->toArray();

        $totalRecords = 0;
        foreach ($totalRecords_data as $key => $value) {
            $mom = [];
            $client = Client::where('lead_id', '=', $value->id)->first();

            if ($client) {
                $mom = Mom::where('client_id', '=', $client->id)->first();
            }

            if (empty($mom)) {
                $totalRecords++;
            }
        }

        $totalRecordswithFilter_data = DB::table('leads')
            ->leftJoin('temp_leads', 'leads.temp_lead_id', '=', 'temp_leads.id')
            ->leftJoin('users as u2', 'leads.bdm_id', '=', 'u2.id')
            ->leftJoin('countries', 'temp_leads.company_country_id', '=', 'countries.id')
            ->leftJoin('industries', 'temp_leads.industry_id', '=', 'industries.id')
            ->where('leads.is_deleted', '=', null)
            ->whereIn('leads.bdm_id', $userIds)
            ->where(function ($query) use ($searchValue) {
                $query->where('country_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('company_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('u2.name', 'like', '%' . $searchValue . '%')
                    ->orWhere('industry_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('address', 'like', '%' . $searchValue . '%')
                    ->orWhere('basic_requirement', 'like', '%' . $searchValue . '%');
            })
            ->select('leads.*', 'u2.name as assigned_to', 'temp_leads.contact_person_name', 'temp_leads.address', 'temp_leads.company_name', 'countries.country_name', 'industries.industry_name')
            ->get()->toArray();

        $totalRecordswithFilter = 0;

        foreach ($totalRecordswithFilter_data as $key => $value) {
            $mom = [];
            $client = Client::where('lead_id', '=', $value->id)->first();

            if ($client) {
                $mom = Mom::where('client_id', '=', $client->id)->first();
            }

            if (empty($mom)) {
                $totalRecordswithFilter++;
            }
        }

        $user = Auth::user();
        $user_permissions = $user->getAllPermissions()->pluck('name')->toArray();
        $user_edit_permissions_check = 'client_edit';
        $user_add_permissions_check = 'client_add';
        $user_mom_edit_permissions = 'mom_edit';
        $user_mom_add_permissions = 'mom_add';
        $user_edit_permissions = in_array($user_edit_permissions_check, $user_permissions);
        $user_add_permissions = in_array($user_add_permissions_check, $user_permissions);
        $user_mom_edit_permissions_check = in_array($user_mom_edit_permissions, $user_permissions);
        $user_mom_add_permissions_check = in_array($user_mom_add_permissions, $user_permissions);

        $records = json_decode(json_encode($records), true);

        $dataArr = [];
        $i = $start + 1;
        foreach ($records as $record) {

            $action_btn = '';
            $mom = [];
            $client = Client::where('lead_id', '=', $record['id'])->first();

            if ($client) {
                $mom = Mom::where('client_id', '=', $client->id)->first();
            }

            if (empty($mom)) {


                if (($user_add_permissions || $user_edit_permissions) && !($client)) {
                    $clientAdd = ($client) ? $client->id : '';
                    $title = ($client) ? 'Update Client' : 'Add Client';
                    $action_btn .= '<a href="javascript:void(0);" class="btn btn btn-icon btn-outline-primary add_client" data-id="' . $record['id'] . '" title="' . $title . '" data-client="' . $clientAdd . '"><i class="bx bx-user-plus"></i></a>&nbsp;&nbsp;&nbsp;';
                }
                if (!$user_edit_permissions && !$user_add_permissions) {
                    $action_btn .= '<span class="badge bg-secondary">No Permission</span>';
                }


                $client_active_status = Client::where('lead_id', '=', $record['id'])->first();

                if ($client_active_status && $client) {
                    $client_active_status = $client_active_status->toArray();
                    if ($client_active_status['active_status'] == 0) {
                        $action_btn .= '<span class="badge bg-secondary">Client InActive</span>';
                    } elseif (($user_mom_edit_permissions_check || $user_mom_add_permissions_check) && $client) {
                        if (($user_mom_edit_permissions_check || $user_mom_add_permissions_check) && $client) {
                            $action_btn .= '<a href="javascript:void(0);" class="btn btn btn-icon btn-outline-primary add_mom" data-id="' . $record['id'] . '" data-client="' . $client->id . '" title="Add MOM"><i class="bx bx-file"></i></a>';
                        }
                        if (!$user_mom_edit_permissions_check && !$user_mom_add_permissions_check && $client) {
                            $action_btn .= '<span class="badge bg-secondary">No Permission</span>';
                        }
                    } else {
                    }
                }

                $dataArr[] = array(
                    "id" => $i++,
                    "company_name" => $record['company_name'],
                    "user_name" => $record['assigned_to'],
                    "industry_name" => $record['industry_name'],
                    "country_name" => $record['country_name'],
                    "address" => $record['address'],
                    "spoken_with" => $record['spoken_with'],
                    "basic_requirement" => $record['basic_requirement'],
                    "action" => $action_btn,
                );
            }
        }


        $logData = array(
            'user_id' => auth()->user()->id,
            'action_type' => 'view',
            'module' => 'view_assinged_leads',
            'description' =>  'View Lead List',
        );

        $storeLog = SystemLogController::addLog($logData);

        return response()->json([
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecordswithFilter,
            "data" => $dataArr,
        ]);
    }

    function AddClientToLead(Request $request)
    {
        $client = $cities = [];

        if (isset($request->clientId) && !empty($request->clientId)) {
            $client = Client::find($request->clientId)->toArray();
            $client['contactPerson'] = ContactPerson::where('client_id', $request->clientId)->where('is_deleted', null)->get()->toArray();
            $cities = City::where('country_id', $client['country_id'])->where('is_deleted', null)->get()->toArray();
        } else {
            $lead = Leads::with('tempLead')->where('id', $request->leadId)->where('is_deleted', null)->first()->toArray();

            $client['id'] = '';
            $client['company_name'] = $lead['temp_lead']['company_name'];
            $client['industry_id'] = $lead['temp_lead']['industry_id'];
            $client['email'] = $lead['temp_lead']['company_email'];
            $client['phone_no'] = $lead['temp_lead']['company_phone_no'];
            $client['country_id'] = $lead['temp_lead']['company_country_id'];
            $client['city_id'] = $lead['temp_lead']['company_city_id'];
            $client['post_box_no'] = $lead['temp_lead']['post_box_no'];
            $client['website_name'] = $lead['temp_lead']['website_name'];
            $client['address'] = $lead['temp_lead']['address'];
            $client['sort_description'] = $lead['basic_requirement'];
            $client['active_status'] = '1';
            $client['lead_id'] = $lead['id'];
            $client['contactPerson'][] = [
                'id' => '',
                'name'  => $lead['temp_lead']['contact_person_name'],
                'department' => $lead['temp_lead']['department'],
                'designation' => $lead['temp_lead']['designation'],
                'email' => $lead['temp_lead']['contact_person_email'],
                'mobile_no' => $lead['temp_lead']['contact_person_phone'],
                'dob' => $lead['temp_lead']['dob']
            ];

            $cities = City::where('country_id', $lead['temp_lead']['company_country_id'])->where('is_deleted', null)->get()->toArray();
        }

        $countries = Country::where('is_deleted', null)->get()->toArray();
        $indusries = Industry::where('is_deleted', null)->get()->toArray();

        return view('backend.masters.modals.client_master_form', [
            'client' => $client,
            'countries' => $countries,
            'indusries' => $indusries,
            'cities' => $cities,
            'isBDE' => 1,
        ]);
    }

    function AddMomToLead(Request $requests)
    {
        //$lead = Leads::find($requests->leadId);
        $client = Client::find($requests->clientId);

        $mom['company_name'] = $client->company_name;
        $mom['client_id'] = $requests->clientId;

        $user = Auth::user();
        $this->finalArray = [];
        //$userIds = MOMController::getHirarchyUser($user->id);
        $MOMController = new MOMController;
        $userIds = $MOMController->getHirarchyUser($user->id);
        $userIds[] = $user->id;
        $meeting_modes = MomMode::where('is_deleted', null)->get()->toArray();

        //we can get company name from clients
        $clients = Client::whereIn('manage_by', $userIds)->get()->toArray();
        /* $clientIds = array_column($clients, 'id');
        $contactPersons = ContactPerson::whereIn('client_id', $clientIds)->get()->toArray(); */

        $mom['contactPersons'] = ContactPerson::where('client_id', $requests->clientId)->where('is_deleted', null)->get()->toArray();
        /* $users = User::all(); */
        $users = User::with('roles')
            ->with('country')
            ->where('is_deleted', null)
            ->get()->toArray();

        $logData = array(
            'user_id' => auth()->user()->id,
            'action_id' => $requests->clientId,
            'action_type' => 'create',
            'module' => 'mom',
            'description' =>  'Add MOM',
        );

        $storeLog = SystemLogController::addLog($logData);

        return view('backend.mom.modals.mom_master_form', [
            'clients' => $clients,
            'users' => $users,
            'mom' => $mom,
            'isBDE' => 1,
            'meeting_modes' => $meeting_modes,
        ]);
    }
}
