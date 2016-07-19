<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
  <meta name="author" content="Smart" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>CxC Reporte HTML</title>
  
</head>

<body id="top" style="font-family:'Arial'">
{assign var=acum value=0}
{assign var=sal value=0}

{* <!-- {report recordset=$agData record=rec resort=true groups="txt_nombre, dias"} *}
{report recordset=$agData record=rec groups="cue_Descripcion,txt_nombre"}

{report_header}
    <hr/>
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong>{$smarty.session.g_empr}</strong>
	<br> {$subtitulo}
	{if !$pcom_FecContab}
	  '{$smarty.now|date_format:'%Y-%m-%d'}'
	{else}
	  {$pcom_FecContab}
	{/if}
	<br> 
    </p>
   <table border=1 cellspacing=0 >
    <thead>
    </thead>
    <tfoot>
      <tr>
	<td colspan="9"> {$smarty.session.g_user} {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'} </td>
    </tfoot>
    
    <tbody>
    
{/report_header}

{report_header group="cue_Descripcion"}
<tr ><td colspan=9>
      <br>
      <p style="text-align: center; font:10; display:block; width=70%; font-size:1.20em;"><b> CUENTA:{$rec.det_Codcuenta} - {$rec.cue_Descripcion}</b> </p> </td>
  </tr>
{/report_header}


{report_header group="txt_nombre"}
<tr >
    <td colspan=9>
      <p style="text-align: center; font:10; display:block; width=70%; font-size:1.20em;">
	  <b>{$rec.txt_nombre}</b>
	  <br>
	    
	  {assign var=idAux value=$rec.det_idauxiliar}
  	  {assign var=stSaldo value=$agSaldos[$idAux]|default:0}
	  {assign var=stValor value=$agValor[$idAux]|default:0}
	  
	  {assign var=ndias value=15}
	  {assign var=stSal15 value=$agSal[$idAux][$ndias]|default:0}
	  
	  {assign var=ndias value=30}
	  {assign var=stSal30 value=$agSal[$idAux][$ndias]|default:0}
	  
	  {assign var=ndias value=60}
	  {assign var=stSal60 value=$agSal[$idAux][$ndias]|default:0}
	  
	  {assign var=ndias value=90}
	  {assign var=stSal90 value=$agSal[$idAux][$ndias]|default:0}
	  
	  {assign var=ndias value=91}
	  {assign var=stSal91 value=$agSal[$idAux][$ndias]|default:0}

      </p>
    </td>
  </tr>
<tr style="font-weight:bold; text-align:center; vertical-align:middle;">
	<td class="headerrow">FECHA FACT.</td>
	<td class="headerrow"><strong>No DOC.</strong></td>
        {* <td class="headerrow"><strong>CONCEPTO</strong></td> *}
    	<td class="headerrow"><strong>VALOR<strong></td>
	<td class="headerrow"><strong>SALDO</strong></td>
	<td class="headerrow"><strong>CORRIENTE</strong></td>
	<td class="headerrow"><strong>30 DIAS</strong></td>
	<td class="headerrow"><strong>31 - 60 DIAS</strong></td>
	<td class="headerrow"><strong>61 - 90 DIAS</strong></td>
	<td class="headerrow"><strong>+90 DIAS</strong></td>
    </tr>
{/report_header}

{report_detail}
  <tr style="text-align:center; vertical-align:middle;">
	<td class="coldata "> {$rec.com_FecContab}</td>
	<td class="coldata">{$rec.det_numdocum}</td>
	<td class="colnum " >{$rec.det_ValDebito|number_format:2}</td>
	<td class="colnum ">{$rec.txt_valor|number_format:2}</td>
	{if ($rec.Ndias eq 15)}
	  <td> {$rec.txt_valor|number_format:2}</td><td></td><td></td><td></td><td></td>
	  {assign var=tSaldoC  value=$tSaldoC+$rec.txt_valor}
	{elseif ($rec.Ndias eq 30)}
	  <td></td><td> {$rec.txt_valor|number_format:2}</td><td></td><td></td><td></td>
	  {assign var=tSaldo30  value=$tSaldo30+$rec.txt_valor}
	{elseif ($rec.Ndias eq 60)}
	  <td></td><td></td><td> {$rec.txt_valor|number_format:2}</td><td></td><td></td>
	  {assign var=tSaldo60  value=$tSaldo60+$rec.txt_valor}
	{elseif ($rec.Ndias eq 90)}
	  <td></td><td></td><td></td><td> {$rec.txt_valor|number_format:2}</td><td></td>
	  {assign var=tSaldo90  value=$tSaldo90+$rec.txt_valor}
	{elseif ($rec.Ndias eq 91)}
	  <td></td><td></td><td></td><td></td><td> {$rec.txt_valor|number_format:2}</td>
	  {assign var=tSaldo_90  value=$tSaldo_90+$rec.txt_valor}
	{/if}
	
  	{assign var=tValor  value=$tValor+$rec.det_ValDebito}
	{assign var=tSaldo  value=$tSaldo+$rec.txt_valor}
    </tr>
{/report_detail}


 {report_footer group="txt_nombre"}
    <tr >
      <td></td>
       <td>Total por Cliente</td>
       <td>{$stValor|number_format:2}</td>
       <td>{$stSaldo|number_format:2}</td>
       <td>{$stSal15|number_format:2}</td>
       <td>{$stSal30|number_format:2}</td>
       <td>{$stSal60|number_format:2}</td>
       <td>{$stSal90|number_format:2}</td>
       <td>{$stSal91|number_format:2}</td>
       
       
    </tr>
    <tr></tr>
{/report_footer}


{report_footer}
<tr style="font-weight:bold; text-align:center; vertical-align:middle;">
	<td class="headerrow"></td>
	<td class="headerrow">TOTAL</td>
        {* <td class="headerrow"><strong>CONCEPTO</strong></td> *}
    	<td class="headerrow"><strong>FACTURADO<strong></td>
	<td class="headerrow"><strong>SALDO</strong></td>
	<td class="headerrow"><strong>CORRIENTE</strong></td>
	<td class="headerrow"><strong>30 DIAS</strong></td>
	<td class="headerrow"><strong>31 - 60 DIAS</strong></td>
	<td class="headerrow"><strong>61 - 90 DIAS</strong></td>
	<td class="headerrow"><strong>+90 DIAS</strong></td>
    </tr>
    <tr style="font-weight:bold;vertical-align:middle;">
      <td></td>
      <td></td>
      <td>{$tValor|number_format:2}</td>
      <td>{$tSaldo|number_format:2}</td>
      <td>{$tSaldoC|number_format:2}</td>
      <td>{$tSaldo30|number_format:2}</td>
      <td>{$tSaldo60|number_format:2}</td>
      <td>{$tSaldo90|number_format:2}</td>
      <td>{$tSaldo_90|number_format:2}</td>
    </tr>
  </tbody>
  </table>
{/report_footer}
{/report}
</body>