<?
/** @TODO: Ajustar la logica para embarques
*   Formulario de captura de datos de embarques
*   Utiliza una plantilla Html con  la estructura Basica del un grid ext, en la que se
*   sustituyen los valores requeridos por este script
*   El codigo JS siempre debe colocarse en un archivo con el mismo nombre de este script, pero
*   con extension js.
*   Define inicialmente las instrucciones Sql que debe aplicarse, grabandolas en vars de sesion
*   @rev    fah 27/02/08        Soporte de tipo deconenedor
*   @rev    fah 15/08/08        Registrar campo ventilacion (%)
**/
ob_start("ob_gzhandler");
header("Content-Type: text/html;  charset=ISO-8859-1");
include "../LibPhp/extTpl.class.php";
include "GenUti.inc.php";
$goPanel = new clsExtTpl("../Ge_Files/GeGeGe_extform.tpl");
$goPanel->pageTitle="CONTENEDORES";
$goPanel->setTplVar("tpHeader", "CONTENEDORES");
//$goPanel->addCssRule("fieldset {float:left}");
$goPanel->addCssRule(".floatleft {float:left} .floatnone {float:none}");
$goPanel->addBodyScript("../LibJava/ext-2.0-xt");
//$goPanel->addBodyScript("../LibJava/ext-2.0/examples/examples"); // @TODO Revisar si es necesario
$goPanel->render();

$gsSesVar = 'OpTrTr_contenedor';
$_SESSION[$gsSesVar . '_bitKey']='GAR'; // Parametro para clave de bitacora
$_SESSION[$gsSesVar . '_bitID']='cnt_ID'; // Parametro que sirve para identificar la bitacora

/* Query principal */
$_SESSION[$gsSesVar]=
"SELECT cnt_ID, cnt_Serial,   cnt_Naviera,  cnt_Tipo,        cnt_SelloNav,     cnt_SelloCia,     cnt_Embarque,
  cnt_Destino,  cnt_Consignatario,
  date_format(cnt_FecInicio, '%Y-%m-%d %H:%i') cnt_FecInicio,
  cnt_HorInicio,
  cnt_HorFin,
  date_format(cnt_FecFin, '%Y-%m-%d %H:%i') cnt_FecFin,
  date_format(cnt_Enchufe, '%Y-%m-%d %H:%i') cnt_Enchufe,
  date_format(cnt_CtrlTemp, '%Y-%m-%d %H:%i') cnt_CtrlTemp,
  date_format(cnt_CtrlEmbarque, '%Y-%m-%d %H:%i') cnt_CtrlEmbarque,
  date_format(cnt_FecZarpe, '%Y-%m-%d %H:%i') cnt_FecZarpe,
  cnt_Chequeador, 	          cnt_Estado, 	cnt_Observaciones, cnt_Temperatura,
	cnt_Usuario, 	cnt_FechaReg,	concat(con.per_Apellidos, ' ', ifNUll(con.per_Nombres,'')) as txt_Consignatario,
	concat(chq.per_Apellidos, ' ', ifNUll(chq.per_Nombres,'')) as txt_Chequeador,
  concat(nav.per_Apellidos, ' ', ifNUll(nav.per_Nombres,'')) as txt_Naviera,
  pai_Descripcion  as txt_Destino,
  tip.par_Descripcion  as txt_Tipo,
  concat(buq_Descripcion, '-', emb_numviaje, ', S ',
 IF(emb_SemInicio = emb_SemTermino, emb_SemInicio, concat(emb_SemInicio, '-',emb_Semtermino ))) as txt_Embarque,
  est.par_Descripcion  as txt_Estado,
  cnt_DestiFinal, dfi.par_descripcion as txt_DestiFinal,
  cnt_Chequeador2, concat(ch2.per_Apellidos, ' ', ifNUll(ch2.per_Nombres,'')) as txt_Chequeador2,
  cnt_Chequeador3, concat(ch3.per_Apellidos, ' ', ifNUll(ch3.per_Nombres,'')) as txt_Chequeador3,
  cnt_CodCajTra, cnt_TipoCajTra, cnt_CantCajTra,
  cnt_Paletizado, cnt_CantDeclarada,
  cnt_Ventilacion
FROM	opecontenedores
	LEFT JOIN conpersonas con on con.per_codauxiliar = cnt_consignatario
	LEFT JOIN conpersonas chq on chq.per_codauxiliar = cnt_chequeador
    LEFT JOIN conpersonas nav on nav.per_codauxiliar = cnt_naviera
	LEFT JOIN conpersonas ch2 on ch2.per_codauxiliar = cnt_chequeador2
	LEFT JOIN conpersonas ch3 on ch3.per_codauxiliar = cnt_chequeador3
	left joiN liqembarques    on emb_refOperativa = cnt_embarque
	LEFT JOIN liqbuques       on buq_codbuque = emb_codvapor
    LEFT JOIN genpaises       on pai_codPais = cnt_destino
	LEFT JOIN genparametros dfi on dfi.par_clave = 'LDESTF'  AND dfi.par_secuencia = cnt_DestiFinal
    LEFT JOIN genparametros est on est.par_clave = 'OGESTC' AND est.par_secuencia = cnt_Estado
    LEFT JOIN genparametros tip on tip.par_clave = 'OGTIPC' AND tip.par_secuencia = cnt_tipo
WHERE	cnt_ID = {cnt_ID}";
$_SESSION[$gsSesVar . '_defs']['cnt_Usuario'] = $_SESSION['g_user'];
$_SESSION[$gsSesVar . '_defs']['cnt_FecRegistro'] = '@date("Y-m-d H:i:s")';

/*  Ordenes Activas */
$_SESSION[$gsSesVar . '_emb']=
"SELECT  emb_refoperativa as cod, concat(buq_Descripcion, '-', emb_numviaje, ', S ',
 IF(emb_SemInicio = emb_SemTermino, emb_SemInicio, concat(emb_SemInicio, '-',emb_Semtermino ))) AS txt
FROM 	liqembarques
		JOIN liqbuques on buq_codbuque = emb_codvapor
WHERE	emb_estado BETWEEN 0 and 40 AND
  concat(buq_Descripcion, '-', emb_numviaje) LIKE '%{query}%'";

/*Consignatarios, Chequeadores, pTipo= Tipo de Persona*/
$_SESSION[$gsSesVar . '_per']=
	"SELECT per_codauxiliar as 'cod'," .
		"concat(per_Apellidos, ' ', per_Nombres) AS txt ".
	"FROM conpersonas join concategorias on cat_codauxiliar = per_codauxiliar ".
	"WHERE cat_categoria  = {pTipo} AND concat(per_Apellidos, ' ', per_Nombres) LIKE '%{query}%' " .
	"ORDER BY 2 ";
$_SESSION[$gsSesVar . '_chk']=
    "SELECT 0 as 'cod', '--' AS txt
        UNION
    SELECT per_codauxiliar as 'cod'," .
		"concat(per_Apellidos, ' ', per_Nombres) AS txt ".
	"FROM conpersonas join concategorias on cat_codauxiliar = per_codauxiliar ".
	"WHERE cat_categoria  = {pTipo} AND per_personacontacto LIKE 'Cheq%' AND concat(per_Apellidos, ' ', per_Nombres) LIKE '%{query}%' " .
	"ORDER BY 2";

/*Tipos de contenedores*/
$_SESSION[$gsSesVar . '_tip']=
"SELECT par_secuencia AS cod, par_descripcion AS txt ".
"FROM genparametros ".
"WHERE par_clave = 'OGTIPC' and par_descripcion LIKE '{query}%' " .
"ORDER BY 1 ";

/*Destino final  contenedores*/
$_SESSION[$gsSesVar . '_dfi']=
"SELECT par_secuencia AS cod, par_descripcion AS txt ".
"FROM genparametros ".
"WHERE par_clave = 'LDESTF' and par_descripcion LIKE '{query}%' " .
"ORDER BY 1 ";


/*Estados de contenedores*/
$_SESSION[$gsSesVar . '_est']=
"SELECT par_secuencia AS cod, par_descripcion AS txt ".
"FROM genparametros ".
"WHERE par_clave = 'OGESTC' and par_descripcion LIKE '{query}%' " .
"ORDER BY 1 ";

/*Destinos*/
$_SESSION[$gsSesVar . '_des']=
"SELECT pai_codpais as cod, pai_descripcion as txt ".
"FROM genpaises ".
"WHERE pai_descripcion LIKE '{query}%' " .
"ORDER BY 2 ";

$_SESSION['pAdoDbg'] = fGetParam('pAdoDbg',false);
include_once "../LibPhp/NoCache.php";
echo $gPaginaHtml;
?>

