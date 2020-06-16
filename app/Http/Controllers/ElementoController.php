<?php

namespace App\Http\Controllers;

use App\Proyectozona;
use App\Proyectotarea;
use App\Proyectopaquete;
use App\Elemento;
use App\TipoGrupo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ElementoController extends Controller {

    use \App\Traits\ApiResponser;

    // Illuminate\Support\Facades\DB;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        
    }

    //validar tarea (para poder registrar el partlist elemento)
    //REGISTRA EL ELEMENTOS PARTLIST

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/partlist_elemento",
     *     tags={"Gestion Elemento"},
     *     summary="Registrar  Elemento del partlist",
     *     @OA\Parameter(
     *         description="Ingrese el id del proyecto",
     *         in="path",
     *         name="intIdProy",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *  @OA\Parameter(
     *         description="Ingrese el tipo producto",
     *         in="path",
     *         name="intIdTipoProducto",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *  @OA\Parameter(
     *         description="Ingrese el idProyecto zona",
     *         in="path",
     *         name="intIdProyZona",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="Ingrese el idProyecto tarea",
     *         in="path",
     *         name="intIdProyTarea",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
      @OA\Parameter(
     *         description="Ingrese el idProyecto Paquete",
     *         in="path",
     *         name="intIdProyPaquete",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="Ingrese el  id TipoEstructurado",
     *         in="path",
     *         name="intIdTipoEstructurado",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="Ingrese el codigo de elemento",
     *         in="path",
     *         name="varCodiElemento",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     
      @OA\Parameter(
     *         description="Ingrese el codigo de intRevision",
     *         in="path",
     *         name="intRevision",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),

     *        @OA\Parameter(
     *         description="Ingrese la Longitud",
     *         in="path",
     *         name="deciLong",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="Ingrese el codigo de varPerfil",
     *         in="path",
     *         name="varPerfil",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="Ingrese la Descripcion",
     *         in="path",
     *         name="varDescripcion",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="Ingrese el Peso Neto",
     *         in="path",
     *         name="deciPesoNeto",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ), 
      @OA\Parameter(
     *         description="Ingrese el Peso Bruto",
     *         in="path",
     *         name="deciPesoBruto",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ), 

      @OA\Parameter(
     *         description="Ingrese el Peso contratista",
     *         in="path",
     *         name="deciPesoContr",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ), 
      @OA\Parameter(
     *         description="Ingrese el  Area",
     *         in="path",
     *         name="deciArea",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ), 
      @OA\Parameter(
     *         description="Ingrese el Ancho",
     *         in="path",
     *         name="deciAncho",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ), 
      @OA\Parameter(
     *         description="Ingrese el Alto",
     *         in="path",
     *         name="deciAlto",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ), 
      @OA\Parameter(
     *         description="Ingrese el Modelo",
     *         in="path",
     *         name="varModelo",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ), 
      @OA\Parameter(
     *         description="Ingrese el intIdTipoEstru",
     *         in="path",
     *         name="varModelo",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ), 
      @OA\Parameter(
     *         description="Ingrese el Codigo Valor",
     *         in="path",
     *         name="varModelo",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="Ingrese el acti_usua",
     *         in="path",
     *         name="acti_usua",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),  

     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="varDescripcion",
     *                     type="string"
     *                 ) ,
     *                
     *                 example={"intIdProy":"1200","intIdTipoProducto":"120","intIdProyZona":"1450","intIdProyPaquete":"1250","intIdTipoEstructurado":"58",
     *                        "varCodiElemento":"013-AP-P121","intRevision":"25","deciLong":"600.000","varPerfil":"PL3X600","varDescripcion":"PLANCHA_PISO",
     *                         "deciPesoNeto":"16.900","deciPesoBruto":"16.900","deciPesoContr":"16.900","deciArea":"1.450","deciAncho":"3.000",
     *                        "deciAlto":"1196.000","varModelo":"RAMPA","intIdTipoEstru":"1","varCodVal":"PL_E","acti_usua":"usuario"
     *                        }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Se registrar el elemento satifactorio."
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description="no se registrar elemento por algunas validaciones que se tiene que realizar ante del guardado."
     *     ),
     *     
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function partlist_elemento(Request $request) {

        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'intIdProyZona' => 'required|max:255',
            'intIdProyTarea' => 'required|max:255',
            'intIdProyPaquete' => 'required|max:255',
            'intIdTipoEstructurado' => 'required|max:255',
            'varCodiElemento' => 'required|max:255',
            // 'intSerie'=>'required|max:255',
            'intRevision' => 'required|max:255',
            'deciLong' => 'required|max:255',
            'varPerfil' => 'required|max:255',
            'varDescripcion' => 'required|max:255',
            'deciPesoNeto' => 'required|max:255',
            'deciPesoBruto' => 'required|max:255',
            'deciPesoContr' => 'required|max:255',
            'deciArea' => 'required|max:255',
            'deciAncho' => 'required|max:255',
            'deciAlto' => 'required|max:255',
            'varModelo' => 'required|max:255',
            'intIdTipoEstru' => 'required|max:255',
            'varCodVal' => 'required|max:255',
            'acti_usua' => 'required|max:255',
            //cantidad ha ingresar
            'cantidad' => 'required|max:255'
        ];
        $this->validate($request, $regla);

        //VARIABLES PARA USAR EL VALIDAR_TAREA




        date_default_timezone_set('America/Lima'); // CDT
        //cantidad que el usuario me van mandar(cuantas veces se va repetir ?)
        $cantidad = ($request->input('cantidad'));

        //dd($cantidad);
        // if(!isset($cond_parlis)){
        $contador = "";


        if ($request->input('varValo1') == null || $request->input('varValo1') == "") {
            $varValo1 = '';
        } else {
            $varValo1 = $request->input('varValo1');
        }
        if ($request->input('varValo2') == null || $request->input('varValo2') == "") {
            $varValo2 = '';
        } else {
            $varValo2 = $request->input('varValo2');
        }
        if ($request->input('varValo3') == null || $request->input('varValo3') == "") {
            $varValo3 = '';
        } else {
            $varValo3 = $request->input('varValo3');
        }
        if ($request->input('varValo4') == null || $request->input('varValo4') == "") {
            $varValo4 = '';
        } else {
            $varValo4 = $request->input('varValo4');
        }
        if ($request->input('varValo5') == null || $request->input('varValo5') == "") {
            $varValo5 = '';
        } else {
            $varValo5 = $request->input('varValo5');
        }



        for ($i = 0; $i < $cantidad; $i++) {
            $serie = 1;

            $cond_parlis = Elemento::where('intIdProy', $request->input('intIdProy'))
                    ->where('intIdTipoProducto', $request->input('intIdTipoProducto'))
                    ->where('varCodiElemento', $request->input('varCodiElemento'))
                    ->max('intSerie');

            if (!isset($cond_parlis)) {
                $cond_parlis = 0;
                $serie = (int) $serie + (int) $cond_parlis;
                $contador = (string) $serie;
            } else {

                $serie = (int) $serie + (int) $cond_parlis;
                $contador = (string) $serie;
            }
            
            $crear_element = Elemento::create([
                        'intIdProy' => $request->input('intIdProy'),
                        'intIdTipoProducto' => $request->input('intIdTipoProducto'),
                        'intIdProyZona' => $request->input('intIdProyZona'),
                        'intIdProyTarea' => $request->input('intIdProyTarea'),
                        'intIdProyPaquete' => $request->input('intIdProyPaquete'),
                        'intIdTipoEstructurado' => $request->input('intIdTipoEstructurado'),
                        'intIdTipoEstru' => $request->input('intIdTipoEstru'),
                        'varCodiElemento' => $request->input('varCodiElemento'),
                        'intSerie' => $contador,
                        'intRevision' => $request->input('intRevision'),
                        'deciLong' => $request->input('deciLong'),
                        'varPerfil' => $request->input('varPerfil'),
                        'varDescripcion' => $request->input('varDescripcion'),
                        'deciPesoNeto' => $request->input('deciPesoNeto'),
                        'deciPesoBruto' => $request->input('deciPesoBruto'),
                        'deciPesoContr' => $request->input('deciPesoContr'),
                        'deciArea' => $request->input('deciArea'),
                        'intIdEtapa' => 0,
                        'intIdEtapaAnte' => 0,
                        'intIdEtapaSiguiente' => 0,
                        'deciPrec' => 0,
                        'varBulto' => '',
                        'nume_guia' => '',
                        'intIdRuta' => 0,
                        'deciAncho' => $request->input('deciAncho'),
                        'deciAlto' => $request->input('deciAlto'),
                        'varModelo' => $request->input('varModelo'),
                        'varCodVal' => $request->input('varCodVal'),
                        'varValo1' => $varValo1,
                        'varValo2' => $varValo2,
                        'varValo3' => $varValo3,
                        'varValo4' => $varValo4,
                        'varValo5' => $varValo5,
                        'acti_usua' => $request->input('acti_usua'),
                        'acti_hora' => $current_date = date('Y/m/d H:i:s'),
                        'intIdEsta' => 4,
                        'varDocAnt' => '',
                        'numDocTratSup' => '',
                        'intCantAcce' =>$request->input('intCantAcce'),
                        'deciAreaPintura' =>$request->input('deciAreaPintura')
            ]);
        }
        $mensaje = [
            'mensaje' => 'Guardado con exito.'
        ];
        return $this->successResponse($mensaje);
    }

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/vali_marcar",
     *     tags={"Gestion Elemento"},
     *     summary="validamos si la marca ya existe en el paquete",
     *     @OA\Parameter(
     *         description="Ingrese el id proyecto",
     *         in="path",
     *         name="intIdProy",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),

     *     @OA\Parameter(
     *         description="Ingrese el codigo elemento",
     *         in="path",
     *         name="varCodiElemento",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingrese el id Paquete",
     *         in="path",
     *         name="intIdProyPaquete",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),


     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="varNumeDni",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProy": "125","varCodiElemento":"013-AP-P1","intIdProyPaquete":"75"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Que si puede registrar la marca"
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description="El mismo marca no puede ser registrar, por la cual ya exite en ese paquete"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    // valida si la marca existe , mandara un mensaje  "no se puede agregar la misma marca." (PARA MODIFICAR )
    public function vali_marcar(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            //  'intIdProyPaquete' => 'required|max:255',
            'varCodiElemento' => 'required|max:255',
            'intIdProyPaquete' => 'required|max:255'
        ];
        $this->validate($request, $regla);



        $vali_marca_cantidad = Elemento::where('intIdProy', $request->input('intIdProy'))
                ->where('intIdProyPaquete', $request->input('intIdProyPaquete'))
                ->where('varCodiElemento', $request->input('varCodiElemento'))
                ->max('intSerie');

        $vali_id_element = Elemento::where('intIdProy', $request->input('intIdProy'))
                        ->where('intIdProyPaquete', '=', $request->input('intIdProyPaquete'))
                        ->where('varCodiElemento', $request->input('varCodiElemento'))->first(['intIdEleme', 'varCodiElemento']);

        //     dd($vali_id_element);
        if (isset($vali_id_element)) {
            $mensaje = [
                'mensaje' => "ERROR NO SE PUEDE AGREGAR LA MISMA MARCA EN EL PAQUETE " . $request->input('varCodigoPaquete')
                . ".POR FAVOR DIGIRSE A LA OPCION MODIFICAR"
            ];
            return $this->successResponse($mensaje);
        } else {

            $mensaje = [
                'mensaje' => "Conforme."
            ];
            return $this->successResponse($mensaje);
        }
    }

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/vali_marcar_regi",
     *     tags={"Gestion Elemento"},
     *     summary="valida si la marca existe,validar al momento de registrar",
     *     @OA\Parameter(
     *         description="Ingresar el id proyecto",
     *         in="path",
     *         name="intIdProy",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *     @OA\Parameter(
     *         description="Ingresar el Id TipoProducto",
     *         in="path",
     *         name="intIdTipoProducto",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingresar el codigo Elemente",
     *         in="path",
     *         name="varCodiElemento",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *     @OA\Parameter(
     *         description="Ingresar el id Proyecto Paquete",
     *         in="path",
     *         name="intIdProyPaquete",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *        
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="varNumeDni",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProy": "126","intIdTipoProducto":"2548","varCodiElemento":"013-AP-P1","intIdProyPaquete":"49"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="ELEMENTO NO EXISTE. POR FAVOR DE REGISTRAR."
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description="Error."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    // valida si la marca existe , VALIDAR MARCAR AL MOMENTO DE REGISTRAR 
    // VALIDAR MARCA AL MOMENTO DE ACTUALIZAR EL COMPONENTE
    public function vali_marcar_regi(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'varCodiElemento' => 'required|max:255',
            'intIdProyPaquete' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $vali_id_element = Elemento::where('intIdProy', $request->input('intIdProy'))
                ->where('intIdTipoProducto', $request->input('intIdTipoProducto'))
                ->where('intIdProyPaquete', $request->input('intIdProyPaquete'))
                ->where('varCodiElemento', $request->input('varCodiElemento'))
                ->first(['intIdEleme', 'varCodiElemento']);


        if (!isset($vali_id_element)) {
            $mensaje = [
                'mensaje' => "ELEMENTO NO EXISTE. POR FAVOR DE REGISTRAR."
            ];
            return $this->successResponse($mensaje);
        } else {

            $mensaje = [
                'mensaje' => "Error.",
                'id' => $vali_id_element['intIdEleme']
            ];
            return $this->successResponse($mensaje);
        }
    }

    //FIN DE VALIDAR MARCAR AL MOMENTO DE REGISTRAR 
    //actualizar Elemento mediante los parametros  marca, idProy, idTipoProy, Cantidad 

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/actu_elem",
     *     tags={"Gestion Elemento"},
     *     summary="actualizar los elemento que estan en el partlist",
     *     @OA\Parameter(
     *         description="Ingresar el id del proyecto",
     *         in="path",
     *         name="intIdProy",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),

      @OA\Parameter(
     *         description="Ingresar el id tipo producto",
     *         in="path",
     *         name="intIdTipoProducto",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ), 

      @OA\Parameter(
     *         description="Ingresar el codigo de Elemento",
     *         in="path",
     *         name="varCodiElemento",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ), 


      @OA\Parameter(
     *         description="Ingresar la cantidad",
     *         in="path",
     *         name="cantidad",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),  


      @OA\Parameter(
     *         description="Ingresar Id de la Zona",
     *         in="path",
     *         name="intIdProyZona",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),  

      @OA\Parameter(
     *         description="Ingresar Id de la tarea",
     *         in="path",
     *         name="intIdProyTarea",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),  

      @OA\Parameter(
     *         description="Ingresar Id Paquete",
     *         in="path",
     *         name="intIdProyPaquete",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),  

      @OA\Parameter(
     *         description="Ingresar Id Tipo Estructurado",
     *         in="path",
     *         name="intIdTipoEstructurado",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),  

      @OA\Parameter(
     *         description="Ingresar Revision",
     *         in="path",
     *         name="intRevision",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),  
      @OA\Parameter(
     *         description="Ingresar Longitud",
     *         in="path",
     *         name="deciLong",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="Ingresar Perfil",
     *         in="path",
     *         name="varPerfil",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),  

      @OA\Parameter(
     *         description="Ingresar Descripcion",
     *         in="path",
     *         name="varDescripcion",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),  

      @OA\Parameter(
     *         description="Ingresar Peso Neto",
     *         in="path",
     *         name="varDescripcion",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),

      @OA\Parameter(
     *         description="Ingresar Peso Bruto",
     *         in="path",
     *         name="deciPesoBruto",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),   

      @OA\Parameter(
     *         description="Ingresar Peso contratista",
     *         in="path",
     *         name="deciPesoContr",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),   

      @OA\Parameter(
     *         description="Ingresar Area",
     *         in="path",
     *         name="deciArea",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),   

      @OA\Parameter(
     *         description="Ingresar Ancho",
     *         in="path",
     *         name="deciAncho",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ), 

      @OA\Parameter(
     *         description="Ingresar Alto",
     *         in="path",
     *         name="deciAlto",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ), 

      @OA\Parameter(
     *         description="Ingresar Modelo",
     *         in="path",
     *         name="varModelo",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),         

      @OA\Parameter(
     *         description="Ingresar intIdTipoEstru",
     *         in="path",
     *         name="intIdTipoEstru",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),  
      @OA\Parameter(
     *         description="Ingresar Codigo Valorizacion",
     *         in="path",
     *         name="varCodVal",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),    

      @OA\Parameter(
     *         description="Ingresar  usua_modi",
     *         in="path",
     *         name="usua_modi",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),    



     *        
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="varNumeDni",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProy": "1200","intIdTipoProducto":"252","varCodiElemento":"C1_14","cantidad":"25"
     *                      ,"intIdProyZona":"120","intIdProyTarea":"254","intIdProyPaquete":"815","intIdTipoEstructurado":"2541"
     *                      ,"intRevision":"2640","deciLong":"2000.000","varPerfil":"TR-100X50X2.5","varDescripcion":"VIGA",
     *                      "deciPesoNeto":"11.860","deciPesoBruto":"11.860","deciPesoContr":"11.860","deciArea":"0.660",
     *                        "deciAncho":"101.000","deciAlto":"100.000","varModelo":"RAMPA","varCodVal":"LFW","usua_modi":"usuario"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Actualizacion Satisfactoria"
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description="la cantidad ingresada,es menor a la cantidad registrada. Por favor de anularlos."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function actu_elem(Request $request) {
        
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'varCodiElemento' => 'required|max:255',
            'cantidad' => 'required|max:255',
            'intIdProyZona' => 'required|max:255',
            'intIdProyTarea' => 'required|max:255',
            'intIdProyPaquete' => 'required|max:255',
            'intIdTipoEstructurado' => 'required|max:255',
            'intRevision' => 'required|max:255',
            'deciLong' => 'required|max:255',
            'varPerfil' => 'required|max:255',
            'varDescripcion' => 'required|max:255',
            'deciPesoNeto' => 'required|max:255',
            'deciPesoBruto' => 'required|max:255',
            'deciPesoContr' => 'required|max:255',
            'deciArea' => 'required|max:255',
            'deciAncho' => 'required|max:255',
            'deciAlto' => 'required|max:255',
            'varModelo' => 'required|max:255',
            'intIdTipoEstru' => 'required|max:255',
            'varCodVal' => 'required|max:255',
            'usua_modi' => 'required|max:255'
        ];
        $this->validate($request, $regla);

        date_default_timezone_set('America/Lima'); // CDT
        $vali_prim_fase = Elemento::where('varCodiElemento', $request->input('varCodiElemento'))
                        ->where('intIdTipoProducto', $request->input('intIdTipoProducto'))
                        ->where('intIdProy', $request->input('intIdProy'))
                        ->where('intIdProyZona', $request->input('intIdProyZona'))
                        ->where('intIdProyTarea', $request->input('intIdProyTarea'))
                        ->where('intIdProyPaquete', $request->input('intIdProyPaquete'))
                        ->where('intIdEsta', '!=', '6')->get(['intIdEleme', 'intIdEsta']);

        //cantidad almacenada en la base de datos 
        $cantidad_almacenada = count($vali_prim_fase);
        // dd($cantidad_almacenada);

        /* PARA ACTUALIZAR ENVIAMOS VALOR1 VALOR2,3,4,5
         * 
         */
        if ($request->input('varValo1') == null || $request->input('varValo1') == "") {
            $varValo1 = '';
        } else {
            $varValo1 = $request->input('varValo1');
        }
        if ($request->input('varValo2') == null || $request->input('varValo2') == "") {
            $varValo2 = '';
        } else {
            $varValo2 = $request->input('varValo2');
        }
        if ($request->input('varValo3') == null || $request->input('varValo3') == "") {
            $varValo3 = '';
        } else {
            $varValo3 = $request->input('varValo3');
        }
        if ($request->input('varValo4') == null || $request->input('varValo4') == "") {
            $varValo4 = '';
        } else {
            $varValo4 = $request->input('varValo4');
        }
        if ($request->input('varValo5') == null || $request->input('varValo5') == "") {
            $varValo5 = '';
        } else {
            $varValo5 = $request->input('varValo5');
        }




        if ($cantidad_almacenada == ($request->input('cantidad'))) {// igualamos la cantidad registrada con la cantidad ingresada
            //obtener todas las  intIdElement de la condicion que se muesta acontinuacion
            $obte_id_Elem = Elemento::where('varCodiElemento', $request->input('varCodiElemento'))
                            ->where('intIdTipoProducto', $request->input('intIdTipoProducto'))
                            ->where('intIdProy', $request->input('intIdProy'))
                            ->where('intIdProyTarea', $request->input('intIdProyTarea'))
                            ->where('intIdProyZona', $request->input('intIdProyZona'))
                            ->where('intIdProyPaquete', $request->input('intIdProyPaquete'))
                            ->where('intIdEsta', '!=', '6')->where('intIdEsta', '!=', '2')->get(['intIdEleme']);

            // La cantidad de intIdElement que cumple  la condicion 
            $total_id_Elem = count($obte_id_Elem);







            for ($i = 0; $i < $total_id_Elem; $i++) {
                //$obte_id_Elem[0]['intIdEleme']   = primero del array que  se origino en $obte_id_Elem 
                $actu_elemen = Elemento::where('intIdEleme', '=', $obte_id_Elem[$i]['intIdEleme'])
                        ->update([
                    'intIdProy' => $request->input('intIdProy'),
                    'intIdTipoProducto' => $request->input('intIdTipoProducto'),
                    'intIdProyZona' => $request->input('intIdProyZona'),
                    'intIdProyTarea' => $request->input('intIdProyTarea'),
                    'intIdProyPaquete' => $request->input('intIdProyPaquete'),
                    'intIdTipoEstructurado' => $request->input('intIdTipoEstructurado'),
                    'intIdTipoEstru' => $request->input('intIdTipoEstru'),
                    'intRevision' => $request->input('intRevision'),
                    'deciLong' => $request->input('deciLong'),
                    'varPerfil' => $request->input('varPerfil'),
                    'varDescripcion' => $request->input('varDescripcion'),
                    'deciPesoNeto' => $request->input('deciPesoNeto'),
                    'deciPesoBruto' => $request->input('deciPesoBruto'),
                    'deciPesoContr' => $request->input('deciPesoContr'),
                    'deciArea' => $request->input('deciArea'),
                    'deciAncho' => $request->input('deciAncho'),
                    'deciAlto' => $request->input('deciAlto'),
                    'varModelo' => $request->input('varModelo'),
                    'varCodVal' => $request->input('varCodVal'),
                    'varValo1' => $varValo1,
                    'varValo2' => $varValo2,
                    'varValo3' => $varValo3,
                    'varValo4' => $varValo4,
                    'varValo5' => $varValo5,
                    'intCantAcce' => $request->input('intCantAcce'),
                    'deciAreaPintura' => $request->input('deciAreaPintura'),
                    'usua_modi' => $request->input('usua_modi'),
                    'hora_modi' => $current_date = date('Y/m/d H:i:s'),
                ]);
            }
            //Mensaje de respuesta para la actualizacion 
            $mensaje = [
                'mensaje' => 'Actualizacion Satisfactoria.'
            ];
            return $this->successResponse($mensaje);
        } else if ($cantidad_almacenada < ($request->input('cantidad'))) {



            //obtener todas las  intIdElement de la condicion que se muesta acontinuacion
            $obte_id_Elem = Elemento::where('varCodiElemento', $request->input('varCodiElemento'))
                            ->where('intIdTipoProducto', $request->input('intIdTipoProducto'))
                            ->where('intIdProy', $request->input('intIdProy'))
                            ->where('intIdProyTarea', $request->input('intIdProyTarea'))
                            ->where('intIdProyZona', $request->input('intIdProyZona'))
                            ->where('intIdProyPaquete', $request->input('intIdProyPaquete'))
                            ->where('intIdEsta', '!=', '6')->where('intIdEsta', '!=', '2')->get(['intIdEleme']);
            //array  [{"intIdEleme":1},{"intIdEleme":7},{"intIdEleme":8},{"intIdEleme":9}]                      
            // La cantidad de intIdElement que cumple  la condicion  $obte_id_Elem
            $total_id_Elem_regi = count($obte_id_Elem); //total_id_Elem_regi = 4         
            $alma_cant_rest = ($request->input('cantidad')) - $total_id_Elem_regi;

            //hacemos el recorrido de el actualizar 
            for ($i = 0; $i < $total_id_Elem_regi; $i++) {
                // actualizar los idElementos   
                $actu_elemen = Elemento::where('intIdEleme', '=', $obte_id_Elem[$i]['intIdEleme'])
                        ->update([
                    'intIdProy' => $request->input('intIdProy'),
                    'intIdTipoProducto' => $request->input('intIdTipoProducto'),
                    'intIdProyZona' => $request->input('intIdProyZona'),
                    'intIdProyTarea' => $request->input('intIdProyTarea'),
                    'intIdProyPaquete' => $request->input('intIdProyPaquete'),
                    'intIdTipoEstructurado' => $request->input('intIdTipoEstructurado'),
                    'intIdTipoEstru' => $request->input('intIdTipoEstru'),
                    // 'varCodiElemento'=>$request->input('varCodiElemento'),
                    // 'intSerie'=>   $contador,
                    'intRevision' => $request->input('intRevision'),
                    'deciLong' => $request->input('deciLong'),
                    'varPerfil' => $request->input('varPerfil'),
                    'varDescripcion' => $request->input('varDescripcion'),
                    'deciPesoNeto' => $request->input('deciPesoNeto'),
                    'deciPesoBruto' => $request->input('deciPesoBruto'),
                    'deciPesoContr' => $request->input('deciPesoContr'),
                    'deciArea' => $request->input('deciArea'),
                    'deciAncho' => $request->input('deciAncho'),
                    'deciAlto' => $request->input('deciAlto'),
                    'varModelo' => $request->input('varModelo'),
                    'varCodVal' => $request->input('varCodVal'),
                    'varValo1' => $varValo1,
                    'varValo2' => $varValo2,
                    'varValo3' => $varValo3,
                    'varValo4' => $varValo4,
                    'varValo5' => $varValo5,
                    'intCantAcce' => $request->input('intCantAcce'),
                    'deciAreaPintura' => $request->input('deciAreaPintura'),
                    'usua_modi' => $request->input('usua_modi'),
                    'hora_modi' => $current_date = date('Y/m/d H:i:s'),
                ]);
            }

            //hacemos el recorrido de los nuevos 
            for ($i = 0; $i < $alma_cant_rest; $i++) {
                $serie = 1;

                $cond_parlis = Elemento::where('varCodiElemento', $request->input('varCodiElemento'))
                        ->max('intSerie');

                $serie = (int) $serie + (int) $cond_parlis;

                $actu_elemen = Elemento::create([
                            'intIdProy' => $request->input('intIdProy'),
                            'intIdTipoProducto' => $request->input('intIdTipoProducto'),
                            'intIdProyZona' => $request->input('intIdProyZona'),
                            'intIdProyTarea' => $request->input('intIdProyTarea'),
                            'intIdProyPaquete' => $request->input('intIdProyPaquete'),
                            'intIdTipoEstructurado' => $request->input('intIdTipoEstructurado'),
                            'intIdTipoEstru' => $request->input('intIdTipoEstru'),
                            'varCodiElemento' => $request->input('varCodiElemento'),
                            'intSerie' => $serie,
                            'intRevision' => $request->input('intRevision'),
                            'deciLong' => $request->input('deciLong'),
                            'varPerfil' => $request->input('varPerfil'),
                            'varDescripcion' => $request->input('varDescripcion'),
                            'deciPesoNeto' => $request->input('deciPesoNeto'),
                            'deciPesoBruto' => $request->input('deciPesoBruto'),
                            'deciPesoContr' => $request->input('deciPesoContr'),
                            'deciArea' => $request->input('deciArea'),
                            'deciAncho' => $request->input('deciAncho'),
                            'deciAlto' => $request->input('deciAlto'),
                            'varModelo' => $request->input('varModelo'),
                            'varCodVal' => $request->input('varCodVal'),
                            'varValo1' => $varValo1,
                            'varValo2' => $varValo2,
                            'varValo3' => $varValo3,
                            'varValo4' => $varValo4,
                            'varValo5' => $varValo5,
                            'intCantAcce' => $request->input('intCantAcce'),
                            'deciAreaPintura' => $request->input('deciAreaPintura'),
                            'usua_modi' => $request->input('usua_modi'),
                            'hora_modi' => $current_date = date('Y/m/d H:i:s'),
                            'intIdEsta' => 4
                ]);
            }


            $mensaje = [
                'mensaje' => 'Actualizacion Satisfactoria.'
            ];
            return $this->successResponse($mensaje);
        } else {
            $mensaje = [
                'mensaje' => "la cantidad ingresada es " . $request->input('cantidad') .
                " y es menor a la cantidad registrada que es " . $cantidad_almacenada . ". Por favor de anularlos."
            ];
            return $this->successResponse($mensaje);
        }

        // return $this->successResponse($vali_prim_fase);                                    
    }

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/vali_revi",
     *     tags={"Gestion Elemento"},
     *     summary="validar la revision del partList",
     *     @OA\Parameter(
     *         description="Ingresar la revision",
     *         in="path",
     *         name="intRevision",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *     @OA\Parameter(
     *         description="Ingresar id proyecto",
     *         in="path",
     *         name="intIdProy",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingresar id Tipo Producto",
     *         in="path",
     *         name="intIdTipoProducto",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *     @OA\Parameter(
     *         description="Ingresar codigo de elemento",
     *         in="path",
     *         name="varCodiElemento",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *        
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="varNumeDni",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProy": "126","intIdTipoProducto":"2548","varCodiElemento":"013-AP-P1","intIdProyPaquete":"49"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="ELEMENTO NO EXISTE. POR FAVOR DE REGISTRAR."
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description="Error."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    // validar la revision del partlist (PAra actualizar el partlist)
    public function vali_revi(Request $request) {
        $regla = [
            'intRevision' => 'required|max:255', // lo que ingresa el  usuario 
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'varCodiElemento' => 'required|max:255',
            'intIdProyZona' => 'required|max:255',
            'intIdProyTarea' => 'required|max:255',
            'intIdProyPaquete' => 'required|max:255',
        ];
        $this->validate($request, $regla);
        $valid_revi = Elemento::where('intIdProy', $request->input('intIdProy'))
                ->where('intIdTipoProducto', $request->input('intIdTipoProducto'))
                ->where('varCodiElemento', $request->input('varCodiElemento'))
                ->where('intIdProyZona', $request->input('intIdProyZona'))
                ->where('intIdProyTarea', $request->input('intIdProyTarea'))
                ->where('intIdProyPaquete', $request->input('intIdProyPaquete'))
                ->where('intIdEsta', '!=', '6')->where('intIdEsta', '!=', '2')
                ->first(['intIdEleme', 'intRevision']);

        //dd("".$valid_revi['intRevision']);
        // if($valid_revi['intRevision'])

        if ($request->input('intRevision') >= $valid_revi['intRevision']) {

            $mensaje = [
                'mensaje' => "Exito."
            ];

            return $this->successResponse($mensaje);
        } else {

            $mensaje = [
                'mensaje' => "LA REVISION INGRESADA " . $request->input('intRevision') .
                " ES MENOR A LA QUE SE ENCUENTRA ACTUALMENTE " . $valid_revi['intRevision'] . "."
            ];

            return $this->successResponse($mensaje);
        }
    }

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/vali_cantidad",
     *     tags={"Gestion Elemento"},
     *     summary="validar la cantidad en el partlist",
     *     @OA\Parameter(
     *         description="ingresar el codigo del elemento",
     *         in="path",
     *         name="varCodiElemento",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="ingresar id proyecto",
     *         in="path",
     *         name="intIdProy",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="ingresar id tipo producto",
     *         in="path",
     *         name="intIdTipoProducto",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="ingresar id de la tarea",
     *         in="path",
     *         name="intIdProyTarea",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="ingresar id de la paquete",
     *         in="path",
     *         name="intIdProyPaquete",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="ingresar id de la zona",
     *         in="path",
     *         name="intIdProyZona",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),

     *        *     @OA\Parameter(
     *         description="ingresar cantidad",
     *         in="path",
     *         name="intIdProyZona",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *        
     *        
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="varCodiElemento",
     *                     type="string"
     *                 ) ,
     *                   @OA\Property(
     *                     property="intIdProy",
     *                     type="string"
     *                 ) ,
     *                     @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
     *                 
     *                   @OA\Property(
     *                     property="intIdProyTarea",
     *                     type="string"
     *                 ) ,
     *       
     *                   @OA\Property(
     *                     property="intIdProyPaquete",
     *                     type="string"
     *                 ) ,
     *                       @OA\Property(
     *                     property="intIdProyZona",
     *                     type="string"
     *                 ) ,
     *                        @OA\Property(
     *                     property="cantidad",
     *                     type="string"
     *                 ) ,
     *                 example={"varCodiElemento": "013-AP-V25","intIdProy":"150","intIdTipoProducto":"450","intIdProyTarea":"125"
     *                         ,"intIdProyPaquete":"840","intIdProyZona":"541","cantidad":"12"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Exito."
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description="La cantidad ingresada, es menor a la que se encuentra Actual"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function vali_cantidad(Request $request) {
        $regla = [
            'varCodiElemento' => 'required|max:255',
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'intIdProyTarea' => 'required|max:255',
            'intIdProyPaquete' => 'required|max:255',
            'intIdProyZona' => 'required|max:255',
            'cantidad' => 'required|max:255'
        ];
        $this->validate($request, $regla);

        $cond_parlis = Elemento::where('varCodiElemento', $request->input('varCodiElemento'))
                ->where('intIdProy', $request->input('intIdProy'))
                ->where('intIdTipoProducto', $request->input('intIdTipoProducto'))
                ->where('intIdProyTarea', $request->input('intIdProyTarea'))
                ->where('intIdProyPaquete', $request->input('intIdProyPaquete'))
                ->where('intIdProyZona', $request->input('intIdProyZona'))
                ->where('intIdEsta', '!=', '6')
                ->max('intSerie');



        $obte_id_Elem = Elemento::where('varCodiElemento', $request->input('varCodiElemento'))
                        ->where('intIdTipoProducto', $request->input('intIdTipoProducto'))
                        ->where('intIdProy', $request->input('intIdProy'))
                        ->where('intIdProyTarea', $request->input('intIdProyTarea'))
                        ->where('intIdProyPaquete', $request->input('intIdProyPaquete'))
                        ->where('intIdProyZona', $request->input('intIdProyZona'))
                        ->where('intIdEsta', '!=', '6')->get(['intSerie']);
        //die("sad".$obte_id_Elem);

        $total_id_Elem_regi = count($obte_id_Elem);


        if ($total_id_Elem_regi == ($request->input('cantidad'))) {
            $mensaje = [
                'mensaje' => "Exito."
            ];

            return $this->successResponse($mensaje);
        } else if (($request->input('cantidad')) > $total_id_Elem_regi) {

            $mensaje = [
                'mensaje' => "Exito."
            ];

            return $this->successResponse($mensaje);
        } else if (($request->input('cantidad')) < $total_id_Elem_regi) {

            $mensaje = [
                'mensaje' => "La cantidad ingresada " . $request->input('cantidad') .
                " es menor a la que se encuentra Actual que es " . $total_id_Elem_regi . "."
            ];
            return $this->successResponse($mensaje);
        } else if ($request->input('cantidad') == 0) {
            $mensaje = [
                'mensaje' => "Error."
            ];

            return $this->successResponse($mensaje);
        }
    }

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/vali_tipo_grup",
     *     tags={"Gestion Elemento"},
     *     summary="validar el tipo grup",
     *     @OA\Parameter(
     *         description="documento de identidad",
     *         in="path",
     *         name="varCodiTipoGrupo",
     *     example="PERNO",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *        
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="varCodiTipoGrupo",
     *                     type="string"
     *                 ) ,
     *                 example={"varCodiTipoGrupo": "PERNO"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="obtiene el id del tipo grupo"
     *     ),
      @OA\Response(
     *         response=203,
     *         description="El tipo de grupo no se encuentra registrado"
     *     ),
     *     
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function vali_tipo_grup(Request $request) {
        $validar = array('mensaje' => '');
        $regla = [
            'varCodiTipoGrupo' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $varCodiTipoGrupo = strtoupper($request->input('varCodiTipoGrupo'));

        $validar_tipo_grupo = TipoGrupo::where('varCodiTipoGrupo', '=', $varCodiTipoGrupo)
                ->first(['intIdTipoGrupo', 'varCodiTipoGrupo']);
        // dd($validar_tipo_grupo['intIdTipoGrupo']);

        if (is_null($validar_tipo_grupo['intIdTipoGrupo'])) {
            $validar['mensaje'] = "El tipo de grupo no se encuentra registrado: " . $varCodiTipoGrupo;
        } else {
            $validar['mensaje'] = "";
            $validar['id'] = $validar_tipo_grupo['intIdTipoGrupo'];
        }
        return $this->successResponse($validar);
    }

    /**
     * @OA\Get(
     *     path="/GestionPartList/public/index.php/comb_tipo_grupo_wip",
     *     tags={"Gestion Elemento"},
     *     summary="Combo box para Tipo Grupo",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Lista los  Tipo Grupo."
     *     )
     * )
     */
    public function comb_tipo_grupo_wip() {
        $todo = [];
        $combo_tipo_grupo = TipoGrupo::where('intIdEsta', '=', 3)
                ->select('intIdTipoGrupo', 'varDescTipoGrupo')->orderBy('varDescTipoGrupo', 'ASC')
                ->get();


        return $this->successResponse($combo_tipo_grupo);
    }

}
