<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Initialize Page @1-DC9102BF
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

$FileName = "GeGeGe_mail.php";
$Redirect = "";
$TemplateFileName = "GeGeGe_mail.html";
$BlockToParse = "main";
$TemplateEncoding = "";
$FileEncoding = "";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-AEF6D84A

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");

if ($Charset)
    header("Content-Type: text/html; charset=" . $Charset);
//End Initialize Objects

//Initialize HTML Template @1-51DB8464
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main", $TemplateEncoding);
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Go to destination page @1-2D4719DF
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    header("Location: " . $Redirect);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-6098CD9C
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
if(preg_match("/<\/body>/i", $main_block)) {
    $main_block = preg_replace("/<\/body>/i", "<center><font face=\"Arial\"><small>G&#101" . ";&#110;e&#114;&#97;&#116;e&#100; <!-- SCC --" . ">wit&#104; <!-- SCC -->&#67;od&#101;&#67;&#10" . "4;&#97;&#114;&#103;e <!-- CCS -->&#83;&#116;u" . "&#100;&#105;o.</small></font></center>" . "</body>", $main_block);
} else if(preg_match("/<\/html>/i", $main_block) && !preg_match("/<\/frameset>/i", $main_block)) {
    $main_block = preg_replace("/<\/html>/i", "<center><font face=\"Arial\"><small>G&#101" . ";&#110;e&#114;&#97;&#116;e&#100; <!-- SCC --" . ">wit&#104; <!-- SCC -->&#67;od&#101;&#67;&#10" . "4;&#97;&#114;&#103;e <!-- CCS -->&#83;&#116;u" . "&#100;&#105;o.</small></font></center>" . "</html>", $main_block);
} else if(!preg_match("/<\/frameset>/i", $main_block)) {
    $main_block .= "<center><font face=\"Arial\"><small>G&#101" . ";&#110;e&#114;&#97;&#116;e&#100; <!-- SCC --" . ">wit&#104; <!-- SCC -->&#67;od&#101;&#67;&#10" . "4;&#97;&#114;&#103;e <!-- CCS -->&#83;&#116;u" . "&#100;&#105;o.</small></font></center>";
}
echo $main_block;
//End Show Page

//Unload Page @1-AB7622EF
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
unset($Tpl);
//End Unload Page


?>
