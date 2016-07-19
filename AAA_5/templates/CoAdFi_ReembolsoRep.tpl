<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Plantilla para el reporte de las transacciones de COMEX
      MUESTRA LA INFORMACION DE LA TRANSACCION Y DE LA CONTABILIZACION
-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <!-- meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8" /-->
  <meta name="author" content="Erika Suarez" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>REEMBOLSO DE GASTOS</title>
  
</head>

<body id="top" style="font-family:'Arial'">

{report recordset=$agData record=rec resort=true}
{report_header}
    <hr/>
    <p style="text-align: left; font:12; display:block; width=70%; font-size:0.8em;">
        <strong>{$smarty.session.g_empr}</strong><br>
        {$subtitulo}<br>
    </p>
   <table border=1 cellspacing=0 >
    <thead>
    </thead>
    <tfoot>
      <tr>
	<td colspan="4"> {$smarty.session.g_user} {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'} </td>
      </tr>
    </tfoot>
    
    <tbody>
      
    <tr>
      <td><strong>No. TRANSACCION:</strong></td><td>{$rec.ree_Id}</td>
      <td><strong>FECHA DE EMISION:</strong></td><td>{$rec.fecTra|date_format:'%Y-%m-%d'}</td>
    </tr>
    <tr>
      <td ><strong>EMISOR:<strong></td><td colspan= 3>{$rec.tx_Emisor}</td>
    </tr>
    <tr>
      <td ><strong>VALOR:<strong></td><td colspan= 3>{$rec.valTotal}</td>
    </tr>
    <tr>
      <td><strong>CONCEPTO:<strong></td><td>{$rec.ree_Concepto}</td>
      <td><strong>SEMANA:<strong></td><td>{$rec.semanaTra}</td>
    </tr>
    <tr>
      <td><strong>ESTADO:<strong></td><td>{$rec.tx_Estado}</td>
      <td><strong>USUARIO EMISION:<strong></td><td>{$rec.ree_Usuario}</td>
    </tr>
    
	<tr style="font-weight:bold; text-align:center; vertical-align:middle;">
	<td colspan=4><br>DETALLE DE REEMBOLSO</td>
	</tr>  
	<tr style="font-weight:bold; text-align:center; vertical-align:middle;">
	    <td class="headerrow">SECUENCIA</td>
	    <td class="headerrow">MOTIVO</td>
	    <td class="headerrow">TIPO</td>
	    <td class="headerrow"><strong>VALOR</strong></td>
	</tr>
{/report_header}
    
  {report_detail}
    <tr style="text-align:center; vertical-align:middle;">
	  <td class="coldata" align="center"> {$rec.red_Sec}</td>
	  <td class="colnum">{$rec.cue_Descripcion}</td>
	  <td class="coldata">{$rec.tx_Aux}</td>
	  <td class="coldata">{$rec.red_Valor}</td>
      </tr>
  {/report_detail}
  
  {report_footer}
    </tbody>
    </table>
  {/report_footer}

{/report}

<!-- REPORTE PARA LA PARTE CONTABLE (SI LA TRANSACCION HA SIDO CONTABILIZADA)-->
{if $rec.ree_NumComp gt 0}
<hr>
{report recordset=$agContab record=rec2 resort=true}
{report_header}
    <p style="text-align: left; font:12; display:block; width=70%; font-size:0.8em;">
        <strong>{$smarty.session.g_empr}</strong> <br> CONTABILIZACION <br>
    </p>
   <table border=1 cellspacing=0 >
    <thead>
    </thead>
    <tfoot>
      <tr>
	<td colspan="4"> {$smarty.session.g_user} {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'} </td>
      </tr>
    </tfoot>
    
    <tbody>
    <tr>
      <td><strong>COMPROBANTE:</strong></td><td>{$rec2.com_TipoComp}-{$rec2.com_NumComp}</td>
      <td><strong>VALOR:<strong></td><td>{$rec2.com_Valor}</td>
    </tr>
    <tr><td colspan=4></td></tr>
    
    <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
	<td class="headerrow"><strong>CUENTA</strong></td>
	<td class="headerrow">AUXILIAR</td>
	<td class="headerrow"><strong>DEBITO</strong></td>
	<td class="headerrow"><strong>CREDITO</strong></td>
    </tr>
    {/report_header}
    
    {report_detail}
      <tr style="vertical-align:middle;">
	    <td class="coldata" WIDTH=200>{$rec2.tx_Cuenta}</td>
	    <td class="coldata" WIDTH=100> {$rec2.tx_Auxiliar}</td>
	    <td class="colnum"  WIDTH=100>{$rec2.det_ValDebito}</td>
	    <td class="colnum"  WIDTH=100>{$rec2.det_ValCredito}</td>
	</tr>
    {/report_detail}
    
    {report_footer}
	    <tr>
	      <td class="coldata" align = "center" colspan="2">
		  <br><br><br>________________________ </td>
	      <td class="coldata" align = "center" colspan="2"><br><br><br>________________________ </td>
	    </tr>
	    <tr>
	      <td class="coldata" align = "center" colspan="2"><I>Emitido por:{$rec2.ree_Usuario}</I></td>
	      <td class="coldata" align = "center" colspan="2"><I>Aprobado por:{$rec2.ree_UsuAprueba}</I></td>
	    </tr>
      </tbody>
      </table>
    {/report_footer}
{/report}
{/if}
</body>