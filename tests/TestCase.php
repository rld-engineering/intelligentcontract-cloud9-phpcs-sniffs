<?php

declare(strict_types = 1);

namespace Cloud9Software\Sniffs;

require_once __DIR__ . '/TestConfiguration.php';

use PHPUnit\Framework\Attributes\DataProvider;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    
    /**
     * 
     * @param array $errors
     * @return array
     */
    private function getTransformedErrors(array $errors): array
    {
        $return = array();
        
        foreach ($errors as $line => $lineErrors) {
            foreach ($lineErrors as $col => $colErrors) {
                foreach ($colErrors as $error) {
                    $return[] = [
                        $line,
                        $col,
                        $error['message']
                    ];
                }
            }
        }
        
        return $return;
    }

    private function classNameWithoutTest(): string
    {
        $parts = explode("\\", get_class($this));
        $partCount = count($parts);
        return mb_substr(
            $parts[$partCount - 1],
            0,
            mb_strlen($parts[$partCount - 1]) - 4);
    }

    private function testNamespace(): string
    {
        $parts = explode("\\", get_class($this));
        $partCount = count($parts);
        return $parts[$partCount - 2];
    }

    private function getAbsoluteFileName(string $fileName): string
    {
        $classNameWithoutTest = $this->classNameWithoutTest();
        return __DIR__ . '/' . $this->testNamespace() . '/_files/' . $classNameWithoutTest . '/' . $fileName . '.inc';
    }

    private function sniffFile(): string
    {
        return $this->testNamespace() . '/' . $this->classNameWithoutTest() . 'Sniff.php';
    }

    #[DataProvider('sniffProvider')]
    public function testSniff($fileName, $expectedErrors)
    {
        $sniffFiles = [
            __DIR__ . '/../Cloud9Software/Sniffs/' . $this->sniffFile()
        ];
        $config = new \PHP_CodeSniffer\Config();
        $ruleset = new \PHP_CodeSniffer\Ruleset($config);
        $ruleset->registerSniffs($sniffFiles, [], []);
        $ruleset->populateTokenListeners();
        $phpcsFile = new \PHP_CodeSniffer\Files\LocalFile($this->getAbsoluteFileName($fileName), $ruleset, $config);
        $phpcsFile->process();
        $foundErrors = $phpcsFile->getErrors();
        if (!$expectedErrors) {
            $this->assertEquals([], $foundErrors);
            return;
        }
        $transformedErrors = $this->getTransformedErrors($foundErrors);
        $this->assertEquals($expectedErrors, $transformedErrors);
    }
    
}
