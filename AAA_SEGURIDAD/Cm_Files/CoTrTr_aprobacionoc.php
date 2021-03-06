<?php
/*
 *  @rev    fah Jun/10/09       Aplicar restricciones de acceso por Usuario- Emisor, segun $_SESSION[restr]
 **/
//Include Common Files @1-8E58AE89
//Evalúa aprobación o desaprobación de comprobante
define("RelativePath", "..");
define('IMAGE_PATH', '../Images/');//Constante global
include("GenUti.inc.php");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @99-49BEB8FC
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation
class clsRecordCmTrTr_qry { //CmTrTr_qry Class @5-113DEF8A

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
    function clsRecordCmTrTr_qry()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record CmTrTr_qry/Error";
        $this->ReadAllowed = false;
        $this->InsertAllowed = false;
        $this->UpdateAllowed = false;
        $this->DeleteAllowed = false;
        if($this->Visible)
        {
            $this->ComponentName = "CmTrTr_qry";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;            
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
        $Redirect = "CmTrTr.php?pMod=" . fGetParam("pMod", "CO")."&";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick")) {
                    $Redirect = "";
                } else {
                    $Redirect .= "CmTrTr.php" . "?pMod=" . fGetParam("pMod", "CO") . "&" . CCMergeQueryStrings(CCGetQueryString("Form", Array("Button_DoSearch", "btCancelar")));
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

} //End CmTrTr_qry Class @5-FCB6E20C

class clsGridCmTrTr_list { //CmTrTr_list class @2-815AAD49

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
    function clsGridCmTrTr_list()
    {
        global $FileName;
        $this->ComponentName = "CmTrTr_list";
        $this->Visible = True;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid CmTrTr_list";
        $this->ds = new clsCmTrTr_listDataSource();        

        $this->hTipoComp = new clsControl(ccsHidden, "hTipoComp", "hTipoComp", ccsText, "", CCGetRequestParam("hTipoComp", ccsGet));
        $this->hNumComp = new clsControl(ccsHidden, "hNumComp", "hNumComp", ccsInteger, "", CCGetRequestParam("hNumComp", ccsGet));
        $this->com_Comproba = new clsControl(ccsLink, "com_Comproba", "com_Comproba", ccsText, "", CCGetRequestParam("com_Comproba", ccsGet));        
        $this->com_FecTrans = new clsControl(ccsLabel, "com_FecTrans", "com_FecTrans", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("com_FecTrans", ccsGet));
        $this->com_FecContab = new clsControl(ccsLabel, "com_FecContab", "com_FecContab", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("com_FecContab", ccsGet));
        $this->com_Concepto = new clsControl(ccsLabel, "com_Concepto", "com_Concepto", ccsMemo, "", CCGetRequestParam("com_Concepto", ccsGet));
        $this->com_Usuario = new clsControl(ccsLabel, "com_Usuario", "com_Usuario", ccsText, "", CCGetRequestParam("com_Usuario", ccsGet));
        $this->com_EstProceso = new clsControl(ccsLabel, "com_EstProceso", "com_EstProceso", ccsText, "", CCGetRequestParam("com_EstProceso", ccsGet));
        $this->com_RefOperat = new clsControl(ccsLabel, "com_RefOperat", "com_RefOperat", ccsInteger, "", CCGetRequestParam("com_RefOperat", ccsGet));
        $this->SolicitadoPor = new clsControl(ccsLabel, "SolicitadoPor", "SolicitadoPor", ccsText, "", CCGetRequestParam("SolicitadoPor", ccsGet));
        $this->Proveedor = new clsControl(ccsLabel, "Proveedor", "Proveedor", ccsText, "", CCGetRequestParam("Proveedor", ccsGet));
        $this->Total = new clsControl(ccsLabel, "Total", "Total", ccsText, "", CCGetRequestParam("Total", ccsGet));
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpCentered);
        $this->concomprobantes_Insert = new clsControl(ccsLink, "concomprobantes_Insert", "concomprobantes_Insert", ccsText, "", CCGetRequestParam("concomprobantes_Insert", ccsGet));
        $this->concomprobantes_Insert->Parameters = CCGetQueryString("QueryString", Array("com_RegNumero", "ccsForm", "pTipoComp"));
        $this->concomprobantes_Insert->Page = "CmTrTr_Cabe.php";
        $this->Sorter_com_NumComp = new clsSorter($this->ComponentName, "Sorter_com_NumComp", $FileName);
        $this->Sorter_com_FecTrans = new clsSorter($this->ComponentName, "Sorter_com_FecTrans", $FileName);
        $this->Sorter_com_FecContab = new clsSorter($this->ComponentName, "Sorter_com_FecContab", $FileName);
        $this->Sorter_com_Usuario = new clsSorter($this->ComponentName, "Sorter_com_Usuario", $FileName);
        $this->Sorter_com_RefOperat = new clsSorter($this->ComponentName, "Sorter_com_RefOperat", $FileName);
    }
//End Class_Initialize Event

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
        $this->ds->Parameters["urls_com_Receptor"] = CCGetFromGet("s_com_Receptor", "");
        $this->ds->Parameters["urls_com_Concepto"] = CCGetFromGet("s_com_Concepto", "");
        $this->ds->Parameters["urls_com_EstProceso"] = CCGetFromGet("s_com_EstProceso", "");
        $this->ds->Parameters["urls_SolicitadoPor"] = CCGetFromGet("s_SolicitadoPor", "");
        $this->ds->Parameters["urls_Proveedor"] = CCGetFromGet("s_Proveedor", "");
        $this->ds->Parameters["urls_Total"] = CCGetFromGet("s_Total", "");

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");


        $this->ds->Prepare();
        $this->ds->Open();

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
        if(!$this->Visible) return;

        $GridBlock = "Grid " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $GridBlock;


        $is_next_record = $this->ds->next_record();
        if($is_next_record)
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
                $this->com_Comproba->Page = "CmTrTr_Cabe.php";
                $this->com_FecTrans->SetValue($this->ds->com_FecTrans->GetValue());
                $this->com_FecContab->SetValue($this->ds->com_FecContab->GetValue());
                $this->com_Concepto->SetValue($this->ds->com_Concepto->GetValue());
                $this->com_Usuario->SetValue($this->ds->com_Usuario->GetValue());
                $this->com_EstProceso->SetValue($this->ds->com_EstProceso->GetValue());
                $this->com_RefOperat->SetValue($this->ds->com_RefOperat->GetValue());
                $this->SolicitadoPor->SetValue($this->ds->SolicitadoPor->GetValue());
                $this->Proveedor->SetValue($this->ds->Proveedor->GetValue());
                $this->Total->SetValue($this->ds->Total->GetValue());
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
                $this->SolicitadoPor->Show();
                $this->Proveedor->Show();
                $this->Total->Show();
                $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                $Tpl->parse("Row", true);
                $ShownRecords++;
                $is_next_record = $this->ds->next_record();
            } while ($is_next_record);
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
        $errors .= $this->SolicitadoPor->Errors->ToString();
        $errors .= $this->Proveedor->Errors->ToString();
        $errors .= $this->Total->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End CmTrTr_list Class @2-FCB6E20C

class clsCmTrTr_listDataSource extends clsDBdatos {  //CmTrTr_listDataSource Class @2-6D7ACF4A

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
    var $SolicitadoPor;
    var $Proveedor;
    var $Total;
    var $com_FecTrans;
    var $com_FecContab;
    var $com_Concepto;
    var $com_Usuario;
    var $com_EstProceso;
    var $com_RefOperat;
//End DataSource Variables

//Class_Initialize Event @2-88EED677
    function clsCmTrTr_listDataSource()
    {
        $this->ErrorBlock = "Grid CmTrTr_list";
        $this->Initialize();
        $this->hTipoComp = new clsField("hTipoComp", ccsText, "");
        $this->hNumComp = new clsField("hNumComp", ccsInteger, "");
        $this->com_Comproba = new clsField("com_Comproba", ccsText, "");        
        $this->com_FecTrans = new clsField("com_FecTrans", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->com_FecContab = new clsField("com_FecContab", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->com_Concepto = new clsField("com_Concepto", ccsMemo, "");
        $this->com_Usuario = new clsField("com_Usuario", ccsText, "");
        $this->com_EstProceso = new clsField("com_EstProceso", ccsText, "");
        $this->com_RefOperat = new clsField("com_RefOperat", ccsInteger, "");
        $this->SolicitadoPor = new clsField("SolicitadoPor", ccsText, "");
        $this->Proveedor = new clsField("Proveedor", ccsText, "");
        $this->Total = new clsField("Total", ccsText, "");

    }
//End Class_Initialize Event
//Prepare Method @2-704DEC36
    function Prepare()
    {
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
        $this->wp->AddParameter("10", "urls_SolicitadoPor", ccsText, "", "", $this->Parameters["urls_SolicitadoPor"], "", false);
        $this->wp->AddParameter("11", "urls_Proveedor", ccsText, "", "", $this->Parameters["urls_Proveedor"], "", false);
        $this->wp->AddParameter("12", "urls_Total", ccsText, "", "", $this->Parameters["urls_Total"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opContains, "com_TipoComp", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opEqual, "com_NumComp", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsInteger),false);
        $this->wp->Criterion[3] = $this->wp->Operation(opEqual, "com_RegNumero", $this->wp->GetDBValue("3"), $this->ToSQL($this->wp->GetDBValue("3"), ccsInteger),false);
        $this->wp->Criterion[4] = $this->wp->Operation(opEqual, "com_FecTrans", $this->wp->GetDBValue("4"), $this->ToSQL($this->wp->GetDBValue("4"), ccsDate),false);
        $this->wp->Criterion[5] = $this->wp->Operation(opEqual, "com_FecContab", $this->wp->GetDBValue("5"), $this->ToSQL($this->wp->GetDBValue("5"), ccsDate),false);
        $this->wp->Criterion[6] = $this->wp->Operation(opContains, "com_Receptor", $this->wp->GetDBValue("6"), $this->ToSQL($this->wp->GetDBValue("6"), ccsText),false);
        $this->wp->Criterion[7] = $this->wp->Operation(opContains, "com_Concepto", $this->wp->GetDBValue("7"), $this->ToSQL($this->wp->GetDBValue("7"), ccsText),false);
        $this->wp->Criterion[8] = $this->wp->Operation(opEqual, "com_EstProceso", $this->wp->GetDBValue("8"), $this->ToSQL($this->wp->GetDBValue("8"), ccsInteger),false);
        $this->wp->Criterion[9] = $this->wp->Operation(opBeginsWith, "com_CodReceptor", $this->wp->GetDBValue("9"), $this->ToSQL($this->wp->GetDBValue("9"), ccsText),false);
        $this->wp->Criterion[10] = $this->wp->Operation(opBeginsWith, "SolicitadoPor", $this->wp->GetDBValue("10"), $this->ToSQL($this->wp->GetDBValue("10"), ccsText),false);
        $this->wp->Criterion[11] = $this->wp->Operation(opBeginsWith, "Proveedor", $this->wp->GetDBValue("11"), $this->ToSQL($this->wp->GetDBValue("11"), ccsText),false);
        $this->wp->Criterion[12] = $this->wp->Operation(opBeginsWith, "Total", $this->wp->GetDBValue("12"), $this->ToSQL($this->wp->GetDBValue("12"), ccsText),false);
        $this->Where = $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->opAND(false, $this->wp->Criterion[1], $this->wp->Criterion[2]), $this->wp->Criterion[3]), $this->wp->Criterion[4]), $this->wp->Criterion[5]), $this->wp->opOR(true, $this->wp->Criterion[6], $this->wp->Criterion[9])), $this->wp->Criterion[7]), $this->wp->Criterion[8]);
    }
//End Prepare Method

//Open Method @2-F38CCB9A
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM concomprobantes c 
                INNER JOIN invdetalle d ON c.com_RegNumero=d.det_RegNumero
                INNER JOIN conactivos a ON d.det_CodItem=act_CodAuxiliar
                INNER JOIN genunmedida m ON d.det_UniMedida=uni_CodUnidad
                INNER JOIN conpersonas p ON c.com_CodReceptor=p.per_CodAuxiliar
                INNER JOIN conpersonas g ON c.com_beneficiary=g.per_CodAuxiliar
                INNER JOIN conpersonas f ON c.com_emisor=f.per_CodAuxiliar
                INNER JOIN conpersonas h ON c.com_embarque=h.per_CodAuxiliar
                LEFT JOIN liqembarques e ON c.com_RefOperat=emb_RefOperativa
        WHERE c.com_TipoComp = 'OC' OR c.com_TipoComp = 'CI'";
        $this->SQL = "SELECT com_NumComp,com_FecTrans, com_Receptor, p.per_RUC ruc, com_Concepto, com_FecContab
        ,com_FecVencim, IF(com_FecVencim > com_FecContab,DATEDIFF(com_FecVencim,com_FecContab), 0) credito
        ,com_RefOperat, emb_SemInicio semana
        ,f.per_Apellidos bodega_apellidos
        ,f.per_Nombres bodega_nombres
        ,DATEDIFF(com_FecVencim,com_FecTrans) Tiempo_Entrega
        ,c.com_payment formaPago
        ,CONCAT(g.per_Nombres,' ',g.per_Apellidos) As SolicitadoPor
        ,h.per_Nombres NombresDptoSolicitante
        ,h.per_Apellidos ApellidosDptoSolicitante
        ,SUM(det_ValUnitario) AS Total
        ,c.com_TipoComp
        ,c.com_NumComp
        ,c.com_Receptor As Proveedor
        ,c.com_EstProceso
        FROM concomprobantes c 
                INNER JOIN invdetalle d ON c.com_RegNumero=d.det_RegNumero
                INNER JOIN conactivos a ON d.det_CodItem=act_CodAuxiliar
                INNER JOIN genunmedida m ON d.det_UniMedida=uni_CodUnidad
                INNER JOIN conpersonas p ON c.com_CodReceptor=p.per_CodAuxiliar
                INNER JOIN conpersonas g ON c.com_beneficiary=g.per_CodAuxiliar
                INNER JOIN conpersonas f ON c.com_emisor=f.per_CodAuxiliar
                INNER JOIN conpersonas h ON c.com_embarque=h.per_CodAuxiliar
                LEFT JOIN liqembarques e ON c.com_RefOperat=emb_RefOperativa
        WHERE CONVERT(c.com_TipoComp using utf8) IN (SELECT CONVERT(par_Valor1 using utf8) FROM genparametros WHERE par_Clave = 'EGCOM') 
            AND c.com_EstProceso = '5'
            AND c.com_EstOperacion = '-1'
        GROUP BY c.com_NumComp";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
/* fah */
            switch(fGetParam("pMod", "CoTrTr")){
                case "IN":
                    $lsModulo = "CmTrTr";
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
            echo "<br>". $CmTrTr_comp->com_Emisor->ds->SQL ;
               echo "<br><br>" . $this->Where;
        }
        $this->Order = $this->Order ? $this->Order : "SolicitadoPor DESC, com_FecTrans DESC";
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
        $this->SolicitadoPor->SetDBValue($this->f("SolicitadoPor"));
        $this->Proveedor->SetDBValue($this->f("Proveedor"));
        $this->Total->SetDBValue($this->f("Total"));
        //$this->Proveedor->SetDBValue(trim($this->f("com_Receptor")));
    }
//End SetValues Method

} //End CmTrTr_listDataSource Class @2-FCB6E20C

class clsAprobacionOC_mantDataSource extends clsDBdatos {  //AprobacionOC_mantDataSource Class @12-AB1393DF
    // Datasource fields
    var $com_EstProceso;
    var $com_EstOperacion;
    var $Tipo_OC;
    var $Num_OC;
    //End DataSource Variables

//Class_Initialize Event @12-D14E7B35
    function clsAprobacionOC_mantDataSource()
    {
        $this->com_EstProceso = "";
        $this->com_EstProceso = "";
    }
//End Class_Initialize Event

//SetValues Method @12-8540B3CD
    function SetValues($comEstOperacion,$comEstProceso,$TipoOC,$NumOC)
    {
        $this->com_EstProceso=$comEstProceso;
        $this->com_EstOperacion=$comEstOperacion;
        $this->Tipo_OC=$TipoOC;
        $this->Num_OC=$NumOC;
    }
//End SetValues Method

//Update Method @12-1761EB83
    function Update()
    {
        $this->Initialize();
        $this->SQL = "UPDATE concomprobantes SET "
             . "com_EstOperacion=" . $this->com_EstOperacion . ", "
             . "com_EstProceso=" . $this->com_EstProceso;
        $this->Where = "com_TipoComp = '" . $this->Tipo_OC . "' AND com_NumComp = " . $this->Num_OC;
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->query($this->SQL);
        $this->close();  
    }
//End Update Method

} //End AprobacionOC_mantDataSource Class @12-FCB6E20C
/*
 * ------------------------------------------------------------------------------------------
 * */
/*
 * 
 * Determina si es un update o muestra la Lista de Comprobantes Activas para auotrizar
 * 
 * 
 * */
 /*
 * ------------------------------------------------------------------------------------------
 * */
if(isset($_POST['actionoc'])){
    if($_POST['actionoc']=='aprobar'){
        if(isset($_POST['itemoc'])){
            $dsUpdate = new clsAprobacionOC_mantDataSource();
            $dsUpdate->SetValues(10, 5, $_POST['itemoc'], $_POST['itemnumero']);
            $dsUpdate->Update();
            $verifyStatus=array("verifyStatus"=> $_POST['itemoc'] . ' - ' . $_POST['itemnumero'] . ' FUE APROBADA.'  );            
            echo json_encode ($verifyStatus);
        }  
    }
    else if($_POST['actionoc']=='desaprobar'){
        if(isset($_POST['itemoc'])){
            $dsUpdate = new clsAprobacionOC_mantDataSource();
            $dsUpdate->SetValues(11, -1, $_POST['itemoc'], $_POST['itemnumero']);
            $dsUpdate->Update();
            $verifyStatus=array("verifyStatus"=>$_POST['itemoc'] . ' - ' . $_POST['itemnumero'] . ' FUE ANULADA.'  );
            echo json_encode ($verifyStatus);
        }
    }
}
else{
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

$FileName = "CoTrTr_aprobacionoc.php";
$Redirect = "";
$TemplateFileName = "../Views/CoTrTr_aprobacionoc.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-191C84BE
$DBdatos = new clsDBdatos();
// Controls
//$Cabecera = new clsCabecera();
//$Cabecera->BindEvents();
//$Cabecera->TemplatePath = "../De_Files/";
//$Cabecera->Initialize();
$CmTrTr_qry = new clsRecordCmTrTr_qry();
$CmTrTr_list = new clsGridCmTrTr_list();
//$CmTrTr_list->Initialize();

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
//$Cabecera->Operations();
$CmTrTr_qry->Operation();
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
//$Cabecera->Show("Cabecera");
//$CmTrTr_qry->Show();
$CmTrTr_list->Show();
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
}

?>
