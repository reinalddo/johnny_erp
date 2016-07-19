<?php
//BindEvents Method @1-06D34ACD
function BindEvents()
{
    global $liqcomponent;
    global $CCSEvents;
    $liqcomponent->liqcomponent_TotalRecords->CCSEvents["BeforeShow"] = "liqcomponent_liqcomponent_TotalRecords_BeforeShow";
    $CCSEvents["AfterInitialize"] = "Page_AfterInitialize";
}
//End BindEvents Method

//liqcomponent_liqcomponent_TotalRecords_BeforeShow @10-89D7BB99
function liqcomponent_liqcomponent_TotalRecords_BeforeShow()
{
    $liqcomponent_liqcomponent_TotalRecords_BeforeShow = true;
//End liqcomponent_liqcomponent_TotalRecords_BeforeShow

//Retrieve number of records @11-39D92458
    global $liqcomponent;
    $liqcomponent->liqcomponent_TotalRecords->SetValue($liqcomponent->ds->RecordsCount);
//End Retrieve number of records

//Close liqcomponent_liqcomponent_TotalRecords_BeforeShow @10-1BE969C8
    return $liqcomponent_liqcomponent_TotalRecords_BeforeShow;
}
//End Close liqcomponent_liqcomponent_TotalRecords_BeforeShow

//Page_AfterInitialize @1-7910B30B
function Page_AfterInitialize()
{
    $Page_AfterInitialize = true;
//End Page_AfterInitialize

//Custom Code @35-79984EBF
// -------------------------
    global $LiAdCo;
   	include_once (RelativePath . "/LibPhp/SegLib.php") ;
// -------------------------
//End Custom Code

//Close Page_AfterInitialize @1-379D319D
    return $Page_AfterInitialize;
}
//End Close Page_AfterInitialize


?>
