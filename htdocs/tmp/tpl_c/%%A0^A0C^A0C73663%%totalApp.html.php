<?php /* Smarty version 2.6.22, created on 2016-03-31 16:36:21
         compiled from totalApp.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'strstr', 'totalApp.html', 20, false),array('modifier', 'strlen', 'totalApp.html', 50, false),array('modifier', 'mb_strlen', 'totalApp.html', 52, false),array('modifier', 'mb_substr', 'totalApp.html', 52, false),)), $this); ?>
﻿<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "./include/old_trident_header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div class="mainContent">
	<div class="w1200">
	
		<form>
		<div class="filterBar underline">
			
				<ul class="index_tabnav lt">
					<li>
						<a href="javascript:void(0)" class="cur iAppGroup" data-group="0">全部</a>
					</li>
					
					<?php $_from = $this->_tpl_vars['appKind']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
					<li>
						<a href="javascript:void(0)" class="iAppGroup" data-group="<?php echo $this->_tpl_vars['key']; ?>
"><?php echo $this->_tpl_vars['item']; ?>
</a>
					</li>
					<?php endforeach; endif; unset($_from); ?>
					
				</ul>
			<?php if (((is_array($_tmp=$this->_tpl_vars['aclGroup']['modules'])) ? $this->_run_mod_handler('strstr', true, $_tmp, '2027') : strstr($_tmp, '2027')) !== false): ?><p class="rt"><a href='index.php?module=totalApp&action=totalDevAppList'><i class="icon-user"></i>　研发者 </a></p><?php endif; ?>
			<input type="hidden" name="iAppGroup">
		</div>
	
	
	
		<div class="filterBar">
		
			<div class="filer">按试用习惯排序</div>

			<div class="rt">

				
				<!--
				<div class="search_div">
					<input id="search" type="text" name="searchV" value="" placeholder="应用名称,常见问题" class="input-text lt" maxlength="">
					<a id="searchAction" target="search" data-rel="showData" class="button-search lt"><i class="icon-search"></i></a>
				</div>-->
				

			</div>
		
		</div>
		<a target="search" data-rel="showData" data-form="pageForm" style="display:none;" id='searchClick'> 搜 索 </a>
		</form>
		<div id="showData">
		<ul class="list_v">

			<?php $_from = $this->_tpl_vars['putData']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['foolist'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foolist']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['foolist']['iteration']++;
?>
				<li id='appList_<?php echo $this->_tpl_vars['item']['iAppId']; ?>
'>
					<?php if ($this->_tpl_vars['item']['iLogoApply'] == 2): ?><span class="devlogo icon_index bg<?php echo $this->_tpl_vars['item']['iLogoColorIndex']; ?>
  <?php if (strlen($this->_tpl_vars['item']['sAppLogoTxt']) == 12): ?>pr<?php endif; ?>"><?php if (strlen($this->_tpl_vars['item']['sAppLogoTxt']) == 12): ?><span class="fontsize4"><?php echo $this->_tpl_vars['item']['sAppLogoTxt']; ?>
</span><?php else: ?><?php echo $this->_tpl_vars['item']['sAppLogoTxt']; ?>
<?php endif; ?></span><?php elseif ($this->_tpl_vars['item']['iLogoApply'] == 1): ?><img class='devlogo' src="<?php echo $this->_tpl_vars['item']['sAppLogo']; ?>
" style='border-radius: 20px;'><?php else: ?><span class="icon_index bg<?php echo ($this->_foreach['foolist']['iteration']-1)+1; ?>
">默认</span><?php endif; ?>
					
					<h2><?php if (mb_strlen($this->_tpl_vars['item']['sAppName']) > 9): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['sAppName'])) ? $this->_run_mod_handler('mb_substr', true, $_tmp, 0, 8, 'utf-8') : mb_substr($_tmp, 0, 8, 'utf-8')); ?>
…<?php else: ?><?php echo $this->_tpl_vars['item']['sAppName']; ?>
<?php endif; ?></h2>

					<p class="contact_p">联系人：<a class="text100 contacts" href="rtxlite.hotlink://UserName=<?php echo $this->_tpl_vars['item']['sConnect']; ?>
"><?php echo $this->_tpl_vars['item']['sConnectRTX']; ?>
</a>
					<!--<a href="rtxlite.hotlink://UserName=<?php echo $this->_tpl_vars['item']['sConnectRTX']; ?>
"><i class="icon-book"></i></a>-->
					</p>
					<div class="textcont"><?php if (mb_strlen($this->_tpl_vars['item']['sAppIntroduction']) > 40): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['sAppIntroduction'])) ? $this->_run_mod_handler('mb_substr', true, $_tmp, 0, 40, 'utf-8') : mb_substr($_tmp, 0, 40, 'utf-8')); ?>
…<?php else: ?><?php echo $this->_tpl_vars['item']['sAppIntroduction']; ?>
<?php endif; ?></div>
					<div class="bottmdiv">
					<!--<span class="lt">已接入业务：<?php if (strlen($this->_tpl_vars['item']['sAppOnlyService']) > 0): ?>专属应用<?php else: ?><?php echo $this->_tpl_vars['item']['iBusinessNum']; ?>
个<?php endif; ?></span>-->
					<?php if (mb_strlen($this->_tpl_vars['userName']) > 0): ?>
					<a class="rt authState" style='cursor:pointer;<?php if (( $this->_tpl_vars['item']['sUserRtx'] == '' || $this->_tpl_vars['item']['iAuthState'] == 0 ) && $this->_tpl_vars['item']['sRoleIDList']): ?>display:block;<?php endif; ?>' data-confirm=0 target='ajax' data-aftfunc='showDialog' data-aftparams='<?php echo '{'; ?>
"iAppId":<?php echo $this->_tpl_vars['item']['iAppId']; ?>
,"appName":"<?php echo $this->_tpl_vars['item']['sAppName']; ?>
"<?php echo '}'; ?>
'  href='index.php?module=totalApp&action=totalAppList&func=applyAuthCheck&iAppId=<?php echo $this->_tpl_vars['item']['iAppId']; ?>
&PlatID=<?php echo $this->_tpl_vars['item']['iPlatID']; ?>
&iIsPlatToService=<?php echo $this->_tpl_vars['item']['iIsPlatToService']; ?>
'><i class="icon-edit"></i>申请开通>></a>
					<a class="rt authState" href="javascript:void(0)" <?php if ($this->_tpl_vars['item']['iAuthState'] == 1): ?>style='display:block;'<?php endif; ?>><i class="icon-edit"></i>审核中……</a>

					<a class="rt authState" href="<?php echo $this->_tpl_vars['item']['sAppUrl']; ?>
" <?php if ($this->_tpl_vars['item']['iAuthState'] == 2 || ! $this->_tpl_vars['item']['sRoleIDList']): ?>style='display:block;'<?php endif; ?> target="_blank"><i class="icon-edit"></i>立即进入>></a>

					<?php else: ?>
					<a class="rt" href="index.php?module=common_until&action=no_acl&func=login"><i class="icon-edit"></i>请登录>></a>
					<?php endif; ?>

<!--	

					<?php if ($this->_tpl_vars['item']['sUserRtx'] == ''): ?>
						<a class="rt" href="javascript:showDialog($(this).parents('li'),'<?php echo $this->_tpl_vars['item']['sAppName']; ?>
');" style='cursor:pointer;'><i class="icon-edit"></i>　申请开通>></a>
					<?php elseif ($this->_tpl_vars['item']['iAuthState'] == 0): ?>
						<a class="rt" href="javascript:void(0)"><i class="icon-edit"></i>　审核中……</a>
					<?php else: ?>
						<a class="rt" href=""><i class="icon-edit"></i>　立即进入>></a>
					<?php endif; ?>-->
					</div>
					<div style='display:none' class='diaOption'>
						<option selected="" value="0">请选择你申请的权限角色</option>
						<?php $_from = $this->_tpl_vars['item']['sRoleIDList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item1']):
?>
							<option value="<?php echo $this->_tpl_vars['key']; ?>
"><?php echo $this->_tpl_vars['item1']; ?>
</option>
						<?php endforeach; endif; unset($_from); ?>
					</div>
					<input type='hidden' value='index.php?module=totalApp&action=totalAppList&func=applyAuth&iAppId=<?php echo $this->_tpl_vars['item']['iAppId']; ?>
&PlatID=<?php echo $this->_tpl_vars['item']['iPlatID']; ?>
&iIsPlatToService=<?php echo $this->_tpl_vars['item']['iIsPlatToService']; ?>
' class='devFormUrl'>
					<input type='hidden' value='<?php echo $this->_tpl_vars['item']['iLoginByQQ']; ?>
' id='iLoginByQQ'>
					<?php if ($this->_tpl_vars['item']['iHot'] == 1): ?><div class="Qr_code hot"></div><?php endif; ?>
				</li>
			<?php endforeach; endif; unset($_from); ?>
		</ul>
		<div id='page'  class="page pagination" data-type='pagination' style="text-align: center;" data-action="index.php?module=totalApp&action=totalAppList" data-rel="showData" data-numshow='5' data-total="<?php echo $this->_tpl_vars['total']; ?>
" data-perpage="<?php echo $this->_tpl_vars['pageSize']; ?>
" data-current="<?php echo $this->_tpl_vars['pageCur']; ?>
"></div>
		</div>
		</div>
	</div>
</div>
<div id="showMyDialog" style="display:none">
<div class="tips_system">
    <p class="title_tips">系统提示</p>
<form>
    <div class="tips_cont">
    	<div class="lt">
    		<!--<span class="icon_index bg10">LOL</span>-->
    		<p>LOL赛事中心管理端</p>
    	</div>
    	<div class="tips_right">
    		<p>
    			<label>权限角色 :</label>
				<select id="u238_input" name='sRoleID'></select>
    		</p>
    		<p>
    			<label>申请原因 :</label>
    			<input type="text" class="" data-is="not.empty" data-rd-title="请输入申请原因" name='authDesc'>
    		</p>
    		<p id="popIuin">
    			<label>绑定 QQ :</label>
    			<input type="text" class="" data-is="checkQQ" data-rd-title="请输入正确的QQ号" name='iUin'>
    		</p>

    	</div>
	
		<div class="btn">
			<a href="" class="tips_btn" target="subform" data-prefunc='beforeSubmit'>确定</a><a href="javascript:Trident.closeAll();" class="cancel">取消</a>
		</div>
       
    </div>
</form>
</div>
</div>
<div class="mobile_right">

<a class="hoverimg"
   data-tp-title="<img src='index.php?module=totalApp&action=newAppUpload&func=qr'><p class='line-height'>' 扫一扫 '</p><p>移动办公带你飞</p>"
   data-tp-position="bottom">
</a>

</div>
<?php echo '
<style>
	.authState {display: none;}

</style>

<script>

function showDialog(e,a){

		if(a.retCode==1){
			alert(a.retInfo+\'是否开通本系统权限\');
			return;
		}
		var iAppId = e.iAppId;
		var appName = e.appName;
		var diaDom = $(\'#appList_\'+iAppId);

		if(a.insert == 1){
			if(a.iAuthState == 2){
				$(\'#appList_\'+iAppId+\' .authState\').hide();
				$(\'#appList_\'+iAppId+\' .authState\').eq(2).show();
			}else{
				$(\'#appList_\'+iAppId+\' .authState\').hide();
				$(\'#appList_\'+iAppId+\' .authState\').eq(1).show();
			}
		}else{
			// var diaDom = dom.children(\'.devlogo\').clone();
			$(\'#showMyDialog .lt .devlogo\').remove();
			$(\'#showMyDialog .lt p\').before(diaDom.children(\'.devlogo\').clone());
			$(\'#showMyDialog .lt p\').html(appName);
			$(\'#u238_input\').html(diaDom.find(\'.diaOption\').html());
			if(diaDom.find(\'#iLoginByQQ\').val() != 1){
				$(\'#popIuin\').hide();
			}else{
				$(\'#popIuin\').show();
			}
			$(\'#showMyDialog a\').attr(\'href\',diaDom.find(\'.devFormUrl\').val());
	    	Trident.tpop(\'showMyDialog\');
		}


		
    }

//用于表单提交成功后的无刷新页面功能实现
function afterSubmit(e){
	if(e.retCode == 0){
		Trident.closeAll();
		if(e.iAuthState == 2){
		
			$(\'#appList_\'+e.iAppId+\' .authState\').hide();
			$(\'#appList_\'+e.iAppId+\' .authState\').eq(2).show();
		}else{

			$(\'#appList_\'+e.iAppId+\' .authState\').hide();
			$(\'#appList_\'+e.iAppId+\' .authState\').eq(1).show();
		}
		
	}
	
}

function beforeSubmit(e){
	if($(\'#u238_input\').val() == 0){
		Trident.tip(0,\'u238_input\',\'请选择你申请的权限角色\',\'top\');
		return false;
	}
	
}
//表单验证扩展
Trident.ready(function(){
    is =  is || {};
  
    is.checkQQ = function(e){
    	return true;
    	if(e.value.match(/^[0-9]*$/)){
    		return (e.value.length < e.min || e.value.length > e.max)? false : true;
    	}else{
    		return false;
    	}
    	
    }
    is.checkQQ.need = {min:5,max:11};

    is.selectNotNull = function(e) {
        return e.value==\'\' ? false : true;
    }
    is.selectNotNull.need = {};

    $(\'.iAppGroup\').click(function(){
    	$(\'input[name=iAppGroup]\').val($(this).attr(\'data-group\'));
    	$(this).addClass(\'cur\').parent(\'li\').siblings(\'li\').find(\'a\').removeClass(\'cur\');
    	$(\'#searchClick\').click();
    });
});
</script>
'; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "./include/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>




















