<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @240-49BEB8FC
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation

Class clsEditableGriddosis_mant { //dosis_mant Class @2-BCDAC873

//Variables @2-2E6BE019

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
    var $Sorter_dos_CodComponente;
    var $Sorter_dos_codItem;
    var $Sorter_act_Descripcion;
    var $Sorter_act_UniMedida;
    var $Sorter_dos_Cantidad;
    var $Navigator;
//End Variables

//Class_Initialize Event @2-683A7E0F
    function clsEditableGriddosis_mant()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "EditableGrid dosis_mant/Error";
        $this->ComponentName = "dosis_mant";
        $this->ds = new clsdosis_mantDataSource();

        $this->PageSize = 10;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: EditableGrid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));

        $this->EmptyRows = 6;
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

        $this->SorterName = CCGetParam("dosis_mantOrder", "");
        $this->SorterDirection = CCGetParam("dosis_mantDir", "");

        $this->lbTitulo = new clsControl(ccsLabel, "lbTitulo", "lbTitulo", ccsText, "");
        $this->Sorter_dos_CodComponente = new clsSorter($this->ComponentName, "Sorter_dos_CodComponente", $FileName);
        $this->Sorter_dos_codItem = new clsSorter($this->ComponentName, "Sorter_dos_codItem", $FileName);
        $this->Sorter_act_Descripcion = new clsSorter($this->ComponentName, "Sorter_act_Descripcion", $FileName);
        $this->Sorter_act_UniMedida = new clsSorter($this->ComponentName, "Sorter_act_UniMedida", $FileName);
        $this->Sorter_dos_Cantidad = new clsSorter($this->ComponentName, "Sorter_dos_Cantidad", $FileName);
        $this->dos_CodComponente = new clsControl(ccsHidden, "dos_CodComponente", "Dos Cod Componente", ccsInteger, "");
        $this->cte_Codigo = new clsControl(ccsHidden, "cte_Codigo", "Cte Codigo", ccsInteger, "");
        $this->dos_codItem = new clsControl(ccsTextBox, "dos_codItem", "Código de Item", ccsInteger, "");
        $this->dos_codItem->Required = true;
        $this->act_Descripcion = new clsControl(ccsTextBox, "act_Descripcion", "Descripcion", ccsText, "");
        $this->act_UniMedida = new clsControl(ccsHidden, "act_UniMedida", "Act Uni Medida", ccsInteger, "");
        $this->uni_Abreviatura = new clsControl(ccsTextBox, "uni_Abreviatura", "Uni Abreviatura", ccsText, "");
        $this->dos_Cantidad = new clsControl(ccsTextBox, "dos_Cantidad", "Dos Cantidad", ccsFloat, "");
        $this->dos_Cantidad->Required = true;
        $this->CheckBox_Delete = new clsControl(ccsCheckBox, "CheckBox_Delete", "CheckBox_Delete", ccsBoolean, Array("Y", "N", ""));
        $this->CheckBox_Delete->CheckedValue = $this->CheckBox_Delete->GetParsedValue(true);
        $this->CheckBox_Delete->UncheckedValue = $this->CheckBox_Delete->GetParsedValue(false);
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple);
        $this->Button_Submit = new clsButton("Button_Submit");
        $this->Cancel = new clsButton("Cancel");
        $this->Button1 = new clsButton("Button1");
    }
//End Class_Initialize Event

//Initialize Method @2-9AEE223D
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);

        $this->ds->Parameters["urlcte_Codigo"] = CCGetFromGet("cte_Codigo", "");
    }
//End Initialize Method

//GetFormParameters Method @2-449871D1
    function GetFormParameters()
    {
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->FormParameters["dos_CodComponente"][$RowNumber] = CCGetFromPost("dos_CodComponente_" . $RowNumber);
            $this->FormParameters["cte_Codigo"][$RowNumber] = CCGetFromPost("cte_Codigo_" . $RowNumber);
            $this->FormParameters["dos_codItem"][$RowNumber] = CCGetFromPost("dos_codItem_" . $RowNumber);
            $this->FormParameters["act_Descripcion"][$RowNumber] = CCGetFromPost("act_Descripcion_" . $RowNumber);
            $this->FormParameters["act_UniMedida"][$RowNumber] = CCGetFromPost("act_UniMedida_" . $RowNumber);
            $this->FormParameters["uni_Abreviatura"][$RowNumber] = CCGetFromPost("uni_Abreviatura_" . $RowNumber);
            $this->FormParameters["dos_Cantidad"][$RowNumber] = CCGetFromPost("dos_Cantidad_" . $RowNumber);
            $this->FormParameters["CheckBox_Delete"][$RowNumber] = CCGetFromPost("CheckBox_Delete_" . $RowNumber);
        }
    }
//End GetFormParameters Method

//Validate Method @2-AE7ECCEC
    function Validate()
    {
        $Validation = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");

        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->dos_CodComponente->SetText($this->FormParameters["dos_CodComponente"][$RowNumber], $RowNumber);
            $this->cte_Codigo->SetText($this->FormParameters["cte_Codigo"][$RowNumber], $RowNumber);
            $this->dos_codItem->SetText($this->FormParameters["dos_codItem"][$RowNumber], $RowNumber);
            $this->act_Descripcion->SetText($this->FormParameters["act_Descripcion"][$RowNumber], $RowNumber);
            $this->act_UniMedida->SetText($this->FormParameters["act_UniMedida"][$RowNumber], $RowNumber);
            $this->uni_Abreviatura->SetText($this->FormParameters["uni_Abreviatura"][$RowNumber], $RowNumber);
            $this->dos_Cantidad->SetText($this->FormParameters["dos_Cantidad"][$RowNumber], $RowNumber);
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

//ValidateRow Method @2-89682FAE
    function ValidateRow($RowNumber)
    {
        $Validation = true;
        $Validation = ($this->dos_CodComponente->Validate() && $Validation);
        $Validation = ($this->cte_Codigo->Validate() && $Validation);
        $Validation = ($this->dos_codItem->Validate() && $Validation);
        $Validation = ($this->act_Descripcion->Validate() && $Validation);
        $Validation = ($this->act_UniMedida->Validate() && $Validation);
        $Validation = ($this->uni_Abreviatura->Validate() && $Validation);
        $Validation = ($this->dos_Cantidad->Validate() && $Validation);
        $Validation = ($this->CheckBox_Delete->Validate() && $Validation);
        $errors = "";
        if(!$Validation)
        {
            $errors .= $this->dos_CodComponente->Errors->ToString();
            $errors .= $this->cte_Codigo->Errors->ToString();
            $errors .= $this->dos_codItem->Errors->ToString();
            $errors .= $this->act_Descripcion->Errors->ToString();
            $errors .= $this->act_UniMedida->Errors->ToString();
            $errors .= $this->uni_Abreviatura->Errors->ToString();
            $errors .= $this->dos_Cantidad->Errors->ToString();
            $errors .= $this->CheckBox_Delete->Errors->ToString();
            $this->dos_CodComponente->Errors->Clear();
            $this->cte_Codigo->Errors->Clear();
            $this->dos_codItem->Errors->Clear();
            $this->act_Descripcion->Errors->Clear();
            $this->act_UniMedida->Errors->Clear();
            $this->uni_Abreviatura->Errors->Clear();
            $this->dos_Cantidad->Errors->Clear();
            $this->CheckBox_Delete->Errors->Clear();
        }
        $this->RowsErrors[$RowNumber] = $errors;
        return $Validation;
    }
//End ValidateRow Method

//CheckInsert Method @2-43D5544B
    function CheckInsert($RowNumber)
    {
        $filed = false;
        $filed = ($filed || strlen($this->FormParameters["dos_CodComponente"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cte_Codigo"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["dos_codItem"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["act_Descripcion"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["act_UniMedida"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["uni_Abreviatura"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["dos_Cantidad"][$RowNumber]));
        return $filed;
    }
//End CheckInsert Method

//CheckErrors Method @2-242E5992
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-2CF20A74
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
        } else if(strlen(CCGetParam("Button1", ""))) {
            $this->PressedButton = "Button1";
        }

        $Redirect = $FileName . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "Button_Submit") {
            if(!CCGetEvent($this->Button_Submit->CCSEvents, "OnClick") || !$this->UpdateGrid()) {
                $Redirect = "";
            } else {
                $Redirect = "LiAdCo.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm", "cte_Codigo"));
            }
        } else if($this->PressedButton == "Cancel") {
            if(!CCGetEvent($this->Cancel->CCSEvents, "OnClick")) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "Button1") {
            if(!CCGetEvent($this->Button1->CCSEvents, "OnClick")) {
                $Redirect = "";
            } else {
                $Redirect = "LiAdCo.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm", "cte_Codigo"));
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//UpdateGrid Method @2-625891FF
    function UpdateGrid()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSubmit");
        if(!$this->Validate()) return;
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->dos_CodComponente->SetText($this->FormParameters["dos_CodComponente"][$RowNumber], $RowNumber);
            $this->cte_Codigo->SetText($this->FormParameters["cte_Codigo"][$RowNumber], $RowNumber);
            $this->dos_codItem->SetText($this->FormParameters["dos_codItem"][$RowNumber], $RowNumber);
            $this->act_Descripcion->SetText($this->FormParameters["act_Descripcion"][$RowNumber], $RowNumber);
            $this->act_UniMedida->SetText($this->FormParameters["act_UniMedida"][$RowNumber], $RowNumber);
            $this->uni_Abreviatura->SetText($this->FormParameters["uni_Abreviatura"][$RowNumber], $RowNumber);
            $this->dos_Cantidad->SetText($this->FormParameters["dos_Cantidad"][$RowNumber], $RowNumber);
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

//InsertRow Method @2-96AE33B8
    function InsertRow($RowNumber)
    {
        if(!$this->InsertAllowed) return false;
        $this->ds->dos_codItem->SetValue($this->dos_codItem->GetValue());
        $this->ds->dos_Cantidad->SetValue($this->dos_Cantidad->GetValue());
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

//UpdateRow Method @2-DCD5B32C
    function UpdateRow($RowNumber)
    {
        if(!$this->UpdateAllowed) return false;
        $this->ds->dos_Cantidad->SetValue($this->dos_Cantidad->GetValue());
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

//DeleteRow Method @2-0C9DDC34
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

//FormScript Method @2-59800DB5
    function FormScript($TotalRows)
    {
        $script = "";
        return $script;
    }
//End FormScript Method

//SetFormState Method @2-69E01441
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
            for($i = 2; $i < sizeof($pieces); $i = $i + 0)  {
                $RowNumber++;
            }

            if(!$RowNumber) { $RowNumber = 1; }
            for($i = 1; $i <= $this->EmptyRows; $i++) {
                $RowNumber++;
            }
        }
    }
//End SetFormState Method

//GetFormState Method @2-BF9CEBD0
    function GetFormState($NonEmptyRows)
    {
        if(!$this->FormSubmitted) {
            $this->FormState  = $NonEmptyRows . ";";
            $this->FormState .= $this->InsertAllowed ? $this->EmptyRows : "0";
            if($NonEmptyRows) {
                for($i = 0; $i <= $NonEmptyRows; $i++) {
                }
            }
        }
        return $this->FormState;
    }
//End GetFormState Method

//Show Method @2-7B79E2F1
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
                        $this->dos_CodComponente->SetValue($this->ds->dos_CodComponente->GetValue());
                        $this->cte_Codigo->SetValue($this->ds->cte_Codigo->GetValue());
                        $this->dos_codItem->SetValue($this->ds->dos_codItem->GetValue());
                        $this->act_Descripcion->SetValue($this->ds->act_Descripcion->GetValue());
                        $this->act_UniMedida->SetValue($this->ds->act_UniMedida->GetValue());
                        $this->uni_Abreviatura->SetValue($this->ds->uni_Abreviatura->GetValue());
                        $this->dos_Cantidad->SetValue($this->ds->dos_Cantidad->GetValue());
                        $this->ValidateRow($RowNumber);
                    } else if (!$this->FormSubmitted){
                        $this->dos_CodComponente->SetText("");
                        $this->cte_Codigo->SetText("");
                        $this->dos_codItem->SetText("");
                        $this->act_Descripcion->SetText("");
                        $this->act_UniMedida->SetText("");
                        $this->uni_Abreviatura->SetText("");
                        $this->dos_Cantidad->SetText("");
                        $this->CheckBox_Delete->SetText("");
                    } else {
                        $this->dos_CodComponente->SetText($this->FormParameters["dos_CodComponente"][$RowNumber], $RowNumber);
                        $this->cte_Codigo->SetText($this->FormParameters["cte_Codigo"][$RowNumber], $RowNumber);
                        $this->dos_codItem->SetText($this->FormParameters["dos_codItem"][$RowNumber], $RowNumber);
                        $this->act_Descripcion->SetText($this->FormParameters["act_Descripcion"][$RowNumber], $RowNumber);
                        $this->act_UniMedida->SetText($this->FormParameters["act_UniMedida"][$RowNumber], $RowNumber);
                        $this->uni_Abreviatura->SetText($this->FormParameters["uni_Abreviatura"][$RowNumber], $RowNumber);
                        $this->dos_Cantidad->SetText($this->FormParameters["dos_Cantidad"][$RowNumber], $RowNumber);
                        $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$RowNumber], $RowNumber);
                    }
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->dos_CodComponente->Show($RowNumber);
                    $this->cte_Codigo->Show($RowNumber);
                    $this->dos_codItem->Show($RowNumber);
                    $this->act_Descripcion->Show($RowNumber);
                    $this->act_UniMedida->Show($RowNumber);
                    $this->uni_Abreviatura->Show($RowNumber);
                    $this->dos_Cantidad->Show($RowNumber);
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
        $this->Navigator->PageNumber = $this->ds->AbsolutePage;
        $this->Navigator->TotalPages = $this->ds->PageCount();
        $this->lbTitulo->Show();
        $this->Sorter_dos_CodComponente->Show();
        $this->Sorter_dos_codItem->Show();
        $this->Sorter_act_Descripcion->Show();
        $this->Sorter_act_UniMedida->Show();
        $this->Sorter_dos_Cantidad->Show();
        $this->Navigator->Show();
        $this->Button_Submit->Show();
        $this->Cancel->Show();
        $this->Button1->Show();

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

} //End dosis_mant Class @2-FCB6E20C

class clsdosis_mantDataSource extends clsDBdatos {  //dosis_mantDataSource Class @2-0A71A339

//DataSource Variables @2-37C5514D
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $InsertParameters;
    var $UpdateParameters;
    var $DeleteParameters;
    var $CountSQL;
    var $wp;
    var $AllParametersSet;


    // Datasource fields
    var $dos_CodComponente;
    var $cte_Codigo;
    var $dos_codItem;
    var $act_Descripcion;
    var $act_UniMedida;
    var $uni_Abreviatura;
    var $dos_Cantidad;
    var $CheckBox_Delete;
//End DataSource Variables

//Class_Initialize Event @2-5092C374
    function clsdosis_mantDataSource()
    {
        $this->ErrorBlock = "EditableGrid dosis_mant/Error";
        $this->Initialize();
        $this->dos_CodComponente = new clsField("dos_CodComponente", ccsInteger, "");
        $this->cte_Codigo = new clsField("cte_Codigo", ccsInteger, "");
        $this->dos_codItem = new clsField("dos_codItem", ccsInteger, "");
        $this->act_Descripcion = new clsField("act_Descripcion", ccsText, "");
        $this->act_UniMedida = new clsField("act_UniMedida", ccsInteger, "");
        $this->uni_Abreviatura = new clsField("uni_Abreviatura", ccsText, "");
        $this->dos_Cantidad = new clsField("dos_Cantidad", ccsFloat, "");
        $this->CheckBox_Delete = new clsField("CheckBox_Delete", ccsBoolean, Array("true", "false", ""));

    }
//End Class_Initialize Event

//SetOrder Method @2-BC9E3DCC
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_dos_CodComponente" => array("dos_CodComponente", ""), 
            "Sorter_dos_codItem" => array("dos_codItem", ""), 
            "Sorter_act_Descripcion" => array("act_Descripcion", ""), 
            "Sorter_act_UniMedida" => array("act_UniMedida", ""), 
            "Sorter_dos_Cantidad" => array("dos_Cantidad", "")));
    }
//End SetOrder Method

//Prepare Method @2-6716E3EC
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlcte_Codigo", ccsInteger, "", "", $this->Parameters["urlcte_Codigo"], "", true);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "cte_Codigo", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),true);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @2-1DB7CFF9
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM ((liqdosis INNER JOIN liqcomponent ON liqcomponent.cte_Codigo = liqdosis.dos_CodComponente) INNER JOIN conactivos ON liqdosis.dos_codItem = conactivos.act_CodAuxiliar) INNER JOIN genunmedida ON conactivos.act_UniMedida = genunmedida.uni_CodUnidad";
        $this->SQL = "SELECT cte_Referencia, cte_Descripcion, liqdosis.*, act_Descripcion, act_Descripcion1, uni_Abreviatura  " .
        "FROM ((liqdosis INNER JOIN liqcomponent ON liqcomponent.cte_Codigo = liqdosis.dos_CodComponente) INNER JOIN conactivos ON liqdosis.dos_codItem = conactivos.act_CodAuxiliar) INNER JOIN genunmedida ON conactivos.act_UniMedida = genunmedida.uni_CodUnidad";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @2-659D59A4
    function SetValues()
    {
        $this->dos_CodComponente->SetDBValue(trim($this->f("dos_CodComponente")));
        $this->cte_Codigo->SetDBValue(trim($this->f("cte_Codigo")));
        $this->dos_codItem->SetDBValue(trim($this->f("dos_codItem")));
        $this->act_Descripcion->SetDBValue($this->f("act_Descripcion"));
        $this->act_UniMedida->SetDBValue(trim($this->f("act_UniMedida")));
        $this->uni_Abreviatura->SetDBValue($this->f("uni_Abreviatura"));
        $this->dos_Cantidad->SetDBValue(trim($this->f("dos_Cantidad")));
    }
//End SetValues Method

//Insert Method @2-E77C6B3A
    function Insert()
    {
        $this->cp["dos_CodComponente"] = new clsSQLParameter("urlcte_Codigo", ccsInteger, "", "", CCGetFromGet("cte_Codigo", ""), -1, false, $this->ErrorBlock);
        $this->cp["dos_codItem"] = new clsSQLParameter("ctrldos_codItem", ccsInteger, "", "", $this->dos_codItem->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["dos_Cantidad"] = new clsSQLParameter("ctrldos_Cantidad", ccsFloat, "", "", $this->dos_Cantidad->GetValue(), 0, false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO liqdosis ("
             . "dos_CodComponente, "
             . "dos_codItem, "
             . "dos_Cantidad"
             . ") VALUES ("
             . $this->ToSQL($this->cp["dos_CodComponente"]->GetDBValue(), $this->cp["dos_CodComponente"]->DataType) . ", "
             . $this->ToSQL($this->cp["dos_codItem"]->GetDBValue(), $this->cp["dos_codItem"]->DataType) . ", "
             . $this->ToSQL($this->cp["dos_Cantidad"]->GetDBValue(), $this->cp["dos_Cantidad"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Insert Method

//Update Method @2-04A0F3FD
    function Update()
    {
        $this->cp["dos_Cantidad"] = new clsSQLParameter("ctrldos_Cantidad", ccsFloat, "", "", $this->dos_Cantidad->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["dos_codItem"] = new clsSQLParameter("ctrldos_codItem", ccsInteger, "", "", $this->dos_codItem->GetValue(), 0, false, $this->ErrorBlock);
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlcte_Codigo", ccsInteger, "", "", CCGetFromGet("cte_Codigo", ""), -1, false);
        $wp->AddParameter("2", "postdos_codItem", ccsInteger, "", "", CCGetFromPost("dos_codItem", ""), "", false);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $wp->Criterion[1] = $wp->Operation(opEqual, "dos_CodComponente", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),false);
        $wp->Criterion[2] = $wp->Operation(opEqual, "dos_codItem", $wp->GetDBValue("2"), $this->ToSQL($wp->GetDBValue("2"), ccsInteger),false);
        $Where = $wp->opAND(false, $wp->Criterion[1], $wp->Criterion[2]);
        $this->SQL = "UPDATE liqdosis SET "
             . "dos_Cantidad=" . $this->ToSQL($this->cp["dos_Cantidad"]->GetDBValue(), $this->cp["dos_Cantidad"]->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($this->SQL);


        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();

    }
//End Update Method

//Delete Method @2-E4B665EF
    function Delete()
    {
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlcte_Codigo", ccsInteger, "", "", CCGetFromGet("cte_Codigo", ""), "", true);
//        $wp->AddParameter("2", "ctrldos_codItem", ccsInteger, "", "", CCGetFromPost("dos_codItem", ""), "", true);
        $wp->AddParameter("2", "dsdos_codItem", ccsInteger, "", "", $this->CachedColumns["dos_codItem"], "", false);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $wp->Criterion[1] = $wp->Operation(opEqual, "dos_CodComponente", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),true);
//        $wp->Criterion[2] = $wp->Operation(opEqual, "dos_codItem", $wp->GetDBValue("2"), $this->ToSQL($wp->GetDBValue("2"), ccsInteger),true);
        $wp->Criterion[2] = $wp->Operation(opEqual, "dos_codItem", $wp->GetDBValue("2"), $this->ToSQL($wp->GetDBValue("2"), ccsInteger),false);
        $Where = $wp->opAND(false, $wp->Criterion[1], $wp->Criterion[2]);
        $this->SQL = "DELETE FROM liqdosis";
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
echo " <br> <br>  $this->SQL<br> ---> "  . $this->CachedColumns["dos_codItem"];
$this->Errors->AddError('EE');
//        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Delete Method

} //End dosis_mantDataSource Class @2-FCB6E20C

//Initialize Page @1-380E4997
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

$FileName = "LiAdRe.php";
$Redirect = "";
$TemplateFileName = "LiAdRe.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-1968FFF7
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
$dosis_mant = new clsEditableGriddosis_mant();
$dosis_mant->Initialize();

// Events
include("./LiAdRe_events.php");
BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-A761C922
$Cabecera->Operations();
$dosis_mant->Operation();
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

//Show Page @1-C29AF0BD
$Cabecera->Show("Cabecera");
$dosis_mant->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
//$generated_with = "<center><font face=\"Arial\"><small>Generated with CodeCharge Studio</small></font></center>";
//$main_block = preg_match("/<\/body>/i", $main_block) ? preg_replace("/<\/body>/i", $generated_with . "</body>", $main_block) : $main_block . $generated_with;
echo $main_block;
//End Show Page

//Unload Page @1-6786D1ED
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBdatos->close();
unset($Tpl);
//End Unload Page


?>
