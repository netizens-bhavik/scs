<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\SystemLogController;

class UserController extends Controller
{

    public function manage_users(Request $request)
    {
        $user_id = $request->user_id;

        if ($user_id == null) {
            $country = Country::all()->where('is_deleted', null)->toArray();
            $view = view('backend.masters.modals.user_master_form', compact('country'))->render();
            return response()->json(['html' => $view]);
        } else {
            $user = User::find($user_id)->toArray();
            $user_roles = User::find($user_id)->getRoleNames()->toArray();
            $user_permissions = User::find($user_id)->getPermissionNames()->toArray();
            $country = Country::all()->where('is_deleted', null)->toArray();
            $user_data = array();
            $user_data['id'] = $user['id'];
            $user_data['name'] = $user['name'];
            $user_data['email'] = $user['email'];
            $user_data['country_id'] = $user['country_id'];
            if ($user['reporting_user_id'] != null) {
                $user_data['reporting_user_id'] = $user['reporting_user_id'];
                $user_data['reporting_user_name'] = User::find($user['reporting_user_id'])->name;
            } else {
                $user_data['reporting_user_id'] = null;
                $user_data['reporting_user_name'] = null;
            }
            $user_data['city_id'] = $user['city_id'];
            $user_data['role'] = $user_roles[0];
            $user_data['permissions'] = $user_permissions;
            $view = view('backend.masters.modals.user_master_form', compact('user_data', 'country'))->render();
            return response()->json(['html' => $view]);
        }
    }



    public function add_user(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'actual_name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'user_position' => 'required',
            'user_location' => 'required',
        ]);
        if ($validator->fails()) {
            $email_check = User::where('email', $request->email)->first();
            if ($email_check) {
                return response()->json([
                    'status' => false,
                    'message' => "Email already exists",
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => "Validation Error",
                    'errors' => $validator->errors(),
                ]);
            }
        } else {
            $name = $request->actual_name;
            $email = $request->email;
            $password = $request->password;
            $role = $request->user_position;
            $report_to = $request->report_to;
            $location = $request->user_location;
            $permission = $request->user_rights;
            $created_by = auth()->user()->id;
            $modified_by = auth()->user()->id;
            $user = new User();
            $user->name = $name;
            $user->email = $email;
            $user->password = bcrypt($password);
            $user->country_id = $location;
            $user->reporting_user_id = $report_to;
            $user->created_by = $created_by;
            $user->modified_by = $modified_by;
            $user->save();
            $user->assignRole($role);
            if ($permission) {
                foreach ($permission as $key => $value) {
                    $user->givePermissionTo($value);
                }
            }

            $logData = array(
                'user_id' => auth()->user()->id,
                'action_id' => $user->id,
                'action_type' => 'create',
                'module' => 'user',
                'description' => $user->id . ' : User Created',
            );

            $storeLog = SystemLogController::addLog($logData);

            return response()->json([
                'status' => true,
                'message' => "User added successfully",
            ]);
        }
    }

    public function edit_user(Request $request)
    {
        $user_id = $request->user_id;
        $user = User::find($user_id)->toArray();
        // $country=countries::find($user['country_id'])->toArray();
        $user_roles = User::find($user_id)->getRoleNames()->toArray();
        $user_permissions = User::find($user_id)->getPermissionNames()->toArray();

        $user_data = array();
        $user_data['id'] = $user['id'];
        $user_data['name'] = $user['name'];
        $user_data['email'] = $user['email'];
        $user_data['reporting_user_id'] = $user['reporting_user_id'];
        $user_data['country_id'] = $user['country_id'];
        $user_data['city_id'] = $user['city_id'];
        $user_data['role'] = $user_roles;
        $user_data['permissions'] = $user_permissions;
        return response()->json([
            'status' => true,
            'message' => "User data",
            'user_data' => $user_data,
        ]);
    }

    public function update_user(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'actual_name' => 'required|max:255',
            'email' => 'required|email',
            'user_position' => 'required',
            'user_location' => 'required',
        ]);
        if ($validator->fails()) {
            $email_check = User::where('email', $request->email)->first();
            if ($email_check) {
                return response()->json([
                    'status' => false,
                    'message' => "Email already exists",
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => "Validation Error",
                    'errors' => $validator->errors(),
                ]);
            }
        } else {
            $user_id = $request->user_id;
            $name = $request->actual_name;
            $email = $request->email;
            $role = $request->user_position;
            $location = $request->user_location;
            $report_to = $request->report_to;
            $permission = $request->user_rights;
            $user = User::find($user_id);
            $user->name = $name;
            $user->email = $email;
            $user->country_id = $location;
            $user->reporting_user_id = $report_to;
            $user->save();
            $user->syncRoles($role);
            if ($permission) {
                $user->syncPermissions($permission);
            } else {
                $user->syncPermissions([]);
            }

            $logData = array(
                'user_id' => auth()->user()->id,
                'action_id' => $user->id,
                'action_type' => 'update',
                'module' => 'user',
                'description' => $user->id . ' : User Updated',
            );

            $storeLog = SystemLogController::addLog($logData);

            return response()->json([
                'status' => true,
                'message' => "User updated successfully",
            ]);
        }
    }

    public function delete_user(Request $request)
    {
        $user_id = $request->user_id;
        $user = User::find($user_id);
        if ($user) {
            $user->is_deleted = 1;
            $user->save();

            $logData = array(
                'user_id' => auth()->user()->id,
                'action_id' => $user->id,
                'action_type' => 'delete',
                'module' => 'user',
                'description' => $user->id . ' : User Deleted',
            );

            $storeLog = SystemLogController::addLog($logData);

            return response()->json([
                'status' => true,
                'message' => "User deleted successfully"
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "User not found"
            ]);
        }
    }

    public function report_to_user_list(Request $request)
    {
        $role = $request->role;
        if ($role == 'director') {
            $users = User::role('administrator')
                ->where('is_deleted', null)
                ->get()->toArray();
        } else if ($role == 'general manager') {
            $users = User::role('director')
                ->where('is_deleted', null)
                ->get()->toArray();
        } else if ($role == 'bdm') {
            $users = User::role('general manager')
                ->where('is_deleted', null)
                ->get()->toArray();
        } else if ($role == 'bde') {
            $users = User::role('bdm')
                ->where('is_deleted', null)
                ->get()->toArray();
        } else {
            $users = User::where('is_deleted', 0)->where('id', '!=', auth()->user()->id)->get();
        }

        $report_to_user = array();
        if ($users) {
            foreach ($users as $key => $value) {
                $report_to_user[$key]['id'] = $value['id'];
                $report_to_user[$key]['name'] = $value['name'];
            }
        } else {
            $report_to_user = array();
        }
        return response()->json([
            'status' => true,
            'message' => "Report to user list",
            'users' => $report_to_user,
        ]);
    }

    public function getUsersList(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndexArr = $request->get('order');
        $columnNameArr = $request->get('columns');
        $orderArr = $request->get('order');
        $searchArr = $request->get('search');

        $columnIndex = isset($columnIndexArr[0]['column']) ? $columnIndexArr[0]['column'] : ''; // Column index
        $columnName = !empty($columnIndex) ? $columnNameArr[$columnIndex]['data'] : 'id'; // Column name
        $columnSortOrder = !empty($columnIndex) ? $orderArr[0]['dir'] : 'Asc'; // asc or desc
        $searchValue = $searchArr['value']; // Search value

        $totalRecords = User::select('count(*) as allcount')->where('is_deleted', null)->count();
        $totalRecordswithFilter = User::with('roles')->where('is_deleted', null)
            ->where('name', 'like', '%' . $searchValue . '%')
            ->count();

        // $users_data = User::with('roles')->where('is_deleted', null)
        //     ->with('country')
        //     ->where('name', 'like', '%' . $searchValue . '%')
        //     ->orWhere('id', 'like', '%' . $searchValue . '%')
        //     ->orWhere('email', 'like', '%' . $searchValue . '%')
        //     ->orWhereHas('country', function ($query) use ($searchValue,$columnName,$columnSortOrder) {
        //         $query->where('country_name', 'like', '%' . $searchValue . '%');
        //         if($columnName == 'country_name'){
        //             $query->orderBy('countries.country_name', $columnSortOrder);
        //         }
        //     })
        //     ->orWhereHas('roles', function ($query) use ($searchValue) {
        //         $query->where('name', 'like', '%' . $searchValue . '%');
        //     })
        //     ->orderBy($columnName, $columnSortOrder)
        //     ->skip($start)
        //     ->take($rowperpage)
        //     ->get()->toArray();

        //get users data from database join role tables

        $users_data = DB::table('users')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->join('countries', 'users.country_id', '=', 'countries.id')
            ->select('users.id','users.is_deleted', 'users.name', 'users.email', 'countries.country_name', 'roles.name as role_name')
            ->where('users.is_deleted', null)
            ->where(function ($query) use ($searchValue) {
                $query->where('users.name', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.id', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.email', 'like', '%' . $searchValue . '%')
                    ->orWhere('countries.country_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('roles.name', 'like', '%' . $searchValue . '%');
            })
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get()->toArray();

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
            //  echo $record['user_role'];
            if ($record->email != 'admin@admin.com') {
                $action_btn = '';
                if ($user_edit_permissions) {
                    $action_btn .= '<button type="button" class="btn btn btn-icon btn-outline-primary edit_user" value="' . $record->id . '">
                    <i class="bx bx-edit-alt"></i></button>&nbsp;&nbsp;&nbsp;';
                }
                if ($user_delete_permissions) {
                    $action_btn .= '<button type="button" class="btn btn btn-icon btn-outline-primary delete_user" value="' . $record->id . '">
                    <i class="bx bx-trash-alt"></i></button>';
                }
                if (!$user_edit_permissions && !$user_delete_permissions) {
                    $action_btn .= '<span class="badge bg-secondary">No Permission</span>';
                }
            } else {
                $action_btn = '';
            }


            $dataArr[] = array(
                "id" => $i++,
                "role_name" => ucwords($record->role_name),
                "email" => $record->email,
                "name" => $record->name,
                "country_name" => $record->country_name,
                "action" => $action_btn,
            );
        }
        $logData = array(
            'user_id' => auth()->user()->id,
            'action_id' => $user->id,
            'action_type' => 'view',
            'module' => 'user',
            'description' => 'User list view',
        );

        $storeLog = SystemLogController::addLog($logData);
        return response()->json([
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecordswithFilter,
            "data" => $dataArr,
        ]);
    }


    public function permissionsCheck(Request $request)
    {
        $role_name = $request->role;
        $administrator = [
            'manage_masters',
            'manage_users',
            'user_view',
            'user_add',
            'user_edit',
            'user_delete',
            'manage_city',
            'city_view',
            'city_add',
            'city_edit',
            'city_delete',
            'manage_country',
            'country_view',
            'country_add',
            'country_edit',
            'country_delete',
            'manage_client',
            'client_view',
            'client_add',
            'client_edit',
            'client_delete',
            'manage_industry',
            'industry_view',
            'industry_add',
            'industry_edit',
            'industry_delete',
            'manage_soft_calling',
            'soft_call_upload',
            'soft_call_add',
            'soft_call_incoming',
            'soft_call_outgoing',
            'soft_call_assign',
        ];

        $director = [
            'manage_city',
            'city_view',
            'city_add',
            'city_edit',
            'city_delete',
            'manage_country',
            'country_view',
            'country_add',
            'country_edit',
            'country_delete',
            'manage_client',
            'client_view',
            'client_add',
            'client_edit',
            'client_delete',
            'manage_industry',
            'industry_view',
            'industry_add',
            'industry_edit',
            'industry_delete',
            'manage_soft_calling',
            'soft_call_upload',
            'soft_call_add',
            'soft_call_incoming',
            'soft_call_outgoing',
            'soft_call_assign',
        ];

        $general_manager = [
            'manage_client',
            'client_view',
            'client_add',
            'client_edit',
            'client_delete',
            'manage_soft_calling',
            'soft_call_upload',
            'soft_call_add',
            'soft_call_incoming',
            'soft_call_outgoing',
            'soft_call_assign',
        ];

        $bde = [
            'manage_client',
            'client_view',
            'client_add',
            'client_edit',
            'client_delete',
            'manage_soft_calling',
            'soft_call_upload',
            'soft_call_add',
            'soft_call_incoming',
            'soft_call_outgoing',
            'soft_call_assign',
        ];

        $bdm = [
            'manage_client',
            'client_view',
            'client_add',
            'client_edit',
            'client_delete',
            'manage_soft_calling',
            'soft_call_upload',
            'soft_call_add',
            'soft_call_incoming',
            'soft_call_outgoing',
            'soft_call_assign',
        ];

        $softcaller = [
            'manage_soft_calling',
            'soft_call_upload',
            'soft_call_add',
            'soft_call_incoming',
            'soft_call_outgoing',
            'soft_call_assign',
        ];


        if ($role_name == 'administrator') {
            $permissions = $administrator;
        } else if ($role_name == 'general manager') {
            $permissions = $general_manager;
        } else if ($role_name == 'director') {
            $permissions = $director;
        } else if ($role_name == 'bde') {
            $permissions = $bde;
        } else if ($role_name == 'bdm') {
            $permissions = $bdm;
        } else if ($role_name == 'softcaller') {
            $permissions = $softcaller;
        }

        return response()->json([
            'status' => true,
            'message' => "Permissions found",
            'permissions' => $permissions
        ]);
    }

    public function change_password()
    {
        return view('backend/change_password');
    }

    public function update_password(Request $request)
    {
        //dd($request->all());

        $old_password = $request->old_password;
        $new_password = $request->new_password;
        $confirm_password = $request->confirm_password;

        //check old password is correct or not
        if (Hash::check($old_password, auth()->user()->password)) {
            //check new password and confirm password is same or not
            if ($new_password == $confirm_password) {
                $user = User::find(auth()->user()->id);
                $user->password = Hash::make($new_password);
                $user->save();

                $logData = array(
                    'user_id' => auth()->user()->id,
                    'action_id' => $user->id,
                    'action_type' => 'update',
                    'module' => 'user',
                    'description' => 'User password updated',
                );

                $storeLog = SystemLogController::addLog($logData);

                return response()->json([
                    'status' => true,
                    'message' => "Password updated successfully",
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => "New password and confirm password is not same",
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => "Old password is incorrect",
            ]);
        }

    }
}
