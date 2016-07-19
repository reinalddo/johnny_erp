<?php
//BindEvents Method @1-AA489A73
function BindEvents()
{
    global $liqrubros_list;
    $liqrubros_list->liqrubros_r_genvarproceso_TotalRecords->CCSEvents["BeforeShow"] = "liqrubros_list_liqrubros_r_genvarproceso_TotalRecords_BeforeShow";
}
//End BindEvents Method

//liqrubros_list_liqrubros_r_genvarproceso_TotalRecords_BeforeShow @57-258F0D8F
function liqrubros_list_liqrubros_r_genvarproceso_TotalRecords_BeforeShow()
{
    $liqrubros_list_liqrubros_r_genvarproceso_TotalRecords_BeforeShow = true;
//End liqrubros_list_liqrubros_r_genvarproceso_TotalRecords_BeforeShow

//Retrieve number of records @58-30FF6BA6
    global $liqrubros_list;
    $liqrubros_list->liqrubros_r_genvarproceso_TotalRecords->SetValue($liqrubros_list->ds->RecordsCount);
//End Retrieve number of records

//Close liqrubros_list_liqrubros_r_genvarproceso_TotalRecords_BeforeShow @57-FD337104
    return $liqrubros_list_liqrubros_r_genvarproceso_TotalRecords_BeforeShow;
}
//End Close liqrubros_list_liqrubros_r_genvarproceso_TotalRecords_BeforeShow


?>
