<?
/*
*   Panel vincular Ordenes de compra con Ingresos a Bodega
*   @created    14/May/09
*   @author     Gina Franco
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
$goPanel->title = $_SESSION["g_empr"] . " - Enlazar OC-IB ";
$goPanel->addCssFile("../LibJs/ext/resources/css/". (fGetParam("theme", "xtheme-gray")));
$goPanel->addCssFile("../LibJs/ext/resources/css/". (fGetParam("theme", "xtheme-gray"). "-colors"));
$goPanel->addBodyScript("../LibJs/ext/ux/menu/EditableItem");
$goPanel->addBodyScript("../LibJs/ext/ux/tree/ColumnNodeUI");
$goPanel->addBodyScript("../LibJs/extEstrPaneles");                 // Codigo comun que aplica la estructura Basica de paneles
$goPanel->addBodyScript("../LibJs/ext/ux/Ext.ux.gen.cmbBox");
$goPanel->addBodyScript("../LibJs/extExtensions");
$goPanel->addJsAfterScripts("window.title='" . $_SESSION['g_empre'] . "';");
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
        .iconImprimir{
            background-image:url(../Images/printer.png) !important;
        }
        .iconGrabar{
            background-image:url(../Images/disk.png) !important;
        }
	";
$goPanel->addCssRule($slCss);                                           // OPCIONAL Incluir la Regla declarada previamente, que se aplica a la pagina resultante
include("work_panels.qry.php");                                         // OPCIONAL Incluir archivo que define instrucciones SQL
/*
*       DEfinicion de variables de sesion que contiene las instrucciones SQl que se ejecutaran via ajax en el servidor
*       cuando se renderize el Front End
*/
$gsSesVar = "XXXX";                                                     //Prefijo de Id se variable de sesion.

$_SESSION[$gsSesVar . '_defs']['start']=0;                              //Valores Operativos minimos para los grids
$_SESSION[$gsSesVar . '_defs']['limit']=25;                             //Valores Operativos minimos para los grids
if(fGetParam('pAdoDbg',false)) $_SESSION['pAdoDbg']= fGetParam('pAdoDbg',false);

$glExpanded = fGetParam('pExpan', 1);

$_SESSION[$gsSesVar. "_aplic"]= "";

$_SESSION["CoTrTr_tipoComp"] = "SELECT cla_tipocomp as cod,  
                concat(cla_tipocomp , ' - ', cla_descripcion) as txt 
                FROM genclasetran WHERE cla_Indicador = 1 and
                concat(cla_tipocomp , ' - ', cla_descripcion) LIKE '%{query}%'";
                
$_SESSION["CoTrTr_Proveedor"] = "SELECT conpersonas.per_CodAuxiliar as cod,CONCAT(conpersonas.per_Apellidos,'',conpersonas.per_Nombres) AS txt
                                FROM conpersonas 
                                WHERE per_TipoID=2 AND
                                CONCAT(conpersonas.per_Apellidos,'',conpersonas.per_Nombres) LIKE '%{query}%'";     


                            



$goPanel->render();
ob_end_flush();
?>

