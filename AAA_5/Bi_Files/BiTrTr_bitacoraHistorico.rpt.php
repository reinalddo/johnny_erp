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
$pHasta = fGetparam("pHasta",date('Y-m-d'));

$pUsuario = fGetparam("pUsuario",false);
$pCerrado = fGetparam("pCerrado",false);
$pTransito = fGetparam("pTransito",false);

/*para consultar los detalles*/
$sSql = "SELECT   cab.bit_codEmpresa AS bit_codEmpresa
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
		  ,det.bit_secuencia AS bit_secuencia
		  /*FECHA EN QUE EL DOCUMENTO FUE ENVIADO AL USUARIO ACTUAL*/
		  ,det.bit_fechaenvio AS fecEnvio
		  ,DATE_FORMAT(det.bit_fechaenvio,'%Y-%m-%d') AS FechaEnv
		  ,DATE_FORMAT(det.bit_fechaenvio,'%h:%i:%s %p') AS HoraEnv
		  /*--------------------------------------------------------------*/
		  ,det.bit_usuario AS bit_usuario
		  ,det.bit_estado AS bit_estado
		  ,est.par_Descripcion AS estado
		  /*FECHA EN QUE EL DOCUMENTO FUE RECIBIDO POR EL USUARIO ACTUAL*/
		  ,det.bit_fecharecibido AS bit_fecharecibido
		  ,DATE_FORMAT(det.bit_fecharecibido,'%Y-%m-%d') AS FechaRec
		  ,DATE_FORMAT(det.bit_fecharecibido,'%h:%i:%s %p') AS HoraRec
		  /*--------------------------------------------------------------*/
		  ,det.bit_movimiento AS bit_movimiento
		  ,mov.par_Descripcion AS movimiento
		  ,det.bit_observacion AS bit_observacion
		  ,det.bit_motivoRechazo AS bit_motivoRechazo
		  ,det.bit_usuariodestino AS bit_usuariodestino
		  ,det.bit_observacionenvio AS bit_observacionenvio
	  FROM bitacora cab
	  JOIN bitacoradetalle det ON det.bit_tipoDoc = cab.bit_tipoDoc AND det.bit_numDoc = cab.bit_numDoc AND det.bit_idAux = cab.bit_idAux and det.bit_registro = cab.bit_registro
	  JOIN conpersonas per ON per.per_CodAuxiliar = cab.bit_idAux
	  JOIN 09_base.genparametros est ON est.par_Clave = 'BITEST' AND est.par_Secuencia = det.bit_estado
	  LEFT JOIN 09_base.genparametros mov ON mov.par_Clave = 'BITMOV' AND mov.par_Secuencia = det.bit_movimiento
	  JOIN 09_base.genparametros doc ON doc.par_Clave = 'BITDOC' AND doc.par_Valor1 = det.bit_tipoDoc
	  WHERE cab.bit_anulado = 0
	  and cab.bit_tipoDoc = '".fGetparam("pTipoDoc",'FC')."'";

$sSql .= ($pnumdoc ? " and cab.bit_numDoc = ". $pnumdoc  : "  " );
$sSql .= ($pidAux ? " and cab.bit_idAux = ". $pidAux  : "  " );
$sSql .= ($pcodEmp ? " and cab.bit_codEmpresa = "."'".$pcodEmp ."'": "" );
$sSql .= ($pDesde ? " and cab.bit_fechaDoc between "."'". $pDesde ."'" : "  " );
$sSql .= ($pHasta ? " and "."'". $pHasta ."'" : "  " );

$sSql .= ($pUsuario ? " and cab.bit_usuarioActual =  "."'". $pUsuario ."'" : "  " );
$sSql .= ($pCerrado ? " and cab.bit_cerrado =  ". $pCerrado : "  " );
$sSql .= ($pTransito ? " and cab.bit_cerrado = 0" :" " );


$sSql .= " order by cab.bit_empresa,cab.bit_tipoDoc,cab.bit_secDoc,cab.bit_emiDoc,cab.bit_numDoc,cab.bit_idAux,det.bit_secuencia";


$rs = $db->execute($sSql . $slFrom);
if($rs->EOF){
    fErrorPage('','NO HAY INFORMACION PARA GENERAR EL REPORTE', true,false);
}else{
    
    $rs->MoveFirst();
    $aDet =& SmartyArray($rs);
    $Tpl->assign("agData", $aDet);
    
    if (!$Tpl->is_cached('BiTrTr_bitacoraHistorico.tpl')) {
            }
    
            $Tpl->display('BiTrTr_bitacoraHistorico.tpl');
}
?>