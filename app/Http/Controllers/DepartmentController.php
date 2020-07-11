<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Exception;
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
        $departments = Department::with('faculties');
        return response()->json(['Departments' => $departments], 200);
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
            'faculty' => 'required',
        ]);

        try {
            $department = Department::create([
                'name' => $request['name'],
                'faculty_id' => DB::table('faculties')->select('id')
                                ->where('name', $request['faculty'])->first(),
            ]);
            return response()->json(['Department' => $department, 'message' => 'Department Created'], 200);
        } catch (\Exception $e) {

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
        $department = Department::findOrFail($id)->with('faculty')->get();
        return response()->json(['Department' => $department], 200);
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
        try {
            $department = Department::findOrFail($id);
            $this->validate($request, [
                'name' => 'required|string|min:5',
                'faculty' => 'required',
            ]);

            $department->name = $request['name'];
            $department->faculty_id = DB::table('faculties')->select('id')
                                ->where('name', $request['faculty'])->first();
            $department->save();
            return response()->json(['Department' => $department, 'message' => 'Department Updated'], 200);
        } catch(Exception $e) {
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
        try {
            Department::findOrFail($id)->delete();
        } catch(Exception $e) {
            error_log('An error occurred ' . $e);
            return response()->json(['message' => 'An error Occurred', 'logs' => $e], 500);
        }
    }
}
