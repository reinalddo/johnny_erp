<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @7-20D3120B
include_once(RelativePath . "/Ig_Files/../De_Files/Cabecera.php");
//End Include Page implementation

class clsGridgenparametros { //genparametros class @2-0FC884FE

//Variables @2-0B3A0FB0

    // Public variables
    var $ComponentName;
    var $Visible;
    var $Errors;
    var $ErrorBlock;
    var $ds; var $PageSize;
    var $SorterName = "";
    var $SorterDirection = "";
    var $PageNumber;

    var $CCSEvents = "";
    var $CCSEventResult;

    // Grid Controls
    var $StaticControls; var $RowControls;
//End Variables

//Class_Initialize Event @2-DBFAEFE0
    function clsGridgenparametros()
    {
        global $FileName;
        $this->ComponentName = "genparametros";
        $this->Visible = True;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid genparametros";
        $this->ds = new clsgenparametrosDataSource();
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 20;
        else
            $this->PageSize = intval($this->PageSize);
        if ($this->PageSize > 100)
            $this->PageSize = 100;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));

        $this->hdPath = new clsControl(ccsHidden, "hdPath", "hdPath", ccsText, "", CCGetRequestParam("hdPath", ccsGet));
        $this->Hidden1 = new clsControl(ccsHidden, "Hidden1", "Hidden1", ccsText, "", CCGetRequestParam("Hidden1", ccsGet));
        $this->lkOpcion = new clsControl(ccsLink, "lkOpcion", "lkOpcion", ccsText, "", CCGetRequestParam("lkOpcion", ccsGet));
        $this->lkOpcion->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
        $this->lkOpcion->Page = "";
    }
//End Class_Initialize Event

//Initialize Method @2-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @2-F32EE327
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;


        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");


        $this->ds->Prepare();
        $this->ds->Open();

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
        if(!$this->Visible) return;

        $GridBlock = "Grid " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $GridBlock;


        $is_next_record = $this->ds->next_record();
        if($is_next_record && $ShownRecords < $this->PageSize)
        {
            do {
                    $this->ds->SetValues();
                $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/Row";
                $this->hdPath->SetValue($this->ds->hdPath->GetValue());
                $this->lkOpcion->SetValue($this->ds->lkOpcion->GetValue());
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->hdPath->Show();
                $this->Hidden1->Show();
                $this->lkOpcion->Show();
                $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                $Tpl->parse("Row", true);
                $ShownRecords++;
                $is_next_record = $this->ds->next_record();
            } while ($is_next_record && $ShownRecords < $this->PageSize);
        }
        else // Show NoRecords block if no records are found
        {
            $Tpl->parse("NoRecords", false);
        }

        $errors = $this->GetErrors();
        if(strlen($errors))
        {
            $Tpl->replaceblock("", $errors);
            $Tpl->block_path = $ParentPath;
            return;
        }
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @2-A3153785
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->hdPath->Errors->ToString();
        $errors .= $this->Hidden1->Errors->ToString();
        $errors .= $this->lkOpcion->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End genparametros Class @2-FCB6E20C

class clsgenparametrosDataSource extends clsDBdatos {  //genparametrosDataSource Class @2-F3E4BDC2

//DataSource Variables @2-4E11A303
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $hdPath;
    var $lkOpcion;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-7591C480
    function clsgenparametrosDataSource()
    {
        $this->ErrorBlock = "Grid genparametros";
        $this->Initialize();
        $this->hdPath = new clsField("hdPath", ccsText, "");
        $this->lkOpcion = new clsField("lkOpcion", ccsText, "");

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @2-826C3099
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "par_Secuencia";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            "");
    }
//End SetOrder Method

//Prepare Method @2-CAF40625
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->Criterion[1] = "par_clave='GECONS'";
        $this->Where = 
             $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @2-C7AE4509
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM genparametros";
        $this->SQL = "SELECT *  " .
        "FROM genparametros";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query($this->OptimizeSQL(CCBuildSQL($this->SQL, $this->Where, $this->Order)));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
    }
//End Open Method

//SetValues Method @2-DC2D9330
    function SetValues()
    {
        $this->hdPath->SetDBValue($this->f("par_Valor1"));
        $this->lkOpcion->SetDBValue($this->f("par_Descripcion"));
    }
//End SetValues Method

} //End genparametrosDataSource Class @2-FCB6E20C

//Initialize Page @1-BABE1B62
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

$FileName = "IgGeGe_opciones.php";
$Redirect = "";
$TemplateFileName = "IgGeGe_opciones.html";
$BlockToParse = "main";
$TemplateEncoding = "";
$FileEncoding = "";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-F96FFA3A
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera("../De_Files/");
$Cabecera->BindEvents();
$Cabecera->Initialize();
$genparametros = new clsGridgenparametros();
$genparametros->Initialize();

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

//Execute Components @1-1CF50FF4
$Cabecera->Operations();
//End Execute Components

//Go to destination page @1-8B3727D5
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    $DBdatos->close();
    header("Location: " . $Redirect);
    $Cabecera->Class_Terminate();
    unset($Cabecera);
    unset($genparametros);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-BFBD1BFC
$Cabecera->Show("Cabecera");
$genparametros->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
if(preg_match("/<\/body>/i", $main_block)) {
    $main_block = preg_replace("/<\/body>/i", implode(array("<center><font face", "=\"Arial\"><small>G", "&#101;ne&#114;", "&#97;t&#101;&#", "100; <!-- SCC --", ">wi&#116;h <!-", "- SCC -->&#67;&#1", "11;&#100;eC&#1", "04;&#97;&#114;ge ", "<!-- SCC -->&", "#83;&#116;u&#", "100;&#105;o.</smal", "l></font></ce", "nter>"), "") . "</body>", $main_block);
} else if(preg_match("/<\/html>/i", $main_block) && !preg_match("/<\/frameset>/i", $main_block)) {
    $main_block = preg_replace("/<\/html>/i", implode(array("<center><font face", "=\"Arial\"><small>G", "&#101;ne&#114;", "&#97;t&#101;&#", "100; <!-- SCC --", ">wi&#116;h <!-", "- SCC -->&#67;&#1", "11;&#100;eC&#1", "04;&#97;&#114;ge ", "<!-- SCC -->&", "#83;&#116;u&#", "100;&#105;o.</smal", "l></font></ce", "nter>"), "") . "</html>", $main_block);
} else if(!preg_match("/<\/frameset>/i", $main_block)) {
    $main_block .= implode(array("<center><font face", "=\"Arial\"><small>G", "&#101;ne&#114;", "&#97;t&#101;&#", "100; <!-- SCC --", ">wi&#116;h <!-", "- SCC -->&#67;&#1", "11;&#100;eC&#1", "04;&#97;&#114;ge ", "<!-- SCC -->&", "#83;&#116;u&#", "100;&#105;o.</smal", "l></font></ce", "nter>"), "");
}
echo $main_block;
//End Show Page

//Unload Page @1-559F3E43
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBdatos->close();
$Cabecera->Class_Terminate();
unset($Cabecera);
unset($genparametros);
unset($Tpl);
//End Unload Page


?>
