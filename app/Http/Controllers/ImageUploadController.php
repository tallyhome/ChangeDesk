<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ImageUploadController extends Controller
{
    public function upload(Request $request)
    {
        Log::info('Upload request received', $request->all());
        
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            Log::info('File received', ['name' => $file->getClientOriginalName()]);
            
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            
            $uploadPath = public_path('uploads/images');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            $file->move($uploadPath, $filename);
            
            $location = asset('uploads/images/' . $filename);
            Log::info('File uploaded', ['location' => $location]);
            
            return response()->json([
                'location' => $location
            ]);
        }
        
        Log::warning('No file in request');
        return response()->json(['error' => 'Aucun fichier n\'a été téléchargé.'], 400);
    }
}