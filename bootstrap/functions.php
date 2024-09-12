<?php

/**
 * 获取追踪ID
 */
if (!function_exists('get_trace_id')) {
    function get_trace_id(): string
    {
        $traceId = request()->offsetGet('traceId');
        if (!isset($traceId)) {
            $traceId = md5(time() . mt_rand(100000, 999999));
            request()->offsetSet('traceId', $traceId);
        }
        return $traceId;
    }
}