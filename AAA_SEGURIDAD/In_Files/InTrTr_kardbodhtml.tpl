<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Kardex General</title>
        <link rel="stylesheet" type="text/css" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/AAA_basico.css">
        <link rel="stylesheet" type="text/css" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/AAA_tablas_print.css">
        <link rel="stylesheet" type="text/css" media="screen, print" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/report.css" title="CSS para pantalla" />
    </head>
    <body align:"center" id="top" style="font-family:'Arial'; ">
    {report recordset=$agData record=rec resort=false groups="DES" }
    {report_header}
     <div id="container"  style="height: 98% !important">
        <div class="tableContainer">
    
    <span style="text-shadow: red 0.2em 0.3em 0.2em; font-size:14pt; font-weight:bold">	</span>
	<p></p>
	<table  align="center" cellspacing="3" BORDERCOLOR="black" >
	  <thead >
			<tr >
			  <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">{$gsEmpresa}</td>
			</tr>
			<tr>
			  <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,sans-serif"> Kardex de Bodega/ Saldos General</td>
			</tr>
                        <tr>
			  <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">PERIODO DE {$gsDesde} A {$gsHasta}</td>
			</tr>
	</thead>
      </table >
<p></p>
<tbody>
      <table align="center" cellspacing="3" BORDERCOLOR="black">
	 <thead>
	 <tr>

			<td align="center" COLSPAN="7" width="50%" style="background-color:white !important; border-top-color: transparent; border-left-color: transparent"><fsize="2" face="Verdana, Arial, Helvetica,
                sans-serif"></td>
                         <td align="center" COLSPAN="3" width="50%" ><fsize="2" face="Verdana, Arial, Helvetica,
                sans-serif">INGRESOS</td>
                         <td align="center" COLSPAN="3" width="50%"><fsize="2" face="Verdana, Arial, Helvetica,
                sans-serif">EGRESOS</td>
                        <td align="center" COLSPAN="3" width="50%"><fsize="2" face="Verdana, Arial, Helvetica,
                sans-serif">SALDOS</td>
	  </tr>
	 <tr>
	    <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,   sans-serif">BODEGA</td>
	    <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,   sans-serif">GRUPO</td>
	    <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,   sans-serif">NOMBRE GRUPO</td>
	   <td align="center"><fsize="2" face="Verdana, Arial, Helvetica,   sans-serif">CODIGO ITEM</td>
	   <td align="center" width="50%"><fsize="2" face="Verdana, Arial, Helvetica,   sans-serif">NOMBRE ITEM</td>
	   <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,   sans-serif">NUM. COMPROB</td>
	   <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,   sans-serif">FECHA</td>
	   <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,   sans-serif">CANT<br/>INGRESOS</td>
	   <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,   sans-serif">COSTO<br/>UNITARIO</td>
	 <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,   sans-serif">COSTOS<br/>INGRESOS</td>
	<td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,   sans-serif">CANT EGRESOS</td>
	<td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,   sans-serif">COSTO<br/>UNITARIO</td>
	 <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,   sans-serif">COSTOS<br/>EGRESOS</td>
	<td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,   sans-serif">SALDO FINAL</td>
	 <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,   sans-serif">COSTO FINAL</td>
	<td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,   sans-serif">C. UNI</td>
	
                      </tr>
	 </thead>
	  <tfoot>
	  <tr> 
			<td colspan="15" style="text-align:left">{$smarty.session.g_user} {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</td>
			<td>{$PiePagina}</td>
             </tr>
	 </tfoot>	
{/report_header}
{report_detail}
  {if $fgBandera neq $rec.DES }{set var=$fgSumCant value=0}{/if}
    {if $fgBandera neq $rec.DES }{set var=$fgSumCant1 value=0}{/if} 
      <tr >	
	    <td align="center"><size="2" face="Verdana, Arial, Helvetica,sans-serif">{$rec.BOD}</td>
	    <td align="center"><size="2" face="Verdana, Arial, Helvetica,sans-serif">{$rec.GRU}</td>
	    <td align="center"><size="2" face="Verdana, Arial, Helvetica,sans-serif">{$rec.GRD}</td>
	    <td align="center" ><size="2" face="Verdana, Arial, Helvetica,sans-serif">{$rec.ITE}</td>
	    <td align="left" ><size="2" face="Verdana, Arial, Helvetica,sans-serif" STYLE="white-space: nowrap">{$rec.DES}</td>
	    <td align="left" ><size="2" face="Verdana, Arial, Helvetica,sans-serif">{$rec.COM}</td>
	    <td align="left" ><size="2" face="Verdana, Arial, Helvetica,sans-serif">{$rec.FEC}</td>
	    <td align="right" ><size="2" face="Verdana, Arial, Helvetica,sans-serif">{$rec.CIN|number_format:2}</td>
	    <td align="right" ><size="2" face="Verdana, Arial, Helvetica,sans-serif">{$rec.VIN / $rec.CIN|number_format:6}</td>
	     <td align="right" ><size="2" face="Verdana, Arial, Helvetica,sans-serif">{$rec.VIN|number_format:2}</td>
	    <td align="right" ><size="2" face="Verdana, Arial, Helvetica,sans-serif">{$rec.CEG|number_format:2}</td>
	    <td align="right" ><size="2" face="Verdana, Arial, Helvetica,sans-serif">{$rec.VEG/$rec.CEG|number_format:6}</td>
	    <td align="right" ><size="2" face="Verdana, Arial, Helvetica,sans-serif">{$rec.VEG|number_format:2}</td>
	    {addto var="fgSumCant" value=$rec.SAC}
	    <td align="right" ><size="2" face="Verdana, Arial, Helvetica,sans-serif">{$fgSumCant|number_format:2}</td>
	    {addto var="fgSumCant1" value=$rec.VAC}
	    <td align="right" ><size="2" face="Verdana, Arial, Helvetica,sans-serif">{$fgSumCant1|number_format:2}</td>
	    <td align="right" ><size="2" face="Verdana, Arial, Helvetica,sans-serif">{$fgSumCant1/$fgSumCant|number_format:6}</td>
	    {assign var="fgBandera" value=$rec.DES}
	      </tr>
	
 {/report_detail}
 {report_footer group="DES"}
			<tr >	
			<td align="center" colspan=1 >&nbsp;</td >
			<td align="center" colspan=1 >&nbsp;</td >
			<td align="center" colspan=1 >&nbsp;</td >
			<td align="center" colspan=1 >&nbsp;</td >
			<td align="center" colspan=1 >&nbsp;</td >
			<td align="center" colspan=1 >&nbsp;</td >
			<td align="center" colspan=1 >&nbsp;</td >
			<td align="center" colspan=1 >&nbsp;</td >
			<td align="center" colspan=1 >&nbsp;</td >
			<td align="center" colspan=1 >&nbsp;</td >
			<td align="center" colspan=1 >&nbsp;</td >
			<td align="center" colspan=1 >&nbsp;</td >
			<td align="center" colspan=1 >&nbsp;</td >
			<td align="center" colspan=1 >&nbsp;</td >
			<td align="center" colspan=1 >&nbsp;</td >
		  </tr>
{/report_footer}
{report_footer}
        <tr >	
			<td align="center">&nbsp;</td >
			<td align="center">&nbsp;</td >
			<td align="center">&nbsp;</td >
			<td align="center">TOTALES:</td >
			<td align="center">&nbsp;</td >
			<td align="center">&nbsp;</td >
			<td align="center">&nbsp;</td >
			<td align="center">&nbsp;</td >
			<td align="center">&nbsp;</td >
			<td align="center">{$sum.VIN|number_format:2}</td >
			<td align="center">&nbsp;</td >
			<td align="center">&nbsp;</td >
			<td align="center">{$sum.VEG|number_format:2}</td >
			<td align="center">&nbsp;</td >
			<td align="center">{$sum.VIN-$sum.VEG|number_format:2}</td >
       </tr>
{/report_footer}
         </table>    
{/report}
   </div>
</div>
</body>
</html>
