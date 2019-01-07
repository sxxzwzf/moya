<?php
// +----------------------------------------------------------------------
// | 魔娅 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.ffgame.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <59821125@qq.com>
// +----------------------------------------------------------------------
namespace lyf;

/**
 * Gps相关处理方法
 * @author jry <7661660@qq.com>
 */
class Gps
{
    /**
     * 点是否在多边形内部
     * @author jry <7661660@qq.com>
     */
    public function isPointInPolygon($polygon, $lnglat)
    {
        $count = count($polygon);
        $px    = $lnglat['lng'];
        $py    = $lnglat['lat'];
        $flag  = false;
        for ($i = 0, $j = $count - 1; $i < $count; $j = $i, $i++) {
            $sy = $polygon[$i]['lng'];
            $sx = $polygon[$i]['lat'];
            $ty = $polygon[$j]['lng'];
            $tx = $polygon[$j]['lat'];
            if ($px == $sx && $py == $sy || $px == $tx && $py == $ty) {
                return true;
            }

            if ($sy < $py && $ty >= $py || $sy >= $py && $ty < $py) {
                $x = $sx + ($py - $sy) * ($tx - $sx) / ($ty - $sy);
                if ($x == $px) {
                    return true;
                }

                if ($x > $px) {
                    $flag = !$flag;
                }

            }
        }
        return $flag;
    }
}
