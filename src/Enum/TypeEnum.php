<?php
declare(strict_types=1);

namespace Zakirullin\Mess\Enum;

final class TypeEnum
{
    public const INT = 'int';
    public const BOOL = 'bool';
    public const STRING = 'string';
    public const OBJECT = 'object';
    public const ARRAY = 'array';
    public const LIST_OF_INT = 'list<int>';
    public const LIST_OF_STRING = 'list<string>';
    public const ARRAY_OF_STRING_TO_INT = 'array<string,int>';
    public const ARRAY_OF_STRING_TO_BOOL = 'array<string,bool>';
    public const ARRAY_OF_STRING_TO_STRING = 'array<string,string>';
}