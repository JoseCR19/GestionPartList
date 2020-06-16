<?php

namespace App\Http\Controllers;

use App\Proyectopaquete;
use App\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProyectopaqueteController extends Controller {

    use \App\Traits\ApiResponser;

    // Illuminate\Support\Facades\DB;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        //
    }

 /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/regi_proy_pque",
     *     tags={"Gestion Proyecto Paquete"},
     *     summary="Registrar Proyecto Paquete",
     *     @OA\Parameter(
     *         description="ingresar el id proyecto tarea",
     *         in="path",
     *         name="intIdProyTarea",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
             *     @OA\Parameter(
     *         description="ingresar el id  Proyectos",
     *         in="path",
     *         name="intIdProy",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      *     @OA\Parameter(
     *         description="documento de id tipo Producto",
     *         in="path",
     *         name="intIdTipoProducto",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ), 
            
         @OA\Parameter(
     *         description="ingresar el usuario que va a registrar",
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
     *                     property="intIdProyTarea",
     *                     type="string"
     *                 ) ,
  *                  @OA\Property(
     *                     property="intIdProy",
     *                     type="string"
     *                 ) ,
                    @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
                    
  *                  @OA\Property(
     *                     property="varCodigoPaquete",
     *                     type="string"
     *                 ) ,
     *                  @OA\Property(
     *                     property="acti_usua",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProyTarea": "123","intIdProy":"124","intIdTipoProducto":"120",
  *                     "varCodigoPaquete":"I002","acti_usua":"usuarios"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Exito."
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Si no existe el paquete entonces se agrega."
     *     ),
             @OA\Response(
     *         response=408,
     *         description="Error diferentes paquetes."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */   
    
// registra los proyecto paquete
    public function regi_proy_pque(Request $request) {
        $regla = [
            'intIdProyTarea' => 'required|max:255',
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'intIdTipoGrupo'=> 'required|max:255',
            'varCodigoPaquete' => 'required|max:255',
            'acti_usua' => 'required|max:255'
        ];

        $this->validate($request, $regla);

        $condi_proy_pque = Proyectopaquete::where('intIdProyTarea', $request->input('intIdProyTarea'))
                ->where('intIdProy', $request->input('intIdProy'))
                ->where('intIdTipoProducto', $request->input('intIdTipoProducto'))
                ->where('varCodigoPaquete', $request->input('varCodigoPaquete'))
                ->first(['intIdProyPaquete', 'intIdProyTarea', 'intIdProy', 'intIdTipoProducto'
            , 'varCodigoPaquete']);

        if (!isset($condi_proy_pque)) {
            date_default_timezone_set('America/Lima'); // CDT
            $crea_proy_pque = Proyectopaquete::create([
                        'intIdProyTarea' => $request->input('intIdProyTarea'),
                        'intIdProy' => $request->input('intIdProy'),
                        'intIdTipoGrupo'=>$request->input('intIdTipoGrupo'),
                        'intIdTipoProducto' => $request->input('intIdTipoProducto'),
                        'varCodigoPaquete' => $request->input('varCodigoPaquete'),
                
                        'intIdEsta'=>17,
                        'acti_usua' => $request->input('acti_usua'),
                        'acti_hora' => $current_date = date('Y/m/d H:i:s')
            ]);

            $dato = [
                'id' => $crea_proy_pque['intIdProyPaquete'],
                'mensaje'=>'Exito.'
            ];
            return $this->successResponse($dato);
        } else {

               /*
            $mensaje = [
                'id' => $condi_proy_pque['intIdProyPaquete'],
                'mensaje'=>'error.'
                    //    'mensaje'=>"El paquete ".$request->input('varCodigoPaquete')." ya se encuentra registrada.  Dirigirse a la opcion Actualizar."//nombre del paquete
            ];
            return $this->successResponse($mensaje);*/
            
             if ($condi_proy_pque['intIdProyTarea'] == $request->input('intIdProyTarea')) {
                $data = [
                    //'mensaje'=>'Tarea ya se encuentra registrada. Dirigirse a la opcion Actualizar.'
                    'mensaje' => "Exito.",
                    
                    'id' => $condi_proy_pque['intIdProyPaquete']
                ];
                return $this->successResponse($data);
            } else {
                $data = [
                    //'mensaje'=>'Tarea ya se encuentra registrada. Dirigirse a la opcion Actualizar.'
                    'mensaje' => "ERROR DIFERENTE GRUPO.",
                    'tarea' => $condi_proy_pque['intIdProyTarea']
                ];
                return $this->successResponse($data);
            }
        }
    }

    
    
    
    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/vali_proy_pque",
     *         tags={"Gestion Proyecto Paquete"},
     *     summary="Validar El proyecto paquete",
     *     @OA\Parameter(
     *         description="ingresar el id proyecto",
     *         in="path",
     *         name="intIdProy",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
             *     @OA\Parameter(
     *         description="ingresar el id tipo producto",
     *         in="path",
     *         name="intIdTipoProducto",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      *     @OA\Parameter(
     *         description="ingresar el id proyecto tarea",
     *         in="path",
     *         name="intIdProyTarea",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ), 
            
         @OA\Parameter(
     *         description="ingresar codigo de paquete",
     *         in="path",
     *         name="varCodigoPaquete",
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
     *                     property="intIdProyTarea",
     *                     type="string"
     *                 ) ,
  *                  @OA\Property(
     *                     property="intIdProy",
     *                     type="string"
     *                 ) ,
                    @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
                    
  *                  
     *                  @OA\Property(
     *                     property="varCodigoPaquete",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProy": "123","intIdTipoProducto":"124"
     *                              ,"intIdProyTarea":"120","varCodigoPaquete":"I002","acti_usua":"usuarios"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Exito."
     *     ),
 
            
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */   
//valida si el proyecto existe, en caso que exista manda un mensaje de exito.
    public function vali_proy_pque(Request $request) {
        $regla = [
            // 'intIdProyTarea'=>'required|max:255',
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'intIdProyTarea' => 'required|max:255',
            'intIdTipoGrupo'=>'required|max:255',
            'varCodigoPaquete' => 'required|max:255',
                //'acti_usua'=>'required|max:255'
        ];

        $this->validate($request, $regla);

        $condi_proy_pque = Proyectopaquete::where('intIdProy', $request->input('intIdProy'))
                ->where('intIdTipoProducto', $request->input('intIdTipoProducto'))
                ->where('varCodigoPaquete', $request->input('varCodigoPaquete'))
                ->where('intIdTipoGrupo', $request->input('intIdTipoGrupo'))
               // ->where('intIdProyTarea', $request->input('intIdProyTarea'))
                ->first(['intIdProyPaquete', 'intIdProyTarea', 'intIdProy', 'intIdTipoProducto'
            , 'varCodigoPaquete']);

        // dd($condi_proy_pque);

        if (!isset($condi_proy_pque)) {
            /// die("asdasd");

            $mensaje = [
                'mensaje' => "Exito."
            ];
            return $this->successResponse($mensaje);
        } else {

            if ($condi_proy_pque['intIdProyTarea'] == $request->input('intIdProyTarea')) {
                $data = [
                    //'mensaje'=>'Tarea ya se encuentra registrada. Dirigirse a la opcion Actualizar.'
                    'mensaje' => "EXITO GRUPO.",
                    
                    'paquete' => $condi_proy_pque['intIdProyPaquete']
                ];
                return $this->successResponse($data);
            } else {
                $data = [
                    //'mensaje'=>'Tarea ya se encuentra registrada. Dirigirse a la opcion Actualizar.'
                    'mensaje' => "ERROR DIFERENTE GRUPO.",
                    'tarea' => $condi_proy_pque['intIdProyTarea']
                ];
                return $this->successResponse($data);
            }



            /* $mensaje=[
              'mensaje'=>"Error.",
              'id'=>$condi_proy_pque['intIdProyPaquete']
              ];
              return $this->successResponse($mensaje);

              //return $this->successResponse($condi_proy_pque['intIdProyPaquete']);
             */
        }
    }

     /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/obte_proy_paqu",
     *       tags={"Gestion Proyecto Paquete"},
     *     summary="Obtener Proyecto Paquete",
     *     @OA\Parameter(
     *         description="ingresar el codigo elemento",
     *         in="path",
     *         name="varCodigoPaquete",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
             *     @OA\Parameter(
     *         description="ingresar el id proyecto",
     *         in="path",
     *         name="intIdProy",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      *     @OA\Parameter(
     *         description="ingresar el id tipo producto",
     *         in="path",
     *         name="intIdTipoProducto",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ), 
            
           
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *          
  *                  @OA\Property(
     *                     property="intIdProy",
     *                     type="string"
     *                 ) ,
                    @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
                    
  *                  @OA\Property(
     *                     property="intIdProyTarea",
     *                     type="string"
     *                 ) ,
     *                  @OA\Property(
     *                     property="varCodigoPaquete",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProy": "123","intIdTipoProducto":"124","intIdProyTarea":"120","varCodigoPaquete":"I002","acti_usua":"usuarios"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Exito."
     *     ),
 
            
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */   
    /*     * PRIMERA VALIDACION obtener id paquete* */
    public function obte_proy_paqu(Request $request) {
        $regla = [
            'varCodigoPaquete' => 'required|max:255',
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255'
        ];
        $this->validate($request, $regla);

        $obte_idproypaqu = proyectopaquete::where('intIdProy', $request->input('intIdProy'))
                        ->where('intIdTipoProducto', $request->input('intIdTipoProducto'))
                        ->where('varCodigoPaquete', $request->input('varCodigoPaquete'))->first(['intIdProyPaquete']);

        return $this->successResponse($obte_idproypaqu);
    }

}
