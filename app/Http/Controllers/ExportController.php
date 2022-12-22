<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ExportTempLeads;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\SystemLogController;

class ExportController extends Controller
{
    //
    public function exportTempLeads(Request $request)
    {
        $logData = array(
            'user_id' => auth()->user()->id,
            'action_type' => 'export',
            'module' => 'import',
            'description' =>  'Exported Latest Temp Leads',
        );

        $storeLog = SystemLogController::addLog($logData);
        
        return Excel::download(new ExportTempLeads, 'leads.xlsx');
    }
}
