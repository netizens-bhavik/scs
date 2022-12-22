<?php

namespace App\Http\Controllers;

use App\Mail\today_followup_list_mail;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailMaster extends Controller
{

    public function todays_followups()
    {
        $all_users = User::all()->where('is_deleted', null)->toArray();
        $today_date_start_date = date('Y-m-d');
        $today_date_end_date = date('Y-m-d');
        $emails = [];

        $records = DB::table('moms')
            ->leftJoin('clients', 'moms.client_id', '=', 'clients.id')
            ->leftJoin('users', 'clients.manage_by', '=', 'users.id')
            ->where('moms.is_deleted', '=', null)
            ->where(function ($query) use ($today_date_start_date, $today_date_end_date) {
                if (!empty($today_date_start_date) && !empty($today_date_end_date)) {
                    $query->where('moms.next_followup_date', '>=', $today_date_start_date)
                        ->where('moms.next_followup_date', '<=', $today_date_end_date);
                }
            })
            ->select('moms.*', 'clients.company_name', 'clients.id as client_id', 'clients.manage_by', 'users.name as user_name', 'users.email as user_email')
            ->get()->toArray();

        //dd($records);

        if($records)
        {
            foreach ($records as $key => $value) {

                if(!array_key_exists($value->user_email, $emails))
                {
                    $emails[$value->user_email] = [];
                }
                $emails[$value->user_email][] = $value;
            }
        }

        foreach ($emails as $key => $value) {
            $data = [
                'user_name' => $value[0]->user_name,
                'user_email' => $value[0]->user_email,
                'records' => $value,
            ];
            $this->send_email($data);
        }

    }

    public function send_email($data)
    {
        $user_name = $data['user_name'];
        $user_email = $data['user_email'];
        $records = $data['records'];

        $data = [
            'user_name' => $user_name,
            'user_email' => $user_email,
            'records' => $records,
        ];

        $email = new today_followup_list_mail($data);
        Mail::to($user_email)->send($email);
    }
}
