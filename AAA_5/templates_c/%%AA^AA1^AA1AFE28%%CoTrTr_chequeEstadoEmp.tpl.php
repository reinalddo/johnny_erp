<?php /* Smarty version 2.6.18, created on 2009-07-15 10:59:13
         compiled from CoTrTr_chequeEstadoEmp.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'CoTrTr_chequeEstadoEmp.tpl', 24, false),array('block', 'report_header', 'CoTrTr_chequeEstadoEmp.tpl', 27, false),array('block', 'report_detail', 'CoTrTr_chequeEstadoEmp.tpl', 80, false),array('block', 'report_footer', 'CoTrTr_chequeEstadoEmp.tpl', 106, false),array('modifier', 'date_format', 'CoTrTr_chequeEstadoEmp.tpl', 31, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Tarjas por Vapor, marca:
	  Tabla de doble entrada, en vertcal los productores, agrupados por zona; en la horizontal las marcas agrupadas por producto -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Gina Franco" />
  <?php echo '
  <style type="text/css">
        .tot{background-color:#D9D9D9;}
	.subtotal{font-style:italic; font-weight:bold;padding-top:7px;}
	.total{font-size:12px; font-weight:bold;margin-top:20px; padding-top:20px;}
	.cabecera{font-weight:bold; text-align:center; vertical-align:middle; border-bottom: 1px dotted red;padding:8px !important;}
    </style>
  '; ?>

  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>ESTADO DE CHEQUES</title>
  
</head>

<body id="top" style="font-family:'Arial'">
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','groups' => "banco, empresa",'resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php $this->assign('acum', 0); ?>
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
        ESTADO DE CHEQUES POR EMPRESA<br>
        <?php echo $this->_tpl_vars['subtitulo']; ?>
<br>
        INFORMACION EN USD
    </p>
    </div>
    <table border=1 cellspacing=0 >
	
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>


<?php $this->_tag_stack[] = array('report_header', array('group' => 'banco')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr style="margin-top:20px;margin-bottom:20px;padding-bottom:20px;">
        <td colspan=9 style="padding-top:20px; text-align:center; font-size:14px;"><strong>BANCO: </strong><?php echo $this->_tpl_vars['rec']['banco']; ?>
</td>
    </tr>
    <tr class="cabecera">
        <td class="cabecera"></td>
        <td class="cabecera"></td>
        <td class="cabecera" colspan=4>Control de Cheques</td>
        <td class="cabecera" colspan=3>Estado de Banco</td>
    </tr>
    <tr class="cabecera">
            <td class="cabecera">EMPRESA</td>
            <?php $_from = $this->_tpl_vars['agCab']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['curr_id']):
?>
                <!--id: <?php echo $this->_tpl_vars['curr_id']; ?>
<br />-->
                <td class="cabecera"><strong><?php echo $this->_tpl_vars['curr_id']; ?>
</strong></td>
                            <?php endforeach; endif; unset($_from); ?>
        </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr>
        <td><?php echo $this->_tpl_vars['rec']['empresa']; ?>
</td>
        <td class="headerrow" style='text-align:center;'><?php echo $this->_tpl_vars['rec']['NA']; ?>
</td>
        <td class="headerrow" style='text-align:center;'><?php echo $this->_tpl_vars['rec']['Emitido']; ?>
</td>
        <td class="headerrow" style='text-align:center;'><?php echo $this->_tpl_vars['rec']['Confirmado']; ?>
</td>
        <td class="headerrow" style='text-align:center;'><?php echo $this->_tpl_vars['rec']['Reconfirmado']; ?>
</td>
        <td class="headerrow" style='text-align:center;'><?php echo $this->_tpl_vars['rec']['Emitido']-$this->_tpl_vars['rec']['Confirmado']+$this->_tpl_vars['rec']['Reconfirmado']; ?>
</td>
        <td class="headerrow" style='text-align:center;'><?php echo $this->_tpl_vars['rec']['Confirmado']; ?>
</td>
        <td class="headerrow" style='text-align:center;'><?php echo $this->_tpl_vars['rec']['Pagado']; ?>
</td>
        <td class="headerrow" style='text-align:center;'><?php echo $this->_tpl_vars['rec']['Confirmado']-$this->_tpl_vars['rec']['Pagado']; ?>
</td>
            </tr>
    
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array('group' => 'banco')); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr class="subtotal">
        <td class="subtotal">SUBTOTAL</td>
        <td class="subtotal" style='text-align:center;'><?php echo $this->_tpl_vars['sum']['NA']; ?>
</td>
        <td class="subtotal" style='text-align:center;'><?php echo $this->_tpl_vars['sum']['Emitido']; ?>
</td>
        <td class="subtotal" style='text-align:center;'><?php echo $this->_tpl_vars['sum']['Confirmado']; ?>
</td>
        <td class="subtotal" style='text-align:center;'><?php echo $this->_tpl_vars['sum']['Reconfirmado']; ?>
</td>
        <td class="subtotal" style='text-align:center;'><?php echo $this->_tpl_vars['sum']['Emitido']-$this->_tpl_vars['sum']['Confirmado']+$this->_tpl_vars['sum']['Reconfirmado']; ?>
</td>
        <td class="subtotal" style='text-align:center;'><?php echo $this->_tpl_vars['sum']['Confirmado']; ?>
</td>
        <td class="subtotal" style='text-align:center;'><?php echo $this->_tpl_vars['sum']['Pagado']; ?>
</td>
        <td class="subtotal" style='text-align:center;'><?php echo $this->_tpl_vars['sum']['Confirmado']-$this->_tpl_vars['sum']['Pagado']; ?>
</td>
            </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr class="total">
        <td class="total">TOTAL</td>
        <td class="total" style='text-align:center;'><?php echo $this->_tpl_vars['sum']['NA']; ?>
</td>
        <td class="total" style='text-align:center;'><?php echo $this->_tpl_vars['sum']['Emitido']; ?>
</td>
        <td class="total" style='text-align:center;'><?php echo $this->_tpl_vars['sum']['Confirmado']; ?>
</td>
        <td class="total" style='text-align:center;'><?php echo $this->_tpl_vars['sum']['Reconfirmado']; ?>
</td>
        <td class="total" style='text-align:center;'><?php echo $this->_tpl_vars['sum']['Emitido']-$this->_tpl_vars['sum']['Confirmado']+$this->_tpl_vars['sum']['Reconfirmado']; ?>
</td>
        <td class="total" style='text-align:center;'><?php echo $this->_tpl_vars['sum']['Confirmado']; ?>
</td>
        <td class="total" style='text-align:center;'><?php echo $this->_tpl_vars['sum']['Pagado']; ?>
</td>
        <td class="total" style='text-align:center;'><?php echo $this->_tpl_vars['sum']['Confirmado']-$this->_tpl_vars['sum']['Pagado']; ?>
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