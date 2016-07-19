<?php
//BindEvents Method @1-D9186BF4
function BindEvents()
{
    global $liqrubros;
    $liqrubros->CCSEvents["BeforeShow"] = "liqrubros_BeforeShow";
}
//End BindEvents Method

//liqrubros_BeforeShow @2-DBE3D7F2
function liqrubros_BeforeShow()
{
    $liqrubros_BeforeShow = true;
//End liqrubros_BeforeShow

//Custom Code @45-8939FA02
// -------------------------
    global $liqrubros;
	if ($liqrubros->EditMode) $liqrubros->lbTitulo->SetValue("MODIFICACION DE RUBRO");
// -------------------------
//End Custom Code

//Close liqrubros_BeforeShow @2-F3BE319E
    return $liqrubros_BeforeShow;
}
//End Close liqrubros_BeforeShow

//DEL  // -------------------------
//DEL  // -------------------------



?>
