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
$goPanel->title = $_SESSION["g_empr"] . " - COMPROBANTES ";
$goPanel->addCssFile("../LibJs/ext/resources/css/". (fGetParam("theme", "xtheme-gray")));
$goPanel->addCssFile("../LibJs/ext/resources/css/". (fGetParam("theme", "xtheme-gray"). "-colors"));
$goPanel->addBodyScript("../LibJs/ext/ux/menu/EditableItem");
$goPanel->addBodyScript("../LibJs/ext/ux/tree/ColumnNodeUI");
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
$gsSesVar = "CoTrTr_";                                                     //Prefijo de Id se variable de sesion.

//  Tipos de Comprobante y su estructura
$_SESSION[$gsSesVar . "tipocomp"] = "
    select 	cla_aplicacion,         cla_tipoComp as 'cod',          cla_Descripcion as 'txt', 
        cla_Formulario,             cla_Formulario2,                cla_TipoMoviliza, 
        cla_Informe,                cla_TipoEmisor,                 cla_TipoReceptor, 
        cla_IndCheque,              cla_ReqEmisor,                  cla_ReqReceptor, 
        cla_TxtEmisor,              cla_TxtReceptor,                cla_Indicador, 
        cla_EmiDefault,             cla_RecDefault,                 cla_QryAuxiliar, 
        cla_Contabilizacion,        cla_IndTransfer,                cla_LisPrecios, 
        cla_LisCostos,              cla_CosFijo,                    cla_PreFijo, 
        cla_VerCosto,               cla_VerPrecio,                  cla_CtaOrigen, 
        cla_AuxOrigen,              cla_CtaDestino,                 cla_AuxDestino, 
        cla_CtaIngresos,            cla_CtaCosto,                   cla_CtaDiferencia, 
        cla_ReqReferencia,          cla_ReqSemana,                  cla_ClaTransaccion, 
        cla_IndiCosteo,             cla_ImpFlag,                    cla_Usuario, 
        cla_FecRegistro,            cla_CodSecuencia,               cla_IndiceIva, 
        cla_IndiceIce,              cla_ReqNumSerie,                cla_QryROCabec, 
        cla_QryRODetalle,           cla_ReqEstadoDet,               cla_ReqDestinDet, 
        cla_QryDestinDet,           cla_ReqCantidad,                cla_PostComprob, 
        cla_PostDetalle,            cla_PreComprob,                 cla_PreDetalle, 
        cla_Duplicable,             cla_ProcCheque,                 cla_QryEstadoDet, 
        cla_ProcTexto
        FROM 
        genclasetran 
        "
        ;
/* SQl para Combos de Personas, requiere especificar categoria como parametro 'pCate'*/
$_SESSION[$gsSesVar . "personas"] =
    "SELECT 
        vau_codauxiliar as 'cod',
        concat(vau_codauxiliar, ' - ', vau_descripcion) as 'txt' 
    FROM v_auxgeneralcate
    WHERE vau_categoria = {pCate} AND concat(vau_codauxiliar, ' - ', vau_descripcion) LIKE '{query}%'
    ORDER BY 1";
    
/* SQl para Combos de Estados, */
$_SESSION[$gsSesVar . '_estados']=
"SELECT par_secuencia AS cod, par_descripcion AS txt ".
"FROM genparametros ".
"WHERE par_clave = 'GEST' and par_descripcion LIKE '{query}%' " .
"ORDER BY 1 ";

$_SESSION[$gsSesVar . '_defs']['start']=0;                              //Valores Operativos minimos para los grids
$_SESSION[$gsSesVar . '_defs']['limit']=25;                             //Valores Operativos minimos para los grids
if(fGetParam('pAdoDbg',false)) $_SESSION['pAdoDbg']= fGetParam('pAdoDbg',false);

$glExpanded = fGetParam('pExpan', 1);

$_SESSION[$gsSesVar. "_aplic"]= "";
$goPanel->render();
ob_end_flush();
?>

