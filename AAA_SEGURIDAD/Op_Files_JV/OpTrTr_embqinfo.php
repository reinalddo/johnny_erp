<?
/*
*   Generar una lista XMl con informacion de los embarques de una semana
*   @created    10/09/08   
*   @author     fah
*   @param      integer  pSem   Semana a consultar
*   @param      integer  pQry   Texto a filtrar
*/
$_SESSION["OpTrTr_embarlist"]=
"SELECT  emb_refoperativa as cod, concat(buq_Descripcion, '-', emb_numviaje, ', S ',
 IF(emb_SemInicio = emb_SemTermino, emb_SemInicio, concat(emb_SemInicio, '-',emb_Semtermino ))) AS txt
FROM 	liqembarques
	JOIN liqbuques on buq_codbuque = emb_codvapor
WHERE	emb_estado BETWEEN 0 and 40 AND
	636 between emb_SemInicio and emb_SemTermino AND
  concat(buq_Descripcion, '-', emb_numviaje) LIKE '%{pQry}%'";
include_once("../Ge_Files/GeGeGe_queryToXml.php");
/*
"SELECT  emb_refoperativa as cod, concat(buq_Descripcion, '-', emb_numviaje, ', S ',
 IF(emb_SemInicio = emb_SemTermino, emb_SemInicio, concat(emb_SemInicio, '-',emb_Semtermino ))) AS txt
FROM 	liqembarques
	JOIN liqbuques on buq_codbuque = emb_codvapor
WHERE	emb_estado BETWEEN 0 and 40 AND
	{pSem} between emb_SemInicio and emb_SemTermino AND
  concat(buq_Descripcion, '-', emb_numviaje) LIKE '%{pQry}%'";
include_once("../Ge_Files/GeGeGe_queryToXml.php");
*/
?>