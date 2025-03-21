<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageUploadController extends Controller
{
    public function store(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/images'), $filename);
            
            return response()->json([
                'location' => asset('uploads/images/' . $filename)
            ]);
        }
        
        return response()->json(['error' => 'Aucun fichier uploadé.'], 400);
    }
}