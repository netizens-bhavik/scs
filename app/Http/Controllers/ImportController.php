<?php

namespace App\Http\Controllers;
use DateTime;

use App\Models\City;
use App\Models\Country;
use App\Models\Industry;
use App\Models\TempLeads;
use Illuminate\Http\Request;
use App\Imports\TempLeadsImport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Spatie\SimpleExcel\SimpleExcelReader;
use App\Http\Controllers\SystemLogController;


class ImportController extends Controller
{
    // public function importTempLeads(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'lead_file'  => 'required|mimes:xls,xlsx,csv'
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => false,
    //             'errors' => $validator->errors(),
    //         ]);
    //     }

    //     /* $path = $request->file('lead_file')->getRealPath();
    //     print_r($path); */
    //     $path1 = $request->file('lead_file')->store('temp');
    //     $path = storage_path('app').'/'.$path1;

    //     $import = new TempLeadsImport;
    //     //Excel::selectSheetsByIndex(0)->load();
    //     $exportArray = Excel::import($import, $path);

    //     Session::put('excelData', $import->data);

    //     $importedData = Session::get('excelData');
    //     $added = 0;
    //     $notAdded = 0;
    //     foreach($importedData as $key => $value) {
    //         if($key != 0) {
    //             if(!empty($value[0]) || !empty($value[1]) || !empty($value[11]) || !empty($value[12]) ) {
    //                 if($value[15] == "Added Successfully" || $value[15] == "Updated Successfully") {
    //                     $added++;
    //                 } else {
    //                     $notAdded++;
    //                 }
    //             }
    //         }
    //     }

    //     $msg = '';
    //     if(!empty($added))
    //         $msg .= "<p>". $added . " record(s) imported successfully.</p>";

    //     if(!empty($notAdded))
    //         $msg .= "<p>" . $notAdded . " record(s) gives error. To check the logs click download leatest file</p>";

    //     return response()->json([
    //         "status" => true,
    //         "message" => $msg
    //     ]);

    // }

    public $exportArray = [];

    public function importTempLeads(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lead_file'  => 'required|mimes:xls,xlsx,csv'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

        try {
            $path1 = $request->file('lead_file')->store('temp');
            $path = storage_path('app').'/'.$path1;

            $header = SimpleExcelReader::create($path)->getHeaders();

            //print_r($header);

            $headererFlag = true;

            if(isset($header[0]) && preg_replace("/\s+/", "", ucwords(strtolower($header[0]))) != "CountryName") {
                $headererFlag = false;
            }
            if(isset($header[1]) && preg_replace("/\s+/", "", ucwords(strtolower($header[1]))) != "CompanyName") {
                $headererFlag = false;
            }
            if(isset($header[2]) && preg_replace("/\s+/", "", ucwords(strtolower($header[2]))) != "IndustryName") {
                $headererFlag = false;
            }
            if(isset($header[3]) && preg_replace("/\s+/", "", ucwords(strtolower($header[3]))) != "ContactPersonName") {
                $headererFlag = false;
            }
            if(isset($header[4]) && preg_replace("/\s+/", "", ucwords(strtolower($header[4]))) != "Department") {
                $headererFlag = false;
            }
            if(isset($header[5]) && preg_replace("/\s+/", "", ucwords(strtolower($header[5]))) != "Designation") {
                $headererFlag = false;
            }
            if(isset($header[6]) && preg_replace("/\s+/", "", ucwords(strtolower($header[6]))) != "EmailId") {
                $headererFlag = false;
            }
            if(isset($header[7]) && preg_replace("/\s+/", "", ucwords(strtolower($header[7]))) != "MobileNo") {
                $headererFlag = false;
            }
            if(isset($header[8]) && preg_replace("/\s+/", "", ucwords(strtolower($header[8]))) != "DateOfBirth") {
                $headererFlag = false;
            }
            if(isset($header[9]) && preg_replace("/\s+/", "", ucwords(strtolower($header[9]))) != "ClientAddress") {
                $headererFlag = false;
            }
            if(isset($header[10]) && preg_replace("/\s+/", "", ucwords(strtolower($header[10]))) != "ClientPostBox.No") {
                $headererFlag = false;
            }
            if(isset($header[11]) && preg_replace("/\s+/", "", ucwords(strtolower($header[11]))) != "ClientCity") {
                $headererFlag = false;
            }
            if(isset($header[12]) && preg_replace("/\s+/", "", ucwords(strtolower($header[12]))) != "PhoneNo") {
                $headererFlag = false;
            }
            if(isset($header[13]) && preg_replace("/\s+/", "", ucwords(strtolower($header[13]))) != "EmailId") {
                $headererFlag = false;
            }
            if(isset($header[14]) && preg_replace("/\s+/", "", ucwords(strtolower($header[14]))) != "WebsiteName") {
                $headererFlag = false;
            }

            if($headererFlag == false) {
                return response()->json([
                    "status" => false,
                    "message" => "Please set proper records in file as sample file."
                ]);
            }

            $rowsCount = SimpleExcelReader::create($path)->getRows()->count();

            if($rowsCount <= 0) {
                return response()->json([
                    "status" => false,
                    "message" => "Please add records in your file"
                ]);
            }


            $rows = SimpleExcelReader::create($path)->noHeaderRow()->getRows()->toArray();
            //$rows->each(function (array $row) {
            foreach($rows as $key => $row) {
                $rowStatus = '';
                $flag = true;
                $emailPattern = "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,9}$/ix";
                if($key != 0) {

                    if (!empty($row)) {
                        if (!empty($row[0]) || !empty($row[1]) || !empty($row[11]) || !empty($row[12])) {
                            if (empty($row[0])) {
                                $flag = false;
                                $rowStatus = 'Country is required.';
                            }
                            if (empty($row[1])) {
                                $flag = false;
                                $rowStatus .= ' Company name is required.';
                            }
                            if (empty($row[11])) {
                                $flag = false;
                                $rowStatus .= ' Client city is required.';
                            }
                            if (empty($row[12])) {
                                $flag = false;
                                $rowStatus .= ' Phone no is required.';
                            }

                            if (!empty($row[6])) {
                                if (!preg_match($emailPattern, $row[6])) {
                                    $flag = false;
                                    $rowStatus .= ' Invalid Email.';
                                }
                            }

                            if (!empty($row[13])) {
                                if (!preg_match($emailPattern, $row[13])) {
                                    $flag = false;
                                    $rowStatus .= ' Invalid Email.';
                                }
                            }

                            if (!empty($row[7])) {
                                if (!preg_match('/^[0-9]*$/', $row[7])) {
                                    $flag = false;
                                    $rowStatus .= ' Invalid Mobile No.';
                                }
                            }

                            if (!empty($row[12])) {
                                if (!preg_match('/^[0-9]*$/', $row[12])) {
                                    $flag = false;
                                    $rowStatus .= ' Invalid Phone No.';
                                }
                            }

                            $countryName = preg_replace("/\s+/", " ", ucwords(strtolower($row[0])));
                            $country = Country::where('country_name', $countryName)->where('is_deleted', null)->first();
                            if ($country) {
                                $country = $country->toArray();
                                $cityName = preg_replace("/\s+/", " ", ucwords(strtolower($row[11])));
                                $city = City::where('city_name', $cityName)->where('is_deleted', null)->where('country_id', $country['id'])->first();
                                if ($city) {
                                    $city = $city->toArray();
                                } else {
                                    $flag = false;
                                    $rowStatus .= ' City is not matched.';
                                }
                            } else {
                                $flag = false;
                                $rowStatus .= ' Country is not matched.';
                            }

                            $industryName = preg_replace("/\s+/", " ", ucwords(strtolower($row[2])));
                            $industry = Industry::where('industry_name', $industryName)->where('is_deleted', null)->first();
                            if ($industry) {
                                $industry = $industry->toArray();
                            } else {
                                $flag = false;
                                $rowStatus .= ' Industry is not matched.';
                            }

                            $birthDate = null;

                            try {
                                if (!empty($row[8])) {
                                    if(!empty($row[8]->format('Y-m-d'))) {
                                        $birthDate = $row[8]->format('Y-m-d');
                                        $exportBirthDate = $row[8]->format('d/m/Y');
                                        $row[8] = $exportBirthDate;

                                    } else {
                                        $flag = false;
                                        $rowStatus .= ' Invalid Birthdate.';
                                    }
                                }
                            } catch (\Throwable $th) {
                                $flag = false;
                                $rowStatus .= ' Invalid Birthdate.';
                            }

                            if ($flag == true) {
                                $tempLead = TempLeads::where('company_name', $row[1])->where('company_country_id', $country['id'])->where('company_city_id', $city['id'])->where('company_phone_no', $row[12])->where('is_deleted', null)->first();
                                if ($tempLead) {
                                    if ($tempLead->calling_status == 3) {
                                        $flag = false;
                                        $rowStatus .= ' Lead status is called.So, Data is not updated.';
                                    } else {
                                        $leadData = [
                                            'company_name' => $row[1],
                                            'company_phone_no' => $row[12],
                                            'company_country_id' => $country['id'],
                                            'company_city_id' => $city['id'],
                                            'industry_id' => $industry['id'],
                                            'company_email' => $row[13],
                                            'department' => $row[4],
                                            'designation' => $row[5],
                                            'contact_person_name' => $row[3],
                                            'contact_person_email' => $row[6],
                                            'contact_person_phone' => $row[7],
                                            'dob' => $birthDate,
                                            'address' => $row[9],
                                            'post_box_no' => $row[10],
                                            'cp_country_id' => '',
                                            'cp_city_id' => '',
                                            'website_name' => $row[14],
                                            'imported_by' => Auth::user()->id,
                                            'created_by' => Auth::user()->id
                                        ];
                                        $response = TempLeads::whereId($tempLead->id)->update($leadData);

                                        $logData = array(
                                            'user_id' => auth()->user()->id,
                                            'action_id' => $tempLead->id,
                                            'action_type' => 'update',
                                            'module' => 'import',
                                            'description' =>  'Lead updated by ' . auth()->user()->name . ' from import.',
                                        );

                                        $storeLog = SystemLogController::addLog($logData);


                                        $rowStatus .= ($response) ? 'Updated Successfully' : 'Error while updating lead';
                                    }
                                } else {
                                    $response = TempLeads::create([
                                        'company_name' => $row[1],
                                        'company_phone_no' => $row[12],
                                        'company_country_id' => $country['id'],
                                        'company_city_id' => $city['id'],
                                        'industry_id' => $industry['id'],
                                        'company_email' => $row[13],
                                        'department' => $row[4],
                                        'designation' => $row[5],
                                        'contact_person_name' => $row[3],
                                        'contact_person_email' => $row[6],
                                        'contact_person_phone' => $row[7],
                                        'dob' => $birthDate,
                                        'address' => $row[9],
                                        'post_box_no' => $row[10],
                                        'cp_country_id' => '',
                                        'cp_city_id' => '',
                                        'website_name' => $row[14],
                                        'imported_by' => Auth::user()->id,
                                        'created_by' => Auth::user()->id
                                    ]);

                                    $logData = array(
                                        'user_id' => auth()->user()->id,
                                        'action_id' => $response->id,
                                        'action_type' => 'import',
                                        'module' => 'import',
                                        'description' =>  'Imported a lead',
                                    );

                                    $storeLog = SystemLogController::addLog($logData);

                                    $rowStatus .= ($response) ? 'Added Successfully' : 'Error while adding lead';
                                }
                            }
                        }
                    }
                }

                $row[15] = $rowStatus;
                $this->exportArray[] = $row;
            }

            Session::put('excelData', $this->exportArray);

            $importedData = Session::get('excelData');
            $added = 0;
            $notAdded = 0;
            foreach($importedData as $key => $value) {
                if($key != 0) {
                    if(!empty($value[0]) || !empty($value[1]) || !empty($value[11]) || !empty($value[12]) ) {
                        if($value[15] == "Added Successfully" || $value[15] == "Updated Successfully") {
                            $added++;
                        } else {
                            $notAdded++;
                        }
                    }
                }
            }

            $msg = '';
            if(!empty($added))
                $msg .= "<p>". $added . " record(s) imported successfully.</p>";

            if(!empty($notAdded))
                $msg .= "<p>" . $notAdded . " record(s) gives error. To check the logs click download leatest file</p>";

            return response()->json([
                "status" => true,
                "message" => $msg
            ]);

        } catch (\Throwable $th) {
            throw $th;
            //echo $th;
        }
    }
}
