<?php
/**
*    Altas, Bajas, y Cambios de Activos (tabla conactivos).
*    Generado por CCS
*    @package	 eContab
*    @subpackage Administracion
*    @program    CoAdAc
*    @author     fausto Astudillo H.
*    @version    1.0 01/Dic/05
*/

//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");

//End Include Common Files
include (RelativePath . "/LibPhp/ConLib.php") ;
//Include Page implementation @159-49BEB8FC
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation
/**
*    Clase para manejar Consultas de Activos
*/
Class clsRecordCoAdAc_qry { //CoAdAc_qry Class @364-0D91CF29

//Variables @364-CB19EB75

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

//Class_Initialize Event @364-80DD35FF
    function clsRecordCoAdAc_qry()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record CoAdAc_qry/Error";
        $this->ReadAllowed = false;
        $this->InsertAllowed = false;
        $this->UpdateAllowed = false;
        $this->DeleteAllowed = false;
        if($this->Visible)
        {
            $this->ComponentName = "CoAdAc_qry";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->s_act_CodAuxiliar = new clsControl(ccsTextBox, "s_act_CodAuxiliar", "s_act_CodAuxiliar", ccsInteger, "", CCGetRequestParam("s_act_CodAuxiliar", $Method));
            $this->s_act_SubCategoria = new clsControl(ccsListBox, "s_act_SubCategoria", "s_act_SubCategoria", ccsInteger, "", CCGetRequestParam("s_act_SubCategoria", $Method));
            $this->s_act_SubCategoria->DSType = dsTable;
            list($this->s_act_SubCategoria->BoundColumn, $this->s_act_SubCategoria->TextColumn, $this->s_act_SubCategoria->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->s_act_SubCategoria->ds = new clsDBdatos();
            $this->s_act_SubCategoria->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->s_act_SubCategoria->ds->Parameters["expr367"] = 'ACTSUB';
            $this->s_act_SubCategoria->ds->wp = new clsSQLParameters();
            $this->s_act_SubCategoria->ds->wp->AddParameter("1", "expr367", ccsText, "", "", $this->s_act_SubCategoria->ds->Parameters["expr367"], "", false);
            $this->s_act_SubCategoria->ds->wp->Criterion[1] = $this->s_act_SubCategoria->ds->wp->Operation(opEqual, "par_Clave", $this->s_act_SubCategoria->ds->wp->GetDBValue("1"), $this->s_act_SubCategoria->ds->ToSQL($this->s_act_SubCategoria->ds->wp->GetDBValue("1"), ccsText),false);
            $this->s_act_SubCategoria->ds->Where = $this->s_act_SubCategoria->ds->wp->Criterion[1];
            $this->s_act_Descripcion = new clsControl(ccsTextBox, "s_act_Descripcion", "s_act_Descripcion", ccsText, "", CCGetRequestParam("s_act_Descripcion", $Method));
            $this->s_act_Descripcion1 = new clsControl(ccsTextBox, "s_act_Descripcion1", "s_act_Descripcion1", ccsText, "", CCGetRequestParam("s_act_Descripcion1", $Method));
            $this->s_act_Grupo = new clsControl(ccsListBox, "s_act_Grupo", "s_act_Grupo", ccsInteger, "", CCGetRequestParam("s_act_Grupo", $Method));
            $this->s_act_Grupo->DSType = dsTable;
            list($this->s_act_Grupo->BoundColumn, $this->s_act_Grupo->TextColumn, $this->s_act_Grupo->DBFormat) = array("", "", "");
            $this->s_act_Grupo->ds = new clsDBdatos();
            $this->s_act_Grupo->ds->SQL = "SELECT par_Secuencia, par_Descripcion  " .
"FROM genparametros";
            $this->s_act_Grupo->ds->Parameters["expr371"] = 'ACTGRU';
            $this->s_act_Grupo->ds->wp = new clsSQLParameters();
            $this->s_act_Grupo->ds->wp->AddParameter("1", "expr371", ccsText, "", "", $this->s_act_Grupo->ds->Parameters["expr371"], "", false);
            $this->s_act_Grupo->ds->wp->Criterion[1] = $this->s_act_Grupo->ds->wp->Operation(opEqual, "par_Clave", $this->s_act_Grupo->ds->wp->GetDBValue("1"), $this->s_act_Grupo->ds->ToSQL($this->s_act_Grupo->ds->wp->GetDBValue("1"), ccsText),false);
            $this->s_act_Grupo->ds->Where = $this->s_act_Grupo->ds->wp->Criterion[1];
            $this->s_act_SubGrupo = new clsControl(ccsListBox, "s_act_SubGrupo", "s_act_SubGrupo", ccsInteger, "", CCGetRequestParam("s_act_SubGrupo", $Method));
            $this->s_act_SubGrupo->DSType = dsTable;
            list($this->s_act_SubGrupo->BoundColumn, $this->s_act_SubGrupo->TextColumn, $this->s_act_SubGrupo->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->s_act_SubGrupo->ds = new clsDBdatos();
            $this->s_act_SubGrupo->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->s_act_SubGrupo->ds->Parameters["expr373"] = 'ACTSGR';
            $this->s_act_SubGrupo->ds->wp = new clsSQLParameters();
            $this->s_act_SubGrupo->ds->wp->AddParameter("1", "expr373", ccsText, "", "", $this->s_act_SubGrupo->ds->Parameters["expr373"], "", false);
            $this->s_act_SubGrupo->ds->wp->Criterion[1] = $this->s_act_SubGrupo->ds->wp->Operation(opEqual, "par_Clave", $this->s_act_SubGrupo->ds->wp->GetDBValue("1"), $this->s_act_SubGrupo->ds->ToSQL($this->s_act_SubGrupo->ds->wp->GetDBValue("1"), ccsText),false);
            $this->s_act_SubGrupo->ds->Where = $this->s_act_SubGrupo->ds->wp->Criterion[1];
            $this->s_act_Tipo = new clsControl(ccsListBox, "s_act_Tipo", "s_act_Tipo", ccsInteger, "", CCGetRequestParam("s_act_Tipo", $Method));
            $this->s_act_Tipo->DSType = dsTable;
            list($this->s_act_Tipo->BoundColumn, $this->s_act_Tipo->TextColumn, $this->s_act_Tipo->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->s_act_Tipo->ds = new clsDBdatos();
            $this->s_act_Tipo->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->s_act_Tipo->ds->Parameters["expr375"] = 'ACTCLA';
            $this->s_act_Tipo->ds->wp = new clsSQLParameters();
            $this->s_act_Tipo->ds->wp->AddParameter("1", "expr375", ccsText, "", "", $this->s_act_Tipo->ds->Parameters["expr375"], "", false);
            $this->s_act_Tipo->ds->wp->Criterion[1] = $this->s_act_Tipo->ds->wp->Operation(opEqual, "par_Clave", $this->s_act_Tipo->ds->wp->GetDBValue("1"), $this->s_act_Tipo->ds->ToSQL($this->s_act_Tipo->ds->wp->GetDBValue("1"), ccsText),false);
            $this->s_act_Tipo->ds->Where = $this->s_act_Tipo->ds->wp->Criterion[1];
            $this->ClearParameters = new clsControl(ccsLink, "ClearParameters", "ClearParameters", ccsText, "", CCGetRequestParam("ClearParameters", $Method));
            $this->ClearParameters->Parameters = CCGetQueryString("QueryString", Array("act_CodAuxiliar", "s_act_CodAuxiliar", "s_act_SubCategoria", "s_act_Descripcion", "s_act_Descripcion1", "s_act_Tipo", "s_act_Grupo", "s_act_SubGrupo", "pOpCode", "ccsForm"));
            $this->ClearParameters->Parameters = CCAddParam($this->ClearParameters->Parameters, "pOpCode", 'ADD');
            $this->ClearParameters->Page = "CoAdAc.php";
            $this->Button_DoSearch = new clsButton("Button_DoSearch");
        }
    }
//End Class_Initialize Event

//Validate Method @364-5E2FB7FA
/**
*    Validacion de datos
*
*/
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_act_CodAuxiliar->Validate() && $Validation);
        $Validation = ($this->s_act_SubCategoria->Validate() && $Validation);
        $Validation = ($this->s_act_Descripcion->Validate() && $Validation);
        $Validation = ($this->s_act_Descripcion1->Validate() && $Validation);
        $Validation = ($this->s_act_Grupo->Validate() && $Validation);
        $Validation = ($this->s_act_SubGrupo->Validate() && $Validation);
        $Validation = ($this->s_act_Tipo->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method
/**
*   Analisis de errores generados, para determinar si el proceso es limpio
*
*/
//CheckErrors Method @364-AE068F1B
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_act_CodAuxiliar->Errors->Count());
        $errors = ($errors || $this->s_act_SubCategoria->Errors->Count());
        $errors = ($errors || $this->s_act_Descripcion->Errors->Count());
        $errors = ($errors || $this->s_act_Descripcion1->Errors->Count());
        $errors = ($errors || $this->s_act_Grupo->Errors->Count());
        $errors = ($errors || $this->s_act_SubGrupo->Errors->Count());
        $errors = ($errors || $this->s_act_Tipo->Errors->Count());
        $errors = ($errors || $this->ClearParameters->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method
/**
*   Secuencia de operaciones a ejecutar con cada instancia del objeto.
*   Aplica la consulta
*
*/
//Operation Method @364-D5A16D88
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
        $Redirect = "CoAdAc.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick")) {
                    $Redirect = "";
                } else {
                    $Redirect = "CoAdAc.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", Array("Button_DoSearch")));
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method
/**
*   Presentacion del objeto basado en la plantilla Html y los datos enviados
*/
//Show Method @364-B81F53B3
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->s_act_SubCategoria->Prepare();
        $this->s_act_Grupo->Prepare();
        $this->s_act_SubGrupo->Prepare();
        $this->s_act_Tipo->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        if(!$this->FormSubmitted)
        {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->s_act_CodAuxiliar->Errors->ToString();
            $Error .= $this->s_act_SubCategoria->Errors->ToString();
            $Error .= $this->s_act_Descripcion->Errors->ToString();
            $Error .= $this->s_act_Descripcion1->Errors->ToString();
            $Error .= $this->s_act_Grupo->Errors->ToString();
            $Error .= $this->s_act_SubGrupo->Errors->ToString();
            $Error .= $this->s_act_Tipo->Errors->ToString();
            $Error .= $this->ClearParameters->Errors->ToString();
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

        $this->s_act_CodAuxiliar->Show();
        $this->s_act_SubCategoria->Show();
        $this->s_act_Descripcion->Show();
        $this->s_act_Descripcion1->Show();
        $this->s_act_Grupo->Show();
        $this->s_act_SubGrupo->Show();
        $this->s_act_Tipo->Show();
        $this->ClearParameters->Show();
        $this->Button_DoSearch->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End CoAdAc_qry Class @364-FCB6E20C
/**
*   Lista de resultados obtenidos de la base de datos, aplicando las condiciones
*   de CoAdAc_qry
*
*/
class clsGridCoAdAc_list { //CoAdAc_list class @21-414C438A

//Variables @21-51BE2206

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
    var $AltRowControls;
    var $IsAltRow;
    var $Sorter_act_CodAuxiliar;
    var $Sorter_act_SubCategoria;
    var $Sorter_act_Descripcion;
    var $Sorter_act_Tipo;
    var $Sorter_act_Grupo;
    var $Sorter_act_SubGrupo;
    var $Navigator;
//End Variables
/**
*    Inicializador de la clase
*/
//Class_Initialize Event @21-86ECAB4C
    function clsGridCoAdAc_list()
    {
        global $FileName;
        $this->ComponentName = "CoAdAc_list";
        $this->Visible = True;
        $this->IsAltRow = false;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid CoAdAc_list";
        $this->ds = new clsCoAdAc_listDataSource();
        $this->PageSize = 15;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        $this->SorterName = CCGetParam("CoAdAc_listOrder", "");
        $this->SorterDirection = CCGetParam("CoAdAc_listDir", "");

        $this->act_CodAuxiliar = new clsControl(ccsLink, "act_CodAuxiliar", "act_CodAuxiliar", ccsInteger, "", CCGetRequestParam("act_CodAuxiliar", ccsGet));
        $this->act_SubCategoria = new clsControl(ccsLabel, "act_SubCategoria", "act_SubCategoria", ccsInteger, "", CCGetRequestParam("act_SubCategoria", ccsGet));
        $this->act_Descripcion = new clsControl(ccsLabel, "act_Descripcion", "act_Descripcion", ccsText, "", CCGetRequestParam("act_Descripcion", ccsGet));
        $this->act_Tipo = new clsControl(ccsLabel, "act_Tipo", "act_Tipo", ccsText, "", CCGetRequestParam("act_Tipo", ccsGet));
        $this->act_Grupo = new clsControl(ccsLabel, "act_Grupo", "act_Grupo", ccsText, "", CCGetRequestParam("act_Grupo", ccsGet));
        $this->act_SubGrupo = new clsControl(ccsLabel, "act_SubGrupo", "act_SubGrupo", ccsText, "", CCGetRequestParam("act_SubGrupo", ccsGet));
        $this->Alt_act_CodAuxiliar = new clsControl(ccsLink, "Alt_act_CodAuxiliar", "Alt_act_CodAuxiliar", ccsInteger, "", CCGetRequestParam("Alt_act_CodAuxiliar", ccsGet));
        $this->Alt_act_SubCategoria = new clsControl(ccsLabel, "Alt_act_SubCategoria", "Alt_act_SubCategoria", ccsInteger, "", CCGetRequestParam("Alt_act_SubCategoria", ccsGet));
        $this->Alt_act_Descripcion = new clsControl(ccsLabel, "Alt_act_Descripcion", "Alt_act_Descripcion", ccsText, "", CCGetRequestParam("Alt_act_Descripcion", ccsGet));
        $this->Alt_act_Tipo = new clsControl(ccsLabel, "Alt_act_Tipo", "Alt_act_Tipo", ccsText, "", CCGetRequestParam("Alt_act_Tipo", ccsGet));
        $this->Alt_act_Grupo = new clsControl(ccsLabel, "Alt_act_Grupo", "Alt_act_Grupo", ccsText, "", CCGetRequestParam("Alt_act_Grupo", ccsGet));
        $this->Alt_act_SubGrupo = new clsControl(ccsLabel, "Alt_act_SubGrupo", "Alt_act_SubGrupo", ccsText, "", CCGetRequestParam("Alt_act_SubGrupo", ccsGet));
        $this->Sorter_act_CodAuxiliar = new clsSorter($this->ComponentName, "Sorter_act_CodAuxiliar", $FileName);
        $this->Sorter_act_SubCategoria = new clsSorter($this->ComponentName, "Sorter_act_SubCategoria", $FileName);
        $this->Sorter_act_Descripcion = new clsSorter($this->ComponentName, "Sorter_act_Descripcion", $FileName);
        $this->Sorter_act_Tipo = new clsSorter($this->ComponentName, "Sorter_act_Tipo", $FileName);
        $this->Sorter_act_Grupo = new clsSorter($this->ComponentName, "Sorter_act_Grupo", $FileName);
        $this->Sorter_act_SubGrupo = new clsSorter($this->ComponentName, "Sorter_act_SubGrupo", $FileName);
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple);
    }
//End Class_Initialize Event
/**
*   Inicializador del contenido
*/
//Initialize Method @21-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method
/**
*   Formateo y presentacion de los datos en rejilla, aplicando la plantilla Html
*   Analiza la existencia de errores, formatea registros alternos.
*   Presenta un bloque para el caso de que no existieran registros como respuesta a la consulta
*/
//Show Method @21-5A256420
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["urls_act_CodAuxiliar"] = CCGetFromGet("s_act_CodAuxiliar", "");
        $this->ds->Parameters["urls_act_SubCategoria"] = CCGetFromGet("s_act_SubCategoria", "");
        $this->ds->Parameters["urls_act_Descripcion"] = CCGetFromGet("s_act_Descripcion", "");
        $this->ds->Parameters["urls_act_Descripcion1"] = CCGetFromGet("s_act_Descripcion1", "");
        $this->ds->Parameters["urls_act_Tipo"] = CCGetFromGet("s_act_Tipo", "");
        $this->ds->Parameters["urls_act_Grupo"] = CCGetFromGet("s_act_Grupo", "");
        $this->ds->Parameters["urls_act_SubGrupo"] = CCGetFromGet("s_act_SubGrupo", "");
        $this->ds->Parameters["expr315"] = 'ACTSGR';
        $this->ds->Parameters["expr316"] = 'ACTCLA';
        $this->ds->Parameters["expr317"] = 'ACTGRU';
        $this->ds->Parameters["expr318"] = 'ACTSGR';
        $this->ds->Parameters["expr319"] = 'ACTSUB';

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
                if(!$this->IsAltRow)
                {
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/Row";
                    $this->act_CodAuxiliar->SetValue($this->ds->act_CodAuxiliar->GetValue());
                    $this->act_CodAuxiliar->Parameters = CCGetQueryString("QueryString", Array("pOpCode", "ccsForm"));
                    $this->act_CodAuxiliar->Parameters = CCAddParam($this->act_CodAuxiliar->Parameters, "act_CodAuxiliar", $this->ds->f("act_CodAuxiliar"));
                    $this->act_CodAuxiliar->Parameters = CCAddParam($this->act_CodAuxiliar->Parameters, "pOpCode", 'UPD');
                    $this->act_CodAuxiliar->Page = "CoAdAc_mant.php";
                    $this->act_SubCategoria->SetValue($this->ds->act_SubCategoria->GetValue());
                    $this->act_Descripcion->SetValue($this->ds->act_Descripcion->GetValue());
                    $this->act_Tipo->SetValue($this->ds->act_Tipo->GetValue());
                    $this->act_Grupo->SetValue($this->ds->act_Grupo->GetValue());
                    $this->act_SubGrupo->SetValue($this->ds->act_SubGrupo->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->act_CodAuxiliar->Show();
                    $this->act_SubCategoria->Show();
                    $this->act_Descripcion->Show();
                    $this->act_Tipo->Show();
                    $this->act_Grupo->Show();
                    $this->act_SubGrupo->Show();
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                    $Tpl->parse("Row", true);
                }
                else
                {
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/AltRow";
                    $this->Alt_act_CodAuxiliar->SetValue($this->ds->Alt_act_CodAuxiliar->GetValue());
                    $this->Alt_act_CodAuxiliar->Parameters = CCGetQueryString("QueryString", Array("pOpCode", "ccsForm"));
                    $this->Alt_act_CodAuxiliar->Parameters = CCAddParam($this->Alt_act_CodAuxiliar->Parameters, "act_CodAuxiliar", $this->ds->f("act_CodAuxiliar"));
                    $this->Alt_act_CodAuxiliar->Parameters = CCAddParam($this->Alt_act_CodAuxiliar->Parameters, "pOpCode", 'UPD');
                    $this->Alt_act_CodAuxiliar->Page = "CoAdAc_mant.php";
                    $this->Alt_act_SubCategoria->SetValue($this->ds->Alt_act_SubCategoria->GetValue());
                    $this->Alt_act_Descripcion->SetValue($this->ds->Alt_act_Descripcion->GetValue());
                    $this->Alt_act_Tipo->SetValue($this->ds->Alt_act_Tipo->GetValue());
                    $this->Alt_act_Grupo->SetValue($this->ds->Alt_act_Grupo->GetValue());
                    $this->Alt_act_SubGrupo->SetValue($this->ds->Alt_act_SubGrupo->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->Alt_act_CodAuxiliar->Show();
                    $this->Alt_act_SubCategoria->Show();
                    $this->Alt_act_Descripcion->Show();
                    $this->Alt_act_Tipo->Show();
                    $this->Alt_act_Grupo->Show();
                    $this->Alt_act_SubGrupo->Show();
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                    $Tpl->parseto("AltRow", true, "Row");
                }
                $this->IsAltRow = (!$this->IsAltRow);
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
        $this->Navigator->PageNumber = $this->ds->AbsolutePage;
        $this->Navigator->TotalPages = $this->ds->PageCount();
        $this->Sorter_act_CodAuxiliar->Show();
        $this->Sorter_act_SubCategoria->Show();
        $this->Sorter_act_Descripcion->Show();
        $this->Sorter_act_Tipo->Show();
        $this->Sorter_act_Grupo->Show();
        $this->Sorter_act_SubGrupo->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method
/**
*   Analiza la existencia de errores en cada campo y determina si el bloque
*   es valido o contiene errores
*/
//GetErrors Method @21-4819DDA7
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->act_CodAuxiliar->Errors->ToString();
        $errors .= $this->act_SubCategoria->Errors->ToString();
        $errors .= $this->act_Descripcion->Errors->ToString();
        $errors .= $this->act_Tipo->Errors->ToString();
        $errors .= $this->act_Grupo->Errors->ToString();
        $errors .= $this->act_SubGrupo->Errors->ToString();
        $errors .= $this->Alt_act_CodAuxiliar->Errors->ToString();
        $errors .= $this->Alt_act_SubCategoria->Errors->ToString();
        $errors .= $this->Alt_act_Descripcion->Errors->ToString();
        $errors .= $this->Alt_act_Tipo->Errors->ToString();
        $errors .= $this->Alt_act_Grupo->Errors->ToString();
        $errors .= $this->Alt_act_SubGrupo->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End CoAdAc_list Class @21-FCB6E20C
/**
*   Origen de datos para la lista de activos (CoAdAc_list)
*
*/
class clsCoAdAc_listDataSource extends clsDBdatos {  //CoAdAc_listDataSource Class @21-C9EFB278

//DataSource Variables @21-182B1C3C
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $CountSQL;
    var $wp;


    // Datasource fields
    /*
    *  integer Codigo de Auxiliar
    */
    var $act_CodAuxiliar;
    /*
    *  string Sub categoria
    */
    var $act_SubCategoria;
    /*
    *  string Descripcion
    */
    var $act_Descripcion;
    /*
    *  string Tipo de auxiliar
    */
    var $act_Tipo;
    /*
    *  string Nombre del grupo
    */
    var $act_Grupo;
    /*
    *  string Nombre del subgrupo
    */
    var $act_SubGrupo;
    /*
    *  integer Codigo de Auxiliar, en registro Alterno
    */
    var $Alt_act_CodAuxiliar;
    /*
    *  string Subcategoria, en registro Alterno
    */
    var $Alt_act_SubCategoria;
    /*
    *  styring Descripcion, en registro Alterno
    */
    var $Alt_act_Descripcion;
    /*
    *  string Tipo de Activo, en registro Alterno
    */
    var $Alt_act_Tipo;
    /*
    *  string Descripcion del grupo, en registro Alterno
    */
    var $Alt_act_Grupo;
    /*
    *  integer Subgrupo, en registro Alterno
    */
    var $Alt_act_SubGrupo;
//End DataSource Variables
/**
*   Inicializador de clase
*/
//Class_Initialize Event @21-1C88F210
    function clsCoAdAc_listDataSource()
    {
        $this->ErrorBlock = "Grid CoAdAc_list";
        $this->Initialize();
        $this->act_CodAuxiliar = new clsField("act_CodAuxiliar", ccsInteger, "");
        $this->act_SubCategoria = new clsField("act_SubCategoria", ccsInteger, "");
        $this->act_Descripcion = new clsField("act_Descripcion", ccsText, "");
        $this->act_Tipo = new clsField("act_Tipo", ccsText, "");
        $this->act_Grupo = new clsField("act_Grupo", ccsText, "");
        $this->act_SubGrupo = new clsField("act_SubGrupo", ccsText, "");
        $this->Alt_act_CodAuxiliar = new clsField("Alt_act_CodAuxiliar", ccsInteger, "");
        $this->Alt_act_SubCategoria = new clsField("Alt_act_SubCategoria", ccsInteger, "");
        $this->Alt_act_Descripcion = new clsField("Alt_act_Descripcion", ccsText, "");
        $this->Alt_act_Tipo = new clsField("Alt_act_Tipo", ccsText, "");
        $this->Alt_act_Grupo = new clsField("Alt_act_Grupo", ccsText, "");
        $this->Alt_act_SubGrupo = new clsField("Alt_act_SubGrupo", ccsText, "");

    }
//End Class_Initialize Event
/**
*   Definicion de la clausula 'Order By' de la instruccion SQL
*/
//SetOrder Method @21-96086A7B
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "act_Descripcion";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection,
            array("Sorter_act_CodAuxiliar" => array("act_CodAuxiliar", ""),
            "Sorter_act_SubCategoria" => array("act_SubCategoria", ""),
            "Sorter_act_Descripcion" => array("act_Descripcion", ""),
            "Sorter_act_Tipo" => array("act_Tipo", ""),
            "Sorter_act_Grupo" => array("act_Grupo", ""),
            "Sorter_act_SubGrupo" => array("act_SubGrupo", "")));
    }
//End SetOrder Method
/**
*   Armado del Bloque Where del comando SQl de Seleccion en funcion de los datos sometidos por CoAdAc_qry
*
*/
//Prepare Method @21-C934D224
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_act_CodAuxiliar", ccsInteger, "", "", $this->Parameters["urls_act_CodAuxiliar"], "", false);
        $this->wp->AddParameter("2", "urls_act_SubCategoria", ccsInteger, "", "", $this->Parameters["urls_act_SubCategoria"], "", false);
        $this->wp->AddParameter("3", "urls_act_Descripcion", ccsText, "", "", $this->Parameters["urls_act_Descripcion"], "", false);
        $this->wp->AddParameter("4", "urls_act_Descripcion1", ccsText, "", "", $this->Parameters["urls_act_Descripcion1"], "", false);
        $this->wp->AddParameter("5", "urls_act_Tipo", ccsInteger, "", "", $this->Parameters["urls_act_Tipo"], "", false);
        $this->wp->AddParameter("6", "urls_act_Grupo", ccsInteger, "", "", $this->Parameters["urls_act_Grupo"], "", false);
        $this->wp->AddParameter("7", "urls_act_SubGrupo", ccsInteger, "", "", $this->Parameters["urls_act_SubGrupo"], "", false);
        $this->wp->AddParameter("8", "expr315", ccsText, "", "", $this->Parameters["expr315"], "", false);
        $this->wp->AddParameter("9", "expr316", ccsText, "", "", $this->Parameters["expr316"], "", false);
        $this->wp->AddParameter("10", "expr317", ccsText, "", "", $this->Parameters["expr317"], "", false);
        $this->wp->AddParameter("11", "expr318", ccsText, "", "", $this->Parameters["expr318"], "", false);
        $this->wp->AddParameter("12", "expr319", ccsText, "", "", $this->Parameters["expr319"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "act_CodAuxiliar", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opEqual, "act_SubCategoria", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsInteger),false);
        $this->wp->Criterion[3] = $this->wp->Operation(opContains, "act_Descripcion", $this->wp->GetDBValue("3"), $this->ToSQL($this->wp->GetDBValue("3"), ccsText),false);
        $this->wp->Criterion[4] = $this->wp->Operation(opContains, "act_Descripcion1", $this->wp->GetDBValue("4"), $this->ToSQL($this->wp->GetDBValue("4"), ccsText),false);
        $this->wp->Criterion[5] = $this->wp->Operation(opEqual, "act_Tipo", $this->wp->GetDBValue("5"), $this->ToSQL($this->wp->GetDBValue("5"), ccsInteger),false);
        $this->wp->Criterion[6] = $this->wp->Operation(opEqual, "act_Grupo", $this->wp->GetDBValue("6"), $this->ToSQL($this->wp->GetDBValue("6"), ccsInteger),false);
        $this->wp->Criterion[7] = $this->wp->Operation(opEqual, "act_SubGrupo", $this->wp->GetDBValue("7"), $this->ToSQL($this->wp->GetDBValue("7"), ccsInteger),false);
        $this->wp->Criterion[8] = $this->wp->Operation(opEqual, "sgr.par_Clave", $this->wp->GetDBValue("8"), $this->ToSQL($this->wp->GetDBValue("8"), ccsText),false);
        $this->wp->Criterion[9] = $this->wp->Operation(opEqual, "cla.par_Clave", $this->wp->GetDBValue("9"), $this->ToSQL($this->wp->GetDBValue("9"), ccsText),false);
        $this->wp->Criterion[10] = $this->wp->Operation(opEqual, "gru.par_Clave", $this->wp->GetDBValue("10"), $this->ToSQL($this->wp->GetDBValue("10"), ccsText),false);
        $this->wp->Criterion[11] = $this->wp->Operation(opEqual, "sgr.par_Clave", $this->wp->GetDBValue("11"), $this->ToSQL($this->wp->GetDBValue("11"), ccsText),false);
        $this->wp->Criterion[12] = $this->wp->Operation(opEqual, "sca.par_Clave", $this->wp->GetDBValue("12"), $this->ToSQL($this->wp->GetDBValue("12"), ccsText),false);
        $this->Where = $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->Criterion[1], $this->wp->Criterion[2]), $this->wp->Criterion[3]), $this->wp->Criterion[4]), $this->wp->Criterion[5]), $this->wp->Criterion[6]), $this->wp->Criterion[7]), $this->wp->Criterion[8]), $this->wp->Criterion[9]), $this->wp->Criterion[10]), $this->wp->Criterion[11]), $this->wp->Criterion[12]);
    }
//End Prepare Method
/*
*   Armado y ejecucion de la Instruccion SQL final, movilizando el recorset hacia la pagina deseada
*/
//Open Method @21-14C0FC5B
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM (((conactivos LEFT JOIN genparametros cla ON conactivos.act_Tipo = cla.par_Secuencia) LEFT JOIN genparametros gru ON conactivos.act_Grupo = gru.par_Secuencia) LEFT JOIN genparametros sgr ON conactivos.act_SubGrupo = sgr.par_Secuencia) LEFT JOIN genparametros sca ON conactivos.act_SubCategoria = sca.par_Secuencia";
        $this->SQL = "SELECT conactivos.*, cla.par_Descripcion AS cla_par_Descripcion, gru.par_Descripcion AS gru_par_Descripcion, sgr.par_Descripcion AS sgr_par_Descripcion, sca.par_Secuencia AS sca_par_Secuencia, concat(act_descripcion, \" \", ifnull(act_descripcion1,'')) AS act_Descripcion, concat(act_descripcion, \" \", act_descripcion1) AS Alt_act_Descripcion  " .
        "FROM (((conactivos LEFT JOIN genparametros cla ON conactivos.act_Tipo = cla.par_Secuencia) LEFT JOIN genparametros gru ON conactivos.act_Grupo = gru.par_Secuencia) LEFT JOIN genparametros sgr ON conactivos.act_SubGrupo = sgr.par_Secuencia) LEFT JOIN genparametros sca ON conactivos.act_SubCategoria = sca.par_Secuencia";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method
/**
*   Trasladar los valores del recorset resultante, hacia los objetos definidos en la rejilla de presentacion
*/
//SetValues Method @21-23EEA732
    function SetValues()
    {
        $this->act_CodAuxiliar->SetDBValue(trim($this->f("act_CodAuxiliar")));
        $this->act_SubCategoria->SetDBValue(trim($this->f("act_SubCategoria")));
        $this->act_Descripcion->SetDBValue($this->f("act_Descripcion"));
        $this->act_Tipo->SetDBValue($this->f("cla_par_Descripcion"));
        $this->act_Grupo->SetDBValue($this->f("gru_par_Descripcion"));
        $this->act_SubGrupo->SetDBValue($this->f("sgr_par_Descripcion"));
        $this->Alt_act_CodAuxiliar->SetDBValue(trim($this->f("act_CodAuxiliar")));
        $this->Alt_act_SubCategoria->SetDBValue(trim($this->f("act_SubCategoria")));
        $this->Alt_act_Descripcion->SetDBValue($this->f("Alt_act_Descripcion"));
        $this->Alt_act_Tipo->SetDBValue($this->f("cla_par_Descripcion"));
        $this->Alt_act_Grupo->SetDBValue($this->f("gru_par_Descripcion"));
        $this->Alt_act_SubGrupo->SetDBValue($this->f("sgr_par_Descripcion"));
    }
//End SetValues Method

} //End CoAdAc_listDataSource Class @21-FCB6E20C







//Initialize Page @1-E6474763
// Variables
/*
* Nombre de archivo de este script
*/
$FileName = "";
/*
* Url a redireccionar luego de ejecutar este script
*/
$Redirect = "";
/*
* Contenido Html que genera durante la ejecucion
*/
$Tpl = "";
/*
* Nombre del archivo fisico de la plantilla Html asociada
*/
$TemplateFileName = "";
/*
* Seccion de la plantilla que se compila
*/
$BlockToParse = "";
/*
* Nombre del componente que se compila
*/
$ComponentName = "";

// Events;
$CCSEvents = "";
$CCSEventResult = "";

$FileName = "CoAdAc.php";
$Redirect = "";
$TemplateFileName = "CoAdAc.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-9DCC624D
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
$CoAdAc_qry = new clsRecordCoAdAc_qry();
$CoAdAc_list = new clsGridCoAdAc_list();
$CoAdAc_list->Initialize();

// Events
include("./CoAdAc_events.php");
BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-F6D6C629
$Cabecera->Operations();
$CoAdAc_qry->Operation();
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

//Show Page @1-F9006CB9
$Cabecera->Show("Cabecera");
$CoAdAc_qry->Show();
$CoAdAc_list->Show();
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
