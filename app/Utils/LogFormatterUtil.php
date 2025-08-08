<?php

namespace App\Utils;

use Monolog\Formatter\LineFormatter;
use Monolog\LogRecord;

class LogFormatterUtil extends LineFormatter
{
    public function __construct(?string $format = null, ?string $dateFormat = null, bool $allowInlineLineBreaks = true, bool $ignoreEmptyContextAndExtra = true, bool $includeStacktraces = true)
    {
        parent::__construct($format, $dateFormat, $allowInlineLineBreaks, $ignoreEmptyContextAndExtra, $includeStacktraces);
    }

    public function format(LogRecord $record): string
    {
        $traceId = get_trace_id();
        $format = "[%datetime%][{$traceId}][%level_name%]%message% %context% %extra%\n";

        // 调用父类format前，先临时设置格式，不改类属性
        $originalFormat = $this->format;
        $this->format = $format;
        $this->dateFormat = 'Y-m-d H:i:s';

        $result = parent::format($record);

        // 还原原始格式，避免影响后续日志
        $this->format = $originalFormat;

        return $result;
    }
}
