<?php

require_once '/usr/local/lib/php-libs/composer/vendor/autoload.php';
require_once __DIR__ . '/../../HappyCustomer/Sniffs/WhiteSpace/ArrayMembersSniff.php';

class WhiteSpace_ArrayMembersTest extends PHPUnit_Framework_TestCase
{
    
    /**
     * 
     * @param array $errors
     * @return array
     */
    private function getTransformedErrors(array $errors)
    {
        $return = array();
        
        foreach ($errors as $line => $lineErrors) {
            foreach ($lineErrors as $col => $colErrors) {
                foreach ($colErrors as $error) {
                    $return[] = array(
                        $line,
                        $col,
                        $error['message']
                    );
                }
            }
        }
        
        return $return;
    }
    
    /**
     * @dataProvider sniffProvider
     */
    public function testSniff($fileName, $expectedErrors)
    {
        $phpcs = new PHP_CodeSniffer();
        $phpcs->initStandard(
            __DIR__ . '/../../HappyCustomer/ruleset.xml',
            array('HappyCustomer.WhiteSpace.ArrayMembers'));

        $phpcs->cli
            ->setCommandLineValues(
                array(
                    '-s'
                ));
        $file = $phpcs->processFile($fileName);
        $errors = $file->getErrors();
        
        if (!$expectedErrors) {
            $this->assertEquals(0, $file->getErrorCount());
            return;
        }
        
        $transformedErrors = $this->getTransformedErrors($errors);
        $this->assertEquals($expectedErrors, $transformedErrors);
    }
    
    public function sniffProvider()
    {
        return [
            'empty method' => [__DIR__ . '/_files/ArrayMembers/empty-method.php', []],
            'empty array declaration' => [__DIR__ . '/_files/ArrayMembers/empty-array-declaration.php', []],
            'multi line array declaration' => [__DIR__ . '/_files/ArrayMembers/multi-line-array.php', []],
            'single line array hanging comma' => [
                __DIR__ . '/_files/ArrayMembers/single-line-array-hanging-comma.php',
                [
                    [8, 22, 'Array members must be separated by a single space or a line-break']
                ]
            ],
            'multi line array hanging comma' => [
                __DIR__ . '/_files/ArrayMembers/multi-line-array-hanging-comma.php',
                [
                    [
                        12,
                        9,
                        'Indent incorrect; expected 12, found 8 (members of multi-line '
                        . 'array declaration must be one per line, with no trailing comma)']
                ]
            ],
            'multi line array incorrect indent' => [
                __DIR__ . '/_files/ArrayMembers/multi-line-incorrect-indent.php',
                [
                    [
                        10,
                        9,
                        'Indent incorrect; expected 12, found 8 (members of multi-line '
                        . 'array declaration must be one per line, with no trailing comma)']
                ]
            ]
        ];
    }
    
}