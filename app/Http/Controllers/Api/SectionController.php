<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Section::with(['classes'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string',
            'class_id'=>'required|integer'
        ]);

        $store = Section::create($request->all());

        return response()->json([
            'message'=>'Create Successfullly!',
            'order'=>$store
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Section::where('class_id',$id)->get();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'=>'required|string',
            'class_id'=>'required|integer'
        ]);

        $update = Section::findOrFail($id);

        $update->update($request->all());

        return response()->json([
            'message'=>'Update Successfully!',
            'order'=> $update
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $delete = Section::findOrFail($id);

        $delete->delete();

        return response()->noContent();
    }
}
