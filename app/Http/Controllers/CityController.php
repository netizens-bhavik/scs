<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\User;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\SystemLogController;

class CityController extends Controller
{
    //
    public function getCityList(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndexArr = $request->get('order');
        $columnNameArr = $request->get('columns');
        $orderArr = $request->get('order');
        $searchArr = $request->get('search');

        $columnIndex = isset($columnIndexArr[0]['column']) ? $columnIndexArr[0]['column'] : ''; // Column index
        $columnName = !empty($columnIndex) ? $columnNameArr[$columnIndex]['data'] : 'cities.updated_at'; // Column name
        $columnSortOrder = !empty($columnIndex) ? $orderArr[0]['dir'] : 'DESC'; // asc or desc
        $searchValue = $searchArr['value']; // Search value

        // Total records
        $totalRecords = City::select('count(*) as allcount')->where('is_deleted', null)->count();
        $totalRecordswithFilter = City::select('count(*) as allcount')
            ->with('country')
            ->where('is_deleted', null)
            ->where('city_name', 'like', '%' . $searchValue . '%')
            ->orWhereHas('country', function ($query) use($searchValue) {
                $query->where('country_name', 'like', '%' . $searchValue . '%');
            })
            ->count();

        $records = DB::table('cities')
                ->leftJoin('countries', 'countries.id', '=', 'cities.country_id')
                ->where('cities.is_deleted', '=', null)
                ->where(function($query) use($searchValue) {
                    $query->where('country_name', 'like', '%' . $searchValue . '%')
                            ->orWhere('city_name', 'like', '%' . $searchValue . '%');
                })
                ->select('cities.*','countries.country_name')
                ->orderBy($columnName, $columnSortOrder)
                ->skip($start)
                ->take($rowperpage)
                ->get()->toArray();

        $records = json_decode(json_encode($records), true);

        $user = Auth::user();
        $user_permissions = $user->getAllPermissions()->pluck('name')->toArray();
        $user_edit_permissions_check = 'city_edit';
        $user_delete_permissions_check = 'city_delete';
        $user_edit_permissions = in_array($user_edit_permissions_check, $user_permissions);
        $user_delete_permissions = in_array($user_delete_permissions_check, $user_permissions);

        $dataArr = [];
        $i = $start+1;
        foreach ($records as $record) {

            $action_btn = '';
            if ($user_edit_permissions){
                $action_btn .= '<a href="javascript:void(0);" class="btn btn btn-icon btn-outline-primary edit_city" data-id="' . $record['id'] . '" title="Edit">
                <i class="bx bx-edit-alt"></i></a>&nbsp;&nbsp;&nbsp;';
            }
            if ($user_delete_permissions){
                $action_btn .= '<a href="javascript:void(0);" class="btn btn btn-icon btn-outline-primary delete_city" data-id="' . $record['id'] . '" data-name="' . $record['city_name'] . '" title="Delete">
                <i class="bx bx-trash-alt"></i></a>';
            }
            if (!$user_edit_permissions && !$user_delete_permissions) {
                $action_btn .= '<span class="badge bg-secondary">No Permission</span>';
            }

            $dataArr[] = array(
                "id" => $i++,
                "city_name" => $record['city_name'],
                "country_name" => $record['country_name'],
                "action" => $action_btn,
            );
        }

        $logData = array(
            'user_id' => auth()->user()->id,
            'action_type' => 'view',
            'module' => 'city',
            'description' => 'City list view',
        );

        $storeLog = SystemLogController::addLog($logData);

        return response()->json([
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecordswithFilter,
            "data" => $dataArr,
        ]);
    }

    function manageCity(Request $request)
    {
        $city = [];
        if (isset($request->id) && !empty($request->id)) {
            $city = City::find($request->id);
        }
        $countries = Country::where('is_deleted', null)->get()->toArray();
        return view('backend.masters.modals.city_master_form', ['city' => $city, 'countries' => $countries]);
    }

    function saveCity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'country_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'validation Error'
            ]);
        } else {

            $user = Auth::user();
            $cityName = preg_replace("/\s+/", " ", ucwords(strtolower($request->name)));

            $cityCheck = City::where('city_name', $cityName)->where('is_deleted', null)->where('country_id', $request->country_id)->first();
            if ($cityCheck) {
                return response()->json([
                    'status' => false,
                    'errors' => ["City Name already exists"],
                ]);
            }

            if (City::find($request->id)) {
                $cityData = [
                    'city_name' => $cityName,
                    'country_id' => $request->country_id,
                    'modified_by' => $user->id,
                ];
                City::whereId($request->id)->update($cityData);

                $logData = array(
                    'user_id' => auth()->user()->id,
                    'action_id' => $request->id,
                    'action_type' => 'update',
                    'module' => 'city',
                    'description' =>  auth()->user()->name . ' updated city ' . $cityName,
                );

                $storeLog = SystemLogController::addLog($logData);

                return response()->json([
                    "status" => true,
                    "message" => "City updated successfully"
                ]);
            } else {
                $response = City::create([
                    'city_name' => $cityName,
                    'country_id' => $request->country_id,
                    'created_by' => $user->id,
                ]);

                $logData = array(
                    'user_id' => auth()->user()->id,
                    'action_id' => $response->id,
                    'action_type' => 'create',
                    'module' => 'city',
                    'description' =>  auth()->user()->name . ' created a new city ' . $cityName,
                );

                $storeLog = SystemLogController::addLog($logData);

                return response()->json([
                    "status" => true,
                    "message" => "City inserted successfully"
                ]);
            }

            return response()->json([
                "status" => false,
                "message" => "Something went wrong.Try again after sometime"
            ]);
        }
    }

    function deleteCity(Request $request)
    {
        $user = Auth::user();

        $cityCheck = User::where('city_id', $request->id)->where('is_deleted', null)->first();

        if ($cityCheck) {
            return response()->json([
                "status" => false,
                "message" => "City can't deleted"
            ]);
        }

        if (City::find($request->id)) {
            $cityData = [
                'is_deleted' => '1',
                'modified_by' => $user->id,
            ];
            City::whereId($request->id)->update($cityData);

            $logData = array(
                'user_id' => auth()->user()->id,
                'action_type' => 'delete',
                'module' => 'city',
                'description' => auth()->user()->name . ' deleted city ' . $request->name,
            );
    
            $storeLog = SystemLogController::addLog($logData);

            return response()->json([
                "status" => true,
                "message" => "City deleted successfully"
            ]);
        }
    }
}
