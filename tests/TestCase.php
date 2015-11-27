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
     * 
     * @param string $fileName
     * @return string
     */
    private function getAbsoluteFileName($fileName)
    {
        $parts = explode('_', get_class($this));
        $classNameWithoutTest = mb_substr($parts[1], 0, mb_strlen($parts[1]) - 4);
        
        return __DIR__ . '/' . $parts[0] . '/_files/' . $classNameWithoutTest . '/' . $fileName . '.inc';
    }
    
    /**
     * @dataProvider sniffProvider
     */
    public function testSniff($fileName, $expectedErrors)
    {
        $phpcs = new PHP_CodeSniffer();
        $phpcs->initStandard(
            __DIR__ . '/../Cloud9Software/ruleset.xml',
            array($this->sniffName));

        $phpcs->cli
            ->setCommandLineValues(
                array(
                    '-s'
                ));
        $file = $phpcs->processFile($this->getAbsoluteFileName($fileName));
        $errors = $file->getErrors();
        
        $transformedErrors = $this->getTransformedErrors($errors);
        
        $errorString = '';
        
        if ($transformedErrors) {
            $errorString .= $transformedErrors[0][2];
        }
        
        if (!$expectedErrors) {
            $this->assertEquals(0, $file->getErrorCount(), $errorString);
            return;
        }
        
        $this->assertEquals($expectedErrors, $transformedErrors);
    }
    
}