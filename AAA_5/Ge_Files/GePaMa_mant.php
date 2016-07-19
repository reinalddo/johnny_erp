<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @417-49BEB8FC
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation

Class clsRecordgecapa_mant { //gecapa_mant Class @288-08780939

//Variables @288-4A82E0A3

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

//Class_Initialize Event @288-CA12AD8D
    function clsRecordgecapa_mant()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record gecapa_mant/Error";
        $this->ds = new clsgecapa_mantDataSource();
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "gecapa_mant";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->tbTitulo1 = new clsControl(ccsLabel, "tbTitulo1", "tbTitulo1", ccsText, "", CCGetRequestParam("tbTitulo1", $Method));
            $this->cat_Codigo = new clsControl(ccsTextBox, "cat_Codigo", "Cat Descripcion", ccsText, "", CCGetRequestParam("cat_Codigo", $Method));
            $this->cat_Descripcion = new clsControl(ccsTextBox, "cat_Descripcion", "Cat Descripcion", ccsText, "", CCGetRequestParam("cat_Descripcion", $Method));
            $this->cat_Descripcion->Required = true;
            $this->cat_Tipo = new clsControl(ccsListBox, "cat_Tipo", "Cat Tipo", ccsText, "", CCGetRequestParam("cat_Tipo", $Method));
            $this->cat_Tipo->DSType = dsListOfValues;
            $this->cat_Tipo->Values = array(array("0", "Opcional"), array("1", "Obligatorio"));
            $this->cat_Tipo->Required = true;
            $this->cat_Clave = new clsControl(ccsTextBox, "cat_Clave", "Cat Clave", ccsText, "", CCGetRequestParam("cat_Clave", $Method));
            $this->cat_Clave->Required = true;
            $this->cat_CodPadre = new clsControl(ccsListBox, "cat_CodPadre", "Cat Cod Padre", ccsInteger, "", CCGetRequestParam("cat_CodPadre", $Method));
            $this->cat_CodPadre->DSType = dsTable;
            list($this->cat_CodPadre->BoundColumn, $this->cat_CodPadre->TextColumn, $this->cat_CodPadre->DBFormat) = array("cat_Codigo", "cat_Descripcion", "");
            $this->cat_CodPadre->ds = new clsDBdatos();
            $this->cat_CodPadre->ds->SQL = "SELECT *  " .
"FROM gencatparam";
            $this->Button_Insert = new clsButton("Button_Insert");
            $this->Button_Update = new clsButton("Button_Update");
            $this->Button_Delete = new clsButton("Button_Delete");
            $this->Button_Cancel = new clsButton("Button_Cancel");
            $this->lkNuevo = new clsControl(ccsLink, "lkNuevo", "lkNuevo", ccsText, "", CCGetRequestParam("lkNuevo", $Method));
            $this->Link1 = new clsControl(ccsLink, "Link1", "Link1", ccsText, "", CCGetRequestParam("Link1", $Method));
            if(!$this->FormSubmitted) {
                if(!is_array($this->cat_Tipo->Value) && !strlen($this->cat_Tipo->Value) && $this->cat_Tipo->Value !== false)
                $this->cat_Tipo->SetText(0);
                if(!is_array($this->cat_CodPadre->Value) && !strlen($this->cat_CodPadre->Value) && $this->cat_CodPadre->Value !== false)
                $this->cat_CodPadre->SetText(0);
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @288-0F145AFC
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->ds->Parameters["urlcat_Codigo"] = CCGetFromGet("cat_Codigo", "");
    }
//End Initialize Method

//Validate Method @288-5EB3953F
    function Validate()
    {
        $Validation = true;
        $Where = "";
        if($this->EditMode && strlen($this->ds->Where))
            $Where = " AND NOT (" . $this->ds->Where . ")";
        global $DBdatos;
        $this->ds->cat_Codigo->SetValue($this->cat_Codigo->GetValue());
        if(CCDLookUp("COUNT(*)", "gencatparam", "cat_Codigo=" . $this->ds->ToSQL($this->ds->cat_Codigo->GetDBValue(), $this->ds->cat_Codigo->DataType) . $Where, $DBdatos) > 0)
            $this->cat_Codigo->Errors->addError("El campo Cat Descripcion ya existe.");
        global $DBdatos;
        $this->ds->cat_Clave->SetValue($this->cat_Clave->GetValue());
        if(CCDLookUp("COUNT(*)", "gencatparam", "cat_Clave=" . $this->ds->ToSQL($this->ds->cat_Clave->GetDBValue(), $this->ds->cat_Clave->DataType) . $Where, $DBdatos) > 0)
            $this->cat_Clave->Errors->addError("El campo Cat Clave ya existe.");
        $Validation = ($this->cat_Codigo->Validate() && $Validation);
        $Validation = ($this->cat_Descripcion->Validate() && $Validation);
        $Validation = ($this->cat_Tipo->Validate() && $Validation);
        $Validation = ($this->cat_Clave->Validate() && $Validation);
        $Validation = ($this->cat_CodPadre->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @288-8395CB34
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->tbTitulo1->Errors->Count());
        $errors = ($errors || $this->cat_Codigo->Errors->Count());
        $errors = ($errors || $this->cat_Descripcion->Errors->Count());
        $errors = ($errors || $this->cat_Tipo->Errors->Count());
        $errors = ($errors || $this->cat_Clave->Errors->Count());
        $errors = ($errors || $this->cat_CodPadre->Errors->Count());
        $errors = ($errors || $this->lkNuevo->Errors->Count());
        $errors = ($errors || $this->Link1->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @288-B7AC9E38
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
        $Redirect = "GePaMa_mant.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
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

//InsertRow Method @288-64AE1E07
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert");
        if(!$this->InsertAllowed) return false;
        $this->ds->cat_Codigo->SetValue($this->cat_Codigo->GetValue());
        $this->ds->cat_Descripcion->SetValue($this->cat_Descripcion->GetValue());
        $this->ds->cat_Tipo->SetValue($this->cat_Tipo->GetValue());
        $this->ds->cat_Clave->SetValue($this->cat_Clave->GetValue());
        $this->ds->cat_CodPadre->SetValue($this->cat_CodPadre->GetValue());
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

//UpdateRow Method @288-2993CB26
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate");
        if(!$this->UpdateAllowed) return false;
        $this->ds->cat_Descripcion->SetValue($this->cat_Descripcion->GetValue());
        $this->ds->cat_Tipo->SetValue($this->cat_Tipo->GetValue());
        $this->ds->cat_Clave->SetValue($this->cat_Clave->GetValue());
        $this->ds->cat_CodPadre->SetValue($this->cat_CodPadre->GetValue());
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

//DeleteRow Method @288-EA88835F
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

//Show Method @288-D27D8EC8
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->cat_Tipo->Prepare();
        $this->cat_CodPadre->Prepare();

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
                    echo "Error in Record gecapa_mant";
                }
                else if($this->ds->next_record())
                {
                    $this->ds->SetValues();
                    if(!$this->FormSubmitted)
                    {
                        $this->cat_Codigo->SetValue($this->ds->cat_Codigo->GetValue());
                        $this->cat_Descripcion->SetValue($this->ds->cat_Descripcion->GetValue());
                        $this->cat_Tipo->SetValue($this->ds->cat_Tipo->GetValue());
                        $this->cat_Clave->SetValue($this->ds->cat_Clave->GetValue());
                        $this->cat_CodPadre->SetValue($this->ds->cat_CodPadre->GetValue());
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
            $this->lkNuevo->SetText("NUEVA...");
            $this->lkNuevo->Parameters = CCGetQueryString("QueryString", Array("cat_Codigo", "ccsForm"));
            $this->lkNuevo->Parameters = CCAddParam($this->lkNuevo->Parameters, "pPadre", $this->ds->f("cat_Codigo"));
            $this->lkNuevo->Page = "";
            $this->Link1->SetText("Parámetros..");
            $this->Link1->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
            $this->Link1->Parameters = CCAddParam($this->Link1->Parameters, "pParCate", $this->ds->f("cat_Codigo"));
            $this->Link1->Page = "GePaMa_mant.php";
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->tbTitulo1->Errors->ToString();
            $Error .= $this->cat_Codigo->Errors->ToString();
            $Error .= $this->cat_Descripcion->Errors->ToString();
            $Error .= $this->cat_Tipo->Errors->ToString();
            $Error .= $this->cat_Clave->Errors->ToString();
            $Error .= $this->cat_CodPadre->Errors->ToString();
            $Error .= $this->lkNuevo->Errors->ToString();
            $Error .= $this->Link1->Errors->ToString();
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
        $this->tbTitulo1->Show();
        $this->cat_Codigo->Show();
        $this->cat_Descripcion->Show();
        $this->cat_Tipo->Show();
        $this->cat_Clave->Show();
        $this->cat_CodPadre->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->Button_Cancel->Show();
        $this->lkNuevo->Show();
        $this->Link1->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

} //End gecapa_mant Class @288-FCB6E20C

class clsgecapa_mantDataSource extends clsDBDatos {  //gecapa_mantDataSource Class @288-DE17A9D5

//DataSource Variables @288-D9557B42
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $InsertParameters;
    var $UpdateParameters;
    var $DeleteParameters;
    var $wp;
    var $AllParametersSet;


    // Datasource fields
    var $tbTitulo1;
    var $cat_Codigo;
    var $cat_Descripcion;
    var $cat_Tipo;
    var $cat_Clave;
    var $cat_CodPadre;
    var $lkNuevo;
    var $Link1;
//End DataSource Variables

//Class_Initialize Event @288-D4BF72EA
    function clsgecapa_mantDataSource()
    {
        $this->ErrorBlock = "Record gecapa_mant/Error";
        $this->Initialize();
        $this->tbTitulo1 = new clsField("tbTitulo1", ccsText, "");
        $this->cat_Codigo = new clsField("cat_Codigo", ccsText, "");
        $this->cat_Descripcion = new clsField("cat_Descripcion", ccsText, "");
        $this->cat_Tipo = new clsField("cat_Tipo", ccsText, "");
        $this->cat_Clave = new clsField("cat_Clave", ccsText, "");
        $this->cat_CodPadre = new clsField("cat_CodPadre", ccsInteger, "");
        $this->lkNuevo = new clsField("lkNuevo", ccsText, "");
        $this->Link1 = new clsField("Link1", ccsText, "");

    }
//End Class_Initialize Event

//Prepare Method @288-BFF608D6
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlcat_Codigo", ccsInteger, "", "", $this->Parameters["urlcat_Codigo"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "cat_Codigo", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @288-4D1EE1CA
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->SQL = "SELECT *  " .
        "FROM gencatparam";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @288-D5DC73A8
    function SetValues()
    {
        $this->cat_Codigo->SetDBValue($this->f("cat_Codigo"));
        $this->cat_Descripcion->SetDBValue($this->f("cat_Descripcion"));
        $this->cat_Tipo->SetDBValue($this->f("cat_Tipo"));
        $this->cat_Clave->SetDBValue($this->f("cat_Clave"));
        $this->cat_CodPadre->SetDBValue(trim($this->f("cat_CodPadre")));
    }
//End SetValues Method

//Insert Method @288-001D768D
    function Insert()
    {
        $this->cp["cat_Codigo"] = new clsSQLParameter("ctrlcat_Codigo", ccsText, "", "", $this->cat_Codigo->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["cat_Descripcion"] = new clsSQLParameter("ctrlcat_Descripcion", ccsText, "", "", $this->cat_Descripcion->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["cat_Tipo"] = new clsSQLParameter("ctrlcat_Tipo", ccsText, "", "", $this->cat_Tipo->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["cat_Clave"] = new clsSQLParameter("ctrlcat_Clave", ccsText, "", "", $this->cat_Clave->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["cat_CodPadre"] = new clsSQLParameter("ctrlcat_CodPadre", ccsInteger, "", "", $this->cat_CodPadre->GetValue(), "", false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO gencatparam ("
             . "cat_Codigo, "
             . "cat_Descripcion, "
             . "cat_Tipo, "
             . "cat_Clave, "
             . "cat_CodPadre"
             . ") VALUES ("
             . $this->ToSQL($this->cp["cat_Codigo"]->GetDBValue(), $this->cp["cat_Codigo"]->DataType) . ", "
             . $this->ToSQL($this->cp["cat_Descripcion"]->GetDBValue(), $this->cp["cat_Descripcion"]->DataType) . ", "
             . $this->ToSQL($this->cp["cat_Tipo"]->GetDBValue(), $this->cp["cat_Tipo"]->DataType) . ", "
             . $this->ToSQL($this->cp["cat_Clave"]->GetDBValue(), $this->cp["cat_Clave"]->DataType) . ", "
             . $this->ToSQL($this->cp["cat_CodPadre"]->GetDBValue(), $this->cp["cat_CodPadre"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Insert Method

//Update Method @288-4D49AD97
    function Update()
    {
        $this->cp["cat_Codigo"] = new clsSQLParameter("urlcat_Codigo", ccsText, "", "", CCGetFromGet("cat_Codigo", ""), "", false, $this->ErrorBlock);
        $this->cp["cat_Descripcion"] = new clsSQLParameter("ctrlcat_Descripcion", ccsText, "", "", $this->cat_Descripcion->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["cat_Tipo"] = new clsSQLParameter("ctrlcat_Tipo", ccsText, "", "", $this->cat_Tipo->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["cat_Clave"] = new clsSQLParameter("ctrlcat_Clave", ccsText, "", "", $this->cat_Clave->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["cat_CodPadre"] = new clsSQLParameter("ctrlcat_CodPadre", ccsInteger, "", "", $this->cat_CodPadre->GetValue(), "", false, $this->ErrorBlock);
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlcat_Codigo", ccsInteger, "", "", CCGetFromGet("cat_Codigo", ""), "", false);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $wp->Criterion[1] = $wp->Operation(opEqual, "cat_Codigo", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),false);
        $Where = $wp->Criterion[1];
        $this->SQL = "UPDATE gencatparam SET "
             . "cat_Codigo=" . $this->ToSQL($this->cp["cat_Codigo"]->GetDBValue(), $this->cp["cat_Codigo"]->DataType) . ", "
             . "cat_Descripcion=" . $this->ToSQL($this->cp["cat_Descripcion"]->GetDBValue(), $this->cp["cat_Descripcion"]->DataType) . ", "
             . "cat_Tipo=" . $this->ToSQL($this->cp["cat_Tipo"]->GetDBValue(), $this->cp["cat_Tipo"]->DataType) . ", "
             . "cat_Clave=" . $this->ToSQL($this->cp["cat_Clave"]->GetDBValue(), $this->cp["cat_Clave"]->DataType) . ", "
             . "cat_CodPadre=" . $this->ToSQL($this->cp["cat_CodPadre"]->GetDBValue(), $this->cp["cat_CodPadre"]->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Update Method

//Delete Method @288-599B50DA
    function Delete()
    {
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlcat_Codigo", ccsInteger, "", "", CCGetFromGet("cat_Codigo", ""), "", false);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $wp->Criterion[1] = $wp->Operation(opEqual, "cat_Codigo", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),false);
        $Where = $wp->Criterion[1];
        $this->SQL = "DELETE FROM gencatparam";
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Delete Method

} //End gecapa_mantDataSource Class @288-FCB6E20C

Class clsEditableGridgecapa_deta { //gecapa_deta Class @374-458F9913

//Variables @374-5C00E59B

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
    var $Sorter_par_Clave;
    var $Sorter_par_Secuencia;
    var $Sorter_par_Descripcion;
    var $Sorter_par_Valor1;
    var $Sorter_par_valor2;
    var $Sorter_par_Valor3;
    var $Sorter_par_Valor4;
    var $Navigator1;
//End Variables

//Class_Initialize Event @374-8AE26390
    function clsEditableGridgecapa_deta()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "EditableGrid gecapa_deta/Error";
        $this->ComponentName = "gecapa_deta";
        $this->CachedColumns["par_Categoria"][0] = "par_Categoria";
        $this->CachedColumns["par_Clave"][0] = "par_Clave";
        $this->CachedColumns["par_Secuencia"][0] = "par_Secuencia";
        $this->ds = new clsgecapa_detaDataSource();

        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize) || $this->PageSize > 10)
            $this->PageSize = 10;
        else
            $this->PageSize = intval($this->PageSize);
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: EditableGrid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));

        $this->EmptyRows = 3;
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

        $this->SorterName = CCGetParam("gecapa_detaOrder", "");
        $this->SorterDirection = CCGetParam("gecapa_detaDir", "");

        $this->Sorter_par_Clave = new clsSorter($this->ComponentName, "Sorter_par_Clave", $FileName);
        $this->Sorter_par_Secuencia = new clsSorter($this->ComponentName, "Sorter_par_Secuencia", $FileName);
        $this->Sorter_par_Descripcion = new clsSorter($this->ComponentName, "Sorter_par_Descripcion", $FileName);
        $this->Sorter_par_Valor1 = new clsSorter($this->ComponentName, "Sorter_par_Valor1", $FileName);
        $this->Sorter_par_valor2 = new clsSorter($this->ComponentName, "Sorter_par_valor2", $FileName);
        $this->Sorter_par_Valor3 = new clsSorter($this->ComponentName, "Sorter_par_Valor3", $FileName);
        $this->Sorter_par_Valor4 = new clsSorter($this->ComponentName, "Sorter_par_Valor4", $FileName);
        $this->par_Categorias = new clsControl(ccsHidden, "par_Categorias", "Par Categoria", ccsInteger, "");
        $this->par_Clave = new clsControl(ccsTextBox, "par_Clave", "Par Clave", ccsText, "");
        $this->par_Clave->Required = true;
        $this->par_Secuencia = new clsControl(ccsTextBox, "par_Secuencia", "Par Secuencia", ccsInteger, "");
        $this->par_Secuencia->Required = true;
        $this->par_Descripcion = new clsControl(ccsTextBox, "par_Descripcion", "Par Descripcion", ccsText, "");
        $this->par_Descripcion->Required = true;
        $this->par_Valor1 = new clsControl(ccsTextBox, "par_Valor1", "Par Valor1", ccsText, "");
        $this->par_valor2 = new clsControl(ccsTextBox, "par_valor2", "Par Valor2", ccsText, "");
        $this->par_Valor3 = new clsControl(ccsTextBox, "par_Valor3", "Par Valor3", ccsText, "");
        $this->par_Valor4 = new clsControl(ccsTextBox, "par_Valor4", "Par Valor4", ccsText, "");
        $this->CheckBox_Delete = new clsControl(ccsCheckBox, "CheckBox_Delete", "CheckBox_Delete", ccsBoolean, Array("Y", "N", ""));
        $this->CheckBox_Delete->CheckedValue = $this->CheckBox_Delete->GetParsedValue(true);
        $this->CheckBox_Delete->UncheckedValue = $this->CheckBox_Delete->GetParsedValue(false);
        $this->Navigator1 = new clsNavigator($this->ComponentName, "Navigator1", $FileName, 10, tpSimple);
        $this->Button_Submit = new clsButton("Button_Submit");
        $this->Cancel = new clsButton("Cancel");
    }
//End Class_Initialize Event

//Initialize Method @374-633239F2
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);

        $this->ds->Parameters["urlcat_Codigo"] = CCGetFromGet("cat_Codigo", "");
    }
//End Initialize Method

//GetFormParameters Method @374-2FEE3BD2
    function GetFormParameters()
    {
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->FormParameters["par_Categorias"][$RowNumber] = CCGetFromPost("par_Categorias_" . $RowNumber);
            $this->FormParameters["par_Clave"][$RowNumber] = CCGetFromPost("par_Clave_" . $RowNumber);
            $this->FormParameters["par_Secuencia"][$RowNumber] = CCGetFromPost("par_Secuencia_" . $RowNumber);
            $this->FormParameters["par_Descripcion"][$RowNumber] = CCGetFromPost("par_Descripcion_" . $RowNumber);
            $this->FormParameters["par_Valor1"][$RowNumber] = CCGetFromPost("par_Valor1_" . $RowNumber);
            $this->FormParameters["par_valor2"][$RowNumber] = CCGetFromPost("par_valor2_" . $RowNumber);
            $this->FormParameters["par_Valor3"][$RowNumber] = CCGetFromPost("par_Valor3_" . $RowNumber);
            $this->FormParameters["par_Valor4"][$RowNumber] = CCGetFromPost("par_Valor4_" . $RowNumber);
            $this->FormParameters["CheckBox_Delete"][$RowNumber] = CCGetFromPost("CheckBox_Delete_" . $RowNumber);
        }
    }
//End GetFormParameters Method

//Validate Method @374-D9574EF7
    function Validate()
    {
        $Validation = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");

        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["par_Categoria"] = $this->CachedColumns["par_Categoria"][$RowNumber];
            $this->ds->CachedColumns["par_Clave"] = $this->CachedColumns["par_Clave"][$RowNumber];
            $this->ds->CachedColumns["par_Secuencia"] = $this->CachedColumns["par_Secuencia"][$RowNumber];
            $this->par_Categorias->SetText($this->FormParameters["par_Categorias"][$RowNumber], $RowNumber);
            $this->par_Clave->SetText($this->FormParameters["par_Clave"][$RowNumber], $RowNumber);
            $this->par_Secuencia->SetText($this->FormParameters["par_Secuencia"][$RowNumber], $RowNumber);
            $this->par_Descripcion->SetText($this->FormParameters["par_Descripcion"][$RowNumber], $RowNumber);
            $this->par_Valor1->SetText($this->FormParameters["par_Valor1"][$RowNumber], $RowNumber);
            $this->par_valor2->SetText($this->FormParameters["par_valor2"][$RowNumber], $RowNumber);
            $this->par_Valor3->SetText($this->FormParameters["par_Valor3"][$RowNumber], $RowNumber);
            $this->par_Valor4->SetText($this->FormParameters["par_Valor4"][$RowNumber], $RowNumber);
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

//ValidateRow Method @374-1C518AD1
    function ValidateRow($RowNumber)
    {
        $Validation = true;
        $Validation = ($this->par_Categorias->Validate() && $Validation);
        $Validation = ($this->par_Clave->Validate() && $Validation);
        $Validation = ($this->par_Secuencia->Validate() && $Validation);
        $Validation = ($this->par_Descripcion->Validate() && $Validation);
        $Validation = ($this->par_Valor1->Validate() && $Validation);
        $Validation = ($this->par_valor2->Validate() && $Validation);
        $Validation = ($this->par_Valor3->Validate() && $Validation);
        $Validation = ($this->par_Valor4->Validate() && $Validation);
        $Validation = ($this->CheckBox_Delete->Validate() && $Validation);
        $errors = "";
        if(!$Validation)
        {
            $errors .= $this->par_Categorias->Errors->ToString();
            $errors .= $this->par_Clave->Errors->ToString();
            $errors .= $this->par_Secuencia->Errors->ToString();
            $errors .= $this->par_Descripcion->Errors->ToString();
            $errors .= $this->par_Valor1->Errors->ToString();
            $errors .= $this->par_valor2->Errors->ToString();
            $errors .= $this->par_Valor3->Errors->ToString();
            $errors .= $this->par_Valor4->Errors->ToString();
            $errors .= $this->CheckBox_Delete->Errors->ToString();
            $this->par_Categorias->Errors->Clear();
            $this->par_Clave->Errors->Clear();
            $this->par_Secuencia->Errors->Clear();
            $this->par_Descripcion->Errors->Clear();
            $this->par_Valor1->Errors->Clear();
            $this->par_valor2->Errors->Clear();
            $this->par_Valor3->Errors->Clear();
            $this->par_Valor4->Errors->Clear();
            $this->CheckBox_Delete->Errors->Clear();
        }
        $this->RowsErrors[$RowNumber] = $errors;
        return $Validation;
    }
//End ValidateRow Method

//CheckInsert Method @374-F71401CB
    function CheckInsert($RowNumber)
    {
        $filed = false;
        $filed = ($filed || strlen($this->FormParameters["par_Categorias"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["par_Clave"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["par_Secuencia"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["par_Descripcion"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["par_Valor1"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["par_valor2"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["par_Valor3"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["par_Valor4"][$RowNumber]));
        return $filed;
    }
//End CheckInsert Method

//CheckErrors Method @374-242E5992
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @374-7B861278
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
        } else if(strlen(CCGetParam("Cancel", ""))) {
            $this->PressedButton = "Cancel";
        }

        $Redirect = $FileName . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "Button_Submit") {
            if(!CCGetEvent($this->Button_Submit->CCSEvents, "OnClick") || !$this->UpdateGrid()) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "Cancel") {
            if(!CCGetEvent($this->Cancel->CCSEvents, "OnClick")) {
                $Redirect = "";
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//UpdateGrid Method @374-DC3F3256
    function UpdateGrid()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSubmit");
        if(!$this->Validate()) return;
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["par_Categoria"] = $this->CachedColumns["par_Categoria"][$RowNumber];
            $this->ds->CachedColumns["par_Clave"] = $this->CachedColumns["par_Clave"][$RowNumber];
            $this->ds->CachedColumns["par_Secuencia"] = $this->CachedColumns["par_Secuencia"][$RowNumber];
            $this->par_Categorias->SetText($this->FormParameters["par_Categorias"][$RowNumber], $RowNumber);
            $this->par_Clave->SetText($this->FormParameters["par_Clave"][$RowNumber], $RowNumber);
            $this->par_Secuencia->SetText($this->FormParameters["par_Secuencia"][$RowNumber], $RowNumber);
            $this->par_Descripcion->SetText($this->FormParameters["par_Descripcion"][$RowNumber], $RowNumber);
            $this->par_Valor1->SetText($this->FormParameters["par_Valor1"][$RowNumber], $RowNumber);
            $this->par_valor2->SetText($this->FormParameters["par_valor2"][$RowNumber], $RowNumber);
            $this->par_Valor3->SetText($this->FormParameters["par_Valor3"][$RowNumber], $RowNumber);
            $this->par_Valor4->SetText($this->FormParameters["par_Valor4"][$RowNumber], $RowNumber);
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

//InsertRow Method @374-F0DA84D2
    function InsertRow($RowNumber)
    {
        if(!$this->InsertAllowed) return false;
        $this->ds->par_Clave->SetValue($this->par_Clave->GetValue());
        $this->ds->par_Secuencia->SetValue($this->par_Secuencia->GetValue());
        $this->ds->par_Descripcion->SetValue($this->par_Descripcion->GetValue());
        $this->ds->par_Valor1->SetValue($this->par_Valor1->GetValue());
        $this->ds->par_valor2->SetValue($this->par_valor2->GetValue());
        $this->ds->par_Valor3->SetValue($this->par_Valor3->GetValue());
        $this->ds->par_Valor4->SetValue($this->par_Valor4->GetValue());
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

//UpdateRow Method @374-D2A81EEC
    function UpdateRow($RowNumber)
    {
        if(!$this->UpdateAllowed) return false;
        $this->ds->par_Clave->SetValue($this->par_Clave->GetValue());
        $this->ds->par_Secuencia->SetValue($this->par_Secuencia->GetValue());
        $this->ds->par_Descripcion->SetValue($this->par_Descripcion->GetValue());
        $this->ds->par_Valor1->SetValue($this->par_Valor1->GetValue());
        $this->ds->par_valor2->SetValue($this->par_valor2->GetValue());
        $this->ds->par_Valor3->SetValue($this->par_Valor3->GetValue());
        $this->ds->par_Valor4->SetValue($this->par_Valor4->GetValue());
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

//DeleteRow Method @374-0C9DDC34
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

//FormScript Method @374-59800DB5
    function FormScript($TotalRows)
    {
        $script = "";
        return $script;
    }
//End FormScript Method

//SetFormState Method @374-D71A2B3F
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
                $this->CachedColumns["par_Categoria"][$RowNumber] = $piece;
                $piece = $pieces[$i + 1];
                $piece = str_replace("\\" . ord("\\"), "\\", $piece);
                $piece = str_replace("\\" . ord(";"), ";", $piece);
                $this->CachedColumns["par_Clave"][$RowNumber] = $piece;
                $piece = $pieces[$i + 2];
                $piece = str_replace("\\" . ord("\\"), "\\", $piece);
                $piece = str_replace("\\" . ord(";"), ";", $piece);
                $this->CachedColumns["par_Secuencia"][$RowNumber] = $piece;
                $RowNumber++;
            }

            if(!$RowNumber) { $RowNumber = 1; }
            for($i = 1; $i <= $this->EmptyRows; $i++) {
                $this->CachedColumns["par_Categoria"][$RowNumber] = "";
                $this->CachedColumns["par_Clave"][$RowNumber] = "";
                $this->CachedColumns["par_Secuencia"][$RowNumber] = "";
                $RowNumber++;
            }
        }
    }
//End SetFormState Method

//GetFormState Method @374-1EF27AB4
    function GetFormState($NonEmptyRows)
    {
        if(!$this->FormSubmitted) {
            $this->FormState  = $NonEmptyRows . ";";
            $this->FormState .= $this->InsertAllowed ? $this->EmptyRows : "0";
            if($NonEmptyRows) {
                for($i = 0; $i <= $NonEmptyRows; $i++) {
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["par_Categoria"][$i]));
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["par_Clave"][$i]));
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["par_Secuencia"][$i]));
                }
            }
        }
        return $this->FormState;
    }
//End GetFormState Method

//Show Method @374-302CDC43
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible) { return; }

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");


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
//        $EmptyRowsLeft = $this->EmptyRows;
        //        $EmptyRowsLeft = $this->EmptyRows;
        // fah_mod: para hacer que se agregue records solo al final
            if ($this->ds->AbsolutePage < $this->ds->PageCount()) $EmptyRowsLeft = 0;
            else {
                if ($this->ds->RecordsCount > $this->PageSize) $resto = fmod($this->ds->RecordsCount, $this->PageSize);
                else $resto = $this->ds->RecordsCount;
                $EmptyRowsLeft = $this->PageSize - $resto;
            }
        if ($EmptyRowsLeft <= 0 && $this->ds->RecordsCount == ($this->PageSize * $this->ds->AbsolutePage)) $EmptyRowsLeft =1;
        // end fah mod
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
                        $this->CachedColumns["par_Categoria"][$RowNumber] = $this->ds->CachedColumns["par_Categoria"];
                        $this->CachedColumns["par_Clave"][$RowNumber] = $this->ds->CachedColumns["par_Clave"];
                        $this->CachedColumns["par_Secuencia"][$RowNumber] = $this->ds->CachedColumns["par_Secuencia"];
                        $this->par_Clave->SetValue($this->ds->par_Clave->GetValue());
                        $this->par_Secuencia->SetValue($this->ds->par_Secuencia->GetValue());
                        $this->par_Descripcion->SetValue($this->ds->par_Descripcion->GetValue());
                        $this->par_Valor1->SetValue($this->ds->par_Valor1->GetValue());
                        $this->par_valor2->SetValue($this->ds->par_valor2->GetValue());
                        $this->par_Valor3->SetValue($this->ds->par_Valor3->GetValue());
                        $this->par_Valor4->SetValue($this->ds->par_Valor4->GetValue());
                        $this->ValidateRow($RowNumber);
                    } else if (!$this->FormSubmitted){
                        $this->CachedColumns["par_Categoria"][$RowNumber] = "";
                        $this->CachedColumns["par_Clave"][$RowNumber] = "";
                        $this->CachedColumns["par_Secuencia"][$RowNumber] = "";
                        $this->par_Categorias->SetText(CCGetFromGet("cat_Codigo", ""));
                        $this->par_Clave->SetText("");
                        $this->par_Secuencia->SetText("");
                        $this->par_Descripcion->SetText("");
                        $this->par_Valor1->SetText("");
                        $this->par_valor2->SetText("");
                        $this->par_Valor3->SetText("");
                        $this->par_Valor4->SetText("");
                        $this->CheckBox_Delete->SetText("");
                    } else {
                        $this->par_Categorias->SetText($this->FormParameters["par_Categorias"][$RowNumber], $RowNumber);
                        $this->par_Clave->SetText($this->FormParameters["par_Clave"][$RowNumber], $RowNumber);
                        $this->par_Secuencia->SetText($this->FormParameters["par_Secuencia"][$RowNumber], $RowNumber);
                        $this->par_Descripcion->SetText($this->FormParameters["par_Descripcion"][$RowNumber], $RowNumber);
                        $this->par_Valor1->SetText($this->FormParameters["par_Valor1"][$RowNumber], $RowNumber);
                        $this->par_valor2->SetText($this->FormParameters["par_valor2"][$RowNumber], $RowNumber);
                        $this->par_Valor3->SetText($this->FormParameters["par_Valor3"][$RowNumber], $RowNumber);
                        $this->par_Valor4->SetText($this->FormParameters["par_Valor4"][$RowNumber], $RowNumber);
                        $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$RowNumber], $RowNumber);
                    }
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->par_Categorias->Show($RowNumber);
                    $this->par_Clave->Show($RowNumber);
                    $this->par_Secuencia->Show($RowNumber);
                    $this->par_Descripcion->Show($RowNumber);
                    $this->par_Valor1->Show($RowNumber);
                    $this->par_valor2->Show($RowNumber);
                    $this->par_Valor3->Show($RowNumber);
                    $this->par_Valor4->Show($RowNumber);
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
        $this->Navigator1->PageNumber = $this->ds->AbsolutePage;
        $this->Navigator1->TotalPages = $this->ds->PageCount();
        $this->Sorter_par_Clave->Show();
        $this->Sorter_par_Secuencia->Show();
        $this->Sorter_par_Descripcion->Show();
        $this->Sorter_par_Valor1->Show();
        $this->Sorter_par_valor2->Show();
        $this->Sorter_par_Valor3->Show();
        $this->Sorter_par_Valor4->Show();
        $this->Navigator1->Show();
        $this->Button_Submit->Show();
        $this->Cancel->Show();

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

} //End gecapa_deta Class @374-FCB6E20C

class clsgecapa_detaDataSource extends clsDBdatos {  //gecapa_detaDataSource Class @374-75BCE98C

//DataSource Variables @374-28D29272
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
    var $par_Categorias;
    var $par_Clave;
    var $par_Secuencia;
    var $par_Descripcion;
    var $par_Valor1;
    var $par_valor2;
    var $par_Valor3;
    var $par_Valor4;
    var $CheckBox_Delete;
//End DataSource Variables

//Class_Initialize Event @374-29663D9F
    function clsgecapa_detaDataSource()
    {
        $this->ErrorBlock = "EditableGrid gecapa_deta/Error";
        $this->Initialize();
        $this->par_Categorias = new clsField("par_Categorias", ccsInteger, "");
        $this->par_Clave = new clsField("par_Clave", ccsText, "");
        $this->par_Secuencia = new clsField("par_Secuencia", ccsInteger, "");
        $this->par_Descripcion = new clsField("par_Descripcion", ccsText, "");
        $this->par_Valor1 = new clsField("par_Valor1", ccsText, "");
        $this->par_valor2 = new clsField("par_valor2", ccsText, "");
        $this->par_Valor3 = new clsField("par_Valor3", ccsText, "");
        $this->par_Valor4 = new clsField("par_Valor4", ccsText, "");
        $this->CheckBox_Delete = new clsField("CheckBox_Delete", ccsBoolean, Array("true", "false", ""));

    }
//End Class_Initialize Event

//SetOrder Method @374-5DA9EE5B
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_par_Clave" => array("par_Clave", ""), 
            "Sorter_par_Secuencia" => array("par_Secuencia", ""), 
            "Sorter_par_Descripcion" => array("par_Descripcion", ""), 
            "Sorter_par_Valor1" => array("par_Valor1", ""), 
            "Sorter_par_valor2" => array("par_valor2", ""), 
            "Sorter_par_Valor3" => array("par_Valor3", ""), 
            "Sorter_par_Valor4" => array("par_Valor4", "")));
    }
//End SetOrder Method

//Prepare Method @374-B6ADC743
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlcat_Codigo", ccsInteger, "", "", $this->Parameters["urlcat_Codigo"], -1, false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "par_Categoria", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @374-DB75A4F3
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM genparametros";
        $this->SQL = "SELECT *  " .
        "FROM genparametros";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @374-6EF92849
    function SetValues()
    {
        $this->CachedColumns["par_Categoria"] = $this->f("par_Categoria");
        $this->CachedColumns["par_Clave"] = $this->f("par_Clave");
        $this->CachedColumns["par_Secuencia"] = $this->f("par_Secuencia");
        $this->par_Clave->SetDBValue($this->f("par_Clave"));
        $this->par_Secuencia->SetDBValue(trim($this->f("par_Secuencia")));
        $this->par_Descripcion->SetDBValue($this->f("par_Descripcion"));
        $this->par_Valor1->SetDBValue($this->f("par_Valor1"));
        $this->par_valor2->SetDBValue($this->f("par_valor2"));
        $this->par_Valor3->SetDBValue($this->f("par_Valor3"));
        $this->par_Valor4->SetDBValue($this->f("par_Valor4"));
    }
//End SetValues Method

//Insert Method @374-7E9E59F6
    function Insert()
    {
        $this->cp["par_Clave"] = new clsSQLParameter("ctrlpar_Clave", ccsText, "", "", $this->par_Clave->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["par_Secuencia"] = new clsSQLParameter("ctrlpar_Secuencia", ccsInteger, "", "", $this->par_Secuencia->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["par_Descripcion"] = new clsSQLParameter("ctrlpar_Descripcion", ccsText, "", "", $this->par_Descripcion->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["par_Valor1"] = new clsSQLParameter("ctrlpar_Valor1", ccsText, "", "", $this->par_Valor1->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["par_valor2"] = new clsSQLParameter("ctrlpar_valor2", ccsText, "", "", $this->par_valor2->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["par_Valor3"] = new clsSQLParameter("ctrlpar_Valor3", ccsText, "", "", $this->par_Valor3->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["par_Valor4"] = new clsSQLParameter("ctrlpar_Valor4", ccsText, "", "", $this->par_Valor4->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["par_Categoria"] = new clsSQLParameter("urlcat_Codigo", ccsInteger, "", "", CCGetFromGet("cat_Codigo", ""), "", false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO genparametros ("
             . "par_Clave, "
             . "par_Secuencia, "
             . "par_Descripcion, "
             . "par_Valor1, "
             . "par_valor2, "
             . "par_Valor3, "
             . "par_Valor4, "
             . "par_Categoria"
             . ") VALUES ("
             . $this->ToSQL($this->cp["par_Clave"]->GetDBValue(), $this->cp["par_Clave"]->DataType) . ", "
             . $this->ToSQL($this->cp["par_Secuencia"]->GetDBValue(), $this->cp["par_Secuencia"]->DataType) . ", "
             . $this->ToSQL($this->cp["par_Descripcion"]->GetDBValue(), $this->cp["par_Descripcion"]->DataType) . ", "
             . $this->ToSQL($this->cp["par_Valor1"]->GetDBValue(), $this->cp["par_Valor1"]->DataType) . ", "
             . $this->ToSQL($this->cp["par_valor2"]->GetDBValue(), $this->cp["par_valor2"]->DataType) . ", "
             . $this->ToSQL($this->cp["par_Valor3"]->GetDBValue(), $this->cp["par_Valor3"]->DataType) . ", "
             . $this->ToSQL($this->cp["par_Valor4"]->GetDBValue(), $this->cp["par_Valor4"]->DataType) . ", "
             . $this->ToSQL($this->cp["par_Categoria"]->GetDBValue(), $this->cp["par_Categoria"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Insert Method

//Update Method @374-A285B4BB
    function Update()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $this->Where = "par_Categoria=" . $this->ToSQL($this->CachedColumns["par_Categoria"], ccsInteger) . " AND par_Clave=" . $this->ToSQL($this->CachedColumns["par_Clave"], ccsText) . " AND par_Secuencia=" . $this->ToSQL($this->CachedColumns["par_Secuencia"], ccsInteger);
        $this->SQL = "UPDATE genparametros SET "
             . "par_Clave=" . $this->ToSQL($this->par_Clave->GetDBValue(), $this->par_Clave->DataType) . ", "
             . "par_Secuencia=" . $this->ToSQL($this->par_Secuencia->GetDBValue(), $this->par_Secuencia->DataType) . ", "
             . "par_Descripcion=" . $this->ToSQL($this->par_Descripcion->GetDBValue(), $this->par_Descripcion->DataType) . ", "
             . "par_Valor1=" . $this->ToSQL($this->par_Valor1->GetDBValue(), $this->par_Valor1->DataType) . ", "
             . "par_valor2=" . $this->ToSQL($this->par_valor2->GetDBValue(), $this->par_valor2->DataType) . ", "
             . "par_Valor3=" . $this->ToSQL($this->par_Valor3->GetDBValue(), $this->par_Valor3->DataType) . ", "
             . "par_Valor4=" . $this->ToSQL($this->par_Valor4->GetDBValue(), $this->par_Valor4->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Update Method

//Delete Method @374-37FCB5A7
    function Delete()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $this->Where = "par_Categoria=" . $this->ToSQL($this->CachedColumns["par_Categoria"], ccsInteger) . " AND par_Clave=" . $this->ToSQL($this->CachedColumns["par_Clave"], ccsText) . " AND par_Secuencia=" . $this->ToSQL($this->CachedColumns["par_Secuencia"], ccsInteger);
        $this->SQL = "DELETE FROM genparametros";
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Delete Method

} //End gecapa_detaDataSource Class @374-FCB6E20C





//Initialize Page @1-EE23E4F0
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

$FileName = "GePaMa_mant.php";
$Redirect = "";
$TemplateFileName = "GePaMa_mant.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-4A9B3A79
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
$gecapa_mant = new clsRecordgecapa_mant();
$gecapa_deta = new clsEditableGridgecapa_deta();
$gecapa_mant->Initialize();
$gecapa_deta->Initialize();

// Events
include("./GePaMa_mant_events.php");
BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-F6DD9B90
$Cabecera->Operations();
$gecapa_mant->Operation();
$gecapa_deta->Operation();
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

//Show Page @1-12E93384
//$Header->Show("Header");
$gecapa_mant->Show();
$gecapa_deta->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
$generated_with = "<center><font face=\"Arial\"><small></small></font></center>";
$main_block = preg_match("/<\/body>/i", $main_block) ? preg_replace("/<\/body>/i", $generated_with . "</body>", $main_block) : $main_block . $generated_with;
echo $main_block;
//End Show Page

//Unload Page @1-6786D1ED
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBdatos->close();
unset($Tpl);
//End Unload Page


?>
