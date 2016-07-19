<?php
//BindEvents Method @1-E0DEDC1E
function BindEvents()
{
    global $CCSEvents;
    $CCSEvents["BeforeShow"] = "Page_BeforeShow";
    $CCSEvents["AfterInitialize"] = "Page_AfterInitialize";
}
//End BindEvents Method

//Page_BeforeShow @1-D8BD2467
function Page_BeforeShow()
{
    $Page_BeforeShow = true;
//End Page_BeforeShow

//Custom Code @2-7C70C8B9
// -------------------------
    global $ValidaUmedida;
	include_once ("../LibPhp/ValLib.php");
	fAplicaBusqueda();
// -------------------------
//End Custom Code

//Close Page_BeforeShow @1-4BC230CD
    return $Page_BeforeShow;
}
//End Close Page_BeforeShow

//Page_AfterInitialize @1-7910B30B
function Page_AfterInitialize()
{
    $Page_AfterInitialize = true;
//End Page_AfterInitialize

//Custom Code @3-7C70C8B9
// -------------------------
    global $ValidaUmedida;
							
// -------------------------
//End Custom Code

//Close Page_AfterInitialize @1-379D319D
    return $Page_AfterInitialize;
}
//End Close Page_AfterInitialize
function CallCustomerSearch() {
	global $Tpl;
	$SearchField = CCGetParam("SearchField","");
	$Wb = "CallSearchWindow('".$SearchField."')";
	$Tpl->SetVar("WindowBehavior",$Wb);
}



?>
