<?php

namespace App\Imports;

//ini_set('memory_limit', '-1');

use App\Models\City;
use App\Models\Country;
use App\Models\Industry;
use App\Models\TempLeads;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Http\Controllers\SystemLogController;

class TempLeadsImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public $data;

    public function collection(Collection $collection)
    {
        //
        $rowStatus = [];
        $exportArray = [];

        foreach ($collection as $key => $row) {
            $flag = true;
            $emailPattern = "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,9}$/ix";
            $rowStatus[$key] = ($key == 0) ? 'Status' : '';

            echo "<pre>";
            print_r($row);
            echo "</pre>";
            // if (!empty($key)) {
            //     if (!empty($row[0]) || !empty($row[1]) || !empty($row[11]) || !empty($row[12])) {
            //         //print_r($row);
            //         if (empty($row[0])) {
            //             $flag = false;
            //             $rowStatus[$key] .= ' Country is required.';
            //         }
            //         if (empty($row[1])) {
            //             $flag = false;
            //             $rowStatus[$key] .= ' Company name is required.';
            //         }
            //         if (empty($row[11])) {
            //             $flag = false;
            //             $rowStatus[$key] .= ' Client city is required.';
            //         }
            //         if (empty($row[12])) {
            //             $flag = false;
            //             $rowStatus[$key] .= ' Phone no is required.';
            //         }

            //         if (!empty($row[6])) {
            //             if (!preg_match($emailPattern, $row[6])) {
            //                 $flag = false;
            //                 $rowStatus[$key] .= ' Invalid Email.';
            //             }
            //         }

            //         if (!empty($row[13])) {
            //             if (!preg_match($emailPattern, $row[13])) {
            //                 $flag = false;
            //                 $rowStatus[$key] .= ' Invalid Email.';
            //             }
            //         }

            //         if (!empty($row[7])) {
            //             if (!preg_match('/^[0-9]*$/', $row[7])) {
            //                 $flag = false;
            //                 $rowStatus[$key] .= ' Invalid Mobile No.';
            //             }
            //         }

            //         if (!empty($row[12])) {
            //             if (!preg_match('/^[0-9]*$/', $row[12])) {
            //                 $flag = false;
            //                 $rowStatus[$key] .= ' Invalid Phone No.';
            //             }
            //         }

            //         $countryName = preg_replace("/\s+/", " ", ucwords(strtolower($row[0])));
            //         $country = Country::where('country_name', $countryName)->where('is_deleted', null)->first();
            //         if ($country) {
            //             $country = $country->toArray();
            //             $cityName = preg_replace("/\s+/", " ", ucwords(strtolower($row[11])));
            //             $city = City::where('city_name', $cityName)->where('is_deleted', null)->where('country_id', $country['id'])->first();
            //             if ($city) {
            //                 $city = $city->toArray();
            //             } else {
            //                 $flag = false;
            //                 $rowStatus[$key] .= ' City is not matched.';
            //             }
            //         } else {
            //             $flag = false;
            //             $rowStatus[$key] .= ' Country is not matched.';
            //         }

            //         $industryName = preg_replace("/\s+/", " ", ucwords(strtolower($row[2])));
            //         $industry = Industry::where('industry_name', $industryName)->where('is_deleted', null)->first();
            //         if ($industry) {
            //             $industry = $industry->toArray();
            //         } else {
            //             $flag = false;
            //             $rowStatus[$key] .= ' Industry is not matched.';
            //         }

            //         $birthDate = null;

            //         try {
            //             if (!empty($row[8])) {
            //                 $date = date_create($row[8]);
            //                 if (isset($date)) {
            //                     $birthDate = date_format($date, "Y-m-d");
            //                 } else {
            //                     $flag = false;
            //                     $rowStatus[$key] .= ' Invalid Birthdate.';
            //                 }
            //             }
            //         } catch (\Throwable $th) {
            //             $flag = false;
            //             $rowStatus[$key] .= ' Invalid Birthdate.';
            //         }

            //         if ($flag == true) {
            //             $tempLead = TempLeads::where('company_name', $row[1])->where('company_country_id', $country['id'])->where('company_city_id', $city['id'])->where('company_phone_no', $row[12])->where('is_deleted', null)->first();
            //             if ($tempLead) {
            //                 if ($tempLead->calling_status == 3) {
            //                     $flag = false;
            //                     $rowStatus[$key] .= ' Lead status is called.So, Data is not updated.';
            //                 } else {
            //                     $leadData = [
            //                         'company_name' => $row[1],
            //                         'company_phone_no' => $row[12],
            //                         'company_country_id' => $country['id'],
            //                         'company_city_id' => $city['id'],
            //                         'industry_id' => $industry['id'],
            //                         'company_email' => $row[13],
            //                         'department' => $row[4],
            //                         'designation' => $row[5],
            //                         'contact_person_name' => $row[3],
            //                         'contact_person_email' => $row[6],
            //                         'contact_person_phone' => $row[7],
            //                         'dob' => $birthDate,
            //                         'address' => $row[9],
            //                         'post_box_no' => $row[10],
            //                         'cp_country_id' => '',
            //                         'cp_city_id' => '',
            //                         'website_name' => $row[14],
            //                         'imported_by' => Auth::user()->id,
            //                         'created_by' => Auth::user()->id
            //                     ];
            //                     $response = TempLeads::whereId($tempLead->id)->update($leadData);

            //                     $logData = array(
            //                         'user_id' => auth()->user()->id,
            //                         'action_id' => $tempLead->id,
            //                         'action_type' => 'update',
            //                         'module' => 'import',
            //                         'description' =>  'Lead updated by ' . auth()->user()->name . ' from import.',
            //                     );

            //                     $storeLog = SystemLogController::addLog($logData);


            //                     $rowStatus[$key] .= ($response) ? 'Updated Successfully' : 'Error while updating lead';
            //                 }
            //             } else {
            //                 $response = TempLeads::create([
            //                     'company_name' => $row[1],
            //                     'company_phone_no' => $row[12],
            //                     'company_country_id' => $country['id'],
            //                     'company_city_id' => $city['id'],
            //                     'industry_id' => $industry['id'],
            //                     'company_email' => $row[13],
            //                     'department' => $row[4],
            //                     'designation' => $row[5],
            //                     'contact_person_name' => $row[3],
            //                     'contact_person_email' => $row[6],
            //                     'contact_person_phone' => $row[7],
            //                     'dob' => $birthDate,
            //                     'address' => $row[9],
            //                     'post_box_no' => $row[10],
            //                     'cp_country_id' => '',
            //                     'cp_city_id' => '',
            //                     'website_name' => $row[14],
            //                     'imported_by' => Auth::user()->id,
            //                     'created_by' => Auth::user()->id
            //                 ]);

            //                 $logData = array(
            //                     'user_id' => auth()->user()->id,
            //                     'action_id' => $response->id,
            //                     'action_type' => 'import',
            //                     'module' => 'import',
            //                     'description' =>  'Imported a lead',
            //                 );

            //                 $storeLog = SystemLogController::addLog($logData);

            //                 $rowStatus[$key] .= ($response) ? 'Added Successfully' : 'Error while adding lead';
            //             }
            //         }
            //     }
            // }

            $row[15] = $rowStatus[$key];

            $this->data[] = $row;
        }
    }
}
