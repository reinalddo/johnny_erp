<!doctype html public "-//W3C//DTD HTML 4.0 //EN">
<html>
<head>
<base target="_self">
<link rel="stylesheet" type="text/css" href="../Themes/StormyWeather/Style.css">
<link rel="stylesheet" type="text/css" href="../Themes/Menu/Style.css">
<title>CONCILIACION BANCARIA</title>
<script language="JavaScript" src="../LibJava/menu_func.js"></script>
<link rel="stylesheet" type="text/css" href="../Themes/Cobalt/Style.css">
<script language="JavaScript">
//Begin CCS script
//Include Common JSFunctions @1-73ADA5ED
</script>
<script language="JavaScript">
</script>
</head>
<body bgcolor="#fffff7" link="#000000" alink="#ff0000" vlink="#000000" text="#000000" class="CobaltPageBODY">
{include file="../Templates/Cabecera.tpl" }
<table border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse" width="100%">
  <tr>
    <td align="middle">
      <form action="" method="post" name="fmSeleccion">
        <font class="CobaltFormHeaderFont">BUSCAR CONCILIACIONES </font>
        <table border="1" cellpadding="0" class="CobaltFormTABLE" cellspacing="0">
          <tr>
            <td class="CobaltFieldCaptionTD" nowrap>&nbsp;CUENTA&nbsp;</td>
            <td class="CobaltDataTD">&nbsp;
                <select class="CobaltSelect" name="selAuxiliar" style="FONT-SIZE:10; HEIGHT: 18px; WIDTH: 100px">
                <option value="">- - - - - - - - - - - - </option>
                {section name=i loop=$aAux}
                    <option value="{$aAux[i].per_CodAuxiliar}" {if  $aAux[i].per_CodAuxiliar == $smarty.request.selAuxiliar} selected {/if}  >
                            {$aAux[i].txt_Nombre}
                    </option>
                 {/section}
            </td>
          </tr>
           <tr>
            <td align="middle" class="CobaltFooterTD" colspan="27" nowrap>
            <input class="CobaltButton" name="btBuscar" type="submit" value="Buscar">&nbsp; </td>
          </tr>
        </table>
      </form>
      <!-- END Record movim_query -->
      <!-- BEGIN Grid movim_lista -->
      <font class="CobaltFormHeaderFont">CONCILIACIONES DISPONIBLES </font>
      <table border="1" cellpadding="3" cellspacing="1" border="0"  style="border-collapse:collapse" >
              <tr>
          <td class="CobaltFooterTD" colspan="11" nowrap>
            <!-- BEGIN Navigator Navigator -->
            <!-- BEGIN First_On --><a class="CobaltNavigatorLink" href="{$First_URL}"><img border="0" src="../Themes/Cobalt/FirstOn.gif"></a> <!-- END First_On -->
            <!-- BEGIN Prev_On --><a class="CobaltNavigatorLink" href="{$Prev_URL}"><img border="0" src="../Themes/Cobalt/PrevOn.gif"></a> <!-- END Prev_On -->&nbsp;{$Page_Number}&nbsp;de
            {$Total_Pages}&nbsp;
            <!-- BEGIN Next_On --><a class="CobaltNavigatorLink" href="{$Next_URL}"><img border="0" src="../Themes/Cobalt/NextOn.gif"></a> <!-- END Next_On -->
            <!-- BEGIN Last_On --><a class="CobaltNavigatorLink" href="{$Last_URL}"><img border="0" src="../Themes/Cobalt/LastOn.gif"></a> <!-- END Last_On --><!-- END Navigator Navigator -->&nbsp; </td>
        </tr>

        <tr align="middle">
          <td class="CobaltColumnTD" nowrap>
{literal}
            <!-- BEGIN Sorter Sorter_com_TipoComp --><a class="CobaltSorterLink" href="{Sort_URL}">FECHA</a><!-- END Sorter Sorter_com_TipoComp -->&nbsp;</td>
          <td class="CobaltColumnTD" nowrap>
            <!-- BEGIN Sorter Sorter_com_FecTrans --><a class="CobaltSorterLink" href="{Sort_URL}">DB MARCADOS</a><!-- END Sorter Sorter_com_FecTrans -->&nbsp;</td>
          <td class="CobaltColumnTD" nowrap>
            <!-- BEGIN Sorter Sorter_com_FecContab --><a class="CobaltSorterLink" href="{Sort_URL}">CR MARCADOS</a><!-- END Sorter Sorter_com_FecContab -->&nbsp;</td>
          <td class="CobaltColumnTD" nowrap>
            <!-- BEGIN Sorter Sorter_com_Usuario --><a class="CobaltSorterLink" href="{Sort_URL}">DIGITADO</a><!-- END Sorter Sorter_com_Usuario -->&nbsp;</td>
          <td class="CobaltColumnTD" nowrap>
{/literal}
        </tr>
        <!-- BEGIN Row -->
        {section name=j loop=$aDet}
        <tr>
          <td class="CobaltDataTD">&nbsp;<a class="CobaltDataLink" href="{$aDet[j].txt_Url}" title="Ver Detalles de la Conciliacion">{$aDet[j].con_FecCorte|date_format:"%b-%d-%y"}</a>&nbsp;</td>
          <td class="CobaltDataTD" title="Suma dest DEBITOS marcados">&nbsp;{$aDet[j].con_DebIncluidos}&nbsp;</td>
          <td class="CobaltDataTD" title="Suma dest CREDITOS marcados">&nbsp;{$aDet[j].con_CreIncluidos}&nbsp;</td>
          <td class="CobaltDataTD" title="Fecha y Usuario que digitó">&nbsp;{$aDet[j].txt_Digitado}</td>
        </tr>

        <!-- END Row -->
        {sectionelse}
        <!-- BEGIN NoRecords -->
        <tr>
          <td class="CobaltAltDataTD" colspan="11">No hay Detalles que Mostrar&nbsp;</td>
        </tr>
        <!-- END NoRecords -->
        {/section}
        <tr height="5">
          <td class="CobaltFooterTD" colspan="11"  >&nbsp;</td>
        </tr>
      </table>
      <!-- END Grid movim_lista -->&nbsp;</p>
 </td> 
  </tr>
   <tr>
    <td>&nbsp;</td> 
  </tr>
</table>
</body>
</html>
