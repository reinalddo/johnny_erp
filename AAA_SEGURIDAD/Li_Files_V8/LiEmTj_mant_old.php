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

class clsRecordliqtarjacabec { //liqtarjacabec Class @396-98C796F9

//Variables @396-4A82E0A3

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

//Class_Initialize Event @396-0C0EBDF8
    function clsRecordliqtarjacabec()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record liqtarjacabec/Error";
        $this->ds = new clsliqtarjacabecDataSource();
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "liqtarjacabec";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->lbTitulo = new clsControl(ccsLabel, "lbTitulo", "lbTitulo", ccsText, "", CCGetRequestParam("lbTitulo", $Method));
            $this->tar_NumTarja = new clsControl(ccsTextBox, "tar_NumTarja", "Nùmero de Tarja", ccsInteger, "", CCGetRequestParam("tar_NumTarja", $Method));
            $this->tar_NumTarja->Required = true;
            $this->hdFecFin = new clsControl(ccsHidden, "hdFecFin", "hdFecFin", ccsText, "", CCGetRequestParam("hdFecFin", $Method));
            $this->hdFecIni = new clsControl(ccsHidden, "hdFecIni", "hdFecIni", ccsText, "", CCGetRequestParam("hdFecIni", $Method));
            $this->hdSemIni = new clsControl(ccsHidden, "hdSemIni", "hdSemIni", ccsText, "", CCGetRequestParam("hdSemIni", $Method));
            $this->hdSemFin = new clsControl(ccsHidden, "hdSemFin", "hdSemFin", ccsText, "", CCGetRequestParam("hdSemFin", $Method));
            $this->hdMarca = new clsControl(ccsHidden, "hdMarca", "hdMarca", ccsText, "", CCGetRequestParam("hdMarca", $Method));
            $this->tac_RefOperativa = new clsControl(ccsTextBox, "tac_RefOperativa", "Còdigo de Embarque", ccsInteger, "", CCGetRequestParam("tac_RefOperativa", $Method));
            $this->tac_RefOperativa->Required = true;
            $this->txtEmbarque = new clsControl(ccsTextBox, "txtEmbarque", "txtEmbarque", ccsText, "", CCGetRequestParam("txtEmbarque", $Method));
            $this->txtSemanas = new clsControl(ccsTextBox, "txtSemanas", "txtSemanas", ccsText, "", CCGetRequestParam("txtSemanas", $Method));
            $this->tac_Ano = new clsControl(ccsTextBox, "tac_Ano", "Año de proceso", ccsText, "", CCGetRequestParam("tac_Ano", $Method));
            $this->tac_Semana = new clsControl(ccsTextBox, "tac_Semana", "Semana de Recepción", ccsInteger, "", CCGetRequestParam("tac_Semana", $Method));
            $this->tac_Semana->Required = true;
            $this->tac_Fecha = new clsControl(ccsTextBox, "tac_Fecha", "Fecha de Recepción", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("tac_Fecha", $Method));
            $this->tac_Fecha->Required = true;
            $this->DatePicker_tac_Fecha1 = new clsDatePicker("DatePicker_tac_Fecha1", "liqtarjacabec", "tac_Fecha");
            $this->tac_Hora = new clsControl(ccsTextBox, "tac_Hora", "Hora de Recepción", ccsText, "", CCGetRequestParam("tac_Hora", $Method));
            $this->tac_HoraFin = new clsControl(ccsTextBox, "tac_HoraFin", "tac_HoraFin", ccsText, "", CCGetRequestParam("tac_HoraFin", $Method));
            $this->tac_PueRecepcion = new clsControl(ccsListBox, "tac_PueRecepcion", "Puerto en el que se Recibe", ccsInteger, "", CCGetRequestParam("tac_PueRecepcion", $Method));
            $this->tac_PueRecepcion->DSType = dsTable;
            list($this->tac_PueRecepcion->BoundColumn, $this->tac_PueRecepcion->TextColumn, $this->tac_PueRecepcion->DBFormat) = array("pue_CodPuerto", "pue_Descripcion", "");
            $this->tac_PueRecepcion->ds = new clsDBdatos();
            $this->tac_PueRecepcion->ds->SQL = "SELECT pue_CodPuerto, pue_Descripcion  " .
"FROM genpuertos";
            $this->tac_PueRecepcion->ds->Parameters["expr415"] = 593;
            $this->tac_PueRecepcion->ds->wp = new clsSQLParameters();
            $this->tac_PueRecepcion->ds->wp->AddParameter("1", "expr415", ccsInteger, "", "", $this->tac_PueRecepcion->ds->Parameters["expr415"], "", true);
            $this->tac_PueRecepcion->ds->wp->Criterion[1] = $this->tac_PueRecepcion->ds->wp->Operation(opEqual, "pue_CodPais", $this->tac_PueRecepcion->ds->wp->GetDBValue("1"), $this->tac_PueRecepcion->ds->ToSQL($this->tac_PueRecepcion->ds->wp->GetDBValue("1"), ccsInteger),true);
            $this->tac_PueRecepcion->ds->Where = $this->tac_PueRecepcion->ds->wp->Criterion[1];
            $this->tac_PueRecepcion->Required = true;
            $this->tac_Embarcador = new clsControl(ccsTextBox, "tac_Embarcador", "Còdigo de productor", ccsInteger, "", CCGetRequestParam("tac_Embarcador", $Method));
            $this->tac_Embarcador->Required = true;
            $this->txtEmbarcador = new clsControl(ccsTextBox, "txtEmbarcador", "Nombre de Productor", ccsText, "", CCGetRequestParam("txtEmbarcador", $Method));
            $this->tac_UniProduccion = new clsControl(ccsTextBox, "tac_UniProduccion", "Tac Uni Produccion", ccsInteger, "", CCGetRequestParam("tac_UniProduccion", $Method));
            $this->tac_UniProduccion->Required = true;
            $this->txtUniproduc = new clsControl(ccsTextBox, "txtUniproduc", "txtUniproduc", ccsText, "", CCGetRequestParam("txtUniproduc", $Method));
            $this->tac_CodOrigen = new clsControl(ccsTextBox, "tac_CodOrigen", "Codigo Original del productor.", ccsText, "", CCGetRequestParam("tac_CodOrigen", $Method));
            $this->tac_GrupLiquidacion = new clsControl(ccsTextBox, "tac_GrupLiquidacion", "Grupo de Liquidación", ccsInteger, "", CCGetRequestParam("tac_GrupLiquidacion", $Method));
            $this->tac_GrupLiquidacion->Required = true;
            $this->txtGrupLiquidacion = new clsControl(ccsTextBox, "txtGrupLiquidacion", "Grupo de Liquidacion", ccsText, "", CCGetRequestParam("txtGrupLiquidacion", $Method));
            $this->tac_Zona = new clsControl(ccsListBox, "tac_Zona", "Zona de Corte", ccsText, "", CCGetRequestParam("tac_Zona", $Method));
            $this->tac_Zona->DSType = dsTable;
            list($this->tac_Zona->BoundColumn, $this->tac_Zona->TextColumn, $this->tac_Zona->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->tac_Zona->ds = new clsDBdatos();
            $this->tac_Zona->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->tac_Zona->ds->Parameters["expr427"] = 'LSZON';
            $this->tac_Zona->ds->wp = new clsSQLParameters();
            $this->tac_Zona->ds->wp->AddParameter("1", "expr427", ccsText, "", "", $this->tac_Zona->ds->Parameters["expr427"], "", false);
            $this->tac_Zona->ds->wp->Criterion[1] = $this->tac_Zona->ds->wp->Operation(opEqual, "par_Clave", $this->tac_Zona->ds->wp->GetDBValue("1"), $this->tac_Zona->ds->ToSQL($this->tac_Zona->ds->wp->GetDBValue("1"), ccsText),false);
            $this->tac_Zona->ds->Where = $this->tac_Zona->ds->wp->Criterion[1];
            $this->tac_CodCartonera = new clsControl(ccsListBox, "tac_CodCartonera", "tac_CodCartonera", ccsText, "", CCGetRequestParam("tac_CodCartonera", $Method));
            $this->tac_Transporte = new clsControl(ccsTextBox, "tac_Transporte", "ID. de Transporte, vehìculo", ccsText, "", CCGetRequestParam("tac_Transporte", $Method));
            $this->tac_RefTransp = new clsControl(ccsTextBox, "tac_RefTransp", "Referencia de Transportista", ccsText, "", CCGetRequestParam("tac_RefTransp", $Method));
            $this->tac_Bodega = new clsControl(ccsTextBox, "tac_Bodega", "Bodega del Vapor en que se Embarca", ccsText, "", CCGetRequestParam("tac_Bodega", $Method));
            $this->tac_Piso = new clsControl(ccsTextBox, "tac_Piso", "Piso del Vapor", ccsText, "", CCGetRequestParam("tac_Piso", $Method));
            $this->tac_Contenedor = new clsControl(ccsTextBox, "tac_Contenedor", "tac_Contenedor", ccsText, "", CCGetRequestParam("tac_Contenedor", $Method));
            $this->tac_Sello = new clsControl(ccsTextBox, "tac_Sello", "tac_Sello", ccsText, "", CCGetRequestParam("tac_Sello", $Method));
            $this->tac_ResCalidad = new clsControl(ccsTextBox, "tac_ResCalidad", "Porcentaje de Calidad General", ccsFloat, "", CCGetRequestParam("tac_ResCalidad", $Method));
            $this->tac_ResCalidad->Required = true;
            $this->hdCodProducto = new clsControl(ccsHidden, "hdCodProducto", "hdCodProducto", ccsText, "", CCGetRequestParam("hdCodProducto", $Method));
            $this->hdCodCaja = new clsControl(ccsHidden, "hdCodCaja", "hdCodCaja", ccsText, "", CCGetRequestParam("hdCodCaja", $Method));
            $this->hdCodCompon1 = new clsControl(ccsHidden, "hdCodCompon1", "hdCodCompon1", ccsText, "", CCGetRequestParam("hdCodCompon1", $Method));
            $this->hdCodCompon2 = new clsControl(ccsHidden, "hdCodCompon2", "hdCodCompon2", ccsText, "", CCGetRequestParam("hdCodCompon2", $Method));
            $this->tac_Observaciones = new clsControl(ccsTextBox, "tac_Observaciones", "Observaciones Generales acerca de la recepción", ccsText, "", CCGetRequestParam("tac_Observaciones", $Method));
            $this->tac_Evaluada = new clsControl(ccsCheckBox, "tac_Evaluada", "Indicador de Evaluación Previa", ccsInteger, "", CCGetRequestParam("tac_Evaluada", $Method));
            $this->tac_Evaluada->CheckedValue = $this->tac_Evaluada->GetParsedValue(1);
            $this->tac_Evaluada->UncheckedValue = $this->tac_Evaluada->GetParsedValue(0);
            $this->tac_NumLiquid = new clsControl(ccsTextBox, "tac_NumLiquid", "Nùmero de Liquidación", ccsInteger, "", CCGetRequestParam("tac_NumLiquid", $Method));
            $this->tac_NumLiquid->Required = true;
            $this->hdCodCompon3 = new clsControl(ccsHidden, "hdCodCompon3", "hdCodCompon3", ccsText, "", CCGetRequestParam("hdCodCompon3", $Method));
            $this->hdCodCompon4 = new clsControl(ccsHidden, "hdCodCompon4", "hdCodCompon4", ccsText, "", CCGetRequestParam("hdCodCompon4", $Method));
            $this->hdPrecOficial = new clsControl(ccsHidden, "hdPrecOficial", "hdPrecOficial", ccsText, "", CCGetRequestParam("hdPrecOficial", $Method));
            $this->hdDifPrecio = new clsControl(ccsHidden, "hdDifPrecio", "hdDifPrecio", ccsText, "", CCGetRequestParam("hdDifPrecio", $Method));
            $this->tac_Estado = new clsControl(ccsListBox, "tac_Estado", "Estado de proceso", ccsMemo, "", CCGetRequestParam("tac_Estado", $Method));
            $this->tac_Estado->DSType = dsTable;
            list($this->tac_Estado->BoundColumn, $this->tac_Estado->TextColumn, $this->tac_Estado->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->tac_Estado->ds = new clsDBdatos();
            $this->tac_Estado->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->tac_Estado->ds->Parameters["expr448"] = 'LGESTA';
            $this->tac_Estado->ds->wp = new clsSQLParameters();
            $this->tac_Estado->ds->wp->AddParameter("1", "expr448", ccsText, "", "", $this->tac_Estado->ds->Parameters["expr448"], "", false);
            $this->tac_Estado->ds->wp->Criterion[1] = $this->tac_Estado->ds->wp->Operation(opEqual, "par_Clave", $this->tac_Estado->ds->wp->GetDBValue("1"), $this->tac_Estado->ds->ToSQL($this->tac_Estado->ds->wp->GetDBValue("1"), ccsText),false);
            $this->tac_Estado->ds->Where = $this->tac_Estado->ds->wp->Criterion[1];
            $this->txtEmpaque = new clsControl(ccsHidden, "txtEmpaque", "Descripcion de Empaque", ccsText, "", CCGetRequestParam("txtEmpaque", $Method));
            $this->tac_CodEmpaque = new clsControl(ccsHidden, "tac_CodEmpaque", "Codigo de Empaque", ccsInteger, "", CCGetRequestParam("tac_CodEmpaque", $Method));
            $this->tac_CodEmpaque->Required = true;
            $this->tac_CodEvaluador = new clsControl(ccsTextBox, "tac_CodEvaluador", "Còdigo de Evaluador", ccsText, "", CCGetRequestParam("tac_CodEvaluador", $Method));
            $this->hdEstado = new clsControl(ccsHidden, "hdEstado", "hdEstado", ccsText, "", CCGetRequestParam("hdEstado", $Method));
            $this->hdCodPuerto = new clsControl(ccsHidden, "hdCodPuerto", "hdCodPuerto", ccsText, "", CCGetRequestParam("hdCodPuerto", $Method));
            $this->hdAnoOperacion = new clsControl(ccsHidden, "hdAnoOperacion", "hdAnoOperacion", ccsText, "", CCGetRequestParam("hdAnoOperacion", $Method));
            $this->tac_Usuario = new clsControl(ccsTextBox, "tac_Usuario", "Tac Usuario", ccsText, "", CCGetRequestParam("tac_Usuario", $Method));
            $this->tac_FecDigitacion = new clsControl(ccsTextBox, "tac_FecDigitacion", "Fecha de  Digitación", ccsDate, Array("dd", "/", "mmm", "/", "yy", " ", "H", ":", "nn"), CCGetRequestParam("tac_FecDigitacion", $Method));
            $this->tac_FecDigitacion->Required = true;
            $this->Button_Insert = new clsButton("Button_Insert");
            $this->Button_Update = new clsButton("Button_Update");
            $this->Button_Delete = new clsButton("Button_Delete");
            $this->Button_Cancel = new clsButton("Button_Cancel");
            $this->btNuevo = new clsButton("btNuevo");
            if(!$this->FormSubmitted) {
                if(!is_array($this->tac_Fecha->Value) && !strlen($this->tac_Fecha->Value) && $this->tac_Fecha->Value !== false)
                $this->tac_Fecha->SetValue(time());
                if(!is_array($this->tac_PueRecepcion->Value) && !strlen($this->tac_PueRecepcion->Value) && $this->tac_PueRecepcion->Value !== false)
                $this->tac_PueRecepcion->SetText(0);
                if(!is_array($this->tac_UniProduccion->Value) && !strlen($this->tac_UniProduccion->Value) && $this->tac_UniProduccion->Value !== false)
                $this->tac_UniProduccion->SetText(0);
                if(!is_array($this->tac_CodOrigen->Value) && !strlen($this->tac_CodOrigen->Value) && $this->tac_CodOrigen->Value !== false)
                $this->tac_CodOrigen->SetText(" ");
                if(!is_array($this->tac_GrupLiquidacion->Value) && !strlen($this->tac_GrupLiquidacion->Value) && $this->tac_GrupLiquidacion->Value !== false)
                $this->tac_GrupLiquidacion->SetText(0);
                if(!is_array($this->txtGrupLiquidacion->Value) && !strlen($this->txtGrupLiquidacion->Value) && $this->txtGrupLiquidacion->Value !== false)
                $this->txtGrupLiquidacion->SetText('');
                if(!is_array($this->tac_Zona->Value) && !strlen($this->tac_Zona->Value) && $this->tac_Zona->Value !== false)
                $this->tac_Zona->SetText('N.D.');
                if(!is_array($this->tac_Transporte->Value) && !strlen($this->tac_Transporte->Value) && $this->tac_Transporte->Value !== false)
                $this->tac_Transporte->SetText('');
                if(!is_array($this->tac_RefTransp->Value) && !strlen($this->tac_RefTransp->Value) && $this->tac_RefTransp->Value !== false)
                $this->tac_RefTransp->SetText('');
                if(!is_array($this->tac_Bodega->Value) && !strlen($this->tac_Bodega->Value) && $this->tac_Bodega->Value !== false)
                $this->tac_Bodega->SetText('');
                if(!is_array($this->tac_Piso->Value) && !strlen($this->tac_Piso->Value) && $this->tac_Piso->Value !== false)
                $this->tac_Piso->SetText('');
                if(!is_array($this->tac_Contenedor->Value) && !strlen($this->tac_Contenedor->Value) && $this->tac_Contenedor->Value !== false)
                $this->tac_Contenedor->SetText('');
                if(!is_array($this->tac_Sello->Value) && !strlen($this->tac_Sello->Value) && $this->tac_Sello->Value !== false)
                $this->tac_Sello->SetText('');
                if(!is_array($this->tac_ResCalidad->Value) && !strlen($this->tac_ResCalidad->Value) && $this->tac_ResCalidad->Value !== false)
                $this->tac_ResCalidad->SetText(0);
                if(!is_array($this->tac_Observaciones->Value) && !strlen($this->tac_Observaciones->Value) && $this->tac_Observaciones->Value !== false)
                $this->tac_Observaciones->SetText(' ');
                if(!is_array($this->tac_Evaluada->Value) && !strlen($this->tac_Evaluada->Value) && $this->tac_Evaluada->Value !== false)
                $this->tac_Evaluada->SetText(0);
                if(!is_array($this->tac_NumLiquid->Value) && !strlen($this->tac_NumLiquid->Value) && $this->tac_NumLiquid->Value !== false)
                $this->tac_NumLiquid->SetText(0);
                if(!is_array($this->tac_Estado->Value) && !strlen($this->tac_Estado->Value) && $this->tac_Estado->Value !== false)
                $this->tac_Estado->SetText(1);
                if(!is_array($this->txtEmpaque->Value) && !strlen($this->txtEmpaque->Value) && $this->txtEmpaque->Value !== false)
                $this->txtEmpaque->SetText(" ");
                if(!is_array($this->tac_CodEmpaque->Value) && !strlen($this->tac_CodEmpaque->Value) && $this->tac_CodEmpaque->Value !== false)
                $this->tac_CodEmpaque->SetText(0);
                if(!is_array($this->tac_CodEvaluador->Value) && !strlen($this->tac_CodEvaluador->Value) && $this->tac_CodEvaluador->Value !== false)
                $this->tac_CodEvaluador->SetText(' ');
                if(!is_array($this->tac_FecDigitacion->Value) && !strlen($this->tac_FecDigitacion->Value) && $this->tac_FecDigitacion->Value !== false)
                $this->tac_FecDigitacion->SetValue(time());
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @396-0EAEB856
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->ds->Parameters["urltar_NumTarja"] = CCGetFromGet("tar_NumTarja", "");
        $this->ds->Parameters["expr467"] = 'IMARCA';
    }
//End Initialize Method

//Validate Method @396-DD7B1DE4
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->tar_NumTarja->Validate() && $Validation);
        $Validation = ($this->hdFecFin->Validate() && $Validation);
        $Validation = ($this->hdFecIni->Validate() && $Validation);
        $Validation = ($this->hdSemIni->Validate() && $Validation);
        $Validation = ($this->hdSemFin->Validate() && $Validation);
        $Validation = ($this->hdMarca->Validate() && $Validation);
        $Validation = ($this->tac_RefOperativa->Validate() && $Validation);
        $Validation = ($this->txtEmbarque->Validate() && $Validation);
        $Validation = ($this->txtSemanas->Validate() && $Validation);
        $Validation = ($this->tac_Ano->Validate() && $Validation);
        $Validation = ($this->tac_Semana->Validate() && $Validation);
        $Validation = ($this->tac_Fecha->Validate() && $Validation);
        $Validation = ($this->tac_Hora->Validate() && $Validation);
        $Validation = ($this->tac_HoraFin->Validate() && $Validation);
        $Validation = ($this->tac_PueRecepcion->Validate() && $Validation);
        $Validation = ($this->tac_Embarcador->Validate() && $Validation);
        $Validation = ($this->txtEmbarcador->Validate() && $Validation);
        $Validation = ($this->tac_UniProduccion->Validate() && $Validation);
        $Validation = ($this->txtUniproduc->Validate() && $Validation);
        $Validation = ($this->tac_CodOrigen->Validate() && $Validation);
        $Validation = ($this->tac_GrupLiquidacion->Validate() && $Validation);
        $Validation = ($this->txtGrupLiquidacion->Validate() && $Validation);
        $Validation = ($this->tac_Zona->Validate() && $Validation);
        $Validation = ($this->tac_CodCartonera->Validate() && $Validation);
        $Validation = ($this->tac_Transporte->Validate() && $Validation);
        $Validation = ($this->tac_RefTransp->Validate() && $Validation);
        $Validation = ($this->tac_Bodega->Validate() && $Validation);
        $Validation = ($this->tac_Piso->Validate() && $Validation);
        $Validation = ($this->tac_Contenedor->Validate() && $Validation);
        $Validation = ($this->tac_Sello->Validate() && $Validation);
        $Validation = ($this->tac_ResCalidad->Validate() && $Validation);
        $Validation = ($this->hdCodProducto->Validate() && $Validation);
        $Validation = ($this->hdCodCaja->Validate() && $Validation);
        $Validation = ($this->hdCodCompon1->Validate() && $Validation);
        $Validation = ($this->hdCodCompon2->Validate() && $Validation);
        $Validation = ($this->tac_Observaciones->Validate() && $Validation);
        $Validation = ($this->tac_Evaluada->Validate() && $Validation);
        $Validation = ($this->tac_NumLiquid->Validate() && $Validation);
        $Validation = ($this->hdCodCompon3->Validate() && $Validation);
        $Validation = ($this->hdCodCompon4->Validate() && $Validation);
        $Validation = ($this->hdPrecOficial->Validate() && $Validation);
        $Validation = ($this->hdDifPrecio->Validate() && $Validation);
        $Validation = ($this->tac_Estado->Validate() && $Validation);
        $Validation = ($this->txtEmpaque->Validate() && $Validation);
        $Validation = ($this->tac_CodEmpaque->Validate() && $Validation);
        $Validation = ($this->tac_CodEvaluador->Validate() && $Validation);
        $Validation = ($this->hdEstado->Validate() && $Validation);
        $Validation = ($this->hdCodPuerto->Validate() && $Validation);
        $Validation = ($this->hdAnoOperacion->Validate() && $Validation);
        $Validation = ($this->tac_Usuario->Validate() && $Validation);
        $Validation = ($this->tac_FecDigitacion->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @396-793715C4
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->lbTitulo->Errors->Count());
        $errors = ($errors || $this->tar_NumTarja->Errors->Count());
        $errors = ($errors || $this->hdFecFin->Errors->Count());
        $errors = ($errors || $this->hdFecIni->Errors->Count());
        $errors = ($errors || $this->hdSemIni->Errors->Count());
        $errors = ($errors || $this->hdSemFin->Errors->Count());
        $errors = ($errors || $this->hdMarca->Errors->Count());
        $errors = ($errors || $this->tac_RefOperativa->Errors->Count());
        $errors = ($errors || $this->txtEmbarque->Errors->Count());
        $errors = ($errors || $this->txtSemanas->Errors->Count());
        $errors = ($errors || $this->tac_Ano->Errors->Count());
        $errors = ($errors || $this->tac_Semana->Errors->Count());
        $errors = ($errors || $this->tac_Fecha->Errors->Count());
        $errors = ($errors || $this->DatePicker_tac_Fecha1->Errors->Count());
        $errors = ($errors || $this->tac_Hora->Errors->Count());
        $errors = ($errors || $this->tac_HoraFin->Errors->Count());
        $errors = ($errors || $this->tac_PueRecepcion->Errors->Count());
        $errors = ($errors || $this->tac_Embarcador->Errors->Count());
        $errors = ($errors || $this->txtEmbarcador->Errors->Count());
        $errors = ($errors || $this->tac_UniProduccion->Errors->Count());
        $errors = ($errors || $this->txtUniproduc->Errors->Count());
        $errors = ($errors || $this->tac_CodOrigen->Errors->Count());
        $errors = ($errors || $this->tac_GrupLiquidacion->Errors->Count());
        $errors = ($errors || $this->txtGrupLiquidacion->Errors->Count());
        $errors = ($errors || $this->tac_Zona->Errors->Count());
        $errors = ($errors || $this->tac_CodCartonera->Errors->Count());
        $errors = ($errors || $this->tac_Transporte->Errors->Count());
        $errors = ($errors || $this->tac_RefTransp->Errors->Count());
        $errors = ($errors || $this->tac_Bodega->Errors->Count());
        $errors = ($errors || $this->tac_Piso->Errors->Count());
        $errors = ($errors || $this->tac_Contenedor->Errors->Count());
        $errors = ($errors || $this->tac_Sello->Errors->Count());
        $errors = ($errors || $this->tac_ResCalidad->Errors->Count());
        $errors = ($errors || $this->hdCodProducto->Errors->Count());
        $errors = ($errors || $this->hdCodCaja->Errors->Count());
        $errors = ($errors || $this->hdCodCompon1->Errors->Count());
        $errors = ($errors || $this->hdCodCompon2->Errors->Count());
        $errors = ($errors || $this->tac_Observaciones->Errors->Count());
        $errors = ($errors || $this->tac_Evaluada->Errors->Count());
        $errors = ($errors || $this->tac_NumLiquid->Errors->Count());
        $errors = ($errors || $this->hdCodCompon3->Errors->Count());
        $errors = ($errors || $this->hdCodCompon4->Errors->Count());
        $errors = ($errors || $this->hdPrecOficial->Errors->Count());
        $errors = ($errors || $this->hdDifPrecio->Errors->Count());
        $errors = ($errors || $this->tac_Estado->Errors->Count());
        $errors = ($errors || $this->txtEmpaque->Errors->Count());
        $errors = ($errors || $this->tac_CodEmpaque->Errors->Count());
        $errors = ($errors || $this->tac_CodEvaluador->Errors->Count());
        $errors = ($errors || $this->hdEstado->Errors->Count());
        $errors = ($errors || $this->hdCodPuerto->Errors->Count());
        $errors = ($errors || $this->hdAnoOperacion->Errors->Count());
        $errors = ($errors || $this->tac_Usuario->Errors->Count());
        $errors = ($errors || $this->tac_FecDigitacion->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @396-D32F172A
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
            } else if(strlen(CCGetParam("btNuevo", ""))) {
                $this->PressedButton = "btNuevo";
            }
        }
        $Redirect = ServerURL . "Li_Files/" . $FileName . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "Button_Delete") {
            if(!CCGetEvent($this->Button_Delete->CCSEvents, "OnClick") || !$this->DeleteRow()) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "Button_Cancel") {
            if(!CCGetEvent($this->Button_Cancel->CCSEvents, "OnClick")) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "btNuevo") {
            if(!CCGetEvent($this->btNuevo->CCSEvents, "OnClick")) {
                $Redirect = "";
            } else {
                $Redirect = "LiEmTj_mant.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm", "tar_NumTarja"));
            }
        } else if($this->Validate()) {
            if($this->PressedButton == "Button_Insert") {
                if(!CCGetEvent($this->Button_Insert->CCSEvents, "OnClick") || !$this->InsertRow()) {
                    $Redirect = "";
                } else {
                    $Redirect = "LiEmTj_mant.php". "?" . CCGetQueryString("QueryString", Array("ccsForm"));
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

//InsertRow Method @396-1A880322
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert");
        if(!$this->InsertAllowed) return false;
        $this->ds->tar_NumTarja->SetValue($this->tar_NumTarja->GetValue());
        $this->ds->tac_RefOperativa->SetValue($this->tac_RefOperativa->GetValue());
        $this->ds->tac_Semana->SetValue($this->tac_Semana->GetValue());
        $this->ds->tac_Fecha->SetValue($this->tac_Fecha->GetValue());
        $this->ds->tac_Hora->SetValue($this->tac_Hora->GetValue());
        $this->ds->tac_PueRecepcion->SetValue($this->tac_PueRecepcion->GetValue());
        $this->ds->tac_Embarcador->SetValue($this->tac_Embarcador->GetValue());
        $this->ds->tac_UniProduccion->SetValue($this->tac_UniProduccion->GetValue());
        $this->ds->tac_Zona->SetValue($this->tac_Zona->GetValue());
        $this->ds->tac_Transporte->SetValue($this->tac_Transporte->GetValue());
        $this->ds->tac_RefTransp->SetValue($this->tac_RefTransp->GetValue());
        $this->ds->tac_Bodega->SetValue($this->tac_Bodega->GetValue());
        $this->ds->tac_Piso->SetValue($this->tac_Piso->GetValue());
        $this->ds->tac_CodEmpaque->SetValue($this->tac_CodEmpaque->GetValue());
        $this->ds->tac_ResCalidad->SetValue($this->tac_ResCalidad->GetValue());
        $this->ds->tac_Observaciones->SetValue($this->tac_Observaciones->GetValue());
        $this->ds->tac_GrupLiquidacion->SetValue($this->tac_GrupLiquidacion->GetValue());
        $this->ds->tac_NumLiquid->SetValue($this->tac_NumLiquid->GetValue());
        $this->ds->tac_Evaluada->SetValue($this->tac_Evaluada->GetValue());
        $this->ds->tac_Estado->SetValue($this->tac_Estado->GetValue());
        $this->ds->tac_CodEvaluador->SetValue($this->tac_CodEvaluador->GetValue());
        $this->ds->tac_Usuario->SetValue($this->tac_Usuario->GetValue());
        $this->ds->tac_FecDigitacion->SetValue($this->tac_FecDigitacion->GetValue());
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

//UpdateRow Method @396-B54928C4
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate");
        if(!$this->UpdateAllowed) return false;
        $this->ds->tac_RefOperativa->SetValue($this->tac_RefOperativa->GetValue());
        $this->ds->tac_Semana->SetValue($this->tac_Semana->GetValue());
        $this->ds->tac_Fecha->SetValue($this->tac_Fecha->GetValue());
        $this->ds->tac_Hora->SetValue($this->tac_Hora->GetValue());
        $this->ds->tac_PueRecepcion->SetValue($this->tac_PueRecepcion->GetValue());
        $this->ds->tac_Embarcador->SetValue($this->tac_Embarcador->GetValue());
        $this->ds->tac_UniProduccion->SetValue($this->tac_UniProduccion->GetValue());
        $this->ds->tac_Zona->SetValue($this->tac_Zona->GetValue());
        $this->ds->tac_Transporte->SetValue($this->tac_Transporte->GetValue());
        $this->ds->tac_RefTransp->SetValue($this->tac_RefTransp->GetValue());
        $this->ds->tac_Bodega->SetValue($this->tac_Bodega->GetValue());
        $this->ds->tac_Piso->SetValue($this->tac_Piso->GetValue());
        $this->ds->tac_CodEmpaque->SetValue($this->tac_CodEmpaque->GetValue());
        $this->ds->tac_ResCalidad->SetValue($this->tac_ResCalidad->GetValue());
        $this->ds->tac_Observaciones->SetValue($this->tac_Observaciones->GetValue());
        $this->ds->tac_GrupLiquidacion->SetValue($this->tac_GrupLiquidacion->GetValue());
        $this->ds->tac_NumLiquid->SetValue($this->tac_NumLiquid->GetValue());
        $this->ds->tac_Evaluada->SetValue($this->tac_Evaluada->GetValue());
        $this->ds->tac_Estado->SetValue($this->tac_Estado->GetValue());
        $this->ds->tac_CodEvaluador->SetValue($this->tac_CodEvaluador->GetValue());
        $this->ds->tac_Usuario->SetValue($this->tac_Usuario->GetValue());
        $this->ds->tac_FecDigitacion->SetValue($this->tac_FecDigitacion->GetValue());
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

//DeleteRow Method @396-EA88835F
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

//Show Method @396-9D4761E0
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->tac_PueRecepcion->Prepare();
        $this->tac_Zona->Prepare();
        $this->tac_CodCartonera->Prepare();
        $this->tac_Estado->Prepare();

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
                    echo "Error in Record liqtarjacabec";
                }
                else if($this->ds->next_record())
                {
                    $this->ds->SetValues();
                    if(!$this->FormSubmitted)
                    {
                        $this->tar_NumTarja->SetValue($this->ds->tar_NumTarja->GetValue());
                        $this->hdFecFin->SetValue($this->ds->hdFecFin->GetValue());
                        $this->hdFecIni->SetValue($this->ds->hdFecIni->GetValue());
                        $this->hdSemIni->SetValue($this->ds->hdSemIni->GetValue());
                        $this->hdSemFin->SetValue($this->ds->hdSemFin->GetValue());
                        $this->hdMarca->SetValue($this->ds->hdMarca->GetValue());
                        $this->tac_RefOperativa->SetValue($this->ds->tac_RefOperativa->GetValue());
                        $this->txtEmbarque->SetValue($this->ds->txtEmbarque->GetValue());
                        $this->tac_Semana->SetValue($this->ds->tac_Semana->GetValue());
                        $this->tac_Fecha->SetValue($this->ds->tac_Fecha->GetValue());
                        $this->tac_Hora->SetValue($this->ds->tac_Hora->GetValue());
                        $this->tac_HoraFin->SetValue($this->ds->tac_HoraFin->GetValue());
                        $this->tac_PueRecepcion->SetValue($this->ds->tac_PueRecepcion->GetValue());
                        $this->tac_Embarcador->SetValue($this->ds->tac_Embarcador->GetValue());
                        $this->txtEmbarcador->SetValue($this->ds->txtEmbarcador->GetValue());
                        $this->tac_UniProduccion->SetValue($this->ds->tac_UniProduccion->GetValue());
                        $this->tac_CodOrigen->SetValue($this->ds->tac_CodOrigen->GetValue());
                        $this->tac_GrupLiquidacion->SetValue($this->ds->tac_GrupLiquidacion->GetValue());
                        $this->tac_Zona->SetValue($this->ds->tac_Zona->GetValue());
                        $this->tac_CodCartonera->SetValue($this->ds->tac_CodCartonera->GetValue());
                        $this->tac_Transporte->SetValue($this->ds->tac_Transporte->GetValue());
                        $this->tac_RefTransp->SetValue($this->ds->tac_RefTransp->GetValue());
                        $this->tac_Bodega->SetValue($this->ds->tac_Bodega->GetValue());
                        $this->tac_Piso->SetValue($this->ds->tac_Piso->GetValue());
                        $this->tac_Contenedor->SetValue($this->ds->tac_Contenedor->GetValue());
                        $this->tac_Sello->SetValue($this->ds->tac_Sello->GetValue());
                        $this->tac_ResCalidad->SetValue($this->ds->tac_ResCalidad->GetValue());
                        $this->hdCodProducto->SetValue($this->ds->hdCodProducto->GetValue());
                        $this->hdCodCaja->SetValue($this->ds->hdCodCaja->GetValue());
                        $this->hdCodCompon1->SetValue($this->ds->hdCodCompon1->GetValue());
                        $this->hdCodCompon2->SetValue($this->ds->hdCodCompon2->GetValue());
                        $this->tac_Observaciones->SetValue($this->ds->tac_Observaciones->GetValue());
                        $this->tac_Evaluada->SetValue($this->ds->tac_Evaluada->GetValue());
                        $this->tac_NumLiquid->SetValue($this->ds->tac_NumLiquid->GetValue());
                        $this->hdCodCompon3->SetValue($this->ds->hdCodCompon3->GetValue());
                        $this->hdCodCompon4->SetValue($this->ds->hdCodCompon4->GetValue());
                        $this->hdPrecOficial->SetValue($this->ds->hdPrecOficial->GetValue());
                        $this->hdDifPrecio->SetValue($this->ds->hdDifPrecio->GetValue());
                        $this->tac_Estado->SetValue($this->ds->tac_Estado->GetValue());
                        $this->tac_CodEmpaque->SetValue($this->ds->tac_CodEmpaque->GetValue());
                        $this->tac_CodEvaluador->SetValue($this->ds->tac_CodEvaluador->GetValue());
                        $this->hdEstado->SetValue($this->ds->hdEstado->GetValue());
                        $this->hdCodPuerto->SetValue($this->ds->hdCodPuerto->GetValue());
                        $this->hdAnoOperacion->SetValue($this->ds->hdAnoOperacion->GetValue());
                        $this->tac_Usuario->SetValue($this->ds->tac_Usuario->GetValue());
                        $this->tac_FecDigitacion->SetValue($this->ds->tac_FecDigitacion->GetValue());
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
            $Error .= $this->tar_NumTarja->Errors->ToString();
            $Error .= $this->hdFecFin->Errors->ToString();
            $Error .= $this->hdFecIni->Errors->ToString();
            $Error .= $this->hdSemIni->Errors->ToString();
            $Error .= $this->hdSemFin->Errors->ToString();
            $Error .= $this->hdMarca->Errors->ToString();
            $Error .= $this->tac_RefOperativa->Errors->ToString();
            $Error .= $this->txtEmbarque->Errors->ToString();
            $Error .= $this->txtSemanas->Errors->ToString();
            $Error .= $this->tac_Ano->Errors->ToString();
            $Error .= $this->tac_Semana->Errors->ToString();
            $Error .= $this->tac_Fecha->Errors->ToString();
            $Error .= $this->DatePicker_tac_Fecha1->Errors->ToString();
            $Error .= $this->tac_Hora->Errors->ToString();
            $Error .= $this->tac_HoraFin->Errors->ToString();
            $Error .= $this->tac_PueRecepcion->Errors->ToString();
            $Error .= $this->tac_Embarcador->Errors->ToString();
            $Error .= $this->txtEmbarcador->Errors->ToString();
            $Error .= $this->tac_UniProduccion->Errors->ToString();
            $Error .= $this->txtUniproduc->Errors->ToString();
            $Error .= $this->tac_CodOrigen->Errors->ToString();
            $Error .= $this->tac_GrupLiquidacion->Errors->ToString();
            $Error .= $this->txtGrupLiquidacion->Errors->ToString();
            $Error .= $this->tac_Zona->Errors->ToString();
            $Error .= $this->tac_CodCartonera->Errors->ToString();
            $Error .= $this->tac_Transporte->Errors->ToString();
            $Error .= $this->tac_RefTransp->Errors->ToString();
            $Error .= $this->tac_Bodega->Errors->ToString();
            $Error .= $this->tac_Piso->Errors->ToString();
            $Error .= $this->tac_Contenedor->Errors->ToString();
            $Error .= $this->tac_Sello->Errors->ToString();
            $Error .= $this->tac_ResCalidad->Errors->ToString();
            $Error .= $this->hdCodProducto->Errors->ToString();
            $Error .= $this->hdCodCaja->Errors->ToString();
            $Error .= $this->hdCodCompon1->Errors->ToString();
            $Error .= $this->hdCodCompon2->Errors->ToString();
            $Error .= $this->tac_Observaciones->Errors->ToString();
            $Error .= $this->tac_Evaluada->Errors->ToString();
            $Error .= $this->tac_NumLiquid->Errors->ToString();
            $Error .= $this->hdCodCompon3->Errors->ToString();
            $Error .= $this->hdCodCompon4->Errors->ToString();
            $Error .= $this->hdPrecOficial->Errors->ToString();
            $Error .= $this->hdDifPrecio->Errors->ToString();
            $Error .= $this->tac_Estado->Errors->ToString();
            $Error .= $this->txtEmpaque->Errors->ToString();
            $Error .= $this->tac_CodEmpaque->Errors->ToString();
            $Error .= $this->tac_CodEvaluador->Errors->ToString();
            $Error .= $this->hdEstado->Errors->ToString();
            $Error .= $this->hdCodPuerto->Errors->ToString();
            $Error .= $this->hdAnoOperacion->Errors->ToString();
            $Error .= $this->tac_Usuario->Errors->ToString();
            $Error .= $this->tac_FecDigitacion->Errors->ToString();
            $Error .= $this->Errors->ToString();
            $Error .= $this->ds->Errors->ToString();
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $CCSForm = $this->EditMode ? $this->ComponentName . ":" . "Edit" : $this->ComponentName;
        if($this->FormSubmitted || CCGetFromGet("ccsForm")) {
            $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        } else {
            $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("All", ""), "ccsForm", $CCSForm);
        }
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
        //----------  fah bloqueo : Modificacion para Habilitar / deshabilitar Botones Segun perfil del usuario
        $aOpc = array();
        global $DBdatos;
        global $Tpl;
        global $ilEstado;
        if ($this->InsertAllowed) $aOpc[]="ADD";
        if ($this->UpdateAllowed) $aOpc[]="UPD";
        if ($this->DeleteAllowed) $aOpc[]="DEL";
        $slMensj="";
        if ($this->EditMode) {
            $ilEstado = CCGetDBValue("SELECT per_Estado FROM liqtarjacabec JOIN conperiodos ON per_Aplicacion = 'LI' AND per_numperiodo = tac_semana WHERE tar_NumTarja =" . CCGetParam('tar_NumTarja', -1), $DBdatos);
            if ($ilEstado) $ilNumPro = CCGetDBValue("SELECT MAX(tad_LiqProceso) FROM liqtarjadetal WHERE tad_NumTarja =" . CCGetParam('tar_NumTarja', -1) , $DBdatos);
            if ($ilNumPro >0) {
                $ilEstado = -1;
                $slMensj = " (Tarja Liquidada o en proceso)";
            }
            else $slMensj = " (Periodo cerrado)";
        }
        else $ilEstado = 1;
        fHabilitaBotonesCCS(false, $aOpc, $ilEstado );
        if ($ilEstado < 1 ) $Tpl->SetVar('lbEstado', ' **Solo para Consulta ' . $slMensj);
        //---------- end fah bloqueo
        $this->lbTitulo->Show();
        $this->tar_NumTarja->Show();
        $this->hdFecFin->Show();
        $this->hdFecIni->Show();
        $this->hdSemIni->Show();
        $this->hdSemFin->Show();
        $this->hdMarca->Show();
        $this->tac_RefOperativa->Show();
        $this->txtEmbarque->Show();
        $this->txtSemanas->Show();
        $this->tac_Ano->Show();
        $this->tac_Semana->Show();
        $this->tac_Fecha->Show();
        $this->DatePicker_tac_Fecha1->Show();
        $this->tac_Hora->Show();
        $this->tac_HoraFin->Show();
        $this->tac_PueRecepcion->Show();
        $this->tac_Embarcador->Show();
        $this->txtEmbarcador->Show();
        $this->tac_UniProduccion->Show();
        $this->txtUniproduc->Show();
        $this->tac_CodOrigen->Show();
        $this->tac_GrupLiquidacion->Show();
        $this->txtGrupLiquidacion->Show();
        $this->tac_Zona->Show();
        $this->tac_CodCartonera->Show();
        $this->tac_Transporte->Show();
        $this->tac_RefTransp->Show();
        $this->tac_Bodega->Show();
        $this->tac_Piso->Show();
        $this->tac_Contenedor->Show();
        $this->tac_Sello->Show();
        $this->tac_ResCalidad->Show();
        $this->hdCodProducto->Show();
        $this->hdCodCaja->Show();
        $this->hdCodCompon1->Show();
        $this->hdCodCompon2->Show();
        $this->tac_Observaciones->Show();
        $this->tac_Evaluada->Show();
        $this->tac_NumLiquid->Show();
        $this->hdCodCompon3->Show();
        $this->hdCodCompon4->Show();
        $this->hdPrecOficial->Show();
        $this->hdDifPrecio->Show();
        $this->tac_Estado->Show();
        $this->txtEmpaque->Show();
        $this->tac_CodEmpaque->Show();
        $this->tac_CodEvaluador->Show();
        $this->hdEstado->Show();
        $this->hdCodPuerto->Show();
        $this->hdAnoOperacion->Show();
        $this->tac_Usuario->Show();
        $this->tac_FecDigitacion->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->Button_Cancel->Show();
        $this->btNuevo->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

} //End liqtarjacabec Class @396-FCB6E20C

class clsliqtarjacabecDataSource extends clsDBdatos {  //liqtarjacabecDataSource Class @396-FBEFFAF6

//DataSource Variables @396-F6850B7F
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
    var $tar_NumTarja;
    var $hdFecFin;
    var $hdFecIni;
    var $hdSemIni;
    var $hdSemFin;
    var $hdMarca;
    var $tac_RefOperativa;
    var $txtEmbarque;
    var $txtSemanas;
    var $tac_Ano;
    var $tac_Semana;
    var $tac_Fecha;
    var $tac_Hora;
    var $tac_HoraFin;
    var $tac_PueRecepcion;
    var $tac_Embarcador;
    var $txtEmbarcador;
    var $tac_UniProduccion;
    var $txtUniproduc;
    var $tac_CodOrigen;
    var $tac_GrupLiquidacion;
    var $txtGrupLiquidacion;
    var $tac_Zona;
    var $tac_CodCartonera;
    var $tac_Transporte;
    var $tac_RefTransp;
    var $tac_Bodega;
    var $tac_Piso;
    var $tac_Contenedor;
    var $tac_Sello;
    var $tac_ResCalidad;
    var $hdCodProducto;
    var $hdCodCaja;
    var $hdCodCompon1;
    var $hdCodCompon2;
    var $tac_Observaciones;
    var $tac_Evaluada;
    var $tac_NumLiquid;
    var $hdCodCompon3;
    var $hdCodCompon4;
    var $hdPrecOficial;
    var $hdDifPrecio;
    var $tac_Estado;
    var $txtEmpaque;
    var $tac_CodEmpaque;
    var $tac_CodEvaluador;
    var $hdEstado;
    var $hdCodPuerto;
    var $hdAnoOperacion;
    var $tac_Usuario;
    var $tac_FecDigitacion;
//End DataSource Variables

//Class_Initialize Event @396-1F5B3FCF
    function clsliqtarjacabecDataSource()
    {
        $this->ErrorBlock = "Record liqtarjacabec/Error";
        $this->Initialize();
        $this->lbTitulo = new clsField("lbTitulo", ccsText, "");
        $this->tar_NumTarja = new clsField("tar_NumTarja", ccsInteger, "");
        $this->hdFecFin = new clsField("hdFecFin", ccsText, "");
        $this->hdFecIni = new clsField("hdFecIni", ccsText, "");
        $this->hdSemIni = new clsField("hdSemIni", ccsText, "");
        $this->hdSemFin = new clsField("hdSemFin", ccsText, "");
        $this->hdMarca = new clsField("hdMarca", ccsText, "");
        $this->tac_RefOperativa = new clsField("tac_RefOperativa", ccsInteger, "");
        $this->txtEmbarque = new clsField("txtEmbarque", ccsText, "");
        $this->txtSemanas = new clsField("txtSemanas", ccsText, "");
        $this->tac_Ano = new clsField("tac_Ano", ccsText, "");
        $this->tac_Semana = new clsField("tac_Semana", ccsInteger, "");
        $this->tac_Fecha = new clsField("tac_Fecha", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss", "GMT"));
        $this->tac_Hora = new clsField("tac_Hora", ccsText, "");
        $this->tac_HoraFin = new clsField("tac_HoraFin", ccsText, "");
        $this->tac_PueRecepcion = new clsField("tac_PueRecepcion", ccsInteger, "");
        $this->tac_Embarcador = new clsField("tac_Embarcador", ccsInteger, "");
        $this->txtEmbarcador = new clsField("txtEmbarcador", ccsText, "");
        $this->tac_UniProduccion = new clsField("tac_UniProduccion", ccsInteger, "");
        $this->txtUniproduc = new clsField("txtUniproduc", ccsText, "");
        $this->tac_CodOrigen = new clsField("tac_CodOrigen", ccsText, "");
        $this->tac_GrupLiquidacion = new clsField("tac_GrupLiquidacion", ccsInteger, "");
        $this->txtGrupLiquidacion = new clsField("txtGrupLiquidacion", ccsText, "");
        $this->tac_Zona = new clsField("tac_Zona", ccsText, "");
        $this->tac_CodCartonera = new clsField("tac_CodCartonera", ccsText, "");
        $this->tac_Transporte = new clsField("tac_Transporte", ccsText, "");
        $this->tac_RefTransp = new clsField("tac_RefTransp", ccsText, "");
        $this->tac_Bodega = new clsField("tac_Bodega", ccsText, "");
        $this->tac_Piso = new clsField("tac_Piso", ccsText, "");
        $this->tac_Contenedor = new clsField("tac_Contenedor", ccsText, "");
        $this->tac_Sello = new clsField("tac_Sello", ccsText, "");
        $this->tac_ResCalidad = new clsField("tac_ResCalidad", ccsFloat, "");
        $this->hdCodProducto = new clsField("hdCodProducto", ccsText, "");
        $this->hdCodCaja = new clsField("hdCodCaja", ccsText, "");
        $this->hdCodCompon1 = new clsField("hdCodCompon1", ccsText, "");
        $this->hdCodCompon2 = new clsField("hdCodCompon2", ccsText, "");
        $this->tac_Observaciones = new clsField("tac_Observaciones", ccsText, "");
        $this->tac_Evaluada = new clsField("tac_Evaluada", ccsInteger, "");
        $this->tac_NumLiquid = new clsField("tac_NumLiquid", ccsInteger, "");
        $this->hdCodCompon3 = new clsField("hdCodCompon3", ccsText, "");
        $this->hdCodCompon4 = new clsField("hdCodCompon4", ccsText, "");
        $this->hdPrecOficial = new clsField("hdPrecOficial", ccsText, "");
        $this->hdDifPrecio = new clsField("hdDifPrecio", ccsText, "");
        $this->tac_Estado = new clsField("tac_Estado", ccsMemo, "");
        $this->txtEmpaque = new clsField("txtEmpaque", ccsText, "");
        $this->tac_CodEmpaque = new clsField("tac_CodEmpaque", ccsInteger, "");
        $this->tac_CodEvaluador = new clsField("tac_CodEvaluador", ccsText, "");
        $this->hdEstado = new clsField("hdEstado", ccsText, "");
        $this->hdCodPuerto = new clsField("hdCodPuerto", ccsText, "");
        $this->hdAnoOperacion = new clsField("hdAnoOperacion", ccsText, "");
        $this->tac_Usuario = new clsField("tac_Usuario", ccsText, "");
        $this->tac_FecDigitacion = new clsField("tac_FecDigitacion", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss", "GMT"));

    }
//End Class_Initialize Event

//Prepare Method @396-81906791
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urltar_NumTarja", ccsInteger, "", "", $this->Parameters["urltar_NumTarja"], "", false);
//        $this->wp->AddParameter("2", "expr467", ccsText, "", "", $this->Parameters["expr467"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "tar_NumTarja", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
//        $this->wp->Criterion[2] = $this->wp->Operation(opEqual, "par_Clave", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsText),false);
//        $this->Where = $this->wp->opAND(false, $this->wp->Criterion[1], $this->wp->Criterion[2]);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @396-844708F7
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->SQL = "SELECT liqtarjacabec.*, emb_AnoOperacion, emb_CodVapor, emb_NumViaje, emb_CodProducto, emb_CodMarca, emb_CodCaja, emb_CodCompon1, " .
        "emb_CodCompon2, emb_CodCompon3, emb_CodCompon4, emb_PrecOficial, emb_DifPrecio, emb_FecInicio, emb_SemInicio, emb_FecTermino, " .
        "emb_SemTermino, emb_Estado, emb_CodPuerto, par_Descripcion, buq_Abreviatura, conpersonas.*, concat(buq_Abreviatura,' - ',emb_NumViaje, '   (Sem ', emb_SemInicio,' a ', emb_SemTermino, ', entre  ', date_format(emb_fecInicio,'%d/%b/%y'), ' y ', date_format(emb_fectermino,'%d/%b/%y'), ')') AS txtEmbarque, concat(per_Apellidos,' ',per_Nombres) AS txtEmbarcador  " .
        "FROM liqembarques JOIN liqtarjacabec ON  liqtarjacabec.tac_RefOperativa = liqembarques.emb_RefOperativa " .
              "LEFT JOIN genparametros ON par_clave='IMARCA' AND liqembarques.emb_CodMarca = genparametros.par_Secuencia " .
              "LEFT JOIN liqbuques ON liqembarques.emb_CodVapor = liqbuques.buq_CodBuque " .
              "LEFT JOIN conpersonas ON liqtarjacabec.tac_Embarcador = conpersonas.per_CodAuxiliar";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
        
    }
//End Open Method

//SetValues Method @396-21C336E0
    function SetValues()
    {
        $this->tar_NumTarja->SetDBValue(trim($this->f("tar_NumTarja")));
        $this->hdFecFin->SetDBValue($this->f("emb_FecTermino"));
        $this->hdFecIni->SetDBValue($this->f("emb_FecInicio"));
        $this->hdSemIni->SetDBValue($this->f("emb_SemInicio"));
        $this->hdSemFin->SetDBValue($this->f("emb_SemTermino"));
        $this->hdMarca->SetDBValue($this->f("emb_CodMarca"));
        $this->tac_RefOperativa->SetDBValue(trim($this->f("tac_RefOperativa")));
        $this->txtEmbarque->SetDBValue($this->f("txtEmbarque"));
        $this->tac_Semana->SetDBValue(trim($this->f("tac_Semana")));
        $this->tac_Fecha->SetDBValue(trim($this->f("tac_Fecha")));
        $this->tac_Hora->SetDBValue($this->f("tac_Hora"));
        $this->tac_HoraFin->SetDBValue($this->f("tac_HoraFin"));
        $this->tac_PueRecepcion->SetDBValue(trim($this->f("tac_PueRecepcion")));
        $this->tac_Embarcador->SetDBValue(trim($this->f("tac_Embarcador")));
        $this->txtEmbarcador->SetDBValue($this->f("txtEmbarcador"));
        $this->tac_UniProduccion->SetDBValue(trim($this->f("tac_UniProduccion")));
        $this->tac_CodOrigen->SetDBValue($this->f("tac_CodOrigen"));
        $this->tac_GrupLiquidacion->SetDBValue(trim($this->f("tac_GrupLiquidacion")));
        $this->tac_Zona->SetDBValue($this->f("tac_Zona"));
        $this->tac_CodCartonera->SetDBValue($this->f("tac_CodCartonera"));
        $this->tac_Transporte->SetDBValue($this->f("tac_Transporte"));
        $this->tac_RefTransp->SetDBValue($this->f("tac_RefTranspor"));
        $this->tac_Bodega->SetDBValue($this->f("tac_Bodega"));
        $this->tac_Piso->SetDBValue($this->f("tac_Piso"));
        $this->tac_Contenedor->SetDBValue($this->f("tac_Contenedor"));
        $this->tac_Sello->SetDBValue($this->f("tac_Sello"));
        $this->tac_ResCalidad->SetDBValue(trim($this->f("tac_ResCalidad")));
        $this->hdCodProducto->SetDBValue($this->f("emb_CodProducto"));
        $this->hdCodCaja->SetDBValue($this->f("emb_CodCaja"));
        $this->hdCodCompon1->SetDBValue($this->f("emb_CodCompon1"));
        $this->hdCodCompon2->SetDBValue($this->f("emb_CodCompon2"));
        $this->tac_Observaciones->SetDBValue($this->f("tac_Observaciones"));
        $this->tac_Evaluada->SetDBValue(trim($this->f("tac_Evaluada")));
        $this->tac_NumLiquid->SetDBValue(trim($this->f("tac_NumLiquid")));
        $this->hdCodCompon3->SetDBValue($this->f("emb_CodCompon3"));
        $this->hdCodCompon4->SetDBValue($this->f("emb_CodCompon4"));
        $this->hdPrecOficial->SetDBValue($this->f("emb_PrecOficial"));
        $this->hdDifPrecio->SetDBValue($this->f("emb_DifPrecio"));
        $this->tac_Estado->SetDBValue($this->f("tac_Estado"));
        $this->tac_CodEmpaque->SetDBValue(trim($this->f("tac_CodEmpaque")));
        $this->tac_CodEvaluador->SetDBValue($this->f("tac_CodEvaluador"));
        $this->hdEstado->SetDBValue($this->f("emb_Estado"));
        $this->hdCodPuerto->SetDBValue($this->f("emb_CodPuerto"));
        $this->hdAnoOperacion->SetDBValue($this->f("emb_AnoOperacion"));
        $this->tac_Usuario->SetDBValue($this->f("tac_Usuario"));
        $this->tac_FecDigitacion->SetDBValue(trim($this->f("tac_FecDigitacion")));
    }
//End SetValues Method

//Insert Method @396-9D721663
    function Insert()
    {
        $this->cp["tar_NumTarja"] = new clsSQLParameter("ctrltar_NumTarja", ccsInteger, "", "", $this->tar_NumTarja->GetValue(), -9999, false, $this->ErrorBlock);
        $this->cp["tac_RefOperativa"] = new clsSQLParameter("ctrltac_RefOperativa", ccsInteger, "", "", $this->tac_RefOperativa->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["tac_Semana"] = new clsSQLParameter("ctrltac_Semana", ccsInteger, "", "", $this->tac_Semana->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["tac_Fecha"] = new clsSQLParameter("ctrltac_Fecha", ccsDate, Array("dd", "/", "mm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->tac_Fecha->GetValue(), "01/01/00", false, $this->ErrorBlock);
        $this->cp["tac_Hora"] = new clsSQLParameter("ctrltac_Hora", ccsText, "", "", $this->tac_Hora->GetValue(), '00:00', false, $this->ErrorBlock);
        $this->cp["tac_PueRecepcion"] = new clsSQLParameter("ctrltac_PueRecepcion", ccsInteger, "", "", $this->tac_PueRecepcion->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["tac_Embarcador"] = new clsSQLParameter("ctrltac_Embarcador", ccsInteger, "", "", $this->tac_Embarcador->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["tac_UniProduccion"] = new clsSQLParameter("ctrltac_UniProduccion", ccsInteger, "", "", $this->tac_UniProduccion->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["tac_Zona"] = new clsSQLParameter("ctrltac_Zona", ccsInteger, "", "", $this->tac_Zona->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["tac_Transporte"] = new clsSQLParameter("ctrltac_Transporte", ccsText, "", "", $this->tac_Transporte->GetValue(), '', false, $this->ErrorBlock);
        $this->cp["tac_RefTranspor"] = new clsSQLParameter("ctrltac_RefTransp", ccsText, "", "", $this->tac_RefTransp->GetValue(), '', false, $this->ErrorBlock);
        $this->cp["tac_Bodega"] = new clsSQLParameter("ctrltac_Bodega", ccsText, "", "", $this->tac_Bodega->GetValue(), '', false, $this->ErrorBlock);
        $this->cp["tac_Piso"] = new clsSQLParameter("ctrltac_Piso", ccsText, "", "", $this->tac_Piso->GetValue(), '', false, $this->ErrorBlock);
        $this->cp["tac_CodEmpaque"] = new clsSQLParameter("ctrltac_CodEmpaque", ccsInteger, "", "", $this->tac_CodEmpaque->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["tac_ResCalidad"] = new clsSQLParameter("ctrltac_ResCalidad", ccsFloat, "", "", $this->tac_ResCalidad->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["tac_Observaciones"] = new clsSQLParameter("ctrltac_Observaciones", ccsText, "", "", $this->tac_Observaciones->GetValue(), '', false, $this->ErrorBlock);
        $this->cp["tac_GrupLiquidacion"] = new clsSQLParameter("ctrltac_GrupLiquidacion", ccsInteger, "", "", $this->tac_GrupLiquidacion->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["tac_NumLiquid"] = new clsSQLParameter("ctrltac_NumLiquid", ccsInteger, "", "", $this->tac_NumLiquid->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["tac_Evaluada"] = new clsSQLParameter("ctrltac_Evaluada", ccsInteger, "", "", $this->tac_Evaluada->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["tac_Estado"] = new clsSQLParameter("ctrltac_Estado", ccsMemo, "", "", $this->tac_Estado->GetValue(), 1, false, $this->ErrorBlock);
        $this->cp["tac_CodEvaluador"] = new clsSQLParameter("ctrltac_CodEvaluador", ccsText, "", "", $this->tac_CodEvaluador->GetValue(), '', false, $this->ErrorBlock);
        $this->cp["tac_Usuario"] = new clsSQLParameter("ctrltac_Usuario", ccsText, "", "", $this->tac_Usuario->GetValue(), '', false, $this->ErrorBlock);
        $this->cp["tac_FecDigitacion"] = new clsSQLParameter("ctrltac_FecDigitacion", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->tac_FecDigitacion->GetValue(), "", false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO liqtarjacabec ("
             . "tar_NumTarja, "
             . "tac_RefOperativa, "
             . "tac_Semana, "
             . "tac_Fecha, "
             . "tac_Hora, "
             . "tac_PueRecepcion, "
             . "tac_Embarcador, "
             . "tac_UniProduccion, "
             . "tac_Zona, "
             . "tac_Transporte, "
             . "tac_RefTranspor, "
             . "tac_Bodega, "
             . "tac_Piso, "
             . "tac_CodEmpaque, "
             . "tac_ResCalidad, "
             . "tac_Observaciones, "
             . "tac_GrupLiquidacion, "
             . "tac_NumLiquid, "
             . "tac_Evaluada, "
             . "tac_Estado, "
             . "tac_CodEvaluador, "
             . "tac_Usuario, "
             . "tac_FecDigitacion"
             . ") VALUES ("
             . $this->ToSQL($this->cp["tar_NumTarja"]->GetDBValue(), $this->cp["tar_NumTarja"]->DataType) . ", "
             . $this->ToSQL($this->cp["tac_RefOperativa"]->GetDBValue(), $this->cp["tac_RefOperativa"]->DataType) . ", "
             . $this->ToSQL($this->cp["tac_Semana"]->GetDBValue(), $this->cp["tac_Semana"]->DataType) . ", "
             . $this->ToSQL($this->cp["tac_Fecha"]->GetDBValue(), $this->cp["tac_Fecha"]->DataType) . ", "
             . $this->ToSQL($this->cp["tac_Hora"]->GetDBValue(), $this->cp["tac_Hora"]->DataType) . ", "
             . $this->ToSQL($this->cp["tac_PueRecepcion"]->GetDBValue(), $this->cp["tac_PueRecepcion"]->DataType) . ", "
             . $this->ToSQL($this->cp["tac_Embarcador"]->GetDBValue(), $this->cp["tac_Embarcador"]->DataType) . ", "
             . $this->ToSQL($this->cp["tac_UniProduccion"]->GetDBValue(), $this->cp["tac_UniProduccion"]->DataType) . ", "
             . $this->ToSQL($this->cp["tac_Zona"]->GetDBValue(), $this->cp["tac_Zona"]->DataType) . ", "
             . $this->ToSQL($this->cp["tac_Transporte"]->GetDBValue(), $this->cp["tac_Transporte"]->DataType) . ", "
             . $this->ToSQL($this->cp["tac_RefTranspor"]->GetDBValue(), $this->cp["tac_RefTranspor"]->DataType) . ", "
             . $this->ToSQL($this->cp["tac_Bodega"]->GetDBValue(), $this->cp["tac_Bodega"]->DataType) . ", "
             . $this->ToSQL($this->cp["tac_Piso"]->GetDBValue(), $this->cp["tac_Piso"]->DataType) . ", "
             . $this->ToSQL($this->cp["tac_CodEmpaque"]->GetDBValue(), $this->cp["tac_CodEmpaque"]->DataType) . ", "
             . $this->ToSQL($this->cp["tac_ResCalidad"]->GetDBValue(), $this->cp["tac_ResCalidad"]->DataType) . ", "
             . $this->ToSQL($this->cp["tac_Observaciones"]->GetDBValue(), $this->cp["tac_Observaciones"]->DataType) . ", "
             . $this->ToSQL($this->cp["tac_GrupLiquidacion"]->GetDBValue(), $this->cp["tac_GrupLiquidacion"]->DataType) . ", "
             . $this->ToSQL($this->cp["tac_NumLiquid"]->GetDBValue(), $this->cp["tac_NumLiquid"]->DataType) . ", "
             . $this->ToSQL($this->cp["tac_Evaluada"]->GetDBValue(), $this->cp["tac_Evaluada"]->DataType) . ", "
             . $this->ToSQL($this->cp["tac_Estado"]->GetDBValue(), $this->cp["tac_Estado"]->DataType) . ", "
             . $this->ToSQL($this->cp["tac_CodEvaluador"]->GetDBValue(), $this->cp["tac_CodEvaluador"]->DataType) . ", "
             . $this->ToSQL($this->cp["tac_Usuario"]->GetDBValue(), $this->cp["tac_Usuario"]->DataType) . ", "
             . $this->ToSQL($this->cp["tac_FecDigitacion"]->GetDBValue(), $this->cp["tac_FecDigitacion"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Insert Method

//Update Method @396-57FE737A
    function Update()
    {
        $this->cp["tac_RefOperativa"] = new clsSQLParameter("ctrltac_RefOperativa", ccsInteger, "", "", $this->tac_RefOperativa->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tac_Semana"] = new clsSQLParameter("ctrltac_Semana", ccsInteger, "", "", $this->tac_Semana->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tac_Fecha"] = new clsSQLParameter("ctrltac_Fecha", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->tac_Fecha->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tac_Hora"] = new clsSQLParameter("ctrltac_Hora", ccsText, "", "", $this->tac_Hora->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tac_PueRecepcion"] = new clsSQLParameter("ctrltac_PueRecepcion", ccsInteger, "", "", $this->tac_PueRecepcion->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tac_Embarcador"] = new clsSQLParameter("ctrltac_Embarcador", ccsInteger, "", "", $this->tac_Embarcador->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tac_UniProduccion"] = new clsSQLParameter("ctrltac_UniProduccion", ccsInteger, "", "", $this->tac_UniProduccion->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tac_Zona"] = new clsSQLParameter("ctrltac_Zona", ccsText, "", "", $this->tac_Zona->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tac_Transporte"] = new clsSQLParameter("ctrltac_Transporte", ccsText, "", "", $this->tac_Transporte->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tac_RefTranspor"] = new clsSQLParameter("ctrltac_RefTransp", ccsText, "", "", $this->tac_RefTransp->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tac_Bodega"] = new clsSQLParameter("ctrltac_Bodega", ccsText, "", "", $this->tac_Bodega->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tac_Piso"] = new clsSQLParameter("ctrltac_Piso", ccsText, "", "", $this->tac_Piso->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tac_CodEmpaque"] = new clsSQLParameter("ctrltac_CodEmpaque", ccsInteger, "", "", $this->tac_CodEmpaque->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tac_ResCalidad"] = new clsSQLParameter("ctrltac_ResCalidad", ccsFloat, "", "", $this->tac_ResCalidad->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tac_Observaciones"] = new clsSQLParameter("ctrltac_Observaciones", ccsText, "", "", $this->tac_Observaciones->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tac_GrupLiquidacion"] = new clsSQLParameter("ctrltac_GrupLiquidacion", ccsInteger, "", "", $this->tac_GrupLiquidacion->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tac_NumLiquid"] = new clsSQLParameter("ctrltac_NumLiquid", ccsInteger, "", "", $this->tac_NumLiquid->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tac_Evaluada"] = new clsSQLParameter("ctrltac_Evaluada", ccsInteger, "", "", $this->tac_Evaluada->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tac_Estado"] = new clsSQLParameter("ctrltac_Estado", ccsMemo, "", "", $this->tac_Estado->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tac_CodEvaluador"] = new clsSQLParameter("ctrltac_CodEvaluador", ccsInteger, "", "", $this->tac_CodEvaluador->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tac_Usuario"] = new clsSQLParameter("ctrltac_Usuario", ccsText, "", "", $this->tac_Usuario->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tac_FecDigitacion"] = new clsSQLParameter("ctrltac_FecDigitacion", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->tac_FecDigitacion->GetValue(), "", false, $this->ErrorBlock);
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urltar_NumTarja", ccsInteger, "", "", CCGetFromGet("tar_NumTarja", ""), "", true);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $wp->Criterion[1] = $wp->Operation(opEqual, "tar_NumTarja", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),true);
        $Where = $wp->Criterion[1];
        $this->SQL = "UPDATE liqtarjacabec SET "
             . "tac_RefOperativa=" . $this->ToSQL($this->cp["tac_RefOperativa"]->GetDBValue(), $this->cp["tac_RefOperativa"]->DataType) . ", "
             . "tac_Semana=" . $this->ToSQL($this->cp["tac_Semana"]->GetDBValue(), $this->cp["tac_Semana"]->DataType) . ", "
             . "tac_Fecha=" . $this->ToSQL($this->cp["tac_Fecha"]->GetDBValue(), $this->cp["tac_Fecha"]->DataType) . ", "
             . "tac_Hora=" . $this->ToSQL($this->cp["tac_Hora"]->GetDBValue(), $this->cp["tac_Hora"]->DataType) . ", "
             . "tac_PueRecepcion=" . $this->ToSQL($this->cp["tac_PueRecepcion"]->GetDBValue(), $this->cp["tac_PueRecepcion"]->DataType) . ", "
             . "tac_Embarcador=" . $this->ToSQL($this->cp["tac_Embarcador"]->GetDBValue(), $this->cp["tac_Embarcador"]->DataType) . ", "
             . "tac_UniProduccion=" . $this->ToSQL($this->cp["tac_UniProduccion"]->GetDBValue(), $this->cp["tac_UniProduccion"]->DataType) . ", "
             . "tac_Zona=" . $this->ToSQL($this->cp["tac_Zona"]->GetDBValue(), $this->cp["tac_Zona"]->DataType) . ", "
             . "tac_Transporte=" . $this->ToSQL($this->cp["tac_Transporte"]->GetDBValue(), $this->cp["tac_Transporte"]->DataType) . ", "
             . "tac_RefTranspor=" . $this->ToSQL($this->cp["tac_RefTranspor"]->GetDBValue(), $this->cp["tac_RefTranspor"]->DataType) . ", "
             . "tac_Bodega=" . $this->ToSQL($this->cp["tac_Bodega"]->GetDBValue(), $this->cp["tac_Bodega"]->DataType) . ", "
             . "tac_Piso=" . $this->ToSQL($this->cp["tac_Piso"]->GetDBValue(), $this->cp["tac_Piso"]->DataType) . ", "
             . "tac_CodEmpaque=" . $this->ToSQL($this->cp["tac_CodEmpaque"]->GetDBValue(), $this->cp["tac_CodEmpaque"]->DataType) . ", "
             . "tac_ResCalidad=" . $this->ToSQL($this->cp["tac_ResCalidad"]->GetDBValue(), $this->cp["tac_ResCalidad"]->DataType) . ", "
             . "tac_Observaciones=" . $this->ToSQL($this->cp["tac_Observaciones"]->GetDBValue(), $this->cp["tac_Observaciones"]->DataType) . ", "
             . "tac_GrupLiquidacion=" . $this->ToSQL($this->cp["tac_GrupLiquidacion"]->GetDBValue(), $this->cp["tac_GrupLiquidacion"]->DataType) . ", "
             . "tac_NumLiquid=" . $this->ToSQL($this->cp["tac_NumLiquid"]->GetDBValue(), $this->cp["tac_NumLiquid"]->DataType) . ", "
             . "tac_Evaluada=" . $this->ToSQL($this->cp["tac_Evaluada"]->GetDBValue(), $this->cp["tac_Evaluada"]->DataType) . ", "
             . "tac_Estado=" . $this->ToSQL($this->cp["tac_Estado"]->GetDBValue(), $this->cp["tac_Estado"]->DataType) . ", "
             . "tac_CodEvaluador=" . $this->ToSQL($this->cp["tac_CodEvaluador"]->GetDBValue(), $this->cp["tac_CodEvaluador"]->DataType) . ", "
             . "tac_Usuario=" . $this->ToSQL($this->cp["tac_Usuario"]->GetDBValue(), $this->cp["tac_Usuario"]->DataType) . ", "
             . "tac_FecDigitacion=" . $this->ToSQL($this->cp["tac_FecDigitacion"]->GetDBValue(), $this->cp["tac_FecDigitacion"]->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Update Method

//Delete Method @396-7DFA1CD3
    function Delete()
    {
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urltar_NumTarja", ccsInteger, "", "", CCGetFromGet("tar_NumTarja", ""), "", true);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $wp->Criterion[1] = $wp->Operation(opEqual, "tar_NumTarja", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),true);
        $Where = $wp->Criterion[1];
        $this->SQL = "DELETE FROM liqtarjacabec";
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Delete Method

} //End liqtarjacabecDataSource Class @396-FCB6E20C

class clsEditableGridliqtarjadetal { //liqtarjadetal Class @292-248811E7

//Variables @292-18AE6B04

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
    var $Sorter_tad_Secuencia;
    var $Sorter_tad_CodCaja;
    var $Sorter_tad_CodProducto;
    var $Navigator1;
//End Variables

//Class_Initialize Event @292-17498BFA
    function clsEditableGridliqtarjadetal()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "EditableGrid liqtarjadetal/Error";
        $this->ComponentName = "liqtarjadetal";
        $this->CachedColumns["tad_Secuencia"][0] = "tad_Secuencia";
        $this->ds = new clsliqtarjadetalDataSource();

        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize) || $this->PageSize > 4)
            $this->PageSize = 4;
        else
            $this->PageSize = intval($this->PageSize);
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: EditableGrid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));

        $this->EmptyRows = 4;
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

        $this->SorterName = CCGetParam("liqtarjadetalOrder", "");
        $this->SorterDirection = CCGetParam("liqtarjadetalDir", "");
        $this->Sorter_tad_Secuencia = new clsSorter($this->ComponentName, "Sorter_tad_Secuencia", $FileName);
        $this->Sorter_tad_CodCaja = new clsSorter($this->ComponentName, "Sorter_tad_CodCaja", $FileName);
        $this->Sorter_tad_CodProducto = new clsSorter($this->ComponentName, "Sorter_tad_CodProducto", $FileName);
        $this->tad_Secuencia = new clsControl(ccsTextBox, "tad_Secuencia", "Tad Secuencia", ccsInteger, Array(True, 0, "", "", False, Array("0", "0"), "", 1, True, ""));
        $this->tad_NumTarja = new clsControl(ccsHidden, "tad_NumTarja", "Tad Num Tarja", ccsInteger, "");
        $this->tad_CodCaja = new clsControl(ccsTextBox, "tad_CodCaja", "Código de Caja (Empaque)", ccsInteger, "");
        $this->txt_Empaque = new clsControl(ccsTextBox, "txt_Empaque", "txt_Empaque", ccsText, "");
        $this->tad_Marca = new clsControl(ccsTextBox, "tad_Marca", "Còdigo de Marca", ccsInteger, "");
        $this->txt_Marca = new clsControl(ccsTextBox, "txt_Marca", "txt_Marca", ccsText, "");
        $this->tad_CodProducto = new clsControl(ccsListBox, "tad_CodProducto", "Código de Producto", ccsInteger, "");
        $this->tad_CodProducto->Required = true;
        $this->tad_Marca->Required = true;
        $this->tad_CodCaja->Required = true;
        $this->tad_CodProducto->DSType = dsTable;
        list($this->tad_CodProducto->BoundColumn, $this->tad_CodProducto->TextColumn, $this->tad_CodProducto->DBFormat) = array("", "", "");
        $this->tad_CodProducto->ds = new clsDBdatos();
        $this->tad_CodProducto->ds->SQL = "SELECT act_CodAuxiliar, act_Descripcion, cat_Activo  " .
"FROM conactivos LEFT JOIN concategorias ON " .
"conactivos.act_CodAuxiliar = concategorias.cat_CodAuxiliar";
        $this->tad_CodProducto->ds->Parameters["expr299"] = 16;
        $this->tad_CodProducto->ds->Parameters["expr300"] = 0;
        $this->tad_CodProducto->ds->wp = new clsSQLParameters();
        $this->tad_CodProducto->ds->wp->AddParameter("1", "expr299", ccsInteger, "", "", $this->tad_CodProducto->ds->Parameters["expr299"], "", false);
        $this->tad_CodProducto->ds->wp->AddParameter("2", "expr300", ccsInteger, "", "", $this->tad_CodProducto->ds->Parameters["expr300"], "", false);
        $this->tad_CodProducto->ds->wp->Criterion[1] = $this->tad_CodProducto->ds->wp->Operation(opEqual, "cat_Categoria", $this->tad_CodProducto->ds->wp->GetDBValue("1"), $this->tad_CodProducto->ds->ToSQL($this->tad_CodProducto->ds->wp->GetDBValue("1"), ccsInteger),false);
        $this->tad_CodProducto->ds->wp->Criterion[2] = $this->tad_CodProducto->ds->wp->Operation(opGreaterThan, "cat_Activo", $this->tad_CodProducto->ds->wp->GetDBValue("2"), $this->tad_CodProducto->ds->ToSQL($this->tad_CodProducto->ds->wp->GetDBValue("2"), ccsInteger),false);
        $this->tad_CodProducto->ds->Where = $this->tad_CodProducto->ds->wp->opAND(false, $this->tad_CodProducto->ds->wp->Criterion[1], $this->tad_CodProducto->ds->wp->Criterion[2]);
        $this->tad_CantDespachada = new clsControl(ccsTextBox, "tad_CantDespachada", "Cant Despachada", ccsInteger, "");
        $this->tad_CantRecibida = new clsControl(ccsTextBox, "tad_CantRecibida", "Cant Recibida", ccsInteger, "");
        $this->txt_Faltante = new clsControl(ccsTextBox, "txt_Faltante", "txt_Faltante", ccsFloat, "");
        $this->tad_CantRechazada = new clsControl(ccsTextBox, "tad_CantRechazada", "Rechazada", ccsInteger, "");
        $this->txt_Embarcado = new clsControl(ccsTextBox, "txt_Embarcado", "Cantidad Embarcada", ccsFloat, Array(True, 1, ",", "", False, Array("0"), Array("#"), 1, True, ""));
        $this->tad_ValUnitario = new clsControl(ccsTextBox, "tad_ValUnitario", "Precio Oficial Unitario", ccsFloat, "");
        $this->tad_DifUnitario = new clsControl(ccsTextBox, "tad_DifUnitario", "Diferencia de precio Unitaria", ccsFloat, "");
        $this->tad_CodCompon1 = new clsControl(ccsTextBox, "tad_CodCompon1", "Còdigo de Componente Cartón", ccsInteger, "");
        $this->txt_Compon1 = new clsControl(ccsTextBox, "txt_Compon1", "txt_Compon1", ccsText, "");
        $this->tad_CodCompon2 = new clsControl(ccsTextBox, "tad_CodCompon2", "Código de Plàstico", ccsInteger, "");
        $this->txt_Compon2 = new clsControl(ccsTextBox, "txt_Compon2", "txt_Compon2", ccsText, "");
        $this->tad_CodCompon3 = new clsControl(ccsTextBox, "tad_CodCompon3", "Código de Materiales", ccsInteger, "");
        $this->txt_Compon3 = new clsControl(ccsTextBox, "txt_Compon3", "txt_Compon3", ccsText, "");
        $this->tad_CodCompon4 = new clsControl(ccsTextBox, "tad_CodCompon4", "Código de Etiqueta", ccsInteger, "");
        $this->txt_Compon4 = new clsControl(ccsTextBox, "txt_Compon4", "txt_Compon4", ccsText, "");
        $this->tad_Observaciones = new clsControl(ccsTextBox, "tad_Observaciones", "Tad Observaciones", ccsText, "");
        $this->CheckBox_Delete = new clsControl(ccsCheckBox, "CheckBox_Delete", "CheckBox_Delete", ccsBoolean, Array("True", "False", ""));
        $this->CheckBox_Delete->CheckedValue = $this->CheckBox_Delete->GetParsedValue(true);
        $this->CheckBox_Delete->UncheckedValue = $this->CheckBox_Delete->GetParsedValue(false);
        $this->Navigator1 = new clsNavigator($this->ComponentName, "Navigator1", $FileName, 10, tpCentered);
        $this->Button_Submit = new clsButton("Button_Submit");
        $this->Cancel = new clsButton("Cancel");
    }
//End Class_Initialize Event

//Initialize Method @292-119D28EB
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);

        $this->ds->Parameters["urltar_NumTarja"] = CCGetFromGet("tar_NumTarja", "");
        $this->ds->Parameters["expr388"] = 'IMARCA';
    }
//End Initialize Method

//GetFormParameters Method @292-222A5F74
    function GetFormParameters()
    {
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->FormParameters["tad_Secuencia"][$RowNumber] = CCGetFromPost("tad_Secuencia_" . $RowNumber);
            $this->FormParameters["tad_NumTarja"][$RowNumber] = CCGetFromPost("tad_NumTarja_" . $RowNumber);
            $this->FormParameters["tad_CodCaja"][$RowNumber] = CCGetFromPost("tad_CodCaja_" . $RowNumber);
            $this->FormParameters["txt_Empaque"][$RowNumber] = CCGetFromPost("txt_Empaque_" . $RowNumber);
            $this->FormParameters["tad_Marca"][$RowNumber] = CCGetFromPost("tad_Marca_" . $RowNumber);
            $this->FormParameters["txt_Marca"][$RowNumber] = CCGetFromPost("txt_Marca_" . $RowNumber);
            $this->FormParameters["tad_CodProducto"][$RowNumber] = CCGetFromPost("tad_CodProducto_" . $RowNumber);
            $this->FormParameters["tad_CantDespachada"][$RowNumber] = CCGetFromPost("tad_CantDespachada_" . $RowNumber);
            $this->FormParameters["tad_CantRecibida"][$RowNumber] = CCGetFromPost("tad_CantRecibida_" . $RowNumber);
            $this->FormParameters["txt_Faltante"][$RowNumber] = CCGetFromPost("txt_Faltante_" . $RowNumber);
            $this->FormParameters["tad_CantRechazada"][$RowNumber] = CCGetFromPost("tad_CantRechazada_" . $RowNumber);
            $this->FormParameters["txt_Embarcado"][$RowNumber] = CCGetFromPost("txt_Embarcado_" . $RowNumber);
            $this->FormParameters["tad_ValUnitario"][$RowNumber] = CCGetFromPost("tad_ValUnitario_" . $RowNumber);
            $this->FormParameters["tad_DifUnitario"][$RowNumber] = CCGetFromPost("tad_DifUnitario_" . $RowNumber);
            $this->FormParameters["tad_CodCompon1"][$RowNumber] = CCGetFromPost("tad_CodCompon1_" . $RowNumber);
            $this->FormParameters["txt_Compon1"][$RowNumber] = CCGetFromPost("txt_Compon1_" . $RowNumber);
            $this->FormParameters["tad_CodCompon2"][$RowNumber] = CCGetFromPost("tad_CodCompon2_" . $RowNumber);
            $this->FormParameters["txt_Compon2"][$RowNumber] = CCGetFromPost("txt_Compon2_" . $RowNumber);
            $this->FormParameters["tad_CodCompon3"][$RowNumber] = CCGetFromPost("tad_CodCompon3_" . $RowNumber);
            $this->FormParameters["txt_Compon3"][$RowNumber] = CCGetFromPost("txt_Compon3_" . $RowNumber);
            $this->FormParameters["tad_CodCompon4"][$RowNumber] = CCGetFromPost("tad_CodCompon4_" . $RowNumber);
            $this->FormParameters["txt_Compon4"][$RowNumber] = CCGetFromPost("txt_Compon4_" . $RowNumber);
            $this->FormParameters["tad_Observaciones"][$RowNumber] = CCGetFromPost("tad_Observaciones_" . $RowNumber);
            $this->FormParameters["CheckBox_Delete"][$RowNumber] = CCGetFromPost("CheckBox_Delete_" . $RowNumber);
        }
    }
//End GetFormParameters Method

//Validate Method @292-C78EF90D
    function Validate()
    {
        $Validation = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");

        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["tad_Secuencia"] = $this->CachedColumns["tad_Secuencia"][$RowNumber];
            $this->tad_Secuencia->SetText($this->FormParameters["tad_Secuencia"][$RowNumber], $RowNumber);
            $this->tad_NumTarja->SetText($this->FormParameters["tad_NumTarja"][$RowNumber], $RowNumber);
            $this->tad_CodCaja->SetText($this->FormParameters["tad_CodCaja"][$RowNumber], $RowNumber);
            $this->txt_Empaque->SetText($this->FormParameters["txt_Empaque"][$RowNumber], $RowNumber);
            $this->tad_Marca->SetText($this->FormParameters["tad_Marca"][$RowNumber], $RowNumber);
            $this->txt_Marca->SetText($this->FormParameters["txt_Marca"][$RowNumber], $RowNumber);
            $this->tad_CodProducto->SetText($this->FormParameters["tad_CodProducto"][$RowNumber], $RowNumber);
            $this->tad_CantDespachada->SetText($this->FormParameters["tad_CantDespachada"][$RowNumber], $RowNumber);
            $this->tad_CantRecibida->SetText($this->FormParameters["tad_CantRecibida"][$RowNumber], $RowNumber);
            $this->txt_Faltante->SetText($this->FormParameters["txt_Faltante"][$RowNumber], $RowNumber);
            $this->tad_CantRechazada->SetText($this->FormParameters["tad_CantRechazada"][$RowNumber], $RowNumber);
            $this->txt_Embarcado->SetText($this->FormParameters["txt_Embarcado"][$RowNumber], $RowNumber);
            $this->tad_ValUnitario->SetText($this->FormParameters["tad_ValUnitario"][$RowNumber], $RowNumber);
            $this->tad_DifUnitario->SetText($this->FormParameters["tad_DifUnitario"][$RowNumber], $RowNumber);
            $this->tad_CodCompon1->SetText($this->FormParameters["tad_CodCompon1"][$RowNumber], $RowNumber);
            $this->txt_Compon1->SetText($this->FormParameters["txt_Compon1"][$RowNumber], $RowNumber);
            $this->tad_CodCompon2->SetText($this->FormParameters["tad_CodCompon2"][$RowNumber], $RowNumber);
            $this->txt_Compon2->SetText($this->FormParameters["txt_Compon2"][$RowNumber], $RowNumber);
            $this->tad_CodCompon3->SetText($this->FormParameters["tad_CodCompon3"][$RowNumber], $RowNumber);
            $this->txt_Compon3->SetText($this->FormParameters["txt_Compon3"][$RowNumber], $RowNumber);
            $this->tad_CodCompon4->SetText($this->FormParameters["tad_CodCompon4"][$RowNumber], $RowNumber);
            $this->txt_Compon4->SetText($this->FormParameters["txt_Compon4"][$RowNumber], $RowNumber);
            $this->tad_Observaciones->SetText($this->FormParameters["tad_Observaciones"][$RowNumber], $RowNumber);
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

//ValidateRow Method @292-7D80F50C
    function ValidateRow($RowNumber)
    {
        $Validation = true;
        $Validation = ($this->tad_Secuencia->Validate() && $Validation);
        $Validation = ($this->tad_NumTarja->Validate() && $Validation);
        $Validation = ($this->tad_CodCaja->Validate() && $Validation);
        $Validation = ($this->txt_Empaque->Validate() && $Validation);
        $Validation = ($this->tad_Marca->Validate() && $Validation);
        $Validation = ($this->txt_Marca->Validate() && $Validation);
        $Validation = ($this->tad_CodProducto->Validate() && $Validation);
        $Validation = ($this->tad_CantDespachada->Validate() && $Validation);
        $Validation = ($this->tad_CantRecibida->Validate() && $Validation);
        $Validation = ($this->txt_Faltante->Validate() && $Validation);
        $Validation = ($this->tad_CantRechazada->Validate() && $Validation);
        $Validation = ($this->txt_Embarcado->Validate() && $Validation);
        $Validation = ($this->tad_ValUnitario->Validate() && $Validation);
        $Validation = ($this->tad_DifUnitario->Validate() && $Validation);
        $Validation = ($this->tad_CodCompon1->Validate() && $Validation);
        $Validation = ($this->txt_Compon1->Validate() && $Validation);
        $Validation = ($this->tad_CodCompon2->Validate() && $Validation);
        $Validation = ($this->txt_Compon2->Validate() && $Validation);
        $Validation = ($this->tad_CodCompon3->Validate() && $Validation);
        $Validation = ($this->txt_Compon3->Validate() && $Validation);
        $Validation = ($this->tad_CodCompon4->Validate() && $Validation);
        $Validation = ($this->txt_Compon4->Validate() && $Validation);
        $Validation = ($this->tad_Observaciones->Validate() && $Validation);
        $Validation = ($this->CheckBox_Delete->Validate() && $Validation);
        $errors = "";
        if(!$Validation)
        {
            $errors .= $this->tad_Secuencia->Errors->ToString();
            $errors .= $this->tad_NumTarja->Errors->ToString();
            $errors .= $this->tad_CodCaja->Errors->ToString();
            $errors .= $this->txt_Empaque->Errors->ToString();
            $errors .= $this->tad_Marca->Errors->ToString();
            $errors .= $this->txt_Marca->Errors->ToString();
            $errors .= $this->tad_CodProducto->Errors->ToString();
            $errors .= $this->tad_CantDespachada->Errors->ToString();
            $errors .= $this->tad_CantRecibida->Errors->ToString();
            $errors .= $this->txt_Faltante->Errors->ToString();
            $errors .= $this->tad_CantRechazada->Errors->ToString();
            $errors .= $this->txt_Embarcado->Errors->ToString();
            $errors .= $this->tad_ValUnitario->Errors->ToString();
            $errors .= $this->tad_DifUnitario->Errors->ToString();
            $errors .= $this->tad_CodCompon1->Errors->ToString();
            $errors .= $this->txt_Compon1->Errors->ToString();
            $errors .= $this->tad_CodCompon2->Errors->ToString();
            $errors .= $this->txt_Compon2->Errors->ToString();
            $errors .= $this->tad_CodCompon3->Errors->ToString();
            $errors .= $this->txt_Compon3->Errors->ToString();
            $errors .= $this->tad_CodCompon4->Errors->ToString();
            $errors .= $this->txt_Compon4->Errors->ToString();
            $errors .= $this->tad_Observaciones->Errors->ToString();
            $errors .= $this->CheckBox_Delete->Errors->ToString();
            $this->tad_Secuencia->Errors->Clear();
            $this->tad_NumTarja->Errors->Clear();
            $this->tad_CodCaja->Errors->Clear();
            $this->txt_Empaque->Errors->Clear();
            $this->tad_Marca->Errors->Clear();
            $this->txt_Marca->Errors->Clear();
            $this->tad_CodProducto->Errors->Clear();
            $this->tad_CantDespachada->Errors->Clear();
            $this->tad_CantRecibida->Errors->Clear();
            $this->txt_Faltante->Errors->Clear();
            $this->tad_CantRechazada->Errors->Clear();
            $this->txt_Embarcado->Errors->Clear();
            $this->tad_ValUnitario->Errors->Clear();
            $this->tad_DifUnitario->Errors->Clear();
            $this->tad_CodCompon1->Errors->Clear();
            $this->txt_Compon1->Errors->Clear();
            $this->tad_CodCompon2->Errors->Clear();
            $this->txt_Compon2->Errors->Clear();
            $this->tad_CodCompon3->Errors->Clear();
            $this->txt_Compon3->Errors->Clear();
            $this->tad_CodCompon4->Errors->Clear();
            $this->txt_Compon4->Errors->Clear();
            $this->tad_Observaciones->Errors->Clear();
            $this->CheckBox_Delete->Errors->Clear();
        }
        $this->RowsErrors[$RowNumber] = $errors;
        return $Validation;
    }
//End ValidateRow Method

//CheckInsert Method @292-93A4A934
    function CheckInsert($RowNumber)
    {
        $filed = false;
        $filed = ($filed || strlen($this->FormParameters["tad_Secuencia"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["tad_NumTarja"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["tad_CodCaja"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["txt_Empaque"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["tad_Marca"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["txt_Marca"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["tad_CodProducto"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["tad_CantDespachada"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["tad_CantRecibida"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["txt_Faltante"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["tad_CantRechazada"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["txt_Embarcado"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["tad_ValUnitario"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["tad_DifUnitario"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["tad_CodCompon1"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["txt_Compon1"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["tad_CodCompon2"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["txt_Compon2"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["tad_CodCompon3"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["txt_Compon3"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["tad_CodCompon4"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["txt_Compon4"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["tad_Observaciones"][$RowNumber]));
        return $filed;
    }
//End CheckInsert Method

//CheckErrors Method @292-242E5992
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @292-7B861278
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

//UpdateGrid Method @292-C83F5D23
    function UpdateGrid()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSubmit");
        if(!$this->Validate()) return;
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["tad_Secuencia"] = $this->CachedColumns["tad_Secuencia"][$RowNumber];
            $this->tad_Secuencia->SetText($this->FormParameters["tad_Secuencia"][$RowNumber], $RowNumber);
            $this->tad_NumTarja->SetText($this->FormParameters["tad_NumTarja"][$RowNumber], $RowNumber);
            $this->tad_CodCaja->SetText($this->FormParameters["tad_CodCaja"][$RowNumber], $RowNumber);
            $this->txt_Empaque->SetText($this->FormParameters["txt_Empaque"][$RowNumber], $RowNumber);
            $this->tad_Marca->SetText($this->FormParameters["tad_Marca"][$RowNumber], $RowNumber);
            $this->txt_Marca->SetText($this->FormParameters["txt_Marca"][$RowNumber], $RowNumber);
            $this->tad_CodProducto->SetText($this->FormParameters["tad_CodProducto"][$RowNumber], $RowNumber);
            $this->tad_CantDespachada->SetText($this->FormParameters["tad_CantDespachada"][$RowNumber], $RowNumber);
            $this->tad_CantRecibida->SetText($this->FormParameters["tad_CantRecibida"][$RowNumber], $RowNumber);
            $this->txt_Faltante->SetText($this->FormParameters["txt_Faltante"][$RowNumber], $RowNumber);
            $this->tad_CantRechazada->SetText($this->FormParameters["tad_CantRechazada"][$RowNumber], $RowNumber);
            $this->txt_Embarcado->SetText($this->FormParameters["txt_Embarcado"][$RowNumber], $RowNumber);
            $this->tad_ValUnitario->SetText($this->FormParameters["tad_ValUnitario"][$RowNumber], $RowNumber);
            $this->tad_DifUnitario->SetText($this->FormParameters["tad_DifUnitario"][$RowNumber], $RowNumber);
            $this->tad_CodCompon1->SetText($this->FormParameters["tad_CodCompon1"][$RowNumber], $RowNumber);
            $this->txt_Compon1->SetText($this->FormParameters["txt_Compon1"][$RowNumber], $RowNumber);
            $this->tad_CodCompon2->SetText($this->FormParameters["tad_CodCompon2"][$RowNumber], $RowNumber);
            $this->txt_Compon2->SetText($this->FormParameters["txt_Compon2"][$RowNumber], $RowNumber);
            $this->tad_CodCompon3->SetText($this->FormParameters["tad_CodCompon3"][$RowNumber], $RowNumber);
            $this->txt_Compon3->SetText($this->FormParameters["txt_Compon3"][$RowNumber], $RowNumber);
            $this->tad_CodCompon4->SetText($this->FormParameters["tad_CodCompon4"][$RowNumber], $RowNumber);
            $this->txt_Compon4->SetText($this->FormParameters["txt_Compon4"][$RowNumber], $RowNumber);
            $this->tad_Observaciones->SetText($this->FormParameters["tad_Observaciones"][$RowNumber], $RowNumber);
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

//InsertRow Method @292-7426FA76
    function InsertRow($RowNumber)
    {
        if(!$this->InsertAllowed) return false;
        $this->ds->tad_Secuencia->SetValue($this->tad_Secuencia->GetValue());
        $this->ds->tad_CodProducto->SetValue($this->tad_CodProducto->GetValue());
        $this->ds->tad_Marca->SetValue($this->tad_Marca->GetValue());
        $this->ds->tad_CodCaja->SetValue($this->tad_CodCaja->GetValue());
        $this->ds->tad_CantDespachada->SetValue($this->tad_CantDespachada->GetValue());
        $this->ds->tad_CantRecibida->SetValue($this->tad_CantRecibida->GetValue());
        $this->ds->tad_CantRechazada->SetValue($this->tad_CantRechazada->GetValue());
        $this->ds->tad_ValUnitario->SetValue($this->tad_ValUnitario->GetValue());
        $this->ds->tad_DifUnitario->SetValue($this->tad_DifUnitario->GetValue());
        $this->ds->tad_CodCompon1->SetValue($this->tad_CodCompon1->GetValue());
        $this->ds->tad_CodCompon2->SetValue($this->tad_CodCompon2->GetValue());
        $this->ds->tad_CodCompon3->SetValue($this->tad_CodCompon3->GetValue());
        $this->ds->tad_CodCompon4->SetValue($this->tad_CodCompon4->GetValue());
        $this->ds->tad_Observaciones->SetValue($this->tad_Observaciones->GetValue());
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

//UpdateRow Method @292-2F59AB08
    function UpdateRow($RowNumber)
    {
        if(!$this->UpdateAllowed) return false;
        $this->ds->tad_Secuencia->SetValue($this->tad_Secuencia->GetValue());
        $this->ds->tad_CodProducto->SetValue($this->tad_CodProducto->GetValue());
        $this->ds->tad_Marca->SetValue($this->tad_Marca->GetValue());
        $this->ds->tad_CodCaja->SetValue($this->tad_CodCaja->GetValue());
        $this->ds->tad_CantDespachada->SetValue($this->tad_CantDespachada->GetValue());
        $this->ds->tad_CantRecibida->SetValue($this->tad_CantRecibida->GetValue());
        $this->ds->tad_CantRechazada->SetValue($this->tad_CantRechazada->GetValue());
        $this->ds->tad_ValUnitario->SetValue($this->tad_ValUnitario->GetValue());
        $this->ds->tad_DifUnitario->SetValue($this->tad_DifUnitario->GetValue());
        $this->ds->tad_CodCompon1->SetValue($this->tad_CodCompon1->GetValue());
        $this->ds->tad_CodCompon2->SetValue($this->tad_CodCompon2->GetValue());
        $this->ds->tad_CodCompon3->SetValue($this->tad_CodCompon3->GetValue());
        $this->ds->tad_CodCompon4->SetValue($this->tad_CodCompon4->GetValue());
        $this->ds->tad_Observaciones->SetValue($this->tad_Observaciones->GetValue());
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

//DeleteRow Method @292-0C9DDC34
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

//FormScript Method @292-DD2B7AA2
    function FormScript($TotalRows)
    {
        $script = "";
        $script .= "\n<script language=\"JavaScript\">\n<!--\n";
        $script .= "var liqtarjadetalElements;\n";
        $script .= "var liqtarjadetalEmptyRows = 4;\n";
        $script .= "var " . $this->ComponentName . "tad_SecuenciaID = 0;\n";
        $script .= "var " . $this->ComponentName . "tad_NumTarjaID = 1;\n";
        $script .= "var " . $this->ComponentName . "tad_CodCajaID = 2;\n";
        $script .= "var " . $this->ComponentName . "txt_EmpaqueID = 3;\n";
        $script .= "var " . $this->ComponentName . "tad_MarcaID = 4;\n";
        $script .= "var " . $this->ComponentName . "txt_MarcaID = 5;\n";
        $script .= "var " . $this->ComponentName . "tad_CodProductoID = 6;\n";
        $script .= "var " . $this->ComponentName . "tad_CantDespachadaID = 7;\n";
        $script .= "var " . $this->ComponentName . "tad_CantRecibidaID = 8;\n";
        $script .= "var " . $this->ComponentName . "txt_FaltanteID = 9;\n";
        $script .= "var " . $this->ComponentName . "tad_CantRechazadaID = 10;\n";
        $script .= "var " . $this->ComponentName . "txt_EmbarcadoID = 11;\n";
        $script .= "var " . $this->ComponentName . "tad_ValUnitarioID = 12;\n";
        $script .= "var " . $this->ComponentName . "tad_DifUnitarioID = 13;\n";
        $script .= "var " . $this->ComponentName . "tad_CodCompon1ID = 14;\n";
        $script .= "var " . $this->ComponentName . "txt_Compon1ID = 15;\n";
        $script .= "var " . $this->ComponentName . "tad_CodCompon2ID = 16;\n";
        $script .= "var " . $this->ComponentName . "txt_Compon2ID = 17;\n";
        $script .= "var " . $this->ComponentName . "tad_CodCompon3ID = 18;\n";
        $script .= "var " . $this->ComponentName . "txt_Compon3ID = 19;\n";
        $script .= "var " . $this->ComponentName . "tad_CodCompon4ID = 20;\n";
        $script .= "var " . $this->ComponentName . "txt_Compon4ID = 21;\n";
        $script .= "var " . $this->ComponentName . "tad_ObservacionesID = 22;\n";
        $script .= "var " . $this->ComponentName . "DeleteControl = 23;\n";
        $script .= "\nfunction initliqtarjadetalElements() {\n";
        $script .= "\tvar ED = document.forms[\"liqtarjadetal\"];\n";
        $script .= "\tliqtarjadetalElements = new Array (\n";
        for($i = 1; $i <= $TotalRows; $i++) {
            $script .= "\t\tnew Array(" . "ED.tad_Secuencia_" . $i . ", " . "ED.tad_NumTarja_" . $i . ", " . "ED.tad_CodCaja_" . $i . ", " . "ED.txt_Empaque_" . $i . ", " . "ED.tad_Marca_" . $i . ", " . "ED.txt_Marca_" . $i . ", " . "ED.tad_CodProducto_" . $i . ", " . "ED.tad_CantDespachada_" . $i . ", " . "ED.tad_CantRecibida_" . $i . ", " . "ED.txt_Faltante_" . $i . ", " . "ED.tad_CantRechazada_" . $i . ", " . "ED.txt_Embarcado_" . $i . ", " . "ED.tad_ValUnitario_" . $i . ", " . "ED.tad_DifUnitario_" . $i . ", " . "ED.tad_CodCompon1_" . $i . ", " . "ED.txt_Compon1_" . $i . ", " . "ED.tad_CodCompon2_" . $i . ", " . "ED.txt_Compon2_" . $i . ", " . "ED.tad_CodCompon3_" . $i . ", " . "ED.txt_Compon3_" . $i . ", " . "ED.tad_CodCompon4_" . $i . ", " . "ED.txt_Compon4_" . $i . ", " . "ED.tad_Observaciones_" . $i . ", " . "ED.CheckBox_Delete_" . $i . ")";
            if($i != $TotalRows) $script .= ",\n";
        }
        $script .= ");\n";
        $script .= "}\n";
        $script .= "\n//-->\n</script>";
        return $script;
    }
//End FormScript Method

//SetFormState Method @292-CDDD2CFC
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
            for($i = 2; $i < sizeof($pieces); $i = $i + 1)  {
                $piece = $pieces[$i + 0];
                $piece = str_replace("\\" . ord("\\"), "\\", $piece);
                $piece = str_replace("\\" . ord(";"), ";", $piece);
                $this->CachedColumns["tad_Secuencia"][$RowNumber] = $piece;
                $RowNumber++;
            }

            if(!$RowNumber) { $RowNumber = 1; }
            for($i = 1; $i <= $this->EmptyRows; $i++) {
                $this->CachedColumns["tad_Secuencia"][$RowNumber] = "";
                $RowNumber++;
            }
        }
    }
//End SetFormState Method

//GetFormState Method @292-5C0C8971
    function GetFormState($NonEmptyRows)
    {
        if(!$this->FormSubmitted) {
            $this->FormState  = $NonEmptyRows . ";";
            $this->FormState .= $this->InsertAllowed ? $this->EmptyRows : "0";
            if($NonEmptyRows) {
                for($i = 0; $i <= $NonEmptyRows; $i++) {
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["tad_Secuencia"][$i]));
                }
            }
        }
        return $this->FormState;
    }
//End GetFormState Method

//Show Method @292-FF70CB0F
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible) { return; }

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->tad_CodProducto->Prepare();

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
                        $this->CachedColumns["tad_Secuencia"][$RowNumber] = $this->ds->CachedColumns["tad_Secuencia"];
                        $this->tad_Secuencia->SetValue($this->ds->tad_Secuencia->GetValue());
                        $this->tad_NumTarja->SetValue($this->ds->tad_NumTarja->GetValue());
                        $this->tad_CodCaja->SetValue($this->ds->tad_CodCaja->GetValue());
                        $this->txt_Empaque->SetValue($this->ds->txt_Empaque->GetValue());
                        $this->tad_Marca->SetValue($this->ds->tad_Marca->GetValue());
                        $this->txt_Marca->SetValue($this->ds->txt_Marca->GetValue());
                        $this->tad_CodProducto->SetValue($this->ds->tad_CodProducto->GetValue());
                        $this->tad_CantDespachada->SetValue($this->ds->tad_CantDespachada->GetValue());
                        $this->tad_CantRecibida->SetValue($this->ds->tad_CantRecibida->GetValue());
                        $this->txt_Faltante->SetValue($this->ds->txt_Faltante->GetValue());
                        $this->tad_CantRechazada->SetValue($this->ds->tad_CantRechazada->GetValue());
                        $this->txt_Embarcado->SetValue($this->ds->txt_Embarcado->GetValue());
                        $this->tad_ValUnitario->SetValue($this->ds->tad_ValUnitario->GetValue());
                        $this->tad_DifUnitario->SetValue($this->ds->tad_DifUnitario->GetValue());
                        $this->tad_CodCompon1->SetValue($this->ds->tad_CodCompon1->GetValue());
                        $this->txt_Compon1->SetValue($this->ds->txt_Compon1->GetValue());
                        $this->tad_CodCompon2->SetValue($this->ds->tad_CodCompon2->GetValue());
                        $this->txt_Compon2->SetValue($this->ds->txt_Compon2->GetValue());
                        $this->tad_CodCompon3->SetValue($this->ds->tad_CodCompon3->GetValue());
                        $this->txt_Compon3->SetValue($this->ds->txt_Compon3->GetValue());
                        $this->tad_CodCompon4->SetValue($this->ds->tad_CodCompon4->GetValue());
                        $this->txt_Compon4->SetValue($this->ds->txt_Compon4->GetValue());
                        $this->tad_Observaciones->SetValue($this->ds->tad_Observaciones->GetValue());
                        $this->ValidateRow($RowNumber);
                    } else if (!$this->FormSubmitted){
                        $this->CachedColumns["tad_Secuencia"][$RowNumber] = "";
                        $this->tad_Secuencia->SetText("");
                        $this->tad_NumTarja->SetText("");
                        $this->tad_CodCaja->SetText("");
                        $this->txt_Empaque->SetText("");
                        $this->tad_Marca->SetText("");
                        $this->txt_Marca->SetText("");
                        $this->tad_CodProducto->SetText("");
                        $this->tad_CantDespachada->SetText("");
                        $this->tad_CantRecibida->SetText("");
                        $this->txt_Faltante->SetText("");
                        $this->tad_CantRechazada->SetText("");
                        $this->txt_Embarcado->SetText("");
                        $this->tad_ValUnitario->SetText("");
                        $this->tad_DifUnitario->SetText("");
                        $this->tad_CodCompon1->SetText("");
                        $this->txt_Compon1->SetText("");
                        $this->tad_CodCompon2->SetText("");
                        $this->txt_Compon2->SetText("");
                        $this->tad_CodCompon3->SetText("");
                        $this->txt_Compon3->SetText("");
                        $this->tad_CodCompon4->SetText("");
                        $this->txt_Compon4->SetText("");
                        $this->tad_Observaciones->SetText("");
                        $this->CheckBox_Delete->SetText("");
                    } else {
                        $this->tad_Secuencia->SetText($this->FormParameters["tad_Secuencia"][$RowNumber], $RowNumber);
                        $this->tad_NumTarja->SetText($this->FormParameters["tad_NumTarja"][$RowNumber], $RowNumber);
                        $this->tad_CodCaja->SetText($this->FormParameters["tad_CodCaja"][$RowNumber], $RowNumber);
                        $this->txt_Empaque->SetText($this->FormParameters["txt_Empaque"][$RowNumber], $RowNumber);
                        $this->tad_Marca->SetText($this->FormParameters["tad_Marca"][$RowNumber], $RowNumber);
                        $this->txt_Marca->SetText($this->FormParameters["txt_Marca"][$RowNumber], $RowNumber);
                        $this->tad_CodProducto->SetText($this->FormParameters["tad_CodProducto"][$RowNumber], $RowNumber);
                        $this->tad_CantDespachada->SetText($this->FormParameters["tad_CantDespachada"][$RowNumber], $RowNumber);
                        $this->tad_CantRecibida->SetText($this->FormParameters["tad_CantRecibida"][$RowNumber], $RowNumber);
                        $this->txt_Faltante->SetText($this->FormParameters["txt_Faltante"][$RowNumber], $RowNumber);
                        $this->tad_CantRechazada->SetText($this->FormParameters["tad_CantRechazada"][$RowNumber], $RowNumber);
                        $this->txt_Embarcado->SetText($this->FormParameters["txt_Embarcado"][$RowNumber], $RowNumber);
                        $this->tad_ValUnitario->SetText($this->FormParameters["tad_ValUnitario"][$RowNumber], $RowNumber);
                        $this->tad_DifUnitario->SetText($this->FormParameters["tad_DifUnitario"][$RowNumber], $RowNumber);
                        $this->tad_CodCompon1->SetText($this->FormParameters["tad_CodCompon1"][$RowNumber], $RowNumber);
                        $this->txt_Compon1->SetText($this->FormParameters["txt_Compon1"][$RowNumber], $RowNumber);
                        $this->tad_CodCompon2->SetText($this->FormParameters["tad_CodCompon2"][$RowNumber], $RowNumber);
                        $this->txt_Compon2->SetText($this->FormParameters["txt_Compon2"][$RowNumber], $RowNumber);
                        $this->tad_CodCompon3->SetText($this->FormParameters["tad_CodCompon3"][$RowNumber], $RowNumber);
                        $this->txt_Compon3->SetText($this->FormParameters["txt_Compon3"][$RowNumber], $RowNumber);
                        $this->tad_CodCompon4->SetText($this->FormParameters["tad_CodCompon4"][$RowNumber], $RowNumber);
                        $this->txt_Compon4->SetText($this->FormParameters["txt_Compon4"][$RowNumber], $RowNumber);
                        $this->tad_Observaciones->SetText($this->FormParameters["tad_Observaciones"][$RowNumber], $RowNumber);
                        $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$RowNumber], $RowNumber);
                    }
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->tad_Secuencia->Show($RowNumber);
                    $this->tad_NumTarja->Show($RowNumber);
                    $this->tad_CodCaja->Show($RowNumber);
                    $this->txt_Empaque->Show($RowNumber);
                    $this->tad_Marca->Show($RowNumber);
                    $this->txt_Marca->Show($RowNumber);
                    $this->tad_CodProducto->Show($RowNumber);
                    $this->tad_CantDespachada->Show($RowNumber);
                    $this->tad_CantRecibida->Show($RowNumber);
                    $this->txt_Faltante->Show($RowNumber);
                    $this->tad_CantRechazada->Show($RowNumber);
                    $this->txt_Embarcado->Show($RowNumber);
                    $this->tad_ValUnitario->Show($RowNumber);
                    $this->tad_DifUnitario->Show($RowNumber);
                    $this->tad_CodCompon1->Show($RowNumber);
                    $this->txt_Compon1->Show($RowNumber);
                    $this->tad_CodCompon2->Show($RowNumber);
                    $this->txt_Compon2->Show($RowNumber);
                    $this->tad_CodCompon3->Show($RowNumber);
                    $this->txt_Compon3->Show($RowNumber);
                    $this->tad_CodCompon4->Show($RowNumber);
                    $this->txt_Compon4->Show($RowNumber);
                    $this->tad_Observaciones->Show($RowNumber);
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
        // fah bloqueo
        global $ilEstado;
        fHabilitaBotonesCCS(false, 'UPD', $ilEstado );
        $Tpl->block_path = $EditableGridPath;
        $this->Navigator1->PageNumber = $this->ds->AbsolutePage;
        $this->Navigator1->TotalPages = $this->ds->PageCount();
        $this->Sorter_tad_Secuencia->Show();
        $this->Sorter_tad_CodCaja->Show();
        $this->Sorter_tad_CodProducto->Show();
        $this->Navigator1->Show();
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

} //End liqtarjadetal Class @292-FCB6E20C

class clsliqtarjadetalDataSource extends clsDBdatos {  //liqtarjadetalDataSource Class @292-6FAFADCC

//DataSource Variables @292-13B642EE
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
    var $tad_Secuencia;
    var $tad_NumTarja;
    var $tad_CodCaja;
    var $txt_Empaque;
    var $tad_Marca;
    var $txt_Marca;
    var $tad_CodProducto;
    var $tad_CantDespachada;
    var $tad_CantRecibida;
    var $txt_Faltante;
    var $tad_CantRechazada;
    var $txt_Embarcado;
    var $tad_ValUnitario;
    var $tad_DifUnitario;
    var $tad_CodCompon1;
    var $txt_Compon1;
    var $tad_CodCompon2;
    var $txt_Compon2;
    var $tad_CodCompon3;
    var $txt_Compon3;
    var $tad_CodCompon4;
    var $txt_Compon4;
    var $tad_Observaciones;
    var $CheckBox_Delete;
//End DataSource Variables

//Class_Initialize Event @292-70E359FA
    function clsliqtarjadetalDataSource()
    {
        $this->ErrorBlock = "EditableGrid liqtarjadetal/Error";
        $this->Initialize();
        $this->tad_Secuencia = new clsField("tad_Secuencia", ccsInteger, "");
        $this->tad_NumTarja = new clsField("tad_NumTarja", ccsInteger, "");
        $this->tad_CodCaja = new clsField("tad_CodCaja", ccsInteger, "");
        $this->txt_Empaque = new clsField("txt_Empaque", ccsText, "");
        $this->tad_Marca = new clsField("tad_Marca", ccsInteger, "");
        $this->txt_Marca = new clsField("txt_Marca", ccsText, "");
        $this->tad_CodProducto = new clsField("tad_CodProducto", ccsInteger, "");
        $this->tad_CantDespachada = new clsField("tad_CantDespachada", ccsInteger, "");
        $this->tad_CantRecibida = new clsField("tad_CantRecibida", ccsInteger, "");
        $this->txt_Faltante = new clsField("txt_Faltante", ccsFloat, "");
        $this->tad_CantRechazada = new clsField("tad_CantRechazada", ccsInteger, "");
        $this->txt_Embarcado = new clsField("txt_Embarcado", ccsFloat, "");
        $this->tad_ValUnitario = new clsField("tad_ValUnitario", ccsFloat, "");
        $this->tad_DifUnitario = new clsField("tad_DifUnitario", ccsFloat, "");
        $this->tad_CodCompon1 = new clsField("tad_CodCompon1", ccsInteger, "");
        $this->txt_Compon1 = new clsField("txt_Compon1", ccsText, "");
        $this->tad_CodCompon2 = new clsField("tad_CodCompon2", ccsInteger, "");
        $this->txt_Compon2 = new clsField("txt_Compon2", ccsText, "");
        $this->tad_CodCompon3 = new clsField("tad_CodCompon3", ccsInteger, "");
        $this->txt_Compon3 = new clsField("txt_Compon3", ccsText, "");
        $this->tad_CodCompon4 = new clsField("tad_CodCompon4", ccsInteger, "");
        $this->txt_Compon4 = new clsField("txt_Compon4", ccsText, "");
        $this->tad_Observaciones = new clsField("tad_Observaciones", ccsText, "");
        $this->CheckBox_Delete = new clsField("CheckBox_Delete", ccsBoolean, Array("true", "false", ""));

    }
//End Class_Initialize Event

//SetOrder Method @292-603C9AFB
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "tad_Secuencia";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_tad_Secuencia" => array("tad_Secuencia", ""), 
            "Sorter_tad_CodCaja" => array("tad_CodCaja", ""), 
            "Sorter_tad_CodProducto" => array("tad_CodProducto", "")));
    }
//End SetOrder Method

//Prepare Method @292-45726C62
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urltar_NumTarja", ccsInteger, "", "", $this->Parameters["urltar_NumTarja"], 0, false);
        $this->wp->AddParameter("2", "expr388", ccsText, "", "", $this->Parameters["expr388"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
    }
//End Prepare Method

//Open Method @292-B16371A6
 function Open()
 {
  $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
  $this->CountSQL = "SELECT COUNT(*) FROM (((((liqtarjadetal ta LEFT JOIN genparametros pa ON ta.tad_CodMarca = pa.par_Secuencia) LEFT JOIN liqcajas cj ON ta.tad_CodCaja = cj.caj_CodCaja) LEFT JOIN liqcomponent cc ON ta.tad_CodCompon1 = cc.cte_Codigo) LEFT JOIN liqcomponent cp ON ta.tad_CodCompon2 = cp.cte_Codigo) LEFT JOIN liqcomponent cm ON ta.tad_CodCompon3 = cm.cte_Codigo) LEFT JOIN liqcomponent ce ON ta.tad_CodCompon4 = ce.cte_Codigo " .
  "WHERE tad_NumTarja = " . $this->SQLValue($this->wp->GetDBValue("1"), ccsInteger) . " AND par_Clave = 'IMARCA' " .
  "";
  $this->SQL = "SELECT ta.*, par_Descripcion, caj_Descripcion,  " .
  "       cp.cte_Descripcion AS cp_Descripcion,  " .
  "       cm.cte_Descripcion AS cm_Descripcion,  " .
  "       ce.cte_Codigo AS ce_Codigo,  " .
  "       ce.cte_Descripcion AS ce_Descripcion,  " .
  "       cc.cte_Descripcion AS cc_Descripcion, " .
  "       tad_Cantdespachada - tad_CantRecibida as txt_Faltante, " .
  "       tad_CantRecibida - tad_CantRechazada as txt_Embarcado " .
  "FROM (((((liqtarjadetal ta LEFT JOIN genparametros pa ON par_Clave = 'IMARCA' AND ta.tad_CodMarca = pa.par_Secuencia) LEFT JOIN liqcajas cj ON ta.tad_CodCaja = cj.caj_CodCaja) LEFT JOIN liqcomponent cc ON ta.tad_CodCompon1 = cc.cte_Codigo) LEFT JOIN liqcomponent cp ON ta.tad_CodCompon2 = cp.cte_Codigo) LEFT JOIN liqcomponent cm ON ta.tad_CodCompon3 = cm.cte_Codigo) LEFT JOIN liqcomponent ce ON ta.tad_CodCompon4 = ce.cte_Codigo " .
  "WHERE tad_NumTarja = " . $this->SQLValue($this->wp->GetDBValue("1"), ccsInteger) . " " .
  "";
  $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
  $this->RecordsCount = CCGetDBValue($this->CountSQL, $this);
  $this->query(CCBuildSQL($this->SQL, "", $this->Order));
  $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
  $this->MoveToPage($this->AbsolutePage);
 }
//End Open Method

//SetValues Method @292-841ED2CF
    function SetValues()
    {
        $this->CachedColumns["tad_Secuencia"] = $this->f("tad_Secuencia");
        $this->tad_Secuencia->SetDBValue(trim($this->f("tad_Secuencia")));
        $this->tad_NumTarja->SetDBValue(trim($this->f("tad_NumTarja")));
        $this->tad_CodCaja->SetDBValue(trim($this->f("tad_CodCaja")));
        $this->txt_Empaque->SetDBValue($this->f("caj_Descripcion"));
        $this->tad_Marca->SetDBValue(trim($this->f("tad_CodMarca")));
        $this->txt_Marca->SetDBValue($this->f("par_Descripcion"));
        $this->tad_CodProducto->SetDBValue(trim($this->f("tad_CodProducto")));
        $this->tad_CantDespachada->SetDBValue(trim($this->f("tad_CantDespachada")));
        $this->tad_CantRecibida->SetDBValue(trim($this->f("tad_CantRecibida")));
        $this->txt_Faltante->SetDBValue(trim($this->f("txt_Faltante")));
        $this->tad_CantRechazada->SetDBValue(trim($this->f("tad_CantRechazada")));
        $this->txt_Embarcado->SetDBValue(trim($this->f("txt_Embarcado")));
        $this->tad_ValUnitario->SetDBValue(trim($this->f("tad_ValUnitario")));
        $this->tad_DifUnitario->SetDBValue(trim($this->f("tad_DifUnitario")));
        $this->tad_CodCompon1->SetDBValue(trim($this->f("tad_CodCompon1")));
        $this->txt_Compon1->SetDBValue($this->f("cc_Descripcion"));
        $this->tad_CodCompon2->SetDBValue(trim($this->f("tad_CodCompon2")));
        $this->txt_Compon2->SetDBValue($this->f("cp_Descripcion"));
        $this->tad_CodCompon3->SetDBValue(trim($this->f("tad_CodCompon3")));
        $this->txt_Compon3->SetDBValue($this->f("cm_Descripcion"));
        $this->tad_CodCompon4->SetDBValue(trim($this->f("tad_CodCompon4")));
        $this->txt_Compon4->SetDBValue($this->f("ce_Descripcion"));
        $this->tad_Observaciones->SetDBValue($this->f("tad_Observaciones"));
    }
//End SetValues Method

//Insert Method @292-CBEC6087
    function Insert()
    {
        $this->cp["tad_NumTarja"] = new clsSQLParameter("urltar_NumTarja", ccsInteger, "", "", CCGetFromGet("tar_NumTarja", ""), -1, false, $this->ErrorBlock);
        $this->cp["tad_Secuencia"] = new clsSQLParameter("ctrltad_Secuencia", ccsInteger, "", "", $this->tad_Secuencia->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["tad_CodProducto"] = new clsSQLParameter("ctrltad_CodProducto", ccsInteger, "", "", $this->tad_CodProducto->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["tad_CodMarca"] = new clsSQLParameter("ctrltad_Marca", ccsInteger, "", "", $this->tad_Marca->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["tad_CodCaja"] = new clsSQLParameter("ctrltad_CodCaja", ccsInteger, "", "", $this->tad_CodCaja->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["tad_CantDespachada"] = new clsSQLParameter("ctrltad_CantDespachada", ccsInteger, "", "", $this->tad_CantDespachada->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["tad_CantRecibida"] = new clsSQLParameter("ctrltad_CantRecibida", ccsInteger, "", "", $this->tad_CantRecibida->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["tad_CantRechazada"] = new clsSQLParameter("ctrltad_CantRechazada", ccsInteger, "", "", $this->tad_CantRechazada->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["tad_ValUnitario"] = new clsSQLParameter("ctrltad_ValUnitario", ccsFloat, "", "", $this->tad_ValUnitario->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["tad_DifUnitario"] = new clsSQLParameter("ctrltad_DifUnitario", ccsFloat, "", "", $this->tad_DifUnitario->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["tad_CodCompon1"] = new clsSQLParameter("ctrltad_CodCompon1", ccsInteger, "", "", $this->tad_CodCompon1->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["tad_CodCompon2"] = new clsSQLParameter("ctrltad_CodCompon2", ccsInteger, "", "", $this->tad_CodCompon2->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["tad_CodCompon3"] = new clsSQLParameter("ctrltad_CodCompon3", ccsInteger, "", "", $this->tad_CodCompon3->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["tad_CodCompon4"] = new clsSQLParameter("ctrltad_CodCompon4", ccsInteger, "", "", $this->tad_CodCompon4->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["tad_Observaciones"] = new clsSQLParameter("ctrltad_Observaciones", ccsText, "", "", $this->tad_Observaciones->GetValue(), '', false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO liqtarjadetal ("
             . "tad_NumTarja, "
             . "tad_Secuencia, "
             . "tad_CodProducto, "
             . "tad_CodMarca, "
             . "tad_CodCaja, "
             . "tad_CantDespachada, "
             . "tad_CantRecibida, "
             . "tad_CantRechazada, "
             . "tad_ValUnitario, "
             . "tad_DifUnitario, "
             . "tad_CodCompon1, "
             . "tad_CodCompon2, "
             . "tad_CodCompon3, "
             . "tad_CodCompon4, "
             . "tad_Observaciones"
             . ") VALUES ("
             . $this->ToSQL($this->cp["tad_NumTarja"]->GetDBValue(), $this->cp["tad_NumTarja"]->DataType) . ", "
             . $this->ToSQL($this->cp["tad_Secuencia"]->GetDBValue(), $this->cp["tad_Secuencia"]->DataType) . ", "
             . $this->ToSQL($this->cp["tad_CodProducto"]->GetDBValue(), $this->cp["tad_CodProducto"]->DataType) . ", "
             . $this->ToSQL($this->cp["tad_CodMarca"]->GetDBValue(), $this->cp["tad_CodMarca"]->DataType) . ", "
             . $this->ToSQL($this->cp["tad_CodCaja"]->GetDBValue(), $this->cp["tad_CodCaja"]->DataType) . ", "
             . $this->ToSQL($this->cp["tad_CantDespachada"]->GetDBValue(), $this->cp["tad_CantDespachada"]->DataType) . ", "
             . $this->ToSQL($this->cp["tad_CantRecibida"]->GetDBValue(), $this->cp["tad_CantRecibida"]->DataType) . ", "
             . $this->ToSQL($this->cp["tad_CantRechazada"]->GetDBValue(), $this->cp["tad_CantRechazada"]->DataType) . ", "
             . $this->ToSQL($this->cp["tad_ValUnitario"]->GetDBValue(), $this->cp["tad_ValUnitario"]->DataType) . ", "
             . $this->ToSQL($this->cp["tad_DifUnitario"]->GetDBValue(), $this->cp["tad_DifUnitario"]->DataType) . ", "
             . $this->ToSQL($this->cp["tad_CodCompon1"]->GetDBValue(), $this->cp["tad_CodCompon1"]->DataType) . ", "
             . $this->ToSQL($this->cp["tad_CodCompon2"]->GetDBValue(), $this->cp["tad_CodCompon2"]->DataType) . ", "
             . $this->ToSQL($this->cp["tad_CodCompon3"]->GetDBValue(), $this->cp["tad_CodCompon3"]->DataType) . ", "
             . $this->ToSQL($this->cp["tad_CodCompon4"]->GetDBValue(), $this->cp["tad_CodCompon4"]->DataType) . ", "
             . $this->ToSQL($this->cp["tad_Observaciones"]->GetDBValue(), $this->cp["tad_Observaciones"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Insert Method

//Update Method @292-AC8518EB
    function Update()
    {
        $this->cp["tad_Secuencia"] = new clsSQLParameter("ctrltad_Secuencia", ccsInteger, "", "", $this->tad_Secuencia->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tad_CodProducto"] = new clsSQLParameter("ctrltad_CodProducto", ccsInteger, "", "", $this->tad_CodProducto->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tad_CodMarca"] = new clsSQLParameter("ctrltad_Marca", ccsInteger, "", "", $this->tad_Marca->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tad_CodCaja"] = new clsSQLParameter("ctrltad_CodCaja", ccsInteger, "", "", $this->tad_CodCaja->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tad_CantDespachada"] = new clsSQLParameter("ctrltad_CantDespachada", ccsInteger, "", "", $this->tad_CantDespachada->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tad_CantRecibida"] = new clsSQLParameter("ctrltad_CantRecibida", ccsInteger, "", "", $this->tad_CantRecibida->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tad_CantRechazada"] = new clsSQLParameter("ctrltad_CantRechazada", ccsInteger, "", "", $this->tad_CantRechazada->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tad_ValUnitario"] = new clsSQLParameter("ctrltad_ValUnitario", ccsFloat, "", "", $this->tad_ValUnitario->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tad_DifUnitario"] = new clsSQLParameter("ctrltad_DifUnitario", ccsFloat, "", "", $this->tad_DifUnitario->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tad_CodCompon1"] = new clsSQLParameter("ctrltad_CodCompon1", ccsInteger, "", "", $this->tad_CodCompon1->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tad_CodCompon2"] = new clsSQLParameter("ctrltad_CodCompon2", ccsInteger, "", "", $this->tad_CodCompon2->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tad_CodCompon3"] = new clsSQLParameter("ctrltad_CodCompon3", ccsInteger, "", "", $this->tad_CodCompon3->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tad_CodCompon4"] = new clsSQLParameter("ctrltad_CodCompon4", ccsInteger, "", "", $this->tad_CodCompon4->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tad_Observaciones"] = new clsSQLParameter("ctrltad_Observaciones", ccsText, "", "", $this->tad_Observaciones->GetValue(), "", false, $this->ErrorBlock);
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urltar_NumTarja", ccsInteger, "", "", CCGetFromGet("tar_NumTarja", ""), "", true);
        $wp->AddParameter("2", "dstad_Secuencia", ccsInteger, "", "", $this->CachedColumns["tad_Secuencia"], "", false);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $wp->Criterion[1] = $wp->Operation(opEqual, "tad_NumTarja", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),true);
        $wp->Criterion[2] = $wp->Operation(opEqual, "tad_Secuencia", $wp->GetDBValue("2"), $this->ToSQL($wp->GetDBValue("2"), ccsInteger),false);
        $Where = $wp->opAND(false, $wp->Criterion[1], $wp->Criterion[2]);
        $this->SQL = "UPDATE liqtarjadetal SET "
             . "tad_Secuencia=" . $this->ToSQL($this->cp["tad_Secuencia"]->GetDBValue(), $this->cp["tad_Secuencia"]->DataType) . ", "
             . "tad_CodProducto=" . $this->ToSQL($this->cp["tad_CodProducto"]->GetDBValue(), $this->cp["tad_CodProducto"]->DataType) . ", "
             . "tad_CodMarca=" . $this->ToSQL($this->cp["tad_CodMarca"]->GetDBValue(), $this->cp["tad_CodMarca"]->DataType) . ", "
             . "tad_CodCaja=" . $this->ToSQL($this->cp["tad_CodCaja"]->GetDBValue(), $this->cp["tad_CodCaja"]->DataType) . ", "
             . "tad_CantDespachada=" . $this->ToSQL($this->cp["tad_CantDespachada"]->GetDBValue(), $this->cp["tad_CantDespachada"]->DataType) . ", "
             . "tad_CantRecibida=" . $this->ToSQL($this->cp["tad_CantRecibida"]->GetDBValue(), $this->cp["tad_CantRecibida"]->DataType) . ", "
             . "tad_CantRechazada=" . $this->ToSQL($this->cp["tad_CantRechazada"]->GetDBValue(), $this->cp["tad_CantRechazada"]->DataType) . ", "
             . "tad_ValUnitario=" . $this->ToSQL($this->cp["tad_ValUnitario"]->GetDBValue(), $this->cp["tad_ValUnitario"]->DataType) . ", "
             . "tad_DifUnitario=" . $this->ToSQL($this->cp["tad_DifUnitario"]->GetDBValue(), $this->cp["tad_DifUnitario"]->DataType) . ", "
             . "tad_CodCompon1=" . $this->ToSQL($this->cp["tad_CodCompon1"]->GetDBValue(), $this->cp["tad_CodCompon1"]->DataType) . ", "
             . "tad_CodCompon2=" . $this->ToSQL($this->cp["tad_CodCompon2"]->GetDBValue(), $this->cp["tad_CodCompon2"]->DataType) . ", "
             . "tad_CodCompon3=" . $this->ToSQL($this->cp["tad_CodCompon3"]->GetDBValue(), $this->cp["tad_CodCompon3"]->DataType) . ", "
             . "tad_CodCompon4=" . $this->ToSQL($this->cp["tad_CodCompon4"]->GetDBValue(), $this->cp["tad_CodCompon4"]->DataType) . ", "
             . "tad_Observaciones=" . $this->ToSQL($this->cp["tad_Observaciones"]->GetDBValue(), $this->cp["tad_Observaciones"]->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Update Method

//Delete Method @292-7771AB75
    function Delete()
    {
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urltar_NumTarja", ccsInteger, "", "", CCGetFromGet("tar_NumTarja", ""), "", true);
        $wp->AddParameter("2", "dstad_Secuencia", ccsInteger, "", "", $this->CachedColumns["tad_Secuencia"], "", false);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $wp->Criterion[1] = $wp->Operation(opEqual, "tad_NumTarja", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),true);
        $wp->Criterion[2] = $wp->Operation(opEqual, "tad_Secuencia", $wp->GetDBValue("2"), $this->ToSQL($wp->GetDBValue("2"), ccsInteger),false);
        $Where = $wp->opAND(false, $wp->Criterion[1], $wp->Criterion[2]);
        $this->SQL = "DELETE FROM liqtarjadetal";
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Delete Method

} //End liqtarjadetalDataSource Class @292-FCB6E20C

//Initialize Page @1-644646CF
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
$ilEstado = 1;                              // Estado de la tarja -1 = Cerrada, en liquidacion   0 o positivo: Abierta
$FileName = "LiEmTj_mant.php";
$Redirect = "";
$TemplateFileName = "LiEmTj_mant.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-FFA2AE9F
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
$liqtarjacabec = new clsRecordliqtarjacabec();
$liqtarjadetal = new clsEditableGridliqtarjadetal();
$liqtarjacabec->Initialize();
$liqtarjadetal->Initialize();

// Events
include("./LiEmTj_mant_events.php");
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

//Execute Components @1-E7169BD1
$Cabecera->Operations();
$liqtarjacabec->Operation();
$liqtarjadetal->Operation();
//End Execute Components
if($liqtarjacabec->PressedButton == "Button_Insert") { 
	$Redirect .=  "&tar_NumTarja=" . CCGetParam("tar_NumTarja", "0");
	}
//die();
//Go to destination page @1-CDA9AAFD
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    $DBdatos->close();
    header("Location: " . $Redirect);
    exit;
}
//End Go to destination page

//Show Page @1-05A73F1A
$Cabecera->Show("Cabecera");
$liqtarjacabec->Show();
$liqtarjadetal->Show();
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
