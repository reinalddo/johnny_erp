<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @29-49BEB8FC
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation

class clsRecordconcomprobantesSearch { //concomprobantesSearch Class @30-CB5F34A8

//Variables @30-CB19EB75

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

    // Class variables
//End Variables

//Class_Initialize Event @30-9CBEEBE1
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
"FROM genclasetran";
            $this->com_TipoComp->ds->Order = "cla_Descripcion";
            $this->com_TipoComp->ds->Parameters["urlpApli"] = CCGetFromGet("pApli", "");
            $this->com_TipoComp->ds->wp = new clsSQLParameters();
            $this->com_TipoComp->ds->wp->AddParameter("1", "urlpApli", ccsText, "", "", $this->com_TipoComp->ds->Parameters["urlpApli"], "", false);
            $this->com_TipoComp->ds->wp->Criterion[1] = $this->com_TipoComp->ds->wp->Operation(opEqual, "cla_aplicacion", $this->com_TipoComp->ds->wp->GetDBValue("1"), $this->com_TipoComp->ds->ToSQL($this->com_TipoComp->ds->wp->GetDBValue("1"), ccsText),false);
            $this->com_TipoComp->ds->Where = $this->com_TipoComp->ds->wp->Criterion[1];
            $this->com_NumComp = new clsControl(ccsTextBox, "com_NumComp", "com_NumComp", ccsInteger, "", CCGetRequestParam("com_NumComp", $Method));
            $this->com_RegNumero = new clsControl(ccsTextBox, "com_RegNumero", "com_RegNumero", ccsInteger, "", CCGetRequestParam("com_RegNumero", $Method));
            $this->com_FecTrans = new clsControl(ccsTextBox, "com_FecTrans", "com_FecTrans", ccsDate, Array("dd", "/", "mm", "/", "yy"), CCGetRequestParam("com_FecTrans", $Method));
            $this->DatePicker_s_com_FecTrans = new clsDatePicker("DatePicker_s_com_FecTrans", "concomprobantesSearch", "com_FecTrans");
            $this->com_FecContab = new clsControl(ccsTextBox, "com_FecContab", "com_FecContab", ccsDate, Array("dd", "/", "mm", "/", "yy"), CCGetRequestParam("com_FecContab", $Method));
            $this->DatePicker_s_com_FecContab = new clsDatePicker("DatePicker_s_com_FecContab", "concomprobantesSearch", "com_FecContab");
            $this->com_NumPeriodo = new clsControl(ccsTextBox, "com_NumPeriodo", "com_NumPeriodo", ccsInteger, "", CCGetRequestParam("com_NumPeriodo", $Method));
            $this->com_FecVencim = new clsControl(ccsTextBox, "com_FecVencim", "com_FecVencim", ccsDate, Array("dd", "/", "mm", "/", "yy"), CCGetRequestParam("com_FecVencim", $Method));
            $this->DatePicker_s_com_FecVencim = new clsDatePicker("DatePicker_s_com_FecVencim", "concomprobantesSearch", "com_FecVencim");
            $this->com_Emisor = new clsControl(ccsTextBox, "com_Emisor", "com_Emisor", ccsInteger, "", CCGetRequestParam("com_Emisor", $Method));
            $this->com_CodReceptor = new clsControl(ccsTextBox, "com_CodReceptor", "com_CodReceptor", ccsInteger, "", CCGetRequestParam("com_CodReceptor", $Method));
            $this->com_Receptor = new clsControl(ccsTextBox, "com_Receptor", "com_Receptor", ccsText, "", CCGetRequestParam("com_Receptor", $Method));
            $this->com_Concepto = new clsControl(ccsTextBox, "com_Concepto", "com_Concepto", ccsMemo, "", CCGetRequestParam("com_Concepto", $Method));
            $this->com_RefOperat = new clsControl(ccsTextBox, "com_RefOperat", "com_RefOperat", ccsInteger, "", CCGetRequestParam("com_RefOperat", $Method));
            $this->com_NumProceso = new clsControl(ccsTextBox, "com_NumProceso", "com_NumProceso", ccsInteger, "", CCGetRequestParam("com_NumProceso", $Method));
            $this->com_EstProceso = new clsControl(ccsTextBox, "com_EstProceso", "com_EstProceso", ccsInteger, "", CCGetRequestParam("com_EstProceso", $Method));
            $this->com_EstOperacion = new clsControl(ccsTextBox, "com_EstOperacion", "com_EstOperacion", ccsInteger, "", CCGetRequestParam("com_EstOperacion", $Method));
            $this->com_Usuario = new clsControl(ccsTextBox, "com_Usuario", "com_Usuario", ccsText, "", CCGetRequestParam("com_Usuario", $Method));
            $this->com_FecDigita = new clsControl(ccsTextBox, "com_FecDigita", "com_FecDigita", ccsDate, Array("dd", "/", "mm", "/", "yy"), CCGetRequestParam("com_FecDigita", $Method));
        }
    }
//End Class_Initialize Event

//Validate Method @30-EA239FB9
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->com_TipoComp->Validate() && $Validation);
        $Validation = ($this->com_NumComp->Validate() && $Validation);
        $Validation = ($this->com_RegNumero->Validate() && $Validation);
        $Validation = ($this->com_FecTrans->Validate() && $Validation);
        $Validation = ($this->com_FecContab->Validate() && $Validation);
        $Validation = ($this->com_NumPeriodo->Validate() && $Validation);
        $Validation = ($this->com_FecVencim->Validate() && $Validation);
        $Validation = ($this->com_Emisor->Validate() && $Validation);
        $Validation = ($this->com_CodReceptor->Validate() && $Validation);
        $Validation = ($this->com_Receptor->Validate() && $Validation);
        $Validation = ($this->com_Concepto->Validate() && $Validation);
        $Validation = ($this->com_RefOperat->Validate() && $Validation);
        $Validation = ($this->com_NumProceso->Validate() && $Validation);
        $Validation = ($this->com_EstProceso->Validate() && $Validation);
        $Validation = ($this->com_EstOperacion->Validate() && $Validation);
        $Validation = ($this->com_Usuario->Validate() && $Validation);
        $Validation = ($this->com_FecDigita->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @30-09336820
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->com_TipoComp->Errors->Count());
        $errors = ($errors || $this->com_NumComp->Errors->Count());
        $errors = ($errors || $this->com_RegNumero->Errors->Count());
        $errors = ($errors || $this->com_FecTrans->Errors->Count());
        $errors = ($errors || $this->DatePicker_s_com_FecTrans->Errors->Count());
        $errors = ($errors || $this->com_FecContab->Errors->Count());
        $errors = ($errors || $this->DatePicker_s_com_FecContab->Errors->Count());
        $errors = ($errors || $this->com_NumPeriodo->Errors->Count());
        $errors = ($errors || $this->com_FecVencim->Errors->Count());
        $errors = ($errors || $this->DatePicker_s_com_FecVencim->Errors->Count());
        $errors = ($errors || $this->com_Emisor->Errors->Count());
        $errors = ($errors || $this->com_CodReceptor->Errors->Count());
        $errors = ($errors || $this->com_Receptor->Errors->Count());
        $errors = ($errors || $this->com_Concepto->Errors->Count());
        $errors = ($errors || $this->com_RefOperat->Errors->Count());
        $errors = ($errors || $this->com_NumProceso->Errors->Count());
        $errors = ($errors || $this->com_EstProceso->Errors->Count());
        $errors = ($errors || $this->com_EstOperacion->Errors->Count());
        $errors = ($errors || $this->com_Usuario->Errors->Count());
        $errors = ($errors || $this->com_FecDigita->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @30-21D3BD9F
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        $this->EditMode = false;
        if(!$this->FormSubmitted)
            return;

        $Redirect = "CoTrTr_condmov.php";
    }
//End Operation Method

//Show Method @30-8CDA28BC
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
            $Error .= $this->com_RegNumero->Errors->ToString();
            $Error .= $this->com_FecTrans->Errors->ToString();
            $Error .= $this->DatePicker_s_com_FecTrans->Errors->ToString();
            $Error .= $this->com_FecContab->Errors->ToString();
            $Error .= $this->DatePicker_s_com_FecContab->Errors->ToString();
            $Error .= $this->com_NumPeriodo->Errors->ToString();
            $Error .= $this->com_FecVencim->Errors->ToString();
            $Error .= $this->DatePicker_s_com_FecVencim->Errors->ToString();
            $Error .= $this->com_Emisor->Errors->ToString();
            $Error .= $this->com_CodReceptor->Errors->ToString();
            $Error .= $this->com_Receptor->Errors->ToString();
            $Error .= $this->com_Concepto->Errors->ToString();
            $Error .= $this->com_RefOperat->Errors->ToString();
            $Error .= $this->com_NumProceso->Errors->ToString();
            $Error .= $this->com_EstProceso->Errors->ToString();
            $Error .= $this->com_EstOperacion->Errors->ToString();
            $Error .= $this->com_Usuario->Errors->ToString();
            $Error .= $this->com_FecDigita->Errors->ToString();
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

        $this->com_TipoComp->Show();
        $this->com_NumComp->Show();
        $this->com_RegNumero->Show();
        $this->com_FecTrans->Show();
        $this->DatePicker_s_com_FecTrans->Show();
        $this->com_FecContab->Show();
        $this->DatePicker_s_com_FecContab->Show();
        $this->com_NumPeriodo->Show();
        $this->com_FecVencim->Show();
        $this->DatePicker_s_com_FecVencim->Show();
        $this->com_Emisor->Show();
        $this->com_CodReceptor->Show();
        $this->com_Receptor->Show();
        $this->com_Concepto->Show();
        $this->com_RefOperat->Show();
        $this->com_NumProceso->Show();
        $this->com_EstProceso->Show();
        $this->com_EstOperacion->Show();
        $this->com_Usuario->Show();
        $this->com_FecDigita->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End concomprobantesSearch Class @30-FCB6E20C



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

$FileName = "CoTrTr_condmov.php";
$Redirect = "";
$TemplateFileName = "CoTrTr_condmov.html";
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
$generated_with = "<center><font face=\"Arial\"><small>Generated with CodeCharge Studio</small></font></center>";
if(preg_match("/<\/body>/i", $main_block)) {
    $main_block = preg_replace("/<\/body>/i", $generated_with . "</body>", $main_block);
} else if(preg_match("/<\/html>/i", $main_block) && !preg_match("/<\/frameset>/i", $main_block)) {
    $main_block = preg_replace("/<\/html>/i", $generated_with . "</html>", $main_block);
} else if(!preg_match("/<\/frameset>/i", $main_block)) {
    $main_block .= $generated_with;
}
echo $main_block;
//End Show Page

//Unload Page @1-6786D1ED
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBdatos->close();
unset($Tpl);
//End Unload Page


?>
