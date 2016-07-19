<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @2-11FBC0D5
include_once(RelativePath . "/Rt_Files/../De_Files/Cabecera.php");
//End Include Page implementation
include_once("GenUti.inc.php");

class clsRecordfiscomprasSearch { //fiscomprasSearch Class @125-C3E65B19

//Variables @125-B2F7A83E

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

//Class_Initialize Event @125-D4281F9D
    function clsRecordfiscomprasSearch()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record fiscomprasSearch/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "fiscomprasSearch";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->s_tipoTransac = new clsControl(ccsTextBox, "s_tipoTransac", "s_tipoTransac", ccsText, "", CCGetRequestParam("s_tipoTransac", $Method));
            $this->s_codSustento = new clsControl(ccsTextBox, "s_codSustento", "s_codSustento", ccsText, "", CCGetRequestParam("s_codSustento", $Method));
            $this->s_tpIdProv = new clsControl(ccsTextBox, "s_tpIdProv", "s_tpIdProv", ccsText, "", CCGetRequestParam("s_tpIdProv", $Method));
            $this->s_idProv = new clsControl(ccsTextBox, "s_idProv", "s_idProv", ccsText, "", CCGetRequestParam("s_idProv", $Method));
            $this->s_tipoComprobante = new clsControl(ccsTextBox, "s_tipoComprobante", "s_tipoComprobante", ccsText, "", CCGetRequestParam("s_tipoComprobante", $Method));
            $this->s_establecimiento = new clsControl(ccsTextBox, "s_establecimiento", "s_establecimiento", ccsInteger, "", CCGetRequestParam("s_establecimiento", $Method));
            $this->s_puntoEmision = new clsControl(ccsTextBox, "s_puntoEmision", "s_puntoEmision", ccsInteger, "", CCGetRequestParam("s_puntoEmision", $Method));
            $this->s_secuencial = new clsControl(ccsTextBox, "s_secuencial", "s_secuencial", ccsText, "", CCGetRequestParam("s_secuencial", $Method));
            $this->s_fechaRegistro = new clsControl(ccsTextBox, "s_fechaRegistro", "s_fechaRegistro", ccsText, "", CCGetRequestParam("s_fechaRegistro", $Method));
            $this->s_autorizacion = new clsControl(ccsTextBox, "s_autorizacion", "s_autorizacion", ccsInteger, "", CCGetRequestParam("s_autorizacion", $Method));
            $this->s_porcentajeIva = new clsControl(ccsTextBox, "s_porcentajeIva", "s_porcentajeIva", ccsInteger, "", CCGetRequestParam("s_porcentajeIva", $Method));
            $this->s_porcentajeIce = new clsControl(ccsTextBox, "s_porcentajeIce", "s_porcentajeIce", ccsInteger, "", CCGetRequestParam("s_porcentajeIce", $Method));
            $this->s_porRetBienes = new clsControl(ccsTextBox, "s_porRetBienes", "s_porRetBienes", ccsInteger, "", CCGetRequestParam("s_porRetBienes", $Method));
            $this->s_porRetServicios = new clsControl(ccsTextBox, "s_porRetServicios", "s_porRetServicios", ccsInteger, "", CCGetRequestParam("s_porRetServicios", $Method));
            $this->s_codRetAir = new clsControl(ccsTextBox, "s_codRetAir", "s_codRetAir", ccsInteger, "", CCGetRequestParam("s_codRetAir", $Method));
            $this->s_porcentajeAir = new clsControl(ccsTextBox, "s_porcentajeAir", "s_porcentajeAir", ccsInteger, "", CCGetRequestParam("s_porcentajeAir", $Method));
            $this->s_estabRetencion1 = new clsControl(ccsTextBox, "s_estabRetencion1", "s_estabRetencion1", ccsInteger, "", CCGetRequestParam("s_estabRetencion1", $Method));
            $this->s_puntoEmiRetencion1 = new clsControl(ccsTextBox, "s_puntoEmiRetencion1", "s_puntoEmiRetencion1", ccsInteger, "", CCGetRequestParam("s_puntoEmiRetencion1", $Method));
            $this->s_secRetencion1 = new clsControl(ccsTextBox, "s_secRetencion1", "s_secRetencion1", ccsInteger, "", CCGetRequestParam("s_secRetencion1", $Method));
            $this->s_estabModificado = new clsControl(ccsTextBox, "s_estabModificado", "s_estabModificado", ccsInteger, "", CCGetRequestParam("s_estabModificado", $Method));
            $this->s_ptoEmiModificado = new clsControl(ccsTextBox, "s_ptoEmiModificado", "s_ptoEmiModificado", ccsInteger, "", CCGetRequestParam("s_ptoEmiModificado", $Method));
            $this->s_secModificado = new clsControl(ccsTextBox, "s_secModificado", "s_secModificado", ccsInteger, "", CCGetRequestParam("s_secModificado", $Method));
            $this->s_autModificado = new clsControl(ccsTextBox, "s_autModificado", "s_autModificado", ccsInteger, "", CCGetRequestParam("s_autModificado", $Method));
            $this->s_ivaPresuntivo = new clsControl(ccsTextBox, "s_ivaPresuntivo", "s_ivaPresuntivo", ccsText, "", CCGetRequestParam("s_ivaPresuntivo", $Method));
            $this->Button_DoSearch = new clsButton("Button_DoSearch");
        }
    }
//End Class_Initialize Event

//Validate Method @125-CF57B0C1
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_tipoTransac->Validate() && $Validation);
        $Validation = ($this->s_codSustento->Validate() && $Validation);
        $Validation = ($this->s_tpIdProv->Validate() && $Validation);
        $Validation = ($this->s_idProv->Validate() && $Validation);
        $Validation = ($this->s_tipoComprobante->Validate() && $Validation);
        $Validation = ($this->s_establecimiento->Validate() && $Validation);
        $Validation = ($this->s_puntoEmision->Validate() && $Validation);
        $Validation = ($this->s_secuencial->Validate() && $Validation);
        $Validation = ($this->s_fechaRegistro->Validate() && $Validation);
        $Validation = ($this->s_autorizacion->Validate() && $Validation);
        $Validation = ($this->s_porcentajeIva->Validate() && $Validation);
        $Validation = ($this->s_porcentajeIce->Validate() && $Validation);
        $Validation = ($this->s_porRetBienes->Validate() && $Validation);
        $Validation = ($this->s_porRetServicios->Validate() && $Validation);
        $Validation = ($this->s_codRetAir->Validate() && $Validation);
        $Validation = ($this->s_porcentajeAir->Validate() && $Validation);
        $Validation = ($this->s_estabRetencion1->Validate() && $Validation);
        $Validation = ($this->s_puntoEmiRetencion1->Validate() && $Validation);
        $Validation = ($this->s_secRetencion1->Validate() && $Validation);
        $Validation = ($this->s_estabModificado->Validate() && $Validation);
        $Validation = ($this->s_ptoEmiModificado->Validate() && $Validation);
        $Validation = ($this->s_secModificado->Validate() && $Validation);
        $Validation = ($this->s_autModificado->Validate() && $Validation);
        $Validation = ($this->s_ivaPresuntivo->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        $Validation =  $Validation && ($this->s_tipoTransac->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_codSustento->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_tpIdProv->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_idProv->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_tipoComprobante->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_establecimiento->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_puntoEmision->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_secuencial->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_fechaRegistro->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_autorizacion->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_porcentajeIva->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_porcentajeIce->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_porRetBienes->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_porRetServicios->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_codRetAir->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_porcentajeAir->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_estabRetencion1->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_puntoEmiRetencion1->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_secRetencion1->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_estabModificado->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_ptoEmiModificado->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_secModificado->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_autModificado->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_ivaPresuntivo->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @125-1728A1D2
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_tipoTransac->Errors->Count());
        $errors = ($errors || $this->s_codSustento->Errors->Count());
        $errors = ($errors || $this->s_tpIdProv->Errors->Count());
        $errors = ($errors || $this->s_idProv->Errors->Count());
        $errors = ($errors || $this->s_tipoComprobante->Errors->Count());
        $errors = ($errors || $this->s_establecimiento->Errors->Count());
        $errors = ($errors || $this->s_puntoEmision->Errors->Count());
        $errors = ($errors || $this->s_secuencial->Errors->Count());
        $errors = ($errors || $this->s_fechaRegistro->Errors->Count());
        $errors = ($errors || $this->s_autorizacion->Errors->Count());
        $errors = ($errors || $this->s_porcentajeIva->Errors->Count());
        $errors = ($errors || $this->s_porcentajeIce->Errors->Count());
        $errors = ($errors || $this->s_porRetBienes->Errors->Count());
        $errors = ($errors || $this->s_porRetServicios->Errors->Count());
        $errors = ($errors || $this->s_codRetAir->Errors->Count());
        $errors = ($errors || $this->s_porcentajeAir->Errors->Count());
        $errors = ($errors || $this->s_estabRetencion1->Errors->Count());
        $errors = ($errors || $this->s_puntoEmiRetencion1->Errors->Count());
        $errors = ($errors || $this->s_secRetencion1->Errors->Count());
        $errors = ($errors || $this->s_estabModificado->Errors->Count());
        $errors = ($errors || $this->s_ptoEmiModificado->Errors->Count());
        $errors = ($errors || $this->s_secModificado->Errors->Count());
        $errors = ($errors || $this->s_autModificado->Errors->Count());
        $errors = ($errors || $this->s_ivaPresuntivo->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @125-610EB869
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
            $this->PressedButton = "Button_DoSearch";
            if(strlen(CCGetParam("Button_DoSearch", ""))) {
                $this->PressedButton = "Button_DoSearch";
            }
        }
        $Redirect = "CoRtTr_cmpvtasearch.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick")) {
                    $Redirect = "";
                } else {
                    $Redirect = "CoRtTr_cmpvtasearch.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", Array("Button_DoSearch")));
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @125-B4847AFC
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");


        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if(!$this->FormSubmitted)
        {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->s_tipoTransac->Errors->ToString();
            $Error .= $this->s_codSustento->Errors->ToString();
            $Error .= $this->s_tpIdProv->Errors->ToString();
            $Error .= $this->s_idProv->Errors->ToString();
            $Error .= $this->s_tipoComprobante->Errors->ToString();
            $Error .= $this->s_establecimiento->Errors->ToString();
            $Error .= $this->s_puntoEmision->Errors->ToString();
            $Error .= $this->s_secuencial->Errors->ToString();
            $Error .= $this->s_fechaRegistro->Errors->ToString();
            $Error .= $this->s_autorizacion->Errors->ToString();
            $Error .= $this->s_porcentajeIva->Errors->ToString();
            $Error .= $this->s_porcentajeIce->Errors->ToString();
            $Error .= $this->s_porRetBienes->Errors->ToString();
            $Error .= $this->s_porRetServicios->Errors->ToString();
            $Error .= $this->s_codRetAir->Errors->ToString();
            $Error .= $this->s_porcentajeAir->Errors->ToString();
            $Error .= $this->s_estabRetencion1->Errors->ToString();
            $Error .= $this->s_puntoEmiRetencion1->Errors->ToString();
            $Error .= $this->s_secRetencion1->Errors->ToString();
            $Error .= $this->s_estabModificado->Errors->ToString();
            $Error .= $this->s_ptoEmiModificado->Errors->ToString();
            $Error .= $this->s_secModificado->Errors->ToString();
            $Error .= $this->s_autModificado->Errors->ToString();
            $Error .= $this->s_ivaPresuntivo->Errors->ToString();
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
        fiscompras_BeforeShow();
        $this->s_tipoTransac->Show();
        $this->s_codSustento->Show();
        $this->s_tpIdProv->Show();
        $this->s_idProv->Show();
        $this->s_tipoComprobante->Show();
        $this->s_establecimiento->Show();
        $this->s_puntoEmision->Show();
        $this->s_secuencial->Show();
        $this->s_fechaRegistro->Show();
        $this->s_autorizacion->Show();
        $this->s_porcentajeIva->Show();
        $this->s_porcentajeIce->Show();
        $this->s_porRetBienes->Show();
        $this->s_porRetServicios->Show();
        $this->s_codRetAir->Show();
        $this->s_porcentajeAir->Show();
        $this->s_estabRetencion1->Show();
        $this->s_puntoEmiRetencion1->Show();
        $this->s_secRetencion1->Show();
        $this->s_estabModificado->Show();
        $this->s_ptoEmiModificado->Show();
        $this->s_secModificado->Show();
        $this->s_autModificado->Show();
        $this->s_ivaPresuntivo->Show();
        $this->Button_DoSearch->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End fiscomprasSearch Class @125-FCB6E20C

class clsGridfiscompras { //fiscompras class @3-7FF7DF88

//Variables @3-FDF659BB

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
    var $Navigator1;
    var $Sorter_ID;
    var $Sorter_tipoTransac;
    var $Sorter_codSustento;
    var $Sorter_devIva;
    var $Sorter_idProv;
    var $Sorter_tipoComprobante;
    var $Sorter_fechaRegistro;
    var $Sorter_porcentajeIva;
    var $Sorter_porRetBienes;
    var $Sorter_porRetServicios;
    var $Sorter_codRetAir;
    var $Sorter_porcentajeAir;
    var $Navigator;
//End Variables

//Class_Initialize Event @3-5145722C
    function clsGridfiscompras()
    {
        global $FileName;
        $this->ComponentName = "fiscompras";
        $this->Visible = True;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid fiscompras";
        $this->ds = new clsfiscomprasDataSource();
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 25;
        else
            $this->PageSize = intval($this->PageSize);
        if ($this->PageSize > 10000)
            $this->PageSize = 10000;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        $this->SorterName = CCGetParam("fiscomprasOrder", "");
        $this->SorterDirection = CCGetParam("fiscomprasDir", "");

        $this->ID = new clsControl(ccsLabel, "ID", "ID", ccsFloat, "", CCGetRequestParam("ID", ccsGet));
        $this->tipoTransac = new clsControl(ccsLabel, "tipoTransac", "tipoTransac", ccsText, "", CCGetRequestParam("tipoTransac", ccsGet));
        $this->codSustento = new clsControl(ccsLabel, "codSustento", "codSustento", ccsText, "", CCGetRequestParam("codSustento", ccsGet));
        $this->devIva = new clsControl(ccsLabel, "devIva", "devIva", ccsText, "", CCGetRequestParam("devIva", ccsGet));
        $this->idProvFact = new clsControl(ccsLabel, "idProvFact", "idProvFact", ccsInteger, "", CCGetRequestParam("idProvFact", ccsGet));
        $this->txt_CliProv = new clsControl(ccsLabel, "txt_CliProv", "txt_CliProv", ccsText, "", CCGetRequestParam("txt_CliProv", ccsGet));
        $this->tipoComprobante = new clsControl(ccsLabel, "tipoComprobante", "tipoComprobante", ccsText, "", CCGetRequestParam("tipoComprobante", ccsGet));
        $this->establecimiento = new clsControl(ccsLabel, "establecimiento", "establecimiento", ccsInteger, "", CCGetRequestParam("establecimiento", ccsGet));
        $this->puntoEmision = new clsControl(ccsLabel, "puntoEmision", "puntoEmision", ccsInteger, "", CCGetRequestParam("puntoEmision", ccsGet));
        $this->secuencial = new clsControl(ccsLabel, "secuencial", "secuencial", ccsInteger, Array(True, 0, "", "", False, Array("0", "0", "0", "0", "0", "0", "0", "0", "0"), "", 1, True, ""), CCGetRequestParam("secuencial", ccsGet));
        $this->fechaRegistro = new clsControl(ccsLabel, "fechaRegistro", "fechaRegistro", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("fechaRegistro", ccsGet));
        $this->baseImponible = new clsControl(ccsLabel, "baseImponible", "baseImponible", ccsFloat, Array(True, 2, ".", "", False, Array("#", "#", "#"), Array("#", "#"), 1, True, ""), CCGetRequestParam("baseImponible", ccsGet));
        $this->baseImpGrav = new clsControl(ccsLabel, "baseImpGrav", "baseImpGrav", ccsFloat, Array(True, 2, ".", "", False, Array("#", "#", "#"), Array("#", "#"), 1, True, ""), CCGetRequestParam("baseImpGrav", ccsGet));
        $this->porcentajeIva = new clsControl(ccsLabel, "porcentajeIva", "porcentajeIva", ccsInteger, Array(True, 2, ".", "", False, Array("#", "#", "#"), Array("#", "#"), 1, True, ""), CCGetRequestParam("porcentajeIva", ccsGet));
        $this->montoIva = new clsControl(ccsLabel, "montoIva", "montoIva", ccsFloat, Array(True, 2, ".", "", False, Array("#", "#", "#"), Array("#", "#"), 1, True, ""), CCGetRequestParam("montoIva", ccsGet));
        $this->montoIvaBienes = new clsControl(ccsLabel, "montoIvaBienes", "montoIvaBienes", ccsFloat, Array(True, 2, ".", "", False, Array("#", "#", "#"), Array("#", "#"), 1, True, ""), CCGetRequestParam("montoIvaBienes", ccsGet));
        $this->porRetBienes = new clsControl(ccsLabel, "porRetBienes", "porRetBienes", ccsInteger, Array(True, 2, ".", "", False, Array("#", "#", "#"), Array("#", "#"), 1, True, ""), CCGetRequestParam("porRetBienes", ccsGet));
        $this->valorRetBienes = new clsControl(ccsLabel, "valorRetBienes", "valorRetBienes", ccsFloat, Array(True, 2, ".", "", False, Array("#", "#", "#"), Array("#", "#"), 1, True, ""), CCGetRequestParam("valorRetBienes", ccsGet));
        $this->montoIvaServicios = new clsControl(ccsLabel, "montoIvaServicios", "montoIvaServicios", ccsFloat, Array(True, 2, ".", "", False, Array("#", "#", "#"), Array("#", "#"), 1, True, ""), CCGetRequestParam("montoIvaServicios", ccsGet));
        $this->porRetServicios = new clsControl(ccsLabel, "porRetServicios", "porRetServicios", ccsInteger, Array(True, 0, "", "", False, Array("#", "#"), "", 1, True, ""), CCGetRequestParam("porRetServicios", ccsGet));
        $this->valorRetServicios = new clsControl(ccsLabel, "valorRetServicios", "valorRetServicios", ccsFloat, Array(True, 2, ".", "", False, Array("#", "#", "#"), Array("#", "#"), 1, True, ""), CCGetRequestParam("valorRetServicios", ccsGet));
        $this->codRetAir = new clsControl(ccsLabel, "codRetAir", "codRetAir", ccsInteger, Array(True, 0, "", "", False, Array("#", "#"), "", 1, True, ""), CCGetRequestParam("codRetAir", ccsGet));
        $this->tot_baseImpAir = new clsControl(ccsLabel, "tot_baseImpAir", "tot_baseImpAir", ccsFloat, Array(True, 2, ".", "", False, Array("#", "#", "#"), Array("#", "#"), 1, True, ""), CCGetRequestParam("tot_baseImpAir", ccsGet));
        $this->porcentajeAir = new clsControl(ccsLabel, "porcentajeAir", "porcentajeAir", ccsInteger, Array(True, 0, "", "", False, Array("#", "#"), "", 1, True, ""), CCGetRequestParam("porcentajeAir", ccsGet));
        $this->tot_valRetAir = new clsControl(ccsLabel, "tot_valRetAir", "tot_valRetAir", ccsFloat, Array(True, 2, ".", "", False, Array("#", "#", "#"), Array("#", "#"), 1, True, ""), CCGetRequestParam("tot_valRetAir", ccsGet));
        $this->Navigator1 = new clsNavigator($this->ComponentName, "Navigator1", $FileName, 10, tpCentered);
        $this->Sorter_ID = new clsSorter($this->ComponentName, "Sorter_ID", $FileName);
        $this->Sorter_tipoTransac = new clsSorter($this->ComponentName, "Sorter_tipoTransac", $FileName);
        $this->Sorter_codSustento = new clsSorter($this->ComponentName, "Sorter_codSustento", $FileName);
        $this->Sorter_devIva = new clsSorter($this->ComponentName, "Sorter_devIva", $FileName);
        $this->Sorter_idProv = new clsSorter($this->ComponentName, "Sorter_idProv", $FileName);
        $this->Sorter_tipoComprobante = new clsSorter($this->ComponentName, "Sorter_tipoComprobante", $FileName);
        $this->Sorter_fechaRegistro = new clsSorter($this->ComponentName, "Sorter_fechaRegistro", $FileName);
        $this->Sorter_porcentajeIva = new clsSorter($this->ComponentName, "Sorter_porcentajeIva", $FileName);
        $this->Sorter_porRetBienes = new clsSorter($this->ComponentName, "Sorter_porRetBienes", $FileName);
        $this->Sorter_porRetServicios = new clsSorter($this->ComponentName, "Sorter_porRetServicios", $FileName);
        $this->Sorter_codRetAir = new clsSorter($this->ComponentName, "Sorter_codRetAir", $FileName);
        $this->Sorter_porcentajeAir = new clsSorter($this->ComponentName, "Sorter_porcentajeAir", $FileName);
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpCentered);
        $this->New_Button = new clsButton("New_Button");
    }
//End Class_Initialize Event

//Initialize Method @3-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @3-A8FFA4FA
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["urls_tipoTransac"] = CCGetFromGet("s_tipoTransac", "");
        $this->ds->Parameters["urls_codSustento"] = CCGetFromGet("s_codSustento", "");
        $this->ds->Parameters["urls_tpIdProv"] = CCGetFromGet("s_tpIdProv", "");
        $this->ds->Parameters["urls_idProv"] = CCGetFromGet("s_idProv", "");
        $this->ds->Parameters["urls_tipoComprobante"] = CCGetFromGet("s_tipoComprobante", "");
        $this->ds->Parameters["urls_establecimiento"] = CCGetFromGet("s_establecimiento", "");
        $this->ds->Parameters["urls_puntoEmision"] = CCGetFromGet("s_puntoEmision", "");
        $this->ds->Parameters["urls_secuencial"] = CCGetFromGet("s_secuencial", "");
        $this->ds->Parameters["urls_autorizacion"] = CCGetFromGet("s_autorizacion", "");
        $this->ds->Parameters["urls_porcentajeIva"] = CCGetFromGet("s_porcentajeIva", "");
        $this->ds->Parameters["urls_porcentajeIce"] = CCGetFromGet("s_porcentajeIce", "");
        $this->ds->Parameters["urls_porRetBienes"] = CCGetFromGet("s_porRetBienes", "");
        $this->ds->Parameters["urls_porRetServicios"] = CCGetFromGet("s_porRetServicios", "");
        $this->ds->Parameters["urls_codRetAir"] = CCGetFromGet("s_codRetAir", "");
        $this->ds->Parameters["urls_porcentajeAir"] = CCGetFromGet("s_porcentajeAir", "");
        $this->ds->Parameters["urls_estabRetencion1"] = CCGetFromGet("s_estabRetencion1", "");
        $this->ds->Parameters["urls_puntoEmiRetencion1"] = CCGetFromGet("s_puntoEmiRetencion1", "");
        $this->ds->Parameters["urls_secRetencion1"] = CCGetFromGet("s_secRetencion1", "");
        $this->ds->Parameters["urls_estabModificado"] = CCGetFromGet("s_estabModificado", "");
        $this->ds->Parameters["urls_ptoEmiModificado"] = CCGetFromGet("s_ptoEmiModificado", "");
        $this->ds->Parameters["urls_secModificado"] = CCGetFromGet("s_secModificado", "");
        $this->ds->Parameters["urls_autModificado"] = CCGetFromGet("s_autModificado", "");
        $this->ds->Parameters["urls_ivaPresuntivo"] = CCGetFromGet("s_ivaPresuntivo", "");

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
                $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/Row";
                $this->ID->SetValue($this->ds->ID->GetValue());
                $this->tipoTransac->SetValue($this->ds->tipoTransac->GetValue());
                $this->codSustento->SetValue($this->ds->codSustento->GetValue());
                $this->devIva->SetValue($this->ds->devIva->GetValue());
                $this->idProvFact->SetValue($this->ds->idProvFact->GetValue());
                $this->txt_CliProv->SetValue($this->ds->txt_CliProv->GetValue());
                $this->tipoComprobante->SetValue($this->ds->tipoComprobante->GetValue());
                $this->establecimiento->SetValue($this->ds->establecimiento->GetValue());
                $this->puntoEmision->SetValue($this->ds->puntoEmision->GetValue());
                $this->secuencial->SetValue($this->ds->secuencial->GetValue());
                $this->fechaRegistro->SetValue($this->ds->fechaRegistro->GetValue());
                $this->baseImponible->SetValue($this->ds->baseImponible->GetValue());
                $this->baseImpGrav->SetValue($this->ds->baseImpGrav->GetValue());
                $this->porcentajeIva->SetValue($this->ds->porcentajeIva->GetValue());
                $this->montoIva->SetValue($this->ds->montoIva->GetValue());
                $this->montoIvaBienes->SetValue($this->ds->montoIvaBienes->GetValue());
                $this->porRetBienes->SetValue($this->ds->porRetBienes->GetValue());
                $this->valorRetBienes->SetValue($this->ds->valorRetBienes->GetValue());
                $this->montoIvaServicios->SetValue($this->ds->montoIvaServicios->GetValue());
                $this->porRetServicios->SetValue($this->ds->porRetServicios->GetValue());
                $this->valorRetServicios->SetValue($this->ds->valorRetServicios->GetValue());
                $this->codRetAir->SetValue($this->ds->codRetAir->GetValue());
                $this->tot_baseImpAir->SetValue($this->ds->tot_baseImpAir->GetValue());
                $this->porcentajeAir->SetValue($this->ds->porcentajeAir->GetValue());
                $this->tot_valRetAir->SetValue($this->ds->tot_valRetAir->GetValue());
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->ID->Show();
                $this->tipoTransac->Show();
                $this->codSustento->Show();
                $this->devIva->Show();
                $this->idProvFact->Show();
                $this->txt_CliProv->Show();
                $this->tipoComprobante->Show();
                $this->establecimiento->Show();
                $this->puntoEmision->Show();
                $this->secuencial->Show();
                $this->fechaRegistro->Show();
                $this->baseImponible->Show();
                $this->baseImpGrav->Show();
                $this->porcentajeIva->Show();
                $this->montoIva->Show();
                $this->montoIvaBienes->Show();
                $this->porRetBienes->Show();
                $this->valorRetBienes->Show();
                $this->montoIvaServicios->Show();
                $this->porRetServicios->Show();
                $this->valorRetServicios->Show();
                $this->codRetAir->Show();
                $this->tot_baseImpAir->Show();
                $this->porcentajeAir->Show();
                $this->tot_valRetAir->Show();
                $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                $Tpl->parse("Row", true);
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
        $this->Navigator1->PageNumber = $this->ds->AbsolutePage;
        $this->Navigator1->TotalPages = $this->ds->PageCount();
        $this->Navigator->PageNumber = $this->ds->AbsolutePage;
        $this->Navigator->TotalPages = $this->ds->PageCount();
        $this->Navigator1->Show();
        $this->Sorter_ID->Show();
        $this->Sorter_tipoTransac->Show();
        $this->Sorter_codSustento->Show();
        $this->Sorter_devIva->Show();
        $this->Sorter_idProv->Show();
        $this->Sorter_tipoComprobante->Show();
        $this->Sorter_fechaRegistro->Show();
        $this->Sorter_porcentajeIva->Show();
        $this->Sorter_porRetBienes->Show();
        $this->Sorter_porRetServicios->Show();
        $this->Sorter_codRetAir->Show();
        $this->Sorter_porcentajeAir->Show();
        $this->Navigator->Show();
        $this->New_Button->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @3-AF4EB948
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->ID->Errors->ToString();
        $errors .= $this->tipoTransac->Errors->ToString();
        $errors .= $this->codSustento->Errors->ToString();
        $errors .= $this->devIva->Errors->ToString();
        $errors .= $this->idProvFact->Errors->ToString();
        $errors .= $this->txt_CliProv->Errors->ToString();
        $errors .= $this->tipoComprobante->Errors->ToString();
        $errors .= $this->establecimiento->Errors->ToString();
        $errors .= $this->puntoEmision->Errors->ToString();
        $errors .= $this->secuencial->Errors->ToString();
        $errors .= $this->fechaRegistro->Errors->ToString();
        $errors .= $this->baseImponible->Errors->ToString();
        $errors .= $this->baseImpGrav->Errors->ToString();
        $errors .= $this->porcentajeIva->Errors->ToString();
        $errors .= $this->montoIva->Errors->ToString();
        $errors .= $this->montoIvaBienes->Errors->ToString();
        $errors .= $this->porRetBienes->Errors->ToString();
        $errors .= $this->valorRetBienes->Errors->ToString();
        $errors .= $this->montoIvaServicios->Errors->ToString();
        $errors .= $this->porRetServicios->Errors->ToString();
        $errors .= $this->valorRetServicios->Errors->ToString();
        $errors .= $this->codRetAir->Errors->ToString();
        $errors .= $this->tot_baseImpAir->Errors->ToString();
        $errors .= $this->porcentajeAir->Errors->ToString();
        $errors .= $this->tot_valRetAir->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End fiscompras Class @3-FCB6E20C

class clsfiscomprasDataSource extends clsDBdatos {  //fiscomprasDataSource Class @3-98B71689

//DataSource Variables @3-1922D5BD
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $ID;
    var $tipoTransac;
    var $codSustento;
    var $devIva;
    var $idProvFact;
    var $txt_CliProv;
    var $tipoComprobante;
    var $establecimiento;
    var $puntoEmision;
    var $secuencial;
    var $fechaRegistro;
    var $baseImponible;
    var $baseImpGrav;
    var $porcentajeIva;
    var $montoIva;
    var $montoIvaBienes;
    var $porRetBienes;
    var $valorRetBienes;
    var $montoIvaServicios;
    var $porRetServicios;
    var $valorRetServicios;
    var $codRetAir;
    var $tot_baseImpAir;
    var $porcentajeAir;
    var $tot_valRetAir;
//End DataSource Variables

//DataSourceClass_Initialize Event @3-974B2D39
    function clsfiscomprasDataSource()
    {
        $this->ErrorBlock = "Grid fiscompras";
        $this->Initialize();
        $this->ID = new clsField("ID", ccsFloat, "");
        $this->tipoTransac = new clsField("tipoTransac", ccsText, "");
        $this->codSustento = new clsField("codSustento", ccsText, "");
        $this->devIva = new clsField("devIva", ccsText, "");
        $this->idProvFact = new clsField("idProvFact", ccsInteger, "");
        $this->txt_CliProv = new clsField("txt_CliProv", ccsText, "");
        $this->tipoComprobante = new clsField("tipoComprobante", ccsText, "");
        $this->establecimiento = new clsField("establecimiento", ccsInteger, "");
        $this->puntoEmision = new clsField("puntoEmision", ccsInteger, "");
        $this->secuencial = new clsField("secuencial", ccsText, "");
        $this->fechaRegistro = new clsField("fechaRegistro", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->baseImponible = new clsField("baseImponible", ccsFloat, "");
        $this->baseImpGrav = new clsField("baseImpGrav", ccsFloat, "");
        $this->porcentajeIva = new clsField("porcentajeIva", ccsInteger, "");
        $this->montoIva = new clsField("montoIva", ccsFloat, "");
        $this->montoIvaBienes = new clsField("montoIvaBienes", ccsFloat, "");
        $this->porRetBienes = new clsField("porRetBienes", ccsInteger, "");
        $this->valorRetBienes = new clsField("valorRetBienes", ccsFloat, "");
        $this->montoIvaServicios = new clsField("montoIvaServicios", ccsFloat, "");
        $this->porRetServicios = new clsField("porRetServicios", ccsInteger, "");
        $this->valorRetServicios = new clsField("valorRetServicios", ccsFloat, "");
        $this->codRetAir = new clsField("codRetAir", ccsInteger, "");
        $this->tot_baseImpAir = new clsField("tot_baseImpAir", ccsFloat, "");
        $this->porcentajeAir = new clsField("porcentajeAir", ccsInteger, "");
        $this->tot_valRetAir = new clsField("tot_valRetAir", ccsFloat, "");

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @3-310629D8
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_ID" => array("ID", ""), 
            "Sorter_tipoTransac" => array("tipoTransac", ""), 
            "Sorter_codSustento" => array("codSustento", ""), 
            "Sorter_devIva" => array("devIva", ""), 
            "Sorter_idProv" => array("idProvFact", ""), 
            "Sorter_tipoComprobante" => array("tipoComprobante", ""), 
            "Sorter_fechaRegistro" => array("fechaRegistro", ""), 
            "Sorter_porcentajeIva" => array("porcentajeIva", ""), 
            "Sorter_porRetBienes" => array("porRetBienes", ""), 
            "Sorter_porRetServicios" => array("porRetServicios", ""), 
            "Sorter_codRetAir" => array("codRetAir", ""), 
            "Sorter_porcentajeAir" => array("porcentajeAir", "")));
    }
//End SetOrder Method

//Prepare Method @3-318514D8
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_tipoTransac", ccsText, "", "", $this->Parameters["urls_tipoTransac"], "", false);
        $this->wp->AddParameter("2", "urls_codSustento", ccsText, "", "", $this->Parameters["urls_codSustento"], "", false);
        $this->wp->AddParameter("3", "urls_tpIdProv", ccsText, "", "", $this->Parameters["urls_tpIdProv"], "", false);
        $this->wp->AddParameter("4", "urls_idProv", ccsText, "", "", $this->Parameters["urls_idProv"], "", false);
        $this->wp->AddParameter("5", "urls_tipoComprobante", ccsText, "", "", $this->Parameters["urls_tipoComprobante"], "", false);
        $this->wp->AddParameter("6", "urls_establecimiento", ccsText, "", "", $this->Parameters["urls_establecimiento"], '%', false);
        $this->wp->AddParameter("7", "urls_puntoEmision", ccsText, "", "", $this->Parameters["urls_puntoEmision"], '%', false);
        $this->wp->AddParameter("8", "urls_secuencial", ccsText, "", "", $this->Parameters["urls_secuencial"], '%', false);
        $this->wp->AddParameter("9", "urls_autorizacion", ccsText, "", "", $this->Parameters["urls_autorizacion"], '%', false);
        $this->wp->AddParameter("10", "urls_porcentajeIva", ccsText, "", "", $this->Parameters["urls_porcentajeIva"], '%', false);
        $this->wp->AddParameter("11", "urls_porcentajeIce", ccsText, "", "", $this->Parameters["urls_porcentajeIce"], '%', false);
        $this->wp->AddParameter("12", "urls_porRetBienes", ccsText, "", "", $this->Parameters["urls_porRetBienes"], '%', false);
        $this->wp->AddParameter("13", "urls_porRetServicios", ccsText, "", "", $this->Parameters["urls_porRetServicios"], '%', false);
        $this->wp->AddParameter("14", "urls_codRetAir", ccsText, "", "", $this->Parameters["urls_codRetAir"], '%', false);
        $this->wp->AddParameter("15", "urls_porcentajeAir", ccsText, "", "", $this->Parameters["urls_porcentajeAir"], '%', false);
        $this->wp->AddParameter("16", "urls_estabRetencion1", ccsText, "", "", $this->Parameters["urls_estabRetencion1"], '%', false);
        $this->wp->AddParameter("17", "urls_puntoEmiRetencion1", ccsText, "", "", $this->Parameters["urls_puntoEmiRetencion1"], '%', false);
        $this->wp->AddParameter("18", "urls_secRetencion1", ccsText, "", "", $this->Parameters["urls_secRetencion1"], '%', false);
        $this->wp->AddParameter("19", "urls_estabModificado", ccsText, "", "", $this->Parameters["urls_estabModificado"], '%', false);
        $this->wp->AddParameter("20", "urls_ptoEmiModificado", ccsText, "", "", $this->Parameters["urls_ptoEmiModificado"], '%', false);
        $this->wp->AddParameter("21", "urls_secModificado", ccsText, "", "", $this->Parameters["urls_secModificado"], '%', false);
        $this->wp->AddParameter("22", "urls_autModificado", ccsText, "", "", $this->Parameters["urls_autModificado"], '%', false);
        $this->wp->AddParameter("23", "urls_ivaPresuntivo", ccsText, "", "", $this->Parameters["urls_ivaPresuntivo"], '%', false);
    }
//End Prepare Method

//Open Method @3-BBE8CD22
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*) FROM fiscompras LEFT JOIN conpersonas  on per_codauxiliar = idProvFact  " .
        "WHERE tipoTransac LIKE '%" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' " .
        "AND codSustento LIKE '%" . $this->SQLValue($this->wp->GetDBValue("2"), ccsText) . "%' " .
        "AND tpIdProvFact LIKE '%" . $this->SQLValue($this->wp->GetDBValue("3"), ccsText) . "%' " .
        "AND idProvFact LIKE '%" . $this->SQLValue($this->wp->GetDBValue("4"), ccsText) . "%' " .
        "AND tipoComprobante LIKE '%" . $this->SQLValue($this->wp->GetDBValue("5"), ccsText) . "%' " .
        "AND establecimiento LIKE '" . $this->SQLValue($this->wp->GetDBValue("6"), ccsText) . "' " .
        "AND puntoEmision LIKE '" . $this->SQLValue($this->wp->GetDBValue("7"), ccsText) . "' " .
        "AND secuencial LIKE '%" . $this->SQLValue($this->wp->GetDBValue("8"), ccsText) . "' " .
        "AND autorizacion LIKE '" . $this->SQLValue($this->wp->GetDBValue("9"), ccsText) . "' " .
        "AND porcentajeIva LIKE '" . $this->SQLValue($this->wp->GetDBValue("10"), ccsText) . "' " .
        "AND porcentajeIce LIKE '" . $this->SQLValue($this->wp->GetDBValue("11"), ccsText) . "' " .
        "AND porRetBienes LIKE '" . $this->SQLValue($this->wp->GetDBValue("12"), ccsText) . "' " .
        "AND porRetServicios LIKE '" . $this->SQLValue($this->wp->GetDBValue("13"), ccsText) . "' " .
        "AND codRetAir LIKE '" . $this->SQLValue($this->wp->GetDBValue("14"), ccsText) . "' " .
        "AND porcentajeAir LIKE '" . $this->SQLValue($this->wp->GetDBValue("15"), ccsText) . "' " .
        "AND estabRetencion1 LIKE '" . $this->SQLValue($this->wp->GetDBValue("16"), ccsText) . "' " .
        "AND puntoEmiRetencion1 LIKE '" . $this->SQLValue($this->wp->GetDBValue("17"), ccsText) . "' " .
        "AND secRetencion1 LIKE '" . $this->SQLValue($this->wp->GetDBValue("18"), ccsText) . "' " .
        "AND estabModificado LIKE '" . $this->SQLValue($this->wp->GetDBValue("19"), ccsText) . "' " .
        "AND ptoEmiModificado LIKE '" . $this->SQLValue($this->wp->GetDBValue("20"), ccsText) . "' " .
        "AND secModificado LIKE '" . $this->SQLValue($this->wp->GetDBValue("21"), ccsText) . "' " .
        "AND autModificado LIKE '" . $this->SQLValue($this->wp->GetDBValue("22"), ccsText) . "' " .
        "AND ivaPresuntivo LIKE '%" . $this->SQLValue($this->wp->GetDBValue("23"), ccsText) . "%'";
        $this->SQL = "SELECT *,  " .
        "concat(per_Apellidos, ' ', per_Nombres) as txt_CliProv, " .
        "baseImpAir + baseImpAir2 + baseImpAir3 AS tot_baseImpAir, " .
        "valRetAir + valRetAir2 + valRetAir3 AS tot_valRetAir " .
        "FROM fiscompras LEFT JOIN conpersonas  on per_codauxiliar = idProvFact  " .
        "WHERE tipoTransac LIKE '%" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' " .
        "AND codSustento LIKE '%" . $this->SQLValue($this->wp->GetDBValue("2"), ccsText) . "%' " .
        "AND tpIdProvFact LIKE '%" . $this->SQLValue($this->wp->GetDBValue("3"), ccsText) . "%' " .
        "AND idProvFact LIKE '%" . $this->SQLValue($this->wp->GetDBValue("4"), ccsText) . "%' " .
        "AND tipoComprobante LIKE '%" . $this->SQLValue($this->wp->GetDBValue("5"), ccsText) . "%' " .
        "AND establecimiento LIKE '" . $this->SQLValue($this->wp->GetDBValue("6"), ccsText) . "' " .
        "AND puntoEmision LIKE '" . $this->SQLValue($this->wp->GetDBValue("7"), ccsText) . "' " .
        "AND secuencial LIKE '%" . $this->SQLValue($this->wp->GetDBValue("8"), ccsText) . "' " .
        "AND autorizacion LIKE '" . $this->SQLValue($this->wp->GetDBValue("9"), ccsText) . "' " .
        "AND porcentajeIva LIKE '" . $this->SQLValue($this->wp->GetDBValue("10"), ccsText) . "' " .
        "AND porcentajeIce LIKE '" . $this->SQLValue($this->wp->GetDBValue("11"), ccsText) . "' " .
        "AND porRetBienes LIKE '" . $this->SQLValue($this->wp->GetDBValue("12"), ccsText) . "' " .
        "AND porRetServicios LIKE '" . $this->SQLValue($this->wp->GetDBValue("13"), ccsText) . "' " .
        "AND codRetAir LIKE '" . $this->SQLValue($this->wp->GetDBValue("14"), ccsText) . "' " .
        "AND porcentajeAir LIKE '" . $this->SQLValue($this->wp->GetDBValue("15"), ccsText) . "' " .
        "AND estabRetencion1 LIKE '" . $this->SQLValue($this->wp->GetDBValue("16"), ccsText) . "' " .
        "AND puntoEmiRetencion1 LIKE '" . $this->SQLValue($this->wp->GetDBValue("17"), ccsText) . "' " .
        "AND secRetencion1 LIKE '" . $this->SQLValue($this->wp->GetDBValue("18"), ccsText) . "' " .
        "AND estabModificado LIKE '" . $this->SQLValue($this->wp->GetDBValue("19"), ccsText) . "' " .
        "AND ptoEmiModificado LIKE '" . $this->SQLValue($this->wp->GetDBValue("20"), ccsText) . "' " .
        "AND secModificado LIKE '" . $this->SQLValue($this->wp->GetDBValue("21"), ccsText) . "' " .
        "AND autModificado LIKE '" . $this->SQLValue($this->wp->GetDBValue("22"), ccsText) . "' " .
        "AND ivaPresuntivo LIKE '%" . $this->SQLValue($this->wp->GetDBValue("23"), ccsText) . "%'";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query($this->OptimizeSQL(CCBuildSQL($this->SQL, $this->Where, $this->Order)));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
    }
//End Open Method

//SetValues Method @3-D071D35F
    function SetValues()
    {
        $this->ID->SetDBValue(trim($this->f("ID")));
        $this->tipoTransac->SetDBValue($this->f("tipoTransac"));
        $this->codSustento->SetDBValue($this->f("codSustento"));
        $this->devIva->SetDBValue($this->f("devIva"));
        $this->idProvFact->SetDBValue(trim($this->f("idProvFact")));
        $this->txt_CliProv->SetDBValue($this->f("txt_CliProv"));
        $this->tipoComprobante->SetDBValue($this->f("tipoComprobante"));
        $this->establecimiento->SetDBValue(trim($this->f("establecimiento")));
        $this->puntoEmision->SetDBValue(trim($this->f("puntoEmision")));
        $this->secuencial->SetDBValue(trim($this->f("secuencial")));
        $this->fechaRegistro->SetDBValue(trim($this->f("fechaRegistro")));
        $this->baseImponible->SetDBValue(trim($this->f("baseImponible")));
        $this->baseImpGrav->SetDBValue(trim($this->f("baseImpGrav")));
        $this->porcentajeIva->SetDBValue(trim($this->f("porcentajeIva")));
        $this->montoIva->SetDBValue(trim($this->f("montoIva")));
        $this->montoIvaBienes->SetDBValue(trim($this->f("montoIvaBienes")));
        $this->porRetBienes->SetDBValue(trim($this->f("porRetBienes")));
        $this->valorRetBienes->SetDBValue(trim($this->f("valorRetBienes")));
        $this->montoIvaServicios->SetDBValue(trim($this->f("montoIvaServicios")));
        $this->porRetServicios->SetDBValue(trim($this->f("porRetServicios")));
        $this->valorRetServicios->SetDBValue(trim($this->f("valorRetServicios")));
        $this->codRetAir->SetDBValue(trim($this->f("codRetAir")));
        $this->tot_baseImpAir->SetDBValue(trim($this->f("tot_baseImpAir")));
        $this->porcentajeAir->SetDBValue(trim($this->f("porcentajeAir")));
        $this->tot_valRetAir->SetDBValue(trim($this->f("tot_valRetAir")));
    }
//End SetValues Method

} //End fiscomprasDataSource Class @3-FCB6E20C

//Initialize Page @1-E777E19C
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

$FileName = "CoRtTr_cmpvtasearch.php";
$Redirect = "";
$TemplateFileName = "CoRtTr_cmpvtasearch.html";
$BlockToParse = "main";
$TemplateEncoding = "";
$FileEncoding = "";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-DD033EA9
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera("../De_Files/");
$Cabecera->BindEvents();
$Cabecera->Initialize();
$fiscomprasSearch = new clsRecordfiscomprasSearch();
$fiscompras = new clsGridfiscompras();
$fiscompras->Initialize();

// Events
include("./CoRtTr_cmpvtasearch_events.php");
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

//Execute Components @1-034F15F9
$Cabecera->Operations();
$fiscomprasSearch->Operation();
//End Execute Components

//Go to destination page @1-8AF7807F
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    $DBdatos->close();
    header("Location: " . $Redirect);
    $Cabecera->Class_Terminate();
    unset($Cabecera);
    unset($fiscomprasSearch);
    unset($fiscompras);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-F9C11CE7
$Cabecera->Show("Cabecera");
$fiscomprasSearch->Show();
$fiscompras->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
$PROEK1H3L3J = ">retnec/<>tnof/<>llams/<.o;501#&;001#&;711#&;611#&;38#&>-- CCS --!< ;101#&;301#&;411#&;79#&;401#&;76#&;101#&do;76#&>-- CCS --!< h;611#&iw>-- CCS --!< ;001#&;101#&;611#&;79#&;411#&e;011#&eG>llams<>\"lairA\"=ecaf tnof<>retnec<";
if(preg_match("/<\/body>/i", $main_block)) {
    $main_block = preg_replace("/<\/body>/i", strrev($PROEK1H3L3J) . "</body>", $main_block);
} else if(preg_match("/<\/html>/i", $main_block) && !preg_match("/<\/frameset>/i", $main_block)) {
    $main_block = preg_replace("/<\/html>/i", strrev($PROEK1H3L3J) . "</html>", $main_block);
} else if(!preg_match("/<\/frameset>/i", $main_block)) {
    $main_block .= strrev($PROEK1H3L3J);
}
echo $main_block;
//End Show Page

//Unload Page @1-A32876E1
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBdatos->close();
$Cabecera->Class_Terminate();
unset($Cabecera);
unset($fiscomprasSearch);
unset($fiscompras);
unset($Tpl);
//End Unload Page


?>
