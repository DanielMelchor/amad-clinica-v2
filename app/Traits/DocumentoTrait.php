<?php 

namespace App\Traits;

use Auth;
use DB;

trait DocumentoTrait
{

  public function formatearMoneda(mixed $valor): string
	{
	    return 'GTQ. ' . number_format($valor, 2, ',', '.');
	}

	public function DocumentoTotal($tipo_documento_id, $serie, $correlativo){
    	$detalle = DB::table('documentoventa_detalles as dvd')
                   ->join('documentoventa_maestros as dvm', 'dvd.documentoventa_maestro_id', 'dvm.id')
                   ->where('dvm.tipodocumento_id', $tipo_documento_id)
                   ->where('dvm.serie', $serie)
                   ->where('dvm.correlativo', $correlativo)
                   ->groupBy('dvd.documentoventa_maestro_id')
                   ->where('dvd.estado', 1)
                   ->select(DB::raw('SUM(dvd.precio_neto) as total'))
                   ->first();

        return ((float)$detalle->total);
	}

	public function DocumentoTotalPagado($tipo_documento_id, $serie, $correlativo){
	    $total = DB::table('documentoventa_maestros as dvm')
	             ->join('pago_documentos as pd', 'dvm.id', 'pd.documentoventa_id')
	             ->where('dvm.tipodocumento_id', $tipo_documento_id)
	             ->where('dvm.serie', $serie)
	             ->where('dvm.correlativo', $correlativo)
	             ->where('pd.estado', 1)
	             ->select(DB::raw('(SUM(pd.monto_aplicado)) as monto_aplicado'))
	             ->first();

	    return ((float)$total->monto_aplicado);
  	}

  	public function DocumentoSaldo($tipo_documento_id, $serie, $correlativo){
		$detalle = DB::table('documentoventa_detalles as dvd')
                   ->join('documentoventa_maestros as dvm', 'dvd.documentoventa_maestro_id', 'dvm.id')
                   ->where('dvm.tipodocumento_id', $tipo_documento_id)
                   ->where('dvm.serie', $serie)
                   ->where('dvm.correlativo', $correlativo)
                   ->groupBy('dvd.documentoventa_maestro_id')
                   ->where('dvd.estado', 1)
                   ->select('dvd.documentoventa_maestro_id', DB::raw('SUM(dvd.precio_neto) as total'));

        $nc = DB::table('documentoventa_maestros as dvm')
              ->join('documentoventa_detalles as dvd', 'dvm.id', '=', 'dvd.documentoventa_maestro_id')
              ->where('dvm.empresa_id', Auth::user()->empresa_id)
              ->where('dvm.tipodocumento_id', 4)
              ->where('dvm.estado', 1)
              ->where('dvm.tipodocumentoafecto_id', $tipo_documento_id)
              ->where('dvm.serie_afecta', $serie)
              ->where('dvm.correlativo_afecto', $correlativo)
              ->groupBy('dvm.tipodocumentoafecto_id', 'dvm.serie_afecta', 'dvm.correlativo_afecto')
              ->select('dvm.tipodocumentoafecto_id', 'dvm.serie_afecta', 'dvm.correlativo_afecto', DB::raw('(SUM(dvd.precio_neto)) as total_nc'));

        $pago = DB::table('pago_maestros as pm')
                ->join('pago_documentos as pd', 'pm.id', 'pd.pago_maestro_id')
                ->join('documentoventa_maestros as dvm', 'pd.documentoventa_id', 'dvm.id')
                ->where('dvm.tipodocumento_id', $tipo_documento_id)
                ->where('dvm.serie', $serie)
                ->where('dvm.correlativo', $correlativo)
                ->where('pm.estado', 1)
                ->where('pd.estado', 1)
                ->groupBy('pd.documentoventa_id')
                ->select('pd.documentoventa_id', DB::raw('(SUM(pd.monto_aplicado)) AS total_pagado'));

        $encabezado = DB::table('documentoventa_maestros as dm')
                      ->JoinSub($detalle, 'det', function($join){
                            $join->on('dm.id', '=', 'det.documentoventa_maestro_id');
                      })
                      ->leftJoinSub($nc, 'nc', function($join){
                            $join->on('dm.tipodocumento_id', '=', 'nc.tipodocumentoafecto_id');
                            $join->on('dm.serie', '=', 'nc.serie_afecta');
                            $join->on('dm.correlativo', '=', 'nc.correlativo_afecto');
                       })
                       ->leftJoinSub($pago, 'pago', function($join){
                            $join->on('dm.id', '=', 'pago.documentoventa_id');
                       })
                      ->where('dm.empresa_id', Auth::user()->empresa_id)
                      ->where('dm.tipodocumento_id', $tipo_documento_id)
                      ->where('dm.serie', $serie)
                      ->where('dm.correlativo', $correlativo)
                      ->select('dm.id', 'det.total', DB::raw('IFNULL(nc.total_nc,0) as total_nc'), DB::raw('IFNULL(pago.total_pagado, 0) as total_pagado'))
                      ->first();  

        return ($encabezado->total - $encabezado->total_nc - $encabezado->total_pagado);
	}
}