<?php
//BindEvents Method @1-4F40EC0B
function BindEvents()
{
    global $liquidac_qry;
    global $liquidac_list;
    global $CCSEvents;
    $liquidac_qry->Button_DoSearch->CCSEvents["OnClick"] = "liquidac_qry_Button_DoSearch_OnClick";
    $liquidac_qry->CCSEvents["BeforeShow"] = "liquidac_qry_BeforeShow";
    $liquidac_list->tmp_res002_TotalRecords->CCSEvents["BeforeShow"] = "liquidac_list_tmp_res002_TotalRecords_BeforeShow";
    $liquidac_list->ds->CCSEvents["BeforeBuildSelect"] = "liquidac_list_ds_BeforeBuildSelect";
    $liquidac_list->ds->CCSEvents["BeforeExecuteSelect"] = "liquidac_list_ds_BeforeExecuteSelect";
    $CCSEvents["BeforeShow"] = "Page_BeforeShow";
    $CCSEvents["AfterInitialize"] = "Page_AfterInitialize";
}
//End BindEvents Method

//liquidac_qry_Button_DoSearch_OnClick @5-1A06C273
function liquidac_qry_Button_DoSearch_OnClick()
{
    $liquidac_qry_Button_DoSearch_OnClick = true;
//End liquidac_qry_Button_DoSearch_OnClick

//Custom Code @33-B366ADD7
// -------------------------
    global $liquidac_qry;
 	$_SESSION['anio']=$liquidac_qry->pro_Ano->GetValue();
 	$_SESSION['semana']=$liquidac_qry->pro_Semana->GetValue();
// -------------------------
//End Custom Code

//Close liquidac_qry_Button_DoSearch_OnClick @5-7CB21800
    return $liquidac_qry_Button_DoSearch_OnClick;
}
//End Close liquidac_qry_Button_DoSearch_OnClick

//liquidac_qry_BeforeShow @2-EEDEC2E9
function liquidac_qry_BeforeShow()
{
    $liquidac_qry_BeforeShow = true;
//End liquidac_qry_BeforeShow

//Custom Code @37-B366ADD7
// -------------------------
    global $liquidac_qry;
 	if (!isset($_SESSION["anio"])) $_SESSION["anio"] = "";
	if (!isset($_SESSION["semana"])) $_SESSION["semana"] = "";
// -------------------------
//End Custom Code

//Close liquidac_qry_BeforeShow @2-6EF69160
    return $liquidac_qry_BeforeShow;
}
//End Close liquidac_qry_BeforeShow

//liquidac_list_tmp_res002_TotalRecords_BeforeShow @8-220A1C84
function liquidac_list_tmp_res002_TotalRecords_BeforeShow()
{
    $liquidac_list_tmp_res002_TotalRecords_BeforeShow = true;
//End liquidac_list_tmp_res002_TotalRecords_BeforeShow

//Retrieve number of records @9-06BBE5F2
    global $liquidac_list;
    $liquidac_list->tmp_res002_TotalRecords->SetValue($liquidac_list->ds->RecordsCount);
//End Retrieve number of records

//Close liquidac_list_tmp_res002_TotalRecords_BeforeShow @8-8FC2F56E
    return $liquidac_list_tmp_res002_TotalRecords_BeforeShow;
}
//End Close liquidac_list_tmp_res002_TotalRecords_BeforeShow

//liquidac_list_ds_BeforeBuildSelect @7-176419FB
function liquidac_list_ds_BeforeBuildSelect()
{
    $liquidac_list_ds_BeforeBuildSelect = true;
//End liquidac_list_ds_BeforeBuildSelect

//Custom Code @34-1FD56B5D
// -------------------------
	global $DBdatos;
	global $liquidac_qry;
    global $liquidac_list;
// -------------------------
//End Custom Code

//Close liquidac_list_ds_BeforeBuildSelect @7-76E4A149
    return $liquidac_list_ds_BeforeBuildSelect;
}
//End Close liquidac_list_ds_BeforeBuildSelect

//liquidac_list_ds_BeforeExecuteSelect @7-5DF1A69A
function liquidac_list_ds_BeforeExecuteSelect()
{
    $liquidac_list_ds_BeforeExecuteSelect = true;
//End liquidac_list_ds_BeforeExecuteSelect

//Custom Code @35-1FD56B5D
// -------------------------
    global $liquidac_list;
// -------------------------
//End Custom Code

//Close liquidac_list_ds_BeforeExecuteSelect @7-24CAEA0D
    return $liquidac_list_ds_BeforeExecuteSelect;
}
//End Close liquidac_list_ds_BeforeExecuteSelect

//Page_BeforeShow @1-D8BD2467
function Page_BeforeShow()
{
    $Page_BeforeShow = true;
//End Page_BeforeShow

//Custom Code @6-48C004C9
// -------------------------

// -------------------------
//End Custom Code

//Close Page_BeforeShow @1-4BC230CD
    return $Page_BeforeShow;
}
//End Close Page_BeforeShow

//Page_AfterInitialize @1-7910B30B
function Page_AfterInitialize()
{
    $Page_AfterInitialize = true;
//End Page_AfterInitialize

//Custom Code @26-0C2A8463
// -------------------------
    global $liquidac_qry;
	global $liquidac_list;
	global $DBdatos;
	if ($liquidac_qry->pro_Semana->GetValue() <> 0 && $liquidac_qry->pro_Ano->GetValue() <> 0)
	{
/*	
			$slSql = "";
			$DBdatos->query("DROP TABLE IF EXISTS tmp_restarjas");
			$slSql = "CREATE TEMPORARY TABLE tmp_restarjas
							SELECT pro_ID, pro_anoProceso, pro_semana, tad_liqnumero, 
								   SUM(tad_cantrecibida - tad_cantrechazada  ) as tmp_cantembarcada
							FROM liqprocesos left join liqtarjadetal on  tad_liqproceso = pro_id
							WHERE pro_semana = " . $liquidac_qry->pro_Semana->GetValue() .
							" GROUP BY 1,2,3,4";
			$DBdatos->query($slSql);
*/
	}
	else $liquidac_list->Visible = false;
//End Custom Code

//Close Page_AfterInitialize @1-379D319D
    return $Page_AfterInitialize;
}
//End Close Page_AfterInitialize
?>
