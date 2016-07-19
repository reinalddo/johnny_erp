<?php
//BindEvents Method @1-470561B5
function BindEvents()
{
    global $liqcomponent;
    $liqcomponent->CCSEvents["BeforeShow"] = "liqcomponent_BeforeShow";
}
//End BindEvents Method

//liqcomponent_BeforeShow @2-20E7EBF9
function liqcomponent_BeforeShow()
{
    $liqcomponent_BeforeShow = true;
//End liqcomponent_BeforeShow

//Custom Code @17-90476087
// -------------------------
    global $liqcomponent;
	if ($liqcomponent->EditMode)
		$liqcomponent->lbTitulo->SetValue("MODIFICACION DE COMPONENTE");
	else
		$liqcomponent->lbTitulo->SetValue("NUEVO COMPONENTE");// -------------------------
//End Custom Code

//Close liqcomponent_BeforeShow @2-5907FFE6
    return $liqcomponent_BeforeShow;
}
//End Close liqcomponent_BeforeShow


?>
