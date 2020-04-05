<?php

use Illuminate\Support\Collection;
use Mpociot\Documentarian\Documentarian;
use PHPUnit\Framework\TestCase;

class DocumentarianTest extends TestCase
{

    public function tearDown(): void
    {
        deleteDirectoryAndContents('/tests/output');
        mkdir(__DIR__ . '/output');
        touch(__DIR__ . '/output/.gitkeep');
    }

    public function test_cannot_generate_html_if_folder_does_not_exist()
    {
        $outputDir = __DIR__ . '/output';

        $documentarian = new Documentarian();
        $this->assertThr($documentarian->generate($outputDir));
    }

    public function test_can_generate_html()
    {
        $outputDir = __DIR__ . '/output';
        $assertionDir = __DIR__ . '/assertions';

        $documentarian = new Documentarian();
        $documentarian->create($outputDir);

        // test1.md - no frontmatter yaml present
        copy(__DIR__ . '/files/test1.md', $outputDir . '/source/index.md');
        $documentarian->generate($outputDir);
        $this->assertFilesHaveSameContentIgnoringNewlines($outputDir . '/index.html', $assertionDir . '/test1.html');

        // test2.md - valid frontmatter yaml
        copy(__DIR__ . '/files/test2.md', $outputDir . '/source/index.md');
        $documentarian->generate($outputDir);
        $this->assertFilesHaveSameContentIgnoringNewlines($outputDir . '/index.html', $assertionDir . '/test2.html');

        // test3.md - include and parse additional markdown files
        copy(__DIR__ . '/files/test3.md', $outputDir . '/source/index.md');
        $documentarian->generate($outputDir);
        $this->assertFilesHaveSameContentIgnoringNewlines($outputDir . '/index.html', $assertionDir . '/test3.html');

        // test4.md - ignore not existing include file
        copy(__DIR__ . '/files/test4.md', $outputDir . '/source/index.md');
        $documentarian->generate($outputDir);
        $this->assertFilesHaveSameContentIgnoringNewlines($outputDir . '/index.html', $assertionDir . '/test4.html');

    }

    public function assertFilesHaveSameContentIgnoringNewlines($pathToExpected, $pathToActual)
    {
        $actual = getFileContentsIgnoringNewlines($pathToActual);
        $expected = getFileContentsIgnoringNewlines($pathToExpected);
        $this->assertSame($expected, $actual);
    }
}