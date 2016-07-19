<?
/*
*   Panel general de Operaciones
*   @created    Oct/30/07
*   @author     fah
*   @
*/
if (!isset ($_SESSION)) session_start();
ob_start("ob_gzhandler");
include_once('GenUti.inc.php');
include "../LibPhp/extTpl.class.php";
include_once "../LibPhp/NoCache.php";
include_once "../De_Files/DeGeGe_cabecera.php";
$goPanel = new clsExtTpl("../Ge_Files/GeGeGe_extpanels.tpl");
$goPanel->addCssFile("../LibJs/ext/resources/css/xtheme-slate");
$goPanel->addBodyScript("../LibJs/ext/ux/menu/EditableItem");
/*echo $goPanel->scripts;
echo "-----";
echo $goPanel->preScripts;
echo $goPanel->postScripts;
echo "-----";
*/
//$goPanel->addBodyScript("../LibJava/ext/ux/tree/column-tree");
$goPanel->addBodyScript("../LibJs/ext/ux/tree/ColumnNodeUI");
$goPanel->addBodyScript("OpTrTr_panelopestr"); //Estructura basica de paneles
//$goPanel->addBodyScript("OpTrTr_panelop");
$slCss= ".x-grid-group {
            font-family:arial,tahoma,helvetica,sans-serif;
            font-size:11px;	
            font-size-adjust:none;
            font-style:normal;
            font-variant:normal;
            font-weight:bold;
            line-height:13px;
            white-space:nowrap;
        }

        .x-grid-emptyrow {
            font-family:arial,tahoma,helvetica,sans-serif;
            font-size:11px;
            font-size-adjust:none;
            font-style:italic;
            font-variant:normal;
            font-weight:bold;
            line-height:13px;
            white-space:nowrap;
        }
    
        /* Example Styles... */
        .cell-align-right .x-grid-cell-text  {
            text-align: right;
        }
		.boton-menu{width:210px !important}
        .leftfloated {float: left} 
	";
$goPanel->addCssRule($slCss);
include("OpTrTr_panelop.qry.php");  // Incluir archivo quedefine instrucciones SQL
/*
*       Modificar un precio de tarjas
*/
$_SESSION[$gsSesVar. "_aplic"]=
"Update liqtarjacabec JOIN liqtarjadetal on tad_numtarja = tar_numtarja
	set tad_valUnitario = {pPOfi},
		tad_difUnitario = {pDife}
WHERE {pCond} ";

$_SESSION["OpTrTr_embarlist"]=
"SELECT  emb_refoperativa as cod, concat(buq_Descripcion, '-', emb_numviaje, ', S ',
 IF(emb_SemInicio = emb_SemTermino, emb_SemInicio, concat(emb_SemInicio, '-',emb_Semtermino ))) AS txt
FROM 	liqembarques
	JOIN liqbuques on buq_codbuque = emb_codvapor
WHERE	emb_estado BETWEEN 0 and 40 AND
	{pSeman} between emb_SemInicio and emb_SemTermino AND
  concat(buq_Descripcion, '-', emb_numviaje) LIKE '%{query}%'";

$_SESSION["OpTrTr_navilist"]=
"SELECT '-9999' as cod, ' TODOS' as txt UNION
SELECT  distinct cnt_Naviera as cod, concat(per_apellidos, ' ', per_Nombres) AS txt
FROM 	liqembarques
        JOIN liqbuques on buq_codbuque = emb_codvapor
        JOIN opecontenedores  on  cnt_Embarque = emb_refoperativa
        JOIN conpersonas on per_CodAuxiliar = cnt_naviera
WHERE	emb_estado BETWEEN 0 and 40 AND
  {pSeman} between emb_SemInicio and emb_SemTermino AND
   concat(buq_Descripcion, '-', emb_numviaje, ', S ',
 IF(emb_SemInicio = emb_SemTermino, emb_SemInicio, concat(emb_SemInicio, '-',emb_Semtermino ))) LIKE '%{pVapo}%' AND
  concat(per_apellidos, ' ', per_Nombres) LIKE '%{query}%'";  

$_SESSION["OpTrTr_frutlist"]=
"SELECT '-9999' as cod, ' TODOS' as txt UNION
 SELECT distinct 
            txp_CodProducto as cod,
            txp_Producto as txt
        FROM v_opetarjexpand 
        WHERE txp_refOperativa = {pEmb} AND txp_Producto LIKE '%{query}%'
        ORDER BY 2" ;  

$goPanel->render();
ob_end_flush();
?>

