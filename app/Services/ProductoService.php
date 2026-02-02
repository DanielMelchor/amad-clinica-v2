<?php

        namespace App\Services;
        use App\Models\Producto;
        use Auth;

        /**
         * 
         */
        class ProductoService{
                public function trae_producto_procedimiento(){
                        $registros = Producto::where('empresa_id', Auth::user()->empresa_id)
                                             ->where('clasificacion', 14)
                                             ->where('estado', 1)
                                             ->select('id', 'descripcion')
                                             ->get();

                        return $registros;
                }

                public function trae_premedicacion(){
                        $registros = Producto::where('empresa_id', Auth::user()->empresa_id)
                                             ->where('premedicacion', 1)
                                             ->where('estado', 1)
                                             ->select('id', 'descripcion')
                                             ->get();

                        return $registros;
                }
        }