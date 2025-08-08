<?php

if (!function_exists('get_trace_id')) {
    /**
     * 获取追踪ID
     * @return string
     */
    function get_trace_id(): string
    {
        static $traceId = null;
        static $lastRequestId = null;

        // 判断当前是否是 HTTP 请求环境
        if (!app()->runningInConsole()) {
            $request = app('request');

            // 用请求的唯一 ID（比如内存地址或请求对象哈希）判断请求是否变化
            $currentRequestId = spl_object_id($request);

            // 请求变了，清空缓存traceId
            if ($lastRequestId !== $currentRequestId) {
                $lastRequestId = $currentRequestId;
                $traceId = null;
            }

            if ($traceId === null) {
                // 绑定到容器方便全局使用
                $traceId = (string)\Illuminate\Support\Str::ulid();
                app()->instance('trace_id', $traceId);
            }
            return $traceId;
        }

        // CLI 环境，保持一个静态traceId，方便脚本内复用
        if ($traceId === null) {
            $traceId = (string)\Illuminate\Support\Str::ulid();
        }
        return $traceId;
    }
}
