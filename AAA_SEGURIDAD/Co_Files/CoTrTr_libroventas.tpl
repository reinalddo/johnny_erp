<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>VENTAS</title>
        <link rel="stylesheet" type="text/css" href="../css/AAA_basico.css">
       <link rel="stylesheet" type="text/css" href="../css/general_print.css">
        <link rel="stylesheet" type="text/css" media="screen, print" href="http:../css/report.css" title="CSS para pantalla" />
    </head>
    <body align:"center" id="top" style="font-family:'Arial'; ">
    {report recordset=$agData record=rec resort=true }
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
			  <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">REPORTE DE CONTABILIZACION</td>
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
	 			<td align="center"><fsize="2" face="Verdana, Arial, Helvetica,sans-serif"></td>
                 <td align="center"><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">COMPROBANTE</td>
                        <td align="center""><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">NUMERO</td>
                        <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">F/EMISION</td>
			            <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">F/CONTAB</td>
                      <td align="center"><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">F/VENC</td>
                     <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">LIBRO</td>
                      <td align="center"><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">USUARIO</td>
                     <td ><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">CLIENTE</td>
                      <td ><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">RUC</td>
                {foreach key=key from=$gsPivot item=Pivot}
             		{foreach key=key item=item from=$Pivot}           
		        		<td align=""><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">{$item}</td>
					{/foreach}
				{/foreach}
			    <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">CONCEPTO</td>
     </thead>
{/report_header}
{report_detail}
	<tr >
	       <td align="center" ><size="2" face="Verdana, Arial, Helvetica,
                sans-serif" ><A HREF="{$rec.url}" TARGET="_new">***</A></td>
			<td align="center" ><size="2" face="Verdana, Arial, Helvetica,
                sans-serif" >{$rec.TIP}</td>
                        <td align="left" ><size="2" face="Verdana, Arial, Helvetica,
                sans-serif">{$rec.NUM}</td>
			<td align="left" ><size="2" face="Verdana, Arial, Helvetica,
                sans-serif">{$rec.FTR}</td>
			<td align="right" ><size="2" face="Verdana, Arial, Helvetica,
                sans-serif">{$rec.FCT}</td>
			<td align="right" ><size="2" face="Verdana, Arial, Helvetica,
                sans-serif">{$rec.FCV}</td>
                         <td align="right" class="nowrap"><size="2" face="Verdana, Arial, Helvetica,
                sans-serif">{$rec.LIB}</td>
			<td align="right" ><size="2" face="Verdana, Arial, Helvetica,
                sans-serif">{$rec.USU}</td>
                        <td align="right" class="nowrap"><size="2" face="Verdana, Arial, Helvetica,
                sans-serif">{$rec.CLI}</td>
			<td align="right" ><size="2" face="Verdana, Arial, Helvetica,
                sans-serif">{$rec.RUC}</td>
                {foreach from=$gsPivot item=Pivot}
				    {foreach key=key item=item from=$Pivot}		                
		        	   <td align="center" class="colnum"><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">{$rec.$item|number_format:2}</td>
					{/foreach}
				{/foreach}
                    <td align="center" class="nowrap"><size="2" face="Verdana, Arial, Helvetica,
                sans-serif">{$rec.CON}</td>                  
	      </tr>
 {/report_detail}

 {report_footer}
             <tr> 
             {assign var=cnt value=$gsPivot|@count}
			<td colspan="{$cnt+10}" style="text-align:left">{$smarty.session.g_user} {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</td>
			<td>{$PiePagina}</td>
             </tr>
 {/report_footer}
         </table>    
{/report}
   </div>
</div>
</body>
</html>
