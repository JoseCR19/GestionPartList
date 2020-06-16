<?php

namespace App\Http\Controllers;

use App\Proyectopaquete;
use App\Proyectotarea;
use App\Proyectozona;
use App\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProyectozonaController extends Controller {

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

    // SE LISTA EL PROYECTO ZONA QUE ESTA REGISTRADO EN LA BASE DE DATOS

    /**
     * @OA\Get(
     *     path="/GestionPartList/public/index.php/list_proy_zona",
     *     tags={"Gestion Proyecto Zona"},
     *     summary="Listar los proyecto zona",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Listar los proyectos zonas."
     *     )
     * )
     */
    public function list_proy_zona() {
        $list_proy_zona = Proyectozona::join('tipo_producto', 'tipo_producto.intIdTipoProducto', '=', 'proyecto_zona.intIdTipoProducto')
                        ->join('proyecto', 'proyecto.intIdProy', '=', 'proyecto_zona.intIdProy')
                        ->select('proyecto_zona.intIdProyZona', 'proyecto.intIdProy', 'proyecto.varDescProy', 'tipo_producto.intIdTipoProducto', 'tipo_producto.varDescTipoProd', 'proyecto_zona.varDescrip'
                        )->get();

        return $this->successResponse($list_proy_zona);
    }

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/buscar_codiProy",
     *    tags={"Gestion Proyecto Zona"},
     *     summary="Buscar el codigo proyecto",
     *     @OA\Parameter(
     *         description="Ingresar el codigo proyecto",
     *         in="path",
     *         name="varCodiProy",
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
     *                     property="varCodiProy",
     *                     type="string"
     *                 ) ,
     *                 example={"varCodiProy": "18-0097PIR"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Muestra el id de varCodiProy"
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description="No se encontro el Codigo del proyecto."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function buscar_codiProy(Request $request) {
        $regla = [
            'varCodiProy' => 'required|max:255'
        ];
        $this->validate($request, $regla);

        $busc_codiproy = Proyecto::where('varCodiProy', $request->input('varCodiProy'))->first(['varCodiProy']);

        if (($busc_codiproy['varCodiProy']) != ($request->input('varCodiProy'))) {

            $mensaje = [
                'mensaje' => 'No se encontro el Codigo del proyecto.'
            ];

            $this->successResponse($mensaje);
        } else {

            $enco_codiproy = Proyecto::where('varCodiProy', $request->input('varCodiProy'))->first(['intIdProy']);
            return $this->successResponse($enco_codiproy);
        }
    }

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/regi_proy_zona",
     *     tags={"Gestion Proyecto Zona"},
     *     summary="Registrar el proyecto zona",
     *     @OA\Parameter(
     *         description="Ingrese el id de proyecto",
     *         in="path",
     *         name="intIdProy",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="Ingrese la descripcion de la tarea",
     *         in="path",
     *         name="varDescripTarea",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="Ingrese id tipo producto",
     *         in="path",
     *         name="intIdTipoProducto",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *         @OA\Parameter(
     *         description="Ingrese la descripcion",
     *         in="path",
     *         name="varDescrip",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * *         @OA\Parameter(
     *         description="Ingrese la codigo del usuario",
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
     *                     property="varDescripTarea",
     *                     type="string"
     *                 ) ,
     *                    @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) , @OA\Property(
     *                     property="varDescrip",
     *                     type="string"
     *                 ) ,@OA\Property(
     *                     property="varCodigoPaquete",
     *                     type="string"
     *                 ) ,
     *                     @OA\Property(
     *                     property="acti_usua",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProy": "1205","varDescripTarea":"101","intIdTipoProducto":"1",
     *                        "varDescrip":"I003","varCodigoPaquete":"I003"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="si existe entonces mandamos el id de ese id proyecto zona"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function regi_proy_zona(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            'varDescripTarea' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'varDescrip' => 'required|max:255',
            'varCodigoPaquete' => 'required|max:255',
            'acti_usua' => 'required|max:255',
            'varCodSubOt' => 'required|max:255',
            'desProd' => 'required|max:255'
        ];
        $this->validate($request, $regla);

        $condi_proy_pque = Proyectopaquete::where('intIdProy', $request->input('intIdProy'))
                ->where('intIdTipoProducto', $request->input('intIdTipoProducto'))
                ->where('varCodigoPaquete', $request->input('varCodigoPaquete'))
                ->first(['intIdProyTarea']);

        if (isset($condi_proy_pque)) {

            $cond_proyect_tare = Proyectotarea::where('intIdProyTarea', $condi_proy_pque['intIdProyTarea'])
                    ->where('intIdProy', $request->input('intIdProy'))
                    //    ->where('intIdTipoProducto', $request->input('intIdTipoProducto'))
                    ->first(['intIdProyZona', 'varDescripTarea']);


            if ($cond_proyect_tare['varDescripTarea'] == $request->input('varDescripTarea')) {

                $condi_proy_zona_paqu = Proyectozona::where('intIdProyZona', $cond_proyect_tare['intIdProyZona'])
                        ->where('intIdProy', $request->input('intIdProy'))
                        ->where('intIdTipoProducto', $request->input('intIdTipoProducto'))
                        ->first(['varDescrip', 'intIdProyZona']);

                if ($condi_proy_zona_paqu['varDescrip'] == $request->input('varDescrip')) {

                    $data = [
                        'id' => $condi_proy_zona_paqu['intIdProyZona'],
                        'mensaje' => 'Exito.'
                    ];
                    return $this->successResponse($data);
                } else {
                    $data = [
                        'mensaje' => 'EL GRUPO ASIGNADO ESTA EN ESE PROGRAMA ' . $request->input('varDescripTarea') . ' PERO NO SE ENCUENTRA EN ESA ZONA ' . $request->input('varDescrip') . '.'
                    ];
                    return $this->successResponse($data);
                }
            } else {
                $data = [
                    'mensaje' => 'EL GRUPO ' . $request->input('varCodigoPaquete') . ' YA SE ENCUENTRA ASIGNADA A UN PROGRAMA EN ESA ZONA.'
                ];
                return $this->successResponse($data);
            }
        } else {

            $cond_proyect = Proyectotarea::where('varDescripTarea', $request->input('varDescripTarea'))
                    ->where('intIdProy', $request->input('intIdProy'))
                    //    ->where('intIdTipoProducto', $request->input('intIdTipoProducto'))
                    ->first(['intIdProyZona']);

            if (isset($cond_proyect)) {

                $condi_proy_zona_desc = Proyectozona::where('intIdProyZona', $cond_proyect['intIdProyZona'])
                        ->where('intIdProy', $request->input('intIdProy'))
                        ->where('intIdTipoProducto', $request->input('intIdTipoProducto'))
                        ->first(['varDescrip']);

                if ($condi_proy_zona_desc['varDescrip'] == $request->input('varDescrip')) {

                    $data = [
                        'id' => $cond_proyect['intIdProyZona'],
                        'mensaje' => 'Exito.'
                    ];
                    return $this->successResponse($data);
                } else {

                    $data = [
                        'mensaje' => 'EL PROGRAMA YA SE ENCUENTRA ASIGNADA A UNA ZONA.'
                    ];
                    return $this->successResponse($data);
                }
            } else {

                //si no existe la tarea en ninguna zona entonces.
                $condi_proy_zona = Proyectozona::where('varDescrip', $request->input('varDescrip'))
                        ->where('intIdProy', $request->input('intIdProy'))
                        ->where('intIdTipoProducto', $request->input('intIdTipoProducto'))
                        ->first(['intIdProyZona', 'intIdProy', 'intIdTipoProducto', 'varDescrip']);

                if (!isset($condi_proy_zona)) {
                    date_default_timezone_set('America/Lima'); // CDT
                    $crea_proy_zona = Proyectozona::create([
                                'varDescrip' => $request->input('varDescrip'),
                                'intIdProy' => $request->input('intIdProy'),
                                'varCodSubOt' => $request->input('varCodSubOt'),
                                'intIdTipoProducto' => $request->input('intIdTipoProducto'),
                                'acti_usua' => $request->input('acti_usua'),
                                'acti_hora' => $current_date = date('Y/m/d H:i:s'),
                                'varDesSubOt' => $request->input('desProd')
                    ]);
                    $data = [
                        'id' => $crea_proy_zona['intIdProyZona'],
                        'mensaje' => 'Exito.'
                    ];
                    return $this->successResponse($data);
                } else {
                    //Dirigirse a la opcion Actualizar.
                    $data = [
                        'id' => $condi_proy_zona['intIdProyZona'],
                        'mensaje' => 'Exito.'
                    ];
                    return $this->successResponse($data);
                }
            }
        }
    }

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/vali_proy_zona",
     *     tags={"Gestion Proyecto Zona"},
     *     summary="validar si la zona existe, en caso que exista mandara el id  proyecto zona",
     *     @OA\Parameter(
     *         description="Ingresar el id del proyecto",
     *         in="path",
     *         name="intIdProy",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      
      @OA\Parameter(
     *         description="Ingresar el id del tipo producto",
     *         in="path",
     *         name="intIdTipoProducto",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),

      @OA\Parameter(
     *         description="Ingresar la descripcion de la zona",
     *         in="path",
     *         name="varDescrip",
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
     *                     property="intIdProy",
     *                     type="string"
     *                 ) ,
     *                @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
     *               @OA\Property(
     *                     property="varDescrip",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProy": "126","intIdTipoProducto","varDescrip":"19_0021_1"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Exito. Ya que el id proyecto zona no existe."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function vali_proy_zona(Request $request) {

        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'varDescrip' => 'required|max:255',
        ];
        $this->validate($request, $regla);

        $condi_proy_zona = Proyectozona::where('varDescrip', $request->input('varDescrip'))
                ->where('intIdProy', $request->input('intIdProy'))
                ->where('intIdTipoProducto', $request->input('intIdTipoProducto'))
                ->first(['intIdProyZona', 'intIdProy', 'intIdTipoProducto', 'varDescrip']);


        if (isset($condi_proy_zona)) {


            $mensaje = [
                'mensaje' => "error.",
                'data' => $condi_proy_zona['intIdProyZona']
            ];
            return $this->successResponse($mensaje);
        } else {


            //Dirigirse a la opcion Actualizar.
            $mensaje = [
                'mensaje' => "Exito."
            ];
            return $this->successResponse($mensaje);
        }
    }

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/actu_proy_zona",
     *     tags={"Gestion Proyecto Zona"},
     *     summary="modificar los datos de una zona",
      @OA\Parameter(
     *         description="El id de la zona que se desea modificar",
     *         in="path",
     *         name="intIdProyZona",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="ingresar el id del proyecto",
     *         in="path",
     *         name="intIdProy",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="Ingresar el id del tipo producto",
     *         in="path",
     *         name="intIdTipoProducto",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="Ingresar la descripcion",
     *         in="path",
     *         name="varDescrip",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),

      @OA\Parameter(
     *         description="ingresar el codigo usuario a modificado una zona",
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
     *                     property="intIdProyZona",
     *                     type="string"
     *                 ) ,
     *                @OA\Property(
     *                     property="intIdProy",
     *                     type="string"
     *                 ) ,

     *               @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
      @OA\Property(
     *                     property="varDescrip",
     *                     type="string"
     *                 ) ,
      @OA\Property(
     *                     property="usua_modi",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProyZona": "4","intIdProy":"175","intIdTipoProducto":"1","varDescrip":"Modulo_Prueba","usua_modi":"andy_ancajima"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Actualizacion Satisfactoria."
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description="No encontro el id para Actualizar."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
//(FALTA DOCUMENTAR )
    public function actu_proy_zona(Request $request) {

        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'varDescrip' => 'required|max:255',
            'usua_modi' => 'required|max:255',
        ];
        $this->validate($request, $regla);


        $condi_proy_zona = Proyectozona::where('intIdProyZona', $request->input('intIdProyZona'))->first(['intIdProyZona']);

        if ($condi_proy_zona['intIdProyZona'] == ($request->input('intIdProyZona'))) {
            date_default_timezone_set('America/Lima');
            $crear_proy_zona = Proyectozona::where('intIdProyZona', $request->input('intIdProyZona'))->update([
                'intIdProy' => $request->input('intIdProy'),
                'intIdTipoProducto' => $request->input('intIdTipoProducto'),
                'varDescrip' => $request->input('varDescrip'),
                'usua_modi' => $request->input('usua_modi'),
                'hora_modi' => $current_date = date('Y/m/d H:i:s')
            ]);
            $mensaje = [
                'mensaje' => 'Actualizacion Satisfactoria.'
            ];
            return $this->successResponse($mensaje);
        } else {
            $mensaje = [
                'mensaje' => 'No encontro el id para Actualizar.'
            ];

            return $this->successResponse($mensaje);
        }
    }

    public function obtener_sub_ot(Request $request) {
        $regla = ['intIdProy.required' => 'EL Campo Fecha Inicio es obligatorio',
            'varDescrip.required' => 'EL Campo Fecha Inicio es obligatorio',
            'varCodiProy.required' => 'EL Campo Fecha Inicio es obligatorio',
            'intIdTipoProducto.required' => 'EL Campo Fecha Inicio es obligatorio',
            'varCodSubOt.required' => 'EL Campo Fecha Fin es obligatorio'];
        $validator = Validator::make($request->all(), ['varCodiProy' => 'required|max:255',
                    'varDescrip' => 'required|max:255',
                    'intIdProy' => 'required|max:255',
                    'intIdTipoProducto' => 'required|max:255',
                    'varCodSubOt' => 'required|max:255'], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            $condi_proy_zona = Proyectozona::where('varDescrip', $request->input('varDescrip'))
                    ->where('intIdProy', $request->input('intIdProy'))
                    ->where('intIdTipoProducto', $request->input('intIdTipoProducto'))
                    ->first(['intIdProyZona', 'intIdProy', 'intIdTipoProducto', 'varDescrip', 'varCodSubOt','varDesSubOt']);


            if (isset($condi_proy_zona)) {
                if ($condi_proy_zona['varCodSubOt']) {
                    if ($condi_proy_zona['varCodSubOt'] === $request->varCodSubOt) {
                        $mensaje = [
                            'mensaje' => 'Exito.',
                            'desProd' => $condi_proy_zona['varDesSubOt'] 
                        ];
                        return $this->successResponse($mensaje);
                    } else {
                        $mensaje = [
                            'mensaje' => 'La sub ot asignada a la zona es diferente a la del partlist.'
                        ];
                        return $this->successResponse($mensaje);
                    }
                } else {
                    $mensaje = [
                        'mensaje' => 'No existe la sub ot en la zona ' . $condi_proy_zona['intIdProyZona']
                    ];
                    return $this->successResponse($mensaje);
                }
            } else {
                $datos_zona = [
                    'varCodiProy' => $request->varCodiProy,
                    'varCodSubOt' => $request->varCodSubOt
                ];
                /* MEDIANTE EL PROCEDIMIENTO DEL CURL ENVIAREMOS Y RECIBIREMOS UN MENSAJE Y UN ID DE REPUESTA Y REGISTRAMOS LOS DATOS */
                $ch6 = curl_init('https://mimcoapps.mimco.com.pe/Sincronizacion/public/index.php/sub_ot');
                curl_setopt($ch6, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch6, CURLOPT_POSTFIELDS, $datos_zona);
                $zona_insert = curl_exec($ch6);
                curl_close($ch6);
                $array_zona_insert = json_decode($zona_insert, true);
                $sub_ot = $array_zona_insert['data']['mensaje'];
                if ($sub_ot === "Exito.") {
                    $mensaje = [
                        'mensaje' => 'Exito.',
                        'desProd' => $array_zona_insert['data']['desProd']
                    ];
                    return $this->successResponse($mensaje);
                } else {
                    $mensaje = [
                        'mensaje' => 'La sub ot ingresada no existe, verificar en el ERP MIMCO'
                    ];
                    return $this->successResponse($mensaje);
                }
            }
        }
    }

}
