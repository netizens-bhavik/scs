<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\NotesMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReminderMailNotification;
use Illuminate\Support\Facades\Validator;

class NotesMasterController extends Controller
{
    public function manage_notes(Request $request)
    {
        $notes = [];
        if (isset($request->id) && !empty($request->id)) {
            $notes = NotesMaster::find($request->id);
        }
        return view('backend.notes_modal', ['notes' => $notes]);
    }

    public function save_notes(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'reminder_at' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ]);
        } else {
            $title = $request->input('title');
            $description = $request->input('description');
            $reminder_at = date('Y-m-d H:i:s', strtotime($request->input('reminder_at')));
            $notes_id = $request->input('id');

            if ($reminder_at < date('Y-m-d H:i:s')) {
                return response()->json([
                    'status' => false,
                    'message' => 'Reminder date should be greater than current date'
                ]);
            } else {
                if ($notes_id != '') {
                    $notes = NotesMaster::find($notes_id);
                    $notes->title = $title;
                    $notes->description = $description;
                    $notes->reminder_at = $reminder_at;
                    $notes->modified_by = Auth::user()->id;
                    $notes->save();

                    return response()->json([
                        'status' => true,
                        'message' => 'Notes updated successfully'
                    ]);
                } else {
                    $notes = new NotesMaster();
                    $notes->title = $title;
                    $notes->description = $description;
                    $notes->reminder_at = $reminder_at;
                    $notes->manage_by = auth()->user()->id;
                    $notes->created_by = auth()->user()->id;
                    $notes->save();

                    return response()->json([
                        'status' => true,
                        'message' => 'Notes added successfully'
                    ]);
                }
            }
        }
    }

    public function delete_notes(Request $request)
    {
        $notes = NotesMaster::find($request->id);
        $notes->is_deleted = 1;
        $notes->save();

        return response()->json([
            'status' => true,
            'message' => 'Notes deleted successfully'
        ]);
    }


    public function get_notes_list(Request $request)
    {
        $user = auth()->user();
        $user_id = $user->id;

        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndexArr = $request->get('order');
        $columnNameArr = $request->get('columns');
        $orderArr = $request->get('order');
        $searchArr = $request->get('search');

        $columnIndex = isset($columnIndexArr[0]['column']) ? $columnIndexArr[0]['column'] : ''; // Column index
        $columnName = !empty($columnIndex) ? $columnNameArr[$columnIndex]['data'] : 'reminder_at'; // Column name
        $columnSortOrder = !empty($columnIndex) ? $orderArr[0]['dir'] : 'ASC'; // asc or desc
        $searchValue = $searchArr['value']; // Search value

        $totalRecords = NotesMaster::select('count(*) as allcount')->where('is_deleted', null)->count();
        $totalRecordswithFilter = NotesMaster::where('is_deleted', null)
            ->where('manage_by', '=', $user_id)
            ->where(function ($query) use ($searchValue) {
                $query->where('title', 'like', '%' . $searchValue . '%')
                    ->orWhere('description', 'like', '%' . $searchValue . '%')
                    ->orWhere('reminder_at', 'like', '%' . $searchValue . '%');
            })
            ->count();

        $notes_data = NotesMaster::where('is_deleted', null)
            ->where('manage_by', '=', $user_id)
            ->where(function ($query) use ($searchValue) {
                $query->where('title', 'like', '%' . $searchValue . '%')
                    ->orWhere('description', 'like', '%' . $searchValue . '%')
                    ->orWhere('reminder_at', 'like', '%' . $searchValue . '%');
            })
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get()->toArray();

        foreach ($notes_data as $key => $value) {
            if ($value['is_deleted'] == 1) {
                unset($notes_data[$key]);
            }
        }

        $records = $notes_data;

        //dd($records);

        $user = Auth::user();
        $user_permissions = $user->getAllPermissions()->pluck('name')->toArray();
        $user_edit_permissions_check = 'user_edit';
        $user_delete_permissions_check = 'user_delete';
        $user_edit_permissions = in_array($user_edit_permissions_check, $user_permissions);
        $user_delete_permissions = in_array($user_delete_permissions_check, $user_permissions);

        $dataArr = array();
        $i = 1;
        foreach ($records as $record) {
            $action_btn = '';
            if ($user_edit_permissions) {
                $action_btn .= '<button type="button" class="btn btn btn-icon btn-outline-primary edit_notes_data" value="' . $record['id'] . '">
                <i class="bx bx-edit-alt"></i></button>&nbsp;&nbsp;&nbsp;';
            }
            if ($user_delete_permissions) {
                $action_btn .= '<button type="button" class="btn btn btn-icon btn-outline-primary delete_notes_data" value="' . $record['id'] . '">
                <i class="bx bx-trash-alt"></i></button>';
            }

            if (!$user_edit_permissions && !$user_delete_permissions) {
                $action_btn .= '<span class="badge bg-secondary">No Permission</span>';
            }

            $dataArr[] = array(
                "id" => $i++,
                "title" => $record['title'],
                "description" => $record['description'],
                "reminder_at" => date("d/m/Y h:i:A", strtotime($record['reminder_at'])),
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

    public function reminder_mail()
    {
        $users = User::all()->toArray();
        foreach ($users as $user) {
            if ($user['is_deleted'] == 1) {
                continue;
            } else {
                $current_time = date('Y-m-d H:i:s.000', time());
                $start_time =  date('Y-m-d H:i:s.000', strtotime($current_time . ' + 30 minutes'));
                $end_time = date('Y-m-d H:i:s.000', strtotime($start_time . ' + 5 minutes'));

                $notes_list = NotesMaster::where('is_deleted', null)
                    ->where('manage_by', '=', $user['id'])
                    ->whereDate('reminder_at', '>=', $start_time)
                    ->whereDate('reminder_at', '<=', $end_time)
                    ->whereTime('reminder_at', '>=', $start_time)
                    ->whereTime('reminder_at', '<=', $end_time)
                    ->get()->toArray();

                if ($notes_list) {
                    foreach ($notes_list as $key => $value) {
                        $details = array(
                            'title' => $value['title'],
                            'description' => $value['description'],
                            'reminder_at' => date('Y-m-d H:i', strtotime($value['reminder_at'])),
                            'user_name' => $user['name'],
                            'app_url' => env('APP_URL') . 'public',
                        );
                        $email = $user['email'];
                        Mail::to($email)->send(new ReminderMailNotification($details));
                    }
                }
            }
        }
    }
}
