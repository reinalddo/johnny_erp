<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @77-49BEB8FC
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

//Class_Initialize Event @2-C52D5351
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
            $this->emb_AnoOperacion = new clsControl(ccsTextBox, "emb_AnoOperacion", "emb_AnoOperacion", ccsInteger, "", CCGetRequestParam("emb_AnoOperacion", $Method));
            $this->emb_SemInicio = new clsControl(ccsTextBox, "emb_SemInicio", "emb_SemInicio", ccsInteger, "", CCGetRequestParam("emb_SemInicio", $Method));
            $this->emb_SemTermino = new clsControl(ccsTextBox, "emb_SemTermino", "emb_SemTermino", ccsInteger, "", CCGetRequestParam("emb_SemTermino", $Method));
            $this->emb_FecZarpe = new clsControl(ccsTextBox, "emb_FecZarpe", "emb_FecZarpe", ccsDate, Array("dd", "/", "mm", "/", "yy"), CCGetRequestParam("emb_FecZarpe", $Method));
            $this->DatePicker_s_emb_FecZarpe = new clsDatePicker("DatePicker_s_emb_FecZarpe", "liqembarquesSearch", "s_emb_FecZarpe");
            $this->emb_CodVapor = new clsControl(ccsListBox, "emb_CodVapor", "emb_CodVapor", ccsInteger, "", CCGetRequestParam("emb_CodVapor", $Method));
            $this->emb_CodVapor->DSType = dsTable;
            list($this->emb_CodVapor->BoundColumn, $this->emb_CodVapor->TextColumn, $this->emb_CodVapor->DBFormat) = array("buq_CodBuque", "buq_Descripcion", "");
            $this->emb_CodVapor->ds = new clsDBdatos();
            $this->emb_CodVapor->ds->SQL = "SELECT *  " .
"FROM liqbuques";
            $this->emb_CodVapor->ds->Order = "buq_Descripcion";
            $this->s_emb_NumViaje = new clsControl(ccsTextBox, "s_emb_NumViaje", "s_emb_NumViaje", ccsInteger, "", CCGetRequestParam("s_emb_NumViaje", $Method));
            $this->emb_Destino = new clsControl(ccsListBox, "emb_Destino", "emb_Destino", ccsInteger, "", CCGetRequestParam("emb_Destino", $Method));
            $this->emb_Destino->DSType = dsTable;
            list($this->emb_Destino->BoundColumn, $this->emb_Destino->TextColumn, $this->emb_Destino->DBFormat) = array("pai_CodPais", "pai_Descripcion", "");
            $this->emb_Destino->ds = new clsDBdatos();
            $this->emb_Destino->ds->SQL = "SELECT *  " .
"FROM genpaises";
            $this->emb_Destino->ds->Order = "pai_Descripcion";
            $this->emb_Consignatario = new clsControl(ccsListBox, "emb_Consignatario", "emb_Consignatario", ccsInteger, "", CCGetRequestParam("emb_Consignatario", $Method));
            $this->emb_Consignatario->DSType = dsSQL;
            list($this->emb_Consignatario->BoundColumn, $this->emb_Consignatario->TextColumn, $this->emb_Consignatario->DBFormat) = array("per_codauxiliar", "txt_nombre", "");
            $this->emb_Consignatario->ds = new clsDBdatos();
            $this->emb_Consignatario->ds->SQL = "SELECT per_codauxiliar, concat(left(per_Apellidos, 15), ' ', left(per_Nombres ,12)) as txt_nombre " .
            "FROM concategorias INNER JOIN conpersonas ON concategorias.cat_CodAuxiliar = conpersonas.per_CodAuxiliar " .
            "WHERE cat_Categoria = 50 " .
            "";
            $this->emb_Consignatario->ds->Order = "2";
            $this->emb_TipoVenta = new clsControl(ccsListBox, "emb_TipoVenta", "emb_TipoVenta", ccsInteger, "", CCGetRequestParam("emb_TipoVenta", $Method));
            $this->emb_TipoVenta->DSType = dsTable;
            list($this->emb_TipoVenta->BoundColumn, $this->emb_TipoVenta->TextColumn, $this->emb_TipoVenta->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->emb_TipoVenta->ds = new clsDBdatos();
            $this->emb_TipoVenta->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->emb_TipoVenta->ds->Parameters["expr71"] = 'ITTIVE';
            $this->emb_TipoVenta->ds->wp = new clsSQLParameters();
            $this->emb_TipoVenta->ds->wp->AddParameter("1", "expr71", ccsText, "", "", $this->emb_TipoVenta->ds->Parameters["expr71"], "", false);
            $this->emb_TipoVenta->ds->wp->Criterion[1] = $this->emb_TipoVenta->ds->wp->Operation(opEqual, "par_Clave", $this->emb_TipoVenta->ds->wp->GetDBValue("1"), $this->emb_TipoVenta->ds->ToSQL($this->emb_TipoVenta->ds->wp->GetDBValue("1"), ccsText),false);
            $this->emb_TipoVenta->ds->Where = $this->emb_TipoVenta->ds->wp->Criterion[1];
            $this->emb_FecInicio = new clsControl(ccsTextBox, "emb_FecInicio", "emb_FecInicio", ccsDate, Array("dd", "/", "mm", "/", "yy"), CCGetRequestParam("emb_FecInicio", $Method));
            $this->DatePicker_s_emb_FecInicio = new clsDatePicker("DatePicker_s_emb_FecInicio", "liqembarquesSearch", "s_emb_FecInicio");
            $this->emb_FecTermino = new clsControl(ccsTextBox, "emb_FecTermino", "emb_FecTermino", ccsDate, Array("dd", "/", "mm", "/", "yy"), CCGetRequestParam("emb_FecTermino", $Method));
            $this->DatePicker_s_emb_FecTermino = new clsDatePicker("DatePicker_s_emb_FecTermino", "liqembarquesSearch", "s_emb_FecTermino");
            $this->emb_Estado = new clsControl(ccsTextBox, "emb_Estado", "emb_Estado", ccsInteger, "", CCGetRequestParam("emb_Estado", $Method));
            $this->tac_NumLiquid = new clsControl(ccsTextBox, "tac_NumLiquid", "tac_NumLiquid", ccsText, "", CCGetRequestParam("tac_NumLiquid", $Method));
            $this->tac_Semana = new clsControl(ccsTextBox, "tac_Semana", "tac_Semana", ccsText, "", CCGetRequestParam("tac_Semana", $Method));
            $this->tac_Fecha = new clsControl(ccsTextBox, "tac_Fecha", "tac_Fecha", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("tac_Fecha", $Method));
            $this->DatePicker1 = new clsDatePicker("DatePicker1", "liqembarquesSearch", "s_tac_Fecha");
            $this->tac_Zona = new clsControl(ccsListBox, "tac_Zona", "tac_Zona", ccsText, "", CCGetRequestParam("tac_Zona", $Method));
            $this->tac_Zona->DSType = dsSQL;
            list($this->tac_Zona->BoundColumn, $this->tac_Zona->TextColumn, $this->tac_Zona->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->tac_Zona->ds = new clsDBdatos();
            $this->tac_Zona->ds->SQL = "SELECT par_Clave, par_Descripcion, par_Secuencia  " .
            "FROM genparametros " .
            "WHERE par_Clave = 'LSZON' " .
            "";
            $this->tac_Zona->ds->Order = "2";
            $this->tac_GrupLiquidacion = new clsControl(ccsListBox, "tac_GrupLiquidacion", "tac_GrupLiquidacion", ccsText, "", CCGetRequestParam("tac_GrupLiquidacion", $Method));
            $this->tac_GrupLiquidacion->DSType = dsSQL;
            list($this->tac_GrupLiquidacion->BoundColumn, $this->tac_GrupLiquidacion->TextColumn, $this->tac_GrupLiquidacion->DBFormat) = array("per_CodAuxiliar", "per_Nombres", "");
            $this->tac_GrupLiquidacion->ds = new clsDBdatos();
            $this->tac_GrupLiquidacion->ds->SQL = "SELECT concat(left(per_Apellidos,15) , ' ' , left(per_nombres,12)) as txt_nombre,  " .
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
            $this->tac_PueRecepcion = new clsControl(ccsListBox, "tac_PueRecepcion", "tac_PueRecepcion", ccsText, "", CCGetRequestParam("tac_PueRecepcion", $Method));
            $this->tac_PueRecepcion->DSType = dsTable;
            list($this->tac_PueRecepcion->BoundColumn, $this->tac_PueRecepcion->TextColumn, $this->tac_PueRecepcion->DBFormat) = array("pue_CodPuerto", "pue_Descripcion", "");
            $this->tac_PueRecepcion->ds = new clsDBdatos();
            $this->tac_PueRecepcion->ds->SQL = "SELECT *  " .
"FROM genpuertos";
            $this->tac_PueRecepcion->ds->Order = "pue_Descripcion";
            $this->tad_CodProducto = new clsControl(ccsListBox, "tad_CodProducto", "tad_CodProducto", ccsText, "", CCGetRequestParam("tad_CodProducto", $Method));
            $this->tad_CodProducto->DSType = dsSQL;
            list($this->tad_CodProducto->BoundColumn, $this->tad_CodProducto->TextColumn, $this->tad_CodProducto->DBFormat) = array("act_codauxiliar", "txt_descripcion", "");
            $this->tad_CodProducto->ds = new clsDBdatos();
            $this->tad_CodProducto->ds->SQL = "SELECT concat(act_Descripcion, ' ', act_Descripcion1) as txt_descripcion, act_codauxiliar  " .
            "FROM concategorias INNER JOIN conactivos ON concategorias.cat_CodAuxiliar = conactivos.act_CodAuxiliar " .
            "WHERE cat_Categoria = 16 ";
            $this->tad_CodProducto->ds->Order = "1";
            $this->tad_CodMarca = new clsControl(ccsListBox, "tad_CodMarca", "tad_CodMarca", ccsText, "", CCGetRequestParam("tad_CodMarca", $Method));
            $this->tad_CodMarca->DSType = dsTable;
            list($this->tad_CodMarca->BoundColumn, $this->tad_CodMarca->TextColumn, $this->tad_CodMarca->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->tad_CodMarca->ds = new clsDBdatos();
            $this->tad_CodMarca->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->tad_CodMarca->ds->Order = "par_Descripcion";
            $this->tad_CodMarca->ds->Parameters["expr55"] = 'IMARCA';
            $this->tad_CodMarca->ds->Parameters["expr58"] = 1;
            $this->tad_CodMarca->ds->wp = new clsSQLParameters();
            $this->tad_CodMarca->ds->wp->AddParameter("1", "expr55", ccsText, "", "", $this->tad_CodMarca->ds->Parameters["expr55"], "", false);
            $this->tad_CodMarca->ds->wp->AddParameter("2", "expr58", ccsText, "", "", $this->tad_CodMarca->ds->Parameters["expr58"], "", false);
            $this->tad_CodMarca->ds->wp->Criterion[1] = $this->tad_CodMarca->ds->wp->Operation(opEqual, "par_Clave", $this->tad_CodMarca->ds->wp->GetDBValue("1"), $this->tad_CodMarca->ds->ToSQL($this->tad_CodMarca->ds->wp->GetDBValue("1"), ccsText),false);
            $this->tad_CodMarca->ds->wp->Criterion[2] = $this->tad_CodMarca->ds->wp->Operation(opEqual, "par_Valor3", $this->tad_CodMarca->ds->wp->GetDBValue("2"), $this->tad_CodMarca->ds->ToSQL($this->tad_CodMarca->ds->wp->GetDBValue("2"), ccsText),false);
            $this->tad_CodMarca->ds->Where = $this->tad_CodMarca->ds->wp->opAND(false, $this->tad_CodMarca->ds->wp->Criterion[1], $this->tad_CodMarca->ds->wp->Criterion[2]);
            $this->tad_CodCaja = new clsControl(ccsListBox, "tad_CodCaja", "tad_CodCaja", ccsText, "", CCGetRequestParam("tad_CodCaja", $Method));
            $this->tad_CodCaja->DSType = dsTable;
            list($this->tad_CodCaja->BoundColumn, $this->tad_CodCaja->TextColumn, $this->tad_CodCaja->DBFormat) = array("caj_CodCaja", "caj_Descripcion", "");
            $this->tad_CodCaja->ds = new clsDBdatos();
            $this->tad_CodCaja->ds->SQL = "SELECT *  " .
"FROM liqcajas";
            $this->tad_CodCaja->ds->Order = "caj_Descripcion";
            $this->tad_Observaciones = new clsControl(ccsTextBox, "tad_Observaciones", "tad_Observaciones", ccsText, "", CCGetRequestParam("tad_Observaciones", $Method));
        }
    }
//End Class_Initialize Event

//Validate Method @2-9C38F361
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->emb_AnoOperacion->Validate() && $Validation);
        $Validation = ($this->emb_SemInicio->Validate() && $Validation);
        $Validation = ($this->emb_SemTermino->Validate() && $Validation);
        $Validation = ($this->emb_FecZarpe->Validate() && $Validation);
        $Validation = ($this->emb_CodVapor->Validate() && $Validation);
        $Validation = ($this->s_emb_NumViaje->Validate() && $Validation);
        $Validation = ($this->emb_Destino->Validate() && $Validation);
        $Validation = ($this->emb_Consignatario->Validate() && $Validation);
        $Validation = ($this->emb_TipoVenta->Validate() && $Validation);
        $Validation = ($this->emb_FecInicio->Validate() && $Validation);
        $Validation = ($this->emb_FecTermino->Validate() && $Validation);
        $Validation = ($this->emb_Estado->Validate() && $Validation);
        $Validation = ($this->tac_NumLiquid->Validate() && $Validation);
        $Validation = ($this->tac_Semana->Validate() && $Validation);
        $Validation = ($this->tac_Fecha->Validate() && $Validation);
        $Validation = ($this->tac_Zona->Validate() && $Validation);
        $Validation = ($this->tac_GrupLiquidacion->Validate() && $Validation);
        $Validation = ($this->tac_Embarcador->Validate() && $Validation);
        $Validation = ($this->tac_UniProduccion->Validate() && $Validation);
        $Validation = ($this->tac_PueRecepcion->Validate() && $Validation);
        $Validation = ($this->tad_CodProducto->Validate() && $Validation);
        $Validation = ($this->tad_CodMarca->Validate() && $Validation);
        $Validation = ($this->tad_CodCaja->Validate() && $Validation);
        $Validation = ($this->tad_Observaciones->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-3801234A
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->emb_AnoOperacion->Errors->Count());
        $errors = ($errors || $this->emb_SemInicio->Errors->Count());
        $errors = ($errors || $this->emb_SemTermino->Errors->Count());
        $errors = ($errors || $this->emb_FecZarpe->Errors->Count());
        $errors = ($errors || $this->DatePicker_s_emb_FecZarpe->Errors->Count());
        $errors = ($errors || $this->emb_CodVapor->Errors->Count());
        $errors = ($errors || $this->s_emb_NumViaje->Errors->Count());
        $errors = ($errors || $this->emb_Destino->Errors->Count());
        $errors = ($errors || $this->emb_Consignatario->Errors->Count());
        $errors = ($errors || $this->emb_TipoVenta->Errors->Count());
        $errors = ($errors || $this->emb_FecInicio->Errors->Count());
        $errors = ($errors || $this->DatePicker_s_emb_FecInicio->Errors->Count());
        $errors = ($errors || $this->emb_FecTermino->Errors->Count());
        $errors = ($errors || $this->DatePicker_s_emb_FecTermino->Errors->Count());
        $errors = ($errors || $this->emb_Estado->Errors->Count());
        $errors = ($errors || $this->tac_NumLiquid->Errors->Count());
        $errors = ($errors || $this->tac_Semana->Errors->Count());
        $errors = ($errors || $this->tac_Fecha->Errors->Count());
        $errors = ($errors || $this->DatePicker1->Errors->Count());
        $errors = ($errors || $this->tac_Zona->Errors->Count());
        $errors = ($errors || $this->tac_GrupLiquidacion->Errors->Count());
        $errors = ($errors || $this->tac_Embarcador->Errors->Count());
        $errors = ($errors || $this->tac_UniProduccion->Errors->Count());
        $errors = ($errors || $this->tac_PueRecepcion->Errors->Count());
        $errors = ($errors || $this->tad_CodProducto->Errors->Count());
        $errors = ($errors || $this->tad_CodMarca->Errors->Count());
        $errors = ($errors || $this->tad_CodCaja->Errors->Count());
        $errors = ($errors || $this->tad_Observaciones->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-404D2FE2
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        $this->EditMode = false;
        if(!$this->FormSubmitted)
            return;

        $Redirect = "LiEmTj_repcond.php";
    }
//End Operation Method

//Show Method @2-FCD06EA7
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->emb_CodVapor->Prepare();
        $this->emb_Destino->Prepare();
        $this->emb_Consignatario->Prepare();
        $this->emb_TipoVenta->Prepare();
        $this->tac_Zona->Prepare();
        $this->tac_GrupLiquidacion->Prepare();
        $this->tac_Embarcador->Prepare();
        $this->tac_UniProduccion->Prepare();
        $this->tac_PueRecepcion->Prepare();
        $this->tad_CodProducto->Prepare();
        $this->tad_CodMarca->Prepare();
        $this->tad_CodCaja->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        if(!$this->FormSubmitted)
        {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->emb_AnoOperacion->Errors->ToString();
            $Error .= $this->emb_SemInicio->Errors->ToString();
            $Error .= $this->emb_SemTermino->Errors->ToString();
            $Error .= $this->emb_FecZarpe->Errors->ToString();
            $Error .= $this->DatePicker_s_emb_FecZarpe->Errors->ToString();
            $Error .= $this->emb_CodVapor->Errors->ToString();
            $Error .= $this->s_emb_NumViaje->Errors->ToString();
            $Error .= $this->emb_Destino->Errors->ToString();
            $Error .= $this->emb_Consignatario->Errors->ToString();
            $Error .= $this->emb_TipoVenta->Errors->ToString();
            $Error .= $this->emb_FecInicio->Errors->ToString();
            $Error .= $this->DatePicker_s_emb_FecInicio->Errors->ToString();
            $Error .= $this->emb_FecTermino->Errors->ToString();
            $Error .= $this->DatePicker_s_emb_FecTermino->Errors->ToString();
            $Error .= $this->emb_Estado->Errors->ToString();
            $Error .= $this->tac_NumLiquid->Errors->ToString();
            $Error .= $this->tac_Semana->Errors->ToString();
            $Error .= $this->tac_Fecha->Errors->ToString();
            $Error .= $this->DatePicker1->Errors->ToString();
            $Error .= $this->tac_Zona->Errors->ToString();
            $Error .= $this->tac_GrupLiquidacion->Errors->ToString();
            $Error .= $this->tac_Embarcador->Errors->ToString();
            $Error .= $this->tac_UniProduccion->Errors->ToString();
            $Error .= $this->tac_PueRecepcion->Errors->ToString();
            $Error .= $this->tad_CodProducto->Errors->ToString();
            $Error .= $this->tad_CodMarca->Errors->ToString();
            $Error .= $this->tad_CodCaja->Errors->ToString();
            $Error .= $this->tad_Observaciones->Errors->ToString();
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

        $this->emb_AnoOperacion->Show();
        $this->emb_SemInicio->Show();
        $this->emb_SemTermino->Show();
        $this->emb_FecZarpe->Show();
        $this->DatePicker_s_emb_FecZarpe->Show();
        $this->emb_CodVapor->Show();
        $this->s_emb_NumViaje->Show();
        $this->emb_Destino->Show();
        $this->emb_Consignatario->Show();
        $this->emb_TipoVenta->Show();
        $this->emb_FecInicio->Show();
        $this->DatePicker_s_emb_FecInicio->Show();
        $this->emb_FecTermino->Show();
        $this->DatePicker_s_emb_FecTermino->Show();
        $this->emb_Estado->Show();
        $this->tac_NumLiquid->Show();
        $this->tac_Semana->Show();
        $this->tac_Fecha->Show();
        $this->DatePicker1->Show();
        $this->tac_Zona->Show();
        $this->tac_GrupLiquidacion->Show();
        $this->tac_Embarcador->Show();
        $this->tac_UniProduccion->Show();
        $this->tac_PueRecepcion->Show();
        $this->tad_CodProducto->Show();
        $this->tad_CodMarca->Show();
        $this->tad_CodCaja->Show();
        $this->tad_Observaciones->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End liqembarquesSearch Class @2-FCB6E20C

//Initialize Page @1-9556C87A
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

$FileName = "LiEmTj_repcond.php";
$Redirect = "";
$TemplateFileName = "LiEmTj_repcond.html";
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
