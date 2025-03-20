<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImageUploadController extends Controller
{
    public function upload(Request $request)
    {
        Log::info('Upload request received');

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            
            // Validation
            $validatedData = $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $uploadPath = public_path('uploads/images');
            
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            try {
                $file->move($uploadPath, $fileName);
                $location = asset('uploads/images/' . $fileName);
                Log::info('File uploaded successfully', ['location' => $location]);
                
                return response()->json([
                    'location' => $location
                ]);
            } catch (\Exception $e) {
                Log::error('File upload failed', ['error' => $e->getMessage()]);
                return response()->json([
                    'error' => 'Échec du téléchargement de l\'image'
                ], 500);
            }
        }

        Log::warning('No file in request');
        return response()->json([
            'error' => 'Aucun fichier n\'a été téléchargé'
        ], 400);
    }
}
