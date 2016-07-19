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

Class clsRecordmovim_query { //movim_query Class @28-279BA096

//Variables @28-CB19EB75

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

//Class_Initialize Event @28-778E4DC7
    function clsRecordmovim_query()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record movim_query/Error";
        $this->ReadAllowed = false;
        $this->InsertAllowed = false;
        $this->UpdateAllowed = false;
        $this->DeleteAllowed = false;
        if($this->Visible)
        {
            $this->ComponentName = "movim_query";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->s_com_TipoComp = new clsControl(ccsTextBox, "s_com_TipoComp", "Tipo de Comprobnate", ccsText, "", CCGetRequestParam("s_com_TipoComp", $Method));
            $this->s_com_NumComp = new clsControl(ccsTextBox, "s_com_NumComp", "s_com_NumComp", ccsInteger, "", CCGetRequestParam("s_com_NumComp", $Method));
            $this->s_com_RegNumero = new clsControl(ccsTextBox, "s_com_RegNumero", "s_com_RegNumero", ccsInteger, "", CCGetRequestParam("s_com_RegNumero", $Method));
            $this->s_com_FecTrans = new clsControl(ccsTextBox, "s_com_FecTrans", "s_com_FecTrans", ccsDate, Array("mm", "/", "dd", "/", "yyyy"), CCGetRequestParam("s_com_FecTrans", $Method));
            $this->DatePicker_s_com_FecTrans = new clsDatePicker("DatePicker_s_com_FecTrans", "movim_query", "s_com_FecTrans");
            $this->s_com_FecContab = new clsControl(ccsTextBox, "s_com_FecContab", "s_com_FecContab", ccsDate, Array("mm", "/", "dd", "/", "yyyy"), CCGetRequestParam("s_com_FecContab", $Method));
            $this->DatePicker_s_com_FecContab = new clsDatePicker("DatePicker_s_com_FecContab", "movim_query", "s_com_FecContab");
            $this->s_com_NumPeriodo = new clsControl(ccsTextBox, "s_com_NumPeriodo", "Número de período contable al que afecta los movimientos", ccsInteger, "", CCGetRequestParam("s_com_NumPeriodo", $Method));
            $this->s_com_Usuario = new clsControl(ccsTextBox, "s_com_Usuario", "Nombre de usuario", ccsText, "", CCGetRequestParam("s_com_Usuario", $Method));
            $this->s_com_FecDigita = new clsControl(ccsTextBox, "s_com_FecDigita", "Fecha de Digitación", ccsDate, Array("mmm", "/", "dd", "/", "yyyy"), CCGetRequestParam("s_com_FecDigita", $Method));
            $this->DatePicker_s_com_FecDigita = new clsDatePicker("DatePicker_s_com_FecDigita", "movim_query", "s_com_FecDigita");
            $this->s_det_IDAuxiliar = new clsControl(ccsTextBox, "s_det_IDAuxiliar", "Código de Auxiliar", ccsInteger, "", CCGetRequestParam("s_det_IDAuxiliar", $Method));
            $this->s_desc_auxil = new clsControl(ccsTextBox, "s_desc_auxil", "Descripcion de Auxiliar", ccsText, "", CCGetRequestParam("s_desc_auxil", $Method));
            $this->s_det_CodCuenta = new clsControl(ccsTextBox, "s_det_CodCuenta", "s_det_CodCuenta", ccsText, "", CCGetRequestParam("s_det_CodCuenta", $Method));
            $this->s_det_ValDebito = new clsControl(ccsTextBox, "s_det_ValDebito", "s_det_ValDebito", ccsFloat, "", CCGetRequestParam("s_det_ValDebito", $Method));
            $this->s_det_ValCredito = new clsControl(ccsTextBox, "s_det_ValCredito", "s_det_ValCredito", ccsFloat, "", CCGetRequestParam("s_det_ValCredito", $Method));
            $this->s_det_Glosa = new clsControl(ccsTextBox, "s_det_Glosa", "s_det_Glosa", ccsText, "", CCGetRequestParam("s_det_Glosa", $Method));
            $this->s_det_NumCheque = new clsControl(ccsTextBox, "s_det_NumCheque", "Número de Cheque", ccsInteger, "", CCGetRequestParam("s_det_NumCheque", $Method));
            $this->s_det_FecCheque = new clsControl(ccsTextBox, "s_det_FecCheque", "Fecha de Vencimiento del Cheque (Si se Aplica)", ccsDate, Array("mm", "/", "dd", "/", "yyyy"), CCGetRequestParam("s_det_FecCheque", $Method));
            $this->DatePicker_s_det_FecCheque = new clsDatePicker("DatePicker_s_det_FecCheque", "movim_query", "s_det_FecCheque");
            $this->concomprobantes_condetallOrder = new clsControl(ccsListBox, "concomprobantes_condetallOrder", "concomprobantes_condetallOrder", ccsText, "", CCGetRequestParam("concomprobantes_condetallOrder", $Method));
            $this->concomprobantes_condetallOrder->DSType = dsListOfValues;
            $this->concomprobantes_condetallOrder->Values = array(array("", "Seleccionar Campo"), array("Sorter_com_TipoComp", "Tipo de Comp."), array("Sorter_com_NumComp", "Numero de Comp."), array("Sorter_com_RegNumero", "Numero de Reg."), array("Sorter_com_FecTrans", "Fecha Emision"), array("Sorter_com_FecContab", "Fecha Contable"), array("Sorter_com_NumPeriodo", "Periodo Contable"), array("Sorter_com_Usuario", "Usuario"), array("Sorter_com_FecDigita", "Fcha Digitacion"), array("Sorter_det_IDAuxiliar", "Auxiliar"), array("Sorter_det_CodCuenta", "Cod. Cuenta"), array("Sorter_det_ValDebito", "Valor Debito"), array("Sorter_det_ValCredito", "Valor Credito"), array("Sorter_det_Glosa", "Glosa"), array("Sorter_det_NumCheque", "Num Cheque"), array("Sorter_det_FecCheque", "Fecha Cheque"), array("Sorter_per_Apellidos", "Apellidos"), array("Sorter_act_Descripcion", "Descripcion"));
            $this->concomprobantes_condetallDir = new clsControl(ccsListBox, "concomprobantes_condetallDir", "concomprobantes_condetallDir", ccsText, "", CCGetRequestParam("concomprobantes_condetallDir", $Method));
            $this->concomprobantes_condetallDir->DSType = dsListOfValues;
            $this->concomprobantes_condetallDir->Values = array(array("", "Seleccionar Orden"), array("ASC", "Ascendente"), array("DESC", "Descendente"));
            $this->Button_DoSearch = new clsButton("Button_DoSearch");
        }
    }
//End Class_Initialize Event

//Validate Method @28-2DE3C9D0
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_com_TipoComp->Validate() && $Validation);
        $Validation = ($this->s_com_NumComp->Validate() && $Validation);
        $Validation = ($this->s_com_RegNumero->Validate() && $Validation);
        $Validation = ($this->s_com_FecTrans->Validate() && $Validation);
        $Validation = ($this->s_com_FecContab->Validate() && $Validation);
        $Validation = ($this->s_com_NumPeriodo->Validate() && $Validation);
        $Validation = ($this->s_com_Usuario->Validate() && $Validation);
        $Validation = ($this->s_com_FecDigita->Validate() && $Validation);
        $Validation = ($this->s_det_IDAuxiliar->Validate() && $Validation);
        $Validation = ($this->s_desc_auxil->Validate() && $Validation);
        $Validation = ($this->s_det_CodCuenta->Validate() && $Validation);
        $Validation = ($this->s_det_ValDebito->Validate() && $Validation);
        $Validation = ($this->s_det_ValCredito->Validate() && $Validation);
        $Validation = ($this->s_det_Glosa->Validate() && $Validation);
        $Validation = ($this->s_det_NumCheque->Validate() && $Validation);
        $Validation = ($this->s_det_FecCheque->Validate() && $Validation);
        $Validation = ($this->concomprobantes_condetallOrder->Validate() && $Validation);
        $Validation = ($this->concomprobantes_condetallDir->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @28-693CAC0D
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_com_TipoComp->Errors->Count());
        $errors = ($errors || $this->s_com_NumComp->Errors->Count());
        $errors = ($errors || $this->s_com_RegNumero->Errors->Count());
        $errors = ($errors || $this->s_com_FecTrans->Errors->Count());
        $errors = ($errors || $this->DatePicker_s_com_FecTrans->Errors->Count());
        $errors = ($errors || $this->s_com_FecContab->Errors->Count());
        $errors = ($errors || $this->DatePicker_s_com_FecContab->Errors->Count());
        $errors = ($errors || $this->s_com_NumPeriodo->Errors->Count());
        $errors = ($errors || $this->s_com_Usuario->Errors->Count());
        $errors = ($errors || $this->s_com_FecDigita->Errors->Count());
        $errors = ($errors || $this->DatePicker_s_com_FecDigita->Errors->Count());
        $errors = ($errors || $this->s_det_IDAuxiliar->Errors->Count());
        $errors = ($errors || $this->s_desc_auxil->Errors->Count());
        $errors = ($errors || $this->s_det_CodCuenta->Errors->Count());
        $errors = ($errors || $this->s_det_ValDebito->Errors->Count());
        $errors = ($errors || $this->s_det_ValCredito->Errors->Count());
        $errors = ($errors || $this->s_det_Glosa->Errors->Count());
        $errors = ($errors || $this->s_det_NumCheque->Errors->Count());
        $errors = ($errors || $this->s_det_FecCheque->Errors->Count());
        $errors = ($errors || $this->DatePicker_s_det_FecCheque->Errors->Count());
        $errors = ($errors || $this->concomprobantes_condetallOrder->Errors->Count());
        $errors = ($errors || $this->concomprobantes_condetallDir->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @28-BE3B19D3
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
        $Redirect = "CoTrTr_movim.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick")) {
                    $Redirect = "";
                } else {
                    $Redirect = "CoTrTr_movim.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", Array("Button_DoSearch")));
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @28-80241531
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->concomprobantes_condetallOrder->Prepare();
        $this->concomprobantes_condetallDir->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        if(!$this->FormSubmitted)
        {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->s_com_TipoComp->Errors->ToString();
            $Error .= $this->s_com_NumComp->Errors->ToString();
            $Error .= $this->s_com_RegNumero->Errors->ToString();
            $Error .= $this->s_com_FecTrans->Errors->ToString();
            $Error .= $this->DatePicker_s_com_FecTrans->Errors->ToString();
            $Error .= $this->s_com_FecContab->Errors->ToString();
            $Error .= $this->DatePicker_s_com_FecContab->Errors->ToString();
            $Error .= $this->s_com_NumPeriodo->Errors->ToString();
            $Error .= $this->s_com_Usuario->Errors->ToString();
            $Error .= $this->s_com_FecDigita->Errors->ToString();
            $Error .= $this->DatePicker_s_com_FecDigita->Errors->ToString();
            $Error .= $this->s_det_IDAuxiliar->Errors->ToString();
            $Error .= $this->s_desc_auxil->Errors->ToString();
            $Error .= $this->s_det_CodCuenta->Errors->ToString();
            $Error .= $this->s_det_ValDebito->Errors->ToString();
            $Error .= $this->s_det_ValCredito->Errors->ToString();
            $Error .= $this->s_det_Glosa->Errors->ToString();
            $Error .= $this->s_det_NumCheque->Errors->ToString();
            $Error .= $this->s_det_FecCheque->Errors->ToString();
            $Error .= $this->DatePicker_s_det_FecCheque->Errors->ToString();
            $Error .= $this->concomprobantes_condetallOrder->Errors->ToString();
            $Error .= $this->concomprobantes_condetallDir->Errors->ToString();
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
        $this->s_com_RegNumero->Show();
        $this->s_com_FecTrans->Show();
        $this->DatePicker_s_com_FecTrans->Show();
        $this->s_com_FecContab->Show();
        $this->DatePicker_s_com_FecContab->Show();
        $this->s_com_NumPeriodo->Show();
        $this->s_com_Usuario->Show();
        $this->s_com_FecDigita->Show();
        $this->DatePicker_s_com_FecDigita->Show();
        $this->s_det_IDAuxiliar->Show();
        $this->s_desc_auxil->Show();
        $this->s_det_CodCuenta->Show();
        $this->s_det_ValDebito->Show();
        $this->s_det_ValCredito->Show();
        $this->s_det_Glosa->Show();
        $this->s_det_NumCheque->Show();
        $this->s_det_FecCheque->Show();
        $this->DatePicker_s_det_FecCheque->Show();
        $this->concomprobantes_condetallOrder->Show();
        $this->concomprobantes_condetallDir->Show();
        $this->Button_DoSearch->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End movim_query Class @28-FCB6E20C

class clsGridmovim_lista { //movim_lista class @3-4D66305C

//Variables @3-8AA73AF9

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
    var $Sorter_com_TipoComp;
    var $Sorter_com_FecTrans;
    var $Sorter_com_FecContab;
    var $Sorter_com_Usuario;
    var $Sorter_com_NumPeriodo;
    var $Sorter_det_CodCuenta;
    var $Sorter_det_IDAuxiliar;
    var $Sorter_det_ValDebito;
    var $Sorter_det_ValCredito;
    var $Sorter_det_Glosa;
    var $Sorter_det_NumCheque;
    var $Navigator;
//End Variables

//Class_Initialize Event @3-EFF77370
    function clsGridmovim_lista()
    {
        global $FileName;
        $this->ComponentName = "movim_lista";
        $this->Visible = True;
        $this->IsAltRow = false;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid movim_lista";
        $this->ds = new clsmovim_listaDataSource();
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 25;
        else if ($this->PageSize > 25)
            $this->PageSize = 25;
        else
            $this->PageSize = intval($this->PageSize);
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        $this->SorterName = CCGetParam("movim_listaOrder", "");
        $this->SorterDirection = CCGetParam("movim_listaDir", "");

        $this->lnCompr = new clsControl(ccsLink, "lnCompr", "lnCompr", ccsText, "", CCGetRequestParam("lnCompr", ccsGet));
        $this->com_FecTrans = new clsControl(ccsLabel, "com_FecTrans", "com_FecTrans", ccsDate, Array("dd", "/", "mmm", "/", "yyyy"), CCGetRequestParam("com_FecTrans", ccsGet));
        $this->com_FecContab = new clsControl(ccsLabel, "com_FecContab", "com_FecContab", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("com_FecContab", ccsGet));
        $this->com_Usuario = new clsControl(ccsLabel, "com_Usuario", "com_Usuario", ccsText, "", CCGetRequestParam("com_Usuario", ccsGet));
        $this->com_FecDigita = new clsControl(ccsLabel, "com_FecDigita", "com_FecDigita", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("com_FecDigita", ccsGet));
        $this->com_NumPeriodo = new clsControl(ccsLabel, "com_NumPeriodo", "com_NumPeriodo", ccsInteger, "", CCGetRequestParam("com_NumPeriodo", ccsGet));
        $this->det_CodCuenta = new clsControl(ccsLabel, "det_CodCuenta", "det_CodCuenta", ccsText, "", CCGetRequestParam("det_CodCuenta", ccsGet));
        $this->det_IDAuxiliar = new clsControl(ccsLabel, "det_IDAuxiliar", "det_IDAuxiliar", ccsInteger, "", CCGetRequestParam("det_IDAuxiliar", ccsGet));
        $this->per_Apellidos = new clsControl(ccsLabel, "per_Apellidos", "per_Apellidos", ccsText, "", CCGetRequestParam("per_Apellidos", ccsGet));
        $this->act_Descripcion = new clsControl(ccsLabel, "act_Descripcion", "act_Descripcion", ccsText, "", CCGetRequestParam("act_Descripcion", ccsGet));
        $this->det_ValDebito = new clsControl(ccsLabel, "det_ValDebito", "det_ValDebito", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("0", "0"), 1, True, ""), CCGetRequestParam("det_ValDebito", ccsGet));
        $this->det_ValCredito = new clsControl(ccsLabel, "det_ValCredito", "det_ValCredito", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("0", "0"), 1, True, ""), CCGetRequestParam("det_ValCredito", ccsGet));
        $this->det_Glosa = new clsControl(ccsLabel, "det_Glosa", "det_Glosa", ccsText, "", CCGetRequestParam("det_Glosa", ccsGet));
        $this->det_NumCheque = new clsControl(ccsLabel, "det_NumCheque", "det_NumCheque", ccsInteger, Array(True, 0, "", "", False, Array("#", "#", "#", "#"), "", 1, True, ""), CCGetRequestParam("det_NumCheque", ccsGet));
        $this->alt_lnCompr = new clsControl(ccsLink, "alt_lnCompr", "alt_lnCompr", ccsText, "", CCGetRequestParam("alt_lnCompr", ccsGet));
        $this->Alt_com_FecTrans = new clsControl(ccsLabel, "Alt_com_FecTrans", "Alt_com_FecTrans", ccsDate, Array("dd", "/", "mmm", "/", "yyyy"), CCGetRequestParam("Alt_com_FecTrans", ccsGet));
        $this->Alt_com_FecContab = new clsControl(ccsLabel, "Alt_com_FecContab", "Alt_com_FecContab", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("Alt_com_FecContab", ccsGet));
        $this->Alt_com_Usuario = new clsControl(ccsLabel, "Alt_com_Usuario", "Alt_com_Usuario", ccsText, "", CCGetRequestParam("Alt_com_Usuario", ccsGet));
        $this->Alt_com_FecDigita = new clsControl(ccsLabel, "Alt_com_FecDigita", "Alt_com_FecDigita", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("Alt_com_FecDigita", ccsGet));
        $this->Alt_com_NumPeriodo = new clsControl(ccsLabel, "Alt_com_NumPeriodo", "Alt_com_NumPeriodo", ccsInteger, "", CCGetRequestParam("Alt_com_NumPeriodo", ccsGet));
        $this->Alt_det_CodCuenta = new clsControl(ccsLabel, "Alt_det_CodCuenta", "Alt_det_CodCuenta", ccsText, "", CCGetRequestParam("Alt_det_CodCuenta", ccsGet));
        $this->Alt_det_IDAuxiliar = new clsControl(ccsLabel, "Alt_det_IDAuxiliar", "Alt_det_IDAuxiliar", ccsInteger, "", CCGetRequestParam("Alt_det_IDAuxiliar", ccsGet));
        $this->Alt_per_Apellidos = new clsControl(ccsLabel, "Alt_per_Apellidos", "Alt_per_Apellidos", ccsText, "", CCGetRequestParam("Alt_per_Apellidos", ccsGet));
        $this->Alt_act_Descripcion = new clsControl(ccsLabel, "Alt_act_Descripcion", "Alt_act_Descripcion", ccsText, "", CCGetRequestParam("Alt_act_Descripcion", ccsGet));
        $this->Alt_det_ValDebito = new clsControl(ccsLabel, "Alt_det_ValDebito", "Alt_det_ValDebito", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("0", "0"), 1, True, ""), CCGetRequestParam("Alt_det_ValDebito", ccsGet));
        $this->Alt_det_ValCredito = new clsControl(ccsLabel, "Alt_det_ValCredito", "Alt_det_ValCredito", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("0", "0"), 1, True, ""), CCGetRequestParam("Alt_det_ValCredito", ccsGet));
        $this->Alt_det_Glosa = new clsControl(ccsLabel, "Alt_det_Glosa", "Alt_det_Glosa", ccsText, "", CCGetRequestParam("Alt_det_Glosa", ccsGet));
        $this->Alt_det_NumCheque = new clsControl(ccsLabel, "Alt_det_NumCheque", "Alt_det_NumCheque", ccsInteger, Array(True, 0, "", "", False, Array("#", "#", "#", "#"), "", 1, True, ""), CCGetRequestParam("Alt_det_NumCheque", ccsGet));
        $this->Link1 = new clsControl(ccsLink, "Link1", "Link1", ccsText, "", CCGetRequestParam("Link1", ccsGet));
        $this->Link1->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
        $this->Link1->Parameters = CCAddParam($this->Link1->Parameters, "action", "Q");
        $this->Link1->Page = "CoTrTr_movim.php";
        $this->concomprobantes_condetall_TotalRecords = new clsControl(ccsLabel, "concomprobantes_condetall_TotalRecords", "concomprobantes_condetall_TotalRecords", ccsText, "", CCGetRequestParam("concomprobantes_condetall_TotalRecords", ccsGet));
        $this->Sorter_com_TipoComp = new clsSorter($this->ComponentName, "Sorter_com_TipoComp", $FileName);
        $this->Sorter_com_FecTrans = new clsSorter($this->ComponentName, "Sorter_com_FecTrans", $FileName);
        $this->Sorter_com_FecContab = new clsSorter($this->ComponentName, "Sorter_com_FecContab", $FileName);
        $this->Sorter_com_Usuario = new clsSorter($this->ComponentName, "Sorter_com_Usuario", $FileName);
        $this->Sorter_com_NumPeriodo = new clsSorter($this->ComponentName, "Sorter_com_NumPeriodo", $FileName);
        $this->Sorter_det_CodCuenta = new clsSorter($this->ComponentName, "Sorter_det_CodCuenta", $FileName);
        $this->Sorter_det_IDAuxiliar = new clsSorter($this->ComponentName, "Sorter_det_IDAuxiliar", $FileName);
        $this->Sorter_det_ValDebito = new clsSorter($this->ComponentName, "Sorter_det_ValDebito", $FileName);
        $this->Sorter_det_ValCredito = new clsSorter($this->ComponentName, "Sorter_det_ValCredito", $FileName);
        $this->Sorter_det_Glosa = new clsSorter($this->ComponentName, "Sorter_det_Glosa", $FileName);
        $this->Sorter_det_NumCheque = new clsSorter($this->ComponentName, "Sorter_det_NumCheque", $FileName);
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 20, tpCentered);
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

//Show Method @3-3DF4EB9F
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["urls_com_TipoComp"] = CCGetFromGet("s_com_TipoComp", "");
        $this->ds->Parameters["urls_com_NumComp"] = CCGetFromGet("s_com_NumComp", "");
        $this->ds->Parameters["urls_com_RegNumero"] = CCGetFromGet("s_com_RegNumero", "");
        $this->ds->Parameters["urls_com_FecTrans"] = CCGetFromGet("s_com_FecTrans", "");
        $this->ds->Parameters["urls_com_FecContab"] = CCGetFromGet("s_com_FecContab", "");
        $this->ds->Parameters["urls_com_Usuario"] = CCGetFromGet("s_com_Usuario", "");
        $this->ds->Parameters["urls_com_NumPeriodo"] = CCGetFromGet("s_com_NumPeriodo", "");
        $this->ds->Parameters["urls_com_FecDigita"] = CCGetFromGet("s_com_FecDigita", "");
        $this->ds->Parameters["urls_det_IDAuxiliar"] = CCGetFromGet("s_det_IDAuxiliar", "");
        $this->ds->Parameters["urls_det_ValDebito"] = CCGetFromGet("s_det_ValDebito", "");
        $this->ds->Parameters["urls_det_ValCredito"] = CCGetFromGet("s_det_ValCredito", "");
        $this->ds->Parameters["urls_det_Glosa"] = CCGetFromGet("s_det_Glosa", "");
        $this->ds->Parameters["urls_det_NumCheque"] = CCGetFromGet("s_det_NumCheque", "");
        $this->ds->Parameters["urls_det_FecCheque"] = CCGetFromGet("s_det_FecCheque", "");
        $this->ds->Parameters["urls_det_CodCuenta"] = CCGetFromGet("s_det_CodCuenta", "");
        $this->ds->Parameters["urls_desc_auxil"] = CCGetFromGet("s_desc_auxil", "");
        $this->ds->Parameters["urls_act_Descripcion"] = CCGetFromGet("s_act_Descripcion", "");

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
                    $this->lnCompr->SetValue($this->ds->lnCompr->GetValue());
                    $this->lnCompr->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
                    $this->lnCompr->Parameters = CCAddParam($this->lnCompr->Parameters, "com_RegNumero", $this->ds->f("com_RegNumero"));
                    $this->lnCompr->Parameters = CCAddParam($this->lnCompr->Parameters, "pTipoComp", $this->ds->f("com_TipoComp"));
                    $this->lnCompr->Page = "CoTrTr_deta.php";
                    $this->com_FecTrans->SetValue($this->ds->com_FecTrans->GetValue());
                    $this->com_FecContab->SetValue($this->ds->com_FecContab->GetValue());
                    $this->com_Usuario->SetValue($this->ds->com_Usuario->GetValue());
                    $this->com_FecDigita->SetValue($this->ds->com_FecDigita->GetValue());
                    $this->com_NumPeriodo->SetValue($this->ds->com_NumPeriodo->GetValue());
                    $this->det_CodCuenta->SetValue($this->ds->det_CodCuenta->GetValue());
                    $this->det_IDAuxiliar->SetValue($this->ds->det_IDAuxiliar->GetValue());
                    $this->per_Apellidos->SetValue($this->ds->per_Apellidos->GetValue());
                    $this->act_Descripcion->SetValue($this->ds->act_Descripcion->GetValue());
                    $this->det_ValDebito->SetValue($this->ds->det_ValDebito->GetValue());
                    $this->det_ValCredito->SetValue($this->ds->det_ValCredito->GetValue());
                    $this->det_Glosa->SetValue($this->ds->det_Glosa->GetValue());
                    $this->det_NumCheque->SetValue($this->ds->det_NumCheque->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->lnCompr->Show();
                    $this->com_FecTrans->Show();
                    $this->com_FecContab->Show();
                    $this->com_Usuario->Show();
                    $this->com_FecDigita->Show();
                    $this->com_NumPeriodo->Show();
                    $this->det_CodCuenta->Show();
                    $this->det_IDAuxiliar->Show();
                    $this->per_Apellidos->Show();
                    $this->act_Descripcion->Show();
                    $this->det_ValDebito->Show();
                    $this->det_ValCredito->Show();
                    $this->det_Glosa->Show();
                    $this->det_NumCheque->Show();
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                    $Tpl->parse("Row", true);
                }
                else
                {
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/AltRow";
                    $this->alt_lnCompr->SetValue($this->ds->alt_lnCompr->GetValue());
                    $this->alt_lnCompr->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
                    $this->alt_lnCompr->Parameters = CCAddParam($this->alt_lnCompr->Parameters, "com_RegNumero", $this->ds->f("com_RegNumero"));
                    $this->alt_lnCompr->Parameters = CCAddParam($this->alt_lnCompr->Parameters, "pTipoComp", $this->ds->f("com_TipoComp"));
                    $this->alt_lnCompr->Page = "CoTrTr_deta.php";
                    $this->Alt_com_FecTrans->SetValue($this->ds->Alt_com_FecTrans->GetValue());
                    $this->Alt_com_FecContab->SetValue($this->ds->Alt_com_FecContab->GetValue());
                    $this->Alt_com_Usuario->SetValue($this->ds->Alt_com_Usuario->GetValue());
                    $this->Alt_com_FecDigita->SetValue($this->ds->Alt_com_FecDigita->GetValue());
                    $this->Alt_com_NumPeriodo->SetValue($this->ds->Alt_com_NumPeriodo->GetValue());
                    $this->Alt_det_CodCuenta->SetValue($this->ds->Alt_det_CodCuenta->GetValue());
                    $this->Alt_det_IDAuxiliar->SetValue($this->ds->Alt_det_IDAuxiliar->GetValue());
                    $this->Alt_per_Apellidos->SetValue($this->ds->Alt_per_Apellidos->GetValue());
                    $this->Alt_act_Descripcion->SetValue($this->ds->Alt_act_Descripcion->GetValue());
                    $this->Alt_det_ValDebito->SetValue($this->ds->Alt_det_ValDebito->GetValue());
                    $this->Alt_det_ValCredito->SetValue($this->ds->Alt_det_ValCredito->GetValue());
                    $this->Alt_det_Glosa->SetValue($this->ds->Alt_det_Glosa->GetValue());
                    $this->Alt_det_NumCheque->SetValue($this->ds->Alt_det_NumCheque->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->alt_lnCompr->Show();
                    $this->Alt_com_FecTrans->Show();
                    $this->Alt_com_FecContab->Show();
                    $this->Alt_com_Usuario->Show();
                    $this->Alt_com_FecDigita->Show();
                    $this->Alt_com_NumPeriodo->Show();
                    $this->Alt_det_CodCuenta->Show();
                    $this->Alt_det_IDAuxiliar->Show();
                    $this->Alt_per_Apellidos->Show();
                    $this->Alt_act_Descripcion->Show();
                    $this->Alt_det_ValDebito->Show();
                    $this->Alt_det_ValCredito->Show();
                    $this->Alt_det_Glosa->Show();
                    $this->Alt_det_NumCheque->Show();
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
        $this->Link1->SetText("Bùsqueda...");
        $this->Navigator->PageNumber = $this->ds->AbsolutePage;
        $this->Navigator->TotalPages = $this->ds->PageCount();
        $this->Link1->Show();
        $this->concomprobantes_condetall_TotalRecords->Show();
        $this->Sorter_com_TipoComp->Show();
        $this->Sorter_com_FecTrans->Show();
        $this->Sorter_com_FecContab->Show();
        $this->Sorter_com_Usuario->Show();
        $this->Sorter_com_NumPeriodo->Show();
        $this->Sorter_det_CodCuenta->Show();
        $this->Sorter_det_IDAuxiliar->Show();
        $this->Sorter_det_ValDebito->Show();
        $this->Sorter_det_ValCredito->Show();
        $this->Sorter_det_Glosa->Show();
        $this->Sorter_det_NumCheque->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @3-FEDDB640
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->lnCompr->Errors->ToString();
        $errors .= $this->com_FecTrans->Errors->ToString();
        $errors .= $this->com_FecContab->Errors->ToString();
        $errors .= $this->com_Usuario->Errors->ToString();
        $errors .= $this->com_FecDigita->Errors->ToString();
        $errors .= $this->com_NumPeriodo->Errors->ToString();
        $errors .= $this->det_CodCuenta->Errors->ToString();
        $errors .= $this->det_IDAuxiliar->Errors->ToString();
        $errors .= $this->per_Apellidos->Errors->ToString();
        $errors .= $this->act_Descripcion->Errors->ToString();
        $errors .= $this->det_ValDebito->Errors->ToString();
        $errors .= $this->det_ValCredito->Errors->ToString();
        $errors .= $this->det_Glosa->Errors->ToString();
        $errors .= $this->det_NumCheque->Errors->ToString();
        $errors .= $this->alt_lnCompr->Errors->ToString();
        $errors .= $this->Alt_com_FecTrans->Errors->ToString();
        $errors .= $this->Alt_com_FecContab->Errors->ToString();
        $errors .= $this->Alt_com_Usuario->Errors->ToString();
        $errors .= $this->Alt_com_FecDigita->Errors->ToString();
        $errors .= $this->Alt_com_NumPeriodo->Errors->ToString();
        $errors .= $this->Alt_det_CodCuenta->Errors->ToString();
        $errors .= $this->Alt_det_IDAuxiliar->Errors->ToString();
        $errors .= $this->Alt_per_Apellidos->Errors->ToString();
        $errors .= $this->Alt_act_Descripcion->Errors->ToString();
        $errors .= $this->Alt_det_ValDebito->Errors->ToString();
        $errors .= $this->Alt_det_ValCredito->Errors->ToString();
        $errors .= $this->Alt_det_Glosa->Errors->ToString();
        $errors .= $this->Alt_det_NumCheque->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End movim_lista Class @3-FCB6E20C

class clsmovim_listaDataSource extends clsDBdatos {  //movim_listaDataSource Class @3-B3158751

//DataSource Variables @3-91EACAF8
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $Link1;
    var $lnCompr;
    var $com_FecTrans;
    var $com_FecContab;
    var $com_Usuario;
    var $com_FecDigita;
    var $com_NumPeriodo;
    var $det_CodCuenta;
    var $det_IDAuxiliar;
    var $per_Apellidos;
    var $act_Descripcion;
    var $det_ValDebito;
    var $det_ValCredito;
    var $det_Glosa;
    var $det_NumCheque;
    var $alt_lnCompr;
    var $Alt_com_FecTrans;
    var $Alt_com_FecContab;
    var $Alt_com_Usuario;
    var $Alt_com_FecDigita;
    var $Alt_com_NumPeriodo;
    var $Alt_det_CodCuenta;
    var $Alt_det_IDAuxiliar;
    var $Alt_per_Apellidos;
    var $Alt_act_Descripcion;
    var $Alt_det_ValDebito;
    var $Alt_det_ValCredito;
    var $Alt_det_Glosa;
    var $Alt_det_NumCheque;
//End DataSource Variables

//Class_Initialize Event @3-45557A55
    function clsmovim_listaDataSource()
    {
        $this->ErrorBlock = "Grid movim_lista";
        $this->Initialize();
        $this->Link1 = new clsField("Link1", ccsText, "");
        $this->lnCompr = new clsField("lnCompr", ccsText, "");
        $this->com_FecTrans = new clsField("com_FecTrans", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->com_FecContab = new clsField("com_FecContab", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->com_Usuario = new clsField("com_Usuario", ccsText, "");
        $this->com_FecDigita = new clsField("com_FecDigita", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->com_NumPeriodo = new clsField("com_NumPeriodo", ccsInteger, "");
        $this->det_CodCuenta = new clsField("det_CodCuenta", ccsText, "");
        $this->det_IDAuxiliar = new clsField("det_IDAuxiliar", ccsInteger, "");
        $this->per_Apellidos = new clsField("per_Apellidos", ccsText, "");
        $this->act_Descripcion = new clsField("act_Descripcion", ccsText, "");
        $this->det_ValDebito = new clsField("det_ValDebito", ccsFloat, "");
        $this->det_ValCredito = new clsField("det_ValCredito", ccsFloat, "");
        $this->det_Glosa = new clsField("det_Glosa", ccsText, "");
        $this->det_NumCheque = new clsField("det_NumCheque", ccsInteger, "");
        $this->alt_lnCompr = new clsField("alt_lnCompr", ccsText, "");
        $this->Alt_com_FecTrans = new clsField("Alt_com_FecTrans", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->Alt_com_FecContab = new clsField("Alt_com_FecContab", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->Alt_com_Usuario = new clsField("Alt_com_Usuario", ccsText, "");
        $this->Alt_com_FecDigita = new clsField("Alt_com_FecDigita", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->Alt_com_NumPeriodo = new clsField("Alt_com_NumPeriodo", ccsInteger, "");
        $this->Alt_det_CodCuenta = new clsField("Alt_det_CodCuenta", ccsText, "");
        $this->Alt_det_IDAuxiliar = new clsField("Alt_det_IDAuxiliar", ccsInteger, "");
        $this->Alt_per_Apellidos = new clsField("Alt_per_Apellidos", ccsText, "");
        $this->Alt_act_Descripcion = new clsField("Alt_act_Descripcion", ccsText, "");
        $this->Alt_det_ValDebito = new clsField("Alt_det_ValDebito", ccsFloat, "");
        $this->Alt_det_ValCredito = new clsField("Alt_det_ValCredito", ccsFloat, "");
        $this->Alt_det_Glosa = new clsField("Alt_det_Glosa", ccsText, "");
        $this->Alt_det_NumCheque = new clsField("Alt_det_NumCheque", ccsInteger, "");

    }
//End Class_Initialize Event

//SetOrder Method @3-2F91DC6A
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_com_TipoComp" => array("com_TipoComp", ""), 
            "Sorter_com_FecTrans" => array("com_FecTrans", ""), 
            "Sorter_com_FecContab" => array("com_FecContab", ""), 
            "Sorter_com_Usuario" => array("com_Usuario", ""), 
            "Sorter_com_NumPeriodo" => array("com_NumPeriodo", ""), 
            "Sorter_det_CodCuenta" => array("det_CodCuenta", ""), 
            "Sorter_det_IDAuxiliar" => array("det_IDAuxiliar", ""), 
            "Sorter_det_ValDebito" => array("det_ValDebito", ""), 
            "Sorter_det_ValCredito" => array("det_ValCredito", ""), 
            "Sorter_det_Glosa" => array("det_Glosa", ""), 
            "Sorter_det_NumCheque" => array("det_NumCheque", "")));
    }
//End SetOrder Method

//Prepare Method @3-5CF7E42A
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_com_TipoComp", ccsText, "", "", $this->Parameters["urls_com_TipoComp"], "", false);
        $this->wp->AddParameter("2", "urls_com_NumComp", ccsInteger, "", "", $this->Parameters["urls_com_NumComp"], "", false);
        $this->wp->AddParameter("3", "urls_com_RegNumero", ccsInteger, "", "", $this->Parameters["urls_com_RegNumero"], "", false);
        $this->wp->AddParameter("4", "urls_com_FecTrans", ccsDate, Array("mm", "/", "dd", "/", "yyyy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->Parameters["urls_com_FecTrans"], "", false);
        $this->wp->AddParameter("5", "urls_com_FecContab", ccsDate, Array("mm", "/", "dd", "/", "yyyy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->Parameters["urls_com_FecContab"], "", false);
        $this->wp->AddParameter("6", "urls_com_Usuario", ccsText, "", "", $this->Parameters["urls_com_Usuario"], "", false);
        $this->wp->AddParameter("7", "urls_com_NumPeriodo", ccsInteger, "", "", $this->Parameters["urls_com_NumPeriodo"], "", false);
        $this->wp->AddParameter("8", "urls_com_FecDigita", ccsDate, Array("mm", "/", "dd", "/", "yyyy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->Parameters["urls_com_FecDigita"], "", false);
        $this->wp->AddParameter("9", "urls_det_IDAuxiliar", ccsInteger, "", "", $this->Parameters["urls_det_IDAuxiliar"], "", false);
        $this->wp->AddParameter("10", "urls_det_ValDebito", ccsFloat, "", "", $this->Parameters["urls_det_ValDebito"], "", false);
        $this->wp->AddParameter("11", "urls_det_ValCredito", ccsFloat, "", "", $this->Parameters["urls_det_ValCredito"], "", false);
        $this->wp->AddParameter("12", "urls_det_Glosa", ccsText, "", "", $this->Parameters["urls_det_Glosa"], "", false);
        $this->wp->AddParameter("13", "urls_det_NumCheque", ccsInteger, "", "", $this->Parameters["urls_det_NumCheque"], "", false);
        $this->wp->AddParameter("14", "urls_det_FecCheque", ccsDate, Array("mm", "/", "dd", "/", "yyyy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->Parameters["urls_det_FecCheque"], "", false);
        $this->wp->AddParameter("15", "urls_det_CodCuenta", ccsText, "", "", $this->Parameters["urls_det_CodCuenta"], "", false);
        $this->wp->AddParameter("16", "urls_desc_auxil", ccsText, "", "", $this->Parameters["urls_desc_auxil"], "", false);
        $this->wp->AddParameter("17", "urls_act_Descripcion", ccsText, "", "", $this->Parameters["urls_act_Descripcion"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opContains, "com_TipoComp", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opEqual, "com_NumComp", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsInteger),false);
        $this->wp->Criterion[3] = $this->wp->Operation(opEqual, "com_RegNumero", $this->wp->GetDBValue("3"), $this->ToSQL($this->wp->GetDBValue("3"), ccsInteger),false);
        $this->wp->Criterion[4] = $this->wp->Operation(opEqual, "com_FecTrans", $this->wp->GetDBValue("4"), $this->ToSQL($this->wp->GetDBValue("4"), ccsDate),false);
        $this->wp->Criterion[5] = $this->wp->Operation(opEqual, "com_FecContab", $this->wp->GetDBValue("5"), $this->ToSQL($this->wp->GetDBValue("5"), ccsDate),false);
        $this->wp->Criterion[6] = $this->wp->Operation(opEqual, "com_Usuario", $this->wp->GetDBValue("6"), $this->ToSQL($this->wp->GetDBValue("6"), ccsText),false);
        $this->wp->Criterion[7] = $this->wp->Operation(opEqual, "com_NumPeriodo", $this->wp->GetDBValue("7"), $this->ToSQL($this->wp->GetDBValue("7"), ccsInteger),false);
        $this->wp->Criterion[8] = $this->wp->Operation(opEqual, "com_FecDigita", $this->wp->GetDBValue("8"), $this->ToSQL($this->wp->GetDBValue("8"), ccsDate),false);
        $this->wp->Criterion[9] = $this->wp->Operation(opEqual, "det_IDAuxiliar", $this->wp->GetDBValue("9"), $this->ToSQL($this->wp->GetDBValue("9"), ccsInteger),false);
        $this->wp->Criterion[10] = $this->wp->Operation(opEqual, "det_ValDebito", $this->wp->GetDBValue("10"), $this->ToSQL($this->wp->GetDBValue("10"), ccsFloat),false);
        $this->wp->Criterion[11] = $this->wp->Operation(opEqual, "det_ValCredito", $this->wp->GetDBValue("11"), $this->ToSQL($this->wp->GetDBValue("11"), ccsFloat),false);
        $this->wp->Criterion[12] = $this->wp->Operation(opContains, "det_Glosa", $this->wp->GetDBValue("12"), $this->ToSQL($this->wp->GetDBValue("12"), ccsText),false);
        $this->wp->Criterion[13] = $this->wp->Operation(opEqual, "det_NumCheque", $this->wp->GetDBValue("13"), $this->ToSQL($this->wp->GetDBValue("13"), ccsInteger),false);
        $this->wp->Criterion[14] = $this->wp->Operation(opEqual, "det_FecCheque", $this->wp->GetDBValue("14"), $this->ToSQL($this->wp->GetDBValue("14"), ccsDate),false);
        $this->wp->Criterion[15] = $this->wp->Operation(opEqual, "det_CodCuenta", $this->wp->GetDBValue("15"), $this->ToSQL($this->wp->GetDBValue("15"), ccsText),false);
        $this->wp->Criterion[16] = $this->wp->Operation(opEqual, "per_Apellidos", $this->wp->GetDBValue("16"), $this->ToSQL($this->wp->GetDBValue("16"), ccsText),false);
        $this->wp->Criterion[17] = $this->wp->Operation(opBeginsWith, "act_Descripcion", $this->wp->GetDBValue("17"), $this->ToSQL($this->wp->GetDBValue("17"), ccsText),false);
        $this->Where = $this->wp->opOR(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->Criterion[1], $this->wp->Criterion[2]), $this->wp->Criterion[3]), $this->wp->Criterion[4]), $this->wp->Criterion[5]), $this->wp->Criterion[6]), $this->wp->Criterion[7]), $this->wp->Criterion[8]), $this->wp->Criterion[9]), $this->wp->Criterion[10]), $this->wp->Criterion[11]), $this->wp->Criterion[12]), $this->wp->Criterion[13]), $this->wp->Criterion[14]), $this->wp->Criterion[15]), $this->wp->Criterion[16]), $this->wp->Criterion[17]);
    }
//End Prepare Method

//Open Method @3-411A6C65
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM ((condetalle INNER JOIN concomprobantes ON concomprobantes.com_RegNumero = condetalle.det_RegNumero) LEFT JOIN conpersonas ON condetalle.det_IDAuxiliar = conpersonas.per_CodAuxiliar) LEFT JOIN conactivos ON condetalle.det_IDAuxiliar = conactivos.act_CodAuxiliar";
        $this->SQL = "SELECT com_TipoComp, com_NumComp, com_RegNumero, com_FecTrans, com_FecContab, com_Usuario, com_NumPeriodo, com_FecDigita, det_IDAuxiliar, det_ValDebito, det_ValCredito, det_Glosa, det_NumCheque, det_FecCheque, det_CodCuenta, per_Apellidos, act_Descripcion, concat(com_TipoComp,'  ',com_NumComp) AS lnCompr, left(per_Apellidos,20) AS per_Apellidos, left(act_Descripcion,20) AS act_Descripcion, left(det_Glosa,35) AS det_Glosa, concat(com_TipoComp,'  ',com_NumComp) AS alt_lnCompr, left(per_Apellidos,20) AS Alt_per_Apellidos, left(act_Descripcion,20) AS Alt_act_Descripcion, left(det_Glosa,35) AS Alt_det_Glosa  " .
        "FROM ((condetalle INNER JOIN concomprobantes ON concomprobantes.com_RegNumero = condetalle.det_RegNumero) LEFT JOIN conpersonas ON condetalle.det_IDAuxiliar = conpersonas.per_CodAuxiliar) LEFT JOIN conactivos ON condetalle.det_IDAuxiliar = conactivos.act_CodAuxiliar";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        if (!$this->Order) $this->Order = " com_FecContab ";
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @3-9C124752
    function SetValues()
    {
        $this->lnCompr->SetDBValue($this->f("lnCompr"));
        $this->com_FecTrans->SetDBValue(trim($this->f("com_FecTrans")));
        $this->com_FecContab->SetDBValue(trim($this->f("com_FecContab")));
        $this->com_Usuario->SetDBValue($this->f("com_Usuario"));
        $this->com_FecDigita->SetDBValue(trim($this->f("com_FecDigita")));
        $this->com_NumPeriodo->SetDBValue(trim($this->f("com_NumPeriodo")));
        $this->det_CodCuenta->SetDBValue($this->f("det_CodCuenta"));
        $this->det_IDAuxiliar->SetDBValue(trim($this->f("det_IDAuxiliar")));
        $this->per_Apellidos->SetDBValue($this->f("per_Apellidos"));
        $this->act_Descripcion->SetDBValue($this->f("act_Descripcion"));
        $this->det_ValDebito->SetDBValue(trim($this->f("det_ValDebito")));
        $this->det_ValCredito->SetDBValue(trim($this->f("det_ValCredito")));
        $this->det_Glosa->SetDBValue($this->f("det_Glosa"));
        $this->det_NumCheque->SetDBValue(trim($this->f("det_NumCheque")));
        $this->alt_lnCompr->SetDBValue($this->f("alt_lnCompr"));
        $this->Alt_com_FecTrans->SetDBValue(trim($this->f("com_FecTrans")));
        $this->Alt_com_FecContab->SetDBValue(trim($this->f("com_FecContab")));
        $this->Alt_com_Usuario->SetDBValue($this->f("com_Usuario"));
        $this->Alt_com_FecDigita->SetDBValue(trim($this->f("com_FecDigita")));
        $this->Alt_com_NumPeriodo->SetDBValue(trim($this->f("com_NumPeriodo")));
        $this->Alt_det_CodCuenta->SetDBValue($this->f("det_CodCuenta"));
        $this->Alt_det_IDAuxiliar->SetDBValue(trim($this->f("det_IDAuxiliar")));
        $this->Alt_per_Apellidos->SetDBValue($this->f("Alt_per_Apellidos"));
        $this->Alt_act_Descripcion->SetDBValue($this->f("Alt_act_Descripcion"));
        $this->Alt_det_ValDebito->SetDBValue(trim($this->f("det_ValDebito")));
        $this->Alt_det_ValCredito->SetDBValue(trim($this->f("det_ValCredito")));
        $this->Alt_det_Glosa->SetDBValue($this->f("Alt_det_Glosa"));
        $this->Alt_det_NumCheque->SetDBValue(trim($this->f("det_NumCheque")));
    }
//End SetValues Method

} //End movim_listaDataSource Class @3-FCB6E20C

//Initialize Page @1-B87E2674
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

$FileName = "CoTrTr_movim.php";
$Redirect = "";
$TemplateFileName = "CoTrTr_movim.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-F3783ECD
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
$movim_query = new clsRecordmovim_query();
$movim_lista = new clsGridmovim_lista();
$movim_lista->Initialize();

// Events
include("./CoTrTr_movim_events.php");
BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-8284A07C
$Cabecera->Operations();
$movim_query->Operation();
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

//Show Page @1-68D8D210
$Cabecera->Show("Cabecera");
$movim_query->Show();
$movim_lista->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
//$generated_with = "<center><font face=\"Arial\"><small>Generated with CodeCharge Studio</small></font></center>";
//$main_block = preg_match("/<\/body>/i", $main_block) ? preg_replace("/<\/body>/i", $generated_with . "</body>", $main_block) : $main_block . $generated_with;
echo $main_block;
//End Show Page

//Unload Page @1-6786D1ED
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBdatos->close();
unset($Tpl);
//End Unload Page


?>
