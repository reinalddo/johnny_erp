<?php
include_once("GenUti.inc.php");
class clsEditableGridCoAdAu_varimantvariables_list { //variables_list Class @3-83401C8A

//Variables @3-6A9151F7

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
    var $ds;
    var $PageSize;
    var $SorterName = "";
    var $SorterDirection = "";
    var $PageNumber;

    var $CCSEvents = "";
    var $CCSEventResult;

    var $InsertAllowed = false;
    var $UpdateAllowed = false;
    var $DeleteAllowed = false;
    var $ReadAllowed   = false;
    var $EditMode;
    var $ValidatingControls;
    var $Controls;
    var $ControlsErrors;

    // Class variables
    var $Sorter_vcf_nombre;
    var $Sorter_iab_Bandera;
    var $Sorter_iab_ValorTex;
    var $Navigator;
//End Variables

//Class_Initialize Event @3-EEECB923
    function clsEditableGridCoAdAu_varimantvariables_list()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "EditableGrid variables_list/Error";
        $this->ComponentName = "variables_list";
        $this->CachedColumns["vcf_tipoObjeto"][0] = "vcf_tipoObjeto";
        $this->CachedColumns["vcf_ID"][0] = "vcf_ID";
        $this->ds = new clsCoAdAu_varimantvariables_listDataSource();
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 10;
        else
            $this->PageSize = intval($this->PageSize);
        if ($this->PageSize > 100)
            $this->PageSize = 100;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: EditableGrid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));

        $this->EmptyRows = 0;
        $this->UpdateAllowed = true;
        $this->ReadAllowed = true;
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

        $this->SorterName = CCGetParam("variables_listOrder", "");
        $this->SorterDirection = CCGetParam("variables_listDir", "");

        $this->genvariables_genvarconfig_TotalRecords = new clsControl(ccsLabel, "genvariables_genvarconfig_TotalRecords", "genvariables_genvarconfig_TotalRecords", ccsText, "");
        $this->Sorter_vcf_nombre = new clsSorter($this->ComponentName, "Sorter_vcf_nombre", $FileName);
        $this->Sorter_iab_Bandera = new clsSorter($this->ComponentName, "Sorter_iab_Bandera", $FileName);
        $this->Sorter_iab_ValorTex = new clsSorter($this->ComponentName, "Sorter_iab_ValorTex", $FileName);
        $this->Label1 = new clsControl(ccsLabel, "Label1", "Label1", ccsText, "");
        $this->iab_Tipo = new clsControl(ccsTextBox, "iab_Tipo", "Iab Tipo", ccsInteger, "");
        $this->iab_ObjetoID = new clsControl(ccsTextBox, "iab_ObjetoID", "ID de Objeto", ccsInteger, "");
        $this->iab_VariableID = new clsControl(ccsTextBox, "iab_VariableID", "ID de Variable", ccsText, "");
        $this->Label2 = new clsControl(ccsLabel, "Label2", "Label2", ccsText, "");
        $this->iab_Bandera = new clsControl(ccsTextBox, "iab_Bandera", "Indicador de Actividad", ccsInteger, Array(True, 0, "", "", False, Array("#", "#"), "", 1, True, ""));
        $this->iab_ValorTex = new clsControl(ccsTextBox, "iab_ValorTex", "valor de texto", ccsText, "");
        $this->iab_ValorNum = new clsControl(ccsTextBox, "iab_ValorNum", "Valor Numerico", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "0"), Array("#", "#"), 1, True, ""));
        $this->vcf_tipoDato = new clsControl(ccsTextBox, "vcf_tipoDato", "Indicador de Actividad", ccsText, "");
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpCentered);
        $this->Button_Submit = new clsButton("Button_Submit");
        $this->Cancel = new clsButton("Cancel");
    }
//End Class_Initialize Event

//Initialize Method @3-D56D31B8
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);

        $this->ds->Parameters["urlact_CodAuxiliar"] = CCGetFromGet("act_CodAuxiliar", "-1000");
    }
//End Initialize Method

//GetFormParameters Method @3-5F818326
    function GetFormParameters()
    {
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->FormParameters["iab_Tipo"][$RowNumber] = CCGetFromPost("iab_Tipo_" . $RowNumber);
            $this->FormParameters["iab_ObjetoID"][$RowNumber] = CCGetFromPost("iab_ObjetoID_" . $RowNumber);
            $this->FormParameters["iab_VariableID"][$RowNumber] = CCGetFromPost("iab_VariableID_" . $RowNumber);
            $this->FormParameters["iab_Bandera"][$RowNumber] = CCGetFromPost("iab_Bandera_" . $RowNumber);
            $this->FormParameters["iab_ValorTex"][$RowNumber] = CCGetFromPost("iab_ValorTex_" . $RowNumber);
            $this->FormParameters["iab_ValorNum"][$RowNumber] = CCGetFromPost("iab_ValorNum_" . $RowNumber);
            $this->FormParameters["vcf_tipoDato"][$RowNumber] = CCGetFromPost("vcf_tipoDato_" . $RowNumber);
        }
    }
//End GetFormParameters Method

//Validate Method @3-5E5C248A
    function Validate()
    {
        $Validation = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");

        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["vcf_tipoObjeto"] = $this->CachedColumns["vcf_tipoObjeto"][$RowNumber];
            $this->ds->CachedColumns["vcf_ID"] = $this->CachedColumns["vcf_ID"][$RowNumber];
            $this->iab_Tipo->SetText($this->FormParameters["iab_Tipo"][$RowNumber], $RowNumber);
            $this->iab_ObjetoID->SetText($this->FormParameters["iab_ObjetoID"][$RowNumber], $RowNumber);
            $this->iab_VariableID->SetText($this->FormParameters["iab_VariableID"][$RowNumber], $RowNumber);
            $this->iab_Bandera->SetText($this->FormParameters["iab_Bandera"][$RowNumber], $RowNumber);
            $this->iab_ValorTex->SetText($this->FormParameters["iab_ValorTex"][$RowNumber], $RowNumber);
            $this->iab_ValorNum->SetText($this->FormParameters["iab_ValorNum"][$RowNumber], $RowNumber);
            $this->vcf_tipoDato->SetText($this->FormParameters["vcf_tipoDato"][$RowNumber], $RowNumber);
            if ($this->UpdatedRows >= $RowNumber) {
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

//ValidateRow Method @3-20916402
    function ValidateRow($RowNumber)
    {
        $this->iab_Tipo->Validate();
        $this->iab_ObjetoID->Validate();
        $this->iab_VariableID->Validate();
        $this->iab_Bandera->Validate();
        $this->iab_ValorTex->Validate();
        $this->iab_ValorNum->Validate();
        $this->vcf_tipoDato->Validate();
        $this->RowErrors = new clsErrors();
        $errors = $this->iab_Tipo->Errors->ToString();
        $errors .= $this->iab_ObjetoID->Errors->ToString();
        $errors .= $this->iab_VariableID->Errors->ToString();
        $errors .= $this->iab_Bandera->Errors->ToString();
        $errors .= $this->iab_ValorTex->Errors->ToString();
        $errors .= $this->iab_ValorNum->Errors->ToString();
        $errors .= $this->vcf_tipoDato->Errors->ToString();
        $this->iab_Tipo->Errors->Clear();
        $this->iab_ObjetoID->Errors->Clear();
        $this->iab_VariableID->Errors->Clear();
        $this->iab_Bandera->Errors->Clear();
        $this->iab_ValorTex->Errors->Clear();
        $this->iab_ValorNum->Errors->Clear();
        $this->vcf_tipoDato->Errors->Clear();
        $errors .=$this->RowErrors->ToString();
        $this->RowsErrors[$RowNumber] = $errors;
        return $errors ? 0 : 1;
    }
//End ValidateRow Method

//CheckInsert Method @3-0ACE8C7D
    function CheckInsert($RowNumber)
    {
        $filed = false;
        $filed = ($filed || strlen($this->FormParameters["iab_Tipo"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["iab_ObjetoID"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["iab_VariableID"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["iab_Bandera"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["iab_ValorTex"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["iab_ValorNum"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["vcf_tipoDato"][$RowNumber]));
        return $filed;
    }
//End CheckInsert Method

//CheckErrors Method @3-242E5992
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @3-7B861278
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

//UpdateGrid Method @3-9BFF17AD
    function UpdateGrid()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSubmit");
        if(!$this->Validate()) return;
        $Validation = true;
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["vcf_tipoObjeto"] = $this->CachedColumns["vcf_tipoObjeto"][$RowNumber];
            $this->ds->CachedColumns["vcf_ID"] = $this->CachedColumns["vcf_ID"][$RowNumber];
            $this->iab_Tipo->SetText($this->FormParameters["iab_Tipo"][$RowNumber], $RowNumber);
            $this->iab_ObjetoID->SetText($this->FormParameters["iab_ObjetoID"][$RowNumber], $RowNumber);
            $this->iab_VariableID->SetText($this->FormParameters["iab_VariableID"][$RowNumber], $RowNumber);
            $this->iab_Bandera->SetText($this->FormParameters["iab_Bandera"][$RowNumber], $RowNumber);
            $this->iab_ValorTex->SetText($this->FormParameters["iab_ValorTex"][$RowNumber], $RowNumber);
            $this->iab_ValorNum->SetText($this->FormParameters["iab_ValorNum"][$RowNumber], $RowNumber);
            $this->vcf_tipoDato->SetText($this->FormParameters["vcf_tipoDato"][$RowNumber], $RowNumber);
            if ($this->UpdatedRows >= $RowNumber) {
                if($this->UpdateAllowed) { $Validation = ($this->UpdateRow($RowNumber) && $Validation); }
            }
            else if($this->CheckInsert($RowNumber) && $this->InsertAllowed)
            {
                $Validation = ($this->InsertRow($RowNumber) && $Validation);
            }
        }
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterSubmit");
        return ($this->Errors->Count() == 0 && $Validation);
    }
//End UpdateGrid Method

//UpdateRow Method @3-6B92DAF5
    function UpdateRow($RowNumber)
    {
        if(!$this->UpdateAllowed) return false;
        $this->ds->iab_Bandera->SetValue($this->iab_Bandera->GetValue());
        $this->ds->iab_ValorTex->SetValue($this->iab_ValorTex->GetValue());
        $this->ds->iab_ValorNum->SetValue($this->iab_ValorNum->GetValue());
        $this->ds->Update();
        $errors = "";
        if($this->ds->Errors->Count() > 0) {
            $errors = $this->ds->Errors->ToString();
            $this->RowsErrors[$RowNumber] = $errors;
            $this->ds->Errors->Clear();
        }
        return (($this->Errors->Count() == 0) && !strlen($errors));
    }
//End UpdateRow Method

//FormScript Method @3-2CEC8301
    function FormScript($TotalRows)
    {
        $script = "";
        $script .= "\n<script language=\"JavaScript\">\n<!--\n";
        $script .= "var variables_listElements;\n";
        $script .= "var variables_listEmptyRows = 0;\n";
        $script .= "var " . $this->ComponentName . "iab_TipoID = 0;\n";
        $script .= "var " . $this->ComponentName . "iab_ObjetoIDID = 1;\n";
        $script .= "var " . $this->ComponentName . "iab_VariableIDID = 2;\n";
        $script .= "var " . $this->ComponentName . "iab_BanderaID = 3;\n";
        $script .= "var " . $this->ComponentName . "iab_ValorTexID = 4;\n";
        $script .= "var " . $this->ComponentName . "iab_ValorNumID = 5;\n";
        $script .= "var " . $this->ComponentName . "vcf_tipoDatoID = 6;\n";
        $script .= "\nfunction initvariables_listElements() {\n";
        $script .= "\tvar ED = document.forms[\"variables_list\"];\n";
        $script .= "\tvariables_listElements = new Array (\n";
        for($i = 1; $i <= $TotalRows; $i++) {
            $script .= "\t\tnew Array(" . "ED.iab_Tipo_" . $i . ", " . "ED.iab_ObjetoID_" . $i . ", " . "ED.iab_VariableID_" . $i . ", " . "ED.iab_Bandera_" . $i . ", " . "ED.iab_ValorTex_" . $i . ", " . "ED.iab_ValorNum_" . $i . ", " . "ED.vcf_tipoDato_" . $i . ")";
            if($i != $TotalRows) $script .= ",\n";
        }
        $script .= ");\n";
        $script .= "}\n";
        $script .= "\n//-->\n</script>";
        return $script;
    }
//End FormScript Method

//SetFormState Method @3-04B6A46F
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
            for($i = 2; $i < sizeof($pieces); $i = $i + 2)  {
                $piece = $pieces[$i + 0];
                $piece = str_replace("\\" . ord("\\"), "\\", $piece);
                $piece = str_replace("\\" . ord(";"), ";", $piece);
                $this->CachedColumns["vcf_tipoObjeto"][$RowNumber] = $piece;
                $piece = $pieces[$i + 1];
                $piece = str_replace("\\" . ord("\\"), "\\", $piece);
                $piece = str_replace("\\" . ord(";"), ";", $piece);
                $this->CachedColumns["vcf_ID"][$RowNumber] = $piece;
                $RowNumber++;
            }

            if(!$RowNumber) { $RowNumber = 1; }
            for($i = 1; $i <= $this->EmptyRows; $i++) {
                $this->CachedColumns["vcf_tipoObjeto"][$RowNumber] = "";
                $this->CachedColumns["vcf_ID"][$RowNumber] = "";
                $RowNumber++;
            }
        }
    }
//End SetFormState Method

//GetFormState Method @3-D1745FEF
    function GetFormState($NonEmptyRows)
    {
        if(!$this->FormSubmitted) {
            $this->FormState  = $NonEmptyRows . ";";
            $this->FormState .= $this->InsertAllowed ? $this->EmptyRows : "0";
            if($NonEmptyRows) {
                for($i = 0; $i <= $NonEmptyRows; $i++) {
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["vcf_tipoObjeto"][$i]));
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["vcf_ID"][$i]));
                }
            }
        }
        return $this->FormState;
    }
//End GetFormState Method

//Show Method @3-CA71F5E9
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

        $this->Button_Submit->Visible = $this->Button_Submit->Visible && ($this->InsertAllowed || $this->UpdateAllowed || $this->DeleteAllowed);
        $ParentPath = $Tpl->block_path;
        $EditableGridPath = $ParentPath . "/EditableGrid " . $this->ComponentName;
        $EditableGridRowPath = $ParentPath . "/EditableGrid " . $this->ComponentName . "/Row";
        $Tpl->block_path = $EditableGridRowPath;
        $RowNumber = 0;
        $NonEmptyRows = 0;
        $EmptyRowsLeft = $this->EmptyRows;
        $is_next_record = $this->ds->next_record() && $this->ReadAllowed && $RowNumber < $this->PageSize;
        if($is_next_record || ($EmptyRowsLeft && $this->InsertAllowed))
        {
            do
            {
                $RowNumber++;
                if($is_next_record) {
                    $NonEmptyRows++;
                    $this->ds->SetValues();
                    $this->Label1->SetValue($this->ds->Label1->GetValue());
                    $this->Label2->SetValue($this->ds->Label2->GetValue());
                } else {
                    $this->Label1->SetText("");
                    $this->Label2->SetText("");
                }
                if(!$this->FormSubmitted && $is_next_record) {
                    $this->CachedColumns["vcf_tipoObjeto"][$RowNumber] = $this->ds->CachedColumns["vcf_tipoObjeto"];
                    $this->CachedColumns["vcf_ID"][$RowNumber] = $this->ds->CachedColumns["vcf_ID"];
                    $this->iab_Tipo->SetValue($this->ds->iab_Tipo->GetValue());
                    $this->iab_ObjetoID->SetValue($this->ds->iab_ObjetoID->GetValue());
                    $this->iab_VariableID->SetValue($this->ds->iab_VariableID->GetValue());
                    $this->iab_Bandera->SetValue($this->ds->iab_Bandera->GetValue());
                    $this->iab_ValorTex->SetValue($this->ds->iab_ValorTex->GetValue());
                    $this->iab_ValorNum->SetValue($this->ds->iab_ValorNum->GetValue());
                    $this->vcf_tipoDato->SetValue($this->ds->vcf_tipoDato->GetValue());
                    $this->ValidateRow($RowNumber);
                } else if (!$this->FormSubmitted){
                    $this->CachedColumns["vcf_tipoObjeto"][$RowNumber] = "";
                    $this->CachedColumns["vcf_ID"][$RowNumber] = "";
                    $this->iab_Tipo->SetText("");
                    $this->iab_ObjetoID->SetText("");
                    $this->iab_VariableID->SetText("");
                    $this->iab_Bandera->SetText("");
                    $this->iab_ValorTex->SetText("");
                    $this->iab_ValorNum->SetText("");
                    $this->vcf_tipoDato->SetText("");
                } else {
                    $this->iab_Tipo->SetText($this->FormParameters["iab_Tipo"][$RowNumber], $RowNumber);
                    $this->iab_ObjetoID->SetText($this->FormParameters["iab_ObjetoID"][$RowNumber], $RowNumber);
                    $this->iab_VariableID->SetText($this->FormParameters["iab_VariableID"][$RowNumber], $RowNumber);
                    $this->iab_Bandera->SetText($this->FormParameters["iab_Bandera"][$RowNumber], $RowNumber);
                    $this->iab_ValorTex->SetText($this->FormParameters["iab_ValorTex"][$RowNumber], $RowNumber);
                    $this->iab_ValorNum->SetText($this->FormParameters["iab_ValorNum"][$RowNumber], $RowNumber);
                    $this->vcf_tipoDato->SetText($this->FormParameters["vcf_tipoDato"][$RowNumber], $RowNumber);
                }
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->Label1->Show($RowNumber);
                $this->iab_Tipo->Show($RowNumber);
                $this->iab_ObjetoID->Show($RowNumber);
                $this->iab_VariableID->Show($RowNumber);
                $this->Label2->Show($RowNumber);
                $this->iab_Bandera->Show($RowNumber);
                $this->iab_ValorTex->Show($RowNumber);
                $this->iab_ValorNum->Show($RowNumber);
                $this->vcf_tipoDato->Show($RowNumber);
                if(isset($this->RowsErrors[$RowNumber]) && $this->RowsErrors[$RowNumber] !== "") {
                    $Tpl->setvar("Error", $this->RowsErrors[$RowNumber]);
                    $Tpl->parse("RowError", false);
                } else {
                    $Tpl->setblockvar("RowError", "");
                }
                $Tpl->setvar("FormScript", $this->FormScript($RowNumber));
                $Tpl->parse();
                if($is_next_record) $is_next_record = $this->ds->next_record() && $this->ReadAllowed && $RowNumber < $this->PageSize;
                else $EmptyRowsLeft--;
            } while($is_next_record || ($EmptyRowsLeft && $this->InsertAllowed));
        } else {
            $Tpl->block_path = $EditableGridPath;
            $Tpl->parse("NoRecords", false);
        }

        $Tpl->block_path = $EditableGridPath;
        $this->Navigator->PageNumber = $this->ds->AbsolutePage;
        $this->Navigator->TotalPages = $this->ds->PageCount();
        $this->genvariables_genvarconfig_TotalRecords->Show();
        $this->Sorter_vcf_nombre->Show();
        $this->Sorter_iab_Bandera->Show();
        $this->Sorter_iab_ValorTex->Show();
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

} //End variables_list Class @3-FCB6E20C

class clsCoAdAu_varimantvariables_listDataSource extends clsDBdatos {  //variables_listDataSource Class @3-099E04D2

//DataSource Variables @3-644E2CAB
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $UpdateParameters;
    var $CountSQL;
    var $wp;
    var $AllParametersSet;

    var $CachedColumns;

    // Datasource fields
    var $Label1;
    var $iab_Tipo;
    var $iab_ObjetoID;
    var $iab_VariableID;
    var $Label2;
    var $iab_Bandera;
    var $iab_ValorTex;
    var $iab_ValorNum;
    var $vcf_tipoDato;
//End DataSource Variables

//DataSourceClass_Initialize Event @3-8085F8C8
    function clsCoAdAu_varimantvariables_listDataSource()
    {
        $this->ErrorBlock = "EditableGrid variables_list/Error";
        $this->Initialize();
        $this->Label1 = new clsField("Label1", ccsText, "");
        $this->iab_Tipo = new clsField("iab_Tipo", ccsInteger, "");
        $this->iab_ObjetoID = new clsField("iab_ObjetoID", ccsInteger, "");
        $this->iab_VariableID = new clsField("iab_VariableID", ccsText, "");
        $this->Label2 = new clsField("Label2", ccsText, "");
        $this->iab_Bandera = new clsField("iab_Bandera", ccsInteger, "");
        $this->iab_ValorTex = new clsField("iab_ValorTex", ccsText, "");
        $this->iab_ValorNum = new clsField("iab_ValorNum", ccsFloat, "");
        $this->vcf_tipoDato = new clsField("vcf_tipoDato", ccsText, "");

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @3-36757DAE
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection,
            array("Sorter_vcf_nombre" => array("vcf_nombre", ""),
            "Sorter_iab_Bandera" => array("iab_Bandera", ""),
            "Sorter_iab_ValorTex" => array("iab_ValorTex", "")));
    }
//End SetOrder Method

//Prepare Method @3-AE26CC3C
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlact_CodAuxiliar", ccsInteger, "", "", $this->Parameters["urlact_CodAuxiliar"], NULL, false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
    }
//End Prepare Method

//Open Method @3-93DCC8FF
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $slCondic =   "WHERE vau_codauxiliar = " . fGetparam('per_CodAuxiliar', fGetparam('act_CodAuxiliar', -1000))  . "";
        $this->CountSQL = "SELECT COUNT(*)
            FROM v_auxgeneralcate 
        	JOIN concategorias ON cat_categoria > 0 AND cat_codauxiliar = vau_codauxiliar 
        	left JOIN genvarconfig on vcf_tipoObjeto = vau_categoria 
        	LEFT JOIN genvariables on iab_tipo = vau_categoria AND  
        	iab_ObjetoID = vau_codAuxiliar AND 
        	iab_VariableID = vcf_ID 
                " . $slCondic . " ";

        $this->SQL = "SELECT vau_categoria as vau_Categoria,
                vau_Codauxiliar as vau_CodAuxiliar, 
               vcf_ID, vcf_tipoObjeto, vcf_Nombre, 
               concat(vcf_Descripcion, '   ') AS txt_Descripcion, 
        	vcf_tipoObjeto, vcf_Validacion, vcf_tipoDato, 
               iab_Bandera, iab_ValorTex as iab_ValorTex, iab_ValorNum as iab_ValorNum 
        FROM v_auxgeneralcate 
        	JOIN concategorias ON cat_categoria > 0 AND cat_codauxiliar = vau_codauxiliar 
        	left JOIN genvarconfig on vcf_tipoObjeto = vau_categoria 
        	LEFT JOIN genvariables on iab_tipo = vau_categoria AND  
        	iab_ObjetoID = vau_codAuxiliar AND 
        	iab_VariableID = vcf_ID 
             " . $slCondic. " ";

//echo $this->SQL;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query($this->OptimizeSQL(CCBuildSQL($this->SQL, $this->Where, $this->Order)));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
    }
//End Open Method

//SetValues Method @3-42323261
    function SetValues()
    {
        $this->CachedColumns["vcf_tipoObjeto"] = $this->f("vcf_tipoObjeto");
        $this->CachedColumns["vcf_ID"] = $this->f("vcf_ID");
        $this->Label1->SetDBValue($this->f("txt_Descripcion"));
        $this->iab_Tipo->SetDBValue(trim($this->f("iab_Tipo")));
        $this->iab_ObjetoID->SetDBValue(trim($this->f("iab_ObjetoID")));
        $this->iab_VariableID->SetDBValue($this->f("iab_VariableID"));
        $this->Label2->SetDBValue($this->f("vcf_Nombre"));
        $this->iab_Bandera->SetDBValue(trim($this->f("iab_Bandera")));
        $this->iab_ValorTex->SetDBValue($this->f("iab_ValorTex"));
        $this->iab_ValorNum->SetDBValue(trim($this->f("iab_ValorNum")));
        $this->vcf_tipoDato->SetDBValue($this->f("vcf_tipoDato"));
    }
//End SetValues Method

//Update Method @3-D5919E88
    function Update()
    { global $gsTipoAux;
        $this->CmdExecution = true;
        if (!isset($gsTipoAux))
            $gsTipoAux = "per_CodAuxiliar";
        $slCodAux = CCGetFromGet($gsTipoAux, "");
        $this->cp["iab_Tipo"] = new clsSQLParameter("dsvcf_tipoObjeto", ccsInteger, "", "", $this->CachedColumns["vcf_tipoObjeto"], 0, false, $this->ErrorBlock);
        $this->cp["iab_ObjetoID"] = new clsSQLParameter("urlper_codAuxiliar", ccsInteger, "", "", $slCodAux, 0, false, $this->ErrorBlock);
        $this->cp["iab_VariableID"] = new clsSQLParameter("dsvcf_ID", ccsText, "", "", $this->CachedColumns["vcf_ID"], "", false, $this->ErrorBlock);
        $this->cp["iab_Bandera"] = new clsSQLParameter("ctrliab_Bandera", ccsInteger, "", "", $this->iab_Bandera->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["iab_ValorTex"] = new clsSQLParameter("ctrliab_ValorTex", ccsText, "", "", $this->iab_ValorTex->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["iab_ValorNum"] = new clsSQLParameter("ctrliab_ValorNum", ccsFloat, Array(True, 4, ".", ",", False, Array("#", "#", "#", "0"), Array("0", "0", "#", "#"), 1, True, ""), "", $this->iab_ValorNum->GetValue(), "", false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        if ($this->vcf_tipoDato->GetDBValue() == "N" ){
            $flNum = $this->SQLValue($this->cp["iab_ValorNum"]->GetDBValue(), ccsFloat);
        }
        else {
            $flNum = 0.00;
        }
        $this->SQL = "REPLACE INTO genvariables  " .
        "VALUES(" . $this->SQLValue($this->cp["iab_Tipo"]->GetDBValue(), ccsInteger) . ",  " .
        "    " . $this->SQLValue($this->cp["iab_ObjetoID"]->GetDBValue(), ccsInteger) . ",  " .
        "    '" . $this->SQLValue($this->cp["iab_VariableID"]->GetDBValue(), ccsText) . "',  " .
        "    " . $this->SQLValue($this->cp["iab_Bandera"]->GetDBValue(), ccsInteger) . ",  " .
        "    '" . $this->SQLValue($this->cp["iab_ValorTex"]->GetDBValue(), ccsText) . "',  " .
        "    " . $flNum . "  )";
//        "    " . $this->SQLValue($this->cp["iab_ValorNum"]->GetDBValue(), ccsFloat) . "  )";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        }
        $this->close();
    }
//End Update Method

} //End variables_listDataSource Class @3-FCB6E20C

class clsCoAdAu_varimant { //CoAdAu_varimant class @1-CEC97D3C

//Variables @1-1987AB94
    var $FileName = "";
    var $Redirect = "";
    var $Tpl = "";
    var $TemplateFileName = "";
    var $BlockToParse = "";
    var $ComponentName = "";

    // Events;
    var $CCSEvents = "";
    var $CCSEventResult = "";
    var $TemplatePath;
    var $Visible;
//End Variables

//Class_Initialize Event @1-594C7C46
    function clsCoAdAu_varimant($path)
    {
        $this->TemplatePath = $path;
        $this->Visible = true;
        $this->FileName = "CoAdAu_varimant.php";
        $this->Redirect = "";
        $this->TemplateFileName = "CoAdAu_varimant.html";
        $this->BlockToParse = "main";
        $this->TemplateEncoding = "";
        if($this->Visible)
        {
            $this->DBdatos = new clsDBdatos();

            // Create Components
            $this->variables_list = new clsEditableGridCoAdAu_varimantvariables_list();
            $this->variables_list->Initialize();
        }
    }
//End Class_Initialize Event

//Class_Terminate Event @1-64E0B1C7
    function Class_Terminate()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUnload");
        unset($this->variables_list);
    }
//End Class_Terminate Event

//BindEvents Method @1-EDBE6904
    function BindEvents()
    {
        $this->variables_list->genvariables_genvarconfig_TotalRecords->CCSEvents["BeforeShow"] = "CoAdAu_varimant_variables_list_genvariables_genvarconfig_TotalRecords_BeforeShow";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInitialize");
    }
//End BindEvents Method

//Operations Method @1-45CAFD51
    function Operations()
    {
        global $Redirect;
        if(!$this->Visible)
            return "";
        $this->variables_list->Operation();
    }
//End Operations Method

//Initialize Method @1-EDD74DD5
    function Initialize()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnInitializeView");
        if(!$this->Visible)
            return "";
    }
//End Initialize Method

//Show Method @1-5ECE20EE
    function Show($Name)
    {
        global $Tpl;
        $block_path = $Tpl->block_path;
        $Tpl->LoadTemplate($this->TemplatePath . $this->TemplateFileName, $Name, $this->TemplateEncoding);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
        if(!$this->Visible)
            return "";
        $Tpl->block_path = $Tpl->block_path . "/" . $Name;
        $this->variables_list->Show();
        $Tpl->Parse();
        $Tpl->block_path = $block_path;
        $Tpl->SetVar($Name, $Tpl->GetVar($Name));
    }
//End Show Method

} //End CoAdAu_varimant Class @1-FCB6E20C

//Include Event File @1-6DA3569E
include(RelativePath . "/Co_Files/CoAdAu_varimant_events.php");
//End Include Event File
?>
