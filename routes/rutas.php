<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$router->post('/regi_usua', 'UsuarioController@regi_usua');
$router->post('/validar_usuario', 'UsuarioController@validar_usuario');
$router->post('/actu_usua', 'UsuarioController@actu_usua');
$router->post('/elim_usua', 'UsuarioController@elim_usua');



$router->get('/list_proy_zona', 'ProyectozonaController@list_proy_zona');

//REGistrar proyecto zona
$router->post('/regi_proy_zona', 'ProyectozonaController@regi_proy_zona');
$router->post('/vali_proy_zona', 'ProyectozonaController@vali_proy_zona');
$router->post('/buscar_codiProy', 'ProyectozonaController@buscar_codiProy');

$router->post('/actu_proy_zona', 'ProyectozonaController@actu_proy_zona');
$router->post('/obtener_sub_ot', 'ProyectozonaController@obtener_sub_ot');

//proyecto tarea
$router->post('/regi_proy_tarea', 'ProyectotareaController@regi_proy_tarea');
$router->post('/vali_tarea', 'ProyectotareaController@vali_tarea');
//proyecto paquete
$router->post('/regi_proy_pque', 'ProyectopaqueteController@regi_proy_pque');
$router->post('/vali_proy_pque', 'ProyectopaqueteController@vali_proy_pque');

/* * PRIMERA VALIDACION obtener id paquete* */
$router->post('/obte_proy_paqu', 'ProyectopaqueteController@obte_proy_paqu');





//registro de elemento
$router->post('/partlist_elemento', 'ElementoController@partlist_elemento');
$router->post('/actu_elem', 'ElementoController@actu_elem');

//realizar  validacion de marca
$router->post('/vali_marcar', 'ElementoController@vali_marcar');

//validar el revision 
$router->post('/vali_revi', 'ElementoController@vali_revi');

// valida si la marca existe , VALIDAR MARCAR AL MOMENTO DE REGISTRAR 
$router->post('/vali_marcar_regi', 'ElementoController@vali_marcar_regi');
//validar cantidad ELemento
$router->post('/vali_cantidad', 'ElementoController@vali_cantidad');

//validar Tipo Grupo
$router->post('/vali_tipo_grup', 'ElementoController@vali_tipo_grup');
$router->get('/comb_tipo_grupo_wip', 'ElementoController@comb_tipo_grupo_wip');





//listar Parlist
$router->post('/list_partlist', 'PartlistController@list_partlist');
//registrar Parlist
$router->post('/regis_partlist', 'PartlistController@regis_partlist');
$router->post('/actu_partlist', 'PartlistController@actu_partlist');




//REGISTRAR COMPONENTES
$router->post('/regi_comp', 'ComponentesController@regi_comp');


$router->post('/modi_comp', 'ComponentesController@modi_comp');

$router->post('/elim_comp', 'ComponentesController@elim_comp');




//Lista de Asignar
$router->get('/List_proy_vige', 'PartlistController@List_proy_vige');
$router->post('/List_proy', 'PartlistController@List_proy');
$router->get('/list_proy_vige_6_mese', 'PartlistController@list_proy_vige_6_mese');

///REPORTE 


$router->get('/List_OT', 'PartlistController@List_OT');
$router->post('/List_tarea', 'PartlistController@List_tarea');
$router->post('/List_paqu', 'PartlistController@List_paqu');
$router->post('/List_Etap_actu', 'PartlistController@List_Etap_actu');
$router->post('/most_cant_info', 'PartlistController@most_cant_info');
$router->post('/most_seri_host_avan', 'PartlistController@most_seri_host_avan');

$router->post('/per_anua_seri', 'PartlistController@per_anua_seri');

$router->post('/vali_nomb_part_list', 'PartlistController@vali_nomb_part_list');




//•	Listar los Codigo dependiendo del paquete y codigo de proyecto.
$router->post('/list_codi_elem', 'PartlistController@list_codi_elem');

//Listar reporte de elemento

$router->post('/list_repo_elem', 'PartlistController@list_repo_elem');

$router->post('/inse_compo', 'PartlistController@inse_compo');

//$router->post('/most_canti_serie', 'PartlistController@most_canti_serie');
$router->post('/anua_seri', 'PartlistController@anua_seri');

$router->post('/list_seri', 'PartlistController@list_seri');

//validar si elemento existe en la tabla elemento


$router->post('/vali_var_codi_elem', 'PartlistController@vali_var_codi_elem');

//•	Opción: Historico, donde deberá ejecutar el siguiente store:
$router->post('/store_elim_avance', 'PartlistController@store_elim_avance');











//rutas 


$router->post('/list_ruta_proy', 'RutaController@list_ruta_proy');
$router->get('/list_tipo_prod_ruta', 'RutaController@list_tipo_prod_ruta');
$router->post('/asig_etap_proy_ruta', 'RutaController@asig_etap_proy_ruta');
$router->post('/crea_asig_ruta_proy', 'RutaController@crea_asig_ruta_proy');




//ruta

$router->post('/modi_ruta', 'RutaController@modi_ruta');

$router->post('/list_deta_ruta_cade_actu', 'RutaController@list_deta_ruta_cade_actu');


$router->post('/list_desc_ruta', 'RutaController@list_desc_ruta');

$router->post('/list_zona_asoc_proy', 'RutaController@list_zona_asoc_proy');
$router->post('/list_tare_asoc_proy', 'RutaController@list_tare_asoc_proy');
$router->post('/list_paqu_asoc_proy', 'RutaController@list_paqu_asoc_proy');
$router->post('/mues_ruta_asoc_tipo_prod', 'RutaController@mues_ruta_asoc_tipo_prod');
$router->post('/most_secu_etap_ruta_asig', 'RutaController@most_secu_etap_ruta_asig');
$router->post('/list_elem_asig_ruta', 'RutaController@list_elem_asig_ruta');
$router->post('/elem_no_tien_avan_regi', 'RutaController@elem_no_tien_avan_regi');
$router->post('/list_codi_elem_ruta', 'RutaController@list_codi_elem_ruta');
$router->post('/store_modi_ruta', 'RutaController@store_modi_ruta');
$router->post('/store_asig_ruta', 'RutaController@store_asig_ruta');

$router->post('/list_tare_asoc_proy_sin_array', 'RutaController@list_tare_asoc_proy_sin_array');
$router->post('/validar_ruta', 'RutaController@validar_ruta');



//actualizar pintura 

$router->post('/gspar_actua_pintura', 'PartlistController@gspar_actua_pintura');

$router->post('/obte_etapa_medi_ruta', 'RutaController@obte_etapa_medi_ruta');
