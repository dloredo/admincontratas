<?php

    public function agregarPagoPrototipo($id , Request $request)
    {
        $pagos_con = PagosContratas::findOrFail($id);
        $contrata = Contratas::findOrFail($pagos_con->id_contrata);
        $pagos_contratas = PagosContratas::where("id_contrata", $pagos_con->id_contrata)
                            //->where("id","<=", $id)
                            ->get();
        request()->validate([
            'cantidad_pagada'   => 'required',
        ]);
        $cantidad_pagada = $request['cantidad_pagada'];
        $pagar = $contrata->pagos_contrata;
        $residuo = $cantidad_pagada;

        $idAux = 0;
        
        foreach ($pagos_contratas as $pago)
        {
            if($pago->estatus != 1)
            {
                $pago->update([
                    'cantidad_pagada'   => $pagar,
                    'adeudo'            => 0,
                    'adelanto'          => 0,
                    'estatus'           => 1,
                ]);

                $idAux = $pago->id;
                $residuo -= $pagar;

                if($residuo < $pagar) break;
            }
        }
        $saldo = $residuo % $pagar;
        if($residuo % $pagar)
        {
            $pagos_contratas = PagosContratas::findOrFail($idAux + 1);
            

            $pagos_contratas->update([
                'cantidad_pagada'   => $saldo,
                'adeudo'            => $contrata->pagos_contrata - $saldo,
                'adelanto'          => 0,
                'estatus'           => 3,
            ]);
            
        }
        

        
    }


    public function agregarPagoPrototipo($id , Request $request)
    {
        request()->validate([
            'cantidad_pagada'   => 'required',
        ]);

        $pagos_con = PagosContratas::findOrFail($id);
        $contrata = Contratas::findOrFail($pagos_con->id_contrata);
        $pagos_contratas = PagosContratas::where("id_contrata", $pagos_con->id_contrata)
                            ->where("estatus","!=",1)
                            ->orderBy("id", "asc")
                            ->get();

        $pagos_contratas_anterior = PagosContratas::where("id_contrata", $pagos_con->id_contrata)
                                           ->where("id","<=", $id)
                                           ->first();
                        
        $cantidad_pagada = $request['cantidad_pagada'];
        $pagar = $contrata->pagos_contrata;
        $totalPagarAdeudo = $pagos_contratas_anterior->adeudo + $pagar;
        $residuo = $cantidad_pagada;

        $idAux = 0;
        if( $cantidad_pagada == $pagar && $contrata->adeudo == 0)
        {
            $pagos_con->update([
                'cantidad_pagada'   => $cantidad_pagada,
                'adeudo'            => 0,
                'adelanto'          => 0,
                'estatus'           => 1,
            ]);
            $contrata->adeudo = 0;
            $contrata->save();
        }
        else if($residuo < $pagar)
        {
            $pago = $pagos_contratas[0];

            $pago_cantidad_pagada = $pago->cantidad_pagada;
            $pago_adeudo = $pago->adeudo;

            if($pago_cantidad_pagada > 0){
                $pago_cantidad_pagada += $residuo;

                if($pago_cantidad_pagada == $pagar){
                    $pago_adeudo = 0;
                }
                else if ($pago_cantidad_pagada < $pagar)
                {
                    $pago_adeudo = $pagar - $pago_cantidad_pagada;
                }
                else{

                    $pago->update([
                        'cantidad_pagada'   => $pagar,
                        'adeudo'            => 0,
                        'adelanto'          => 0,
                        'estatus'           => 1,
                    ]);

                    $pago_cantidad_pagada -= $pagar;

                    $pago = $pagos_contratas[1];

                    $pago->update([
                        'cantidad_pagada'   => $pago_cantidad_pagada,
                        'adeudo'            => $pagar - $pago_cantidad_pagada,
                        'adelanto'          => 0,
                        'estatus'           => 3,
                    ]);

                }

            }
            else{
                $pago_cantidad_pagada = $residuo;
            }

            $pago->update([
                'cantidad_pagada'   => $pago_cantidad_pagada,
                'adeudo'            =>  $pago_adeudo,
                'adelanto'          => 0,
                'estatus'           => 3,
            ]);
        }
        else{
        

            foreach ($pagos_contratas as $pago)
            {
                if($residuo < $pagar) break;

                $pago->update([
                    'cantidad_pagada'   => $pagar,
                    'adeudo'            => 0,
                    'adelanto'          => 0,
                    'estatus'           => 1,
                ]);

                $idAux = $pago->id;
                $residuo -= $pagar;

                
            }
            $saldo = $residuo % $pagar;
            if($residuo % $pagar)
            {
                $pagos_contratas = PagosContratas::findOrFail($idAux + 1);
                

                $pagos_contratas->update([
                    'cantidad_pagada'   => $saldo,
                    'adeudo'            => $contrata->pagos_contrata - $saldo,
                    'adelanto'          => 0,
                    'estatus'           => 3,
                ]);
                
            }

            if( $cantidad_pagada >= $contrata->adeudo )
            {
                $contrata->adeudo = $contrata->pagos_contrata - $saldo;
                $contrata->save();
            }
            else
            {
                $contrata->adeudo += $contrata->pagos_contrata - $saldo;
                $contrata->save();
            }


        }
        // else if( $cantidad_pagada < $totalPagarAdeudo )
        // {
        //     $pagos_con->update([
        //         'cantidad_pagada'   => $cantidad_pagada,
        //         'adeudo'            => ($pagar + $contrata->adeudo) - $cantidad_pagada,
        //         'adelanto'          => 0,
        //         'estatus'           => 3,
        //     ]);
        //     $contrata->adeudo += ($pagar + $contrata->adeudo) - $cantidad_pagada;
        //     $contrata->save();
        // }
        
        
        

        return back()->with('message', 'Se agrego el cobro con éxito.')->with('estatus',true);
        
    }


    public function agregarPagoPrototipo($id , Request $request)
    {
        request()->validate([
            'cantidad_pagada'   => 'required',
        ]);

        $pagos_con = PagosContratas::findOrFail($id);
        $contrata = Contratas::findOrFail($pagos_con->id_contrata);
        $pagos_contratas = PagosContratas::where("id_contrata", $pagos_con->id_contrata)
                            ->where("estatus","!=",1)
                            ->orderBy("id", "asc")
                            ->get();

       
        $cantidad_pagada = $request['cantidad_pagada'];
        $pagar = $contrata->pagos_contrata;
        $residuo = $cantidad_pagada;

        $idAux = 0;
        if($contrata->adeudo == 0)
        {

            if($cantidad_pagada <= $pagar){

                if($pagos_con->cantidad_pagada == 0){

                    $pagos_con->update([
                        'cantidad_pagada'   => $cantidad_pagada,
                        'estatus'           => 3,
                    ]);

                }
                else{
                    
                    if(($pagos_con->cantidad_pagada + $cantidad_pagada) <= $pagar){
                        $pagos_con->update([
                            'cantidad_pagada'   => $pagos_con->cantidad_pagada + $cantidad_pagada,
                            'estatus'           => 3,
                        ]);
                    }
                    else{
                        $pago_cantidad_pagada = $pagos_con->cantidad_pagada;

                        $pagos_con->update([
                            'cantidad_pagada'   => $pagar,
                            'estatus'           => 1,
                        ]);

                        $pagos_con = PagosContratas::findOrFail($id+1);

                        $pagos_con->update([
                            'cantidad_pagada'   => ($cantidad_pagada) - ($pagar - $pago_cantidad_pagada),
                            'estatus'           => 3,
                        ]);

                    }


                }

            }
            else{
                $index = 0;
                foreach ($pagos_contratas as $pago)
                {
                    if($residuo < $pagar) break;
    
                    if($index == 0 && $pago->cantidad_pagada > 0){
                        $residuo += $pago->cantidad_pagada;
                    }
                    $pago->update([
                        'cantidad_pagada'   => $pagar,
                        'estatus'           => 1,
                    ]);
    
                    $idAux = $pago->id;
                    $residuo -= $pagar;
    
                    $index++;
                }
                $saldo = $residuo % $pagar;
                if($residuo % $pagar)
                {
                    $pagos_contratas = PagosContratas::findOrFail($idAux + 1);
                    $pagos_contratas->update([
                        'cantidad_pagada'   => $saldo,
                        'estatus'           => ($saldo == $pagar)? 1 :3,
                    ]);

                    
                }
            }

            
        }
        else if($residuo < $pagar)
        {
            $pago = $pagos_contratas[0];

            $pago_cantidad_pagada = $pago->cantidad_pagada;

            if($pago_cantidad_pagada > 0){
                
                if(($pago_cantidad_pagada + $residuo) <= $pagar){

                    $estatus = (($pago_cantidad_pagada + $residuo) == $pagar)? 1: 3;

                    $pago->update([
                        'cantidad_pagada'   => ($pago_cantidad_pagada + $residuo),
                        'estatus'           => $estatus,
                    ]);

                }
                else{

                    $pago->update([
                        'cantidad_pagada'   => $pagar,
                        'estatus'           => 1,
                    ]);

                    $pago = $pagos_contratas[1];

                    $pago->update([
                        'cantidad_pagada'   => ($residuo) - ($pagar - $pago_cantidad_pagada),
                        'estatus'           => 3,
                    ]);

                }

            }
            else{

                $estatus = ($residuo == $pagar)? 1: 3;

                $pago->update([
                    'cantidad_pagada'   => $residuo,
                    'estatus'           => $estatus,
                ]);
            }

        }
        else{
        
            $index = 0;
            foreach ($pagos_contratas as $pago)
            {
                if($residuo < $pagar) break;

                if($index == 0 && $pago->cantidad_pagada > 0){
                    $residuo += $pago->cantidad_pagada;
                }

                $pago->update([
                    'cantidad_pagada'   => $pagar,
                    'estatus'           => 1,
                ]);

                $idAux = $pago->id;
                $residuo -= $pagar;

                $index++;
            }
            $saldo = $residuo % $pagar;
            if($residuo % $pagar)
            {
                $pagos_contratas = PagosContratas::findOrFail($idAux + 1);
                $pagos_contratas->update([
                    'cantidad_pagada'   => $saldo,
                    'estatus'           => ($saldo == $pagar)? 1 :3,
                ]);
                
            }
        }
        $contrata->control_pago_confirmar = $cantidad_pagada;
        $contrata->save();
        return back()->with('message', 'Se agrego el cobro con éxito.')->with('estatus',true);
        
    }

    public function agregarPago($id,Request $request)
    {
        $id_cobrador = User::findOrFail(Auth::user()->id);
        $saldo_cobrador = $request['cantidad_pagada'];
        $pagos_contratas = PagosContratas::findOrFail($id);
        $contrata = Contratas::findOrFail($pagos_contratas->id_contrata);
        request()->validate([
            'cantidad_pagada'   => 'required',
        ]);
        //dd($pagos_contratas);
        try{
            DB::beginTransaction();


            if( $pagos_contratas->estatus == 2 || $pagos_contratas->estatus == 3 )
            {
                $pago_anterior = $pagos_contratas->cantidad_pagada;
                if( $request['cantidad_pagada'] + $pago_anterior > $contrata->pagos_contrata  )
                {
                    $pago = $request['cantidad_pagada'] + $pago_anterior;
                    $residuo = $request['cantidad_pagada']+$pago_anterior - $contrata->pagos_contrata;
                    $pagar = $contrata->pagos_contrata;
                    $contador = $residuo / $contrata->pagos_contrata;
                    $aux = 1;

                    $pagos_contratas->update([
                        'confirmacion'           =>1,
                    ]);

                    ConfirmacionPagos::create([
                        "id_pago_contrata" => $pagos_contratas->id,
                        'id_cobrador' => Auth::user()->id,
                        'id_contrata'       => $contrata->id,
                        'cantidad_pagada'   => $contrata->pagos_contrata,
                        'adeudo'            => 0,
                        'adelanto'          => $residuo,
                        'estatus'           => 1,
                    ]);

                    for( $i=0; $i<intval($contador); $i++)
                    {
                    
                        $pagos_contratas = PagosContratas::findOrFail($id+$aux);

                        ConfirmacionPagos::create([
                            "id_pago_contrata"  => $pagos_contratas->id,
                            'id_cobrador'       => Auth::user()->id,
                            'id_contrata'       => $contrata->id,
                            'cantidad_pagada'   => $contrata->pagos_contrata,
                            'adeudo'            => 0,
                            'adelanto'          => 0,
                            'estatus'           => 1,
                        ]);

                        $aux++;
                    }        
                    if($residuo % $pagar)
                    {
                        $pagos_contratas = PagosContratas::findOrFail($id+$aux);
                        $saldo = $residuo % $pagar;

                        ConfirmacionPagos::create([
                            "id_pago_contrata"  => $pagos_contratas->id,
                            'id_cobrador'       => Auth::user()->id,
                            'id_contrata'       => $contrata->id,
                            'cantidad_pagada'   => $residuo % $pagar,
                            'adeudo'            => $pagar - $saldo,
                            'adelanto'          => 0,
                            'estatus'           => 2,
                        ]);
        
                        //$contrata->adeudo = $pagar - $saldo;
                    }
                }
                else if( $request['cantidad_pagada'] + $pago_anterior == $contrata->pagos_contrata )
                {
                    $pagos_contratas->update([
                        'confirmacion'           => 1,
                    ]); 

                    ConfirmacionPagos::create([
                        "id_pago_contrata"  => $pagos_contratas->id,
                        'id_cobrador'       => Auth::user()->id,
                        'id_contrata'       => $contrata->id,
                        'cantidad_pagada'   => $request['cantidad_pagada'] + $pago_anterior,
                        'adeudo'            => 0,
                        'adelanto'          => 0,
                        'estatus'           => 1,
                    ]);

                    //$contrata->adeudo = 0;
                }
                else if( $request['cantidad_pagada'] + $pago_anterior < $contrata->pagos_contrata )
                {
                    $pagos_contratas->update([
                        'confirmacion'           => 1,
                    ]);

                    ConfirmacionPagos::create([
                        "id_pago_contrata"  => $pagos_contratas->id,
                        'id_cobrador'       => Auth::user()->id,
                        'id_contrata'       => $contrata->id,
                        'cantidad_pagada'   => $request['cantidad_pagada'] + $pago_anterior,
                        'adeudo'            => $contrata->pagos_contrata - $request['cantidad_pagada'] - $pago_anterior,
                        'adelanto'          => 0,
                        'estatus'           => 3,
                    ]);

                    //$contrata->adeudo += $contrata->pagos_contrata - $request['cantidad_pagada'] - $pago_anterior; 
                } 
                else
                {
                    dd("No hay");
                }
            }
            else
            {
                if($contrata->pagos_contrata+$contrata->adeudo == $request['cantidad_pagada'])
                {
                    $pagos_contratas->update([
                        'confirmacion'           => 1,
                    ]);

                    ConfirmacionPagos::create([
                        "id_pago_contrata"  => $pagos_contratas->id,
                        'id_cobrador'       => Auth::user()->id,
                        'id_contrata'       => $contrata->id,
                        'cantidad_pagada'   => $request['cantidad_pagada'],
                        'adeudo'            => 0,
                        'adelanto'          => 0,
                        'estatus'           => 1,
                    ]);

                    //$contrata->adeudo = 0;
                }
                elseif($request['cantidad_pagada'] >= $contrata->pagos_contrata+$contrata->adeudo)
                {
                    if( $contrata->adeudo > 0 )
                    {
                        $pago = $contrata->pagos_contrata+$contrata->adeudo;
                        $pagos_contratas->update([
                            'confirmacion'           => 1,
                        ]);

                        ConfirmacionPagos::create([
                            "id_pago_contrata"  => $pagos_contratas->id,
                            'id_cobrador'       => Auth::user()->id,
                            'id_contrata'       => $contrata->id,
                            'cantidad_pagada'   => $pago,
                            'adeudo'            => 0,
                            'adelanto'          => $request['cantidad_pagada'] - $pago,
                            'estatus'           => 1,
                        ]);

                        $residuo = $request['cantidad_pagada'] - $pago;
                        $pagar = $contrata->pagos_contrata;
                        $contador = $residuo / $pagar;
                        $aux = 1;
                        //$contrata->adeudo = 0;
                        for( $i=0; $i<intval($contador); $i++)
                        {
                            $pagos_contratas = PagosContratas::findOrFail($id+$aux);
                            // $pagos_contratas->update([
                            //     'cantidad_pagada'   => $contrata->pagos_contrata,
                            //     'adeudo'            => 0,
                            //     'adelanto'          => 0,
                            //     'estatus'           => 1,
                            // ]);
                            ConfirmacionPagos::create([
                                "id_pago_contrata"  => $pagos_contratas->id,
                                'id_cobrador'       => Auth::user()->id,
                                'id_contrata'       => $contrata->id,
                                'cantidad_pagada'   => $contrata->pagos_contrata,
                                'adeudo'            => 0,
                                'adelanto'          => 0,
                                'estatus'           => 1,
                            ]);
                            $aux++;
                        }        
                        if($residuo % $pagar)
                        {
                            $pagos_contratas = PagosContratas::findOrFail($id+$aux);
                            $saldo = $residuo % $pagar;
                            // $pagos_contratas->update([
                            //     'cantidad_pagada'   => $residuo % $pagar,
                            //     'adeudo'            => $pagar - $saldo,
                            //     'adelanto'          => 0,
                            //     'estatus'           => 3,
                            // ]);
            
                            ConfirmacionPagos::create([
                                "id_pago_contrata"  => $pagos_contratas->id,
                                'id_cobrador'       => Auth::user()->id,
                                'id_contrata'       => $contrata->id,
                                'cantidad_pagada'   => $residuo % $pagar,
                                'adeudo'            => $pagar - $saldo,
                                'adelanto'          => 0,
                                'estatus'           => 3,
                            ]);
                            //$contrata->adeudo = $pagar - $saldo;
                        }
                    }
                    else
                    {
                        $pagos_contratas->update([
                            'confirmacion'           => 1,
                        ]);

                        ConfirmacionPagos::create([
                            "id_pago_contrata"  => $pagos_contratas->id,
                            'id_cobrador'       => Auth::user()->id,
                            'id_contrata'       => $contrata->id,
                            'cantidad_pagada'   => $contrata->pagos_contrata,
                            'adeudo'            => 0,
                            'adelanto'          => $request['cantidad_pagada'] - $contrata->pagos_contrata,
                            'estatus'           => 1,
                        ]);

                        $residuo = $request['cantidad_pagada'] - $contrata->pagos_contrata;
                        $pagar = $contrata->pagos_contrata;
                        $contador = $residuo / $pagar;
                        $aux = 1;
                        //$contrata->adeudo = 0;
                        for( $i=0; $i<intval($contador); $i++)
                        {
                            $pagos_contratas = PagosContratas::findOrFail($id+$aux);
                            // $pagos_contratas->update([
                            //     'cantidad_pagada'   => $contrata->pagos_contrata,
                            //     'adeudo'            => 0,
                            //     'adelanto'          => 0,
                            //     'estatus'           => 1,
                            // ]);

                            ConfirmacionPagos::create([
                                "id_pago_contrata"  => $pagos_contratas->id,
                                'id_cobrador'       => Auth::user()->id,
                                'id_contrata'       => $contrata->id,
                                'cantidad_pagada'   => $contrata->pagos_contrata,
                                'adeudo'            => 0,
                                'adelanto'          => 0,
                                'estatus'           => 1,
                            ]);
                            $aux++;
                        }        
                        if($residuo % $pagar)
                        {
                            $pagos_contratas = PagosContratas::findOrFail($id+$aux);
                            $saldo = $residuo % $pagar;
                            // $pagos_contratas->update([
                            //     'cantidad_pagada'   => $residuo % $pagar,
                            //     'adeudo'            => $pagar - $saldo,
                            //     'adelanto'          => 0,
                            //     'estatus'           => 2,
                            // ]);
                            ConfirmacionPagos::create([
                                "id_pago_contrata"  => $pagos_contratas->id,
                                'id_cobrador'       => Auth::user()->id,
                                'id_contrata'       => $contrata->id,
                                'cantidad_pagada'   => $residuo % $pagar,
                                'adeudo'            => $pagar - $saldo,
                                'adelanto'          => 0,
                                'estatus'           => 2,
                            ]);
                            //$contrata->adeudo = $pagar - $saldo;
                        }
                    }
                }
                else
                {
                    $adeudo = $contrata->pagos_contrata+$contrata->adeudo - $request['cantidad_pagada'];
                    $pagos_contratas->update([
                        'confirmacion'           => 1,
                    ]);

                    ConfirmacionPagos::create([
                        "id_pago_contrata"  => $pagos_contratas->id,
                        'id_cobrador'       => Auth::user()->id,
                        'id_contrata'       => $contrata->id,
                        'cantidad_pagada'   => $request['cantidad_pagada'],
                        'adeudo'            => $adeudo,
                        'adelanto'          => 0,
                        'estatus'           => 3,
                    ]);

                    //$contrata->adeudo = $adeudo;
                }
            }
                
            
            // $contrata->control_pago = $contrata->control_pago += $saldo_cobrador;
            // if($pagos_contratas->fecha_pago == $contrata->fecha_termino )
            //     $contrata->estatus = 1;

            HistorialCobrosDia::create([
                'id_cobrador' => Auth::user()->id,
                'cantidad' => $request['cantidad_pagada'],
                'id_contrata' => $contrata->id,
                'id_cliente' => $contrata->id_cliente,
                'confirmado' => 0,
                'fecha' => Carbon::now()->format("Y-m-d")
            ]);
            //$contrata->update();
            // $id_cobrador->update([
            //     'saldo' => $id_cobrador->saldo+=$saldo_cobrador,
            // ]);

            DB::commit();
        }
        catch(Exception $e)
        {
            
            DB::rollBack();

            return back()->with('message', 'Hubo un error al agregar el pago.')->with('estatus',false);
        }

        return back()->with('message', 'Se agrego el pago con éxito.')->with('estatus',true);
    }
 
    function confirmarPagos()
    {
        try{
            DB::beginTransaction();

            
            HistorialCobrosDia::where('id_cobrador', Auth::user()->id)->update(["confirmado" => 1]);
            
            PagosContratas::confirmarPagos(Auth::user()->id);
    
            $pagos = ConfirmacionPagos::selectRaw("sum(cantidad_pagada) as total_pagado, sum(adeudo) as total_adeudo, id_contrata")
                                        ->where("id_cobrador",Auth::user()->id)
                                        ->where("pago_atrasado",false)
                                        ->groupBy("id_contrata")
                                        ->get();
            
            foreach($pagos as $pago)
            {
                $contrata = Contratas::findOrFail($pago->id_contrata);
                $contrata->adeudo = $pago->total_adeudo;
                $contrata->control_pago += $pago->total_pagado;
                $contrata->update();
            }

            $pagosAtrasados = ConfirmacionPagos::selectRaw("sum(cantidad_pago_atrasado) as total_pagado, id_contrata")
                                        ->where("id_cobrador",Auth::user()->id)
                                        ->where("pago_atrasado",true)
                                        ->groupBy("id_contrata")
                                        ->get();
            
            foreach($pagosAtrasados as $pago)
            {
                $contrata = Contratas::findOrFail($pago->id_contrata);
                $contrata->control_pago += $pago->total_pagado;
                $contrata->update();
            }

            

            $saldo = ConfirmacionPagos::selectRaw("sum(cantidad_pagada) as total_pagado")
                                        ->where("id_cobrador",Auth::user()->id)
                                        ->where("pago_atrasado",false)
                                        ->get()
                                        ->first();

            $saldoAdeudo = ConfirmacionPagos::selectRaw("sum(cantidad_pago_atrasado) as total_pagado")
                                        ->where("id_cobrador",Auth::user()->id)
                                        ->where("pago_atrasado",true)
                                        ->get()
                                        ->first();

            $cobrador = User::findOrFail(Auth::user()->id);
            $cobrador->saldo += ($saldo->total_pagado + $saldoAdeudo->total_pagado);
            $cobrador->update();

            ConfirmacionPagos::where("id_cobrador",Auth::user()->id)->delete();

            DB::commit();
        }
        catch(Exception $e){

            DB::rollBack();
            return redirect()->route('historialCobranza')->with('message', "Hubo un error al confirmar : ". $e->getMessage())->with('estatus',false);
        }

        return redirect()->route('historialCobranza')->with('message', 'Se confirmaron los pagos con éxito.')->with('estatus',true);
    }
}



public function agregarGasto(Request $request)
{
    $id_cobrador = User::findOrFail(Auth::user()->id);
    $gasto = $request['cantidad'];
    $capital = Capital::find(1);
    if($request['categoria'] == "Contratas")
    {
        Gastos::create([
            'cantidad'    => $request['cantidad'],
            'categoria'   => "Sin categoria",
            'informacion' => $request['informacion'],
            'fecha_gasto' => Carbon::now(),
            'id_user'     => Auth::user()->id,
        ]);
        $capital->gastos += $gasto;
        $capital->save();
    }
    else
    {
        Gastos::create([
            'cantidad'    => $request['cantidad'],
            'categoria'   => "Sin categoria",
            'informacion' => $request['informacion'],
            'fecha_gasto' => Carbon::now(),
            'id_user'     => Auth::user()->id,
        ]);
        $capital->saldo_efectivo -= $gasto;
        $capital->gastos += $gasto;
        $capital->save();
    }
    

    $id_cobrador->update([
        'saldo' => $id_cobrador->saldo-=$gasto,
    ]);

    return redirect()->route('vista.gastos');
}