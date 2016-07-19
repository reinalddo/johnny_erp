<?
/**
*   Grid de estados de Tramites de Clientes
*   Utiliza una plantilla Html con  la estructura Basica del un RicoLive Grid , en la que se
*   sustituyen los valores requeridos por este script
*   El codigo JS siempre debe colocarse en un archivo con el mismo nombre de este script, pero
*   con extension js.
*   Define inicialmente las instrucciones Sql que debe aplicarse, grabandolas en vars de sesion
**/
if (!isset ($_SESSION)) session_start();
$gbCabecera =false;
include('GenUti.inc.php');
include_once "../De_Files/DeGeGe_cabecera.php";
$FileName=basename($_SERVER["PHP_SELF"], ".php"); //   Definicion obligatoria para que funciones Seglib
//$HtmlFile = $FileName .".html";
$HtmlFile = "../Ge_Files/GeGeGe_ricogrid.tpl" ;  // Template para Grids basados en Rico LiveGrid.
$gf = fopen($HtmlFile, "r");           //    Cargar Plantilla HTML
$gPaginaHtml = fread ($gf, filesize ($HtmlFile));
fclose ($gf);
if ($gbCabecera && strpos($gPaginaHtml, "{_tpCabecera_}") >= 0){
    $olCabecera = new clsCabecera();
    $gPaginaHtml= str_replace("{_tpCabecera_}",  $olCabecera->getHtmlOut(), $gPaginaHtml) ;
    }
else {
	$gPaginaHtml= str_replace("{_tpCabecera_}",  '', $gPaginaHtml) ;
}
// Sustituir variables Basicas de Plantilla
$slPreScript ='';
$ilWidth ="620px";
$ilHeight="415px";
$slPostScript  ='<script type="text/javascript" src="' .$FileName . '.js"></script>';
$gPaginaHtml = str_replace("<!--_tpPreScript_-->",    $slPreScript,  $gPaginaHtml) ;
$gPaginaHtml = str_replace("<!--_tpPostScript_-->",   $slPostScript, $gPaginaHtml) ;
$gPaginaHtml = str_replace("{_tpGridStyle_}",  "width:$ilWidth;height:$ilHeight;align:'center'"  , $gPaginaHtml) ;
$gPaginaHtml = str_replace("{_tpTitle_}",    "RESUMEN DE PRECIOS"  , $gPaginaHtml) ;
//  Definir una condicion de filtro para el usuario de cliente
//print_r ($_SESSION);
$slCondUsuario = "";
if ($_SESSION['g_usugru'] == 20 || $_SESSION['g_usugru'] == 25){
	$slCondUsuario = " ord_cliente = " . $_SESSION['g_usuaux'] . " AND " ;

} /*else $slCondUsuario = "eve_Usuario LIKE '". $_SESSION['g_user'] . "' AND "*/;
$gsSesVar = 'InTrTr_resprecprov';
$_SESSION[$gsSesVar]=
"SELECT SQL_CALC_FOUND_ROWS ".
    	"ord_ID, concat(ti.par_valor2,  '-', ord_numdocum) as txt_Orden, ".
		"ord_Descripcion, ".
		"concat(fa.per_Apellidos, ' ', fa.per_Nombres) as txt_FuncAsignado, ".
		"date_format(ord_fecinicio, '%d/%m/%y') as txt_Inicio, " .
		"date_format(ord_fectermino,'%d/%m/%y') as txt_Termino, " .
		"es.par_descripcion As txt_Estado, ord_Usuario ".
	"FROM serordenes ".
		"left join  conpersonas fa  on fa.per_codauxiliar = ord_funcasignado ".
		"left JOIN genparametros  es on es.par_CLAVE = 'VGEST' AND es.par_secuencia  = ord_Estado ".
		"left JOIN genparametros  ti on ti.par_CLAVE = 'VGTIP' AND ti.par_secuencia  = ord_Tipo " .
	"WHERE   " . $slCondUsuario .
			"ord_fecinicio BEtween '{pDesde}' AND '{pHasta}' ".
	"ORDER BY ord_FecInicio DESC, ord_ID DESC " .
	" LIMIT {start}, {limit}";
$slTramite=$_GET["pText"];
$_SESSION[$gsSesVar . '_defs']['start']=0;
$_SESSION[$gsSesVar . '_defs']['limit']=25;
if(!isset($_SESSION['pAdoDbg'])) $_SESSION['pAdoDbg']= fGetParam('pAdoDbg',false);
if(!isset($_SESSION['pAppDbg'])) $_SESSION['pAppDbg']= fGetParam('pAppDbg',false);
include_once "../LibPhp/NoCache.php";
$slHeader= "<table  id='oGrid_header' align='center' class='ricoLiveGrid' cellspacing='0'
			cellpadding='0'>
        <tr>
        	<th style='width:60px;'>CODIGO</th>
            <th style='width:350px;'>PROVEEDOR</th>
            <th style='width:45px;'>AÑO</th>
        	<th style='width:25px;'>MES</th>
        	<th style='width:80px;'>COMPRA</th>
        </tr>
	  <tr class='dataInput'>
		  <th class='ricoFrozen'>
		  <input type='text' onkeyup='keyfilter(this,0)' size='5'></th>
		  <th><input type='text' onkeyup='keyfilter(this,1)' size='5'></th>
		  <th><input type='text' onkeyup='keyfilter(this,2)'></th>
		  <th><input type='text' onkeyup='keyfilter(this,3)'></th>

		  <th><input type='text' onkeyup='keyfilter(this,4)'></th>
		  <th>&nbsp;</th>
		  <th>&nbsp;</th>
	  </tr>
    </table>"
echo $gPaginaHtml;
?>
