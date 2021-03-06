<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\Model\Worker;
use App\Model\WorkerRole;
use App\Http\Requests\Reservation\ReservationIdRequest;
use Notification;
use App\Notifications\ReservationApprove;
use App\Notifications\ReservationDisapprove;
use App\Http\Controllers\Controller;

/**
 * 
 */
class EmployeeController extends Controller {
    /**
     * View directory
     * @var type 
     */
    protected $view = 'admin.';

    // EMPLOYEE

    public  function employee()
    {
        $employee = Worker::join('worker_role', 'worker.worker_role_id', '=', 'worker_role.worker_role_id')
            ->where('worker.status', 0)
            ->get();
            
        $empt = WorkerRole::where('status', 0)
            ->get();
        return view($this->view . 'employee', ['employee' => $employee, 'type' => $empt]);
    }
    
    public function getEmployee(Request $req){
        $type = Worker::where('worker_id',$req->id)->get();
        $empt = WorkerRole::where('status', 0)->get();
        return response()->json($type, $empt);
    }

    public function addEmployee(){
        Worker::insert([ 
            'worker_lname' => $_POST['lname'],
            'worker_fname' => $_POST['fname'],
            'worker_mname' => $_POST['mname'],
            'worker_age' => $_POST['age'],
            'worker_role_id' => $_POST['worker_role']
            ]);
        alert()->success('Successfully added a worker', 'Success')->persistent('Close');
 
        return redirect('/admin/employee');
    }
    
    public function editEmployee(){
        Worker::where('worker_id', $_POST['id'])
            ->update(['worker_fname' => $_POST['fname'],
            'worker_mname' => $_POST['mname'],
            'worker_lname' => $_POST['lname'],
            'worker_mname' => $_POST['mname'],
            'worker_age' => $_POST['age']
        ]);

        alert()->success('Successfully edited a worker', 'Success')->persistent('Close');
        
        return redirect('/admin/employee');
    }
    public function deleteEmployee(){
        if(Worker::where('worker_id', $_POST['id'])->delete()){
            Worker::where('worker_id', $_POST['id'])->delete();
            alert()->success('Successfully deleted a worker', 'Success')->persistent('Close');
           }else{
           alert()->error('Something went wrong deleting the worker', 'Error')->persistent('Close');
           }
           return redirect('/admin/employee');
    }
    // END EMPLOYEE
}