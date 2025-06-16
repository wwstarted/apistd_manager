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
        return Student::with(['classes','sections'])->get();
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
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
            'class_id'=>'required|integer',
            'section_id'=>'required|integer'
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
            'class_id'=>$request->class_id,
            'section_id'=>$request->section_id,
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
            'name'=>'nullable|string',
            'email'=>'nullable|string',
            'address'=>'nullable|string',
            'phone'=>'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
            'class_id'=>'nullable|integer',
            'section_id'=>'nullable|integer'
        ]);

        $update = Student::findOrFail($id);

        $updateData = [
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            ];

        if ($request->hasFile('image')){
            if(!empty($update->image)){
                unlink('public/'.$update->image);
            }

            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName(); // tên file mới (an toàn hơn)
            $file->move('public/uploads/', $fileName); // Lưu vào thư mục public/uploads
            $updateData['image'] = 'uploads/' . $fileName;
        };




        $update->update($updateData);

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

        if (!$delete) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        if(!empty($delete->image)){
            $imagePath = 'public/'.$delete->image;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }


        $delete->delete();
        return response()->noContent();
    }
}
