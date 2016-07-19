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
{report recordset=$agData record=rec resort=true}
<table width="98%" border="1" cellpadding="0" cellspacing="0">
{report_header}
<div id="container"  style="height: 98% !important">
  <div class="tableContainer">
    
    <span style="text-shadow: red 0.2em 0.3em 0.2em; font-size:14pt; font-weight:bold">	</span>
	<table  cellspacing="0" >
	  <thead>
			<tr >
			  <td class="colhead" colspan={$gsNumCols} align="center" style="text-align: center; font-weight:bold; ">{$gsEmpresa}</td>
			</tr>
			<tr>
			  <td class="colhead" colspan={$gsNumCols} align="center" style="text-align: center; font-weight:bold; ">BALANCE GENERAL</td>
			</tr>
			<tr>
			  <!-- <td class="colhead"  colspan={$gsNumCols} align="center" style="text-align: center; font-weight:bold; ">Acumulado a: {$rec.PERI} </td> !-->
			</tr>
	      		 
			   <tr>
				  <td>CUENTA</td>
				  <td>AUXILIAR</td>
				  <td>DESCRIPCION</td>
				  {foreach item="col" from=$gaColumnas}
					<td  align="right">{$col}</td>
   				  {/foreach}

			 </tr>
			   
			 
         </thead>	 
         <tbody>
{/report_header}

{report_detail}
        
		              <tr style="white-space:nowrap;">
						  <td>{$rec.CUENTA}</td>
						  <td>{$rec.AUXILIAR}</td>
						  <td>{$rec.DESCRIPCION}</td>
		                     {foreach item="col" from=$gaColumnas}
		    
		                     <td  align="right">{$rec.$col|number_format:2:'.':','}</td>
		      
		                    {/foreach}
		    
	                      </tr>
	             
  {/report_detail}

</table>

{/report}
</body>
</html>