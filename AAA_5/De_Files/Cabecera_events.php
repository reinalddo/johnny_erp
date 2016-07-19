<?php
// //Events @1-F81417CB

//Cabecera_BeforeShow @1-40EA9197
function Cabecera_BeforeShow()
{
    $Cabecera_BeforeShow = true;
//End Cabecera_BeforeShow

//Custom Code @2-8B14D144
// -------------------------
    global $Cabecera;
	session_set_cookie_params ( 0, "/", $_SERVER["HTTP_HOST"] );
	$Cabecera->lbUsuario->SetValue(fTraeUsuario());
// -------------------------
//End Custom Code

//Close Cabecera_BeforeShow @1-E976FE02
    return $Cabecera_BeforeShow;
}
//End Close Cabecera_BeforeShow

//Cabecera_AfterInitialize @1-3B2C5CF9
function Cabecera_AfterInitialize()
{
    $Cabecera_AfterInitialize = true;
//End Cabecera_AfterInitialize

//Custom Code @3-8B14D144
// -------------------------

    global $Cabecera;
   	include_once (RelativePath . "/LibPhp/SegLib.php") ;
	if (!fValidAcceso("","","")) {
    	fMensaje ("UD. NO TIENE ACCESO A ESTE MODULO", 1);
        die();
		}
// -------------------------
//End Custom Code

//Close Cabecera_AfterInitialize @1-942C5F05
    return $Cabecera_AfterInitialize;
}
//End Close Cabecera_AfterInitialize

//Cabecera_OnInitializeView @1-8FF15B92
function Cabecera_OnInitializeView()
{
    $Cabecera_OnInitializeView = true;
//End Cabecera_OnInitializeView

//Custom Code @4-8B14D144
// -------------------------
   global $Cabecera;
    if (isset($_SESSION["g_empr"])) $Cabecera->lbEmpresa->SetValue($_SESSION["g_empr"]);
    else $Cabecera->lbEmpresa->SetValue("????????");
    $Cabecera->lbFecha->SetValue(date("F j, Y, g:i a"));
// -------------------------
//End Custom Code

//Close Cabecera_OnInitializeView @1-7FA8292A
    return $Cabecera_OnInitializeView;
}
//End Close Cabecera_OnInitializeView


?>
