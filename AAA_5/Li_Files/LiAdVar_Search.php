<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

Class clsRecordgenvarproceso_genparametrSearch { //genvarproceso_genparametrSearch Class @18-0FB43CDD

//Variables @18-CB19EB75

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

//Class_Initialize Event @18-A945C500
    function clsRecordgenvarproceso_genparametrSearch()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record genvarproceso_genparametrSearch/Error";
        $this->ReadAllowed = false;
        $this->InsertAllowed = false;
        $this->UpdateAllowed = false;
        $this->DeleteAllowed = false;
        if($this->Visible)
        {
            $this->ComponentName = "genvarproceso_genparametrSearch";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->s_keyword = new clsControl(ccsTextBox, "s_keyword", "s_keyword", ccsText, "", CCGetRequestParam("s_keyword", $Method));
            $this->ClearParameters = new clsControl(ccsLink, "ClearParameters", "ClearParameters", ccsText, "", CCGetRequestParam("ClearParameters", $Method));
            $this->ClearParameters->Parameters = CCGetQueryString("QueryString", Array("s_keyword", "ccsForm"));
            $this->ClearParameters->Page = "LiAdVar_Search.php";
            $this->Button_DoSearch = new clsButton("Button_DoSearch");
        }
    }
//End Class_Initialize Event

//Validate Method @18-F230E30A
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_keyword->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @18-B0E04A52
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_keyword->Errors->Count());
        $errors = ($errors || $this->ClearParameters->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @18-870EF8E5
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
        $Redirect = "LiAdVar_Search.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick")) {
                    $Redirect = "";
                } else {
                    $Redirect = "LiAdVar_Search.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", Array("Button_DoSearch")));
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @18-93421E23
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
            $Error .= $this->s_keyword->Errors->ToString();
            $Error .= $this->ClearParameters->Errors->ToString();
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

        $this->s_keyword->Show();
        $this->ClearParameters->Show();
        $this->Button_DoSearch->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End genvarproceso_genparametrSearch Class @18-FCB6E20C

class clsGridgenvarproceso_genparametr { //genvarproceso_genparametr class @2-9F1F1D48

//Variables @2-92E97AA8

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
    var $Sorter_var_ID;
    var $Sorter_var_TipProceso;
    var $Sorter_var_Nombre;
    var $Sorter_var_Descripcion;
    var $Sorter_var_Clase;
    var $Sorter_var_Orden;
    var $Sorter_var_Estado;
    var $Sorter_par_Descripcion;
    var $Navigator;
//End Variables

//Class_Initialize Event @2-3F90C2C2
    function clsGridgenvarproceso_genparametr()
    {
        global $FileName;
        $this->ComponentName = "genvarproceso_genparametr";
        $this->Visible = True;
        $this->IsAltRow = false;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid genvarproceso_genparametr";
        $this->ds = new clsgenvarproceso_genparametrDataSource();
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
        $this->SorterName = CCGetParam("genvarproceso_genparametrOrder", "");
        $this->SorterDirection = CCGetParam("genvarproceso_genparametrDir", "");

        $this->var_ID = new clsControl(ccsLabel, "var_ID", "var_ID", ccsInteger, "", CCGetRequestParam("var_ID", ccsGet));
        $this->var_TipProceso = new clsControl(ccsLabel, "var_TipProceso", "var_TipProceso", ccsText, "", CCGetRequestParam("var_TipProceso", ccsGet));
        $this->var_Nombre = new clsControl(ccsLabel, "var_Nombre", "var_Nombre", ccsText, "", CCGetRequestParam("var_Nombre", ccsGet));
        $this->var_Descripcion = new clsControl(ccsLabel, "var_Descripcion", "var_Descripcion", ccsText, "", CCGetRequestParam("var_Descripcion", ccsGet));
        $this->var_Clase = new clsControl(ccsLabel, "var_Clase", "var_Clase", ccsText, "", CCGetRequestParam("var_Clase", ccsGet));
        $this->var_Orden = new clsControl(ccsLabel, "var_Orden", "var_Orden", ccsInteger, "", CCGetRequestParam("var_Orden", ccsGet));
        $this->var_Estado = new clsControl(ccsLabel, "var_Estado", "var_Estado", ccsInteger, "", CCGetRequestParam("var_Estado", ccsGet));
        $this->par_Descripcion = new clsControl(ccsLabel, "par_Descripcion", "par_Descripcion", ccsText, "", CCGetRequestParam("par_Descripcion", ccsGet));
        $this->Alt_var_ID = new clsControl(ccsLabel, "Alt_var_ID", "Alt_var_ID", ccsInteger, "", CCGetRequestParam("Alt_var_ID", ccsGet));
        $this->Alt_var_TipProceso = new clsControl(ccsLabel, "Alt_var_TipProceso", "Alt_var_TipProceso", ccsText, "", CCGetRequestParam("Alt_var_TipProceso", ccsGet));
        $this->Alt_var_Nombre = new clsControl(ccsLabel, "Alt_var_Nombre", "Alt_var_Nombre", ccsText, "", CCGetRequestParam("Alt_var_Nombre", ccsGet));
        $this->Alt_var_Descripcion = new clsControl(ccsLabel, "Alt_var_Descripcion", "Alt_var_Descripcion", ccsText, "", CCGetRequestParam("Alt_var_Descripcion", ccsGet));
        $this->Alt_var_Clase = new clsControl(ccsLabel, "Alt_var_Clase", "Alt_var_Clase", ccsText, "", CCGetRequestParam("Alt_var_Clase", ccsGet));
        $this->Alt_var_Orden = new clsControl(ccsLabel, "Alt_var_Orden", "Alt_var_Orden", ccsInteger, "", CCGetRequestParam("Alt_var_Orden", ccsGet));
        $this->Alt_var_Estado = new clsControl(ccsLabel, "Alt_var_Estado", "Alt_var_Estado", ccsInteger, "", CCGetRequestParam("Alt_var_Estado", ccsGet));
        $this->Alt_par_Descripcion = new clsControl(ccsLabel, "Alt_par_Descripcion", "Alt_par_Descripcion", ccsText, "", CCGetRequestParam("Alt_par_Descripcion", ccsGet));
        $this->genvarproceso_genparametr_TotalRecords = new clsControl(ccsLabel, "genvarproceso_genparametr_TotalRecords", "genvarproceso_genparametr_TotalRecords", ccsText, "", CCGetRequestParam("genvarproceso_genparametr_TotalRecords", ccsGet));
        $this->Sorter_var_ID = new clsSorter($this->ComponentName, "Sorter_var_ID", $FileName);
        $this->Sorter_var_TipProceso = new clsSorter($this->ComponentName, "Sorter_var_TipProceso", $FileName);
        $this->Sorter_var_Nombre = new clsSorter($this->ComponentName, "Sorter_var_Nombre", $FileName);
        $this->Sorter_var_Descripcion = new clsSorter($this->ComponentName, "Sorter_var_Descripcion", $FileName);
        $this->Sorter_var_Clase = new clsSorter($this->ComponentName, "Sorter_var_Clase", $FileName);
        $this->Sorter_var_Orden = new clsSorter($this->ComponentName, "Sorter_var_Orden", $FileName);
        $this->Sorter_var_Estado = new clsSorter($this->ComponentName, "Sorter_var_Estado", $FileName);
        $this->Sorter_par_Descripcion = new clsSorter($this->ComponentName, "Sorter_par_Descripcion", $FileName);
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpCentered);
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

//Show Method @2-ECC22ED2
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["urls_keyword"] = CCGetFromGet("s_keyword", "");

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
                    $this->var_ID->SetValue($this->ds->var_ID->GetValue());
                    $this->var_TipProceso->SetValue($this->ds->var_TipProceso->GetValue());
                    $this->var_Nombre->SetValue($this->ds->var_Nombre->GetValue());
                    $this->var_Descripcion->SetValue($this->ds->var_Descripcion->GetValue());
                    $this->var_Clase->SetValue($this->ds->var_Clase->GetValue());
                    $this->var_Orden->SetValue($this->ds->var_Orden->GetValue());
                    $this->var_Estado->SetValue($this->ds->var_Estado->GetValue());
                    $this->par_Descripcion->SetValue($this->ds->par_Descripcion->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->var_ID->Show();
                    $this->var_TipProceso->Show();
                    $this->var_Nombre->Show();
                    $this->var_Descripcion->Show();
                    $this->var_Clase->Show();
                    $this->var_Orden->Show();
                    $this->var_Estado->Show();
                    $this->par_Descripcion->Show();
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                    $Tpl->parse("Row", true);
                }
                else
                {
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/AltRow";
                    $this->Alt_var_ID->SetValue($this->ds->Alt_var_ID->GetValue());
                    $this->Alt_var_TipProceso->SetValue($this->ds->Alt_var_TipProceso->GetValue());
                    $this->Alt_var_Nombre->SetValue($this->ds->Alt_var_Nombre->GetValue());
                    $this->Alt_var_Descripcion->SetValue($this->ds->Alt_var_Descripcion->GetValue());
                    $this->Alt_var_Clase->SetValue($this->ds->Alt_var_Clase->GetValue());
                    $this->Alt_var_Orden->SetValue($this->ds->Alt_var_Orden->GetValue());
                    $this->Alt_var_Estado->SetValue($this->ds->Alt_var_Estado->GetValue());
                    $this->Alt_par_Descripcion->SetValue($this->ds->Alt_par_Descripcion->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->Alt_var_ID->Show();
                    $this->Alt_var_TipProceso->Show();
                    $this->Alt_var_Nombre->Show();
                    $this->Alt_var_Descripcion->Show();
                    $this->Alt_var_Clase->Show();
                    $this->Alt_var_Orden->Show();
                    $this->Alt_var_Estado->Show();
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
        $this->genvarproceso_genparametr_TotalRecords->Show();
        $this->Sorter_var_ID->Show();
        $this->Sorter_var_TipProceso->Show();
        $this->Sorter_var_Nombre->Show();
        $this->Sorter_var_Descripcion->Show();
        $this->Sorter_var_Clase->Show();
        $this->Sorter_var_Orden->Show();
        $this->Sorter_var_Estado->Show();
        $this->Sorter_par_Descripcion->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @2-D24545B8
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->var_ID->Errors->ToString();
        $errors .= $this->var_TipProceso->Errors->ToString();
        $errors .= $this->var_Nombre->Errors->ToString();
        $errors .= $this->var_Descripcion->Errors->ToString();
        $errors .= $this->var_Clase->Errors->ToString();
        $errors .= $this->var_Orden->Errors->ToString();
        $errors .= $this->var_Estado->Errors->ToString();
        $errors .= $this->par_Descripcion->Errors->ToString();
        $errors .= $this->Alt_var_ID->Errors->ToString();
        $errors .= $this->Alt_var_TipProceso->Errors->ToString();
        $errors .= $this->Alt_var_Nombre->Errors->ToString();
        $errors .= $this->Alt_var_Descripcion->Errors->ToString();
        $errors .= $this->Alt_var_Clase->Errors->ToString();
        $errors .= $this->Alt_var_Orden->Errors->ToString();
        $errors .= $this->Alt_var_Estado->Errors->ToString();
        $errors .= $this->Alt_par_Descripcion->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End genvarproceso_genparametr Class @2-FCB6E20C

class clsgenvarproceso_genparametrDataSource extends clsDBdatos {  //genvarproceso_genparametrDataSource Class @2-767F05A9

//DataSource Variables @2-4756C0DE
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $var_ID;
    var $var_TipProceso;
    var $var_Nombre;
    var $var_Descripcion;
    var $var_Clase;
    var $var_Orden;
    var $var_Estado;
    var $par_Descripcion;
    var $Alt_var_ID;
    var $Alt_var_TipProceso;
    var $Alt_var_Nombre;
    var $Alt_var_Descripcion;
    var $Alt_var_Clase;
    var $Alt_var_Orden;
    var $Alt_var_Estado;
    var $Alt_par_Descripcion;
//End DataSource Variables

//Class_Initialize Event @2-6057ADE4
    function clsgenvarproceso_genparametrDataSource()
    {
        $this->ErrorBlock = "Grid genvarproceso_genparametr";
        $this->Initialize();
        $this->var_ID = new clsField("var_ID", ccsInteger, "");
        $this->var_TipProceso = new clsField("var_TipProceso", ccsText, "");
        $this->var_Nombre = new clsField("var_Nombre", ccsText, "");
        $this->var_Descripcion = new clsField("var_Descripcion", ccsText, "");
        $this->var_Clase = new clsField("var_Clase", ccsText, "");
        $this->var_Orden = new clsField("var_Orden", ccsInteger, "");
        $this->var_Estado = new clsField("var_Estado", ccsInteger, "");
        $this->par_Descripcion = new clsField("par_Descripcion", ccsText, "");
        $this->Alt_var_ID = new clsField("Alt_var_ID", ccsInteger, "");
        $this->Alt_var_TipProceso = new clsField("Alt_var_TipProceso", ccsText, "");
        $this->Alt_var_Nombre = new clsField("Alt_var_Nombre", ccsText, "");
        $this->Alt_var_Descripcion = new clsField("Alt_var_Descripcion", ccsText, "");
        $this->Alt_var_Clase = new clsField("Alt_var_Clase", ccsText, "");
        $this->Alt_var_Orden = new clsField("Alt_var_Orden", ccsInteger, "");
        $this->Alt_var_Estado = new clsField("Alt_var_Estado", ccsInteger, "");
        $this->Alt_par_Descripcion = new clsField("Alt_par_Descripcion", ccsText, "");

    }
//End Class_Initialize Event

//SetOrder Method @2-35B5C09A
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_var_ID" => array("var_ID", ""), 
            "Sorter_var_TipProceso" => array("var_TipProceso", ""), 
            "Sorter_var_Nombre" => array("var_Nombre", ""), 
            "Sorter_var_Descripcion" => array("var_Descripcion", ""), 
            "Sorter_var_Clase" => array("var_Clase", ""), 
            "Sorter_var_Orden" => array("var_Orden", ""), 
            "Sorter_var_Estado" => array("var_Estado", ""), 
            "Sorter_par_Descripcion" => array("par_Descripcion", "")));
    }
//End SetOrder Method

//Prepare Method @2-024A442C
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_keyword", ccsInteger, "", "", $this->Parameters["urls_keyword"], "", false);
        $this->wp->AddParameter("2", "urls_keyword", ccsText, "", "", $this->Parameters["urls_keyword"], "", false);
        $this->wp->AddParameter("3", "urls_keyword", ccsText, "", "", $this->Parameters["urls_keyword"], "", false);
        $this->wp->AddParameter("4", "urls_keyword", ccsText, "", "", $this->Parameters["urls_keyword"], "", false);
        $this->wp->AddParameter("5", "urls_keyword", ccsText, "", "", $this->Parameters["urls_keyword"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opContains, "var_ID", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opContains, "var_TipProceso", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsText),false);
        $this->wp->Criterion[3] = $this->wp->Operation(opContains, "var_Nombre", $this->wp->GetDBValue("3"), $this->ToSQL($this->wp->GetDBValue("3"), ccsText),false);
        $this->wp->Criterion[4] = $this->wp->Operation(opContains, "var_Descripcion", $this->wp->GetDBValue("4"), $this->ToSQL($this->wp->GetDBValue("4"), ccsText),false);
        $this->wp->Criterion[5] = $this->wp->Operation(opContains, "par_Descripcion", $this->wp->GetDBValue("5"), $this->ToSQL($this->wp->GetDBValue("5"), ccsText),false);
        $this->Where = $this->wp->opOR(false, $this->wp->opOR(false, $this->wp->opOR(false, $this->wp->opOR(false, $this->wp->Criterion[1], $this->wp->Criterion[2]), $this->wp->Criterion[3]), $this->wp->Criterion[4]), $this->wp->Criterion[5]);
    }
//End Prepare Method

//Open Method @2-A0A496DB
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM genvarproceso LEFT JOIN genparametros ON genvarproceso.var_Clase = genparametros.par_Secuencia";
        $this->SQL = "SELECT genvarproceso.*, par_Descripcion  " .
        "FROM genvarproceso LEFT JOIN genparametros ON genvarproceso.var_Clase = genparametros.par_Secuencia";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @2-37268E29
    function SetValues()
    {
        $this->var_ID->SetDBValue(trim($this->f("var_ID")));
        $this->var_TipProceso->SetDBValue($this->f("var_TipProceso"));
        $this->var_Nombre->SetDBValue($this->f("var_Nombre"));
        $this->var_Descripcion->SetDBValue($this->f("var_Descripcion"));
        $this->var_Clase->SetDBValue($this->f("var_Clase"));
        $this->var_Orden->SetDBValue(trim($this->f("var_Orden")));
        $this->var_Estado->SetDBValue(trim($this->f("var_Estado")));
        $this->par_Descripcion->SetDBValue($this->f("par_Descripcion"));
        $this->Alt_var_ID->SetDBValue(trim($this->f("var_ID")));
        $this->Alt_var_TipProceso->SetDBValue($this->f("var_TipProceso"));
        $this->Alt_var_Nombre->SetDBValue($this->f("var_Nombre"));
        $this->Alt_var_Descripcion->SetDBValue($this->f("var_Descripcion"));
        $this->Alt_var_Clase->SetDBValue($this->f("var_Clase"));
        $this->Alt_var_Orden->SetDBValue(trim($this->f("var_Orden")));
        $this->Alt_var_Estado->SetDBValue(trim($this->f("var_Estado")));
        $this->Alt_par_Descripcion->SetDBValue($this->f("par_Descripcion"));
    }
//End SetValues Method

} //End genvarproceso_genparametrDataSource Class @2-FCB6E20C

//Initialize Page @1-B8DF6886
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

$FileName = "LiAdVar_Search.php";
$Redirect = "";
$TemplateFileName = "LiAdVar_Search.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-65498EAE
$DBdatos = new clsDBdatos();

// Controls
$genvarproceso_genparametrSearch = new clsRecordgenvarproceso_genparametrSearch();
$genvarproceso_genparametr = new clsGridgenvarproceso_genparametr();
$genvarproceso_genparametr->Initialize();

// Events
include("./LiAdVar_Search_events.php");
BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-41010DF5
$genvarproceso_genparametrSearch->Operation();
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

//Show Page @1-9F7F6D2C
$genvarproceso_genparametrSearch->Show();
$genvarproceso_genparametr->Show();
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
