<?php

namespace App\Http\Controllers;

use App\Models\VideoFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VideoFileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $videos = VideoFile::all();

        return response()->json($videos);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), VideoFile::rules());

        if($validator->fails()){
            return response([
                'message' => 'Error de validacion',
                'error' => $validator->errors()
            ], 422);
        }

        $video = new VideoFile();
        $video->fill($request->all());
        $video->save();

        return response()->json([
            'message' => 'Exito, creado correctamente',
            'contenido' => $video
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $video = VideoFile::findOrFail($id);

        if(!$video){
            return response()->json([
                'message' => 'No encontrado'
            ], 404);
        }

        return response()->json($video);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $video = VideoFile::findOrFail($id);
        if(!$video){
            return response()->json([
                'message' => 'No encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), VideoFile::rules());
        if($validator->fails()){
            return response([
                'message' => 'Error de validacion',
                'error' => $validator->errors()
            ], 422);
        }

        $video->update($request->all());
        $video->save();

        return response()->json([
            'message' => 'Exito, actualizado correctamente',
            'evento' => $video
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $video = VideoFile::findOrFail($id);
       
        $video->delete();

        return response()->json([
            'message' => 'Exito, video eliminado exitosamente'
        ]);
    }
    
}
