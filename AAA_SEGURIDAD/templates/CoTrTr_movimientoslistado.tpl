<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  @rev esl 14-Sep-2012 Cambiar glosa del detalle por glosa de concepto - Solicitado por Wachito para Asisbane
      @rev esl 19/oct/2012 Reporte de movimientos sin grupos, solo una lista
-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
  <meta name="author" content="Gina Franco" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>Movimientos de Cuentas</title>
  
</head>

<body id="top" style="font-family:'Arial'">
{report recordset=$agData record=rec	groups="det_CodCuenta, det_CodAuxiliar, com_FecContab,txt_Compr" resort=true}
{assign var=acum value=0}
{*assign var=acumula value=0*}
{report_header}
    <!--<input type="button" title="Envia por correo este reporte" value="Correo" onclick="fGenMail()"/>
    <input type="button" title="Presenta los datos Base de reporte para ser exportados a excel" value="Excel" onclick="fGenExcel()"/>-->
    <hr/>
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong>{$smarty.session.g_empr}</strong><br>
        LISTADO DE MOVIMIENTOS DE CUENTAS<br>
        {$subtitulo}
    </p>
    <table border=1 cellspacing=0 >
	<tr style="font-weight:bold; text-align:center; vertical-align:middle;">
	    <td class="headerrow">COD CUENTA</td>
	    <td class="headerrow"><strong>CUENTA</strong></td>
	    <td class="headerrow"><strong>COD AUXILIAR</strong></td>
	    <td class="headerrow"><strong>COD ANTERIOR</strong></td>
	    <td class="headerrow"><strong>AUXILIAR</strong></td>
	    <td class="headerrow"><strong>REF OPERATIVA</strong></td>
	    <td class="headerrow">COMP</td>
	    <td class="headerrow"><strong>CHEQ</strong></td>
	    <td class="headerrow"><strong>FECHA</strong></td>
	    <td class="headerrow"><strong>BENEFICIARIO</strong></td>
	    <td class="headerrow"><strong>GLOSA</strong></td>
	    <td class="headerrow"><strong>DEBITO</strong></td>
	    <td class="headerrow"><strong>CREDITO</strong></td>
	</tr>
{/report_header}

{report_header group="det_CodAuxiliar"}
{/report_header}

{report_detail}
    <tr>
	<td class="colnum ">{$rec.det_CodCuenta}</td>
	<td class="coldata ">{$rec.txt_NombCuenta}</td>
	<td class="colnum ">{$rec.det_CodAuxiliar}</td>
	<td class="colnum ">{$rec.per_codAnterior}</td>
	<td class="coldata ">{$rec.txt_NombAuxiliar}</td>
	<td class="coldata ">{$rec.det_RefOperativa}</td>
    
	<td nowrap>{$rec.txt_Compr}</td><td class="colnum ">{$rec.com_Cheque}</td>
        <td class="colnum ">{$rec.com_FecContab}</td>
	<td class="coldata ">{$rec.beneficiario}</td>
        <td class="coldata ">{$rec.CON}</td> <!-- @rev esl 14-Sep-2012 Cambiar glosa del detalle por glosa de concepto - Solicitado por Wachito para Asisbane -->
        <td class="colnum ">{$rec.det_ValorDeb|number_format:2}</td>
        <td class="colnum ">{$rec.det_ValorCre|number_format:2}</td>
    </tr>
{/report_detail}

{report_footer}
    <tr >
        <td></td><td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td>
        <td class="colnum"><strong>TOTAL {*$rec.det_CodCuenta*}{*$rec.txt_NombCuenta*}</strong></td>
        {if ($sum.det_ValorDeb > 0)}
            <td class="colnum headerrow">{$sum.det_ValorDeb|number_format:2}</td>
        {else}
            <td>0.00</td>
        {/if}
        {if ($sum.det_ValorCre > 0)}
            <td class="colnum headerrow">{$sum.det_ValorCre|number_format:2}</td>
        {else}
            <td>0.00</td>
        {/if}
    </tr>

  </table>
{/report_footer}

{/report}
</body>