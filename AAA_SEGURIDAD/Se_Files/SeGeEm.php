<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @37-49BEB8FC
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation

Class clsRecordSeGeEm_list { //SeGeEm_list Class @3-E2AAF0F5

//Variables @3-CB19EB75

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

//Class_Initialize Event @3-93F5689A
    function clsRecordSeGeEm_list()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record SeGeEm_list/Error";
        $this->ReadAllowed = false;
        $this->InsertAllowed = false;
        $this->UpdateAllowed = false;
        $this->DeleteAllowed = false;
        if($this->Visible)
        {
            $this->ComponentName = "SeGeEm_list";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->s_keyword = new clsControl(ccsTextBox, "s_keyword", "s_keyword", ccsText, "", CCGetRequestParam("s_keyword", $Method));
            $this->Button_DoSearch = new clsButton("Button_DoSearch");
        }
    }
//End Class_Initialize Event

//Validate Method @3-F230E30A
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_keyword->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @3-D6729123
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_keyword->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @3-A8A50446
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
        $Redirect = "SeGeEm.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick")) {
                    $Redirect = "";
                } else {
                    $Redirect = "SeGeEm.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", Array("Button_DoSearch")));
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @3-7C48BB57
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
        $this->Button_DoSearch->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End SeGeEm_list Class @3-FCB6E20C

class clsGridSeGeEm_qry { //SeGeEm_qry class @2-5722C44B

//Variables @2-0DBCF4DC

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
    var $Sorter_emp_IDempresa;
    var $Sorter_emp_Descripcion;
    var $Sorter_emp_Estado;
    var $Sorter_emp_BaseDatos;
//End Variables

//Class_Initialize Event @2-5A13B0ED
    function clsGridSeGeEm_qry()
    {
        global $FileName;
        $this->ComponentName = "SeGeEm_qry";
        $this->Visible = True;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid SeGeEm_qry";
        $this->ds = new clsSeGeEm_qryDataSource();
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 100;
        else if ($this->PageSize > 100)
            $this->PageSize = 100;
        else
            $this->PageSize = intval($this->PageSize);
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        $this->SorterName = CCGetParam("SeGeEm_qryOrder", "");
        $this->SorterDirection = CCGetParam("SeGeEm_qryDir", "");

        $this->emp_IDempresa = new clsControl(ccsLink, "emp_IDempresa", "emp_IDempresa", ccsText, "", CCGetRequestParam("emp_IDempresa", ccsGet));
        $this->emp_Descripcion = new clsControl(ccsLabel, "emp_Descripcion", "emp_Descripcion", ccsText, "", CCGetRequestParam("emp_Descripcion", ccsGet));
        $this->par_Descripcion = new clsControl(ccsLabel, "par_Descripcion", "par_Descripcion", ccsText, "", CCGetRequestParam("par_Descripcion", ccsGet));
        $this->emp_BaseDatos = new clsControl(ccsLabel, "emp_BaseDatos", "emp_BaseDatos", ccsText, "", CCGetRequestParam("emp_BaseDatos", ccsGet));
        $this->Sorter_emp_IDempresa = new clsSorter($this->ComponentName, "Sorter_emp_IDempresa", $FileName);
        $this->Sorter_emp_Descripcion = new clsSorter($this->ComponentName, "Sorter_emp_Descripcion", $FileName);
        $this->Sorter_emp_Estado = new clsSorter($this->ComponentName, "Sorter_emp_Estado", $FileName);
        $this->Sorter_emp_BaseDatos = new clsSorter($this->ComponentName, "Sorter_emp_BaseDatos", $FileName);
        $this->segempresas_Insert = new clsControl(ccsLink, "segempresas_Insert", "segempresas_Insert", ccsText, "", CCGetRequestParam("segempresas_Insert", ccsGet));
        $this->segempresas_Insert->Parameters = CCGetQueryString("QueryString", Array("emp_IDempresa", "ccsForm"));
        $this->segempresas_Insert->Parameters = CCAddParam($this->segempresas_Insert->Parameters, "pOpCode", "ADD");
        $this->segempresas_Insert->Page = "SeGeEm.php";
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

//Show Method @2-C8E00A5F
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["urls_keyword"] = CCGetFromGet("s_keyword", "");
        $this->ds->Parameters["expr47"] = 'LGESTA';

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
                $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/Row";
                $this->emp_IDempresa->SetValue($this->ds->emp_IDempresa->GetValue());
                $this->emp_IDempresa->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
                $this->emp_IDempresa->Parameters = CCAddParam($this->emp_IDempresa->Parameters, "emp_IDempresa", $this->ds->f("emp_IDempresa"));
                $this->emp_IDempresa->Parameters = CCAddParam($this->emp_IDempresa->Parameters, "pOpCode", "UPD");
                $this->emp_IDempresa->Page = "SeGeEm.php";
                $this->emp_Descripcion->SetValue($this->ds->emp_Descripcion->GetValue());
                $this->par_Descripcion->SetValue($this->ds->par_Descripcion->GetValue());
                $this->emp_BaseDatos->SetValue($this->ds->emp_BaseDatos->GetValue());
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->emp_IDempresa->Show();
                $this->emp_Descripcion->Show();
                $this->par_Descripcion->Show();
                $this->emp_BaseDatos->Show();
                $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                $Tpl->parse("Row", true);
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
        $this->Sorter_emp_IDempresa->Show();
        $this->Sorter_emp_Descripcion->Show();
        $this->Sorter_emp_Estado->Show();
        $this->Sorter_emp_BaseDatos->Show();
        $this->segempresas_Insert->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @2-CFF9E6F0
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->emp_IDempresa->Errors->ToString();
        $errors .= $this->emp_Descripcion->Errors->ToString();
        $errors .= $this->par_Descripcion->Errors->ToString();
        $errors .= $this->emp_BaseDatos->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End SeGeEm_qry Class @2-FCB6E20C

class clsSeGeEm_qryDataSource extends clsDBseguridad {  //SeGeEm_qryDataSource Class @2-2667CE1D

//DataSource Variables @2-B4B4CCB2
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $emp_IDempresa;
    var $emp_Descripcion;
    var $par_Descripcion;
    var $emp_BaseDatos;
//End DataSource Variables

//Class_Initialize Event @2-AAC05C6C
    function clsSeGeEm_qryDataSource()
    {
        $this->ErrorBlock = "Grid SeGeEm_qry";
        $this->Initialize();
        $this->emp_IDempresa = new clsField("emp_IDempresa", ccsText, "");
        $this->emp_Descripcion = new clsField("emp_Descripcion", ccsText, "");
        $this->par_Descripcion = new clsField("par_Descripcion", ccsText, "");
        $this->emp_BaseDatos = new clsField("emp_BaseDatos", ccsText, "");

    }
//End Class_Initialize Event

//SetOrder Method @2-51568349
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "emp_Descripcion";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_emp_IDempresa" => array("emp_IDempresa", ""), 
            "Sorter_emp_Descripcion" => array("emp_Descripcion", ""), 
            "Sorter_emp_Estado" => array("emp_Estado", ""), 
            "Sorter_emp_BaseDatos" => array("emp_BaseDatos", "")));
    }
//End SetOrder Method

//Prepare Method @2-7B6E713D
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_keyword", ccsText, "", "", $this->Parameters["urls_keyword"], "", false);
        $this->wp->AddParameter("2", "urls_keyword", ccsText, "", "", $this->Parameters["urls_keyword"], "", false);
        $this->wp->AddParameter("3", "urls_keyword", ccsText, "", "", $this->Parameters["urls_keyword"], "", false);
        $this->wp->AddParameter("4", "expr47", ccsText, "", "", $this->Parameters["expr47"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opContains, "emp_IDempresa", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opContains, "emp_Descripcion", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsText),false);
        $this->wp->Criterion[3] = $this->wp->Operation(opContains, "emp_BaseDatos", $this->wp->GetDBValue("3"), $this->ToSQL($this->wp->GetDBValue("3"), ccsText),false);
        $this->wp->Criterion[4] = $this->wp->Operation(opEqual, "par_Clave", $this->wp->GetDBValue("4"), $this->ToSQL($this->wp->GetDBValue("4"), ccsText),false);
        $this->Where = $this->wp->opOR(false, $this->wp->opOR(false, $this->wp->opOR(false, $this->wp->Criterion[1], $this->wp->Criterion[2]), $this->wp->Criterion[3]), $this->wp->Criterion[4]);
    }
//End Prepare Method

//Open Method @2-46DF0CB6
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM segempresas LEFT JOIN genparametros ON segempresas.emp_Estado = genparametros.par_Secuencia";
        $this->SQL = "SELECT *  " .
        "FROM segempresas LEFT JOIN genparametros ON segempresas.emp_Estado = genparametros.par_Secuencia";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @2-E6D9ABB5
    function SetValues()
    {
        $this->emp_IDempresa->SetDBValue($this->f("emp_IDempresa"));
        $this->emp_Descripcion->SetDBValue($this->f("emp_Descripcion"));
        $this->par_Descripcion->SetDBValue($this->f("par_Descripcion"));
        $this->emp_BaseDatos->SetDBValue($this->f("emp_BaseDatos"));
    }
//End SetValues Method

} //End SeGeEm_qryDataSource Class @2-FCB6E20C

Class clsRecordSeGeEm_mant { //SeGeEm_mant Class @21-0E80C189

//Variables @21-4A82E0A3

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

//Class_Initialize Event @21-1EC6A8F1
    function clsRecordSeGeEm_mant()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record SeGeEm_mant/Error";
        $this->ds = new clsSeGeEm_mantDataSource();
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "SeGeEm_mant";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->lbTitulo = new clsControl(ccsLabel, "lbTitulo", "lbTitulo", ccsText, "", CCGetRequestParam("lbTitulo", $Method));
            $this->emp_IDempresa = new clsControl(ccsTextBox, "emp_IDempresa", "Emp IDempresa", ccsText, "", CCGetRequestParam("emp_IDempresa", $Method));
            $this->emp_IDempresa->Required = true;
            $this->emp_Descripcion = new clsControl(ccsTextBox, "emp_Descripcion", "Emp Descripcion", ccsText, "", CCGetRequestParam("emp_Descripcion", $Method));
            $this->emp_Estado = new clsControl(ccsListBox, "emp_Estado", "Emp Estado", ccsInteger, "", CCGetRequestParam("emp_Estado", $Method));
            $this->emp_Estado->DSType = dsListOfValues;
            $this->emp_Estado->Values = array(array("0", "Inactiva"), array("1", "Activa"));
            $this->emp_BaseDatos = new clsControl(ccsTextBox, "emp_BaseDatos", "Emp Base Datos", ccsText, "", CCGetRequestParam("emp_BaseDatos", $Method));
            $this->Button_Insert = new clsButton("Button_Insert");
            $this->Button_Update = new clsButton("Button_Update");
            $this->Button_Delete = new clsButton("Button_Delete");
            $this->Button_Cancel = new clsButton("Button_Cancel");
            if(!$this->FormSubmitted) {
                if(!is_array($this->emp_Estado->Value) && !strlen($this->emp_Estado->Value) && $this->emp_Estado->Value !== false)
                $this->emp_Estado->SetText(1);
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @21-7B9DD61A
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->ds->Parameters["urlemp_IDempresa"] = CCGetFromGet("emp_IDempresa", "");
    }
//End Initialize Method

//Validate Method @21-E238EE01
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->emp_IDempresa->Validate() && $Validation);
        $Validation = ($this->emp_Descripcion->Validate() && $Validation);
        $Validation = ($this->emp_Estado->Validate() && $Validation);
        $Validation = ($this->emp_BaseDatos->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @21-D858B222
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->lbTitulo->Errors->Count());
        $errors = ($errors || $this->emp_IDempresa->Errors->Count());
        $errors = ($errors || $this->emp_Descripcion->Errors->Count());
        $errors = ($errors || $this->emp_Estado->Errors->Count());
        $errors = ($errors || $this->emp_BaseDatos->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @21-F753EBC9
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
        $Redirect = "SeGeEm.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
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

//InsertRow Method @21-2E3C4B48
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert");
        if(!$this->InsertAllowed) return false;
        $this->ds->lbTitulo->SetValue($this->lbTitulo->GetValue());
        $this->ds->emp_IDempresa->SetValue($this->emp_IDempresa->GetValue());
        $this->ds->emp_Descripcion->SetValue($this->emp_Descripcion->GetValue());
        $this->ds->emp_Estado->SetValue($this->emp_Estado->GetValue());
        $this->ds->emp_BaseDatos->SetValue($this->emp_BaseDatos->GetValue());
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

//UpdateRow Method @21-2F9EEC18
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate");
        if(!$this->UpdateAllowed) return false;
        $this->ds->lbTitulo->SetValue($this->lbTitulo->GetValue());
        $this->ds->emp_IDempresa->SetValue($this->emp_IDempresa->GetValue());
        $this->ds->emp_Descripcion->SetValue($this->emp_Descripcion->GetValue());
        $this->ds->emp_Estado->SetValue($this->emp_Estado->GetValue());
        $this->ds->emp_BaseDatos->SetValue($this->emp_BaseDatos->GetValue());
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

//DeleteRow Method @21-EA88835F
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

//Show Method @21-A97F3673
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->emp_Estado->Prepare();

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
                    echo "Error in Record SeGeEm_mant";
                }
                else if($this->ds->next_record())
                {
                    $this->ds->SetValues();
                    if(!$this->FormSubmitted)
                    {
                        $this->emp_IDempresa->SetValue($this->ds->emp_IDempresa->GetValue());
                        $this->emp_Descripcion->SetValue($this->ds->emp_Descripcion->GetValue());
                        $this->emp_Estado->SetValue($this->ds->emp_Estado->GetValue());
                        $this->emp_BaseDatos->SetValue($this->ds->emp_BaseDatos->GetValue());
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
            $Error .= $this->emp_IDempresa->Errors->ToString();
            $Error .= $this->emp_Descripcion->Errors->ToString();
            $Error .= $this->emp_Estado->Errors->ToString();
            $Error .= $this->emp_BaseDatos->Errors->ToString();
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
        $this->emp_IDempresa->Show();
        $this->emp_Descripcion->Show();
        $this->emp_Estado->Show();
        $this->emp_BaseDatos->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->Button_Cancel->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

} //End SeGeEm_mant Class @21-FCB6E20C

class clsSeGeEm_mantDataSource extends clsDBseguridad {  //SeGeEm_mantDataSource Class @21-038284C6

//DataSource Variables @21-1772ECAB
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
    var $emp_IDempresa;
    var $emp_Descripcion;
    var $emp_Estado;
    var $emp_BaseDatos;
//End DataSource Variables

//Class_Initialize Event @21-113BDBB7
    function clsSeGeEm_mantDataSource()
    {
        $this->ErrorBlock = "Record SeGeEm_mant/Error";
        $this->Initialize();
        $this->lbTitulo = new clsField("lbTitulo", ccsText, "");
        $this->emp_IDempresa = new clsField("emp_IDempresa", ccsText, "");
        $this->emp_Descripcion = new clsField("emp_Descripcion", ccsText, "");
        $this->emp_Estado = new clsField("emp_Estado", ccsInteger, "");
        $this->emp_BaseDatos = new clsField("emp_BaseDatos", ccsText, "");

    }
//End Class_Initialize Event

//Prepare Method @21-BD1867CE
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlemp_IDempresa", ccsText, "", "", $this->Parameters["urlemp_IDempresa"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "emp_IDempresa", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @21-DF818DB6
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->SQL = "SELECT *  " .
        "FROM segempresas";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @21-6A4A5A1E
    function SetValues()
    {
        $this->emp_IDempresa->SetDBValue($this->f("emp_IDempresa"));
        $this->emp_Descripcion->SetDBValue($this->f("emp_Descripcion"));
        $this->emp_Estado->SetDBValue(trim($this->f("emp_Estado")));
        $this->emp_BaseDatos->SetDBValue($this->f("emp_BaseDatos"));
    }
//End SetValues Method

//Insert Method @21-04280BF9
    function Insert()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO segempresas ("
             . "emp_IDempresa, "
             . "emp_Descripcion, "
             . "emp_Estado, "
             . "emp_BaseDatos"
             . ") VALUES ("
             . $this->ToSQL($this->emp_IDempresa->GetDBValue(), $this->emp_IDempresa->DataType) . ", "
             . $this->ToSQL($this->emp_Descripcion->GetDBValue(), $this->emp_Descripcion->DataType) . ", "
             . $this->ToSQL($this->emp_Estado->GetDBValue(), $this->emp_Estado->DataType) . ", "
             . $this->ToSQL($this->emp_BaseDatos->GetDBValue(), $this->emp_BaseDatos->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Insert Method

//Update Method @21-84828948
    function Update()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $this->SQL = "UPDATE segempresas SET "
             . "emp_IDempresa=" . $this->ToSQL($this->emp_IDempresa->GetDBValue(), $this->emp_IDempresa->DataType) . ", "
             . "emp_Descripcion=" . $this->ToSQL($this->emp_Descripcion->GetDBValue(), $this->emp_Descripcion->DataType) . ", "
             . "emp_Estado=" . $this->ToSQL($this->emp_Estado->GetDBValue(), $this->emp_Estado->DataType) . ", "
             . "emp_BaseDatos=" . $this->ToSQL($this->emp_BaseDatos->GetDBValue(), $this->emp_BaseDatos->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Update Method

//Delete Method @21-F6EEFA6B
    function Delete()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $this->SQL = "DELETE FROM segempresas";
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Delete Method

} //End SeGeEm_mantDataSource Class @21-FCB6E20C

//Initialize Page @1-FCA14264
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

$FileName = "SeGeEm.php";
$Redirect = "";
$TemplateFileName = "SeGeEm.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-3F329B52
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
$SeGeEm_list = new clsRecordSeGeEm_list();
$SeGeEm_qry = new clsGridSeGeEm_qry();
$SeGeEm_mant = new clsRecordSeGeEm_mant();
$SeGeEm_qry->Initialize();
$SeGeEm_mant->Initialize();

// Events
include("./SeGeEm_events.php");
BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-0E83CC56
$Cabecera->Operations();
$SeGeEm_list->Operation();
$SeGeEm_mant->Operation();
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

//Show Page @1-A6C899E0
$Cabecera->Show("Cabecera");
$SeGeEm_list->Show();
$SeGeEm_qry->Show();
$SeGeEm_mant->Show();
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
