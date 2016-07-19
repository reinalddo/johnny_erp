<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @126-49BEB8FC
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation

class clsRecordliqembarques_qry { //liqembarques_qry Class @22-EA343610

//Variables @22-CB19EB75

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

//Class_Initialize Event @22-F8361A76
    function clsRecordliqembarques_qry()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record liqembarques_qry/Error";
        $this->ReadAllowed = false;
        $this->InsertAllowed = false;
        $this->UpdateAllowed = false;
        $this->DeleteAllowed = false;
        if($this->Visible)
        {
            $this->ComponentName = "liqembarques_qry";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->pMensj = new clsControl(ccsLabel, "pMensj", "pMensj", ccsText, "", CCGetRequestParam("pMensj", $Method));
            $this->s_keyword = new clsControl(ccsTextBox, "s_keyword", "s_keyword", ccsText, "", CCGetRequestParam("s_keyword", $Method));
            $this->ClearParameters = new clsControl(ccsLink, "ClearParameters", "ClearParameters", ccsText, "", CCGetRequestParam("ClearParameters", $Method));
            $this->ClearParameters->Parameters = CCGetQueryString("QueryString", Array("s_keyword", "ccsForm"));
            $this->ClearParameters->Page = "LiAdEm_search.php";
            $this->Button_DoSearch = new clsButton("Button_DoSearch");
        }
    }
//End Class_Initialize Event

//Validate Method @22-F230E30A
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_keyword->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @22-E7F39E68
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->pMensj->Errors->Count());
        $errors = ($errors || $this->s_keyword->Errors->Count());
        $errors = ($errors || $this->ClearParameters->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @22-85D46FE2
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
        $Redirect = "LiAdEm_search.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "Button_DoSearch") {
            if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick")) {
                $Redirect = "";
            } else {
                $Redirect = "LiAdEm_search.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", Array("Button_DoSearch")), CCGetQueryString("QueryString", Array("s_keyword", "ccsForm")));
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @22-CEE35C29
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
            $Error .= $this->pMensj->Errors->ToString();
            $Error .= $this->s_keyword->Errors->ToString();
            $Error .= $this->ClearParameters->Errors->ToString();
            $Error .= $this->Errors->ToString();
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $CCSForm = $this->EditMode ? $this->ComponentName . ":" . "Edit" : $this->ComponentName;
        if($this->FormSubmitted || CCGetFromGet("ccsForm")) {
            $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        } else {
            $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("All", ""), "ccsForm", $CCSForm);
        }
        $Tpl->SetVar("Action", $this->HTMLFormAction);
        $Tpl->SetVar("HTMLFormName", $this->ComponentName);
        $Tpl->SetVar("HTMLFormEnctype", $this->FormEnctype);

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        $this->pMensj->Show();
        $this->s_keyword->Show();
        $this->ClearParameters->Show();
        $this->Button_DoSearch->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End liqembarques_qry Class @22-FCB6E20C

class clsGridliqembarques_list { //liqembarques_list class @3-37CB26E6

//Variables @3-0FE7F148

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
    var $Sorter_emb_RefOperativa;
    var $Sorter_emb_AnoOperacion;
    var $Sorter_buq_Abreviatura;
    var $Sorter_emb_FecZarpe;
    var $Sorter_par_Descripcion;
    var $Sorter_emb_Descripcion1;
    var $Sorter_emb_FecInicio;
    var $Sorter_emb_SemInicio;
    var $Sorter_emb_Estado;
//End Variables

//Class_Initialize Event @3-50F673D6
    function clsGridliqembarques_list()
    {
        global $FileName;
        $this->ComponentName = "liqembarques_list";
        $this->Visible = True;
        $this->IsAltRow = false;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid liqembarques_list";
        $this->ds = new clsliqembarques_listDataSource();
        $this->PageSize = 25;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        $this->SorterName = CCGetParam("liqembarques_listOrder", "");
        $this->SorterDirection = CCGetParam("liqembarques_listDir", "");

        $this->emb_RefOperativa = new clsControl(ccsHidden, "emb_RefOperativa", "emb_RefOperativa", ccsInteger, "", CCGetRequestParam("emb_RefOperativa", ccsGet));
        $this->Button1 = new clsButton("Button1");
        $this->emb_AnoOperacion = new clsControl(ccsTextBox, "emb_AnoOperacion", "emb_AnoOperacion", ccsInteger, Array(True, 0, "", ",", False, Array("#", "#", "0", "0"), "", 1, True, ""), CCGetRequestParam("emb_AnoOperacion", ccsGet));
        $this->buq_Abreviatura = new clsControl(ccsHidden, "buq_Abreviatura", "buq_Abreviatura", ccsText, "", CCGetRequestParam("buq_Abreviatura", ccsGet));
        $this->buq_Descripcion = new clsControl(ccsTextBox, "buq_Descripcion", "buq_Descripcion", ccsText, "", CCGetRequestParam("buq_Descripcion", ccsGet));
        $this->emb_CodVapor = new clsControl(ccsHidden, "emb_CodVapor", "emb_CodVapor", ccsInteger, "", CCGetRequestParam("emb_CodVapor", ccsGet));
        $this->emb_NumViaje = new clsControl(ccsTextBox, "emb_NumViaje", "emb_NumViaje", ccsInteger, Array(True, 0, "", "", False, Array("#", "#", "0", "0"), "", 1, True, ""), CCGetRequestParam("emb_NumViaje", ccsGet));
        $this->txtEmbarque = new clsControl(ccsHidden, "txtEmbarque", "txtEmbarque", ccsText, "", CCGetRequestParam("txtEmbarque", ccsGet));
        $this->emb_FecZarpe = new clsControl(ccsTextBox, "emb_FecZarpe", "emb_FecZarpe", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("emb_FecZarpe", ccsGet));
        $this->par_Descripcion = new clsControl(ccsTextBox, "par_Descripcion", "par_Descripcion", ccsText, "", CCGetRequestParam("par_Descripcion", ccsGet));
        $this->emb_CodMarca = new clsControl(ccsHidden, "emb_CodMarca", "emb_CodMarca", ccsInteger, "", CCGetRequestParam("emb_CodMarca", ccsGet));
        $this->emb_Descripcion1 = new clsControl(ccsTextBox, "emb_Descripcion1", "emb_Descripcion1", ccsText, "", CCGetRequestParam("emb_Descripcion1", ccsGet));
        $this->emb_FecInicio = new clsControl(ccsTextBox, "emb_FecInicio", "emb_FecInicio", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("emb_FecInicio", ccsGet));
        $this->emb_FecTermino = new clsControl(ccsTextBox, "emb_FecTermino", "emb_FecTermino", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("emb_FecTermino", ccsGet));
        $this->emb_SemInicio = new clsControl(ccsTextBox, "emb_SemInicio", "emb_SemInicio", ccsInteger, Array(True, 0, "", "", False, Array("0", "0"), "", 1, True, ""), CCGetRequestParam("emb_SemInicio", ccsGet));
        $this->emb_SemTermino = new clsControl(ccsTextBox, "emb_SemTermino", "emb_SemTermino", ccsInteger, Array(True, 0, "", "", False, Array("0", "0"), "", 1, True, ""), CCGetRequestParam("emb_SemTermino", ccsGet));
        $this->emb_Estado = new clsControl(ccsTextBox, "emb_Estado", "emb_Estado", ccsInteger, "", CCGetRequestParam("emb_Estado", ccsGet));
        $this->emb_CodProducto = new clsControl(ccsHidden, "emb_CodProducto", "emb_CodProducto", ccsText, "", CCGetRequestParam("emb_CodProducto", ccsGet));
        $this->emb_CodCaja = new clsControl(ccsHidden, "emb_CodCaja", "emb_CodCaja", ccsText, "", CCGetRequestParam("emb_CodCaja", ccsGet));
        $this->emb_CodCompon1 = new clsControl(ccsHidden, "emb_CodCompon1", "emb_CodCompon1", ccsText, "", CCGetRequestParam("emb_CodCompon1", ccsGet));
        $this->emb_CodCompon2 = new clsControl(ccsHidden, "emb_CodCompon2", "emb_CodCompon2", ccsText, "", CCGetRequestParam("emb_CodCompon2", ccsGet));
        $this->emb_CodCompon3 = new clsControl(ccsHidden, "emb_CodCompon3", "emb_CodCompon3", ccsText, "", CCGetRequestParam("emb_CodCompon3", ccsGet));
        $this->emb_CodCompon4 = new clsControl(ccsHidden, "emb_CodCompon4", "emb_CodCompon4", ccsText, "", CCGetRequestParam("emb_CodCompon4", ccsGet));
        $this->emb_PrecOficial = new clsControl(ccsHidden, "emb_PrecOficial", "emb_PrecOficial", ccsText, "", CCGetRequestParam("emb_PrecOficial", ccsGet));
        $this->emb_DifPrecio = new clsControl(ccsHidden, "emb_DifPrecio", "emb_DifPrecio", ccsText, "", CCGetRequestParam("emb_DifPrecio", ccsGet));
        $this->emb_CodPuerto = new clsControl(ccsHidden, "emb_CodPuerto", "emb_CodPuerto", ccsText, "", CCGetRequestParam("emb_CodPuerto", ccsGet));
        $this->Alt_emb_RefOperativa = new clsControl(ccsHidden, "Alt_emb_RefOperativa", "Alt_emb_RefOperativa", ccsInteger, "", CCGetRequestParam("Alt_emb_RefOperativa", ccsGet));
        $this->Button2 = new clsButton("Button2");
        $this->Alt_emb_AnoOperacion = new clsControl(ccsTextBox, "Alt_emb_AnoOperacion", "Alt_emb_AnoOperacion", ccsInteger, Array(True, 0, "", ",", False, Array("#", "#", "0", "0"), "", 1, True, ""), CCGetRequestParam("Alt_emb_AnoOperacion", ccsGet));
        $this->Alt_buq_Abreviatura = new clsControl(ccsHidden, "Alt_buq_Abreviatura", "Alt_buq_Abreviatura", ccsText, "", CCGetRequestParam("Alt_buq_Abreviatura", ccsGet));
        $this->Alt_buq_Descripcion = new clsControl(ccsTextBox, "Alt_buq_Descripcion", "Alt_buq_Descripcion", ccsText, "", CCGetRequestParam("Alt_buq_Descripcion", ccsGet));
        $this->Alt_emb_CodVapor = new clsControl(ccsHidden, "Alt_emb_CodVapor", "Alt_emb_CodVapor", ccsInteger, "", CCGetRequestParam("Alt_emb_CodVapor", ccsGet));
        $this->Alt_emb_NumViaje = new clsControl(ccsTextBox, "Alt_emb_NumViaje", "Alt_emb_NumViaje", ccsInteger, Array(True, 0, "", "", False, Array("#", "#", "0", "0"), "", 1, True, ""), CCGetRequestParam("Alt_emb_NumViaje", ccsGet));
        $this->AlttxtEmbarque = new clsControl(ccsHidden, "AlttxtEmbarque", "AlttxtEmbarque", ccsText, "", CCGetRequestParam("AlttxtEmbarque", ccsGet));
        $this->Alt_emb_FecZarpe = new clsControl(ccsTextBox, "Alt_emb_FecZarpe", "Alt_emb_FecZarpe", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("Alt_emb_FecZarpe", ccsGet));
        $this->Alt_par_Descripcion = new clsControl(ccsTextBox, "Alt_par_Descripcion", "Alt_par_Descripcion", ccsText, "", CCGetRequestParam("Alt_par_Descripcion", ccsGet));
        $this->Alt_emb_CodMarca = new clsControl(ccsHidden, "Alt_emb_CodMarca", "Alt_emb_CodMarca", ccsInteger, "", CCGetRequestParam("Alt_emb_CodMarca", ccsGet));
        $this->Alt_emb_Descripcion1 = new clsControl(ccsTextBox, "Alt_emb_Descripcion1", "Alt_emb_Descripcion1", ccsText, "", CCGetRequestParam("Alt_emb_Descripcion1", ccsGet));
        $this->Alt_emb_FecInicio = new clsControl(ccsTextBox, "Alt_emb_FecInicio", "Alt_emb_FecInicio", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("Alt_emb_FecInicio", ccsGet));
        $this->Alt_emb_FecTermino = new clsControl(ccsTextBox, "Alt_emb_FecTermino", "Alt_emb_FecTermino", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("Alt_emb_FecTermino", ccsGet));
        $this->Alt_emb_SemInicio = new clsControl(ccsTextBox, "Alt_emb_SemInicio", "Alt_emb_SemInicio", ccsInteger, Array(True, 0, "", "", False, Array("0", "0"), "", 1, True, ""), CCGetRequestParam("Alt_emb_SemInicio", ccsGet));
        $this->Alt_emb_SemTermino = new clsControl(ccsTextBox, "Alt_emb_SemTermino", "Alt_emb_SemTermino", ccsInteger, Array(True, 0, "", "", False, Array("0", "0"), "", 1, True, ""), CCGetRequestParam("Alt_emb_SemTermino", ccsGet));
        $this->Alt_emb_Estado = new clsControl(ccsTextBox, "Alt_emb_Estado", "Alt_emb_Estado", ccsInteger, "", CCGetRequestParam("Alt_emb_Estado", ccsGet));
        $this->Alt_emb_CodProducto = new clsControl(ccsHidden, "Alt_emb_CodProducto", "Alt_emb_CodProducto", ccsText, "", CCGetRequestParam("Alt_emb_CodProducto", ccsGet));
        $this->Alt_emb_CodCaja = new clsControl(ccsHidden, "Alt_emb_CodCaja", "Alt_emb_CodCaja", ccsText, "", CCGetRequestParam("Alt_emb_CodCaja", ccsGet));
        $this->Alt_emb_CodCompon1 = new clsControl(ccsHidden, "Alt_emb_CodCompon1", "Alt_emb_CodCompon1", ccsText, "", CCGetRequestParam("Alt_emb_CodCompon1", ccsGet));
        $this->Alt_emb_CodCompon2 = new clsControl(ccsHidden, "Alt_emb_CodCompon2", "Alt_emb_CodCompon2", ccsText, "", CCGetRequestParam("Alt_emb_CodCompon2", ccsGet));
        $this->Alt_emb_CodCompon3 = new clsControl(ccsHidden, "Alt_emb_CodCompon3", "Alt_emb_CodCompon3", ccsText, "", CCGetRequestParam("Alt_emb_CodCompon3", ccsGet));
        $this->Alt_emb_CodCompon4 = new clsControl(ccsHidden, "Alt_emb_CodCompon4", "Alt_emb_CodCompon4", ccsText, "", CCGetRequestParam("Alt_emb_CodCompon4", ccsGet));
        $this->Alt_emb_PrecOficial = new clsControl(ccsHidden, "Alt_emb_PrecOficial", "Alt_emb_PrecOficial", ccsText, "", CCGetRequestParam("Alt_emb_PrecOficial", ccsGet));
        $this->Alt_emb_DifPrecio = new clsControl(ccsHidden, "Alt_emb_DifPrecio", "Alt_emb_DifPrecio", ccsText, "", CCGetRequestParam("Alt_emb_DifPrecio", ccsGet));
        $this->Alt_emb_CodPuerto = new clsControl(ccsHidden, "Alt_emb_CodPuerto", "Alt_emb_CodPuerto", ccsText, "", CCGetRequestParam("Alt_emb_CodPuerto", ccsGet));
        $this->liqembarques_liqbuques_ge_TotalRecords = new clsControl(ccsLabel, "liqembarques_liqbuques_ge_TotalRecords", "liqembarques_liqbuques_ge_TotalRecords", ccsText, "", CCGetRequestParam("liqembarques_liqbuques_ge_TotalRecords", ccsGet));
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpCentered);
        $this->btNuevo = new clsButton("btNuevo");
        $this->btCierre = new clsButton("btCierre");
        $this->Sorter_emb_RefOperativa = new clsSorter($this->ComponentName, "Sorter_emb_RefOperativa", $FileName);
        $this->Sorter_emb_AnoOperacion = new clsSorter($this->ComponentName, "Sorter_emb_AnoOperacion", $FileName);
        $this->Sorter_buq_Abreviatura = new clsSorter($this->ComponentName, "Sorter_buq_Abreviatura", $FileName);
        $this->Sorter_emb_FecZarpe = new clsSorter($this->ComponentName, "Sorter_emb_FecZarpe", $FileName);
        $this->Sorter_par_Descripcion = new clsSorter($this->ComponentName, "Sorter_par_Descripcion", $FileName);
        $this->Sorter_emb_Descripcion1 = new clsSorter($this->ComponentName, "Sorter_emb_Descripcion1", $FileName);
        $this->Sorter_emb_FecInicio = new clsSorter($this->ComponentName, "Sorter_emb_FecInicio", $FileName);
        $this->Sorter_emb_SemInicio = new clsSorter($this->ComponentName, "Sorter_emb_SemInicio", $FileName);
        $this->Sorter_emb_Estado = new clsSorter($this->ComponentName, "Sorter_emb_Estado", $FileName);
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

//Show Method @3-2F91E3F0
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
                    $this->emb_RefOperativa->SetValue($this->ds->emb_RefOperativa->GetValue());
                    $this->emb_AnoOperacion->SetValue($this->ds->emb_AnoOperacion->GetValue());
                    $this->buq_Abreviatura->SetValue($this->ds->buq_Abreviatura->GetValue());
                    $this->buq_Descripcion->SetValue($this->ds->buq_Descripcion->GetValue());
                    $this->emb_CodVapor->SetValue($this->ds->emb_CodVapor->GetValue());
                    $this->emb_NumViaje->SetValue($this->ds->emb_NumViaje->GetValue());
                    $this->txtEmbarque->SetValue($this->ds->txtEmbarque->GetValue());
                    $this->emb_FecZarpe->SetValue($this->ds->emb_FecZarpe->GetValue());
                    $this->par_Descripcion->SetValue($this->ds->par_Descripcion->GetValue());
                    $this->emb_CodMarca->SetValue($this->ds->emb_CodMarca->GetValue());
                    $this->emb_Descripcion1->SetValue($this->ds->emb_Descripcion1->GetValue());
                    $this->emb_FecInicio->SetValue($this->ds->emb_FecInicio->GetValue());
                    $this->emb_FecTermino->SetValue($this->ds->emb_FecTermino->GetValue());
                    $this->emb_SemInicio->SetValue($this->ds->emb_SemInicio->GetValue());
                    $this->emb_SemTermino->SetValue($this->ds->emb_SemTermino->GetValue());
                    $this->emb_Estado->SetValue($this->ds->emb_Estado->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->emb_RefOperativa->Show();
                    $this->Button1->Show();
                    $this->emb_AnoOperacion->Show();
                    $this->buq_Abreviatura->Show();
                    $this->buq_Descripcion->Show();
                    $this->emb_CodVapor->Show();
                    $this->emb_NumViaje->Show();
                    $this->txtEmbarque->Show();
                    $this->emb_FecZarpe->Show();
                    $this->par_Descripcion->Show();
                    $this->emb_CodMarca->Show();
                    $this->emb_Descripcion1->Show();
                    $this->emb_FecInicio->Show();
                    $this->emb_FecTermino->Show();
                    $this->emb_SemInicio->Show();
                    $this->emb_SemTermino->Show();
                    $this->emb_Estado->Show();
                    $this->emb_CodProducto->Show();
                    $this->emb_CodCaja->Show();
                    $this->emb_CodCompon1->Show();
                    $this->emb_CodCompon2->Show();
                    $this->emb_CodCompon3->Show();
                    $this->emb_CodCompon4->Show();
                    $this->emb_PrecOficial->Show();
                    $this->emb_DifPrecio->Show();
                    $this->emb_CodPuerto->Show();
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                    $Tpl->parse("Row", true);
                }
                else
                {
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/AltRow";
                    $this->Alt_emb_RefOperativa->SetValue($this->ds->Alt_emb_RefOperativa->GetValue());
                    $this->Alt_emb_AnoOperacion->SetValue($this->ds->Alt_emb_AnoOperacion->GetValue());
                    $this->Alt_buq_Abreviatura->SetValue($this->ds->Alt_buq_Abreviatura->GetValue());
                    $this->Alt_buq_Descripcion->SetValue($this->ds->Alt_buq_Descripcion->GetValue());
                    $this->Alt_emb_CodVapor->SetValue($this->ds->Alt_emb_CodVapor->GetValue());
                    $this->Alt_emb_NumViaje->SetValue($this->ds->Alt_emb_NumViaje->GetValue());
                    $this->AlttxtEmbarque->SetValue($this->ds->AlttxtEmbarque->GetValue());
                    $this->Alt_emb_FecZarpe->SetValue($this->ds->Alt_emb_FecZarpe->GetValue());
                    $this->Alt_par_Descripcion->SetValue($this->ds->Alt_par_Descripcion->GetValue());
                    $this->Alt_emb_CodMarca->SetValue($this->ds->Alt_emb_CodMarca->GetValue());
                    $this->Alt_emb_Descripcion1->SetValue($this->ds->Alt_emb_Descripcion1->GetValue());
                    $this->Alt_emb_FecInicio->SetValue($this->ds->Alt_emb_FecInicio->GetValue());
                    $this->Alt_emb_FecTermino->SetValue($this->ds->Alt_emb_FecTermino->GetValue());
                    $this->Alt_emb_SemInicio->SetValue($this->ds->Alt_emb_SemInicio->GetValue());
                    $this->Alt_emb_SemTermino->SetValue($this->ds->Alt_emb_SemTermino->GetValue());
                    $this->Alt_emb_Estado->SetValue($this->ds->Alt_emb_Estado->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->Alt_emb_RefOperativa->Show();
                    $this->Button2->Show();
                    $this->Alt_emb_AnoOperacion->Show();
                    $this->Alt_buq_Abreviatura->Show();
                    $this->Alt_buq_Descripcion->Show();
                    $this->Alt_emb_CodVapor->Show();
                    $this->Alt_emb_NumViaje->Show();
                    $this->AlttxtEmbarque->Show();
                    $this->Alt_emb_FecZarpe->Show();
                    $this->Alt_par_Descripcion->Show();
                    $this->Alt_emb_CodMarca->Show();
                    $this->Alt_emb_Descripcion1->Show();
                    $this->Alt_emb_FecInicio->Show();
                    $this->Alt_emb_FecTermino->Show();
                    $this->Alt_emb_SemInicio->Show();
                    $this->Alt_emb_SemTermino->Show();
                    $this->Alt_emb_Estado->Show();
                    $this->Alt_emb_CodProducto->Show();
                    $this->Alt_emb_CodCaja->Show();
                    $this->Alt_emb_CodCompon1->Show();
                    $this->Alt_emb_CodCompon2->Show();
                    $this->Alt_emb_CodCompon3->Show();
                    $this->Alt_emb_CodCompon4->Show();
                    $this->Alt_emb_PrecOficial->Show();
                    $this->Alt_emb_DifPrecio->Show();
                    $this->Alt_emb_CodPuerto->Show();
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
        $this->liqembarques_liqbuques_ge_TotalRecords->Show();
        $this->Navigator->Show();
        $this->btNuevo->Show();
        $this->btCierre->Show();
        $this->Sorter_emb_RefOperativa->Show();
        $this->Sorter_emb_AnoOperacion->Show();
        $this->Sorter_buq_Abreviatura->Show();
        $this->Sorter_emb_FecZarpe->Show();
        $this->Sorter_par_Descripcion->Show();
        $this->Sorter_emb_Descripcion1->Show();
        $this->Sorter_emb_FecInicio->Show();
        $this->Sorter_emb_SemInicio->Show();
        $this->Sorter_emb_Estado->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @3-9F820575
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->emb_RefOperativa->Errors->ToString();
        $errors .= $this->emb_AnoOperacion->Errors->ToString();
        $errors .= $this->buq_Abreviatura->Errors->ToString();
        $errors .= $this->buq_Descripcion->Errors->ToString();
        $errors .= $this->emb_CodVapor->Errors->ToString();
        $errors .= $this->emb_NumViaje->Errors->ToString();
        $errors .= $this->txtEmbarque->Errors->ToString();
        $errors .= $this->emb_FecZarpe->Errors->ToString();
        $errors .= $this->par_Descripcion->Errors->ToString();
        $errors .= $this->emb_CodMarca->Errors->ToString();
        $errors .= $this->emb_Descripcion1->Errors->ToString();
        $errors .= $this->emb_FecInicio->Errors->ToString();
        $errors .= $this->emb_FecTermino->Errors->ToString();
        $errors .= $this->emb_SemInicio->Errors->ToString();
        $errors .= $this->emb_SemTermino->Errors->ToString();
        $errors .= $this->emb_Estado->Errors->ToString();
        $errors .= $this->emb_CodProducto->Errors->ToString();
        $errors .= $this->emb_CodCaja->Errors->ToString();
        $errors .= $this->emb_CodCompon1->Errors->ToString();
        $errors .= $this->emb_CodCompon2->Errors->ToString();
        $errors .= $this->emb_CodCompon3->Errors->ToString();
        $errors .= $this->emb_CodCompon4->Errors->ToString();
        $errors .= $this->emb_PrecOficial->Errors->ToString();
        $errors .= $this->emb_DifPrecio->Errors->ToString();
        $errors .= $this->emb_CodPuerto->Errors->ToString();
        $errors .= $this->Alt_emb_RefOperativa->Errors->ToString();
        $errors .= $this->Alt_emb_AnoOperacion->Errors->ToString();
        $errors .= $this->Alt_buq_Abreviatura->Errors->ToString();
        $errors .= $this->Alt_buq_Descripcion->Errors->ToString();
        $errors .= $this->Alt_emb_CodVapor->Errors->ToString();
        $errors .= $this->Alt_emb_NumViaje->Errors->ToString();
        $errors .= $this->AlttxtEmbarque->Errors->ToString();
        $errors .= $this->Alt_emb_FecZarpe->Errors->ToString();
        $errors .= $this->Alt_par_Descripcion->Errors->ToString();
        $errors .= $this->Alt_emb_CodMarca->Errors->ToString();
        $errors .= $this->Alt_emb_Descripcion1->Errors->ToString();
        $errors .= $this->Alt_emb_FecInicio->Errors->ToString();
        $errors .= $this->Alt_emb_FecTermino->Errors->ToString();
        $errors .= $this->Alt_emb_SemInicio->Errors->ToString();
        $errors .= $this->Alt_emb_SemTermino->Errors->ToString();
        $errors .= $this->Alt_emb_Estado->Errors->ToString();
        $errors .= $this->Alt_emb_CodProducto->Errors->ToString();
        $errors .= $this->Alt_emb_CodCaja->Errors->ToString();
        $errors .= $this->Alt_emb_CodCompon1->Errors->ToString();
        $errors .= $this->Alt_emb_CodCompon2->Errors->ToString();
        $errors .= $this->Alt_emb_CodCompon3->Errors->ToString();
        $errors .= $this->Alt_emb_CodCompon4->Errors->ToString();
        $errors .= $this->Alt_emb_PrecOficial->Errors->ToString();
        $errors .= $this->Alt_emb_DifPrecio->Errors->ToString();
        $errors .= $this->Alt_emb_CodPuerto->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End liqembarques_list Class @3-FCB6E20C

class clsliqembarques_listDataSource extends clsDBdatos {  //liqembarques_listDataSource Class @3-1EBB2C2A

//DataSource Variables @3-1F2B8D4E
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $emb_RefOperativa;
    var $emb_AnoOperacion;
    var $buq_Abreviatura;
    var $buq_Descripcion;
    var $emb_CodVapor;
    var $emb_NumViaje;
    var $txtEmbarque;
    var $emb_FecZarpe;
    var $par_Descripcion;
    var $emb_CodMarca;
    var $emb_Descripcion1;
    var $emb_FecInicio;
    var $emb_FecTermino;
    var $emb_SemInicio;
    var $emb_SemTermino;
    var $emb_Estado;
    var $Alt_emb_RefOperativa;
    var $Alt_emb_AnoOperacion;
    var $Alt_buq_Abreviatura;
    var $Alt_buq_Descripcion;
    var $Alt_emb_CodVapor;
    var $Alt_emb_NumViaje;
    var $AlttxtEmbarque;
    var $Alt_emb_FecZarpe;
    var $Alt_par_Descripcion;
    var $Alt_emb_CodMarca;
    var $Alt_emb_Descripcion1;
    var $Alt_emb_FecInicio;
    var $Alt_emb_FecTermino;
    var $Alt_emb_SemInicio;
    var $Alt_emb_SemTermino;
    var $Alt_emb_Estado;
//End DataSource Variables

//Class_Initialize Event @3-7026F244
    function clsliqembarques_listDataSource()
    {
        $this->ErrorBlock = "Grid liqembarques_list";
        $this->Initialize();
        $this->emb_RefOperativa = new clsField("emb_RefOperativa", ccsInteger, "");
        $this->emb_AnoOperacion = new clsField("emb_AnoOperacion", ccsInteger, "");
        $this->buq_Abreviatura = new clsField("buq_Abreviatura", ccsText, "");
        $this->buq_Descripcion = new clsField("buq_Descripcion", ccsText, "");
        $this->emb_CodVapor = new clsField("emb_CodVapor", ccsInteger, "");
        $this->emb_NumViaje = new clsField("emb_NumViaje", ccsInteger, "");
        $this->txtEmbarque = new clsField("txtEmbarque", ccsText, "");
        $this->emb_FecZarpe = new clsField("emb_FecZarpe", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->par_Descripcion = new clsField("par_Descripcion", ccsText, "");
        $this->emb_CodMarca = new clsField("emb_CodMarca", ccsInteger, "");
        $this->emb_Descripcion1 = new clsField("emb_Descripcion1", ccsText, "");
        $this->emb_FecInicio = new clsField("emb_FecInicio", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss", ".", "S"));
        $this->emb_FecTermino = new clsField("emb_FecTermino", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->emb_SemInicio = new clsField("emb_SemInicio", ccsInteger, "");
        $this->emb_SemTermino = new clsField("emb_SemTermino", ccsInteger, "");
        $this->emb_Estado = new clsField("emb_Estado", ccsInteger, "");
        $this->Alt_emb_RefOperativa = new clsField("Alt_emb_RefOperativa", ccsInteger, "");
        $this->Alt_emb_AnoOperacion = new clsField("Alt_emb_AnoOperacion", ccsInteger, "");
        $this->Alt_buq_Abreviatura = new clsField("Alt_buq_Abreviatura", ccsText, "");
        $this->Alt_buq_Descripcion = new clsField("Alt_buq_Descripcion", ccsText, "");
        $this->Alt_emb_CodVapor = new clsField("Alt_emb_CodVapor", ccsInteger, "");
        $this->Alt_emb_NumViaje = new clsField("Alt_emb_NumViaje", ccsInteger, "");
        $this->AlttxtEmbarque = new clsField("AlttxtEmbarque", ccsText, "");
        $this->Alt_emb_FecZarpe = new clsField("Alt_emb_FecZarpe", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->Alt_par_Descripcion = new clsField("Alt_par_Descripcion", ccsText, "");
        $this->Alt_emb_CodMarca = new clsField("Alt_emb_CodMarca", ccsInteger, "");
        $this->Alt_emb_Descripcion1 = new clsField("Alt_emb_Descripcion1", ccsText, "");
        $this->Alt_emb_FecInicio = new clsField("Alt_emb_FecInicio", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->Alt_emb_FecTermino = new clsField("Alt_emb_FecTermino", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->Alt_emb_SemInicio = new clsField("Alt_emb_SemInicio", ccsInteger, "");
        $this->Alt_emb_SemTermino = new clsField("Alt_emb_SemTermino", ccsInteger, "");
        $this->Alt_emb_Estado = new clsField("Alt_emb_Estado", ccsInteger, "");

    }
//End Class_Initialize Event

//SetOrder Method @3-4B4FF964
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_emb_RefOperativa" => array("emb_RefOperativa", ""), 
            "Sorter_emb_AnoOperacion" => array("emb_AnoOperacion", ""), 
            "Sorter_buq_Abreviatura" => array("buq_Abreviatura", ""), 
            "Sorter_emb_FecZarpe" => array("emb_FecZarpe", ""), 
            "Sorter_par_Descripcion" => array("par_Descripcion", ""), 
            "Sorter_emb_Descripcion1" => array("emb_Descripcion1", ""), 
            "Sorter_emb_FecInicio" => array("emb_FecInicio", ""), 
            "Sorter_emb_SemInicio" => array("emb_SemInicio", ""), 
            "Sorter_emb_Estado" => array("emb_Estado", "")));
    }
//End SetOrder Method

//Prepare Method @3-2CCC2EDE
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_keyword", ccsText, "", "", $this->Parameters["urls_keyword"], "", false);
    }
//End Prepare Method

//Open Method @3-81D8D3E6
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*) FROM (liqembarques LEFT JOIN liqbuques ON liqembarques.emb_CodVapor = liqbuques.buq_CodBuque)  " .
        "     LEFT JOIN genparametros ON par_clave='IMARCA' AND liqembarques.emb_CodMarca = genparametros.par_Secuencia " .
        "WHERE ( " .
        "      emb_AnoOperacion LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "' OR  " .
        "      emb_CodVapor LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "' OR  " .
        "      emb_NumViaje LIKE  '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "' OR  " .
        "      par_Descripcion LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' OR  " .
        "      buq_Abreviatura LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' OR  " .
        "      buq_Descripcion LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%')";
        $this->SQL = "SELECT liqembarques.*, buq_Abreviatura, buq_Descripcion, par_Descripcion, " .
        "       concat(left(buq_descripcion,15),' - ', emb_NumViaje, ' ', ' (Sem ',emb_SemInicio,' a ', emb_SemTermino,')') as txtEmbarque " .
        "FROM (liqembarques LEFT JOIN liqbuques ON liqembarques.emb_CodVapor = liqbuques.buq_CodBuque)  " .
        "     LEFT JOIN genparametros ON par_clave='IMARCA' AND liqembarques.emb_CodMarca = genparametros.par_Secuencia " .
        "WHERE  ( " .
        "      emb_AnoOperacion LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "' OR  " .
        "      emb_CodVapor LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "' OR  " .
        "      emb_NumViaje LIKE  '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "' OR  " .
        "      par_Descripcion LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' OR  " .
        "      buq_Abreviatura LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' OR  " .
        "      buq_Descripcion LIKE '" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%')";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue($this->CountSQL, $this);
        $this->query(CCBuildSQL($this->SQL, "", $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @3-5E9243CF
    function SetValues()
    {
        $this->emb_RefOperativa->SetDBValue(trim($this->f("emb_RefOperativa")));
        $this->emb_AnoOperacion->SetDBValue(trim($this->f("emb_AnoOperacion")));
        $this->buq_Abreviatura->SetDBValue($this->f("buq_Abreviatura"));
        $this->buq_Descripcion->SetDBValue($this->f("buq_Descripcion"));
        $this->emb_CodVapor->SetDBValue(trim($this->f("emb_CodVapor")));
        $this->emb_NumViaje->SetDBValue(trim($this->f("emb_NumViaje")));
        $this->txtEmbarque->SetDBValue($this->f("txtEmbarque"));
        $this->emb_FecZarpe->SetDBValue(trim($this->f("emb_FecZarpe")));
        $this->par_Descripcion->SetDBValue($this->f("par_Descripcion"));
        $this->emb_CodMarca->SetDBValue(trim($this->f("emb_CodMarca")));
        $this->emb_Descripcion1->SetDBValue($this->f("emb_Descripcion1"));
        $this->emb_FecInicio->SetDBValue(trim($this->f("emb_FecInicio")));
        $this->emb_FecTermino->SetDBValue(trim($this->f("emb_FecTermino")));
        $this->emb_SemInicio->SetDBValue(trim($this->f("emb_SemInicio")));
        $this->emb_SemTermino->SetDBValue(trim($this->f("emb_SemTermino")));
        $this->emb_Estado->SetDBValue(trim($this->f("emb_Estado")));
        $this->Alt_emb_RefOperativa->SetDBValue(trim($this->f("emb_RefOperativa")));
        $this->Alt_emb_AnoOperacion->SetDBValue(trim($this->f("emb_AnoOperacion")));
        $this->Alt_buq_Abreviatura->SetDBValue($this->f("buq_Abreviatura"));
        $this->Alt_buq_Descripcion->SetDBValue($this->f("buq_Descripcion"));
        $this->Alt_emb_CodVapor->SetDBValue(trim($this->f("emb_CodVapor")));
        $this->Alt_emb_NumViaje->SetDBValue(trim($this->f("emb_NumViaje")));
        $this->AlttxtEmbarque->SetDBValue($this->f("txtEmbarque"));
        $this->Alt_emb_FecZarpe->SetDBValue(trim($this->f("emb_FecZarpe")));
        $this->Alt_par_Descripcion->SetDBValue($this->f("par_Descripcion"));
        $this->Alt_emb_CodMarca->SetDBValue(trim($this->f("emb_CodMarca")));
        $this->Alt_emb_Descripcion1->SetDBValue($this->f("emb_Descripcion1"));
        $this->Alt_emb_FecInicio->SetDBValue(trim($this->f("emb_FecInicio")));
        $this->Alt_emb_FecTermino->SetDBValue(trim($this->f("emb_FecTermino")));
        $this->Alt_emb_SemInicio->SetDBValue(trim($this->f("emb_SemInicio")));
        $this->Alt_emb_SemTermino->SetDBValue(trim($this->f("emb_SemTermino")));
        $this->Alt_emb_Estado->SetDBValue(trim($this->f("emb_Estado")));
    }
//End SetValues Method

} //End liqembarques_listDataSource Class @3-FCB6E20C

//Initialize Page @1-0C0EB674
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

$FileName = "LiAdEm_search.php";
$Redirect = "";
$TemplateFileName = "LiAdEm_search.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-42C43691
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
$liqembarques_qry = new clsRecordliqembarques_qry();
$liqembarques_list = new clsGridliqembarques_list();
$liqembarques_list->Initialize();

// Events
include("./LiAdEm_search_events.php");
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

//Execute Components @1-D65C0B97
$Cabecera->Operations();
$liqembarques_qry->Operation();
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

//Show Page @1-23FB8B59
$Cabecera->Show("Cabecera");
$liqembarques_qry->Show();
$liqembarques_list->Show();
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
