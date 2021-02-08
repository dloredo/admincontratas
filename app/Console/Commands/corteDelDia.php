<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\PagosContratas;
use App\ConfirmacionPagos;
use App\ConfirmacionPagoAnualidad;
use App\HistorialCobrosDia;
use App\Contratas;
use App\User;
use Carbon\Carbon;
use Exception;
class corteDelDia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'corte:dia';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para realizar el corte de las confirmaciones de pago y los pagos del dia';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try{
            DB::beginTransaction();
            $idCobradores = User::select("id")
                            ->join("confirmacion_pagos","confirmacion_pagos.id_cobrador","usuarios.id")
                            ->distinct()
                            ->get();

            
            if(sizeof($idCobradores) > 0){

                foreach($idCobradores as $idCobrador)
                {
                    $this->confirmarPagos($idCobrador->id);
                }
            }

            $now = Carbon::now();
            $pagos = PagosContratas::where("fecha_pago",$now->format("Y-m-d"))
                            ->whereIn("estatus",[0,3])
                            ->get();

                      
            if(sizeof($pagos)>0)
            {
    
                foreach($pagos as $pago)
                {
                    $contrata = Contratas::findOrFail($pago->id_contrata);
                    // $pagoAnterior = PagosContratas::where("id","<",$pago->id)
                    //                 ->where("id_contrata",$pago->id_contrata)
                    //                 ->orderBy("id","asc")
                    //                 ->get()
                    //                 ->first();
    
                    

                        if($pago->estatus == 0)
                        {
                            $contrata->adeudo += ($pago->anualidad)? $contrata->pago_anualidad :$contrata->pagos_contrata;
                        }
                        else if($pago->estatus == 3)
                        {
                            $adeudo = ($pago->anualidad)? $contrata->pago_anualidad - $pago->cantidad_pagada :$contrata->pagos_contrata - $pago->cantidad_pagada;
                            $contrata->adeudo += $adeudo;
                        }
        
                        $pago->estatus = 3;
        
                        $pago->update();
                        $contrata->update();
                                        
                }
            }

            DB::commit();
            echo  "Corte realizado con éxito";
        }
        catch(Exception $e){
            DB::rollBack();
            echo  $e->getMessage();
        }

        
    }

    function confirmarPagos($idCobrador)
    {
        try{
            DB::beginTransaction();

            $cobros = HistorialCobrosDia::where('id_cobrador', $idCobrador)
                                        ->where("confirmado",0)
                                        ->get();

            
            
            PagosContratas::confirmarPagos($idCobrador);
    
            
            foreach($cobros as $cobro)
            {
                $contrata = Contratas::findOrFail($cobro->id_contrata);

                if($contrata->adeudo > 0)
                {
                    if($contrata->adeudo <= $cobro->cantidad){
                        $contrata->adeudo = 0;
                    }
                    else{
                        $contrata->adeudo -= $cobro->cantidad;
                    }
                }

                $contrata->control_pago += $cobro->cantidad;
                
                if($contrata->control_pago == $contrata->cantidad_pagar )
                    $contrata->estatus = 1;
                    

               

                $contrata->update();
            }


            $saldo = HistorialCobrosDia::selectRaw("sum(cantidad) as total_pagado")
                                        ->where("id_cobrador",$idCobrador)
                                        ->get()
                                        ->first();

            $cobrador = User::findOrFail($idCobrador);
            $cobrador->saldo += $saldo->total_pagado;
            $cobrador->update();

            ConfirmacionPagos::where("id_cobrador",$idCobrador)->delete();
            HistorialCobrosDia::where('id_cobrador', $idCobrador)->update(["confirmado" => 1]);

            DB::commit();
        }
        catch(Exception $e){
            DB::rollBack();
            throw new Exception($e->getMessage());
        }

        //return redirect()->route('historialCobranza')->with('message', 'Se confirmaron los pagos con éxito.')->with('estatus',true);
    }
}
