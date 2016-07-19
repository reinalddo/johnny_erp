<?php /* Smarty version 2.6.18, created on 2009-07-17 11:54:12
         compiled from InTrTr_listasimple.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'InTrTr_listasimple.tpl', 26, false),array('block', 'report_header', 'InTrTr_listasimple.tpl', 29, false),array('block', 'report_detail', 'InTrTr_listasimple.tpl', 69, false),array('block', 'report_footer', 'InTrTr_listasimple.tpl', 82, false),array('modifier', 'date_format', 'InTrTr_listasimple.tpl', 33, false),array('modifier', 'number_format', 'InTrTr_listasimple.tpl', 75, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Tarjas por Vapor, marca:
	  Tabla de doble entrada, en vertcal los productores, agrupados por zona; en la horizontal las marcas agrupadas por producto -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Gina Franco" />
  <?php echo '
  <style type="text/css">
        .num{text-align:right;}
	.subtitulo{font-size:10px;}
	.subtotal{font-style:italic; font-weight:bold;}
	.total{font-size:12px; font-weight:bold;margin-top:20px; padding-top:20px;}
        .espacio{margin-left:20px;};
        .espaciogrupo{padding:10px !important;}
    </style>
  '; ?>

  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>LISTA SIMPLE</title>
  
</head>

<body id="top" style="font-family:'Arial'">
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','groups' => "BODEG, RECEP, COMPR, SECUE",'resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>


<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <!--<input type="button" title="Envia por correo este reporte" value="Correo" onclick="fGenMail()"/>
    <input type="button" title="Presenta los datos Base de reporte para ser exportados a excel" value="Excel" onclick="fGenExcel()"/>-->
    <hr/>
    <!--<div style="font-size:0.8em; text-align:left; float:right;">Fecha Imp.: <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
</div>-->
    </br>
    <div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong></strong><br>
        <strong>DETALLE DE TRANSACCIONES DE INVENTARIO<strong><br>
        <span class="subtitulo"><?php echo $this->_tpl_vars['subtitulo']; ?>
</span>
    </p>
    </div>
    <?php $this->assign('cols', 9); ?>
    <table border=1 cellspacing=0 >
	

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_header', array('group' => 'BODEG')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr style="margin-top:20px;margin-bottom:20px;padding-bottom:20px;">
        <td colspan=<?php echo $this->_tpl_vars['cols']; ?>
 style="padding-top:20px; text-align:center; font-size:14px;"><strong><?php echo $this->_tpl_vars['rec']['BODEG']; ?>
</strong></td>
    </tr>
    <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
            <td class="headerrow">S</td>
            <td class="headerrow">COD.</td>
            <td class="headerrow">ITEM</td>
            <td class="headerrow">UNI</td>
            <td class="headerrow">CANT.</td>
            <td class="headerrow">COSTO</td>
            <td class="headerrow">VALOR</td>
        </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_header', array('group' => 'COMPR')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr  class="espaciogrupo">
        <td colspan=7 style="padding:10px !important;"><strong>COMPROB.</strong> <?php echo $this->_tpl_vars['rec']['TIPO']; ?>
 <?php echo $this->_tpl_vars['rec']['COMPR']; ?>
   <strong class="espacio">FECHA:</strong> <?php echo $this->_tpl_vars['rec']['FECHA']; ?>
 <strong class="espacio">A:</strong> <?php echo $this->_tpl_vars['rec']['RECEP']; ?>
 <strong class="espacio">S:</strong> <?php echo $this->_tpl_vars['rec']['REFOP']; ?>
</td>
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr>
        <td><?php echo $this->_tpl_vars['rec']['SECUE']; ?>
</td>
        <td><?php echo $this->_tpl_vars['rec']['CODIT']; ?>
</td>
        <td><?php echo $this->_tpl_vars['rec']['ITEM']; ?>
</td>
        <td><?php echo $this->_tpl_vars['rec']['UNIDA']; ?>
</td>
        <td class="num"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['CANTI'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <td class="num"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['COSTO'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <td class="num"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['VALOR'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>	
    </tr>
    
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array('group' => 'BODEG')); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr class="subtotal">
        <td colspan=4>SUBTOTAL</td>
        <td class="num"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['CANTI'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <td class="num"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['COSTO'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <td class="num"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['VALOR'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>        
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr class="total">
        <td class="total" colspan=4>SUMA GENERAL</td>
        <td class="total num"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['CANTI'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <td class="total num"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['COSTO'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <td class="total num"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['VALOR'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>       
    </tr>
    </table>
    <div style="font-size:0.7em; text-align:left; float:left;color:#000000; margin-top:20px;">
        <p style="line-height:0.5em;"><strong>Usuario: </strong><?php echo $_SESSION['g_user']; ?>
</p>
        <p style="line-height:0.5em;"><strong>Fecha Imp.: </strong><?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
</p>
	<p style="line-height:0.5em;"><?php echo $this->_tpl_vars['agArchivo']; ?>
</p>
    </div>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>