<?php

namespace App\Http\Controllers;

use App\Proyectotarea;
use App\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\TestTrait;

class ProyectotareaController extends Controller {

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

    
    
    
//funcion principal para el registro de elementos  valida si existe ,
// en caso que  no se registra devolviendo el id de la proyecto tarea
     /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/regi_proy_tarea",
     *     tags={"Gestion Proyecto Tarea"},
     *     summary="Regitrar un proyecto tarea.",
     *     @OA\Parameter(
     *         description="Ingrese el id proyecto zona",
     *         in="path",
     *         name="intIdProyZona",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      *     @OA\Parameter(
     *         description="Ingrese el id proyecto",
     *         in="path",
     *         name="intIdProy",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      * 
      *     @OA\Parameter(
     *         description="Ingrese el id TipoProducto",
     *         in="path",
     *         name="intIdTipoProducto",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      * 
              @OA\Parameter(
     *         description="Ingrese el DescripTarea",
     *         in="path",
     *         name="varDescripTarea",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
          
              @OA\Parameter(
     *         description="Ingrese el DescripTarea",
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
     *                     property="intIdProyZona",
     *                     type="string"
     *                 ) ,
      *             @OA\Property(
     *                     property="intIdProy",
     *                     type="string"
     *                 ) ,
      *              @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
      *             @OA\Property(
     *                     property="varDescripTarea",
     *                     type="string"
     *                 ) ,
      *             @OA\Property(
     *                     property="acti_usua",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProyZona": "1200","intIdProy":"1260","intIdTipoProducto":"1410",
      *                         "varDescripTarea":"19_0021_2"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sin Mensaje"
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description="El Documento de identidad ingresado no se encuentra registrado."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function regi_proy_tarea(Request $request) {

        $regla = [
            'intIdProyZona' => 'required|max:255',
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'varDescripTarea' => 'required|max:255',
            'acti_usua' => 'required|max:255'
        ];

        $this->validate($request, $regla);


        $cond_proyect = Proyectotarea::where('intIdProyZona', $request->input('intIdProyZona'))
                ->where('intIdProy', $request->input('intIdProy'))
                ->where('varDescripTarea', $request->input('varDescripTarea'))
                ->first(['intIdProyTarea', 'intIdProyZona', 'intIdProy', 'intIdTipoProducto', 'varDescripTarea']);

        if (!isset($cond_proyect)) {


            date_default_timezone_set('America/Lima'); // CDT
            $crea_proy_tare = Proyectotarea::create([
                        'intIdProyZona' => $request->input('intIdProyZona'),
                        'intIdProy' => $request->input('intIdProy'),
                        'intIdTipoProducto' => $request->input('intIdTipoProducto'),
                        'varDescripTarea' => $request->input('varDescripTarea'),
                        'acti_usua' => $request->input('acti_usua'),
                        'acti_hora' => $current_date = date('Y/m/d H:i:s')
            ]);

            $data = [
                'id' => $crea_proy_tare['intIdProyTarea'],
                'mensaje'=>'Exito.'
                ];
            return $this->successResponse($data);
        } else {

           /* $data = [
                'id' => $cond_proyect['intIdProyTarea'],
                'mensaje'=>'Exito.'
            ];
            return $this->successResponse($data);
            // return $this->successResponse($cond_proyect['intIdProyTarea']);*/
               if ($cond_proyect['intIdProyZona'] == $request->input('intIdProyZona')) {
                $data = [
                    //'mensaje'=>'Tarea ya se encuentra registrada. Dirigirse a la opcion Actualizar.'
                    'mensaje' => "Exito.",
                    'id' => $cond_proyect['intIdProyTarea'],
                    //'zona' => $cond_proyect['intIdProyZona']
                ];
                return $this->successResponse($data);
            } else {
                $data = [
                    //'mensaje'=>'Tarea ya se encuentra registrada. Dirigirse a la opcion Actualizar.'
                    'mensaje' => "EL PROGRAMA YA SE ENCUENTRA ASIGNADA A UNA ZONA. POR FAVOR VERIFICAR EL PARTLIST",
                    'tarea' => $cond_proyect['intIdProyTarea']
                ];
                return $this->successResponse($data);
            }

            
        }
    }
    
    
    
    
    
    
    
 // validamos tarea  si es null entonces que siga con el proceso , en caso contrario entonces 
    // manda un mensaje la tarea se encuentra registrado.
    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/vali_tarea",
     *     tags={"Gestion Proyecto Tarea"},
     *     summary="validar si la tarea existe",
     *     @OA\Parameter(
     *         description="ingresar el id proyecto zona",
     *         in="path",
     *         name="intIdProyZona",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     
     *      @OA\Parameter(
     *         description="ingresar el id proyecto",
     *         in="path",
     *         name="intIdProy",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     
     *        @OA\Parameter(
     *         description="ingresar el id  tipo producto ",
     *         in="path",
     *         name="intIdTipoProducto",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     
     *    *        @OA\Parameter(
     *         description="ingresar la descriptarea ",
     *         in="path",
     *         name="varDescripTarea",
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
     *                     property="intIdProyZona",
     *                     type="string"
     *                 ) ,
     *                   @OA\Property(
     *                     property="intIdProy",
     *                     type="string"
     *                 ) ,
     *                    @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
     *    *               @OA\Property(
     *                     property="varDescripTarea",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProyZona": "25214121","intIdProy":"1500","intIdTipoProducto":"1250"
     *                          ,"varDescripTarea":"114"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="si no existe entonces, mandamos un mensaje de exito para la siguiente evaluacion"
     *     ),
              @OA\Response(
     *         response=201,
     *         description=" la tarea ya se encuentra asignada a una zona.Por favor verificar el Patlist"
     *     ),
     *    
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function vali_tarea(Request $request) {

        $regla = [
            'intIdProyZona' => 'required|max:255',
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'varDescripTarea' => 'required|max:255',
                // 'acti_usua'=>'required|max:255'
        ];

        $this->validate($request, $regla);


        $cond_proyect = Proyectotarea::where('intIdProy', $request->input('intIdProy'))
                        ->where('varDescripTarea', $request->input('varDescripTarea'))
                        ->where('intIdTipoProducto', $request->input('intIdTipoProducto'))
                        ->select('intIdProyTarea', 'intIdProyZona')->first([]);


        if (!isset($cond_proyect)) {


            $data = [
                'mensaje' => "Exito."
            ];
            return $this->successResponse($data);
        } else {

            if ($cond_proyect['intIdProyZona'] == $request->input('intIdProyZona')) {
                $data = [
                    //'mensaje'=>'Tarea ya se encuentra registrada. Dirigirse a la opcion Actualizar.'
                    'mensaje' => "EXITO ZONA.",
                    'tarea' => $cond_proyect['intIdProyTarea'],
                    'zona' => $cond_proyect['intIdProyZona']
                ];
                return $this->successResponse($data);
            } else {
                $data = [
                    //'mensaje'=>'Tarea ya se encuentra registrada. Dirigirse a la opcion Actualizar.'
                    'mensaje' => "EL PROGRAMA YA SE ENCUENTRA ASIGNADA A UNA ZONA. POR FAVOR VERIFICAR EL PARTLIST",
                    'tarea' => $cond_proyect['intIdProyTarea']
                ];
                return $this->successResponse($data);
            }



            // return $this->successResponse($cond_proyect['intIdProyTarea']);
        }
    }

}
