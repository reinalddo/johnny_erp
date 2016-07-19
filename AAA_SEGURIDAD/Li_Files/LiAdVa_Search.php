<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @130-49BEB8FC
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation

class clsRecordvarproceso_qry { //varproceso_qry Class @54-46B59B7E

//Variables @54-CB19EB75

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

//Class_Initialize Event @54-8CD1125D
    function clsRecordvarproceso_qry()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record varproceso_qry/Error";
        $this->ReadAllowed = false;
        $this->InsertAllowed = false;
        $this->UpdateAllowed = false;
        $this->DeleteAllowed = false;
        if($this->Visible)
        {
            $this->ComponentName = "varproceso_qry";
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

//Validate Method @54-F230E30A
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_keyword->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @54-D6729123
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_keyword->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @54-D6ECBFBB
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
                    $Redirect = "LiAdVa_search.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", Array("Button_DoSearch")));
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @54-7C48BB57
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

} //End varproceso_qry Class @54-FCB6E20C

class clsGridvarproceso_list { //varproceso_list class @58-90225B50

//Variables @58-8D9C3069

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
    var $Navigator;
    var $Sorter_var_ID;
    var $Sorter_var_Nombre;
    var $Sorter_var_Descripcion;
    var $Sorter_par_Descripcion;
    var $Sorter_var_TipProceso;
    var $Sorter_var_Orden;
    var $Sorter_var_Estado;
//End Variables

//Class_Initialize Event @58-65124318
    function clsGridvarproceso_list()
    {
        global $FileName;
        $this->ComponentName = "varproceso_list";
        $this->Visible = True;
        $this->IsAltRow = false;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid varproceso_list";
        $this->ds = new clsvarproceso_listDataSource();
        $this->PageSize = 25;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        $this->SorterName = CCGetParam("varproceso_listOrder", "");
        $this->SorterDirection = CCGetParam("varproceso_listDir", "");

        $this->var_ID = new clsControl(ccsHidden, "var_ID", "var_ID", ccsInteger, "", CCGetRequestParam("var_ID", ccsGet));
        $this->Button2 = new clsButton("Button2");
        $this->var_Nombre = new clsControl(ccsHidden, "var_Nombre", "var_Nombre", ccsText, "", CCGetRequestParam("var_Nombre", ccsGet));
        $this->Label1 = new clsControl(ccsLabel, "Label1", "Label1", ccsText, "", CCGetRequestParam("Label1", ccsGet));
        $this->var_Descripcion = new clsControl(ccsHidden, "var_Descripcion", "var_Descripcion", ccsText, "", CCGetRequestParam("var_Descripcion", ccsGet));
        $this->Label3 = new clsControl(ccsLabel, "Label3", "Label3", ccsText, "", CCGetRequestParam("Label3", ccsGet));
        $this->par_Descripcion = new clsControl(ccsLabel, "par_Descripcion", "par_Descripcion", ccsText, "", CCGetRequestParam("par_Descripcion", ccsGet));
        $this->var_TipProceso = new clsControl(ccsLabel, "var_TipProceso", "var_TipProceso", ccsText, "", CCGetRequestParam("var_TipProceso", ccsGet));
        $this->var_Orden = new clsControl(ccsLabel, "var_Orden", "var_Orden", ccsInteger, Array(True, 0, "", "", False, Array("0", "0"), "", 1, True, ""), CCGetRequestParam("var_Orden", ccsGet));
        $this->var_Estado = new clsControl(ccsLabel, "var_Estado", "var_Estado", ccsText, "", CCGetRequestParam("var_Estado", ccsGet));
        $this->Alt_var_ID = new clsControl(ccsHidden, "Alt_var_ID", "Alt_var_ID", ccsInteger, "", CCGetRequestParam("Alt_var_ID", ccsGet));
        $this->Button3 = new clsButton("Button3");
        $this->Alt_var_Nombre = new clsControl(ccsHidden, "Alt_var_Nombre", "Alt_var_Nombre", ccsText, "", CCGetRequestParam("Alt_var_Nombre", ccsGet));
        $this->Label2 = new clsControl(ccsLabel, "Label2", "Label2", ccsText, "", CCGetRequestParam("Label2", ccsGet));
        $this->Alt_var_Descripcion = new clsControl(ccsHidden, "Alt_var_Descripcion", "Alt_var_Descripcion", ccsText, "", CCGetRequestParam("Alt_var_Descripcion", ccsGet));
        $this->Label4 = new clsControl(ccsLabel, "Label4", "Label4", ccsText, "", CCGetRequestParam("Label4", ccsGet));
        $this->Alt_par_Descripcion = new clsControl(ccsLabel, "Alt_par_Descripcion", "Alt_par_Descripcion", ccsText, "", CCGetRequestParam("Alt_par_Descripcion", ccsGet));
        $this->Alt_var_TipProceso = new clsControl(ccsLabel, "Alt_var_TipProceso", "Alt_var_TipProceso", ccsText, "", CCGetRequestParam("Alt_var_TipProceso", ccsGet));
        $this->Alt_var_Orden = new clsControl(ccsLabel, "Alt_var_Orden", "Alt_var_Orden", ccsInteger, Array(True, 0, "", "", False, Array("0", "0"), "", 1, True, ""), CCGetRequestParam("Alt_var_Orden", ccsGet));
        $this->Alt_var_Estado = new clsControl(ccsLabel, "Alt_var_Estado", "Alt_var_Estado", ccsText, "", CCGetRequestParam("Alt_var_Estado", ccsGet));
        $this->genvarproceso_genparametr_TotalRecords = new clsControl(ccsLabel, "genvarproceso_genparametr_TotalRecords", "genvarproceso_genparametr_TotalRecords", ccsText, "", CCGetRequestParam("genvarproceso_genparametr_TotalRecords", ccsGet));
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpCentered);
        $this->btNuevo = new clsButton("btNuevo");
        $this->btCerrar = new clsButton("btCerrar");
        $this->Sorter_var_ID = new clsSorter($this->ComponentName, "Sorter_var_ID", $FileName);
        $this->Sorter_var_Nombre = new clsSorter($this->ComponentName, "Sorter_var_Nombre", $FileName);
        $this->Sorter_var_Descripcion = new clsSorter($this->ComponentName, "Sorter_var_Descripcion", $FileName);
        $this->Sorter_par_Descripcion = new clsSorter($this->ComponentName, "Sorter_par_Descripcion", $FileName);
        $this->Sorter_var_TipProceso = new clsSorter($this->ComponentName, "Sorter_var_TipProceso", $FileName);
        $this->Sorter_var_Orden = new clsSorter($this->ComponentName, "Sorter_var_Orden", $FileName);
        $this->Sorter_var_Estado = new clsSorter($this->ComponentName, "Sorter_var_Estado", $FileName);
    }
//End Class_Initialize Event

//Initialize Method @58-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @58-F7803896
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["expr96"] = LGVAR;
        $this->ds->Parameters["expr129"] = LGESTA;
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
                    $this->var_Nombre->SetValue($this->ds->var_Nombre->GetValue());
                    $this->Label1->SetValue($this->ds->Label1->GetValue());
                    $this->var_Descripcion->SetValue($this->ds->var_Descripcion->GetValue());
                    $this->Label3->SetValue($this->ds->Label3->GetValue());
                    $this->par_Descripcion->SetValue($this->ds->par_Descripcion->GetValue());
                    $this->var_TipProceso->SetValue($this->ds->var_TipProceso->GetValue());
                    $this->var_Orden->SetValue($this->ds->var_Orden->GetValue());
                    $this->var_Estado->SetValue($this->ds->var_Estado->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->var_ID->Show();
                    $this->Button2->Show();
                    $this->var_Nombre->Show();
                    $this->Label1->Show();
                    $this->var_Descripcion->Show();
                    $this->Label3->Show();
                    $this->par_Descripcion->Show();
                    $this->var_TipProceso->Show();
                    $this->var_Orden->Show();
                    $this->var_Estado->Show();
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                    $Tpl->parse("Row", true);
                }
                else
                {
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/AltRow";
                    $this->Alt_var_ID->SetValue($this->ds->Alt_var_ID->GetValue());
                    $this->Alt_var_Nombre->SetValue($this->ds->Alt_var_Nombre->GetValue());
                    $this->Label2->SetValue($this->ds->Label2->GetValue());
                    $this->Alt_var_Descripcion->SetValue($this->ds->Alt_var_Descripcion->GetValue());
                    $this->Label4->SetValue($this->ds->Label4->GetValue());
                    $this->Alt_par_Descripcion->SetValue($this->ds->Alt_par_Descripcion->GetValue());
                    $this->Alt_var_TipProceso->SetValue($this->ds->Alt_var_TipProceso->GetValue());
                    $this->Alt_var_Orden->SetValue($this->ds->Alt_var_Orden->GetValue());
                    $this->Alt_var_Estado->SetValue($this->ds->Alt_var_Estado->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->Alt_var_ID->Show();
                    $this->Button3->Show();
                    $this->Alt_var_Nombre->Show();
                    $this->Label2->Show();
                    $this->Alt_var_Descripcion->Show();
                    $this->Label4->Show();
                    $this->Alt_par_Descripcion->Show();
                    $this->Alt_var_TipProceso->Show();
                    $this->Alt_var_Orden->Show();
                    $this->Alt_var_Estado->Show();
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
        $this->Navigator->Show();
        $this->btNuevo->Show();
        $this->btCerrar->Show();
        $this->Sorter_var_ID->Show();
        $this->Sorter_var_Nombre->Show();
        $this->Sorter_var_Descripcion->Show();
        $this->Sorter_par_Descripcion->Show();
        $this->Sorter_var_TipProceso->Show();
        $this->Sorter_var_Orden->Show();
        $this->Sorter_var_Estado->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @58-7E85221D
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->var_ID->Errors->ToString();
        $errors .= $this->var_Nombre->Errors->ToString();
        $errors .= $this->Label1->Errors->ToString();
        $errors .= $this->var_Descripcion->Errors->ToString();
        $errors .= $this->Label3->Errors->ToString();
        $errors .= $this->par_Descripcion->Errors->ToString();
        $errors .= $this->var_TipProceso->Errors->ToString();
        $errors .= $this->var_Orden->Errors->ToString();
        $errors .= $this->var_Estado->Errors->ToString();
        $errors .= $this->Alt_var_ID->Errors->ToString();
        $errors .= $this->Alt_var_Nombre->Errors->ToString();
        $errors .= $this->Label2->Errors->ToString();
        $errors .= $this->Alt_var_Descripcion->Errors->ToString();
        $errors .= $this->Label4->Errors->ToString();
        $errors .= $this->Alt_par_Descripcion->Errors->ToString();
        $errors .= $this->Alt_var_TipProceso->Errors->ToString();
        $errors .= $this->Alt_var_Orden->Errors->ToString();
        $errors .= $this->Alt_var_Estado->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End varproceso_list Class @58-FCB6E20C

class clsvarproceso_listDataSource extends clsDBdatos {  //varproceso_listDataSource Class @58-C8747819

//DataSource Variables @58-7FA6AFB7
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $var_ID;
    var $var_Nombre;
    var $Label1;
    var $var_Descripcion;
    var $Label3;
    var $par_Descripcion;
    var $var_TipProceso;
    var $var_Orden;
    var $var_Estado;
    var $Alt_var_ID;
    var $Alt_var_Nombre;
    var $Label2;
    var $Alt_var_Descripcion;
    var $Label4;
    var $Alt_par_Descripcion;
    var $Alt_var_TipProceso;
    var $Alt_var_Orden;
    var $Alt_var_Estado;
//End DataSource Variables

//Class_Initialize Event @58-9C1195F3
    function clsvarproceso_listDataSource()
    {
        $this->ErrorBlock = "Grid varproceso_list";
        $this->Initialize();
        $this->var_ID = new clsField("var_ID", ccsInteger, "");
        $this->var_Nombre = new clsField("var_Nombre", ccsText, "");
        $this->Label1 = new clsField("Label1", ccsText, "");
        $this->var_Descripcion = new clsField("var_Descripcion", ccsText, "");
        $this->Label3 = new clsField("Label3", ccsText, "");
        $this->par_Descripcion = new clsField("par_Descripcion", ccsText, "");
        $this->var_TipProceso = new clsField("var_TipProceso", ccsText, "");
        $this->var_Orden = new clsField("var_Orden", ccsInteger, "");
        $this->var_Estado = new clsField("var_Estado", ccsText, "");
        $this->Alt_var_ID = new clsField("Alt_var_ID", ccsInteger, "");
        $this->Alt_var_Nombre = new clsField("Alt_var_Nombre", ccsText, "");
        $this->Label2 = new clsField("Label2", ccsText, "");
        $this->Alt_var_Descripcion = new clsField("Alt_var_Descripcion", ccsText, "");
        $this->Label4 = new clsField("Label4", ccsText, "");
        $this->Alt_par_Descripcion = new clsField("Alt_par_Descripcion", ccsText, "");
        $this->Alt_var_TipProceso = new clsField("Alt_var_TipProceso", ccsText, "");
        $this->Alt_var_Orden = new clsField("Alt_var_Orden", ccsInteger, "");
        $this->Alt_var_Estado = new clsField("Alt_var_Estado", ccsText, "");

    }
//End Class_Initialize Event

//SetOrder Method @58-FA8C9E87
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "var_ID";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_var_ID" => array("var_ID", ""), 
            "Sorter_var_Nombre" => array("var_Nombre", ""), 
            "Sorter_var_Descripcion" => array("var_Descripcion", ""), 
            "Sorter_par_Descripcion" => array("par_Descripcion", ""), 
            "Sorter_var_TipProceso" => array("var_TipProceso", ""), 
            "Sorter_var_Orden" => array("var_Orden", ""), 
            "Sorter_var_Estado" => array("var_Estado", "")));
    }
//End SetOrder Method

//Prepare Method @58-DB4CCEFC
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "expr96", ccsText, "", "", $this->Parameters["expr96"], "", false);
        $this->wp->AddParameter("2", "expr129", ccsText, "", "", $this->Parameters["expr129"], "", false);
        $this->wp->AddParameter("3", "urls_keyword", ccsText, "", "", $this->Parameters["urls_keyword"], "", false);
        $this->wp->AddParameter("4", "urls_keyword", ccsText, "", "", $this->Parameters["urls_keyword"], "", false);
        $this->wp->AddParameter("5", "urls_keyword", ccsText, "", "", $this->Parameters["urls_keyword"], "", false);
        $this->wp->AddParameter("6", "urls_keyword", ccsText, "", "", $this->Parameters["urls_keyword"], "", false);
        $this->wp->AddParameter("7", "urls_keyword", ccsText, "", "", $this->Parameters["urls_keyword"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "genparametros.par_Clave", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opEqual, "est.par_Clave", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsText),false);
        $this->wp->Criterion[3] = $this->wp->Operation(opBeginsWith, "var_ID", $this->wp->GetDBValue("3"), $this->ToSQL($this->wp->GetDBValue("3"), ccsText),false);
        $this->wp->Criterion[4] = $this->wp->Operation(opBeginsWith, "var_TipProceso", $this->wp->GetDBValue("4"), $this->ToSQL($this->wp->GetDBValue("4"), ccsText),false);
        $this->wp->Criterion[5] = $this->wp->Operation(opContains, "var_Nombre", $this->wp->GetDBValue("5"), $this->ToSQL($this->wp->GetDBValue("5"), ccsText),false);
        $this->wp->Criterion[6] = $this->wp->Operation(opContains, "var_Descripcion", $this->wp->GetDBValue("6"), $this->ToSQL($this->wp->GetDBValue("6"), ccsText),false);
        $this->wp->Criterion[7] = $this->wp->Operation(opContains, "genparametros.par_Descripcion", $this->wp->GetDBValue("7"), $this->ToSQL($this->wp->GetDBValue("7"), ccsText),false);
        $this->Where = $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->Criterion[1], $this->wp->Criterion[2]), $this->wp->opOR(true, $this->wp->opOR(false, $this->wp->opOR(false, $this->wp->opOR(false, $this->wp->Criterion[3], $this->wp->Criterion[4]), $this->wp->Criterion[5]), $this->wp->Criterion[6]), $this->wp->Criterion[7]));
    }
//End Prepare Method

//Open Method @58-0713094B
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM (genvarproceso LEFT JOIN genparametros ON " .
        "genvarproceso.var_Clase = genparametros.par_Secuencia) LEFT JOIN genparametros est ON " .
        "genvarproceso.var_Estado = est.par_Secuencia";
        $this->SQL = "SELECT genvarproceso.*, genparametros.par_Descripcion AS genparametros_par_Descripcion, est.par_Clave AS est_par_Clave, est.par_Descripcion AS tmp_Estado  " .
        "FROM (genvarproceso LEFT JOIN genparametros ON " .
        "genvarproceso.var_Clase = genparametros.par_Secuencia) LEFT JOIN genparametros est ON " .
        "genvarproceso.var_Estado = est.par_Secuencia";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @58-C3B33925
    function SetValues()
    {
        $this->var_ID->SetDBValue(trim($this->f("var_ID")));
        $this->var_Nombre->SetDBValue($this->f("var_Nombre"));
        $this->Label1->SetDBValue($this->f("var_Nombre"));
        $this->var_Descripcion->SetDBValue($this->f("var_Descripcion"));
        $this->Label3->SetDBValue($this->f("var_Descripcion"));
        $this->par_Descripcion->SetDBValue($this->f("par_Descripcion"));
        $this->var_TipProceso->SetDBValue($this->f("var_TipProceso"));
        $this->var_Orden->SetDBValue(trim($this->f("var_Orden")));
        $this->var_Estado->SetDBValue($this->f("tmp_Estado"));
        $this->Alt_var_ID->SetDBValue(trim($this->f("var_ID")));
        $this->Alt_var_Nombre->SetDBValue($this->f("var_Nombre"));
        $this->Label2->SetDBValue($this->f("var_Nombre"));
        $this->Alt_var_Descripcion->SetDBValue($this->f("var_Descripcion"));
        $this->Label4->SetDBValue($this->f("var_Descripcion"));
        $this->Alt_par_Descripcion->SetDBValue($this->f("par_Descripcion"));
        $this->Alt_var_TipProceso->SetDBValue($this->f("var_TipProceso"));
        $this->Alt_var_Orden->SetDBValue(trim($this->f("var_Orden")));
        $this->Alt_var_Estado->SetDBValue($this->f("tmp_Estado"));
    }
//End SetValues Method

} //End varproceso_listDataSource Class @58-FCB6E20C

//Initialize Page @1-5477C711
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

$FileName = "LiAdVa_search.php";
$Redirect = "";
$TemplateFileName = "LiAdVa_search.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-24A5E388
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
$varproceso_qry = new clsRecordvarproceso_qry();
$varproceso_list = new clsGridvarproceso_list();
$varproceso_list->Initialize();

// Events
include("./LiAdVa_search_events.php");
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

//Execute Components @1-087A9481
$Cabecera->Operations();
$varproceso_qry->Operation();
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

//Show Page @1-4EE1C3F9
$Cabecera->Show("Cabecera");
$varproceso_qry->Show();
$varproceso_list->Show();
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
