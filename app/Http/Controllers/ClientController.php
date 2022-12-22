<?php

namespace App\Http\Controllers;

use Whoops\Run;
use App\Models\City;
use App\Models\Client;
use App\Models\Country;
use App\Models\Industry;
use Illuminate\Http\Request;
use App\Models\ContactPerson;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\SystemLogController;

class ClientController extends Controller
{
    //
    function getClientList(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndexArr = $request->get('order');
        $columnNameArr = $request->get('columns');
        $orderArr = $request->get('order');
        $searchArr = $request->get('search');

        $columnIndex = isset($columnIndexArr[0]['column']) ? $columnIndexArr[0]['column'] : ''; // Column index
        $columnName = !empty($columnIndex) ? $columnNameArr[$columnIndex]['data'] : 'clients.updated_at'; // Column name
        $columnSortOrder = !empty($columnIndex) ? $orderArr[0]['dir'] : 'DESC'; // asc or desc
        $searchValue = $searchArr['value']; // Search value

        // Total records
        $totalRecords = Client::select('count(*) as allcount')->where('is_deleted', null)->count();
        $totalRecordswithFilter = Client::select('count(*) as allcount')
            ->where('is_deleted', null)
            ->with('country')
            ->with('city')
            ->with('industry')
            ->orWhereHas('country', function ($query) use ($searchValue) {
                $query->where('country_name', 'like', '%' . $searchValue . '%');
            })
            ->count();


        $records = DB::table('clients')
            ->leftJoin('countries', 'countries.id', '=', 'clients.country_id')
            ->leftJoin('cities', 'cities.id', '=', 'clients.city_id')
            ->leftJoin('industries', 'industries.id', '=', 'clients.industry_id')
            ->where('clients.is_deleted', '=', null)
            ->where(function ($query) use ($searchValue) {
                $query->where('company_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('country_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('industry_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('address', 'like', '%' . $searchValue . '%')
                    ->orWhere('post_box_no', 'like', '%' . $searchValue . '%')
                    ->orWhere('city_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('phone_no', 'like', '%' . $searchValue . '%')
                    ->orWhere('email', 'like', '%' . $searchValue . '%')
                    ->orWhere('website_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('active_status', 'like', '%' . $searchValue . '%');
            })
            ->select('clients.*', 'countries.country_name', 'cities.city_name', 'industries.industry_name')
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get()->toArray();

        $records = json_decode(json_encode($records), true);

        $user = Auth::user();
        $user_permissions = $user->getAllPermissions()->pluck('name')->toArray();
        $user_edit_permissions_check = 'client_edit';
        $user_delete_permissions_check = 'client_delete';
        $user_edit_permissions = in_array($user_edit_permissions_check, $user_permissions);
        $user_delete_permissions = in_array($user_delete_permissions_check, $user_permissions);

        $dataArr = [];
        $i = $start + 1;
        foreach ($records as $record) {

            $action_btn = '';
            if ($user_edit_permissions) {
                $action_btn .= '<a href="javascript:void(0);" class="btn btn btn-icon btn-outline-primary edit_client" data-id="' . $record['id'] . '" title="Edit">
                <i class="bx bx-edit-alt"></i></a>&nbsp;&nbsp;&nbsp;';
            }
            if ($user_delete_permissions) {
                $action_btn .= '<a href="javascript:void(0);" class="btn btn btn-icon btn-outline-primary delete_client" data-id="' . $record['id'] . '" data-name="' . $record['company_name'] . '" title="Delete">
                <i class="bx bx-trash-alt"></i></a>';
            }
            if (!$user_edit_permissions && !$user_delete_permissions) {
                $action_btn .= '<span class="badge bg-secondary">No Permission</span>';
            }

            $dataArr[] = array(
                "id" => $i++,
                "country_name" => $record['country_name'] ?? '',
                "company_name" => $record['company_name'],
                "industry_name" => $record['industry_name'] ?? '',
                "address" => $record['address'],
                "post_box_no" => $record['post_box_no'],
                "city_name" => $record['city_name'] ?? '',
                "phone_no" => $record['phone_no'],
                "email" => $record['email'],
                "website_name" => $record['website_name'],
                "active_status" => ($record['active_status'] == 1) ? 'Active' : 'Inactive',
                "action" => $action_btn
            );
        }

        $logData = array(
            'user_id' => auth()->user()->id,
            'action_type' => 'view',
            'module' => 'client',
            'description' => 'Client List Viewed',
        );

        $storeLog = SystemLogController::addLog($logData);

        return response()->json([
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecordswithFilter,
            "data" => $dataArr,
        ]);
    }

    function manageClient(Request $request)
    {
        $client = $cities = [];

        if (isset($request->id) && !empty($request->id)) {
            $client = Client::find($request->id)->toArray();
            $client['contactPerson'] = ContactPerson::where('client_id', $request->id)->where('is_deleted', null)->get()->toArray();
            $cities = City::where('country_id', $client['country_id'])->where('is_deleted', null)->get()->toArray();
        }
        $countries = Country::where('is_deleted', null)->get()->toArray();
        $indusries = Industry::where('is_deleted', null)->get()->toArray();

        return view('backend.masters.modals.client_master_form', ['client' => $client, 'countries' => $countries, 'indusries' => $indusries, 'cities' => $cities]);
    }

    function getCity(Request $request)
    {
        $cities = City::where('country_id', $request->id)->where('is_deleted', null)->get()->toArray();

        return response()->json([
            'status' => true,
            'cities' => $cities,
        ]);
    }

    function saveClient(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|max:255',
            'industry_id' => 'required',
            'country_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'validation Error'
            ]);
        } else {

            $user = Auth::user();
            if (Client::find($request->id)) {
                $clientData = [
                    'lead_id' => $request->lead_id,
                    'company_name' => $request->company_name,
                    'industry_id' => $request->industry_id,
                    'country_id' => $request->country_id,
                    'city_id' => $request->city_id,
                    'address' => $request->address,
                    'post_box_no' => $request->post_box_no,
                    'phone_no' => $request->phone_no,
                    'email' => $request->email,
                    'website_name' => $request->website_name,
                    'sort_description' => $request->sort_description,
                    'active_status' => $request->active_status,
                    'modified_by' => $user->id,
                ];
                Client::whereId($request->id)->update($clientData);

                $logData = array(
                    'user_id' => auth()->user()->id,
                    'action_id' => $request->id,
                    'action_type' => 'update',
                    'module' => 'client',
                    'description' => 'Client Updated',
                );

                $storeLog = SystemLogController::addLog($logData);

                for ($i = 0; $i < sizeof($request->cp_name); $i++) {
                    if (isset($request->cp_id[$i]) && !empty($request->cp_id[$i])) {
                        $cpData = [
                            'client_id' => $request->id,
                            'name' => $request->cp_name[$i],
                            'department' => $request->department[$i],
                            'designation' => $request->designation[$i],
                            'email' => $request->cp_email[$i],
                            'mobile_no' => $request->mobile_no[$i],
                            'dob' => $request->dob[$i],
                            'modified_by' => $user->id,
                        ];
                        ContactPerson::whereId($request->cp_id[$i])->update($cpData);

                        $logData = array(
                            'user_id' => auth()->user()->id,
                            'action_id' => $request->id,
                            'action_to_id' => $request->cp_id[$i],
                            'action_type' => 'update',
                            'module' => 'client',
                            'description' =>  $request->company_name . ' Client Contact Person Updated',
                        );

                        $storeLog = SystemLogController::addLog($logData);
                    } else {
                        $contactPersonResponse = ContactPerson::create([
                            'client_id' => $request->id,
                            'name' => $request->cp_name[$i],
                            'department' => $request->department[$i],
                            'designation' => $request->designation[$i],
                            'email' => $request->cp_email[$i],
                            'mobile_no' => $request->mobile_no[$i],
                            'dob' => $request->dob[$i],
                            'created_by' => $user->id,
                        ]);

                        $logData = array(
                            'user_id' => auth()->user()->id,
                            'action_id' => $request->id,
                            'action_to_id' => $request->cp_id[$i],
                            'action_type' => 'create',
                            'module' => 'client',
                            'description' =>  $request->company_name . ' Client Contact Person Created',
                        );

                        $storeLog = SystemLogController::addLog($logData);
                    }
                }

                return response()->json([
                    "status" => true,
                    "message" => "Client updated successfully"
                ]);
            } else {
                $clientCheck = Client::where('company_name', $request->company_name)->where('country_id', $request->country_id)->where('is_deleted', null)->first();
                if ($clientCheck) {
                    return response()->json([
                        'status' => false,
                        'errors' => ["Client already exists"],
                    ]);
                }

                $clientResponse = Client::create([
                    'lead_id' => $request->lead_id,
                    'company_name' => $request->company_name,
                    'industry_id' => $request->industry_id,
                    'country_id' => $request->country_id,
                    'city_id' => $request->city_id,
                    'address' => $request->address,
                    'post_box_no' => $request->post_box_no,
                    'phone_no' => $request->phone_no,
                    'email' => $request->email,
                    'website_name' => $request->website_name,
                    'sort_description' => $request->sort_description,
                    'active_status' => $request->active_status,
                    'created_by' => $user->id,
                    'manage_by' => $user->id,
                ]);
                $logData = array(
                    'user_id' => auth()->user()->id,
                    'action_id' => $clientResponse->id,
                    'action_type' => 'create',
                    'module' => 'client',
                    'description' =>  $request->company_name . ' Client Created',
                );

                $storeLog = SystemLogController::addLog($logData);

                if (isset($clientResponse->id) && !empty($clientResponse->id)) {
                    for ($i = 0; $i < sizeof($request->cp_name); $i++) {
                        if (isset($request->cp_id[$i]) && !empty($request->cp_id[$i])) {
                            $cpData = [
                                'client_id' => $clientResponse->id,
                                'name' => $request->cp_name[$i],
                                'department' => $request->department[$i],
                                'designation' => $request->designation[$i],
                                'email' => $request->cp_email[$i],
                                'mobile_no' => $request->mobile_no[$i],
                                'dob' => $request->dob[$i],
                                'modified_by' => $user->id,
                            ];
                            ContactPerson::whereId($request->cp_id[$i])->update($cpData);
                            $logData = array(
                                'user_id' => auth()->user()->id,
                                'action_id' => $clientResponse->id,
                                'action_to_id' => $request->cp_id[$i],
                                'action_type' => 'update',
                                'module' => 'client',
                                'description' =>  $request->company_name . ' Client Contact Person Updated',
                            );

                            $storeLog = SystemLogController::addLog($logData);
                        } else {
                            $contactPersonResponse = ContactPerson::create([
                                'client_id' => $clientResponse->id,
                                'name' => $request->cp_name[$i],
                                'department' => $request->department[$i],
                                'designation' => $request->designation[$i],
                                'email' => $request->cp_email[$i],
                                'mobile_no' => $request->mobile_no[$i],
                                'dob' => $request->dob[$i],
                                'created_by' => $user->id,
                            ]);
                            $logData = array(
                                'user_id' => auth()->user()->id,
                                'action_id' => $clientResponse->id,
                                'action_to_id' => $contactPersonResponse->id,
                                'action_type' => 'create',
                                'module' => 'client',
                                'description' =>  $request->company_name . ' Client Contact Person Created',
                            );

                            $storeLog = SystemLogController::addLog($logData);
                        }
                    }
                }

                return response()->json([
                    "status" => true,
                    "message" => "Client inserted successfully"
                ]);
            }


            return response()->json([
                "status" => false,
                "message" => "Something went wrong.Try again after sometime"
            ]);
        }
    }

    /**
     * Delete contact person
     */
    function deleteContactPerson(Request $request)
    {
        $user = Auth::user();

        if (ContactPerson::find($request->id)) {
            $data = [
                'is_deleted' => '1',
                'modified_by' => $user->id,
            ];
            ContactPerson::whereId($request->id)->update($data);

            $logData = array(
                'user_id' => auth()->user()->id,
                'action_id' => $request->client_id,
                'action_to_id' => $request->id,
                'action_type' => 'delete',
                'module' => 'client',
                'description' =>  $request->company_name . ' Client Contact Person Deleted',
            );

            $storeLog = SystemLogController::addLog($logData);

            return response()->json([
                "status" => true,
                "message" => "Contact Person deleted successfully"
            ]);
        }

        return response()->json([
            "status" => false,
            "message" => "Something went wrong.Try again after sometime"
        ]);
    }

    /**
     * delete client
     */
    function deleteClient(Request $request)
    {
        $user = Auth::user();

        if (Client::find($request->id)) {
            $data = [
                'is_deleted' => '1',
                'modified_by' => $user->id,
            ];
            Client::whereId($request->id)->update($data);

            $logData = array(
                'user_id' => auth()->user()->id,
                'action_id' => $request->id,
                'action_type' => 'delete',
                'module' => 'client',
                'description' =>  $request->company_name . ' Client Deleted',
            );

            $storeLog = SystemLogController::addLog($logData);

            return response()->json([
                "status" => true,
                "message" => "Client deleted successfully"
            ]);
        }

        return response()->json([
            "status" => false,
            "message" => "Something went wrong.Try again after sometime"
        ]);
    }
}
