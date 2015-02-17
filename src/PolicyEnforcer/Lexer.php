<?php

namespace PolicyEnforcer;

use Doctrine\Common\Lexer\AbstractLexer;

class Lexer extends AbstractLexer
{
    const T_NONE   = 1;
    const T_MODULE = 2;
    const T_ALLOW  = 3;
    const T_DENY   = 4;
    const T_STRING = 5;
    const T_COMMA  = 6;

    protected function getCatchablePatterns()
    {
        return array(
            '[a-zA-Z0-9_]*[a-z0-9]{1}',
            '[^\s.]',
            "'(?:[^']|'')*'",
            '\"(?:[^\"]|\"\")*\"'
        );
    }

    protected function getNonCatchablePatterns()
    {
        return array('\s+');
    }

    protected function getType(&$value)
    {
        $type = self::T_NONE;

        switch (true) {
        case (in_array(strtolower($value), array('module', 'allow', 'deny'))):
            return constant("PolicyEnforcer\Lexer::T_" . strtoupper($value));
        case ($value[0] === "'"):
                $value = str_replace("''", "'", substr($value, 1, strlen($value) - 2));
                return self::T_STRING;
        case ($value[0] === '"'):
                $value = str_replace('""', '"', substr($value, 1, strlen($value) - 2));
                return self::T_STRING;
        case ($value === ','):
            return self::T_COMMA;
        case (ctype_alpha($value[0])):
            return self::T_STRING;
        }

        return $type;

    }
    
}
