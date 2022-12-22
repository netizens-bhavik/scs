<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Leads;
use App\Jobs\EmailJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\SystemLogController;

class AssignLeads extends Controller
{
    public function get_upcoming_assign_leads(Request $request)
    {

        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndexArr = $request->get('order');
        $columnNameArr = $request->get('columns');
        $orderArr = $request->get('order');
        $searchArr = $request->get('search');

        $columnIndex = isset($columnIndexArr[0]['column']) ? $columnIndexArr[0]['column'] : ''; // Column index
        $columnName = !empty($columnIndex) ? $columnNameArr[$columnIndex]['data'] : 'leads.updated_at'; // Column name
        $columnSortOrder = !empty($columnIndex) ? $orderArr[0]['dir'] : 'DESC'; // asc or desc
        $searchValue = $searchArr['value']; // Search value

        // Total records
        $totalRecords = Leads::select('count(*) as allcount')->where('is_deleted', null)->count();
        $totalRecordswithFilter = Leads::select('count(*) as allcount')
            ->where('is_deleted', null)
            ->count();

        $records = DB::table('leads')
            ->leftJoin('temp_leads', 'temp_leads.id', '=', 'leads.temp_lead_id')
            ->leftJoin('countries', 'countries.id', '=', 'temp_leads.company_country_id')
            ->leftJoin('cities', 'cities.id', '=', 'temp_leads.company_city_id')
            ->where('leads.is_deleted', '=', null)
            ->where('leads.is_requirement', '=', 1)
            ->where('leads.bdm_id', '=', null)
            ->where(function ($query) use ($searchValue) {
                $query->where('company_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('country_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('company_phone_no', 'like', '%' . $searchValue . '%')
                    ->orWhere('contact_person_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('spoken_with', 'like', '%' . $searchValue . '%')
                    ->orWhere('contact_no', 'like', '%' . $searchValue . '%')
                    ->orWhere('basic_requirement', 'like', '%' . $searchValue . '%');
            })
            ->select('temp_leads.*', 'countries.country_name', 'leads.*')
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get()->toArray();


        $assign_to = User::with('roles')
            ->with('country')
            ->where('is_deleted', null)
            ->get()->toArray();


        $action_dropdown = '';
        $bdm_bde_users = array();
        foreach ($assign_to as $key => $value) {
            $bdm_bde_users['assign'] = '<option value="' . $value['id'] . '">' . ucwords($value['roles'][0]['name'] . '-' . $value['country']['country_name'] . '-' . $value['name']) . '</option>';
            $action_dropdown .= $bdm_bde_users['assign'];
        }
        $dataArr = [];
        $i = 1;
        foreach ($records as $record) {
            $dataArr[] = array(
                "id" => $i++,
                "client_name" => $record->contact_person_name,
                "country_name" => $record->country_name,
                "company_name" => $record->company_name,
                "phone_number" => $record->company_phone_no,
                "contact_no" => $record->contact_no,
                "spoken_with" => $record->spoken_with,
                "basic_requirement" => $record->basic_requirement,
                "action" => '<select class="form-control form-select assign_to_drop" name="assign_to[]" data-lead_id="' . $record->id . '" id="assign_to"><option value="">Select</option>' . $action_dropdown . '</select>',
            );
        }

        $logData = array(
            'user_id' => auth()->user()->id,
            'action_type' => 'view',
            'module' => 'softcall',
            'description' =>  'Viewed upcoming assign leads',
        );

        $storeLog = SystemLogController::addLog($logData);

        return response()->json([
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecordswithFilter,
            "data" => $dataArr,
        ]);
    }

    public function assign_leads(Request $request)
    {

        $assign_to = $request->assign_to;

        foreach ($assign_to as $key => $value) {
            $lead_id = $value['lead_id'];
            $assign_to = $value['user_id'];
            $assign_lead = Leads::where('id', $lead_id);
            if ($assign_lead->exists()) {
                $assign_lead->update([
                    'bdm_id' => $assign_to,
                    'created_by' => Auth::user()->id,
                    'modified_by' => Auth::user()->id,
                ]);
                $to = User::where('id', $assign_to)->first()->email;
                $details = [
                    'app_url' => env('APP_URL') . '/public',
                    'email' => $to,
                    'title' => 'Lead Assigned',
                    'body' => 'Lead has been assigned to you. Please login to your account to view the lead details.'
                ];
                dispatch(new EmailJob($details));

                $logData = array(
                    'user_id' => auth()->user()->id,
                    'action_id' => $lead_id,
                    'action_to_id' => $assign_to,
                    'action_type' => 'assign',
                    'module' => 'softcall',
                    'description' =>  $lead_id . ' Lead has been assigned to ' . $assign_to,
                );

                $storeLog = SystemLogController::addLog($logData);
            }
        }
        return response()->json([
            'status' => true,
            'message' => 'Lead assigned successfully.',
        ]);
    }
}
