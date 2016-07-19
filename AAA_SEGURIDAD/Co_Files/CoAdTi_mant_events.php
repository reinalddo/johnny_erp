<?php
//BindEvents Method @1-662699A8
function BindEvents()
{
    global $CoAdTi_man;
    $CoAdTi_man->CCSEvents["BeforeShow"] = "CoAdTi_man_BeforeShow";
}
//End BindEvents Method

//CoAdTi_man_BeforeShow @37-68172534
function CoAdTi_man_BeforeShow()
{
    $CoAdTi_man_BeforeShow = true;
//End CoAdTi_man_BeforeShow

//Custom Code @90-75EC7482
// -------------------------
    global $CoAdTi_man;
	if ($CoAdTi_man->EditMode) 	$CoAdTi_man->lbTitulo->SetValue("MODIFICANDO TIPOS DE TRANSACCIONES:");
    else  						$CoAdTi_man->lbTitulo->SetValue("AGREGANDO TIPOS DE TRANSACCIONES:     ");
// -------------------------
//End Custom Code

//Close CoAdTi_man_BeforeShow @37-B4BAA8D2
    return $CoAdTi_man_BeforeShow;
}
//End Close CoAdTi_man_BeforeShow

?>
