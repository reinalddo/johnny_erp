<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <META http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
        <title>CARGA DE ARCHIVO</title>
        <link rel="stylesheet" type="text/css" href="../Themes/Cobalt/Style.css">
        <link rel="stylesheet" type="text/css" href="../Themes/Menu/Style.css">

        <script>
        </script>
    </head>
    <body bgcolor="#fffff7" align="center" >
    <center>
        <form action="InTrTr_excel.php" method="post" enctype="multipart/form-data">
            <div class="normalbox" style="PADDING-RIGHT: 4px; PADDING-LEFT: 4px; PADDING-BOTTOM: 4px; MARGIN: 20px; PADDING-TOP: 0px; TEXT-ALIGN: center">
                <table background="../Themes/Cobalt/Light Streaks.bmp" border="0" width="100%">
                    <tr><td>Archivo</td>
                        <td><input type="file" name="archivo" required/></td></tr>
                    <td>A&ntilde;o</td>
                    <td>
                        <select name="axio">
                            <?php
                            for ($x = 0; $x < 2050; $x++) {
                                if ($x == date("Y")) {
                                    echo "<option value='$x' selected>$x</option>";
                                } else {
                                    echo "<option value='$x'>$x</option>";
                                }
                            }
                            ?>
                        </select>
                    </td>
                    <tr><td>Semana</td>
                        <td><input type="number" name="semana" required/></td></tr>
                    <tr><td colspan="2"><input type="submit" value="Cargar Archivo"></td></tr>
                </table>
            </div>
        </form>
    </center>
</body>
</html>

