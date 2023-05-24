<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class EmployeeController extends Controller
{
    public function index(){
        $employees = Employee::all();

        return view('employee.index', compact('employees'));
    }
    public function store(Request $request){
              
        $validator = Validator::make($request->all(),[
            'name'=> 'required|max:191',
            'email'=> 'required|email|max:191',
            'phone'=> 'required|max:191',
            'role'=> 'required|max:191',
        ]);
        
        if($validator->fails()){
            return response()->json([
                'status'=> 400,
                'errors'=>$validator->messages()
            ]);
            }
            else{
                $employee = new Employee;

                $employee->names = $request->input('name');
                $employee->email = $request->input('email');
                $employee->phone = $request->input('phone');
                $employee->role = $request->input('role');
                $employee->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Employee added successfully',
                    'employee' => $employee,
                ]);

            }

    }
    public function edit($id)
    {
        $employee = Employee::findOrFail($id);

        return response()->json(['status' => 200, 'employee' => $employee]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'names' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'role' => 'required',
        ]);

        $employee = Employee::findOrFail($id);
        $employee->update($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'Employee updated successfully',
            'employee' => $employee,
        ]);
    }
        public function destroy($id)
        {
            $employee = Employee::findOrFail($id);
            $employee->delete();
            return response()->json(['status' => 200, 'message' => 'Employee deleted successfully']);
            }           
}
