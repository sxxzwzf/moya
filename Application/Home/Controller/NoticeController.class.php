<?php
// +----------------------------------------------------------------------
// | 魔娅 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.ffgame.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <7661660@qq.com>
// +----------------------------------------------------------------------
// | 版权申明：魔娅不是一个自由软件，是魔娅官方推出的商业源码，严禁在未经许可的情况下
// | 拷贝、复制、传播、使用魔娅的任意代码，如有违反，请立即删除，否则您将面临承担相应
// | 法律责任的风险。如果需要取得官方授权，请联系官方http://www.ffgame.com
// +----------------------------------------------------------------------
namespace Home\Controller;


use lyf\Page;

/**
 * 幻灯片控制器
 * @author jry <7661660@qq.com>
 */
class NoticeController extends HomeController
{
    /**
     * 文章
     * @author jry <7661660@qq.com>
     */
    // 获取文章信息
       public function details($id){

           $article_model = D('Site/Notice');
           $con           = array();
           $con['status'] = '1';
           $con['id']     = $id;
           $article_info  = $article_model->where($con)->find();

           $this->assign("product",$article_info);
           $this->assign("title","公告");
         

           $this->display();

       }


}
