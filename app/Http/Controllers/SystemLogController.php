<?php

namespace App\Http\Controllers;

use App\Models\SystemLog;
use Illuminate\Http\Request;

class SystemLogController extends Controller
{
    public function addLog($logData)
    {
        $log = new SystemLog();
        if (isset($logData['user_id'])) {
            $log->user_id = $logData['user_id'];
        }
        if (isset($logData['action_id'])) {
            $log->action_id = $logData['action_id'];
        }
        if (isset($logData['action_to_id'])) {
            $log->action_to_id = $logData['action_to_id'];
        }
        if (isset($logData['call_type'])) {
            $log->call_type = $logData['call_type'];
        }
        if (isset($logData['call_status'])) {
            $log->call_status = $logData['call_status'];
        }
        if (isset($logData['module'])) {
            $log->module = $logData['module'];
        }
        if (isset($logData['action_type'])) {
            $log->action_type = $logData['action_type'];
        }
        if (isset($logData['description'])) {
            $log->description = $logData['description'];
        }
        if (isset($logData['user_id'])) {
            $log->save();
        }
    }
}
