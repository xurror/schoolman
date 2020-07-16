<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacultyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        try {
            $faculties = Faculty::all();
            return response()->json(['Faculties' => $faculties], 200);
        } catch (\Exception $e) {
            error_log($e);
            return response()->json(['message' => 'Transaction failed', 'logs' => $e], 409);
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
        ]);

        DB::beginTransaction();
        try {
            $faculty = Faculty::create([
                'name' => $request['name'],
            ]);
            DB::commit();
            return response()->json(['Faculty' => $faculty, 'message' => 'Faculty Created'], 200);
        } catch (\Exception $e) {
            //return error message
            DB::rollback();
            error_log('An error occurred caused by ' . $e);
            return response()->json(['message' => 'Staff update Failed!'], 409);
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
            return Faculty::findOrFail($id);
        } catch(\Exception $e) {
            error_log('An error occurred ' . $e);
            return response()->json(['message' => 'An error Occurred'], 500);
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
        DB::beginTransaction();
        try {
            $faculty = Faculty::findOrFail($id);
            $this->validate($request, [
                'name' => 'required|string|min:5',
            ]);

            $faculty->name = $request['name'];
            $faculty->save();
            return response()->json(['Faculty' => $faculty, 'message' => 'Faculty Updated'], 200);
        } catch(\Exception $e) {
            DB::rollback();
            error_log('An error occurred ' . $e);
            return response()->json(['message' => 'An error Occurred'], 500);
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
            Faculty::findOrFail($id)->delete();
            return response()->json(['message' => 'Successfully deleted'], 200);
        } catch(\Exception $e) {
            DB::rollback();
            error_log('An error occurred ' . $e);
            return response()->json(['message' => 'An error Occurred'], 500);
        }
    }
}
