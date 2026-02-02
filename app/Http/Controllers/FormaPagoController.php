<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Response;
use App\Models\FormaPago;

class FormaPagoController extends Controller
{
    public function campos_requeridos(){
    	$id = $_POST['fpago_id'];

    	if (isset($id)) {
    		$forma_pago = FormaPago::findOrFail($id);
    		return Response::json($forma_pago);
    	}
    }

    public function formas_pago(){
        $listado = FormaPago::select('id', 'descripcion')
                   ->where('recibos', 'N')
                   ->orderBy('id', 'ASC')
                   ->get();
        return Response::json($listado);
    }
}
