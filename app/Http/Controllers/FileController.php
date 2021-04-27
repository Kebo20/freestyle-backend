<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function storage(Request $request)
    {
        $validatedData = $request->validate([
            'file_to_upload' => 'required'
        ]);
        $directory = date('Y') . '/' . date('m');

        Storage::makeDirectory($directory);

        $ext = $request->file('file_to_upload')->getClientOriginalExtension();
        $name = $this->randomName($ext);

        if ($ext == 'pdf' || $ext == 'xlsx' || $ext == 'docx' || $ext == 'xsl' || $ext == 'png' || $ext == 'jpg' || $ext == 'jpeg') {
        } else {
            return response()->json([
                'message' => 'Archivo .' . $ext . ' no permitido'
            ], 400);
        }


        $path = Storage::putFileAs(
            'public/' . $directory,
            $request->file('file_to_upload'),
            $name
        );
        $url = Storage::url($path);
        return response()->json([
            'message' => 'Archivo subido correctamente.',
            'url' => $url,
            'status' => 'done',
            'name' => $name
        ], 200);
    }

    private function randomName($extension)
    {
        return time() . '.' . $extension;
    }
}
