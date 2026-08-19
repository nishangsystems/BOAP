<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Services\AppService;
use App\Http\Services\ApiService;
use App\Http\Resources\AdmittedStudentResource;
use App\Mail\AdmissionMail;
use App\Models\ApplicationForm;
use App\Models\Batch;
use App\Models\CampusBank;
use App\Models\ClassSubject;
use App\Models\Config;
use App\Models\EntryQualification;
use App\Models\Level;
use App\Models\ProgramLevel;
use App\Models\School;
use App\Models\SchoolUnits;
use App\Models\StudentClass;
use App\Models\Students;
use App\Models\Subjects;
use App\Models\Transaction;
use App\Models\TranzakTransaction;
use App\Services\TranzakSMSService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ProgramController extends Controller
{

    public $appService, $api_service, $current_year, $tranzak_sms_service;
    public function __construct(AppService $app_service, ApiService $api_service, TranzakSMSService $tranzakSMSService){
        $this->appService = $app_service;
        $this->api_service = $api_service;
        $this->current_year = Helpers::instance()->getCurrentAccademicYear();
        $this->tranzak_sms_service = $tranzakSMSService;
    }

    public function open_admission(Request $request)
    {
        # code...
        $data['title'] = "Configure Admission Session.";
        $data['sessions'] = Config::all();
        $data['current_session'] = Config::where('year_id', Helpers::instance()->getCurrentAccademicYear())->first();
        // return $data;
        return view('admin.setting.config_admission', $data);
    }

    public function set_open_admission(Request $request)
    {
        # code...
        $validity = Validator::make($request->all(), ['start_date'=>'required|date', 'end_date'=>'required|date']);
        if($validity->fails()){return back()->with('error', $validity->errors()->first());}

        // return $request->all();
        $config = ['start_date'=>$request->start_date, 'end_date'=>$request->end_date, 'start_of_lectures'=>$request->start_of_lectures];
        Config::updateOrInsert(['year_id'=>Helpers::instance()->getCurrentAccademicYear()], $config);
        return back()->with('success', __('text.word_done'));
    }

    public function applicants_report_by_degree(Request $request)
    {
        # code...
    }

    public function applicants_report_by_program(Request $request)
    {
        # code...
    }

    public function finance_report_general()
    {
        # code...
    }

    public function config_programs(Request $request, $cid = null)
    {
        # code...
        $data['title'] = "Configure Programs Per Entry Qualification";
        // return $data;

        
        $qlf = json_decode($this->api_service->certificates());
        if($qlf != null){
            $data['certs'] = $qlf->data;
            $data['cert'] = collect($qlf->data)->where('id', $cid)->first();
            if($data['cert'] != null){
                $data['cert_programs'] = collect(json_decode($this->api_service->certificatePrograms($cid))->data)->pluck('id')->toArray();
                $progs = json_decode($this->api_service->programs());
                // return $progs;
                if($progs != null){
                    $data['programs'] = $progs->data;
                }
            }
        }
        // return $data;
        return view('admin.setting.config_program', $data);
    }

    public function set_config_programs(Request $request, $entry_id)
    {
        # code...
        $validity = Validator::make($request->all(), ['programs'=>'required|array']);
        if($validity->fails()){return back()->with('error', $validity->errors()->first());}

        // save program configuration
        $programs = $request->programs;
        $response = $this->api_service->setCertificatePrograms($entry_id, $programs);
        return back()->with('message', $response);
    }

    public function config_degrees(Request $request, $campus_id = null)
    {
        # code...
        $data['title'] = "Configure Campus Degrees";
        $data['campuses'] = json_decode($this->api_service->campuses())->data;
        $data['degrees'] = json_decode($this->api_service->degrees())->data;
        if($campus_id != null){
            $degs = $this->api_service->campusDegrees($campus_id);
            if($degs != null){
                // dd($degs);
                $data['campus_degrees'] = collect(json_decode($degs)->data)->pluck('id')->toArray();
            }
        }
           
        return view('admin.setting.configure_campus_degrees', $data);
    }

    public function set_config_degrees(Request $request, $cid)
    {
        # code...
        $validity = Validator::make($request->all(), ['campus_degrees'=>'array']);
        if($validity->fails()){return back()->with('error', $validity->errors()->first());}
        // return $request->all();
        if(($resp = json_decode($this->api_service->setCampusDegrees($cid, $request->campus_degrees??[]))->data) == '1'){
            return back()->with('success', 'Updated successfully');
        }else{
            return back()->with('error', $resp);
        };
    }

    public function applications()
    {
        # code...
        $data['title'] = "All Application Forms";
        $data['_this'] = $this;
        $data['applications'] = ApplicationForm::whereNotNull('transaction_id')->get();
        return view('admin.student.applications', $data);
    }

    public function application_details(Request $request, $id)
    {
        # code...
        $data['application'] = ApplicationForm::find($id);
        $data['title'] = "Application Details For ".$data['application']->name;
        
    }

    public function print_application_form(Request $request, $id = null)
    {
        # code...
        // dd(123);
        if($id == null){
            $data['title'] = "Print Student Application Form";
            $data['_this'] = $this;
            $data['action'] = __('text.word_print');
            $data['download'] = __('text.word_download');
            $data['applications'] = ApplicationForm::whereNotNull('transaction_id')->get();
            return view('admin.student.applications', $data);
        }

        $application = ApplicationForm::find($id);
        $data['campuses'] = json_decode($this->api_service->campuses())->data;
        $data['application'] = ApplicationForm::find($id);
        $data['degree'] = collect(json_decode($this->api_service->degrees())->data??[])->where('id', $data['application']->degree_id)->first();
        $data['campus'] = collect($data['campuses'])->where('id', $data['application']->campus_id)->first();
        $data['certs'] = json_decode($this->api_service->certificates())->data;
        
        $data['programs'] = json_decode($this->api_service->campusDegreeCertificatePrograms($data['application']->campus_id, $data['application']->degree_id, $data['application']->entry_qualification))->data;
        $data['cert'] = collect($data['certs'])->where('id', $data['application']->entry_qualification)->first();
        $data['program1'] = collect($data['programs'])->where('id', $data['application']->program_first_choice)->first();
        $data['program2'] = collect($data['programs'])->where('id', $data['application']->program_second_choice)->first();
        
        // $title = $application->degree??''.' APPLICATION FOR '.$application->campus->name??' --- '.' CAMPUS';
        $title = "APPLICATION FORM FOR ".$data['degree']->deg_name;
        $data['title'] = $title;

        if(in_array(null, array_values($data))){ return redirect(route('student.application.start', [0, $id]))->with('message', "Make sure your form is correctly filled and try again.");}
        // return view('student.online.form_dawnloadable', $data);
        $pdf = PDF::loadView('student.online.form_dawnloadable', $data);
        $filename = $title.' - '.$application->name.'.pdf';
        return $pdf->download($filename);
    }

    public function edit_application_form(Request $request, $id = null)
    {
        # code...
        if($id == null){
            $data['title'] = "Edit Student Information";
            $data['_this'] = $this;
            $data['action'] = __('text.word_edit');
            $data['applications'] = ApplicationForm::where('year_id', Helpers::instance()->getCurrentAccademicYear())->get();
            return view('admin.student.applications', $data);
        }

        # code...
        // return $this->api_service->campuses();
        $data['campuses'] = json_decode($this->api_service->campuses())->data;
        $data['application'] = ApplicationForm::find($id);

        if($data['application']->degree_id != null){
            $data['degree'] = collect(json_decode($this->api_service->degrees())->data)->where('id', $data['application']->degree_id)->first();
        }
        if($data['application']->campus_id != null){
            $data['campus'] = collect($data['campuses'])->where('id', $data['application']->campus_id)->first();
        }
        if($data['application']->degree_id != null){
            $data['certs'] = json_decode($this->api_service->certificates())->data;
        }
        if($data['application']->entry_qualification != null){
            $data['programs'] = json_decode($this->api_service->campusDegreeCertificatePrograms($data['application']->campus_id, $data['application']->degree_id, $data['application']->entry_qualification))->data;
            $data['cert'] = collect($data['certs'])->where('id', $data['application']->entry_qualification)->first();
        }
        if($data['application']->program_first_choice != null){
            $data['program1'] = collect($data['programs'])->where('id', $data['application']->program_first_choice)->first();
            $data['program2'] = collect($data['programs'])->where('id', $data['application']->program_second_choice)->first();
            // return $data;
        }
        
        $data['title'] = "APPLICATION FORM FOR ".$data['degree']->deg_name;
        return view('admin.student.edit_form', $data);
        
    }

    public function update_application_form(Request $request, $id)
    {
        # code...
        $validity = Validator::make($request->all(), ['name'=>'required']);
        if($validity->fails()){
            return back()->with('error', $validity->errors()->first());
        }

        $data = ['name'=>$request->name];
        $application = ApplicationForm::find($id);
        $application->update($data);
        if($application->admitted == 1){
            $this->api_service->update_student($application->matric, $data);
        }
        return back()->with('success', __('text.word_done'));
    }

    public function uncompleted_application_form(Request $request, $id=null)
    {
        # code...
        if($id == null){
            $data['title'] = "Uncompleted Application Forms";
            $data['_this'] = $this;
            $data['action'] = __('text.word_show');
            $data['applications'] = ApplicationForm::where('submitted', 0)->where('year_id', Helpers::instance()->getCurrentAccademicYear())->get();
            // return $data;
            return view('admin.student.applications', $data);
        }

        // return $this->api_service->campuses();
        $data['campuses'] = json_decode($this->api_service->campuses())->data;
        $data['application'] = ApplicationForm::find($id);

        if($data['application']->degree_id != null){
            $data['degree'] = collect(json_decode($this->api_service->degrees())->data)->where('id', $data['application']->degree_id)->first();
        }
        if($data['application']->campus_id != null){
            $data['campus'] = collect($data['campuses'])->where('id', $data['application']->campus_id)->first();
        }
        if($data['application']->degree_id != null){
            $data['certs'] = json_decode($this->api_service->certificates())->data;
        }
        if($data['application']->entry_qualification != null){
            $data['programs'] = json_decode($this->api_service->campusDegreeCertificatePrograms($data['application']->campus_id, $data['application']->degree_id, $data['application']->entry_qualification))->data;
            $data['cert'] = collect($data['certs'])->where('id', $data['application']->entry_qualification)->first();
        }
        if($data['application']->program_first_choice != null){
            $data['program1'] = collect($data['programs'])->where('id', $data['application']->program_first_choice)->first();
            $data['program2'] = collect($data['programs'])->where('id', $data['application']->program_second_choice)->first();
            // return $data;
        }
        
        $data['title'] = "INCOMPLETE APPLICATION FORM ".( array_key_exists('degree', $data) ? "FOR ".$data['degree']->deg_name : null);
        return view('admin.student.show_form', $data);
    }

    public function distant_application_form(Request $request, $id)
    {
        # code...
    }

    public function admission_letter(Request $request, $id = null)
    {

        # code...
        if($id == null){
            $data['title'] = "Send Student Admission Letter";
            $data['_this'] = $this;
            // $data['action'] = __('text.word_send');
            $data['adml'] = 1;
            $data['download'] = __('text.word_download');
            $data['applications'] = ApplicationForm::whereNotNull('transaction_id')->where('admitted', 1)->where('year_id', Helpers::instance()->getCurrentAccademicYear())->get();
            return view('admin.student.applications', $data);
        }
        if($request->has('_atn')){
            return $this->send_admission_letter($id, $request->_atn);
        }
        // if($this->send_admission_letter($id)){
        //     return back()->with('success', __('text.word_done'));
        // }
        return back()->with('error', __('text.operation_failed'));
    }

    public function send_admission_letter($id, $action = null)
    {
        // TEMPORARILY HALTING SENDING OF ADMISSION LETTERS
        // return true;

        $appl = ApplicationForm::find($id);
        if($appl != null){
            return $this->appService->admission_letter($id);
            // $this->sendAdmissionEmails($appl->name, $appl->email, $appl->matric, $program->name??null, $campus->name??null, $config->fee1_latest_date, $config->fee2_latest_date, $config->director, $config->dean, $config->help_email, $pdf, $degree->deg_name??null);
        }
        return back();
    }

    public function admit_application_form(Request $request, $id=null)
    {
        # code...
        if($id == null){
            $data['title'] = "Admit Student";
            $data['_this'] = $this;
            $data['action'] = __('text.word_admit');
            $data['applications'] = ApplicationForm::whereNotNull('transaction_id')->where('admitted', 0)->where('year_id', Helpers::instance()->getCurrentAccademicYear())->get();
            return view('admin.student.applications', $data);
        }
        if(!$request->has('matric') or ($request->matric == null)){
            // dd($request->matric);
            // GENERATE MATRICULE
            $application = ApplicationForm::find($id);
            if($application->name == null || strlen($application->name) == 0){
                $application->update(['name' => $application->student->name]);
            }
            if(($programs = json_decode($this->api_service->programs())->data) != null){
                $program = collect($programs)->where('id', $application->program_first_choice)->first()??null;
                if($program != null){
                    // dd($program);
                    $year = substr(Batch::find(Helpers::instance()->getCurrentAccademicYear())->name, 2, 2);
                    $prefix = $program->prefix??null;//3 char length
                    $suffix = $program->suffix??'';//3 char length
                    $max_count = '';
                    if($prefix == null){
                        return back()->with('error', 'Matricule generation prefix not set.');
                    }
                    $max_matric = json_decode($this->api_service->max_matric($prefix, $year, $suffix))->data; //matrics starting with '$prefix' sort
                    // dd($max_matric);
                    if($max_matric == null){
                        $max_count = 0;
                    }else{
                        $max_count = intval(substr($max_matric, -3));
                    }

                    NEXT_MATRIC:
                    $next_count = substr('000'.(++$max_count), -3);
                    $suffix = $suffix.(request('foreign') == 1 ? 'F' : '');//3 char length

                    $student_matric = $prefix.$year.$suffix.$next_count;
                    // dd($student_matric);
                    if(ApplicationForm::where('matric', $student_matric)->where('id', '!=', $id)->count() == 0){
                        $matric_exist = json_decode($this->api_service->matric_exist($student_matric))->data??0;
                        if($matric_exist == 1){
                            goto NEXT_MATRIC;
                        }
                        $data['title'] = "Student Admission";
                        $data['application'] = $application;
                        $data['program'] = $program;
                        if($request->foreign == 1){
                            $data['program_status'] = 'INTERNATIONAL';
                            }
                        $data['matricule'] = $student_matric;
                        $data['campus'] = collect(json_decode($this->api_service->campuses())->data)->where('id', $application->campus_id)->first();
                        // dd($data);
                        return view('admin.student.confirm_admission', $data);
                    }else{
                        # code...
                        goto NEXT_MATRIC;
                    }
                    return back()->with('error', 'Failed to generate matricule');
                }
            }
        }
    }

    public function admit_student(Request $request, $id)
    {

        
        $validity = Validator::make($request->all(), ['matric'=>'required']);
        if($validity->fails()){
            return back()->with('error', 'Missing matricule');
        }
        $application = ApplicationForm::find($id);

        // dd($application);
        // POST STUDENT TO SCHOOL SYSTEM
        // $application->update(['matric' => $request->matric]);

        // dd($request->matric);
        $student_data = [
            'name'=>$application->name??null, 
            'email'=>$application->email??null, 
            'phone'=>$application->phone??null,
            'residence'=>$application->residence??null, 
            'gender'=>$application->gender??null,
            'matric'=>$request->matric??null, 
            'dob'=>$application->dob??null, 
            'pob'=>$application->pob??null,
            'year_id'=>$application->year_id??null,
            'campus_id'=>$application->campus_id??null, 
            'admission_batch_id'=>$application->year_id??null,
            'fee_payer_name'=>$application->fee_payer_name??null, 
            'program_first_choice'=>$application->program_first_choice??null, 
            'region'=>$application->_region->name??null,
            'fee_payer_tel'=>$application->fee_payer_tel??null, 
            'division'=>$application->_division->name??null,
            'level'=>$application->level??null,
            'program_status' => $request->program_status??'ON-CAMPUS'
        ];
        $resp = json_decode($this->api_service->store_student($student_data))->data??null;
        // dd($resp);
        if($resp != null and !is_string($resp)){
           if($resp->status == 1){
                $application->update(['matric'=>$request->matric, 'admitted'=>1, 'admitted_at'=>now()]);

                // Send sms/email notification
                $phone_number = $application->phone;
                if(str_starts_with($phone_number, '+')){
                    $phone_number = substr($phone_number, '1');
                }
                if(strlen($phone_number) <= 9){
                    $phone_number = '237'.$phone_number;
                }
                // dd($phone_number);
                $message="Congratulations {$application->name}. You have been admitted into ".($chool_name??"BUIB")." for {$application->year->name} . Access your admission portal at https://apply.buibsystems.org to download your admission letter";
                $sent = $this->tranzak_sms_service->send([$phone_number], $message);

                // Send student admission letter to email
                // $this->send_admission_letter($application->id);

                return redirect(route('admin.applications.admit'))->with('success', "Student admitted successfully.");
           }else
           return back()->with('error', $resp);
       }else{
           return back()->with('error', $resp);
       }



    }

    public function application_form_change_program(Request $request, $id = null)
    {
        # code...
        if($id == null){
            $data['title'] = "Change Student Program";
            $data['_this'] = $this;
            $data['action'] = __('text.change_program');
            $data['applications'] = ApplicationForm::where('admitted', true)->where('year_id', Helpers::instance()->getCurrentAccademicYear())->get();
            return view('admin.student.applications', $data);
        }

        // return $this->api_service->campuses();
        $data['campuses'] = json_decode($this->api_service->campuses())->data;
        $data['application'] = ApplicationForm::find($id);
        $data['programs'] = collect(json_decode($this->api_service->programs())->data);

        if($data['application']->degree_id != null){
            $data['degree'] = collect(json_decode($this->api_service->degrees())->data)->where('id', $data['application']->degree_id)->first();
        }
        if($data['application']->campus_id != null){
            $data['campus'] = collect($data['campuses'])->where('id', $data['application']->campus_id)->first();
        }
        if($data['application']->degree_id != null){
            $data['certs'] = json_decode($this->api_service->certificates())->data;
        }
        if($data['application']->entry_qualification != null){
            $data['programs'] = json_decode($this->api_service->campusPrograms($data['application']->campus_id))->data;
            $data['cert'] = collect($data['certs'])->where('id', $data['application']->entry_qualification)->first();
        }
        if($data['application']->program_first_choice != null){
            $data['program1'] = collect($data['programs'])->where('id', $data['application']->program_first_choice)->first();
            $data['program2'] = collect($data['programs'])->where('id', $data['application']->program_second_choice)->first();
            // return $data;
        }
        if($data['application']->level != null){
            $data['levels'] = json_decode($this->api_service->levels())->data;
        }
        
        $data['title'] = "CHANGE PROGRAM FOR ".$data['degree']->deg_name;
        return view('admin.student.change_program', $data);
    }

    public function change_program(Request $request, $id)
    {
        # code...
        $validity = Validator::make($request->all(), ['current_program'=>'required', 'new_program'=>'required', 'level'=>'required']);
        if($validity->fails()){
            return back()->with('error', $validity->errors()->first());
        }
        $data = ['program_first_choice'=>$request->new_program, 'level'=>$request->level];
        $application = ApplicationForm::find($id);
        // $application->update($data);

        // UPDATE STUDENT IN SCHOOL SYSTEM.
        // 
        // GENERATE MATRICULE
        $program = json_decode($this->api_service->programs($request->new_program))->data??null;
        if($program != null){
            try{
                $year = substr(Batch::find(Helpers::instance()->getCurrentAccademicYear())->name, 2, 2);
                $prefix = $program->prefix;//3 char length
                $suffix = $program->suffix;//3 char length
                $max_count = '';
                if($prefix == null){
                    return back()->with('error', 'Matricule generation prefix not set.');
                }
                // dd($this->api_service->max_matric($prefix, $year, $suffix));
                $max_matric = json_decode($this->api_service->max_matric($prefix, $year, $suffix))->data??null; //matrics starting with '$prefix' sort
                if($max_matric == null){
                    $max_count = 0;
                }else{
                    $max_count = intval(substr($max_matric, -3));
                }
                
                NEXT_MATRIC:
                $max_count++;
                $next_count = substr("000{$max_count}", -3);
                $suffix = $suffix.$request->foreigner??'';
                
                
                $student_matric = $prefix.$year.$suffix.$next_count;
                // dd($student_matric);
                
                // dd(ApplicationForm::where('matric', $student_matric)->get());
                if(ApplicationForm::where('matric', $student_matric)->count() == 0){
                    // check if the matricule already exist in the main student system
                    $matric_exist = json_decode($this->api_service->matric_exist($student_matric))->data??0;
                    if($matric_exist == 1){
                        goto NEXT_MATRIC;
                    }
                    $data['title'] = "Change Student Program";
                    $data['application'] = $application;
                    $data['program'] = $program;
                    $data['matricule'] = $student_matric;
                    $data['campus'] = collect(json_decode($this->api_service->campuses())->data)->where('id', $application->campus_id)->first();
                    return view('admin.student.confirm_change_program', $data);
                }else{
                    goto NEXT_MATRIC;
                }
            }catch(\Throwable $th){
                return back()->with('error', 'Failed to generate matricule. '.$th->getMessage());
            }
        }
        return back()->with('success', 'Done');
    }

    public function change_program_save(Request $request, $id)
    {
        # code...
        $validity = Validator::make($request->all(), ['matric'=>'required', 'level'=>'required', 'program_id'=>'required', 'degree_id'=>'required']);
        if($validity->fails()){return back()->with('error', $validity->errors()->first());}
        $application = ApplicationForm::find($id);
        
        
        // POST STUDENT TO SCHOOL SYSTEM
        $resp = json_decode($this->api_service->update_student($application->matric, ['program'=>$request->program_id, 'level'=>$request->level, 'matric'=>$request->matric]))->data??null;
        
        if($resp != null){
            // return $resp;
            if(($resp->status??0) ==1){
                // $application->matric = $request->matric;
                $former_program = $application->program_first_choice;
                $application->update(['matric'=>$request->matric, 'admitted'=>1, 'program_first_choice'=>$request->program_id, 'level'=>$request->level, 'degree_id'=>$request->degree_id ]);
                $current_program = $application->program_first_choice;

                event(new \App\Events\ProgramChangeEvent($former_program, $current_program, $id, auth()->id()));                

                // Send sms/email notification
                return redirect(route('admin.applications.change_program'))->with('success', "Program changed successfully.");
            }else
            return back()->with('error', $resp);
        }
        return back()->with('error', "Operation Failed");
    }

    public function bypass_application_fee($application_id = null)
    {
        # code...
        $data['title'] = __('text.bypass_application_fee');
        $data['_this'] = $this;
        $data['applications'] = ApplicationForm::whereNull('transaction_id')->whereNotNull('degree_id')->where('year_id', Helpers::instance()->getCurrentAccademicYear())->get();
        if($application_id != null){
            $data['application'] = ApplicationForm::find($application_id);
        }
        return view('admin.student.bypass_application_fee', $data);
    }

    public function application_fee_bypass_report()
    {
        # code...
        $data['title'] = "Application Fee Bypass Report";
        $data['_this'] = $this;
        $data['applications'] = ApplicationForm::where(['year_id'=>$this->current_year])->whereNotNull('transaction_id')->whereNotNull('bypass_reason')->get()->filter(function($rec){return strlen($rec->bypass_reason) > 0;});
        // dd($data);
        return view('admin.student.application_bypass_report', $data);
    }

    public function bypass_application_fee_save(Request $request, $id)
    {
        # code...
        // create a relatively null transaction for the student

        if(!($request->has('bypass_reason'))){
            session()->flash('error', 'Bypass reason required');
            return back()->withInput();
        }
        $application = ApplicationForm::find($id);
        $degree = collect(json_decode($this->api_service->degrees())->data)->where('id', $application->degree_id)->first();


        // $data = ['transaction_ref'=>'_______', 'app_id'=>'_______', 'transaction_id'=>'_________', 'payment_method'=>'______', 'payer_user_id'=>'_________', 'payer_name'=>'_________', 'payer_account_id'=>'________', 'merchant_fee'=>0, 'merchant_account_id'=>'___________', 'net_amount_recieved'=>0];
        $data = [
            'student_id'=>$application->student_id, 'amount'=>$degree->amount??0, 'year_id'=>$application->year_id,
            'tel'=>($application->phone == null ? $application->student->phone : $application->phone), 'status'=>'SUCCESSFUL','payment_purpose'=>'____________','payment_method'=>'_________',
            'reference'=>auth()->id(), 'transaction_id'=>'_________', 'payment_id'=>$application->degree_id, 'financialTransactionId'=>'_________',
            ];
        $transaction = new Transaction($data);
        $transaction->save();

        $application->update(['transaction_id'=>$transaction->id, 'bypass_reason'=>$request->bypass_reason]);
        return redirect(route('admin.applications.uncompleted'))->with('success', __('text.word_done'));
    }

    public function applications_per_program(Request $request, $program_id = null)
    {
        # code...
        $progs = $this->api_service->school_program_structure()['data'];
        $data['title'] = "Applications Statistics";
        $data['progs'] = $progs;
        $data['totals'] = collect($progs)->groupBy('school')->map(function($sch, $key){
            // dd($key);
            $program_ids = collect($sch)->pluck('program_id')->toArray();
            $dt['applicants'] = ApplicationForm::whereIn('program_first_choice', $program_ids)->where('year_id', $this->current_year)->whereNotNull('transaction_id')->count();
            $dt['depts'] = $sch->groupBy('department')->map(function($dept, $p_key){
                $d_program_ids = $dept->pluck('program_id')->toArray();
                // dd($dept);
                $dt['applicants'] = ApplicationForm::whereIn('program_first_choice', $d_program_ids)->where('year_id', $this->current_year)->whereNotNull('transaction_id')->count();
                $dt['progs'] = $dept->groupBy('program')->map(function($prog, $p_key){
                    $d_program_ids = $prog->pluck('program_id')->toArray();
                    // dd($prog);
                    $prog['applicants'] = ApplicationForm::whereIn('program_first_choice', $d_program_ids)->where('year_id', $this->current_year)->whereNotNull('transaction_id')->count();
                    return $prog->toArray();
                });
                return $dt;
            });
            return $dt;
        });
        // dd($data);
        return view('admin.student.program_applications', $data);
    }

    public function applications_per_degree(Request $request, $degree_id = null)
    {
        # code...
        $campus_id = auth()->user()->campus_id;
        if($degree_id == null){
            $data['title'] = "Select Degree type";
            $data['campus_id'] = $campus_id;
            $data['degrees'] = json_decode($this->api_service->degrees())->data??[];
            return view('admin.student.degree_applications', $data);
        }else{
            $progs = collect(json_decode($this->api_service->programs())->data);
            $degs = collect(json_decode($this->api_service->degrees())->data);
            // dd($degs->where('id', $degree_id)->first());
            $data['title'] = $degs->where('id', $degree_id)->first()->deg_name.' Applications';
            $data['progs'] = $progs;
            if($campus_id != null){
                $data['appls'] = ApplicationForm::where('degree_id', $degree_id)->where('year_id', $this->current_year)->whereNotNull('transaction_id')->where('campus_id', $campus_id)->get();
            }else{
                $data['appls'] = ApplicationForm::where('degree_id', $degree_id)->where('year_id', $this->current_year)->whereNotNull('transaction_id')->get();
            }
            return view('admin.student.degree_applications', $data);
        }
    }

    public function applications_per_campus($campus_id = null)
    {
        # code...
        $campuses = collect(json_decode($this->api_service->campuses())->data);
        // dd($campuses);
        if($campus_id == null){
            $campus = auth()->user()->campus_id;
            if($campus == null){
                $data['campuses'] = ApplicationForm::select(['campus_id', DB::raw('COUNT(id) as applicants')])->where('year_id', $this->current_year)->whereNotNull('transaction_id')->groupBy('campus_id')->get()->map(function($row)use($campuses){
                    $row->campus_name = $campuses->where('id', $row->campus_id)->first()->name??'';
                    return $row;
                });
            }else{
                $data['campuses'] = ApplicationForm::select(['campus_id', DB::raw('COUNT(id) as applicants')])->where('year_id', $this->current_year)->whereNotNull('transaction_id')->where('campus_id', $campus)->groupBy('campus_id')->get()->map(function($row)use($campuses){
                    $row->campus_name = $campuses->where('id', $row->campus_id)->first()->name??'';
                    return $row;
                });
            }
            $data['title'] = "Applications per Campus";
        }else{
            $data['title'] = 'Applications for '.$campuses->where('id', $campus_id)->first()->name??null;
            $data['appls'] = ApplicationForm::where('campus_id', $campus_id)->where('year_id', $this->current_year)->whereNotNull('transaction_id')->orderBy('name')->get();
            $data['progs'] = collect(json_decode($this->api_service->programs())->data);
        }
        // dd($data);
        return view('admin.student.campus_applications', $data);
    }

    public function finance_general_report(Request $request)
    {
        # code...
        $year_id = $request->year_id != null ? $request->year_id : $this->current_year;
        $data['title'] = "General Financial Reports";
        $data['appls'] = ApplicationForm::whereNotNull('transaction_id')->where('year_id', $year_id)->get();
        return view('admin.student.finance_general', $data);
    }

    public function finance_summary_report(Request $request){
        $year_id = $request->year_id != null ? $request->year_id : $this->current_year;
        $school_structure = $this->api_service->school_program_structure();
        $year = Batch::find($year_id);
        $data['school_structure'] = collect($school_structure->first());
        $data['years'] = Batch::all();
        $data['applications'] = ApplicationForm::whereNotNull('transaction_id')->where('year_id', $year_id)
            ->get()
            ->each(function($rec){
                $rec->amount = optional($rec->transaction)->amount??0;
            });
        $data['title'] = "Summary Financial Report &Rang; ".$year->name;
        return view('admin.student.finance_summary', $data);
    }

    private function sendAdmissionEmails($name, $email, $matric, $program, $campus, $fee1_dateline, $fee2_dateline, $director_name, $dean_name, $help_email, $file, $degree){
        Mail::to($email)->send(new AdmissionMail($name, $campus, $program, $matric,  $fee1_dateline, $fee2_dateline, $help_email, $director_name, $dean_name, $degree,  $file, config('platform_links')[$campus]));
    }

    public function degree_certificates($degree_id = null)
    {
        # code...
        $data['title'] = __('text.configure_degree_certificates');
        $data['degrees'] = json_decode($this->api_service->degrees())->data;
        $data['certificates'] = json_decode($this->api_service->certificates())->data;
        if($degree_id != null){
            $data['degree_certificates'] = collect(json_decode($this->api_service->degree_certificates($degree_id))->data)->pluck('id')->toArray();
        }
        // dd($data);
        return view('admin.setting.degree_certs', $data);
    }

    public function set_degree_certificates(Request $request, $degree_id)
    {
        # code...
        $validator = Validator::make($request->all(), ['certificates'=>'required|array']);
        if($validator->fails()){
            return back()->with('error', $validator->errors()->first());
        }
        $certificate_ids = $request->certificates;
        $response = json_decode($this->api_service->set_degree_certificates($degree_id, $certificate_ids));
        if($response->status == 'success'){return back()->with('success', __('text.word_done'));}else{
            return back()->with('error', $response->message);
        }
    }

    public function undo_bypass_application_fee($application_id)
    {
        # code...
        $appl = ApplicationForm::find($application_id);
        if($appl != null){
            $transaction = TranzakTransaction::find($appl->transaction_id);
            if($transaction != null)
                $transaction->delete();
            
            $appl->update(['transaction_id'=>null, 'bypass_reason'=>null]);
            return back()->with('success', 'Done');
        }
        session()->flash('error', 'Transaction not found.');
        return back()->withInput();
    }

    public function configure_appliable_programs(Request $request){
        $data['title'] = "Configure Appliable Programs";
        $data['programs'] = json_decode($this->api_service->programs())->data;
        return view('admin.setting.appliable_programs', $data);
    }

    public function save_appliable_programs(Request $request){
        $validity = Validator::make($request->all(), ['programs'=>'required|array']);
        if($validity->fails()){
            session()->flash('error', $validity->errors()->first());
            return back()->withInput();
        }
        // dd($request->all());
        $this->api_service->set_appliable_programs($request->programs);
        return back()->with('success', "Done");
    }

    public function ad_letter_page2_index(Request $request){
        $data['title'] = "Set Second Page Of Admission Letter Per Program";
        $data['programs'] = json_decode($this->api_service->programs())->data;
        return view('admin.programs.p2_index', $data);
    }

    public function set_ad_letter_page2(Request $request, $program_id){
        $program = json_decode($this->api_service->programs($program_id))->data;
        $data['title'] = "Create Second Page For ".$program->name;
        $data['program'] = $program;
        $data['page2'] = \App\Models\AdmissionLetterPage2::where('program_id', $program_id)->first();
        return view('admin.programs.p2_create', $data);
    }

    public function save_ad_letter_page2(Request $request, $program_id){
        $validity = Validator::make($request->all(), ['content'=>'required']);
        if($validity->fails()){
            session()->flash('error', $validity->errors()->first());
            return back()->withInput();
        }

        \App\Models\AdmissionLetterPage2::updateOrInsert(['program_id'=>$program_id], ['content'=>$request->content]);
        return back()->with('success', 'Done');
    }

    public function program_change_report(Request $request){
        $year = \App\Models\Batch::find(Helpers::instance()->getCurrentAccademicYear());
        $programs = optional(json_decode($this->api_service->programs()))->data??null;
        // dd($programs);
        $data['title'] = "Program Change Report For ".$year->name??'';
        $data['reports'] = \App\Models\ProgramChangeTrack::join('application_forms', 'application_forms.id', '=', 'program_change_tracks.form_id')
            ->where('application_forms.year_id', $year->id)->get(['program_change_tracks.*'])
            ->each(function($rec)use($programs){
                $rec->former_program_name = $programs == null ? "" : optional(collect($programs)->where('id', $rec->former_program)->first())->name??'';
                $rec->current_program_name = $programs == null ? "" : optional(collect($programs)->where('id', $rec->current_program)->first())->name??'';
            });
        // dd($data['reports']);
        return view('admin.student.program_change_report', $data);
    }

    public function admitted_students(Request $request){
        
        $batch = Batch::find(Helpers::instance()->getCurrentAccademicYear());
        $programs = collect(json_decode($this->api_service->programs())->data);
        $data['title'] = "Admitted Students For {$batch->name} Accademic Year";
        $program_ids = ApplicationForm::where('year_id', $batch->id)->where('admitted', 1)->distinct()->pluck('program_first_choice')->toArray();
        $data['programs'] = $programs->filter(function($prog)use($program_ids){
            return in_array($prog->id, $program_ids);
        })->sortBy('name');
        if($request->program != null){
            $program = $programs->where('id', $request->program)->first();
            $data['program'] = $program;
            $data['title'] = ($program != null ? $program->name : '')." Admitted Students For {$batch->name} Accademic Year";
            $data['students'] = ApplicationForm::where('year_id', $batch->id)->where('program_first_choice', $request->program)->where('admitted', 1)->select(['name', 'dob', 'gender', 'pob', 'phone', 'program_first_choice', 'matric'])->distinct()->get();
        }
        // dd($data);
        return view('admin.student.admitted', $data);

    }


    // ENTRY QUALIFICATION REPORT
    public function entry_qualification_report(Request $request){
        $year = \App\Models\Batch::find(Helpers::instance()->getCurrentAccademicYear());
        $certificates = collect(json_decode($this->api_service->certificates())->data??[]);
        $data['certificates'] = $certificates;
        // dd(vars: $certificates);
        $data['title'] = "Entry Qualification Report For ".$year->name??'';

        if($request->certificate_id != null){
            $data['selected_certificate'] = $certificates->where('id', $request->certificate_id)->first();
            $data['title'] = "Entry Qualification Report For ".$certificates->where('id', $request->certificate_id)->first()->certi??'';
            $programs = collect(json_decode($this->api_service->programs())->data??[]);
            $program_structure = $this->api_service->school_program_structure()?->collect('data')??null;
            // dd($program_structure);
            $data['report'] = ApplicationForm::where('year_id', $year->id)
                ->where('entry_qualification', $request->certificate_id)
                ->get()
                ->each(function($rec)use($certificates, $programs, $program_structure){
                    $rec->certificate_name = $certificates == null ? "" : optional(collect($certificates)->where('id', $rec->entry_qualification)->first())->certi??'';
                    $rec->program_name = $programs->count() == 0 ? "" : $programs->where('id', $rec->program_first_choice)->first()?->name??'';
                    $rec->region_name = $rec->_region?->region??'';
                    $rec->school_name = $program_structure == null ? "" : $program_structure->where('id', $rec->program_first_choice)->first()->school??'';
                });
            // dd($data['reports']);
            if($request->download == 'csv'){
                $filename = 'entry_qualification_report_'.$data['selected_certificate']->certi.'_'.date('Ymd_His').'.csv';
                $headers = array(
                    "Content-type"        => "text/csv",
                    "Content-Disposition" => "attachment; filename=$filename",
                    "Pragma"              => "no-cache",
                    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                    "Expires"             => "0"
                );

                $columns = ['Name', 'Date of birth', 'Place of birth', 'Region of origin', 'Sex', 'Phone number', 'Email', 'School', 'Level', 'Option', 'Diplome'];
                $callback = function() use($data, $columns) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach ($data['report'] as $rec) {
                        $row['Name']  = $rec->name;
                        $row['Date of birth']    = $rec->dob;
                        $row['Place of birth']    = $rec->pob;
                        $row['Region of origin']    = $rec->region_name;
                        $row['Sex']    = $rec->gender;
                        $row['Phone number']    = $rec->phone;
                        $row['Email']    = $rec->email;
                        $row['School']    = $rec->school_name;
                        $row['Level']    = $rec->level;
                        $row['Option']    = $rec->program_name;
                        $row['Diplome']    = $rec->certificate_name;
                        fputcsv($file, array_values($row));
                    }
                    fclose($file);
                };
                return response()->streamDownload($callback, $filename, $headers);
            }
        }
        // dd($data['reports']);
        return view('admin.student.entry_qualification_report', $data);
    }


    // report of applications per degree type
    public function degree_applications_report(Request $request){
        // get degrees and school program structure from main system and match with admitted applications
        $degrees = collect(json_decode($this->api_service->degrees())->data??[]);
        
        if($request->degree_id != null){
            $program_structure = $this->api_service->school_program_structure()?->collect('data')??null;
            $certificates = collect(json_decode($this->api_service->certificates())->data??[]);
            $degree = $degrees->where('id', $request->degree_id)->first();
            $admitted_applications = ApplicationForm::whereNotNull('matricule')->whereNotNull('admitted_at')->where('degree_id', $request->degree_id)->where('year_id', Helpers::instance()->getCurrentAccademicYear())->get()
                ->map(function($rec)use($program_structure, $certificates){
                    return [
                        'name' => $rec->name,
                        'dob' => $rec->dob,
                        'pob' => $rec->pob,
                        'region' => $rec->_region->region,
                        'gender' => $rec->gender,
                        'phone' => $rec->phone,
                        'email' => $rec->email,
                        'level' => $rec->level,
                        'school' => $program_structure->where('id', $rec->program_first_choice)->first()?->school??'',
                        'program' => $program_structure->where('id', $rec->program_first_choice)->first()?->program??'',
                        'diplome' => $certificates->where('id', $rec->entry_qualification)->first()?->certi??''
                    ];
                });

        }

        return view('admin.student.degree_applications_report', [
            'title' => 'Degree Applications Report',
            'degrees' => $degrees,
            'degree' => $degree ?? null,
            'admitted_applications' => $admitted_applications ?? null
        ]);
    }
}
