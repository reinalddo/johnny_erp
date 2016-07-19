<?php
//BindEvents Method @1-93248007
function BindEvents()
{
    global $liqprecaja_qry;
    global $liqprecaja_list;
    $liqprecaja_qry->Button_DoSearch->CCSEvents["OnClick"] = "liqprecaja_qry_Button_DoSearch_OnClick";
    $liqprecaja_list->CCSEvents["BeforeShow"] = "liqprecaja_list_BeforeShow";
    $liqprecaja_list->CCSEvents["BeforeShowRow"] = "liqprecaja_list_BeforeShowRow";
    $liqprecaja_list->ds->CCSEvents["BeforeExecuteInsert"] = "liqprecaja_list_ds_BeforeExecuteInsert";
}
//End BindEvents Method

//liqprecaja_qry_Button_DoSearch_OnClick @52-12F8E05B
function liqprecaja_qry_Button_DoSearch_OnClick()
{
    $liqprecaja_qry_Button_DoSearch_OnClick = true;
//End liqprecaja_qry_Button_DoSearch_OnClick

//Custom Code @246-5120EEDA
// -------------------------
    global $liqprecaja_qry;
	
 	$_SESSION['anio']=$liqprecaja_qry->pre_Ano->GetValue();
 	$_SESSION['semana']=$liqprecaja_qry->pre_Semana->GetValue();
// -------------------------
//End Custom Code

//Close liqprecaja_qry_Button_DoSearch_OnClick @52-9CDA5B50
    return $liqprecaja_qry_Button_DoSearch_OnClick;
}
//End Close liqprecaja_qry_Button_DoSearch_OnClick

//liqprecaja_list_BeforeShow @2-85E0F113
function liqprecaja_list_BeforeShow()
{
    $liqprecaja_list_BeforeShow = true;
//End liqprecaja_list_BeforeShow

//Custom Code @185-618651A3
// -------------------------
    global $liqprecaja_list;
	if ($liqprecaja_list->ds->AbsolutePage < $liqprecaja_list->ds->PageCount())  $liqprecaja_list->EmptyRows = 0;
	if (ccGetParam("pre_Ano", 0) == 0 OR ccGetParam("pre_Semana", 0) == 0 OR ccGetParam("pre_Semana", 0) >  99999) {
		$liqprecaja_list->Visible = false;
		}

// -------------------------
//End Custom Code

//Close liqprecaja_list_BeforeShow @2-639392DE
    return $liqprecaja_list_BeforeShow;
}
//End Close liqprecaja_list_BeforeShow

//liqprecaja_list_BeforeShowRow @2-F23283C1
function liqprecaja_list_BeforeShowRow()
{
    $liqprecaja_list_BeforeShowRow = true;
//End liqprecaja_list_BeforeShowRow

//Custom Code @231-618651A3
// -------------------------
    global $liqprecaja_list;

// -------------------------
//End Custom Code

//Close liqprecaja_list_BeforeShowRow @2-7FADFD69
    return $liqprecaja_list_BeforeShowRow;
}
//End Close liqprecaja_list_BeforeShowRow

//liqprecaja_list_ds_BeforeExecuteInsert @2-999420C6
function liqprecaja_list_ds_BeforeExecuteInsert()
{
    $liqprecaja_list_ds_BeforeExecuteInsert = true;
//End liqprecaja_list_ds_BeforeExecuteInsert

//Custom Code @234-618651A3
// -------------------------
// -------------------------
//End Custom Code

//Close liqprecaja_list_ds_BeforeExecuteInsert @2-31FB50F0
    return $liqprecaja_list_ds_BeforeExecuteInsert;
}
//End Close liqprecaja_list_ds_BeforeExecuteInsert

?>
