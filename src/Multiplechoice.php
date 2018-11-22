<?php

namespace Multiplechoice;

use Twig_Environment;
use Twig_Loader_Filesystem;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Parser;
require_once __DIR__.'/../vendor/autoload.php';

class Multiplechoice{
    protected $preguntas = [];
    protected $cant;
    protected $descipciones = [];
    protected $ocultartodasAnteriores = [];
    protected $ocultarNingunatodasAnteriores = [];
    protected $respuesta_incorrectas = [];
    protected $respuestas_correcta = [];
    protected $preguntasExamen = [];
    protected $cantTemas;
    protected $abc = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N'];

    public function __construct($cant,$test) {   
        $this->cant = $cant;
        $this->preguntas = Yaml::parseFile('/ejemplo/preguntas.yml');
        $this->cantTemas = $test;
        foreach($this->preguntas as $pregunta){
            if($contador > $this->cant ){ 
                $descipciones[$contador] = $pregunta[descripcion];
                $this->respuesta_incorrectas[$contador] = $pregunta[respuestas_incorrectas];
                $this->respuestas_correcta[$contador] = $pregunta[respuestas_correctas];
                if (array_key_exists('ocultar_opcion_todas_las_anteriores',$preguntas)){
                    $this->ocultartodasAnteriores[$contador] = true; 
                }
                else{
                    $this->ocultartodasAnteriores[$contador] = false;
                }
                if(array_key_exists('ocultas_opcion_ninguna_de_las_anteriores',$pregunta)){
                    $this->ocultarNingunatodasAnteriores[$contador] = true;
                }
                else{
                    $this->ocultarNingunatodasAnteriores[$contador] = false;
                }
                $contador++;
            }
        }
    }

    public function getCorrectas() {
		$letras = [];
		$i = 0;
		foreach ($this->respuestas_correcta as $correctas ) {
            $letras[$i] = $this->abc[array_search($correctas[$i], $this->preguntasExamen[])];
            $i++;
		}
		return $letras;
	}

    
    public function opciones($numero){
         
            if ($this->respuesta_incorrectas[$numero] = []){
                $this->respuesta_incorrectas[$numero] = $this->respuesta_correctas[$numero];
                $this->respuesta_correctas[$numero] = [];
                if(!($this->ocultarNingunatodasAnteriores[$numero])){
                    array_push($this->respuesta_incorrectas[$numero],'Ninguna de las anteriores');            
                }
                if(!($this->ocultartodasAnteriores[$numero])){
                    array_push($this->respuesta_correctas[$numero],'Todas de las anteriores');            
                }
                shuffle($this->respuestas_correcta[$numero]);
                shuffle($this->respuesta_incorrectas[$numero]);
                $this->preguntasExamen[$numero] = array_merge($this->respuestas_correcta,$this->respuesta_incorrectas);
                
                return $this->preguntasExamen[$numero];
            }
            if ($this->respuesta_correctas[$numero] = []){
                
                if(!($this->ocultarNingunatodasAnteriores[$numero])){
                    array_push($this->respuesta_correctas[$numero],'Ninguna de las anteriores');            
                }
                if(!($this->ocultartodasAnteriores[$numero])){
                    array_push($this->respuesta_incorrectas[$numero],'Todas de las anteriores');            
                }

                shuffle($this->respuestas_correcta[$numero]);
                shuffle($this->respuesta_incorrectas[$numero]);
                $this->preguntasExamen[$numero] = array_merge($this->respuestas_correcta,$this->respuesta_incorrectas);

                return $this->preguntasExamen[$numero];
            }
    }
    


}