<?php
/** 
*   Grid para presentar transacciones que han sido ingresadas
**/
ob_start("ob_gzhandler");
if (!isset ($_SESSION)) session_start();
require "GenUti.inc.php";
include_once "../LibPhp/NoCache.php";
error_reporting(E_ALL);
$gsSesVar="consbitRec";
if (fGetParam("init", false) != false) {
    /* Query principal */
    $_SESSION[$gsSesVar]=NULL;
    
    // Sin restricción por usuario
    if (fGetParam("todusr", 0) == 1){
	$_SESSION[$gsSesVar . "_count"]= "select count(*) as totalRecs
			    FROM  bitacora cab
			    JOIN bitacoradetalle det  ON det.bit_tipoDoc = cab.bit_tipoDoc 
						    AND det.bit_secDoc = cab.bit_secDoc
						    AND det.bit_emiDoc = cab.bit_emiDoc
						    AND det.bit_numDoc = cab.bit_numDoc
						    AND det.bit_idAux = cab.bit_idAux
						    AND det.bit_registro = cab.bit_registro
						    AND  det.bit_secuencia = (SELECT MAX(d2.bit_secuencia) FROM bitacoradetalle d2  WHERE d2.bit_tipoDoc = det.bit_tipoDoc
									    AND d2.bit_secDoc = det.bit_secDoc
									    AND d2.bit_emiDoc = det.bit_emiDoc
									    AND d2.bit_numDoc = det.bit_numDoc
									    AND d2.bit_idAux = det.bit_idAux
									    AND d2.bit_registro = det.bit_registro)
			    JOIN conpersonas ON per_CodAuxiliar = cab.bit_idAux
			    LEFT JOIN 09_base.genparametros mov ON mov.par_Clave = 'BITMOV' AND mov.par_Secuencia = det.bit_movimiento
			    LEFT JOIN 09_base.genparametros est ON est.par_Clave = 'BITEST' AND est.par_Secuencia = det.bit_estado
			    /*Todos los documentos que estan pendientes de recibir o los que fueron rechazados*/
			    where det.bit_estado in('1','3')
			    and cab.bit_cerrado = 0
			    and cab.bit_anulado = 0
			    and DATE_FORMAT(cab.bit_fechaReg,'%Y-%m-%d') between '".fgetparam('fecha_desde')."' and '".fgetparam('fecha_hasta')."'
			    ";
	
	$_SESSION[$gsSesVar]= "select  cab.bit_empresa as bit_empresa
				    ,cab.bit_tipoDoc as bit_tipoDoc
				    ,cab.bit_secDoc as bit_secDoc
				    ,cab.bit_emiDoc as bit_emiDoc
				    ,cab.bit_numDoc as bit_numDoc
				    ,CONCAT(per_Apellidos,' ',per_Nombres) AS AuxNombre
				    ,DATE_FORMAT(cab.bit_fechaDoc,'%Y-%m-%d') AS bit_fechaDoc
				    ,cab.bit_valor as bit_valor
				    ,DATE_FORMAT(cab.bit_fechaReg,'%Y-%m-%d %h:%i:%s') AS bit_fechaReg
				    ,DATE_FORMAT(cab.bit_fechaReg,'%Y-%m-%d') AS FechaReg
				    ,DATE_FORMAT(cab.bit_fechaReg,'%h:%i:%s %p') AS HoraReg
				    ,cab.bit_usuarioActual as bit_usuarioActual
				    ,est.par_Descripcion AS estado
				    ,DATE_FORMAT(det.bit_fechaenvio,'%Y-%m-%d %h:%i:%s') AS bit_fechaenvio
				    ,DATE_FORMAT(det.bit_fechaenvio,'%Y-%m-%d') AS FechaEnv
				    ,DATE_FORMAT(det.bit_fechaenvio,'%h:%i:%s %p') AS HoraEnv
				    ,det.bit_usuario as bit_usuario
				    ,DATE_FORMAT(det.bit_fecharecibido,'%Y-%m-%d %h:%i:%s') AS bit_fecharecibido
				    ,DATE_FORMAT(det.bit_fecharecibido,'%Y-%m-%d') AS FechaRecib
				    ,DATE_FORMAT(det.bit_fecharecibido,'%h:%i:%s %p') AS HoraRecib
				    ,mov.par_Descripcion AS movimiento
				    ,det.bit_observacion as bit_observacion
				    ,det.bit_motivoRechazo as bit_motivoRechazo
				    ,det.bit_movimiento as bit_movimiento
				    ,cab.bit_idAux as bit_idAux
				    ,det.bit_estado as bit_estado
				    ,det.bit_secuencia as bit_secuencia
				    ,cab.bit_registro as bit_registro
			    FROM  bitacora cab
			    JOIN bitacoradetalle det  ON det.bit_tipoDoc = cab.bit_tipoDoc 
						    AND det.bit_secDoc = cab.bit_secDoc
						    AND det.bit_emiDoc = cab.bit_emiDoc
						    AND det.bit_numDoc = cab.bit_numDoc
						    AND det.bit_idAux = cab.bit_idAux
						    AND det.bit_registro = cab.bit_registro
						    AND  det.bit_secuencia = (SELECT MAX(d2.bit_secuencia) FROM bitacoradetalle d2  WHERE d2.bit_tipoDoc = det.bit_tipoDoc
									    AND d2.bit_secDoc = det.bit_secDoc
									    AND d2.bit_emiDoc = det.bit_emiDoc
									    AND d2.bit_numDoc = det.bit_numDoc
									    AND d2.bit_idAux = det.bit_idAux
									    AND d2.bit_registro = det.bit_registro)
			    JOIN conpersonas ON per_CodAuxiliar = cab.bit_idAux
			    LEFT JOIN 09_base.genparametros mov ON mov.par_Clave = 'BITMOV' AND mov.par_Secuencia = det.bit_movimiento
			    LEFT JOIN 09_base.genparametros est ON est.par_Clave = 'BITEST' AND est.par_Secuencia = det.bit_estado
			    /*Todos los documentos que estan pendientes de recibir o los que fueron rechazados*/
			    where det.bit_estado in('1','3')
			    and cab.bit_cerrado = 0
			    and cab.bit_anulado = 0
			    and DATE_FORMAT(cab.bit_fechaReg,'%Y-%m-%d') between '".fgetparam('fecha_desde')."' and '".fgetparam('fecha_hasta')."'
			    order by {sort} {dir} 
			    LIMIT {start}, {limit} ";
			    
    }
    else{ // Con restricción por usuario
	
	$_SESSION[$gsSesVar . "_count"]= "select count(*) as totalRecs
			    FROM  bitacora cab
			    JOIN bitacoradetalle det  ON det.bit_tipoDoc = cab.bit_tipoDoc 
						    AND det.bit_secDoc = cab.bit_secDoc
						    AND det.bit_emiDoc = cab.bit_emiDoc
						    AND det.bit_numDoc = cab.bit_numDoc
						    AND det.bit_idAux = cab.bit_idAux
						    AND det.bit_registro = cab.bit_registro
						    AND  det.bit_secuencia = (SELECT MAX(d2.bit_secuencia) FROM bitacoradetalle d2  WHERE d2.bit_tipoDoc = det.bit_tipoDoc
									    AND d2.bit_secDoc = det.bit_secDoc
									    AND d2.bit_emiDoc = det.bit_emiDoc
									    AND d2.bit_numDoc = det.bit_numDoc
									    AND d2.bit_idAux = det.bit_idAux
									    AND d2.bit_registro = det.bit_registro)
			    JOIN conpersonas ON per_CodAuxiliar = cab.bit_idAux
			    LEFT JOIN 09_base.genparametros mov ON mov.par_Clave = 'BITMOV' AND mov.par_Secuencia = det.bit_movimiento
			    LEFT JOIN 09_base.genparametros est ON est.par_Clave = 'BITEST' AND est.par_Secuencia = det.bit_estado
			    /*Todos los documentos que estan pendientes de recibir o los que fueron rechazados*/
			    where det.bit_estado in('1','3')
			    and cab.bit_cerrado = 0
			    and cab.bit_anulado = 0
			    and det.bit_usuario = '".$_SESSION['g_user']."'
			    and DATE_FORMAT(cab.bit_fechaReg,'%Y-%m-%d') between '".fgetparam('fecha_desde')."' and '".fgetparam('fecha_hasta')."'
			    ";
	
	$_SESSION[$gsSesVar]= "select  cab.bit_empresa as bit_empresa
				    ,cab.bit_tipoDoc as bit_tipoDoc
				    ,cab.bit_emiDoc as bit_emiDoc
				    ,cab.bit_secDoc as bit_secDoc
				    ,cab.bit_numDoc as bit_numDoc
				    ,CONCAT(per_Apellidos,' ',per_Nombres) AS AuxNombre
				    ,DATE_FORMAT(cab.bit_fechaDoc,'%Y-%m-%d') AS bit_fechaDoc
				    ,cab.bit_valor as bit_valor
				    ,DATE_FORMAT(cab.bit_fechaReg,'%Y-%m-%d %h:%i:%s') AS bit_fechaReg
				    ,DATE_FORMAT(cab.bit_fechaReg,'%Y-%m-%d') AS FechaReg
				    ,DATE_FORMAT(cab.bit_fechaReg,'%h:%i:%s %p') AS HoraReg
				    ,cab.bit_usuarioActual as bit_usuarioActual
				    ,est.par_Descripcion AS estado
				    ,DATE_FORMAT(det.bit_fechaenvio,'%Y-%m-%d %h:%i:%s') AS bit_fechaenvio
				    ,DATE_FORMAT(det.bit_fechaenvio,'%Y-%m-%d') AS FechaEnv
				    ,DATE_FORMAT(det.bit_fechaenvio,'%h:%i:%s %p') AS HoraEnv
				    ,det.bit_usuario as bit_usuario
				    ,DATE_FORMAT(det.bit_fecharecibido,'%Y-%m-%d %h:%i:%s') AS bit_fecharecibido
				    ,DATE_FORMAT(det.bit_fecharecibido,'%Y-%m-%d') AS FechaRecib
				    ,DATE_FORMAT(det.bit_fecharecibido,'%h:%i:%s %p') AS HoraRecib
				    ,mov.par_Descripcion AS movimiento
				    ,det.bit_observacion as bit_observacion
				    ,det.bit_motivoRechazo as bit_motivoRechazo
				    ,det.bit_movimiento as bit_movimiento
				    ,cab.bit_idAux as bit_idAux
				    ,det.bit_estado as bit_estado
				    ,det.bit_secuencia as bit_secuencia
				    ,cab.bit_registro as bit_registro
			    FROM  bitacora cab
			    JOIN bitacoradetalle det  ON det.bit_tipoDoc = cab.bit_tipoDoc 
						    AND det.bit_secDoc = cab.bit_secDoc
						    AND det.bit_emiDoc = cab.bit_emiDoc
						    AND det.bit_numDoc = cab.bit_numDoc
						    AND det.bit_idAux = cab.bit_idAux
						    AND det.bit_registro = cab.bit_registro
						    AND  det.bit_secuencia = (SELECT MAX(d2.bit_secuencia) FROM bitacoradetalle d2  WHERE d2.bit_tipoDoc = det.bit_tipoDoc
									    AND d2.bit_secDoc = det.bit_secDoc
									    AND d2.bit_emiDoc = det.bit_emiDoc
									    AND d2.bit_numDoc = det.bit_numDoc
									    AND d2.bit_idAux = det.bit_idAux
									    AND d2.bit_registro = det.bit_registro)
			    JOIN conpersonas ON per_CodAuxiliar = cab.bit_idAux
			    LEFT JOIN 09_base.genparametros mov ON mov.par_Clave = 'BITMOV' AND mov.par_Secuencia = det.bit_movimiento
			    LEFT JOIN 09_base.genparametros est ON est.par_Clave = 'BITEST' AND est.par_Secuencia = det.bit_estado
			    /*Todos los documentos que estan pendientes de recibir o los que fueron rechazados*/
			    where det.bit_estado in('1','3')
			    and cab.bit_cerrado = 0
			    and cab.bit_anulado = 0
			    and det.bit_usuario = '".$_SESSION['g_user']."'
			    and DATE_FORMAT(cab.bit_fechaReg,'%Y-%m-%d') between '".fgetparam('fecha_desde')."' and '".fgetparam('fecha_hasta')."'
			    order by {sort} {dir} 
			    LIMIT {start}, {limit} ";
    }
    
    
    
    $_SESSION[$gsSesVar . '_defs']['cnt_Usuario'] = $_SESSION['g_user'];
    $_SESSION[$gsSesVar . '_defs']['cnt_FecRegistro'] = '@date("Y-m-d H:i:s")';
    $loadJSFile = "BiTrTr_bitacoraRecibir";
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
	    $this->countFlag = 2;
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
            $this->totalProperty="recordCount";
            $goGrid->colWidthFlag= true;
	    
	    $goGrid->setFieldOpt('bit_empresa', array("header"=>"Empresa", "hidden"=>0, "width"=>15));
            $goGrid->setFieldOpt('bit_tipoDoc', array("header"=>"Tipo", "hidden"=>0, "width"=>6));
	    $goGrid->setFieldOpt('bit_secDoc', array("header"=>"Secuencial", "hidden"=>0, "width"=>15));
	    $goGrid->setFieldOpt('bit_emiDoc', array("header"=>"Punto Emision", "hidden"=>0, "width"=>15));
	    $goGrid->setFieldOpt('bit_numDoc', array("header"=>"Documento", "hidden"=>0, "width"=>15));
	    $goGrid->setFieldOpt('AuxNombre',array("header"=>"Proveedor", "hidden"=>0, "width"=>100));
	    $goGrid->setFieldOpt('bit_fechaDoc',array("header"=>"Emision", "hidden"=>0, "width"=>15));
	    $goGrid->setFieldOpt('bit_valor', array("header"=>"Valor", "hidden"=>0, "width"=>10));
	    $goGrid->setFieldOpt('bit_usuarioActual',array("header"=>"Envia:", "hidden"=>0, "width"=>10));
	    $goGrid->setFieldOpt('estado',array("header"=>"Estado", "hidden"=>0, "width"=>15));
	    $goGrid->setFieldOpt('FechaEnv',array("header"=>"Fecha Envio", "hidden"=>0, "width"=>15));
	    $goGrid->setFieldOpt('HoraEnv',array("header"=>"Hora Envio", "hidden"=>0, "width"=>15));
	    $goGrid->setFieldOpt('bit_usuario',array("header"=>"Recibe:", "hidden"=>0, "width"=>10));
	    $goGrid->setFieldOpt('bit_registro',array("header"=>"Registro:", "hidden"=>1, "width"=>10));
	    
            $this->metaData = $goGrid->processMetaData($this->metaData); 
        }
    }
    $goGrid = new clsExtGrid("bitRecib");
    $goData = new clsQueryGrid("rows", true, $gsSesVar);
    $goData->setMetadataFlag(true);
    $goData->getJson();
    ob_end_flush();
}

?>