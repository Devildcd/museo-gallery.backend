<?php

namespace App\Http\Controllers;

use App\Models\VideosEvento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VideoEventoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $videos = VideosEvento::all();

        return response()->json($videos);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), VideosEvento::rules());

        if($validator->fails()){
            return response([
                'message' => 'Error de validacion',
                'error' => $validator->errors()
            ], 422);
        }

        $video = new VideosEvento();
        $video->fill($request->all());
        $video->save();

        return response()->json([
            'message' => 'Exito, creado exitosamente',
            'video' => $video
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $video = VideosEvento::find($id);
        if(!$video){
            return response()->json([
                'message' => 'No encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), VideosEvento::rules());
        if($validator->fails()){
            return response([
                'message' => 'Error de validacion',
                'error' => $validator->errors()
            ], 422);
        }

        $video->update($request->all());

        return response()->json([
            'message' => 'Exito, actualizado exitosamente',
            'video' => $video
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $video = VideosEvento::find($id);
        if (!$video) {
            return response()->json([
                'message' => 'No encontrado'
            ], 404);
        }

        $video->delete();

        return response()->json([
            'message' => 'Exito, eliminado exitosamente'
        ]);
    }
}
