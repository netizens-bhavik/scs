<?php

namespace App\Http\Controllers;

use App\Models\Mom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SystemStatsController extends Controller
{
    public function index()
    {

    }

    public function mom_stats(Request $request)
    {

        $logged_user_role = Auth::user()->getRoleNames()->first();

        if ($logged_user_role == 'administrator' || $logged_user_role == 'director') {
            $country_id = $request->country_id ?? 0;
        } else {
            $country_id = $request->country_id ?? auth()->user()->country_id;
        }

//        $country_id = $request->country_id ?? auth()->user()->country_id;
        $user_id = $request->user_id ?? auth()->user()->id;

        $followup_type = $request->followup_type;

        $MOMController = new MOMController;
        $userIds = $MOMController->getHirarchyUser($user_id);
        $userIds[] = $user_id;

        $hierarchy_users = implode(',', $userIds);

        $start_date = '';
        $end_date = '';

        if ($followup_type == 1) {
            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d');
        } elseif ($followup_type == 2) {
            $start_date = date('Y-m-d', strtotime('+1 day'));
            $end_date = date('Y-m-d', strtotime('+1 day'));
        } elseif ($followup_type == 3) {
            $end_date = date('Y-m-d', strtotime('-1 day'));
        } elseif ($followup_type == 4) {
            $start_date = date('Y-m-d', strtotime('+2 day'));
        } else {
            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d');
        }

        $filter_data = array(
            'country_id' => $country_id,
            'user_id' => $user_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'followup_type' => $followup_type,
            'hierarchy_users' => $hierarchy_users,
        );

        $view = view('backend.dashboard.dashboard_mom_stats_table', compact('filter_data'))->render();
        return response()->json(['html' => $view]);
    }

    public function get_followup_count(Request $request)
    {
        $logged_user_role = Auth::user()->getRoleNames()->first();

        if ($logged_user_role == 'administrator' || $logged_user_role == 'director') {
            $country_id = $request->country_id ?? 0;
        } else {
            $country_id = $request->country_id ?? auth()->user()->country_id;
        }

        $data_user_id = $request->user_id ?? auth()->user()->id;
        $logged_user_id = auth()->user()->id;
        $data_user_id = (int)$data_user_id;

        $MOMController = new MOMController;
        $userIds = $MOMController->getHirarchyUser($data_user_id);
        $userIds[] = $data_user_id;


        $today_date_start_date = date('Y-m-d');
        $today_date_end_date = date('Y-m-d');

        $tomorrow_date_start_date = date('Y-m-d', strtotime('+1 day'));
        $tomorrow_date_end_date = date('Y-m-d', strtotime('+1 day'));

        $yesterday_date_start_date = date('Y-m-d', strtotime('-1 day'));
        $yesterday_date_end_date = date('Y-m-d', strtotime('-1 day'));

        $after_tomorrow_date_start_date = date('Y-m-d', strtotime('+2 day'));
        $after_tomorrow_date_end_date = date('Y-m-d', strtotime('+2 day'));

        $total_today_records = DB::table('moms')
            ->leftJoin('clients', 'moms.client_id', '=', 'clients.id')
            ->leftJoin('users', 'clients.manage_by', '=', 'users.id')
            ->where('moms.is_deleted', '=', null)
            ->where('moms.followup_status', '=', null)
            ->where(function ($query) use ($country_id) {
                if ($country_id != 0) {
                    $query->where('users.country_id', '=', $country_id);
                }
            })
            ->where(function ($query) use ($userIds, $data_user_id) {
                $query->whereIn('clients.manage_by', $userIds)
                    ->orWhere('moms.share_user_id', '=', $data_user_id);
            })
            ->where('moms.next_followup_date', '>=', $today_date_start_date)
            ->where('moms.next_followup_date', '<=', $today_date_end_date)
            ->select('moms.*', 'clients.company_name')
            ->count();

        $total_tomorrow_records = DB::table('moms')
            ->leftJoin('clients', 'moms.client_id', '=', 'clients.id')
            ->leftJoin('users', 'clients.manage_by', '=', 'users.id')
            ->where('moms.is_deleted', '=', null)
            ->where('moms.followup_status', '=', null)
            ->where(function ($query) use ($country_id) {
                if ($country_id != 0) {
                    $query->where('users.country_id', '=', $country_id);
                }
            })
            ->where(function ($query) use ($userIds, $data_user_id) {
                $query->whereIn('clients.manage_by', $userIds)
                    ->orWhere('moms.share_user_id', '=', $data_user_id);
            })
            ->where('moms.next_followup_date', '>=', $tomorrow_date_start_date)
            ->where('moms.next_followup_date', '<=', $tomorrow_date_end_date)
            ->count();

        $total_pending_records = DB::table('moms')
            ->leftJoin('clients', 'moms.client_id', '=', 'clients.id')
            ->leftJoin('users', 'clients.manage_by', '=', 'users.id')
            ->where('moms.is_deleted', '=', null)
            ->where('moms.followup_status', '=', null)
            ->where(function ($query) use ($country_id) {
                if ($country_id != 0) {
                    $query->where('users.country_id', '=', $country_id);
                }
            })
            ->where(function ($query) use ($userIds, $data_user_id) {
                $query->whereIn('clients.manage_by', $userIds)
                    ->orWhere('moms.share_user_id', '=', $data_user_id);
            })
            ->where('moms.next_followup_date', '<=', $yesterday_date_end_date)
            ->count();

        $total_future_records = DB::table('moms')
            ->leftJoin('clients', 'moms.client_id', '=', 'clients.id')
            ->leftJoin('users', 'clients.manage_by', '=', 'users.id')
            ->where('moms.is_deleted', '=', null)
            ->where('moms.followup_status', '=', null)
            ->where(function ($query) use ($country_id) {
                if ($country_id != 0) {
                    $query->where('users.country_id', '=', $country_id);
                }
            })
            ->where(function ($query) use ($userIds, $data_user_id) {
                $query->whereIn('clients.manage_by', $userIds)
                    ->orWhere('moms.share_user_id', '=', $data_user_id);
            })
            ->where('moms.next_followup_date', '>=', $after_tomorrow_date_start_date)
            ->count();

        return response()->json([
            'status' => true,
            'total_today_records' => $total_today_records,
            'total_tomorrow_records' => $total_tomorrow_records,
            'total_pending_records' => $total_pending_records,
            'total_future_records' => $total_future_records,
        ]);

    }

    function get_mom_followups_data(Request $request)
    {
        $mom_followups_start_date = $request->mom_followups_start_date ?? '';
        $mom_followups_end_date = $request->mom_followups_end_date ?? '';
        $mom_followups_user_id = $request->mom_followups_user_id;
        $mom_followups_country_id = $request->mom_followups_country_id;
        $mom_followups_followup_type = $request->mom_followups_followup_type;
        $mom_hierarchy_users = $request->mom_followups_hierarchy_users;
        $hierarchy_users = explode(',', $mom_hierarchy_users);
        $user_id = Auth::user()->id;


        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndexArr = $request->get('order');
        $columnNameArr = $request->get('columns');
        $orderArr = $request->get('order');
        $searchArr = $request->get('search');

        $columnIndex = isset($columnIndexArr[0]['column']) ? $columnIndexArr[0]['column'] : ''; // Column index
        $columnName = !empty($columnIndex) ? $columnNameArr[$columnIndex]['data'] : 'moms.updated_at'; // Column name
        $columnSortOrder = !empty($columnIndex) ? $orderArr[0]['dir'] : 'DESC'; // asc or desc
        $searchValue = $searchArr['value']; // Search value

        // Get records, also we have included search filter as well

        $records = DB::table('moms')
            ->leftJoin('clients', 'moms.client_id', '=', 'clients.id')
            ->leftJoin('users', 'clients.manage_by', '=', 'users.id')
            ->leftJoin('users as shared_by', 'moms.shared_user_by', '=', 'shared_by.id')
            ->leftJoin('mom_modes', 'moms.mode_of_meeting', '=', 'mom_modes.id')
            ->where('moms.is_deleted', '=', null)
            ->where('moms.followup_status', '=', null)
            ->where(function ($query) use ($mom_followups_country_id) {
                if ($mom_followups_country_id != 0) {
                    $query->where('users.country_id', '=', $mom_followups_country_id);
                }
            })
            ->where(function ($query) use ($hierarchy_users, $user_id) {
                $query->whereIn('clients.manage_by', $hierarchy_users)
                    ->orWhere('moms.share_user_id', '=', $user_id);
            })
            ->where(function ($query) use ($searchValue) {
                $query->where('clients.company_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('contact_person', 'like', '%' . $searchValue . '%');
            })
            ->where(function ($query) use ($mom_followups_start_date, $mom_followups_end_date) {
                if (!empty($mom_followups_start_date) && !empty($mom_followups_end_date)) {
                    $query->where('moms.next_followup_date', '>=', $mom_followups_start_date)
                        ->where('moms.next_followup_date', '<=', $mom_followups_end_date);
                } elseif (!empty($mom_followups_start_date)) {
                    $query->where('moms.next_followup_date', '>=', $mom_followups_start_date);
                } elseif (!empty($mom_followups_end_date)) {
                    $query->where('moms.next_followup_date', '<=', $mom_followups_end_date);
                }
            })
            ->select('moms.*', 'clients.company_name', 'clients.id as client_id', 'users.name as user_name', 'shared_by.name as shared_by_name', 'mom_modes.mode_name as mode_of_meeting_name')
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get()->toArray();

        // Total records
        $totalRecords = DB::table('moms')
            ->leftJoin('clients', 'moms.client_id', '=', 'clients.id')
            ->leftJoin('users', 'clients.manage_by', '=', 'users.id')
            ->where('moms.is_deleted', '=', null)
            ->where('moms.followup_status', '=', null)
            ->where(function ($query) use ($mom_followups_country_id) {
                if ($mom_followups_country_id != 0) {
                    $query->where('users.country_id', '=', $mom_followups_country_id);
                }
            })
            ->where(function ($query) use ($hierarchy_users, $user_id) {
                $query->whereIn('clients.manage_by', $hierarchy_users)
                    ->orWhere('moms.share_user_id', '=', $user_id);
            })
            ->where(function ($query) use ($mom_followups_start_date, $mom_followups_end_date) {
                if (!empty($mom_followups_start_date) && !empty($mom_followups_end_date)) {
                    $query->where('moms.next_followup_date', '>=', $mom_followups_start_date)
                        ->where('moms.next_followup_date', '<=', $mom_followups_end_date);
                } elseif (!empty($mom_followups_start_date)) {
                    $query->where('moms.next_followup_date', '>=', $mom_followups_start_date);
                } elseif (!empty($mom_followups_end_date)) {
                    $query->where('moms.next_followup_date', '<=', $mom_followups_end_date);
                }
            })
            ->count();

        $totalRecordswithFilter = DB::table('moms')
            ->leftJoin('clients', 'moms.client_id', '=', 'clients.id')
            ->leftJoin('users', 'clients.manage_by', '=', 'users.id')
            ->leftJoin('mom_modes', 'moms.mode_of_meeting', '=', 'mom_modes.id')
            ->where('moms.is_deleted', '=', null)
            ->where('moms.followup_status', '=', null)
            ->where(function ($query) use ($mom_followups_country_id) {
                if ($mom_followups_country_id != 0) {
                    $query->where('users.country_id', '=', $mom_followups_country_id);
                }
            })
            ->where(function ($query) use ($hierarchy_users, $user_id) {
                $query->whereIn('clients.manage_by', $hierarchy_users)
                    ->orWhere('moms.share_user_id', '=', $user_id);
            })
            ->where(function ($query) use ($searchValue) {
                $query->where('clients.company_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('contact_person', 'like', '%' . $searchValue . '%');
            })
            ->where(function ($query) use ($mom_followups_start_date, $mom_followups_end_date) {
                if (!empty($mom_followups_start_date) && !empty($mom_followups_end_date)) {
                    $query->where('moms.next_followup_date', '>=', $mom_followups_start_date)
                        ->where('moms.next_followup_date', '<=', $mom_followups_end_date);
                } elseif (!empty($mom_followups_start_date)) {
                    $query->where('moms.next_followup_date', '>=', $mom_followups_start_date);
                } elseif (!empty($mom_followups_end_date)) {
                    $query->where('moms.next_followup_date', '<=', $mom_followups_end_date);
                }
            })
            ->count();

        $records = json_decode(json_encode($records), true);

        $user = Auth::user();
        $user_permissions = $user->getAllPermissions()->pluck('name')->toArray();
        $user_edit_permissions_check = 'mom_edit';
        $user_delete_permissions_check = 'mom_delete';
        $user_edit_permissions = in_array($user_edit_permissions_check, $user_permissions);
        $user_delete_permissions = in_array($user_delete_permissions_check, $user_permissions);
        $auth_user_role = $user->roles->pluck('name')->toArray();
        $id_admin = in_array('administrator', $auth_user_role);

        $dataArr = [];
        $i = $start + 1;
        foreach ($records as $record) {
            $action_btn = '';
            $company_modal = '';

            $shared_user_check = DB::table('moms')
                ->where('moms.id', '=', $record['id'])
                ->where('moms.share_user_id', '=', $user_id)
                ->select('moms.*')
                ->count();

            $company_modal .= '<a href="javascript:void(0);" class="company_modal" data-id="' . $record['client_id'] . '" title="company_modal">' .
                $record['company_name'] . '</a>';

            if ($shared_user_check) {
                $action_btn .= '<span class="badge bg-secondary">Shared</span>';
            } else {


//            if ($user_edit_permissions){
//                $action_btn .= '<a href="javascript:void(0);" class="btn btn  btn-outline-primary manage_mom" data-client_id="'.$record['client_id'].'"  data-id="' . $record['id'] . '" title="manage">
//                Manage MOM</a>&nbsp;&nbsp;&nbsp;';
//            }

                // add permission condition
                if ($user_edit_permissions) {
                    $action_btn .= '<a href="javascript:void(0);" class="btn  btn-outline-primary edit_mom" data-id="' . $record['id'] . '" title="Edit">
                Add MOM</a>&nbsp;&nbsp;&nbsp;';
                }

//            // add permission condition
//            if ($user_delete_permissions) {
//                $action_btn .= '<a href="javascript:void(0);" class="btn btn btn-icon btn-outline-primary delete_mom" data-id="' . $record['id'] . '" data-name="' . $record['company_name'] . '" title="Delete">
//                <i class="bx bx-trash-alt"></i></a>';
//            }

                if (!$user_edit_permissions && !$user_delete_permissions) {
                    $action_btn .= '<span class="badge bg-secondary">No Permission</span>';
                }

            }


            $followup_datetime = date('d/m/Y', strtotime($record['next_followup_date'])) . ' ' . date('h:i:A', strtotime($record['next_followup_time']));

            $dataArr[] = array(
                "id" => $i++,
                "user_name" => $record['user_name'],
                "followup_datetime" => $followup_datetime,
                "company_name" => $company_modal,
                "contact_person" => $record['contact_person'],
                "action" => $action_btn,
                "mode_of_meeting_name" => $record['mode_of_meeting_name'],
                "shared_by_name" => $record['shared_by_name'] ?? '-',
            );
        }

        return response()->json([
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecordswithFilter,
            "data" => $dataArr,
        ]);


    }

    function get_chart_data(Request $request)
    {

        $logged_user_role = Auth::user()->getRoleNames()->first();

        if ($logged_user_role == 'administrator' || $logged_user_role == 'director') {
            $country_id = $request->country_id ?? 0;
        } else {
            $country_id = $request->country_id ?? auth()->user()->country_id;
        }

        //$country_id = $request->country_id == 0 ? auth()->user()->country_id : $request->country_id;
        $data_user_id = $request->user_id == 0 ? auth()->user()->id : $request->user_id;

        $country_chart_id = $request->country_id == 0 ? null : $request->country_id;

        $data_user_id = (int)$data_user_id;

        $MOMController = new MOMController;
        $hierarchy_users = $MOMController->getHirarchyUser($data_user_id);
        $hierarchy_users[] = $data_user_id;

        $client_status_records = DB::table('moms')
            ->leftJoin('clients', 'moms.client_id', '=', 'clients.id')
            ->leftJoin('users', 'clients.manage_by', '=', 'users.id')
            ->where('moms.is_deleted', '=', null)
            ->where('moms.followup_status', '=', null)
            ->where(function ($query) use ($country_id) {
                if ($country_id != 0) {
                    $query->where('users.country_id', '=', $country_id);
                }
            })
            ->whereIn('clients.manage_by', $hierarchy_users)
            ->select('moms.id as mom_id', 'moms.client_id as client_id', 'clients.company_name', 'clients.manage_by', 'users.name as user_name')
            ->orderBy('moms.created_by', 'desc')
//            ->groupBy('moms.client_status')
//            ->select('moms.client_status',DB::raw("STRING_AGG(moms.id,',') as moms_id"), DB::raw('count(*) as total'))
            ->get()->toArray();

        $client_status_records = json_decode(json_encode($client_status_records), true);

        $all_clients_id = array();
        foreach ($client_status_records as $key => $value) {
            $all_clients_id[] = $value['client_id'];
        }

        $all_clients_id = array_unique($all_clients_id);
        $total_client_status = array();
        foreach ($all_clients_id as $key => $value) {
            $data = DB::table('moms')
                ->where('moms.is_deleted', '=', null)
                ->where('moms.followup_status', '=', null)
                ->where('moms.client_id', '=', $value)
                ->select('moms.client_status', 'moms.client_id', 'moms.id')
                ->latest()
                ->first();

            if (array_key_exists($data->client_status, $total_client_status)) {
                $total_client_status[$data->client_status] += 1;
            } else {
                $total_client_status[$data->client_status] = 1;
            }
        }

        $client_status_records = [];

           foreach($total_client_status as $key => $value){
               $client_status_records[] = [
                   'client_status' => $key,
                   'total' => $value
               ];
           }


        $job_status_records = DB::table('moms')
            ->leftJoin('mom_jobs', 'moms.id', '=', 'mom_jobs.mom_id')
            ->leftJoin('clients', 'moms.client_id', '=', 'clients.id')
            ->leftJoin('users', 'clients.manage_by', '=', 'users.id')
            ->where('moms.is_deleted', '=', null)
//            ->where('moms.followup_status', '=', null)
            ->where(function ($query) use ($country_id) {
                if ($country_id != 0) {
                    $query->where('users.country_id', '=', $country_id);
                }
            })
            ->whereIn('clients.manage_by', $hierarchy_users)
            ->groupBy('mom_jobs.job_status')
            ->select('mom_jobs.job_status', DB::raw('count(*) as total'))
            ->get()->toArray();

        $job_status_records = json_decode(json_encode($job_status_records), true);

        if ($country_chart_id == null) {
            $country_wise_records = DB::table('clients')
                ->leftJoin('countries', 'clients.country_id', '=', 'countries.id')
                ->where('clients.is_deleted', '=', null)
                ->groupBy('clients.country_id')
                ->select('clients.country_id', DB::raw('count(*) as total'))
                ->get()->toArray();
        } else {
            $country_wise_records = DB::table('clients')
                ->leftJoin('countries', 'clients.country_id', '=', 'countries.id')
                ->where('clients.is_deleted', '=', null)
                ->where('clients.country_id', '=', $country_chart_id)
                ->groupBy('clients.country_id')
                ->select('clients.country_id', DB::raw('count(*) as total'))
                ->get()->toArray();
        }

        $country_wise_records = json_decode(json_encode($country_wise_records), true);

        $country_chart_data = array();

        if ($country_wise_records) {
            foreach ($country_wise_records as $key => $value) {
                $country_data = DB::table('countries')->where('id', '=', $value['country_id'])->first();
                $country_chart_data['country_name'][] = $country_data->country_name;
                $country_chart_data['total'][] = (int)$value['total'];
            }
        }

        $data = array();
        $data['client_status'] = array(
            'High Priority' => 0,
            'Medium Priority' => 0,
            'Low Priority' => 0,
            'Requirement Received' => 0,
            'Under Discussion' => 0,
            'Closed' => 0,
        );
        $data['job_status'] = array(
            'Ongoing' => 0,
            'On Hold' => 0,
            'Completed' => 0,
            'Cancel' => 0,
        );

        if ($country_wise_records) {
            $data['country_wise'] = array(
                'country_name' => $country_chart_data['country_name'],
                'total' => $country_chart_data['total'],
            );
        } else {
            $data['country_wise'] = array(
                'country_name' => [],
                'total' => [],
            );
        }

        foreach ($client_status_records as $client_status_record) {
            if ($client_status_record['client_status'] == '1') {
                $data['client_status']['High Priority'] = (int)$client_status_record['total'] ?? 0;
            } else if ($client_status_record['client_status'] == '2') {
                $data['client_status']['Medium Priority'] = (int)$client_status_record['total'] ?? 0;
            } else if ($client_status_record['client_status'] == '3') {
                $data['client_status']['Low Priority'] = (int)$client_status_record['total'] ?? 0;
            } else if ($client_status_record['client_status'] == '4') {
                $data['client_status']['Requirement Received'] = (int)$client_status_record['total'] ?? 0;
            } else if ($client_status_record['client_status'] == '5') {
                $data['client_status']['Under Discussion'] = (int)$client_status_record['total'] ?? 0;
            } else if ($client_status_record['client_status'] == '6') {
                $data['client_status']['Closed'] = (int)$client_status_record['total'] ?? 0;
            }
        }

        foreach ($job_status_records as $job_status_record) {
            if ($job_status_record['job_status'] == '1') {
                $data['job_status']['Ongoing'] = (int)$job_status_record['total'] ?? 0;
            } else if ($job_status_record['job_status'] == '2') {
                $data['job_status']['On Hold'] = (int)$job_status_record['total'] ?? 0;
            } else if ($job_status_record['job_status'] == '3') {
                $data['job_status']['Completed'] = (int)$job_status_record['total'] ?? 0;
            } else if ($job_status_record['job_status'] == '4') {
                $data['job_status']['Cancel'] = (int)$job_status_record['total'] ?? 0;
            }
        }

        $client_status_labels = array_keys($data['client_status']);
        $client_status_data = array_values($data['client_status']);

        $job_status_labels = array_keys($data['job_status']);
        $job_status_data = array_values($data['job_status']);

        $country_wise_labels = $data['country_wise']['country_name'];
        $country_wise_data = $data['country_wise']['total'];

        $total_client = array_sum($client_status_data);
        $total_job = array_sum($job_status_data);
        $total_country = array_sum($country_wise_data);

//        //if $client_status all zero then return empty array
//        if (array_sum($client_status_data) == 0) {
//            $client_status_labels = ['No Data'];
//            $client_status_data = [100];
//        }
//
//        //if $job_status all zero then return empty array
//        if (array_sum($job_status_data) == 0) {
//            $job_status_labels = ['No Data'];
//            $job_status_data = [100];
//        }
//
//        //if $country_wise all zero then return empty array
//        if (array_sum($country_wise_data) == 0) {
//            $country_wise_labels = ['No Data'];
//            $country_wise_data = [100];
//        }

        return response()->json([
            'total_client' => $total_client,
            'total_job' => $total_job,
            'total_country' => $total_country,
            'client_status_labels' => $client_status_labels,
            'client_status' => $client_status_data,
            'job_status_labels' => $job_status_labels,
            'job_status' => $job_status_data,
            'country_wise_labels' => $country_wise_labels,
            'country_wise_data' => $country_wise_data,
        ]);
    }

    public function get_chart_data_view(Request $request)
    {
        $country_id = $request->country_id ?? null;
        $user_id = $request->user_id ?? null;

        $view = view('backend.dashboard.charts', compact('country_id', 'user_id'))->render();
        return response()->json(['html' => $view]);
    }

    public function get_dashboard_country_users(Request $request)
    {

        $country_id = $request->country_id ?? null;

        $logged_in_user = Auth::user();
        $logged_in_user_id = $logged_in_user->id;

        $MOMController = new MOMController;
        $hierarchy_users = $MOMController->getHirarchyUser($logged_in_user_id);
        $hierarchy_users[] = $logged_in_user_id;

        if ($country_id != null) {
            $users = DB::table('users')
                ->select('users.id', 'users.name')
                ->leftJoin('countries', 'countries.id', '=', 'users.country_id')
                ->leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                ->leftJoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->select('users.*', 'countries.country_name as country_name', 'roles.name as role_name')
                ->where('users.country_id', $country_id)
                ->whereIn('users.id', $hierarchy_users)
                ->get();
        } else {
            $users = DB::table('users')
                ->whereIn('users.id', $hierarchy_users)
                ->leftJoin('countries', 'countries.id', '=', 'users.country_id')
                ->leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                ->leftJoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->select('users.*', 'countries.country_name as country_name', 'roles.name as role_name')
                ->get();
        }

        // $users = User::where('country_id', $country_id)->get();

        if ($users) {
            $users = json_decode(json_encode($users), true);
            // $users = $users->toArray();
        } else {
            $users = [];
        }

//        echo "<pre>";
//        print_r($users);
//        echo "</pre>";
//        exit;
        // dd($users);

        $view = view('backend.dashboard.country_users', compact('users'))->render();

        return response()->json(['html' => $view]);

    }

    public function company_modal(Request $request)
    {
        $client_id = (int)$request->id ?? null;


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

        // dd($client_data);
        $view = view('backend.client_history.company_history_modal', compact('client_data'))->render();
        return response()->json(
            [
                'status' => true,
                'html' => $view,
            ]
        );

//        return view('backend.client_history.client_history', compact( 'client_data'));
    }
}
