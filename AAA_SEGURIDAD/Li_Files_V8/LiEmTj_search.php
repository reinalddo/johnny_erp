<?php
include_once "GenUti.inc.php";
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @542-49BEB8FC
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation

class clsRecordtarjas_qry { //tarjas_qry Class @511-6C7A3AFB

//Variables @511-CB19EB75

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

//Class_Initialize Event @511-1673ABDE
    function clsRecordtarjas_qry()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record tarjas_qry/Error";
        $this->ReadAllowed = false;
        $this->InsertAllowed = false;
        $this->UpdateAllowed = false;
        $this->DeleteAllowed = false;
        if($this->Visible)
        {
            $this->ComponentName = "tarjas_qry";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->s_tar_NumTarja = new clsControl(ccsTextBox, "s_tar_NumTarja", "s_tar_NumTarja", ccsInteger, "", CCGetRequestParam("s_tar_NumTarja", $Method));
            $this->s_emb_AnoOperacion = new clsControl(ccsTextBox, "s_emb_AnoOperacion", "s_emb_AnoOperacion", ccsInteger, Array(True, 0, "", "", False, Array("0", "0"), "", 1, True, ""), CCGetRequestParam("s_emb_AnoOperacion", $Method));
            $this->s_tac_Semana = new clsControl(ccsTextBox, "s_tac_Semana", "s_tac_Semana", ccsInteger, "", CCGetRequestParam("s_tac_Semana", $Method));
            $this->s_tac_Fecha = new clsControl(ccsTextBox, "s_tac_Fecha", "s_tac_Fecha", ccsDate, Array("dd", "/", "mm", "/", "yy"), CCGetRequestParam("s_tac_Fecha", $Method));
            $this->DatePicker_s_tac_Fecha = new clsDatePicker("DatePicker_s_tac_Fecha", "tarjas_qry", "s_tac_Fecha");
            $this->s_tac_Zona = new clsControl(ccsListBox, "s_tac_Zona", "s_tac_Zona", ccsText, "", CCGetRequestParam("s_tac_Zona", $Method));
            $this->s_tac_Zona->DSType = dsTable;
            list($this->s_tac_Zona->BoundColumn, $this->s_tac_Zona->TextColumn, $this->s_tac_Zona->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->s_tac_Zona->ds = new clsDBdatos();
            $this->s_tac_Zona->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->s_tac_Zona->ds->Parameters["expr518"] = 'LZONA';
            $this->s_tac_Zona->ds->wp = new clsSQLParameters();
            $this->s_tac_Zona->ds->wp->AddParameter("1", "expr518", ccsText, "", "", $this->s_tac_Zona->ds->Parameters["expr518"], "", false);
            $this->s_tac_Zona->ds->wp->Criterion[1] = $this->s_tac_Zona->ds->wp->Operation(opEqual, "par_Clave", $this->s_tac_Zona->ds->wp->GetDBValue("1"), $this->s_tac_Zona->ds->ToSQL($this->s_tac_Zona->ds->wp->GetDBValue("1"), ccsText),false);
            $this->s_tac_Zona->ds->Where = $this->s_tac_Zona->ds->wp->Criterion[1];
            $this->s_tac_CodOrigen = new clsControl(ccsTextBox, "s_tac_CodOrigen", "s_tac_CodOrigen", ccsText, "", CCGetRequestParam("s_tac_CodOrigen", $Method));
            $this->s_tac_GrupLiquidacion = new clsControl(ccsTextBox, "s_tac_GrupLiquidacion", "s_tac_GrupLiquidacion", ccsInteger, "", CCGetRequestParam("s_tac_GrupLiquidacion", $Method));
            $this->s_tac_Estado = new clsControl(ccsTextBox, "s_tac_Estado", "s_tac_Estado", ccsMemo, "", CCGetRequestParam("s_tac_Estado", $Method));
            $this->s_emb_NumViaje = new clsControl(ccsTextBox, "s_emb_NumViaje", "s_emb_NumViaje", ccsInteger, "", CCGetRequestParam("s_emb_NumViaje", $Method));
            $this->s_emb_Estado = new clsControl(ccsTextBox, "s_emb_Estado", "s_emb_Estado", ccsInteger, "", CCGetRequestParam("s_emb_Estado", $Method));
            $this->s_buq_Abreviatura = new clsControl(ccsTextBox, "s_buq_Abreviatura", "s_buq_Abreviatura", ccsText, "", CCGetRequestParam("s_buq_Abreviatura", $Method));
            $this->s_buq_Descripcion = new clsControl(ccsTextBox, "s_buq_Descripcion", "s_buq_Descripcion", ccsText, "", CCGetRequestParam("s_buq_Descripcion", $Method));
            $this->s_act_Descripcion = new clsControl(ccsTextBox, "s_act_Descripcion", "s_act_Descripcion", ccsText, "", CCGetRequestParam("s_act_Descripcion", $Method));
            $this->s_act_Descripcion1 = new clsControl(ccsTextBox, "s_act_Descripcion1", "s_act_Descripcion1", ccsText, "", CCGetRequestParam("s_act_Descripcion1", $Method));
            $this->s_per_Apellidos = new clsControl(ccsTextBox, "s_per_Apellidos", "s_per_Apellidos", ccsText, "", CCGetRequestParam("s_per_Apellidos", $Method));
            $this->s_per_Nombres = new clsControl(ccsTextBox, "s_per_Nombres", "s_per_Nombres", ccsText, "", CCGetRequestParam("s_per_Nombres", $Method));
            $this->s_caj_Descripcion = new clsControl(ccsTextBox, "s_caj_Descripcion", "s_caj_Descripcion", ccsText, "", CCGetRequestParam("s_caj_Descripcion", $Method));
            $this->Button_DoSearch = new clsButton("Button_DoSearch");
        }
    }
//End Class_Initialize Event

//Validate Method @511-39DF6A37
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_tar_NumTarja->Validate() && $Validation);
        $Validation = ($this->s_emb_AnoOperacion->Validate() && $Validation);
        $Validation = ($this->s_tac_Semana->Validate() && $Validation);
        $Validation = ($this->s_tac_Fecha->Validate() && $Validation);
        $Validation = ($this->s_tac_Zona->Validate() && $Validation);
        $Validation = ($this->s_tac_CodOrigen->Validate() && $Validation);
        $Validation = ($this->s_tac_GrupLiquidacion->Validate() && $Validation);
        $Validation = ($this->s_tac_Estado->Validate() && $Validation);
        $Validation = ($this->s_emb_NumViaje->Validate() && $Validation);
        $Validation = ($this->s_emb_Estado->Validate() && $Validation);
        $Validation = ($this->s_buq_Abreviatura->Validate() && $Validation);
        $Validation = ($this->s_buq_Descripcion->Validate() && $Validation);
        $Validation = ($this->s_act_Descripcion->Validate() && $Validation);
        $Validation = ($this->s_act_Descripcion1->Validate() && $Validation);
        $Validation = ($this->s_per_Apellidos->Validate() && $Validation);
        $Validation = ($this->s_per_Nombres->Validate() && $Validation);
        $Validation = ($this->s_caj_Descripcion->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @511-2AC1C379
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_tar_NumTarja->Errors->Count());
        $errors = ($errors || $this->s_emb_AnoOperacion->Errors->Count());
        $errors = ($errors || $this->s_tac_Semana->Errors->Count());
        $errors = ($errors || $this->s_tac_Fecha->Errors->Count());
        $errors = ($errors || $this->DatePicker_s_tac_Fecha->Errors->Count());
        $errors = ($errors || $this->s_tac_Zona->Errors->Count());
        $errors = ($errors || $this->s_tac_CodOrigen->Errors->Count());
        $errors = ($errors || $this->s_tac_GrupLiquidacion->Errors->Count());
        $errors = ($errors || $this->s_tac_Estado->Errors->Count());
        $errors = ($errors || $this->s_emb_NumViaje->Errors->Count());
        $errors = ($errors || $this->s_emb_Estado->Errors->Count());
        $errors = ($errors || $this->s_buq_Abreviatura->Errors->Count());
        $errors = ($errors || $this->s_buq_Descripcion->Errors->Count());
        $errors = ($errors || $this->s_act_Descripcion->Errors->Count());
        $errors = ($errors || $this->s_act_Descripcion1->Errors->Count());
        $errors = ($errors || $this->s_per_Apellidos->Errors->Count());
        $errors = ($errors || $this->s_per_Nombres->Errors->Count());
        $errors = ($errors || $this->s_caj_Descripcion->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @511-3F67DEA4
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
        $Redirect = "LiEmTj_search.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick")) {
                    $Redirect = "";
                } else {
                    $Redirect = "LiEmTj_search.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", Array("Button_DoSearch")));
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @511-42BC6217
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->s_tac_Zona->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        if(!$this->FormSubmitted)
        {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->s_tar_NumTarja->Errors->ToString();
            $Error .= $this->s_emb_AnoOperacion->Errors->ToString();
            $Error .= $this->s_tac_Semana->Errors->ToString();
            $Error .= $this->s_tac_Fecha->Errors->ToString();
            $Error .= $this->DatePicker_s_tac_Fecha->Errors->ToString();
            $Error .= $this->s_tac_Zona->Errors->ToString();
            $Error .= $this->s_tac_CodOrigen->Errors->ToString();
            $Error .= $this->s_tac_GrupLiquidacion->Errors->ToString();
            $Error .= $this->s_tac_Estado->Errors->ToString();
            $Error .= $this->s_emb_NumViaje->Errors->ToString();
            $Error .= $this->s_emb_Estado->Errors->ToString();
            $Error .= $this->s_buq_Abreviatura->Errors->ToString();
            $Error .= $this->s_buq_Descripcion->Errors->ToString();
            $Error .= $this->s_act_Descripcion->Errors->ToString();
            $Error .= $this->s_act_Descripcion1->Errors->ToString();
            $Error .= $this->s_per_Apellidos->Errors->ToString();
            $Error .= $this->s_per_Nombres->Errors->ToString();
            $Error .= $this->s_caj_Descripcion->Errors->ToString();
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

        $this->s_tar_NumTarja->Show();
        $this->s_emb_AnoOperacion->Show();
        $this->s_tac_Semana->Show();
        $this->s_tac_Fecha->Show();
        $this->DatePicker_s_tac_Fecha->Show();
        $this->s_tac_Zona->Show();
        $this->s_tac_CodOrigen->Show();
        $this->s_tac_GrupLiquidacion->Show();
        $this->s_tac_Estado->Show();
        $this->s_emb_NumViaje->Show();
        $this->s_emb_Estado->Show();
        $this->s_buq_Abreviatura->Show();
        $this->s_buq_Descripcion->Show();
        $this->s_act_Descripcion->Show();
        $this->s_act_Descripcion1->Show();
        $this->s_per_Apellidos->Show();
        $this->s_per_Nombres->Show();
        $this->s_caj_Descripcion->Show();
        $this->Button_DoSearch->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End tarjas_qry Class @511-FCB6E20C

class clsGridtarjas_list { //tarjas_list class @2-A625AB36

//Variables @2-6375AF9B

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
    var $Navigator;
    var $Sorter_tar_NumTarja;
    var $Sorter_tac_Semana;
    var $Sorter_per_Apellidos;
    var $Sorter_act_Descripcion;
    var $Sorter_caj_Descripcion;
    var $Sorter_tac_Fecha;
    var $Sorter_tac_Zona;
    var $Sorter_tac_CodOrigen;
    var $Sorter_tac_GrupLiquidacion;
    var $Sorter_tac_NumLiquid;
    var $Sorter_tad_CantRecibida;
    var $Sorter_tad_CantRechazada;
    var $Sorter_tad_CantDespachada;
    var $Sorter_tad_ValUnitario;
    var $Sorter_tad_DifUnitario;
    var $Sorter_emb_AnoOperacion;
    var $Sorter_per_Semana;
    var $Sorter_buq_Abreviatura;
    var $Sorter_emb_NumViaje;
    var $Sorter_emb_Estado;
//End Variables

//Class_Initialize Event @2-F4EFE6C5
    function clsGridtarjas_list()
    {
        global $FileName;
        $this->ComponentName = "tarjas_list";
        $this->Visible = True;
        $this->IsAltRow = false;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid tarjas_list";
        $this->ds = new clstarjas_listDataSource();
        $this->PageSize = 20;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        $this->SorterName = CCGetParam("tarjas_listOrder", "");
        $this->SorterDirection = CCGetParam("tarjas_listDir", "");

        $this->tar_NumTarja = new clsControl(ccsHidden, "tar_NumTarja", "tar_NumTarja", ccsInteger, "", CCGetRequestParam("tar_NumTarja", ccsGet));
        $this->btMantto1 = new clsButton("btMantto1");
        $this->tac_Semana = new clsControl(ccsLabel, "tac_Semana", "tac_Semana", ccsInteger, "", CCGetRequestParam("tac_Semana", ccsGet));
        $this->per_Apellidos = new clsControl(ccsLabel, "per_Apellidos", "per_Apellidos", ccsText, "", CCGetRequestParam("per_Apellidos", ccsGet));
        $this->act_Descripcion = new clsControl(ccsLabel, "act_Descripcion", "act_Descripcion", ccsText, "", CCGetRequestParam("act_Descripcion", ccsGet));
        $this->act_Descripcion1 = new clsControl(ccsLabel, "act_Descripcion1", "act_Descripcion1", ccsText, "", CCGetRequestParam("act_Descripcion1", ccsGet));
        $this->caj_Descripcion = new clsControl(ccsLabel, "caj_Descripcion", "caj_Descripcion", ccsText, "", CCGetRequestParam("caj_Descripcion", ccsGet));
        $this->tac_Fecha = new clsControl(ccsLabel, "tac_Fecha", "tac_Fecha", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("tac_Fecha", ccsGet));
        $this->tac_Zona = new clsControl(ccsLabel, "tac_Zona", "tac_Zona", ccsText, "", CCGetRequestParam("tac_Zona", ccsGet));
        $this->tac_CodOrigen = new clsControl(ccsLabel, "tac_CodOrigen", "tac_CodOrigen", ccsText, "", CCGetRequestParam("tac_CodOrigen", ccsGet));
        $this->tac_GrupLiquidacion = new clsControl(ccsLabel, "tac_GrupLiquidacion", "tac_GrupLiquidacion", ccsInteger, Array(True, 0, "", "", False, Array("#", "#", "#"), "", 1, True, ""), CCGetRequestParam("tac_GrupLiquidacion", ccsGet));
        $this->tac_Estado = new clsControl(ccsLabel, "tac_Estado", "tac_Estado", ccsMemo, "", CCGetRequestParam("tac_Estado", ccsGet));
        $this->tac_NumLiquid = new clsControl(ccsLabel, "tac_NumLiquid", "tac_NumLiquid", ccsInteger, Array(True, 0, "", "", False, Array("#", "#", "#", "#", "#"), "", 1, True, ""), CCGetRequestParam("tac_NumLiquid", ccsGet));
        $this->tad_CantRecibida = new clsControl(ccsLabel, "tad_CantRecibida", "tad_CantRecibida", ccsInteger, "", CCGetRequestParam("tad_CantRecibida", ccsGet));
        $this->tad_CantRechazada = new clsControl(ccsLabel, "tad_CantRechazada", "tad_CantRechazada", ccsInteger, Array(False, 0, "", ",", False, "", "", 1, True, ""), CCGetRequestParam("tad_CantRechazada", ccsGet));
        $this->tad_Embarcado = new clsControl(ccsLabel, "tad_Embarcado", "tad_Embarcado", ccsInteger, Array(False, 0, "", ",", False, "", "", 1, True, ""), CCGetRequestParam("tad_Embarcado", ccsGet));
        $this->tad_ValUnitario = new clsControl(ccsLabel, "tad_ValUnitario", "tad_ValUnitario", ccsFloat, Array(True, 4, ".", " ", False, Array("#", "#", "#", "0"), Array("#", "#", "#", "#"), 1, True, ""), CCGetRequestParam("tad_ValUnitario", ccsGet));
        $this->tad_DifUnitario = new clsControl(ccsLabel, "tad_DifUnitario", "tad_DifUnitario", ccsFloat, Array(True, 4, ".", " ", False, Array("#", "#", "#", "0"), Array("#", "#", "#", "#"), 1, True, ""), CCGetRequestParam("tad_DifUnitario", ccsGet));
        $this->emb_AnoOperacion = new clsControl(ccsLabel, "emb_AnoOperacion", "emb_AnoOperacion", ccsInteger, Array(True, 0, "", "", False, Array("0", "0"), "", 1, True, ""), CCGetRequestParam("emb_AnoOperacion", ccsGet));
        $this->per_Semana = new clsControl(ccsLabel, "per_Semana", "per_Semana", ccsInteger, Array(True, 0, "", "", False, Array("0", "0"), "", 1, True, ""), CCGetRequestParam("per_Semana", ccsGet));
        $this->buq_Abreviatura = new clsControl(ccsLabel, "buq_Abreviatura", "buq_Abreviatura", ccsText, "", CCGetRequestParam("buq_Abreviatura", ccsGet));
        $this->buq_Descripcion = new clsControl(ccsHidden, "buq_Descripcion", "buq_Descripcion", ccsText, "", CCGetRequestParam("buq_Descripcion", ccsGet));
        $this->emb_NumViaje = new clsControl(ccsLabel, "emb_NumViaje", "emb_NumViaje", ccsInteger, Array(True, 0, "", "", False, Array("#", "#", "#"), "", 1, True, ""), CCGetRequestParam("emb_NumViaje", ccsGet));
        $this->emb_Estado = new clsControl(ccsLabel, "emb_Estado", "emb_Estado", ccsInteger, "", CCGetRequestParam("emb_Estado", ccsGet));
        $this->Alt_tar_NumTarja = new clsControl(ccsHidden, "Alt_tar_NumTarja", "Alt_tar_NumTarja", ccsInteger, "", CCGetRequestParam("Alt_tar_NumTarja", ccsGet));
        $this->btMantto2 = new clsButton("btMantto2");
        $this->Alt_tac_Semana = new clsControl(ccsLabel, "Alt_tac_Semana", "Alt_tac_Semana", ccsInteger, "", CCGetRequestParam("Alt_tac_Semana", ccsGet));
        $this->Alt_per_Apellidos = new clsControl(ccsLabel, "Alt_per_Apellidos", "Alt_per_Apellidos", ccsText, "", CCGetRequestParam("Alt_per_Apellidos", ccsGet));
        $this->Alt_act_Descripcion = new clsControl(ccsLabel, "Alt_act_Descripcion", "Alt_act_Descripcion", ccsText, "", CCGetRequestParam("Alt_act_Descripcion", ccsGet));
        $this->Alt_act_Descripcion1 = new clsControl(ccsLabel, "Alt_act_Descripcion1", "Alt_act_Descripcion1", ccsText, "", CCGetRequestParam("Alt_act_Descripcion1", ccsGet));
        $this->Alt_caj_Descripcion = new clsControl(ccsLabel, "Alt_caj_Descripcion", "Alt_caj_Descripcion", ccsText, "", CCGetRequestParam("Alt_caj_Descripcion", ccsGet));
        $this->Alt_tac_Fecha = new clsControl(ccsLabel, "Alt_tac_Fecha", "Alt_tac_Fecha", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("Alt_tac_Fecha", ccsGet));
        $this->Alt_tac_Zona = new clsControl(ccsLabel, "Alt_tac_Zona", "Alt_tac_Zona", ccsText, "", CCGetRequestParam("Alt_tac_Zona", ccsGet));
        $this->Alt_tac_CodOrigen = new clsControl(ccsLabel, "Alt_tac_CodOrigen", "Alt_tac_CodOrigen", ccsText, "", CCGetRequestParam("Alt_tac_CodOrigen", ccsGet));
        $this->Alt_tac_GrupLiquidacion = new clsControl(ccsLabel, "Alt_tac_GrupLiquidacion", "Alt_tac_GrupLiquidacion", ccsInteger, Array(True, 0, "", "", False, Array("#", "#", "#"), "", 1, True, ""), CCGetRequestParam("Alt_tac_GrupLiquidacion", ccsGet));
        $this->Alt_tac_Estado = new clsControl(ccsLabel, "Alt_tac_Estado", "Alt_tac_Estado", ccsMemo, "", CCGetRequestParam("Alt_tac_Estado", ccsGet));
        $this->Alt_tac_NumLiquid = new clsControl(ccsLabel, "Alt_tac_NumLiquid", "Alt_tac_NumLiquid", ccsInteger, Array(True, 0, "", "", False, Array("#", "#", "#", "#", "#"), "", 1, True, ""), CCGetRequestParam("Alt_tac_NumLiquid", ccsGet));
        $this->Alt_tad_CantRecibida = new clsControl(ccsLabel, "Alt_tad_CantRecibida", "Alt_tad_CantRecibida", ccsInteger, Array(False, 0, "", ",", False, "", "", 1, True, ""), CCGetRequestParam("Alt_tad_CantRecibida", ccsGet));
        $this->Alt_tad_CantRechazada = new clsControl(ccsLabel, "Alt_tad_CantRechazada", "Alt_tad_CantRechazada", ccsInteger, Array(False, 0, "", ",", False, "", "", 1, True, ""), CCGetRequestParam("Alt_tad_CantRechazada", ccsGet));
        $this->Alt_tad_Embarcado = new clsControl(ccsLabel, "Alt_tad_Embarcado", "Alt_tad_Embarcado", ccsInteger, "", CCGetRequestParam("Alt_tad_Embarcado", ccsGet));
        $this->Alt_tad_ValUnitario = new clsControl(ccsLabel, "Alt_tad_ValUnitario", "Alt_tad_ValUnitario", ccsFloat, Array(True, 4, ".", " ", False, Array("#", "#", "#", "0"), Array("#", "#", "#", "#"), 1, True, ""), CCGetRequestParam("Alt_tad_ValUnitario", ccsGet));
        $this->Alt_tad_DifUnitario = new clsControl(ccsLabel, "Alt_tad_DifUnitario", "Alt_tad_DifUnitario", ccsFloat, Array(True, 4, ".", " ", False, Array("#", "#", "#", "0"), Array("#", "#", "#", "#"), 1, True, ""), CCGetRequestParam("Alt_tad_DifUnitario", ccsGet));
        $this->Alt_emb_AnoOperacion = new clsControl(ccsLabel, "Alt_emb_AnoOperacion", "Alt_emb_AnoOperacion", ccsInteger, Array(True, 0, "", "", False, Array("0", "0"), "", 1, True, ""), CCGetRequestParam("Alt_emb_AnoOperacion", ccsGet));
        $this->Alt_per_Semana = new clsControl(ccsLabel, "Alt_per_Semana", "Alt_per_Semana", ccsInteger, Array(True, 0, "", "", False, Array("0", "0"), "", 1, True, ""), CCGetRequestParam("Alt_per_Semana", ccsGet));
        $this->Alt_buq_Abreviatura = new clsControl(ccsLabel, "Alt_buq_Abreviatura", "Alt_buq_Abreviatura", ccsText, "", CCGetRequestParam("Alt_buq_Abreviatura", ccsGet));
        $this->Alt_buq_Descripcion = new clsControl(ccsHidden, "Alt_buq_Descripcion", "Alt_buq_Descripcion", ccsText, "", CCGetRequestParam("Alt_buq_Descripcion", ccsGet));
        $this->Alt_emb_NumViaje = new clsControl(ccsLabel, "Alt_emb_NumViaje", "Alt_emb_NumViaje", ccsInteger, Array(True, 0, "", "", False, Array("#", "#", "#"), "", 1, True, ""), CCGetRequestParam("Alt_emb_NumViaje", ccsGet));
        $this->Alt_emb_Estado = new clsControl(ccsLabel, "Alt_emb_Estado", "Alt_emb_Estado", ccsInteger, "", CCGetRequestParam("Alt_emb_Estado", ccsGet));
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpCentered);
        $this->btNuevo = new clsButton("btNuevo");
        $this->btCerrar = new clsButton("btCerrar");
        $this->Sorter_tar_NumTarja = new clsSorter($this->ComponentName, "Sorter_tar_NumTarja", $FileName);
        $this->Sorter_tac_Semana = new clsSorter($this->ComponentName, "Sorter_tac_Semana", $FileName);
        $this->Sorter_per_Apellidos = new clsSorter($this->ComponentName, "Sorter_per_Apellidos", $FileName);
        $this->Sorter_act_Descripcion = new clsSorter($this->ComponentName, "Sorter_act_Descripcion", $FileName);
        $this->Sorter_caj_Descripcion = new clsSorter($this->ComponentName, "Sorter_caj_Descripcion", $FileName);
        $this->Sorter_tac_Fecha = new clsSorter($this->ComponentName, "Sorter_tac_Fecha", $FileName);
        $this->Sorter_tac_Zona = new clsSorter($this->ComponentName, "Sorter_tac_Zona", $FileName);
        $this->Sorter_tac_CodOrigen = new clsSorter($this->ComponentName, "Sorter_tac_CodOrigen", $FileName);
        $this->Sorter_tac_GrupLiquidacion = new clsSorter($this->ComponentName, "Sorter_tac_GrupLiquidacion", $FileName);
        $this->Sorter_tac_NumLiquid = new clsSorter($this->ComponentName, "Sorter_tac_NumLiquid", $FileName);
        $this->Sorter_tad_CantRecibida = new clsSorter($this->ComponentName, "Sorter_tad_CantRecibida", $FileName);
        $this->Sorter_tad_CantRechazada = new clsSorter($this->ComponentName, "Sorter_tad_CantRechazada", $FileName);
        $this->Sorter_tad_CantDespachada = new clsSorter($this->ComponentName, "Sorter_tad_CantDespachada", $FileName);
        $this->Sorter_tad_ValUnitario = new clsSorter($this->ComponentName, "Sorter_tad_ValUnitario", $FileName);
        $this->Sorter_tad_DifUnitario = new clsSorter($this->ComponentName, "Sorter_tad_DifUnitario", $FileName);
        $this->Sorter_emb_AnoOperacion = new clsSorter($this->ComponentName, "Sorter_emb_AnoOperacion", $FileName);
        $this->Sorter_per_Semana = new clsSorter($this->ComponentName, "Sorter_per_Semana", $FileName);
        $this->Sorter_buq_Abreviatura = new clsSorter($this->ComponentName, "Sorter_buq_Abreviatura", $FileName);
        $this->Sorter_emb_NumViaje = new clsSorter($this->ComponentName, "Sorter_emb_NumViaje", $FileName);
        $this->Sorter_emb_Estado = new clsSorter($this->ComponentName, "Sorter_emb_Estado", $FileName);
        $this->txt_CantRecibida = new clsControl(ccsLabel, "txt_CantRecibida", "txt_CantRecibida", ccsInteger, Array(False, 0, "", ",", False, "", "", 1, True, ""), CCGetRequestParam("txt_CantRecibida", ccsGet));
        $this->txt_CantRechazada = new clsControl(ccsLabel, "txt_CantRechazada", "txt_CantRechazada", ccsInteger, Array(False, 0, "", ",", False, "", "", 1, True, ""), CCGetRequestParam("txt_CantRechazada", ccsGet));
        $this->txt_CantEmbarcada = new clsControl(ccsLabel, "txt_CantEmbarcada", "txt_CantEmbarcada", ccsInteger, Array(False, 0, "", ",", False, "", "", 1, True, ""), CCGetRequestParam("txt_CantEmbarcada", ccsGet));
        $this->txt_ValUnitario = new clsControl(ccsLabel, "txt_ValUnitario", "txt_ValUnitario", ccsInteger, Array(True, 4, ".", ",", False, Array("#", "#", "#", "0"), Array("#", "#", "#", "#"), 1, True, ""), CCGetRequestParam("txt_ValUnitario", ccsGet));
        $this->txt_DifUnitario = new clsControl(ccsLabel, "txt_DifUnitario", "txt_DifUnitario", ccsInteger, Array(True, 4, ".", ",", False, Array("#", "#", "#", "0"), Array("#", "#", "#", "#"), 1, True, ""), CCGetRequestParam("txt_DifUnitario", ccsGet));
        $this->txt_ValTotal = new clsControl(ccsLabel, "txt_ValTotal", "txt_ValTotal", ccsInteger, Array(True, 4, ".", ",", False, Array("#", "#", "#", "0"), Array("0", "0", "#", "#"), 1, True, ""), CCGetRequestParam("txt_ValTotal", ccsGet));
    }
//End Class_Initialize Event

//Initialize Method @2-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @2-3CF23C83
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;


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
                    $this->tar_NumTarja->SetValue($this->ds->tar_NumTarja->GetValue());
                    $this->tac_Semana->SetValue($this->ds->tac_Semana->GetValue());
                    $this->per_Apellidos->SetValue($this->ds->per_Apellidos->GetValue());
                    $this->act_Descripcion->SetValue($this->ds->act_Descripcion->GetValue());
                    $this->act_Descripcion1->SetValue($this->ds->act_Descripcion1->GetValue());
                    $this->caj_Descripcion->SetValue($this->ds->caj_Descripcion->GetValue());
                    $this->tac_Fecha->SetValue($this->ds->tac_Fecha->GetValue());
                    $this->tac_Zona->SetValue($this->ds->tac_Zona->GetValue());
                    $this->tac_CodOrigen->SetValue($this->ds->tac_CodOrigen->GetValue());
                    $this->tac_GrupLiquidacion->SetValue($this->ds->tac_GrupLiquidacion->GetValue());
                    $this->tac_Estado->SetValue($this->ds->tac_Estado->GetValue());
                    $this->tac_NumLiquid->SetValue($this->ds->tac_NumLiquid->GetValue());
                    $this->tad_CantRecibida->SetValue($this->ds->tad_CantRecibida->GetValue());
                    $this->tad_CantRechazada->SetValue($this->ds->tad_CantRechazada->GetValue());
                    $this->tad_Embarcado->SetValue($this->ds->tad_Embarcado->GetValue());
                    $this->tad_ValUnitario->SetValue($this->ds->tad_ValUnitario->GetValue());
                    $this->tad_DifUnitario->SetValue($this->ds->tad_DifUnitario->GetValue());
                    $this->emb_AnoOperacion->SetValue($this->ds->emb_AnoOperacion->GetValue());
                    $this->per_Semana->SetValue($this->ds->per_Semana->GetValue());
                    $this->buq_Abreviatura->SetValue($this->ds->buq_Abreviatura->GetValue());
                    $this->buq_Descripcion->SetValue($this->ds->buq_Descripcion->GetValue());
                    $this->emb_NumViaje->SetValue($this->ds->emb_NumViaje->GetValue());
                    $this->emb_Estado->SetValue($this->ds->emb_Estado->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->tar_NumTarja->Show();
                    $this->btMantto1->Show();
                    $this->tac_Semana->Show();
                    $this->per_Apellidos->Show();
                    $this->act_Descripcion->Show();
                    $this->act_Descripcion1->Show();
                    $this->caj_Descripcion->Show();
                    $this->tac_Fecha->Show();
                    $this->tac_Zona->Show();
                    $this->tac_CodOrigen->Show();
                    $this->tac_GrupLiquidacion->Show();
                    $this->tac_Estado->Show();
                    $this->tac_NumLiquid->Show();
                    $this->tad_CantRecibida->Show();
                    $this->tad_CantRechazada->Show();
                    $this->tad_Embarcado->Show();
                    $this->tad_ValUnitario->Show();
                    $this->tad_DifUnitario->Show();
                    $this->emb_AnoOperacion->Show();
                    $this->per_Semana->Show();
                    $this->buq_Abreviatura->Show();
                    $this->buq_Descripcion->Show();
                    $this->emb_NumViaje->Show();
                    $this->emb_Estado->Show();
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                    $Tpl->parse("Row", true);
                }
                else
                {
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/AltRow";
                    $this->Alt_tar_NumTarja->SetValue($this->ds->Alt_tar_NumTarja->GetValue());
                    $this->Alt_tac_Semana->SetValue($this->ds->Alt_tac_Semana->GetValue());
                    $this->Alt_per_Apellidos->SetValue($this->ds->Alt_per_Apellidos->GetValue());
                    $this->Alt_act_Descripcion->SetValue($this->ds->Alt_act_Descripcion->GetValue());
                    $this->Alt_act_Descripcion1->SetValue($this->ds->Alt_act_Descripcion1->GetValue());
                    $this->Alt_caj_Descripcion->SetValue($this->ds->Alt_caj_Descripcion->GetValue());
                    $this->Alt_tac_Fecha->SetValue($this->ds->Alt_tac_Fecha->GetValue());
                    $this->Alt_tac_Zona->SetValue($this->ds->Alt_tac_Zona->GetValue());
                    $this->Alt_tac_CodOrigen->SetValue($this->ds->Alt_tac_CodOrigen->GetValue());
                    $this->Alt_tac_GrupLiquidacion->SetValue($this->ds->Alt_tac_GrupLiquidacion->GetValue());
                    $this->Alt_tac_Estado->SetValue($this->ds->Alt_tac_Estado->GetValue());
                    $this->Alt_tac_NumLiquid->SetValue($this->ds->Alt_tac_NumLiquid->GetValue());
                    $this->Alt_tad_CantRecibida->SetValue($this->ds->Alt_tad_CantRecibida->GetValue());
                    $this->Alt_tad_CantRechazada->SetValue($this->ds->Alt_tad_CantRechazada->GetValue());
                    $this->Alt_tad_Embarcado->SetValue($this->ds->Alt_tad_Embarcado->GetValue());
                    $this->Alt_tad_ValUnitario->SetValue($this->ds->Alt_tad_ValUnitario->GetValue());
                    $this->Alt_tad_DifUnitario->SetValue($this->ds->Alt_tad_DifUnitario->GetValue());
                    $this->Alt_emb_AnoOperacion->SetValue($this->ds->Alt_emb_AnoOperacion->GetValue());
                    $this->Alt_per_Semana->SetValue($this->ds->Alt_per_Semana->GetValue());
                    $this->Alt_buq_Abreviatura->SetValue($this->ds->Alt_buq_Abreviatura->GetValue());
                    $this->Alt_buq_Descripcion->SetValue($this->ds->Alt_buq_Descripcion->GetValue());
                    $this->Alt_emb_NumViaje->SetValue($this->ds->Alt_emb_NumViaje->GetValue());
                    $this->Alt_emb_Estado->SetValue($this->ds->Alt_emb_Estado->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->Alt_tar_NumTarja->Show();
                    $this->btMantto2->Show();
                    $this->Alt_tac_Semana->Show();
                    $this->Alt_per_Apellidos->Show();
                    $this->Alt_act_Descripcion->Show();
                    $this->Alt_act_Descripcion1->Show();
                    $this->Alt_caj_Descripcion->Show();
                    $this->Alt_tac_Fecha->Show();
                    $this->Alt_tac_Zona->Show();
                    $this->Alt_tac_CodOrigen->Show();
                    $this->Alt_tac_GrupLiquidacion->Show();
                    $this->Alt_tac_Estado->Show();
                    $this->Alt_tac_NumLiquid->Show();
                    $this->Alt_tad_CantRecibida->Show();
                    $this->Alt_tad_CantRechazada->Show();
                    $this->Alt_tad_Embarcado->Show();
                    $this->Alt_tad_ValUnitario->Show();
                    $this->Alt_tad_DifUnitario->Show();
                    $this->Alt_emb_AnoOperacion->Show();
                    $this->Alt_per_Semana->Show();
                    $this->Alt_buq_Abreviatura->Show();
                    $this->Alt_buq_Descripcion->Show();
                    $this->Alt_emb_NumViaje->Show();
                    $this->Alt_emb_Estado->Show();
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
        $this->txt_CantRecibida->SetValue($this->ds->txt_CantRecibida->GetValue());
        $this->txt_CantRechazada->SetValue($this->ds->txt_CantRechazada->GetValue());
        $this->txt_CantEmbarcada->SetValue($this->ds->txt_CantEmbarcada->GetValue());
        $this->txt_ValUnitario->SetValue($this->ds->txt_ValUnitario->GetValue());
        $this->txt_DifUnitario->SetValue($this->ds->txt_DifUnitario->GetValue());
        $this->txt_ValTotal->SetValue($this->ds->txt_ValTotal->GetValue());
        $this->Navigator->Show();
        $this->btNuevo->Show();
        $this->btCerrar->Show();
        $this->Sorter_tar_NumTarja->Show();
        $this->Sorter_tac_Semana->Show();
        $this->Sorter_per_Apellidos->Show();
        $this->Sorter_act_Descripcion->Show();
        $this->Sorter_caj_Descripcion->Show();
        $this->Sorter_tac_Fecha->Show();
        $this->Sorter_tac_Zona->Show();
        $this->Sorter_tac_CodOrigen->Show();
        $this->Sorter_tac_GrupLiquidacion->Show();
        $this->Sorter_tac_NumLiquid->Show();
        $this->Sorter_tad_CantRecibida->Show();
        $this->Sorter_tad_CantRechazada->Show();
        $this->Sorter_tad_CantDespachada->Show();
        $this->Sorter_tad_ValUnitario->Show();
        $this->Sorter_tad_DifUnitario->Show();
        $this->Sorter_emb_AnoOperacion->Show();
        $this->Sorter_per_Semana->Show();
        $this->Sorter_buq_Abreviatura->Show();
        $this->Sorter_emb_NumViaje->Show();
        $this->Sorter_emb_Estado->Show();
        $this->txt_CantRecibida->Show();
        $this->txt_CantRechazada->Show();
        $this->txt_CantEmbarcada->Show();
        $this->txt_ValUnitario->Show();
        $this->txt_DifUnitario->Show();
        $this->txt_ValTotal->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @2-34FCFD6B
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->tar_NumTarja->Errors->ToString();
        $errors .= $this->tac_Semana->Errors->ToString();
        $errors .= $this->per_Apellidos->Errors->ToString();
        $errors .= $this->act_Descripcion->Errors->ToString();
        $errors .= $this->act_Descripcion1->Errors->ToString();
        $errors .= $this->caj_Descripcion->Errors->ToString();
        $errors .= $this->tac_Fecha->Errors->ToString();
        $errors .= $this->tac_Zona->Errors->ToString();
        $errors .= $this->tac_CodOrigen->Errors->ToString();
        $errors .= $this->tac_GrupLiquidacion->Errors->ToString();
        $errors .= $this->tac_Estado->Errors->ToString();
        $errors .= $this->tac_NumLiquid->Errors->ToString();
        $errors .= $this->tad_CantRecibida->Errors->ToString();
        $errors .= $this->tad_CantRechazada->Errors->ToString();
        $errors .= $this->tad_Embarcado->Errors->ToString();
        $errors .= $this->tad_ValUnitario->Errors->ToString();
        $errors .= $this->tad_DifUnitario->Errors->ToString();
        $errors .= $this->emb_AnoOperacion->Errors->ToString();
        $errors .= $this->per_Semana->Errors->ToString();
        $errors .= $this->buq_Abreviatura->Errors->ToString();
        $errors .= $this->buq_Descripcion->Errors->ToString();
        $errors .= $this->emb_NumViaje->Errors->ToString();
        $errors .= $this->emb_Estado->Errors->ToString();
        $errors .= $this->Alt_tar_NumTarja->Errors->ToString();
        $errors .= $this->Alt_tac_Semana->Errors->ToString();
        $errors .= $this->Alt_per_Apellidos->Errors->ToString();
        $errors .= $this->Alt_act_Descripcion->Errors->ToString();
        $errors .= $this->Alt_act_Descripcion1->Errors->ToString();
        $errors .= $this->Alt_caj_Descripcion->Errors->ToString();
        $errors .= $this->Alt_tac_Fecha->Errors->ToString();
        $errors .= $this->Alt_tac_Zona->Errors->ToString();
        $errors .= $this->Alt_tac_CodOrigen->Errors->ToString();
        $errors .= $this->Alt_tac_GrupLiquidacion->Errors->ToString();
        $errors .= $this->Alt_tac_Estado->Errors->ToString();
        $errors .= $this->Alt_tac_NumLiquid->Errors->ToString();
        $errors .= $this->Alt_tad_CantRecibida->Errors->ToString();
        $errors .= $this->Alt_tad_CantRechazada->Errors->ToString();
        $errors .= $this->Alt_tad_Embarcado->Errors->ToString();
        $errors .= $this->Alt_tad_ValUnitario->Errors->ToString();
        $errors .= $this->Alt_tad_DifUnitario->Errors->ToString();
        $errors .= $this->Alt_emb_AnoOperacion->Errors->ToString();
        $errors .= $this->Alt_per_Semana->Errors->ToString();
        $errors .= $this->Alt_buq_Abreviatura->Errors->ToString();
        $errors .= $this->Alt_buq_Descripcion->Errors->ToString();
        $errors .= $this->Alt_emb_NumViaje->Errors->ToString();
        $errors .= $this->Alt_emb_Estado->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End tarjas_list Class @2-FCB6E20C

class clstarjas_listDataSource extends clsDBdatos {  //tarjas_listDataSource Class @2-7C4C8044

//DataSource Variables @2-12CF573C
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $tar_NumTarja;
    var $tac_Semana;
    var $per_Apellidos;
    var $act_Descripcion;
    var $act_Descripcion1;
    var $caj_Descripcion;
    var $tac_Fecha;
    var $tac_Zona;
    var $tac_CodOrigen;
    var $tac_GrupLiquidacion;
    var $tac_Estado;
    var $tac_NumLiquid;
    var $tad_CantRecibida;
    var $tad_CantRechazada;
    var $tad_Embarcado;
    var $tad_ValUnitario;
    var $tad_DifUnitario;
    var $emb_AnoOperacion;
    var $per_Semana;
    var $buq_Abreviatura;
    var $buq_Descripcion;
    var $emb_NumViaje;
    var $emb_Estado;
    var $Alt_tar_NumTarja;
    var $Alt_tac_Semana;
    var $Alt_per_Apellidos;
    var $Alt_act_Descripcion;
    var $Alt_act_Descripcion1;
    var $Alt_caj_Descripcion;
    var $Alt_tac_Fecha;
    var $Alt_tac_Zona;
    var $Alt_tac_CodOrigen;
    var $Alt_tac_GrupLiquidacion;
    var $Alt_tac_Estado;
    var $Alt_tac_NumLiquid;
    var $Alt_tad_CantRecibida;
    var $Alt_tad_CantRechazada;
    var $Alt_tad_Embarcado;
    var $Alt_tad_ValUnitario;
    var $Alt_tad_DifUnitario;
    var $Alt_emb_AnoOperacion;
    var $Alt_per_Semana;
    var $Alt_buq_Abreviatura;
    var $Alt_buq_Descripcion;
    var $Alt_emb_NumViaje;
    var $Alt_emb_Estado;
    var $txt_CantRecibida;
    var $txt_CantRechazada;
    var $txt_CantEmbarcada;
    var $txt_ValUnitario;
    var $txt_DifUnitario;
    var $txt_ValTotal;
//End DataSource Variables

//Class_Initialize Event @2-FFA6A9B2
    function clstarjas_listDataSource()
    {
        $this->ErrorBlock = "Grid tarjas_list";
        $this->Initialize();
        $this->tar_NumTarja = new clsField("tar_NumTarja", ccsInteger, "");
        $this->tac_Semana = new clsField("tac_Semana", ccsInteger, "");
        $this->per_Apellidos = new clsField("per_Apellidos", ccsText, "");
        $this->act_Descripcion = new clsField("act_Descripcion", ccsText, "");
        $this->act_Descripcion1 = new clsField("act_Descripcion1", ccsText, "");
        $this->caj_Descripcion = new clsField("caj_Descripcion", ccsText, "");
        $this->tac_Fecha = new clsField("tac_Fecha", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->tac_Zona = new clsField("tac_Zona", ccsText, "");
        $this->tac_CodOrigen = new clsField("tac_CodOrigen", ccsText, "");
        $this->tac_GrupLiquidacion = new clsField("tac_GrupLiquidacion", ccsInteger, "");
        $this->tac_Estado = new clsField("tac_Estado", ccsMemo, "");
        $this->tac_NumLiquid = new clsField("tac_NumLiquid", ccsInteger, "");
        $this->tad_CantRecibida = new clsField("tad_CantRecibida", ccsInteger, "");
        $this->tad_CantRechazada = new clsField("tad_CantRechazada", ccsInteger, "");
        $this->tad_Embarcado = new clsField("tad_Embarcado", ccsInteger, "");
        $this->tad_ValUnitario = new clsField("tad_ValUnitario", ccsFloat, "");
        $this->tad_DifUnitario = new clsField("tad_DifUnitario", ccsFloat, "");
        $this->emb_AnoOperacion = new clsField("emb_AnoOperacion", ccsInteger, "");
        $this->per_Semana = new clsField("per_Semana", ccsInteger, "");
        $this->buq_Abreviatura = new clsField("buq_Abreviatura", ccsText, "");
        $this->buq_Descripcion = new clsField("buq_Descripcion", ccsText, "");
        $this->emb_NumViaje = new clsField("emb_NumViaje", ccsInteger, "");
        $this->emb_Estado = new clsField("emb_Estado", ccsInteger, "");
        $this->Alt_tar_NumTarja = new clsField("Alt_tar_NumTarja", ccsInteger, "");
        $this->Alt_tac_Semana = new clsField("Alt_tac_Semana", ccsInteger, "");
        $this->Alt_per_Apellidos = new clsField("Alt_per_Apellidos", ccsText, "");
        $this->Alt_act_Descripcion = new clsField("Alt_act_Descripcion", ccsText, "");
        $this->Alt_act_Descripcion1 = new clsField("Alt_act_Descripcion1", ccsText, "");
        $this->Alt_caj_Descripcion = new clsField("Alt_caj_Descripcion", ccsText, "");
        $this->Alt_tac_Fecha = new clsField("Alt_tac_Fecha", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->Alt_tac_Zona = new clsField("Alt_tac_Zona", ccsText, "");
        $this->Alt_tac_CodOrigen = new clsField("Alt_tac_CodOrigen", ccsText, "");
        $this->Alt_tac_GrupLiquidacion = new clsField("Alt_tac_GrupLiquidacion", ccsInteger, "");
        $this->Alt_tac_Estado = new clsField("Alt_tac_Estado", ccsMemo, "");
        $this->Alt_tac_NumLiquid = new clsField("Alt_tac_NumLiquid", ccsInteger, "");
        $this->Alt_tad_CantRecibida = new clsField("Alt_tad_CantRecibida", ccsInteger, "");
        $this->Alt_tad_CantRechazada = new clsField("Alt_tad_CantRechazada", ccsInteger, "");
        $this->Alt_tad_Embarcado = new clsField("Alt_tad_Embarcado", ccsInteger, "");
        $this->Alt_tad_ValUnitario = new clsField("Alt_tad_ValUnitario", ccsFloat, "");
        $this->Alt_tad_DifUnitario = new clsField("Alt_tad_DifUnitario", ccsFloat, "");
        $this->Alt_emb_AnoOperacion = new clsField("Alt_emb_AnoOperacion", ccsInteger, "");
        $this->Alt_per_Semana = new clsField("Alt_per_Semana", ccsInteger, "");
        $this->Alt_buq_Abreviatura = new clsField("Alt_buq_Abreviatura", ccsText, "");
        $this->Alt_buq_Descripcion = new clsField("Alt_buq_Descripcion", ccsText, "");
        $this->Alt_emb_NumViaje = new clsField("Alt_emb_NumViaje", ccsInteger, "");
        $this->Alt_emb_Estado = new clsField("Alt_emb_Estado", ccsInteger, "");
        $this->txt_CantRecibida = new clsField("txt_CantRecibida", ccsInteger, "");
        $this->txt_CantRechazada = new clsField("txt_CantRechazada", ccsInteger, "");
        $this->txt_CantEmbarcada = new clsField("txt_CantEmbarcada", ccsInteger, "");
        $this->txt_ValUnitario = new clsField("txt_ValUnitario", ccsInteger, "");
        $this->txt_DifUnitario = new clsField("txt_DifUnitario", ccsInteger, "");
        $this->txt_ValTotal = new clsField("txt_ValTotal", ccsInteger, "");

    }
//End Class_Initialize Event

//SetOrder Method @2-0A73B6CA
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_tar_NumTarja" => array("tar_NumTarja", ""), 
            "Sorter_tac_Semana" => array("tac_Semana", ""), 
            "Sorter_per_Apellidos" => array("per_Apellidos", ""), 
            "Sorter_act_Descripcion" => array("act_Descripcion", ""), 
            "Sorter_caj_Descripcion" => array("caj_Descripcion", ""), 
            "Sorter_tac_Fecha" => array("tac_Fecha", ""), 
            "Sorter_tac_Zona" => array("tac_Zona", ""), 
            "Sorter_tac_CodOrigen" => array("tac_CodOrigen", ""), 
            "Sorter_tac_GrupLiquidacion" => array("tac_GrupLiquidacion", ""), 
            "Sorter_tac_NumLiquid" => array("tac_NumLiquid", ""), 
            "Sorter_tad_CantRecibida" => array("tad_CantRecibida", ""), 
            "Sorter_tad_CantRechazada" => array("tad_CantRechazada", ""), 
            "Sorter_tad_CantDespachada" => array("tad_CantDespachada", ""), 
            "Sorter_tad_ValUnitario" => array("tad_ValUnitario", ""), 
            "Sorter_tad_DifUnitario" => array("tad_DifUnitario", ""), 
            "Sorter_emb_AnoOperacion" => array("emb_AnoOperacion", ""), 
            "Sorter_per_Semana" => array("per_Semana", ""), 
            "Sorter_buq_Abreviatura" => array("buq_Abreviatura", ""), 
            "Sorter_emb_NumViaje" => array("emb_NumViaje", ""), 
            "Sorter_emb_Estado" => array("emb_Estado", "")));
    }
//End SetOrder Method

//Prepare Method @2-DFF3DD87
    function Prepare()
    {
    }
//End Prepare Method

//Open Method @2-57AC38F9
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM liqembarques em JOIN liqtarjacabec tc ON tc.tac_RefOperativa = em.emb_RefOperativa  " .
        "LEFT JOIN liqbuques bu ON em.emb_CodVapor = bu.buq_CodBuque  " .
        "LEFT JOIN liqtarjadetal td ON td.tad_NumTarja = tc.tar_NumTarja  " .
        "LEFT JOIN conactivos it ON td.tad_CodProducto = it.act_CodAuxiliar  " .
        "LEFT JOIN liqcajas ON td.tad_CodCaja = liqcajas.caj_CodCaja  " .
        "LEFT JOIN conperiodos pe ON per_aplicacion = 'LI' AND tc.tac_Semana = pe.per_NumPeriodo  " .
        "LEFT JOIN conpersonas pr ON tc.tac_Embarcador = pr.per_CodAuxiliar " .
        "LEFT JOIN liqcomponent cm ON cte_codigo = tad_codCompon2 ";
        $this->SQL = "SELECT tar_NumTarja, tac_Semana, tac_Fecha, tac_Zona, tac_CodOrigen, tac_GrupLiquidacion, tac_Estado, tad_LiqNumero,  " .
        "tad_CantRecibida - tad_CantRechazada   as txt_Embarcado, " .
        "tad_CantDespachada, tad_CantRecibida, tad_CantRechazada, tad_ValUnitario, tad_DifUnitario, emb_AnoOperacion, emb_NumViaje,  " .
        "emb_Estado, buq_Abreviatura, buq_Descripcion, per_Semana, act_Descripcion, act_Descripcion1, tac_Contenedor, " .
        "concat(left(per_Apellidos,15), ' ', left(ifNULL(per_Nombres,''),12)) as txt_Nomb, concat(caj_Abreviatura , \" - \", cte_referencia) as txt_empaque " .
        "FROM liqtarjacabec tc " .
	"LEFT JOIN liqembarques em ON em.emb_RefOperativa = tc.tac_RefOperativa " .
        "LEFT JOIN liqbuques bu ON em.emb_CodVapor = bu.buq_CodBuque  " .
        "LEFT JOIN liqtarjadetal td ON td.tad_NumTarja = tc.tar_NumTarja  " .
        "LEFT JOIN conactivos it ON td.tad_CodProducto = it.act_CodAuxiliar  " .
        "LEFT JOIN liqcajas ON td.tad_CodCaja = liqcajas.caj_CodCaja  " .
        "LEFT JOIN conperiodos pe ON per_aplicacion = 'LI' AND tc.tac_Semana = pe.per_NumPeriodo  " .
        "LEFT JOIN conpersonas pr ON tc.tac_Embarcador = pr.per_CodAuxiliar " .
        "LEFT JOIN liqcomponent cm ON cte_codigo = tad_codCompon2  ";
       
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue($this->CountSQL, $this);
        $this->query(CCBuildSQL($this->SQL, "", $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @2-790B467E
    function SetValues()
    {
        $this->tar_NumTarja->SetDBValue(trim($this->f("tar_NumTarja")));
        $this->tac_Semana->SetDBValue(trim($this->f("tac_Semana")));
        $this->per_Apellidos->SetDBValue($this->f("txt_Nomb"));
        $this->act_Descripcion->SetDBValue($this->f("act_Descripcion"));
        $this->act_Descripcion1->SetDBValue($this->f("act_Descripcion1"));
        $this->caj_Descripcion->SetDBValue($this->f("txt_empaque"));
        $this->tac_Fecha->SetDBValue(trim($this->f("tac_Fecha")));
        $this->tac_Zona->SetDBValue($this->f("tac_Zona"));
        $this->tac_CodOrigen->SetDBValue($this->f("tac_CodOrigen"));
        $this->tac_GrupLiquidacion->SetDBValue(trim($this->f("tac_GrupLiquidacion")));
        $this->tac_Estado->SetDBValue($this->f("tac_Estado"));
        $this->tac_NumLiquid->SetDBValue(trim($this->f("tad_LiqNumero")));
        $this->tad_CantRecibida->SetDBValue(trim($this->f("tad_CantRecibida")));
        $this->tad_CantRechazada->SetDBValue(trim($this->f("tad_CantRechazada")));
        $this->tad_Embarcado->SetDBValue(trim($this->f("txt_Embarcado")));
        $this->tad_ValUnitario->SetDBValue(trim($this->f("tad_ValUnitario")));
        $this->tad_DifUnitario->SetDBValue(trim($this->f("tad_DifUnitario")));
        $this->emb_AnoOperacion->SetDBValue(trim($this->f("emb_AnoOperacion")));
        $this->per_Semana->SetDBValue(trim($this->f("per_Semana")));
        $this->buq_Abreviatura->SetDBValue($this->f("buq_Abreviatura"));
        $this->buq_Descripcion->SetDBValue($this->f("buq_Descripcion"));
        $this->emb_NumViaje->SetDBValue(trim($this->f("emb_NumViaje")));
        $this->emb_Estado->SetDBValue(trim($this->f("emb_Estado")));
        $this->Alt_tar_NumTarja->SetDBValue(trim($this->f("tar_NumTarja")));
        $this->Alt_tac_Semana->SetDBValue(trim($this->f("tac_Semana")));
        $this->Alt_per_Apellidos->SetDBValue($this->f("txt_Nomb"));
        $this->Alt_act_Descripcion->SetDBValue($this->f("act_Descripcion"));
        $this->Alt_act_Descripcion1->SetDBValue($this->f("act_Descripcion1"));
        $this->Alt_caj_Descripcion->SetDBValue($this->f("txt_empaque"));
        $this->Alt_tac_Fecha->SetDBValue(trim($this->f("tac_Fecha")));
        $this->Alt_tac_Zona->SetDBValue($this->f("tac_Zona"));
        $this->Alt_tac_CodOrigen->SetDBValue($this->f("tac_CodOrigen"));
        $this->Alt_tac_GrupLiquidacion->SetDBValue(trim($this->f("tac_GrupLiquidacion")));
        $this->Alt_tac_Estado->SetDBValue($this->f("tac_Estado"));
        $this->Alt_tac_NumLiquid->SetDBValue(trim($this->f("tad_LiqNumero")));
        $this->Alt_tad_CantRecibida->SetDBValue(trim($this->f("tad_CantRecibida")));
        $this->Alt_tad_CantRechazada->SetDBValue(trim($this->f("tad_CantRechazada")));
        $this->Alt_tad_Embarcado->SetDBValue(trim($this->f("txt_Embarcado")));
        $this->Alt_tad_ValUnitario->SetDBValue(trim($this->f("tad_ValUnitario")));
        $this->Alt_tad_DifUnitario->SetDBValue(trim($this->f("tad_DifUnitario")));
        $this->Alt_emb_AnoOperacion->SetDBValue(trim($this->f("emb_AnoOperacion")));
        $this->Alt_per_Semana->SetDBValue(trim($this->f("per_Semana")));
        $this->Alt_buq_Abreviatura->SetDBValue($this->f("buq_Abreviatura"));
        $this->Alt_buq_Descripcion->SetDBValue($this->f("buq_Descripcion"));
        $this->Alt_emb_NumViaje->SetDBValue(trim($this->f("emb_NumViaje")));
        $this->Alt_emb_Estado->SetDBValue(trim($this->f("emb_Estado")));
        $this->txt_CantRecibida->SetDBValue(trim($this->f("tad_CantDespachada")));
        $this->txt_CantRechazada->SetDBValue(trim($this->f("tad_CantDespachada")));
        $this->txt_CantEmbarcada->SetDBValue(trim($this->f("tad_CantDespachada")));
        $this->txt_ValUnitario->SetDBValue(trim($this->f("tad_CantDespachada")));
        $this->txt_DifUnitario->SetDBValue(trim($this->f("tad_CantDespachada")));
        $this->txt_ValTotal->SetDBValue(trim($this->f("tad_CantDespachada")));
    }
//End SetValues Method

} //End tarjas_listDataSource Class @2-FCB6E20C

//Initialize Page @1-22627392
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

$FileName = "LiEmTj_search.php";
$Redirect = "";
$TemplateFileName = "LiEmTj_search.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-4557BDD0
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
$tarjas_qry = new clsRecordtarjas_qry();
$tarjas_list = new clsGridtarjas_list();
$tarjas_list->Initialize();

// Events
include("./LiEmTj_search_events.php");
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

//Execute Components @1-69615336
$Cabecera->Operations();
$tarjas_qry->Operation();
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

//Show Page @1-FD407FD6
$Cabecera->Show("Cabecera");
$tarjas_qry->Show();
$tarjas_list->Show();
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
