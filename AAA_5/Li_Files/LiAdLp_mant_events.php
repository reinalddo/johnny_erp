<?php
//BindEvents Method @1-F9CCB056
function BindEvents()
{
    global $lispredetal;
    $lispredetal->conactivos_invprecios_gen_TotalRecords->CCSEvents["BeforeShow"] = "lispredetal_conactivos_invprecios_gen_TotalRecords_BeforeShow";
    $lispredetal->CCSEvents["BeforeShow"] = "lispredetal_BeforeShow";
}
//End BindEvents Method

//lispredetal_conactivos_invprecios_gen_TotalRecords_BeforeShow @49-FACCFA20
function lispredetal_conactivos_invprecios_gen_TotalRecords_BeforeShow()
{
    $lispredetal_conactivos_invprecios_gen_TotalRecords_BeforeShow = true;
//End lispredetal_conactivos_invprecios_gen_TotalRecords_BeforeShow

//Retrieve number of records @50-0C5CD522
    global $lispredetal;
    $lispredetal->conactivos_invprecios_gen_TotalRecords->SetValue($lispredetal->ds->RecordsCount);
//End Retrieve number of records

//Close lispredetal_conactivos_invprecios_gen_TotalRecords_BeforeShow @49-669A731D
    return $lispredetal_conactivos_invprecios_gen_TotalRecords_BeforeShow;
}
//End Close lispredetal_conactivos_invprecios_gen_TotalRecords_BeforeShow

//lispredetal_BeforeShow @2-B88E4942
function lispredetal_BeforeShow()
{
    $lispredetal_BeforeShow = true;
//End lispredetal_BeforeShow

//Custom Code @271-C4755B3D
// -------------------------
    global $lispredetal;
	if (!CCGetParam("s_LisPrecios", false)) $lispredetal->Visible = false;
// -------------------------
//End Custom Code

//Close lispredetal_BeforeShow @2-B7225396
    return $lispredetal_BeforeShow;
}
//End Close lispredetal_BeforeShow

//DEL  // -------------------------
//DEL      global $lispredetal;
//DEL  	echo $lispredetal->ds->SQL . " WHERE " . $lispredetal->ds->Where;
//DEL  	
//DEL  // -------------------------

?>
