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
            $this->Cue_CodCuenta = new clsControl(ccsListBox, "Cue_CodCuenta", "Cue_CodCuenta", ccsText, "", CCGetRequestParam("Cue_CodCuenta", $Method));
            $this->Cue_CodCuenta->DSType = dsTable;
            list($this->Cue_CodCuenta->BoundColumn, $this->Cue_CodCuenta->TextColumn, $this->Cue_CodCuenta->DBFormat) = array("Cue_CodCuenta", "Cue_Descripcion", "");
            $this->Cue_CodCuenta->ds = new clsDBdatos();
            $this->Cue_CodCuenta->ds->SQL = "SELECT Cue_CodCuenta, Cue_Descripcion from concuentas where cue_TipMovim = 1 and Cue_CodCuenta like concat((select par_Valor1 from genparametros where par_Clave = 'CUCXC'), '%') "; //CUENTAS POR COBRAR
            $this->Cue_CodCuenta->ds->Order = "Cue_Descripcion";
            $this->Cue_CodCuenta->ds->Parameters["urlpApli"] = CCGetFromGet("pApli", "");
            $this->Cue_CodCuenta->ds->wp = new clsSQLParameters();
            $this->Cue_CodCuenta->ds->wp->AddParameter("1", "urlpApli", ccsText, "", "", $this->Cue_CodCuenta->ds->Parameters["urlpApli"], "", false);
            $this->Cue_CodCuenta->ds->wp->Criterion[1] = $this->Cue_CodCuenta->ds->wp->Operation(opEqual, "cla_aplicacion", $this->Cue_CodCuenta->ds->wp->GetDBValue("1"), $this->Cue_CodCuenta->ds->ToSQL($this->Cue_CodCuenta->ds->wp->GetDBValue("1"), ccsText),false);
            $this->Cue_CodCuenta->ds->Where = $this->Cue_CodCuenta->ds->wp->Criterion[1];
            
            $this->com_Fec_Contab = new clsControl(ccsTextBox, "com_Fec_Contab", "com_Fec_Contab", ccsDate, Array("dd", "/", "mm", "/", "yy"), CCGetRequestParam("com_Fec_Contab", $Method));
            $this->DatePicker_s_com_Fec_Contab = new clsDatePicker("DatePicker_s_com_Fec_Contab", "concomprobantesSearch", "com_Fec_Contab");
            $this->com_Usuario = new clsControl(ccsTextBox, "com_Usuario", "com_Usuario", ccsText, "", CCGetRequestParam("com_Usuario", $Method));
        }
    }

    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->Cue_CodCuenta->Validate() && $Validation);
        $Validation = ($this->com_Fec_Contab->Validate() && $Validation);
        $Validation = ($this->com_Usuario->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }

    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->Cue_CodCuenta->Errors->Count());
        $errors = ($errors || $this->com_Fec_Contab->Errors->Count());
        $errors = ($errors || $this->DatePicker_s_com_Fec_Contab->Errors->Count());
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

        $Redirect = "CoTrTr_condRepCXC.php";
    }

    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->Cue_CodCuenta->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        if(!$this->FormSubmitted)
        {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->Cue_CodCuenta->Errors->ToString();
            $Error .= $this->com_Fec_Contab->Errors->ToString();
            $Error .= $this->DatePicker_s_com_Fec_Contab->Errors->ToString();
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

        
        $this->Cue_CodCuenta->Show();
        $this->com_Fec_Contab->Show();
        $this->DatePicker_s_com_Fec_Contab->Show();
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

$FileName = "CoTrTr_condRepCXC.php";
$Redirect = "";
$TemplateFileName = "CoTrTr_condRepCXC.html";
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
