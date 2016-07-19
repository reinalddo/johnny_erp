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
{report recordset=$agData record=rec	groups="vau_descripcion" resort=true}
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
	  <td class="headerrow"><strong>Codigo</strong></td>
	  <td class="headerrow"><strong>Productor</strong></td>
	  <td class="headerrow"><strong>Zona de Corte</strong></td>
	  <td class="headerrow"><strong>Banco</strong></td>
	  <td class="headerrow"><strong>NUMERO CTA CTE</strong></td>
	  <td class="headerrow"><strong>NUM. INSCR. MAGAP</strong></td>
	  <td class="headerrow"><strong>TIPO DE PAGO (CH,TR)</strong></td>
	  <td class="headerrow"><strong>ZONA DE PAGO</strong></td>
	  <td class="headerrow"><strong>BENEF. ALTERNO</strong></td>
	  <td class="headerrow"><strong>TIPO DE CUENTA (A, C)</strong></td>
	</tr>
        
{/report_header}

{report_detail}
    <tr>
        <td class="colnum ">{$rec.vau_codauxiliar}</td>  
	<td nowrap>{$rec.vau_descripcion}</td>
        <td class="colnum ">{$rec.Zona_Corte}</td>        
        <td class="colnum ">{$rec.BANCO}</td>
        <td class="colnum ">{$rec.NUMERO_CTA_CTE}</td>
	<td class="colnum ">{$rec.NUM_INSCR_MAGAP}</td>
	<td class="colnum ">{$rec.TIPO_PAGO}</td>
	<td class="colnum ">{$rec.ZONA_PAGO}</td>
	<td class="colnum ">{$rec.BENEF_ALTERNO}</td>
        <td class="colnum ">{$rec.TIPO_CUENTA}</td>
    </tr>
{/report_detail}

{report_footer}
    
    </table>
{/report_footer}

{/report}
</body>