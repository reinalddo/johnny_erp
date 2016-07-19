<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Plantilla para el reporte de ubicación de documentos en bitacora-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Gina Franco" />
  
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>Bitacora - Historico de Documentos</title>
  
</head>

<body id="top" style="font-family:'Arial'">
{report recordset=$agData record=rec	groups="bit_codEmpresa, bit_tipoDoc,bit_numDoc, bit_idAux" resort=false}
{report_header}
    <hr/>
    <div style="float:right;font-size:0.8em;">Fecha Imp.: {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</div>
    <div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <!-- <strong>{$smarty.session.g_empr}</strong><br> -->
	<strong>BITACORA DE DOCUMENTOS</strong><br> 
        REPORTE HISTORICO DE DOCUMENTOS<br>
    </p>
    </div>
    <table border=1 cellspacing=0 style="font-size:0.6em;">
        <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
            <td class="headerrow"><strong>Tipo de Documento</strong></td>
            <td class="headerrow"><strong>Documento</strong></td>
            <td class="headerrow"><strong>Proveedor</strong></td>
            <td class="headerrow"><strong>Secuencia</strong></td>
	    <td class="headerrow"><strong>Emision</strong></td>
	    <td class="headerrow"><strong>Valor</strong></td>
            <td class="headerrow"><strong>Usuario</strong></td>
	    <td class="headerrow"><strong>Fecha Envio</strong></td>
            <td class="headerrow"><strong>Hora Envio</strong></td>
	    <td class="headerrow"><strong>Fecha Recepcion</strong></td>
	    <td class="headerrow"><strong>Hora Recepcion</strong></td>
	    <td class="headerrow"><strong>Estado</strong></td>
	    <td class="headerrow"><strong>Movimiento</strong></td>
	    <td class="headerrow"><strong>Observacion</strong></td>
	    <td class="headerrow"><strong>MOTIVO RECHAZO</strong></td>
	    <td class="headerrow"><strong>Envio a:</strong></td>
	    <td class="headerrow"><strong>Observacion Envio</strong></td>
	    
        </tr>
{/report_header}





{report_header group="bit_codEmpresa"}
    <tr>
	  <td colspan=1 nowrap><strong>EMPRESA:</strong></td>
	  <td colspan=16 nowrap><b>{$rec.bit_empresa}<b></td>
    </tr>
{/report_header}
{report_header group="bit_tipoDoc"}
    <tr>
	  <td colspan=1 nowrap><strong>TIPO DE DOCUMENTO:</strong></td>
    	  <td colspan=16 nowrap><b>{$rec.bit_tipoDoc} - {$rec.tipoDocu}</b></td>
    </tr>
{/report_header}

{report_header group="bit_numDoc"}
  <tr>
	  <td colspan=1 nowrap><strong>DOCUMENTO:</strong></td>
    	  <td colspan=16 nowrap><b>{$rec.bit_secDoc}-{$rec.bit_emiDoc}-{$rec.bit_numDoc}</b></td>
  </tr>
{/report_header}
{report_header group="bit_idAux"}
  <tr>
	  <td colspan=1 nowrap><strong>AUXILIAR:</strong></td>
	  <td colspan=16 nowrap><b>{$rec.bit_idAux}-{$rec.proveedor}</b></td>
  </tr>
{/report_header}


 



{report_detail}
    <tr><td class="colnum">{$rec.bit_tipoDoc}</td>
        <td class="colnum">{$rec.bit_secDoc}-{$rec.bit_emiDoc}-{$rec.bit_numDoc}</td>
        <td class="coldata">{$rec.proveedor}</td>
	<td class="coldata" style="text-align:center;">{$rec.bit_secuencia}</td>
	<td class="coldata" style="text-align:center;" nowrap>{$rec.bit_fechaDoc}</td>
	<td class="colnum" style="text-align:right;" nowrap>{$rec.bit_valor|number_format:2|default:0}</td>
	<td class="coldata" style="text-align:center; color:blue;">{$rec.bit_usuario}</td>
	<td class="coldata" style="text-align:center;" nowrap>{$rec.FechaEnv}</td>
	<td class="coldata" style="text-align:center;" nowrap>{$rec.HoraEnv}</td>
	<td class="coldata" style="text-align:center;" nowrap>{$rec.FechaRec}</td>
	<td class="coldata" style="text-align:center;" nowrap>{$rec.HoraRec}</td>
	<td class="coldata">{$rec.estado}</td>
	<td class="coldata">{$rec.movimiento}</td>
	<td class="coldata">{$rec.bit_observacion}</td>
	<td class="coldata">{$rec.bit_motivoRechazo}</td>
	<td class="coldata" style="text-align:center;">{$rec.bit_usuariodestino}</td>
	<td class="coldata">{$rec.bit_observacionenvio}</td>
    </tr>
{/report_detail}
    
{report_footer}
    
</table>
{/report_footer}

{/report}
</body>