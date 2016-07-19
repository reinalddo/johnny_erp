<?php
//BindEvents Method @1-D40060DD
function BindEvents()
{
    global $CCSEvents;
    global $CoAdCu_list;
    $CCSEvents["BeforeShow"] = "Page_BeforeShow";
    $CoAdCu_list->CCSEvents["BeforeShow"] = "CoAdCu_list_BeforeShow";       // fah mod
}
//End BindEvents Method

//Page_BeforeShow @1-D8BD2467
function Page_BeforeShow()
{
    $Page_BeforeShow = true;
//End Page_BeforeShow

//Custom Code @269-6E4E92E8
// -------------------------
    global $CoAdCu_list;
	fGeneraJs("CoAdCu_list");
//	if (strlen(CCGetParam("s_keyword", "")) <1  )	{ // si es una consulta vacia
//                $CoAdCu_list->Visible = False;
//        }
// -------------------------
//End Custom Code

//Close Page_BeforeShow @1-4BC230CD
    return $Page_BeforeShow;
}
//End Close Page_BeforeShow

function CoAdCu_list_BeforeShow()                   // fah mod
{    global $CoAdCu_list;
    $CoAdCu_list_BeforeShow = true;

    $CoAdCu_list->num_Recs->SetValue($CoAdCu_list->ds->RecordsCount);
    return $CoAdCu_list_BeforeShow;
}
?>
