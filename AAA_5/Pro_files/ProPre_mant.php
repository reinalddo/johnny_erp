<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

Class clsEditableGridperfil_modu { //perfil_modu Class @3-C304A78F

//Variables @3-A5C1CE1E

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
    var $DeleteAllowed = false;
    var $EditMode;
    var $ValidatingControls;
    var $Controls;
    var $ControlsErrors;

    // Class variables
    var $Navigator;
//End Variables

//Class_Initialize Event @3-5F740632
    function clsEditableGridperfil_modu()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "EditableGrid perfil_modu/Error";
        $this->ComponentName = "perfil_modu";
        $this->CachedColumns["atr_Codmodulo"][0] = "atr_Codmodulo";
        $this->ds = new clsperfil_moduDataSource();

        $this->PageSize = 10;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: EditableGrid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));

        $this->EmptyRows = 1;
        $this->UpdateAllowed = true;
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

        $this->lbTitulo = new clsControl(ccsLabel, "lbTitulo", "lbTitulo", ccsText, "");
        $this->atr_CodModulo = new clsControl(ccsHidden, "atr_CodModulo", "Atr CodModulo", ccsText, "");
        $this->atr_CodModulo->Required = false;
        $this->lSubmod = new clsControl(ccsLink, "lSubmod", "lSubmod", ccsText, "");
        $this->eAplicacion = new clsControl(ccsLabel, "eAplicacion", "eAplicacion", ccsText, "");
        $this->mod_descripcion = new clsControl(ccsLabel, "mod_descripcion", "mod_descripcion", ccsText, "");
        $this->atr_nivel = new clsControl(ccsListBox, "atr_nivel", "Atr Nivel", ccsInteger, "");
        $this->atr_nivel->DSType = dsListOfValues;
        $this->atr_nivel->Values = array(array("0", "INHABILITADO"), array("1", "HABILITADO"), array("2", "TOTAL"));
        $this->atr_nivel->Required =false;
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple);
        $this->Button_Submit = new clsButton("Button_Submit");
    }
//End Class_Initialize Event

//Initialize Method @3-D39A0DA6
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);

        $this->ds->Parameters["urlatr_IDperfil"] = CCGetFromGet("atr_IDperfil", "");
        $this->ds->Parameters["urlatr_Codmodulo"] = CCGetFromGet("atr_Codmodulo", "");
    }
//End Initialize Method

//GetFormParameters Method @3-16BE4308
    function GetFormParameters()
    {
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->FormParameters["atr_CodModulo"][$RowNumber] = CCGetFromPost("atr_CodModulo_" . $RowNumber);
            $this->FormParameters["atr_nivel"][$RowNumber] = CCGetFromPost("atr_nivel_" . $RowNumber);
        }
    }
//End GetFormParameters Method

//Validate Method @3-18423B34
    function Validate()
    {
        $Validation = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");

        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["atr_Codmodulo"] = $this->CachedColumns["atr_Codmodulo"][$RowNumber];
            $this->atr_CodModulo->SetText($this->FormParameters["atr_CodModulo"][$RowNumber], $RowNumber);
            $this->atr_nivel->SetText($this->FormParameters["atr_nivel"][$RowNumber], $RowNumber);
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

//ValidateRow Method @3-01B4455D
    function ValidateRow($RowNumber)
    {
        $Validation = true;
        $Validation = ($this->atr_CodModulo->Validate() && $Validation);
        $Validation = ($this->atr_nivel->Validate() && $Validation);
        $errors = "";
        if(!$Validation)
        {
            $errors .= $this->atr_CodModulo->Errors->ToString();
            $errors .= $this->atr_nivel->Errors->ToString();
            $this->atr_CodModulo->Errors->Clear();
            $this->atr_nivel->Errors->Clear();
        }
        $this->RowsErrors[$RowNumber] = $errors;
        return $Validation;
    }
//End ValidateRow Method

//CheckInsert Method @3-007FD2C2
    function CheckInsert($RowNumber)
    {
        $filed = false;
        $filed = ($filed || strlen($this->FormParameters["atr_CodModulo"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["atr_nivel"][$RowNumber]));
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

//Operation Method @3-6A172129
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
        }

        $Redirect = $FileName . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "Button_Submit") {
            if(!CCGetEvent($this->Button_Submit->CCSEvents, "OnClick") || !$this->UpdateGrid()) {
                $Redirect = "";
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//UpdateGrid Method @3-2A2879B1
    function UpdateGrid()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSubmit");
        if(!$this->Validate()) return;
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["atr_Codmodulo"] = $this->CachedColumns["atr_Codmodulo"][$RowNumber];
            $this->atr_CodModulo->SetText($this->FormParameters["atr_CodModulo"][$RowNumber], $RowNumber);
            $this->atr_nivel->SetText($this->FormParameters["atr_nivel"][$RowNumber], $RowNumber);
            if ($this->UpdatedRows >= $RowNumber) {
                if($this->UpdateAllowed) $this->UpdateRow($RowNumber);
            }
            else if($this->CheckInsert($RowNumber) && $this->InsertAllowed)
            {
                $this->InsertRow($RowNumber);
            }
        }
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterSubmit");
        return ($this->Errors->Count() == 0);
    }
//End UpdateGrid Method

//UpdateRow Method @3-082DDBFF
    function UpdateRow($RowNumber)
    {
        if(!$this->UpdateAllowed) return false;
        $this->ds->atr_nivel->SetValue($this->atr_nivel->GetValue());
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

//FormScript Method @3-59800DB5
    function FormScript($TotalRows)
    {
        $script = "";
        return $script;
    }
//End FormScript Method

//SetFormState Method @3-58776874
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
                $this->CachedColumns["atr_Codmodulo"][$RowNumber] = $piece;
                $RowNumber++;
            }

            if(!$RowNumber) { $RowNumber = 1; }
            for($i = 1; $i <= $this->EmptyRows; $i++) {
                $this->CachedColumns["atr_Codmodulo"][$RowNumber] = "";
                $RowNumber++;
            }
        }
    }
//End SetFormState Method

//GetFormState Method @3-EBDB105F
    function GetFormState($NonEmptyRows)
    {
        if(!$this->FormSubmitted) {
            $this->FormState  = $NonEmptyRows . ";";
            $this->FormState .= $this->InsertAllowed ? $this->EmptyRows : "0";
            if($NonEmptyRows) {
                for($i = 0; $i <= $NonEmptyRows; $i++) {
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["atr_Codmodulo"][$i]));
                }
            }
        }
        return $this->FormState;
    }
//End GetFormState Method

//Show Method @3-7D5A1529
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible) { return; }

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->atr_nivel->Prepare();

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
        $EmptyRowsLeft = $this->EmptyRows;
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
                        $this->eAplicacion->SetValue($this->ds->eAplicacion->GetValue());
                        $this->mod_descripcion->SetValue($this->ds->mod_descripcion->GetValue());
                    } else {
                        $this->eAplicacion->SetText("");
                        $this->mod_descripcion->SetText("");
                    }
                    $this->lSubmod->SetText("  ");
                    $this->lSubmod->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
                    $this->lSubmod->Parameters = CCAddParam($this->lSubmod->Parameters, "pSub", $this->ds->f("atr_Codmodulo"));
                    $this->lSubmod->Page = "SeGeAt_mant.php";
                    if(!$this->FormSubmitted && $is_next_record) {
                        $this->CachedColumns["atr_Codmodulo"][$RowNumber] = $this->ds->CachedColumns["atr_Codmodulo"];
                        $this->atr_CodModulo->SetValue($this->ds->atr_CodModulo->GetValue());
                        $this->atr_nivel->SetValue($this->ds->atr_nivel->GetValue());
                        $this->ValidateRow($RowNumber);
                    } else if (!$this->FormSubmitted){
                        $this->CachedColumns["atr_Codmodulo"][$RowNumber] = "";
                        $this->atr_CodModulo->SetText("");
                        $this->atr_nivel->SetText("");
                    } else {
                        $this->atr_CodModulo->SetText($this->FormParameters["atr_CodModulo"][$RowNumber], $RowNumber);
                        $this->atr_nivel->SetText($this->FormParameters["atr_nivel"][$RowNumber], $RowNumber);
                    }
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->atr_CodModulo->Show($RowNumber);
                    $this->lSubmod->Show($RowNumber);
                    $this->eAplicacion->Show($RowNumber);
                    $this->mod_descripcion->Show($RowNumber);
                    $this->atr_nivel->Show($RowNumber);
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
        $this->lbTitulo->SetText("DEFINICION DE ATRIBUTOS DE ACCESO");
        $this->Navigator->PageNumber = $this->ds->AbsolutePage;
        $this->Navigator->TotalPages = $this->ds->PageCount();
        $this->lbTitulo->Show();
        $this->Navigator->Show();
        $this->Button_Submit->Show();

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

} //End perfil_modu Class @3-FCB6E20C

class clsperfil_moduDataSource extends clsDBSeguridad {  //perfil_moduDataSource Class @3-E1CFEE60

//DataSource Variables @3-0AAA2C7D
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $UpdateParameters;
    var $CountSQL;
    var $wp;
    var $AllParametersSet;

    var $CachedColumns;

    // Datasource fields
    var $atr_CodModulo;
    var $lSubmod;
    var $eAplicacion;
    var $mod_descripcion;
    var $atr_nivel;
//End DataSource Variables

//Class_Initialize Event @3-E95E887B
    function clsperfil_moduDataSource()
    {
        $this->ErrorBlock = "EditableGrid perfil_modu/Error";
        $this->Initialize();
        $this->atr_CodModulo = new clsField("atr_CodModulo", ccsText, "");
        $this->lSubmod = new clsField("lSubmod", ccsText, "");
        $this->eAplicacion = new clsField("eAplicacion", ccsText, "");
        $this->mod_descripcion = new clsField("mod_descripcion", ccsText, "");
        $this->atr_nivel = new clsField("atr_nivel", ccsInteger, "");

    }
//End Class_Initialize Event

//SetOrder Method @3-9E1383D1
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            "");
    }
//End SetOrder Method

//Prepare Method @3-18ED3650
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlatr_IDperfil", ccsText, "", "", $this->Parameters["urlatr_IDperfil"], "---", false);
        $this->wp->AddParameter("2", "urlatr_Codmodulo", ccsInteger, "", "", $this->Parameters["urlatr_Codmodulo"], -1, false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "atr_IDperfil", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opEqual, "mod_id", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsInteger),false);
        $this->Where = $this->wp->opAND(false, $this->wp->Criterion[1], $this->wp->Criterion[2]);
    }
//End Prepare Method

//Open Method @3-04695C66
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM   segmodulos m LEFT JOIN segatributos  ON mod_id = atr_Codmodulo AND atr_IDperfil = '" . CCGetFromGet('atr_IDperfil', "...") . "' ";
/*        $this->SQL = "SELECT atr_IDperfil, ifnull(atr_Codmodulo, mod_id) as atr_Codmodulo, atr_nivel, mod_descripcion AS mod_descripcion, mod_subsistema AS mod_subsistema  " .
        "FROM segatributos INNER JOIN segmodulos m ON segatributos.atr_Codmodulo = m.mod_id";
*/
        $this->SQL = "SELECT ifnull(atr_IDperfil, ' " . CCGetFromGet("atr_Codmodulo", "") . "') AS atr_IDperfil, ifnull(atr_Codmodulo, mod_id) as atr_Codmodulo, ifnull(atr_nivel,0) as atr_nivel, mod_descripcion AS mod_descripcion, concat_ws('-', mod_subsistema, mod_modulo, mod_submod) AS mod_subsistema " .
                     "FROM   segmodulos m LEFT JOIN segatributos  ON mod_id = atr_Codmodulo AND atr_IDperfil = '" . CCGetFromGet('atr_IDperfil', "...") . "' ";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->Where ="mod_id = " . CCGetFromGet("atr_Codmodulo", ""); // fah
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @3-75B53B99
    function SetValues()
    {
        $this->CachedColumns["atr_Codmodulo"] = $this->f("atr_Codmodulo");
        $this->atr_CodModulo->SetDBValue($this->f("atr_Codmodulo"));
        $this->eAplicacion->SetDBValue($this->f("mod_subsistema"));
        $this->mod_descripcion->SetDBValue($this->f("mod_descripcion"));
        $this->atr_nivel->SetDBValue(trim($this->f("atr_nivel")));
    }
//End SetValues Method

//Update Method @3-32FA9F58
    function Update()
    {
        $this->cp["atr_nivel"] = new clsSQLParameter("ctrlatr_nivel", ccsInteger, "", "", $this->atr_nivel->GetValue(), "", false, $this->ErrorBlock);
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlatr_IDperfil", ccsText, "", "", CCGetFromGet("atr_IDperfil", ""), "", false);
        $wp->AddParameter("2", "dsatr_Codmodulo", ccsInteger, "", "", $this->CachedColumns["atr_Codmodulo"], "", false);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $wp->Criterion[1] = $wp->Operation(opEqual, "atr_IDperfil", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsText),false);
        $wp->Criterion[2] = $wp->Operation(opEqual, "atr_Codmodulo", $wp->GetDBValue("2"), $this->ToSQL($wp->GetDBValue("2"), ccsInteger),false);
        $Where = $wp->opAND(false, $wp->Criterion[1], $wp->Criterion[2]);
/*        $this->SQL = "UPDATE segatributos SET "
             . "atr_nivel=" . $this->ToSQL($this->cp["atr_nivel"]->GetDBValue(), $this->cp["atr_nivel"]->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
*/
        if(CCGetParam("atr_IDperfil",false)) {
            $this->SQL = "INSERT INTO segatributos (atr_Idperfil, atr_codmodulo, atr_nivel) " .
                     "VALUES ('" . CCGetParam("atr_IDperfil","") . "' , " .
                                   CCGetParam("atr_Codmodulo","") . ", " .
                                   $this->ToSQL($this->cp["atr_nivel"]->GetDBValue(), $this->cp["atr_nivel"]->DataType) . ") " .
                                   "ON DUPLICATE KEY UPDATE atr_nivel = " . $this->ToSQL($this->cp["atr_nivel"]->GetDBValue(), $this->cp["atr_nivel"]->DataType) ;
        }
        else {
            $this->SQL="";
            $this->Errors->AddError("NO SE PUEDE AGREGAR UN ATRIBUTO SIN LOS PARAMETROS REQUERIDOS");
        }
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Update Method

} //End perfil_moduDataSource Class @3-FCB6E20C

Class clsEditableGridperfil_subm { //perfil_subm Class @77-A5E6DD7C

//Variables @77-A5C1CE1E

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
    var $DeleteAllowed = false;
    var $EditMode;
    var $ValidatingControls;
    var $Controls;
    var $ControlsErrors;

    // Class variables
    var $Navigator;
//End Variables

//Class_Initialize Event @77-CACC1875
    function clsEditableGridperfil_subm()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "EditableGrid perfil_subm/Error";
        $this->ComponentName = "perfil_subm";
        $this->CachedColumns["atr_Codmodulo"][0] = "atr_Codmodulo";
        $this->ds = new clsperfil_submDataSource();

        $this->PageSize = 10;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: EditableGrid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));

        $this->EmptyRows = 3;
        $this->UpdateAllowed = true;
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

        $this->atr_CodModulo = new clsControl(ccsHidden, "atr_CodModulo", "Atr Codmodulo", ccsInteger, "");
        $this->atr_CodModulo->Required = true;
        $this->lOpciones = new clsControl(ccsLink, "lOpciones", "lOpciones", ccsText, "");
        $this->eModulo = new clsControl(ccsLabel, "eModulo", "Atr IDperfil", ccsText, "");
        $this->mod_descripcion = new clsControl(ccsLabel, "mod_descripcion", "Sm Mod Descripcion", ccsText, "");
        $this->atr_nivel = new clsControl(ccsListBox, "atr_nivel", "Atr Nivel", ccsInteger, "");
        $this->atr_nivel->DSType = dsListOfValues;
        $this->atr_nivel->Values = array(array("0", "INHABILITADO"), array("1", "HABILITADO"), array("2", "TOTAL"));
        $this->atr_nivel->Required = true;
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple);
        $this->Button_Submit = new clsButton("Button_Submit");
    }
//End Class_Initialize Event

//Initialize Method @77-D39A0DA6
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);

        $this->ds->Parameters["urlatr_IDperfil"] = CCGetFromGet("atr_IDperfil", "");
        $this->ds->Parameters["urlatr_Codmodulo"] = CCGetFromGet("atr_Codmodulo", "");
    }
//End Initialize Method

//GetFormParameters Method @77-16BE4308
    function GetFormParameters()
    {
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->FormParameters["atr_CodModulo"][$RowNumber] = CCGetFromPost("atr_CodModulo_" . $RowNumber);
            $this->FormParameters["atr_nivel"][$RowNumber] = CCGetFromPost("atr_nivel_" . $RowNumber);
        }
    }
//End GetFormParameters Method

//Validate Method @77-18423B34
    function Validate()
    {
        $Validation = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");

        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["atr_Codmodulo"] = $this->CachedColumns["atr_Codmodulo"][$RowNumber];
            $this->atr_CodModulo->SetText($this->FormParameters["atr_CodModulo"][$RowNumber], $RowNumber);
            $this->atr_nivel->SetText($this->FormParameters["atr_nivel"][$RowNumber], $RowNumber);
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

//ValidateRow Method @77-01B4455D
    function ValidateRow($RowNumber)
    {
        $Validation = true;
        $Validation = ($this->atr_CodModulo->Validate() && $Validation);
        $Validation = ($this->atr_nivel->Validate() && $Validation);
        $errors = "";
        if(!$Validation)
        {
            $errors .= $this->atr_CodModulo->Errors->ToString();
            $errors .= $this->atr_nivel->Errors->ToString();
            $this->atr_CodModulo->Errors->Clear();
            $this->atr_nivel->Errors->Clear();
        }
        $this->RowsErrors[$RowNumber] = $errors;
        return $Validation;
    }
//End ValidateRow Method

//CheckInsert Method @77-007FD2C2
    function CheckInsert($RowNumber)
    {
        $filed = false;
        $filed = ($filed || strlen($this->FormParameters["atr_CodModulo"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["atr_nivel"][$RowNumber]));
        return $filed;
    }
//End CheckInsert Method

//CheckErrors Method @77-242E5992
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @77-6A172129
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
        }

        $Redirect = $FileName . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "Button_Submit") {
            if(!CCGetEvent($this->Button_Submit->CCSEvents, "OnClick") || !$this->UpdateGrid()) {
                $Redirect = "";
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//UpdateGrid Method @77-2A2879B1
    function UpdateGrid()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSubmit");
        if(!$this->Validate()) return;
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["atr_Codmodulo"] = $this->CachedColumns["atr_Codmodulo"][$RowNumber];
            $this->atr_CodModulo->SetText($this->FormParameters["atr_CodModulo"][$RowNumber], $RowNumber);
            $this->atr_nivel->SetText($this->FormParameters["atr_nivel"][$RowNumber], $RowNumber);
            if ($this->UpdatedRows >= $RowNumber) {
                if($this->UpdateAllowed) $this->UpdateRow($RowNumber);
            }
            else if($this->CheckInsert($RowNumber) && $this->InsertAllowed)
            {
                $this->InsertRow($RowNumber);
            }
        }
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterSubmit");
        return ($this->Errors->Count() == 0);
    }
//End UpdateGrid Method

//UpdateRow Method @77-082DDBFF
    function UpdateRow($RowNumber)
    {
        if(!$this->UpdateAllowed) return false;
        $this->ds->atr_nivel->SetValue($this->atr_nivel->GetValue());
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

//FormScript Method @77-59800DB5
    function FormScript($TotalRows)
    {
        $script = "";
        return $script;
    }
//End FormScript Method

//SetFormState Method @77-58776874
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
                $this->CachedColumns["atr_Codmodulo"][$RowNumber] = $piece;
                $RowNumber++;
            }

            if(!$RowNumber) { $RowNumber = 1; }
            for($i = 1; $i <= $this->EmptyRows; $i++) {
                $this->CachedColumns["atr_Codmodulo"][$RowNumber] = "";
                $RowNumber++;
            }
        }
    }
//End SetFormState Method

//GetFormState Method @77-EBDB105F
    function GetFormState($NonEmptyRows)
    {
        if(!$this->FormSubmitted) {
            $this->FormState  = $NonEmptyRows . ";";
            $this->FormState .= $this->InsertAllowed ? $this->EmptyRows : "0";
            if($NonEmptyRows) {
                for($i = 0; $i <= $NonEmptyRows; $i++) {
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["atr_Codmodulo"][$i]));
                }
            }
        }
        return $this->FormState;
    }
//End GetFormState Method

//Show Method @77-6E315D20
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible) { return; }

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->atr_nivel->Prepare();

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
        $EmptyRowsLeft = $this->EmptyRows;
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
                        $this->eModulo->SetValue($this->ds->eModulo->GetValue());
                        $this->mod_descripcion->SetValue($this->ds->mod_descripcion->GetValue());
                    } else {
                        $this->eModulo->SetText("");
                        $this->mod_descripcion->SetText("");
                    }
                    $this->lOpciones->SetText(">>> ");
                    $this->lOpciones->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
                    $this->lOpciones->Parameters = CCAddParam($this->lOpciones->Parameters, "pOpc", $this->ds->f("atr_Codmodulo"));
                    $this->lOpciones->Page = "SeGeAt_mant.php";
                    if(!$this->FormSubmitted && $is_next_record) {
                        $this->CachedColumns["atr_Codmodulo"][$RowNumber] = $this->ds->CachedColumns["atr_Codmodulo"];
                        $this->atr_CodModulo->SetValue($this->ds->atr_CodModulo->GetValue());
                        $this->atr_nivel->SetValue($this->ds->atr_nivel->GetValue());
                        $this->ValidateRow($RowNumber);
                    } else if (!$this->FormSubmitted){
                        $this->CachedColumns["atr_Codmodulo"][$RowNumber] = "";
                        $this->atr_CodModulo->SetText("");
                        $this->atr_nivel->SetText("");
                    } else {
                        $this->atr_CodModulo->SetText($this->FormParameters["atr_CodModulo"][$RowNumber], $RowNumber);
                        $this->atr_nivel->SetText($this->FormParameters["atr_nivel"][$RowNumber], $RowNumber);
                    }
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->atr_CodModulo->Show($RowNumber);
                    $this->lOpciones->Show($RowNumber);
                    $this->eModulo->Show($RowNumber);
                    $this->mod_descripcion->Show($RowNumber);
                    $this->atr_nivel->Show($RowNumber);
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
        $this->Navigator->Show();
        $this->Button_Submit->Show();

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

} //End perfil_subm Class @77-FCB6E20C

class clsperfil_submDataSource extends clsDBSeguridad {  //perfil_submDataSource Class @77-163F62AE

//DataSource Variables @77-937C3958
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $UpdateParameters;
    var $CountSQL;
    var $wp;
    var $AllParametersSet;

    var $CachedColumns;

    // Datasource fields
    var $atr_CodModulo;
    var $lOpciones;
    var $eModulo;
    var $mod_descripcion;
    var $atr_nivel;
//End DataSource Variables

//Class_Initialize Event @77-5D056524
    function clsperfil_submDataSource()
    {
        $this->ErrorBlock = "EditableGrid perfil_subm/Error";
        $this->Initialize();
        $this->atr_CodModulo = new clsField("atr_CodModulo", ccsInteger, "");
        $this->lOpciones = new clsField("lOpciones", ccsText, "");
        $this->eModulo = new clsField("eModulo", ccsText, "");
        $this->mod_descripcion = new clsField("mod_descripcion", ccsText, "");
        $this->atr_nivel = new clsField("atr_nivel", ccsInteger, "");

    }
//End Class_Initialize Event

//SetOrder Method @77-9E1383D1
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            "");
    }
//End SetOrder Method

//Prepare Method @77-F2B15728
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlatr_IDperfil", ccsText, "", "", $this->Parameters["urlatr_IDperfil"], "", true);
        $this->wp->AddParameter("2", "urlatr_Codmodulo", ccsInteger, "", "", $this->Parameters["urlatr_Codmodulo"], "", true);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "atr_IDperfil", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),true);
        $this->wp->Criterion[2] = $this->wp->Operation(opEqual, "mod_padre", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsInteger),true);
        $this->Where = $this->wp->opAND(false, $this->wp->Criterion[1], $this->wp->Criterion[2]);
    }
//End Prepare Method

//Open Method @77-C1396B4F
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM segmodulos m INNER JOIN segatributos ON m.mod_codigo = segatributos.atr_Codmodulo";
        $this->SQL = "SELECT atr_IDperfil, atr_Codmodulo, atr_nivel, mod_descripcion AS sub_descripcion, mod_modulo AS mod_Modulo  " .
        "FROM segmodulos m INNER JOIN segatributos ON m.mod_id = segatributos.atr_Codmodulo";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @77-56C99970
    function SetValues()
    {
        $this->CachedColumns["atr_Codmodulo"] = $this->f("atr_Codmodulo");
        $this->atr_CodModulo->SetDBValue(trim($this->f("atr_Codmodulo")));
        $this->eModulo->SetDBValue($this->f("mod_Modulo"));
        $this->mod_descripcion->SetDBValue($this->f("sub_descripcion"));
        $this->atr_nivel->SetDBValue(trim($this->f("atr_nivel")));
    }
//End SetValues Method

//Update Method @77-32FA9F58
    function Update()
    {
        $this->cp["atr_nivel"] = new clsSQLParameter("ctrlatr_nivel", ccsInteger, "", "", $this->atr_nivel->GetValue(), "", false, $this->ErrorBlock);
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlatr_IDperfil", ccsText, "", "", CCGetFromGet("atr_IDperfil", ""), "", false);
        $wp->AddParameter("2", "dsatr_Codmodulo", ccsInteger, "", "", $this->CachedColumns["atr_Codmodulo"], "", false);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $wp->Criterion[1] = $wp->Operation(opEqual, "atr_IDperfil", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsText),false);
        $wp->Criterion[2] = $wp->Operation(opEqual, "atr_Codmodulo", $wp->GetDBValue("2"), $this->ToSQL($wp->GetDBValue("2"), ccsInteger),false);
        $Where = $wp->opAND(false, $wp->Criterion[1], $wp->Criterion[2]);
        $this->SQL = "UPDATE segatributos SET "
             . "atr_nivel=" . $this->ToSQL($this->cp["atr_nivel"]->GetDBValue(), $this->cp["atr_nivel"]->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Update Method

} //End perfil_submDataSource Class @77-FCB6E20C

//Initialize Page @1-CFE483BB
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

$FileName = "SeGeAt_mant.php";
$Redirect = "";
$TemplateFileName = "SeGeAt_mant.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-FE99C435
$DBSeguridad = new clsDBSeguridad();

// Controls
$perfil_modu = new clsEditableGridperfil_modu();
$perfil_subm = new clsEditableGridperfil_subm();
$perfil_modu->Initialize();
$perfil_subm->Initialize();

// Events
include("./SeGeAt_mant_events.php");
BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-B4465026
$perfil_modu->Operation();
$perfil_subm->Operation();
//End Execute Components

//Go to destination page @1-ADB1C9C4
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    $DBSeguridad->close();
    header("Location: " . $Redirect);
    exit;
}
//End Go to destination page

//Show Page @1-F259E087
$perfil_modu->Show();
$perfil_subm->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
$generated_with = "<center><font face=\"Arial\"><small>Generated with CodeCharge Studio</small></font></center>";
$main_block = preg_match("/<\/body>/i", $main_block) ? preg_replace("/<\/body>/i", $generated_with . "</body>", $main_block) : $main_block . $generated_with;
echo $main_block;
//End Show Page

//Unload Page @1-23B48A28
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBSeguridad->close();
unset($Tpl);
//End Unload Page


?>
