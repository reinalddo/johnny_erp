<?php
//BindEvents Method @1-397EAC53
function BindEvents()
{
 global $CCSEvents;
 $CCSEvents["AfterInitialize"] = "Page_AfterInitialize";
}
//End BindEvents Method

//Page_AfterInitialize @1-7910B30B
function Page_AfterInitialize()
{
 $Page_AfterInitialize = true;
//End Page_AfterInitialize

//Custom Code @3-DFB93085
// -------------------------
	global $LiLiRp_conftree;


// -------------------------
//End Custom Code

//Close Page_AfterInitialize @1-379D319D
 return $Page_AfterInitialize;
}
//End Close Page_AfterInitialize
?>
