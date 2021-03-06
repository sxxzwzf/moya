<?php
// +----------------------------------------------------------------------
// | 魔娅 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.ffgame.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <7661660@qq.com>
// +----------------------------------------------------------------------
// 模块信息配置
return array(
    // 模块信息
    'info'       => array(
        'name'        => 'Admin',
        'title'       => '系统',
        'icon'        => 'fa fa-cog',
        'icon_color'  => '#3CA6F1',
        'description' => '核心系统',
        'developer'   => '山西魔娅网络科技有限公司',
        'website'     => 'http://www.ffgame.com',
        'version'     => '1.6.2',
    ),

    // 后台菜单及权限节点配置
    'admin_menu' => array(
        '1'  => array(
            'pid'   => '0',
            'title' => '系统',
            'icon'  => 'fa fa-cog',
            'level' => 'system',
        ),
        '2'  => array(
            'pid'   => '1',
            'title' => '系统功能',
            'icon'  => 'fa fa-folder-open-o',
        ),
        '3'  => array(
            'pid'   => '2',
            'title' => '系统设置',
            'icon'  => 'fa fa-wrench',
            'url'   => 'Admin/Config/group',
        ),
        '4'  => array(
            'pid'   => '3',
            'title' => '修改设置',
            'url'   => 'Admin/Config/groupSave',
        ),
         '6'  => array(
            'pid'   => '5',
            'title' => '新增',
            'url'   => 'Admin/Nav/add',
        ),
        '7'  => array(
            'pid'   => '5',
            'title' => '编辑',
            'url'   => 'Admin/Nav/edit',
        ),
        '8'  => array(
            'pid'   => '5',
            'title' => '设置状态',
            'url'   => 'Admin/Nav/setStatus',
        ),
        '9'  => array(
            'pid'   => '2',
            'title' => '幻灯管理',
            'icon'  => 'fa fa-image',
            'url'   => 'Admin/Slider/index',
        ),
        '10' => array(
            'pid'   => '9',
            'title' => '新增',
            'url'   => 'Admin/Slider/add',
        ),
        '11' => array(
            'pid'   => '9',
            'title' => '编辑',
            'url'   => 'Admin/Slider/edit',
        ),
        '12' => array(
            'pid'   => '9',
            'title' => '设置状态',
            'url'   => 'Admin/Slider/setStatus',
        ),
        '18' => array(
            'pid'   => '17',
            'title' => '新增',
            'url'   => 'Admin/Config/add',
        ),
        '19' => array(
            'pid'   => '17',
            'title' => '编辑',
            'url'   => 'Admin/Config/edit',
        ),
        '20' => array(
            'pid'   => '17',
            'title' => '设置状态',
            'url'   => 'Admin/Config/setStatus',
        ),
        '21' => array(
            'pid'   => '2',
            'title' => '上传管理',
            'icon'  => 'fa fa-upload',
            'url'   => 'Admin/Upload/index',
        ),
        '22' => array(
            'pid'   => '21',
            'title' => '上传文件',
            'url'   => 'Admin/Upload/upload',
        ),
        '23' => array(
            'pid'   => '21',
            'title' => '删除文件',
            'url'   => 'Admin/Upload/delete',
        ),
        '24' => array(
            'pid'   => '21',
            'title' => '设置状态',
            'url'   => 'Admin/Upload/setStatus',
        ),
        '25' => array(
            'pid'   => '21',
            'title' => '下载远程图片',
            'url'   => 'Admin/Upload/downremoteimg',
        ),
        '26' => array(
            'pid'   => '21',
            'title' => '文件浏览',
            'url'   => 'Admin/Upload/fileManager',
        ),
        '27' => array(
            'pid'   => '1',
            'title' => '系统权限',
            'icon'  => 'fa fa-folder-open-o',
        ),
        '28' => array(
            'pid'   => '27',
            'title' => '用户管理',
            'icon'  => 'fa fa-user',
            'url'   => 'Admin/User/index',
        ),
        '29' => array(
            'pid'   => '28',
            'title' => '新增',
            'url'   => 'Admin/User/add',
        ),
        '30' => array(
            'pid'   => '28',
            'title' => '编辑',
            'url'   => 'Admin/User/edit',
        ),
        '31' => array(
            'pid'   => '28',
            'title' => '设置状态',
            'url'   => 'Admin/User/setStatus',
        ),
        '32' => array(
            'pid'   => '27',
            'title' => '管理员管理',
            'icon'  => 'fa fa-lock',
            'url'   => 'Admin/Access/index',
        ),
        '33' => array(
            'pid'   => '32',
            'title' => '新增',
            'url'   => 'Admin/Access/add',
        ),
        '34' => array(
            'pid'   => '32',
            'title' => '编辑',
            'url'   => 'Admin/Access/edit',
        ),
        '35' => array(
            'pid'   => '32',
            'title' => '设置状态',
            'url'   => 'Admin/Access/setStatus',
        ),
        '37' => array(
            'pid'   => '36',
            'title' => '新增',
            'url'   => 'Admin/Group/add',
        ),
        '38' => array(
            'pid'   => '36',
            'title' => '编辑',
            'url'   => 'Admin/Group/edit',
        ),
        '39' => array(
            'pid'   => '36',
            'title' => '设置状态',
            'url'   => 'Admin/Group/setStatus',
        ),

        '48' => array(
            'pid'   => '40',
            'title' => '功能模块',
            'icon'  => 'fa fa-th-large',
            'url'   => 'Admin/Module/index',
        ),
        '49' => array(
            'pid'   => '48',
            'title' => '安装检查',
            'url'   => 'Admin/Module/install_before',
        ),
        '50' => array(
            'pid'   => '48',
            'title' => '安装',
            'url'   => 'Admin/Module/install',
        ),
        '51' => array(
            'pid'   => '48',
            'title' => '卸载检查',
            'url'   => 'Admin/Module/uninstall_before',
        ),
        '52' => array(
            'pid'   => '48',
            'title' => '卸载',
            'url'   => 'Admin/Module/uninstall',
        ),
        '53' => array(
            'pid'   => '48',
            'title' => '更新信息',
            'url'   => 'Admin/Module/updateInfo',
        ),
        '54' => array(
            'pid'   => '48',
            'title' => '设置状态',
            'url'   => 'Admin/Module/setStatus',
        ),
        '55' => array(
            'pid'   => '40',
            'title' => '插件管理',
            'icon'  => 'fa fa-th',
            'url'   => 'Admin/Addon/index',
        ),
        '56' => array(
            'pid'   => '55',
            'title' => '安装',
            'url'   => 'Admin/Addon/install',
        ),
        '57' => array(
            'pid'   => '55',
            'title' => '卸载',
            'url'   => 'Admin/Addon/uninstall',
        ),
        '58' => array(
            'pid'   => '55',
            'title' => '运行',
            'url'   => 'Admin/Addon/execute',
        ),
        '59' => array(
            'pid'   => '55',
            'title' => '设置',
            'url'   => 'Admin/Addon/config',
        ),
        '60' => array(
            'pid'   => '55',
            'title' => '后台管理',
            'url'   => 'Admin/Addon/adminList',
        ),
        '61' => array(
            'pid'   => '60',
            'title' => '新增数据',
            'url'   => 'Admin/Addon/adminAdd',
        ),
        '62' => array(
            'pid'   => '60',
            'title' => '编辑数据',
            'url'   => 'Admin/Addon/adminEdit',
        ),
        '63' => array(
            'pid'   => '60',
            'title' => '设置状态',
            'url'   => 'Admin/Addon/setStatus',
        ),
        '64' => array(
            'pid'   => '1',
            'title' => '图库功能',
            'icon'  => 'fa fa-folder-open-o',
            'url'   => 'Admin/Addon/setStatus',
        ),
        '65' => array(
            'pid'   => '64',
            'title' => '分类管理',
            'icon'  => 'fa fa-list ',
            'url'   => '/site/category/category',
        ),
        '66' => array(
            'pid'   => '64',
            'title' => '图片管理',
            'icon'  => 'fa fa-rss-square ',
            'url'   => '/site/picture/picture',
        ),
        '40' => array(
            'pid'   => '1',
            'title' => '扩展中心',
            'icon'  => 'fa fa-folder-open-o',
        ),
        '67' => array(
            'pid'   => '1',
            'title' => '其它功能',
            'icon'  => 'fa fa-folder-open-o',
            'url'   => 'Admin/Addon/setStatus',
        ),
        '68' => array(
            'pid'   => '67',
            'title' => '产品管理',
            'icon'  => 'fa fa-list ',
            'url'   => '/admin/product/manage',
        ),
        '69' => array(
            'pid'   => '67',
            'title' => '公告管理',
            'icon'  => 'fa fa-list ',
            'url'   => '/site/notice/manage',
        ),
        '70' => array(
            'pid'   => '67',
            'title' => '质检报告',
            'icon'  => 'fa fa-rss-square ',
            'url'   => '/site/zhijian/picture',
        ),
        '71' => array(
            'pid'   => '67',
            'title' => '联系我们',
            'icon'  => 'fa fa-rss-square ',
            'url'   => 'site/article/article_edit/id/2',
        ),
        '72' => array(
            'pid'   => '64',
            'title' => '实用工具',
            'icon'  => 'fa fa-rss-square ',
            'url'   => '/site/picture/Tools',
        ),

    ),
);
