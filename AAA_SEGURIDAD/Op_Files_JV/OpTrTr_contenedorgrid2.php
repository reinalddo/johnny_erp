<?
/** @TODO: Ajustar la logica para embarques
*   Formulario de captura de datos de embarques
*   Utiliza una plantilla Html con  la estructura Basica del un grid ext, en la que se
*   sustituyen los valores requeridos por este script
*   El codigo JS siempre debe colocarse en un archivo con el mismo nombre de este script, pero
*   con extension js.
*   Define inicialmente las instrucciones Sql que debe aplicarse, grabandolas en vars de sesion
**/
//ob_start("ob_gzhandler");
ob_start();
header("Content-Type: text/html;  charset=ISO-8859-1");
include "../LibPhp/extTpl.class.php";
$goPanel = new clsExtTpl("../Ge_Files/GeGeGe_extform.tpl");
$goPanel->pageTitle="CONTENEDORES";
$goPanel->setTplVar("tpHeader", "CONTENEDORES");
$goPanel->addCssFile("global.css");
$goPanel->addCssRule(".floatleft {float:left} .floatnone {float:none}");
$goPanel->addBodyScript("../LibJava/ext-2.0-xt");
$goPanel->addBodyScript("../LibJava/ext-2.0/ux/menu/EditableItem");
$goPanel->addBodyScript("../LibJava/ext-2.0/ux/menu/RangeMenu");
$goPanel->addBodyScript("../LibJava/general");
$goPanel->addBodyScript("../LibJava/ext-2.0/ux/grid/GridFilters");
$goPanel->addBodyScript("../LibJava/ext-2.0/ux/grid/filter/Filter");
$goPanel->addBodyScript("../LibJava/ext-2.0/ux/grid/filter/StringFilter");
$goPanel->addBodyScript("../LibJava/ext-2.0/ux/grid/filter/DateFilter");
$goPanel->addBodyScript("../LibJava/ext-2.0/ux/grid/filter/ListFilter");
$goPanel->addBodyScript("../LibJava/ext-2.0/ux/grid/filter/NumericFilter");
$goPanel->addBodyScript("../LibJava/ext-2.0/ux/grid/filter/BooleanFilter");
$goPanel->addBodyScript("Autogrid");
$goPanel->addBodyScript("GridDef");
$goPanel->render();

$gsSesVar = 'OpTrTr_contenedor';
$_SESSION[$gsSesVar . '_bitKey']='GAR'; // Parametro para clave de bitacora
$_SESSION[$gsSesVar . '_bitID']='cnt_ID'; // Parametro que sirve para identificar la bitacora

/* Query principal */
$_SESSION[$gsSesVar]=
"SELECT cnt_ID, cnt_Serial,   cnt_Naviera,  cnt_SelloNav,     cnt_SelloCia,     cnt_Embarque,
  cnt_Destino,  cnt_Consignatario,          cnt_FecInicio,    cnt_HorInicio,    cnt_FecFin,     cnt_HorFin,
  date_format(cnt_Conexion, '%Y-%m-%d %H:%i') cnt_Conexion,
  date_format(cnt_Enchufe, '%Y-%m-%d %H:%i') cnt_Enchufe,
  date_format(cnt_CtrlTemp, '%Y-%m-%d %H:%i') cnt_CtrlTemp,
  date_format(cnt_CtrlEmbarque, '%Y-%m-%d %H:%i') cnt_CtrlEmbarque,
  cnt_Chequeador, 	          cnt_Estado, 	cnt_Observaciones, cnt_Temperatura,
	cnt_Usuario, 	cnt_FechaReg,	concat(con.per_Apellidos, ' ', ifNUll(con.per_Nombres,'')) as txt_Consignatario,
	concat(chq.per_Apellidos, ' ', ifNUll(chq.per_Nombres,'')) as txt_Chequeador,
  concat(nav.per_Apellidos, ' ', ifNUll(nav.per_Nombres,'')) as txt_Naviera,
  pai_Descripcion  as txt_Destino,
  concat(buq_Descripcion, '-', emb_numviaje, ', S ',
 IF(emb_SemInicio = emb_SemTermino, emb_SemInicio, concat(emb_SemInicio, '-',emb_Semtermino ))) as txt_Embarque,
  est.par_Descripcion  as txt_Estado
FROM	opecontenedores
	LEFT JOIN conpersonas con on con.per_codauxiliar = cnt_consignatario
	LEFT JOIN conpersonas chq on chq.per_codauxiliar = cnt_chequeador
  LEFT JOIN conpersonas nav on nav.per_codauxiliar = cnt_naviera
	left joiN liqembarques    on emb_refOperativa = cnt_embarque
	LEFT JOIN liqbuques       on buq_codbuque = emb_codvapor
  LEFT JOIN genpaises       on pai_codPais = cnt_destino
  LEFT JOIN genparametros est on est.par_clave = 'OGESTC' AND est.par_secuencia = cnt_Estado
WHERE	cnt_ID = {cnt_ID}";
$_SESSION[$gsSesVar . '_defs']['cnt_Usuario'] = $_SESSION['g_user'];
$_SESSION[$gsSesVar . '_defs']['cnt_FecRegistro'] = '@date("Y-m-d H:i:s")';

$_SESSION['pAdoDbg'] = fGetParam('rows',false);

include_once "../LibPhp/NoCache.php";
echo $gPaginaHtml;
?>

