# Pastel

Generate beautiful API documentation from Markdown.


![image](http://img.shields.io/packagist/v/shalvah/pastel.svg?style=flat)
[![Build Status](https://travis-ci.org/shalvah/pastel.svg?branch=master)](https://travis-ci.org/shalvah/pastel)

This project is a fork of [Documentarian](https://github.com/shalvah/pastel), which is itself a PHP port of [Slate](https://github.com/tripit/slate), the API documentation tool.


## Installation
```bash
composer require shalvah/pastel
```
 
## Usage
Pastel is like Documentarian and Slate, but simpler and with a somewhat different paradigm. You shouldn't need to write any PHP, just Markdown and maybe HTML. Here's what you need to know:

### How do I write my docs in Markdown?
Pastel's Markdown syntax is borrowed from Slate's, so we'll refer you to the Slate wiki as needed. Here are the key parts:

#### The content
Your Markdown file should contain your docs, written as you like. THere's no set format, but you can start with an introduction, talk about authentication and any general details, then describe each endpoint in its own section. Write example requests and responses using code blocks, use tables to describe request and response parameters.
 
 There's a good example in the included example Markdown ([stubs/index.md](./stubs/index.md)) and the resulting HTML output ([stubs/output/index.html](./stubs/output/index.html)).

For a full explanation of the supported Markdown syntax, see [How to Edit Slate Markdown files](https://github.com/slatedocs/slate/wiki/Markdown-Syntax)

#### The front matter
The front matter is a YAML section in your Markdown file that comes before the actual content. It's separated from the main content by a line before and after it containing only "---" (see [stubs/index.md](./stubs/index.md)).
 
```
---
# This section is the front matter
title: API Docs
---

This section is the content.
```

The front matter provides "meta" information about a Markdown document's contents (in this case, the API doc).  You can use it to customise how your documentation will look like. Here are the values Pastel supports:

- `title`: The page title. This is used as the value of `<title>`, so it's only shown on the browser window.

- `language_tabs`: Array of languages to switch between in the code samples. Please list them in the same order your code blocks are. Supported languages for highlighting: `bash`, `csharp`, `go`, `java`, `javascript`, `php`, `python`, `ruby`.  You can use other languages too, but you won't get syntax hghlighting. 

- `toc_footers`: Array of items to add below your table of contents. See [Slate's docs](https://github.com/slatedocs/slate/wiki/External-Links-in-the-ToC).

- `search`: Set this to `true` if you'd like to include a search box above the table of contents so users can search through your headings (why wouldn't you?🙄).

- `logo`: If you'd like to use a logo on the sidebar, set this to the path to the logo image file. Must be either a URL or a path relative to the docs destination from a browser. The image will have to fit in a 230px width box (the sidebar), so make sure it scales nicely.

- `includes`: This is where you can append more files to the main Markdown file you're using. Each entry in this array is the path to a Markdown file relative to the main file. So, if your folder structure is like this:
 
```
source/
  |- index.md
  |- includes/
     |- errors.md
     |- appendix.md
```

you can append the other files to `index.md` by using

```
includes:
- ./includes/appendix.md
- ./includes/errors.md
```

- `last_updated`: The date on which the docs were last updated. Helpful so your users know if they're looking at something stale. Leave this empty and it will automatically be set to the most recent time you editing your Markdown files (main + includes). If you want to set this manually, you can write whatever you want here. Pastel will render it as is.


Most of these sections can be disabled in the generated documentation by omitting them from the front matter.

### How do I convert my Markdown file to HTML docs?
If you've installed this using Composer, you can just run the CLI script (`vendor/bin/pastel`). Here's how you'd use it:

```bash
vendor/bin/pastel generate vendor/shalvah/pastel/stubs/index.md docs
```

This will generate a new API documentation using the sample file included with this project (stubs/index.md) and place the output in your docs/ directory (it will create it if it doesn't exist). You should replace the path to the source Markdown file with the path to your own Markdown file when you're ready.

```bash
vendor/bin/pastel generate docs_source/index.md docs
```

You can also call Pastel from PHP. This is especially useful if you're building a tool on top of this. Here's how you'd use it:

```php
$pastel = new Shalvah\Pastel\Pastel();
$pastel->generate("docs_source/index.md", "docs");
```

### Do I have to put all my docs in a single file?
Nope. You can split them across multiple files, and use the `includes` key in the front matter of the main file to append them.

## You might be interested in...

## Todo
- Custom favicon support
- Override more front matter options from config/CLI