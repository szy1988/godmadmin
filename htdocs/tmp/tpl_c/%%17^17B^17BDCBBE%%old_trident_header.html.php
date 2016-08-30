<?php /* Smarty version 2.6.22, created on 2016-03-31 16:36:21
         compiled from ./include/old_trident_header.html */ ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>营销手册</title>
<link href="http://x.ied.com/static/css/main.css" rel="stylesheet" type="text/css" />
<link href="http://x.ied.com/static/css/font-awesome/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="http://x.ied.com/favicon.ico" />
<script type="text/javascript" src="http://ossweb-img.qq.com/images/js/jquery/jquery-1.7.2.min.js"></script>
<script type="text/javascript" id="tridentjs" src="http://gpm.oa.com/trident/config.js?utf8"></script>




</head>

<body>
<div class="header">
  <div class="w1200">
    <div class="logo lt"><a href="index.php?module=totalApp&action=totalAppList"><img class="logoimg" src="http://x.ied.com/static/images/logo.png"></a><span class="line_logo"></span><img class="sublogo lol" src="http://x.ied.com/static/images/<?php echo $this->_tpl_vars['serviceType']; ?>
_logo.png" alt='<?php echo $this->_tpl_vars['serviceType']; ?>
'></div>
    <div class="rt">
      <ul class="nav lt">
        <li <?php if ($this->_tpl_vars['navigation'] == 'totalApp'): ?>class="cur"<?php endif; ?>><a href="index.php?module=totalApp&action=totalAppList"><i class="icon1"></i>全部应用</a></li>
        <li <?php if ($this->_tpl_vars['navigation'] == 'myApp'): ?>class="cur"<?php endif; ?>><a href="index.php?module=myApp&action=appList"><i class="icon2"></i>我的应用</a></li>
        <li <?php if ($this->_tpl_vars['navigation'] == 'app_'): ?>class="cur"<?php endif; ?>><a href="javascript:alert('暂未开放');"><i class="icon3"></i>经典案例</a></li>
        <li <?php if ($this->_tpl_vars['navigation'] == 'app_'): ?>class="cur"<?php endif; ?>><a href="javascript:alert('暂未开放');"><i class="icon4"></i>帮助中心</a></li>
      </ul>
      <div class="login lt">
        <?php if ($this->_tpl_vars['isLogin'] == 1): ?><span class="name" id="right_user" ><?php echo $this->_tpl_vars['userName']; ?>
</span>|<a href="index.php?module=common_until&action=no_acl&func=logout">登出</a><?php else: ?><a href="index.php?module=common_until&action=no_acl&func=login">登录</a><?php endif; ?>
        
        
      </div>
    </div>
  </div>
</div>