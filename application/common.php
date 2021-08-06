<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * 返回信息
 * @param  bool $rst 处理结果
 * @param  string $msg 要返回的提示语
 * @return json
 */
function response($code, $msg, $data=[])
{
    if (empty($data)) {
        return json(['code' => (int)$code, 'message' => $msg]);
    } else {
        return json(['code' => (int)$code, 'message' => $msg, 'data' => $data]);
    }
}