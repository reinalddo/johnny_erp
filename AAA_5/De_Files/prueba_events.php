<?php
//BindEvents Method @1-FA3AC75D
function BindEvents()
{
    global $CCSEvents;
    $CCSEvents["OnInitializeView"] = "Page_OnInitializeView";
}
//End BindEvents Method

//Page_OnInitializeView @1-493DD3AA
function Page_OnInitializeView()
{
    $Page_OnInitializeView = true;
//End Page_OnInitializeView

//Custom Code @2-490C22E2
// -------------------------
    global $prueba;
	if (CCGetParam('pProc', '0') == 1) {
echo "1";
echo "<br>" . "USER: " .$_SERVER["g_user"];
		$slEmpresa = CCGetParam('pDe', 'X');
		$slDataBase = CCGetParam('pBd', 'X');
		$slRetorno = CCGetParam("p2", "http://".$_SERVER['HTTP_HOST'] . "/AAA/AAA_SEGURIDAD");
			$_SERVER["g_empr"] = CCGetParam('pDe', 'X');  
			$_SERVER["g_dbase"] = CCGetParam('pBd', 'X');
$_SERVER["g_user"] = 'SSSSS';
echo "<br> -" .$_SERVER["g_user"];
echo $_SERVER["g_empr"];
echo "<br>" . $_SERVER["g_dbase"];
echo "<br> RET; " .$slRetorno;
//		header("Location:  " . $slRetorno);
//		header("Location: http://".$_SERVER['HTTP_HOST'] . "/AAA/AAA_SEGURIDAD");
	}// -------------------------
//End Custom Code

//Close Page_OnInitializeView @1-81DF8332
    return $Page_OnInitializeView;
}
//End Close Page_OnInitializeView


?>
