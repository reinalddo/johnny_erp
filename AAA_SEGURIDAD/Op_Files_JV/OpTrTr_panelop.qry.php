<?
/*
*   Panel general de Operaciones
*   @created    Oct/30/07
*   @author     fah
*   @
*/
/* ob_start("ob_gzhandler");
if (!isset ($_SESSION)) session_start();
include_once('GenUti.inc.php');
$slCondUsuario = "";
if ($_SESSION['g_usugru'] == 20 || $_SESSION['g_usugru'] == 25){
	$slCondUsuario = " ord_cliente = " . $_SESSION['g_usuaux'] . " AND " ;
}
*/
/**/
/*
* 	Inicializacion de variables generales varios      
*/
$gsSesVar = 'OpTrTr_panelop';
$_SESSION[$gsSesVar . '_defs']['start']=0;
$_SESSION[$gsSesVar . '_defs']['limit']=25;
if(fGetParam('pAdoDbg',false)) $_SESSION['pAdoDbg']= fGetParam('pAdoDbg',false);
if(fGetParam('pAppDbg',false)) $_SESSION['pAppDbg']= fGetParam('pAppDbg',false);

$glExpanded = fGetParam('pExpan', 1);
/*
 *		ToTALES ACUMULADOS
 */
$_SESSION[$gsSesVar . "_" . "T"]=
"SELECT 1 as txt_tipo ,
		act_codauxiliar as id , 
		concat(act_codauxiliar, '-', act_descripcion, ' ', act_Descripcion1) as text,
		'task-folder' as iconCls,
		'master-task' as cls,
		'col' as uiProvider,
		sum(tad_cantrecibida - tad_cantrechazada - tad_cantcaidas) as txt_cantidad,
		sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) as txt_valor,
		sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) /
		sum(tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) as txt_precio
		FROM liqembarques
			LEFT JOIN liqbuques on buq_codbuque  = emb_codvapor
			left join liqtarjacabec on tac_refoperativa = emb_refoperativa
			LEFT JOIN liqtarjadetal on tad_numtarja = tar_numtarja
			LEFT JOIN conactivos ON act_codauxiliar = tad_codproducto
		WHERE emb_anooperacion  = {pAnioOp} and
			  {pSeman} between emb_seminicio and emb_semtermino
		GROUP BY 1,2,3,4,5,6
UNION
SELECT 1 as txt_tipo ,
		'' as id , 
		'TOTAL'  as text,
		'task-folder' as iconCls, 'master-task' as cls,
		'col' as uiProvider,
		sum(tad_cantrecibida - tad_cantrechazada - tad_cantcaidas) as txt_cantidad,
		sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) as txt_valor,
		sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) /
		sum(tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) as txt_precio
		FROM liqembarques
			LEFT JOIN liqbuques on buq_codbuque  = emb_codvapor
			left join liqtarjacabec on tac_refoperativa = emb_refoperativa
			LEFT JOIN liqtarjadetal on tad_numtarja = tar_numtarja
			LEFT JOIN conactivos ON act_codauxiliar = tad_codproducto
		WHERE emb_anooperacion  = {pAnioOp} and
			  {pSeman} between emb_seminicio and emb_semtermino
		GROUP BY 1,2,3,4,5,6
"
/*
 *								TOTALES ACUMULADOR POR VAPOR, PARA EL CASO DE INGRESO DE PRECIOS MAS SIMPLE
 **
 ***/
;
$_SESSION[$gsSesVar . "_E_0"]=
"SELECT 1 as txt_tipo ,
		emb_refOperativa as txt_rope ,
		concat(emb_numviaje, ' - ', buq_descripcion) as text,
		'task-folder' as iconCls,
		'master-task' as cls,
		'col' as uiProvider,
		sum(tad_cantrecibida - tad_cantrechazada - tad_cantcaidas) as txt_cantidad,
		sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) as txt_valor,
		sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) /
		sum(tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) as txt_precio
		FROM liqembarques
			LEFT JOIN liqbuques on buq_codbuque  = emb_codvapor
			left join liqtarjacabec on tac_refoperativa = emb_refoperativa
			LEFT JOIN liqtarjadetal on tad_numtarja = tar_numtarja
		WHERE emb_anooperacion  = {pAnioOp} and
			  {pSeman} between emb_seminicio and emb_semtermino 
		GROUP BY 1,2,3,4,5,6
UNION
SELECT 1 as txt_tipo ,
		'' as id , 
		'TOTAL'  as text,
		'task-folder' as iconCls, 'master-task' as cls,
		'col' as uiProvider,
		sum(tad_cantrecibida - tad_cantrechazada - tad_cantcaidas) as txt_cantidad,
		sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) as txt_valor,
		sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) /
		sum(tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) as txt_precio
		FROM liqembarques
			LEFT JOIN liqbuques on buq_codbuque  = emb_codvapor
			left join liqtarjacabec on tac_refoperativa = emb_refoperativa
			LEFT JOIN liqtarjadetal on tad_numtarja = tar_numtarja
		WHERE emb_anooperacion  = {pAnioOp} and
			  {pSeman} between emb_seminicio and emb_semtermino 
		GROUP BY 1,2,3,4,5,6
		ORDER BY text "
;

/*----------------------------------------------------------------------
*   								Version Por Embarques
*-----------------------------------------------------------------------*/
$slVer = "E";
/* 																				resumen de embarques semanales */
/*   version anterior, detalles por Vapor para cada tipo de fruta
  $_SESSION[$gsSesVar . "_" . $slVer ."_1"]=
"SELECT 2 as txt_tipo ,
		emb_refOperativa as txt_rope ,
		concat(emb_numviaje, ' - ', buq_descripcion) as text,
		'task-folder' as iconCls,
		'master-task' as cls,
		'col' as uiProvider,
		sum(tad_cantrecibida - tad_cantrechazada - tad_cantcaidas) as txt_cantidad,
		sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) as txt_valor,
		sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) /
		sum(tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) as txt_precio
		FROM liqembarques
			LEFT JOIN liqbuques on buq_codbuque  = emb_codvapor
			left join liqtarjacabec on tac_refoperativa = emb_refoperativa
			LEFT JOIN liqtarjadetal on tad_numtarja = tar_numtarja
		WHERE emb_anooperacion  = {pAnioOp} and
			  {pSeman} between emb_seminicio and emb_semtermino AND
				tad_codproducto   = {pPrdc}
		GROUP BY 1,2,3,4,5,6
		ORDER BY emb_NumViaje"
;
 */
//  Presentar totales por Tipo de Fruta en cada vapor
$_SESSION[$gsSesVar . "_" . $slVer."_1"]=
"SELECT 2 as txt_tipo ,
		act_codauxiliar as item , 
		concat(act_codauxiliar, '-', act_descripcion, ' ', act_Descripcion1) as text,
		'task-folder' as iconCls,
		'master-task' as cls,
		'col' as uiProvider,
		sum(tad_cantrecibida - tad_cantrechazada - tad_cantcaidas) as txt_cantidad,
		sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) as txt_valor,
		sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) /
		sum(tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) as txt_precio
		FROM liqembarques
			LEFT JOIN liqbuques on buq_codbuque  = emb_codvapor
			left join liqtarjacabec on tac_refoperativa = emb_refoperativa
			LEFT JOIN liqtarjadetal on tad_numtarja = tar_numtarja
			LEFT JOIN conactivos ON act_codauxiliar = tad_codproducto
		WHERE emb_anooperacion  = {pAnioOp} and
			  {pSeman} between emb_seminicio and emb_semtermino AND
			  emb_refoperativa = {pRope} 
		GROUP BY 1,2,3,4,5,6
		ORDER BY act_descripcion " ;
/* 																		Nodos de segundo nivel (Productores)*/
$_SESSION[$gsSesVar . "_" . $slVer."_2"]=
"SELECT 3 as txt_tipo,
	per_codauxiliar as id,
	concat(per_Apellidos, ' ', per_Nombres) as text,
	per_codauxiliar as id, $glExpanded as expanded,
	'task-folder' as iconCls,'master-task' as cls,
	'col' as uiProvider,
	sum(tad_cantrecibida - tad_cantrechazada - tad_cantcaidas) as txt_cantidad,
	sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) as txt_valor,
	sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) /
	sum(tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) as txt_precio
FROM liqembarques
	left join liqtarjacabec on tac_refoperativa = emb_refoperativa
	LEFT JOIN liqtarjadetal on tad_numtarja = tar_numtarja
	left join conpersonas on per_codauxiliar = tac_embarcador
WHERE emb_anooperacion  = {pAnioOp} and
	  tac_semana   = {pSeman} AND
	  emb_refoperativa = {pRope} AND
		tad_codproducto   = {pPrdc}
GROUP BY 1, 2, 3, 4,5,6,7,8
ORDER by 3 ";
/* 																		Nodos de tercer nivel (EMpaques)*/
$_SESSION[$gsSesVar. "_" . $slVer ."_3"]=
"SELECT 4 as txt_tipo ,
	concat(par_descripcion,' - ',ifnull(caj_abreviatura,'')) as text,
	tad_codcaja as id,
	$glExpanded as expanded,
	par_secuencia,
	'task-folder' as iconCls,
	'master-task' as cls,
	'col' as uiProvider,
	sum(tad_cantrecibida - tad_cantrechazada - tad_cantcaidas) as txt_cantidad,
	sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) as txt_valor,
	sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) /
	sum(tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) as txt_precio
FROM liqembarques
	left join liqtarjacabec on tac_refoperativa = emb_refoperativa
	LEFT JOIN liqtarjadetal on tad_numtarja = tar_numtarja
	left join genparametros on par_clave = 'IMARCA' and par_secuencia = tad_CodMarca
	left join liqcajas 	on caj_codcaja = tad_codCaja
WHERE emb_anooperacion  = {pAnioOp} and
	  tac_semana   = {pSeman} AND
	  emb_refoperativa = {pRope} AND
	  tac_Embarcador= {pProd} AND
		tad_codproducto   = {pPrdc}
GROUP BY 1, 2,3,4,5,6,7,8
ORDER BY 2 ";

/* 																		Nodos de 4to nivel (Tarjas)*/
$_SESSION[$gsSesVar. "_" . $slVer ."_4"]=
"SELECT  5 as txt_tipo ,
	tad_numTarja	as id,
	concat(date_format(tac_fecha, '%d-%m-%y'), ' - ', tar_numtarja) as text,
	tad_codcaja,
	$glExpanded as expanded,
	par_secuencia,
	'task-folder' as iconCls,
	'master-task' as cls,
	1 leaf,
	'col' as uiProvider,
	sum(tad_cantrecibida - tad_cantrechazada - tad_cantcaidas) as txt_cantidad,
	sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) as txt_valor,
	sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) /
	sum(tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) as txt_precio
FROM liqembarques
	left join liqtarjacabec on tac_refoperativa = emb_refoperativa
	LEFT JOIN liqtarjadetal on tad_numtarja = tar_numtarja
	left join genparametros on par_clave = 'IMARCA' and par_secuencia = tad_CodMarca
	left join liqcajas 	on caj_codcaja = tad_codCaja
WHERE emb_anooperacion  = {pAnioOp} and
	  tac_semana   		= {pSeman} AND
	  emb_refoperativa = {pRope} AND
	  tac_Embarcador	= {pProd} AND
		tad_codcaja			= {pEmpq} AND
		tad_codproducto   = {pPrdc}
GROUP BY 1, 2,3,4,5,6,7,8,9
ORDER BY 2 ";


/*----------------------------------------------------------------------
*   								Version Alfabetica
*-----------------------------------------------------------------------*/
$slVer = "A";
/* 																			Resumen de Productores */
$_SESSION[$gsSesVar . "_" . $slVer. "_1"]=
"SELECT 2 as txt_tipo,
	concat(per_Apellidos, ' ', per_Nombres) as text,
	per_codauxiliar as id,
	$glExpanded as expanded,
	'task-folder' as iconCls,
	'master-task' as cls,
	'col' as uiProvider,
	sum(tad_cantrecibida - tad_cantrechazada - tad_cantcaidas) as txt_cantidad,
	sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) as txt_valor,
	sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) /
	sum(tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) as txt_precio
FROM liqembarques
	left join liqtarjacabec on tac_refoperativa = emb_refoperativa
	LEFT JOIN liqtarjadetal on tad_numtarja = tar_numtarja
	left join conpersonas on per_codauxiliar = tac_embarcador
WHERE emb_anooperacion  = {pAnioOp} and
	  tac_semana   = {pSeman} AND
		tad_codproducto   = {pPrdc}
GROUP BY 1, 2, 3, 4,5,6, 7
ORDER by 2 ";
/* 																		Embarques de Productores */
$_SESSION[$gsSesVar . "_" . $slVer ."_2"]=
"SELECT 3 as txt_tipo ,
		emb_refOperativa as  txt_rope,
		concat(emb_numviaje, ' - ', buq_descripcion) as text,
		$glExpanded as expanded,
		'task-folder' as iconCls,
		'master-task' as cls,
		'col' as uiProvider,
		sum(tad_cantrecibida - tad_cantrechazada - tad_cantcaidas) as txt_cantidad,
		sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) as txt_valor,
		sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) /
		sum(tad_cantrecibida - tad_cantrechazada - tad_cantcaidas) as txt_precio
		FROM liqembarques
			LEFT JOIN liqbuques on buq_codbuque  = emb_codvapor
			left join liqtarjacabec on tac_refoperativa = emb_refoperativa
			LEFT JOIN liqtarjadetal on tad_numtarja = tar_numtarja
			left join genparametros on par_clave = 'IMARCA' and par_secuencia = tad_CodMarca
		WHERE emb_anooperacion  = {pAnioOp} and
			  {pSeman} between emb_seminicio and emb_semtermino AND
	  		  tac_Embarcador= {pProd} AND
				tad_codproducto   = {pPrdc}
		GROUP BY 1,3,4,5,6,7
		ORDER BY 3"
;
/*												       Nivel 2: Marcas/Empaques del productor	*/
$_SESSION[$gsSesVar . "_" . $slVer ."_3"]=
"SELECT 4 as txt_tipo ,
		emb_refOperativa as  txt_rope,
		tad_codcaja  as  id,
		concat(par_descripcion, ' - ',ifnull(caj_abreviatura,'')) as text,
		$glExpanded as expanded,
		'task-folder' as iconCls,
		'master-task' as cls,
		'col' as uiProvider,
		sum(tad_cantrecibida - tad_cantrechazada - tad_cantcaidas) as txt_cantidad,
		sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) as txt_valor,
		sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) /
		sum(tad_cantrecibida - tad_cantrechazada - tad_cantcaidas) as txt_precio
		FROM liqembarques
			LEFT JOIN liqbuques on buq_codbuque  = emb_codvapor
			left join liqtarjacabec on tac_refoperativa = emb_refoperativa
			LEFT JOIN liqtarjadetal on tad_numtarja = tar_numtarja
			left join genparametros on par_clave = 'IMARCA' and par_secuencia = tad_CodMarca
			left join liqcajas 	on caj_codcaja = tad_codCaja
		WHERE emb_anooperacion  = {pAnioOp} and
			  {pSeman} between emb_seminicio and emb_semtermino AND
	  		  tac_Embarcador= {pProd} AND
			  tac_RefOperativa  = {pRope} AND
				tad_codproducto   = {pPrdc}
		GROUP BY 1,3,4,5,6,7,8
		ORDER BY 4"
;
/*					       										Nivel 3: TYarjas*/
$_SESSION[$gsSesVar . "_" . $slVer ."_4"]=
"SELECT 5 as txt_tipo ,
		tad_numtarja as  id,
		concat(date_format(tac_fecha, '%d-%m-%y'), ' - ', tar_numtarja) as text,
		$glExpanded as expanded,
		'task-folder' as iconCls,
		'master-task' as cls,
		'col' as uiProvider,
		1 as leaf,
		sum(tad_cantrecibida - tad_cantrechazada - tad_cantcaidas) as txt_cantidad,
		sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) as txt_valor,
		sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) /
		sum(tad_cantrecibida - tad_cantrechazada - tad_cantcaidas) as txt_precio
		FROM liqembarques
			LEFT JOIN liqbuques on buq_codbuque  = emb_codvapor
			left join liqtarjacabec on tac_refoperativa = emb_refoperativa
			LEFT JOIN liqtarjadetal on tad_numtarja = tar_numtarja
			left join genparametros on par_clave = 'IMARCA' and par_secuencia = tad_CodMarca
		WHERE emb_anooperacion  = {pAnioOp} and
			  {pSeman} between emb_seminicio and emb_semtermino AND
	  		tac_Embarcador= {pProd} AND
			  tac_RefOperativa  = {pRope} AND
				tad_codCaja   		= {pEmpq} AND
				tad_codproducto   = {pPrdc} 
		GROUP BY 1,3,4,5,6,7,8
		ORDER BY 3"
;

/*----------------------------------------------------------------------------------------------------
*   								Version CONTENEDORES
*----------------------------------------------------------------------------------------------------*/
$slVer = "C";
																/* Nodos de Primer nivel (Contenedores)*/
$_SESSION[$gsSesVar . "_" . $slVer. "_1"]=
"SELECT 2 as txt_tipo ,
		emb_refOperativa as  txt_rope,
		tac_contenedor as txt_conte,
		concat(emb_numviaje, ' - ', buq_descripcion, ' - ', tac_contenedor) as text,
		$glExpanded as expanded,
		'task-folder' as iconCls,
		'master-task' as cls,
		'col' as uiProvider,
		sum(tad_cantrecibida - tad_cantrechazada - tad_cantcaidas) as txt_cantidad,
		sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) as txt_valor,
		sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) /
		sum(tad_cantrecibida - tad_cantrechazada - tad_cantcaidas) as txt_precio
		FROM liqembarques
			LEFT JOIN liqbuques on buq_codbuque  = emb_codvapor
			left join liqtarjacabec on tac_refoperativa = emb_refoperativa
			LEFT JOIN liqtarjadetal on tad_numtarja = tar_numtarja
			left join genparametros on par_clave = 'IMARCA' and par_secuencia = tad_CodMarca
		WHERE emb_anooperacion  = {pAnioOp} and
			  {pSeman} between emb_seminicio and emb_semtermino AND
				tad_codproducto   = {pPrdc}
		GROUP BY 1,3,4,5,6,7
		ORDER BY 4"
;

/* 																	Nivel 1: Marcas dentro del contenedor*/
$_SESSION[$gsSesVar. "_" . $slVer ."_2"]=
"SELECT 3 as txt_tipo ,
	tad_codmarca	as id,
	par_descripcion as text,
	$glExpanded as expanded,
	par_secuencia,
	'task-folder' as iconCls,
	'master-task' as cls,
	'col' as uiProvider,
	sum(tad_cantrecibida - tad_cantrechazada - tad_cantcaidas) as txt_cantidad,
	sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) as txt_valor,
	sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) /
 	sum(tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) as txt_precio
FROM liqembarques
	left join liqtarjacabec on tac_refoperativa = emb_refoperativa
	LEFT JOIN liqtarjadetal on tad_numtarja = tar_numtarja
	left join genparametros on par_clave = 'IMARCA' and par_secuencia = tad_CodMarca
	left join liqcajas 	on caj_codcaja = tad_codCaja
WHERE emb_anooperacion  = {pAnioOp} and
	tac_semana   	  = {pSeman} AND
	tac_contenedor  = '{pCont}' AND
	tad_codproducto = {pPrdc}
GROUP BY 1, 2, 3, 4, 5, 6, 7, 8
ORDER BY 3 ";
/* 																	Nivel 2: Tipos de Caja en el contenedor*/
$_SESSION[$gsSesVar. "_" . $slVer ."_3"]=
"SELECT 4 as txt_tipo ,
	tad_codCaja	as id,
	concat(par_descripcion, ' - ',ifnull(caj_abreviatura,'')) as text,
	$glExpanded as expanded,
	par_secuencia,
	'task-folder' as iconCls,
	'master-task' as cls,
	'col' as uiProvider,
	sum(tad_cantrecibida - tad_cantrechazada - tad_cantcaidas) as txt_cantidad,
	sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) as txt_valor,
	sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) /
 	sum(tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) as txt_precio
FROM liqembarques
	left join liqtarjacabec on tac_refoperativa = emb_refoperativa
	LEFT JOIN liqtarjadetal on tad_numtarja = tar_numtarja
	left join genparametros on par_clave = 'IMARCA' and par_secuencia = tad_CodMarca
	left join liqcajas 	on caj_codcaja = tad_codCaja
WHERE emb_anooperacion  = {pAnioOp} and
	tac_semana   	 = {pSeman} AND
	tac_contenedor = '{pCont}' AND
	tad_codMarca 	 = '{pMarc}' AND
	tad_codproducto= {pPrdc}
GROUP BY 1, 2, 3, 4, 5, 6, 7, 8
ORDER BY 3 ";
/* 																	Nivel 3: Tarjas dentro del contenedor*/
$_SESSION[$gsSesVar. "_" . $slVer ."_4"]=
"SELECT 5 as txt_tipo ,
	tad_numTarja	as id,
	concat(date_format(tac_fecha, '%d-%m-%y'), ' - ', left(per_apellidos,20), ' ', left(per_Nombres,12), ' - ', tar_numtarja) as text,
	$glExpanded as expanded,
	par_secuencia,
	'task-folder' as iconCls,
	'master-task' as cls,
	'col' as uiProvider,
	sum(tad_cantrecibida - tad_cantrechazada - tad_cantcaidas) as txt_cantidad,
	sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) as txt_valor,
	sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) /
 	sum(tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) as txt_precio
FROM liqembarques
	left join liqtarjacabec on tac_refoperativa = emb_refoperativa
	LEFT JOIN liqtarjadetal on tad_numtarja = tar_numtarja
	LEFT JOIN conpersonas on per_codauxiliar = tac_embarcador
	left join genparametros on par_clave = 'IMARCA' and par_secuencia = tad_CodMarca
	left join liqcajas 	on caj_codcaja = tad_codCaja
WHERE emb_anooperacion  = {pAnioOp} and
	tac_semana   	 = {pSeman} AND
	tac_contenedor = '{pCont}' AND
	tad_codMarca 	 = '{pMarc}' AND
	tad_codcaja    = '{pEmpq}' AND	
	tad_codproducto= {pPrdc}
GROUP BY 1, 2, 3, 4, 5, 6, 7, 8
ORDER BY 2 ";

/*----------------------------------------------------------------------
*   								Version MARCAS
-----------------------------------------------------------------------*/
$slVer = "M";
																/* Nodos de Primer nivel (Contenedores)*/
$_SESSION[$gsSesVar . "_" . $slVer. "_1"]=
"SELECT 2 as txt_tipo ,
		emb_refOperativa as  txt_rope,
		tac_contenedor as txt_conte,
		concat(emb_numviaje, ' - ', buq_descripcion, ' - ', tac_contenedor) as text,
		$glExpanded as expanded,
		'task-folder' as iconCls,
		'master-task' as cls,
		'col' as uiProvider,
		sum(tad_cantrecibida - tad_cantrechazada - tad_cantcaidas) as txt_cantidad,
		sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) as txt_valor,
		sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) /
		sum(tad_cantrecibida - tad_cantrechazada - tad_cantcaidas) as txt_precio
		FROM liqembarques
			LEFT JOIN liqbuques on buq_codbuque  = emb_codvapor
			left join liqtarjacabec on tac_refoperativa = emb_refoperativa
			LEFT JOIN liqtarjadetal on tad_numtarja = tar_numtarja
			left join genparametros on par_clave = 'IMARCA' and par_secuencia = tad_CodMarca
		WHERE emb_anooperacion  = {pAnioOp} and
			  {pSeman} between emb_seminicio and emb_semtermino AND
				tad_codproducto   = {pPrdc}
		GROUP BY 1,3,4,5,6,7
		ORDER BY 4"
;

/* 																	Nivel 1: Marcas dentro del contenedor*/
$_SESSION[$gsSesVar. "_" . $slVer ."_2"]=
"SELECT 3 as txt_tipo ,
	tad_codmarca	as id,
	par_descripcion as text,
	$glExpanded as expanded,
	par_secuencia,
	'task-folder' as iconCls,
	'master-task' as cls,
	'col' as uiProvider,
	sum(tad_cantrecibida - tad_cantrechazada - tad_cantcaidas) as txt_cantidad,
	sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) as txt_valor,
	sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) /
 	sum(tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) as txt_precio
FROM liqembarques
	left join liqtarjacabec on tac_refoperativa = emb_refoperativa
	LEFT JOIN liqtarjadetal on tad_numtarja = tar_numtarja
	left join genparametros on par_clave = 'IMARCA' and par_secuencia = tad_CodMarca
	left join liqcajas 	on caj_codcaja = tad_codCaja
WHERE emb_anooperacion  = {pAnioOp} and
	tac_semana   	  = {pSeman} AND
	tac_contenedor  = '{pCont}' AND
	tad_codproducto = {pPrdc}
GROUP BY 1, 2, 3, 4, 5, 6, 7, 8
ORDER BY 2 ";
/* 																	Nivel 2: Tipos de Caja en el contenedor*/
$_SESSION[$gsSesVar. "_" . $slVer ."_3"]=
"SELECT 4 as txt_tipo ,
	tad_codCaja	as id,
	concat(par_descripcion, ' - ',ifnull(caj_abreviatura,'')) as text,
	$glExpanded as expanded,
	par_secuencia,
	'task-folder' as iconCls,
	'master-task' as cls,
	'col' as uiProvider,
	sum(tad_cantrecibida - tad_cantrechazada - tad_cantcaidas) as txt_cantidad,
	sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) as txt_valor,
	sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) /
 	sum(tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) as txt_precio
FROM liqembarques
	left join liqtarjacabec on tac_refoperativa = emb_refoperativa
	LEFT JOIN liqtarjadetal on tad_numtarja = tar_numtarja
	left join genparametros on par_clave = 'IMARCA' and par_secuencia = tad_CodMarca
	left join liqcajas 	on caj_codcaja = tad_codCaja
WHERE emb_anooperacion  = {pAnioOp} and
	tac_semana   	 = {pSeman} AND
	tac_contenedor = '{pCont}' AND
	tad_codMarca 	 = '{pMarc}' AND
	tad_codproducto= {pPrdc}
GROUP BY 1, 2, 3, 4, 5, 6, 7, 8
ORDER BY 3 ";
/* 																	Nivel 3: Tarjas dentro del contenedor*/
$_SESSION[$gsSesVar. "_" . $slVer ."_4"]=
"SELECT 5 as txt_tipo ,
	tad_numTarja	as id,
	concat(date_format(tac_fecha, '%d-%m-%y'), ' - ', left(per_apellidos,20), ' ', left(per_Nombres,12), ' - ', tar_numtarja) as text,
	$glExpanded as expanded,
	par_secuencia,
	'task-folder' as iconCls,
	'master-task' as cls,
	'col' as uiProvider,
	sum(tad_cantrecibida - tad_cantrechazada - tad_cantcaidas) as txt_cantidad,
	sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) as txt_valor,
	sum((tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) * (tad_ValUnitario - tad_DifUnitario)) /
 	sum(tad_CantRecibida -tad_cantrechazada - tad_cantCaidas) as txt_precio
FROM liqembarques
	left join liqtarjacabec on tac_refoperativa = emb_refoperativa
	LEFT JOIN liqtarjadetal on tad_numtarja = tar_numtarja
	LEFT JOIN conpersonas on per_codauxiliar = tac_embarcador
	left join genparametros on par_clave = 'IMARCA' and par_secuencia = tad_CodMarca
	left join liqcajas 	on caj_codcaja = tad_codCaja
WHERE emb_anooperacion  = {pAnioOp} and
	tac_semana   	 = {pSeman} AND
	tac_contenedor = '{pCont}' AND
	tad_codMarca 	 = '{pMarc}' AND
	tad_codcaja    = '{pEmpq}' AND
	tad_codproducto= {pPrdc}
GROUP BY 1, 2, 3, 4, 5, 6, 7, 8
ORDER BY 2 ";

/*
*       Modificar un precio de tarjas
*/
$_SESSION[$gsSesVar. "_aplic"]=
"Update liqtarjacabec JOIN liqtarjadetal on tad_numtarja = tar_numtarja
	set tad_valUnitario = {pPOfi},
		tad_difUnitario = {pDife}
WHERE {pCond} ";


/*
 *		Consultad de apoyo
 **/
$_SESSION["OpTrTr_contenlist"]=
"SELECT '-9999' as cod, ' TODOS' as txt UNION
 SELECT  distinct tac_contenedor as cod,
		 ifnull(concat(tac_contenedor, ' (', na.per_Apellidos,'-', pai_descripcion, '-', cl.per_Apellidos, ' ', cl.per_Nombres, ')'), concat(' N.D. (', tac_contenedor, ')')) as txt
 FROM 	liqtarjacabec
	LEFT JOIN opecontenedores on cnt_serial = tac_contenedor
	LEFT JOIN genpaises on pai_codpais = cnt_destino
	LEFT JOIN conpersonas na on na.per_codauxiliar = cnt_naviera
	LEFT JOIN conpersonas cl on cl.per_codauxiliar = cnt_consignatario
WHERE

	tac_semana = {pSeman} and
	tac_refoperativa = {pEmb} AND
	tac_contenedor  LIKE '%{query}%'
ORDER BY 2";

$_SESSION["OpTrTr_destilist"]=
"SELECT '-9999' as cod, ' TODOS' as txt UNION
SELECT  distinct ifnull(pai_codpais, 0) as cod, ifnull(pai_descripcion, 'N.D') as txt
 FROM 	liqtarjacabec
	LEFT JOIN opecontenedores on cnt_serial = tac_contenedor
	LEFT JOIN genpaises on pai_codpais = cnt_destino
WHERE	
	tac_semana = {pSeman} and
	tac_refoperativa = {pEmb} AND
	pai_descripcion  LIKE '%{query}%'
ORDER BY 2";

$_SESSION["OpTrTr_destfilist"]=
"SELECT '-9999' as cod, ' TODOS' as txt UNION
SELECT  par_secuencia as cod, par_descripcion as txt
 FROM 	genparametros
WHERE	
	par_clave = 'LDESTF' AND
	par_descripcion  LIKE '%{query}%'
ORDER BY 2";

$_SESSION["OpTrTr_consiglist"]=
"SELECT '-9999' as cod, ' TODOS' as txt UNION
SELECT  distinct ifnull(per_codauxiliar, 0) as cod,
	ifnull(concat(per_Apellidos, ' ', per_nombres), 'N.D') as txt
 FROM 	liqtarjacabec
	LEFT JOIN opecontenedores on cnt_serial = tac_contenedor
	LEFT JOIN genpaises on pai_codpais = cnt_destino
	LEFT JOIN conpersonas on per_codauxiliar = cnt_consignatario
WHERE	
	tac_semana = {pSeman} and
	tac_refoperativa = {pEmb} AND
	concat(per_Apellidos, ' ', per_nombres)  LIKE '%{query}%'
ORDER BY 2";



//obsafe_print_r($_SESSION, false, true, 4);
//echo"{'success':true}";
//ob_end_flush();
?>

