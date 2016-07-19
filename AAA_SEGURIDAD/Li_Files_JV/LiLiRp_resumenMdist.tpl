<html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>{$gsSubTitul}</title>
<link rel="stylesheet" type="text/css" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/AAA_basico.css">
<link rel="stylesheet" type="text/css" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/AAA_tablas_print.css">
<link rel="stylesheet" type="text/css" media="screen, print" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/report.css" title="CSS para pantalla" />
</head>
<body align:"center" id="top" style="font-family:'Arial'; ">
{report recordset=$agData groups="C0,C1,EM" record=rec}
<table width="98%" border="1" cellpadding="0" cellspacing="0">
{report_header}
<div id="container"  style="height: 98% !important">
  <div class="tableContainer">
    <span style="text-shadow: red 0.2em 0.3em 0.2em; font-size:14pt; font-weight:bold">	</span>
	<table  cellspacing="0">
			<tr>
			  <td class="colhead" colspan={$gsNumCols} style="text-align: center; font-weight:bold; ">{$gsEmpresa}</td>
			</tr>
			<tr>
			  <td class="colhead" colspan={$gsNumCols} style="text-align: center; font-weight:bold; ">CUADRO GENERAL DE LIQUIDACIONES</td>
			</tr>
			  <tr>
			  <td class="colhead" colspan={$gsNumCols} style="text-align: center;font-weight:bold; ">{$gsSubTitul}</td>
			   </tr>
			  <tr>
			  <td class="colhead" colspan={$gsNumCols} style="text-align: right; font-weight:bold; ">Fecha Imp.: {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</td>
			   
			</tr>
			<tr class="nowraprow" style="height: 20px; vertical-align:bottom; font-size:10; font-weight:bold; text-align:left">
			<td>COD</td>
			<td>NOMBRE</td>
			<td>EMPRESA</td>
			<td>CAJAS</td>
			<td>FRUTA</td>
			<td>BONIFIC</td>
			<td>COMPENSAC</td>
			<td>EMPAQ PAGDO</td>
			<td>OTROS INGR</td>
			<td>EMPAQ.COBR</td>
			<td>RET.FUENT</td>
			<td>ADELANTO</td>
			<td>ANTICIPOS</td>
			<td>PRESTAMOS</td>
			<td>INTER.PREST</td>
			<td>GTOS.ADMIN</td>
			<td>FERTILIZANT</td>
			<td>TRANSPORT</td>
			<td>INSUMOS</td>
			<td>TRANSFEREN</td>
			<td>VAL P/LIQ</td>
			<td>TCI</td>
			<td>OTROS.DESC</td>
			<td>NETO</td>
			<td>CHEQUE 1</td>
			<td>CHEQUE 2</td>
			</tr>
         	 
{/report_header}


{report_detail}
	<tr style="white-space:nowrap;">
			<td style="text-align:left;">{$rec.C0}</td>
			<td>{$rec.C1}</td>
			<td class="colnum">{$rec.EM}</td>
			<td class="colnum">{$rec.C2}</td>
			<td class="colnum">{$rec.D1}</td>
			<td class="colnum">{$rec.D2}</td>
			<td class="colnum">{$rec.D3}</td>
			<td class="colnum">{$rec.D4}</td>
			<td class="colnum">{$rec.D5}</td>
			<td class="colnum">{$rec.D10}</td>
			<td class="colnum">{$rec.D11}</td>
			<td class="colnum">{$rec.D12}</td>
			<td class="colnum">{$rec.D13}</td>
			<td class="colnum">{$rec.D14}</td>
			<td class="colnum">{$rec.D15}</td>
			<td class="colnum">{$rec.D16}</td>
			<td class="colnum">{$rec.D21}</td>
			<td class="colnum">{$rec.D22}</td>
			<td class="colnum">{$rec.D24}</td>
			<td class="colnum">{$rec.D25}</td>
			<td class="colnum">{$rec.D26}</td>
			<td class="colnum">{$rec.D27}</td>
			<td class="colnum">{$rec.D28}</td>
			<td class="colnum">{$rec.TT}</td>
			<td class="colnum">{$rec.E1}</td>
			<td class="colnum">{$rec.E2}</td>
         </tr>
{/report_detail}


{report_footer}
		  <tr>
			<td colspan="3" style="text-align:right">TOTAL </td>
			<td style="text-align:right">{$sum.C2|number_format:2}</td>
			<td style="text-align:right">{$sum.D1|number_format:2}</td>
			<td style="text-align:right">{$sum.D2|number_format:2}</td>
			<td style="text-align:right">{$sum.D3}</td>
			<td style="text-align:right">{$sum.D4|number_format:2}</td>
			<td style="text-align:right">{$sum.D5}</td>
			<td style="text-align:right">{$sum.D10|number_format:2}</td>
			<td style="text-align:right">{$sum.D11|number_format:2}</td>
			<td style="text-align:right">{$sum.D12|number_format:2}</td>
			<td style="text-align:right">{$sum.D13|number_format:2}</td>
			<td style="text-align:right">{$sum.D14|number_format:2}</td>
			<td style="text-align:right">{$sum.D15|number_format:2}</td>
			<td style="text-align:right">{$sum.D16|number_format:2}</td>
			<td style="text-align:right">{$sum.D21|number_format:2}</td>
			<td style="text-align:right">{$sum.D22|number_format:2}</td>
			<td style="text-align:right">{$sum.D24|number_format:2}</td>
			<td style="text-align:right">{$sum.D25|number_format:2}</td>
			<td style="text-align:right">{$sum.D26|number_format:2}</td>
			<td style="text-align:right">{$sum.D27|number_format:2}</td>
			<td style="text-align:right">{$sum.D28|number_format:2}</td>
			<td style="text-align:right">{$sum.TT|number_format:2}</td>
			<td style="text-align:right">{$sum.E1|number_format:2}</td>
			<td style="text-align:right">{$sum.E2|number_format:2}</td>
		  </tr>
{/report_footer}

</table>
{/report}
</body>
</html>
