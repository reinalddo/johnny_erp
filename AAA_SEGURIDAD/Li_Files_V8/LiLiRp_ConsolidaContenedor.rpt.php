<?php
/*    Reporte Consolidado de Contenedores - Frutiboni
 */

ob_start("ob_gzhandler");
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

include("../LibPhp/excelOut.php");

$Tpl = new Smarty_AAA();
$Tpl->debugging =fGetparam("pAppDbg",false);
$glFlag= fGetParam('pEmpq', false);

// parametro para el query general
$pQry = fGetParam('pQryTar',false);
$subtitulo2 = fGetParam('pCond',false);


// Parametros individuales para el query
$semana = fGetParam('tac_Semana',false);
$embarcador = fGetParam('tac_Embarcador',false);
$producto = fGetParam('tad_CodProducto',false);
$vapor = fGetParam('emb_CodVapor',false);
$anioOpe = fGetParam('emb_AnoOperacion',false);
$SemInicio = fGetParam('emb_SemInicio',false);
$SemTermino = fGetParam('emb_SemTermino',false);
$Destino = fGetParam('emb_Destino',false);
$Consignatario = fGetParam('emb_Consignatario',false);



//$subtitulo2 .= ($semana ? " Semana: ". $semana  : "  " );
//$subtitulo2 .= ($embarcador ? " Productor: ". $embarcador  : "  " );

/*if(!$semana){
    fErrorPage('','INGRESE UNA SEMANA PARA GENERAR EL REPORTE', true,false);
}*/

$subtitulo = fGetParam('pCond','');
$subtitulo="REPORTE DE CONSOLIDACION DE CONTENEDORES";
//$Tpl->assign("subtitulo",$subtitulo);

//$subtitulo2 .= ($com_codReceptor ? " Productor: ". $com_codReceptor  : "  " );

$Tpl->assign("subtitulo2",$subtitulo2);

/*para consultar los detalles*/
$sSql = " SELECT   t.tar_NumTarja
		  /* , CONCAT('Vapor',IFNULL(emb.emb_CodVapor,''),'CONTE',IFNULL(t.tac_Contenedor,'')) AS GrpCont */
		  ,CONCAT(IFNULL(emb.emb_CodVapor,''),IFNULL(emb.emb_Descripcion1,''), IFNULL(tac_Semana,''), IFNULL(emb.emb_CodPuerto,''), IFNULL(emb_Consignatario,''), IFNULL(emb_codpais,''), emb_FecInicio, emb_FecTermino, IFNULL(t.tac_Contenedor,''),ifnull(t.tac_Sello,''),ifnull(t.tac_selloempresa,'') , ifnull(t.tac_selloAntNarc1,''), ifnull(t.tac_selloAntNarc2,''),ifnull(d.tad_CodProducto,''),ifnull(t.tac_Paletizado,'')) AS GrpCont
		  ,CONCAT(IFNULL(emb.emb_CodVapor,''),IFNULL(emb.emb_Descripcion1,''), IFNULL(tac_Semana,''), IFNULL(emb.emb_CodPuerto,''), IFNULL(emb_Consignatario,''), IFNULL(emb_codpais,'')) as GrpVapor
		  ,IFNULL(t.tac_Contenedor,'') AS tac_Contenedor
		  ,t.tac_Sello as SelloNaviera
		  ,t.tac_Paletizado
		  ,tcarg.par_Descripcion AS TipoCarga
		  ,t.tac_Embarcador
		  ,CONCAT(pro.per_Apellidos,'-',pro.per_Nombres) AS Embarcador
		  ,t.tac_UniProduccion /*Hacienda*/
		  ,CONCAT(hac.per_Apellidos,'-',hac.per_Nombres) AS Hacienda
		  ,hac.per_CodAnterior AS CodHacienda
		  ,t.tac_Semana
		  ,GROUP_CONCAT(DISTINCT  TRIM(IF(tac_termografo='',NULL,tac_termografo))) AS Termografo
		  /* ,tac_termografo as Termografo */
		  ,tac_selloempresa as SelloEmpresa
		  ,group_concat(DISTINCT tac_selloAntNarc1) as SelloAntiNarc1
		  ,group_concat(DISTINCT tac_selloAntNarc2) as SelloAntiNarc2
		  ,d.tad_CodProducto
		  ,act.act_Descripcion AS DescProducto
		  ,act.act_Descripcion1
		  ,t.tac_RefOperativa /*Embarque*/
		  ,d.tad_CodMarca
		  ,GROUP_CONCAT(DISTINCT tmarc.par_Descripcion) AS Marca
		  ,d.tad_CodCaja
		  ,GROUP_CONCAT(DISTINCT caj_TipoCaja) AS TipoCaja
		  ,GROUP_CONCAT(DISTINCT caj_Abreviatura) AS AbreviaturaCaja
		  ,MAX(d.tad_Peso) AS Peso
		  
		  
		  ,emb.emb_CodBuque
		  ,emb.emb_Descripcion1 as Agencia
		  ,emb.emb_FecInicio
		  ,emb.emb_FecTermino
		  ,emb.emb_Consignatario
		  ,CONCAT(cli.per_Apellidos,'-',cli.per_Nombres) AS Consignatario
		  ,emb.emb_Destino
		  ,emb.emb_codpais
		  ,pai.pai_Descripcion AS PaisDestino
		  ,emb.emb_CodPuerto
		  ,pue.pue_Descripcion AS Puerto
		  ,emb.emb_CodVapor
		  ,vap.buq_Descripcion AS Vapor
		  ,'N/A' AS Modulo
		  ,'N/A' AS Booking
		  
		  /*
		  ,d.tad_CantRecibida
		  ,d.tad_CantRechazada
		  ,d.tad_CantCaidas
		  ,d.tad_CantDespachada*/
		  ,SUM(d.tad_CantRecibida-d.tad_CantRechazada-d.tad_CantCaidas) AS CjaEmbarcada
		  
		  ,(	  SELECT   SUM(dt.tad_CantRecibida-dt.tad_CantRechazada-dt.tad_CantCaidas) 
			  FROM liqtarjacabec ct
			  LEFT JOIN liqtarjadetal dt ON dt.tad_NumTarja = ct.tar_NumTarja
			  LEFT JOIN liqembarques emb2 ON emb2.emb_RefOperativa = ct.tac_RefOperativa
			  WHERE emb2.emb_CodVapor =  emb.emb_CodVapor
			  AND IFNULL(emb2.emb_Descripcion1,'') = IFNULL(emb.emb_Descripcion1,'')
			  AND ct.tac_Semana = t.tac_Semana
			  AND emb2.emb_CodPuerto = emb.emb_CodPuerto 
			  AND emb2.emb_Consignatario = emb.emb_Consignatario
			  AND emb2.emb_codpais = emb.emb_codpais
			  AND emb2.emb_FecInicio = emb.emb_FecInicio
			  AND emb2.emb_FecTermino = emb.emb_FecTermino
			  AND emb2.emb_SemInicio = emb.emb_SemInicio
			  AND emb2.emb_SemTermino = emb.emb_SemTermino
			  AND IFNULL(ct.tac_Contenedor,'') = IFNULL(t.tac_Contenedor,'')			  
			  AND IFNULL(ct.tac_Sello,'') = IFNULL(t.tac_Sello,'')
			  AND IFNULL(ct.tac_selloempresa,'') = IFNULL(t.tac_selloempresa,'')
			  AND IFNULL(ct.tac_selloAntNarc1,'') = IFNULL(t.tac_selloAntNarc1,'')
			  AND IFNULL(ct.tac_selloAntNarc2,'') = IFNULL(t.tac_selloAntNarc2,'')
			  AND IFNULL(dt.tad_CodProducto,'') = IFNULL(d.tad_CodProducto,'')
			  AND IFNULL(ct.tac_Paletizado,'') = IFNULL(t.tac_Paletizado,'')
		  )AS TotCjaEmbarcada
		  ,(	  SELECT   CASE COUNT(DISTINCT tac_Embarcador) WHEN 0 THEN 1 ELSE COUNT(DISTINCT tac_Embarcador) END
			  FROM liqtarjacabec ct
			  LEFT JOIN liqtarjadetal dt ON dt.tad_NumTarja = ct.tar_NumTarja
			  LEFT JOIN liqembarques emb2 ON emb2.emb_RefOperativa = ct.tac_RefOperativa
			  WHERE emb2.emb_CodVapor =  emb.emb_CodVapor
			  AND IFNULL(emb2.emb_Descripcion1,'') = IFNULL(emb.emb_Descripcion1,'')
			  AND ct.tac_Semana = t.tac_Semana
			  AND emb2.emb_CodPuerto = emb.emb_CodPuerto 
			  AND emb2.emb_Consignatario = emb.emb_Consignatario
			  AND emb2.emb_codpais = emb.emb_codpais
			  AND emb2.emb_FecInicio = emb.emb_FecInicio
			  AND emb2.emb_FecTermino = emb.emb_FecTermino
			  AND emb2.emb_SemInicio = emb.emb_SemInicio
			  AND emb2.emb_SemTermino = emb.emb_SemTermino
			  AND IFNULL(ct.tac_Contenedor,'') = IFNULL(t.tac_Contenedor,'')
			  AND IFNULL(ct.tac_Sello,'') = IFNULL(t.tac_Sello,'')
			  AND IFNULL(ct.tac_selloempresa,'') = IFNULL(t.tac_selloempresa,'')
			  AND IFNULL(ct.tac_selloAntNarc1,'') = IFNULL(t.tac_selloAntNarc1,'')
			  AND IFNULL(ct.tac_selloAntNarc2,'') = IFNULL(t.tac_selloAntNarc2,'')
			  AND IFNULL(dt.tad_CodProducto,'') = IFNULL(d.tad_CodProducto,'')
			  AND IFNULL(ct.tac_Paletizado,'') = IFNULL(t.tac_Paletizado,'')
		  ) AS NVap_Con
	  FROM liqtarjacabec t
	  LEFT JOIN liqtarjadetal d ON d.tad_NumTarja = t.tar_NumTarja
	  LEFT JOIN conpersonas pro ON pro.per_CodAuxiliar = t.tac_Embarcador
	  LEFT JOIN conpersonas hac ON hac.per_CodAuxiliar = t.tac_UniProduccion
	  LEFT JOIN genparametros tcarg ON tcarg.par_Clave = 'OGTCAR' AND tcarg.par_Secuencia = t.tac_Paletizado
	  LEFT JOIN liqembarques emb ON emb.emb_RefOperativa = t.tac_RefOperativa
	  LEFT JOIN liqbuques vap ON vap.buq_CodBuque = emb.emb_CodVapor
	  LEFT JOIN conpersonas cli ON cli.per_CodAuxiliar = emb.emb_Consignatario
	  LEFT JOIN genpuertos pue ON pue.pue_CodPuerto = emb.emb_CodPuerto
	  LEFT JOIN genpaises pai ON pai.pai_CodPais = emb.emb_CodPais
	  LEFT JOIN genparametros tmarc ON tmarc.par_Clave = 'IMARCA' AND tmarc.par_Secuencia = d.tad_CodMarca
	  LEFT JOIN liqcajas ON caj_CodCaja = d.tad_CodCaja
	  LEFT JOIN conactivos act ON act.act_CodAuxiliar = d.tad_CodProducto
	  WHERE 1 = 1
	  ";
	  
//$sSql .= ($pQry ? " and ". $pQry  : "  " );
$sSql .= ($semana ? " and tac_Semana = ". $semana  : "  " );
$sSql .= ($embarcador ? " and tac_Embarcador = ". $embarcador  : "  " );
$sSql .= ($producto ? " and tad_CodProducto = ". $producto  : "  " );
$sSql .= ($vapor ? " and emb_CodVapor = ". $vapor  : "  " );
$sSql .= ($anioOpe ? " and emb_AnoOperacion = ". $anioOpe  : "  " );
//$sSql .= ($SemInicio ? " and emb_SemInicio = ". $SemInicio  : "  " );
//$sSql .= ($SemTermino ? " and emb_SemTermino = ". $SemTermino  : "  " );

$sSql .= ($SemInicio ? " and emb_SemInicio >= ". $SemInicio  : "  " );
$sSql .= ($SemTermino ? " and emb_SemInicio <= ". $SemTermino  : "  " ); //esl ambas con semana de inicio para que seleccione un grupo de registros en el rango ingresado

$sSql .= ($Destino ? " and emb_codpais = ". $Destino  : "  " );
$sSql .= ($Consignatario ? " and emb_Consignatario = ". $Consignatario  : "  " );


$sSql .= "  /*
	    GROUP BY emb.emb_CodVapor,emb.emb_Descripcion1, emb_Consignatario, IFNULL(t.tac_Contenedor,''), t.tac_Embarcador
	    ORDER BY buq_Descripcion, CONCAT(cli.per_Apellidos,'-',cli.per_Nombres), IFNULL(t.tac_Contenedor,''), CONCAT(pro.per_Apellidos,'-',pro.per_Nombres)
	    */
	    GROUP BY emb.emb_CodVapor,emb.emb_Descripcion1, tac_Semana, emb_CodPuerto, emb_Consignatario, emb_codpais,
		     IFNULL(t.tac_Contenedor,''), emb_FecInicio, emb_FecTermino,
		     t.tac_Sello,t.tac_selloempresa , t.tac_selloAntNarc1, t.tac_selloAntNarc2,
		     d.tad_CodProducto,t.tac_Paletizado,
		     t.tac_Embarcador
		     
	    having SUM(d.tad_CantRecibida-d.tad_CantRechazada-d.tad_CantCaidas) > 0 
		     
	    ORDER BY buq_Descripcion, tac_Semana, pue.pue_Descripcion, emb_Consignatario,pai.pai_Descripcion,  
		     IFNULL(t.tac_Contenedor,''), emb_FecInicio, emb_FecTermino,t.tac_Sello,t.tac_selloempresa , t.tac_selloAntNarc1, t.tac_selloAntNarc2,
		     d.tad_CodProducto,tcarg.par_Descripcion,
		     CONCAT(pro.per_Apellidos,'-',pro.per_Nombres)";
	

$rs = $db->execute($sSql . $slFrom);


$Periodo =  ($pcom_FecVencimDesde ? " Desde ". $pcom_FecVencimDesde  : "  " );
$Periodo .= ($pcom_FecVencimHasta ? " Al  ". $pcom_FecVencimHasta  : "  " );
//pr.pro_Semana = 1126 
//style="font-weight:bold;"

if($rs->EOF){
    fErrorPage('','NO SE GENERO INFORMACION PARA PRESENTAR', true,false);
}else{
    
    $rs->MoveFirst();
    $aDet =& SmartyArray($rs);
    $Tpl->assign("agData", $aDet);
    $Tpl->assign("agPeriodo", $Periodo);
    $slPiePag = $_SESSION["g_user"] . ", " . date("%d %M %y");
    $Tpl->assign("slPiePag", $slPiePag);
    /*if (!$Tpl->is_cached('../Li_Files/LiLiRp_ConsolidaContenedor.tpl')) {
            }
            $Tpl->display('../Li_Files/LiLiRp_ConsolidaContenedor.tpl');*/
    $html = $Tpl->fetch('../Li_Files/LiLiRp_ConsolidaContenedor.tpl');
    echo (utf8_encode($html));
}
?>