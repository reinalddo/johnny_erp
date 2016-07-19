<?php
//BindEvents Method @1-7E290B79
function BindEvents()
{
    global $genvarproceso;
    $genvarproceso->CCSEvents["BeforeShow"] = "genvarproceso_BeforeShow";
}
//End BindEvents Method

//genvarproceso_BeforeShow @2-C226D971
function genvarproceso_BeforeShow()
{
    $genvarproceso_BeforeShow = true;
//End genvarproceso_BeforeShow

//Custom Code @20-E4AAE929
// -------------------------
    global $genvarproceso;
	if ($genvarproceso->EditMode)
		$genvarproceso->lbTitulo->SetValue("MODIFICAR VARIABLE DE PROCESO");
// -------------------------
//End Custom Code

//Close genvarproceso_BeforeShow @2-85895589
    return $genvarproceso_BeforeShow;
}
//End Close genvarproceso_BeforeShow


?>
