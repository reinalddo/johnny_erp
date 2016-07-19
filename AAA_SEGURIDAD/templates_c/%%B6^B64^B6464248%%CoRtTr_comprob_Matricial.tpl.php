<?php /* Smarty version 2.6.18, created on 2015-06-18 10:37:01
         compiled from CoRtTr_comprob_Matricial.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'CoRtTr_comprob_Matricial.tpl', 44, false),array('block', 'report_header', 'CoRtTr_comprob_Matricial.tpl', 45, false),array('block', 'report_detail', 'CoRtTr_comprob_Matricial.tpl', 100, false),array('block', 'report_footer', 'CoRtTr_comprob_Matricial.tpl', 163, false),array('modifier', 'number_format', 'CoRtTr_comprob_Matricial.tpl', 111, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Plantilla que aplica a la impresion del comprobante de retencion en matricial -->

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="root" />
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
	@page retencion {size:21cm 14.5cm;}
	pagina {page: retencion;}
    </style>
  '; ?>

  
  <!--<link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />-->
  <!--<link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />-->
  <!--<link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_tablas_print.css" title="CSS para imprimir" />-->
  
  <title>RETENCION</title>
  
</head>
<body id="top" style="font-family:'Arial' !important; font-size:9pt; font-weight:bold " >
  <div id="pagina" style="margin-left:0pt; height:6cm">
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','groups' => 'TIB','resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <!--<hr/>-->
    <!--JVL    <div id = "cuerpo" style="height:5.5cm; border:#FFFFFF solid 1px; margin-top:95pt;">   JVL Original Aplesa **-->
    <div id = "cuerpo" style="height:5.0cm; border:#FFFFFF solid 1px; margin-top:105pt;">
    <!-- ********************************** C A B E C E R A ***********************************-->  
    <table border=0 cellspacing=0 style="table-layout:fixed; padding-left: 0px; border:#FFFFFF solid 1px; width:800px; height:'3.0cm'" >
     <tr>
	    <td colspan=1 class="resalta" nowrap style="padding-top:8pt;width:120pt;">&nbsp;</td>
	    <td colspan=1 class="" nowrap  style="width:400pt"><?php echo $this->_tpl_vars['rec']['NOM']; ?>
</td>
	    <td colspan=1 class="" style="width:220pt">&nbsp;</td>
	    <td colspan=1 class="" nowrap><?php echo $this->_tpl_vars['rec']['FEC']; ?>
</td>
     </tr>
     <tr>
	    <td colspan=1 class="resalta" nowrap style="padding-top:8pt;width:120pt;">&nbsp;</td>
	    <td colspan=1 class="" nowrap  style="width:400pt"><?php echo $this->_tpl_vars['rec']['RUC']; ?>
</td>
	    <td colspan=1 class="" style="width:220pt">&nbsp;</td>
	    <td colspan=1 class="" nowrap><?php echo $this->_tpl_vars['rec']['TIP']; ?>
</td>
     </tr>
     <tr>
	    <td colspan=1 class="resalta" nowrap style="padding-top:8pt;width:120pt;">&nbsp;</td>
	    <td colspan=1 class="" nowrap  style="width:400pt"><?php echo $this->_tpl_vars['rec']['Direcc']; ?>
</td>
	    <td colspan=1 class="" style="width:220pt">&nbsp;</td>
	    <td colspan=1 class="" nowrap><?php echo $this->_tpl_vars['rec']['FAC']; ?>
</td>
     </tr>
<!-- JVL    La siguiente FILA no existia       JVL Original Aplesa **-->

     <tr>
	    <td colspan=1 class="resalta" nowrap style="padding-top:8pt;width:120pt;">&nbsp;</td>
     </tr>
     <tr>
	    <td colspan=1 class="resalta" nowrap style="padding-top:8pt;width:120pt;">&nbsp;</td>
     </tr>

     <!-- <tr>
	    <td colspan=1 class="resalta" nowrap style="padding-top:8pt;width:120pt;">&nbsp;</td>
	    <td colspan=3 class="" nowrap style="width:800pt;"><?php echo $this->_tpl_vars['rec']['CONCEP']; ?>
</td>
        </tr> -->
    </table>
      <!-- ********************************** DETALLE ***********************************-->  
	
      <table border=0  cellspacing=0 style="table-layout:fixed; padding-left: 0px; border:#FFFFFF solid 1px; width:800px; height:'0.5cm'" >
<!-- JVL          <tr style="font-weight:bold; text-align:center; vertical-align:middle; text-transform:uppercase;">
	  <td class="headerrow cabTabla resalta" style="text-align:right; width:170pt;">&nbsp;<!--<strong>EJERCICIO FISCAL</strong>--></td>
<!-- JVL	  <td class="headerrow cabTabla resalta" style="text-align:center; width:190pt;">&nbsp;<!--<strong>BASE IMPONIBLE</strong>--></td>
<!-- JVL	  <td class="headerrow cabTabla resalta" style="text-align:center; width:120pt;">&nbsp;<!--<strong>IMPUESTO</strong>--></td>
<!-- JVL	  <td class="headerrow cabTabla resalta" style="text-align:center; width:140pt;">&nbsp;<!--<strong>COD IMPUESTO</strong>--></td>
<!-- JVL	  <td class="headerrow cabTabla resalta" style="text-align:center; width:150pt;">&nbsp;<!--<strong>% RETENCION</strong>--></td>
<!-- JVL	  <td class="headerrow cabTabla resalta" style="width:140pt;">&nbsp;<!--<strong>VALOR RETENIDO</strong>--></td>
<!-- JVL	</tr> 
  JVL Original Para cabecera de RETENCION se debe habilitar cuando se dibuja toda la retencion **-->

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<!--JVL    En el detalle del Reporte todo era align: center excepto columna VALOR RETENIDO  OJO JVL Original Aplesa **-->
<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<!-- JVL  LINEA BORRADA PARA DUREXPORTA   <tr>
	    <td colspan=1 class="resalta" nowrap style="padding-top:8pt;width:120pt;">&nbsp;</td>
     </tr>
**-->
        <?php if (( $this->_tpl_vars['rec']['MIB'] > 0 )): ?>
		      <tr style="white-space:nowrap">
			<td class="colnum der" style="text-align:right; width:170pt;"> <?php echo $this->_tpl_vars['rec']['PER']; ?>
</td>
			<td nowrap class="coldata espacioIzq" style="text-align:center; width:190pt;"><?php echo $this->_tpl_vars['rec']['BIB']; ?>
</td>
			<td class="colnum espacioDer der" style="text-align:right; width:100pt;">IVA</td>
			<td style="text-align:center; width:140pt;"><?php echo $this->_tpl_vars['rec']['TIB2']; ?>
</td>
			<td class="colnum espacioDer der" style="text-align:center; width:100pt;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['PIB'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
 %</td>
			<td class="colnum espacioDer der" style="text-align:right; width:100pt;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['MIB'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
		      </tr>
      	<?php endif; ?>
			
		      <?php if (( $this->_tpl_vars['rec']['MIS'] > 0 )): ?>
		      <tr style="white-space:nowrap">
		      <td class="colnum der" style="text-align:right; width:170pt;"><?php echo $this->_tpl_vars['rec']['PER']; ?>
</td>
			<td nowrap class="coldata espacioIzq" style="text-align:center; width:190pt;"><?php echo $this->_tpl_vars['rec']['BIS']; ?>
</td>
			<td class="colnum espacioDer der" style="text-align:right; width:100pt;">IVA</td>
			<td style="text-align:center; width:140pt;"><?php echo $this->_tpl_vars['rec']['TIS2']; ?>
</td>
			<td class="colnum espacioDer der" style="text-align:center; width:100pt;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['PIS'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
 %</td>
			<td class="colnum espacioDer der" style="text-align:right; width:100pt;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['MIS'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			</tr>
		      <?php endif; ?>
			
		      <?php if (( $this->_tpl_vars['rec']['MIR'] > 0 )): ?>
		      <tr style="white-space:nowrap">
		      <td class="colnum der" style="text-align:right; width:170pt;"><?php echo $this->_tpl_vars['rec']['PER']; ?>
</td>
			<td nowrap class="coldata espacioIzq" style="text-align:center; width:190pt;"><?php echo $this->_tpl_vars['rec']['BIR']; ?>
</td>
			<td class="colnum espacioDer der" style="text-align:right; width:100pt;">RENTA</td>
			<td style="text-align:center; width:140pt;"><?php echo $this->_tpl_vars['rec']['TIR']; ?>
</td>
			<td class="colnum espacioDer der" style="text-align:center; width:100pt;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['PIR'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
 %</td>
			<td class="colnum espacioDer der" style="text-align:right; width:100pt;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['MIR'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			</tr>
		      <?php endif; ?>
			
		      <?php if (( $this->_tpl_vars['rec']['MIR2'] > 0 )): ?>
		      <tr style="white-space:nowrap">
		      <td class="colnum der" style="text-align:right; width:170pt;"><?php echo $this->_tpl_vars['rec']['PER']; ?>
</td>
			<td nowrap class="coldata espacioIzq" style="text-align:center; width:190pt;"><?php echo $this->_tpl_vars['rec']['BIR2']; ?>
</td>
			<td class="colnum espacioDer der" style="text-align:right; width:100pt;">RENTA</td>
			<td style="text-align:center; width:140pt;"><?php echo $this->_tpl_vars['rec']['TIR2']; ?>
</td>
			<td class="colnum espacioDer der" style="text-align:center; width:100pt;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['PIR2'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
 %</td>
			<td class="colnum espacioDer der" style="text-align:right; width:100pt;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['MIR2'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			</tr>
		      <?php endif; ?>
		      <?php if (( $this->_tpl_vars['rec']['MIR3'] > 0 )): ?>
		      <tr style="white-space:nowrap">
		      <td class="colnum der" style="text-align:right; width:170pt;"><?php echo $this->_tpl_vars['rec']['PER']; ?>
</td>
			<td nowrap class="coldata espacioIzq" style="text-align:center; width:190pt;"><?php echo $this->_tpl_vars['rec']['BIR3']; ?>
</td>
			<td class="colnum espacioDer der" style="text-align:right; width:100pt;">RENTA</td>
			<td style="text-align:center; width:140pt;"><?php echo $this->_tpl_vars['rec']['TIR3']; ?>
</td>
			<td class="colnum espacioDer der" style="text-align:center; width:100pt;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['PIR3'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
 %</td>
			<td class="colnum espacioDer der" style="text-align:right; width:100pt;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['MIR3'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
		      </tr>
		      <?php endif; ?>
<!-- JVL durexporta     <tr>
	    <td colspan=1 class="resalta" nowrap style="padding-top:8pt;width:120pt;">&nbsp;</td>
     </tr>   **-->
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
 </table>
</div>
    <!-- ********************************** SUMATORIA ***********************************-->  
  <table border=0 cellspacing=0 style="table-layout:fixed; padding-left: 0px; border:#FFFFFF solid 1px; width:800px; height:'0.5cm'" >    
    <tr style="white-space:nowrap">
        <td class ="resalta" style="width:750pt"><strong></strong></td>
        <?php $this->assign('TotalRet', $this->_tpl_vars['rec']['MIR3']+$this->_tpl_vars['rec']['MIR2']+$this->_tpl_vars['rec']['MIR']+$this->_tpl_vars['rec']['MIS']+$this->_tpl_vars['rec']['MIB']); ?>
      	<td class="colnum espacioDer der" style="text-align:right; width:100pt;"><?php echo ((is_array($_tmp=$this->_tpl_vars['TotalRet'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
    </tr>
  </table>
   
    
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</div>
</body>