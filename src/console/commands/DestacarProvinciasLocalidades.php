<?php

namespace Cardumen\ArgentinaProvinciasLocalidades\Commands;

use Illuminate\Console\Command;
use Cardumen\ArgentinaProvinciasLocalidades\Models\Localidad;
use Cardumen\ArgentinaProvinciasLocalidades\Models\Provincia;
use DB;


class DestacarProvinciasLocalidades extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'provincias-localidades:destacar {--quitar}';

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
        $que_destaca = $this->choice("¿Sobre que va a trabajar?",$opciones);
        if ($que_destaca == 'L'){
            $modelo = new Localidad();
            $nombre = 'localidad';
            $tabla = "localidades";
        } else {
            $modelo =  new Provincia();
            $nombre = 'provincia';
            $tabla = "provincias";
        } 

        $this->ids = [];
        $debe_quitar = ($this->option("quitar"));
        if(!empty($debe_quitar)){
            $destacados = $modelo->where('fav',1)->get();
            if($destacados->count() == 0){
                $this->info('No hay ningun objeto destacado para quitar');
            } else {
                $quitar_destacados = [];
                foreach($destacados as $d){
                    $quitar_destacados[$d->id] = $d->id."-".$d->$nombre;
                }
                $pregunta = "Selecciones las ".$opciones[$que_destaca]." que desea quitar";
                $this->seleccionarQuitar($tabla,$pregunta,$quitar_destacados);
            }
        } else {
            $destacados = $modelo->where('fav',0)->get();
            $agregar_destacados = [];
            foreach($destacados as $d){
                $agregar_destacados[$d->id] = $d->id."-".$d->$nombre;
            }
            $pregunta = "Selecciones las ".$opciones[$que_destaca]." que desea agregar";
            $this->seleccionarAgregar($tabla,$pregunta,$nombre,$agregar_destacados);
        }

    }

    private function seleccionarQuitar($tabla,$pregunta,$quitar_destacados){
        $elId = $this->choice($pregunta,$quitar_destacados);
        $this->ids[] = $elId;
        $mas = $this->confirm('Desea seleccionar alguna más?');
        if($mas){
            unset($quitar_destacados[$elId]);
            $this->seleccionarQuitar($tabla,$pregunta,$quitar_destacados);
        } else {
            foreach($this->ids as $id){
                $id = explode("-",$id);
                DB::statement("update  ".$tabla."  set fav = 0 where id = ?",[$id[0]]);
            }
            $this->info('Se han quitado los destacados');
            return true;
        }
    }

    private function seleccionarAgregar($tabla,$pregunta, $nombre, $agregar_destacados){
        if($nombre == 'provincia'){
            $filtered = $agregar_destacados;
        } else {
            $texto = $this->ask('Ingrese parte del nombre de la '.$nombre.' a agregar');
            $filtered = array_filter($agregar_destacados,function ($value, $key) use ($texto) {
                $busca = strtolower($texto);
                return strpos(strtolower($value), $busca) !== false;
            }, ARRAY_FILTER_USE_BOTH);
            if(count("filtered") == 0){
                $this->info('No hay resultados para: '.$texto);
                $this->seleccionarAgregar($tabla,$pregunta, $nombre,$agregar_destacados);
            }

        }
        $elId = $this->choice($pregunta,$filtered);
        $this->ids[] = $elId;
        $mas = $this->confirm('Desea seleccionar alguna más?');
        if($mas){
            $this->seleccionarAgregar($tabla,$pregunta, $nombre,$agregar_destacados);
        } else {
            foreach($this->ids as $id){
                $id = explode("-",$id);
                DB::statement("update  ".$tabla."  set fav = 1 where id = ?",[$id[0]]);
            }
            
            $this->info('Se han agregado los destacados');
            return true;
        }
    }
}
