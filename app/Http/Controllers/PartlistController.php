<?php

namespace App\Http\Controllers;

use App\Proyectopaquete;
use App\Partlist;
use App\Proyecto;
use App\Proyectotarea;
use App\Etapa;
use App\Elemento;
use App\ProyectoAvance;
use App\AsignarEtapaProyecto;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

set_time_limit(0);

class PartlistController extends Controller {

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
     * @OA\Info(title="Gestion PartList", version="1",  
     * @OA\Contact(
     *     email="antony.rodriguez@mimco.com.pe"
     *   )
     * )
     */
    // se lista el partlist
    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/list_partlist",
     *     tags={"Gestion PartList"},
     *     summary="Listar el PartList",
     *     @OA\Parameter(
     *         description="seleccionar la ot",
     *         in="path",
     *         name="intIdProy",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      
     * *     @OA\Parameter(
     *         description="seleccionar el idTipoProducto",
     *         in="path",
     *         name="intIdTipoProducto",
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
     *                 example={"intIdProy": "126","intIdTipoProducto":"1"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Muestra un mensaje de error que existe ese codigo elemento."
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description="El el idtipoetapa ingresado no se encuentra registrado."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function list_partlist(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255'
        ];
        $this->validate($request, $regla);

        $validar = DB::table('partlist')
                        ->join('proyecto', 'proyecto.intIdProy', '=', 'partlist.intIdProy')
                        ->join('tipo_producto', 'tipo_producto.intIdTipoProducto', '=', 'partlist.intIdTipoProducto')
                        ->where('proyecto.intIdProy', $request->input('intIdProy'))
                        ->where('partlist.intIdTipoProducto', $request->input('intIdTipoProducto'))
                        ->select(
                                'partlist.intIdProy', 'partlist.varDescripcion', 'partlist.varArchivo', 'partlist.boolNuevo', 'partlist.boolActu', DB::raw('(CASE WHEN partlist.vartipoDocu = "TP" THEN "PARTLIST" ELSE "COMPONENTE" END) AS vartipoDocu'), 'partlist.acti_usua', 'partlist.acti_hora'
                        )->get();
        return $this->successResponse($validar);
    }

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/regis_partlist",
     *     tags={"Gestion PartList"},
     *     summary="Registrar PartList",
     *     @OA\Parameter(
     *         description="seleccionar la ot",
     *         in="path",
     *         name="intIdProy",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      
     * *     @OA\Parameter(
     *         description="seleccionar el idTipoProducto",
     *         in="path",
     *         name="intIdTipoProducto",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     
     * * *     @OA\Parameter(
     *         description="seleccionar el descripcion de partlist",
     *         in="path",
     *         name="varDescripcion",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *      @OA\Parameter(
     *         description="seleccionar el archivo",
     *         in="path",
     *         name="varArchivo",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="seleccionar bool nuevo",
     *         in="path",
     *         name="boolNuevo",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      *     @OA\Parameter(
     *         description="seleccionar boolActu",
     *         in="path",
     *         name="boolActu",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *       @OA\Parameter(
     *         description="seleccionar Tipo de documento",
     *         in="path",
     *         name="vartipoDocu",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *  @OA\Parameter(
     *         description="Ingresar el nombre del usuario que va registrar",
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
     *                     property="intIdProy",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
     *                     @OA\Property(
     *                     property="varDescripcion",
     *                     type="string"
     *                 ) ,
     *              @OA\Property(
     *                     property="varArchivo",
     *                     type="string"
     *                 ) ,
     *    *              @OA\Property(
     *                     property="boolNuevo",
     *                     type="string"
     *                 ) ,
     *              @OA\Property(
     *                     property="boolActu",
     *                     type="string"
     *                 ) ,
     *             @OA\Property(
     *                     property="vartipoDocu",
     *                     type="string"
     *                 ) ,
     *          @OA\Property(
     *                     property="acti_usua",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProy": "126","intIdTipoProducto":"1","varDescripcion":"PRUEBAMOD","varArchivo":"20000.csv",
     *                          "boolNuevo":"1","boolActul":"0","vartipoDocu":"TP","acti_usua":"usuario"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Muestra un mensaje de error que existe ese codigo elemento."
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description="El el idtipoetapa ingresado no se encuentra registrado."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
// se registra el partlist
    public function regis_partlist(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'varDescripcion' => 'required|max:255',
            'varArchivo' => 'required|max:255',
            'boolNuevo' => 'required|max:255',
            'boolActu' => 'required|max:255',
            'vartipoDocu' => 'required|max:255',
            'acti_usua' => 'required|max:255'
        ];
        $this->validate($request, $regla);


        date_default_timezone_set('America/Lima'); // CDT

        $vali = Partlist::create([
                    'intIdProy' => $request->input('intIdProy'),
                    'intIdTipoProducto' => $request->input('intIdTipoProducto'),
                    'varDescripcion' => $request->input('varDescripcion'),
                    'varArchivo' => $request->input('varArchivo'),
                    'boolNuevo' => $request->input('boolNuevo'),
                    'boolActu' => $request->input('boolActu'),
                    'vartipoDocu' => $request->input('vartipoDocu'),
                    'acti_usua' => $request->input('acti_usua'),
                    'acti_hora' => $current_date = date('Y/m/d H:i:s')
        ]);

        $mensaje = [
            'mensaje' => "Guardado con exito.",
            'id' => $vali['intIdPartList']
        ];
        return $this->successResponse($mensaje);
    }

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/actu_partlist",
     *     tags={"Gestion PartList"},
     *     summary="Actualizar el PartList",
     *     @OA\Parameter(
     *         description="seleccionar el id de partlist que va actualizar",
     *         in="path",
     *         name="intIdPartList",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     
     *  @OA\Parameter(
     *         description="seleccionar el archivo",
     *         in="path",
     *         name="varArchivo",
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
     *                     property="intIdPartList",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="varArchivo",
     *                     type="string"
     *                 ) ,
     *                 
     *                 example={"intIdPartList":"10","varArchivo":"PRUEBAMIMCO"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Carga de Partlist Satisfactoria."
     *     ),
     *     
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function actu_partlist(Request $request) {
        $regla = [
            'intIdPartList' => 'required|max:255',
            'varArchivo' => 'required|max:255'
        ];

        $this->validate($request, $regla);

        $actu_partlist = Partlist::where('intIdPartList', $request->input('intIdPartList'))
                ->update([
            'varArchivo' => $request->input('varArchivo')
        ]);
        $mensaje = [
            'mensaje' => "Carga de Partlist Satisfactoria.",
            'id' => $request->input('varArchivo')
        ];

        return $this->successResponse($mensaje);
    }

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/vali_nomb_part_list",
     *     tags={"Gestion PartList"},
     *     summary="validar el nombre del partlist",
     *     @OA\Parameter(
     *         description="Ingresar el nombre que se ha asignar al partlist",
     *         in="path",
     *         name="varDescripcion",
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
     *                     property="varDescripcion",
     *                     type="string"
     *                 ) ,
     *                
     *                 example={"varDescripcion":"PRUEBA MIMCO VALIDAR"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="si el nombre no existe en la base de datos entonces mostrar un mensaje de exito"
     *     ),
     *     
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
// VALIDAR PARLIST NOMBRE QUE NO SE REPITA 
    public function vali_nomb_part_list(Request $request) {
        $regla = [
            'varDescripcion' => 'required|max:255',
        ];
        $this->validate($request, $regla);

        $vali_nombr_part_list = Partlist::where('varDescripcion', $request->input('varDescripcion'))
                ->first(['varDescripcion']);

        if ($vali_nombr_part_list['varDescripcion'] != $request->input('varDescripcion')) {
            $mensaje = [
                'mensaje' => 'Exito.'
            ];

            return $this->successResponse($mensaje);
        } else {
            $mensaje = [
                'mensaje' => 'ERROR EL NOMBRE DEL ARCHIVO YA EXISTE.'
            ];
            return $this->successResponse($mensaje);
        }
    }

/// ********************************REPORTE******************************************

    /**
     * @OA\Get(
     *     path="/GestionPartList/public/index.php/List_proy_vige",
     *     tags={"Gestion PartList"},
     *     summary="Listar los proyecto vigentes",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Listar los proyecto vigentes"
     *     )
     * )
     */
    //listar proyecto vigentes//
    public function List_proy_vige() {
        $result = DB::select('CALL sp_proyectos_Q01(?)', array(1));

        return $this->successResponse($result);
        // $result = DB::select("SELECT * FROM etapa where varEstaEtap = 'ACT' ". $query);
    }

    public function List_proy(Request $request) {
        $regla = [
            'tipo_ot' => 'required|max:255',
        ];
        $this->validate($request, $regla);
        $tipo_ot = (int) $request->input('tipo_ot');
        $result = DB::select('CALL sp_proyectos_Q01(?)', array($tipo_ot));

        return $this->successResponse($result);
        // $result = DB::select("SELECT * FROM etapa where varEstaEtap = 'ACT' ". $query);
    }

    //listar proyecto vigente de 6 meses 

    /**
     * @OA\Get(
     *     path="/GestionPartList/public/index.php/list_proy_vige_6_mese",
     *     tags={"Gestion PartList"},
     *     summary="Listar los proyecto vigentes durante los 6 ultimos meses",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Listar los proyecto vigentes durante los 6 ultimos meses"
     *     )
     * )
     */
    public function list_proy_vige_6_mese() {
        date_default_timezone_set('America/Lima');
        $fechaactual = "";
        $fecha_ante_6_meses = "";
        $fechaactual = date("Y-m-d");


        $fecha_ante_6_meses = date("Y-m-d", strtotime($fechaactual . "- 6 month"));


        //  dd("hace 6 meses: ".$fecha_ante_6_meses."| Fecha Actual: ".$fechaactual);


        $reservations = Proyecto::whereBetween('dateFechInic', array($fecha_ante_6_meses, $fechaactual))
                        ->select('intIdProy', 'varCodiOt', 'intIdUniNego', 'varCodiProy', 'dateFechInic')->get();



        return $this->successResponse($reservations);
    }

    /**
     * @OA\Get(
     *     path="/GestionPartList/public/index.php/List_OT",
     *     tags={"Gestion PartList"},
     *     summary="Listar todas las OT",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Listar todas las OT"
     *     )
     * )
     */
    //listar la OT ()
    public function List_OT() {

        $list_OT = Proyecto::where('intIdEsta', '=', 3)
                        ->select('intIdProy', 'varCodiProy')->get();

        return $this->successResponse($list_OT);
    }

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/List_Etap_actu",
     *     tags={"Gestion PartList"},
     *     summary="Listar dependiendo a lo que se escoge en el select tipo Etapa",
     *     @OA\Parameter(
     *         description="seleccionar el idtipoetapa",
     *         in="path",
     *         name="intIdTipoEtap",
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
     *                     property="intIdTipoEtap",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdTipoEtap": "3"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sin Mensaje"
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description="El el idtipoetapa ingresado no se encuentra registrado."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    //•	Etapa Actual es dependiente (Listar dependiendo a lo que se escoge en el select tipo Etapa) 
    public function List_Etap_actu(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'intIdTipoEtap' => 'required|max:255'
        ];
        $this->validate($request, $regla);

        $intIdProy = $request->input('intIdProy');
        $intIdTipoProducto = $request->input('intIdTipoProducto');
        $intIdTipoEtap = (int) $request->input('intIdTipoEtap');

        // dd($intIdTipoEtap);
        if ($intIdTipoEtap === -1) {

            $mostr_todo = AsignarEtapaProyecto::join('etapa', 'asig_etap_proy.intIdEtapa', '=', 'etapa.intIdEtapa')
                            ->where('asig_etap_proy.intIdProy', '=', $intIdProy)
                            ->where('asig_etap_proy.intIdTipoProducto', '=', $intIdTipoProducto)
                            ->select('etapa.intIdEtapa', 'etapa.varDescEtap', 'asig_etap_proy.intOrden')
                            ->orderBy('asig_etap_proy.intOrden', 'desc')->get();

            $dato_todo = ['intIdEtapa' => -1, 'varDescEtap' => 'TODOS'];
            $mostr_todo->push($dato_todo);
            return $this->successResponse($mostr_todo);
        } else {

            $mostr_todo = AsignarEtapaProyecto::join('etapa', 'asig_etap_proy.intIdEtapa', '=', 'etapa.intIdEtapa')
                            ->rightJoin('tipoetapa', 'etapa.intIdTipoEtap', '=', 'tipoetapa.intIdTipoEtap')
                            ->where('asig_etap_proy.intIdProy', '=', $intIdProy)
                            ->where('asig_etap_proy.intIdTipoProducto', '=', $intIdTipoProducto)
                            ->where('tipoetapa.intIdTipoEtap', '=', $intIdTipoEtap)
                            ->select('etapa.intIdEtapa', 'etapa.varDescEtap', 'asig_etap_proy.intOrden')->orderBy('asig_etap_proy.intOrden', 'desc')->get();
            $dato_todo = ['intIdEtapa' => -1, 'varDescEtap' => 'TODOS'];
            $mostr_todo->push($dato_todo);
            return $this->successResponse($mostr_todo);
        }


        /*
          if ($request->input('intIdTipoEtap') > 0) {


          $list_etap_actu = Etapa::where('intIdTipoEtap', $request->input('intIdTipoEtap'))
          ->where('varEstaEtap', '=', 'ACT')
          ->select('intIdEtapa', 'varDescEtap')->get();

          if (count($list_etap_actu) == 0) {
          $mensaje = [
          'mensaje' => 'error'
          ];

          return $this->successResponse($mensaje);
          } else {
          $dato_todo = ['intIdEtapa' => -1, 'varDescEtap' => 'TODOS'];
          $list_etap_actu->push($dato_todo);

          return $this->successResponse($list_etap_actu);
          }
          } else {
          //SI ES -1
          //LISTAMOS TODAS LAS ETAPAS

          $list_etap_actu = Etapa::where('varEstaEtap', '=', 'ACT')
          ->select('intIdEtapa', 'varDescEtap')->get();

          $dato_todo = ['intIdEtapa' => -1, 'varDescEtap' => 'TODOS'];
          $list_etap_actu->push($dato_todo);
          return $this->successResponse($list_etap_actu);
          } */
    }

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/List_tarea",
     *     tags={"Gestion PartList"},
     *     summary="Listar las Tareas dependiendo el codigo de Proyecto",
     *     @OA\Parameter(
     *         description="seleccione la ot",
     *         in="path",
     *         name="intIdProy",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *    @OA\Parameter(
     *         description="seleccionar el id tipo producto",
     *         in="path",
     *         name="intIdTipoProducto",
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
     *              @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProy": "126","intIdTipoProducto": "1"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="mostrar la tarea"
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description="El el intIdProy y  intIdTipoProducto no se encuentra registrados para esta tarea"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    //Listar las Tareas dependiendo el codigo de Proyecto ()
    public function List_tarea(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        // $list_pro=Proyecto::where('varCodiProy',$request->input('varCodiProy'))->first(['intIdProy']);



        $list_tarea = Proyectotarea::where('intIdProy', $request->input('intIdProy'))
                        ->where('intIdTipoProducto', $request->input('intIdTipoProducto'))
                        ->select('intIdProyTarea', 'varDescripTarea')->get();
        //ie($list_tarea);
        if (count($list_tarea) == 0) {

            //die("add"); 
            $mensaje = [
                'mensaje' => 'error'
            ];

            return $this->successResponse($mensaje);
        } else {

            $dato_todo = ['intIdProyTarea' => -1, 'varDescripTarea' => 'TODOS'];
            $list_tarea->push($dato_todo);

            return $this->successResponse($list_tarea);
        }
    }

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/List_paqu",
     *     tags={"Gestion PartList"},
     *     summary="Listar los Paquetes dependiendo el codigo de la tarea y proyecto.",
     * 
     *     @OA\Parameter(
     *         description="seleccione la ot",
     *         in="path",
     *         name="intIdProy",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *    @OA\Parameter(
     *         description="seleccione la ot",
     *         in="path",
     *         name="intIdProy",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *    @OA\Parameter(
     *         description="seleccionar el id tipo producto",
     *         in="path",
     *         name="intIdTipoProducto",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *  @OA\Parameter(
     *         description="seleccionar el id proyecto tarea",
     *         in="path",
     *         name="intIdProyTarea",
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
     *              @OA\Property(
     *                     property="intIdProyTarea",
     *                     type="string"
     *                 ) ,  
     *                  @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProy": "126","intIdProyTarea": "1","intIdTipoProducto":"1"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="mostrar la paquete de acuerdo a la OT,tipoproducto y  el idproyectoTarea"
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description="El el intIdProy , intIdProyTarea,intIdTipoProductointIdTipoProducto no se encuentra registrados para esta paquete"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    //•	Listar los Paquetes dependiendo el codigo de la tarea y proyecto.
    public function List_paqu(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdProyTarea' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdProy = $request->input('intIdProy');
        $intIdProyTarea = trim($request->input('intIdProyTarea'), ',');
        $intIdTipoProducto = $request->input('intIdTipoProducto');

        if ($intIdProyTarea == "-1") {
            $result = DB::select("SELECT intIdProyPaquete,varCodigoPaquete FROM proyecto_paquetes where intIdProy = '$intIdProy' and intIdTipoProducto= '$intIdTipoProducto'");
        } else {
            $result = DB::select("SELECT intIdProyPaquete,varCodigoPaquete FROM proyecto_paquetes where intIdProy = '$intIdProy' and intIdTipoProducto= '$intIdTipoProducto'  and intIdProyTarea in ( " . $intIdProyTarea . " )");
        }

        return $this->successResponse($result);
    }

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/anua_seri",
     *     tags={"Gestion PartList"},
     *     summary="Poder anular una serie.",
     *     @OA\Parameter(
     *         description="seleccionar el id Elemento",
     *         in="path",
     *         name="intIdEleme",
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
     *                     property="intIdEleme",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdEleme": "3"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Se ha eliminado."
     *     ),
     *    
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    //•	Poder anular una serie. Idproyecto, idproducto, serie y marca. Cambiar a estado anulado
    //per_anua_seri
    public function anua_seri(Request $request) {
        $regla = [
            'intIdEleme' => 'required'
        ];
        $this->validate($request, $regla);


        $Obte_idEle = $request->input('intIdEleme');

        //  die("".$Obte_idEle);     

        for ($i = 0; $i < count($Obte_idEle); $i++) {
            //1 die("".$Obte_idEle[$i]['intIdEleme']);
            $pode_anul = Elemento::where('intIdEleme', '=', $Obte_idEle[$i])
                    ->update([
                'intIdEsta' => 6
            ]);
        }
        $mensaje = [
            'mensaje' => 'Exito.'
        ];

        return $this->successResponse($mensaje);
    }

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/list_codi_elem",
     *     tags={"Gestion PartList"},
     *     summary="Listar los Codigo dependiendo del paquete y codigo de proyecto.",
     *     @OA\Parameter(
     *         description="Seleccionar la OT",
     *         in="path",
     *         name="intIdProy",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *    @OA\Parameter(
     *         description="Seleccionar id Proyecto paquete",
     *         in="path",
     *         name="intIdProyPaquete",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     * @OA\Parameter(
     *         description="Seleccionar el tipo producto.",
     *         in="path",
     *         name="intIdTipoProducto",
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
     *                  @OA\Property(
     *                     property="intIdProyPaquete",
     *                     type="string"
     *                 ) ,
     *                   @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProy": "3", "intIdProyPaquete":"2","intIdTipoProducto":"1"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="mostrar lista de codigo de Elemento."
     *     ),
     *  @OA\Response(
     *         response=407,
     *         description="No se encuentra registrado."
     *     ),
     *    
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    //Listar los Codigo dependiendo del paquete y codigo de proyecto.
    //(CODIGO ES LOS ELEMENTO) 
    public function list_codi_elem(Request $request) {

        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdProyPaquete' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255'
                //'intIdProyTarea'=>'required|max:255'
        ];
        $this->validate($request, $regla);

        $intIdProy = $request->input('intIdProy');
        $intIdProyPaquete = trim($request->input('intIdProyPaquete'), ',');
        $intIdTipoProducto = $request->input('intIdTipoProducto');

        if ($intIdProyPaquete == "-1") {
            $result = DB::select("SELECT varCodiElemento FROM elemento where intIdProy = '$intIdProy' and intIdTipoProducto= '$intIdTipoProducto'  GROUP BY varCodiElemento ORDER BY varCodiElemento DESC");

            $dato_todo = ['varCodiElemento' => 'TODOS'];
            array_push($result, $dato_todo);
        } else {
            $result = DB::select("SELECT varCodiElemento FROM elemento where intIdProy = '$intIdProy' and intIdTipoProducto= '$intIdTipoProducto'  and intIdProyPaquete in ( " . $intIdProyPaquete . " ) GROUP BY varCodiElemento ORDER BY varCodiElemento DESC");

            $dato_todo = ['varCodiElemento' => 'TODOS'];
            array_push($result, $dato_todo);
        }


        return $this->successResponse($result);
    }

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/list_repo_elem",
     *     tags={"Gestion PartList"},
     *     summary="mostrar reporte de acuerdo a los parametros",
     *     @OA\Parameter(
     *         description="Seleccionar la OT",
     *         in="path",
     *         name="intIdProy",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * @OA\Parameter(
     *         description="Seleccionar el tipo producto",
     *         in="path",
     *         name="intIdTipoProducto",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * @OA\Parameter(
     *         description="Seleccionar id Tipo etapa",
     *         in="path",
     *         name="intIdTipoEtap",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *   @OA\Parameter(
     *         description="Seleccionar id  etapa",
     *         in="path",
     *         name="intIdEtapa",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *       
     *   @OA\Parameter(
     *         description="Seleccionar id proyecto tarea",
     *         in="path",
     *         name="intIdProyTarea",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *     *   @OA\Parameter(
     *         description="Seleccionar id proyecto paquete",
     *         in="path",
     *         name="intIdProyPaquete",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *           @OA\Parameter(
     *         description="Seleccionar elemento",
     *         in="path",
     *         name="varCodiElemento",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     * 
     *          @OA\Parameter(
     *         description="Seleccionar Codigo elemento",
     *         in="path",
     *         name="TipoReporte",
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
     *                  @OA\Property(
     *                     property="varNumeDni",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProy": "126","intIdTipoProducto":"1","intIdTipoEtap":"-1" ,"intIdEtapa":"1" ,"intIdProyTarea":"3",
     *                         "intIdProyPaquete":"2","varCodiElemento":"4","TipoReporte":"-1"  }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listar en la grilla"
     *     ),
     *     
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    // MOSTRAR LISTAR DE REPORTE sp_elementos_Q01
    public function list_repo_elem(Request $request) {


        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'intIdTipoEtap' => 'required|max:255',
            'intIdEtapa' => 'required|max:255',
            'intIdProyTarea' => 'required|max:255', /*             * '-1' es todos o concatenado de ids '1,2,3' * */
            'intIdProyPaquete' => 'required|max:255', /*             * '-1' es todos o concatenado de ids '1,2,3' * */
            'varCodiElemento' => 'required|max:255', /*             * '-1' es todos o concatenado de ids '1,2,3' * */
            'TipoReporte' => 'required|max:255',
            'v_estado' => 'required|max:255'
                //'v_RangoFecha' => 'required|max:255',
        ];
        $this->validate($request, $regla);
        $v_intIdproy = (int) $request->input('intIdProy');
        $v_intIdTipoProducto = (int) $request->input('intIdTipoProducto');
        $v_intIdTipoEtapa = (int) $request->input('intIdTipoEtap');
        $v_intIdEtapa = (int) $request->input('intIdEtapa');
        $v_strIdTarea = trim($request->input('intIdProyTarea'), ',');
        $v_strIdPaquete = trim($request->input('intIdProyPaquete'), ',');

        $v_strCodigos = trim($request->input('varCodiElemento'), ',');
        $v_nTipoReporte = $request->input('TipoReporte');
        $v_RangoFecha = (int) $request->input('v_RangoFecha');
        $v_estado = (int) $request->input('v_estado');
        $v_FechaIni = "";
        $v_FechaFin = "";

        if ($request->input('v_FechaIni') == "") {
            $v_FechaIni = null;
        } else {
            $v_FechaIni = $request->input('v_FechaIni');
        }

        if ($request->input('v_FechaFin') == "") {
            $v_FechaFin = null;
        } else {
            $v_FechaFin = $request->input('v_FechaFin');
        }
        /* dd($v_intIdproy,
          $v_intIdTipoProducto,
          $v_intIdTipoEtapa,
          $v_intIdEtapa,
          $v_strIdTarea,
          $v_strIdPaquete,
          $v_strCodigos,
          $v_nTipoReporte,
          $v_RangoFecha,
          $v_FechaIni,
          $v_FechaFin,
          $v_estado); */

        $result = DB::select("CALL sp_elementos_Q01(?,?,?,?,?,?,?,?,?,?,?,?)", array(
                    $v_intIdproy,
                    $v_intIdTipoProducto,
                    $v_intIdTipoEtapa,
                    $v_intIdEtapa,
                    $v_strIdTarea,
                    $v_strIdPaquete,
                    $v_strCodigos,
                    $v_nTipoReporte,
                    $v_RangoFecha,
                    $v_FechaIni,
                    $v_FechaFin,
                    $v_estado
        ));
        return $this->successResponse($result);
    }

    /*   •	Al momento de dar clic en cantidad, mostrar una grilla con la información de las series 
      que forman ese elemento y cantidad.
      Serie, codigo, nombre, fecha de modificación. Datos importantes idproyecto, idproducto, marca.
     */

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/most_cant_info",
     *     tags={"Gestion PartList"},
     *     summary="ostrar una grilla con la información de las series que forman ese elemento y cantidad.",
     *     @OA\Parameter(
     *         description="seleccionar una OT",
     *         in="path",
     *         name="intIdProy",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *    @OA\Parameter(
     *         description="Seleccion de un tipo producto",
     *         in="path",
     *         name="intIdTipoProducto",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *        @OA\Parameter(
     *         description="Seleccion un var elemento",
     *         in="path",
     *         name="varCodiElemento",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *        @OA\Parameter(
     *         description="Seleccion un  id Proyecto zona",
     *         in="path",
     *         name="intIdProyZona",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     * ),
     *     
     *        @OA\Parameter(
     *         description="ingresar la revision",
     *         in="path",
     *         name="intRevision",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *      @OA\Parameter(
     *         description="ingresar la descripcion",
     *         in="path",
     *         name="varDescripcion",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     * *      @OA\Parameter(
     *         description="ingresar Peso neto",
     *         in="path",
     *         name="deciPesoNeto",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),

     *           @OA\Parameter(
     *         description="Ingrese el area",
     *         in="path",
     *         name="deciArea",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *        
     *         @OA\Parameter(
     *         description="Ingrese la longuitud",
     *         in="path",
     *         name="deciLong",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *   
     *          @OA\Parameter(
     *         description="Ingrese el perfil",
     *         in="path",
     *         name="varPerfil",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *    
     *   @OA\Parameter(
     *         description="Ingrese el codigo valor",
     *         in="path",
     *         name="varCodVal",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingrese el IdProyTarea",
     *         in="path",
     *         name="intIdProyTarea",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *   @OA\Parameter(
     *         description="Ingrese el intIdProyPaquete",
     *         in="path",
     *         name="intIdProyPaquete",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *   @OA\Parameter(
     *         description="Ingrese el intIdEsta",
     *         in="path",
     *         name="intIdEsta",
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
     *                 @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
     *                  @OA\Property(
     *                     property="varCodiElemento",
     *                     type="string"
     *                 ) ,
     *                   @OA\Property(
     *                     property="intIdProyZona",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="intRevision",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="varDescripcion",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="deciPesoNeto",
     *                     type="string"
     *                 ) ,
     *                   @OA\Property(
     *                     property="deciArea",
     *                     type="string"
     *                 ) ,
     *                  @OA\Property(
     *                     property="deciLong",
     *                     type="string"
     *                 ) ,
     *              @OA\Property(
     *                     property="varPerfil",
     *                     type="string"
     *                 ) ,
     *                    @OA\Property(
     *                     property="varCodVal",
     *                     type="string"
     *                 ) ,
     *                       @OA\Property(
     *                     property="intIdProyTarea",
     *                     type="string"
     *                 ) ,
     *                      @OA\Property(
     *                     property="intIdProyPaquete",
     *                     type="string"
     *                 ) ,
     *                   @OA\Property(
     *                     property="intIdEsta",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProy": "126","intIdTipoProducto":"1","varCodiElemento":"C1_14","intIdProyZona"="12","intRevision":"3",
     *                 "varDescripcion":"PRUEBA","deciPesoNeto":"9.710","deciArea":"1.190","deciLong":"3014.000", "varPerfil":"U-100X50X2",
     *                 "varCodVal":"LFW","intIdProyTarea":"1","intIdProyPaquete":"1","intIdEsta":"2"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="muestra la cantidad de ese elemento"
     *     ),
     *     
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function most_cant_info(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'varCodiElemento' => 'required|max:255',
            'intIdProyZona' => 'required|max:255',
            'intRevision' => 'required|max:255',
            'varDescripcion' => 'required|max:255',
            'deciPesoNeto' => 'required|max:255',
            'deciArea' => 'required|max:255',
            'deciLong' => 'required|max:255',
            'varPerfil' => 'required|max:255',
            'varCodVal' => 'required|max:255',
            'intIdProyTarea' => 'required|max:255',
            'intIdProyPaquete' => 'required|max:255',
            'intIdEsta' => 'required|max:255',
            //'varValo1'=>'required|max:255', // pintura si me lo envias esta como pintura
            'deciPesoBruto' => 'required|max:255',
            'deciPesoContr' => 'required|max:255',
            'varModelo' => 'required|max:255',
            // 'intIdRuta' => 'required|max:255',
            'intCantRepro' => 'required|max:255',
                // 'DocEnvioTS'=>'required|max:255'
        ];
        $this->validate($request, $regla);
        $intCantRepro = "";
        $deciPrec = "";
        $intIdEtapa = "";
        $intIdEtapaAnte = "";
        $intIdEtapaSiguiente = "";
        $varValo1 = "";
        $DocEnvioTS = "";
        $nume_guia = "";
        $idlotepintura = "";

        if ($request->input('intCantRepro') == 0) {
            $intCantRepro = 0;
        } else {
            $intCantRepro = $request->input('intCantRepro');
        }


        if ($request->input('intIdEtapa') == 0) {
            $intIdEtapa = 0;
        } else {
            $intIdEtapa = (int) $request->input('intIdEtapa');
        }

        if ($request->input('intIdEtapaAnte') == 0) {


            $intIdEtapaAnte = 0;
        }
        if ($request->input('intIdEtapaAnte') == null || $request->input('intIdEtapaAnte') == "") {

            $intIdEtapaAnte = null;
        } else {

            $intIdEtapaAnte = (int) $request->input('intIdEtapaAnte');
        }
        if ($request->input('intIdLotePintura') == null || $request->input('intIdLotePintura') == "") {

            $idlotepintura = null;
        } else {

            $idlotepintura = (int) $request->input('intIdLotePintura');
        }


        if ($request->input('intIdEtapaSiguiente') == 0) {
            $intIdEtapaSiguiente = 0;
        } else {
            $intIdEtapaSiguiente = (int) $request->input('intIdEtapaSiguiente');
        }


        $intIdRuta = (int) $request->input('intIdRuta');
        //PINTURA
        if ($request->input('varValo1') == null || $request->input('varValo1') == "") {
            $varValo1 = '';
        } else {
            $varValo1 = trim($request->input('varValo1'));
        }


        if ($request->input('DocEnvioTS') == null || $request->input('DocEnvioTS') == "") {
            $DocEnvioTS = '';
        } else {
            $DocEnvioTS = trim($request->input('DocEnvioTS'));
        }



        if ($request->input('Obs1') == null || $request->input('Obs1') == "") {
            $varValo2 = '';
        } else {
            $varValo2 = trim($request->input('Obs1'));
        }
        if ($request->input('obs2') == null || $request->input('obs2') == "") {
            $varValo3 = '';
        } else {
            $varValo3 = trim($request->input('obs2'));
        }
        if ($request->input('obs3') == null || $request->input('obs3') == "") {
            $varValo4 = '';
        } else {
            $varValo4 = trim($request->input('obs3'));
        }
        if ($request->input('obs4') == null || $request->input('obs4') == "") {
            $varValo5 = '';
        } else {
            $varValo5 = trim($request->input('obs4'));
        }

        /* if ($request->input('bultos') == null || $request->input('bultos') == "") {
          $varBulto = '';
          } else {
          $varBulto = $request->input('bultos');
          } */

        /* if ($request->input('nume_guia') == null || $request->input('nume_guia') == "") {
          $nume_guia = '';
          } else {
          $nume_guia = $request->input('nume_guia');
          } */



        // dd($DocEnvioTS);




        $intIdProy = (int) $request->input('intIdProy');
        $intIdTipoProducto = (int) $request->input('intIdTipoProducto');
        $varCodiElemento = $request->input('varCodiElemento');
        $intIdProyZona = (int) $request->input('intIdProyZona');
        $intRevision = (int) $request->input('intRevision');
        $varDescripcion = $request->input('varDescripcion');
        $deciPesoNeto = (float) $request->input('deciPesoNeto');
        $deciArea = (float) $request->input('deciArea');
        //number_format($num, 2)
        $deciLong = (float) $request->input('deciLong');
        $varPerfil = $request->input('varPerfil');
        $varCodVal = $request->input('varCodVal');
        $intIdProyTarea = (int) $request->input('intIdProyTarea');
        $intIdProyPaquete = (int) $request->input('intIdProyPaquete');
        $intIdEsta = (int) $request->input('intIdEsta');
        $deciPesoContr = (float) $request->input('deciPesoContr');
        $deciPesoBruto = (float) $request->input('deciPesoBruto');
        $varModelo = $request->input('varModelo'); //
        //$intIdRuta = $request->input('intIdRuta');
        $intReproceso = (int) $request->input('intReproceso');
        $intRechazo = (int) $request->input('intRechazo');
        $intIdRutaAnt = (int) $request->input('intIdRutaAnt');
        $intCantRepro = (int) $request->input('intCantRepro');
        $varBulto = $request->input('bultos');
        $varValo5 = $request->input('obs4');
        $deciPrec = (float) $request->input('deciPrec');
        $nume_guia = $request->input('nume_guia');
        //$intIdTipoEstru=$request->input('intIdTipoEstru');

        /* dd($intIdProy, $intIdTipoProducto, $varCodiElemento, $intIdProyZona, $intRevision, $varDescripcion, $intCantRepro, $deciPesoNeto, $deciPesoContr, 
          $deciPesoBruto, $deciArea, $deciLong, $varPerfil, $deciPrec, $varCodVal, $intIdEtapa, $intIdEtapaAnte, $intIdEtapaSiguiente, $intIdProyTarea, $intIdProyPaquete, $intIdEsta, $varModelo, $intIdRuta, $varValo1, $DocEnvioTS, $varValo2, $varValo3, $varValo4, $varValo5, $varBulto, $nume_guia
          ); */
        if ($intIdTipoProducto === 1) {


            /* $vali_cant_infor_sere = DB::select('select * from elemento where intIdProy = :intIdProy and intIdTipoProducto=:intIdTipoProducto and '
              . 'varCodiElemento=:varCodiElemento and intIdProyZona=:intIdProyZona and intRevision=:intRevision and '
              . 'varDescripcion=:varDescripcion and intCantRepro=:intCantRepro and deciPesoNeto=:deciPesoNeto and '
              . 'deciPesoContr=:deciPesoContr and deciPesoBruto=:deciPesoBruto and deciArea=:deciArea and deciLong=:deciLong '
              . 'and varPerfil=:varPerfil and deciPrec=:deciPrec and varCodVal=:varCodVal and intIdEtapa=:intIdEtapa and '
              . 'intIdEtapaAnte=:intIdEtapaAnte and intIdEtapaSiguiente=:intIdEtapaSiguiente and intIdProyTarea=:intIdProyTarea and '
              . 'intIdProyPaquete=:intIdProyPaquete and intIdEsta=:intIdEsta and varModelo=:varModelo and intIdRuta=:intIdRuta and '
              . 'numDocTratSup=:numDocTratSup and varValo1=:varValo1 and varValo2=:varValo2 and varValo3=:varValo3 and varValo4=:varValo4 and '
              . 'varValo5=:varValo5 and varBulto=:varBulto and nume_guia=:nume_guia' ,
              ['intIdProy' => $intIdProy, 'intIdTipoProducto' => $intIdTipoProducto, 'varCodiElemento' => $varCodiElemento,
              'intIdProyZona' => $intIdProyZona, 'intRevision' => $intRevision, 'varDescripcion' => $varDescripcion,
              'intCantRepro' => $intCantRepro, 'deciPesoNeto' => $deciPesoNeto, 'deciPesoContr' => $deciPesoContr,
              'deciPesoBruto' => $deciPesoBruto, 'deciArea' => $deciArea, 'deciLong' => $deciLong, 'varPerfil' => $varPerfil,
              'deciPrec' => $deciPrec,'varCodVal'=>$varCodVal,'intIdEtapa'=>$intIdEtapa,'intIdEtapaAnte'=>$intIdEtapaAnte,
              'intIdEtapaSiguiente'=>$intIdEtapaSiguiente,'intIdProyTarea'=>$intIdProyTarea,'intIdProyPaquete'=>$intIdProyPaquete,
              'intIdEsta'=>$intIdEsta,'varModelo'=>$varModelo,'intIdRuta'=>$intIdRuta,'numDocTratSup'=>$DocEnvioTS,'varValo1'=>$varValo1,
              'varValo2'=>$varValo2,'varValo3'=>$varValo3,'varValo4'=>$varValo4,'varValo5'=>$varValo5,'varBulto'=>$varBulto,'nume_guia'=>$nume_guia]); */
            //dd($select_jose);
            /* $select_jose = DB::select('select * from elemento where '
              . 'intIdProy='.$intIdProy.' and intIdTipoProducto='.$intIdTipoProducto.' '
              . 'and varCodiElemento="'.$varCodiElemento.'" and intIdProyZona='.$intIdProyZona.' and intRevision='.$intRevision
              .' and varDescripcion='.$varDescripcion.' and intCantRepro='.$intCantRepro.' and deciPesoNeto='.$deciPesoNeto);
              if ($deciPrec === null) {
              $select_jose = DB::select("select * from elemento where intIdProy=$intIdProy and intIdTipoProducto=$intIdTipoProducto and varCodiElemento='$varCodiElemento' and intIdProyZona=$intIdProyZona and intRevision=$intRevision and varDescripcion='$varDescripcion' and intCantRepro=$intCantRepro and deciPesoNeto=$deciPesoNeto and deciPesoContr=$deciPesoContr and deciPesoBruto=$deciPesoBruto and deciArea=$deciArea and deciLong=$deciLong and varPerfil=$varPerfil and deciPrec=$deciPrec");
              DB::select("SELECT varCodiElemento FROM elemento where intIdProy = '$intIdProy' and intIdTipoProducto= '$intIdTipoProducto'  GROUP BY varCodiElemento ORDER BY varCodiElemento DESC");
              } else {
              $select_jose = DB::select('select * from elemento where intIdProy = :intIdProy and intIdTipoProducto=:intIdTipoProducto and varCodiElemento=:varCodiElemento and intIdProyZona=:intIdProyZona and intRevision=:intRevision and varDescripcion=:varDescripcion and intCantRepro=:intCantRepro and deciPesoNeto=:deciPesoNeto and deciPesoContr=:deciPesoContr and deciPesoBruto=:deciPesoBruto and deciArea=:deciArea and deciLong=:deciLong and varPerfil=:varPerfil and deciPrec=:deciPrec', ['intIdProy' => $intIdProy, 'intIdTipoProducto' => $intIdTipoProducto, 'varCodiElemento' => $varCodiElemento, 'intIdProyZona' => $intIdProyZona, 'intRevision' => $intRevision, 'varDescripcion' => $varDescripcion, 'intCantRepro' => $intCantRepro, 'deciPesoNeto' => $deciPesoNeto, 'deciPesoContr' => $deciPesoContr, 'deciPesoBruto' => $deciPesoBruto, 'deciArea' => $deciArea, 'deciLong' => $deciLong, 'varPerfil' => $varPerfil, 'deciPrec' => $deciPrec]);
              } */
            /* $select_jose = DB::select('select * from elemento where intIdProy = :intIdProy and intIdTipoProducto=:intIdTipoProducto and varCodiElemento=:varCodiElemento and intIdProyZona=:intIdProyZona and intRevision=:intRevision and varDescripcion=:varDescripcion and intCantRepro=:intCantRepro and deciPesoNeto=:deciPesoNeto and deciPesoContr=:deciPesoContr and deciPesoBruto=:deciPesoBruto and deciArea=:deciArea and deciLong=:deciLong and varPerfil=:varPerfil and deciPrec=:deciPrec and varValo1=:varValo1 and intIdEtapa=:intIdEtapa', ['intIdProy' => $intIdProy, 'intIdTipoProducto' => $intIdTipoProducto, 'varCodiElemento' => $varCodiElemento, 'intIdProyZona' => $intIdProyZona, 'intRevision' => $intRevision, 'varDescripcion' => $varDescripcion, 'intCantRepro' => $intCantRepro, 'deciPesoNeto' => $deciPesoNeto, 'deciPesoContr' => $deciPesoContr, 'deciPesoBruto' => $deciPesoBruto, 'deciArea' => $deciArea, 'deciLong' => $deciLong, 'varPerfil' => $varPerfil, 'deciPrec' => $deciPrec,'varValo1'=>$varValo1,'intIdEtapa'=>$intIdEtapa]); */
            /* $select_jose = DB::select("select * from elemento where intIdProy=$intIdProy and intIdTipoProducto=$intIdTipoProducto and varCodiElemento=$varCodiElemento and intIdProyZona=$intIdProyZona and intRevision=$intRevision and varDescripcion=$varDescripcion and intCantRepro=$intCantRepro and deciPesoNeto=$deciPesoNeto and deciPesoContr=$deciPesoContr and deciPesoBruto=$deciPesoBruto and deciArea=$deciArea and deciLong=$deciLong and varPerfil='$varPerfil' and deciPrec=$deciPrec and varCodVal='$varCodVal' and intIdEtapa=$intIdEtapa and intIdEtapaAnte=$intIdEtapaAnte and intIdEtapaSiguiente=$intIdEtapaSiguiente and intIdProyTarea=$intIdProyTarea and intIdProyPaquete=$intIdProyPaquete and intIdEsta=$intIdEsta and varModelo=$varModelo and intIdRuta=$intIdRuta and numDocTratSup=$DocEnvioTS and varValo2=$varValo2 and varValo3=$varValo3 and varValo4=$varValo4 and varValo5=$varValo5 and varBulto=$varBulto and nume_guia=$nume_guia");
              dd($select_jose); */
            $vali_cant_infor_sere = Elemento::join('estado', 'elemento.intIdEsta', '=', 'estado.intIdEsta')
                    ->where('elemento.intIdProy', '=', $intIdProy)
                    ->where('elemento.intIdTipoProducto', '=', $intIdTipoProducto)
                    ->where('elemento.varCodiElemento', '=', $varCodiElemento)
                    ->where('elemento.intIdProyZona', '=', $intIdProyZona)
                    ->where('elemento.intRevision', '=', $intRevision)
                    ->where('elemento.varDescripcion', '=', $varDescripcion)
                    ->where('elemento.intCantRepro', '=', $intCantRepro)//
                    ->where('elemento.deciPesoNeto', '=', $deciPesoNeto)//
                    ->where('elemento.deciPesoContr', '=', $deciPesoContr)//
                    ->where('elemento.deciPesoBruto', '=', $deciPesoBruto)//
                    ->where('elemento.deciArea', '=', $deciArea)
                    ->where('elemento.deciLong', '=', $deciLong)
                    ->where('elemento.varPerfil', '=', $varPerfil)
                    ->where('elemento.deciPrec', '=', $deciPrec)
                    ->where('elemento.varCodVal', '=', $varCodVal)
                    ->where('elemento.intIdEtapa', '=', $intIdEtapa)
                    ->where('elemento.intIdEtapaAnte', '=', $intIdEtapaAnte)
                    ->where('elemento.intIdEtapaSiguiente', '=', $intIdEtapaSiguiente)
                    ->where('elemento.intIdProyTarea', '=', $intIdProyTarea)
                    ->where('elemento.intIdProyPaquete', '=', $intIdProyPaquete)
                    ->where('elemento.intIdEsta', '=', $intIdEsta)
                    ->where('elemento.varModelo', '=', $varModelo)
                    ->where('elemento.intIdRuta', '=', $intIdRuta)
                    ->where('elemento.numDocTratSup', '=', $DocEnvioTS)
                    ->where('elemento.varValo1', '=', $varValo1)
                    ->where('elemento.varValo2', '=', $varValo2)
                    ->where('elemento.varValo3', '=', $varValo3)
                    ->where('elemento.varValo4', '=', $varValo4)
                    ->where('elemento.varValo5', '=', $varValo5)
                    ->where('elemento.varBulto', '=', $varBulto)
                    ->where('elemento.nume_guia', '=', $nume_guia)
                    ->where('elemento.intReproceso', '=', $intReproceso)
                    ->where('elemento.intRechazo', '=', $intRechazo)
                    ->where('elemento.intIdRutaAnt', '=', $intIdRutaAnt)
                    ->where('elemento.intIdLotePintura', '=', $idlotepintura)
                    ->select('elemento.intIdProy', 'elemento.intIdEleme', 'elemento.varCodiElemento', 'elemento.intIdTipoProducto', 'elemento.varDescripcion', 'elemento.intRevision', 'elemento.intSerie', 'elemento.intIdEsta', 'estado.varDescEsta', 'elemento.usua_modi', 'elemento.hora_modi', 'elemento.varCodVal', 'elemento.intIdEtapa')
                    ->get();
            //dd($vali_cant_infor_sere);
        } elseif ($intIdTipoProducto === 2) {
            $vali_cant_infor_sere = Elemento::join('estado', 'elemento.intIdEsta', '=', 'estado.intIdEsta')
                    ->where('elemento.intIdProy', '=', $intIdProy)
                    ->where('elemento.intIdTipoProducto', '=', $intIdTipoProducto)
                    ->where('elemento.varCodiElemento', '=', $varCodiElemento)
                    ->where('elemento.varDescripcion', '=', $varDescripcion)
                    ->where('elemento.intIdProyZona', '=', $intIdProyZona)
                    ->where('elemento.intIdProyTarea', '=', $intIdProyTarea)
                    ->where('elemento.intIdProyPaquete', '=', $intIdProyPaquete)
                    ->where('elemento.intRevision', '=', $intRevision)
                    ->where('elemento.intCantRepro', '=', $intCantRepro)// 
                    ->select('elemento.intIdProy', 'elemento.intIdEleme', 'elemento.varCodiElemento', 'elemento.intIdTipoProducto', 'elemento.varDescripcion', 'elemento.intRevision', 'elemento.intSerie', 'elemento.intIdEsta', 'estado.varDescEsta', 'elemento.usua_modi', 'elemento.hora_modi', 'elemento.varCodVal', 'elemento.intIdEtapa')
                    ->get();
        }

        return $this->successResponse($vali_cant_infor_sere);
    }

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/list_seri",
     *     tags={"Gestion PartList"},
     *     summary="Listar la serie",
     *     @OA\Parameter(
     *         description="Seleccionar el id OT",
     *         in="path",
     *         name="intIdProy",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *    @OA\Parameter(
     *         description="Seleccionar el idtipoproducto",
     *         in="path",
     *         name="intIdTipoProducto",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *    @OA\Parameter(
     *         description="Seleccinamos el codigo elemento",
     *         in="path",
     *         name="varCodiElemento",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *  *    @OA\Parameter(
     *         description="Seleccionar id proyecto zona",
     *         in="path",
     *         name="intIdProyZona",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *  *    @OA\Parameter(
     *         description="Ingresar la revision ",
     *         in="path",
     *         name="intRevision",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *  *  *    @OA\Parameter(
     *         description="ingresar la descripcion",
     *         in="path",
     *         name="varDescripcion",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *   @OA\Parameter(
     *         description="ingresar la serie del elemento",
     *         in="path",
     *         name="intSerie",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *       @OA\Parameter(
     *         description="ingresar peso neto",
     *         in="path",
     *         name="deciPesoNeto",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingresar el Area",
     *         in="path",
     *         name="deciArea",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *    @OA\Parameter(
     *         description="Ingresar la longitud del elemento",
     *         in="path",
     *         name="deciLong",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      *    @OA\Parameter(
     *         description="Ingresar la perfil del elemento",
     *         in="path",
     *         name="varPerfil",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="Ingresar el codigo valor",
     *         in="path",
     *         name="varCodVal",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *         @OA\Parameter(
     *         description="Ingresar la id proyecto tarea",
     *         in="path",
     *         name="intIdProyTarea",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *       @OA\Parameter(
     *         description="Ingresar la id proyecto paquete",
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
     *                     property="intIdProy",
     *                     type="string"
     *                 ) ,
     *                      @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
     *                 
     *                 @OA\Property(
     *                     property="varCodiElemento",
     *                     type="string"
     *                 ) ,
     *                   @OA\Property(
     *                     property="intIdProyZona",
     *                     type="string"
     *                 ) ,
     *                   @OA\Property(
     *                     property="intRevision",
     *                     type="string"
     *                 ) ,
     *                      @OA\Property(
     *                     property="varDescripcion",
     *                     type="string"
     *                 ) ,
     *                   @OA\Property(
     *                     property="intSerie",
     *                     type="string"
     *                 ) ,
     *                      @OA\Property(
     *                     property="deciPesoNeto",
     *                     type="string"
     *                 ) ,
     *                  @OA\Property(
     *                     property="deciArea",
     *                     type="string"
     *                 ) ,
     *                    @OA\Property(
     *                     property="deciLong",
     *                     type="string"
     *                 ) ,
     *                  @OA\Property(
     *                     property="varPerfil",
     *                     type="string"
     *                 ) ,
     *                   @OA\Property(
     *                     property="varCodVal",
     *                     type="string"
     *                 ) ,
     *                      @OA\Property(
     *                     property="intIdProyTarea",
     *                     type="string"
     *                 ) ,
     *                    @OA\Property(
     *                     property="intIdProyPaquete",
     *                     type="string"
     *                 ) ,
     * 
     *                  
     *                 example={"intIdProy": "126","intIdTipoProducto":"1","varCodiElemento":"013-AP-V23","intIdProyZona":"1","intRevision":"0","varDescripcion":"VIGA","intSerie":"12","deciPesoNeto":"9.710","deciArea":"1.190","deciLong":"3014.000","varPerfil":"U-100X50X2",
     *                          "varCodVal":"LFW","intIdProyTarea":"1","intIdProyPaquete":"1","intCantRepro"="0","deciPrec"=""}
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
    // function de LISTAR _SERIE PARA LA VISTA OT
    public function list_seri(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'varCodiElemento' => 'required|max:255',
            'intIdProyZona' => 'required|max:255',
            'intRevision' => 'required|max:255',
            'varDescripcion' => 'required|max:255',
            'intSerie' => 'max:255',
            'deciPesoNeto' => 'required|max:255',
            'deciArea' => 'required|max:255',
            'deciLong' => 'required|max:255',
            'varPerfil' => 'required|max:255',
            'varCodVal' => 'required|max:255',
            'intIdProyTarea' => 'required|max:255',
            'intIdProyPaquete' => 'required|max:255'
                //'deciPrec'=>'max:255', 
        ];

        $this->validate($request, $regla);
        $intcantrepro = "";
        $deciPrec = "";
        if ($request->input('intCantRepro') == "") {
            $intcantrepro = null;
        } else {
            $intcantrepro = $request->input('intCantRepro');
        }

        if ($request->input('deciPrec') == "") {
            $deciPrec = null;
        } else {
            $deciPrec = $request->input('deciPrec');
        }

// Serie, codigo, nombre, fecha de modificación. Datos importantes idproyecto, idproducto, marca.
        $most_cada_serie = Elemento::join('proyecto', 'proyecto.intIdProy', '=', 'elemento.intIdProy')
                ->join('estado', 'estado.intIdEsta', '=', 'elemento.intIdEsta')
                ->where('elemento.intIdProy', $request->input('intIdProy'))
                ->where('elemento.intIdTipoProducto', $request->input('intIdTipoProducto'))
                ->where('elemento.varCodiElemento', $request->input('varCodiElemento'))
                ->where('elemento.intIdProyZona', $request->input('intIdProyZona'))
                ->where('elemento.intRevision', $request->input('intRevision'))
                ->where('elemento.varDescripcion', $request->input('varDescripcion'))
                ->where('elemento.intCantRepro', $intcantrepro)
                ->where('elemento.deciPesoNeto', $request->input('deciPesoNeto'))
                ->where('elemento.deciArea', $request->input('deciArea'))
                ->where('elemento.deciLong', $request->input('deciLong'))
                ->where('elemento.varPerfil', $request->input('varPerfil'))
                ->where('elemento.deciPrec', $deciPrec)
                ->where('elemento.varCodVal', $request->input('varCodVal'))
                //falta ruta     
                ->where('elemento.intSerie', $request->input('intSerie'))
                ->where('elemento.intIdProyTarea', $request->input('intIdProyTarea'))
                ->where('elemento.intIdProyPaquete', $request->input('intIdProyPaquete'))
                ->select('elemento.intIdProy', 'proyecto.varCodiProy', 'elemento.intIdEleme', 'elemento.varCodiElemento', 'elemento.intIdTipoProducto', 'elemento.varDescripcion', 'elemento.intRevision', 'elemento.intSerie', 'elemento.intIdEsta', 'estado.varDescEsta', 'elemento.usua_modi', 'elemento.hora_modi')
                ->get();


        return $this->successResponse($most_cada_serie);
    }

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/vali_var_codi_elem",
     *     tags={"Gestion PartList"},
     *     summary="valirdar codigo elemento",
     *     @OA\Parameter(
     *         description="seleccionar el id proyecto",
     *         in="path",
     *         name="intIdProy",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *         @OA\Parameter(
     *         description="selecciona el tipo de producto",
     *         in="path",
     *         name="intIdTipoProducto",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      *         @OA\Parameter(
     *         description="seleccionar codigo elemento",
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
     *                  @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
     *                    @OA\Property(
     *                     property="varCodiElemento",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProy": "126","intIdTipoProducto":"1","varCodiElemento":"013-AP-V23"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="validar si el codigo elemento existe"
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description=" El codigo elemento ingresado no se encuentra registrado."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function vali_var_codi_elem(Request $request) {
        $validar = array('mensaje' => '');
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'varCodiElemento' => 'required|max:255',
        ];

        $this->validate($request, $regla);

        $intIdProy = $request->input('intIdProy');
        $intIdTipoProducto = $request->input('intIdTipoProducto');
        $varCodiElemento = $request->input('varCodiElemento');

        $exite_varcodiElemento = Elemento::where('intIdProy', '=', $intIdProy)
                        ->where('intIdTipoProducto', '=', $intIdTipoProducto)
                        ->where('varCodiElemento', '=', $varCodiElemento)
                        ->select('intIdEleme')->get();


        if (count($exite_varcodiElemento) > 0) {
            $validar['mensaje'] = "Error.";
            return $this->successResponse($validar);
        } else {
            $validar['mensaje'] = "";
            return $this->successResponse($validar);
        }
    }

    /*     * ***************************STORE* */

    /**
     * @OA\Post(
     *     path="/GestionUsuarios/public/index.php/store_elim_avance",
     *     tags={"Gestion PartList"},
     *     summary="Permite eliminar un avance",
     *     @OA\Parameter(
     *         description="ingresa el id del proyecto",
     *         in="path",
     *         name="v_intIdproy",
     *         example="126",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),

     *       @OA\Parameter(
     *         description="ingresar el id del proyecto",
     *         in="path",
     *         name="v_intIdTipoProducto",
     *        example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *    @OA\Parameter(
     *         description="Ingrese el codigo del elemento",
     *         in="path",
     *         name="v_codigo",
     *        example="013-AP-A1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *    *    @OA\Parameter(
     *         description="Ingrese el numero de serie",
     *         in="path",
     *         name="v_strNuSerie",
     *        example="4",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *    @OA\Parameter(
     *         description="Ingrese el nombre del usuario",
     *         in="path",
     *         name="v_usuario",
     *        example="4",
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
     *                     property="varNumeDni",
     *                     type="string"
     *                 ) ,
     *                 example={"v_intIdproy": "126","v_intIdTipoProducto":"1","v_codigo":"013-AP-A1","v_strNuSerie":"4","v_usuario":"v_usuario"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="envia un mensaje de respuesta cuando salga del store"
     *     ),
     *   
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    //eliminar avance 
    public function store_elim_avance(Request $request) {
        $regla = [
            'v_intIdproy' => 'required|max:255',
            'v_intIdTipoProducto' => 'required|max:255',
            'v_codigo' => 'required|max:255',
            'v_strNuSerie' => 'required|max:255', //concatenado 
            'v_usuario' => 'required|max:255'
        ];

        $this->validate($request, $regla);
        date_default_timezone_set('America/Lima'); // CDT
        $v_intIdproy = (int) $request->input('v_intIdproy');
        $v_intIdTipoProducto = (int) $request->input('v_intIdTipoProducto');
        $v_codigo = $request->input('v_codigo');
        $v_strNuSerie = trim($request->input('v_strNuSerie'), ',');
        $v_usuario = $request->input('v_usuario');
        $v_fecha = $current_date = date('Y-m-d H:i:s');


        //   dd($v_intIdproy,$v_intIdTipoProducto,$v_codigo,$v_strNuSerie,$v_usuario);

        DB::select('CALL sp_avance_eliminar_D01(?,?,?,?,?,?,@mensaje)', array(
            $v_intIdproy,
            $v_intIdTipoProducto,
            $v_codigo,
            $v_strNuSerie,
            $v_usuario,
            $v_fecha
        ));
        $results = DB::select('select @mensaje');
        return $this->successResponse($results);
    }

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/most_seri_host_avan",
     *      tags={"Gestion PartList"},
     *     summary="seleccion fila de los historicos y muestra la informacion",
     *     @OA\Parameter(
     *         description="ingresar el id del proyecto",
     *         in="path",
     *         name="intIdProy",
     *         example="126",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="ingresar el ID del intIdTipoProducto",
     *         in="path",
     *         name="intIdTipoProducto",
     *         example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *      @OA\Parameter(
     *         description="ingresar la marca del elemento",
     *         in="path",
     *         name="varCodiElemento",
     *         example="013-AP-P1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),

     *   @OA\Parameter(
     *         description="ingresar la descripcion de elemento",
     *         in="path",
     *         name="varDescripcion",
     *         example="PLANCHA_PISO",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *      @OA\Parameter(
     *         description="ingresar el id del proyecto zona",
     *         in="path",
     *         name="intIdProyZona",
     *         example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
      @OA\Parameter(
     *         description="ingresar el id del proyecto tarea",
     *         in="path",
     *         name="intIdProyTarea",
     *         example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
      @OA\Parameter(
     *         description="ingresar el id del proyecto paquete",
     *         in="path",
     *         name="intIdProyPaquete",
     *         example="17",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *               @OA\Parameter(
     *         description="ingresar el peso neto",
     *         in="path",
     *         name="deciPesoNeto",
     *         example="47.660",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *     @OA\Parameter(
     *         description="ingresar el peso bruto",
     *         in="path",
     *         name="deciPesoBruto",
     *         example="47.660",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *     @OA\Parameter(
     *         description="ingresar el Area",
     *         in="path",
     *         name="deciArea",
     *         example="4.070",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *  *     @OA\Parameter(
     *         description="ingresar la revision",
     *         in="path",
     *         name="intNuRevis",
     *         example="0",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *   @OA\Parameter(
     *         description="ingrese el numero de contrata",
     *         in="path",
     *         name="intNuConta",
     *         example="0",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="ingrese el ID etapa",
     *         in="path",
     *         name="intIdEtapa",
     *         example="8",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *  
     *          @OA\Parameter(
     *         description="ingrese el precio",
     *         in="path",
     *         name="deciPrec",
     *         example="0.000",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ), 
     *          @OA\Parameter(
     *         description="ingrese el Contratista",
     *         in="path",
     *         name="intIdContr",
     *         example="0",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ), 
     * 
     *  *          @OA\Parameter(
     *         description="ingrese el ID de la maquina",
     *         in="path",
     *         name="intIdMaqui",
     *         required=false,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ), 
     * 
     *      @OA\Parameter(
     *         description="ingrese la observacion avance",
     *         in="path",
     *         name="obse_avan",
     *         required=false,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ), 
     *  *      @OA\Parameter(
     *         description="ingrese el Bulto",
     *         in="path",
     *         name="varBulto",
     *         required=false,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *     @OA\Parameter(
     *         description="ingrese el numero de guia",
     *         in="path",
     *         name="nume_guia",
     *         required=false,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),     
     *         @OA\Parameter(
     *         description="ingrese el numero de guia",
     *         in="path",
     *         name="fech_avan",
     *          example="2019-11-12",
     *         required=false,
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
     *                  example={"intIdProy":"126","intIdTipoProducto":"1","varCodiElemento":"013-AP-P1","varDescripcion":"PLANCHA_PISO",
     *                          "intIdProyZona":"1","intIdProyTarea":"1","intIdProyPaquete":"17","deciPesoNeto":"47.660","deciPesoBruto":"47.660",
     *                          "deciArea":"4.070","intNuRevis":"0","intNuConta":"0","intIdEtapa":"8","deciPrec":"0.000","intIdContr":"21","intIdMaqui":"",
     *                          "obse_avan":"","varBulto":"","nume_guia":"","fech_avan":"2019-11-12"}

     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="muestra la informacion de los parametros asignados"
     *     ),
     *    
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    //*****mostrar informacio historico******//
    public function most_seri_host_avan(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'varCodiElemento' => 'required|max:255',
            'varDescripcion' => 'required|max:255',
            'intIdProyZona' => 'required|max:255',
            'intIdProyTarea' => 'required|max:255',
            'intIdProyPaquete' => 'required|max:255',
            'deciPesoNeto' => 'required|max:255',
            'deciPesoBruto' => 'required|max:255',
            'deciArea' => 'required|max:255',
            'intNuRevis' => 'required|max:255',
            'intNuConta' => 'required|max:255',
            'intIdEtapa' => 'required|max:255',
            // 'deciPrec' => 'required|max:255',
            'intIdContr' => 'required|max:255',
            'fech_avan' => 'required|max:255',
                //'varPerfil'=>'required|max:255',
                //'varValo1'=>'required|max:255'
                // 'intIdMaqui' => 'required|max:255', me lo mandas 
                //'obse_avan'=>'required|max:255', me lo mandas 
                // 'varBulto'=>'required|max:255',me lo mandas 
                // 'nume_guia'=>'required|max:255', me lo mandas  
                // 'DocEnvioTS'=>'required|max:255', me lo mandas  
        ];
        $this->validate($request, $regla);


        $intIdProy = (int) $request->input('intIdProy');
        $intIdTipoProducto = (int) $request->input('intIdTipoProducto');
        $varCodiElemento = $request->input('varCodiElemento');
        $varDescripcion = $request->input('varDescripcion');
        $intIdProyZona = (int) $request->input('intIdProyZona');
        $intIdProyTarea = (int) $request->input('intIdProyTarea');
        $intIdProyPaquete = (int) $request->input('intIdProyPaquete');
        $deciPesoNeto = $request->input('deciPesoNeto');
        $deciPesoBruto = $request->input('deciPesoBruto');
        $deciArea = $request->input('deciArea');
        $intNuRevis = (int) $request->input('intNuRevis');
        $intNuConta = (int) $request->input('intNuConta');
        $intIdEtapa = (int) $request->input('intIdEtapa');
        $deciPrec = $request->input('deciPrec');
        $intIdContr = (int) $request->input('intIdContr');
        $intIdRuta = (int) $request->input('intIdRuta');
        $intMaxContaEtap = (int) $request->input('intMaxContaEtap');
        $fech_avan = $request->input('fech_avan');

        $intIdMaqui = $request->input('intIdMaqui');
        $varBulto = $request->input('varBulto');
        $obse_avan = $request->input('obse_avan');
        $nume_guia = $request->input('nume_guia');


        $deciLong = $request->input('deciLong');
        $varPerfil = $request->input('varPerfil');

        $varValo1 = $request->input('varValo1');


        if ($intIdMaqui == null) {

            $intIdMaqui = null;
        } else if ($intIdMaqui == 0 | $intIdMaqui == '') {
            $intIdMaqui = 0;
        } else {
            $intIdMaqui = $intIdMaqui;
        }

        if ($varBulto == null || $varBulto == "") {
            $varBulto = '';
        } else {
            $varBulto = $varBulto;
        }

        if ($nume_guia == null || $nume_guia == "") {
            $nume_guia = '';
        } else {
            $nume_guia = $nume_guia;
        }


        if ($obse_avan == null || $obse_avan == "") {
            $obse_avan = '';
        } else {
            $obse_avan = $obse_avan;
        }

        if ($varValo1 == null || $varValo1 == "") {
            $varValo1 = '';
        } else {
            $varValo1 = $varValo1;
        }

        if ($deciPrec == null || $deciPrec == "") {
            $deciPrec = null;
        } else {
            $deciPrec = $deciPrec;
        }


        if ($request->input('Obs1') == null || $request->input('Obs1') == "") {
            $varValo2 = '';
        } else {
            $varValo2 = $request->input('Obs1');
        }
        if ($request->input('obs2') == null || $request->input('obs2') == "") {
            $varValo3 = '';
        } else {
            $varValo3 = $request->input('obs2');
        }
        if ($request->input('obs3') == null || $request->input('obs3') == "") {
            $varValo4 = '';
        } else {
            $varValo4 = $request->input('obs3');
        }
        if ($request->input('obs4') == null || $request->input('obs4') == "") {
            $varValo5 = '';
        } else {
            $varValo5 = $request->input('obs4');
        }



        /*   dd(
          $intIdProy,
          $intIdTipoProducto,
          $varCodiElemento,
          $varDescripcion,
          $intIdProyZona,
          $intIdProyTarea,
          $intIdProyPaquete,
          $deciPesoNeto,
          $deciPesoBruto,
          $deciArea,
          $intNuRevis,
          $intNuConta,
          $intIdEtapa,
          $deciPrec,
          $intIdContr,
          $intIdMaqui,
          $fech_avan,
          $obse_avan,

          $varBulto,
          $nume_guia,
          $varValo1,
          $varPerfil
          ); */


        $mostrar_informacion = Elemento::join('proy_avan', 'proy_avan.intIdEleme', '=', 'elemento.intIdEleme')
                ->where('elemento.intIdProy', '=', $intIdProy)
                ->where('elemento.intIdTipoProducto', '=', $intIdTipoProducto)
                ->where('elemento.varCodiElemento', '=', $varCodiElemento)
                ->where('elemento.varDescripcion', '=', $varDescripcion)
                ->where('proy_avan.intIdProyZona', '=', $intIdProyZona)
                ->where('proy_avan.intIdProyTarea', '=', $intIdProyTarea)
                ->where('proy_avan.intIdProyPaquete', '=', $intIdProyPaquete)
                ->where('elemento.deciPesoNeto', '=', $deciPesoNeto)
                ->where('elemento.deciPesoBruto', '=', $deciPesoBruto)
                ->where('elemento.deciArea', '=', $deciArea)
                ->where('proy_avan.intNuRevis', '=', $intNuRevis)
                ->where('proy_avan.intNuConta', '=', $intNuConta)
                ->where('proy_avan.intIdEtapa', '=', $intIdEtapa)
                ->where('proy_avan.deciPrec', '=', $deciPrec)
                ->where('proy_avan.intIdContr', '=', $intIdContr)
                ->where('proy_avan.intIdMaqui', '=', $intIdMaqui)
                ->where('proy_avan.obse_avan', '=', $obse_avan)
                ->where('proy_avan.varBulto', '=', $varBulto)
                ->where('proy_avan.varDocu', '=', $nume_guia)
                ->where('proy_avan.fech_avan', '=', $fech_avan)
                ->where('elemento.varValo1', '=', $varValo1)
                ->where('elemento.varPerfil', '=', $varPerfil)
                ->where('elemento.varValo2', '=', $varValo2)
                ->where('elemento.varValo3', '=', $varValo3)
                ->where('elemento.varValo4', '=', $varValo4)
                ->where('elemento.varValo5', '=', $varValo5)
                ->where('proy_avan.intIdRuta', '=', $intIdRuta)
                ->where('proy_avan.intMaxContaEtap', '=', $intMaxContaEtap)
                ->select('elemento.varCodiElemento', 'elemento.intSerie', 'elemento.varDescripcion', 'proy_avan.acti_usua', 'proy_avan.acti_hora', 'proy_avan.usua_modi', 'proy_avan.hora_modi', 'proy_avan.acti_usua')
                ->get();



        return $this->successResponse($mostrar_informacion);
    }

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/gspar_actua_pintura",
     *       tags={"Gestion PartList"},
     *     summary="actualiza la pintura",
     *     @OA\Parameter(
     *         description="ingrese el id del proyecto,tipo producto,paquete,codigo elemento",
     *         in="path",
     *         name="cod_pintura",
     *         required=true,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *       @OA\Parameter(
     *         description="Ingrese la pintura",
     *         in="path",
     *         name="varValo1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="Ingrese ingrese el usuario que ha modificado",
     *         in="path",
     *         name="usua_modi",
     *         required=true,
     *         @OA\Schema(
     *           type="string"
     *         
     *         )
     *     ),
     *        
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="cod_pintura",
     *                     type="string",
     *                 ) ,
     *                
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="manda un mensaje vacio."
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="SELECCIONE LA FILA"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function gspar_actua_pintura(Request $request) {
        $validar = array('mensaje' => '');
        $regla = [
            'cod_pintura' => 'required',
            'varValo1' => 'required|max:255',
            'usua_modi' => 'required|max:255'
        ];
        $this->validate($request, $regla);


        $cod_pintura = $request->input('cod_pintura');
        $varValo1 = $request->input('varValo1');
        //$usua_modi = $request->input('usua_modi');

        $contar = count($cod_pintura);
        $quitar = "";
        $porciones = [];
        // dd($cod_pintura[0]['intIdProy']);
        //  'hora_modi' => $current_date = date('Y/m/d H:i:s')   date_default_timezone_set('America/Lima'); // 
        //

     // dd($cod_pintura);

        if (count($cod_pintura) > 0) {

            foreach ($cod_pintura as $index) {

                // dd($index['series']);
                if ($index['series'] == "") {

                    //dd($index['Pintura']);
                    Elemento::where('intIdproy', '=', (int) $index['intIdProy'])
                            ->where('intIdTipoProducto', '=', (int) $index['intIdTipoProducto'])
                            ->where('intIdProyPaquete', '=', (int) $index['intIdProyPaquete'])
                            ->where('varCodiElemento', '=', $index['varCodiElemento'])
                            ->where('intIdEsta', '!=', 2)
                            ->where('intIdEsta', '!=', 6)
                            ->where('varValo1', '=', $index['Pintura'])
                            ->update([
                                'varValo1' => $varValo1,
                    ]);
                } else {
                    $quitar = trim($index['series'], ',');
                    $porciones = explode(",", $quitar);


                    foreach ($porciones as $serie) {
                        ///   dd($serie);
                        Elemento::where('intIdproy', '=', (int) $index['intIdProy'])
                                ->where('intIdTipoProducto', '=', (int) $index['intIdTipoProducto'])
                                ->where('intIdProyPaquete', '=', (int) $index['intIdProyPaquete'])
                                ->where('varCodiElemento', '=', $index['varCodiElemento'])
                                ->where('intSerie', '=', $serie)
                                ->where('intIdEsta', '!=', 6)
                                ->where('intIdEsta', '!=', 2)
                                ->update([
                                    'varValo1' => $varValo1,
                        ]);
                    }
                }
            }
        } else {
            $validar['mensaje'] = "SELECCIONE LA FILA";
        }

        return $this->successResponse($validar);
    }

    // INSERTAR COMPONENTES sp_Elementos_I01

    public function inse_compo(Request $request) {
        $regla = [
            'v_intIdProy' => 'required|max:255',
            'v_intIdTipoProducto' => 'required|max:255',
            'v_varCodiElemento' => 'required|max:255',
            'v_varComponente' => 'required|max:255',
            'v_intCantidad' => 'required|max:255', //-- CANTIDAD DEL COMPONENTE POR SERIE
            'v_material' => 'required|max:255',
            'v_varPerfil' => 'required|max:255',
            'v_deciLong' => 'required|max:255',
            'v_varDescripcion' => 'required|max:255',
            'v_deciPesoNeto' => 'required|max:255',
            'v_deciPesoBruto' => 'required|max:255',
            'v_deciPesoContr' => 'required|max:255',
            'v_deciArea' => 'required|max:255',
            'v_usuario' => 'required|max:255',
            //coloco andy 
            'v_intIdProyZona' => 'required|max:255',
            'v_intIdProyTarea' => 'required|max:255',
            'v_strDeZona' => 'required|max:255',
            'v_strDeTarea' => 'required|max:255'
                //'v_fechahora'=>'required|max:255'
        ];
        $this->validate($request, $regla);
        $v_intIdProy = (int) $request->input('v_intIdProy');
        $v_intIdTipoProducto = (int) $request->input('v_intIdTipoProducto');
        $v_varCodiElemento = $request->input('v_varCodiElemento');
        $v_varComponente = $request->input('v_varComponente');
        $v_material = $request->input('v_material');
        $v_intCantidad = (int) $request->input('v_intCantidad');
        $v_varPerfil = $request->input('v_varPerfil');
        $v_deciLong = $request->input('v_deciLong');
        $v_varDescripcion = $request->input('v_varDescripcion');
        $v_deciPesoNeto = $request->input('v_deciPesoNeto');
        $v_deciPesoBruto = $request->input('v_deciPesoBruto');
        $v_deciPesoContr = $request->input('v_deciPesoContr');
        $v_deciArea = $request->input('v_deciArea');
        $v_usuario = $request->input('v_usuario');

        // se coloco 
        $v_intIdProyZona = (int) $request->input('v_intIdProyZona');
        $v_intIdProyTarea = (int) $request->input('v_intIdProyTarea');
        $v_strDeZona = $request->input('v_strDeZona');
        $v_strDeTarea = $request->input('v_strDeTarea');

        $v_fechahora = "";
        date_default_timezone_set('America/Lima'); // CDT
        $v_fechahora = $current_date = date('Y/m/d H:i:s');

        /* dd($v_intIdProy, 
          $v_intIdTipoProducto,
          $v_varCodiElemento,
          $v_varComponente,
          $v_intCantidad,
          $v_material,
          $v_varPerfil,
          $v_deciLong,
          $v_varDescripcion,
          $v_deciPesoNeto,
          $v_deciPesoBruto,
          $v_deciPesoContr,
          $v_deciArea,
          $v_usuario,
          $v_fechahora,
          $v_intIdProyZona,
          $v_intIdProyTarea,
          $v_strDeZona,
          $v_strDeTarea);

         */
        DB::select('CALL sp_Elementos_I01(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,@v_mensaje)', array(
            $v_intIdProy,
            $v_intIdTipoProducto,
            $v_varCodiElemento,
            $v_varComponente,
            $v_intCantidad,
            $v_material,
            $v_varPerfil,
            $v_deciLong,
            $v_varDescripcion,
            $v_deciPesoNeto,
            $v_deciPesoBruto,
            $v_deciPesoContr,
            $v_deciArea,
            $v_usuario,
            $v_fechahora,
            $v_intIdProyZona,
            $v_intIdProyTarea,
            $v_strDeZona,
            $v_strDeTarea
        ));
        $results = DB::select('select @v_mensaje as mensaje');
        //dd($results);
        return $this->successResponse($results);
    }

}
