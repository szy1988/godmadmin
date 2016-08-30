<?php
/**
 * 权限全局控制，1为打开，0为关闭
 */
$acl['acl_lock'] = array(
    'open' => 1
);

/**
 * 不进行权限验证的方法目录
 */
$acl['no_acl'] = array(

        'totalApp'=>array(
                'totalAppList'=>array('getGroupByCenterID','addUseTimes'),
                'newAppUpload'=>array('index','qr','newAppInsert'),
                'appShow'=>array('index'),

                'caseShowForAction'=>array('index','zan'),
                'totalCgiList'=>array('index'),
                'totalDevAppList'=>array('index'),
                'devAppList'=>array('index','passApp','refuseApp','downLineApp'),
                'syncAllApps'=>array('index'),
                'appUpdate'=>array('index','appUpdate','appDetailShow'),
                'userAuthApply'=>array('index','applyAuthNew'),
                'caseControlList'=>array('index','passApp','refuseApp','downLineApp'),
                'caseControlListForAction'=>array('index','passApp','refuseApp','downLineApp'),
                'caseUpdate'=>array('index','caseUpdate'),
                ),
        'common_until'=>array('no_acl'=>array('index','login','logout','applyAuth','entrySystem'),'otherPlatHeader'=>array('index'),'getTofGroup'=>array('index','zxlist'),'authImport'=>array('index','applyAuthAll')),
        'myApp'=>array('appList'=>array('mobileAppList'),'cgiList'=>array('index'),'devAppList'=>array('index','collectMyApp')),

        'devApp'=>array('cgiUpdate'=>array(
                    'index',
                    'cgiUpdate',
                    ),
                    'devAppList'=>array(
                        'index',
                        'cgiList',
                        'passApp',
                        'refuseApp',
                        'downLineApp',
                        ),
                    'cgiShow'=>array(
                        'index',
                        ),
                    'newAppUpload'=>array(
                        'index',
                        'newAppInsert',
                        ),
                    'appUpdate'=>array(
                        'index',
                        'appUpdate',
                        'appDetailShow',
                ),
        ),
        'upload' =>array(
                'img_upload' => array(
                    'index',
                ),
                'pdf_upload' => array(
                    'index',
                ),

            ),
        
    );

//不需要用户登录的目录限制到方法
$acl['no_login'] = array(
        'totalApp'=>array('syncAllApps'=>array('index')),
        'common_until'=>array('no_acl'=>array('index','login','logout','applyAuth','entrySystem','entryLogin'),
        'otherPlatHeader'=>array('index'),'authImport'=>array('index','applyAuthAll')),
        'crontab_task' => array('syncServiceType'=>array('index')),
        'demo' => array('ajax'=>array('js')),
    );


/**
 * myApp moudle control
 */
$acl['myApp'] = array(
    'appList' => array(
        'index' => '2046',
        'collectMyApp' => '2046',
        'mobileAppList' => '2046',
        'goOtherPlat' => '2046',
    ),
    'devAppList' => array(
        'index' => '2027',
        ),
    'cgiList' => array(
        'index'=>'2027'
        ),

);

/**
 * totalApp moudle control
 */
$acl['totalApp'] = array(
    'totalAppList' => array(
        'index' => '2046',
        'applyAuth' => '2046',
        'applyAuthCheck' => '2046',
    ),
    'newAppUpload'=>array(
        'index' => '2027',
        'newAppInsert' => '2027',
        ),
    'appUpdate'=>array(
        'index' => '2027',
        'appUpdate' => '2027',
        'appDetailShow' => '2027',
        ),
    'devAppList'=>array(
        'index'  => '2027',
        'passApp' => '2066',
        'refuseApp' => '2066',
        'downLineApp' => '2066',
        'applyAuth' => '2046',
        ),
    'totalDevAppList'=>array(
        'index' => '2027',
        'applyAuth' => '2027',
        'applyAuthCheck' => '2027',
        ),
    'cgiUpdate'=>array(
        'index' => '2027',
        'cgiUpdate' => '2027',
        ),
    'totalCgiList' => array(
        'index' => '2027',
        'applyAuth'=>'2027',
        ),
    'caseShow' => array(
        'index' => '2046',
        'zan' => '2046',
        ),
);

$acl['devApp'] = array(
    'cgiUpdate'=>array(
        'index' => '2027',
        'cgiUpdate' => '2027',
        ),
    'devAppList'=>array(
        'index'  => '2027',
        'passApp' => '2066',
        'refuseApp' => '2066',
        'downLineApp' => '2066',
        'cgiList'=>'2027',
        ),
    'cgiShow'=>array(
        'index'=>'2027',
        ),
    'newAppUpload'=>array(
        'index' => '2027',
        'newAppInsert' => '2027',
        ),
    'appUpdate'=>array(
        'index' => '2027',
        'appUpdate' => '2027',
        'appDetailShow' => '2027',
        ),
);

$acl['upload'] = array(
    'img_upload' => array(
        'index' => '2027',
    ),
    'pdf_upload' => array(
        'index' => '2027',
    ),

);

$acl['easyApply'] = array(
'jaspersong',
'timmu',
'gloryzhang',
'rebeccayang',
'baoleiwang',
'v_zjzzhu',
'nicklzli',
);

$acl['caseCheck'] = array(
'lol' =>'rebeccayang',

'default'=>'rebeccayang',

);
