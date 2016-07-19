<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @58-6BA6AD4B
include_once(RelativePath . "/Co_Files/../De_Files/Cabecera.php");
//End Include Page implementation

class clsRecordautsri_Search { //autsri_Search Class @5-C1FC85CE

//Variables @5-B2F7A83E

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

    var $InsertAllowed = false;
    var $UpdateAllowed = false;
    var $DeleteAllowed = false;
    var $ReadAllowed   = false;
    var $EditMode      = false;
    var $ds;
    var $ValidatingControls;
    var $Controls;

    // Class variables
//End Variables

//Class_Initialize Event @5-EC7E9916
    function clsRecordautsri_Search()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record autsri_Search/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "autsri_Search";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->s_aut_IdAuxiliar = new clsControl(ccsTextBox, "s_aut_IdAuxiliar", "s_aut_IdAuxiliar", ccsInteger, "", CCGetRequestParam("s_aut_IdAuxiliar", $Method));
            $this->s_aut_TipoDocum = new clsControl(ccsTextBox, "s_aut_TipoDocum", "s_aut_TipoDocum", ccsText, "", CCGetRequestParam("s_aut_TipoDocum", $Method));
            $this->s_aut_AutSri = new clsControl(ccsTextBox, "s_aut_AutSri", "s_aut_AutSri", ccsInteger, "", CCGetRequestParam("s_aut_AutSri", $Method));
            $this->Button_DoSearch = new clsButton("Button_DoSearch");
        }
    }
//End Class_Initialize Event

//Validate Method @5-7EFAF81C
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_aut_IdAuxiliar->Validate() && $Validation);
        $Validation = ($this->s_aut_TipoDocum->Validate() && $Validation);
        $Validation = ($this->s_aut_AutSri->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        $Validation =  $Validation && ($this->s_aut_IdAuxiliar->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_aut_TipoDocum->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_aut_AutSri->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @5-7C248782
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_aut_IdAuxiliar->Errors->Count());
        $errors = ($errors || $this->s_aut_TipoDocum->Errors->Count());
        $errors = ($errors || $this->s_aut_AutSri->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @5-69248522
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        if(!$this->FormSubmitted) {
            return;
        }

        if($this->FormSubmitted) {
            $this->PressedButton = "Button_DoSearch";
            if(strlen(CCGetParam("Button_DoSearch", ""))) {
                $this->PressedButton = "Button_DoSearch";
            }
        }
        $Redirect = "CoTrTr_sriaut.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick")) {
                    $Redirect = "";
                } else {
                    $Redirect = "CoTrTr_sriaut.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", Array("Button_DoSearch")));
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @5-552C1E1C
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
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if(!$this->FormSubmitted)
        {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->s_aut_IdAuxiliar->Errors->ToString();
            $Error .= $this->s_aut_TipoDocum->Errors->ToString();
            $Error .= $this->s_aut_AutSri->Errors->ToString();
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

        $this->s_aut_IdAuxiliar->Show();
        $this->s_aut_TipoDocum->Show();
        $this->s_aut_AutSri->Show();
        $this->Button_DoSearch->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End autsri_Search Class @5-FCB6E20C

class clsGridautsri_list { //autsri_list class @2-1056F7B6

//Variables @2-E63B2A12

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
    var $Sorter_aut_AutSri;
    var $Sorter_aut_TipoDocum;
    var $Sorter_aut_IdAuxiliar;
    var $Sorter_aur_RucImprenta;
    var $Sorter_aut_AutImprenta;
    var $Sorter_aut_FecEmision;
    var $Sorter_aut_FecVigencia;
    var $Sorter_aut_NroInicial;
    var $Sorter_aut_FecRegistro;
    var $Navigator;
//End Variables

//Class_Initialize Event @2-49C630A7
    function clsGridautsri_list()
    {
        global $FileName;
        $this->ComponentName = "autsri_list";
        $this->Visible = True;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid autsri_list";
        $this->ds = new clsautsri_listDataSource();
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 15;
        else
            $this->PageSize = intval($this->PageSize);
        if ($this->PageSize > 100)
            $this->PageSize = 100;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        $this->SorterName = CCGetParam("autsri_listOrder", "");
        $this->SorterDirection = CCGetParam("autsri_listDir", "");

        $this->aut_AutSri = new clsControl(ccsLink, "aut_AutSri", "aut_AutSri", ccsInteger, "", CCGetRequestParam("aut_AutSri", ccsGet));
        $this->aut_TipoDocum = new clsControl(ccsLabel, "aut_TipoDocum", "aut_TipoDocum", ccsText, "", CCGetRequestParam("aut_TipoDocum", ccsGet));
        $this->aut_IdAuxiliar = new clsControl(ccsLabel, "aut_IdAuxiliar", "aut_IdAuxiliar", ccsInteger, "", CCGetRequestParam("aut_IdAuxiliar", ccsGet));
        $this->txt_Nombre = new clsControl(ccsLabel, "txt_Nombre", "txt_Nombre", ccsText, "", CCGetRequestParam("txt_Nombre", ccsGet));
        $this->aur_RucImprenta = new clsControl(ccsLabel, "aur_RucImprenta", "aur_RucImprenta", ccsText, "", CCGetRequestParam("aur_RucImprenta", ccsGet));
        $this->aut_AutImprenta = new clsControl(ccsLabel, "aut_AutImprenta", "aut_AutImprenta", ccsInteger, "", CCGetRequestParam("aut_AutImprenta", ccsGet));
        $this->aut_FecEmision = new clsControl(ccsLabel, "aut_FecEmision", "aut_FecEmision", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("aut_FecEmision", ccsGet));
        $this->aut_FecVigencia = new clsControl(ccsLabel, "aut_FecVigencia", "aut_FecVigencia", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("aut_FecVigencia", ccsGet));
        $this->aut_NroInicial = new clsControl(ccsLabel, "aut_NroInicial", "aut_NroInicial", ccsInteger, "", CCGetRequestParam("aut_NroInicial", ccsGet));
        $this->aut_NroFinal = new clsControl(ccsLabel, "aut_NroFinal", "aut_NroFinal", ccsInteger, "", CCGetRequestParam("aut_NroFinal", ccsGet));
        $this->aut_Usuario = new clsControl(ccsLabel, "aut_Usuario", "aut_Usuario", ccsText, "", CCGetRequestParam("aut_Usuario", ccsGet));
        $this->aut_FecRegistro = new clsControl(ccsLabel, "aut_FecRegistro", "aut_FecRegistro", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("aut_FecRegistro", ccsGet));
        $this->Sorter_aut_AutSri = new clsSorter($this->ComponentName, "Sorter_aut_AutSri", $FileName);
        $this->Sorter_aut_TipoDocum = new clsSorter($this->ComponentName, "Sorter_aut_TipoDocum", $FileName);
        $this->Sorter_aut_IdAuxiliar = new clsSorter($this->ComponentName, "Sorter_aut_IdAuxiliar", $FileName);
        $this->Sorter_aur_RucImprenta = new clsSorter($this->ComponentName, "Sorter_aur_RucImprenta", $FileName);
        $this->Sorter_aut_AutImprenta = new clsSorter($this->ComponentName, "Sorter_aut_AutImprenta", $FileName);
        $this->Sorter_aut_FecEmision = new clsSorter($this->ComponentName, "Sorter_aut_FecEmision", $FileName);
        $this->Sorter_aut_FecVigencia = new clsSorter($this->ComponentName, "Sorter_aut_FecVigencia", $FileName);
        $this->Sorter_aut_NroInicial = new clsSorter($this->ComponentName, "Sorter_aut_NroInicial", $FileName);
        $this->Sorter_aut_FecRegistro = new clsSorter($this->ComponentName, "Sorter_aut_FecRegistro", $FileName);
        $this->genautsri_Insert = new clsControl(ccsLink, "genautsri_Insert", "genautsri_Insert", ccsText, "", CCGetRequestParam("genautsri_Insert", ccsGet));
        $this->genautsri_Insert->Parameters = CCGetQueryString("QueryString", Array("aut_TipoDocum", "ccsForm"));
        $this->genautsri_Insert->Page = "CoTrTr_sriaut.php";
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

//Show Method @2-8CDD34F9
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["urls_aut_IdAuxiliar"] = CCGetFromGet("s_aut_IdAuxiliar", "");
        $this->ds->Parameters["urls_aut_TipoDocum"] = CCGetFromGet("s_aut_TipoDocum", "");
        $this->ds->Parameters["urls_aut_AutSri"] = CCGetFromGet("s_aut_AutSri", "");

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
                $this->aut_AutSri->SetValue($this->ds->aut_AutSri->GetValue());
                $this->aut_AutSri->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
                $this->aut_AutSri->Parameters = CCAddParam($this->aut_AutSri->Parameters, "aut_AutSri", $this->ds->f("aut_AutSri"));
                $this->aut_AutSri->Page = "CoTrTr_sriaut.php";
                $this->aut_TipoDocum->SetValue($this->ds->aut_TipoDocum->GetValue());
                $this->aut_IdAuxiliar->SetValue($this->ds->aut_IdAuxiliar->GetValue());
                $this->txt_Nombre->SetValue($this->ds->txt_Nombre->GetValue());
                $this->aur_RucImprenta->SetValue($this->ds->aur_RucImprenta->GetValue());
                $this->aut_AutImprenta->SetValue($this->ds->aut_AutImprenta->GetValue());
                $this->aut_FecEmision->SetValue($this->ds->aut_FecEmision->GetValue());
                $this->aut_FecVigencia->SetValue($this->ds->aut_FecVigencia->GetValue());
                $this->aut_NroInicial->SetValue($this->ds->aut_NroInicial->GetValue());
                $this->aut_NroFinal->SetValue($this->ds->aut_NroFinal->GetValue());
                $this->aut_Usuario->SetValue($this->ds->aut_Usuario->GetValue());
                $this->aut_FecRegistro->SetValue($this->ds->aut_FecRegistro->GetValue());
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->aut_AutSri->Show();
                $this->aut_TipoDocum->Show();
                $this->aut_IdAuxiliar->Show();
                $this->txt_Nombre->Show();
                $this->aur_RucImprenta->Show();
                $this->aut_AutImprenta->Show();
                $this->aut_FecEmision->Show();
                $this->aut_FecVigencia->Show();
                $this->aut_NroInicial->Show();
                $this->aut_NroFinal->Show();
                $this->aut_Usuario->Show();
                $this->aut_FecRegistro->Show();
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
        $this->Navigator->PageNumber = $this->ds->AbsolutePage;
        $this->Navigator->TotalPages = $this->ds->PageCount();
        $this->Sorter_aut_AutSri->Show();
        $this->Sorter_aut_TipoDocum->Show();
        $this->Sorter_aut_IdAuxiliar->Show();
        $this->Sorter_aur_RucImprenta->Show();
        $this->Sorter_aut_AutImprenta->Show();
        $this->Sorter_aut_FecEmision->Show();
        $this->Sorter_aut_FecVigencia->Show();
        $this->Sorter_aut_NroInicial->Show();
        $this->Sorter_aut_FecRegistro->Show();
        $this->genautsri_Insert->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @2-8E22F150
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->aut_AutSri->Errors->ToString();
        $errors .= $this->aut_TipoDocum->Errors->ToString();
        $errors .= $this->aut_IdAuxiliar->Errors->ToString();
        $errors .= $this->txt_Nombre->Errors->ToString();
        $errors .= $this->aur_RucImprenta->Errors->ToString();
        $errors .= $this->aut_AutImprenta->Errors->ToString();
        $errors .= $this->aut_FecEmision->Errors->ToString();
        $errors .= $this->aut_FecVigencia->Errors->ToString();
        $errors .= $this->aut_NroInicial->Errors->ToString();
        $errors .= $this->aut_NroFinal->Errors->ToString();
        $errors .= $this->aut_Usuario->Errors->ToString();
        $errors .= $this->aut_FecRegistro->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End autsri_list Class @2-FCB6E20C

class clsautsri_listDataSource extends clsDBdatos {  //autsri_listDataSource Class @2-E98F3573

//DataSource Variables @2-8391A3A3
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $aut_AutSri;
    var $aut_TipoDocum;
    var $aut_IdAuxiliar;
    var $txt_Nombre;
    var $aur_RucImprenta;
    var $aut_AutImprenta;
    var $aut_FecEmision;
    var $aut_FecVigencia;
    var $aut_NroInicial;
    var $aut_NroFinal;
    var $aut_Usuario;
    var $aut_FecRegistro;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-998CC185
    function clsautsri_listDataSource()
    {
        $this->ErrorBlock = "Grid autsri_list";
        $this->Initialize();
        $this->aut_AutSri = new clsField("aut_AutSri", ccsInteger, "");
        $this->aut_TipoDocum = new clsField("aut_TipoDocum", ccsText, "");
        $this->aut_IdAuxiliar = new clsField("aut_IdAuxiliar", ccsInteger, "");
        $this->txt_Nombre = new clsField("txt_Nombre", ccsText, "");
        $this->aur_RucImprenta = new clsField("aur_RucImprenta", ccsText, "");
        $this->aut_AutImprenta = new clsField("aut_AutImprenta", ccsInteger, "");
        $this->aut_FecEmision = new clsField("aut_FecEmision", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->aut_FecVigencia = new clsField("aut_FecVigencia", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->aut_NroInicial = new clsField("aut_NroInicial", ccsInteger, "");
        $this->aut_NroFinal = new clsField("aut_NroFinal", ccsInteger, "");
        $this->aut_Usuario = new clsField("aut_Usuario", ccsText, "");
        $this->aut_FecRegistro = new clsField("aut_FecRegistro", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @2-09213F8B
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_aut_AutSri" => array("aut_AutSri", ""), 
            "Sorter_aut_TipoDocum" => array("aut_TipoDocum", ""), 
            "Sorter_aut_IdAuxiliar" => array("aut_IdAuxiliar", ""), 
            "Sorter_aur_RucImprenta" => array("aur_RucImprenta", ""), 
            "Sorter_aut_AutImprenta" => array("aut_AutImprenta", ""), 
            "Sorter_aut_FecEmision" => array("aut_FecEmision", ""), 
            "Sorter_aut_FecVigencia" => array("aut_FecVigencia", ""), 
            "Sorter_aut_NroInicial" => array("aut_NroInicial", ""), 
            "Sorter_aut_FecRegistro" => array("aut_FecRegistro", "")));
    }
//End SetOrder Method

//Prepare Method @2-E6D2C4F2
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_aut_IdAuxiliar", ccsInteger, "", "", $this->Parameters["urls_aut_IdAuxiliar"], 0, false);
        $this->wp->AddParameter("2", "urls_aut_TipoDocum", ccsText, "", "", $this->Parameters["urls_aut_TipoDocum"], "", false);
        $this->wp->AddParameter("3", "urls_aut_AutSri", ccsInteger, "", "", $this->Parameters["urls_aut_AutSri"], 0, false);
    }
//End Prepare Method

//Open Method @2-A3B26F67
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*) FROM genautsri left JOIN conpersonas ON per_codauxiliar = aut_IdAuxiliar";
        $this->SQL = "SELECT genautsri.*,  " .
        "       concat(per_Apellidos, ' ' , per_Nombres) AS txt_DescAuxiliar " .
        "FROM genautsri left JOIN conpersonas ON per_codauxiliar = aut_IdAuxiliar";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query($this->OptimizeSQL(CCBuildSQL($this->SQL, $this->Where, $this->Order)));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
    }
//End Open Method

//SetValues Method @2-0B4D158A
    function SetValues()
    {
        $this->aut_AutSri->SetDBValue(trim($this->f("aut_AutSri")));
        $this->aut_TipoDocum->SetDBValue($this->f("aut_TipoDocum"));
        $this->aut_IdAuxiliar->SetDBValue(trim($this->f("aut_IdAuxiliar")));
        $this->txt_Nombre->SetDBValue($this->f("txt_DescAuxiliar"));
        $this->aur_RucImprenta->SetDBValue($this->f("aut_RucImprenta"));
        $this->aut_AutImprenta->SetDBValue(trim($this->f("aut_AutImprenta")));
        $this->aut_FecEmision->SetDBValue(trim($this->f("aut_FecEmision")));
        $this->aut_FecVigencia->SetDBValue(trim($this->f("aut_FecVigencia")));
        $this->aut_NroInicial->SetDBValue(trim($this->f("aut_NroInicial")));
        $this->aut_NroFinal->SetDBValue(trim($this->f("aut_NroFinal")));
        $this->aut_Usuario->SetDBValue($this->f("aut_Usuario"));
        $this->aut_FecRegistro->SetDBValue(trim($this->f("aut_FecRegistro")));
    }
//End SetValues Method

} //End autsri_listDataSource Class @2-FCB6E20C

class clsRecordautsri_mant { //autsri_mant Class @38-F0E861DF

//Variables @38-B2F7A83E

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

    var $InsertAllowed = false;
    var $UpdateAllowed = false;
    var $DeleteAllowed = false;
    var $ReadAllowed   = false;
    var $EditMode      = false;
    var $ds;
    var $ValidatingControls;
    var $Controls;

    // Class variables
//End Variables

//Class_Initialize Event @38-8AE3B37C
    function clsRecordautsri_mant()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record autsri_mant/Error";
        $this->ds = new clsautsri_mantDataSource();
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "autsri_mant";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->aut_AutSri = new clsControl(ccsTextBox, "aut_AutSri", "Aut Aut Sri", ccsInteger, "", CCGetRequestParam("aut_AutSri", $Method));
            $this->aut_AutSri->Required = true;
            $this->aut_RucImprenta = new clsControl(ccsTextBox, "aut_RucImprenta", "Ruc de Imprenta", ccsText, "", CCGetRequestParam("aut_RucImprenta", $Method));
            $this->aut_RucImprenta->Required = true;
            $this->aut_IdAuxiliar = new clsControl(ccsTextBox, "aut_IdAuxiliar", "Aut Id Auxiliar", ccsInteger, "", CCGetRequestParam("aut_IdAuxiliar", $Method));
            $this->txt_DescAuxiliar = new clsControl(ccsTextBox, "txt_DescAuxiliar", "Nombre, Razón Social del Proveedor, Auxiliar", ccsText, "", CCGetRequestParam("txt_DescAuxiliar", $Method));
            $this->aut_TipoDocum = new clsControl(ccsListBox, "aut_TipoDocum", "Aut Tipo Docum", ccsInteger, "", CCGetRequestParam("aut_TipoDocum", $Method));
            $this->aut_TipoDocum->DSType = dsSQL;
            list($this->aut_TipoDocum->BoundColumn, $this->aut_TipoDocum->TextColumn, $this->aut_TipoDocum->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->aut_TipoDocum->ds = new clsDBdatos();
            $this->aut_TipoDocum->ds->SQL = "SELECT par_Secuencia, par_Descripcion " .
            "FROM genparametros " .
            "WHERE par_clave = 'CFDOC'";
            $this->aut_TipoDocum->Required = true;
            $this->aut_AutImprenta = new clsControl(ccsTextBox, "aut_AutImprenta", "Aut Aut Imprenta", ccsInteger, "", CCGetRequestParam("aut_AutImprenta", $Method));
            $this->aut_AutImprenta->Required = true;
            $this->aut_FecEmision = new clsControl(ccsTextBox, "aut_FecEmision", "Aut Fec Emision", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("aut_FecEmision", $Method));
            $this->DatePicker_aut_FecEmision = new clsDatePicker("DatePicker_aut_FecEmision", "autsri_mant", "aut_FecEmision");
            $this->aut_FecVigencia = new clsControl(ccsTextBox, "aut_FecVigencia", "Aut Fec Vigencia", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("aut_FecVigencia", $Method));
            $this->DatePicker_aut_FecVigencia = new clsDatePicker("DatePicker_aut_FecVigencia", "autsri_mant", "aut_FecVigencia");
            $this->aut_NroInicial = new clsControl(ccsTextBox, "aut_NroInicial", "Aut Nro Inicial", ccsInteger, "", CCGetRequestParam("aut_NroInicial", $Method));
            $this->aut_NroFinal = new clsControl(ccsTextBox, "aut_NroFinal", "Aut Nro Final", ccsInteger, "", CCGetRequestParam("aut_NroFinal", $Method));
            $this->aut_FecRegistro = new clsControl(ccsTextBox, "aut_FecRegistro", "Aut Fec Registro", ccsDate, Array("dd", "/", "mmm", "/", "yy", " ", "h", ":", "nn", " ", "AM/PM"), CCGetRequestParam("aut_FecRegistro", $Method));
            $this->aut_Usuario = new clsControl(ccsTextBox, "aut_Usuario", "Aut Usuario", ccsText, "", CCGetRequestParam("aut_Usuario", $Method));
            $this->Button_Insert = new clsButton("Button_Insert");
            $this->Button_Update = new clsButton("Button_Update");
            $this->Button_Delete = new clsButton("Button_Delete");
            if(!$this->FormSubmitted) {
                if(!is_array($this->aut_FecRegistro->Value) && !strlen($this->aut_FecRegistro->Value) && $this->aut_FecRegistro->Value !== false)
                $this->aut_FecRegistro->SetValue(time());
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @38-00D5CDAE
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->ds->Parameters["urlaut_AutSri"] = CCGetFromGet("aut_AutSri", "");
    }
//End Initialize Method

//Validate Method @38-DB1DF31D
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->aut_AutSri->Validate() && $Validation);
        $Validation = ($this->aut_RucImprenta->Validate() && $Validation);
        $Validation = ($this->aut_IdAuxiliar->Validate() && $Validation);
        $Validation = ($this->txt_DescAuxiliar->Validate() && $Validation);
        $Validation = ($this->aut_TipoDocum->Validate() && $Validation);
        $Validation = ($this->aut_AutImprenta->Validate() && $Validation);
        $Validation = ($this->aut_FecEmision->Validate() && $Validation);
        $Validation = ($this->aut_FecVigencia->Validate() && $Validation);
        $Validation = ($this->aut_NroInicial->Validate() && $Validation);
        $Validation = ($this->aut_NroFinal->Validate() && $Validation);
        $Validation = ($this->aut_FecRegistro->Validate() && $Validation);
        $Validation = ($this->aut_Usuario->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        $Validation =  $Validation && ($this->aut_AutSri->Errors->Count() == 0);
        $Validation =  $Validation && ($this->aut_RucImprenta->Errors->Count() == 0);
        $Validation =  $Validation && ($this->aut_IdAuxiliar->Errors->Count() == 0);
        $Validation =  $Validation && ($this->txt_DescAuxiliar->Errors->Count() == 0);
        $Validation =  $Validation && ($this->aut_TipoDocum->Errors->Count() == 0);
        $Validation =  $Validation && ($this->aut_AutImprenta->Errors->Count() == 0);
        $Validation =  $Validation && ($this->aut_FecEmision->Errors->Count() == 0);
        $Validation =  $Validation && ($this->aut_FecVigencia->Errors->Count() == 0);
        $Validation =  $Validation && ($this->aut_NroInicial->Errors->Count() == 0);
        $Validation =  $Validation && ($this->aut_NroFinal->Errors->Count() == 0);
        $Validation =  $Validation && ($this->aut_FecRegistro->Errors->Count() == 0);
        $Validation =  $Validation && ($this->aut_Usuario->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @38-DB9EF9E2
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->aut_AutSri->Errors->Count());
        $errors = ($errors || $this->aut_RucImprenta->Errors->Count());
        $errors = ($errors || $this->aut_IdAuxiliar->Errors->Count());
        $errors = ($errors || $this->txt_DescAuxiliar->Errors->Count());
        $errors = ($errors || $this->aut_TipoDocum->Errors->Count());
        $errors = ($errors || $this->aut_AutImprenta->Errors->Count());
        $errors = ($errors || $this->aut_FecEmision->Errors->Count());
        $errors = ($errors || $this->DatePicker_aut_FecEmision->Errors->Count());
        $errors = ($errors || $this->aut_FecVigencia->Errors->Count());
        $errors = ($errors || $this->DatePicker_aut_FecVigencia->Errors->Count());
        $errors = ($errors || $this->aut_NroInicial->Errors->Count());
        $errors = ($errors || $this->aut_NroFinal->Errors->Count());
        $errors = ($errors || $this->aut_FecRegistro->Errors->Count());
        $errors = ($errors || $this->aut_Usuario->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @38-067AF6CD
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        $this->ds->Prepare();
        if(!$this->FormSubmitted) {
            $this->EditMode = $this->ds->AllParametersSet;
            return;
        }

        if($this->FormSubmitted) {
            $this->PressedButton = $this->EditMode ? "Button_Update" : "Button_Insert";
            if(strlen(CCGetParam("Button_Insert", ""))) {
                $this->PressedButton = "Button_Insert";
            } else if(strlen(CCGetParam("Button_Update", ""))) {
                $this->PressedButton = "Button_Update";
            } else if(strlen(CCGetParam("Button_Delete", ""))) {
                $this->PressedButton = "Button_Delete";
            }
        }
        $Redirect = "CoTrTr_sriaut.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "Button_Delete") {
            if(!CCGetEvent($this->Button_Delete->CCSEvents, "OnClick") || !$this->DeleteRow()) {
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

//InsertRow Method @38-16163374
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert");
        if(!$this->InsertAllowed) return false;
        $this->ds->aut_AutSri->SetValue($this->aut_AutSri->GetValue());
        $this->ds->aut_RucImprenta->SetValue($this->aut_RucImprenta->GetValue());
        $this->ds->aut_IdAuxiliar->SetValue($this->aut_IdAuxiliar->GetValue());
        $this->ds->aut_TipoDocum->SetValue($this->aut_TipoDocum->GetValue());
        $this->ds->aut_AutImprenta->SetValue($this->aut_AutImprenta->GetValue());
        $this->ds->aut_FecEmision->SetValue($this->aut_FecEmision->GetValue());
        $this->ds->aut_FecVigencia->SetValue($this->aut_FecVigencia->GetValue());
        $this->ds->aut_NroInicial->SetValue($this->aut_NroInicial->GetValue());
        $this->ds->aut_NroFinal->SetValue($this->aut_NroFinal->GetValue());
        $this->ds->aut_FecRegistro->SetValue($this->aut_FecRegistro->GetValue());
        $this->ds->aut_Usuario->SetValue($this->aut_Usuario->GetValue());
        $this->ds->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert");
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @38-581C2DC0
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate");
        if(!$this->UpdateAllowed) return false;
        $this->ds->aut_AutSri->SetValue($this->aut_AutSri->GetValue());
        $this->ds->aut_RucImprenta->SetValue($this->aut_RucImprenta->GetValue());
        $this->ds->aut_IdAuxiliar->SetValue($this->aut_IdAuxiliar->GetValue());
        $this->ds->aut_TipoDocum->SetValue($this->aut_TipoDocum->GetValue());
        $this->ds->aut_AutImprenta->SetValue($this->aut_AutImprenta->GetValue());
        $this->ds->aut_FecEmision->SetValue($this->aut_FecEmision->GetValue());
        $this->ds->aut_FecVigencia->SetValue($this->aut_FecVigencia->GetValue());
        $this->ds->aut_NroInicial->SetValue($this->aut_NroInicial->GetValue());
        $this->ds->aut_NroFinal->SetValue($this->aut_NroFinal->GetValue());
        $this->ds->Update();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate");
        return (!$this->CheckErrors());
    }
//End UpdateRow Method

//DeleteRow Method @38-91867A4A
    function DeleteRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeDelete");
        if(!$this->DeleteAllowed) return false;
        $this->ds->Delete();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterDelete");
        return (!$this->CheckErrors());
    }
//End DeleteRow Method

//Show Method @38-E02922B9
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->aut_TipoDocum->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if($this->EditMode)
        {
            $this->ds->open();
            if($this->Errors->Count() == 0)
            {
                if($this->ds->Errors->Count() > 0)
                {
                    echo "Error in Record autsri_mant";
                }
                else if($this->ds->next_record())
                {
                    $this->ds->SetValues();
                    if(!$this->FormSubmitted)
                    {
                        $this->aut_AutSri->SetValue($this->ds->aut_AutSri->GetValue());
                        $this->aut_RucImprenta->SetValue($this->ds->aut_RucImprenta->GetValue());
                        $this->aut_IdAuxiliar->SetValue($this->ds->aut_IdAuxiliar->GetValue());
                        $this->txt_DescAuxiliar->SetValue($this->ds->txt_DescAuxiliar->GetValue());
                        $this->aut_TipoDocum->SetValue($this->ds->aut_TipoDocum->GetValue());
                        $this->aut_AutImprenta->SetValue($this->ds->aut_AutImprenta->GetValue());
                        $this->aut_FecEmision->SetValue($this->ds->aut_FecEmision->GetValue());
                        $this->aut_FecVigencia->SetValue($this->ds->aut_FecVigencia->GetValue());
                        $this->aut_NroInicial->SetValue($this->ds->aut_NroInicial->GetValue());
                        $this->aut_NroFinal->SetValue($this->ds->aut_NroFinal->GetValue());
                        $this->aut_FecRegistro->SetValue($this->ds->aut_FecRegistro->GetValue());
                        $this->aut_Usuario->SetValue($this->ds->aut_Usuario->GetValue());
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
            $Error .= $this->aut_AutSri->Errors->ToString();
            $Error .= $this->aut_RucImprenta->Errors->ToString();
            $Error .= $this->aut_IdAuxiliar->Errors->ToString();
            $Error .= $this->txt_DescAuxiliar->Errors->ToString();
            $Error .= $this->aut_TipoDocum->Errors->ToString();
            $Error .= $this->aut_AutImprenta->Errors->ToString();
            $Error .= $this->aut_FecEmision->Errors->ToString();
            $Error .= $this->DatePicker_aut_FecEmision->Errors->ToString();
            $Error .= $this->aut_FecVigencia->Errors->ToString();
            $Error .= $this->DatePicker_aut_FecVigencia->Errors->ToString();
            $Error .= $this->aut_NroInicial->Errors->ToString();
            $Error .= $this->aut_NroFinal->Errors->ToString();
            $Error .= $this->aut_FecRegistro->Errors->ToString();
            $Error .= $this->aut_Usuario->Errors->ToString();
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

        $this->aut_AutSri->Show();
        $this->aut_RucImprenta->Show();
        $this->aut_IdAuxiliar->Show();
        $this->txt_DescAuxiliar->Show();
        $this->aut_TipoDocum->Show();
        $this->aut_AutImprenta->Show();
        $this->aut_FecEmision->Show();
        $this->DatePicker_aut_FecEmision->Show();
        $this->aut_FecVigencia->Show();
        $this->DatePicker_aut_FecVigencia->Show();
        $this->aut_NroInicial->Show();
        $this->aut_NroFinal->Show();
        $this->aut_FecRegistro->Show();
        $this->aut_Usuario->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

} //End autsri_mant Class @38-FCB6E20C

class clsautsri_mantDataSource extends clsDBdatos {  //autsri_mantDataSource Class @38-EA133B7A

//DataSource Variables @38-0338BFB6
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $InsertParameters;
    var $UpdateParameters;
    var $DeleteParameters;
    var $wp;
    var $AllParametersSet;


    // Datasource fields
    var $aut_AutSri;
    var $aut_RucImprenta;
    var $aut_IdAuxiliar;
    var $txt_DescAuxiliar;
    var $aut_TipoDocum;
    var $aut_AutImprenta;
    var $aut_FecEmision;
    var $aut_FecVigencia;
    var $aut_NroInicial;
    var $aut_NroFinal;
    var $aut_FecRegistro;
    var $aut_Usuario;
//End DataSource Variables

//DataSourceClass_Initialize Event @38-47AE3A73
    function clsautsri_mantDataSource()
    {
        $this->ErrorBlock = "Record autsri_mant/Error";
        $this->Initialize();
        $this->aut_AutSri = new clsField("aut_AutSri", ccsInteger, "");
        $this->aut_RucImprenta = new clsField("aut_RucImprenta", ccsText, "");
        $this->aut_IdAuxiliar = new clsField("aut_IdAuxiliar", ccsInteger, "");
        $this->txt_DescAuxiliar = new clsField("txt_DescAuxiliar", ccsText, "");
        $this->aut_TipoDocum = new clsField("aut_TipoDocum", ccsInteger, "");
        $this->aut_AutImprenta = new clsField("aut_AutImprenta", ccsInteger, "");
        $this->aut_FecEmision = new clsField("aut_FecEmision", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->aut_FecVigencia = new clsField("aut_FecVigencia", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->aut_NroInicial = new clsField("aut_NroInicial", ccsInteger, "");
        $this->aut_NroFinal = new clsField("aut_NroFinal", ccsInteger, "");
        $this->aut_FecRegistro = new clsField("aut_FecRegistro", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->aut_Usuario = new clsField("aut_Usuario", ccsText, "");

    }
//End DataSourceClass_Initialize Event

//Prepare Method @38-B41341C7
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlaut_AutSri", ccsInteger, "", "", $this->Parameters["urlaut_AutSri"], 0, false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
    }
//End Prepare Method

//Open Method @38-F17B1020
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->SQL = "SELECT genautsri.*,  " .
        "       concat(per_Apellidos, ' ' , per_Nombres) AS txt_DescAuxiliar " .
        "FROM genautsri left JOIN conpersonas ON per_codauxiliar = aut_IdAuxiliar " .
        "WHERE aut_AutSri = '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsInteger) . "'";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->PageSize = 1;
        $this->query($this->OptimizeSQL(CCBuildSQL($this->SQL, $this->Where, $this->Order)));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
    }
//End Open Method

//SetValues Method @38-097EBFD3
    function SetValues()
    {
        $this->aut_AutSri->SetDBValue(trim($this->f("aut_AutSri")));
        $this->aut_RucImprenta->SetDBValue($this->f("aut_RucImprenta"));
        $this->aut_IdAuxiliar->SetDBValue(trim($this->f("aut_IdAuxiliar")));
        $this->txt_DescAuxiliar->SetDBValue($this->f("txt_DescAuxiliar"));
        $this->aut_TipoDocum->SetDBValue(trim($this->f("aut_TipoDocum")));
        $this->aut_AutImprenta->SetDBValue(trim($this->f("aut_AutImprenta")));
        $this->aut_FecEmision->SetDBValue(trim($this->f("aut_FecEmision")));
        $this->aut_FecVigencia->SetDBValue(trim($this->f("aut_FecVigencia")));
        $this->aut_NroInicial->SetDBValue(trim($this->f("aut_NroInicial")));
        $this->aut_NroFinal->SetDBValue(trim($this->f("aut_NroFinal")));
        $this->aut_FecRegistro->SetDBValue(trim($this->f("aut_FecRegistro")));
        $this->aut_Usuario->SetDBValue($this->f("aut_Usuario"));
    }
//End SetValues Method

//Insert Method @38-30303E77
    function Insert()
    {
        $this->CmdExecution = true;
        $this->cp["aut_AutSri"] = new clsSQLParameter("ctrlaut_AutSri", ccsInteger, "", "", $this->aut_AutSri->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["aut_RucImprenta"] = new clsSQLParameter("ctrlaut_RucImprenta", ccsText, "", "", $this->aut_RucImprenta->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["aut_IdAuxiliar"] = new clsSQLParameter("ctrlaut_IdAuxiliar", ccsInteger, "", "", $this->aut_IdAuxiliar->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["aut_TipoDocum"] = new clsSQLParameter("ctrlaut_TipoDocum", ccsText, "", "", $this->aut_TipoDocum->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["aut_AutImprenta"] = new clsSQLParameter("ctrlaut_AutImprenta", ccsInteger, "", "", $this->aut_AutImprenta->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["aut_FecEmision"] = new clsSQLParameter("ctrlaut_FecEmision", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->aut_FecEmision->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["aut_FecVigencia"] = new clsSQLParameter("ctrlaut_FecVigencia", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->aut_FecVigencia->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["aut_NroInicial"] = new clsSQLParameter("ctrlaut_NroInicial", ccsInteger, "", "", $this->aut_NroInicial->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["aut_NroFinal"] = new clsSQLParameter("ctrlaut_NroFinal", ccsInteger, "", "", $this->aut_NroFinal->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["aut_FecRegistro"] = new clsSQLParameter("ctrlaut_FecRegistro", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->aut_FecRegistro->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["aut_Usuario"] = new clsSQLParameter("ctrlaut_Usuario", ccsText, "", "", $this->aut_Usuario->GetValue(), "", false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO genautsri ("
             . "aut_AutSri, "
             . "aut_RucImprenta, "
             . "aut_IdAuxiliar, "
             . "aut_TipoDocum, "
             . "aut_AutImprenta, "
             . "aut_FecEmision, "
             . "aut_FecVigencia, "
             . "aut_NroInicial, "
             . "aut_NroFinal, "
             . "aut_FecRegistro, "
             . "aut_Usuario"
             . ") VALUES ("
             . $this->ToSQL($this->cp["aut_AutSri"]->GetDBValue(), $this->cp["aut_AutSri"]->DataType) . ", "
             . $this->ToSQL($this->cp["aut_RucImprenta"]->GetDBValue(), $this->cp["aut_RucImprenta"]->DataType) . ", "
             . $this->ToSQL($this->cp["aut_IdAuxiliar"]->GetDBValue(), $this->cp["aut_IdAuxiliar"]->DataType) . ", "
             . $this->ToSQL($this->cp["aut_TipoDocum"]->GetDBValue(), $this->cp["aut_TipoDocum"]->DataType) . ", "
             . $this->ToSQL($this->cp["aut_AutImprenta"]->GetDBValue(), $this->cp["aut_AutImprenta"]->DataType) . ", "
             . $this->ToSQL($this->cp["aut_FecEmision"]->GetDBValue(), $this->cp["aut_FecEmision"]->DataType) . ", "
             . $this->ToSQL($this->cp["aut_FecVigencia"]->GetDBValue(), $this->cp["aut_FecVigencia"]->DataType) . ", "
             . $this->ToSQL($this->cp["aut_NroInicial"]->GetDBValue(), $this->cp["aut_NroInicial"]->DataType) . ", "
             . $this->ToSQL($this->cp["aut_NroFinal"]->GetDBValue(), $this->cp["aut_NroFinal"]->DataType) . ", "
             . $this->ToSQL($this->cp["aut_FecRegistro"]->GetDBValue(), $this->cp["aut_FecRegistro"]->DataType) . ", "
             . $this->ToSQL($this->cp["aut_Usuario"]->GetDBValue(), $this->cp["aut_Usuario"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        }
        $this->close();
    }
//End Insert Method

//Update Method @38-31189F73
    function Update()
    {
        $this->CmdExecution = true;
        $this->cp["aut_AutSri"] = new clsSQLParameter("ctrlaut_AutSri", ccsInteger, "", "", $this->aut_AutSri->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["aut_RucImprenta"] = new clsSQLParameter("ctrlaut_RucImprenta", ccsText, "", "", $this->aut_RucImprenta->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["aut_IdAuxiliar"] = new clsSQLParameter("ctrlaut_IdAuxiliar", ccsInteger, "", "", $this->aut_IdAuxiliar->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["aut_TipoDocum"] = new clsSQLParameter("ctrlaut_TipoDocum", ccsText, "", "", $this->aut_TipoDocum->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["aut_AutImprenta"] = new clsSQLParameter("ctrlaut_AutImprenta", ccsInteger, "", "", $this->aut_AutImprenta->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["aut_FecEmision"] = new clsSQLParameter("ctrlaut_FecEmision", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->aut_FecEmision->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["aut_FecVigencia"] = new clsSQLParameter("ctrlaut_FecVigencia", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->aut_FecVigencia->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["aut_NroInicial"] = new clsSQLParameter("ctrlaut_NroInicial", ccsInteger, "", "", $this->aut_NroInicial->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["aut_NroFinal"] = new clsSQLParameter("ctrlaut_NroFinal", ccsInteger, "", "", $this->aut_NroFinal->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["aut_Usuario"] = new clsSQLParameter("sesg_user", ccsText, "", "", CCGetSession("g_user"), "", false, $this->ErrorBlock);
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlaut_AutSri", ccsInteger, "", "", CCGetFromGet("aut_AutSri", ""), "", true);
        if(!$wp->AllParamsSet()) {
            $this->Errors->addError("");
        }
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $wp->Criterion[1] = $wp->Operation(opEqual, "aut_AutSri", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),true);
        $Where = 
             $wp->Criterion[1];
        $this->SQL = "UPDATE genautsri SET "
             . "aut_AutSri=" . $this->ToSQL($this->cp["aut_AutSri"]->GetDBValue(), $this->cp["aut_AutSri"]->DataType) . ", "
             . "aut_RucImprenta=" . $this->ToSQL($this->cp["aut_RucImprenta"]->GetDBValue(), $this->cp["aut_RucImprenta"]->DataType) . ", "
             . "aut_IdAuxiliar=" . $this->ToSQL($this->cp["aut_IdAuxiliar"]->GetDBValue(), $this->cp["aut_IdAuxiliar"]->DataType) . ", "
             . "aut_TipoDocum=" . $this->ToSQL($this->cp["aut_TipoDocum"]->GetDBValue(), $this->cp["aut_TipoDocum"]->DataType) . ", "
             . "aut_AutImprenta=" . $this->ToSQL($this->cp["aut_AutImprenta"]->GetDBValue(), $this->cp["aut_AutImprenta"]->DataType) . ", "
             . "aut_FecEmision=" . $this->ToSQL($this->cp["aut_FecEmision"]->GetDBValue(), $this->cp["aut_FecEmision"]->DataType) . ", "
             . "aut_FecVigencia=" . $this->ToSQL($this->cp["aut_FecVigencia"]->GetDBValue(), $this->cp["aut_FecVigencia"]->DataType) . ", "
             . "aut_NroInicial=" . $this->ToSQL($this->cp["aut_NroInicial"]->GetDBValue(), $this->cp["aut_NroInicial"]->DataType) . ", "
             . "aut_NroFinal=" . $this->ToSQL($this->cp["aut_NroFinal"]->GetDBValue(), $this->cp["aut_NroFinal"]->DataType) . ", "
             . "aut_Usuario=" . $this->ToSQL($this->cp["aut_Usuario"]->GetDBValue(), $this->cp["aut_Usuario"]->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        }
        $this->close();
    }
//End Update Method

//Delete Method @38-6EDCFE61
    function Delete()
    {
        $this->CmdExecution = true;
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlaut_AutSri", ccsInteger, "", "", CCGetFromGet("aut_AutSri", ""), "", true);
        if(!$wp->AllParamsSet()) {
            $this->Errors->addError("");
        }
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $wp->Criterion[1] = $wp->Operation(opEqual, "aut_autSri", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),true);
        $Where = 
             $wp->Criterion[1];
        $this->SQL = "DELETE FROM genautsri";
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        }
        $this->close();
    }
//End Delete Method

} //End autsri_mantDataSource Class @38-FCB6E20C

//Initialize Page @1-F9C0C3DA
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

$FileName = "CoTrTr_sriaut.php";
$Redirect = "";
$TemplateFileName = "CoTrTr_sriaut.html";
$BlockToParse = "main";
$TemplateEncoding = "";
$FileEncoding = "";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-1F6F3F56
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera("../De_Files/");
$Cabecera->BindEvents();
$Cabecera->Initialize();
$autsri_Search = new clsRecordautsri_Search();
$autsri_list = new clsGridautsri_list();
$autsri_mant = new clsRecordautsri_mant();
$autsri_list->Initialize();
$autsri_mant->Initialize();

// Events
include("./CoTrTr_sriaut_events.php");
BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");

if ($Charset)
    header("Content-Type: text/html; charset=" . $Charset);
//End Initialize Objects

//Initialize HTML Template @1-51DB8464
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main", $TemplateEncoding);
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-63F95AC1
$Cabecera->Operations();
$autsri_Search->Operation();
$autsri_mant->Operation();
//End Execute Components

//Go to destination page @1-947E3B48
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    $DBdatos->close();
    header("Location: " . $Redirect);
    $Cabecera->Class_Terminate();
    unset($Cabecera);
    unset($autsri_Search);
    unset($autsri_list);
    unset($autsri_mant);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-728781F1
$Cabecera->Show("Cabecera");
$autsri_Search->Show();
$autsri_list->Show();
$autsri_mant->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
$MAPGRE2J0R5F5M2E = "<center><font face=\"Arial\"><small>&#71;en&#101;r&#97;t&#101;&#100; <!-- CCS -->with <!-- SCC -->CodeCha&#114;&#103;e <!-- CCS -->&#83;&#116;udio.</small></font></center>";
if(preg_match("/<\/body>/i", $main_block)) {
    $main_block = preg_replace("/<\/body>/i", $MAPGRE2J0R5F5M2E . "</body>", $main_block);
} else if(preg_match("/<\/html>/i", $main_block) && !preg_match("/<\/frameset>/i", $main_block)) {
    $main_block = preg_replace("/<\/html>/i", $MAPGRE2J0R5F5M2E . "</html>", $main_block);
} else if(!preg_match("/<\/frameset>/i", $main_block)) {
    $main_block .= $MAPGRE2J0R5F5M2E;
}
echo $main_block;
//End Show Page

//Unload Page @1-080EA3E7
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBdatos->close();
$Cabecera->Class_Terminate();
unset($Cabecera);
unset($autsri_Search);
unset($autsri_list);
unset($autsri_mant);
unset($Tpl);
//End Unload Page


?>
