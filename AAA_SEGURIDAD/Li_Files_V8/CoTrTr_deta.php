<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
//End Include Common Files

Class clsEditableGridCoTrTr_detalle { //CoTrTr_detalle Class @11-4425920B

//Variables @11-C5FED2DA

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
    var $DeleteAllowed = true;
    var $EditMode;
    var $ValidatingControls;
    var $Controls;
    var $ControlsErrors;

    // Class variables
    var $Sorter_det_Secuencia;
    var $Sorter_cue_Descripcion;
    var $Sorter_det_IDAuxiliar;
    var $Sorter_det_ValDebito;
    var $Sorter_det_ValCredito;
    var $Sorter_det_Glosa;
    var $Navigator;
//End Variables

//Class_Initialize Event @11-6D0289A1
    function clsEditableGridCoTrTr_detalle()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "EditableGrid CoTrTr_detalle/Error";
        $this->ComponentName = "CoTrTr_detalle";
        $this->CachedColumns["det_Secuencia"][0] = "det_Secuencia";
        $this->ds = new clsCoTrTr_detalleDataSource();

        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize) || $this->PageSize > 15)
            $this->PageSize = 15;
        else
            $this->PageSize = intval($this->PageSize);
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: EditableGrid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));

        $this->EmptyRows = 15;
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
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

        $this->SorterName = CCGetParam("CoTrTr_detalleOrder", "");
        $this->SorterDirection = CCGetParam("CoTrTr_detalleDir", "");

        $this->condetalle_concuentas_con_TotalRecords = new clsControl(ccsLabel, "condetalle_concuentas_con_TotalRecords", "condetalle_concuentas_con_TotalRecords", ccsText, "");
        $this->lbTitulo = new clsControl(ccsLabel, "lbTitulo", "lbTitulo", ccsText, "");
        $this->Sorter_det_Secuencia = new clsSorter($this->ComponentName, "Sorter_det_Secuencia", $FileName);
        $this->Sorter_cue_Descripcion = new clsSorter($this->ComponentName, "Sorter_cue_Descripcion", $FileName);
        $this->Sorter_det_IDAuxiliar = new clsSorter($this->ComponentName, "Sorter_det_IDAuxiliar", $FileName);
        $this->Sorter_det_ValDebito = new clsSorter($this->ComponentName, "Sorter_det_ValDebito", $FileName);
        $this->Sorter_det_ValCredito = new clsSorter($this->ComponentName, "Sorter_det_ValCredito", $FileName);
        $this->Sorter_det_Glosa = new clsSorter($this->ComponentName, "Sorter_det_Glosa", $FileName);
        $this->det_TipoComp = new clsControl(ccsHidden, "det_TipoComp", "Tipo de Comprobante", ccsText, "");
        $this->det_TipoComp->Required = true;
        $this->det_NumComp = new clsControl(ccsHidden, "det_NumComp", "Número de Comprobante", ccsInteger, "");
        $this->det_RegNumero = new clsControl(ccsHidden, "det_RegNumero", "Número de Registro", ccsInteger, "");
        $this->det_Secuencia = new clsControl(ccsTextBox, "det_Secuencia", "Secuencia", ccsInteger, "");
        $this->det_Secuencia->Required = true;
        $this->det_ClasRegistro = new clsControl(ccsHidden, "det_ClasRegistro", "Clase de registro", ccsInteger, "");
        $this->det_CodCuenta = new clsControl(ccsTextBox, "det_CodCuenta", "Código de Cuenta", ccsText, "");
        $this->det_CodCuenta->Required = true;
        $this->cue_Descripcion = new clsControl(ccsTextBox, "cue_Descripcion", "Descripcion de cuenta", ccsText, "");
        $this->cue_Descripcion->Required = true;
        $this->det_IDAuxiliar = new clsControl(ccsTextBox, "det_IDAuxiliar", "Codigo de  Auxiliar", ccsInteger, "");
        $this->det_IDAuxiliar->Required = true;
        $this->det_DescAuxiliar = new clsControl(ccsTextBox, "det_DescAuxiliar", "Descripcion de Auxiliar", ccsText, "");
        $this->det_DescAuxiliar->Required = true;
        $this->det_ValDebito = new clsControl(ccsTextBox, "det_ValDebito", "Valor al Debito", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""));
        $this->det_ValDebito->Required = true;
        $this->det_ValCredito = new clsControl(ccsTextBox, "det_ValCredito", "Valor al Crédito", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""));
        $this->det_ValCredito->Required = true;
        $this->det_Glosa = new clsControl(ccsTextBox, "det_Glosa", "Glosa, comentario contable", ccsText, "");
        $this->det_RefOperativa = new clsControl(ccsTextBox, "det_RefOperativa", "Referencia Operativa", ccsInteger, "");
        $this->txt_RefOperativa = new clsControl(ccsTextBox, "txt_RefOperativa", "Referencia Operativa", ccsText, "");
        $this->det_NumCheque = new clsControl(ccsTextBox, "det_NumCheque", "Nùmero de Cheque, si la transaccion corresponde a cuenta de banco", ccsInteger, "");
        $this->det_FecCheque = new clsControl(ccsTextBox, "det_FecCheque", "Fecha de vencimiento del Cheque, si es postfechado", ccsDate, Array("dd", "/", "mm", "/", "yy"));
        $this->CheckBox_Delete = new clsControl(ccsCheckBox, "CheckBox_Delete", "CheckBox_Delete", ccsBoolean, Array("Y", "N", ""));
        $this->CheckBox_Delete->CheckedValue = $this->CheckBox_Delete->GetParsedValue(true);
        $this->CheckBox_Delete->UncheckedValue = $this->CheckBox_Delete->GetParsedValue(false);
        $this->det_EstLibros = new clsControl(ccsHidden, "det_EstLibros", "Estadio en Libros", ccsInteger, "");
        $this->det_FecEjecucion = new clsControl(ccsHidden, "det_FecEjecucion", "Fecha de Ejecución", ccsDate, Array("dd", "/", "mm", "/", "yy"));
        $this->aux_ReqRefOper = new clsControl(ccsHidden, "aux_ReqRefOper", "aux_ReqRefOper", ccsText, "");
        $this->det_EstEjecucion = new clsControl(ccsHidden, "det_EstEjecucion", "Estado de Ejecución de la transacción", ccsInteger, "");
        $this->cue_ReqRefOperat = new clsControl(ccsHidden, "cue_ReqRefOperat", "Indicador de exijencia de Ref. Operativa de la cuenta", ccsInteger, "");
        $this->cue_TipAuxiliar = new clsControl(ccsHidden, "cue_TipAuxiliar", "Tipo de Auxiliar que maneja la cuenta", ccsInteger, "");
        $this->det_FecLibros = new clsControl(ccsHidden, "det_FecLibros", "Fecha en libros", ccsDate, Array("dd", "/", "mm", "/", "yy"));
        $this->tot_ValDebito = new clsControl(ccsTextBox, "tot_ValDebito", "Valor al Debito", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""));
        $this->tot_ValDebito->Required = true;
        $this->aux_Clase = new clsControl(ccsHidden, "aux_Clase", "Clase de Auxiliar", ccsText, "");  // fah
        $this->tot_ValCredito = new clsControl(ccsTextBox, "tot_ValCredito", "Valor al Crédito", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""));
        $this->tot_ValCredito->Required = true;
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpCentered);
//        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple);
        $this->Button_Submit = new clsButton("Button_Submit");
        $this->Cancel = new clsButton("Cancel");
    }
//End Class_Initialize Event

//Initialize Method @11-7B32C6DE
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);

        $this->ds->Parameters["urlcom_RegNumero"] = CCGetFromGet("com_RegNumero", "");
    }
//End Initialize Method

//GetFormParameters Method @11-703F800E
    function GetFormParameters()
    {
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->FormParameters["det_TipoComp"][$RowNumber] = CCGetFromPost("det_TipoComp_" . $RowNumber);
            $this->FormParameters["det_NumComp"][$RowNumber] = CCGetFromPost("det_NumComp_" . $RowNumber);
            $this->FormParameters["det_RegNumero"][$RowNumber] = CCGetFromPost("det_RegNumero_" . $RowNumber);
            $this->FormParameters["det_Secuencia"][$RowNumber] = CCGetFromPost("det_Secuencia_" . $RowNumber);
            $this->FormParameters["det_ClasRegistro"][$RowNumber] = CCGetFromPost("det_ClasRegistro_" . $RowNumber);
            $this->FormParameters["det_CodCuenta"][$RowNumber] = CCGetFromPost("det_CodCuenta_" . $RowNumber);
            $this->FormParameters["cue_Descripcion"][$RowNumber] = CCGetFromPost("cue_Descripcion_" . $RowNumber);
            $this->FormParameters["det_IDAuxiliar"][$RowNumber] = CCGetFromPost("det_IDAuxiliar_" . $RowNumber);
            $this->FormParameters["det_DescAuxiliar"][$RowNumber] = CCGetFromPost("det_DescAuxiliar_" . $RowNumber);
            $this->FormParameters["det_ValDebito"][$RowNumber] = CCGetFromPost("det_ValDebito_" . $RowNumber);
            $this->FormParameters["det_ValCredito"][$RowNumber] = CCGetFromPost("det_ValCredito_" . $RowNumber);
            $this->FormParameters["det_Glosa"][$RowNumber] = CCGetFromPost("det_Glosa_" . $RowNumber);
            $this->FormParameters["det_RefOperativa"][$RowNumber] = CCGetFromPost("det_RefOperativa_" . $RowNumber);
            $this->FormParameters["txt_RefOperativa"][$RowNumber] = CCGetFromPost("txt_RefOperativa_" . $RowNumber);
            $this->FormParameters["det_NumCheque"][$RowNumber] = CCGetFromPost("det_NumCheque_" . $RowNumber);
            $this->FormParameters["det_FecCheque"][$RowNumber] = CCGetFromPost("det_FecCheque_" . $RowNumber);
            $this->FormParameters["CheckBox_Delete"][$RowNumber] = CCGetFromPost("CheckBox_Delete_" . $RowNumber);
            $this->FormParameters["det_EstLibros"][$RowNumber] = CCGetFromPost("det_EstLibros_" . $RowNumber);
            $this->FormParameters["det_FecEjecucion"][$RowNumber] = CCGetFromPost("det_FecEjecucion_" . $RowNumber);
            $this->FormParameters["aux_ReqRefOper"][$RowNumber] = CCGetFromPost("aux_ReqRefOper_" . $RowNumber);
            $this->FormParameters["det_EstEjecucion"][$RowNumber] = CCGetFromPost("det_EstEjecucion_" . $RowNumber);
            $this->FormParameters["cue_ReqRefOperat"][$RowNumber] = CCGetFromPost("cue_ReqRefOperat_" . $RowNumber);
            $this->FormParameters["cue_TipAuxiliar"][$RowNumber] = CCGetFromPost("cue_TipAuxiliar_" . $RowNumber);
            $this->FormParameters["aux_Clase"][$RowNumber] = CCGetFromPost("aux_Clase_" . $RowNumber);
            $this->FormParameters["det_FecLibros"][$RowNumber] = CCGetFromPost("det_FecLibros_" . $RowNumber);
        }
    }
//End GetFormParameters Method

//Validate Method @11-F46A7B8D
    function Validate()
    {
        $Validation = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");

        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["det_Secuencia"] = $this->CachedColumns["det_Secuencia"][$RowNumber];
            $this->det_TipoComp->SetText($this->FormParameters["det_TipoComp"][$RowNumber], $RowNumber);
            $this->det_NumComp->SetText($this->FormParameters["det_NumComp"][$RowNumber], $RowNumber);
            $this->det_RegNumero->SetText($this->FormParameters["det_RegNumero"][$RowNumber], $RowNumber);
            $this->det_Secuencia->SetText($this->FormParameters["det_Secuencia"][$RowNumber], $RowNumber);
            $this->det_ClasRegistro->SetText($this->FormParameters["det_ClasRegistro"][$RowNumber], $RowNumber);
            $this->det_CodCuenta->SetText($this->FormParameters["det_CodCuenta"][$RowNumber], $RowNumber);
            $this->cue_Descripcion->SetText($this->FormParameters["cue_Descripcion"][$RowNumber], $RowNumber);
            $this->det_IDAuxiliar->SetText($this->FormParameters["det_IDAuxiliar"][$RowNumber], $RowNumber);
            $this->det_DescAuxiliar->SetText($this->FormParameters["det_DescAuxiliar"][$RowNumber], $RowNumber);
            $this->det_ValDebito->SetText($this->FormParameters["det_ValDebito"][$RowNumber], $RowNumber);
            $this->det_ValCredito->SetText($this->FormParameters["det_ValCredito"][$RowNumber], $RowNumber);
            $this->det_Glosa->SetText($this->FormParameters["det_Glosa"][$RowNumber], $RowNumber);
            $this->det_RefOperativa->SetText($this->FormParameters["det_RefOperativa"][$RowNumber], $RowNumber);
            $this->txt_RefOperativa->SetText($this->FormParameters["txt_RefOperativa"][$RowNumber], $RowNumber);
            $this->det_NumCheque->SetText($this->FormParameters["det_NumCheque"][$RowNumber], $RowNumber);
            $this->det_FecCheque->SetText($this->FormParameters["det_FecCheque"][$RowNumber], $RowNumber);
            $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$RowNumber], $RowNumber);
            $this->det_EstLibros->SetText($this->FormParameters["det_EstLibros"][$RowNumber], $RowNumber);
            $this->det_FecEjecucion->SetText($this->FormParameters["det_FecEjecucion"][$RowNumber], $RowNumber);
            $this->aux_ReqRefOper->SetText($this->FormParameters["aux_ReqRefOper"][$RowNumber], $RowNumber);
            $this->det_EstEjecucion->SetText($this->FormParameters["det_EstEjecucion"][$RowNumber], $RowNumber);
            $this->cue_ReqRefOperat->SetText($this->FormParameters["cue_ReqRefOperat"][$RowNumber], $RowNumber);
            $this->cue_TipAuxiliar->SetText($this->FormParameters["cue_TipAuxiliar"][$RowNumber], $RowNumber);
            $this->aux_Clase->SetText($this->FormParameters["aux_Clase"][$RowNumber], $RowNumber);
            $this->det_FecLibros->SetText($this->FormParameters["det_FecLibros"][$RowNumber], $RowNumber);
            if ($this->UpdatedRows >= $RowNumber) {
                if(!$this->CheckBox_Delete->Value)
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

//ValidateRow Method @11-589F220D
    function ValidateRow($RowNumber)
    {
        $Validation = true;
        $Validation = ($this->det_TipoComp->Validate() && $Validation);
        $Validation = ($this->det_NumComp->Validate() && $Validation);
        $Validation = ($this->det_RegNumero->Validate() && $Validation);
        $Validation = ($this->det_Secuencia->Validate() && $Validation);
        $Validation = ($this->det_ClasRegistro->Validate() && $Validation);
        $Validation = ($this->det_CodCuenta->Validate() && $Validation);
        $Validation = ($this->cue_Descripcion->Validate() && $Validation);
        $Validation = ($this->det_IDAuxiliar->Validate() && $Validation);
        $Validation = ($this->det_DescAuxiliar->Validate() && $Validation);
        $Validation = ($this->det_ValDebito->Validate() && $Validation);
        $Validation = ($this->det_ValCredito->Validate() && $Validation);
        $Validation = ($this->det_Glosa->Validate() && $Validation);
        $Validation = ($this->det_RefOperativa->Validate() && $Validation);
        $Validation = ($this->txt_RefOperativa->Validate() && $Validation);
        $Validation = ($this->det_NumCheque->Validate() && $Validation);
        $Validation = ($this->det_FecCheque->Validate() && $Validation);
        $Validation = ($this->CheckBox_Delete->Validate() && $Validation);
        $Validation = ($this->det_EstLibros->Validate() && $Validation);
        $Validation = ($this->det_FecEjecucion->Validate() && $Validation);
        $Validation = ($this->aux_ReqRefOper->Validate() && $Validation);
        $Validation = ($this->det_EstEjecucion->Validate() && $Validation);
        $Validation = ($this->cue_ReqRefOperat->Validate() && $Validation);
        $Validation = ($this->cue_TipAuxiliar->Validate() && $Validation);
        $Validation = ($this->det_FecLibros->Validate() && $Validation);
        $errors = "";
        if(!$Validation)
        {
            $errors .= $this->det_TipoComp->Errors->ToString();
            $errors .= $this->det_NumComp->Errors->ToString();
            $errors .= $this->det_RegNumero->Errors->ToString();
            $errors .= $this->det_Secuencia->Errors->ToString();
            $errors .= $this->det_ClasRegistro->Errors->ToString();
            $errors .= $this->det_CodCuenta->Errors->ToString();
            $errors .= $this->cue_Descripcion->Errors->ToString();
            $errors .= $this->det_IDAuxiliar->Errors->ToString();
            $errors .= $this->det_DescAuxiliar->Errors->ToString();
            $errors .= $this->det_ValDebito->Errors->ToString();
            $errors .= $this->det_ValCredito->Errors->ToString();
            $errors .= $this->det_Glosa->Errors->ToString();
            $errors .= $this->det_RefOperativa->Errors->ToString();
            $errors .= $this->txt_RefOperativa->Errors->ToString();
            $errors .= $this->det_NumCheque->Errors->ToString();
            $errors .= $this->det_FecCheque->Errors->ToString();
            $errors .= $this->CheckBox_Delete->Errors->ToString();
            $errors .= $this->det_EstLibros->Errors->ToString();
            $errors .= $this->det_FecEjecucion->Errors->ToString();
            $errors .= $this->aux_ReqRefOper->Errors->ToString();
            $errors .= $this->det_EstEjecucion->Errors->ToString();
            $errors .= $this->cue_ReqRefOperat->Errors->ToString();
            $errors .= $this->cue_TipAuxiliar->Errors->ToString();
            $errors .= $this->det_FecLibros->Errors->ToString();
            $this->det_TipoComp->Errors->Clear();
            $this->det_NumComp->Errors->Clear();
            $this->det_RegNumero->Errors->Clear();
            $this->det_Secuencia->Errors->Clear();
            $this->det_ClasRegistro->Errors->Clear();
            $this->det_CodCuenta->Errors->Clear();
            $this->cue_Descripcion->Errors->Clear();
            $this->det_IDAuxiliar->Errors->Clear();
            $this->det_DescAuxiliar->Errors->Clear();
            $this->det_ValDebito->Errors->Clear();
            $this->det_ValCredito->Errors->Clear();
            $this->det_Glosa->Errors->Clear();
            $this->det_RefOperativa->Errors->Clear();
            $this->txt_RefOperativa->Errors->Clear();
            $this->det_NumCheque->Errors->Clear();
            $this->det_FecCheque->Errors->Clear();
            $this->CheckBox_Delete->Errors->Clear();
            $this->det_EstLibros->Errors->Clear();
            $this->det_FecEjecucion->Errors->Clear();
            $this->aux_ReqRefOper->Errors->Clear();
            $this->det_EstEjecucion->Errors->Clear();
            $this->cue_ReqRefOperat->Errors->Clear();
            $this->cue_TipAuxiliar->Errors->Clear();
            $this->det_FecLibros->Errors->Clear();
        }
        $this->RowsErrors[$RowNumber] = $errors;
        return $Validation;
    }
//End ValidateRow Method

//CheckInsert Method @11-725FE909
    function CheckInsert($RowNumber)
    {
        $filed = false;
        $filed = ($filed || strlen($this->FormParameters["det_TipoComp"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["det_NumComp"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["det_RegNumero"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["det_Secuencia"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["det_ClasRegistro"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["det_CodCuenta"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cue_Descripcion"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["det_IDAuxiliar"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["det_DescAuxiliar"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["det_ValDebito"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["det_ValCredito"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["det_Glosa"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["det_RefOperativa"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["txt_RefOperativa"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["det_NumCheque"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["det_FecCheque"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["det_EstLibros"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["det_FecEjecucion"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["aux_ReqRefOper"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["det_EstEjecucion"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cue_ReqRefOperat"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["cue_TipAuxiliar"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["det_FecLibros"][$RowNumber]));
        return $filed;
    }
//End CheckInsert Method

//CheckErrors Method @11-242E5992
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @11-7B861278
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

//UpdateGrid Method @11-E4BE608F
    function UpdateGrid()
    {
//      fah --->
        global $gfValTotal;
        global $gfCosTotal;
        global $gfCanTotal;
        global $gfCanElim;
        global $gfCanInser;
        global $gfCanModif;
        $gfValTotal = 0;
        $gfCosTotal = 0;
        $gfCanTotal = 0;
        $gfCanElim = 0;
//      <--- fah
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSubmit");
        if(!$this->Validate()) return;
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["det_Secuencia"] = $this->CachedColumns["det_Secuencia"][$RowNumber];
            $this->det_TipoComp->SetText($this->FormParameters["det_TipoComp"][$RowNumber], $RowNumber);
            $this->det_NumComp->SetText($this->FormParameters["det_NumComp"][$RowNumber], $RowNumber);
            $this->det_RegNumero->SetText($this->FormParameters["det_RegNumero"][$RowNumber], $RowNumber);
            $this->det_Secuencia->SetText($this->FormParameters["det_Secuencia"][$RowNumber], $RowNumber);
            $this->det_ClasRegistro->SetText($this->FormParameters["det_ClasRegistro"][$RowNumber], $RowNumber);
            $this->det_CodCuenta->SetText($this->FormParameters["det_CodCuenta"][$RowNumber], $RowNumber);
            $this->cue_Descripcion->SetText($this->FormParameters["cue_Descripcion"][$RowNumber], $RowNumber);
            $this->det_IDAuxiliar->SetText($this->FormParameters["det_IDAuxiliar"][$RowNumber], $RowNumber);
            $this->det_DescAuxiliar->SetText($this->FormParameters["det_DescAuxiliar"][$RowNumber], $RowNumber);
            $this->det_ValDebito->SetText($this->FormParameters["det_ValDebito"][$RowNumber], $RowNumber);
            $this->det_ValCredito->SetText($this->FormParameters["det_ValCredito"][$RowNumber], $RowNumber);
            $this->det_Glosa->SetText($this->FormParameters["det_Glosa"][$RowNumber], $RowNumber);
            $this->det_RefOperativa->SetText($this->FormParameters["det_RefOperativa"][$RowNumber], $RowNumber);
            $this->txt_RefOperativa->SetText($this->FormParameters["txt_RefOperativa"][$RowNumber], $RowNumber);
            $this->det_NumCheque->SetText($this->FormParameters["det_NumCheque"][$RowNumber], $RowNumber);
            $this->det_FecCheque->SetText($this->FormParameters["det_FecCheque"][$RowNumber], $RowNumber);
            $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$RowNumber], $RowNumber);
            $this->det_EstLibros->SetText($this->FormParameters["det_EstLibros"][$RowNumber], $RowNumber);
            $this->det_FecEjecucion->SetText($this->FormParameters["det_FecEjecucion"][$RowNumber], $RowNumber);
            $this->aux_ReqRefOper->SetText($this->FormParameters["aux_ReqRefOper"][$RowNumber], $RowNumber);
            $this->det_EstEjecucion->SetText($this->FormParameters["det_EstEjecucion"][$RowNumber], $RowNumber);
            $this->cue_ReqRefOperat->SetText($this->FormParameters["cue_ReqRefOperat"][$RowNumber], $RowNumber);
            $this->cue_TipAuxiliar->SetText($this->FormParameters["cue_TipAuxiliar"][$RowNumber], $RowNumber);
            $this->aux_Clase->SetText($this->FormParameters["aux_Clase"][$RowNumber], $RowNumber);   // fah
            $this->det_FecLibros->SetText($this->FormParameters["det_FecLibros"][$RowNumber], $RowNumber);
//          fah --->
            if(!$this->CheckBox_Delete->Value){ // No acumular lo que se va a eliminar
	            $gfCosTotal += $this->FormParameters["det_ValDebito"][$RowNumber];
	            $gfValTotal += $this->FormParameters["det_ValCredito"][$RowNumber];
            }
//          <--- fah
            if ($this->UpdatedRows >= $RowNumber) {
                if($this->CheckBox_Delete->Value) {
                    if($this->DeleteAllowed) $this->DeleteRow($RowNumber);
                    $gfCanElim+=1;  // -----fah
                } else if($this->UpdateAllowed) {
                    $this->UpdateRow($RowNumber);
                    $gfCanModif+=1;// -----fah
                }
            }
            else if($this->CheckInsert($RowNumber) && $this->InsertAllowed)
            {
                $this->InsertRow($RowNumber);
                $gfCanInser+=1;// -----fah
            }
        }
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterSubmit");
        return ($this->Errors->Count() == 0);
    }
//End UpdateGrid Method

//InsertRow Method @11-D5B1FBF2
    function InsertRow($RowNumber)
    {
        if(!$this->InsertAllowed) return false;
        $this->ds->det_TipoComp->SetValue($this->det_TipoComp->GetValue());
        $this->ds->det_NumComp->SetValue($this->det_NumComp->GetValue());
        $this->ds->det_Secuencia->SetValue($this->det_Secuencia->GetValue());
        $this->ds->det_ClasRegistro->SetValue($this->det_ClasRegistro->GetValue());
        $this->ds->det_CodCuenta->SetValue($this->det_CodCuenta->GetValue());
        $this->ds->det_ValDebito->SetValue($this->det_ValDebito->GetValue());
        $this->ds->det_ValCredito->SetValue($this->det_ValCredito->GetValue());
        $this->ds->det_Glosa->SetValue($this->det_Glosa->GetValue());
        $this->ds->det_EstEjecucion->SetValue($this->det_EstEjecucion->GetValue());
        $this->ds->det_FecEjecucion->SetValue($this->det_FecEjecucion->GetValue());
        $this->ds->det_EstLibros->SetValue($this->det_EstLibros->GetValue());
        $this->ds->det_FecLibros->SetValue($this->det_FecLibros->GetValue());
        $this->ds->det_RefOperativa->SetValue($this->det_RefOperativa->GetValue());
        $this->ds->txt_RefOperativa->SetValue($this->txt_RefOperativa->GetValue());
        $this->ds->det_NumCheque->SetValue($this->det_NumCheque->GetValue());
        $this->ds->det_FecCheque->SetValue($this->det_FecCheque->GetValue());
        $this->ds->det_IDAuxiliar->SetValue($this->det_IDAuxiliar->GetValue());
        $this->ds->Insert();
        $errors = "";
        if($this->ds->Errors->Count() > 0) {
            echo "Error in EditableGrid " . $this->ComponentName . " / Insert Operation";
            $this->ds->Errors->Clear();
            $this->Errors->AddError("Database command error.");
        }
        return (($this->Errors->Count() == 0) && !strlen($errors));
    }
//End InsertRow Method

//UpdateRow Method @11-F54EC514
    function UpdateRow($RowNumber)
    {
        if(!$this->UpdateAllowed) return false;
        $this->ds->det_ClasRegistro->SetValue($this->det_ClasRegistro->GetValue());
        $this->ds->det_CodCuenta->SetValue($this->det_CodCuenta->GetValue());
        $this->ds->det_IDAuxiliar->SetValue($this->det_IDAuxiliar->GetValue());
        $this->ds->det_ValDebito->SetValue($this->det_ValDebito->GetValue());
        $this->ds->det_ValCredito->SetValue($this->det_ValCredito->GetValue());
        $this->ds->det_Glosa->SetValue($this->det_Glosa->GetValue());
        $this->ds->det_RefOperativa->SetValue($this->det_RefOperativa->GetValue());
        $this->ds->txt_RefOperativa->SetValue($this->txt_RefOperativa->GetValue());
        $this->ds->det_NumCheque->SetValue($this->det_NumCheque->GetValue());
        $this->ds->det_FecCheque->SetValue($this->det_FecCheque->GetValue());
        $this->ds->det_EstEjecucion->SetValue($this->det_EstEjecucion->GetValue());
        $this->ds->det_FecEjecucion->SetValue($this->det_FecEjecucion->GetValue());
        $this->ds->det_EstLibros->SetValue($this->det_EstLibros->GetValue());
        $this->ds->det_FecLibros->SetValue($this->det_FecLibros->GetValue());
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

//DeleteRow Method @11-0C9DDC34
    function DeleteRow($RowNumber)
    {
        if(!$this->DeleteAllowed) return false;
        $this->ds->Delete();
        $errors = "";
        if($this->ds->Errors->Count() > 0) {
            echo "Error in EditableGrid " . ComponentName . " / Delete Operation";
            $this->ds->Errors->Clear();
            $this->Errors->AddError("Database command error.");
        }
        return (($this->Errors->Count() == 0) && !strlen($errors));
    }
//End DeleteRow Method

//FormScript Method @11-ED0CFD37
    function FormScript($TotalRows)
    {
        $script = "";
        $script .= "\n<script language=\"JavaScript\">\n<!--\n";
        $script .= "var CoTrTr_detalleElements;\n";
        $script .= "var CoTrTr_detalleEmptyRows = 10;\n";
        $script .= "var " . $this->ComponentName . "det_TipoCompID = 0;\n";
        $script .= "var " . $this->ComponentName . "det_NumCompID = 1;\n";
        $script .= "var " . $this->ComponentName . "det_RegNumeroID = 2;\n";
        $script .= "var " . $this->ComponentName . "det_SecuenciaID = 3;\n";
        $script .= "var " . $this->ComponentName . "det_ClasRegistroID = 4;\n";
        $script .= "var " . $this->ComponentName . "det_CodCuentaID = 5;\n";
        $script .= "var " . $this->ComponentName . "cue_DescripcionID = 6;\n";
        $script .= "var " . $this->ComponentName . "det_IDAuxiliarID = 7;\n";
        $script .= "var " . $this->ComponentName . "det_DescAuxiliarID = 8;\n";
        $script .= "var " . $this->ComponentName . "det_ValDebitoID = 9;\n";
        $script .= "var " . $this->ComponentName . "det_ValCreditoID = 10;\n";
        $script .= "var " . $this->ComponentName . "det_GlosaID = 11;\n";
        $script .= "var " . $this->ComponentName . "det_RefOperativaID = 12;\n";
        $script .= "var " . $this->ComponentName . "det_NumChequeID = 13;\n";
        $script .= "var " . $this->ComponentName . "det_FecChequeID = 14;\n";
        $script .= "var " . $this->ComponentName . "DeleteControl = 15;\n";
        $script .= "var " . $this->ComponentName . "det_EstLibrosID = 16;\n";
        $script .= "var " . $this->ComponentName . "det_FecEjecucionID = 17;\n";
        $script .= "var " . $this->ComponentName . "aux_ReqRefOperID = 18;\n";
        $script .= "var " . $this->ComponentName . "det_EstEjecucionID = 19;\n";
        $script .= "var " . $this->ComponentName . "cue_ReqRefOperatID = 20;\n";
        $script .= "var " . $this->ComponentName . "cue_TipAuxiliarID = 21;\n";
        $script .= "var " . $this->ComponentName . "det_FecLibrosID = 22;\n";
        $script .= "var " . $this->ComponentName . "aux_ClaseID = 23;\n";                 // fah
        $script .= "\nfunction initCoTrTr_detalleElements() {\n";
        $script .= "\tvar ED = document.forms[\"CoTrTr_detalle\"];\n";
        $script .= "\tCoTrTr_detalleElements = new Array (\n";
        for($i = 1; $i <= $TotalRows; $i++) {
            $script .= "\t\tnew Array(" . "ED.det_TipoComp_" . $i . ", " . "ED.det_NumComp_" . $i . ", " . "ED.det_RegNumero_" . $i . ", " . "ED.det_Secuencia_" . $i . ", " . "ED.det_ClasRegistro_" . $i . ", " . "ED.det_CodCuenta_" . $i . ", " . "ED.cue_Descripcion_" . $i . ", " . "ED.det_IDAuxiliar_" . $i . ", " . "ED.det_DescAuxiliar_" . $i . ", " . "ED.det_ValDebito_" . $i . ", " . "ED.det_ValCredito_" . $i . ", " . "ED.det_Glosa_" . $i . ", " . "ED.det_RefOperativa_" . $i . ", " . "ED.det_NumCheque_" . $i . ", " . "ED.det_FecCheque_" . $i . ", " . "ED.CheckBox_Delete_" . $i . ", " . "ED.det_EstLibros_" . $i . ", " . "ED.det_FecEjecucion_" . $i . ", " . "ED.aux_ReqRefOper_" . $i . ", " . "ED.det_EstEjecucion_" . $i . ", " . "ED.cue_ReqRefOperat_" . $i . ", " . "ED.cue_TipAuxiliar_" . $i . ", " . "ED.det_FecLibros_" . $i . ")";
            if($i != $TotalRows) $script .= ",\n";
        }
        $script .= ");\n";
        $script .= "}\n";
        $script .= "\n//-->\n</script>";
        return $script;
    }
//End FormScript Method

//SetFormState Method @11-01736EBB
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
            for($i = 2; $i < sizeof($pieces); $i = $i + 1)  {
                $piece = $pieces[$i + 0];
                $piece = str_replace("\\" . ord("\\"), "\\", $piece);
                $piece = str_replace("\\" . ord(";"), ";", $piece);
                $this->CachedColumns["det_Secuencia"][$RowNumber] = $piece;
                $RowNumber++;
            }

            if(!$RowNumber) { $RowNumber = 1; }
            for($i = 1; $i <= $this->EmptyRows; $i++) {
                $this->CachedColumns["det_Secuencia"][$RowNumber] = "";
                $RowNumber++;
            }
        }
    }
//End SetFormState Method

//GetFormState Method @11-13514F69
    function GetFormState($NonEmptyRows)
    {
        if(!$this->FormSubmitted) {
            $this->FormState  = $NonEmptyRows . ";";
            $this->FormState .= $this->InsertAllowed ? $this->EmptyRows : "0";
            if($NonEmptyRows) {
                for($i = 0; $i <= $NonEmptyRows; $i++) {
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["det_Secuencia"][$i]));
                }
            }
        }
        return $this->FormState;
    }
//End GetFormState Method

//Show Method @11-488AC72C
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
        //        $EmptyRowsLeft = $this->EmptyRows;
        // fah_mod: para hacer que se agregue records solo al final
            if ($this->ds->AbsolutePage < $this->ds->PageCount()) $EmptyRowsLeft = 0;
            else {
                if ($this->ds->RecordsCount > $this->PageSize) $resto = fmod($this->ds->RecordsCount, $this->PageSize);
                else $resto = $this->ds->RecordsCount;
                $EmptyRowsLeft = $this->PageSize - $resto;
            }
        if ($EmptyRowsLeft <= 0 && $this->ds->RecordsCount == ($this->PageSize * $this->ds->AbsolutePage)) $EmptyRowsLeft =1;
        // end fah mod
        //----------  fah bloqueo : Modificacion para Habilitar / deshabilitar Botones Segun perfil del usuario
        $aOpc = array();
        global $DBdatos;
        global $Tpl;
        if ($this->InsertAllowed) $aOpc[]="ADD";
        if ($this->UpdateAllowed) $aOpc[]="UPD";
        if ($this->DeleteAllowed) $aOpc[]="DEL";
        $ilEstado = CCGetDBValue("SELECT per_Estado FROM concomprobantes JOIN conperiodos ON per_Aplicacion = 'CO' AND per_numperiodo = com_numperiodo WHERE com_RegNumero =" . CCGetParam('com_RegNumero', -1), $DBdatos);
        fHabilitaBotonesCCS(false, $aOpc, $ilEstado );
        if ($ilEstado < 1 ) $Tpl->SetVar('lbEstado', ' **Solo para Consulta**');
        //---------- end fah bloqueo

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
                    if(!$is_next_record || !$this->DeleteAllowed)
                        $this->CheckBox_Delete->Visible = false;
                    if(!$this->FormSubmitted && $is_next_record) {
                        $this->CachedColumns["det_Secuencia"][$RowNumber] = $this->ds->CachedColumns["det_Secuencia"];
                        $this->det_TipoComp->SetValue($this->ds->det_TipoComp->GetValue());
                        $this->det_NumComp->SetValue($this->ds->det_NumComp->GetValue());
                        $this->det_RegNumero->SetValue($this->ds->det_RegNumero->GetValue());
                        $this->det_Secuencia->SetValue($this->ds->det_Secuencia->GetValue());
                        $this->det_ClasRegistro->SetValue($this->ds->det_ClasRegistro->GetValue());
                        $this->det_CodCuenta->SetValue($this->ds->det_CodCuenta->GetValue());
                        $this->cue_Descripcion->SetValue($this->ds->cue_Descripcion->GetValue());
                        $this->det_IDAuxiliar->SetValue($this->ds->det_IDAuxiliar->GetValue());
                        $this->det_DescAuxiliar->SetValue($this->ds->det_DescAuxiliar->GetValue());
                        $this->det_ValDebito->SetValue($this->ds->det_ValDebito->GetValue());
                        $this->det_ValCredito->SetValue($this->ds->det_ValCredito->GetValue());
                        $this->det_Glosa->SetValue($this->ds->det_Glosa->GetValue());
                        $this->det_RefOperativa->SetValue($this->ds->det_RefOperativa->GetValue());
                        $this->txt_RefOperativa->SetValue($this->ds->txt_RefOperativa->GetValue());
                        $this->det_NumCheque->SetValue($this->ds->det_NumCheque->GetValue());
                        $this->det_FecCheque->SetValue($this->ds->det_FecCheque->GetValue());
                        $this->det_EstLibros->SetValue($this->ds->det_EstLibros->GetValue());
                        $this->det_FecEjecucion->SetValue($this->ds->det_FecEjecucion->GetValue());
                        $this->det_EstEjecucion->SetValue($this->ds->det_EstEjecucion->GetValue());
                        $this->cue_ReqRefOperat->SetValue($this->ds->cue_ReqRefOperat->GetValue());
                        $this->cue_TipAuxiliar->SetValue($this->ds->cue_TipAuxiliar->GetValue());
                        $this->aux_Clase->SetValue($this->ds->aux_Clase->GetValue());                   // fah
                        $this->det_FecLibros->SetValue($this->ds->det_FecLibros->GetValue());
                        $this->ValidateRow($RowNumber);
                    } else if (!$this->FormSubmitted){
                        $this->CachedColumns["det_Secuencia"][$RowNumber] = "";
                        $this->det_TipoComp->SetText("");
                        $this->det_NumComp->SetText("");
                        $this->det_RegNumero->SetText("");
                        $this->det_Secuencia->SetText("");
                        $this->det_ClasRegistro->SetText("");
                        $this->det_CodCuenta->SetText("");
                        $this->cue_Descripcion->SetText("");
                        $this->det_IDAuxiliar->SetText("");
                        $this->det_DescAuxiliar->SetText("");
                        $this->det_ValDebito->SetText("");
                        $this->det_ValCredito->SetText("");
                        $this->det_Glosa->SetText("");
                        $this->det_RefOperativa->SetText("");
                        $this->txt_RefOperativa->SetText("");
                        $this->det_NumCheque->SetText("");
                        $this->det_FecCheque->SetText("");
                        $this->CheckBox_Delete->SetText("");
                        $this->det_EstLibros->SetText("");
                        $this->det_FecEjecucion->SetText("");
                        $this->aux_ReqRefOper->SetText("");
                        $this->det_EstEjecucion->SetText("");
                        $this->cue_ReqRefOperat->SetText("");
                        $this->cue_TipAuxiliar->SetText("");
                        $this->aux_Clase->SetText("");                   // fah
                        $this->det_FecLibros->SetText("");
                    } else {
                        $this->det_TipoComp->SetText($this->FormParameters["det_TipoComp"][$RowNumber], $RowNumber);
                        $this->det_NumComp->SetText($this->FormParameters["det_NumComp"][$RowNumber], $RowNumber);
                        $this->det_RegNumero->SetText($this->FormParameters["det_RegNumero"][$RowNumber], $RowNumber);
                        $this->det_Secuencia->SetText($this->FormParameters["det_Secuencia"][$RowNumber], $RowNumber);
                        $this->det_ClasRegistro->SetText($this->FormParameters["det_ClasRegistro"][$RowNumber], $RowNumber);
                        $this->det_CodCuenta->SetText($this->FormParameters["det_CodCuenta"][$RowNumber], $RowNumber);
                        $this->cue_Descripcion->SetText($this->FormParameters["cue_Descripcion"][$RowNumber], $RowNumber);
                        $this->det_IDAuxiliar->SetText($this->FormParameters["det_IDAuxiliar"][$RowNumber], $RowNumber);
                        $this->det_DescAuxiliar->SetText($this->FormParameters["det_DescAuxiliar"][$RowNumber], $RowNumber);
                        $this->det_ValDebito->SetText($this->FormParameters["det_ValDebito"][$RowNumber], $RowNumber);
                        $this->det_ValCredito->SetText($this->FormParameters["det_ValCredito"][$RowNumber], $RowNumber);
                        $this->det_Glosa->SetText($this->FormParameters["det_Glosa"][$RowNumber], $RowNumber);
                        $this->det_RefOperativa->SetText($this->FormParameters["det_RefOperativa"][$RowNumber], $RowNumber);
                        $this->txt_RefOperativa->SetText($this->FormParameters["txt_RefOperativa"][$RowNumber], $RowNumber);
                        $this->det_NumCheque->SetText($this->FormParameters["det_NumCheque"][$RowNumber], $RowNumber);
                        $this->det_FecCheque->SetText($this->FormParameters["det_FecCheque"][$RowNumber], $RowNumber);
                        $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$RowNumber], $RowNumber);
                        $this->det_EstLibros->SetText($this->FormParameters["det_EstLibros"][$RowNumber], $RowNumber);
                        $this->det_FecEjecucion->SetText($this->FormParameters["det_FecEjecucion"][$RowNumber], $RowNumber);
                        $this->aux_ReqRefOper->SetText($this->FormParameters["aux_ReqRefOper"][$RowNumber], $RowNumber);
                        $this->det_EstEjecucion->SetText($this->FormParameters["det_EstEjecucion"][$RowNumber], $RowNumber);
                        $this->cue_ReqRefOperat->SetText($this->FormParameters["cue_ReqRefOperat"][$RowNumber], $RowNumber);
                        $this->cue_TipAuxiliar->SetText($this->FormParameters["cue_TipAuxiliar"][$RowNumber], $RowNumber);
                        $this->aux_Clase->SetText($this->FormParameters["aux_Clase"][$RowNumber], $RowNumber);  // fah
                        $this->det_FecLibros->SetText($this->FormParameters["det_FecLibros"][$RowNumber], $RowNumber);
                    }
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->det_TipoComp->Show($RowNumber);
                    $this->det_NumComp->Show($RowNumber);
                    $this->det_RegNumero->Show($RowNumber);
                    $this->det_Secuencia->Show($RowNumber);
                    $this->det_ClasRegistro->Show($RowNumber);
                    $this->det_CodCuenta->Show($RowNumber);
                    $this->cue_Descripcion->Show($RowNumber);
                    $this->det_IDAuxiliar->Show($RowNumber);
                    $this->det_DescAuxiliar->Show($RowNumber);
                    $this->det_ValDebito->Show($RowNumber);
                    $this->det_ValCredito->Show($RowNumber);
                    $this->det_Glosa->Show($RowNumber);
                    $this->det_RefOperativa->Show($RowNumber);
                    $this->txt_RefOperativa->Show($RowNumber);
                    $this->det_NumCheque->Show($RowNumber);
                    $this->det_FecCheque->Show($RowNumber);
                    $this->CheckBox_Delete->Show($RowNumber);
                    $this->det_EstLibros->Show($RowNumber);
                    $this->det_FecEjecucion->Show($RowNumber);
                    $this->aux_ReqRefOper->Show($RowNumber);
                    $this->det_EstEjecucion->Show($RowNumber);
                    $this->cue_ReqRefOperat->Show($RowNumber);
                    $this->cue_TipAuxiliar->Show($RowNumber);
                    $this->aux_Clase->Show($RowNumber);                             // fah
                    $this->det_FecLibros->Show($RowNumber);
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
        $this->condetalle_concuentas_con_TotalRecords->Show();
        $this->lbTitulo->Show();
        $this->Sorter_det_Secuencia->Show();
        $this->Sorter_cue_Descripcion->Show();
        $this->Sorter_det_IDAuxiliar->Show();
        $this->Sorter_det_ValDebito->Show();
        $this->Sorter_det_ValCredito->Show();
        $this->Sorter_det_Glosa->Show();
        $this->tot_ValDebito->Show();
        $this->tot_ValCredito->Show();
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

} //End CoTrTr_detalle Class @11-FCB6E20C

class clsCoTrTr_detalleDataSource extends clsDBdatos {  //CoTrTr_detalleDataSource Class @11-A714AC6D

//DataSource Variables @11-4438BC8E
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $InsertParameters;
    var $UpdateParameters;
    var $DeleteParameters;
    var $CountSQL;
    var $wp;
    var $AllParametersSet;

    var $CachedColumns;

    // Datasource fields
    var $det_TipoComp;
    var $det_NumComp;
    var $det_RegNumero;
    var $det_Secuencia;
    var $det_ClasRegistro;
    var $det_CodCuenta;
    var $cue_Descripcion;
    var $det_IDAuxiliar;
    var $det_DescAuxiliar;
    var $det_ValDebito;
    var $det_ValCredito;
    var $det_Glosa;
    var $det_RefOperativa;
    var $txt_RefOperativa;
    var $det_NumCheque;
    var $det_FecCheque;
    var $CheckBox_Delete;
    var $det_EstLibros;
    var $det_FecEjecucion;
    var $aux_ReqRefOper;
    var $det_EstEjecucion;
    var $cue_ReqRefOperat;
    var $cue_TipAuxiliar;
    var $aux_Clase;                             // fah
    var $det_FecLibros;
//End DataSource Variables

//Class_Initialize Event @11-83D89510
    function clsCoTrTr_detalleDataSource()
    {
        $this->ErrorBlock = "EditableGrid CoTrTr_detalle/Error";
        $this->Initialize();
        $this->det_TipoComp = new clsField("det_TipoComp", ccsText, "");
        $this->det_NumComp = new clsField("det_NumComp", ccsInteger, "");
        $this->det_RegNumero = new clsField("det_RegNumero", ccsInteger, "");
        $this->det_Secuencia = new clsField("det_Secuencia", ccsInteger, "");
        $this->det_ClasRegistro = new clsField("det_ClasRegistro", ccsInteger, "");
        $this->det_CodCuenta = new clsField("det_CodCuenta", ccsText, "");
        $this->cue_Descripcion = new clsField("cue_Descripcion", ccsText, "");
        $this->det_IDAuxiliar = new clsField("det_IDAuxiliar", ccsInteger, "");
        $this->det_DescAuxiliar = new clsField("det_DescAuxiliar", ccsText, "");
        $this->det_ValDebito = new clsField("det_ValDebito", ccsFloat, "");
        $this->det_ValCredito = new clsField("det_ValCredito", ccsFloat, "");
        $this->det_Glosa = new clsField("det_Glosa", ccsText, "");
        $this->det_RefOperativa = new clsField("det_RefOperativa", ccsInteger, "");
        $this->txt_RefOperativa = new clsField("txt_RefOperativa", ccsText, "");
        $this->det_NumCheque = new clsField("det_NumCheque", ccsInteger, "");
        $this->det_FecCheque = new clsField("det_FecCheque", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->CheckBox_Delete = new clsField("CheckBox_Delete", ccsBoolean, Array("true", "false", ""));
        $this->det_EstLibros = new clsField("det_EstLibros", ccsInteger, "");
        $this->det_FecEjecucion = new clsField("det_FecEjecucion", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->aux_ReqRefOper = new clsField("aux_ReqRefOper", ccsText, "");
        $this->det_EstEjecucion = new clsField("det_EstEjecucion", ccsInteger, "");
        $this->cue_ReqRefOperat = new clsField("cue_ReqRefOperat", ccsInteger, "");
        $this->cue_TipAuxiliar = new clsField("cue_TipAuxiliar", ccsInteger, "");
        $this->aux_Clase = new clsField("aux_Clase", ccsInteger, "");                       // fah
        $this->det_FecLibros = new clsField("det_FecLibros", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));

    }
//End Class_Initialize Event

//SetOrder Method @11-AE52F50E
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "det_Secuencia";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_det_Secuencia" => array("det_Secuencia", ""), 
            "Sorter_cue_Descripcion" => array("cue_Descripcion", ""), 
            "Sorter_det_IDAuxiliar" => array("det_IDAuxiliar", ""), 
            "Sorter_det_ValDebito" => array("det_ValDebito", ""), 
            "Sorter_det_ValCredito" => array("det_ValCredito", ""), 
            "Sorter_det_Glosa" => array("det_Glosa", "")));
    }
//End SetOrder Method

//Prepare Method @11-8503346F
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlcom_RegNumero", ccsInteger, "", "", $this->Parameters["urlcom_RegNumero"], -1, false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
    }
//End Prepare Method

//Open Method @11-4ADE4F13
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*) FROM ((condetalle LEFT JOIN conpersonas pe ON condetalle.det_IDAuxiliar = pe.per_CodAuxiliar)  " .
        "       LEFT JOIN conactivos ON condetalle.det_IDAuxiliar = conactivos.act_CodAuxiliar)  " .
        "       left JOIN concuentas ON condetalle.det_CodCuenta = concuentas.Cue_CodCuenta " .
        "WHERE det_RegNumero = " . $this->SQLValue($this->wp->GetDBValue("1"), ccsInteger) . " " .
        " ";
        $this->SQL = "SELECT condetalle.*, cue_Descripcion, cue_TipAuxiliar,  " .
        "       cue_ReqRefOperat, pe.per_Apellidos, pe.per_Nombres, act_Descripcion, act_Descripcion1,  " .
        "       if(pe.per_Apellidos is null, concat_ws(\" \", left(act_Descripcion,12), left(act_descripcion1,12) ),  concat(left(pe.per_Apellidos,12), \" \", left(pe.per_Nombres,10)) ) as det_DescAuxiliar,  " .
        "       if(ord_ID>0, concat(ifnull(par_Valor2,''), '-',
                ifnull(ord_NumDocum,ord_ID), '  / ', left(IFNULL(pp.per_Apellidos,''), 12), ' ' ,
                left(IFNULL(pp.per_Nombres,''),12)),'N.A.') as txt_RefOperativa,
                ord_ID, concat(left(IFNULL(pp.per_Apellidos,''), 12), ' ' , left(IFNULL(pp.per_Nombres,''),12))  as txt_cliente,
                ord_estado " .
        "FROM condetalle LEFT JOIN conpersonas pe ON condetalle.det_IDAuxiliar = pe.per_CodAuxiliar  " .
        "       LEFT JOIN conactivos ON condetalle.det_IDAuxiliar = conactivos.act_CodAuxiliar  " .
        "       left JOIN concuentas ON condetalle.det_CodCuenta = concuentas.Cue_CodCuenta " .
        "       LEFT JOIN serordenes on ord_ID = det_refOperativa
	            LEFT JOIN  conpersonas pp on pp.per_codauxiliar = ord_Cliente
	            LEFT JOIN  genparametros pa on par_clave = 'VGTIP' AND par_secuencia = ord_tipo " .
        "WHERE det_RegNumero = " . $this->SQLValue($this->wp->GetDBValue("1"), ccsInteger) . " " .
        " ";
/**
        if(ord_ID>0, concat(ifnull(par_Valor2,''), ' / ',
         ifnull(ord_NumDocum,ord_ID), '  / ', left(IFNULL(per_Apellidos,''), 12), ' ' ,
         left(IFNULL(per_Nombres,''),12)),'N.A.') as txt_orden,
         ord_ID, concat(left(IFNULL(per_Apellidos,''), 12), ' ' , left(IFNULL(per_Nombres,''),12))  as txt_cliente,
         ord_estado
FROM serordenes
	 LEFT JOIN  conpersonas pe on per_codauxiliar = ord_Cliente
	 LEFT JOIN  genparametros pa on par_clave = 'VGTIP' AND par_secuencia = ord_tipo
**/

        
        
        
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue($this->CountSQL, $this);
        $this->query(CCBuildSQL($this->SQL, "", $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @11-F78B3E2F
    function SetValues()
    {
        $this->CachedColumns["det_Secuencia"] = $this->f("det_Secuencia");
        $this->det_TipoComp->SetDBValue($this->f("det_TipoComp"));
        $this->det_NumComp->SetDBValue(trim($this->f("det_NumComp")));
        $this->det_RegNumero->SetDBValue(trim($this->f("det_RegNumero")));
        $this->det_Secuencia->SetDBValue(trim($this->f("det_Secuencia")));
        $this->det_ClasRegistro->SetDBValue(trim($this->f("det_ClasRegistro")));
        $this->det_CodCuenta->SetDBValue($this->f("det_CodCuenta"));
        $this->cue_Descripcion->SetDBValue($this->f("cue_Descripcion"));
        $this->det_IDAuxiliar->SetDBValue(trim($this->f("det_IDAuxiliar")));
        $this->det_DescAuxiliar->SetDBValue($this->f("det_DescAuxiliar"));
        $this->det_ValDebito->SetDBValue(trim($this->f("det_ValDebito")));
        $this->det_ValCredito->SetDBValue(trim($this->f("det_ValCredito")));
        $this->det_Glosa->SetDBValue($this->f("det_Glosa"));
        $this->det_RefOperativa->SetDBValue(trim($this->f("det_RefOperativa")));
        $this->txt_RefOperativa->SetDBValue(trim($this->f("txt_RefOperativa")));
        $this->det_NumCheque->SetDBValue(trim($this->f("det_NumCheque")));
        $this->det_FecCheque->SetDBValue(trim($this->f("det_FecCheque")));
        $this->det_EstLibros->SetDBValue(trim($this->f("det_EstLibros")));
        $this->det_FecEjecucion->SetDBValue(trim($this->f("det_FecEjecucion")));
        $this->det_EstEjecucion->SetDBValue(trim($this->f("det_EstEjecucion")));
        $this->cue_ReqRefOperat->SetDBValue(trim($this->f("cue_ReqRefOperat")));
        $this->cue_TipAuxiliar->SetDBValue(trim($this->f("cue_TipAuxiliar")));
        $this->det_FecLibros->SetDBValue(trim($this->f("det_FecLibros")));
//        print_r($this); die();
    }
//End SetValues Method

//Insert Method @11-5AC06BFE
    function Insert()
    {
        $this->cp["det_TipoComp"] = new clsSQLParameter("ctrldet_TipoComp", ccsText, "", "", $this->det_TipoComp->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["det_NumComp"] = new clsSQLParameter("ctrldet_NumComp", ccsInteger, "", "", $this->det_NumComp->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["det_Secuencia"] = new clsSQLParameter("ctrldet_Secuencia", ccsInteger, "", "", $this->det_Secuencia->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["det_ClasRegistro"] = new clsSQLParameter("ctrldet_ClasRegistro", ccsInteger, "", "", $this->det_ClasRegistro->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["det_CodCuenta"] = new clsSQLParameter("ctrldet_CodCuenta", ccsText, "", "", $this->det_CodCuenta->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["det_ValDebito"] = new clsSQLParameter("ctrldet_ValDebito", ccsFloat, "", "", $this->det_ValDebito->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["det_ValCredito"] = new clsSQLParameter("ctrldet_ValCredito", ccsFloat, "", "", $this->det_ValCredito->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["det_Glosa"] = new clsSQLParameter("ctrldet_Glosa", ccsText, "", "", $this->det_Glosa->GetValue(), " ", false, $this->ErrorBlock);
        $this->cp["det_EstEjecucion"] = new clsSQLParameter("ctrldet_EstEjecucion", ccsInteger, "", "", $this->det_EstEjecucion->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["det_FecEjecucion"] = new clsSQLParameter("ctrldet_FecEjecucion", ccsDate, Array("dd", "/", "mm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->det_FecEjecucion->GetValue(), 31/12/50, false, $this->ErrorBlock);
        $this->cp["det_EstLibros"] = new clsSQLParameter("ctrldet_EstLibros", ccsInteger, "", "", $this->det_EstLibros->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["det_FecLibros"] = new clsSQLParameter("ctrldet_FecLibros", ccsDate, Array("mm", "/", "dd", "/", "yyyy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->det_FecLibros->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["det_RefOperativa"] = new clsSQLParameter("ctrldet_RefOperativa", ccsInteger, "", "", $this->det_RefOperativa->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["det_NumCheque"] = new clsSQLParameter("ctrldet_NumCheque", ccsInteger, "", "", $this->det_NumCheque->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["det_FecCheque"] = new clsSQLParameter("ctrldet_FecCheque", ccsDate, Array("mm", "/", "dd", "/", "yyyy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->det_FecCheque->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["det_IDAuxiliar"] = new clsSQLParameter("ctrldet_IDAuxiliar", ccsInteger, "", "", $this->det_IDAuxiliar->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["det_RegNumero"] = new clsSQLParameter("urlcom_RegNumero", ccsInteger, "", "", CCGetFromGet("com_RegNumero", ""), "", false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO condetalle ("
             . "det_TipoComp, "
             . "det_NumComp, "
             . "det_Secuencia, "
             . "det_ClasRegistro, "
             . "det_CodCuenta, "
             . "det_ValDebito, "
             . "det_ValCredito, "
             . "det_Glosa, "
             . "det_EstEjecucion, "
             . "det_FecEjecucion, "
             . "det_EstLibros, "
             . "det_FecLibros, "
             . "det_RefOperativa, "
             . "det_NumCheque, "
             . "det_FecCheque, "
             . "det_IDAuxiliar, "
             . "det_RegNumero"
             . ") VALUES ("
             . $this->ToSQL($this->cp["det_TipoComp"]->GetDBValue(), $this->cp["det_TipoComp"]->DataType) . ", "
             . $this->ToSQL($this->cp["det_NumComp"]->GetDBValue(), $this->cp["det_NumComp"]->DataType) . ", "
             . $this->ToSQL($this->cp["det_Secuencia"]->GetDBValue(), $this->cp["det_Secuencia"]->DataType) . ", "
             . $this->ToSQL($this->cp["det_ClasRegistro"]->GetDBValue(), $this->cp["det_ClasRegistro"]->DataType) . ", "
             . $this->ToSQL($this->cp["det_CodCuenta"]->GetDBValue(), $this->cp["det_CodCuenta"]->DataType) . ", "
             . $this->ToSQL($this->cp["det_ValDebito"]->GetDBValue(), $this->cp["det_ValDebito"]->DataType) . ", "
             . $this->ToSQL($this->cp["det_ValCredito"]->GetDBValue(), $this->cp["det_ValCredito"]->DataType) . ", "
             . $this->ToSQL($this->cp["det_Glosa"]->GetDBValue(), $this->cp["det_Glosa"]->DataType) . ", "
             . $this->ToSQL($this->cp["det_EstEjecucion"]->GetDBValue(), $this->cp["det_EstEjecucion"]->DataType) . ", "
             . $this->ToSQL($this->cp["det_FecEjecucion"]->GetDBValue(), $this->cp["det_FecEjecucion"]->DataType) . ", "
             . $this->ToSQL($this->cp["det_EstLibros"]->GetDBValue(), $this->cp["det_EstLibros"]->DataType) . ", "
             . $this->ToSQL($this->cp["det_FecLibros"]->GetDBValue(), $this->cp["det_FecLibros"]->DataType) . ", "
             . $this->ToSQL($this->cp["det_RefOperativa"]->GetDBValue(), $this->cp["det_RefOperativa"]->DataType) . ", "
             . $this->ToSQL($this->cp["det_NumCheque"]->GetDBValue(), $this->cp["det_NumCheque"]->DataType) . ", "
             . $this->ToSQL($this->cp["det_FecCheque"]->GetDBValue(), $this->cp["det_FecCheque"]->DataType) . ", "
             . $this->ToSQL($this->cp["det_IDAuxiliar"]->GetDBValue(), $this->cp["det_IDAuxiliar"]->DataType) . ", "
             . $this->ToSQL($this->cp["det_RegNumero"]->GetDBValue(), $this->cp["det_RegNumero"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Insert Method

//Update Method @11-856D5F72
    function Update()
    {
        $this->cp["det_ClasRegistro"] = new clsSQLParameter("ctrldet_ClasRegistro", ccsInteger, "", "", $this->det_ClasRegistro->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["det_CodCuenta"] = new clsSQLParameter("ctrldet_CodCuenta", ccsText, "", "", $this->det_CodCuenta->GetValue(), '0', false, $this->ErrorBlock);
        $this->cp["det_IDAuxiliar"] = new clsSQLParameter("ctrldet_IDAuxiliar", ccsInteger, "", "", $this->det_IDAuxiliar->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["det_ValDebito"] = new clsSQLParameter("ctrldet_ValDebito", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), "", $this->det_ValDebito->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["det_ValCredito"] = new clsSQLParameter("ctrldet_ValCredito", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), "", $this->det_ValCredito->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["det_Glosa"] = new clsSQLParameter("ctrldet_Glosa", ccsText, "", "", $this->det_Glosa->GetValue(), ' ' , false, $this->ErrorBlock);
        $this->cp["det_RefOperativa"] = new clsSQLParameter("ctrldet_RefOperativa", ccsInteger, "", "", $this->det_RefOperativa->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["det_NumCheque"] = new clsSQLParameter("ctrldet_NumCheque", ccsInteger, "", "", $this->det_NumCheque->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["det_FecCheque"] = new clsSQLParameter("ctrldet_FecCheque", ccsDate, Array("dd", "/", "mm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->det_FecCheque->GetValue(), 01/01/20, false, $this->ErrorBlock);
        $this->cp["det_EstEjecucion"] = new clsSQLParameter("ctrldet_EstEjecucion", ccsInteger, "", "", $this->det_EstEjecucion->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["det_FecEjecucion"] = new clsSQLParameter("ctrldet_FecEjecucion", ccsDate, Array("dd", "/", "mm", "/", "yyyy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->det_FecEjecucion->GetValue(), 01/01/20, false, $this->ErrorBlock);
        $this->cp["det_EstLibros"] = new clsSQLParameter("ctrldet_EstLibros", ccsInteger, "", "", $this->det_EstLibros->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["det_FecLibros"] = new clsSQLParameter("ctrldet_FecLibros", ccsDate, Array("mm", "/", "dd", "/", "yyyy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->det_FecLibros->GetValue(), 01/01/20, false, $this->ErrorBlock);
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlcom_RegNumero", ccsInteger, "", "", CCGetFromGet("com_RegNumero", ""), "", true);
        $wp->AddParameter("2", "dsdet_Secuencia", ccsInteger, "", "", $this->CachedColumns["det_Secuencia"], "", true);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $wp->Criterion[1] = $wp->Operation(opEqual, "det_RegNumero", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),true);
        $wp->Criterion[2] = $wp->Operation(opEqual, "det_Secuencia", $wp->GetDBValue("2"), $this->ToSQL($wp->GetDBValue("2"), ccsInteger),true);
        $Where = $wp->opAND(false, $wp->Criterion[1], $wp->Criterion[2]);
        $this->SQL = "UPDATE condetalle SET "
             . "det_ClasRegistro=" . $this->ToSQL($this->cp["det_ClasRegistro"]->GetDBValue(), $this->cp["det_ClasRegistro"]->DataType) . ", "
             . "det_CodCuenta=" . $this->ToSQL($this->cp["det_CodCuenta"]->GetDBValue(), $this->cp["det_CodCuenta"]->DataType) . ", "
             . "det_IDAuxiliar=" . $this->ToSQL($this->cp["det_IDAuxiliar"]->GetDBValue(), $this->cp["det_IDAuxiliar"]->DataType) . ", "
             . "det_ValDebito=" . $this->ToSQL($this->cp["det_ValDebito"]->GetDBValue(), $this->cp["det_ValDebito"]->DataType) . ", "
             . "det_ValCredito=" . $this->ToSQL($this->cp["det_ValCredito"]->GetDBValue(), $this->cp["det_ValCredito"]->DataType) . ", "
             . "det_Glosa=" . $this->ToSQL($this->cp["det_Glosa"]->GetDBValue(), $this->cp["det_Glosa"]->DataType) . ", "
             . "det_RefOperativa=" . $this->ToSQL($this->cp["det_RefOperativa"]->GetDBValue(), $this->cp["det_RefOperativa"]->DataType) . ", "
             . "det_NumCheque=" . $this->ToSQL($this->cp["det_NumCheque"]->GetDBValue(), $this->cp["det_NumCheque"]->DataType) . ", "
             . "det_FecCheque=" . $this->ToSQL($this->cp["det_FecCheque"]->GetDBValue(), $this->cp["det_FecCheque"]->DataType) . ", "
             . "det_EstEjecucion=" . $this->ToSQL($this->cp["det_EstEjecucion"]->GetDBValue(), $this->cp["det_EstEjecucion"]->DataType) . ", "
             . "det_FecEjecucion=" . $this->ToSQL($this->cp["det_FecEjecucion"]->GetDBValue(), $this->cp["det_FecEjecucion"]->DataType) . ", "
             . "det_EstLibros=" . $this->ToSQL($this->cp["det_EstLibros"]->GetDBValue(), $this->cp["det_EstLibros"]->DataType) . ", "
             . "det_FecLibros=" . $this->ToSQL($this->cp["det_FecLibros"]->GetDBValue(), $this->cp["det_FecLibros"]->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Update Method

//Delete Method @11-7E6D6CAE
    function Delete()
    {
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlcom_RegNumero", ccsInteger, "", "", CCGetFromGet("com_RegNumero", ""), "", true);
		$wp->AddParameter("2", "dsdet_Secuencia", ccsInteger, "", "", $this->CachedColumns["det_Secuencia"], "", false);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $wp->Criterion[1] = $wp->Operation(opEqual, "det_RegNumero", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),true);
        $wp->Criterion[2] = $wp->Operation(opEqual, "det_Secuencia", $wp->GetDBValue("2"), $this->ToSQL($wp->GetDBValue("2"), ccsInteger),true);
        $Where = $wp->opAND(false, $wp->Criterion[1], $wp->Criterion[2]);
        $this->SQL = "DELETE FROM condetalle";
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        $this->query($this->SQL);
//        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        CoTrTr_detalle_AfterExecuteDelete();
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Delete Method

} //End CoTrTr_detalleDataSource Class @11-FCB6E20C

//Initialize Page @1-37D5B888
// Variables
$FileName = "";
$Redirect = "";
$Tpl = "";
$TemplateFileName = "";
$BlockToParse = "";
$ComponentName = "";
/**  Variables para Bitacora  **/
$gfValTotal = 0;
$gfCosTotal = 0;
$gfCanTotal = 0;
$gfCanInser = 0;
$gfCanModif = 0;
$gfCanElim = 0;
// Events;
$CCSEvents = "";
$CCSEventResult = "";

$FileName = "CoTrTr_deta.php";
$Redirect = "";
$TemplateFileName = "CoTrTr_deta.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-D1E253AD
$DBdatos = new clsDBdatos();

// Controls
$CoTrTr_detalle = new clsEditableGridCoTrTr_detalle();
$CoTrTr_detalle->Initialize();

// Events
include("./CoTrTr_deta_events.php");
BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-174D7AD7
$CoTrTr_detalle->Operation();
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

//Show Page @1-01C74387
$CoTrTr_detalle->Show();
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
