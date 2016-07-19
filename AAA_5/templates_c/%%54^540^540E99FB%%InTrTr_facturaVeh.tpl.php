<?php /* Smarty version 2.6.18, created on 2010-10-12 11:23:58
         compiled from InTrTr_facturaVeh.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'InTrTr_facturaVeh.tpl', 44, false),array('block', 'report_header', 'InTrTr_facturaVeh.tpl', 45, false),array('block', 'report_detail', 'InTrTr_facturaVeh.tpl', 120, false),array('block', 'report_footer', 'InTrTr_facturaVeh.tpl', 133, false),array('modifier', 'truncate', 'InTrTr_facturaVeh.tpl', 64, false),array('modifier', 'number_format', 'InTrTr_facturaVeh.tpl', 123, false),array('modifier', 'regex_replace', 'InTrTr_facturaVeh.tpl', 181, false),array('modifier', 'default', 'InTrTr_facturaVeh.tpl', 207, false),array('modifier', 'date_format', 'InTrTr_facturaVeh.tpl', 231, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Plantilla para imprimir factura de vehículos en formato html -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Erika Suarez" />
  <?php echo '
  <style type="text/css">
        .padding10{padding:10px;}
	.padding5{padding:4px;}
        .empresa{height:50px;font-size:20px;font-weight:bold;text-align:center;vertical-align:middle;}
        .titulo{font-weight:bold; text-align:center; vertical-align:middle; text-transform:uppercase;}
        .parrafo{text-align:justify; vertical-align:middle; text-transform:uppercase;}
        .bordeCompleto {border: #FFFFFF solid 1px;}
        .bordeDer {border-right: #FFFFFF solid 1px;}
        .bordeIzq {border-left: #FFFFFF solid 1px;}
        .bordeSup {border-top: #FFFFFF solid 1px;}
        .bordeInf {border-bottom: #FFFFFF solid 1px;}
	.fuente{font-size:12px;}
        .izq{text-align:left;}
        .der{text-align:right;}
        .cen{text-align:center;}
        .espacioCol1{padding-left:120px;}
	.cabTabla{height:40px;}
        .espacioDer{padding-right:25px !important;}
        .espacioIzq{padding-left:55px !important;}
	.espacioIzq2{padding-left:85px !important;}
	.espacioTipo{padding-left:25px !important;}
        @media print {
            body { font-size: 10pt; }
          }

    </style>
  '; ?>

  
  <!--<link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />-->
  <!--<link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />-->
  <!--<link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_tablas_print.css" title="CSS para imprimir" />-->
  
    <title>Factura</title>
  
</head>
<body id="top" style=" font:'sans-serif'; !important;  font-size:10px;" onload="window.print();">
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','groups' => 'TIPONOM','resort' => false)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <!--<hr/>-->
    <?php $this->assign('numCol', 7); ?>
    
    
        <table width="850" border=0 cellspacing=0 style="border:#FFFFFF solid 1px; margin-top:60px;">
	  
	<!--<tr>
            <td colspan=<?php echo $this->_tpl_vars['numCol']; ?>
 style="height:50px;font-size:16px;font-weight:bold;text-align:center;vertical-align:middle;">
                <?php echo $_SESSION['g_empr']; ?>
</td>            
        </tr>-->
	  <tr style="vertical-align:middle;">
	      <td colspan=4 class="espacioCol1 padding5 fuente" nowrap><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['RECEP'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 32) : smarty_modifier_truncate($_tmp, 32)); ?>
</td>
	      <td colspan=2 class="espacioCol1 fuente" ><?php echo $this->_tpl_vars['rec']['FECHA']; ?>
</td>
	  </tr>
	  <tr>
	        <td colspan=5 class="espacioCol1 fuente padding5"><?php echo $this->_tpl_vars['rec']['RUC']; ?>
</td>
	      <td colspan=2 class="espacioCol1 fuente" ><?php echo $this->_tpl_vars['rec']['TIPOPAGO']; ?>
 </td>
	  </tr>
	  <tr>
	      <td colspan=4 class="espacioCol1 fuente padding5"  nowrap><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['direccion'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 50) : smarty_modifier_truncate($_tmp, 50)); ?>
</td>
	      <td colspan=2 class="espacioCol1 fuente" ><?php echo $this->_tpl_vars['rec']['telefono']; ?>
</td>
	  </tr>
	  <tr>
	      <td colspan=3 class="espacioCol1 padding5 fuente" style="padding-left:150px;" nowrap><?php echo $this->_tpl_vars['rec']['FECHAING']; ?>
</td>
	      <td colspan=2 class="espacioIzq fuente" ><?php echo $this->_tpl_vars['rec']['FECHASAL']; ?>
</td>
	      <td colspan=1 class="espacioCol1 fuente"><?php echo $this->_tpl_vars['rec']['ORDEN']; ?>
</td>
	  </tr>
	  <tr>
	      <td colspan=3 class="espacioCol1 fuente padding5" nowrap><?php echo $this->_tpl_vars['rec']['DISCO']; ?>
</td>
	      <td colspan=2 class="fuente" style="padding-left:-60px;"><?php echo $this->_tpl_vars['rec']['MARCA']; ?>
</td>
	      <td colspan=1 class="espacioCol1 fuente"><?php echo $this->_tpl_vars['rec']['PRON']; ?>
</td>
	  </tr>
	  <tr>
	      <td colspan=3 class="espacioCol1 fuente padding5" nowrap><?php echo $this->_tpl_vars['rec']['PLACA']; ?>
</td>
	      <td colspan=3 class="fuente " style="padding-left:-60px;"><?php echo $this->_tpl_vars['rec']['MODELO']; ?>
</td>
	  </tr>
	  <tr>
	      <td colspan=3 class="espacioCol1 fuente padding5" nowrap><?php echo $this->_tpl_vars['rec']['MOTOR']; ?>
</td>
	      <td colspan=2 class="fuente" style="padding-left:-60px;" ><?php echo $this->_tpl_vars['rec']['A_O']; ?>
</td>
	      <td colspan=1 class="espacioCol1 fuente"><?php echo $this->_tpl_vars['rec']['KILOMETRAJE']; ?>
</td>
	  </tr>
	  <tr>
	      <td colspan=3 class="espacioCol1 padding5 fuente" nowrap><?php echo $this->_tpl_vars['rec']['CHASIS']; ?>
</td>
	      <td colspan=3 class=" fuente" style="padding-left:-60px;"><?php echo $this->_tpl_vars['rec']['COLOR']; ?>
</td>
	  </tr>
	  
	  
	  <tr style="font-weight:bold; text-align:center; vertical-align:middle; /*text-transform:uppercase;*/">
	    <td class="headerrow cabTabla" width="40">&nbsp;<!--<strong>CODIGO DE ITEM</strong>--></td>
	    <td class="headerrow cabTabla" width="30">&nbsp;<!--<strong>CANTIDAD</strong>--></td>
	    <td class="headerrow cabTabla" width="300">&nbsp;<!--<strong>ITEM</strong>--></td>
	    <td class="headerrow cabTabla" width="100">&nbsp;<!--<strong>VALOR SIN DESCUENTO</strong>--></td>
	    <td class="headerrow cabTabla" width="100">&nbsp;<!--<strong>DESCUENTO</strong>--></td>
	    <td class="headerrow cabTabla" width="100">&nbsp;<!--<strong>VALOR CON DESCUENTO</strong>--></td>
	  </tr>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>



<?php $this->_tag_stack[] = array('report_header', array('group' => 'TIPONOM')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
	  
	  <tr style="font-weight:bold;  vertical-align:middle;">
            <td class="coldata espacioTipo izq" colspan=3><?php echo $this->_tpl_vars['rec']['TIPONOM']; ?>
</td>
	  </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
	   <tr>
            <td class="coldata espacioIzq der"><?php echo $this->_tpl_vars['rec']['CODIT']; ?>
</td>
            <td class="colnum espacioIzq der"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['CANTE'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
	    <td class="coldata espacioIzq" nowrap><?php echo $this->_tpl_vars['rec']['ITEM']; ?>
</td>
            <td class="colnum espacioIzq der"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['VALSINDES'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	    <td class="coldata espacioIzq2 der" nowrap><?php echo $this->_tpl_vars['rec']['DESCU']; ?>
</td>
            <td class="colnum der" style="padding-left:-100px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['VALOR'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	    <td>&nbsp;</td>
	   </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>


<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
   <!-- 
    <tr>
		 <td class="colnum der" >&nbsp</td>  
		 <td class="colnum der" >&nbsp</td>
		 <td class="colnum der" >&nbsp</td>  
		 <td class="colnum der" >&nbsp</td>
		 <td class="colnum der" >&nbsp</td>
		 <td class="colnum der" >&nbsp</td>
    </tr>-->
    <tr>
		 <td class="colnum der" >&nbsp</td>  
		 <td class="colnum der" >&nbsp</td>
		 <td class="colnum der" >&nbsp</td>  
		 <td class="colnum der" >&nbsp</td>
		 <td class="colnum der" >&nbsp</td>
		 <td class="colnum der" >&nbsp</td>
    </tr>
    <tr>
		 <td class="colnum der" >&nbsp</td>  
		 <td class="colnum der" >&nbsp</td>
		 <td class="colnum der" >&nbsp</td>  
		 <td class="colnum der" >&nbsp</td>
		 <td class="colnum der" >&nbsp</td>
		 <td class="colnum der" >&nbsp</td>
    </tr>
     <tr>
		 <td class="colnum der" >&nbsp</td>  
		 <td class="colnum der" >&nbsp</td>
		 <td class="colnum der" >&nbsp</td>  
		 <td class="colnum der" >&nbsp</td>
		 <td class="colnum der" >&nbsp</td>
		 <td class="colnum der" >&nbsp</td>
    </tr>
     <tr>
		 <td class="colnum der" >&nbsp</td>  
		 <td class="colnum der" >&nbsp</td>
		 <td class="colnum der" >&nbsp</td>  
		 <td class="colnum der" >&nbsp</td>
		 <td class="colnum der" >&nbsp</td>
		 <td class="colnum der" >&nbsp</td>
    </tr>
    
     
     <!--Reemplaza los enter por salto de lineas-->
     <!-- <?php if (( ( $this->_tpl_vars['empresa'] == "COSTAFRUT S.A." || $this->_tpl_vars['empresa'] == "AMENEGSA S.A." || $this->_tpl_vars['empresa'] == "LIGHTFRUIT S.A." || $this->_tpl_vars['empresa'] == "FORZAFRUT S.A." || $this->_tpl_vars['empresa'] == "MUNDIPAK S.A." || $this->_tpl_vars['empresa'] == "CONTABAN S.A." ) )): ?>
	      <tr>
		  <td class="izq bordeDer"></td>
		  <td colspan=2 align="left" nowrap class="coldata espacioIzq"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['CONCEP'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/[\n]/", "<br>") : smarty_modifier_regex_replace($_tmp, "/[\n]/", "<br>")); ?>
</td> 
	      </tr>
     <?php endif; ?> -->
     
    <?php $this->assign('cuenta', $this->_tpl_vars['count']['CANTI']); ?>
    <?php $_from = $this->_tpl_vars['agFilas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['value1']):
?>
        <?php if (( $this->_tpl_vars['value1'] > $this->_tpl_vars['cuenta'] )): ?>
            <tr>
                <td class="der bordeIzq bordeDer" >&nbsp</td>
                <td class="izq bordeDer"></td>
                <td class="izq bordeDer"></td>
                <td class="izq bordeDer"></td>
		<td class="izq bordeDer"></td>
                <td class="izq bordeDer"></td>
            </tr>
        <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
	  <!-- Valor en letras:-->
	  <tr>
		  <td colspan=5 rowspan=20 class="espacioCol1" style="vertical-align:top;"><!--<strong>SON:</strong>--><?php echo $this->_tpl_vars['letras']; ?>
</td>
	  </tr>
	  
	  <!-- Subtotales -->
	  <?php $_from = $this->_tpl_vars['tipos']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tipoNom']):
?>
	  <tr>
		  <!--<td><strong>VALOR SIN DESCUENTO:</strong></td>-->  
		  <td  class="colnum espacioDer der padding5"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['valsindes'][$this->_tpl_vars['tipoNom']])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)))) ? $this->_run_mod_handler('default', true, $_tmp, "0.00") : smarty_modifier_default($_tmp, "0.00")); ?>
</td>
	  </tr>
	  <tr>
		  <!--<td><strong>DESCUENTO:</strong></td>-->  
		  <td  class="colnum espacioDer der padding5"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['valdes'][$this->_tpl_vars['tipoNom']])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)))) ? $this->_run_mod_handler('default', true, $_tmp, "0.00") : smarty_modifier_default($_tmp, "0.00")); ?>
</td>
	  </tr>
	  <tr>	  <td"></td>  </tr>
	  <tr>
		  <!--<td><strong>IVA:</strong></td>-->  
		  <td class="colnum espacioDer der padding5"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['valiva'][$this->_tpl_vars['tipoNom']])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)))) ? $this->_run_mod_handler('default', true, $_tmp, "0.00") : smarty_modifier_default($_tmp, "0.00")); ?>
</td>
	  </tr>
	  <tr>	  <td">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>  </tr>
	  <tr>
		  <!--<td><strong>TOTAL POR TIPO:</strong></td>-->  
		  <td class="colnum espacioDer der padding5"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['valor'][$this->_tpl_vars['tipoNom']])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)))) ? $this->_run_mod_handler('default', true, $_tmp, "0.00") : smarty_modifier_default($_tmp, "0.00")); ?>
</td>
	  </tr>
	  <tr>	  <td">&nbsp;</td>  </tr>
	  <?php endforeach; endif; unset($_from); ?>
	  <tr>
		    <!--<td><strong>TOTAL A PAGAR:</strong></td>-->
		    <td class="colnum espacioDer der padding5" style="padding-top:5px;"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['valorTot'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)))) ? $this->_run_mod_handler('default', true, $_tmp, "0.00") : smarty_modifier_default($_tmp, "0.00")); ?>
</td>
	  </tr>
</table>
</br>
<!-- <div style="font-size:0.8em; text-align:left;">Fecha Imp.: <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
</div> -->
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>