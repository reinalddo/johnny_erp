<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
include_once ("General.inc.php");
include_once ("../LibPhp/ConEst.php");
include_once ("GenUti.inc.php");

//End Include Common Files
include_once("../LibPhp/SegLib.php");
Class clsRecordCoAdCu_mant { //CoAdCu_mant Class @41-680CCBDB

//Variables @41-4A82E0A3

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

//Class_Initialize Event @41-102D65C9
    function clsRecordCoAdCu_mant()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record CoAdCu_mant/Error";
        $this->ds = new clsCoAdCu_mantDataSource();
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "CoAdCu_mant";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->lbTitulo = new clsControl(ccsLabel, "lbTitulo", "lbTitulo", ccsText, "", CCGetRequestParam("lbTitulo", $Method));
            $this->Cue_CodCuenta = new clsControl(ccsTextBox, "Cue_CodCuenta", "Código de Cuenta", ccsText, "", CCGetRequestParam("Cue_CodCuenta", $Method));
            $this->lbCodCuenta = new clsControl(ccsLabel, "lbCodCuenta", "lbCodCuenta", ccsText, "", CCGetRequestParam("lbCodCuenta", $Method));
            $this->hdCueId = new clsControl(ccsHidden, "hdCueId", "hdCueId", ccsText, "", CCGetRequestParam("hdCueId", $Method));
            $this->Cue_Padre = new clsControl(ccsListBox, "Cue_Padre", "Cuenta Padre", ccsInteger, "", CCGetRequestParam("Cue_Padre", $Method));
            $this->Cue_Padre->DSType = dsSQL;
            list($this->Cue_Padre->BoundColumn, $this->Cue_Padre->TextColumn, $this->Cue_Padre->DBFormat) = array("cue_id", "descr", "");
            $this->Cue_Padre->ds = new clsDBdatos();
            $this->Cue_Padre->ds->SQL = "select cue_id, concat(cue_CodCuenta, \" -  \", left(cue_descripcion,20)) as descr " .
            "from concuentas " .
            "";
            $this->Cue_Padre->ds->Order = "descr";
            $this->Cue_Padre->Required = true;
            $this->cue_Descripcion = new clsControl(ccsTextBox, "cue_Descripcion", "Cue Descripcion", ccsText, "", CCGetRequestParam("cue_Descripcion", $Method));
            $this->cue_Clase = new clsControl(ccsListBox, "cue_Clase", "Clase de Cuenta", ccsInteger, "", CCGetRequestParam("cue_Clase", $Method));
            $this->cue_Clase->DSType = dsTable;
            list($this->cue_Clase->BoundColumn, $this->cue_Clase->TextColumn, $this->cue_Clase->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->cue_Clase->ds = new clsDBdatos();
            $this->cue_Clase->ds->SQL = "SELECT par_Secuencia, par_Descripcion  " .
"FROM gencatparam INNER JOIN genparametros ON gencatparam.cat_Codigo = genparametros.par_Categoria";
            $this->cue_Clase->ds->Order = "par_Descripcion";
            $this->cue_Clase->ds->Parameters["expr67"] = 'CCUEN';
            $this->cue_Clase->ds->wp = new clsSQLParameters();
            $this->cue_Clase->ds->wp->AddParameter("1", "expr67", ccsText, "", "", $this->cue_Clase->ds->Parameters["expr67"], "", false);
            $this->cue_Clase->ds->wp->Criterion[1] = $this->cue_Clase->ds->wp->Operation(opEqual, "cat_Clave", $this->cue_Clase->ds->wp->GetDBValue("1"), $this->cue_Clase->ds->ToSQL($this->cue_Clase->ds->wp->GetDBValue("1"), ccsText),false);
            $this->cue_Clase->ds->Where = $this->cue_Clase->ds->wp->Criterion[1];
            $this->cue_Clase->Required = true;
            $this->cue_Posicion = new clsControl(ccsTextBox, "cue_Posicion", "Cue Posicion", ccsInteger, "", CCGetRequestParam("cue_Posicion", $Method));
            $this->cue_Posicion->Required = true;
            $this->cue_Estado = new clsControl(ccsCheckBox, "cue_Estado", "Cue Estado", ccsInteger, "", CCGetRequestParam("cue_Estado", $Method));
            $this->cue_Estado->CheckedValue = $this->cue_Estado->GetParsedValue(1);
            $this->cue_Estado->UncheckedValue = $this->cue_Estado->GetParsedValue(0);
            $this->cue_ReqAuxiliar = new clsControl(ccsCheckBox, "cue_ReqAuxiliar", "Cue Req Auxiliar", ccsInteger, "", CCGetRequestParam("cue_ReqAuxiliar", $Method));
            $this->cue_ReqAuxiliar->CheckedValue = $this->cue_ReqAuxiliar->GetParsedValue(1);
            $this->cue_ReqAuxiliar->UncheckedValue = $this->cue_ReqAuxiliar->GetParsedValue(0);
            $this->cue_TipAuxiliar = new clsControl(ccsListBox, "cue_TipAuxiliar", "Cue Tip Auxiliar", ccsInteger, "", CCGetRequestParam("cue_TipAuxiliar", $Method));
            $this->cue_TipAuxiliar->DSType = dsTable;
            list($this->cue_TipAuxiliar->BoundColumn, $this->cue_TipAuxiliar->TextColumn, $this->cue_TipAuxiliar->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->cue_TipAuxiliar->ds = new clsDBdatos();
            $this->cue_TipAuxiliar->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->cue_TipAuxiliar->ds->Order = "par_Descripcion";
            $this->cue_TipAuxiliar->ds->Parameters["expr87"] = 'CAUTI';
            $this->cue_TipAuxiliar->ds->wp = new clsSQLParameters();
            $this->cue_TipAuxiliar->ds->wp->AddParameter("1", "expr87", ccsText, "", "", $this->cue_TipAuxiliar->ds->Parameters["expr87"], "", false);
            $this->cue_TipAuxiliar->ds->wp->Criterion[1] = $this->cue_TipAuxiliar->ds->wp->Operation(opEqual, "par_Clave", $this->cue_TipAuxiliar->ds->wp->GetDBValue("1"), $this->cue_TipAuxiliar->ds->ToSQL($this->cue_TipAuxiliar->ds->wp->GetDBValue("1"), ccsText),false);
            $this->cue_TipAuxiliar->ds->Where = $this->cue_TipAuxiliar->ds->wp->Criterion[1];
            $this->cue_ReqRefOperat = new clsControl(ccsCheckBox, "cue_ReqRefOperat", "Cue Req Ref Operat", ccsInteger, "", CCGetRequestParam("cue_ReqRefOperat", $Method));
            $this->cue_ReqRefOperat->CheckedValue = $this->cue_ReqRefOperat->GetParsedValue(1);
            $this->cue_ReqRefOperat->UncheckedValue = $this->cue_ReqRefOperat->GetParsedValue(0);
            $this->cue_TipMovim = new clsControl(ccsCheckBox, "cue_TipMovim", "cue_TipMovim", ccsInteger, "", CCGetRequestParam("cue_TipMovim", $Method));
            $this->cue_TipMovim->CheckedValue = $this->cue_TipMovim->GetParsedValue(1);
            $this->cue_TipMovim->UncheckedValue = $this->cue_TipMovim->GetParsedValue(0);
            $this->Button_Insert = new clsButton("Button_Insert");
            $this->Button_Update = new clsButton("Button_Update");
            $this->Button_Delete = new clsButton("Button_Delete");
            $this->Button_Cancel = new clsButton("Button_Cancel");
            $this->btAgregar = new clsButton("btAgregar");
            if(!$this->FormSubmitted) {
                if(!is_array($this->hdCueId->Value) && !strlen($this->hdCueId->Value) && $this->hdCueId->Value !== false)
                $this->hdCueId->SetText(0);
                if(!is_array($this->Cue_Padre->Value) && !strlen($this->Cue_Padre->Value) && $this->Cue_Padre->Value !== false)
                $this->Cue_Padre->SetText(1);
                if(!is_array($this->cue_Posicion->Value) && !strlen($this->cue_Posicion->Value) && $this->cue_Posicion->Value !== false)
                $this->cue_Posicion->SetText(0);
                if(!is_array($this->cue_Estado->Value) && !strlen($this->cue_Estado->Value) && $this->cue_Estado->Value !== false)
                $this->cue_Estado->SetText(1);
                if(!is_array($this->cue_ReqAuxiliar->Value) && !strlen($this->cue_ReqAuxiliar->Value) && $this->cue_ReqAuxiliar->Value !== false)
                $this->cue_ReqAuxiliar->SetText(0);
                if(!is_array($this->cue_TipAuxiliar->Value) && !strlen($this->cue_TipAuxiliar->Value) && $this->cue_TipAuxiliar->Value !== false)
                $this->cue_TipAuxiliar->SetText(0);
                if(!is_array($this->cue_ReqRefOperat->Value) && !strlen($this->cue_ReqRefOperat->Value) && $this->cue_ReqRefOperat->Value !== false)
                $this->cue_ReqRefOperat->SetText(0);
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @41-0430CBD3
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->ds->Parameters["urlCue_ID"] = CCGetFromGet("Cue_ID", "");
    }
//End Initialize Method

//Validate Method @41-14537269
    function Validate()
    {
        $Validation = true;
        $Where = "";
        if($this->EditMode && strlen($this->ds->Where))
            $Where = " AND NOT (" . $this->ds->Where . ")";
        global $DBdatos;
        $this->ds->Cue_CodCuenta->SetValue($this->Cue_CodCuenta->GetValue());
        if(CCDLookUp("COUNT(*)", "concuentas", "Cue_CodCuenta=" . $this->ds->ToSQL($this->ds->Cue_CodCuenta->GetDBValue(), $this->ds->Cue_CodCuenta->DataType) . $Where, $DBdatos) > 0)
            $this->Cue_CodCuenta->Errors->addError("El campo Código de Cuenta ya existe.");
        $Validation = ($this->Cue_CodCuenta->Validate() && $Validation);
        $Validation = ($this->hdCueId->Validate() && $Validation);
        $Validation = ($this->Cue_Padre->Validate() && $Validation);
        $Validation = ($this->cue_Descripcion->Validate() && $Validation);
        $Validation = ($this->cue_Clase->Validate() && $Validation);
        $Validation = ($this->cue_Posicion->Validate() && $Validation);
        $Validation = ($this->cue_Estado->Validate() && $Validation);
        $Validation = ($this->cue_ReqAuxiliar->Validate() && $Validation);
        $Validation = ($this->cue_TipAuxiliar->Validate() && $Validation);
        $Validation = ($this->cue_ReqRefOperat->Validate() && $Validation);
        $Validation = ($this->cue_TipMovim->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @41-7E198FDE
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->lbTitulo->Errors->Count());
        $errors = ($errors || $this->Cue_CodCuenta->Errors->Count());
        $errors = ($errors || $this->lbCodCuenta->Errors->Count());
        $errors = ($errors || $this->hdCueId->Errors->Count());
        $errors = ($errors || $this->Cue_Padre->Errors->Count());
        $errors = ($errors || $this->cue_Descripcion->Errors->Count());
        $errors = ($errors || $this->cue_Clase->Errors->Count());
        $errors = ($errors || $this->cue_Posicion->Errors->Count());
        $errors = ($errors || $this->cue_Estado->Errors->Count());
        $errors = ($errors || $this->cue_ReqAuxiliar->Errors->Count());
        $errors = ($errors || $this->cue_TipAuxiliar->Errors->Count());
        $errors = ($errors || $this->cue_ReqRefOperat->Errors->Count());
        $errors = ($errors || $this->cue_TipMovim->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @41-CEB3B8ED
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
            } else if(strlen(CCGetParam("btAgregar", ""))) {
                $this->PressedButton = "btAgregar";
            }
        }
        $Redirect = "CoAdCu_mant.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "Button_Delete") {
            if(!CCGetEvent($this->Button_Delete->CCSEvents, "OnClick") || !$this->DeleteRow()) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "Button_Cancel") {
            if(!CCGetEvent($this->Button_Cancel->CCSEvents, "OnClick")) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "btAgregar") {
            if(!CCGetEvent($this->btAgregar->CCSEvents, "OnClick")) {
                $Redirect = "";
            } else {
                $Redirect = "CoAdCu_mant.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm", "Cue_ID"));
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

//InsertRow Method @41-607F91BD
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert");
        if(!$this->InsertAllowed) return false;
        $this->ds->Cue_CodCuenta->SetValue($this->Cue_CodCuenta->GetValue());
        $this->ds->Cue_Padre->SetValue($this->Cue_Padre->GetValue());
        $this->ds->cue_Descripcion->SetValue($this->cue_Descripcion->GetValue());
        $this->ds->cue_Clase->SetValue($this->cue_Clase->GetValue());
        $this->ds->cue_Posicion->SetValue($this->cue_Posicion->GetValue());
        $this->ds->cue_Estado->SetValue($this->cue_Estado->GetValue());
        $this->ds->cue_ReqAuxiliar->SetValue($this->cue_ReqAuxiliar->GetValue());
        $this->ds->cue_TipAuxiliar->SetValue($this->cue_TipAuxiliar->GetValue());
        $this->ds->cue_ReqRefOperat->SetValue($this->cue_ReqRefOperat->GetValue());
        $this->ds->cue_TipMovim->SetValue($this->cue_TipMovim->GetValue());
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

//UpdateRow Method @41-AD0D92D6
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate");
        if(!$this->UpdateAllowed) return false;
        $this->ds->Cue_Padre->SetValue($this->Cue_Padre->GetValue());
        $this->ds->cue_Descripcion->SetValue($this->cue_Descripcion->GetValue());
        $this->ds->cue_Clase->SetValue($this->cue_Clase->GetValue());
        $this->ds->cue_Posicion->SetValue($this->cue_Posicion->GetValue());
        $this->ds->cue_Estado->SetValue($this->cue_Estado->GetValue());
        $this->ds->cue_ReqAuxiliar->SetValue($this->cue_ReqAuxiliar->GetValue());
        $this->ds->cue_TipAuxiliar->SetValue($this->cue_TipAuxiliar->GetValue());
        $this->ds->cue_ReqRefOperat->SetValue($this->cue_ReqRefOperat->GetValue());
        $this->ds->cue_TipMovim->SetValue($this->cue_TipMovim->GetValue());
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

//DeleteRow Method @41-EA88835F
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

//Show Method @41-826C347C
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->Cue_Padre->Prepare();
        $this->cue_Clase->Prepare();
        $this->cue_TipAuxiliar->Prepare();

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
                    echo "Error in Record CoAdCu_mant";
                }
                else if($this->ds->next_record())
                {
                    $this->ds->SetValues();
                    $this->lbCodCuenta->SetValue($this->ds->lbCodCuenta->GetValue());
                    if(!$this->FormSubmitted)
                    {
                        $this->Cue_CodCuenta->SetValue($this->ds->Cue_CodCuenta->GetValue());
                        $this->hdCueId->SetValue($this->ds->hdCueId->GetValue());
                        $this->Cue_Padre->SetValue($this->ds->Cue_Padre->GetValue());
                        $this->cue_Descripcion->SetValue($this->ds->cue_Descripcion->GetValue());
                        $this->cue_Clase->SetValue($this->ds->cue_Clase->GetValue());
                        $this->cue_Posicion->SetValue($this->ds->cue_Posicion->GetValue());
                        $this->cue_Estado->SetValue($this->ds->cue_Estado->GetValue());
                        $this->cue_ReqAuxiliar->SetValue($this->ds->cue_ReqAuxiliar->GetValue());
                        $this->cue_TipAuxiliar->SetValue($this->ds->cue_TipAuxiliar->GetValue());
                        $this->cue_ReqRefOperat->SetValue($this->ds->cue_ReqRefOperat->GetValue());
                        $this->cue_TipMovim->SetValue($this->ds->cue_TipMovim->GetValue());
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
            $this->lbTitulo->SetText("AGREGAR CUENTA");
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->lbTitulo->Errors->ToString();
            $Error .= $this->Cue_CodCuenta->Errors->ToString();
            $Error .= $this->lbCodCuenta->Errors->ToString();
            $Error .= $this->hdCueId->Errors->ToString();
            $Error .= $this->Cue_Padre->Errors->ToString();
            $Error .= $this->cue_Descripcion->Errors->ToString();
            $Error .= $this->cue_Clase->Errors->ToString();
            $Error .= $this->cue_Posicion->Errors->ToString();
            $Error .= $this->cue_Estado->Errors->ToString();
            $Error .= $this->cue_ReqAuxiliar->Errors->ToString();
            $Error .= $this->cue_TipAuxiliar->Errors->ToString();
            $Error .= $this->cue_ReqRefOperat->Errors->ToString();
            $Error .= $this->cue_TipMovim->Errors->ToString();
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
        
        //----------  Modificacion para Habilitar / deshabilitar Botones Segun perfil del usuario
        //print_r($_SESSION);
        $aOpc = array();
        if ($this->InsertAllowed) $aOpc[]="ADD";
        if ($this->InsertAllowed) $aOpc[]="UPD";
        if ($this->InsertAllowed) $aOpc[]="DEL";
        fHabilitaBotonesCCS(false, $aOpc, 1);
       
        $this->lbTitulo->Show();
        $this->Cue_CodCuenta->Show();
        $this->lbCodCuenta->Show();
        $this->hdCueId->Show();
        $this->Cue_Padre->Show();
        $this->cue_Descripcion->Show();
        $this->cue_Clase->Show();
        $this->cue_Posicion->Show();
        $this->cue_Estado->Show();
        $this->cue_ReqAuxiliar->Show();
        $this->cue_TipAuxiliar->Show();
        $this->cue_ReqRefOperat->Show();
        $this->cue_TipMovim->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->Button_Cancel->Show();
        $this->btAgregar->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

} //End CoAdCu_mant Class @41-FCB6E20C

class clsCoAdCu_mantDataSource extends clsDBdatos {  //CoAdCu_mantDataSource Class @41-70856AB7

//DataSource Variables @41-5DB701D4
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
    var $Cue_CodCuenta;
    var $lbCodCuenta;
    var $hdCueId;
    var $Cue_Padre;
    var $cue_Descripcion;
    var $cue_Clase;
    var $cue_Posicion;
    var $cue_Estado;
    var $cue_ReqAuxiliar;
    var $cue_TipAuxiliar;
    var $cue_ReqRefOperat;
    var $cue_TipMovim;
//End DataSource Variables

//Class_Initialize Event @41-5879311D
    function clsCoAdCu_mantDataSource()
    {
        $this->ErrorBlock = "Record CoAdCu_mant/Error";
        $this->Initialize();
        $this->lbTitulo = new clsField("lbTitulo", ccsText, "");
        $this->Cue_CodCuenta = new clsField("Cue_CodCuenta", ccsText, "");
        $this->lbCodCuenta = new clsField("lbCodCuenta", ccsText, "");
        $this->hdCueId = new clsField("hdCueId", ccsText, "");
        $this->Cue_Padre = new clsField("Cue_Padre", ccsInteger, "");
        $this->cue_Descripcion = new clsField("cue_Descripcion", ccsText, "");
        $this->cue_Clase = new clsField("cue_Clase", ccsInteger, "");
        $this->cue_Posicion = new clsField("cue_Posicion", ccsInteger, "");
        $this->cue_Estado = new clsField("cue_Estado", ccsInteger, "");
        $this->cue_ReqAuxiliar = new clsField("cue_ReqAuxiliar", ccsInteger, "");
        $this->cue_TipAuxiliar = new clsField("cue_TipAuxiliar", ccsInteger, "");
        $this->cue_ReqRefOperat = new clsField("cue_ReqRefOperat", ccsInteger, "");
        $this->cue_TipMovim = new clsField("cue_TipMovim", ccsInteger, "");

    }
//End Class_Initialize Event

//Prepare Method @41-B064CC04
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlCue_ID", ccsInteger, "", "", $this->Parameters["urlCue_ID"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "Cue_ID", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @41-790293FA
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->SQL = "SELECT *  " .
        "FROM concuentas";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @41-5C71460F
    function SetValues()
    {
        $this->Cue_CodCuenta->SetDBValue($this->f("Cue_CodCuenta"));
        $this->lbCodCuenta->SetDBValue($this->f("Cue_CodCuenta"));
        $this->hdCueId->SetDBValue($this->f("Cue_ID"));
        $this->Cue_Padre->SetDBValue(trim($this->f("Cue_Padre")));
        $this->cue_Descripcion->SetDBValue($this->f("cue_Descripcion"));
        $this->cue_Clase->SetDBValue(trim($this->f("cue_Clase")));
        $this->cue_Posicion->SetDBValue(trim($this->f("cue_Posicion")));
        $this->cue_Estado->SetDBValue(trim($this->f("cue_Estado")));
        $this->cue_ReqAuxiliar->SetDBValue(trim($this->f("cue_ReqAuxiliar")));
        $this->cue_TipAuxiliar->SetDBValue(trim($this->f("cue_TipAuxiliar")));
        $this->cue_ReqRefOperat->SetDBValue(trim($this->f("cue_ReqRefOperat")));
        $this->cue_TipMovim->SetDBValue(trim($this->f("cue_TipMovim")));
    }
//End SetValues Method

//Insert Method @41-6A021DBB
    function Insert()
    {
        $this->cp["Cue_CodCuenta"] = new clsSQLParameter("ctrlCue_CodCuenta", ccsText, "", "", $this->Cue_CodCuenta->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["Cue_Padre"] = new clsSQLParameter("ctrlCue_Padre", ccsInteger, "", "", $this->Cue_Padre->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["cue_Descripcion"] = new clsSQLParameter("ctrlcue_Descripcion", ccsText, "", "", $this->cue_Descripcion->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["cue_Clase"] = new clsSQLParameter("ctrlcue_Clase", ccsInteger, "", "", $this->cue_Clase->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["cue_Posicion"] = new clsSQLParameter("ctrlcue_Posicion", ccsInteger, "", "", $this->cue_Posicion->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["cue_Estado"] = new clsSQLParameter("ctrlcue_Estado", ccsInteger, "", "", $this->cue_Estado->GetValue(), 1, false, $this->ErrorBlock);
        $this->cp["cue_ReqAuxiliar"] = new clsSQLParameter("ctrlcue_ReqAuxiliar", ccsInteger, "", "", $this->cue_ReqAuxiliar->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["cue_TipAuxiliar"] = new clsSQLParameter("ctrlcue_TipAuxiliar", ccsInteger, "", "", $this->cue_TipAuxiliar->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["cue_ReqRefOperat"] = new clsSQLParameter("ctrlcue_ReqRefOperat", ccsInteger, "", "", $this->cue_ReqRefOperat->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["cue_TipMovim"] = new clsSQLParameter("ctrlcue_TipMovim", ccsInteger, "", "", $this->cue_TipMovim->GetValue(), 0, false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO concuentas ("
             . "Cue_CodCuenta, "
             . "Cue_Padre, "
             . "cue_Descripcion, "
             . "cue_Clase, "
             . "cue_Posicion, "
             . "cue_Estado, "
             . "cue_ReqAuxiliar, "
             . "cue_TipAuxiliar, "
             . "cue_ReqRefOperat, "
             . "cue_TipMovim"
             . ") VALUES ("
             . $this->ToSQL($this->cp["Cue_CodCuenta"]->GetDBValue(), $this->cp["Cue_CodCuenta"]->DataType) . ", "
             . $this->ToSQL($this->cp["Cue_Padre"]->GetDBValue(), $this->cp["Cue_Padre"]->DataType) . ", "
             . $this->ToSQL($this->cp["cue_Descripcion"]->GetDBValue(), $this->cp["cue_Descripcion"]->DataType) . ", "
             . $this->ToSQL($this->cp["cue_Clase"]->GetDBValue(), $this->cp["cue_Clase"]->DataType) . ", "
             . $this->ToSQL($this->cp["cue_Posicion"]->GetDBValue(), $this->cp["cue_Posicion"]->DataType) . ", "
             . $this->ToSQL($this->cp["cue_Estado"]->GetDBValue(), $this->cp["cue_Estado"]->DataType) . ", "
             . $this->ToSQL($this->cp["cue_ReqAuxiliar"]->GetDBValue(), $this->cp["cue_ReqAuxiliar"]->DataType) . ", "
             . $this->ToSQL($this->cp["cue_TipAuxiliar"]->GetDBValue(), $this->cp["cue_TipAuxiliar"]->DataType) . ", "
             . $this->ToSQL($this->cp["cue_ReqRefOperat"]->GetDBValue(), $this->cp["cue_ReqRefOperat"]->DataType) . ", "
             . $this->ToSQL($this->cp["cue_TipMovim"]->GetDBValue(), $this->cp["cue_TipMovim"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        CoAdCu_mant_AfterInsert();
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Insert Method

//Update Method @41-4E47C8BE
    function Update()
    {
        $this->cp["Cue_Padre"] = new clsSQLParameter("ctrlCue_Padre", ccsInteger, "", "", $this->Cue_Padre->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["cue_Descripcion"] = new clsSQLParameter("ctrlcue_Descripcion", ccsText, "", "", $this->cue_Descripcion->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["cue_Clase"] = new clsSQLParameter("ctrlcue_Clase", ccsInteger, "", "", $this->cue_Clase->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["cue_Posicion"] = new clsSQLParameter("ctrlcue_Posicion", ccsInteger, "", "", $this->cue_Posicion->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["cue_Estado"] = new clsSQLParameter("ctrlcue_Estado", ccsInteger, "", "", $this->cue_Estado->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["cue_ReqAuxiliar"] = new clsSQLParameter("ctrlcue_ReqAuxiliar", ccsInteger, "", "", $this->cue_ReqAuxiliar->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["cue_TipAuxiliar"] = new clsSQLParameter("ctrlcue_TipAuxiliar", ccsInteger, "", "", $this->cue_TipAuxiliar->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["cue_ReqRefOperat"] = new clsSQLParameter("ctrlcue_ReqRefOperat", ccsInteger, "", "", $this->cue_ReqRefOperat->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["cue_TipMovim"] = new clsSQLParameter("ctrlcue_TipMovim", ccsInteger, "", "", $this->cue_TipMovim->GetValue(), 0, false, $this->ErrorBlock);
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlCue_ID", ccsInteger, "", "", CCGetFromGet("Cue_ID", ""), "", false);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $wp->Criterion[1] = $wp->Operation(opEqual, "Cue_ID", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),false);
        $Where = $wp->Criterion[1];
        $this->SQL = "UPDATE concuentas SET "
             . "Cue_Padre=" . $this->ToSQL($this->cp["Cue_Padre"]->GetDBValue(), $this->cp["Cue_Padre"]->DataType) . ", "
             . "cue_Descripcion=" . $this->ToSQL($this->cp["cue_Descripcion"]->GetDBValue(), $this->cp["cue_Descripcion"]->DataType) . ", "
             . "cue_Clase=" . $this->ToSQL($this->cp["cue_Clase"]->GetDBValue(), $this->cp["cue_Clase"]->DataType) . ", "
             . "cue_Posicion=" . $this->ToSQL($this->cp["cue_Posicion"]->GetDBValue(), $this->cp["cue_Posicion"]->DataType) . ", "
             . "cue_Estado=" . $this->ToSQL($this->cp["cue_Estado"]->GetDBValue(), $this->cp["cue_Estado"]->DataType) . ", "
             . "cue_ReqAuxiliar=" . $this->ToSQL($this->cp["cue_ReqAuxiliar"]->GetDBValue(), $this->cp["cue_ReqAuxiliar"]->DataType) . ", "
             . "cue_TipAuxiliar=" . $this->ToSQL($this->cp["cue_TipAuxiliar"]->GetDBValue(), $this->cp["cue_TipAuxiliar"]->DataType) . ", "
             . "cue_ReqRefOperat=" . $this->ToSQL($this->cp["cue_ReqRefOperat"]->GetDBValue(), $this->cp["cue_ReqRefOperat"]->DataType) . ", "
             . "cue_TipMovim=" . $this->ToSQL($this->cp["cue_TipMovim"]->GetDBValue(), $this->cp["cue_TipMovim"]->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
//        fMensaje ("REGISTRO MODIFICADO 0");
        CoAdCu_mant_AfterInsert();
//        fMensaje ("REGISTRO MODIFICADO 3");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Update Method

//Delete Method @41-D0BB5B5A
    function Delete()
    {
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlCue_ID", ccsInteger, "", "", CCGetFromGet("Cue_ID", ""), "", false);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $wp->Criterion[1] = $wp->Operation(opEqual, "Cue_ID", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),false);
        $Where = $wp->Criterion[1];
        $this->SQL = "DELETE FROM concuentas";
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
//        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete"); ????
        $this->CCSEventResult = CoAdCu_mant_BeforeExecuteDelete();
        if($this->CCSEventResult) {     // fah: Asegurarse de no procesar en caso de que el evento sea negado
			$this->query($this->SQL);
        	$this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        }
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Delete Method

} //End CoAdCu_mantDataSource Class @41-FCB6E20C

// Inicializacion de variables necesarias para rastreo de cuentas
$ignivel = 0;
$p_mode   = "R";
$igCuenta = 0;
$igPosic = 0;

//Initialize Page @1-48CE1266
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

$FileName = "CoAdCu_mant.php";
$Redirect = "";
$TemplateFileName = "CoAdCu_mant.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-4CF75D2E
$DBdatos = new clsDBdatos();

// Controls
$CoAdCu_mant = new clsRecordCoAdCu_mant();
$CoAdCu_mant->Initialize();

// Events
include("./CoAdCu_mant_events.php");
BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-C6438D60
$CoAdCu_mant->Operation();
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

//Show Page @1-9A9A4A34
$CoAdCu_mant->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
$generated_with = "<right><font face=\"Arial\"><small>AAA</small></font></right>";
$main_block = preg_match("/<\/body>/i", $main_block) ? preg_replace("/<\/body>/i", $generated_with . "</body>", $main_block) : $main_block . $generated_with;
echo $main_block;
//End Show Page

//Unload Page @1-6786D1ED
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBdatos->close();
unset($Tpl);
//End Unload Page


?>
