<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @2-49BEB8FC
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation

Class clsRecordSeleccion { //Seleccion Class @3-04AEA1D9

//Variables @3-CB19EB75

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

//Class_Initialize Event @3-6DB4BCE9
    function clsRecordSeleccion()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record Seleccion/Error";
        $this->ReadAllowed = false;
        $this->InsertAllowed = false;
        $this->UpdateAllowed = false;
        $this->DeleteAllowed = false;
        if($this->Visible)
        {
            $this->ComponentName = "Seleccion";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->lbEstados = new clsControl(ccsListBox, "lbEstados", "Estado Financiero", ccsText, "", CCGetRequestParam("lbEstados", $Method));
            $this->lbEstados->DSType = dsTable;
            list($this->lbEstados->BoundColumn, $this->lbEstados->TextColumn, $this->lbEstados->DBFormat) = array("par_valor2", "par_Descripcion", "");
            $this->lbEstados->ds = new clsDBdatos();
            $this->lbEstados->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->lbEstados->ds->Parameters["expr11"] = 'EFIN';
            $this->lbEstados->ds->wp = new clsSQLParameters();
            $this->lbEstados->ds->wp->AddParameter("1", "expr11", ccsText, "", "", $this->lbEstados->ds->Parameters["expr11"], "", true);
            $this->lbEstados->ds->wp->Criterion[1] = $this->lbEstados->ds->wp->Operation(opEqual, "par_Clave", $this->lbEstados->ds->wp->GetDBValue("1"), $this->lbEstados->ds->ToSQL($this->lbEstados->ds->wp->GetDBValue("1"), ccsText),true);
            $this->lbEstados->ds->Where = $this->lbEstados->ds->wp->Criterion[1];
            $this->lbPeriodo = new clsControl(ccsListBox, "lbPeriodo", "Período", ccsInteger, "", CCGetRequestParam("lbPeriodo", $Method));
            $this->lbPeriodo->DSType = dsSQL;
            list($this->lbPeriodo->BoundColumn, $this->lbPeriodo->TextColumn, $this->lbPeriodo->DBFormat) = array("periodo", "fecha", "");
            $this->lbPeriodo->ds = new clsDBdatos();
            $this->lbPeriodo->ds->SQL = "SELECT (per_numperiodo * 1)as periodo , DATE_FORMAT(per_fecfinal, '%M %d %y') as fecha " .
            "FROM conperiodos " .
            "WHERE per_Aplicacion = 'CO'";
            $this->lbEsquema = new clsControl(ccsListBox, "lbEsquema", "Esquema de Cuentas", ccsText, "", CCGetRequestParam("lbEsquema", $Method));
            $this->lbEsquema->DSType = dsTable;
            list($this->lbEsquema->BoundColumn, $this->lbEsquema->TextColumn, $this->lbEsquema->DBFormat) = array("par_Valor1", "par_Descripcion", "");
            $this->lbEsquema->ds = new clsDBdatos();
            $this->lbEsquema->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->lbEsquema->ds->Parameters["expr13"] = 'CEFIN';
            $this->lbEsquema->ds->wp = new clsSQLParameters();
            $this->lbEsquema->ds->wp->AddParameter("1", "expr13", ccsText, "", "", $this->lbEsquema->ds->Parameters["expr13"], "", false);
            $this->lbEsquema->ds->wp->Criterion[1] = $this->lbEsquema->ds->wp->Operation(opEqual, "par_Clave", $this->lbEsquema->ds->wp->GetDBValue("1"), $this->lbEsquema->ds->ToSQL($this->lbEsquema->ds->wp->GetDBValue("1"), ccsText),false);
            $this->lbEsquema->ds->Where = $this->lbEsquema->ds->wp->Criterion[1];
            $this->lbEsquema->Required = true;
            $this->btEjecutar = new clsButton("btEjecutar");
            if(!$this->FormSubmitted) {
                if(!is_array($this->lbEstados->Value) && !strlen($this->lbEstados->Value) && $this->lbEstados->Value !== false)
                $this->lbEstados->SetText(' ');
                if(!is_array($this->lbEsquema->Value) && !strlen($this->lbEsquema->Value) && $this->lbEsquema->Value !== false)
                $this->lbEsquema->SetText(' ');
            }
        }
    }
//End Class_Initialize Event

//Validate Method @3-5C66F1EC
    function Validate()
    {
        $Validation = true;
        $Where = "";
        if(! (lbEstados > ' ')) {
            $this->lbEstados->Errors->addError("Defina un estado Financiero");
        }
        $Validation = ($this->lbEstados->Validate() && $Validation);
        $Validation = ($this->lbPeriodo->Validate() && $Validation);
        $Validation = ($this->lbEsquema->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @3-52FB7858
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->lbEstados->Errors->Count());
        $errors = ($errors || $this->lbPeriodo->Errors->Count());
        $errors = ($errors || $this->lbEsquema->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @3-839739B0
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        $this->EditMode = false;
        if(!$this->FormSubmitted)
            return;

        if($this->FormSubmitted) {
            $this->PressedButton = "btEjecutar";
            if(strlen(CCGetParam("btEjecutar", ""))) {
                $this->PressedButton = "btEjecutar";
            }
        }
        $Redirect = $FileName . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->Validate()) {
            if($this->PressedButton == "btEjecutar") {
                if(!CCGetEvent($this->btEjecutar->CCSEvents, "OnClick")) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @3-969F16EE
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->lbEstados->Prepare();
        $this->lbPeriodo->Prepare();
        $this->lbEsquema->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        if(!$this->FormSubmitted)
        {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->lbEstados->Errors->ToString();
            $Error .= $this->lbPeriodo->Errors->ToString();
            $Error .= $this->lbEsquema->Errors->ToString();
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

        $this->lbEstados->Show();
        $this->lbPeriodo->Show();
        $this->lbEsquema->Show();
        $this->btEjecutar->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End Seleccion Class @3-FCB6E20C

//Initialize Page @1-AB61C279
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

$FileName = "CoTrEf_Selecc.php";
$Redirect = "";
$TemplateFileName = "CoTrEf_Selecc.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-01D0259B
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
$Seleccion = new clsRecordSeleccion();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-4CB347A0
$Cabecera->Operations();
$Seleccion->Operation();
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

//Show Page @1-F0371B63
$Cabecera->Show("Cabecera");
$Seleccion->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
$generated_with = "<center><font face=\"Arial\"><small>Generated with CodeCharge Studio</small></font></center>";
$main_block = preg_match("/<\/body>/i", $main_block) ? preg_replace("/<\/body>/i", $generated_with . "</body>", $main_block) : $main_block . $generated_with;
echo $main_block;
//End Show Page

//Unload Page @1-6786D1ED
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBdatos->close();
unset($Tpl);
//End Unload Page


?>
