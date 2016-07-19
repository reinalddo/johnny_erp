<?php /* Smarty version 2.6.18, created on 2009-11-05 16:42:08
         compiled from CoTrTr_productoresdet.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'CoTrTr_productoresdet.tpl', 17, false),array('block', 'report_header', 'CoTrTr_productoresdet.tpl', 18, false),array('block', 'report_detail', 'CoTrTr_productoresdet.tpl', 44, false),array('block', 'report_footer', 'CoTrTr_productoresdet.tpl', 59, false),array('modifier', 'date_format', 'CoTrTr_productoresdet.tpl', 20, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Tarjas por Vapor, marca:
	  Tabla de doble entrada, en vertcal los productores, agrupados por zona; en la horizontal las marcas agrupadas por producto -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Gina Franco" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="print" href="../css/AAA_tablas_print.css" title="CSS para imprimir" />
  
    <title>Detalle de Productores</title>
  
</head>
<body id="top" style="font-family:'Arial'">
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','groups' => 'vau_descripcion','resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <hr/>
    <div style="float:right;font-size:0.8em;">Fecha Imp.: <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
</div>
    <div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong><?php echo $_SESSION['g_empr']; ?>
</strong><br>
        PRODUCTORES<br>
        <?php echo $this->_tpl_vars['subtitulo']; ?>

    </p>
    </div>
    <table border=1 cellspacing=0 >
	<tr style="font-weight:bold; text-align:center; vertical-align:middle; text-transform:uppercase;">
	  <td class="headerrow"><strong>Codigo</strong></td>
	  <td class="headerrow"><strong>Productor</strong></td>
	  <td class="headerrow"><strong>Zona de Corte</strong></td>
	  <td class="headerrow"><strong>Banco</strong></td>
	  <td class="headerrow"><strong>NUMERO CTA CTE</strong></td>
	  <td class="headerrow"><strong>NUM. INSCR. MAGAP</strong></td>
	  <td class="headerrow"><strong>TIPO DE PAGO (CH,TR)</strong></td>
	  <td class="headerrow"><strong>ZONA DE PAGO</strong></td>
	  <td class="headerrow"><strong>BENEF. ALTERNO</strong></td>
	  <td class="headerrow"><strong>TIPO DE CUENTA (A, C)</strong></td>
	</tr>
        
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr>
        <td class="colnum "><?php echo $this->_tpl_vars['rec']['vau_codauxiliar']; ?>
</td>  
	<td nowrap><?php echo $this->_tpl_vars['rec']['vau_descripcion']; ?>
</td>
        <td class="colnum "><?php echo $this->_tpl_vars['rec']['Zona_Corte']; ?>
</td>        
        <td class="colnum "><?php echo $this->_tpl_vars['rec']['BANCO']; ?>
</td>
        <td class="colnum "><?php echo $this->_tpl_vars['rec']['NUMERO_CTA_CTE']; ?>
</td>
	<td class="colnum "><?php echo $this->_tpl_vars['rec']['NUM_INSCR_MAGAP']; ?>
</td>
	<td class="colnum "><?php echo $this->_tpl_vars['rec']['TIPO_PAGO']; ?>
</td>
	<td class="colnum "><?php echo $this->_tpl_vars['rec']['ZONA_PAGO']; ?>
</td>
	<td class="colnum "><?php echo $this->_tpl_vars['rec']['BENEF_ALTERNO']; ?>
</td>
        <td class="colnum "><?php echo $this->_tpl_vars['rec']['TIPO_CUENTA']; ?>
</td>
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    
    </table>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>