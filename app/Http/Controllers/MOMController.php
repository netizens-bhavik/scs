<?php

namespace App\Http\Controllers;

use App\Mail\mom_share_user_notification_mail;
use App\Models\Mom;
use App\Models\MomMode;
use App\Models\User;
use App\Models\Client;
use App\Models\MomJob;
use Illuminate\Http\Request;
use App\Models\ContactPerson;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\SystemLogController;

class MOMController extends Controller
{
    //
    public function __construct()
    {
        $this->finalArray = [];
    }

    public function getMomList(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;

        $MOMController = new MOMController;
        $hierarchy_users = $MOMController->getHirarchyUser($user_id);
        $hierarchy_users[] = $user_id;

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

        $records = DB::table('moms')
            ->leftJoin('clients', 'moms.client_id', '=', 'clients.id')
            ->leftJoin('users', 'moms.shared_user_by', '=', 'users.id')
            ->leftJoin('mom_modes', 'moms.mode_of_meeting', '=', 'mom_modes.id')
            ->where('moms.is_deleted', '=', null)
            ->where(function ($query) use ($searchValue) {
                $query->where('clients.company_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('contact_person', 'like', '%' . $searchValue . '%')
                    ->orWhere('minutes_of_meeting', 'like', '%' . $searchValue . '%')
                    ->orWhere('bde_feedback', 'like', '%' . $searchValue . '%')
                    ->orWhere('mode_name', 'like', '%' . $searchValue . '%');
            })
            ->where(function ($query) use ($hierarchy_users, $user_id) {
                $query->whereIn('clients.manage_by', $hierarchy_users)
                    ->orWhere('moms.share_user_id', '=', $user_id);
            })
            ->select('moms.*', 'clients.company_name', 'clients.manage_by', 'users.name as shared_user_name', 'mom_modes.mode_name as mode_of_meeting_name')
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get()->toArray();

        $records = json_decode(json_encode($records), true);

        // Total records
        $totalRecords = DB::table('moms')
            ->leftJoin('clients', 'moms.client_id', '=', 'clients.id')
            ->leftJoin('users', 'moms.shared_user_by', '=', 'users.id')
            ->where('moms.is_deleted', '=', null)
            ->where(function ($query) use ($hierarchy_users, $user_id) {
                $query->whereIn('clients.manage_by', $hierarchy_users)
                    ->orWhere('moms.share_user_id', '=', $user_id);
            })
            ->select('moms.*', 'clients.company_name', 'clients.manage_by')
            ->count();
        $totalRecordswithFilter = DB::table('moms')
            ->leftJoin('clients', 'moms.client_id', '=', 'clients.id')
            ->leftJoin('users', 'moms.shared_user_by', '=', 'users.id')
            ->leftJoin('mom_modes', 'moms.mode_of_meeting', '=', 'mom_modes.id')
            ->where('moms.is_deleted', '=', null)
            ->where(function ($query) use ($searchValue) {
                $query->where('clients.company_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('contact_person', 'like', '%' . $searchValue . '%')
                    ->orWhere('minutes_of_meeting', 'like', '%' . $searchValue . '%')
                    ->orWhere('bde_feedback', 'like', '%' . $searchValue . '%')
                    ->orWhere('mode_name', 'like', '%' . $searchValue . '%');
            })
            ->where(function ($query) use ($hierarchy_users, $user_id) {
                $query->whereIn('clients.manage_by', $hierarchy_users)
                    ->orWhere('moms.share_user_id', '=', $user_id);
            })
            ->select('moms.*', 'clients.company_name', 'clients.manage_by')
            ->count();

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

            $shared_user_check = DB::table('moms')
                ->where('moms.id', '=', $record['id'])
                ->where('moms.share_user_id', '=', $user_id)
                ->select('moms.*')
                ->count();

            if ($shared_user_check && !$id_admin) {
                $action_btn .= '<span class="badge bg-secondary">Shared</span>';
            } else {

                // add permission condition
                if ($user_edit_permissions) {
                    $action_btn .= '<a href="javascript:void(0);" class="btn btn btn-icon btn-outline-primary edit_mom" data-id="' . $record['id'] . '" title="Edit">
                <i class="bx bx-edit-alt"></i></a>&nbsp;&nbsp;&nbsp;';
                }

                // add permission condition
                if ($user_delete_permissions) {
                    $action_btn .= '<a href="javascript:void(0);" class="btn btn btn-icon btn-outline-primary delete_mom" data-id="' . $record['id'] . '" data-name="' . $record['company_name'] . '" title="Delete">
                <i class="bx bx-trash-alt"></i></a>';
                }

                if (!$user_edit_permissions && !$user_delete_permissions) {
                    $action_btn .= '<span class="badge bg-secondary">No Permission</span>';
                }
            }


            $momType = '';
            switch ($record['mom_type']) {
                case '1':
                    $momType = 'Follow Up';
                    break;
                case '2':
                    $momType = 'Meeting';
                    break;
                case '3':
                    $momType = 'Requirement Discussion';
                    break;
                case '4':
                    $momType = 'Close';
                    break;

                default:
                    break;
            }
            $dataArr[] = array(
                "id" => $i++,
                "company_name" => $record['company_name'],
                "meeting_date" => date("d/m/Y", strtotime($record['meeting_date'])),
                "contact_person" => $record['contact_person'],
                "minutes_of_meeting" => $record['minutes_of_meeting'],
                "bde_feedback" => $record['bde_feedback'],
                "mode_of_meeting_name" => $record['mode_of_meeting_name'],
                "mom_type" => $momType,
                "action" => $action_btn,
                "shared_user_name" => $record['shared_user_name'] ?? '-',
            );
        }

        $logData = array(
            'user_id' => auth()->user()->id,
            'action_type' => 'view',
            'module' => 'mom',
            'description' => 'Viewed MOM List',
        );

        $storeLog = SystemLogController::addLog($logData);

        return response()->json([
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecordswithFilter,
            "data" => $dataArr,
        ]);
    }

    function manageMom(Request $request)
    {
        $dashboard_country_id = (int)$request->dashboard_country_id ?? '';
        $dashboard_user_id = (int)$request->dashboard_user_id ?? '';
        $dashboard_flag = $request->dashboard_user_id ?? false;

        $mom = [];
        if (isset($request->id) && !empty($request->id)) {
            $mom = Mom::find($request->id)->toArray();
            $mom['jobs'] = MomJob::where('mom_id', $request->id)->where('is_deleted', null)->get()->toArray();
            $mom['contactPersons'] = ContactPerson::where('client_id', $mom['client_id'])->where('is_deleted', null)->get()->toArray();
        }

        $user = Auth::user();
        $this->finalArray = [];
        $hirarchyUsers = $this->getHirarchyUser($user->id);
        $hirarchyUsers[] = $user->id;

        //we can get company name from clients
        $clients = Client::whereIn('manage_by', $hirarchyUsers)
            ->get()->toArray();

        $users = User::with('roles')
            ->with('country')
            ->where('is_deleted', null)
            ->get()->toArray();

        $meeting_modes = MomMode::where('is_deleted', null)->get()->toArray();


        if($dashboard_flag)
        {
            $data = [];
            $data['client_id'] = $mom['client_id'] ?? '';
            $data['contact_person'] = $mom['contact_person'] ?? '';
            $data['contactPersons'] = $mom['contactPersons'] ?? '';
            $data['meeting_date'] =  date('Y-m-d');
            $data['follow_up_id'] = $mom['id'] ?? '';

        } else {
            $data = $mom;
        }

        return view('backend.mom.modals.mom_master_form', [
            'clients' => $clients,
            'users' => $users,
            'mom' => $data,
            'meeting_modes' => $meeting_modes,
            'dashboard_country_id' => $dashboard_country_id,
            'dashboard_user_id' => $dashboard_user_id,
            'dashboard_flag' => $dashboard_flag,
        ]);
    }

    function getContactPersons(Request $request)
    {
        $contactPerson = ContactPerson::where('client_id', $request->id)->where('is_deleted', null)->get()->toArray();

        return response()->json([
            'status' => true,
            'contactPerson' => $contactPerson,
        ]);
    }

    function deleteMomJob(Request $request)
    {
        $user = Auth::user();

        if (MomJob::find($request->id)) {
            $data = [
                'is_deleted' => '1',
                'modified_by' => $user->id,
            ];
            MomJob::whereId($request->id)->update($data);

            return response()->json([
                "status" => true,
                "message" => "MOM deleted successfully"
            ]);
        }

        return response()->json([
            "status" => false,
            "message" => "Something went wrong.Try again after sometime"
        ]);
    }

    function saveMOM(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'meeting_date' => 'required',
            'client_id' => 'required',
            'contact_person' => 'required',
            'minutes_of_meeting' => 'required',
            'bde_feedback' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'validation Error'
            ]);
        } else {
            $user = Auth::user();
            if (Mom::find($request->id)) {
                $momData = [
                    'client_id' => $request->client_id,
                    'meeting_date' => $request->meeting_date,
                    //'company_name' => $request->company_name,
                    'contact_person' => $request->contact_person,
                    'minutes_of_meeting' => $request->minutes_of_meeting,
                    'bde_feedback' => $request->bde_feedback,
                    'mom_type' => $request->mom_type,
                    'followup' => $request->followup,
                    'share_user_id' => $request->share_user_id,
                    'mode_of_meeting' => $request->meeting_mode_id,
                    'next_followup_date' => $request->next_followup_date,
                    'next_followup_time' => $request->next_followup_time,
                    'client_status' => $request->client_status,
                    'modified_by' => $user->id,
                ];

                $shared_email_check = Mom::where('id', $request->id)->where('share_user_id', $request->share_user_id)->first();
                if ($shared_email_check) {
                    Mom::whereId($request->id)->update($momData);
                } else {
                    Mom::whereId($request->id)->update(['is_shared_user_notified' => '0']);
                    Mom::whereId($request->id)->update($momData);
                }

                $logData = array(
                    'user_id' => auth()->user()->id,
                    'action_id' => $request->id,
                    'action_to_id' => $request->client_id,
                    'action_type' => 'update',
                    'module' => 'mom',
                    'description' => 'Updated MOM',
                );

                $storeLog = SystemLogController::addLog($logData);

                if ($request->share_user_id != null && $request->share_user_id != '') {

                    $shareUser = DB::table('moms as m')
                        ->leftJoin('users as u', 'u.id', '=', 'm.share_user_id')
                        ->where('m.id', '=', $request->id)
                        ->where('m.share_user_id', $request->share_user_id)
                        ->where('m.is_shared_user_notified', '=', 0)
                        ->where('m.is_deleted', null)
                        ->select('u.email', 'u.name')
                        ->first();

                    if ($shareUser) {
                        //send mail to share user
                        $data = array(
                            'name' => $shareUser->name,
                            'email' => $shareUser->email,
                            'subject' => 'MOM Shared',
                            'bodyMessage' => 'MOM has been shared with you. Please login to your account to view MOM.',
                            'url' => url('/'),
                            'button_text' => 'Login',
                        );

                        $email = new mom_share_user_notification_mail($data);
                        Mail::to($shareUser->email)->send($email);

                        Mom::whereId($request->id)->update(['is_shared_user_notified' => '1', 'shared_user_by' => $user->id]);
//                    \Mail::to($shareUser->email)->send(new \App\Mail\mom_share_user_notification_mail($data));

                    }

                }

                if (!empty($request->job_category[0])) {
                    for ($i = 0; $i < sizeof($request->job_category); $i++) {
                        if (isset($request->job_id[$i]) && !empty($request->job_id[$i])) {
                            $jobData = [
                                'mom_id' => $request->id,
                                'j_date' => date('Y-m-d', strtotime($request->job_date[$i])),
                                'job_category' => $request->job_category[$i],
                                'quantity' => $request->quantity[$i],
                                'job_description' => $request->job_description[$i],
                                'modified_by' => $user->id,
                            ];
                            MomJob::whereId($request->job_id[$i])->update($jobData);
                            $logData = array(
                                'user_id' => auth()->user()->id,
                                'action_id' => $request->job_id[$i],
                                'action_to_id' => $request->id,
                                'action_type' => 'update',
                                'module' => 'mom',
                                'description' => 'Updated MOM Job',
                            );

                            $storeLog = SystemLogController::addLog($logData);
                        } else {
                            $jobResponse = new MomJob();
                            $jobResponse->mom_id = $request->id;
                            $jobResponse->j_date = date('Y-m-d', strtotime($request->job_date[$i]));
                            $jobResponse->quantity = $request->quantity[$i];
                            $jobResponse->job_category = $request->job_category[$i];
                            $jobResponse->job_description = $request->job_description[$i];
                            $jobResponse->created_by = $user->id;
                            $jobResponse->save();

                            $logData = array(
                                'user_id' => auth()->user()->id,
                                'action_id' => $jobResponse->id,
                                'action_to_id' => $request->id,
                                'action_type' => 'create',
                                'module' => 'mom',
                                'description' => 'Added MOM Job',
                            );

                            $storeLog = SystemLogController::addLog($logData);
                        }
                    }
                }

                return response()->json([
                    "status" => true,
                    "message" => "MOM updated successfully"
                ]);
            } else {
                $momResponse = Mom::create([
                    'client_id' => $request->client_id,
                    'meeting_date' => $request->meeting_date,
                    //'company_name' => $request->company_name,
                    'contact_person' => $request->contact_person,
                    'minutes_of_meeting' => $request->minutes_of_meeting,
                    'bde_feedback' => $request->bde_feedback,
                    'mom_type' => $request->mom_type,
                    'followup' => $request->followup,
                    'share_user_id' => $request->share_user_id,
                    'mode_of_meeting' => $request->meeting_mode_id,
                    'next_followup_date' => $request->next_followup_date,
                    'next_followup_time' => $request->next_followup_time,
                    'client_status' => $request->client_status,
                    'created_by' => $user->id,
                ]);

                if (isset($momResponse->id) && !empty($momResponse->id)) {
                    if(isset($request->follow_up_id) && $request->follow_up_id != '')
                    {
                        $follow_up_data = [
                            'followup_status' => $momResponse->id
                        ];
                        Mom::whereId($request->follow_up_id)->update($follow_up_data);
                    }
                }

                if ($request->share_user_id != null && $request->share_user_id != '') {
                    $shareUser = DB::table('moms as m')
                        ->leftJoin('users as u', 'u.id', '=', 'm.share_user_id')
                        ->where('m.id', '=', $momResponse->id)
                        ->where('m.share_user_id', $request->share_user_id)
                        ->where('m.is_shared_user_notified', '=', 0)
                        ->where('m.is_deleted', null)
                        ->select('u.email', 'u.name')
                        ->first();
                    if ($shareUser) {
                        //send mail to share user
                        $data = array(
                            'name' => $shareUser->name,
                            'email' => $shareUser->email,
                            'subject' => 'MOM Shared',
                            'bodyMessage' => 'MOM has been shared with you. Please login to your account to view MOM.',
                            'url' => url('/'),
                            'button_text' => 'Login',
                        );
                        $email = new mom_share_user_notification_mail($data);
                        Mail::to($shareUser->email)->send($email);
                        Mom::whereId($momResponse->id)->update(['is_shared_user_notified' => '1', 'shared_user_by' => $user->id]);
                    }
                }


                $logData = array(
                    'user_id' => auth()->user()->id,
                    'action_id' => $momResponse->id,
                    'action_to_id' => $request->client_id,
                    'action_type' => 'create',
                    'module' => 'mom',
                    'description' => 'Added MOM',
                );

                $storeLog = SystemLogController::addLog($logData);


                if (!empty($request->job_category[0])) {
                    if (isset($momResponse->id) && !empty($momResponse->id)) {
                        for ($i = 0; $i < sizeof($request->job_category); $i++) {
                            if (isset($request->job_id[$i]) && !empty($request->job_id[$i])) {
                                $jobData = [
                                    'mom_id' => $momResponse->id,
                                    'j_date' => date('Y-m-d', strtotime($request->job_date[$i])),
                                    'job_category' => $request->job_category[$i],
                                    'quantity' => $request->quantity[$i],
                                    'job_description' => $request->job_description[$i],
                                    'modified_by' => $user->id,
                                ];
                                MomJob::whereId($request->job_id[$i])->update($jobData);
                                $logData = array(
                                    'user_id' => auth()->user()->id,
                                    'action_id' => $request->job_id[$i],
                                    'action_to_id' => $momResponse->id,
                                    'action_type' => 'update',
                                    'module' => 'mom',
                                    'description' => 'Updated MOM Job',
                                );

                                $storeLog = SystemLogController::addLog($logData);

                            } else {
                                $jobResponse = new MomJob();
                                $jobResponse->mom_id = $momResponse->id;
                                $jobResponse->j_date = date('Y-m-d', strtotime($request->job_date[$i]));
                                $jobResponse->quantity = $request->quantity[$i];
                                $jobResponse->job_category = $request->job_category[$i];
                                $jobResponse->job_description = $request->job_description[$i];
                                $jobResponse->created_by = $user->id;
                                $jobResponse->save();

                                $logData = array(
                                    'user_id' => auth()->user()->id,
                                    'action_id' => $jobResponse->id,
                                    'action_to_id' => $momResponse->id,
                                    'action_type' => 'create',
                                    'module' => 'mom',
                                    'description' => 'Added MOM Job',
                                );

                                $storeLog = SystemLogController::addLog($logData);
                            }
                        }
                    }
                }

                return response()->json([
                    "status" => true,
                    "message" => "MOM inserted successfully"
                ]);
            }

            return response()->json([
                "status" => false,
                "message" => "Something went wrong.Try again after sometime"
            ]);
        }
    }

    function deleteMOM(Request $request)
    {
        $user = Auth::user();

        if (Mom::find($request->id)) {
            $data = [
                'is_deleted' => '1',
                'modified_by' => $user->id,
            ];
            Mom::whereId($request->id)->update($data);

            $logData = array(
                'user_id' => auth()->user()->id,
                'action_id' => $request->id,
                'action_type' => 'delete',
                'module' => 'mom',
                'description' => 'Deleted MOM',
            );

            $storeLog = SystemLogController::addLog($logData);

            return response()->json([
                "status" => true,
                "message" => "MOM deleted successfully"
            ]);
        }

        return response()->json([
            "status" => false,
            "message" => "Something went wrong.Try again after sometime"
        ]);
    }

    function getJobClientList(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndexArr = $request->get('order');
        $columnNameArr = $request->get('columns');
        $orderArr = $request->get('order');
        $searchArr = $request->get('search');

        $columnIndex = isset($columnIndexArr[0]['column']) ? $columnIndexArr[0]['column'] : ''; // Column index
        $columnName = !empty($columnIndex) ? $columnNameArr[$columnIndex]['data'] : 'moms.id'; // Column name
        $columnSortOrder = !empty($columnIndex) ? $orderArr[0]['dir'] : 'DESC'; // asc or desc
        $searchValue = $searchArr['value']; // Search value

        // Total records
        $totalRecords = MomJob::select('count(*) as allcount')->where('is_deleted', null)->count();
        $totalRecordswithFilter = MomJob::select('count(*) as allcount')->count();

        $user = Auth::user();
        $this->finalArray = [];
        $hirarchyUsers = $this->getHirarchyUser($user->id);
        $hirarchyUsers[] = $user->id;

        // Get records, also we have included search filter as well
        $records = DB::table('moms')
            ->join('clients', 'moms.client_id', '=', 'clients.id')
            ->where('moms.is_deleted', '=', null)
            ->where('moms.mom_type', '=', '3')
            ->whereIn('clients.manage_by', $hirarchyUsers)
            ->where(function ($query) use ($searchValue) {
                $query->where('clients.company_name', 'like', '%' . $searchValue . '%');
            })
            ->select('moms.id as mom_id', 'clients.company_name', 'moms.meeting_date')
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get()->toArray();

        $records = json_decode(json_encode($records), true);

        // echo "<pre>"; print_r($records);die;

        /* $user = Auth::user();
        $user_permissions = $user->getAllPermissions()->pluck('name')->toArray();
        $user_edit_permissions_check = '';
        $user_delete_permissions_check = '';
        $user_edit_permissions = in_array($user_edit_permissions_check, $user_permissions);
        $user_delete_permissions = in_array($user_delete_permissions_check, $user_permissions); */

        $dataArr = [];
        $i = $start + 1;
        foreach ($records as $record) {
            $jobCount = MomJob::where('is_deleted', null)->where('mom_id', $record['mom_id'])->count();

            //add change job status permission
            $dataArr[] = array(
                "id" => $i++,
                "company_name" => $record['company_name'],
                "meeting_date" => date("d/m/Y", strtotime($record['meeting_date'])),
                "total_jobs" => $jobCount,

                "action" => '<a href="' . url('/jobs/' . Crypt::encryptString($record['mom_id'])) . '" class="btn btn-primary"><i class="bx bxs-chevrons-right"></i></a>',
            );
        }

        return response()->json([
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecordswithFilter,
            "data" => $dataArr,
        ]);
    }


    public function jobs($momId)
    {
        return view('backend.mom.job_master', ['momId' => $momId]);
    }

    function getJobList(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndexArr = $request->get('order');
        $columnNameArr = $request->get('columns');
        $orderArr = $request->get('order');
        $searchArr = $request->get('search');

        $columnIndex = isset($columnIndexArr[0]['column']) ? $columnIndexArr[0]['column'] : ''; // Column index
        $columnName = !empty($columnIndex) ? $columnNameArr[$columnIndex]['data'] : 'mom_jobs.created_at'; // Column name
        $columnSortOrder = !empty($columnIndex) ? $orderArr[0]['dir'] : 'DESC'; // asc or desc
        $searchValue = $searchArr['value']; // Search value

        // Total records
        $totalRecords = MomJob::select('count(*) as allcount')->where('is_deleted', null)->count();
        $totalRecordswithFilter = MomJob::select('count(*) as allcount')->count();

        $user = Auth::user();

        // Get records, also we have included search filter as well
        $records = DB::table('mom_jobs')
            ->where('mom_jobs.is_deleted', '=', null)
            ->join('moms', 'mom_jobs.mom_id', '=', 'moms.id')
            ->where('mom_jobs.mom_id', '=', Crypt::decryptString($request->post('momId')))
            ->where(function ($query) use ($searchValue) {
                $query->where('mom_jobs.job_category', 'like', '%' . $searchValue . '%')
                    ->orWhere('mom_jobs.quantity', 'like', '%' . $searchValue . '%')
                    ->orWhere('mom_jobs.job_description', 'like', '%' . $searchValue . '%');
            })
            ->select('mom_jobs.*')
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get()->toArray();

        $records = json_decode(json_encode($records), true);

        /* echo "<pre>"; print_r($records);die; */

        /* $user = Auth::user();
        $user_permissions = $user->getAllPermissions()->pluck('name')->toArray();
        $user_edit_permissions_check = '';
        $user_delete_permissions_check = '';
        $user_edit_permissions = in_array($user_edit_permissions_check, $user_permissions);
        $user_delete_permissions = in_array($user_delete_permissions_check, $user_permissions); */

        $dataArr = [];
        $i = $start + 1;
        foreach ($records as $record) {

            //add change job status permission
            $actionDropdown = '<select class="form-control form-select job_status_drop" name="job_status[]" id="job_status" data-id="' . $record['id'] . '">
                <option value="">Select</option>
                <option value="1" ' . ($record['job_status'] == 1 ? 'selected' : '') . '>Ongoing</option>
                <option value="2" ' . ($record['job_status'] == 2 ? 'selected' : '') . '>On Hold</option>
                <option value="3" ' . ($record['job_status'] == 3 ? 'selected' : '') . '>Completed</option>
                <option value="4" ' . ($record['job_status'] == 4 ? 'selected' : '') . '>Cancel</option>
                </select>';

            $dataArr[] = array(
                "id" => $i++,
                "j_date" => date("d/m/Y", strtotime($record['j_date'])),
                "job_category" => $record['job_category'],
                "quantity" => $record['quantity'],
                "job_description" => $record['job_description'],
                "action" => $actionDropdown,
            );
        }

        return response()->json([
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecordswithFilter,
            "data" => $dataArr,
        ]);
    }

    function changeJobStatus(Request $request)
    {
        $jobStatus = $request->job_status;

        foreach ($jobStatus as $key => $value) {
            $job = MomJob::where('id', $value['jobId']);
            if ($job->exists()) {
                $job->update([
                    'job_status' => $value['jobStatus'],
                    'status_date' => date('Y-m-d'),
                    'modified_by' => Auth::user()->id,
                ]);
            }
        }
        return response()->json([
            'status' => true,
            'message' => 'Job status changed successfully.',
        ]);
    }

    public function getHirarchyUser($userId)
    {
        //$result = [];
        $users = User::where('reporting_user_id', $userId)->get()->toArray();
        if (!empty($users)) {
            foreach ($users as $key => $value) {
                $this->finalArray[] = $value['id'];
                $this->getHirarchyUser($value['id']);
            }
        }
        return $this->finalArray;
    }
}
