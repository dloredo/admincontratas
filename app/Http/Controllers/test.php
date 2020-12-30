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
 
}