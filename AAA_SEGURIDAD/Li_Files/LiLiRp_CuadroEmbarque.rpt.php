<?php
/*    Reporte - REPORTE GERENCIAL PARA APLESA - CUADRO DE EMBARQUE
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
$pQry = fGetParam('pQryCom','');

// Parametros individuales para el query
$semana = fGetParam('pro_Semana','');
$com_codReceptor = fGetParam('com_codReceptor',false);
$subtitulo = fGetParam('pCond','');
$subtitulo="CUADRO DE EMBARQUE";
$Tpl->assign("subtitulo",$subtitulo);
$subtitulo2 = "Semana:".$semana;
$subtitulo2 .= ($com_codReceptor ? " Productor: ". $com_codReceptor  : "  " );
$Tpl->assign("subtitulo2",$subtitulo2);



/*para consultar PROMEDIO GENERAL: ROL LIQUIDACION +  COMISIONES +  TRANSPORTE*/
$sSql = "   SELECT tac_Semana as semana, SUM(CajEmb) AS Cajas, SUM(tFruta) AS Total, (SUM(tFruta)/SUM(CajEmb)) AS PromGeneral
	    FROM
	    ( 
		    SELECT  	t.tac_Semana,
				     SUM(d.tad_CantRecibida-d.tad_CantCaidas-d.tad_CantRechazada) AS CajEmb, 
				     ROUND( SUM((d.tad_CantRecibida-d.tad_CantCaidas-d.tad_CantRechazada) * (d.tad_ValUnitario-d.tad_DifUnitario))/
					    SUM(d.tad_CantRecibida-d.tad_CantCaidas-d.tad_CantRechazada)
					 ,2)AS pCaj, 
				     SUM((d.tad_CantRecibida-d.tad_CantCaidas-d.tad_CantRechazada) * (d.tad_ValUnitario-d.tad_DifUnitario)) AS tFruta
		    FROM liqtarjacabec t 
		    LEFT JOIN liqtarjadetal d ON d.tad_NumTarja = t.tar_NumTarja
		    WHERE tac_Semana = ".$semana."
		    UNION
		    SELECT 		lde_semana, 0 AS CajEmb, ROUND(SUM(lde_cajas * lde_precio) / SUM(lde_cajas),2) AS pComision,SUM(lde_cajas * lde_precio) AS total
		    FROM liquidacionDatoExtra WHERE lde_semana = ".$semana." AND lde_estado = 1 AND lde_tipoVariable = 1 /*comisiones*/	
		    UNION
		    SELECT 		lde_semana, 0 AS CajEmb, 0 AS pComision,SUM(lde_precio) AS transporte
		    FROM liquidacionDatoExtra WHERE lde_semana = ".$semana." AND lde_estado = 1 AND lde_tipoVariable = 2 /*Transporte*/
	    ) TotalGeneral";

$rs = $db->execute($sSql . $slFrom);
$General = $rs->FetchRow();


/*para consultar CAJAS LIQUIDADAS AL PRODUCTOR*/
$sSql = " SELECT ifnull(SUM(liq_Cantidad),0) AS LiqProduc 
FROM liqliquidaciones WHERE liq_NumProceso IN (SELECT pro_id FROM liqprocesos WHERE pro_Semana = ".$semana.") AND liq_CodRubro = 1 /*Total Fruta*/ ";

$rs = $db->execute($sSql . $slFrom);
$LiqProduc = $rs->FetchRow();

/*para consultar los detalles*/
$sSql = " SELECT 
		  /*Tarja*/
				  tac_Semana
				  /*Detalle Tarjas*/
				  ,SUM(tad_CantCaidas) AS tad_CantCaidas
				  ,SUM(tad_CantDespachada) AS tad_CantDespachada
				  ,SUM(tad_CantRechazada) AS tad_CantRechazada
				  ,SUM(tad_CantRecibida) AS tad_CantRecibida
				  ,SUM(IFNULL(tad_CantRecibida,0) - IFNULL(tad_CantRechazada,0) - IFNULL(tad_CantCaidas,0)) AS Embarcadas
				  /*DATOS DEL EMBARQUE*/
				  ,emb_RefOperativa
				  ,emb_CodPais
				  ,emb_SemInicio
				  ,emb_SemTermino
				  ,emb_Consignatario
				  ,emb_CodVapor
				  ,emb_Destino
				  ,emb_CodBuque
				  ,emb_Descripcion2 as FACTURA_EMB
				  /*BUQUE*/
				  ,buq_Abreviatura
				  ,buq_Descripcion
				  /*PAIS DESTINO*/
				  ,pai_Nomenclatura
				  ,pai_Descripcion
				  ,venta
				  /*CLIENTE*/
				  ,cliente
				  /*PALETIZADO*/
				  ,tac_Paletizado
				  , paletizado
				  
				  /* PRECIO FOB*/
				  ,MAX(IFNULL(PRECIO_FOB,0)) AS PRECIO_FOB
				  ,MAX(IFNULL(PRECIO_CIF,0)) AS PRECIO_CIF
				  ,MAX(IFNULL(COSTO_FOB,0)) AS COSTO_FOB
				  ,MAX(IFNULL(COSTO_CIF,0)) AS COSTO_CIF
				  /* PRECIO CIF*/
				  
				  /* COSTO FOB*/
				  
				  ,EGNombre
				  ,(MAX(IFNULL(PRECIO_FOB,0))+MAX(IFNULL(PRECIO_CIF,0))-MAX(IFNULL(COSTO_FOB,0))) AS PRECIO_FRUTA
				  ,(SUM(IFNULL(tad_CantRecibida,0) - IFNULL(tad_CantRechazada,0) - IFNULL(tad_CantCaidas,0)) * (MAX(IFNULL(PRECIO_FOB,0))+MAX(IFNULL(PRECIO_CIF,0))-MAX(IFNULL(COSTO_FOB,0))) ) as TOTAL_EMBARQUE
	  FROM (
	  
				 SELECT 	/*  liqtarjacabec.* , */
					       /*Tarja*/
					       tac_Semana
					       /*Detalle Tarjas*/
					       ,SUM(tad_CantCaidas) AS tad_CantCaidas
					       ,SUM(tad_CantDespachada) AS tad_CantDespachada
					       ,SUM(tad_CantRechazada) AS tad_CantRechazada
					       ,SUM(tad_CantRecibida) AS tad_CantRecibida
					       ,SUM(tad_CantRecibida - tad_CantRechazada - tad_CantCaidas) AS Embarcadas
					       /*DATOS DEL EMBARQUE*/
					       ,emb_RefOperativa
					       ,emb_CodPais
					       ,emb_SemInicio
					       ,emb_SemTermino
					       ,emb_Consignatario
					       ,emb_CodVapor
					       ,emb_Destino
					       ,emb_CodBuque
					       ,emb_Descripcion2
					       /*BUQUE*/
					       ,buq_Abreviatura
					       ,buq_Descripcion
					       /*PAIS DESTINO*/
					       ,pai_Nomenclatura
					       ,pai_Descripcion
					       ,CASE pai_Nomenclatura WHEN 'ec' THEN 'VENTAS LOCALES' ELSE 'VENTAS AL EXTERIOR' END AS venta
					       /*CLIENTE*/
					       ,CONCAT(per_Apellidos,' ',per_Nombres) AS cliente
					       /*PALETIZADO*/
					       ,tac_Paletizado
					       ,palet.par_Descripcion AS paletizado
					       
					       /* PRECIO FOB*/
					       ,CASE tac_Paletizado 
						       WHEN 1 THEN 0
						       WHEN 2 THEN 0
						       WHEN 3 THEN 0
						       WHEN 4 THEN 0
					       END AS PRECIO_FOB
					       ,CASE tac_Paletizado 
						       WHEN 1 THEN 0
						       WHEN 2 THEN 0
						       WHEN 3 THEN 0
						       WHEN 4 THEN 0
					       END AS PRECIO_CIF
					       ,CASE tac_Paletizado 
						       WHEN 1 THEN 0
						       WHEN 2 THEN 0
						       WHEN 3 THEN 0
						       WHEN 4 THEN 0
					       END AS COSTO_FOB
					       ,CASE tac_Paletizado 
						       WHEN 1 THEN 0
						       WHEN 2 THEN 0
						       WHEN 3 THEN 0
						       WHEN 4 THEN 0
					       END AS COSTO_CIF	
					       /* PRECIO CIF*/
					       
					       /* COSTO FOB*/
					       
					       
					       
					       ,egnom.par_Descripcion AS EGNombre
					       
				       FROM liqtarjacabec
				       JOIN liqtarjadetal ON tar_NumTarja = tad_NumTarja
				       LEFT JOIN liqembarques  ON emb_RefOperativa = tac_RefOperativa
				       LEFT JOIN liqbuques ON buq_CodBuque = emb_CodVapor
				       LEFT JOIN genpaises ON pai_CodPais = emb_CodPais
				       LEFT JOIN conpersonas ON per_CodAuxiliar = emb_Consignatario
				       LEFT JOIN genparametros palet ON palet.par_Clave = 'OGTCAR' AND palet.par_Secuencia = tac_Paletizado
				       LEFT JOIN genparametros egnom ON egnom.par_Clave = 'EGNOM'
		       WHERE tac_Semana = ".$semana;
//$sSql .= ($com_codReceptor ? " and c.com_CodReceptor = ". $com_codReceptor  : "  " );
$sSql .= " GROUP BY emb_SemInicio, emb_Consignatario, emb_CodBuque, tac_Paletizado, emb_CodPais";

$sSql .= " UNION ALL
SELECT 	/*  liqtarjacabec.* , */
			/*Tarja*/
			tac_Semana
			/*Detalle Tarjas*/
			,0 AS tad_CantCaidas
			,0 AS tad_CantDespachada
			,0 AS tad_CantRechazada
			,0 AS tad_CantRecibida
			,0 AS Embarcadas
			/*DATOS DEL EMBARQUE*/
			,emb_RefOperativa
			,emb_CodPais
			,emb_SemInicio
			,emb_SemTermino
			,emb_Consignatario
			,emb_CodVapor
			,emb_Destino
			,emb_CodBuque
			,emb_Descripcion2
			/*BUQUE*/
			,buq_Abreviatura
			,buq_Descripcion
			/*PAIS DESTINO*/
			,pai_Nomenclatura
			,pai_Descripcion
			,CASE pai_Nomenclatura WHEN 'ec' THEN 'VENTAS LOCALES' ELSE 'VENTAS AL EXTERIOR' END AS venta
			/*CLIENTE*/
			,CONCAT(per_Apellidos,' ',per_Nombres) AS cliente
			/*PALETIZADO*/
			,tac_Paletizado
			,palet.par_Descripcion AS paletizado
			
			/* PRECIO FOB*/
			,CASE tac_Paletizado 
				WHEN 1 THEN MAX(CASE WHEN det_CodItem = (SELECT par_Valor1 FROM genparametros WHERE par_Clave = 'PYCEMB' AND par_Secuencia = 1) THEN det_ValUnitario ELSE 0 END)
				WHEN 2 THEN MAX(CASE WHEN det_CodItem = (SELECT par_Valor1 FROM genparametros WHERE par_Clave = 'PYCEMB' AND par_Secuencia = 2) THEN det_ValUnitario ELSE 0 END)
				WHEN 3 THEN MAX(CASE WHEN det_CodItem = (SELECT par_Valor1 FROM genparametros WHERE par_Clave = 'PYCEMB' AND par_Secuencia = 5) THEN det_ValUnitario ELSE 0 END)
				WHEN 4 THEN MAX(CASE WHEN det_CodItem = (SELECT par_Valor1 FROM genparametros WHERE par_Clave = 'PYCEMB' AND par_Secuencia = 6) THEN det_ValUnitario ELSE 0 END)
			END AS PRECIO_FOB
			,CASE tac_Paletizado 
				WHEN 1 THEN MAX(CASE WHEN det_CodItem = (SELECT par_Valor1 FROM genparametros WHERE par_Clave = 'PYCEMB' AND par_Secuencia = 3) THEN det_ValUnitario ELSE 0 END)
				WHEN 2 THEN MAX(CASE WHEN det_CodItem = (SELECT par_Valor1 FROM genparametros WHERE par_Clave = 'PYCEMB' AND par_Secuencia = 4) THEN det_ValUnitario ELSE 0 END)
				WHEN 3 THEN MAX(CASE WHEN det_CodItem = (SELECT par_Valor1 FROM genparametros WHERE par_Clave = 'PYCEMB' AND par_Secuencia = 7) THEN det_ValUnitario ELSE 0 END)
				WHEN 4 THEN MAX(CASE WHEN det_CodItem = (SELECT par_Valor1 FROM genparametros WHERE par_Clave = 'PYCEMB' AND par_Secuencia = 8) THEN det_ValUnitario ELSE 0 END)
			END AS PRECIO_CIF
			,MAX(CASE WHEN det_CodItem = (SELECT par_Valor1 FROM genparametros WHERE par_Clave = 'PYCEMB' AND par_Secuencia = 9) THEN det_ValUnitario ELSE 0 END) AS COSTO_FOB
			,CASE tac_Paletizado 
				WHEN 1 THEN 0
				WHEN 2 THEN 0
				WHEN 3 THEN 0
				WHEN 4 THEN 0
			END AS COSTO_CIF	
			/* PRECIO CIF*/
			
			/* COSTO FOB*/
			
			
			
			,egnom.par_Descripcion AS EGNombre
			
		FROM liqtarjacabec
		JOIN liqtarjadetal ON tar_NumTarja = tad_NumTarja
		LEFT JOIN liqembarques  ON emb_RefOperativa = tac_RefOperativa
		LEFT JOIN liqbuques ON buq_CodBuque = emb_CodVapor
		LEFT JOIN genpaises ON pai_CodPais = emb_CodPais
		LEFT JOIN conpersonas ON per_CodAuxiliar = emb_Consignatario
		LEFT JOIN genparametros palet ON palet.par_Clave = 'OGTCAR' AND palet.par_Secuencia = tac_Paletizado
		LEFT JOIN genparametros egnom ON egnom.par_Clave = 'EGNOM'
		/*PRECIOS Y COSTOS DEL EMBARQUE*/
		JOIN concomprobantes ON com_TipoComp = 'PX'
		LEFT JOIN invdetalle ON det_RegNUmero = com_RegNumero AND det_RefOperativa = emb_RefOperativa
WHERE tac_Semana = ".$semana;


$sSql .= " GROUP BY emb_SemInicio, emb_Consignatario, emb_CodBuque, tac_Paletizado, emb_CodPais
	    ) AS REPORTE
	    GROUP BY emb_SemInicio, emb_Consignatario, emb_CodBuque, tac_Paletizado, emb_CodPais
	    ORDER BY venta,emb_SemInicio, cliente, buq_Descripcion,paletizado, pai_Descripcion
	    ";
	
//$sSql .= " ORDER BY liq, productor, tipo desc, dmarca";

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
    $Tpl->assign("LiqProduc",$LiqProduc['LiqProduc']);
    $Tpl->assign("PromGeneral",$General['PromGeneral']);
    if (!$Tpl->is_cached('../Li_Files/LiLiRp_CuadroEmbarque.tpl')) {
            }
            $Tpl->display('../Li_Files/LiLiRp_CuadroEmbarque.tpl');
}
?>