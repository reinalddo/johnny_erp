<?php
/*
*   Presentecion de un Grid que se inserta en componentes ya exietntes, no genera codigo html de la pagina,
*   solo el requerido para armar el componente Ext
*   Utiliza una plantilla Html con  la estructura Basica del un grid ext, en la que se
*   sustituyen los valores requeridos por este script
*   El codigo JS siempre debe colocarse en un archivo con el mismo nombre de este script, pero
*   con extension js.
*   Define la informacion que configura el gris, pero no lo renderiza, para que se pueda agregar opciones
*   de configuracion y renderizarlo luego. Renderizar en el script final.
*   @see GeGeGe_loadgrid.php
*
*   @rev    Gina Franco 26-May-09   agregue archivo Ext.ux.grid.printergrid_0.0.1
*   
**///ob_start("ob_gzhandler");
//ob_start();
header("Content-Type: text/html;  charset=ISO-8859-1");
include "../LibPhp/extTpl.class.php"; 
if (!isset($tplFile)) $tplFile= "../Ge_Files/GeGeGe_extbody.tpl"; // Template to load
$goGrid = new clsExtTpl($tplFile, fGetParam("pPagina", false), false); // generar solo componentes
$goGrid->isPage = fGetParam("pPagina", false); // Indicador de pagina stand alone
$goGrid->addCssRule(
    ".x-grid3-hd-row td.ux-filtered-column {font-style: italic; font-weight: bold;}
    .negative {color: #dd0000;}
    .positive {color: green;}
    .x-grid3-cell-inner {font-family:'segoe ui',tahoma, arial, sans-serif;}
    .x-grid-group-hd div {font-family:'segoe ui',tahoma, arial, sans-serif;}
    .x-grid3-hd-inner {font-family:'segoe ui',tahoma, arial, sans-serif;
        font-size:12px;}
    .x-grid3-body .x-grid3-td-cost {background-color:#f1f2f4;}
    .x-grid3-summary-row .x-grid3-summary-table .x-grid3-td-cost {background-color:#e1e2e4; font-style:italic; font-weight:bold}
    .x-grid3-summary-row {background-color:#e1e2e4; font-style:italic; font-weight:bold}
    .x-grid3-summary-table {background-color:#e1e2e4; font-style:italic; font-weight:bold}
    .x-grid3-summary-cell  {font-style:italic; font-weight:bold}
    .icon-grid {background-image:url(../LibJs/ext/resources/icons/fam/grid.png) !important;}
    .new-item {background-image:url(../Images/App/new-tab.gif)!important;}
    .save {background-color: red; background-image:url(../Images/App/new-tab.gif)!important;}
    .x-grid3-dirty-cell {background-image:none;}");
$goGrid->addOptions("summary, filter");		// add this to Basic Options for a grid
$goGrid->addBodyScript("../LibJs/ext/ux/Ext.ux.gen.cmbBox");
$goGrid->addBodyScript("../LibJs/ext/ux/Ext.ux.grid.printergrid_0.0.1");
/*$goGrid->addBodyScript("../LibJs/general");
$goGrid->addBodyScript("../LibJs/extExtensions");
$goGrid->addBodyScript("../LibJs/ext/ux/grid/GridSummary");
$goGrid->addBodyScript("../LibJs/ext/ux/grid/GroupSummary");
$goGrid->addBodyScript("../LibJs/ext/ux/grid/RowExpander");
$goGrid->addBodyScript("../LibJs/ext/ux/menu/RangeMenu");
$goGrid->addBodyScript("../LibJs/ext/ux/menu/EditableItem");
$goGrid->addBodyScript("../LibJs/ext/ux/grid/GridFilters");
$goGrid->addBodyScript("../LibJs/ext/ux/grid/filter/Filter");
$goGrid->addBodyScript("../LibJs/ext/ux/grid/filter/StringFilter");
$goGrid->addBodyScript("../LibJs/ext/ux/grid/filter/DateFilter");
$goGrid->addBodyScript("../LibJs/ext/ux/grid/filter/ListFilter");
$goGrid->addBodyScript("../LibJs/ext/ux/grid/filter/NumericFilter");
$goGrid->addBodyScript("../LibJs/ext/ux/grid/filter/BooleanFilter");*/
$goGrid->addBodyScript("../LibJs/extAutogrid");
$gsUrl=fGetParam('pUrl', false); // el datasource para el grid
$gsObj=fGetParam('pObj', 'grid'); // NOmbre del Objeto Grid
if ($gsUrl == false) $gsUrl = $_SERVER["PHP_SELF"];     // este mismo script es el origen de datos
$goGrid->addJsBeforeScripts("sgLoadUrl='$gsUrl'; gsObj='$gsObj'");
?>
