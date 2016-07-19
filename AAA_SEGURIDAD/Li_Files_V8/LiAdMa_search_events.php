<?php
//BindEvents Method @1-C03501A1
function BindEvents()
{
    global $marcas_list;
    $marcas_list->ds->CCSEvents["BeforeExecuteSelect"] = "marcas_list_ds_BeforeExecuteSelect";
}
//End BindEvents Method

//marcas_list_ds_BeforeExecuteSelect @2-E69DDCB7
function marcas_list_ds_BeforeExecuteSelect()
{
    $marcas_list_ds_BeforeExecuteSelect = true;
//End marcas_list_ds_BeforeExecuteSelect

//Custom Code @22-49F472B0
// -------------------------
    global $marcas_list;
 echo $marcas_list->ds->SQL . $marcas_list->ds->Where;
// -------------------------
//End Custom Code

//Close marcas_list_ds_BeforeExecuteSelect @2-128BDCAB
    return $marcas_list_ds_BeforeExecuteSelect;
}
//End Close marcas_list_ds_BeforeExecuteSelect


?>
