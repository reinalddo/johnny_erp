<?php
/** 
*   Grid para presentar transacciones que han sido ingresadas
**/
ob_start("ob_gzhandler");
if (!isset ($_SESSION)) session_start();
require "GenUti.inc.php";
include_once "../LibPhp/NoCache.php";
error_reporting(E_ALL);
$gsSesVar="consReemb";
if (fGetParam("init", false) != false) {
    /* Query principal */
    $ptra_Estado = fGetParam("pEstado", "ree_Estado");
    $_SESSION[$gsSesVar]=NULL;
    
    $tipoCons = fGetParam("tipCons", 'U');
    
    if ($tipoCons == "T") {
    
		$_SESSION[$gsSesVar]= "SELECT   ree_Id, 
						ree_Emisor, 
						CONCAT(per_Apellidos, ' ', per_Nombres) AS Emisor,
						ree_Concepto, 
						ree_RefOperat, 
						DATE_FORMAT(ree_Fecha,'%Y-%m-%d') as ree_Fecha, 
						ree_Valor, 
						ree_Estado,
						par_Descripcion AS Estado, 
						ree_Usuario, 
						ree_UsuAprueba, 
						ree_FecAprueba, 
						ree_TipoComp, 
						ree_NumComp, 
						ree_FechaRegistro 
					FROM conReembolso
					LEFT JOIN genparametros ON par_Clave = 'CADEST' AND par_Valor4 = 'REEMB' AND par_Valor1 = ree_Estado
					LEFT JOIN conpersonas ON per_CodAuxiliar = ree_Emisor
					where ree_Estado in ($ptra_Estado)
					/* and ree_Estado not in(3) */ /* anulado */
					and {filter}
					order by {sort} {dir}
					LIMIT {start}, {limit}        ";
    }
    else{
	$_SESSION[$gsSesVar]= "SELECT   ree_Id, 
						ree_Emisor, 
						CONCAT(per_Apellidos, ' ', per_Nombres) AS Emisor,
						ree_Concepto, 
						ree_RefOperat, 
						DATE_FORMAT(ree_Fecha,'%Y-%m-%d') as ree_Fecha, 
						ree_Valor, 
						ree_Estado,
						par_Descripcion AS Estado, 
						ree_Usuario, 
						ree_UsuAprueba, 
						ree_FecAprueba, 
						ree_TipoComp, 
						ree_NumComp, 
						ree_FechaRegistro 
					FROM conReembolso
					LEFT JOIN genparametros ON par_Clave = 'CADEST' AND par_Valor4 = 'REEMB' AND par_Valor1 = ree_Estado
					LEFT JOIN conpersonas ON per_CodAuxiliar = ree_Emisor
					where ree_Estado in ($ptra_Estado)
					AND ree_Usuario = '".$_SESSION['g_user']."' 
					/* and ree_Estado not in(3) */ /* anulado */
					and {filter}
					order by {sort} {dir}
					LIMIT {start}, {limit}        ";
    }
    
    
    $_SESSION[$gsSesVar . '_defs']['cnt_Usuario'] = $_SESSION['g_user'];
    $_SESSION[$gsSesVar . '_defs']['cnt_FecRegistro'] = '@date("Y-m-d H:i:s")';
    $loadJSFile = "CoAdFi_ReembolsoAprobar";
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
            $goGrid->setFieldOpt('ree_Id', array("header"=>"Id. Solicitud", "hidden"=>0, "width"=>10));
	    $goGrid->setFieldOpt('ree_Emisor',array("header"=>"Cod. Emisor", "hidden"=>0, "width"=>15));
	    $goGrid->setFieldOpt('Emisor',array("header"=>"Emisor", "hidden"=>0, "width"=>150));
	    $goGrid->setFieldOpt('ree_Fecha', array("header"=>"Fecha", "hidden"=>0, "width"=>40));
	    $goGrid->setFieldOpt('ree_Valor',array("header"=>"Valor", "hidden"=>0, "width"=>30));
	    $goGrid->setFieldOpt('Estado',array("header"=>"Estado", "hidden"=>0, "width"=>60));
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