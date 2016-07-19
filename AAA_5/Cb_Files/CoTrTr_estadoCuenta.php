<?php
/** 
*   Grid para presentar datos de estado de cuenta de una persona.
*   Utiliza una plantilla Html con  la estructura Basica del un grid ext, en la que se
*   sustituyen los valores requeridos por este script
*   El codigo JS siempre debe colocarse en un archivo con el mismo nombre de este script, pero
*   con extension js.
*   Define inicialmente las instrucciones Sql que debe aplicarse, grabandolas en vars de sesion
*   @author    Gina Franco
*   @date      27/Nov/09    
**/
ob_start("ob_gzhandler");
if (!isset ($_SESSION)) session_start();
require "GenUti.inc.php";
include_once "../LibPhp/NoCache.php";
error_reporting(E_ALL);
$gsSesVar="conSalCxc";
if (fGetParam("init", false) != false) {
    /* Query principal */
    $_SESSION[$gsSesVar]=NULL;
    
    $_SESSION[$gsSesVar]= "
        select com_TipoComp, com_NumComp, com_FecContab, det_ValDebito, det_ValCredito,
            com_Concepto, det_NumCheque,  /*com_RegNumero,*/ concat(com_usuario,', ',com_fecDigita) txt_usuario
        from concomprobantes c inner join condetalle d on c.com_RegNumero=d.det_RegNumero
        where det_NumCheque <> 0 and det_IDAuxiliar=".fGetParam("pAuxil", '0')." 
	and com_FecContab between '".fGetParam("fecIni", '2020-12-31')."' and '".fGetParam("fecFin", '2020-12-31')."'
         AND {filter}
	order by {sort} {dir}
        LIMIT {start}, {limit}";
    
    
    //echo $_SESSION[$gsSesVar];
    // @TODO: Hacer que la semana sea un parametro enviado al datasource no a este script
    $_SESSION[$gsSesVar . '_defs']['cnt_Usuario'] = $_SESSION['g_user'];
    $_SESSION[$gsSesVar . '_defs']['cnt_FecRegistro'] = '@date("Y-m-d H:i:s")';
    $loadJSFile = "CoTrTr_estadoCuenta";
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
                    return ((v === 0 || v > 1) ? '(' + v +' Personas)' : '(1 PersonaS)');
                }";
            $alFieldsOpt = array();        
            $this->getRecordset(); // populates metadata
            $goGrid->metaData = $this->metaData;
            $goGrid->setGlobalOpt("hidden", 1);
            $this->totalProperty="totalCount";
            $goGrid->colWidthFlag= true;
            $goGrid->setFieldOpt('com_TipoComp', array("header"=>"TIPO", "hidden"=>0, "width"=>5));
            $goGrid->setFieldOpt('com_NumComp', array("header"=>"NUM.COMP.", "hidden"=>0, "width"=>10));//, "renderer"=>"intSimple"));
            $goGrid->setFieldOpt('com_FecContab', array("header"=>"FECHA", "hidden"=>0,"width"=>15));
            $goGrid->setFieldOpt('det_ValDebito', array("header"=>"DEBITO", "hidden"=>0, "width"=>15, "renderer"=>"floatPosNeg"));
            $goGrid->setFieldOpt('det_ValCredito', array("header"=>"CREDITO", "hidden"=>0, "width"=>15, "renderer"=>"floatPosNeg"));
            $goGrid->setFieldOpt('com_Concepto', array("header"=>"CONCEPTO", "hidden"=>0, "width"=>100));
            $goGrid->setFieldOpt('det_NumCheque', array("header"=>"CHEQUE", "hidden"=>0,"width"=>10));
            $goGrid->setFieldOpt('txt_usuario', array("header"=>"USUARIO", "hidden"=>0,"width"=>50));
            $this->metaData = $goGrid->processMetaData($this->metaData); // computes options for grid
        }
    }
    $goGrid = new clsExtGrid("cntGrid");
    $goData = new clsQueryGrid("rows", true, $gsSesVar);
    $goData->setMetadataFlag(true);
    $goData->getJson();
    ob_end_flush();
}