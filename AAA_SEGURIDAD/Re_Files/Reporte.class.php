<?php
class Reporte {

    private $_objConnection = "";

    //put your code here
    public function __construct() {
        $this->_objConnection = new CMysqlConnection();
        $this->_objConnection->connection();
    }

    public function getBrands() {
        $query = "SELECT * FROM `genparametros` WHERE par_Clave = 'IMARCA';";
        return $this->_objConnection->executeQuery($query);
    }

    public function getMultiplier() {
        $query = "SELECT * FROM `genparametros` WHERE par_Clave = 'REQFR';";
        return $this->_objConnection->executeQuery($query);
    }

    public function getTransito() {
        $query = "SELECT * FROM `genparametros` WHERE par_Clave = 'ESTIMP' AND par_Descripcion = 'TRANSIT';";
        return $this->_objConnection->executeQuery($query);
    }

    public function getInitialDate() {
        $query = "SELECT * FROM `genparametros` WHERE par_Clave = 'DATEMO';";
        return $this->_objConnection->executeQuery($query);
    }

    public function getDiffDate($fini, $ffin) {
        $query = "SELECT DATEDIFF('$ffin','$fini') AS DiffDate"; //
        //echo $query;
        return $this->_objConnection->executeQuery($query);
    }

    public function getSpecialItems($brands = "") {

        $query = "SELECT * FROM `genparametros` WHERE par_Clave = 'REQPR';";
        $rpta = $this->_objConnection->executeQuery($query);
        $base = "";
        $precio = "''";
        if (count($rpta) > 0) {                        
            if($rpta[0]['par_Valor3']!=""){
                    $base = $rpta[0]['par_Valor3'].".";
            }          
            $precio = $rpta[0]['par_Valor1'];
        }
        
        if ($brands != "") {
            $brands = " AND act_Marca IN ($brands)";
        }
        $query = "SELECT c.*, a.*, p.*, pr.*, g.par_Secuencia AS marca, g.par_Descripcion AS marca_Descripcion from concategorias c
                  INNER JOIN conactivos a on c.cat_CodAuxiliar = a.`act_CodAuxiliar` $brands
                  INNER JOIN genparametros p on p.par_Valor1 = c.`cat_Categoria` AND p.par_Clave = 'REQITM'
                  LEFT JOIN `genparametros` g ON a.act_Marca = g.par_Secuencia AND g.par_Clave = 'IMARCA'                  
                  LEFT join {$base}invprecios pr ON pr.pre_coditem = a.`act_CodAuxiliar` AND pr.pre_LisPrecios IN 
	  ($precio)";// WHERE c.cat_CodAuxiliar IN (25000, 25002, 25003);"; // echo $query; exit;
//echo $query;         EXIT;
//SELECT PAR_VALOR1 FROM `genparametros` WHERE PAR_CLAVE IN ('REQPR')
                  return $this->_objConnection->executeQuery($query);
    }

    public function getCellars() {
        $query = "SELECT par_Valor1, par_valor2, par_Valor3 FROM genparametros 
WHERE Par_Clave = 'BODREQ';"; //echo $query;
        /*$query = "SELECT a.par_Valor1, a.par_valor2, a.par_Valor3 FROM genparametros a
WHERE Par_Clave = 'BODREQ';";*/ 
        $rpta = array();
        $arrdata =  $this->_objConnection->executeQuery($query);
        foreach($arrdata as $data){
            
            $query = "SELECT  * FROM {$data[par_Valor3]}.conpersonas WHERE per_CodAuxiliar = '{$data[par_Valor1]}' LIMIT 1";
            //echo $query;
            $tmp = $this->_objConnection->executeQuery($query);
           
            if(count($tmp)>0){
                $data['per_Apellidos'] = $tmp[0]['per_Apellidos']; 
            }else{
                $data['per_Apellidos'] = ''; 
            }
            $rpta[] = $data;
        }
        //var_dump($rpta);
        return $rpta;
    }

    public function getMovementsByCellar($fini, $ffin, $base, $cellar) {

        if ($fini != "") {
            $fini = " AND com_feccontab >= '$fini'";
        }
        if ($ffin != "") {
            $ffin = " AND com_feccontab <=  '$ffin'";
        }
        if ($cellar != "") {
            $cellar = " AND com_Emisor = '$cellar'";
        }
        if ($base != "") {
            $base.=".";
        }

        $query = "SELECT com_Emisor AS COE,
            	det_coditem AS 'ITE',                
            	SUM(det_cantequivale * par_Valor4)  AS SAC,
            	SUM(det_cosTotal * par_Valor4)      AS VAC,
            	SUM(det_cosTotal * par_Valor4) /	SUM(det_cantequivale * par_Valor4)  AS PUN
            FROM {$base}invprocesos JOIN
            {$base}concomprobantes ON pro_codproceso = 1 AND com_tipocomp = cla_tipotransacc $fini $ffin $cellar
                JOIN `genparametros` ON par_valor2 = com_Emisor AND  par_Clave = 'COMPCO' AND com_TipoComp = par_Valor1 
            	JOIN {$base}invdetalle ON det_regnumero = com_regnumero
                JOIN {$base}conactivos ON act_codauxiliar = det_coditem 
            	JOIN {$base}genunmedida ON uni_codunidad= act_unimedida
            WHERE  (det_cantequivale <> 0 OR det_cosTotal <> 0) 
	    GROUP BY 1,2";
        //echo $query."</br></br>";
        $data = array();
        $arrTmp = $this->_objConnection->executeQuery($query);

        if (count($arrTmp) > 0) {
            foreach ($arrTmp as $tmp) {
                $data[$tmp['ITE']] = $tmp['SAC'];
            }
        }
        return $data;
    }

    public function getCiItems($fini, $ffin, $cellar = '', $transito = '-1') {
        
          $query = " select * from genparametros p 
    where  par_Clave = 'REQCOM' AND par_Valor1 = 'CI' 
  ";
            //echo $query;
            $tmp = $this->_objConnection->executeQuery($query);           
            $base = "";
            if(count($tmp)>0){
                $base = $tmp[0]['par_Valor3']."."; 
            }
        
        
        $fcini = "";
        if ($fini != "") {
            //$fcini = " AND com_FecVencim >= '$fini'";
            $fini = " AND com_feccontab >= '$fini'";
        }
        $fcfin = "";
        if ($ffin != "") {
            //$fcfin = " AND com_FecVencim <=  '$ffin'";
            $ffin = " AND com_feccontab <=  '$ffin'";
        }
        $cellar = "";
        /* if ($cellar != "") {
          $cellar = " AND com_Emisor = '$cellar'";
          } */

        $query = "SELECT com_Emisor AS COE,
            	det_coditem AS 'ITE',                
            	SUM(det_cantequivale)  AS SAC,
            	SUM(det_cosTotal)      AS VAC,
            	SUM(det_cosTotal) /	SUM(det_cantequivale)  AS PUN
            FROM {$base}concomprobantes 
            inner join {$base}genparametros p on p.par_Valor1 = com_tipocomp and  par_Clave = 'REQCOM'	AND com_EstProceso = '$transito'
            	JOIN {$base}invdetalle ON det_regnumero = com_regnumero $fini $ffin  $fcini $fcfin $cellar
            	JOIN {$base}conactivos ON act_codauxiliar = det_coditem 
            	JOIN {$base}genunmedida ON uni_codunidad= det_unimedida
            WHERE  (det_cantequivale <> 0 OR det_cosTotal <> 0) 
	    GROUP BY 2 ";
        //echo $query."</br></br>";
        $data = array();
        $arrTmp = $this->_objConnection->executeQuery($query);

        if (count($arrTmp) > 0) {
            foreach ($arrTmp as $tmp) {
                $data[$tmp['ITE']] = $tmp['SAC'];
            }
        }
        return $data;
    }

    // Cellar es el item: $cellar
    public function getCiItemsDetail($fini, $ffin, $cellar, $transito = '-1') {
        
        
        $fcini = "";
        if ($fini != "") {
            //$fcini = " AND com_FecVencim >= '$fini'";
            $fini = " AND com_feccontab >= '$fini'";
        }
        $fcfin = "";
        if ($ffin != "") {
            //$fcfin = " AND com_FecVencim <=  '$ffin'";
            $ffin = " AND com_feccontab <=  '$ffin'";
        }
       // $cellar = "";

        $query = "SELECT concomprobantes.*, invdetalle.*, gp.par_Descripcion AS ciudad
            FROM {$base}concomprobantes 
            inner join {$base}genparametros p on p.par_Valor1 = com_tipocomp and  par_Clave = 'REQCOM'	AND com_EstProceso = '$transito'
            	JOIN {$base}invdetalle ON det_regnumero = com_regnumero $fini $ffin  $fcini $fcfin AND det_coditem = '$cellar'
            	JOIN {$base}conactivos ON act_codauxiliar = det_coditem 
            	JOIN {$base}genunmedida ON uni_codunidad= det_unimedida
                LEFT JOIN {$base}`genparametros` gp  ON  concomprobantes.com_Supervisor = gp.par_Secuencia AND gp.par_clave = 'IMPCI'
            WHERE  (det_cantequivale <> 0 OR det_cosTotal <> 0) 
	   ";
        //echo $query."</br></br>";
        $arrTmp = $this->_objConnection->executeQuery($query);
        return $arrTmp;
    }

    public function getItemsByCellar($fini, $ffin, $base, $cellar) {

        if ($fini != "") {
            $fini = " AND com_feccontab >= '$fini'";
        }
        $fini = " AND com_feccontab >= '2004-12-31'";
        if ($ffin != "") {
            $ffin = " AND com_feccontab <=  '$ffin'";
        }
        if ($cellar != "") {
            $cellar = " AND com_Emisor = '$cellar'";
        }
        if ($base != "") {
            $base.=".";
        }

        $query = "SELECT com_Emisor AS COE,
            	det_coditem AS 'ITE',                
            	sum(det_cantequivale * pro_signo)  as SAC,
            	sum(det_cosTotal * pro_signo)      as VAC,
            	sum(det_cosTotal * pro_signo) /	sum(det_cantequivale * pro_signo)  as PUN
            FROM {$base}invprocesos JOIN
                {$base}concomprobantes ON pro_codproceso = 1 AND com_tipocomp = cla_tipotransacc $fini $ffin $cellar
                JOIN {$base}invdetalle ON det_regnumero = com_regnumero
                JOIN {$base}conactivos ON act_codauxiliar = det_coditem 
                JOIN {$base}genunmedida ON uni_codunidad= act_unimedida
            WHERE  (det_cantequivale <> 0 OR det_cosTotal <> 0) 
	    GROUP BY 1,2 ";
        //echo $query."</br></br>";
        $data = array();
        $arrTmp = $this->_objConnection->executeQuery($query);

        if (count($arrTmp) > 0) {
            foreach ($arrTmp as $tmp) {
                $data[$tmp['ITE']] = $tmp['SAC'];
            }
        }
        return $data;
    }

}
