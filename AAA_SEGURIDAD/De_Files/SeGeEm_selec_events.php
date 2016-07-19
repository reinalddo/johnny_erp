<?php
//BindEvents Method @1-18C16C85
function BindEvents()
{
    global $segempresas;
    global $CCSEvents;
    $segempresas->lbUsuario->CCSEvents["BeforeShow"] = "segempresas_lbUsuario_BeforeShow";
    $CCSEvents["OnInitializeView"] = "Page_OnInitializeView";
}
//End BindEvents Method

//segempresas_lbUsuario_BeforeShow @51-E5A924FE
function segempresas_lbUsuario_BeforeShow()
{
    $segempresas_lbUsuario_BeforeShow = true;
//End segempresas_lbUsuario_BeforeShow

//Custom Code @53-AACCE278
// -------------------------
    global $segempresas;
    $segempresas->lbUsuario->SetValue($_SERVER["g_user"]);
// -------------------------
//End Custom Code

//Close segempresas_lbUsuario_BeforeShow @51-3B08D825
    return $segempresas_lbUsuario_BeforeShow;
}
//End Close segempresas_lbUsuario_BeforeShow

//DEL  // -------------------------
//DEL      global $segempresas;
//DEL  
//DEL  // -------------------------

//Page_OnInitializeView @1-493DD3AA
function Page_OnInitializeView()
{
    $Page_OnInitializeView = true;
//End Page_OnInitializeView

//Custom Code @63-5541D903
// -------------------------

	$slEmpresa = CCGetParam('pDe', '');
	$slDataBase = CCGetParam('pBd', '');
	$slRetorno = CCGetParam("p2", $_SERVER['PROTOCOL']."://". $_SERVER['HTTP_HOST'] . "/AAA/AAA_SEGURIDAD");
	$slUsuario = CCGetParam("p1", '');

	$_SERVER["g_empr"]  = $slEmpresa;
	$_SERVER["g_dbase"] = $slDataBase;
/*
	$_SERVER["g_user"] = $slUsuario;

	$_COOKIE["g_empr"] = $slEmpresa;
    $_COOKIE["g_dbase"] = $slDataBase;
*/
//    if (strlen($slEmpresa) > 1) {
	if (CCGetParam('pProc', '0') == 1) {
//			header("Location:  " . $slRetorno );
//			header("Location: " . $_SERVER['PROTOCOL']. "://".$_SERVER['HTTP_HOST'] . "/" .$slRetorno);
		}
	else {
	}	
		echo "<br> pProc " . (CCGetParam('pProc', '0'));
		echo "<br>" . "slusu:  " .		$slUsuario;
		echo "<br>" . "g USER:  " .		$_SERVER["g_user"];
		echo "<br>" . "Referer:  " .	$_SERVER["HTML_REFERER"];
		echo "<br> EMPRESA : " .		$_SERVER["g_empr"];
		echo "<br> BASE DE DATOS: " . 	$_SERVER["g_dbase"];
		echo "<br> RET; " .				$slRetorno;
		echo "<br> EMPRESA URL; " .		$slEmpresa;
		echo "<br> DESCR URL; " .		$slDataBase;

//		phpinfo();
		// -------------------------
//End Custom Code
//Close Page_OnInitializeView @1-81DF8332
    return $Page_OnInitializeView;
}
//End Close Page_OnInitializeView

?>
