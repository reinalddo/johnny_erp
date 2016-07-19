<?
/*
*   Panel general de Operaciones
*   @created    Oct/30/07
*   @author     fah
*   @
*/
/************************************************************************* Inclusion de librerias bassicas */
if (!isset ($_SESSION)) session_start();
ob_start("ob_gzhandler");
include_once('GenUti.inc.php');
include "../LibPhp/extTpl.class.php";
include_once "../LibPhp/NoCache.php";
include_once "../De_Files/DeGeGe_cabecera.php";
$goPanel = new clsExtTpl("../Ge_Files/GeGeGe_extpanels.tpl");
$goPanel->title = $_SESSION["g_empr"] . " - CONSOLIDACION DE MOVIMIENTOS ";
$goPanel->addCssFile("../LibJs/ext/resources/css/". (fGetParam("theme", "xtheme-gray")));
$goPanel->addCssFile("../css/multiselect");
$goPanel->addCssFile("../LibJs/ext/resources/css/". (fGetParam("theme", "xtheme-gray"). "-colors"));
$goPanel->addBodyScript("../LibJs/ext/ux/Ext.ux.gen.cmbBox");
$goPanel->addBodyScript("../LibJs/ext/ux/Ext.ux.ms.DDView");
$goPanel->addBodyScript("../LibJs/ext/ux/Ext.ux.ms.MultiSelect");
$goPanel->addBodyScript("../LibJs/extEstrPaneles");                 // Codigo comun que aplica la estructura Basica de paneles
//$goPanel->addBodyScript("OpTrTr_pruestr");                          // OPCIONAL:  Incluir JS con Estructura basica de paneles
$slCss= ".x-grid-group {                                                // OPCIONAL:  Declarar una Regla CSS 
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
       
        #north_left {
            float:left;
            width:50%;
            height:70px;
            padding:0;
            margin:0;
            background-color:#ffffff;
            background-image:url(" . (isset($_SESSION['logo_emp'])? $_SESSION['logo_emp']: "logo_app.png" ) ." );
            background-repeat:no-repeat;
            text-align:left;
        }
        #north_right{
            float:right;
            width:50%;
            height:70px; 
            padding:0;
            margin:0;
            background-color:#ffffff;
            background-image:url(" . (isset($_SESSION['logo_mod'])? $_SESSION['logo_mod']: "header_app_caja.jpg" ) ." );
            background-repeat:no-repeat;
            text-align:right;
        }
        Body{
			align:center;
        }
	";
$goPanel->addCssRule($slCss);                                           // OPCIONAL Incluir la Regla declarada previamente, que se aplica a la pagina resultante
/*
*       DEfinicion de variables de sesion que contiene las instrucciones SQl que se ejecutaran via ajax en el servidor
*       cuando se renderize el Front End
*/
$gsSesVar = "cns_";                                                     //Prefijo de Id se variable de sesion.
// data source para combo de periodos (semanas a consolidar)
$_SESSION[$gsSesVar . 'periodos'] = "SELECT per_numperiodo as cod, 
    concat(per_numperiodo, '-', date_format(per_fecinicial, '%d/%M/%y' )) as txt
    from conperiodos 
    where per_Aplicacion = 'LI' AND  per_Estado=1/*per_bandera <= 3*/
    and concat(per_numperiodo, '-', date_format(per_fecinicial, '%d/%M/%y' )) LIKE '%{query}%'
    order by per_numperiodo desc
";
// data source para combo de empresas (semanas a consolidar)
$_SESSION[$gsSesVar . 'empresas'] = "SELECT emp_basedatos as cod, emp_descripcion as txt
    FROM seguridad.segempresas
    WHERE emp_consolidacion LIKE 'ICL%'
";

$_SESSION[$gsSesVar . '_defs']['start']=0;                              //Valores Operativos minimos para los grids
$_SESSION[$gsSesVar . '_defs']['limit']=25;                             //Valores Operativos minimos para los grids
if(fGetParam('pAdoDbg',false)) $_SESSION['pAdoDbg']= fGetParam('pAdoDbg',false);
$goPanel->render();
ob_end_flush();
?>

