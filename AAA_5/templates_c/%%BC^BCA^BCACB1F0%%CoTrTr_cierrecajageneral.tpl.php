<?php /* Smarty version 2.6.18, created on 2009-07-21 09:25:47
         compiled from CoTrTr_cierrecajageneral.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'CoTrTr_cierrecajageneral.tpl', 99, false),array('block', 'report_header', 'CoTrTr_cierrecajageneral.tpl', 102, false),array('block', 'report_detail', 'CoTrTr_cierrecajageneral.tpl', 137, false),array('block', 'report_footer', 'CoTrTr_cierrecajageneral.tpl', 164, false),array('modifier', 'date_format', 'CoTrTr_cierrecajageneral.tpl', 106, false),array('modifier', 'default', 'CoTrTr_cierrecajageneral.tpl', 144, false),array('modifier', 'number_format', 'CoTrTr_cierrecajageneral.tpl', 155, false),)), $this); ?>
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
    </style>
    <script type="text/javascript">
      /*function autofitIframe(id){
	if (!window.opera && !document.mimeType && document.all && document.getElementById){
	  parent.document.getElementById(id).style.height=this.document.body.offsetHeight+"px";
	}
	else if(document.getElementById) {
	  parent.document.getElementById(id).style.height=this.document.body.scrollHeight+"px"
	}
      }*/
    </script>
    <script type="text/javascript">

	/***********************************************
	* IFrame SSI script II- © Dynamic Drive DHTML code library (http://www.dynamicdrive.com)
	* Visit DynamicDrive.com for hundreds of original DHTML scripts
	* This notice must stay intact for legal use
	***********************************************/
	
	//Input the IDs of the IFRAMES you wish to dynamically resize to match its content height:
	//Separate each ID with a comma. Examples: ["myframe1", "myframe2"] or ["myframe"] or [] for none:
	var iframeids=["fraCheques"]
	
	//Should script hide iframe from browsers that don\'t support this script (non IE5+/NS6+ browsers. Recommended):
	var iframehide="yes"
	
	var getFFVersion=navigator.userAgent.substring(navigator.userAgent.indexOf("Firefox")).split("/")[1]
	var FFextraHeight=parseFloat(getFFVersion)>=0.1? 16 : 0 //extra height in px to add to iframe in FireFox 1.0+ browsers

	function resizeCaller() {
	  var dyniframe=new Array()
	  for (i=0; i<iframeids.length; i++){
	    if (document.getElementById)
	      resizeIframe(iframeids[i])
	    //reveal iframe for lower end browsers? (see var above):
	    if ((document.all || document.getElementById) && iframehide=="no"){
	      var tempobj=document.all? document.all[iframeids[i]] : document.getElementById(iframeids[i])
	      tempobj.style.display="block"
	    }
	  }
	}

	function resizeIframe(frameid){
	  var currentfr=document.getElementById(frameid)
	  if (currentfr && !window.opera){
	    currentfr.style.display="block"
	    if (currentfr.contentDocument && currentfr.contentDocument.body.offsetHeight) //ns6 syntax
	      currentfr.height = currentfr.contentDocument.body.offsetHeight+FFextraHeight; 
	    else if (currentfr.Document && currentfr.Document.body.scrollHeight) //ie5+ syntax
	      currentfr.height = currentfr.Document.body.scrollHeight;
	    if (currentfr.addEventListener)
	      currentfr.addEventListener("load", readjustIframe, false)
	    else if (currentfr.attachEvent){
	      currentfr.detachEvent("onload", readjustIframe) // Bug fix line
	      currentfr.attachEvent("onload", readjustIframe)
	    }
	  }
	}

	function readjustIframe(loadevt) {
	  var crossevt=(window.event)? event : loadevt
	  var iframeroot=(crossevt.currentTarget)? crossevt.currentTarget : crossevt.srcElement
	  if (iframeroot)
	    resizeIframe(iframeroot.id);
	}
	  
	function loadintoIframe(iframeid, url){
	    if (document.getElementById)
	      document.getElementById(iframeid).src=url
	}

	if (window.addEventListener)
	  window.addEventListener("load", resizeCaller, false)
	else if (window.attachEvent)
	  window.attachEvent("onload", resizeCaller)
	else
	  window.onload=resizeCaller

</script>
  '; ?>

  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>CONSOLIDADO CIERRE DE CAJA</title>
  
</head>

<body id="top" style="font-family:'Arial'" onLoad="autofitIframe('fraCheques')">
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','groups' => 'empresa','resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
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
        CONSOLIDADO CIERRE DE CAJA<br>
        <?php echo $this->_tpl_vars['subtitulo']; ?>

    </p>
    </div>
    <table border=1 cellspacing=0 >
	<tr style="font-weight:bold; text-align:center; vertical-align:middle;">
	  <td></td>
	  <td colspan=<?php echo $this->_tpl_vars['agTotCabCaja']; ?>
>CAJA</td>
	  <td colspan=<?php echo $this->_tpl_vars['agTotCabBancos']; ?>
>BANCOS</td>
	  <td></td>
	</tr>
        <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
            <td class="headerrow">CAJA/BANCOS</td>
                                           <!-- <td class="headerrow"><strong><?php echo $this->_tpl_vars['con']['1']; ?>
</strong></td>-->
                        <?php $_from = $this->_tpl_vars['agCab']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['curr_id']):
?>
                <!--id: <?php echo $this->_tpl_vars['curr_id']; ?>
<br />-->
                <td class="headerrow"><strong><?php echo $this->_tpl_vars['curr_id']; ?>
</strong></td>
            <?php endforeach; endif; unset($_from); ?>
        </tr>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>


<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr>
        <td><?php echo $this->_tpl_vars['rec']['empresa']; ?>
</td>
        
        <?php $_from = $this->_tpl_vars['agCodCab']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['curr_id']):
?>
            <?php $this->assign('col', $this->_tpl_vars['curr_id']); ?>
            <?php $this->assign('empr', $this->_tpl_vars['rec']['empresa']); ?>
            <?php $this->assign('sal', ((is_array($_tmp=@$this->_tpl_vars['agSaldos'][$this->_tpl_vars['empr']][$this->_tpl_vars['curr_id']])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))); ?>
            <?php if (( $this->_tpl_vars['sal'] == 0 )): ?>
              <td class="colnum "><?php echo 0; ?>
</td>
            <?php else: ?>
                
              <td class="colnum"
                    <?php if (( -1 == $this->_tpl_vars['curr_id'] || -2 == $this->_tpl_vars['curr_id'] )): ?>
                        style='font-weight:bold; font-style:italic;'
                    <?php elseif (( -3 == $this->_tpl_vars['curr_id'] )): ?>
                        style='font-weight:bold;'
                    <?php endif; ?>
                  "><?php echo ((is_array($_tmp=$this->_tpl_vars['sal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
            <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?>
        
        
    </tr>
    
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr style="font-weight:bold;">
        <td style="font-style:italic;">Total</td>
        <!--<td class="colnum tot"><?php echo 0; ?>
</td>-->
        <?php $_from = $this->_tpl_vars['agCodCab']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['curr_id']):
?>
            <?php $this->assign('empr', -5); ?>
            <?php $this->assign('sal', ((is_array($_tmp=@$this->_tpl_vars['agSaldos'][$this->_tpl_vars['empr']][$this->_tpl_vars['curr_id']])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))); ?>
            <?php if (( $this->_tpl_vars['sal'] == 0 )): ?>
              <td class="colnum tot"><?php echo 0; ?>
</td>
            <?php else: ?>
              <td class="colnum tot"><?php echo ((is_array($_tmp=$this->_tpl_vars['sal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
            <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?>
    </tr>
    </table>
    <iframe id="fraCheques" frameborder="0" src="CoTrTr_chequeEstadoEmp.rpt.php?init=1&pOpc=&fIni=<?php echo $this->_tpl_vars['fecIni']; ?>
&fFin=<?php echo $this->_tpl_vars['fecFin']; ?>
" scrolling="no" marginwidth="0" marginheight="0" frameborder="0" vspace="0" hspace="0" style="overflow:visible; width:100%; display:none;"></iframe>
    <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>