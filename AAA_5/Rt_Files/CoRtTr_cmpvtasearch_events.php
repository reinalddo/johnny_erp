<?php
//BindEvents Method @1-81461ACF
function BindEvents()
{
    global $fiscompras;
    $fiscompras->ds->CCSEvents["BeforeExecuteSelect"] = "fiscompras_ds_BeforeExecuteSelect";
    $fiscompras->CCSEvents["BeforeShow"] = "fiscompras_BeforeShow";
}
//End BindEvents Method

//fiscompras_ds_BeforeExecuteSelect @3-F299724D
function fiscompras_ds_BeforeExecuteSelect()
{
    $fiscompras_ds_BeforeExecuteSelect = true;
//End fiscompras_ds_BeforeExecuteSelect

//Custom Code @123-D7BDDFDC
// -------------------------
    global $fiscompras;
    // Write your own code here.
	if (fGetParam('s_fechaRegistro',false)) {
	 	$fiscompras->ds->CountSQL .= " AND " . fAnalizador('d', fGetParam('s_fechaRegistro',false), 'fechaRegistro', '',3) ; 
	 	$fiscompras->ds->SQL .= " AND " . fAnalizador('d', fGetParam('s_fechaRegistro',false), 'fechaRegistro', '',3) ;
	}
// -------------------------
//End Custom Code

//Close fiscompras_ds_BeforeExecuteSelect @3-79D5D61A
    return $fiscompras_ds_BeforeExecuteSelect;
}
//End Close fiscompras_ds_BeforeExecuteSelect

function fiscompras_BeforeShow()
{
    global $fiscompras, $Tpl;
	$ilRecPag = fGetParam('fiscomprasPageSize',50);
	$Tpl->SetVar('fiscomprasPageSize',$ilRecPag);
    return true;
}
?>
