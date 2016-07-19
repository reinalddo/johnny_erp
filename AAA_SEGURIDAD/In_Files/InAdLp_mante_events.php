<?php
//BindEvents Method @1-058B4C05
function BindEvents()
{
    global $Precios_lista;
    global $Precios_detalle;
    $Precios_lista->genparametros_TotalRecords->CCSEvents["BeforeShow"] = "Precios_lista_genparametros_TotalRecords_BeforeShow";
    $Precios_detalle->ds->CCSEvents["BeforeExecuteInsert"] = "Precios_detalle_ds_BeforeExecuteInsert";
    $Precios_detalle->ds->CCSEvents["BeforeExecuteUpdate"] = "Precios_detalle_ds_BeforeExecuteUpdate";
}
//End BindEvents Method

//Precios_lista_genparametros_TotalRecords_BeforeShow @3-CFAE6D0B
function Precios_lista_genparametros_TotalRecords_BeforeShow()
{
    $Precios_lista_genparametros_TotalRecords_BeforeShow = true;
//End Precios_lista_genparametros_TotalRecords_BeforeShow

//Retrieve number of records @4-C05A094A
    global $Precios_lista;
    $Precios_lista->genparametros_TotalRecords->SetValue($Precios_lista->ds->RecordsCount);
//End Retrieve number of records

//Close Precios_lista_genparametros_TotalRecords_BeforeShow @3-46239F97
    return $Precios_lista_genparametros_TotalRecords_BeforeShow;
}
//End Close Precios_lista_genparametros_TotalRecords_BeforeShow

//Precios_detalle_ds_BeforeExecuteInsert @50-D3BDD4BE
function Precios_detalle_ds_BeforeExecuteInsert()
{
    $Precios_detalle_ds_BeforeExecuteInsert = true;
//End Precios_detalle_ds_BeforeExecuteInsert

//Custom Code @116-9076BFEE
// -------------------------
    global $Precios_detalle;
// -------------------------
//End Custom Code

//Close Precios_detalle_ds_BeforeExecuteInsert @50-D5D1B32B
    return $Precios_detalle_ds_BeforeExecuteInsert;
}
//End Close Precios_detalle_ds_BeforeExecuteInsert

//Precios_detalle_ds_BeforeExecuteUpdate @50-7E8BCECC
function Precios_detalle_ds_BeforeExecuteUpdate()
{
    $Precios_detalle_ds_BeforeExecuteUpdate = true;
//End Precios_detalle_ds_BeforeExecuteUpdate

//Custom Code @117-9076BFEE
// -------------------------
    global $Precios_detalle;
// -------------------------
//End Custom Code

//Close Precios_detalle_ds_BeforeExecuteUpdate @50-1AF872A4
    return $Precios_detalle_ds_BeforeExecuteUpdate;
}
//End Close Precios_detalle_ds_BeforeExecuteUpdate
?>
