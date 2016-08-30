<?php
/**
 * hook定义 正三角关系
 * 倒三角不支持
 * 如：$hook['home']['index']['*'][] = 'test' //正三角关系
 * 	   $hook['home']['*']['*'][] = 'test'		//正三角关系
 * 	   $hook['*']['index']['index'][] = 'test'	//倒三角关系 不支持
 */
$hook = array();
$hook['*']['*']['*'][] = 'systemState';
$hook['*']['*']['*'][] = 'behaviorlog';

// $hook['totalApp']['*']['*'][] = 'checkTof';
// $hook['myApp']['*']['*'][] = 'checkTof';
// $hook['devApp']['*']['*'][] = 'checkTof';
$hook['*']['*']['*'][] = 'checkTof';

// $hook['myApp']['*']['*'][] = 'checkUserOALogin';
// $hook['totalApp']['*']['*'][] = 'checkUserOALogin';
// $hook['devApp']['*']['*'][] = 'checkUserOALogin';
$hook['*']['*']['*'][] = 'checkUserOALogin';

$hook['*']['*']['*'][] = 'checkAcl';
$hook['*']['*']['*'][] = 'getAclGroup';
$hook['*']['*']['*'][] = 'getServiceMap';

$hook['*']['*']['*'][] = 'setNavigation';

// $hook['totalApp']['totalAppList']['*'][] = 'getAppKind';

$hook['*']['*']['*'][] = 'updateAuth';


//钩子黑名单
$hook['!'] = array();
$hook['!']['demo']['*']['*'][] = 'checkAcl';
$hook['!']['demo']['*']['*'][] = 'checkTof';

?>