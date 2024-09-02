<?php

namespace App\Utils;

use App\Commons\RespDef;
use Illuminate\Http\JsonResponse;

class RespUtil
{

    public static function sucJson($data = null): JsonResponse
    {
        return self::json(RespDef::CODE_SUCCESS, RespDef::MSG_SUCCESS, $data);
    }

    /**
     * Json数据返回
     * @param int $code
     * @param string $msg
     * @param object|array|null $data
     * @param int $httpStatus
     * @return JsonResponse
     */
    public static function json(int $code, string $msg, object|array|null $data = null, int $httpStatus = 200): JsonResponse
    {
        return response()->json([
            'code' => $code,
            'message' => $msg,
            'data' => isset($data) ? (object)$data : null,
        ], $httpStatus);
    }
}
