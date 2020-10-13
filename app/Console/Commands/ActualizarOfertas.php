<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Oferta;
use App\Models\EstadoOferta;

use Carbon\Carbon;

class ActualizarOfertas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ofertas:actualizar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza el estado de todas las ofertas';

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
        // *********************************** IMPORTANTE *******************************************
        // AL MOMENTO DE FINALIZAR UNA OFERTA, SI EL GRADUADO ES SELECCIONADO Y NO HA ACEPTADO, PONER EL ESTADO DEL ESTUDIANTE "NO ACEPTÓ", Y SI EL
        // GRADUADO ACEPTÓ PERO LA EMPRESA NO LO ELIGIO, PONER EL ESTADO DE LA EMPRESA COMO "NO SELECCIONADO"
        
        $ofertas = Oferta::all();
        $act = 0;
        $hora = Carbon::now()->toDayDateTimeString();
        foreach ($ofertas as $key => $oferta) {
            $hoy = new Carbon();
            $fechaCierre = new Carbon($oferta->fechaCierre);
            $fechaCierre->day++;
            if(($oferta->getestado->nombre == 'Publicada' || $oferta->getestado->nombre == 'Por aprobar') && $hoy >= $fechaCierre) {
                $oferta->estado = EstadoOferta::where('nombre','Finalizada')->first()->id;
                $oferta->save();
                $act++;
            }
        }

        $this->info('actualizadas '.$act.' ofertas en '.$hora);
    }
}
