<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Tarjas por Vapor, marca:
	  Tabla de doble entrada, en vertcal los productores, agrupados por zona; en la horizontal las marcas agrupadas por producto -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Gina Franco" />
  
  <!--<link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />-->
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>Movimientos de Cuentas</title>
  
</head>
<body id="top" style="font-family:'Arial'">
{report recordset=$agData record=rec	groups="fecha,tipo,numero,det_secuencia" resort=true}
{report_header}
    <hr/>
    <div style="float:right;font-size:0.8em;">Fecha Imp.: {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</div>
    <div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong>{$smarty.session.g_empr}</strong><br>
        REPORTE DE MOVIMIENTOS DE CUENTAS<br>
        {$subtitulo}
    </p>
    </div>
    <table border=1 cellspacing=0 style="font-size:0.6em;">
        <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
            <td class="headerrow">Tipo</td>
            <td class="headerrow"><strong>Numero</strong></td>
            <td class="headerrow"><strong>Fecha</strong></td>
            <td class="headerrow"><strong>Cuenta</strong></td>
	    <td class="headerrow"><strong>Auxiliar</strong></td>
            <td class="headerrow"><strong>Glosa</strong></td>
	    <td class="headerrow"><strong>#Doc</strong></td>
            <td class="headerrow"><strong>Debito</strong></td>
            <td class="headerrow"><strong>Credito</strong></td>
	    <td class="headerrow"><strong>Diferencia</strong></td>
        </tr>
{/report_header}

{report_header group="tipo"}
{/report_header}

{report_header group="numero"}
{/report_header}

{report_detail}
    <tr><td nowrap>{$rec.tipo}</td>
        <td class="colnum ">{$rec.numero}</td>
        <td class="colnum ">{$rec.fecha}</td>
	<td class="coldata ">{$rec.nombre}</td>
	<td class="coldata ">{$rec.nom_aux}</td>
        <td class="coldata ">{$rec.glosa}</td>
	{if ($rec.cheque != 0)}
	  <td class="colnum" style="text-align:right;">{$rec.cheque}</td>
	{else}
	  <td class="colnum">&nbsp</td>
	{/if}
        <td class="colnum " style="text-align:right;">{$rec.debito|number_format:2}</td>
        <td class="colnum " style="text-align:right;">{$rec.credito|number_format:2}</td>
	<!--<td class="colnum">&nbsp</td>
    </tr>-->
{/report_detail}

{report_footer group="tipo"}
  
{/report_footer}

{report_footer group="numero"}
      {if (($sum.debito-$sum.credito|number_format:2) != 0.00)}
	<td class="colnum" style="text-align:right;">{$sum.debito-$sum.credito|number_format:2}</td>
      {else}
	<td class="colnum">&nbsp</td>
      {/if}
    </tr>
    <!--<tr>
        <td colspan=6 style="text-align:right;font-style:italic;"><strong>Total:</strong></td>
        <td class="colnum" style="text-align:right; font-style:italic;border-top:solid 1px;">
	    {$sum.debito|number_format:2}</td>
        <td class="colnum" style="text-align:right; font-style:italic;border-top:solid 1px;">
	    {$sum.credito|number_format:2}</td>	
    </tr>
    {if ($sum.debito > $sum.credito && ($sum.debito-$sum.credito|number_format:2) != 0.00)}
      <tr>
	<td colspan=6 style="text-align:right;color:#FF0000;"><strong>Diferencia:</strong></td>
	<td class="colnum">&nbsp</td>
	<td class="colnum" style="text-align:right; font-style:italic;">
	      {$sum.debito-$sum.credito|number_format:2}</td>
      </tr>
    {elseif ($sum.credito > $sum.debito && ($sum.credito-$sum.debito|number_format:2)!= 0.00 )}
      <tr>
	<td colspan=6 style="text-align:right;color:#FF0000;"><strong>Diferencia:</strong></td>
	<td class="colnum" style="text-align:right; font-style:italic;">{$sum.credito-$sum.debito|number_format:2}</td>
	<td class="colnum">&nbsp</td>
      </tr>
    {/if}-->
{/report_footer}

{report_footer}
    <tr><td colspan=10>&nbsp</td></tr>
    <tr>
        
        <td colspan=7 class="coldata"><strong>TOTAL GENERAL:</strong></td>
        <td class="colnum" style="text-align:right;">{$sum.debito|number_format:2}</td>
        <td class="colnum" style="text-align:right;">{$sum.credito|number_format:2}</td>
	{if (($sum.debito-$sum.credito|number_format:2) != 0.00)}
	  <td class="colnum" style="text-align:right;">{$sum.debito-$sum.credito|number_format:2}</td>
	{else}
	  <td class="colnum">&nbsp</td>
	{/if}	
    </tr>
    </table>
{/report_footer}

{/report}
</body>