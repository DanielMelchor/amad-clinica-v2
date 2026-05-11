<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use Auth;
use DB;
use Response;
use Session;
use Redirect;
use Carbon\Carbon;
use App\Models\Proveedor;
use App\Models\Bodega;
use App\Models\Producto;
use App\Models\ProductoMedida;
use App\Models\MaestroMovimiento;
use App\Models\DetalleMovimiento;

class InvMovimientoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index_compras(){
    	$lista = DB::table('maestro_movimientos as mm')
    	         ->join('proveedores as p', ['mm.empresa_id' => 'p.empresa_id', 'mm.proveedor_id' => 'p.id'])
                 ->join('inventario_transacciones as it', 'mm.inventario_transaccion_id', 'it.id')
    	         ->where('mm.empresa_id', Auth::user()->empresa_id)
                 ->where('it.tipo_transaccion', 'C')
    	         ->select('mm.id','mm.correlativo', 'mm.anio','mm.created_at', 'p.nombre_comercial', 'mm.serie', 'mm.numero_documento', 'mm.total')
    	         ->orderBy('mm.id','DESC')
    	         ->get();
    	return view('inventarios.index_compras', compact('lista'));
    }

    public function index_ajustes(){

        $lista = MaestroMovimiento::with('transaccion') // Si existe la relación
                 ->where('empresa_id', Auth::user()->empresa_id)
                 ->whereHas('transaccion', function($q) {
                     $q->where('tipo_transaccion', 'A'); // Filtramos por tipo 'A' desde la relación
                 })
                 ->latest()
                 ->get();

        return view('inventarios.index_ajustes', compact('lista'));
    }

    public function index_traslado(){
        $lista = MaestroMovimiento::with('transaccion', 'bodegaOrigen', 'BodegaDestino') // Si existe la relación
                 ->where('empresa_id', Auth::user()->empresa_id)
                 ->whereHas('transaccion', function($q) {
                     $q->where('tipo_transaccion', 'T'); // Filtramos por tipo 'A' desde la relación
                 })
                 ->latest()
                 ->get();

        return view('inventarios.index_traslados', compact('lista'));
    }

    public function create_compra(){
        $hoy             = Carbon::now()->format('Y-m-d');
        $proveedores     = Proveedor::where('empresa_id', Auth::user()->empresa_id)->where('estado', 1)->get();
        $bodegas         = Bodega::where('empresa_id', Auth::user()->empresa_id)->where('estado', 1)->get();
        $tipo_documentos = DB::table('tipo_documentos as td')
                           ->join('inventario_transacciones as it','td.inventario_transaccion_id', 'it.id')
                           ->where('it.empresa_id', Auth::user()->empresa_id)
                           ->select('td.id', 'td.descripcion')
                           ->get();
        $productos = Producto::where('empresa_id', Auth::user()->empresa_id)
                     ->where('estado', 1)
                     ->whereIn('clasificacion', [9, 10, 12, 13])
                     ->select('id', 'descripcion', 'medida_id')
                     ->get();

        return view('inventarios.create_compra', compact('proveedores', 'bodegas', 'hoy', 'tipo_documentos', 'productos'));  
    }

    public function create_ajuste(){
        
        $hoy             = Carbon::now()->format('Y-m-d');
        $bodegas         = Bodega::where('empresa_id', Auth::user()->empresa_id)->where('estado', 1)->get();
        $productos = Producto::where('empresa_id', Auth::user()->empresa_id)
                     ->where('estado', 1)
                     ->whereIn('clasificacion', [9, 10, 12, 13])
                     ->select('id', 'descripcion', 'medida_id')
                     ->get();
        return view('inventarios.create_ajuste', compact('bodegas', 'hoy', 'productos'));

    }

    public function create_traslado(){
        $hoy             = Carbon::now()->format('Y-m-d');
        $bodegas         = Bodega::where('empresa_id', Auth::user()->empresa_id)->where('estado', 1)->get();
        $productos = Producto::where('empresa_id', Auth::user()->empresa_id)
                     ->where('estado', 1)
                     ->whereIn('clasificacion', [9, 10, 12, 13])
                     ->select('id', 'descripcion', 'medida_id')
                     ->get();
        return view('inventarios.create_traslado', compact('bodegas', 'hoy', 'productos'));
    }

    public function store_compra(Request $request)
    {
        // 1. Validación de datos
        $validData = $request->validate([
            'proveedor_id'      => 'required',
            'nit'               => 'required',
            'documento_id'      => 'required',
            'serie'             => 'required',
            'numero_documento'  => 'required',
            'fecha_emision'     => 'required|date',
            'fecha_vencimiento' => 'required|date',
            'dias_credito'      => 'required|integer',
            'total'             => 'required|numeric',
            'bodega_id'         => 'required',
            'productos'         => 'required|array|min:1',
            'productos.*.articulo_id'      => 'required|exists:productos,id',
            'productos.*.unidad_medida_id' => 'required',
            'productos.*.cantidad'         => 'required|numeric|min:0.01',
            'productos.*.precio_unitario'  => 'required|numeric|min:0.01',
            'productos.*.precio_total'     => 'required|numeric|min:0.01',
        ]);

        // 2. Validación de cuadre de montos
        $montoManual = floatval($request->total);
        $montoCalculado = floatval($request->total_final);

        if (abs($montoManual - $montoCalculado) > 0.01) {
            return back()->withInput()->with([
                'type' => 'error',
                'message' => 'El total del documento no coincide con el detalle de artículos.'
            ]);
        }

        // 3. Obtener tipo de transacción
        $inv_transaccion = DB::table('inventario_transacciones')->where('id', 1)->first();
        if (!$inv_transaccion) {
            return back()->with(['message' => 'Tipo de Transacción no localizada', 'type' => 'error']);
        }

        DB::beginTransaction();
        try {
            $anio = Carbon::now()->format('Y');
            $empresa_id = Auth::user()->empresa_id;

            // --- BLOQUEO Y CORRELATIVO ---
            // Bloqueamos la fila del máximo correlativo para evitar duplicados por concurrencia
            $correlativoData = DB::table('maestro_movimientos')
                ->where('empresa_id', $empresa_id)
                ->where('inventario_transaccion_id', $inv_transaccion->id)
                ->where('anio', $anio)
                ->lockForUpdate() 
                ->selectRaw('IFNULL(MAX(correlativo), 0) as ultimo_correlativo')
                ->first();

            $nuevo = $correlativoData->ultimo_correlativo + 1;

            // --- GUARDAR MAESTRO ---
            $compra = new MaestroMovimiento();
            $compra->empresa_id                = $empresa_id;
            $compra->inventario_transaccion_id = $inv_transaccion->id;
            $compra->signo                     = $inv_transaccion->signo;
            $compra->correlativo               = $nuevo;
            $compra->anio                      = $anio;
            $compra->bodega_origen_id          = $validData['bodega_id'];
            $compra->proveedor_id              = $validData['proveedor_id'];
            $compra->nit                       = strtoupper($validData['nit']);
            $compra->tipo_documento_id         = $validData['documento_id'];
            $compra->serie                     = strtoupper($validData['serie']);
            $compra->numero_documento          = $validData['numero_documento'];
            $compra->fecha_emision             = $validData['fecha_emision'];
            $compra->dias_credito              = $validData['dias_credito'];
            $compra->fecha_vencimiento         = $validData['fecha_vencimiento'];
            $compra->total                     = $validData['total'];
            $compra->estado                    = 1;
            $compra->save();

            // --- GUARDAR DETALLE ---
            foreach ($request->productos as $item) {
                $producto = Producto::findOrFail($item['articulo_id']);
                $producto_medida = ProductoMedida::where('producto_id', $producto->id)
                    ->where('unidad_medida_id', $item['unidad_medida_id'])
                    ->first();

                $movimiento = new DetalleMovimiento();
                $movimiento->maestro_movimiento_id = $compra->id;
                $movimiento->producto_id           = $item['articulo_id'];
                $movimiento->descripcion           = $producto->descripcion;
                $movimiento->unidad_medida_id      = $item['unidad_medida_id'];
                $movimiento->signo                 = $inv_transaccion->signo;
                $movimiento->cantidad              = floatval($item['cantidad']);
                $movimiento->cantidad_medida       = $producto_medida->cantidad ?? 1;
                $movimiento->cantidad_x_medida     = $movimiento->cantidad * $movimiento->cantidad_medida;
                $movimiento->precio_unitario       = floatval($item['precio_unitario']);
                $movimiento->precio_bruto          = floatval($item['precio_total']);
                $movimiento->descuento             = 0;
                $movimiento->recargo               = 0;
                $movimiento->precio_cliente        = 0;
                $movimiento->precio_aseguradora    = 0;
                $movimiento->precio_base           = $movimiento->precio_bruto / 1.12;
                $movimiento->precio_impuesto       = $movimiento->precio_bruto - $movimiento->precio_base;
                $movimiento->precio_total          = $movimiento->precio_bruto;
                $movimiento->estado                = 1;
                $movimiento->save();
            }

            DB::commit();

            return redirect()->route('editar_compra', Crypt::encrypt($compra->id))
                             ->with(['message' => '¡Compra Q' . $nuevo . ' registrada!', 'type' => 'success']);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with([
                'message' => 'Error al guardar: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function store_ajuste(Request $request){
        $validData = $request->validate([
            'fecha'             => 'required|date_format:Y-m-d',
            'bodega_id'         => 'required|exists:bodegas,id',
            'productos'         => 'required|array|min:1',
            'productos.*.articulo_id'      => 'required|exists:productos,id',
            'productos.*.unidad_medida_id' => 'required',
            'productos.*.cantidad'         => 'required|numeric|min:0.01',
        ]);

        $inv_transaccion = DB::table('inventario_transacciones')
                            ->where('empresa_id', Auth::user()->empresa_id)
                            ->where('tipo_transaccion', 'A')
                            ->where('estado', 1)
                            ->first();
        
        if (!$inv_transaccion) {
            return back()->with(['message' => 'Tipo de Transacción no localizada', 'type' => 'error']);
        }

        DB::beginTransaction();
        try {
            $anio = Carbon::now()->format('Y');

            $ultimo_correlativo = DB::table('maestro_movimientos')
                                ->where('empresa_id', Auth::user()->empresa_id)
                                ->where('inventario_transaccion_id', $inv_transaccion->id)
                                ->where('anio', $anio)
                                ->max('correlativo') ?? 0;

            $nuevo_correlativo = $ultimo_correlativo + 1;

            $ajuste = new MaestroMovimiento();
            $ajuste->empresa_id                = Auth::user()->empresa_id;
            $ajuste->inventario_transaccion_id = $inv_transaccion->id;
            $ajuste->fecha_emision             = $validData['fecha'];
            $ajuste->signo                     = $inv_transaccion->signo;
            $ajuste->correlativo               = $nuevo_correlativo;
            $ajuste->anio                      = $anio;
            $ajuste->bodega_origen_id          = $validData['bodega_id'];
            $ajuste->estado                    = 1;
            $ajuste->save();

            foreach ($request->productos as $item) {
                $producto = producto::find($item['articulo_id']);
                $producto_medida = ProductoMedida::where('producto_id', $item['articulo_id'])
                                    ->where('unidad_medida_id', $item['unidad_medida_id'])
                                    ->firstOrFail();

                $detalle = new DetalleMovimiento();
                $detalle->maestro_movimiento_id = $ajuste->id;
                $detalle->producto_id        = $item['articulo_id'];
                $detalle->descripcion        = $producto->descripcion_a_mostrar;
                $detalle->unidad_medida_id   = $item['unidad_medida_id'];
                $detalle->signo              = $item['tipo'] ?? $ajuste->signo;
                $detalle->cantidad           = $item['cantidad'];
                $detalle->cantidad_medida    = $producto_medida->cantidad;
                $detalle->cantidad_x_medida  = $item['cantidad'] * $producto_medida->cantidad;
                $detalle->precio_unitario    = 0;
                $detalle->precio_bruto       = 0;
                $detalle->descuento          = 0;
                $detalle->recargo            = 0;
                $detalle->precio_base        = 0;
                $detalle->precio_impuesto    = 0;
                $detalle->precio_total       = 0;
                $detalle->precio_cliente     = 0;
                $detalle->precio_aseguradora = 0;
                $detalle->estado             = 1;
                $detalle->save();
            }
            DB::commit();

            $ajusteId = Crypt::encrypt($ajuste->id);
            return redirect()->route('editar_ajuste', [$ajusteId])
                             ->with(['message' => '¡Registro almacenado con éxito!', 'type' => 'success']);

        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al guardar: ' . $e->getMessage()
            ], 500);
            //return back()->withInput()->with(['message' => 'Error al guardar: ' . $e->getMessage(), 'type' => 'error']);
        }
    }

    public function store_traslado(Request $request){
        // 1. Validación de datos
        $validData = $request->validate([
            'fecha'              => 'required',
            'bodega_origen_id'   => 'required',
            'bodega_destino_id'  => 'required',
            'fecha'              => 'required|date',
            'productos'          => 'required|array|min:1',
            'productos.*.articulo_id'      => 'required|exists:productos,id',
            'productos.*.unidad_medida_id' => 'required',
            'productos.*.cantidad'         => 'required|numeric|min:0.01'
        ]);

        $inv_transaccion = DB::table('inventario_transacciones')
                            ->where('empresa_id', Auth::user()->empresa_id)
                            ->where('tipo_transaccion', 'T')
                            ->where('estado', 1)
                            ->first();
        
        if (!$inv_transaccion) {
            return back()->with(['message' => 'Tipo de Transacción no localizada', 'type' => 'error']);
        }

        DB::beginTransaction();
        try {
            $anio = Carbon::now()->format('Y');
            $empresa_id = Auth::user()->empresa_id;

            // --- BLOQUEO Y CORRELATIVO ---
            // Bloqueamos la fila del máximo correlativo para evitar duplicados por concurrencia
            $correlativoData = DB::table('maestro_movimientos')
                ->where('empresa_id', $empresa_id)
                ->where('inventario_transaccion_id', $inv_transaccion->id)
                ->where('anio', $anio)
                ->lockForUpdate() 
                ->selectRaw('IFNULL(MAX(correlativo), 0) as ultimo_correlativo')
                ->first();

            $nuevo = $correlativoData->ultimo_correlativo + 1;

            // --- GUARDAR MAESTRO ---
            $traslado = new MaestroMovimiento();
            $traslado->empresa_id                = $empresa_id;
            $traslado->inventario_transaccion_id = $inv_transaccion->id;
            $traslado->signo                     = $inv_transaccion->signo;
            $traslado->correlativo               = $nuevo;
            $traslado->anio                      = $anio;
            $traslado->bodega_origen_id          = $validData['bodega_origen_id'];
            $traslado->bodega_destino_id         = $validData['bodega_destino_id'];
            $traslado->fecha_emision             = $validData['fecha'];
            $traslado->estado                    = 1;
            $traslado->save();

            // --- GUARDAR DETALLE ---
            foreach ($request->productos as $item) {
                $producto = Producto::findOrFail($item['articulo_id']);
                $producto_medida = ProductoMedida::where('producto_id', $producto->id)
                    ->where('unidad_medida_id', $item['unidad_medida_id'])
                    ->first();

                $traslado->detalles()->create([
                                                'producto_id'       => $item['articulo_id'],
                                                'descripcion'       => $producto->descripcion,
                                                'unidad_medida_id'  => $item['unidad_medida_id'],
                                                'signo'             => -1,
                                                'cantidad'          => floatval($item['cantidad']),
                                                'cantidad_medida'   => $producto_medida->cantidad ?? 1,
                                                'cantidad_x_medida' => floatval($item['cantidad']) * ($producto_medida->cantidad ?? 1),
                                                'precio_unitario'   => 0,
                                                'precio_bruto'      => 0,
                                                'descuento'         => 0,
                                                'recargo'           => 0,
                                                'precio_cliente'    => 0,
                                                'precio_aseguradora'=> 0,
                                                'precio_base'       => 0,
                                                'precio_impuesto'   => 0,
                                                'precio_total'      => 0,
                                                'estado'            => 1,
                                            ]);

            }

            DB::commit();

            return redirect()->route('editar_traslado', Crypt::encrypt($traslado->id))
                             ->with(['message' => 'Traslado ' . $nuevo . ' registrada!', 'type' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                                        'message' => $e->getMessage(),
                                        'type'    => 'error'
                                    ], 422); // Enviamos un código 422 (Unprocessable Entity)
        }

    }

    public function edit_compra($id){
        $registroId      = Crypt::decrypt($id);

        $proveedores     = Proveedor::where('empresa_id', Auth::user()->empresa_id)->where('estado', 1)->get();

        $bodegas         = Bodega::where('empresa_id', Auth::user()->empresa_id)->where('estado', 1)->get();

        $tipo_documentos = DB::table('tipo_documentos as td')
                           ->join('inventario_transacciones as it','td.inventario_transaccion_id', 'it.id')
                           ->where('it.empresa_id', Auth::user()->empresa_id)
                           ->select('td.id', 'td.descripcion')
                           ->get();

        $productos = Producto::where('empresa_id', Auth::user()->empresa_id)
                     ->where('estado', 1)
                     ->whereIn('clasificacion', [9, 10, 12, 13])
                     ->select('id', 'descripcion', 'medida_id')
                     ->get();

        $esSoloLectura = !Auth::user()->hasAnyRole(['Administrador', 'Super Admin']);

        // En el controlador
        $encabezado = MaestroMovimiento::with(['proveedor', 
                                               'tipoDocumento', 
                                               'bodega',
                                               'detalles' => function($query) {
                                                    $query->where('estado', 1); // Filtra solo los detalles activos
                                            }])->findOrFail($registroId);
        $detalle = DetalleMovimiento::where('maestro_movimiento_id', $registroId)->where('estado', 1)->get();

        return view('inventarios.edit_compra', compact('encabezado', 'detalle', 'proveedores', 'bodegas', 'tipo_documentos', 'productos', 'esSoloLectura'));
    }

    public function edit_ajuste($id){
        $registroId      = Crypt::decrypt($id);
        $bodegas         = Bodega::where('empresa_id', Auth::user()->empresa_id)->where('estado', 1)->get();

        $encabezado = MaestroMovimiento::with(['detalles.unidadMedida'])->findOrFail($registroId);

        $esSoloLectura = !Auth::user()->hasAnyRole(['Administrador', 'Super Admin']);

        // $detalle         = DB::table('detalle_movimientos as dm')
        //                    ->join('unidad_medidas as um', 'dm.unidad_medida_id', 'um.id')
        //                    ->where('dm.maestro_movimiento_id', $registroId)
        //                    ->where('dm.estado', '!=', 2)
        //                    ->select('dm.id', 'dm.producto_id', 'dm.descripcion as producto_descripcion', 'dm.unidad_medida_id', 'um.descripcion as unidad_medida_descripcion', 'dm.cantidad', 'dm.producto_caracteristica_id', 'signo')
        //                    ->get();

        $productos = Producto::where('empresa_id', Auth::user()->empresa_id)
                     ->where('estado', 1)
                     ->whereIn('clasificacion', [9, 10, 12, 13])
                     ->select('id', 'descripcion', 'medida_id')
                     ->get();

        return view('inventarios.edit_ajuste', compact('bodegas', 'encabezado', 'productos', 'esSoloLectura'));
    }

    public function edit_traslado($id){
        $registroId      = Crypt::decrypt($id);
        $bodegas         = Bodega::where('empresa_id', Auth::user()->empresa_id)->where('estado', 1)->get();

        $productos = Producto::where('empresa_id', Auth::user()->empresa_id)
                     ->where('estado', 1)
                     ->whereIn('clasificacion', [9, 10, 12, 13])
                     ->select('id', 'descripcion', 'medida_id')
                     ->get();

        $esSoloLectura = !Auth::user()->hasAnyRole(['Administrador', 'Super Admin']);

        $encabezado = MaestroMovimiento::with(['bodega',
                                               'detalles' => function($query) {
                                                    $query->where('estado', 1); // Filtra solo los detalles activos
                                            }])->findOrFail($registroId);

        $detalle = DetalleMovimiento::where('maestro_movimiento_id', $registroId)->where('estado', 1)->get();

        return view('inventarios.edit_traslado', compact('encabezado', 'detalle', 'bodegas', 'productos', 'esSoloLectura'));

    }

    public function update_compra(Request $request){
        $validData = $request->validate([
            'compra_id'         => 'required',
            'proveedor_id'      => 'required',
            'nit'               => 'required',
            'documento_id'      => 'required',
            'serie'             => 'required',
            'numero_documento'  => 'required',
            'fecha_emision'     => 'required|date',
            'fecha_vencimiento' => 'required|date',
            'dias_credito'      => 'required|integer',
            'total'             => 'required|numeric',
            'bodega_id'         => 'required',
            'productos'         => 'required|array|min:1',
            'productos.*.articulo_id'      => 'required|exists:productos,id',
            'productos.*.unidad_medida_id' => 'required',
            'productos.*.cantidad'         => 'required|numeric|min:0.01',
            'productos.*.precio_unitario'  => 'required|numeric|min:0.01',
            'productos.*.precio_total'     => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();
        try {
            $ajuste = MaestroMovimiento::findOrFail($validData['compra_id']);
            $ajuste->fecha_emision             = $validData['fecha_emision'];
            $ajuste->bodega_origen_id          = $validData['bodega_id'];
            $ajuste->estado                    = 1;
            $ajuste->save();

            $articulosEnviados = collect($request->productos)->pluck('articulo_id')->toArray();

            DetalleMovimiento::where('maestro_movimiento_id', $validData['compra_id'])
            ->whereNotIn('producto_id', $articulosEnviados)
            ->update(['estado' => 2]);

            foreach ($request->productos as $item) {
                $producto = producto::find($item['articulo_id']);
                $producto_medida = ProductoMedida::where('producto_id', $item['articulo_id'])
                                    ->where('unidad_medida_id', $item['unidad_medida_id'])
                                    ->firstOrFail();

                DetalleMovimiento::updateOrCreate([
                    // Condiciones para buscar el registro existente
                    'maestro_movimiento_id' => $validData['compra_id'],
                    'producto_id'           => $item['articulo_id'],
                    'unidad_medida_id'      => $item['unidad_medida_id'],
                ],
                [
                    // Valores que se actualizarán o insertarán
                    'cantidad'          => $item['cantidad'],
                    'cantidad_medida'   => $producto_medida->cantidad,
                    'cantidad_x_medida' => $item['cantidad'] * $producto_medida->cantidad,
                    'precio_unitario' => $item['precio_unitario'],
                    'precio_total'    => $item['cantidad'] * $item['precio_unitario'],
                    // Agrega aquí otros campos necesarios como bodega_id si aplica
                ]);
            }
            DB::commit();

            return redirect()->back()->with(['message' => '¡Compra actualizada con éxito!',
                                             'type'    => 'success'
                                            ]);

        }catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['message' => 'Error al guardar: ' . $e->getMessage(),
                                             'type'    => 'error'
                                            ]);
        }
    }

    public function update_ajuste(Request $request){
        if (!Auth::user()->hasAnyRole(['Administrador', 'Super Admin'])) {
            return back()->with(['message' => 'No tiene permisos para modificar este registro.', 'type' => 'error']);
        }
        
        $validData = $request->validate([
            'maestro_id'        => 'required',
            'fecha_transaccion' => 'required|date_format:Y-m-d',
            'bodega_id'         => 'required|exists:bodegas,id',
            'productos'         => 'required|array|min:1',
            'productos.*.articulo_id'      => 'required|exists:productos,id',
            'productos.*.unidad_medida_id' => 'required',
            'productos.*.cantidad'         => 'required|numeric|min:0.01',
        ]);

        $inv_transaccion = DB::table('inventario_transacciones')
                            ->where('empresa_id', Auth::user()->empresa_id)
                            ->where('tipo_transaccion', 'A')
                            ->where('estado', 1)
                            ->first();
        
        if (!$inv_transaccion) {
            return back()->with(['message' => 'Tipo de Transacción no localizada', 'type' => 'error']);
        }

        DB::beginTransaction();
        try {
            $ajuste = MaestroMovimiento::findOrFail($validData['maestro_id']);
            $ajuste->fecha_emision             = $validData['fecha_transaccion'];
            $ajuste->bodega_origen_id          = $validData['bodega_id'];
            $ajuste->save();

            $idsMantener = collect($request->productos)->pluck('id')->filter()->toArray();

            DetalleMovimiento::where('maestro_movimiento_id', $ajuste->id)->whereNotIn('id', $idsMantener)->update(['estado' => 2]);

            foreach ($request->productos as $item) {
                $producto = producto::find($item['articulo_id']);
                $producto_medida = ProductoMedida::where('producto_id', $item['articulo_id'])
                                    ->where('unidad_medida_id', $item['unidad_medida_id'])
                                    ->firstOrFail();

                $detalle = DetalleMovimiento::updateOrCreate(
                    // 1. Condición de búsqueda: si existe un registro con estos datos, lo actualiza
                    [
                        'maestro_movimiento_id' => $ajuste->id,
                        'producto_id'           => $item['articulo_id'],
                        'unidad_medida_id'      => $item['unidad_medida_id']
                    ],
                    // 2. Datos a llenar o actualizar
                    [
                        'descripcion'        => $producto->descripcion_a_mostrar,
                        'signo'              => $item['signo'] ?? $ajuste->signo,
                        'cantidad'           => $item['cantidad'],
                        'cantidad_medida'    => $producto_medida->cantidad,
                        'cantidad_x_medida'  => $item['cantidad'] * $producto_medida->cantidad,
                        'precio_unitario'    => 0,
                        'precio_bruto'       => 0,
                        'descuento'          => 0,
                        'recargo'            => 0,
                        'precio_base'        => 0,
                        'precio_impuesto'    => 0,
                        'precio_total'       => 0,
                        'precio_cliente'     => 0,
                        'precio_aseguradora' => 0,
                        'estado'             => 1,
                    ]
                );
                $detalle->save();
            }
            DB::commit();
        
            $ajusteId = Crypt::encrypt($ajuste->id);
            return redirect()->route('editar_ajuste', [$ajusteId])
                             ->with(['message' => '¡Registro actualizado con éxito!', 'type' => 'success']);


        }catch (\Exception $e) {
            DB::rollBack();
            return back()->with(['message' => 'Error al guardar: ' . $e->getMessage(), 'type' => 'error']);
        }

    }

    public function update_traslado(Request $request){
        // 1. Validación de datos
        $validData = $request->validate([
            'traslado_id'        => 'required',
            'fecha'              => 'required',
            'bodega_origen_id'   => 'required',
            'bodega_destino_id'  => 'required',
            'fecha'              => 'required|date',
            'productos'          => 'required|array|min:1',
            'productos.*.articulo_id'      => 'required|exists:productos,id',
            'productos.*.unidad_medida_id' => 'required',
            'productos.*.cantidad'         => 'required|numeric|min:0.01'
        ]);

        $inv_transaccion = DB::table('inventario_transacciones')
                            ->where('empresa_id', Auth::user()->empresa_id)
                            ->where('tipo_transaccion', 'T')
                            ->where('estado', 1)
                            ->first();
        
        if (!$inv_transaccion) {
            return back()->with(['message' => 'Tipo de Transacción no localizada', 'type' => 'error']);
        }

        DB::beginTransaction();
        try {
            $traslado = MaestroMovimiento::findOrFail($validData['traslado_id']);
            $traslado->bodega_origen_id  = $validData['bodega_origen_id'];
            $traslado->bodega_destino_id = $validData['bodega_destino_id'];
            $traslado->fecha_emision     = $validData['fecha'];
            $traslado->save();

            // 1. IDENTIFICAR PRODUCTOS QUE VIENEN DEL FRONTEND
            // Extraemos los IDs de los productos que el usuario quiere conservar o añadir
            $productosFront = collect($request->productos);
            $idsArticulosEnviados = $productosFront->pluck('articulo_id')->toArray();

            // 2. ANULAR SOLO LO QUE YA NO ESTÁ EN EL REQUEST
            // Buscamos los que estaban activos (estado 1) pero NO vienen en el nuevo array
            $detallesAEliminar = DetalleMovimiento::where('maestro_movimiento_id', $traslado->id)
                ->where('estado', 1)
                ->whereNotIn('producto_id', $idsArticulosEnviados)
                ->get();

            foreach ($detallesAEliminar as $detalle) {
                $detalle->estado = 0;
                $detalle->save(); // El Observer reversará SOLO estos productos eliminados
            }

            // 3. PROCESAR PRODUCTOS (Nuevos o Actualizaciones)
            foreach ($productosFront as $item) {
                $producto = Producto::findOrFail($item['articulo_id']);
                $producto_medida = ProductoMedida::where('producto_id', $producto->id)
                    ->where('unidad_medida_id', $item['unidad_medida_id'])
                    ->first();

                $cantidadBase = floatval($item['cantidad']) * ($producto_medida->cantidad ?? 1);

                DetalleMovimiento::updateOrCreate(
                    [
                        'maestro_movimiento_id' => $traslado->id,
                        'producto_id'           => $item['articulo_id'],
                        'unidad_medida_id'      => $item['unidad_medida_id']
                    ],
                    [
                        'descripcion'       => $producto->descripcion,
                        'signo'             => -1,
                        'cantidad'          => floatval($item['cantidad']),
                        'cantidad_medida'   => $producto_medida->cantidad ?? 1,
                        'cantidad_x_medida' => $cantidadBase,
                        'estado'            => 1, // Se asegura de que esté activo
                        // ... (restos de campos en 0)
                    ]
                );
                // El Observer en 'updated' detectará si la cantidad cambió y ajustará la diferencia
            }

            DB::commit();

            return redirect()->route('editar_traslado', Crypt::encrypt($traslado->id))
                             ->with(['message' => '¡Compra Q' . $traslado->correlativo.'-'.$traslado->anio . ' registrada!', 'type' => 'success']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al guardar: ' . $e->getMessage()
            ], 500);
        }
    }

    public function trae_detalle_compra(){
        $compra_id = $_POST['compra_id'];
        
        $detalle = DB::table('detalle_movimientos as dm')
                   ->join('unidad_medidas as um', 'dm.unidad_medida_id', 'um.id')
                   ->where('dm.maestro_movimiento_id', $compra_id)
                   ->where('dm.estado', '!=', 2)
                   ->orderBy('dm.id', 'ASC')
                   ->select('dm.id', 'dm.producto_id', 'dm.descripcion as producto_descripcion', 'dm.unidad_medida_id', 'um.descripcion as unidad_medida_descripcion', 'dm.cantidad', 'dm.precio_unitario', 'dm.precio_total', 'dm.producto_caracteristica_id')
                   ->get();
        return Response::json($detalle);
    }

    public function trae_detalle_ajuste(){
        $ajuste_id = $_POST['ajuste_id'];
        
        $detalle = DB::table('detalle_movimientos as dm')
                   ->join('unidad_medidas as um', 'dm.unidad_medida_id', 'um.id')
                   ->where('dm.maestro_movimiento_id', $ajuste_id)
                   ->select('dm.id', 'dm.producto_id', 'dm.descripcion as producto_descripcion', 'dm.unidad_medida_id', 'um.descripcion as unidad_medida_descripcion', 'dm.cantidad', 'dm.producto_caracteristica_id')
                   ->get();
        return Response::json($detalle);
    }

}
