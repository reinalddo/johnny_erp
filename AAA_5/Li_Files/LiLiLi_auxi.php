<?php
/**
*		Hoja auxiliar de Liquidaciones, permite presentar el detalle de un rubro de liquidacion
*		Recibe como parametros el número de liquidacion y presenta el detalle de tarjas asociadas 
*		a esa liquidación, incluyendo detalle de precios
*		@author		Fausto Astudillo
*		@created	Jul/10/04
*/
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files
include (RelativePath . "/LibPhp/SegLib.php");
class clsGridliquidacion { //liquidacion class @2-E6110FE4

//Variables @2-0B3A0FB0

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

//Class_Initialize Event @2-C144411E
    function clsGridliquidacion()
    {
        global $FileName;
        $this->ComponentName = "liquidacion";
        $this->Visible = True;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid liquidacion";
        $this->ds = new clsliquidacionDataSource();
        $this->PageSize = 10;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));

        $this->com_NumComp = new clsControl(ccsLabel, "com_NumComp", "com_NumComp", ccsInteger, "", CCGetRequestParam("com_NumComp", ccsGet));
        $this->com_RegNumero = new clsControl(ccsTextBox, "com_RegNumero", "com_RegNumero", ccsInteger, "", CCGetRequestParam("com_RegNumero", ccsGet));
        $this->com_FecContab = new clsControl(ccsLabel, "com_FecContab", "com_FecContab", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("com_FecContab", ccsGet));
        $this->com_CodReceptor = new clsControl(ccsLabel, "com_CodReceptor", "com_CodReceptor", ccsInteger, "", CCGetRequestParam("com_CodReceptor", ccsGet));
        $this->per_Apellidos = new clsControl(ccsLabel, "per_Apellidos", "per_Apellidos", ccsText, "", CCGetRequestParam("per_Apellidos", ccsGet));
        $this->per_Nombres = new clsControl(ccsLabel, "per_Nombres", "per_Nombres", ccsText, "", CCGetRequestParam("per_Nombres", ccsGet));
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

//Show Method @2-4FE7E166
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["expr11"] = 'LQ';
        $this->ds->Parameters["urlliq_NumLiquida"] = CCGetFromGet("liq_NumLiquida", "");

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
                $this->com_NumComp->SetValue($this->ds->com_NumComp->GetValue());
                $this->com_RegNumero->SetValue($this->ds->com_RegNumero->GetValue());
                $this->com_FecContab->SetValue($this->ds->com_FecContab->GetValue());
                $this->com_CodReceptor->SetValue($this->ds->com_CodReceptor->GetValue());
                $this->per_Apellidos->SetValue($this->ds->per_Apellidos->GetValue());
                $this->per_Nombres->SetValue($this->ds->per_Nombres->GetValue());
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->com_NumComp->Show();
                $this->com_RegNumero->Show();
                $this->com_FecContab->Show();
                $this->com_CodReceptor->Show();
                $this->per_Apellidos->Show();
                $this->per_Nombres->Show();
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
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @2-124B5AFB
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->com_NumComp->Errors->ToString();
        $errors .= $this->com_RegNumero->Errors->ToString();
        $errors .= $this->com_FecContab->Errors->ToString();
        $errors .= $this->com_CodReceptor->Errors->ToString();
        $errors .= $this->per_Apellidos->Errors->ToString();
        $errors .= $this->per_Nombres->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End liquidacion Class @2-FCB6E20C

class clsliquidacionDataSource extends clsDBdatos {  //liquidacionDataSource Class @2-0169D179

//DataSource Variables @2-ECF414B9
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $com_NumComp;
    var $com_RegNumero;
    var $com_FecContab;
    var $com_CodReceptor;
    var $per_Apellidos;
    var $per_Nombres;
//End DataSource Variables

//Class_Initialize Event @2-FC05CB42
    function clsliquidacionDataSource()
    {
        $this->ErrorBlock = "Grid liquidacion";
        $this->Initialize();
        $this->com_NumComp = new clsField("com_NumComp", ccsInteger, "");
        $this->com_RegNumero = new clsField("com_RegNumero", ccsInteger, "");
        $this->com_FecContab = new clsField("com_FecContab", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->com_CodReceptor = new clsField("com_CodReceptor", ccsInteger, "");
        $this->per_Apellidos = new clsField("per_Apellidos", ccsText, "");
        $this->per_Nombres = new clsField("per_Nombres", ccsText, "");

    }
//End Class_Initialize Event

//SetOrder Method @2-9E1383D1
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            "");
    }
//End SetOrder Method

//Prepare Method @2-CD00E583
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "expr11", ccsText, "", "", $this->Parameters["expr11"], "", true);
        $this->wp->AddParameter("2", "urlliq_NumLiquida", ccsInteger, "", "", $this->Parameters["urlliq_NumLiquida"], "", true);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "com_TipoComp", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),true);
        $this->wp->Criterion[2] = $this->wp->Operation(opEqual, "com_NumComp", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsInteger),true);
        $this->Where = $this->wp->opAND(false, $this->wp->Criterion[1], $this->wp->Criterion[2]);
    }
//End Prepare Method

//Open Method @2-6A48B852
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM concomprobantes LEFT JOIN conpersonas ON " .
        "concomprobantes.com_CodReceptor = conpersonas.per_CodAuxiliar";
        $this->SQL = "SELECT com_TipoComp, com_NumComp, com_RegNumero, com_FecContab, com_CodReceptor, com_Concepto, per_Apellidos, per_Nombres  " .
        "FROM concomprobantes LEFT JOIN conpersonas ON " .
        "concomprobantes.com_CodReceptor = conpersonas.per_CodAuxiliar";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @2-25A7552E
    function SetValues()
    {
        $this->com_NumComp->SetDBValue(trim($this->f("com_NumComp")));
        $this->com_RegNumero->SetDBValue(trim($this->f("com_RegNumero")));
        $this->com_FecContab->SetDBValue(trim($this->f("com_FecContab")));
        $this->com_CodReceptor->SetDBValue(trim($this->f("com_CodReceptor")));
        $this->per_Apellidos->SetDBValue($this->f("per_Apellidos"));
        $this->per_Nombres->SetDBValue($this->f("per_Nombres"));
    }
//End SetValues Method

} //End liquidacionDataSource Class @2-FCB6E20C

class clsGriddettarjas_list { //dettarjas_list class @24-12407C3F

//Variables @24-D0DD0B88

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
    var $Sorter_tac_Semana;
    var $Sorter_buq_Descripcion;
    var $Sorter_emb_FecZarpe;
    var $Sorter_tad_NumTarja;
    var $Navigator;
//End Variables

//Class_Initialize Event @24-B432D726
    function clsGriddettarjas_list()
    {
        global $FileName;
        $this->ComponentName = "dettarjas_list";
        $this->Visible = True;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid dettarjas_list";
        $this->ds = new clsdettarjas_listDataSource();
        $this->PageSize = 15;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        $this->SorterName = CCGetParam("dettarjas_listOrder", "");
        $this->SorterDirection = CCGetParam("dettarjas_listDir", "");

        $this->tac_Semana = new clsControl(ccsLabel, "tac_Semana", "tac_Semana", ccsInteger, "", CCGetRequestParam("tac_Semana", ccsGet));
        $this->buq_Descripcion = new clsControl(ccsLabel, "buq_Descripcion", "buq_Descripcion", ccsText, "", CCGetRequestParam("buq_Descripcion", ccsGet));
        $this->emb_NumViaje = new clsControl(ccsLabel, "emb_NumViaje", "emb_NumViaje", ccsInteger, "", CCGetRequestParam("emb_NumViaje", ccsGet));
        $this->Label1 = new clsControl(ccsLabel, "Label1", "Label1", ccsText, "", CCGetRequestParam("Label1", ccsGet));
        $this->tac_Fecha = new clsControl(ccsLabel, "tac_Fecha", "tac_Fecha", ccsDate, Array("dd", "/", "mmm", "/", "yy"), CCGetRequestParam("tac_Fecha", ccsGet));
        $this->tad_NumTarja = new clsControl(ccsLabel, "tad_NumTarja", "tad_NumTarja", ccsInteger, "", CCGetRequestParam("tad_NumTarja", ccsGet));
        $this->tad_Secuencia = new clsControl(ccsLabel, "tad_Secuencia", "tad_Secuencia", ccsInteger, "", CCGetRequestParam("tad_Secuencia", ccsGet));
        $this->tad_CantDespachada = new clsControl(ccsLabel, "tad_CantDespachada", "tad_CantDespachada", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""), CCGetRequestParam("tad_CantDespachada", ccsGet));
        $this->tad_CantRecibida = new clsControl(ccsLabel, "tad_CantRecibida", "tad_CantRecibida", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""), CCGetRequestParam("tad_CantRecibida", ccsGet));
        $this->tad_CantRechazada = new clsControl(ccsLabel, "tad_CantRechazada", "tad_CantRechazada", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""), CCGetRequestParam("tad_CantRechazada", ccsGet));
        $this->tad_CantCaidas = new clsControl(ccsLabel, "tad_CantCaidas", "tad_CantCaidas", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""), CCGetRequestParam("tad_CantCaidas", ccsGet));
        $this->tmp_CantEmbarcada = new clsControl(ccsLabel, "tmp_CantEmbarcada", "tmp_CantEmbarcada", ccsFloat, Array(True, 1, ".", " ", False, Array("#", "#", "#", "#"), Array("#"), 1, True, ""), CCGetRequestParam("tmp_CantEmbarcada", ccsGet));
        $this->tad_ValUnitario = new clsControl(ccsLabel, "tad_ValUnitario", "tad_ValUnitario", ccsFloat, "", CCGetRequestParam("tad_ValUnitario", ccsGet));
        $this->tmp_Adelanto = new clsControl(ccsLabel, "tmp_Adelanto", "tmp_Adelanto", ccsFloat, "", CCGetRequestParam("tmp_Adelanto", ccsGet));
        $this->tmp_Bono = new clsControl(ccsLabel, "tmp_Bono", "tmp_Bono", ccsFloat, "", CCGetRequestParam("tmp_Bono", ccsGet));
        $this->tmp_ValFruta = new clsControl(ccsLabel, "tmp_ValFruta", "tmp_ValFruta", ccsFloat, "", CCGetRequestParam("tmp_ValFruta", ccsGet));
        $this->tmp_ValAdel = new clsControl(ccsLabel, "tmp_ValAdel", "tmp_ValAdel", ccsFloat, "", CCGetRequestParam("tmp_ValAdel", ccsGet));
        $this->tmp_ValBono = new clsControl(ccsLabel, "tmp_ValBono", "tmp_ValBono", ccsFloat, "", CCGetRequestParam("tmp_ValBono", ccsGet));
        $this->tad_LiqProceso = new clsControl(ccsLabel, "tad_LiqProceso", "tad_LiqProceso", ccsInteger, Array(True, 0, "", "", False, Array("#", "#", "#", "#"), "", 1, True, ""), CCGetRequestParam("tad_LiqProceso", ccsGet));
        $this->Sorter_tac_Semana = new clsSorter($this->ComponentName, "Sorter_tac_Semana", $FileName);
        $this->Sorter_buq_Descripcion = new clsSorter($this->ComponentName, "Sorter_buq_Descripcion", $FileName);
        $this->Sorter_emb_FecZarpe = new clsSorter($this->ComponentName, "Sorter_emb_FecZarpe", $FileName);
        $this->Sorter_tad_NumTarja = new clsSorter($this->ComponentName, "Sorter_tad_NumTarja", $FileName);
        $this->txt_SumDesp = new clsControl(ccsLabel, "txt_SumDesp", "txt_SumDesp", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""), CCGetRequestParam("txt_SumDesp", ccsGet));
        $this->txt_SumReci = new clsControl(ccsLabel, "txt_SumReci", "txt_SumReci", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""), CCGetRequestParam("txt_SumReci", ccsGet));
        $this->txt_SumRech = new clsControl(ccsLabel, "txt_SumRech", "txt_SumRech", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""), CCGetRequestParam("txt_SumRech", ccsGet));
        $this->txt_SumCaid = new clsControl(ccsLabel, "txt_SumCaid", "txt_SumCaid", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""), CCGetRequestParam("txt_SumCaid", ccsGet));
        $this->txt_SumEmba = new clsControl(ccsLabel, "txt_SumEmba", "txt_SumEmba", ccsFloat, Array(True, 2, ".", ",", False, Array("#", "#", "#", "#"), Array("#", "#"), 1, True, ""), CCGetRequestParam("txt_SumEmba", ccsGet));
        $this->txt_SumFruta = new clsControl(ccsLabel, "txt_SumFruta", "txt_SumFruta", ccsFloat, "", CCGetRequestParam("txt_SumFruta", ccsGet));
        $this->txt_SumAdel = new clsControl(ccsLabel, "txt_SumAdel", "txt_SumAdel", ccsFloat, "", CCGetRequestParam("txt_SumAdel", ccsGet));
        $this->txt_SumBono = new clsControl(ccsLabel, "txt_SumBono", "txt_SumBono", ccsFloat, "", CCGetRequestParam("txt_SumBono", ccsGet));
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpCentered);
    }
//End Class_Initialize Event

//Initialize Method @24-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @24-7ABD3F7D
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["urlliq_NumLiquida"] = CCGetFromGet("liq_NumLiquida", "");

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
                $this->tac_Semana->SetValue($this->ds->tac_Semana->GetValue());
                $this->buq_Descripcion->SetValue($this->ds->buq_Descripcion->GetValue());
                $this->emb_NumViaje->SetValue($this->ds->emb_NumViaje->GetValue());
                $this->Label1->SetValue($this->ds->Label1->GetValue());
                $this->tac_Fecha->SetValue($this->ds->tac_Fecha->GetValue());
                $this->tad_NumTarja->SetValue($this->ds->tad_NumTarja->GetValue());
                $this->tad_Secuencia->SetValue($this->ds->tad_Secuencia->GetValue());
                $this->tad_CantDespachada->SetValue($this->ds->tad_CantDespachada->GetValue());
                $this->tad_CantRecibida->SetValue($this->ds->tad_CantRecibida->GetValue());
                $this->tad_CantRechazada->SetValue($this->ds->tad_CantRechazada->GetValue());
                $this->tad_CantCaidas->SetValue($this->ds->tad_CantCaidas->GetValue());
                $this->tmp_CantEmbarcada->SetValue($this->ds->tmp_CantEmbarcada->GetValue());
                $this->tad_ValUnitario->SetValue($this->ds->tad_ValUnitario->GetValue());
                $this->tmp_Adelanto->SetValue($this->ds->tmp_Adelanto->GetValue());
                $this->tmp_Bono->SetValue($this->ds->tmp_Bono->GetValue());
                $this->tmp_ValFruta->SetValue($this->ds->tmp_ValFruta->GetValue());
                $this->tmp_ValAdel->SetValue($this->ds->tmp_ValAdel->GetValue());
                $this->tmp_ValBono->SetValue($this->ds->tmp_ValBono->GetValue());
                $this->tad_LiqProceso->SetValue($this->ds->tad_LiqProceso->GetValue());
                
                if( doubleval($this->tad_ValUnitario->GetValue()) > 0 ) {
                          $this->tad_ValUnitario->Text = CCFormatNumber($this->tad_ValUnitario->GetValue(), Array(False, 3, ".", " ", False, "", "", 1, True, ""));
                } else if ( $this->tad_ValUnitario->GetValue() == 0 && strlen($this->tad_ValUnitario->GetValue()) ) {
                          $this->tad_ValUnitario->Text = CCFormatNumber($this->tad_ValUnitario->GetValue(), Array(True, 0, ".", " ", False, Array(" "), "", 1, True, ""));
                } else {
                          $this->tad_ValUnitario->Text = CCFormatNumber($this->tad_ValUnitario->GetValue(), Array(True, 0, ".", " ", False, Array(" "), "", 1, True, ""));
                }
                
                if( doubleval($this->tmp_Adelanto->GetValue()) > 0 ) {
                          $this->tmp_Adelanto->Text = CCFormatNumber($this->tmp_Adelanto->GetValue(), Array(False, 3, ".", " ", False, "", "", 1, True, ""));
                } else if ( $this->tmp_Adelanto->GetValue() == 0 && strlen($this->tmp_Adelanto->GetValue()) ) {
                          $this->tmp_Adelanto->Text = CCFormatNumber($this->tmp_Adelanto->GetValue(), Array(True, 0, ".", " ", False, Array(" "), "", 1, True, ""));
                } else {
                          $this->tmp_Adelanto->Text = CCFormatNumber($this->tmp_Adelanto->GetValue(), Array(True, 0, ".", " ", False, Array(" "), "", 1, True, ""));
                }
                
                if( doubleval($this->tmp_Bono->GetValue()) > 0 ) {
                          $this->tmp_Bono->Text = CCFormatNumber($this->tmp_Bono->GetValue(), Array(False, 3, ".", " ", False, "", "", 1, True, ""));
                } else if ( $this->tmp_Bono->GetValue() == 0 && strlen($this->tmp_Bono->GetValue()) ) {
                          $this->tmp_Bono->Text = CCFormatNumber($this->tmp_Bono->GetValue(), Array(True, 0, ".", " ", False, Array(" "), "", 1, True, ""));
                } else {
                          $this->tmp_Bono->Text = CCFormatNumber($this->tmp_Bono->GetValue(), Array(True, 0, ".", " ", False, Array(" "), "", 1, True, ""));
                }
                
                if( doubleval($this->tmp_ValFruta->GetValue()) > 0 ) {
                          $this->tmp_ValFruta->Text = CCFormatNumber($this->tmp_ValFruta->GetValue(), Array(False, 2, ".", ",", False, "", "", 1, True, ""));
                } else if ( $this->tmp_ValFruta->GetValue() == 0 && strlen($this->tmp_ValFruta->GetValue()) ) {
                          $this->tmp_ValFruta->Text = CCFormatNumber($this->tmp_ValFruta->GetValue(), Array(True, 0, ".", ",", False, Array(" "), "", 1, True, ""));
                } else {
                          $this->tmp_ValFruta->Text = CCFormatNumber($this->tmp_ValFruta->GetValue(), Array(True, 0, ".", ",", False, Array(" "), "", 1, True, ""));
                }
                
                if( doubleval($this->tmp_ValAdel->GetValue()) > 0 ) {
                          $this->tmp_ValAdel->Text = CCFormatNumber($this->tmp_ValAdel->GetValue(), Array(False, 2, ".", ",", False, "", "", 1, True, ""));
                } else if ( $this->tmp_ValAdel->GetValue() == 0 && strlen($this->tmp_ValAdel->GetValue()) ) {
                          $this->tmp_ValAdel->Text = CCFormatNumber($this->tmp_ValAdel->GetValue(), Array(True, 0, ".", ",", False, Array(" "), "", 1, True, ""));
                } else {
                          $this->tmp_ValAdel->Text = CCFormatNumber($this->tmp_ValAdel->GetValue(), Array(True, 0, ".", ",", False, Array(" "), "", 1, True, ""));
                }
                
                if( doubleval($this->tmp_ValBono->GetValue()) > 0 ) {
                          $this->tmp_ValBono->Text = CCFormatNumber($this->tmp_ValBono->GetValue(), Array(False, 2, ".", ",", False, "", "", 1, True, ""));
                } else if ( $this->tmp_ValBono->GetValue() == 0 && strlen($this->tmp_ValBono->GetValue()) ) {
                          $this->tmp_ValBono->Text = CCFormatNumber($this->tmp_ValBono->GetValue(), Array(True, 0, ".", ",", False, Array(" "), "", 1, True, ""));
                } else {
                          $this->tmp_ValBono->Text = CCFormatNumber($this->tmp_ValBono->GetValue(), Array(True, 0, ".", ",", False, Array(" "), "", 1, True, ""));
                }
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->tac_Semana->Show();
                $this->buq_Descripcion->Show();
                $this->emb_NumViaje->Show();
                $this->Label1->Show();
                $this->tac_Fecha->Show();
                $this->tad_NumTarja->Show();
                $this->tad_Secuencia->Show();
                $this->tad_CantDespachada->Show();
                $this->tad_CantRecibida->Show();
                $this->tad_CantRechazada->Show();
                $this->tad_CantCaidas->Show();
                $this->tmp_CantEmbarcada->Show();
                $this->tad_ValUnitario->Show();
                $this->tmp_Adelanto->Show();
                $this->tmp_Bono->Show();
                $this->tmp_ValFruta->Show();
                $this->tmp_ValAdel->Show();
                $this->tmp_ValBono->Show();
                $this->tad_LiqProceso->Show();
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
        
        if( doubleval($this->txt_SumFruta->GetValue()) > 0 ) {
                  $this->txt_SumFruta->Text = CCFormatNumber($this->txt_SumFruta->GetValue(), Array(False, 2, ".", ",", False, "", "", 1, True, ""));
        } else if ( $this->txt_SumFruta->GetValue() == 0 && strlen($this->txt_SumFruta->GetValue()) ) {
                  $this->txt_SumFruta->Text = CCFormatNumber($this->txt_SumFruta->GetValue(), Array(True, 0, ".", ",", False, Array(" "), "", 1, True, ""));
        } else {
                  $this->txt_SumFruta->Text = CCFormatNumber($this->txt_SumFruta->GetValue(), Array(True, 0, ".", ",", False, Array(" "), "", 1, True, ""));
        }
        
        if( doubleval($this->txt_SumAdel->GetValue()) > 0 ) {
                  $this->txt_SumAdel->Text = CCFormatNumber($this->txt_SumAdel->GetValue(), Array(False, 2, ".", ",", False, "", "", 1, True, ""));
        } else if ( $this->txt_SumAdel->GetValue() == 0 && strlen($this->txt_SumAdel->GetValue()) ) {
                  $this->txt_SumAdel->Text = CCFormatNumber($this->txt_SumAdel->GetValue(), Array(True, 0, ".", ",", False, Array(" "), "", 1, True, ""));
        } else {
                  $this->txt_SumAdel->Text = CCFormatNumber($this->txt_SumAdel->GetValue(), Array(True, 0, ".", ",", False, Array(" "), "", 1, True, ""));
        }
        
        if( doubleval($this->txt_SumBono->GetValue()) > 0 ) {
                  $this->txt_SumBono->Text = CCFormatNumber($this->txt_SumBono->GetValue(), Array(False, 2, ".", ",", False, "", "", 1, True, ""));
        } else if ( $this->txt_SumBono->GetValue() == 0 && strlen($this->txt_SumBono->GetValue()) ) {
                  $this->txt_SumBono->Text = CCFormatNumber($this->txt_SumBono->GetValue(), Array(True, 0, ".", ",", False, Array(" "), "", 1, True, ""));
        } else {
                  $this->txt_SumBono->Text = CCFormatNumber($this->txt_SumBono->GetValue(), Array(True, 0, ".", ",", False, Array(" "), "", 1, True, ""));
        }
        $this->Sorter_tac_Semana->Show();
        $this->Sorter_buq_Descripcion->Show();
        $this->Sorter_emb_FecZarpe->Show();
        $this->Sorter_tad_NumTarja->Show();
        $this->txt_SumDesp->Show();
        $this->txt_SumReci->Show();
        $this->txt_SumRech->Show();
        $this->txt_SumCaid->Show();
        $this->txt_SumEmba->Show();
        $this->txt_SumFruta->Show();
        $this->txt_SumAdel->Show();
        $this->txt_SumBono->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @24-65508C34
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->tac_Semana->Errors->ToString();
        $errors .= $this->buq_Descripcion->Errors->ToString();
        $errors .= $this->emb_NumViaje->Errors->ToString();
        $errors .= $this->Label1->Errors->ToString();
        $errors .= $this->tac_Fecha->Errors->ToString();
        $errors .= $this->tad_NumTarja->Errors->ToString();
        $errors .= $this->tad_Secuencia->Errors->ToString();
        $errors .= $this->tad_CantDespachada->Errors->ToString();
        $errors .= $this->tad_CantRecibida->Errors->ToString();
        $errors .= $this->tad_CantRechazada->Errors->ToString();
        $errors .= $this->tad_CantCaidas->Errors->ToString();
        $errors .= $this->tmp_CantEmbarcada->Errors->ToString();
        $errors .= $this->tad_ValUnitario->Errors->ToString();
        $errors .= $this->tmp_Adelanto->Errors->ToString();
        $errors .= $this->tmp_Bono->Errors->ToString();
        $errors .= $this->tmp_ValFruta->Errors->ToString();
        $errors .= $this->tmp_ValAdel->Errors->ToString();
        $errors .= $this->tmp_ValBono->Errors->ToString();
        $errors .= $this->tad_LiqProceso->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End dettarjas_list Class @24-FCB6E20C

class clsdettarjas_listDataSource extends clsDBdatos {  //dettarjas_listDataSource Class @24-1605638B

//DataSource Variables @24-9C5CBE1C
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $tac_Semana;
    var $buq_Descripcion;
    var $emb_NumViaje;
    var $Label1;
    var $tac_Fecha;
    var $tad_NumTarja;
    var $tad_Secuencia;
    var $tad_CantDespachada;
    var $tad_CantRecibida;
    var $tad_CantRechazada;
    var $tad_CantCaidas;
    var $tmp_CantEmbarcada;
    var $tad_ValUnitario;
    var $tmp_Adelanto;
    var $tmp_Bono;
    var $tmp_ValFruta;
    var $tmp_ValAdel;
    var $tmp_ValBono;
    var $tad_LiqProceso;
//End DataSource Variables

//Class_Initialize Event @24-95EEF914
    function clsdettarjas_listDataSource()
    {
        $this->ErrorBlock = "Grid dettarjas_list";
        $this->Initialize();
        $this->tac_Semana = new clsField("tac_Semana", ccsInteger, "");
        $this->buq_Descripcion = new clsField("buq_Descripcion", ccsText, "");
        $this->emb_NumViaje = new clsField("emb_NumViaje", ccsInteger, "");
        $this->Label1 = new clsField("Label1", ccsText, "");
        $this->tac_Fecha = new clsField("tac_Fecha", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->tad_NumTarja = new clsField("tad_NumTarja", ccsInteger, "");
        $this->tad_Secuencia = new clsField("tad_Secuencia", ccsInteger, "");
        $this->tad_CantDespachada = new clsField("tad_CantDespachada", ccsFloat, "");
        $this->tad_CantRecibida = new clsField("tad_CantRecibida", ccsFloat, "");
        $this->tad_CantRechazada = new clsField("tad_CantRechazada", ccsFloat, "");
        $this->tad_CantCaidas = new clsField("tad_CantCaidas", ccsFloat, "");
        $this->tmp_CantEmbarcada = new clsField("tmp_CantEmbarcada", ccsFloat, "");
        $this->tad_ValUnitario = new clsField("tad_ValUnitario", ccsFloat, "");
        $this->tmp_Adelanto = new clsField("tmp_Adelanto", ccsFloat, "");
        $this->tmp_Bono = new clsField("tmp_Bono", ccsFloat, "");
        $this->tmp_ValFruta = new clsField("tmp_ValFruta", ccsFloat, "");
        $this->tmp_ValAdel = new clsField("tmp_ValAdel", ccsFloat, "");
        $this->tmp_ValBono = new clsField("tmp_ValBono", ccsFloat, "");
        $this->tad_LiqProceso = new clsField("tad_LiqProceso", ccsInteger, "");

    }
//End Class_Initialize Event

//SetOrder Method @24-F5D66D27
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_tac_Semana" => array("tac_Semana", ""), 
            "Sorter_buq_Descripcion" => array("buq_Descripcion", ""), 
            "Sorter_emb_FecZarpe" => array("tac_Fecha", ""), 
            "Sorter_tad_NumTarja" => array("tad_NumTarja", "")));
    }
//End SetOrder Method

//Prepare Method @24-B8E40115
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlliq_NumLiquida", ccsInteger, "", "", $this->Parameters["urlliq_NumLiquida"], -1, false);
    }
//End Prepare Method

//Open Method @24-1C306BC0
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*) FROM (((liqtarjacabec INNER JOIN liqtarjadetal ON liqtarjadetal.tad_NumTarja = liqtarjacabec.tar_NumTarja) INNER JOIN liqembarques ON liqtarjacabec.tac_RefOperativa = liqembarques.emb_RefOperativa) INNER JOIN liqcajas ON liqtarjadetal.tad_CodCaja = liqcajas.caj_CodCaja) INNER JOIN liqbuques ON liqembarques.emb_CodVapor = liqbuques.buq_CodBuque " .
        "WHERE tad_LiqNumero = " . $this->SQLValue($this->wp->GetDBValue("1"), ccsInteger) . "";
        $this->SQL = "SELECT tad_NumTarja, tad_Secuencia, tad_CantDespachada,  " .
        "tad_CantRecibida, tad_CantRechazada, tad_CantCaidas,  " .
        "tad_ValUnitario, tad_DifUnitario, tad_LiqProceso,  " .
        "if(tad_DifUnitario>0, tad_DifUnitario,0) as tmp_Adelanto, " .
        "if(tad_DifUnitario<=0, tad_DifUnitario * (-1),0) as tmp_Bono, " .
        "tad_Observaciones, tac_Semana, emb_NumViaje, tac_Fecha,  " .
        "buq_Descripcion, caj_Abreviatura,  " .
        "tad_CantRecibida - tad_CantRechazada   AS tmp_CantEmbarcada, " .
        "ROUND(tad_ValUnitario * (tad_CantRecibida - tad_CantRechazada  ),2)  AS tmp_ValFruta, " .
        "ROUND(if(tad_DifUnitario>=0, tad_DifUnitario * (tad_CantRecibida - tad_CantRechazada  ),  0 ),2)  AS tmp_ValAdel, " .
        "ROUND(if(tad_DifUnitario<0, tad_DifUnitario * (tad_CantRecibida - tad_CantRechazada  ) * (-1),  0 ),2)  AS tmp_ValBono " .
        "FROM (((liqtarjacabec INNER JOIN liqtarjadetal ON liqtarjadetal.tad_NumTarja = liqtarjacabec.tar_NumTarja) INNER JOIN liqembarques ON liqtarjacabec.tac_RefOperativa = liqembarques.emb_RefOperativa) INNER JOIN liqcajas ON liqtarjadetal.tad_CodCaja = liqcajas.caj_CodCaja) INNER JOIN liqbuques ON liqembarques.emb_CodVapor = liqbuques.buq_CodBuque " .
        "WHERE tad_LiqNumero = " . $this->SQLValue($this->wp->GetDBValue("1"), ccsInteger) . "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue($this->CountSQL, $this);
        $this->query(CCBuildSQL($this->SQL, "", $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @24-86C0E1BA
    function SetValues()
    {
        $this->tac_Semana->SetDBValue(trim($this->f("tac_Semana")));
        $this->buq_Descripcion->SetDBValue($this->f("buq_Descripcion"));
        $this->emb_NumViaje->SetDBValue(trim($this->f("emb_NumViaje")));
        $this->Label1->SetDBValue($this->f("caj_Abreviatura"));
        $this->tac_Fecha->SetDBValue(trim($this->f("tac_Fecha")));
        $this->tad_NumTarja->SetDBValue(trim($this->f("tad_NumTarja")));
        $this->tad_Secuencia->SetDBValue(trim($this->f("tad_Secuencia")));
        $this->tad_CantDespachada->SetDBValue(trim($this->f("tad_CantDespachada")));
        $this->tad_CantRecibida->SetDBValue(trim($this->f("tad_CantRecibida")));
        $this->tad_CantRechazada->SetDBValue(trim($this->f("tad_CantRechazada")));
        $this->tad_CantCaidas->SetDBValue(trim($this->f("tad_CantCaidas")));
        $this->tmp_CantEmbarcada->SetDBValue(trim($this->f("tmp_CantEmbarcada")));
        $this->tad_ValUnitario->SetDBValue(trim($this->f("tad_ValUnitario")));
        $this->tmp_Adelanto->SetDBValue(trim($this->f("tmp_Adelanto")));
        $this->tmp_Bono->SetDBValue(trim($this->f("tmp_Bono")));
        $this->tmp_ValFruta->SetDBValue(trim($this->f("tmp_ValFruta")));
        $this->tmp_ValAdel->SetDBValue(trim($this->f("tmp_ValAdel")));
        $this->tmp_ValBono->SetDBValue(trim($this->f("tmp_ValBono")));
        $this->tad_LiqProceso->SetDBValue(trim($this->f("tad_LiqProceso")));
    }
//End SetValues Method

} //End dettarjas_listDataSource Class @24-FCB6E20C

//Initialize Page @1-4CF8201A
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

$FileName = "LiLiLi_auxi.php";
$Redirect = "";
$TemplateFileName = "LiLiLi_auxi.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-71A8A0F0
$DBdatos = new clsDBdatos();

// Controls
$liquidacion = new clsGridliquidacion();
$dettarjas_list = new clsGriddettarjas_list();
$liquidacion->Initialize();
$dettarjas_list->Initialize();

// Events
include("./LiLiLi_auxi_events.php");
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

//Go to destination page @1-CDA9AAFD
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    $DBdatos->close();
    header("Location: " . $Redirect);
    exit;
}
//End Go to destination page

//Show Page @1-8EE60BC2
$liquidacion->Show();
$dettarjas_list->Show();
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
