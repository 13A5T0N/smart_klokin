<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

// all models
use App\Models\attendance;
use App\Models\employees;
use App\Models\employee_shifts as shifts;
use App\Models\holiday_shift as holiday;
use App\Models\national_days as national;
use App\Models\holiday_shift_employee as holiday_employee;
use App\Models\employee_extend as extend;
use App\Models\employee_sub as sub;
use App\Models\leave_of_absence as leave;
use App\Models\shift_swap as swap;
use App\Models\employee_break;
use App\Models\extend as shift_extend;

class LoginController extends Controller
{

    //get todays date
    private function get_date()
    {
        $date = Carbon::now();
        $date->toDateString();
        $today = date('Y-m-d', strtotime($date));

        return $today;
    }

    private function get_hour()
    {
        $date = Carbon::now();
        $date->toDateString();
        $today = date('h:i:s', strtotime($date));

        return $today;
    }

    // get day
    private function get_day()
    {
        $date = Carbon::now();
        $date->toDateString();
        $today = date('l', strtotime($date));

        return $today;
    }

    private function get_employee($id)
    {
        $user = employees::select('*')
        ->where('pin', '=', $id)
        ->value('id');
        return $user;
    }

    private function get_attendance($id, $date)
    {
        $attendnace = attendance::select('*')
        ->where('employee_id', '=', $id)
        ->where('date', '=', $date)
        ->where('time_out', '=', '00:00:00')
        ->value('id');

        return $attendnace;
    }

    private function get_shift($id, $date)
    {
        $shifts = shifts::select('*')
        ->where('employee_id', '=', $id)
        ->where('day', '=', $date)
        ->value('id');

        return $shifts;
    }
    
    private function get_shift_swap($id, $date)
    {
        $swap = swap::select('*')
        ->where('employee_2', '=', $id)
        ->where('date', '=', $date)
        ->value('id');

        return $swap;
    }

    private function get_national_day()
    {
        $date = $this->get_day();
        $swap = national::select('*')
        ->where('start', '=', $date)
        ->value('id');

        return $swap;
    }

    private function get_holiday()
    {
        $date = $this -> get_national_day();
        $swap = holiday::select('*')
        ->where('holiday', '=', $date)
        ->value('id');

        return $swap;
    }

    private function get_holiday_shift($id, $date)
    {
        $shift = $this -> get_holiday();
        $swap = holiday_employee::select('*')
        ->where('employee', '=', $id)
        ->where('holiday_shift', '=', $shift)
        ->value('id');

        return $swap;
    }

    private function get_sub($id, $date)
    {
        $swap = sub::select('*')
        ->where('employee', '=', $id)
        ->where('date', '=', $date)
        ->value('id');

        return $swap;
    }

    private function get_extend($id, $date)
    {
        $swap = sub::select('*')
        ->where('employee', '=', $id)
        ->where('date', '=', $date)
        ->value('id');

        return $swap;
    }

    
    public function login(Request $req)
    {
        $today = $this->get_date();
        $day = $this->get_day();
        $hour = $this-> get_hour();

        $pin = $req->input('pin');

        $user = $this -> get_employee($pin);

        $attendance = $this -> get_attendance($user, $today);
        $swap = $this->get_shift_swap($user, $today);
        $holiday_shift = $this-> get_holiday_shift($user, $today);
        $shift_sub = $this->get_sub($user, $today);
        $extend = $this->get_extend($user, $today);
        $shifts = $this->get_shift($user, $day);


       

        
        if (!$user) {
            $status = '0';
        } else {
            if (!$attendance) {
                if (!$shifts) {
                    if (!$swap) {
                        if (!$holiday_shift) {
                            if (!$shift_sub) {
                                if (!$extend) {
                                    $status = '2';
                                } else {
                                    $status = '1';
                                }
                            } else {
                                $status = '1';
                            }
                        } else {
                            $status = '1';
                        }
                    } else {
                        $status = '1';
                    }
                } else {
                    $status = '1';
                }
            } else {
                $status = '3';
            }
        }

        if ($status == '1') {
            $res = attendance::create([
            'employee_id' => $user,
            'branch' => '2',
            'type' => 'normal',
            'status' => '0',
            'time_in' =>  $hour,
            'date' => $today
        ]) ;
        }
        
        
        
        return response() -> json([
            'return' =>  $status
        ]);
    }

    
    public function time_out(Request $req)
    {
        $today = $this->get_date();
        $day = $this->get_day();
        $hour = $this-> get_hour();

        $pin = $req->input('pin');
        $user = $this -> get_employee($pin);
        $attendance = $this->get_attendance($user, $today);

        if (!$user) {
            $res = 'user not found';
        } else {
            $res =  attendance:: where('id', '=', $attendance)
            ->update(['time_out' => $hour]);
        }

        return response() -> json([
            'return' =>  '1'
        ]);
    }


    private function get_break($id)
    {
        $break = employee_break::select('*')
        ->where('attendance', '=', $id)
        ->where('break_status', '=', 'actief')
        ->value('id');

        return $break;
    }

    public function break_in(Request $req)
    {
        $today = $this->get_date();
        $day = $this->get_day();
        $hour = $this-> get_hour();

        $pin = $req ->input('pin');
        $user = $this -> get_employee($pin);
        $attendance = $this->get_attendance($user, $today);
        $break = $this->get_break($attendance);

        if (!$user) {
            $status = '0';
        } else {
            if (!$attendance) {
                $status = '1';
            } else {
                if (!$break) {
                    $status = employee_break::create([
                        'attendance' => $attendance,
                        'employee' => $user,
                        'break_date' => $today,
                        'break_in' => $hour
                   ]);
                    $status = "2";
                }else {
                    
                    $status = '3';
                }
            }
        }

        return response() -> json([
            'return' =>  $status
        ]);
    }

    public function break_out(Request $req)
    {
        $today = $this->get_date();
        $day = $this->get_day();
        $hour = $this-> get_hour();

        $pin = $req ->input('pin');
        $user = $this -> get_employee($pin);
        $attendance = $this->get_attendance($user, $today);
        $break = $this->get_break($attendance);
        
        $details =  array(
            'break_out' => $hour,
            'break_status' => 'deactief'
        );
        if (!$user) {
            $status = '0';
        } else {
            if (!$attendance) {
                $status = '1';
            } else {
                if (!$break) {
                    $status = '3';
                } else {
                    $status = employee_break::where('id', '=', $break)
                    ->update($details)
                    ;
                    $status = '2';
                }
            }
        }

        return response() -> json([
            'return' =>  $status
        ]);
    }

    private function get_switch($id,$date){
        $shift = extend::select('*')
        ->where('employee','=',$id)
        ->where('date','=',$date)
        ->value('id');

        return $shift;
    }

    public function switch_in(Request $req){
        
        $today = $this->get_date();
        $day = $this->get_day();
        $hour = $this-> get_hour();

        $pin = $req ->input('pin');
        $user = $this -> get_employee($pin);
        $attendance = $this->get_attendance($user, $today);
        $switch = $this->get_switch($user,$today);

        $details =  array(
            'break_out' => $hour,
            'break_status' => 'deactief'
        );

        if(!$attendance){
            $status = '0';
        }else {
            if (!$switch) {
                $status = '2';
            } else {
                $res = shift_extend::create([
                    'employee' => $user,
                    'attendance' => $attendance,
                    'status' => 'actief',
                    'switch_in'  =>  $hour,
                    'date' => $today]);
                $status = '3';
            }
            
        }

        return response() -> json([
            'return' =>  $status
        ]);
    }

    private function get_active_switch($id,$date){
        $shift = shift_extend::select('*')
        ->where("employee", '=',$id)
        ->where("date",'=',$date)
        ->where("status",'=',"actief")
        ->value("id");

        return $shift;
    }

    public function switch_out(Request $req){
        $today = $this->get_date();
        $day = $this->get_day();
        $hour = $this-> get_hour();

        $pin = $req ->input('pin');
        $user = $this -> get_employee($pin);
        $attendance = $this->get_attendance($user, $today);
        $switch = $this->get_switch($user,$today);
        $active = $this -> get_active_switch($user,$today);

        $details =  array(
            'switch_out' => $hour,
            'status' => 'deactief'
        );

        if (!$user) {
            $status = '1';
        }else{
            if (!$attendance) {
                $status = '1';
            } else {
                if (!$active) {
                    $status = '2';
                } else {
                     shift_extend::where('attendance', '=', $attendance)
                    ->update($details)
                    ;
                    $status = '3';
                }
                
            }
        }

        return response() -> json([
            'return' =>  $status
        ]);
    }
}
