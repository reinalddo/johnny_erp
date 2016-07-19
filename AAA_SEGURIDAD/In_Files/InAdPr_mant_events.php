<?php
//BindEvents Method @1-1796CE3F
function BindEvents()
{
    global $invprocesos;
    $invprocesos->CCSEvents["BeforeShow"] = "invprocesos_BeforeShow";
}
//End BindEvents Method

//invprocesos_BeforeShow @3-B4D2F94A
function invprocesos_BeforeShow()
{
    $invprocesos_BeforeShow = true;
//End invprocesos_BeforeShow

//Custom Code @68-1303951C
// -------------------------
    global $invprocesos;
	$invprocesos->pro_Descripcion->SetValue(CCGEtParam("pro_Descripcion", false));
	if (!CCGetParam("pro_codProceso", false)) $invprocesos->Visible = false;
// -------------------------
//End Custom Code

//Close invprocesos_BeforeShow @3-DEA6BE0A
    return $invprocesos_BeforeShow;
}
//End Close invprocesos_BeforeShow


?>
