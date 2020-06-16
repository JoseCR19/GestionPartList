<?php

namespace App\Http\Controllers;

use App\Usuario;
use App\Rutaproyecto;
use App\Detalleruta;
use App\Proyectozona;
use App\Proyecto;
use App\Etapa;
use App\Elemento;
use App\AsignarEtapaProyecto;
use App\TipoProducto;
use App\Proyectopaquete;
use App\Proyectotarea;
use Illuminate\Http\Request;
use DB;
use Illuminate\Http\Response;

class RutaController extends Controller {

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
     *     path="/GestionPartList/public/index.php/list_ruta_proy",
     *     tags={"Gestion Rutas"},
     *     summary="Obtenemos las lista de ruta de un proyecto",
     *     @OA\Parameter(
     *         description="Ingresar el id del proyecto",
     *         in="path",
     *         name="intIdProy",
     *        example="126",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *        
     *  *     @OA\Parameter(
     *         description="Ingrese el id del tipo producto",
     *         in="path",
     *         name="intIdTipoProducto",
     *        example="1",
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
     *                 example={"intIdProy": "126","intIdTipoProducto":"1"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Obtenemos las rutas de ese proyecto"
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description="Error.Por que no encuentra ruta para ese proyecto"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    //LISTAR RUTA PROYECTO
    public function list_ruta_proy(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255'
        ];

        if ($request->input('intIdTipoProducto') == -1) {
            $list_ruta = RutaProyecto::leftjoin('tipo_producto', 'tipo_producto.intIdTipoProducto', '=', 'ruta.intIdTipoProducto')
                            ->leftjoin('proyecto', 'proyecto.intIdProy', '=', 'ruta.intIdProy')
                            ->where('ruta.intIdProy', $request->input('intIdProy'))
                            ->select(
                                    'ruta.intIdRuta', 'ruta.intIdProy', 'ruta.intIdTipoProducto', 'proyecto.varCodiProy', 'proyecto.varCodiOt', 'tipo_producto.varDescTipoProd', 'ruta.varNombre', 'ruta.varDescrip', 'ruta.acti_usua', 'ruta.acti_hora', 'ruta.usua_modi', 'ruta.hora_modi'
                            )->get();
        } else {
            $list_ruta = RutaProyecto::leftjoin('tipo_producto', 'tipo_producto.intIdTipoProducto', '=', 'ruta.intIdTipoProducto')
                            ->leftjoin('proyecto', 'proyecto.intIdProy', '=', 'ruta.intIdProy')
                            ->where('ruta.intIdProy', $request->input('intIdProy'))
                            ->where('ruta.intIdTipoProducto', $request->input('intIdTipoProducto'))
                            ->select(
                                    'ruta.intIdRuta', 'ruta.intIdProy', 'ruta.intIdTipoProducto', 'proyecto.varCodiProy', 'proyecto.varCodiOt', 'tipo_producto.varDescTipoProd', 'ruta.varNombre', 'ruta.varDescrip', 'ruta.acti_usua', 'ruta.acti_hora', 'ruta.usua_modi', 'ruta.hora_modi'
                            )->get();
        }
        if (count($list_ruta) > 0) {
            return $this->successResponse($list_ruta);
        } else {
            $mensaje = ['mensaje' => 'Error.'];
            return $this->successResponse($mensaje);
        }
    }

    /**
     * @OA\Get(
     *     path="/GestionPartList/public/index.php/list_tipo_prod_ruta",
     *     tags={"Gestion Rutas"},
     *     summary="Listar tipo producto para las rutas",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Listar tipo producto para las rutas"
     *     )
     * )
     */
    public function list_tipo_prod_ruta() {

        $lista_tipo_prod = TipoProducto::where('varEstaTipoProd', '=', 'ACT')
                ->get(['intIdTipoProducto', 'varDescTipoProd']);
        $dato_todo = ['intIdTipoProducto' => -1, 'varDescTipoProd' => 'TODOS'];
        $lista_tipo_prod->push($dato_todo);

        return $this->successResponse($lista_tipo_prod);
    }

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/crea_asig_ruta_proy",
     *     tags={"Gestion Rutas"},
     *     summary="Permite asignar ruta al proyecto",
     *     @OA\Parameter(
     *         description="ingresar el id del proyecto",
     *         in="path",
     *         name="intIdProy",
     *       example="126",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="ingresar el id del intIdTipoProducto",
     *         in="path",
     *         name="intIdTipoProducto",
     *       example="2",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="El nombre del ruta",
     *         in="path",
     *         name="varNombre",
     *   example="RUTA PRUEBA11",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *       @OA\Parameter(
     *         description="Ingresse la descripcion",
     *         in="path",
     *         name="varDescrip",
     *     example="CALIDAD",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *        
      @OA\Parameter(
     *         description="Ingrese ingrese al usuario que ha creado la ruta",
     *         in="path",
     *         name="acti_usua",
      example="andy_ancajima",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="Ingresea el idi de esa asignar etapa proyecto",
     *         in="path",
     *         name="intIdAsigEtapProy",
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

      @OA\Property(
      property="intIdTipoProducto",
      type="string"
      ) ,
      @OA\Property(
      property="varNombre",
      type="string"
      ) ,
      @OA\Property(
      property="varDescrip",
      type="string"
      ) ,
      @OA\Property(
      property="acti_usua",
      type="string"
      ) ,
     *                  @OA\Property(
      property="intIdAsigEtapProy",
      type="string"
      ) ,
     *                 example={"intIdProy": "126","intIdTipoProducto":"2","varNombre":"RUTA PRUEBA11","varDescrip":"CALIDAD",
     *                       "acti_usua":"andy_ancajima","intIdAsigEtapProy":"0"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="EXITO"
     *     ),
     *     
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function crea_asig_ruta_proy(Request $request) {
        // $mensaje=array('mensaje'=>'');
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'varNombre' => 'required|max:255',
            'varDescrip' => 'required|max:255',
            //  'intIdEsta' => 'required|max:255',
            'acti_usua' => 'required|max:255',
            //para colocarlo en la tabla "deta_ruta"
            'intIdAsigEtapProy' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdProy = $request->input('intIdProy');
        $intIdTipoProducto = $request->input('intIdTipoProducto');
        $nombre_ruta = $request->input('varNombre');
        //$obtener_nombre = Rutaproyecto::where('varNombre', $request->input('varNombre'))->first(['varNombre']);
        $obtener_nombre = DB::select("select varNombre,intIdRuta from ruta  where varNombre='$nombre_ruta' and intIdProy=$intIdProy");
        if (count($obtener_nombre) > 0) {

            $mensaje = ['mensaje' => 'Error.', 'error' => 'El nombre de la ruta ya se encuentra utilizada, registrar con otro nombre.'];
            //   $mensaje="El nombre de la ruta ya se encuentra utilizada, registrar con otro nombre.";
        } else {
            date_default_timezone_set('America/Lima'); // CDT
            //contamos la cantidad de descripcion ha seleccionado
            $input = $request->input('varDescrip'); //Array lo que recibo
            //dd($input);
            $contar = count($input);
            $intIdAsigEtapProy = $request->input('intIdAsigEtapProy'); //Array
            $query = "";
            for ($i = 0; $i < count($intIdAsigEtapProy); $i++) {
                $valor = "";
                if ($contar == 1) {
                    $valor .= $intIdAsigEtapProy[$i];
                } else {
                    $valor .= $intIdAsigEtapProy[$i] . ",";
                }
                $query .= $valor;
            }
            $query = trim(($query), ',');
            $obtener_nombre = DB::select("select asig_etap_proy.intIdAsigEtapProy,etapa.varDescEtap from asig_etap_proy 
                                                    inner join etapa on etapa.intIdEtapa=asig_etap_proy.intIdEtapa 
                                                        where  asig_etap_proy.intIdProy=" . $intIdProy . " and 
                                                        asig_etap_proy.intIdTipoProducto=" . $intIdTipoProducto . " and 
                                                        asig_etap_proy.intIdAsigEtapProy IN(" . $query . ") ORDER BY intOrden asc");
            $descripcion = "";
            for ($i = 0; $i < count($obtener_nombre); $i++) {
                $add = "";
                if ($contar == 1) {
                    // 
                    $add .= $obtener_nombre[$i]->{'varDescEtap'};
                } else {

                    $add .= "->" . $obtener_nombre[$i]->{'varDescEtap'};
                }
                $descripcion .= $add;
            }
            if ($contar == 1) {

                $prueba = $descripcion;
            } else {
                $prueba = (substr($descripcion, 2));
            }
            $obtener_ruta = Rutaproyecto::where('intIdProy', $request->input('intIdProy'))
                            ->where('intIdTipoProducto', $request->input('intIdTipoProducto'))
                            ->where('varDescrip', $prueba)->first(['varDescrip']);
            if ($prueba == $obtener_ruta['varDescrip']) {
                $mensaje = ['mensaje' => 'Error.', 'error' => 'La secuencia de etapas asignadas ya existe'];
            } else {
                //agregamos el registro en dos tablas "ruta" y "deta_ruta"
                $crea_ruta_proy = Rutaproyecto::create([
                            'intIdProy' => $request->input('intIdProy'),
                            'intIdTipoProducto' => $request->input('intIdTipoProducto'),
                            'varNombre' => $request->input('varNombre'),
                            'varDescrip' => $prueba,
                            'intIdEsta' => $request->input('intIdEsta'),
                            'acti_usua' => $request->input('acti_usua'),
                            'acti_hora' => $current_date = date('Y/m/d H:i:s')
                ]);
                $idruta = $crea_ruta_proy['intIdRuta'];
                for ($y = 0; $y < count($intIdAsigEtapProy); $y++) {
                    $regi_deta_ruta = Detalleruta::create([
                                'intIdRuta' => (int) $idruta,
                                'intIdAsigEtapProy' => $intIdAsigEtapProy[$y],
                                'acti_usua' => $request->input('acti_usua'),
                                'acti_hora' => $current_date = date('Y/m/d H:i:s')
                    ]);
                }
                $mensaje = ['mensaje' => 'Exito.'];
            }
        }
        return $this->successResponse($mensaje);
    }

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/modi_ruta",
     *     tags={"Gestion Rutas"},
     *     summary="Permite modificar la ruta que se ha seleccionado, mostrando los datos de esa ruta",
     *     @OA\Parameter(
     *         description="ingresar el id de la ruta que se quiere modificar",
     *         in="path",
     *         name="intIdRuta",
     *       example="3",
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
     *                     property="intIdRuta",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdRuta": "3"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Muestra los datos de la ruta seleccionada"
     *     ),
     * 
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    //modificar ruta para que vea la interfaz jose 
    public function modi_ruta(Request $request) {
        $regla = [
            'intIdRuta' => 'required|max:255'
        ];
        $this->validate($request, $regla);

        $obte_info = DB::table('deta_ruta')
                        ->join('ruta', 'deta_ruta.intIdRuta', '=', 'ruta.intIdRuta')
                        ->join('asig_etap_proy', 'asig_etap_proy.intIdAsigEtapProy', '=', 'deta_ruta.intIdAsigEtapProy')
                        ->join('etapa', 'etapa.intIdEtapa', '=', 'asig_etap_proy.intIdEtapa')
                        ->join('proyecto', 'proyecto.intIdProy', '=', 'ruta.intIdProy')
                        ->join('tipo_producto', 'ruta.intIdTipoProducto', '=', 'tipo_producto.intIdTipoProducto')
                        ->where('deta_ruta.intIdRuta', '=', $request->input('intIdRuta'))
                        ->select('deta_ruta.intIdRuta', 'ruta.intIdProy', 'ruta.intIdTipoProducto', 'deta_ruta.intIdAsigEtapProy', 'etapa.intIdEtapa', 'etapa.varDescEtap', 'proyecto.varCodiOt', 'proyecto.varCodiProy', 'ruta.varNombre', 'varDescTipoProd')
                        ->orderBy('deta_ruta.intIdAsigEtapProy')->get();
        return $this->successResponse($obte_info);
    }

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/list_desc_ruta",
     *     tags={"Gestion Rutas"},
     *     summary="Permite mirar la descripcion de la ruta que se ha seleccionado",
     *     @OA\Parameter(
     *         description="documento de identidad",
     *         in="path",
     *         name="intIdRuta",
     * example="1",
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
     *                     property="intIdRuta",
     *                     type="2"
     *                 ) ,
     *                 example={"intIdRuta": "1"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Muestra la descripcion de esa ruta"
     *     ),
     *  
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    /* tomar la lista detalle ruta  y forma mi cadena actualizar y
      mostrar si no esta en orden voy a la tabla asig_eta_proy para obtener el orden
     *  y realizar mi cadena
     *      */
    public function list_desc_ruta(Request $request) {
        $validar = array('mensaje' => '');
        $regla = [
            'intIdRuta' => 'required|max:255',
        ];
        $this->validate($request, $regla);
        $intIdRuta = ($request->input('intIdRuta'));
        $list_desc_ruta = Rutaproyecto::where('intIdRuta', '=', $intIdRuta)
                ->first(['intIdRuta', 'varDescrip']);

        return $this->successResponse($list_desc_ruta);
    }

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/asig_etap_proy_ruta",
     *     tags={"Gestion Rutas"},
     *     summary="Permite asignar la etapa del proyecto a una ruta",
     *     @OA\Parameter(
     *         description="ingrese el id del proyecto",
     *         in="path",
     *         name="intIdProy",
     * example="126",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="ingrese el id del tipo de producto",
     *         in="path",
     *         name="intIdTipoProducto",
     * example="1",
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
     *           @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProy": "126","intIdTipoProducto":"1"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="muesta la ruta en forma ordena para ese proyecto"
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="No existe la OT "
     *     ),
      @OA\Response(
     *         response=202,
     *         description="No existe "
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function asig_etap_proy_ruta(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255'
        ];
        $this->validate($request, $regla);

        $vali_proy = Proyecto::where('intIdProy', $request->input('intIdProy'))->first(['intIdProy']);

        if (!isset($vali_proy)) {
            $mensaje = [
                'mensaje' => "No existe la OT: " . $request->input('varCodiProy')
            ];
            return $this->successResponse($mensaje);
        } else {
            $vacio = null;
            $list_todo = DB::table('asig_etap_proy')->join('etapa', 'etapa.intIdEtapa', '=', 'asig_etap_proy.intIdEtapa') // junto las tablas etapa y asig_etap_proy
                            ->join('planta', 'planta.intIdPlanta', '=', 'etapa.intIdPlan') //junto las tablas planta y etapa
                            ->join('proyecto', 'proyecto.intIdProy', '=', 'asig_etap_proy.intIdProy') //junto las tablas proyecto y  asig_etap_proy
                            ->join('tipoetapa', 'tipoetapa.intIdTipoEtap', '=', 'etapa.intIdTipoEtap')
                            ->where('proyecto.intIdProy', $request->input('intIdProy'))
                            ->where('asig_etap_proy.intIdTipoProducto', $request->input('intIdTipoProducto'))
                            ->where('asig_etap_proy.intOrden', '!=', $vacio)
                            ->select(
                                    'asig_etap_proy.intIdAsigEtapProy', 'asig_etap_proy.intOrden', 'asig_etap_proy.intIdEtapa', 'etapa.varDescEtap', 'etapa.intIdPlan', 'planta.varDescPlanta', 'tipoetapa.intIdTipoEtap')
                            ->orderBy('asig_etap_proy.intOrden', 'asc')->get();

            if (count($list_todo) == 0) {
                $mensaje = [
                    'mensaje' => "No existe"
                ];
                return $this->successResponse($mensaje);
            } else {
                //  die("adas".$list_todo);
                return $this->successResponse($list_todo);
            }
        }
    }

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/list_codi_elem_ruta",
     *     tags={"Gestion Rutas"},
     *     summary="Lista los códigos de elemento.",
     *     @OA\Parameter(
     *         description="documento de identidad",
     *         in="path",
     *         name="intIdProy",
     *           example="126",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *         @OA\Parameter(
     *         description="ingresar el id del Tipo Productos",
     *         in="path",
     *         name="intIdTipoProducto",
     *           example="1",
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
     *         description="muestrar los codigo deacuerdo a los parametros"
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description="No hay dato existente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
//Lista los códigos de elemento.(Falta OT + TP CON GROUP BY)
    public function list_codi_elem_ruta(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
        ];
        $this->validate($request, $regla);

        $valida_proy = Elemento::where('intIdProy', $request->input('intIdProy'))
                        ->where('intIdTipoProducto', $request->input('intIdTipoProducto'))
                        ->select('varCodiElemento')
                        ->groupBy('varCodiElemento')->get();

        if (count($valida_proy) == 0) {
            $mensaje = ['mensaje' => 'Error.', 'error' => 'No hay dato existente'];
            return $this->successResponse($mensaje);
        } else {
            /*
              $dato_todo = ['intIdProyPaquete' => -1, 'varCodigoPaquete' => 'TODOS'];
              $list_paque->push($dato_todo);
             *              */

            $dato_todo = ['varCodiElemento' => 'TODOS'];
            $valida_proy->push($dato_todo);
            return $this->successResponse($valida_proy);
        }




        return $this->successResponse($valida_proy);
    }

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/list_zona_asoc_proy",
     *     tags={"Gestion Rutas"},
     *     summary="Listar las zonas asociadas al proyecto.",
     *     @OA\Parameter(
     *         description="documento de identidad",
     *         in="path",
     *         name="intIdProy",
     *           example="126",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *         @OA\Parameter(
     *         description="ingresar el id del Tipo Productos",
     *         in="path",
     *         name="intIdTipoProducto",
     *           example="1",
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
     *                 example={"intIdProy": "126","intIdTipoProducto":"1"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="muestrar los codigo deacuerdo a los parametros"
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description="No hay dato existente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    //Listar las zonas asociadas al proyecto.(Falta OT+TP)
    public function list_zona_asoc_proy(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
        ];
        $this->validate($request, $regla);


        $list_zona = Proyectozona::where('intIdProy', $request->input('intIdProy'))
                ->where('intIdTipoProducto', $request->input('intIdTipoProducto'))
                ->select('intIdProyZona', 'varDescrip')
                ->get();
        if (count($list_zona) == 0) {
            $mensaje = ['mensaje' => 'Error.', 'error' => 'No hay dato existente'];
            return $this->successResponse($mensaje);
        } else {
            /*
              $dato_todo = ['intIdProyPaquete' => -1, 'varCodigoPaquete' => 'TODOS'];
              $list_paque->push($dato_todo);
             *              */

            $dato_todo = ['intIdProyZona' => -1, 'varDescrip' => 'TODOS'];
            $list_zona->push($dato_todo);
            return $this->successResponse($list_zona);
        }
    }

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/list_tare_asoc_proy",
     *     tags={"Gestion Rutas"},
     *     summary="Lista las tareas asociadas a la zona de un proyecto.",
     *     @OA\Parameter(
     *         description="documento de identidad",
     *         in="path",
     *         name="intIdProy",
     *           example="126",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *         @OA\Parameter(
     *         description="ingresar el id del Tipo Productos",
     *         in="path",
     *         name="intIdTipoProducto",
     *           example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *        @OA\Parameter(
     *         description="ingresar el id del proyecto zona",
     *         in="path",
     *         name="intIdProyZona",
     *           
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
     *            @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
     *              @OA\Property(
     *                     property="intIdProyZona",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProy": "126","intIdTipoProducto":"1","intIdProyZona":""}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista las tareas asociadas a la zona"
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="No hay dato existente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    //Lista las tareas asociadas a la zona de un proyecto.(Falta OT+TP)
    public function list_tare_asoc_proy(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
                // 'intIdProyZona' => 'required|max:255',
        ];

        $this->validate($request, $regla);
        $arra_zona = $request->input('intIdProyZona');

        $idProy = ($request->input('intIdProy'));
        $intIdTipoProducto = $request->input('intIdTipoProducto');
        $query = "";

        if ($arra_zona[0] == "") {

            $mensaje = ['mensaje' => 'Error.', 'error' => 'No hay dato existente'];
            return $this->successResponse($mensaje);
        } else {

            if (count($arra_zona) > 1) {

                for ($i = 0; $i < count($arra_zona); $i++) {

                    $query .= "," . $arra_zona[$i];
                }
                $query = substr($query, 1);

                //$result = DB::select("SELECT intIdProyTarea,varDescripTarea FROM proyecto_tarea where intIdProy = '$idProy' and intIdTipoProducto= '$intIdTipoProducto' " . $query);
                $result = DB::select("SELECT intIdProyTarea,varDescripTarea FROM proyecto_tarea where intIdProy = '$idProy' and intIdTipoProducto= '$intIdTipoProducto'  and intIdProyZona in ( " . $query . " ) ORDER BY varDescripTarea DESC");

                $dato_todo = ['intIdProyTarea' => -1, 'varDescripTarea' => 'TODOS'];
                array_push($result, $dato_todo);

                return $this->successResponse($result);
            } else {
                if ($arra_zona[0] != "-1") {

                    $list_tarea = Proyectotarea::where('intIdProy', $idProy)
                                    ->where('intIdTipoProducto', $intIdTipoProducto)
                                    ->where('intIdProyZona', $arra_zona[0])
                                    ->select('intIdProyTarea', 'varDescripTarea')
                                    ->orderBy('varDescripTarea', 'DESC')->get();

                    $dato_todo = ['intIdProyTarea' => -1, 'varDescripTarea' => 'TODOS'];
                    $list_tarea->push($dato_todo);

                    return $this->successResponse($list_tarea);
                } else {

                    $list_tarea = Proyectotarea::where('intIdProy', $idProy)
                            ->where('intIdTipoProducto', $intIdTipoProducto)
                            ->select('intIdProyTarea', 'varDescripTarea')
                            ->orderBy('varDescripTarea', 'DESC')
                            ->get();
                    $dato_todo = ['intIdProyTarea' => -1, 'varDescripTarea' => 'TODOS'];
                    $list_tarea->push($dato_todo);

                    return $this->successResponse($list_tarea);
                }
            }
        }
    }

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/list_paqu_asoc_proy",
     *     tags={"Gestion Rutas"},
     *     summary="Lista las tareas asociadas a la zona de un proyecto.",
     *     @OA\Parameter(
     *         description="documento de identidad",
     *         in="path",
     *         name="intIdProy",
     *           example="126",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *         @OA\Parameter(
     *         description="ingresar el id del Tipo Productos",
     *         in="path",
     *         name="intIdTipoProducto",
     *           example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *        @OA\Parameter(
     *         description="ingresar el id del proyecto zona",
     *         in="path",
     *         name="intIdProyZona",
     *           
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
     *            @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
     *              @OA\Property(
     *                     property="intIdProyZona",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProy": "126","intIdTipoProducto":"1","intIdProyZona":""}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista las tareas asociadas a la zona"
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="No hay dato existente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    //6.Lista los paquetes asociados a la tarea de una zona de un proyecto.(Falta OT+TP+idtarea)
    public function list_paqu_asoc_proy(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
                //  'intIdProyTarea' => 'required|max:255'
        ];
        $this->validate($request, $regla);



        $arra_tarea = $request->input('intIdProyTarea');
        $intIdTipoProducto = $request->input('intIdTipoProducto');
        $idProy = ($request->input('intIdProy'));

        $query = "";

        if ($arra_tarea[0] == "") {
            $mensaje = ['mensaje' => 'Error.', 'error' => 'No hay dato existente'];
            return $this->successResponse($mensaje);
        } else {
            if (count($arra_tarea) > 1) {

                for ($i = 0; $i < count($arra_tarea); $i++) {

                    $query .= "," . $arra_tarea[$i];
                }
                $query = substr($query, 1);


                $result = DB::select("SELECT intIdProyPaquete,varCodigoPaquete FROM proyecto_paquetes where intIdProy = '$idProy' and intIdTipoProducto= '$intIdTipoProducto'  and intIdProyTarea in ( " . $query . " ) order by varCodigoPaquete desc");



                $dato_todo = ['intIdProyPaquete' => -1, 'varCodigoPaquete' => 'TODOS'];
                array_push($result, $dato_todo);

                return $this->successResponse($result);
            } else {
                if ($arra_tarea[0] != "-1") {
                    $list_paqu = Proyectopaquete::where('intIdProy', $idProy)
                                    ->where('intIdTipoProducto', $intIdTipoProducto)
                                    ->where('intIdProyTarea', $arra_tarea[0])
                                    ->orderBy('varCodigoPaquete', 'desc')
                                    ->select('intIdProyPaquete', 'varCodigoPaquete')->get();

                    $dato_todo = ['intIdProyPaquete' => -1, 'varCodigoPaquete' => 'TODOS'];
                    $list_paqu->push($dato_todo);

                    return $this->successResponse($list_paqu);
                } else {
                    $list_paqu = Proyectopaquete::where('intIdProy', $idProy)
                            ->where('intIdTipoProducto', $intIdTipoProducto)->select('intIdProyPaquete', 'varCodigoPaquete')
                            ->orderBy('varCodigoPaquete', 'desc')
                            ->get();
                    $dato_todo = ['intIdProyPaquete' => -1, 'varCodigoPaquete' => 'TODOS'];
                    $list_paqu->push($dato_todo);

                    return $this->successResponse($list_paqu);
                }
            }
        }
    }

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/mues_ruta_asoc_tipo_prod",
     *    tags={"Gestion Rutas"},
     *     summary="Muestra las rutas asociadas al tipo de producto.",
     *     @OA\Parameter(
     *         description="documento de identidad",
     *         in="path",
     *         name="intIdProy",
     *       example="126",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="ingrese el id del tipo de producto ",
     *         in="path",
     *         name="intIdTipoProducto",
     *        example="1",
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
     *                     property="intIdProy",
     *                     type="string"
     *                 ) ,
     *               @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProy": "126","intIdTipoProducto":"1"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Muestra un listado."
     *     ),

     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    //7Muestra las rutas asociadas al tipo de producto.(Falta OT+TP)
    public function mues_ruta_asoc_tipo_prod(Request $request) {

        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
        ];
        $this->validate($request, $regla);



        $mues_ruta_tipo_prod = Rutaproyecto::where('intIdProy', $request->input('intIdProy'))
                        ->where('intIdTipoProducto', $request->input('intIdTipoProducto'))
                        ->select('intIdRuta', 'varNombre')->get();

        if (count($mues_ruta_tipo_prod) == 0) {
            $mensaje = ['mensaje' => 'Error.', 'error' => 'No hay dato existente'];
            return $this->successResponse($mensaje);
        } else {
            $dato_todo = ['intIdRuta' => -1, 'varNombre' => 'TODOS'];
            $mues_ruta_tipo_prod->push($dato_todo);
            return $this->successResponse($mues_ruta_tipo_prod);
        }
    }

    //Para elementos que no tienen ruta asignada
    /* Mostar popup para asignar ruta, donde se muestra en combo el nombre de las rutas creadas previamente, 
     * por cada ruta seleccionada se debe mostrar en un textbox la secuencia de etapas de la ruta asignada.  
     */

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/most_secu_etap_ruta_asig",
     *     tags={"Gestion Rutas"},
     *     summary="ruta seleccionada se debe mostrar en un textbox la secuencia de etapas de la ruta asignada",
     *     @OA\Parameter(
     *         description="Ingrese el id de la ruta",
     *         in="path",
     *         name="intIdRuta",
     *        example="2",
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
     *                     property="intIdRuta",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdRuta": "2"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description=" secuencia de etapas de la ruta asignada"
     *     ),
     *   
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    //ruta seleccionada se debe mostrar en un textbox la secuencia de etapas de la ruta asignada
    public function most_secu_etap_ruta_asig(Request $request) {
        $regla = [
            'intIdRuta' => 'required|max:255'
        ];

        //$this->validate($request, $regla);

        $this->validate($request, $regla);
        $vali_secu_etapa_ruta_asig = Rutaproyecto::where('intIdRuta', $request->input('intIdRuta'))
                ->first(['intIdRuta', 'varNombre', 'varDescrip']);

        return $this->successResponse($vali_secu_etapa_ruta_asig);
    }

    // fin de la funcion  combo el nombre de las rutas creadas previamente y seleccionada se debe mostrar en un textbox

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/elem_no_tien_avan_regi",
     *     tags={"Gestion Rutas"},
     *     summary="obtiene datos del usuario a través del dni",
     *     @OA\Parameter(
     *         description="ingrese el id del elemento",
     *         in="path",
     *         name="intIdEleme",
     *        example="350",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *       @OA\Parameter(
     *         description="ingrese el id de la ruta",
     *         in="path",
     *         name="intIdRuta",
     *        example="3",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *        *       @OA\Parameter(
     *         description="ingrese el id de la etapa",
     *         in="path",
     *         name="intIdEtapa",
     *        example="6",
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
     *  @OA\Property(
     *                     property="intIdRuta",
     *                     type="string"
     *                 ) ,
     *                @OA\Property(
     *                     property="intIdEtapa",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdEleme": "350","intIdRuta":"3","intIdEtapa":"6"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description=" permitir cambiar ruta, actualizar etapa actual con la primera etapa
      de la ruta asignada"
     *     ),
     *    
      @OA\Response(
     *         response=201,
     *         description="ERROR. ESTE ELEMENTO YA CUENTA CON AVANCE."
     *     ),
     *      @OA\Response(
     *         response=202,
     *         description="Obtenemos el codigo elemento"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    /*
      //Elemento que tiene rutas asignadas
      o	Si los elementos no tienen ningún avance registrado
      (Validar por el estado “Con Avance�? y que no tenga “etapa anterior�?)
      permitir cambiar ruta, actualizar etapa actual con la primera etapa
      de la ruta asignada.

     */
    public function elem_no_tien_avan_regi(Request $request) {
        $regla = [
            'intIdEleme' => 'required|max:255',
            'intIdRuta' => 'required|max:255',
            'intIdEtapa' => 'required|max:255'
        ];
        $this->validate($request, $regla);

        $vacio = null;

        $vali_elem = Elemento::where('intIdEleme', $request->input('intIdEleme'))
                        ->where('intIdEsta', '!=', 5)
                        ->where('intIdEsta', '!=', 2)
                        ->where('intIdEsta', '!=', 6)
                        ->where('intIdEtapaAnte', '=', $vacio)
                        ->select('varCodiElemento')->first();

        //die("asdas".$vali_elem);
        if (isset($vali_elem)) {
            $vali_elem = Elemento::where('intIdEleme', $request->input('intIdEleme'))
                    ->update([
                'intIdRuta' => $request->input('intIdRuta'),
                'intIdEtapa' => $request->input('intIdEtapa')
            ]);
            //->first(['intIdEleme']);
            $dato = [
                'mensaje' => 'Exito.'
            ];
            return $this->successResponse($mensaje);
        } else {
            $dato = [
                'mensaje' => 'ERROR. ESTE ELEMENTO YA CUENTA CON AVANCE.'
            ];
            return $this->successResponse($dato);
        }

        return $this->successResponse($vali_elem);
    }

    /*     * ******************PROCEDIMIENTO ALMACENADOS **************** */

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/list_elem_asig_ruta",
     *     tags={"Gestion Rutas"},
     *     summary="Listado de elementos según los filtros seleccionados",
     *     @OA\Parameter(
     *         description="Ingrese el id del proyecto",
     *         in="path",
     *         name="intIdProy",
     *       example="175",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingrese el id del tipo de producto",
     *         in="path",
     *         name="intIdTipoProducto",
     *       example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     
     *     @OA\Parameter(
     *         description="ingrese el  id de proyecto zona",
     *         in="path",
     *         name="intIdProyZona",
     *       example="3",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),  

      @OA\Parameter(
     *         description="Ingrese id del proyecto tarea",
     *         in="path",
     *         name="intIdProyTarea",
     *       example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),  

     *      @OA\Parameter(
     *         description="Ingrese id del proyecto paquete",
     *         in="path",
     *         name="intIdProyPaquete",
     *       example="-1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="Ingrese id del proyecto ruta",
     *         in="path",
     *         name="intIdRuta",
     *       example="-1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="Ingrese todos los elementos",
     *         in="path",
     *         name="strCodigos",
     *       example="-1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ), 

     *      @OA\Parameter(
     *         description="Ingrese todos los elementos",
     *         in="path",
     *         name="Tiporuta",
     *       example="1",
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
     *             @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
     *              
     *              @OA\Property(
     *                     property="intIdProyZona",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="intIdProyTarea",
     *                     type="string"
     *                 ) ,
     *                   @OA\Property(
     *                     property="intIdProyPaquete",
     *                     type="string"
     *                 ) ,
     *                  @OA\Property(
     *                     property="intIdRuta",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="strCodigos",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="Tiporuta",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProy": "175","intIdTipoProducto":"1","intIdProyZona":"3","intIdProyTarea":"-1","intIdProyPaquete":"-1",
     *                        "intIdRuta":"-1","strCodigos":"-1","Tiporuta":"1"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listado de elementos según los filtros seleccionados"
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="data vacia"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    //Listado de elementos según los filtros seleccionados: *sp_elementos_Q02*
    public function list_elem_asig_ruta(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'intIdProyZona' => 'required|max:255',
            'intIdProyTarea' => 'required|max:255',
            'intIdProyPaquete' => 'required|max:255',
            'intIdRuta' => 'required|max:255',
            'strCodigos' => 'required|max:255',
            'Tiporuta' => 'required|max:255',
        ];
        $this->validate($request, $regla);


        $intIdProy = ($request->input('intIdProy'));
        $intIdTipoProducto = ($request->input('intIdTipoProducto'));
        $intIdProyZona = ($request->input('intIdProyZona'));
        $intIdProyTarea = trim($request->input('intIdProyTarea'), ',');
        $intIdProyPaquete = trim($request->input('intIdProyPaquete'), ',');
        $IdRuta = (int) ($request->input('intIdRuta'));
        $strCodigos = trim($request->input('strCodigos'), ',');
        $Tiporuta = ($request->input('Tiporuta'));
        //  $intIdEsta=($request->input('intIdEsta'));

        $result = DB::select('CALL sp_elementos_Q02(?,?,?,?,?,?,?,?)', array($intIdProy,
                    $intIdTipoProducto,
                    $intIdProyZona,
                    $intIdProyTarea,
                    $intIdProyPaquete,
                    $IdRuta,
                    $strCodigos,
                    $Tiporuta
        ));

        return $this->successResponse($result);
    }

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/store_modi_ruta",
     *     tags={"Gestion Rutas"},
     *     summary="este es el store de modificación de ruta, ejecutarlo cada vez que agreguen o quiten una etapa de una ruta existente.",
     *     @OA\Parameter(
     *         description="Ingrese el id del proyecto",
     *         in="path",
     *         name="intIdProy",
     *        example="126",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *    @OA\Parameter(
     *         description="Ingrese el id del tipo producto",
     *         in="path",
     *         name="intIdTipoProducto",
     *        example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *    @OA\Parameter(
     *         description="Ingrese el id de la ruta",
     *         in="path",
     *         name="intIdRuta",
     *        example="2",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *    @OA\Parameter(
     *         description="ingrese al id de asignar etapa proyecto",
     *         in="path",
     *         name="v_intIdAsigEtapProy",
     *        example="6",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *    @OA\Parameter(
     *         description="Ingrese la operacion que se va realizar",
     *         in="path",
     *         name="v_operacion",
     *        example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *         @OA\Parameter(
     *         description="Ingrese el usuario que va realizar los cambios",
     *         in="path",
     *         name="usua_modi",
     *        example="usuario_autorizado",
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
     *                     property="intIdProy",
     *                     type="number"
     *                 ) ,
     *                  @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="number"
     *                 ) ,
     *               @OA\Property(
     *                     property="intIdRuta",
     *                     type="number"
     *                 ) ,
     *                   @OA\Property(
     *                     property="v_intIdAsigEtapProy",
     *                     type="number"
     *                 ) ,
     *                   @OA\Property(
     *                     property="v_operacion",
     *                     type="number"
     *                 ) ,
     *                 @OA\Property(
     *                     property="usua_modi",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProy": "126","intIdTipoProducto":"1","intIdRuta":"2","v_intIdAsigEtapProy":"6",
     *                         "v_operacion":"1","usua_modi":"andy_ancajima"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="ste es el store de modificación de ruta, ejecutarlo cada vez que agreguen o quiten una etapa de una ruta existente"
     *     ),
     *    
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    //este es el store de modificación de ruta, ejecutarlo cada vez que agreguen o quiten una etapa de una ruta existente.
    public function store_modi_ruta(Request $request) {
        $validar = array('mensaje' => '');
        $regla = [
            'intIdProy' => 'required|max:255', //     Idproyecto
            'intIdTipoProducto' => 'required|max:255', // TipoProducto
            'intIdRuta' => 'required|max:255', //  IdRuta a modificar
            'v_intIdAsigEtapProy' => 'required|max:255', //  Etapa a agregar o quitar
            'v_operacion' => 'required|max:255', // /**Enviar 1 Agregar, 2 Quitar**/
            //       'v_intIdAsigEtapProySig' => 'required|max:255', //    Etapa siguiente
            //     'v_intIdAsigEtapProySigSig' => 'required|max:255', //  Etapa Siguiente siguiente
            //   'v_intIdAsigEtapProyAnt' => 'required|max:255', // Etapa Siguiente siguiente
            'usua_modi' => 'required|max:255',
                //  'v_mensaje'=>'required|max:255',
        ];
        //dd('a');
        $this->validate($request, $regla);


        $arra_comb = array();
        $intIdProy = (int) ($request->input('intIdProy'));
        $intIdTipoProducto = (int) ($request->input('intIdTipoProducto'));
        $intIdRuta = (int) ($request->input('intIdRuta'));
        $v_intIdAsigEtapProy = (int) ($request->input('v_intIdAsigEtapProy')); //Etapa a agregar o quitar
        $v_operacion = (int) ($request->input('v_operacion')); //agregar o quitar 
        $nomb_usuario = ($request->input('usua_modi'));

        date_default_timezone_set('America/Lima'); // CDT
        $prev = "";
        $next = "";
        $next_next = "";

//OBTENER EL ANTERIOR , EL SIGUIENTE Y SIGUIENTE.
        $list_ruta = Detalleruta::join('asig_etap_proy', 'deta_ruta.intIdAsigEtapProy', '=', 'asig_etap_proy.intIdAsigEtapProy')
                ->join('etapa', 'etapa.intIdEtapa', '=', 'asig_etap_proy.intIdEtapa')
                ->where('deta_ruta.intIdRuta', '=', $intIdRuta)
                ->select('deta_ruta.intIdRuta', 'deta_ruta.intIdAsigEtapProy', 'asig_etap_proy.intOrden', 'etapa.intIdEtapa', 'etapa.varDescEtap')
                ->orderByRaw('intOrden')
                ->get();
        if ($v_operacion == 2) {
            foreach ($list_ruta as $k => $v) {
                if ((int) $v['intIdAsigEtapProy'] == $v_intIdAsigEtapProy) {
                    //dd('ingresa',$v['intIdAsigEtapProy']);
                    if (isset($list_ruta[$k - 1]['intIdAsigEtapProy'])) {
                        $prev = $list_ruta[$k - 1]['intIdAsigEtapProy'];
                    } else {
                        $prev = 0;
                    }

                    if (isset($list_ruta[$k + 1]['intIdAsigEtapProy'])) {
                        $next = $list_ruta[$k + 1]['intIdAsigEtapProy'];
                    } else {
                        $next = 0;
                    }

                    if (isset($list_ruta[$k + 2]['intIdAsigEtapProy'])) {
                        $next_next = $list_ruta[$k + 2]['intIdAsigEtapProy'];
                    } else {
                        $next_next = 0;
                    }

                    break;
                }
            }
            DB::select('CALL sp_rutaprodu_V01 (?,?,?,?,?,?,?,?,@mensaje)', array($intIdProy,
                $intIdTipoProducto,
                $intIdRuta,
                $v_intIdAsigEtapProy,
                $v_operacion,
                $next,
                $next_next,
                $prev
            ));
            $result = DB::select("select @mensaje");
            $validar['mensaje'] = $result[0]->{'@mensaje'};
        } else if ($v_operacion == 1) {
            //VALIDAR SI YA ESTA REGISTRADO 
            $vali_regi = DB::select("select * from deta_ruta where intIdRuta=$intIdRuta and intIdAsigEtapProy=$v_intIdAsigEtapProy");
            //dd(count($vali_regi));
            /* SI ES VACIO EL IF */
            if (count($vali_regi) === 0) {
                $obte_asig_proy = AsignarEtapaProyecto::where('intIdAsigEtapProy', '=', $v_intIdAsigEtapProy)
                                ->select('intOrden', 'intIdAsigEtapProy')
                                ->get()->toArray();
                //dd($obte_asig_proy);
                // HACER UN SELECT EN DETALLE RUTA
                $obte_deta_ruta = Detalleruta::select('asig_etap_proy.intOrden', 'deta_ruta.intIdAsigEtapProy')
                                ->join('asig_etap_proy', 'deta_ruta.intIdAsigEtapProy', '=', 'asig_etap_proy.intIdAsigEtapProy')
                                ->where('deta_ruta.intIdRuta', '=', $intIdRuta)
                                ->orderBy('asig_etap_proy.intOrden')
                                ->get()->toArray();
                //dd($obte_asig_proy, $obte_deta_ruta);

                $arra_comb = array_merge($obte_deta_ruta, $obte_asig_proy);
                //dd($arra_comb);
                sort($arra_comb);
                //dd($arra_comb);
                foreach ($arra_comb as $k => $v) {

                    if ((int) $v['intIdAsigEtapProy'] == $v_intIdAsigEtapProy) {

                        if (isset($arra_comb[$k - 1]['intIdAsigEtapProy'])) {
                            $prev = $arra_comb[$k - 1]['intIdAsigEtapProy'];
                        } else {
                            $prev = 0;
                        }


                        if (isset($arra_comb[$k + 1]['intIdAsigEtapProy'])) {
                            $next = $arra_comb[$k + 1]['intIdAsigEtapProy'];
                        } else {
                            $next = 0;
                        }

                        if (isset($arra_comb[$k + 2]['intIdAsigEtapProy'])) {
                            $next_next = $arra_comb[$k + 2]['intIdAsigEtapProy'];
                        } else {
                            $next_next = 0;
                        }
                        break;
                    }
                }
                /* dd($intIdProy,
                  $intIdTipoProducto,
                  $intIdRuta,
                  $v_intIdAsigEtapProy,
                  $v_operacion,
                  $next,
                  $next_next,
                  $prev); */

                DB::select('CALL sp_rutaprodu_V01 (?,?,?,?,?,?,?,?,@mensaje)', array($intIdProy,
                    $intIdTipoProducto,
                    $intIdRuta,
                    $v_intIdAsigEtapProy,
                    $v_operacion,
                    $next,
                    $next_next,
                    $prev
                ));
                $result = DB::select("select @mensaje");
                $validar['mensaje'] = $result[0]->{'@mensaje'};
            } else {
                $validar['mensaje'] = "Ya se encuentra Asignado.";
                return $this->successResponse($validar);
            }
        }
        if ($validar['mensaje'] === "") {
            if ($v_operacion == 1) {

                $agre_ruta_detalle = Detalleruta::create([
                            'intIdRuta' => $intIdRuta,
                            'intIdAsigEtapProy' => $v_intIdAsigEtapProy,
                            'acti_usua' => $nomb_usuario,
                            'acti_hora' => $current_date = date('Y/m/d H:i:s')
                ]);
            } else {
                // dd($intIdRuta);
                $elim_ruta_detalle = Detalleruta::where('intIdRuta', '=', $intIdRuta)
                        ->where('intIdAsigEtapProy', '=', $v_intIdAsigEtapProy)
                        ->delete();
            }
            $query = "";

            //ACTUALIZAR LA FUNCION 
            $list_ruta = Detalleruta::join('asig_etap_proy', 'deta_ruta.intIdAsigEtapProy', '=', 'asig_etap_proy.intIdAsigEtapProy')
                    ->join('etapa', 'etapa.intIdEtapa', '=', 'asig_etap_proy.intIdEtapa')
                    ->where('deta_ruta.intIdRuta', '=', $intIdRuta)
                    ->select('deta_ruta.intIdRuta', 'deta_ruta.intIdAsigEtapProy', 'asig_etap_proy.intOrden', 'etapa.varDescEtap')
                    ->orderByRaw('intOrden')
                    ->get();


            for ($i = 0; $i < count($list_ruta); $i++) {
                //  die($list_ruta[$i]['varDescEtap']);
                $add = "";

                if (count($list_ruta) == 1) {
                    // 
                    $add .= $list_ruta[$i]['varDescEtap'];
                } else {

                    $add .= "->" . $list_ruta[$i]['varDescEtap'];
                }


                $query .= $add;
            }


            $regi_descr = (substr($query, 2));


            $actu_descrip = Rutaproyecto::where('intIdRuta', '=', $intIdRuta)
                    ->update([
                'varDescrip' => $regi_descr,
                'usua_modi' => $nomb_usuario,
                'hora_modi' => $current_date = date('Y/m/d H:i:s')
            ]);

            if ($v_operacion == 1) {

                return $this->successResponse($validar);
            } else {
                $validar['mensaje'] = "Eliminado.";
                return $this->successResponse($validar);
            }
        } else {
            return $this->successResponse($validar);
        }
    }

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/store_asig_ruta",
     *    tags={"Gestion Rutas"},
     *     summary="Store para asignación de ruta",
     *     @OA\Parameter(
     *         description="ingresar el id proyectos",
     *         in="path",
     *         name="intIdProy",
     *          example="126",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingresar el tipo de producto",
     *         in="path",
     *         name="intIdTipoProducto",
     *         example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *       @OA\Parameter(
     *         description="Ingresar el id del elemento",
     *         in="path",
     *         name="intIdEleme",
     *         example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="Ingresar la etapa anterior",
     *         in="path",
     *         name="intIdEtapaAnte",
     *         example="2",
     *         required=false,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *         @OA\Parameter(
     *         description="Ingresar el id de la nueva ruta",
     *         in="path",
     *         name="v_IdNuevaRuta",
     *         example="2",
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
     *                     property="intIdProy",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
     *                  @OA\Property(
     *                     property="intIdEleme",
     *                     type="string"
     *                 ) ,
     *                  @OA\Property(
     *                     property="v_IdNuevaRuta",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProy": "126","intIdTipoProducto":"1","intIdEleme":"1","intIdEtapaAnte":"2"
     *                         ,"v_IdNuevaRuta":"2"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Store para asignación de ruta"
     *     ),
     *    
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    //Store para asignación de ruta: sp_rutaprodu_V02
    public function store_asig_ruta(Request $request) {
        //$validar = array('mensaje' => '');

        $validar = array('mensaje' => Array());
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255', //     Idproyecto
            'intIdEleme' => 'required', // TipoProducto
            // 'intIdEtapaAnte' => 'required|max:255', //  IdRuta a modificar
            'v_IdNuevaRuta' => 'required|max:255'
                //  'v_mensaje'=>'required|max:255',
        ];
        $this->validate($request, $regla);
        $intIdProy = $request->input('intIdProy');
        $intIdTipoProducto = $request->input('intIdTipoProducto');
        $intIdEleme = json_decode($request->input('intIdEleme'));
        $v_IdNuevaRuta = $request->input('v_IdNuevaRuta');
        $todo_mensaje = [];
        $fila = 0;
        $prev = "";
        $next = "";
        $anterior = "";
        /* BUSCAMOS LA RUTA PARA OBTENER EL ORDEN AL CUAL SE ASIGNARA */
        $list_ruta = Detalleruta::join('asig_etap_proy', 'deta_ruta.intIdAsigEtapProy', '=', 'asig_etap_proy.intIdAsigEtapProy')
                ->join('etapa', 'etapa.intIdEtapa', '=', 'asig_etap_proy.intIdEtapa')
                ->where('deta_ruta.intIdRuta', '=', $v_IdNuevaRuta)
                ->select('deta_ruta.intIdRuta', 'deta_ruta.intIdAsigEtapProy', 'asig_etap_proy.intOrden', 'etapa.intIdEtapa', 'etapa.varDescEtap')
                ->orderByRaw('intOrden')
                ->get();

        foreach ($list_ruta as $k => $v) {

            $fila = $fila + 1;

            if ($fila == 1) {
                if (isset($list_ruta[$k]['intIdEtapa'])) {
                    $prev = $list_ruta[$k]['intIdEtapa'];
                } else {
                    $prev = 0;
                }
                if (isset($list_ruta[$k + 1]['intIdEtapa'])) {
                    $next = $list_ruta[$k + 1]['intIdEtapa'];
                } else {
                    $next = 0;
                }

                if (isset($list_ruta[$k - 1]['intIdEtapa'])) {
                    $anterior = $list_ruta[$k - 1]['intIdEtapa'];
                } else {
                    $anterior = 0;
                }
                break;
            }
        }

        foreach ($intIdEleme as $k) {
            $id_elem = $k->Id;
            $id_etap_ante = $k->IdAnterior;
            $codi_elem = $k->Codigo;
            $seri_elem = $k->Serie;

            if ($id_etap_ante == 0 || $id_etap_ante == '') {
                Elemento::where('intIdEleme', '=', $id_elem)
                        ->update([
                            'intIdRuta' => $v_IdNuevaRuta,
                            'intIdEtapa' => $prev,
                            'intIdEtapaSiguiente' => $next
                ]);
            } else {
                DB::select('CALL sp_rutaprodu_V02(?,?,?,?,?,@mensaje)', array(
                    $intIdProy,
                    $intIdTipoProducto,
                    $id_elem,
                    $id_etap_ante,
                    $v_IdNuevaRuta
                ));
                $results = DB::select('select @mensaje');
                //  dd($results[0]->{"@mensaje"});
                if ($results[0]->{"@mensaje"} === "" || $results[0]->{"@mensaje"} === null) {
                    
                } else {
                    array_push($todo_mensaje, $results[0]->{"@mensaje"} . ' Codigo ' . $codi_elem . ' Serie  ' . $seri_elem);
                }
            }
        }
        /*
          for ($i = 0; $i < count($intIdEleme); $i++) {

          $id_elem = $intIdEleme[$i]->Id;
          $id_etap_ante = $intIdEleme[$i]->IdAnterior;
          $codi_elem = $intIdEleme[$i]->Codigo;
          $seri_elem = $intIdEleme[$i]->Serie;
          if ($id_etap_ante == 0 || $id_etap_ante == '') {
          Elemento::where('intIdEleme', '=', $id_elem)
          ->update([
          'intIdRuta' => $v_IdNuevaRuta,
          'intIdEtapa' => $prev,
          'intIdEtapaSiguiente' => $next
          ]);
          } else {
          DB::select('CALL sp_rutaprodu_V02(?,?,?,?,?,@mensaje)', array(
          $intIdProy,
          $intIdTipoProducto,
          $id_elem,
          $id_etap_ante,
          $v_IdNuevaRuta
          ));
          $results = DB::select('select @mensaje');
          //  dd($results[0]->{"@mensaje"});
          array_push($todo_mensaje, $results[0]->{"@mensaje"} . ' Codigo ' . $codi_elem . ' Serie  ' . $seri_elem);
          }

          }
         */
        /* respuestas */
        if ($id_etap_ante == 0 || $id_etap_ante == '') {
            return $this->successResponse("");
        } else {
            return $this->successResponse($todo_mensaje);
        }
    }

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/list_tare_asoc_proy_sin_array",
     *       tags={"Gestion Rutas"},
     *     summary="obtiene datos del usuario a través del dni",
     *     @OA\Parameter(
     *         description="Ingrese el id del proyecto",
     *         in="path",
     *         name="intIdProy",
     *     example="126",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="Ingrese el id TipoProducto",
     *         in="path",
     *         name="intIdTipoProducto",
     *      example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="Ingrese el id del proyecto zona",
     *         in="path",
     *         name="intIdProyZona",
     *         example="-1",
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
     *                     property="intIdProyZona",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProy": "126","intIdTipoProducto":"1","intIdProyZona":"-1"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="lista todos"
     *     ),

     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function list_tare_asoc_proy_sin_array(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'intIdProyZona' => 'required|max:255',
        ];
        $this->validate($request, $regla);

        $intIdProy = (int) $request->input('intIdProy');
        $intIdTipoProducto = (int) $request->input('intIdTipoProducto');
        $intIdProyZona = $request->input('intIdProyZona');

        if ($intIdProyZona == "-1") {

            $result = DB::select("SELECT intIdProyTarea,varDescripTarea FROM proyecto_tarea where intIdProy = '$intIdProy' and intIdTipoProducto= '$intIdTipoProducto' ");
            $dato_todo = ['intIdProyTarea' => -1, 'varDescripTarea' => 'TODOS'];
            array_push($result, $dato_todo);
            return $this->successResponse($result);
        } else {

            $result = DB::select("SELECT intIdProyTarea,varDescripTarea FROM proyecto_tarea where intIdProy = '$intIdProy' and intIdTipoProducto= '$intIdTipoProducto'  and intIdProyZona='$intIdProyZona'");
            $dato_todo = ['intIdProyTarea' => -1, 'varDescripTarea' => 'TODOS'];
            array_push($result, $dato_todo);
            return $this->successResponse($result);
        }
    }

    public function obte_etapa_medi_ruta(Request $request) {
        $regla = [
            'intIdRuta' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdRuta = $request->input('intIdRuta');
        $obten_etapa_id_ruta = Detalleruta::join('asig_etap_proy', 'deta_ruta.intIdAsigEtapProy', '=', 'asig_etap_proy.intIdAsigEtapProy')
                        ->join('etapa', 'etapa.intIdEtapa', '=', 'asig_etap_proy.intIdEtapa')
                        ->where('deta_ruta.intIdRuta', '=', $intIdRuta)
                        ->select('etapa.varDescEtap', 'etapa.intIdEtapa')->orderBy('asig_etap_proy.intOrden', 'ASC')->get();
        return $this->successResponse($obten_etapa_id_ruta);
    }

    public function validar_ruta(Request $request) {
        $regla = [
            'intIdRuta' => 'required|max:255',
            'varNombre',
            'intIdProy',
            'usua_modi'
        ];
        $this->validate($request, $regla);
        $intIdRuta = (int) $request->input('intIdRuta');
        $intIdProy = (int) $request->input('intIdProy');
        $varNombre = strtoupper($request->input('varNombre'));
        $usua_modi = $request->input('usua_modi');
        date_default_timezone_set('America/Lima'); // CDT
        $nombre = DB::select("select varNombre,intIdRuta from ruta  where varNombre='$varNombre' and intIdProy=$intIdProy");
        if (count($nombre) === 0) {
            Rutaproyecto::where('intIdRuta', '=', $intIdRuta)->update(['varNombre' => $varNombre, 'usua_modi' => $usua_modi, 'hora_modi' => $current_date = date('Y/m/d H:i:s')]);
            $respuesta = '';
            return $this->successResponse($respuesta);
        } else if ($nombre[0]->{'intIdRuta'} == $intIdRuta) {
            Rutaproyecto::where('intIdRuta', '=', $intIdRuta)->update(['varNombre' => $varNombre, 'usua_modi' => $usua_modi, 'hora_modi' => $current_date = date('Y/m/d H:i:s')]);
            $respuesta = '';
            return $this->successResponse($respuesta);
        } else {
            $respuesta = $nombre[0]->{'varNombre'};
            return $this->successResponse($respuesta);
        }
    }

}
