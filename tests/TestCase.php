<?php

require_once '/usr/local/lib/php-libs/composer/vendor/autoload.php';

abstract class TestCase extends PHPUnit_Framework_TestCase
{
    
    protected $sniffName = '';
    
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
            __DIR__ . '/../HappyCustomer/ruleset.xml',
            array($this->sniffName));

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
    
}