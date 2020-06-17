<?php

namespace Knuckles\Pastel\Tests;

use DOMDocument;
use DOMElement;
use Illuminate\Support\Str;
use Knuckles\Pastel\Pastel;
use PHPUnit\Framework\TestCase;
use Shalvah\Clara\Clara;

class PastelTest extends TestCase
{

    /**
     * @var string
     */
    private $outputDir;

    /**
     * @var Pastel
     */
    private $pastel;

    protected function setUp(): void
    {
        $this->outputDir = __DIR__ . '/output';
        $this->pastel = new Pastel();
        clara::mute();
        // Silence unnecessary DomDocument errors
        libxml_use_internal_errors(true);
    }

    public function tearDown(): void
    {
        deleteDirectoryAndContents('/tests/output');
        mkdir(__DIR__ . '/output');
        touch(__DIR__ . '/output/.gitkeep');
    }

    public function test_uses_default_metadata_when_front_matter_missing()
    {
        $this->pastel->generate(__DIR__ . '/files/test-no-front-matter.md', $this->outputDir . '/no-front-matter');

        $source = file_get_contents(__DIR__ . '/output/no-front-matter/index.html');
        $dom = new DOMDocument;
        $dom->loadHTML($source);

        $title = $dom->getElementsByTagName('title')[0] ?? null;
        $this->assertNotNull($title);
        $this->assertEquals(Pastel::$defaultMetadata['title'], $title->nodeValue);

        $inputs = collect($dom->getElementsByTagName('input'));
        $searchInput = $inputs->first(function (DOMElement $input) {
            return $input->getAttribute('id') === "input-search";
        });
        $this->assertNotNull($searchInput);
    }

    public function test_can_set_last_updated_time_automatically()
    {
        // Update file modification time to today
        touch(__DIR__ . '/files/test-no-front-matter.md');
        $lastUpdated = date("F j Y");

        $this->pastel->generate(__DIR__ . '/files/test-no-front-matter.md', $this->outputDir . '/no-front-matter');

        $source = file_get_contents(__DIR__ . '/output/no-front-matter/index.html');
        $this->assertStringContainsString("Last updated: $lastUpdated", $source);

        // Update file modification time to yesterday
        $yesterdayTimestamp = time() - 86400;
        touch(__DIR__ . '/files/test-no-front-matter.md', $yesterdayTimestamp);
        $lastUpdated = date("F j Y", $yesterdayTimestamp);

        $this->pastel->generate(__DIR__ . '/files/test-no-front-matter.md', $this->outputDir . '/no-front-matter');

        $source = file_get_contents(__DIR__ . '/output/no-front-matter/index.html');
        $this->assertStringContainsString("Last updated: $lastUpdated", $source);
    }

    public function test_uses_front_matter_values_properly()
    {
        $this->pastel->generate(__DIR__ . '/files/test-with-front-matter.md', $this->outputDir . '/with-front-matter');

        $source = file_get_contents(__DIR__ . '/output/with-front-matter/index.html');
        $dom = new DOMDocument;
        $dom->loadHTML($source);

        $title = $dom->getElementsByTagName('title')[0] ?? null;
        $this->assertNotNull($title);
        $this->assertEquals("Test With Front Matter", $title->nodeValue);

        $uls = collect($dom->getElementsByTagName('ul'));
        /** @var DOMElement $tocFooter */
        $tocFooter = $uls->first(function (DOMElement $ul) {
            return $ul->getAttribute('id') === "toc-footer";
        });
        $this->assertNotNull($tocFooter);

        $li = null;
        /** @var \DOMNode $node */
        foreach ($tocFooter->childNodes as $node) {
            if ($node instanceof DOMElement) {
                $li = $node;
            }
        }
        $link = $li->childNodes[0];
        $this->assertNotNull($link);
        $this->assertEquals("Hey", $link->textContent);

        $images = collect($dom->getElementsByTagName('img'));
        $logo = $images->first(function (DOMElement $image) {
            return $image->getAttribute('class') === "logo";
        });
        $this->assertNull($logo);
    }

    public function test_front_matter_can_be_overriden()
    {
        $overrides = [
            'title' => 'Test With Front Matter Overriden',
            'language_tabs' => [],
            'toc_footers' => [],
            'search' => false,
            'logo' => "http://fake",
            'last_updated' => '2017',
        ];
        $this->pastel->generate(
            __DIR__ . '/files/test-with-front-matter.md',
            $this->outputDir . '/front-matter-overriden',
            $overrides
        );

        $source = file_get_contents(__DIR__ . '/output/front-matter-overriden/index.html');
        $dom = new DOMDocument;
        $dom->loadHTML($source);

        $title = $dom->getElementsByTagName('title')[0] ?? null;
        $this->assertNotNull($title);
        $this->assertEquals("Test With Front Matter Overriden", $title->nodeValue);

        $images = collect($dom->getElementsByTagName('img'));
        $logo = $images->first(function (DOMElement $image) {
            return $image->getAttribute('class') === "logo";
        });
        $this->assertNotNull($logo);
        $this->assertEquals("http://fake", $logo->getAttribute("src"));

        $uls = collect($dom->getElementsByTagName('ul'));
        /** @var DOMElement $tocFooter */
        $tocFooter = $uls->first(function (DOMElement $ul) {
            return $ul->getAttribute('id') === "toc-footer";
        });
        $this->assertNotNull($tocFooter);

        $this->assertStringContainsString("Last updated: 2017", $source);
    }

    public function test_include_file_contents_get_included()
    {
        $this->pastel->generate(
            __DIR__ . '/files/test-with-includes.md',
            $this->outputDir . '/with-includes'
        );

        $source = file_get_contents(__DIR__ . '/output/with-includes/index.html');
        $dom = new DOMDocument;
        $dom->loadHTML($source);

        $h1s = collect($dom->getElementsByTagName('h1'));
        $header = $h1s->first(function (DOMElement $h1) {
            return $h1->nodeValue === "Include Me";
        });
        $this->assertNotNull($header);
        $this->assertStringContainsString("Yay! I was included.", $source);
    }

    public function test_can_include_entire_directory_in_alphabetical_order()
    {
        $this->pastel->generate(
            __DIR__ . '/files/test-with-directory-include.md',
            $this->outputDir . '/with-directory-include'
        );

        $source = file_get_contents(__DIR__ . '/output/with-directory-include/index.html');
        $dom = new DOMDocument;
        $dom->loadHTML($source);

        $h1s = collect($dom->getElementsByTagName('h1'));
        $indexOfFirstOne = $h1s->search(function (DOMElement $h1) {
            return $h1->nodeValue === "Also Include Me";
        });
        $indexOfSecondOne = $h1s->search(function (DOMElement $h1) {
            return $h1->nodeValue === "Include Me";
        });

        $this->assertNotFalse($indexOfFirstOne);
        $this->assertNotFalse($indexOfSecondOne);
        $this->assertGreaterThan($indexOfFirstOne, $indexOfSecondOne);
    }

    public function test_logs_warning_for_missing_include_files()
    {

        Clara::startCapturingOutput('shalvah/pastel');;
        $this->pastel->generate(
            __DIR__ . '/files/test-with-missing-includes.md',
            $this->outputDir . '/with-missing-includes'
        );

        $output = Clara::getCapturedOutput('shalvah/pastel');

        $filePath = __DIR__ . '/files/partials/nonexistent.md';
        $warning = collect($output)->first(function ($line) use ($filePath) {
           return Str::contains($line, "Include file $filePath not found");
        });
        $this->assertNotNull($warning);
    }
}