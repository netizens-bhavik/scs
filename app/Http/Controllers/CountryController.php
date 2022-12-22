<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\SystemLogController;

class CountryController extends Controller
{
    //
    public function getCountryList(Request $request)
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
        $totalRecords = Country::select('count(*) as allcount')->where('is_deleted', null)->count();
        $totalRecordswithFilter = Country::select('count(*) as allcount')
            ->where('is_deleted', '=', null)
            ->where(function ($query) use ($searchValue) {
                $query->where('country_name', 'like', '%' . $searchValue . '%');
                $query->orWhere('country_code', 'like', '%' . $searchValue . '%');
            })
            ->count();

        // Get records, also we have included search filter as well
        $records = Country::orderBy($columnName, $columnSortOrder)
            ->where('is_deleted', '=', null)
            ->where(function ($query) use ($searchValue) {
                $query->where('country_name', 'like', '%' . $searchValue . '%');
                $query->orWhere('country_code', 'like', '%' . $searchValue . '%');
            })
            ->select('*')
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $user = Auth::user();
        $user_permissions = $user->getAllPermissions()->pluck('name')->toArray();
        $user_edit_permissions_check = 'country_edit';
        $user_delete_permissions_check = 'country_delete';
        $user_edit_permissions = in_array($user_edit_permissions_check, $user_permissions);
        $user_delete_permissions = in_array($user_delete_permissions_check, $user_permissions);

        $dataArr = [];
        $i = $start + 1;
        foreach ($records as $record) {

            $action_btn = '';
            if ($user_edit_permissions) {
                $action_btn .= '<a href="javascript:void(0);" class="btn btn btn-icon btn-outline-primary edit_country" data-id="' . $record->id . '" title="Edit">
                <i class="bx bx-edit-alt"></i></a>&nbsp;&nbsp;&nbsp;';
            }
            if ($user_delete_permissions) {
                $action_btn .= '<a href="javascript:void(0);" class="btn btn btn-icon btn-outline-primary delete_country" data-id="' . $record->id . '" data-name="' . $record->country_name . '" title="Delete">
                <i class="bx bx-trash-alt"></i></a>';
            }
            if (!$user_edit_permissions && !$user_delete_permissions) {
                $action_btn .= '<span class="badge bg-secondary">No Permission</span>';
            }

            $dataArr[] = array(
                "id" => $i++,
                "country_name" => $record->country_name,
                "country_code" => $record->country_code,
                "action" => $action_btn,
            );
        }

        $logData = array(
            'user_id' => auth()->user()->id,
            'action_type' => 'view',
            'module' => 'country',
            'description' =>  'Country list view',
        );

        $storeLog = SystemLogController::addLog($logData);

        return response()->json([
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecordswithFilter,
            "data" => $dataArr,
        ]);
    }

    function manageCountry(Request $request)
    {
        $country = [];
        if (isset($request->id) && !empty($request->id)) {
            $country = Country::find($request->id);
        }
        return view('backend.masters.modals.country_master_form', ['country' => $country]);
    }

    function saveCountry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'country_code' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'validation Error'
            ]);
        } else {

            $user = Auth::user();
            $countryName = preg_replace("/\s+/", " ", ucwords(strtolower($request->name)));
            $country_code = preg_replace("/\s+/", " ", ucwords(strtolower($request->country_code)));

            if (Country::find($request->id)) {

                $countryData = [
                    'country_name' => $countryName,
                    'country_code' => $request->country_code,
                    'modified_by' => $user->id,
                ];
                Country::whereId($request->id)->update($countryData);

                $logData = array(
                    'user_id' => auth()->user()->id,
                    'action_id' => $request->id,
                    'action_type' => 'update',
                    'module' => 'country',
                    'description' => 'Country updated',
                );

                $storeLog = SystemLogController::addLog($logData);

                return response()->json([
                    "status" => true,
                    "message" => "Country updated successfully"
                ]);
            } else {

                $countryCheck = Country::where('country_name', $countryName)
                    ->orWhere('country_code', $country_code)
                    ->where('is_deleted', null)->first();
                if ($countryCheck) {
                    return response()->json([
                        'status' => false,
                        'errors' => ["Country Name/Code already exists"],
                    ]);
                }

                $response = Country::create([
                    'country_name' => $countryName,
                    'country_code' => $country_code,
                    'created_by' => $user->id,
                ]);

                $logData = array(
                    'user_id' => auth()->user()->id,
                    'action_id' => $response->id,
                    'action_type' => 'create',
                    'module' => 'country',
                    'description' =>  'Country created',
                );

                $storeLog = SystemLogController::addLog($logData);

                return response()->json([
                    "status" => true,
                    "message" => "Country inserted successfully"
                ]);
            }


            return response()->json([
                "status" => false,
                "message" => "Something went wrong.Try again after sometime"
            ]);
        }
    }

    function deleteCountry(Request $request)
    {
        $user = Auth::user();

        $countryCheck = City::where('country_id', $request->id)->where('is_deleted', null)->first();

        if ($countryCheck) {
            return response()->json([
                "status" => false,
                "message" => "Country can't deleted"
            ]);
        }

        if (Country::find($request->id)) {
            $countryData = [
                'is_deleted' => '1',
                'modified_by' => $user->id,
            ];
            Country::whereId($request->id)->update($countryData);

            $logData = array(
                'user_id' => auth()->user()->id,
                'action_id' => $request->id,
                'action_type' => 'delete',
                'module' => 'country',
                'description' => 'Country deleted',
            );

            $storeLog = SystemLogController::addLog($logData);

            return response()->json([
                "status" => true,
                "message" => "Country deleted successfully"
            ]);
        }
    }
}
