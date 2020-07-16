<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        try {
            $departments = Department::join('faculties', 'departments.faculty_id', '=', 'faculties.id')
                                    ->select('departments.id', 'departments.name', 'faculties.id as faculty_id', 'faculties.name as faculty_name')
                                    ->get();
            return response()->json(['Departments' => $departments], 200);
        } catch (\Exception $e) {
            //return error message
            error_log('An error occurred caused by ' . $e);
            return response()->json(['message' => 'User update Failed!', 'logs' => $e], 409);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|min:5',
            'faculty_id' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $faculty = Faculty::findOrFail($request['faculty_id']);
            $department = new Department();
            $department->name = $request['name'];
            $faculty->departments()->save($department);

            DB::commit();
            return response()->json(['Department' => $department, 'message' => 'Department Created'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            error_log('An error occurred caused by ' . $e);
            return response()->json(['message' => 'An error occurred!', 'logs' => $e], 409);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $department = Department::join('faculties', 'departments.faculty_id', '=', 'faculties.id')
                                    ->select('departments.id', 'departments.name', 'faculties.id as faculty_id', 'faculties.name as faculty_name')
                                    ->where('departments.id', $id)
                                    ->first();
            if ($department == null) {
                return response()->json(['message' => 'Resource not found'], 404);
            } else {
                return response()->json(['Department' => $department], 200);
            }
        } catch(\Exception $e) {
            error_log('An error occurred ' . $e);
            return response()->json(['message' => 'An error Occurred', 'logs' => $e], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|min:5',
            'faculty_id' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $faculty = Faculty::findOrFail($request['faculty_id']);
            $department = Department::findOrFail($id);
            $department->name = $request['name'];
            $faculty->departments()->save($department);
            DB::commit();
            return response()->json(['Department' => $department, 'message' => 'Department Updated'], 200);
        } catch(\Exception $e) {
            DB::rollback();
            error_log('An error occurred ' . $e);
            return response()->json(['message' => 'An error Occurred', 'logs' => $e], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            Department::findOrFail($id)->delete();
            DB::commit();
            return response()->json(['message' => 'Successfully deleted'], 200);
        } catch(\Exception $e) {
            DB::rollback();
            error_log('An error occurred ' . $e);
            return response()->json(['message' => 'An error Occurred', 'logs' => $e], 500);
        }
    }
}
