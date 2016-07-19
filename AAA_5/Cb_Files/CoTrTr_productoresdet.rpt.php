<?php
/*    Reporte de tarjas, detalle General por Puerto, Vapor, Marca. Formato HTML
 *    @param   integer  pSem     Numero de semana a procesar
 *    @param   integer  pEmb     Numero de Embarque
 *    @param   string   PMarca   Marca
 *
 */
//$FileName = "CoTrTr_productoresdet.rpt.php";
//include_once("../LibPhp/SegLib.php");
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
        $this->template_dir = 'template';
        $this->compile_dir = '../templates_c/';
        $this->config_dir = '../configs';
        $this->cache_dir = '../cache/';
        $this->compile_check = true;
        $this->debugging = false;
   }
}

include("../LibPhp/excelOut.php");

$Tpl = new Smarty_AAA();
$Tpl->debugging =fGetparam("pAppDbg",false);
$glFlag= fGetParam('pEmpq', false);
$pQry = fGetParam('pQryCom','');


//$anio = fGetParam('s_Anio',date('Y'));
//$mes = fGetParam('s_Mes',date('m'));

//if ($anio == '') $anio = date('Y');
//if ($mes == '') $mes = date('m');

//$subtitulo = fGetParam('pCond',"Año: ".$anio." - Mes: ".$mes);
$Tpl->assign("subtitulo",$subtitulo);


/*para consultar los detalles*/
$sSql = "select vau_codauxiliar, vau_descripcion, 
        MAX(if(vcf_nombre = 'ZONEMB', iab_valortex, '')) as 	'Zona_Corte',
        MAX(if(vcf_nombre = 'BANCO', iab_valortex, '')) as 		'BANCO ',
        MAX(if(vcf_nombre = 'NUMCTA', iab_valortex, '')) as 		'NUMERO_CTA_CTE',
        MAX(if(vcf_nombre = 'NUMINSCR', iab_valortex, '')) as 		'NUM_INSCR_MAGAP',
        MAX(if(vcf_nombre = 'TIPPAG', iab_valortex, '')) as 		'TIPO_PAGO',
        MAX(if(vcf_nombre = 'ZONPAG', iab_valortex, '')) as 		'ZONA_PAGO',
        MAX(if(vcf_nombre = 'BENALTE', iab_valortex, '')) as 		'BENEF_ALTERNO',
        MAX(if(vcf_nombre = 'TIPOCTA', iab_valortex, '')) as 		'TIPO_CUENTA'	
        from v_auxgeneralcate
        join  09_base.genvariables on iab_objetoID = vau_codauxiliar
        join  09_base.genvarconfig on vcf_ID =  iab_variableID
        where vau_categoria = 52
        GROUP BY 1,2
        ORDER BY 2";

//echo $sSql;

$rs = $db->execute($sSql);
if($rs->EOF){
    fErrorPage('','NO SE GENERARON LOS DETALLES DE MOVIMIENTOS', true,false);
}else{
    
    $rs->MoveFirst();
    $aDet =& SmartyArray($rs);
    $Tpl->assign("agData", $aDet);
    
    if (!$Tpl->is_cached('CoTrTr_productoresdet.tpl')) {
            }
    
            $Tpl->display('CoTrTr_productoresdet.tpl');
}
?>