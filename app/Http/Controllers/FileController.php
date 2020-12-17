<?php

namespace App\Http\Controllers;

use App\Model\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $allFile = File::all();

        if (empty($allFile)) {

            return response()->json([
                'success' => false,
                'message' => 'Listelenecek dosyalar bulunamadı.'
            ]);
        }

        return response()->json([
            'success' => true,
            'files' => $allFile
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,txt,xlx,xls,pdf|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->messages()->first()
            ]);
        }

        $requestFile = $request->file('file');
        $fileName = time() . '_' . $requestFile->getClientOriginalName();

        if (!Storage::disk('local')->exists($fileName)){

            $requestFile->storeAs('/', $fileName, 'local');

            $file = new File();
            $file->name = $fileName;
            $file->file_path = config('filesystems.disks.local.directory');
            $file->mime_type = $request->file('file')->getMimeType();
            $file->save();

            return response()->json([
                'success' => true,
                'message' => 'Dosya ekleme işlemi başarılı sonuçlandı'
            ]);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'Dosya ismi kullanılmaktadır. Farklı bir dosya ismi kullanınız.'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $file = File::find($id);

        if (empty($file))
            return response()->json([
                'success' => false,
                'message' => 'Dosya bulunamadı.'
            ]);

        return response()->json($file);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $file = File::find($id);

        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,txt,xlx,xls,pdf|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->messages()->first()
            ]);
        }

        if (!Storage::disk('local')->exists($file->name)) {
            return response()->json([
                'success' => false,
                'message' => 'Dosya bulunamadı.'
            ]);
        }

        $requestFile = $request->file('file');
        $fileName = time() . '_' . $requestFile->getClientOriginalName();

        if (!Storage::disk('local')->exists($fileName)){

            $requestFile->storeAs('/', $fileName, 'local');

            $file->name = $fileName;
            $file->file_path = config('filesystems.disks.local.directory');
            $file->mime_type = $request->file('file')->getMimeType();
            $file->save();

            return response()->json([
                'success' => true,
                'message' => 'Dosya güncelleme işlemi başarılı sonuçlandı.'
            ]);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'Dosya ismi kullanılmaktadır. Farklı bir dosya ismi kullanınız.'
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $file = File::find($id);

        if (empty($file))
            return response()->json([
                'success' => false,
                'message' => 'Dosya bulunamadı.'
            ]);

        File::destroy($id);

        return response()->json([
            'success' => true,
            'message' => 'Dosya başarılı bir şekilde silindi.'
        ]);
    }
}
