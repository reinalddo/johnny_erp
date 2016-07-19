<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Tarjas por Vapor, marca:
	  Tabla de doble entrada, en vertcal los productores, agrupados por zona; en la horizontal las marcas agrupadas por producto -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Gina Franco" />
  {literal}
  <style type="text/css">
        .padding10{padding:10px;}
        .empresa{height:50px;font-size:20px;font-weight:bold;text-align:center;vertical-align:middle;}
        .titulo{font-weight:bold; text-align:center; vertical-align:middle; text-transform:uppercase;}
        .parrafo{text-align:justify; vertical-align:middle; text-transform:uppercase;}
        .bordeCompleto {border: #000000 solid 1px;}
        .bordeDer {border-right: #000000 solid 1px;}
        .bordeIzq {border-left: #000000 solid 1px;}
        .bordeSup {border-top: #000000 solid 1px;}
        .bordeInf {border-bottom: #000000 solid 1px;}
        .izq{text-align:left;}
        .der{text-align:right;}
    </style>
  {/literal}
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
  <!--<link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_tablas_print.css" title="CSS para imprimir" />-->
  
    <title>Resumen de Gastos</title>
    
    
  
</head>
<body id="top" style="font-family:'Arial'">
{report recordset=$agData record=rec	groups="ANIO, MES, TIPO, AREA, CENTRO" resort=true}
{report_header}
    <hr/>
    
    
    <div style="font-size:0.8em; text-align:left; float:right;">Fecha Imp.: {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</div>
    </br>
    <div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong>{$smarty.session.g_empr}</strong><br>
        RESUMEN DE GASTOS<br>
        {$subtitulo}
    </p>
    </div>
    <table border=1 cellspacing=0 style="border:#000000 solid 1px;">
        
        <tr class="titulo bordeCompleto">
	  <td class=" bordeCompleto"><strong>Año</strong></td>
	  <td class=" bordeCompleto"><strong>Mes</strong></td>
	  <td class=" bordeCompleto"><strong>Tipo</strong></td>
	  <td class=" bordeCompleto"><strong>Area</strong></td>
	  <td class=" bordeCompleto"><strong>Centro</strong></td>
	  <td class=" bordeCompleto"><strong>Clase</strong></td>
	  <td class=" bordeCompleto"><strong>Cod.</strong></td>
          <td class=" bordeCompleto"><strong>Rubro</strong></td>
          <td class=" bordeCompleto"><strong>Valor</strong></td>
	</tr>
        
{/report_header}

{report_detail}
    <tr><!-- style="border:#000000 solid 1px;">headerrow-->
        <td class="izq bordeIzq bordeDer" >{$rec.ANIO}</td>  
	<td nowrap class="izq bordeDer">{$rec.MES}</td>
        <td class="izq bordeDer">{$rec.TIPO}</td>        
        <td class="izq bordeDer">{$rec.AREA}</td>
        <td class="izq bordeDer">{$rec.CENTRO}</td>
        <td class="izq bordeDer">{$rec.CLASE}</td>
        <td class="izq bordeDer">{$rec.COD}</td>
	<td class="izq bordeDer">{$rec.RUBRO}</td>
	<td class="der bordeDer">{$rec.VALOR|number_format:2}</td>
    </tr>
{/report_detail}

{report_footer}
    <tr>
        
    </tr>
    {*    
    <tr>
        <td class="der bordeIzq bordeDer bordeInf" ></td>
        <td class="izq bordeDer bordeInf"></td>
        <td class="izq bordeDer bordeInf"></td>
        <td class="izq bordeDer bordeInf"></td>
        <td class="izq bordeDer bordeInf"></td>
        <td class="izq bordeDer bordeInf"></td>
        <td class="izq bordeDer bordeInf"></td>
    </tr>
    *}
    </table>
    </br>
    
    
{/report_footer}

{/report}
</body>