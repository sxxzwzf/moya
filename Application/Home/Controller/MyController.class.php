<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/6
 * Time: 10:16
 */
namespace Home\Controller;
class MyController  extends HomeController
{

    private $extra_html = <<<EOF
        <script type="text/javascript">
            var tab = new auiTab({
                    element:document.getElementById("footer"),
                    index:2,
                    repeatClick:false
                },function(ret){
                    switch (ret.index){
                        case 1:
                            window.location.href = "/home/index/index.html";
                            break;
                        case 2:
                            window.location.href = "/category/office.html";
                            break;
                        case 3:
                            window.location.href = "/home/my/index.html";
                            break;
                    }
                });
        </script>
EOF;

    //修改个人信息
    public function edit($id="")
    {
        $id=$this->user_info["id"];
        if (request()->isPost()) {
            // 密码为空表示不修改密码
            if (!$_POST['password']) {
                unset($_POST['password']);
            }

            // 提交数据
            $user_object = D('Admin/User');
            $data=$user_object->find($id);
            $data  = $user_object->create();



            if (!isset($_POST['password'])) {
                unset($data["password"]);
            }
            if ($data) {
                $result = $user_object
                   // ->field('id,nickname,username,password,email,email_bind,mobile,mobile_bind,gender,avatar,update_time')
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
            $info = D('Admin/User')->find($id);
            unset($info['password']);
            $this->assign("title","个人信息修改");
            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('编辑用户') // 设置页面标题

            ->setPostUrl(U('edit')) // 设置表单提交地址

            ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('username', 'static', '手机号', '手机号', 'fontsize:18px;', array('must' => 0))
                ->addFormItem('username', 'hidden', '手机号', '手机号', '', array('must' => 0))
                ->addFormItem('nickname', 'text', '昵称', '昵称', '', array('must' => 1))
                ->addFormItem('password', 'password', '密码', '密码', '', array('must' => 1))
             //   ->addFormItem('email', 'text', '邮箱', '邮箱')
             //   ->addFormItem('email_bind', 'radio', '邮箱绑定', '手机绑定', array('1' => '已绑定', '0' => '未绑定'))
             //   ->addFormItem('mobile', 'text', '手机号', '手机号')
             //   ->addFormItem('mobile_bind', 'radio', '手机绑定', '手机绑定', array('1' => '已绑定', '0' => '未绑定'))
                ->addFormItem('avatar', 'picture', '头像', '头像')
                ->addFormItem('authority', 'picture', '代理商授权证书', '代理商授权证书')
                ->setFormData($info)
                ->setExtraHtml($this->extra_html)
                ->display();
        }
    }

    public function index()
    {

        $this->assign('meta_title', "我的");

        $userinfo= D('Admin/User');

        $uid=$_SESSION["ly_home_"]["user_auth"]["uid"];
        $data=$userinfo->find($uid);
        $nickname=$userinfo->data()["nickname"];

        $this->assign('nickname', $nickname);
        $this->assign('avatar',$userinfo->data()["avatar_url"]);

        $this->assign('title',"个人中心");

        $this->display();
    }
}