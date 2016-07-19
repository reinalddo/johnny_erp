<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @63-49BEB8FC
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation

Class clsRecordcjas_query { //cjas_query Class @4-B45E8245

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

//Class_Initialize Event @4-ED094F78
    function clsRecordcjas_query()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record cjas_query/Error";
        $this->ReadAllowed = false;
        $this->InsertAllowed = false;
        $this->UpdateAllowed = false;
        $this->DeleteAllowed = false;
        if($this->Visible)
        {
            $this->ComponentName = "cjas_query";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->s_caj_CodMarca = new clsControl(ccsListBox, "s_caj_CodMarca", "s_caj_CodMarca", ccsInteger, "", CCGetRequestParam("s_caj_CodMarca", $Method));
            $this->s_caj_CodMarca->DSType = dsTable;
            list($this->s_caj_CodMarca->BoundColumn, $this->s_caj_CodMarca->TextColumn, $this->s_caj_CodMarca->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->s_caj_CodMarca->ds = new clsDBdatos();
            $this->s_caj_CodMarca->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->s_caj_CodMarca->ds->Parameters["expr58"] = 'IMARCA';
            $this->s_caj_CodMarca->ds->wp = new clsSQLParameters();
            $this->s_caj_CodMarca->ds->wp->AddParameter("1", "expr58", ccsText, "", "", $this->s_caj_CodMarca->ds->Parameters["expr58"], "", false);
            $this->s_caj_CodMarca->ds->wp->Criterion[1] = $this->s_caj_CodMarca->ds->wp->Operation(opEqual, "par_Clave", $this->s_caj_CodMarca->ds->wp->GetDBValue("1"), $this->s_caj_CodMarca->ds->ToSQL($this->s_caj_CodMarca->ds->wp->GetDBValue("1"), ccsText),false);
            $this->s_caj_CodMarca->ds->Where = $this->s_caj_CodMarca->ds->wp->Criterion[1];
            $this->s_caj_Abreviatura = new clsControl(ccsTextBox, "s_caj_Abreviatura", "s_caj_Abreviatura", ccsText, "", CCGetRequestParam("s_caj_Abreviatura", $Method));
            $this->s_caj_Descripcion = new clsControl(ccsTextBox, "s_caj_Descripcion", "s_caj_Descripcion", ccsText, "", CCGetRequestParam("s_caj_Descripcion", $Method));
            $this->s_caj_TipoCaja = new clsControl(ccsTextBox, "s_caj_TipoCaja", "s_caj_TipoCaja", ccsText, "", CCGetRequestParam("s_caj_TipoCaja", $Method));
            $this->Button_DoSearch = new clsButton("Button_DoSearch");
            $this->ClearParameters = new clsControl(ccsLink, "ClearParameters", "ClearParameters", ccsText, "", CCGetRequestParam("ClearParameters", $Method));
            $this->ClearParameters->Parameters = CCGetQueryString("QueryString", Array("s_caj_CodMarca", "s_caj_Abreviatura", "s_caj_Descripcion", "s_caj_TipoCaja", "ccsForm"));
            $this->ClearParameters->Page = "LiAdTc.php";
        }
    }
//End Class_Initialize Event

//Validate Method @4-D1B01A50
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_caj_CodMarca->Validate() && $Validation);
        $Validation = ($this->s_caj_Abreviatura->Validate() && $Validation);
        $Validation = ($this->s_caj_Descripcion->Validate() && $Validation);
        $Validation = ($this->s_caj_TipoCaja->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @4-57B23AB2
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_caj_CodMarca->Errors->Count());
        $errors = ($errors || $this->s_caj_Abreviatura->Errors->Count());
        $errors = ($errors || $this->s_caj_Descripcion->Errors->Count());
        $errors = ($errors || $this->s_caj_TipoCaja->Errors->Count());
        $errors = ($errors || $this->ClearParameters->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @4-8096B462
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
        $Redirect = "LiAdTc.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick")) {
                    $Redirect = "";
                } else {
                    $Redirect = "LiAdTc.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", Array("Button_DoSearch")));
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @4-2E4A8C22
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->s_caj_CodMarca->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        if(!$this->FormSubmitted)
        {
            $this->ClearParameters->SetText("LIMPIAR");
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->s_caj_CodMarca->Errors->ToString();
            $Error .= $this->s_caj_Abreviatura->Errors->ToString();
            $Error .= $this->s_caj_Descripcion->Errors->ToString();
            $Error .= $this->s_caj_TipoCaja->Errors->ToString();
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

        $this->s_caj_CodMarca->Show();
        $this->s_caj_Abreviatura->Show();
        $this->s_caj_Descripcion->Show();
        $this->s_caj_TipoCaja->Show();
        $this->Button_DoSearch->Show();
        $this->ClearParameters->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End cjas_query Class @4-FCB6E20C

class clsGridcjas_list { //cjas_list class @3-38FB2C11

//Variables @3-F1C80DB7

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
    var $Sorter_caj_CodCaja;
    var $Sorter_caj_Descripcion;
    var $Sorter_caj_CodMarca;
    var $Sorter_caj_TipoCaja;
    var $Sorter_caj_Abreviatura;
    var $Navigator;
//End Variables

//Class_Initialize Event @3-37DAC85B
    function clsGridcjas_list()
    {
        global $FileName;
        $this->ComponentName = "cjas_list";
        $this->Visible = True;
        $this->IsAltRow = false;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid cjas_list";
        $this->ds = new clscjas_listDataSource();
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 20;
        else if ($this->PageSize > 100)
            $this->PageSize = 100;
        else
            $this->PageSize = intval($this->PageSize);
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        $this->SorterName = CCGetParam("cjas_listOrder", "");
        $this->SorterDirection = CCGetParam("cjas_listDir", "");

        $this->caj_CodCaja = new clsControl(ccsLink, "caj_CodCaja", "caj_CodCaja", ccsInteger, "", CCGetRequestParam("caj_CodCaja", ccsGet));
        $this->caj_Descripcion = new clsControl(ccsLabel, "caj_Descripcion", "caj_Descripcion", ccsText, "", CCGetRequestParam("caj_Descripcion", ccsGet));
        $this->caj_CodMarca = new clsControl(ccsLabel, "caj_CodMarca", "caj_CodMarca", ccsText, "", CCGetRequestParam("caj_CodMarca", ccsGet));
        $this->caj_TipoCaja = new clsControl(ccsLabel, "caj_TipoCaja", "caj_TipoCaja", ccsText, "", CCGetRequestParam("caj_TipoCaja", ccsGet));
        $this->caj_Abreviatura = new clsControl(ccsLabel, "caj_Abreviatura", "caj_Abreviatura", ccsText, "", CCGetRequestParam("caj_Abreviatura", ccsGet));
        $this->Alt_caj_CodCaja = new clsControl(ccsLink, "Alt_caj_CodCaja", "Alt_caj_CodCaja", ccsInteger, "", CCGetRequestParam("Alt_caj_CodCaja", ccsGet));
        $this->Alt_caj_Descripcion = new clsControl(ccsLabel, "Alt_caj_Descripcion", "Alt_caj_Descripcion", ccsText, "", CCGetRequestParam("Alt_caj_Descripcion", ccsGet));
        $this->Alt_caj_CodMarca = new clsControl(ccsLabel, "Alt_caj_CodMarca", "Alt_caj_CodMarca", ccsText, "", CCGetRequestParam("Alt_caj_CodMarca", ccsGet));
        $this->Alt_caj_TipoCaja = new clsControl(ccsLabel, "Alt_caj_TipoCaja", "Alt_caj_TipoCaja", ccsText, "", CCGetRequestParam("Alt_caj_TipoCaja", ccsGet));
        $this->Alt_caj_Abreviatura = new clsControl(ccsLabel, "Alt_caj_Abreviatura", "Alt_caj_Abreviatura", ccsText, "", CCGetRequestParam("Alt_caj_Abreviatura", ccsGet));
        $this->Sorter_caj_CodCaja = new clsSorter($this->ComponentName, "Sorter_caj_CodCaja", $FileName);
        $this->Sorter_caj_Descripcion = new clsSorter($this->ComponentName, "Sorter_caj_Descripcion", $FileName);
        $this->Sorter_caj_CodMarca = new clsSorter($this->ComponentName, "Sorter_caj_CodMarca", $FileName);
        $this->Sorter_caj_TipoCaja = new clsSorter($this->ComponentName, "Sorter_caj_TipoCaja", $FileName);
        $this->Sorter_caj_Abreviatura = new clsSorter($this->ComponentName, "Sorter_caj_Abreviatura", $FileName);
        $this->liqcajas_Insert = new clsControl(ccsLink, "liqcajas_Insert", "liqcajas_Insert", ccsText, "", CCGetRequestParam("liqcajas_Insert", ccsGet));
        $this->liqcajas_Insert->Parameters = CCGetQueryString("QueryString", Array("caj_CodCaja", "ccsForm"));
        $this->liqcajas_Insert->Page = "LiAdTc_mant.php";
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

//Show Method @3-29A86332
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["urls_caj_CodMarca"] = CCGetFromGet("s_caj_CodMarca", "");
        $this->ds->Parameters["urls_caj_Abreviatura"] = CCGetFromGet("s_caj_Abreviatura", "");
        $this->ds->Parameters["urls_caj_Descripcion"] = CCGetFromGet("s_caj_Descripcion", "");
        $this->ds->Parameters["urls_caj_TipoCaja"] = CCGetFromGet("s_caj_TipoCaja", "");

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
                    $this->caj_CodCaja->SetValue($this->ds->caj_CodCaja->GetValue());
                    $this->caj_CodCaja->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
                    $this->caj_CodCaja->Parameters = CCAddParam($this->caj_CodCaja->Parameters, "caj_CodCaja", $this->ds->f("caj_CodCaja"));
                    $this->caj_CodCaja->Page = "LiAdTc_mant.php";
                    $this->caj_Descripcion->SetValue($this->ds->caj_Descripcion->GetValue());
                    $this->caj_CodMarca->SetValue($this->ds->caj_CodMarca->GetValue());
                    $this->caj_TipoCaja->SetValue($this->ds->caj_TipoCaja->GetValue());
                    $this->caj_Abreviatura->SetValue($this->ds->caj_Abreviatura->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->caj_CodCaja->Show();
                    $this->caj_Descripcion->Show();
                    $this->caj_CodMarca->Show();
                    $this->caj_TipoCaja->Show();
                    $this->caj_Abreviatura->Show();
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                    $Tpl->parse("Row", true);
                }
                else
                {
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/AltRow";
                    $this->Alt_caj_CodCaja->SetValue($this->ds->Alt_caj_CodCaja->GetValue());
                    $this->Alt_caj_CodCaja->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
                    $this->Alt_caj_CodCaja->Parameters = CCAddParam($this->Alt_caj_CodCaja->Parameters, "caj_CodCaja", $this->ds->f("caj_CodCaja"));
                    $this->Alt_caj_CodCaja->Page = "LiAdTc_mant.php";
                    $this->Alt_caj_Descripcion->SetValue($this->ds->Alt_caj_Descripcion->GetValue());
                    $this->Alt_caj_CodMarca->SetValue($this->ds->Alt_caj_CodMarca->GetValue());
                    $this->Alt_caj_TipoCaja->SetValue($this->ds->Alt_caj_TipoCaja->GetValue());
                    $this->Alt_caj_Abreviatura->SetValue($this->ds->Alt_caj_Abreviatura->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->Alt_caj_CodCaja->Show();
                    $this->Alt_caj_Descripcion->Show();
                    $this->Alt_caj_CodMarca->Show();
                    $this->Alt_caj_TipoCaja->Show();
                    $this->Alt_caj_Abreviatura->Show();
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
        $this->Sorter_caj_CodCaja->Show();
        $this->Sorter_caj_Descripcion->Show();
        $this->Sorter_caj_CodMarca->Show();
        $this->Sorter_caj_TipoCaja->Show();
        $this->Sorter_caj_Abreviatura->Show();
        $this->liqcajas_Insert->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @3-DFF6D8E4
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->caj_CodCaja->Errors->ToString();
        $errors .= $this->caj_Descripcion->Errors->ToString();
        $errors .= $this->caj_CodMarca->Errors->ToString();
        $errors .= $this->caj_TipoCaja->Errors->ToString();
        $errors .= $this->caj_Abreviatura->Errors->ToString();
        $errors .= $this->Alt_caj_CodCaja->Errors->ToString();
        $errors .= $this->Alt_caj_Descripcion->Errors->ToString();
        $errors .= $this->Alt_caj_CodMarca->Errors->ToString();
        $errors .= $this->Alt_caj_TipoCaja->Errors->ToString();
        $errors .= $this->Alt_caj_Abreviatura->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End cjas_list Class @3-FCB6E20C

class clscjas_listDataSource extends clsDBdatos {  //cjas_listDataSource Class @3-6CD0881B

//DataSource Variables @3-09303B46
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $caj_CodCaja;
    var $caj_Descripcion;
    var $caj_CodMarca;
    var $caj_TipoCaja;
    var $caj_Abreviatura;
    var $Alt_caj_CodCaja;
    var $Alt_caj_Descripcion;
    var $Alt_caj_CodMarca;
    var $Alt_caj_TipoCaja;
    var $Alt_caj_Abreviatura;
//End DataSource Variables

//Class_Initialize Event @3-7405EAEE
    function clscjas_listDataSource()
    {
        $this->ErrorBlock = "Grid cjas_list";
        $this->Initialize();
        $this->caj_CodCaja = new clsField("caj_CodCaja", ccsInteger, "");
        $this->caj_Descripcion = new clsField("caj_Descripcion", ccsText, "");
        $this->caj_CodMarca = new clsField("caj_CodMarca", ccsText, "");
        $this->caj_TipoCaja = new clsField("caj_TipoCaja", ccsText, "");
        $this->caj_Abreviatura = new clsField("caj_Abreviatura", ccsText, "");
        $this->Alt_caj_CodCaja = new clsField("Alt_caj_CodCaja", ccsInteger, "");
        $this->Alt_caj_Descripcion = new clsField("Alt_caj_Descripcion", ccsText, "");
        $this->Alt_caj_CodMarca = new clsField("Alt_caj_CodMarca", ccsText, "");
        $this->Alt_caj_TipoCaja = new clsField("Alt_caj_TipoCaja", ccsText, "");
        $this->Alt_caj_Abreviatura = new clsField("Alt_caj_Abreviatura", ccsText, "");

    }
//End Class_Initialize Event

//SetOrder Method @3-84259E76
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "par_descripcion";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_caj_CodCaja" => array("caj_CodCaja", ""), 
            "Sorter_caj_Descripcion" => array("caj_Descripcion", ""), 
            "Sorter_caj_CodMarca" => array("caj_CodMarca", ""), 
            "Sorter_caj_TipoCaja" => array("caj_TipoCaja", ""), 
            "Sorter_caj_Abreviatura" => array("caj_Abreviatura", "")));
    }
//End SetOrder Method

//Prepare Method @3-7809FD6D
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_caj_CodMarca", ccsText, "", "", $this->Parameters["urls_caj_CodMarca"], "", false);
        $this->wp->AddParameter("2", "urls_caj_Abreviatura", ccsText, "", "", $this->Parameters["urls_caj_Abreviatura"], "", false);
        $this->wp->AddParameter("3", "urls_caj_Descripcion", ccsText, "", "", $this->Parameters["urls_caj_Descripcion"], "", false);
        $this->wp->AddParameter("4", "urls_caj_TipoCaja", ccsText, "", "", $this->Parameters["urls_caj_TipoCaja"], "", false);
    }
//End Prepare Method

//Open Method @3-4633D628
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*) FROM liqcajas, genparametros " .
        "WHERE genparametros.par_clave = 'IMARCA' AND " .
        "      genparametros.par_secuencia = caj_CodMarca AND " .
        "      liqcajas.caj_CodMarca LIKE '%" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' AND  " .
        "      caj_Abreviatura LIKE '%" . $this->SQLValue($this->wp->GetDBValue("2"), ccsText) . "%' AND  " .
        "      caj_Descripcion LIKE '%" . $this->SQLValue($this->wp->GetDBValue("3"), ccsText) . "%' AND  " .
        "      caj_TipoCaja LIKE '%" . $this->SQLValue($this->wp->GetDBValue("4"), ccsText) . "%' " .
        "";
        $this->SQL = "SELECT liqcajas.*, genparametros.par_descripcion " .
        "FROM liqcajas, genparametros " .
        "WHERE genparametros.par_clave = 'IMARCA' AND " .
        "      genparametros.par_secuencia = caj_CodMarca AND " .
        "      liqcajas.caj_CodMarca LIKE '%" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' AND  " .
        "      caj_Abreviatura LIKE '%" . $this->SQLValue($this->wp->GetDBValue("2"), ccsText) . "%' AND  " .
        "      caj_Descripcion LIKE '%" . $this->SQLValue($this->wp->GetDBValue("3"), ccsText) . "%' AND  " .
        "      caj_TipoCaja LIKE '%" . $this->SQLValue($this->wp->GetDBValue("4"), ccsText) . "%' " .
        "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue($this->CountSQL, $this);
        $this->query(CCBuildSQL($this->SQL, "", $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @3-F406C57C
    function SetValues()
    {
        $this->caj_CodCaja->SetDBValue(trim($this->f("caj_CodCaja")));
        $this->caj_Descripcion->SetDBValue($this->f("caj_Descripcion"));
        $this->caj_CodMarca->SetDBValue($this->f("par_descripcion"));
        $this->caj_TipoCaja->SetDBValue($this->f("caj_TipoCaja"));
        $this->caj_Abreviatura->SetDBValue($this->f("caj_Abreviatura"));
        $this->Alt_caj_CodCaja->SetDBValue(trim($this->f("caj_CodCaja")));
        $this->Alt_caj_Descripcion->SetDBValue($this->f("caj_Descripcion"));
        $this->Alt_caj_CodMarca->SetDBValue($this->f("par_descripcion"));
        $this->Alt_caj_TipoCaja->SetDBValue($this->f("caj_TipoCaja"));
        $this->Alt_caj_Abreviatura->SetDBValue($this->f("caj_Abreviatura"));
    }
//End SetValues Method

} //End cjas_listDataSource Class @3-FCB6E20C



//Initialize Page @1-95D5A779
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

$FileName = "LiAdTc.php";
$Redirect = "";
$TemplateFileName = "LiAdTc.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-69158348
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
$cjas_query = new clsRecordcjas_query();
$cjas_list = new clsGridcjas_list();
$cjas_list->Initialize();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-8CE16BD7
$Cabecera->Operations();
$cjas_query->Operation();
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

//Show Page @1-E5AD6951
$Cabecera->Show("Cabecera");
$cjas_query->Show();
$cjas_list->Show();
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
