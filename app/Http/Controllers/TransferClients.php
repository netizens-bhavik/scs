<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\SystemLogController;

class TransferClients extends Controller
{
    public function index()
    {
        // $assign_from = User::with('roles')
        //     ->with('country')
        //     ->where('is_deleted', null)
        //     ->get()->toArray();

        //     $logData = array(
        //         'user_id' => auth()->user()->id,
        //         'action_type' => 'transfer',
        //         'module' => 'client',
        //         'description' => 'Viewed Transfer Clients List',
        //     );
        //     $storeLog = SystemLogController::addLog($logData);

        // return view('backend/transfer_client', compact('assign_from'));
    }


    public function get_transfer_to_user(Request $request)
    {
        $assign_to = User::with('roles')
            ->with('country')
            ->where('is_deleted', null)
            ->where('id', '!=', $request->transfer_from_user_id)
            ->get()->toArray();

        $transfer_to = '';
        $transfer_to .= '<option value="">Select User</option>';

        foreach ($assign_to as $key => $value) {
            $transfer_to .= '<option value="' . $value['id'] . '">' . ucwords($value['roles'][0]['name'] . '-' . $value['country']['country_name'] . '-' . $value['name']) . '</option>';
        }

        return response()->json([
            'status' => true,
            'transfer_to' => $transfer_to
        ]);
    }

    public function get_transfer_clients()
    {
        $view = view('backend/transfer_clients_list')->render();

        return response()->json([
            'status' => true,
            'clients' => $view
        ]);
    }

    public function get_transfer_clients_list(Request $request)
    {
        $transfer_from_user_id = $request->transfer_from_user_id;

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
            })->where('clients.manage_by', '=', $transfer_from_user_id)
            ->select('clients.*', 'countries.country_name', 'cities.city_name', 'industries.industry_name')
            ->orderBy($columnName, $columnSortOrder)
            ->get()->toArray();

        $records = json_decode(json_encode($records), true);

        $dataArr = [];
        $i = $start + 1;
        foreach ($records as $record) {

            $dataArr[] = array(
                "id" => $i++,
                "checkbox"=> '<input type="checkbox" class="form-check-input transfer_clients_checkbox" name="transfer_clients[]" value="'.$record['id'].'">',
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
            );
        }

        return response()->json([
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecordswithFilter,
            "data" => $dataArr,
        ]);
    }

    public function transfer_clients(Request $request)
    {
        
        $transfer_from_user_id = $request->transfer_from_user_id;
        $transfer_to_user_id = $request->transfer_to_user_id;
        $transfer_clients = $request->transfer_clients;

        if (empty($transfer_clients)) {
            return response()->json([
                'status' => false,
                'message' => 'Please select atleast minimun 1 client to transfer.'
            ]);
        }

        foreach ($transfer_clients as $key => $value) {
            $client = Client::find($value);
            $client->manage_by = $transfer_to_user_id;
            $client->save();
        }

        $logData = array(
            'user_id' => auth()->user()->id,
            'action_id' => $transfer_from_user_id,
            'action_to_id' => $transfer_to_user_id,
            'action_type' => 'transfer',
            'module' => 'client',
            'description' =>  auth()->user()->name.' transfered clients to '.User::find($transfer_to_user_id)->name,
        );

        $storeLog = SystemLogController::addLog($logData);

        return response()->json([
            'status' => true,
            'message' => 'Clients Transfer Successfully'
        ]);
    }
}
