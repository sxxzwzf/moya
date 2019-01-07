<?php
// +----------------------------------------------------------------------
// | 魔娅 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.ffgame.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <7661660@qq.com>
// +----------------------------------------------------------------------
namespace Site\Admin;

use Admin\Controller\AdminController;
use lyf\Page;

/**
 * 后台上传控制器
 * @author jry <7661660@qq.com>
 */
class ZhijianAdmin extends AdminController
{

    //编辑上传图片
    public function edit($id)
    {
        if (request()->isPost()) {
            // 密码为空表示不修改密码


            // 提交数据
            $user_object = D('Site/picture');
            $data["id"]=I("id/d");
            $data["cid"]=I("cid/d");
            $data["name"]=I("name",'');
            $data["psdid"]=I("psdid","");


            if ($data) {
                $result = $user_object->save($data);
                if ($result) {
                    $this->success('更新成功', U('picture'));
                } else {
                    $this->error('更新失败：' . $user_object->getError());
                }
            } else {
                $this->error($user_object->getError());
            }
        } else {
            // 获取账号信息
            $info = D('Picture')->find($id);

            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('编辑用户') // 设置页面标题
            ->setPostUrl(U('edit')) // 设置表单提交地址
            ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('cid', 'select', '上级分类', '所属的上级分类', select_list_as_tree('Site/Category', array(),'','id',array('root'=>16)), array('must' => 1))
                ->addFormItem('name', 'text', '名称', '名称', '', array('must' => 0))
                ->addFormItem('picid', 'image', '图片', '图片', '', array('must' => 0))
                ->addFormItem('create_time', 'time', '创建时间', '创建时间')
                ->addFormItem('download', 'static', '下载次数', '下载次数', '', array('must' => 0))

                ->addFormItem('psdid', 'pictureswithpsd', '上传psd', '上传psd',"",array("self"=>array("ext"=>"rar","size"=>"200")))
                ->setFormData($info)
                ->display();
        }
    }


    /**
    *新增图片
     * * @author jry <7661660@qq.com>
    */

    public function  add()
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

                }
                 else {
                     $this->error($picture_model->getError());
                  }

            }
             if ($id)
             {
                    $this->success('新增成功', U('picture', array('cid' => $cid)));
             } else {
                          $this->error('新增失败' . $picture_model->getError());
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
            ->addFormItem('cid', 'select', '上级分类', '所属的上级分类', select_list_as_tree('Site/Category', array(),'','id',array('root'=>16)), array('must' => 1))
              //  ->addFormItem('title', 'text', '文章标题', '文章标题', '', array('must' => 1))
               // ->addFormItem('abstract', 'textarea', '文章简介', '文章简介')
              //  ->addFormItem('content', 'kindeditor', '文章内容', '文章内容', '', array('must' => 1, 'self' => array('upload_driver' => C('site_config.upload_driver') ?: 'Qiniu')))
                ->addFormItem('picids', 'pictures', '多图上传', '多图上传', null, array('must' => 1, 'self' => array('upload_driver' => C('site_config.upload_driver') ?: 'Qiniu')))
            //    ->addFormItem('banner', 'picture_temp', 'Banner图片', 'Banner图片', null, array('self' => array('upload_driver' => C('site_config.upload_driver') ?: 'Qiniu')))
             //   ->addFormItem('detail_template', 'select', '详情模版', '文章详情页模版', $template_detail)
                ->display();
        }



    }


    /**
     * 图片列表管理
     * @author jry <7661660@qq.com>
     */
    public function picture()
    {


            $cid=I("cid");
            $keyword   = I('keyword', '', 'string');
            if($cid)
            {
                $condition = array('EQ',$cid);
                $map['cid'] = array($condition);
            }



        //搜索
       // $condition[] = array('like', '%' . $keyword . '%');

     /*   $map['adminpic.id'] = array($condition);

        //获取所有上传
        $map['adminpic.status'] = array('egt', '0'); //禁用和正常状态


        $data_list     = $sitepicture
            ->page($p, C('ADMIN_PAGE_ROWS'))
            ->where($map)
            ->order('adminpic.sort desc,adminpic.id desc')
          ->join("ly_admin_uploadfile as adminpic ON adminpic.id=ly_site_picture.picid")
           ->join("LEFT JOIN ly_admin_uploadfile as adminpsd ON adminpsd.id=ly_site_picture.psdid")
           ->fetchsql()
            ->select();
        //trace($data_list);


        unset($map['adminpic.id']);

        //获取所有上传
        unset($map['adminpic.status']) ; //禁用和正常状态
        */
        $sitepicture = D('Site/Picture');
        $p             = !empty($_GET["p"]) ? $_GET['p'] : 1;

        //获取所有上传
        $map['status'] = array('egt', '0'); //禁用和正常状态
        $data_list     = $sitepicture
            ->page($p, C('ADMIN_PAGE_ROWS'))
            ->where($map)
            ->order('sort desc,id desc')
           // ->fetchsql()
            ->select();

       $page = new Page(
            $sitepicture->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );
        //获得数据列表数据
        /*
            $data_list=M("site_picture");
            $data1= $data_list->alias('sitepicture')
            ->where('sitepicture.status>=0')

            ->join("ly_admin_uploadfile as adminpic ON adminpic.id=sitepicture.picid")
            ->join("LEFT JOIN ly_admin_uploadfile as adminpsd ON adminpsd.id=sitepicture.psdid")
            ->fetchsql()
            ->select();
        */
        $uploadfile = D('Admin/Upload');
        $categary=D('Site/Category');


        foreach ($data_list as &$data) {



            $condition1["id"]=$data["picid"];
            $data1=$uploadfile->where($condition1)->find();
            // 获取文件地址
            if ($data1['url']) {
                $data['url_result'] = $data1['url'];
            } else {
                $data['url_result'] = C('TOP_HOME_PAGE') . $data1['path'];
            }
            if (in_array($data1['ext'], array('jpg', 'jpeg', 'png', 'gif', 'bmp'))) {
                $data['show'] = '<img class="picture" src="' . $data1['url_result'] . '">';
            } else {
                $data['show'] = '<i class="fa fa-file-' . $data1['ext'] . '"></i>';
            }

           /* $data['name'] = cut_str($data['name'], 0, 30)
                . '<input class="form-control input-sm" value="'
                . $data1['path'] . '">';*/

            $data['name'] = cut_str($data['name'], 0, 30)
                . '<input class="form-control input-sm" value="'
                . $data1['path'] . '">';

            $data["picsize"]=$data1["size"];
            $data["piccreate_time"]=$data1["create_time"];

            $condition1["id"]=$data["psdid"];
            $data1=$uploadfile->where($condition1)->find();
            $data["psdname"]=$data1["name"];
            $data["psdsize"]=$data1["size"];
            $data["psdcreate_time"]=$data1["createtime"];

            $condition1["id"]=$data["cid"];
            $data1=$categary->where($condition1)->find();
            $data["cidname"]=$data1["title"];

        }


        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('上传列表') // 设置页面标题

        ->addTopButton('addnew') // 添加新增按钮
       // ->addTopButton('resume') // 添加启用按钮
       // ->addTopButton('forbid') // 添加禁用按钮
        ->addTopButton('delete') // 添加删除按钮
        ->setsearch_form_items('cid', 'select', '分类', '所属的上级分类', select_list_as_tree('Site/Category', array(),'','id',array('root'=>16)), array('must' => 0),U("picture/picture"),U('picture'))
            ->addTableColumn('id', 'ID')
            ->addTableColumn('show', '文件')
            ->addTableColumn('name', '图片名及路径')
            ->addTableColumn('picsize', '大小', 'byte')
            ->addTableColumn('piccreate_time', '创建时间', 'time')
            ->addTableColumn('psdname', 'psd文件名及路径')
            ->addTableColumn('psdsize', 'psd大小', 'byte')
            ->addTableColumn('psdcreate_time', 'psd创建时间', 'time')
            ->addTableColumn('cidname', '所属类别')
            //->addTableColumn('sort', '排序')
           // ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('edit') // 添加禁用/启用按钮
            ->addRightButton('delete') // 添加删除按钮
            ->display();
    }

    public  function picture_add()
    {

    }
    /**
     * 设置一条或者多条数据的状态
     * @author jry <7661660@qq.com>
     */
    public function setStatus($model = '', $strict = null)
    {
        $mpicture=D("Site/Picture");
        if($mpicture->deletepictures($model,I('request.ids'),I('request.status')))
        {
            $this->success("删除成功");

        }
        else
        {
            $this->error("删除失败");
        }

        /*
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
                        $realpath = realpath('.' . $upload_info['path']);
                        if ($realpath) {
                            array_map("unlink", glob($realpath));

                            if (count(glob($realpath))) {
                                $this->error('删除失败！');
                            } else {
                                $resut = D('Upload')->delete($id);
                                $this->success('删除成功！');
                            }
                        } else {
                            $resut = D('Upload')->delete($id);
                            $this->success('删除成功！');
                        }
                    }
                }
                break;
            default:
                parent::setStatus($model);
                break;
        }
        */
    }

    /**
     * 上传
     * @author jry <7661660@qq.com>
     */
    public function upload()
    {
        $return = json_encode(D('Upload')->upload());
        exit($return);
    }

    /**
     * 下载
     * @author jry <7661660@qq.com>
     */
    public function download($token)
    {
        if (empty($token)) {
            $this->error('token参数错误！');
        }

        //解密下载token
        $file_md5 = \lyf\Crypt::decrypt($token, user_md5(is_login()));
        if (!$file_md5) {
            $this->error('下载链接已过期，请刷新页面！');
        }

        $sitepicture = D('Upload');
        $file_id       = $sitepicture->getFieldByMd5($file_md5, 'id');
        if (!$sitepicture->download($file_id)) {
            $this->error($sitepicture->getError());
        }
    }

    /**
     * KindEditor编辑器文件管理
     * @author jry <7661660@qq.com>
     */
    public function fileManager()
    {
        exit(D('Upload')->fileManager());
    }
}
