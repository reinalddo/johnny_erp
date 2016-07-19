<! -- tutorial.tpl -->
<!doctype html public "-//W3C//DTD HTML 4.0 //EN">
<html>
<head>
       <title>Title here!</title>
</head>
<body>
<table border="0" cellspacing="0" cellpadding="5">
      <tr>
            <td>TipoComp</td>
            <td>FecContab</td>
            <td>Glosa</td>
            <td>Descripcion</td>
            <td>ValCredito</td>
            <td>Valdebito</td>
      </tr>

      {section name=i loop=$aList}
      <tr>
            <td>{$aList[i].com_TipoComp}</td>
            <td>{$aList[i].com_FecContab}</td>
            <td>{$aList[i].det_Glosa}</td>
            <td>{$aList[i].det_Descripcion}</td>
            <td>{$aList[i].det_ValCredito}</td>
            <td>{$aList[i].det_Valdebito}</td>
      </tr>
      {/section}
</table>

</body>
</html>
