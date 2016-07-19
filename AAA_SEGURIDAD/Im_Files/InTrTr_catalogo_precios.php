<?php
error_reporting(E_ALL);
define("RelativePath", "..");
include_once("../LibPhp/GenCifras.php");
include_once("General.inc.php");
include_once("GenUti.inc.php");
include_once("adodb.inc.php");
extract($_REQUEST);

class CatalogoPrecios {

    private $link;

    public function CatalogoPrecios() {
        $this->link = mysqli_connect(DBSRVR, DBUSER, DBPASS, DBNAME);
    }

    public function getCatalogo() {
        $query = "SELECT act_CodAuxiliar, act_CodAnterior, CONCAT(conactivos.act_Descripcion, ' ', conactivos.act_Descripcion1) AS descripcion
, grupo.`par_Descripcion` AS grupo, subgrupo.`par_Descripcion` AS subgrupo,marca.`par_Descripcion` AS marca,
 conactivos.act_Modelo as modelo, concat(per_Apellidos, ' ', per_Nombres) AS per_Nombres,
per_Ruc, per_Direccion,	per_Telefono1,	per_PersonaContacto, per_Email, per_CodAuxiliar, pre_PreUnitario FROM    
    conactivos 
    INNER JOIN invprecios ON conactivos.act_CodAuxiliar =  invprecios.pre_CodItem AND pre_LisPrecios = 1 
    LEFT JOIN genvariables ON iab_ObjetoID = act_CodAuxiliar AND iab_Tipo = '30' AND iab_VariableID = '16' 
      LEFT JOIN conpersonas ON iab_ValorTex = per_CodAuxiliar
LEFT JOIN genparametros grupo ON grupo.par_Secuencia = conactivos.act_Grupo AND grupo.par_Clave = 'ACTGRU'
LEFT JOIN genparametros subgrupo ON subgrupo.par_Secuencia = conactivos.act_SubGrupo AND subgrupo.par_Clave = 'ACTSGR'
LEFT JOIN genparametros marca ON marca.par_Secuencia = conactivos.act_Marca AND marca.par_Clave = 'IMARCA'
      "; //echo $query;

        return mysqli_query($this->link, $query);
    }

}

$objCatalogo = new CatalogoPrecios();
$res = $objCatalogo->getCatalogo();
$table = "";
if (mysqli_num_rows($res) > 0) {
    while ($data = mysqli_fetch_array($res)) {
        $table .= "<tr><td><a onclick='openItem({$data['act_CodAuxiliar']})'>{$data['act_CodAuxiliar']}</a></td><td>{$data['act_CodAnterior']}</td><td>{$data['descripcion']}</td><td>{$data['grupo']}</td><td>{$data['subgrupo']}</td><td>{$data['marca']}</td><td>{$data['modelo']}</td><td>{$data['pre_PreUnitario']}</td><td><a onClick=openProvider({$data['per_CodAuxiliar']})>{$data['per_Nombres']}</a></td><td>{$data['per_Ruc']}</td><td>{$data['per_Direccion']}</td><td>{$data['per_Telefono1']}</td><td>{$data['per_PersonaContacto']}</td><td>{$data['per_Email']}</td></tr>";
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <base target="_self">
        <title>CATALOGO DE COMPRAS</title>

        <link rel="stylesheet" type="text/css" href="../LibJs/DataTables/media/css/bootstrap.css"/>
        <link rel="stylesheet" type="text/css" href="../LibJs/DataTables/media/css/jquery.dataTables.css"/>
        <script type="text/javascript" language="javascript" src="../LibJs/DataTables/media/js/jquery.js"></script>
        <script type="text/javascript" language="javascript" src="../LibJs/DataTables/media/js/bootstrap.min.js"></script>        
        <script type="text/javascript" language="javascript" src="../LibJs/DataTables/media/js/jquery.dataTables.js"></script>
        
        <style>
            body {
                color: #333;
                font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
                font-size: 11px;
                line-height: 1;
            }

        </style>
        <script type="text/javascript">
            $(document).ready(function() {
                
                $('#example').DataTable({"language": {
                 "url": "../LibJs/DataTables/media/dataTables.spanish.lang"
                 }});

            });
            function openItem(id){
                window.open('../Co_Files/CoAdAc_mant2.php?act_CodAuxiliar=' + id, '_blank', "width=800, height=450");
            }
             function openProvider(id){
                window.open('../Co_Files/CoAdAu_mant.php?per_CodAuxiliar=' + id, '_blank', "width=800, height=450");
            }
            
            function printCatalog(){
                //$("#imprimir").hide();
                window.print();
            }
        </script>
    </head>
    <body>
        <div class="container">
            </br>
            <input id= "imprimir" class="btn btn_primary" type="button" value="Imprimir" onclick="printCatalog()">
            </br>
            </br>
            <table id='example'>
                <thead>
                    <tr>
                        <th>Cod Item</th>
                        <th>Cod Alterno</th>
                        <th>Descricpion</th>
                        <th>Grupo</th>
                        <th>Subgrupo</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Precio</th>
                        <th>Proveedor</th>
                        <th>Ruc</th>
                        <th>Direccion</th>
                        <th>Telefono</th>
                        <th>Contacto</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $table; ?>
                </tbody>
            </table>

        </div>
        
    </body>



</html>
