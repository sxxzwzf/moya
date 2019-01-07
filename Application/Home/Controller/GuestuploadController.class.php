<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace Home\Controller;
use Common\Controller\Controller;
/**
 * 上传控制器
 * @author jry <598821125@qq.com>
 */
class GuestuploadController extends  Controller
{
    /**
     * 上传
     * @author jry <598821125@qq.com>
     */
    public function upload()
    {

        $return = json_encode(D('Admin/Upload')->upload());
        exit($return);

    }

    /**
     * 下载
     * @author jry <598821125@qq.com>
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
     * KindEditor编辑器文件管理
     * @author jry <598821125@qq.com>
     */
    public function fileManager($only_image = true)
    {
        $uid = $this->is_login();
        exit(D('Admin/cUpload')->fileManager($only_image));
    }
}
