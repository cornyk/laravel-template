<?php

use App\Commons\RespDef;
use App\Utils\RespUtil;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * @var Illuminate\Foundation\Configuration\Exceptions $exceptions
 */
$exceptions->report(function (\Throwable $e) {
    // 如果异常抛出时在事务中，需要手动回滚事务
    if (DB::transactionLevel() > 0) {
        DB::rollBack();
    }

    if ($e instanceof PDOException) {
        Log::channel('sql_error')->error($e->getMessage() . "\n" . $e);
    } else {
        Log::channel('error')->error($e->getMessage() . "\n" . $e);
    }
});

$exceptions->render(function (\Throwable $e) {
    if ($e instanceof NotFoundHttpException) {
        return RespUtil::json(RespDef::CODE_NO_API, RespDef::MSG_NO_API, null, 404);
    }

    if ($e instanceof PDOException) {
        return RespUtil::json(RespDef::CODE_DB_ERROR, $e->getMessage(), null, 400);
    }

    return RespUtil::json($e->getCode() ?: RespDef::CODE_SYSTEM_ERROR, $e->getMessage());
});
