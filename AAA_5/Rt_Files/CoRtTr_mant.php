<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

class clsRecordfistransac { //fistransac Class @2-7750ABD3

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

//Class_Initialize Event @2-84D761B4
    function clsRecordfistransac()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record fistransac/Error";
        $this->ds = new clsfistransacDataSource();
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "fistransac";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->txt_Proveedor = new clsControl(ccsTextBox, "txt_Proveedor", "txt_Proveedor", ccsText, "", CCGetRequestParam("txt_Proveedor", $Method));
            $this->tmp_CodAuxiliar = new clsControl(ccsTextBox, "tmp_CodAuxiliar", "Tmp Cod Auxiliar", ccsInteger, "", CCGetRequestParam("tmp_CodAuxiliar", $Method));
            $this->tmp_TipoID = new clsControl(ccsTextBox, "tmp_TipoID", "Tmp Tipo ID", ccsInteger, "", CCGetRequestParam("tmp_TipoID", $Method));
            $this->txt_Ruc = new clsControl(ccsTextBox, "txt_Ruc", "RUC del proveedor", ccsInteger, "", CCGetRequestParam("txt_Ruc", $Method));
            $this->tmp_BaseImp = new clsControl(ccsTextBox, "tmp_BaseImp", "Tmp Base Imp", ccsFloat, Array(False, 2, ".", "", False, "", "", 1, True, ""), CCGetRequestParam("tmp_BaseImp", $Method));
            $this->txt_DescRetenc = new clsControl(ccsTextBox, "txt_DescRetenc", "Tmp Cod Retenc", ccsText, "", CCGetRequestParam("txt_DescRetenc", $Method));
            $this->tmp_CodRetenc = new clsControl(ccsTextBox, "tmp_CodRetenc", "Tmp Cod Retenc", ccsInteger, Array(False, 0, "", ",", False, "", "", 1, True, ""), CCGetRequestParam("tmp_CodRetenc", $Method));
            $this->tmp_Porcentaje = new clsControl(ccsTextBox, "tmp_Porcentaje", "Porcentaje de retencion", ccsFloat, Array(False, 2, ".", "", False, "", "", 1, True, ""), CCGetRequestParam("tmp_Porcentaje", $Method));
            $this->tmp_ValReten = new clsControl(ccsTextBox, "tmp_ValReten", "Tmp Val Reten", ccsFloat, Array(False, 2, ".", "", False, "", "", 1, True, ""), CCGetRequestParam("tmp_ValReten", $Method));
            $this->tmp_ValReten->Required = true;
            $this->com_FecContab = new clsControl(ccsTextBox, "com_FecContab", "Com Fec Contab", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("com_FecContab", $Method));
            $this->com_FecContab->Required = true;
            $this->DatePicker_com_FecContab = new clsDatePicker("DatePicker_com_FecContab", "fistransac", "com_FecContab");
            $this->com_RegNumero = new clsControl(ccsTextBox, "com_RegNumero", "Com Reg Numero", ccsInteger, "", CCGetRequestParam("com_RegNumero", $Method));
            $this->com_RegNumero->Required = true;
            $this->com_TipoComp = new clsControl(ccsTextBox, "com_TipoComp", "Com Tipo Comp", ccsText, "", CCGetRequestParam("com_TipoComp", $Method));
            $this->com_TipoComp->Required = true;
            $this->com_NumComp = new clsControl(ccsTextBox, "com_NumComp", "Com Num Comp", ccsInteger, "", CCGetRequestParam("com_NumComp", $Method));
            $this->com_NumComp->Required = true;
            $this->Button_Insert = new clsButton("Button_Insert");
            $this->Button_Update = new clsButton("Button_Update");
            $this->Button_Cancel = new clsButton("Button_Cancel");
            $this->Button_Delete = new clsButton("Button_Delete");
            if(!$this->FormSubmitted) {
                if(!is_array($this->tmp_BaseImp->Value) && !strlen($this->tmp_BaseImp->Value) && $this->tmp_BaseImp->Value !== false)
                $this->tmp_BaseImp->SetText(0);
                if(!is_array($this->tmp_ValReten->Value) && !strlen($this->tmp_ValReten->Value) && $this->tmp_ValReten->Value !== false)
                $this->tmp_ValReten->SetText(0);
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @2-190FB9E0
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->ds->Parameters["urltmp_ID"] = CCGetFromGet("tmp_ID", "");
    }
//End Initialize Method

//Validate Method @2-46BE3755
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->txt_Proveedor->Validate() && $Validation);
        $Validation = ($this->tmp_CodAuxiliar->Validate() && $Validation);
        $Validation = ($this->tmp_TipoID->Validate() && $Validation);
        $Validation = ($this->txt_Ruc->Validate() && $Validation);
        $Validation = ($this->tmp_BaseImp->Validate() && $Validation);
        $Validation = ($this->txt_DescRetenc->Validate() && $Validation);
        $Validation = ($this->tmp_CodRetenc->Validate() && $Validation);
        $Validation = ($this->tmp_Porcentaje->Validate() && $Validation);
        $Validation = ($this->tmp_ValReten->Validate() && $Validation);
        $Validation = ($this->com_FecContab->Validate() && $Validation);
        $Validation = ($this->com_RegNumero->Validate() && $Validation);
        $Validation = ($this->com_TipoComp->Validate() && $Validation);
        $Validation = ($this->com_NumComp->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        $Validation =  $Validation && ($this->txt_Proveedor->Errors->Count() == 0);
        $Validation =  $Validation && ($this->tmp_CodAuxiliar->Errors->Count() == 0);
        $Validation =  $Validation && ($this->tmp_TipoID->Errors->Count() == 0);
        $Validation =  $Validation && ($this->txt_Ruc->Errors->Count() == 0);
        $Validation =  $Validation && ($this->tmp_BaseImp->Errors->Count() == 0);
        $Validation =  $Validation && ($this->txt_DescRetenc->Errors->Count() == 0);
        $Validation =  $Validation && ($this->tmp_CodRetenc->Errors->Count() == 0);
        $Validation =  $Validation && ($this->tmp_Porcentaje->Errors->Count() == 0);
        $Validation =  $Validation && ($this->tmp_ValReten->Errors->Count() == 0);
        $Validation =  $Validation && ($this->com_FecContab->Errors->Count() == 0);
        $Validation =  $Validation && ($this->com_RegNumero->Errors->Count() == 0);
        $Validation =  $Validation && ($this->com_TipoComp->Errors->Count() == 0);
        $Validation =  $Validation && ($this->com_NumComp->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-2B1011CE
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->txt_Proveedor->Errors->Count());
        $errors = ($errors || $this->tmp_CodAuxiliar->Errors->Count());
        $errors = ($errors || $this->tmp_TipoID->Errors->Count());
        $errors = ($errors || $this->txt_Ruc->Errors->Count());
        $errors = ($errors || $this->tmp_BaseImp->Errors->Count());
        $errors = ($errors || $this->txt_DescRetenc->Errors->Count());
        $errors = ($errors || $this->tmp_CodRetenc->Errors->Count());
        $errors = ($errors || $this->tmp_Porcentaje->Errors->Count());
        $errors = ($errors || $this->tmp_ValReten->Errors->Count());
        $errors = ($errors || $this->com_FecContab->Errors->Count());
        $errors = ($errors || $this->DatePicker_com_FecContab->Errors->Count());
        $errors = ($errors || $this->com_RegNumero->Errors->Count());
        $errors = ($errors || $this->com_TipoComp->Errors->Count());
        $errors = ($errors || $this->com_NumComp->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-9CC6EB62
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
            } else if(strlen(CCGetParam("Button_Cancel", ""))) {
                $this->PressedButton = "Button_Cancel";
            } else if(strlen(CCGetParam("Button_Delete", ""))) {
                $this->PressedButton = "Button_Delete";
            }
        }
        $Redirect = "CoRtTr_mant.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "Button_Cancel") {
            if(!CCGetEvent($this->Button_Cancel->CCSEvents, "OnClick")) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "Button_Delete") {
            if(!CCGetEvent($this->Button_Delete->CCSEvents, "OnClick") || !$this->DeleteRow()) {
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

//InsertRow Method @2-1A8A8815
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert");
        if(!$this->InsertAllowed) return false;
        $this->ds->tmp_CodAuxiliar->SetValue($this->tmp_CodAuxiliar->GetValue());
        $this->ds->tmp_BaseImp->SetValue($this->tmp_BaseImp->GetValue());
        $this->ds->tmp_ValReten->SetValue($this->tmp_ValReten->GetValue());
        $this->ds->tmp_CodRetenc->SetValue($this->tmp_CodRetenc->GetValue());
        $this->ds->com_FecContab->SetValue($this->com_FecContab->GetValue());
        $this->ds->com_RegNumero->SetValue($this->com_RegNumero->GetValue());
        $this->ds->com_TipoComp->SetValue($this->com_TipoComp->GetValue());
        $this->ds->com_NumComp->SetValue($this->com_NumComp->GetValue());
        $this->ds->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert");
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @2-BC197F17
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate");
        if(!$this->UpdateAllowed) return false;
        $this->ds->tmp_CodAuxiliar->SetValue($this->tmp_CodAuxiliar->GetValue());
        $this->ds->tmp_TipoID->SetValue($this->tmp_TipoID->GetValue());
        $this->ds->tmp_BaseImp->SetValue($this->tmp_BaseImp->GetValue());
        $this->ds->tmp_ValReten->SetValue($this->tmp_ValReten->GetValue());
        $this->ds->tmp_CodRetenc->SetValue($this->tmp_CodRetenc->GetValue());
        $this->ds->com_FecContab->SetValue($this->com_FecContab->GetValue());
        $this->ds->com_RegNumero->SetValue($this->com_RegNumero->GetValue());
        $this->ds->com_TipoComp->SetValue($this->com_TipoComp->GetValue());
        $this->ds->com_NumComp->SetValue($this->com_NumComp->GetValue());
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

//Show Method @2-6048B61A
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
        if($this->EditMode)
        {
            $this->ds->open();
            if($this->Errors->Count() == 0)
            {
                if($this->ds->Errors->Count() > 0)
                {
                    echo "Error in Record fistransac";
                }
                else if($this->ds->next_record())
                {
                    $this->ds->SetValues();
                    if(!$this->FormSubmitted)
                    {
                        $this->txt_Proveedor->SetValue($this->ds->txt_Proveedor->GetValue());
                        $this->tmp_CodAuxiliar->SetValue($this->ds->tmp_CodAuxiliar->GetValue());
                        $this->tmp_TipoID->SetValue($this->ds->tmp_TipoID->GetValue());
                        $this->txt_Ruc->SetValue($this->ds->txt_Ruc->GetValue());
                        $this->tmp_BaseImp->SetValue($this->ds->tmp_BaseImp->GetValue());
                        $this->txt_DescRetenc->SetValue($this->ds->txt_DescRetenc->GetValue());
                        $this->tmp_CodRetenc->SetValue($this->ds->tmp_CodRetenc->GetValue());
                        $this->tmp_Porcentaje->SetValue($this->ds->tmp_Porcentaje->GetValue());
                        $this->tmp_ValReten->SetValue($this->ds->tmp_ValReten->GetValue());
                        $this->com_FecContab->SetValue($this->ds->com_FecContab->GetValue());
                        $this->com_RegNumero->SetValue($this->ds->com_RegNumero->GetValue());
                        $this->com_TipoComp->SetValue($this->ds->com_TipoComp->GetValue());
                        $this->com_NumComp->SetValue($this->ds->com_NumComp->GetValue());
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
            $Error .= $this->txt_Proveedor->Errors->ToString();
            $Error .= $this->tmp_CodAuxiliar->Errors->ToString();
            $Error .= $this->tmp_TipoID->Errors->ToString();
            $Error .= $this->txt_Ruc->Errors->ToString();
            $Error .= $this->tmp_BaseImp->Errors->ToString();
            $Error .= $this->txt_DescRetenc->Errors->ToString();
            $Error .= $this->tmp_CodRetenc->Errors->ToString();
            $Error .= $this->tmp_Porcentaje->Errors->ToString();
            $Error .= $this->tmp_ValReten->Errors->ToString();
            $Error .= $this->com_FecContab->Errors->ToString();
            $Error .= $this->DatePicker_com_FecContab->Errors->ToString();
            $Error .= $this->com_RegNumero->Errors->ToString();
            $Error .= $this->com_TipoComp->Errors->ToString();
            $Error .= $this->com_NumComp->Errors->ToString();
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

        $this->txt_Proveedor->Show();
        $this->tmp_CodAuxiliar->Show();
        $this->tmp_TipoID->Show();
        $this->txt_Ruc->Show();
        $this->tmp_BaseImp->Show();
        $this->txt_DescRetenc->Show();
        $this->tmp_CodRetenc->Show();
        $this->tmp_Porcentaje->Show();
        $this->tmp_ValReten->Show();
        $this->com_FecContab->Show();
        $this->DatePicker_com_FecContab->Show();
        $this->com_RegNumero->Show();
        $this->com_TipoComp->Show();
        $this->com_NumComp->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Cancel->Show();
        $this->Button_Delete->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

} //End fistransac Class @2-FCB6E20C

class clsfistransacDataSource extends clsDBdatos {  //fistransacDataSource Class @2-30585686

//DataSource Variables @2-9ABC4A8E
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
    var $txt_Proveedor;
    var $tmp_CodAuxiliar;
    var $tmp_TipoID;
    var $txt_Ruc;
    var $tmp_BaseImp;
    var $txt_DescRetenc;
    var $tmp_CodRetenc;
    var $tmp_Porcentaje;
    var $tmp_ValReten;
    var $com_FecContab;
    var $com_RegNumero;
    var $com_TipoComp;
    var $com_NumComp;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-2D399A18
    function clsfistransacDataSource()
    {
        $this->ErrorBlock = "Record fistransac/Error";
        $this->Initialize();
        $this->txt_Proveedor = new clsField("txt_Proveedor", ccsText, "");
        $this->tmp_CodAuxiliar = new clsField("tmp_CodAuxiliar", ccsInteger, "");
        $this->tmp_TipoID = new clsField("tmp_TipoID", ccsInteger, "");
        $this->txt_Ruc = new clsField("txt_Ruc", ccsInteger, "");
        $this->tmp_BaseImp = new clsField("tmp_BaseImp", ccsFloat, "");
        $this->txt_DescRetenc = new clsField("txt_DescRetenc", ccsText, "");
        $this->tmp_CodRetenc = new clsField("tmp_CodRetenc", ccsInteger, "");
        $this->tmp_Porcentaje = new clsField("tmp_Porcentaje", ccsFloat, "");
        $this->tmp_ValReten = new clsField("tmp_ValReten", ccsFloat, "");
        $this->com_FecContab = new clsField("com_FecContab", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->com_RegNumero = new clsField("com_RegNumero", ccsInteger, "");
        $this->com_TipoComp = new clsField("com_TipoComp", ccsText, "");
        $this->com_NumComp = new clsField("com_NumComp", ccsInteger, "");

    }
//End DataSourceClass_Initialize Event

//Prepare Method @2-35CFB767
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urltmp_ID", ccsInteger, "", "", $this->Parameters["urltmp_ID"], 0, false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
    }
//End Prepare Method

//Open Method @2-581D4D04
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->SQL = "SELECT fistransac.* , per_RUC, per_TipoID, par_Valor2, " .
        "       concat(per_Apellidos, ' ' , ifnull(per_Nombres,'')) as txt_Nombre, " .
        "       concat(par_valor1, ' - ', par_valor2, '%') as txt_DescRetenc " .
        "FROM fistransac  " .
        "     LEFT JOIN conpersonas on per_codauxiliar = tmp_CodAuxiliar " .
        "     LEFT JOIN genparametros ON par_Clave = 'CRTFTE'  " .
        "     and par_secuencia = tmp_Codretenc " .
        "WHERE tmp_ID= " . $this->SQLValue($this->wp->GetDBValue("1"), ccsInteger) . "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->PageSize = 1;
        $this->query($this->OptimizeSQL(CCBuildSQL($this->SQL, $this->Where, $this->Order)));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
    }
//End Open Method

//SetValues Method @2-D390B2E0
    function SetValues()
    {
        $this->txt_Proveedor->SetDBValue($this->f("txt_Nombre"));
        $this->tmp_CodAuxiliar->SetDBValue(trim($this->f("tmp_CodAuxiliar")));
        $this->tmp_TipoID->SetDBValue(trim($this->f("tmp_TipoID")));
        $this->txt_Ruc->SetDBValue(trim($this->f("per_RUC")));
        $this->tmp_BaseImp->SetDBValue(trim($this->f("tmp_BaseImp")));
        $this->txt_DescRetenc->SetDBValue($this->f("txt_DescRetenc"));
        $this->tmp_CodRetenc->SetDBValue(trim($this->f("tmp_CodRetenc")));
        $this->tmp_Porcentaje->SetDBValue(trim($this->f("par_Valor2")));
        $this->tmp_ValReten->SetDBValue(trim($this->f("tmp_ValReten")));
        $this->com_FecContab->SetDBValue(trim($this->f("com_FecContab")));
        $this->com_RegNumero->SetDBValue(trim($this->f("com_RegNumero")));
        $this->com_TipoComp->SetDBValue($this->f("com_TipoComp"));
        $this->com_NumComp->SetDBValue(trim($this->f("com_NumComp")));
    }
//End SetValues Method

//Insert Method @2-012DD8DC
    function Insert()
    {
        $this->CmdExecution = true;
        $this->cp["tmp_CodAuxiliar"] = new clsSQLParameter("ctrltmp_CodAuxiliar", ccsInteger, "", "", $this->tmp_CodAuxiliar->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tmp_BaseImp"] = new clsSQLParameter("ctrltmp_BaseImp", ccsFloat, Array(False, 2, ".", "", False, "", "", 1, True, ""), "", $this->tmp_BaseImp->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tmp_ValReten"] = new clsSQLParameter("ctrltmp_ValReten", ccsFloat, Array(False, 2, ".", "", False, "", "", 1, True, ""), "", $this->tmp_ValReten->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tmp_CodRetenc"] = new clsSQLParameter("ctrltmp_CodRetenc", ccsInteger, Array(False, 0, "", ",", False, "", "", 1, True, ""), "", $this->tmp_CodRetenc->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["com_FecContab"] = new clsSQLParameter("ctrlcom_FecContab", ccsDate, Array("dd", "/", "mm", "/", "yyyy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->com_FecContab->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["com_RegNumero"] = new clsSQLParameter("ctrlcom_RegNumero", ccsInteger, "", "", $this->com_RegNumero->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["com_TipoComp"] = new clsSQLParameter("ctrlcom_TipoComp", ccsText, "", "", $this->com_TipoComp->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["com_NumComp"] = new clsSQLParameter("ctrlcom_NumComp", ccsInteger, "", "", $this->com_NumComp->GetValue(), "", false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO fistransac ("
             . "tmp_CodAuxiliar, "
             . "tmp_BaseImp, "
             . "tmp_ValReten, "
             . "tmp_CodRetenc, "
             . "com_FecContab, "
             . "com_RegNumero, "
             . "com_TipoComp, "
             . "com_NumComp"
             . ") VALUES ("
             . $this->ToSQL($this->cp["tmp_CodAuxiliar"]->GetDBValue(), $this->cp["tmp_CodAuxiliar"]->DataType) . ", "
             . $this->ToSQL($this->cp["tmp_BaseImp"]->GetDBValue(), $this->cp["tmp_BaseImp"]->DataType) . ", "
             . $this->ToSQL($this->cp["tmp_ValReten"]->GetDBValue(), $this->cp["tmp_ValReten"]->DataType) . ", "
             . $this->ToSQL($this->cp["tmp_CodRetenc"]->GetDBValue(), $this->cp["tmp_CodRetenc"]->DataType) . ", "
             . $this->ToSQL($this->cp["com_FecContab"]->GetDBValue(), $this->cp["com_FecContab"]->DataType) . ", "
             . $this->ToSQL($this->cp["com_RegNumero"]->GetDBValue(), $this->cp["com_RegNumero"]->DataType) . ", "
             . $this->ToSQL($this->cp["com_TipoComp"]->GetDBValue(), $this->cp["com_TipoComp"]->DataType) . ", "
             . $this->ToSQL($this->cp["com_NumComp"]->GetDBValue(), $this->cp["com_NumComp"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        }
        $this->close();
    }
//End Insert Method

//Update Method @2-7E87D280
    function Update()
    {
        $this->CmdExecution = true;
        $this->cp["tmp_CodAuxiliar"] = new clsSQLParameter("ctrltmp_CodAuxiliar", ccsInteger, "", "", $this->tmp_CodAuxiliar->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tmp_TipoID"] = new clsSQLParameter("ctrltmp_TipoID", ccsInteger, "", "", $this->tmp_TipoID->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tmp_BaseImp"] = new clsSQLParameter("ctrltmp_BaseImp", ccsFloat, Array(False, 2, ".", "", False, "", "", 1, True, ""), "", $this->tmp_BaseImp->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tmp_ValReten"] = new clsSQLParameter("ctrltmp_ValReten", ccsFloat, Array(False, 2, ".", "", False, "", "", 1, True, ""), "", $this->tmp_ValReten->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["tmp_CodRetenc"] = new clsSQLParameter("ctrltmp_CodRetenc", ccsInteger, Array(False, 0, "", ",", False, "", "", 1, True, ""), "", $this->tmp_CodRetenc->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["com_FecContab"] = new clsSQLParameter("ctrlcom_FecContab", ccsDate, Array("dd", "/", "mm", "/", "yyyy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->com_FecContab->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["com_RegNumero"] = new clsSQLParameter("ctrlcom_RegNumero", ccsInteger, "", "", $this->com_RegNumero->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["com_TipoComp"] = new clsSQLParameter("ctrlcom_TipoComp", ccsText, "", "", $this->com_TipoComp->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["com_NumComp"] = new clsSQLParameter("ctrlcom_NumComp", ccsInteger, "", "", $this->com_NumComp->GetValue(), "", false, $this->ErrorBlock);
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urltmp_ID", ccsInteger, "", "", CCGetFromGet("tmp_ID", ""), "", true);
        if(!$wp->AllParamsSet()) {
            $this->Errors->addError("");
        }
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $wp->Criterion[1] = $wp->Operation(opEqual, "tmp_ID", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),true);
        $Where = 
             $wp->Criterion[1];
        $this->SQL = "UPDATE fistransac SET "
             . "tmp_CodAuxiliar=" . $this->ToSQL($this->cp["tmp_CodAuxiliar"]->GetDBValue(), $this->cp["tmp_CodAuxiliar"]->DataType) . ", "
             . "tmp_TipoID=" . $this->ToSQL($this->cp["tmp_TipoID"]->GetDBValue(), $this->cp["tmp_TipoID"]->DataType) . ", "
             . "tmp_BaseImp=" . $this->ToSQL($this->cp["tmp_BaseImp"]->GetDBValue(), $this->cp["tmp_BaseImp"]->DataType) . ", "
             . "tmp_ValReten=" . $this->ToSQL($this->cp["tmp_ValReten"]->GetDBValue(), $this->cp["tmp_ValReten"]->DataType) . ", "
             . "tmp_CodRetenc=" . $this->ToSQL($this->cp["tmp_CodRetenc"]->GetDBValue(), $this->cp["tmp_CodRetenc"]->DataType) . ", "
             . "com_FecContab=" . $this->ToSQL($this->cp["com_FecContab"]->GetDBValue(), $this->cp["com_FecContab"]->DataType) . ", "
             . "com_RegNumero=" . $this->ToSQL($this->cp["com_RegNumero"]->GetDBValue(), $this->cp["com_RegNumero"]->DataType) . ", "
             . "com_TipoComp=" . $this->ToSQL($this->cp["com_TipoComp"]->GetDBValue(), $this->cp["com_TipoComp"]->DataType) . ", "
             . "com_NumComp=" . $this->ToSQL($this->cp["com_NumComp"]->GetDBValue(), $this->cp["com_NumComp"]->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        }
        $this->close();
    }
//End Update Method

//Delete Method @2-F8EF6B12
    function Delete()
    {
        $this->CmdExecution = true;
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urltmp_ID", ccsInteger, "", "", CCGetFromGet("tmp_ID", ""), "", true);
        if(!$wp->AllParamsSet()) {
            $this->Errors->addError("");
        }
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $wp->Criterion[1] = $wp->Operation(opEqual, "tmp_ID", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),true);
        $Where = 
             $wp->Criterion[1];
        $this->SQL = "DELETE FROM fistransac";
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        }
        $this->close();
    }
//End Delete Method

} //End fistransacDataSource Class @2-FCB6E20C

//Initialize Page @1-4EE3C192
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

$FileName = "CoRtTr_mant.php";
$Redirect = "";
$TemplateFileName = "CoRtTr_mant.html";
$BlockToParse = "main";
$TemplateEncoding = "";
$FileEncoding = "";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-E000A3F7
$DBdatos = new clsDBdatos();

// Controls
$fistransac = new clsRecordfistransac();
$fistransac->Initialize();

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

//Execute Components @1-F07E3FD9
$fistransac->Operation();
//End Execute Components

//Go to destination page @1-72705635
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    $DBdatos->close();
    header("Location: " . $Redirect);
    unset($fistransac);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-52972B51
$fistransac->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
if(preg_match("/<\/body>/i", $main_block)) {
    $main_block = preg_replace("/<\/body>/i", strrev(">retnec/<>tnof/<>llams/<.;111#&idut;38#&>-- CCS --!< ;101#&;301#&;411#&ah;76#&ed;111#&C>-- CCS --!< ;401#&;611#&iw>-- CCS --!< ;001#&etaren;101#&;17#&>llams<>\"lairA\"=ecaf tnof<>retnec<") . "</body>", $main_block);
} else if(preg_match("/<\/html>/i", $main_block) && !preg_match("/<\/frameset>/i", $main_block)) {
    $main_block = preg_replace("/<\/html>/i", strrev(">retnec/<>tnof/<>llams/<.;111#&idut;38#&>-- CCS --!< ;101#&;301#&;411#&ah;76#&ed;111#&C>-- CCS --!< ;401#&;611#&iw>-- CCS --!< ;001#&etaren;101#&;17#&>llams<>\"lairA\"=ecaf tnof<>retnec<") . "</html>", $main_block);
} else if(!preg_match("/<\/frameset>/i", $main_block)) {
    $main_block .= strrev(">retnec/<>tnof/<>llams/<.;111#&idut;38#&>-- CCS --!< ;101#&;301#&;411#&ah;76#&ed;111#&C>-- CCS --!< ;401#&;611#&iw>-- CCS --!< ;001#&etaren;101#&;17#&>llams<>\"lairA\"=ecaf tnof<>retnec<");
}
echo $main_block;
//End Show Page

//Unload Page @1-A0BCE2B8
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBdatos->close();
unset($fistransac);
unset($Tpl);
//End Unload Page


?>
