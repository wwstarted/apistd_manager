<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Classes;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Classes::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string'
        ]);

        $store = Classes::create($request->all());

        return response()->json([
            'message'=>'Create Successfully!',
            'order'=> $store
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $show = Classes::findOrFail($id);
        return $show;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'=>'required|string'
        ]);

        $update = Classes::findOrFail($id);

        $update->update($request->all());

        return response()->json([
            'message'=>'Update Successfully!',
            'order'=>$update
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $class = Classes::findOrFail($id);

        $class->delete();
    }
}
