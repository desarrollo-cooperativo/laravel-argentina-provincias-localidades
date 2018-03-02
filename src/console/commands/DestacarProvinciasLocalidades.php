<?php

namespace Cardumen\ArgentinaProvinciasLocalidades\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Localidad;

use Illuminate\Database\Eloquent\Provincia;
use DB;


class DestacarProvinciasLocalidades extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'provincias-localidades:destacar --quitar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Destaca provincias o localidades';
    protected $ids = [];
 
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
        $opciones = ["P" => "provincias","L"=>"localidades"];
        $que_destaca = $this->ask("¿Sobre que va a trabajar?",$opciones);
        if ($que_destaca == 'L'){
            $modelo = new Localidad();
            $nombre = 'localidad';
        } else {
            $modelo =  new Provincia();
            $nombre = 'provincia';
        } 

        $ids = [];

        if($this->options("quitar")){
            $destacados = $modelo->where('fav',1)->get();
            if($destacados->count() == 0){
                $this->info('No hay ningun objeto destacado para quitar');
            } else {
                $quitar_destacados = [];
                foreach($destacados as $d){
                    $quitar_destacados[$d->id] = $d->$nombre;
                }
                $pregunta = "Selecciones las ".$opciones[$que_destaca]." que desea quitar";
                $this->seleccionarQuitar($pregunta,$quitar_destacados);
            }
        } else {
            $destacados = $modelo->where('fav',0)->get();
            if($destacados->count() == 0){
                $this->info('No hay ningun objeto destacado para quitar');
            } else {
                $agregar_destacados = [];
                foreach($destacados as $d){
                    $agregar_destacados[$d->id] = $d->$nombre;
                }
                $this->seleccionarAgregar($pregunta,$agregar_destacados);
            }
        }

    }

    private function seleccionarQuitar($pregunta,$quitar_destacados){
        $elId = $this->ask($pregunta,$quitar_destacados);
        $this->ids[] = $elId;
        $mas = $this->ask('Desea seleccionar alguna más?');
        if($mas){
            unset($quitar_destacados[$elId]);
            $this->seleccionarQuitar($pregunta,$quitar_destacados);
        } else {
            DB::statement("update ".$opciones[$que_destaca]." set fav = 0 where id in (?)",implode(',',$this->ids));
            $this->info('Se han quitado los destacados');
            return true;
        }
    }

    private function seleccionarAgregar($nombre, $agregar_destacados){
        $texto = $this->ask('Ingrese parte del nombre de la '.$nombre.'a agregar');
        $filtered = $agregar_destacados->filter(function ($value, $key) use ($texto) {
            $busca = strtolower($texto);
            return strpos(strtolower($value), $busca) !== 0;
        });
        $elId = $this->ask($pregunta,$filtered->all());
        $this->ids[] = $elId;
        $mas = $this->ask('Desea seleccionar alguna más?');
        if($mas){
            $this->seleccionarAgregar($nombre,$agregar_destacados);
        } else {
            DB::statement("update ".$opciones[$que_destaca]." set fav = 1 where id in (?)",implode(',',$this->ids));
            $this->info('Se han agregado los destacados');
            return true;
        }
    }
}
