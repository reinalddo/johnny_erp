<?php
include ("General.inc.php");
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");

//End Include Common Files

include_once (RelativePath . "/LibPhp/SegLib.php");
Class clsRecordSeGeMo_mant { //SeGeMo_mant Class @2-4AFACB15

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

//Class_Initialize Event @2-F01B9394
    function clsRecordSeGeMo_mant()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record SeGeMo_mant/Error";
        $this->ds = new clsSeGeMo_mantDataSource();
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "SeGeMo_mant";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->mod_id = new clsControl(ccsTextBox, "mod_id", "Identificador de Módulo", ccsInteger, "", CCGetRequestParam("mod_id", $Method));
            $this->tbRuta = new clsControl(ccsTextBox, "tbRuta", "tbRuta", ccsText, "", CCGetRequestParam("tbRuta", $Method));
            $this->mod_descripcion = new clsControl(ccsTextBox, "mod_descripcion", "Mod Descripcion", ccsText, "", CCGetRequestParam("mod_descripcion", $Method));
            $this->mod_subsistema = new clsControl(ccsListBox, "mod_subsistema", "Mod Subsistema", ccsText, "", CCGetRequestParam("mod_subsistema", $Method));
            $this->mod_subsistema->DSType = dsSQL;
            list($this->mod_subsistema->BoundColumn, $this->mod_subsistema->TextColumn, $this->mod_subsistema->DBFormat) = array("cod", "descr", "");
            $this->mod_subsistema->ds = new clsDBSeguridad();
            $this->mod_subsistema->ds->SQL = "select 'ooo' as cod, '----- Nuevo               ' as descr " .
            "UNION " .
            "SELECT mod_subsistema as cod, CONCAT(MOD_SUBSISTEMA, \"  \",  " .
            "       mod_descripcion) as descr " .
            "FROM segmodulos " .
            "WHERE MOD_subsistema <>'ooo'  and mod_modulo = 'ooo' ";
            $this->mod_subsistema->ds->Order = "2";
            $this->mod_Modulo = new clsControl(ccsListBox, "mod_Modulo", "Mod Modulo", ccsText, "", CCGetRequestParam("mod_Modulo", $Method));
            $this->mod_Modulo->DSType = dsSQL;
            list($this->mod_Modulo->BoundColumn, $this->mod_Modulo->TextColumn, $this->mod_Modulo->DBFormat) = array("cod", "descr", "");
            $this->mod_Modulo->ds = new clsDBSeguridad();
            $this->mod_Modulo->ds->SQL = "select 'ooo' as cod, '----- Sin subsistema' as descr " .
            "UNION " .
            "SELECT mod_modulo cod, CONCAT(MOD_SUBSISTEMA, '/', MOD_MODULO, '  ', mod_descripcion) as descr " .
            "FROM segmodulos " .
            "WHERE MOD_modulo <>'ooo'  and mod_submod = 'ooo' ";
            $this->mod_Modulo->ds->Order = "2";
            $this->txt_Modulo = new clsControl(ccsTextBox, "txt_Modulo", "txt_Modulo", ccsText, "", CCGetRequestParam("txt_Modulo", $Method));
            $this->mod_submod = new clsControl(ccsListBox, "mod_submod", "Mod Submod", ccsText, "", CCGetRequestParam("mod_submod", $Method));
            $this->mod_submod->DSType = dsSQL;
            list($this->mod_submod->BoundColumn, $this->mod_submod->TextColumn, $this->mod_submod->DBFormat) = array("cod", "descr", "");
            $this->mod_submod->ds = new clsDBSeguridad();
            $this->mod_submod->ds->SQL = "select 'ooo' as cod, '----- Nuevo submodulo                  ' as descr " .
            "UNION " .
            "SELECT mod_submod cod, CONCAT(MOD_SUBSISTEMA, '/', MOD_MODULO, '/',  " .
            "       mod_submod, '  ', mod_descripcion) as descr " .
            "FROM segmodulos " .
            "WHERE MOD_submod <>'ooo'  and mod_operacion = 'ooo' ";
            $this->mod_submod->ds->Order = "2";
            $this->txt_submod = new clsControl(ccsTextBox, "txt_submod", "txt_submod", ccsText, "", CCGetRequestParam("txt_submod", $Method));
            $this->mod_Operacion = new clsControl(ccsTextBox, "mod_Operacion", "Mod Operacion", ccsText, "", CCGetRequestParam("mod_Operacion", $Method));
            $this->mod_Funcion = new clsControl(ccsTextBox, "mod_Funcion", "Mod Funcion", ccsText, "", CCGetRequestParam("mod_Funcion", $Method));
            $this->mod_Padre = new clsControl(ccsListBox, "mod_Padre", "Mod Padre", ccsInteger, "", CCGetRequestParam("mod_Padre", $Method));
            $this->mod_Padre->DSType = dsSQL;
            list($this->mod_Padre->BoundColumn, $this->mod_Padre->TextColumn, $this->mod_Padre->DBFormat) = array("cod", "descr", "");
            $this->mod_Padre->ds = new clsDBSeguridad();
            $this->mod_Padre->ds->SQL = "SELECT mod_ID as cod, CONCAT(MOD_SUBSISTEMA, '-', MOD_MODULO, '-',  " .
            "       mod_submod, '-', mod_operacion, '-', mod_funcion, ' ',  " .
            "       mod_descripcion) as descr " .
            "FROM segmodulos " .
            "WHERE mod_operacion = 'ooo' ";
            $this->mod_Padre->ds->Order = "2";
            $this->mod_Padre->Required = true;
            $this->mod_Comentario = new clsControl(ccsTextArea, "mod_Comentario", "Mod Comentario", ccsMemo, "", CCGetRequestParam("mod_Comentario", $Method));
            $this->Button_Insert = new clsButton("Button_Insert");
            $this->Button_Update = new clsButton("Button_Update");
            $this->Button_Delete = new clsButton("Button_Delete");
            $this->Button_Cancel = new clsButton("Button_Cancel");
            $this->btNuevo = new clsButton("btNuevo");
            if(!$this->FormSubmitted) {
                if(!is_array($this->mod_subsistema->Value) && !strlen($this->mod_subsistema->Value) && $this->mod_subsistema->Value !== false)
                $this->mod_subsistema->SetText('ooo');
                if(!is_array($this->mod_Modulo->Value) && !strlen($this->mod_Modulo->Value) && $this->mod_Modulo->Value !== false)
                $this->mod_Modulo->SetText('ooo');
                if(!is_array($this->mod_submod->Value) && !strlen($this->mod_submod->Value) && $this->mod_submod->Value !== false)
                $this->mod_submod->SetText('ooo');
                if(!is_array($this->mod_Operacion->Value) && !strlen($this->mod_Operacion->Value) && $this->mod_Operacion->Value !== false)
                $this->mod_Operacion->SetText('ooo');
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @2-2FEA4F49
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->ds->Parameters["urlmod_ID"] = CCGetFromGet("mod_ID", "");
    }
//End Initialize Method

//Validate Method @2-5C5A9659
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->mod_id->Validate() && $Validation);
        $Validation = ($this->tbRuta->Validate() && $Validation);
        $Validation = ($this->mod_descripcion->Validate() && $Validation);
        $Validation = ($this->mod_subsistema->Validate() && $Validation);
        $Validation = ($this->mod_Modulo->Validate() && $Validation);
        $Validation = ($this->txt_Modulo->Validate() && $Validation);
        $Validation = ($this->mod_submod->Validate() && $Validation);
        $Validation = ($this->txt_submod->Validate() && $Validation);
        $Validation = ($this->mod_Operacion->Validate() && $Validation);
        $Validation = ($this->mod_Funcion->Validate() && $Validation);
        $Validation = ($this->mod_Padre->Validate() && $Validation);
        $Validation = ($this->mod_Comentario->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-6EFBFB26
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->mod_id->Errors->Count());
        $errors = ($errors || $this->tbRuta->Errors->Count());
        $errors = ($errors || $this->mod_descripcion->Errors->Count());
        $errors = ($errors || $this->mod_subsistema->Errors->Count());
        $errors = ($errors || $this->mod_Modulo->Errors->Count());
        $errors = ($errors || $this->txt_Modulo->Errors->Count());
        $errors = ($errors || $this->mod_submod->Errors->Count());
        $errors = ($errors || $this->txt_submod->Errors->Count());
        $errors = ($errors || $this->mod_Operacion->Errors->Count());
        $errors = ($errors || $this->mod_Funcion->Errors->Count());
        $errors = ($errors || $this->mod_Padre->Errors->Count());
        $errors = ($errors || $this->mod_Comentario->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-E212CF58
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
        $Redirect = "SeGeMo_mant.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
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
                $Redirect = "SeGeMo_mant.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm", "mod_ID"));
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

//InsertRow Method @2-A7D803CC
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert");
        if(!$this->InsertAllowed) return false;
        $this->ds->mod_descripcion->SetValue($this->mod_descripcion->GetValue());
        $this->ds->mod_subsistema->SetValue($this->mod_subsistema->GetValue());
        $this->ds->mod_Modulo->SetValue($this->mod_Modulo->GetValue());
        $this->ds->mod_submod->SetValue($this->mod_submod->GetValue());
        $this->ds->mod_Operacion->SetValue($this->mod_Operacion->GetValue());
        $this->ds->mod_Funcion->SetValue($this->mod_Funcion->GetValue());
        $this->ds->mod_Padre->SetValue($this->mod_Padre->GetValue());
        $this->ds->mod_Comentario->SetValue($this->mod_Comentario->GetValue());
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

//UpdateRow Method @2-4A4E7A92
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate");
        if(!$this->UpdateAllowed) return false;
        $this->ds->mod_descripcion->SetValue($this->mod_descripcion->GetValue());
        $this->ds->mod_subsistema->SetValue($this->mod_subsistema->GetValue());
        $this->ds->txt_Modulo->SetValue($this->txt_Modulo->GetValue());
        $this->ds->txt_submod->SetValue($this->txt_submod->GetValue());
        $this->ds->mod_Operacion->SetValue($this->mod_Operacion->GetValue());
        $this->ds->mod_Funcion->SetValue($this->mod_Funcion->GetValue());
        $this->ds->mod_Padre->SetValue($this->mod_Padre->GetValue());
        $this->ds->mod_Comentario->SetValue($this->mod_Comentario->GetValue());
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

//Show Method @2-47A17B14
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->mod_subsistema->Prepare();
        $this->mod_Modulo->Prepare();
        $this->mod_submod->Prepare();
        $this->mod_Padre->Prepare();

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
                    echo "Error in Record SeGeMo_mant";
                }
                else if($this->ds->next_record())
                {
                    $this->ds->SetValues();
                    if(!$this->FormSubmitted)
                    {
                        $this->mod_id->SetValue($this->ds->mod_id->GetValue());
                        $this->tbRuta->SetValue($this->ds->tbRuta->GetValue());
                        $this->mod_descripcion->SetValue($this->ds->mod_descripcion->GetValue());
                        $this->mod_subsistema->SetValue($this->ds->mod_subsistema->GetValue());
                        $this->mod_Modulo->SetValue($this->ds->mod_Modulo->GetValue());
                        $this->txt_Modulo->SetValue($this->ds->txt_Modulo->GetValue());
                        $this->mod_submod->SetValue($this->ds->mod_submod->GetValue());
                        $this->txt_submod->SetValue($this->ds->txt_submod->GetValue());
                        $this->mod_Operacion->SetValue($this->ds->mod_Operacion->GetValue());
                        $this->mod_Funcion->SetValue($this->ds->mod_Funcion->GetValue());
                        $this->mod_Padre->SetValue($this->ds->mod_Padre->GetValue());
                        $this->mod_Comentario->SetValue($this->ds->mod_Comentario->GetValue());
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
            $Error .= $this->mod_id->Errors->ToString();
            $Error .= $this->tbRuta->Errors->ToString();
            $Error .= $this->mod_descripcion->Errors->ToString();
            $Error .= $this->mod_subsistema->Errors->ToString();
            $Error .= $this->mod_Modulo->Errors->ToString();
            $Error .= $this->txt_Modulo->Errors->ToString();
            $Error .= $this->mod_submod->Errors->ToString();
            $Error .= $this->txt_submod->Errors->ToString();
            $Error .= $this->mod_Operacion->Errors->ToString();
            $Error .= $this->mod_Funcion->Errors->ToString();
            $Error .= $this->mod_Padre->Errors->ToString();
            $Error .= $this->mod_Comentario->Errors->ToString();
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
        $this->mod_id->Show();
        $this->tbRuta->Show();
        $this->mod_descripcion->Show();
        $this->mod_subsistema->Show();
        $this->mod_Modulo->Show();
        $this->txt_Modulo->Show();
        $this->mod_submod->Show();
        $this->txt_submod->Show();
        $this->mod_Operacion->Show();
        $this->mod_Funcion->Show();
        $this->mod_Padre->Show();
        $this->mod_Comentario->Show();
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

} //End SeGeMo_mant Class @2-FCB6E20C

class clsSeGeMo_mantDataSource extends clsDBSeguridad {  //SeGeMo_mantDataSource Class @2-D16B31F1

//DataSource Variables @2-7142F293
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $InsertParameters;
    var $UpdateParameters;
    var $DeleteParameters;
    var $wp;
    var $AllParametersSet;


    // Datasource fields
    var $mod_id;
    var $tbRuta;
    var $mod_descripcion;
    var $mod_subsistema;
    var $mod_Modulo;
    var $txt_Modulo;
    var $mod_submod;
    var $txt_submod;
    var $mod_Operacion;
    var $mod_Funcion;
    var $mod_Padre;
    var $mod_Comentario;
//End DataSource Variables

//Class_Initialize Event @2-06281777
    function clsSeGeMo_mantDataSource()
    {
        $this->ErrorBlock = "Record SeGeMo_mant/Error";
        $this->Initialize();
        $this->mod_id = new clsField("mod_id", ccsInteger, "");
        $this->tbRuta = new clsField("tbRuta", ccsText, "");
        $this->mod_descripcion = new clsField("mod_descripcion", ccsText, "");
        $this->mod_subsistema = new clsField("mod_subsistema", ccsText, "");
        $this->mod_Modulo = new clsField("mod_Modulo", ccsText, "");
        $this->txt_Modulo = new clsField("txt_Modulo", ccsText, "");
        $this->mod_submod = new clsField("mod_submod", ccsText, "");
        $this->txt_submod = new clsField("txt_submod", ccsText, "");
        $this->mod_Operacion = new clsField("mod_Operacion", ccsText, "");
        $this->mod_Funcion = new clsField("mod_Funcion", ccsText, "");
        $this->mod_Padre = new clsField("mod_Padre", ccsInteger, "");
        $this->mod_Comentario = new clsField("mod_Comentario", ccsMemo, "");

    }
//End Class_Initialize Event

//Prepare Method @2-E263D214
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlmod_ID", ccsInteger, "", "", $this->Parameters["urlmod_ID"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "mod_ID", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @2-9BAB1516
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->SQL = "SELECT segmodulos.*, concat_ws(\"-\",mod_subsistema ,  mod_modulo , mod_submod , mod_operacion , mod_funcion) AS tbRuta  " .
        "FROM segmodulos";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @2-2A4712A5
    function SetValues()
    {
        $this->mod_id->SetDBValue(trim($this->f("mod_id")));
        $this->tbRuta->SetDBValue($this->f("tbRuta"));
        $this->mod_descripcion->SetDBValue($this->f("mod_descripcion"));
        $this->mod_subsistema->SetDBValue($this->f("mod_subsistema"));
        $this->mod_Modulo->SetDBValue($this->f("mod_modulo"));
        $this->txt_Modulo->SetDBValue($this->f("mod_modulo"));
        $this->mod_submod->SetDBValue($this->f("mod_submod"));
        $this->txt_submod->SetDBValue($this->f("mod_submod"));
        $this->mod_Operacion->SetDBValue($this->f("mod_Operacion"));
        $this->mod_Funcion->SetDBValue($this->f("mod_funcion"));
        $this->mod_Padre->SetDBValue(trim($this->f("mod_padre")));
        $this->mod_Comentario->SetDBValue($this->f("mod_comentario"));
    }
//End SetValues Method

//Insert Method @2-805AE04A
    function Insert()
    {
        $this->cp["mod_descripcion"] = new clsSQLParameter("ctrlmod_descripcion", ccsText, "", "", $this->mod_descripcion->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["mod_subsistema"] = new clsSQLParameter("ctrlmod_subsistema", ccsText, "", "", $this->mod_subsistema->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["mod_modulo"] = new clsSQLParameter("ctrlmod_Modulo", ccsText, "", "", $this->mod_Modulo->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["mod_submod"] = new clsSQLParameter("ctrlmod_submod", ccsText, "", "", $this->mod_submod->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["mod_Operacion"] = new clsSQLParameter("ctrlmod_Operacion", ccsText, "", "", $this->mod_Operacion->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["mod_funcion"] = new clsSQLParameter("ctrlmod_Funcion", ccsText, "", "", $this->mod_Funcion->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["mod_padre"] = new clsSQLParameter("ctrlmod_Padre", ccsInteger, "", "", $this->mod_Padre->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["mod_comentario"] = new clsSQLParameter("ctrlmod_Comentario", ccsMemo, "", "", $this->mod_Comentario->GetValue(), "", false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO segmodulos ("
             . "mod_descripcion, "
             . "mod_subsistema, "
             . "mod_modulo, "
             . "mod_submod, "
             . "mod_Operacion, "
             . "mod_funcion, "
             . "mod_padre, "
             . "mod_comentario"
             . ") VALUES ("
             . $this->ToSQL($this->cp["mod_descripcion"]->GetDBValue(), $this->cp["mod_descripcion"]->DataType) . ", "
             . $this->ToSQL($this->cp["mod_subsistema"]->GetDBValue(), $this->cp["mod_subsistema"]->DataType) . ", "
             . $this->ToSQL($this->cp["mod_modulo"]->GetDBValue(), $this->cp["mod_modulo"]->DataType) . ", "
             . $this->ToSQL($this->cp["mod_submod"]->GetDBValue(), $this->cp["mod_submod"]->DataType) . ", "
             . $this->ToSQL($this->cp["mod_Operacion"]->GetDBValue(), $this->cp["mod_Operacion"]->DataType) . ", "
             . $this->ToSQL($this->cp["mod_funcion"]->GetDBValue(), $this->cp["mod_funcion"]->DataType) . ", "
             . $this->ToSQL($this->cp["mod_padre"]->GetDBValue(), $this->cp["mod_padre"]->DataType) . ", "
             . $this->ToSQL($this->cp["mod_comentario"]->GetDBValue(), $this->cp["mod_comentario"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Insert Method

//Update Method @2-2DB6159E
    function Update()
    {
        $this->cp["mod_descripcion"] = new clsSQLParameter("ctrlmod_descripcion", ccsText, "", "", $this->mod_descripcion->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["mod_subsistema"] = new clsSQLParameter("ctrlmod_subsistema", ccsText, "", "", $this->mod_subsistema->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["mod_modulo"] = new clsSQLParameter("ctrltxt_Modulo", ccsText, "", "", $this->txt_Modulo->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["mod_submod"] = new clsSQLParameter("ctrltxt_submod", ccsText, "", "", $this->txt_submod->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["mod_Operacion"] = new clsSQLParameter("ctrlmod_Operacion", ccsText, "", "", $this->mod_Operacion->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["mod_funcion"] = new clsSQLParameter("ctrlmod_Funcion", ccsText, "", "", $this->mod_Funcion->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["mod_padre"] = new clsSQLParameter("ctrlmod_Padre", ccsInteger, "", "", $this->mod_Padre->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["mod_comentario"] = new clsSQLParameter("ctrlmod_Comentario", ccsMemo, "", "", $this->mod_Comentario->GetValue(), "", false, $this->ErrorBlock);
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "postmod_id", ccsInteger, "", "", CCGetFromPost("mod_id", ""), "", false);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $wp->Criterion[1] = $wp->Operation(opEqual, "mod_id", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),false);
        $Where = $wp->Criterion[1];
        $this->SQL = "UPDATE segmodulos SET "
             . "mod_descripcion=" . $this->ToSQL($this->cp["mod_descripcion"]->GetDBValue(), $this->cp["mod_descripcion"]->DataType) . ", "
             . "mod_subsistema=" . $this->ToSQL($this->cp["mod_subsistema"]->GetDBValue(), $this->cp["mod_subsistema"]->DataType) . ", "
             . "mod_modulo=" . $this->ToSQL($this->cp["mod_modulo"]->GetDBValue(), $this->cp["mod_modulo"]->DataType) . ", "
             . "mod_submod=" . $this->ToSQL($this->cp["mod_submod"]->GetDBValue(), $this->cp["mod_submod"]->DataType) . ", "
             . "mod_Operacion=" . $this->ToSQL($this->cp["mod_Operacion"]->GetDBValue(), $this->cp["mod_Operacion"]->DataType) . ", "
             . "mod_funcion=" . $this->ToSQL($this->cp["mod_funcion"]->GetDBValue(), $this->cp["mod_funcion"]->DataType) . ", "
             . "mod_padre=" . $this->ToSQL($this->cp["mod_padre"]->GetDBValue(), $this->cp["mod_padre"]->DataType) . ", "
             . "mod_comentario=" . $this->ToSQL($this->cp["mod_comentario"]->GetDBValue(), $this->cp["mod_comentario"]->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Update Method

//Delete Method @2-974B4F9F
    function Delete()
    {
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlmod_id", ccsInteger, "", "", CCGetFromGet("mod_id", ""), "", false);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $wp->Criterion[1] = $wp->Operation(opEqual, "mod_id", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),false);
        $Where = $wp->Criterion[1];
        $this->SQL = "DELETE FROM segmodulos";
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Delete Method

} //End SeGeMo_mantDataSource Class @2-FCB6E20C

class clsGridSeGeMo_hijos { //SeGeMo_hijos class @31-9E0FF443

//Variables @31-5F2CB2D1

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
    var $Sorter_mod_id;
    var $Sorter_mod_descripcion;
    var $Navigator;
//End Variables

//Class_Initialize Event @31-BD9BC46D
    function clsGridSeGeMo_hijos()
    {
        global $FileName;
        $this->ComponentName = "SeGeMo_hijos";
        $this->Visible = True;
        $this->IsAltRow = false;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid SeGeMo_hijos";
        $this->ds = new clsSeGeMo_hijosDataSource();
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 15;
        else if ($this->PageSize > 100)
            $this->PageSize = 100;
        else
            $this->PageSize = intval($this->PageSize);
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        $this->SorterName = CCGetParam("SeGeMo_hijosOrder", "");
        $this->SorterDirection = CCGetParam("SeGeMo_hijosDir", "");

        $this->mod_id = new clsControl(ccsLabel, "mod_id", "mod_id", ccsInteger, "", CCGetRequestParam("mod_id", ccsGet));
        $this->mod_funcion = new clsControl(ccsLabel, "mod_funcion", "mod_funcion", ccsText, "", CCGetRequestParam("mod_funcion", ccsGet));
        $this->mod_descripcion = new clsControl(ccsLabel, "mod_descripcion", "mod_descripcion", ccsText, "", CCGetRequestParam("mod_descripcion", ccsGet));
        $this->mod_comentario = new clsControl(ccsLabel, "mod_comentario", "mod_comentario", ccsMemo, "", CCGetRequestParam("mod_comentario", ccsGet));
        $this->Alt_mod_id = new clsControl(ccsLabel, "Alt_mod_id", "Alt_mod_id", ccsInteger, "", CCGetRequestParam("Alt_mod_id", ccsGet));
        $this->Alt_mod_funcion = new clsControl(ccsLabel, "Alt_mod_funcion", "Alt_mod_funcion", ccsText, "", CCGetRequestParam("Alt_mod_funcion", ccsGet));
        $this->Alt_mod_descripcion = new clsControl(ccsLabel, "Alt_mod_descripcion", "Alt_mod_descripcion", ccsText, "", CCGetRequestParam("Alt_mod_descripcion", ccsGet));
        $this->Alt_mod_comentario = new clsControl(ccsLabel, "Alt_mod_comentario", "Alt_mod_comentario", ccsMemo, "", CCGetRequestParam("Alt_mod_comentario", ccsGet));
        $this->Sorter_mod_id = new clsSorter($this->ComponentName, "Sorter_mod_id", $FileName);
        $this->Sorter_mod_descripcion = new clsSorter($this->ComponentName, "Sorter_mod_descripcion", $FileName);
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpCentered);
    }
//End Class_Initialize Event

//Initialize Method @31-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @31-4BE99EA0
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["urlmod_ID"] = CCGetFromGet("mod_ID", "");

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
                    $this->mod_id->SetValue($this->ds->mod_id->GetValue());
                    $this->mod_funcion->SetValue($this->ds->mod_funcion->GetValue());
                    $this->mod_descripcion->SetValue($this->ds->mod_descripcion->GetValue());
                    $this->mod_comentario->SetValue($this->ds->mod_comentario->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->mod_id->Show();
                    $this->mod_funcion->Show();
                    $this->mod_descripcion->Show();
                    $this->mod_comentario->Show();
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                    $Tpl->parse("Row", true);
                }
                else
                {
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/AltRow";
                    $this->Alt_mod_id->SetValue($this->ds->Alt_mod_id->GetValue());
                    $this->Alt_mod_funcion->SetValue($this->ds->Alt_mod_funcion->GetValue());
                    $this->Alt_mod_descripcion->SetValue($this->ds->Alt_mod_descripcion->GetValue());
                    $this->Alt_mod_comentario->SetValue($this->ds->Alt_mod_comentario->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->Alt_mod_id->Show();
                    $this->Alt_mod_funcion->Show();
                    $this->Alt_mod_descripcion->Show();
                    $this->Alt_mod_comentario->Show();
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
        $this->Sorter_mod_id->Show();
        $this->Sorter_mod_descripcion->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @31-92151A24
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->mod_id->Errors->ToString();
        $errors .= $this->mod_funcion->Errors->ToString();
        $errors .= $this->mod_descripcion->Errors->ToString();
        $errors .= $this->mod_comentario->Errors->ToString();
        $errors .= $this->Alt_mod_id->Errors->ToString();
        $errors .= $this->Alt_mod_funcion->Errors->ToString();
        $errors .= $this->Alt_mod_descripcion->Errors->ToString();
        $errors .= $this->Alt_mod_comentario->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End SeGeMo_hijos Class @31-FCB6E20C

class clsSeGeMo_hijosDataSource extends clsDBSeguridad {  //SeGeMo_hijosDataSource Class @31-5AFAD968

//DataSource Variables @31-7B272125
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $mod_id;
    var $mod_funcion;
    var $mod_descripcion;
    var $mod_comentario;
    var $Alt_mod_id;
    var $Alt_mod_funcion;
    var $Alt_mod_descripcion;
    var $Alt_mod_comentario;
//End DataSource Variables

//Class_Initialize Event @31-CF7D1465
    function clsSeGeMo_hijosDataSource()
    {
        $this->ErrorBlock = "Grid SeGeMo_hijos";
        $this->Initialize();
        $this->mod_id = new clsField("mod_id", ccsInteger, "");
        $this->mod_funcion = new clsField("mod_funcion", ccsText, "");
        $this->mod_descripcion = new clsField("mod_descripcion", ccsText, "");
        $this->mod_comentario = new clsField("mod_comentario", ccsMemo, "");
        $this->Alt_mod_id = new clsField("Alt_mod_id", ccsInteger, "");
        $this->Alt_mod_funcion = new clsField("Alt_mod_funcion", ccsText, "");
        $this->Alt_mod_descripcion = new clsField("Alt_mod_descripcion", ccsText, "");
        $this->Alt_mod_comentario = new clsField("Alt_mod_comentario", ccsMemo, "");

    }
//End Class_Initialize Event

//SetOrder Method @31-27D8D581
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "mod_id";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_mod_id" => array("mod_id", ""), 
            "Sorter_mod_descripcion" => array("mod_descripcion", "")));
    }
//End SetOrder Method

//Prepare Method @31-C9310FFA
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlmod_ID", ccsInteger, "", "", $this->Parameters["urlmod_ID"], "", true);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "mod_padre", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),true);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @31-D29E5C50
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM segmodulos";
        $this->SQL = "SELECT mod_descripcion, mod_subsistema, mod_modulo, mod_submod, mod_Operacion, mod_funcion, mod_id, mod_padre, mod_comentario, concat(mod_subsistema,\" \", mod_modulo,\" \", mod_submod,\" \" , mod_operacion, \" \",  mod_funcion) AS mod_funcion, concat(mod_subsistema,\" \", mod_modulo,\" \", mod_submod,\" \" , mod_operacion, \" \",  mod_funcion) AS Alt_mod_funcion  " .
        "FROM segmodulos";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @31-19597874
    function SetValues()
    {
        $this->mod_id->SetDBValue(trim($this->f("mod_id")));
        $this->mod_funcion->SetDBValue($this->f("mod_funcion"));
        $this->mod_descripcion->SetDBValue($this->f("mod_descripcion"));
        $this->mod_comentario->SetDBValue($this->f("mod_comentario"));
        $this->Alt_mod_id->SetDBValue(trim($this->f("mod_id")));
        $this->Alt_mod_funcion->SetDBValue($this->f("Alt_mod_funcion"));
        $this->Alt_mod_descripcion->SetDBValue($this->f("mod_descripcion"));
        $this->Alt_mod_comentario->SetDBValue($this->f("mod_comentario"));
    }
//End SetValues Method

} //End SeGeMo_hijosDataSource Class @31-FCB6E20C

//Initialize Page @1-A2816387
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

$FileName = "SeGeMo_mant.php";
$Redirect = "";
$TemplateFileName = "SeGeMo_mant.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-D69AAFEF
$DBSeguridad = new clsDBSeguridad();
$DBdatos = new clsDBdatos();

// Controls
$SeGeMo_mant = new clsRecordSeGeMo_mant();
$SeGeMo_hijos = new clsGridSeGeMo_hijos();
$SeGeMo_mant->Initialize();
$SeGeMo_hijos->Initialize();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-0B9C4BAE
$SeGeMo_mant->Operation();
//End Execute Components

//Go to destination page @1-468824C4
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    $DBSeguridad->close();
    $DBdatos->close();
    header("Location: " . $Redirect);
    exit;
}
//End Go to destination page

//Show Page @1-4D42BE6E
$SeGeMo_mant->Show();
$SeGeMo_hijos->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
$generated_with = "<center><font face=\"Arial\"><small>Generated with CodeCharge Studio</small></font></center>";
$main_block = preg_match("/<\/body>/i", $main_block) ? preg_replace("/<\/body>/i", $generated_with . "</body>", $main_block) : $main_block . $generated_with;
echo $main_block;
//End Show Page

//Unload Page @1-945CF9B5
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBSeguridad->close();
$DBdatos->close();
unset($Tpl);
//End Unload Page


?>
