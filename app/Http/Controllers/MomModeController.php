<?php

namespace App\Http\Controllers;

use App\Models\Mom;
use App\Models\MomMode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MomModeController extends Controller
{
    public function getMomModeList(Request $request)
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
        $totalRecords = MomMode::select('count(*) as allcount')->where('is_deleted', null)->count();
        $totalRecordswithFilter = MomMode::select('count(*) as allcount')
            ->where('is_deleted', '=', null)
            ->where(function ($query) use ($searchValue) {
                $query->where('mode_name', 'like', '%' . $searchValue . '%');
            })
            ->count();

        // Get records, also we have included search filter as well
        $records = MomMode::orderBy($columnName, $columnSortOrder)
            ->where('is_deleted', '=', null)
            ->where(function ($query) use ($searchValue) {
                $query->where('mode_name', 'like', '%' . $searchValue . '%');
            })
            ->select('*')
            ->skip($start)
            ->take($rowperpage)
            ->get();


        $user = Auth::user();
        $user_permissions = $user->getAllPermissions()->pluck('name')->toArray();
        $user_edit_permissions_check = 'mom_mode_edit';
        $user_delete_permissions_check = 'mom_mode_delete';
        $user_edit_permissions = in_array($user_edit_permissions_check, $user_permissions);
        $user_delete_permissions = in_array($user_delete_permissions_check, $user_permissions);
        $dataArr = [];
        $i = $start + 1;
        foreach ($records as $record) {
            $action_btn = '';
            if ($user_edit_permissions) {
                $action_btn .= '<a href="javascript:void(0);" class="btn btn btn-icon btn-outline-primary edit_mom_mode" data-id="' . $record->id . '" title="Edit">
                <i class="bx bx-edit-alt"></i></a>&nbsp;&nbsp;&nbsp;';
            }
            if ($user_delete_permissions) {
                $action_btn .= '<a href="javascript:void(0);" class="btn btn btn-icon btn-outline-primary delete_mom_mode" data-id="' . $record->id . '" data-name="' . $record->mode_name . '" title="Delete">
                <i class="bx bx-trash-alt"></i></a>';
            }
            if (!$user_edit_permissions && !$user_delete_permissions) {
                $action_btn .= '<span class="badge bg-secondary">No Permission</span>';
            }


            $dataArr[] = array(
                "id" => $i++,
                "mode_name" => $record->mode_name,
                "action" => $action_btn,
            );
        }

        return response()->json([
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecordswithFilter,
            "data" => $dataArr,
        ]);

    }

    public function manageMomMode(Request $request)
    {
        $mom_mode = [];
        if (isset($request->id) && !empty($request->id)) {
            $mom_mode = MomMode::find($request->id);
        }
        return view('backend.masters.modals.mom_mode_master_form', ['mom_mode' => $mom_mode]);

    }

    public function saveMomMode(Request $request)
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
            $modeName = preg_replace("/\s+/", " ", ucwords(strtolower($request->name)));

            $modeCheck = MomMode::where('mode_name', $modeName)->first();
            if ($modeCheck) {
                return response()->json([
                    'status' => false,
                    'errors' => ["Mode Of Meeting already exists"],
                ]);
            }

            if (MomMode::find($request->id)) {
                $modeData = [
                    'mode_name' => $modeName,
                    'modified_by' => $user->id,
                ];
                MomMode::whereId($request->id)->update($modeData);

                return response()->json([
                    "status" => true,
                    "message" => "Mode Of Meeting updated successfully",
                ]);
            } else {
                $response = MomMode::create([
                    'mode_name' => $modeName,
                    'created_by' => $user->id,
                ]);

                return response()->json([
                    "status" => true,
                    "message" => "Mode Of Meeting created successfully",
                ]);

            }
        }
    }


    public function deleteMomMode(Request $request)
    {
        $user = Auth::user();
        if (MomMode::find($request->id)) {
            if (Mom::where('mode_of_meeting', $request->id)->first()) {
                return response()->json([
                    "status" => false,
                    "message" => "Mode of Meeting is in use"
                ]);
            }
        } else {
            MomMode::whereId($request->id)->update(['is_deleted' => 1, 'modified_by' => $user->id]);

            return response()->json([
                "status" => true,
                "message" => "Mode of Meeting deleted successfully",
            ]);
        }
    }


}
