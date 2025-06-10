<?php

namespace App\Http\Controllers\Api;

use App\Models\Student;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Student::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string',
            'email'=>'required|string',
            'address'=>'required|string',
            'phone'=>'required|string',
            'image'=>'required|string'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
        $file = $request->file('image');
        $fileName = time() . '_' . $file->getClientOriginalName(); // tên file mới (an toàn hơn)
        $file->move('public/uploads/', $fileName); // Lưu vào thư mục public/uploads
        $imagePath = 'uploads/' . $fileName; // Đường dẫn ảnh để lưu vào DB\
        }

        $create = Student::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'image' => $imagePath
        ]);

        return response()->json([
            'message'=>'Tao moi student thanh cong!',
            'order'=>$create
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $show = Student::findOrFail($id);
        return $show;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'=>'required|string',
            'email'=>'required|string',
            'address'=>'required|string',
            'phone'=>'required|string',
            'image'=>'required|string'
        ]);

        $update = Student::findOrFail($id);
        if(!empty($update->image)){
            unlink('public/'.$update->image);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
        $file = $request->file('image');
        $fileName = time() . '_' . $file->getClientOriginalName(); // tên file mới (an toàn hơn)
        $file->move('public/uploads/', $fileName); // Lưu vào thư mục public/uploads
        $imagePath = 'uploads/' . $fileName; // Đường dẫn ảnh để lưu vào DB\
        }


        $update = Student::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'image' => $imagePath
        ]);

        return response()->json([
            'message'=>'Update successfully!',
            'order'=>$update
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $delete = Student::find($id);
        if(!empty($delete->image)){
            unlink('public/'.$delete->image);
        }


        $delete->delete();
        return response()->noContent();
    }
}
