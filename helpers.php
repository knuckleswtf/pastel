<?php

use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\Filesystem;

/**
 * Recursively copy files from one directory to another
 *
 * @param String $src - Source of files being moved
 * @param String $dest - Destination of files being moved
 * @return bool
 */
if (!function_exists('rcopy')) {
    function rcopy($src, $dest)
    {
        // If source is not a directory stop processing
        if (!is_dir($src)) return;

        // If the destination directory does not exist create it
        if (!is_dir($dest)) {
            if (!mkdir($dest, 0777, true)) {
                // If the destination directory could not be created stop processing
                throw new Exception("Failed to create target directory: $dest");
            }
        }

        // Open the source directory to read in files
        $i = new DirectoryIterator($src);
        foreach ($i as $f) {
            if ($f->isFile()) {
                copy($f->getRealPath(), "$dest/" . $f->getFilename());
            } else if (!$f->isDot() && $f->isDir()) {
                rcopy($f->getRealPath(), "$dest/$f");
            }
        }
    }
}

// Note that league/flysystem is in require-dev, so this function wont work outside tests
function deleteDirectoryAndContents($dir)
{
    $dir = ltrim($dir, '/');
    $adapter = new LocalFilesystemAdapter(realpath(__DIR__ ));
    $fs = new Filesystem($adapter);
    $fs->deleteDirectory($dir);
}

/**
 * Get the contents of a file, normalizing newlines.
 *
 * @param $path
 *
 * @return string
 */
function getFileContentsIgnoringNewlines($path)
{
    return str_replace("\r\n", "\n", file_get_contents($path));
}

function get_css_link_tag($name, $media = '')
{
    return <<<HTML
    <link rel="stylesheet" href="css/$name.css" media="$media" />
HTML;
}

function get_js_script_tag($name)
{
    return <<<HTML
    <script src="js/$name.js"></script>
HTML;
}

function get_image_tag($path, $class = '')
{
    return <<<HTML
    <img src="$path" alt="$class-image" class="$class"/>
HTML;
}