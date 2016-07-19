<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Tarjas por Vapor, marca:
	  Tabla de doble entrada, en vertcal los productores, agrupados por zona; en la horizontal las marcas agrupadas por producto -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Gina Franco" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>Mayor</title>
  
</head>
<body id="top" style="font-family:'Arial'; font-size:0.9em;">
{report recordset=$agData record=rec	groups="fecha,numero,det_secuencia" resort=true}
{report_header}
    <hr/>
    <div style="float:right;font-size:0.8em;">Fecha Imp.: {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</div>
    <div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong>{$smarty.session.g_empr}</strong><br>
        {$subtitulo}
    </p>
    </div>
    
    <table border=1 cellspacing=0 >
        <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
            <td class="headerrow">Codigo</td>
            <td class="headerrow"><strong>Tipo</strong></td>
            <td class="headerrow"><strong>Numero</strong></td>
	    <td class="headerrow"><strong>Cod Cuenta</strong></td>
	    <td class="headerrow"><strong>Nom Cta</strong></td>
	    <td class="headerrow"><strong>Cod Aux</strong></td>
            <td class="headerrow"><strong>Nom Aux</strong></td>
            <td class="headerrow"><strong>Fecha</strong></td>
            <td class="headerrow"><strong>Descripcion</strong></td>
            <td class="headerrow"><strong>Debito</strong></td>
            <td class="headerrow"><strong>Credito</strong></td>
            <td class="headerrow"><strong>Saldo</strong></td>
            <td class="headerrow"><strong>Saldo F</strong></td>
            <!--<td class="headerrow"><strong>Act.</strong></td>-->
            <td class="headerrow"><strong>Cheque</strong></td>            
        </tr>
{/report_header}

{report_detail}
    <tr><td nowrap>{$rec.codigo}</td>
        <td class="colnum ">{$rec.tipo}</td>        
        <td class="colnum ">{$rec.numero}</td>
	<td class="coldata ">{$rec.codcuenta}</td>
	<td class="coldata ">{$rec.cuenta}</td>
	<td class="coldata ">{$rec.codauxiliar}</td>
        <td class="coldata ">{$rec.nom_aux}</td>
	<td class="colnum ">{$rec.fecha}</td>
	<td class="coldata ">{$rec.descripcion}</td>
        <td class="colnum " style="text-align:right;">{$rec.debito|number_format:2}</td>
        <td class="colnum " style="text-align:right;">{$rec.credito|number_format:2}</td>
        <td class="colnum " style="text-align:right;">{$rec.saldo}</td>
        <td class="colnum " style="text-align:right;">{$rec.saldof|number_format:2}</td>
        <!--<td class="colnum ">{$rec.act}</td>-->
	{if ($rec.cheque != 0)}
	  <td class="colnum " style="text-align:right;">{$rec.cheque}</td>
	{else}
	  <td>&nbsp</td>
	{/if}
    </tr>
{/report_detail}

{report_footer}
    <tr><td colspan=12>&nbsp</td></tr>
    <tr>
        <!--<td colspan=5>&nbsp</td>-->
        <td colspan=7 class="colnum"><strong>TOTALES:</strong></td>
        <td class="colnum headerrow" style="border:solid 2px;">{$sum.debito|number_format:2}</td>
        <td class="colnum headerrow" style="border:solid 2px;">{$sum.credito|number_format:2}</td>
	{assign var=tot value=$sum.debito-$sum.credito}
        <td class="colnum headerrow" style="border:solid 2px;">{$tot|number_format:2}</td>
        <td class="colnum headerrow" style="border:solid 2px;">{$sum.saldof|number_format:2}</td>
        <td>&nbsp</td>
    </tr>
    </table>
{/report_footer}

{/report}
</body>