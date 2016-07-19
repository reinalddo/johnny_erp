<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Tarjas por Vapor, marca:
	  Tabla de doble entrada, en vertcal los productores, agrupados por zona; en la horizontal las marcas agrupadas por producto -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Gina Franco" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="print" href="../css/AAA_tablas_print.css" title="CSS para imprimir" />
  
    <title>Detalle de Productores</title>
  
</head>
<body id="top" style="font-family:'Arial'">
{report recordset=$agData record=rec	groups="nombre" resort=true}
{report_header}
    <hr/>
    <div style="float:right;font-size:0.8em;">Fecha Imp.: {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</div>
    <div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong>{$smarty.session.g_empr}</strong><br>
        PRODUCTORES<br>
        {$subtitulo}
    </p>
    </div>
    <table border=1 cellspacing=0 >
	<tr style="font-weight:bold; text-align:center; vertical-align:middle; text-transform:uppercase;">
	  <td class="headerrow"><strong>CODIGO</strong></td>
	  <td class="headerrow"><strong>PRODUCTOR</strong></td>
	  <td class="headerrow"><strong>BANCO</strong></td>
	  <td class="headerrow"><strong>NUMERO CTA CTE</strong></td>
	  <td class="headerrow"><strong>NUM. INSCR. MAGAP</strong></td>
	  <td class="headerrow"><strong>TIPO DE PAGO (CH,TR)</strong></td>
	  <td class="headerrow"><strong>BENEF. ALTERNO</strong></td>
	  <td class="headerrow"><strong>TIPO DE CUENTA (A, C)</strong></td>
	  <td class="headerrow"><strong>ZONA_ORIGEN</strong></td>
	  <td class="headerrow"><strong>ZONA_PAGO</strong></td>
	  <td class="headerrow"><strong>ZONA_CORTE</strong></td>
	</tr>
        
{/report_header}

{report_detail}
    <tr>
        <td class="colnum ">{$rec.codigo}</td>  
	<td nowrap>{$rec.nombre}</td>
        <td class="colnum ">{$rec.banco}</td>        
        <td class="colnum ">{$rec.numero_cta_cte}</td>
        <td class="colnum ">{$rec.num_inscr_magap}</td>
	<td class="colnum " style="text-align: center;">{$rec.tipo_pago}</td>
	<td class="colnum ">{$rec.benef_alterno}</td>
	<td class="colnum " style="text-align: center;">{$rec.tipo_cuenta}</td>
	<td class="colnum ">{$rec.zona_origen}</td>
        <td class="colnum ">{$rec.zona_pago}</td>
	<td class="colnum ">{$rec.zona_corte}</td>
    </tr>
{/report_detail}

{report_footer}
    
    </table>
{/report_footer}

{/report}
</body>