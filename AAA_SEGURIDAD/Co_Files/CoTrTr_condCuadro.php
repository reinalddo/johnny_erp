<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");

//Include Page implementation @29-49BEB8FC
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation

class clsRecordconcomprobantesSearch { 

    // Public variables
    var $ComponentName;
    var $HTMLFormAction;
    var $PressedButton;
    var $Errors;
    var $ErrorBlock;
    var $FormSubmitted;
    var $FormEnctype;
    var $Visible;
    var $Recordset;

    var $CCSEvents = "";
    var $CCSEventResult;

    var $ds;
    var $EditMode;
    var $ValidatingControls;
    var $Controls;


    function clsRecordconcomprobantesSearch()
    {
        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record concomprobantesSearch/Error";
        $this->ReadAllowed = false;
        $this->InsertAllowed = false;
        $this->UpdateAllowed = false;
        $this->DeleteAllowed = false;
        if($this->Visible)
        {
            $this->ComponentName = "concomprobantesSearch";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->com_TipoComp = new clsControl(ccsListBox, "com_TipoComp", "com_TipoComp", ccsText, "", CCGetRequestParam("com_TipoComp", $Method));
            $this->com_TipoComp->DSType = dsTable;
            list($this->com_TipoComp->BoundColumn, $this->com_TipoComp->TextColumn, $this->com_TipoComp->DBFormat) = array("cla_tipoComp", "cla_Descripcion", "");
            $this->com_TipoComp->ds = new clsDBdatos();
            $this->com_TipoComp->ds->SQL = "SELECT cla_tipoComp, concat(cla_Descripcion,' - (', cla_tipocomp, ')') as cla_Descripcion " .
            "FROM genclasetran where cla_tipoComp = 'PC'"; //Sólo para provision de compra
            $this->com_TipoComp->ds->Order = "cla_Descripcion";
            $this->com_TipoComp->ds->Parameters["urlpApli"] = CCGetFromGet("pApli", "");
            $this->com_TipoComp->ds->wp = new clsSQLParameters();
            $this->com_TipoComp->ds->wp->AddParameter("1", "urlpApli", ccsText, "", "", $this->com_TipoComp->ds->Parameters["urlpApli"], "", false);
            $this->com_TipoComp->ds->wp->Criterion[1] = $this->com_TipoComp->ds->wp->Operation(opEqual, "cla_aplicacion", $this->com_TipoComp->ds->wp->GetDBValue("1"), $this->com_TipoComp->ds->ToSQL($this->com_TipoComp->ds->wp->GetDBValue("1"), ccsText),false);
            $this->com_TipoComp->ds->Where = $this->com_TipoComp->ds->wp->Criterion[1];
            $this->com_NumComp = new clsControl(ccsTextBox, "com_NumComp", "com_NumComp", ccsInteger, "", CCGetRequestParam("com_NumComp", $Method));
            //$this->estado = new clsControl(ccsTextBox, "estado", "estado", ccsText, "", CCGetRequestParam("estado", $Method));            
            $this->com_FecHasta = new clsControl(ccsTextBox, "com_FecHasta", "com_FecHasta", ccsDate, Array("dd", "/", "mm", "/", "yy"), CCGetRequestParam("com_FecHasta", $Method));
            $this->DatePicker_s_com_FecHasta = new clsDatePicker("DatePicker_s_com_FecHasta", "concomprobantesSearch", "com_FecHasta");
            
            $this->com_FecVencimDesde = new clsControl(ccsTextBox, "com_FecVencimDesde", "com_FecVencimDesde", ccsDate, Array("dd", "/", "mm", "/", "yy"), CCGetRequestParam("com_FecVencimDesde", $Method));
            $this->DatePicker_s_com_FecVencimDesde = new clsDatePicker("DatePicker_s_com_FecVencimDesde", "concomprobantesSearch", "com_FecVencimDesde");
            
            $this->com_Usuario = new clsControl(ccsTextBox, "com_Usuario", "com_Usuario", ccsText, "", CCGetRequestParam("com_Usuario", $Method));
        }
    }

    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->com_TipoComp->Validate() && $Validation);
        $Validation = ($this->com_NumComp->Validate() && $Validation);
        //$Validation = ($this->estado->Validate() && $Validation);
        $Validation = ($this->com_FecHasta->Validate() && $Validation);
        
        $Validation = ($this->com_FecVencimDesde->Validate() && $Validation);
        $Validation = ($this->com_Usuario->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }

    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->com_TipoComp->Errors->Count());
        //$errors = ($errors || $this->estado->Errors->Count());
        $errors = ($errors || $this->com_NumComp->Errors->Count());
        
        $errors = ($errors || $this->com_FecHasta->Errors->Count());
        $errors = ($errors || $this->DatePicker_s_com_FecHasta->Errors->Count());
        
        $errors = ($errors || $this->com_FecVencimDesde->Errors->Count());
        $errors = ($errors || $this->DatePicker_s_com_FecVencimDesde->Errors->Count());
        $errors = ($errors || $this->com_Usuario->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }

    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        $this->EditMode = false;
        if(!$this->FormSubmitted)
            return;

        $Redirect = "CoTrTr_condCuadro.php";
    }

    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->com_TipoComp->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        if(!$this->FormSubmitted)
        {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->com_TipoComp->Errors->ToString();
            $Error .= $this->com_NumComp->Errors->ToString();
            
            //$Error .= $this->estado->Errors->ToString();
            
            $Error .= $this->com_FecHasta->Errors->ToString();
            $Error .= $this->DatePicker_s_com_FecHasta->Errors->ToString();
            
            
            $Error .= $this->com_FecVencimDesde->Errors->ToString();
            $Error .= $this->DatePicker_s_com_FecVencimDesde->Errors->ToString();
            
            
            $Error .= $this->com_Usuario->Errors->ToString();
            $Error .= $this->Errors->ToString();
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $CCSForm = $this->EditMode ? $this->ComponentName . ":" . "Edit" : $this->ComponentName;
        $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        $Tpl->SetVar("Action", $this->HTMLFormAction);
        $Tpl->SetVar("HTMLFormName", $this->ComponentName);
        $Tpl->SetVar("HTMLFormEnctype", $this->FormEnctype);

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        //$this->estado->Show();
        $this->com_TipoComp->Show();
        $this->com_NumComp->Show();
        $this->com_FecHasta->Show();
        $this->DatePicker_s_com_FecHasta->Show();
        $this->com_FecVencimDesde->Show();
        $this->DatePicker_s_com_FecVencimDesde->Show();
        $this->com_Usuario->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
} 

//Initialize Page @1-7B6B516A
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

$FileName = "CoTrTr_condCuadro.php";
$Redirect = "";
$TemplateFileName = "CoTrTr_condCuadro.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-75CC21B5
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
$concomprobantesSearch = new clsRecordconcomprobantesSearch();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");

if($Charset) {
    header("Content-Type: text/html; charset=" . $Charset);
}
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-DECACBBA
$Cabecera->Operations();
$concomprobantesSearch->Operation();
//End Execute Components

//Go to destination page @1-CDA9AAFD
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    $DBdatos->close();
    header("Location: " . $Redirect);
    exit;
}
//End Go to destination page

//Show Page @1-85E2BD07
$Cabecera->Show("Cabecera");
$concomprobantesSearch->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
echo $main_block;
//End Show Page

//Unload Page @1-6786D1ED
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBdatos->close();
unset($Tpl);
//End Unload Page


?>
