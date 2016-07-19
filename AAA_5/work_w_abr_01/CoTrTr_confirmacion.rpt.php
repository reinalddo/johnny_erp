<?php
/*    Reporte de confirmacion de cheques
 *
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
        $this->template_dir = 'template';
        $this->compile_dir = '../templates_c/';
        $this->config_dir = '../configs';
        $this->cache_dir = '../cache/';
        $this->compile_check = true;
        $this->debugging = false;
    }
}
$Tpl = new Smarty_AAA();
$Tpl->debugging =fGetparam("pAppDbg",false);

/*$sSql ="select distinct mpr_banco text, mpr_Cuenta cod
        from liqmagprodinscrip where mpr_Nombre like 'agro %'
        group by 1
        order by 1";
$rs = $db->execute($sSql);
$rs->MoveFirst();
while ($r = $rs->fetchRow()){
   $agCabeceras[] = $r['text'];
   $agCols[] = "c_" . $r['cod'];
   $agSumas[$r['cod']] = 0;
 }
$aCabeceras =& SmartyArray($rs);

$Tpl->assign("agCabeceras", $agCabeceras);
$Tpl->assign("agCols", $agCols);
$Tpl->assign("agSumas", $agSumas);*/
   

$sSql=
      "select mpr_codAuxiliar cnf_NumReg, '02/04/2009' cnf_Fecha, mpr_banco det_Banco,mpr_Ruc det_Cheque,
        'CH' det_TipoComp, mpr_Cuenta det_NumComp,mpr_Nombre txt_Nombre,12.30 det_Valor
        from liqmagprodinscrip where mpr_Nombre like 'agrop%'
        group by 3,4,7
        order by 3,4,7" ;
        
$rs = $db->execute($sSql . $slFrom);
$rs->MoveFirst();
$aDet =& SmartyArray($rs);
$Tpl->assign("agData", $aDet);

if (!$Tpl->is_cached('CoTrTr_confirmacion.tpl')) {
}

$Tpl->display('CoTrTr_confirmacion.tpl');
?>