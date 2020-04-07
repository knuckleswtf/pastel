<?php

namespace Shalvah\Pastel;

use Illuminate\Support\Str;
use Mni\FrontYAML\Parser;
use Windwalker\Renderer\BladeRenderer;

class Pastel
{

    /**
     * Generate the API documentation using the markdown and include files
     */
    public function generate(
        string $sourceFolder,
        ?string $destinationFolder = '',
        $config = ['logo' => false]
    )
    {
        if (Str::endsWith($sourceFolder, '.md')) {
            // We're given just the path to a file, we'll use default assets
            $sourceMarkdownFilePath = $sourceFolder;
            $sourceFolder = dirname($sourceMarkdownFilePath);
            $assetsFolder = __DIR__ . '/../resources';
        } else {
            if (!is_dir($sourceFolder)) {
                throw new \InvalidArgumentException("Source folder $sourceFolder is not a directory.");
            }

            // Valid source directory
            $sourceMarkdownFilePath = $sourceFolder . '/index.md';
            $assetsFolder = $sourceFolder;
        }

        if (empty($destinationFolder)) {
            // If no destination is supplied, place it in the source folder
            $destinationFolder = $sourceFolder;
        }

        $parser = new Parser();

        $document = $parser->parse(file_get_contents($sourceMarkdownFilePath));

        $frontmatter = $document->getYAML();
        $html = $document->getContent();

        // Parse and include optional include markdown files
        if (isset($frontmatter['includes'])) {
            $filePathsToInclude = collect($frontmatter['includes'])
                ->map(function ($include) use ($sourceFolder) {
                    return rtrim($sourceFolder, '/') . '/'. ltrim($include, '/');
            });
            $filePathsToInclude->each(function ($filePath) use ($parser, &$html) {
                if (file_exists(realpath($filePath))) {
                    $html .= $parser->parse(file_get_contents($filePath))->getContent();
                } else {
                    echo "Include file $filePath not found\n";
                }
            });
        }

        if (empty($frontmatter['last_updated'])) {
            # Set last_updated to most recent time main or include files was modified
            $timesLastUpdatedFiles = $filePathsToInclude->map(function ($filePath) {
                $realPath = realpath($filePath);
                return $realPath ? filemtime($realPath) : 0;
            });
            $timesLastUpdatedFiles->push(filemtime($sourceMarkdownFilePath));
            $frontmatter['last_updated'] = date("F j Y H:i:s", $timesLastUpdatedFiles->max());
        }

        // Allow overriding logo set in front matter from config
        $frontmatter['logo'] = $config['logo'] ?: $frontmatter['logo'] ?? false;

        $renderer = new BladeRenderer(
            [__DIR__ . '/../resources/views'],
            ['cache_path' => __DIR__ . '/_tmp']
        );
        $output = $renderer->render('index', [
            'page' => $frontmatter,
            'content' => $html,
        ]);

        if (!is_dir($destinationFolder)) {
            mkdir($destinationFolder, 0777, true);
        }

        file_put_contents($destinationFolder . '/index.html', $output);

        // Copy assets
        rcopy($assetsFolder . '/images/', $destinationFolder . '/images');
        rcopy($assetsFolder . '/css/', $destinationFolder . '/css');
        rcopy($assetsFolder . '/js/', $destinationFolder . '/js');
        rcopy($assetsFolder . '/fonts/', $destinationFolder . '/fonts');
    }
}