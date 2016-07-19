<?php
//BindEvents Method @1-9DD0D3D7
function BindEvents()
{
    global $Aux_search;
    global $Aux_list;
    global $CCSEvents;
    $Aux_search->CCSEvents["BeforeShow"] = "Aux_search_BeforeShow";
    $Aux_list->ds->CCSEvents["BeforeExecuteSelect"] = "Aux_list_ds_BeforeExecuteSelect";
    $Aux_list->CCSEvents["BeforeShow"] = "Aux_list_BeforeShow";
    $CCSEvents["BeforeShow"] = "Page_BeforeShow";
}
//End BindEvents Method

//Aux_search_BeforeShow @3-412EAB39
function Aux_search_BeforeShow()
{
    $Aux_search_BeforeShow = true;
//End Aux_search_BeforeShow

//Custom Code @49-570AC994
// -------------------------
    global $Aux_search;
	if(!CCGetParam("pMensj"))  $Aux_search->pMensj->Visible = false;
// -------------------------
//End Custom Code

//Close Aux_search_BeforeShow @3-F99877B7
    return $Aux_search_BeforeShow;
}
//End Close Aux_search_BeforeShow

//Aux_list_ds_BeforeExecuteSelect @34-65A10274
function Aux_list_ds_BeforeExecuteSelect()
{
    $Aux_list_ds_BeforeExecuteSelect = true;
//End Aux_list_ds_BeforeExecuteSelect

//Custom Code @53-C57BE4C4
// -------------------------
    global $Aux_list;
// -------------------------
//End Custom Code

//Close Aux_list_ds_BeforeExecuteSelect @34-EFC4EAD0
    return $Aux_list_ds_BeforeExecuteSelect;
}
//End Close Aux_list_ds_BeforeExecuteSelect

//Page_BeforeShow @1-D8BD2467
function Page_BeforeShow()
{
    $Page_BeforeShow = true;
//End Page_BeforeShow

//Custom Code @50-6D0C4152
// -------------------------
    global $CoAdAu_SearchN;
    global $Aux_list;
	if (CCGetParam("pOpCode", "X") == "R")	{
            fGeneraJs("Aux_list");			// Solo si es una consulta predefinida del exterior
        if (strlen(CCGetParam("s_keyword", "")) <1)	{ // si es una consulta vacia
                $Aux_list->Visible = False;
        }
    }
	
// -------------------------
//End Custom Code

//Close Page_BeforeShow @1-4BC230CD
    return $Page_BeforeShow;
}
//End Close Page_BeforeShow

function Aux_list_BeforeShow()          // fah Añadido
{
    $Aux_list_BeforeShow = true;
    global $Aux_list;

    $Aux_list->num_Recs->SetValue($Aux_list->ds->RecordsCount);
    return $Aux_list_BeforeShow ;
}


?>
