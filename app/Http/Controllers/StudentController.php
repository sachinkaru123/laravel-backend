<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use PhpParser\Node\Stmt\TryCatch;

class StudentController extends Controller
{
    function sendStudents()
    {
        $students = Student::all();
        return  response()->json($students);
    }

    function createStudent(Request $request)
    {
        if ($request) {


            //validate using fackade validator
            $validator = FacadesValidator::make($request->all(), [
                'name' => 'required|string|max:191',
                'course' => 'required|string|max:191',
                'email' => 'required|email|max:191',
                'phone' => 'required|digits:10',
            ]);

            if ($validator->fails()) {

                return response()->json(['errors' => $validator->messages()], 422);
            } else {
                $student = Student::create([
                    'name' => $request->name,
                    'course' => $request->course,
                    'email' => $request->email,
                    'phone' => $request->phone,
                ]);

                $student->save();
                return response()->json(['success' => "Data Stored"]);
            }
        }
    }

    function getStudent($id)
    {

        try {
            $student = Student::find($id);
            if ($student) {
                return  response()->json($student);
            } else {
                //generate error 
                return response()->json(['Error:Record not found'], 404);
            }
        } catch (\Throwable $th) {
            return response()->json(['Error:' . $th->getMessage()], 500);
        }
    }

    function updateStudent(Request $request)
    {
        $student = Student::find($request->id);

        //validate using fackade validator
        $validator = FacadesValidator::make($request->all(), [
            'name' => 'required|string|max:191',
            'course' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'phone' => 'required|digits:10',
        ]);

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->messages()], 422);
        } else {
            $student->update([
                'name' => $request->name,
                'course' => $request->course,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);

            return response()->json(['success' => "Data Updated"]);
        }
    }

    function deleteStudent($id)
    {
        try {
            $student = Student::find($id)->first();
            if ($student) {

                //delete
                $student->delete();
                return response()->json(['success' => "Data Delted"]);

            }
            else{
                return response()->json(['Error:Record not found'], 404);
            }
           
            
        } catch (\Throwable $th) {
            return response()->json(['Error:' . $th->getMessage()], 500);
        }
    }
}
