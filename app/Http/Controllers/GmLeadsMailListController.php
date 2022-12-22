<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\GmLeadsMailList;
use App\Mail\AssignLeadListCron;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\SystemLogController;

class GmLeadsMailListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GmLeadsMailList  $gmLeadsMailList
     * @return \Illuminate\Http\Response
     */
    public function show(GmLeadsMailList $gmLeadsMailList)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GmLeadsMailList  $gmLeadsMailList
     * @return \Illuminate\Http\Response
     */
    public function edit(GmLeadsMailList $gmLeadsMailList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GmLeadsMailList  $gmLeadsMailList
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GmLeadsMailList $gmLeadsMailList)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GmLeadsMailList  $gmLeadsMailList
     * @return \Illuminate\Http\Response
     */
    public function destroy(GmLeadsMailList $gmLeadsMailList)
    {
        //
    }

    public function sendMail()
    {
        $mailList = GmLeadsMailList::where('is_mail_sent', false)->get()->toArray();

        if (!empty($mailList)) {

            $users = User::whereHas('permissions', function ($q) {
                $q->where('name', 'soft_call_assign');
            })->get()->toArray();

            foreach ($users as $user) {
                $details = [
                    'title' => 'New Leads Available',
                    'body' => 'New Leads Available',
                    'mailList' => $mailList,
                    'user_name' => $user['name'],
                    'app_url' => env('APP_URL') . 'public',
                ];
                $email = $user['email'];
                Mail::to($email)->send(new AssignLeadListCron($details));
            }

            foreach ($mailList as $mail) {
                $id = $mail['id'];
                $mailList = GmLeadsMailList::find($id);
                $mailList->is_mail_sent = true;
                $mailList->save();

                $logData = array(
                    'user_id' => auth()->user()->id,
                    'action_id' => $id,
                    'action_type' => 'send_mail',
                    'module' => 'mailer',
                    'description' =>  'Mail sent to ' . $email,
                );

                $storeLog = SystemLogController::addLog($logData);
            }
        } else {
            echo "No new leads available";
        }
    }

    public function sendMailToUser($mail_table_data_row)
    {
    }
    public function sendMailToAll()
    {
    }

    public function sendMailToAllNotSent()
    {
        $mailList = GmLeadsMailList::where('is_mail_sent', false)->get();
        //dd($mailList);
        // foreach ($mailList as $mail) {
        //     $mail->is_mail_sent = true;
        //     $mail->save();
        // }
    }

    public function create_mail_send_list($lead_id, $name, $country)
    {
        $mailList = new GmLeadsMailList();
        $mailList->lead_id = $lead_id;
        $mailList->name = $name;
        $mailList->country = $country;
        $mailList->save();

        $logData = array(
            'user_id' => auth()->user()->id,
            'action_id' => $mailList->id,
            'action_type' => 'create',
            'module' => 'mailer',
            'description' => 'Mail list created for ' . $name,
        );

        $storeLog = SystemLogController::addLog($logData);
    }
}
