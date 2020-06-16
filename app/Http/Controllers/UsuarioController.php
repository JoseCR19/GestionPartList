<?php

namespace App\Http\Controllers;

use App\Usuario;
use App\UsuarioToken;
use App\Software;
use App\Programa;
use App\UsuarioPrograma;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UsuarioController extends Controller {

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

    //verificar inicio sesion
    public function veri_logi(Request $request) {

        $regla = [
            'codi_usua' => 'required|max:255',
            'clav_usua' => 'required|max:255'
        ];
        $this->validate($request, $regla);

        $usua = Usuario::where('varCodiUsua', $request->input('codi_usua'))->where('varClavUsua', '=', $request->input('clav_usua'))->first();
        if ($usua == null) {

            $regla = [
                'mensaje' => 'Usuario o Clave incorrectos.'
            ];
            return $this->successResponse($regla);
        }

        return $this->successResponse($usua, Response::HTTP_CREATED);
    }

//
    public function regi_usua(Request $request) {



        $regla = [
            'varNumeDni' => 'required|max:255',
            'varNombUsua' => 'required|max:255',
            'varApelUsua' => 'required|max:255',
            'varCodiUsua' => 'required|max:255',
            'varClavUsua' => 'required|max:255',
            'varCorrUsua' => 'required|max:255',
            'varTelfUsua' => 'required|max:255',
            'varEstaUsua' => 'required|max:255',
            'codi_usua' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        //713077008
        $rand_stri = $this->GenerateRandomCaracter();

        $usua_condi = Usuario::where('varNumeDni', '=', $request->input('varNumeDni'))->first(['varNumeDni', 'varNombUsua', 'varApelUsua']);
        if ($usua_condi != null) {
            $mensaje = [
                'mensaje' => 'DNI ya se encuentra registrado.'
            ];
            return $this->successResponse($mensaje);
        } else {
            $usua_codi = Usuario::where('varCodiUsua', $request->input('varCodiUsua'))->first(['varNumeDni', 'varNombUsua', 'varApelUsua', 'varCodiUsua']);
            if (($usua_codi['varCodiUsua']) == ($request->input('varCodiUsua'))) {
                $mensaje = [
                    'mensaje' => 'Codigo de usuario ya se encuentra registrado.'
                ];
                return $this->successResponse($mensaje);
            } else {

                $usua = UsuarioToken::create([
                            'user_id' => $request->input('varNumeDni'),
                            'name' => $request->input('varCodiUsua'),
                            'secret' => $rand_stri,
                            'redirect' => '',
                            'personal_access_client' => 1,
                            'password_client' => 0,
                            'revoked' => 0
                ]);
                $usua_token = UsuarioToken::where('user_id', $request->input('varNumeDni'))->first(['id']);
                //registramos  al usuario en la base de datos
                $cla_crifr = password_hash($request->input('varClavUsua'), PASSWORD_DEFAULT);
                date_default_timezone_set('America/Lima'); // CDT

                $registrar_usua_db = Usuario::create([
                            'varNumeDni' => $request->input('varNumeDni'),
                            'varNombUsua' => $request->input('varNombUsua'),
                            'varApelUsua' => $request->input('varApelUsua'),
                            'varCodiUsua' => $request->input('varCodiUsua'),
                            //'varClavUsua'=>$request->input('varClavUsua'),
                            'varClavUsua' => $cla_crifr,
                            'varEstaUsua' => $request->input('varEstaUsua'),
                            'varCorrUsua' => $request->input('varCorrUsua'),
                            'varTelfUsua' => $request->input('varTelfUsua'),
                            'varCodiUsua' => $request->input('varCodiUsua'),
                            'acti_usua' => $request->input('codi_usua'),
                            'acti_hora' => $current_date = date('Y/m/d H:i:s'),
                            'intIdUsuaToke' => $usua_token['id'],
                            'varSecrUsua' => $rand_stri
                ]);

                $template = file_get_contents('../App/Http/bienvenida.tpl');
                $url = "http://www.mimco.com.pe/home/";
                // $url= $usuario_codigo['varClavUsua'];

                $btn_cred = "<a href ='$url' style='background: #374960 ;color: #ffffff ;font-size: 20px;border-radius:50px'>Intranet Mimco</a>";
                //  die($btn_cred);
                $nomb_comp = $request->input('varNombUsua') . ' ' . $request->input('varApelUsua');
                $codi_usua = $request->input('varCodiUsua');
                $pass_usua = $request->input('varClavUsua');
                $corr_usua = $request->input('varCorrUsua');

                $template = str_replace(
                        array("<!-- #{Nombre} -->", "<!-- #{boton} -->", "<!-- #{codi} -->", "<!-- #{pass} -->"), array(ucwords(strtolower($nomb_comp)), $btn_cred, $codi_usua, $pass_usua), $template);
                $asun_mens = "Acceso al intranet Mimco";
                $mail = new \PHPMailer\PHPMailer\PHPMailer();
                $mail->CharSet = 'utf-8';
                $mail->SMTPAuth = true; // habilitamos la autenticaci贸n SMTP
                $mail->Username = 'punisherancajima@gmail.com';
                $mail->Password = 'Livestrong5678';
                $mail->Host = "smtp.gmail.com";      // sets GMAIL as the SMTP server
                $mail->Port = 587;
                $mail->MsgHTML($template);
                $mail->From = 'noresponder@mimco.com.pe';
                $mail->FromName = 'NO RESPONDER';
                $mail->IsHTML(true);
                $mail->Subject = $asun_mens;
                $mail->AddAddress($corr_usua);
                $mail->Send();
                /* if (!$mail->Send()) { // visualizar en consola

                  echo "error".($mail->ErrorInfo);
                  } else {
                  echo 'ok';
                  }

                 */



                $mensaje = [
                    'registro' => 'Registro satisfactorio.'
                ];
                return $this->successResponse($mensaje, Response::HTTP_CREATED);
            }
        }
    }

    function GenerateRandomCaracter($length = 32) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

//Verifica que el usuarios exista, si existe manda los datos  correspondiente de el usuario

    public function validar_usuario(Request $request) {
        $regla = [
            'varNumeDni' => 'required|max:13',
        ];
        $this->validate($request, $regla);


        $usuario = Usuario::where('varNumeDni', '=', $request->input('varNumeDni'))->first(['varNumeDni', 'varNombUsua', 'varApelUsua', 'varCodiUsua', 'varClavUsua', 'varCorrUsua', 'varTelfUsua', 'varEstaUsua']);

        if ($usuario == null) {
            $mensaje = [
                'mensaje' => 'El Documento de identidad ingresado no se encuentra registrado.'
            ];
            return $this->successResponse($mensaje);
        } else {
            return $this->successResponse($usuario);
        }
    }

// actualizamos  los datos del usuario 

    public function actu_usua(Request $request) {

        $regla = [
            'varNumeDni' => 'required|max:255',
            'varNombUsua' => 'required|max:255',
            'varApelUsua' => 'required|max:255',
            'varCodiUsua' => 'required|max:255',
            'varClavUsua' => 'required|max:255',
            'varCorrUsua' => 'required|max:255',
            'varTelfUsua' => 'required|max:255',
            'varEstaUsua' => 'required|max:255',
            'codi_usua' => 'required|max:255',
        ];



        $this->validate($request, $regla);



        date_default_timezone_set('America/Lima'); // CDT



        $usua_cond = Usuario::where('varNumeDni', $request->input('varNumeDni'))->first(['varClavUsua']);

        if (($usua_cond['varClavUsua']) == ($request->input('varClavUsua'))) {

            $usua = Usuario::where('varNumeDni', $request->input('varNumeDni'))->update([
                'varNombUsua' => $request->input('varNombUsua'),
                'varApelUsua' => $request->input('varApelUsua'),
                // 'varClavUsua'=>$cla_crifr,
                'varCorrUsua' => $request->input('varCorrUsua'),
                'varTelfUsua' => $request->input('varTelfUsua'),
                'varEstaUsua' => $request->input('varEstaUsua'),
                'usua_modi' => $request->input('codi_usua'),
                'hora_modi' => $current_date = date('Y/m/d H:i:s')
            ]);

            $mensaje = [
                'mensaje' => 'Se ha actualizado Correctamente.'
            ];

            return $this->successResponse($mensaje);
        } else {

            $clav_cifr = password_hash($request->input('varClavUsua'), PASSWORD_DEFAULT);

            $usua_upda = Usuario::where('varNumeDni', $request->input('varNumeDni'))->update([
                'varNombUsua' => $request->input('varNombUsua'),
                'varApelUsua' => $request->input('varApelUsua'),
                'varClavUsua' => $clav_cifr,
                'varCorrUsua' => $request->input('varCorrUsua'),
                'varTelfUsua' => $request->input('varTelfUsua'),
                'varEstaUsua' => $request->input('varEstaUsua'),
                'usua_modi' => $request->input('codi_usua'),
                'hora_modi' => $current_date = date('Y/m/d H:i:s')
            ]);

            $mensaje = [
                'mensaje' => 'Se ha actualizado Correctamente.'
            ];

            return $this->successResponse($mensaje);
        }
    }

    //eliminar usuario (cambiar el estado de usuario de ACT a INA)

    public function elim_usua(Request $request) {
        $regla = [
            'varNumeDni' => 'required|max:255',
            'codi_usua' => 'required|max:255'
        ];

        $this->validate($request, $regla);

        $usuario = Usuario::where('varNumeDni', $request->input('varNumeDni'))->update([
            'varEstaUsua' => 'INA',
            'usua_modi' => $request->input('codi_usua'),
            'hora_modi' => $current_date = date('Y/m/d H:i:s')
        ]);



        $mensaje = [
            'mensaje' => 'El usuario ha sido eliminado.'
        ];
        return $this->successResponse($mensaje);
    }

    //listar usuario por (DNI, usuario . nombre , apellido )
    public function list_usua() {



        $usuario = Usuario::get(['intIdUsua', 'varNumeDni', 'varCodiUsua', 'varNombUsua', 'varApelUsua', 'varEstaUsua']);
        return $this->successResponse($usuario);
    }

// sirver para eliminar los permiso de el usuario 
    public function elim_usua_prog(Request $request) {

        $regla = [
            'intIdSoft' => 'required|max:255',
            'varCodiUsua' => 'required|max:255'
        ];
        $this->validate($request, $regla);

        $sele = Usuario::where('varCodiUsua', $request->input('varCodiUsua'))->first(['varCodiUsua', 'intIdUsua']);
        $usua_dele = UsuarioPrograma::where('IntIdUsua', '=', $sele['intIdUsua'])->where('intIdSoft', $request->input('intIdSoft'))->delete();


        $mensaje = [
            'mensaje' => 'se ha eliminado los programas. '
        ];

        return $this->successResponse($mensaje);
    }

// permite registrar los permiso del usuario 
    public function regi_usua_prog(Request $request) {

        $regla = [
            // 'IntIdUsua'=>'required|max:255',
            'varCodiUsua' => 'required|max:255',
            'intIdProg' => 'required|max:255',
            'intIdSoft' => 'required|max:255',
            'acti_usua' => 'required|max:255'
        ];
        $this->validate($request, $regla);

        date_default_timezone_set('America/Lima'); // CDT

        $sele = Usuario::where('varCodiUsua', $request->input('varCodiUsua'))->first(['varCodiUsua', 'intIdUsua']);



        //   $usua_dele=UsuarioPrograma::where('IntIdUsua','=',$sele['intIdUsua'])->where('intIdSoft',$request->input('intIdSoft'))->delete();

        $usua_prog = UsuarioPrograma::create([
                    'IntIdUsua' => $sele['intIdUsua'],
                    'varCodiUsua' => $request->input('varCodiUsua'),
                    'intIdProg' => $request->input('intIdProg'),
                    'intIdSoft' => $request->input('intIdSoft'),
                    'acti_usua' => $request->input('acti_usua'),
                    'acti_hora' => $current_date = date('Y/m/d H:i:s')
        ]);

        $mensaje = [
            'mensaje' => 'Registro Satisfactorio. '
        ];

        return $this->successResponse($mensaje);
    }

    public function obte_perm(Request $request) {
        $regla = [
            'varCodiUsua' => 'required|max:255',
                // 'intIdSoft'=>'required|max:255'  //->where('intIdSoft',$request->input('intIdSoft'))
        ];
        $this->validate($request, $regla);
        $sele = Usuario::where('varCodiUsua', $request->input('varCodiUsua'))->first(['IntIdUsua', 'varNombUsua']);
        $usua_obtn = UsuarioPrograma::where('IntIdUsua', '=', $sele['IntIdUsua'])->get([
            'varCodiUsua', 'intIdProg'
        ]);

        return $this->successResponse($usua_obtn);
    }

    //copia_perfiles
    //Gestion de Usuarios
    // sirver para eliminar el perfil de un usuario 
    public function elim_usua_perf(Request $request) {

        $regla = [
            'varCodiUsua' => 'required|max:255' //
        ];
        $this->validate($request, $regla);

        $sele = Usuario::where('varCodiUsua', $request->input('varCodiUsua'))->first(['varCodiUsua', 'intIdUsua']);
        $usua_dele = UsuarioPrograma::where('IntIdUsua', '=', $sele['intIdUsua'])->delete();




        $mensaje = [
            'mensaje' => 'se ha eliminado los programas. '
        ];

        return $this->successResponse($mensaje);
    }

    public function copi_perf(Request $request) {

        $regla = [
            'varCodiUsua' => 'required|max:255',
            'IntIdUsua' => 'required|max:255',
            'varUsuaPerf' => 'required|max:255',
            'acti_usua' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $sele = Usuario::where('varCodiUsua', $request->input('varCodiUsua'))->first(['IntIdUsua', 'varNombUsua']);
        $usua_obtn = UsuarioPrograma::where('IntIdUsua', '=', $sele['IntIdUsua'])->get([
            'varCodiUsua', 'intIdProg', 'intIdSoft'
        ]);

        date_default_timezone_set('America/Lima'); // CDT

        for ($i = 0; $i < count($usua_obtn); $i++) {



            $usua_prog = UsuarioPrograma::create([
                        'IntIdUsua' => $request->input('IntIdUsua'),
                        'varCodiUsua' => $request->input('varUsuaPerf'),
                        'intIdProg' => $usua_obtn[$i]['intIdProg'],
                        'intIdSoft' => $usua_obtn[$i]['intIdSoft'],
                        'acti_usua' => $request->input('acti_usua'),
                        'acti_hora' => $current_date = date('Y/m/d H:i:s')
            ]);
        }


        // $list_soft=Software::where('intIdSoft',$request->input('intIdSoft'))->first(['varNombSoft','intIdSoft']);
        //$list_progr=Programa::where('intIdSoft',$list_soft['intIdSoft'])->get(['varNombProg']);
        $mensaje = [
            'mensaje' => 'Copia de perfil satisfactoria.'
        ];


        return $this->successResponse($mensaje);
    }

}
