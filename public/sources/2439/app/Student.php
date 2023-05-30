<?php

namespace App;

use App\BaseModel;
use App\User;
use App\CSVReader;
use App\Term;
use App\Setting;
use App\Course;
use App\Prerequisite;
use App\PrerequisiteCourses;
use App\Grade;
use App\GradeTerm;
use App\BranchingPreference;
use App\StudentResearch;
use App\Plan;
use App\TermStage;
use App\ChangeTrackPreference;
use App\StudentCredit;
use Auth;
use App\QuestionnaireTask;
use App\StudentSummerTermDecision;

use Illuminate\Support\Facades\DB;

function removeS($codes) {

    $finalCodes = [];
    foreach ($codes as $code) {
        if(substr($code, -1)=='s') $code = substr($code, 0, strlen($code)-1);
        $finalCodes[] = $code;
    }

    return $finalCodes;
}

class Student extends BaseModel
{
    const MILITARY_STATUS_DELAYED = 1;
    const MILITARY_STATUS_EXEMPTED = 2;
    const MILITARY_STATUS_TEMP_EXEMPTED = 3;
    const MILITARY_STATUS_DONE = 4;
    const MILITARY_STATUS_FOREIGN = 5;
    const MILITARY_STATUS_OTHER = 6;

    const FOREIGN_STATUS_SPECIAL_EXPENSE = 1;
    const FOREIGN_STATUS_SCHOLARSHIP = 2;
    const FOREIGN_STATUS_GRANT = 3;

    const MILITARY_EDUCATION_STATUS_NOT_YET = 0;
    const MILITARY_EDUCATION_STATUS_DONE = 1;

    const TRANSFER_FRESH = 1;
    const TRANSFER_TRANSFERED = 2;

    const SPECIALIZED_PROGRAM = 1;
    const INTERDISCIPLINARY_PROGRAM = 2;

    const STATUS_REGULAR            = 0x00000001;
    const STATUS_EXTERNAL           = 0x00000002;
    const STATUS_TRANSFERRED        = 0x00000004;
    const STATUS_DISMISSED          = 0x00000008;

    const STATUS_SUSPENDED          = 0x00000010;
    const STATUS_REPEAT             = 0x00000020;
    const STATUS_REGISTERED         = 0x00000040;
    const STATUS_GRADUATED          = 0x00000080;
    const STATUS_SEMI_GRADUATED     = 0x00000100;
    const STATUS_STUDYING           = 0x00000200;
    const STATUS_INVALID_RECORD     = 0x00000400;
    const STATUS_THESIS_REGISTERED  = 0x00000800;

    const STATUS_UNDERGRADUATE      = 0x00001000;
    const STATUS_POSTGRADUATE       = 0x00002000;
    const STATUS_NON_CREDIT_HOURS   = 0x00004000;
    const STATUS_CREDIT_HOURS       = 0x00008000;

    const STATUS_MODIFY_REQUEST     = 0x00010000;
    const STATUS_OFFICIAL_REQUEST   = 0x00020000;
    const STATUS_STUDIES_REQUEST    = 0x00040000;
    const STATUS_ACADEMIC_REQUEST   = 0x00080000;

    const STATUS_INTER_DISCIPLINARY = 0x00100000;
    const STATUS_SPECIALIZED        = 0x00200000;
    const STATUS_FINISHED           = 0x00400000;
    const STATUS_UNKNOWN            = 0x00800000;

    const STATUS_ADVANCE_REQUEST    = 0x10000000;
    const STATUS_RESERVED_FOR_NOT   = 0x40000000;

    const STATUS_FINAL_DISMISSAL    = 0x20000000;

    const grades = [
        ['A', 4, 90, null],
        ['A-', 3.7, 85, 90],
        ['B+', 3.3, 80, 85],
        ['B+', 3, 75, 80],
        ['B-', 2.7, 70, 75],
        ['C+', 2.3, 65, 70],
        ['C', 2, 60, 65],
        ['C-', 1.7, 55, 60],
        ['D+', 1.3, 53, 55],
        ['D', 1, 50, 53],
        ['F', 0, 0, 50],
    ];

    protected $fillable = ['credit'];

    public function department(){
        return $this->belongsTo('App\Department');
    }

    public function advisor(){
        return $this->belongsTo('App\User','advisor_id','id');
    }
   
    public function approvedBy(){
        return $this->belongsTo('App\User','approved_by','id');
    }

    public function user() {
        return $this->belongsTo('App\User','id','id');
    }

    public function plan() {
        return $this->belongsTo('App\Plan','last_plan_id','id');
    }

    public function level() {
        return $this->belongsTo('App\Level','last_level','id');
    }

    public function year() {
        return $this->belongsTo('App\Year','last_year_id','id');
    }

    public function birthCountry() {
        return $this->belongsTo('App\Country', 'birth_country_id','id')->withDefault(Country::where('id', 'EGY')->first()->attributes);
    }

    public function nationality() {
        return $this->belongsTo('App\Country', 'nationality_country_id', 'id')->withDefault(Country::where('id', 'EGY')->first()->attributes);
    }

    public function gradeTotal() {
        return $this->belongsTo(GradeTotal::class,'id', 'student_id');
    }

    public function studentResearch() {
        return $this->belongsTo('App\StudentResearch','id', 'student_id');
    }


    public function term() {
        return $this->belongsTo('App\Term','last_term_id', 'id');
    }

    public function firstTerm() {
        return $this->belongsTo('App\Term','first_term_id', 'id');
    }

    public function lastGradeTerm() {
        return $this->belongsTo('App\GradeTerm','last_grade_term_id', 'id');
    }

    //only for 2018 bylaw
    public function lastPublishedGradeTerm() {

        $lang = lang();

        $lastTerm = GradeTerm::select('grades_terms.*', "terms.".$lang."_name as term_name")
                    ->join('terms', 'terms.id', 'grades_terms.term_id')
                    ->where('grades_terms.published', 1)
                    ->where('grades_terms.student_id', $this->id)
                    ->orderBy('terms.start_date', 'DESC')
                    ->first();

        $firstTerm = GradeTerm::select("terms.".$lang."_name as term_name")
                    ->join('terms', 'terms.id', 'grades_terms.term_id')
                    ->where('grades_terms.published', 1)
                    ->where('grades_terms.student_id', $this->id)
                    ->orderBy('terms.start_date', 'ASC')
                    ->first();

        
        return (object)[
            'first_term_name' => $firstTerm->term_name,
            'last_term_name' => $lastTerm->term_name,
            'passed_hours' => $lastTerm->final_cumulative_passed_hours,
            'cumulative_gpa' => $lastTerm->cumulative_gpa,
             
            
        ];
    }


    public function gradesTerms($orderDirection = "ASC") {
        return $this->hasMany(GradeTerm::class)->select('grades_terms.*')->join('terms', 'terms.id', 'grades_terms.term_id')->where('published', 1)->orderBy('terms.start_date', $orderDirection);
    }

    public function isGradeTotalPublished() {
        $gradeTerm = GradeTerm::where('term_id', $this->last_term_id)->where('student_id', $this->id)->first();
        if($gradeTerm && $gradeTerm->published) return true;
        return false;
    }

    public function currentGradeTerm() {
        return $this->hasMany(GradeTerm::class)->select('grades_terms.*')->join('terms', 'terms.id', 'term_id')->orderBy('terms.start_date', 'DESC')->first();
    }

    public function yearsGrades() {

        $result = [];
        
        if($this->gradeTotal) {
            for ($i = 0; $i <= 4; $i++) {
                if(!empty(GradeType::find($this->gradeTotal['year' . $i . '_grade'])))
                    $result[$i] = (object)[
                        'total' => $this->gradeTotal['year' . $i . '_total'],
                        'max' => $this->gradeTotal['year' . $i . '_max'],
                        'grade' => GradeType::find($this->gradeTotal['year' . $i . '_grade'])->lang('name'),
                    ];
            }
        } else {
            $i = 0;
            foreach ($this->gradesTerms as $gradeTerm) {
                $result[$gradeTerm->year] = (object)[
                    'total' => $gradeTerm->total,
                    'max' => $gradeTerm->max_total,
                    'grade' => $gradeTerm->gradeType->lang('name'),
                ];
                $i++;
            }
        }

        return $result;
    }

    public function grades($year) {
        return $this->hasMany(Grade::class)->where('year', '=', $year)->orderBy('course_id');
    }

    public function gradesByTerm($term_id) {
        return $this->hasMany(Grade::class)->where('term_id', '=', $term_id)->orderBy('course_id');
    }

    public function distinctCoursesGrades() {
        $rows = $this->hasMany(Grade::class)
            ->join('terms', 'terms.id', 'grades.term_id')
            ->orderBy('terms.start_date')
            ->orderBy('course_id')->get();

        $grades = [];
        foreach ($rows as $grade) {
            if(array_key_exists($grade->course_id, $grades)) {
                $temp = $grades[$grade->course_id];
                unset($grades[$grade->course_id]);
                $grade->attempts = $temp->attempts+1;
            } else {
                $grade->attempts = 0;
            }

            $grades[$grade->course_id] = $grade;
        }

        return $grades;
    }

    public function finalGPAGrade(){
        return $this->belongsTo(CurrentGrade::class , 'id','student_id');
    }

    public function gradesByPlan($plan , $term_id) {
        return $this->hasMany(Grade::class)->where('plan_id', '=', $plan)->where('term_id','=',$term_id)->orderBy('course_id');
    }

    public function allGrades() {
        return $this->hasMany(Grade::class);
    }

    public function gradesByYear($year) {
        $rows = $this->hasMany(Grade::class)
            ->where('year', $year)
            ->join('terms', 'terms.id', 'grades.term_id')
            ->orderBy('terms.start_date')
            ->orderBy('course_id')->get();

        $grades = [];
        foreach ($rows as $grade) {
            if(array_key_exists($grade->course_id, $grades)) {
                $temp = $grades[$grade->course_id];
                unset($grades[$grade->course_id]);
                $grade->attempts = $temp->attempts+1;
            } else {
                $grade->attempts = 0;
            }

            $grades[$grade->course_id] = $grade;
        }

        return $grades;
    }


    public function terms() {
        return Term::select('*')->whereRaw(\DB::raw("id IN(SELECT DISTINCT term_id FROM grades WHERE student_id = $this->id)"))->orderBy('terms.start_date')->get();
    }

    public function getPlansByStudent() {
        $majorField = lang()."_major";
        $minorField = lang()."_minor";
        $nameField = lang()."_name";
        return $this->hasMany(Grade::class)
            ->select(DB::raw("CONCAT(plans.$majorField , ',',plans.$minorField) as plan_name"),"terms.$nameField as year_name",'terms.years','plans.id' ,'terms.id as term_id')
            ->leftJoin('plans','plans.id','=','grades.plan_id')
            ->leftJoin('terms','terms.id','=','grades.term_id')
            ->where('year','100')
            ->orderBy('terms.start_date')
            ->groupBy('grades.term_id');
    }

    public static function militaryStatusLabels() {
        return [
            Student::MILITARY_STATUS_DELAYED => __('tr.Military Status - Delayed'),
            Student::MILITARY_STATUS_EXEMPTED => __('tr.Military Status - Exempted'),
            Student::MILITARY_STATUS_TEMP_EXEMPTED => __('tr.Military Status - Temprorary Exempted'),
            Student::MILITARY_STATUS_DONE => __('tr.Military Status - Finished the Service'),
            Student::MILITARY_STATUS_FOREIGN => __('tr.Military Status - Foreign'),
            Student::MILITARY_STATUS_OTHER => __('tr.Other'),
        ];
    }

    public static function militaryEducationStatusLabels(){
        return [
            Student::MILITARY_EDUCATION_STATUS_NOT_YET => __('tr.Not Yet'),
            Student::MILITARY_EDUCATION_STATUS_DONE => __('tr.Done'),
        ];
    }

    public function militaryStatusLabel(){
        if(empty($this->military_status))
            return "";

        return Student::militaryStatusLabels()[$this->military_status];
    }

    public static function foreignStatusLabels(){
        return [
            Student::FOREIGN_STATUS_GRANT => __('tr.Grant'),
            Student::FOREIGN_STATUS_SPECIAL_EXPENSE => __('tr.Special Expenses'),
            Student::FOREIGN_STATUS_SCHOLARSHIP => __('tr.Scholarship'),
        ];
    }

    public function foreignStatusLabel(){
        if(empty($this->foreign_status))
            return "";

        return Student::foreignStatusLabels()[$this->foreign_status];
    }

    public function getStatusLabels(){
        return [
            1 => __('tr.Student Transferred'),
            2 => __('tr.Student Foreign'),
            0 => __('tr.Student Regular')
        ];
    }

    public function getStatusLabel(){
        if(empty($this->status))
            return "";

        return Student::getStatusLabels()[$this->status];
    }

    public static function getParentRelation(){
        return [
            'father' => __('tr.Father'),
            'mother' => __('tr.Mother'),
            'brother' => __('tr.Uncle'),
            'other' => __('tr.Other'),
        ];
    }

    public function studyGradeTerms($year = null, $order = "ASC") {
        if ($year == null)
            return $this->gradesTerms()->select(\DB::raw('plan_id, year, max(term_id) as term_id'))->groupBy('plan_id', 'year')->orderBy('year', $order);
        else
            return $this->gradesTerms()->select(\DB::raw('plan_id, year, max(term_id) as term_id'))->groupBy('plan_id', 'year')->where('year','=', $year)->orderBy('year');

    }

    public function lastStudyYears($year) {
        return $this->gradesTerms()
            ->select('plan_id', 'year', 'term_id', 'grade_type')
            ->join('terms', 'terms.id', 'grades_terms.term_id')
            ->where('year', $year)
            ->orderBy('terms.start_date', 'desc')
            ->first();
    }

    public static function add($code, $arName = null, $enName = null, $nationalId = null) {

        if(empty($arName))$arName = $code;
        if(empty($enName))$enName = $arName;

        $user = User::where("code", $code)->first();
        if(empty($user)){
            $user = new User();
            $user->code = $code;
            echo "<p>Add $user->code</p>";
        }

        $user->email = "$code@eng.asu.edu.eg";
        if($nationalId)$user->national_id = $nationalId;
        $user->en_name = $enName;
        $user->ar_name = $arName;
        $user->active = 1;
        $user->type = 3;
        $user->save();  

        $student = $user->student;
        if(empty($student)){
            $student = new Student();
            $student->id = $user->id;
        }
        
        $student->save();

        return $student;
    }

 public function contentsTypes() {
        return [
            'personal_photo_copy' => __('tr.Personal Photo'),
            'national_id_copy' => __('tr.National ID'),
            'english_certificate_copy' => __('tr.English Certificate'),
            'certification_copy' => __('tr.Certification'),
            'birth_certificate_copy' => __('tr.Birth Certificate'),
            
        ];
    }
    public function getGeneralInfomation(){
        return Student::select(\DB::raw("CONCAT(round((grades_total.total/(grades_total.year0_max+grades_total.year1_max+grades_total.year2_max+grades_total.year3_max+grades_total.year3_max))*100,2),'%') as student_Percent")
            , 'users.ar_name', 'users.en_name', 'plans.en_major', 'plans.ar_major', 'plans.en_minor', 'plans.ar_minor', 'terms.years', 'terms.ar_name as term_ar_name', 'terms.en_name as term_en_name'
            , 'years.ar_name as year_ar_name' , 'years.en_name as year_en_name','project_grade.en_name as project_en_grade','project_grade.ar_name as project_ar_grade','final_grade.en_name as final_en_grade'
            ,'final_grade.ar_name as final_ar_grade','grades_total.total',\DB::raw("grades_total.year0_max+grades_total.year1_max+grades_total.year2_max+grades_total.year3_max+grades_total.year3_max as final_total")
            ,'users.national_id as student_national_id', 'users.birth_date as student_birth_data','nationality.en_name as nationality_en_name' ,'nationality.ar_name as nationality_ar_name','ar_birth_city'
            , 'en_birth_city','countries.en_name as birth_country_en_name','countries.ar_name as birth_country_ar_name','users.code as student_code')
            ->leftJoin('users', 'users.id', '=', 'students.id')
            ->leftJoin('grades_total', 'grades_total.student_id', '=', 'students.id')
            ->leftJoin('terms', 'terms.id', '=', 'students.last_term_id')
            ->leftJoin('plans', 'plans.id', '=', 'students.last_plan_id')
            ->leftJoin('countries', 'countries.id', '=', 'students.birth_country_id')
            ->leftJoin('countries as nationality', 'countries.id', '=', 'students.nationality_country_id')
            ->leftJoin('years', 'years.id', '=', 'plans.year_id')
            ->leftJoin('grades_types as project_grade', 'project_grade.id', '=', 'grades_total.project_grade')
            ->leftJoin('grades_types as final_grade', 'final_grade.id', '=', 'grades_total.grade')
            ->where('students.id', $this->id)->first();
    }

    public static function Columns(){
        return[
          1 => __('tr.Name')  ,
          2 => __('tr.total')  ,
          3 => __('tr.Percent')  ,
          4 => __('tr.Email')  ,
          5 => __('tr.address')  ,
          6 => __('tr.grade')  ,
          7 => __('tr.Phone')  ,
          8 => __('tr.Nationality')  ,
          9 => __('tr.Minor')  ,
          10 => __('tr.Code')  ,
          11 => __('tr.Birthplace')  ,
          12 => __('tr.Birthdate')  ,
          13 => __('tr.NationalId')  ,
        ];
    }

    public function isCreditHours() {
        if($this->last_plan_id) {
            if($this->plan->getBylaw->credit_hours==1)
                return true;
        }

        return false;
    }

    public function getGradesTerms() {
        return Term::whereRaw(\DB::raw("id IN (SELECT DISTINCT term_id FROM grades WHERE student_id = $this->id)"))->orderBy('start_date')->get();
    }

    public function termCreditHours($termId) {
        return 5;
    }

    public function termCreditPoints($termId) {
        return 5;
    }

    public function comulativeCreditHours($termId) {
        return 5;
    }

    public function comulativeCoursePoints($termId) {
        return 5;
    }

    public function comulativeGPA($termId) {
        return 5;
    }

    public function certificates(){
        return $this->hasMany('App\StudentCertificate','student_id','id');
    }

    public static function schoolStudyType(){
        return [
            1 => __('tr.High School'),
            2 => __('tr.Industrial High School'),
            3 => __('tr.Foreign High School'),
            4 => __('tr.Technical Institute'),
            5 => __('tr.IG'),
            6 => __('tr.American Diploma'),
            7 => __('tr.STEM Schools'),
            8 => __('tr.Arabic Equivalent'),
         //   9 => __('tr.Foreign Equivalent'),
            10 => __('tr.Technical Equivalent'),
            11 => __('tr.Central Competition'),
            12 => __('tr.Nile Schools'),
            13 => __('tr.BAC (Baccaloria Francais)'),
            14 => __('tr.Abitur'),
            100 => __('tr.Other'),
        ];
    }

    public static function passportsTypes(){
        return [
            1 => __('tr.Diplomatic'),
            2 => __('tr.Ordinary'),
            3 => __('tr.Other'),
        ];
    }

    public static function currentAcademicYear() {
        $now = \Carbon\Carbon::now();
        if($now->month>6) {
            $currentYear = $now->year - 1;
        } 
        else {
            $currentYear = $now->year;
        }

        return $currentYear."/".($currentYear+1);
    }

    public static function importNPStudents($filePath) {

        $csv = CSVReader::open($filePath);
        if(empty($csv))dd("open failed");

        while ($row = $csv->next()) {

            $plan = Plan::find($row->plan_id);

            $student = Student::add($row->student_code, $row->ar_name, $row->en_name, $row->national_id);

            $user = $student->user;
            if(!empty($row->en_name)) $user->en_name = $row->en_name;
            if(!empty($row->ar_name)) $user->ar_name = $row->ar_name;
            if(empty($user->national_id))$user->national_id = $row->national_id;
            if(empty($user->mobile))$user->mobile = $row->mobile;
            if(empty($user->email_alt))$user->email_alt = $row->email_alt;
            $user->updateFTS();
            
            if(empty($student->graduated))$student->graduated = $row->graduated;
            $student->last_plan_id = $plan->id;
            $student->bylaw = $plan->bylaw;
            $student->save();
        }

        dd("done nRows:$csv->nRows");
    }

    public function certificateGradeTypeLabel($gradeType) {
        if($gradeType->id==0)
            return __("tr.Succeeded");
        else if($gradeType->id==7)
            return GradeType::find(6)->lang('name');
        else if($this->graduated==1 && $gradeType->id==5)
            return __("tr.Pass");

        return $gradeType->lang('name');
    }

    public function certificateGradeNoteLabel($gradeNote) {
        if($gradeNote->id==0)
            return __("tr.Succeeded");
        if($this->graduated==1 && $gradeNote->id==5)
            return __("tr.Pass");
        return $gradeNote->lang('name');
    }

    public function projectGPA() {

        $grade = (object)[];
        
        $grade->grade_gpa = $grade->credit_hours = $grade->credit_points = 0;

        if($this->gradeTotal && $this->gradeTotal->project_grade_gpa) {

            $grade->grade_gpa = $this->gradeTotal->project_grade_gpa;
            $grade->credit_hours = $this->gradeTotal->project_credit_hours;
            $grade->credit_points = $this->gradeTotal->project_credit_points;
            return $grade;
        }

        $projectGrades = Grade::select('grades.*')->join('courses', 'courses.id', 'grades.course_id')->where('student_id', $this->id)->where('courses.project', 1)->get();

        foreach ($projectGrades as $projectGrade) {
            $grade->credit_points += $projectGrade->calculateGPA()*$projectGrade->course->credit_hours;
            $grade->credit_hours += $projectGrade->course->credit_hours;
        }
        if($grade->credit_hours<=0)return null;

        $grade->grade_gpa = $grade->credit_points/$grade->credit_hours;
        return $grade;
    }

    public function isEgyptian() {
        return ($this->nationality_country_id=="EGY" || empty($this->user->student->nationality_country_id));
    }

    public function canChangeTrack() {

        $nextTermID = Setting::value('next_term_id');
        if(!$nextTermID)
            return false;

        $nextTerm = Term::find($nextTermID);
        if(!$nextTerm)
            return false;

        if($this->isGraduated()) return false;  
        
        $gradesTerm = $this->gradesTerms('DESC')->first();
        
        if(empty($gradesTerm) || $gradesTerm->grade_type == 7) return false; //Dismissed

        $stage = $nextTerm->stage("change_track", $this->bylaw);

        if(empty($stage) || !$stage->isOpen()) return false;

        //Make sure that, this the first attempt to change the student study track
        $oldRequest = ChangeTrackPreference::where('term_id', '!=', $nextTermID)->where('user_id', $this->id)->first();
        if($oldRequest)
            return false;

        $count = BaseModel::evalSQLValue("SELECT count(*) as value  FROM change_track_rules WHERE from_plan_id = $this->last_plan_id;");
        return ($count>0);
    }

    public function canSelectTrack() {

        $nextTermID = Setting::value('next_term_id');
        if(!$nextTermID)
            return false;

        $nextTerm = Term::find($nextTermID);
        if(!$nextTerm)
            return false;

        if($this->isGraduated()) return false;

        //check the term type [if not Fall then only 2018 bylaw allowed to select track]
        if($nextTerm->term_type != Term::FALL){
            if($this->bylaw != 'UG2018')
                return false;
        }


        $stage = $nextTerm->stage("preferences", $this->bylaw);

        $gradesTerm = $this->lastGradeTerm;

        if($gradesTerm == null) return false; //New Freshman
        
        if(!$this->isCreditHours()){

            if(empty($stage) || !$stage->isOpen()) return false;
                
            if(empty($gradesTerm) || !$gradesTerm->isSucceeded()) return false; //for 2003 bylaw students
        }
        elseif($this->isCreditHours()){

            if(empty($stage) || !$stage->isOpen()) return false;
                
           // $preferences_min_required_credit_hours = Setting::value("preferences_min_required_credit_hours");
           // if(empty($gradesTerm) || $gradesTerm->final_cumulative_passed_hours < $preferences_min_required_credit_hours) return false; //for 2018 bylaw students
             $allPlans = Plan::select("plans.*")
                       ->join("branching_rules", "plans.id", "branching_rules.to_plan_id")
                       ->where("branching_rules.from_plan_id", $this->last_plan_id)
                       ->where("branching_rules.required_credit_hours",'<=',$gradesTerm->final_cumulative_passed_hours)
                       ->get();
                       
                     if(empty($allPlans)||$allPlans == null || count($allPlans)==0){
                         return false;
                     }

                       
        } 
        else {
           return false;
        }

        $count = BaseModel::evalSQLValue("SELECT count(*) as value  FROM branching_rules WHERE from_plan_id = $this->last_plan_id;");

        return ($count>0);
    }


    public function canTransfer()
    {
        $nextTermID = Setting::value('next_term_id');
        if(!$nextTermID)
            return false;

        $nextTerm = Term::find($nextTermID);
        if(!$nextTerm)
            return false;

        if($this->isGraduated()) return false;

        $stage = $nextTerm->stage("transfer_between_programs", $this->bylaw);

        $gradesTerm = $this->lastGradeTerm;

        if(!$this->isCreditHours()){

            if(empty($stage) || !$stage->isOpen()) return false;
                
            if(empty($gradesTerm) || !$gradesTerm->isSucceeded()) return false; //for 2003 bylaw students
        }
        elseif($this->isCreditHours()){

            if(empty($stage) || !$stage->isOpen()) return false;
                
            $preferences_min_required_credit_hours = Setting::value("preferences_min_required_credit_hours");
            
            if(empty($gradesTerm) || $gradesTerm->final_cumulative_passed_hours  < $preferences_min_required_credit_hours) 
                return false; //for 2018 bylaw students
             
        } 
        else {
           return false;
        }

        $count = BaseModel::evalSQLValue("SELECT count(*) as value  FROM branching_rules WHERE from_plan_id = $gradesTerm->plan_id;");

        return ($count>0);

    }

    

    public function checkPrerequisites($plan) {

        //get all Prerequisites for this plan
        $prerequisites = Prerequisite::where('plan_id', $plan->id)->pluck('course_code')->toArray();

        if(empty($prerequisites) || count($prerequisites)==0)
            return true;

        foreach($prerequisites as $prerequisite) {
            
            $courseCode = "%".substr($prerequisite, 0, 3)."%".substr($prerequisite, 3)."%";
            $grades = Grade::select('*')
            ->join('courses', 'courses.id', 'grades.course_id')
            ->where('grades.student_id', $this->id)
            ->where('grades.grade_letter', '!=', 'F')
            ->where('grades.grade_letter', '!=', 'I')
            ->where('grades.grade_letter', '!=', 'W')
            ->where('grades.grade_letter', '!=', 'E')
            ->where('courses.code', 'like', $courseCode)
            ->get();
 
            if(!$grades || empty($grades) || count($grades) == 0){

            $studies = Study::select('*')
            ->join('courses', 'courses.id', 'studies.course_id')
            ->where('studies.user_id', $this->id)
            ->where('studies.grade_letter', '!=', 'F')
            ->where('studies.grade_letter', '!=', 'I')
            ->where('studies.grade_letter', '!=', 'W')
            ->where('courses.code', 'like', $courseCode)
            ->get();
            
            if(!$studies || empty($studies) || count($studies) == 0)
                return false;
            }
            //make sure that the student succeeded this course if not break and return false
            foreach($grades as $grade){

                if($grade->failed()) 
                    return false;
                /*if($grade->withdrawed()) 
                    return false;*/
                   
            }

            
        }
        return true;
    }

    /**
     * A function to check the prerequisites of a course
     */
    public function checkPrerequisitesCourse($course_id) {

        //get all Prerequisites for this course
        $prerequisites = PrerequisiteCourses::where('course_id', $course_id)->get();
         
        if(empty($prerequisites) || count($prerequisites)==0)
            return true;
            
        foreach($prerequisites as $prerequisite) {
            
            $grades = Grade::select('*')
                ->join('courses', 'courses.id', 'grades.course_id')
                ->where('grades.student_id', $this->id)
                ->where('courses.id', 'like', $prerequisite->pre_course_id)
                ->get();
 
            if(!$grades || empty($grades) || count($grades) == 0)
                return false;
           
            //make sure that the student succeeded this course if not break and return false
            foreach($grades as $grade){

                if(!$grade->failed()) 
                    return true;
               
            }

            return false;
        }

        return false;
    }

    
    //check the date to dispaly the selection result
    public function canDisplaySelectTrackResult() {
        if($this->isCreditHours()){
            $term = Term::findOpenStudentsPreferencesChResult();
            if(empty($term)) 
               return false;
           
        }else{
            $term = Term::findOpenStudentsPreferencesResult();
            if(empty($term)) 
                return false;
        }

    

        $preferences = BranchingPreference::where('user_id', $this->id)->get();
        if(!$preferences || count($preferences) == 0)
            return false;
 
        return true;
    }

    public function canRegister() {

        $currentTermId = Setting::value("current_term_id");
        if($this->last_term_id!=$currentTermId || $this->isDismissed() || $this->isGraduated() || $this->isSemiGraduated())
            return false;

        return true;
    }

    //check the course registration date
    public function canRegisterCourses(){  

        //temp check
        if($this->last_plan_id != 179 &&
           $this->last_plan_id != 339 &&
           $this->last_plan_id != 338 &&
           $this->last_plan_id != 337 &&
           $this->last_plan_id != 336
        ){
            return false;
        }
        
        if($this->isCreditHours()){

            $term = Term::findOpenStudentsCoursesRegistration();
             
            if(empty($term)) 
                return false;
            else
                return true;
           
        }
        return false;
    }

    /**
     * A function to return the max allowed courses and credit hours of a student to register
     */
    public function maxAllowedCoursesAndCHR(){

        $gradesTerm = $this->gradesTerms('DESC')->first();
       
        if(!$gradesTerm)
            return ['courses'=> 0, 'chr'=> 0];
        
        $gpa = $gradesTerm->cumulative_gpa;
         
        if($gpa < 2){
            $maxCoursesAndCHR = ['courses'=> 5, 'chr'=> 14];
        }elseif($gpa >= 2 && $gpa < 3){
            $maxCoursesAndCHR = ['courses'=> 6, 'chr'=> 17];
        }elseif($gpa >= 3){
            $maxCoursesAndCHR = ['courses'=> 7, 'chr'=> 21];
        }else{
            $maxCoursesAndCHR = ['courses'=> 0, 'chr'=> 0];
        }

        return $maxCoursesAndCHR;

    }

    public function isNewStudent($term_id = NULL){

        $term_id = ($term_id)?$term_id:$this->last_term_id;

        if($this->first_term_id == $term_id )
            return true;

        $term = Term::findOrFail($term_id);
        if($term->term_type == Term::ADMISSION)
            return true;

        return false;
    }


    /**
     * A function to check if the student is new freshman or not based on the grades table
     */
    public function firstTime()
    {
        if($this->last_term_id == $this->first_term_id)
            return true;
        
        //if the last term is admission term, then the student in new.
        $term = Term::findOrFail($this->last_term_id);
        if($term->term_type == Term::ADMISSION)
            return true;
        
        $grades = Grade::where('student_id', $this->id)
                       ->leftJoin('terms', 'terms.id', 'grades.term_id')
                       ->get();  
 
        if(count($grades) <= 0 && ($this->last_plan_id == 179 || $this->last_plan_id == 180 || ($this->last_plan_id >= 192 && $this->last_plan_id <= 202))){
           return true;
        }elseif(count($grades) > 0 && $this->last_plan_id == 179){
 
            foreach($grades as $grade){
 
                if($grade->term_type != Term::ADMISSION)
                   return false;

            }
 
            return true;

        }elseif(count($grades) > 0 && $this->last_plan_id == 179){
            foreach($grades as $grade){

                if($grade->term_id == 125){  
                    return true;
                }
            }
        }

       return false;
    }


    public function firstFallTerm()
    {
        $grades = GradeTerm::leftJoin('terms', 'grades_terms.term_id', 'terms.id')
                            ->where('terms.term_type', Term::FALL)
                            ->where('grades_terms.student_id', $this->id)
                            ->get();

        $makasa = GradeTerm::leftJoin('terms', 'grades_terms.term_id', 'terms.id')
                            ->where('terms.term_type', 'Transfer')
                            ->where('grades_terms.student_id', $this->id)
                            ->get();
                            

            
        if(!$grades || empty($grades) || $grades->count() == 0){
          if(!$makasa || empty($makasa) || $makasa->count() == 0)
            return true;
            else
            return false;
        }else{
                return false;
        }
    }


    public function getBylaw(){
        return $this->belongsTo('App\Bylaw', 'bylaw', 'code');
    }    

    public function takenCourses() {
        $termsIDs = GradeTerm::distinct('term_id')->where('student_id', $this->id)->where('published', 1)->pluck('term_id')->toArray();
        $coursesIDs = Grade::distinct('course_id')->where('student_id', $this->id)->whereIn('term_id', $termsIDs)->pluck('course_id')->toArray();        
        return Course::whereIn('id', $coursesIDs)->where('short_name', 'NOT LIKE', 'CRN%')->orderBy('short_name')->get();
    }

    public function courseGrades($courseId) {
        return Grade::select('grades.*')
                ->where('grades.course_id', $courseId)
                ->where('grades.student_id', $this->id)
                ->join('terms', 'grades.term_id', 'terms.id')
                ->join('grades_terms', function($join) {
                    $join->on('grades_terms.student_id', 'grades.student_id');
                    $join->on('grades_terms.term_id', 'grades.term_id');
                    $join->where('grades_terms.published', '1');
                })
                ->orderBy('terms.start_date', 'DESC')
                ->get();
    }

    public function allPlansIds() {
        $plan_id = $this->last_plan_id;
        while ($plan_id!==null) {
            $plansIds[] = $plan_id;
            $plan = Plan::find($plan_id);
            $plan_id = $plan->parent_id;
        }

        return $plansIds;
    }

    public static function transferTypes(){
        return [
            Student::TRANSFER_FRESH => __("tr.Fresh Student"),
            Student::TRANSFER_TRANSFERED => __("Transfered from Other Faculty or Institute"),
        ];
    }


    /**
     *
     * A function to get the student program
     */
    public function getStdProgram(){

        //get the student paln id
        $plan_id = $this->last_plan_id;

        //get the program
        $plan = Plan::find($plan_id);

        if(!$plan)
            return null;

        if($this->bylaw == 'UG2018'){

            if($plan->en_program == 'Specialized Programs')
                return Student::SPECIALIZED_PROGRAM;
            elseif($plan->en_program == 'Inter-Disciplinary Programs')
                return Student::INTERDISCIPLINARY_PROGRAM;
            else
                return null;
        }else{

            $code = strtolower($this->user->code);

            if(strpos($code, 'p') !== false){
                return Student::INTERDISCIPLINARY_PROGRAM;
            }else{
                return Student::SPECIALIZED_PROGRAM;
            }

        }
    }


  

    public function canApplyToQuestionnaire() {

        if($this->isChep()) return false;
        
        $term = Setting::currentTerm();
        if(!$term) return false;        

        return $term->isQuestionnaireOpen();
    }

    public function canRegisterResearchPlan() {
        if($this->getBylaw->type!="postgraduate") return false;
        if($this->research && $this->research->isSubmitted()) return false;
        return $this->level->can_register;
    }

    public function research() {
        return $this->belongsTo('App\StudentResearch', 'id', 'student_id');
    }

    public function canChangeCourses() {
        if($this->registered!=0)return false;
        if($this->advising_status!=0)return false;
        //Freshmen has fixed courses and sections
        if($this->bylaw=='UG2018' && $this->last_plan_id==179)return false;
        if($this->bylaw=='UG2018' && (strpos($this->plan->short_name,"UG18PRID")===0)||strpos($this->plan->short_name,"UG18ID")===0)return false;
        if($this->bylaw=='UG2018')return false;
        if($this->bylaw=='UG2007')return false;
        if($this->bylaw=='UG2013')return false;
        if($this->bylaw=='UG2003')return false;
        if($this->bylaw=='PG2008')return false;

        return true;
    }

    public function termStage($stageGroup, $all = null) {
         
        $query = TermStage::whereRaw('start_date <= NOW()')
                            ->whereRaw('DATE_ADD(end_date, INTERVAL 1 DAY) >= NOW()')
                            ->whereNotIn('stage_code', ['internal_advise'])
                            ->where('stage_group', $stageGroup)
                            ->where('bylaw_code', $this->bylaw)
                            ->where('term_id', $this->last_term_id);
        if($all == 'all')               
            $result = $query->get();
        else
            $result = $query->first();


        return $result;
    }
    
    public function termStageWithStageCode($stageCode) {
        return TermStage::whereRaw('start_date <= NOW()')
        ->whereRaw('DATE_ADD(end_date, INTERVAL 1 DAY) >= NOW()')
        ->where('stage_code', $stageCode)
        ->where('bylaw_code', $this->bylaw)
        ->first();
    }

    public function isStudiesRemoveAllowed() {
        $termStage = $this->termStage('studies');
        if(empty($termStage))return false;
        return true;
    }

    public function isStudiesEditAllowed() {
        $termStage = $this->termStage('studies', 'all');
        if(empty($termStage))return false;

        $gpa = ($this->lastGradeTerm)?$this->lastGradeTerm->final_cumulative_gpa:NULL;
        $plan_id = $this->last_plan_id;
        $freshmen = ($this->first_term_id == $this->last_term_id)?1:0;
        
        foreach($termStage as $row){
            if($row->stage_code=="registration" || $row->stage_code=="add_drop") {
                if( (($gpa >= $row->gpa_range_from && $gpa < $row->gpa_range_to) || $gpa == NULL) && 
                    ($plan_id === $row->plan_id || $row->plan_id === 0) && 
                    $freshmen === $row->freshmen
                    ){
                    return true;
                }
            }
        }     
         
        return false;
    }

    public function isStudiesAddAllowed() {
        
        $termStage = $this->termStage('studies', 'all');
        if( empty($termStage) ) return false;
        if( $this->isPostgraduate() ) return true;
            
        $gpa = ($this->lastGradeTerm) ? $this->lastGradeTerm->final_cumulative_gpa : NULL;
        $plan_id = $this->last_plan_id;
        $freshmen = ($this->first_term_id == $this->last_term_id) ? 1 : 0;
        
        foreach($termStage as $row){
            if($row->stage_code=="registration" || $row->stage_code=="add_drop") {
                if( (   ( $gpa == NULL ) || 
                        ( $gpa >= $row->gpa_range_from && $gpa < $row->gpa_range_to ) ) && 
                    ( $plan_id === $row->plan_id || $row->plan_id === 0 ) && 
                    $freshmen === $row->freshmen ) {
                    return true;
                }
            }
        }

        return false;
    }

    public function stageAddTitle() {
        $termStage = $this->termStage('studies');
        if(empty($termStage))return "";
        if($termStage->stage_code=="registration") return __('tr.Register');
        else if($termStage->stage_code=="add_drop") return __('tr.Add');
        return "";
    }

    public function stageRemoveTitle() {
        $termStage = $this->termStage('studies');
        if(empty($termStage))return "";
        if($termStage->stage_code=="registration") return __('tr.Remove');
        else if($termStage->stage_code=="add_drop") return __('tr.Drop');
        else if($termStage->stage_code=="withdraw") return __('tr.Withdraw');
        return "";
    }

    public function isRegistered() {
        return (($this->status&Student::STATUS_REGISTERED)!=0);
    }

    public function isGraduated() {
        return (($this->status&Student::STATUS_GRADUATED)!=0);
    }

    public function isSemiGraduated() {
        return (($this->status&Student::STATUS_SEMI_GRADUATED)!=0);
    }

    public function isPostgraduate() {
        return (($this->status&Student::STATUS_POSTGRADUATE)!=0);
    }

    public function isDismissed() {
        return (($this->status&Student::STATUS_DISMISSED)!=0);
    }

    public function isFinalDismissed() {
        return (($this->status&Student::STATUS_FINAL_DISMISSAL)!=0);
    }

    public function isSuspended() {
        return (($this->status&Student::STATUS_SUSPENDED)!=0);
    }

    public function isStudying() {
        return (($this->status&Student::STATUS_STUDYING)!=0);
    }

    public function hasModificationRequest() {
        return (($this->status&Student::STATUS_MODIFY_REQUEST)!=0);
    }

    public function hasOfficialRequest() {
        return (($this->status&Student::STATUS_OFFICIAL_REQUEST)!=0);
    }

    public function hasStudiesRequest() {
        return (($this->status&Student::STATUS_STUDIES_REQUEST)!=0);
    }

    public function hasAcademicRequest() {
        return (($this->status&Student::STATUS_ACADEMIC_REQUEST)!=0);
    }

    public static $cashedStudentsByID = [];
    public static function getCashedByID($id) {
        
        $key = $id;
        if(array_key_exists($key, Student::$cashedStudentsByID))
            return Student::$cashedStudentsByID[$key];

        $student = Student::find($id);

        Student::$cashedStudentsByID[$key] = $student;

        return $student;
    }

    public function updatedStudiesRequest() {
        if(Study::where('term_id', $this->last_term_id)
        ->where('user_id', $this->id)
        ->whereRaw('status&'.Study::requestMask())
        ->exists()) {
            $this->status |= Student::STATUS_STUDIES_REQUEST;
            $this->save();
        }
        else {
            $this->status &= ~Student::STATUS_STUDIES_REQUEST;
            $this->save();
        }
    }

    function hasPendingRequest() {
        $termID = Setting::value('current_term_id');
        return Study::where('term_id', $termID)->where('user_id', $this->id)->where('status', '&', Study::requestMask())->exists();
    }

    private $successCoursesCodes = null;

    public function canTakeCourse($coursePrerequisite) {

        if($this->successCoursesCodes===null) {
            
            $this->successCoursesCodes = Grade::distinct('courses.short_name')
            ->join('courses', 'courses.id', 'grades.course_id')
            ->where('grades.student_id', $this->id)
            ->whereIn('grades.plan_id', $this->allPlansIDs())
            ->where('grades.status', '&', Study::STATUS_SUCCEEDED)
            ->pluck('courses.short_name as code')
            ->toArray();

            $this->successCoursesCodes = removeS($this->successCoursesCodes);
        }

        $prerequisites = $coursePrerequisite->prerequisites;
        $codes = str_replace("AND", ",", $prerequisites);
        $codes = str_replace("OR", ",", $codes);
        $codes = str_replace("(", " ", $codes);
        $codes = str_replace(")", " ", $codes);
        $codes = str_replace(" ", "", $codes);
        $codes = explode(",", $codes);
        $codes = removeS($codes);

        $finalPrerequisites = $prerequisites;
        foreach ($codes as $code) {
            $finalPrerequisites = str_replace($code, in_array($code, $this->successCoursesCodes)?"1":"0", $finalPrerequisites);
        }
        $finalPrerequisites = str_replace("s", "", $finalPrerequisites);
        $finalPrerequisites = str_replace("OR", "||", $finalPrerequisites);
        $finalPrerequisites = str_replace("AND", "&&", $finalPrerequisites);

        return eval("return ($finalPrerequisites)?true:false;");
    }

    /**
     * A function to check if the stuent can apply an Grades Recheck request
     */
    public function canRequestGradesRecheck(){

        //check the dates
        if(TermStage::isAnyOpen('grades_recheck', $this->bylaw))
            return true;

        return false;
    }

    public function maxCoursesCreditHours() {
        return 1000;
    }

    public function maxCourses() {

        if($this->lastGradeTerm) {

            if($this->lastGradeTerm->cumulative_gpa<2) {
                return 5;
            }
            else if($this->lastGradeTerm->cumulative_gpa<3) {
                return 6;
            }
            
            return 7;
        }

        return 5;
    }

    public function checkCreditLimits($creditHours, $nCourses) {
        
        if($creditHours>$this->maxCoursesCreditHours())return false;
        if($nCourses>$this->maxCourses())return false;

        return true;
    }

    public function studentTraining()
    {
        return $this->hasMany(StudentTraining::class);
    }

    /**
     * 
     */
    public function reachedTrainingsWeeksLimit($term_id){

        $weeks = Training::select('trainings.num_of_weeks')
                            ->join('students_trainings', 'trainings.id',  'students_trainings.training_id')
                            ->where('trainings.target', 'Students')
                            ->where('trainings.term_id', $term_id)
                            ->where('students_trainings.student_id', $this->id)
                            ->where('students_trainings.status', 1) //only the accepted
                            ->sum('num_of_weeks');

        if($weeks >= 8)
            return true;
        else
            return false;
    }
    
    public function canChangeOnlineExamMethod() {

        //Use setting or other way to allow changing the online exam preferences (at faculty, at home)

        return false;
    }

    public function askForSummerTermDecision(){

        //1- get the last inserted summer term [current or upcomming]
        $summerTerm = Term::where('term_type', Term::SUMMER)->orderBy('id', 'DESC')->first();
 
        //2- check the dates in the stages table
        $stage = $summerTerm->stage("summer_term_decision", $this->bylaw);
        if(empty($stage) || !$stage->isOpen()) return false;
 
        //3- check if the student already decided
        $studentDecision = StudentSummerTermDecision::where('term_id', $summerTerm->id)->where('student_id', $this->id)->first();
        if($studentDecision) return false;

        return true;
    }

    public function canApplyExamSpecialRequests(){
        
        $term = Term::currentExamTerm();

        $stage = $term->stage("exam_special_requests", $this->bylaw);
        if(empty($stage) || !$stage->isOpen()) return false;


        return true;

    }

    public function isThisMyInfo(){

        if(auth()->id() == $this->id)
            return true;


        return false;
       
    }

    public function canAccessArchAptitudeTest(){ 

        //check the dates
        $termStage = $this->termStageWithStageCode('arch_aptitude_test');
        if(empty($termStage))
            return false;

        //check if the test taken before
        $course_id = Setting::value('arch_aptitude_test_course_id');
        $study = Study::where('user_id', $this->id)->where('course_id', $course_id)->first();
        if($study && $study->total >= 70)
            return false;


        return true;
    }

    public function canTransferToSpecialized() {

        if($this->bylaw=='UG2018') {
            return $this->isChep();
        }

        return false;
    }

    public function canTransferToChep() {

        if($this->bylaw=='UG2003' || $this->bylaw=='UG2018') {
            return $this->isChep();
        }

        return false;
    }

    public function isChep() {

        if($this->bylaw=='UG2007' || $this->bylaw=='UG2013') {
            return true;
        }

        if($this->bylaw=='UG2018' && $this->plan) {
            if($this->plan->short_name=="UG18PRID") return true;
            if(strpos($this->plan->short_name, "UG18ID")===0) return true;
        }

        return false;
    }

    public function canApplyExternalRegistrationRequest(){

        if($this->isCreditHours())
            return false;

        if($this->isFinalDismissed())
            return false;

        $gradesTerm = $this->lastgradeterm;

        if($gradesTerm){
            if($gradesTerm->grade_type != 7) return false;
        }else{
            return false;
        }
            

        return true;
    }

    public function forceToChangeBylaw(){

        $founded = Setting::where('value', $this->last_plan_id)->where('name', 'plan_to_convert_to_2018_bylaws')->first();
        if($founded)
            return true;

        return false;
    }

    private $savedGrades = [];

    public function mergeGrade($newGrade) {

        if($this->getBylaw->useLastGrade()) return $newGrade;

        $savedGrade = null;
        if(array_key_exists($newGrade->course_id, $this->savedGrades)) {
            $savedGrade = $this->savedGrades[$newGrade->course_id];
        }

        $course = $newGrade->course;
        if($savedGrade===null || $newGrade->grade_gpa>$savedGrade->grade_gpa) {
            $this->savedGrades[$newGrade->course_id] = $newGrade;
            return $newGrade;
        }        

        return $savedGrade;
    }

    public function courseGradesInfo($courseID, $beforeTermID = null) {

        $course = Course::find($courseID);
        
        $query = Grade::select('grades.*')
        ->join('terms', 'terms.id', 'grades.term_id')
        ->join('courses', 'courses.id', 'grades.course_id')
        //->where('courses.short_name', 'LIKE', $course->plainCode().'%')
        ->whereRaw("TRIM(TRAILING 's' FROM courses.short_name) = TRIM(TRAILING 's' FROM '$course->short_name')")
        ->where('courses.bylaw', $course->bylaw)
        ->where('student_id', $this->id);        

        if($beforeTermID) {
            $term = Term::find($beforeTermID);
            $query->where('terms.start_date', '<', $term->start_date);
        }
        
        $grades = $query->orderBy('terms.start_date', 'ASC')->get();

        $isCreditHours = $this->isCreditHours();

        $gradesInfo = (object)[];
        $activeGrade = null;
        $gradesInfo->grades = [];        
        $gradesInfo->taken = false;
        $gradesInfo->failed = false;
        $gradesInfo->succeeded = false;
        $gradesInfo->incomplete = false;
        $gradesInfo->latestGrade = null;
        $activeGrade = null;
        foreach ($grades as $grade) {
            $gradesInfo->grades[] = $grade;
            if(!$grade->isTaken()) continue;
            if(!$isCreditHours && !Plan::isOneOfParentsIDs($grade->plan_id, $this->last_plan_id)) continue;
            if($isCreditHours && $this->bylaw!=$grade->plan->bylaw) continue;
            $activeGrade = $this->mergeGrade($grade);
        }

        $gradesInfo->activeGrade = $activeGrade;
        if($gradesInfo->activeGrade) {
            $gradesInfo->incomplete = $activeGrade->isIncomplete();            
            $gradesInfo->failed = $activeGrade->isFailed();
            $gradesInfo->succeeded = $activeGrade->isSucceeded();                
            $gradesInfo->taken = true;
            $summery = $activeGrade->summery();
            $gradesInfo->display = '-';
            if($gradesInfo->succeeded) $gradesInfo->display = __("tr.Succeeded");
            else if($gradesInfo->failed) $gradesInfo->display = __("tr.Failed");
        }

        $gradesInfo->grades = array_reverse($gradesInfo->grades);

        return $gradesInfo;
    }

    /*
        
     */
    public function getPaymentStartDateField()
    {
        if(!$this->isGraduated()){
            
            if($this->isPostgraduate()){
                return 'pg_std_payment_start_date';
            }elseif($this->isCreditHours() && $this->isNewStudent() && !$this->isChep()){
                return 'ug_2018_sp_new_std_payment_start_date';
            }elseif($this->isCreditHours() && !$this->isNewStudent() && !$this->isChep()){
                return 'ug_2018_sp_old_std_payment_start_date';
            }elseif($this->isCreditHours() && $this->isNewStudent() && $this->isChep()){
                return 'ug_ip_new_std_payment_start_date';
            }elseif($this->isCreditHours() && !$this->isNewStudent() && $this->isChep()){
                return 'ug_ip_old_std_payment_start_date';
            }else{
                return 'ug_2003_sp_old_std_payment_start_date';
            }
        }else{
            return NULL;
        }
    }

    public function canApplyTakafulRequest()
    {
        $takaful_request_status = Setting::value('takaful_request_status');
        if(!$takaful_request_status)
            return false;

        if($takaful_request_status == 'opened')
            return true;

        return false;
    }    

    public function getPlanType(){
       
        $plan = Plan::findOrFail($this->last_plan_id);

        return $plan->getType();
    }

    public function externalProgramRegisteration() {
        
        return ExternalProgramStudent::where('external_programs_students.student_id', $this->id)->first();
    }
    public function externalProgramRegisterationState() {
        
        return ExternalProgramStudent::where('external_programs_students.student_id', $this->id)
        ->whereNotIn('external_programs_students.status',[4,5])       
        ->first();
    }
    public function canApplyToExternalProgram() {
        
        return ($this->isChep() && empty($this->externalProgramRegisteration()));
    }

    public function tuition_fees_exempt()
    {
        return $this->hasMany(TuitionFeeExemption::class);

    }

    public function isTuitionFeesTotallyExempt(){
        foreach($this->tuition_fees_exempt as $row){
            if($row->term_id == -1)
                return true;
        }
        return false;
    }

    public function calcTermCreditHours($term_id){
        
        $grades = $this->allGrades()->where('term_id', $term_id)->get();
        $credit_hours = 0;
        foreach($grades as $grade){
            if($this->grade_letter != 'W')
                $credit_hours += $grade->course->credit_hours;
        }

        return $credit_hours;
    }

    public function financialCredit() {

        $credits = StudentCredit::select('id', 'type', 'amount')
        ->where('student_id', $this->id)
        ->get();

        $total = 0;

        foreach ($credits as $credit) {
            
            switch($credit->type) {
                case StudentCredit::TYPE_DEPOSIT: $total += $credit->amount; break;
                case StudentCredit::TYPE_WITHDRAW: $total -= $credit->amount; break;
            }
        }

        return $total;
    }

    public function canMakeAdvisorQuestionnaire() {

        if(empty($this->advisor_id)) return false;

        $term = Setting::currentTerm();
        if(empty($term)) return false;

        return $term->isAdvisorQuestionnaireOpen();
    }

    public function hasGraduationSurvey() {
        $user = auth()->user();
        $student = $user->student;
        $controlTermID = Setting::where('name','control_term_id')->first()->value;
        if($controlTermID != $student->last_term_id)
            return false;

        if( (($this->status&Student::STATUS_GRADUATED)!=0) || (($this->status&Student::STATUS_SEMI_GRADUATED)!=0) ){
            $questionnaireID = Setting::where('name','graduation_questionnaire_id')->first()->value;
            $questionnaireTask = QuestionnaireTask::where('questionnaire_id', $questionnaireID)->where('term_id', $student->last_term_id)->first();
            if($questionnaireTask != null){
                $answer = QuestionnaireAnswer::where('task_id', $questionnaireTask->id)->where('student_id', auth()->id())->get()->count();
                $comment = QuestionnaireComment::where('task_id', $questionnaireTask->id)->where('student_id', auth()->id())->get()->count();
                if($answer > 0 || $comment > 0)
                    return false;
                
            }
            return true;      
        }
        return false;

    }

}
