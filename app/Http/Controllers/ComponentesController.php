<?php

namespace App\Http\Controllers;

use App\Proyectozona;
use App\Proyectotarea;
use App\Componentes;
use App\Elemento;
use App\Proyecto;
use App\TipoProducto;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ComponentesController extends Controller {

    use \App\Traits\ApiResponser;

    // Illuminate\Support\Facades\DB;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        
    }

    
    
 
      // registra el componente en la base de datos
    // Antes de registrar los componente: primero consulta si el intIdProy(Proyecto) y  intIdTipoProducto(Tipo Producto)
    // si existe entonces comienza el proceso de registro.En caso que el intIdProy(Proyecto) y  intIdTipoProducto 
    //entonces mostrara un mensaje  Proyecto no  se encuentra registrado
     /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/regi_comp",
     *     tags={"Gestion Componente"},
     *     summary="Registrar los componentes",
     *     @OA\Parameter(
     *         description="Ingresar el id del proyecto",
     *         in="path",
     *         name="intIdProy",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingresar el id tipo producto",
     *         in="path",
     *         name="intIdTipoProducto",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
       *     @OA\Parameter(
     *         description="Ingresar el codigo de elemento",
     *         in="path",
     *         name="varCodiElemento",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *        
        *     @OA\Parameter(
     *         description="Ingresar la cantidad",
     *         in="path",
     *         name="intCantidad",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      
           *     @OA\Parameter(
     *         description="Ingresar el material",
     *         in="path",
     *         name="varMaterial",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      *
       *     @OA\Parameter(
     *         description="Ingresar el varPerfil",
     *         in="path",
     *         name="varPerfil",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      * 
      *     @OA\Parameter(
     *         description="Ingresar la longitud",
     *         in="path",
     *         name="deciLong",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
       *     @OA\Parameter(
     *         description="Ingresar la Descripcion",
     *         in="path",
     *         name="varDescripcion",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      
        *     @OA\Parameter(
     *         description="Ingresar el peso neto",
     *         in="path",
     *         name="deciPesoNeto",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      
    *     @OA\Parameter(
     *         description="Ingresar el peso bruto",
     *         in="path",
     *         name="deciPesoBruto",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     
      *     @OA\Parameter(
     *         description="Ingresar el peso contr",
     *         in="path",
     *         name="deciPesoContr",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
        *     @OA\Parameter(
     *         description="Ingresar el  Area",
     *         in="path",
     *         name="deciArea",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     
      *  @OA\Parameter(
     *         description="Ingresar el  Area",
     *         in="path",
     *         name="acti_usua",
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
     *                     property="intIdProy",
     *                     type="string"
     *                 ) ,
      *                 @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
      *              @OA\Property(
     *                     property="varCodiElemento",
     *                     type="string"
     *                 ) ,
      *              @OA\Property(
     *                     property="varComponente",
     *                     type="string"
     *                 ) ,
      *                    @OA\Property(
     *                     property="intCantidad",
     *                     type="string"
     *                 ) ,
      *                        @OA\Property(
     *                     property="varMaterial",
     *                     type="string"
     *                 ) ,
      *                  @OA\Property(
     *                     property="varPerfil",
     *                     type="string"
     *                 ) ,
      *                    @OA\Property(
     *                     property="deciLong",
     *                     type="string"
     *                 ) ,
      *                @OA\Property(
     *                     property="varDescripcion",
     *                     type="string"
     *                 ) ,
      *                   @OA\Property(
     *                     property="deciPesoNeto",
     *                     type="string"
     *                 ) ,
      *                 @OA\Property(
     *                     property="deciPesoBruto",
     *                     type="string"
     *                 ) ,
      *                  @OA\Property(
     *                     property="deciPesoContr",
     *                     type="string"
     *                 ) ,
      *                        @OA\Property(
     *                     property="deciArea",
     *                     type="string"
     *                 ) ,
      *              @OA\Property(
     *                     property="acti_usua",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProy": "1500","intIdTipoProducto":"1200","varCodiElemento":"013-AP-V25",
      *                         "varComponente":"013AP-2NP1-6","intCantidad":"1","varMaterial":"A36","varPerfil":"A36",
      *                         "deciLong":"753.000","varDescripcion":"ANGULO","deciPesoNeto":"0.500","deciPesoBruto":"0.500",
      *                          "deciPesoContr":"0.500","deciArea":"0.010","acti_usua":"usuario"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Guardado Satisfactorio."
     *     ),
     *     
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function regi_comp(Request $request) {


        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'varCodiElemento' => 'required|max:255',
            'varComponente' => 'required|max:255',
            'intCantidad' => 'required|max:255',
            'varMaterial' => 'required|max:255',
            'varPerfil' => 'required|max:255',
            'deciLong' => 'required|max:255',
            'varDescripcion' => 'required|max:255',
            'deciPesoNeto' => 'required|max:255',
            'deciPesoBruto' => 'required|max:255',
            'deciPesoContr' => 'required|max:255',
            'deciArea' => 'required|max:255',
            'acti_usua' => 'required|max:255'
        ];
        $this->validate($request, $regla);


        date_default_timezone_set('America/Lima'); // CDT
        $regi_compo = Componentes::create([
                    'intIdProy' => $request->input('intIdProy'),
                    'intIdTipoProducto' => $request->input('intIdTipoProducto'),
                    'varCodiElemento' => $request->input('varCodiElemento'),
                    'varComponente' => $request->input('varComponente'),
                    'intCantidad' => $request->input('intCantidad'),
                    'varMaterial' => $request->input('varMaterial'),
                    'varPerfil' => $request->input('varPerfil'),
                    'deciLong' => $request->input('deciLong'),
                    'varDescripcion' => $request->input('varDescripcion'),
                    'deciPesoNeto' => $request->input('deciPesoNeto'),
                    'deciPesoBruto' => $request->input('deciPesoBruto'),
                    'deciPesoContr' => $request->input('deciPesoContr'),
                    'deciArea' => $request->input('deciArea'),
                    'acti_usua' => $request->input('acti_usua'),
                    'acti_hora' => $current_date = date('Y/m/d H:i:s')
        ]);

        $mensaje = [
            'mensaje' => "Guardado Satisfactorio."
        ];

        return $this->successResponse($mensaje);
    }

    //fin del modulo consultar regis_comp
    // Registrar los componentes, p    ero para evitar problemas, debemos
    // eliminar primero la marca con sus componentes y luego volverlos a registrar.(X)
    //(Falta revisarlo)

    
    
    
    
    
    
    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/modi_comp",
     *      tags={"Gestion Componente"},
     *     summary="Modifica el componente",
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
     
     *      @OA\Parameter(
     *         description="Ingresar codigo elemento",
     *         in="path",
     *         name="varCodiElemento",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     
     *    @OA\Parameter(
     *         description="Ingresar codigo Componente",
     *         in="path",
     *         name="varComponente",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     
     *     @OA\Parameter(
     *         description="Ingresar  Cantidad",
     *         in="path",
     *         name="intCantidad",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
             @OA\Parameter(
     *         description="Ingresar Material",
     *         in="path",
     *         name="varMaterial",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *   
     * 
     
     *         @OA\Parameter(
     *         description="Ingresar Perfil",
     *         in="path",
     *         name="varPerfil",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     
     *           @OA\Parameter(
     *         description="Ingresar la longitud",
     *         in="path",
     *         name="deciLong",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
      *      @OA\Parameter(
     *         description="Ingresar la Descripcion",
     *         in="path",
     *         name="varDescripcion",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     * 
     *      @OA\Parameter(
     *         description="Ingresar la peso neto",
     *         in="path",
     *         name="deciPesoNeto",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *      @OA\Parameter(
     *         description="Ingresar la peso bruto",
     *         in="path",
     *         name="deciPesoBruto",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
    
     *         @OA\Parameter(
     *         description="Ingresar la peso contratista",
     *         in="path",
     *         name="deciPesoContr",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
    
     *          @OA\Parameter(
     *         description="Ingresar la peso deciArea",
     *         in="path",
     *         name="deciArea",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     
     *        @OA\Parameter(
     *         description="Ingresar el usuario",
     *         in="path",
     *         name="acti_usua",
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
     *                     property="intIdProy",
     *                     type="string"
     *                 ) ,
     *                   @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
     *               @OA\Property(
     *                     property="varCodiElemento",
     *                     type="string"
     *                 ) ,
     *                  @OA\Property(
     *                     property="varComponente",
     *                     type="string"
     *                 ) ,
     *                  @OA\Property(
     *                     property="intCantidad",
     *                     type="string"
     *                 ) ,
     *                  @OA\Property(
     *                     property="varMaterial",
     *                     type="string"
     *                 ) ,
     *                   @OA\Property(
     *                     property="varPerfil",
     *                     type="string"
     *                 ) ,
     *                   @OA\Property(
     *                     property="deciLong",
     *                     type="string"
     *                 ) ,
     *                     @OA\Property(
     *                     property="varDescripcion",
     *                     type="string"
     *                 ) ,
     *                         @OA\Property(
     *                     property="deciPesoNeto",
     *                     type="string"
     *                 ) ,
     *                               @OA\Property(
     *                     property="deciPesoBruto",
     *                     type="string"
     *                 ) ,
     *                     @OA\Property(
     *                     property="deciPesoContr",
     *                     type="string"
     *                 ) ,
     *               @OA\Property(
     *                     property="deciArea",
     *                     type="string"
     *                 ) ,
     *               @OA\Property(
     *                     property="acti_usua",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProy": "152","intIdTipoProducto":"1200","varCodiElemento":"013-AP-A3","varComponente":"013-AP-A3",
     *                          "intCantidad":"1","varMaterial":"A36","varPerfil":"A36","deciLong":"1271.000",
     *                           "varDescripcion":"PLATINA DE BARANDA","deciPesoNeto":"0.400","deciPesoBruto":"0.400","deciPesoContr":"0.400",
     *                          "deciArea":"0.650","acti_usua":"usuario"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Guardado con exito."
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
    public function modi_comp(Request $request) {

        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'varCodiElemento' => 'required|max:255',
            'varComponente' => 'required|max:255',
            'intCantidad' => 'required|max:255',
            'varMaterial' => 'required|max:255',
            'varPerfil' => 'required|max:255',
            'deciLong' => 'required|max:255',
            'varDescripcion' => 'required|max:255',
            'deciPesoNeto' => 'required|max:255',
            'deciPesoBruto' => 'required|max:255',
            'deciPesoContr' => 'required|max:255',
            'deciArea' => 'required|max:255',
            'acti_usua' => 'required|max:255'
        ];
        $this->validate($request, $regla);

        //mediante esta validacion obtenemos el array 
        $vali_compon = Componentes::where('intIdProy', $request->input('intIdProy'))
                        ->where('intIdTipoProducto', $request->input('intIdTipoProducto'))
                        ->where('varCodiElemento', $request->input('varCodiElemento'))->get(['intIdComponente']);


        date_default_timezone_set('America/Lima'); // CDT
        //validamos si existe la condicion
        if (isset($vali_compon)) {

            // se hace el procedimiento de Eliminar
            /*  $dele_comp=Componentes::where('intIdProy',$request->input('intIdProy'))
              ->where('intIdTipoProducto',$request->input('intIdTipoProducto'))
              ->where('varCodiElemento',$request->input('varCodiElemento'))->delete();
             */
            //se hace el   procedimiento de creata 
            $regis_comp = Componentes::create([
                        'intIdProy' => $request->input('intIdProy'),
                        'intIdTipoProducto' => $request->input('intIdTipoProducto'),
                        'varCodiElemento' => $request->input('varCodiElemento'),
                        'varComponente' => $request->input('varComponente'),
                        'intCantidad' => $request->input('intCantidad'),
                        'varMaterial' => $request->input('varMaterial'),
                        'varPerfil' => $request->input('varPerfil'),
                        'deciLong' => $request->input('deciLong'),
                        'varDescripcion' => $request->input('varDescripcion'),
                        'deciPesoNeto' => $request->input('deciPesoNeto'),
                        'deciPesoBruto' => $request->input('deciPesoBruto'),
                        'deciPesoContr' => $request->input('deciPesoContr'),
                        'deciArea' => $request->input('deciArea'),
                        'acti_usua' => $request->input('acti_usua'),
                        'acti_hora' => $current_date = date('Y/m/d H:i:s')
            ]);
            //gUARDA EL REGISTRAR
            $mensaje = [
                'mensaje' => 'Guardado con exito.'
            ];
            return $this->successResponse($mensaje);
        } else {
            // EN CASO QUE NO EXITA, SE MANDARA UN MENSAJE AL USUARIO 
            $mensaje = [
                'mensaje' => 'Error.'
            ];
            return $this->successResponse($mensaje);
        }
    }

//Validar que el proyecto cuente con componentes registrados en elementos, 
//si no mostrar mensaje de error “Por favor de registrar los componentes antes de ingresarlos
// se utilizar para componente (REVISARLO)

    
    
    
    /**
     * @OA\Post(
     *      path="/GestionPartList/public/index.php/vali_proy_con_comp",
     *      tags={"Gestion Componente"},
     *     summary="validar el proyecto con el componente",
     *     @OA\Parameter(
     *         description="Ingresar el id del proyecto",
     *         in="path",
     *         name="intIdProy",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *    @OA\Parameter(
     *         description="Ingresar el id del tipo producto",
     *         in="path",
     *         name="intIdTipoProducto",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingresar el codigo del elemento",
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
     *                     property="intIdProy",
     *                     type="string"
     *                 ) ,
     *                   @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
     *               @OA\Property(
     *                     property="varCodiElemento",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProy": "175","intIdTipoProducto":"1","varCodiElemento":"013AP-2NP2-10"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="si se encuentra, entonces mandara el mensaje de Exito"
     *     ),
   
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function vali_proy_con_comp(Request $request) {

        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'varCodiElemento' => 'required|max:255'
        ];

        $this->validate($request, $regla);
        $vali_proy_con_comp = Componentes::where('intIdProy', $request->input('intIdProy'))
                        ->where('intIdTipoProducto', $request->input('intIdTipoProducto'))
                        ->where('varCodiElemento', $request->input('varCodiElemento'))->first([
            'intIdComponente'
        ]);

        // SI EXITE , SIGUE SU FLUJO
        if (isset($vali_proy_con_comp)) {
            $mensaje = [
                'mensaje' => 'Exito.'
            ];
            return $this->successReponse($mensaje);
        } else {

            $mensaje = [
                'mensaje' => 'error.'
            ];

            return $this->successReponse($mensaje);
        }
    }

    //final de FLUJO
    




     /**
     * @OA\Post(
     *      path="/GestionPartList/public/index.php/elim_comp",
     *      tags={"Gestion Componente"},
     *     summary="validar el proyecto con el componente",
     *     @OA\Parameter(
     *         description="Ingresar el id del proyecto",
     *         in="path",
     *         name="intIdProy",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *    @OA\Parameter(
     *         description="Ingresar el id del tipo producto",
     *         in="path",
     *         name="intIdTipoProducto",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingresar el codigo del elemento",
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
     *                     property="intIdProy",
     *                     type="string"
     *                 ) ,
     *                   @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
     *               @OA\Property(
     *                     property="varCodiElemento",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProy": "175","intIdTipoProducto":"1","varCodiElemento":"013AP-2NP2-10"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="si se encuentra entonces, eliminaremos"
     *     ),
   
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    //ELIMINAR COMPONENTE 
    public function elim_comp(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'varCodiElemento' => 'required|max:255'
        ];
        $this->validate($request, $regla);

        //VALIDO SI EXISTE 
        $sele = Componentes::where('intIdProy', $request->input('intIdProy'))
                ->where('intIdTipoProducto', $request->input('intIdTipoProducto'))
                ->where('varCodiElemento', $request->input('varCodiElemento'))
                ->get(['intIdComponente']); // LOS OBTENEGO CON UN ID YA QUE SON VARIOS ID QUE SE HAN ENCONTRADO
        $items = count($sele);
        if ($items == 0) {
            $mensaje = [
                'mensaje' => 'Error.'
            ];
            return $this->successResponse($mensaje);
        } else {
            //ELIMINAR  COMPONENTE MEDIANTE LA FUNCION DESTROY
            $dele = Componentes::destroy($sele->toArray());
            $mensaje = [
                'mensaje' => 'exito.'
            ];
            return $this->successResponse($mensaje);
        }

        /* $collection = ModelName::where('condition', 'value')->get(['id']);
          ModelName::destroy($collection->toArray()); */
    }

    //FINAL DEL PROCEDEMIENTO  DE ELIMINAR ELEMENTO
}
