<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @75-49BEB8FC
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation

class clsRecordliqrubros { //liqrubros Class @2-50E8870C

//Variables @2-4A82E0A3

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

//Class_Initialize Event @2-018922DD
    function clsRecordliqrubros()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record liqrubros/Error";
        $this->ds = new clsliqrubrosDataSource();
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "liqrubros";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->lbTitulo = new clsControl(ccsLabel, "lbTitulo", "lbTitulo", ccsText, "", CCGetRequestParam("lbTitulo", $Method));
            $this->rub_Abreviatura = new clsControl(ccsTextBox, "rub_Abreviatura", "Abreviatura", ccsText, "", CCGetRequestParam("rub_Abreviatura", $Method));
            $this->rub_Abreviatura->Required = true;
            $this->rub_TipoProceso = new clsControl(ccsListBox, "rub_TipoProceso", "Tipo Proceso", ccsInteger, "", CCGetRequestParam("rub_TipoProceso", $Method));
            $this->rub_TipoProceso->DSType = dsTable;
            list($this->rub_TipoProceso->BoundColumn, $this->rub_TipoProceso->TextColumn, $this->rub_TipoProceso->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->rub_TipoProceso->ds = new clsDBdatos();
            $this->rub_TipoProceso->ds->SQL = "SELECT *  " .
"FROM genparametros INNER JOIN gencatparam ON " .
"genparametros.par_Categoria = gencatparam.cat_Codigo";
            $this->rub_TipoProceso->ds->Parameters["expr41"] = 'LGPROC';
            $this->rub_TipoProceso->ds->wp = new clsSQLParameters();
            $this->rub_TipoProceso->ds->wp->AddParameter("1", "expr41", ccsText, "", "", $this->rub_TipoProceso->ds->Parameters["expr41"], "", false);
            $this->rub_TipoProceso->ds->wp->Criterion[1] = $this->rub_TipoProceso->ds->wp->Operation(opEqual, "cat_Clave", $this->rub_TipoProceso->ds->wp->GetDBValue("1"), $this->rub_TipoProceso->ds->ToSQL($this->rub_TipoProceso->ds->wp->GetDBValue("1"), ccsText),false);
            $this->rub_TipoProceso->ds->Where = $this->rub_TipoProceso->ds->wp->Criterion[1];
            $this->rub_TipoProceso->Required = true;
            $this->rub_Grupo = new clsControl(ccsListBox, "rub_Grupo", "Grupo", ccsInteger, "", CCGetRequestParam("rub_Grupo", $Method));
            $this->rub_Grupo->DSType = dsTable;
            list($this->rub_Grupo->BoundColumn, $this->rub_Grupo->TextColumn, $this->rub_Grupo->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->rub_Grupo->ds = new clsDBdatos();
            $this->rub_Grupo->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->rub_Grupo->ds->Parameters["expr42"] = 'LGGRUP';
            $this->rub_Grupo->ds->wp = new clsSQLParameters();
            $this->rub_Grupo->ds->wp->AddParameter("1", "expr42", ccsText, "", "", $this->rub_Grupo->ds->Parameters["expr42"], "", false);
            $this->rub_Grupo->ds->wp->Criterion[1] = $this->rub_Grupo->ds->wp->Operation(opEqual, "par_Clave", $this->rub_Grupo->ds->wp->GetDBValue("1"), $this->rub_Grupo->ds->ToSQL($this->rub_Grupo->ds->wp->GetDBValue("1"), ccsText),false);
            $this->rub_Grupo->ds->Where = $this->rub_Grupo->ds->wp->Criterion[1];
            $this->rub_Grupo->Required = true;
            $this->rub_DescCorta = new clsControl(ccsTextBox, "rub_DescCorta", "Descripcion", ccsText, "", CCGetRequestParam("rub_DescCorta", $Method));
            $this->rub_DescLarga = new clsControl(ccsTextBox, "rub_DescLarga", "Descripción Larga", ccsText, "", CCGetRequestParam("rub_DescLarga", $Method));
            $this->rub_PosOrdinal = new clsControl(ccsTextBox, "rub_PosOrdinal", "Posición Ordinal", ccsInteger, "", CCGetRequestParam("rub_PosOrdinal", $Method));
            $this->rub_PosOrdinal->Required = true;
            $this->rub_IndContab = new clsControl(ccsRadioButton, "rub_IndContab", "Indicador Contabilizacion", ccsInteger, "", CCGetRequestParam("rub_IndContab", $Method));
            $this->rub_IndContab->DSType = dsListOfValues;
            $this->rub_IndContab->Values = array(array("1", "Sí"), array("0", "No"));
            $this->rub_IndContab->HTML = true;
            $this->rub_IndContab->Required = true;
            $this->rub_IndDbCr = new clsControl(ccsRadioButton, "rub_IndDbCr", "Indicador DB / CR", ccsInteger, "", CCGetRequestParam("rub_IndDbCr", $Method));
            $this->rub_IndDbCr->DSType = dsListOfValues;
            $this->rub_IndDbCr->Values = array(array("1", "Débito"), array("-1", "Crédito"));
            $this->rub_IndDbCr->HTML = true;
            $this->rub_IndDbCr->Required = true;
            $this->rub_CtaOrigen = new clsControl(ccsListBox, "rub_CtaOrigen", "Cuenta Origen", ccsText, "", CCGetRequestParam("rub_CtaOrigen", $Method));
            $this->rub_CtaOrigen->DSType = dsSQL;
            list($this->rub_CtaOrigen->BoundColumn, $this->rub_CtaOrigen->TextColumn, $this->rub_CtaOrigen->DBFormat) = array("cue_codcuenta", "descr", "");
            $this->rub_CtaOrigen->ds = new clsDBdatos();
            $this->rub_CtaOrigen->ds->SQL = "SELECT cue_codcuenta, concat(cue_codcuenta, ' - - -',  cue_descripcion) as descr " .
            "FROM concuentas " .
            "where cue_codcuenta > ' ' " .
            "";
            $this->rub_CtaOrigen->ds->Order = "1";
            $this->rub_CtaDestino = new clsControl(ccsListBox, "rub_CtaDestino", "Cuenta Destino", ccsText, "", CCGetRequestParam("rub_CtaDestino", $Method));
            $this->rub_CtaDestino->DSType = dsSQL;
            list($this->rub_CtaDestino->BoundColumn, $this->rub_CtaDestino->TextColumn, $this->rub_CtaDestino->DBFormat) = array("cue_codcuenta", "descr", "");
            $this->rub_CtaDestino->ds = new clsDBdatos();
            $this->rub_CtaDestino->ds->SQL = "SELECT cue_codcuenta, concat(cue_codcuenta, ' - - -',  cue_descripcion) as descr " .
            "FROM concuentas " .
            "where cue_codcuenta > ' ' " .
            "";
            $this->rub_CtaDestino->ds->Order = "1";
            $this->rub_variablea = new clsControl(ccsListBox, "rub_variablea", "Variable A", ccsText, "", CCGetRequestParam("rub_variablea", $Method));
            $this->rub_variablea->DSType = dsSQL;
            list($this->rub_variablea->BoundColumn, $this->rub_variablea->TextColumn, $this->rub_variablea->DBFormat) = array("var_Nombre", "var_descripcion", "");
            $this->rub_variablea->ds = new clsDBdatos();
            $this->rub_variablea->ds->SQL = "SELECT var_Nombre , concat(var_nombre,': ',var_Descripcion) as var_texto, var_descripcion " .
            "FROM genvarproceso";
            $this->rub_Constantea = new clsControl(ccsTextBox, "rub_Constantea", "Constante a", ccsFloat, Array(False, 4, ".", "", False, "", "", 1, True, ""), CCGetRequestParam("rub_Constantea", $Method));
            $this->rub_Operacion = new clsControl(ccsListBox, "rub_Operacion", "Operacion", ccsText, "", CCGetRequestParam("rub_Operacion", $Method));
            $this->rub_Operacion->DSType = dsListOfValues;
            $this->rub_Operacion->Values = array(array("+", "Suma"), array("*", "Multiplicacion"), array("-", "Resta"), array("/", "División"), array("A", "Aplicado con"));
            $this->rub_Operacion->Required = true;
            $this->rub_Variableb = new clsControl(ccsListBox, "rub_Variableb", "Variable b", ccsText, "", CCGetRequestParam("rub_Variableb", $Method));
            $this->rub_Variableb->DSType = dsSQL;
            list($this->rub_Variableb->BoundColumn, $this->rub_Variableb->TextColumn, $this->rub_Variableb->DBFormat) = array("var_Nombre", "var_texto", "");
            $this->rub_Variableb->ds = new clsDBdatos();
            $this->rub_Variableb->ds->SQL = "SELECT var_Nombre , concat(var_nombre,':-- ',var_Descripcion) as var_texto, var_descripcion " .
            "FROM genvarproceso " .
            "";
            $this->rub_Variableb->ds->Order = "2";
            $this->rub_Constanteb = new clsControl(ccsTextBox, "rub_Constanteb", "Constante B", ccsFloat, Array(False, 4, ".", "", False, "", "", 1, True, ""), CCGetRequestParam("rub_Constanteb", $Method));
            $this->rub_ValMinimo = new clsControl(ccsTextBox, "rub_ValMinimo", "Valor Mínimo", ccsFloat, "", CCGetRequestParam("rub_ValMinimo", $Method));
            $this->rub_IndDetalle = new clsControl(ccsRadioButton, "rub_IndDetalle", "Detallar", ccsInteger, "", CCGetRequestParam("rub_IndDetalle", $Method));
            $this->rub_IndDetalle->DSType = dsListOfValues;
            $this->rub_IndDetalle->Values = array(array("1", "Detalle"), array("0", "Productor"));
            $this->rub_IndDetalle->HTML = true;
            $this->rub_IndDetalle->Required = true;
            $this->rub_ValMaximo = new clsControl(ccsTextBox, "rub_ValMaximo", "Valor Máximo", ccsFloat, "", CCGetRequestParam("rub_ValMaximo", $Method));
            $this->rub_IndCantidad = new clsControl(ccsRadioButton, "rub_IndCantidad", "Indicador Cantidad", ccsInteger, "", CCGetRequestParam("rub_IndCantidad", $Method));
            $this->rub_IndCantidad->DSType = dsListOfValues;
            $this->rub_IndCantidad->Values = array(array("1", "Sí"), array("0", "No"));
            $this->rub_IndCantidad->HTML = true;
            $this->rub_IndCantidad->Required = true;
            $this->rub_IndTexto = new clsControl(ccsRadioButton, "rub_IndTexto", "Texto", ccsInteger, "", CCGetRequestParam("rub_IndTexto", $Method));
            $this->rub_IndTexto->DSType = dsListOfValues;
            $this->rub_IndTexto->Values = array(array("1", "Sí"), array("0", "No"));
            $this->rub_IndTexto->HTML = true;
            $this->rub_IndTexto->Required = true;
            $this->rub_IndOpcional = new clsControl(ccsRadioButton, "rub_IndOpcional", "Opcional", ccsInteger, "", CCGetRequestParam("rub_IndOpcional", $Method));
            $this->rub_IndOpcional->DSType = dsListOfValues;
            $this->rub_IndOpcional->Values = array(array("1", "Sí"), array("0", "No"));
            $this->rub_IndOpcional->HTML = true;
            $this->rub_IndOpcional->Required = true;
            $this->ind_Modificacion = new clsControl(ccsRadioButton, "ind_Modificacion", "Modificable", ccsInteger, "", CCGetRequestParam("ind_Modificacion", $Method));
            $this->ind_Modificacion->DSType = dsListOfValues;
            $this->ind_Modificacion->Values = array(array("1", "Sí"), array("0", "No"));
            $this->ind_Modificacion->HTML = true;
            $this->ind_Modificacion->Required = true;
            $this->rub_IndImpuesto = new clsControl(ccsRadioButton, "rub_IndImpuesto", "Rub Ind Impuesto", ccsInteger, "", CCGetRequestParam("rub_IndImpuesto", $Method));
            $this->rub_IndImpuesto->DSType = dsListOfValues;
            $this->rub_IndImpuesto->Values = array(array("1", "Sí"), array("0", "No"));
            $this->rub_IndImpuesto->HTML = true;
            $this->rub_IndImpuesto->Required = true;
            $this->rub_IndGeneracion = new clsControl(ccsRadioButton, "rub_IndGeneracion", "Generado", ccsInteger, "", CCGetRequestParam("rub_IndGeneracion", $Method));
            $this->rub_IndGeneracion->DSType = dsListOfValues;
            $this->rub_IndGeneracion->Values = array(array("1", "Sí"), array("0", "No"));
            $this->rub_IndGeneracion->HTML = true;
            $this->rub_IndGeneracion->Required = true;
            $this->rub_Activo = new clsControl(ccsRadioButton, "rub_Activo", "Estado", ccsText, "", CCGetRequestParam("rub_Activo", $Method));
            $this->rub_Activo->DSType = dsListOfValues;
            $this->rub_Activo->Values = array(array("1", "Sí"), array("0", "No"));
            $this->rub_Activo->HTML = true;
            $this->rub_Activo->Required = true;
            $this->rub_PagAuxiliar = new clsControl(ccsListBox, "rub_PagAuxiliar", "rub_PagAuxiliar", ccsText, "", CCGetRequestParam("rub_PagAuxiliar", $Method));
            $this->rub_PagAuxiliar->DSType = dsSQL;
            list($this->rub_PagAuxiliar->BoundColumn, $this->rub_PagAuxiliar->TextColumn, $this->rub_PagAuxiliar->DBFormat) = array("", "", "");
            $this->rub_PagAuxiliar->ds = new clsDBdatos();
            $this->rub_PagAuxiliar->ds->SQL = "SELECT par_valor1, par_descripcion " .
            "FROM genparametros " .
            "WHERE  par_clave = \"LGPAUX\" " .
            "";
            $this->rub_PagAuxiliar->ds->Order = "par_secuencia";
            $this->Button_Insert = new clsButton("Button_Insert");
            $this->Button_Update = new clsButton("Button_Update");
            $this->Button_Delete = new clsButton("Button_Delete");
            $this->Button_Cancel = new clsButton("Button_Cancel");
            if(!$this->FormSubmitted) {
                if(!is_array($this->rub_TipoProceso->Value) && !strlen($this->rub_TipoProceso->Value) && $this->rub_TipoProceso->Value !== false)
                $this->rub_TipoProceso->SetText(10);
                if(!is_array($this->rub_IndDetalle->Value) && !strlen($this->rub_IndDetalle->Value) && $this->rub_IndDetalle->Value !== false)
                $this->rub_IndDetalle->SetText(0);
                if(!is_array($this->rub_IndCantidad->Value) && !strlen($this->rub_IndCantidad->Value) && $this->rub_IndCantidad->Value !== false)
                $this->rub_IndCantidad->SetText(0);
                if(!is_array($this->rub_IndTexto->Value) && !strlen($this->rub_IndTexto->Value) && $this->rub_IndTexto->Value !== false)
                $this->rub_IndTexto->SetText(0);
                if(!is_array($this->rub_IndOpcional->Value) && !strlen($this->rub_IndOpcional->Value) && $this->rub_IndOpcional->Value !== false)
                $this->rub_IndOpcional->SetText(0);
                if(!is_array($this->ind_Modificacion->Value) && !strlen($this->ind_Modificacion->Value) && $this->ind_Modificacion->Value !== false)
                $this->ind_Modificacion->SetText(1);
                if(!is_array($this->rub_IndImpuesto->Value) && !strlen($this->rub_IndImpuesto->Value) && $this->rub_IndImpuesto->Value !== false)
                $this->rub_IndImpuesto->SetText(0);
                if(!is_array($this->rub_IndGeneracion->Value) && !strlen($this->rub_IndGeneracion->Value) && $this->rub_IndGeneracion->Value !== false)
                $this->rub_IndGeneracion->SetText(1);
                if(!is_array($this->rub_Activo->Value) && !strlen($this->rub_Activo->Value) && $this->rub_Activo->Value !== false)
                $this->rub_Activo->SetText(1);
                if(!is_array($this->rub_PagAuxiliar->Value) && !strlen($this->rub_PagAuxiliar->Value) && $this->rub_PagAuxiliar->Value !== false)
                $this->rub_PagAuxiliar->SetText(99);
            }
            if(!is_array($this->lbTitulo->Value) && !strlen($this->lbTitulo->Value) && $this->lbTitulo->Value !== false)
            $this->lbTitulo->SetText('NUEVO RUBRO');
        }
    }
//End Class_Initialize Event

//Initialize Method @2-F7D78816
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->ds->Parameters["urlrub_CodRubro"] = CCGetFromGet("rub_CodRubro", "");
    }
//End Initialize Method

//Validate Method @2-BAC2659A
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->rub_Abreviatura->Validate() && $Validation);
        $Validation = ($this->rub_TipoProceso->Validate() && $Validation);
        $Validation = ($this->rub_Grupo->Validate() && $Validation);
        $Validation = ($this->rub_DescCorta->Validate() && $Validation);
        $Validation = ($this->rub_DescLarga->Validate() && $Validation);
        $Validation = ($this->rub_PosOrdinal->Validate() && $Validation);
        $Validation = ($this->rub_IndContab->Validate() && $Validation);
        $Validation = ($this->rub_IndDbCr->Validate() && $Validation);
        $Validation = ($this->rub_CtaOrigen->Validate() && $Validation);
        $Validation = ($this->rub_CtaDestino->Validate() && $Validation);
        $Validation = ($this->rub_variablea->Validate() && $Validation);
        $Validation = ($this->rub_Constantea->Validate() && $Validation);
        $Validation = ($this->rub_Operacion->Validate() && $Validation);
        $Validation = ($this->rub_Variableb->Validate() && $Validation);
        $Validation = ($this->rub_Constanteb->Validate() && $Validation);
        $Validation = ($this->rub_ValMinimo->Validate() && $Validation);
        $Validation = ($this->rub_IndDetalle->Validate() && $Validation);
        $Validation = ($this->rub_ValMaximo->Validate() && $Validation);
        $Validation = ($this->rub_IndCantidad->Validate() && $Validation);
        $Validation = ($this->rub_IndTexto->Validate() && $Validation);
        $Validation = ($this->rub_IndOpcional->Validate() && $Validation);
        $Validation = ($this->ind_Modificacion->Validate() && $Validation);
        $Validation = ($this->rub_IndImpuesto->Validate() && $Validation);
        $Validation = ($this->rub_IndGeneracion->Validate() && $Validation);
        $Validation = ($this->rub_Activo->Validate() && $Validation);
        $Validation = ($this->rub_PagAuxiliar->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-29084556
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->lbTitulo->Errors->Count());
        $errors = ($errors || $this->rub_Abreviatura->Errors->Count());
        $errors = ($errors || $this->rub_TipoProceso->Errors->Count());
        $errors = ($errors || $this->rub_Grupo->Errors->Count());
        $errors = ($errors || $this->rub_DescCorta->Errors->Count());
        $errors = ($errors || $this->rub_DescLarga->Errors->Count());
        $errors = ($errors || $this->rub_PosOrdinal->Errors->Count());
        $errors = ($errors || $this->rub_IndContab->Errors->Count());
        $errors = ($errors || $this->rub_IndDbCr->Errors->Count());
        $errors = ($errors || $this->rub_CtaOrigen->Errors->Count());
        $errors = ($errors || $this->rub_CtaDestino->Errors->Count());
        $errors = ($errors || $this->rub_variablea->Errors->Count());
        $errors = ($errors || $this->rub_Constantea->Errors->Count());
        $errors = ($errors || $this->rub_Operacion->Errors->Count());
        $errors = ($errors || $this->rub_Variableb->Errors->Count());
        $errors = ($errors || $this->rub_Constanteb->Errors->Count());
        $errors = ($errors || $this->rub_ValMinimo->Errors->Count());
        $errors = ($errors || $this->rub_IndDetalle->Errors->Count());
        $errors = ($errors || $this->rub_ValMaximo->Errors->Count());
        $errors = ($errors || $this->rub_IndCantidad->Errors->Count());
        $errors = ($errors || $this->rub_IndTexto->Errors->Count());
        $errors = ($errors || $this->rub_IndOpcional->Errors->Count());
        $errors = ($errors || $this->ind_Modificacion->Errors->Count());
        $errors = ($errors || $this->rub_IndImpuesto->Errors->Count());
        $errors = ($errors || $this->rub_IndGeneracion->Errors->Count());
        $errors = ($errors || $this->rub_Activo->Errors->Count());
        $errors = ($errors || $this->rub_PagAuxiliar->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-365D367D
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
            } else if(strlen(CCGetParam("Button_Cancel", ""))) {
                $this->PressedButton = "Button_Cancel";
            }
        }
        $Redirect = "LiAdRu_mant.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "Button_Delete") {
            if(!CCGetEvent($this->Button_Delete->CCSEvents, "OnClick") || !$this->DeleteRow()) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "Button_Cancel") {
            if(!CCGetEvent($this->Button_Cancel->CCSEvents, "OnClick")) {
                $Redirect = "";
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

//InsertRow Method @2-21E62DC9
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert");
        if(!$this->InsertAllowed) return false;
        $this->ds->lbTitulo->SetValue($this->lbTitulo->GetValue());
        $this->ds->rub_Abreviatura->SetValue($this->rub_Abreviatura->GetValue());
        $this->ds->rub_TipoProceso->SetValue($this->rub_TipoProceso->GetValue());
        $this->ds->rub_Grupo->SetValue($this->rub_Grupo->GetValue());
        $this->ds->rub_DescCorta->SetValue($this->rub_DescCorta->GetValue());
        $this->ds->rub_DescLarga->SetValue($this->rub_DescLarga->GetValue());
        $this->ds->rub_PosOrdinal->SetValue($this->rub_PosOrdinal->GetValue());
        $this->ds->rub_IndContab->SetValue($this->rub_IndContab->GetValue());
        $this->ds->rub_IndDbCr->SetValue($this->rub_IndDbCr->GetValue());
        $this->ds->rub_CtaOrigen->SetValue($this->rub_CtaOrigen->GetValue());
        $this->ds->rub_CtaDestino->SetValue($this->rub_CtaDestino->GetValue());
        $this->ds->rub_variablea->SetValue($this->rub_variablea->GetValue());
        $this->ds->rub_Constantea->SetValue($this->rub_Constantea->GetValue());
        $this->ds->rub_Operacion->SetValue($this->rub_Operacion->GetValue());
        $this->ds->rub_Variableb->SetValue($this->rub_Variableb->GetValue());
        $this->ds->rub_Constanteb->SetValue($this->rub_Constanteb->GetValue());
        $this->ds->rub_ValMinimo->SetValue($this->rub_ValMinimo->GetValue());
        $this->ds->rub_IndDetalle->SetValue($this->rub_IndDetalle->GetValue());
        $this->ds->rub_ValMaximo->SetValue($this->rub_ValMaximo->GetValue());
        $this->ds->rub_IndCantidad->SetValue($this->rub_IndCantidad->GetValue());
        $this->ds->rub_IndTexto->SetValue($this->rub_IndTexto->GetValue());
        $this->ds->rub_IndOpcional->SetValue($this->rub_IndOpcional->GetValue());
        $this->ds->ind_Modificacion->SetValue($this->ind_Modificacion->GetValue());
        $this->ds->rub_IndImpuesto->SetValue($this->rub_IndImpuesto->GetValue());
        $this->ds->rub_IndGeneracion->SetValue($this->rub_IndGeneracion->GetValue());
        $this->ds->rub_Activo->SetValue($this->rub_Activo->GetValue());
        $this->ds->rub_PagAuxiliar->SetValue($this->rub_PagAuxiliar->GetValue());
        $this->ds->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert");
        if($this->ds->Errors->Count() > 0) {
            echo "Error in Record " . $this->ComponentName . " / Insert Operation";
            $this->ds->Errors->Clear();
            $this->Errors->AddError("Database command error.");
        }
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @2-833103A3
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate");
        if(!$this->UpdateAllowed) return false;
        $this->ds->rub_Abreviatura->SetValue($this->rub_Abreviatura->GetValue());
        $this->ds->rub_TipoProceso->SetValue($this->rub_TipoProceso->GetValue());
        $this->ds->rub_Grupo->SetValue($this->rub_Grupo->GetValue());
        $this->ds->rub_DescCorta->SetValue($this->rub_DescCorta->GetValue());
        $this->ds->rub_DescLarga->SetValue($this->rub_DescLarga->GetValue());
        $this->ds->rub_PosOrdinal->SetValue($this->rub_PosOrdinal->GetValue());
        $this->ds->rub_IndContab->SetValue($this->rub_IndContab->GetValue());
        $this->ds->rub_IndDbCr->SetValue($this->rub_IndDbCr->GetValue());
        $this->ds->rub_CtaOrigen->SetValue($this->rub_CtaOrigen->GetValue());
        $this->ds->rub_CtaDestino->SetValue($this->rub_CtaDestino->GetValue());
        $this->ds->rub_variablea->SetValue($this->rub_variablea->GetValue());
        $this->ds->rub_Constantea->SetValue($this->rub_Constantea->GetValue());
        $this->ds->rub_Operacion->SetValue($this->rub_Operacion->GetValue());
        $this->ds->rub_Variableb->SetValue($this->rub_Variableb->GetValue());
        $this->ds->rub_Constanteb->SetValue($this->rub_Constanteb->GetValue());
        $this->ds->rub_ValMinimo->SetValue($this->rub_ValMinimo->GetValue());
        $this->ds->rub_IndDetalle->SetValue($this->rub_IndDetalle->GetValue());
        $this->ds->rub_ValMaximo->SetValue($this->rub_ValMaximo->GetValue());
        $this->ds->rub_IndCantidad->SetValue($this->rub_IndCantidad->GetValue());
        $this->ds->rub_IndTexto->SetValue($this->rub_IndTexto->GetValue());
        $this->ds->rub_IndOpcional->SetValue($this->rub_IndOpcional->GetValue());
        $this->ds->ind_Modificacion->SetValue($this->ind_Modificacion->GetValue());
        $this->ds->rub_IndImpuesto->SetValue($this->rub_IndImpuesto->GetValue());
        $this->ds->rub_IndGeneracion->SetValue($this->rub_IndGeneracion->GetValue());
        $this->ds->rub_Activo->SetValue($this->rub_Activo->GetValue());
        $this->ds->rub_PagAuxiliar->SetValue($this->rub_PagAuxiliar->GetValue());
        $this->ds->Update();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate");
        if($this->ds->Errors->Count() > 0) {
            echo "Error in Record " . $this->ComponentName . " / Update Operation";
            $this->ds->Errors->Clear();
            $this->Errors->AddError("Database command error.");
        }
        return (!$this->CheckErrors());
    }
//End UpdateRow Method

//DeleteRow Method @2-EA88835F
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

//Show Method @2-39164084
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->rub_TipoProceso->Prepare();
        $this->rub_Grupo->Prepare();
        $this->rub_IndContab->Prepare();
        $this->rub_IndDbCr->Prepare();
        $this->rub_CtaOrigen->Prepare();
        $this->rub_CtaDestino->Prepare();
        $this->rub_variablea->Prepare();
        $this->rub_Operacion->Prepare();
        $this->rub_Variableb->Prepare();
        $this->rub_IndDetalle->Prepare();
        $this->rub_IndCantidad->Prepare();
        $this->rub_IndTexto->Prepare();
        $this->rub_IndOpcional->Prepare();
        $this->ind_Modificacion->Prepare();
        $this->rub_IndImpuesto->Prepare();
        $this->rub_IndGeneracion->Prepare();
        $this->rub_Activo->Prepare();
        $this->rub_PagAuxiliar->Prepare();

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
                    echo "Error in Record liqrubros";
                }
                else if($this->ds->next_record())
                {
                    $this->ds->SetValues();
                    if(!$this->FormSubmitted)
                    {
                        $this->rub_Abreviatura->SetValue($this->ds->rub_Abreviatura->GetValue());
                        $this->rub_TipoProceso->SetValue($this->ds->rub_TipoProceso->GetValue());
                        $this->rub_Grupo->SetValue($this->ds->rub_Grupo->GetValue());
                        $this->rub_DescCorta->SetValue($this->ds->rub_DescCorta->GetValue());
                        $this->rub_DescLarga->SetValue($this->ds->rub_DescLarga->GetValue());
                        $this->rub_PosOrdinal->SetValue($this->ds->rub_PosOrdinal->GetValue());
                        $this->rub_IndContab->SetValue($this->ds->rub_IndContab->GetValue());
                        $this->rub_IndDbCr->SetValue($this->ds->rub_IndDbCr->GetValue());
                        $this->rub_CtaOrigen->SetValue($this->ds->rub_CtaOrigen->GetValue());
                        $this->rub_CtaDestino->SetValue($this->ds->rub_CtaDestino->GetValue());
                        $this->rub_variablea->SetValue($this->ds->rub_variablea->GetValue());
                        $this->rub_Constantea->SetValue($this->ds->rub_Constantea->GetValue());
                        $this->rub_Operacion->SetValue($this->ds->rub_Operacion->GetValue());
                        $this->rub_Variableb->SetValue($this->ds->rub_Variableb->GetValue());
                        $this->rub_Constanteb->SetValue($this->ds->rub_Constanteb->GetValue());
                        $this->rub_ValMinimo->SetValue($this->ds->rub_ValMinimo->GetValue());
                        $this->rub_IndDetalle->SetValue($this->ds->rub_IndDetalle->GetValue());
                        $this->rub_ValMaximo->SetValue($this->ds->rub_ValMaximo->GetValue());
                        $this->rub_IndCantidad->SetValue($this->ds->rub_IndCantidad->GetValue());
                        $this->rub_IndTexto->SetValue($this->ds->rub_IndTexto->GetValue());
                        $this->rub_IndOpcional->SetValue($this->ds->rub_IndOpcional->GetValue());
                        $this->ind_Modificacion->SetValue($this->ds->ind_Modificacion->GetValue());
                        $this->rub_IndImpuesto->SetValue($this->ds->rub_IndImpuesto->GetValue());
                        $this->rub_IndGeneracion->SetValue($this->ds->rub_IndGeneracion->GetValue());
                        $this->rub_Activo->SetValue($this->ds->rub_Activo->GetValue());
                        $this->rub_PagAuxiliar->SetValue($this->ds->rub_PagAuxiliar->GetValue());
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
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->lbTitulo->Errors->ToString();
            $Error .= $this->rub_Abreviatura->Errors->ToString();
            $Error .= $this->rub_TipoProceso->Errors->ToString();
            $Error .= $this->rub_Grupo->Errors->ToString();
            $Error .= $this->rub_DescCorta->Errors->ToString();
            $Error .= $this->rub_DescLarga->Errors->ToString();
            $Error .= $this->rub_PosOrdinal->Errors->ToString();
            $Error .= $this->rub_IndContab->Errors->ToString();
            $Error .= $this->rub_IndDbCr->Errors->ToString();
            $Error .= $this->rub_CtaOrigen->Errors->ToString();
            $Error .= $this->rub_CtaDestino->Errors->ToString();
            $Error .= $this->rub_variablea->Errors->ToString();
            $Error .= $this->rub_Constantea->Errors->ToString();
            $Error .= $this->rub_Operacion->Errors->ToString();
            $Error .= $this->rub_Variableb->Errors->ToString();
            $Error .= $this->rub_Constanteb->Errors->ToString();
            $Error .= $this->rub_ValMinimo->Errors->ToString();
            $Error .= $this->rub_IndDetalle->Errors->ToString();
            $Error .= $this->rub_ValMaximo->Errors->ToString();
            $Error .= $this->rub_IndCantidad->Errors->ToString();
            $Error .= $this->rub_IndTexto->Errors->ToString();
            $Error .= $this->rub_IndOpcional->Errors->ToString();
            $Error .= $this->ind_Modificacion->Errors->ToString();
            $Error .= $this->rub_IndImpuesto->Errors->ToString();
            $Error .= $this->rub_IndGeneracion->Errors->ToString();
            $Error .= $this->rub_Activo->Errors->ToString();
            $Error .= $this->rub_PagAuxiliar->Errors->ToString();
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
        $this->Button_Insert->Visible = !$this->EditMode && $this->InsertAllowed;
        $this->Button_Update->Visible = $this->EditMode && $this->UpdateAllowed;
        $this->Button_Delete->Visible = $this->EditMode && $this->DeleteAllowed;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        $this->lbTitulo->Show();
        $this->rub_Abreviatura->Show();
        $this->rub_TipoProceso->Show();
        $this->rub_Grupo->Show();
        $this->rub_DescCorta->Show();
        $this->rub_DescLarga->Show();
        $this->rub_PosOrdinal->Show();
        $this->rub_IndContab->Show();
        $this->rub_IndDbCr->Show();
        $this->rub_CtaOrigen->Show();
        $this->rub_CtaDestino->Show();
        $this->rub_variablea->Show();
        $this->rub_Constantea->Show();
        $this->rub_Operacion->Show();
        $this->rub_Variableb->Show();
        $this->rub_Constanteb->Show();
        $this->rub_ValMinimo->Show();
        $this->rub_IndDetalle->Show();
        $this->rub_ValMaximo->Show();
        $this->rub_IndCantidad->Show();
        $this->rub_IndTexto->Show();
        $this->rub_IndOpcional->Show();
        $this->ind_Modificacion->Show();
        $this->rub_IndImpuesto->Show();
        $this->rub_IndGeneracion->Show();
        $this->rub_Activo->Show();
        $this->rub_PagAuxiliar->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->Button_Cancel->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

} //End liqrubros Class @2-FCB6E20C

class clsliqrubrosDataSource extends clsDBdatos {  //liqrubrosDataSource Class @2-8976F53D

//DataSource Variables @2-C3893254
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
    var $rub_Abreviatura;
    var $rub_TipoProceso;
    var $rub_Grupo;
    var $rub_DescCorta;
    var $rub_DescLarga;
    var $rub_PosOrdinal;
    var $rub_IndContab;
    var $rub_IndDbCr;
    var $rub_CtaOrigen;
    var $rub_CtaDestino;
    var $rub_variablea;
    var $rub_Constantea;
    var $rub_Operacion;
    var $rub_Variableb;
    var $rub_Constanteb;
    var $rub_ValMinimo;
    var $rub_IndDetalle;
    var $rub_ValMaximo;
    var $rub_IndCantidad;
    var $rub_IndTexto;
    var $rub_IndOpcional;
    var $ind_Modificacion;
    var $rub_IndImpuesto;
    var $rub_IndGeneracion;
    var $rub_Activo;
    var $rub_PagAuxiliar;
//End DataSource Variables

//Class_Initialize Event @2-9A9EC154
    function clsliqrubrosDataSource()
    {
        $this->ErrorBlock = "Record liqrubros/Error";
        $this->Initialize();
        $this->lbTitulo = new clsField("lbTitulo", ccsText, "");
        $this->rub_Abreviatura = new clsField("rub_Abreviatura", ccsText, "");
        $this->rub_TipoProceso = new clsField("rub_TipoProceso", ccsInteger, "");
        $this->rub_Grupo = new clsField("rub_Grupo", ccsInteger, "");
        $this->rub_DescCorta = new clsField("rub_DescCorta", ccsText, "");
        $this->rub_DescLarga = new clsField("rub_DescLarga", ccsText, "");
        $this->rub_PosOrdinal = new clsField("rub_PosOrdinal", ccsInteger, "");
        $this->rub_IndContab = new clsField("rub_IndContab", ccsInteger, "");
        $this->rub_IndDbCr = new clsField("rub_IndDbCr", ccsInteger, "");
        $this->rub_CtaOrigen = new clsField("rub_CtaOrigen", ccsText, "");
        $this->rub_CtaDestino = new clsField("rub_CtaDestino", ccsText, "");
        $this->rub_variablea = new clsField("rub_variablea", ccsText, "");
        $this->rub_Constantea = new clsField("rub_Constantea", ccsFloat, "");
        $this->rub_Operacion = new clsField("rub_Operacion", ccsText, "");
        $this->rub_Variableb = new clsField("rub_Variableb", ccsText, "");
        $this->rub_Constanteb = new clsField("rub_Constanteb", ccsFloat, "");
        $this->rub_ValMinimo = new clsField("rub_ValMinimo", ccsFloat, "");
        $this->rub_IndDetalle = new clsField("rub_IndDetalle", ccsInteger, "");
        $this->rub_ValMaximo = new clsField("rub_ValMaximo", ccsFloat, "");
        $this->rub_IndCantidad = new clsField("rub_IndCantidad", ccsInteger, "");
        $this->rub_IndTexto = new clsField("rub_IndTexto", ccsInteger, "");
        $this->rub_IndOpcional = new clsField("rub_IndOpcional", ccsInteger, "");
        $this->ind_Modificacion = new clsField("ind_Modificacion", ccsInteger, "");
        $this->rub_IndImpuesto = new clsField("rub_IndImpuesto", ccsInteger, "");
        $this->rub_IndGeneracion = new clsField("rub_IndGeneracion", ccsInteger, "");
        $this->rub_Activo = new clsField("rub_Activo", ccsText, "");
        $this->rub_PagAuxiliar = new clsField("rub_PagAuxiliar", ccsText, "");

    }
//End Class_Initialize Event

//Prepare Method @2-F0295406
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlrub_CodRubro", ccsInteger, "", "", $this->Parameters["urlrub_CodRubro"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "rub_CodRubro", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @2-045C081C
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->SQL = "SELECT *  " .
        "FROM liqrubros";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @2-C5F9C5BC
    function SetValues()
    {
        $this->rub_Abreviatura->SetDBValue($this->f("rub_Abreviatura"));
        $this->rub_TipoProceso->SetDBValue(trim($this->f("rub_TipoProceso")));
        $this->rub_Grupo->SetDBValue(trim($this->f("rub_Grupo")));
        $this->rub_DescCorta->SetDBValue($this->f("rub_DescCorta"));
        $this->rub_DescLarga->SetDBValue($this->f("rub_DescLarga"));
        $this->rub_PosOrdinal->SetDBValue(trim($this->f("rub_PosOrdinal")));
        $this->rub_IndContab->SetDBValue(trim($this->f("rub_IndContab")));
        $this->rub_IndDbCr->SetDBValue(trim($this->f("rub_IndDbCr")));
        $this->rub_CtaOrigen->SetDBValue($this->f("rub_CtaOrigen"));
        $this->rub_CtaDestino->SetDBValue($this->f("rub_CtaDestino"));
        $this->rub_variablea->SetDBValue($this->f("rub_variablea"));
        $this->rub_Constantea->SetDBValue(trim($this->f("rub_Constantea")));
        $this->rub_Operacion->SetDBValue($this->f("rub_Operacion"));
        $this->rub_Variableb->SetDBValue($this->f("rub_Variableb"));
        $this->rub_Constanteb->SetDBValue(trim($this->f("rub_Constanteb")));
        $this->rub_ValMinimo->SetDBValue(trim($this->f("rub_ValMinimo")));
        $this->rub_IndDetalle->SetDBValue(trim($this->f("rub_IndDetalle")));
        $this->rub_ValMaximo->SetDBValue(trim($this->f("rub_ValMaximo")));
        $this->rub_IndCantidad->SetDBValue(trim($this->f("rub_IndCantidad")));
        $this->rub_IndTexto->SetDBValue(trim($this->f("rub_IndTexto")));
        $this->rub_IndOpcional->SetDBValue(trim($this->f("rub_IndOpcional")));
        $this->ind_Modificacion->SetDBValue(trim($this->f("ind_Modificacion")));
        $this->rub_IndImpuesto->SetDBValue(trim($this->f("rub_IndImpuesto")));
        $this->rub_IndGeneracion->SetDBValue(trim($this->f("rub_IndGeneracion")));
        $this->rub_Activo->SetDBValue($this->f("rub_Activo"));
        $this->rub_PagAuxiliar->SetDBValue($this->f("rub_PagAuxiliar"));
    }
//End SetValues Method

//Insert Method @2-A33B7248
    function Insert()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO liqrubros ("
             . "rub_Abreviatura, "
             . "rub_TipoProceso, "
             . "rub_Grupo, "
             . "rub_DescCorta, "
             . "rub_DescLarga, "
             . "rub_PosOrdinal, "
             . "rub_IndContab, "
             . "rub_IndDbCr, "
             . "rub_CtaOrigen, "
             . "rub_CtaDestino, "
             . "rub_variablea, "
             . "rub_Constantea, "
             . "rub_Operacion, "
             . "rub_Variableb, "
             . "rub_Constanteb, "
             . "rub_ValMinimo, "
             . "rub_IndDetalle, "
             . "rub_ValMaximo, "
             . "rub_IndCantidad, "
             . "rub_IndTexto, "
             . "rub_IndOpcional, "
             . "ind_Modificacion, "
             . "rub_IndImpuesto, "
             . "rub_IndGeneracion, "
             . "rub_Activo, "
             . "rub_PagAuxiliar"
             . ") VALUES ("
             . $this->ToSQL($this->rub_Abreviatura->GetDBValue(), $this->rub_Abreviatura->DataType) . ", "
             . $this->ToSQL($this->rub_TipoProceso->GetDBValue(), $this->rub_TipoProceso->DataType) . ", "
             . $this->ToSQL($this->rub_Grupo->GetDBValue(), $this->rub_Grupo->DataType) . ", "
             . $this->ToSQL($this->rub_DescCorta->GetDBValue(), $this->rub_DescCorta->DataType) . ", "
             . $this->ToSQL($this->rub_DescLarga->GetDBValue(), $this->rub_DescLarga->DataType) . ", "
             . $this->ToSQL($this->rub_PosOrdinal->GetDBValue(), $this->rub_PosOrdinal->DataType) . ", "
             . $this->ToSQL($this->rub_IndContab->GetDBValue(), $this->rub_IndContab->DataType) . ", "
             . $this->ToSQL($this->rub_IndDbCr->GetDBValue(), $this->rub_IndDbCr->DataType) . ", "
             . $this->ToSQL($this->rub_CtaOrigen->GetDBValue(), $this->rub_CtaOrigen->DataType) . ", "
             . $this->ToSQL($this->rub_CtaDestino->GetDBValue(), $this->rub_CtaDestino->DataType) . ", "
             . $this->ToSQL($this->rub_variablea->GetDBValue(), $this->rub_variablea->DataType) . ", "
             . $this->ToSQL($this->rub_Constantea->GetDBValue(), $this->rub_Constantea->DataType) . ", "
             . $this->ToSQL($this->rub_Operacion->GetDBValue(), $this->rub_Operacion->DataType) . ", "
             . $this->ToSQL($this->rub_Variableb->GetDBValue(), $this->rub_Variableb->DataType) . ", "
             . $this->ToSQL($this->rub_Constanteb->GetDBValue(), $this->rub_Constanteb->DataType) . ", "
             . $this->ToSQL($this->rub_ValMinimo->GetDBValue(), $this->rub_ValMinimo->DataType) . ", "
             . $this->ToSQL($this->rub_IndDetalle->GetDBValue(), $this->rub_IndDetalle->DataType) . ", "
             . $this->ToSQL($this->rub_ValMaximo->GetDBValue(), $this->rub_ValMaximo->DataType) . ", "
             . $this->ToSQL($this->rub_IndCantidad->GetDBValue(), $this->rub_IndCantidad->DataType) . ", "
             . $this->ToSQL($this->rub_IndTexto->GetDBValue(), $this->rub_IndTexto->DataType) . ", "
             . $this->ToSQL($this->rub_IndOpcional->GetDBValue(), $this->rub_IndOpcional->DataType) . ", "
             . $this->ToSQL($this->ind_Modificacion->GetDBValue(), $this->ind_Modificacion->DataType) . ", "
             . $this->ToSQL($this->rub_IndImpuesto->GetDBValue(), $this->rub_IndImpuesto->DataType) . ", "
             . $this->ToSQL($this->rub_IndGeneracion->GetDBValue(), $this->rub_IndGeneracion->DataType) . ", "
             . $this->ToSQL($this->rub_Activo->GetDBValue(), $this->rub_Activo->DataType) . ", "
             . $this->ToSQL($this->rub_PagAuxiliar->GetDBValue(), $this->rub_PagAuxiliar->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Insert Method

//Update Method @2-C7352DBF
    function Update()
    {
        $this->cp["rub_Abreviatura"] = new clsSQLParameter("ctrlrub_Abreviatura", ccsText, "", "", $this->rub_Abreviatura->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["rub_TipoProceso"] = new clsSQLParameter("ctrlrub_TipoProceso", ccsInteger, "", "", $this->rub_TipoProceso->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["rub_Grupo"] = new clsSQLParameter("ctrlrub_Grupo", ccsInteger, "", "", $this->rub_Grupo->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["rub_DescCorta"] = new clsSQLParameter("ctrlrub_DescCorta", ccsText, "", "", $this->rub_DescCorta->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["rub_DescLarga"] = new clsSQLParameter("ctrlrub_DescLarga", ccsText, "", "", $this->rub_DescLarga->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["rub_PosOrdinal"] = new clsSQLParameter("ctrlrub_PosOrdinal", ccsInteger, "", "", $this->rub_PosOrdinal->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["rub_IndContab"] = new clsSQLParameter("ctrlrub_IndContab", ccsInteger, "", "", $this->rub_IndContab->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["rub_IndDbCr"] = new clsSQLParameter("ctrlrub_IndDbCr", ccsInteger, "", "", $this->rub_IndDbCr->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["rub_CtaOrigen"] = new clsSQLParameter("ctrlrub_CtaOrigen", ccsText, "", "", $this->rub_CtaOrigen->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["rub_CtaDestino"] = new clsSQLParameter("ctrlrub_CtaDestino", ccsText, "", "", $this->rub_CtaDestino->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["rub_variablea"] = new clsSQLParameter("ctrlrub_variablea", ccsText, "", "", $this->rub_variablea->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["rub_Constantea"] = new clsSQLParameter("ctrlrub_Constantea", ccsFloat, Array(False, 4, ".", "", False, "", "", 1, True, ""), "", $this->rub_Constantea->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["rub_Operacion"] = new clsSQLParameter("ctrlrub_Operacion", ccsText, "", "", $this->rub_Operacion->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["rub_Variableb"] = new clsSQLParameter("ctrlrub_Variableb", ccsText, "", "", $this->rub_Variableb->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["rub_Constanteb"] = new clsSQLParameter("ctrlrub_Constanteb", ccsFloat, Array(False, 4, ".", "", False, "", "", 1, True, ""), "", $this->rub_Constanteb->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["rub_ValMinimo"] = new clsSQLParameter("ctrlrub_ValMinimo", ccsFloat, "", "", $this->rub_ValMinimo->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["rub_IndDetalle"] = new clsSQLParameter("ctrlrub_IndDetalle", ccsInteger, "", "", $this->rub_IndDetalle->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["rub_ValMaximo"] = new clsSQLParameter("ctrlrub_ValMaximo", ccsFloat, "", "", $this->rub_ValMaximo->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["rub_IndCantidad"] = new clsSQLParameter("ctrlrub_IndCantidad", ccsInteger, "", "", $this->rub_IndCantidad->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["rub_IndTexto"] = new clsSQLParameter("ctrlrub_IndTexto", ccsInteger, "", "", $this->rub_IndTexto->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["rub_IndOpcional"] = new clsSQLParameter("ctrlrub_IndOpcional", ccsInteger, "", "", $this->rub_IndOpcional->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["ind_Modificacion"] = new clsSQLParameter("ctrlind_Modificacion", ccsInteger, "", "", $this->ind_Modificacion->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["rub_IndImpuesto"] = new clsSQLParameter("ctrlrub_IndImpuesto", ccsInteger, "", "", $this->rub_IndImpuesto->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["rub_IndGeneracion"] = new clsSQLParameter("ctrlrub_IndGeneracion", ccsInteger, "", "", $this->rub_IndGeneracion->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["rub_Activo"] = new clsSQLParameter("ctrlrub_Activo", ccsText, "", "", $this->rub_Activo->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["rub_PagAuxiliar"] = new clsSQLParameter("ctrlrub_PagAuxiliar", ccsText, "", "", $this->rub_PagAuxiliar->GetValue(), "", false, $this->ErrorBlock);
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlrub_CodRubro", ccsInteger, "", "", CCGetFromGet("rub_CodRubro", ""), "", true);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $wp->Criterion[1] = $wp->Operation(opEqual, "rub_CodRubro", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),true);
        $Where = $wp->Criterion[1];
        $this->SQL = "UPDATE liqrubros SET "
             . "rub_Abreviatura=" . $this->ToSQL($this->cp["rub_Abreviatura"]->GetDBValue(), $this->cp["rub_Abreviatura"]->DataType) . ", "
             . "rub_TipoProceso=" . $this->ToSQL($this->cp["rub_TipoProceso"]->GetDBValue(), $this->cp["rub_TipoProceso"]->DataType) . ", "
             . "rub_Grupo=" . $this->ToSQL($this->cp["rub_Grupo"]->GetDBValue(), $this->cp["rub_Grupo"]->DataType) . ", "
             . "rub_DescCorta=" . $this->ToSQL($this->cp["rub_DescCorta"]->GetDBValue(), $this->cp["rub_DescCorta"]->DataType) . ", "
             . "rub_DescLarga=" . $this->ToSQL($this->cp["rub_DescLarga"]->GetDBValue(), $this->cp["rub_DescLarga"]->DataType) . ", "
             . "rub_PosOrdinal=" . $this->ToSQL($this->cp["rub_PosOrdinal"]->GetDBValue(), $this->cp["rub_PosOrdinal"]->DataType) . ", "
             . "rub_IndContab=" . $this->ToSQL($this->cp["rub_IndContab"]->GetDBValue(), $this->cp["rub_IndContab"]->DataType) . ", "
             . "rub_IndDbCr=" . $this->ToSQL($this->cp["rub_IndDbCr"]->GetDBValue(), $this->cp["rub_IndDbCr"]->DataType) . ", "
             . "rub_CtaOrigen=" . $this->ToSQL($this->cp["rub_CtaOrigen"]->GetDBValue(), $this->cp["rub_CtaOrigen"]->DataType) . ", "
             . "rub_CtaDestino=" . $this->ToSQL($this->cp["rub_CtaDestino"]->GetDBValue(), $this->cp["rub_CtaDestino"]->DataType) . ", "
             . "rub_variablea=" . $this->ToSQL($this->cp["rub_variablea"]->GetDBValue(), $this->cp["rub_variablea"]->DataType) . ", "
             . "rub_Constantea=" . $this->ToSQL($this->cp["rub_Constantea"]->GetDBValue(), $this->cp["rub_Constantea"]->DataType) . ", "
             . "rub_Operacion=" . $this->ToSQL($this->cp["rub_Operacion"]->GetDBValue(), $this->cp["rub_Operacion"]->DataType) . ", "
             . "rub_Variableb=" . $this->ToSQL($this->cp["rub_Variableb"]->GetDBValue(), $this->cp["rub_Variableb"]->DataType) . ", "
             . "rub_Constanteb=" . $this->ToSQL($this->cp["rub_Constanteb"]->GetDBValue(), $this->cp["rub_Constanteb"]->DataType) . ", "
             . "rub_ValMinimo=" . $this->ToSQL($this->cp["rub_ValMinimo"]->GetDBValue(), $this->cp["rub_ValMinimo"]->DataType) . ", "
             . "rub_IndDetalle=" . $this->ToSQL($this->cp["rub_IndDetalle"]->GetDBValue(), $this->cp["rub_IndDetalle"]->DataType) . ", "
             . "rub_ValMaximo=" . $this->ToSQL($this->cp["rub_ValMaximo"]->GetDBValue(), $this->cp["rub_ValMaximo"]->DataType) . ", "
             . "rub_IndCantidad=" . $this->ToSQL($this->cp["rub_IndCantidad"]->GetDBValue(), $this->cp["rub_IndCantidad"]->DataType) . ", "
             . "rub_IndTexto=" . $this->ToSQL($this->cp["rub_IndTexto"]->GetDBValue(), $this->cp["rub_IndTexto"]->DataType) . ", "
             . "rub_IndOpcional=" . $this->ToSQL($this->cp["rub_IndOpcional"]->GetDBValue(), $this->cp["rub_IndOpcional"]->DataType) . ", "
             . "ind_Modificacion=" . $this->ToSQL($this->cp["ind_Modificacion"]->GetDBValue(), $this->cp["ind_Modificacion"]->DataType) . ", "
             . "rub_IndImpuesto=" . $this->ToSQL($this->cp["rub_IndImpuesto"]->GetDBValue(), $this->cp["rub_IndImpuesto"]->DataType) . ", "
             . "rub_IndGeneracion=" . $this->ToSQL($this->cp["rub_IndGeneracion"]->GetDBValue(), $this->cp["rub_IndGeneracion"]->DataType) . ", "
             . "rub_Activo=" . $this->ToSQL($this->cp["rub_Activo"]->GetDBValue(), $this->cp["rub_Activo"]->DataType) . ", "
             . "rub_PagAuxiliar=" . $this->ToSQL($this->cp["rub_PagAuxiliar"]->GetDBValue(), $this->cp["rub_PagAuxiliar"]->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Update Method

//Delete Method @2-40E2E051
    function Delete()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $this->SQL = "DELETE FROM liqrubros";
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Delete Method

} //End liqrubrosDataSource Class @2-FCB6E20C

//Initialize Page @1-6781CFA4
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

$FileName = "LiAdRu_mant.php";
$Redirect = "";
$TemplateFileName = "LiAdRu_mant.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-9201469D
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
$liqrubros = new clsRecordliqrubros();
$liqrubros->Initialize();

// Events
include("./LiAdRu_mant_events.php");
BindEvents();

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

//Execute Components @1-450AD429
$Cabecera->Operations();
$liqrubros->Operation();
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

//Show Page @1-106755E7
$Cabecera->Show("Cabecera");
$liqrubros->Show();
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
