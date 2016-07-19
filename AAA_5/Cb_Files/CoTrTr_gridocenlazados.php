<?php

ob_start("ob_gzhandler");
if (!isset ($_SESSION)) session_start();
require "GenUti.inc.php";
include_once "../LibPhp/NoCache.php";
error_reporting(E_ALL);
$gsSesVar="ocenlazados";
$Desde=fGetParam("FecI", false);
$Hasta=fGetParam("FecF", false);

if (fGetParam("init", false) != false) {
    $_SESSION[$gsSesVar]=NULL;
    $_SESSION[$gsSesVar]=
       "SELECT com_NumComp,0 seleccionar ,CONCAT('OC-',com_NumComp) AS Num,com_RegNumero AS RegNum,enl_FecRegistro AS FecEnl, enl_Usuario AS usuario
        FROM 09_inventario.invenlace
		JOIN concomprobantes ON enl_RegNumero=com_RegNumero
		where enl_CodEmpresa='". $_SESSION['g_dbase'] ."'
		GROUP BY com_NumComp
		ORDER BY com_NumComp
	    LIMIT {start}, {limit}";
//echo($_SESSION[$gsSesVar]);
//die();
	//$_SESSION[$gsSesVar . '_defs']['det_Usuario'] = $_SESSION['g_user'];
	$_SESSION[$gsSesVar . '_defs']['cnt_Usuario'] = $_SESSION['g_user'];
    $_SESSION[$gsSesVar . '_defs']['cnt_FecRegistro'] = '@date("Y-m-d H:i:s")';
    $loadJSFile = "CoTrTr_gridocenlazados.js";
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
                    return ((v === 0 || v > 1) ? '(' + v +' Contenedores)' : '(1 Contenedor)');
                }";
            $alFieldsOpt = array();
            $this->getRecordset(); // populates metadata
            $goGrid->metaData = $this->metaData;
            $goGrid->setGlobalOpt("hidden", 1);
            $this->totalProperty="totalCount";
            $goGrid->colWidthFlag= true;
            $goGrid->setFieldOpt('seleccionar',      array("header"=>"Sel.", "hidden"=>0,"width"=>5, "renderer"=>"check"));
            $goGrid->setFieldOpt('com_NumComp',      array("header"=>"Num. Comp.", "hidden"=>1,"width"=>45));
            $goGrid->setFieldOpt('Num',              array("header"=>"Comprobante", "hidden"=>0, "width"=>45));    
            $goGrid->setFieldOpt('RegNum',           array("header"=>"Reg Num", "hidden"=>1, "width"=>15)); 
            $goGrid->setFieldOpt('FecEnl',           array("header"=>"Fecha de Enlace", "hidden"=>0, "width"=>45));
            $goGrid->setFieldOpt('usuario',          array("header"=>"Usuario", "hidden"=>0, "width"=>45));
            $this->metaData = $goGrid->processMetaData($this->metaData); // computes options for grid
        }
    }
    $goGrid = new clsExtGrid("detGrid");
    $goData = new clsQueryGrid("rows", true, $gsSesVar);
    $goData->setMetadataFlag(true);
    $goData->getJson();
    ob_end_flush();
}