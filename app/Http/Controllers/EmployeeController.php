<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function ListEmployees(Request $request){
        $employees = DB::select("SELECT * FROM employees");

        return response()->json([
            'message' => 'Data retrieved',
            'data' => $employees
        ], 200);
    }

    public function EditEmployee($id){
        return view('employee.edit', ['id' => $id]);
    }

    public function deleteEmployee($id){
//        Employee::where('id', $id)->delete();

        return response()->json([
            'message' => 'Employee data successfully deleted',
        ], 200);
    }

    public function createEmployee(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'salary' => ['required'],
            'image' => ['required'],
            'job_title' => ['required', 'string'],
            'job_type' => ['required', 'string'],
            'job_status' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 401);
        }
        $insertData = array(
            'name' => $data['name'],
            'current_salary' => $data['salary'],
            'job_type' => $data['job_type'],
            'image' => $request->get('image'),
            'job_title' => $data['job_title'],
            'job_status' => $data['job_status'],
        );
        DB::table('employees')->insert($insertData);

        return response()->json([
            'status' => 'success',
            'message' => 'Employee details has been saved successfully'
        ], 201);
    }
    public function updateEmployee(Request $request, $id){
        $employee = Employee::where('id', $id)->first();
        if (!$employee){
            return response()->json([
                'status' => 'error',
                'message' => 'Employee not found'
            ], 400);
        }
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'salary' => ['required'],
            'image' => ['required'],
            'job_title' => ['required', 'string'],
            'job_type' => ['required', 'string'],
            'job_status' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 401);
        }
        $lastSalaryChange = $data['salary'] - $employee->current_salary;
        $insertData = array(
            'name' => $data['name'],
            'current_salary' => $data['salary'],
            'last_salary_change	' => $lastSalaryChange,
            'job_type' => $data['job_type'],
            'image' => $request->get('image'),
            'job_title' => $data['job_title'],
            'job_status' => $data['job_status'],
        );
        DB::table('employees')->where('id', $id)->update($insertData);

        return response()->json([
            'status' => 'success',
            'message' => 'Employee details has been saved successfully'
        ], 201);
    }
    public function getEmployee($id){
        $employee = Employee::where('id', $id)->first();
        if ($employee){
            return response()->json([
                'message' => 'details retrieved',
                'employee' => $employee
            ], 200);
        }else{
            return response()->json([
                'message' => 'details not found',
            ],400);
        }
    }
}
