<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Tarjas por Vapor, marca:
	  Tabla de doble entrada, en vertcal los productores, agrupados por zona; en la horizontal las marcas agrupadas por producto -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Gina Franco" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>Cuentas Por Pagar</title>
  
</head>

<body id="top" style="font-family:'Arial'">
{report recordset=$agData record=rec	groups="EMPRESA,CODIGO,NOMBRE,FECHA" resort=true}

{report_header}
    <hr/>
    <div style="float:right;font-size:0.8em;">Fecha Imp.: {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</div>
    <div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong>{$smarty.session.g_empr}</strong><br>
        REPORTE DE CUENTAS POR PAGAR<br>
        {$subtitulo}
    </p>
    </div>
    <table border=1 cellspacing=0 style="font-size:0.6em;">
        <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
            <td class="headerrow">Compa�ia</td>
            <td class="headerrow"><strong>Codigo</strong></td>
            <td class="headerrow"><strong>Nombre</strong></td>
            <td class="headerrow"><strong>No.Fact.</strong></td>
	    <td class="headerrow"><strong>Fecha</strong></td>
            <td class="headerrow"><strong>Vencimiento</strong></td>
	    <td class="headerrow"><strong>Concepto</strong></td>
            <td class="headerrow"><strong>Total Fact.</strong></td>
            <td class="headerrow"><strong>Total Abono</strong></td>
	    <td class="headerrow"><strong>Saldo</strong></td>
        </tr>
{/report_header}

{report_header group="NOMBRE"}
    <tr><td nowrap>{$rec.EMPRESA}</td>
        <td class="colnum ">{$rec.CODIGO}</td>
        <td class="coldata " colspan=8>{$rec.NOMBRE}</td>
    </tr>
{/report_header}

{report_detail}
    <tr><td colspan=3>&nbsp</td>
        <td class="colnum ">{$rec.NUM_FACT}</td>
        <td class="colnum ">{$rec.FECHA}</td>
        <td class="colnum ">{$rec.VENCIMIENTO}</td>
        <td class="coldata ">{$rec.CONCEPTO}</td>
	<td class="colnum " style="text-align:right;">{$rec.VALOR_FACT|number_format:2}</td>
        <td class="colnum " style="text-align:right;">{$rec.ABONADO|number_format:2}</td>
        <td class="colnum " style="text-align:right;">{$rec.SALDO|number_format:2}</td>
    </tr>
{/report_detail}
    
{report_footer group="NOMBRE"}
    <tr style="text-align:right;"><td colspan=7><strong>Subtotal</strong></td>
        <td class="colnum" style="font-style:italic;">{$sum.VALOR_FACT|number_format:2}</td>
        <td class="colnum" style="font-style:italic;">{$sum.ABONADO|number_format:2}</td>
        <td class="colnum" style="font-style:italic;">{$sum.SALDO|number_format:2}</td>
    </tr>  
{/report_footer}
    
{report_footer}
    <tr style="text-align:right; font-style:italic;"><td colspan=7><strong>Totales</strong></td>
        <td class="colnum">{$sum.VALOR_FACT|number_format:2}</td>
        <td class="colnum">{$sum.ABONADO|number_format:2}</td>
        <td class="colnum">{$sum.SALDO|number_format:2}</td>
    </tr>      
</table>
{/report_footer}

{/report}
</body>