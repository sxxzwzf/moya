<?php
// +----------------------------------------------------------------------
// | 魔娅 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.ffgame.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <7661660@qq.com>
// +----------------------------------------------------------------------
namespace Home\Controller;

use Common\Controller\Controller;
use lyf\Verify;

/**
 * 后台唯一不需要权限验证的控制器
 * @author jry <7661660@qq.com>
 */
class LoginController extends Controller
{
    //改密码
    //增加新用户
    public function changepwd()
    {
        if (request()->isPost()) {

            $username = I('username');
            $password = I('nickname');
            if($password=='' or $username==""){
                $this->error("请输入昵称和用户名!");
            }
            if($username=='admin')
            {
                $this->error("请输入昵称和用户名!");
            }
            // 验证用户名昵称是否正确
            $user_object = D('Admin/User');

            $user_info   = $user_object->checkNickName($username, $password);
            if (!$user_info) {
                $this->error($user_object->getError());
            }


            // 图片验证码校验
            if (!$this->checkVerify(I('post.verify')) && 'localhost' !== request()->hostname() && '127.0.0.6' !== request()->hostname()) {
                $this->error('验证码输入错误！');
            }

            $user_object = D('Admin/User');
            $user_info["password"]=I("password");
            $data        = $user_object->create($user_info);
            if ($data) {

                $id = $user_object->save($data);
                if ($id) {
                    $this->success('更新密码成功', U('Home/login/login'));
                } else {
                    $this->error('更新密码失败：' . $user_object->getError());
                }
            } else {
                $this->error($user_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $this->assign("title","更改密码");
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('更改密码') //设置页面标题
            ->setPostUrl(U('add')) //设置表单提交地址
            ->addFormItem('reg_type', 'hidden', '注册方式', '注册方式', '', array('must' => 1))

                ->display();
        }
    }
    /**
     * 审核中方法
     * @author jry <7661660@qq.com>
     */
    public function checking()
    {
        if(!is_login())
        {
            $this->redirect('Home/Login/Login');
        }
        $id=$_SESSION["ly_home_"]["user_auth"]["uid"];
        if(!$id)
        {
            $this->redirect('Home/Login/Login');
        }
        if(!request()->isPost())
        {
            $info = D('Admin/User')->find($id);

            $uid = D('Admin/User')->auto_login($info);
            if ( is_login() && is_enable() ) {
                $this->redirect('Home/Index/index');
            }
        }
        else {
            $user_object = D('Admin/User');
            $data=$user_object->find($id);

            $data["authority"]=$_POST["authority"];
            $userdata=$user_object->create($data);
            $userdata["password"]=$data["password"];
            if ($data) {
                $result = $user_object->save($data);
                if ($result) {
                    $this->success('更新成功');
                } else {
                    $this->error('更新失败：' . $user_object->getError());
                }
            } else {
                $this->error($user_object->getError());
            }
        }
        $userinfo = D("Admin/User")->find($id);
        if($userinfo["authority"])
        {
            $picinfo=D("Admin/Upload")->find($userinfo["authority"]);
        }
        $this->assign('meta_title', "正在审核中");
        $this->assign("title","正在审核中");
        $this->assign("userinfo",$userinfo);
        $this->assign("picinfo",$picinfo);
        // 使用FormBuilder快速建立表单页面
        $builder = new \lyf\builder\FormBuilder();
        $builder->setMetaTitle('正在审核中') // 设置页面标题
        ->addFormItem('id', 'hidden', 'ID', 'ID')
            ->addFormItem('username', 'static', '手机号', '手机号', 'fontsize:18px;', array('must' => 0))
            ->addFormItem('username', 'hidden', '手机号', '手机号', '', array('must' => 0))
            ->addFormItem('nickname', 'static', '昵称', '昵称', '', array('must' => 1))
            ->addFormItem('authority', 'picture', '代理商授权证书', '代理商授权证书')
            ->setFormData($info)
            ->setExtraHtml($this->extra_html)
            ->display();
    }
    //增加新用户
    public function add()
    {
        if (request()->isPost()) {

            // 图片验证码校验
            if (!$this->checkVerify(I('post.verify')) && 'localhost' !== request()->hostname() && '127.0.0.6' !== request()->hostname()) {
                $this->error('验证码输入错误！');
            }
            $user_object = D('Admin/User');
            $data        = $user_object->create();
            if ($data) {
                $data["status"]=0;
                $id = $user_object->add($data);
                if ($id) {
                     $this->success('新增成功,请等待管理员审核', U('Home/login/login'));
                } else {
                    $this->error('新增失败：' . $user_object->getError());
                }
            } else {
                $this->error($user_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $this->assign("title","用户注册");
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('新增用户') //设置页面标题
            ->setPostUrl(U('add')) //设置表单提交地址
            ->addFormItem('reg_type', 'hidden', '注册方式', '注册方式', '', array('must' => 1))

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
            if ($data) {
                $result = $user_object
                    ->field('id,nickname,username,password,email,email_bind,mobile,mobile_bind,gender,avatar,update_time')
                    ->save($data);
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
                ->addFormItem('email', 'text', '邮箱', '邮箱')
                ->addFormItem('email_bind', 'radio', '邮箱绑定', '手机绑定', array('1' => '已绑定', '0' => '未绑定'))
                ->addFormItem('mobile', 'text', '手机号', '手机号')
                ->addFormItem('mobile_bind', 'radio', '手机绑定', '手机绑定', array('1' => '已绑定', '0' => '未绑定'))
                ->addFormItem('avatar', 'picture', '头像', '头像')
                ->setFormData($info)
                ->display();
        }
    }

    /**
     * 后台登陆
     * @author jry <7661660@qq.com>
     */
    public function login()
    {

        // 登录
        if (is_login()&&is_enable()) {
            $this->redirect('Home/Index/index');
        }
        if (request()->isPost()) {
            $username = I('username');
            $password = I('password');
            if($password=='' or $username==""){
                $this->error("请输入用户名和密码!");
            }
            // 图片验证码校验
            if (!$this->checkVerify(I('post.verify')) && 'localhost' !== request()->hostname() && '127.0.0.6' !== request()->hostname()) {
               $this->error('验证码输入错误！');
            }

            // 验证用户名密码是否正确
            $user_object = D('Admin/User');
            $user_info   = $user_object->login($username, $password);
            if (!$user_info) {
               $this->error($user_object->getError());
            }

            $uid = $user_object->auto_login($user_info);
            if(is_enable())
            {

                $this->success('登录成功！', U('Home/Index/index'));
            }
            else{
                $this->success('正在审核！', U('Home/Login/checking'));
            }




            // 设置登录状态
            //$this->redirect('Home/Index/index');

            // 跳转
           // if (0 < $account_info['uid'] && $account_info['uid'] === $uid) {
            //    $this->success('登录成功！', U('Admin/Index/index'));
          //  } else {
           //     $this->logout();
          //  }
        } else {
            $this->assign('meta_title', '会员登录');
           $this->display();
        }
    }

    /**
     * 注销
     * @author jry <7661660@qq.com>
     */
    public function logout()
    {
        session('user_auth', null);
        session('user_auth_sign', null);
        $this->success('退出成功！', U('login'));
    }

    /**
     * 图片验证码生成，用于登录和注册
     * @author jry <7661660@qq.com>
     */
    public function verify($vid = 1)
    {
        $verify         = new Verify();
        $verify->length = 4;
        $verify->entry($vid);
    }


    /**
     * 检测验证码
     * @param  integer $id 验证码ID
     * @return boolean 检测结果
     */
    protected function checkVerify($code, $vid = 1)
    {
        $verify = new Verify();
        return $verify->check($code, $vid);
    }
}
