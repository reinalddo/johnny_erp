<?php
//BindEvents Method @1-D40060DD
function BindEvents()
{
    global $CCSEvents;
    global $Item_lista;
    $CCSEvents["BeforeShow"] = "Page_BeforeShow";
    $Item_lista->CCSEvents["BeforeShow"] = "Item_lista_BeforeShow";
}
//End BindEvents Method

//Page_BeforeShow @1-D8BD2467
function Page_BeforeShow()
{
    $Page_BeforeShow = true;
//End Page_BeforeShow

//Custom Code @56-676AFEF5
// -------------------------
    global $InTrTr_items;
    global $Item_lista;
	if (CCGetParam("pOpCode", "X") == "R")	{
            fGeneraJs("Item_lista");			// Solo si es una consulta predefinida del exterior
        if (strlen(CCGetParam("s_keyword", "")) <1)	{ // si es una consulta vacia
                $Item_lista->Visible = False;
        }
    }
// -------------------------
//End Custom Code
//Close Page_BeforeShow @1-4BC230CD
    return $Page_BeforeShow;
}
//End Close Page_BeforeShow
 
function Item_lista_BeforeShow()          // fah Añadido
{
    global $Item_lista;

    $Item_lista->num_Recs->SetValue($Item_lista->ds->RecordsCount);
    return true ;
}


?>
