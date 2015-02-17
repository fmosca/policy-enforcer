<?php

use PolicyEnforcer\Lexer;

class LexerProxy extends Lexer 
{
    public function getType(&$value) 
    {
        return self::getType($value);
    }
}

