<?php
namespace Api\Controller;

use Think\Controller;

class MobileController extends Controller
{
    public function index($info="")
    {
        $requesturi=$_SERVER["REQUEST_URI"];
        $requestlashparam=explode("/", $requesturi);
        $param=$requestlashparam[count($requestlashparam)-1];
        // echo $param;
        //echo json_encode($param);
        //echo <<<str
        //{"code":1,"msg":"请求成功!","data":[{"id":2,"post_type":1,"user_id":1,"comment_status":1,"is_top":0,"recommended":0,"post_hits":3,"post_like":0,"comment_count":0,"create_time":1526789197,"update_time":1526789197,"published_time":"2018-05-20 12:06:25","post_title":"111","post_keywords":"111","post_excerpt":"1111","post_source":"111","post_content":"<p>1111<\/p>","more":{"thumbnail":"","template":""}},{"id":3,"post_type":1,"user_id":1,"comment_status":1,"is_top":0,"recommended":0,"post_hits":7,"post_like":0,"comment_count":0,"create_time":1526789212,"update_time":1526789212,"published_time":"2018-05-20 12:06:43","post_title":"222","post_keywords":"222","post_excerpt":"222","post_source":"222","post_content":"<p>222<\/p>","more":{"thumbnail":"","template":""}}]}
//str;

        $noticemodel=D("Site/Notice");
        $noticedata=$noticemodel->order("update_time desc")->select();
        foreach ($noticedata as $data) {
            $retdata[]=array("id"=>$data["id"],"post_title"=>$data["title"]);
        }


        $raw_success = array('code' => 1, 'msg' => '获取滚动消息成功','data'=>$retdata);

        $raw_fail = array('code' => 2, 'msg' => '验证码错误');

        $res_success = json_encode($raw_success);
        header('Content-Type:application/json');//这个类型声明非常关键
        echo $res_success;

    }
    public function mine($info="")
    {
        //logout退出登录
        $requesturi=$_SERVER["REQUEST_URI"];
        $requestlashparam=explode("/", $requesturi);
        $param=$requestlashparam[count($requestlashparam)-1];
        // echo $param;
        //echo json_encode($param);
        //echo <<<str
        //{"code":1,"msg":"请求成功!","data":[{"id":2,"post_type":1,"user_id":1,"comment_status":1,"is_top":0,"recommended":0,"post_hits":3,"post_like":0,"comment_count":0,"create_time":1526789197,"update_time":1526789197,"published_time":"2018-05-20 12:06:25","post_title":"111","post_keywords":"111","post_excerpt":"1111","post_source":"111","post_content":"<p>1111<\/p>","more":{"thumbnail":"","template":""}},{"id":3,"post_type":1,"user_id":1,"comment_status":1,"is_top":0,"recommended":0,"post_hits":7,"post_like":0,"comment_count":0,"create_time":1526789212,"update_time":1526789212,"published_time":"2018-05-20 12:06:43","post_title":"222","post_keywords":"222","post_excerpt":"222","post_source":"222","post_content":"<p>222<\/p>","more":{"thumbnail":"","template":""}}]}
//str;

        session('user_auth', null);
        session('user_auth_sign', null);


        $raw_success = array('code' => 1, 'msg' => '获取滚动消息成功','data'=>array(array('id'=>1,'post_title'=>'assdfdsfafasdf'),array('id'=>2,'post_title'=>'222222222222')));

        $raw_fail = array('code' => 2, 'msg' => '验证码错误');

        $res_success = json_encode($raw_success);
        header('Content-Type:application/json');//这个类型声明非常关键
        echo $res_success;

    }

}