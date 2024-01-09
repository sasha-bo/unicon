<?php

namespace Unicon\Unicon\Tools;


class VarPrinter
{
    private const KEYS_LIMIT = 10;
    public static function print(mixed $var): string
    {
        return match (true) {
            is_null($var) => 'null',
            is_int($var), is_float($var) => (string) $var,
            is_string($var) => '"'.$var.'"',
            is_bool($var) => $var ? 'true' : 'false',
            is_resource($var) => 'resource',
            is_callable($var) => 'callable',
            is_array($var) => self::printArray($var),
            is_object($var) => get_class($var),
            default => '???'
        };
    }

    /**
     * @param array<mixed> $array
     * @return string
     */
    public static function printArray(array $array): string
    {
        $ret = 'array with keys ';
        $cnt = 0;
        foreach ($array as $key => $value) {
            if ($cnt >= self::KEYS_LIMIT) {
                $ret .= ', ...';
                break;
            }
            $ret .= ($cnt > 0 ? ', ' : '').self::print($key);
            $cnt ++;
        }
        return $ret;
    }
}
