<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files
include (RelativePath . "/LibPhp/SegLib.php");
class clsRecordgenparametros { //genparametros Class @2-70CF5835

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

//Class_Initialize Event @2-DB98E185
    function clsRecordgenparametros()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record genparametros/Error";
        $this->ds = new clsgenparametrosDataSource();
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "genparametros";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->lbTitulo = new clsControl(ccsLabel, "lbTitulo", "lbTitulo", ccsText, "", CCGetRequestParam("lbTitulo", $Method));
            $this->par_Secuencia = new clsControl(ccsTextBox, "par_Secuencia", "CODIGO", ccsInteger, "", CCGetRequestParam("par_Secuencia", $Method));
            $this->par_Descripcion = new clsControl(ccsTextBox, "par_Descripcion", "DESCRIPCION", ccsText, "", CCGetRequestParam("par_Descripcion", $Method));
            $this->par_Descripcion->Required = true;
            $this->par_Valor1 = new clsControl(ccsListBox, "par_Valor1", "TIPO DE MARCA", ccsText, "", CCGetRequestParam("par_Valor1", $Method));
            $this->par_Valor1->DSType = dsTable;
            list($this->par_Valor1->BoundColumn, $this->par_Valor1->TextColumn, $this->par_Valor1->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->par_Valor1->ds = new clsDBdatos();
            $this->par_Valor1->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->par_Valor1->ds->Parameters["expr16"] = 'IGTIMA';
            $this->par_Valor1->ds->wp = new clsSQLParameters();
            $this->par_Valor1->ds->wp->AddParameter("1", "expr16", ccsText, "", "", $this->par_Valor1->ds->Parameters["expr16"], "", false);
            $this->par_Valor1->ds->wp->Criterion[1] = $this->par_Valor1->ds->wp->Operation(opEqual, "par_Clave", $this->par_Valor1->ds->wp->GetDBValue("1"), $this->par_Valor1->ds->ToSQL($this->par_Valor1->ds->wp->GetDBValue("1"), ccsText),false);
            $this->par_Valor1->ds->Where = $this->par_Valor1->ds->wp->Criterion[1];
            $this->par_Valor1->Required = true;
            $this->par_valor2 = new clsControl(ccsTextBox, "par_valor2", "TEXTO ADICIONAL", ccsText, "", CCGetRequestParam("par_valor2", $Method));
            $this->par_Valor3 = new clsControl(ccsListBox, "par_Valor3", "APLICACION", ccsText, "", CCGetRequestParam("par_Valor3", $Method));
            $this->par_Valor3->DSType = dsTable;
            list($this->par_Valor3->BoundColumn, $this->par_Valor3->TextColumn, $this->par_Valor3->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->par_Valor3->ds = new clsDBdatos();
            $this->par_Valor3->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->par_Valor3->ds->Parameters["expr17"] = 'IGUSMA';
            $this->par_Valor3->ds->wp = new clsSQLParameters();
            $this->par_Valor3->ds->wp->AddParameter("1", "expr17", ccsText, "", "", $this->par_Valor3->ds->Parameters["expr17"], "", false);
            $this->par_Valor3->ds->wp->Criterion[1] = $this->par_Valor3->ds->wp->Operation(opEqual, "par_Clave", $this->par_Valor3->ds->wp->GetDBValue("1"), $this->par_Valor3->ds->ToSQL($this->par_Valor3->ds->wp->GetDBValue("1"), ccsText),false);
            $this->par_Valor3->ds->Where = $this->par_Valor3->ds->wp->Criterion[1];
            $this->par_Valor4 = new clsControl(ccsListBox, "par_Valor4", "ESTADO", ccsText, "", CCGetRequestParam("par_Valor4", $Method));
            $this->par_Valor4->DSType = dsTable;
            list($this->par_Valor4->BoundColumn, $this->par_Valor4->TextColumn, $this->par_Valor4->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->par_Valor4->ds = new clsDBdatos();
            $this->par_Valor4->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->par_Valor4->ds->Parameters["expr27"] = LGESTA;
            $this->par_Valor4->ds->wp = new clsSQLParameters();
            $this->par_Valor4->ds->wp->AddParameter("1", "expr27", ccsText, "", "", $this->par_Valor4->ds->Parameters["expr27"], "", false);
            $this->par_Valor4->ds->wp->Criterion[1] = $this->par_Valor4->ds->wp->Operation(opEqual, "par_Clave", $this->par_Valor4->ds->wp->GetDBValue("1"), $this->par_Valor4->ds->ToSQL($this->par_Valor4->ds->wp->GetDBValue("1"), ccsText),false);
            $this->par_Valor4->ds->Where = $this->par_Valor4->ds->wp->Criterion[1];
            $this->par_Valor4->Required = true;
            $this->Button_Insert = new clsButton("Button_Insert");
            $this->Button_Update = new clsButton("Button_Update");
            $this->Button_Delete = new clsButton("Button_Delete");
            $this->Button_Cancel = new clsButton("Button_Cancel");
            if(!$this->FormSubmitted) {
                if(!is_array($this->par_Valor4->Value) && !strlen($this->par_Valor4->Value) && $this->par_Valor4->Value !== false)
                $this->par_Valor4->SetText(1);
            }
            if(!is_array($this->lbTitulo->Value) && !strlen($this->lbTitulo->Value) && $this->lbTitulo->Value !== false)
            $this->lbTitulo->SetText('MARCA NUEVA');
        }
    }
//End Class_Initialize Event

//Initialize Method @2-12B0C3BF
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->ds->Parameters["expr25"] = IMARCA;
        $this->ds->Parameters["urlpar_Secuencia"] = CCGetFromGet("par_Secuencia", "");
    }
//End Initialize Method

//Validate Method @2-205D2C6B
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->par_Secuencia->Validate() && $Validation);
        $Validation = ($this->par_Descripcion->Validate() && $Validation);
        $Validation = ($this->par_Valor1->Validate() && $Validation);
        $Validation = ($this->par_valor2->Validate() && $Validation);
        $Validation = ($this->par_Valor3->Validate() && $Validation);
        $Validation = ($this->par_Valor4->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-701C763E
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->lbTitulo->Errors->Count());
        $errors = ($errors || $this->par_Secuencia->Errors->Count());
        $errors = ($errors || $this->par_Descripcion->Errors->Count());
        $errors = ($errors || $this->par_Valor1->Errors->Count());
        $errors = ($errors || $this->par_valor2->Errors->Count());
        $errors = ($errors || $this->par_Valor3->Errors->Count());
        $errors = ($errors || $this->par_Valor4->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-9D8322F3
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
            }
        }
        $Redirect = "LiAdMa_mant.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "Button_Delete") {
            if(!CCGetEvent($this->Button_Delete->CCSEvents, "OnClick") || !$this->DeleteRow()) {
                $Redirect = "";
            } else {
                $Redirect = "LiAdMa_mant.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm", "par_Secuencia"));
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

//InsertRow Method @2-B1593F4E
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert");
        if(!$this->InsertAllowed) return false;
        $this->ds->par_Secuencia->SetValue($this->par_Secuencia->GetValue());
        $this->ds->par_Descripcion->SetValue($this->par_Descripcion->GetValue());
        $this->ds->par_Valor1->SetValue($this->par_Valor1->GetValue());
        $this->ds->par_valor2->SetValue($this->par_valor2->GetValue());
        $this->ds->par_Valor3->SetValue($this->par_Valor3->GetValue());
        $this->ds->par_Valor4->SetValue($this->par_Valor4->GetValue());
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

//UpdateRow Method @2-C39CE328
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate");
        if(!$this->UpdateAllowed) return false;
        $this->ds->par_Descripcion->SetValue($this->par_Descripcion->GetValue());
        $this->ds->par_Valor1->SetValue($this->par_Valor1->GetValue());
        $this->ds->par_valor2->SetValue($this->par_valor2->GetValue());
        $this->ds->par_Valor3->SetValue($this->par_Valor3->GetValue());
        $this->ds->par_Valor4->SetValue($this->par_Valor4->GetValue());
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

//Show Method @2-77454220
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->par_Valor1->Prepare();
        $this->par_Valor3->Prepare();
        $this->par_Valor4->Prepare();

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
                    echo "Error in Record genparametros";
                }
                else if($this->ds->next_record())
                {
                    $this->ds->SetValues();
                    if(!$this->FormSubmitted)
                    {
                        $this->par_Secuencia->SetValue($this->ds->par_Secuencia->GetValue());
                        $this->par_Descripcion->SetValue($this->ds->par_Descripcion->GetValue());
                        $this->par_Valor1->SetValue($this->ds->par_Valor1->GetValue());
                        $this->par_valor2->SetValue($this->ds->par_valor2->GetValue());
                        $this->par_Valor3->SetValue($this->ds->par_Valor3->GetValue());
                        $this->par_Valor4->SetValue($this->ds->par_Valor4->GetValue());
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
            $Error .= $this->par_Secuencia->Errors->ToString();
            $Error .= $this->par_Descripcion->Errors->ToString();
            $Error .= $this->par_Valor1->Errors->ToString();
            $Error .= $this->par_valor2->Errors->ToString();
            $Error .= $this->par_Valor3->Errors->ToString();
            $Error .= $this->par_Valor4->Errors->ToString();
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

        $this->lbTitulo->Show();
        $this->par_Secuencia->Show();
        $this->par_Descripcion->Show();
        $this->par_Valor1->Show();
        $this->par_valor2->Show();
        $this->par_Valor3->Show();
        $this->par_Valor4->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->Button_Cancel->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

} //End genparametros Class @2-FCB6E20C

class clsgenparametrosDataSource extends clsDBdatos {  //genparametrosDataSource Class @2-F3E4BDC2

//DataSource Variables @2-031DC4B6
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
    var $par_Secuencia;
    var $par_Descripcion;
    var $par_Valor1;
    var $par_valor2;
    var $par_Valor3;
    var $par_Valor4;
//End DataSource Variables

//Class_Initialize Event @2-C262490A
    function clsgenparametrosDataSource()
    {
        $this->ErrorBlock = "Record genparametros/Error";
        $this->Initialize();
        $this->lbTitulo = new clsField("lbTitulo", ccsText, "");
        $this->par_Secuencia = new clsField("par_Secuencia", ccsInteger, "");
        $this->par_Descripcion = new clsField("par_Descripcion", ccsText, "");
        $this->par_Valor1 = new clsField("par_Valor1", ccsText, "");
        $this->par_valor2 = new clsField("par_valor2", ccsText, "");
        $this->par_Valor3 = new clsField("par_Valor3", ccsText, "");
        $this->par_Valor4 = new clsField("par_Valor4", ccsText, "");

    }
//End Class_Initialize Event

//Prepare Method @2-F2F34360
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "expr25", ccsText, "", "", $this->Parameters["expr25"], "", false);
        $this->wp->AddParameter("2", "urlpar_Secuencia", ccsInteger, "", "", $this->Parameters["urlpar_Secuencia"], "", true);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "par_Clave", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opEqual, "par_Secuencia", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsInteger),true);
        $this->Where = $this->wp->opAND(false, $this->wp->Criterion[1], $this->wp->Criterion[2]);
    }
//End Prepare Method

//Open Method @2-8A62BABD
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->SQL = "SELECT *  " .
        "FROM genparametros";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @2-3510AA62
    function SetValues()
    {
        $this->par_Secuencia->SetDBValue(trim($this->f("par_Secuencia")));
        $this->par_Descripcion->SetDBValue($this->f("par_Descripcion"));
        $this->par_Valor1->SetDBValue($this->f("par_Valor1"));
        $this->par_valor2->SetDBValue($this->f("par_valor2"));
        $this->par_Valor3->SetDBValue($this->f("par_Valor3"));
        $this->par_Valor4->SetDBValue($this->f("par_Valor4"));
    }
//End SetValues Method

//Insert Method @2-00F7EC85
    function Insert()
    {
        $this->cp["par_Secuencia"] = new clsSQLParameter("ctrlpar_Secuencia", ccsInteger, "", "", $this->par_Secuencia->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["par_Descripcion"] = new clsSQLParameter("ctrlpar_Descripcion", ccsText, "", "", $this->par_Descripcion->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["par_Valor1"] = new clsSQLParameter("ctrlpar_Valor1", ccsText, "", "", $this->par_Valor1->GetValue(), 1, false, $this->ErrorBlock);
        $this->cp["par_valor2"] = new clsSQLParameter("ctrlpar_valor2", ccsText, "", "", $this->par_valor2->GetValue(), 99, false, $this->ErrorBlock);
        $this->cp["par_Valor3"] = new clsSQLParameter("ctrlpar_Valor3", ccsText, "", "", $this->par_Valor3->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["par_Valor4"] = new clsSQLParameter("ctrlpar_Valor4", ccsInteger, "", "", $this->par_Valor4->GetValue(), 1, false, $this->ErrorBlock);
        $this->cp["par_Categoria"] = new clsSQLParameter("expr24", ccsInteger, "", "", 30, "", false, $this->ErrorBlock);
        $this->cp["par_Clave"] = new clsSQLParameter("expr32", ccsText, "", "", 'IMARCA', "", false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO genparametros ("
             . "par_Secuencia, "
             . "par_Descripcion, "
             . "par_Valor1, "
             . "par_valor2, "
             . "par_Valor3, "
             . "par_Valor4, "
             . "par_Categoria, "
             . "par_Clave"
             . ") VALUES ("
             . $this->ToSQL($this->cp["par_Secuencia"]->GetDBValue(), $this->cp["par_Secuencia"]->DataType) . ", "
             . $this->ToSQL($this->cp["par_Descripcion"]->GetDBValue(), $this->cp["par_Descripcion"]->DataType) . ", "
             . $this->ToSQL($this->cp["par_Valor1"]->GetDBValue(), $this->cp["par_Valor1"]->DataType) . ", "
             . $this->ToSQL($this->cp["par_valor2"]->GetDBValue(), $this->cp["par_valor2"]->DataType) . ", "
             . $this->ToSQL($this->cp["par_Valor3"]->GetDBValue(), $this->cp["par_Valor3"]->DataType) . ", "
             . $this->ToSQL($this->cp["par_Valor4"]->GetDBValue(), $this->cp["par_Valor4"]->DataType) . ", "
             . $this->ToSQL($this->cp["par_Categoria"]->GetDBValue(), $this->cp["par_Categoria"]->DataType) . ", "
             . $this->ToSQL($this->cp["par_Clave"]->GetDBValue(), $this->cp["par_Clave"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Insert Method

//Update Method @2-0540DB29
    function Update()
    {
        $this->cp["par_Descripcion"] = new clsSQLParameter("ctrlpar_Descripcion", ccsText, "", "", $this->par_Descripcion->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["par_Valor1"] = new clsSQLParameter("ctrlpar_Valor1", ccsText, "", "", $this->par_Valor1->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["par_valor2"] = new clsSQLParameter("ctrlpar_valor2", ccsText, "", "", $this->par_valor2->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["par_Valor3"] = new clsSQLParameter("ctrlpar_Valor3", ccsText, "", "", $this->par_Valor3->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["par_Valor4"] = new clsSQLParameter("ctrlpar_Valor4", ccsText, "", "", $this->par_Valor4->GetValue(), "", false, $this->ErrorBlock);
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "expr39", ccsText, "", "", 30, "", true);
        $wp->AddParameter("2", "urlpar_Secuencia", ccsInteger, "", "", CCGetFromGet("par_Secuencia", ""), "", false);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $wp->Criterion[1] = $wp->Operation(opEqual, "par_Categoria", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsText),true);
        $wp->Criterion[2] = $wp->Operation(opEqual, "par_Secuencia", $wp->GetDBValue("2"), $this->ToSQL($wp->GetDBValue("2"), ccsInteger),false);
        $Where = $wp->opAND(false, $wp->Criterion[1], $wp->Criterion[2]);
        $this->SQL = "UPDATE genparametros SET "
             . "par_Descripcion=" . $this->ToSQL($this->cp["par_Descripcion"]->GetDBValue(), $this->cp["par_Descripcion"]->DataType) . ", "
             . "par_Valor1=" . $this->ToSQL($this->cp["par_Valor1"]->GetDBValue(), $this->cp["par_Valor1"]->DataType) . ", "
             . "par_valor2=" . $this->ToSQL($this->cp["par_valor2"]->GetDBValue(), $this->cp["par_valor2"]->DataType) . ", "
             . "par_Valor3=" . $this->ToSQL($this->cp["par_Valor3"]->GetDBValue(), $this->cp["par_Valor3"]->DataType) . ", "
             . "par_Valor4=" . $this->ToSQL($this->cp["par_Valor4"]->GetDBValue(), $this->cp["par_Valor4"]->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Update Method

//Delete Method @2-9E8E9143
    function Delete()
    {
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "expr41", ccsText, "", "", 30, "", false);
        $wp->AddParameter("2", "urlpar_Secuencia", ccsInteger, "", "", CCGetFromGet("par_Secuencia", ""), "", false);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $wp->Criterion[1] = $wp->Operation(opEqual, "par_Categoria", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsText),false);
        $wp->Criterion[2] = $wp->Operation(opEqual, "par_Secuencia", $wp->GetDBValue("2"), $this->ToSQL($wp->GetDBValue("2"), ccsInteger),false);
        $Where = $wp->opAND(false, $wp->Criterion[1], $wp->Criterion[2]);
        $this->SQL = "DELETE FROM genparametros";
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Delete Method

} //End genparametrosDataSource Class @2-FCB6E20C

//Initialize Page @1-31A65D0F
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

$FileName = "LiAdMa_mant.php";
$Redirect = "";
$TemplateFileName = "LiAdMa_mant.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-52F64F8D
$DBdatos = new clsDBdatos();

// Controls
$genparametros = new clsRecordgenparametros();
$genparametros->Initialize();

// Events
include("./LiAdMa_mant_events.php");
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

//Execute Components @1-50A1110E
$genparametros->Operation();
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

//Show Page @1-D870F3AF
$genparametros->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
$generated_with = "<center><font face=\"Arial\"><small>Generated with Smart ERP</small></font></center>";
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
