<?php
/** 
*   Grid para presentar datos de CXC
*   Utiliza una plantilla Html con  la estructura Basica del un grid ext, en la que se
*   sustituyen los valores requeridos por este script
*   El codigo JS siempre debe colocarse en un archivo con el mismo nombre de este script, pero
*   con extension js.
*   Define inicialmente las instrucciones Sql que debe aplicarse, grabandolas en vars de sesion
**/
ob_start("ob_gzhandler");
if (!isset ($_SESSION)) session_start();
require "GenUti.inc.php";
include_once "../LibPhp/NoCache.php";
error_reporting(E_ALL);
$gsSesVar="conSalCtaDet";
if (fGetParam("init", false) != false) {
    /* Query principal */
    $_SESSION[$gsSesVar]=NULL;
    $_SESSION[$gsSesVar]=
    "SELECT com_RegNumero AS 'REG', com_TipoComp, com_NumComp, det_NumCheque, com_FecContab,
    com_Receptor AS 'beneficiario', det_Glosa AS 'com_Concepto', det_ValDebito,  det_ValCredito
    FROM concomprobantes 
    JOIN condetalle on (det_RegNumero = com_RegNumero) 
    LEFT JOIN conpersonas p ON (p.per_CodAuxiliar = det_IdAuxiliar) 
    LEFT JOIN conactivos ON (act_CodAuxiliar = det_IdAuxiliar) 
    LEFT JOIN concuentas ON (cue_codcuenta = det_codcuenta) 
    where det_CodCuenta='".fGetParam("pConsCta", 0)."' AND det_IDAuxiliar=".fGetParam("pConsAux", 0)."
        AND det_NumCheque like '".fGetParam("pConsNumCheque", "")."%'
        and {filter}
    ORDER BY det_CodCuenta, det_IDauxiliar, com_FecContab desc, com_TipoComp, com_NumComp  
            /*, {sort} {dir}*/
    LIMIT {start}, {limit}";
    //echo $_SESSION[$gsSesVar];
    // @TODO: Hacer que la semana sea un parametro enviado al datasource no a este script
    $_SESSION[$gsSesVar . '_defs']['cnt_Usuario'] = $_SESSION['g_user'];
    $_SESSION[$gsSesVar . '_defs']['cnt_FecRegistro'] = '@date("Y-m-d H:i:s")';
    $loadJSFile = "CoRtTr_Anexocons";
    
    require "../Ge_Files/GeGeGe_loadgrid.php";
    }
else {    
    require('../LibPhp/extAutoGrid.class.php');
    require('../LibPhp/queryToJson.class.php');
    require('../LibPhp/NoCache.php');
    //$db->debug = $_SESSION['pAdoDbg'] >= 2;
    header("Content-Type: text/html;  charset=ISO-8859-1");
    /*
     *	Derivated class to implement a variation on metadata (adding field options) before output
     *
     */
    
    class clsQueryGrid extends clsQueryToJson {
        function init(){
            $this->metaDataFlag = true;
        }
        function beforeGetData(){
            global $goGrid;
            $slRendFunc="function(v, params, data){
                    return ((v === 0 || v > 1) ? '(' + v +' Tarjas)' : '(1 TarjaS)');
                }";
            $alFieldsOpt = array();        
            $this->getRecordset(); // populates metadata
            $goGrid->metaData = $this->metaData;
            $goGrid->setGlobalOpt("hidden", 0);
            $this->totalProperty="totalCount";
            $goGrid->colWidthFlag= true;
            $goGrid->setFieldOpt('REG', array("header"=>"ID", "hidden"=>1, "width"=>10));
            $goGrid->setFieldOpt('com_TipoComp', array("header"=>"Tipo Comp.", "hidden"=>0, "width"=>4));
            $goGrid->setFieldOpt('com_NumComp', array("header"=>"Num.Comp.", "hidden"=>0,"width"=>10));
            $goGrid->setFieldOpt('det_NumCheque', array("header"=>"Cheque", "hidden"=>0, "width"=>8, "renderer"=>"intSimple"));
            $goGrid->setFieldOpt('com_FecContab', array("header"=>"Fecha", "hidden"=>0,"width"=>12));
            $goGrid->setFieldOpt('beneficiario', array("header"=>"Beneficiario", "Cod Prov."=>0, "width"=>10));
            $goGrid->setFieldOpt('com_Concepto', array("header"=>"Concepto", "hidden"=>0,"width"=>90));
            $goGrid->setFieldOpt('det_ValDebito', array("header"=>"Debito", "hidden"=>0,"width"=>10, "renderer"=>"floatPosNeg"));
            $goGrid->setFieldOpt('det_ValCredito', array("header"=>"Credito", "hidden"=>0,"width"=>10, "renderer"=>"floatPosNeg"));
            $this->metaData = $goGrid->processMetaData($this->metaData); // computes options for grid
        }
    }
    $goGrid = new clsExtGrid("cntGrid");
    $goData = new clsQueryGrid("rows", true, $gsSesVar);
    $goData->setMetadataFlag(true);
    $goData->getJson();
    ob_end_flush();
}