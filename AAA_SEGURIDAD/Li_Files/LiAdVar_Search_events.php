<?php
//BindEvents Method @1-1D9EC77C
function BindEvents()
{
    global $genvarproceso_genparametr;
    $genvarproceso_genparametr->genvarproceso_genparametr_TotalRecords->CCSEvents["BeforeShow"] = "genvarproceso_genparametr_genvarproceso_genparametr_TotalRecords_BeforeShow";
}
//End BindEvents Method

//genvarproceso_genparametr_genvarproceso_genparametr_TotalRecords_BeforeShow @22-6C4C0BC6
function genvarproceso_genparametr_genvarproceso_genparametr_TotalRecords_BeforeShow()
{
    $genvarproceso_genparametr_genvarproceso_genparametr_TotalRecords_BeforeShow = true;
//End genvarproceso_genparametr_genvarproceso_genparametr_TotalRecords_BeforeShow

//Retrieve number of records @23-D7C1AA56
    global $genvarproceso_genparametr;
    $genvarproceso_genparametr->genvarproceso_genparametr_TotalRecords->SetValue($genvarproceso_genparametr->ds->RecordsCount);
//End Retrieve number of records

//Close genvarproceso_genparametr_genvarproceso_genparametr_TotalRecords_BeforeShow @22-30FD6502
    return $genvarproceso_genparametr_genvarproceso_genparametr_TotalRecords_BeforeShow;
}
//End Close genvarproceso_genparametr_genvarproceso_genparametr_TotalRecords_BeforeShow


?>
