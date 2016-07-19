<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

Class clsRecordconcuentasSearch { //concuentasSearch Class @2-A2F306DB

//Variables @2-CB19EB75

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

//Class_Initialize Event @2-AF62CFB1
    function clsRecordconcuentasSearch()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record concuentasSearch/Error";
        $this->ReadAllowed = false;
        $this->InsertAllowed = false;
        $this->UpdateAllowed = false;
        $this->DeleteAllowed = false;
        if($this->Visible)
        {
            $this->ComponentName = "concuentasSearch";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->s_Cue_CodCuenta = new clsControl(ccsTextBox, "s_Cue_CodCuenta", "s_Cue_CodCuenta", ccsText, "", CCGetRequestParam("s_Cue_CodCuenta", $Method));
            $this->s_cue_Descripcion = new clsControl(ccsTextBox, "s_cue_Descripcion", "s_cue_Descripcion", ccsText, "", CCGetRequestParam("s_cue_Descripcion", $Method));
            $this->s_Cue_Padre = new clsControl(ccsListBox, "s_Cue_Padre", "s_Cue_Padre", ccsInteger, "", CCGetRequestParam("s_Cue_Padre", $Method));
            $this->s_Cue_Padre->DSType = dsSQL;
            list($this->s_Cue_Padre->BoundColumn, $this->s_Cue_Padre->TextColumn, $this->s_Cue_Padre->DBFormat) = array("cue_padre", "txt_cuenta", "");
            $this->s_Cue_Padre->ds = new clsDBdatos();
            $this->s_Cue_Padre->ds->SQL = "select distinct a.cue_padre, concat(b.cue_codcuenta, ' - - - - - ' , left(b.cue_descripcion,15)) as txt_cuenta " .
            "from concuentas a  join concuentas b on b.cue_id = a.cue_padre " .
            "";
            $this->s_Cue_Padre->ds->Order = "2";
            $this->s_Cue_Padre->HTML = true;
            $this->s_cue_TipMovim = new clsControl(ccsListBox, "s_cue_TipMovim", "s_cue_TipMovim", ccsInteger, "", CCGetRequestParam("s_cue_TipMovim", $Method));
            $this->s_cue_TipMovim->DSType = dsListOfValues;
            $this->s_cue_TipMovim->Values = array(array("0", "DE TITULO"), array("1", "DE MOVIMIENTO"));
            $this->s_cue_TipMovim->HTML = true;
            $this->s_cue_ReqAuxiliar = new clsControl(ccsListBox, "s_cue_ReqAuxiliar", "s_cue_ReqAuxiliar", ccsInteger, "", CCGetRequestParam("s_cue_ReqAuxiliar", $Method));
            $this->s_cue_ReqAuxiliar->DSType = dsListOfValues;
            $this->s_cue_ReqAuxiliar->Values = array(array("0", "NO"), array("1", "SI"));
            $this->s_cue_ReqAuxiliar->HTML = true;
            $this->s_cue_TipAuxiliar = new clsControl(ccsListBox, "s_cue_TipAuxiliar", "s_cue_TipAuxiliar", ccsInteger, "", CCGetRequestParam("s_cue_TipAuxiliar", $Method));
            $this->s_cue_TipAuxiliar->DSType = dsTable;
            list($this->s_cue_TipAuxiliar->BoundColumn, $this->s_cue_TipAuxiliar->TextColumn, $this->s_cue_TipAuxiliar->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->s_cue_TipAuxiliar->ds = new clsDBdatos();
            $this->s_cue_TipAuxiliar->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->s_cue_TipAuxiliar->ds->Parameters["expr13"] = 'CAUTI';
            $this->s_cue_TipAuxiliar->ds->wp = new clsSQLParameters();
            $this->s_cue_TipAuxiliar->ds->wp->AddParameter("1", "expr13", ccsText, "", "", $this->s_cue_TipAuxiliar->ds->Parameters["expr13"], "", false);
            $this->s_cue_TipAuxiliar->ds->wp->Criterion[1] = $this->s_cue_TipAuxiliar->ds->wp->Operation(opEqual, "par_Clave", $this->s_cue_TipAuxiliar->ds->wp->GetDBValue("1"), $this->s_cue_TipAuxiliar->ds->ToSQL($this->s_cue_TipAuxiliar->ds->wp->GetDBValue("1"), ccsText),false);
            $this->s_cue_TipAuxiliar->ds->Where = $this->s_cue_TipAuxiliar->ds->wp->Criterion[1];
            $this->s_cue_Estado = new clsControl(ccsListBox, "s_cue_Estado", "s_cue_Estado", ccsInteger, "", CCGetRequestParam("s_cue_Estado", $Method));
            $this->s_cue_Estado->DSType = dsListOfValues;
            $this->s_cue_Estado->Values = array(array("0", "INACTIVO"), array("1", "ACTIVO"));
            $this->s_cue_Estado->HTML = true;
            $this->Button_DoSearch = new clsButton("Button_DoSearch");
        }
    }
//End Class_Initialize Event

//Validate Method @2-B6BF4E95
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_Cue_CodCuenta->Validate() && $Validation);
        $Validation = ($this->s_cue_Descripcion->Validate() && $Validation);
        $Validation = ($this->s_Cue_Padre->Validate() && $Validation);
        $Validation = ($this->s_cue_TipMovim->Validate() && $Validation);
        $Validation = ($this->s_cue_ReqAuxiliar->Validate() && $Validation);
        $Validation = ($this->s_cue_TipAuxiliar->Validate() && $Validation);
        $Validation = ($this->s_cue_Estado->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-D19A7785
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_Cue_CodCuenta->Errors->Count());
        $errors = ($errors || $this->s_cue_Descripcion->Errors->Count());
        $errors = ($errors || $this->s_Cue_Padre->Errors->Count());
        $errors = ($errors || $this->s_cue_TipMovim->Errors->Count());
        $errors = ($errors || $this->s_cue_ReqAuxiliar->Errors->Count());
        $errors = ($errors || $this->s_cue_TipAuxiliar->Errors->Count());
        $errors = ($errors || $this->s_cue_Estado->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-9FDC6567
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
            $this->PressedButton = "Button_DoSearch";
            if(strlen(CCGetParam("Button_DoSearch", ""))) {
                $this->PressedButton = "Button_DoSearch";
            }
        }
        $Redirect = "CoAdCu_repcond.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick")) {
                    $Redirect = "";
                } else {
                    $Redirect = "CoAdCu_repcond.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", Array("Button_DoSearch")));
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @2-EA102C8D
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->s_Cue_Padre->Prepare();
        $this->s_cue_TipMovim->Prepare();
        $this->s_cue_ReqAuxiliar->Prepare();
        $this->s_cue_TipAuxiliar->Prepare();
        $this->s_cue_Estado->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        if(!$this->FormSubmitted)
        {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->s_Cue_CodCuenta->Errors->ToString();
            $Error .= $this->s_cue_Descripcion->Errors->ToString();
            $Error .= $this->s_Cue_Padre->Errors->ToString();
            $Error .= $this->s_cue_TipMovim->Errors->ToString();
            $Error .= $this->s_cue_ReqAuxiliar->Errors->ToString();
            $Error .= $this->s_cue_TipAuxiliar->Errors->ToString();
            $Error .= $this->s_cue_Estado->Errors->ToString();
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

        $this->s_Cue_CodCuenta->Show();
        $this->s_cue_Descripcion->Show();
        $this->s_Cue_Padre->Show();
        $this->s_cue_TipMovim->Show();
        $this->s_cue_ReqAuxiliar->Show();
        $this->s_cue_TipAuxiliar->Show();
        $this->s_cue_Estado->Show();
        $this->Button_DoSearch->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End concuentasSearch Class @2-FCB6E20C

//Initialize Page @1-3DFDAE51
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

$FileName = "CoAdCu_repcond.php";
$Redirect = "";
$TemplateFileName = "CoAdCu_repcond.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-86F81072
$DBdatos = new clsDBdatos();

// Controls
$concuentasSearch = new clsRecordconcuentasSearch();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-D9503F68
$concuentasSearch->Operation();
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

//Show Page @1-A122106E
$concuentasSearch->Show();
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
