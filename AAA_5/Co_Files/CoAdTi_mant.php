<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @88-49BEB8FC
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation



Class clsRecordCoAdTi_man { //CoAdTi_man Class @37-98C02EBE

//Variables @37-4A82E0A3

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

//Class_Initialize Event @37-1B0D5087
    function clsRecordCoAdTi_man()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record CoAdTi_man/Error";
        $this->ds = new clsCoAdTi_manDataSource();
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "CoAdTi_man";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->lbTitulo = new clsControl(ccsLabel, "lbTitulo", "lbTitulo", ccsText, "", CCGetRequestParam("lbTitulo", $Method));
            $this->cla_aplicacion = new clsControl(ccsListBox, "cla_aplicacion", "Cla Aplicacion", ccsText, "", CCGetRequestParam("cla_aplicacion", $Method));
            $this->cla_aplicacion->DSType = dsTable;
            list($this->cla_aplicacion->BoundColumn, $this->cla_aplicacion->TextColumn, $this->cla_aplicacion->DBFormat) = array("mod_subsistema", "mod_descripcion", "");
            $this->cla_aplicacion->ds = new clsDBSeguridad();
            $this->cla_aplicacion->ds->SQL = "SELECT *  " .
"FROM segmodulos";
            $this->cla_aplicacion->ds->Parameters["expr85"] = 'ooo';
            $this->cla_aplicacion->ds->wp = new clsSQLParameters();
            $this->cla_aplicacion->ds->wp->AddParameter("1", "expr85", ccsText, "", "", $this->cla_aplicacion->ds->Parameters["expr85"], "", false);
            $this->cla_aplicacion->ds->wp->Criterion[1] = $this->cla_aplicacion->ds->wp->Operation(opEqual, "mod_modulo", $this->cla_aplicacion->ds->wp->GetDBValue("1"), $this->cla_aplicacion->ds->ToSQL($this->cla_aplicacion->ds->wp->GetDBValue("1"), ccsText),false);
            $this->cla_aplicacion->ds->Where = $this->cla_aplicacion->ds->wp->Criterion[1];
            $this->cla_aplicacion->Required = true;
            $this->cla_tipoComp = new clsControl(ccsTextBox, "cla_tipoComp", "Cla Tipo Comp", ccsText, "", CCGetRequestParam("cla_tipoComp", $Method));
            $this->cla_tipoComp->Required = true;
            $this->cla_Descripcion = new clsControl(ccsTextBox, "cla_Descripcion", "Cla Descripcion", ccsText, "", CCGetRequestParam("cla_Descripcion", $Method));
            $this->cla_Formulario = new clsControl(ccsTextBox, "cla_Formulario", "Cla Formulario", ccsText, "", CCGetRequestParam("cla_Formulario", $Method));
            $this->cla_Informe = new clsControl(ccsTextBox, "cla_Informe", "Cla Informe", ccsText, "", CCGetRequestParam("cla_Informe", $Method));
            $this->cla_ReqEmisor = new clsControl(ccsListBox, "cla_ReqEmisor", "Cla Req Emisor", ccsInteger, "", CCGetRequestParam("cla_ReqEmisor", $Method));
            $this->cla_ReqEmisor->DSType = dsListOfValues;
            $this->cla_ReqEmisor->Values = array(array("0", "No"), array("1", "Si"));
            $this->cla_ReqEmisor->Required = true;
            $this->cla_TxtEmisor = new clsControl(ccsTextBox, "cla_TxtEmisor", "Cla Txt Emisor", ccsText, "", CCGetRequestParam("cla_TxtEmisor", $Method));
            $this->cla_ReqReceptor = new clsControl(ccsListBox, "cla_ReqReceptor", "Cla Req Receptor", ccsInteger, "", CCGetRequestParam("cla_ReqReceptor", $Method));
            $this->cla_ReqReceptor->DSType = dsListOfValues;
            $this->cla_ReqReceptor->Values = array(array("0", "No"), array("1", "Si"));
            $this->cla_ReqReceptor->Required = true;
            $this->cla_TxtReceptor = new clsControl(ccsTextBox, "cla_TxtReceptor", "Cla Txt Receptor", ccsText, "", CCGetRequestParam("cla_TxtReceptor", $Method));
            $this->cla_TipoEmisor = new clsControl(ccsListBox, "cla_TipoEmisor", "Cla Tipo Emisor", ccsInteger, "", CCGetRequestParam("cla_TipoEmisor", $Method));
            $this->cla_TipoEmisor->DSType = dsTable;
            list($this->cla_TipoEmisor->BoundColumn, $this->cla_TipoEmisor->TextColumn, $this->cla_TipoEmisor->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->cla_TipoEmisor->ds = new clsDBdatos();
            $this->cla_TipoEmisor->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->cla_TipoEmisor->ds->Parameters["expr80"] = 'CAUTI';
            $this->cla_TipoEmisor->ds->wp = new clsSQLParameters();
            $this->cla_TipoEmisor->ds->wp->AddParameter("1", "expr80", ccsText, "", "", $this->cla_TipoEmisor->ds->Parameters["expr80"], "", false);
            $this->cla_TipoEmisor->ds->wp->Criterion[1] = $this->cla_TipoEmisor->ds->wp->Operation(opEqual, "par_Clave", $this->cla_TipoEmisor->ds->wp->GetDBValue("1"), $this->cla_TipoEmisor->ds->ToSQL($this->cla_TipoEmisor->ds->wp->GetDBValue("1"), ccsText),false);
            $this->cla_TipoEmisor->ds->Where = $this->cla_TipoEmisor->ds->wp->Criterion[1];
            $this->cla_TipoEmisor->Required = true;
            $this->cla_EmiDefault = new clsControl(ccsTextBox, "cla_EmiDefault", "Cla Emi Default", ccsInteger, "", CCGetRequestParam("cla_EmiDefault", $Method));
            $this->cla_EmiDefault->Required = true;
            $this->cla_TipoReceptor = new clsControl(ccsListBox, "cla_TipoReceptor", "Cla Tipo Receptor", ccsInteger, "", CCGetRequestParam("cla_TipoReceptor", $Method));
            $this->cla_TipoReceptor->DSType = dsTable;
            list($this->cla_TipoReceptor->BoundColumn, $this->cla_TipoReceptor->TextColumn, $this->cla_TipoReceptor->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->cla_TipoReceptor->ds = new clsDBdatos();
            $this->cla_TipoReceptor->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->cla_TipoReceptor->ds->Parameters["expr81"] = 'CAUTI';
            $this->cla_TipoReceptor->ds->wp = new clsSQLParameters();
            $this->cla_TipoReceptor->ds->wp->AddParameter("1", "expr81", ccsText, "", "", $this->cla_TipoReceptor->ds->Parameters["expr81"], "", false);
            $this->cla_TipoReceptor->ds->wp->Criterion[1] = $this->cla_TipoReceptor->ds->wp->Operation(opEqual, "par_Clave", $this->cla_TipoReceptor->ds->wp->GetDBValue("1"), $this->cla_TipoReceptor->ds->ToSQL($this->cla_TipoReceptor->ds->wp->GetDBValue("1"), ccsText),false);
            $this->cla_TipoReceptor->ds->Where = $this->cla_TipoReceptor->ds->wp->Criterion[1];
            $this->cla_TipoReceptor->Required = true;
            $this->cla_RecDefault = new clsControl(ccsTextBox, "cla_RecDefault", "Cla Rec Default", ccsInteger, "", CCGetRequestParam("cla_RecDefault", $Method));
            $this->cla_RecDefault->Required = true;
            $this->cla_IndCheque = new clsControl(ccsListBox, "cla_IndCheque", "Cla Ind Cheque", ccsInteger, "", CCGetRequestParam("cla_IndCheque", $Method));
            $this->cla_IndCheque->DSType = dsListOfValues;
            $this->cla_IndCheque->Values = array(array("0", "No"), array("1", "Si"));
            $this->cla_IndCheque->Required = true;
            $this->cla_Indicador = new clsControl(ccsListBox, "cla_Indicador", "Cla Indicador", ccsInteger, "", CCGetRequestParam("cla_Indicador", $Method));
            $this->cla_Indicador->DSType = dsTable;
            list($this->cla_Indicador->BoundColumn, $this->cla_Indicador->TextColumn, $this->cla_Indicador->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->cla_Indicador->ds = new clsDBdatos();
            $this->cla_Indicador->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->cla_Indicador->ds->Parameters["expr82"] = 'LGESTA';
            $this->cla_Indicador->ds->wp = new clsSQLParameters();
            $this->cla_Indicador->ds->wp->AddParameter("1", "expr82", ccsText, "", "", $this->cla_Indicador->ds->Parameters["expr82"], "", false);
            $this->cla_Indicador->ds->wp->Criterion[1] = $this->cla_Indicador->ds->wp->Operation(opEqual, "par_Clave", $this->cla_Indicador->ds->wp->GetDBValue("1"), $this->cla_Indicador->ds->ToSQL($this->cla_Indicador->ds->wp->GetDBValue("1"), ccsText),false);
            $this->cla_Indicador->ds->Where = $this->cla_Indicador->ds->wp->Criterion[1];
            $this->cla_Indicador->Required = true;
            $this->cla_Contabilizacion = new clsControl(ccsListBox, "cla_Contabilizacion", "Cla Contabilizacion", ccsInteger, "", CCGetRequestParam("cla_Contabilizacion", $Method));
            $this->cla_Contabilizacion->DSType = dsListOfValues;
            $this->cla_Contabilizacion->Values = array(array("0", "No"), array("1", "Si"));
            $this->cla_Contabilizacion->Required = true;
            $this->cla_IndTransfer = new clsControl(ccsListBox, "cla_IndTransfer", "Cla Ind Transfer", ccsInteger, "", CCGetRequestParam("cla_IndTransfer", $Method));
            $this->cla_IndTransfer->DSType = dsListOfValues;
            $this->cla_IndTransfer->Values = array(array("0", "No"), array("1", "Si"));
            $this->cla_IndTransfer->Required = true;
            $this->cla_LisPrecios = new clsControl(ccsListBox, "cla_LisPrecios", "Cla Lis Precios", ccsInteger, "", CCGetRequestParam("cla_LisPrecios", $Method));
            $this->cla_LisPrecios->DSType = dsTable;
            list($this->cla_LisPrecios->BoundColumn, $this->cla_LisPrecios->TextColumn, $this->cla_LisPrecios->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->cla_LisPrecios->ds = new clsDBdatos();
            $this->cla_LisPrecios->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->cla_LisPrecios->ds->Parameters["expr83"] = 'ITRLIS';
            $this->cla_LisPrecios->ds->wp = new clsSQLParameters();
            $this->cla_LisPrecios->ds->wp->AddParameter("1", "expr83", ccsText, "", "", $this->cla_LisPrecios->ds->Parameters["expr83"], "", false);
            $this->cla_LisPrecios->ds->wp->Criterion[1] = $this->cla_LisPrecios->ds->wp->Operation(opEqual, "par_Clave", $this->cla_LisPrecios->ds->wp->GetDBValue("1"), $this->cla_LisPrecios->ds->ToSQL($this->cla_LisPrecios->ds->wp->GetDBValue("1"), ccsText),false);
            $this->cla_LisPrecios->ds->Where = $this->cla_LisPrecios->ds->wp->Criterion[1];
            $this->cla_LisPrecios->Required = true;
            $this->cla_LisCostos = new clsControl(ccsListBox, "cla_LisCostos", "Cla Lis Costos", ccsInteger, "", CCGetRequestParam("cla_LisCostos", $Method));
            $this->cla_LisCostos->DSType = dsTable;
            list($this->cla_LisCostos->BoundColumn, $this->cla_LisCostos->TextColumn, $this->cla_LisCostos->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->cla_LisCostos->ds = new clsDBdatos();
            $this->cla_LisCostos->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->cla_LisCostos->ds->Parameters["expr84"] = 'ITRLIS';
            $this->cla_LisCostos->ds->wp = new clsSQLParameters();
            $this->cla_LisCostos->ds->wp->AddParameter("1", "expr84", ccsText, "", "", $this->cla_LisCostos->ds->Parameters["expr84"], "", false);
            $this->cla_LisCostos->ds->wp->Criterion[1] = $this->cla_LisCostos->ds->wp->Operation(opEqual, "par_Clave", $this->cla_LisCostos->ds->wp->GetDBValue("1"), $this->cla_LisCostos->ds->ToSQL($this->cla_LisCostos->ds->wp->GetDBValue("1"), ccsText),false);
            $this->cla_LisCostos->ds->Where = $this->cla_LisCostos->ds->wp->Criterion[1];
            $this->cla_LisCostos->Required = true;
            $this->cla_CosFijo = new clsControl(ccsListBox, "cla_CosFijo", "Cla Cos Fijo", ccsInteger, "", CCGetRequestParam("cla_CosFijo", $Method));
            $this->cla_CosFijo->DSType = dsListOfValues;
            $this->cla_CosFijo->Values = array(array("0", "No"), array("1", "Si"));
            $this->cla_CosFijo->Required = true;
            $this->cla_PreFijo = new clsControl(ccsListBox, "cla_PreFijo", "Cla Pre Fijo", ccsInteger, "", CCGetRequestParam("cla_PreFijo", $Method));
            $this->cla_PreFijo->DSType = dsListOfValues;
            $this->cla_PreFijo->Values = array(array("0", "No"), array("1", "Si"));
            $this->cla_PreFijo->Required = true;
            $this->cla_VerCosto = new clsControl(ccsListBox, "cla_VerCosto", "Cla Ver Costo", ccsInteger, "", CCGetRequestParam("cla_VerCosto", $Method));
            $this->cla_VerCosto->DSType = dsListOfValues;
            $this->cla_VerCosto->Values = array(array("0", "No"), array("1", "Si"));
            $this->cla_VerCosto->Required = true;
            $this->cla_VerPrecio = new clsControl(ccsListBox, "cla_VerPrecio", "Cla Ver Precio", ccsInteger, "", CCGetRequestParam("cla_VerPrecio", $Method));
            $this->cla_VerPrecio->DSType = dsListOfValues;
            $this->cla_VerPrecio->Values = array(array("0", "No"), array("1", "Si"));
            $this->cla_VerPrecio->Required = true;
            $this->cla_CtaOrigen = new clsControl(ccsListBox, "cla_CtaOrigen", "Cla Cta Origen", ccsText, "", CCGetRequestParam("cla_CtaOrigen", $Method));
            $this->cla_CtaOrigen->DSType = dsSQL;
            list($this->cla_CtaOrigen->BoundColumn, $this->cla_CtaOrigen->TextColumn, $this->cla_CtaOrigen->DBFormat) = array("cue_codcuenta", "cue_descripcion", "");
            $this->cla_CtaOrigen->ds = new clsDBdatos();
            $this->cla_CtaOrigen->ds->SQL = "SELECT cue_codcuenta,  " .
            "       concat(cue_codcuenta, \"   -  \", cue_descripcion) as cue_descripcion " .
            "FROM concuentas " .
            "";
            $this->cla_CtaOrigen->ds->Order = "2";
            $this->cla_AuxOrigen = new clsControl(ccsListBox, "cla_AuxOrigen", "Cla Aux Origen", ccsInteger, "", CCGetRequestParam("cla_AuxOrigen", $Method));
            $this->cla_AuxOrigen->DSType = dsSQL;
            list($this->cla_AuxOrigen->BoundColumn, $this->cla_AuxOrigen->TextColumn, $this->cla_AuxOrigen->DBFormat) = array("act_codauxiliar", "nombre", "");
            $this->cla_AuxOrigen->ds = new clsDBdatos();
            $this->cla_AuxOrigen->ds->SQL = "select conactivos.act_codauxiliar, concat(conactivos.act_descripcion, \" \", conactivos.act_descripcion1) as nombre " .
            "from conactivos " .
            "union " .
            "select conpersonas.per_codauxiliar, concat(conpersonas.per_Apellidos, \" \", conpersonas.per_Nombres) as nombre " .
            "from conpersonas " .
            "";
            $this->cla_AuxOrigen->ds->Order = "nombre";
            $this->cla_CtaDestino = new clsControl(ccsListBox, "cla_CtaDestino", "Cla Cta Destino", ccsText, "", CCGetRequestParam("cla_CtaDestino", $Method));
            $this->cla_CtaDestino->DSType = dsSQL;
            list($this->cla_CtaDestino->BoundColumn, $this->cla_CtaDestino->TextColumn, $this->cla_CtaDestino->DBFormat) = array("cue_codcuenta", "cue_descripcion", "");
            $this->cla_CtaDestino->ds = new clsDBdatos();
            $this->cla_CtaDestino->ds->SQL = "SELECT cue_codcuenta,  " .
            "       concat(cue_codcuenta, \"   -  \", cue_descripcion) as cue_descripcion " .
            "FROM concuentas " .
            "";
            $this->cla_CtaDestino->ds->Order = "2";
            $this->cla_AuxDestino = new clsControl(ccsListBox, "cla_AuxDestino", "Cla Aux Destino", ccsInteger, "", CCGetRequestParam("cla_AuxDestino", $Method));
            $this->cla_AuxDestino->DSType = dsSQL;
            list($this->cla_AuxDestino->BoundColumn, $this->cla_AuxDestino->TextColumn, $this->cla_AuxDestino->DBFormat) = array("act_codauxiliar", "nombre", "");
            $this->cla_AuxDestino->ds = new clsDBdatos();
            $this->cla_AuxDestino->ds->SQL = "select conactivos.act_codauxiliar, concat(conactivos.act_descripcion, \" \", conactivos.act_descripcion1) as nombre " .
            "from conactivos " .
            "union " .
            "select conpersonas.per_codauxiliar, concat(conpersonas.per_Apellidos, \" \", conpersonas.per_Nombres) as nombre " .
            "from conpersonas " .
            "";
            $this->cla_AuxDestino->ds->Order = "nombre";
            $this->cla_CtaIngresos = new clsControl(ccsListBox, "cla_CtaIngresos", "Cla Cta Ingresos", ccsText, "", CCGetRequestParam("cla_CtaIngresos", $Method));
            $this->cla_CtaIngresos->DSType = dsSQL;
            list($this->cla_CtaIngresos->BoundColumn, $this->cla_CtaIngresos->TextColumn, $this->cla_CtaIngresos->DBFormat) = array("cue_codcuenta", "cue_descripcion", "");
            $this->cla_CtaIngresos->ds = new clsDBdatos();
            $this->cla_CtaIngresos->ds->SQL = "SELECT cue_codcuenta,  " .
            "       concat(cue_codcuenta, \"   -  \", cue_descripcion) as cue_descripcion " .
            "FROM concuentas " .
            "";
            $this->cla_CtaIngresos->ds->Order = "2";
            $this->cla_CtaCosto = new clsControl(ccsListBox, "cla_CtaCosto", "Cla Cta Costo", ccsText, "", CCGetRequestParam("cla_CtaCosto", $Method));
            $this->cla_CtaCosto->DSType = dsSQL;
            list($this->cla_CtaCosto->BoundColumn, $this->cla_CtaCosto->TextColumn, $this->cla_CtaCosto->DBFormat) = array("cue_codcuenta", "cue_descripcion", "");
            $this->cla_CtaCosto->ds = new clsDBdatos();
            $this->cla_CtaCosto->ds->SQL = "SELECT cue_codcuenta,  " .
            "       concat(cue_codcuenta, \"   -  \", cue_descripcion) as cue_descripcion " .
            "FROM concuentas " .
            "";
            $this->cla_CtaCosto->ds->Order = "2";
            $this->cla_CtaDiferencia = new clsControl(ccsListBox, "cla_CtaDiferencia", "Cla Cta Diferencia", ccsText, "", CCGetRequestParam("cla_CtaDiferencia", $Method));
            $this->cla_CtaDiferencia->DSType = dsSQL;
            list($this->cla_CtaDiferencia->BoundColumn, $this->cla_CtaDiferencia->TextColumn, $this->cla_CtaDiferencia->DBFormat) = array("cue_codcuenta", "cue_descripcion", "");
            $this->cla_CtaDiferencia->ds = new clsDBdatos();
            $this->cla_CtaDiferencia->ds->SQL = "SELECT cue_codcuenta,  " .
            "       concat(cue_codcuenta, \"   -  \", cue_descripcion) as cue_descripcion " .
            "FROM concuentas " .
            "";
            $this->cla_CtaDiferencia->ds->Order = "2";
            $this->cla_ReqReferencia = new clsControl(ccsListBox, "cla_ReqReferencia", "Cla Req Referencia", ccsInteger, "", CCGetRequestParam("cla_ReqReferencia", $Method));
            $this->cla_ReqReferencia->DSType = dsListOfValues;
            $this->cla_ReqReferencia->Values = array(array("0", "No"), array("1", "Al Comprobante"), array("2", "Al Detalle"));
            $this->cla_ReqReferencia->Required = true;
            $this->cla_ReqSemana = new clsControl(ccsListBox, "cla_ReqSemana", "Cla Req Semana", ccsInteger, "", CCGetRequestParam("cla_ReqSemana", $Method));
            $this->cla_ReqSemana->DSType = dsListOfValues;
            $this->cla_ReqSemana->Values = array(array("0", "No"), array("1", "Al Comprobante"), array("2", "Al Detalle"));
            $this->cla_ReqSemana->Required = true;
            $this->cla_ClaTransaccion = new clsControl(ccsListBox, "cla_ClaTransaccion", "Cla Cla Transaccion", ccsInteger, "", CCGetRequestParam("cla_ClaTransaccion", $Method));
            $this->cla_ClaTransaccion->DSType = dsListOfValues;
            $this->cla_ClaTransaccion->Values = array(array("-1", "Egreso (-)"), array("1", "Ingreso (+)"), array(" 0", "No Aplica"));
            $this->cla_ClaTransaccion->Required = true;
            $this->cla_IndiCosteo = new clsControl(ccsListBox, "cla_IndiCosteo", "Cla Indi Costeo", ccsInteger, "", CCGetRequestParam("cla_IndiCosteo", $Method));
            $this->cla_IndiCosteo->DSType = dsListOfValues;
            $this->cla_IndiCosteo->Values = array(array("0", "No"), array("1", "Si"));
            $this->cla_IndiCosteo->Required = true;
            $this->cla_reqCantidad = new clsControl(ccsListBox, "cla_reqCantidad", "Cla Req Cantidad", ccsInteger, "", CCGetRequestParam("cla_reqCantidad", $Method));
            $this->cla_reqCantidad->DSType = dsListOfValues;
            $this->cla_reqCantidad->Values = array(array("0", "No"), array("1", "Si"));
            $this->cla_reqCantidad->Required = true;
            $this->cla_QryAuxiliar = new clsControl(ccsTextArea, "cla_QryAuxiliar", "Cla Qry Auxiliar", ccsMemo, "", CCGetRequestParam("cla_QryAuxiliar", $Method));
            $this->Button_Insert = new clsButton("Button_Insert");
            $this->Button_Update = new clsButton("Button_Update");
            $this->Button_Delete = new clsButton("Button_Delete");
            $this->Button_Cancel = new clsButton("Button_Cancel");
            $this->btRegresar = new clsButton("btRegresar");
            $this->btAnadir = new clsButton("btAnadir");
            if(!$this->FormSubmitted) {
                if(!is_array($this->cla_ReqEmisor->Value) && !strlen($this->cla_ReqEmisor->Value) && $this->cla_ReqEmisor->Value !== false)
                $this->cla_ReqEmisor->SetText(1);
                if(!is_array($this->cla_ReqReceptor->Value) && !strlen($this->cla_ReqReceptor->Value) && $this->cla_ReqReceptor->Value !== false)
                $this->cla_ReqReceptor->SetText(1);
                if(!is_array($this->cla_TipoEmisor->Value) && !strlen($this->cla_TipoEmisor->Value) && $this->cla_TipoEmisor->Value !== false)
                $this->cla_TipoEmisor->SetText(9999);
                if(!is_array($this->cla_EmiDefault->Value) && !strlen($this->cla_EmiDefault->Value) && $this->cla_EmiDefault->Value !== false)
                $this->cla_EmiDefault->SetText(0);
                if(!is_array($this->cla_TipoReceptor->Value) && !strlen($this->cla_TipoReceptor->Value) && $this->cla_TipoReceptor->Value !== false)
                $this->cla_TipoReceptor->SetText(999);
                if(!is_array($this->cla_RecDefault->Value) && !strlen($this->cla_RecDefault->Value) && $this->cla_RecDefault->Value !== false)
                $this->cla_RecDefault->SetText(0);
                if(!is_array($this->cla_IndCheque->Value) && !strlen($this->cla_IndCheque->Value) && $this->cla_IndCheque->Value !== false)
                $this->cla_IndCheque->SetText(0);
                if(!is_array($this->cla_Indicador->Value) && !strlen($this->cla_Indicador->Value) && $this->cla_Indicador->Value !== false)
                $this->cla_Indicador->SetText(1);
                if(!is_array($this->cla_Contabilizacion->Value) && !strlen($this->cla_Contabilizacion->Value) && $this->cla_Contabilizacion->Value !== false)
                $this->cla_Contabilizacion->SetText(1);
                if(!is_array($this->cla_IndTransfer->Value) && !strlen($this->cla_IndTransfer->Value) && $this->cla_IndTransfer->Value !== false)
                $this->cla_IndTransfer->SetText(0);
                if(!is_array($this->cla_LisPrecios->Value) && !strlen($this->cla_LisPrecios->Value) && $this->cla_LisPrecios->Value !== false)
                $this->cla_LisPrecios->SetText(9999);
                if(!is_array($this->cla_LisCostos->Value) && !strlen($this->cla_LisCostos->Value) && $this->cla_LisCostos->Value !== false)
                $this->cla_LisCostos->SetText(9999);
                if(!is_array($this->cla_CosFijo->Value) && !strlen($this->cla_CosFijo->Value) && $this->cla_CosFijo->Value !== false)
                $this->cla_CosFijo->SetText(0);
                if(!is_array($this->cla_PreFijo->Value) && !strlen($this->cla_PreFijo->Value) && $this->cla_PreFijo->Value !== false)
                $this->cla_PreFijo->SetText(0);
                if(!is_array($this->cla_VerCosto->Value) && !strlen($this->cla_VerCosto->Value) && $this->cla_VerCosto->Value !== false)
                $this->cla_VerCosto->SetText(0);
                if(!is_array($this->cla_VerPrecio->Value) && !strlen($this->cla_VerPrecio->Value) && $this->cla_VerPrecio->Value !== false)
                $this->cla_VerPrecio->SetText(0);
                if(!is_array($this->cla_AuxOrigen->Value) && !strlen($this->cla_AuxOrigen->Value) && $this->cla_AuxOrigen->Value !== false)
                $this->cla_AuxOrigen->SetText(0);
                if(!is_array($this->cla_AuxDestino->Value) && !strlen($this->cla_AuxDestino->Value) && $this->cla_AuxDestino->Value !== false)
                $this->cla_AuxDestino->SetText(0);
                if(!is_array($this->cla_ReqReferencia->Value) && !strlen($this->cla_ReqReferencia->Value) && $this->cla_ReqReferencia->Value !== false)
                $this->cla_ReqReferencia->SetText(0);
                if(!is_array($this->cla_ReqSemana->Value) && !strlen($this->cla_ReqSemana->Value) && $this->cla_ReqSemana->Value !== false)
                $this->cla_ReqSemana->SetText(0);
                if(!is_array($this->cla_ClaTransaccion->Value) && !strlen($this->cla_ClaTransaccion->Value) && $this->cla_ClaTransaccion->Value !== false)
                $this->cla_ClaTransaccion->SetText(0);
                if(!is_array($this->cla_IndiCosteo->Value) && !strlen($this->cla_IndiCosteo->Value) && $this->cla_IndiCosteo->Value !== false)
                $this->cla_IndiCosteo->SetText(0);
                if(!is_array($this->cla_reqCantidad->Value) && !strlen($this->cla_reqCantidad->Value) && $this->cla_reqCantidad->Value !== false)
                $this->cla_reqCantidad->SetText(0);
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @37-09B4E90F
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->ds->Parameters["urlcla_tipoComp"] = CCGetFromGet("cla_tipoComp", "");
    }
//End Initialize Method

//Validate Method @37-17E3C4F2
    function Validate()
    {
        $Validation = true;
        $Where = "";
        if($this->EditMode && strlen($this->ds->Where))
            $Where = " AND NOT (" . $this->ds->Where . ")";
        global $DBdatos;
        $this->ds->cla_tipoComp->SetValue($this->cla_tipoComp->GetValue());
        if(CCDLookUp("COUNT(*)", "genclasetran", "cla_tipoComp=" . $this->ds->ToSQL($this->ds->cla_tipoComp->GetDBValue(), $this->ds->cla_tipoComp->DataType) . $Where, $DBdatos) > 0)
            $this->cla_tipoComp->Errors->addError("El campo Cla Tipo Comp ya existe.");
        $Validation = ($this->cla_aplicacion->Validate() && $Validation);
        $Validation = ($this->cla_tipoComp->Validate() && $Validation);
        $Validation = ($this->cla_Descripcion->Validate() && $Validation);
        $Validation = ($this->cla_Formulario->Validate() && $Validation);
        $Validation = ($this->cla_Informe->Validate() && $Validation);
        $Validation = ($this->cla_ReqEmisor->Validate() && $Validation);
        $Validation = ($this->cla_TxtEmisor->Validate() && $Validation);
        $Validation = ($this->cla_ReqReceptor->Validate() && $Validation);
        $Validation = ($this->cla_TxtReceptor->Validate() && $Validation);
        $Validation = ($this->cla_TipoEmisor->Validate() && $Validation);
        $Validation = ($this->cla_EmiDefault->Validate() && $Validation);
        $Validation = ($this->cla_TipoReceptor->Validate() && $Validation);
        $Validation = ($this->cla_RecDefault->Validate() && $Validation);
        $Validation = ($this->cla_IndCheque->Validate() && $Validation);
        $Validation = ($this->cla_Indicador->Validate() && $Validation);
        $Validation = ($this->cla_Contabilizacion->Validate() && $Validation);
        $Validation = ($this->cla_IndTransfer->Validate() && $Validation);
        $Validation = ($this->cla_LisPrecios->Validate() && $Validation);
        $Validation = ($this->cla_LisCostos->Validate() && $Validation);
        $Validation = ($this->cla_CosFijo->Validate() && $Validation);
        $Validation = ($this->cla_PreFijo->Validate() && $Validation);
        $Validation = ($this->cla_VerCosto->Validate() && $Validation);
        $Validation = ($this->cla_VerPrecio->Validate() && $Validation);
        $Validation = ($this->cla_CtaOrigen->Validate() && $Validation);
        $Validation = ($this->cla_AuxOrigen->Validate() && $Validation);
        $Validation = ($this->cla_CtaDestino->Validate() && $Validation);
        $Validation = ($this->cla_AuxDestino->Validate() && $Validation);
        $Validation = ($this->cla_CtaIngresos->Validate() && $Validation);
        $Validation = ($this->cla_CtaCosto->Validate() && $Validation);
        $Validation = ($this->cla_CtaDiferencia->Validate() && $Validation);
        $Validation = ($this->cla_ReqReferencia->Validate() && $Validation);
        $Validation = ($this->cla_ReqSemana->Validate() && $Validation);
        $Validation = ($this->cla_ClaTransaccion->Validate() && $Validation);
        $Validation = ($this->cla_IndiCosteo->Validate() && $Validation);
        $Validation = ($this->cla_reqCantidad->Validate() && $Validation);
        $Validation = ($this->cla_QryAuxiliar->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @37-CDB393AF
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->lbTitulo->Errors->Count());
        $errors = ($errors || $this->cla_aplicacion->Errors->Count());
        $errors = ($errors || $this->cla_tipoComp->Errors->Count());
        $errors = ($errors || $this->cla_Descripcion->Errors->Count());
        $errors = ($errors || $this->cla_Formulario->Errors->Count());
        $errors = ($errors || $this->cla_Informe->Errors->Count());
        $errors = ($errors || $this->cla_ReqEmisor->Errors->Count());
        $errors = ($errors || $this->cla_TxtEmisor->Errors->Count());
        $errors = ($errors || $this->cla_ReqReceptor->Errors->Count());
        $errors = ($errors || $this->cla_TxtReceptor->Errors->Count());
        $errors = ($errors || $this->cla_TipoEmisor->Errors->Count());
        $errors = ($errors || $this->cla_EmiDefault->Errors->Count());
        $errors = ($errors || $this->cla_TipoReceptor->Errors->Count());
        $errors = ($errors || $this->cla_RecDefault->Errors->Count());
        $errors = ($errors || $this->cla_IndCheque->Errors->Count());
        $errors = ($errors || $this->cla_Indicador->Errors->Count());
        $errors = ($errors || $this->cla_Contabilizacion->Errors->Count());
        $errors = ($errors || $this->cla_IndTransfer->Errors->Count());
        $errors = ($errors || $this->cla_LisPrecios->Errors->Count());
        $errors = ($errors || $this->cla_LisCostos->Errors->Count());
        $errors = ($errors || $this->cla_CosFijo->Errors->Count());
        $errors = ($errors || $this->cla_PreFijo->Errors->Count());
        $errors = ($errors || $this->cla_VerCosto->Errors->Count());
        $errors = ($errors || $this->cla_VerPrecio->Errors->Count());
        $errors = ($errors || $this->cla_CtaOrigen->Errors->Count());
        $errors = ($errors || $this->cla_AuxOrigen->Errors->Count());
        $errors = ($errors || $this->cla_CtaDestino->Errors->Count());
        $errors = ($errors || $this->cla_AuxDestino->Errors->Count());
        $errors = ($errors || $this->cla_CtaIngresos->Errors->Count());
        $errors = ($errors || $this->cla_CtaCosto->Errors->Count());
        $errors = ($errors || $this->cla_CtaDiferencia->Errors->Count());
        $errors = ($errors || $this->cla_ReqReferencia->Errors->Count());
        $errors = ($errors || $this->cla_ReqSemana->Errors->Count());
        $errors = ($errors || $this->cla_ClaTransaccion->Errors->Count());
        $errors = ($errors || $this->cla_IndiCosteo->Errors->Count());
        $errors = ($errors || $this->cla_reqCantidad->Errors->Count());
        $errors = ($errors || $this->cla_QryAuxiliar->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @37-16DD732E
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
            } else if(strlen(CCGetParam("btRegresar", ""))) {
                $this->PressedButton = "btRegresar";
            } else if(strlen(CCGetParam("btAnadir", ""))) {
                $this->PressedButton = "btAnadir";
            }
        }
        $Redirect = "CoAdTi_mant.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "Button_Delete") {
            if(!CCGetEvent($this->Button_Delete->CCSEvents, "OnClick") || !$this->DeleteRow()) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "Button_Cancel") {
            if(!CCGetEvent($this->Button_Cancel->CCSEvents, "OnClick")) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "btRegresar") {
            if(!CCGetEvent($this->btRegresar->CCSEvents, "OnClick")) {
                $Redirect = "";
            } else {
                $Redirect = "CoAdTi.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
            }
        } else if($this->PressedButton == "btAnadir") {
            if(!CCGetEvent($this->btAnadir->CCSEvents, "OnClick")) {
                $Redirect = "";
            } else {
                $Redirect = "CoAdTi_mant.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm", "cla_tipoComp"));
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

//InsertRow Method @37-24F21A3F
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert");
        if(!$this->InsertAllowed) return false;
        $this->ds->cla_aplicacion->SetValue($this->cla_aplicacion->GetValue());
        $this->ds->cla_tipoComp->SetValue($this->cla_tipoComp->GetValue());
        $this->ds->cla_Descripcion->SetValue($this->cla_Descripcion->GetValue());
        $this->ds->cla_Formulario->SetValue($this->cla_Formulario->GetValue());
        $this->ds->cla_Informe->SetValue($this->cla_Informe->GetValue());
        $this->ds->cla_ReqEmisor->SetValue($this->cla_ReqEmisor->GetValue());
        $this->ds->cla_TxtEmisor->SetValue($this->cla_TxtEmisor->GetValue());
        $this->ds->cla_ReqReceptor->SetValue($this->cla_ReqReceptor->GetValue());
        $this->ds->cla_TxtReceptor->SetValue($this->cla_TxtReceptor->GetValue());
        $this->ds->cla_TipoEmisor->SetValue($this->cla_TipoEmisor->GetValue());
        $this->ds->cla_EmiDefault->SetValue($this->cla_EmiDefault->GetValue());
        $this->ds->cla_TipoReceptor->SetValue($this->cla_TipoReceptor->GetValue());
        $this->ds->cla_RecDefault->SetValue($this->cla_RecDefault->GetValue());
        $this->ds->cla_IndCheque->SetValue($this->cla_IndCheque->GetValue());
        $this->ds->cla_Indicador->SetValue($this->cla_Indicador->GetValue());
        $this->ds->cla_Contabilizacion->SetValue($this->cla_Contabilizacion->GetValue());
        $this->ds->cla_IndTransfer->SetValue($this->cla_IndTransfer->GetValue());
        $this->ds->cla_LisPrecios->SetValue($this->cla_LisPrecios->GetValue());
        $this->ds->cla_LisCostos->SetValue($this->cla_LisCostos->GetValue());
        $this->ds->cla_CosFijo->SetValue($this->cla_CosFijo->GetValue());
        $this->ds->cla_PreFijo->SetValue($this->cla_PreFijo->GetValue());
        $this->ds->cla_VerCosto->SetValue($this->cla_VerCosto->GetValue());
        $this->ds->cla_VerPrecio->SetValue($this->cla_VerPrecio->GetValue());
        $this->ds->cla_CtaOrigen->SetValue($this->cla_CtaOrigen->GetValue());
        $this->ds->cla_AuxOrigen->SetValue($this->cla_AuxOrigen->GetValue());
        $this->ds->cla_CtaDestino->SetValue($this->cla_CtaDestino->GetValue());
        $this->ds->cla_AuxDestino->SetValue($this->cla_AuxDestino->GetValue());
        $this->ds->cla_CtaIngresos->SetValue($this->cla_CtaIngresos->GetValue());
        $this->ds->cla_CtaCosto->SetValue($this->cla_CtaCosto->GetValue());
        $this->ds->cla_CtaDiferencia->SetValue($this->cla_CtaDiferencia->GetValue());
        $this->ds->cla_ReqReferencia->SetValue($this->cla_ReqReferencia->GetValue());
        $this->ds->cla_ReqSemana->SetValue($this->cla_ReqSemana->GetValue());
        $this->ds->cla_ClaTransaccion->SetValue($this->cla_ClaTransaccion->GetValue());
        $this->ds->cla_IndiCosteo->SetValue($this->cla_IndiCosteo->GetValue());
        $this->ds->cla_reqCantidad->SetValue($this->cla_reqCantidad->GetValue());
        $this->ds->cla_QryAuxiliar->SetValue($this->cla_QryAuxiliar->GetValue());
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

//UpdateRow Method @37-6AA5C79B
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate");
        if(!$this->UpdateAllowed) return false;
        $this->ds->lbTitulo->SetValue($this->lbTitulo->GetValue());
        $this->ds->cla_aplicacion->SetValue($this->cla_aplicacion->GetValue());
        $this->ds->cla_tipoComp->SetValue($this->cla_tipoComp->GetValue());
        $this->ds->cla_Descripcion->SetValue($this->cla_Descripcion->GetValue());
        $this->ds->cla_Formulario->SetValue($this->cla_Formulario->GetValue());
        $this->ds->cla_Informe->SetValue($this->cla_Informe->GetValue());
        $this->ds->cla_ReqEmisor->SetValue($this->cla_ReqEmisor->GetValue());
        $this->ds->cla_TxtEmisor->SetValue($this->cla_TxtEmisor->GetValue());
        $this->ds->cla_ReqReceptor->SetValue($this->cla_ReqReceptor->GetValue());
        $this->ds->cla_TxtReceptor->SetValue($this->cla_TxtReceptor->GetValue());
        $this->ds->cla_TipoEmisor->SetValue($this->cla_TipoEmisor->GetValue());
        $this->ds->cla_EmiDefault->SetValue($this->cla_EmiDefault->GetValue());
        $this->ds->cla_TipoReceptor->SetValue($this->cla_TipoReceptor->GetValue());
        $this->ds->cla_RecDefault->SetValue($this->cla_RecDefault->GetValue());
        $this->ds->cla_IndCheque->SetValue($this->cla_IndCheque->GetValue());
        $this->ds->cla_Indicador->SetValue($this->cla_Indicador->GetValue());
        $this->ds->cla_Contabilizacion->SetValue($this->cla_Contabilizacion->GetValue());
        $this->ds->cla_IndTransfer->SetValue($this->cla_IndTransfer->GetValue());
        $this->ds->cla_LisPrecios->SetValue($this->cla_LisPrecios->GetValue());
        $this->ds->cla_LisCostos->SetValue($this->cla_LisCostos->GetValue());
        $this->ds->cla_CosFijo->SetValue($this->cla_CosFijo->GetValue());
        $this->ds->cla_PreFijo->SetValue($this->cla_PreFijo->GetValue());
        $this->ds->cla_VerCosto->SetValue($this->cla_VerCosto->GetValue());
        $this->ds->cla_VerPrecio->SetValue($this->cla_VerPrecio->GetValue());
        $this->ds->cla_CtaOrigen->SetValue($this->cla_CtaOrigen->GetValue());
        $this->ds->cla_AuxOrigen->SetValue($this->cla_AuxOrigen->GetValue());
        $this->ds->cla_CtaDestino->SetValue($this->cla_CtaDestino->GetValue());
        $this->ds->cla_AuxDestino->SetValue($this->cla_AuxDestino->GetValue());
        $this->ds->cla_CtaIngresos->SetValue($this->cla_CtaIngresos->GetValue());
        $this->ds->cla_CtaCosto->SetValue($this->cla_CtaCosto->GetValue());
        $this->ds->cla_CtaDiferencia->SetValue($this->cla_CtaDiferencia->GetValue());
        $this->ds->cla_ReqReferencia->SetValue($this->cla_ReqReferencia->GetValue());
        $this->ds->cla_ReqSemana->SetValue($this->cla_ReqSemana->GetValue());
        $this->ds->cla_ClaTransaccion->SetValue($this->cla_ClaTransaccion->GetValue());
        $this->ds->cla_IndiCosteo->SetValue($this->cla_IndiCosteo->GetValue());
        $this->ds->cla_reqCantidad->SetValue($this->cla_reqCantidad->GetValue());
        $this->ds->cla_QryAuxiliar->SetValue($this->cla_QryAuxiliar->GetValue());
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

//DeleteRow Method @37-EA88835F
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

//Show Method @37-81AF1EE9
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->cla_aplicacion->Prepare();
        $this->cla_ReqEmisor->Prepare();
        $this->cla_ReqReceptor->Prepare();
        $this->cla_TipoEmisor->Prepare();
        $this->cla_TipoReceptor->Prepare();
        $this->cla_IndCheque->Prepare();
        $this->cla_Indicador->Prepare();
        $this->cla_Contabilizacion->Prepare();
        $this->cla_IndTransfer->Prepare();
        $this->cla_LisPrecios->Prepare();
        $this->cla_LisCostos->Prepare();
        $this->cla_CosFijo->Prepare();
        $this->cla_PreFijo->Prepare();
        $this->cla_VerCosto->Prepare();
        $this->cla_VerPrecio->Prepare();
        $this->cla_CtaOrigen->Prepare();
        $this->cla_AuxOrigen->Prepare();
        $this->cla_CtaDestino->Prepare();
        $this->cla_AuxDestino->Prepare();
        $this->cla_CtaIngresos->Prepare();
        $this->cla_CtaCosto->Prepare();
        $this->cla_CtaDiferencia->Prepare();
        $this->cla_ReqReferencia->Prepare();
        $this->cla_ReqSemana->Prepare();
        $this->cla_ClaTransaccion->Prepare();
        $this->cla_IndiCosteo->Prepare();
        $this->cla_reqCantidad->Prepare();

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
                    echo "Error in Record CoAdTi_man";
                }
                else if($this->ds->next_record())
                {
                    $this->ds->SetValues();
                    if(!$this->FormSubmitted)
                    {
                        $this->cla_aplicacion->SetValue($this->ds->cla_aplicacion->GetValue());
                        $this->cla_tipoComp->SetValue($this->ds->cla_tipoComp->GetValue());
                        $this->cla_Descripcion->SetValue($this->ds->cla_Descripcion->GetValue());
                        $this->cla_Formulario->SetValue($this->ds->cla_Formulario->GetValue());
                        $this->cla_Informe->SetValue($this->ds->cla_Informe->GetValue());
                        $this->cla_ReqEmisor->SetValue($this->ds->cla_ReqEmisor->GetValue());
                        $this->cla_TxtEmisor->SetValue($this->ds->cla_TxtEmisor->GetValue());
                        $this->cla_ReqReceptor->SetValue($this->ds->cla_ReqReceptor->GetValue());
                        $this->cla_TxtReceptor->SetValue($this->ds->cla_TxtReceptor->GetValue());
                        $this->cla_TipoEmisor->SetValue($this->ds->cla_TipoEmisor->GetValue());
                        $this->cla_EmiDefault->SetValue($this->ds->cla_EmiDefault->GetValue());
                        $this->cla_TipoReceptor->SetValue($this->ds->cla_TipoReceptor->GetValue());
                        $this->cla_RecDefault->SetValue($this->ds->cla_RecDefault->GetValue());
                        $this->cla_IndCheque->SetValue($this->ds->cla_IndCheque->GetValue());
                        $this->cla_Indicador->SetValue($this->ds->cla_Indicador->GetValue());
                        $this->cla_Contabilizacion->SetValue($this->ds->cla_Contabilizacion->GetValue());
                        $this->cla_IndTransfer->SetValue($this->ds->cla_IndTransfer->GetValue());
                        $this->cla_LisPrecios->SetValue($this->ds->cla_LisPrecios->GetValue());
                        $this->cla_LisCostos->SetValue($this->ds->cla_LisCostos->GetValue());
                        $this->cla_CosFijo->SetValue($this->ds->cla_CosFijo->GetValue());
                        $this->cla_PreFijo->SetValue($this->ds->cla_PreFijo->GetValue());
                        $this->cla_VerCosto->SetValue($this->ds->cla_VerCosto->GetValue());
                        $this->cla_VerPrecio->SetValue($this->ds->cla_VerPrecio->GetValue());
                        $this->cla_CtaOrigen->SetValue($this->ds->cla_CtaOrigen->GetValue());
                        $this->cla_AuxOrigen->SetValue($this->ds->cla_AuxOrigen->GetValue());
                        $this->cla_CtaDestino->SetValue($this->ds->cla_CtaDestino->GetValue());
                        $this->cla_AuxDestino->SetValue($this->ds->cla_AuxDestino->GetValue());
                        $this->cla_CtaIngresos->SetValue($this->ds->cla_CtaIngresos->GetValue());
                        $this->cla_CtaCosto->SetValue($this->ds->cla_CtaCosto->GetValue());
                        $this->cla_CtaDiferencia->SetValue($this->ds->cla_CtaDiferencia->GetValue());
                        $this->cla_ReqReferencia->SetValue($this->ds->cla_ReqReferencia->GetValue());
                        $this->cla_ReqSemana->SetValue($this->ds->cla_ReqSemana->GetValue());
                        $this->cla_ClaTransaccion->SetValue($this->ds->cla_ClaTransaccion->GetValue());
                        $this->cla_IndiCosteo->SetValue($this->ds->cla_IndiCosteo->GetValue());
                        $this->cla_reqCantidad->SetValue($this->ds->cla_reqCantidad->GetValue());
                        $this->cla_QryAuxiliar->SetValue($this->ds->cla_QryAuxiliar->GetValue());
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
            $Error .= $this->cla_aplicacion->Errors->ToString();
            $Error .= $this->cla_tipoComp->Errors->ToString();
            $Error .= $this->cla_Descripcion->Errors->ToString();
            $Error .= $this->cla_Formulario->Errors->ToString();
            $Error .= $this->cla_Informe->Errors->ToString();
            $Error .= $this->cla_ReqEmisor->Errors->ToString();
            $Error .= $this->cla_TxtEmisor->Errors->ToString();
            $Error .= $this->cla_ReqReceptor->Errors->ToString();
            $Error .= $this->cla_TxtReceptor->Errors->ToString();
            $Error .= $this->cla_TipoEmisor->Errors->ToString();
            $Error .= $this->cla_EmiDefault->Errors->ToString();
            $Error .= $this->cla_TipoReceptor->Errors->ToString();
            $Error .= $this->cla_RecDefault->Errors->ToString();
            $Error .= $this->cla_IndCheque->Errors->ToString();
            $Error .= $this->cla_Indicador->Errors->ToString();
            $Error .= $this->cla_Contabilizacion->Errors->ToString();
            $Error .= $this->cla_IndTransfer->Errors->ToString();
            $Error .= $this->cla_LisPrecios->Errors->ToString();
            $Error .= $this->cla_LisCostos->Errors->ToString();
            $Error .= $this->cla_CosFijo->Errors->ToString();
            $Error .= $this->cla_PreFijo->Errors->ToString();
            $Error .= $this->cla_VerCosto->Errors->ToString();
            $Error .= $this->cla_VerPrecio->Errors->ToString();
            $Error .= $this->cla_CtaOrigen->Errors->ToString();
            $Error .= $this->cla_AuxOrigen->Errors->ToString();
            $Error .= $this->cla_CtaDestino->Errors->ToString();
            $Error .= $this->cla_AuxDestino->Errors->ToString();
            $Error .= $this->cla_CtaIngresos->Errors->ToString();
            $Error .= $this->cla_CtaCosto->Errors->ToString();
            $Error .= $this->cla_CtaDiferencia->Errors->ToString();
            $Error .= $this->cla_ReqReferencia->Errors->ToString();
            $Error .= $this->cla_ReqSemana->Errors->ToString();
            $Error .= $this->cla_ClaTransaccion->Errors->ToString();
            $Error .= $this->cla_IndiCosteo->Errors->ToString();
            $Error .= $this->cla_reqCantidad->Errors->ToString();
            $Error .= $this->cla_QryAuxiliar->Errors->ToString();
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
        $this->cla_aplicacion->Show();
        $this->cla_tipoComp->Show();
        $this->cla_Descripcion->Show();
        $this->cla_Formulario->Show();
        $this->cla_Informe->Show();
        $this->cla_ReqEmisor->Show();
        $this->cla_TxtEmisor->Show();
        $this->cla_ReqReceptor->Show();
        $this->cla_TxtReceptor->Show();
        $this->cla_TipoEmisor->Show();
        $this->cla_EmiDefault->Show();
        $this->cla_TipoReceptor->Show();
        $this->cla_RecDefault->Show();
        $this->cla_IndCheque->Show();
        $this->cla_Indicador->Show();
        $this->cla_Contabilizacion->Show();
        $this->cla_IndTransfer->Show();
        $this->cla_LisPrecios->Show();
        $this->cla_LisCostos->Show();
        $this->cla_CosFijo->Show();
        $this->cla_PreFijo->Show();
        $this->cla_VerCosto->Show();
        $this->cla_VerPrecio->Show();
        $this->cla_CtaOrigen->Show();
        $this->cla_AuxOrigen->Show();
        $this->cla_CtaDestino->Show();
        $this->cla_AuxDestino->Show();
        $this->cla_CtaIngresos->Show();
        $this->cla_CtaCosto->Show();
        $this->cla_CtaDiferencia->Show();
        $this->cla_ReqReferencia->Show();
        $this->cla_ReqSemana->Show();
        $this->cla_ClaTransaccion->Show();
        $this->cla_IndiCosteo->Show();
        $this->cla_reqCantidad->Show();
        $this->cla_QryAuxiliar->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->Button_Cancel->Show();
        $this->btRegresar->Show();
        $this->btAnadir->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

} //End CoAdTi_man Class @37-FCB6E20C

class clsCoAdTi_manDataSource extends clsDBdatos {  //CoAdTi_manDataSource Class @37-AB7FA662

//DataSource Variables @37-8D92DC61
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
    var $cla_aplicacion;
    var $cla_tipoComp;
    var $cla_Descripcion;
    var $cla_Formulario;
    var $cla_Informe;
    var $cla_ReqEmisor;
    var $cla_TxtEmisor;
    var $cla_ReqReceptor;
    var $cla_TxtReceptor;
    var $cla_TipoEmisor;
    var $cla_EmiDefault;
    var $cla_TipoReceptor;
    var $cla_RecDefault;
    var $cla_IndCheque;
    var $cla_Indicador;
    var $cla_Contabilizacion;
    var $cla_IndTransfer;
    var $cla_LisPrecios;
    var $cla_LisCostos;
    var $cla_CosFijo;
    var $cla_PreFijo;
    var $cla_VerCosto;
    var $cla_VerPrecio;
    var $cla_CtaOrigen;
    var $cla_AuxOrigen;
    var $cla_CtaDestino;
    var $cla_AuxDestino;
    var $cla_CtaIngresos;
    var $cla_CtaCosto;
    var $cla_CtaDiferencia;
    var $cla_ReqReferencia;
    var $cla_ReqSemana;
    var $cla_ClaTransaccion;
    var $cla_IndiCosteo;
    var $cla_reqCantidad;
    var $cla_QryAuxiliar;
//End DataSource Variables

//Class_Initialize Event @37-F441FA84
    function clsCoAdTi_manDataSource()
    {
        $this->ErrorBlock = "Record CoAdTi_man/Error";
        $this->Initialize();
        $this->lbTitulo = new clsField("lbTitulo", ccsText, "");
        $this->cla_aplicacion = new clsField("cla_aplicacion", ccsText, "");
        $this->cla_tipoComp = new clsField("cla_tipoComp", ccsText, "");
        $this->cla_Descripcion = new clsField("cla_Descripcion", ccsText, "");
        $this->cla_Formulario = new clsField("cla_Formulario", ccsText, "");
        $this->cla_Informe = new clsField("cla_Informe", ccsText, "");
        $this->cla_ReqEmisor = new clsField("cla_ReqEmisor", ccsInteger, "");
        $this->cla_TxtEmisor = new clsField("cla_TxtEmisor", ccsText, "");
        $this->cla_ReqReceptor = new clsField("cla_ReqReceptor", ccsInteger, "");
        $this->cla_TxtReceptor = new clsField("cla_TxtReceptor", ccsText, "");
        $this->cla_TipoEmisor = new clsField("cla_TipoEmisor", ccsInteger, "");
        $this->cla_EmiDefault = new clsField("cla_EmiDefault", ccsInteger, "");
        $this->cla_TipoReceptor = new clsField("cla_TipoReceptor", ccsInteger, "");
        $this->cla_RecDefault = new clsField("cla_RecDefault", ccsInteger, "");
        $this->cla_IndCheque = new clsField("cla_IndCheque", ccsInteger, "");
        $this->cla_Indicador = new clsField("cla_Indicador", ccsInteger, "");
        $this->cla_Contabilizacion = new clsField("cla_Contabilizacion", ccsInteger, "");
        $this->cla_IndTransfer = new clsField("cla_IndTransfer", ccsInteger, "");
        $this->cla_LisPrecios = new clsField("cla_LisPrecios", ccsInteger, "");
        $this->cla_LisCostos = new clsField("cla_LisCostos", ccsInteger, "");
        $this->cla_CosFijo = new clsField("cla_CosFijo", ccsInteger, "");
        $this->cla_PreFijo = new clsField("cla_PreFijo", ccsInteger, "");
        $this->cla_VerCosto = new clsField("cla_VerCosto", ccsInteger, "");
        $this->cla_VerPrecio = new clsField("cla_VerPrecio", ccsInteger, "");
        $this->cla_CtaOrigen = new clsField("cla_CtaOrigen", ccsText, "");
        $this->cla_AuxOrigen = new clsField("cla_AuxOrigen", ccsInteger, "");
        $this->cla_CtaDestino = new clsField("cla_CtaDestino", ccsText, "");
        $this->cla_AuxDestino = new clsField("cla_AuxDestino", ccsInteger, "");
        $this->cla_CtaIngresos = new clsField("cla_CtaIngresos", ccsText, "");
        $this->cla_CtaCosto = new clsField("cla_CtaCosto", ccsText, "");
        $this->cla_CtaDiferencia = new clsField("cla_CtaDiferencia", ccsText, "");
        $this->cla_ReqReferencia = new clsField("cla_ReqReferencia", ccsInteger, "");
        $this->cla_ReqSemana = new clsField("cla_ReqSemana", ccsInteger, "");
        $this->cla_ClaTransaccion = new clsField("cla_ClaTransaccion", ccsInteger, "");
        $this->cla_IndiCosteo = new clsField("cla_IndiCosteo", ccsInteger, "");
        $this->cla_reqCantidad = new clsField("cla_reqCantidad", ccsInteger, "");
        $this->cla_QryAuxiliar = new clsField("cla_QryAuxiliar", ccsMemo, "");

    }
//End Class_Initialize Event

//Prepare Method @37-1D05179C
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlcla_tipoComp", ccsText, "", "", $this->Parameters["urlcla_tipoComp"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "cla_tipoComp", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @37-E5D9CFCA
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->SQL = "SELECT *  " .
        "FROM genclasetran";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @37-B898C0D4
    function SetValues()
    {
        $this->cla_aplicacion->SetDBValue($this->f("cla_aplicacion"));
        $this->cla_tipoComp->SetDBValue($this->f("cla_tipoComp"));
        $this->cla_Descripcion->SetDBValue($this->f("cla_Descripcion"));
        $this->cla_Formulario->SetDBValue($this->f("cla_Formulario"));
        $this->cla_Informe->SetDBValue($this->f("cla_Informe"));
        $this->cla_ReqEmisor->SetDBValue(trim($this->f("cla_ReqEmisor")));
        $this->cla_TxtEmisor->SetDBValue($this->f("cla_TxtEmisor"));
        $this->cla_ReqReceptor->SetDBValue(trim($this->f("cla_ReqReceptor")));
        $this->cla_TxtReceptor->SetDBValue($this->f("cla_TxtReceptor"));
        $this->cla_TipoEmisor->SetDBValue(trim($this->f("cla_TipoEmisor")));
        $this->cla_EmiDefault->SetDBValue(trim($this->f("cla_EmiDefault")));
        $this->cla_TipoReceptor->SetDBValue(trim($this->f("cla_TipoReceptor")));
        $this->cla_RecDefault->SetDBValue(trim($this->f("cla_RecDefault")));
        $this->cla_IndCheque->SetDBValue(trim($this->f("cla_IndCheque")));
        $this->cla_Indicador->SetDBValue(trim($this->f("cla_Indicador")));
        $this->cla_Contabilizacion->SetDBValue(trim($this->f("cla_Contabilizacion")));
        $this->cla_IndTransfer->SetDBValue(trim($this->f("cla_IndTransfer")));
        $this->cla_LisPrecios->SetDBValue(trim($this->f("cla_LisPrecios")));
        $this->cla_LisCostos->SetDBValue(trim($this->f("cla_LisCostos")));
        $this->cla_CosFijo->SetDBValue(trim($this->f("cla_CosFijo")));
        $this->cla_PreFijo->SetDBValue(trim($this->f("cla_PreFijo")));
        $this->cla_VerCosto->SetDBValue(trim($this->f("cla_VerCosto")));
        $this->cla_VerPrecio->SetDBValue(trim($this->f("cla_VerPrecio")));
        $this->cla_CtaOrigen->SetDBValue($this->f("cla_CtaOrigen"));
        $this->cla_AuxOrigen->SetDBValue(trim($this->f("cla_AuxOrigen")));
        $this->cla_CtaDestino->SetDBValue($this->f("cla_CtaDestino"));
        $this->cla_AuxDestino->SetDBValue(trim($this->f("cla_AuxDestino")));
        $this->cla_CtaIngresos->SetDBValue($this->f("cla_CtaIngresos"));
        $this->cla_CtaCosto->SetDBValue($this->f("cla_CtaCosto"));
        $this->cla_CtaDiferencia->SetDBValue($this->f("cla_CtaDiferencia"));
        $this->cla_ReqReferencia->SetDBValue(trim($this->f("cla_ReqReferencia")));
        $this->cla_ReqSemana->SetDBValue(trim($this->f("cla_ReqSemana")));
        $this->cla_ClaTransaccion->SetDBValue(trim($this->f("cla_ClaTransaccion")));
        $this->cla_IndiCosteo->SetDBValue(trim($this->f("cla_IndiCosteo")));
        $this->cla_reqCantidad->SetDBValue(trim($this->f("cla_reqCantidad")));
        $this->cla_QryAuxiliar->SetDBValue($this->f("cla_QryAuxiliar"));
    }
//End SetValues Method

//Insert Method @37-A8B0095F
    function Insert()
    {
        $this->cp["cla_aplicacion"] = new clsSQLParameter("ctrlcla_aplicacion", ccsText, "", "", $this->cla_aplicacion->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["cla_tipoComp"] = new clsSQLParameter("ctrlcla_tipoComp", ccsText, "", "", $this->cla_tipoComp->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["cla_Descripcion"] = new clsSQLParameter("ctrlcla_Descripcion", ccsText, "", "", $this->cla_Descripcion->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["cla_Formulario"] = new clsSQLParameter("ctrlcla_Formulario", ccsText, "", "", $this->cla_Formulario->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["cla_Informe"] = new clsSQLParameter("ctrlcla_Informe", ccsText, "", "", $this->cla_Informe->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["cla_ReqEmisor"] = new clsSQLParameter("ctrlcla_ReqEmisor", ccsInteger, "", "", $this->cla_ReqEmisor->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["cla_TxtEmisor"] = new clsSQLParameter("ctrlcla_TxtEmisor", ccsText, "", "", $this->cla_TxtEmisor->GetValue(), '', false, $this->ErrorBlock);
        $this->cp["cla_ReqReceptor"] = new clsSQLParameter("ctrlcla_ReqReceptor", ccsInteger, "", "", $this->cla_ReqReceptor->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["cla_TxtReceptor"] = new clsSQLParameter("ctrlcla_TxtReceptor", ccsText, "", "", $this->cla_TxtReceptor->GetValue(), '', false, $this->ErrorBlock);
        $this->cp["cla_TipoEmisor"] = new clsSQLParameter("ctrlcla_TipoEmisor", ccsInteger, "", "", $this->cla_TipoEmisor->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["cla_EmiDefault"] = new clsSQLParameter("ctrlcla_EmiDefault", ccsInteger, "", "", $this->cla_EmiDefault->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["cla_TipoReceptor"] = new clsSQLParameter("ctrlcla_TipoReceptor", ccsInteger, "", "", $this->cla_TipoReceptor->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["cla_RecDefault"] = new clsSQLParameter("ctrlcla_RecDefault", ccsInteger, "", "", $this->cla_RecDefault->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["cla_IndCheque"] = new clsSQLParameter("ctrlcla_IndCheque", ccsInteger, "", "", $this->cla_IndCheque->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["cla_Indicador"] = new clsSQLParameter("ctrlcla_Indicador", ccsInteger, "", "", $this->cla_Indicador->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["cla_Contabilizacion"] = new clsSQLParameter("ctrlcla_Contabilizacion", ccsInteger, "", "", $this->cla_Contabilizacion->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["cla_IndTransfer"] = new clsSQLParameter("ctrlcla_IndTransfer", ccsInteger, "", "", $this->cla_IndTransfer->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["cla_LisPrecios"] = new clsSQLParameter("ctrlcla_LisPrecios", ccsInteger, "", "", $this->cla_LisPrecios->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["cla_LisCostos"] = new clsSQLParameter("ctrlcla_LisCostos", ccsInteger, "", "", $this->cla_LisCostos->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["cla_CosFijo"] = new clsSQLParameter("ctrlcla_CosFijo", ccsInteger, "", "", $this->cla_CosFijo->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["cla_PreFijo"] = new clsSQLParameter("ctrlcla_PreFijo", ccsInteger, "", "", $this->cla_PreFijo->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["cla_VerCosto"] = new clsSQLParameter("ctrlcla_VerCosto", ccsInteger, "", "", $this->cla_VerCosto->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["cla_VerPrecio"] = new clsSQLParameter("ctrlcla_VerPrecio", ccsInteger, "", "", $this->cla_VerPrecio->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["cla_CtaOrigen"] = new clsSQLParameter("ctrlcla_CtaOrigen", ccsText, "", "", $this->cla_CtaOrigen->GetValue(), '', false, $this->ErrorBlock);
        $this->cp["cla_AuxOrigen"] = new clsSQLParameter("ctrlcla_AuxOrigen", ccsInteger, "", "", $this->cla_AuxOrigen->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["cla_CtaDestino"] = new clsSQLParameter("ctrlcla_CtaDestino", ccsText, "", "", $this->cla_CtaDestino->GetValue(), '', false, $this->ErrorBlock);
        $this->cp["cla_AuxDestino"] = new clsSQLParameter("ctrlcla_AuxDestino", ccsInteger, "", "", $this->cla_AuxDestino->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["cla_CtaIngresos"] = new clsSQLParameter("ctrlcla_CtaIngresos", ccsText, "", "", $this->cla_CtaIngresos->GetValue(), '', false, $this->ErrorBlock);
        $this->cp["cla_CtaCosto"] = new clsSQLParameter("ctrlcla_CtaCosto", ccsText, "", "", $this->cla_CtaCosto->GetValue(), '', false, $this->ErrorBlock);
        $this->cp["cla_CtaDiferencia"] = new clsSQLParameter("ctrlcla_CtaDiferencia", ccsText, "", "", $this->cla_CtaDiferencia->GetValue(), '', false, $this->ErrorBlock);
        $this->cp["cla_ReqReferencia"] = new clsSQLParameter("ctrlcla_ReqReferencia", ccsInteger, "", "", $this->cla_ReqReferencia->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["cla_ReqSemana"] = new clsSQLParameter("ctrlcla_ReqSemana", ccsInteger, "", "", $this->cla_ReqSemana->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["cla_ClaTransaccion"] = new clsSQLParameter("ctrlcla_ClaTransaccion", ccsInteger, "", "", $this->cla_ClaTransaccion->GetValue(), 1, false, $this->ErrorBlock);
        $this->cp["cla_IndiCosteo"] = new clsSQLParameter("ctrlcla_IndiCosteo", ccsInteger, "", "", $this->cla_IndiCosteo->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["cla_reqCantidad"] = new clsSQLParameter("ctrlcla_reqCantidad", ccsInteger, "", "", $this->cla_reqCantidad->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["cla_QryAuxiliar"] = new clsSQLParameter("ctrlcla_QryAuxiliar", ccsMemo, "", "", $this->cla_QryAuxiliar->GetValue(), '', false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO genclasetran ("
             . "cla_aplicacion, "
             . "cla_tipoComp, "
             . "cla_Descripcion, "
             . "cla_Formulario, "
             . "cla_Informe, "
             . "cla_ReqEmisor, "
             . "cla_TxtEmisor, "
             . "cla_ReqReceptor, "
             . "cla_TxtReceptor, "
             . "cla_TipoEmisor, "
             . "cla_EmiDefault, "
             . "cla_TipoReceptor, "
             . "cla_RecDefault, "
             . "cla_IndCheque, "
             . "cla_Indicador, "
             . "cla_Contabilizacion, "
             . "cla_IndTransfer, "
             . "cla_LisPrecios, "
             . "cla_LisCostos, "
             . "cla_CosFijo, "
             . "cla_PreFijo, "
             . "cla_VerCosto, "
             . "cla_VerPrecio, "
             . "cla_CtaOrigen, "
             . "cla_AuxOrigen, "
             . "cla_CtaDestino, "
             . "cla_AuxDestino, "
             . "cla_CtaIngresos, "
             . "cla_CtaCosto, "
             . "cla_CtaDiferencia, "
             . "cla_ReqReferencia, "
             . "cla_ReqSemana, "
             . "cla_ClaTransaccion, "
             . "cla_IndiCosteo, "
             . "cla_reqCantidad, "
             . "cla_QryAuxiliar"
             . ") VALUES ("
             . $this->ToSQL($this->cp["cla_aplicacion"]->GetDBValue(), $this->cp["cla_aplicacion"]->DataType) . ", "
             . $this->ToSQL($this->cp["cla_tipoComp"]->GetDBValue(), $this->cp["cla_tipoComp"]->DataType) . ", "
             . $this->ToSQL($this->cp["cla_Descripcion"]->GetDBValue(), $this->cp["cla_Descripcion"]->DataType) . ", "
             . $this->ToSQL($this->cp["cla_Formulario"]->GetDBValue(), $this->cp["cla_Formulario"]->DataType) . ", "
             . $this->ToSQL($this->cp["cla_Informe"]->GetDBValue(), $this->cp["cla_Informe"]->DataType) . ", "
             . $this->ToSQL($this->cp["cla_ReqEmisor"]->GetDBValue(), $this->cp["cla_ReqEmisor"]->DataType) . ", "
             . $this->ToSQL($this->cp["cla_TxtEmisor"]->GetDBValue(), $this->cp["cla_TxtEmisor"]->DataType) . ", "
             . $this->ToSQL($this->cp["cla_ReqReceptor"]->GetDBValue(), $this->cp["cla_ReqReceptor"]->DataType) . ", "
             . $this->ToSQL($this->cp["cla_TxtReceptor"]->GetDBValue(), $this->cp["cla_TxtReceptor"]->DataType) . ", "
             . $this->ToSQL($this->cp["cla_TipoEmisor"]->GetDBValue(), $this->cp["cla_TipoEmisor"]->DataType) . ", "
             . $this->ToSQL($this->cp["cla_EmiDefault"]->GetDBValue(), $this->cp["cla_EmiDefault"]->DataType) . ", "
             . $this->ToSQL($this->cp["cla_TipoReceptor"]->GetDBValue(), $this->cp["cla_TipoReceptor"]->DataType) . ", "
             . $this->ToSQL($this->cp["cla_RecDefault"]->GetDBValue(), $this->cp["cla_RecDefault"]->DataType) . ", "
             . $this->ToSQL($this->cp["cla_IndCheque"]->GetDBValue(), $this->cp["cla_IndCheque"]->DataType) . ", "
             . $this->ToSQL($this->cp["cla_Indicador"]->GetDBValue(), $this->cp["cla_Indicador"]->DataType) . ", "
             . $this->ToSQL($this->cp["cla_Contabilizacion"]->GetDBValue(), $this->cp["cla_Contabilizacion"]->DataType) . ", "
             . $this->ToSQL($this->cp["cla_IndTransfer"]->GetDBValue(), $this->cp["cla_IndTransfer"]->DataType) . ", "
             . $this->ToSQL($this->cp["cla_LisPrecios"]->GetDBValue(), $this->cp["cla_LisPrecios"]->DataType) . ", "
             . $this->ToSQL($this->cp["cla_LisCostos"]->GetDBValue(), $this->cp["cla_LisCostos"]->DataType) . ", "
             . $this->ToSQL($this->cp["cla_CosFijo"]->GetDBValue(), $this->cp["cla_CosFijo"]->DataType) . ", "
             . $this->ToSQL($this->cp["cla_PreFijo"]->GetDBValue(), $this->cp["cla_PreFijo"]->DataType) . ", "
             . $this->ToSQL($this->cp["cla_VerCosto"]->GetDBValue(), $this->cp["cla_VerCosto"]->DataType) . ", "
             . $this->ToSQL($this->cp["cla_VerPrecio"]->GetDBValue(), $this->cp["cla_VerPrecio"]->DataType) . ", "
             . $this->ToSQL($this->cp["cla_CtaOrigen"]->GetDBValue(), $this->cp["cla_CtaOrigen"]->DataType) . ", "
             . $this->ToSQL($this->cp["cla_AuxOrigen"]->GetDBValue(), $this->cp["cla_AuxOrigen"]->DataType) . ", "
             . $this->ToSQL($this->cp["cla_CtaDestino"]->GetDBValue(), $this->cp["cla_CtaDestino"]->DataType) . ", "
             . $this->ToSQL($this->cp["cla_AuxDestino"]->GetDBValue(), $this->cp["cla_AuxDestino"]->DataType) . ", "
             . $this->ToSQL($this->cp["cla_CtaIngresos"]->GetDBValue(), $this->cp["cla_CtaIngresos"]->DataType) . ", "
             . $this->ToSQL($this->cp["cla_CtaCosto"]->GetDBValue(), $this->cp["cla_CtaCosto"]->DataType) . ", "
             . $this->ToSQL($this->cp["cla_CtaDiferencia"]->GetDBValue(), $this->cp["cla_CtaDiferencia"]->DataType) . ", "
             . $this->ToSQL($this->cp["cla_ReqReferencia"]->GetDBValue(), $this->cp["cla_ReqReferencia"]->DataType) . ", "
             . $this->ToSQL($this->cp["cla_ReqSemana"]->GetDBValue(), $this->cp["cla_ReqSemana"]->DataType) . ", "
             . $this->ToSQL($this->cp["cla_ClaTransaccion"]->GetDBValue(), $this->cp["cla_ClaTransaccion"]->DataType) . ", "
             . $this->ToSQL($this->cp["cla_IndiCosteo"]->GetDBValue(), $this->cp["cla_IndiCosteo"]->DataType) . ", "
             . $this->ToSQL($this->cp["cla_reqCantidad"]->GetDBValue(), $this->cp["cla_reqCantidad"]->DataType) . ", "
             . $this->ToSQL($this->cp["cla_QryAuxiliar"]->GetDBValue(), $this->cp["cla_QryAuxiliar"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Insert Method

//Update Method @37-7BD9508F
    function Update()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $this->SQL = "UPDATE genclasetran SET "
             . "cla_aplicacion=" . $this->ToSQL($this->cla_aplicacion->GetDBValue(), $this->cla_aplicacion->DataType) . ", "
             . "cla_tipoComp=" . $this->ToSQL($this->cla_tipoComp->GetDBValue(), $this->cla_tipoComp->DataType) . ", "
             . "cla_Descripcion=" . $this->ToSQL($this->cla_Descripcion->GetDBValue(), $this->cla_Descripcion->DataType) . ", "
             . "cla_Formulario=" . $this->ToSQL($this->cla_Formulario->GetDBValue(), $this->cla_Formulario->DataType) . ", "
             . "cla_Informe=" . $this->ToSQL($this->cla_Informe->GetDBValue(), $this->cla_Informe->DataType) . ", "
             . "cla_ReqEmisor=" . $this->ToSQL($this->cla_ReqEmisor->GetDBValue(), $this->cla_ReqEmisor->DataType) . ", "
             . "cla_TxtEmisor=" . $this->ToSQL($this->cla_TxtEmisor->GetDBValue(), $this->cla_TxtEmisor->DataType) . ", "
             . "cla_ReqReceptor=" . $this->ToSQL($this->cla_ReqReceptor->GetDBValue(), $this->cla_ReqReceptor->DataType) . ", "
             . "cla_TxtReceptor=" . $this->ToSQL($this->cla_TxtReceptor->GetDBValue(), $this->cla_TxtReceptor->DataType) . ", "
             . "cla_TipoEmisor=" . $this->ToSQL($this->cla_TipoEmisor->GetDBValue(), $this->cla_TipoEmisor->DataType) . ", "
             . "cla_EmiDefault=" . $this->ToSQL($this->cla_EmiDefault->GetDBValue(), $this->cla_EmiDefault->DataType) . ", "
             . "cla_TipoReceptor=" . $this->ToSQL($this->cla_TipoReceptor->GetDBValue(), $this->cla_TipoReceptor->DataType) . ", "
             . "cla_RecDefault=" . $this->ToSQL($this->cla_RecDefault->GetDBValue(), $this->cla_RecDefault->DataType) . ", "
             . "cla_IndCheque=" . $this->ToSQL($this->cla_IndCheque->GetDBValue(), $this->cla_IndCheque->DataType) . ", "
             . "cla_Indicador=" . $this->ToSQL($this->cla_Indicador->GetDBValue(), $this->cla_Indicador->DataType) . ", "
             . "cla_Contabilizacion=" . $this->ToSQL($this->cla_Contabilizacion->GetDBValue(), $this->cla_Contabilizacion->DataType) . ", "
             . "cla_IndTransfer=" . $this->ToSQL($this->cla_IndTransfer->GetDBValue(), $this->cla_IndTransfer->DataType) . ", "
             . "cla_LisPrecios=" . $this->ToSQL($this->cla_LisPrecios->GetDBValue(), $this->cla_LisPrecios->DataType) . ", "
             . "cla_LisCostos=" . $this->ToSQL($this->cla_LisCostos->GetDBValue(), $this->cla_LisCostos->DataType) . ", "
             . "cla_CosFijo=" . $this->ToSQL($this->cla_CosFijo->GetDBValue(), $this->cla_CosFijo->DataType) . ", "
             . "cla_PreFijo=" . $this->ToSQL($this->cla_PreFijo->GetDBValue(), $this->cla_PreFijo->DataType) . ", "
             . "cla_VerCosto=" . $this->ToSQL($this->cla_VerCosto->GetDBValue(), $this->cla_VerCosto->DataType) . ", "
             . "cla_VerPrecio=" . $this->ToSQL($this->cla_VerPrecio->GetDBValue(), $this->cla_VerPrecio->DataType) . ", "
             . "cla_CtaOrigen=" . $this->ToSQL($this->cla_CtaOrigen->GetDBValue(), $this->cla_CtaOrigen->DataType) . ", "
             . "cla_AuxOrigen=" . $this->ToSQL($this->cla_AuxOrigen->GetDBValue(), $this->cla_AuxOrigen->DataType) . ", "
             . "cla_CtaDestino=" . $this->ToSQL($this->cla_CtaDestino->GetDBValue(), $this->cla_CtaDestino->DataType) . ", "
             . "cla_AuxDestino=" . $this->ToSQL($this->cla_AuxDestino->GetDBValue(), $this->cla_AuxDestino->DataType) . ", "
             . "cla_CtaIngresos=" . $this->ToSQL($this->cla_CtaIngresos->GetDBValue(), $this->cla_CtaIngresos->DataType) . ", "
             . "cla_CtaCosto=" . $this->ToSQL($this->cla_CtaCosto->GetDBValue(), $this->cla_CtaCosto->DataType) . ", "
             . "cla_CtaDiferencia=" . $this->ToSQL($this->cla_CtaDiferencia->GetDBValue(), $this->cla_CtaDiferencia->DataType) . ", "
             . "cla_ReqReferencia=" . $this->ToSQL($this->cla_ReqReferencia->GetDBValue(), $this->cla_ReqReferencia->DataType) . ", "
             . "cla_ReqSemana=" . $this->ToSQL($this->cla_ReqSemana->GetDBValue(), $this->cla_ReqSemana->DataType) . ", "
             . "cla_ClaTransaccion=" . $this->ToSQL($this->cla_ClaTransaccion->GetDBValue(), $this->cla_ClaTransaccion->DataType) . ", "
             . "cla_IndiCosteo=" . $this->ToSQL($this->cla_IndiCosteo->GetDBValue(), $this->cla_IndiCosteo->DataType) . ", "
             . "cla_reqCantidad=" . $this->ToSQL($this->cla_reqCantidad->GetDBValue(), $this->cla_reqCantidad->DataType) . ", "
             . "cla_QryAuxiliar=" . $this->ToSQL($this->cla_QryAuxiliar->GetDBValue(), $this->cla_QryAuxiliar->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Update Method

//Delete Method @37-5A97AB22
    function Delete()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $this->SQL = "DELETE FROM genclasetran";
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Delete Method

} //End CoAdTi_manDataSource Class @37-FCB6E20C

//Initialize Page @1-BC26577C
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

$FileName = "CoAdTi_mant.php";
$Redirect = "";
$TemplateFileName = "CoAdTi_mant.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-A7A68389
$DBdatos = new clsDBdatos();
$DBSeguridad = new clsDBSeguridad();

// Controls
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
$CoAdTi_man = new clsRecordCoAdTi_man();
$CoAdTi_man->Initialize();

// Events
include("./CoAdTi_mant_events.php");
BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-D24A4D72
$Cabecera->Operations();
$CoAdTi_man->Operation();
//End Execute Components

//Go to destination page @1-BC69900D
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    $DBdatos->close();
    $DBSeguridad->close();
    header("Location: " . $Redirect);
    exit;
}
//End Go to destination page

//Show Page @1-79395483
$Cabecera->Show("Cabecera");
$CoAdTi_man->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
$generated_with = "<center><font face=\"Arial\"><small>Generated with CodeCharge Studio</small></font></center>";
$main_block = preg_match("/<\/body>/i", $main_block) ? preg_replace("/<\/body>/i", $generated_with . "</body>", $main_block) : $main_block . $generated_with;
echo $main_block;
//End Show Page

//Unload Page @1-FF671926
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBdatos->close();
$DBSeguridad->close();
unset($Tpl);
//End Unload Page


?>
