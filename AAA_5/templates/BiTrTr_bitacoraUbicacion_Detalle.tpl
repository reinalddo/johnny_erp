<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Plantilla para el reporte de ubicaci�n de documentos en bitacora-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Gina Franco" />
  
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>Bitacora - Ubicacion de Documentos</title>
  
</head>

<body id="top" style="font-family:'Arial'">
{report recordset=$agData record=rec resort=true}
{report_header}
    <hr/>
    <div style="float:right;font-size:0.8em;">Fecha Imp.: {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</div>
    <div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <!-- <strong>{$smarty.session.g_empr}</strong><br> -->
	<strong>BITACORA DE DOCUMENTOS</strong><br> 
        REPORTE DE UBICACION (DETALLE)<br>
    </p>
    </div>
    <table border=1 cellspacing=0 style="font-size:0.6em;">
        <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
	    <td class="headerrow"><strong>Fecha Ingreso</strong></td> {*Fecha en que se recibi� el documento al usuario actual*}
	    <td class="headerrow"><strong>Entregado por</strong></td>
	    <td class="headerrow"><strong>Proveedor</strong></td>
	    <td class="headerrow"><strong>CIA</strong></td>
	    <td class="headerrow"><strong>Fecha de Emision</strong></td>
            <td class="headerrow"><strong>Serie</strong></td>
	    <td class="headerrow"><strong>Pto Emision</strong></td>
	    <td class="headerrow"><strong>N. de Documento</strong></td>
            <td class="headerrow"><strong>Detalle</strong></td>
	    <td class="headerrow"><strong>Valor</strong></td>
	    <td class="headerrow"><strong>Fecha Ingreso</strong></td>            
	    <td class="headerrow"><strong>Usuario Destino</strong></td>
	    
        </tr>
{/report_header}

{report_detail}
    <tr><td class="coldata" style="text-align:center;" nowrap>{$rec.FechaRec}</td>
	<td class="coldata" style="text-align:center; color:blue;">{$rec.bit_usuarioActual}</td>
	<td class="coldata">{$rec.proveedor}</td>
	<td class="colnum">{$rec.bit_empresa}</td>
	<td class="coldata" style="text-align:center;" nowrap>{$rec.bit_fechaDoc}</td>
	<td class="colnum">{$rec.bit_secDoc}</td>
	<td class="colnum">{$rec.bit_emiDoc}</td>
	<td class="colnum">{$rec.bit_numDoc}</td>
        <td class="coldata">{$rec.bit_observacion}</td>
        <td class="colnum" style="text-align:right;" nowrap>{$rec.bit_valor|number_format:2|default:0}</td>
	<td class="colnum">{$rec.FechaDestino}</td>
	<td class="coldata" style="text-align:center;">{$rec.usuarioDestino}</td>
    
    </tr>
{/report_detail}
    
{report_footer}
    
</table>
{/report_footer}

{/report}
</body>