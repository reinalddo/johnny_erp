<?php
//BindEvents Method @1-69DB0148
function BindEvents()
{
    global $liqbuques;
    $liqbuques->CCSEvents["BeforeShow"] = "liqbuques_BeforeShow";
}
//End BindEvents Method

//DEL  // -------------------------
//DEL      global $liqbuques;
//DEL  	$Redirect = "..
//DEL  // -------------------------

//liqbuques_BeforeShow @2-5CEBF15B
function liqbuques_BeforeShow()
{
    $liqbuques_BeforeShow = true;
//End liqbuques_BeforeShow

//Custom Code @15-4AA786FA
// -------------------------
    global $liqbuques;
    if ($liqbuques->EditMode) $liqbuques->lbTitulo->SetValue("MODIFICACION DE BUQUE / VAPOR");
// -------------------------
//End Custom Code

//Close liqbuques_BeforeShow @2-3EC14D0E
    return $liqbuques_BeforeShow;
}
//End Close liqbuques_BeforeShow


?>
