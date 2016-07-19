<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

class clsGridPrecios_lista { //Precios_lista class @2-6E5FF2EF

//Variables @2-36ED41AE

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
    var $Sorter_par_Secuencia;
    var $Sorter_par_Descripcion;
    var $Navigator;
//End Variables

//Class_Initialize Event @2-56B0E9BA
    function clsGridPrecios_lista()
    {
        global $FileName;
        $this->ComponentName = "Precios_lista";
        $this->Visible = True;
        $this->IsAltRow = false;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid Precios_lista";
        $this->ds = new clsPrecios_listaDataSource();
        $this->PageSize = 10;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        $this->SorterName = CCGetParam("Precios_listaOrder", "");
        $this->SorterDirection = CCGetParam("Precios_listaDir", "");

        $this->par_secuencia = new clsControl(ccsLink, "par_secuencia", "par_secuencia", ccsInteger, "", CCGetRequestParam("par_secuencia", ccsGet));
        $this->par_Descripcion = new clsControl(ccsLabel, "par_Descripcion", "par_Descripcion", ccsText, "", CCGetRequestParam("par_Descripcion", ccsGet));
        $this->Alt_par_secuencia = new clsControl(ccsLink, "Alt_par_secuencia", "Alt_par_secuencia", ccsInteger, "", CCGetRequestParam("Alt_par_secuencia", ccsGet));
        $this->Alt_par_Descripcion = new clsControl(ccsLabel, "Alt_par_Descripcion", "Alt_par_Descripcion", ccsText, "", CCGetRequestParam("Alt_par_Descripcion", ccsGet));
        $this->genparametros_TotalRecords = new clsControl(ccsLabel, "genparametros_TotalRecords", "genparametros_TotalRecords", ccsText, "", CCGetRequestParam("genparametros_TotalRecords", ccsGet));
        $this->Sorter_par_Secuencia = new clsSorter($this->ComponentName, "Sorter_par_Secuencia", $FileName);
        $this->Sorter_par_Descripcion = new clsSorter($this->ComponentName, "Sorter_par_Descripcion", $FileName);
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple);
    }
//End Class_Initialize Event

//Initialize Method @2-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @2-61E02489
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["expr12"] = 'ITRLIS';

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
                    $this->par_secuencia->SetValue($this->ds->par_secuencia->GetValue());
                    $this->par_secuencia->Parameters = CCGetQueryString("All", Array("ccsForm"));
                    $this->par_secuencia->Parameters = CCAddParam($this->par_secuencia->Parameters, "par_Secuencia", $this->ds->f("par_Secuencia"));
                    $this->par_secuencia->Page = "";
                    $this->par_Descripcion->SetValue($this->ds->par_Descripcion->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->par_secuencia->Show();
                    $this->par_Descripcion->Show();
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                    $Tpl->parse("Row", true);
                }
                else
                {
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/AltRow";
                    $this->Alt_par_secuencia->SetValue($this->ds->Alt_par_secuencia->GetValue());
                    $this->Alt_par_secuencia->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
                    $this->Alt_par_secuencia->Parameters = CCAddParam($this->Alt_par_secuencia->Parameters, "par_Secuencia", $this->ds->f("par_Secuencia"));
                    $this->Alt_par_secuencia->Page = "";
                    $this->Alt_par_Descripcion->SetValue($this->ds->Alt_par_Descripcion->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->Alt_par_secuencia->Show();
                    $this->Alt_par_Descripcion->Show();
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
        $this->genparametros_TotalRecords->Show();
        $this->Sorter_par_Secuencia->Show();
        $this->Sorter_par_Descripcion->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @2-E8BFAD2F
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->par_secuencia->Errors->ToString();
        $errors .= $this->par_Descripcion->Errors->ToString();
        $errors .= $this->Alt_par_secuencia->Errors->ToString();
        $errors .= $this->Alt_par_Descripcion->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End Precios_lista Class @2-FCB6E20C

class clsPrecios_listaDataSource extends clsDBdatos {  //Precios_listaDataSource Class @2-7FDDD710

//DataSource Variables @2-092C8ED7
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $par_secuencia;
    var $par_Descripcion;
    var $Alt_par_secuencia;
    var $Alt_par_Descripcion;
//End DataSource Variables

//Class_Initialize Event @2-2EAA39A8
    function clsPrecios_listaDataSource()
    {
        $this->ErrorBlock = "Grid Precios_lista";
        $this->Initialize();
        $this->par_secuencia = new clsField("par_secuencia", ccsInteger, "");
        $this->par_Descripcion = new clsField("par_Descripcion", ccsText, "");
        $this->Alt_par_secuencia = new clsField("Alt_par_secuencia", ccsInteger, "");
        $this->Alt_par_Descripcion = new clsField("Alt_par_Descripcion", ccsText, "");

    }
//End Class_Initialize Event

//SetOrder Method @2-B6468C52
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_par_Secuencia" => array("par_Secuencia", ""), 
            "Sorter_par_Descripcion" => array("par_Descripcion", "")));
    }
//End SetOrder Method

//Prepare Method @2-3C064C09
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "expr12", ccsText, "", "", $this->Parameters["expr12"], "", true);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "par_Clave", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),true);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @2-DB75A4F3
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

//SetValues Method @2-D6E8AA62
    function SetValues()
    {
        $this->par_secuencia->SetDBValue(trim($this->f("par_Secuencia")));
        $this->par_Descripcion->SetDBValue($this->f("par_Descripcion"));
        $this->Alt_par_secuencia->SetDBValue(trim($this->f("par_Secuencia")));
        $this->Alt_par_Descripcion->SetDBValue($this->f("par_Descripcion"));
    }
//End SetValues Method

} //End Precios_listaDataSource Class @2-FCB6E20C

Class clsEditableGridPrecios_detalle { //Precios_detalle Class @50-C171C1CB

//Variables @50-16EA37AD

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
    var $Sorter_pre_CodItem;
    var $Sorter_act_Descripcion;
    var $Sorter_uni_Descripcion;
    var $Sorter_pre_PreUnitario;
    var $Navigator;
//End Variables

//Class_Initialize Event @50-231A8B2F
    function clsEditableGridPrecios_detalle()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "EditableGrid Precios_detalle/Error";
        $this->ComponentName = "Precios_detalle";
        $this->ds = new clsPrecios_detalleDataSource();

        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize) || $this->PageSize > 25)
            $this->PageSize = 25;
        else
            $this->PageSize = intval($this->PageSize);
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: EditableGrid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));

        $this->EmptyRows = 0;
        $this->UpdateAllowed = true;
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

        $this->SorterName = CCGetParam("Precios_detalleOrder", "");
        $this->SorterDirection = CCGetParam("Precios_detalleDir", "");

        $this->Sorter_pre_CodItem = new clsSorter($this->ComponentName, "Sorter_pre_CodItem", $FileName);
        $this->Sorter_act_Descripcion = new clsSorter($this->ComponentName, "Sorter_act_Descripcion", $FileName);
        $this->Sorter_uni_Descripcion = new clsSorter($this->ComponentName, "Sorter_uni_Descripcion", $FileName);
        $this->Sorter_pre_PreUnitario = new clsSorter($this->ComponentName, "Sorter_pre_PreUnitario", $FileName);
        $this->pre_CodItem = new clsControl(ccsTextBox, "pre_CodItem", "Pre Cod Item", ccsInteger, "");
        $this->act_Descripcion = new clsControl(ccsTextBox, "act_Descripcion", "Act Descripcion", ccsText, "");
        $this->uni_Descripcion = new clsControl(ccsTextBox, "uni_Descripcion", "Uni Descripcion", ccsText, "");
        $this->pre_PreUnitario = new clsControl(ccsTextBox, "pre_PreUnitario", "Pre Pre Unitario", ccsFloat, "");
        $this->CheckBox_Delete = new clsControl(ccsCheckBox, "CheckBox_Delete", "CheckBox_Delete", ccsBoolean, Array("Y", "N", ""));
        $this->CheckBox_Delete->CheckedValue = $this->CheckBox_Delete->GetParsedValue(true);
        $this->CheckBox_Delete->UncheckedValue = $this->CheckBox_Delete->GetParsedValue(false);
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpMoving);
        $this->Button_Submit = new clsButton("Button_Submit");
        $this->Cancel = new clsButton("Cancel");
    }
//End Class_Initialize Event

//Initialize Method @50-EF498E80
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);

        $this->ds->Parameters["urlparSecuencia"] = CCGetFromGet("parSecuencia", "");
    }
//End Initialize Method

//GetFormParameters Method @50-FF9F27FE
    function GetFormParameters()
    {
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->FormParameters["pre_CodItem"][$RowNumber] = CCGetFromPost("pre_CodItem_" . $RowNumber);
            $this->FormParameters["act_Descripcion"][$RowNumber] = CCGetFromPost("act_Descripcion_" . $RowNumber);
            $this->FormParameters["uni_Descripcion"][$RowNumber] = CCGetFromPost("uni_Descripcion_" . $RowNumber);
            $this->FormParameters["pre_PreUnitario"][$RowNumber] = CCGetFromPost("pre_PreUnitario_" . $RowNumber);
            $this->FormParameters["CheckBox_Delete"][$RowNumber] = CCGetFromPost("CheckBox_Delete_" . $RowNumber);
        }
    }
//End GetFormParameters Method

//Validate Method @50-DE894EA8
    function Validate()
    {
        $Validation = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");

        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->pre_CodItem->SetText($this->FormParameters["pre_CodItem"][$RowNumber], $RowNumber);
            $this->act_Descripcion->SetText($this->FormParameters["act_Descripcion"][$RowNumber], $RowNumber);
            $this->uni_Descripcion->SetText($this->FormParameters["uni_Descripcion"][$RowNumber], $RowNumber);
            $this->pre_PreUnitario->SetText($this->FormParameters["pre_PreUnitario"][$RowNumber], $RowNumber);
            $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$RowNumber], $RowNumber);
            if ($this->UpdatedRows >= $RowNumber) {
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

//ValidateRow Method @50-4F380607
    function ValidateRow($RowNumber)
    {
        $Validation = true;
        $Validation = ($this->pre_CodItem->Validate() && $Validation);
        $Validation = ($this->act_Descripcion->Validate() && $Validation);
        $Validation = ($this->uni_Descripcion->Validate() && $Validation);
        $Validation = ($this->pre_PreUnitario->Validate() && $Validation);
        $Validation = ($this->CheckBox_Delete->Validate() && $Validation);
        $errors = "";
        if(!$Validation)
        {
            $errors .= $this->pre_CodItem->Errors->ToString();
            $errors .= $this->act_Descripcion->Errors->ToString();
            $errors .= $this->uni_Descripcion->Errors->ToString();
            $errors .= $this->pre_PreUnitario->Errors->ToString();
            $errors .= $this->CheckBox_Delete->Errors->ToString();
            $this->pre_CodItem->Errors->Clear();
            $this->act_Descripcion->Errors->Clear();
            $this->uni_Descripcion->Errors->Clear();
            $this->pre_PreUnitario->Errors->Clear();
            $this->CheckBox_Delete->Errors->Clear();
        }
        $this->RowsErrors[$RowNumber] = $errors;
        return $Validation;
    }
//End ValidateRow Method

//CheckInsert Method @50-02525010
    function CheckInsert($RowNumber)
    {
        $filed = false;
        $filed = ($filed || strlen($this->FormParameters["pre_CodItem"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["act_Descripcion"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["uni_Descripcion"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["pre_PreUnitario"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["CheckBox_Delete"][$RowNumber]));
        return $filed;
    }
//End CheckInsert Method

//CheckErrors Method @50-242E5992
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @50-7B861278
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

//UpdateGrid Method @50-6C54BC66
    function UpdateGrid()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSubmit");
        if(!$this->Validate()) return;
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->pre_CodItem->SetText($this->FormParameters["pre_CodItem"][$RowNumber], $RowNumber);
            $this->act_Descripcion->SetText($this->FormParameters["act_Descripcion"][$RowNumber], $RowNumber);
            $this->uni_Descripcion->SetText($this->FormParameters["uni_Descripcion"][$RowNumber], $RowNumber);
            $this->pre_PreUnitario->SetText($this->FormParameters["pre_PreUnitario"][$RowNumber], $RowNumber);
            $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$RowNumber], $RowNumber);
            if ($this->UpdatedRows >= $RowNumber) {
                if($this->UpdateAllowed) $this->UpdateRow($RowNumber);
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

//UpdateRow Method @50-E8D78F2E
    function UpdateRow($RowNumber)
    {
        if(!$this->UpdateAllowed) return false;
        $this->ds->pre_CodItem->SetValue($this->pre_CodItem->GetValue());
        $this->ds->pre_PreUnitario->SetValue($this->pre_PreUnitario->GetValue());
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

//FormScript Method @50-09F24470
    function FormScript($TotalRows)
    {
        $script = "";
        $script .= "\n<script language=\"JavaScript\">\n<!--\n";
        $script .= "var Precios_detalleElements;\n";
        $script .= "var Precios_detalleEmptyRows = 0;\n";
        $script .= "var " . $this->ComponentName . "pre_CodItemID = 0;\n";
        $script .= "var " . $this->ComponentName . "act_DescripcionID = 1;\n";
        $script .= "var " . $this->ComponentName . "uni_DescripcionID = 2;\n";
        $script .= "var " . $this->ComponentName . "pre_PreUnitarioID = 3;\n";
        $script .= "var " . $this->ComponentName . "CheckBox_DeleteID = 4;\n";
        $script .= "\nfunction initPrecios_detalleElements() {\n";
        $script .= "\tvar ED = document.forms[\"Precios_detalle\"];\n";
        $script .= "\tPrecios_detalleElements = new Array (\n";
        for($i = 1; $i <= $TotalRows; $i++) {
            $script .= "\t\tnew Array(" . "ED.pre_CodItem_" . $i . ", " . "ED.act_Descripcion_" . $i . ", " . "ED.uni_Descripcion_" . $i . ", " . "ED.pre_PreUnitario_" . $i . ", " . "ED.CheckBox_Delete_" . $i . ")";
            if($i != $TotalRows) $script .= ",\n";
        }
        $script .= ");\n";
        $script .= "}\n";
        $script .= "\n//-->\n</script>";
        return $script;
    }
//End FormScript Method

//SetFormState Method @50-69E01441
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

//GetFormState Method @50-BF9CEBD0
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

//Show Method @50-3CFD5AE6
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
                    if(!$this->FormSubmitted && $is_next_record) {
                        $this->pre_CodItem->SetValue($this->ds->pre_CodItem->GetValue());
                        $this->act_Descripcion->SetValue($this->ds->act_Descripcion->GetValue());
                        $this->uni_Descripcion->SetValue($this->ds->uni_Descripcion->GetValue());
                        $this->pre_PreUnitario->SetValue($this->ds->pre_PreUnitario->GetValue());
                        $this->ValidateRow($RowNumber);
                    } else if (!$this->FormSubmitted){
                        $this->pre_CodItem->SetText("");
                        $this->act_Descripcion->SetText("");
                        $this->uni_Descripcion->SetText("");
                        $this->pre_PreUnitario->SetText("");
                        $this->CheckBox_Delete->SetText("");
                    } else {
                        $this->pre_CodItem->SetText($this->FormParameters["pre_CodItem"][$RowNumber], $RowNumber);
                        $this->act_Descripcion->SetText($this->FormParameters["act_Descripcion"][$RowNumber], $RowNumber);
                        $this->uni_Descripcion->SetText($this->FormParameters["uni_Descripcion"][$RowNumber], $RowNumber);
                        $this->pre_PreUnitario->SetText($this->FormParameters["pre_PreUnitario"][$RowNumber], $RowNumber);
                        $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$RowNumber], $RowNumber);
                    }
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->pre_CodItem->Show($RowNumber);
                    $this->act_Descripcion->Show($RowNumber);
                    $this->uni_Descripcion->Show($RowNumber);
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
        $this->Sorter_pre_CodItem->Show();
        $this->Sorter_act_Descripcion->Show();
        $this->Sorter_uni_Descripcion->Show();
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

} //End Precios_detalle Class @50-FCB6E20C

class clsPrecios_detalleDataSource extends clsDBdatos {  //Precios_detalleDataSource Class @50-5DBEA65C

//DataSource Variables @50-D0F6CA80
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $UpdateParameters;
    var $CountSQL;
    var $wp;
    var $AllParametersSet;


    // Datasource fields
    var $pre_CodItem;
    var $act_Descripcion;
    var $uni_Descripcion;
    var $pre_PreUnitario;
    var $CheckBox_Delete;
//End DataSource Variables

//Class_Initialize Event @50-06A2378C
    function clsPrecios_detalleDataSource()
    {
        $this->ErrorBlock = "EditableGrid Precios_detalle/Error";
        $this->Initialize();
        $this->pre_CodItem = new clsField("pre_CodItem", ccsInteger, "");
        $this->act_Descripcion = new clsField("act_Descripcion", ccsText, "");
        $this->uni_Descripcion = new clsField("uni_Descripcion", ccsText, "");
        $this->pre_PreUnitario = new clsField("pre_PreUnitario", ccsFloat, "");
        $this->CheckBox_Delete = new clsField("CheckBox_Delete", ccsBoolean, Array("true", "false", ""));

    }
//End Class_Initialize Event

//SetOrder Method @50-DD0A3404
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_pre_CodItem" => array("pre_CodItem", ""), 
            "Sorter_act_Descripcion" => array("act_Descripcion", ""), 
            "Sorter_uni_Descripcion" => array("uni_Descripcion", ""), 
            "Sorter_pre_PreUnitario" => array("pre_PreUnitario", "")));
    }
//End SetOrder Method

//Prepare Method @50-A7AF0F5F
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlparSecuencia", ccsInteger, "", "", $this->Parameters["urlparSecuencia"], "", true);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "pre_LisPrecios", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),true);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @50-AD58D55E
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM (conactivos LEFT JOIN invprecios ON conactivos.act_CodAuxiliar = invprecios.pre_CodItem) INNER JOIN genunmedida ON conactivos.act_UniMedida = genunmedida.uni_CodUnidad";
        $this->SQL = "SELECT act_CodAuxiliar, act_Descripcion, invprecios.*, uni_Descripcion  " .
        "FROM (conactivos LEFT JOIN invprecios ON conactivos.act_CodAuxiliar = invprecios.pre_CodItem) INNER JOIN genunmedida ON conactivos.act_UniMedida = genunmedida.uni_CodUnidad";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @50-7C12D5A7
    function SetValues()
    {
        $this->pre_CodItem->SetDBValue(trim($this->f("act_CodAuxiliar")));
        $this->act_Descripcion->SetDBValue($this->f("act_Descripcion"));
        $this->uni_Descripcion->SetDBValue($this->f("uni_Descripcion"));
        $this->pre_PreUnitario->SetDBValue(trim($this->f("pre_PreUnitario")));
    }
//End SetValues Method

//Update Method @50-C5D66E6E
    function Update()
    {
        $this->cp["lis"] = new clsSQLParameter("urlpar_Secuencia", ccsInteger, "", "", CCGetFromGet("par_Secuencia", ""), -1, false, $this->ErrorBlock);
        $this->cp["act"] = new clsSQLParameter("ctrlpre_CodItem", ccsInteger, "", "", $this->pre_CodItem->GetValue(), -1, false, $this->ErrorBlock);
        $this->cp["pre"] = new clsSQLParameter("ctrlpre_PreUnitario", ccsFloat, "", "", $this->pre_PreUnitario->GetValue(), 0, false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $this->SQL = "INSERT INTO invprecios VALUES(" . $this->SQLValue($this->cp["lis"]->GetDBValue(), ccsInteger) . "," . $this->SQLValue($this->cp["act"]->GetDBValue(), ccsInteger) . " , " . $this->SQLValue($this->cp["pre"]->GetDBValue(), ccsFloat) . ")" .
                            "ON DUPLICATE KEY UPDATE pre_PreUnitario = " . $this->cp["pre"]->GetDBValue();

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Update Method

} //End Precios_detalleDataSource Class @50-FCB6E20C

//Initialize Page @1-9222765C
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

$FileName = "InAdLp_mante.php";
$Redirect = "";
$TemplateFileName = "InAdLp_mante.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-52133522
$DBdatos = new clsDBdatos();

// Controls
$Precios_lista = new clsGridPrecios_lista();
$Precios_detalle = new clsEditableGridPrecios_detalle();
$Precios_lista->Initialize();
$Precios_detalle->Initialize();

// Events
include("./InAdLp_mante_events.php");
BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-6216BB2D
$Precios_detalle->Operation();
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

//Show Page @1-1D6BCC6F
$Precios_lista->Show();
$Precios_detalle->Show();
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
