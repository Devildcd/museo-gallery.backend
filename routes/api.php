<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// 

Route::group( ['middleware' => ["auth:sanctum"]], function(){


    Route::get('user-profile', [UserController::class, 'userProfile']);
    Route::put('user-edit', [UserController::class, 'updateUsers']);
    Route::get('logout', [UserController::class, 'logout']);
          
    //rutas privadas para content
    Route::get('/eventos', 'App\Http\Controllers\ContentController@eventos');
    Route::get('/noticias', 'App\Http\Controllers\ContentController@noticias');
    Route::get('/visitas', 'App\Http\Controllers\ContentController@visitas');
    Route::get('/muestras', 'App\Http\Controllers\ContentController@muestras');
    Route::get('/content/{id}', 'App\Http\Controllers\ContentController@show');
    Route::post('/content', 'App\Http\Controllers\ContentController@store');
    Route::put('/content/{id}', 'App\Http\Controllers\ContentController@update');
    Route::delete('/content/{id}', 'App\Http\Controllers\ContentController@destroy');

    //rutas privadas para imagenes
    Route::post('/only-imagen', 'App\Http\Controllers\ImagenController@store');
    Route::delete('/delete-all-images/{id}', 'App\Http\Controllers\ContentController@deleteAllImages');
    Route::delete('/imagen/{id}', 'App\Http\Controllers\ContentController@deleteImage');

    //rutas privadas para videoFile
    Route::get('/videos', 'App\Http\Controllers\VideoFileController@index');
    Route::get('/video/{id}', 'App\Http\Controllers\VideoFileController@show');
    Route::post('/video', 'App\Http\Controllers\VideoFileController@store');
    Route::put('/video/{id}', 'App\Http\Controllers\VideoFileController@update');
    Route::delete('/video/{id}', 'App\Http\Controllers\VideoFileController@destroy');

    //rutas privadas para videosEvento
    Route::get('/videos-evento', 'App\Http\Controllers\VideoEventoController@index');
    Route::get('/video-evento/{id}', 'App\Http\Controllers\VideoEventoController@show');
    Route::post('/video-evento', 'App\Http\Controllers\VideoEventoController@store');
    Route::put('/video-evento/{id}', 'App\Http\Controllers\VideoEventoController@update');
    Route::delete('/video-evento/{id}', 'App\Http\Controllers\VideoEventoController@destroy');
});
    
   
    //Rutas publicas para content
    Route::get('/eventos-publicos', 'App\Http\Controllers\ContentController@PublicEventos');
    Route::get('/noticias-publicos', 'App\Http\Controllers\ContentController@PublicNoticias');
    Route::get('/visitas-publicos', 'App\Http\Controllers\ContentController@publicVisitas');
    Route::get('/muestras-publicos', 'App\Http\Controllers\ContentController@publicMuestras');
    Route::get('/videos-evento-porId/{id}', 'App\Http\Controllers\ContentController@videosPorEventoId');

    Route::get('/content-publico/{id}', 'App\Http\Controllers\ContentController@publicShow');

    Route::get('muestra-principal', 'App\Http\Controllers\ContentController@muestraPrincipal');
    Route::get('muestra-principal-imagenes', 'App\Http\Controllers\ContentController@imagenesMuestraPrincipal');

    Route::get('eventos-principales', 'App\Http\Controllers\ContentController@eventosPrincipales');
    Route::get('eventos-principales-todos', 'App\Http\Controllers\ContentController@eventosPrincipalesTodos');
    Route::get('eventos-programados', 'App\Http\Controllers\ContentController@eventosProgramados');
    Route::get('evento-principal-imagenes', 'App\Http\Controllers\ContentController@imagenesEventoPrincipal');

    Route::get('noticias-principales', 'App\Http\Controllers\ContentController@noticiasPrincipales');
    Route::get('noticias-principales-todas', 'App\Http\Controllers\ContentController@noticiasPrincipalesTodas');
    Route::get('noticia-principal-imagenes', 'App\Http\Controllers\ContentController@imagenesNoticiaPrincipal');

    Route::get('visitas-principales', 'App\Http\Controllers\ContentController@visitasPrincipales');
    Route::get('visita-principal-imagenes', 'App\Http\Controllers\ContentController@imagenesVisitaPrincipal');

    Route::put('/incrementar-visitas/{id}', 'App\Http\Controllers\ContentController@incrementarVisitas');
    Route::get('/cantContenidosPrincipales', 'App\Http\Controllers\ContentController@contEventosPrincipales');
    Route::get('/cantVisitasPrincipales', 'App\Http\Controllers\ContentController@contVisitasPrincipales');
    Route::get('/noticias-mas-visitadas', 'App\Http\Controllers\ContentController@noticiasMasVisitadas');

    // Rutas publicas para videosGenerales
    Route::get('/videos-publicos', 'App\Http\Controllers\VideoFileController@index');
    // Rutas publicas para videosEvento
    Route::get('/videos-publicos-eventos', 'App\Http\Controllers\VideosEventoController@index');
    
    // Rutas publicas para auth
    Route::post('register', [UserController::class, 'register']);
    Route::post('login', [UserController::class, 'login']);
    Route::get('user-count', [UserController::class, 'countUsers']);

    // Rutas para contar las visitas
    Route::post('/visitas-diarias-guardar', 'App\Http\Controllers\ContadorController@guardarVisitasDiarias');
    Route::get('/visitas-diarias', 'App\Http\Controllers\ContadorController@visitasDiarias');
    Route::get('/visitas-semanales', 'App\Http\Controllers\ContadorController@visitasSemanales');
    Route::get('/visitas-mensuales', 'App\Http\Controllers\ContadorController@visitasMensuales');
    Route::get('/visitas-anuales', 'App\Http\Controllers\ContadorController@visitasAnuales');
    
    
    
    
    
    
    
    
    
    
    
