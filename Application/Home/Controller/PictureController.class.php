<?php
// +----------------------------------------------------------------------
// | 魔娅 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.ffgame.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <7661660@qq.com>
// +----------------------------------------------------------------------
namespace Home\Controller;

/**
 * 上传控制器
 * @author jry <7661660@qq.com>
 */
class PictureController extends HomeController
{
    /**
     * 上传
     * @author jry <7661660@qq.com>
     */
    public function delete($model=null)
    {


        if ('' == $model) {
            $model = request()->controller();
        }
        $ids    = I('request.ids');
        $status = I('request.status');
        if (empty($ids)) {
            $this->error('请选择要操作的数据');
        }
        switch ($status) {
            case 'delete': // 删除条目
                if (!is_array($ids)) {
                    $id_list[0] = $ids;
                } else {
                    $id_list = $ids;
                }
                foreach ($id_list as $id) {
                    $upload_info = D('Site/Picture')->find($id);

                    if ($upload_info) {
                        $picid=$upload_info["picid"];
                        $resut = D('Site/Picture')->delete($id);

                        //查找别的条目是否引用此图片，如果不引用则删除文件(只删除HOME文件夹中的数据)
                        $hasquot = D('Site/Picture')->where("picid=".$picid)->select();
                        if(!$hasquot)
                        {
                            $mupload=D("Admin/Upload");
                            $uploadfile=$mupload->find($picid);


                            if(!preg_match("/home/",$uploadfile["path"]))
                            {
                                $this->success('删除成功！');
                                continue;
                            }
                            $realpath = realpath('.' . $uploadfile['path']);

                            if ($realpath) {
                                array_map("unlink", glob($realpath));
                            }
                            $mupload->delete($picid);

                            if (count(glob($realpath))) {
                                $this->error('删除失败！');
                            }
                            else{
                                $this->success('删除成功！');
                            }
                        }

                        $this->success('删除成功！');

                    }
                }
                break;
            default:
                parent::setStatus($model);
                break;
        }



    }
    /**
 * 上传
 * @author jry <7661660@qq.com>
 */
    public function add()
    {
        // 新增
        $picture_model = D('Site/picture');
        if (request()->isPost()) {
            $picids=explode(",",$_POST["picids"]);

            foreach ( $picids as $pic) {

                $data = $picture_model->create();
                if ($data) {
                    $data["picid"] = $pic;
                    $adminuploadfile=D('Admin/Upload');
                    $filedata=$adminuploadfile->find($pic);

                    $data['name']=$filedata["name"];
                    $id = $picture_model->add($data);
                    if ($id)
                    {

                    } else {
                      //  $this->error('新增失败' . $picture_model->getError());
                    }
                }
                else {
                    $this->error($picture_model->getError());
                }

            }
            $this->success('新增成功');

        } else {
            $this->assign('info', $info);

            // 获取前台模版供选择
            // 获取模板信息
            $this->assign("title","上传本地图片");

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
            $builder->setMetaTitle('新增图片') // 设置页面标题
            ->setPostUrl(U('add')) // 设置表单提交地址
            ->addFormItem('cid', 'select', '上级分类', '所属的上级分类', select_list_as_tree('Site/Category', array(),array(),"id",array("root"=>"2")), array('must' => 1))
                //  ->addFormItem('title', 'text', '文章标题', '文章标题', '', array('must' => 1))
                // ->addFormItem('abstract', 'textarea', '文章简介', '文章简介')
                //  ->addFormItem('content', 'kindeditor', '文章内容', '文章内容', '', array('must' => 1, 'self' => array('upload_driver' => C('site_config.upload_driver') ?: 'Qiniu')))
                ->addFormItem('picids', 'pictures', '多图上传', '多图上传', null, array('must' => 1, 'self' => array('upload_driver' => C('site_config.upload_driver') ?: 'Qiniu')))
                //    ->addFormItem('banner', 'picture_temp', 'Banner图片', 'Banner图片', null, array('self' => array('upload_driver' => C('site_config.upload_driver') ?: 'Qiniu')))
                //   ->addFormItem('detail_template', 'select', '详情模版', '文章详情页模版', $template_detail)
                ->display();
        }

    }
    public function upload_manage()
    {
        $listtree=select_list_as_tree2('Site/Category', array(),array(),"id",array("root"=>"2"));

        foreach ($listtree as $treekey=>$listitem)
        {
            foreach ($listitem['picdata'] as $key => $picdata)
            {

                $pic=D("Admin/Upload")->order("create_time desc")->find($picdata['picid']);
                $picdata1 = &$listtree[$treekey]["picdata"][$key];
                $picdata1["url"]=$pic['path'];

                $i=0;
            }
        }
        $this->assign("title","上传图片管理");
        $this->assign("listtree",$listtree);
        // 新增
        $picture_model = D('Site/picture');
        if (request()->isPost()) {
            $picids=explode(",",$_POST["picids"]);

            foreach ( $picids as $pic) {

                $data = $picture_model->create();
                if ($data) {
                    $data["picid"] = $pic;
                    $adminuploadfile=D('Admin/Upload');
                    $filedata=$adminuploadfile->find($pic);

                    $data['name']=$filedata["name"];
                    $id = $picture_model->add($data);

                }
                else {
                    $this->error($picture_model->getError());
                }

            }
            if ($id)
            {
                $this->success('新增成功');
            } else {
                //$this->error('新增失败' . $picture_model->getError());
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
            $builder->setMetaTitle('新增图片') // 设置页面标题
            ->setPostUrl(U('add')) // 设置表单提交地址
            ->addFormItem('cid', 'select', '上级分类', '所属的上级分类', select_list_as_tree('Site/Category', array(),array(),"id",array("root"=>"2")), array('must' => 1))
                //  ->addFormItem('title', 'text', '文章标题', '文章标题', '', array('must' => 1))
                // ->addFormItem('abstract', 'textarea', '文章简介', '文章简介')
                //  ->addFormItem('content', 'kindeditor', '文章内容', '文章内容', '', array('must' => 1, 'self' => array('upload_driver' => C('site_config.upload_driver') ?: 'Qiniu')))
                ->addFormItem('picids', 'pictures', '多图上传', '多图上传', null, array('must' => 1, 'self' => array('upload_driver' => C('site_config.upload_driver') ?: 'Qiniu')))
                //    ->addFormItem('banner', 'picture_temp', 'Banner图片', 'Banner图片', null, array('self' => array('upload_driver' => C('site_config.upload_driver') ?: 'Qiniu')))
                //   ->addFormItem('detail_template', 'select', '详情模版', '文章详情页模版', $template_detail)
                ->display();
        }




    }
    //分类显示
    public function category()
    {
        $cid=I("get.cid");
        $listtree=select_category_by_cid('Site/Category', array('pid'=>array('eq',$cid)),array(),"id",array("root"=>"0"));


        $cidname=D('Site/Category')->where('id='.$cid)->select();

        $this->assign('title',$cidname[0]['title']);
        $this->assign("type",$listtree["type"]);
        $this->assign('form', $listtree[$listtree["type"]]); //表单项目

        $builder = new \lyf\builder\FormBuilder();
        $builder->display();

    }
    //分类显示
    public function categoryzhijian()
    {
        $cid=I("get.cid");
        $listtree=select_category_by_cid('Site/Category', array('pid'=>array('eq',$cid)),array(),"id",array("root"=>"0"));

        $this->assign('title',"质检报告");
        $this->assign("type",$listtree["type"]);
        $this->assign('form', $listtree[$listtree["type"]]); //表单项目

        $builder = new \lyf\builder\FormBuilder();
        $builder->display();

    }
    //分类显示
    public function categoryfirst()
    {
        $cid=I("get.cid");

        $listtree=select_category2level_by_cid('Site/Category', array('pid'=>array('eq',$cid)),array(),"id",array("root"=>"1"));
        unset($listtree["cid"][2]);
        $this->assign('title',"图库分类");
        $this->assign("type",$listtree["type"]);
        $this->assign('form', $listtree[$listtree["type"]]); //表单项目

        $builder = new \lyf\builder\FormBuilder();
        $builder->display();

    }
    //滑动效果
    public function swiper()
    {
        $cid=I("get.cid");
        $listtree=select_category_by_cid('Site/Category', array('pid'=>array('eq',$cid)),array(),"id",array("root"=>"0"));

        $this->assign('title',"图片预览");
        $this->assign("type",$listtree["type"]);
        $this->assign('form', $listtree[$listtree["type"]]); //表单项目

        //根据ID查找是第几个
        $this->assign("id",I("get.id"));
        $builder = new \lyf\builder\FormBuilder();
        $builder->display();

    }
    /**
     * 下载
     * @author jry <7661660@qq.com>
     */
    public function download($token)
    {
        $this->is_login();

        if (empty($token)) {
            $this->error('token参数错误！');
        }

        //解密下载token
        $file_md5 = \lyf\Crypt::decrypt($token, user_md5(is_login()));
        if (!$file_md5) {
            $this->error('下载链接已过期，请刷新页面！');
        }

        $upload_object = D('Admin/Upload');
        $file_id       = $upload_object->getFieldByMd5($file_md5, 'id');
        if (!$upload_object->download($file_id)) {
            $this->error($upload_object->getError());
        }
    }
    /**
     * 下载
     * @author jry <7661660@qq.com>
     */
    public function downloadpsd($id)
    {
        $this->is_login();

        $psdmodle = D("Admin/Upload");
        $psddata = $psdmodle->find($id);

        $file_name = $psddata["name"];

        $file_dir = ".".$psddata["path"];        //下载文件存放目录

        $rpath=realpath($file_dir);
        //检查文件是否存在
        if (!file_exists($file_dir)) {
            header('HTTP/1.1 404 NOT FOUND');
        } else {
            //以只读和二进制模式打开文件
            $file = fopen($file_dir , "rb");

            //告诉浏览器这是一个文件流格式的文件
            Header("Content-type: application/octet-stream");
            //请求范围的度量单位
            Header("Accept-Ranges: bytes");
            //Content-Length是指定包含于请求或响应中数据的字节长度
            Header("Accept-Length: " . filesize($file_dir));
            //用来告诉浏览器，文件是可以当做附件被下载，下载后的文件名称为$file_name该变量的值。
            Header("Content-Disposition: attachment; filename=" . $file_name);

            //读取文件内容并直接输出到浏览器
            echo fread($file, filesize($file_dir));
            fclose($file);
            exit ();
        }
    }
    /**
     * KindEditor编辑器文件管理
     * @author jry <7661660@qq.com>
     */
    public function fileManager($only_image = true)
    {
        $uid = $this->is_login();
        exit(D('Admin/cUpload')->fileManager($only_image));
    }
}
