<?php
declare(strict_types=1);

namespace Zakirullin\TypedAccessor;

use function array_keys;
use function count;
use function filter_var;
use function is_array;
use function is_bool;
use function is_int;
use function is_string;
use function range;
use const FILTER_VALIDATE_INT;

final class Caster
{
    public static function toInt($value): ?int
    {
        if (is_bool($value)) {
            return null;
        }

        $intValue = filter_var($value, FILTER_VALIDATE_INT);
        if ($intValue === false) {
            return null;
        }

        return $intValue;
    }

    public static function toBool($value): ?bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if ($value === 'true') {
            return true;
        }

        if ($value === 'false') {
            return false;
        }

        $intValue = $this->toInt($this->value);
        if ($intValue === 1) {
            return true;
        }
        if ($intValue === 0) {
            return false;
        }

        return null;
    }

    public static function toString($value): ?string
    {
        if (is_string($value)) {
            return $value;
        }

        if (is_int($value)) {
            return (string) $value;
        }

        return null;
    }

    public static function toListOfInt($value): ?array
    {
        return self::toListOfCasted($value, [$this, 'toInt']);
    }

    public static function toListOfString($value): ?array
    {

    }

    public static function toMapOfStringToInt($value): ?array
    {
        return self::toMapOfStringToCasted($value, [$this, 'toInt']);
    }

    public static function toMapOfStringToBool($value): ?array
    {
        return self::toMapOfStringToCasted($value, [$this, 'toBool']);
    }

    public static function toMapOfStringToString($value): ?array
    {
        return self::toMapOfStringToCasted($value, [$this, 'toString']);
    }

    public static function toMapOfStringToMixed($value): ?array
    {
        $array = self::toArray($value);
        if ($array === null) {
            return null;
        }

        foreach ($value as $key => $val) {
            if (!is_string($key)) {
                return null;
            }
        }

        return $value;
    }

    public function toArray($value): ?array
    {
        if (is_array($value)) {
            return $value;
        }

        return null;
    }

    private function toList($value): ?array
    {
        $array = $this->toArray($value);
        if ($array === null) {
            return null;
        }

        if (empty($array)) {
            return [];
        }

        /**
         * @psalm-var list
         */
        $keys = array_keys($array);
        $isList = $keys === range(0, count($array) - 1);
        if (!$isList) {
            return null;
        }

        /**
         * @psalm-var list
         */

        return $array;
    }

    private function toListOfCasted($value, callable $caster): ?array
    {
        $list = $this->toList($value);
        if ($list === null) {
            return null;
        }

        $listOfCasted = [];
        /**
         * @psalm-suppress all
         */
        foreach ($list as $val) {
            $castedValue = $caster($val);
            if ($castedValue === null) {
                return null;
            }

            $listOfInt[] = $castedValue;
        }

        return $listOfCasted;
    }

    /**
     * @param          $value
     * @param callable $caster
     * @return array|null
     */
    private function toMapOfStringToCasted($value, callable $caster): ?array
    {
        $mapOfStringToMixed = self::toMapOfStringToMixed($value);
        if ($mapOfStringToMixed === null) {
            return null;
        }

        $mapOfStringToCasted = [];
        foreach ($value as $key => $val) {
            $castedValue = $caster($val);
            if ($castedValue === null) {
                return null;
            }

            $mapOfStringToCasted[$key] = $castedValue;
        }

        return $mapOfStringToCasted;
    }
}