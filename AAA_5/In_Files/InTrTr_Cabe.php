<?php
/*
 *      @rev    fah 12/02/09    Presentar correctamente el nombre de bodega en transacciones de inventario,
 *                              el nombre de campo de ta ltabla es com_emisor en lugar de com_Emisor, se aplica un alias
 *                              en la instruccion sql.
 **/
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @99-49BEB8FC
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation

class clsGridInTrTr_topline { //InTrTr_topline class @220-6D93EAB3

//Variables @220-0B3A0FB0

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
//End Variables

//Class_Initialize Event @220-1FA6EF35
    function clsGridInTrTr_topline()
    {
        global $FileName;
        $this->ComponentName = "InTrTr_topline";
        $this->Visible = True;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid InTrTr_topline";
        $this->PageSize = 20;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));

        $this->lbTituloComp = new clsControl(ccsLabel, "lbTituloComp", "lbTituloComp", ccsText, "", CCGetRequestParam("lbTituloComp", ccsGet));
        $this->pTipoComp = new clsControl(ccsListBox, "pTipoComp", "pTipoComp", ccsText, "", CCGetRequestParam("pTipoComp", ccsGet));
        $this->pTipoComp->DSType = dsSQL;
        list($this->pTipoComp->BoundColumn, $this->pTipoComp->TextColumn, $this->pTipoComp->DBFormat) = array("tipo", "descr", "");
        $this->pTipoComp->ds = new clsDBdatos();
        $this->pTipoComp->ds->SQL = "SELECT cla_tipocomp as tipo,  " .
        "       concat(cla_tipocomp , \"   - \", cla_descripcion) as descr " .
        "FROM genclasetran WHERE cla_Indicador = 1 ";
        $slAplic = "";
        $slAplic = CCGetRequestParam("pAplic", ccsGet);
        if (strlen($slAplic)>1) $this->pTipoComp->ds->SQL .= " AND  cla_aplicacion = '$slAplic'";
        $this->btCrear = new clsButton("btCrear");
    }
//End Class_Initialize Event

//Initialize Method @220-5D060BAC
    function Initialize()
    {
        if(!$this->Visible) return;
    }
//End Initialize Method

//Show Method @220-10B73321
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;


        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->pTipoComp->Prepare();

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
        if(!$this->Visible) return;

        $GridBlock = "Grid " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $GridBlock;

//      $Tpl->parse("NoRecords", false);                                    //fah

        $errors = $this->GetErrors();
        if(strlen($errors))
        {
            $Tpl->replaceblock("", $errors);
            $Tpl->block_path = $ParentPath;
            return;
        }
        $this->lbTituloComp->Show();
        $this->pTipoComp->Show();
        $this->btCrear->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

//GetErrors Method @220-3BDFC8DE
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End InTrTr_topline Class @220-FCB6E20C



Class clsRecordInTrTr_comp { //InTrTr_comp Class @70-D7F4399E

//Variables @70-4A82E0A3

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

    var $InsertAllowed;
    var $UpdateAllowed;
    var $DeleteAllowed;
    var $ds;
    var $EditMode;
    var $ValidatingControls;
    var $Controls;

    // Class variables
//End Variables

//Class_Initialize Event @70-7B995C26
    function clsRecordInTrTr_comp()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record InTrTr_comp/Error";
        $this->ds = new clsInTrTr_compDataSource();
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "InTrTr_comp";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->com_TipoComp = new clsControl(ccsTextBox, "com_TipoComp", "Tipo de Comprobante", ccsText, "", CCGetRequestParam("com_TipoComp", $Method));
            $this->com_NumComp = new clsControl(ccsTextBox, "com_NumComp", "Número de Comprobante", ccsInteger, "", CCGetRequestParam("com_NumComp", $Method));
            $this->ro_NumComp = new clsControl(ccsTextBox, "ro_NumComp", "ro_NumComp", ccsInteger, "", CCGetRequestParam("ro_NumComp", $Method));
            $this->hdFormulario = new clsControl(ccsHidden, "hdFormulario", "hdFormulario", ccsText, "", CCGetRequestParam("hdFormulario", $Method));
            $this->hdInforme = new clsControl(ccsHidden, "hdInforme", "hdInforme", ccsText, "", CCGetRequestParam("hdInforme", $Method));
            $this->hdRegNumero = new clsControl(ccsHidden, "hdRegNumero", "hdRegNumero", ccsText, "", CCGetRequestParam("hdRegNumero", $Method));
            $this->hd_NumComp = new clsControl(ccsHidden, "hd_NumComp", "hd_NumComp", ccsText, "", CCGetRequestParam("hd_NumComp", $Method));
            $this->com_FecTrans = new clsControl(ccsTextBox, "com_FecTrans", "Com Fec Trans", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("com_FecTrans", $Method));
            $this->com_FecTrans->Required = true;
            $this->hTipoEmisor = new clsControl(ccsHidden, "hTipoEmisor", "hTipoEmisor", ccsInteger, "", CCGetRequestParam("hTipoEmisor", $Method));
            $this->hTipoReceptor = new clsControl(ccsHidden, "hTipoReceptor", "hTipoReceptor", ccsInteger, "", CCGetRequestParam("hTipoReceptor", $Method));
            $this->com_FecContab = new clsControl(ccsTextBox, "com_FecContab", "Com Fec Contab", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("com_FecContab", $Method));
            $this->com_FecContab->Required = true;
            $this->com_FecVencim = new clsControl(ccsTextBox, "com_FecVencim", "Fecha de Vencimiento", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("com_FecVencim", $Method));
            $this->com_FecVencim->Required = true;
            $this->com_CodMoneda = new clsControl(ccsListBox, "com_CodMoneda", "Codigo de Moneda", ccsInteger, "", CCGetRequestParam("com_CodMoneda", $Method));
            $this->com_CodMoneda->DSType = dsSQL;
            list($this->com_CodMoneda->BoundColumn, $this->com_CodMoneda->TextColumn, $this->com_CodMoneda->DBFormat) = array("mon_Codigo", "mon_Abreviatura", "");
            $this->com_CodMoneda->ds = new clsDBdatos();
            $this->com_CodMoneda->ds->SQL = "SELECT mon_Codigo, concat(mon_Abreviatura , \"  - \", mon_descripcion) as mon_Abreviatura " .
            "FROM genmonedas " .
            "";
            $this->com_CodMoneda->ds->Order = "2";
            $this->com_CodMoneda->Required = true;
            $this->com_TipoCambio = new clsControl(ccsTextBox, "com_TipoCambio", "Com Tipo Cambio", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), CCGetRequestParam("com_TipoCambio", $Method));
            $this->com_TipoCambio->Required = true;
            $this->lbEmisor = new clsControl(ccsLabel, "lbEmisor", "lbEmisor", ccsText, "", CCGetRequestParam("lbEmisor", $Method));
            $this->com_Emisor = new clsControl(ccsListBox, "com_Emisor", "Com Emisor", ccsInteger, "", CCGetRequestParam("com_Emisor", $Method));
            $this->com_Emisor->DSType = dsSQL;
            list($this->com_Emisor->BoundColumn, $this->com_Emisor->TextColumn, $this->com_Emisor->DBFormat) = array("cod", "nomb", "");
            $this->com_Emisor->ds = new clsDBdatos();
            $this->com_Emisor->ds->SQL = "SELECT act_codauxiliar as cod, concat(act_descripcion, \" \", act_descripcion1) as nomb " .
            "FROM conactivos INNER JOIN concategorias on concategorias.cat_codauxiliar = conactivos.act_codauxiliar " .
            "UNION " .
            "SELECT per_codauxiliar as cod , concat(per_Nombres, \" \", per_apellidos) as nomb " .
            "FROM conpersonas INNER JOIN concategorias on conpersonas.per_codauxiliar = concategorias.cat_codauxiliar " .
            "";
            $this->com_Emisor->ds->Order = "NOMB";
            $this->lbReceptor = new clsControl(ccsLabel, "lbReceptor", "lbReceptor", ccsText, "", CCGetRequestParam("lbReceptor", $Method));
            $this->txtReceptor = new clsControl(ccsTextBox, "txtReceptor", "txtReceptor", ccsText, "", CCGetRequestParam("txtReceptor", $Method));
            $this->com_Receptor = new clsControl(ccsTextBox, "com_Receptor", "Nombre de Receptor / Beneficiario", ccsText, "", CCGetRequestParam("com_Receptor", $Method));
            $this->com_CodReceptor = new clsControl(ccsTextBox, "com_CodReceptor", "Código del Receptor", ccsText, "", CCGetRequestParam("com_CodReceptor", $Method));
            $this->com_Valor = new clsControl(ccsTextBox, "com_Valor", "Valor de Transacción", ccsText, "", CCGetRequestParam("com_Valor", $Method));
            $this->com_Valor->Required = true;
            $this->com_Libro = new clsControl(ccsListBox, "com_Libro", "Libro Contable", ccsInteger, "", CCGetRequestParam("com_Libro", $Method));
            $this->com_Libro->DSType = dsTable;
            list($this->com_Libro->BoundColumn, $this->com_Libro->TextColumn, $this->com_Libro->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->com_Libro->ds = new clsDBdatos();
            $this->com_Libro->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->com_Libro->ds->Parameters["expr88"] = 'CLIBRO';
            $this->com_Libro->ds->wp = new clsSQLParameters();
            $this->com_Libro->ds->wp->AddParameter("1", "expr88", ccsText, "", "", $this->com_Libro->ds->Parameters["expr88"], "", false);
            $this->com_Libro->ds->wp->Criterion[1] = $this->com_Libro->ds->wp->Operation(opEqual, "par_Clave", $this->com_Libro->ds->wp->GetDBValue("1"), $this->com_Libro->ds->ToSQL($this->com_Libro->ds->wp->GetDBValue("1"), ccsText),false);
            $this->com_Libro->ds->Where = $this->com_Libro->ds->wp->Criterion[1];
            $this->com_Libro->Required = true;
            $this->com_Concepto = new clsControl(ccsTextArea, "com_Concepto", "Com Concepto", ccsMemo, "", CCGetRequestParam("com_Concepto", $Method));
            $this->com_NumRetenc = new clsControl(ccsTextBox, "com_NumRetenc", "Com Num Retenc", ccsInteger, "", CCGetRequestParam("com_NumRetenc", $Method));
            $this->com_NumRetenc->Required = true;
            $this->com_RefOperat = new clsControl(ccsTextBox, "com_RefOperat", "Com Ref Operat", ccsInteger, "", CCGetRequestParam("com_RefOperat", $Method));
            $this->com_RefOperat->Required = true;
            $this->com_EstProceso = new clsControl(ccsListBox, "com_EstProceso", "Estado de Proceso Contable", ccsInteger, "", CCGetRequestParam("com_EstProceso", $Method));
            $this->com_EstProceso->DSType = dsTable;
            list($this->com_EstProceso->BoundColumn, $this->com_EstProceso->TextColumn, $this->com_EstProceso->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->com_EstProceso->ds = new clsDBdatos();
            $this->com_EstProceso->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->com_EstProceso->ds->Parameters["expr116"] = 'CADEST';
            $this->com_EstProceso->ds->wp = new clsSQLParameters();
            $this->com_EstProceso->ds->wp->AddParameter("1", "expr116", ccsText, "", "", $this->com_EstProceso->ds->Parameters["expr116"], "", true);
            $this->com_EstProceso->ds->wp->Criterion[1] = $this->com_EstProceso->ds->wp->Operation(opEqual, "par_Clave", $this->com_EstProceso->ds->wp->GetDBValue("1"), $this->com_EstProceso->ds->ToSQL($this->com_EstProceso->ds->wp->GetDBValue("1"), ccsText),true);
            $this->com_EstProceso->ds->Where = $this->com_EstProceso->ds->wp->Criterion[1];
            $this->com_EstOperacion = new clsControl(ccsListBox, "com_EstOperacion", "Estado de Operativo", ccsInteger, "", CCGetRequestParam("com_EstOperacion", $Method));
            $this->com_EstOperacion->DSType = dsTable;
            list($this->com_EstOperacion->BoundColumn, $this->com_EstOperacion->TextColumn, $this->com_EstOperacion->DBFormat) = array("par_Secuencia", "par_Descripcion", "");
            $this->com_EstOperacion->ds = new clsDBdatos();
            $this->com_EstOperacion->ds->SQL = "SELECT *  " .
"FROM genparametros";
            $this->com_EstOperacion->ds->Parameters["expr110"] = 'CADEST';
            $this->com_EstOperacion->ds->wp = new clsSQLParameters();
            $this->com_EstOperacion->ds->wp->AddParameter("1", "expr110", ccsText, "", "", $this->com_EstOperacion->ds->Parameters["expr110"], "", false);
            $this->com_EstOperacion->ds->wp->Criterion[1] = $this->com_EstOperacion->ds->wp->Operation(opEqual, "par_Clave", $this->com_EstOperacion->ds->wp->GetDBValue("1"), $this->com_EstOperacion->ds->ToSQL($this->com_EstOperacion->ds->wp->GetDBValue("1"), ccsText),false);
            $this->com_EstOperacion->ds->Where = $this->com_EstOperacion->ds->wp->Criterion[1];
            $this->com_NumProceso = new clsControl(ccsTextBox, "com_NumProceso", "com_NumProceso", ccsText, "", CCGetRequestParam("com_NumProceso", $Method));
            $this->com_NumPeriodo = new clsControl(ccsTextBox, "com_NumPeriodo", "Com Num Proceso", ccsInteger, "", CCGetRequestParam("com_NumPeriodo", $Method));
            $this->com_NumPeriodo->Required = true;
            $this->com_Usuario = new clsControl(ccsTextBox, "com_Usuario", "Nombre de Usuario", ccsText, "", CCGetRequestParam("com_Usuario", $Method));
            $this->com_Usuario->Required = true;
            $this->com_FecDigita = new clsControl(ccsTextBox, "com_FecDigita", "Com Fec Digita", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("com_FecDigita", $Method));
            $this->com_FecDigita->Required = true;
            $this->Button_Insert = new clsButton("Button_Insert");
            $this->Button_Update = new clsButton("Button_Update");
            $this->Button_Delete = new clsButton("Button_Delete");
            $this->btDetalle = new clsButton("btDetalle");
            $this->btBusqueda = new clsButton("btBusqueda");
            $this->btNuevo = new clsButton("btNuevo");
            if(!$this->FormSubmitted) {
                if(!is_array($this->com_NumComp->Value) && !strlen($this->com_NumComp->Value) && $this->com_NumComp->Value !== false)
                $this->com_NumComp->SetText(0);
                if(!is_array($this->com_FecTrans->Value) && !strlen($this->com_FecTrans->Value) && $this->com_FecTrans->Value !== false)
                $this->com_FecTrans->SetValue(time());
                if(!is_array($this->com_FecContab->Value) && !strlen($this->com_FecContab->Value) && $this->com_FecContab->Value !== false)
                $this->com_FecContab->SetValue(time());
                if(!is_array($this->com_FecVencim->Value) && !strlen($this->com_FecVencim->Value) && $this->com_FecVencim->Value !== false)
                $this->com_FecVencim->SetValue(time());
                if(!is_array($this->com_CodMoneda->Value) && !strlen($this->com_CodMoneda->Value) && $this->com_CodMoneda->Value !== false)
                $this->com_CodMoneda->SetText(593);
                if(!is_array($this->com_TipoCambio->Value) && !strlen($this->com_TipoCambio->Value) && $this->com_TipoCambio->Value !== false)
                $this->com_TipoCambio->SetText(1);
                if(!is_array($this->com_Emisor->Value) && !strlen($this->com_Emisor->Value) && $this->com_Emisor->Value !== false)
                $this->com_Emisor->SetText(0);
                if(!is_array($this->com_Valor->Value) && !strlen($this->com_Valor->Value) && $this->com_Valor->Value !== false)
                $this->com_Valor->SetText(0);
                if(!is_array($this->com_Libro->Value) && !strlen($this->com_Libro->Value) && $this->com_Libro->Value !== false)
                $this->com_Libro->SetText(9999);
                if(!is_array($this->com_NumRetenc->Value) && !strlen($this->com_NumRetenc->Value) && $this->com_NumRetenc->Value !== false)
                $this->com_NumRetenc->SetText(0);
                if(!is_array($this->com_RefOperat->Value) && !strlen($this->com_RefOperat->Value) && $this->com_RefOperat->Value !== false)
                $this->com_RefOperat->SetText(0);
                if(!is_array($this->com_EstProceso->Value) && !strlen($this->com_EstProceso->Value) && $this->com_EstProceso->Value !== false)
                $this->com_EstProceso->SetText(-1);
                if(!is_array($this->com_EstOperacion->Value) && !strlen($this->com_EstOperacion->Value) && $this->com_EstOperacion->Value !== false)
                $this->com_EstOperacion->SetText(-1);
                if(!is_array($this->com_NumPeriodo->Value) && !strlen($this->com_NumPeriodo->Value) && $this->com_NumPeriodo->Value !== false)
                $this->com_NumPeriodo->SetText(0);
                if(!is_array($this->com_FecDigita->Value) && !strlen($this->com_FecDigita->Value) && $this->com_FecDigita->Value !== false)
                $this->com_FecDigita->SetValue(time());
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @70-5B3E6895
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->ds->Parameters["urlcom_RegNumero"] = CCGetFromGet("com_RegNumero", "");
    }
//End Initialize Method

//Validate Method @70-2C462403
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->com_TipoComp->Validate() && $Validation);
        $Validation = ($this->com_NumComp->Validate() && $Validation);
        $Validation = ($this->ro_NumComp->Validate() && $Validation);
        $Validation = ($this->hdFormulario->Validate() && $Validation);
        $Validation = ($this->hdInforme->Validate() && $Validation);
        $Validation = ($this->hdRegNumero->Validate() && $Validation);
        $Validation = ($this->hd_NumComp->Validate() && $Validation);
        $Validation = ($this->com_FecTrans->Validate() && $Validation);
        $Validation = ($this->hTipoEmisor->Validate() && $Validation);
        $Validation = ($this->hTipoReceptor->Validate() && $Validation);
        $Validation = ($this->com_FecContab->Validate() && $Validation);
        $Validation = ($this->com_FecVencim->Validate() && $Validation);
        $Validation = ($this->com_CodMoneda->Validate() && $Validation);
        $Validation = ($this->com_TipoCambio->Validate() && $Validation);
        $Validation = ($this->com_Emisor->Validate() && $Validation);
        $Validation = ($this->txtReceptor->Validate() && $Validation);
        $Validation = ($this->com_Receptor->Validate() && $Validation);
        $Validation = ($this->com_CodReceptor->Validate() && $Validation);
        $Validation = ($this->com_Valor->Validate() && $Validation);
        $Validation = ($this->com_Libro->Validate() && $Validation);
        $Validation = ($this->com_Concepto->Validate() && $Validation);
        $Validation = ($this->com_NumRetenc->Validate() && $Validation);
        $Validation = ($this->com_RefOperat->Validate() && $Validation);
        $Validation = ($this->com_EstProceso->Validate() && $Validation);
        $Validation = ($this->com_EstOperacion->Validate() && $Validation);
        $Validation = ($this->com_NumProceso->Validate() && $Validation);
        $Validation = ($this->com_NumPeriodo->Validate() && $Validation);
        $Validation = ($this->com_Usuario->Validate() && $Validation);
        $Validation = ($this->com_FecDigita->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @70-612C24D1
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->com_TipoComp->Errors->Count());
        $errors = ($errors || $this->com_NumComp->Errors->Count());
        $errors = ($errors || $this->ro_NumComp->Errors->Count());
        $errors = ($errors || $this->hdFormulario->Errors->Count());
        $errors = ($errors || $this->hdInforme->Errors->Count());
        $errors = ($errors || $this->hdRegNumero->Errors->Count());
        $errors = ($errors || $this->hd_NumComp->Errors->Count());
        $errors = ($errors || $this->com_FecTrans->Errors->Count());
        $errors = ($errors || $this->hTipoEmisor->Errors->Count());
        $errors = ($errors || $this->hTipoReceptor->Errors->Count());
        $errors = ($errors || $this->com_FecContab->Errors->Count());
        $errors = ($errors || $this->com_FecVencim->Errors->Count());
        $errors = ($errors || $this->com_CodMoneda->Errors->Count());
        $errors = ($errors || $this->com_TipoCambio->Errors->Count());
        $errors = ($errors || $this->lbEmisor->Errors->Count());
        $errors = ($errors || $this->com_Emisor->Errors->Count());
        $errors = ($errors || $this->lbReceptor->Errors->Count());
        $errors = ($errors || $this->txtReceptor->Errors->Count());
        $errors = ($errors || $this->com_Receptor->Errors->Count());
        $errors = ($errors || $this->com_CodReceptor->Errors->Count());
        $errors = ($errors || $this->com_Valor->Errors->Count());
        $errors = ($errors || $this->com_Libro->Errors->Count());
        $errors = ($errors || $this->com_Concepto->Errors->Count());
        $errors = ($errors || $this->com_NumRetenc->Errors->Count());
        $errors = ($errors || $this->com_RefOperat->Errors->Count());
        $errors = ($errors || $this->com_EstProceso->Errors->Count());
        $errors = ($errors || $this->com_EstOperacion->Errors->Count());
        $errors = ($errors || $this->com_NumProceso->Errors->Count());
        $errors = ($errors || $this->com_NumPeriodo->Errors->Count());
        $errors = ($errors || $this->com_Usuario->Errors->Count());
        $errors = ($errors || $this->com_FecDigita->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @70-9B37560E
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        $this->ds->Prepare();
        $this->EditMode = $this->ds->AllParametersSet;
        if(!$this->FormSubmitted)
            return;

        if($this->FormSubmitted) {
            $this->PressedButton = $this->EditMode ? "Button_Update" : "Button_Insert";
            if(strlen(CCGetParam("Button_Insert", ""))) {
                $this->PressedButton = "Button_Insert";
            } else if(strlen(CCGetParam("Button_Update", ""))) {
                $this->PressedButton = "Button_Update";
            } else if(strlen(CCGetParam("Button_Delete", ""))) {
                $this->PressedButton = "Button_Delete";
            } else if(strlen(CCGetParam("btDetalle", ""))) {
                $this->PressedButton = "btDetalle";
            } else if(strlen(CCGetParam("btBusqueda", ""))) {
                $this->PressedButton = "btBusqueda";
            } else if(strlen(CCGetParam("btNuevo", ""))) {
                $this->PressedButton = "btNuevo";
            }
        }
        $Redirect = $FileName . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "Button_Delete") {
            if(!CCGetEvent($this->Button_Delete->CCSEvents, "OnClick") || !$this->DeleteRow()) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "btDetalle") {
            if(!CCGetEvent($this->btDetalle->CCSEvents, "OnClick")) {
                $Redirect = "";
            } else {
                $Redirect = $FileName . "?" . CCGetQueryString("QueryString", Array("ccsForm", "ccsForm"));
            }
        } else if($this->PressedButton == "btBusqueda") {
            if(!CCGetEvent($this->btBusqueda->CCSEvents, "OnClick")) {
                $Redirect = "";
            } else {
                $Redirect = "InTrTr.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm", "com_RegNumero", " com_TipoComp"));
            }
        } else if($this->PressedButton == "btNuevo") {
            if(!CCGetEvent($this->btNuevo->CCSEvents, "OnClick")) {
                $Redirect = "";
            } else {
                $Redirect = $FileName . "?" . CCGetQueryString("QueryString", Array("ccsForm",  "com_RegNumero"));
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

//InsertRow Method @70-02BF5213
    function InsertRow()
    {
    	global $DBdatos;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert");
        if(!$this->InsertAllowed) return false;
        $this->ds->com_TipoComp->SetValue($this->com_TipoComp->GetValue());
        $this->ds->com_NumComp->SetValue($this->com_NumComp->GetValue());
        $this->ds->com_FecTrans->SetValue($this->com_FecTrans->GetValue());
        $this->ds->com_FecContab->SetValue($this->com_FecContab->GetValue());
        $this->ds->com_FecVencim->SetValue($this->com_FecVencim->GetValue());
        $this->ds->com_CodMoneda->SetValue($this->com_CodMoneda->GetValue());
        $this->ds->com_TipoCambio->SetValue($this->com_TipoCambio->GetValue());
        $this->ds->com_Valor->SetValue($this->com_Valor->GetValue());
        $this->ds->com_Emisor->SetValue($this->com_Emisor->GetValue());
        $this->ds->com_CodReceptor->SetValue($this->com_CodReceptor->GetValue());
        $this->ds->com_Receptor->SetValue($this->com_Receptor->GetValue());
        $this->ds->com_Concepto->SetValue($this->com_Concepto->GetValue());
        $this->ds->com_Libro->SetValue($this->com_Libro->GetValue());
        $this->ds->com_NumRetenc->SetValue($this->com_NumRetenc->GetValue());
        $this->ds->com_RefOperat->SetValue($this->com_RefOperat->GetValue());
        $this->ds->com_EstProceso->SetValue($this->com_EstProceso->GetValue());
        $this->ds->com_EstOperacion->SetValue($this->com_EstOperacion->GetValue());
        $this->ds->com_NumProceso->SetValue($this->com_NumProceso->GetValue());
        $this->ds->com_Usuario->SetValue($this->com_Usuario->GetValue());
        $this->ds->com_FecDigita->SetValue($this->com_FecDigita->GetValue());
        $this->ds->com_NumPeriodo->SetValue($this->com_NumPeriodo->GetValue());
        $this->ds->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert");
        if($this->ds->Errors->Count() > 0) {
            echo "Error in Record " . $this->ComponentName . " / Insert Operation";
            $this->ds->Errors->Clear();
            $this->Errors->AddError("Database command error.");
        }
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @70-6A2BF9A3
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate");
        if(!$this->UpdateAllowed) return false;
        $this->ds->com_FecTrans->SetValue($this->com_FecTrans->GetValue());
        $this->ds->com_FecContab->SetValue($this->com_FecContab->GetValue());
        $this->ds->com_FecVencim->SetValue($this->com_FecVencim->GetValue());
        $this->ds->com_CodMoneda->SetValue($this->com_CodMoneda->GetValue());
        $this->ds->com_TipoCambio->SetValue($this->com_TipoCambio->GetValue());
        $this->ds->com_Valor->SetValue($this->com_Valor->GetValue());
        $this->ds->com_Emisor->SetValue($this->com_Emisor->GetValue());
        $this->ds->com_CodReceptor->SetValue($this->com_CodReceptor->GetValue());
        $this->ds->com_Receptor->SetValue($this->com_Receptor->GetValue());
        $this->ds->com_Concepto->SetValue($this->com_Concepto->GetValue());
        $this->ds->com_Libro->SetValue($this->com_Libro->GetValue());
        $this->ds->com_NumRetenc->SetValue($this->com_NumRetenc->GetValue());
        $this->ds->com_RefOperat->SetValue($this->com_RefOperat->GetValue());
        $this->ds->com_EstProceso->SetValue($this->com_EstProceso->GetValue());
        $this->ds->com_EstOperacion->SetValue($this->com_EstOperacion->GetValue());
        $this->ds->com_NumProceso->SetValue($this->com_NumProceso->GetValue());
        $this->ds->com_Usuario->SetValue($this->com_Usuario->GetValue());
        $this->ds->com_FecDigita->SetValue($this->com_FecDigita->GetValue());
        $this->ds->com_NumPeriodo->SetValue($this->com_NumPeriodo->GetValue());
        $this->ds->Update();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate");
        if($this->ds->Errors->Count() > 0) {
            echo "Error in Record " . $this->ComponentName . " / Update Operation";
            $this->ds->Errors->Clear();
            $this->Errors->AddError("Database command error.");
        }
        return (!$this->CheckErrors());
    }
//End UpdateRow Method

//DeleteRow Method @70-EA88835F
    function DeleteRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeDelete");
        if(!$this->DeleteAllowed) return false;
        if (CCGetFromGet("com_RegNumero", 0) == 0) $this->Errors->AddError("NO PUEDE ELIMINAR UN COMPROBANTE MIENTRAS LO INGRESA" );   // fah
        else {
            $this->ds->Delete();
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterDelete");
            if($this->ds->Errors->Count() > 0) {
                echo "Error in Record " . $this->ComponentName . " / Delete Operation";
                $this->ds->Errors->Clear();
                $this->Errors->AddError("Database command error.");
            }
        }
        return (!$this->CheckErrors());
    }
//End DeleteRow Method

//Show Method @70-2E9C2339
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->com_CodMoneda->Prepare();
        $this->com_Emisor->Prepare();
        $this->com_Libro->Prepare();
        $this->com_EstProceso->Prepare();
        $this->com_EstOperacion->Prepare();

        $this->ds->open();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        if($this->EditMode)
        {
            if($this->Errors->Count() == 0)
            {
                if($this->ds->Errors->Count() > 0)
                {
                    echo "Error in Record InTrTr_comp";
                }
                else if($this->ds->next_record())
                {
                    $this->ds->SetValues();
                    if(!$this->FormSubmitted)
                    {
                        $this->com_TipoComp->SetValue($this->ds->com_TipoComp->GetValue());
                        $this->com_NumComp->SetValue($this->ds->com_NumComp->GetValue());
                        $this->ro_NumComp->SetValue($this->ds->ro_NumComp->GetValue());
                        $this->hdRegNumero->SetValue($this->ds->hdRegNumero->GetValue());
                        $this->hd_NumComp->SetValue($this->ds->hd_NumComp->GetValue());
                        $this->com_FecTrans->SetValue($this->ds->com_FecTrans->GetValue());
                        $this->com_FecContab->SetValue($this->ds->com_FecContab->GetValue());
                        $this->com_FecVencim->SetValue($this->ds->com_FecVencim->GetValue());
                        $this->com_CodMoneda->SetValue($this->ds->com_CodMoneda->GetValue());
                        $this->com_TipoCambio->SetValue($this->ds->com_TipoCambio->GetValue());
                        $this->com_Emisor->SetValue($this->ds->com_Emisor->GetValue());
                        $this->txtReceptor->SetValue($this->ds->txtReceptor->GetValue());
                        $this->com_Receptor->SetValue($this->ds->com_Receptor->GetValue());
                        $this->com_CodReceptor->SetValue($this->ds->com_CodReceptor->GetValue());
                        $this->com_Valor->SetValue($this->ds->com_Valor->GetValue());
                        $this->com_Libro->SetValue($this->ds->com_Libro->GetValue());
                        $this->com_Concepto->SetValue($this->ds->com_Concepto->GetValue());
                        $this->com_NumRetenc->SetValue($this->ds->com_NumRetenc->GetValue());
                        $this->com_RefOperat->SetValue($this->ds->com_RefOperat->GetValue());
                        $this->com_EstProceso->SetValue($this->ds->com_EstProceso->GetValue());
                        $this->com_EstOperacion->SetValue($this->ds->com_EstOperacion->GetValue());
                        $this->com_NumProceso->SetValue($this->ds->com_NumProceso->GetValue());
                        $this->com_NumPeriodo->SetValue($this->ds->com_NumPeriodo->GetValue());
                        $this->com_Usuario->SetValue($this->ds->com_Usuario->GetValue());
                        $this->com_FecDigita->SetValue($this->ds->com_FecDigita->GetValue());
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
            $Error .= $this->com_TipoComp->Errors->ToString();
            $Error .= $this->com_NumComp->Errors->ToString();
            $Error .= $this->ro_NumComp->Errors->ToString();
            $Error .= $this->hdFormulario->Errors->ToString();
            $Error .= $this->hdInforme->Errors->ToString();
            $Error .= $this->hdRegNumero->Errors->ToString();
            $Error .= $this->hd_NumComp->Errors->ToString();
            $Error .= $this->com_FecTrans->Errors->ToString();
            $Error .= $this->hTipoEmisor->Errors->ToString();
            $Error .= $this->hTipoReceptor->Errors->ToString();
            $Error .= $this->com_FecContab->Errors->ToString();
            $Error .= $this->com_FecVencim->Errors->ToString();
            $Error .= $this->com_CodMoneda->Errors->ToString();
            $Error .= $this->com_TipoCambio->Errors->ToString();
            $Error .= $this->lbEmisor->Errors->ToString();
            $Error .= $this->com_Emisor->Errors->ToString();
            $Error .= $this->lbReceptor->Errors->ToString();
            $Error .= $this->txtReceptor->Errors->ToString();
            $Error .= $this->com_Receptor->Errors->ToString();
            $Error .= $this->com_CodReceptor->Errors->ToString();
            $Error .= $this->com_Valor->Errors->ToString();
            $Error .= $this->com_Libro->Errors->ToString();
            $Error .= $this->com_Concepto->Errors->ToString();
            $Error .= $this->com_NumRetenc->Errors->ToString();
            $Error .= $this->com_RefOperat->Errors->ToString();
            $Error .= $this->com_EstProceso->Errors->ToString();
            $Error .= $this->com_EstOperacion->Errors->ToString();
            $Error .= $this->com_NumProceso->Errors->ToString();
            $Error .= $this->com_NumPeriodo->Errors->ToString();
            $Error .= $this->com_Usuario->Errors->ToString();
            $Error .= $this->com_FecDigita->Errors->ToString();
            $Error .= $this->Errors->ToString();
            $Error .= $this->ds->Errors->ToString();
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
        //----------  Modificacion para Habilitar / deshabilitar Botones Segun perfil del usuario
        $aOpc = array();
        global $DBdatos;
        global $goTrans;
        global $Tpl;
        if ($this->InsertAllowed) $aOpc[]="ADD";
        if ($this->InsertAllowed) $aOpc[]="UPD";
        if ($this->InsertAllowed) $aOpc[]="DEL";
//        $aOpc[]="DUP";
        $ilNumPeriodo = $this->com_NumPeriodo->GetValue();
        if (is_null($ilNumPeriodo) || empty($ilNumPeriodo)) $ilNumPeriodo = 0;
        if ($this->EditMode) $ilEstado = CCGetDBValue("SELECT per_Estado FROM conperiodos WHERE per_Aplicacion = 'CO' AND per_Numperiodo = " . $ilNumPeriodo , $DBdatos);
        else $ilEstado = 1;
        //echo "<br> Edit Mode: " . $InTrTr_comp->EditMode . " // " . $InTrTr_comp->com_NumPeriodo->GetValue() . "//" . $ilEstado;
//        fHabilitaCCS($pPagina, 'DUP', $goTrans->f('cla_Duplicable')); // siempre habilitar duplicacicones en funcion del ususario y transacc
        //fHabilitaBotonesCCS(false, $aOpc, $ilEstado );
        //fHabilitaBotonesCCS(false, 'DUP', $goTrans->f('cla_Duplicable') );
        //----------
        echo "ggggggggggggggggggggggggggg";
        $this->Button_Insert->Visible = !$this->EditMode && $this->InsertAllowed;
        $this->Button_Update->Visible = $this->EditMode && $this->UpdateAllowed;
        $this->Button_Delete->Visible = $this->EditMode && $this->DeleteAllowed;
        $this->com_TipoComp->Show();
        $this->com_NumComp->Show();
        $this->ro_NumComp->Show();
        $this->hdFormulario->Show();
        $this->hdInforme->Show();
        $this->hdRegNumero->Show();
        $this->hd_NumComp->Show();
        $this->com_FecTrans->Show();
        $this->hTipoEmisor->Show();
        $this->hTipoReceptor->Show();
        $this->com_FecContab->Show();
        $this->com_FecVencim->Show();
        $this->com_CodMoneda->Show();
        $this->com_TipoCambio->Show();
        $this->lbEmisor->Show();
        $this->com_Emisor->Show();
        $this->lbReceptor->Show();
        $this->txtReceptor->Show();
        $this->com_Receptor->Show();
        $this->com_CodReceptor->Show();
        $this->com_Valor->Show();
        $this->com_Libro->Show();
        $this->com_Concepto->Show();
        $this->com_NumRetenc->Show();
        $this->com_RefOperat->Show();
        $this->com_EstProceso->Show();
        $this->com_EstOperacion->Show();
        $this->com_NumProceso->Show();
        $this->com_NumPeriodo->Show();
        $this->com_Usuario->Show();
        $this->com_FecDigita->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->btDetalle->Show();
        $this->btBusqueda->Show();
        $this->btNuevo->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

} //End InTrTr_comp Class @70-FCB6E20C

class clsInTrTr_compDataSource extends clsDBdatos {  //InTrTr_compDataSource Class @70-861D918F

//DataSource Variables @70-D3D61C8D
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $InsertParameters;
    var $UpdateParameters;
    var $DeleteParameters;
    var $wp;
    var $AllParametersSet;


    // Datasource fields
    var $com_TipoComp;
    var $com_NumComp;
    var $ro_NumComp;
    var $hdFormulario;
    var $hdInforme;
    var $hdRegNumero;
    var $hd_NumComp;
    var $com_FecTrans;
    var $hTipoEmisor;
    var $hTipoReceptor;
    var $com_FecContab;
    var $com_FecVencim;
    var $com_CodMoneda;
    var $com_TipoCambio;
    var $lbEmisor;
    var $com_Emisor;
    var $lbReceptor;
    var $txtReceptor;
    var $com_Receptor;
    var $com_CodReceptor;
    var $com_Valor;
    var $com_Libro;
    var $com_Concepto;
    var $com_NumRetenc;
    var $com_RefOperat;
    var $com_EstProceso;
    var $com_EstOperacion;
    var $com_NumProceso;
    var $com_NumPeriodo;
    var $com_Usuario;
    var $com_FecDigita;
//End DataSource Variables

//Class_Initialize Event @70-B479E5A5
    function clsInTrTr_compDataSource()
    {
        $this->ErrorBlock = "Record InTrTr_comp/Error";
        $this->Initialize();
        $this->com_TipoComp = new clsField("com_TipoComp", ccsText, "");
        $this->com_NumComp = new clsField("com_NumComp", ccsInteger, "");
        $this->ro_NumComp = new clsField("ro_NumComp", ccsInteger, "");
        $this->hdFormulario = new clsField("hdFormulario", ccsText, "");
        $this->hdInforme = new clsField("hdInforme", ccsText, "");
        $this->hdRegNumero = new clsField("hdRegNumero", ccsText, "");
        $this->hd_NumComp = new clsField("hd_NumComp", ccsText, "");
        $this->com_FecTrans = new clsField("com_FecTrans", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->hTipoEmisor = new clsField("hTipoEmisor", ccsInteger, "");
        $this->hTipoReceptor = new clsField("hTipoReceptor", ccsInteger, "");
        $this->com_FecContab = new clsField("com_FecContab", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->com_FecVencim = new clsField("com_FecVencim", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->com_CodMoneda = new clsField("com_CodMoneda", ccsInteger, "");
        $this->com_TipoCambio = new clsField("com_TipoCambio", ccsFloat, "");
        $this->lbEmisor = new clsField("lbEmisor", ccsText, "");
        $this->com_Emisor = new clsField("com_Emisor", ccsInteger, "");
        $this->lbReceptor = new clsField("lbReceptor", ccsText, "");
        $this->txtReceptor = new clsField("txtReceptor", ccsText, "");
        $this->com_Receptor = new clsField("com_Receptor", ccsText, "");
        $this->com_CodReceptor = new clsField("com_CodReceptor", ccsText, "");
        $this->com_Valor = new clsField("com_Valor", ccsText, "");
        $this->com_Libro = new clsField("com_Libro", ccsInteger, "");
        $this->com_Concepto = new clsField("com_Concepto", ccsMemo, "");
        $this->com_NumRetenc = new clsField("com_NumRetenc", ccsInteger, "");
        $this->com_RefOperat = new clsField("com_RefOperat", ccsInteger, "");
        $this->com_EstProceso = new clsField("com_EstProceso", ccsFloat, "");
        $this->com_EstOperacion = new clsField("com_EstOperacion", ccsInteger, "");
        $this->com_NumProceso = new clsField("com_NumProceso", ccsText, "");
        $this->com_NumPeriodo = new clsField("com_NumPeriodo", ccsInteger, "");
        $this->com_Usuario = new clsField("com_Usuario", ccsText, "");
        $this->com_FecDigita = new clsField("com_FecDigita", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));

    }
//End Class_Initialize Event

//Prepare Method @70-8B2C5EED
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlcom_RegNumero", ccsInteger, "", "", $this->Parameters["urlcom_RegNumero"], "", true);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "com_RegNumero", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),true);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @70-23DA9141
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->SQL = "SELECT    com_TipoComp, 
                com_NumComp,    com_RegNumero,      com_FecTrans,       com_FecContab,      com_FecVencim, 
                com_emisor as   com_Emisor,         com_CodReceptor,    com_Receptor,       com_Concepto,
                com_Valor,      com_TipoCambio,     com_Libro,          com_NumRetenc,      com_RefOperat, 
                com_EstProceso, com_EstOperacion,   com_NumProceso,     com_CodMoneda,      com_Usuario, 
                com_NumPeriodo, com_PerOperativo,   com_Vendedor,       com_TsaImpuestos,   com_FecDigita,
                cla_Formulario, cla_Informe,        cla_TipoEmisor,     cla_TipoReceptor,   cla_CodSecuencia,
                conpersonas.*,
                concat_ws(' ',per_Apellidos, per_Nombres) AS txtReceptor  " .
        "FROM (concomprobantes INNER JOIN genclasetran ON concomprobantes.com_TipoComp = genclasetran.cla_tipoComp) LEFT JOIN conpersonas ON concomprobantes.com_CodReceptor = conpersonas.per_CodAuxiliar";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @70-F4D799B9
    function SetValues()
    {
        global $igPeriodo;
        global $DBdatos;
        $this->com_TipoComp->SetDBValue($this->f("com_TipoComp"));
        $this->com_NumComp->SetDBValue(trim($this->f("com_NumComp")));
        $this->ro_NumComp->SetDBValue(trim($this->f("com_NumComp")));
        $this->hdRegNumero->SetDBValue($this->f("com_RegNumero"));
        $this->hd_NumComp->SetDBValue($this->f("com_NumComp"));
        $this->com_FecTrans->SetDBValue(trim($this->f("com_FecTrans")));
        $this->com_FecContab->SetDBValue(trim($this->f("com_FecContab")));
        $this->com_FecVencim->SetDBValue(trim($this->f("com_FecVencim")));
        $this->com_CodMoneda->SetDBValue(trim($this->f("com_CodMoneda")));
        $this->com_TipoCambio->SetDBValue(trim($this->f("com_TipoCambio")));
        $this->com_Emisor->SetDBValue(trim($this->f("com_Emisor")));
        $this->txtReceptor->SetDBValue($this->f("txtReceptor"));
        $this->com_Receptor->SetDBValue($this->f("com_Receptor"));
        $this->com_CodReceptor->SetDBValue($this->f("com_CodReceptor"));
        $this->com_Valor->SetDBValue($this->f("com_Valor"));
        $this->com_Libro->SetDBValue(trim($this->f("com_Libro")));
        $this->com_Concepto->SetDBValue(trim($this->f("com_Concepto")));
        $this->com_NumRetenc->SetDBValue(trim($this->f("com_NumRetenc")));
        $this->com_RefOperat->SetDBValue(trim($this->f("com_RefOperat")));
        $this->com_EstProceso->SetDBValue(trim($this->f("com_EstProceso")));
        $this->com_EstOperacion->SetDBValue(trim($this->f("com_EstOperacion")));
        $this->com_NumProceso->SetDBValue($this->f("com_NumProceso"));
        $this->com_NumPeriodo->SetDBValue(trim($this->f("com_NumPeriodo")));
        $this->com_Usuario->SetDBValue($this->f("com_Usuario"));
        $this->com_FecDigita->SetDBValue(trim($this->f("com_FecDigita")));
        $igPeriodo = $this->f("com_NumPeriodo");
    }
//End SetValues Method

//Insert Method @70-A3776B6C
    function Insert()
    {
    	global $goTrans;
        $this->cp["com_TipoComp"] = new clsSQLParameter("ctrlcom_TipoComp", ccsText, "", "", $this->com_TipoComp->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["com_NumComp"] = new clsSQLParameter("ctrlcom_NumComp", ccsInteger, "", "", $this->com_NumComp->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["com_FecTrans"] = new clsSQLParameter("ctrlcom_FecTrans", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->com_FecTrans->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["com_FecContab"] = new clsSQLParameter("ctrlcom_FecContab", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->com_FecContab->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["com_FecVencim"] = new clsSQLParameter("ctrlcom_FecVencim", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->com_FecVencim->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["com_CodMoneda"] = new clsSQLParameter("ctrlcom_CodMoneda", ccsInteger, "", "", $this->com_CodMoneda->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["com_TipoCambio"] = new clsSQLParameter("ctrlcom_TipoCambio", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), "", $this->com_TipoCambio->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["com_Valor"] = new clsSQLParameter("ctrlcom_Valor", ccsText, "", "", $this->com_Valor->GetValue(), 0, false, $this->ErrorBlock);
//fah: Modificacion para asumir SIEMPRE como emisor el valor del defaul de la transaccion
        $this->cp["com_Emisor"] = new clsSQLParameter("ctrlcom_Emisor", ccsInteger, "", "", ($goTrans->f("cla_EmiDefault")>0 ?  $goTrans->f("cla_RecDefault") : $this->com_Emisor->GetValue()), 0, false, $this->ErrorBlock);
//echo "---" . $this->com_Emisor->GetValue() . " // " . $goTrans->f("cla_EmiDefault") . " // " . $this->cp["com_Emisor"]->GetDBValue();  ;
//die();
//echo "VAL2:" .$this->cp["com_Emisor"]->GetDBValue()  . "<br>";		
//echo $goTrans->f("cla_EmiDefault"). "<br>";		
//print_r($goTrans);
//die();
        $this->cp["com_CodReceptor"] = new clsSQLParameter("ctrlcom_CodReceptor", ccsInteger, "", "", $this->com_CodReceptor->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["com_Receptor"] = new clsSQLParameter("ctrlcom_Receptor", ccsText, "", "", $this->com_Receptor->GetValue(), " ", false, $this->ErrorBlock);
        $this->cp["com_Concepto"] = new clsSQLParameter("ctrlcom_Concepto", ccsMemo, "", "", $this->com_Concepto->GetValue(), " ", false, $this->ErrorBlock);
        $this->cp["com_Libro"] = new clsSQLParameter("ctrlcom_Libro", ccsInteger, "", "", $this->com_Libro->GetValue(), 9999, false, $this->ErrorBlock);
        $this->cp["com_NumRetenc"] = new clsSQLParameter("ctrlcom_NumRetenc", ccsInteger, "", "", $this->com_NumRetenc->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["com_RefOperat"] = new clsSQLParameter("ctrlcom_RefOperat", ccsInteger, "", "", $this->com_RefOperat->GetValue(), 0, false, $this->ErrorBlock);
        $this->cp["com_EstProceso"] = new clsSQLParameter("ctrlcom_EstProceso", ccsInteger, "", "", $this->com_EstProceso->GetValue(), -1, false, $this->ErrorBlock);
        $this->cp["com_EstOperacion"] = new clsSQLParameter("ctrlcom_EstOperacion", ccsInteger, "", "", $this->com_EstOperacion->GetValue(), -1, false, $this->ErrorBlock);
        $this->cp["com_NumProceso"] = new clsSQLParameter("ctrlcom_NumProceso", ccsInteger, "", "", $this->com_NumProceso->GetValue(), -1, false, $this->ErrorBlock);
        $this->cp["com_Usuario"] = new clsSQLParameter("ctrlcom_Usuario", ccsText, "", "", $this->com_Usuario->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["com_FecDigita"] = new clsSQLParameter("ctrlcom_FecDigita", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->com_FecDigita->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["com_NumPeriodo"] = new clsSQLParameter("ctrlcom_NumPeriodo", ccsInteger, "", "", $this->com_NumPeriodo->GetValue(), -1, false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO concomprobantes ("
             . "com_TipoComp, "
             . "com_NumComp, "
             . "com_FecTrans, "
             . "com_FecContab, "
             . "com_FecVencim, "
             . "com_CodMoneda, "
             . "com_TipoCambio, "
             . "com_Valor, "
             . "com_Emisor, "
             . "com_CodReceptor, "
             . "com_Receptor, "
             . "com_Concepto, "
             . "com_Libro, "
             . "com_NumRetenc, "
             . "com_RefOperat, "
             . "com_EstProceso, "
             . "com_EstOperacion, "
             . "com_NumProceso, "
             . "com_Usuario, "
             . "com_FecDigita, "
             . "com_NumPeriodo"
             . ") VALUES ("
             . $this->ToSQL($this->cp["com_TipoComp"]->GetDBValue(), $this->cp["com_TipoComp"]->DataType) . ", "
             . $this->ToSQL($this->cp["com_NumComp"]->GetDBValue(), $this->cp["com_NumComp"]->DataType) . ", "
             . $this->ToSQL($this->cp["com_FecTrans"]->GetDBValue(), $this->cp["com_FecTrans"]->DataType) . ", "
             . $this->ToSQL($this->cp["com_FecContab"]->GetDBValue(), $this->cp["com_FecContab"]->DataType) . ", "
             . $this->ToSQL($this->cp["com_FecVencim"]->GetDBValue(), $this->cp["com_FecVencim"]->DataType) . ", "
             . $this->ToSQL($this->cp["com_CodMoneda"]->GetDBValue(), $this->cp["com_CodMoneda"]->DataType) . ", "
             . $this->ToSQL($this->cp["com_TipoCambio"]->GetDBValue(), $this->cp["com_TipoCambio"]->DataType) . ", "
             . $this->ToSQL($this->cp["com_Valor"]->GetDBValue(), $this->cp["com_Valor"]->DataType) . ", "
             . $this->ToSQL($this->cp["com_Emisor"]->GetDBValue(), $this->cp["com_Emisor"]->DataType) . ", "
             . $this->ToSQL($this->cp["com_CodReceptor"]->GetDBValue(), $this->cp["com_CodReceptor"]->DataType) . ", "
             . $this->ToSQL($this->cp["com_Receptor"]->GetDBValue(), $this->cp["com_Receptor"]->DataType) . ", "
             . $this->ToSQL($this->cp["com_Concepto"]->GetDBValue(), $this->cp["com_Concepto"]->DataType) . ", "
             . $this->ToSQL($this->cp["com_Libro"]->GetDBValue(), $this->cp["com_Libro"]->DataType) . ", "
             . $this->ToSQL($this->cp["com_NumRetenc"]->GetDBValue(), $this->cp["com_NumRetenc"]->DataType) . ", "
             . $this->ToSQL($this->cp["com_RefOperat"]->GetDBValue(), $this->cp["com_RefOperat"]->DataType) . ", "
             . $this->ToSQL($this->cp["com_EstProceso"]->GetDBValue(), $this->cp["com_EstProceso"]->DataType) . ", "
             . $this->ToSQL($this->cp["com_EstOperacion"]->GetDBValue(), $this->cp["com_EstOperacion"]->DataType) . ", "
             . $this->ToSQL($this->cp["com_NumProceso"]->GetDBValue(), $this->cp["com_NumProceso"]->DataType) . ", "
             . $this->ToSQL($this->cp["com_Usuario"]->GetDBValue(), $this->cp["com_Usuario"]->DataType) . ", "
             . $this->ToSQL($this->cp["com_FecDigita"]->GetDBValue(), $this->cp["com_FecDigita"]->DataType) . ", "
             . $this->ToSQL($this->cp["com_NumPeriodo"]->GetDBValue(), $this->cp["com_NumPeriodo"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Insert Method

//Update Method @70-A829AA99
    function Update()
    {
        $this->cp["com_FecTrans"] = new clsSQLParameter("ctrlcom_FecTrans", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->com_FecTrans->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["com_FecContab"] = new clsSQLParameter("ctrlcom_FecContab", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->com_FecContab->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["com_FecVencim"] = new clsSQLParameter("ctrlcom_FecVencim", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->com_FecVencim->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["com_CodMoneda"] = new clsSQLParameter("ctrlcom_CodMoneda", ccsInteger, "", "", $this->com_CodMoneda->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["com_TipoCambio"] = new clsSQLParameter("ctrlcom_TipoCambio", ccsFloat, Array(False, 2, ".", ",", False, "", "", 1, True, ""), "", $this->com_TipoCambio->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["com_Valor"] = new clsSQLParameter("ctrlcom_Valor", ccsText, "", "", $this->com_Valor->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["com_Emisor"] = new clsSQLParameter("ctrlcom_Emisor", ccsInteger, "", "", $this->com_Emisor->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["com_CodReceptor"] = new clsSQLParameter("ctrlcom_CodReceptor", ccsInteger, "", "", $this->com_CodReceptor->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["com_Receptor"] = new clsSQLParameter("ctrlcom_Receptor", ccsText, "", "", $this->com_Receptor->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["com_Concepto"] = new clsSQLParameter("ctrlcom_Concepto", ccsMemo, "", "", $this->com_Concepto->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["com_Libro"] = new clsSQLParameter("ctrlcom_Libro", ccsInteger, "", "", $this->com_Libro->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["com_NumRetenc"] = new clsSQLParameter("ctrlcom_NumRetenc", ccsInteger, "", "", $this->com_NumRetenc->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["com_RefOperat"] = new clsSQLParameter("ctrlcom_RefOperat", ccsInteger, "", "", $this->com_RefOperat->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["com_EstProceso"] = new clsSQLParameter("ctrlcom_EstProceso", ccsInteger, "", "", $this->com_EstProceso->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["com_EstOperacion"] = new clsSQLParameter("ctrlcom_EstOperacion", ccsInteger, "", "", $this->com_EstOperacion->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["com_NumProceso"] = new clsSQLParameter("ctrlcom_NumProceso", ccsInteger, "", "", $this->com_NumProceso->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["com_Usuario"] = new clsSQLParameter("ctrlcom_Usuario", ccsText, "", "", $this->com_Usuario->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["com_FecDigita"] = new clsSQLParameter("ctrlcom_FecDigita", ccsDate, Array("dd", "/", "mmm", "/", "yy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->com_FecDigita->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["com_NumPeriodo"] = new clsSQLParameter("ctrlcom_NumPeriodo", ccsInteger, "", "", $this->com_NumPeriodo->GetValue(), -1, false, $this->ErrorBlock);
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlcom_RegNumero", ccsInteger, "", "", CCGetFromGet("com_RegNumero", ""), "", true);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $wp->Criterion[1] = $wp->Operation(opEqual, "com_RegNumero", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),true);
        $Where = $wp->Criterion[1];
        $this->SQL = "UPDATE concomprobantes SET "
             . "com_FecTrans=" . $this->ToSQL($this->cp["com_FecTrans"]->GetDBValue(), $this->cp["com_FecTrans"]->DataType) . ", "
             . "com_FecContab=" . $this->ToSQL($this->cp["com_FecContab"]->GetDBValue(), $this->cp["com_FecContab"]->DataType) . ", "
             . "com_FecVencim=" . $this->ToSQL($this->cp["com_FecVencim"]->GetDBValue(), $this->cp["com_FecVencim"]->DataType) . ", "
             . "com_CodMoneda=" . $this->ToSQL($this->cp["com_CodMoneda"]->GetDBValue(), $this->cp["com_CodMoneda"]->DataType) . ", "
             . "com_TipoCambio=" . $this->ToSQL($this->cp["com_TipoCambio"]->GetDBValue(), $this->cp["com_TipoCambio"]->DataType) . ", "
             . "com_Valor=" . $this->ToSQL($this->cp["com_Valor"]->GetDBValue(), $this->cp["com_Valor"]->DataType) . ", "
             . "com_Emisor=" . $this->ToSQL($this->cp["com_Emisor"]->GetDBValue(), $this->cp["com_Emisor"]->DataType) . ", "
             . "com_CodReceptor=" . $this->ToSQL($this->cp["com_CodReceptor"]->GetDBValue(), $this->cp["com_CodReceptor"]->DataType) . ", "
             . "com_Receptor=" . $this->ToSQL($this->cp["com_Receptor"]->GetDBValue(), $this->cp["com_Receptor"]->DataType) . ", "
             . "com_Concepto=" . $this->ToSQL($this->cp["com_Concepto"]->GetDBValue(), $this->cp["com_Concepto"]->DataType) . ", "
             . "com_Libro=" . $this->ToSQL($this->cp["com_Libro"]->GetDBValue(), $this->cp["com_Libro"]->DataType) . ", "
             . "com_NumRetenc=" . $this->ToSQL($this->cp["com_NumRetenc"]->GetDBValue(), $this->cp["com_NumRetenc"]->DataType) . ", "
             . "com_RefOperat=" . $this->ToSQL($this->cp["com_RefOperat"]->GetDBValue(), $this->cp["com_RefOperat"]->DataType) . ", "
//             . "com_EstProceso=" . $this->ToSQL($this->cp["com_EstProceso"]->GetDBValue(), $this->cp["com_EstProceso"]->DataType) . ", "
//             . "com_EstOperacion=" . $this->ToSQL($this->cp["com_EstOperacion"]->GetDBValue(), $this->cp["com_EstOperacion"]->DataType) . ", "
             . "com_NumProceso=" . $this->ToSQL($this->cp["com_NumProceso"]->GetDBValue(), $this->cp["com_NumProceso"]->DataType) . ", "
             . "com_Usuario=" . $this->ToSQL($this->cp["com_Usuario"]->GetDBValue(), $this->cp["com_Usuario"]->DataType) . ", "
             . "com_FecDigita=" . $this->ToSQL($this->cp["com_FecDigita"]->GetDBValue(), $this->cp["com_FecDigita"]->DataType) . ", "
             . "com_NumPeriodo=" . $this->ToSQL($this->cp["com_NumPeriodo"]->GetDBValue(), $this->cp["com_NumPeriodo"]->DataType);
/*
echo ">>>dato: " . $this->cp["com_EstProceso"]->GetDBValue();
echo "<br>>>UPD:" .$this->SQL;
die();
*/
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Update Method

//Delete Method @70-6001A975
    function Delete()
    {
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlcom_RegNumero", ccsInteger, "", "", CCGetFromGet("com_RegNumero", 0), "", false);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $wp->Criterion[1] = $wp->Operation(opEqual, "com_RegNumero", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),false);
        $Where = $wp->Criterion[1];
        $this->SQL = "DELETE FROM concomprobantes";
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        $this->query($this->SQL);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        if($this->Errors->Count() > 0)
            $this->Errors->AddError($this->Errors->ToString());
        $this->close();
    }
//End Delete Method

} //End InTrTr_compDataSource Class @70-FCB6E20C



//Initialize Page @1-0AF99ED2
// Variables
$ilPeriodo = 0; //          Periodo Contable
$FileName = "";
$Redirect = "";
$Tpl = "";
$TemplateFileName = "";
$BlockToParse = "";
$ComponentName = "";

// Events;
$CCSEvents = "";
$CCSEventResult = "";

$FileName = "InTrTr_Cabe.php";
$Redirect = "";
$TemplateFileName = "InTrTr_Cabe.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-B9EA322F
$DBdatos = new clsDBdatos();

// Controls
$Cabecera = new clsCabecera();
$Cabecera->BindEvents();
$Cabecera->TemplatePath = "../De_Files/";
$Cabecera->Initialize();
$InTrTr_topline = new clsGridInTrTr_topline();
$InTrTr_comp = new clsRecordInTrTr_comp();
$InTrTr_comp->Initialize();

// Events
include("./InTrTr_Cabe_events.php");
BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-FA9125FB
$Cabecera->Operations();
$InTrTr_comp->Operation();
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

//Show Page @1-55CA29F9
$Cabecera->Show("Cabecera");
$InTrTr_topline->Show();
$InTrTr_comp->Show();
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
