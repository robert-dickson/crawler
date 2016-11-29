<?php

namespace Spatie\Crawler\Test;

use PHPUnit_Framework_TestCase;
use Spatie\Crawler\Crawler;

class CrawlerTest extends PHPUnit_Framework_TestCase
{
    /** @var logPath */
    protected static $logPath;

    public function setUp()
    {
        parent::setUp();

        static::$logPath = __DIR__ . '/temp/crawledUrls.txt';

        file_put_contents(static::$logPath, 'start log'.PHP_EOL);
    }

    /** @test */
    public function it_crawls()
    {
        Crawler::create()
            ->setCrawlObserver(new CrawlLogger())
            ->startCrawling('http://localhost:8080');

        $this->assertCrawledOnce([
            'http://localhost:8080/',
            'http://localhost:8080/link1',
            'http://localhost:8080/link2',
            'http://localhost:8080/link3',
        ]);
    }

    protected function assertCrawledOnce($urls)
    {
        if (! is_array($urls)) {
            $urls = [$urls];
        }

        $logContent = file_get_contents(static::$logPath);

        foreach($urls as $url) {
            $logMessage = "hasBeenCrawled: {$url}" . PHP_EOL;
        }

        $this->assertEquals(1, substr_count($logContent, $logMessage), "Did not find {$logMessage} exactly one time in the log");
    }

    public static function log(string $text)
    {
        file_put_contents(static::$logPath, $text . PHP_EOL, FILE_APPEND);
    }


}