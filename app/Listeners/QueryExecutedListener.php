<?php

namespace App\Listeners;

use DateTime;
use Exception;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Log;

class QueryExecutedListener
{
    /** @var int 慢sql最小时长（微秒） */
    const SLOW_SQL_MIN_MILLISECONDS = 1000;

    public function handle(QueryExecuted $event)
    {
        try {
            $sql = str_replace("?", "'%s'", $event->sql);
            foreach ($event->bindings as $i => $binding) {
                if ($binding instanceof DateTime) {
                    $event->bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
                } else {
                    if (is_string($binding)) {
                        $event->bindings[$i] = "$binding";
                    }
                }
            }
            $sql = str_replace('%Y', '%%Y', $sql);
            $sql = str_replace('%m', '%%m', $sql);
            $sql = str_replace('%d', '%%d', $sql);

            $log = vsprintf($sql, $event->bindings);
            $log = str_replace("\\", "", $log);
            $log = '[' . $event->time . 'ms] ' . $log;
            Log::channel('sql')->info($log);
            // 记录慢sql
            if ($event->time > self::SLOW_SQL_MIN_MILLISECONDS) {
                Log::channel('sql_slow')->info($log);
            }
        } catch (Exception $e) {
            Log::channel('error')->error($e->getMessage());
        }
    }
}
