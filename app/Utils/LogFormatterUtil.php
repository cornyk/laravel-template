<?php

namespace App\Utils;

use Monolog\Formatter\LineFormatter;

class LogFormatterUtil extends LineFormatter
{
    public function __construct(?string $format = null, ?string $dateFormat = null, bool $allowInlineLineBreaks = true, bool $ignoreEmptyContextAndExtra = true, bool $includeStacktraces = true)
    {
        $traceId = get_trace_id();
        $format = "[%datetime%][{$traceId}][%level_name%]%message% %context% %extra%\n";
        $dateFormat = 'Y-m-d H:i:s';

        parent::__construct($format, $dateFormat, $allowInlineLineBreaks, $ignoreEmptyContextAndExtra, $includeStacktraces);
    }
}
