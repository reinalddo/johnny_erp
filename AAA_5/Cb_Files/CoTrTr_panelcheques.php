<?
/*
*   Panel general de cheques
*   @created    25/Junio/09
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
$goPanel->title = $_SESSION["g_empr"] . " - CHEQUES ";
$goPanel->addCssFile("../LibJs/ext/resources/css/". (fGetParam("theme", "xtheme-gray")));
$goPanel->addCssFile("../LibJs/ext/resources/css/". (fGetParam("theme", "xtheme-gray"). "-colors"));
$goPanel->addCssFile("multiselect");
$goPanel->addBodyScript("../LibJs/ext/ux/menu/EditableItem");
$goPanel->addBodyScript("../LibJs/ext/ux/tree/ColumnNodeUI");
$goPanel->addBodyScript("../LibJs/extEstrPaneles");                 // Codigo comun que aplica la estructura Basica de paneles
$goPanel->addBodyScript("MultiSelect");
$goPanel->addBodyScript("DDView");
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
        .textLeft{
            text-align: left;
        }
        .x-btn-izq .x-btn-center { text-align: left;}
        
        .x-btn-izq .x-btn-over .x-btn-left,.x-btn-izq .x-btn-focus .x-btn-left{background-position:0 -108px;}
        .x-btn-izq .x-btn-over .x-btn-right,.x-btn-izq .x-btn-focus .x-btn-right{background-position:0 -144px;}
        .x-btn-izq .x-btn-over .x-btn-center,.x-btn-izq .x-btn-focus .x-btn-center{background-position:0 -180px;}
        
        .iconAnular{
            background-image:url(../Images/cross.png) !important;
        }
        .iconImprimir{
            background-image:url(../Images/printer.png) !important;
        }
        .iconConsultar{
            background-image:url(../Images/book_open.png) !important;
        }
        .iconSalir{
            background-image:url(../Images/cancel.png) !important;
        }
        .iconBuscar{
            background-image:url(../Images/find.png) !important;
        }
        .iconProgramar{
            background-image:url(../Images/calendar_edit.png) !important;
        }
        .iconEnviar{
            background-image:url(../Images/email_go.png) !important;
        }
        .iconConfirmar{
            background-image:url(../Images/accept.png) !important;
        }
        .iconProceder{
            background-image:url(../Images/package_go.png) !important;
        }
        .iconReimprimir{
            background-image:url(../Images/printer_add.png) !important;
        }
        
        .yellow-row{ background-color: #FBF8BF !important; }
        
        .noconfirm-row{ background-color: #CBF5CC !important; }
        

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

$_SESSION["CoTrTr_bancos"] = "SELECT per_CodAuxiliar cod, concat(per_Apellidos, ' ', per_Nombres) as txt 
                            FROM conpersonas JOIN concategorias ON  cat_codauxiliar = per_codauxiliar
                             WHERE cat_categoria = 10
                            and concat(per_Apellidos, ' ', per_Nombres) LIKE '%{query}%'
                            order by per_Apellidos,per_Nombres";
                            
$_SESSION["CoTrTr_estado"] = "select par_Secuencia cod, par_Descripcion txt from genparametros
                                        where par_clave='ESDOC'
                                        and par_Descripcion LIKE '%{query}%'
                                    order by par_Secuencia";

$_SESSION[$gsSesVar. "_aplic"]= "";
$goPanel->render();
ob_end_flush();
?>

