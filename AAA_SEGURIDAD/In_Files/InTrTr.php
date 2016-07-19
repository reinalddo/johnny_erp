<?php
/*
 *  @rev    fah Jun/10/09       Aplicar restricciones de acceso por Usuario- Emisor, segun $_SESSION[restr]
 **/
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include("GenUti.inc.php");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @99-49BEB8FC
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation

class clsRecordInTrTr_qry { //InTrTr_qry Class @5-113DEF8A

//Variables @5-CB19EB75

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

//Class_Initialize Event @5-A203767C
    function clsRecordInTrTr_qry()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record InTrTr_qry/Error";
        $this->ReadAllowed = false;
        $this->InsertAllowed = false;
        $this->UpdateAllowed = false;
        $this->DeleteAllowed = false;
        if($this->Visible)
        {
            $this->ComponentName = "InTrTr_qry";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->lbTituloQry = new clsControl(ccsLabel, "lbTituloQry", "lbTituloQry", ccsText, "", CCGetRequestParam("lbTituloQry", $Method));
            $this->s_com_TipoComp = new clsControl(ccsTextBox, "s_com_TipoComp", "Tipo de comprobante", ccsText, "", CCGetRequestParam("s_com_TipoComp", $Method));
            $this->s_com_NumComp = new clsControl(ccsTextBox, "s_com_NumComp", "Numero de Comprobante", ccsInteger, "", CCGetRequestParam("s_com_NumComp", $Method));
            $this->s_com_RegNumero = new clsControl(ccsTextBox, "s_com_RegNumero", "Numero de Registro", ccsInteger, "", CCGetRequestParam("s_com_RegNumero", $Method));
            $this->s_com_RefOperat = new clsControl(ccsTextBox, "s_com_RefOperat", "Semana", ccsInteger, "", CCGetRequestParam("com_RefOperat", $Method));
            $this->s_com_FecTrans = new clsControl(ccsTextBox, "s_com_FecTrans", "Fecha de Transaccion", ccsDate, Array("dd", "/", "mm", "/", "yy"), CCGetRequestParam("s_com_FecTrans", $Method));
            $this->s_com_FecContab = new clsControl(ccsTextBox, "s_com_FecContab", "Fecha Contable", ccsDate, Array("dd", "/", "mm", "/", "yy"), CCGetRequestParam("s_com_FecContab", $Method));
            $this->s_com_FecDigita = new clsControl(ccsTextBox, "s_com_FecDigita", "Fecha Digitacion", ccsDate, Array("dd", "/", "mm", "/", "yy"), CCGetRequestParam("s_com_FecDigita", $Method));
            $this->s_com_Receptor = new clsControl(ccsTextBox, "s_com_Receptor", "Nombre del Beneficiario de la Transaccion", ccsText, "", CCGetRequestParam("s_com_Receptor", $Method));
            $this->s_com_Concepto = new clsControl(ccsTextBox, "s_com_Concepto", "Concepto", ccsMemo, "", CCGetRequestParam("s_com_Concepto", $Method));
            $this->s_com_EstProceso = new clsControl(ccsListBox, "s_com_EstProceso", "Estado de Proceso Contable", ccsFloat, "", CCGetRequestParam("s_com_EstProceso", $Method));
            $this->s_com_EstProceso->DSType = dsTable;
            list($this->s_com_EstProceso->BoundColumn, $this->s_com_EstProceso->TextColumn, $this->s_com_EstProceso->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->s_com_EstProceso->ds = new clsDBdatos();
            $this->s_com_EstProceso->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->s_com_EstProceso->ds->Parameters["expr131"] = 'CADEST';
            $this->s_com_EstProceso->ds->wp = new clsSQLParameters();
            $this->s_com_EstProceso->ds->wp->AddParameter("1", "expr131", ccsText, "", "", $this->s_com_EstProceso->ds->Parameters["expr131"], "", true);
            $this->s_com_EstProceso->ds->wp->Criterion[1] = $this->s_com_EstProceso->ds->wp->Operation(opEqual, "par_Clave", $this->s_com_EstProceso->ds->wp->GetDBValue("1"), $this->s_com_EstProceso->ds->ToSQL($this->s_com_EstProceso->ds->wp->GetDBValue("1"), ccsText),true);
            $this->s_com_EstProceso->ds->Where = $this->s_com_EstProceso->ds->wp->Criterion[1];
            
            $this->s_com_emisor = new clsControl(ccsTextBox, "s_com_emisor", "Nombre del Emisor", ccsText, "", CCGetRequestParam("s_com_emisor", $Method));
            //$this->s_com_Libro = new clsControl(ccsTextBox, "s_com_Libro", "Libro", ccsText, "", CCGetRequestParam("s_com_Lib
            
            $this->s_com_Libro = new clsControl(ccsListBox, "s_com_Libro", "Estado de Proceso Contable", ccsFloat, "", CCGetRequestParam("s_com_Libro", $Method));
            $this->s_com_Libro->DSType = dsTable;
            list($this->s_com_Libro->BoundColumn, $this->s_com_Libro->TextColumn, $this->s_com_Libro->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->s_com_Libro->ds = new clsDBdatos();
            $this->s_com_Libro->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->s_com_Libro->ds->Parameters["expr88"] = 'CLIBRO';
            $this->s_com_Libro->ds->wp = new clsSQLParameters();
            $this->s_com_Libro->ds->wp->AddParameter("1", "expr88", ccsText, "", "", $this->s_com_Libro->ds->Parameters["expr88"], "", true);
            $this->s_com_Libro->ds->wp->Criterion[1] = $this->s_com_Libro->ds->wp->Operation(opEqual, "par_Clave", $this->s_com_Libro->ds->wp->GetDBValue("1"), $this->s_com_Libro->ds->ToSQL($this->s_com_Libro->ds->wp->GetDBValue("1"), ccsText),true);
            $this->s_com_Libro->ds->Where = $this->s_com_Libro->ds->wp->Criterion[1];
            
            
            //$this->s_com_Supervisor = new clsControl(ccsTextBox, "s_com_Supervisor", "Supervisor", ccsText, "", CCGetRequestParam("s_com_Supervisor", $Method));
            $this->s_com_Supervisor = new clsControl(ccsListBox, "s_com_Supervisor", "s_com_Supervisor", ccsInteger, "", CCGetRequestParam("s_com_Supervisor", $Method));
            $this->s_com_Supervisor->DSType = dsTable;
            list($this->s_com_Supervisor->BoundColumn, $this->s_com_Supervisor->TextColumn, $this->s_com_Supervisor->DBFormat) = array("per_CodAuxiliar", "per_Nombre", "");
            $this->s_com_Supervisor->ds = new clsDBdatos();            
            $this->s_com_Supervisor->ds->SQL = "SELECT per_CodAuxiliar, concat( per_Apellidos,' ', per_Nombres ) as per_Nombre  " .
            "FROM conpersonas INNER JOIN concategorias on conpersonas.per_codauxiliar = concategorias.cat_codauxiliar WHERE cat_Categoria = '92'";            
            
            $this->s_com_Contenedor = new clsControl(ccsTextBox, "s_com_Contenedor", "Contenedor", ccsText, "", CCGetRequestParam("s_com_Contenedor", $Method));
            
            $this->Button_DoSearch = new clsButton("Button_DoSearch");
            $this->btCancelar = new clsButton("btCancelar");
            if(!$this->FormSubmitted) {
                if(!is_array($this->s_com_EstProceso->Value) && !strlen($this->s_com_EstProceso->Value) && $this->s_com_EstProceso->Value !== false)
                $this->s_com_EstProceso->SetText(9999);
            }
        }
    }
//End Class_Initialize Event

//Validate Method @5-D8349152
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_com_TipoComp->Validate() && $Validation);
        $Validation = ($this->s_com_NumComp->Validate() && $Validation);
        $Validation = ($this->s_com_RegNumero->Validate() && $Validation);
        $Validation = ($this->s_com_FecTrans->Validate() && $Validation);
        $Validation = ($this->s_com_FecContab->Validate() && $Validation);
        $Validation = ($this->s_com_FecDigita->Validate() && $Validation);
        $Validation = ($this->s_com_Receptor->Validate() && $Validation);
        $Validation = ($this->s_com_Concepto->Validate() && $Validation);
        $Validation = ($this->s_com_EstProceso->Validate() && $Validation);
        $Validation = ($this->s_com_emisor->Validate() && $Validation);
        $Validation = ($this->s_com_Libro->Validate() && $Validation);
        $Validation = ($this->s_com_Supervisor->Validate() && $Validation);
        $Validation = ($this->s_com_Contenedor->Validate() && $Validation);
        $Validation = ($this->s_com_RefOperat->Validate() && $Validation);
        
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @5-7F659F51
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->lbTituloQry->Errors->Count());
        $errors = ($errors || $this->s_com_TipoComp->Errors->Count());
        $errors = ($errors || $this->s_com_NumComp->Errors->Count());
        $errors = ($errors || $this->s_com_RegNumero->Errors->Count());
        $errors = ($errors || $this->s_com_FecTrans->Errors->Count());
        $errors = ($errors || $this->s_com_FecContab->Errors->Count());
        $errors = ($errors || $this->s_com_FecDigita->Errors->Count());
        $errors = ($errors || $this->s_com_Receptor->Errors->Count());
        $errors = ($errors || $this->s_com_Concepto->Errors->Count());
        $errors = ($errors || $this->s_com_EstProceso->Errors->Count());
        $errors = ($errors || $this->s_com_RefOperat->Errors->Count());
        $errors = ($errors || $this->s_com_emisor->Errors->Count());
        $errors = ($errors || $this->s_com_Libro->Errors->Count());
        $errors = ($errors || $this->s_com_Supervisor->Errors->Count());
        $errors = ($errors || $this->s_com_Contenedor->Errors->Count());
        
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @5-00DE88B1
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
            } else if(strlen(CCGetParam("btCancelar", ""))) {
                $this->PressedButton = "btCancelar";
            }
        }
        $Redirect = "InTrTr.php?pMod=" . fGetParam("pMod", "CO")."&";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick")) {
                    $Redirect = "";
                } else {
                    $Redirect .= "InTrTr.php" . "?pMod=" . fGetParam("pMod", "CO") . "&" . CCMergeQueryStrings(CCGetQueryString("Form", Array("Button_DoSearch", "btCancelar")));
                }
            } else if($this->PressedButton == "btCancelar") {
                if(!CCGetEvent($this->btCancelar->CCSEvents, "OnClick")) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @5-E16735D1
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->s_com_EstProceso->Prepare();
        $this->s_com_Libro->Prepare();
        $this->s_com_Supervisor->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        if(!$this->FormSubmitted)
        {
            $this->lbTituloQry->SetText("BUSQUEDA DE COMPROBANTES");
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->lbTituloQry->Errors->ToString();
            $Error .= $this->s_com_TipoComp->Errors->ToString();
            $Error .= $this->s_com_NumComp->Errors->ToString();
            $Error .= $this->s_com_RegNumero->Errors->ToString();
            $Error .= $this->s_com_FecTrans->Errors->ToString();
            $Error .= $this->s_com_FecContab->Errors->ToString();
            $Error .= $this->s_com_FecDigita->Errors->ToString();
            $Error .= $this->s_com_Receptor->Errors->ToString();
            $Error .= $this->s_com_Concepto->Errors->ToString();
            $Error .= $this->s_com_EstProceso->Errors->ToString();
            $Error .= $this->s_com_emisor->Errors->ToString();
            $Error .= $this->s_com_Libro->Errors->ToString();
            $Error .= $this->s_com_Supervidsor->Errors->ToString();
            $Error .= $this->s_com_Contenedor->Errors->ToString();
            $Error .= $this->s_com_RefOperat->Errors->ToString();
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

        $this->lbTituloQry->Show();
        $this->s_com_TipoComp->Show();
        $this->s_com_NumComp->Show();
        $this->s_com_RegNumero->Show();
        $this->s_com_FecTrans->Show();
        $this->s_com_FecContab->Show();
        $this->s_com_FecDigita->Show();
        $this->s_com_Receptor->Show();
        $this->s_com_Concepto->Show();
        $this->s_com_RefOperat->Show();
        $this->s_com_EstProceso->Show();
        $this->Button_DoSearch->Show();
        
        $this->s_com_emisor->Show();
        $this->s_com_Libro->Show();
        $this->s_com_Supervisor->Show();
        $this->s_com_Contenedor->Show();
        
        $this->btCancelar->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End InTrTr_qry Class @5-FCB6E20C

class clsGridInTrTr_list { //InTrTr_list class @2-815AAD49

//Variables @2-03FB8474

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
    var $Navigator;
    var $Sorter_com_NumComp;
    var $Sorter_com_FecTrans;
    var $Sorter_com_FecContab;
    var $Sorter_com_Usuario;
    var $Sorter_com_RefOperat;
//End Variables

//Class_Initialize Event @2-8A6D4415
    function clsGridInTrTr_list()
    {
        global $FileName;
        $this->ComponentName = "InTrTr_list";
        $this->Visible = True;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid InTrTr_list";
        $this->ds = new clsInTrTr_listDataSource();
        $this->PageSize = 25;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        $this->SorterName = CCGetParam("InTrTr_listOrder", "");
        $this->SorterDirection = CCGetParam("InTrTr_listDir", "");

        $this->hTipoComp = new clsControl(ccsHidden, "hTipoComp", "hTipoComp", ccsText, "", CCGetRequestParam("hTipoComp", ccsGet));
        $this->hNumComp = new clsControl(ccsHidden, "hNumComp", "hNumComp", ccsInteger, "", CCGetRequestParam("hNumComp", ccsGet));
        $this->com_Comproba = new clsControl(ccsLink, "com_Comproba", "com_Comproba", ccsText, "", CCGetRequestParam("com_Comproba", ccsGet));
        $this->com_FecTrans = new clsControl(ccsLabel, "com_FecTrans", "com_FecTrans", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("com_FecTrans", ccsGet));
        $this->com_FecContab = new clsControl(ccsLabel, "com_FecContab", "com_FecContab", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("com_FecContab", ccsGet));
        $this->com_Concepto = new clsControl(ccsLabel, "com_Concepto", "com_Concepto", ccsMemo, "", CCGetRequestParam("com_Concepto", ccsGet));
        $this->com_Usuario = new clsControl(ccsLabel, "com_Usuario", "com_Usuario", ccsText, "", CCGetRequestParam("com_Usuario", ccsGet));
        $this->com_EstProceso = new clsControl(ccsLabel, "com_EstProceso", "com_EstProceso", ccsText, "", CCGetRequestParam("com_EstProceso", ccsGet));
        $this->com_RefOperat = new clsControl(ccsLabel, "com_RefOperat", "com_RefOperat", ccsInteger, "", CCGetRequestParam("com_RefOperat", ccsGet));
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpCentered);
        $this->concomprobantes_Insert = new clsControl(ccsLink, "concomprobantes_Insert", "concomprobantes_Insert", ccsText, "", CCGetRequestParam("concomprobantes_Insert", ccsGet));
        $this->concomprobantes_Insert->Parameters = CCGetQueryString("QueryString", Array("com_RegNumero", "ccsForm", "pTipoComp"));
        $this->concomprobantes_Insert->Page = "InTrTr_Cabe.php";
        $this->Sorter_com_NumComp = new clsSorter($this->ComponentName, "Sorter_com_NumComp", $FileName);
        $this->Sorter_com_FecTrans = new clsSorter($this->ComponentName, "Sorter_com_FecTrans", $FileName);
        $this->Sorter_com_FecContab = new clsSorter($this->ComponentName, "Sorter_com_FecContab", $FileName);
        $this->Sorter_com_Usuario = new clsSorter($this->ComponentName, "Sorter_com_Usuario", $FileName);
        $this->Sorter_com_RefOperat = new clsSorter($this->ComponentName, "Sorter_com_RefOperat", $FileName);
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

//Show Method @2-521E504E
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
        $this->ds->Parameters["urls_com_FecDigita"] = CCGetFromGet("s_com_FecDigita", "");
        $this->ds->Parameters["urls_com_Receptor"] = CCGetFromGet("s_com_Receptor", "");
        $this->ds->Parameters["urls_com_Concepto"] = CCGetFromGet("s_com_Concepto", "");
        $this->ds->Parameters["urls_com_EstProceso"] = CCGetFromGet("s_com_EstProceso", "");

        $this->ds->Parameters["urls_com_emisor"] = CCGetFromGet("s_com_emisor", "");
        $this->ds->Parameters["urls_com_Libro"] = CCGetFromGet("s_com_Libro", "");
        $this->ds->Parameters["urls_com_Supervisor"] = CCGetFromGet("s_com_Supervisor", "");
        $this->ds->Parameters["urls_com_Contenedor"] = CCGetFromGet("s_com_Contenedor", "");
        $this->ds->Parameters["urls_com_RefOperat"] = CCGetFromGet("com_RefOperat", "");
        
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
                $this->hTipoComp->SetValue($this->ds->hTipoComp->GetValue());
                $this->hNumComp->SetValue($this->ds->hNumComp->GetValue());
                $this->com_Comproba->SetValue($this->ds->com_Comproba->GetValue());
                $this->com_Comproba->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
                $this->com_Comproba->Parameters = CCAddParam($this->com_Comproba->Parameters, "com_RegNumero", $this->ds->f("com_RegNumero"));
                $this->com_Comproba->Parameters = CCAddParam($this->com_Comproba->Parameters, "pTipoComp", $this->ds->f("com_TipoComp"));
                $this->com_Comproba->Page = "InTrTr_Cabe.php";
                $this->com_FecTrans->SetValue($this->ds->com_FecTrans->GetValue());
                $this->com_FecContab->SetValue($this->ds->com_FecContab->GetValue());
                $this->com_Concepto->SetValue($this->ds->com_Concepto->GetValue());
                $this->com_Usuario->SetValue($this->ds->com_Usuario->GetValue());
                $this->com_EstProceso->SetValue($this->ds->com_EstProceso->GetValue());
                $this->com_RefOperat->SetValue($this->ds->com_RefOperat->GetValue());
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->hTipoComp->Show();
                $this->hNumComp->Show();
                $this->com_Comproba->Show();
                $this->com_FecTrans->Show();
                $this->com_FecContab->Show();
                $this->com_Concepto->Show();
                $this->com_Usuario->Show();
                $this->com_EstProceso->Show();
                $this->com_RefOperat->Show();
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
        $this->Navigator->Show();
        $this->concomprobantes_Insert->Show();
        $this->Sorter_com_NumComp->Show();
        $this->Sorter_com_FecTrans->Show();
        $this->Sorter_com_FecContab->Show();
        $this->Sorter_com_Usuario->Show();
        $this->Sorter_com_RefOperat->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @2-F56DCCF4
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->hTipoComp->Errors->ToString();
        $errors .= $this->hNumComp->Errors->ToString();
        $errors .= $this->com_Comproba->Errors->ToString();
        $errors .= $this->com_FecTrans->Errors->ToString();
        $errors .= $this->com_FecContab->Errors->ToString();
        $errors .= $this->com_Concepto->Errors->ToString();
        $errors .= $this->com_Usuario->Errors->ToString();
        $errors .= $this->com_EstProceso->Errors->ToString();
        $errors .= $this->com_RefOperat->Errors->ToString();
        
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End InTrTr_list Class @2-FCB6E20C

class clsInTrTr_listDataSource extends clsDBdatos {  //InTrTr_listDataSource Class @2-6D7ACF4A

//DataSource Variables @2-F58DE582
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $hTipoComp;
    var $hNumComp;
    var $com_Comproba;
    var $com_FecTrans;
    var $com_FecContab;
    var $com_FecDigita;
    var $com_Concepto;
    var $com_Usuario;
    var $com_EstProceso;
    var $com_RefOperat;
//End DataSource Variables

//Class_Initialize Event @2-88EED677
    function clsInTrTr_listDataSource()
    {
        $this->ErrorBlock = "Grid InTrTr_list";
        $this->Initialize();
        $this->hTipoComp = new clsField("hTipoComp", ccsText, "");
        $this->hNumComp = new clsField("hNumComp", ccsInteger, "");
        $this->com_Comproba = new clsField("com_Comproba", ccsText, "");
        $this->com_FecTrans = new clsField("com_FecTrans", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->com_FecContab = new clsField("com_FecContab", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->com_FecDigita = new clsField("com_FecDigita", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->com_Concepto = new clsField("com_Concepto", ccsMemo, "");
        $this->com_Usuario = new clsField("com_Usuario", ccsText, "");
        $this->com_EstProceso = new clsField("com_EstProceso", ccsText, "");
        $this->com_RefOperat = new clsField("com_RefOperat", ccsInteger, "");

    }
//End Class_Initialize Event

//SetOrder Method @2-35939D83
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_com_NumComp" => array("com_NumComp", ""), 
            "Sorter_com_FecTrans" => array("com_FecTrans", ""), 
            "Sorter_com_FecContab" => array("com_FecContab", ""), 
            "Sorter_com_Usuario" => array("com_Usuario", ""),
            "Sorter_com_RefOperat" => array("com_RefOperat", "")));
    }
//End SetOrder Method

//Prepare Method @2-704DEC36
    function Prepare()
    {   //var_dump($this->Parameters);
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_com_TipoComp", ccsText, "", "", $this->Parameters["urls_com_TipoComp"], "", false);
        $this->wp->AddParameter("2", "urls_com_NumComp", ccsInteger, "", "", $this->Parameters["urls_com_NumComp"], "", false);
        $this->wp->AddParameter("3", "urls_com_RegNumero", ccsInteger, "", "", $this->Parameters["urls_com_RegNumero"], "", false);
        $this->wp->AddParameter("4", "urls_com_FecTrans", ccsDate, Array("dd", "/", "mm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->Parameters["urls_com_FecTrans"], "", false);
        $this->wp->AddParameter("5", "urls_com_FecContab", ccsDate, Array("dd", "/", "mm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->Parameters["urls_com_FecContab"], "", false);
        $this->wp->AddParameter("6", "urls_com_Receptor", ccsText, "", "", $this->Parameters["urls_com_Receptor"], "", false);
        $this->wp->AddParameter("7", "urls_com_Concepto", ccsText, "", "", $this->Parameters["urls_com_Concepto"], "", false);
        $this->wp->AddParameter("8", "urls_com_EstProceso", ccsInteger, "", "", $this->Parameters["urls_com_EstProceso"], "", false);
        $this->wp->AddParameter("9", "urls_com_CodReceptor", ccsText, "", "", $this->Parameters["urls_com_Receptor"], "", false);
        
        $this->wp->AddParameter("10", "urls_com_Contenedor", ccsText, "", "", $this->Parameters["urls_com_Contenedor"], "", false);
        $this->wp->AddParameter("11", "urls_com_Libro", ccsInteger, "", "", $this->Parameters["urls_com_Libro"], "", false);        
        $this->wp->AddParameter("12", "urls_com_Supervisor", ccsText, "", "", $this->Parameters["urls_com_Supervisor"], "", false);
        $this->wp->AddParameter("13", "urls_com_emisor", ccsText, "", "", $this->Parameters["urls_com_emisor"], "", false);
        $this->wp->AddParameter("14", "urls_com_RefOperat", ccsText, "", "", $this->Parameters["urls_com_RefOperat"], "", false);
        $this->wp->AddParameter("15", "urls_com_FecDigita", ccsDate, Array("dd", "/", "mm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->Parameters["urls_com_FecDigita"], "", false);
        
        $this->wp->Criterion[1] = $this->wp->Operation(opContains, "com_TipoComp", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opEqual, "com_NumComp", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsInteger),false);
        $this->wp->Criterion[3] = $this->wp->Operation(opEqual, "com_RegNumero", $this->wp->GetDBValue("3"), $this->ToSQL($this->wp->GetDBValue("3"), ccsInteger),false);
        $this->wp->Criterion[4] = $this->wp->Operation(opEqual, "com_FecTrans", $this->wp->GetDBValue("4"), $this->ToSQL($this->wp->GetDBValue("4"), ccsDate),false);
        $this->wp->Criterion[5] = $this->wp->Operation(opEqual, "com_FecContab", $this->wp->GetDBValue("5"), $this->ToSQL($this->wp->GetDBValue("5"), ccsDate),false);
        $this->wp->Criterion[6] = $this->wp->Operation(opContains, "com_Receptor", $this->wp->GetDBValue("6"), $this->ToSQL($this->wp->GetDBValue("6"), ccsText),false);
        $this->wp->Criterion[7] = $this->wp->Operation(opContains, "com_Concepto", $this->wp->GetDBValue("7"), $this->ToSQL($this->wp->GetDBValue("7"), ccsText),false);
        $this->wp->Criterion[8] = $this->wp->Operation(opEqual, "com_EstProceso", $this->wp->GetDBValue("8"), $this->ToSQL($this->wp->GetDBValue("8"), ccsInteger),false);+
        $this->wp->Criterion[9] = $this->wp->Operation(opBeginsWith, "com_CodReceptor", $this->wp->GetDBValue("9"), $this->ToSQL($this->wp->GetDBValue("9"), ccsText),false);
        $this->wp->Criterion[10] = $this->wp->Operation(opEqual, "com_Contenedor", $this->wp->GetDBValue("10"), $this->ToSQL($this->wp->GetDBValue("10"), ccsText),false);
        $this->wp->Criterion[11] = $this->wp->Operation(opEqual, "com_Libro", $this->wp->GetDBValue("11"), $this->ToSQL($this->wp->GetDBValue("11"), ccsText),false);
        //$this->wp->Criterion[12] = $this->wp->Operation(opContains, "conpersonas.per_Nombres", $this->wp->GetDBValue("12"), $this->ToSQL($this->wp->GetDBValue("12"), ccsText),false);
        $this->wp->Criterion[12] = $this->wp->Operation(opEqual, "com_Supervisor", $this->wp->GetDBValue("12"), $this->ToSQL($this->wp->GetDBValue("12"), ccsText),false);
        $this->wp->Criterion[13] = $this->wp->Operation(opContains, "conpersonas.per_Apellidos", $this->wp->GetDBValue("12"), $this->ToSQL($this->wp->GetDBValue("12"), ccsText),false);
        $this->wp->Criterion[14] = $this->wp->Operation(opContains, "emisor.per_Nombres", $this->wp->GetDBValue("13"), $this->ToSQL($this->wp->GetDBValue("13"), ccsText),false);
        $this->wp->Criterion[15] = $this->wp->Operation(opContains, "emisor.per_Apellidos", $this->wp->GetDBValue("13"), $this->ToSQL($this->wp->GetDBValue("13"), ccsText),false);
        $this->wp->Criterion[16] = $this->wp->Operation(opEqual, "com_RefOperat", $this->wp->GetDBValue("14"), $this->ToSQL($this->wp->GetDBValue("14"), ccsText),false);
        $this->wp->Criterion[17] = $this->wp->Operation(opEqual, "com_FecDigita", $this->wp->GetDBValue("15"), $this->ToSQL($this->wp->GetDBValue("15"), ccsDate),false);
       // var_dump( $this->wp->Criterion[15]);
        $this->Where = $this->wp->opAND(false,
                       $this->wp->opAND(false,
                       $this->wp->opAND(false,
                       $this->wp->opAND(false,
                       $this->wp->opAND(false,
                       $this->wp->opAND(false,
                       $this->wp->opAND(false,
                       $this->wp->opAND(false,
                       $this->wp->opAND(false,
                       $this->wp->opAND(false,
                       $this->wp->opAND(false, 
                       $this->wp->opAND(false, 
                       //$this->wp->opOR(true,
                       $this->wp->opOR(false,                       
                       $this->wp->opOR(true,                       
                       
                       $this->wp->Criterion[14],
                       $this->wp->Criterion[15]),
                       $this->wp->Criterion[12],
                       $this->wp->Criterion[13]),
                       $this->wp->Criterion[1]),
                       $this->wp->Criterion[2]),
                       $this->wp->Criterion[3]),
                       $this->wp->Criterion[4]),
                       $this->wp->Criterion[5]),
                       $this->wp->Criterion[17]),
                       $this->wp->Criterion[6]),
                       $this->wp->Criterion[7]),
                       $this->wp->Criterion[8]),
                       //$this->wp->Criterion[9]),
                       $this->wp->Criterion[10]),
                       $this->wp->Criterion[11]),
                       $this->wp->Criterion[16]);
  
// $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false,  $this->wp->opAND(false,  $this->wp->Criterion[2]), $this->wp->Criterion[3]), $this->wp->Criterion[4]), $this->wp->Criterion[5]), $this->wp->Criterion[10]), $this->wp->Criterion[11]), $this->wp->opAND(false, $this->wp->opOR(true, $this->wp->Criterion[6], $this->wp->Criterion[9]),$this->wp->opAND(false, $this->wp->opOR(true, $this->wp->Criterion[12], $this->wp->Criterion[13]),$this->wp->opOR(true, $this->wp->Criterion[14]))))        
// var_dump($this->Where) ; exit;
        
    }
//End Prepare Method

//Open Method @2-F38CCB9A
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM concomprobantes LEFT JOIN conpersonas ON concomprobantes.`com_Supervisor` = conpersonas.`per_CodAuxiliar`"
                . "LEFT JOIN conpersonas emisor ON concomprobantes.`com_Emisor` = emisor.`per_CodAuxiliar`";
        $this->SQL = "SELECT concomprobantes.*, concat(left(com_TipoComp,3), \" \", com_Numcomp) AS com_Comproba, concat_ws(\" - \", com_Usuario, DATE_FORMAT(com_FecDigita, '%d/%b/%Y')) AS com_Usuario  " .
        "FROM concomprobantes 
LEFT JOIN conpersonas ON concomprobantes.`com_Supervisor` = conpersonas.`per_CodAuxiliar`
LEFT JOIN conpersonas emisor ON concomprobantes.`com_Emisor` = emisor.`per_CodAuxiliar`";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
/* fah */
            switch(fGetParam("pMod", "CoTrTr")){
                case "IN":
                    $lsModulo = "InTrTr";
                    break;
                case "CO":
                    $lsModulo = "CoTrTr";
                    break;
                default:
                    $lsModulo = "CoTrTr";
                    break;
            }
        $slRestr = $_SESSION['restr'][$lsModulo]['ACC'];  //#fahJun/10/09 Restriccion de Emisor
        if (strlen($this->Where) > 1){
            $this->Where .= (strlen($slRestr) >= 1 ) ? " AND " : "";  // #fahJun/10/09
        }
        $this->Where .=  (strlen($slRestr) >= 1 ) ? " com_emisor in ( " . $_SESSION['restr'][$lsModulo]['ACC'] . ") " : "" ;
        
        $slRestr = $_SESSION['restr'][$lsModulo]['TIP'];  //#fahJun/10/09 Restriccion de Tipo de Doc
        if (strlen($this->Where) > 1 && strlen($slRestr) >= 1) $this->Where .= " AND ";
            $this->Where .=  (strlen($slRestr) >= 1 ) ? " com_tipocomp in ( " . $_SESSION['restr'][$lsModulo]['TIP'] . ") " : "" ;        
        if (fGetParam("pAppDbg", "0") == 1) {
            echo "<br> <br> <br> SESION:";print_r($_SESSION);
            echo "<br> SESION-Restr:";print_r($_SESSION["restr"]);
            echo "<br> SESION-Restr- $lsModulo :";print_r($_SESSION["restr"][$lsModulo]);
            echo "<br> SESION-Restr-  $lsModulo - ACC:";print_r($_SESSION["restr"][$lsModulo]["ACC"]);
            echo "<br> SESION-Restr-  $lsModulo - Tip:";print_r($_SESSION["restr"][$lsModulo]["TIP"]);
            echo "<br> Restr:". $slRestr ;
            echo "<br>Modulo:". $lsModulo . " / " ;
            echo "<br>". $InTrTr_comp->com_Emisor->ds->SQL ;
               echo "<br><br>" . $this->Where;
        }
        $this->Order = $this->Order ? $this->Order : "com_FecContab  desc";
//echo "<br><br><br>" .$this->SQL . ' WHERE ' . $this->Where;        
//error_log ($this->SQL . ' WHERE ' . $this->Where, 3, "c:/tmp/my-errors.log");
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @2-1F64A47F
    function SetValues()
    {
        $this->hTipoComp->SetDBValue($this->f("com_TipoComp"));
        $this->hNumComp->SetDBValue(trim($this->f("com_NumComp")));
        $this->com_Comproba->SetDBValue($this->f("com_Comproba"));
        $this->com_FecTrans->SetDBValue(trim($this->f("com_FecTrans")));
        $this->com_FecContab->SetDBValue(trim($this->f("com_FecContab")));
        $this->com_Concepto->SetDBValue($this->f("com_Concepto"));
        $this->com_Usuario->SetDBValue($this->f("com_Usuario"));
        $this->com_EstProceso->SetDBValue($this->f("com_EstProceso"));
        $this->com_RefOperat->SetDBValue(trim($this->f("com_RefOperat")));
    }
//End SetValues Method

} //End InTrTr_listDataSource Class @2-FCB6E20C





//Initialize Page @1-7E4C149F
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

$FileName = "InTrTr.php";
$Redirect = "";
$TemplateFileName = "InTrTr.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-191C84BE
$DBdatos = new clsDBdatos();
// Controls
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
$InTrTr_qry = new clsRecordInTrTr_qry();
$InTrTr_list = new clsGridInTrTr_list();
$InTrTr_list->Initialize();

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

//Execute Components @1-36D8B68F
$Cabecera->Operations();
$InTrTr_qry->Operation();
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

//Show Page @1-BFD592E7
$Cabecera->Show("Cabecera");
$InTrTr_qry->Show();
$InTrTr_list->Show();
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
