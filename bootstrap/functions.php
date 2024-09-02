<?php

/**
 * 获取追踪ID
 */
if (!function_exists('get_trace_id')) {
    function get_trace_id(): string
    {
        if (empty($GLOBALS['traceId'])) {
            $GLOBALS['traceId'] = md5(time() . mt_rand(100000, 999999));
        }
        return $GLOBALS['traceId'];
    }
}
