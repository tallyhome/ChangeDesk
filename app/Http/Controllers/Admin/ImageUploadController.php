<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImageUploadController extends Controller
{
    public function store(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            
            // Validation
            $validatedData = $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $fileName);

            // Format pour TinyMCE
            return response()->json([
                'location' => asset('uploads/' . $fileName)
            ]);
        }

        return response()->json([
            'error' => 'No file uploaded'
        ], 400);
    }
}
