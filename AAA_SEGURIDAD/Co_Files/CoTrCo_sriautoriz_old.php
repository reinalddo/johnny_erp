<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

class clsRecordgenautsri { //genautsri Class @2-4E5EE584

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

//Class_Initialize Event @2-07AA3EDC
    function clsRecordgenautsri()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record genautsri/Error";
        $this->ds = new clsgenautsriDataSource();
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "genautsri";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->aut_ID = new clsControl(ccsTextBox, "aut_ID", "Numero de Autorizacion", ccsInteger, "", CCGetRequestParam("aut_ID", $Method));
            $this->aut_AutSri = new clsControl(ccsHidden, "aut_AutSri", "Aut Aut Sri", ccsInteger, "", CCGetRequestParam("aut_AutSri", $Method));
            $this->aut_IdAuxiliar = new clsControl(ccsTextBox, "aut_IdAuxiliar", "Aut Id Auxiliar", ccsInteger, "", CCGetRequestParam("aut_IdAuxiliar", $Method));
            $this->aut_IdAuxiliar->Required = true;
            $this->txt_Apellidos = new clsControl(ccsLabel, "txt_Apellidos", "txt_Apellidos", ccsText, "", CCGetRequestParam("txt_Apellidos", $Method));
            $this->txt_Nombres = new clsControl(ccsLabel, "txt_Nombres", "txt_Nombres", ccsText, "", CCGetRequestParam("txt_Nombres", $Method));
            $this->txt_Ruc = new clsControl(ccsLabel, "txt_Ruc", "txt_Ruc", ccsText, "", CCGetRequestParam("txt_Ruc", $Method));
            $this->aut_TipoDocum = new clsControl(ccsListBox, "aut_TipoDocum", "Tipo de Documento", ccsText, "", CCGetRequestParam("aut_TipoDocum", $Method));
            $this->aut_TipoDocum->DSType = dsSQL;
            list($this->aut_TipoDocum->BoundColumn, $this->aut_TipoDocum->TextColumn, $this->aut_TipoDocum->DBFormat) = array("tab_Codigo", "tab_Descripcion", "");
            $this->aut_TipoDocum->ds = new clsDBdatos();
            $this->aut_TipoDocum->ds->SQL = "SELECT tab_Codigo, tab_Descripcion " .
            "FROM fistablassri " .
            "where tab_CodTabla = 2 " .
            "";
            $this->aut_TipoDocum->ds->Order = "tab_Descripcion";
            $this->aut_TipoDocum->Required = true;
            $this->aut_FecEmision = new clsControl(ccsTextBox, "aut_FecEmision", "Aut Fec Emision", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("aut_FecEmision", $Method));
            $this->DatePicker_aut_FecEmision = new clsDatePicker("DatePicker_aut_FecEmision", "genautsri", "aut_FecEmision");
            $this->aut_FecVigencia = new clsControl(ccsTextBox, "aut_FecVigencia", "Aut Fec Vigencia", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("aut_FecVigencia", $Method));
            $this->DatePicker_aut_FecVigencia = new clsDatePicker("DatePicker_aut_FecVigencia", "genautsri", "aut_FecVigencia");
            $this->aut_NroInicial = new clsControl(ccsTextBox, "aut_NroInicial", "Aut Nro Inicial", ccsInteger, "", CCGetRequestParam("aut_NroInicial", $Method));
            $this->aut_NroFinal = new clsControl(ccsTextBox, "aut_NroFinal", "Aut Nro Final", ccsInteger, "", CCGetRequestParam("aut_NroFinal", $Method));
            $this->aut_RucImprenta = new clsControl(ccsTextBox, "aut_RucImprenta", "RUC de  Imprenta", ccsText, "", CCGetRequestParam("aut_RucImprenta", $Method));
            $this->aut_AutImprenta = new clsControl(ccsTextBox, "aut_AutImprenta", "RUC de  Imprenta", ccsText, "", CCGetRequestParam("aut_AutImprenta", $Method));
            $this->aut_Usuario = new clsControl(ccsTextBox, "aut_Usuario", "Aut Usuario", ccsText, "", CCGetRequestParam("aut_Usuario", $Method));
            $this->aut_FecRegistro = new clsControl(ccsTextBox, "aut_FecRegistro", "Aut Fec Registro", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("aut_FecRegistro", $Method));
            $this->Button_Insert = new clsButton("Button_Insert");
            $this->Button_Update = new clsButton("Button_Update");
            $this->Button_Delete = new clsButton("Button_Delete");
            $this->Button_Cancel = new clsButton("Button_Cancel");
            if(!$this->FormSubmitted) {
                if(!is_array($this->aut_IdAuxiliar->Value) && !strlen($this->aut_IdAuxiliar->Value) && $this->aut_IdAuxiliar->Value !== false)
                $this->aut_IdAuxiliar->SetText($_GET['aut_IdAuxiliar']);
                if(!is_array($this->aut_RucImprenta->Value) && !strlen($this->aut_RucImprenta->Value) && $this->aut_RucImprenta->Value !== false)
                $this->aut_RucImprenta->SetText(" ");
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @2-513D0B61
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->ds->Parameters["urlaut_ID"] = CCGetFromGet("aut_ID", "");
    }
//End Initialize Method

//Validate Method @2-E6CC1C77
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->aut_ID->Validate() && $Validation);
        $Validation = ($this->aut_AutSri->Validate() && $Validation);
        $Validation = ($this->aut_IdAuxiliar->Validate() && $Validation);
        $Validation = ($this->aut_TipoDocum->Validate() && $Validation);
        $Validation = ($this->aut_FecEmision->Validate() && $Validation);
        $Validation = ($this->aut_FecVigencia->Validate() && $Validation);
        $Validation = ($this->aut_NroInicial->Validate() && $Validation);
        $Validation = ($this->aut_NroFinal->Validate() && $Validation);
        $Validation = ($this->aut_RucImprenta->Validate() && $Validation);
        $Validation = ($this->aut_AutImprenta->Validate() && $Validation);
        $Validation = ($this->aut_Usuario->Validate() && $Validation);
        $Validation = ($this->aut_FecRegistro->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        $Validation =  $Validation && ($this->aut_ID->Errors->Count() == 0);
        $Validation =  $Validation && ($this->aut_AutSri->Errors->Count() == 0);
        $Validation =  $Validation && ($this->aut_IdAuxiliar->Errors->Count() == 0);
        $Validation =  $Validation && ($this->aut_TipoDocum->Errors->Count() == 0);
        $Validation =  $Validation && ($this->aut_FecEmision->Errors->Count() == 0);
        $Validation =  $Validation && ($this->aut_FecVigencia->Errors->Count() == 0);
        $Validation =  $Validation && ($this->aut_NroInicial->Errors->Count() == 0);
        $Validation =  $Validation && ($this->aut_NroFinal->Errors->Count() == 0);
        $Validation =  $Validation && ($this->aut_RucImprenta->Errors->Count() == 0);
        $Validation =  $Validation && ($this->aut_AutImprenta->Errors->Count() == 0);
        $Validation =  $Validation && ($this->aut_Usuario->Errors->Count() == 0);
        $Validation =  $Validation && ($this->aut_FecRegistro->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-0F25303C
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->aut_ID->Errors->Count());
        $errors = ($errors || $this->aut_AutSri->Errors->Count());
        $errors = ($errors || $this->aut_IdAuxiliar->Errors->Count());
        $errors = ($errors || $this->txt_Apellidos->Errors->Count());
        $errors = ($errors || $this->txt_Nombres->Errors->Count());
        $errors = ($errors || $this->txt_Ruc->Errors->Count());
        $errors = ($errors || $this->aut_TipoDocum->Errors->Count());
        $errors = ($errors || $this->aut_FecEmision->Errors->Count());
        $errors = ($errors || $this->DatePicker_aut_FecEmision->Errors->Count());
        $errors = ($errors || $this->aut_FecVigencia->Errors->Count());
        $errors = ($errors || $this->DatePicker_aut_FecVigencia->Errors->Count());
        $errors = ($errors || $this->aut_NroInicial->Errors->Count());
        $errors = ($errors || $this->aut_NroFinal->Errors->Count());
        $errors = ($errors || $this->aut_RucImprenta->Errors->Count());
        $errors = ($errors || $this->aut_AutImprenta->Errors->Count());
        $errors = ($errors || $this->aut_Usuario->Errors->Count());
        $errors = ($errors || $this->aut_FecRegistro->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-B880B6F1
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
        $Redirect = "CoTrCo_sriautoriz.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
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

//InsertRow Method @2-C59146CD
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert");
        if(!$this->InsertAllowed) return false;
        $this->ds->aut_IdAuxiliar->SetValue($this->aut_IdAuxiliar->GetValue());
        $this->ds->aut_TipoDocum->SetValue($this->aut_TipoDocum->GetValue());
        $this->ds->aut_RucImprenta->SetValue($this->aut_RucImprenta->GetValue());
        $this->ds->aut_FecEmision->SetValue($this->aut_FecEmision->GetValue());
        $this->ds->aut_FecVigencia->SetValue($this->aut_FecVigencia->GetValue());
        $this->ds->aut_NroInicial->SetValue($this->aut_NroInicial->GetValue());
        $this->ds->aut_NroFinal->SetValue($this->aut_NroFinal->GetValue());
        $this->ds->aut_AutImprenta->SetValue($this->aut_AutImprenta->GetValue());
        $this->ds->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert");
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @2-AFC695B6
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate");
        if(!$this->UpdateAllowed) return false;
        $this->ds->aut_IdAuxiliar->SetValue($this->aut_IdAuxiliar->GetValue());
        $this->ds->aut_TipoDocum->SetValue($this->aut_TipoDocum->GetValue());
        $this->ds->aut_RucImprenta->SetValue($this->aut_RucImprenta->GetValue());
        $this->ds->aut_FecEmision->SetValue($this->aut_FecEmision->GetValue());
        $this->ds->aut_FecVigencia->SetValue($this->aut_FecVigencia->GetValue());
        $this->ds->aut_NroInicial->SetValue($this->aut_NroInicial->GetValue());
        $this->ds->aut_NroFinal->SetValue($this->aut_NroFinal->GetValue());
        $this->ds->aut_FecRegistro->SetValue($this->aut_FecRegistro->GetValue());
        $this->ds->aut_AutImprenta->SetValue($this->aut_AutImprenta->GetValue());
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

//Show Method @2-23C4BDC8
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->aut_TipoDocum->Prepare();

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
                    echo "Error in Record genautsri";
                }
                else if($this->ds->next_record())
                {
                    $this->ds->SetValues();
                    $this->txt_Apellidos->SetValue($this->ds->txt_Apellidos->GetValue());
                    $this->txt_Nombres->SetValue($this->ds->txt_Nombres->GetValue());
                    $this->txt_Ruc->SetValue($this->ds->txt_Ruc->GetValue());
                    if(!$this->FormSubmitted)
                    {
                        $this->aut_ID->SetValue($this->ds->aut_ID->GetValue());
                        $this->aut_AutSri->SetValue($this->ds->aut_AutSri->GetValue());
                        $this->aut_IdAuxiliar->SetValue($this->ds->aut_IdAuxiliar->GetValue());
                        $this->aut_TipoDocum->SetValue($this->ds->aut_TipoDocum->GetValue());
                        $this->aut_FecEmision->SetValue($this->ds->aut_FecEmision->GetValue());
                        $this->aut_FecVigencia->SetValue($this->ds->aut_FecVigencia->GetValue());
                        $this->aut_NroInicial->SetValue($this->ds->aut_NroInicial->GetValue());
                        $this->aut_NroFinal->SetValue($this->ds->aut_NroFinal->GetValue());
                        $this->aut_RucImprenta->SetValue($this->ds->aut_RucImprenta->GetValue());
                        $this->aut_AutImprenta->SetValue($this->ds->aut_AutImprenta->GetValue());
                        $this->aut_Usuario->SetValue($this->ds->aut_Usuario->GetValue());
                        $this->aut_FecRegistro->SetValue($this->ds->aut_FecRegistro->GetValue());
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
            $Error .= $this->aut_ID->Errors->ToString();
            $Error .= $this->aut_AutSri->Errors->ToString();
            $Error .= $this->aut_IdAuxiliar->Errors->ToString();
            $Error .= $this->txt_Apellidos->Errors->ToString();
            $Error .= $this->txt_Nombres->Errors->ToString();
            $Error .= $this->txt_Ruc->Errors->ToString();
            $Error .= $this->aut_TipoDocum->Errors->ToString();
            $Error .= $this->aut_FecEmision->Errors->ToString();
            $Error .= $this->DatePicker_aut_FecEmision->Errors->ToString();
            $Error .= $this->aut_FecVigencia->Errors->ToString();
            $Error .= $this->DatePicker_aut_FecVigencia->Errors->ToString();
            $Error .= $this->aut_NroInicial->Errors->ToString();
            $Error .= $this->aut_NroFinal->Errors->ToString();
            $Error .= $this->aut_RucImprenta->Errors->ToString();
            $Error .= $this->aut_AutImprenta->Errors->ToString();
            $Error .= $this->aut_Usuario->Errors->ToString();
            $Error .= $this->aut_FecRegistro->Errors->ToString();
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

        $this->aut_ID->Show();
        $this->aut_AutSri->Show();
        $this->aut_IdAuxiliar->Show();
        $this->txt_Apellidos->Show();
        $this->txt_Nombres->Show();
        $this->txt_Ruc->Show();
        $this->aut_TipoDocum->Show();
        $this->aut_FecEmision->Show();
        $this->DatePicker_aut_FecEmision->Show();
        $this->aut_FecVigencia->Show();
        $this->DatePicker_aut_FecVigencia->Show();
        $this->aut_NroInicial->Show();
        $this->aut_NroFinal->Show();
        $this->aut_RucImprenta->Show();
        $this->aut_AutImprenta->Show();
        $this->aut_Usuario->Show();
        $this->aut_FecRegistro->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->Button_Cancel->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

} //End genautsri Class @2-FCB6E20C

class clsgenautsriDataSource extends clsDBdatos {  //genautsriDataSource Class @2-B814CA55

//DataSource Variables @2-2F7926E2
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
    var $aut_ID;
    var $aut_AutSri;
    var $aut_IdAuxiliar;
    var $txt_Apellidos;
    var $txt_Nombres;
    var $txt_Ruc;
    var $aut_TipoDocum;
    var $aut_FecEmision;
    var $aut_FecVigencia;
    var $aut_NroInicial;
    var $aut_NroFinal;
    var $aut_RucImprenta;
    var $aut_AutImprenta;
    var $aut_Usuario;
    var $aut_FecRegistro;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-2D8DA1A2
    function clsgenautsriDataSource()
    {
        $this->ErrorBlock = "Record genautsri/Error";
        $this->Initialize();
        $this->aut_ID = new clsField("aut_ID", ccsInteger, "");
        $this->aut_AutSri = new clsField("aut_AutSri", ccsInteger, "");
        $this->aut_IdAuxiliar = new clsField("aut_IdAuxiliar", ccsInteger, "");
        $this->txt_Apellidos = new clsField("txt_Apellidos", ccsText, "");
        $this->txt_Nombres = new clsField("txt_Nombres", ccsText, "");
        $this->txt_Ruc = new clsField("txt_Ruc", ccsText, "");
        $this->aut_TipoDocum = new clsField("aut_TipoDocum", ccsText, "");
        $this->aut_FecEmision = new clsField("aut_FecEmision", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->aut_FecVigencia = new clsField("aut_FecVigencia", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->aut_NroInicial = new clsField("aut_NroInicial", ccsInteger, "");
        $this->aut_NroFinal = new clsField("aut_NroFinal", ccsInteger, "");
        $this->aut_RucImprenta = new clsField("aut_RucImprenta", ccsText, "");
        $this->aut_AutImprenta = new clsField("aut_AutImprenta", ccsText, "");
        $this->aut_Usuario = new clsField("aut_Usuario", ccsText, "");
        $this->aut_FecRegistro = new clsField("aut_FecRegistro", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));

    }
//End DataSourceClass_Initialize Event

//Prepare Method @2-032BD6F2
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlaut_ID", ccsInteger, "", "", $this->Parameters["urlaut_ID"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "genautsri.aut_ID", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = 
             $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @2-657C52F0
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->SQL = "SELECT genautsri.*, per_Apellidos, per_Nombres, per_Ruc  " .
        "FROM genautsri LEFT JOIN conpersonas ON " .
        "genautsri.aut_IdAuxiliar = conpersonas.per_CodAuxiliar";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->PageSize = 1;
        $this->query($this->OptimizeSQL(CCBuildSQL($this->SQL, $this->Where, $this->Order)));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
    }
//End Open Method

//SetValues Method @2-77DA13E5
    function SetValues()
    {
        $this->aut_ID->SetDBValue(trim($this->f("aut_ID")));
        $this->aut_AutSri->SetDBValue(trim($this->f("aut_AutSri")));
        $this->aut_IdAuxiliar->SetDBValue(trim($this->f("aut_IdAuxiliar")));
        $this->txt_Apellidos->SetDBValue($this->f("per_Apellidos"));
        $this->txt_Nombres->SetDBValue($this->f("per_Nombres"));
        $this->txt_Ruc->SetDBValue($this->f("per_Ruc"));
        $this->aut_TipoDocum->SetDBValue($this->f("aut_TipoDocum"));
        $this->aut_FecEmision->SetDBValue(trim($this->f("aut_FecEmision")));
        $this->aut_FecVigencia->SetDBValue(trim($this->f("aut_FecVigencia")));
        $this->aut_NroInicial->SetDBValue(trim($this->f("aut_NroInicial")));
        $this->aut_NroFinal->SetDBValue(trim($this->f("aut_NroFinal")));
        $this->aut_RucImprenta->SetDBValue($this->f("aut_RucImprenta"));
        $this->aut_AutImprenta->SetDBValue($this->f("aut_AutImprenta"));
        $this->aut_Usuario->SetDBValue($this->f("aut_Usuario"));
        $this->aut_FecRegistro->SetDBValue(trim($this->f("aut_FecRegistro")));
    }
//End SetValues Method

//Insert Method @2-B7AFF68C
    function Insert()
    {
        $this->CmdExecution = true;
        $this->cp["aut_IdAuxiliar"] = new clsSQLParameter("ctrlaut_IdAuxiliar", ccsInteger, "", "", $this->aut_IdAuxiliar->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["aut_TipoDocum"] = new clsSQLParameter("ctrlaut_TipoDocum", ccsText, "", "", $this->aut_TipoDocum->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["aut_AutSri"] = new clsSQLParameter("urlaut_ID", ccsInteger, "", "", CCGetFromGet("aut_ID", ""), -1, false, $this->ErrorBlock);
        $this->cp["aut_RucImprenta"] = new clsSQLParameter("ctrlaut_RucImprenta", ccsText, "", "", $this->aut_RucImprenta->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["aut_FecEmision"] = new clsSQLParameter("ctrlaut_FecEmision", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->aut_FecEmision->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["aut_FecVigencia"] = new clsSQLParameter("ctrlaut_FecVigencia", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->aut_FecVigencia->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["aut_NroInicial"] = new clsSQLParameter("ctrlaut_NroInicial", ccsInteger, "", "", $this->aut_NroInicial->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["aut_NroFinal"] = new clsSQLParameter("ctrlaut_NroFinal", ccsInteger, "", "", $this->aut_NroFinal->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["aut_Usuario"] = new clsSQLParameter("sesg_user", ccsText, "", "", CCGetSession("g_user"), $_SESSION['g_user'], false, $this->ErrorBlock);
        $this->cp["aut_ID"] = new clsSQLParameter("urlaut_ID", ccsInteger, "", "", CCGetFromGet("aut_ID", ""), -1, false, $this->ErrorBlock);
        $this->cp["aut_AutImprenta"] = new clsSQLParameter("ctrlaut_AutImprenta", ccsInteger, "", "", $this->aut_AutImprenta->GetValue(), 0, false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO genautsri ("
             . "aut_IdAuxiliar, "
             . "aut_TipoDocum, "
             . "aut_AutSri, "
             . "aut_RucImprenta, "
             . "aut_FecEmision, "
             . "aut_FecVigencia, "
             . "aut_NroInicial, "
             . "aut_NroFinal, "
             . "aut_Usuario, "
             . "aut_ID, "
             . "aut_AutImprenta"
             . ") VALUES ("
             . $this->ToSQL($this->cp["aut_IdAuxiliar"]->GetDBValue(), $this->cp["aut_IdAuxiliar"]->DataType) . ", "
             . $this->ToSQL($this->cp["aut_TipoDocum"]->GetDBValue(), $this->cp["aut_TipoDocum"]->DataType) . ", "
             . $this->ToSQL($this->cp["aut_AutSri"]->GetDBValue(), $this->cp["aut_AutSri"]->DataType) . ", "
             . $this->ToSQL($this->cp["aut_RucImprenta"]->GetDBValue(), $this->cp["aut_RucImprenta"]->DataType) . ", "
             . $this->ToSQL($this->cp["aut_FecEmision"]->GetDBValue(), $this->cp["aut_FecEmision"]->DataType) . ", "
             . $this->ToSQL($this->cp["aut_FecVigencia"]->GetDBValue(), $this->cp["aut_FecVigencia"]->DataType) . ", "
             . $this->ToSQL($this->cp["aut_NroInicial"]->GetDBValue(), $this->cp["aut_NroInicial"]->DataType) . ", "
             . $this->ToSQL($this->cp["aut_NroFinal"]->GetDBValue(), $this->cp["aut_NroFinal"]->DataType) . ", "
             . $this->ToSQL($this->cp["aut_Usuario"]->GetDBValue(), $this->cp["aut_Usuario"]->DataType) . ", "
             . $this->ToSQL($this->cp["aut_ID"]->GetDBValue(), $this->cp["aut_ID"]->DataType) . ", "
             . $this->ToSQL($this->cp["aut_AutImprenta"]->GetDBValue(), $this->cp["aut_AutImprenta"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        }
        $this->close();
    }
//End Insert Method

//Update Method @2-D294F026
    function Update()
    {
        $this->CmdExecution = true;
        $this->cp["aut_IdAuxiliar"] = new clsSQLParameter("ctrlaut_IdAuxiliar", ccsInteger, "", "", $this->aut_IdAuxiliar->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["aut_TipoDocum"] = new clsSQLParameter("ctrlaut_TipoDocum", ccsText, "", "", $this->aut_TipoDocum->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["aut_AutSri"] = new clsSQLParameter("urlaut_ID", ccsInteger, "", "", CCGetFromGet("aut_ID", ""), "", false, $this->ErrorBlock);
        $this->cp["aut_RucImprenta"] = new clsSQLParameter("ctrlaut_RucImprenta", ccsText, "", "", $this->aut_RucImprenta->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["aut_FecEmision"] = new clsSQLParameter("ctrlaut_FecEmision", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->aut_FecEmision->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["aut_FecVigencia"] = new clsSQLParameter("ctrlaut_FecVigencia", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->aut_FecVigencia->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["aut_NroInicial"] = new clsSQLParameter("ctrlaut_NroInicial", ccsInteger, "", "", $this->aut_NroInicial->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["aut_NroFinal"] = new clsSQLParameter("ctrlaut_NroFinal", ccsInteger, "", "", $this->aut_NroFinal->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["aut_Usuario"] = new clsSQLParameter("sesg_user", ccsText, "", "", CCGetSession("g_user"), $_SESSION['g_user'], false, $this->ErrorBlock);
        $this->cp["aut_FecRegistro"] = new clsSQLParameter("ctrlaut_FecRegistro", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->aut_FecRegistro->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["aut_AutImprenta"] = new clsSQLParameter("ctrlaut_AutImprenta", ccsFloat, "", "", $this->aut_AutImprenta->GetValue(), 0, false, $this->ErrorBlock);
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlaut_ID", ccsInteger, "", "", CCGetFromGet("aut_ID", ""), "", false);
        if(!$wp->AllParamsSet()) {
            $this->Errors->addError("");
        }
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $wp->Criterion[1] = $wp->Operation(opEqual, "aut_ID", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),false);
        $Where = 
             $wp->Criterion[1];
        $this->SQL = "UPDATE genautsri SET "
             . "aut_IdAuxiliar=" . $this->ToSQL($this->cp["aut_IdAuxiliar"]->GetDBValue(), $this->cp["aut_IdAuxiliar"]->DataType) . ", "
             . "aut_TipoDocum=" . $this->ToSQL($this->cp["aut_TipoDocum"]->GetDBValue(), $this->cp["aut_TipoDocum"]->DataType) . ", "
             . "aut_AutSri=" . $this->ToSQL($this->cp["aut_AutSri"]->GetDBValue(), $this->cp["aut_AutSri"]->DataType) . ", "
             . "aut_RucImprenta=" . $this->ToSQL($this->cp["aut_RucImprenta"]->GetDBValue(), $this->cp["aut_RucImprenta"]->DataType) . ", "
             . "aut_FecEmision=" . $this->ToSQL($this->cp["aut_FecEmision"]->GetDBValue(), $this->cp["aut_FecEmision"]->DataType) . ", "
             . "aut_FecVigencia=" . $this->ToSQL($this->cp["aut_FecVigencia"]->GetDBValue(), $this->cp["aut_FecVigencia"]->DataType) . ", "
             . "aut_NroInicial=" . $this->ToSQL($this->cp["aut_NroInicial"]->GetDBValue(), $this->cp["aut_NroInicial"]->DataType) . ", "
             . "aut_NroFinal=" . $this->ToSQL($this->cp["aut_NroFinal"]->GetDBValue(), $this->cp["aut_NroFinal"]->DataType) . ", "
             . "aut_Usuario=" . $this->ToSQL($this->cp["aut_Usuario"]->GetDBValue(), $this->cp["aut_Usuario"]->DataType) . ", "
             . "aut_FecRegistro=" . $this->ToSQL($this->cp["aut_FecRegistro"]->GetDBValue(), $this->cp["aut_FecRegistro"]->DataType) . ", "
             . "aut_AutImprenta=" . $this->ToSQL($this->cp["aut_AutImprenta"]->GetDBValue(), $this->cp["aut_AutImprenta"]->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        }
        $this->close();
    }
//End Update Method

//Delete Method @2-A8190C8A
    function Delete()
    {
        $this->CmdExecution = true;
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlaut_ID", ccsInteger, "", "", CCGetFromGet("aut_ID", ""), "", false);
        if(!$wp->AllParamsSet()) {
            $this->Errors->addError("");
        }
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $wp->Criterion[1] = $wp->Operation(opEqual, "aut_ID", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),false);
        $Where = 
             $wp->Criterion[1];
        $this->SQL = "DELETE FROM genautsri";
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        }
        $this->close();
    }
//End Delete Method

} //End genautsriDataSource Class @2-FCB6E20C

//Initialize Page @1-9FBC08E9
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

$FileName = "CoTrCo_sriautoriz.php";
$Redirect = "";
$TemplateFileName = "CoTrCo_sriautoriz.html";
$BlockToParse = "main";
$TemplateEncoding = "";
$FileEncoding = "";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-2E6CC310
$DBdatos = new clsDBdatos();

// Controls
$genautsri = new clsRecordgenautsri();
$genautsri->Initialize();

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

//Execute Components @1-93204C7B
$genautsri->Operation();
//End Execute Components

//Go to destination page @1-2C5D8FDF
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    $DBdatos->close();
    header("Location: " . $Redirect);
    unset($genautsri);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-1A97C207
$genautsri->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
$TMSENNH2F1J7C = explode("|", "<center><font f|ace=\"Arial\"><small>G|en&#101;r&#97;te&#|100; <!-- CCS -->&#|119;i&#116;h <!-- |CCS -->Cod&#101;Ch&|#97;&#114;&#103;&#10|1; <!-- SCC -->&|#83;&#116;ud&#105;&|#111;.</small></font|></center>");
if(preg_match("/<\/body>/i", $main_block)) {
    $main_block = preg_replace("/<\/body>/i", join($TMSENNH2F1J7C,"") . "</body>", $main_block);
} else if(preg_match("/<\/html>/i", $main_block) && !preg_match("/<\/frameset>/i", $main_block)) {
    $main_block = preg_replace("/<\/html>/i", join($TMSENNH2F1J7C,"") . "</html>", $main_block);
} else if(!preg_match("/<\/frameset>/i", $main_block)) {
    $main_block .= join($TMSENNH2F1J7C,"");
}
echo $main_block;
//End Show Page

//Unload Page @1-8B9ECBEF
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBdatos->close();
unset($genautsri);
unset($Tpl);
//End Unload Page


?>
