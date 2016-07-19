<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @2-49BEB8FC
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation

Class clsRecordCoAdCu_qry { //CoAdCu_qry Class @4-4F813F86

//Variables @4-CB19EB75

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

    var $ds;
    var $EditMode;
    var $ValidatingControls;
    var $Controls;

    // Class variables
//End Variables

//Class_Initialize Event @4-A5E0B991
    function clsRecordCoAdCu_qry()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record CoAdCu_qry/Error";
        $this->ReadAllowed = false;
        $this->InsertAllowed = false;
        $this->UpdateAllowed = false;
        $this->DeleteAllowed = false;
        if($this->Visible)
        {
            $this->ComponentName = "CoAdCu_qry";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->s_Cue_CodCuenta = new clsControl(ccsTextBox, "s_Cue_CodCuenta", "s_Cue_CodCuenta", ccsText, "", CCGetRequestParam("s_Cue_CodCuenta", $Method));
            $this->s_Cue_Padre = new clsControl(ccsTextBox, "s_Cue_Padre", "s_Cue_Padre", ccsInteger, "", CCGetRequestParam("s_Cue_Padre", $Method));
            $this->s_cue_Descripcion = new clsControl(ccsTextBox, "s_cue_Descripcion", "s_cue_Descripcion", ccsText, "", CCGetRequestParam("s_cue_Descripcion", $Method));
            $this->s_cue_Clase = new clsControl(ccsTextBox, "s_cue_Clase", "s_cue_Clase", ccsInteger, "", CCGetRequestParam("s_cue_Clase", $Method));
            $this->s_cue_TipAuxiliar = new clsControl(ccsTextBox, "s_cue_TipAuxiliar", "s_cue_TipAuxiliar", ccsInteger, "", CCGetRequestParam("s_cue_TipAuxiliar", $Method));
            $this->Button_DoSearch = new clsButton("Button_DoSearch");
        }
    }
//End Class_Initialize Event

//Validate Method @4-5A771DE3
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_Cue_CodCuenta->Validate() && $Validation);
        $Validation = ($this->s_Cue_Padre->Validate() && $Validation);
        $Validation = ($this->s_cue_Descripcion->Validate() && $Validation);
        $Validation = ($this->s_cue_Clase->Validate() && $Validation);
        $Validation = ($this->s_cue_TipAuxiliar->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @4-B71A6B06
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_Cue_CodCuenta->Errors->Count());
        $errors = ($errors || $this->s_Cue_Padre->Errors->Count());
        $errors = ($errors || $this->s_cue_Descripcion->Errors->Count());
        $errors = ($errors || $this->s_cue_Clase->Errors->Count());
        $errors = ($errors || $this->s_cue_TipAuxiliar->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @4-95753EB4
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        $this->EditMode = false;
        if(!$this->FormSubmitted)
            return;

        if($this->FormSubmitted) {
            $this->PressedButton = "Button_DoSearch";
            if(strlen(CCGetParam("Button_DoSearch", ""))) {
                $this->PressedButton = "Button_DoSearch";
            }
        }
        $Redirect = "CoAdCu.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick")) {
                    $Redirect = "";
                } else {
                    $Redirect = "CoAdCu.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", Array("Button_DoSearch")));
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @4-F9E78B33
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
        if(!$this->FormSubmitted)
        {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->s_Cue_CodCuenta->Errors->ToString();
            $Error .= $this->s_Cue_Padre->Errors->ToString();
            $Error .= $this->s_cue_Descripcion->Errors->ToString();
            $Error .= $this->s_cue_Clase->Errors->ToString();
            $Error .= $this->s_cue_TipAuxiliar->Errors->ToString();
            $Error .= $this->Errors->ToString();
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

        $this->s_Cue_CodCuenta->Show();
        $this->s_Cue_Padre->Show();
        $this->s_cue_Descripcion->Show();
        $this->s_cue_Clase->Show();
        $this->s_cue_TipAuxiliar->Show();
        $this->Button_DoSearch->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End CoAdCu_qry Class @4-FCB6E20C

class clsGridCoAdCu_list { //CoAdCu_list class @3-0767ED03

//Variables @3-5B3F1E43

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
    var $Sorter_Cue_CodCuenta;
    var $Sorter_cue_Descripcion;
    var $Sorter_cue_Clase;
    var $Sorter_cue_Posicion;
    var $Sorter_cue_Estado;
    var $Sorter_Cue_Padre;
    var $Navigator;
//End Variables

//Class_Initialize Event @3-600E6F5D
    function clsGridCoAdCu_list()
    {
        global $FileName;
        $this->ComponentName = "CoAdCu_list";
        $this->Visible = True;
        $this->IsAltRow = false;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid CoAdCu_list";
        $this->ds = new clsCoAdCu_listDataSource();
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 10;
        else if ($this->PageSize > 100)
            $this->PageSize = 100;
        else
            $this->PageSize = intval($this->PageSize);
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        $this->SorterName = CCGetParam("CoAdCu_listOrder", "");
        $this->SorterDirection = CCGetParam("CoAdCu_listDir", "");

        $this->Cue_ID = new clsControl(ccsHidden, "Cue_ID", "Cue_ID", ccsInteger, "", CCGetRequestParam("Cue_ID", ccsGet));
        $this->Cue_CodCuenta = new clsControl(ccsLink, "Cue_CodCuenta", "Cue_CodCuenta", ccsText, "", CCGetRequestParam("Cue_CodCuenta", ccsGet));
        $this->cue_Descripcion = new clsControl(ccsLabel, "cue_Descripcion", "cue_Descripcion", ccsText, "", CCGetRequestParam("cue_Descripcion", ccsGet));
        $this->cue_Clase = new clsControl(ccsLabel, "cue_Clase", "cue_Clase", ccsText, "", CCGetRequestParam("cue_Clase", ccsGet));
        $this->cue_Posicion = new clsControl(ccsLabel, "cue_Posicion", "cue_Posicion", ccsInteger, "", CCGetRequestParam("cue_Posicion", ccsGet));
        $this->cue_Estado = new clsControl(ccsCheckBox, "cue_Estado", "cue_Estado", ccsInteger, "", CCGetRequestParam("cue_Estado", ccsGet));
        $this->cue_Estado->CheckedValue = $this->cue_Estado->GetParsedValue(1);
        $this->cue_Estado->UncheckedValue = $this->cue_Estado->GetParsedValue(0);
        $this->Cue_Padre = new clsControl(ccsLabel, "Cue_Padre", "Cue_Padre", ccsText, "", CCGetRequestParam("Cue_Padre", ccsGet));
        $this->Alt_Cue_ID = new clsControl(ccsHidden, "Alt_Cue_ID", "Alt_Cue_ID", ccsInteger, "", CCGetRequestParam("Alt_Cue_ID", ccsGet));
        $this->Alt_Cue_CodCuenta = new clsControl(ccsLink, "Alt_Cue_CodCuenta", "Alt_Cue_CodCuenta", ccsText, "", CCGetRequestParam("Alt_Cue_CodCuenta", ccsGet));
        $this->Alt_cue_Descripcion = new clsControl(ccsLabel, "Alt_cue_Descripcion", "Alt_cue_Descripcion", ccsText, "", CCGetRequestParam("Alt_cue_Descripcion", ccsGet));
        $this->Alt_cue_Clase = new clsControl(ccsLabel, "Alt_cue_Clase", "Alt_cue_Clase", ccsText, "", CCGetRequestParam("Alt_cue_Clase", ccsGet));
        $this->Alt_cue_Posicion = new clsControl(ccsLabel, "Alt_cue_Posicion", "Alt_cue_Posicion", ccsInteger, "", CCGetRequestParam("Alt_cue_Posicion", ccsGet));
        $this->Alt_cue_Estado = new clsControl(ccsCheckBox, "Alt_cue_Estado", "Alt_cue_Estado", ccsInteger, "", CCGetRequestParam("Alt_cue_Estado", ccsGet));
        $this->Alt_cue_Estado->CheckedValue = $this->Alt_cue_Estado->GetParsedValue(1);
        $this->Alt_cue_Estado->UncheckedValue = $this->Alt_cue_Estado->GetParsedValue(0);
        $this->Alt_Cue_Padre = new clsControl(ccsLabel, "Alt_Cue_Padre", "Alt_Cue_Padre", ccsText, "", CCGetRequestParam("Alt_Cue_Padre", ccsGet));
        $this->Sorter_Cue_CodCuenta = new clsSorter($this->ComponentName, "Sorter_Cue_CodCuenta", $FileName);
        $this->Sorter_cue_Descripcion = new clsSorter($this->ComponentName, "Sorter_cue_Descripcion", $FileName);
        $this->Sorter_cue_Clase = new clsSorter($this->ComponentName, "Sorter_cue_Clase", $FileName);
        $this->Sorter_cue_Posicion = new clsSorter($this->ComponentName, "Sorter_cue_Posicion", $FileName);
        $this->Sorter_cue_Estado = new clsSorter($this->ComponentName, "Sorter_cue_Estado", $FileName);
        $this->Sorter_Cue_Padre = new clsSorter($this->ComponentName, "Sorter_Cue_Padre", $FileName);
        $this->concuentas_Insert = new clsControl(ccsLink, "concuentas_Insert", "concuentas_Insert", ccsText, "", CCGetRequestParam("concuentas_Insert", ccsGet));
        $this->concuentas_Insert->Parameters = CCGetQueryString("QueryString", Array("Cue_ID", "ccsForm"));
        $this->concuentas_Insert->Page = "CoAdCu.php";
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple);
    }
//End Class_Initialize Event

//Initialize Method @3-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @3-DAFF3F36
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["expr121"] = CCUEN;
        $this->ds->Parameters["urls_Cue_CodCuenta"] = CCGetFromGet("s_Cue_CodCuenta", "");
        $this->ds->Parameters["urls_Cue_Padre"] = CCGetFromGet("s_Cue_Padre", "");
        $this->ds->Parameters["urls_cue_Descripcion"] = CCGetFromGet("s_cue_Descripcion", "");
        $this->ds->Parameters["urls_cue_Clase"] = CCGetFromGet("s_cue_Clase", "");
        $this->ds->Parameters["urls_cue_TipAuxiliar"] = CCGetFromGet("s_cue_TipAuxiliar", "");

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
                    $this->Cue_ID->SetValue($this->ds->Cue_ID->GetValue());
                    $this->Cue_CodCuenta->SetValue($this->ds->Cue_CodCuenta->GetValue());
                    $this->Cue_CodCuenta->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
                    $this->Cue_CodCuenta->Parameters = CCAddParam($this->Cue_CodCuenta->Parameters, "Cue_ID", $this->ds->f("Cue_ID"));
                    $this->Cue_CodCuenta->Page = "CoAdCu.php";
                    $this->cue_Descripcion->SetValue($this->ds->cue_Descripcion->GetValue());
                    $this->cue_Clase->SetValue($this->ds->cue_Clase->GetValue());
                    $this->cue_Posicion->SetValue($this->ds->cue_Posicion->GetValue());
                    $this->cue_Estado->SetValue($this->ds->cue_Estado->GetValue());
                    $this->Cue_Padre->SetValue($this->ds->Cue_Padre->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->Cue_ID->Show();
                    $this->Cue_CodCuenta->Show();
                    $this->cue_Descripcion->Show();
                    $this->cue_Clase->Show();
                    $this->cue_Posicion->Show();
                    $this->cue_Estado->Show();
                    $this->Cue_Padre->Show();
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                    $Tpl->parse("Row", true);
                }
                else
                {
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/AltRow";
                    $this->Alt_Cue_ID->SetValue($this->ds->Alt_Cue_ID->GetValue());
                    $this->Alt_Cue_CodCuenta->SetValue($this->ds->Alt_Cue_CodCuenta->GetValue());
                    $this->Alt_Cue_CodCuenta->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
                    $this->Alt_Cue_CodCuenta->Parameters = CCAddParam($this->Alt_Cue_CodCuenta->Parameters, "Cue_ID", $this->ds->f("Cue_ID"));
                    $this->Alt_Cue_CodCuenta->Page = "CoAdCu.php";
                    $this->Alt_cue_Descripcion->SetValue($this->ds->Alt_cue_Descripcion->GetValue());
                    $this->Alt_cue_Clase->SetValue($this->ds->Alt_cue_Clase->GetValue());
                    $this->Alt_cue_Posicion->SetValue($this->ds->Alt_cue_Posicion->GetValue());
                    $this->Alt_cue_Estado->SetValue($this->ds->Alt_cue_Estado->GetValue());
                    $this->Alt_Cue_Padre->SetValue($this->ds->Alt_Cue_Padre->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->Alt_Cue_ID->Show();
                    $this->Alt_Cue_CodCuenta->Show();
                    $this->Alt_cue_Descripcion->Show();
                    $this->Alt_cue_Clase->Show();
                    $this->Alt_cue_Posicion->Show();
                    $this->Alt_cue_Estado->Show();
                    $this->Alt_Cue_Padre->Show();
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
        $this->Sorter_Cue_CodCuenta->Show();
        $this->Sorter_cue_Descripcion->Show();
        $this->Sorter_cue_Clase->Show();
        $this->Sorter_cue_Posicion->Show();
        $this->Sorter_cue_Estado->Show();
        $this->Sorter_Cue_Padre->Show();
        $this->concuentas_Insert->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @3-73B78B56
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->Cue_ID->Errors->ToString();
        $errors .= $this->Cue_CodCuenta->Errors->ToString();
        $errors .= $this->cue_Descripcion->Errors->ToString();
        $errors .= $this->cue_Clase->Errors->ToString();
        $errors .= $this->cue_Posicion->Errors->ToString();
        $errors .= $this->cue_Estado->Errors->ToString();
        $errors .= $this->Cue_Padre->Errors->ToString();
        $errors .= $this->Alt_Cue_ID->Errors->ToString();
        $errors .= $this->Alt_Cue_CodCuenta->Errors->ToString();
        $errors .= $this->Alt_cue_Descripcion->Errors->ToString();
        $errors .= $this->Alt_cue_Clase->Errors->ToString();
        $errors .= $this->Alt_cue_Posicion->Errors->ToString();
        $errors .= $this->Alt_cue_Estado->Errors->ToString();
        $errors .= $this->Alt_Cue_Padre->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End CoAdCu_list Class @3-FCB6E20C

class clsCoAdCu_listDataSource extends clsDBdatos {  //CoAdCu_listDataSource Class @3-731964BE

//DataSource Variables @3-1B5B6473
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $Cue_ID;
    var $Cue_CodCuenta;
    var $cue_Descripcion;
    var $cue_Clase;
    var $cue_Posicion;
    var $cue_Estado;
    var $Cue_Padre;
    var $Alt_Cue_ID;
    var $Alt_Cue_CodCuenta;
    var $Alt_cue_Descripcion;
    var $Alt_cue_Clase;
    var $Alt_cue_Posicion;
    var $Alt_cue_Estado;
    var $Alt_Cue_Padre;
//End DataSource Variables

//Class_Initialize Event @3-8BCAEF93
    function clsCoAdCu_listDataSource()
    {
        $this->ErrorBlock = "Grid CoAdCu_list";
        $this->Initialize();
        $this->Cue_ID = new clsField("Cue_ID", ccsInteger, "");
        $this->Cue_CodCuenta = new clsField("Cue_CodCuenta", ccsText, "");
        $this->cue_Descripcion = new clsField("cue_Descripcion", ccsText, "");
        $this->cue_Clase = new clsField("cue_Clase", ccsText, "");
        $this->cue_Posicion = new clsField("cue_Posicion", ccsInteger, "");
        $this->cue_Estado = new clsField("cue_Estado", ccsInteger, "");
        $this->Cue_Padre = new clsField("Cue_Padre", ccsText, "");
        $this->Alt_Cue_ID = new clsField("Alt_Cue_ID", ccsInteger, "");
        $this->Alt_Cue_CodCuenta = new clsField("Alt_Cue_CodCuenta", ccsText, "");
        $this->Alt_cue_Descripcion = new clsField("Alt_cue_Descripcion", ccsText, "");
        $this->Alt_cue_Clase = new clsField("Alt_cue_Clase", ccsText, "");
        $this->Alt_cue_Posicion = new clsField("Alt_cue_Posicion", ccsInteger, "");
        $this->Alt_cue_Estado = new clsField("Alt_cue_Estado", ccsInteger, "");
        $this->Alt_Cue_Padre = new clsField("Alt_Cue_Padre", ccsText, "");

    }
//End Class_Initialize Event

//SetOrder Method @3-9B678EE8
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "concuentas.Cue_CodCuenta";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_Cue_CodCuenta" => array("Cue_CodCuenta", ""), 
            "Sorter_cue_Descripcion" => array("cue_Descripcion", ""), 
            "Sorter_cue_Clase" => array("cue_Clase", ""), 
            "Sorter_cue_Posicion" => array("cue_Posicion", ""), 
            "Sorter_cue_Estado" => array("cue_Estado", ""), 
            "Sorter_Cue_Padre" => array("Cue_Padre", "")));
    }
//End SetOrder Method

//Prepare Method @3-321E6125
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "expr121", ccsText, "", "", $this->Parameters["expr121"], "", false);
        $this->wp->AddParameter("2", "urls_Cue_CodCuenta", ccsText, "", "", $this->Parameters["urls_Cue_CodCuenta"], "", false);
        $this->wp->AddParameter("3", "urls_Cue_Padre", ccsInteger, "", "", $this->Parameters["urls_Cue_Padre"], "", false);
        $this->wp->AddParameter("4", "urls_cue_Descripcion", ccsText, "", "", $this->Parameters["urls_cue_Descripcion"], "", false);
        $this->wp->AddParameter("5", "urls_cue_Clase", ccsInteger, "", "", $this->Parameters["urls_cue_Clase"], "", false);
        $this->wp->AddParameter("6", "urls_cue_TipAuxiliar", ccsInteger, "", "", $this->Parameters["urls_cue_TipAuxiliar"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "cat_Clave", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opBeginsWith, "concuentas.Cue_CodCuenta", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsText),false);
        $this->wp->Criterion[3] = $this->wp->Operation(opEqual, "concuentas.Cue_Padre", $this->wp->GetDBValue("3"), $this->ToSQL($this->wp->GetDBValue("3"), ccsInteger),false);
        $this->wp->Criterion[4] = $this->wp->Operation(opBeginsWith, "concuentas.cue_Descripcion", $this->wp->GetDBValue("4"), $this->ToSQL($this->wp->GetDBValue("4"), ccsText),false);
        $this->wp->Criterion[5] = $this->wp->Operation(opEqual, "concuentas.cue_Clase", $this->wp->GetDBValue("5"), $this->ToSQL($this->wp->GetDBValue("5"), ccsInteger),false);
        $this->wp->Criterion[6] = $this->wp->Operation(opEqual, "concuentas.cue_TipAuxiliar", $this->wp->GetDBValue("6"), $this->ToSQL($this->wp->GetDBValue("6"), ccsInteger),false);
        $this->Where = $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->Criterion[1], $this->wp->Criterion[2]), $this->wp->Criterion[3]), $this->wp->Criterion[4]), $this->wp->Criterion[5]), $this->wp->Criterion[6]);
    }
//End Prepare Method

//Open Method @3-D6561B0E
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM ((genparametros RIGHT JOIN concuentas ON concuentas.cue_Clase = genparametros.par_Secuencia) INNER JOIN gencatparam ON gencatparam.cat_Codigo = genparametros.par_Categoria) LEFT JOIN concuentas `pad` ON concuentas.Cue_Padre = `pad`.Cue_ID";
        $this->SQL = "SELECT concuentas.*, `pad`.cue_Descripcion AS pad_cue_Descripcion, par_Descripcion, cat_Clave  " .
        "FROM ((genparametros RIGHT JOIN concuentas ON concuentas.cue_Clase = genparametros.par_Secuencia) INNER JOIN gencatparam ON gencatparam.cat_Codigo = genparametros.par_Categoria) LEFT JOIN concuentas `pad` ON concuentas.Cue_Padre = `pad`.Cue_ID";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @3-55AA453C
    function SetValues()
    {
        $this->Cue_ID->SetDBValue(trim($this->f("Cue_ID")));
        $this->Cue_CodCuenta->SetDBValue($this->f("Cue_CodCuenta"));
        $this->cue_Descripcion->SetDBValue($this->f("cue_Descripcion"));
        $this->cue_Clase->SetDBValue($this->f("par_Descripcion"));
        $this->cue_Posicion->SetDBValue(trim($this->f("cue_Posicion")));
        $this->cue_Estado->SetDBValue(trim($this->f("cue_Estado")));
        $this->Cue_Padre->SetDBValue($this->f("pad_cue_Descripcion"));
        $this->Alt_Cue_ID->SetDBValue(trim($this->f("Cue_ID")));
        $this->Alt_Cue_CodCuenta->SetDBValue($this->f("Cue_CodCuenta"));
        $this->Alt_cue_Descripcion->SetDBValue($this->f("cue_Descripcion"));
        $this->Alt_cue_Clase->SetDBValue($this->f("par_Descripcion"));
        $this->Alt_cue_Posicion->SetDBValue(trim($this->f("cue_Posicion")));
        $this->Alt_cue_Estado->SetDBValue(trim($this->f("cue_Estado")));
        $this->Alt_Cue_Padre->SetDBValue($this->f("pad_cue_Descripcion"));
    }
//End SetValues Method

} //End CoAdCu_listDataSource Class @3-FCB6E20C

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

//Class_Initialize Event @41-2E642F79
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
            $this->Cue_CodCuenta = new clsControl(ccsTextBox, "Cue_CodCuenta", "Cue Cod Cuenta", ccsText, "", CCGetRequestParam("Cue_CodCuenta", $Method));
            $this->Cue_CodCuenta->Required = true;
            $this->hdCueId = new clsControl(ccsHidden, "hdCueId", "hdCueId", ccsText, "", CCGetRequestParam("hdCueId", $Method));
            $this->Cue_Padre = new clsControl(ccsListBox, "Cue_Padre", "Cuenta Padre", ccsInteger, "", CCGetRequestParam("Cue_Padre", $Method));
            $this->Cue_Padre->DSType = dsSQL;
            list($this->Cue_Padre->BoundColumn, $this->Cue_Padre->TextColumn, $this->Cue_Padre->DBFormat) = array("cue_id", "descr", "");
            $this->Cue_Padre->ds = new clsDBdatos();
            $this->Cue_Padre->ds->SQL = "select cue_id, concat(cue_CodCuenta, \" -  \", cue_descripcion) as descr " .
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
            $this->cue_TipAuxiliar->Required = true;
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
            if(!$this->FormSubmitted) {
                if(!is_array($this->Cue_Padre->Value) && !strlen($this->Cue_Padre->Value) && $this->Cue_Padre->Value !== false)
                $this->Cue_Padre->SetText(0);
                if(!is_array($this->cue_Posicion->Value) && !strlen($this->cue_Posicion->Value) && $this->cue_Posicion->Value !== false)
                $this->cue_Posicion->SetText(0);
                if(!is_array($this->cue_Estado->Value) && !strlen($this->cue_Estado->Value) && $this->cue_Estado->Value !== false)
                $this->cue_Estado->SetText(1);
                if(!is_array($this->cue_ReqAuxiliar->Value) && !strlen($this->cue_ReqAuxiliar->Value) && $this->cue_ReqAuxiliar->Value !== false)
                $this->cue_ReqAuxiliar->SetText(0);
                if(!is_array($this->cue_ReqRefOperat->Value) && !strlen($this->cue_ReqRefOperat->Value) && $this->cue_ReqRefOperat->Value !== false)
                $this->cue_ReqRefOperat->SetText(0);
                if(!is_array($this->cue_TipMovim->Value) && !strlen($this->cue_TipMovim->Value) && $this->cue_TipMovim->Value !== false)
                $this->cue_TipMovim->SetText(0);
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

//Validate Method @41-A1FEA0A0
    function Validate()
    {
        $Validation = true;
        $Where = "";
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

//CheckErrors Method @41-54F210C1
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->Cue_CodCuenta->Errors->Count());
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

//Operation Method @41-26F369E9
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
        $Redirect = "CoAdCu.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
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

//InsertRow Method @41-A2F11AD0
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert");
        if(!$this->InsertAllowed) return false;
        $this->ds->Cue_CodCuenta->SetValue($this->Cue_CodCuenta->GetValue());
        $this->ds->hdCueId->SetValue($this->hdCueId->GetValue());
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

//UpdateRow Method @41-94793F6D
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate");
        if(!$this->UpdateAllowed) return false;
        $this->ds->Cue_CodCuenta->SetValue($this->Cue_CodCuenta->GetValue());
        $this->ds->hdCueId->SetValue($this->hdCueId->GetValue());
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

//Show Method @41-F03FA50A
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
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->Cue_CodCuenta->Errors->ToString();
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
        $this->Cue_CodCuenta->Show();
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
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

} //End CoAdCu_mant Class @41-FCB6E20C

class clsCoAdCu_mantDataSource extends clsDBdatos {  //CoAdCu_mantDataSource Class @41-70856AB7

//DataSource Variables @41-0AE24E87
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $InsertParameters;
    var $UpdateParameters;
    var $DeleteParameters;
    var $wp;
    var $AllParametersSet;


    // Datasource fields
    var $Cue_CodCuenta;
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

//Class_Initialize Event @41-304FC7DF
    function clsCoAdCu_mantDataSource()
    {
        $this->ErrorBlock = "Record CoAdCu_mant/Error";
        $this->Initialize();
        $this->Cue_CodCuenta = new clsField("Cue_CodCuenta", ccsText, "");
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

//SetValues Method @41-81324C6A
    function SetValues()
    {
        $this->Cue_CodCuenta->SetDBValue($this->f("Cue_CodCuenta"));
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

//Insert Method @41-E0F55A30
    function Insert()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO concuentas ("
             . "Cue_CodCuenta, "
             . "Cue_ID, "
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
             . $this->ToSQL($this->Cue_CodCuenta->GetDBValue(), $this->Cue_CodCuenta->DataType) . ", "
             . $this->ToSQL($this->hdCueId->GetDBValue(), $this->hdCueId->DataType) . ", "
             . $this->ToSQL($this->Cue_Padre->GetDBValue(), $this->Cue_Padre->DataType) . ", "
             . $this->ToSQL($this->cue_Descripcion->GetDBValue(), $this->cue_Descripcion->DataType) . ", "
             . $this->ToSQL($this->cue_Clase->GetDBValue(), $this->cue_Clase->DataType) . ", "
             . $this->ToSQL($this->cue_Posicion->GetDBValue(), $this->cue_Posicion->DataType) . ", "
             . $this->ToSQL($this->cue_Estado->GetDBValue(), $this->cue_Estado->DataType) . ", "
             . $this->ToSQL($this->cue_ReqAuxiliar->GetDBValue(), $this->cue_ReqAuxiliar->DataType) . ", "
             . $this->ToSQL($this->cue_TipAuxiliar->GetDBValue(), $this->cue_TipAuxiliar->DataType) . ", "
             . $this->ToSQL($this->cue_ReqRefOperat->GetDBValue(), $this->cue_ReqRefOperat->DataType) . ", "
             . $this->ToSQL($this->cue_TipMovim->GetDBValue(), $this->cue_TipMovim->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Insert Method

//Update Method @41-E0B924F3
    function Update()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $this->SQL = "UPDATE concuentas SET "
             . "Cue_CodCuenta=" . $this->ToSQL($this->Cue_CodCuenta->GetDBValue(), $this->Cue_CodCuenta->DataType) . ", "
             . "Cue_ID=" . $this->ToSQL($this->hdCueId->GetDBValue(), $this->hdCueId->DataType) . ", "
             . "Cue_Padre=" . $this->ToSQL($this->Cue_Padre->GetDBValue(), $this->Cue_Padre->DataType) . ", "
             . "cue_Descripcion=" . $this->ToSQL($this->cue_Descripcion->GetDBValue(), $this->cue_Descripcion->DataType) . ", "
             . "cue_Clase=" . $this->ToSQL($this->cue_Clase->GetDBValue(), $this->cue_Clase->DataType) . ", "
             . "cue_Posicion=" . $this->ToSQL($this->cue_Posicion->GetDBValue(), $this->cue_Posicion->DataType) . ", "
             . "cue_Estado=" . $this->ToSQL($this->cue_Estado->GetDBValue(), $this->cue_Estado->DataType) . ", "
             . "cue_ReqAuxiliar=" . $this->ToSQL($this->cue_ReqAuxiliar->GetDBValue(), $this->cue_ReqAuxiliar->DataType) . ", "
             . "cue_TipAuxiliar=" . $this->ToSQL($this->cue_TipAuxiliar->GetDBValue(), $this->cue_TipAuxiliar->DataType) . ", "
             . "cue_ReqRefOperat=" . $this->ToSQL($this->cue_ReqRefOperat->GetDBValue(), $this->cue_ReqRefOperat->DataType) . ", "
             . "cue_TipMovim=" . $this->ToSQL($this->cue_TipMovim->GetDBValue(), $this->cue_TipMovim->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Update Method

//Delete Method @41-DEBFD81F
    function Delete()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $this->SQL = "DELETE FROM concuentas";
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Delete Method

} //End CoAdCu_mantDataSource Class @41-FCB6E20C

//Initialize Page @1-6F9BBE47
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

$FileName = "CoAdCu.php";
$Redirect = "";
$TemplateFileName = "CoAdCu.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-E66EBB06
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
$CoAdCu_qry = new clsRecordCoAdCu_qry();
$CoAdCu_list = new clsGridCoAdCu_list();
$CoAdCu_mant = new clsRecordCoAdCu_mant();
$CoAdCu_list->Initialize();
$CoAdCu_mant->Initialize();

// Events
include("./CoAdCu_events.php");
BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-7B87E818
$Cabecera->Operations();
$CoAdCu_qry->Operation();
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

//Show Page @1-1FC2A263
$Cabecera->Show("Cabecera");
$CoAdCu_qry->Show();
$CoAdCu_list->Show();
$CoAdCu_mant->Show();
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
