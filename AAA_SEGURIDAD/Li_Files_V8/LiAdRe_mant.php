<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @284-49BEB8FC
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation

class clsEditableGriddosis_mant { //dosis_mant Class @121-BB56D412

//Variables @121-73535229

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
    var $Sorter_dos_codItem;
    var $Sorter_act_Descripcion;
    var $Sorter_uni_Descripcion;
    var $Sorter_dos_Cantidad;
    var $Sorter_pre_PreUnitario;
    var $Navigator;
//End Variables

//Class_Initialize Event @121-1A1CE7D9
    function clsEditableGriddosis_mant()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "EditableGrid dosis_mant/Error";
        $this->ComponentName = "dosis_mant";
        $this->CachedColumns["dos_codItem"][0] = "dos_codItem";
        $this->ds = new clsdosis_mantDataSource();

        $this->PageSize = 15;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: EditableGrid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));

        $this->EmptyRows = 10;
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
        $this->Sorter_dos_codItem = new clsSorter($this->ComponentName, "Sorter_dos_codItem", $FileName);
        $this->Sorter_act_Descripcion = new clsSorter($this->ComponentName, "Sorter_act_Descripcion", $FileName);
        $this->Sorter_uni_Descripcion = new clsSorter($this->ComponentName, "Sorter_uni_Descripcion", $FileName);
        $this->Sorter_dos_Cantidad = new clsSorter($this->ComponentName, "Sorter_dos_Cantidad", $FileName);
        $this->Sorter_pre_PreUnitario = new clsSorter($this->ComponentName, "Sorter_pre_PreUnitario", $FileName);
        $this->dos_codItem = new clsControl(ccsTextBox, "dos_codItem", "Dos Cod Item", ccsInteger, "");
        $this->act_Descripcion = new clsControl(ccsTextBox, "act_Descripcion", "Act Descripcion", ccsText, "");
        $this->uni_Descripcion = new clsControl(ccsTextBox, "uni_Descripcion", "Uni Descripcion", ccsText, "");
        $this->dos_Cantidad = new clsControl(ccsTextBox, "dos_Cantidad", "Cantidad", ccsFloat, Array(False, 4, ".", "", False, "", "", 1, True, ""));
        $this->dos_Cantidad->Required = true;
        $this->pre_PreUnitario = new clsControl(ccsTextBox, "pre_PreUnitario", "Precio Unitario", ccsFloat, Array(False, 4, ".", "", False, "", "", 1, True, ""));
        $this->CheckBox_Delete = new clsControl(ccsCheckBox, "CheckBox_Delete", "CheckBox_Delete", ccsBoolean, Array("True", "False", ""));
        $this->CheckBox_Delete->CheckedValue = $this->CheckBox_Delete->GetParsedValue(true);
        $this->CheckBox_Delete->UncheckedValue = $this->CheckBox_Delete->GetParsedValue(false);
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpCentered);
        $this->Button_Submit = new clsButton("Button_Submit");
        $this->Cancel = new clsButton("Cancel");
    }
//End Class_Initialize Event

//Initialize Method @121-176AE03F
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);

        $this->ds->Parameters["urldos_CodComponente"] = CCGetFromGet("dos_CodComponente", "");
    }
//End Initialize Method

//GetFormParameters Method @121-9086D225
    function GetFormParameters()
    {
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->FormParameters["dos_codItem"][$RowNumber] = CCGetFromPost("dos_codItem_" . $RowNumber);
            $this->FormParameters["act_Descripcion"][$RowNumber] = CCGetFromPost("act_Descripcion_" . $RowNumber);
            $this->FormParameters["uni_Descripcion"][$RowNumber] = CCGetFromPost("uni_Descripcion_" . $RowNumber);
            $this->FormParameters["dos_Cantidad"][$RowNumber] = CCGetFromPost("dos_Cantidad_" . $RowNumber);
            $this->FormParameters["pre_PreUnitario"][$RowNumber] = CCGetFromPost("pre_PreUnitario_" . $RowNumber);
            $this->FormParameters["CheckBox_Delete"][$RowNumber] = CCGetFromPost("CheckBox_Delete_" . $RowNumber);
        }
    }
//End GetFormParameters Method

//Validate Method @121-818C6ACC
    function Validate()
    {
        $Validation = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");

        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["dos_codItem"] = $this->CachedColumns["dos_codItem"][$RowNumber];
            $this->dos_codItem->SetText($this->FormParameters["dos_codItem"][$RowNumber], $RowNumber);
            $this->act_Descripcion->SetText($this->FormParameters["act_Descripcion"][$RowNumber], $RowNumber);
            $this->uni_Descripcion->SetText($this->FormParameters["uni_Descripcion"][$RowNumber], $RowNumber);
            $this->dos_Cantidad->SetText($this->FormParameters["dos_Cantidad"][$RowNumber], $RowNumber);
            $this->pre_PreUnitario->SetText($this->FormParameters["pre_PreUnitario"][$RowNumber], $RowNumber);
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

//ValidateRow Method @121-84F5783C
    function ValidateRow($RowNumber)
    {
        $Validation = true;
        $Validation = ($this->dos_codItem->Validate() && $Validation);
        $Validation = ($this->act_Descripcion->Validate() && $Validation);
        $Validation = ($this->uni_Descripcion->Validate() && $Validation);
        $Validation = ($this->dos_Cantidad->Validate() && $Validation);
        $Validation = ($this->pre_PreUnitario->Validate() && $Validation);
        $Validation = ($this->CheckBox_Delete->Validate() && $Validation);
        $errors = "";
        if(!$Validation)
        {
            $errors .= $this->dos_codItem->Errors->ToString();
            $errors .= $this->act_Descripcion->Errors->ToString();
            $errors .= $this->uni_Descripcion->Errors->ToString();
            $errors .= $this->dos_Cantidad->Errors->ToString();
            $errors .= $this->pre_PreUnitario->Errors->ToString();
            $errors .= $this->CheckBox_Delete->Errors->ToString();
            $this->dos_codItem->Errors->Clear();
            $this->act_Descripcion->Errors->Clear();
            $this->uni_Descripcion->Errors->Clear();
            $this->dos_Cantidad->Errors->Clear();
            $this->pre_PreUnitario->Errors->Clear();
            $this->CheckBox_Delete->Errors->Clear();
        }
        $this->RowsErrors[$RowNumber] = $errors;
        return $Validation;
    }
//End ValidateRow Method

//CheckInsert Method @121-FDEC60EF
    function CheckInsert($RowNumber)
    {
        $filed = false;
        $filed = ($filed || strlen($this->FormParameters["dos_codItem"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["act_Descripcion"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["uni_Descripcion"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["dos_Cantidad"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["pre_PreUnitario"][$RowNumber]));
        return $filed;
    }
//End CheckInsert Method

//CheckErrors Method @121-242E5992
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @121-7B861278
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

//UpdateGrid Method @121-08D6D68F
    function UpdateGrid()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSubmit");
        if(!$this->Validate()) return;
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["dos_codItem"] = $this->CachedColumns["dos_codItem"][$RowNumber];
            $this->dos_codItem->SetText($this->FormParameters["dos_codItem"][$RowNumber], $RowNumber);
            $this->act_Descripcion->SetText($this->FormParameters["act_Descripcion"][$RowNumber], $RowNumber);
            $this->uni_Descripcion->SetText($this->FormParameters["uni_Descripcion"][$RowNumber], $RowNumber);
            $this->dos_Cantidad->SetText($this->FormParameters["dos_Cantidad"][$RowNumber], $RowNumber);
            $this->pre_PreUnitario->SetText($this->FormParameters["pre_PreUnitario"][$RowNumber], $RowNumber);
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

//InsertRow Method @121-96AE33B8
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

//UpdateRow Method @121-DCD5B32C
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

//DeleteRow Method @121-0C9DDC34
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

//FormScript Method @121-D0662AE0
    function FormScript($TotalRows)
    {
        $script = "";
        $script .= "\n<script language=\"JavaScript\">\n<!--\n";
        $script .= "var dosis_mantElements;\n";
        $script .= "var dosis_mantEmptyRows = 10;\n";
        $script .= "var " . $this->ComponentName . "dos_codItemID = 0;\n";
        $script .= "var " . $this->ComponentName . "act_DescripcionID = 1;\n";
        $script .= "var " . $this->ComponentName . "uni_DescripcionID = 2;\n";
        $script .= "var " . $this->ComponentName . "dos_CantidadID = 3;\n";
        $script .= "var " . $this->ComponentName . "pre_PreUnitarioID = 4;\n";
        $script .= "var " . $this->ComponentName . "DeleteControl = 5;\n";
        $script .= "\nfunction initdosis_mantElements() {\n";
        $script .= "\tvar ED = document.forms[\"dosis_mant\"];\n";
        $script .= "\tdosis_mantElements = new Array (\n";
        for($i = 1; $i <= $TotalRows; $i++) {
            $script .= "\t\tnew Array(" . "ED.dos_codItem_" . $i . ", " . "ED.act_Descripcion_" . $i . ", " . "ED.uni_Descripcion_" . $i . ", " . "ED.dos_Cantidad_" . $i . ", " . "ED.pre_PreUnitario_" . $i . ", " . "ED.CheckBox_Delete_" . $i . ")";
            if($i != $TotalRows) $script .= ",\n";
        }
        $script .= ");\n";
        $script .= "}\n";
        $script .= "\n//-->\n</script>";
        return $script;
    }
//End FormScript Method

//SetFormState Method @121-3D2D691F
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
            for($i = 2; $i < sizeof($pieces); $i = $i + 1)  {
                $piece = $pieces[$i + 0];
                $piece = str_replace("\\" . ord("\\"), "\\", $piece);
                $piece = str_replace("\\" . ord(";"), ";", $piece);
                $this->CachedColumns["dos_codItem"][$RowNumber] = $piece;
                $RowNumber++;
            }

            if(!$RowNumber) { $RowNumber = 1; }
            for($i = 1; $i <= $this->EmptyRows; $i++) {
                $this->CachedColumns["dos_codItem"][$RowNumber] = "";
                $RowNumber++;
            }
        }
    }
//End SetFormState Method

//GetFormState Method @121-8A4B0396
    function GetFormState($NonEmptyRows)
    {
        if(!$this->FormSubmitted) {
            $this->FormState  = $NonEmptyRows . ";";
            $this->FormState .= $this->InsertAllowed ? $this->EmptyRows : "0";
            if($NonEmptyRows) {
                for($i = 0; $i <= $NonEmptyRows; $i++) {
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["dos_codItem"][$i]));
                }
            }
        }
        return $this->FormState;
    }
//End GetFormState Method

//Show Method @121-93D69983
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
                        $this->CachedColumns["dos_codItem"][$RowNumber] = $this->ds->CachedColumns["dos_codItem"];
                        $this->dos_codItem->SetValue($this->ds->dos_codItem->GetValue());
                        $this->act_Descripcion->SetValue($this->ds->act_Descripcion->GetValue());
                        $this->uni_Descripcion->SetValue($this->ds->uni_Descripcion->GetValue());
                        $this->dos_Cantidad->SetValue($this->ds->dos_Cantidad->GetValue());
                        $this->pre_PreUnitario->SetValue($this->ds->pre_PreUnitario->GetValue());
                        $this->ValidateRow($RowNumber);
                    } else if (!$this->FormSubmitted){
                        $this->CachedColumns["dos_codItem"][$RowNumber] = "";
                        $this->dos_codItem->SetText("");
                        $this->act_Descripcion->SetText("");
                        $this->uni_Descripcion->SetText("");
                        $this->dos_Cantidad->SetText("");
                        $this->pre_PreUnitario->SetText("");
                        $this->CheckBox_Delete->SetText("");
                    } else {
                        $this->dos_codItem->SetText($this->FormParameters["dos_codItem"][$RowNumber], $RowNumber);
                        $this->act_Descripcion->SetText($this->FormParameters["act_Descripcion"][$RowNumber], $RowNumber);
                        $this->uni_Descripcion->SetText($this->FormParameters["uni_Descripcion"][$RowNumber], $RowNumber);
                        $this->dos_Cantidad->SetText($this->FormParameters["dos_Cantidad"][$RowNumber], $RowNumber);
                        $this->pre_PreUnitario->SetText($this->FormParameters["pre_PreUnitario"][$RowNumber], $RowNumber);
                        $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$RowNumber], $RowNumber);
                    }
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->dos_codItem->Show($RowNumber);
                    $this->act_Descripcion->Show($RowNumber);
                    $this->uni_Descripcion->Show($RowNumber);
                    $this->dos_Cantidad->Show($RowNumber);
                    $this->pre_PreUnitario->Show($RowNumber);
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
        $this->Sorter_dos_codItem->Show();
        $this->Sorter_act_Descripcion->Show();
        $this->Sorter_uni_Descripcion->Show();
        $this->Sorter_dos_Cantidad->Show();
        $this->Sorter_pre_PreUnitario->Show();
        $this->Navigator->Show();
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

} //End dosis_mant Class @121-FCB6E20C

class clsdosis_mantDataSource extends clsDBdatos {  //dosis_mantDataSource Class @121-0A71A339

//DataSource Variables @121-0E1AF864
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
    var $dos_codItem;
    var $act_Descripcion;
    var $uni_Descripcion;
    var $dos_Cantidad;
    var $pre_PreUnitario;
    var $CheckBox_Delete;
//End DataSource Variables

//Class_Initialize Event @121-A75C9901
    function clsdosis_mantDataSource()
    {
        $this->ErrorBlock = "EditableGrid dosis_mant/Error";
        $this->Initialize();
        $this->dos_codItem = new clsField("dos_codItem", ccsInteger, "");
        $this->act_Descripcion = new clsField("act_Descripcion", ccsText, "");
        $this->uni_Descripcion = new clsField("uni_Descripcion", ccsText, "");
        $this->dos_Cantidad = new clsField("dos_Cantidad", ccsFloat, "");
        $this->pre_PreUnitario = new clsField("pre_PreUnitario", ccsFloat, "");
        $this->CheckBox_Delete = new clsField("CheckBox_Delete", ccsBoolean, Array("true", "false", ""));

    }
//End Class_Initialize Event

//SetOrder Method @121-31E7011C
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_dos_codItem" => array("dos_codItem", ""), 
            "Sorter_act_Descripcion" => array("act_Descripcion", ""), 
            "Sorter_uni_Descripcion" => array("uni_Descripcion", ""), 
            "Sorter_dos_Cantidad" => array("dos_Cantidad", ""), 
            "Sorter_pre_PreUnitario" => array("pre_PreUnitario", "")));
    }
//End SetOrder Method

//Prepare Method @121-FA009B92
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urldos_CodComponente", ccsInteger, "", "", $this->Parameters["urldos_CodComponente"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "dos_CodComponente", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @121-4285F8FF  JVL se incremento en JOIN linea 614   invprecios.pre_LisPrecios = '3'
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM ((conactivos LEFT JOIN invprecios ON " .
        "conactivos.act_CodAuxiliar = invprecios.pre_CodItem) INNER JOIN liqdosis ON " .
        "liqdosis.dos_codItem = conactivos.act_CodAuxiliar) LEFT JOIN genunmedida ON " .
        "conactivos.act_UniMedida = genunmedida.uni_CodUnidad";
        //$this->SQL = "SELECT act_Descripcion, act_Descripcion1, pre_PreUnitario, uni_Descripcion, liqdosis.*  " .
        $this->SQL = "SELECT act_Descripcion, act_Descripcion1, pre_PreUnitario, uni_Descripcion, dos_codItem, dos_Cantidad  " .
        "FROM ((conactivos LEFT JOIN invprecios ON " .
        "(conactivos.act_CodAuxiliar = invprecios.pre_CodItem AND invprecios.pre_LisPrecios = '3')) INNER JOIN liqdosis ON " .
        "liqdosis.dos_codItem = conactivos.act_CodAuxiliar) LEFT JOIN genunmedida ON " .
        "conactivos.act_UniMedida = genunmedida.uni_CodUnidad" ;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @121-565775CE
    function SetValues()
    {
        $this->CachedColumns["dos_codItem"] = $this->f("dos_codItem");
        $this->dos_codItem->SetDBValue(trim($this->f("dos_codItem")));
        $this->act_Descripcion->SetDBValue($this->f("act_Descripcion"));
        $this->uni_Descripcion->SetDBValue($this->f("uni_Descripcion"));
        $this->dos_Cantidad->SetDBValue(trim($this->f("dos_Cantidad")));
        $this->pre_PreUnitario->SetDBValue(trim($this->f("pre_PreUnitario")));
    }
//End SetValues Method

//Insert Method @121-C9795B0F
    function Insert()
    {
        $this->cp["dos_codItem"] = new clsSQLParameter("ctrldos_codItem", ccsInteger, "", "", $this->dos_codItem->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["dos_Cantidad"] = new clsSQLParameter("ctrldos_Cantidad", ccsFloat, Array(False, 4, ".", "", False, "", "", 1, True, ""), "", $this->dos_Cantidad->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["dos_CodComponente"] = new clsSQLParameter("urldos_CodComponente", ccsInteger, "", "", CCGetFromGet("dos_CodComponente", ""), -1, false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO liqdosis ("
             . "dos_codItem, "
             . "dos_Cantidad, "
             . "dos_CodComponente"
             . ") VALUES ("
             . $this->ToSQL($this->cp["dos_codItem"]->GetDBValue(), $this->cp["dos_codItem"]->DataType) . ", "
             . $this->ToSQL($this->cp["dos_Cantidad"]->GetDBValue(), $this->cp["dos_Cantidad"]->DataType) . ", "
             . $this->ToSQL($this->cp["dos_CodComponente"]->GetDBValue(), $this->cp["dos_CodComponente"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Insert Method

//Update Method @121-B20B91E2
    function Update()
    {
        $this->cp["dos_Cantidad"] = new clsSQLParameter("ctrldos_Cantidad", ccsFloat, Array(False, 4, ".", "", False, "", "", 1, True, ""), "", $this->dos_Cantidad->GetValue(), "", false, $this->ErrorBlock);
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urldos_CodComponente", ccsInteger, "", "", CCGetFromGet("dos_CodComponente", ""), "", true);
        $wp->AddParameter("2", "dsdos_codItem", ccsInteger, "", "", $this->CachedColumns["dos_codItem"], "", false);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $wp->Criterion[1] = $wp->Operation(opEqual, "dos_CodComponente", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),true);
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

//Delete Method @121-11090B11
    function Delete()
    {
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urldos_CodComponente", ccsInteger, "", "", CCGetFromGet("dos_CodComponente", ""), "", true);
        $wp->AddParameter("2", "dsdos_codItem", ccsInteger, "", "", $this->CachedColumns["dos_codItem"], "", true);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $wp->Criterion[1] = $wp->Operation(opEqual, "dos_CodComponente", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),true);
        $wp->Criterion[2] = $wp->Operation(opEqual, "dos_codItem", $wp->GetDBValue("2"), $this->ToSQL($wp->GetDBValue("2"), ccsInteger),true);
        $Where = $wp->opAND(false, $wp->Criterion[1], $wp->Criterion[2]);
        $this->SQL = "DELETE FROM liqdosis";
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Delete Method

} //End dosis_mantDataSource Class @121-FCB6E20C

//Initialize Page @1-CC713E8C
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

$FileName = "LiAdRe_mant.php";
$Redirect = "";
$TemplateFileName = "LiAdRe_mant.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-4F533793
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
$dosis_mant = new clsEditableGriddosis_mant();
$dosis_mant->Initialize();

// Events
include("./LiAdRe_mant_events.php");
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

//Show Page @1-1B3AAA57
$Cabecera->Show("Cabecera");
$dosis_mant->Show();
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
