<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @92-49BEB8FC
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation



Class clsRecordcjas_mant { //cjas_mant Class @63-943C91A1

//Variables @63-4A82E0A3

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

//Class_Initialize Event @63-CE1E0B51
    function clsRecordcjas_mant()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record cjas_mant/Error";
        $this->ds = new clscjas_mantDataSource();
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "cjas_mant";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->lbTitulo = new clsControl(ccsLabel, "lbTitulo", "lbTitulo", ccsText, "", CCGetRequestParam("lbTitulo", $Method));
            $this->caj_CodMarca = new clsControl(ccsListBox, "caj_CodMarca", "Caj Cod Marca", ccsInteger, "", CCGetRequestParam("caj_CodMarca", $Method));
            $this->caj_CodMarca->DSType = dsTable;
            list($this->caj_CodMarca->BoundColumn, $this->caj_CodMarca->TextColumn, $this->caj_CodMarca->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->caj_CodMarca->ds = new clsDBdatos();
            $this->caj_CodMarca->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->caj_CodMarca->ds->Parameters["expr65"] = 'IMARCA';
            $this->caj_CodMarca->ds->wp = new clsSQLParameters();
            $this->caj_CodMarca->ds->wp->AddParameter("1", "expr65", ccsText, "", "", $this->caj_CodMarca->ds->Parameters["expr65"], "", false);
            $this->caj_CodMarca->ds->wp->Criterion[1] = $this->caj_CodMarca->ds->wp->Operation(opEqual, "par_Clave", $this->caj_CodMarca->ds->wp->GetDBValue("1"), $this->caj_CodMarca->ds->ToSQL($this->caj_CodMarca->ds->wp->GetDBValue("1"), ccsText),false);
            $this->caj_CodMarca->ds->Where = $this->caj_CodMarca->ds->wp->Criterion[1];
            $this->caj_CodMarca->Required = true;
            $this->caj_Abreviatura = new clsControl(ccsTextBox, "caj_Abreviatura", "Caj Abreviatura", ccsText, "", CCGetRequestParam("caj_Abreviatura", $Method));
            $this->caj_Abreviatura->Required = true;
            $this->caj_Descripcion = new clsControl(ccsTextBox, "caj_Descripcion", "Caj Descripcion", ccsText, "", CCGetRequestParam("caj_Descripcion", $Method));
            $this->caj_Descripcion->Required = true;
            $this->caj_TipoCaja = new clsControl(ccsTextBox, "caj_TipoCaja", "Caj Tipo Caja", ccsText, "", CCGetRequestParam("caj_TipoCaja", $Method));
            $this->caj_Componente1 = new clsControl(ccsListBox, "caj_Componente1", "Tipo de Carton", ccsInteger, "", CCGetRequestParam("caj_Componente1", $Method));
            $this->caj_Componente1->DSType = dsSQL;
            list($this->caj_Componente1->BoundColumn, $this->caj_Componente1->TextColumn, $this->caj_Componente1->DBFormat) = array("cte_codigo", "cte_refer", "");
            $this->caj_Componente1->ds = new clsDBdatos();
            $this->caj_Componente1->ds->SQL = "SELECT cte_codigo, concat(cte_referencia, '- ', cte_descripcion) as cte_refer " .
            "FROM liqcomponent " .
            "WHERE cte_Clase = 1 " .
            "";
            $this->caj_Componente1->ds->Order = "2";
            $this->caj_Componente1->Required = true;
            $this->caj_Componente2 = new clsControl(ccsListBox, "caj_Componente2", "Tipo de Plástico", ccsInteger, "", CCGetRequestParam("caj_Componente2", $Method));
            $this->caj_Componente2->DSType = dsSQL;
            list($this->caj_Componente2->BoundColumn, $this->caj_Componente2->TextColumn, $this->caj_Componente2->DBFormat) = array("cte_codigo", "cte_refer", "");
            $this->caj_Componente2->ds = new clsDBdatos();
            $this->caj_Componente2->ds->SQL = "SELECT cte_codigo, concat(cte_referencia, '- ', cte_descripcion) as cte_refer " .
            "FROM liqcomponent " .
            "WHERE cte_Clase = 2 " .
            "";
            $this->caj_Componente2->ds->Order = "2";
            $this->caj_Componente2->Required = true;
            $this->caj_Componente3 = new clsControl(ccsListBox, "caj_Componente3", "Tipo de Material Empaque", ccsInteger, "", CCGetRequestParam("caj_Componente3", $Method));
            $this->caj_Componente3->DSType = dsSQL;
            list($this->caj_Componente3->BoundColumn, $this->caj_Componente3->TextColumn, $this->caj_Componente3->DBFormat) = array("cte_codigo", "cte_refer", "");
            $this->caj_Componente3->ds = new clsDBdatos();
            $this->caj_Componente3->ds->Parameters["expr87"] = 3;
            $this->caj_Componente3->ds->wp = new clsSQLParameters();
            $this->caj_Componente3->ds->wp->AddParameter("1", "expr87", ccsInteger, "", "", $this->caj_Componente3->ds->Parameters["expr87"], 0, false);
            $this->caj_Componente3->ds->SQL = "SELECT cte_codigo, concat(cte_referencia, '- ', cte_descripcion) as cte_refer " .
            "FROM liqcomponent " .
            "WHERE cte_Clase = 3 " .
            "";
            $this->caj_Componente3->ds->Order = "2";
            $this->caj_Componente3->Required = true;
            $this->caj_Componente4 = new clsControl(ccsListBox, "caj_Componente4", "Tipo de Etiqueta", ccsInteger, "", CCGetRequestParam("caj_Componente4", $Method));
            $this->caj_Componente4->DSType = dsSQL;
            list($this->caj_Componente4->BoundColumn, $this->caj_Componente4->TextColumn, $this->caj_Componente4->DBFormat) = array("cte_codigo", "cte_refer", "");
            $this->caj_Componente4->ds = new clsDBdatos();
            $this->caj_Componente4->ds->SQL = "SELECT cte_codigo, concat(cte_referencia, '- ', cte_descripcion) as cte_refer " .
            "FROM liqcomponent " .
            "WHERE cte_Clase = 4 " .
            "";
            $this->caj_Componente4->ds->Order = "2";
            $this->caj_Componente4->Required = true;
            $this->caj_Largo = new clsControl(ccsTextBox, "caj_Largo", "Caj Largo", ccsFloat, "", CCGetRequestParam("caj_Largo", $Method));
            $this->caj_Largo->Required = true;
            $this->caj_Ancho = new clsControl(ccsTextBox, "caj_Ancho", "Caj Ancho", ccsFloat, "", CCGetRequestParam("caj_Ancho", $Method));
            $this->caj_Ancho->Required = true;
            $this->caj_Fondo = new clsControl(ccsTextBox, "caj_Fondo", "Caj Fondo", ccsFloat, "", CCGetRequestParam("caj_Fondo", $Method));
            $this->caj_Fondo->Required = true;
            $this->caj_PulCentim = new clsControl(ccsListBox, "caj_PulCentim", "Caj Pul Centim", ccsInteger, "", CCGetRequestParam("caj_PulCentim", $Method));
            $this->caj_PulCentim->DSType = dsListOfValues;
            $this->caj_PulCentim->Values = array(array("1", "Cms"), array("2", "Pgd"));
            $this->caj_PulCentim->Required = true;
            $this->caj_Capacidad = new clsControl(ccsTextBox, "caj_Capacidad", "Caj Capacidad", ccsFloat, "", CCGetRequestParam("caj_Capacidad", $Method));
            $this->caj_Capacidad->Required = true;
            $this->caj_KilLibras = new clsControl(ccsListBox, "caj_KilLibras", "Caj Kil Libras", ccsInteger, "", CCGetRequestParam("caj_KilLibras", $Method));
            $this->caj_KilLibras->DSType = dsListOfValues;
            $this->caj_KilLibras->Values = array(array("1", "Lbs"), array("2", "Kg"));
            $this->caj_KilLibras->Required = true;
            $this->Button_Insert = new clsButton("Button_Insert");
            $this->Button_Update = new clsButton("Button_Update");
            $this->Button_Delete = new clsButton("Button_Delete");
            $this->Button_Cancel = new clsButton("Button_Cancel");
            $this->btRegresar = new clsButton("btRegresar");
            if(!$this->FormSubmitted) {
                if(!is_array($this->caj_Largo->Value) && !strlen($this->caj_Largo->Value) && $this->caj_Largo->Value !== false)
                $this->caj_Largo->SetText(0);
                if(!is_array($this->caj_Ancho->Value) && !strlen($this->caj_Ancho->Value) && $this->caj_Ancho->Value !== false)
                $this->caj_Ancho->SetText(0);
                if(!is_array($this->caj_Fondo->Value) && !strlen($this->caj_Fondo->Value) && $this->caj_Fondo->Value !== false)
                $this->caj_Fondo->SetText(0);
                if(!is_array($this->caj_PulCentim->Value) && !strlen($this->caj_PulCentim->Value) && $this->caj_PulCentim->Value !== false)
                $this->caj_PulCentim->SetText(1);
                if(!is_array($this->caj_Capacidad->Value) && !strlen($this->caj_Capacidad->Value) && $this->caj_Capacidad->Value !== false)
                $this->caj_Capacidad->SetText(0);
                if(!is_array($this->caj_KilLibras->Value) && !strlen($this->caj_KilLibras->Value) && $this->caj_KilLibras->Value !== false)
                $this->caj_KilLibras->SetText(1);
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @63-2D73F437
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->ds->Parameters["urlcaj_CodCaja"] = CCGetFromGet("caj_CodCaja", "");
    }
//End Initialize Method

//Validate Method @63-4414A78E
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->caj_CodMarca->Validate() && $Validation);
        $Validation = ($this->caj_Abreviatura->Validate() && $Validation);
        $Validation = ($this->caj_Descripcion->Validate() && $Validation);
        $Validation = ($this->caj_TipoCaja->Validate() && $Validation);
        $Validation = ($this->caj_Componente1->Validate() && $Validation);
        $Validation = ($this->caj_Componente2->Validate() && $Validation);
        $Validation = ($this->caj_Componente3->Validate() && $Validation);
        $Validation = ($this->caj_Componente4->Validate() && $Validation);
        $Validation = ($this->caj_Largo->Validate() && $Validation);
        $Validation = ($this->caj_Ancho->Validate() && $Validation);
        $Validation = ($this->caj_Fondo->Validate() && $Validation);
        $Validation = ($this->caj_PulCentim->Validate() && $Validation);
        $Validation = ($this->caj_Capacidad->Validate() && $Validation);
        $Validation = ($this->caj_KilLibras->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @63-A2BA6044
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->lbTitulo->Errors->Count());
        $errors = ($errors || $this->caj_CodMarca->Errors->Count());
        $errors = ($errors || $this->caj_Abreviatura->Errors->Count());
        $errors = ($errors || $this->caj_Descripcion->Errors->Count());
        $errors = ($errors || $this->caj_TipoCaja->Errors->Count());
        $errors = ($errors || $this->caj_Componente1->Errors->Count());
        $errors = ($errors || $this->caj_Componente2->Errors->Count());
        $errors = ($errors || $this->caj_Componente3->Errors->Count());
        $errors = ($errors || $this->caj_Componente4->Errors->Count());
        $errors = ($errors || $this->caj_Largo->Errors->Count());
        $errors = ($errors || $this->caj_Ancho->Errors->Count());
        $errors = ($errors || $this->caj_Fondo->Errors->Count());
        $errors = ($errors || $this->caj_PulCentim->Errors->Count());
        $errors = ($errors || $this->caj_Capacidad->Errors->Count());
        $errors = ($errors || $this->caj_KilLibras->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @63-CF8EEDA6
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
            }
        }
        $Redirect = "LiAdTc_mant.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
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
                $Redirect = "LiAdTc.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm", "caj_CodCaja"));
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

//InsertRow Method @63-DE6E097C
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert");
        if(!$this->InsertAllowed) return false;
        $this->ds->caj_CodMarca->SetValue($this->caj_CodMarca->GetValue());
        $this->ds->caj_Abreviatura->SetValue($this->caj_Abreviatura->GetValue());
        $this->ds->caj_Descripcion->SetValue($this->caj_Descripcion->GetValue());
        $this->ds->caj_TipoCaja->SetValue($this->caj_TipoCaja->GetValue());
        $this->ds->caj_Componente1->SetValue($this->caj_Componente1->GetValue());
        $this->ds->caj_Componente2->SetValue($this->caj_Componente2->GetValue());
        $this->ds->caj_Componente3->SetValue($this->caj_Componente3->GetValue());
        $this->ds->caj_Componente4->SetValue($this->caj_Componente4->GetValue());
        $this->ds->caj_Largo->SetValue($this->caj_Largo->GetValue());
        $this->ds->caj_Ancho->SetValue($this->caj_Ancho->GetValue());
        $this->ds->caj_Fondo->SetValue($this->caj_Fondo->GetValue());
        $this->ds->caj_PulCentim->SetValue($this->caj_PulCentim->GetValue());
        $this->ds->caj_Capacidad->SetValue($this->caj_Capacidad->GetValue());
        $this->ds->caj_KilLibras->SetValue($this->caj_KilLibras->GetValue());
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

//UpdateRow Method @63-1E1F1EAA
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate");
        if(!$this->UpdateAllowed) return false;
        $this->ds->caj_CodMarca->SetValue($this->caj_CodMarca->GetValue());
        $this->ds->caj_Abreviatura->SetValue($this->caj_Abreviatura->GetValue());
        $this->ds->caj_Descripcion->SetValue($this->caj_Descripcion->GetValue());
        $this->ds->caj_TipoCaja->SetValue($this->caj_TipoCaja->GetValue());
        $this->ds->caj_Componente1->SetValue($this->caj_Componente1->GetValue());
        $this->ds->caj_Componente2->SetValue($this->caj_Componente2->GetValue());
        $this->ds->caj_Componente3->SetValue($this->caj_Componente3->GetValue());
        $this->ds->caj_Componente4->SetValue($this->caj_Componente4->GetValue());
        $this->ds->caj_Largo->SetValue($this->caj_Largo->GetValue());
        $this->ds->caj_Ancho->SetValue($this->caj_Ancho->GetValue());
        $this->ds->caj_Fondo->SetValue($this->caj_Fondo->GetValue());
        $this->ds->caj_PulCentim->SetValue($this->caj_PulCentim->GetValue());
        $this->ds->caj_Capacidad->SetValue($this->caj_Capacidad->GetValue());
        $this->ds->caj_KilLibras->SetValue($this->caj_KilLibras->GetValue());
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

//DeleteRow Method @63-EA88835F
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

//Show Method @63-4B688675
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->caj_CodMarca->Prepare();
        $this->caj_Componente1->Prepare();
        $this->caj_Componente2->Prepare();
        $this->caj_Componente3->Prepare();
        $this->caj_Componente4->Prepare();
        $this->caj_PulCentim->Prepare();
        $this->caj_KilLibras->Prepare();

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
                    echo "Error in Record cjas_mant";
                }
                else if($this->ds->next_record())
                {
                    $this->ds->SetValues();
                    if(!$this->FormSubmitted)
                    {
                        $this->caj_CodMarca->SetValue($this->ds->caj_CodMarca->GetValue());
                        $this->caj_Abreviatura->SetValue($this->ds->caj_Abreviatura->GetValue());
                        $this->caj_Descripcion->SetValue($this->ds->caj_Descripcion->GetValue());
                        $this->caj_TipoCaja->SetValue($this->ds->caj_TipoCaja->GetValue());
                        $this->caj_Componente1->SetValue($this->ds->caj_Componente1->GetValue());
                        $this->caj_Componente2->SetValue($this->ds->caj_Componente2->GetValue());
                        $this->caj_Componente3->SetValue($this->ds->caj_Componente3->GetValue());
                        $this->caj_Componente4->SetValue($this->ds->caj_Componente4->GetValue());
                        $this->caj_Largo->SetValue($this->ds->caj_Largo->GetValue());
                        $this->caj_Ancho->SetValue($this->ds->caj_Ancho->GetValue());
                        $this->caj_Fondo->SetValue($this->ds->caj_Fondo->GetValue());
                        $this->caj_PulCentim->SetValue($this->ds->caj_PulCentim->GetValue());
                        $this->caj_Capacidad->SetValue($this->ds->caj_Capacidad->GetValue());
                        $this->caj_KilLibras->SetValue($this->ds->caj_KilLibras->GetValue());
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
            $Error .= $this->caj_CodMarca->Errors->ToString();
            $Error .= $this->caj_Abreviatura->Errors->ToString();
            $Error .= $this->caj_Descripcion->Errors->ToString();
            $Error .= $this->caj_TipoCaja->Errors->ToString();
            $Error .= $this->caj_Componente1->Errors->ToString();
            $Error .= $this->caj_Componente2->Errors->ToString();
            $Error .= $this->caj_Componente3->Errors->ToString();
            $Error .= $this->caj_Componente4->Errors->ToString();
            $Error .= $this->caj_Largo->Errors->ToString();
            $Error .= $this->caj_Ancho->Errors->ToString();
            $Error .= $this->caj_Fondo->Errors->ToString();
            $Error .= $this->caj_PulCentim->Errors->ToString();
            $Error .= $this->caj_Capacidad->Errors->ToString();
            $Error .= $this->caj_KilLibras->Errors->ToString();
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
        $this->caj_CodMarca->Show();
        $this->caj_Abreviatura->Show();
        $this->caj_Descripcion->Show();
        $this->caj_TipoCaja->Show();
        $this->caj_Componente1->Show();
        $this->caj_Componente2->Show();
        $this->caj_Componente3->Show();
        $this->caj_Componente4->Show();
        $this->caj_Largo->Show();
        $this->caj_Ancho->Show();
        $this->caj_Fondo->Show();
        $this->caj_PulCentim->Show();
        $this->caj_Capacidad->Show();
        $this->caj_KilLibras->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->Button_Cancel->Show();
        $this->btRegresar->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

} //End cjas_mant Class @63-FCB6E20C

class clscjas_mantDataSource extends clsDBdatos {  //cjas_mantDataSource Class @63-6F4C8612

//DataSource Variables @63-B97C2AEA
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
    var $caj_CodMarca;
    var $caj_Abreviatura;
    var $caj_Descripcion;
    var $caj_TipoCaja;
    var $caj_Componente1;
    var $caj_Componente2;
    var $caj_Componente3;
    var $caj_Componente4;
    var $caj_Largo;
    var $caj_Ancho;
    var $caj_Fondo;
    var $caj_PulCentim;
    var $caj_Capacidad;
    var $caj_KilLibras;
//End DataSource Variables

//Class_Initialize Event @63-2E85C19A
    function clscjas_mantDataSource()
    {
        $this->ErrorBlock = "Record cjas_mant/Error";
        $this->Initialize();
        $this->lbTitulo = new clsField("lbTitulo", ccsText, "");
        $this->caj_CodMarca = new clsField("caj_CodMarca", ccsInteger, "");
        $this->caj_Abreviatura = new clsField("caj_Abreviatura", ccsText, "");
        $this->caj_Descripcion = new clsField("caj_Descripcion", ccsText, "");
        $this->caj_TipoCaja = new clsField("caj_TipoCaja", ccsText, "");
        $this->caj_Componente1 = new clsField("caj_Componente1", ccsInteger, "");
        $this->caj_Componente2 = new clsField("caj_Componente2", ccsInteger, "");
        $this->caj_Componente3 = new clsField("caj_Componente3", ccsInteger, "");
        $this->caj_Componente4 = new clsField("caj_Componente4", ccsInteger, "");
        $this->caj_Largo = new clsField("caj_Largo", ccsFloat, "");
        $this->caj_Ancho = new clsField("caj_Ancho", ccsFloat, "");
        $this->caj_Fondo = new clsField("caj_Fondo", ccsFloat, "");
        $this->caj_PulCentim = new clsField("caj_PulCentim", ccsInteger, "");
        $this->caj_Capacidad = new clsField("caj_Capacidad", ccsFloat, "");
        $this->caj_KilLibras = new clsField("caj_KilLibras", ccsInteger, "");

    }
//End Class_Initialize Event

//Prepare Method @63-96E55A54
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlcaj_CodCaja", ccsInteger, "", "", $this->Parameters["urlcaj_CodCaja"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "caj_CodCaja", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @63-65FA993D
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->SQL = "SELECT *  " .
        "FROM liqcajas";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @63-763BEFE1
    function SetValues()
    {
        $this->caj_CodMarca->SetDBValue(trim($this->f("caj_CodMarca")));
        $this->caj_Abreviatura->SetDBValue($this->f("caj_Abreviatura"));
        $this->caj_Descripcion->SetDBValue($this->f("caj_Descripcion"));
        $this->caj_TipoCaja->SetDBValue($this->f("caj_TipoCaja"));
        $this->caj_Componente1->SetDBValue(trim($this->f("caj_Componente1")));
        $this->caj_Componente2->SetDBValue(trim($this->f("caj_Componente2")));
        $this->caj_Componente3->SetDBValue(trim($this->f("caj_Componente3")));
        $this->caj_Componente4->SetDBValue(trim($this->f("caj_Componente4")));
        $this->caj_Largo->SetDBValue(trim($this->f("caj_Largo")));
        $this->caj_Ancho->SetDBValue(trim($this->f("caj_Ancho")));
        $this->caj_Fondo->SetDBValue(trim($this->f("caj_Fondo")));
        $this->caj_PulCentim->SetDBValue(trim($this->f("caj_PulCentim")));
        $this->caj_Capacidad->SetDBValue(trim($this->f("caj_Capacidad")));
        $this->caj_KilLibras->SetDBValue(trim($this->f("caj_KilLibras")));
    }
//End SetValues Method

//Insert Method @63-6F96A622
    function Insert()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO liqcajas ("
             . "caj_CodMarca, "
             . "caj_Abreviatura, "
             . "caj_Descripcion, "
             . "caj_TipoCaja, "
             . "caj_Componente1, "
             . "caj_Componente2, "
             . "caj_Componente3, "
             . "caj_Componente4, "
             . "caj_Largo, "
             . "caj_Ancho, "
             . "caj_Fondo, "
             . "caj_PulCentim, "
             . "caj_Capacidad, "
             . "caj_KilLibras"
             . ") VALUES ("
             . $this->ToSQL($this->caj_CodMarca->GetDBValue(), $this->caj_CodMarca->DataType) . ", "
             . $this->ToSQL($this->caj_Abreviatura->GetDBValue(), $this->caj_Abreviatura->DataType) . ", "
             . $this->ToSQL($this->caj_Descripcion->GetDBValue(), $this->caj_Descripcion->DataType) . ", "
             . $this->ToSQL($this->caj_TipoCaja->GetDBValue(), $this->caj_TipoCaja->DataType) . ", "
             . $this->ToSQL($this->caj_Componente1->GetDBValue(), $this->caj_Componente1->DataType) . ", "
             . $this->ToSQL($this->caj_Componente2->GetDBValue(), $this->caj_Componente2->DataType) . ", "
             . $this->ToSQL($this->caj_Componente3->GetDBValue(), $this->caj_Componente3->DataType) . ", "
             . $this->ToSQL($this->caj_Componente4->GetDBValue(), $this->caj_Componente4->DataType) . ", "
             . $this->ToSQL($this->caj_Largo->GetDBValue(), $this->caj_Largo->DataType) . ", "
             . $this->ToSQL($this->caj_Ancho->GetDBValue(), $this->caj_Ancho->DataType) . ", "
             . $this->ToSQL($this->caj_Fondo->GetDBValue(), $this->caj_Fondo->DataType) . ", "
             . $this->ToSQL($this->caj_PulCentim->GetDBValue(), $this->caj_PulCentim->DataType) . ", "
             . $this->ToSQL($this->caj_Capacidad->GetDBValue(), $this->caj_Capacidad->DataType) . ", "
             . $this->ToSQL($this->caj_KilLibras->GetDBValue(), $this->caj_KilLibras->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Insert Method

//Update Method @63-BE644452
    function Update()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $this->SQL = "UPDATE liqcajas SET "
             . "caj_CodMarca=" . $this->ToSQL($this->caj_CodMarca->GetDBValue(), $this->caj_CodMarca->DataType) . ", "
             . "caj_Abreviatura=" . $this->ToSQL($this->caj_Abreviatura->GetDBValue(), $this->caj_Abreviatura->DataType) . ", "
             . "caj_Descripcion=" . $this->ToSQL($this->caj_Descripcion->GetDBValue(), $this->caj_Descripcion->DataType) . ", "
             . "caj_TipoCaja=" . $this->ToSQL($this->caj_TipoCaja->GetDBValue(), $this->caj_TipoCaja->DataType) . ", "
             . "caj_Componente1=" . $this->ToSQL($this->caj_Componente1->GetDBValue(), $this->caj_Componente1->DataType) . ", "
             . "caj_Componente2=" . $this->ToSQL($this->caj_Componente2->GetDBValue(), $this->caj_Componente2->DataType) . ", "
             . "caj_Componente3=" . $this->ToSQL($this->caj_Componente3->GetDBValue(), $this->caj_Componente3->DataType) . ", "
             . "caj_Componente4=" . $this->ToSQL($this->caj_Componente4->GetDBValue(), $this->caj_Componente4->DataType) . ", "
             . "caj_Largo=" . $this->ToSQL($this->caj_Largo->GetDBValue(), $this->caj_Largo->DataType) . ", "
             . "caj_Ancho=" . $this->ToSQL($this->caj_Ancho->GetDBValue(), $this->caj_Ancho->DataType) . ", "
             . "caj_Fondo=" . $this->ToSQL($this->caj_Fondo->GetDBValue(), $this->caj_Fondo->DataType) . ", "
             . "caj_PulCentim=" . $this->ToSQL($this->caj_PulCentim->GetDBValue(), $this->caj_PulCentim->DataType) . ", "
             . "caj_Capacidad=" . $this->ToSQL($this->caj_Capacidad->GetDBValue(), $this->caj_Capacidad->DataType) . ", "
             . "caj_KilLibras=" . $this->ToSQL($this->caj_KilLibras->GetDBValue(), $this->caj_KilLibras->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Update Method

//Delete Method @63-3F9BBA4C
    function Delete()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $this->SQL = "DELETE FROM liqcajas";
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Delete Method

} //End cjas_mantDataSource Class @63-FCB6E20C

//Initialize Page @1-2EC1E208
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

$FileName = "LiAdTc_mant.php";
$Redirect = "";
$TemplateFileName = "LiAdTc_mant.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-47EF35FE
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
$cjas_mant = new clsRecordcjas_mant();
$cjas_mant->Initialize();

// Events
include("./LiAdTc_mant_events.php");
BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-F8BD1E31
$Cabecera->Operations();
$cjas_mant->Operation();
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

//Show Page @1-9632DBCC
$Cabecera->Show("Cabecera");
$cjas_mant->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
$generated_with = "<center><font face=\"Arial\"><small>Generated with CodeCharge Studio</small></font></center>";
$main_block = preg_match("/<\/body>/i", $main_block) ? preg_replace("/<\/body>/i", $generated_with . "</body>", $main_block) : $main_block . $generated_with;
echo $main_block;
//End Show Page

//Unload Page @1-6786D1ED
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBdatos->close();
unset($Tpl);
//End Unload Page


?>
