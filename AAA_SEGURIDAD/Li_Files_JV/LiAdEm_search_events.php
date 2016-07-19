<?php
//BindEvents Method @1-7ADDEBC9
function BindEvents()
{
    global $liqembarques_list;
    $liqembarques_list->liqembarques_liqbuques_ge_TotalRecords->CCSEvents["BeforeShow"] = "liqembarques_list_liqembarques_liqbuques_ge_TotalRecords_BeforeShow";
}
//End BindEvents Method

//liqembarques_list_liqembarques_liqbuques_ge_TotalRecords_BeforeShow @26-D30EE6F3
function liqembarques_list_liqembarques_liqbuques_ge_TotalRecords_BeforeShow()
{
    $liqembarques_list_liqembarques_liqbuques_ge_TotalRecords_BeforeShow = true;
//End liqembarques_list_liqembarques_liqbuques_ge_TotalRecords_BeforeShow

//Retrieve number of records @27-F1DC5922
    global $liqembarques_list;
    $liqembarques_list->liqembarques_liqbuques_ge_TotalRecords->SetValue($liqembarques_list->ds->RecordsCount);
//End Retrieve number of records

//Close liqembarques_list_liqembarques_liqbuques_ge_TotalRecords_BeforeShow @26-B73F8650
    return $liqembarques_list_liqembarques_liqbuques_ge_TotalRecords_BeforeShow;
}
//End Close liqembarques_list_liqembarques_liqbuques_ge_TotalRecords_BeforeShow


?>
