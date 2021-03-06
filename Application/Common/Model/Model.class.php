<?php
// +----------------------------------------------------------------------
// | 魔娅 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.ffgame.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <7661660@qq.com>
// +----------------------------------------------------------------------
namespace Common\Model;

/**
 * 公共模型
 * @author jry <7661660@qq.com>
 */
class Model extends \lyf\Model
{
    // 禁止写入
    protected $forbidWrite    = false;
    protected $forbidWriteMsg = '演示模式已关闭数据库写入功能';

    /**
     * 初始化方法
     */
    protected function _initialize()
    {
        $this->forbidWrite = C('APP_DEMO');
    }
}
