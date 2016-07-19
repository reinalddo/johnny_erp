<?php
/**
*   Miscelaneos: Validador de REgistro Unico de Contribuyentes
*   @param  pRuc    String  Numero de ruc a verificar
*   @param  pTipo   integer Tipo de Ruc: 1=Ruc 2= CI
**/

/**
*   Validacion de Ruc aplicando Modulo 10 u 11 segun el tipo de identificacion
*   @param  pRuc    String  Numero de ruc a verificar
*   @param  pTipo   integer Tipo de Ruc: 1=Ruc 2= CI, omitir si no se desea analizar el tipo de Id
*   @return integer 	true=   Numero Valido,
*                       -13 =   Longitud Invalida
*                       -13 =   Longitud Invalida
*                       -2  =   2 primeros digitos invalidos
*                       -3  =   Tercer Digito invalido
*                       de -4 en adelante = Digito posicional invalido
*                       -90 =   El tipo de ID no corresponde
*                       -99 =   Digito Verificado Invalido
**/
function fValidaRuc($pRuc, $pTipo=false)
{

//echo "<br>$pTipo // ";
  switch ($pTipo) {
  case "O":
  case "P":
  case "F":                         // No validar ruc
    return true;
    break;
  case "R":                         // Aplicar Algoritmo Persona Juridica
  	$pTipo ="1";
	  break;
  case "C":                         // Aplicar Algoritmo Persona Natural
  	  $pTipo ="2";
	  break;
   default:
	}
  $iLong =strlen(trim($pRuc));
//echo $pRuc . " //  $iLong <br> $pTipo  <br>";
  if ("1" == $pTipo &&  ($iLong <> 13))
      return -13;
    
  if( ($iLong <> 13) &&  ($iLong <> 10 )){
    return -13;                                  		// Longitud Invalida
  }
  if (substr($pRuc,0,2) >22 || substr($pRuc,0,2) < 1){
    return -2;                                          // 2 Primeros digitosinvalidos
  }
  $aArrayRuc = array();

  for ($i=0; $i<13; $i++){
    $aArrayRuc[1][$i]= substr($pRuc,$i,1);
    $aArrayRuc[2][$i] =0;
    $aArrayRuc[3][$i] =0;
  }
  switch  (substr($pRuc,2,1)){
    case 9:    // Coeficientes para SociedadesPrivadas, y Extranjeros sin cedula
		  $aArrayRuc[2][0]=4;
		  $aArrayRuc[2][1]=3;
		  $aArrayRuc[2][2]=2;
		  $aArrayRuc[2][3]=7;
		  $aArrayRuc[2][4]=6;
		  $aArrayRuc[2][5]=5;
		  $aArrayRuc[2][6]=4;
		  $aArrayRuc[2][7]=3;
		  $aArrayRuc[2][8]=2;
		  $iDigVerif=10;
		  $iModulo = 11;
		  $iTipo = 1;                               // Tipo de Id segun el Ruc
		  break;
    case 6:    // Coeficientes para Sociedades Publicas
		  $aArrayRuc[2][0]=3;
		  $aArrayRuc[2][1]=2;
		  $aArrayRuc[2][2]=7;
		  $aArrayRuc[2][3]=6;
		  $aArrayRuc[2][4]=5;
		  $aArrayRuc[2][5]=4;
		  $aArrayRuc[2][6]=3;
		  $aArrayRuc[2][7]=2;
		  $iDigVerif=9;
		  $iModulo = 11;
		  $iTipo = 1;                          // Tipo de Id segun el Ruc
		  break;
    case 5:
    case 4:
    case 3:
    case 2:
    case 0:
    case 1:
											    // Coeficientes para Personas Naturales
		  $aArrayRuc[2][0]=2;
		  $aArrayRuc[2][1]=1;
		  $aArrayRuc[2][2]=2;
		  $aArrayRuc[2][3]=1;
		  $aArrayRuc[2][4]=2;
		  $aArrayRuc[2][5]=1;
		  $aArrayRuc[2][6]=2;
		  $aArrayRuc[2][7]=1;
		  $aArrayRuc[2][8]=2;
		  $iDigVerif=10;
		  $iModulo = 10;
		  $iTipo = 2;							// Tipo de Id segun el Ruc
		  break;
	default:
	    return -88;  							//  Tercer digito invalido
  }

  for ($i=0; $i< ($iDigVerif -1); $i++){         // Aplicar Coeficientes
    $aArrayRuc[3][$i]  = $aArrayRuc[1][$i]  * $aArrayRuc[2][$i];
    if ($aArrayRuc[3][$i] > 9&& $iTipo == 2) $aArrayRuc[3][$iDigVerif-1] += substr($aArrayRuc[3][$i],0, 1) + substr($aArrayRuc[3][$i],1,1);
  	else $aArrayRuc[3][$iDigVerif -1] += $aArrayRuc[3][$i] ;
  }
  $iResiduo   = $aArrayRuc[3][$iDigVerif-1] % $iModulo;
  if ($iResiduo == 0)  $iDigito = 0;
  else  $iDigito = $iModulo - $iResiduo;
  if (fGetParam('pAdoDbg', false)) {
	  echo  "ruc : " . $pRuc . "<br>" .
	  		"posi : " . $iDigVerif . "<br>" .
	        "sum : " . $aArrayRuc[3][$iDigVerif -1] ."<br>" .
	        "Res : " . $iResiduo ."<br>" .
	        "Dig : " . $iDigito ."<br>" .
	        "Ver :  " . $aArrayRuc[1][$iDigVerif-1];
  }
  if ($iDigito <> $aArrayRuc[1][$iDigVerif-1]) return -99;  // Digito Verificador Invalido
  //if ($pTipo && $iTipo <> $pTipo) return -90;   // Si el tipo de Id enviado no coincide con el de la estructura del ruc
  return true;
}
?>
