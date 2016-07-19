<?php
/*    Reporte que muestra la ubicación de un documento
 *    @param   integer  bit_reptipoDoc     Tipo de Documento a consultar
 */
include_once("General.inc.php");
include_once("GenUti.inc.php");
include_once("adodb.inc.php");
include_once("adoConn.inc.php");
$db->debug=fGetparam("pAdoDbg",false);
require('Smarty.class.php');
class Smarty_AAA extends Smarty {
   function Smarty_AAA()
   {
        $this->Smarty();
        $this->template_dir = '../templates';
        $this->compile_dir = '../templates_c/';
        $this->config_dir = '../configs';
        $this->cache_dir = '../cache/';
        $this->compile_check = true;
        $this->debugging = false;
   }
}
/*
if (fGetparam("pExcel",false)){
   header("Content-Type:  application/vnd.ms-excel");
   header("Expires: 0");
   header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
}*/
include("../LibPhp/excelOut.php");

$Tpl = new Smarty_AAA();
$Tpl->debugging =fGetparam("pAppDbg",false);

$pnumdoc = fGetparam("pnumDoc",false);
$pidAux = fGetparam("pidAux",false);
$pcodEmp = fGetparam("pcodEmp",false);
$pDesde = fGetparam("pDesde",false);
//$pHasta = fGetparam("pHasta",date('Y-m-d'));
$pHasta = fGetparam("pHasta",false);
$pEntDesde = fGetparam("pEntDesde",false);
$pEntHasta = fGetparam("pEntHasta",false);

$pUsuario = fGetparam("pUsuario",false);
$pCerrado = fGetparam("pCerrado",false);
$pTransito = fGetparam("pTransito",false);


/*para consultar los detalles*/
$sSql = "SELECT    cab.bit_codEmpresa AS bit_codEmpresa
		  ,cab.bit_empresa AS bit_empresa
		  ,doc.par_Descripcion as tipoDocu
		  ,cab.bit_tipoDoc AS bit_tipoDoc
		  ,cab.bit_secDoc AS bit_secDoc
		  ,cab.bit_emiDoc AS bit_emiDoc
		  ,cab.bit_numDoc AS bit_numDoc
		  ,cab.bit_idAux AS bit_idAux
		  ,CONCAT(TRIM(per.per_Apellidos),TRIM(per.per_Nombres)) AS proveedor
		  ,cab.bit_fechaDoc AS bit_fechaDoc
		  ,cab.bit_valor AS bit_valor
		  ,cab.bit_usuarioActual AS bit_usuarioActual
		  ,'envio'
		  ,env.bit_usuario AS usrEnvia
		  ,env.bit_secuencia AS secEnv
		  ,env.bit_observacionenvio AS obsEnv
		  /*FECHA EN QUE EL DOCUMENTO FUE ENVIADO AL USUARIO ACTUAL*/
		  ,det.bit_fechaenvio AS fecEnvio
		  ,DATE_FORMAT(det.bit_fechaenvio,'%Y-%m-%d') AS FechaEnv
		  ,DATE_FORMAT(det.bit_fechaenvio,'%h:%i:%s %p') AS HoraEnv
		  /*--------------------------------------------------------------*/
		  /*FECHA EN QUE EL DOCUMENTO FUE RECIBIDO POR EL USUARIO ACTUAL*/
		  ,'recepcion'
		  ,det.bit_fecharecibido AS fecRecibido
		  ,DATE_FORMAT(det.bit_fecharecibido,'%Y-%m-%d') AS FechaRec
		  ,DATE_FORMAT(det.bit_fecharecibido,'%h:%i:%s %p') AS HoraRec
		  /*--------------------------------------------------------------*/
		  /*ENVIO A OTRO USUARIO QUE NO LO HAYA RECIBIDO AUN */
		  ,det.bit_usuariodestino AS usuarioDestino
		  ,destino.bit_fechaenvio as fecDestino
		  ,DATE_FORMAT(destino.bit_fechaenvio,'%Y-%m-%d') AS FechaDestino
		  ,DATE_FORMAT(destino.bit_fechaenvio,'%h:%i:%s %p') AS HoraDestino
		  /*--------------------------------------------------------------*/
		  ,det.bit_secuencia AS secRec
		  ,det.bit_usuario AS usrRec
		  ,est.par_Descripcion AS estado
		  ,det.bit_movimiento AS bit_movimiento
		  ,mov.par_Descripcion AS movimiento
		  ,det.bit_observacion AS bit_observacion
	 FROM bitacora cab
	 /*para fechas cuando fue enviado el documento*/
	 JOIN bitacoradetalle env ON env.bit_tipoDoc = cab.bit_tipoDoc AND env.bit_numDoc = cab.bit_numDoc AND env.bit_idAux = cab.bit_idAux and env.bit_registro = cab.bit_registro
				  AND env.bit_secuencia = (
							  SELECT CASE 
								  WHEN MAX(sec.bit_secuencia)= 1 THEN 1 /*Los doc q estan en recepcion tienen la misma fecha de envio y de recibido*/
								  ELSE (MAX(sec.bit_secuencia)-1) /*para los documentos que ya han sido enviados se busca la secuencia anterior*/
							  END secenv FROM bitacoradetalle sec
							  WHERE sec.bit_usuario = cab.bit_usuarioActual
							  AND sec.bit_tipoDoc = cab.bit_tipoDoc
							  AND sec.bit_numDoc = cab.bit_numDoc
							  AND sec.bit_idAux = cab.bit_idAux
							  AND sec.bit_registro = cab.bit_registro
							  )
	 /*para fechas cuando fue recibido el documento*/
	 JOIN bitacoradetalle det ON det.bit_tipoDoc = cab.bit_tipoDoc AND det.bit_numDoc = cab.bit_numDoc AND det.bit_idAux = cab.bit_idAux and det.bit_registro = cab.bit_registro
				  AND det.bit_secuencia = (
							  SELECT MAX(sec.bit_secuencia) FROM bitacoradetalle sec
							  WHERE sec.bit_usuario = cab.bit_usuarioActual
							  AND sec.bit_tipoDoc = cab.bit_tipoDoc
							  AND sec.bit_numDoc = cab.bit_numDoc
							  AND sec.bit_idAux = cab.bit_idAux
							  AND sec.bit_registro = cab.bit_registro
							  )
	 /*Si tiene documentos que estan pendientes de recibir por otros usuarios */	 
	 LEFT JOIN bitacoradetalle AS destino    ON destino.bit_tipoDoc = cab.bit_tipoDoc 
						AND destino.bit_secDoc	 = cab.bit_secDoc
						AND destino.bit_emiDoc	= cab.bit_emiDoc 
						AND destino.bit_numDoc = cab.bit_numDoc 
						AND destino.bit_idAux = cab.bit_idAux 
						AND destino.bit_registro = cab.bit_registro
						AND destino.bit_usuario	= det.bit_usuariodestino /*cuando el usuario chechaza el documento no se guarda el usuario como el usuario actual*/
						AND destino.bit_estado	= 1 /*PENDIENTE*/
	 JOIN conpersonas per ON per.per_CodAuxiliar = cab.bit_idAux
	 LEFT JOIN 09_base.genparametros est ON est.par_Clave = 'BITEST' AND est.par_Secuencia = det.bit_estado
	 LEFT JOIN 09_base.genparametros mov ON mov.par_Clave = 'BITMOV' AND mov.par_Secuencia = det.bit_movimiento
	 LEFT JOIN 09_base.genparametros doc ON doc.par_Clave = 'BITDOC' AND doc.par_Valor1 = det.bit_tipoDoc
	 WHERE cab.bit_anulado = 0
	 and cab.bit_tipoDoc = '".fGetparam("pTipoDoc",'FC')."'";


$sSql .= ($pnumdoc ? " and cab.bit_numDoc = ". $pnumdoc  : "  " );
$sSql .= ($pidAux ? " and cab.bit_idAux = ". $pidAux  : "  " );
$sSql .= ($pcodEmp ? " and cab.bit_codEmpresa = "."'".$pcodEmp ."'": "" );
$sSql .= ($pDesde ? " and cab.bit_fechaDoc >= "."'". $pDesde ."'" : "  " );
$sSql .= ($pHasta ? " and cab.bit_fechaDoc <= "."'". $pHasta ."'" : "  " );
$sSql .= ($pEntDesde ? " and DATE_FORMAT(det.bit_fechaenvio,'%Y-%m-%d') >= "."'". $pEntDesde ."'" : "  " );
$sSql .= ($pEntHasta ? " and DATE_FORMAT(det.bit_fechaenvio,'%Y-%m-%d') <= "."'". $pEntHasta ."'" : "  " );
$sSql .= ($pUsuario ? " and cab.bit_usuarioActual =  "."'". $pUsuario ."'" : "  " );
$sSql .= ($pCerrado ? " and cab.bit_cerrado =  ". $pCerrado : "  " );
$sSql .= ($pTransito ? " and cab.bit_cerrado = 0" :" " );



$sSql .= " order by cab.bit_empresa,cab.bit_tipoDoc,cab.bit_numDoc,cab.bit_idAux,cab.bit_fechaDoc";


$rs = $db->execute($sSql . $slFrom);
if($rs->EOF){
    fErrorPage('','NO HAY INFORMACION PARA GENERAR EL REPORTE', true,false);
}else{
    
    $rs->MoveFirst();
    $aDet =& SmartyArray($rs);
    $Tpl->assign("agData", $aDet);
    
    if (!$Tpl->is_cached('BiTrTr_bitacoraUbicacion_Detalle.tpl')) {
            }
    
            $Tpl->display('BiTrTr_bitacoraUbicacion_Detalle.tpl');
}
?>