<?php
/*
*   Captura de datos, CRUD de transacciones ATS
*   @author JVL
*   @created    01/09/07
*   @rev        30/01/09    Modificaion para sustituir pajax en funcionn verAutorizacion, por incompatibilidad de versiones ("invalid Domain")
*
**/
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files
include_once(RelativePath . "/LibPhp/MisRuc.php");
include_once("GenUti.inc.php");
//Include Page implementation @354-11FBC0D5
include_once(RelativePath . "/Rt_Files/../De_Files/Cabecera.php");
//End Include Page implementation
include (RelativePath . "/LibPhp/ConLib.php") ;
class clsRecordfiscompras { //fiscompras Class @2-5D4BF243

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

//Class_Initialize Event @2-94F7786C
    function clsRecordfiscompras()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record fiscompras/Error";
        $this->ds = new clsfiscomprasDataSource();
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "fiscompras";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->Label2 = new clsControl(ccsLabel, "Label2", "Label2", ccsText, "", CCGetRequestParam("Label2", $Method));
            $this->Label1 = new clsControl(ccsLabel, "Label1", "Label1", ccsText, "", CCGetRequestParam("Label1", $Method));
            $this->tra_Descripcion = new clsControl(ccsTextBox, "tra_Descripcion", "Codigo de Transaccion", ccsText, "", CCGetRequestParam("tra_Descripcion", $Method));
            $this->tipoTransac = new clsControl(ccsTextBox, "tipoTransac", "Tipo Transac", ccsText, "", CCGetRequestParam("tipoTransac", $Method));
            $this->tra_CodCompr = new clsControl(ccsTextBox, "tra_CodCompr", "tra_CodCompr", ccsText, "", CCGetRequestParam("tra_CodCompr", $Method));
            $this->tra_Secuencial = new clsControl(ccsTextBox, "tra_Secuencial", "tra_Secuencial", ccsText, "", CCGetRequestParam("tra_Secuencial", $Method));
            $this->tra_IndProceso = new clsControl(ccsTextBox, "tra_IndProceso", "tra_IndProceso", ccsText, "", CCGetRequestParam("tra_IndProceso", $Method));
            $this->devIva = new clsControl(ccsRadioButton, "devIva", "Dev Iva", ccsText, "", CCGetRequestParam("devIva", $Method));
            $this->devIva->DSType = dsListOfValues;
            $this->devIva->Values = array(array("S", "SI"), array("N", "NO"));
            $this->devIva->Required = true;
            $this->sus_Descripcion = new clsControl(ccsTextBox, "sus_Descripcion", "Codigo de Sustento Tributario", ccsText, "", CCGetRequestParam("sus_Descripcion", $Method));
            $this->codSustento = new clsControl(ccsTextBox, "codSustento", "Cod Sustento", ccsText, "", CCGetRequestParam("codSustento", $Method));
            $this->codSustento->Required = true;
            $this->txt_ProvDescripcion = new clsControl(ccsTextBox, "txt_ProvDescripcion", "Tipo de Identificacion", ccsText, "", CCGetRequestParam("txt_ProvDescripcion", $Method));
            $this->tpIdProv = new clsControl(ccsTextBox, "tpIdProv", "TIPO DE ID PROVEEDOR CONTABLE", ccsText, "", CCGetRequestParam("tpIdProv", $Method));
            $this->tpIdProv->Required = true;
            $this->codProv = new clsControl(ccsTextBox, "codProv", "CODIGO DE PROVEEDOR CONTABLE", ccsText, "", CCGetRequestParam("codProv", $Method));
            $this->codProv->Required = true;
            $this->txt_rucProv = new clsControl(ccsTextBox, "txt_rucProv", "RUC de PROVEEDOR CONTABLE", ccsText, "", CCGetRequestParam("txt_rucProv", $Method));
            $this->txt_rucProv->Required = true;
            $this->btn_ProvCont = new clsButton("btn_ProvCont");
            $this->txt_ProvDescripcionFact = new clsControl(ccsTextBox, "txt_ProvDescripcionFact", "Tipo de Identificacion", ccsText, "", CCGetRequestParam("txt_ProvDescripcionFact", $Method));
            $this->tpIdProvFact = new clsControl(ccsTextBox, "tpIdProvFact", "TIPO DE ID PROVEEDOR FISCAL", ccsText, "", CCGetRequestParam("tpIdProvFact", $Method));
            $this->tpIdProvFact->Required = true;
            $this->idProvFact = new clsControl(ccsTextBox, "idProvFact", "CODIGO DE PROVEEDOR FISCAL", ccsText, "", CCGetRequestParam("idProvFact", $Method));
            $this->idProvFact->Required = true;
            $this->txt_rucProvFact = new clsControl(ccsTextBox, "txt_rucProvFact", "RUC DE PROVEEDOR FISCAL (FACTURA)", ccsText, "", CCGetRequestParam("txt_rucProvFact", $Method));
            $this->txt_rucProvFact->Required = true;
            $this->btn_ProvFisc = new clsButton("btn_ProvFisc");
            $this->tco_Descripcion = new clsControl(ccsTextBox, "tco_Descripcion", "Tipo de Comprobante", ccsText, "", CCGetRequestParam("tco_Descripcion", $Method));
            $this->tipoComprobante = new clsControl(ccsTextBox, "tipoComprobante", "Tipo de Comprobante", ccsText, "", CCGetRequestParam("tipoComprobante", $Method));
            $this->tipoComprobante->Required = true;
            $this->establecimiento = new clsControl(ccsTextBox, "establecimiento", "Establecimiento", ccsInteger, "", CCGetRequestParam("establecimiento", $Method));
            $this->establecimiento->Required = true;
            $this->puntoEmision = new clsControl(ccsTextBox, "puntoEmision", "Punto Emision", ccsInteger, "", CCGetRequestParam("puntoEmision", $Method));
            $this->puntoEmision->Required = true;
            $this->secuencial = new clsControl(ccsTextBox, "secuencial", "Secuencial de Factura", ccsInteger, Array(True, 0, "", "", False, Array("0", "0", "0", "0", "0", "0", "0"), "", 1, True, ""), CCGetRequestParam("secuencial", $Method));
            $this->secuencial->Required = true;
            $this->fechaRegistro = new clsControl(ccsTextBox, "fechaRegistro", "Fecha Registro", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("fechaRegistro", $Method));
            $this->fechaRegistro->Required = true;
            $this->DatePicker_fechaRegistro = new clsDatePicker("DatePicker_fechaRegistro", "fiscompras", "fechaRegistro");
            $this->autorizacion = new clsControl(ccsTextBox, "autorizacion", "Autorizacion", ccsText, "", CCGetRequestParam("autorizacion", $Method));
            $this->autorizacion->Required = true;
            $this->tmp_FecEmision = new clsControl(ccsTextBox, "tmp_FecEmision", "Fec. Emision del Documento de Compra Venta", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("tmp_FecEmision", $Method));
            $this->tmp_FecEmision->Required = true;
            $this->tmp_FecCaduc = new clsControl(ccsTextBox, "tmp_FecCaduc", "Caducidad del Doc de Compra Venta", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("tmp_FecCaduc", $Method));
            $this->aut_NroInicial = new clsControl(ccsHidden, "aut_NroInicial", "NroInicial Autorizado", ccsInteger, "", CCGetRequestParam("aut_NroInicial", $Method));
            $this->aut_NroFinal = new clsControl(ccsHidden, "aut_NroFinal", "Nro Final Autorizado", ccsInteger, "", CCGetRequestParam("aut_NroFinal", $Method));
            $this->tmp_IndicTransComp = new clsControl(ccsTextBox, "tmp_IndicTransComp", "Fec. Emision del Documento de Compra Venta", ccsText, "", CCGetRequestParam("tmp_IndicTransComp", $Method));
            $this->fechaEmision = new clsControl(ccsTextBox, "fechaEmision", "Secuencial de Factura", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("fechaEmision", $Method));
            $this->fechaEmision->Required = true;
            $this->tmp_Descripcion = new clsControl(ccsTextBox, "tmp_Descripcion", "Tipo de Comprobante", ccsText, "", CCGetRequestParam("tmp_Descripcion", $Method));
            $this->tco_Secuencial = new clsControl(ccsTextBox, "tco_Secuencial", "tco_Secuencial", ccsText, "", CCGetRequestParam("tco_Secuencial", $Method));
            $this->tco_Sustento = new clsControl(ccsTextBox, "tco_Sustento", "tco_Sustento", ccsText, "", CCGetRequestParam("tco_Sustento", $Method));
            $this->civ_FecInic = new clsControl(ccsTextBox, "civ_FecInic", "civ_FecInic", ccsText, "", CCGetRequestParam("civ_FecInic", $Method));
            $this->civ_FecFin = new clsControl(ccsTextBox, "civ_FecFin", "civ_FecFin", ccsText, "", CCGetRequestParam("civ_FecFin", $Method));
            $this->sus_CodCompr = new clsControl(ccsTextBox, "sus_CodCompr", "sus_CodCompr", ccsText, "", CCGetRequestParam("sus_CodCompr", $Method));
            $this->cra_Porcent = new clsControl(ccsTextBox, "cra_Porcent", "cra_Porcent", ccsText, "", CCGetRequestParam("cra_Porcent", $Method));
            $this->cra_FecIni = new clsControl(ccsTextBox, "cra_FecIni", "cra_FecIni", ccsText, "", CCGetRequestParam("cra_FecIni", $Method));
            $this->cra_FecFin = new clsControl(ccsTextBox, "cra_FecFin", "cra_FecFin", ccsText, "", CCGetRequestParam("cra_FecFin", $Method));
            $this->cra_Proceso = new clsControl(ccsTextBox, "cra_Proceso", "cra_Proceso", ccsText, "", CCGetRequestParam("cra_Proceso", $Method));
            $this->ID = new clsControl(ccsHidden, "ID", "ID", ccsText, "", CCGetRequestParam("ID", $Method));
            $this->hdRegNumero = new clsControl(ccsHidden, "hdRegNumero", "hdRegNumero", ccsInteger, "", CCGetRequestParam("hdRegNumero", $Method));
            $this->hdTipoComp = new clsControl(ccsHidden, "hdTipoComp", "hdTipoComp", ccsText, "", CCGetRequestParam("hdTipoComp", $Method));
            $this->hdNumComp = new clsControl(ccsHidden, "hdNumComp", "hdNumComp", ccsInteger, "", CCGetRequestParam("hdNumComp", $Method));
            $this->baseImponible = new clsControl(ccsTextBox, "baseImponible", "Base Imponible", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("baseImponible", $Method));
            $this->baseImponible->Required = true;
            $this->baseImpGrav = new clsControl(ccsTextBox, "baseImpGrav", "Base Imp Grav", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("baseImpGrav", $Method));
            $this->baseImpGrav->Required = true;
            $this->baseNoGraIva = new clsControl(ccsTextBox, "baseNoGraIva", "Base No Iva", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("baseNoGraIva", $Method));
            $this->baseNoGraIva->Required = true;
            $this->baseImpExe = new clsControl(ccsTextBox, "baseImpExe", "Base Exenta de Iva", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("baseImpExe", $Method));
            $this->baseImpExe->Required = true;
            $this->civ_Descripcion = new clsControl(ccsTextBox, "civ_Descripcion", "Codigo de Porcentaje Iva", ccsText, "", CCGetRequestParam("civ_Descripcion", $Method));
            $this->porcentajeIva = new clsControl(ccsTextBox, "porcentajeIva", "Codigo de % Iva", ccsInteger, Array(False, 0, "", "", False, "", "", 1, True, ""), CCGetRequestParam("porcentajeIva", $Method));
            $this->civ_Porcent = new clsControl(ccsTextBox, "civ_Porcent", "Codigo de Porcentaje Iva", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("civ_Porcent", $Method));
            $this->montoIva = new clsControl(ccsTextBox, "montoIva", "Monto Iva", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("montoIva", $Method));
            $this->montoIva->Required = true;
            $this->baseImpIce = new clsControl(ccsTextBox, "baseImpIce", "Base Imponible", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("baseImpIce", $Method));
            $this->baseImpIce->Required = true;
            $this->pic_Descripcion = new clsControl(ccsTextBox, "pic_Descripcion", "Codigo deImpuesto ICE", ccsText, "", CCGetRequestParam("pic_Descripcion", $Method));
            $this->porcentajeIce = new clsControl(ccsTextBox, "porcentajeIce", "Porcentaje Ice", ccsInteger, "", CCGetRequestParam("porcentajeIce", $Method));
            $this->pic_porcent = new clsControl(ccsTextBox, "pic_porcent", "Codigo de Porcentaje Iva", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("pic_porcent", $Method));
            $this->montoIce = new clsControl(ccsTextBox, "montoIce", "Monto Ice", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("montoIce", $Method));
            $this->montoIce->Required = true;
            $this->montoIvaBienes = new clsControl(ccsTextBox, "montoIvaBienes", "Monto Iva Bienes", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("montoIvaBienes", $Method));
            $this->montoIvaBienes->Required = true;
            $this->prb_Descripcion = new clsControl(ccsTextBox, "prb_Descripcion", "Codigo de Retencion IVA Bienes", ccsText, "", CCGetRequestParam("prb_Descripcion", $Method));
            $this->porRetBienes = new clsControl(ccsTextBox, "porRetBienes", "% Ret Bienes", ccsInteger, "", CCGetRequestParam("porRetBienes", $Method));
            $this->prb_Porcent = new clsControl(ccsTextBox, "prb_Porcent", "% de  Retencion IVA BIENES", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("prb_Porcent", $Method));
            $this->valorRetBienes = new clsControl(ccsTextBox, "valorRetBienes", "Valor Ret Bienes", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("valorRetBienes", $Method));
            $this->valorRetBienes->Required = true;
            $this->montoIvaServicios = new clsControl(ccsTextBox, "montoIvaServicios", "Monto Iva Servicios", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("montoIvaServicios", $Method));
            $this->montoIvaServicios->Required = true;
            $this->prs_Descripcion = new clsControl(ccsTextBox, "prs_Descripcion", "Codigo retencion IVA SERVICIOS", ccsText, "", CCGetRequestParam("prs_Descripcion", $Method));
            $this->porRetServicios = new clsControl(ccsTextBox, "porRetServicios", "%  Ret Servicios", ccsInteger, "", CCGetRequestParam("porRetServicios", $Method));
            $this->prs_Porcent = new clsControl(ccsTextBox, "prs_Porcent", "% de Retencion Iva Servicios", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("prs_Porcent", $Method));
            $this->valorRetServicios = new clsControl(ccsTextBox, "valorRetServicios", "Valor Ret Servicios", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("valorRetServicios", $Method));
            $this->valorRetServicios->Required = true;
            $this->estabRetencion1 = new clsControl(ccsTextBox, "estabRetencion1", "Estab Retencion1", ccsInteger, Array(True, 0, "", "", False, Array("0", "0", "0"), "", 1, True, ""), CCGetRequestParam("estabRetencion1", $Method));
            $this->estabRetencion1->Required = true;
            $this->puntoEmiRetencion1 = new clsControl(ccsTextBox, "puntoEmiRetencion1", "Punto Emi Retencion1", ccsInteger, Array(True, 0, "", "", False, Array("0", "0", "0"), "", 1, True, ""), CCGetRequestParam("puntoEmiRetencion1", $Method));
            $this->puntoEmiRetencion1->Required = true;
            $this->secRetencion1 = new clsControl(ccsTextBox, "secRetencion1", "Sec Retencion1", ccsInteger, Array(True, 0, "", "", False, Array("0", "0", "0", "0", "0", "0", "0"), "", 1, True, ""), CCGetRequestParam("secRetencion1", $Method));
            $this->secRetencion1->Required = true;
            $this->autRetencion1 = new clsControl(ccsTextBox, "autRetencion1", "Aut Retencion1", ccsText, "", CCGetRequestParam("autRetencion1", $Method));
            $this->autRetencion1->Required = true;
            $this->fechaEmiRet1 = new clsControl(ccsTextBox, "fechaEmiRet1", "Sec Retencion1", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("fechaEmiRet1", $Method));
            $this->cra_Descripcion = new clsControl(ccsTextBox, "cra_Descripcion", "Cdigo de Retencion de Imp Renta", ccsText, "", CCGetRequestParam("cra_Descripcion", $Method));
            $this->codRetAir = new clsControl(ccsTextBox, "codRetAir", "CODIGO RETENCION 1", ccsInteger, "", CCGetRequestParam("codRetAir", $Method));
            $this->baseImpAir = new clsControl(ccsTextBox, "baseImpAir", "BASE IMP. RETENCION FUENTE 1", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("0", "0"), 1, True, ""), CCGetRequestParam("baseImpAir", $Method));
            $this->baseImpAir->Required = true;
            $this->porcentajeAir = new clsControl(ccsTextBox, "porcentajeAir", "Porcentaje Air", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("porcentajeAir", $Method));
            $this->valRetAir = new clsControl(ccsTextBox, "valRetAir", "MONTO DE RETENCION 1",   ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("valRetAir", $Method));
            $this->cra_Descripcion2 = new clsControl(ccsTextBox, "cra_Descripcion2", "Cdigo de Retencion de Imp Renta", ccsText, "", CCGetRequestParam("cra_Descripcion2", $Method));
            $this->codRetAir2 = new clsControl(ccsTextBox, "codRetAir2", "CODIGO RETENCION 2", ccsInteger, "", CCGetRequestParam("codRetAir2", $Method));
            $this->baseImpAir2 = new clsControl(ccsTextBox, "baseImpAir2", "BASE IMP. RETENCION FUENTE 2", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("0", "0"), 1, True, ""), CCGetRequestParam("baseImpAir2", $Method));
            $this->porcentajeAir2 = new clsControl(ccsTextBox, "porcentajeAir2", "Porcentaje Air", ccsInteger, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("porcentajeAir2", $Method));
            $this->valRetAir2 = new clsControl(ccsTextBox, "valRetAir2", "MONTO DE RETENCION 2", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("valRetAir2", $Method));
            $this->cra_Descripcion3 = new clsControl(ccsTextBox, "cra_Descripcion3", "Cdigo de Retencion de Imp Renta", ccsText, "", CCGetRequestParam("cra_Descripcion3", $Method));
            $this->codRetAir3 = new clsControl(ccsTextBox, "codRetAir3", "CODIGO RETENCION 3", ccsInteger, "", CCGetRequestParam("codRetAir3", $Method));
            $this->baseImpAir3 = new clsControl(ccsTextBox, "baseImpAir3", "BASE IMP. RETENCION FUENTE 3", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("0", "0"), 1, True, ""), CCGetRequestParam("baseImpAir3", $Method));
            $this->porcentajeAir3 = new clsControl(ccsTextBox, "porcentajeAir3", "Porcentaje Air", ccsInteger, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("porcentajeAir3", $Method));
            $this->valRetAir3 = new clsControl(ccsTextBox, "valRetAir3", "MONTO DE RETENCION 3", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("valRetAir3", $Method));
            $this->baseImpAirTot = new clsControl(ccsTextBox, "baseImpAirTot", "Base Imp Air", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("0", "0"), 1, True, ""), CCGetRequestParam("baseImpAirTot", $Method));
            $this->valRetAirTot = new clsControl(ccsTextBox, "valRetAirTot", "Valret Air", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("valRetAirTot", $Method));
            $this->ccm_Descripcion = new clsControl(ccsTextBox, "ccm_Descripcion", "Codigo de Comprobante Modificado", ccsText, "", CCGetRequestParam("ccm_Descripcion", $Method));
            $this->docModificado = new clsControl(ccsTextBox, "docModificado", "Doc Modificado", ccsText, "", CCGetRequestParam("docModificado", $Method));
            $this->docModificado->Required = true;
            $this->fechaEmiModificado = new clsControl(ccsTextBox, "fechaEmiModificado", "Fecha Emision de Documento Modificado", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("fechaEmiModificado", $Method));
            $this->DatePicker_fechaEmiModificado = new clsDatePicker("DatePicker_fechaEmiModificado", "fiscompras", "fechaEmiModificado");
            $this->estabModificado = new clsControl(ccsTextBox, "estabModificado", "Estab Modificado", ccsInteger, Array(True, 0, "", "", False, Array("0", "0", "0"), "", 1, True, ""), CCGetRequestParam("estabModificado", $Method));
            $this->estabModificado->Required = true;
            $this->ptoEmiModificado = new clsControl(ccsTextBox, "ptoEmiModificado", "Pto Emi Modificado", ccsInteger, Array(True, 0, "", "", False, Array("0", "0", "0"), "", 1, True, ""), CCGetRequestParam("ptoEmiModificado", $Method));
            $this->ptoEmiModificado->Required = true;
            $this->secModificado = new clsControl(ccsTextBox, "secModificado", "Sec Modificado", ccsInteger, Array(True, 0, "", "", False, Array("0", "0", "0", "0", "0", "0", "0"), "", 1, True, ""), CCGetRequestParam("secModificado", $Method));
            $this->secModificado->Required = true;
            $this->autModificado = new clsControl(ccsTextBox, "autModificado", "Aut Modificado", ccsText, Array(True, 0, "", "", False, Array("0", "0", "0", "0", "0", "0", "0"), "", 1, True, ""), CCGetRequestParam("autModificado", $Method));
            $this->autModificado->Required = true;
            $this->contratoPartidoPolitico = new clsControl(ccsTextBox, "contratoPartidoPolitico", "Contrato Partido Politico", ccsInteger, Array(False, 0, "", "", False, "", "", 1, True, ""), CCGetRequestParam("contratoPartidoPolitico", $Method));
            $this->contratoPartidoPolitico->Required = true;
            $this->montoTituloOneroso = new clsControl(ccsTextBox, "montoTituloOneroso", "Monto Titulo Oneroso", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("montoTituloOneroso", $Method));
            $this->montoTituloGratuito = new clsControl(ccsTextBox, "montoTituloGratuito", "Monto Titulo Gratuito", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("montoTituloGratuito", $Method));
            $this->numeroComprobantes = new clsControl(ccsTextBox, "numeroComprobantes", "Numero Comprobantes", ccsInteger, Array(False, 0, "", "", False, "", "", 1, True, ""), CCGetRequestParam("numeroComprobantes", $Method));
            $this->numeroComprobantes->Required = true;
            $this->ivaPresuntivo = new clsControl(ccsRadioButton, "ivaPresuntivo", "Iva Presuntivo", ccsText, "", CCGetRequestParam("ivaPresuntivo", $Method));
            $this->ivaPresuntivo->DSType = dsListOfValues;
            $this->ivaPresuntivo->Values = array(array("S", "SI"), array("N", "NO"));
            $this->retPresuntiva = new clsControl(ccsRadioButton, "retPresuntiva", "Ret Presuntiva", ccsText, "", CCGetRequestParam("retPresuntiva", $Method));
            $this->retPresuntiva->DSType = dsListOfValues;
            $this->retPresuntiva->Values = array(array("S", "SI"), array("N", "NO"));
            $this->distAduanero = new clsControl(ccsTextBox, "distAduanero", "Distrito Aduanero", ccsInteger, Array(True, 0, "", "", False, Array("0", "0", "0"), "", 1, True, ""), CCGetRequestParam("distAduanero", $Method));
            $this->distAduanero->Required = true;
            $this->anio = new clsControl(ccsTextBox, "anio", "A�o de Refrendo", ccsText, "", CCGetRequestParam("anio", $Method));
            $this->regimen = new clsControl(ccsTextBox, "regimen", "regimen", ccsText, "", CCGetRequestParam("regimen", $Method));
            $this->correlativo = new clsControl(ccsTextBox, "correlativo", "correlativo", ccsText, "", CCGetRequestParam("correlativo", $Method));
            $this->verificador = new clsControl(ccsTextBox, "verificador", "verificador", ccsText, "", CCGetRequestParam("verificador", $Method));
            $this->numCajBan = new clsControl(ccsTextBox, "numCajBan", "numCajBan", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("numCajBan", $Method));
            $this->precCajBan = new clsControl(ccsTextBox, "precCajBan", "precCajBan", ccsFloat, Array(False, 4, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("precCajBan", $Method));
            $this->numCajBan2 = new clsControl(ccsTextBox, "numCajBan2", "numCajBan2", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("numCajBan2", $Method));
            $this->precCajBan2 = new clsControl(ccsTextBox, "precCajBan2", "precCajBan2", ccsFloat, Array(False, 4, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("precCajBan2", $Method));
            $this->numCajBan3 = new clsControl(ccsTextBox, "numCajBan3", "numCajBan3", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("numCajBan3", $Method));
            $this->precCajBan3 = new clsControl(ccsTextBox, "precCajBan3", "precCajBan3", ccsFloat, Array(False, 4, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("precCajBan3", $Method));

            $this->tce_Descripcion = new clsControl(ccsTextBox, "tce_Descripcion", "Tipo de Comprobante", ccsText, "", CCGetRequestParam("tce_Descripcion", $Method));
            $this->facturaExportacion = new clsControl(ccsTextBox, "facturaExportacion", "Tipo de Comprobante del Documento de exportacion", ccsText, "", CCGetRequestParam("facturaExportacion", $Method));
            $this->tipImpExp = new clsControl(ccsRadioButton, "tipImpExp", "Tipo deImportacion / Exportacion", ccsText, "", CCGetRequestParam("tipImpExp", $Method));
            $this->tipImpExp->DSType = dsTable;
            list($this->tipImpExp->BoundColumn, $this->tipImpExp->TextColumn, $this->tipImpExp->DBFormat) = array("tab_Codigo", "tab_Descripcion", "");
            $this->tipImpExp->ds = new clsDBdatos();
            $this->tipImpExp->ds->SQL = "SELECT *  " .
"FROM fistablassri";
            $this->tipImpExp->ds->Parameters["expr421"] = B;
            $this->tipImpExp->ds->wp = new clsSQLParameters();
            $this->tipImpExp->ds->wp->AddParameter("1", "expr421", ccsText, "", "", $this->tipImpExp->ds->Parameters["expr421"], "", false);
            $this->tipImpExp->ds->wp->Criterion[1] = $this->tipImpExp->ds->wp->Operation(opEqual, "tab_CodTabla", $this->tipImpExp->ds->wp->GetDBValue("1"), $this->tipImpExp->ds->ToSQL($this->tipImpExp->ds->wp->GetDBValue("1"), ccsText),false);
            $this->tipImpExp->ds->Where = 
                 $this->tipImpExp->ds->wp->Criterion[1];
            $this->tipImpExp->HTML = true;
            $this->valorCifFob = new clsControl(ccsTextBox, "valorCifFob", "valorCifFob", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("valorCifFob", $Method));
            $this->valorFobComprobante = new clsControl(ccsTextBox, "valorFobComprobante", "valorFobComprobante", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("valorFobComprobante", $Method));
            $this->fechaEmbarque = new clsControl(ccsTextBox, "fechaEmbarque", "Fecha de Embarque", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("fechaEmbarque", $Method));
            $this->documEmbarque = new clsControl(ccsTextBox, "documEmbarque", "Fecha de Embarque", ccsText, "", CCGetRequestParam("documEmbarque", $Method));
            $this->Button_Insert = new clsButton("Button_Insert");
            $this->Button_Update = new clsButton("Button_Update");
            $this->Button1 = new clsButton("Button1");
            $this->Button2 = new clsButton("Button2");
            $this->Button_Delete = new clsButton("Button_Delete");
            $this->Button_Cancel = new clsButton("Button_Cancel");
            if(!$this->FormSubmitted) {
                if(!is_array($this->devIva->Value) && !strlen($this->devIva->Value) && $this->devIva->Value !== false)
                $this->devIva->SetText(N);
                if(!is_array($this->establecimiento->Value) && !strlen($this->establecimiento->Value) && $this->establecimiento->Value !== false)
                $this->establecimiento->SetText(001);
                if(!is_array($this->puntoEmision->Value) && !strlen($this->puntoEmision->Value) && $this->puntoEmision->Value !== false)
                $this->puntoEmision->SetText(001);
                if(!is_array($this->fechaRegistro->Value) && !strlen($this->fechaRegistro->Value) && $this->fechaRegistro->Value !== false)
                $this->fechaRegistro->SetValue(time());
                if(!is_array($this->fechaEmision->Value) && !strlen($this->fechaEmision->Value) && $this->fechaEmision->Value !== false)
                $this->fechaEmision->SetValue(time());
                if(!is_array($this->baseImponible->Value) && !strlen($this->baseImponible->Value) && $this->baseImponible->Value !== false)
                $this->baseImponible->SetText(0);
                if(!is_array($this->baseImpGrav->Value) && !strlen($this->baseImpGrav->Value) && $this->baseImpGrav->Value !== false)
                $this->baseImpGrav->SetText(0);
                if(!is_array($this->baseNoGraIva->Value) && !strlen($this->baseNoGraIva->Value) && $this->baseNoGraIva->Value !== false)
                $this->baseNoGraIva->SetText(0);
                if(!is_array($this->baseImpExe->Value) && !strlen($this->baseImpExe->Value) && $this->baseImpExe->Value !== false)
                $this->baseImpExe->SetText(0);
                if(!is_array($this->porcentajeIva->Value) && !strlen($this->porcentajeIva->Value) && $this->porcentajeIva->Value !== false)
                $this->porcentajeIva->SetText(0);
                if(!is_array($this->civ_Porcent->Value) && !strlen($this->civ_Porcent->Value) && $this->civ_Porcent->Value !== false)
                $this->civ_Porcent->SetText(0);
                if(!is_array($this->montoIva->Value) && !strlen($this->montoIva->Value) && $this->montoIva->Value !== false)
                $this->montoIva->SetText(0);
                if(!is_array($this->baseImpIce->Value) && !strlen($this->baseImpIce->Value) && $this->baseImpIce->Value !== false)
                $this->baseImpIce->SetText(0);
                if(!is_array($this->porcentajeIce->Value) && !strlen($this->porcentajeIce->Value) && $this->porcentajeIce->Value !== false)
                $this->porcentajeIce->SetText(0);
                if(!is_array($this->pic_porcent->Value) && !strlen($this->pic_porcent->Value) && $this->pic_porcent->Value !== false)
                $this->pic_porcent->SetText(0);
                if(!is_array($this->montoIce->Value) && !strlen($this->montoIce->Value) && $this->montoIce->Value !== false)
                $this->montoIce->SetText(0);
                if(!is_array($this->montoIvaBienes->Value) && !strlen($this->montoIvaBienes->Value) && $this->montoIvaBienes->Value !== false)
                $this->montoIvaBienes->SetText(0);
                if(!is_array($this->porRetBienes->Value) && !strlen($this->porRetBienes->Value) && $this->porRetBienes->Value !== false)
                $this->porRetBienes->SetText(0);
                if(!is_array($this->prb_Porcent->Value) && !strlen($this->prb_Porcent->Value) && $this->prb_Porcent->Value !== false)
                $this->prb_Porcent->SetText(0);
                if(!is_array($this->valorRetBienes->Value) && !strlen($this->valorRetBienes->Value) && $this->valorRetBienes->Value !== false)
                $this->valorRetBienes->SetText(0);
                if(!is_array($this->montoIvaServicios->Value) && !strlen($this->montoIvaServicios->Value) && $this->montoIvaServicios->Value !== false)
                $this->montoIvaServicios->SetText(0);
                if(!is_array($this->porRetServicios->Value) && !strlen($this->porRetServicios->Value) && $this->porRetServicios->Value !== false)
                $this->porRetServicios->SetText(0);
                if(!is_array($this->prs_Porcent->Value) && !strlen($this->prs_Porcent->Value) && $this->prs_Porcent->Value !== false)
                $this->prs_Porcent->SetText(0);
                if(!is_array($this->valorRetServicios->Value) && !strlen($this->valorRetServicios->Value) && $this->valorRetServicios->Value !== false)
                $this->valorRetServicios->SetText(0);
                if(!is_array($this->estabRetencion1->Value) && !strlen($this->estabRetencion1->Value) && $this->estabRetencion1->Value !== false)
                $this->estabRetencion1->SetText(000);
                if(!is_array($this->puntoEmiRetencion1->Value) && !strlen($this->puntoEmiRetencion1->Value) && $this->puntoEmiRetencion1->Value !== false)
                $this->puntoEmiRetencion1->SetText(000);
                if(!is_array($this->secRetencion1->Value) && !strlen($this->secRetencion1->Value) && $this->secRetencion1->Value !== false)
                $this->secRetencion1->SetText(0);
                if(!is_array($this->autRetencion1->Value) && !strlen($this->autRetencion1->Value) && $this->autRetencion1->Value !== false)
                $this->autRetencion1->SetText(0);
                if(!is_array($this->fechaEmiRet1->Value) && !strlen($this->fechaEmiRet1->Value) && $this->fechaEmiRet1->Value !== false)
                //$this->fechaEmiRet1->SetText(0);
                $this->fechaEmiRet1->SetValue(time());
                
                if(!is_array($this->codRetAir->Value) && !strlen($this->codRetAir->Value) && $this->codRetAir->Value !== false)
                $this->codRetAir->SetText(0);
                if(!is_array($this->baseImpAir->Value) && !strlen($this->baseImpAir->Value) && $this->baseImpAir->Value !== false)
                $this->baseImpAir->SetText(0);
                if(!is_array($this->porcentajeAir->Value) && !strlen($this->porcentajeAir->Value) && $this->porcentajeAir->Value !== false)
                $this->porcentajeAir->SetText(0);
                if(!is_array($this->valRetAir->Value) && !strlen($this->valRetAir->Value) && $this->valRetAir->Value !== false)
                $this->valRetAir->SetText(0);
                if(!is_array($this->codRetAir2->Value) && !strlen($this->codRetAir2->Value) && $this->codRetAir2->Value !== false)
                $this->codRetAir2->SetText(0);
                if(!is_array($this->baseImpAir2->Value) && !strlen($this->baseImpAir2->Value) && $this->baseImpAir2->Value !== false)
                $this->baseImpAir2->SetText(0);
                if(!is_array($this->porcentajeAir2->Value) && !strlen($this->porcentajeAir2->Value) && $this->porcentajeAir2->Value !== false)
                $this->porcentajeAir2->SetText(0);
                if(!is_array($this->valRetAir2->Value) && !strlen($this->valRetAir2->Value) && $this->valRetAir2->Value !== false)
                $this->valRetAir2->SetText(0);
                if(!is_array($this->codRetAir3->Value) && !strlen($this->codRetAir3->Value) && $this->codRetAir3->Value !== false)
                $this->codRetAir3->SetText(0);
                if(!is_array($this->baseImpAir3->Value) && !strlen($this->baseImpAir3->Value) && $this->baseImpAir3->Value !== false)
                $this->baseImpAir3->SetText(0);
                if(!is_array($this->porcentajeAir3->Value) && !strlen($this->porcentajeAir3->Value) && $this->porcentajeAir3->Value !== false)
                $this->porcentajeAir3->SetText(0);
                if(!is_array($this->valRetAir3->Value) && !strlen($this->valRetAir3->Value) && $this->valRetAir3->Value !== false)
                $this->valRetAir3->SetText(0);
                if(!is_array($this->baseImpAirTot->Value) && !strlen($this->baseImpAirTot->Value) && $this->baseImpAirTot->Value !== false)
                $this->baseImpAirTot->SetText(0);
                if(!is_array($this->valRetAirTot->Value) && !strlen($this->valRetAirTot->Value) && $this->valRetAirTot->Value !== false)
                $this->valRetAirTot->SetText(0);
                if(!is_array($this->docModificado->Value) && !strlen($this->docModificado->Value) && $this->docModificado->Value !== false)
                $this->docModificado->SetText(0);
                if(!is_array($this->estabModificado->Value) && !strlen($this->estabModificado->Value) && $this->estabModificado->Value !== false)
                $this->estabModificado->SetText(0);
                if(!is_array($this->ptoEmiModificado->Value) && !strlen($this->ptoEmiModificado->Value) && $this->ptoEmiModificado->Value !== false)
                $this->ptoEmiModificado->SetText(0);
                if(!is_array($this->secModificado->Value) && !strlen($this->secModificado->Value) && $this->secModificado->Value !== false)
                $this->secModificado->SetText(0);
                if(!is_array($this->autModificado->Value) && !strlen($this->autModificado->Value) && $this->autModificado->Value !== false)
                $this->autModificado->SetText(0);
                if(!is_array($this->contratoPartidoPolitico->Value) && !strlen($this->contratoPartidoPolitico->Value) && $this->contratoPartidoPolitico->Value !== false)
                $this->contratoPartidoPolitico->SetText(0);
                if(!is_array($this->montoTituloOneroso->Value) && !strlen($this->montoTituloOneroso->Value) && $this->montoTituloOneroso->Value !== false)
                $this->montoTituloOneroso->SetText(0);
                if(!is_array($this->montoTituloGratuito->Value) && !strlen($this->montoTituloGratuito->Value) && $this->montoTituloGratuito->Value !== false)
                $this->montoTituloGratuito->SetText(0);
                if(!is_array($this->numeroComprobantes->Value) && !strlen($this->numeroComprobantes->Value) && $this->numeroComprobantes->Value !== false)
                $this->numeroComprobantes->SetText(0.00);
                if(!is_array($this->ivaPresuntivo->Value) && !strlen($this->ivaPresuntivo->Value) && $this->ivaPresuntivo->Value !== false)
                $this->ivaPresuntivo->SetText(N);
                if(!is_array($this->retPresuntiva->Value) && !strlen($this->retPresuntiva->Value) && $this->retPresuntiva->Value !== false)
                $this->retPresuntiva->SetText(N);
                if(!is_array($this->distAduanero->Value) && !strlen($this->distAduanero->Value) && $this->distAduanero->Value !== false)
                $this->distAduanero->SetText(028);
                if(!is_array($this->facturaExportacion->Value) && !strlen($this->facturaExportacion->Value) && $this->facturaExportacion->Value !== false)
                $this->facturaExportacion->SetText(0);
                if(!is_array($this->tipImpExp->Value) && !strlen($this->tipImpExp->Value) && $this->tipImpExp->Value !== false)
                $this->tipImpExp->SetText(1);
                if(!is_array($this->valorCifFob->Value) && !strlen($this->valorCifFob->Value) && $this->valorCifFob->Value !== false)
                $this->valorCifFob->SetText(0);
                if(!is_array($this->valorFobComprobante->Value) && !strlen($this->valorFobComprobante->Value) && $this->valorFobComprobante->Value !== false)
                $this->valorFobComprobante->SetText(0);
                if(!is_array($this->fechaEmbarque->Value) && !strlen($this->fechaEmbarque->Value) && $this->fechaEmbarque->Value !== false)
                $this->fechaEmbarque->SetValue(time());
                if(!is_array($this->documEmbarque->Value) && !strlen($this->documEmbarque->Value) && $this->documEmbarque->Value !== false)
                $this->documEmbarque->SetValue(time());
                if(!is_array($this->numCajBan->Value) && !strlen($this->numCajBan->Value) && $this->numCajBan->Value !== false)
                $this->numCajBan->SetText(0);
                if(!is_array($this->precCajBan->Value) && !strlen($this->precCajBan->Value) && $this->precCajBan->Value !== false)
                $this->precCajBan->SetText(0);
                if(!is_array($this->numCajBan2->Value) && !strlen($this->numCajBan2->Value) && $this->numCajBan2->Value !== false)
                $this->numCajBan2->SetText(0);
                if(!is_array($this->precCajBan2->Value) && !strlen($this->precCajBan2->Value) && $this->precCajBan2->Value !== false)
                $this->precCajBan2->SetText(0);
                if(!is_array($this->numCajBan3->Value) && !strlen($this->numCajBan3->Value) && $this->numCajBan3->Value !== false)
                $this->numCajBan3->SetText(0);
                if(!is_array($this->precCajBan3->Value) && !strlen($this->precCajBan3->Value) && $this->precCajBan3->Value !== false)
                $this->precCajBan3->SetText(0);
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @2-085B63B1
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->ds->Parameters["urlID"] = CCGetFromGet("ID", "");
    }
//End Initialize Method

//Validate Method @2-C5540600
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->tra_Descripcion->Validate() && $Validation);
        $Validation = ($this->tipoTransac->Validate() && $Validation);
        $Validation = ($this->tra_CodCompr->Validate() && $Validation);
        $Validation = ($this->tra_Secuencial->Validate() && $Validation);
        $Validation = ($this->tra_IndProceso->Validate() && $Validation);
        $Validation = ($this->devIva->Validate() && $Validation);
        $Validation = ($this->sus_Descripcion->Validate() && $Validation);
        $Validation = ($this->codSustento->Validate() && $Validation);
        $Validation = ($this->txt_ProvDescripcion->Validate() && $Validation);
        $Validation = ($this->tpIdProv->Validate() && $Validation);
        $Validation = ($this->codProv->Validate() && $Validation);
        $Validation = ($this->txt_rucProv->Validate() && $Validation);
        $Validation = ($this->txt_ProvDescripcionFact->Validate() && $Validation);
        $Validation = ($this->tpIdProvFact->Validate() && $Validation);
        $Validation = ($this->idProvFact->Validate() && $Validation);
        $Validation = ($this->txt_rucProvFact->Validate() && $Validation);
        $Validation = ($this->tco_Descripcion->Validate() && $Validation);
        $Validation = ($this->tipoComprobante->Validate() && $Validation);
        $Validation = ($this->establecimiento->Validate() && $Validation);
        $Validation = ($this->puntoEmision->Validate() && $Validation);
        $Validation = ($this->secuencial->Validate() && $Validation);
        $Validation = ($this->fechaRegistro->Validate() && $Validation);
        $Validation = ($this->autorizacion->Validate() && $Validation);
        $Validation = ($this->tmp_FecEmision->Validate() && $Validation);
        $Validation = ($this->tmp_FecCaduc->Validate() && $Validation);
        $Validation = ($this->aut_NroInicial->Validate() && $Validation);
        $Validation = ($this->aut_NroFinal->Validate() && $Validation);
        $Validation = ($this->tmp_IndicTransComp->Validate() && $Validation);
        $Validation = ($this->fechaEmision->Validate() && $Validation);
        $Validation = ($this->tmp_Descripcion->Validate() && $Validation);
        $Validation = ($this->tco_Secuencial->Validate() && $Validation);
        $Validation = ($this->tco_Sustento->Validate() && $Validation);
        $Validation = ($this->civ_FecInic->Validate() && $Validation);
        $Validation = ($this->civ_FecFin->Validate() && $Validation);
        $Validation = ($this->sus_CodCompr->Validate() && $Validation);
        $Validation = ($this->cra_Porcent->Validate() && $Validation);
        $Validation = ($this->cra_FecIni->Validate() && $Validation);
        $Validation = ($this->cra_FecFin->Validate() && $Validation);
        $Validation = ($this->cra_Proceso->Validate() && $Validation);
        $Validation = ($this->ID->Validate() && $Validation);
        $Validation = ($this->hdRegNumero->Validate() && $Validation);
        $Validation = ($this->hdTipoComp->Validate() && $Validation);
        $Validation = ($this->hdNumComp->Validate() && $Validation);
        $Validation = ($this->baseImponible->Validate() && $Validation);
        $Validation = ($this->baseImpGrav->Validate() && $Validation);
        $Validation = ($this->baseNoGraIva->Validate() && $Validation);
        $Validation = ($this->baseImpExe->Validate() && $Validation);
        $Validation = ($this->civ_Descripcion->Validate() && $Validation);
        $Validation = ($this->porcentajeIva->Validate() && $Validation);
        $Validation = ($this->civ_Porcent->Validate() && $Validation);
        $Validation = ($this->montoIva->Validate() && $Validation);
        $Validation = ($this->baseImpIce->Validate() && $Validation);
        $Validation = ($this->pic_Descripcion->Validate() && $Validation);
        $Validation = ($this->porcentajeIce->Validate() && $Validation);
        $Validation = ($this->pic_porcent->Validate() && $Validation);
        $Validation = ($this->montoIce->Validate() && $Validation);
        $Validation = ($this->montoIvaBienes->Validate() && $Validation);
        $Validation = ($this->prb_Descripcion->Validate() && $Validation);
        $Validation = ($this->porRetBienes->Validate() && $Validation);
        $Validation = ($this->prb_Porcent->Validate() && $Validation);
        $Validation = ($this->valorRetBienes->Validate() && $Validation);
        $Validation = ($this->montoIvaServicios->Validate() && $Validation);
        $Validation = ($this->prs_Descripcion->Validate() && $Validation);
        $Validation = ($this->porRetServicios->Validate() && $Validation);
        $Validation = ($this->prs_Porcent->Validate() && $Validation);
        $Validation = ($this->valorRetServicios->Validate() && $Validation);
        $Validation = ($this->estabRetencion1->Validate() && $Validation);
        $Validation = ($this->puntoEmiRetencion1->Validate() && $Validation);
        $Validation = ($this->secRetencion1->Validate() && $Validation);
        $Validation = ($this->autRetencion1->Validate() && $Validation);
        $Validation = ($this->fechaEmiRet1->Validate() && $Validation);
        $Validation = ($this->cra_Descripcion->Validate() && $Validation);
        $Validation = ($this->codRetAir->Validate() && $Validation);
        $Validation = ($this->baseImpAir->Validate() && $Validation);
        $Validation = ($this->porcentajeAir->Validate() && $Validation);
        $Validation = ($this->valRetAir->Validate() && $Validation);
        $Validation = ($this->cra_Descripcion2->Validate() && $Validation);
        $Validation = ($this->codRetAir2->Validate() && $Validation);
        $Validation = ($this->baseImpAir2->Validate() && $Validation);
        $Validation = ($this->porcentajeAir2->Validate() && $Validation);
        $Validation = ($this->valRetAir2->Validate() && $Validation);
        $Validation = ($this->cra_Descripcion3->Validate() && $Validation);
        $Validation = ($this->codRetAir3->Validate() && $Validation);
        $Validation = ($this->baseImpAir3->Validate() && $Validation);
        $Validation = ($this->porcentajeAir3->Validate() && $Validation);
        $Validation = ($this->valRetAir3->Validate() && $Validation);
        $Validation = ($this->baseImpAirTot->Validate() && $Validation);
        $Validation = ($this->valRetAirTot->Validate() && $Validation);
        $Validation = ($this->ccm_Descripcion->Validate() && $Validation);
        $Validation = ($this->docModificado->Validate() && $Validation);
        $Validation = ($this->fechaEmiModificado->Validate() && $Validation);
        $Validation = ($this->estabModificado->Validate() && $Validation);
        $Validation = ($this->ptoEmiModificado->Validate() && $Validation);
        $Validation = ($this->secModificado->Validate() && $Validation);
        $Validation = ($this->autModificado->Validate() && $Validation);
        $Validation = ($this->contratoPartidoPolitico->Validate() && $Validation);
        $Validation = ($this->montoTituloOneroso->Validate() && $Validation);
        $Validation = ($this->montoTituloGratuito->Validate() && $Validation);
        $Validation = ($this->numeroComprobantes->Validate() && $Validation);
        $Validation = ($this->ivaPresuntivo->Validate() && $Validation);
        $Validation = ($this->retPresuntiva->Validate() && $Validation);
        $Validation = ($this->distAduanero->Validate() && $Validation);
        $Validation = ($this->anio->Validate() && $Validation);
        $Validation = ($this->regimen->Validate() && $Validation);
        $Validation = ($this->correlativo->Validate() && $Validation);
        $Validation = ($this->verificador->Validate() && $Validation);
        $Validation = ($this->numCajBan->Validate() && $Validation);
        $Validation = ($this->precCajBan->Validate() && $Validation);
        $Validation = ($this->numCajBan2->Validate() && $Validation);
        $Validation = ($this->precCajBan2->Validate() && $Validation);
        $Validation = ($this->numCajBan3->Validate() && $Validation);
        $Validation = ($this->precCajBan3->Validate() && $Validation);
        $Validation = ($this->tce_Descripcion->Validate() && $Validation);
        $Validation = ($this->facturaExportacion->Validate() && $Validation);
        $Validation = ($this->tipImpExp->Validate() && $Validation);
        $Validation = ($this->valorCifFob->Validate() && $Validation);
        $Validation = ($this->valorFobComprobante->Validate() && $Validation);
        $Validation = ($this->fechaEmbarque->Validate() && $Validation);
        $Validation = ($this->documEmbarque->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        $Validation =  $Validation && ($this->tra_Descripcion->Errors->Count() == 0);
        $Validation =  $Validation && ($this->tipoTransac->Errors->Count() == 0);
        $Validation =  $Validation && ($this->tra_CodCompr->Errors->Count() == 0);
        $Validation =  $Validation && ($this->tra_Secuencial->Errors->Count() == 0);
        $Validation =  $Validation && ($this->tra_IndProceso->Errors->Count() == 0);
        $Validation =  $Validation && ($this->devIva->Errors->Count() == 0);
        $Validation =  $Validation && ($this->sus_Descripcion->Errors->Count() == 0);
        $Validation =  $Validation && ($this->codSustento->Errors->Count() == 0);
        $Validation =  $Validation && ($this->txt_ProvDescripcion->Errors->Count() == 0);
        $Validation =  $Validation && ($this->tpIdProv->Errors->Count() == 0);
        $Validation =  $Validation && ($this->codProv->Errors->Count() == 0);
        $Validation =  $Validation && ($this->txt_rucProv->Errors->Count() == 0);
        $Validation =  $Validation && ($this->txt_ProvDescripcionFact->Errors->Count() == 0);
        $Validation =  $Validation && ($this->tpIdProvFact->Errors->Count() == 0);
        $Validation =  $Validation && ($this->idProvFact->Errors->Count() == 0);
        $Validation =  $Validation && ($this->txt_rucProvFact->Errors->Count() == 0);
        $Validation =  $Validation && ($this->tco_Descripcion->Errors->Count() == 0);
        $Validation =  $Validation && ($this->tipoComprobante->Errors->Count() == 0);
        $Validation =  $Validation && ($this->establecimiento->Errors->Count() == 0);
        $Validation =  $Validation && ($this->puntoEmision->Errors->Count() == 0);
        $Validation =  $Validation && ($this->secuencial->Errors->Count() == 0);
        $Validation =  $Validation && ($this->fechaRegistro->Errors->Count() == 0);
        $Validation =  $Validation && ($this->autorizacion->Errors->Count() == 0);
        $Validation =  $Validation && ($this->tmp_FecEmision->Errors->Count() == 0);
        $Validation =  $Validation && ($this->tmp_FecCaduc->Errors->Count() == 0);
        $Validation =  $Validation && ($this->aut_NroInicial->Errors->Count() == 0);
        $Validation =  $Validation && ($this->aut_NroFinal->Errors->Count() == 0);
        $Validation =  $Validation && ($this->tmp_IndicTransComp->Errors->Count() == 0);
        $Validation =  $Validation && ($this->fechaEmision->Errors->Count() == 0);
        $Validation =  $Validation && ($this->tmp_Descripcion->Errors->Count() == 0);
        $Validation =  $Validation && ($this->tco_Secuencial->Errors->Count() == 0);
        $Validation =  $Validation && ($this->tco_Sustento->Errors->Count() == 0);
        $Validation =  $Validation && ($this->civ_FecInic->Errors->Count() == 0);
        $Validation =  $Validation && ($this->civ_FecFin->Errors->Count() == 0);
        $Validation =  $Validation && ($this->sus_CodCompr->Errors->Count() == 0);
        $Validation =  $Validation && ($this->cra_Porcent->Errors->Count() == 0);
        $Validation =  $Validation && ($this->cra_FecIni->Errors->Count() == 0);
        $Validation =  $Validation && ($this->cra_FecFin->Errors->Count() == 0);
        $Validation =  $Validation && ($this->cra_Proceso->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ID->Errors->Count() == 0);
        $Validation =  $Validation && ($this->hdRegNumero->Errors->Count() == 0);
        $Validation =  $Validation && ($this->hdTipoComp->Errors->Count() == 0);
        $Validation =  $Validation && ($this->hdNumComp->Errors->Count() == 0);
        $Validation =  $Validation && ($this->baseImponible->Errors->Count() == 0);
        $Validation =  $Validation && ($this->baseImpGrav->Errors->Count() == 0);
        $Validation =  $Validation && ($this->baseNoGraIva->Errors->Count() == 0);
        $Validation =  $Validation && ($this->baseImpExe->Errors->Count() == 0);
        $Validation =  $Validation && ($this->civ_Descripcion->Errors->Count() == 0);
        $Validation =  $Validation && ($this->porcentajeIva->Errors->Count() == 0);
        $Validation =  $Validation && ($this->civ_Porcent->Errors->Count() == 0);
        $Validation =  $Validation && ($this->montoIva->Errors->Count() == 0);
        $Validation =  $Validation && ($this->baseImpIce->Errors->Count() == 0);
        $Validation =  $Validation && ($this->pic_Descripcion->Errors->Count() == 0);
        $Validation =  $Validation && ($this->porcentajeIce->Errors->Count() == 0);
        $Validation =  $Validation && ($this->pic_porcent->Errors->Count() == 0);
        $Validation =  $Validation && ($this->montoIce->Errors->Count() == 0);
        $Validation =  $Validation && ($this->montoIvaBienes->Errors->Count() == 0);
        $Validation =  $Validation && ($this->prb_Descripcion->Errors->Count() == 0);
        $Validation =  $Validation && ($this->porRetBienes->Errors->Count() == 0);
        $Validation =  $Validation && ($this->prb_Porcent->Errors->Count() == 0);
        $Validation =  $Validation && ($this->valorRetBienes->Errors->Count() == 0);
        $Validation =  $Validation && ($this->montoIvaServicios->Errors->Count() == 0);
        $Validation =  $Validation && ($this->prs_Descripcion->Errors->Count() == 0);
        $Validation =  $Validation && ($this->porRetServicios->Errors->Count() == 0);
        $Validation =  $Validation && ($this->prs_Porcent->Errors->Count() == 0);
        $Validation =  $Validation && ($this->valorRetServicios->Errors->Count() == 0);
        $Validation =  $Validation && ($this->estabRetencion1->Errors->Count() == 0);
        $Validation =  $Validation && ($this->puntoEmiRetencion1->Errors->Count() == 0);
        $Validation =  $Validation && ($this->secRetencion1->Errors->Count() == 0);
        $Validation =  $Validation && ($this->autRetencion1->Errors->Count() == 0);
        $Validation =  $Validation && ($this->fechaEmiRet1->Errors->Count() == 0);
        $Validation =  $Validation && ($this->cra_Descripcion->Errors->Count() == 0);
        $Validation =  $Validation && ($this->codRetAir->Errors->Count() == 0);
        $Validation =  $Validation && ($this->baseImpAir->Errors->Count() == 0);
        $Validation =  $Validation && ($this->porcentajeAir->Errors->Count() == 0);
        $Validation =  $Validation && ($this->valRetAir->Errors->Count() == 0);
        $Validation =  $Validation && ($this->cra_Descripcion2->Errors->Count() == 0);
        $Validation =  $Validation && ($this->codRetAir2->Errors->Count() == 0);
        $Validation =  $Validation && ($this->baseImpAir2->Errors->Count() == 0);
        $Validation =  $Validation && ($this->porcentajeAir2->Errors->Count() == 0);
        $Validation =  $Validation && ($this->valRetAir2->Errors->Count() == 0);
        $Validation =  $Validation && ($this->cra_Descripcion3->Errors->Count() == 0);
        $Validation =  $Validation && ($this->codRetAir3->Errors->Count() == 0);
        $Validation =  $Validation && ($this->baseImpAir3->Errors->Count() == 0);
        $Validation =  $Validation && ($this->porcentajeAir3->Errors->Count() == 0);
        $Validation =  $Validation && ($this->valRetAir3->Errors->Count() == 0);
        $Validation =  $Validation && ($this->baseImpAirTot->Errors->Count() == 0);
        $Validation =  $Validation && ($this->valRetAirTot->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ccm_Descripcion->Errors->Count() == 0);
        $Validation =  $Validation && ($this->docModificado->Errors->Count() == 0);
        $Validation =  $Validation && ($this->fechaEmiModificado->Errors->Count() == 0);
        $Validation =  $Validation && ($this->estabModificado->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ptoEmiModificado->Errors->Count() == 0);
        $Validation =  $Validation && ($this->secModificado->Errors->Count() == 0);
        $Validation =  $Validation && ($this->autModificado->Errors->Count() == 0);
        $Validation =  $Validation && ($this->contratoPartidoPolitico->Errors->Count() == 0);
        $Validation =  $Validation && ($this->montoTituloOneroso->Errors->Count() == 0);
        $Validation =  $Validation && ($this->montoTituloGratuito->Errors->Count() == 0);
        $Validation =  $Validation && ($this->numeroComprobantes->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ivaPresuntivo->Errors->Count() == 0);
        $Validation =  $Validation && ($this->retPresuntiva->Errors->Count() == 0);
        $Validation =  $Validation && ($this->distAduanero->Errors->Count() == 0);
        $Validation =  $Validation && ($this->anio->Errors->Count() == 0);
        $Validation =  $Validation && ($this->regimen->Errors->Count() == 0);
        $Validation =  $Validation && ($this->correlativo->Errors->Count() == 0);
        $Validation =  $Validation && ($this->verificador->Errors->Count() == 0);
        $Validation =  $Validation && ($this->numCajBan->Errors->Count() == 0);
        $Validation =  $Validation && ($this->precCajBan->Errors->Count() == 0);
        $Validation =  $Validation && ($this->numCajBan2->Errors->Count() == 0);
        $Validation =  $Validation && ($this->precCajBan2->Errors->Count() == 0);
        $Validation =  $Validation && ($this->numCajBan3->Errors->Count() == 0);
        $Validation =  $Validation && ($this->precCajBan3->Errors->Count() == 0);
        $Validation =  $Validation && ($this->tce_Descripcion->Errors->Count() == 0);
        $Validation =  $Validation && ($this->facturaExportacion->Errors->Count() == 0);
        $Validation =  $Validation && ($this->tipImpExp->Errors->Count() == 0);
        $Validation =  $Validation && ($this->valorCifFob->Errors->Count() == 0);
        $Validation =  $Validation && ($this->valorFobComprobante->Errors->Count() == 0);
        $Validation =  $Validation && ($this->fechaEmbarque->Errors->Count() == 0);
        $Validation =  $Validation && ($this->documEmbarque->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-0CB917A0
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->Label2->Errors->Count());
        $errors = ($errors || $this->Label1->Errors->Count());
        $errors = ($errors || $this->tra_Descripcion->Errors->Count());
        $errors = ($errors || $this->tipoTransac->Errors->Count());
        $errors = ($errors || $this->tra_CodCompr->Errors->Count());
        $errors = ($errors || $this->tra_Secuencial->Errors->Count());
        $errors = ($errors || $this->tra_IndProceso->Errors->Count());
        $errors = ($errors || $this->devIva->Errors->Count());
        $errors = ($errors || $this->sus_Descripcion->Errors->Count());
        $errors = ($errors || $this->codSustento->Errors->Count());
        $errors = ($errors || $this->txt_ProvDescripcion->Errors->Count());
        $errors = ($errors || $this->tpIdProv->Errors->Count());
        $errors = ($errors || $this->codProv->Errors->Count());
        $errors = ($errors || $this->txt_rucProv->Errors->Count());
        $errors = ($errors || $this->txt_ProvDescripcionFact->Errors->Count());
        $errors = ($errors || $this->tpIdProvFact->Errors->Count());
        $errors = ($errors || $this->idProvFact->Errors->Count());
        $errors = ($errors || $this->txt_rucProvFact->Errors->Count());
        $errors = ($errors || $this->tco_Descripcion->Errors->Count());
        $errors = ($errors || $this->tipoComprobante->Errors->Count());
        $errors = ($errors || $this->establecimiento->Errors->Count());
        $errors = ($errors || $this->puntoEmision->Errors->Count());
        $errors = ($errors || $this->secuencial->Errors->Count());
        $errors = ($errors || $this->fechaRegistro->Errors->Count());
        $errors = ($errors || $this->DatePicker_fechaRegistro->Errors->Count());
        $errors = ($errors || $this->autorizacion->Errors->Count());
        $errors = ($errors || $this->tmp_FecEmision->Errors->Count());
        $errors = ($errors || $this->tmp_FecCaduc->Errors->Count());
        $errors = ($errors || $this->aut_NroInicial->Errors->Count());
        $errors = ($errors || $this->aut_NroFinal->Errors->Count());
        $errors = ($errors || $this->tmp_IndicTransComp->Errors->Count());
        $errors = ($errors || $this->fechaEmision->Errors->Count());
        $errors = ($errors || $this->tmp_Descripcion->Errors->Count());
        $errors = ($errors || $this->tco_Secuencial->Errors->Count());
        $errors = ($errors || $this->tco_Sustento->Errors->Count());
        $errors = ($errors || $this->civ_FecInic->Errors->Count());
        $errors = ($errors || $this->civ_FecFin->Errors->Count());
        $errors = ($errors || $this->sus_CodCompr->Errors->Count());
        $errors = ($errors || $this->cra_Porcent->Errors->Count());
        $errors = ($errors || $this->cra_FecIni->Errors->Count());
        $errors = ($errors || $this->cra_FecFin->Errors->Count());
        $errors = ($errors || $this->cra_Proceso->Errors->Count());
        $errors = ($errors || $this->ID->Errors->Count());
        $errors = ($errors || $this->hdRegNumero->Errors->Count());
        $errors = ($errors || $this->hdTipoComp->Errors->Count());
        $errors = ($errors || $this->hdNumComp->Errors->Count());
        $errors = ($errors || $this->baseImponible->Errors->Count());
        $errors = ($errors || $this->baseImpGrav->Errors->Count());
        $errors = ($errors || $this->baseNoGraIva->Errors->Count());
        $errors = ($errors || $this->baseImpExe->Errors->Count());
        $errors = ($errors || $this->civ_Descripcion->Errors->Count());
        $errors = ($errors || $this->porcentajeIva->Errors->Count());
        $errors = ($errors || $this->civ_Porcent->Errors->Count());
        $errors = ($errors || $this->montoIva->Errors->Count());
        $errors = ($errors || $this->baseImpIce->Errors->Count());
        $errors = ($errors || $this->pic_Descripcion->Errors->Count());
        $errors = ($errors || $this->porcentajeIce->Errors->Count());
        $errors = ($errors || $this->pic_porcent->Errors->Count());
        $errors = ($errors || $this->montoIce->Errors->Count());
        $errors = ($errors || $this->montoIvaBienes->Errors->Count());
        $errors = ($errors || $this->prb_Descripcion->Errors->Count());
        $errors = ($errors || $this->porRetBienes->Errors->Count());
        $errors = ($errors || $this->prb_Porcent->Errors->Count());
        $errors = ($errors || $this->valorRetBienes->Errors->Count());
        $errors = ($errors || $this->montoIvaServicios->Errors->Count());
        $errors = ($errors || $this->prs_Descripcion->Errors->Count());
        $errors = ($errors || $this->porRetServicios->Errors->Count());
        $errors = ($errors || $this->prs_Porcent->Errors->Count());
        $errors = ($errors || $this->valorRetServicios->Errors->Count());
        $errors = ($errors || $this->estabRetencion1->Errors->Count());
        $errors = ($errors || $this->puntoEmiRetencion1->Errors->Count());
        $errors = ($errors || $this->secRetencion1->Errors->Count());
        $errors = ($errors || $this->autRetencion1->Errors->Count());
        $errors = ($errors || $this->fechaEmiRet1->Errors->Count());
        $errors = ($errors || $this->cra_Descripcion->Errors->Count());
        $errors = ($errors || $this->codRetAir->Errors->Count());
        $errors = ($errors || $this->baseImpAir->Errors->Count());
        $errors = ($errors || $this->porcentajeAir->Errors->Count());
        $errors = ($errors || $this->valRetAir->Errors->Count());
        $errors = ($errors || $this->cra_Descripcion2->Errors->Count());
        $errors = ($errors || $this->codRetAir2->Errors->Count());
        $errors = ($errors || $this->baseImpAir2->Errors->Count());
        $errors = ($errors || $this->porcentajeAir2->Errors->Count());
        $errors = ($errors || $this->valRetAir2->Errors->Count());
        $errors = ($errors || $this->cra_Descripcion3->Errors->Count());
        $errors = ($errors || $this->codRetAir3->Errors->Count());
        $errors = ($errors || $this->baseImpAir3->Errors->Count());
        $errors = ($errors || $this->porcentajeAir3->Errors->Count());
        $errors = ($errors || $this->valRetAir3->Errors->Count());
        $errors = ($errors || $this->baseImpAirTot->Errors->Count());
        $errors = ($errors || $this->valRetAirTot->Errors->Count());
        $errors = ($errors || $this->ccm_Descripcion->Errors->Count());
        $errors = ($errors || $this->docModificado->Errors->Count());
        $errors = ($errors || $this->fechaEmiModificado->Errors->Count());
        $errors = ($errors || $this->DatePicker_fechaEmiModificado->Errors->Count());
        $errors = ($errors || $this->estabModificado->Errors->Count());
        $errors = ($errors || $this->ptoEmiModificado->Errors->Count());
        $errors = ($errors || $this->secModificado->Errors->Count());
        $errors = ($errors || $this->autModificado->Errors->Count());
        $errors = ($errors || $this->contratoPartidoPolitico->Errors->Count());
        $errors = ($errors || $this->montoTituloOneroso->Errors->Count());
        $errors = ($errors || $this->montoTituloGratuito->Errors->Count());
        $errors = ($errors || $this->numeroComprobantes->Errors->Count());
        $errors = ($errors || $this->ivaPresuntivo->Errors->Count());
        $errors = ($errors || $this->retPresuntiva->Errors->Count());
        $errors = ($errors || $this->distAduanero->Errors->Count());
        $errors = ($errors || $this->anio->Errors->Count());
        $errors = ($errors || $this->regimen->Errors->Count());
        $errors = ($errors || $this->correlativo->Errors->Count());
        $errors = ($errors || $this->verificador->Errors->Count());
        $errors = ($errors || $this->numCajBan->Errors->Count());
        $errors = ($errors || $this->precCajBan->Errors->Count());
        $errors = ($errors || $this->numCajBan2->Errors->Count());
        $errors = ($errors || $this->precCajBan2->Errors->Count());
        $errors = ($errors || $this->numCajBan3->Errors->Count());
        $errors = ($errors || $this->precCajBan3->Errors->Count());
        $errors = ($errors || $this->tce_Descripcion->Errors->Count());
        $errors = ($errors || $this->facturaExportacion->Errors->Count());
        $errors = ($errors || $this->tipImpExp->Errors->Count());
        $errors = ($errors || $this->valorCifFob->Errors->Count());
        $errors = ($errors || $this->valorFobComprobante->Errors->Count());
        $errors = ($errors || $this->fechaEmbarque->Errors->Count());
        $errors = ($errors || $this->documEmbarque->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-D10AA424
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        $this->ds->Prepare();
        if(!$this->FormSubmitted) {
            $this->EditMode = $this->ds->AllParametersSet;
            return;
        }

        if($this->FormSubmitted) {
            $this->PressedButton = $this->EditMode ? "Button_Update" : "Button_Insert";
            if(strlen(CCGetParam("btn_ProvCont", ""))) {
                $this->PressedButton = "btn_ProvCont";
            } else if(strlen(CCGetParam("btn_ProvFisc", ""))) {
                $this->PressedButton = "btn_ProvFisc";
            } else if(strlen(CCGetParam("Button_Insert", ""))) {
                $this->PressedButton = "Button_Insert";
            } else if(strlen(CCGetParam("Button_Update", ""))) {
                $this->PressedButton = "Button_Update";
            } else if(strlen(CCGetParam("Button1", ""))) {
                $this->PressedButton = "Button1";
            } else if(strlen(CCGetParam("Button2", ""))) {
                $this->PressedButton = "Button2";
            } else if(strlen(CCGetParam("Button_Delete", ""))) {
                $this->PressedButton = "Button_Delete";
            } else if(strlen(CCGetParam("Button_Cancel", ""))) {
                $this->PressedButton = "Button_Cancel";
            }
        }
        $Redirect = "CoRtTr_cmpvtamant.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "Button2") {
            if(!CCGetEvent($this->Button2->CCSEvents, "OnClick")) {
                $Redirect = "";
            } else {
                $Redirect = "CoRtTr_cmpvtamant.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm", "ID"));
            }
        } else if($this->PressedButton == "Button_Delete") {
            if(!CCGetEvent($this->Button_Delete->CCSEvents, "OnClick") || !$this->DeleteRow()) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "Button_Cancel") {
            if(!CCGetEvent($this->Button_Cancel->CCSEvents, "OnClick")) {
                $Redirect = "";
            }
        } else if($this->Validate()) {
            if($this->PressedButton == "btn_ProvCont") {
                if(!CCGetEvent($this->btn_ProvCont->CCSEvents, "OnClick")) {
                    $Redirect = "";
                }
            } else if($this->PressedButton == "btn_ProvFisc") {
                if(!CCGetEvent($this->btn_ProvFisc->CCSEvents, "OnClick")) {
                    $Redirect = "";
                }
            } else if($this->PressedButton == "Button_Insert") {
                if(!CCGetEvent($this->Button_Insert->CCSEvents, "OnClick") || !$this->InsertRow()) {
                    $Redirect = "";
                }
            } else if($this->PressedButton == "Button_Update") {
                if(!CCGetEvent($this->Button_Update->CCSEvents, "OnClick") || !$this->UpdateRow()) {
                    $Redirect = "";
                }
            } else if($this->PressedButton == "Button1") {
                if(!CCGetEvent($this->Button1->CCSEvents, "OnClick")) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//InsertRow Method @2-EB92C967
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert");
        if(!$this->InsertAllowed) return false;
        $this->ds->tipoTransac->SetValue($this->tipoTransac->GetValue());
        $this->ds->codSustento->SetValue($this->codSustento->GetValue());
        $this->ds->devIva->SetValue($this->devIva->GetValue());
        $this->ds->tipoComprobante->SetValue($this->tipoComprobante->GetValue());
        $this->ds->establecimiento->SetValue($this->establecimiento->GetValue());
        $this->ds->puntoEmision->SetValue($this->puntoEmision->GetValue());
        $this->ds->secuencial->SetValue($this->secuencial->GetValue());
        $this->ds->fechaRegistro->SetValue($this->fechaRegistro->GetValue());
        $this->ds->autorizacion->SetValue($this->autorizacion->GetValue());
        $this->ds->baseImponible->SetValue($this->baseImponible->GetValue());
        $this->ds->montoIvaBienes->SetValue($this->montoIvaBienes->GetValue());
        $this->ds->porRetBienes->SetValue($this->porRetBienes->GetValue());
        $this->ds->valorRetBienes->SetValue($this->valorRetBienes->GetValue());
        $this->ds->baseImpGrav->SetValue($this->baseImpGrav->GetValue());
        $this->ds->porcentajeIva->SetValue($this->porcentajeIva->GetValue());
        $this->ds->montoIva->SetValue($this->montoIva->GetValue());
        $this->ds->montoIvaServicios->SetValue($this->montoIvaServicios->GetValue());
        $this->ds->porRetServicios->SetValue($this->porRetServicios->GetValue());
        $this->ds->valorRetServicios->SetValue($this->valorRetServicios->GetValue());
        $this->ds->baseNoGraIva->SetValue($this->baseNoGraIva->GetValue());
        $this->ds->baseImpExe->SetValue($this->baseImpExe->GetValue());
        $this->ds->baseImpIce->SetValue($this->baseImpIce->GetValue());
        $this->ds->porcentajeIce->SetValue($this->porcentajeIce->GetValue());
        $this->ds->montoIce->SetValue($this->montoIce->GetValue());
        $this->ds->baseImpAir->SetValue($this->baseImpAir->GetValue());
        $this->ds->codRetAir->SetValue($this->codRetAir->GetValue());
        $this->ds->porcentajeAir->SetValue($this->porcentajeAir->GetValue());
        $this->ds->valRetAir->SetValue($this->valRetAir->GetValue());
        $this->ds->estabRetencion1->SetValue($this->estabRetencion1->GetValue());
        $this->ds->puntoEmiRetencion1->SetValue($this->puntoEmiRetencion1->GetValue());
        $this->ds->secRetencion1->SetValue($this->secRetencion1->GetValue());
        $this->ds->autRetencion1->SetValue($this->autRetencion1->GetValue());
        $this->ds->docModificado->SetValue($this->docModificado->GetValue());
        $this->ds->fechaEmiModificado->SetValue($this->fechaEmiModificado->GetValue());
        $this->ds->estabModificado->SetValue($this->estabModificado->GetValue());
        $this->ds->ptoEmiModificado->SetValue($this->ptoEmiModificado->GetValue());
        $this->ds->secModificado->SetValue($this->secModificado->GetValue());
        $this->ds->autModificado->SetValue($this->autModificado->GetValue());
        $this->ds->contratoPartidoPolitico->SetValue($this->contratoPartidoPolitico->GetValue());
        $this->ds->montoTituloOneroso->SetValue($this->montoTituloOneroso->GetValue());
        $this->ds->montoTituloGratuito->SetValue($this->montoTituloGratuito->GetValue());
        $this->ds->numeroComprobantes->SetValue($this->numeroComprobantes->GetValue());
        $this->ds->ivaPresuntivo->SetValue($this->ivaPresuntivo->GetValue());
        $this->ds->retPresuntiva->SetValue($this->retPresuntiva->GetValue());
        $this->ds->codProv->SetValue($this->codProv->GetValue());
        $this->ds->tpIdProvFact->SetValue($this->tpIdProvFact->GetValue());
        $this->ds->idProvFact->SetValue($this->idProvFact->GetValue());
        $this->ds->codRetAir2->SetValue($this->codRetAir2->GetValue());
        $this->ds->baseImpAir2->SetValue($this->baseImpAir2->GetValue());
        $this->ds->porcentajeAir2->SetValue($this->porcentajeAir2->GetValue());
        $this->ds->valRetAir2->SetValue($this->valRetAir2->GetValue());
        $this->ds->codRetAir3->SetValue($this->codRetAir3->GetValue());
        $this->ds->baseImpAir3->SetValue($this->baseImpAir3->GetValue());
        $this->ds->valRetAir3->SetValue($this->valRetAir3->GetValue());
        $this->ds->porcentajeAir3->SetValue($this->porcentajeAir3->GetValue());
        $this->ds->distAduanero->SetValue($this->distAduanero->GetValue());
        $this->ds->anio->SetValue($this->anio->GetValue());
        $this->ds->regimen->SetValue($this->regimen->GetValue());
        $this->ds->correlativo->SetValue($this->correlativo->GetValue());
        $this->ds->verificador->SetValue($this->verificador->GetValue());
        $this->ds->numCajBan->SetValue($this->numCajBan->GetValue());
        $this->ds->precCajBan->SetValue($this->precCajBan->GetValue());
        $this->ds->numCajBan2->SetValue($this->numCajBan2->GetValue());
        $this->ds->precCajBan2->SetValue($this->precCajBan2->GetValue());
        $this->ds->numCajBan3->SetValue($this->numCajBan3->GetValue());
        $this->ds->precCajBan3->SetValue($this->precCajBan3->GetValue());
        $this->ds->facturaExportacion->SetValue($this->facturaExportacion->GetValue());
        $this->ds->tipImpExp->SetValue($this->tipImpExp->GetValue());
        $this->ds->valorCifFob->SetValue($this->valorCifFob->GetValue());
        $this->ds->valorFobComprobante->SetValue($this->valorFobComprobante->GetValue());
        $this->ds->fechaEmision->SetValue($this->fechaEmision->GetValue());
        $this->ds->fechaEmbarque->SetValue($this->fechaEmbarque->GetValue());
        $this->ds->documEmbarque->SetValue($this->documEmbarque->GetValue());
        $this->ds->fechaEmiRet1->SetValue($this->fechaEmiRet1->GetValue());
        $this->ds->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert");
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @2-E9AADC9D
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate");
        if(!$this->UpdateAllowed) return false;
        $this->ds->tipoTransac->SetValue($this->tipoTransac->GetValue());
        $this->ds->codSustento->SetValue($this->codSustento->GetValue());
        $this->ds->devIva->SetValue($this->devIva->GetValue());
        $this->ds->tipoComprobante->SetValue($this->tipoComprobante->GetValue());
        $this->ds->establecimiento->SetValue($this->establecimiento->GetValue());
        $this->ds->puntoEmision->SetValue($this->puntoEmision->GetValue());
        $this->ds->secuencial->SetValue($this->secuencial->GetValue());
        $this->ds->fechaRegistro->SetValue($this->fechaRegistro->GetValue());
        $this->ds->autorizacion->SetValue($this->autorizacion->GetValue());
        $this->ds->baseImponible->SetValue($this->baseImponible->GetValue());
        $this->ds->montoIvaBienes->SetValue($this->montoIvaBienes->GetValue());
        $this->ds->porRetBienes->SetValue($this->porRetBienes->GetValue());
        $this->ds->valorRetBienes->SetValue($this->valorRetBienes->GetValue());
        $this->ds->baseImpGrav->SetValue($this->baseImpGrav->GetValue());
        $this->ds->porcentajeIva->SetValue($this->porcentajeIva->GetValue());
        $this->ds->montoIva->SetValue($this->montoIva->GetValue());
        $this->ds->montoIvaServicios->SetValue($this->montoIvaServicios->GetValue());
        $this->ds->porRetServicios->SetValue($this->porRetServicios->GetValue());
        $this->ds->valorRetServicios->SetValue($this->valorRetServicios->GetValue());
        $this->ds->baseNoGraIva->SetValue($this->baseNoGraIva->GetValue());
        $this->ds->baseImpExe->SetValue($this->baseImpExe->GetValue());
        $this->ds->baseImpIce->SetValue($this->baseImpIce->GetValue());
        $this->ds->porcentajeIce->SetValue($this->porcentajeIce->GetValue());
        $this->ds->montoIce->SetValue($this->montoIce->GetValue());
        $this->ds->baseImpAir->SetValue($this->baseImpAir->GetValue());
        $this->ds->codRetAir->SetValue($this->codRetAir->GetValue());
        $this->ds->porcentajeAir->SetValue($this->porcentajeAir->GetValue());
        $this->ds->valRetAir->SetValue($this->valRetAir->GetValue());
        $this->ds->estabRetencion1->SetValue($this->estabRetencion1->GetValue());
        $this->ds->puntoEmiRetencion1->SetValue($this->puntoEmiRetencion1->GetValue());
        $this->ds->secRetencion1->SetValue($this->secRetencion1->GetValue());
        $this->ds->autRetencion1->SetValue($this->autRetencion1->GetValue());
        $this->ds->docModificado->SetValue($this->docModificado->GetValue());
        $this->ds->fechaEmiModificado->SetValue($this->fechaEmiModificado->GetValue());
        $this->ds->estabModificado->SetValue($this->estabModificado->GetValue());
        $this->ds->ptoEmiModificado->SetValue($this->ptoEmiModificado->GetValue());
        $this->ds->secModificado->SetValue($this->secModificado->GetValue());
        $this->ds->autModificado->SetValue($this->autModificado->GetValue());
        $this->ds->contratoPartidoPolitico->SetValue($this->contratoPartidoPolitico->GetValue());
        $this->ds->montoTituloOneroso->SetValue($this->montoTituloOneroso->GetValue());
        $this->ds->montoTituloGratuito->SetValue($this->montoTituloGratuito->GetValue());
        $this->ds->numeroComprobantes->SetValue($this->numeroComprobantes->GetValue());
        $this->ds->ivaPresuntivo->SetValue($this->ivaPresuntivo->GetValue());
        $this->ds->retPresuntiva->SetValue($this->retPresuntiva->GetValue());
        $this->ds->codProv->SetValue($this->codProv->GetValue());
        $this->ds->idProvFact->SetValue($this->idProvFact->GetValue());
        $this->ds->tpIdProvFact->SetValue($this->tpIdProvFact->GetValue());
        $this->ds->codRetAir2->SetValue($this->codRetAir2->GetValue());
        $this->ds->baseImpAir2->SetValue($this->baseImpAir2->GetValue());
        $this->ds->porcentajeAir2->SetValue($this->porcentajeAir2->GetValue());
        $this->ds->valRetAir2->SetValue($this->valRetAir2->GetValue());
        $this->ds->codRetAir3->SetValue($this->codRetAir3->GetValue());
        $this->ds->baseImpAir3->SetValue($this->baseImpAir3->GetValue());
        $this->ds->porcentajeAir3->SetValue($this->porcentajeAir3->GetValue());
        $this->ds->valRetAir3->SetValue($this->valRetAir3->GetValue());
        $this->ds->fechaEmbarque->SetValue($this->fechaEmbarque->GetValue());
        $this->ds->valorCifFob->SetValue($this->valorCifFob->GetValue());
        $this->ds->valorFobComprobante->SetValue($this->valorFobComprobante->GetValue());
        $this->ds->fechaEmision->SetValue($this->fechaEmision->GetValue());
        $this->ds->distAduanero->SetValue($this->distAduanero->GetValue());
        $this->ds->anio->SetValue($this->anio->GetValue());
        $this->ds->regimen->SetValue($this->regimen->GetValue());
        $this->ds->regimen->SetValue($this->regimen->GetValue());
        $this->ds->correlativo->SetValue($this->correlativo->GetValue());
        $this->ds->verificador->SetValue($this->verificador->GetValue());
        $this->ds->numCajBan->SetValue($this->numCajBan->GetValue());
        $this->ds->precCajBan->SetValue($this->precCajBan->GetValue());
        $this->ds->numCajBan2->SetValue($this->numCajBan2->GetValue());
        $this->ds->precCajBan2->SetValue($this->precCajBan2->GetValue());
        $this->ds->numCajBan3->SetValue($this->numCajBan3->GetValue());
        $this->ds->precCajBan3->SetValue($this->precCajBan3->GetValue());
        $this->ds->facturaExportacion->SetValue($this->facturaExportacion->GetValue());
        $this->ds->tipImpExp->SetValue($this->tipImpExp->GetValue());
        $this->ds->documEmbarque->SetValue($this->documEmbarque->GetValue());
        $this->ds->fechaEmiRet1->SetValue($this->fechaEmiRet1->GetValue());
        $this->ds->Update();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate");
        return (!$this->CheckErrors());
    }
//End UpdateRow Method

//DeleteRow Method @2-91867A4A
    function DeleteRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeDelete");
        if(!$this->DeleteAllowed) return false;
        $this->ds->Delete();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterDelete");
        return (!$this->CheckErrors());
    }
//End DeleteRow Method

//Show Method @2-215D78A1
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->devIva->Prepare();
        $this->ivaPresuntivo->Prepare();
        $this->retPresuntiva->Prepare();
        $this->tipImpExp->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if($this->EditMode)
        {
            $this->ds->open();
            if($this->Errors->Count() == 0)
            {
                if($this->ds->Errors->Count() > 0)
                {
                    echo "Error in Record fiscompras";
                }
                else if($this->ds->next_record())
                {
                    $this->ds->SetValues();
                    $this->Label1->SetValue($this->ds->Label1->GetValue());
                    if(!$this->FormSubmitted)
                    {
                        $this->tra_Descripcion->SetValue($this->ds->tra_Descripcion->GetValue());
                        $this->tipoTransac->SetValue($this->ds->tipoTransac->GetValue());
                        $this->tra_CodCompr->SetValue($this->ds->tra_CodCompr->GetValue());
                        $this->tra_Secuencial->SetValue($this->ds->tra_Secuencial->GetValue());
                        $this->tra_IndProceso->SetValue($this->ds->tra_IndProceso->GetValue());
                        $this->devIva->SetValue($this->ds->devIva->GetValue());
                        $this->sus_Descripcion->SetValue($this->ds->sus_Descripcion->GetValue());
                        $this->codSustento->SetValue($this->ds->codSustento->GetValue());
                        $this->txt_ProvDescripcion->SetValue($this->ds->txt_ProvDescripcion->GetValue());
                        $this->tpIdProv->SetValue($this->ds->tpIdProv->GetValue());
                        $this->codProv->SetValue($this->ds->codProv->GetValue());
                        $this->txt_rucProv->SetValue($this->ds->txt_rucProv->GetValue());
                        $this->txt_ProvDescripcionFact->SetValue($this->ds->txt_ProvDescripcionFact->GetValue());
                        $this->tpIdProvFact->SetValue($this->ds->tpIdProvFact->GetValue());
                        $this->idProvFact->SetValue($this->ds->idProvFact->GetValue());
                        $this->txt_rucProvFact->SetValue($this->ds->txt_rucProvFact->GetValue());
                        $this->tco_Descripcion->SetValue($this->ds->tco_Descripcion->GetValue());
                        $this->tipoComprobante->SetValue($this->ds->tipoComprobante->GetValue());
                        $this->establecimiento->SetValue($this->ds->establecimiento->GetValue());
                        $this->puntoEmision->SetValue($this->ds->puntoEmision->GetValue());
                        $this->secuencial->SetValue($this->ds->secuencial->GetValue());
                        $this->fechaRegistro->SetValue($this->ds->fechaRegistro->GetValue());
                        $this->autorizacion->SetValue($this->ds->autorizacion->GetValue());
                        $this->tmp_FecEmision->SetValue($this->ds->tmp_FecEmision->GetValue());
                        $this->tmp_FecCaduc->SetValue($this->ds->tmp_FecCaduc->GetValue());
                        $this->aut_NroInicial->SetValue($this->ds->aut_NroInicial->GetValue());
                        $this->aut_NroFinal->SetValue($this->ds->aut_NroFinal->GetValue());
                        $this->fechaEmision->SetValue($this->ds->fechaEmision->GetValue());
                        $this->tco_Secuencial->SetValue($this->ds->tco_Secuencial->GetValue());
                        $this->tco_Sustento->SetValue($this->ds->tco_Sustento->GetValue());
                        $this->civ_FecInic->SetValue($this->ds->civ_FecInic->GetValue());
                        $this->civ_FecFin->SetValue($this->ds->civ_FecFin->GetValue());
                        $this->sus_CodCompr->SetValue($this->ds->sus_CodCompr->GetValue());
                        $this->cra_Porcent->SetValue($this->ds->cra_Porcent->GetValue());
                        $this->cra_FecIni->SetValue($this->ds->cra_FecIni->GetValue());
                        $this->cra_FecFin->SetValue($this->ds->cra_FecFin->GetValue());
                        $this->cra_Proceso->SetValue($this->ds->cra_Proceso->GetValue());
                        $this->ID->SetValue($this->ds->ID->GetValue());
                        $this->hdRegNumero->SetValue($this->ds->hdRegNumero->GetValue());
                        $this->hdTipoComp->SetValue($this->ds->hdTipoComp->GetValue());
                        $this->hdNumComp->SetValue($this->ds->hdNumComp->GetValue());
                        $this->baseImponible->SetValue($this->ds->baseImponible->GetValue());
                        $this->baseImpGrav->SetValue($this->ds->baseImpGrav->GetValue());
                        $this->baseNoGraIva->SetValue($this->ds->baseNoGraIva->GetValue());
                        $this->baseImpExe->SetValue($this->ds->baseImpExe->GetValue());
                        $this->civ_Descripcion->SetValue($this->ds->civ_Descripcion->GetValue());
                        $this->porcentajeIva->SetValue($this->ds->porcentajeIva->GetValue());
                        $this->civ_Porcent->SetValue($this->ds->civ_Porcent->GetValue());
                        $this->montoIva->SetValue($this->ds->montoIva->GetValue());
                        $this->baseImpIce->SetValue($this->ds->baseImpIce->GetValue());
                        $this->pic_Descripcion->SetValue($this->ds->pic_Descripcion->GetValue());
                        $this->porcentajeIce->SetValue($this->ds->porcentajeIce->GetValue());
                        $this->pic_porcent->SetValue($this->ds->pic_porcent->GetValue());
                        $this->montoIce->SetValue($this->ds->montoIce->GetValue());
                        $this->montoIvaBienes->SetValue($this->ds->montoIvaBienes->GetValue());
                        $this->prb_Descripcion->SetValue($this->ds->prb_Descripcion->GetValue());
                        $this->porRetBienes->SetValue($this->ds->porRetBienes->GetValue());
                        $this->prb_Porcent->SetValue($this->ds->prb_Porcent->GetValue());
                        $this->valorRetBienes->SetValue($this->ds->valorRetBienes->GetValue());
                        $this->montoIvaServicios->SetValue($this->ds->montoIvaServicios->GetValue());
                        $this->prs_Descripcion->SetValue($this->ds->prs_Descripcion->GetValue());
                        $this->porRetServicios->SetValue($this->ds->porRetServicios->GetValue());
                        $this->prs_Porcent->SetValue($this->ds->prs_Porcent->GetValue());
                        $this->valorRetServicios->SetValue($this->ds->valorRetServicios->GetValue());
                        $this->estabRetencion1->SetValue($this->ds->estabRetencion1->GetValue());
                        $this->puntoEmiRetencion1->SetValue($this->ds->puntoEmiRetencion1->GetValue());
                        $this->secRetencion1->SetValue($this->ds->secRetencion1->GetValue());
                        $this->autRetencion1->SetValue($this->ds->autRetencion1->GetValue());
                        $this->fechaEmiRet1->SetValue($this->ds->fechaEmiRet1->GetValue());
                        $this->cra_Descripcion->SetValue($this->ds->cra_Descripcion->GetValue());
                        $this->codRetAir->SetValue($this->ds->codRetAir->GetValue());
                        $this->baseImpAir->SetValue($this->ds->baseImpAir->GetValue());
                        $this->porcentajeAir->SetValue($this->ds->porcentajeAir->GetValue());
                        $this->valRetAir->SetValue($this->ds->valRetAir->GetValue());
                        $this->cra_Descripcion2->SetValue($this->ds->cra_Descripcion2->GetValue());
                        $this->codRetAir2->SetValue($this->ds->codRetAir2->GetValue());
                        $this->baseImpAir2->SetValue($this->ds->baseImpAir2->GetValue());
                        $this->porcentajeAir2->SetValue($this->ds->porcentajeAir2->GetValue());
                        $this->valRetAir2->SetValue($this->ds->valRetAir2->GetValue());
                        $this->cra_Descripcion3->SetValue($this->ds->cra_Descripcion3->GetValue());
                        $this->codRetAir3->SetValue($this->ds->codRetAir3->GetValue());
                        $this->baseImpAir3->SetValue($this->ds->baseImpAir3->GetValue());
                        $this->porcentajeAir3->SetValue($this->ds->porcentajeAir3->GetValue());
                        $this->valRetAir3->SetValue($this->ds->valRetAir3->GetValue());
                        $this->baseImpAirTot->SetValue($this->ds->baseImpAirTot->GetValue());
                        $this->valRetAirTot->SetValue($this->ds->valRetAirTot->GetValue());
                        $this->ccm_Descripcion->SetValue($this->ds->ccm_Descripcion->GetValue());
                        $this->docModificado->SetValue($this->ds->docModificado->GetValue());
                        $this->fechaEmiModificado->SetValue($this->ds->fechaEmiModificado->GetValue());
                        $this->estabModificado->SetValue($this->ds->estabModificado->GetValue());
                        $this->ptoEmiModificado->SetValue($this->ds->ptoEmiModificado->GetValue());
                        $this->secModificado->SetValue($this->ds->secModificado->GetValue());
                        $this->autModificado->SetValue($this->ds->autModificado->GetValue());
                        $this->contratoPartidoPolitico->SetValue($this->ds->contratoPartidoPolitico->GetValue());
                        $this->montoTituloOneroso->SetValue($this->ds->montoTituloOneroso->GetValue());
                        $this->montoTituloGratuito->SetValue($this->ds->montoTituloGratuito->GetValue());
                        $this->numeroComprobantes->SetValue($this->ds->numeroComprobantes->GetValue());
                        $this->ivaPresuntivo->SetValue($this->ds->ivaPresuntivo->GetValue());
                        $this->retPresuntiva->SetValue($this->ds->retPresuntiva->GetValue());
                        $this->distAduanero->SetValue($this->ds->distAduanero->GetValue());
                        $this->anio->SetValue($this->ds->anio->GetValue());
                        $this->regimen->SetValue($this->ds->regimen->GetValue());
                        $this->correlativo->SetValue($this->ds->correlativo->GetValue());
                        $this->verificador->SetValue($this->ds->verificador->GetValue());
                        $this->numCajBan->SetValue($this->ds->numCajBan->GetValue());
                        $this->precCajBan->SetValue($this->ds->precCajBan->GetValue());
                        $this->numCajBan2->SetValue($this->ds->numCajBan2->GetValue());
                        $this->precCajBan2->SetValue($this->ds->precCajBan2->GetValue());
                        $this->numCajBan3->SetValue($this->ds->numCajBan3->GetValue());
                        $this->precCajBan3->SetValue($this->ds->precCajBan3->GetValue());                       
                        $this->tce_Descripcion->SetValue($this->ds->tce_Descripcion->GetValue());
                        $this->facturaExportacion->SetValue($this->ds->facturaExportacion->GetValue());
                        $this->tipImpExp->SetValue($this->ds->tipImpExp->GetValue());
                        $this->valorCifFob->SetValue($this->ds->valorCifFob->GetValue());
                        $this->valorFobComprobante->SetValue($this->ds->valorFobComprobante->GetValue());
                        $this->fechaEmbarque->SetValue($this->ds->fechaEmbarque->GetValue());
                        $this->documEmbarque->SetValue($this->ds->documEmbarque->GetValue());
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
            $this->Label2->SetText($this->EditMode ? "MODIFICACION" : "A�ADIR");
        }
        
        if( doubleval($this->establecimiento->GetValue()) >= 0 ) {
                  $this->establecimiento->Text = CCFormatNumber($this->establecimiento->GetValue(), Array(True, 0, "", "", False, Array("0", "0", "0"), "", 1, True, ""));
        } else {
                  $this->establecimiento->Text = CCFormatNumber($this->establecimiento->GetValue(), Array(False, 0, "", "", True, "(", ")", 1, True, ""));
        }
        
        if( doubleval($this->puntoEmision->GetValue()) >= 0 ) {
                  $this->puntoEmision->Text = CCFormatNumber($this->puntoEmision->GetValue(), Array(True, 0, "", "", False, Array("0", "0", "0"), "", 1, True, ""));
        } else {
                  $this->puntoEmision->Text = CCFormatNumber($this->puntoEmision->GetValue(), Array(False, 0, "", "", True, "(", ")", 1, True, ""));
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->Label2->Errors->ToString();
            $Error .= $this->Label1->Errors->ToString();
            $Error .= $this->tra_Descripcion->Errors->ToString();
            $Error .= $this->tipoTransac->Errors->ToString();
            $Error .= $this->tra_CodCompr->Errors->ToString();
            $Error .= $this->tra_Secuencial->Errors->ToString();
            $Error .= $this->tra_IndProceso->Errors->ToString();
            $Error .= $this->devIva->Errors->ToString();
            $Error .= $this->sus_Descripcion->Errors->ToString();
            $Error .= $this->codSustento->Errors->ToString();
            $Error .= $this->txt_ProvDescripcion->Errors->ToString();
            $Error .= $this->tpIdProv->Errors->ToString();
            $Error .= $this->codProv->Errors->ToString();
            $Error .= $this->txt_rucProv->Errors->ToString();
            $Error .= $this->txt_ProvDescripcionFact->Errors->ToString();
            $Error .= $this->tpIdProvFact->Errors->ToString();
            $Error .= $this->idProvFact->Errors->ToString();
            $Error .= $this->txt_rucProvFact->Errors->ToString();
            $Error .= $this->tco_Descripcion->Errors->ToString();
            $Error .= $this->tipoComprobante->Errors->ToString();
            $Error .= $this->establecimiento->Errors->ToString();
            $Error .= $this->puntoEmision->Errors->ToString();
            $Error .= $this->secuencial->Errors->ToString();
            $Error .= $this->fechaRegistro->Errors->ToString();
            $Error .= $this->DatePicker_fechaRegistro->Errors->ToString();
            $Error .= $this->autorizacion->Errors->ToString();
            $Error .= $this->tmp_FecEmision->Errors->ToString();
            $Error .= $this->tmp_FecCaduc->Errors->ToString();
            $Error .= $this->aut_NroInicial->Errors->ToString();
            $Error .= $this->aut_NroFinal->Errors->ToString();
            $Error .= $this->tmp_IndicTransComp->Errors->ToString();
            $Error .= $this->fechaEmision->Errors->ToString();
            $Error .= $this->tmp_Descripcion->Errors->ToString();
            $Error .= $this->tco_Secuencial->Errors->ToString();
            $Error .= $this->tco_Sustento->Errors->ToString();
            $Error .= $this->civ_FecInic->Errors->ToString();
            $Error .= $this->civ_FecFin->Errors->ToString();
            $Error .= $this->sus_CodCompr->Errors->ToString();
            $Error .= $this->cra_Porcent->Errors->ToString();
            $Error .= $this->cra_FecIni->Errors->ToString();
            $Error .= $this->cra_FecFin->Errors->ToString();
            $Error .= $this->cra_Proceso->Errors->ToString();
            $Error .= $this->ID->Errors->ToString();
            $Error .= $this->hdRegNumero->Errors->ToString();
            $Error .= $this->hdTipoComp->Errors->ToString();
            $Error .= $this->hdNumComp->Errors->ToString();
            $Error .= $this->baseImponible->Errors->ToString();
            $Error .= $this->baseImpGrav->Errors->ToString();
            $Error .= $this->baseNoGraIva->Errors->ToString();
            $Error .= $this->baseImpExe->Errors->ToString();
            $Error .= $this->civ_Descripcion->Errors->ToString();
            $Error .= $this->porcentajeIva->Errors->ToString();
            $Error .= $this->civ_Porcent->Errors->ToString();
            $Error .= $this->montoIva->Errors->ToString();
            $Error .= $this->baseImpIce->Errors->ToString();
            $Error .= $this->pic_Descripcion->Errors->ToString();
            $Error .= $this->porcentajeIce->Errors->ToString();
            $Error .= $this->pic_porcent->Errors->ToString();
            $Error .= $this->montoIce->Errors->ToString();
            $Error .= $this->montoIvaBienes->Errors->ToString();
            $Error .= $this->prb_Descripcion->Errors->ToString();
            $Error .= $this->porRetBienes->Errors->ToString();
            $Error .= $this->prb_Porcent->Errors->ToString();
            $Error .= $this->valorRetBienes->Errors->ToString();
            $Error .= $this->montoIvaServicios->Errors->ToString();
            $Error .= $this->prs_Descripcion->Errors->ToString();
            $Error .= $this->porRetServicios->Errors->ToString();
            $Error .= $this->prs_Porcent->Errors->ToString();
            $Error .= $this->valorRetServicios->Errors->ToString();
            $Error .= $this->estabRetencion1->Errors->ToString();
            $Error .= $this->puntoEmiRetencion1->Errors->ToString();
            $Error .= $this->secRetencion1->Errors->ToString();
            $Error .= $this->autRetencion1->Errors->ToString();
            $Error .= $this->fechaEmiRet1->Errors->ToString();
            $Error .= $this->cra_Descripcion->Errors->ToString();
            $Error .= $this->codRetAir->Errors->ToString();
            $Error .= $this->baseImpAir->Errors->ToString();
            $Error .= $this->porcentajeAir->Errors->ToString();
            $Error .= $this->valRetAir->Errors->ToString();
            $Error .= $this->cra_Descripcion2->Errors->ToString();
            $Error .= $this->codRetAir2->Errors->ToString();
            $Error .= $this->baseImpAir2->Errors->ToString();
            $Error .= $this->porcentajeAir2->Errors->ToString();
            $Error .= $this->valRetAir2->Errors->ToString();
            $Error .= $this->cra_Descripcion3->Errors->ToString();
            $Error .= $this->codRetAir3->Errors->ToString();
            $Error .= $this->baseImpAir3->Errors->ToString();
            $Error .= $this->porcentajeAir3->Errors->ToString();
            $Error .= $this->valRetAir3->Errors->ToString();
            $Error .= $this->baseImpAirTot->Errors->ToString();
            $Error .= $this->valRetAirTot->Errors->ToString();
            $Error .= $this->ccm_Descripcion->Errors->ToString();
            $Error .= $this->docModificado->Errors->ToString();
            $Error .= $this->fechaEmiModificado->Errors->ToString();
            $Error .= $this->DatePicker_fechaEmiModificado->Errors->ToString();
            $Error .= $this->estabModificado->Errors->ToString();
            $Error .= $this->ptoEmiModificado->Errors->ToString();
            $Error .= $this->secModificado->Errors->ToString();
            $Error .= $this->autModificado->Errors->ToString();
            $Error .= $this->contratoPartidoPolitico->Errors->ToString();
            $Error .= $this->montoTituloOneroso->Errors->ToString();
            $Error .= $this->montoTituloGratuito->Errors->ToString();
            $Error .= $this->numeroComprobantes->Errors->ToString();
            $Error .= $this->ivaPresuntivo->Errors->ToString();
            $Error .= $this->retPresuntiva->Errors->ToString();
            $Error .= $this->distAduanero->Errors->ToString();
            $Error .= $this->anio->Errors->ToString();
            $Error .= $this->regimen->Errors->ToString();
            $Error .= $this->correlativo->Errors->ToString();
            $Error .= $this->verificador->Errors->ToString();
            $Error .= $this->numCajBan->Errors->ToString();
            $Error .= $this->precCajBan->Errors->ToString();
            $Error .= $this->numCajBan2->Errors->ToString();
            $Error .= $this->precCajBan2->Errors->ToString();
            $Error .= $this->numCajBan3->Errors->ToString();
            $Error .= $this->precCajBan3->Errors->ToString();
            $Error .= $this->tce_Descripcion->Errors->ToString();
            $Error .= $this->facturaExportacion->Errors->ToString();
            $Error .= $this->tipImpExp->Errors->ToString();
            $Error .= $this->valorCifFob->Errors->ToString();
            $Error .= $this->valorFobComprobante->Errors->ToString();
            $Error .= $this->fechaEmbarque->Errors->ToString();
            $Error .= $this->documEmbarque->Errors->ToString();
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

        $this->Label2->Show();
        $this->Label1->Show();
        $this->tra_Descripcion->Show();
        $this->tipoTransac->Show();
        $this->tra_CodCompr->Show();
        $this->tra_Secuencial->Show();
        $this->tra_IndProceso->Show();
        $this->devIva->Show();
        $this->sus_Descripcion->Show();
        $this->codSustento->Show();
        $this->txt_ProvDescripcion->Show();
        $this->tpIdProv->Show();
        $this->codProv->Show();
        $this->txt_rucProv->Show();
        $this->btn_ProvCont->Show();
        $this->txt_ProvDescripcionFact->Show();
        $this->tpIdProvFact->Show();
        $this->idProvFact->Show();
        $this->txt_rucProvFact->Show();
        $this->btn_ProvFisc->Show();
        $this->tco_Descripcion->Show();
        $this->tipoComprobante->Show();
        $this->establecimiento->Show();
        $this->puntoEmision->Show();
        $this->secuencial->Show();
        $this->fechaRegistro->Show();
        $this->DatePicker_fechaRegistro->Show();
        $this->autorizacion->Show();
        $this->tmp_FecEmision->Show();
        $this->tmp_FecCaduc->Show();
        $this->aut_NroInicial->Show();
        $this->aut_NroFinal->Show();
        $this->tmp_IndicTransComp->Show();
        $this->fechaEmision->Show();
        $this->tmp_Descripcion->Show();
        $this->tco_Secuencial->Show();
        $this->tco_Sustento->Show();
        $this->civ_FecInic->Show();
        $this->civ_FecFin->Show();
        $this->sus_CodCompr->Show();
        $this->cra_Porcent->Show();
        $this->cra_FecIni->Show();
        $this->cra_FecFin->Show();
        $this->cra_Proceso->Show();
        $this->ID->Show();
        $this->hdRegNumero->Show();
        $this->hdTipoComp->Show();
        $this->hdNumComp->Show();
        $this->baseImponible->Show();
        $this->baseImpGrav->Show();
        $this->baseNoGraIva->Show();
        $this->baseImpExe->Show();
        $this->civ_Descripcion->Show();
        $this->porcentajeIva->Show();
        $this->civ_Porcent->Show();
        $this->montoIva->Show();
        $this->baseImpIce->Show();
        $this->pic_Descripcion->Show();
        $this->porcentajeIce->Show();
        $this->pic_porcent->Show();
        $this->montoIce->Show();
        $this->montoIvaBienes->Show();
        $this->prb_Descripcion->Show();
        $this->porRetBienes->Show();
        $this->prb_Porcent->Show();
        $this->valorRetBienes->Show();
        $this->montoIvaServicios->Show();
        $this->prs_Descripcion->Show();
        $this->porRetServicios->Show();
        $this->prs_Porcent->Show();
        $this->valorRetServicios->Show();
        $this->estabRetencion1->Show();
        $this->puntoEmiRetencion1->Show();
        $this->secRetencion1->Show();
        $this->autRetencion1->Show();
        $this->fechaEmiRet1->Show();
        $this->cra_Descripcion->Show();
        $this->codRetAir->Show();
        $this->baseImpAir->Show();
        $this->porcentajeAir->Show();
        $this->valRetAir->Show();
        $this->cra_Descripcion2->Show();
        $this->codRetAir2->Show();
        $this->baseImpAir2->Show();
        $this->porcentajeAir2->Show();
        $this->valRetAir2->Show();
        $this->cra_Descripcion3->Show();
        $this->codRetAir3->Show();
        $this->baseImpAir3->Show();
        $this->porcentajeAir3->Show();
        $this->valRetAir3->Show();
        $this->baseImpAirTot->Show();
        $this->valRetAirTot->Show();
        $this->ccm_Descripcion->Show();
        $this->docModificado->Show();
        $this->fechaEmiModificado->Show();
        $this->DatePicker_fechaEmiModificado->Show();
        $this->estabModificado->Show();
        $this->ptoEmiModificado->Show();
        $this->secModificado->Show();
        $this->autModificado->Show();
        $this->contratoPartidoPolitico->Show();
        $this->montoTituloOneroso->Show();
        $this->montoTituloGratuito->Show();
        $this->numeroComprobantes->Show();
        $this->ivaPresuntivo->Show();
        $this->retPresuntiva->Show();
        $this->distAduanero->Show();
        $this->anio->Show();
        $this->regimen->Show();
        $this->correlativo->Show();
        $this->verificador->Show();
        $this->numCajBan->Show();
        $this->precCajBan->Show();
        $this->numCajBan2->Show();
        $this->precCajBan2->Show();
        $this->numCajBan3->Show();
        $this->precCajBan3->Show();
        $this->tce_Descripcion->Show();
        $this->facturaExportacion->Show();
        $this->tipImpExp->Show();
        $this->valorCifFob->Show();
        $this->valorFobComprobante->Show();
        $this->fechaEmbarque->Show();
        $this->documEmbarque->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button1->Show();
        $this->Button2->Show();
        $this->Button_Delete->Show();
        $this->Button_Cancel->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

} //End fiscompras Class @2-FCB6E20C

class clsfiscomprasDataSource extends clsDBdatos {  //fiscomprasDataSource Class @2-98B71689

//DataSource Variables @2-593BE7DB
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $InsertParameters;
    var $UpdateParameters;
    var $DeleteParameters;
    var $wp;
    var $AllParametersSet;


    // Datasource fields
    var $Label2;
    var $Label1;
    var $tra_Descripcion;
    var $tipoTransac;
    var $tra_CodCompr;
    var $tra_Secuencial;
    var $tra_IndProceso;
    var $devIva;
    var $sus_Descripcion;
    var $codSustento;
    var $txt_ProvDescripcion;
    var $tpIdProv;
    var $codProv;
    var $txt_rucProv;
    var $txt_ProvDescripcionFact;
    var $tpIdProvFact;
    var $idProvFact;
    var $txt_rucProvFact;
    var $tco_Descripcion;
    var $tipoComprobante;
    var $establecimiento;
    var $puntoEmision;
    var $secuencial;
    var $fechaRegistro;
    var $autorizacion;
    var $tmp_FecEmision;
    var $tmp_FecCaduc;
    var $aut_NroInicial;
    var $aut_NroFinal;
    var $tmp_IndicTransComp;
    var $fechaEmision;
    var $tmp_Descripcion;
    var $tco_Secuencial;
    var $tco_Sustento;
    var $civ_FecInic;
    var $civ_FecFin;
    var $sus_CodCompr;
    var $cra_Porcent;
    var $cra_FecIni;
    var $cra_FecFin;
    var $cra_Proceso;
    var $ID;
    var $hdRegNumero;
    var $hdTipoComp;
    var $hdNumComp;
    var $baseImponible;
    var $baseImpGrav;
    var $baseNoGraIva;
    var $baseImpExe;
    var $civ_Descripcion;
    var $porcentajeIva;
    var $civ_Porcent;
    var $montoIva;
    var $baseImpIce;
    var $pic_Descripcion;
    var $porcentajeIce;
    var $pic_porcent;
    var $montoIce;
    var $montoIvaBienes;
    var $prb_Descripcion;
    var $porRetBienes;
    var $prb_Porcent;
    var $valorRetBienes;
    var $montoIvaServicios;
    var $prs_Descripcion;
    var $porRetServicios;
    var $prs_Porcent;
    var $valorRetServicios;
    var $estabRetencion1;
    var $puntoEmiRetencion1;
    var $secRetencion1;
    var $autRetencion1;
    var $fechaEmiRet1;
    var $cra_Descripcion;
    var $codRetAir;
    var $baseImpAir;
    var $porcentajeAir;
    var $valRetAir;
    var $cra_Descripcion2;
    var $codRetAir2;
    var $baseImpAir2;
    var $porcentajeAir2;
    var $valRetAir2;
    var $cra_Descripcion3;
    var $codRetAir3;
    var $baseImpAir3;
    var $porcentajeAir3;
    var $valRetAir3;
    var $baseImpAirTot;
    var $valRetAirTot;
    var $ccm_Descripcion;
    var $docModificado;
    var $fechaEmiModificado;
    var $estabModificado;
    var $ptoEmiModificado;
    var $secModificado;
    var $autModificado;
    var $contratoPartidoPolitico;
    var $montoTituloOneroso;
    var $montoTituloGratuito;
    var $numeroComprobantes;
    var $ivaPresuntivo;
    var $retPresuntiva;
    var $distAduanero;
    var $anio;
    var $regimen;
    var $correlativo;
    var $verificador;
    var $numCajBan;
    var $precCajBan;
    var $numCajBan2;
    var $precCajBan2;
    var $numCajBan3;
    var $precCajBan3;
    var $tce_Descripcion;
    var $facturaExportacion;
    var $tipImpExp;
    var $valorCifFob;
    var $valorFobComprobante;
    var $fechaEmbarque;
    var $documEmbarque;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-4BDFF98A
    function clsfiscomprasDataSource()
    {
        $this->ErrorBlock = "Record fiscompras/Error";
        $this->Initialize();
        $this->Label2 = new clsField("Label2", ccsText, "");
        $this->Label1 = new clsField("Label1", ccsText, "");
        $this->tra_Descripcion = new clsField("tra_Descripcion", ccsText, "");
        $this->tipoTransac = new clsField("tipoTransac", ccsText, "");
        $this->tra_CodCompr = new clsField("tra_CodCompr", ccsText, "");
        $this->tra_Secuencial = new clsField("tra_Secuencial", ccsText, "");
        $this->tra_IndProceso = new clsField("tra_IndProceso", ccsText, "");
        $this->devIva = new clsField("devIva", ccsText, "");
        $this->sus_Descripcion = new clsField("sus_Descripcion", ccsText, "");
        $this->codSustento = new clsField("codSustento", ccsText, "");
        $this->txt_ProvDescripcion = new clsField("txt_ProvDescripcion", ccsText, "");
        $this->tpIdProv = new clsField("tpIdProv", ccsText, "");
        $this->codProv = new clsField("codProv", ccsText, "");
        $this->txt_rucProv = new clsField("txt_rucProv", ccsText, "");
        $this->txt_ProvDescripcionFact = new clsField("txt_ProvDescripcionFact", ccsText, "");
        $this->tpIdProvFact = new clsField("tpIdProvFact", ccsText, "");
        $this->idProvFact = new clsField("idProvFact", ccsText, "");
        $this->txt_rucProvFact = new clsField("txt_rucProvFact", ccsText, "");
        $this->tco_Descripcion = new clsField("tco_Descripcion", ccsText, "");
        $this->tipoComprobante = new clsField("tipoComprobante", ccsText, "");
        $this->establecimiento = new clsField("establecimiento", ccsInteger, "");
        $this->puntoEmision = new clsField("puntoEmision", ccsInteger, "");
        $this->secuencial = new clsField("secuencial", ccsInteger, "");
        $this->fechaRegistro = new clsField("fechaRegistro", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->autorizacion = new clsField("autorizacion", ccsText, "");
        $this->tmp_FecEmision = new clsField("tmp_FecEmision", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->tmp_FecCaduc = new clsField("tmp_FecCaduc", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->aut_NroInicial = new clsField("aut_NroInicial", ccsInteger, "");
        $this->aut_NroFinal = new clsField("aut_NroFinal", ccsInteger, "");
        $this->tmp_IndicTransComp = new clsField("tmp_IndicTransComp", ccsText, "");
        $this->fechaEmision = new clsField("fechaEmision", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->tmp_Descripcion = new clsField("tmp_Descripcion", ccsText, "");
        $this->tco_Secuencial = new clsField("tco_Secuencial", ccsText, "");
        $this->tco_Sustento = new clsField("tco_Sustento", ccsText, "");
        $this->civ_FecInic = new clsField("civ_FecInic", ccsText, "");
        $this->civ_FecFin = new clsField("civ_FecFin", ccsText, "");
        $this->sus_CodCompr = new clsField("sus_CodCompr", ccsText, "");
        $this->cra_Porcent = new clsField("cra_Porcent", ccsText, "");
        $this->cra_FecIni = new clsField("cra_FecIni", ccsText, "");
        $this->cra_FecFin = new clsField("cra_FecFin", ccsText, "");
        $this->cra_Proceso = new clsField("cra_Proceso", ccsText, "");
        $this->ID = new clsField("ID", ccsText, "");
        $this->hdRegNumero = new clsField("hdRegNumero", ccsInteger, "");
        $this->hdTipoComp = new clsField("hdTipoComp", ccsText, "");
        $this->hdNumComp = new clsField("hdNumComp", ccsInteger, "");
        $this->baseImponible = new clsField("baseImponible", ccsFloat, "");
        $this->baseImpGrav = new clsField("baseImpGrav", ccsFloat, "");
        $this->baseNoGraIva = new clsField("baseNoGraIva", ccsFloat, "");
        $this->baseImpExe = new clsField("baseImpExe", ccsFloat, "");
        $this->civ_Descripcion = new clsField("civ_Descripcion", ccsText, "");
        $this->porcentajeIva = new clsField("porcentajeIva", ccsInteger, "");
        $this->civ_Porcent = new clsField("civ_Porcent", ccsFloat, "");
        $this->montoIva = new clsField("montoIva", ccsFloat, "");
        $this->baseImpIce = new clsField("baseImpIce", ccsFloat, "");
        $this->pic_Descripcion = new clsField("pic_Descripcion", ccsText, "");
        $this->porcentajeIce = new clsField("porcentajeIce", ccsInteger, "");
        $this->pic_porcent = new clsField("pic_porcent", ccsFloat, "");
        $this->montoIce = new clsField("montoIce", ccsFloat, "");
        $this->montoIvaBienes = new clsField("montoIvaBienes", ccsFloat, "");
        $this->prb_Descripcion = new clsField("prb_Descripcion", ccsText, "");
        $this->porRetBienes = new clsField("porRetBienes", ccsInteger, "");
        $this->prb_Porcent = new clsField("prb_Porcent", ccsFloat, "");
        $this->valorRetBienes = new clsField("valorRetBienes", ccsFloat, "");
        $this->montoIvaServicios = new clsField("montoIvaServicios", ccsFloat, "");
        $this->prs_Descripcion = new clsField("prs_Descripcion", ccsText, "");
        $this->porRetServicios = new clsField("porRetServicios", ccsInteger, "");
        $this->prs_Porcent = new clsField("prs_Porcent", ccsFloat, "");
        $this->valorRetServicios = new clsField("valorRetServicios", ccsFloat, "");
        $this->estabRetencion1 = new clsField("estabRetencion1", ccsInteger, "");
        $this->puntoEmiRetencion1 = new clsField("puntoEmiRetencion1", ccsInteger, "");
        $this->secRetencion1 = new clsField("secRetencion1", ccsInteger, "");
        $this->autRetencion1 = new clsField("autRetencion1", ccsText, "");
        $this->fechaEmiRet1 = new clsField("fechaEmiRet1", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->cra_Descripcion = new clsField("cra_Descripcion", ccsText, "");
        $this->codRetAir = new clsField("codRetAir", ccsInteger, "");
        $this->baseImpAir = new clsField("baseImpAir", ccsFloat, "");
        $this->porcentajeAir = new clsField("porcentajeAir", ccsFloat, "");
        $this->valRetAir = new clsField("valRetAir", ccsFloat, "");
        $this->cra_Descripcion2 = new clsField("cra_Descripcion2", ccsText, "");
        $this->codRetAir2 = new clsField("codRetAir2", ccsInteger, "");
        $this->baseImpAir2 = new clsField("baseImpAir2", ccsFloat, "");
        $this->porcentajeAir2 = new clsField("porcentajeAir2", ccsInteger, "");
        $this->valRetAir2 = new clsField("valRetAir2", ccsFloat, "");
        $this->cra_Descripcion3 = new clsField("cra_Descripcion3", ccsText, "");
        $this->codRetAir3 = new clsField("codRetAir3", ccsInteger, "");
        $this->baseImpAir3 = new clsField("baseImpAir3", ccsFloat, "");
        $this->porcentajeAir3 = new clsField("porcentajeAir3", ccsInteger, "");
        $this->valRetAir3 = new clsField("valRetAir3", ccsFloat, "");
        $this->baseImpAirTot = new clsField("baseImpAirTot", ccsFloat, "");
        $this->valRetAirTot = new clsField("valRetAirTot", ccsFloat, "");
        $this->ccm_Descripcion = new clsField("ccm_Descripcion", ccsText, "");
        $this->docModificado = new clsField("docModificado", ccsText, "");
        $this->fechaEmiModificado = new clsField("fechaEmiModificado", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->estabModificado = new clsField("estabModificado", ccsInteger, "");
        $this->ptoEmiModificado = new clsField("ptoEmiModificado", ccsInteger, "");
        $this->secModificado = new clsField("secModificado", ccsInteger, "");
        $this->autModificado = new clsField("autModificado", ccsText, "");
        $this->contratoPartidoPolitico = new clsField("contratoPartidoPolitico", ccsInteger, "");
        $this->montoTituloOneroso = new clsField("montoTituloOneroso", ccsFloat, "");
        $this->montoTituloGratuito = new clsField("montoTituloGratuito", ccsFloat, "");
        $this->numeroComprobantes = new clsField("numeroComprobantes", ccsInteger, "");
        $this->ivaPresuntivo = new clsField("ivaPresuntivo", ccsText, "");
        $this->retPresuntiva = new clsField("retPresuntiva", ccsText, "");
        $this->distAduanero = new clsField("distAduanero", ccsInteger, "");
        $this->anio = new clsField("anio", ccsText, "");
        $this->regimen = new clsField("regimen", ccsText, "");
        $this->correlativo = new clsField("correlativo", ccsText, "");
        $this->verificador = new clsField("verificador", ccsText, "");
        $this->numCajBan = new clsField("numCajBan", ccsFloat, "");
        $this->precCajBan = new clsField("precCajBan", ccsFloat, "");
        $this->numCajBan2 = new clsField("numCajBan2", ccsFloat, "");
        $this->precCajBan2 = new clsField("precCajBan2", ccsFloat, "");
        $this->numCajBan3 = new clsField("numCajBan3", ccsFloat, "");
        $this->precCajBan3 = new clsField("precCajBan3", ccsFloat, "");
        $this->tce_Descripcion = new clsField("tce_Descripcion", ccsText, "");
        $this->facturaExportacion = new clsField("facturaExportacion", ccsText, "");
        $this->tipImpExp = new clsField("tipImpExp", ccsText, "");
        $this->valorCifFob = new clsField("valorCifFob", ccsFloat, "");
        $this->valorFobComprobante = new clsField("valorFobComprobante", ccsFloat, "");
        $this->fechaEmbarque = new clsField("fechaEmbarque", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->documEmbarque = new clsField("documEmbarque", ccsText, "");

    }
//End DataSourceClass_Initialize Event

//Prepare Method @2-CBBE2C69
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlID", ccsFloat, "", "", $this->Parameters["urlID"], 0, false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
    }
//End Prepare Method

//Open Method @2-F6E0F5E7
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->SQL = "SELECT fco.*, " .
        "	tra.tab_Codigo AS tra_Codigo, concat(tra.tab_Codigo, '.- ',tra.tab_Descripcion) AS tra_Descripcion, tra.tab_TxtData AS tra_CodCompr, tra.tab_TxtData2 AS tra_Secuencial, " .
        "	tid.tab_Codigo AS tid_Codigo, concat(tid.tab_Codigo, '.- ',tid.tab_Descripcion) AS tid_Descripcion, " .
        "	tco.tab_Codigo AS tco_Codigo, concat(tco.tab_Codigo, '.- ',tco.tab_Descripcion) AS tco_Descripcion, tco.tab_TxtData AS tco_Secuencial, tco.tab_TxtData2 AS tco_Sustento, " .
        "	civ.tab_Codigo AS civ_codigo, concat(civ.tab_Codigo, '.- ',civ.tab_Descripcion) AS civ_Descripcion, civ.tab_Porcentaje AS civ_Porcent, civ.tab_FecInicio AS civ_FecInic, civ.tab_FecFinal AS civ_FecFin, " .
        "	sus.tab_Codigo AS sus_Codigo, concat(sus.tab_Codigo, '.- ',sus.tab_Descripcion) AS sus_Descripcion, sus.tab_TxtData AS sus_CodCompr, " .
        "	pic.tab_Codigo AS pic_Codigo, concat(pic.tab_Codigo, '.- ',pic.tab_Descripcion) AS pic_Descripcion, pic.tab_Porcentaje AS pic_Porcent, " .
        "	prb.tab_Codigo AS prb_Codigo, concat(prb.tab_Codigo, '.- ',prb.tab_Descripcion) AS prb_Descripcion, prb.tab_Porcentaje AS prb_Porcent, " .
        "	prs.tab_Codigo AS prs_Codigo, concat(prs.tab_Codigo, '.- ',prs.tab_Descripcion) AS prs_Descripcion, prs.tab_Porcentaje AS prs_Porcent, " .
        "	cra.tab_Codigo AS cra_Codigo, concat(cra.tab_Codigo, '.- ',cra.tab_Descripcion) AS cra_Descripcion, cra.tab_Porcentaje AS cra_Porcent, cra.tab_FecInicio AS cra_FecIni, " .
        "	cra.tab_FecFinal AS cra_FecFin, cra.tab_IndProceso AS cra_Proceso, " .
        "	cr2.tab_Codigo AS cr2_Codigo, concat(tra.tab_Codigo, '.- ',cr2.tab_Descripcion) AS cr2_Descripcion, cr2.tab_Porcentaje AS cr2_Porcent, " .
        "	cr3.tab_Codigo AS cr3_Codigo, concat(tra.tab_Codigo, '.- ',cr3.tab_Descripcion) AS cr3_Descripcion, cr3.tab_Porcentaje AS cr3_Porcent, " .
        "	ccm.tab_Codigo AS ccm_Codigo, concat(tra.tab_Codigo, '.- ',ccm.tab_Descripcion) AS ccm_Descripcion, " .
        "	UCASE(ltrim(concat(ifnull(pro.per_Ruc,'-----------------'), ' ', ifnull(pro.per_Apellidos,'-'), ' ',  ifnull(pro.per_Nombres,''), ' [',pro.per_codAuxiliar, ']'))) as 	txt_ProvDescripcion, " .
        "	ifnull(pro.per_Ruc,'') as txt_rucProv, " .
        "	ifnull(par.par_Valor2,'') as txt_tpIdProv, " .
        "        UCASE(ltrim(concat(ifnull(pv2.per_Ruc,'-----------------'), ' ', ifnull(pv2.per_Apellidos,'-'), ' ',  ifnull(pv2.per_Nombres,''), ' [',pv2.per_codAuxiliar, ']'))) as 	txt_ProvDescripcionFact, " .
        "	ifnull(pv2.per_Ruc,'') as txt_rucProvFact, " .
        "	ifnull(pm2.par_Valor2,'') as txt_tpIdProvFac, " .
        "        aut_FecEmision as txt_FecEmision,  " .
        "        aut_FecVigencia as txt_FecVigencia, " .
        "        aut_NroInicial, " .
        "        aut_Nrofinal, " .
        "        concat(tce.tab_Codigo, '.- ',tce.tab_Descripcion) AS tce_Descripcion, " .
        "        com_TipoComp, com_NumComp, com_RegNumero " .
        "FROM fiscompras fco " .
        "	LEFT JOIN fistablassri sus ON sus.tab_CodTabla = \"3\"  AND fco.codSustento +0  = sus.tab_Codigo +0  " .
        "	LEFT JOIN fistablassri tco ON tco.tab_CodTabla = \"2\"  AND fco.tipoComprobante +0 = tco.tab_Codigo " .
        "	LEFT JOIN fistablassri tce ON tce.tab_CodTabla = \"2\"  AND fco.facturaExportacion +0 = tce.tab_Codigo " .
        "	LEFT JOIN fistablassri civ ON civ.tab_CodTabla = \"4\"  AND fco.porcentajeIva = civ.tab_Codigo " .
        "	LEFT JOIN fistablassri pic ON pic.tab_CodTabla = \"6\"  AND fco.porcentajeIce = pic.tab_Codigo " .
        "	LEFT JOIN fistablassri prb ON prb.tab_CodTabla = \"5a\" AND fco.porRetBienes = prb.tab_Codigo " .
        "	LEFT JOIN fistablassri prs ON prs.tab_CodTabla = \"5\"  AND fco.porRetServicios = prs.tab_Codigo " .
        "	LEFT JOIN fistablassri cra ON cra.tab_CodTabla = \"10\" AND fco.codRetAir = cra.tab_Codigo " .
        "        LEFT JOIN fistablassri cr2 ON cr2.tab_CodTabla = \"10\" AND fco.codRetAir2 = cr2.tab_Codigo " .
        "        LEFT JOIN fistablassri cr3 ON cr3.tab_CodTabla = \"10\" AND fco.codRetAir3 = cr3.tab_Codigo " .
        "	LEFT JOIN fistablassri ccm ON civ.tab_CodTabla = \"2\"  AND fco.docModificado = ccm.tab_Codigo " .
        "	LEFT JOIN fistablassri tra ON tra.tab_CodTabla = \"A\"  AND fco.tipoTransac = tra.tab_Codigo " .
        "	LEFT JOIN conpersonas  pro ON pro.per_CodAuxiliar = fco.codProv " .
        "	LEFT JOIN genparametros par ON par.par_clave= \"TIPID\" AND par.par_secuencia = pro.per_tipoID " .
        "	LEFT JOIN conpersonas  pv2 ON pv2.per_CodAuxiliar = fco.idProvFact " .
        "	LEFT JOIN genparametros pm2 ON pm2.par_clave= \"TIPID\" AND pm2.par_secuencia = pv2.per_tipoID " .
        "	LEFT JOIN fistablassri tid ON tid.tab_CodTabla = \"8\"  AND par.par_Valor2 = tid.tab_Codigo " .
        "        LEFT JOIN genautsri  ON aut_ID = autorizacion " .
        "        LEFT JOIN  concomprobantes ON com_tipocomp = tra.tab_indproceso AND com_numretenc = fco.ID " .
        "WHERE ID = " . $this->SQLValue($this->wp->GetDBValue("1"), ccsFloat) . "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->PageSize = 1;
        $this->query($this->OptimizeSQL(CCBuildSQL($this->SQL, $this->Where, $this->Order)));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
    }
//End Open Method

//SetValues Method @2-5E927E3D
    function SetValues()
    {
        $this->Label1->SetDBValue($this->f("ID"));
        $this->tra_Descripcion->SetDBValue($this->f("tra_Descripcion"));
        $this->tipoTransac->SetDBValue($this->f("tipoTransac"));
        $this->tra_CodCompr->SetDBValue($this->f("tra_CodCompr"));
        $this->tra_Secuencial->SetDBValue($this->f("tra_Secuencial"));
        $this->tra_IndProceso->SetDBValue($this->f("tra_IndProceso"));
        $this->devIva->SetDBValue($this->f("devIva"));
        $this->sus_Descripcion->SetDBValue($this->f("sus_Descripcion"));
        $this->codSustento->SetDBValue($this->f("codSustento"));
        $this->txt_ProvDescripcion->SetDBValue($this->f("txt_ProvDescripcion"));
        $this->tpIdProv->SetDBValue($this->f("txt_tpIdProv"));
        $this->codProv->SetDBValue($this->f("codProv"));
        $this->txt_rucProv->SetDBValue($this->f("txt_rucProv"));
        $this->txt_ProvDescripcionFact->SetDBValue($this->f("txt_ProvDescripcionFact"));
        $this->tpIdProvFact->SetDBValue($this->f("tpIdProvFact"));
        $this->idProvFact->SetDBValue($this->f("idProvFact"));
        $this->txt_rucProvFact->SetDBValue($this->f("txt_rucProvFact"));
        $this->tco_Descripcion->SetDBValue($this->f("tco_Descripcion"));
        $this->tipoComprobante->SetDBValue($this->f("tipoComprobante"));
        $this->establecimiento->SetDBValue(trim($this->f("establecimiento")));
        $this->puntoEmision->SetDBValue(trim($this->f("puntoEmision")));
        $this->secuencial->SetDBValue(trim($this->f("secuencial")));
        $this->fechaRegistro->SetDBValue(trim($this->f("fechaRegistro")));
        $this->autorizacion->SetDBValue(trim($this->f("autorizacion")));
        $this->tmp_FecEmision->SetDBValue(trim($this->f("txt_FecEmision")));
        $this->tmp_FecCaduc->SetDBValue(trim($this->f("txt_FecVigencia")));
        $this->aut_NroInicial->SetDBValue(trim($this->f("aut_NroInicial")));
        $this->aut_NroFinal->SetDBValue(trim($this->f("aut_NroFinal")));
        $this->fechaEmision->SetDBValue(trim($this->f("fechaEmision")));
        $this->tco_Secuencial->SetDBValue($this->f("tco_Secuencial"));
        $this->tco_Sustento->SetDBValue($this->f("tco_Sustento"));
        $this->civ_FecInic->SetDBValue($this->f("civ_FecInic"));
        $this->civ_FecFin->SetDBValue($this->f("civ_FecFin"));
        $this->sus_CodCompr->SetDBValue($this->f("sus_CodCompr"));
        $this->cra_Porcent->SetDBValue($this->f("cra_Porcent"));
        $this->cra_FecIni->SetDBValue($this->f("cra_FecIni"));
        $this->cra_FecFin->SetDBValue($this->f("cra_FecFin"));
        $this->cra_Proceso->SetDBValue($this->f("cra_Proceso"));
        $this->ID->SetDBValue($this->f("ID"));
        $this->hdRegNumero->SetDBValue(trim($this->f("com_RegNumero")));
        $this->hdTipoComp->SetDBValue($this->f("com_TipoComp"));
        $this->hdNumComp->SetDBValue(trim($this->f("com_NumComp")));
        $this->baseImponible->SetDBValue(trim($this->f("baseImponible")));
        $this->baseImpGrav->SetDBValue(trim($this->f("baseImpGrav")));
        $this->baseNoGraIva->SetDBValue(trim($this->f("baseNoGraIva")));
        $this->baseImpExe->SetDBValue(trim($this->f("baseImpExe")));
        $this->civ_Descripcion->SetDBValue($this->f("civ_Descripcion"));
        $this->porcentajeIva->SetDBValue(trim($this->f("porcentajeIva")));
        $this->civ_Porcent->SetDBValue(trim($this->f("civ_Porcent")));
        $this->montoIva->SetDBValue(trim($this->f("montoIva")));
        $this->baseImpIce->SetDBValue(trim($this->f("baseImpIce")));
        $this->pic_Descripcion->SetDBValue($this->f("pic_Descripcion"));
        $this->porcentajeIce->SetDBValue(trim($this->f("porcentajeIce")));
        $this->pic_porcent->SetDBValue(trim($this->f("pic_Porcent")));
        $this->montoIce->SetDBValue(trim($this->f("montoIce")));
        $this->montoIvaBienes->SetDBValue(trim($this->f("montoIvaBienes")));
        $this->prb_Descripcion->SetDBValue($this->f("prb_Descripcion"));
        $this->porRetBienes->SetDBValue(trim($this->f("porRetBienes")));
        $this->prb_Porcent->SetDBValue(trim($this->f("prb_Porcent")));
        $this->valorRetBienes->SetDBValue(trim($this->f("valorRetBienes")));
        $this->montoIvaServicios->SetDBValue(trim($this->f("montoIvaServicios")));
        $this->prs_Descripcion->SetDBValue($this->f("prs_Descripcion"));
        $this->porRetServicios->SetDBValue(trim($this->f("porRetServicios")));
        $this->prs_Porcent->SetDBValue(trim($this->f("prs_Porcent")));
        $this->valorRetServicios->SetDBValue(trim($this->f("valorRetServicios")));
        $this->estabRetencion1->SetDBValue(trim($this->f("estabRetencion1")));
        $this->puntoEmiRetencion1->SetDBValue(trim($this->f("puntoEmiRetencion1")));
        $this->secRetencion1->SetDBValue(trim($this->f("secRetencion1")));
        $this->autRetencion1->SetDBValue(trim($this->f("autRetencion1")));
        $this->fechaEmiRet1->SetDBValue(trim($this->f("fechaEmiRet1")));
        $this->cra_Descripcion->SetDBValue($this->f("cra_Descripcion"));
        $this->codRetAir->SetDBValue(trim($this->f("codRetAir")));
        $this->baseImpAir->SetDBValue(trim($this->f("baseImpAir")));
        $this->porcentajeAir->SetDBValue(trim($this->f("porcentajeAir")));
        $this->valRetAir->SetDBValue(trim($this->f("valRetAir")));
        $this->cra_Descripcion2->SetDBValue($this->f("cr2_Descripcion"));
        $this->codRetAir2->SetDBValue(trim($this->f("codRetAir2")));
        $this->baseImpAir2->SetDBValue(trim($this->f("baseImpAir2")));
        $this->porcentajeAir2->SetDBValue(trim($this->f("porcentajeAir2")));
        $this->valRetAir2->SetDBValue(trim($this->f("valRetAir2")));
        $this->cra_Descripcion3->SetDBValue($this->f("cr3_Descripcion"));
        $this->codRetAir3->SetDBValue(trim($this->f("codRetAir3")));
        $this->baseImpAir3->SetDBValue(trim($this->f("baseImpAir3")));
        $this->porcentajeAir3->SetDBValue(trim($this->f("porcentajeAir3")));
        $this->valRetAir3->SetDBValue(trim($this->f("valRetAir3")));
        $this->baseImpAirTot->SetDBValue(trim($this->f("baseImpAirTot")));
        $this->valRetAirTot->SetDBValue(trim($this->f("valRetAirTot")));
        $this->ccm_Descripcion->SetDBValue($this->f("ccm_Descripcion"));
        $this->docModificado->SetDBValue($this->f("docModificado"));
        $this->fechaEmiModificado->SetDBValue(trim($this->f("fechaEmiModificado")));
        $this->estabModificado->SetDBValue(trim($this->f("estabModificado")));
        $this->ptoEmiModificado->SetDBValue(trim($this->f("ptoEmiModificado")));
        $this->secModificado->SetDBValue(trim($this->f("secModificado")));
        $this->autModificado->SetDBValue(trim($this->f("autModificado")));
        $this->contratoPartidoPolitico->SetDBValue(trim($this->f("contratoPartidoPolitico")));
        $this->montoTituloOneroso->SetDBValue(trim($this->f("montoTituloOneroso")));
        $this->montoTituloGratuito->SetDBValue(trim($this->f("montoTituloGratuito")));
        $this->numeroComprobantes->SetDBValue(trim($this->f("numeroComprobantes")));
        $this->ivaPresuntivo->SetDBValue($this->f("ivaPresuntivo"));
        $this->retPresuntiva->SetDBValue($this->f("retPresuntiva"));
        $this->distAduanero->SetDBValue(trim($this->f("distAduanero")));
        $this->anio->SetDBValue($this->f("anio"));
        $this->regimen->SetDBValue($this->f("regimen"));
        $this->correlativo->SetDBValue($this->f("correlativo"));
        $this->verificador->SetDBValue($this->f("verificador"));
        $this->numCajBan->SetDBValue(trim($this->f("numCajBan")));
        $this->precCajBan->SetDBValue(trim($this->f("precCajBan")));
        $this->numCajBan2->SetDBValue(trim($this->f("numCajBan2")));
        $this->precCajBan2->SetDBValue(trim($this->f("precCajBan2")));
        $this->numCajBan3->SetDBValue(trim($this->f("numCajBan3")));
        $this->precCajBan3->SetDBValue(trim($this->f("precCajBan3")));
        $this->tce_Descripcion->SetDBValue($this->f("tce_Descripcion"));
        $this->facturaExportacion->SetDBValue($this->f("facturaExportacion"));
        $this->tipImpExp->SetDBValue($this->f("tipImpExp"));
        $this->valorCifFob->SetDBValue(trim($this->f("valorCifFob")));
        $this->valorFobComprobante->SetDBValue(trim($this->f("valorFobComprobante")));
        $this->fechaEmbarque->SetDBValue(trim($this->f("fechaEmbarque")));
        $this->documEmbarque->SetDBValue($this->f("documEmbarque"));
    }
//End SetValues Method

//Insert Method @2-3D79D516
    function Insert()
    {
        $this->CmdExecution = true;
        $this->cp["tipoTransac"] = new clsSQLParameter("ctrltipoTransac", ccsText, "", "", $this->tipoTransac->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["codSustento"] = new clsSQLParameter("ctrlcodSustento", ccsText, "", "", $this->codSustento->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["devIva"] = new clsSQLParameter("ctrldevIva", ccsText, "", "", $this->devIva->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tipoComprobante"] = new clsSQLParameter("ctrltipoComprobante", ccsText, "", "", $this->tipoComprobante->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["establecimiento"] = new clsSQLParameter("ctrlestablecimiento", ccsInteger, "", "", $this->establecimiento->GetValue(), 001, false, $this->ErrorBlock);
        $this->cp["puntoEmision"] = new clsSQLParameter("ctrlpuntoEmision", ccsInteger, "", "", $this->puntoEmision->GetValue(), 001, false, $this->ErrorBlock);
        $this->cp["secuencial"] = new clsSQLParameter("ctrlsecuencial", ccsInteger, Array(True, 0, "", "", False, Array("0", "0", "0", "0", "0", "0", "0"), "", 1, True, ""), "", $this->secuencial->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["fechaRegistro"] = new clsSQLParameter("ctrlfechaRegistro", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->fechaRegistro->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["autorizacion"] = new clsSQLParameter("ctrlautorizacion", ccsText, "", "", $this->autorizacion->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["baseImponible"] = new clsSQLParameter("ctrlbaseImponible", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""), "", $this->baseImponible->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["montoIvaBienes"] = new clsSQLParameter("ctrlmontoIvaBienes", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""), "", $this->montoIvaBienes->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["porRetBienes"] = new clsSQLParameter("ctrlporRetBienes", ccsInteger, "", "", $this->porRetBienes->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["valorRetBienes"] = new clsSQLParameter("ctrlvalorRetBienes", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""), "", $this->valorRetBienes->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["baseImpGrav"] = new clsSQLParameter("ctrlbaseImpGrav", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""), "", $this->baseImpGrav->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["porcentajeIva"] = new clsSQLParameter("ctrlporcentajeIva", ccsInteger, Array(False, 2, ".", ",", False, "", "", 1, True, ""), "", $this->porcentajeIva->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["montoIva"] = new clsSQLParameter("ctrlmontoIva", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""), "", $this->montoIva->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["montoIvaServicios"] = new clsSQLParameter("ctrlmontoIvaServicios", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""), "", $this->montoIvaServicios->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["porRetServicios"] = new clsSQLParameter("ctrlporRetServicios", ccsInteger, "", "", $this->porRetServicios->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["valorRetServicios"] = new clsSQLParameter("ctrlvalorRetServicios", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""), "", $this->valorRetServicios->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["baseNoGraIva"] = new clsSQLParameter("ctrlbaseNoGraIva", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""), "", $this->baseNoGraIva->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["baseImpExe"] = new clsSQLParameter("ctrlbaseImpExe", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""), "", $this->baseImpExe->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["baseImpIce"] = new clsSQLParameter("ctrlbaseImpIce", ccsText, "", "", $this->baseImpIce->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["porcentajeIce"] = new clsSQLParameter("ctrlporcentajeIce", ccsInteger, Array(False, 2, ".", ",", False, "", "", 1, True, ""), "", $this->porcentajeIce->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["montoIce"] = new clsSQLParameter("ctrlmontoIce", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""), "", $this->montoIce->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["baseImpAir"] = new clsSQLParameter("ctrlbaseImpAir", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""), "", $this->baseImpAir->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["codRetAir"] = new clsSQLParameter("ctrlcodRetAir", ccsInteger, "", "", $this->codRetAir->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["porcentajeAir"] = new clsSQLParameter("ctrlporcentajeAir", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), "", $this->porcentajeAir->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["valRetAir"] = new clsSQLParameter("ctrlvalRetAir", ccsMemo, "", "", $this->valRetAir->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["estabRetencion1"] = new clsSQLParameter("ctrlestabRetencion1", ccsInteger, "", "", $this->estabRetencion1->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["puntoEmiRetencion1"] = new clsSQLParameter("ctrlpuntoEmiRetencion1", ccsInteger, "", "", $this->puntoEmiRetencion1->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["secRetencion1"] = new clsSQLParameter("ctrlsecRetencion1", ccsInteger, "", "", $this->secRetencion1->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["autRetencion1"] = new clsSQLParameter("ctrlautRetencion1", ccsText, "", "", $this->autRetencion1->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["docModificado"] = new clsSQLParameter("ctrldocModificado", ccsText, "", "", $this->docModificado->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["fechaEmiModificado"] = new clsSQLParameter("ctrlfechaEmiModificado", ccsDate, Array("dd", "/", "mmm", "/", "yyyy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->fechaEmiModificado->GetValue(), 0000-00-00, false, $this->ErrorBlock);
        $this->cp["estabModificado"] = new clsSQLParameter("ctrlestabModificado", ccsInteger, "", "", $this->estabModificado->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["ptoEmiModificado"] = new clsSQLParameter("ctrlptoEmiModificado", ccsInteger, "", "", $this->ptoEmiModificado->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["secModificado"] = new clsSQLParameter("ctrlsecModificado", ccsInteger, "", "", $this->secModificado->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["autModificado"] = new clsSQLParameter("ctrlautModificado", ccsText, "", "", $this->autModificado->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["contratoPartidoPolitico"] = new clsSQLParameter("ctrlcontratoPartidoPolitico", ccsInteger, "", "", $this->contratoPartidoPolitico->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["montoTituloOneroso"] = new clsSQLParameter("ctrlmontoTituloOneroso", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""), "", $this->montoTituloOneroso->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["montoTituloGratuito"] = new clsSQLParameter("ctrlmontoTituloGratuito", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""), "", $this->montoTituloGratuito->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["numeroComprobantes"] = new clsSQLParameter("ctrlnumeroComprobantes", ccsInteger, "", "", $this->numeroComprobantes->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["ivaPresuntivo"] = new clsSQLParameter("ctrlivaPresuntivo", ccsText, "", "", $this->ivaPresuntivo->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["retPresuntiva"] = new clsSQLParameter("ctrlretPresuntiva", ccsText, "", "", $this->retPresuntiva->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["codProv"] = new clsSQLParameter("ctrlcodProv", ccsInteger, "", "", $this->codProv->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["tpIdProvFact"] = new clsSQLParameter("ctrltpIdProvFact", ccsText, "", "", $this->tpIdProvFact->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["idProvFact"] = new clsSQLParameter("ctrlidProvFact", ccsInteger, "", "", $this->idProvFact->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["codRetAir2"] = new clsSQLParameter("ctrlcodRetAir2", ccsInteger, "", "", $this->codRetAir2->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["baseImpAir2"] = new clsSQLParameter("ctrlbaseImpAir2", ccsMemo, "", "", $this->baseImpAir2->GetValue(), 0.00, false, $this->ErrorBlock);
        $this->cp["porcentajeAir2"] = new clsSQLParameter("ctrlporcentajeAir2", ccsInteger, "", "", $this->porcentajeAir2->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["valRetAir2"] = new clsSQLParameter("ctrlvalRetAir2", ccsMemo, "", "", $this->valRetAir2->GetValue(), 0.00, false, $this->ErrorBlock);
        $this->cp["codRetAir3"] = new clsSQLParameter("ctrlcodRetAir3", ccsInteger, "", "", $this->codRetAir3->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["baseImpAir3"] = new clsSQLParameter("ctrlbaseImpAir3", ccsMemo, "", "", $this->baseImpAir3->GetValue(), 0.00, false, $this->ErrorBlock);
        $this->cp["valRetAir3"] = new clsSQLParameter("ctrlvalRetAir3", ccsMemo, "", "", $this->valRetAir3->GetValue(), 0.00, false, $this->ErrorBlock);
        $this->cp["porcentajeAir3"] = new clsSQLParameter("ctrlporcentajeAir3", ccsInteger, "", "", $this->porcentajeAir3->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["distAduanero"] = new clsSQLParameter("ctrldistAduanero", ccsText, "", "", $this->distAduanero->GetValue(), 000, false, $this->ErrorBlock);
        $this->cp["anio"] = new clsSQLParameter("ctrlanio", ccsText, "", "", $this->anio->GetValue(), 0000, false, $this->ErrorBlock);
        $this->cp["regimen"] = new clsSQLParameter("ctrlregimen", ccsText, "", "", $this->regimen->GetValue(), 00, false, $this->ErrorBlock);
        $this->cp["correlativo"] = new clsSQLParameter("ctrlcorrelativo", ccsText, "", "", $this->correlativo->GetValue(), 000000, false, $this->ErrorBlock);
        $this->cp["verificador"] = new clsSQLParameter("ctrlverificador", ccsText, "", "", $this->verificador->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["numCajBan"] = new clsSQLParameter("ctrlnumCajBan", ccsMemo, "", "", $this->numCajBan->GetValue(), 0.00, false, $this->ErrorBlock);
        $this->cp["precCajBan"] = new clsSQLParameter("ctrlprecCajBan", ccsMemo, "", "", $this->precCajBan->GetValue(), 0.0000, false, $this->ErrorBlock);
        $this->cp["numCajBan2"] = new clsSQLParameter("ctrlnumCajBan2", ccsMemo, "", "", $this->numCajBan2->GetValue(), 0.00, false, $this->ErrorBlock);
        $this->cp["precCajBan2"] = new clsSQLParameter("ctrlprecCajBan2", ccsMemo, "", "", $this->precCajBan2->GetValue(), 0.0000, false, $this->ErrorBlock);
        $this->cp["numCajBan3"] = new clsSQLParameter("ctrlnumCajBan3", ccsMemo, "", "", $this->numCajBan3->GetValue(), 0.00, false, $this->ErrorBlock);
        $this->cp["precCajBan3"] = new clsSQLParameter("ctrlprecCajBan3", ccsMemo, "", "", $this->precCajBan3->GetValue(), 0.0000, false, $this->ErrorBlock);
        $this->cp["facturaExportacion"] = new clsSQLParameter("ctrlfacturaExportacion", ccsText, "", "", $this->facturaExportacion->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["tipImpExp"] = new clsSQLParameter("ctrltipImpExp", ccsText, "", "", $this->tipImpExp->GetValue(), B, false, $this->ErrorBlock);
        $this->cp["valorCifFob"] = new clsSQLParameter("ctrlvalorCifFob", ccsMemo, "", "", $this->valorCifFob->GetValue(), 0.00, false, $this->ErrorBlock);
        $this->cp["valorFobComprobante"] = new clsSQLParameter("ctrlvalorFobComprobante", ccsMemo, "", "", $this->valorFobComprobante->GetValue(), 0.00, false, $this->ErrorBlock);
        $this->cp["fechaEmision"] = new clsSQLParameter("ctrlfechaEmision", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->fechaEmision->GetValue(), 0000-00-00, false, $this->ErrorBlock);
        $this->cp["fechaEmbarque"] = new clsSQLParameter("ctrlfechaEmbarque", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->fechaEmbarque->GetValue(), 0000-00-00, false, $this->ErrorBlock);
        $this->cp["documEmbarque"] = new clsSQLParameter("ctrldocumEmbarque", ccsText, "", "", $this->documEmbarque->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["fechaEmiRet1"] = new clsSQLParameter("ctrlfechaEmiRet1", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->fechaEmiRet1->GetValue(), 0000-00-00, false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO fiscompras ("
             . "tipoTransac, "
             . "codSustento, "
             . "devIva, "
             . "tipoComprobante, "
             . "establecimiento, "
             . "puntoEmision, "
             . "secuencial, "
             . "fechaRegistro, "
             . "autorizacion, "
             . "baseImponible, "
             . "montoIvaBienes, "
             . "porRetBienes, "
             . "valorRetBienes, "
             . "baseImpGrav, "
             . "porcentajeIva, "
             . "montoIva, "
             . "montoIvaServicios, "
             . "porRetServicios, "
             . "valorRetServicios, "
             . "baseNoGraIva, "
             . "baseImpExe, "
             . "baseImpIce, "
             . "porcentajeIce, "
             . "montoIce, "
             . "baseImpAir, "
             . "codRetAir, "
             . "porcentajeAir, "
             . "valRetAir, "
             . "estabRetencion1, "
             . "puntoEmiRetencion1, "
             . "secRetencion1, "
             . "autRetencion1, "
             . "docModificado, "
             . "fechaEmiModificado, "
             . "estabModificado, "
             . "ptoEmiModificado, "
             . "secModificado, "
             . "autModificado, "
             . "contratoPartidoPolitico, "
             . "montoTituloOneroso, "
             . "montoTituloGratuito, "
             . "numeroComprobantes, "
             . "ivaPresuntivo, "
             . "retPresuntiva, "
             . "codProv, "
             . "tpIdProvFact, "
             . "idProvFact, "
             . "codRetAir2, "
             . "baseImpAir2, "
             . "porcentajeAir2, "
             . "valRetAir2, "
             . "codRetAir3, "
             . "baseImpAir3, "
             . "valRetAir3, "
             . "porcentajeAir3, "
             . "distAduanero, "
             . "anio, "
             . "regimen, "
             . "correlativo, "
             . "verificador, "
             . "facturaExportacion, "
             . "tipImpExp, "
             . "valorCifFob, "
             . "valorFobComprobante, "
             . "fechaEmision, "
             . "fechaEmbarque, "
             . "documEmbarque, "
             . "fechaEmiRet1, "
             . "numCajBan, "
             . "precCajBan, "
             . "numCajBan2, "
             . "precCajBan2, "
             . "numCajBan3, "
             . "precCajBan3"
             . ") VALUES ("
             . $this->ToSQL($this->cp["tipoTransac"]->GetDBValue(), $this->cp["tipoTransac"]->DataType) . ", "
             . $this->ToSQL($this->cp["codSustento"]->GetDBValue(), $this->cp["codSustento"]->DataType) . ", "
             . $this->ToSQL($this->cp["devIva"]->GetDBValue(), $this->cp["devIva"]->DataType) . ", "
             . $this->ToSQL($this->cp["tipoComprobante"]->GetDBValue(), $this->cp["tipoComprobante"]->DataType) . ", "
             . $this->ToSQL($this->cp["establecimiento"]->GetDBValue(), $this->cp["establecimiento"]->DataType) . ", "
             . $this->ToSQL($this->cp["puntoEmision"]->GetDBValue(), $this->cp["puntoEmision"]->DataType) . ", "
             . $this->ToSQL($this->cp["secuencial"]->GetDBValue(), $this->cp["secuencial"]->DataType) . ", "
             . $this->ToSQL($this->cp["fechaRegistro"]->GetDBValue(), $this->cp["fechaRegistro"]->DataType) . ", "
             . $this->ToSQL($this->cp["autorizacion"]->GetDBValue(), $this->cp["autorizacion"]->DataType) . ", "
             . $this->ToSQL($this->cp["baseImponible"]->GetDBValue(), $this->cp["baseImponible"]->DataType) . ", "
             . $this->ToSQL($this->cp["montoIvaBienes"]->GetDBValue(), $this->cp["montoIvaBienes"]->DataType) . ", "
             . $this->ToSQL($this->cp["porRetBienes"]->GetDBValue(), $this->cp["porRetBienes"]->DataType) . ", "
             . $this->ToSQL($this->cp["valorRetBienes"]->GetDBValue(), $this->cp["valorRetBienes"]->DataType) . ", "
             . $this->ToSQL($this->cp["baseImpGrav"]->GetDBValue(), $this->cp["baseImpGrav"]->DataType) . ", "
             . $this->ToSQL($this->cp["porcentajeIva"]->GetDBValue(), $this->cp["porcentajeIva"]->DataType) . ", "
             . $this->ToSQL($this->cp["montoIva"]->GetDBValue(), $this->cp["montoIva"]->DataType) . ", "
             . $this->ToSQL($this->cp["montoIvaServicios"]->GetDBValue(), $this->cp["montoIvaServicios"]->DataType) . ", "
             . $this->ToSQL($this->cp["porRetServicios"]->GetDBValue(), $this->cp["porRetServicios"]->DataType) . ", "
             . $this->ToSQL($this->cp["valorRetServicios"]->GetDBValue(), $this->cp["valorRetServicios"]->DataType) . ", "
             . $this->ToSQL($this->cp["baseNoGraIva"]->GetDBValue(), $this->cp["baseNoGraIva"]->DataType) . ", "
             . $this->ToSQL($this->cp["baseImpExe"]->GetDBValue(), $this->cp["baseImpExe"]->DataType) . ", "
             . $this->ToSQL($this->cp["baseImpIce"]->GetDBValue(), $this->cp["baseImpIce"]->DataType) . ", "
             . $this->ToSQL($this->cp["porcentajeIce"]->GetDBValue(), $this->cp["porcentajeIce"]->DataType) . ", "
             . $this->ToSQL($this->cp["montoIce"]->GetDBValue(), $this->cp["montoIce"]->DataType) . ", "
             . $this->ToSQL($this->cp["baseImpAir"]->GetDBValue(), $this->cp["baseImpAir"]->DataType) . ", "
             . $this->ToSQL($this->cp["codRetAir"]->GetDBValue(), $this->cp["codRetAir"]->DataType) . ", "
             . $this->ToSQL($this->cp["porcentajeAir"]->GetDBValue(), $this->cp["porcentajeAir"]->DataType) . ", "
             . $this->ToSQL($this->cp["valRetAir"]->GetDBValue(), $this->cp["valRetAir"]->DataType) . ", "
             . $this->ToSQL($this->cp["estabRetencion1"]->GetDBValue(), $this->cp["estabRetencion1"]->DataType) . ", "
             . $this->ToSQL($this->cp["puntoEmiRetencion1"]->GetDBValue(), $this->cp["puntoEmiRetencion1"]->DataType) . ", "
             . $this->ToSQL($this->cp["secRetencion1"]->GetDBValue(), $this->cp["secRetencion1"]->DataType) . ", "
             . $this->ToSQL($this->cp["autRetencion1"]->GetDBValue(), $this->cp["autRetencion1"]->DataType) . ", "
             . $this->ToSQL($this->cp["docModificado"]->GetDBValue(), $this->cp["docModificado"]->DataType) . ", "
             . $this->ToSQL($this->cp["fechaEmiModificado"]->GetDBValue(), $this->cp["fechaEmiModificado"]->DataType) . ", "
             . $this->ToSQL($this->cp["estabModificado"]->GetDBValue(), $this->cp["estabModificado"]->DataType) . ", "
             . $this->ToSQL($this->cp["ptoEmiModificado"]->GetDBValue(), $this->cp["ptoEmiModificado"]->DataType) . ", "
             . $this->ToSQL($this->cp["secModificado"]->GetDBValue(), $this->cp["secModificado"]->DataType) . ", "
             . $this->ToSQL($this->cp["autModificado"]->GetDBValue(), $this->cp["autModificado"]->DataType) . ", "
             . $this->ToSQL($this->cp["contratoPartidoPolitico"]->GetDBValue(), $this->cp["contratoPartidoPolitico"]->DataType) . ", "
             . $this->ToSQL($this->cp["montoTituloOneroso"]->GetDBValue(), $this->cp["montoTituloOneroso"]->DataType) . ", "
             . $this->ToSQL($this->cp["montoTituloGratuito"]->GetDBValue(), $this->cp["montoTituloGratuito"]->DataType) . ", "
             . $this->ToSQL($this->cp["numeroComprobantes"]->GetDBValue(), $this->cp["numeroComprobantes"]->DataType) . ", "
             . $this->ToSQL($this->cp["ivaPresuntivo"]->GetDBValue(), $this->cp["ivaPresuntivo"]->DataType) . ", "
             . $this->ToSQL($this->cp["retPresuntiva"]->GetDBValue(), $this->cp["retPresuntiva"]->DataType) . ", "
             . $this->ToSQL($this->cp["codProv"]->GetDBValue(), $this->cp["codProv"]->DataType) . ", "
             . $this->ToSQL($this->cp["tpIdProvFact"]->GetDBValue(), $this->cp["tpIdProvFact"]->DataType) . ", "
             . $this->ToSQL($this->cp["idProvFact"]->GetDBValue(), $this->cp["idProvFact"]->DataType) . ", "
             . $this->ToSQL($this->cp["codRetAir2"]->GetDBValue(), $this->cp["codRetAir2"]->DataType) . ", "
             . $this->ToSQL($this->cp["baseImpAir2"]->GetDBValue(), $this->cp["baseImpAir2"]->DataType) . ", "
             . $this->ToSQL($this->cp["porcentajeAir2"]->GetDBValue(), $this->cp["porcentajeAir2"]->DataType) . ", "
             . $this->ToSQL($this->cp["valRetAir2"]->GetDBValue(), $this->cp["valRetAir2"]->DataType) . ", "
             . $this->ToSQL($this->cp["codRetAir3"]->GetDBValue(), $this->cp["codRetAir3"]->DataType) . ", "
             . $this->ToSQL($this->cp["baseImpAir3"]->GetDBValue(), $this->cp["baseImpAir3"]->DataType) . ", "
             . $this->ToSQL($this->cp["valRetAir3"]->GetDBValue(), $this->cp["valRetAir3"]->DataType) . ", "
             . $this->ToSQL($this->cp["porcentajeAir3"]->GetDBValue(), $this->cp["porcentajeAir3"]->DataType) . ", "
             . $this->ToSQL($this->cp["distAduanero"]->GetDBValue(), $this->cp["distAduanero"]->DataType) . ", "
             . $this->ToSQL($this->cp["anio"]->GetDBValue(), $this->cp["anio"]->DataType) . ", "
             . $this->ToSQL($this->cp["regimen"]->GetDBValue(), $this->cp["regimen"]->DataType) . ", "
             . $this->ToSQL($this->cp["correlativo"]->GetDBValue(), $this->cp["correlativo"]->DataType) . ", "
             . $this->ToSQL($this->cp["verificador"]->GetDBValue(), $this->cp["verificador"]->DataType) . ", "
             . $this->ToSQL($this->cp["facturaExportacion"]->GetDBValue(), $this->cp["facturaExportacion"]->DataType) . ", "
             . $this->ToSQL($this->cp["tipImpExp"]->GetDBValue(), $this->cp["tipImpExp"]->DataType) . ", "
             . $this->ToSQL($this->cp["valorCifFob"]->GetDBValue(), $this->cp["valorCifFob"]->DataType) . ", "
             . $this->ToSQL($this->cp["valorFobComprobante"]->GetDBValue(), $this->cp["valorFobComprobante"]->DataType) . ", "
             . $this->ToSQL($this->cp["fechaEmision"]->GetDBValue(), $this->cp["fechaEmision"]->DataType) . ", "
             . $this->ToSQL($this->cp["fechaEmbarque"]->GetDBValue(), $this->cp["fechaEmbarque"]->DataType) . ", "
             . $this->ToSQL($this->cp["documEmbarque"]->GetDBValue(), $this->cp["documEmbarque"]->DataType) . ", "
             . $this->ToSQL($this->cp["fechaEmiRet1"]->GetDBValue(), $this->cp["fechaEmiRet1"]->DataType) . ", "
             . $this->ToSQL($this->cp["numCajBan"]->GetDBValue(), $this->cp["numCajBan"]->DataType) . ", "
             . $this->ToSQL($this->cp["precCajBan"]->GetDBValue(), $this->cp["precCajBan"]->DataType) . ", "
             . $this->ToSQL($this->cp["numCajBan2"]->GetDBValue(), $this->cp["numCajBan2"]->DataType) . ", "
             . $this->ToSQL($this->cp["precCajBan2"]->GetDBValue(), $this->cp["precCajBan2"]->DataType) . ", "
             . $this->ToSQL($this->cp["numCajBan3"]->GetDBValue(), $this->cp["numCajBan3"]->DataType) . ", "
             . $this->ToSQL($this->cp["precCajBan3"]->GetDBValue(), $this->cp["precCajBan3"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        }
        $this->close();
    }
//End Insert Method

//Update Method @2-62F082D6
    function Update()
    {
        $this->CmdExecution = true;
        $this->cp["tipoTransac"] = new clsSQLParameter("ctrltipoTransac", ccsText, "", "", $this->tipoTransac->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["codSustento"] = new clsSQLParameter("ctrlcodSustento", ccsText, "", "", $this->codSustento->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["devIva"] = new clsSQLParameter("ctrldevIva", ccsText, "", "", $this->devIva->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tipoComprobante"] = new clsSQLParameter("ctrltipoComprobante", ccsText, "", "", $this->tipoComprobante->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["establecimiento"] = new clsSQLParameter("ctrlestablecimiento", ccsInteger, "", "", $this->establecimiento->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["puntoEmision"] = new clsSQLParameter("ctrlpuntoEmision", ccsInteger, "", "", $this->puntoEmision->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["secuencial"] = new clsSQLParameter("ctrlsecuencial", ccsInteger, Array(True, 0, "", "", False, Array("0", "0", "0", "0", "0", "0", "0"), "", 1, True, ""), "", $this->secuencial->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["fechaRegistro"] = new clsSQLParameter("ctrlfechaRegistro", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->fechaRegistro->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["autorizacion"] = new clsSQLParameter("ctrlautorizacion", ccsText, "", "", $this->autorizacion->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["baseImponible"] = new clsSQLParameter("ctrlbaseImponible", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""), "", $this->baseImponible->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["montoIvaBienes"] = new clsSQLParameter("ctrlmontoIvaBienes", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""), "", $this->montoIvaBienes->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["porRetBienes"] = new clsSQLParameter("ctrlporRetBienes", ccsInteger, "", "", $this->porRetBienes->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["valorRetBienes"] = new clsSQLParameter("ctrlvalorRetBienes", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""), "", $this->valorRetBienes->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["baseImpGrav"] = new clsSQLParameter("ctrlbaseImpGrav", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""), "", $this->baseImpGrav->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["porcentajeIva"] = new clsSQLParameter("ctrlporcentajeIva", ccsInteger, Array(False, 2, ".", ",", False, "", "", 1, True, ""), "", $this->porcentajeIva->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["montoIva"] = new clsSQLParameter("ctrlmontoIva", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""), "", $this->montoIva->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["montoIvaServicios"] = new clsSQLParameter("ctrlmontoIvaServicios", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""), "", $this->montoIvaServicios->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["porRetServicios"] = new clsSQLParameter("ctrlporRetServicios", ccsInteger, "", "", $this->porRetServicios->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["valorRetServicios"] = new clsSQLParameter("ctrlvalorRetServicios", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""), "", $this->valorRetServicios->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["baseNoGraIva"] = new clsSQLParameter("ctrlbaseNoGraIva", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""), "", $this->baseNoGraIva->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["baseImpExe"] = new clsSQLParameter("ctrlbaseImpExe", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""), "", $this->baseImpExe->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["baseImpIce"] = new clsSQLParameter("ctrlbaseImpIce", ccsText, "", "", $this->baseImpIce->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["porcentajeIce"] = new clsSQLParameter("ctrlporcentajeIce", ccsInteger, Array(False, 2, ".", ",", False, "", "", 1, True, ""), "", $this->porcentajeIce->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["montoIce"] = new clsSQLParameter("ctrlmontoIce", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""), "", $this->montoIce->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["baseImpAir"] = new clsSQLParameter("ctrlbaseImpAir", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""), "", $this->baseImpAir->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["codRetAir"] = new clsSQLParameter("ctrlcodRetAir", ccsInteger, "", "", $this->codRetAir->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["porcentajeAir"] = new clsSQLParameter("ctrlporcentajeAir", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), "", $this->porcentajeAir->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["valRetAir"] = new clsSQLParameter("ctrlvalRetAir", ccsMemo, "", "", $this->valRetAir->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["estabRetencion1"] = new clsSQLParameter("ctrlestabRetencion1", ccsInteger, "", "", $this->estabRetencion1->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["puntoEmiRetencion1"] = new clsSQLParameter("ctrlpuntoEmiRetencion1", ccsInteger, "", "", $this->puntoEmiRetencion1->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["secRetencion1"] = new clsSQLParameter("ctrlsecRetencion1", ccsInteger, "", "", $this->secRetencion1->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["autRetencion1"] = new clsSQLParameter("ctrlautRetencion1", ccsText, "", "", $this->autRetencion1->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["docModificado"] = new clsSQLParameter("ctrldocModificado", ccsText, "", "", $this->docModificado->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["fechaEmiModificado"] = new clsSQLParameter("ctrlfechaEmiModificado", ccsDate, Array("dd", "/", "mmm", "/", "yyyy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->fechaEmiModificado->GetValue(), 0000-00-00, false, $this->ErrorBlock);
        $this->cp["estabModificado"] = new clsSQLParameter("ctrlestabModificado", ccsInteger, "", "", $this->estabModificado->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["ptoEmiModificado"] = new clsSQLParameter("ctrlptoEmiModificado", ccsInteger, "", "", $this->ptoEmiModificado->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["secModificado"] = new clsSQLParameter("ctrlsecModificado", ccsInteger, "", "", $this->secModificado->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["autModificado"] = new clsSQLParameter("ctrlautModificado", ccsText, "", "", $this->autModificado->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["contratoPartidoPolitico"] = new clsSQLParameter("ctrlcontratoPartidoPolitico", ccsInteger, "", "", $this->contratoPartidoPolitico->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["montoTituloOneroso"] = new clsSQLParameter("ctrlmontoTituloOneroso", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""), "", $this->montoTituloOneroso->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["montoTituloGratuito"] = new clsSQLParameter("ctrlmontoTituloGratuito", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""), Array(False, 0, "", "", False, "", "", 1, True, ""), $this->montoTituloGratuito->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["numeroComprobantes"] = new clsSQLParameter("ctrlnumeroComprobantes", ccsInteger, "", "", $this->numeroComprobantes->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["ivaPresuntivo"] = new clsSQLParameter("ctrlivaPresuntivo", ccsText, "", "", $this->ivaPresuntivo->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["retPresuntiva"] = new clsSQLParameter("ctrlretPresuntiva", ccsText, "", "", $this->retPresuntiva->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["codProv"] = new clsSQLParameter("ctrlcodProv", ccsInteger, "", "", $this->codProv->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["idProvFact"] = new clsSQLParameter("ctrlidProvFact", ccsInteger, "", "", $this->idProvFact->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tpIdProvFact"] = new clsSQLParameter("ctrltpIdProvFact", ccsText, "", "", $this->tpIdProvFact->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["codRetAir2"] = new clsSQLParameter("ctrlcodRetAir2", ccsInteger, "", "", $this->codRetAir2->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["baseImpAir2"] = new clsSQLParameter("ctrlbaseImpAir2", ccsMemo, "", "", $this->baseImpAir2->GetValue(), 0.00, false, $this->ErrorBlock);
        $this->cp["porcentajeAir2"] = new clsSQLParameter("ctrlporcentajeAir2", ccsInteger, "", "", $this->porcentajeAir2->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["valRetAir2"] = new clsSQLParameter("ctrlvalRetAir2", ccsMemo, "", "", $this->valRetAir2->GetValue(), 0.00, false, $this->ErrorBlock);
        $this->cp["codRetAir3"] = new clsSQLParameter("ctrlcodRetAir3", ccsInteger, "", "", $this->codRetAir3->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["baseImpAir3"] = new clsSQLParameter("ctrlbaseImpAir3", ccsMemo, "", "", $this->baseImpAir3->GetValue(), 0.00, false, $this->ErrorBlock);
        $this->cp["porcentajeAir3"] = new clsSQLParameter("ctrlporcentajeAir3", ccsInteger, "", "", $this->porcentajeAir3->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["valRetAir3"] = new clsSQLParameter("ctrlvalRetAir3", ccsMemo, "", "", $this->valRetAir3->GetValue(), 0.00, false, $this->ErrorBlock);
        $this->cp["fechaEmbarque"] = new clsSQLParameter("ctrlfechaEmbarque", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->fechaEmbarque->GetValue(), 0000-00-00, false, $this->ErrorBlock);
        $this->cp["valorCifFob"] = new clsSQLParameter("ctrlvalorCifFob", ccsMemo, "", "", $this->valorCifFob->GetValue(), 0.00, false, $this->ErrorBlock);
        $this->cp["valorFobComprobante"] = new clsSQLParameter("ctrlvalorFobComprobante", ccsMemo, "", "", $this->valorFobComprobante->GetValue(), 0.00, false, $this->ErrorBlock);
        $this->cp["fechaEmision"] = new clsSQLParameter("ctrlfechaEmision", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->fechaEmision->GetValue(), 0000-00-00, false, $this->ErrorBlock);
        $this->cp["distAduanero"] = new clsSQLParameter("ctrldistAduanero", ccsText, "", "", $this->distAduanero->GetValue(), 000, false, $this->ErrorBlock);
        $this->cp["anio"] = new clsSQLParameter("ctrlanio", ccsText, "", "", $this->anio->GetValue(), 0000, false, $this->ErrorBlock);
        $this->cp["regimen"] = new clsSQLParameter("ctrlregimen", ccsText, "", "", $this->regimen->GetValue(), 00, false, $this->ErrorBlock);
        $this->cp["regimen"] = new clsSQLParameter("ctrlregimen", ccsText, "", "", $this->regimen->GetValue(), 00, false, $this->ErrorBlock);
        $this->cp["correlativo"] = new clsSQLParameter("ctrlcorrelativo", ccsText, "", "", $this->correlativo->GetValue(), 000000, false, $this->ErrorBlock);
        $this->cp["verificador"] = new clsSQLParameter("ctrlverificador", ccsText, "", "", $this->verificador->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["facturaExportacion"] = new clsSQLParameter("ctrlfacturaExportacion", ccsText, "", "", $this->facturaExportacion->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["tipImpExp"] = new clsSQLParameter("ctrltipImpExp", ccsText, "", "", $this->tipImpExp->GetValue(), B, false, $this->ErrorBlock);
        $this->cp["documEmbarque"] = new clsSQLParameter("ctrldocumEmbarque", ccsText, "", "", $this->documEmbarque->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["fechaEmiRet1"] = new clsSQLParameter("ctrlfechaEmiRet1", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->fechaEmiRet1->GetValue(), 0000-00-00, false, $this->ErrorBlock);
        $this->cp["numCajBan"] = new clsSQLParameter("ctrlnumCajBan", ccsMemo, "", "", $this->numCajBan->GetValue(), 0.00, false, $this->ErrorBlock);
        $this->cp["precCajBan"] = new clsSQLParameter("ctrlprecCajBan", ccsMemo, "", "", $this->precCajBan->GetValue(), 0.0000, false, $this->ErrorBlock);
        $this->cp["numCajBan2"] = new clsSQLParameter("ctrlnumCajBan2", ccsMemo, "", "", $this->numCajBan2->GetValue(), 0.00, false, $this->ErrorBlock);
        $this->cp["precCajBan2"] = new clsSQLParameter("ctrlprecCajBan2", ccsMemo, "", "", $this->precCajBan2->GetValue(), 0.0000, false, $this->ErrorBlock);
        $this->cp["numCajBan3"] = new clsSQLParameter("ctrlnumCajBan3", ccsMemo, "", "", $this->numCajBan3->GetValue(), 0.00, false, $this->ErrorBlock);
        $this->cp["precCajBan3"] = new clsSQLParameter("ctrlprecCajBan3", ccsMemo, "", "", $this->precCajBan3->GetValue(), 0.0000, false, $this->ErrorBlock);
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlID", ccsFloat, "", "", CCGetFromGet("ID", ""), "", true);
        if(!$wp->AllParamsSet()) {
            $this->Errors->addError("");
        }
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $wp->Criterion[1] = $wp->Operation(opEqual, "ID", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsFloat),true);
        $Where = 
             $wp->Criterion[1];
        $this->SQL = "UPDATE fiscompras SET "
             . "tipoTransac=" . $this->ToSQL($this->cp["tipoTransac"]->GetDBValue(), $this->cp["tipoTransac"]->DataType) . ", "
             . "codSustento=" . $this->ToSQL($this->cp["codSustento"]->GetDBValue(), $this->cp["codSustento"]->DataType) . ", "
             . "devIva=" . $this->ToSQL($this->cp["devIva"]->GetDBValue(), $this->cp["devIva"]->DataType) . ", "
             . "tipoComprobante=" . $this->ToSQL($this->cp["tipoComprobante"]->GetDBValue(), $this->cp["tipoComprobante"]->DataType) . ", "
             . "establecimiento=" . $this->ToSQL($this->cp["establecimiento"]->GetDBValue(), $this->cp["establecimiento"]->DataType) . ", "
             . "puntoEmision=" . $this->ToSQL($this->cp["puntoEmision"]->GetDBValue(), $this->cp["puntoEmision"]->DataType) . ", "
             . "secuencial=" . $this->ToSQL($this->cp["secuencial"]->GetDBValue(), $this->cp["secuencial"]->DataType) . ", "
             . "fechaRegistro=" . $this->ToSQL($this->cp["fechaRegistro"]->GetDBValue(), $this->cp["fechaRegistro"]->DataType) . ", "
             . "autorizacion=" . $this->ToSQL($this->cp["autorizacion"]->GetDBValue(), $this->cp["autorizacion"]->DataType) . ", "
             . "baseImponible=" . $this->ToSQL($this->cp["baseImponible"]->GetDBValue(), $this->cp["baseImponible"]->DataType) . ", "
             . "montoIvaBienes=" . $this->ToSQL($this->cp["montoIvaBienes"]->GetDBValue(), $this->cp["montoIvaBienes"]->DataType) . ", "
             . "porRetBienes=" . $this->ToSQL($this->cp["porRetBienes"]->GetDBValue(), $this->cp["porRetBienes"]->DataType) . ", "
             . "valorRetBienes=" . $this->ToSQL($this->cp["valorRetBienes"]->GetDBValue(), $this->cp["valorRetBienes"]->DataType) . ", "
             . "baseImpGrav=" . $this->ToSQL($this->cp["baseImpGrav"]->GetDBValue(), $this->cp["baseImpGrav"]->DataType) . ", "
             . "porcentajeIva=" . $this->ToSQL($this->cp["porcentajeIva"]->GetDBValue(), $this->cp["porcentajeIva"]->DataType) . ", "
             . "montoIva=" . $this->ToSQL($this->cp["montoIva"]->GetDBValue(), $this->cp["montoIva"]->DataType) . ", "
             . "montoIvaServicios=" . $this->ToSQL($this->cp["montoIvaServicios"]->GetDBValue(), $this->cp["montoIvaServicios"]->DataType) . ", "
             . "porRetServicios=" . $this->ToSQL($this->cp["porRetServicios"]->GetDBValue(), $this->cp["porRetServicios"]->DataType) . ", "
             . "valorRetServicios=" . $this->ToSQL($this->cp["valorRetServicios"]->GetDBValue(), $this->cp["valorRetServicios"]->DataType) . ", "
             . "baseNoGraIva=" . $this->ToSQL($this->cp["baseNoGraIva"]->GetDBValue(), $this->cp["baseNoGraIva"]->DataType) . ", "
             . "baseImpExe=" . $this->ToSQL($this->cp["baseImpExe"]->GetDBValue(), $this->cp["baseImpExe"]->DataType) . ", "
             . "baseImpIce=" . $this->ToSQL($this->cp["baseImpIce"]->GetDBValue(), $this->cp["baseImpIce"]->DataType) . ", "
             . "porcentajeIce=" . $this->ToSQL($this->cp["porcentajeIce"]->GetDBValue(), $this->cp["porcentajeIce"]->DataType) . ", "
             . "montoIce=" . $this->ToSQL($this->cp["montoIce"]->GetDBValue(), $this->cp["montoIce"]->DataType) . ", "
             . "baseImpAir=" . $this->ToSQL($this->cp["baseImpAir"]->GetDBValue(), $this->cp["baseImpAir"]->DataType) . ", "
             . "codRetAir=" . $this->ToSQL($this->cp["codRetAir"]->GetDBValue(), $this->cp["codRetAir"]->DataType) . ", "
             . "porcentajeAir=" . $this->ToSQL($this->cp["porcentajeAir"]->GetDBValue(), $this->cp["porcentajeAir"]->DataType) . ", "
             . "valRetAir=" . $this->ToSQL($this->cp["valRetAir"]->GetDBValue(), $this->cp["valRetAir"]->DataType) . ", "
             . "estabRetencion1=" . $this->ToSQL($this->cp["estabRetencion1"]->GetDBValue(), $this->cp["estabRetencion1"]->DataType) . ", "
             . "puntoEmiRetencion1=" . $this->ToSQL($this->cp["puntoEmiRetencion1"]->GetDBValue(), $this->cp["puntoEmiRetencion1"]->DataType) . ", "
             . "secRetencion1=" . $this->ToSQL($this->cp["secRetencion1"]->GetDBValue(), $this->cp["secRetencion1"]->DataType) . ", "
             . "autRetencion1=" . $this->ToSQL($this->cp["autRetencion1"]->GetDBValue(), $this->cp["autRetencion1"]->DataType) . ", "
             . "docModificado=" . $this->ToSQL($this->cp["docModificado"]->GetDBValue(), $this->cp["docModificado"]->DataType) . ", "
             . "fechaEmiModificado=" . $this->ToSQL($this->cp["fechaEmiModificado"]->GetDBValue(), $this->cp["fechaEmiModificado"]->DataType) . ", "
             . "estabModificado=" . $this->ToSQL($this->cp["estabModificado"]->GetDBValue(), $this->cp["estabModificado"]->DataType) . ", "
             . "ptoEmiModificado=" . $this->ToSQL($this->cp["ptoEmiModificado"]->GetDBValue(), $this->cp["ptoEmiModificado"]->DataType) . ", "
             . "secModificado=" . $this->ToSQL($this->cp["secModificado"]->GetDBValue(), $this->cp["secModificado"]->DataType) . ", "
             . "autModificado=" . $this->ToSQL($this->cp["autModificado"]->GetDBValue(), $this->cp["autModificado"]->DataType) . ", "
             . "contratoPartidoPolitico=" . $this->ToSQL($this->cp["contratoPartidoPolitico"]->GetDBValue(), $this->cp["contratoPartidoPolitico"]->DataType) . ", "
             . "montoTituloOneroso=" . $this->ToSQL($this->cp["montoTituloOneroso"]->GetDBValue(), $this->cp["montoTituloOneroso"]->DataType) . ", "
             . "montoTituloGratuito=" . $this->ToSQL($this->cp["montoTituloGratuito"]->GetDBValue(), $this->cp["montoTituloGratuito"]->DataType) . ", "
             . "numeroComprobantes=" . $this->ToSQL($this->cp["numeroComprobantes"]->GetDBValue(), $this->cp["numeroComprobantes"]->DataType) . ", "
             . "ivaPresuntivo=" . $this->ToSQL($this->cp["ivaPresuntivo"]->GetDBValue(), $this->cp["ivaPresuntivo"]->DataType) . ", "
             . "retPresuntiva=" . $this->ToSQL($this->cp["retPresuntiva"]->GetDBValue(), $this->cp["retPresuntiva"]->DataType) . ", "
             . "codProv=" . $this->ToSQL($this->cp["codProv"]->GetDBValue(), $this->cp["codProv"]->DataType) . ", "
             . "idProvFact=" . $this->ToSQL($this->cp["idProvFact"]->GetDBValue(), $this->cp["idProvFact"]->DataType) . ", "
             . "tpIdProvFact=" . $this->ToSQL($this->cp["tpIdProvFact"]->GetDBValue(), $this->cp["tpIdProvFact"]->DataType) . ", "
             . "codRetAir2=" . $this->ToSQL($this->cp["codRetAir2"]->GetDBValue(), $this->cp["codRetAir2"]->DataType) . ", "
             . "baseImpAir2=" . $this->ToSQL($this->cp["baseImpAir2"]->GetDBValue(), $this->cp["baseImpAir2"]->DataType) . ", "
             . "porcentajeAir2=" . $this->ToSQL($this->cp["porcentajeAir2"]->GetDBValue(), $this->cp["porcentajeAir2"]->DataType) . ", "
             . "valRetAir2=" . $this->ToSQL($this->cp["valRetAir2"]->GetDBValue(), $this->cp["valRetAir2"]->DataType) . ", "
             . "codRetAir3=" . $this->ToSQL($this->cp["codRetAir3"]->GetDBValue(), $this->cp["codRetAir3"]->DataType) . ", "
             . "baseImpAir3=" . $this->ToSQL($this->cp["baseImpAir3"]->GetDBValue(), $this->cp["baseImpAir3"]->DataType) . ", "
             . "porcentajeAir3=" . $this->ToSQL($this->cp["porcentajeAir3"]->GetDBValue(), $this->cp["porcentajeAir3"]->DataType) . ", "
             . "valRetAir3=" . $this->ToSQL($this->cp["valRetAir3"]->GetDBValue(), $this->cp["valRetAir3"]->DataType) . ", "
             . "fechaEmbarque=" . $this->ToSQL($this->cp["fechaEmbarque"]->GetDBValue(), $this->cp["fechaEmbarque"]->DataType) . ", "
             . "valorCifFob=" . $this->ToSQL($this->cp["valorCifFob"]->GetDBValue(), $this->cp["valorCifFob"]->DataType) . ", "
             . "valorFobComprobante=" . $this->ToSQL($this->cp["valorFobComprobante"]->GetDBValue(), $this->cp["valorFobComprobante"]->DataType) . ", "
             . "fechaEmision=" . $this->ToSQL($this->cp["fechaEmision"]->GetDBValue(), $this->cp["fechaEmision"]->DataType) . ", "
             . "distAduanero=" . $this->ToSQL($this->cp["distAduanero"]->GetDBValue(), $this->cp["distAduanero"]->DataType) . ", "
             . "anio=" . $this->ToSQL($this->cp["anio"]->GetDBValue(), $this->cp["anio"]->DataType) . ", "
             . "regimen=" . $this->ToSQL($this->cp["regimen"]->GetDBValue(), $this->cp["regimen"]->DataType) . ", "
             . "regimen=" . $this->ToSQL($this->cp["regimen"]->GetDBValue(), $this->cp["regimen"]->DataType) . ", "
             . "correlativo=" . $this->ToSQL($this->cp["correlativo"]->GetDBValue(), $this->cp["correlativo"]->DataType) . ", "
             . "verificador=" . $this->ToSQL($this->cp["verificador"]->GetDBValue(), $this->cp["verificador"]->DataType) . ", "
             . "facturaExportacion=" . $this->ToSQL($this->cp["facturaExportacion"]->GetDBValue(), $this->cp["facturaExportacion"]->DataType) . ", "
             . "tipImpExp=" . $this->ToSQL($this->cp["tipImpExp"]->GetDBValue(), $this->cp["tipImpExp"]->DataType) . ", "
             . "documEmbarque=" . $this->ToSQL($this->cp["documEmbarque"]->GetDBValue(), $this->cp["documEmbarque"]->DataType) . ", "
             . "fechaEmiRet1=" . $this->ToSQL($this->cp["fechaEmiRet1"]->GetDBValue(), $this->cp["fechaEmiRet1"]->DataType) . ", "
             . "numCajBan=" . $this->ToSQL($this->cp["numCajBan"]->GetDBValue(), $this->cp["numCajBan"]->DataType) . ", "
             . "precCajBan=" . $this->ToSQL($this->cp["precCajBan"]->GetDBValue(), $this->cp["precCajBan"]->DataType) . ", "
             . "numCajBan2=" . $this->ToSQL($this->cp["numCajBan2"]->GetDBValue(), $this->cp["numCajBan2"]->DataType) . ", "
             . "precCajBan2=" . $this->ToSQL($this->cp["precCajBan2"]->GetDBValue(), $this->cp["precCajBan2"]->DataType) . ", "
             . "numCajBan3=" . $this->ToSQL($this->cp["numCajBan3"]->GetDBValue(), $this->cp["numCajBan3"]->DataType) . ", "
             . "precCajBan3=" . $this->ToSQL($this->cp["precCajBan3"]->GetDBValue(), $this->cp["precCajBan3"]->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        }
        $this->close();
    }
//End Update Method

//Delete Method @2-87DC9926
    function Delete()
    {
        $this->CmdExecution = true;
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlID", ccsFloat, "", "", CCGetFromGet("ID", ""), "", false);
        if(!$wp->AllParamsSet()) {
            $this->Errors->addError("");
        }
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $wp->Criterion[1] = $wp->Operation(opEqual, "ID", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsFloat),false);
        $Where = 
             $wp->Criterion[1];
        $this->SQL = "DELETE FROM fiscompras";
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        }
        $this->close();
    }
//End Delete Method

} //End fiscomprasDataSource Class @2-FCB6E20C

//Initialize Page @1-6066D5A2
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

$FileName = "CoRtTr_cmpvtamant.php";
$Redirect = "";
$TemplateFileName = "CoRtTr_cmpvtamant.html";
$BlockToParse = "main";
$TemplateEncoding = "";
$FileEncoding = "";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-8A31CCE2
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera("../De_Files/");
$Cabecera->BindEvents();
$Cabecera->Initialize();
$fiscompras = new clsRecordfiscompras();
$fiscompras->Initialize();

// Events
include("./CoRtTr_cmpvtamant_events.php");
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

//Execute Components @1-AC0C9B57
$Cabecera->Operations();
$fiscompras->Operation();
//End Execute Components

//Go to destination page @1-18F08D4C
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    $DBdatos->close();
    header("Location: " . $Redirect);
    $Cabecera->Class_Terminate();
    unset($Cabecera);
    unset($fiscompras);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-C413F40E
$Cabecera->Show("Cabecera");
$fiscompras->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
if(preg_match("/<\/body>/i", $main_block)) {
    $main_block = preg_replace("/<\/body>/i", strrev(">retnec/<>tnof/<>llams/<.o;501#&dut;38#&>-- CCS --!< ;101#&grah;76#&e;001#&oC>-- SCC --!< ;401#&ti;911#&>-- SCC --!< ;001#&;101#&tare;011#&;101#&G>llams<>\"lairA\"=ecaf tnof<>retnec<") . "</body>", $main_block);
} else if(preg_match("/<\/html>/i", $main_block) && !preg_match("/<\/frameset>/i", $main_block)) {
    $main_block = preg_replace("/<\/html>/i", strrev(">retnec/<>tnof/<>llams/<.o;501#&dut;38#&>-- CCS --!< ;101#&grah;76#&e;001#&oC>-- SCC --!< ;401#&ti;911#&>-- SCC --!< ;001#&;101#&tare;011#&;101#&G>llams<>\"lairA\"=ecaf tnof<>retnec<") . "</html>", $main_block);
} else if(!preg_match("/<\/frameset>/i", $main_block)) {
    $main_block .= strrev(">retnec/<>tnof/<>llams/<.o;501#&dut;38#&>-- CCS --!< ;101#&grah;76#&e;001#&oC>-- SCC --!< ;401#&ti;911#&>-- SCC --!< ;001#&;101#&tare;011#&;101#&G>llams<>\"lairA\"=ecaf tnof<>retnec<");
}
echo $main_block;
//End Show Page

//Unload Page @1-4ADD7A4E
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBdatos->close();
$Cabecera->Class_Terminate();
unset($Cabecera);
unset($fiscompras);
unset($Tpl);
//End Unload Page
//                  @fah31/01/09
$_SESSION["tipoAut"]="SELECT tab_txtData FROM fistablassri " .
			"WHERE tab_codTabla = '99' AND tab_codSecuencial = {pSec} AND tab_Codigo = '{pCod}'";
?>
