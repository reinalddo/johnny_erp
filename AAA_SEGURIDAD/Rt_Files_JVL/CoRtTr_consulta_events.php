<?php
//BindEvents Method @1-16DEBECD
function BindEvents()
{
    global $transacc_list;
    $transacc_list->concomprobantes_TotalRecords->CCSEvents["BeforeShow"] = "transacc_list_concomprobantes_TotalRecords_BeforeShow";
    $transacc_list->CCSEvents["BeforeShow"] = "transacc_list_BeforeShow";
    $transacc_list->ds->CCSEvents["BeforeExecuteSelect"] = "transacc_list_ds_BeforeExecuteSelect";
}
//End BindEvents Method

//transacc_list_concomprobantes_TotalRecords_BeforeShow @12-9C4D8E84
function transacc_list_concomprobantes_TotalRecords_BeforeShow()
{
    $transacc_list_concomprobantes_TotalRecords_BeforeShow = true;
//End transacc_list_concomprobantes_TotalRecords_BeforeShow

//Retrieve number of records @13-DA0D5AC8
    global $transacc_list;
    $transacc_list->concomprobantes_TotalRecords->SetValue($transacc_list->ds->RecordsCount);
//End Retrieve number of records

//Close transacc_list_concomprobantes_TotalRecords_BeforeShow @12-93B74466
    return $transacc_list_concomprobantes_TotalRecords_BeforeShow;
}
//End Close transacc_list_concomprobantes_TotalRecords_BeforeShow

//transacc_list_BeforeShow @2-9896A6F9
function transacc_list_BeforeShow()
{
    $transacc_list_BeforeShow = true;
//End transacc_list_BeforeShow

//Custom Code @53-D3784E25
// -------------------------
    global $transacc_list;
// -------------------------
//End Custom Code

//Close transacc_list_BeforeShow @2-AE48BBAB
    return $transacc_list_BeforeShow;
}
//End Close transacc_list_BeforeShow

//transacc_list_ds_BeforeExecuteSelect @2-84380718
function transacc_list_ds_BeforeExecuteSelect()
{
    $transacc_list_ds_BeforeExecuteSelect = true;
//End transacc_list_ds_BeforeExecuteSelect

//Custom Code @66-D3784E25
// -------------------------
    global $transacc_list;
	global $DBdatos;
	if (strlen($_GET['s_com_FecTrans']) > 0){
		$slCon = " AND " . fAnalizador('d', $_GET['s_com_FecTrans'], 'com_FecContab');
		}
	else $slCon= " ";
	$transacc_list->ds->SQL 	 = str_replace ("{cond_fecha}", $slCon, $transacc_list->ds->SQL);
	$transacc_list->ds->CountSQL = str_replace ("{cond_fecha}", $slCon, $transacc_list->ds->CountSQL);
	$slSQL = str_replace ("COUNT(*)", " SUM(tmp_BaseImp) ", $transacc_list->ds->CountSQL);
	$flValor = CCGetDBValue(CCBuildSQL($slSQL, $transacc_list->Where, ""), $DBdatos);
	$transacc_list->lbl_Imponible->SetValue($flValor);
	$slSQL = str_replace ("COUNT(*)", " SUM(tmp_Valreten) ", $transacc_list->ds->CountSQL);
	$flValor = CCGetDBValue(CCBuildSQL($slSQL, $transacc_list->Where, ""), $DBdatos);
	$transacc_list->lbl_Retenido->SetValue($flValor);
// -------------------------
//End Custom Code

//Close transacc_list_ds_BeforeExecuteSelect @2-6BE37E4A
    return $transacc_list_ds_BeforeExecuteSelect;
}
//End Close transacc_list_ds_BeforeExecuteSelect
?>
