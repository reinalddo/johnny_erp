<?php
//BindEvents Method @1-7F839D97
function BindEvents()
{
    global $cjas_mant;
    $cjas_mant->CCSEvents["BeforeShow"] = "cjas_mant_BeforeShow";
}
//End BindEvents Method

//cjas_mant_BeforeShow @63-9A259F06
function cjas_mant_BeforeShow()
{
    $cjas_mant_BeforeShow = true;
//End cjas_mant_BeforeShow

//Custom Code @90-F24E69CC
// -------------------------
    global $cjas_mant;
 	if (!$cjas_mant->EditMode)  $cjas_mant->lbTitulo->SetValue("NUEVO TIPO DE CAJA");
	else $cjas_mant->lbTitulo->SetValue("MODIFICAR " . $cjas_mant->caj_Descripcion->Value);
	// -------------------------
//End Custom Code

//Close cjas_mant_BeforeShow @63-F986356E
    return $cjas_mant_BeforeShow;
}
//End Close cjas_mant_BeforeShow


?>
