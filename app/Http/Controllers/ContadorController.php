<?php

namespace App\Http\Controllers;

use App\Models\Contador;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ContadorController extends Controller
{
    // Guardar Visitas Diarias

    public function guardarVisitasDiarias()
{
    DB::beginTransaction();

    try {
        $fechaActual = Carbon::now()->toDateString();

        $contador = Contador::where('fecha', $fechaActual)->first();

        if (!$contador) {
            $contador = new Contador();
            $contador->cantVisitasDia = 1;
            $contador->fecha = $fechaActual;
        } else {
            // Verificar si el contador pertenece a la fecha actual
            $contadorFecha = Carbon::parse($contador->fecha)->toDateString();

            if ($contadorFecha !== $fechaActual) {
                // Crear un nuevo contador para la fecha actual
                $contador = new Contador();
                $contador->cantVisitasDia = 1;
                $contador->fecha = $fechaActual;
            } else {
                // Incrementar las visitas solo si pertenece al día actual
                $contador->increment('cantVisitasDia');
            }
        }

        $contador->save();

        $visitas = $contador->cantVisitasDia;

        DB::commit();

        return response()->json([
            'message' => 'Visitas incrementadas',
            'visitas' => $visitas
        ]);
    } catch (\Exception $e) {
        DB::rollback();
        // Manejar el error, puedes loggearlo o retornar una respuesta de error
        return response()->json(['error' => 'Error al guardar visitas diarias'], 500);
    }
}
    // public function guardarVisitasDiarias()
    // {


    //     return response()->json([
    //         'message' => 'Visitas incrementadas',
    //         'visitas' => $visitas
    //     ]);
    // }

    // Obtener Visitas Diarias
    public function visitasDiarias() 
    {
        $fechaActual = Carbon::now()->toDateString();

        $contador = Contador::where('fecha', $fechaActual)->first();

        return response()->json($contador);

    }

    // Obtener Visitas Semanales
    public function visitasSemanales()
    {
        $fechaActual = Carbon::now();

        // Obtener el día de la semana (0 para domingo, 1 para lunes, etc.)
        $diaSemanaActual = $fechaActual->dayOfWeek;

        // Calcular la fecha del domingo de la semana actual
        $domingoSemanaActual = $fechaActual->subDays($diaSemanaActual)->toDateString();

        // Calcular la fecha del sábado de la semana actual
        $sabadoSemanaActual = Carbon::parse($domingoSemanaActual)->addDays(6)->toDateString();

        // Filtrar los contadores en el rango de fechas de la semana actual
        $contadoresSemanaActual = Contador::whereBetween('fecha', [$domingoSemanaActual, $sabadoSemanaActual])->get();


        if ($contadoresSemanaActual->isEmpty()) {
            return response()->json([
                'message' => 'No hay registros para la semana actual.',
            ]);
        }

        // Sumar las visitas diarias en el rango de fechas
        $visitasSemanales = $contadoresSemanaActual->sum('cantVisitasDia');

        return response()->json( $visitasSemanales );
    }


    // Obtener Visitas mensuales
    public function visitasMensuales()
    {
        $fechaActual = Carbon::now();
        $mesActual = $fechaActual->format('m');
        $annoActual = $fechaActual->format('Y');

        $contador = Contador::whereYear('fecha', $annoActual)
            ->whereMonth('fecha', $mesActual)
            ->get();

        if ($contador->isEmpty()) {
            return response()->json([
                'message' => 'No hay registros para el mes y año actual.',
            ]);
        }

        $visitasMensuales = $contador->sum('cantVisitasDia');

        return response()->json( $visitasMensuales );
    }


    // Obtener Visitas Anuales
    public function visitasAnuales()
    {
        $fechaActual = Carbon::now();
        $annoActual = $fechaActual->format('Y');

        $contador = Contador::whereYear('fecha', $annoActual)
            ->get();

        if ($contador->isEmpty()) {
            return response()->json([
                'message' => 'No hay registros para año actual.',
            ]);
        }

        $visitasAnuales = $contador->sum('cantVisitasDia');

        return response()->json( $visitasAnuales );
    }
}
