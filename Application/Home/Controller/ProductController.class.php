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
class ProductController extends HomeController
{
    /**
     * 文章列表
     * @author jry <7661660@qq.com>
     */
    public function product($cid = 0)
    {

        if ($cid) {
            $category_object = D('Site/Category');
            $category_info   = $category_object->find($cid);
            if (!$category_info) {
                $this->error('分类不存在');
            }
        }
        // 获取文章列表
        $map = array();

        $map['status'] = array('egt', '0');
        $product_model = D('Admin/Product');
        $p             = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $data_list     = $product_model->where($map)->order('sort desc,id desc')->select();
        // 分页
        $page = new \lyf\Page(
            $product_model->where($map)->count(),
            10
        );

        $this->assign("product",$data_list);
        $this->assign("title",产品列表);
        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('文章管理') // 设置页面标题

            ->display();
    }


    public function details($id)
    {
        // 网站配置
        $info = $this->info;

        // 获取文章信息
        $product_model = D('Admin/Product');
        $con = array();
        $con['status'] = '1';
        $con['id'] = $id;
        $product = $product_model->where($con)->find();
        $this->assign("product",$product);
        $this->assign("title",产品详情);

        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('文章管理') // 设置页面标题

        ->display();


    }

}
