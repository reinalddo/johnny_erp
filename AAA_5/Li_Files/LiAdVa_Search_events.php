<?php
//BindEvents Method @1-B955F03B
function BindEvents()
{
    global $varproceso_list;
    $varproceso_list->genvarproceso_genparametr_TotalRecords->CCSEvents["BeforeShow"] = "varproceso_list_genvarproceso_genparametr_TotalRecords_BeforeShow";
}
//End BindEvents Method

//varproceso_list_genvarproceso_genparametr_TotalRecords_BeforeShow @59-41AEFBCF
function varproceso_list_genvarproceso_genparametr_TotalRecords_BeforeShow()
{
    $varproceso_list_genvarproceso_genparametr_TotalRecords_BeforeShow = true;
//End varproceso_list_genvarproceso_genparametr_TotalRecords_BeforeShow

//Retrieve number of records @60-9F6CBF17
    global $varproceso_list;
    $varproceso_list->genvarproceso_genparametr_TotalRecords->SetValue($varproceso_list->ds->RecordsCount);
//End Retrieve number of records

//Close varproceso_list_genvarproceso_genparametr_TotalRecords_BeforeShow @59-77350DE2
    return $varproceso_list_genvarproceso_genparametr_TotalRecords_BeforeShow;
}
//End Close varproceso_list_genvarproceso_genparametr_TotalRecords_BeforeShow

//DEL  // -------------------------
//DEL      global $varproceso_list;
//DEL  	echo "con: " . $varproceso_list->ds->SQL . $varproceso_list->ds->Where;
//DEL  	// -------------------------


//DEL  // -------------------------
//DEL      global $genvarproceso_genparametr;
//DEL      echo 
//DEL  // -------------------------

?>
