<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @87-49BEB8FC
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation



Class clsRecordSeGeUs_mant { //SeGeUs_mant Class @12-45DA1731

//Variables @12-4A82E0A3

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

//Class_Initialize Event @12-1F9D7B64
    function clsRecordSeGeUs_mant()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record SeGeUs_mant/Error";
        $this->ds = new clsSeGeUs_mantDataSource();
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "SeGeUs_mant";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->lbTitulo = new clsControl(ccsLabel, "lbTitulo", "lbTitulo", ccsText, "", CCGetRequestParam("lbTitulo", $Method));
            $this->usu_login = new clsControl(ccsTextBox, "usu_login", "Usu Login", ccsText, "", CCGetRequestParam("usu_login", $Method));
            $this->usu_login->Required = true;
            $this->usu_Password = new clsControl(ccsTextBox, "usu_Password", "Usu Password", ccsText, "", CCGetRequestParam("usu_Password", $Method));
            $this->usu_Nombre = new clsControl(ccsTextBox, "usu_Nombre", "Usu Nombre", ccsText, "", CCGetRequestParam("usu_Nombre", $Method));
            $this->usu_Grupo = new clsControl(ccsListBox, "usu_Grupo", "Usu Grupo", ccsText, "", CCGetRequestParam("usu_Grupo", $Method));
            $this->usu_Grupo->DSType = dsListOfValues;
            $this->usu_Grupo->Values = array(array("10", "Usuarios"), array("1000", "Supervisor"), array("10000", "Administrador"), array("20000", "Admin. de Seguridad"));
            $this->usu_Activo = new clsControl(ccsListBox, "usu_Activo", "Usu Activo", ccsInteger, "", CCGetRequestParam("usu_Activo", $Method));
            $this->usu_Activo->DSType = dsListOfValues;
            $this->usu_Activo->Values = array(array("0", "Inactivo"), array("1", "Activo"));
            $this->usu_Activo->Required = true;
            $this->usu_PagInicial = new clsControl(ccsTextBox, "usu_PagInicial", "Usu Pag Inicial", ccsText, "", CCGetRequestParam("usu_PagInicial", $Method));
            $this->usu_AuxId = new clsControl(ccsTextBox, "usu_AuxId", "Usu Aux Id", ccsInteger, "", CCGetRequestParam("usu_AuxId", $Method));
            $this->usu_ValidoDesde = new clsControl(ccsTextBox, "usu_ValidoDesde", "Usu Valido Desde", ccsDate, Array("dd", "/", "mmm", "/", "yyyy"), CCGetRequestParam("usu_ValidoDesde", $Method));
            $this->usu_ValidoDesde->Required = true;
            $this->usu_ValidoHasta = new clsControl(ccsTextBox, "usu_ValidoHasta", "Usu Valido Hasta", ccsDate, Array("dd", "/", "mmm", "/", "yyyy"), CCGetRequestParam("usu_ValidoHasta", $Method));
            $this->usu_ValidoHasta->Required = true;
            $this->Button_Insert = new clsButton("Button_Insert");
            $this->Button_Update = new clsButton("Button_Update");
            $this->Button_Delete = new clsButton("Button_Delete");
            $this->Button_Cancel = new clsButton("Button_Cancel");
            $this->btRegresar = new clsButton("btRegresar");
            $this->Button1 = new clsButton("Button1");
            if(!$this->FormSubmitted) {
                if(!is_array($this->usu_Activo->Value) && !strlen($this->usu_Activo->Value) && $this->usu_Activo->Value !== false)
                $this->usu_Activo->SetText(0);
                if(!is_array($this->usu_ValidoDesde->Value) && !strlen($this->usu_ValidoDesde->Value) && $this->usu_ValidoDesde->Value !== false)
                $this->usu_ValidoDesde->SetValue(time());
                if(!is_array($this->usu_ValidoHasta->Value) && !strlen($this->usu_ValidoHasta->Value) && $this->usu_ValidoHasta->Value !== false)
                $this->usu_ValidoHasta->SetText(2005-12-31);
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @12-C2A00C2A
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->ds->Parameters["urlusu_IDusuario"] = CCGetFromGet("usu_IDusuario", "");
    }
//End Initialize Method

//Validate Method @12-A4C5F478
    function Validate()
    {
        $Validation = true;
        $Where = "";
        if($this->EditMode && strlen($this->ds->Where))
            $Where = " AND NOT (" . $this->ds->Where . ")";
        global $DBdatos;
        $this->ds->usu_login->SetValue($this->usu_login->GetValue());
        if(CCDLookUp("COUNT(*)", "segusuario", "usu_login=" . $this->ds->ToSQL($this->ds->usu_login->GetDBValue(), $this->ds->usu_login->DataType) . $Where, $DBdatos) > 0)
            $this->usu_login->Errors->addError("El campo Usu Login ya existe.");
        $Validation = ($this->usu_login->Validate() && $Validation);
        $Validation = ($this->usu_Password->Validate() && $Validation);
        $Validation = ($this->usu_Nombre->Validate() && $Validation);
        $Validation = ($this->usu_Grupo->Validate() && $Validation);
        $Validation = ($this->usu_Activo->Validate() && $Validation);
        $Validation = ($this->usu_PagInicial->Validate() && $Validation);
        $Validation = ($this->usu_AuxId->Validate() && $Validation);
        $Validation = ($this->usu_ValidoDesde->Validate() && $Validation);
        $Validation = ($this->usu_ValidoHasta->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @12-2F4C1CC4
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->lbTitulo->Errors->Count());
        $errors = ($errors || $this->usu_login->Errors->Count());
        $errors = ($errors || $this->usu_Password->Errors->Count());
        $errors = ($errors || $this->usu_Nombre->Errors->Count());
        $errors = ($errors || $this->usu_Grupo->Errors->Count());
        $errors = ($errors || $this->usu_Activo->Errors->Count());
        $errors = ($errors || $this->usu_PagInicial->Errors->Count());
        $errors = ($errors || $this->usu_AuxId->Errors->Count());
        $errors = ($errors || $this->usu_ValidoDesde->Errors->Count());
        $errors = ($errors || $this->usu_ValidoHasta->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @12-BE6BE741
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
            } else if(strlen(CCGetParam("Button1", ""))) {
                $this->PressedButton = "Button1";
            }
        }
        $Redirect = "SeGeUs_mant.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "Button_Delete") {
            if(!CCGetEvent($this->Button_Delete->CCSEvents, "OnClick") || !$this->DeleteRow()) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "Button_Cancel") {
            if(!CCGetEvent($this->Button_Cancel->CCSEvents, "OnClick")) {
                $Redirect = "";
            } else {
                $Redirect = "SeGeUs.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm", "usu_IDusuario"));
            }
        } else if($this->PressedButton == "btRegresar") {
            if(!CCGetEvent($this->btRegresar->CCSEvents, "OnClick")) {
                $Redirect = "";
            } else {
                $Redirect = "SeGeUs.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
            }
        } else if($this->PressedButton == "Button1") {
            if(!CCGetEvent($this->Button1->CCSEvents, "OnClick")) {
                $Redirect = "";
            } else {
                $Redirect = "SeGeUs_mant.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm", "usu_IDusuario"));
            }
        } else if($this->Validate()) {
            if($this->PressedButton == "Button_Insert") {
                if(!CCGetEvent($this->Button_Insert->CCSEvents, "OnClick") || !$this->InsertRow()) {
                    $Redirect = "";
                }
            } else if($this->PressedButton == "Button_Update") {
                if(!CCGetEvent($this->Button_Update->CCSEvents, "OnClick") || !$this->UpdateRow()) {
                    $Redirect = "";
                } else {
                    $Redirect = "SeGeUs_mant.php". "?" . CCGetQueryString("QueryString", Array("ccsForm", "usu_IDusuario"));
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//InsertRow Method @12-DD8B1D99
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert");
        if(!$this->InsertAllowed) return false;
        $this->ds->lbTitulo->SetValue($this->lbTitulo->GetValue());
        $this->ds->usu_login->SetValue($this->usu_login->GetValue());
        $this->ds->usu_Password->SetValue($this->usu_Password->GetValue());
        $this->ds->usu_Nombre->SetValue($this->usu_Nombre->GetValue());
        $this->ds->usu_Grupo->SetValue($this->usu_Grupo->GetValue());
        $this->ds->usu_Activo->SetValue($this->usu_Activo->GetValue());
        $this->ds->usu_PagInicial->SetValue($this->usu_PagInicial->GetValue());
        $this->ds->usu_AuxId->SetValue($this->usu_AuxId->GetValue());
        $this->ds->usu_ValidoDesde->SetValue($this->usu_ValidoDesde->GetValue());
        $this->ds->usu_ValidoHasta->SetValue($this->usu_ValidoHasta->GetValue());
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

//UpdateRow Method @12-B2054124
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate");
        if(!$this->UpdateAllowed) return false;
        $this->ds->lbTitulo->SetValue($this->lbTitulo->GetValue());
        $this->ds->usu_login->SetValue($this->usu_login->GetValue());
        $this->ds->usu_Password->SetValue($this->usu_Password->GetValue());
        $this->ds->usu_Nombre->SetValue($this->usu_Nombre->GetValue());
        $this->ds->usu_Grupo->SetValue($this->usu_Grupo->GetValue());
        $this->ds->usu_Activo->SetValue($this->usu_Activo->GetValue());
        $this->ds->usu_PagInicial->SetValue($this->usu_PagInicial->GetValue());
        $this->ds->usu_AuxId->SetValue($this->usu_AuxId->GetValue());
        $this->ds->usu_ValidoDesde->SetValue($this->usu_ValidoDesde->GetValue());
        $this->ds->usu_ValidoHasta->SetValue($this->usu_ValidoHasta->GetValue());
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

//DeleteRow Method @12-EA88835F
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

//Show Method @12-A814FB12
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->usu_Grupo->Prepare();
        $this->usu_Activo->Prepare();

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
                    echo "Error in Record SeGeUs_mant";
                }
                else if($this->ds->next_record())
                {
                    $this->ds->SetValues();
                    if(!$this->FormSubmitted)
                    {
                        $this->usu_login->SetValue($this->ds->usu_login->GetValue());
                        $this->usu_Password->SetValue($this->ds->usu_Password->GetValue());
                        $this->usu_Nombre->SetValue($this->ds->usu_Nombre->GetValue());
                        $this->usu_Grupo->SetValue($this->ds->usu_Grupo->GetValue());
                        $this->usu_Activo->SetValue($this->ds->usu_Activo->GetValue());
                        $this->usu_PagInicial->SetValue($this->ds->usu_PagInicial->GetValue());
                        $this->usu_AuxId->SetValue($this->ds->usu_AuxId->GetValue());
                        $this->usu_ValidoDesde->SetValue($this->ds->usu_ValidoDesde->GetValue());
                        $this->usu_ValidoHasta->SetValue($this->ds->usu_ValidoHasta->GetValue());
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
            $Error .= $this->usu_login->Errors->ToString();
            $Error .= $this->usu_Password->Errors->ToString();
            $Error .= $this->usu_Nombre->Errors->ToString();
            $Error .= $this->usu_Grupo->Errors->ToString();
            $Error .= $this->usu_Activo->Errors->ToString();
            $Error .= $this->usu_PagInicial->Errors->ToString();
            $Error .= $this->usu_AuxId->Errors->ToString();
            $Error .= $this->usu_ValidoDesde->Errors->ToString();
            $Error .= $this->usu_ValidoHasta->Errors->ToString();
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
        $this->usu_login->Show();
        $this->usu_Password->Show();
        $this->usu_Nombre->Show();
        $this->usu_Grupo->Show();
        $this->usu_Activo->Show();
        $this->usu_PagInicial->Show();
        $this->usu_AuxId->Show();
        $this->usu_ValidoDesde->Show();
        $this->usu_ValidoHasta->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->Button_Cancel->Show();
        $this->btRegresar->Show();
        $this->Button1->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

} //End SeGeUs_mant Class @12-FCB6E20C

class clsSeGeUs_mantDataSource extends clsDBseguridad {  //SeGeUs_mantDataSource Class @12-AB1393DF

//DataSource Variables @12-0E10DCB1
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
    var $usu_login;
    var $usu_Password;
    var $usu_Nombre;
    var $usu_Grupo;
    var $usu_Activo;
    var $usu_PagInicial;
    var $usu_AuxId;
    var $usu_ValidoDesde;
    var $usu_ValidoHasta;
//End DataSource Variables

//Class_Initialize Event @12-D14E7B35
    function clsSeGeUs_mantDataSource()
    {
        $this->ErrorBlock = "Record SeGeUs_mant/Error";
        $this->Initialize();
        $this->lbTitulo = new clsField("lbTitulo", ccsText, "");
        $this->usu_login = new clsField("usu_login", ccsText, "");
        $this->usu_Password = new clsField("usu_Password", ccsText, "");
        $this->usu_Nombre = new clsField("usu_Nombre", ccsText, "");
        $this->usu_Grupo = new clsField("usu_Grupo", ccsText, "");
        $this->usu_Activo = new clsField("usu_Activo", ccsInteger, "");
        $this->usu_PagInicial = new clsField("usu_PagInicial", ccsText, "");
        $this->usu_AuxId = new clsField("usu_AuxId", ccsInteger, "");
        $this->usu_ValidoDesde = new clsField("usu_ValidoDesde", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss", ".", "S"));
        $this->usu_ValidoHasta = new clsField("usu_ValidoHasta", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));

    }
//End Class_Initialize Event

//Prepare Method @12-899BBB69
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlusu_IDusuario", ccsText, "", "", $this->Parameters["urlusu_IDusuario"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "usu_IDusuario", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @12-6516B38B
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->SQL = "SELECT *  " .
        "FROM segusuario";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @12-8540B3CD
    function SetValues()
    {
        $this->usu_login->SetDBValue($this->f("usu_login"));
        $this->usu_Password->SetDBValue($this->f("usu_Password"));
        $this->usu_Nombre->SetDBValue($this->f("usu_Nombre"));
        $this->usu_Grupo->SetDBValue($this->f("usu_Grupo"));
        $this->usu_Activo->SetDBValue(trim($this->f("usu_Activo")));
        $this->usu_PagInicial->SetDBValue($this->f("usu_PagInicial"));
        $this->usu_AuxId->SetDBValue(trim($this->f("usu_AuxId")));
        $this->usu_ValidoDesde->SetDBValue(trim($this->f("usu_ValidoDesde")));
        $this->usu_ValidoHasta->SetDBValue(trim($this->f("usu_ValidoHasta")));
    }
//End SetValues Method

//Insert Method @12-B7CC0861
    function Insert()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO segusuario ("
             . "usu_login, "
             . "usu_Password, "
             . "usu_Nombre, "
             . "usu_Grupo, "
             . "usu_Activo, "
             . "usu_PagInicial, "
             . "usu_AuxId, "
             . "usu_ValidoDesde, "
             . "usu_ValidoHasta"
             . ") VALUES ("
             . $this->ToSQL($this->usu_login->GetDBValue(), $this->usu_login->DataType) . ", "
             . $this->ToSQL($this->usu_Password->GetDBValue(), $this->usu_Password->DataType) . ", "
             . $this->ToSQL($this->usu_Nombre->GetDBValue(), $this->usu_Nombre->DataType) . ", "
             . $this->ToSQL($this->usu_Grupo->GetDBValue(), $this->usu_Grupo->DataType) . ", "
             . $this->ToSQL($this->usu_Activo->GetDBValue(), $this->usu_Activo->DataType) . ", "
             . $this->ToSQL($this->usu_PagInicial->GetDBValue(), $this->usu_PagInicial->DataType) . ", "
             . $this->ToSQL($this->usu_AuxId->GetDBValue(), $this->usu_AuxId->DataType) . ", "
             . $this->ToSQL($this->usu_ValidoDesde->GetDBValue(), $this->usu_ValidoDesde->DataType) . ", "
             . $this->ToSQL($this->usu_ValidoHasta->GetDBValue(), $this->usu_ValidoHasta->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Insert Method

//Update Method @12-1761EB83
    function Update()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $this->SQL = "UPDATE segusuario SET "
             . "usu_login=" . $this->ToSQL($this->usu_login->GetDBValue(), $this->usu_login->DataType) . ", "
             . "usu_Password=" . $this->ToSQL($this->usu_Password->GetDBValue(), $this->usu_Password->DataType) . ", "
             . "usu_Nombre=" . $this->ToSQL($this->usu_Nombre->GetDBValue(), $this->usu_Nombre->DataType) . ", "
             . "usu_Grupo=" . $this->ToSQL($this->usu_Grupo->GetDBValue(), $this->usu_Grupo->DataType) . ", "
             . "usu_Activo=" . $this->ToSQL($this->usu_Activo->GetDBValue(), $this->usu_Activo->DataType) . ", "
             . "usu_PagInicial=" . $this->ToSQL($this->usu_PagInicial->GetDBValue(), $this->usu_PagInicial->DataType) . ", "
             . "usu_AuxId=" . $this->ToSQL($this->usu_AuxId->GetDBValue(), $this->usu_AuxId->DataType) . ", "
             . "usu_ValidoDesde=" . $this->ToSQL($this->usu_ValidoDesde->GetDBValue(), $this->usu_ValidoDesde->DataType) . ", "
             . "usu_ValidoHasta=" . $this->ToSQL($this->usu_ValidoHasta->GetDBValue(), $this->usu_ValidoHasta->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Update Method

//Delete Method @12-2188D198
    function Delete()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $this->SQL = "DELETE FROM segusuario";
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Delete Method

} //End SeGeUs_mantDataSource Class @12-FCB6E20C

Class clsEditableGridSeGeUs_perf { //SeGeUs_perf Class @31-D6815C2C

//Variables @31-0E27CA65

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
    var $Sorter_usp_IDusuario;
    var $Sorter_usp_IDperfil;
    var $Sorter_usp_IDempresa;
//End Variables

//Class_Initialize Event @31-D5926A93
    function clsEditableGridSeGeUs_perf()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "EditableGrid SeGeUs_perf/Error";
        $this->ComponentName = "SeGeUs_perf";
        $this->CachedColumns["usp_IDusuario"][0] = "usp_IDusuario";
        $this->CachedColumns["usp_IDperfil"][0] = "usp_IDperfil";
        $this->CachedColumns["usp_IDempresa"][0] = "usp_IDempresa";
        $this->ds = new clsSeGeUs_perfDataSource();

        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize) || $this->PageSize > 10)
            $this->PageSize = 30;
        else
            $this->PageSize = intval($this->PageSize);
        if($this->PageSize == 10)
            $this->Errors->addError("<p>Form: EditableGrid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));

        $this->EmptyRows = 5;
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

        $this->SorterName = CCGetParam("SeGeUs_perfOrder", "");
        $this->SorterDirection = CCGetParam("SeGeUs_perfDir", "");

        $this->Sorter_usp_IDusuario = new clsSorter($this->ComponentName, "Sorter_usp_IDusuario", $FileName);
        $this->Sorter_usp_IDperfil = new clsSorter($this->ComponentName, "Sorter_usp_IDperfil", $FileName);
        $this->Sorter_usp_IDempresa = new clsSorter($this->ComponentName, "Sorter_usp_IDempresa", $FileName);
        $this->usp_IDusuario = new clsControl(ccsHidden, "usp_IDusuario", "Usp IDusuario", ccsText, "");
        $this->usp_IDperfil = new clsControl(ccsListBox, "usp_IDperfil", "Usp IDperfil", ccsText, "");
        $this->usp_IDperfil->DSType = dsTable;
        list($this->usp_IDperfil->BoundColumn, $this->usp_IDperfil->TextColumn, $this->usp_IDperfil->DBFormat) = array("pfl_IDperfil", "pfl_Descripcion", "");
        $this->usp_IDperfil->ds = new clsDBseguridad();
        $this->usp_IDperfil->ds->SQL = "SELECT pfl_IDperfil, pfl_Descripcion  " .
"FROM segperfiles";
        $this->usp_IDperfil->ds->Parameters["expr71"] = 1;
        $this->usp_IDperfil->ds->wp = new clsSQLParameters();
        $this->usp_IDperfil->ds->wp->AddParameter("1", "expr71", ccsInteger, "", "", $this->usp_IDperfil->ds->Parameters["expr71"], "", false);
        $this->usp_IDperfil->ds->wp->Criterion[1] = $this->usp_IDperfil->ds->wp->Operation(opEqual, "pfl_estado", $this->usp_IDperfil->ds->wp->GetDBValue("1"), $this->usp_IDperfil->ds->ToSQL($this->usp_IDperfil->ds->wp->GetDBValue("1"), ccsInteger),false);
        $this->usp_IDperfil->ds->Where = $this->usp_IDperfil->ds->wp->Criterion[1];
        $this->usp_IDempresa = new clsControl(ccsListBox, "usp_IDempresa", "Usp IDempresa", ccsText, "");
        $this->usp_IDempresa->DSType = dsTable;
        list($this->usp_IDempresa->BoundColumn, $this->usp_IDempresa->TextColumn, $this->usp_IDempresa->DBFormat) = array("emp_IDempresa", "emp_Descripcion", "");
        $this->usp_IDempresa->ds = new clsDBseguridad();
        $this->usp_IDempresa->ds->SQL = "SELECT *  " .
"FROM segempresas";
        $this->usp_IDempresa->Required = true;
        $this->CheckBox_Delete = new clsControl(ccsCheckBox, "CheckBox_Delete", "CheckBox_Delete", ccsBoolean, Array("Y", "N", ""));
        $this->CheckBox_Delete->CheckedValue = $this->CheckBox_Delete->GetParsedValue(true);
        $this->CheckBox_Delete->UncheckedValue = $this->CheckBox_Delete->GetParsedValue(false);
        $this->Button_Submit = new clsButton("Button_Submit");
    }
//End Class_Initialize Event

//Initialize Method @31-CD209C2A
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);

        $this->ds->Parameters["urlusp_IDusuario"] = CCGetFromGet("usp_IDusuario", "");
    }
//End Initialize Method

//GetFormParameters Method @31-42AB3D91
    function GetFormParameters()
    {
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->FormParameters["usp_IDusuario"][$RowNumber] = CCGetFromPost("usp_IDusuario_" . $RowNumber);
            $this->FormParameters["usp_IDperfil"][$RowNumber] = CCGetFromPost("usp_IDperfil_" . $RowNumber);
            $this->FormParameters["usp_IDempresa"][$RowNumber] = CCGetFromPost("usp_IDempresa_" . $RowNumber);
            $this->FormParameters["CheckBox_Delete"][$RowNumber] = CCGetFromPost("CheckBox_Delete_" . $RowNumber);
        }
    }
//End GetFormParameters Method

//Validate Method @31-0FDC62C2
    function Validate()
    {
        $Validation = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");

        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["usp_IDusuario"] = $this->CachedColumns["usp_IDusuario"][$RowNumber];
            $this->ds->CachedColumns["usp_IDperfil"] = $this->CachedColumns["usp_IDperfil"][$RowNumber];
            $this->ds->CachedColumns["usp_IDempresa"] = $this->CachedColumns["usp_IDempresa"][$RowNumber];
            $this->usp_IDusuario->SetText($this->FormParameters["usp_IDusuario"][$RowNumber], $RowNumber);
            $this->usp_IDperfil->SetText($this->FormParameters["usp_IDperfil"][$RowNumber], $RowNumber);
            $this->usp_IDempresa->SetText($this->FormParameters["usp_IDempresa"][$RowNumber], $RowNumber);
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

//ValidateRow Method @31-29372D4A
    function ValidateRow($RowNumber)
    {
        $Validation = true;
        $Validation = ($this->usp_IDusuario->Validate() && $Validation);
        $Validation = ($this->usp_IDperfil->Validate() && $Validation);
        $Validation = ($this->usp_IDempresa->Validate() && $Validation);
        $Validation = ($this->CheckBox_Delete->Validate() && $Validation);
        $errors = "";
        if(!$Validation)
        {
            $errors .= $this->usp_IDusuario->Errors->ToString();
            $errors .= $this->usp_IDperfil->Errors->ToString();
            $errors .= $this->usp_IDempresa->Errors->ToString();
            $errors .= $this->CheckBox_Delete->Errors->ToString();
            $this->usp_IDusuario->Errors->Clear();
            $this->usp_IDperfil->Errors->Clear();
            $this->usp_IDempresa->Errors->Clear();
            $this->CheckBox_Delete->Errors->Clear();
        }
        $this->RowsErrors[$RowNumber] = $errors;
        return $Validation;
    }
//End ValidateRow Method

//CheckInsert Method @31-4E7BDB92
    function CheckInsert($RowNumber)
    {
        $filed = false;
        $filed = ($filed || strlen($this->FormParameters["usp_IDusuario"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["usp_IDperfil"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["usp_IDempresa"][$RowNumber]));
        return $filed;
    }
//End CheckInsert Method

//CheckErrors Method @31-242E5992
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @31-6A172129
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
        }

        $Redirect = $FileName . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "Button_Submit") {
            if(!CCGetEvent($this->Button_Submit->CCSEvents, "OnClick") || !$this->UpdateGrid()) {
                $Redirect = "";
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//UpdateGrid Method @31-C67FBC3B
    function UpdateGrid()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSubmit");
        if(!$this->Validate()) return;
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["usp_IDusuario"] = $this->CachedColumns["usp_IDusuario"][$RowNumber];
            $this->ds->CachedColumns["usp_IDperfil"] = $this->CachedColumns["usp_IDperfil"][$RowNumber];
            $this->ds->CachedColumns["usp_IDempresa"] = $this->CachedColumns["usp_IDempresa"][$RowNumber];
            $this->usp_IDusuario->SetText($this->FormParameters["usp_IDusuario"][$RowNumber], $RowNumber);
            $this->usp_IDperfil->SetText($this->FormParameters["usp_IDperfil"][$RowNumber], $RowNumber);
            $this->usp_IDempresa->SetText($this->FormParameters["usp_IDempresa"][$RowNumber], $RowNumber);
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

//InsertRow Method @31-7EDF4351
    function InsertRow($RowNumber)
    {
        if(!$this->InsertAllowed) return false;
        $this->ds->usp_IDperfil->SetValue($this->usp_IDperfil->GetValue());
        $this->ds->usp_IDempresa->SetValue($this->usp_IDempresa->GetValue());
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

//UpdateRow Method @31-AF97ADE6
    function UpdateRow($RowNumber)
    {
        if(!$this->UpdateAllowed) return false;
        $this->ds->usp_IDperfil->SetValue($this->usp_IDperfil->GetValue());
        $this->ds->usp_IDempresa->SetValue($this->usp_IDempresa->GetValue());
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

//DeleteRow Method @31-0C9DDC34
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

//FormScript Method @31-59800DB5
    function FormScript($TotalRows)
    {
        $script = "";
        return $script;
    }
//End FormScript Method

//SetFormState Method @31-B9B6E689
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
            for($i = 2; $i < sizeof($pieces); $i = $i + 3)  {
                $piece = $pieces[$i + 0];
                $piece = str_replace("\\" . ord("\\"), "\\", $piece);
                $piece = str_replace("\\" . ord(";"), ";", $piece);
                $this->CachedColumns["usp_IDusuario"][$RowNumber] = $piece;
                $piece = $pieces[$i + 1];
                $piece = str_replace("\\" . ord("\\"), "\\", $piece);
                $piece = str_replace("\\" . ord(";"), ";", $piece);
                $this->CachedColumns["usp_IDperfil"][$RowNumber] = $piece;
                $piece = $pieces[$i + 2];
                $piece = str_replace("\\" . ord("\\"), "\\", $piece);
                $piece = str_replace("\\" . ord(";"), ";", $piece);
                $this->CachedColumns["usp_IDempresa"][$RowNumber] = $piece;
                $RowNumber++;
            }

            if(!$RowNumber) { $RowNumber = 1; }
            for($i = 1; $i <= $this->EmptyRows; $i++) {
                $this->CachedColumns["usp_IDusuario"][$RowNumber] = "";
                $this->CachedColumns["usp_IDperfil"][$RowNumber] = "";
                $this->CachedColumns["usp_IDempresa"][$RowNumber] = "";
                $RowNumber++;
            }
        }
    }
//End SetFormState Method

//GetFormState Method @31-235A58AA
    function GetFormState($NonEmptyRows)
    {
        if(!$this->FormSubmitted) {
            $this->FormState  = $NonEmptyRows . ";";
            $this->FormState .= $this->InsertAllowed ? $this->EmptyRows : "0";
            if($NonEmptyRows) {
                for($i = 0; $i <= $NonEmptyRows; $i++) {
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["usp_IDusuario"][$i]));
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["usp_IDperfil"][$i]));
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["usp_IDempresa"][$i]));
                }
            }
        }
        return $this->FormState;
    }
//End GetFormState Method

//Show Method @31-8E28E924
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible) { return; }

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->usp_IDperfil->Prepare();
        $this->usp_IDempresa->Prepare();

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
                        $this->CachedColumns["usp_IDusuario"][$RowNumber] = $this->ds->CachedColumns["usp_IDusuario"];
                        $this->CachedColumns["usp_IDperfil"][$RowNumber] = $this->ds->CachedColumns["usp_IDperfil"];
                        $this->CachedColumns["usp_IDempresa"][$RowNumber] = $this->ds->CachedColumns["usp_IDempresa"];
                        $this->usp_IDusuario->SetValue($this->ds->usp_IDusuario->GetValue());
                        $this->usp_IDperfil->SetValue($this->ds->usp_IDperfil->GetValue());
                        $this->usp_IDempresa->SetValue($this->ds->usp_IDempresa->GetValue());
                        $this->ValidateRow($RowNumber);
                    } else if (!$this->FormSubmitted){
                        $this->CachedColumns["usp_IDusuario"][$RowNumber] = "";
                        $this->CachedColumns["usp_IDperfil"][$RowNumber] = "";
                        $this->CachedColumns["usp_IDempresa"][$RowNumber] = "";
                        $this->usp_IDusuario->SetText("");
                        $this->usp_IDperfil->SetText("");
                        $this->usp_IDempresa->SetText("");
                        $this->CheckBox_Delete->SetText("");
                    } else {
                        $this->usp_IDusuario->SetText($this->FormParameters["usp_IDusuario"][$RowNumber], $RowNumber);
                        $this->usp_IDperfil->SetText($this->FormParameters["usp_IDperfil"][$RowNumber], $RowNumber);
                        $this->usp_IDempresa->SetText($this->FormParameters["usp_IDempresa"][$RowNumber], $RowNumber);
                        $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$RowNumber], $RowNumber);
                    }
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->usp_IDusuario->Show($RowNumber);
                    $this->usp_IDperfil->Show($RowNumber);
                    $this->usp_IDempresa->Show($RowNumber);
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

        $Tpl->block_path = $EditableGridPath;
        $this->Sorter_usp_IDusuario->Show();
        $this->Sorter_usp_IDperfil->Show();
        $this->Sorter_usp_IDempresa->Show();
        $this->Button_Submit->Show();

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

} //End SeGeUs_perf Class @31-FCB6E20C

class clsSeGeUs_perfDataSource extends clsDBseguridad {  //SeGeUs_perfDataSource Class @31-F1FCC77C

//DataSource Variables @31-CCD27A52
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
    var $usp_IDusuario;
    var $usp_IDperfil;
    var $usp_IDempresa;
    var $CheckBox_Delete;
//End DataSource Variables

//Class_Initialize Event @31-DBE92350
    function clsSeGeUs_perfDataSource()
    {
        $this->ErrorBlock = "EditableGrid SeGeUs_perf/Error";
        $this->Initialize();
        $this->usp_IDusuario = new clsField("usp_IDusuario", ccsText, "");
        $this->usp_IDperfil = new clsField("usp_IDperfil", ccsText, "");
        $this->usp_IDempresa = new clsField("usp_IDempresa", ccsText, "");
        $this->CheckBox_Delete = new clsField("CheckBox_Delete", ccsBoolean, Array("true", "false", ""));

    }
//End Class_Initialize Event

//SetOrder Method @31-15DB5B60
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_usp_IDusuario" => array("usp_IDusuario", ""), 
            "Sorter_usp_IDperfil" => array("usp_IDperfil", ""), 
            "Sorter_usp_IDempresa" => array("usp_IDempresa", "")));
    }
//End SetOrder Method

//Prepare Method @31-2F904C89
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlusp_IDusuario", ccsInteger, "", "", $this->Parameters["urlusp_IDusuario"], "", true);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "usp_IDusuario", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),true);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @31-B7BCF830
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM segusperfiles ";
        $this->SQL = "SELECT *  " .
        "FROM segusperfiles left join segempresas on emp_idempresa = usp_idempresa  ";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->Order = " emp_descripcion";
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @31-5F83363B
    function SetValues()
    {
        $this->CachedColumns["usp_IDusuario"] = $this->f("usp_IDusuario");
        $this->CachedColumns["usp_IDperfil"] = $this->f("usp_IDperfil");
        $this->CachedColumns["usp_IDempresa"] = $this->f("usp_IDempresa");
        $this->usp_IDusuario->SetDBValue($this->f("usp_IDusuario"));
        $this->usp_IDperfil->SetDBValue($this->f("usp_IDperfil"));
        $this->usp_IDempresa->SetDBValue($this->f("usp_IDempresa"));
    }
//End SetValues Method

//Insert Method @31-E0BFF2C0
    function Insert()
    {
        $this->cp["usp_IDusuario"] = new clsSQLParameter("urlusp_IDusuario", ccsText, "", "", CCGetFromGet("usp_IDusuario", ""), "", false, $this->ErrorBlock);
        $this->cp["usp_IDperfil"] = new clsSQLParameter("ctrlusp_IDperfil", ccsText, "", "", $this->usp_IDperfil->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["usp_IDempresa"] = new clsSQLParameter("ctrlusp_IDempresa", ccsText, "", "", $this->usp_IDempresa->GetValue(), "", false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO segusperfiles ("
             . "usp_IDusuario, "
             . "usp_IDperfil, "
             . "usp_IDempresa"
             . ") VALUES ("
             . $this->ToSQL($this->cp["usp_IDusuario"]->GetDBValue(), $this->cp["usp_IDusuario"]->DataType) . ", "
             . $this->ToSQL($this->cp["usp_IDperfil"]->GetDBValue(), $this->cp["usp_IDperfil"]->DataType) . ", "
             . $this->ToSQL($this->cp["usp_IDempresa"]->GetDBValue(), $this->cp["usp_IDempresa"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Insert Method

//Update Method @31-644AFF01
    function Update()
    {
        $this->cp["usp_IDusuario"] = new clsSQLParameter("urlusp_IDusuario", ccsText, "", "", CCGetFromGet("usp_IDusuario", ""), "", false, $this->ErrorBlock);
        $this->cp["usp_IDperfil"] = new clsSQLParameter("ctrlusp_IDperfil", ccsText, "", "", $this->usp_IDperfil->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["usp_IDempresa"] = new clsSQLParameter("ctrlusp_IDempresa", ccsText, "", "", $this->usp_IDempresa->GetValue(), "", false, $this->ErrorBlock);
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlusp_IDusuario", ccsText, "", "", CCGetFromGet("usp_IDusuario", ""), "", false);
        $wp->AddParameter("2", "dsusp_IDperfil", ccsText, "", "", $this->CachedColumns["usp_IDperfil"], "", false);
        $wp->AddParameter("3", "dsusp_IDempresa", ccsText, "", "", $this->CachedColumns["usp_IDempresa"], "", false);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $wp->Criterion[1] = $wp->Operation(opEqual, "usp_IDusuario", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsText),false);
        $wp->Criterion[2] = $wp->Operation(opEqual, "usp_IDperfil", $wp->GetDBValue("2"), $this->ToSQL($wp->GetDBValue("2"), ccsText),false);
        $wp->Criterion[3] = $wp->Operation(opEqual, "usp_IDempresa", $wp->GetDBValue("3"), $this->ToSQL($wp->GetDBValue("3"), ccsText),false);
        $Where = $wp->opAND(false, $wp->opAND(false, $wp->Criterion[1], $wp->Criterion[2]), $wp->Criterion[3]);
        $this->SQL = "UPDATE segusperfiles SET "
             . "usp_IDusuario=" . $this->ToSQL($this->cp["usp_IDusuario"]->GetDBValue(), $this->cp["usp_IDusuario"]->DataType) . ", "
             . "usp_IDperfil=" . $this->ToSQL($this->cp["usp_IDperfil"]->GetDBValue(), $this->cp["usp_IDperfil"]->DataType) . ", "
             . "usp_IDempresa=" . $this->ToSQL($this->cp["usp_IDempresa"]->GetDBValue(), $this->cp["usp_IDempresa"]->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Update Method

//Delete Method @31-0EF2284B
    function Delete()
    {
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlusp_IDusuario", ccsText, "", "", CCGetFromGet("usp_IDusuario", ""), "", false);
        $wp->AddParameter("2", "dsusp_IDperfil", ccsText, "", "", $this->CachedColumns["usp_IDperfil"], "", false);
        $wp->AddParameter("3", "dsusp_IDempresa", ccsText, "", "", $this->CachedColumns["usp_IDempresa"], "", false);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $wp->Criterion[1] = $wp->Operation(opEqual, "usp_IDusuario", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsText),false);
        $wp->Criterion[2] = $wp->Operation(opEqual, "usp_IDperfil", $wp->GetDBValue("2"), $this->ToSQL($wp->GetDBValue("2"), ccsText),false);
        $wp->Criterion[3] = $wp->Operation(opEqual, "usp_IDempresa", $wp->GetDBValue("3"), $this->ToSQL($wp->GetDBValue("3"), ccsText),false);
        $Where = $wp->opAND(false, $wp->opAND(false, $wp->Criterion[1], $wp->Criterion[2]), $wp->Criterion[3]);
        $this->SQL = "DELETE FROM segusperfiles";
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Delete Method

} //End SeGeUs_perfDataSource Class @31-FCB6E20C

//Initialize Page @1-B45C215F
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

$FileName = "SeGeUs_mant.php";
$Redirect = "";
$TemplateFileName = "SeGeUs_mant.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-578419FE
$DBdatos = new clsDBseguridad();

// Controls
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
$SeGeUs_mant = new clsRecordSeGeUs_mant();
$SeGeUs_perf = new clsEditableGridSeGeUs_perf();
$SeGeUs_mant->Initialize();
$SeGeUs_perf->Initialize();

// Events
include("./SeGeUs_mant_events.php");
BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-B16F8A69
$Cabecera->Operations();
$SeGeUs_mant->Operation();
$SeGeUs_perf->Operation();
//End Execute Components

//Go to destination page @1-77BDE3EB
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    $DBdatos->close();
    header("Location: " . $Redirect);
    exit;
}
//End Go to destination page
//Show Page @1-2BC655BF
$Cabecera->Show("Cabecera");
$SeGeUs_mant->Show();
$SeGeUs_perf->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
$generated_with = "<center><font face=\"Arial\"><small></small></font></center>";
$main_block = preg_match("/<\/body>/i", $main_block) ? preg_replace("/<\/body>/i", $generated_with . "</body>", $main_block) : $main_block . $generated_with;
echo $main_block;
//End Show Page
//Unload Page @1-3ABDE6D4
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBdatos->close();
unset($Tpl);
//End Unload Page
?>
