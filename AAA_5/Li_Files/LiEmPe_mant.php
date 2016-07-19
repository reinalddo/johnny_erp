<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @38-49BEB8FC
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation

class clsRecordliqembarques { //liqembarques Class @2-009A0617

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

//Class_Initialize Event @2-F7B4DE3F
    function clsRecordliqembarques()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record liqembarques/Error";
        $this->ds = new clsliqembarquesDataSource();
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "liqembarques";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->emb_RefOperativa = new clsControl(ccsTextBox, "emb_RefOperativa", "emb_RefOperativa", ccsText, "", CCGetRequestParam("emb_RefOperativa", $Method));
            $this->emb_AnoOperacion = new clsControl(ccsTextBox, "emb_AnoOperacion", "Año de Operacion", ccsInteger, "", CCGetRequestParam("emb_AnoOperacion", $Method));
            $this->emb_AnoOperacion->Required = true;
            $this->emb_Consignatario = new clsControl(ccsTextBox, "emb_Consignatario", "Cód. de Consignatario", ccsInteger, "", CCGetRequestParam("emb_Consignatario", $Method));
            $this->emb_Consignatario->Required = true;
            $this->txt_Consignat = new clsControl(ccsTextBox, "txt_Consignat", "CONSIGNATARIO", ccsText, "", CCGetRequestParam("txt_Consignat", $Method));
            $this->emb_CodVapor = new clsControl(ccsTextBox, "emb_CodVapor", "Código de Vapor", ccsInteger, "", CCGetRequestParam("emb_CodVapor", $Method));
            $this->emb_CodVapor->Required = true;
            $this->txt_Vapor = new clsControl(ccsTextBox, "txt_Vapor", "NOMBRE DE BUQUE", ccsText, "", CCGetRequestParam("txt_Vapor", $Method));
            $this->emb_NumViaje = new clsControl(ccsTextBox, "emb_NumViaje", "Número de Viaje", ccsInteger, "", CCGetRequestParam("emb_NumViaje", $Method));
            $this->emb_NumViaje->Required = true;
            $this->emb_SemInicio = new clsControl(ccsTextBox, "emb_SemInicio", "Semana Inicial", ccsInteger, "", CCGetRequestParam("emb_SemInicio", $Method));
            $this->emb_SemInicio->Required = true;
            $this->emb_SemTermino = new clsControl(ccsTextBox, "emb_SemTermino", "Semana Final", ccsInteger, "", CCGetRequestParam("emb_SemTermino", $Method));
            $this->emb_SemTermino->Required = true;
            $this->emb_FecInicio = new clsControl(ccsTextBox, "emb_FecInicio", "Fecha de Inicio", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("emb_FecInicio", $Method));
            $this->emb_FecInicio->Required = true;
            $this->DatePicker_emb_FecInicio = new clsDatePicker("DatePicker_emb_FecInicio", "liqembarques", "emb_FecInicio");
            $this->emb_FecTermino = new clsControl(ccsTextBox, "emb_FecTermino", "Fecha de Termino", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("emb_FecTermino", $Method));
            $this->emb_FecTermino->Required = true;
            $this->DatePicker_emb_FecTermino = new clsDatePicker("DatePicker_emb_FecTermino", "liqembarques", "emb_FecTermino");
            $this->emb_FecZarpe = new clsControl(ccsTextBox, "emb_FecZarpe", "fecha de Zarpe", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("emb_FecZarpe", $Method));
            $this->emb_FecZarpe->Required = true;
            $this->DatePicker_emb_FecZarpe = new clsDatePicker("DatePicker_emb_FecZarpe", "liqembarques", "emb_FecZarpe");
            $this->emb_CodPuerto = new clsControl(ccsListBox, "emb_CodPuerto", "Cód. de Puerto", ccsInteger, "", CCGetRequestParam("emb_CodPuerto", $Method));
            $this->emb_CodPuerto->DSType = dsTable;
            list($this->emb_CodPuerto->BoundColumn, $this->emb_CodPuerto->TextColumn, $this->emb_CodPuerto->DBFormat) = array("pue_CodPuerto", "pue_Descripcion", "");
            $this->emb_CodPuerto->ds = new clsDBdatos();
            $this->emb_CodPuerto->ds->SQL = "SELECT *  " .
"FROM genpuertos";
            $this->emb_CodPuerto->Required = true;
            $this->emb_CodPais = new clsControl(ccsListBox, "emb_CodPais", "País de Destino", ccsInteger, "", CCGetRequestParam("emb_CodPais", $Method));
            $this->emb_CodPais->DSType = dsTable;
            list($this->emb_CodPais->BoundColumn, $this->emb_CodPais->TextColumn, $this->emb_CodPais->DBFormat) = array("pai_CodPais", "pai_Descripcion", "");
            $this->emb_CodPais->ds = new clsDBdatos();
            $this->emb_CodPais->ds->SQL = "SELECT *  " .
"FROM genpaises";
            $this->emb_CodPais->Required = true;
            $this->emb_Destino = new clsControl(ccsListBox, "emb_Destino", "Puerto de Destino", ccsInteger, "", CCGetRequestParam("emb_Destino", $Method));
            $this->emb_Destino->DSType = dsTable;
            list($this->emb_Destino->BoundColumn, $this->emb_Destino->TextColumn, $this->emb_Destino->DBFormat) = array("pue_CodPuerto", "pue_Descripcion", "");
            $this->emb_Destino->ds = new clsDBdatos();
            $this->emb_Destino->ds->SQL = "SELECT *  " .
"FROM genpuertos";
            $this->emb_Destino->Required = true;
            $this->emb_Descripcion1 = new clsControl(ccsTextBox, "emb_Descripcion1", "Descripcion1", ccsText, "", CCGetRequestParam("emb_Descripcion1", $Method));
            $this->emb_Descripcion2 = new clsControl(ccsTextBox, "emb_Descripcion2", "Descripcion2", ccsText, "", CCGetRequestParam("emb_Descripcion2", $Method));
            $this->emb_CodProducto = new clsControl(ccsListBox, "emb_CodProducto", "Código de Producto", ccsInteger, "", CCGetRequestParam("emb_CodProducto", $Method));
            $this->emb_CodProducto->DSType = dsTable;
            list($this->emb_CodProducto->BoundColumn, $this->emb_CodProducto->TextColumn, $this->emb_CodProducto->DBFormat) = array("", "", "");
            $this->emb_CodProducto->ds = new clsDBdatos();
            $this->emb_CodProducto->ds->SQL = "SELECT act_CodAuxiliar, act_Descripcion, cat_Categoria, cat_Activo  " .
"FROM conactivos LEFT JOIN concategorias ON " .
"conactivos.act_CodAuxiliar = concategorias.cat_CodAuxiliar";
            $this->emb_CodProducto->ds->Parameters["expr530"] = 16;
            $this->emb_CodProducto->ds->Parameters["expr531"] = 0;
            $this->emb_CodProducto->ds->wp = new clsSQLParameters();
            $this->emb_CodProducto->ds->wp->AddParameter("1", "expr530", ccsInteger, "", "", $this->emb_CodProducto->ds->Parameters["expr530"], "", false);
            $this->emb_CodProducto->ds->wp->AddParameter("2", "expr531", ccsInteger, "", "", $this->emb_CodProducto->ds->Parameters["expr531"], "", false);
            $this->emb_CodProducto->ds->wp->Criterion[1] = $this->emb_CodProducto->ds->wp->Operation(opEqual, "cat_Categoria", $this->emb_CodProducto->ds->wp->GetDBValue("1"), $this->emb_CodProducto->ds->ToSQL($this->emb_CodProducto->ds->wp->GetDBValue("1"), ccsInteger),false);
            $this->emb_CodProducto->ds->wp->Criterion[2] = $this->emb_CodProducto->ds->wp->Operation(opGreaterThan, "cat_Activo", $this->emb_CodProducto->ds->wp->GetDBValue("2"), $this->emb_CodProducto->ds->ToSQL($this->emb_CodProducto->ds->wp->GetDBValue("2"), ccsInteger),false);
            $this->emb_CodProducto->ds->Where = $this->emb_CodProducto->ds->wp->opAND(false, $this->emb_CodProducto->ds->wp->Criterion[1], $this->emb_CodProducto->ds->wp->Criterion[2]);
            $this->emb_CodMarca = new clsControl(ccsTextBox, "emb_CodMarca", "Emb Cod Marca", ccsInteger, "", CCGetRequestParam("emb_CodMarca", $Method));
            $this->emb_CodMarca->Required = false;
            $this->txt_Marca = new clsControl(ccsTextBox, "txt_Marca", "Marca", ccsText, "", CCGetRequestParam("txt_Marca", $Method));
            $this->emb_Estado = new clsControl(ccsListBox, "emb_Estado", "Emb Estado", ccsInteger, "", CCGetRequestParam("emb_Estado", $Method));
            $this->emb_Estado->DSType = dsTable;
            list($this->emb_Estado->BoundColumn, $this->emb_Estado->TextColumn, $this->emb_Estado->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->emb_Estado->ds = new clsDBdatos();
            $this->emb_Estado->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->emb_Estado->ds->Parameters["expr570"] = LGESTA;
            $this->emb_Estado->ds->wp = new clsSQLParameters();
            $this->emb_Estado->ds->wp->AddParameter("1", "expr570", ccsText, "", "", $this->emb_Estado->ds->Parameters["expr570"], "", true);
            $this->emb_Estado->ds->wp->Criterion[1] = $this->emb_Estado->ds->wp->Operation(opEqual, "par_Clave", $this->emb_Estado->ds->wp->GetDBValue("1"), $this->emb_Estado->ds->ToSQL($this->emb_Estado->ds->wp->GetDBValue("1"), ccsText),true);
            $this->emb_Estado->ds->Where = $this->emb_Estado->ds->wp->Criterion[1];
            $this->emb_Estado->Required = true;
            $this->emb_CodCaja = new clsControl(ccsTextBox, "emb_CodCaja", "Emb Cod Caja", ccsInteger, "", CCGetRequestParam("emb_CodCaja", $Method));
            $this->emb_CodCaja->Required = false;
            $this->txt_Empaque = new clsControl(ccsTextBox, "txt_Empaque", "Empaque", ccsText, "", CCGetRequestParam("txt_Empaque", $Method));
            $this->emb_CodCompon1 = new clsControl(ccsTextBox, "emb_CodCompon1", "Emb Cod Compon1", ccsInteger, "", CCGetRequestParam("emb_CodCompon1", $Method));
            $this->emb_CodCompon1->Required = false;
            $this->txt_Compon1 = new clsControl(ccsTextBox, "txt_Compon1", "Componenete Carton", ccsText, "", CCGetRequestParam("txt_Compon1", $Method));
            $this->txt_Compon1->Required = false;
            $this->emb_CodCompon2 = new clsControl(ccsTextBox, "emb_CodCompon2", "Emb Cod Compon2", ccsInteger, "", CCGetRequestParam("emb_CodCompon2", $Method));
            $this->emb_CodCompon2->Required = false;
            $this->txt_Compon2 = new clsControl(ccsTextBox, "txt_Compon2", "Componente Platico", ccsText, "", CCGetRequestParam("txt_Compon2", $Method));
            $this->txt_Compon2->Required = false;
            $this->emb_CodCompon3 = new clsControl(ccsTextBox, "emb_CodCompon3", "Emb Cod Compon3", ccsInteger, "", CCGetRequestParam("emb_CodCompon3", $Method));
            $this->emb_CodCompon3->Required = false;
            $this->txt_Compon3 = new clsControl(ccsTextBox, "txt_Compon3", "Componente Materiales", ccsText, "", CCGetRequestParam("txt_Compon3", $Method));
            $this->txt_Compon3->Required = false;
            $this->emb_CodCompon4 = new clsControl(ccsTextBox, "emb_CodCompon4", "Emb Cod Compon4", ccsInteger, "", CCGetRequestParam("emb_CodCompon4", $Method));
            $this->emb_CodCompon4->Required = false;
            $this->txt_Compon4 = new clsControl(ccsTextBox, "txt_Compon4", "Componente Etiqueta", ccsText, "", CCGetRequestParam("txt_Compon4", $Method));
            $this->txt_Compon4->Required = false;
            $this->emb_PrecOficial = new clsControl(ccsTextBox, "emb_PrecOficial", "Precio oficial", ccsFloat, "", CCGetRequestParam("emb_PrecOficial", $Method));
            $this->emb_PrecOficial->Required = false;
            $this->emb_DifPrecio = new clsControl(ccsTextBox, "emb_DifPrecio", "Diferencia de Precio", ccsFloat, "", CCGetRequestParam("emb_DifPrecio", $Method));
            $this->emb_DifPrecio->Required = false;
            $this->emb_PreVenta = new clsControl(ccsTextBox, "emb_PreVenta", "Precio de Venta al Consignatario", ccsFloat, "", CCGetRequestParam("emb_PreVenta", $Method));
            $this->emb_PreVenta->Required = false;
            $this->emb_CodBuque = new clsControl(ccsHidden, "emb_CodBuque", "Emb Cod Buque", ccsInteger, "", CCGetRequestParam("emb_CodBuque", $Method));
            $this->Button_Insert = new clsButton("Button_Insert");
            $this->Button_Update = new clsButton("Button_Update");
            $this->Button_Delete = new clsButton("Button_Delete");
            $this->Button_Cancel = new clsButton("Button_Cancel");
            $this->btNUEVO = new clsButton("btNUEVO");
            $this->btBUSQUEDA = new clsButton("btBUSQUEDA");
            if(!$this->FormSubmitted) {
                if(!is_array($this->emb_Estado->Value) && !strlen($this->emb_Estado->Value) && $this->emb_Estado->Value !== false)
                $this->emb_Estado->SetText(1);
                if(!is_array($this->txt_Empaque->Value) && !strlen($this->txt_Empaque->Value) && $this->txt_Empaque->Value !== false)
                $this->txt_Empaque->SetText('');
                if(!is_array($this->txt_Compon1->Value) && !strlen($this->txt_Compon1->Value) && $this->txt_Compon1->Value !== false)
                $this->txt_Compon1->SetText('');
                if(!is_array($this->txt_Compon2->Value) && !strlen($this->txt_Compon2->Value) && $this->txt_Compon2->Value !== false)
                $this->txt_Compon2->SetText('');
                if(!is_array($this->txt_Compon3->Value) && !strlen($this->txt_Compon3->Value) && $this->txt_Compon3->Value !== false)
                $this->txt_Compon3->SetText('');
                if(!is_array($this->txt_Compon4->Value) && !strlen($this->txt_Compon4->Value) && $this->txt_Compon4->Value !== false)
                $this->txt_Compon4->SetText('');
                if(!is_array($this->emb_CodBuque->Value) && !strlen($this->emb_CodBuque->Value) && $this->emb_CodBuque->Value !== false)
                $this->emb_CodBuque->SetText(0);
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @2-D6343B86
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->ds->Parameters["urlemb_RefOperativa"] = CCGetFromGet("emb_RefOperativa", "");
        $this->ds->Parameters["expr231"] = IMARCA;
    }
//End Initialize Method

//Validate Method @2-6783B69A
    function Validate()
    {
        $Validation = true;
        $Where = "";
        if(! ($liqembarques->emb_SemFin >= $liqembarques->emb_SemInicio)) {
            $this->emb_SemTermino->Errors->addError("La Semana de Termino debe ser mayor que la de inicio");
        }
        $Validation = ($this->emb_RefOperativa->Validate() && $Validation);
        $Validation = ($this->emb_AnoOperacion->Validate() && $Validation);
        $Validation = ($this->emb_Consignatario->Validate() && $Validation);
        $Validation = ($this->txt_Consignat->Validate() && $Validation);
        $Validation = ($this->emb_CodVapor->Validate() && $Validation);
        $Validation = ($this->txt_Vapor->Validate() && $Validation);
        $Validation = ($this->emb_NumViaje->Validate() && $Validation);
        $Validation = ($this->emb_SemInicio->Validate() && $Validation);
        $Validation = ($this->emb_SemTermino->Validate() && $Validation);
        $Validation = ($this->emb_FecInicio->Validate() && $Validation);
        $Validation = ($this->emb_FecTermino->Validate() && $Validation);
        $Validation = ($this->emb_FecZarpe->Validate() && $Validation);
        $Validation = ($this->emb_CodPuerto->Validate() && $Validation);
        $Validation = ($this->emb_CodPais->Validate() && $Validation);
        $Validation = ($this->emb_Destino->Validate() && $Validation);
        $Validation = ($this->emb_Descripcion1->Validate() && $Validation);
        $Validation = ($this->emb_Descripcion2->Validate() && $Validation);
        $Validation = ($this->emb_CodProducto->Validate() && $Validation);
        $Validation = ($this->emb_CodMarca->Validate() && $Validation);
        $Validation = ($this->txt_Marca->Validate() && $Validation);
        $Validation = ($this->emb_Estado->Validate() && $Validation);
        $Validation = ($this->emb_CodCaja->Validate() && $Validation);
        $Validation = ($this->txt_Empaque->Validate() && $Validation);
        $Validation = ($this->emb_CodCompon1->Validate() && $Validation);
        $Validation = ($this->txt_Compon1->Validate() && $Validation);
        $Validation = ($this->emb_CodCompon2->Validate() && $Validation);
        $Validation = ($this->txt_Compon2->Validate() && $Validation);
        $Validation = ($this->emb_CodCompon3->Validate() && $Validation);
        $Validation = ($this->txt_Compon3->Validate() && $Validation);
        $Validation = ($this->emb_CodCompon4->Validate() && $Validation);
        $Validation = ($this->txt_Compon4->Validate() && $Validation);
        $Validation = ($this->emb_PrecOficial->Validate() && $Validation);
        $Validation = ($this->emb_DifPrecio->Validate() && $Validation);
        $Validation = ($this->emb_PreVenta->Validate() && $Validation);
        $Validation = ($this->emb_CodBuque->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-B95DAB49
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->emb_RefOperativa->Errors->Count());
        $errors = ($errors || $this->emb_AnoOperacion->Errors->Count());
        $errors = ($errors || $this->emb_Consignatario->Errors->Count());
        $errors = ($errors || $this->txt_Consignat->Errors->Count());
        $errors = ($errors || $this->emb_CodVapor->Errors->Count());
        $errors = ($errors || $this->txt_Vapor->Errors->Count());
        $errors = ($errors || $this->emb_NumViaje->Errors->Count());
        $errors = ($errors || $this->emb_SemInicio->Errors->Count());
        $errors = ($errors || $this->emb_SemTermino->Errors->Count());
        $errors = ($errors || $this->emb_FecInicio->Errors->Count());
        $errors = ($errors || $this->DatePicker_emb_FecInicio->Errors->Count());
        $errors = ($errors || $this->emb_FecTermino->Errors->Count());
        $errors = ($errors || $this->DatePicker_emb_FecTermino->Errors->Count());
        $errors = ($errors || $this->emb_FecZarpe->Errors->Count());
        $errors = ($errors || $this->DatePicker_emb_FecZarpe->Errors->Count());
        $errors = ($errors || $this->emb_CodPuerto->Errors->Count());
        $errors = ($errors || $this->emb_CodPais->Errors->Count());
        $errors = ($errors || $this->emb_Destino->Errors->Count());
        $errors = ($errors || $this->emb_Descripcion1->Errors->Count());
        $errors = ($errors || $this->emb_Descripcion2->Errors->Count());
        $errors = ($errors || $this->emb_CodProducto->Errors->Count());
        $errors = ($errors || $this->emb_CodMarca->Errors->Count());
        $errors = ($errors || $this->txt_Marca->Errors->Count());
        $errors = ($errors || $this->emb_Estado->Errors->Count());
        $errors = ($errors || $this->emb_CodCaja->Errors->Count());
        $errors = ($errors || $this->txt_Empaque->Errors->Count());
        $errors = ($errors || $this->emb_CodCompon1->Errors->Count());
        $errors = ($errors || $this->txt_Compon1->Errors->Count());
        $errors = ($errors || $this->emb_CodCompon2->Errors->Count());
        $errors = ($errors || $this->txt_Compon2->Errors->Count());
        $errors = ($errors || $this->emb_CodCompon3->Errors->Count());
        $errors = ($errors || $this->txt_Compon3->Errors->Count());
        $errors = ($errors || $this->emb_CodCompon4->Errors->Count());
        $errors = ($errors || $this->txt_Compon4->Errors->Count());
        $errors = ($errors || $this->emb_PrecOficial->Errors->Count());
        $errors = ($errors || $this->emb_DifPrecio->Errors->Count());
        $errors = ($errors || $this->emb_PreVenta->Errors->Count());
        $errors = ($errors || $this->emb_CodBuque->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-6FB7672C
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
            } else if(strlen(CCGetParam("btNUEVO", ""))) {
                $this->PressedButton = "btNUEVO";
            } else if(strlen(CCGetParam("btBUSQUEDA", ""))) {
                $this->PressedButton = "btBUSQUEDA";
            }
        }
        $Redirect = "LiEmPe_mant.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "Button_Delete") {
            if(!CCGetEvent($this->Button_Delete->CCSEvents, "OnClick") || !$this->DeleteRow()) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "Button_Cancel") {
            if(!CCGetEvent($this->Button_Cancel->CCSEvents, "OnClick")) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "btNUEVO") {
            if(!CCGetEvent($this->btNUEVO->CCSEvents, "OnClick")) {
                $Redirect = "";
            } else {
                $Redirect = "LiEmPe_mant.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm", "emb_RefOperativa"));
            }
        } else if($this->PressedButton == "btBUSQUEDA") {
            if(!CCGetEvent($this->btBUSQUEDA->CCSEvents, "OnClick")) {
                $Redirect = "";
            } else {
                $Redirect = "LiAdEm_search.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm", "emb_RefOperativa", "ccsForm", "s_keyword"));
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

//InsertRow Method @2-CDE38E3E
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert");
        if(!$this->InsertAllowed) return false;
        $this->ds->emb_AnoOperacion->SetValue($this->emb_AnoOperacion->GetValue());
        $this->ds->emb_Consignatario->SetValue($this->emb_Consignatario->GetValue());
        $this->ds->emb_CodVapor->SetValue($this->emb_CodVapor->GetValue());
        $this->ds->emb_NumViaje->SetValue($this->emb_NumViaje->GetValue());
        $this->ds->emb_SemInicio->SetValue($this->emb_SemInicio->GetValue());
        $this->ds->emb_SemTermino->SetValue($this->emb_SemTermino->GetValue());
        $this->ds->emb_FecInicio->SetValue($this->emb_FecInicio->GetValue());
        $this->ds->emb_FecTermino->SetValue($this->emb_FecTermino->GetValue());
        $this->ds->emb_FecZarpe->SetValue($this->emb_FecZarpe->GetValue());
        $this->ds->emb_CodPuerto->SetValue($this->emb_CodPuerto->GetValue());
        $this->ds->emb_CodPais->SetValue($this->emb_CodPais->GetValue());
        $this->ds->emb_Destino->SetValue($this->emb_Destino->GetValue());
        $this->ds->emb_Descripcion1->SetValue($this->emb_Descripcion1->GetValue());
        $this->ds->emb_Descripcion2->SetValue($this->emb_Descripcion2->GetValue());
        $this->ds->emb_CodProducto->SetValue($this->emb_CodProducto->GetValue());
        $this->ds->emb_CodMarca->SetValue($this->emb_CodMarca->GetValue());
        $this->ds->emb_Estado->SetValue($this->emb_Estado->GetValue());
        $this->ds->emb_CodCaja->SetValue($this->emb_CodCaja->GetValue());
        $this->ds->emb_CodCompon1->SetValue($this->emb_CodCompon1->GetValue());
        $this->ds->emb_CodCompon2->SetValue($this->emb_CodCompon2->GetValue());
        $this->ds->emb_CodCompon3->SetValue($this->emb_CodCompon3->GetValue());
        $this->ds->emb_CodCompon4->SetValue($this->emb_CodCompon4->GetValue());
        $this->ds->emb_PrecOficial->SetValue($this->emb_PrecOficial->GetValue());
        $this->ds->emb_DifPrecio->SetValue($this->emb_DifPrecio->GetValue());
        $this->ds->emb_PreVenta->SetValue($this->emb_PreVenta->GetValue());
        $this->ds->emb_CodBuque->SetValue($this->emb_CodBuque->GetValue());
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

//UpdateRow Method @2-B0393600
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate");
        if(!$this->UpdateAllowed) return false;
        $this->ds->emb_AnoOperacion->SetValue($this->emb_AnoOperacion->GetValue());
        $this->ds->emb_Consignatario->SetValue($this->emb_Consignatario->GetValue());
        $this->ds->emb_CodVapor->SetValue($this->emb_CodVapor->GetValue());
        $this->ds->emb_NumViaje->SetValue($this->emb_NumViaje->GetValue());
        $this->ds->emb_SemInicio->SetValue($this->emb_SemInicio->GetValue());
        $this->ds->emb_SemTermino->SetValue($this->emb_SemTermino->GetValue());
        $this->ds->emb_FecInicio->SetValue($this->emb_FecInicio->GetValue());
        $this->ds->emb_FecTermino->SetValue($this->emb_FecTermino->GetValue());
        $this->ds->emb_FecZarpe->SetValue($this->emb_FecZarpe->GetValue());
        $this->ds->emb_CodPuerto->SetValue($this->emb_CodPuerto->GetValue());
        $this->ds->emb_CodPais->SetValue($this->emb_CodPais->GetValue());
        $this->ds->emb_Destino->SetValue($this->emb_Destino->GetValue());
        $this->ds->emb_Descripcion1->SetValue($this->emb_Descripcion1->GetValue());
        $this->ds->emb_Descripcion2->SetValue($this->emb_Descripcion2->GetValue());
        $this->ds->emb_CodProducto->SetValue($this->emb_CodProducto->GetValue());
        $this->ds->emb_CodMarca->SetValue($this->emb_CodMarca->GetValue());
        $this->ds->emb_Estado->SetValue($this->emb_Estado->GetValue());
        $this->ds->emb_CodCaja->SetValue($this->emb_CodCaja->GetValue());
        $this->ds->emb_CodCompon1->SetValue($this->emb_CodCompon1->GetValue());
        $this->ds->emb_CodCompon2->SetValue($this->emb_CodCompon2->GetValue());
        $this->ds->emb_CodCompon3->SetValue($this->emb_CodCompon3->GetValue());
        $this->ds->emb_CodCompon4->SetValue($this->emb_CodCompon4->GetValue());
        $this->ds->emb_PrecOficial->SetValue($this->emb_PrecOficial->GetValue());
        $this->ds->emb_DifPrecio->SetValue($this->emb_DifPrecio->GetValue());
        $this->ds->emb_PreVenta->SetValue($this->emb_PreVenta->GetValue());
        $this->ds->emb_CodBuque->SetValue($this->emb_CodBuque->GetValue());
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

//Show Method @2-BDB81FAA
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->emb_CodPuerto->Prepare();
        $this->emb_CodPais->Prepare();
        $this->emb_Destino->Prepare();
        $this->emb_CodProducto->Prepare();
        $this->emb_Estado->Prepare();

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
                    echo "Error in Record liqembarques";
                }
                else if($this->ds->next_record())
                {
                    $this->ds->SetValues();
                    if(!$this->FormSubmitted)
                    {
                        $this->emb_RefOperativa->SetValue($this->ds->emb_RefOperativa->GetValue());
                        $this->emb_AnoOperacion->SetValue($this->ds->emb_AnoOperacion->GetValue());
                        $this->emb_Consignatario->SetValue($this->ds->emb_Consignatario->GetValue());
                        $this->txt_Consignat->SetValue($this->ds->txt_Consignat->GetValue());
                        $this->emb_CodVapor->SetValue($this->ds->emb_CodVapor->GetValue());
                        $this->txt_Vapor->SetValue($this->ds->txt_Vapor->GetValue());
                        $this->emb_NumViaje->SetValue($this->ds->emb_NumViaje->GetValue());
                        $this->emb_SemInicio->SetValue($this->ds->emb_SemInicio->GetValue());
                        $this->emb_SemTermino->SetValue($this->ds->emb_SemTermino->GetValue());
                        $this->emb_FecInicio->SetValue($this->ds->emb_FecInicio->GetValue());
                        $this->emb_FecTermino->SetValue($this->ds->emb_FecTermino->GetValue());
                        $this->emb_FecZarpe->SetValue($this->ds->emb_FecZarpe->GetValue());
                        $this->emb_CodPuerto->SetValue($this->ds->emb_CodPuerto->GetValue());
                        $this->emb_CodPais->SetValue($this->ds->emb_CodPais->GetValue());
                        $this->emb_Destino->SetValue($this->ds->emb_Destino->GetValue());
                        $this->emb_Descripcion1->SetValue($this->ds->emb_Descripcion1->GetValue());
                        $this->emb_Descripcion2->SetValue($this->ds->emb_Descripcion2->GetValue());
                        $this->emb_CodProducto->SetValue($this->ds->emb_CodProducto->GetValue());
                        $this->emb_CodMarca->SetValue($this->ds->emb_CodMarca->GetValue());
                        $this->txt_Marca->SetValue($this->ds->txt_Marca->GetValue());
                        $this->emb_Estado->SetValue($this->ds->emb_Estado->GetValue());
                        $this->emb_CodCaja->SetValue($this->ds->emb_CodCaja->GetValue());
                        $this->txt_Empaque->SetValue($this->ds->txt_Empaque->GetValue());
                        $this->emb_CodCompon1->SetValue($this->ds->emb_CodCompon1->GetValue());
                        $this->txt_Compon1->SetValue($this->ds->txt_Compon1->GetValue());
                        $this->emb_CodCompon2->SetValue($this->ds->emb_CodCompon2->GetValue());
                        $this->txt_Compon2->SetValue($this->ds->txt_Compon2->GetValue());
                        $this->emb_CodCompon3->SetValue($this->ds->emb_CodCompon3->GetValue());
                        $this->txt_Compon3->SetValue($this->ds->txt_Compon3->GetValue());
                        $this->emb_CodCompon4->SetValue($this->ds->emb_CodCompon4->GetValue());
                        $this->txt_Compon4->SetValue($this->ds->txt_Compon4->GetValue());
                        $this->emb_PrecOficial->SetValue($this->ds->emb_PrecOficial->GetValue());
                        $this->emb_DifPrecio->SetValue($this->ds->emb_DifPrecio->GetValue());
                        $this->emb_PreVenta->SetValue($this->ds->emb_PreVenta->GetValue());
                        $this->emb_CodBuque->SetValue($this->ds->emb_CodBuque->GetValue());
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
            $Error .= $this->emb_RefOperativa->Errors->ToString();
            $Error .= $this->emb_AnoOperacion->Errors->ToString();
            $Error .= $this->emb_Consignatario->Errors->ToString();
            $Error .= $this->txt_Consignat->Errors->ToString();
            $Error .= $this->emb_CodVapor->Errors->ToString();
            $Error .= $this->txt_Vapor->Errors->ToString();
            $Error .= $this->emb_NumViaje->Errors->ToString();
            $Error .= $this->emb_SemInicio->Errors->ToString();
            $Error .= $this->emb_SemTermino->Errors->ToString();
            $Error .= $this->emb_FecInicio->Errors->ToString();
            $Error .= $this->DatePicker_emb_FecInicio->Errors->ToString();
            $Error .= $this->emb_FecTermino->Errors->ToString();
            $Error .= $this->DatePicker_emb_FecTermino->Errors->ToString();
            $Error .= $this->emb_FecZarpe->Errors->ToString();
            $Error .= $this->DatePicker_emb_FecZarpe->Errors->ToString();
            $Error .= $this->emb_CodPuerto->Errors->ToString();
            $Error .= $this->emb_CodPais->Errors->ToString();
            $Error .= $this->emb_Destino->Errors->ToString();
            $Error .= $this->emb_Descripcion1->Errors->ToString();
            $Error .= $this->emb_Descripcion2->Errors->ToString();
            $Error .= $this->emb_CodProducto->Errors->ToString();
            $Error .= $this->emb_CodMarca->Errors->ToString();
            $Error .= $this->txt_Marca->Errors->ToString();
            $Error .= $this->emb_Estado->Errors->ToString();
            $Error .= $this->emb_CodCaja->Errors->ToString();
            $Error .= $this->txt_Empaque->Errors->ToString();
            $Error .= $this->emb_CodCompon1->Errors->ToString();
            $Error .= $this->txt_Compon1->Errors->ToString();
            $Error .= $this->emb_CodCompon2->Errors->ToString();
            $Error .= $this->txt_Compon2->Errors->ToString();
            $Error .= $this->emb_CodCompon3->Errors->ToString();
            $Error .= $this->txt_Compon3->Errors->ToString();
            $Error .= $this->emb_CodCompon4->Errors->ToString();
            $Error .= $this->txt_Compon4->Errors->ToString();
            $Error .= $this->emb_PrecOficial->Errors->ToString();
            $Error .= $this->emb_DifPrecio->Errors->ToString();
            $Error .= $this->emb_PreVenta->Errors->ToString();
            $Error .= $this->emb_CodBuque->Errors->ToString();
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

        $this->emb_RefOperativa->Show();
        $this->emb_AnoOperacion->Show();
        $this->emb_Consignatario->Show();
        $this->txt_Consignat->Show();
        $this->emb_CodVapor->Show();
        $this->txt_Vapor->Show();
        $this->emb_NumViaje->Show();
        $this->emb_SemInicio->Show();
        $this->emb_SemTermino->Show();
        $this->emb_FecInicio->Show();
        $this->DatePicker_emb_FecInicio->Show();
        $this->emb_FecTermino->Show();
        $this->DatePicker_emb_FecTermino->Show();
        $this->emb_FecZarpe->Show();
        $this->DatePicker_emb_FecZarpe->Show();
        $this->emb_CodPuerto->Show();
        $this->emb_CodPais->Show();
        $this->emb_Destino->Show();
        $this->emb_Descripcion1->Show();
        $this->emb_Descripcion2->Show();
        $this->emb_CodProducto->Show();
        $this->emb_CodMarca->Show();
        $this->txt_Marca->Show();
        $this->emb_Estado->Show();
        $this->emb_CodCaja->Show();
        $this->txt_Empaque->Show();
        $this->emb_CodCompon1->Show();
        $this->txt_Compon1->Show();
        $this->emb_CodCompon2->Show();
        $this->txt_Compon2->Show();
        $this->emb_CodCompon3->Show();
        $this->txt_Compon3->Show();
        $this->emb_CodCompon4->Show();
        $this->txt_Compon4->Show();
        $this->emb_PrecOficial->Show();
        $this->emb_DifPrecio->Show();
        $this->emb_PreVenta->Show();
        $this->emb_CodBuque->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->Button_Cancel->Show();
        $this->btNUEVO->Show();
        $this->btBUSQUEDA->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

} //End liqembarques Class @2-FCB6E20C

class clsliqembarquesDataSource extends clsDBdatos {  //liqembarquesDataSource Class @2-73D5D16D

//DataSource Variables @2-0B627C71
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $InsertParameters;
    var $UpdateParameters;
    var $DeleteParameters;
    var $wp;
    var $AllParametersSet;


    // Datasource fields
    var $emb_RefOperativa;
    var $emb_AnoOperacion;
    var $emb_Consignatario;
    var $txt_Consignat;
    var $emb_CodVapor;
    var $txt_Vapor;
    var $emb_NumViaje;
    var $emb_SemInicio;
    var $emb_SemTermino;
    var $emb_FecInicio;
    var $emb_FecTermino;
    var $emb_FecZarpe;
    var $emb_CodPuerto;
    var $emb_CodPais;
    var $emb_Destino;
    var $emb_Descripcion1;
    var $emb_Descripcion2;
    var $emb_CodProducto;
    var $emb_CodMarca;
    var $txt_Marca;
    var $emb_Estado;
    var $emb_CodCaja;
    var $txt_Empaque;
    var $emb_CodCompon1;
    var $txt_Compon1;
    var $emb_CodCompon2;
    var $txt_Compon2;
    var $emb_CodCompon3;
    var $txt_Compon3;
    var $emb_CodCompon4;
    var $txt_Compon4;
    var $emb_PrecOficial;
    var $emb_DifPrecio;
    var $emb_PreVenta;
    var $emb_CodBuque;
//End DataSource Variables

//Class_Initialize Event @2-06F9E2E1
    function clsliqembarquesDataSource()
    {
        $this->ErrorBlock = "Record liqembarques/Error";
        $this->Initialize();
        $this->emb_RefOperativa = new clsField("emb_RefOperativa", ccsText, "");
        $this->emb_AnoOperacion = new clsField("emb_AnoOperacion", ccsInteger, "");
        $this->emb_Consignatario = new clsField("emb_Consignatario", ccsInteger, "");
        $this->txt_Consignat = new clsField("txt_Consignat", ccsText, "");
        $this->emb_CodVapor = new clsField("emb_CodVapor", ccsInteger, "");
        $this->txt_Vapor = new clsField("txt_Vapor", ccsText, "");
        $this->emb_NumViaje = new clsField("emb_NumViaje", ccsInteger, "");
        $this->emb_SemInicio = new clsField("emb_SemInicio", ccsInteger, "");
        $this->emb_SemTermino = new clsField("emb_SemTermino", ccsInteger, "");
        $this->emb_FecInicio = new clsField("emb_FecInicio", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->emb_FecTermino = new clsField("emb_FecTermino", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->emb_FecZarpe = new clsField("emb_FecZarpe", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->emb_CodPuerto = new clsField("emb_CodPuerto", ccsInteger, "");
        $this->emb_CodPais = new clsField("emb_CodPais", ccsInteger, "");
        $this->emb_Destino = new clsField("emb_Destino", ccsInteger, "");
        $this->emb_Descripcion1 = new clsField("emb_Descripcion1", ccsText, "");
        $this->emb_Descripcion2 = new clsField("emb_Descripcion2", ccsText, "");
        $this->emb_CodProducto = new clsField("emb_CodProducto", ccsInteger, "");
        $this->emb_CodMarca = new clsField("emb_CodMarca", ccsInteger, "");
        $this->txt_Marca = new clsField("txt_Marca", ccsText, "");
        $this->emb_Estado = new clsField("emb_Estado", ccsInteger, "");
        $this->emb_CodCaja = new clsField("emb_CodCaja", ccsInteger, "");
        $this->txt_Empaque = new clsField("txt_Empaque", ccsText, "");
        $this->emb_CodCompon1 = new clsField("emb_CodCompon1", ccsInteger, "");
        $this->txt_Compon1 = new clsField("txt_Compon1", ccsText, "");
        $this->emb_CodCompon2 = new clsField("emb_CodCompon2", ccsInteger, "");
        $this->txt_Compon2 = new clsField("txt_Compon2", ccsText, "");
        $this->emb_CodCompon3 = new clsField("emb_CodCompon3", ccsInteger, "");
        $this->txt_Compon3 = new clsField("txt_Compon3", ccsText, "");
        $this->emb_CodCompon4 = new clsField("emb_CodCompon4", ccsInteger, "");
        $this->txt_Compon4 = new clsField("txt_Compon4", ccsText, "");
        $this->emb_PrecOficial = new clsField("emb_PrecOficial", ccsFloat, "");
        $this->emb_DifPrecio = new clsField("emb_DifPrecio", ccsFloat, "");
        $this->emb_PreVenta = new clsField("emb_PreVenta", ccsFloat, "");
        $this->emb_CodBuque = new clsField("emb_CodBuque", ccsInteger, "");

    }
//End Class_Initialize Event

//Prepare Method @2-9005CE6D
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlemb_RefOperativa", ccsInteger, "", "", $this->Parameters["urlemb_RefOperativa"], "", false);
        $this->wp->AddParameter("2", "expr231", ccsText, "", "", $this->Parameters["expr231"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "emb_RefOperativa", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opEqual, "par_Clave", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsText),false);
        $this->Where = $this->wp->opAND(false, $this->wp->Criterion[1], $this->wp->Criterion[2]);
    }
//End Prepare Method

//Open Method @2-3F099AB5
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->SQL = "SELECT liqembarques.*, per_Apellidos, per_Nombres, buq_Descripcion, ptoe.pue_Descripcion AS txt_puerto, pai_Descripcion AS txt_paisd, " .
        "ptod.pue_Descripcion AS txt_destino, par_Descripcion AS txt_Marca, caj_Descripcion AS txt_Empaque, com2.cte_Descripcion AS txt_Compon2, " .
        "com1.cte_Descripcion AS txt_Compon1, com3.cte_Descripcion AS txt_Compon3, com4.cte_Descripcion AS txt_Compon4, concat(per_Nombres, ' ', per_Apellidos) AS txt_Consignat  " .
        "FROM liqembarques LEFT JOIN liqbuques ON  liqembarques.emb_CodVapor = liqbuques.buq_CodBuque " .
            "LEFT JOIN conpersonas ON  liqembarques.emb_Consignatario = conpersonas.per_CodAuxiliar " .
            "LEFT JOIN genpaises paid ON liqembarques.emb_CodPais = paid.pai_CodPais " .
            "LEFT JOIN genpuertos ptod ON  liqembarques.emb_Destino = ptod.pue_CodPuerto " .
            "LEFT JOIN genpuertos ptoe ON  liqembarques.emb_CodPuerto = ptoe.pue_CodPuerto " .
            "LEFT JOIN genparametros marc ON  par_clave = 'IMARCA' and  liqembarques.emb_CodMarca = marc.par_Secuencia " .
            "LEFT JOIN liqcajas ON  liqembarques.emb_CodCaja = liqcajas.caj_CodCaja " .
            "LEFT JOIN liqcomponent com1 ON  liqembarques.emb_CodCompon1 = com1.cte_Codigo " .
            "LEFT JOIN liqcomponent com2 ON  liqembarques.emb_CodCompon2 = com2.cte_Codigo " .
            "LEFT JOIN liqcomponent com3 ON  liqembarques.emb_CodCompon3 = com3.cte_Codigo " .
            "LEFT JOIN liqcomponent com4 ON  liqembarques.emb_CodCompon4 = com4.cte_Codigo";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        if (CCGetParam("emb_RefOperativa", false)) $this->Where = "emb_RefOperativa = " . CCGetParam("emb_RefOperativa", false); // fah:ene 12 ^^
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
    
//End Open Method

//SetValues Method @2-D9DB7026
    function SetValues()
    {
        $this->emb_RefOperativa->SetDBValue($this->f("emb_RefOperativa"));
        $this->emb_AnoOperacion->SetDBValue(trim($this->f("emb_AnoOperacion")));
        $this->emb_Consignatario->SetDBValue(trim($this->f("emb_Consignatario")));
        $this->txt_Consignat->SetDBValue($this->f("txt_Consignat"));
        $this->emb_CodVapor->SetDBValue(trim($this->f("emb_CodVapor")));
        $this->txt_Vapor->SetDBValue($this->f("buq_Descripcion"));
        $this->emb_NumViaje->SetDBValue(trim($this->f("emb_NumViaje")));
        $this->emb_SemInicio->SetDBValue(trim($this->f("emb_SemInicio")));
        $this->emb_SemTermino->SetDBValue(trim($this->f("emb_SemTermino")));
        $this->emb_FecInicio->SetDBValue(trim($this->f("emb_FecInicio")));
        $this->emb_FecTermino->SetDBValue(trim($this->f("emb_FecTermino")));
        $this->emb_FecZarpe->SetDBValue(trim($this->f("emb_FecZarpe")));
        $this->emb_CodPuerto->SetDBValue(trim($this->f("emb_CodPuerto")));
        $this->emb_CodPais->SetDBValue(trim($this->f("emb_CodPais")));
        $this->emb_Destino->SetDBValue(trim($this->f("emb_Destino")));
        $this->emb_Descripcion1->SetDBValue($this->f("emb_Descripcion1"));
        $this->emb_Descripcion2->SetDBValue($this->f("emb_Descripcion2"));
        $this->emb_CodProducto->SetDBValue(trim($this->f("emb_CodProducto")));
        $this->emb_CodMarca->SetDBValue(trim($this->f("emb_CodMarca")));
        $this->txt_Marca->SetDBValue($this->f("txt_Marca"));
        $this->emb_Estado->SetDBValue(trim($this->f("emb_Estado")));
        $this->emb_CodCaja->SetDBValue(trim($this->f("emb_CodCaja")));
        $this->txt_Empaque->SetDBValue($this->f("txt_Empaque"));
        $this->emb_CodCompon1->SetDBValue(trim($this->f("emb_CodCompon1")));
        $this->txt_Compon1->SetDBValue($this->f("txt_Compon1"));
        $this->emb_CodCompon2->SetDBValue(trim($this->f("emb_CodCompon2")));
        $this->txt_Compon2->SetDBValue($this->f("txt_Compon2"));
        $this->emb_CodCompon3->SetDBValue(trim($this->f("emb_CodCompon3")));
        $this->txt_Compon3->SetDBValue($this->f("txt_Compon3"));
        $this->emb_CodCompon4->SetDBValue(trim($this->f("emb_CodCompon4")));
        $this->txt_Compon4->SetDBValue($this->f("txt_Compon4"));
        $this->emb_PrecOficial->SetDBValue(trim($this->f("emb_PrecOficial")));
        $this->emb_DifPrecio->SetDBValue(trim($this->f("emb_DifPrecio")));
        $this->emb_PreVenta->SetDBValue(trim($this->f("emb_PreVenta")));
        $this->emb_CodBuque->SetDBValue(trim($this->f("emb_CodBuque")));
    }
//End SetValues Method

//Insert Method @2-81745E18
    function Insert()
    {
        $this->cp["emb_AnoOperacion"] = new clsSQLParameter("ctrlemb_AnoOperacion", ccsInteger, "", "", $this->emb_AnoOperacion->GetValue(), 1, false, $this->ErrorBlock);
        $this->cp["emb_Consignatario"] = new clsSQLParameter("ctrlemb_Consignatario", ccsInteger, "", "", $this->emb_Consignatario->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["emb_CodVapor"] = new clsSQLParameter("ctrlemb_CodVapor", ccsInteger, "", "", $this->emb_CodVapor->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["emb_NumViaje"] = new clsSQLParameter("ctrlemb_NumViaje", ccsInteger, "", "", $this->emb_NumViaje->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["emb_SemInicio"] = new clsSQLParameter("ctrlemb_SemInicio", ccsInteger, "", "", $this->emb_SemInicio->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["emb_SemTermino"] = new clsSQLParameter("ctrlemb_SemTermino", ccsInteger, "", "", $this->emb_SemTermino->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["emb_FecInicio"] = new clsSQLParameter("ctrlemb_FecInicio", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->emb_FecInicio->GetValue(), 01/01/04, false, $this->ErrorBlock);
        $this->cp["emb_FecTermino"] = new clsSQLParameter("ctrlemb_FecTermino", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->emb_FecTermino->GetValue(), 01/01/04, false, $this->ErrorBlock);
        $this->cp["emb_FecZarpe"] = new clsSQLParameter("ctrlemb_FecZarpe", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->emb_FecZarpe->GetValue(), 01/01/04, false, $this->ErrorBlock);
        $this->cp["emb_CodPuerto"] = new clsSQLParameter("ctrlemb_CodPuerto", ccsInteger, "", "", $this->emb_CodPuerto->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["emb_CodPais"] = new clsSQLParameter("ctrlemb_CodPais", ccsInteger, "", "", $this->emb_CodPais->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["emb_Destino"] = new clsSQLParameter("ctrlemb_Destino", ccsInteger, "", "", $this->emb_Destino->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["emb_Descripcion1"] = new clsSQLParameter("ctrlemb_Descripcion1", ccsText, "", "", $this->emb_Descripcion1->GetValue(), '', false, $this->ErrorBlock);
        $this->cp["emb_Descripcion2"] = new clsSQLParameter("ctrlemb_Descripcion2", ccsText, "", "", $this->emb_Descripcion2->GetValue(), '', false, $this->ErrorBlock);
        $this->cp["emb_CodProducto"] = new clsSQLParameter("ctrlemb_CodProducto", ccsInteger, "", "", $this->emb_CodProducto->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["emb_CodMarca"] = new clsSQLParameter("ctrlemb_CodMarca", ccsInteger, "", "", $this->emb_CodMarca->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["emb_Estado"] = new clsSQLParameter("ctrlemb_Estado", ccsInteger, "", "", $this->emb_Estado->GetValue(), 1, false, $this->ErrorBlock);
        $this->cp["emb_CodCaja"] = new clsSQLParameter("ctrlemb_CodCaja", ccsInteger, "", "", $this->emb_CodCaja->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["emb_CodCompon1"] = new clsSQLParameter("ctrlemb_CodCompon1", ccsInteger, "", "", $this->emb_CodCompon1->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["emb_CodCompon2"] = new clsSQLParameter("ctrlemb_CodCompon2", ccsInteger, "", "", $this->emb_CodCompon2->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["emb_CodCompon3"] = new clsSQLParameter("ctrlemb_CodCompon3", ccsInteger, "", "", $this->emb_CodCompon3->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["emb_CodCompon4"] = new clsSQLParameter("ctrlemb_CodCompon4", ccsInteger, "", "", $this->emb_CodCompon4->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["emb_PrecOficial"] = new clsSQLParameter("ctrlemb_PrecOficial", ccsFloat, "", "", $this->emb_PrecOficial->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["emb_DifPrecio"] = new clsSQLParameter("ctrlemb_DifPrecio", ccsFloat, "", "", $this->emb_DifPrecio->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["emb_PreVenta"] = new clsSQLParameter("ctrlemb_PreVenta", ccsFloat, "", "", $this->emb_PreVenta->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["emb_CodBuque"] = new clsSQLParameter("ctrlemb_CodBuque", ccsInteger, "", "", $this->emb_CodBuque->GetValue(), 0, false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO liqembarques ("
             . "emb_AnoOperacion, "
             . "emb_Consignatario, "
             . "emb_CodVapor, "
             . "emb_NumViaje, "
             . "emb_SemInicio, "
             . "emb_SemTermino, "
             . "emb_FecInicio, "
             . "emb_FecTermino, "
             . "emb_FecZarpe, "
             . "emb_CodPuerto, "
             . "emb_CodPais, "
             . "emb_Destino, "
             . "emb_Descripcion1, "
             . "emb_Descripcion2, "
             . "emb_CodProducto, "
             . "emb_CodMarca, "
             . "emb_Estado, "
             . "emb_CodCaja, "
             . "emb_CodCompon1, "
             . "emb_CodCompon2, "
             . "emb_CodCompon3, "
             . "emb_CodCompon4, "
             . "emb_PrecOficial, "
             . "emb_DifPrecio, "
             . "emb_PreVenta, "
             . "emb_CodBuque"
             . ") VALUES ("
             . $this->ToSQL($this->cp["emb_AnoOperacion"]->GetDBValue(), $this->cp["emb_AnoOperacion"]->DataType) . ", "
             . $this->ToSQL($this->cp["emb_Consignatario"]->GetDBValue(), $this->cp["emb_Consignatario"]->DataType) . ", "
             . $this->ToSQL($this->cp["emb_CodVapor"]->GetDBValue(), $this->cp["emb_CodVapor"]->DataType) . ", "
             . $this->ToSQL($this->cp["emb_NumViaje"]->GetDBValue(), $this->cp["emb_NumViaje"]->DataType) . ", "
             . $this->ToSQL($this->cp["emb_SemInicio"]->GetDBValue(), $this->cp["emb_SemInicio"]->DataType) . ", "
             . $this->ToSQL($this->cp["emb_SemTermino"]->GetDBValue(), $this->cp["emb_SemTermino"]->DataType) . ", "
             . $this->ToSQL($this->cp["emb_FecInicio"]->GetDBValue(), $this->cp["emb_FecInicio"]->DataType) . ", "
             . $this->ToSQL($this->cp["emb_FecTermino"]->GetDBValue(), $this->cp["emb_FecTermino"]->DataType) . ", "
             . $this->ToSQL($this->cp["emb_FecZarpe"]->GetDBValue(), $this->cp["emb_FecZarpe"]->DataType) . ", "
             . $this->ToSQL($this->cp["emb_CodPuerto"]->GetDBValue(), $this->cp["emb_CodPuerto"]->DataType) . ", "
             . $this->ToSQL($this->cp["emb_CodPais"]->GetDBValue(), $this->cp["emb_CodPais"]->DataType) . ", "
             . $this->ToSQL($this->cp["emb_Destino"]->GetDBValue(), $this->cp["emb_Destino"]->DataType) . ", "
             . $this->ToSQL($this->cp["emb_Descripcion1"]->GetDBValue(), $this->cp["emb_Descripcion1"]->DataType) . ", "
             . $this->ToSQL($this->cp["emb_Descripcion2"]->GetDBValue(), $this->cp["emb_Descripcion2"]->DataType) . ", "
             . $this->ToSQL($this->cp["emb_CodProducto"]->GetDBValue(), $this->cp["emb_CodProducto"]->DataType) . ", "
             . $this->ToSQL($this->cp["emb_CodMarca"]->GetDBValue(), $this->cp["emb_CodMarca"]->DataType) . ", "
             . $this->ToSQL($this->cp["emb_Estado"]->GetDBValue(), $this->cp["emb_Estado"]->DataType) . ", "
             . $this->ToSQL($this->cp["emb_CodCaja"]->GetDBValue(), $this->cp["emb_CodCaja"]->DataType) . ", "
             . $this->ToSQL($this->cp["emb_CodCompon1"]->GetDBValue(), $this->cp["emb_CodCompon1"]->DataType) . ", "
             . $this->ToSQL($this->cp["emb_CodCompon2"]->GetDBValue(), $this->cp["emb_CodCompon2"]->DataType) . ", "
             . $this->ToSQL($this->cp["emb_CodCompon3"]->GetDBValue(), $this->cp["emb_CodCompon3"]->DataType) . ", "
             . $this->ToSQL($this->cp["emb_CodCompon4"]->GetDBValue(), $this->cp["emb_CodCompon4"]->DataType) . ", "
             . $this->ToSQL($this->cp["emb_PrecOficial"]->GetDBValue(), $this->cp["emb_PrecOficial"]->DataType) . ", "
             . $this->ToSQL($this->cp["emb_DifPrecio"]->GetDBValue(), $this->cp["emb_DifPrecio"]->DataType) . ", "
             . $this->ToSQL($this->cp["emb_PreVenta"]->GetDBValue(), $this->cp["emb_PreVenta"]->DataType) . ", "
             . $this->ToSQL($this->cp["emb_CodBuque"]->GetDBValue(), $this->cp["emb_CodBuque"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Insert Method

//Update Method @2-462B36F8
    function Update()
    {
        $this->cp["emb_AnoOperacion"] = new clsSQLParameter("ctrlemb_AnoOperacion", ccsInteger, "", "", $this->emb_AnoOperacion->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["emb_Consignatario"] = new clsSQLParameter("ctrlemb_Consignatario", ccsInteger, "", "", $this->emb_Consignatario->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["emb_CodVapor"] = new clsSQLParameter("ctrlemb_CodVapor", ccsInteger, "", "", $this->emb_CodVapor->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["emb_NumViaje"] = new clsSQLParameter("ctrlemb_NumViaje", ccsInteger, "", "", $this->emb_NumViaje->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["emb_SemInicio"] = new clsSQLParameter("ctrlemb_SemInicio", ccsInteger, "", "", $this->emb_SemInicio->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["emb_SemTermino"] = new clsSQLParameter("ctrlemb_SemTermino", ccsInteger, "", "", $this->emb_SemTermino->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["emb_FecInicio"] = new clsSQLParameter("ctrlemb_FecInicio", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->emb_FecInicio->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["emb_FecTermino"] = new clsSQLParameter("ctrlemb_FecTermino", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->emb_FecTermino->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["emb_FecZarpe"] = new clsSQLParameter("ctrlemb_FecZarpe", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->emb_FecZarpe->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["emb_CodPuerto"] = new clsSQLParameter("ctrlemb_CodPuerto", ccsInteger, "", "", $this->emb_CodPuerto->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["emb_CodPais"] = new clsSQLParameter("ctrlemb_CodPais", ccsInteger, "", "", $this->emb_CodPais->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["emb_Destino"] = new clsSQLParameter("ctrlemb_Destino", ccsInteger, "", "", $this->emb_Destino->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["emb_Descripcion1"] = new clsSQLParameter("ctrlemb_Descripcion1", ccsText, "", "", $this->emb_Descripcion1->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["emb_Descripcion2"] = new clsSQLParameter("ctrlemb_Descripcion2", ccsText, "", "", $this->emb_Descripcion2->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["emb_CodProducto"] = new clsSQLParameter("ctrlemb_CodProducto", ccsInteger, "", "", $this->emb_CodProducto->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["emb_CodMarca"] = new clsSQLParameter("ctrlemb_CodMarca", ccsInteger, "", "", $this->emb_CodMarca->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["emb_Estado"] = new clsSQLParameter("ctrlemb_Estado", ccsInteger, "", "", $this->emb_Estado->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["emb_CodCaja"] = new clsSQLParameter("ctrlemb_CodCaja", ccsInteger, "", "", $this->emb_CodCaja->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["emb_CodCompon1"] = new clsSQLParameter("ctrlemb_CodCompon1", ccsInteger, "", "", $this->emb_CodCompon1->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["emb_CodCompon2"] = new clsSQLParameter("ctrlemb_CodCompon2", ccsInteger, "", "", $this->emb_CodCompon2->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["emb_CodCompon3"] = new clsSQLParameter("ctrlemb_CodCompon3", ccsInteger, "", "", $this->emb_CodCompon3->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["emb_CodCompon4"] = new clsSQLParameter("ctrlemb_CodCompon4", ccsInteger, "", "", $this->emb_CodCompon4->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["emb_PrecOficial"] = new clsSQLParameter("ctrlemb_PrecOficial", ccsFloat, "", "", $this->emb_PrecOficial->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["emb_DifPrecio"] = new clsSQLParameter("ctrlemb_DifPrecio", ccsFloat, "", "", $this->emb_DifPrecio->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["emb_PreVenta"] = new clsSQLParameter("ctrlemb_PreVenta", ccsFloat, "", "", $this->emb_PreVenta->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["emb_CodBuque"] = new clsSQLParameter("ctrlemb_CodBuque", ccsInteger, "", "", $this->emb_CodBuque->GetValue(), "", false, $this->ErrorBlock);
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlemb_RefOperativa", ccsInteger, "", "", CCGetFromGet("emb_RefOperativa", ""), "", true);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $wp->Criterion[1] = $wp->Operation(opEqual, "emb_RefOperativa", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),true);
        $Where = $wp->Criterion[1];
        $this->SQL = "UPDATE liqembarques SET "
             . "emb_AnoOperacion=" . $this->ToSQL($this->cp["emb_AnoOperacion"]->GetDBValue(), $this->cp["emb_AnoOperacion"]->DataType) . ", "
             . "emb_Consignatario=" . $this->ToSQL($this->cp["emb_Consignatario"]->GetDBValue(), $this->cp["emb_Consignatario"]->DataType) . ", "
             . "emb_CodVapor=" . $this->ToSQL($this->cp["emb_CodVapor"]->GetDBValue(), $this->cp["emb_CodVapor"]->DataType) . ", "
             . "emb_NumViaje=" . $this->ToSQL($this->cp["emb_NumViaje"]->GetDBValue(), $this->cp["emb_NumViaje"]->DataType) . ", "
             . "emb_SemInicio=" . $this->ToSQL($this->cp["emb_SemInicio"]->GetDBValue(), $this->cp["emb_SemInicio"]->DataType) . ", "
             . "emb_SemTermino=" . $this->ToSQL($this->cp["emb_SemTermino"]->GetDBValue(), $this->cp["emb_SemTermino"]->DataType) . ", "
             . "emb_FecInicio=" . $this->ToSQL($this->cp["emb_FecInicio"]->GetDBValue(), $this->cp["emb_FecInicio"]->DataType) . ", "
             . "emb_FecTermino=" . $this->ToSQL($this->cp["emb_FecTermino"]->GetDBValue(), $this->cp["emb_FecTermino"]->DataType) . ", "
             . "emb_FecZarpe=" . $this->ToSQL($this->cp["emb_FecZarpe"]->GetDBValue(), $this->cp["emb_FecZarpe"]->DataType) . ", "
             . "emb_CodPuerto=" . $this->ToSQL($this->cp["emb_CodPuerto"]->GetDBValue(), $this->cp["emb_CodPuerto"]->DataType) . ", "
             . "emb_CodPais=" . $this->ToSQL($this->cp["emb_CodPais"]->GetDBValue(), $this->cp["emb_CodPais"]->DataType) . ", "
             . "emb_Destino=" . $this->ToSQL($this->cp["emb_Destino"]->GetDBValue(), $this->cp["emb_Destino"]->DataType) . ", "
             . "emb_Descripcion1=" . $this->ToSQL($this->cp["emb_Descripcion1"]->GetDBValue(), $this->cp["emb_Descripcion1"]->DataType) . ", "
             . "emb_Descripcion2=" . $this->ToSQL($this->cp["emb_Descripcion2"]->GetDBValue(), $this->cp["emb_Descripcion2"]->DataType) . ", "
             . "emb_CodProducto=" . $this->ToSQL($this->cp["emb_CodProducto"]->GetDBValue(), $this->cp["emb_CodProducto"]->DataType) . ", "
             . "emb_CodMarca=" . $this->ToSQL($this->cp["emb_CodMarca"]->GetDBValue(), $this->cp["emb_CodMarca"]->DataType) . ", "
             . "emb_Estado=" . $this->ToSQL($this->cp["emb_Estado"]->GetDBValue(), $this->cp["emb_Estado"]->DataType) . ", "
             . "emb_CodCaja=" . $this->ToSQL($this->cp["emb_CodCaja"]->GetDBValue(), $this->cp["emb_CodCaja"]->DataType) . ", "
             . "emb_CodCompon1=" . $this->ToSQL($this->cp["emb_CodCompon1"]->GetDBValue(), $this->cp["emb_CodCompon1"]->DataType) . ", "
             . "emb_CodCompon2=" . $this->ToSQL($this->cp["emb_CodCompon2"]->GetDBValue(), $this->cp["emb_CodCompon2"]->DataType) . ", "
             . "emb_CodCompon3=" . $this->ToSQL($this->cp["emb_CodCompon3"]->GetDBValue(), $this->cp["emb_CodCompon3"]->DataType) . ", "
             . "emb_CodCompon4=" . $this->ToSQL($this->cp["emb_CodCompon4"]->GetDBValue(), $this->cp["emb_CodCompon4"]->DataType) . ", "
             . "emb_PrecOficial=" . $this->ToSQL($this->cp["emb_PrecOficial"]->GetDBValue(), $this->cp["emb_PrecOficial"]->DataType) . ", "
             . "emb_DifPrecio=" . $this->ToSQL($this->cp["emb_DifPrecio"]->GetDBValue(), $this->cp["emb_DifPrecio"]->DataType) . ", "
             . "emb_PreVenta=" . $this->ToSQL($this->cp["emb_PreVenta"]->GetDBValue(), $this->cp["emb_PreVenta"]->DataType) . ", "
             . "emb_CodBuque=" . $this->ToSQL($this->cp["emb_CodBuque"]->GetDBValue(), $this->cp["emb_CodBuque"]->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Update Method

//Delete Method @2-9F2B3E4B
    function Delete()
    {
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlemb_RefOperativa", ccsInteger, "", "", CCGetFromGet("emb_RefOperativa", ""), "", true);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $wp->Criterion[1] = $wp->Operation(opEqual, "emb_RefOperativa", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),true);
        $Where = $wp->Criterion[1];
        $this->SQL = "DELETE FROM liqembarques";
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Delete Method

} //End liqembarquesDataSource Class @2-FCB6E20C



//Initialize Page @1-7F18C4B4
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

$FileName = "LiEmPe_mant.php";
$Redirect = "";
$TemplateFileName = "LiEmPe_mant.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-5A53581D
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
$liqembarques = new clsRecordliqembarques();
$liqembarques->Initialize();

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

//Execute Components @1-F4C05326
$Cabecera->Operations();
$liqembarques->Operation();
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

//Show Page @1-76B2B808
$Cabecera->Show("Cabecera");
$liqembarques->Show();
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
