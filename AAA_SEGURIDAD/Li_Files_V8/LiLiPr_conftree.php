<?php
echo "<br>000";
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
echo "<br>00";
//End Include Common Files
include_once ("General.inc.php");      					// Definiciones generales
include ("../Ut_Files/lib/PHPLIB.php");
include ("../Ut_Files/lib/layersmenu-common.inc.php");
include ("../Ut_Files/lib/treemenu.inc.php");
echo "<br>0";
//Include Page implementation @2-49BEB8FC
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation

//Initialize Page @1-4DF39BDA
// Variables
$FileName = "";
$Redirect = "";
$Tpl = "";
$TemplateFileName = "";
$BlockToParse = "";
$ComponentName = "";

// Events;
$CCSEvents = "";
$CCSEventResult = "";

$FileName = "LiLiPr_conftree.php";
$Redirect = "";
$TemplateFileName = "LiLiPr_conftree.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-6A4C714A

// Controls
/*
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
*/
// Events

include("./LiLiPr_conftree_events.php");
BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-1CF50FF4
$Cabecera->Operations();
//End Execute Components

//Go to destination page @1-BEB91355
if($Redirect)
{
 $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
 header("Location: " . $Redirect);
 exit;
}
//End Go to destination page


	$mid = new TreeMenu();  //  Global object
	$mid->setDBConnParms(DATOS_CON);
	$mid->setTableName("concuentas");
	$mid->setTableFields(array(
		"id"		=> "cue_ID",
		"parent_id"	=> "cue_Padre",
		"text"		=> "concat(cue_codCuenta, '.&nbsp;&nbsp;&nbsp;&nbsp;', cue_Descripcion)",
		"href"		=> "concat('../Co_Files/CoAdCu_mant.php' , '?' , 'Cue_ID=', cue_ID)",
		"title"		=> "",
		"icon"		=> "",
	//	"icon"		=> "''",	// this is an alternative to the line above
		"target"	=> "'myIframe1'",
		"orderfield"	=> "cue_padre",
		"expanded"	=> "0"));

	$mid->scanTableForMenu("treemenu1");
//	$mid->getTreeMenu("treemenu1");




//Show Page @1-0D5DD8BF
//$Cabecera->Show("Cabecera");
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
$generated_with = "<center><font face=\"Arial\"><small>Generated with CodeCharge Studio</small></font></center>";
$main_block = preg_match("/<\/body>/i", $main_block) ? preg_replace("/<\/body>/i", $generated_with . "</body>", $main_block) : $main_block . $generated_with;
echo $main_block;
//End Show Page

//Unload Page @1-AB7622EF
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
unset($Tpl);
//End Unload Page
?>
