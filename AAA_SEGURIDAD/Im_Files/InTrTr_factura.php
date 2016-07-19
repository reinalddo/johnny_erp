<?php
/*    Factura Formato HTML
   @rev fah  18/11/2010  Incorporar doble plantilla hgtml, para formato en blanco, sin rayado. Por omision. si pFmto=2, aplicar el pre impreso
   @rev fah  08/12/2010  Incorporar plantilla html, para formato A4. si pFmto=3, aplicar el A4
   @rev esl  26/03/2012  Incorporar plantilla html, para formato pequeño - matricial. si pFmto=4, Factura para CODRIGNA - Washinton Neira
   @rev esl  28/03/2012  AGREGAR CALCULOS DEL IVA DEPENDIENDO DE act_IvaFlag , separar el valor de la factura en base 0 y base iva
 *
 */
//$FileName = "CoTrTr_productoresdet.rpt.php";
define("RelativePath", "..");
include_once("../LibPhp/GenCifras.php");
include_once("General.inc.php");
include_once("GenUti.inc.php");
include_once("adodb.inc.php");
$gbTrans	= false;
$db = Null;
$ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
$db = &ADONewConnection('mysql');
$db->autoRollback = true;
$db->debug = fGetParam("pAdoDbg",0);
$db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);
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

include("../LibPhp/excelOut.php");

$Tpl = new Smarty_AAA();
$Tpl->debugging =fGetparam("pAppDbg",false);
$glFlag= fGetParam('pEmpq', false);
$pQry = fGetParam('pQryCom','');
$numero = fGetParam('regNumero','');
//Para capturar la empresa que ingreso
$empresa = $_SESSION['g_empr'];

//$anio = fGetParam('s_Anio',date('Y'));
//$mes = fGetParam('s_Mes',date('m'));

//if ($anio == '') $anio = date('Y');
//if ($mes == '') $mes = date('m');

//$subtitulo = fGetParam('pCond',"Año: ".$anio." - Mes: ".$mes);
$Tpl->assign("subtitulo",$subtitulo);
$ilFmto = $db->GetOne("SELECT par_valor1 FROM genparametros WHERE par_clave='EGFFA'");
$slLeng = 25;
if ($ilFmto == 3) $slLeng = 60;
if ($ilFmto == 4) $slLeng = 60; //Codrigna
/*para consultar los detalles*/
$sSql = "SELECT com_tipocomp AS TIPO, com_numcomp AS COMPR, det_secuencia  AS SECUE, com_concepto AS CONCEP
            , com_feccontab AS FECHA
            , CASE MONTH(com_FecContab)
			WHEN  1 THEN DATE_FORMAT(com_FecContab, '%d de Enero de %Y')
			WHEN  2 THEN DATE_FORMAT(com_FecContab, '%d de Febrero de %Y')
			WHEN  3 THEN DATE_FORMAT(com_FecContab, '%d de Marzo de %Y')
			WHEN  4 THEN DATE_FORMAT(com_FecContab, '%d de Abril de %Y')
			WHEN  5 THEN DATE_FORMAT(com_FecContab, '%d de Mayo de %Y')
			WHEN  6 THEN DATE_FORMAT(com_FecContab, '%d de Junio de %Y')
			WHEN  7 THEN DATE_FORMAT(com_FecContab, '%d de Julio de %Y')
			WHEN  8 THEN DATE_FORMAT(com_FecContab, '%d de Agosto de %Y')
			WHEN  9 THEN DATE_FORMAT(com_FecContab, '%d de Septiembre de %Y')
			WHEN 10 THEN DATE_FORMAT(com_FecContab, '%d de Octubre de %Y')
			WHEN 11 THEN DATE_FORMAT(com_FecContab, '%d de Noviembre de %Y')
			WHEN 12 THEN DATE_FORMAT(com_FecContab, '%d de Diciembre de %Y')
               END AS FECHALetra /*Para Asisbane*/
            , com_refoperat AS REFOP, com_emisor AS CODEM/*, 
            concat(b.per_Apellidos, ' ', b.per_nombres) as BODEG*/,p.per_Ciudad as CIU, com_codreceptor AS CODRE, 
            concat(p.per_Apellidos, ' ', p.per_nombres) as RECEP, det_coditem AS CODIT, 
            left(concat(act_descripcion, ' ', act_descripcion1),$slLeng) as ITEM,
	    act_UniMedida, uni_Abreviatura, uni_Descripcion, 
	    det_candespachada AS CANTI, 
            det_cantequivale AS CANTE, uni_abreviatura AS UNIDA,  det_costotal AS COSTO
            ,det_ValUnitario vunit
            ,p.per_direccion direccion, p.per_ciudad ciudad, p.per_telefono1 telefono,
            p.per_ruc ruc,
	    
	    /* AGREGAR CALCULOS DEL IVA DEPENDIENDO DE act_IvaFlag */
	    
	    /* det_valtotal AS VALOR, act_IvaFlag iva, */
            /* Si el IVA es incluido debe descontarse, si es imponible debe calcularse */
            
            round((case act_IvaFlag
               when 3 /*incluido*/ then round((round(det_valtotal,2) / 1.12),2)
               when 2 /*imponible*/ then round(det_valtotal,2)
               else round(det_valtotal,2)
            end),2) as VALOR,
	    
	    /* BASES SEPARADAS */
	    round((case act_IvaFlag
               when 3 /*incluido*/ then round((round(det_valtotal,2) / 1.12),2)
               when 2 /*imponible*/ then round(det_valtotal,2)
               else 0
            end),2) as BASEIMP,
	    round((case act_IvaFlag
               when 3 /*incluido*/ then 0
               when 2 /*imponible*/ then 0
               else round(det_valtotal,2)
            end),2) as BASE0,
            
            /* Para sacar el IVA primero hay que hacer el descuento */
            case act_IvaFlag
               when 3 /*incluido*/ then (/*valor:*/ round((round(det_valtotal,2) / 1.12),2) - /*descuento*/ (round((round(det_valtotal,2) / 1.12),2) * (ifnull(det_Destino,0)/100))) * 0.12
               when 2 /*imponible*/ then (/*valor:*/ round(det_valtotal,2) - /*descuento*/ (round(det_valtotal,2) * (ifnull(det_Destino,0)/100))) * 0.12
               else 0
            end as VALiva,
            
            round((case act_IvaFlag
               when 3 /*incluido*/ then (round((round(det_valtotal,2) / 1.12),2) * (ifnull(det_Destino,0)/100))
               when 2 /*imponible*/ then (round(det_valtotal,2) * (ifnull(det_Destino,0)/100))
               else (det_valtotal * (ifnull(det_Destino,0)/100))
            end),2) as VALDscto
	    
	    
	    
	    
            FROM genclasetran 
            JOIN concomprobantes ON cla_aplicacion = 'IN' AND com_tipoComp = cla_tipoComp              
            LEFT JOIN conpersonas b ON b.per_codauxiliar = com_emisor              
            LEFT JOIN conpersonas p ON p.per_codauxiliar = com_codreceptor              
            LEFT JOIN invdetalle ON det_regnumero = com_regnumero              
            LEFT JOIN conactivos ON act_codauxiliar = det_coditem              
            LEFT JOIN genunmedida ON uni_CodUnidad = act_unimedida 
            WHERE ".$pQry."/*com_TipoComp='FA' AND com_NumComp=251*/
            ORDER  BY com_emisor, com_numcomp, com_tipocomp, det_secuencia DESC";

//echo $sSql;

$rs = $db->execute($sSql);
if($rs->EOF){
    fErrorPage('','NO SE GENERARON LOS DETALLES DE MOVIMIENTOS', true,false);
}else{
   while ($r = $rs->fetchRow()){
       $total += $r['VALOR'];
       $cadena= $r['CONCEP'];
       $iva += $r['VALiva'];
       $Dscto += $r['VALDscto'];
      
      /* if (2 == $r['iva']){
         $iva += $r['VALOR'];
       }   IVA VIENE DEL QUERY*/
    }
   $cnt=0; 
   for ($i=0; $i < strlen($cadena); $i++) {
      $caracter = substr($cadena, $i, 1);
      if (ord($caracter)==10) {
      $aux = $aux . "<br>";
      $cnt++;
      }
      else $aux = $aux . $caracter;
   }
   $cadena = $aux;
   $ind = 0;   
   if(strcmp($empresa,'COSTAFRUT S.A.') || strcmp($empresa,'AMENEGSA S.A.')|| strcmp($empresa,'LIGHTFRUIT S.A.')|| strcmp($empresa,'FORZAFRUT S.A.')|| strcmp($empresa,'MUNDIPAK S.A.'))
          $cnt = $cnt;
    else
         $cnt=0;
    while ($ind < 21-$cnt){
       $filas[$ind] = $ind + 1;
       $ind++;
    }
    $Tpl->assign("cantidad", $cnt);
    $Tpl->assign("concepto", $cadena);
    $Tpl->assign("agFilas", $filas);
   
/*    if ($iva > 0){
         $iva = ($iva * 0.12);
    }else $iva  = 0; IVA VIENE DEL QUERY */
   
    $total -= $Dscto;
    $total += $iva;
    
    //echo $total."------";
    $letras = num2letras($total, false, 2, 2, " Dolares", " ctvs. ");//num2letras($total,false,1,2);
    $Tpl->assign("letras", $letras);
    $Tpl->assign("iva", $iva);
    $Tpl->assign("valorTot", $total);
    $Tpl->assign("empresa",$empresa);
    
    $rs->MoveFirst();
    $aDet =& SmartyArray($rs);
    $Tpl->assign("agData", $aDet);
    

   switch ($ilFmto){
      case 2 :
         $slFmto = "InTrTr_factura.tpl";
         break;
      case 3 :
         $slFmto = "InTrTr_facturablancoA4.tpl"; //Formulario Smart NET
         break;
      case 4 :
         $slFmto = "InTrTr_facturacod.tpl"; //Formulario Codrigna
         break;
      case 5 :
         $slFmto = "InTrTr_facturaAsis.tpl"; //Formulario Asisbane
         break;
      case 6 :
         $slFmto = "InTrTr_facturaBalsh.tpl"; //Formulario Baloschi
         break;
      case 7 :
         $slFmto = "InTrTr_facturaSchaffry.tpl"; // Formato Alex Schaffry
         break;
      case 8 :
         $slFmto = "InTrTr_facturaEncoser.tpl"; // Formato Enconser
         break;
      case 9 :
         $slFmto = "InTrTr_facturablancoMLS.tpl"; // Formulario MLS96
         break;
      default :
         $slFmto = "InTrTr_facturablanco.tpl"; //CASE 1 es Formulario Default
   }
   if (!$Tpl->is_cached($slFmto)) {
   }
   $Tpl->display($slFmto);  
   /********if (fGetParam("pFmto", 1) == 1) {
      if (!$Tpl->is_cached('InTrTr_facturablanco.tpl')) {
              }
      
              $Tpl->display('InTrTr_facturablanco.tpl');  
   } else { 
      if (!$Tpl->is_cached('InTrTr_factura.tpl')) {
            }
             $Tpl->display('InTrTr_factura.tpl');
   }*/
}
?>