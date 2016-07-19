<?php
/**		Contabilizacion de una transaccion GENERICA aplicando plantilla   @@@@TODO : Todo, ajustar y simplificar este proceso
 *		Genralizacion de InAdPr_contabapli.pro.php.
 *		Existen los siguientes modos de ejecucion:
 *
 *		1.-	Un comprobante especifico, modo default. Recibe el parametro
 *				pReg 	entero, regNumero del comprobante a contabilizar
 *
 *		2.- Un periodo contable. Requiere los parametros:
 *				pGen 	entero, con valor de 2
 *				pPro 	entero, ID de proceso de inventario (pro_codproceso) que selecciona las transacciones que intervendran
 *				pPer 	entero, ID de periodo contable
 *
 *		3.-	Un rango de periodos
 *				pGen 	entero, con valor de 3
 *				pPro  	entero, ID de proceso de inventario (pro_codproceso) que selecciona las transacciones que intervendran
 *				pPerI 	entero, ID de periodo inicial, menor que pPerI y mayor que cero
 *				pPerF 	entero, ID de periodo final
 *
 *		4.	Un rango de fechas
 *				pGen 	entero, con valor de 4
 *				pPro  	entero, ID de proceso de inventario (pro_codproceso) que selecciona las transacciones que intervendran
 *				pFecI 	fecha,  Fecha inicial
 *				pFecF 	fecha,  Fecha final
 *
 **/
//error_reporting (E_ALL);
$ilIni =microtime();
include ("../LibPhp/LibInc.php");   // para produccion
include_once("GenUti.inc.php");
include_once("../LibPhp/ConTranLib.php");
include_once("../LibPhp/ConTasas.php");
include_once("../LibPhp/genContabilizador.class.php");
	/**
	*   Contabilizacion de una transaccionde inventario. Se presume por omision que el proceso en el de Inventario (Kardex)
	*   @param      $db     Object      Byref, Coneccion ala base de datos
	*   @param      $pRNum  Integer     N�mero de registro del comprobante
	**/
	class clsContabInv extends clsContabilizador{
		function init(){
			$this->db->debug 	= fGetParam("pAdoDbg",0);
			$this->debug 		= fGetParam("pAppDbg",0);;
			$blModo = fGetparam('pGen', 1) ;
			$ilPro 	= fGetparam('pPro', 1) ; // Se presume por omision que el proceso es el de kardx de inv.
			$ilPer 	= fGetparam('pPer', false);
			$ilPerI = fGetparam('pPerI', 0);
			$ilPerF = fGetparam('pPerF', 0);
			$dlFecI = fGetparam('pFecI', 0);
			$dlFecF = fGetparam('pFecF', 0);
			if($ilPerI >0 and $ilPerF >= $ilPerI) $blTipo = 3;
			if($ilPer >0 ) $blModo = 2;
			$this->titulo = "";
			switch($blModo){
				case 1:		// Aplicar a un comprobante
					$pCond = " WHERE pro_codproceso = $ilPro AND com_regnumero = " . fGetparam('pReg', false);
					break;
				case 2:		//Aplicar a un periodo
					$pCond = " WHERE pro_codproceso = $ilPro AND com_numperiodo = " . $ilPer;
					$this->titulo = "PERIODO " . $ilPerI ;
					break;
				case 3:		//Aplicar a un rango de periodos.
					$pCond = " WHERE pro_codproceso = $ilPro AND com_numperiodo between " . $ilPerI . " and  " . $ilPerF;
					$this->titulo = "PERIODO DESDE " . $ilPerI . " HASTA " . $ilPerF;
					break;
				case 4:		//Aplicar a un periodo multiple de fechas.
					$pCond = " WHERE pro_codproceso = $ilPro AND com_feccontab BETWEEN '$dlFecI' AND '$dlFecF' ";
					$this->titulo =  "ENTRE " . $dlFecI . " HASTA " . $dlFecF;
					break;
				case 5:		//Aplicar a un rango de Semanas.
					$pCond = " WHERE pro_codproceso = $ilPro AND com_refOperat between " . $ilPerI . " and  " . $ilPerF;
					$this->titulo = "SEMANA DESDE " . $ilPerI . " HASTA " . $ilPerF;
					break;
			}
			$this->idField="com_regnumero";
			$this->setSql($pCond);
			echo "<br> CONDICION ".$pCond . " / " .$blModo;
			echo "<br>".$this->sql;
		}

		function setSql($pCond){
			$this->sql =
				"SELECT
					com_regnumero,
					com_tipocomp com_tipocomp,
					com_numcomp,
					com_codreceptor,
					com_concepto,
					det_secuencia,
					det_coditem 				AS ITE,
					det_cantequivale,
					ifnull(det_costotal * cla_clatransaccion,0) as COSTO,
					if(det_valtotal=0,det_costotal * cla_clatransaccion, det_valtotal * cla_clatransaccion) as VALOR,
					ifnull(det_valtotal * cla_clatransaccion, 0) AS tmpVALOR,
					det_DescTotal 				as 'DESC',
					uni_abreviatura,
					ifNULL(gr.par_valor1,'10') 	AS SGR,
					ifNULL(sg.par_valor1,'10') 	AS SSG,
					ifNULL(gr.par_valor2,'NN') 	AS GRCTA,
					ifNULL(sc.par_valor1,'') 	AS SSC,
					act_grupo,
					if(act_IvaFlag>0,act_IvaFlag, ifNULL(iv.par_valor1,0)) +0 as tmp_indiva,
					if(act_IvaFlag<>0, if(det_valtotal = 0,det_CosTotal, det_Valtotal) *
					(if(act_IvaFlag>0,1, 0) +0), 0) AS 'BIIVA',
					if(act_IvaFlag=0, if(det_valtotal = 0,det_CosTotal, det_Valtotal), 0) AS 'BIIVA0',
					if(act_IceFlag, det_ValTotal * (if(act_IceFlag>0,act_IceFlag, 0) +0), 0)  AS 'TICE',
					if(act_IceFlag>0, 0, if(det_valtotal = 0,det_CosTotal, det_Valtotal) ) AS 'BICE',
					com_tsaimpuestos,
					com_tipocomp,
					act_descripcion,
					com_feccontab,
					com_emisor 					AS EMI,
					com_codreceptor 			AS REC,
					com_receptor 				AS txtREC,
					act_sufijocuenta 			AS SIT,
					com_refoperat ,
					com_numperiodo,
					com_libro,
					com_fecdigita,
					per_codAnterior 			as SFR,
					act_IvaFlag 				as TIVA,
					li.par_valor3 				as LIB,
					cla_descripcion cla_descripcion,
					cla_tipocomp cla_tipocomp,
					cla_contabilizacion cla_contabilizacion,
					cla_indtransfer cla_indtransfer,
					cla_ctaorigen cla_ctaorigen,
					cla_ctadestino cla_ctadestino,
					cla_auxorigen cla_auxorigen,
					cla_auxdestino cla_auxdestino,
					cla_ctaingresos cla_ctaingresos,
					cla_ctacosto cla_ctacosto,
					cla_ctadiferencia cla_ctadiferencia,
					cla_reqreferencia cla_reqreferencia,
					cla_reqsemana cla_reqsemana,
					cla_clatransaccion cla_clatransaccion,
					cla_indicosteo cla_indicosteo,
					cla_ImpFlag cla_ImpFlag
				FROM invprocesos
					JOIN genclasetran		ON cla_tipocomp  = cla_tipotransacc
					JOIN concomprobantes c 	ON  cla_tipocomp = c.com_tipocomp
					JOIN conpersonas 		on per_codauxiliar = com_codreceptor
					JOIN invdetalle 		ON det_regnumero = com_regnumero
					JOIN conactivos 		ON act_codauxiliar = det_coditem
					JOIN genparametros gr 	ON gr.par_CLAVE ='ACTGRU' AND gr.par_secuencia = act_grupo
					JOIN genparametros sg 	ON sg.par_CLAVE ='ACTSGR' AND sg.par_secuencia = act_SubGrupo
					JOIN genparametros sc 	ON sc.par_CLAVE ='ACTSUB' AND sc.par_secuencia = act_subcategoria
					JOIN genparametros li 	ON li.par_CLAVE ='CLIBRO' AND li.par_secuencia = com_libro
					JOIN genparametros iv 	ON iv.par_CLAVE ='CTIVA'  AND iv.par_secuencia = act_IvaFlag
					JOIN genunmedida 		on uni_codunidad = act_unimedida
				" . $pCond .
				" ORDER BY 1, 5 "
					;
					echo $this;
		}
		function beforeAplicarPlantilla(){
			$this->w['valUni'] = ($this->record->det_cantequivale <> 0)? round(($this->record->VALOR / $this->record->det_cantequivale),6) : 0 ;
			$this->w['cosUni'] = ($this->record->det_cantequivale <> 0)? round(($this->record->COSTO / $this->record->det_cantequivale),6) : 0 ;
			return true;
		}

		function beforeAgregarMov(){
			//print("Det_CANTEQUIVALENTE".$this->record->det_cantequivale);
			//print("Det_COSTO".$this->record->COSTO);

			if ($this->record->cla_indtransfer == 1 ) {
				$this->recBase['det_secuencia'] = $this->secActual + 10000;
				$this->recBase['det_valdebito'] = 0;
				$this->recBase['det_codcuenta'] = $this->record->cla_ctadestino . $this->record->SGR;
				$this->recBase['det_valcredito'] = $this->record->COSTO; //

			}
			return true;

		}

		function afterAgregarMov(){return true;}

		function beforeGrabarComprob(){ return true;}

		function afterGrabarComprob(){}

		function encerarAcumuladores(){
			$this->acum=Array();
			$this->acum["COSTO"] 	= 0;
			$this->acum["VALOR"] 	= 0;
			$this->acum["BIIVA"] 	= 0;
			$this->acum["BIIVA0"] 	= 0;
			$this->acum["BICE"] 	= 0;
			$this->acum["TICE"] 	= 0;
			$this->acum["_SUMA"] 	= 0;
			$this->acum["_IMPIVA"]	= 0;
			$this->acum["_IMPICE"] 	= 0;
		}

		function acumular(){
			$this->acum["COSTO"] 	+= $this->record->COSTO;
			$this->acum["VALOR"] 	+= $this->record->VALOR;
			$this->acum["BIIVA"] 	+= $this->record->BIIVA;
			$this->acum["BIIVA0"] 	+= $this->record->BIIVA0;
			$this->acum["BICE"] 	+= $this->record->BICE;
			$this->acum["TICE"] 	+= $this->record->TICE;

		}

		function beforeRecord(){
			$slCodCue = "";
			$ilCodAux = 0;
			if ($this->record->VALOR == 0 AND $this->record->COSTO != 0) {$this->record->VALOR = $this->record->COSTO;}
			$this->record->IVA = 0;
			if ($this->record->cla_ImpFlag == 1 && $this->record->tmp_indiva)  { //                        Se aplica impuestos al item y transacc
				$this->record->IVA = round(($this->record->BIIVA * $alTasas['iva']) / 100, 2);
			}
			$this->dbgData("<br><br><br>Iva: " . $this->record->IVA. " / " . $this->record->cla_ImpFlag ." / ".$this->record->tmp_indiva);
			$this->record->VALTOTAL= NZ($this->record->BIIVA + $this->record->BIIVA0 + $this->record->IVA, 0); // Valor total, incluyendo Iva
		}
	}

$olInv = new clsContabInv();

$olInv->ejecutar();
$ilFin=microtime();
$txt .= "<br> Finalizo: " . date ("d M Y, H \h\\r\s: i \m\i\\n: s \s\e\g");
	$txt .= "<br> TIEMPO UTILIZADO:      ". round(microtime_diff($ilIni, $ilFin),2) . " segs. <br>";
	echo "<center> $txt <br> PROCESAMIENTO OK </center>";
	$txt.= " ." ;
