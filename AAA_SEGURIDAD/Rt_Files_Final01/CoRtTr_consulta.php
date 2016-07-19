<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files
include "General.inc.php";
include "GenUti.inc.php";
//Include Page implementation @52-11FBC0D5
include_once(RelativePath . "/Rt_Files/../De_Files/Cabecera.php");
//End Include Page implementation

class clsRecordtransacc_qry { //transacc_qry Class @3-628F0034

//Variables @3-B2F7A83E

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

//Class_Initialize Event @3-D8DA935D
    function clsRecordtransacc_qry()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record transacc_qry/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "transacc_qry";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->s_com_TipoComp = new clsControl(ccsTextBox, "s_com_TipoComp", "s_com_TipoComp", ccsText, "", CCGetRequestParam("s_com_TipoComp", $Method));
            $this->s_com_NumComp = new clsControl(ccsTextBox, "s_com_NumComp", "s_com_NumComp", ccsInteger, "", CCGetRequestParam("s_com_NumComp", $Method));
            $this->s_com_FecTrans = new clsControl(ccsTextBox, "s_com_FecTrans", "s_com_FecTrans", ccsText, "", CCGetRequestParam("s_com_FecTrans", $Method));
            $this->DatePicker_s_com_FecTrans = new clsDatePicker("DatePicker_s_com_FecTrans", "transacc_qry", "s_com_FecTrans");
            $this->s_com_Emisor = new clsControl(ccsTextBox, "s_com_Emisor", "s_com_Emisor", ccsInteger, "", CCGetRequestParam("s_com_Emisor", $Method));
            $this->s_com_Receptor = new clsControl(ccsTextBox, "s_com_Receptor", "s_com_Receptor", ccsText, "", CCGetRequestParam("s_com_Receptor", $Method));
            $this->s_par_Valor1 = new clsControl(ccsListBox, "s_par_Valor1", "s_par_Valor1", ccsText, "", CCGetRequestParam("s_par_Valor1", $Method));
            $this->s_par_Valor1->DSType = dsSQL;
            list($this->s_par_Valor1->BoundColumn, $this->s_par_Valor1->TextColumn, $this->s_par_Valor1->DBFormat) = array("par_Valor1", "tmp_Descr", "");
            $this->s_par_Valor1->ds = new clsDBdatos();
            $this->s_par_Valor1->ds->SQL = "SELECT distinct par_Valor1, concat(par_Valor1, ' - ', par_Valor2, '%') " .
            "        as tmp_Descr " .
            "FROM genparametros " .
            "WHERE par_Clave = 'CRTFTE' " .
            "";
            $this->s_par_Valor1->ds->Order = "2
;";
            $this->s_com_Concepto = new clsControl(ccsTextBox, "s_com_Concepto", "s_com_Concepto", ccsMemo, "", CCGetRequestParam("s_com_Concepto", $Method));
            $this->Button_DoSearch = new clsButton("Button_DoSearch");
        }
    }
//End Class_Initialize Event

//Validate Method @3-1385CA34
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_com_TipoComp->Validate() && $Validation);
        $Validation = ($this->s_com_NumComp->Validate() && $Validation);
        $Validation = ($this->s_com_FecTrans->Validate() && $Validation);
        $Validation = ($this->s_com_Emisor->Validate() && $Validation);
        $Validation = ($this->s_com_Receptor->Validate() && $Validation);
        $Validation = ($this->s_par_Valor1->Validate() && $Validation);
        $Validation = ($this->s_com_Concepto->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        $Validation =  $Validation && ($this->s_com_TipoComp->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_com_NumComp->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_com_FecTrans->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_com_Emisor->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_com_Receptor->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_par_Valor1->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_com_Concepto->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @3-085EF76A
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_com_TipoComp->Errors->Count());
        $errors = ($errors || $this->s_com_NumComp->Errors->Count());
        $errors = ($errors || $this->s_com_FecTrans->Errors->Count());
        $errors = ($errors || $this->DatePicker_s_com_FecTrans->Errors->Count());
        $errors = ($errors || $this->s_com_Emisor->Errors->Count());
        $errors = ($errors || $this->s_com_Receptor->Errors->Count());
        $errors = ($errors || $this->s_par_Valor1->Errors->Count());
        $errors = ($errors || $this->s_com_Concepto->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @3-F004D781
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
        $Redirect = "CoRtTr_consulta.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick")) {
                    $Redirect = "";
                } else {
                    $Redirect = "CoRtTr_consulta.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", Array("Button_DoSearch")));
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @3-9FD54CAE
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->s_par_Valor1->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if(!$this->FormSubmitted)
        {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->s_com_TipoComp->Errors->ToString();
            $Error .= $this->s_com_NumComp->Errors->ToString();
            $Error .= $this->s_com_FecTrans->Errors->ToString();
            $Error .= $this->DatePicker_s_com_FecTrans->Errors->ToString();
            $Error .= $this->s_com_Emisor->Errors->ToString();
            $Error .= $this->s_com_Receptor->Errors->ToString();
            $Error .= $this->s_par_Valor1->Errors->ToString();
            $Error .= $this->s_com_Concepto->Errors->ToString();
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

        $this->s_com_TipoComp->Show();
        $this->s_com_NumComp->Show();
        $this->s_com_FecTrans->Show();
        $this->DatePicker_s_com_FecTrans->Show();
        $this->s_com_Emisor->Show();
        $this->s_com_Receptor->Show();
        $this->s_par_Valor1->Show();
        $this->s_com_Concepto->Show();
        $this->Button_DoSearch->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End transacc_qry Class @3-FCB6E20C

class clsGridtransacc_list { //transacc_list class @2-DE63FAF3

//Variables @2-BF1FFC63

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
    var $Navigator1;
    var $Sorter_com_TipoComp;
    var $Sorter_com_FecContab;
    var $Sorter_com_CodReceptor;
    var $Sorter_com_Receptor;
    var $TIPO;
    var $VALOR_RETENIDO;
    var $Navigator;
//End Variables

//Class_Initialize Event @2-06602FF0
    function clsGridtransacc_list()
    {
        global $FileName;
        $this->ComponentName = "transacc_list";
        $this->Visible = True;
        $this->IsAltRow = false;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid transacc_list";
        $this->ds = new clstransacc_listDataSource();
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 25;
        else
            $this->PageSize = intval($this->PageSize);
        if ($this->PageSize > 25)
            $this->PageSize = 25;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        $this->SorterName = CCGetParam("transacc_listOrder", "");
        $this->SorterDirection = CCGetParam("transacc_listDir", "");

        $this->com_TipoComp = new clsControl(ccsLabel, "com_TipoComp", "com_TipoComp", ccsText, "", CCGetRequestParam("com_TipoComp", ccsGet));
        $this->tmp_ID = new clsControl(ccsHidden, "tmp_ID", "tmp_ID", ccsText, "", CCGetRequestParam("tmp_ID", ccsGet));
        $this->com_RegNumero = new clsControl(ccsHidden, "com_RegNumero", "com_RegNumero", ccsText, "", CCGetRequestParam("com_RegNumero", ccsGet));
        $this->com_NumComp = new clsControl(ccsLabel, "com_NumComp", "com_NumComp", ccsInteger, "", CCGetRequestParam("com_NumComp", ccsGet));
        $this->com_FecContab = new clsControl(ccsLabel, "com_FecContab", "com_FecContab", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("com_FecContab", ccsGet));
        $this->com_CodReceptor = new clsControl(ccsLabel, "com_CodReceptor", "com_CodReceptor", ccsInteger, "", CCGetRequestParam("com_CodReceptor", ccsGet));
        $this->com_Receptor = new clsControl(ccsLabel, "com_Receptor", "com_Receptor", ccsText, "", CCGetRequestParam("com_Receptor", ccsGet));
        $this->tmp_Identif = new clsControl(ccsLabel, "tmp_Identif", "tmp_Identif", ccsText, "", CCGetRequestParam("tmp_Identif", ccsGet));
        $this->tmp_CodRetenc = new clsControl(ccsLabel, "tmp_CodRetenc", "tmp_CodRetenc", ccsText, "", CCGetRequestParam("tmp_CodRetenc", ccsGet));
        $this->tmp_BaseImp = new clsControl(ccsLabel, "tmp_BaseImp", "tmp_BaseImp", ccsFloat, Array(False, 2, ".", "", False, "", "", 1, True, ""), CCGetRequestParam("tmp_BaseImp", ccsGet));
        $this->tmp_ValReten = new clsControl(ccsLabel, "tmp_ValReten", "tmp_ValReten", ccsFloat, Array(False, 2, ".", "", False, "", "", 1, True, ""), CCGetRequestParam("tmp_ValReten", ccsGet));
        $this->com_Concepto = new clsControl(ccsTextArea, "com_Concepto", "com_Concepto", ccsText, "", CCGetRequestParam("com_Concepto", ccsGet));
        $this->Alt_com_TipoComp = new clsControl(ccsLabel, "Alt_com_TipoComp", "Alt_com_TipoComp", ccsText, "", CCGetRequestParam("Alt_com_TipoComp", ccsGet));
        $this->Alt_tmp_ID = new clsControl(ccsHidden, "Alt_tmp_ID", "Alt_tmp_ID", ccsText, "", CCGetRequestParam("Alt_tmp_ID", ccsGet));
        $this->Alt_com_RegNumero = new clsControl(ccsHidden, "Alt_com_RegNumero", "Alt_com_RegNumero", ccsText, "", CCGetRequestParam("Alt_com_RegNumero", ccsGet));
        $this->Alt_com_NumComp = new clsControl(ccsLabel, "Alt_com_NumComp", "Alt_com_NumComp", ccsInteger, "", CCGetRequestParam("Alt_com_NumComp", ccsGet));
        $this->Alt_com_FecContab = new clsControl(ccsLabel, "Alt_com_FecContab", "Alt_com_FecContab", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("Alt_com_FecContab", ccsGet));
        $this->Alt_com_CodReceptor = new clsControl(ccsLabel, "Alt_com_CodReceptor", "Alt_com_CodReceptor", ccsInteger, "", CCGetRequestParam("Alt_com_CodReceptor", ccsGet));
        $this->Alt_com_Receptor = new clsControl(ccsLabel, "Alt_com_Receptor", "Alt_com_Receptor", ccsText, "", CCGetRequestParam("Alt_com_Receptor", ccsGet));
        $this->Alt_tmp_Identif = new clsControl(ccsLabel, "Alt_tmp_Identif", "Alt_tmp_Identif", ccsText, "", CCGetRequestParam("Alt_tmp_Identif", ccsGet));
        $this->Alt_tmp_CodRetenc = new clsControl(ccsLabel, "Alt_tmp_CodRetenc", "Alt_tmp_CodRetenc", ccsInteger, "", CCGetRequestParam("Alt_tmp_CodRetenc", ccsGet));
        $this->Alt_tmp_BaseImp = new clsControl(ccsLabel, "Alt_tmp_BaseImp", "Alt_tmp_BaseImp", ccsFloat, Array(False, 2, ".", "", False, "", "", 1, True, ""), CCGetRequestParam("Alt_tmp_BaseImp", ccsGet));
        $this->Alt_tmp_ValReten = new clsControl(ccsLabel, "Alt_tmp_ValReten", "Alt_tmp_ValReten", ccsFloat, Array(False, 2, ".", "", False, "", "", 1, True, ""), CCGetRequestParam("Alt_tmp_ValReten", ccsGet));
        $this->Alt_com_Concepto = new clsControl(ccsTextArea, "Alt_com_Concepto", "Alt_com_Concepto", ccsMemo, "", CCGetRequestParam("Alt_com_Concepto", ccsGet));
        $this->concomprobantes_TotalRecords = new clsControl(ccsLabel, "concomprobantes_TotalRecords", "concomprobantes_TotalRecords", ccsText, "", CCGetRequestParam("concomprobantes_TotalRecords", ccsGet));
        $this->Navigator1 = new clsNavigator($this->ComponentName, "Navigator1", $FileName, 10, tpCentered);
        $this->Sorter_com_TipoComp = new clsSorter($this->ComponentName, "Sorter_com_TipoComp", $FileName);
        $this->Sorter_com_FecContab = new clsSorter($this->ComponentName, "Sorter_com_FecContab", $FileName);
        $this->Sorter_com_CodReceptor = new clsSorter($this->ComponentName, "Sorter_com_CodReceptor", $FileName);
        $this->Sorter_com_Receptor = new clsSorter($this->ComponentName, "Sorter_com_Receptor", $FileName);
        $this->TIPO = new clsSorter($this->ComponentName, "TIPO", $FileName);
        $this->VALOR_RETENIDO = new clsSorter($this->ComponentName, "VALOR_RETENIDO", $FileName);
        $this->lbl_Imponible = new clsControl(ccsLabel, "lbl_Imponible", "lbl_Imponible", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("lbl_Imponible", ccsGet));
        $this->lbl_Retenido = new clsControl(ccsLabel, "lbl_Retenido", "lbl_Retenido", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("lbl_Retenido", ccsGet));
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpCentered);
        $this->Button1 = new clsButton("Button1");
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

//Show Method @2-6F642B7A
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["urls_com_TipoComp"] = CCGetFromGet("s_com_TipoComp", "");
        $this->ds->Parameters["urls_com_NumComp"] = CCGetFromGet("s_com_NumComp", "");
        $this->ds->Parameters["urls_com_Emisor"] = CCGetFromGet("s_com_Emisor", "");
        $this->ds->Parameters["urls_com_Receptor"] = CCGetFromGet("s_com_Receptor", "");
        $this->ds->Parameters["urls_com_Concepto"] = CCGetFromGet("s_com_Concepto", "");
        $this->ds->Parameters["urls_par_Valor1"] = CCGetFromGet("s_par_Valor1", "");

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
                    $this->com_TipoComp->SetValue($this->ds->com_TipoComp->GetValue());
                    $this->tmp_ID->SetValue($this->ds->tmp_ID->GetValue());
                    $this->com_RegNumero->SetValue($this->ds->com_RegNumero->GetValue());
                    $this->com_NumComp->SetValue($this->ds->com_NumComp->GetValue());
                    $this->com_FecContab->SetValue($this->ds->com_FecContab->GetValue());
                    $this->com_CodReceptor->SetValue($this->ds->com_CodReceptor->GetValue());
                    $this->com_Receptor->SetValue($this->ds->com_Receptor->GetValue());
                    $this->tmp_Identif->SetValue($this->ds->tmp_Identif->GetValue());
                    $this->tmp_CodRetenc->SetValue($this->ds->tmp_CodRetenc->GetValue());
                    $this->tmp_BaseImp->SetValue($this->ds->tmp_BaseImp->GetValue());
                    $this->tmp_ValReten->SetValue($this->ds->tmp_ValReten->GetValue());
                    $this->com_Concepto->SetValue($this->ds->com_Concepto->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->com_TipoComp->Show();
                    $this->tmp_ID->Show();
                    $this->com_RegNumero->Show();
                    $this->com_NumComp->Show();
                    $this->com_FecContab->Show();
                    $this->com_CodReceptor->Show();
                    $this->com_Receptor->Show();
                    $this->tmp_Identif->Show();
                    $this->tmp_CodRetenc->Show();
                    $this->tmp_BaseImp->Show();
                    $this->tmp_ValReten->Show();
                    $this->com_Concepto->Show();
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                    $Tpl->parse("Row", true);
                }
                else
                {
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/AltRow";
                    $this->Alt_com_TipoComp->SetValue($this->ds->Alt_com_TipoComp->GetValue());
                    $this->Alt_tmp_ID->SetValue($this->ds->Alt_tmp_ID->GetValue());
                    $this->Alt_com_RegNumero->SetValue($this->ds->Alt_com_RegNumero->GetValue());
                    $this->Alt_com_NumComp->SetValue($this->ds->Alt_com_NumComp->GetValue());
                    $this->Alt_com_FecContab->SetValue($this->ds->Alt_com_FecContab->GetValue());
                    $this->Alt_com_CodReceptor->SetValue($this->ds->Alt_com_CodReceptor->GetValue());
                    $this->Alt_com_Receptor->SetValue($this->ds->Alt_com_Receptor->GetValue());
                    $this->Alt_tmp_Identif->SetValue($this->ds->Alt_tmp_Identif->GetValue());
                    $this->Alt_tmp_CodRetenc->SetValue($this->ds->Alt_tmp_CodRetenc->GetValue());
                    $this->Alt_tmp_BaseImp->SetValue($this->ds->Alt_tmp_BaseImp->GetValue());
                    $this->Alt_tmp_ValReten->SetValue($this->ds->Alt_tmp_ValReten->GetValue());
                    $this->Alt_com_Concepto->SetValue($this->ds->Alt_com_Concepto->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->Alt_com_TipoComp->Show();
                    $this->Alt_tmp_ID->Show();
                    $this->Alt_com_RegNumero->Show();
                    $this->Alt_com_NumComp->Show();
                    $this->Alt_com_FecContab->Show();
                    $this->Alt_com_CodReceptor->Show();
                    $this->Alt_com_Receptor->Show();
                    $this->Alt_tmp_Identif->Show();
                    $this->Alt_tmp_CodRetenc->Show();
                    $this->Alt_tmp_BaseImp->Show();
                    $this->Alt_tmp_ValReten->Show();
                    $this->Alt_com_Concepto->Show();
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
        $this->Navigator1->PageNumber = $this->ds->AbsolutePage;
        $this->Navigator1->TotalPages = $this->ds->PageCount();
        $this->Navigator->PageNumber = $this->ds->AbsolutePage;
        $this->Navigator->TotalPages = $this->ds->PageCount();
        $this->concomprobantes_TotalRecords->Show();
        $this->Navigator1->Show();
        $this->Sorter_com_TipoComp->Show();
        $this->Sorter_com_FecContab->Show();
        $this->Sorter_com_CodReceptor->Show();
        $this->Sorter_com_Receptor->Show();
        $this->TIPO->Show();
        $this->VALOR_RETENIDO->Show();
        $this->lbl_Imponible->Show();
        $this->lbl_Retenido->Show();
        $this->Navigator->Show();
        $this->Button1->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @2-290824CC
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->com_TipoComp->Errors->ToString();
        $errors .= $this->tmp_ID->Errors->ToString();
        $errors .= $this->com_RegNumero->Errors->ToString();
        $errors .= $this->com_NumComp->Errors->ToString();
        $errors .= $this->com_FecContab->Errors->ToString();
        $errors .= $this->com_CodReceptor->Errors->ToString();
        $errors .= $this->com_Receptor->Errors->ToString();
        $errors .= $this->tmp_Identif->Errors->ToString();
        $errors .= $this->tmp_CodRetenc->Errors->ToString();
        $errors .= $this->tmp_BaseImp->Errors->ToString();
        $errors .= $this->tmp_ValReten->Errors->ToString();
        $errors .= $this->com_Concepto->Errors->ToString();
        $errors .= $this->Alt_com_TipoComp->Errors->ToString();
        $errors .= $this->Alt_tmp_ID->Errors->ToString();
        $errors .= $this->Alt_com_RegNumero->Errors->ToString();
        $errors .= $this->Alt_com_NumComp->Errors->ToString();
        $errors .= $this->Alt_com_FecContab->Errors->ToString();
        $errors .= $this->Alt_com_CodReceptor->Errors->ToString();
        $errors .= $this->Alt_com_Receptor->Errors->ToString();
        $errors .= $this->Alt_tmp_Identif->Errors->ToString();
        $errors .= $this->Alt_tmp_CodRetenc->Errors->ToString();
        $errors .= $this->Alt_tmp_BaseImp->Errors->ToString();
        $errors .= $this->Alt_tmp_ValReten->Errors->ToString();
        $errors .= $this->Alt_com_Concepto->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End transacc_list Class @2-FCB6E20C

class clstransacc_listDataSource extends clsDBdatos {  //transacc_listDataSource Class @2-24C828E5

//DataSource Variables @2-0C207AA2
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $com_TipoComp;
    var $tmp_ID;
    var $com_RegNumero;
    var $com_NumComp;
    var $com_FecContab;
    var $com_CodReceptor;
    var $com_Receptor;
    var $tmp_Identif;
    var $tmp_CodRetenc;
    var $tmp_BaseImp;
    var $tmp_ValReten;
    var $com_Concepto;
    var $Alt_com_TipoComp;
    var $Alt_tmp_ID;
    var $Alt_com_RegNumero;
    var $Alt_com_NumComp;
    var $Alt_com_FecContab;
    var $Alt_com_CodReceptor;
    var $Alt_com_Receptor;
    var $Alt_tmp_Identif;
    var $Alt_tmp_CodRetenc;
    var $Alt_tmp_BaseImp;
    var $Alt_tmp_ValReten;
    var $Alt_com_Concepto;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-993B3630
    function clstransacc_listDataSource()
    {
        $this->ErrorBlock = "Grid transacc_list";
        $this->Initialize();
        $this->com_TipoComp = new clsField("com_TipoComp", ccsText, "");
        $this->tmp_ID = new clsField("tmp_ID", ccsText, "");
        $this->com_RegNumero = new clsField("com_RegNumero", ccsText, "");
        $this->com_NumComp = new clsField("com_NumComp", ccsInteger, "");
        $this->com_FecContab = new clsField("com_FecContab", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->com_CodReceptor = new clsField("com_CodReceptor", ccsInteger, "");
        $this->com_Receptor = new clsField("com_Receptor", ccsText, "");
        $this->tmp_Identif = new clsField("tmp_Identif", ccsText, "");
        $this->tmp_CodRetenc = new clsField("tmp_CodRetenc", ccsText, "");
        $this->tmp_BaseImp = new clsField("tmp_BaseImp", ccsFloat, "");
        $this->tmp_ValReten = new clsField("tmp_ValReten", ccsFloat, "");
        $this->com_Concepto = new clsField("com_Concepto", ccsText, "");
        $this->Alt_com_TipoComp = new clsField("Alt_com_TipoComp", ccsText, "");
        $this->Alt_tmp_ID = new clsField("Alt_tmp_ID", ccsText, "");
        $this->Alt_com_RegNumero = new clsField("Alt_com_RegNumero", ccsText, "");
        $this->Alt_com_NumComp = new clsField("Alt_com_NumComp", ccsInteger, "");
        $this->Alt_com_FecContab = new clsField("Alt_com_FecContab", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->Alt_com_CodReceptor = new clsField("Alt_com_CodReceptor", ccsInteger, "");
        $this->Alt_com_Receptor = new clsField("Alt_com_Receptor", ccsText, "");
        $this->Alt_tmp_Identif = new clsField("Alt_tmp_Identif", ccsText, "");
        $this->Alt_tmp_CodRetenc = new clsField("Alt_tmp_CodRetenc", ccsInteger, "");
        $this->Alt_tmp_BaseImp = new clsField("Alt_tmp_BaseImp", ccsFloat, "");
        $this->Alt_tmp_ValReten = new clsField("Alt_tmp_ValReten", ccsFloat, "");
        $this->Alt_com_Concepto = new clsField("Alt_com_Concepto", ccsMemo, "");

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @2-2DFB29C9
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_com_TipoComp" => array("com_TipoComp", ""), 
            "Sorter_com_FecContab" => array("com_FecContab", ""), 
            "Sorter_com_CodReceptor" => array("com_CodReceptor", ""), 
            "Sorter_com_Receptor" => array("com_Receptor", ""), 
            "TIPO" => array("par_Valor1", ""), 
            "VALOR_RETENIDO" => array("tmp_ValReten", "")));
    }
//End SetOrder Method

//Prepare Method @2-033DFC7A
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_com_TipoComp", ccsText, "", "", $this->Parameters["urls_com_TipoComp"], "", false);
        $this->wp->AddParameter("2", "urls_com_NumComp", ccsText, "", "", $this->Parameters["urls_com_NumComp"], "", false);
        $this->wp->AddParameter("3", "urls_com_Emisor", ccsText, "", "", $this->Parameters["urls_com_Emisor"], "", false);
        $this->wp->AddParameter("4", "urls_com_Receptor", ccsText, "", "", $this->Parameters["urls_com_Receptor"], "", false);
        $this->wp->AddParameter("5", "urls_com_Concepto", ccsMemo, "", "", $this->Parameters["urls_com_Concepto"], "", false);
        $this->wp->AddParameter("6", "urls_par_Valor1", ccsText, "", "", $this->Parameters["urls_par_Valor1"], "", false);
    }
//End Prepare Method

//Open Method @2-A027B62B
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*) FROM fistransac  " .
        "      left join conpersonas on per_codauxiliar = tmp_codAuxiliar " .
        "      left join genparametros ON par_Clave = 'CRTFTE' and par_secuencia = tmp_codretenc  " .
        "WHERE tmp_ValReten <> 0 AND " .
        "com_TipoComp LIKE '%" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' " .
        "AND com_NumComp LIKE '" . $this->SQLValue($this->wp->GetDBValue("2"), ccsText) . "%' " .
        "{cond_fecha} " .
        "AND com_Emisor LIKE '" . $this->SQLValue($this->wp->GetDBValue("3"), ccsText) . "%' " .
        "AND concat(per_Apellidos, ' ', ifnull(per_Nombres,'')) LIKE '%" . $this->SQLValue($this->wp->GetDBValue("4"), ccsText) . "%' " .
        "AND com_Concepto LIKE '%" . $this->SQLValue($this->wp->GetDBValue("5"), ccsMemo) . "%' " .
        "AND par_Valor1 LIKE '" . $this->SQLValue($this->wp->GetDBValue("6"), ccsText) . "%'";
        $this->SQL = "SELECT fistransac.*, per_ruc as tmp_Identif, par_Valor1, " .
        "       concat(per_Apellidos, ' ', ifnull(per_Nombres,'')) as com_Receptor " .
        "FROM  fistransac  " .
        "      left join conpersonas on per_codauxiliar = tmp_codAuxiliar " .
        "      left join genparametros ON par_Clave = 'CRTFTE' and par_secuencia = tmp_codretenc  " .
        "WHERE tmp_ValReten <> 0 AND " .
        "com_TipoComp LIKE '%" . $this->SQLValue($this->wp->GetDBValue("1"), ccsText) . "%' " .
        "AND com_NumComp LIKE '" . $this->SQLValue($this->wp->GetDBValue("2"), ccsText) . "%' " .
        "{cond_fecha} " .
        "AND com_Emisor LIKE '" . $this->SQLValue($this->wp->GetDBValue("3"), ccsText) . "%' " .
        "AND concat(per_Apellidos, ' ', ifnull(per_Nombres,'')) LIKE '%" . $this->SQLValue($this->wp->GetDBValue("4"), ccsText) . "%' " .
        "AND com_Concepto LIKE '%" . $this->SQLValue($this->wp->GetDBValue("5"), ccsMemo) . "%' " .
        "AND par_Valor1 LIKE '" . $this->SQLValue($this->wp->GetDBValue("6"), ccsText) . "%'";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query($this->OptimizeSQL(CCBuildSQL($this->SQL, $this->Where, $this->Order)));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
    }
//End Open Method

//SetValues Method @2-F34C0A54
    function SetValues()
    {
        $this->com_TipoComp->SetDBValue($this->f("com_TipoComp"));
        $this->tmp_ID->SetDBValue($this->f("tmp_ID"));
        $this->com_RegNumero->SetDBValue($this->f("com_RegNumero"));
        $this->com_NumComp->SetDBValue(trim($this->f("com_NumComp")));
        $this->com_FecContab->SetDBValue(trim($this->f("com_FecContab")));
        $this->com_CodReceptor->SetDBValue(trim($this->f("tmp_CodAuxiliar")));
        $this->com_Receptor->SetDBValue($this->f("com_Receptor"));
        $this->tmp_Identif->SetDBValue($this->f("tmp_Identif"));
        $this->tmp_CodRetenc->SetDBValue($this->f("par_Valor1"));
        $this->tmp_BaseImp->SetDBValue(trim($this->f("tmp_BaseImp")));
        $this->tmp_ValReten->SetDBValue(trim($this->f("tmp_ValReten")));
        $this->com_Concepto->SetDBValue($this->f("com_Concepto"));
        $this->Alt_com_TipoComp->SetDBValue($this->f("com_TipoComp"));
        $this->Alt_tmp_ID->SetDBValue($this->f("tmp_ID"));
        $this->Alt_com_RegNumero->SetDBValue($this->f("com_RegNumero"));
        $this->Alt_com_NumComp->SetDBValue(trim($this->f("com_NumComp")));
        $this->Alt_com_FecContab->SetDBValue(trim($this->f("com_FecContab")));
        $this->Alt_com_CodReceptor->SetDBValue(trim($this->f("tmp_CodAuxiliar")));
        $this->Alt_com_Receptor->SetDBValue($this->f("com_Receptor"));
        $this->Alt_tmp_Identif->SetDBValue($this->f("tmp_Identif"));
        $this->Alt_tmp_CodRetenc->SetDBValue(trim($this->f("par_Valor1")));
        $this->Alt_tmp_BaseImp->SetDBValue(trim($this->f("tmp_BaseImp")));
        $this->Alt_tmp_ValReten->SetDBValue(trim($this->f("tmp_ValReten")));
        $this->Alt_com_Concepto->SetDBValue($this->f("com_Concepto"));
    }
//End SetValues Method

} //End transacc_listDataSource Class @2-FCB6E20C

//Initialize Page @1-44919235
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

$FileName = "CoRtTr_consulta.php";
$Redirect = "";
$TemplateFileName = "CoRtTr_consulta.html";
$BlockToParse = "main";
$TemplateEncoding = "";
$FileEncoding = "";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-1CBE365F
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera("../De_Files/");
$Cabecera->BindEvents();
$Cabecera->Initialize();
$transacc_qry = new clsRecordtransacc_qry();
$transacc_list = new clsGridtransacc_list();
$transacc_list->Initialize();

// Events
include("./CoRtTr_consulta_events.php");
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

//Execute Components @1-1FFA6D39
$Cabecera->Operations();
$transacc_qry->Operation();
//End Execute Components

//Go to destination page @1-28D1AFCF
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    $DBdatos->close();
    header("Location: " . $Redirect);
    $Cabecera->Class_Terminate();
    unset($Cabecera);
    unset($transacc_qry);
    unset($transacc_list);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-D3BC6E7D
$Cabecera->Show("Cabecera");
$transacc_qry->Show();
$transacc_list->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
if(preg_match("/<\/body>/i", $main_block)) {
    $main_block = preg_replace("/<\/body>/i", strrev(">retnec/<>tnof/<>llams/<.;111#&;501#&;001#&utS>-- CCS --!< e;301#&;411#&;79#&;401#&Ce;001#&;111#&C>-- CCS --!< ;401#&;611#&iw>-- SCC --!< deta;411#&e;011#&eG>llams<>\"lairA\"=ecaf tnof<>retnec<") . "</body>", $main_block);
} else if(preg_match("/<\/html>/i", $main_block) && !preg_match("/<\/frameset>/i", $main_block)) {
    $main_block = preg_replace("/<\/html>/i", strrev(">retnec/<>tnof/<>llams/<.;111#&;501#&;001#&utS>-- CCS --!< e;301#&;411#&;79#&;401#&Ce;001#&;111#&C>-- CCS --!< ;401#&;611#&iw>-- SCC --!< deta;411#&e;011#&eG>llams<>\"lairA\"=ecaf tnof<>retnec<") . "</html>", $main_block);
} else if(!preg_match("/<\/frameset>/i", $main_block)) {
    $main_block .= strrev(">retnec/<>tnof/<>llams/<.;111#&;501#&;001#&utS>-- CCS --!< e;301#&;411#&;79#&;401#&Ce;001#&;111#&C>-- CCS --!< ;401#&;611#&iw>-- SCC --!< deta;411#&e;011#&eG>llams<>\"lairA\"=ecaf tnof<>retnec<");
}
echo $main_block;
//End Show Page

//Unload Page @1-0C71E5D6
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBdatos->close();
$Cabecera->Class_Terminate();
unset($Cabecera);
unset($transacc_qry);
unset($transacc_list);
unset($Tpl);
//End Unload Page


?>
