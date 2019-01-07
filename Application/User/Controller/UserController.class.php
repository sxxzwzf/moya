<?php
namespace User\Controller;

use Think\Controller;

class UserController extends Controller
{
    public function index($info="")
    {
        $data=$_POST;
        $requesturi=$_SERVER["REQUEST_URI"];
        $requestlashparam=explode("/", $requesturi);
        $param=$requestlashparam[count($requestlashparam)-1];
      // echo $param;
       //echo json_encode($param);
       //echo <<<str
//{"code":1,"msg":"请求成功!","data":[{"id":2,"post_type":1,"user_id":1,"comment_status":1,"is_top":0,"recommended":0,"post_hits":3,"post_like":0,"comment_count":0,"create_time":1526789197,"update_time":1526789197,"published_time":"2018-05-20 12:06:25","post_title":"111","post_keywords":"111","post_excerpt":"1111","post_source":"111","post_content":"<p>1111<\/p>","more":{"thumbnail":"","template":""}},{"id":3,"post_type":1,"user_id":1,"comment_status":1,"is_top":0,"recommended":0,"post_hits":7,"post_like":0,"comment_count":0,"create_time":1526789212,"update_time":1526789212,"published_time":"2018-05-20 12:06:43","post_title":"222","post_keywords":"222","post_excerpt":"222","post_source":"222","post_content":"<p>222<\/p>","more":{"thumbnail":"","template":""}}]}
//str;



        $raw_success = array('code' => 1, 'msg' => '获取滚动消息成功','data'=>array(array('id'=>1,'post_title'=>'assdfdsfafasdf'),array('id'=>2,'post_title'=>'222222222222')));

        $raw_fail = array('code' => 2, 'msg' => '验证码错误');

        $res_success = json_encode($raw_success);
        header('Content-Type:application/json');//这个类型声明非常关键
echo $res_success;

    }
public function login()
{

    // 登录

    if (request()->isPost()) {
        $username = I('account');
        $password = I('password');

        // 图片验证码校验
       // if (!$this->checkVerify(I('post.verify')) && 'localhost' !== request()->hostname() && '127.0.0.1' !== request()->hostname()) {
        //    $this->error('验证码输入错误！');
        //}

        // 验证用户名密码是否正确
        $user_object = D('User/User');
        $user_info   = $user_object->login($username, $password);
        if (!$user_info) {
            $this->error($user_object->getError());
        }

        // 验证管理员表里是否有该用户
        $account_object = D('Admin/Access');
        $where['uid']   = $user_info['id'];
        $account_info   = $account_object->where($where)->find();
       // if (!$account_info) {
       //     $this->error('该用户没有管理员权限' . $account_object->getError());
       // }

        // 设置登录状态
        $uid = $user_object->auto_login($user_info);


    }


    $raw_success = array('status' => 1,  'info' => '登录成功','url'=>'/');

    $raw_fail = array('status' => 0);

    $res_success = json_encode($raw_success);
    header('Content-Type:application/json');//这个类型声明非常关键
    echo $res_success;
}
}