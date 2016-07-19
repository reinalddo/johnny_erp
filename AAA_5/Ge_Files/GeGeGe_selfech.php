<?php
/**
*       GeGeGe_selfech.phph:  Script para seleccionar fechas o rango de fechas, periodos o rango de periodos.
*       de proposito General.
*       maneja los siguientes conceptos:
*       EL parametro GET pUrl   define el script destino al que se llamara con los parametros ingresados
*       EL parametro GET pTipo define el tipo de parametros a solicitar:
*               . F:        Una fecha
*               . FF:       Un Rango de fecha (Inicio y  Fin)
*               . P         Un Periodo
*               . PP        Un Rango de periodos (Inicio y Fin)
*       GET pNom1 : Nombre del parametro1 (Inicio). Se adjunta al Url del Script destino
*       GET pNom2 : Nombre del parametro2 (Inicio). Se adjunta al Url del Script destino
*       GET pWin  : Nombre de ventana que se abrirá para ejecutar el Script destino
*       GET pAnch : Ancho de la ventana destino
*       GET pLarg : Largo de la ventana destino
*       GET pRepParam : Parametros extra que requiere el Script destino
*       GET pTxtI : Texto que aparecera en pantalla para solicitar el parametro 1
*       GET pTxtF : Texto que aparecera en pantalla para solicitar el parametro 2
*       GET pApli : Tipo de periodo a aplicar (si es tipo P o PP)
*
**/

//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
include(RelativePath . "/LibPhp/SegLib.php");
$giMaxEstado = CCGetParam("pMaxE", 99); //          Maximo estado aceptable de los periodos
$giMinEstado = CCGetParam("pMinE", -1); //          Maximo estado aceptable de los periodos
//End Include Common Files

class clsRecordForm_1 { //Form_1 Class @2-C4A7B320

//Variables @2-B2F7A83E

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

    var $InsertAllowed = false;
    var $UpdateAllowed = false;
    var $DeleteAllowed = false;
    var $ReadAllowed   = false;
    var $EditMode      = false;
    var $ds;
    var $ValidatingControls;
    var $Controls;

    // Class variables
//End Variables

//Class_Initialize Event @2-B2891633
    function clsRecordForm_1()
    {

        global $FileName;
        global $giMaxEstado;
        global $giMinEstado;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record Form_1/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "Form_1";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->pTitul2 = new clsControl(ccsLabel, "pTitul2", "pTitul2", ccsText, "", CCGetRequestParam("pTitul2", $Method));
            $this->pTxtI = new clsControl(ccsLabel, "pTxtI", "pTxtI", ccsText, "", CCGetRequestParam("pTxtI", $Method));
            $this->txt_Desde = new clsControl(ccsTextBox, "txt_Desde", "txt_Desde", ccsDate, Array("dd", "/MMM/", "yy"), CCGetRequestParam("txt_Desde", $Method));
            $this->sel_Desde = new clsControl(ccsListBox, "sel_Desde", "Periodo Inicial", ccsInteger, "", CCGetRequestParam("sel_Desde", $Method));
            $this->sel_Desde->DSType = dsSQL;
            list($this->sel_Desde->BoundColumn, $this->sel_Desde->TextColumn, $this->sel_Desde->DBFormat) = array("per_numperiodo", "tmp_desde", "");
            $this->sel_Desde->ds = new clsDBdatos();
            $this->sel_Desde->ds->Parameters["urlpApli"] = CCGetFromGet("pApli", "");
            $this->sel_Desde->ds->wp = new clsSQLParameters();
            $this->sel_Desde->ds->wp->AddParameter("1", "urlpApli", ccsText, "", "", $this->sel_Desde->ds->Parameters["urlpApli"], "", false);
            $this->sel_Desde->ds->SQL = "select conperiodos.per_aplicacion, conperiodos.per_numperiodo, concat(date_format(conperiodos.per_fecinicial,'%b-%d-%y'),' . . ', '(', conperiodos.per_numperiodo, ')') as tmp_desde,   " .
            "	date_format(conperiodos.per_fecfinal,'%b-%d-%y') as tmp_hasta " .
            "FROM conperiodos JOIN conperiodos con on con.per_aplicacion = 'CO' AND
	               con.per_numperiodo = conperiodos.per_percontable " .
            "where conperiodos.per_aplicacion = '" . $this->sel_Desde->ds->SQLValue($this->sel_Desde->ds->wp->GetDBValue("1"), ccsText) . "' " .
            " AND conperiodos.per_estado BETWEEN $giMinEstado and $giMaxEstado AND
                  con.per_estado between $giMinEstado and $giMaxEstado
            ";
            $this->sel_Desde->ds->Order = "1,2 desc";
            $this->DatePicker1 = new clsDatePicker("DatePicker1", "Form_1", "txt_Desde");
            $this->pTxtF = new clsControl(ccsLabel, "pTxtF", "pTxtF", ccsText, "", CCGetRequestParam("pTxtF", $Method));
            $this->txt_Hasta = new clsControl(ccsTextBox, "txt_Hasta", "txt_Hasta", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("txt_Hasta", $Method));
            $this->DatePicker2 = new clsDatePicker("DatePicker2", "Form_1", "txt_Hasta");
            $this->sel_Hasta = new clsControl(ccsListBox, "sel_Hasta", "Periodo Inicial", ccsInteger, "", CCGetRequestParam("sel_Hasta", $Method));
            $this->sel_Hasta->DSType = dsSQL;
            list($this->sel_Hasta->BoundColumn, $this->sel_Hasta->TextColumn, $this->sel_Hasta->DBFormat) = array("per_numperiodo", "tmp_desde", "");
            $this->sel_Hasta->ds = new clsDBdatos();
            $this->sel_Hasta->ds->Parameters["urlpApli"] = CCGetFromGet("pApli", "");
            $this->sel_Hasta->ds->wp = new clsSQLParameters();
            $this->sel_Hasta->ds->wp->AddParameter("1", "urlpApli", ccsText, "", "", $this->sel_Hasta->ds->Parameters["urlpApli"], "", false);
            $this->sel_Hasta->ds->SQL = "select conperiodos.per_aplicacion, conperiodos.per_numperiodo, concat(date_format(conperiodos.per_fecfinal,'%b-%d-%y'),' . . ', '(', conperiodos.per_numperiodo, ')') as tmp_desde,   " .
            "	date_format(conperiodos.per_fecfinal,'%b-%d-%y') as tmp_hasta " .
            "FROM conperiodos JOIN conperiodos con on con.per_aplicacion = 'CO' AND
	               con.per_numperiodo = conperiodos.per_percontable " .
            "where conperiodos.per_aplicacion = '" . $this->sel_Hasta->ds->SQLValue($this->sel_Hasta->ds->wp->GetDBValue("1"), ccsText) . "' " .
            " AND conperiodos.per_estado BETWEEN $giMinEstado and $giMaxEstado AND
                  con.per_estado between $giMinEstado and $giMaxEstado
            ";
            $this->sel_Hasta->ds->Order = "1,2 desc";
            $this->Button_Insert = new clsButton("Button_Insert");
            $this->Button_Update = new clsButton("Button_Update");
            $this->Button_Delete = new clsButton("Button_Delete");
        }
    }
//End Class_Initialize Event

//Validate Method @2-7CA4CEAD
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->txt_Desde->Validate() && $Validation);
        $Validation = ($this->sel_Desde->Validate() && $Validation);
        $Validation = ($this->txt_Hasta->Validate() && $Validation);
        $Validation = ($this->sel_Hasta->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        $Validation =  $Validation && ($this->txt_Desde->Errors->Count() == 0);
        $Validation =  $Validation && ($this->sel_Desde->Errors->Count() == 0);
        $Validation =  $Validation && ($this->txt_Hasta->Errors->Count() == 0);
        $Validation =  $Validation && ($this->sel_Hasta->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-D69A9DC6
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->pTitul2->Errors->Count());
        $errors = ($errors || $this->pTxtI->Errors->Count());
        $errors = ($errors || $this->txt_Desde->Errors->Count());
        $errors = ($errors || $this->sel_Desde->Errors->Count());
        $errors = ($errors || $this->DatePicker1->Errors->Count());
        $errors = ($errors || $this->pTxtF->Errors->Count());
        $errors = ($errors || $this->txt_Hasta->Errors->Count());
        $errors = ($errors || $this->DatePicker2->Errors->Count());
        $errors = ($errors || $this->sel_Hasta->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-F1FBA6AD
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        if(!$this->FormSubmitted) {
            return;
        }

        if($this->FormSubmitted) {
            $this->PressedButton = "Button_Insert";
            if(strlen(CCGetParam("Button_Insert", ""))) {
                $this->PressedButton = "Button_Insert";
            } else if(strlen(CCGetParam("Button_Update", ""))) {
                $this->PressedButton = "Button_Update";
            } else if(strlen(CCGetParam("Button_Delete", ""))) {
                $this->PressedButton = "Button_Delete";
            }
        }
        $Redirect = $FileName . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "Button_Delete") {
            if(!CCGetEvent($this->Button_Delete->CCSEvents, "OnClick")) {
                $Redirect = "";
            }
        } else if($this->Validate()) {
            if($this->PressedButton == "Button_Insert") {
                if(!CCGetEvent($this->Button_Insert->CCSEvents, "OnClick")) {
                    $Redirect = "";
                }
            } else if($this->PressedButton == "Button_Update") {
                if(!CCGetEvent($this->Button_Update->CCSEvents, "OnClick")) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @2-C9D8C749
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->sel_Desde->Prepare();
        $this->sel_Hasta->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if(!$this->FormSubmitted)
        {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->pTitul2->Errors->ToString();
            $Error .= $this->pTxtI->Errors->ToString();
            $Error .= $this->txt_Desde->Errors->ToString();
            $Error .= $this->sel_Desde->Errors->ToString();
            $Error .= $this->DatePicker1->Errors->ToString();
            $Error .= $this->pTxtF->Errors->ToString();
            $Error .= $this->txt_Hasta->Errors->ToString();
            $Error .= $this->DatePicker2->Errors->ToString();
            $Error .= $this->sel_Hasta->Errors->ToString();
            $Error .= $this->Errors->ToString();
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $CCSForm = $this->EditMode ? $this->ComponentName . ":" . "Edit" : $this->ComponentName;
        $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        $Tpl->SetVar("Action", $this->HTMLFormAction);
        $Tpl->SetVar("HTMLFormName", $this->ComponentName);
        $Tpl->SetVar("HTMLFormEnctype", $this->FormEnctype);
        $this->Button_Update->Visible = $this->EditMode && $this->UpdateAllowed;
        $this->Button_Delete->Visible = $this->EditMode && $this->DeleteAllowed;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        $this->pTitul2->Show();
        $this->pTxtI->Show();
        $this->txt_Desde->Show();
        $this->sel_Desde->Show();
        $this->DatePicker1->Show();
        $this->pTxtF->Show();
        $this->txt_Hasta->Show();
        $this->DatePicker2->Show();
        $this->sel_Hasta->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End Form_1 Class @2-FCB6E20C

//Initialize Page @1-8E97BB7D
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

$FileName = "GeGeGe_selfech.php";
$Redirect = "";
$TemplateFileName = "GeGeGe_selfech.html";
$BlockToParse = "main";
$TemplateEncoding = "";
$FileEncoding = "";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-9AF6D462
$DBdatos = new clsDBdatos();

// Controls
$Form_1 = new clsRecordForm_1();

// Events
include("./GeGeGe_selfech_events.php");
BindEvents();

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

//Execute Components @1-39AFB7D4
$Form_1->Operation();
//End Execute Components

//Go to destination page @1-24062F54
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    $DBdatos->close();
    header("Location: " . $Redirect);
    unset($Form_1);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-0593A433
$Form_1->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
/** Para evitar el mensaje final
if(preg_match("/<\/body>/i", $main_block)) {
    $main_block = preg_replace("/<\/body>/i", "<center><font face=\"Arial\"><small>&#71;ene&#114;a&#116;ed <!-- CCS -->&#119;ith <!-- CCS -->&#67;&#111;deCha&#114;&#103;&#101; <!-- CCS -->&#83;t&#117;&#100;i&#111;.</small></font></center>" . "</body>", $main_block);
} else if(preg_match("/<\/html>/i", $main_block) && !preg_match("/<\/frameset>/i", $main_block)) {
    $main_block = preg_replace("/<\/html>/i", "<center><font face=\"Arial\"><small>&#71;ene&#114;a&#116;ed <!-- CCS -->&#119;ith <!-- CCS -->&#67;&#111;deCha&#114;&#103;&#101; <!-- CCS -->&#83;t&#117;&#100;i&#111;.</small></font></center>" . "</html>", $main_block);
} else if(!preg_match("/<\/frameset>/i", $main_block)) {
    $main_block .= "<center><font face=\"Arial\"><small>&#71;ene&#114;a&#116;ed <!-- CCS -->&#119;ith <!-- CCS -->&#67;&#111;deCha&#114;&#103;&#101; <!-- CCS -->&#83;t&#117;&#100;i&#111;.</small></font></center>";
}
**/
echo $main_block;
//End Show Page

//Unload Page @1-D575FF1A
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBdatos->close();
unset($Form_1);
unset($Tpl);
//End Unload Page


?>
