<?php

use PolicyEnforcer\Lexer;


class LexerTest extends \PHPUnit_Framework_TestCase
{
    protected static function getLexerMethod($name) {
        $class = new ReflectionClass('PolicyEnforcer\Lexer');
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }


    /**
     * @dataProvider tokenProvider
     */
    public function test_identifies_correct_types($type, $token)
    {
        $lexer = new Lexer();
        $getType = self::getLexerMethod('getType');
        $result = $getType->invokeArgs($lexer, array(&$token));
        $this->assertEquals($type, $result);
    }

    public function tokenProvider()
    {
        return array(
            array(Lexer::T_MODULE, 'module'),
            array(Lexer::T_ALLOW, 'aLLow'),
            array(Lexer::T_DENY, 'DENY'),
            array(Lexer::T_COMMA, ','),
            array(Lexer::T_STRING, 'foobar'),
            array(Lexer::T_STRING, "'quoted foobar'"),
            array(Lexer::T_STRING, '"doubly quoted foobar"'),
        );
    }

    public function test_base_lexer_example()
    {
        $sample = <<<EOT
Module test
    Allow user pippo
EOT;
        $lexer = new Lexer();
        $lexer->setInput($sample);
        $count = 0;
        while($lexer->moveNext()) {
            $count++;
        }

        $this->assertEquals(5, $count);
    }

    public function test_lexes_input_with_commas()
    {
        $sample = <<<EOT
Module test
Allow user foo, bar
EOT;
        $lexer = new Lexer();
        $lexer->setInput($sample);
        $lexer->moveNext();

        $lexer->skipUntil(Lexer::T_COMMA);
        $this->assertNotNull($lexer->lookahead);
    }
    
}
