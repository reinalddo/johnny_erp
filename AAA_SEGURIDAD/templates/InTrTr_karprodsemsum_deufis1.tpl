<html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>{$title1}</title>
<link rel="stylesheet" type="text/css" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/AAA_basico.css">
<link rel="stylesheet" type="text/css" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/AAA_tablas_print.css">
<link rel="stylesheet" type="text/css" media="screen, print" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/report.css" title="CSS para pantalla" />
</head>
<body align:"center" id="top" style="font-family:'Arial'; ">
  
 <center style="font-size: 16px">{$title1} <br> <br> {$subTitle} <br> </center>
 
{report recordset=$agData record=rec groups="RECEP,ITEM" resort=true}
<table width="98%" border="1" cellpadding="0" cellspacing="0">
{report_header}
<div id="container"  style="height: 98% !important">
  <div class="tableContainer">
    
    <span style="text-shadow: red 0.2em 0.3em 0.2em; font-size:14pt; font-weight:bold">	</span>
	<table  cellspacing="0" >
	  <thead>
				      		 
			   <tr>
			      {foreach key=key item="rec_item" from=$rec}
			 
			       <td  align="center">{$key}</td>
			    
			     {/foreach}
			 </tr>
			   
			 
         </thead>	 
         <tbody>
{/report_header}

{report_header group="RECEP"}			
  		<tr>
			
			  
			    <td height="40" class="colhead" style="vertical-align: bottom;  text-align:center" colspan=10 >
		                     {$rec.RECEP}
			      </td>
	                    
       		</tr>
{/report_header}

{report_header group="ITEM"}			
  		<tr>
		          
			    <td height="40"  align="left" colspan=10 style="vertical-align: bottom">
		                     {$rec.ITEM}
			      </td>
	                 
       		</tr>

{/report_header}


{report_detail}
        
		              <tr style="white-space:nowrap;">
						    
		                     {foreach item="rec_item" from=$rec}
				       {if ($rec_item eq $rec.COMPR) or ($rec_item eq $rec.RECEP) or ($rec_item eq $rec.ITEM)}
		                         <td  align="left">{$rec_item}</td>
				       {elseif ($rec_item eq $rec.CANTID) or ($rec_item eq $rec.COSTO)}
				         <td  align="right">{$rec_item|number_format:2}</td>
				       {else}
				         <td  align="center">{$rec_item}</td>
		                       {/if}
		                    {/foreach}
		    
	                      </tr>
	             
{/report_detail}


{report_footer group="RECEP"}
		  <tr> 
			<td colspan="4">TOTAL PRODUCTOR {$rec.RECEP}: </td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td align="right">{$sum.CANTID|number_format:2}</td>
			<td align="right">{$sum.COSTO|number_format:2}</td>
		  </tr>
{/report_footer}


</table>

{/report}
</body>
</html>