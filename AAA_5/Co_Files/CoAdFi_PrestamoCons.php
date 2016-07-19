<?php
/** 
*   Grid para presentar transacciones que han sido ingresadas
**/
ob_start("ob_gzhandler");
if (!isset ($_SESSION)) session_start();
require "GenUti.inc.php";
include_once "../LibPhp/NoCache.php";
error_reporting(E_ALL);
$gsSesVar="consTrans";
if (fGetParam("init", false) != false) {
    /* Query principal */
    $ptra_Estado = fGetParam("pEstado", "tra_Estado");
    $_SESSION[$gsSesVar]=NULL;
    $_SESSION[$gsSesVar]= "select tra_Id,tra_Receptor,aux_nombre,tra_Cuotas,tra_Valor,mot.par_Descripcion transaccion,
				tra_Concepto, tra_RefOperat,
				tra_Motivo, tra_Fecha, tra_Estado,
				est.par_Descripcion estado, tra_Emisor
			   from genTransac c inner join conpersonas p on c.tra_Receptor=p.per_CodAuxiliar
			      JOIN v_conauxiliar on aux_codigo = per_CodAuxiliar
			      JOIN genparametros mot on
				   mot.par_clave = 'CLIBRO'
				   AND mot.PAR_VALOR4 = 'COMEX'
				   AND mot.par_Secuencia = tra_Motivo
			      JOIN genparametros est on
				   est.par_clave = 'CADEST'
				   AND est.PAR_VALOR4 = 'COMEX'
				   AND est.par_Valor1 = tra_Estado
			   where tra_Estado in ($ptra_Estado)
			   and {filter}
			   order by {sort} {dir}
			   LIMIT {start}, {limit}        ";
    
    $_SESSION[$gsSesVar . '_defs']['cnt_Usuario'] = $_SESSION['g_user'];
    $_SESSION[$gsSesVar . '_defs']['cnt_FecRegistro'] = '@date("Y-m-d H:i:s")';
    $loadJSFile = "CoAdFi_PrestamoCons";
    require "../Ge_Files/GeGeGe_loadgrid.php";   
    } 
else {
    require('../LibPhp/extAutoGrid.class.php');
    require('../LibPhp/queryToJson.class.php');
    require('../LibPhp/NoCache.php');
    header("Content-Type: text/html;  charset=ISO-8859-1");
    
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
            $this->getRecordset(); 
            $goGrid->metaData = $this->metaData;
            $goGrid->setGlobalOpt("hidden", 1);
            $this->totalProperty="totalCount";
            $goGrid->colWidthFlag= true;
            $goGrid->setFieldOpt('tra_Id', array("header"=>"Id. Trans.", "hidden"=>0, "width"=>10));
	    $goGrid->setFieldOpt('tra_Receptor',array("header"=>"Cod. Receptor", "hidden"=>0, "width"=>15));
	    $goGrid->setFieldOpt('aux_nombre',array("header"=>"Receptor", "hidden"=>0, "width"=>200));
	    $goGrid->setFieldOpt('tra_Cuotas', array("header"=>"Cuotas", "hidden"=>0, "width"=>5));
	    $goGrid->setFieldOpt('tra_Valor',array("header"=>"Valor", "hidden"=>0, "width"=>10));
	    $goGrid->setFieldOpt('transaccion',array("header"=>"Transaccion", "hidden"=>0, "width"=>30));
	    $goGrid->setFieldOpt('estado',array("header"=>"Estado", "hidden"=>0, "width"=>20));
            $this->metaData = $goGrid->processMetaData($this->metaData); 
        }
    }
    $goGrid = new clsExtGrid("cntGrid");
    $goData = new clsQueryGrid("rows", true, $gsSesVar);
    $goData->setMetadataFlag(true);
    $goData->getJson();
    ob_end_flush();
}

?>