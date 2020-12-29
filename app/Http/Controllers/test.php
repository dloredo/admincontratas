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