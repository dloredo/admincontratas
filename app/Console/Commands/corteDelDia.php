<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\PagosContratas;
use App\ConfirmacionPagos;
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
                            ->whereIn("estatus",[0,2])
                            ->get();

                      
            if(sizeof($pagos)>0)
            {
    
                foreach($pagos as $pago)
                {
                    $contrata = Contratas::findOrFail($pago->id_contrata);
                    $pagoAnterior = PagosContratas::where("id","<",$pago->id)
                                    ->where("id_contrata",$pago->id_contrata)
                                    ->orderBy("id","asc")
                                    ->get()
                                    ->first();
    
                    if($pagoAnterior){

                        if($pago->estatus == 0)
                        {
                            $contrata->adeudo += $contrata->pagos_contrata;
                            $pago->adeudo = ($pagoAnterior->estatus == 3)?  $contrata->pagos_contrata + $pagoAnterior->adeudo : $contrata->pagos_contrata;
                        }
                        else if($pago->estatus == 2)
                        {
                            $adeudo = $contrata->pagos_contrata - $pago->cantidad_pagada;
                            $contrata->adeudo += $adeudo;

                            if($pagoAnterior->estatus == 3)
                                $adeudo += $pagoAnterior->adeudo;

                            $pago->adeudo = $adeudo;
                        }
        
                        $pago->estatus = 3;
        
                        $pago->update();
                        $contrata->update();
                    }
                    
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

            HistorialCobrosDia::where('id_cobrador', $idCobrador)->update(["confirmado" => 1]);
            
            PagosContratas::confirmarPagos($idCobrador);
    
            $pagos = ConfirmacionPagos::selectRaw("sum(cantidad_pagada) as total_pagado, sum(adeudo) as total_adeudo, id_contrata")
                                        ->where("id_cobrador",$idCobrador)
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
                                        ->where("id_cobrador",$idCobrador)
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
                                        ->where("id_cobrador",$idCobrador)
                                        ->where("pago_atrasado",false)
                                        ->get()
                                        ->first();

            $saldoAdeudo = ConfirmacionPagos::selectRaw("sum(cantidad_pago_atrasado) as total_pagado")
                                        ->where("id_cobrador",$idCobrador)
                                        ->where("pago_atrasado",true)
                                        ->get()
                                        ->first();

            $cobrador = User::findOrFail($idCobrador);
            $cobrador->saldo += ($saldo->total_pagado + $saldoAdeudo->total_pagado);
            $cobrador->update();

            ConfirmacionPagos::where("id_cobrador",$idCobrador)->delete();

            DB::commit();
        }
        catch(Exception $e){
            DB::rollBack();
            throw new Exception($e->getMessage());
        }

        //return redirect()->route('historialCobranza')->with('message', 'Se confirmaron los pagos con éxito.')->with('estatus',true);
    }
}
