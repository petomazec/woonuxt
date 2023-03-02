<?php

declare(strict_types=1);

namespace RedisCachePro\Loggers;

/**
 * @deprecated 1.18.0
 * @see \RedisCachePro\Loggers\CallbackLogger
 */
class BacktraceLogger extends Logger
{
    /**
     * Logs with an arbitrary level.
     *
     * @deprecated 1.18.0
     * @see \RedisCachePro\Loggers\CallbackLogger
     *
     * @param  mixed  $level
     * @param  string  $message
     * @param  array<mixed>  $context
     * @return void
     */
    public function log($level, $message, array $context = [])
    {
        \error_log(
            "objectcache.{$level}: {$message} [Backtrace not available]"
        );
    }
}
