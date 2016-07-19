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
$gsSesVar="conSalCxc";
$Sem=fGetParam('Sem', false);
if (fGetParam("init", false) != false) {
    /* Query principal */
    $_SESSION[$gsSesVar]=NULL;
    $_SESSION[$gsSesVar]=
    "SELECT tac_Semana, tar_NumTarja, CONCAT(pr.per_Apellidos,' ',pr.per_Nombres) as aux_nombre
    , concat(caj_Abreviatura , \" - \", cte_referencia) as txt_empaque 
    FROM liqtarjacabec tc 
    LEFT JOIN liqembarques em ON em.emb_RefOperativa = tc.tac_RefOperativa 
    LEFT JOIN liqbuques bu ON em.emb_CodVapor = bu.buq_CodBuque  
    LEFT JOIN liqtarjadetal td ON td.tad_NumTarja = tc.tar_NumTarja  
    LEFT JOIN conactivos it ON td.tad_CodProducto = it.act_CodAuxiliar  
    LEFT JOIN liqcajas ON td.tad_CodCaja = liqcajas.caj_CodCaja  
    LEFT JOIN conperiodos pe ON per_aplicacion = 'LI' AND tc.tac_Semana = pe.per_NumPeriodo  
    LEFT JOIN conpersonas pr ON tc.tac_Embarcador = pr.per_CodAuxiliar
    LEFT JOIN liqcomponent cm ON cte_codigo = tad_codCompon2
    where tac_Semana=".$Sem." AND {filter}
    GROUP BY 1,2/*2,4 */
    ORDER BY tac_Semana desc, {sort} {dir}
    LIMIT {start}, {limit}";
    // @TODO: Hacer que la semana sea un parametro enviado al datasource no a este script
    $_SESSION[$gsSesVar . '_defs']['cnt_Usuario'] = $_SESSION['g_user'];
    $_SESSION[$gsSesVar . '_defs']['cnt_FecRegistro'] = '@date("Y-m-d H:i:s")';
    $loadJSFile = "LiEmTj_Tarjacons";
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
            $goGrid->setFieldOpt('tar_NumTarja', array("header"=>"NumTarja", "hidden"=>0, "width"=>10));
            $goGrid->setFieldOpt('tac_Semana', array("header"=>"Semana", "hidden"=>0, "width"=>10, "renderer"=>"intSimple"));
            $goGrid->setFieldOpt('tac_Fecha', array("header"=>"Fecha", "hidden"=>0,"width"=>25));//,"renderer"=>"fRenderDate"));
            $goGrid->setFieldOpt('aux_nombre', array("header"=>"Productor", "hidden"=>0,"width"=>100));
            $goGrid->setFieldOpt('buq_Descripcion', array("header"=>"Embarque", "hidden"=>0, "width"=>100, "renderer"=>"floatPosNeg"));
            $goGrid->setFieldOpt('txt_empaque', array("header"=>"Empaque", "hidden"=>0,"width"=>80));
            $this->metaData = $goGrid->processMetaData($this->metaData); // computes options for grid
        }
    }
    $goGrid = new clsExtGrid("cntGrid");
    $goData = new clsQueryGrid("rows", true, $gsSesVar);
    $goData->setMetadataFlag(true);
    $goData->getJson();
    ob_end_flush();
}