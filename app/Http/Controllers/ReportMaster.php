<?php

namespace App\Http\Controllers;

use App\Exports\ExportReports;
use App\Models\Mom;
use App\Models\City;
use App\Models\User;
use App\Models\Client;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\MOMController;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\SimpleExcel\SimpleExcelWriter;
use App\Http\Controllers\SystemLogController;
use Illuminate\Support\Facades\Session;


class ReportMaster extends Controller
{
    public function mom_report()
    {
        $selected_menu = 'mom_report';
        $auth_user_role = Auth::user()->roles()->first()->name;
        $user = Auth::user();
        $user_id = $user->id;
        $user_country = $user->country_id;
        $MOMController = new MOMController;
        $userIds = $MOMController->getHirarchyUser($user->id);
        $userIds[] = $user->id;

        $users = User::whereIn('id', $userIds)->get()->toArray();

        $all_countries = Country::all()->toArray();

        if ($auth_user_role == 'general manager') {
            $company_data = DB::table('moms')
                ->join('clients', 'clients.id', '=', 'moms.client_id')
                ->select('clients.company_name', 'clients.id')
                ->where('clients.country_id', $user_country)
                ->whereIn('moms.created_by', $userIds)
                ->where('moms.is_deleted', null)
                ->get()->toArray();
            $company_data = array_map("unserialize", array_unique(array_map("serialize", $company_data)));
        } else {
            $company_data = DB::table('moms')
                ->join('clients', 'clients.id', '=', 'moms.client_id')
                ->select('clients.company_name', 'clients.id')
                ->whereIn('moms.created_by', $userIds)
                ->where('moms.is_deleted', null)
                ->get()->toArray();
            $company_data = array_map("unserialize", array_unique(array_map("serialize", $company_data)));
        }

        // echo "<pre>";
        // print_r($company_data);
        // exit;

        $mom_added_by_users = DB::table('moms')
            ->select('users.id', 'users.name')
            ->join('users', 'users.id', '=', 'moms.created_by')
            ->whereIn('moms.created_by', $userIds)
            ->get()->toArray();

        $mom_added_by_users = array_map("unserialize", array_unique(array_map("serialize", $mom_added_by_users)));

        // echo "<pre>";
        // print_r($company_data);
        // exit;

        return view('backend.reports.mom_report', compact('selected_menu', 'mom_added_by_users', 'user_country', 'company_data', 'auth_user_role', 'all_countries'));
    }

    public function mom_report_data(Request $request)
    {
        $data = $request->all();

        // dd($data);
        $view = view('backend.reports.mom_report_table', compact('data'))->render();
        return response()->json(['html' => $view]);
    }

    public function get_mom_report_data(Request $request)
    {
        // $data = $request->all();
        // dd($data);

        $mom_report_from_date = $request->mom_report_from_date;
        $mom_report_to_date = $request->mom_report_to_date;
        $mom_report_company_name = $request->mom_report_company_name;
        $mom_report_country = $request->mom_report_country;
        $mom_report_user = $request->mom_report_user;

        if ($mom_report_from_date != '' && $mom_report_to_date != '') {
            $mom_report_from_date = date('Y-m-d', strtotime($mom_report_from_date));
            $mom_report_to_date = date('Y-m-d', strtotime($mom_report_to_date));
        }
        if ($mom_report_company_name != '') {
            $mom_report_company_id = $mom_report_company_name;
            $mom_report_company_name = Client::where('id', $mom_report_company_id)->first()->company_name;
        } else {
            $mom_report_company_name = '';
        }
        if ($mom_report_country != '') {
            $mom_report_country = $mom_report_country;
        } else {
            $mom_report_country = '';
        }
        if ($mom_report_user != '') {
            $mom_report_user = $mom_report_user;
        } else {
            $mom_report_user = '';
        }

        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndexArr = $request->get('order');
        $columnNameArr = $request->get('columns');
        $orderArr = $request->get('order');
        $searchArr = $request->get('search');

        $columnIndex = isset($columnIndexArr[0]['column']) ? $columnIndexArr[0]['column'] : ''; // Column index
        $columnName = !empty($columnIndex) ? $columnNameArr[$columnIndex]['data'] : 'moms.id'; // Column name
        $columnSortOrder = !empty($columnIndex) ? $orderArr[0]['dir'] : 'Asc'; // asc or desc
        $searchValue = $searchArr['value']; // Search value


        $user = Auth::user();
        $MOMController = new MOMController;
        $userIds = $MOMController->getHirarchyUser($user->id);
        $userIds[] = $user->id;

        $users_data = DB::table('moms')
            ->leftJoin('clients', 'clients.id', '=', 'moms.client_id')
            ->leftJoin('countries', 'countries.id', '=', 'clients.country_id')
            ->leftJoin('cities', 'cities.id', '=', 'clients.city_id')
            ->leftJoin('users as manage_user', 'manage_user.id', '=', 'clients.manage_by')
            ->leftJoin('users as added_by', 'added_by.id', '=', 'moms.created_by')
            ->select('moms.*', 'moms.id as meeting_id', 'manage_user.name as manage_by_username', 'added_by.name as added_by_username', 'clients.*', 'countries.country_name as country_name', 'cities.city_name as city_name')
            ->where('moms.is_deleted', null)
            ->where(function ($query) use ($searchValue) {
                $query->where('moms.id', 'like', '%' . $searchValue . '%')
                    ->orWhere('contact_person', 'like', '%' . $searchValue . '%')
                    ->orWhere('country_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('company_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('meeting_date', 'like', '%' . $searchValue . '%')
                    ->orWhere('manage_user.name', 'like', '%' . $searchValue . '%')
                    ->orWhere('phone_no', 'like', '%' . $searchValue . '%')
                    ->orWhere('city_name', 'like', '%' . $searchValue . '%')
                    ->orWhereDate('minutes_of_meeting', date('Y-m-d', strtotime($searchValue)))
                    ->orWhere('added_by.name', 'like', '%' . $searchValue . '%')
                    ->orWhereDate('next_followup_date', date('Y-m-d', strtotime($searchValue)))
                    ->orWhere('clients.email', 'like', '%' . $searchValue . '%')
                    ->orWhere('address', 'like', '%' . $searchValue . '%')
                    ->orWhere('bde_feedback', 'like', '%' . $searchValue . '%');
            })
            ->where(function ($query) use ($mom_report_from_date, $mom_report_to_date) {
                if ($mom_report_from_date != '' && $mom_report_to_date != '') {
                    $query->whereBetween('meeting_date', [$mom_report_from_date, $mom_report_to_date]);
                }
            })->where(function ($query) use ($mom_report_company_name) {
                if ($mom_report_company_name != '') {
                    $query->where('company_name', $mom_report_company_name);
                }
            })->where(function ($query) use ($mom_report_country) {
                if ($mom_report_country != '') {
                    $query->where('clients.country_id', $mom_report_country);
                }
            })->where(function ($query) use ($mom_report_user) {
                if ($mom_report_user != '') {
                    $query->where('moms.created_by', $mom_report_user);
                }
            })
            ->whereIn('moms.created_by', $userIds)
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get()->toArray();

        $totalRecords = DB::table('moms')
            ->leftJoin('clients', 'clients.id', '=', 'moms.client_id')
            ->leftJoin('countries', 'countries.id', '=', 'clients.country_id')
            ->leftJoin('cities', 'cities.id', '=', 'clients.city_id')
            ->leftJoin('users as manage_user', 'manage_user.id', '=', 'clients.manage_by')
            ->leftJoin('users as added_by', 'added_by.id', '=', 'moms.created_by')
            ->select('moms.*', 'moms.id as meeting_id', 'manage_user.name as manage_by_username', 'added_by.name as added_by_username', 'clients.*', 'countries.country_name as country_name', 'cities.city_name as city_name')
            ->where('moms.is_deleted', null)
            ->where(function ($query) use ($searchValue) {
                $query->where('moms.id', 'like', '%' . $searchValue . '%')
                    ->orWhere('contact_person', 'like', '%' . $searchValue . '%')
                    ->orWhere('country_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('company_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('meeting_date', 'like', '%' . $searchValue . '%')
                    ->orWhere('manage_user.name', 'like', '%' . $searchValue . '%')
                    ->orWhere('phone_no', 'like', '%' . $searchValue . '%')
                    ->orWhere('city_name', 'like', '%' . $searchValue . '%')
                    ->orWhereDate('minutes_of_meeting', date('Y-m-d', strtotime($searchValue)))
                    ->orWhere('added_by.name', 'like', '%' . $searchValue . '%')
                    ->orWhereDate('next_followup_date', date('Y-m-d', strtotime($searchValue)))
                    ->orWhere('clients.email', 'like', '%' . $searchValue . '%')
                    ->orWhere('address', 'like', '%' . $searchValue . '%')
                    ->orWhere('bde_feedback', 'like', '%' . $searchValue . '%');
            })
            ->where(function ($query) use ($mom_report_from_date, $mom_report_to_date) {
                if ($mom_report_from_date != '' && $mom_report_to_date != '') {
                    $query->whereBetween('meeting_date', [$mom_report_from_date, $mom_report_to_date]);
                }
            })->where(function ($query) use ($mom_report_company_name) {
                if ($mom_report_company_name != '') {
                    $query->where('company_name', $mom_report_company_name);
                }
            })->where(function ($query) use ($mom_report_country) {
                if ($mom_report_country != '') {
                    $query->where('clients.country_id', $mom_report_country);
                }
            })->where(function ($query) use ($mom_report_user) {
                if ($mom_report_user != '') {
                    $query->where('moms.created_by', $mom_report_user);
                }
            })
            ->whereIn('moms.created_by', $userIds)
            ->count();

        $totalRecordswithFilter = DB::table('moms')
            ->leftJoin('clients', 'clients.id', '=', 'moms.client_id')
            ->leftJoin('countries', 'countries.id', '=', 'clients.country_id')
            ->leftJoin('cities', 'cities.id', '=', 'clients.city_id')
            ->leftJoin('users as manage_user', 'manage_user.id', '=', 'clients.manage_by')
            ->leftJoin('users as added_by', 'added_by.id', '=', 'moms.created_by')
            ->select('moms.*', 'moms.id as meeting_id', 'manage_user.name as manage_by_username', 'added_by.name as added_by_username', 'clients.*', 'countries.country_name as country_name', 'cities.city_name as city_name')
            ->where('moms.is_deleted', null)
            ->where(function ($query) use ($searchValue) {
                $query->where('moms.id', 'like', '%' . $searchValue . '%')
                    ->orWhere('contact_person', 'like', '%' . $searchValue . '%')
                    ->orWhere('country_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('company_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('meeting_date', 'like', '%' . $searchValue . '%')
                    ->orWhere('manage_user.name', 'like', '%' . $searchValue . '%')
                    ->orWhere('phone_no', 'like', '%' . $searchValue . '%')
                    ->orWhere('city_name', 'like', '%' . $searchValue . '%')
                    ->orWhereDate('minutes_of_meeting', date('Y-m-d', strtotime($searchValue)))
                    ->orWhere('added_by.name', 'like', '%' . $searchValue . '%')
                    ->orWhereDate('next_followup_date', date('Y-m-d', strtotime($searchValue)))
                    ->orWhere('clients.email', 'like', '%' . $searchValue . '%')
                    ->orWhere('address', 'like', '%' . $searchValue . '%')
                    ->orWhere('bde_feedback', 'like', '%' . $searchValue . '%');
            })
            ->where(function ($query) use ($mom_report_from_date, $mom_report_to_date) {
                if ($mom_report_from_date != '' && $mom_report_to_date != '') {
                    $query->whereBetween('meeting_date', [$mom_report_from_date, $mom_report_to_date]);
                }
            })->where(function ($query) use ($mom_report_company_name) {
                if ($mom_report_company_name != '') {
                    $query->where('company_name', $mom_report_company_name);
                }
            })->where(function ($query) use ($mom_report_country) {
                if ($mom_report_country != '') {
                    $query->where('clients.country_id', $mom_report_country);
                }
            })->where(function ($query) use ($mom_report_user) {
                if ($mom_report_user != '') {
                    $query->where('moms.created_by', $mom_report_user);
                }
            })
            ->whereIn('moms.created_by', $userIds)
            ->count();


        //  dd($users_data);

        // echo "<pre>";
        // print_r($users_data);
        // echo "</pre>";
        // die;


        // dd($users_data);
        $records = $users_data;

        $user = Auth::user();
        $user_permissions = $user->getAllPermissions()->pluck('name')->toArray();
        $user_edit_permissions_check = 'user_edit';
        $user_delete_permissions_check = 'user_delete';
        $user_edit_permissions = in_array($user_edit_permissions_check, $user_permissions);
        $user_delete_permissions = in_array($user_delete_permissions_check, $user_permissions);

        $dataArr = array();
        $i = $start + 1;
        foreach ($records as $record) {

            $dataArr[] = array(
                'DT_RowIndex' => $i++,
                'meeting_id' => $record->meeting_id,
                'meeting_date' => date('d/m/Y', strtotime($record->meeting_date)),
                'country_name' => $record->country_name,
                'assigned_to' => $record->manage_by_username,
                'company_name' => $record->company_name,
                'contact_person' => $record->contact_person,
                'address' => $record->address,
                'city_name' => $record->city_name,
                'phone_no' => $record->phone_no,
                'email' => $record->email,
                'minutes_of_meeting' => $record->minutes_of_meeting,
                'bde_feedback' => $record->bde_feedback,
                'next_followup_date' => date('d/m/Y', strtotime($record->next_followup_date)),
                'added_by_username' => $record->added_by_username,
            );
        }


        // $logData = array(
        //     'user_id' => auth()->user()->id,
        //     'action_id' => $user->id,
        //     'action_type' => 'view',
        //     'module' => 'user',
        //     'description' => 'User list view',
        // );

        // $storeLog = SystemLogController::addLog($logData);
        return response()->json([
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecordswithFilter,
            "data" => $dataArr,
        ]);
    }

    public function get_company_by_country(Request $request)
    {
        $country_id = $request->country_id;
        $auth_user_role = Auth::user()->roles()->first()->name;
        $user = Auth::user();

        $MOMController = new MOMController;
        $userIds = $MOMController->getHirarchyUser($user->id);
        $userIds[] = $user->id;

        $user_country_id = $user->country_id;
        if ($country_id == '') {
            //all Client records
            $company = Client::select('company_name', 'id')->where('is_deleted', null)->get();
            $all_company_id = array();
            foreach ($company as $key => $value) {
                $all_company_id[] = $value['id'];
            }
        } else {

            if ($auth_user_role == 'general manager') {
            } else {
                $company = Client::where('country_id', $country_id)->get()->toArray();
                $all_company_id = array();
                foreach ($company as $key => $value) {
                    $all_company_id[] = $value['id'];
                }
                $data = DB::table('moms')
                    ->join('clients', 'clients.id', '=', 'moms.client_id')
                    ->select('clients.id', 'clients.company_name')
                    ->whereIn('client_id', $all_company_id)
                    ->where('moms.is_deleted', null)
                    ->get()->toArray();

                $data = array_map("unserialize", array_unique(array_map("serialize", $data)));

                $view = view('backend.reports.get_company_by_country', compact('data'))->render();
                return response()->json(['html' => $view]);
            }
        }
    }

    public function get_company_users(Request $request)
    {
        $company_id = $request->company_id;

        if ($company_id == '') {
            $company_users = DB::table('moms')
                ->join('users', 'users.id', '=', 'moms.created_by')
                ->select('users.id', 'users.name')
                ->get()->toArray();
        } else {
            $company_users = DB::table('moms')
                ->join('users', 'users.id', '=', 'moms.created_by')
                ->select('users.id', 'users.name')
                ->where('client_id', $company_id)
                ->get()->toArray();
        }
        $company_users = array_map("unserialize", array_unique(array_map("serialize", $company_users)));

        // dd($company_users);

        $view = view('backend.reports.get_company_users', compact('company_users'))->render();
        return response()->json(['html' => $view]);
    }

    public function export_mom_data(Request $request)
    {
        $user = Auth::user();
        $MOMController = new MOMController;
        $userIds = $MOMController->getHirarchyUser($user->id);
        $userIds[] = $user->id;

        $mom_report_from_date = $request->mom_report_meeting_from_date;
        $mom_report_to_date = $request->mom_report_meeting_to_date;
        $mom_report_company_name = $request->mom_report_company_name;
        $mom_report_country = $request->mom_report_country;
        $mom_report_user = $request->mom_report_user;

        if ($mom_report_from_date != '' && $mom_report_to_date != '') {
            $mom_report_from_date = date('Y-m-d', strtotime($mom_report_from_date));
            $mom_report_to_date = date('Y-m-d', strtotime($mom_report_to_date));
        }
        if ($mom_report_company_name != '') {
            $mom_report_company_id = $mom_report_company_name;
            $mom_report_company_name = Client::where('id', $mom_report_company_id)->first()->company_name;
        } else {
            $mom_report_company_name = '';
        }
        if ($mom_report_country != '') {
            $mom_report_country = $mom_report_country;
        } else {
            $mom_report_country = '';
        }
        if ($mom_report_user != '') {
            $mom_report_user = $mom_report_user;
        } else {
            $mom_report_user = '';
        }
        $users_data = DB::table('moms')
            ->leftJoin('clients', 'clients.id', '=', 'moms.client_id')
            ->leftJoin('countries', 'countries.id', '=', 'clients.country_id')
            ->leftJoin('cities', 'cities.id', '=', 'clients.city_id')
            ->leftJoin('users as manage_user', 'manage_user.id', '=', 'clients.manage_by')
            ->leftJoin('users as added_by', 'added_by.id', '=', 'moms.created_by')
            ->select('moms.*', 'moms.id as meeting_id', 'manage_user.name as manage_by_username', 'added_by.name as added_by_username', 'clients.*', 'countries.country_name as country_name', 'cities.city_name as city_name')
            ->where('moms.is_deleted', null)
            ->where(function ($query) use ($mom_report_from_date, $mom_report_to_date) {
                if ($mom_report_from_date != '' && $mom_report_to_date != '') {
                    $query->whereBetween('meeting_date', [$mom_report_from_date, $mom_report_to_date]);
                }
            })->where(function ($query) use ($mom_report_company_name) {
                if ($mom_report_company_name != '') {
                    $query->where('company_name', $mom_report_company_name);
                }
            })->where(function ($query) use ($mom_report_country) {
                if ($mom_report_country != '') {
                    $query->where('clients.country_id', $mom_report_country);
                }
            })->where(function ($query) use ($mom_report_user) {
                if ($mom_report_user != '') {
                    $query->where('moms.created_by', $mom_report_user);
                }
            })
            ->whereIn('moms.created_by', $userIds)
            ->get()->toArray();

        $records = $users_data;

        //remove export_report_data session
        if (Session::has('export_report_data')) {
            Session::forget('export_report_data');
        }

        $dataArr = array();
        $dataArr[] = array(
            'No.',
            'Meeting Date',
            'Country',
            'Assigned To.',
            'Company',
            'Contact Person',
            'Address',
            'City',
            'Phone Number',
            'Email Address',
            'Minutes of Meeting',
            'BDE Feedback',
            'FollowUp Date',
            'Added By',
        );
        $i = +1;
        foreach ($records as $record) {
            $dataArr[] = array(
                (string)$i++,
                (string)date('d/m/Y', strtotime($record->meeting_date)),
                (string)$record->country_name,
                (string)$record->manage_by_username,
                (string)$record->company_name,
                (string)$record->contact_person,
                (string)$record->address,
                (string)$record->city_name,
                (string)$record->phone_no,
                (string)$record->email,
                (string)$record->minutes_of_meeting,
                (string)$record->bde_feedback,
                (string)date('d/m/Y', strtotime($record->next_followup_date)),
                (string)$record->added_by_username,
            );
        }

       

        if (count((array)$dataArr) > 0) {
            $request->session()->put('export_report_data', $dataArr);
            $fileName = 'MOM_Report_' . date('Ymd') . '.xlsx';
            return Excel::download(new ExportReports, $fileName);
        } else {
            return back()->with('error', 'No record available for export');
        }
    }

    public function call_status_report()
    {
        $selected_menu = 'call_status_report';

        $all_countries = Country::all()->toArray();

        return view('backend.reports.call_status_report', compact('selected_menu', 'all_countries'));
    }

    public function call_status_report_export(Request $request)
    {
        $call_status_report_from_date = $request->call_status_report_meeting_from_date;
        $call_status_report_to_date = $request->call_status_report_meeting_to_date;
        $call_status_report_country = $request->call_status_report_country;
        $call_status_report_user = $request->call_status_report_user;


        if ($call_status_report_from_date != '' || $call_status_report_to_date != '' || $call_status_report_country != '' || $call_status_report_user != '') {
            if ($call_status_report_from_date != '' && $call_status_report_to_date != '') {
                $call_status_report_from_date = date('Y-m-d', strtotime($call_status_report_from_date));
                $call_status_report_to_date = date('Y-m-d', strtotime($call_status_report_to_date));
                $call_status_report_to_date = date('Y-m-d', strtotime($call_status_report_to_date . ' +1 day'));
            }
            if ($call_status_report_country != '') {
                $call_status_report_country = $call_status_report_country;
            } else {
                $call_status_report_country = '';
            }
            if ($call_status_report_user != '') {
                $call_status_report_user = $call_status_report_user;
            } else {
                $call_status_report_user = '';
            }

            $call_status_data = DB::table('system_logs')
                ->join('users', 'users.id', '=', 'system_logs.user_id')
                ->where('call_type', '!=', null)
                ->where('action_type', '=', 'update')
                ->where(function ($query) use ($call_status_report_from_date, $call_status_report_to_date, $call_status_report_country, $call_status_report_user) {
                    if ($call_status_report_from_date != '' && $call_status_report_to_date != '') {
                        $query->whereBetween('system_logs.created_at', [$call_status_report_from_date, $call_status_report_to_date]);
                    }
                    if ($call_status_report_country != '') {
                        $query->where('users.country_id', $call_status_report_country);
                    }
                    if ($call_status_report_user != '') {
                        $query->where('system_logs.user_id', $call_status_report_user);
                    }
                })
                ->select('system_logs.call_status', DB::raw('count(*) as total'), DB::raw("FORMAT(system_logs.updated_at, 'yyyy-MM-d') as call_date"))
                ->groupBy('system_logs.call_status')
                ->groupBy(DB::raw("FORMAT(system_logs.updated_at, 'yyyy-MM-d')"))
                ->get()->toArray();


            $is_requirement_data = DB::table('leads')
                ->join('users', 'users.id', '=', 'leads.created_by')
                ->where('is_requirement', '=', 1)
                ->where(function ($query) use ($call_status_report_from_date, $call_status_report_to_date, $call_status_report_country, $call_status_report_user) {
                    if ($call_status_report_from_date != '' && $call_status_report_to_date != '') {
                        $query->whereBetween('leads.created_at', [$call_status_report_from_date, $call_status_report_to_date]);
                    }
                    if ($call_status_report_country != '') {
                        $query->where('users.country_id', $call_status_report_country);
                    }
                    if ($call_status_report_user != '') {
                        $query->where('leads.created_by', $call_status_report_user);
                    }
                })
                ->select(DB::raw('count(*) as total_leads'), DB::raw("FORMAT(leads.created_at, 'yyyy-MM-d') as is_requirement_call_date"))
                ->groupBy(DB::raw("FORMAT(leads.created_at, 'yyyy-MM-d')"))
                ->get()->toArray();

            $report_data = [];

            $calling_status_list = array();
            $calling_status_list[0] = "not_called";
            $calling_status_list[1] = "busy";
            $calling_status_list[2] = "call_later";
            $calling_status_list[3] = "called";
            $calling_status_list[4] = "do_not_call_again";
            $calling_status_list[5] = "no_requirements";
            $calling_status_list[6] = "not_reachable";
            $calling_status_list[7] = "out_of_service";
            $calling_status_list[8] = "ringing";
            $calling_status_list[9] = "swich_off";
            $calling_status_list[10] = "wrong_number";


            foreach ($call_status_data as $key => $value) {
                if ($value->call_date != null) {
                    foreach ($calling_status_list as $key1 => $value1) {
                        if ($key1 == $value->call_status) {
                            $report_data[$value->call_date][$value1] = $value->total;
                        } else {
                            if (!isset($report_data[$value->call_date][$value1])) {
                                $report_data[$value->call_date][$value1] = 0;
                            }
                        }
                    }
                }
            }

            foreach ($report_data as $key => $value) {
                $report_data[$key]['total'] = array_sum($value);
            }
            foreach ($is_requirement_data as $key => $value) {
                if ($value->is_requirement_call_date != null) {
                    $report_data[$value->is_requirement_call_date]['is_requirement'] = $value->total_leads;
                } else {
                    $report_data[$value->is_requirement_call_date]['is_requirement'] = 0;
                }
            }

            $final_report_data = array();
            foreach ($report_data as $key => $value) {
                $final_report_data[] = array(
                    'last_call_date' => $key,
                    'not_called' => $value['not_called'] ?? 0,
                    'busy' => $value['busy'] ?? 0,
                    'call_later' => $value['call_later'] ?? 0,
                    'called' => $value['called'] ?? 0,
                    'is_requirement' => (isset($value['is_requirement']) ? $value['is_requirement'] : 0),
                    'do_not_call_again' => $value['do_not_call_again'] ?? 0,
                    'no_requirements' => $value['no_requirements'] ?? 0,
                    'not_reachable' => $value['not_reachable'] ?? 0,
                    'out_of_service' => $value['out_of_service'] ?? 0,
                    'ringing' => $value['ringing'] ?? 0,
                    'swich_off' => $value['swich_off'] ?? 0,
                    'wrong_number' => $value['wrong_number'] ?? 0,
                    'total' => $value['total'] ?? 0,
                );
            }

            if (count($final_report_data) > 0) {
                $final_report_data[] = array(
                    'last_call_date' => 'Total',
                    'not_called' => array_sum(array_column($final_report_data, 'not_called')),
                    'busy' => array_sum(array_column($final_report_data, 'busy')),
                    'call_later' => array_sum(array_column($final_report_data, 'call_later')),
                    'called' => array_sum(array_column($final_report_data, 'called')),
                    'is_requirement' => array_sum(array_column($final_report_data, 'is_requirement')),
                    'do_not_call_again' => array_sum(array_column($final_report_data, 'do_not_call_again')),
                    'no_requirements' => array_sum(array_column($final_report_data, 'no_requirements')),
                    'not_reachable' => array_sum(array_column($final_report_data, 'not_reachable')),
                    'out_of_service' => array_sum(array_column($final_report_data, 'out_of_service')),
                    'ringing' => array_sum(array_column($final_report_data, 'ringing')),
                    'swich_off' => array_sum(array_column($final_report_data, 'swich_off')),
                    'wrong_number' => array_sum(array_column($final_report_data, 'wrong_number')),
                    'total' => array_sum(array_column($final_report_data, 'total')),
                );
            }

            $records = $final_report_data;

            //remove export_report_data session
            if (Session::has('export_report_data')) {
                Session::forget('export_report_data');
            }

            $dataArr = array();
            $dataArr[] = array('Date',
                'Busy',
                'Call Later',
                'Called',
                'Is Requirement',
                'Do Not Call Again',
                'No Requirement',
                'Not Reachable',
                'Out Of Service',
                'Ringing',
                'Switch Off',
                'Wrong Number',
                'Total');
            foreach ($records as $record) {

                if ($record['last_call_date'] == 'Total') {
                    $last_call_date = $record['last_call_date'];
                } else {
                    $last_call_date = date('d/m/Y', strtotime($record['last_call_date']));
                }

                $dataArr[] = array(
                    (string)$last_call_date,
                    (string)$record['busy'],
                    (string)$record['call_later'],
                    (string)$record['called'],
                    (string)$record['is_requirement'],
                    (string)$record['do_not_call_again'],
                    (string)$record['no_requirements'],
                    (string)$record['not_reachable'],
                    (string)$record['out_of_service'],
                    (string)$record['ringing'],
                    (string)$record['swich_off'],
                    (string)$record['wrong_number'],
                    (string)$record['total'],
                );
            }

           
            if (count((array)$dataArr) > 0) {
                $request->session()->put('export_report_data', $dataArr);
                $fileName = 'Call_Status_Report_' . date('Ymd') . '.xlsx';
                return Excel::download(new ExportReports, $fileName);
            } else {
                return back()->with('error', 'No record available for export');
            }
        } else {
            return back()->with('error', 'Please select any one of the filter');
        }
    }

    public function call_status_report_country_user(Request $request)
    {

        $country_id = $request->country_id;

        $company_users = User::where('country_id', $country_id)
            ->select('id', 'name')
            ->where('is_deleted', null)
            ->get()->toArray();

        $company_users = array_map("unserialize", array_unique(array_map("serialize", $company_users)));

        $view = view('backend.reports.get_call_status_country_users', compact('company_users'))->render();
        return response()->json(['html' => $view]);
    }

    public function call_status_report_table_view(Request $request)
    {

        $data = $request->all();

        $view = view('backend.reports.call_status_report_table', compact('data'))->render();
        return response()->json(['html' => $view]);
    }

    public function get_call_status_report_data(Request $request)
    {


        $call_status_report_from_date = $request->call_status_report_from_date;
        $call_status_report_to_date = $request->call_status_report_to_date;
        $call_status_report_country = $request->call_status_report_country;
        $call_status_report_user = $request->call_status_report_user;

        if ($call_status_report_from_date != '' && $call_status_report_to_date != '') {
            $call_status_report_from_date = date('Y-m-d', strtotime($call_status_report_from_date));
            $call_status_report_to_date = date('Y-m-d', strtotime($call_status_report_to_date));
            $call_status_report_to_date = date('Y-m-d', strtotime($call_status_report_to_date . ' +1 day'));
        }
        if ($call_status_report_country != '') {
            $call_status_report_country = $call_status_report_country;
        } else {
            $call_status_report_country = '';
        }
        if ($call_status_report_user != '') {
            $call_status_report_user = $call_status_report_user;
        } else {
            $call_status_report_user = '';
        }

        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page
        $rowperpage = $rowperpage - 1;

        $columnIndexArr = $request->get('order');
        $columnNameArr = $request->get('columns');
        $orderArr = $request->get('order');
        $searchArr = $request->get('search');

        $columnIndex = isset($columnIndexArr[0]['column']) ? $columnIndexArr[0]['column'] : ''; // Column index
        $columnName = !empty($columnIndex) ? $columnNameArr[$columnIndex]['data'] : 'moms.id'; // Column name
        $columnSortOrder = !empty($columnIndex) ? $orderArr[0]['dir'] : 'Asc'; // asc or desc
        $searchValue = $searchArr['value']; // Search value


        $call_status_data = DB::table('system_logs')
            ->join('users', 'users.id', '=', 'system_logs.user_id')
            ->leftjoin('leads', 'leads.temp_lead_id', '=', 'system_logs.action_id')
            ->where('call_type', '!=', null)
            ->where('action_type', '=', 'update')
            ->where(function ($query) use ($call_status_report_from_date, $call_status_report_to_date, $call_status_report_country, $call_status_report_user) {
                if ($call_status_report_from_date != '' && $call_status_report_to_date != '') {
                    $query->whereBetween('system_logs.created_at', [$call_status_report_from_date, $call_status_report_to_date]);
                }
                if ($call_status_report_country != '') {
                    $query->where('users.country_id', $call_status_report_country);
                }
                if ($call_status_report_user != '') {
                    $query->where('system_logs.user_id', $call_status_report_user);
                }
            })
            ->select('system_logs.call_status', DB::raw('count(system_logs.call_status) as total'), DB::raw("FORMAT(system_logs.updated_at, 'yyyy-MM-d') as call_date"), DB::raw('count(leads.is_requirement) as re'))
            ->groupBy('system_logs.call_status')
            ->groupBy(DB::raw("FORMAT(system_logs.updated_at, 'yyyy-MM-d')"))
            ->skip($start)
            ->take($rowperpage)
            ->get()->toArray();

        $totalRecords = 0;
        $totalRecordswithFilter = 0;

        $total_call_status_data = DB::table('system_logs')
            ->join('users', 'users.id', '=', 'system_logs.user_id')
            ->leftjoin('leads', 'leads.temp_lead_id', '=', 'system_logs.action_id')
            ->where('call_type', '!=', null)
            ->where('action_type', '=', 'update')
            ->where(function ($query) use ($call_status_report_from_date, $call_status_report_to_date, $call_status_report_country, $call_status_report_user) {
                if ($call_status_report_from_date != '' && $call_status_report_to_date != '') {
                    $query->whereBetween('system_logs.created_at', [$call_status_report_from_date, $call_status_report_to_date]);
                }
                if ($call_status_report_country != '') {
                    $query->where('users.country_id', $call_status_report_country);
                }
                if ($call_status_report_user != '') {
                    $query->where('system_logs.user_id', $call_status_report_user);
                }
            })
            ->select('system_logs.call_status', DB::raw('count(system_logs.call_status) as total'), DB::raw("FORMAT(system_logs.updated_at, 'yyyy-MM-d') as call_date"), DB::raw('count(leads.is_requirement) as re'))
            ->groupBy('system_logs.call_status')
            ->groupBy(DB::raw("FORMAT(system_logs.updated_at, 'yyyy-MM-d')"))
            ->get()->toArray();

        $is_requirement_data = DB::table('leads')
            ->join('users', 'users.id', '=', 'leads.created_by')
            ->where('is_requirement', '=', 1)
            ->where(function ($query) use ($call_status_report_from_date, $call_status_report_to_date, $call_status_report_country, $call_status_report_user) {
                if ($call_status_report_from_date != '' && $call_status_report_to_date != '') {
                    $query->whereBetween('leads.created_at', [$call_status_report_from_date, $call_status_report_to_date]);
                }
                if ($call_status_report_country != '') {
                    $query->where('users.country_id', $call_status_report_country);
                }
                if ($call_status_report_user != '') {
                    $query->where('leads.created_by', $call_status_report_user);
                }
            })
            ->select(DB::raw('count(*) as total_leads'), DB::raw("FORMAT(leads.created_at, 'yyyy-MM-d') as is_requirement_call_date"))
            ->groupBy(DB::raw("FORMAT(leads.created_at, 'yyyy-MM-d')"))
            ->get()->toArray();

        // echo "<pre>";
        // print_r($is_requirement_data);
        // echo "</pre>";
        // exit;

        $report_data = [];
        $total_report_data = [];

        $calling_status_list = array();
        $calling_status_list[0] = "not_called";
        $calling_status_list[1] = "busy";
        $calling_status_list[2] = "call_later";
        $calling_status_list[3] = "called";
        $calling_status_list[4] = "do_not_call_again";
        $calling_status_list[5] = "no_requirements";
        $calling_status_list[6] = "not_reachable";
        $calling_status_list[7] = "out_of_service";
        $calling_status_list[8] = "ringing";
        $calling_status_list[9] = "swich_off";
        $calling_status_list[10] = "wrong_number";


        foreach ($call_status_data as $key => $value) {
            if ($value->call_date != null) {
                foreach ($calling_status_list as $key1 => $value1) {
                    if ($key1 == $value->call_status) {
                        $report_data[$value->call_date][$value1] = $value->total;
                    } else {
                        if (!isset($report_data[$value->call_date][$value1])) {
                            $report_data[$value->call_date][$value1] = 0;
                        }
                    }
                }
            }
        }

        foreach ($total_call_status_data as $key => $value) {
            if ($value->call_date != null) {
                foreach ($calling_status_list as $key1 => $value1) {
                    if ($key1 == $value->call_status) {
                        $total_report_data[$value->call_date][$value1] = $value->total;
                    } else {
                        if (!isset($total_report_data[$value->call_date][$value1])) {
                            $total_report_data[$value->call_date][$value1] = 0;
                        }
                    }
                }
            }
        }

        foreach ($report_data as $key => $value) {
            $report_data[$key]['total'] = array_sum($value);
        }

        foreach ($total_report_data as $key => $value) {
            $total_report_data[$key]['total'] = array_sum($value);
        }

        foreach ($is_requirement_data as $key => $value) {
            if ($value->is_requirement_call_date != null) {
                $report_data[$value->is_requirement_call_date]['is_requirement'] = $value->total_leads;
            } else {
                $report_data[$value->is_requirement_call_date]['is_requirement'] = 0;
            }
        }

        foreach ($is_requirement_data as $key => $value) {
            if ($value->is_requirement_call_date != null) {
                $total_report_data[$value->is_requirement_call_date]['is_requirement'] = $value->total_leads;
            } else {
                $total_report_data[$value->is_requirement_call_date]['is_requirement'] = 0;
            }
        }

        //dd($report_data);

        $final_report_data = array();
        foreach ($report_data as $key => $value) {
            $final_report_data[] = array(
                'last_call_date' => $key,
                'not_called' => $value['not_called'] ?? 0,
                'busy' => $value['busy'] ?? 0,
                'call_later' => $value['call_later'] ?? 0,
                'called' => $value['called'] ?? 0,
                'is_requirement' => (isset($value['is_requirement']) ? $value['is_requirement'] : 0),
                'do_not_call_again' => $value['do_not_call_again'] ?? 0,
                'no_requirements' => $value['no_requirements'] ?? 0,
                'not_reachable' => $value['not_reachable'] ?? 0,
                'out_of_service' => $value['out_of_service'] ?? 0,
                'ringing' => $value['ringing'] ?? 0,
                'swich_off' => $value['swich_off'] ?? 0,
                'wrong_number' => $value['wrong_number'] ?? 0,
                'total' => $value['total'] ?? 0,
            );
        }

        $final_total_report_data = array();
        foreach ($total_report_data as $key => $value) {
            $final_total_report_data[] = array(
                'last_call_date' => $key,
                'not_called' => $value['not_called'] ?? 0,
                'busy' => $value['busy'] ?? 0,
                'call_later' => $value['call_later'] ?? 0,
                'called' => $value['called'] ?? 0,
                'is_requirement' => (isset($value['is_requirement']) ? $value['is_requirement'] : 0),
                'do_not_call_again' => $value['do_not_call_again'] ?? 0,
                'no_requirements' => $value['no_requirements'] ?? 0,
                'not_reachable' => $value['not_reachable'] ?? 0,
                'out_of_service' => $value['out_of_service'] ?? 0,
                'ringing' => $value['ringing'] ?? 0,
                'swich_off' => $value['swich_off'] ?? 0,
                'wrong_number' => $value['wrong_number'] ?? 0,
                'total' => $value['total'] ?? 0,
            );
        }

        if (count($final_report_data) > 0) {
            $final_report_data[] = array(
                'last_call_date' => 'Total',
                'not_called' => array_sum(array_column($final_report_data, 'not_called')),
                'busy' => array_sum(array_column($final_report_data, 'busy')),
                'call_later' => array_sum(array_column($final_report_data, 'call_later')),
                'called' => array_sum(array_column($final_report_data, 'called')),
                'is_requirement' => array_sum(array_column($final_report_data, 'is_requirement')),
                'do_not_call_again' => array_sum(array_column($final_report_data, 'do_not_call_again')),
                'no_requirements' => array_sum(array_column($final_report_data, 'no_requirements')),
                'not_reachable' => array_sum(array_column($final_report_data, 'not_reachable')),
                'out_of_service' => array_sum(array_column($final_report_data, 'out_of_service')),
                'ringing' => array_sum(array_column($final_report_data, 'ringing')),
                'swich_off' => array_sum(array_column($final_report_data, 'swich_off')),
                'wrong_number' => array_sum(array_column($final_report_data, 'wrong_number')),
                'total' => array_sum(array_column($final_report_data, 'total')),
            );
        }

        if (count($final_total_report_data) > 0) {
            $final_total_report_data[] = array(
                'last_call_date' => 'Total',
                'not_called' => array_sum(array_column($final_total_report_data, 'not_called')),
                'busy' => array_sum(array_column($final_total_report_data, 'busy')),
                'call_later' => array_sum(array_column($final_total_report_data, 'call_later')),
                'called' => array_sum(array_column($final_total_report_data, 'called')),
                'is_requirement' => array_sum(array_column($final_total_report_data, 'is_requirement')),
                'do_not_call_again' => array_sum(array_column($final_total_report_data, 'do_not_call_again')),
                'no_requirements' => array_sum(array_column($final_total_report_data, 'no_requirements')),
                'not_reachable' => array_sum(array_column($final_total_report_data, 'not_reachable')),
                'out_of_service' => array_sum(array_column($final_total_report_data, 'out_of_service')),
                'ringing' => array_sum(array_column($final_total_report_data, 'ringing')),
                'swich_off' => array_sum(array_column($final_total_report_data, 'swich_off')),
                'wrong_number' => array_sum(array_column($final_total_report_data, 'wrong_number')),
                'total' => array_sum(array_column($final_total_report_data, 'total')),
            );
        }

        $records = $final_report_data;
        $totalRecords = count($final_total_report_data);
        $totalRecordswithFilter = count($final_total_report_data);
        $user = Auth::user();
        $user_permissions = $user->getAllPermissions()->pluck('name')->toArray();
        $user_edit_permissions_check = 'user_edit';
        $user_delete_permissions_check = 'user_delete';
        $user_edit_permissions = in_array($user_edit_permissions_check, $user_permissions);
        $user_delete_permissions = in_array($user_delete_permissions_check, $user_permissions);

        $dataArr = array();
        $i = $start + 1;
        foreach ($records as $record) {

            if ($record['last_call_date'] == 'Total') {
                $last_call_date = '<b>' . $record['last_call_date'] . '</b>';
            } else {
                $last_call_date = date('d/m/Y', strtotime($record['last_call_date']));
            }

            $dataArr[] = array(
                'DT_RowIndex' => $i++,
                'last_call_date' => $last_call_date,
                'busy' => $record['busy'],
                'call_later' => $record['call_later'],
                'called' => $record['called'],
                'is_requirement' => $record['is_requirement'],
                'do_not_call_again' => $record['do_not_call_again'],
                'no_requirements' => $record['no_requirements'],
                'not_reachable' => $record['not_reachable'],
                'out_of_service' => $record['out_of_service'],
                'ringing' => $record['ringing'],
                'swich_off' => $record['swich_off'],
                'wrong_number' => $record['wrong_number'],
                'total' => $record['total'],
            );
        }

        // $logData = array(
        //     'user_id' => auth()->user()->id,
        //     'action_id' => $user->id,
        //     'action_type' => 'view',
        //     'module' => 'user',
        //     'description' => 'User list view',
        // );

        // $storeLog = SystemLogController::addLog($logData);
        return response()->json([
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecordswithFilter,
            "data" => $dataArr,
        ]);
    }

    public function call_status_uw_report()
    {
        $selected_menu = 'call_status_uw_report';
        $all_countries = Country::all()->toArray();
        return view('backend.reports.call_status_uw_report', compact('selected_menu', 'all_countries'));
    }

    public function call_status_uw_report_table_view(Request $request)
    {
        $data = $request->all();
        $view = view('backend.reports.call_status_uw_report_table', compact('data'))->render();
        return response()->json(['html' => $view]);
    }

    public function call_status_uw_report_export(Request $request)
    {
        //dd($request->all());
        $call_status_uw_report_from_date = $request->call_status_uw_report_meeting_from_date;
        $call_status_uw_report_to_date = $request->call_status_uw_report_meeting_to_date;
        $call_status_uw_report_country = $request->call_status_uw_report_country;

        if ($call_status_uw_report_from_date != '' || $call_status_uw_report_to_date != '' || $call_status_uw_report_country != '') {
            if ($call_status_uw_report_from_date != '' && $call_status_uw_report_to_date != '') {
                $call_status_uw_report_from_date = date('Y-m-d', strtotime($call_status_uw_report_from_date));
                $call_status_uw_report_to_date = date('Y-m-d', strtotime($call_status_uw_report_to_date));
                $call_status_uw_report_to_date = date('Y-m-d', strtotime($call_status_uw_report_to_date . ' +1 day'));
            }
            if ($call_status_uw_report_country != '') {
                $call_status_uw_report_country = $call_status_uw_report_country;
            } else {
                $call_status_uw_report_country = '';
            }

            $call_status_data = DB::table('system_logs')
                ->join('users', 'users.id', '=', 'system_logs.user_id')
                ->where('call_type', '!=', null)
                ->where('action_type', '=', 'update')
                ->where(function ($query) use ($call_status_uw_report_from_date, $call_status_uw_report_to_date, $call_status_uw_report_country) {
                    if ($call_status_uw_report_from_date != '' && $call_status_uw_report_to_date != '') {
                        $query->whereBetween('system_logs.created_at', [$call_status_uw_report_from_date, $call_status_uw_report_to_date]);
                    }
                    if ($call_status_uw_report_country != '') {
                        $query->where('users.country_id', $call_status_uw_report_country);
                    }
                })
                ->select('system_logs.call_status', DB::raw('count(system_logs.call_status) as total'), 'users.name')
                ->groupBy('system_logs.call_status')
                ->groupBy('users.name')
                ->get()->toArray();

            $report_data = [];

            $calling_status_list = array();
            $calling_status_list[0] = "not_called";
            $calling_status_list[1] = "busy";
            $calling_status_list[2] = "call_later";
            $calling_status_list[3] = "called";
            $calling_status_list[4] = "do_not_call_again";
            $calling_status_list[5] = "no_requirements";
            $calling_status_list[6] = "not_reachable";
            $calling_status_list[7] = "out_of_service";
            $calling_status_list[8] = "ringing";
            $calling_status_list[9] = "swich_off";
            $calling_status_list[10] = "wrong_number";


            foreach ($call_status_data as $key => $value) {
                if ($value->name != null) {
                    foreach ($calling_status_list as $key1 => $value1) {
                        if ($key1 == $value->call_status) {
                            $report_data[$value->name][$value1] = $value->total;
                        } else {
                            if (!isset($report_data[$value->name][$value1])) {
                                $report_data[$value->name][$value1] = 0;
                            }
                        }
                    }
                }
            }

            foreach ($report_data as $key => $value) {
                $report_data[$key]['total'] = array_sum($value);
            }

            $final_report_data = array();
            foreach ($report_data as $key => $value) {
                $final_report_data[] = array(
                    'user_name' => $key,
                    'not_called' => $value['not_called'],
                    'busy' => $value['busy'],
                    'call_later' => $value['call_later'],
                    'called' => $value['called'],
                    'do_not_call_again' => $value['do_not_call_again'],
                    'no_requirements' => $value['no_requirements'],
                    'not_reachable' => $value['not_reachable'],
                    'out_of_service' => $value['out_of_service'],
                    'ringing' => $value['ringing'],
                    'swich_off' => $value['swich_off'],
                    'wrong_number' => $value['wrong_number'],
                    'total' => $value['total'],
                );
            }


            if (count($final_report_data) > 0) {
                $final_report_data[] = array(
                    'user_name' => 'Total',
                    'not_called' => array_sum(array_column($final_report_data, 'not_called')),
                    'busy' => array_sum(array_column($final_report_data, 'busy')),
                    'call_later' => array_sum(array_column($final_report_data, 'call_later')),
                    'called' => array_sum(array_column($final_report_data, 'called')),
                    'do_not_call_again' => array_sum(array_column($final_report_data, 'do_not_call_again')),
                    'no_requirements' => array_sum(array_column($final_report_data, 'no_requirements')),
                    'not_reachable' => array_sum(array_column($final_report_data, 'not_reachable')),
                    'out_of_service' => array_sum(array_column($final_report_data, 'out_of_service')),
                    'ringing' => array_sum(array_column($final_report_data, 'ringing')),
                    'swich_off' => array_sum(array_column($final_report_data, 'swich_off')),
                    'wrong_number' => array_sum(array_column($final_report_data, 'wrong_number')),
                    'total' => array_sum(array_column($final_report_data, 'total')),
                );
            }


            $records = $final_report_data;

            //remove export_report_data session
            if (Session::has('export_report_data')) {
                Session::forget('export_report_data');
            }

            $dataArr = array();
            $dataArr[] = array('User Name', 'Busy', 'Call Later', 'Called', 'Do Not Call Again', 'No Requirement', 'Not Reachable', 'Out Of Service', 'Ringing', 'Switch Off', 'Wrong Number', 'Total');
            foreach ($records as $record) {
                if ($record['user_name'] == 'Total') {
                    $user_name = $record['user_name'];
                } else {
                    $user_name = $record['user_name'];
                }
                $dataArr[] = array($user_name,
                    (string)$record['busy'],
                    (string)$record['call_later'],
                    (string)$record['called'],
                    (string)$record['do_not_call_again'],
                    (string)$record['no_requirements'],
                    (string)$record['not_reachable'],
                    (string)$record['out_of_service'],
                    (string)$record['ringing'],
                    (string)$record['swich_off'],
                    (string)$record['wrong_number'],
                    (string)$record['total'],
                );
            }
           
            if (count((array)$dataArr) > 0) {
                $request->session()->put('export_report_data', $dataArr);
                $fileName = 'Call_Status_UW_Report_' . date('Ymd') . '.xlsx';
                return Excel::download(new ExportReports, $fileName);
            } else {
                return back()->with('error', 'No record available for export');
            }
        } else {
            return back()->with('error', 'Please select any one of the filter');
        }
    }

    public function get_call_status_uw_report_data(Request $request)
    {
        $call_status_uw_report_from_date = $request->call_status_uw_report_from_date;
        $call_status_uw_report_to_date = $request->call_status_uw_report_to_date;
        $call_status_uw_report_country = $request->call_status_uw_report_country;

        if ($call_status_uw_report_from_date != '' && $call_status_uw_report_to_date != '') {
            $call_status_uw_report_from_date = date('Y-m-d', strtotime($call_status_uw_report_from_date));
            $call_status_uw_report_to_date = date('Y-m-d', strtotime($call_status_uw_report_to_date));
            $call_status_uw_report_to_date = date('Y-m-d', strtotime($call_status_uw_report_to_date . ' +1 day'));
        }
        if ($call_status_uw_report_country != '') {
            $call_status_uw_report_country = $call_status_uw_report_country;
        } else {
            $call_status_uw_report_country = '';
        }

        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page
        $rowperpage = $rowperpage - 1;

        $columnIndexArr = $request->get('order');
        $columnNameArr = $request->get('columns');
        $orderArr = $request->get('order');
        $searchArr = $request->get('search');

        $columnIndex = isset($columnIndexArr[0]['column']) ? $columnIndexArr[0]['column'] : ''; // Column index
        $columnName = !empty($columnIndex) ? $columnNameArr[$columnIndex]['data'] : 'moms.id'; // Column name
        $columnSortOrder = !empty($columnIndex) ? $orderArr[0]['dir'] : 'Asc'; // asc or desc
        $searchValue = $searchArr['value']; // Search value


        $call_status_data = DB::table('system_logs')
            ->join('users', 'users.id', '=', 'system_logs.user_id')
            ->where('call_type', '!=', null)
            ->where('action_type', '=', 'update')
            ->where(function ($query) use ($call_status_uw_report_from_date, $call_status_uw_report_to_date, $call_status_uw_report_country) {
                if ($call_status_uw_report_from_date != '' && $call_status_uw_report_to_date != '') {
                    $query->whereBetween('system_logs.created_at', [$call_status_uw_report_from_date, $call_status_uw_report_to_date]);
                }
                if ($call_status_uw_report_country != '') {
                    $query->where('users.country_id', $call_status_uw_report_country);
                }
            })
            ->select('system_logs.call_status', DB::raw('count(system_logs.call_status) as total'), 'users.name')
            ->groupBy('system_logs.call_status')
            ->groupBy('users.name')
            ->skip($start)
            ->take($rowperpage)
            ->get()->toArray();

        $totalRecords_data = DB::table('system_logs')
            ->join('users', 'users.id', '=', 'system_logs.user_id')
            ->where('call_type', '!=', null)
            ->where('action_type', '=', 'update')
            ->where(function ($query) use ($call_status_uw_report_from_date, $call_status_uw_report_to_date, $call_status_uw_report_country) {
                if ($call_status_uw_report_from_date != '' && $call_status_uw_report_to_date != '') {
                    $query->whereBetween('system_logs.created_at', [$call_status_uw_report_from_date, $call_status_uw_report_to_date]);
                }
                if ($call_status_uw_report_country != '') {
                    $query->where('users.country_id', $call_status_uw_report_country);
                }
            })
            ->select('system_logs.call_status', DB::raw('count(system_logs.call_status) as total'), 'users.name')
            ->groupBy('system_logs.call_status')
            ->groupBy('users.name')
            ->get()->toArray();

        $totalRecords = 0;
        $totalRecordswithFilter = 0;

        $report_data = [];
        $total_report_data = [];
        $calling_status_list = array();
        $calling_status_list[0] = "not_called";
        $calling_status_list[1] = "busy";
        $calling_status_list[2] = "call_later";
        $calling_status_list[3] = "called";
        $calling_status_list[4] = "do_not_call_again";
        $calling_status_list[5] = "no_requirements";
        $calling_status_list[6] = "not_reachable";
        $calling_status_list[7] = "out_of_service";
        $calling_status_list[8] = "ringing";
        $calling_status_list[9] = "swich_off";
        $calling_status_list[10] = "wrong_number";


        foreach ($call_status_data as $key => $value) {
            if ($value->name != null) {
                foreach ($calling_status_list as $key1 => $value1) {
                    if ($key1 == $value->call_status) {
                        $report_data[$value->name][$value1] = $value->total;
                    } else {
                        if (!isset($report_data[$value->name][$value1])) {
                            $report_data[$value->name][$value1] = 0;
                        }
                    }
                }
            }
        }

        foreach ($totalRecords_data as $key => $value) {
            if ($value->name != null) {
                foreach ($calling_status_list as $key1 => $value1) {
                    if ($key1 == $value->call_status) {
                        $total_report_data[$value->name][$value1] = $value->total;
                    } else {
                        if (!isset($total_report_data[$value->name][$value1])) {
                            $total_report_data[$value->name][$value1] = 0;
                        }
                    }
                }
            }
        }


        foreach ($report_data as $key => $value) {
            $report_data[$key]['total'] = array_sum($value);
        }

        foreach ($total_report_data as $key => $value) {
            $total_report_data[$key]['total'] = array_sum($value);
        }

        $final_report_data = array();
        foreach ($report_data as $key => $value) {
            $final_report_data[] = array(
                'user_name' => $key,
                'not_called' => $value['not_called'],
                'busy' => $value['busy'],
                'call_later' => $value['call_later'],
                'called' => $value['called'],
                'do_not_call_again' => $value['do_not_call_again'],
                'no_requirements' => $value['no_requirements'],
                'not_reachable' => $value['not_reachable'],
                'out_of_service' => $value['out_of_service'],
                'ringing' => $value['ringing'],
                'swich_off' => $value['swich_off'],
                'wrong_number' => $value['wrong_number'],
                'total' => $value['total'],
            );
        }

        $total_report_data_final = array();
        foreach ($total_report_data as $key => $value) {
            $total_report_data_final[] = array(
                'user_name' => $key,
                'not_called' => $value['not_called'],
                'busy' => $value['busy'],
                'call_later' => $value['call_later'],
                'called' => $value['called'],
                'do_not_call_again' => $value['do_not_call_again'],
                'no_requirements' => $value['no_requirements'],
                'not_reachable' => $value['not_reachable'],
                'out_of_service' => $value['out_of_service'],
                'ringing' => $value['ringing'],
                'swich_off' => $value['swich_off'],
                'wrong_number' => $value['wrong_number'],
                'total' => $value['total'],
            );
        }

        if (count($final_report_data) > 0) {
            $final_report_data[] = array(
                'user_name' => 'Total',
                'not_called' => array_sum(array_column($final_report_data, 'not_called')),
                'busy' => array_sum(array_column($final_report_data, 'busy')),
                'call_later' => array_sum(array_column($final_report_data, 'call_later')),
                'called' => array_sum(array_column($final_report_data, 'called')),
                'do_not_call_again' => array_sum(array_column($final_report_data, 'do_not_call_again')),
                'no_requirements' => array_sum(array_column($final_report_data, 'no_requirements')),
                'not_reachable' => array_sum(array_column($final_report_data, 'not_reachable')),
                'out_of_service' => array_sum(array_column($final_report_data, 'out_of_service')),
                'ringing' => array_sum(array_column($final_report_data, 'ringing')),
                'swich_off' => array_sum(array_column($final_report_data, 'swich_off')),
                'wrong_number' => array_sum(array_column($final_report_data, 'wrong_number')),
                'total' => array_sum(array_column($final_report_data, 'total')),
            );
        }

        if (count($total_report_data_final) > 0) {
            $total_report_data_final[] = array(
                'user_name' => 'Total',
                'not_called' => array_sum(array_column($total_report_data_final, 'not_called')),
                'busy' => array_sum(array_column($total_report_data_final, 'busy')),
                'call_later' => array_sum(array_column($total_report_data_final, 'call_later')),
                'called' => array_sum(array_column($total_report_data_final, 'called')),
                'do_not_call_again' => array_sum(array_column($total_report_data_final, 'do_not_call_again')),
                'no_requirements' => array_sum(array_column($total_report_data_final, 'no_requirements')),
                'not_reachable' => array_sum(array_column($total_report_data_final, 'not_reachable')),
                'out_of_service' => array_sum(array_column($total_report_data_final, 'out_of_service')),
                'ringing' => array_sum(array_column($total_report_data_final, 'ringing')),
                'swich_off' => array_sum(array_column($total_report_data_final, 'swich_off')),
                'wrong_number' => array_sum(array_column($total_report_data_final, 'wrong_number')),
                'total' => array_sum(array_column($total_report_data_final, 'total')),
            );
        }
        $records = $final_report_data;
        $totalRecords = count($total_report_data_final);
        $totalRecordswithFilter = count($total_report_data_final);
        $user = Auth::user();
        $user_permissions = $user->getAllPermissions()->pluck('name')->toArray();
        $user_edit_permissions_check = 'user_edit';
        $user_delete_permissions_check = 'user_delete';
        $user_edit_permissions = in_array($user_edit_permissions_check, $user_permissions);
        $user_delete_permissions = in_array($user_delete_permissions_check, $user_permissions);

        $dataArr = array();
        $i = $start + 1;
        foreach ($records as $record) {

            if ($record['user_name'] == 'Total') {
                $user_name = '<b>' . $record['user_name'] . '</b>';
            } else {
                $user_name = $record['user_name'];
            }

            $dataArr[] = array(
                'DT_RowIndex' => $i++,
                'user_name' => $user_name,
                'busy' => $record['busy'],
                'call_later' => $record['call_later'],
                'called' => $record['called'],
                'do_not_call_again' => $record['do_not_call_again'],
                'no_requirements' => $record['no_requirements'],
                'not_reachable' => $record['not_reachable'],
                'out_of_service' => $record['out_of_service'],
                'ringing' => $record['ringing'],
                'swich_off' => $record['swich_off'],
                'wrong_number' => $record['wrong_number'],
                'total' => $record['total'],
            );
        }


        // $logData = array(
        //     'user_id' => auth()->user()->id,
        //     'action_id' => $user->id,
        //     'action_type' => 'view',
        //     'module' => 'user',
        //     'description' => 'User list view',
        // );

        // $storeLog = SystemLogController::addLog($logData);
        return response()->json([
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecordswithFilter,
            "data" => $dataArr,
        ]);
    }

    public function client_report()
    {
        $selected_menu = 'client_report';
        $user = Auth::user();
        $user_id = $user->id;
        $user_country = $user->country_id;
        $MOMController = new MOMController;
        $userIds = $MOMController->getHirarchyUser($user->id);
        $userIds[] = $user->id;
        $userIds = array_unique($userIds);

        $user_list = User::whereIn('id', $userIds)->where('is_deleted', null)->get()->pluck('name', 'id')->toArray();

        $all_countries = Country::all()->toArray();

        return view('backend.reports.client_report', compact('selected_menu', 'all_countries', 'user_list'));
    }

    public function client_status_report_export(Request $request)
    {
        //dd($request->all());
        $client_status_report_country_id = $request->client_status_report_country;
        $client_status_report_city_id = $request->client_status_report_city;
        $client_status_report_user_id = $request->client_status_report_user;
        $client_status_report_activity_status = $request->client_status_report_activity;

        // if ($client_status_report_country_id != '' || $client_status_report_city_id != '' || $client_status_report_user_id != '' || $client_status_report_activity_status != '') {
        // } else {
        //     return back()->with('error', 'Please select any one of the filter');
        // }

        $data_from_date = date('Y-m-d', strtotime('-30 days'));
        $data_to_date = date('Y-m-d');

        $user = Auth::user();
        $MOMController = new MOMController;
        $userIds = $MOMController->getHirarchyUser($user->id);
        $userIds[] = $user->id;
        $userIds = array_unique($userIds);

        if ($client_status_report_activity_status != '') {
            if ($client_status_report_activity_status == '30p') {
            }
            if ($client_status_report_activity_status == '30n') {
            }
        } else {
        }

        $client_status_data = DB::table('clients')
            ->leftJoin('countries', 'countries.id', '=', 'clients.country_id')
            ->leftJoin('cities', 'cities.id', '=', 'clients.city_id')
            ->leftJoin('industries', 'industries.id', '=', 'clients.industry_id')
            ->leftJoin('moms', 'moms.client_id', '=', 'clients.id')
            ->leftJoin('users', 'users.id', '=', 'clients.manage_by')
            ->where('clients.is_deleted', '=', null)
            ->where(function ($query) use ($client_status_report_country_id, $client_status_report_city_id, $client_status_report_user_id, $data_from_date, $data_to_date) {
                if ($client_status_report_country_id != '') {
                    $query->where('clients.country_id', '=', $client_status_report_country_id);
                }
                if ($client_status_report_city_id != '') {
                    $query->where('clients.city_id', '=', $client_status_report_city_id);
                }
                if ($client_status_report_user_id != '') {
                    $query->where('clients.manage_by', '=', $client_status_report_user_id);
                }
            })
            ->whereDate('moms.updated_at', '>=', $data_from_date)
            ->whereDate('moms.updated_at', '<=', $data_to_date)
            ->whereIn('clients.manage_by', $userIds)
            ->select('clients.*', 'countries.country_name', 'cities.city_name', 'industries.industry_name', 'moms.updated_at as last_mom_date', 'users.name as manage_by_name')
            ->get()->toArray();

        $records = $client_status_data;

        //remove export_report_data session
        if (Session::has('export_report_data')) {
            Session::forget('export_report_data');
        }

        $dataArr = array();
        $dataArr[]=array('Country','Company Name','Address','City','Phone No','Email','Industry','BDE Name','Last Mom Date');
        foreach ($records as $record) {
            $dataArr[] = array(
                (string)$record->country_name,
                (string)$record->company_name,
                (string)$record->address,
                (string)$record->city_name,
                (string)$record->phone_no,
                (string)$record->email,
                (string)$record->industry_name,
                (string)$record->manage_by_name,
                (string)date('d/m/Y', strtotime($record->last_mom_date)),
            );
        }
       
        if (count((array)$dataArr) > 0) {
            $request->session()->put('export_report_data', $dataArr);
            $fileName = 'Client_Status_Report_' . date('Ymd') . '.xlsx';
            return Excel::download(new ExportReports, $fileName);
        } else {
            return back()->with('error', 'No record available for export');
        }
    }

    public function client_status_report_country_change(Request $request)
    {
        $country_id = $request->country_id;

        $city_list = City::where('country_id', $country_id)->get()->toArray();
        if ($country_id == '') {
            $city_list = array();
        } else {
            $city_list = City::where('country_id', $country_id)->get()->toArray();
            $city_data = array_map("unserialize", array_unique(array_map("serialize", $city_list)));
        }
        $data = $city_data;
        $view = view('backend.reports.get_city_by_country', compact('data'))->render();
        return response()->json(['html' => $view]);
    }

    public function client_status_report_table_view(Request $request)
    {
        $data = $request->all();
        $view = view('backend.reports.client_status_report_table', compact('data'))->render();
        return response()->json(['html' => $view]);
    }

    public function get_client_status_report_data(Request $request)
    {

        $client_status_report_country_id = $request->client_status_report_country_id;
        $client_status_report_city_id = $request->client_status_report_city_id;
        $client_status_report_user_id = $request->client_status_report_user_id;
        $client_status_report_activity_status = $request->client_status_report_activity_status;

        $data_from_date = date('Y-m-d H:i:s.000', strtotime('-30 days'));
        $data_to_date = date('Y-m-d H:i:s.000');

        if ($client_status_report_activity_status != '') {
            if ($client_status_report_activity_status == '30p') {
            }
            if ($client_status_report_activity_status == '30n') {
            }
        } else {
        }

        $user = Auth::user();
        $MOMController = new MOMController;
        $userIds = $MOMController->getHirarchyUser($user->id);
        $userIds[] = $user->id;
        $userIds = array_unique($userIds);

        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndexArr = $request->get('order');
        $columnNameArr = $request->get('columns');
        $orderArr = $request->get('order');
        $searchArr = $request->get('search');

        $columnIndex = isset($columnIndexArr[0]['column']) ? $columnIndexArr[0]['column'] : ''; // Column index
        $columnName = !empty($columnIndex) ? $columnNameArr[$columnIndex]['data'] : 'moms.id'; // Column name
        $columnSortOrder = !empty($columnIndex) ? $orderArr[0]['dir'] : 'Asc'; // asc or desc
        $searchValue = $searchArr['value']; // Search value


        $client_status_data = DB::table('clients')
            ->leftJoin('countries', 'countries.id', '=', 'clients.country_id')
            ->leftJoin('cities', 'cities.id', '=', 'clients.city_id')
            ->leftJoin('industries', 'industries.id', '=', 'clients.industry_id')
            ->leftJoin('moms', 'moms.client_id', '=', 'clients.id')
            ->leftJoin('users', 'users.id', '=', 'clients.manage_by')
            ->where('clients.is_deleted', '=', null)
            ->where(function ($query) use ($client_status_report_country_id, $client_status_report_city_id, $client_status_report_user_id, $data_from_date, $data_to_date, $client_status_report_activity_status) {
                if ($client_status_report_country_id != '') {
                    $query->where('clients.country_id', '=', $client_status_report_country_id);
                }
                if ($client_status_report_city_id != '') {
                    $query->where('clients.city_id', '=', $client_status_report_city_id);
                }
                if ($client_status_report_user_id != '') {
                    $query->where('clients.manage_by', '=', $client_status_report_user_id);
                }
                if ($client_status_report_activity_status != '' && $client_status_report_activity_status == '30p') {
                    $query->whereBetween('moms.updated_at', [$data_from_date, $data_to_date]);
                }
                if ($client_status_report_activity_status != '' && $client_status_report_activity_status == '30n') {
                    $query->whereNotBetween('moms.updated_at', [$data_from_date, $data_to_date]);
                }
                if ($client_status_report_activity_status == '') {
                }
            })
            ->whereDate('moms.updated_at', '>=', $data_from_date)
            ->whereDate('moms.updated_at', '<=', $data_to_date)
            ->whereIn('clients.manage_by', $userIds)
            ->select('clients.*', 'countries.country_name', 'cities.city_name', 'industries.industry_name', 'moms.updated_at as last_mom_date', 'users.name as manage_by_name')
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get()->toArray();

        $totalRecords = DB::table('clients')
            ->leftJoin('countries', 'countries.id', '=', 'clients.country_id')
            ->leftJoin('cities', 'cities.id', '=', 'clients.city_id')
            ->leftJoin('industries', 'industries.id', '=', 'clients.industry_id')
            ->leftJoin('moms', 'moms.client_id', '=', 'clients.id')
            ->leftJoin('users', 'users.id', '=', 'clients.manage_by')
            ->where('clients.is_deleted', '=', null)
            ->where(function ($query) use ($client_status_report_country_id, $client_status_report_city_id, $client_status_report_user_id, $data_from_date, $data_to_date, $client_status_report_activity_status) {
                if ($client_status_report_country_id != '') {
                    $query->where('clients.country_id', '=', $client_status_report_country_id);
                }
                if ($client_status_report_city_id != '') {
                    $query->where('clients.city_id', '=', $client_status_report_city_id);
                }
                if ($client_status_report_user_id != '') {
                    $query->where('clients.manage_by', '=', $client_status_report_user_id);
                }
                if ($client_status_report_activity_status != '' && $client_status_report_activity_status == '30p') {
                    $query->whereBetween('moms.updated_at', [$data_from_date, $data_to_date]);
                }
                if ($client_status_report_activity_status != '' && $client_status_report_activity_status == '30n') {
                    $query->whereNotBetween('moms.updated_at', [$data_from_date, $data_to_date]);
                }
                if ($client_status_report_activity_status == '') {
                }
            })
            ->whereDate('moms.updated_at', '>=', $data_from_date)
            ->whereDate('moms.updated_at', '<=', $data_to_date)
            ->whereIn('clients.manage_by', $userIds)
            ->select('clients.*', 'countries.country_name', 'cities.city_name', 'industries.industry_name', 'moms.updated_at as last_mom_date', 'users.name as manage_by_name')
            ->count();

        $totalRecordswithFilter = DB::table('clients')
            ->leftJoin('countries', 'countries.id', '=', 'clients.country_id')
            ->leftJoin('cities', 'cities.id', '=', 'clients.city_id')
            ->leftJoin('industries', 'industries.id', '=', 'clients.industry_id')
            ->leftJoin('moms', 'moms.client_id', '=', 'clients.id')
            ->leftJoin('users', 'users.id', '=', 'clients.manage_by')
            ->where('clients.is_deleted', '=', null)
            ->where(function ($query) use ($client_status_report_country_id, $client_status_report_city_id, $client_status_report_user_id, $data_from_date, $data_to_date, $client_status_report_activity_status) {
                if ($client_status_report_country_id != '') {
                    $query->where('clients.country_id', '=', $client_status_report_country_id);
                }
                if ($client_status_report_city_id != '') {
                    $query->where('clients.city_id', '=', $client_status_report_city_id);
                }
                if ($client_status_report_user_id != '') {
                    $query->where('clients.manage_by', '=', $client_status_report_user_id);
                }
                if ($client_status_report_activity_status != '' && $client_status_report_activity_status == '30p') {
                    $query->whereBetween('moms.updated_at', [$data_from_date, $data_to_date]);
                }
                if ($client_status_report_activity_status != '' && $client_status_report_activity_status == '30n') {
                    $query->whereNotBetween('moms.updated_at', [$data_from_date, $data_to_date]);
                }
                if ($client_status_report_activity_status == '') {
                }
            })
            ->whereDate('moms.updated_at', '>=', $data_from_date)
            ->whereDate('moms.updated_at', '<=', $data_to_date)
            ->whereIn('clients.manage_by', $userIds)
            ->select('clients.*', 'countries.country_name', 'cities.city_name', 'industries.industry_name', 'moms.updated_at as last_mom_date', 'users.name as manage_by_name')
            ->count();


        $records = $client_status_data;
        $app_url = env('APP_URL');

        $dataArr = array();
        $i = $start + 1;
        foreach ($records as $record) {
            $dataArr[] = array(
                'DT_RowIndex' => $i++,
                'country_name' => $record->country_name,
                'company_name' => $record->company_name,
                'contact_person' => "<a href='" . $app_url . "get_company_contact_person/" . $record->id . "' target='_blank' value=" . $record->id . " class='btn btn-primary btn-sm'
                id='client_status_report_contact_person_btn'>
                <i class='bx bx-group'></i>
                View
                </a>",
                'address' => $record->address,
                'city_name' => $record->city_name,
                'phone_no' => $record->phone_no,
                'email' => $record->email,
                'industry_name' => $record->industry_name,
                'manage_by' => $record->manage_by_name,
                'last_mom_date' => date('d/m/Y', strtotime($record->last_mom_date)),
            );
        }
        // $logData = array(
        //     'user_id' => auth()->user()->id,
        //     'action_id' => $user->id,
        //     'action_type' => 'view',
        //     'module' => 'user',
        //     'description' => 'User list view',
        // );
        // $storeLog = SystemLogController::addLog($logData);
        return response()->json([
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecordswithFilter,
            "data" => $dataArr,
        ]);
    }

    public function get_company_contact_person($id)
    {
        $client_id = $id;

        $client_data = DB::table('clients')
            ->leftJoin('countries', 'countries.id', '=', 'clients.country_id')
            ->leftJoin('cities', 'cities.id', '=', 'clients.city_id')
            ->leftJoin('industries', 'industries.id', '=', 'clients.industry_id')
            ->leftJoin('moms', 'moms.client_id', '=', 'clients.id')
            ->leftJoin('users', 'users.id', '=', 'clients.manage_by')
            ->where('clients.is_deleted', '=', null)
            ->where('clients.id', '=', $client_id)
            ->select('clients.*', 'countries.country_name', 'cities.city_name', 'industries.industry_name', 'moms.updated_at as last_mom_date', 'users.name as manage_by_name')
            ->first();

        if ($client_data->company_name != '') {
            $contact_person_list_data = DB::table('contact_person')
                ->where('client_id', '=', $client_id)
                ->where('is_deleted', '=', null)
                ->get()->toArray();

            return view('backend.reports.contact_person_list', compact('contact_person_list_data', 'client_data'));
        } else {
            return redirect()->back()->with('error', 'Something went wrong!');
        }


        // echo "<pre>";
        // print_r($contact_person_list_data);
        // echo "</pre>";
    }

    public function call_status_report_export_check(Request $request)
    {
        $call_status_report_from_date = $request->from_date;
        $call_status_report_to_date = $request->to_date;
        $call_status_report_country = $request->country_id;
        $call_status_report_user = $request->user_id;

        if ($call_status_report_from_date != '' || $call_status_report_to_date != '' || $call_status_report_country != '' || $call_status_report_user != '') {
            if ($call_status_report_from_date != '' && $call_status_report_to_date != '') {
                $call_status_report_from_date = date('Y-m-d', strtotime($call_status_report_from_date));
                $call_status_report_to_date = date('Y-m-d', strtotime($call_status_report_to_date));
                $call_status_report_to_date = date('Y-m-d', strtotime($call_status_report_to_date . ' +1 day'));
            }
            if ($call_status_report_country != '') {
                $call_status_report_country = $call_status_report_country;
            } else {
                $call_status_report_country = '';
            }
            if ($call_status_report_user != '') {
                $call_status_report_user = $call_status_report_user;
            } else {
                $call_status_report_user = '';
            }

            $call_status_data = DB::table('system_logs')
                ->join('users', 'users.id', '=', 'system_logs.user_id')
                ->where('call_type', '!=', null)
                ->where('action_type', '=', 'update')
                ->where(function ($query) use ($call_status_report_from_date, $call_status_report_to_date, $call_status_report_country, $call_status_report_user) {
                    if ($call_status_report_from_date != '' && $call_status_report_to_date != '') {
                        $query->whereBetween('system_logs.created_at', [$call_status_report_from_date, $call_status_report_to_date]);
                    }
                    if ($call_status_report_country != '') {
                        $query->where('users.country_id', $call_status_report_country);
                    }
                    if ($call_status_report_user != '') {
                        $query->where('system_logs.user_id', $call_status_report_user);
                    }
                })
                ->select('system_logs.call_status', DB::raw('count(*) as total'), DB::raw("FORMAT(system_logs.updated_at, 'yyyy-MM-d') as call_date"))
                ->groupBy('system_logs.call_status')
                ->groupBy(DB::raw("FORMAT(system_logs.updated_at, 'yyyy-MM-d')"))
                ->get()->toArray();


            $is_requirement_data = DB::table('leads')
                ->join('users', 'users.id', '=', 'leads.created_by')
                ->where('is_requirement', '=', 1)
                ->where(function ($query) use ($call_status_report_from_date, $call_status_report_to_date, $call_status_report_country, $call_status_report_user) {
                    if ($call_status_report_from_date != '' && $call_status_report_to_date != '') {
                        $query->whereBetween('leads.created_at', [$call_status_report_from_date, $call_status_report_to_date]);
                    }
                    if ($call_status_report_country != '') {
                        $query->where('users.country_id', $call_status_report_country);
                    }
                    if ($call_status_report_user != '') {
                        $query->where('leads.created_by', $call_status_report_user);
                    }
                })
                ->select(DB::raw('count(*) as total_leads'), DB::raw("FORMAT(leads.created_at, 'yyyy-MM-d') as is_requirement_call_date"))
                ->groupBy(DB::raw("FORMAT(leads.created_at, 'yyyy-MM-d')"))
                ->get()->toArray();

            $report_data = [];

            $calling_status_list = array();
            $calling_status_list[0] = "not_called";
            $calling_status_list[1] = "busy";
            $calling_status_list[2] = "call_later";
            $calling_status_list[3] = "called";
            $calling_status_list[4] = "do_not_call_again";
            $calling_status_list[5] = "no_requirements";
            $calling_status_list[6] = "not_reachable";
            $calling_status_list[7] = "out_of_service";
            $calling_status_list[8] = "ringing";
            $calling_status_list[9] = "swich_off";
            $calling_status_list[10] = "wrong_number";


            foreach ($call_status_data as $key => $value) {
                if ($value->call_date != null) {
                    foreach ($calling_status_list as $key1 => $value1) {
                        if ($key1 == $value->call_status) {
                            $report_data[$value->call_date][$value1] = $value->total;
                        } else {
                            if (!isset($report_data[$value->call_date][$value1])) {
                                $report_data[$value->call_date][$value1] = 0;
                            }
                        }
                    }
                }
            }

            foreach ($report_data as $key => $value) {
                $report_data[$key]['total'] = array_sum($value);
            }
            foreach ($is_requirement_data as $key => $value) {
                if ($value->is_requirement_call_date != null) {
                    $report_data[$value->is_requirement_call_date]['is_requirement'] = $value->total_leads;
                } else {
                    $report_data[$value->is_requirement_call_date]['is_requirement'] = 0;
                }
            }

            $final_report_data = array();
            foreach ($report_data as $key => $value) {
                $final_report_data[] = array(
                    'last_call_date' => $key,
                    'not_called' => $value['not_called'] ?? 0,
                    'busy' => $value['busy'] ?? 0,
                    'call_later' => $value['call_later'] ?? 0,
                    'called' => $value['called'] ?? 0,
                    'is_requirement' => (isset($value['is_requirement']) ? $value['is_requirement'] : 0),
                    'do_not_call_again' => $value['do_not_call_again'] ?? 0,
                    'no_requirements' => $value['no_requirements'] ?? 0,
                    'not_reachable' => $value['not_reachable'] ?? 0,
                    'out_of_service' => $value['out_of_service'] ?? 0,
                    'ringing' => $value['ringing'] ?? 0,
                    'swich_off' => $value['swich_off'] ?? 0,
                    'wrong_number' => $value['wrong_number'] ?? 0,
                    'total' => $value['total'] ?? 0,
                );
            }

            if (count($final_report_data) > 0) {
                $final_report_data[] = array(
                    'last_call_date' => 'Total',
                    'not_called' => array_sum(array_column($final_report_data, 'not_called')),
                    'busy' => array_sum(array_column($final_report_data, 'busy')),
                    'call_later' => array_sum(array_column($final_report_data, 'call_later')),
                    'called' => array_sum(array_column($final_report_data, 'called')),
                    'is_requirement' => array_sum(array_column($final_report_data, 'is_requirement')),
                    'do_not_call_again' => array_sum(array_column($final_report_data, 'do_not_call_again')),
                    'no_requirements' => array_sum(array_column($final_report_data, 'no_requirements')),
                    'not_reachable' => array_sum(array_column($final_report_data, 'not_reachable')),
                    'out_of_service' => array_sum(array_column($final_report_data, 'out_of_service')),
                    'ringing' => array_sum(array_column($final_report_data, 'ringing')),
                    'swich_off' => array_sum(array_column($final_report_data, 'swich_off')),
                    'wrong_number' => array_sum(array_column($final_report_data, 'wrong_number')),
                    'total' => array_sum(array_column($final_report_data, 'total')),
                );
            }

            $records = $final_report_data;

            $dataArr = array();
            foreach ($records as $record) {

                if ($record['last_call_date'] == 'Total') {
                    $last_call_date = $record['last_call_date'];
                } else {
                    $last_call_date = date('d/m/Y', strtotime($record['last_call_date']));
                }

                $dataArr[] = array(
                    'Date' => $last_call_date,
                    'Busy' => (int)$record['busy'],
                    'Call Later' => (int)$record['call_later'],
                    'Called' => (int)$record['called'],
                    'Is Requirement' => (int)$record['is_requirement'],
                    'Do Not Call Again' => (int)$record['do_not_call_again'],
                    'No Requirements' => (int)$record['no_requirements'],
                    'Not Reachable' => (int)$record['not_reachable'],
                    'Out Of Service' => (int)$record['out_of_service'],
                    'Ringing' => (int)$record['ringing'],
                    'Swich Off' => (int)$record['swich_off'],
                    'Wrong Number' => (int)$record['wrong_number'],
                    'Total' => $record['total'],
                );
            }

            if (count((array)$dataArr) > 0) {
                return response()->json(['status' => true, 'message' => 'Export file is ready to download']);
            } else {
                return response()->json(['status' => false, 'message' => 'No record available for export']);
            }
        } else {
            return response()->json(['status' => false, 'message' => 'Filter date is required']);
        }
    }

    public function mom_report_export_check(Request $request)
    {
        $user = Auth::user();
        $MOMController = new MOMController;
        $userIds = $MOMController->getHirarchyUser($user->id);
        $userIds[] = $user->id;

        $mom_report_from_date = $request->meetingFromDate;
        $mom_report_to_date = $request->meetingToDate;
        $mom_report_company_name = $request->companyName;
        $mom_report_country = $request->country;
        $mom_report_user = $request->user;

        if ($mom_report_from_date != '' && $mom_report_to_date != '') {
            $mom_report_from_date = date('Y-m-d', strtotime($mom_report_from_date));
            $mom_report_to_date = date('Y-m-d', strtotime($mom_report_to_date));
        }
        if ($mom_report_company_name != '') {
            $mom_report_company_id = $mom_report_company_name;
            $mom_report_company_name = Client::where('id', $mom_report_company_id)->first()->company_name;
        } else {
            $mom_report_company_name = '';
        }
        if ($mom_report_country != '') {
            $mom_report_country = $mom_report_country;
        } else {
            $mom_report_country = '';
        }
        if ($mom_report_user != '') {
            $mom_report_user = $mom_report_user;
        } else {
            $mom_report_user = '';
        }
        $users_data = DB::table('moms')
            ->leftJoin('clients', 'clients.id', '=', 'moms.client_id')
            ->leftJoin('countries', 'countries.id', '=', 'clients.country_id')
            ->leftJoin('cities', 'cities.id', '=', 'clients.city_id')
            ->leftJoin('users as manage_user', 'manage_user.id', '=', 'clients.manage_by')
            ->leftJoin('users as added_by', 'added_by.id', '=', 'moms.created_by')
            ->select('moms.*', 'moms.id as meeting_id', 'manage_user.name as manage_by_username', 'added_by.name as added_by_username', 'clients.*', 'countries.country_name as country_name', 'cities.city_name as city_name')
            ->where('moms.is_deleted', null)
            ->where(function ($query) use ($mom_report_from_date, $mom_report_to_date) {
                if ($mom_report_from_date != '' && $mom_report_to_date != '') {
                    $query->whereBetween('meeting_date', [$mom_report_from_date, $mom_report_to_date]);
                }
            })->where(function ($query) use ($mom_report_company_name) {
                if ($mom_report_company_name != '') {
                    $query->where('company_name', $mom_report_company_name);
                }
            })->where(function ($query) use ($mom_report_country) {
                if ($mom_report_country != '') {
                    $query->where('clients.country_id', $mom_report_country);
                }
            })->where(function ($query) use ($mom_report_user) {
                if ($mom_report_user != '') {
                    $query->where('moms.created_by', $mom_report_user);
                }
            })
            ->whereIn('moms.created_by', $userIds)
            ->get()->toArray();

        $records = $users_data;

        $dataArr = array();
        $i = +1;
        foreach ($records as $record) {
            $dataArr[] = array(
                'No.' => $i++,
                'Meeting Date' => date('d/m/Y', strtotime($record->meeting_date)),
                'Country Name' => $record->country_name,
                'Assigned To' => $record->manage_by_username,
                'Company Name' => $record->company_name,
                'Contact Person' => $record->contact_person,
                'Address' => $record->address,
                'City Name' => $record->city_name,
                'Phone No.' => $record->phone_no,
                'Email' => $record->email,
                'Minutes Of Meeting' => $record->minutes_of_meeting,
                'Bde Feedback' => $record->bde_feedback,
                'Next Followup Date' => date('d/m/Y', strtotime($record->next_followup_date)),
                'Added By' => $record->added_by_username,
            );
        }

        if (count((array)$dataArr) > 0) {
            return response()->json(['status' => true, 'message' => 'Export file is ready to download']);
        } else {
            return response()->json(['status' => false, 'message' => 'No record available for export']);
        }
    }

    public function call_status_uw_report_export_check(Request $request)
    {
        $call_status_uw_report_from_date = $request->from_date;
        $call_status_uw_report_to_date = $request->to_date;
        $call_status_uw_report_country = $request->country_id;

        if ($call_status_uw_report_from_date != '' || $call_status_uw_report_to_date != '' || $call_status_uw_report_country != '') {
            if ($call_status_uw_report_from_date != '' && $call_status_uw_report_to_date != '') {
                $call_status_uw_report_from_date = date('Y-m-d', strtotime($call_status_uw_report_from_date));
                $call_status_uw_report_to_date = date('Y-m-d', strtotime($call_status_uw_report_to_date));
                $call_status_uw_report_to_date = date('Y-m-d', strtotime($call_status_uw_report_to_date . ' +1 day'));
            }
            if ($call_status_uw_report_country != '') {
                $call_status_uw_report_country = $call_status_uw_report_country;
            } else {
                $call_status_uw_report_country = '';
            }

            $call_status_data = DB::table('system_logs')
                ->join('users', 'users.id', '=', 'system_logs.user_id')
                ->where('call_type', '!=', null)
                ->where('action_type', '=', 'update')
                ->where(function ($query) use ($call_status_uw_report_from_date, $call_status_uw_report_to_date, $call_status_uw_report_country) {
                    if ($call_status_uw_report_from_date != '' && $call_status_uw_report_to_date != '') {
                        $query->whereBetween('system_logs.created_at', [$call_status_uw_report_from_date, $call_status_uw_report_to_date]);
                    }
                    if ($call_status_uw_report_country != '') {
                        $query->where('users.country_id', $call_status_uw_report_country);
                    }
                })
                ->select('system_logs.call_status', DB::raw('count(system_logs.call_status) as total'), 'users.name')
                ->groupBy('system_logs.call_status')
                ->groupBy('users.name')
                ->get()->toArray();

            $report_data = [];

            $calling_status_list = array();
            $calling_status_list[0] = "not_called";
            $calling_status_list[1] = "busy";
            $calling_status_list[2] = "call_later";
            $calling_status_list[3] = "called";
            $calling_status_list[4] = "do_not_call_again";
            $calling_status_list[5] = "no_requirements";
            $calling_status_list[6] = "not_reachable";
            $calling_status_list[7] = "out_of_service";
            $calling_status_list[8] = "ringing";
            $calling_status_list[9] = "swich_off";
            $calling_status_list[10] = "wrong_number";


            foreach ($call_status_data as $key => $value) {
                if ($value->name != null) {
                    foreach ($calling_status_list as $key1 => $value1) {
                        if ($key1 == $value->call_status) {
                            $report_data[$value->name][$value1] = $value->total;
                        } else {
                            if (!isset($report_data[$value->name][$value1])) {
                                $report_data[$value->name][$value1] = 0;
                            }
                        }
                    }
                }
            }

            foreach ($report_data as $key => $value) {
                $report_data[$key]['total'] = array_sum($value);
            }

            $final_report_data = array();
            foreach ($report_data as $key => $value) {
                $final_report_data[] = array(
                    'user_name' => $key,
                    'not_called' => $value['not_called'],
                    'busy' => $value['busy'],
                    'call_later' => $value['call_later'],
                    'called' => $value['called'],
                    'do_not_call_again' => $value['do_not_call_again'],
                    'no_requirements' => $value['no_requirements'],
                    'not_reachable' => $value['not_reachable'],
                    'out_of_service' => $value['out_of_service'],
                    'ringing' => $value['ringing'],
                    'swich_off' => $value['swich_off'],
                    'wrong_number' => $value['wrong_number'],
                    'total' => $value['total'],
                );
            }


            if (count($final_report_data) > 0) {
                $final_report_data[] = array(
                    'user_name' => 'Total',
                    'not_called' => array_sum(array_column($final_report_data, 'not_called')),
                    'busy' => array_sum(array_column($final_report_data, 'busy')),
                    'call_later' => array_sum(array_column($final_report_data, 'call_later')),
                    'called' => array_sum(array_column($final_report_data, 'called')),
                    'do_not_call_again' => array_sum(array_column($final_report_data, 'do_not_call_again')),
                    'no_requirements' => array_sum(array_column($final_report_data, 'no_requirements')),
                    'not_reachable' => array_sum(array_column($final_report_data, 'not_reachable')),
                    'out_of_service' => array_sum(array_column($final_report_data, 'out_of_service')),
                    'ringing' => array_sum(array_column($final_report_data, 'ringing')),
                    'swich_off' => array_sum(array_column($final_report_data, 'swich_off')),
                    'wrong_number' => array_sum(array_column($final_report_data, 'wrong_number')),
                    'total' => array_sum(array_column($final_report_data, 'total')),
                );
            }


            $records = $final_report_data;

            $dataArr = array();
            foreach ($records as $record) {
                if ($record['user_name'] == 'Total') {
                    $user_name = $record['user_name'];
                } else {
                    $user_name = $record['user_name'];
                }
                $dataArr[] = array(
                    'User Name' => $user_name,
                    'Busy' => (int)$record['busy'],
                    'Call Later' => (int)$record['call_later'],
                    'Called' => (int)$record['called'],
                    'Do Not Call Again' => (int)$record['do_not_call_again'],
                    'No Requirements' => (int)$record['no_requirements'],
                    'Not Reachable' => (int)$record['not_reachable'],
                    'Out Of Service' => (int)$record['out_of_service'],
                    'Ringing' => (int)$record['ringing'],
                    'Swich Off' => (int)$record['swich_off'],
                    'Wrong Number' => (int)$record['wrong_number'],
                    'Total' => $record['total'],
                );
            }

            if (count((array)$dataArr) > 0) {
                return response()->json(['status' => true, 'message' => 'Export file is ready to download']);
            } else {
                return response()->json(['status' => false, 'message' => 'No record available for export']);
            }
        } else {
            return response()->json(['status' => false, 'message' => 'Please select any filter to export']);
        }
    }

    public function client_status_report_export_check(Request $request)
    {
        //dd($request->all());
        $client_status_report_country_id = $request->country_id;
        $client_status_report_city_id = $request->city_id;
        $client_status_report_user_id = $request->user_id;
        $client_status_report_activity_status = $request->activity_status;

        // if ($client_status_report_country_id != '' || $client_status_report_city_id != '' || $client_status_report_user_id != '' || $client_status_report_activity_status != '') {
        // } else {
        //     return back()->with('error', 'Please select any one of the filter');
        // }

        $data_from_date = date('Y-m-d', strtotime('-30 days'));
        $data_to_date = date('Y-m-d');

        $user = Auth::user();
        $MOMController = new MOMController;
        $userIds = $MOMController->getHirarchyUser($user->id);
        $userIds[] = $user->id;
        $userIds = array_unique($userIds);

        if ($client_status_report_activity_status != '') {
            if ($client_status_report_activity_status == '30p') {
            }
            if ($client_status_report_activity_status == '30n') {
            }
        } else {
        }

        $client_status_data = DB::table('clients')
            ->leftJoin('countries', 'countries.id', '=', 'clients.country_id')
            ->leftJoin('cities', 'cities.id', '=', 'clients.city_id')
            ->leftJoin('industries', 'industries.id', '=', 'clients.industry_id')
            ->leftJoin('moms', 'moms.client_id', '=', 'clients.id')
            ->leftJoin('users', 'users.id', '=', 'clients.manage_by')
            ->where('clients.is_deleted', '=', null)
            ->where(function ($query) use ($client_status_report_country_id, $client_status_report_city_id, $client_status_report_user_id, $data_from_date, $data_to_date) {
                if ($client_status_report_country_id != '') {
                    $query->where('clients.country_id', '=', $client_status_report_country_id);
                }
                if ($client_status_report_city_id != '') {
                    $query->where('clients.city_id', '=', $client_status_report_city_id);
                }
                if ($client_status_report_user_id != '') {
                    $query->where('clients.manage_by', '=', $client_status_report_user_id);
                }
            })
            ->whereDate('moms.updated_at', '>=', $data_from_date)
            ->whereDate('moms.updated_at', '<=', $data_to_date)
            ->whereIn('clients.manage_by', $userIds)
            ->select('clients.*', 'countries.country_name', 'cities.city_name', 'industries.industry_name', 'moms.updated_at as last_mom_date', 'users.name as manage_by_name')
            ->get()->toArray();

        $records = $client_status_data;
        $dataArr = array();
        foreach ($records as $record) {
            $dataArr[] = array(
                'Country Name' => $record->country_name,
                'Company Name' => $record->company_name,
                'Address' => $record->address,
                'City Name' => $record->city_name,
                'Phone No' => $record->phone_no,
                'Email' => $record->email,
                'Industry Name' => $record->industry_name,
                'Manage By' => $record->manage_by_name,
                'Last Mom Date' => date('d/m/Y', strtotime($record->last_mom_date)),
            );
        }
        if (count((array)$dataArr) > 0) {
            return response()->json(['status' => true, 'message' => 'Export file is ready to download']);
        } else {
            return response()->json(['status' => false, 'message' => 'No record available for export']);
        }
    }
}
