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
        $idCobradores = User::select("id")
                        ->join("confirmacion_pagos","confirmacion_pagos.id_cobrador","users.id")
                        ->get()
                        ->disctinct();

        foreach($idCobradores as $idCobrador)
        {
            $this->confirmarPagos($idCobrador);
        }

        $paagos = PagosContratas::where("fecha_pago",Carbon::now()->format("Y-m-d"))
                        ->whereRaw("(pagos_contratas.estatus = 0 or pagos_contratas.estatus = 2 )")
                        ->get();

        foreach($pagos as $pago)
        {
            
        }

    }

    function confirmarPagos($idCobrador)
    {
        try{
            DB::beginTransaction();

            HistorialCobrosDia::where('id_cobrador', $idCobrador)->update(["confirmado" => 1]);
            
            PagosContratas::confirmarPagos($idCobrador);
    
            $pagos = ConfirmacionPagos::selectRaw("sum(cantidad_pagada) as toal_pagado, sum(adeudo) as total_adeudo, id_contrata")
                                        ->where("id_cobrador",$idCobrador)
                                        ->groupBy("id_contrata")
                                        ->get();
            
            foreach($pagos as $pago)
            {
                $contrata = Contratas::findOrFail($pago->id_contrata);
                $contrata->adeudo = $pago->total_adeudo;
                $contrata->control_pago += $pago->total_pagado;
                $contrata->update();
            }

            $saldo = ConfirmacionPagos::selectRaw("sum(cantidad_pagada) as toal_pagado")
                                        ->where("id_cobrador",$idCobrador)
                                        ->get()
                                        ->first();

            $cobrador = User::findOrFail($idCobrador);
            $cobrador->saldo += $saldo->toal_pagado;


            ConfirmacionPagos::where("id_cobrador",$idCobrador)->delete();

            DB::commit();
        }
        catch(Exception $e){
            DB::rollBack();
        }

        //return redirect()->route('historialCobranza')->with('message', 'Se confirmaron los pagos con éxito.')->with('estatus',true);
    }
}
