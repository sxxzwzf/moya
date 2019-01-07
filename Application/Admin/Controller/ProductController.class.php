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
namespace Admin\Controller;

use Admin\Controller\AdminController;
use lyf\Page;

/**
 * 幻灯片控制器
 * @author jry <7661660@qq.com>
 */
class ProductController extends AdminController
{
    /**
     * 产品列表
     * @author jry <7661660@qq.com>
     */
    public function manage($cid = 0)
    {

        if ($cid) {
            $category_object = D('Site/Category');
            $category_info   = $category_object->find($cid);
            if (!$category_info) {
                $this->error('分类不存在');
            }
        }
        // 获取产品列表
        $map = array();
        if ($cid) {
            $map['cid'] = array('eq', $cid);
        }
        $map['status'] = array('egt', '0');
        $article_model = D('Product');
        $p             = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $data_list     = $article_model->where($map)->order('sort desc,id desc')->page($p, 10)->select();
        // 分页
        $page = new \lyf\Page(
            $article_model->where($map)->count(),
            10
        );
        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('产品管理') // 设置页面标题
            ->addTopButton('addnew', array('href' => U('add', array('cid' => $cid)))) // 添加新增按钮
            ->addTopButton('delete') // 添加删除按钮
            ->addTableColumn('id', 'ID')
            ->addTableColumn('title', '标题')
            ->addTableColumn('cover', '封面', 'picture')
            ->addTableColumn('price', '价格')
            ->addTableColumn('sort', '排序')
            ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show())
            ->addRightButton('edit', array('href' => U('edit', array('id' => '__data_id__')))) // 添加编辑按钮
            ->addRightButton('forbid') // 添加禁用/启用按钮
            ->addRightButton('delete') // 添加删除按钮
            ->display();
    }
    /**
     * 新增产品
     * @author jry <7661660@qq.com>
     */
    public function add($cid = null)
    {
        // 新增
        $article_model = D('Admin/Product');
        if (request()->isPost()) {
            $data = $article_model->create();
            if ($data) {
                $id = $article_model->add();
                if ($id) {
                    $this->success('新增成功', U('manage', array('cid' => $cid)));
                } else {
                    $this->error('新增失败' . $article_model->getError());
                }
            } else {
                $this->error($article_model->getError());
            }
        } else {
            $this->assign('info', $info);

            // 获取前台模版供选择
            // 获取模板信息
            $theme_model     = D('Site/Theme');
            $con             = array();
            $con['id'] = C('Site_config.theme');
            $theme_info      = $theme_model->where($con)->find();
            if (!$theme_info) {
                $this->error('请先在后台设置网站模板');
            }
            if (C('CURRENT_THEME')) {
                $template_list = \lyf\File::get_dirs(getcwd() . '/Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name']);
            } else {
                $template_list = \lyf\File::get_dirs(getcwd() . '/Application/Site/View/Site/theme/' . $theme_info['name']);
            }
            foreach ($template_list['file'] as $val) {
                $val = substr($val, 0, -4);
                if (strstr($val, 'detail')) {
                    $template_detail[$val] = $val;
                }
            }

            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('新增产品') // 设置页面标题
                ->setPostUrl(U('add')) // 设置表单提交地址
               // ->addFormItem('cid', 'select', '上级分类', '所属的上级分类', select_list_as_tree('Site/Category', array()), array('must' => 1))
                ->addFormItem('title', 'text', '产品标题', '产品标题', '', array('must' => 1))
                ->addFormItem('price', 'text', '产品价格', '产品价格',"",array('must'=>1))
                ->addFormItem('sort', 'text', '产品排序', '产品排序',"")
                ->addFormItem('content', 'kindeditor', '产品内容', '产品内容', '', array('must' => 1, 'self' => array('upload_driver' => C('site_config.upload_driver') ?: 'Qiniu')))
                ->addFormItem('cover', 'picture', '产品封面', '产品封面', null, array('must' => 1, 'self' => array('upload_driver' => C('site_config.upload_driver') ?: 'Qiniu')))
                ->addFormItem('banner', 'picture_temp', 'Banner图片', 'Banner图片', null, array('self' => array('upload_driver' => C('site_config.upload_driver') ?: 'Qiniu')))
              //  ->addFormItem('detail_template', 'select', '详情模版', '产品详情页模版', $template_detail)
                ->display();
        }
    }
    /**
     * 编辑产品
     * @author jry <7661660@qq.com>
     */
    public function edit($id, $cid = mull)
    {
        // 权限检测
        $article_model = D('Admin/Product');
        $article_info  = $article_model->find($id);
        if (!$article_info) {
            $this->error('产品不存在');
        }

        // 编辑
        if (request()->isPost()) {
            $data = $article_model->create();
            if ($data) {
                if ($article_model->save() !== false) {
                    $this->success('更新成功', U('manage'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($article_model->getError());
            }
        } else {
            $this->assign('info', $info);

            // 获取前台模版供选择
            // 获取模板信息
            $theme_model     = D('Site/Theme');
            $con             = array();
            $con['id'] = C('Site_config.theme');
            $theme_info      = $theme_model->where($con)->find();
            if (!$theme_info) {
                $this->error('请先在后台设置网站模板');
            }
            if (C('CURRENT_THEME')) {
                $template_list = \lyf\File::get_dirs(getcwd() . '/Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name']);
            } else {
                $template_list = \lyf\File::get_dirs(getcwd() . '/Application/Site/View/Site/theme/' . $theme_info['name']);
            }
            foreach ($template_list['file'] as $val) {
                $val = substr($val, 0, -4);
                if (strstr($val, 'detail')) {
                    $template_detail[$val] = $val;
                }
            }

            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('编辑分类') // 设置页面标题
                ->setPostUrl(U('edit', array('id' => $id, 'cid' => $cid))) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
               // ->addFormItem('cid', 'select', '上级分类', '所属的上级分类', select_list_as_tree('Site/Category', array()), array('must' => 1))
                ->addFormItem('title', 'text', '产品标题', '产品标题', '', array('must' => 1))
                ->addFormItem('price', 'text', '产品价格', '产品价格', array('must' => 1))
                ->addFormItem('sort', 'text', '产品排序', '产品排序')
                ->addFormItem('content', 'kindeditor', '产品内容', '产品内容', '', array('must' => 1, 'self' => array('upload_driver' => C('site_config.upload_driver') ?: 'Qiniu')))
                ->addFormItem('cover', 'picture', '产品封面', '产品封面', null, array('self' => array('upload_driver' => C('site_config.upload_driver') ?: 'Qiniu')))
               // ->addFormItem('banner', 'picture', 'Banner图片', 'Banner图片', null, array('self' => array('upload_driver' => C('site_config.upload_driver') ?: 'Qiniu')))
              //  ->addFormItem('detail_template', 'select', '详情模版', '产品详情页模版', $template_detail)
                ->setFormData($article_info)
                ->display();
        }
    }
}
