<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Cuadro de Cuentas por Pagar -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
  <meta name="author" content="Erika Suarez" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>Cuadro</title>
  
</head>

<body id="top" style="font-family:'Arial'">
{assign var=acum value=0}
{assign var=sal value=0}

{report recordset=$agData record=rec resort=true groups="txt_nombre, dias"}

{report_header}
    <hr/>
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong>{$smarty.session.g_empr}</strong>
	<br> {$subtitulo}
	<br> {$rec.cue_Descripcion}
    </p>
   <table border=1 cellspacing=0 >
    <thead>
    </thead>
    <tfoot>
      <tr>
	<td colspan="16"> {$smarty.session.g_user} {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'} </td>
    </tfoot>
    
    <tbody>
    <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
	<td class="headerrow">FECHA FACTURA</td>
	<td class="headerrow"><strong>No DOCUMENTO</strong></td>
        <td class="headerrow"><strong>CLIENTE</strong></td>
	<td class="headerrow"><strong>CONCEPTO</strong></td>
    	<td class="headerrow"><strong>VALOR TOTAL<strong></td>
	<td class="headerrow"><strong>SALDO</strong></td>
	<td class="headerrow"><strong>USUARIO/FECHA DE DIGITACION</strong></td>
    </tr>
{/report_header}


{report_header group="dias"}
<tr >
    <td colspan=7>
      <p style="text-align: center; font:10; display:block; width=70%; font-size:0.8em;">
	  <strong> {$rec.txt_nombre} <strong>
	  <br> Dias vencidos {$rec.dias}
	  {assign var=idAux value=$rec.det_idauxiliar}
	  {assign var=ndias value=$rec.dias}
	  {assign var=stSaldo value=$agSaldos[$idAux][$ndias]|default:0}
	  {assign var=stValor value=$agValor[$idAux][$ndias]|default:0}
	 </p>
    </td>
  </tr>
{/report_header}

{report_detail}
  <tr style="text-align:center; vertical-align:middle;">
      <td class="coldata "> {$rec.com_FecContab}</td>
	<td class="coldata">{$rec.det_numdocum}</td>
	<td class="coldata "align="left">{$rec.txt_nombre}</td>
	<td class="coldata "align="left">{$rec.com_Concepto}</td>
	<!-- <td class="colnum " ><a href= "../Co_Files/CoTrTr_CuadroDet.rpt.php?&pidProvFact={$rec.idProvFact}&pnombreProveedor={$rec.nombreProveedor}&psecuencial={$rec.secuencial}" target="_blank">&nbsp;{$rec.establecimiento|str_pad:3:'0':STR_PAD_LEFT}-{$rec.puntoEmision|str_pad:3:'0':STR_PAD_LEFT}-{$rec.secuencial|str_pad:7:'0':STR_PAD_LEFT}</td> 
        <td class="colnum ">&nbsp;{$rec.estabretencion1|str_pad:3:'0':STR_PAD_LEFT}-{$rec.puntoEmiRetencion1|str_pad:3:'0':STR_PAD_LEFT}-{$rec.secretencion1|str_pad:7:'0':STR_PAD_LEFT}</td> -->
	<td class="colnum " >{$rec.det_ValDebito|number_format:2}</td>
	<td class="colnum ">{$rec.txt_valor|number_format:2}</td>
	<td class="coldata ">{$rec.usuario}</td>
	
	
  	{assign var=tValor  value=$tValor+$rec.det_ValDebito}
	{assign var=tSaldo  value=$tSaldo+$rec.txt_valor}
    </tr>
{/report_detail}


{report_footer group="dias"}
    <tr >
      <td></td><td></td><td></td>
       <td><strong> Subtotal {$rec.txt_nombre} Dias vencidos {$rec.dias} <strong></td>
       <td><strong>{$stValor|number_format:2}</strong></td>
       <td><strong>{$stSaldo|number_format:2}</strong></td>
       <td></td>      
    </tr>
    <tr></tr>
{/report_footer}


{report_footer}
    <tr style="font-weight:bold;text-align:center; vertical-align:middle;">
      <td></td><td></td><td></td>
       <td> Totales: </td>
       <td>{$tValor|number_format:2}</td>
       <td>{$tSaldo|number_format:2}</td>
       <td></td>
    </tr>
  </tbody>
  </table>
{/report_footer}

{/report}
</body>