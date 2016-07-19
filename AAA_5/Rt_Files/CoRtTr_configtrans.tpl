<!doctype html public "-//W3C//DTD HTML 4.0 //EN">
<html>
<head>
<base target="_self">
<link rel="stylesheet" type="text/css" href="../Themes/StormyWeather/Style.css">
<link rel="stylesheet" type="text/css" href="../Themes/Menu/Style.css">
<link rel="stylesheet" type="text/css" href="../Themes/Cobalt/Style.css">
<title>CONFIGURACION DE TRANSACCIONES DIMM</title>
<script language="JavaScript" src="../LibJava/general.js"></script>
<script language="JavaScript" src="../LibJava/menu_func.js"></script>
<script language="JavaScript" src="../LibJava/pajax-commom.js"></script>
<script language="JavaScript" src="../LibJava/pajax-parser.js"></script>
<script language="JavaScript" src="../LibJava/pajax-core.js"></script>
<script language="JavaScript" src="../LibJava/clsRpc.js"></script>
<script language="JavaScript" src="./CoRtTr_configtrans.js"></script>
<script language="JavaScript">
//Begin CCS script
//Include Common JSFunctions @1-73ADA5ED
</script>
</head>
<body bgcolor="#fffff7" link="#000000" alink="#ff0000" vlink="#000000" text="#000000" class="CobaltPageBODY" >
{include file="../templates/Cabecera.tpl" }
<!-- Record movim_query
<table border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse" width="100%" align="center">
  <tr>
    <td align="middle">
      <form action="" method="post" name="fmSeleccion">
        <font class="CobaltFormHeaderFont">BUSCAR TRANSACCIONES </font>
        <table border="1" cellpadding="0" class="CobaltFormTABLE" cellspacing="0">
          <tr>
            <td class="CobaltFieldCaptionTD" nowrap>&nbsp;CUENTA&nbsp;</td>
            <td class="CobaltDataTD">&nbsp;
                <select class="CobaltSelect" name="selAuxiliar" style="font-size: 9; height: 17; width: 95">
                <option value="">- - - - - - - - - - - - </option>
                {section name=i loop=$aAux}
                    <option value="{$aAux[i].per_CodAuxiliar}" {if  $aAux[i].per_CodAuxiliar == $smarty.request.selAuxiliar} selected {/if}  >
                            {$aAux[i].txt_Nombre}
                    </option>
                 {/section}
              </select>
            </td>
          </tr>
           <tr>
            <td align="middle" class="CobaltFooterTD" colspan="27" nowrap>
            <input class="CobaltButton" name="btBuscar" type="submit" value="Buscar">&nbsp; </td>
          </tr>
        </table>
      </form>
 -->
      <!-- BEGIN Grid movim_lista -->
      <font class="CobaltFormHeaderFont" align="center">- TRANSACCIONES DISPONIBLES -</font>
      <table border="0" cellpadding="3" cellspacing="1" style="border-collapse: collapse"  align="center">
        <tr align="middle" >
          <td  nowrap width=""></td>
          <td class="CobaltColumnTD"  colspan={$numCols} style="text-align:center">TRANSACCION </td>
        </tr>
        <tr align="middle" >
          <td class="CobaltColumnTD"  nowrap width="">TIPO DE COMPROBANTE</td>
          {section name=j loop=$aNomCols}
          <td class="CobaltColumnTD" align="center" nowrap width="60">{$aNomCols[j][1]}</td>
          {/section}
        </tr>
      <!-- BEGIN Row -->
      
        {section name=row loop=$aRowData}
		<tr valign="top">
		    <td >{$aRowData[row][0]} - {$aRowData[row][1]}</td>
			{section name=col start=2 loop=$aRowData[row]}
			<td align="center"><input value = "{$aRowData[row][col]}" size=2 maxsize=2 class="CobaltInputSB2"
				  onchange="fCambioDato(this, {$aRowData[row][0]}, {$smarty.section.col.iteration})"></input>
			</td>

			{/section}
		</tr>
		{/section}

      

      </table>
      <!-- END Grid movim_lista -->&nbsp;
 </td> 
  </tr>
   <tr>
    <td>&nbsp;</td> 
  </tr>
</table>
</body>
</html>
