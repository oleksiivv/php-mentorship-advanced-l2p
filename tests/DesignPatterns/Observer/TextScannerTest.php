<?php

use PHPUnit\Framework\TestCase;
use DesignPatterns\Observer\TextScanner;
use DesignPatterns\Observer\Listeners\NumberCounter;
use DesignPatterns\Observer\Listeners\WordCounter;
use DesignPatterns\Observer\Listeners\LongestWordKeeper;
use DesignPatterns\Observer\Listeners\ReverseWordHandler;

class TextScannerTest extends TestCase
{
    private string $filePath = 'test.txt';

    /**
     * @dataProvider wordCounterDataProvider
     */
    public function testWordCounter(string $content, int $expectedWordCount): void
    {
        file_put_contents($this->filePath, $content);

        $scanner = new TextScanner();
        $wordCounter = new WordCounter();

        $scanner->addListener($wordCounter);

        $scanner->execute($this->filePath);

        $this->assertEquals($expectedWordCount, $wordCounter->getWordsCount());
    }

    /**
     * @dataProvider longestWordKeeperDataProvider
     */
    public function testLongestWordKeeper(string $content, string $expectedLongestWord): void
    {
        file_put_contents($this->filePath, $content);

        $scanner = new TextScanner();
        $longestWordKeeper = new LongestWordKeeper();

        $scanner->addListener($longestWordKeeper);

        $scanner->execute($this->filePath);

        $this->assertEquals($expectedLongestWord, $longestWordKeeper->getLongestWord());
    }

    /**
     * @dataProvider numberCounterDataProvider
     */
    public function testNumberCounter(string $content, int $expectedNumberCount): void
    {
        file_put_contents($this->filePath, $content);

        $scanner = new TextScanner();
        $numberCounter = new NumberCounter();

        $scanner->addListener($numberCounter);

        $scanner->execute($this->filePath);

        $this->assertEquals($expectedNumberCount, $numberCounter->getNumbersCount());
    }

    /**
     * @dataProvider wordsReverseDataProvider
     */
    public function testWordsReverse(string $content, string $contentWithReversedWords): void
    {
        file_put_contents($this->filePath, $content);

        $scanner = new TextScanner();
        $reverseWordHandler = new ReverseWordHandler();

        $scanner->addListener($reverseWordHandler);

        $scanner->execute($this->filePath);

        $this->assertEquals($contentWithReversedWords, implode(' ', $reverseWordHandler->getReversedWords()));
    }

    private function wordCounterDataProvider(): array
    {
        return [
            ['Helloo world', 2],
            ['Test the word word test one two', 7],
        ];
    }

    private function numberCounterDataProvider(): array
    {
        return [
            ['1 2 34 Helloo 78 world 12', 5],
            ['1 2 34', 3],
            ['hello world', 0],
        ];
    }

    private function longestWordKeeperDataProvider(): array
    {
        return [
            ['Helloo world', 'Helloo'],
            ['Test the longest word, like efstefeefhf', 'efstefeefhf'],
        ];
    }

    private function wordsReverseDataProvider(): array
    {
        return [
            ['Helloo world', 'oolleH dlrow'],
            ['Test', 'tseT'],
        ];
    }
}
