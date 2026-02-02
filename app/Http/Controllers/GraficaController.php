<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use DB;
use Response;
use App\Models\Admision;

class GraficaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($fecha_inicial, $fecha_final){
        return view('graficos.index', compact('fecha_inicial', 'fecha_final'));
    }

    public function trae_datos(){
        $fecha_inicial = $_POST['fecha_inicial'];
        $fecha_final   = $_POST['fecha_final'];

        $arreglo_final          = [];
        $cargos_array           = [];
        $anulaciones_array      = [];
        $antiguedad_label_array = [];
        $antiguedad_data_array  = [];
        $admisiones_array       = [];
        $tipo_admision          = ['C', 'P', 'H'];

        $query = DB::table('detalle_movimientos as dm')
                 ->whereNotNull('admision_id')
                 ->whereBetween('created_at', [$fecha_inicial, $fecha_final])
                 ->where('estado', 'A')
                 ->select(DB::raw('COUNT(1) as total_cargos'), DB::raw('SUM(IFNULL(precio_total,0)) as suma_total'))
                 ->first();

        $anulaciones = DB::table('documentoventa_maestros as dm')
                       ->join(DB::raw('(SELECT dd.documentoventa_maestro_id, SUM(dd.precio_neto) as total
                                        FROM documentoventa_detalles as dd
                                        GROUP BY dd.documentoventa_maestro_id) as detalle'),
                             function($j){
                                $j->on('dm.id', '=', 'detalle.documentoventa_maestro_id');
                             })
                       ->where('dm.estado', 2)
                       ->whereBetween('dm.fecha_anulacion', [$fecha_inicial, $fecha_final])
                       ->groupBy('detalle.total')
                       ->select(DB::raw('COUNT(1) as cantidad'), 'detalle.total')
                       ->first();

        $cargos_array = ['total_cargos' => $query->total_cargos,
                         'suma_total'   => $query->suma_total];

        if (isset($anulaciones)) {
            $anulaciones_array = ['cantidad_anulaciones' => $anulaciones->cantidad,
                                  'monto_anulaciones'    => $anulaciones->total];
        }else{
            $anulaciones_array = ['cantidad_anulaciones' => 0,
                                  'monto_anulaciones'    => 0];
        }
        

        array_push($antiguedad_label_array, ['dias' => 30]);
        array_push($antiguedad_label_array, ['dias' => 60]);
        array_push($antiguedad_label_array, ['dias' => 90]);
        array_push($antiguedad_label_array, ['dias' => 120]);
        array_push($antiguedad_label_array, ['dias' => 999]);

        $dia_inicial = 0;
        $dia_final   = 0;
        foreach ($antiguedad_label_array as $valor) {
            $dia_final = $valor['dias'];
            $data = DB::table('documentoventa_maestros as dm')
                    ->join('documentoventa_detalles as dd', 'dm.id', 'dd.documentoventa_maestro_id')
                    ->leftjoin(DB::raw('(SELECT documentoventa_id, SUM(monto_aplicado) as total_pago
                                         FROM pago_documentos
                                         WHERE estado = "A"
                                         GROUP BY documentoventa_id) as pd'),
                                function($j){
                                   $j->on('dm.id', '=', 'pd.documentoventa_id');
                               })
                    ->whereBetween('dm.fecha_emision', [$fecha_inicial, $fecha_final])
                    ->where('dm.estado', 1)
                    ->whereRaw('datediff(NOW(), dm.fecha_emision) >= '.$dia_inicial)
                    ->whereRaw('datediff(NOW(), dm.fecha_emision) <= '.$dia_final)
                    ->groupBy('pd.total_pago')
                    ->selectRaw('sum(dd.precio_neto)-ifnull(pd.total_pago, 0) as total')
                    ->first();

            if (!isset($data)) {
                array_push($antiguedad_data_array, 0);
            }else{
                array_push($antiguedad_data_array, $data->total);
            }

            $dia_inicial = $dia_final+1;
        }

        $citas = DB::table('agendas as a')
                 ->where('a.empresa_id', Auth::user()->empresa_id)
                 ->whereDate('a.fecha_inicio', '>=', $fecha_inicial)
                 ->whereDate('a.fecha_inicio', '<=', $fecha_final)
                 ->whereRaw('(paciente_id is not null or nombre_completo is not null)')
                 ->count();

        $admisiones = DB::table('admisiones as a')
                      ->where('a.empresa_id', Auth::user()->empresa_id)
                      ->whereDate('a.fecha', '>=', $fecha_inicial)
                      ->whereDate('a.fecha', '<=', $fecha_final)
                      ->count();

        $admisiones_activas = DB::table('admisiones as a')
                              ->where('a.empresa_id', Auth::user()->empresa_id)
                              ->whereDate('a.fecha', '>=', $fecha_inicial)
                              ->whereDate('a.fecha', '<=', $fecha_final)
                              ->where('a.estado', 'P')
                              ->count();

        $admisiones_con_saldo = DB::table('admisiones as a')
                                ->join('detalle_movimientos as dm', 'a.id', 'dm.admision_id')
                                ->where('a.empresa_id', Auth::user()->empresa_id)
                                ->whereDate('a.fecha', '>=', $fecha_inicial)
                                ->whereDate('a.fecha', '<=', $fecha_final)
                                ->where('dm.estado', 'A')
                                ->groupBy('dm.admision_id')
                                ->select('dm.admision_id', DB::raw('SUM(IFNULL(dm.precio_total,0)) as total'))
                                ->get();
        
        $cantidad_con_saldo = 0;
        $monto_con_saldo    = 0;


        foreach ($admisiones_con_saldo as $registro) {
            $cantidad_con_saldo += 1;
            $monto_con_saldo += $registro->total;
        }

        $admisiones_consulta = Admision::where('empresa_id', Auth::user()->empresa_id)
                               ->whereDate('fecha', '>=', $fecha_inicial)
                               ->whereDate('fecha', '<=', $fecha_final)
                               ->where('tipo_admision', 'C')
                               ->count();

        $admisiones_procedimiento = Admision::where('empresa_id', Auth::user()->empresa_id)
                                    ->whereDate('fecha', '>=', $fecha_inicial)
                                    ->whereDate('fecha', '<=', $fecha_final)
                                    ->where('tipo_admision', 'P')
                                    ->count();

        $admisiones_hospitalizacion = Admision::where('empresa_id', Auth::user()->empresa_id)
                                      ->whereDate('fecha', '>=', $fecha_inicial)
                                      ->whereDate('fecha', '<=', $fecha_final)
                                      ->where('tipo_admision', 'H')
                                      ->count();

        //=======================================================================================
        // Finanzas
        //=======================================================================================
        $hoy   = Carbon::now()->format('Y-m-d');
        $series    = [];
        $drilldown = [];

        $ventas = DB::table('documentoventa_maestros as dvm')
                  ->join(DB::raw('(SELECT documentoventa_maestro_id, SUM(precio_neto) AS total
                                   FROM documentoventa_detalles 
                                   GROUP BY documentoventa_maestro_id ) as dvd'),
                                 function($j){
                                     $j->on('dvm.id', '=', 'dvd.documentoventa_maestro_id');
                                 }
                         )
                    ->whereDate('dvm.fecha_emision', '>= ', $fecha_inicial)
                    ->whereDate('dvm.fecha_emision', '<= ', $fecha_final)
                    ->where('dvm.estado', 1)
                    ->selectRaw('AVG(dvd.total) as promedio, SUM(dvd.total) as suma')
                    ->tosql();
        dd($ventas);

        $dvd = DB::table('documentoventa_detalles')
                ->select('documentoventa_maestro_id', DB::raw('SUM(precio_neto) as total'))
                ->groupBy('documentoventa_maestro_id');

        $pd = DB::table('pago_documentos')
                ->select('documentoventa_id', DB::raw('SUM(monto_aplicado) as total_aplicado'))
                ->where('estado', 1)
                ->groupBy('documentoventa_id');

        //===============================================================================
        // 0 - 30
        //===============================================================================
                // dd($fecha_inicial.' - '.$fecha_final);
        $data = DB::table('documentoventa_maestros as dvm')
                ->joinSub($dvd, 'dvd', function($join){
                    $join->on('dvm.id', '=', 'dvd.documentoventa_maestro_id');
                })
                ->leftJoinSub($pd, 'pd', function($join){
                    $join->on('dvm.id', '=', 'pd.documentoventa_id');
                })
                ->select(
                    'dvm.nombre',
                    DB::raw('COUNT(DISTINCT dvm.id) as cantidad'),
                    DB::raw('SUM(IFNULL(dvd.total,0)) - SUM(IFNULL(pd.total_aplicado,0)) as suma')
                )
                ->whereRaw("DATEDIFF('$hoy', dvm.fecha_emision) BETWEEN 0 AND 30")
                ->where('dvm.estado', 1)
                ->whereDate('dvm.fecha_emision', '>= ', $fecha_inicial)
                ->whereDate('dvm.fecha_emision', '<= ', $fecha_final)
                ->groupBy('dvm.nombre')
                ->havingRaw('SUM(dvd.total) - IFNULL(SUM(pd.total_aplicado),0) != 0')
                ->get();

        $detalle = [];
        $suma = 0;
        foreach ($data as $dat) {
            // $detalle[] = [$dat->nombre, (float)$dat->suma]; 
            $detalle[] = [
                'name' => $dat->nombre,
                'y'    => (float) $dat->suma,
                'custom' => [
                    'cantidad' => (int) $dat->cantidad
                ]
            ];
            // aquí 1 es el valor, podrías usar otro campo
            $suma += $dat->suma;
        }

        $series[] = [
            'name' => '0-30 dias',
            'y'    => (float) $suma,
            'drilldown' => '0-30',
            'custom' => [
                'cantidad' => array_sum(array_column($detalle, 1)) // o el total de cantidad
            ]
        ];

        $drilldown[] = [
            'id'   => '0-30',
            'data' => $detalle,
        ];

        //===============================================================================
        // 31 - 60
        //===============================================================================
        $data = DB::table('documentoventa_maestros as dvm')
                ->joinSub($dvd, 'dvd', function($join){
                    $join->on('dvm.id', '=', 'dvd.documentoventa_maestro_id');
                })
                ->leftJoinSub($pd, 'pd', function($join){
                    $join->on('dvm.id', '=', 'pd.documentoventa_id');
                })
                ->select(
                    'dvm.nombre',
                    DB::raw('COUNT(DISTINCT dvm.id) as cantidad'),
                    DB::raw('SUM(IFNULL(dvd.total,0)) - SUM(IFNULL(pd.total_aplicado,0)) as suma')
                )
                ->whereRaw("DATEDIFF('$hoy', dvm.fecha_emision) BETWEEN 31 AND 60")
                ->where('dvm.estado', 1)
                ->whereDate('dvm.fecha_emision', '>= ', $fecha_inicial)
                ->whereDate('dvm.fecha_emision', '<= ', $fecha_final)
                ->groupBy('dvm.nombre')
                ->havingRaw('SUM(dvd.total) - IFNULL(SUM(pd.total_aplicado),0) != 0')
                ->get();

        $detalle = [];
        $suma = 0;
        foreach ($data as $dat) {
            // $detalle[] = [$dat->nombre, (float)$dat->suma]; 
            $detalle[] = [
                'name' => $dat->nombre,
                'y'    => (float) $dat->suma,
                'custom' => [
                    'cantidad' => (int) $dat->cantidad
                ]
            ];
            // aquí 1 es el valor, podrías usar otro campo
            $suma += $dat->suma;
        }

        $series[] = [
            'name' => '31-60 dias',
            'y'    => (float) $suma,
            'drilldown' => '31-60',
            'custom' => [
                'cantidad' => array_sum(array_column($detalle, 1)) // o el total de cantidad
            ]
        ];

        $drilldown[] = [
            'id'   => '31-60',
            'data' => $detalle,
        ];

        //===============================================================================
        // 61 - 90
        //===============================================================================
        $data = DB::table('documentoventa_maestros as dvm')
                ->joinSub($dvd, 'dvd', function($join){
                    $join->on('dvm.id', '=', 'dvd.documentoventa_maestro_id');
                })
                ->leftJoinSub($pd, 'pd', function($join){
                    $join->on('dvm.id', '=', 'pd.documentoventa_id');
                })
                ->select(
                    'dvm.nombre',
                    DB::raw('COUNT(DISTINCT dvm.id) as cantidad'),
                    DB::raw('SUM(IFNULL(dvd.total,0)) - SUM(IFNULL(pd.total_aplicado,0)) as suma')
                )
                ->whereRaw("DATEDIFF('$hoy', dvm.fecha_emision) BETWEEN 61 AND 90")
                ->where('dvm.estado', 1)
                ->whereDate('dvm.fecha_emision', '>= ', $fecha_inicial)
                ->whereDate('dvm.fecha_emision', '<= ', $fecha_final)
                ->groupBy('dvm.nombre')
                ->havingRaw('SUM(dvd.total) - IFNULL(SUM(pd.total_aplicado),0) != 0')
                ->get();


        $detalle = [];
        $suma = 0;
        foreach ($data as $dat) {
            // $detalle[] = [$dat->nombre, (float)$dat->suma]; 
            $detalle[] = [
                'name' => $dat->nombre,
                'y'    => (float) $dat->suma,
                'custom' => [
                    'cantidad' => (int) $dat->cantidad
                ]
            ];
            // aquí 1 es el valor, podrías usar otro campo
            $suma += $dat->suma;
        }

        $series[] = [
            'name' => '61-90 dias',
            'y'    => (float) $suma,
            'drilldown' => '61-90',
            'custom' => [
                'cantidad' => array_sum(array_column($detalle, 1)) // o el total de cantidad
            ]
        ];

        $drilldown[] = [
            'id'   => '61-90',
            'data' => $detalle,
        ];

        //===============================================================================
        // +90
        //===============================================================================
        $data = DB::table('documentoventa_maestros as dvm')
                ->joinSub($dvd, 'dvd', function($join){
                    $join->on('dvm.id', '=', 'dvd.documentoventa_maestro_id');
                })
                ->leftJoinSub($pd, 'pd', function($join){
                    $join->on('dvm.id', '=', 'pd.documentoventa_id');
                })
                ->select(
                    'dvm.nombre',
                    DB::raw('COUNT(DISTINCT dvm.id) as cantidad'),
                    DB::raw('SUM(IFNULL(dvd.total,0)) - SUM(IFNULL(pd.total_aplicado,0)) as suma')
                )
                ->whereRaw("DATEDIFF('$hoy', dvm.fecha_emision) > 90")
                ->where('dvm.estado', 1)
                ->whereDate('dvm.fecha_emision', '>= ', $fecha_inicial)
                ->whereDate('dvm.fecha_emision', '<= ', $fecha_final)
                ->groupBy('dvm.nombre')
                ->havingRaw('SUM(dvd.total) - IFNULL(SUM(pd.total_aplicado),0) != 0')
                ->get();

        $detalle = [];
        $suma = 0;
        foreach ($data as $dat) {
            // $detalle[] = [$dat->nombre, (float)$dat->suma]; 
            $detalle[] = [
                'name' => $dat->nombre,
                'y'    => (float) $dat->suma,
                'custom' => [
                    'cantidad' => (int) $dat->cantidad
                ]
            ];
            // aquí 1 es el valor, podrías usar otro campo
            $suma += $dat->suma;
        }

        $series[] = [
            'name' => '+90 dias',
            'y'    => (float) $suma,
            'drilldown' => '+90',
            'custom' => [
                'cantidad' => array_sum(array_column($detalle, 1)) // o el total de cantidad
            ]
        ];

        $drilldown[] = [
            'id'   => '+90',
            'data' => $detalle,
        ];


        //=======================================================================================
        // Final
        //=======================================================================================
        array_push($arreglo_final, ['ticket_promedio' => number_format($ventas->promedio, 2, '.', ',')]);
        array_push($arreglo_final, ['total_ventas' => number_format($ventas->suma, 2, '.', ',')]);
        array_push($arreglo_final, ['series' => $series]);
        array_push($arreglo_final, $series);
        array_push($arreglo_final, $drilldown);

        return Response::json($arreglo_final);
    }
}
