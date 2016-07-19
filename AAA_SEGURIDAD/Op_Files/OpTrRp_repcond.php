<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @39-49BEB8FC
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation

class clsRecordliqembarquesSearch { //liqembarquesSearch Class @2-13C1847A

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

//Class_Initialize Event @2-23EFCC06
    function clsRecordliqembarquesSearch()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record liqembarquesSearch/Error";
        $this->ReadAllowed = false;
        $this->InsertAllowed = false;
        $this->UpdateAllowed = false;
        $this->DeleteAllowed = false;
        if($this->Visible)
        {
            $this->ComponentName = "liqembarquesSearch";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->pro_Semana = new clsControl(ccsTextBox, "pro_Semana", "pro_Semana", ccsText, "", CCGetRequestParam("pro_Semana", $Method));
            $this->pro_ID = new clsControl(ccsTextBox, "pro_ID", "pro_ID", ccsInteger, "", CCGetRequestParam("pro_ID", $Method));
            $this->pro_FechaLiquid = new clsControl(ccsTextBox, "pro_FechaLiquid", "pro_FechaLiquid", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("pro_FechaLiquid", $Method));
            $this->DatePicker1 = new clsDatePicker("DatePicker1", "liqembarquesSearch", "liq_Fecha");
            $this->pro_GrupoLiquid = new clsControl(ccsListBox, "pro_GrupoLiquid", "pro_GrupoLiquid", ccsText, "", CCGetRequestParam("pro_GrupoLiquid", $Method));
            $this->pro_GrupoLiquid->DSType = dsSQL;
            list($this->pro_GrupoLiquid->BoundColumn, $this->pro_GrupoLiquid->TextColumn, $this->pro_GrupoLiquid->DBFormat) = array("per_CodAuxiliar", "per_Nombres", "");
            $this->pro_GrupoLiquid->ds = new clsDBdatos();
            $this->pro_GrupoLiquid->ds->SQL = "SELECT concat(left(per_Apellidos,15) , ' ' , left(per_nombres,12)) as txt_nombre,  " .
            "       per_CodAuxiliar, per_Nombres  " .
            "FROM concategorias INNER JOIN conpersonas ON concategorias.cat_CodAuxiliar = conpersonas.per_CodAuxiliar " .
            "WHERE cat_categoria = 51";
            $this->tac_Embarcador = new clsControl(ccsListBox, "tac_Embarcador", "tac_Embarcador", ccsText, "", CCGetRequestParam("tac_Embarcador", $Method));
            $this->tac_Embarcador->DSType = dsSQL;
            list($this->tac_Embarcador->BoundColumn, $this->tac_Embarcador->TextColumn, $this->tac_Embarcador->DBFormat) = array("per_codauxiliar", "txt_Nombres", "");
            $this->tac_Embarcador->ds = new clsDBdatos();
            $this->tac_Embarcador->ds->SQL = "SELECT per_codauxiliar, concat(left(per_Apellidos,15), ' ', left(per_Nombres,12)) as txt_Nombres " .
            "FROM concategorias INNER JOIN conpersonas ON concategorias.cat_CodAuxiliar = conpersonas.per_CodAuxiliar " .
            "WHERE cat_categoria = 52 " .
            "";
            $this->tac_Embarcador->ds->Order = "2";
            $this->tac_UniProduccion = new clsControl(ccsListBox, "tac_UniProduccion", "tac_UniProduccion", ccsText, "", CCGetRequestParam("tac_UniProduccion", $Method));
            $this->tac_UniProduccion->DSType = dsTable;
            list($this->tac_UniProduccion->BoundColumn, $this->tac_UniProduccion->TextColumn, $this->tac_UniProduccion->DBFormat) = array("dat_ID", "dat_NomHacienda", "");
            $this->tac_UniProduccion->ds = new clsDBdatos();
            $this->tac_UniProduccion->ds->SQL = "SELECT *  " .
"FROM liqdatosmag";
            $this->tac_UniProduccion->ds->Order = "dat_NomHacienda";
        }
    }
//End Class_Initialize Event

//Validate Method @2-F2F06D95
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->pro_Semana->Validate() && $Validation);
        $Validation = ($this->pro_ID->Validate() && $Validation);
        $Validation = ($this->pro_FechaLiquid->Validate() && $Validation);
        $Validation = ($this->pro_GrupoLiquid->Validate() && $Validation);
        $Validation = ($this->tac_Embarcador->Validate() && $Validation);
        $Validation = ($this->tac_UniProduccion->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-3AF27ECE
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->pro_Semana->Errors->Count());
        $errors = ($errors || $this->pro_ID->Errors->Count());
        $errors = ($errors || $this->pro_FechaLiquid->Errors->Count());
        $errors = ($errors || $this->DatePicker1->Errors->Count());
        $errors = ($errors || $this->pro_GrupoLiquid->Errors->Count());
        $errors = ($errors || $this->tac_Embarcador->Errors->Count());
        $errors = ($errors || $this->tac_UniProduccion->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-20065683
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        $this->EditMode = false;
        if(!$this->FormSubmitted)
            return;

        $Redirect = "LiLiRp_repcond.php";
    }
//End Operation Method

//Show Method @2-EDA33AE4
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->pro_GrupoLiquid->Prepare();
        $this->tac_Embarcador->Prepare();
        $this->tac_UniProduccion->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        if(!$this->FormSubmitted)
        {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->pro_Semana->Errors->ToString();
            $Error .= $this->pro_ID->Errors->ToString();
            $Error .= $this->pro_FechaLiquid->Errors->ToString();
            $Error .= $this->DatePicker1->Errors->ToString();
            $Error .= $this->pro_GrupoLiquid->Errors->ToString();
            $Error .= $this->tac_Embarcador->Errors->ToString();
            $Error .= $this->tac_UniProduccion->Errors->ToString();
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

        $this->pro_Semana->Show();
        $this->pro_ID->Show();
        $this->pro_FechaLiquid->Show();
        $this->DatePicker1->Show();
        $this->pro_GrupoLiquid->Show();
        $this->tac_Embarcador->Show();
        $this->tac_UniProduccion->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End liqembarquesSearch Class @2-FCB6E20C

//Initialize Page @1-3523DB47
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

$FileName = "OpTrRp_repcond.php";
$Redirect = "";
$TemplateFileName = "OpTrRp_repcond.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-35FDCAF9
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
$liqembarquesSearch = new clsRecordliqembarquesSearch();

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

//Execute Components @1-44604631
$Cabecera->Operations();
$liqembarquesSearch->Operation();
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

//Show Page @1-D6673643
$Cabecera->Show("Cabecera");
$liqembarquesSearch->Show();
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
