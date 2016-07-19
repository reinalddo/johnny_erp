<?php /* Smarty version 2.6.18, created on 2015-06-17 10:16:09
         compiled from CoRtTr_comprob_Matricial_Asis.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'CoRtTr_comprob_Matricial_Asis.tpl', 43, false),array('block', 'report_header', 'CoRtTr_comprob_Matricial_Asis.tpl', 44, false),array('block', 'report_detail', 'CoRtTr_comprob_Matricial_Asis.tpl', 84, false),array('block', 'report_footer', 'CoRtTr_comprob_Matricial_Asis.tpl', 140, false),array('modifier', 'number_format', 'CoRtTr_comprob_Matricial_Asis.tpl', 90, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Plantilla que aplica a la impresion del comprobante de retencion en matricial -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Erika Suarez" />
  <?php echo '
  <style type="text/css">
        .padding10{padding:10px;}
        .empresa{height:50px;font-size:20px;font-weight:bold;text-align:center;vertical-align:middle;}
        .titulo{font-weight:bold; text-align:center; vertical-align:middle; text-transform:uppercase;}
        .parrafo{text-align:justify; vertical-align:middle; text-transform:uppercase;}
        .bordeCompleto {border: #FFFFFF solid 1px;}
        .bordeDer {border-right: #FFFFFF solid 1px;}
        .bordeIzq {border-left: #FFFFFF solid 1px;}
        .bordeSup {border-top: #FFFFFF solid 1px;}
        .bordeInf {border-bottom: #FFFFFF solid 1px;}
        .izq{text-align:left;}
        .der{text-align:right;}
        .cen{text-align:center;}
        .espacioCol1{padding-left:70px;}
        .cabTabla{height:45px;}
        .espacioDer{padding-right:24px !important;}
        .espacioIzq{padding-left:10px !important;}
	.resalta {font-weight:900; font-style:italic}
        @media print {
            body { font-size: 9pt; }
        }
	@page retencion {size:21.5cm 15.5cm;}
	pagina {page: retencion;}
    </style>
  '; ?>

  
  <!--<link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />-->
  <!--<link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />-->
  <!--<link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_tablas_print.css" title="CSS para imprimir" />-->
  
  <title>RETENCION</title>
  
</head>
<body id="top" style="font-family:'Arial' !important; font-size:9pt; font-weight:bold " onload="window.print();" >
  <div id="pagina" style="margin-left:0pt; height:7.5cm">
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','groups' => 'TIB','resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <!--<hr/>-->
        
        
    <div id = "cuerpo" style="height:6.5cm; border:#FFFFFF solid 1px; margin-top:120pt;">
    <!-- ********************************** C A B E C E R A ***********************************-->  
    <table border=0 cellspacing=0 style="table-layout:fixed; padding-left: 0px; border:#FFFFFF solid 1px; width:800px; height:'2.5cm'" >
        <tr style="height:0.7cm;">
	    <td colspan=1 class="resalta" nowrap style="padding-top:6pt;width:130pt;">&nbsp;</td>
	    <td colspan=1 class="" nowrap  style="width:400pt"><?php echo $this->_tpl_vars['rec']['NOM']; ?>
</td>
	    <td colspan=1 class="" style="width:200pt">&nbsp;</td>
	    <td colspan=1 class="" nowrap><?php echo $this->_tpl_vars['rec']['FEC']; ?>
</td>
        </tr>
	<tr style="height:0.7cm;">
	    <td colspan=1 class="resalta" nowrap style="padding-top:6pt;width:130pt;">&nbsp;</td>
	    <td colspan=1 class="" nowrap  style="width:400pt"><?php echo $this->_tpl_vars['rec']['RUC']; ?>
</td>
	    <td colspan=1 class="" style="width:200pt">&nbsp;</td>
	    <td colspan=1 class="" style="padding-left:1.7cm" nowrap><?php echo $this->_tpl_vars['rec']['TIP']; ?>
</td>
        </tr>
        <tr style="height:0.7cm;">
	    <td colspan=1 class="resalta" nowrap style="padding-top:6pt;width:130pt;">&nbsp;</td>
	    <td colspan=1 class="" nowrap  style="width:400pt"><?php echo $this->_tpl_vars['rec']['Direcc']; ?>
</td>
	    <td colspan=1 class="" style="width:200pt">&nbsp;</td>
	    <td colspan=1 class="" style="padding-left:1.5cm" nowrap><?php echo $this->_tpl_vars['rec']['FAC']; ?>
</td>
        </tr>
        </table>
      <!-- ********************************** DETALLE ***********************************-->  
	
      <table border=0  cellspacing=0 style="table-layout:fixed; padding-left: 0px; border:#FFFFFF solid 1px; width:800px; height:'4cm'"  >
        <tr style="font-weight:bold; text-align:center; vertical-align:middle; text-transform:uppercase;">
	  <td class="headerrow cabTabla resalta" style="text-align:center; width:180pt;">&nbsp;<!--<strong>EJERCICIO FISCAL</strong>--></td>
	  <td class="headerrow cabTabla resalta" style="text-align:center; width:307pt;">&nbsp;<!--<strong>BASE IMPONIBLE</strong>--></td>
	  <td class="headerrow cabTabla resalta" style="text-align:center; width:155pt;">&nbsp;<!--<strong>IMPUESTO</strong>--></td>
	  <!--<td class="headerrow cabTabla resalta" style="text-align:center; width:115pt;">&nbsp;--><!--<strong>COD RETENCION</strong>--><!--</td>-->
	  <td class="headerrow cabTabla resalta" style="text-align:center; width:130pt;">&nbsp;<!--<strong>% RETENCION</strong>--></td>
	  <td class="headerrow cabTabla resalta" style="width:200pt;">&nbsp;<!--<strong>VALOR RETENIDO</strong>--></td>
	</tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
        <?php if (( $this->_tpl_vars['rec']['MIB'] > 0 )): ?>
		      <tr style="white-space:nowrap; height:0.62cm;">
			<td class="colnum der" style="text-align:center;"> <?php echo $this->_tpl_vars['rec']['PER']; ?>
</td>
			<td nowrap class="coldata espacioIzq" style="text-align:center; "><?php echo $this->_tpl_vars['rec']['BIB']; ?>
</td>
			<td class="colnum espacioDer der" style="text-align:center; ">IVA</td>
			<!--<td style="text-align:center; width:75pt;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['TIB2'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td> -->
			<td class="colnum espacioDer der" style="text-align:center; "><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['PIB'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
 %</td>
			<td class="colnum espacioDer der" style="text-align:center; "><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['MIB'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
		      </tr>
	<?php endif; ?>
			
		      <?php if (( $this->_tpl_vars['rec']['MIS'] > 0 )): ?>
		      <tr style="white-space:nowrap;height:0.7cm;">
		      <td class="colnum der" style="text-align:center; "><?php echo $this->_tpl_vars['rec']['PER']; ?>
</td>
			<td nowrap class="coldata espacioIzq" style="text-align:center; "><?php echo $this->_tpl_vars['rec']['BIS']; ?>
</td>
			<td class="colnum espacioDer der" style="text-align:center;">IVA</td>
			<!--<td style="text-align:center; width:75pt;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['TIS2'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>-->
			<td class="colnum espacioDer der" style="text-align:center; "><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['PIS'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
 %</td>
			<td class="colnum espacioDer der" style="text-align:center; "><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['MIS'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			</tr>
		      <?php endif; ?>
			
		      <?php if (( $this->_tpl_vars['rec']['MIR'] > 0 )): ?>
		      <tr style="white-space:nowrap; height:0.7cm;">
		      <td class="colnum der" style="text-align:center; "><?php echo $this->_tpl_vars['rec']['PER']; ?>
</td>
			<td nowrap class="coldata espacioIzq" style="text-align:center;"><?php echo $this->_tpl_vars['rec']['BIR']; ?>
</td>
			<td class="colnum espacioDer der" style="text-align:center;">RENTA</td>
			<!--<td style="text-align:center; width:75pt;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['TIR'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>-->
			<td class="colnum espacioDer der" style="text-align:center;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['PIR'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
 %</td>
			<td class="colnum espacioDer der" style="text-align:center;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['MIR'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			</tr>
		      <?php endif; ?>
			
		      <?php if (( $this->_tpl_vars['rec']['MIR2'] > 0 )): ?>
		      <tr style="white-space:nowrap;height:0.7cm;">
		      <td class="colnum der" style="text-align:center;"><?php echo $this->_tpl_vars['rec']['PER']; ?>
</td>
			<td nowrap class="coldata espacioIzq" style="text-align:center;"><?php echo $this->_tpl_vars['rec']['BIR2']; ?>
</td>
			<td class="colnum espacioDer der" style="text-align:center;">RENTA</td>
			<!--<td style="text-align:center; width:75pt;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['TIR2'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>-->
			<td class="colnum espacioDer der" style="text-align:center;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['PIR2'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
 %</td>
			<td class="colnum espacioDer der" style="text-align:center;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['MIR2'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			</tr>
		      <?php endif; ?>
		      <?php if (( $this->_tpl_vars['rec']['MIR3'] > 0 )): ?>
		      <tr style="white-space:nowrap;height:0.7cm;">
		      <td class="colnum der" style="text-align:center; "><?php echo $this->_tpl_vars['rec']['PER']; ?>
</td>
			<td nowrap class="coldata espacioIzq" style="text-align:center; "><?php echo $this->_tpl_vars['rec']['BIR3']; ?>
</td>
			<td class="colnum espacioDer der" style="text-align:center;">RENTA</td>
			<!--<td style="text-align:center; width:75pt;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['TIR3'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>-->
			<td class="colnum espacioDer der" style="text-align:center;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['PIR3'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
 %</td>
			<td class="colnum espacioDer der" style="text-align:center;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['MIR3'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
		      </tr>
		      <?php endif; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
 </table>
</div>
    <!-- ********************************** SUMATORIA ***********************************-->  
  <!-- <table border=0 cellspacing=0 style="table-layout:fixed; padding-left: 0px; border:#FFFFFF solid 1px; width:800px; height:'8.5cm'" >    
    <tr style="white-space:nowrap">
        <td class ="resalta" style="width:720pt"><strong></strong></td>
	<?php $this->assign('TotalRet', $this->_tpl_vars['rec']['MIR3']+$this->_tpl_vars['rec']['MIR2']+$this->_tpl_vars['rec']['MIR']+$this->_tpl_vars['rec']['MIS']+$this->_tpl_vars['rec']['MIB']); ?>
        	<td class="colnum espacioDer der" style="text-align:center; width:200pt;"><?php echo ((is_array($_tmp=$this->_tpl_vars['TotalRet'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
    </tr>
  </table> -->
   
    
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</div>
</body>