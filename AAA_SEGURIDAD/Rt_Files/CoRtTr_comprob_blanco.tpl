<html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>prueba</title>
<link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />



<body id="top" style="font-family:'Arial'; font size:5;" onload="window.print()">

{report recordset=$agData groups="TIB" record=rec}
<table width="98%" border="1" cellpadding="0">
{report_header}
	  
<div id="container"  style="height: 98% !important">
  <div class="tableContainer">
    
	<table  style="margin-top:150px; font-family:'Arial'; font-size:13px;" cellspacing="0" >
	  
	  <thead>
	    
	    
	    <tr style="margin-top:200px;">
	      <td style="text-align:left" colspan=3>Sr.(es): {$rec.NOM}</td>
	      <td style="text-align:left" colspan=3>FECHA DE EMISION:{$rec.FEC}</td>
	    </tr>
	     <tr>
	      <td style="text-align:left" colspan=3>RUC/CI:{$rec.RUC}</td>
	      <td style="text-align:left" colspan=3>TIPO DE COMPROBANTE DE VENTA:{$rec.TIP}</td>
	    </tr>
	     <tr>
	      <td style="text-align:left" colspan=3>DIRECCION:{$rec.Direcc}</td>
	      <td style="text-align:left" colspan=3>Nº DE COMPROBANTE DE VENTA:{$rec.FAC}</td>
	    </tr>
			<tr>
			<td>EJERCICIO FISCAL</td>
			<td>BASE IMPONIBLE</td>
			<td>IMPUESTO</td>
			<td>CODIGO DEL IMPUESTO</td>
			<td>% DE RETENCION</td>
			<td>VALOR RETENIDO</td>
			</tr>
         </thead>	 
{/report_header}
{report_detail}
		      { if ($rec.MIB > 0) }
		      <tr style="white-space:nowrap">
			<td>{$rec.PER}</td>
			<td>{$rec.BIB}</td>
			<td>IVA</td>
			<td>{$rec.TIB}</td>
			<td>{$rec.PIB}</td>
			<td>{$rec.MIB}</td>
			</tr>
		      
		      {/if}
			
		      { if ($rec.MIS > 0) }
		      <tr style="white-space:nowrap">
		      <td>{$rec.PER}</td>
			<td>{$rec.BIS}</td>
			<td>IVA</td>
			<td>{$rec.TIS}</td>
			<td>{$rec.PIS}</td>
			<td>{$rec.MIS}</td>
			</tr>
		      {/if}
			
		      {if ($rec.MIR>0)}
		      <tr style="white-space:nowrap">
		      <td>{$rec.PER}</td>
			<td>{$rec.BIR}</td>
			<td>RENTA</td>
			<td>{$rec.TIR}</td>
			<td>{$rec.PIR}</td>
			<td>{$rec.MIR}</td>
			</tr>
		      {/if}
			
		      {if ($rec.MIR2>0)}
		      <tr style="white-space:nowrap">
		      <td>{$rec.PER}</td>
			<td>{$rec.BIR2}</td>
			<td>RENTA</td>
			<td>{$rec.TIR2}</td>
			<td>{$rec.PIR2}</td>
			<td>{$rec.MIR2}</td>
			</tr>
		      {/if}
		      {if ($rec.MIR3>0)}
		      <tr style="white-space:nowrap">
		      <td>{$rec.PER}</td>
			<td>{$rec.BIR3}</td>
			<td>RENTA</td>
			<td>{$rec.TIR3}</td>
			<td>{$rec.PIR3}</td>
			<td>{$rec.MIR3}</td>
		      </tr>
		      {/if}
					
       
{/report_detail}

{report_footer}
		  <tr> 
			<td colspan=5>&nbsp;</td>
			<td>Total {math equation="x + y + z + a + b" x=$rec.MIR3 y=$rec.MIR2 z=$rec.MIR a=$rec.MIS b=$rec.MIB}</td>
		  </tr>
		  
		   <tr> 
			<td style="height:100px; vertical-align:bottom; text-align:center;" colspan=3>_____________________</td>
			<td style="height:100px; vertical-align:bottom; text-align:center;" colspan=3>_____________________</td>
		  </tr>
		   <tr> 
			<td style="text-align:center" colspan=3>FIRMA DEL AGENTE DE RETENCION</td>
			<td style="text-align:center" colspan=3>FIRMA DEL SUJETO PASIVO RETENIDO</td>
		  </tr>
{/report_footer}
</table>
{/report}
</body>
</html>
