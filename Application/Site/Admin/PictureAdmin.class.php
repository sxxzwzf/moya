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
class PictureAdmin extends AdminController
{


    private $extra_html = <<<EOF
     <script language="javascript">
        $(function(){
              var displeft;
              var disptop;
            var offsetX=20-$(".builder-table").offset().left;
            var offsetY=20-$(".builder-table").offset().top;
            var winwidth=$(window).width();
            var winheight=$(window).height();
          
            var size=5*$('.picture').width();
            var height=5*$('.picture').height();
            
            $(".picture").mouseover(function(event) {
                var \$target=$(event.target);
                if(\$target.is('img'))
                {
                   var minlenght=Math.min(winheight,winwidth);
                    var wid=\$target.width();
                    var hei=\$target.height();
                    if(wid>=hei)
                        {
                            var dispwidth= minlenght/1.5;
                            var dispheight=dispwidth*hei/wid;
                            
                        }
                        else 
                        {
                             var dispheight= minlenght/1.5;
                             var dispwidth=dispwidth*hei/wid;
                         }
                         
                     displeft=event.pageX+offsetX;
                      disptop=event.pageY+offsetY;
                     if(dispheight+disptop-offsetY>winheight)
                         {
                             disptop=winheight-dispheight+offsetY-20;
                          console.log(disptop);  
                         }
                     
                    
                    $("<img id='tip' src='"+\$target.attr("src")+"'>").css({
                        "height":dispheight,
                        "width":dispwidth,
                        "top":disptop,
                        "left":displeft,
                    }).appendTo($(".builder-table"));
                }
            }).mouseout(function() {
                $("#tip").remove();
            }).mousemove(function(event) {
                $("#tip").css(
                    {
                        "left":displeft,
                        "top":disptop
                    });
            });
        })
    </script>
EOF;


    //编辑上传图片
    public function edit($id)
    {
        if (request()->isPost()) {
            // 密码为空表示不修改密码


            // 提交数据
            $picture_object = D('Site/picture');
            $data["id"]=I("id/d");
            $data["cid"]=I("cid/d");
            $data["name"]=I("name",'');
            $data["psdid"]=I("psdid","");

            //根据上传的psdid删除或者覆盖psd文件
            $picdata=$picture_object->where('id='.$data["id"])->find();
            if($picdata["psdid"])
            {
                if($data["psdid"]!=$picdata["psdid"])
                    {
                        //上传了不相同的psd删除原psd
                        $psddata=$picture_object->where('psdid='.$picdata["psdid"])->select();
                        if(count($psddata)==1)
                        {
                            $upload_info = D('Admin/Upload')->find($picdata["psdid"]);
                            if ($upload_info) {
                                $realpath = realpath('.' . $upload_info['path']);
                                if ($realpath) {
                                    array_map("unlink", glob($realpath));
                                    if (count(glob($realpath))) {
                                        //$this->error('删除失败！');
                                    } else {
                                        $resut = D('Admin/Upload')->delete($id);
                                        //$this->success('删除成功！');
                                    }
                                } else {
                                    $resut = D('Admin/Upload')->delete($id);

                                }
                            }
                        }

                    }
            }

            if ($data) {
                $result = $picture_object->save($data);
                if ($result) {
                    $this->success('更新成功', U('picture'));
                } else {
                    $this->error('更新失败：' . $picture_object->getError());
                }
            } else {
                $this->error($picture_object->getError());
            }
        } else {
            // 获取账号信息
            $info = D('Picture')->find($id);

            // 使用FormBuilder快速建立表单页面
            $builder = new \lyf\builder\FormBuilder();
            $builder->setMetaTitle('编辑用户') // 设置页面标题
            ->setPostUrl(U('edit')) // 设置表单提交地址
            ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('cid', 'select', '上级分类', '所属的上级分类', select_list_as_tree('Site/Category', array()), array('must' => 1))
                ->addFormItem('name', 'text', '名称', '名称', '', array('must' => 0))
                ->addFormItem('picid', 'image', '图片', '图片', '', array('must' => 0))
                ->addFormItem('create_time', 'time', '创建时间', '创建时间')
                ->addFormItem('download', 'static', '下载次数', '下载次数', '', array('must' => 0))

                ->addFormItem('psdid', 'pictureswithpsd', '上传psd', '上传psd',"",array("self"=>array("ext"=>"rar","size"=>"200")))
                ->setFormData($info)
                ->display();
        }
    }
public  function tools()
{
    set_time_limit(0);
    //改变png到jpg
    if(I("action")=="pngtojpg")
    {
        $picupload = D('Admin/Upload');
        $uploadfile=$picupload->where("ext='png'")->select();
        foreach ($uploadfile as $item){
            $realpath = realpath('.' . $item['path']);
            if($realpath)
            {
                if(png2jpg($realpath,true))
                {
                    $realpath=str_replace(".png",".jpg",$realpath);
                    $item['name']=str_replace(".png",".jpg",$item['name']);
                    $item['path']=str_replace(".png",".jpg",$item['path']);
                    $item['ext']="jpg";
                    $item['size']=filesize ( $realpath);
                    $item['md5']  = md5_file($realpath);
                    $item['sha1'] = sha1_file($realpath);
                    $picupload->save($item);
                    echo ("转换文件：".$realpath." 完成\br");
                }
                else
                {
                    echo ("转换文件：".$realpath." 不成功\br");
                }


            }

        }


        //picture内的文件名改名
        $dsitepic=D("Site/Picture");
        $sitepics=$dsitepic->where("name like'%png%'")->select();
        foreach ($sitepics as $item){
            $item["name"]=str_replace(".png",".jpg",$item["name"]);
            $dsitepic->save($item);
        }

    }

    //清除无用图片或文件
    if(I("action")=="cleanuploadfile")
    {
        $picupload = D('Admin/Upload');
        $uploadfile=$picupload->select();
        foreach ($uploadfile as $index=>&$item)
        {
            $uploadfile[$index]=$item["id"];
        }

        deleteUploadFiles($uploadfile);

        $this->success("成功处理完成");
    }

    $builder = new \lyf\builder\FormBuilder();
    $builder->setMetaTitle('图片工具') // 设置页面标题
    ->display();

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
            ->addFormItem('cid', 'select', '上级分类', '', select_list_as_tree('Site/Category', array()), array('must' => 1))
                //  ->addFormItem('title', 'text', '文章标题', '文章标题', '', array('must' => 1))
                // ->addFormItem('abstract', 'textarea', '文章简介', '文章简介')
                //  ->addFormItem('content', 'kindeditor', '文章内容', '文章内容', '', array('must' => 1, 'self' => array('upload_driver' => C('site_config.upload_driver') ?: 'Qiniu')))
                ->addFormItem('picids', 'pictures', '多图上传', '', null, array('must' => 1, 'self' => array('upload_driver' => C('site_config.upload_driver') ?: 'Qiniu')))
                //    ->addFormItem('banner', 'picture_temp', 'Banner图片', 'Banner图片', null, array('self' => array('upload_driver' => C('site_config.upload_driver') ?: 'Qiniu')))
                //   ->addFormItem('detail_template', 'select', '详情模版', '文章详情页模版', $template_detail)
                ->display();
        }



    }

    public function  changeCatagory()
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
            ->addFormItem('cid', 'select', '上级分类', '', select_list_as_tree('Site/Category', array()), array('must' => 1))
                //  ->addFormItem('title', 'text', '文章标题', '文章标题', '', array('must' => 1))
                // ->addFormItem('abstract', 'textarea', '文章简介', '文章简介')
                //  ->addFormItem('content', 'kindeditor', '文章内容', '文章内容', '', array('must' => 1, 'self' => array('upload_driver' => C('site_config.upload_driver') ?: 'Qiniu')))
                ->addFormItem('picids', 'pictures', '多图上传', '', null, array('must' => 1, 'self' => array('upload_driver' => C('site_config.upload_driver') ?: 'Qiniu')))
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

        $rowsperpage=I("perpage",C('ADMIN_PAGE_ROWS'));
        $cid=I("cid");
        $createtime=I('createtime');
        $keyword   = I('keyword', '', 'string');
        if($cid)
        {
            $condition = array('EQ',$cid);
            $map['cid'] = array($condition);
        }
         if($createtime)
         {
             $condition =  array(array('gt',strtotime($createtime)),array('lt',strtotime("+1 day"), 'and')) ;
             $map['create_time'] = array($condition);
         }
        $conditionkeyword = array('like', '%' . $keyword . '%');

        $map['name'] = array($conditionkeyword);

        $sitepicture = D('Site/Picture');
        $p  = !empty($_GET["p"]) ? $_GET['p'] : 1;

        //获取所有上传
        $map['status'] = array('egt', '0'); //禁用和正常状态
        $data_list     = $sitepicture
            ->page($p, $rowsperpage)
            ->where($map)
            ->order('create_time desc,sort desc,id desc')
           // ->fetchsql()
            ->select();

        $page = new Page(
            $sitepicture->where($map)->count(),
            $rowsperpage
        );

        $uploadfile = D('Admin/Upload');
        $categary=D('Site/Category');

        foreach ($data_list as &$data) {
            $condition1["id"]=$data["picid"];
            $picdata=$uploadfile->where($condition1)->find();
            // 获取文件地址
            if ($picdata['url']) {
                $data['url_result'] = $picdata['url'];
            } else {
                $data['url_result'] = C('TOP_HOME_PAGE') . $picdata['path'];
            }
            if (in_array(strtolower($picdata['ext']), array('jpg', 'jpeg', 'png', 'gif', 'bmp'))) {
                $data['show'] = '<img class="picture" src="' . $picdata['url_result'] . '">';
            } else {
                $data['show'] = '<i class="fa fa-file-' . $picdata['ext'] . '"></i>';
            }

            $data['name'] = cut_str($data['name'], 0, 30)
                . '<input readonly class="form-control input-sm" value="'
                . $picdata['path'] . '">';

            $data["picsize"]=$picdata["size"];
            $data["piccreate_time"]=$picdata["create_time"];

            $psdcondition["id"]=$data["psdid"];
            $psddata=$uploadfile->where($psdcondition)->find();
            $data["psdname"]=cut_str($psddata['name'], 0, 30)
                . '<input readonly class="form-control input-sm" value="'
                . $psddata['path'] . '">';
            $data["psdsize"]=$psddata["size"];
            $data["psdcreate_time"]=$psddata["create_time"];

            $condition1["id"]=$data["cid"];
            $picdata=$categary->where($condition1)->find();
            $data["cidname"]=$picdata["title"];

            $condition1["id"]=$data["uid"];
            $picdata=D('Admin/User')->where($condition1)->find();
            $data["uidname"]=$picdata["nickname"];

        }
        $this->assign("formoptions",select_list_as_tree('Site/Category', array()));


        // 使用Builder快速建立列表页面
        $builder = new \lyf\builder\ListBuilder();
        $builder->setMetaTitle('上传列表') // 设置页面标题

            ->addTopButton('addnew') // 添加新增按钮
            // ->addTopButton('resume') // 添加启用按钮
            // ->addTopButton('forbid') // 添加禁用按钮
            ->addTopButton('delete') // 添加删除按钮
            ->addTopButton('changecatagory') // 添加改分类按钮
            ->setsearch_form_items('cid', 'select', '分类', '所属的上级分类', select_list_as_tree('Site/Category', array()), array('must' => 0),U("picture/picture"),U('picture'))
            ->setsearch_form_items('createtime', 'date', '上传日期')
            ->addTableColumn('id', 'ID')
            ->addTableColumn('show', '图片')
            ->addTableColumn('name', '图片名及路径')
            ->addTableColumn('picsize', '大小', 'byte')
            ->addTableColumn('create_time', '创建时间', 'time')
            ->addTableColumn('psdname', 'psd文件名及路径')
            ->addTableColumn('psdsize', 'psd大小', 'byte')
            ->addTableColumn('psdcreate_time', 'psd创建时间', 'time')
            ->addTableColumn('cidname', '所属类别')
            ->addTableColumn('uidname', '上传人')
            //->addTableColumn('sort', '排序')
            // ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('edit') // 添加禁用/启用按钮
            ->addRightButton('delete') // 添加删除按钮
            ->setExtraHtml($this->extra_html)
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

        if ('' == $model) {
            $model = request()->controller();
        }
        $ids    = I('request.ids');
        $status = I('request.status');
        if (empty($ids)) {
            $this->error('请选择要操作的数据');
        }
        switch ($status) {
            case 'delete': // 删除条目changecatagory
                $mpicture=D("Site/Picture");
                if($mpicture->deletepictures($model,I('request.ids'),I('request.status')))
                {
                    $this->success("删除成功");
                }
                else
                {
                    $this->error("删除失败");
                }
                break;
            case 'changecatagory': // 更改分类

                $cid=I("parent_id");
                $picture_model = D('Site/picture');
                $data['cid'] = $cid;
                $where=array();
                foreach ($ids as $key=>$value )
                {
                    $where[$key]["id"]=$value;

                }
                $where["_logic"]="or";
                $ret=$picture_model->where($where)->setField($data);

                if($ret)
                {
                    $this->success("更新成功");
                }
                else{
                    $this->error("更新失败");
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
