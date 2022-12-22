<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\User;
use App\Models\Leads;
use App\Models\Country;
use App\Models\Industry;
use App\Models\TempLeads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\SystemLogController;
use App\Http\Controllers\GmLeadsMailListController;

class SoftCallController extends Controller
{
    public function manage_add_edit_soft_calling(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'company_name' => 'required',
            'contact_mobile_number' => 'required',
            'industry_id' => 'required',
            'country_id' => 'required',
            'city_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'message' => $validator->errors()->first(),
                ],
            );
        } else {

            $temp_lead_id = $request->temp_lead_id;
            $company_name = $request->company_name;
            $company_phone_no = $request->contact_phone_number;
            $company_country_id = $request->country_id;
            $company_city_id = $request->city_id;
            $industry_id = $request->industry_id;
            $company_email = $request->contact_email;
            $department = $request->department;
            $designation = $request->designation;
            $contact_person_name = $request->contact_person;
            $contact_person_email = $request->contact_email;
            $contact_person_phone = $request->contact_mobile_number;
            $dob = $request->dob;
            $address = $request->address;
            $post_box_no = $request->pbno;
            $cp_country_id = $request->country_id;
            $cp_city_id = $request->city_id;
            $website_name = $request->website;
            $created_by = Auth::user()->id;
            $modified_by = Auth::user()->id;

            if ($temp_lead_id) {
                $temp_leads_selected = TempLeads::find($temp_lead_id);
                if ($temp_leads_selected) {
                    if ($temp_leads_selected->calling_status != '3') {
                        $tempLead = TempLeads::where('company_name', $company_name)->where('company_country_id', $company_country_id)->where('company_city_id', $company_city_id)->where('company_phone_no', $company_phone_no)->where('is_deleted', null)->first();
                        if ($tempLead && $tempLead->id != $temp_lead_id) {
                            return response()->json(
                                [
                                    'status' => false,
                                    'message' => 'This lead already exists',
                                ],
                            );
                        } else {
                            $temp_leads = TempLeads::find($temp_lead_id);
                            $temp_leads->company_name = $company_name;
                            $temp_leads->company_phone_no = $company_phone_no;
                            $temp_leads->company_country_id = $company_country_id;
                            $temp_leads->company_city_id = $company_city_id;
                            $temp_leads->industry_id = $industry_id;
                            $temp_leads->company_email = $company_email;
                            $temp_leads->department = $department;
                            $temp_leads->designation = $designation;
                            $temp_leads->contact_person_name = $contact_person_name;
                            $temp_leads->contact_person_email = $contact_person_email;
                            $temp_leads->contact_person_phone = $contact_person_phone;
                            $temp_leads->dob = $dob;
                            $temp_leads->address = $address;
                            $temp_leads->post_box_no = $post_box_no;
                            $temp_leads->cp_country_id = $cp_country_id;
                            $temp_leads->cp_city_id = $cp_city_id;
                            $temp_leads->website_name = $website_name;
                            $temp_leads->modified_by = $modified_by;
                            $temp_leads->save();

                            $logData = array(
                                'user_id' => auth()->user()->id,
                                'action_id' => $temp_leads->id,
                                'action_type' => 'create',
                                'module' => 'softcall',
                                'description' =>  'Soft Call Updated',
                            );

                            $storeLog = SystemLogController::addLog($logData);

                            return response()->json(
                                [
                                    'status' => true,
                                    'message' => 'Soft Call Updated Successfully',
                                ],
                            );
                        }
                    } else {
                        return response()->json(
                            [
                                'status' => false,
                                'message' => 'Soft Call Already Converted',
                            ],
                        );
                    }
                }
            } else {

                $tempLead = TempLeads::where('company_name', $company_name)->where('company_country_id', $company_country_id)->where('company_city_id', $company_city_id)->where('company_phone_no', $company_phone_no)->where('is_deleted', null)->first();
                if ($tempLead) {
                    return response()->json(
                        [
                            'status' => false,
                            'message' => 'This lead already exists',
                        ],
                    );
                } else {
                    $temp_leads = new TempLeads();
                    $temp_leads->company_name = $company_name;
                    $temp_leads->company_phone_no = $company_phone_no;
                    $temp_leads->company_country_id = $company_country_id;
                    $temp_leads->company_city_id = $company_city_id;
                    $temp_leads->industry_id = $industry_id;
                    $temp_leads->company_email = $company_email;
                    $temp_leads->department = $department;
                    $temp_leads->designation = $designation;
                    $temp_leads->contact_person_name = $contact_person_name;
                    $temp_leads->contact_person_email = $contact_person_email;
                    $temp_leads->contact_person_phone = $contact_person_phone;
                    $temp_leads->dob = $dob;
                    $temp_leads->address = $address;
                    $temp_leads->post_box_no = $post_box_no;
                    $temp_leads->cp_country_id = $cp_country_id;
                    $temp_leads->cp_city_id = $cp_city_id;
                    $temp_leads->website_name = $website_name;
                    $temp_leads->created_by = $created_by;
                    $temp_leads->save();

                    $logData = array(
                        'user_id' => auth()->user()->id,
                        'action_id' => $temp_leads->id,
                        'action_type' => 'create',
                        'module' => 'softcall',
                        'description' =>  'Soft Call Created',
                    );

                    $storeLog = SystemLogController::addLog($logData);

                    return response()->json(
                        [
                            'status' => true,
                            'message' => 'Soft Call Added Successfully',
                        ],
                    );
                }
            }
        }
    }

    public function delete_soft_call_data(Request $request)
    {
        $temp_lead_id = $request->temp_lead_id;
        $temp_leads_selected = TempLeads::find($temp_lead_id);
        if ($temp_leads_selected) {
            $temp_leads_selected->is_deleted = 1;
            $temp_leads_selected->save();

            $logData = array(
                'user_id' => auth()->user()->id,
                'action_id' => $temp_leads_selected->id,
                'action_type' => 'delete',
                'module' => 'softcall',
                'description' =>  'Soft Call Deleted',
            );

            $storeLog = SystemLogController::addLog($logData);

            return response()->json(
                [
                    'status' => true,
                    'message' => 'Soft Call Record Deleted Successfully',
                ],
            );
        } else {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Soft Call Not Found',
                ],
            );
        }
    }

    public function incoming_call_status(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'lead_id' => 'required',
            'call_status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'message' => $validator->errors()->first(),
                ],
            );
        } else {

            $lead_id = $request->lead_id;
            $call_status = $request->call_status;
            $next_call_date = date('Y-m-d H:i:s', strtotime($request->next_call_date));
            $spoken_with = Auth::user()->id;
            $call_spoken_with = $request->spoken_with;
            $cell_no = $request->cell_no;
            $email_id = $request->email_id;
            $requirements = $request->requirement;
            $requirement_remarks = $request->requirement_remarks;
            $remarks = $request->remarks;

            $created_by = Auth::user()->id;

            if ($call_status == 2) {
                if ($next_call_date == null) {
                    return response()->json(
                        [
                            'status' => false,
                            'message' => 'Next Call Date is Required',
                        ],
                    );
                } else {
                    if ($next_call_date <= date('Y-m-d H:i:s')) {
                        return response()->json(
                            [
                                'status' => false,
                                'message' => 'Next Call Date/Time Must be Greater than Current Date/Time',
                            ],
                        );
                    } else {
                        $temp_leads = TempLeads::find($lead_id);
                        $temp_leads->calling_status = $call_status;
                        $temp_leads->call_type = 1;
                        $temp_leads->is_assigned = 0;
                        $temp_leads->last_tele_caller_id = $spoken_with;
                        $temp_leads->last_call_date = date('Y-m-d H:i:s');
                        $temp_leads->recalling_date = $next_call_date;
                        $temp_leads->tele_caller_id = null;
                        $temp_leads->last_call_comment = $remarks;
                        $temp_leads->save();

                        $logData = array(
                            'user_id' => auth()->user()->id,
                            'action_id' => $temp_leads->id,
                            'call_status' => $call_status,
                            'call_type' => 1,
                            'action_type' => 'update',
                            'module' => 'incoming_dashboard',
                            'description' => 'Incoming Call Status Updated',
                        );
                        $storeLog = SystemLogController::addLog($logData);


                        return response()->json(
                            [
                                'status' => true,
                                'message' => 'Incoming Call Status Updated Successfully',
                            ],
                        );
                    }
                }
            }
            if ($call_status == 3) {
//                if ($cell_no == null) {
//                    return response()->json(
//                        [
//                            'status' => false,
//                            'message' => 'Cell No is Required',
//                        ],
//                    );
//                }

//                if ($email_id == null) {
//                    return response()->json(
//                        [
//                            'status' => false,
//                            'message' => 'Email Id is Required',
//                        ],
//                    );
//                }

                if ($requirements == null) {
                    return response()->json(
                        [
                            'status' => false,
                            'message' => 'Requirements is Required',
                        ],
                    );
                }

                if ($requirements == 1) {
                    if ($requirement_remarks == null) {
                        return response()->json(
                            [
                                'status' => false,
                                'message' => 'Requirement Remarks is Required',
                            ],
                        );
                    }
                }

                $temp_leads = TempLeads::find($lead_id);
                $temp_leads->calling_status = $call_status;
                $temp_leads->call_type = 1;
                $temp_leads->is_assigned = 0;
                $temp_leads->last_tele_caller_id = $spoken_with;
                $temp_leads->last_call_date = date('Y-m-d H:i:s');
                $temp_leads->recalling_date =  date('Y-m-d H:i:s');
                $temp_leads->last_call_comment = $remarks;
                $temp_leads->tele_caller_id = null;
                $temp_leads->save();

                if ($requirements == 1) {
                    $temp_leads_details_mail_details = TempLeads::where('id', $lead_id)->first()->toArray();
                    $mail_data_name = $temp_leads_details_mail_details['contact_person_name'];
                    $mail_data_country = Country::where('id', $temp_leads_details_mail_details['company_country_id'])->first()->country_name;
                    $add_to_gm_mail_list = GmLeadsMailListController::create_mail_send_list($lead_id, $mail_data_name, $mail_data_country);
                }

                $lead = new Leads();
                $lead->temp_lead_id = $lead_id;
                $lead->spoken_with = $call_spoken_with;
                $lead->contact_no = $cell_no;
                $lead->email = $email_id;
                if ($requirements == 1) {
                    $lead->is_requirement = 1;
                    $lead->basic_requirement = $requirement_remarks;
                } else {
                    $lead->is_requirement = 0;
                    $lead->basic_requirement = "No Manpower Required";
                }
                $lead->created_by = $created_by;
                $lead->save();

                $logData = array(
                    'user_id' => auth()->user()->id,
                    'action_id' => $temp_leads->id,
                    'call_status' => $call_status,
                    'call_type' => 1,
                    'action_type' => 'update',
                    'module' => 'incoming_dashboard',
                    'description' => 'Incoming Call Status Updated',
                );
                $storeLog = SystemLogController::addLog($logData);

                return response()->json(
                    [
                        'status' => true,
                        'message' => 'Saved Successfully',
                    ],
                );
            }

            if ($call_status == 4) {
                $temp_leads = TempLeads::find($lead_id);
                $temp_leads->recalling_date = Date('Y-m-d', strtotime('+180 days'));
                $temp_leads->is_assigned = 0;
                $temp_leads->last_tele_caller_id = $spoken_with;
                $temp_leads->last_call_date = date('Y-m-d H:i:s');
                $temp_leads->calling_status = $call_status;
                $temp_leads->call_type = 1;
                $temp_leads->tele_caller_id = null;
                $temp_leads->last_call_comment = $remarks;
                $temp_leads->save();

                return response()->json(
                    [
                        'status' => true,
                        'message' => 'Incoming Call Status Updated Successfully',
                    ],
                );
            }

            if ($call_status == 1 || $call_status == 5 || $call_status == 6 || $call_status == 7 || $call_status == 8 || $call_status == 9) {
                $temp_leads = TempLeads::find($lead_id);
                $temp_leads->recalling_date = Date('Y-m-d', strtotime('+10 days'));
                $temp_leads->calling_status = $call_status;
                $temp_leads->call_type = 1;
                $temp_leads->last_call_date = date('Y-m-d H:i:s');
                $temp_leads->is_assigned = 0;
                $temp_leads->last_tele_caller_id = $spoken_with;
                $temp_leads->tele_caller_id = null;
                $temp_leads->last_call_comment = $remarks;
                $temp_leads->save();

                $logData = array(
                    'user_id' => auth()->user()->id,
                    'action_id' => $temp_leads->id,
                    'call_status' => $call_status,
                    'call_type' => 1,
                    'action_type' => 'update',
                    'module' => 'incoming_dashboard',
                    'description' => 'Incoming Call Status Updated',
                );
                $storeLog = SystemLogController::addLog($logData);

                return response()->json(
                    [
                        'status' => true,
                        'message' => 'Incoming Call Status Updated Successfully',
                    ],
                );
            }

            if ($call_status == 10) {
                $temp_leads = TempLeads::find($lead_id);
                $temp_leads->calling_status = $call_status;
                $temp_leads->call_type = 1;
                $temp_leads->last_tele_caller_id = $spoken_with;
                $temp_leads->last_call_date = date('Y-m-d H:i:s');
                $temp_leads->is_assigned = 0;
                $temp_leads->tele_caller_id = $spoken_with;
                $temp_leads->last_call_comment = $remarks;
                $temp_leads->save();

                $logData = array(
                    'user_id' => auth()->user()->id,
                    'action_id' => $temp_leads->id,
                    'call_status' => $call_status,
                    'call_type' => 1,
                    'action_type' => 'update',
                    'module' => 'incoming_dashboard',
                    'description' => 'Incoming Call Status Updated',
                );
                $storeLog = SystemLogController::addLog($logData);

                return response()->json(
                    [
                        'status' => true,
                        'message' => 'Incoming Call Status Updated Successfully',
                    ],
                );
            }
        }
    }


    public function outgoing_call_status(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'lead_id' => 'required',
            'call_status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'message' => $validator->errors()->first(),
                ],
            );
        } else {

            $lead_id = $request->lead_id;
            $call_status = $request->call_status;
            $next_call_date = date('Y-m-d H:i:s', strtotime($request->next_call_date));
            $spoken_with = Auth::user()->id;
            $call_spoken_with = $request->spoken_with;
            $cell_no = $request->cell_no;
            $email_id = $request->email_id;
            $requirements = $request->requirement;
            $requirement_remarks = $request->requirement_remarks;
            $remarks = $request->remarks;

            $created_by = Auth::user()->id;

            if ($call_status == 2) {
                if ($next_call_date == null) {
                    return response()->json(
                        [
                            'status' => false,
                            'message' => 'Next Call Date is Required',
                        ],
                    );
                } else {
                    if ($next_call_date <= date('Y-m-d H:i:s')) {
                        return response()->json(
                            [
                                'status' => false,
                                'message' => 'Next Call Date/Time Must be Greater than Current Date/Time',
                            ],
                        );
                    } else {
                        $temp_leads = TempLeads::find($lead_id);
                        $temp_leads->calling_status = $call_status;
                        $temp_leads->call_type = 2;
                        $temp_leads->is_assigned = 0;
                        $temp_leads->last_tele_caller_id = $spoken_with;
                        $temp_leads->last_call_date = date('Y-m-d H:i:s');
                        $temp_leads->recalling_date = $next_call_date;
                        $temp_leads->tele_caller_id = null;
                        $temp_leads->last_call_comment = $remarks;
                        $temp_leads->save();

                        $logData = array(
                            'user_id' => auth()->user()->id,
                            'action_id' => $temp_leads->id,
                            'call_status' => $call_status,
                            'call_type' => 2,
                            'action_type' => 'update',
                            'module' => 'outgoing_dashboard',
                            'description' => 'Outgoing Call Status Updated',
                        );
                        $storeLog = SystemLogController::addLog($logData);

                        return response()->json(
                            [
                                'status' => true,
                                'message' => 'Outgoing Call Status Updated Successfully',
                            ],
                        );
                    }
                }
            }
            if ($call_status == 3) {
                if ($cell_no == null) {
                    return response()->json(
                        [
                            'status' => false,
                            'message' => 'Cell No is Required',
                        ],
                    );
                }

                if ($email_id == null) {
                    return response()->json(
                        [
                            'status' => false,
                            'message' => 'Email Id is Required',
                        ],
                    );
                }

                if ($requirements == null) {
                    return response()->json(
                        [
                            'status' => false,
                            'message' => 'Requirements is Required',
                        ],
                    );
                }

                if ($requirements == 1) {
                    if ($requirement_remarks == null) {
                        return response()->json(
                            [
                                'status' => false,
                                'message' => 'Requirement Remarks is Required',
                            ],
                        );
                    }
                }

                $temp_leads = TempLeads::find($lead_id);
                $temp_leads->calling_status = $call_status;
                $temp_leads->call_type = 2;
                $temp_leads->is_assigned = 0;
                $temp_leads->last_tele_caller_id = $spoken_with;
                $temp_leads->last_call_date = date('Y-m-d H:i:s');
                $temp_leads->recalling_date = date('Y-m-d H:i:s');
                $temp_leads->tele_caller_id = null;
                $temp_leads->last_call_comment = $remarks;
                $temp_leads->save();

                if ($requirements == 1) {
                    $temp_leads_details_mail_details = TempLeads::where('id', $lead_id)->first()->toArray();
                    $mail_data_name = $temp_leads_details_mail_details['contact_person_name'];
                    $mail_data_country = Country::where('id', $temp_leads_details_mail_details['company_country_id'])->first()->country_name;

                    $add_to_gm_mail_list = GmLeadsMailListController::create_mail_send_list($lead_id, $mail_data_name, $mail_data_country);
                }

                $lead = new Leads();
                $lead->temp_lead_id = $lead_id;
                $lead->spoken_with = $call_spoken_with;
                $lead->contact_no = $cell_no;
                $lead->email = $email_id;
                if ($requirements == 1) {
                    $lead->is_requirement = 1;
                    $lead->basic_requirement = $requirement_remarks;
                } else {
                    $lead->is_requirement = 0;
                    $lead->basic_requirement = "No Manpower Required";
                }
                $lead->created_by = $created_by;
                $lead->save();

                $logData = array(
                    'user_id' => auth()->user()->id,
                    'action_id' => $temp_leads->id,
                    'call_status' => $call_status,
                    'call_type' => 2,
                    'action_type' => 'update',
                    'module' => 'outgoing_dashboard',
                    'description' => 'Outgoing Call Status Updated',
                );
                $storeLog = SystemLogController::addLog($logData);
                return response()->json(
                    [
                        'status' => true,
                        'message' => 'Saved Successfully',
                    ],
                );
            }

            if ($call_status == 4) {
                $temp_leads = TempLeads::find($lead_id);
                $temp_leads->recalling_date = Date('Y-m-d', strtotime('+180 days'));
                $temp_leads->is_assigned = 0;
                $temp_leads->last_tele_caller_id = $spoken_with;
                $temp_leads->last_call_date = date('Y-m-d H:i:s');
                $temp_leads->calling_status = $call_status;
                $temp_leads->call_type = 2;
                $temp_leads->tele_caller_id = null;
                $temp_leads->last_call_comment = $remarks;
                $temp_leads->save();

                $logData = array(
                    'user_id' => auth()->user()->id,
                    'action_id' => $temp_leads->id,
                    'call_status' => $call_status,
                    'call_type' => 2,
                    'action_type' => 'update',
                    'module' => 'outgoing_dashboard',
                    'description' => 'Outgoing Call Status Updated',
                );
                $storeLog = SystemLogController::addLog($logData);

                return response()->json(
                    [
                        'status' => true,
                        'message' => 'Outgoing Call Status Updated Successfully',
                    ],
                );
            }

            if ($call_status == 1 || $call_status == 5 || $call_status == 6 || $call_status == 7 || $call_status == 8 || $call_status == 9) {
                $temp_leads = TempLeads::find($lead_id);
                $temp_leads->recalling_date = Date('Y-m-d', strtotime('+10 days'));
                $temp_leads->calling_status = $call_status;
                $temp_leads->call_type = 2;
                $temp_leads->last_call_date = date('Y-m-d H:i:s');
                $temp_leads->is_assigned = 0;
                $temp_leads->last_tele_caller_id = $spoken_with;
                $temp_leads->tele_caller_id = null;
                $temp_leads->last_call_comment = $remarks;
                $temp_leads->save();

                $logData = array(
                    'user_id' => auth()->user()->id,
                    'action_id' => $temp_leads->id,
                    'call_status' => $call_status,
                    'call_type' => 2,
                    'action_type' => 'update',
                    'module' => 'outgoing_dashboard',
                    'description' => 'Outgoing Call Status Updated',
                );
                $storeLog = SystemLogController::addLog($logData);

                return response()->json(
                    [
                        'status' => true,
                        'message' => 'Outgoing Call Status Updated Successfully',
                    ],
                );
            }

            if ($call_status == 10) {
                $temp_leads = TempLeads::find($lead_id);
                $temp_leads->calling_status = $call_status;
                $temp_leads->call_type = 2;
                $temp_leads->last_tele_caller_id = $spoken_with;
                $temp_leads->last_call_date = date('Y-m-d H:i:s');
                $temp_leads->is_assigned = 0;
                $temp_leads->tele_caller_id = null;
                $temp_leads->last_call_comment = $remarks;
                $temp_leads->save();

                $logData = array(
                    'user_id' => auth()->user()->id,
                    'action_id' => $temp_leads->id,
                    'call_status' => $call_status,
                    'call_type' => 2,
                    'action_type' => 'update',
                    'module' => 'outgoing_dashboard',
                    'description' => 'Outgoing Call Status Updated',
                );
                $storeLog = SystemLogController::addLog($logData);

                return response()->json(
                    [
                        'status' => true,
                        'message' => 'Outgoing Call Status Updated Successfully',
                    ],
                );
            }
        }
    }


    public function search_temp_leads(Request $request)
    {
        $search_query = $request->search_query;
        $temp_leads = TempLeads::where('contact_person_name', 'like', '%' . $search_query . '%')
            ->orWhere('contact_person_email', 'like', '%' . $search_query . '%')
            ->orWhere('contact_person_phone', 'like', '%' . $search_query . '%')
            ->orWhere('company_name', 'like', '%' . $search_query . '%')
            ->get();
        if ($temp_leads) {
            $temp_leads = $temp_leads->toArray();
            $temp_leads = array_map(function ($temp_leads) {
                $temp_leads['country_name'] = Country::where('id', $temp_leads['company_country_id'])->first()->country_name;
                return $temp_leads;
            }, $temp_leads);
        }
        $view = view('backend.soft_calling.search_result_datatable', compact('temp_leads'))->render();
        return response()->json(['html' => $view]);
    }


    public function manage_soft_call_data(Request $request)
    {
        $temp_lead_id = $request->temp_lead_id;

        if ($temp_lead_id) {
            $temp_lead = TempLeads::find($temp_lead_id);
            $temp_lead = $temp_lead->toArray();
        } else {
            $temp_lead = array();
        }
        $all_industries = Industry::all()->where('is_deleted','==',null)->toArray();
        $all_countries = Country::all()->where('is_deleted','==',null)->toArray();
        $all_cities = City::all()->toArray();
        $view = view('backend.soft_calling.modals.manage_soft_calling_data_modal', compact('temp_lead', 'all_industries', 'all_countries', 'all_cities'))->render();
        return response()->json(['html' => $view]);
    }

    public function get_details($id)
    {
        $user_data = Auth::user()->toArray();
        $soft_caller_id = $user_data['id'];
        $calling_status_list = array();
        $calling_status_list[0] = "Not Called";
        $calling_status_list[1] = "Busy";
        $calling_status_list[2] = "Call Later";
        $calling_status_list[3] = "Called";
        $calling_status_list[4] = "Do Not Call Again";
        $calling_status_list[5] = "No Requirement";
        $calling_status_list[6] = "Not Reachable";
        $calling_status_list[7] = "Out OF Service";
        $calling_status_list[8] = "Ringing";
        $calling_status_list[9] = "Swich Off";
        $calling_status_list[10] = "Wrong Number";

        $temp_leads = TempLeads::find($id);

        if ($temp_leads) {
            $temp_leads = $temp_leads->toArray();
            $company_country = Country::where('id', $temp_leads['company_country_id'])->first()->toArray();
            $company_city = City::where('id', $temp_leads['company_city_id'])->first()->toArray();
            $industry = Industry::where('id', $temp_leads['industry_id'])->first()->toArray();
            $last_called_by = User::where('id', $temp_leads['last_tele_caller_id'])->first();

            if ($last_called_by) {
                $last_called_by = $last_called_by->toArray();
                $temp_leads['last_called_by'] = $last_called_by['name'];
            } else {
                $last_called_by = array();
                $temp_leads['last_called_by'] = 'N/A';
            }

            $temp_leads['company_country'] = $company_country['country_name'];
            $temp_leads['country_code'] = $company_country['country_code'];
            $temp_leads['company_city'] = $company_city['city_name'];
            $temp_leads['industry'] = $industry['industry_name'];
            $temp_leads['calling_status'] = $calling_status_list[$temp_leads['calling_status']];
        } else {
            $temp_leads = array();
        }
        return view('backend.soft_calling.incoming_single_record', compact('temp_leads'));
    }




    public function get_temp_lead_list(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndexArr = $request->get('order');
        $columnNameArr = $request->get('columns');
        $orderArr = $request->get('order');
        $searchArr = $request->get('search');

        $columnIndex = isset($columnIndexArr[0]['column']) ? $columnIndexArr[0]['column'] : ''; // Column index
        $columnName = !empty($columnIndex) ? $columnNameArr[$columnIndex]['data'] : 'updated_at'; // Column name
        $columnSortOrder = !empty($columnIndex) ? $orderArr[0]['dir'] : 'DESC'; // asc or desc
        $searchValue = $searchArr['value']; // Search value

        $totalRecords = TempLeads::select('count(*) as allcount')->where('is_deleted', null)->count();
        $totalRecordswithFilter = TempLeads::where('is_deleted', null)
            ->where('company_name', 'like', '%' . $searchValue . '%')
            ->orWhere('company_phone_no', 'like', '%' . $searchValue . '%')
            ->orWhere('company_email', 'like', '%' . $searchValue . '%')
            ->orWhere('contact_person_email', 'like', '%' . $searchValue . '%')
            ->orWhere('contact_person_name', 'like', '%' . $searchValue . '%')
            ->orWhere('contact_person_phone', 'like', '%' . $searchValue . '%')
            ->count();

        // $temp_leads_data = TempLeads::where('is_deleted', null)
        //     ->with('country')
        //     ->with('city')
        //     ->with('industry')
        //     ->where('company_name', 'like', '%' . $searchValue . '%')
        //     ->orWhere('company_phone_no', 'like', '%' . $searchValue . '%')
        //     ->orWhere('company_email', 'like', '%' . $searchValue . '%')
        //     ->orWhere('contact_person_email', 'like', '%' . $searchValue . '%')
        //     ->orWhere('contact_person_name', 'like', '%' . $searchValue . '%')
        //     ->orWhere('contact_person_phone', 'like', '%' . $searchValue . '%')
        //     ->orderBy($columnName, $columnSortOrder)
        //     ->skip($start)
        //     ->take($rowperpage)
        //     ->get()->toArray();



        $temp_leads_data = DB::table('temp_leads')
            ->join('countries', 'temp_leads.company_country_id', '=', 'countries.id')
            ->join('cities', 'temp_leads.company_city_id', '=', 'cities.id')
            ->join('industries', 'temp_leads.industry_id', '=', 'industries.id')
            ->select('temp_leads.*', 'countries.country_name', 'cities.city_name', 'industries.industry_name')
            ->where('temp_leads.is_deleted', '=', null)
            ->where(function ($query) use ($searchValue) {
                $query->where('temp_leads.company_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('temp_leads.company_phone_no', 'like', '%' . $searchValue . '%')
                    ->orWhere('temp_leads.company_email', 'like', '%' . $searchValue . '%')
                    ->orWhere('temp_leads.contact_person_email', 'like', '%' . $searchValue . '%')
                    ->orWhere('temp_leads.contact_person_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('temp_leads.contact_person_phone', 'like', '%' . $searchValue . '%');
            })
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get()->toArray();

          //  dd($temp_leads_data);


        // foreach ($temp_leads_data as $key => $value) {

        //     $get_country_name = Country::where('id', $value['company_country_id'])->first()->toArray();
        //     $temp_leads_data[$key]['company_country'] = $get_country_name['country_name'];
        //     if ($value['is_deleted'] == 1) {
        //         unset($temp_leads_data[$key]);
        //     }
        // }

        //search country_name in temp_leads_data with $searchValue
        // $temp_leads_data = array_filter($temp_leads_data, function ($var) use ($searchValue) {
        //     return preg_match("/$searchValue/i", $var['company_country']);
        // });

        $records = $temp_leads_data;

        //dd($records);

        $user = Auth::user();
        $user_permissions = $user->getAllPermissions()->pluck('name')->toArray();
        $user_edit_permissions_check = 'soft_call_edit';
        $user_delete_permissions_check = 'soft_call_delete';
        $user_edit_permissions = in_array($user_edit_permissions_check, $user_permissions);
        $user_delete_permissions = in_array($user_delete_permissions_check, $user_permissions);

        $dataArr = array();
        $i = $start + 1;
        foreach ($records as $record) {
            $action_btn = '';
            if ($user_edit_permissions) {
                $action_btn .= '<button type="button" class="btn btn btn-icon btn-outline-primary edit_temp_lead_data" value="' . $record->id . '">
                <i class="bx bx-edit-alt"></i></button>&nbsp;&nbsp;&nbsp;';
            }
            if ($user_delete_permissions) {
                $action_btn .= '<button type="button" class="btn btn btn-icon btn-outline-primary delete_temp_lead_data" value="' . $record->id . '">
                <i class="bx bx-trash-alt"></i></button>';
            }
            if (!$user_edit_permissions && !$user_delete_permissions) {
                $action_btn .= '<span class="badge bg-secondary">No Permission</span>';
            }

            $dataArr[] = array(
                "id" => $i++,
                "company_name" => $record->company_name,
                "country_name" => $record->country_name,
                "company_phone_no" => $record->company_phone_no,
                "contact_person_name" => $record->contact_person_name,
                "contact_person_phone" => $record->contact_person_phone,
                "contact_person_email" => $record->contact_person_email,
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
}
