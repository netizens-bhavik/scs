<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Industry;
use App\Models\TempLeads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\SystemLogController;

class IndustryController extends Controller
{
    public function getIndustryList(Request $request)
    {
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
        $totalRecords = Industry::select('count(*) as allcount')->where('is_deleted', null)->count();
        $totalRecordswithFilter = Industry::select('count(*) as allcount')
            ->where('is_deleted', '=', null)
            ->where(function ($query) use ($searchValue) {
                $query->where('industry_name', 'like', '%' . $searchValue . '%');
            })
            ->count();

        // Get records, also we have included search filter as well
        $records = Industry::orderBy($columnName, $columnSortOrder)
            ->where('is_deleted', '=', null)
            ->where(function ($query) use ($searchValue) {
                $query->where('industry_name', 'like', '%' . $searchValue . '%');
            })
            ->select('*')
            ->skip($start)
            ->take($rowperpage)
            ->get();


        $user = Auth::user();
        $user_permissions = $user->getAllPermissions()->pluck('name')->toArray();
        $user_edit_permissions_check = 'industry_edit';
        $user_delete_permissions_check = 'industry_delete';
        $user_edit_permissions = in_array($user_edit_permissions_check, $user_permissions);
        $user_delete_permissions = in_array($user_delete_permissions_check, $user_permissions);
        $dataArr = [];
        $i = $start + 1;
        foreach ($records as $record) {
            $action_btn = '';
            if ($user_edit_permissions) {
                $action_btn .= '<a href="javascript:void(0);" class="btn btn btn-icon btn-outline-primary edit_industry" data-id="' . $record->id . '" title="Edit">
                <i class="bx bx-edit-alt"></i></a>&nbsp;&nbsp;&nbsp;';
            }
            if ($user_delete_permissions) {
                $action_btn .= '<a href="javascript:void(0);" class="btn btn btn-icon btn-outline-primary delete_industry" data-id="' . $record->id . '" data-name="' . $record->industry_name . '" title="Delete">
                <i class="bx bx-trash-alt"></i></a>';
            }
            if (!$user_edit_permissions && !$user_delete_permissions) {
                $action_btn .= '<span class="badge bg-secondary">No Permission</span>';
            }


            $dataArr[] = array(
                "id" => $i++,
                "industry_name" => $record->industry_name,
                "action" => $action_btn,
            );
        }

        $logData = array(
            'user_id' => auth()->user()->id,
            'action_type' => 'view',
            'module' => 'industry',
            'description' => 'Industry list viewed',
        );

        $storeLog = SystemLogController::addLog($logData);

        return response()->json([
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecordswithFilter,
            "data" => $dataArr,
        ]);
    }

    public function manageIndustry(Request $request)
    {
        $industry = [];
        if (isset($request->id) && !empty($request->id)) {
            $industry = Industry::find($request->id);
        }
        return view('backend.masters.modals.industry_master_form', ['industry' => $industry]);
    }

    public function saveIndustry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'validation Error'
            ]);
        } else {

            $user = Auth::user();
            $industryName = preg_replace("/\s+/", " ", ucwords(strtolower($request->name)));

            $industryCheck = Industry::where('industry_name', $industryName)->where('is_deleted', null)->first();
            if ($industryCheck) {
                return response()->json([
                    'status' => false,
                    'errors' => ["Industry Name already exists"],
                ]);
            }

            if (Industry::find($request->id)) {
                $countryData = [
                    'industry_name' => $industryName,
                    'modified_by' => $user->id,
                ];
                Industry::whereId($request->id)->update($countryData);

                $logData = array(
                    'user_id' => auth()->user()->id,
                    'action_id' => $request->id,
                    'action_type' => 'update',
                    'module' => 'industry',
                    'description' =>  'Industry updated',
                );

                $storeLog = SystemLogController::addLog($logData);

                return response()->json([
                    "status" => true,
                    "message" => "Industry updated successfully"
                ]);
            } else {
                $response = Industry::create([
                    'industry_name' => $industryName,
                    'created_by' => $user->id,
                ]);

                $logData = array(
                    'user_id' => auth()->user()->id,
                    'action_id' => $response->id,
                    'action_type' => 'create',
                    'module' => 'industry',
                    'description' =>  'Industry created',
                );

                $storeLog = SystemLogController::addLog($logData);

                return response()->json([
                    "status" => true,
                    "message" => "Industry inserted successfully"
                ]);
            }

            return response()->json([
                "status" => false,
                "message" => "Something went wrong.Try again after sometime"
            ]);
        }
    }
    function deleteIndustry(Request $request)
    {
        $user = Auth::user();
        if (Industry::find($request->id)) {
            if (TempLeads::where('industry_id', $request->id)->first()) {
                return response()->json([
                    "status" => false,
                    "message" => "Industry is in use"
                ]);
            } elseif (Client::where('industry_id', $request->id)->first()) {
                return response()->json([
                    "status" => false,
                    "message" => "Industry is in use"
                ]);
            } else {
                Industry::whereId($request->id)->update(['is_deleted' => 1, 'modified_by' => $user->id]);
                $logData = array(
                    'user_id' => auth()->user()->id,
                    'action_id' => $request->id,
                    'action_type' => 'delete',
                    'module' => 'industry',
                    'description' =>  'Industry deleted',
                );

                $storeLog = SystemLogController::addLog($logData);
                return response()->json([
                    "status" => true,
                    "message" => "Industry deleted successfully"
                ]);
            }
        }
    }
}
