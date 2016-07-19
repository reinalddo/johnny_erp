<?php
/*
*   RecSetProc.php: Procesamiento de un record Set, controlando quiebres por cualquiera de sus campos
*   @author     Fausto Astudillo
*   @param      string		pQryLiq  Condición de búsqueda
*/
class RecSetProc
{
    /**    *       @var Apuntador a la BD    **/
    var $db = NULL;
    /**    *       @var Direccion del resultado de consulta    **/
    var $rs = NULL;
    /**    *       @var Arreglo que contiene los datos    **/
    var $data = array();
    /**    *   @var Arreglo que contiene el registro actual    **/
    var $row  = array();
    /**    *   @var Arreglo que contiene las columnas    **/
    var $columns = array();
    /**    *   @var Arreglo con los nombres de columnas    **/
    var $acols = array();
    /**    *   @var Arreglo que contiene los grupos    **/
    var $groups = array();
    /**    *   @var Numero de Registros    **/
    var $recno=0;
    var $idat=0;
/**
*   Process Initiator
*   @access  private
*   @param  object  $db         Apuntador a la BD
*   @param  string  $trsql      Sentencia Sql
*   @return void
*/
    function RecSetproc($db, $trsql)
    {
        $this->db = NewADOConnection(DBTYPE);
        $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
        $this->db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or die("<BR> <BR> no hay acceso al servidor");
        $this->db->debug=fGetParam('pAdoDbg', 0);
        $this->rs = $this->db->execute($trSql);
        if (!$this->rs) fErrorPage('',"NO SE PUDO EJECUTAR LA CONSULTA " );
        $this->rs->MoveFirst();
        if ($this->rs->EOF) fErrorPage('',"NO SE EXISTE INFORMACION PARA PROCeSAR " );
        $this->recno=0;
        $this->agrps = array_keys($this->groups);
        $idat=0;
        $this->columns =array_keys($this->rs->fields);
        $this->acols=array_keys($this->columns);
        //print_r($this->groups);
        //-------
    }
/**
*   Recorre todo el recordset ejecutando las tareas definidas en los eventos
*   @access  private
*   @param  object  $db         Apuntador a la BD
*   @param  string  $trsql      Sentencia Sql
*   @return void
*/
    function process()
{
        while (!$this->rs->EOF) {
            $grp=count($this->groups);
            $ilbreaks=0;
            $this->record = $this->rs->FetchRow();// -----------------                    DATA $this->record TO PROCESS
            $this->recno+=1;
            $brkLevel=0;
            $brkLevelMin=0;
        //echo "<br> rec: " .$recno . " " . $this->record['GRU'];
            if ($this->recno == 1) { //
                for ($i=count($this->agrps)-1; $i>=0; $i--) {
                    $slGroup = $this->agrps[$i];
                    $this->groups[$slGroup]->lastRec=$this->record;
                    $this->groups[$slGroup]->currValue = $this->record[$slGroup];
                    $this->groups[$slGroup]->lastValue = NULL;
                    if ($this->groups[$slGroup]->beforeEvent ) { processEvent('before_group_', $slGroup);  }
                }
            }
        // echo $this->record['REG']. " / " . $this->record['PRO']. " / " . $this->record['GRU']. " / <br>" ;
            foreach ($this->record as $col => $val) {//                            FOR Every Field in $this->record
                    if(isset($this->groups[$col]) ) { //                                When  a group for this column exist analize except for first $this->record
                        $this->groups[$col]->counts +=1;//                              Increment $this->record counting for group
        //                $this->groups[$col]->lastRec=$this->record;
                        if ($recno != 1) { //                                                   If is not the first $this->record
                            if ($this->groups[$col]->currValue != $this->record[$col]){ //                                               If the column value has changed or it is the last $this->record
                                $this->groups[$col]->lastValue = $this->groups[$col]->currValue;
                                $this->groups[$col]->break = true;  //                              break Flag for this col group
                                $ilbreaks+=1; //                                                    Col change counter
                                $brkLevel = array_search($col, $this->agrps);//                           Level of break, for recursive process
                                if ($brkLevel || $brkLevel > $brkLevelMin) $brkLevel = $brkLevelMin ; //   point to lowest level
                                $vlColValue = '';
                            }
                        }
                        $this->groups[$col]->currValue = $this->record[$col]; //          Set the current value for the column break
                    }
            } //                            Fin Rec procesamiento
            $idat++;
            if ($ilbreaks ) {
                processBreak($brkLevel);
                unset($this->data);
                $idat=0;
                $ilcount=count($this->agrps)-1;
        /**        for ($l=$brkLevel; $l<=$ilcount; $l++){
                    $slGroup=$this->agrps[$l];
                    if ($this->groups[$slGroup]->beforeEvent ) {
                        processEvent('before_group_', $slGroup); //   process the group headers for next section
                    }

                }
        **/
            }
            reset($this->agrps);
            reset($this->record);
            foreach ($this->record as $col => $val) {//                            FOR Every Field in $this->record acumulate
                foreach ($this->agrps as $grp) {
                    if(isset($this->groups[$grp]->resume[0][$col]) && $this->groups[$grp]->resume[0][$col]  == "S" ) {
                        if (!isset($this->groups[$grp]->sums[$col])) $this->groups[$grp]->sums[$col] = 0;
                        $this->groups[$grp]->sums[$col] += $val;
                    }
                }
            }
        //    if ($rs->EOF) $this->lastGrp=true;//                                 To force the last group after last $this->record
    //--------
    }
}
/**
*   Initialization of group values
*   @access  public
*   @param   string     $key  name of group to initialize; if null, aplies to all groups
*/
function iniGrps($key=false) {
    	if (!$key) $akeys=array_keys($this->groups);
    	else {
            if (is_array($key)) $akeys=($key);   // si viene un arrehlo
            else $akeys[] = $key;               // si viene un solo nombre
        }
//        print_r($this->columns);
        reset($this->columns);
        $i=0;
        foreach($this->columns as $col){
        	foreach($akeys  as  $grp) {
			    $this->groups[$grp]->sums[$col] = 0;    //              Initialize sums for every column
                $this->groups[$grp]->counts = 0;                //              Initialize counter for the group
            }
            $this->columns[$i]->previous = NULL;
            $i++;
        }
        unset($this->acols);
        unset($akeys);
}
/**
*   Event Before/After procesor: determines if a function for processing before or after a group break exists then calls it
*   The function must be called "before_XXXX" or "after_XXX", where XXXX = groupname; must receive a group object and return
*   text string to add at current position in pdf output.
*   @access  private
*   @param   string     $pType      Type of event
*   @param   string     $pGrp       Group name
*   @return  void
*/
function processEvent($pType, $pGrp='') {
        $slFuncName=$pType . $pGrp;//                                              name of the function to call ('event' After Group)
        if (strlen($pGrp))   $slFuncName();//                             call the function to simulate "Before/After Group Event"
    }
/**
*   Process a column break
*   @access     private
*   @param      array   $data        Reference to an array containing the actual data records
*   @param      array   $acols       The actual column names (ByRef)
*   @param      array   $agrps       The actual group names (ByRef )
*   @return     void
*/
function processBreak($brkLevel)  {                     // process the group break
        reset($this->groups);

        $aBreaks = array();
        $brkIni = count($this->agrps);                // nivel de corte inicial
        $j=count($this->agrps)-1;
        for ($i=0; $i<= $j; $i++){
            if ($this->groups[$this->agrps[$i]]->break) {    // si hay un corte en este grupo
                if ($i < $brkIni)  $brkIni = $i;// el nivel correspondiente es menor al minimo presumido, ahora el mminimo es el actual
            }
            if ($i >= $brkIni) $aBreaks[] = $this->agrps[$i]; // incluir todos los grupos menores al nivel de corte (mayor indice en el arreglo)
        }

//      print_r($this->agrps);
      foreach(array_reverse($aBreaks,TRUE) as $grp){      // para todos los grupos procesables en orden inverso;
                $this->groups[$grp]->break = false;
                $this->data=null;
                $this->data = Array();
                if ($this->groups[$grp]->afterEvent ) {
                    processEvent('after_group_', $this->groups[$grp]->name); //                         process a event
                }
                iniGrps($grp);//                                                Initialize cummulatives
      }
      foreach($aBreaks as $grp){      // para todos los grupos procesables en orden ascendent;
                $this->groups[$grp]->break = false;
                $this->data=null;
                $this->data = Array();
                if ($this->groups[$grp]->afterEvent ) {
                    processEvent('before_group_', $this->groups[$grp]->name); //                         process a event
                }
                $this->groups[$grp]->lastRec = $this->record;
                iniGrps($grp);//                                                Initialize cummulatives
      }
}
/**
*   Add a Resume line to the group header / footer definition
*   @access     public
*   @param      string      $pGrp   Group name
*   @param      string      $pAgg   Aggegate function to aply
*   @param      string      $pTxt   Text to present in resume line
*   @param      integer     $pBll   Blank Line Definition for resume: 0=Nothing, 1=Before, 2=After, 3=Both
*   @return     void
*/
function addResumeLine($pGrp, $pAgg='S', $pTxt="", $pBll=0) {
        $alres= array();
        $alres['resume_text'] = $pTxt;
        $this->groups[$pGrp]->linesBefore=$pBll;
        foreach($this->columns as $col) {
           $alres[$col] = $pAgg;
        }
        $this->groups[$pGrp]->resume[] = $alres;
        unset($alres);
    }
/**
*   set the aggregate function to aply for a column in a resume line
*   @access     public
*   @param      string      $pGrp   Group name
*   @param      integer     $pRes   Number of resume line
*   @param      string      $pCol   Column name
*   @param      string      $pTipo  Aggregate function to aply
*   @return     void
*/
function setAggregate($pGrp, $pRes=0, $pCol=false, $pTipo='S') {
    if ($pCol)   $this->groups[$pGrp]->resume[$pRes][$pCol] = $pTipo;
}
/**
*   New Group definition
*   @access  public
*   @param   string     $pGrp  name for group (must be a column name)
*/
    function addGrp($pGrp) {
        if ($this->stop) return;
        if (!isset($this->groups[$pGrp])) {
            if (isset($this->columns[$pGrp]) || strtolower($pGrp)=='general') {
                $lGrp = new clsGrp($pGrp);
                $lGrp->font = $this->font;
                $lGrp->fontSize = $this->fontSize;
                $this->groups[$pGrp]= $lGrp;
                unset($lGrp);
            }
            else  echo " $pGrp DEBE SER UN NOMBRE DE COLUMNA PREVIAMENTE DEFINIDO";
        }
        else echo " YA EXISTE UN GRUPO LLAMADO '$pGrp'";
    }
}
//----------------------------------------------------------- Class ResSetProc

?>
