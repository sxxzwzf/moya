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
namespace Site\Model;

use Common\Model\Model;

/**
 * 主题模型
 * @author jry <7661660@qq.com>
 */
class ThemeModel extends Model
{
    /**
     * 数据库真实表名
     * 一般为了数据库的整洁，同时又不影响Model和Controller的名称
     * 我们约定每个模块的数据表都加上相同的前缀，比如微信模块用weixin作为数据表前缀
     * @author jry <7661660@qq.com>
     */
    protected $tableName = 'site_theme';

    /**
     * 自动验证规则
     * @author jry <7661660@qq.com>
     */
    protected $_validate = array(
        array('cid', 'require', '分类不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('name', 'require', '名称不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('title', 'require', '标题不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('cover', 'require', '封面不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('images', 'require', '预览图不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     * @author jry <7661660@qq.com>
     */
    protected $_auto = array(
        array('uid', 'is_login', self::MODEL_INSERT, 'function'),
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
        array('status', '1', self::MODEL_INSERT),
    );

    /**
     * 查找后置操作
     * @author jry <7661660@qq.com>
     */
    protected function _after_find(&$result, $options)
    {
        $result['user']               = D('Admin/User')->getUserInfo($result['uid']);
        $result['cover_url']          = get_cover($result['cover'], 'default');
        $result['create_time_format'] = time_format($result['create_time'], 'Y-m-d H:i:s');
    }

    /**
     * 查找后置操作
     * @author jry <7661660@qq.com>
     */
    protected function _after_select(&$result, $options)
    {
        foreach ($result as &$record) {
            $this->_after_find($record, $options);
        }
    }

    public function theme_list(){
        $tarr=$this->where('status=1')->select();
        $th_arr=array();
        foreach ($tarr as $k1 => $v1) {
            $th_arr[$v1["id"]]=$v1['name'];
        }
        return $th_arr;
    }
}
