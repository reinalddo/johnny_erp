<?php
/**
*    Mantenimiento de  la tabla de Auxiliares "no-personas", es decir Activos y conceptos basicos.
*    @abstract   Todos los auxiliares que no correspondan a nu concepto de persona natural o juridica,
*		 deben tener un registro en la tabla conactivos, de cuyo mantenimiento se encarga este script.
*		 La plantilla de captura es generica, cierta informacion debe ajustarse al contexto de
*		 la naturaleza del auxiliar.
*    		 Generado por CCS
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

Class clsRecordCoAdAc_mant { //CoAdAc_mant Class @99-2E276552

//Variables @99-4A82E0A3

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

    var $InsertAllowed;
    var $UpdateAllowed;
    var $DeleteAllowed;
    var $ds;
    var $EditMode;
    var $ValidatingControls;
    var $Controls;

    // Class variables
//End Variables

//Class_Initialize Event @99-A8675C2B
    function clsRecordCoAdAc_mant()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record CoAdAc_mant/Error";
        $this->ds = new clsCoAdAc_mantDataSource();
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "CoAdAc_mant";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->lbTitulo = new clsControl(ccsLabel, "lbTitulo", "lbTitulo", ccsText, "", CCGetRequestParam("lbTitulo", $Method));
            $this->lbCategAux = new clsControl(ccsListBox, "lbCategAux", "Categoria", ccsText, "", CCGetRequestParam("lbCategAux", $Method));
            $this->lbCategAux->DSType = dsTable;
            list($this->lbCategAux->BoundColumn, $this->lbCategAux->TextColumn, $this->lbCategAux->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->lbCategAux->ds = new clsDBdatos();
            $this->lbCategAux->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->lbCategAux->ds->Parameters["expr101"] = 'CAUTI';
            $this->lbCategAux->ds->Parameters["expr102"] = 'Activo';
            $this->lbCategAux->ds->wp = new clsSQLParameters();
            $this->lbCategAux->ds->wp->AddParameter("1", "expr101", ccsText, "", "", $this->lbCategAux->ds->Parameters["expr101"], "", false);
            $this->lbCategAux->ds->wp->AddParameter("2", "expr102", ccsText, "", "", $this->lbCategAux->ds->Parameters["expr102"], "", false);
            $this->lbCategAux->ds->wp->Criterion[1] = $this->lbCategAux->ds->wp->Operation(opEqual, "par_Clave", $this->lbCategAux->ds->wp->GetDBValue("1"), $this->lbCategAux->ds->ToSQL($this->lbCategAux->ds->wp->GetDBValue("1"), ccsText),false);
            $this->lbCategAux->ds->wp->Criterion[2] = $this->lbCategAux->ds->wp->Operation(opEqual, "par_Valor1", $this->lbCategAux->ds->wp->GetDBValue("2"), $this->lbCategAux->ds->ToSQL($this->lbCategAux->ds->wp->GetDBValue("2"), ccsText),false);
            $this->lbCategAux->ds->Where = $this->lbCategAux->ds->wp->opAND(false, $this->lbCategAux->ds->wp->Criterion[1], $this->lbCategAux->ds->wp->Criterion[2]);
            $this->act_CodAuxiliar = new clsControl(ccsTextBox, "act_CodAuxiliar", "Codigo de  Auxiliar", ccsInteger, "", CCGetRequestParam("act_CodAuxiliar", $Method));
            $this->act_Descripcion = new clsControl(ccsTextBox, "act_Descripcion", "Descripción", ccsText, "", CCGetRequestParam("act_Descripcion", $Method));
            $this->act_Descripcion->Required = true;
            $this->act_Descripcion1 = new clsControl(ccsTextBox, "act_Descripcion1", "Descripción Adicional", ccsText, "", CCGetRequestParam("act_Descripcion1", $Method));
            $this->act_SubCategoria = new clsControl(ccsListBox, "act_SubCategoria", "Sub Categoria", ccsInteger, "", CCGetRequestParam("act_SubCategoria", $Method));
            $this->act_SubCategoria->DSType = dsTable;
            list($this->act_SubCategoria->BoundColumn, $this->act_SubCategoria->TextColumn, $this->act_SubCategoria->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->act_SubCategoria->ds = new clsDBdatos();
            $this->act_SubCategoria->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->act_SubCategoria->ds->Parameters["expr105"] = 'ACTSUB';
            $this->act_SubCategoria->ds->wp = new clsSQLParameters();
            $this->act_SubCategoria->ds->wp->AddParameter("1", "expr105", ccsText, "", "", $this->act_SubCategoria->ds->Parameters["expr105"], "", false);
            $this->act_SubCategoria->ds->wp->Criterion[1] = $this->act_SubCategoria->ds->wp->Operation(opEqual, "par_Clave", $this->act_SubCategoria->ds->wp->GetDBValue("1"), $this->act_SubCategoria->ds->ToSQL($this->act_SubCategoria->ds->wp->GetDBValue("1"), ccsText),false);
            $this->act_SubCategoria->ds->Where = $this->act_SubCategoria->ds->wp->Criterion[1];
            $this->act_SubCategoria->Required = true;
            $this->act_UniMedida = new clsControl(ccsListBox, "act_UniMedida", "Unidad de Medida", ccsInteger, "", CCGetRequestParam("act_UniMedida", $Method));
            $this->act_UniMedida->DSType = dsTable;
            list($this->act_UniMedida->BoundColumn, $this->act_UniMedida->TextColumn, $this->act_UniMedida->DBFormat) = array("uni_CodUnidad", "uni_Descripcion", "");
            $this->act_UniMedida->ds = new clsDBdatos();
            $this->act_UniMedida->ds->SQL = "SELECT *  " .
"FROM genunmedida";
            $this->act_UniMedida->Required = true;
            $this->act_Marca = new clsControl(ccsListBox, "act_Marca", "Marca", ccsInteger, "", CCGetRequestParam("act_Marca", $Method));
            $this->act_Marca->DSType = dsTable;
            list($this->act_Marca->BoundColumn, $this->act_Marca->TextColumn, $this->act_Marca->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->act_Marca->ds = new clsDBdatos();
            $this->act_Marca->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->act_Marca->ds->Parameters["expr162"] = "IMARCA";
            $this->act_Marca->ds->wp = new clsSQLParameters();
            $this->act_Marca->ds->wp->AddParameter("1", "expr162", ccsText, "", "", $this->act_Marca->ds->Parameters["expr162"], "", false);
            $this->act_Marca->ds->wp->Criterion[1] = $this->act_Marca->ds->wp->Operation(opEqual, "par_Clave", $this->act_Marca->ds->wp->GetDBValue("1"), $this->act_Marca->ds->ToSQL($this->act_Marca->ds->wp->GetDBValue("1"), ccsText),false);
            $this->act_Marca->ds->Where = $this->act_Marca->ds->wp->Criterion[1];
            $this->act_Modelo = new clsControl(ccsTextBox, "act_Modelo", "Act Modelo", ccsText, "", CCGetRequestParam("act_Modelo", $Method));
            $this->act_NumSerie = new clsControl(ccsTextBox, "act_NumSerie", "Act Num Serie", ccsText, "", CCGetRequestParam("act_NumSerie", $Method));
            $this->act_Tipo = new clsControl(ccsListBox, "act_Tipo", "Clase de Auxiliar", ccsInteger, "", CCGetRequestParam("act_Tipo", $Method));
            $this->act_Tipo->DSType = dsTable;
            list($this->act_Tipo->BoundColumn, $this->act_Tipo->TextColumn, $this->act_Tipo->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->act_Tipo->ds = new clsDBdatos();
            $this->act_Tipo->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->act_Tipo->ds->Parameters["expr180"] = 'ACTCLA';
            $this->act_Tipo->ds->wp = new clsSQLParameters();
            $this->act_Tipo->ds->wp->AddParameter("1", "expr180", ccsText, "", "", $this->act_Tipo->ds->Parameters["expr180"], "", false);
            $this->act_Tipo->ds->wp->Criterion[1] = $this->act_Tipo->ds->wp->Operation(opEqual, "par_Clave", $this->act_Tipo->ds->wp->GetDBValue("1"), $this->act_Tipo->ds->ToSQL($this->act_Tipo->ds->wp->GetDBValue("1"), ccsText),false);
            $this->act_Tipo->ds->Where = $this->act_Tipo->ds->wp->Criterion[1];
            $this->act_Tipo->Required = true;
            $this->act_Grupo = new clsControl(ccsListBox, "act_Grupo", "Grupo al que pertenece", ccsInteger, "", CCGetRequestParam("act_Grupo", $Method));
            $this->act_Grupo->DSType = dsTable;
            list($this->act_Grupo->BoundColumn, $this->act_Grupo->TextColumn, $this->act_Grupo->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->act_Grupo->ds = new clsDBdatos();
            $this->act_Grupo->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->act_Grupo->ds->Parameters["expr179"] = 'ACTGRU';
            $this->act_Grupo->ds->wp = new clsSQLParameters();
            $this->act_Grupo->ds->wp->AddParameter("1", "expr179", ccsText, "", "", $this->act_Grupo->ds->Parameters["expr179"], "", false);
            $this->act_Grupo->ds->wp->Criterion[1] = $this->act_Grupo->ds->wp->Operation(opEqual, "par_Clave", $this->act_Grupo->ds->wp->GetDBValue("1"), $this->act_Grupo->ds->ToSQL($this->act_Grupo->ds->wp->GetDBValue("1"), ccsText),false);
            $this->act_Grupo->ds->Where = $this->act_Grupo->ds->wp->Criterion[1];
            $this->act_Grupo->Required = true;
            $this->act_SubGrupo = new clsControl(ccsListBox, "act_SubGrupo", "Sub Grupo", ccsInteger, "", CCGetRequestParam("act_SubGrupo", $Method));
            $this->act_SubGrupo->DSType = dsTable;
            list($this->act_SubGrupo->BoundColumn, $this->act_SubGrupo->TextColumn, $this->act_SubGrupo->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->act_SubGrupo->ds = new clsDBdatos();
            $this->act_SubGrupo->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->act_SubGrupo->ds->Parameters["expr178"] = 'ACTSGR';
            $this->act_SubGrupo->ds->wp = new clsSQLParameters();
            $this->act_SubGrupo->ds->wp->AddParameter("1", "expr178", ccsText, "", "", $this->act_SubGrupo->ds->Parameters["expr178"], "", false);
            $this->act_SubGrupo->ds->wp->Criterion[1] = $this->act_SubGrupo->ds->wp->Operation(opEqual, "par_Clave", $this->act_SubGrupo->ds->wp->GetDBValue("1"), $this->act_SubGrupo->ds->ToSQL($this->act_SubGrupo->ds->wp->GetDBValue("1"), ccsText),false);
            $this->act_SubGrupo->ds->Where = $this->act_SubGrupo->ds->wp->Criterion[1];
            $this->Button_Insert = new clsButton("Button_Insert");
            $this->Button_Update = new clsButton("Button_Update");
            $this->Button_Delete = new clsButton("Button_Delete");
            $this->btBusqueda = new clsButton("btBusqueda");
            $this->btNuevo = new clsButton("btNuevo");
            if(!$this->FormSubmitted) {
                if(!is_array($this->lbCategAux->Value) && !strlen($this->lbCategAux->Value) && $this->lbCategAux->Value !== false)
                $this->lbCategAux->SetText(0);
                if(!is_array($this->act_Descripcion1->Value) && !strlen($this->act_Descripcion1->Value) && $this->act_Descripcion1->Value !== false)
                $this->act_Descripcion1->SetText(" ");
                if(!is_array($this->act_UniMedida->Value) && !strlen($this->act_UniMedida->Value) && $this->act_UniMedida->Value !== false)
                $this->act_UniMedida->SetText(1);
                if(!is_array($this->act_Marca->Value) && !strlen($this->act_Marca->Value) && $this->act_Marca->Value !== false)
                $this->act_Marca->SetText(9999);
                if(!is_array($this->act_Modelo->Value) && !strlen($this->act_Modelo->Value) && $this->act_Modelo->Value !== false)
                $this->act_Modelo->SetText(' ');
                if(!is_array($this->act_NumSerie->Value) && !strlen($this->act_NumSerie->Value) && $this->act_NumSerie->Value !== false)
                $this->act_NumSerie->SetText(' ');
                if(!is_array($this->act_Tipo->Value) && !strlen($this->act_Tipo->Value) && $this->act_Tipo->Value !== false)
                $this->act_Tipo->SetText(9999);
                if(!is_array($this->act_Grupo->Value) && !strlen($this->act_Grupo->Value) && $this->act_Grupo->Value !== false)
                $this->act_Grupo->SetText(9999);
                if(!is_array($this->act_SubGrupo->Value) && !strlen($this->act_SubGrupo->Value) && $this->act_SubGrupo->Value !== false)
                $this->act_SubGrupo->SetText(9999);
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @99-DD45F12A
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->ds->Parameters["urlact_CodAuxiliar"] = CCGetFromGet("act_CodAuxiliar", "");
    }
//End Initialize Method

//Validate Method @99-46C8822F
    function Validate()
    {
        $Validation = true;
        $Where = "";
        if($this->EditMode && strlen($this->ds->Where))
            $Where = " AND NOT (" . $this->ds->Where . ")";
        global $DBdatos;
        $this->ds->act_CodAuxiliar->SetValue($this->act_CodAuxiliar->GetValue());
        if(CCDLookUp("COUNT(*)", "conactivos", "act_CodAuxiliar=" . $this->ds->ToSQL($this->ds->act_CodAuxiliar->GetDBValue(), $this->ds->act_CodAuxiliar->DataType) . $Where, $DBdatos) > 0)
            $this->act_CodAuxiliar->Errors->addError("El campo Codigo de  Auxiliar ya existe.");
        $Validation = ($this->lbCategAux->Validate() && $Validation);
        $Validation = ($this->act_CodAuxiliar->Validate() && $Validation);
        $Validation = ($this->act_Descripcion->Validate() && $Validation);
        $Validation = ($this->act_Descripcion1->Validate() && $Validation);
        $Validation = ($this->act_SubCategoria->Validate() && $Validation);
        $Validation = ($this->act_UniMedida->Validate() && $Validation);
        $Validation = ($this->act_Marca->Validate() && $Validation);
        $Validation = ($this->act_Modelo->Validate() && $Validation);
        $Validation = ($this->act_NumSerie->Validate() && $Validation);
        $Validation = ($this->act_Tipo->Validate() && $Validation);
        $Validation = ($this->act_Grupo->Validate() && $Validation);
        $Validation = ($this->act_SubGrupo->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @99-6C4A1FCC
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->lbTitulo->Errors->Count());
        $errors = ($errors || $this->lbCategAux->Errors->Count());
        $errors = ($errors || $this->act_CodAuxiliar->Errors->Count());
        $errors = ($errors || $this->act_Descripcion->Errors->Count());
        $errors = ($errors || $this->act_Descripcion1->Errors->Count());
        $errors = ($errors || $this->act_SubCategoria->Errors->Count());
        $errors = ($errors || $this->act_UniMedida->Errors->Count());
        $errors = ($errors || $this->act_Marca->Errors->Count());
        $errors = ($errors || $this->act_Modelo->Errors->Count());
        $errors = ($errors || $this->act_NumSerie->Errors->Count());
        $errors = ($errors || $this->act_Tipo->Errors->Count());
        $errors = ($errors || $this->act_Grupo->Errors->Count());
        $errors = ($errors || $this->act_SubGrupo->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @99-77349263
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        $this->ds->Prepare();
        $this->EditMode = $this->ds->AllParametersSet;
        if(!$this->FormSubmitted)
            return;

        if($this->FormSubmitted) {
            $this->PressedButton = $this->EditMode ? "Button_Update" : "Button_Insert";
            if(strlen(CCGetParam("Button_Insert", ""))) {
                $this->PressedButton = "Button_Insert";
            } else if(strlen(CCGetParam("Button_Update", ""))) {
                $this->PressedButton = "Button_Update";
            } else if(strlen(CCGetParam("Button_Delete", ""))) {
                $this->PressedButton = "Button_Delete";
            } else if(strlen(CCGetParam("btBusqueda", ""))) {
                $this->PressedButton = "btBusqueda";
            } else if(strlen(CCGetParam("btNuevo", ""))) {
                $this->PressedButton = "btNuevo";
            }
        }
        $Redirect = "CoAdAc_mant.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "Button_Delete") {
            if(!CCGetEvent($this->Button_Delete->CCSEvents, "OnClick") || !$this->DeleteRow()) {
                $Redirect = "";
            } else {
                $Redirect = "CoAdAc_mant.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm", "act_CodAuxiliar", "pOpCode"));
            }
        } else if($this->PressedButton == "btBusqueda") {
            if(!CCGetEvent($this->btBusqueda->CCSEvents, "OnClick")) {
                $Redirect = "";
            } else {
                $Redirect = "CoAdAc.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm", "pOpCode", "act_Codauxiliar"));
            }
        } else if($this->PressedButton == "btNuevo") {
            if(!CCGetEvent($this->btNuevo->CCSEvents, "OnClick")) {
                $Redirect = "";
            } else {
                $Redirect = "CoAdAc_mant.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm", "act_CodAuxiliar", "pOpCode"));
            }
        } else if($this->Validate()) {
            if($this->PressedButton == "Button_Insert") {
                if(!CCGetEvent($this->Button_Insert->CCSEvents, "OnClick") || !$this->InsertRow()) {
                    $Redirect = "";
                }
            } else if($this->PressedButton == "Button_Update") {
                if(!CCGetEvent($this->Button_Update->CCSEvents, "OnClick") || !$this->UpdateRow()) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//InsertRow Method @99-A7733AA4
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert");
        if ($this->CCSEventResult) {
            if(!$this->InsertAllowed) return false;
            $this->ds->act_CodAuxiliar->SetValue($this->act_CodAuxiliar->GetValue());
            $this->ds->act_SubCategoria->SetValue($this->act_SubCategoria->GetValue());
            $this->ds->act_Descripcion->SetValue($this->act_Descripcion->GetValue());
            $this->ds->act_Descripcion1->SetValue($this->act_Descripcion1->GetValue());
            $this->ds->act_Marca->SetValue($this->act_Marca->GetValue());
            $this->ds->act_Modelo->SetValue($this->act_Modelo->GetValue());
            $this->ds->act_NumSerie->SetValue($this->act_NumSerie->GetValue());
            $this->ds->act_UniMedida->SetValue($this->act_UniMedida->GetValue());
            $this->ds->act_Tipo->SetValue($this->act_Tipo->GetValue());
            $this->ds->act_Grupo->SetValue($this->act_Grupo->GetValue());
            $this->ds->act_SubGrupo->SetValue($this->act_SubGrupo->GetValue());
            $this->ds->Insert();
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert");
            if($this->ds->Errors->Count() > 0) {
                echo "Error in Record " . $this->ComponentName . " / Insert Operation";
                $this->ds->Errors->Clear();
                $this->Errors->AddError("Database command error.");
            }
        }
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @99-8FA1EF81
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate");
        if ($this->CCSEventResult) {
            if(!$this->UpdateAllowed) return false;
            $this->ds->lbCategAux->SetValue($this->lbCategAux->GetValue());
            $this->ds->act_CodAuxiliar->SetValue($this->act_CodAuxiliar->GetValue());
            $this->ds->act_Descripcion->SetValue($this->act_Descripcion->GetValue());
            $this->ds->act_Descripcion1->SetValue($this->act_Descripcion1->GetValue());
            $this->ds->act_SubCategoria->SetValue($this->act_SubCategoria->GetValue());
            $this->ds->act_UniMedida->SetValue($this->act_UniMedida->GetValue());
            $this->ds->act_Marca->SetValue($this->act_Marca->GetValue());
            $this->ds->act_Modelo->SetValue($this->act_Modelo->GetValue());
            $this->ds->act_NumSerie->SetValue($this->act_NumSerie->GetValue());
            $this->ds->act_Tipo->SetValue($this->act_Tipo->GetValue());
            $this->ds->act_Grupo->SetValue($this->act_Grupo->GetValue());
            $this->ds->act_SubGrupo->SetValue($this->act_SubGrupo->GetValue());
            $this->ds->Update();
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate");
            if($this->ds->Errors->Count() > 0) {
                echo "Error in Record " . $this->ComponentName . " / Update Operation";
                $this->ds->Errors->Clear();
                $this->Errors->AddError("Database command error.");
            }
        }
        return (!$this->CheckErrors());
    }
//End UpdateRow Method

//DeleteRow Method @99-EA88835F
    function DeleteRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeDelete");
        if(!$this->DeleteAllowed) return false;
        $this->ds->Delete();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterDelete");
        if($this->ds->Errors->Count() > 0) {
            echo "Error in Record " . ComponentName . " / Delete Operation";
            $this->ds->Errors->Clear();
            $this->Errors->AddError("Database command error.");
        }
        return (!$this->CheckErrors());
    }
//End DeleteRow Method

//Show Method @99-EA4D928B
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->lbCategAux->Prepare();
        $this->act_SubCategoria->Prepare();
        $this->act_UniMedida->Prepare();
        $this->act_Marca->Prepare();
        $this->act_Tipo->Prepare();
        $this->act_Grupo->Prepare();
        $this->act_SubGrupo->Prepare();

        $this->ds->open();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        if($this->EditMode)
        {
            if($this->Errors->Count() == 0)
            {
                if($this->ds->Errors->Count() > 0)
                {
                    echo "Error in Record CoAdAc_mant";
                }
                else if($this->ds->next_record())
                {
                    $this->ds->SetValues();
                    if(!$this->FormSubmitted)
                    {
                        $this->act_CodAuxiliar->SetValue($this->ds->act_CodAuxiliar->GetValue());
                        $this->act_Descripcion->SetValue($this->ds->act_Descripcion->GetValue());
                        $this->act_Descripcion1->SetValue($this->ds->act_Descripcion1->GetValue());
                        $this->act_SubCategoria->SetValue($this->ds->act_SubCategoria->GetValue());
                        $this->act_UniMedida->SetValue($this->ds->act_UniMedida->GetValue());
                        $this->act_Marca->SetValue($this->ds->act_Marca->GetValue());
                        $this->act_Modelo->SetValue($this->ds->act_Modelo->GetValue());
                        $this->act_NumSerie->SetValue($this->ds->act_NumSerie->GetValue());
                        $this->act_Tipo->SetValue($this->ds->act_Tipo->GetValue());
                        $this->act_Grupo->SetValue($this->ds->act_Grupo->GetValue());
                        $this->act_SubGrupo->SetValue($this->ds->act_SubGrupo->GetValue());
                    }
                }
                else
                {
                    $this->EditMode = false;
                }
            }
        }
        if(!$this->FormSubmitted)
        {
            $this->lbTitulo->SetText("MODIFICACION DE AUXILIAR");
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->lbTitulo->Errors->ToString();
            $Error .= $this->lbCategAux->Errors->ToString();
            $Error .= $this->act_CodAuxiliar->Errors->ToString();
            $Error .= $this->act_Descripcion->Errors->ToString();
            $Error .= $this->act_Descripcion1->Errors->ToString();
            $Error .= $this->act_SubCategoria->Errors->ToString();
            $Error .= $this->act_UniMedida->Errors->ToString();
            $Error .= $this->act_Marca->Errors->ToString();
            $Error .= $this->act_Modelo->Errors->ToString();
            $Error .= $this->act_NumSerie->Errors->ToString();
            $Error .= $this->act_Tipo->Errors->ToString();
            $Error .= $this->act_Grupo->Errors->ToString();
            $Error .= $this->act_SubGrupo->Errors->ToString();
            $Error .= $this->Errors->ToString();
            $Error .= $this->ds->Errors->ToString();
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

        $this->Button_Insert->Visible = !$this->EditMode && $this->InsertAllowed;
        $this->Button_Update->Visible = $this->EditMode && $this->UpdateAllowed;
        $this->Button_Delete->Visible = $this->EditMode && $this->DeleteAllowed;
        $this->lbTitulo->Show();
        $this->lbCategAux->Show();
        $this->act_CodAuxiliar->Show();
        $this->act_Descripcion->Show();
        $this->act_Descripcion1->Show();
        $this->act_SubCategoria->Show();
        $this->act_UniMedida->Show();
        $this->act_Marca->Show();
        $this->act_Modelo->Show();
        $this->act_NumSerie->Show();
        $this->act_Tipo->Show();
        $this->act_Grupo->Show();
        $this->act_SubGrupo->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->btBusqueda->Show();
        $this->btNuevo->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

} //End CoAdAc_mant Class @99-FCB6E20C

class clsCoAdAc_mantDataSource extends clsDBdatos {  //CoAdAc_mantDataSource Class @99-CA73BC71

//DataSource Variables @99-49D26579
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $InsertParameters;
    var $UpdateParameters;
    var $DeleteParameters;
    var $wp;
    var $AllParametersSet;


    // Datasource fields
    var $lbTitulo;
    var $lbCategAux;
    var $act_CodAuxiliar;
    var $act_Descripcion;
    var $act_Descripcion1;
    var $act_SubCategoria;
    var $act_UniMedida;
    var $act_Marca;
    var $act_Modelo;
    var $act_NumSerie;
    var $act_Tipo;
    var $act_Grupo;
    var $act_SubGrupo;
//End DataSource Variables

//Class_Initialize Event @99-2449F4C3
    function clsCoAdAc_mantDataSource()
    {
        $this->ErrorBlock = "Record CoAdAc_mant/Error";
        $this->Initialize();
        $this->lbTitulo = new clsField("lbTitulo", ccsText, "");
        $this->lbCategAux = new clsField("lbCategAux", ccsText, "");
        $this->act_CodAuxiliar = new clsField("act_CodAuxiliar", ccsInteger, "");
        $this->act_Descripcion = new clsField("act_Descripcion", ccsText, "");
        $this->act_Descripcion1 = new clsField("act_Descripcion1", ccsText, "");
        $this->act_SubCategoria = new clsField("act_SubCategoria", ccsInteger, "");
        $this->act_UniMedida = new clsField("act_UniMedida", ccsInteger, "");
        $this->act_Marca = new clsField("act_Marca", ccsInteger, "");
        $this->act_Modelo = new clsField("act_Modelo", ccsText, "");
        $this->act_NumSerie = new clsField("act_NumSerie", ccsText, "");
        $this->act_Tipo = new clsField("act_Tipo", ccsInteger, "");
        $this->act_Grupo = new clsField("act_Grupo", ccsInteger, "");
        $this->act_SubGrupo = new clsField("act_SubGrupo", ccsInteger, "");

    }
//End Class_Initialize Event

//Prepare Method @99-03F2D12F
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlact_CodAuxiliar", ccsInteger, "", "", $this->Parameters["urlact_CodAuxiliar"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "act_CodAuxiliar", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @99-7E3A4157
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->SQL = "SELECT *  " .
        "FROM conactivos";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @99-A3D680BA
    function SetValues()
    {
        $this->act_CodAuxiliar->SetDBValue(trim($this->f("act_CodAuxiliar")));
        $this->act_Descripcion->SetDBValue($this->f("act_Descripcion"));
        $this->act_Descripcion1->SetDBValue($this->f("act_Descripcion1"));
        $this->act_SubCategoria->SetDBValue(trim($this->f("act_SubCategoria")));
        $this->act_UniMedida->SetDBValue(trim($this->f("act_UniMedida")));
        $this->act_Marca->SetDBValue(trim($this->f("act_Marca")));
        $this->act_Modelo->SetDBValue($this->f("act_Modelo"));
        $this->act_NumSerie->SetDBValue($this->f("act_NumSerie"));
        $this->act_Tipo->SetDBValue(trim($this->f("act_Tipo")));
        $this->act_Grupo->SetDBValue(trim($this->f("act_Grupo")));
        $this->act_SubGrupo->SetDBValue(trim($this->f("act_SubGrupo")));
    }
//End SetValues Method

//Insert Method @99-B90FDFE8
    function Insert()
    {
        $this->cp["act_CodAuxiliar"] = new clsSQLParameter("ctrlact_CodAuxiliar", ccsInteger, "", "", $this->act_CodAuxiliar->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["act_SubCategoria"] = new clsSQLParameter("ctrlact_SubCategoria", ccsInteger, "", "", $this->act_SubCategoria->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["act_Descripcion"] = new clsSQLParameter("ctrlact_Descripcion", ccsText, "", "", $this->act_Descripcion->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["act_Descripcion1"] = new clsSQLParameter("ctrlact_Descripcion1", ccsText, "", "", $this->act_Descripcion1->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["act_Marca"] = new clsSQLParameter("ctrlact_Marca", ccsInteger, "", "", $this->act_Marca->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["act_Modelo"] = new clsSQLParameter("ctrlact_Modelo", ccsText, "", "", $this->act_Modelo->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["act_NumSerie"] = new clsSQLParameter("ctrlact_NumSerie", ccsText, "", "", $this->act_NumSerie->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["act_UniMedida"] = new clsSQLParameter("ctrlact_UniMedida", ccsInteger, "", "", $this->act_UniMedida->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["act_Tipo"] = new clsSQLParameter("ctrlact_Tipo", ccsInteger, "", "", $this->act_Tipo->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["act_Grupo"] = new clsSQLParameter("ctrlact_Grupo", ccsInteger, "", "", $this->act_Grupo->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["act_SubGrupo"] = new clsSQLParameter("ctrlact_SubGrupo", ccsInteger, "", "", $this->act_SubGrupo->GetValue(), "", false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO conactivos ("
             . "act_CodAuxiliar, "
             . "act_SubCategoria, "
             . "act_Descripcion, "
             . "act_Descripcion1, "
             . "act_Marca, "
             . "act_Modelo, "
             . "act_NumSerie, "
             . "act_UniMedida, "
             . "act_Tipo, "
             . "act_Grupo, "
             . "act_SubGrupo"
             . ") VALUES ("
             . $this->ToSQL($this->cp["act_CodAuxiliar"]->GetDBValue(), $this->cp["act_CodAuxiliar"]->DataType) . ", "
             . $this->ToSQL($this->cp["act_SubCategoria"]->GetDBValue(), $this->cp["act_SubCategoria"]->DataType) . ", "
             . $this->ToSQL($this->cp["act_Descripcion"]->GetDBValue(), $this->cp["act_Descripcion"]->DataType) . ", "
             . $this->ToSQL($this->cp["act_Descripcion1"]->GetDBValue(), $this->cp["act_Descripcion1"]->DataType) . ", "
             . $this->ToSQL($this->cp["act_Marca"]->GetDBValue(), $this->cp["act_Marca"]->DataType) . ", "
             . $this->ToSQL($this->cp["act_Modelo"]->GetDBValue(), $this->cp["act_Modelo"]->DataType) . ", "
             . $this->ToSQL($this->cp["act_NumSerie"]->GetDBValue(), $this->cp["act_NumSerie"]->DataType) . ", "
             . $this->ToSQL($this->cp["act_UniMedida"]->GetDBValue(), $this->cp["act_UniMedida"]->DataType) . ", "
             . $this->ToSQL($this->cp["act_Tipo"]->GetDBValue(), $this->cp["act_Tipo"]->DataType) . ", "
             . $this->ToSQL($this->cp["act_Grupo"]->GetDBValue(), $this->cp["act_Grupo"]->DataType) . ", "
             . $this->ToSQL($this->cp["act_SubGrupo"]->GetDBValue(), $this->cp["act_SubGrupo"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        if ($this->CCSEventResult) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        }
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Insert Method

//Update Method @99-E2745073
    function Update()
    {
        $desc1=$this->act_Descripcion1->GetDBValue();
        if (empty($desc1)) $desc1=" ";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $this->SQL = "UPDATE conactivos SET "
             . "act_CodAuxiliar=" . $this->ToSQL($this->act_CodAuxiliar->GetDBValue(), $this->act_CodAuxiliar->DataType) . ", "
             . "act_Descripcion=" . $this->ToSQL($this->act_Descripcion->GetDBValue(), $this->act_Descripcion->DataType) . ", "
             . "act_Descripcion1=" . $this->ToSQL($desc1, $this->act_Descripcion1->DataType) . ", "
             . "act_SubCategoria=" . $this->ToSQL($this->act_SubCategoria->GetDBValue(), $this->act_SubCategoria->DataType) . ", "
             . "act_UniMedida=" . $this->ToSQL($this->act_UniMedida->GetDBValue(), $this->act_UniMedida->DataType) . ", "
             . "act_Marca=" . $this->ToSQL($this->act_Marca->GetDBValue(), $this->act_Marca->DataType) . ", "
             . "act_Modelo=" . $this->ToSQL($this->act_Modelo->GetDBValue(), $this->act_Modelo->DataType) . ", "
             . "act_NumSerie=" . $this->ToSQL($this->act_NumSerie->GetDBValue(), $this->act_NumSerie->DataType) . ", "
             . "act_Tipo=" . $this->ToSQL($this->act_Tipo->GetDBValue(), $this->act_Tipo->DataType) . ", "
             . "act_Grupo=" . $this->ToSQL($this->act_Grupo->GetDBValue(), $this->act_Grupo->DataType) . ", "
             . "act_SubGrupo=" . $this->ToSQL($this->act_SubGrupo->GetDBValue(), $this->act_SubGrupo->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Update Method

//Delete Method @99-E215F9DC
    function Delete()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $this->SQL = "DELETE FROM conactivos";
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Delete Method

} //End CoAdAc_mantDataSource Class @99-FCB6E20C

Class clsEditableGridCoAdAc_cate { //CoAdAc_cate Class @235-3AFE7B3B

//Variables @235-35C6B523

    // Public variables
    var $ComponentName;
    var $HTMLFormAction;
    var $PressedButton;
    var $Errors;
    var $ErrorBlock;
    var $FormSubmitted;
    var $FormParameters;
    var $FormState;
    var $FormEnctype;
    var $CachedColumns;
    var $TotalRows;
    var $UpdatedRows;
    var $EmptyRows;
    var $Visible;
    var $EditableGridset;
    var $RowsErrors;
    var $ds; var $PageSize;
    var $SorterName = "";
    var $SorterDirection = "";
    var $PageNumber;

    var $CCSEvents = "";
    var $CCSEventResult;

    var $InsertAllowed = false;
    var $UpdateAllowed = false;
    var $DeleteAllowed = false;
    var $EditMode;
    var $ValidatingControls;
    var $Controls;
    var $ControlsErrors;

    // Class variables
    var $Sorter_cat_Categoria;
    var $Sorter_cat_FecIncorp;
    var $Sorter_cat_Activo;
    var $Navigator;
//End Variables

//Class_Initialize Event @235-B138C658
    function clsEditableGridCoAdAc_cate()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "EditableGrid CoAdAc_cate/Error";
        $this->ComponentName = "CoAdAc_cate";
        $this->CachedColumns["cat_CodAuxiliar"][0] = "cat_CodAuxiliar";
        $this->CachedColumns["act_CodAuxiliar"][0] = "act_CodAuxiliar";
        $this->CachedColumns["cat_Categoria"][0] = "cat_Categoria";
        $this->ds = new clsCoAdAc_cateDataSource();

        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize) || $this->PageSize > 15)
            $this->PageSize = 15;
        else
            $this->PageSize = intval($this->PageSize);
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: EditableGrid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));

        $this->EmptyRows = 1;
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        if(!$this->Visible) return;

        $CCSForm = CCGetFromGet("ccsForm", "");
        $this->FormEnctype = "application/x-www-form-urlencoded";
        $this->FormSubmitted = ($CCSForm == $this->ComponentName);
        if($this->FormSubmitted) {
            $this->FormState = CCGetFromPost("FormState", "");
            $this->SetFormState($this->FormState);
        } else {
            $this->FormState = "";
        }
        $Method = $this->FormSubmitted ? ccsPost : ccsGet;

        $this->SorterName = CCGetParam("CoAdAc_cateOrder", "");
        $this->SorterDirection = CCGetParam("CoAdAc_cateDir", "");

        $this->Sorter_cat_Categoria = new clsSorter($this->ComponentName, "Sorter_cat_Categoria", $FileName);
        $this->Sorter_cat_FecIncorp = new clsSorter($this->ComponentName, "Sorter_cat_FecIncorp", $FileName);
        $this->Sorter_cat_Activo = new clsSorter($this->ComponentName, "Sorter_cat_Activo", $FileName);
        $this->cat_CodAuxiliar = new clsControl(ccsHidden, "cat_CodAuxiliar", "Cat Cod Auxiliar", ccsInteger, "");
        $this->cat_Categoria = new clsControl(ccsListBox, "cat_Categoria", "Cat Categoria", ccsInteger, "");
        $this->cat_Categoria->DSType = dsTable;
        list($this->cat_Categoria->BoundColumn, $this->cat_Categoria->TextColumn, $this->cat_Categoria->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
        $this->cat_Categoria->ds = new clsDBdatos();
        $this->cat_Categoria->ds->SQL = "SELECT *  " .
"FROM genparametros";
        $this->cat_Categoria->ds->Parameters["expr252"] = 'CAUTI';
        $this->cat_Categoria->ds->Parameters["expr253"] = 'Activo';
        $this->cat_Categoria->ds->wp = new clsSQLParameters();
        $this->cat_Categoria->ds->wp->AddParameter("1", "expr252", ccsText, "", "", $this->cat_Categoria->ds->Parameters["expr252"], "", false);
        $this->cat_Categoria->ds->wp->AddParameter("2", "expr253", ccsText, "", "", $this->cat_Categoria->ds->Parameters["expr253"], "", true);
        $this->cat_Categoria->ds->wp->Criterion[1] = $this->cat_Categoria->ds->wp->Operation(opEqual, "par_Clave", $this->cat_Categoria->ds->wp->GetDBValue("1"), $this->cat_Categoria->ds->ToSQL($this->cat_Categoria->ds->wp->GetDBValue("1"), ccsText),false);
        $this->cat_Categoria->ds->wp->Criterion[2] = $this->cat_Categoria->ds->wp->Operation(opEqual, "par_Valor1", $this->cat_Categoria->ds->wp->GetDBValue("2"), $this->cat_Categoria->ds->ToSQL($this->cat_Categoria->ds->wp->GetDBValue("2"), ccsText),true);
        $this->cat_Categoria->ds->Where = $this->cat_Categoria->ds->wp->opAND(false, $this->cat_Categoria->ds->wp->Criterion[1], $this->cat_Categoria->ds->wp->Criterion[2]);
        $this->cat_FecIncorp = new clsControl(ccsTextBox, "cat_FecIncorp", "Cat Fec Incorp", ccsDate, Array("dd", "/", "mmm", "/", "yy"));
        $this->DatePicker_cat_FecIncorp = new clsDatePicker("DatePicker_cat_FecIncorp", "CoAdAc_cate", "cat_FecIncorp");
        $this->cat_Activo = new clsControl(ccsListBox, "cat_Activo", "Cat Activo", ccsInteger, "");
        $this->cat_Activo->DSType = dsListOfValues;
        $this->cat_Activo->Values = array(array("0", "Inactivo"), array("1", "Activo"));
        $this->CheckBox_Delete = new clsControl(ccsCheckBox, "CheckBox_Delete", "CheckBox_Delete", ccsBoolean, Array("Y", "N", ""));
        $this->CheckBox_Delete->CheckedValue = $this->CheckBox_Delete->GetParsedValue(true);
        $this->CheckBox_Delete->UncheckedValue = $this->CheckBox_Delete->GetParsedValue(false);
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple);
        $this->Button_Submit = new clsButton("Button_Submit");
        $this->Cancel = new clsButton("Cancel");
    }
//End Class_Initialize Event

//Initialize Method @235-D56D31B8
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);

        $this->ds->Parameters["urlact_CodAuxiliar"] = CCGetFromGet("act_CodAuxiliar", "");
    }
//End Initialize Method

//GetFormParameters Method @235-B802970B
    function GetFormParameters()
    {
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->FormParameters["cat_CodAuxiliar"][$RowNumber] = CCGetFromPost("cat_CodAuxiliar_" . $RowNumber);
            $this->FormParameters["cat_Categoria"][$RowNumber] = CCGetFromPost("cat_Categoria_" . $RowNumber);
            $this->FormParameters["cat_FecIncorp"][$RowNumber] = CCGetFromPost("cat_FecIncorp_" . $RowNumber);
            $this->FormParameters["cat_Activo"][$RowNumber] = CCGetFromPost("cat_Activo_" . $RowNumber);
            $this->FormParameters["CheckBox_Delete"][$RowNumber] = CCGetFromPost("CheckBox_Delete_" . $RowNumber);
        }
    }
//End GetFormParameters Method

//Validate Method @235-E7584164
    function Validate()
    {
        $Validation = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");

        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["cat_CodAuxiliar"] = $this->CachedColumns["cat_CodAuxiliar"][$RowNumber];
            $this->ds->CachedColumns["act_CodAuxiliar"] = $this->CachedColumns["act_CodAuxiliar"][$RowNumber];
            $this->ds->CachedColumns["cat_Categoria"] = $this->CachedColumns["cat_Categoria"][$RowNumber];
            $this->cat_CodAuxiliar->SetText($this->FormParameters["cat_CodAuxiliar"][$RowNumber], $RowNumber);
            $this->cat_Categoria->SetText($this->FormParameters["cat_Categoria"][$RowNumber], $RowNumber);
            $this->cat_FecIncorp->SetText($this->FormParameters["cat_FecIncorp"][$RowNumber], $RowNumber);
            $this->cat_Activo->SetText($this->FormParameters["cat_Activo"][$RowNumber], $RowNumber);
            $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$RowNumber], $RowNumber);
            if ($this->UpdatedRows >= $RowNumber) {
                if(!$this->CheckBox_Delete->Value)
                    $Validation = ($this->ValidateRow($RowNumber) && $Validation);
            }
            else if($this->CheckInsert($RowNumber))
            {
                $Validation = ($this->ValidateRow($RowNumber) && $Validation);
            }
        }
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//ValidateRow Method @235-50BA8EA2
    function ValidateRow($RowNumber)
    {
        $Validation = true;
        $Validation = ($this->cat_CodAuxiliar->Validate() && $Validation);
        $Validation = ($this->cat_Categoria->Validate() && $Validation);
        $Validation = ($this->cat_FecIncorp->Validate() && $Validation);
        $Validation = ($this->cat_Activo->Validate() && $Validation);
        $Validation = ($this->CheckBox_Delete->Validate() && $Validation);
        $errors = "";
        if(!$Validation)
        {
            $errors .= $this->cat_CodAuxiliar->Errors->ToString();
            $errors .= $this->cat_Categoria->Errors->ToString();
            $errors .= $this->cat_FecIncorp->Errors->ToString();
            $errors .= $this->cat_Activo->Errors->ToString();
            $errors .= $this->CheckBox_Delete->Errors->ToString();
            $this->cat_CodAuxiliar->Errors->Clear();
            $this->cat_Categoria->Errors->Clear();
            $this->cat_FecIncorp->Errors->Clear();
            $this->cat_Activo->Errors->Clear();
            $this->CheckBox_Delete->Errors->Clear();
        }
        $this->RowsErrors[$RowNumber] = $errors;
        return $Validation;
    }
//End ValidateRow Method

//CheckInsert Method @235-F83B46DA
    function CheckInsert($RowNumber)
    {
        $filed = false;
        $filed = ($filed || strlen($this->FormParameters["cat_CodAuxiliar"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cat_Categoria"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cat_FecIncorp"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cat_Activo"][$RowNumber]));
        return $filed;
    }
//End CheckInsert Method

//CheckErrors Method @235-242E5992
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @235-7B861278
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        $this->ds->Prepare();
        if(!$this->FormSubmitted)
            return;

        $this->GetFormParameters();
        $this->PressedButton = "Button_Submit";
        if(strlen(CCGetParam("Button_Submit", ""))) {
            $this->PressedButton = "Button_Submit";
        } else if(strlen(CCGetParam("Cancel", ""))) {
            $this->PressedButton = "Cancel";
        }

        $Redirect = $FileName . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "Button_Submit") {
            if(!CCGetEvent($this->Button_Submit->CCSEvents, "OnClick") || !$this->UpdateGrid()) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "Cancel") {
            if(!CCGetEvent($this->Cancel->CCSEvents, "OnClick")) {
                $Redirect = "";
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//UpdateGrid Method @235-52AF9CA1
    function UpdateGrid()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSubmit");
        if(!$this->Validate()) return;
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["cat_CodAuxiliar"] = $this->CachedColumns["cat_CodAuxiliar"][$RowNumber];
            $this->ds->CachedColumns["act_CodAuxiliar"] = $this->CachedColumns["act_CodAuxiliar"][$RowNumber];
            $this->ds->CachedColumns["cat_Categoria"] = $this->CachedColumns["cat_Categoria"][$RowNumber];
            $this->cat_CodAuxiliar->SetText($this->FormParameters["cat_CodAuxiliar"][$RowNumber], $RowNumber);
            $this->cat_Categoria->SetText($this->FormParameters["cat_Categoria"][$RowNumber], $RowNumber);
            $this->cat_FecIncorp->SetText($this->FormParameters["cat_FecIncorp"][$RowNumber], $RowNumber);
            $this->cat_Activo->SetText($this->FormParameters["cat_Activo"][$RowNumber], $RowNumber);
            $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$RowNumber], $RowNumber);
            if ($this->UpdatedRows >= $RowNumber) {
                if($this->CheckBox_Delete->Value) {
                    if($this->DeleteAllowed) $this->DeleteRow($RowNumber);
                } else if($this->UpdateAllowed) {
                    $this->UpdateRow($RowNumber);
                }
            }
            else if($this->CheckInsert($RowNumber) && $this->InsertAllowed)
            {
                $this->InsertRow($RowNumber);
            }
        }
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterSubmit");
        return ($this->Errors->Count() == 0);
    }
//End UpdateGrid Method

//InsertRow Method @235-87AA598F
    function InsertRow($RowNumber)
    {
        if(!$this->InsertAllowed) return false;
        $this->ds->cat_Categoria->SetValue($this->cat_Categoria->GetValue());
        $this->ds->cat_FecIncorp->SetValue($this->cat_FecIncorp->GetValue());
        $this->ds->cat_Activo->SetValue($this->cat_Activo->GetValue());
        $this->ds->Insert();
        $errors = "";
        if($this->ds->Errors->Count() > 0) {
            echo "Error in EditableGrid " . $this->ComponentName . " / Insert Operation";
            $this->ds->Errors->Clear();
            $this->Errors->AddError("Database command error.");
        }
        return (($this->Errors->Count() == 0) && !strlen($errors));
    }
//End InsertRow Method

//UpdateRow Method @235-16F054FE
    function UpdateRow($RowNumber)
    {
        if(!$this->UpdateAllowed) return false;
        $this->ds->cat_FecIncorp->SetValue($this->cat_FecIncorp->GetValue());
        $this->ds->cat_Activo->SetValue($this->cat_Activo->GetValue());
        $this->ds->Update();
        $errors = "";
        if($this->ds->Errors->Count() > 0) {
            echo "Error in EditableGrid " . $this->ComponentName . " / Update Operation";
            $this->ds->Errors->Clear();
            $this->Errors->AddError("Database command error.");
        }
        return (($this->Errors->Count() == 0) && !strlen($errors));
    }
//End UpdateRow Method

//DeleteRow Method @235-0C9DDC34
    function DeleteRow($RowNumber)
    {
        if(!$this->DeleteAllowed) return false;
        $this->ds->Delete();
        $errors = "";
        if($this->ds->Errors->Count() > 0) {
            echo "Error in EditableGrid " . ComponentName . " / Delete Operation";
            $this->ds->Errors->Clear();
            $this->Errors->AddError("Database command error.");
        }
        return (($this->Errors->Count() == 0) && !strlen($errors));
    }
//End DeleteRow Method

//FormScript Method @235-59800DB5
    function FormScript($TotalRows)
    {
        $script = "";
        return $script;
    }
//End FormScript Method

//SetFormState Method @235-45809DDD
    function SetFormState($FormState)
    {
        if(strlen($FormState)) {
            $FormState = str_replace("\\\\", "\\" . ord("\\"), $FormState);
            $FormState = str_replace("\\;", "\\" . ord(";"), $FormState);
            $pieces = explode(";", $FormState);
            $this->UpdatedRows = $pieces[0];
            $this->EmptyRows   = $pieces[1];
            $this->TotalRows = $this->UpdatedRows + $this->EmptyRows;
            $RowNumber = 0;
            for($i = 2; $i < sizeof($pieces); $i = $i + 3)  {
                $piece = $pieces[$i + 0];
                $piece = str_replace("\\" . ord("\\"), "\\", $piece);
                $piece = str_replace("\\" . ord(";"), ";", $piece);
                $this->CachedColumns["cat_CodAuxiliar"][$RowNumber] = $piece;
                $piece = $pieces[$i + 1];
                $piece = str_replace("\\" . ord("\\"), "\\", $piece);
                $piece = str_replace("\\" . ord(";"), ";", $piece);
                $this->CachedColumns["act_CodAuxiliar"][$RowNumber] = $piece;
                $piece = $pieces[$i + 2];
                $piece = str_replace("\\" . ord("\\"), "\\", $piece);
                $piece = str_replace("\\" . ord(";"), ";", $piece);
                $this->CachedColumns["cat_Categoria"][$RowNumber] = $piece;
                $RowNumber++;
            }

            if(!$RowNumber) { $RowNumber = 1; }
            for($i = 1; $i <= $this->EmptyRows; $i++) {
                $this->CachedColumns["cat_CodAuxiliar"][$RowNumber] = "";
                $this->CachedColumns["act_CodAuxiliar"][$RowNumber] = "";
                $this->CachedColumns["cat_Categoria"][$RowNumber] = "";
                $RowNumber++;
            }
        }
    }
//End SetFormState Method

//GetFormState Method @235-04283B2C
    function GetFormState($NonEmptyRows)
    {
        if(!$this->FormSubmitted) {
            $this->FormState  = $NonEmptyRows . ";";
            $this->FormState .= $this->InsertAllowed ? $this->EmptyRows : "0";
            if($NonEmptyRows) {
                for($i = 0; $i <= $NonEmptyRows; $i++) {
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["cat_CodAuxiliar"][$i]));
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["act_CodAuxiliar"][$i]));
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["cat_Categoria"][$i]));
                }
            }
        }
        return $this->FormState;
    }
//End GetFormState Method

//Show Method @235-0D261BB2
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible) { return; }

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->cat_Categoria->Prepare();
        $this->cat_Activo->Prepare();

        $this->ds->open();

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
        if(!$this->Visible) { return; }

        $this->Button_Submit->Visible = ($this->InsertAllowed || $this->UpdateAllowed || $this->DeleteAllowed);
        $ParentPath = $Tpl->block_path;
        $EditableGridPath = $ParentPath . "/EditableGrid " . $this->ComponentName;
        $EditableGridRowPath = $ParentPath . "/EditableGrid " . $this->ComponentName . "/Row";
        $Tpl->block_path = $EditableGridRowPath;
        $RowNumber = 0;
        $NonEmptyRows = 0;
        $EmptyRowsLeft = $this->EmptyRows;
        $is_next_record = false;
        if($this->Errors->Count() == 0)
        {
            $is_next_record = ($this->ds->next_record() && $RowNumber < $this->PageSize);
            if($is_next_record || ($EmptyRowsLeft && $this->InsertAllowed))
            {
                do
                {
                    $RowNumber++;
                    if($is_next_record) {
                        $NonEmptyRows++;
                        $this->ds->SetValues();
                    } else {
                    }
                    if(!$is_next_record || !$this->DeleteAllowed)
                        $this->CheckBox_Delete->Visible = false;
                    if(!$this->FormSubmitted && $is_next_record) {
                        $this->CachedColumns["cat_CodAuxiliar"][$RowNumber] = $this->ds->CachedColumns["cat_CodAuxiliar"];
                        $this->CachedColumns["act_CodAuxiliar"][$RowNumber] = $this->ds->CachedColumns["act_CodAuxiliar"];
                        $this->CachedColumns["cat_Categoria"][$RowNumber] = $this->ds->CachedColumns["cat_Categoria"];
                        $this->cat_CodAuxiliar->SetValue($this->ds->cat_CodAuxiliar->GetValue());
                        $this->cat_Categoria->SetValue($this->ds->cat_Categoria->GetValue());
                        $this->cat_FecIncorp->SetValue($this->ds->cat_FecIncorp->GetValue());
                        $this->cat_Activo->SetValue($this->ds->cat_Activo->GetValue());
                        $this->ValidateRow($RowNumber);
                    } else if (!$this->FormSubmitted){
                        $this->CachedColumns["cat_CodAuxiliar"][$RowNumber] = "";
                        $this->CachedColumns["act_CodAuxiliar"][$RowNumber] = "";
                        $this->CachedColumns["cat_Categoria"][$RowNumber] = "";
                        $this->cat_CodAuxiliar->SetText("");
                        $this->cat_Categoria->SetText("");
                        $this->cat_FecIncorp->SetText("");
                        $this->cat_Activo->SetText("");
                        $this->CheckBox_Delete->SetText("");
                    } else {
                        $this->cat_CodAuxiliar->SetText($this->FormParameters["cat_CodAuxiliar"][$RowNumber], $RowNumber);
                        $this->cat_Categoria->SetText($this->FormParameters["cat_Categoria"][$RowNumber], $RowNumber);
                        $this->cat_FecIncorp->SetText($this->FormParameters["cat_FecIncorp"][$RowNumber], $RowNumber);
                        $this->cat_Activo->SetText($this->FormParameters["cat_Activo"][$RowNumber], $RowNumber);
                        $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$RowNumber], $RowNumber);
                    }
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->cat_CodAuxiliar->Show($RowNumber);
                    $this->cat_Categoria->Show($RowNumber);
                    $this->cat_FecIncorp->Show($RowNumber);
                    $this->DatePicker_cat_FecIncorp->Show($RowNumber);
                    $this->cat_Activo->Show($RowNumber);
                    $this->CheckBox_Delete->Show($RowNumber);
                    if(isset($this->RowsErrors[$RowNumber]) && $this->RowsErrors[$RowNumber] !== "") {
                        $Tpl->setvar("Error", $this->RowsErrors[$RowNumber]);
                        $Tpl->parse("RowError", false);
                    } else {
                        $Tpl->setblockvar("RowError", "");
                    }
                    $Tpl->setvar("FormScript", $this->FormScript($RowNumber));
                    $Tpl->parse();
                    if($is_next_record) $is_next_record = ($this->ds->next_record() && $RowNumber < $this->PageSize);
                    else $EmptyRowsLeft--;
                } while($is_next_record || ($EmptyRowsLeft && $this->InsertAllowed));
            } else {
                $Tpl->block_path = $EditableGridPath;
                $Tpl->parse("NoRecords", false);
            }
        }

        $Tpl->block_path = $EditableGridPath;
        $this->Navigator->PageNumber = $this->ds->AbsolutePage;
        $this->Navigator->TotalPages = $this->ds->PageCount();
        $this->Sorter_cat_Categoria->Show();
        $this->Sorter_cat_FecIncorp->Show();
        $this->Sorter_cat_Activo->Show();
        $this->Navigator->Show();
        $this->Button_Submit->Show();
        $this->Cancel->Show();

        if($this->CheckErrors()) {
            $Error .= $this->Errors->ToString();
            $Error .= $this->ds->Errors->ToString();
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $CCSForm = $this->ComponentName;
        $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        $Tpl->SetVar("Action", $this->HTMLFormAction);
        $Tpl->SetVar("HTMLFormName", $this->ComponentName);
        $Tpl->SetVar("HTMLFormEnctype", $this->FormEnctype);
        $Tpl->SetVar("HTMLFormProperties", "method=\"POST\" action=\"" . $this->HTMLFormAction . "\" name=\"" . $this->ComponentName . "\"");
        $Tpl->SetVar("FormState", htmlspecialchars($this->GetFormState($NonEmptyRows)));
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

} //End CoAdAc_cate Class @235-FCB6E20C

class clsCoAdAc_cateDataSource extends clsDBdatos {  //CoAdAc_cateDataSource Class @235-B5BC2F28

//DataSource Variables @235-E72AC76A
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $InsertParameters;
    var $UpdateParameters;
    var $DeleteParameters;
    var $CountSQL;
    var $wp;
    var $AllParametersSet;

    var $CachedColumns;

    // Datasource fields
    var $cat_CodAuxiliar;
    var $cat_Categoria;
    var $cat_FecIncorp;
    var $cat_Activo;
    var $CheckBox_Delete;
//End DataSource Variables

//Class_Initialize Event @235-6D0EED55
    function clsCoAdAc_cateDataSource()
    {
        $this->ErrorBlock = "EditableGrid CoAdAc_cate/Error";
        $this->Initialize();
        $this->cat_CodAuxiliar = new clsField("cat_CodAuxiliar", ccsInteger, "");
        $this->cat_Categoria = new clsField("cat_Categoria", ccsInteger, "");
        $this->cat_FecIncorp = new clsField("cat_FecIncorp", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss", ".", "S"));
        $this->cat_Activo = new clsField("cat_Activo", ccsInteger, "");
        $this->CheckBox_Delete = new clsField("CheckBox_Delete", ccsBoolean, Array("true", "false", ""));

    }
//End Class_Initialize Event

//SetOrder Method @235-BDE14459
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_cat_Categoria" => array("cat_Categoria", ""), 
            "Sorter_cat_FecIncorp" => array("cat_FecIncorp", ""), 
            "Sorter_cat_Activo" => array("cat_Activo", "")));
    }
//End SetOrder Method

//Prepare Method @235-646253A6
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlact_CodAuxiliar", ccsInteger, "", "", $this->Parameters["urlact_CodAuxiliar"], -1, false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "cat_CodAuxiliar", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @235-BB740A65
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM concategorias";
        $this->SQL = "SELECT *  " .
        "FROM concategorias";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @235-F802FC82
    function SetValues()
    {
        $this->CachedColumns["cat_CodAuxiliar"] = $this->f("cat_CodAuxiliar");
        $this->CachedColumns["act_CodAuxiliar"] = $this->f("act_CodAuxiliar");
        $this->CachedColumns["cat_Categoria"] = $this->f("cat_Categoria");
        $this->cat_CodAuxiliar->SetDBValue(trim($this->f("cat_CodAuxiliar")));
        $this->cat_Categoria->SetDBValue(trim($this->f("cat_Categoria")));
        $this->cat_FecIncorp->SetDBValue(trim($this->f("cat_FecIncorp")));
        $this->cat_Activo->SetDBValue(trim($this->f("cat_Activo")));
    }
//End SetValues Method

//Insert Method @235-D2A0DBF1
    function Insert()
    {
        $this->cp["cat_CodAuxiliar"] = new clsSQLParameter("urlact_CodAuxiliar", ccsInteger, "", "", CCGetFromGet("act_CodAuxiliar", ""), "", false, $this->ErrorBlock);
        $this->cp["cat_Categoria"] = new clsSQLParameter("ctrlcat_Categoria", ccsInteger, "", "", $this->cat_Categoria->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["cat_FecIncorp"] = new clsSQLParameter("ctrlcat_FecIncorp", ccsDate, Array("dd", "/", "mmm", "/", "yyyy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss", ".", "S"), $this->cat_FecIncorp->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["cat_Activo"] = new clsSQLParameter("ctrlcat_Activo", ccsInteger, "", "", $this->cat_Activo->GetValue(), "", false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO concategorias ("
             . "cat_CodAuxiliar, "
             . "cat_Categoria, "
             . "cat_FecIncorp, "
             . "cat_Activo"
             . ") VALUES ("
             . $this->ToSQL($this->cp["cat_CodAuxiliar"]->GetDBValue(), $this->cp["cat_CodAuxiliar"]->DataType) . ", "
             . $this->ToSQL($this->cp["cat_Categoria"]->GetDBValue(), $this->cp["cat_Categoria"]->DataType) . ", "
             . $this->ToSQL($this->cp["cat_FecIncorp"]->GetDBValue(), $this->cp["cat_FecIncorp"]->DataType) . ", "
             . $this->ToSQL($this->cp["cat_Activo"]->GetDBValue(), $this->cp["cat_Activo"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Insert Method

//Update Method @235-4B8AA7CD
    function Update()
    {
        $this->cp["cat_FecIncorp"] = new clsSQLParameter("ctrlcat_FecIncorp", ccsDate, Array("dd", "/", "mmm", "/", "yyyy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss", ".", "S"), $this->cat_FecIncorp->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["cat_Activo"] = new clsSQLParameter("ctrlcat_Activo", ccsInteger, "", "", $this->cat_Activo->GetValue(), "", false, $this->ErrorBlock);
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "dscat_Categoria", ccsInteger, "", "", $this->CachedColumns["cat_Categoria"], "", false);
        $wp->AddParameter("2", "dsact_CodAuxiliar", ccsInteger, "", "", $this->CachedColumns["act_CodAuxiliar"], "", false);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $wp->Criterion[1] = $wp->Operation(opEqual, "cat_Categoria", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),false);
        $wp->Criterion[2] = $wp->Operation(opEqual, "cat_CodAuxiliar", $wp->GetDBValue("2"), $this->ToSQL($wp->GetDBValue("2"), ccsInteger),false);
        $Where = $wp->opAND(false, $wp->Criterion[1], $wp->Criterion[2]);
        $this->SQL = "UPDATE concategorias SET "
             . "cat_FecIncorp=" . $this->ToSQL($this->cp["cat_FecIncorp"]->GetDBValue(), $this->cp["cat_FecIncorp"]->DataType) . ", "
             . "cat_Activo=" . $this->ToSQL($this->cp["cat_Activo"]->GetDBValue(), $this->cp["cat_Activo"]->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Update Method

//Delete Method @235-18B8B3E9
    function Delete()
    {
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "dscat_Categoria", ccsInteger, "", "", $this->CachedColumns["cat_Categoria"], "", false);
        $wp->AddParameter("2", "urlact_CodAuxiliar", ccsInteger, "", "", CCGetFromGet("act_CodAuxiliar", ""), "", false);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $wp->Criterion[1] = $wp->Operation(opEqual, "cat_Categoria", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),false);
        $wp->Criterion[2] = $wp->Operation(opEqual, "cat_CodAuxiliar", $wp->GetDBValue("2"), $this->ToSQL($wp->GetDBValue("2"), ccsInteger),false);
        $Where = $wp->opAND(false, $wp->Criterion[1], $wp->Criterion[2]);
        $this->SQL = "DELETE FROM concategorias";
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Delete Method

} //End CoAdAc_cateDataSource Class @235-FCB6E20C





//Initialize Page @1-97AD6B78
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

$FileName = "CoAdAc_mant.php";
$Redirect = "";
$TemplateFileName = "CoAdAc_mant.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-E47A837C
$DBdatos = new clsDBdatos();
$DBexportadora = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
$CoAdAc_mant = new clsRecordCoAdAc_mant();
$CoAdAc_cate = new clsEditableGridCoAdAc_cate();
$CoAdAc_mant->Initialize();
$CoAdAc_cate->Initialize();

// Events
include("./CoAdAc_mant_events.php");
BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-0E919DC0
$Cabecera->Operations();
$CoAdAc_mant->Operation();
$CoAdAc_cate->Operation();
//End Execute Components

//Go to destination page @1-0BC0AE30
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    $DBdatos->close();
    $DBdatos->close();
    header("Location: " . $Redirect);
    exit;
}
//End Go to destination page

//Show Page @1-EF4A11DF
$Cabecera->Show("Cabecera");
$CoAdAc_mant->Show();
$CoAdAc_cate->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
$generated_with = "<center><font face=\"Arial\"><small>Generated with CodeCharge Studio</small></font></center>";
$main_block = preg_match("/<\/body>/i", $main_block) ? preg_replace("/<\/body>/i", $generated_with . "</body>", $main_block) : $main_block . $generated_with;
echo $main_block;
//End Show Page

//Unload Page @1-C91A1E27
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBdatos->close();
$DBdatos->close();
unset($Tpl);
//End Unload Page


?>
