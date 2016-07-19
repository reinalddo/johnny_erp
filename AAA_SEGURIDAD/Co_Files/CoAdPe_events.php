<?php
//BindEvents Method @1-35EF59BA
function BindEvents()
{
    global $periodos;
    $periodos->CCSEvents["BeforeShow"] = "periodos_BeforeShow";
}
//End BindEvents Method

//periodos_BeforeShow @2-CB254322
function periodos_BeforeShow()
{
    $periodos_BeforeShow = true;
//End periodos_BeforeShow

//Custom Code @52-4895EE62
// -------------------------
    global $periodos;
	$slAplicacion = CCGetParam("per_Aplicacion");
	if (strlen($slAplicacion) == 0) $periodos->Visible = False;
// -------------------------
//End Custom Code

//Close periodos_BeforeShow @2-0BD20ECE
    return $periodos_BeforeShow;
}
//End Close periodos_BeforeShow

?>
