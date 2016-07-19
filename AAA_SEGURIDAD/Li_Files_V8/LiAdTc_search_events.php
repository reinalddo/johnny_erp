<?php
//BindEvents Method @1-59043103
function BindEvents()
{
    global $liqcajas_list;
    $liqcajas_list->liqcajas_genparametros_TotalRecords->CCSEvents["BeforeShow"] = "liqcajas_list_liqcajas_genparametros_TotalRecords_BeforeShow";
    $liqcajas_list->ds->CCSEvents["BeforeExecuteSelect"] = "liqcajas_list_ds_BeforeExecuteSelect";
}
//End BindEvents Method

//liqcajas_list_liqcajas_genparametros_TotalRecords_BeforeShow @89-EC6376BA
function liqcajas_list_liqcajas_genparametros_TotalRecords_BeforeShow()
{
    $liqcajas_list_liqcajas_genparametros_TotalRecords_BeforeShow = true;
//End liqcajas_list_liqcajas_genparametros_TotalRecords_BeforeShow

//Retrieve number of records @90-03B57706
    global $liqcajas_list;
    $liqcajas_list->liqcajas_genparametros_TotalRecords->SetValue($liqcajas_list->ds->RecordsCount);
//End Retrieve number of records

//Close liqcajas_list_liqcajas_genparametros_TotalRecords_BeforeShow @89-3B6D6E15
    return $liqcajas_list_liqcajas_genparametros_TotalRecords_BeforeShow;
}
//End Close liqcajas_list_liqcajas_genparametros_TotalRecords_BeforeShow

//liqcajas_list_ds_BeforeExecuteSelect @3-90DEC4D6
function liqcajas_list_ds_BeforeExecuteSelect()
{
    $liqcajas_list_ds_BeforeExecuteSelect = true;
//End liqcajas_list_ds_BeforeExecuteSelect

//Custom Code @114-98B57B3B
// -------------------------
    global $liqcajas_list;
//	echo $liqcajas_list->ds->SQL . "<br> " . $liqcajas_list->ds->Where;
	// -------------------------
//End Custom Code

//Close liqcajas_list_ds_BeforeExecuteSelect @3-6C303527
    return $liqcajas_list_ds_BeforeExecuteSelect;
}
//End Close liqcajas_list_ds_BeforeExecuteSelect


?>
