<?php
// +----------------------------------------------------------------------
// | 魔娅 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.ffgame.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <7661660@qq.com>
// +----------------------------------------------------------------------
namespace Admin\Controller;

use lyf\Page;

/**
 * 用户控制器
 * @author jry <7661660@qq.com>
 */
class UserController extends AdminController
{
    /**
     * 用户列表
     * @author jry <7661660@qq.com>
     */
    public function index()
    {
        // 搜索
        $keyword                                  = I('keyword', '', 'string');
        $condition                                = array('like', '%' . $keyword . '%');
        $map['id|username|nickname|email|mobile'] = array(
            $condition,
            $condition,
            $condition,
            $condition,
            $condition,
            '_multi' => true,
        );
        $rowsperpage=I("perpage",C('ADMIN_PAGE_ROWS'));
        // 获取所有用户
        if(I("action")=="listdisable"){
            $map['status'] = array('eq', '0'); // 禁用状态
        }
        elseif(I("action")=="listenable"){
            $map['status'] = array('eq', '1'); // 正常状态
        }
        else{
            $map['status'] = array('egt', '0'); // 禁用和正常状态
        }

        $p             = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $user_object   = D('User');
        $data_list     = $user_object
            ->page($p, $rowsperpage)
            ->where($map)
            ->order('id desc')
            ->select();
        $page = new Page(
            $user_object->where($map)->count(),
            $rowsperpage
        );

        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('用户列表') // 设置页面标题
            ->addTopButton('addnew') // 添加新增按钮
            ->addTopButton('resume') // 添加启用按钮
            ->addTopButton('forbid') // 添加禁用按钮
            ->addTopButton('delete') // 添加删除按钮
            ->setsearch_form_items("显示未审核","link","","",'','','',U('',"action=listdisable"))
            ->setsearch_form_items("显示已审核","link","","",'','','',U('',"action=listenable"))
           //->setSearch('请输入ID/用户名／邮箱／手机号', U('index'))
            ->addTableColumn('id', 'UID')
            ->addTableColumn('avatar', '头像', 'picture')
            ->addTableColumn('nickname', '昵称')
            ->addTableColumn('username', '用户名')
           // ->addTableColumn('email', '邮箱')
            ->addTableColumn('authority', '代理授权书','picture')
            ->addTableColumn('create_time', '注册时间', 'time')
            ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('edit') // 添加编辑按钮
            ->addRightButton('forbid') // 添加禁用/启用按钮
            ->addRightButton('delete') // 添加删除按钮

            ->display();
    }

    /**
     * 新增用户
     * @author jry <7661660@qq.com>
     */
    //增加新用户
    public function add()
    {
        if (request()->isPost()) {
            $user_object = D('User');
            $data        = $user_object->create();
            if ($data) {
                $id = $user_object->add($data);
                if ($id) {
                    $this->success('新增成功', U('index'));
                } else {
                    $this->error('新增失败：' . $user_object->getError());
                }
            } else {
                $this->error($user_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('新增用户') //设置页面标题
            ->setPostUrl(U('add')) //设置表单提交地址
            ->addFormItem('reg_type', 'hidden', '注册方式', '注册方式', '', array('must' => 1))
                ->addFormItem('nickname', 'text', '昵称', '昵称', '', array('must' => 1))
                ->addFormItem('username', 'text', '用户名', '用户名', '', array('must' => 1))
                ->addFormItem('password', 'password', '密码', '密码', '', array('must' => 1))
                ->addFormItem('email', 'text', '邮箱', '邮箱')
                ->addFormItem('email_bind', 'radio', '邮箱绑定', '手机绑定', array('1' => '已绑定', '0' => '未绑定'))
                ->addFormItem('mobile', 'text', '手机号', '手机号')
                ->addFormItem('mobile_bind', 'radio', '手机绑定', '手机绑定', array('1' => '已绑定', '0' => '未绑定'))
                ->addFormItem('avatar', 'picture', '头像', '头像')
                ->setFormData(array('reg_type' => 'admin'))
                ->display();
        }
    }

    /**
     * 编辑用户
     * @author jry <7661660@qq.com>
     */
    public function edit($id)
    {
        if (request()->isPost()) {
            // 密码为空表示不修改密码
            if (!$_POST['password']) {
                unset($_POST['password']);
            }

            // 提交数据
            $user_object = D('User');
            $data        = $user_object->create();
            if (!$_POST['password']) {
                unset($data['password']);
            }
            if ($data) {
                $result = $user_object->save($data);
                if ($result) {
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败：' . $user_object->getError());
                }
            } else {
                $this->error($user_object->getError());
            }
        } else {
            // 获取账号信息
            $info = D('User')->find($id);
            unset($info['password']);

            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('编辑用户') // 设置页面标题
                ->setPostUrl(U('edit')) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('nickname', 'text', '昵称', '昵称', '', array('must' => 1))
                ->addFormItem('username', 'text', '用户名', '用户名', '', array('must' => 1))
                ->addFormItem('password', 'password', '密码', '密码', '', array('must' => 1))
                ->addFormItem('status', 'radio', '账号状态', '账号状态', array('1' => '启用', '0' => '禁用'))
               // ->addFormItem('email', 'text', '邮箱', '邮箱')
               // ->addFormItem('email_bind', 'radio', '邮箱绑定', '手机绑定', array('1' => '已绑定', '0' => '未绑定'))
               // ->addFormItem('mobile', 'text', '手机号', '手机号')
               // ->addFormItem('mobile_bind', 'radio', '手机绑定', '手机绑定', array('1' => '已绑定', '0' => '未绑定'))
                ->addFormItem('avatar', 'picture', '头像', '头像')
                ->addFormItem('authority', 'picture', '代理授权书', '代理授权书')
                ->setFormData($info)
                ->display();
        }
    }

    /**
     * 设置一条或者多条数据的状态
     * @author jry <7661660@qq.com>
     */
    public function setStatus($model = '', $strict = null)
    {
        if ('' == $model) {
            $model = request()->controller();
        }
        $ids = I('request.ids');
        if (is_array($ids)) {
            if (in_array('1', $ids)) {
                $this->error('超级管理员不允许操作');
            }
        } else {
            if ($ids === '1') {
                $this->error('超级管理员不允许操作');
            }
        }
        parent::setStatus($model);
    }
}
