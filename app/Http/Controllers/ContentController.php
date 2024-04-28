<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Imagen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ContentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function eventos()
    {
        $eventos  = Content::with('imagenes')->where('tipo','Evento')->get();

        return response()->json($eventos);
    }

    public function noticias()
    {
        $noticias  = Content::with('imagenes')->where('tipo','Noticia')->get();

        return response()->json($noticias);
    }

    public function visitas()
    {
        $visitas  = Content::with('imagenes')->where('tipo','Visita')->get();

        return response()->json($visitas);
    }

    public function muestras()
    {
        $muestras  = Content::with('imagenes')->where('tipo','Muestra')->get();

        return response()->json($muestras);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), Content::rules());

        if ($validator->fails()) {
            return response([
                'message' => 'Error de validacion',
                'error' => $validator->errors()
            ], 422);
        }

        $content = new Content();
        $content->fill($request->all());

        if ($request->has('tipo')) {
            if ($request->tipo === 'Muestra' || $request->tipo === 'Visita') {
                $content->fecha = null;
                $content->programado = false;
                $content->prioridad = false;
                $content->info = null;
            } else if ($request->tipo === 'Noticia') {
                $content->programado = false;
            } else if ($request->tipo === 'Muestra') {
                $content->prioridad = false;
                $content->info = null;
            } else if ($request->tipo === 'Evento' && $request->programado) {
                $content->principal = false;
                $content->prioridad = false;
            }
        }
          
          $content->save();

        if ($request->hasFile('img')) {
            foreach ($request->file('img') as $imagen) {
                $rutaImagen = $imagen->store('public/img');
                Imagen::create([
                    'img' => $rutaImagen,
                    'content_id' => $content->id
                ]);
            }
        }

        //   Algoritmo para poner Evento/Noticia como prioridad
        if ($content->prioridad) {
            $contenidosPrioritarios = Content::where('prioridad', true)
                ->where('id', '!=', $content->id)->get();

            foreach ($contenidosPrioritarios as $contenidoPrioritario) {
                if ($contenidoPrioritario->tipo === $request->tipo) {
                    $contenidoPrioritario->prioridad = false;
                    $contenidoPrioritario->save();
                }
            }

            $content->prioridad = true;
            $content->save();
        }

         // Algoritmo para cambiar el contenido principal
        // Verificar si el contenido es marcado como principal
        if ($content->principal) {
            // Obtener todos los contenidos marcados como principal excepto el contenido recién creado
            $contenidosPrincipales = Content::where('principal', true)
                ->where('id', '!=', $content->id)
                ->get();
            // Actualizar la propiedad "principal" de los contenidos a falso
            foreach ($contenidosPrincipales as $contentPrincipal) {
                if ($contentPrincipal->tipo === 'Visita' && $content->tipo === 'Visita') {
                    $contentPrincipal->principal = false;
                    $contentPrincipal->save();
                } elseif ($contentPrincipal->tipo === 'Muestra' && $content->tipo === 'Muestra') {
                    $contentPrincipal->principal = false;
                    $contentPrincipal->save();
                }
            }
            // Marcar el contenido recién creado como principal
            $content->principal = true;
            $content->save();
        }

        return response()->json([
            'message' => 'Exito, creado exitosamente',
            'contenido' => $content
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $content = Content::with(['imagenes', 'videos'])->find($id);
        
        if(!$content){
            return response()->json([
                'message' => 'No encontrado'
            ], 404);
        }

        return response()->json($content);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $content = Content::with('imagenes')->find($id);
        if(!$content){
            return response()->json([
                'message' => 'No encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), Content::rules());
        if($validator->fails()){
            return response([
                'message' => 'Error de validacion',
                'error' => $validator->errors()
            ], 422);
        }

        $content->update($request->all());

        if ($request->has('tipo')) {
            if ($request->tipo === 'Muestra' || $request->tipo === 'Visita') {
                $content->fecha = null;
                $content->programado = false;
                $content->prioridad = false;
                $content->info = null;
            } else if ($request->tipo === 'Noticia') {
                $content->programado = false;
            } else if ($request->tipo === 'Muestra') {
                $content->prioridad = false;
                $content->info = null;
            } else if ($request->tipo === 'Evento' && $request->programado) {
                $content->principal = false;
                $content->prioridad = false;
            }
        }
          
          $content->save();
          

          if(!$content->principal) 
          {
            $content->prioridad = false;
            $content->save();
          }
          

         //   Algoritmo para poner Evento/Noticia/Visita como prioridad
         if ($content->prioridad) {
            $contenidosPrioritarios = Content::where('prioridad', true)
                ->where('id', '!=', $content->id)->get();

            foreach ($contenidosPrioritarios as $contenidoPrioritario) {
                if ($contenidoPrioritario->tipo === $request->tipo) {
                    $contenidoPrioritario->prioridad = false;
                    $contenidoPrioritario->save();
                }
            }

            $content->prioridad = true;
            $content->save();
        } 

        // Algoritmo para cambiar el libro principal
        // Verificar si el contenido es marcado como principal
        if ($content->principal) {
            // Obtener todos los contenidos marcados como principal excepto el contenido recién creado
            $contenidosPrincipales = Content::where('principal', true)
                ->where('id', '!=', $content->id)
                ->get();
            // Actualizar la propiedad "principal" de los contenidos a falso
            foreach ($contenidosPrincipales as $contentPrincipal) {
                if ($contentPrincipal->tipo === 'Visita' && $content->tipo === 'Visita') {
                    $contentPrincipal->principal = false;
                    $contentPrincipal->save();
                } elseif ($contentPrincipal->tipo === 'Muestra' && $content->tipo === 'Muestra') {
                    $contentPrincipal->principal = false;
                    $contentPrincipal->save();
                }
            }
        
            // Marcar el contenido recién creado como principal
            $content->principal = true;
            $content->save();
        }

        return response()->json([
            'message' => 'Exito, actualizado exitosamente',
            'contenido' => $content
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $content = Content::find($id);
        if (!$content) {
            return response()->json([
                'message' => 'No encontrado'
            ], 404);
        }

        $imagenes = $content->imagenes;

        foreach ( $imagenes as $imagen ) {
            Storage::delete($imagen->img);
            $imagen->delete();
        } 

        $content->delete();
    }

    public function deleteImage($id)
    {
        $imagen = Imagen::findOrFail($id);
        Storage::delete($imagen->img);
        $imagen->delete();

        return response()->json([
            'message' => 'Exito, imagen eliminada exitosamente'
        ]);
    }


    public function deleteAllImages($id)
    {
        $contenido = Content::findOrFail($id);
        $imagenes = $contenido->imagenes;

        foreach ( $imagenes as $imagen ) {
            Storage::delete($imagen->img);
            $imagen->delete();
        } 

        return response()->json([
            'message' => 'Imagenes elimindas exitosamente',
            'imagenes' => $imagenes
        ]);
    }


    // Funciones no protegidas por middleware
    public function publicEventos()
    {
        $publicEventos = Content::with('imagenes')->where('tipo', 'Evento')->where('programado', false)->get();

        return response()->json($publicEventos);
    }

    public function eventosProgramados()
    {
        $eventosProgramados = Content::where('programado', true)->get();

        return response()->json($eventosProgramados);
    }

    public function publicNoticias()
    {
        $publicNoticias  = Content::with('imagenes')->where('tipo','Noticia')->get();

        return response()->json($publicNoticias);
    }

    public function publicVisitas()
    {
        $publicVisitas  = Content::with('imagenes')->where('tipo','Visita')->get();

        return response()->json($publicVisitas);
    }

    public function publicMuestras()
    {
        $publicMuestras  = Content::with('imagenes')->where('tipo','Muestra')->get();

        return response()->json($publicMuestras);
    }

    public function publicShow(string $id)
    {
        $publicContent = Content::with('imagenes')->find($id);
        
        if(!$publicContent){
            return response()->json([
                'message' => 'No encontrado'
            ], 404);
        }

        return response()->json($publicContent);
    }

    // Obtener el Contenido marcado como Muesta del mes principal(unico)

    public function muestraPrincipal()
    {
       $muestaPrincipal = Content::where('principal', true)->where('tipo', 'Muestra')->first();

       return response()->json($muestaPrincipal);
    }

    public function imagenesMuestraPrincipal()

    {
        $contenido = Content::where('principal', true)->where('tipo', 'Muestra')->first();
        if ($contenido && $contenido->tipo === 'Muestra') {
            $imagenes = $contenido->imagenes;
            return response()->json($imagenes);
        } 
        else {
            return response()->json([]);
        }
    }

    public function eventosPrincipales()
    {
        $eventos = Content::with('imagenes')->where('tipo', 'Evento')->where('principal', true)
                                            ->where('prioridad', 'false')->get();

        return response()->json($eventos);
    }


    public function eventosPrincipalesTodos()
    {
        $eventos = Content::with('imagenes')->where('tipo', 'Evento')->where('principal', true)
                                            ->get();

        return response()->json($eventos);
    }


    public function imagenesEventoPrincipal()

    {
        $contenido = Content::where('prioridad', true)->where('tipo', 'Evento')->first();
        if ($contenido && $contenido->tipo === 'Evento') {
            $imagenes = $contenido->imagenes;
            return response()->json($imagenes);
        } 
        else {
            return response()->json([]);
        }
    }

    // public function noticiasPrinicpalesAll()
    // {
    //     $noticias = Content::with('imagenes')->where('tipo', 'Noticia')->where('principal', true)
    //                                         ->get();

    //     return response()->json($noticias);
    // }

    public function noticiasPrincipales()
    {
        $noticias = Content::with('imagenes')->where('tipo', 'Noticia')->where('principal', true)
                                             ->where('prioridad', 'false')->get();

        return response()->json($noticias);
    }

    public function imagenesNoticiaPrincipal()

    {
        $contenido = Content::with('imagenes')->where('prioridad', true)->where('tipo', 'Noticia')->first();
        if ($contenido && $contenido->tipo === 'Noticia') {
            $imagenes = $contenido->imagenes;
            return response()->json($imagenes);
        } 
        else {
            return response()->json([]);
        }
    }

    public function imagenesVisitaPrincipal()

    {
        $contenido = Content::where('principal', true)->where('tipo', 'Visita')->first();
        if ($contenido && $contenido->tipo === 'Visita') {
            $imagenes = $contenido->imagenes;
            return response()->json($imagenes);
        } 
        else {
            return response()->json([]);
        }
    }

    // Incrementar las visitas
    public function incrementarVisitas($id)
    {
        $content = Content::findOrFail($id);
        $visitas = $content->visitas++;
        $content->save();

        return response()->json([
            'message' => 'Visitas incrementadas',
            'visitas' => $visitas
        ]);

    }

    // Contar la cantidad de Eventos y Noticias principales
    public function contEventosPrincipales()
    {
        $eventos = Content::where('principal', true)->where('tipo', 'Evento');
        $contEventos = $eventos->count();

        $noticias = Content::where('principal', true)->where('tipo', 'Noticia');
        $contNoticias = $noticias->count();

        return response()->json([$contEventos, $contNoticias]);
    }

     // Contar la cantidad de Visitas Principales
    public function contVisitasPrincipales()
    {
        $visitas = Content::where('principal', true)->where('tipo', 'Visita');
        $contVisitas = $visitas->count();

        return response()->json($contVisitas);
    }

    // Obtener Noticias con mas visitas
    public function noticiasMasVisitadas() {
        $noticiasMasVisitadas = Content::where('tipo', 'Noticia')->orderBy('visitas', 'desc')->get();

        return response()->json($noticiasMasVisitadas);
    }

    // Obtener Videos de un contenido(evento)
    public function videosPorEventoId($id) 
    {
        $evento = Content::findOrFail($id);

        $videosEvento = $evento->videos;

        return response()->json($videosEvento);
    }

    public function contarVisitasSitio()
    {
        $contador = 0;
        $visitas = $contador + 1;

        return response()->json([
            'message' => 'Visitas incrementadas',
            'visitas' => $visitas
        ]);

    }


}
