---
# Page title. Only shown on the browser tab, not actual page content 
title: API Reference

# Languages to switch between in the code samples. Please list them in the same order your code blocks are.
# Supported languages for highlighting: `bash`, `csharp`, `go`, `java`, `javascript`, `php`, `python`, `ruby`
language_tabs: 
  - php
  - python
  - bash
  - javascript

# HTMl to add in the footer of the sidebar (table of contents)
toc_footers:
  - <a href='#'>Sign up for a developer key</a>
  - <a href='https://github.com/knuckleswtf/pastel'>Documentation powered by Pastel 🎨</a>

# If you want a logo on the sidebar, set this to the path to the logo image file. Must be either a URL or a path relative to the docs destination from a browser.
logo: false

# Date the docs were last updated. Leave this as empty to use the last time the file was modified
last_updated: ''

# Other Markdown files to include. They will be appended to this file. Files are appended in the order listed.
includes:
- "./includes/errors.md"
 
---

# Introduction

Welcome to the Kittn API! You can use our API to can get information on various cats, kittens, and breeds in our database.

As you scroll, you'll see code examples for working with the API in Bash, PHP, Python, and JavaScript in the dark area to the right (or as part of the content on mobile), and you can switch the programming language of the examples with the tabs in the top right (or from the nav menu at the top left on mobile).

This example API documentation page was borrowed from [Slate](https://github.com/slatedocs/slate) and generated with [Pastel](https://github.com/knuckleswtf/pastel). Feel free to edit it and use it as a base for your own API's documentation.

# Authentication

> To authorize, use this code:

```php
# With PHP, you can pass in the correct header with each request
$client = new \GuzzleHttp\Client();
$response = $client->get(
    "api_endpoint_here",
    [
        'headers' => [
            'Authorization' => 'meowmeowmeow',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```

```python
import kittn

api = kittn.authorize('meowmeowmeow')
```

```bash
# With Bash, you pass in the correct header with each request
curl "api_endpoint_here"
  -H "Authorization: meowmeowmeow"
```

```javascript
const kittn = require('kittn');

let api = kittn.authorize('meowmeowmeow');
```

> Make sure to replace `meowmeowmeow` with your API key.

Kittn uses API keys to allow access to the API. You can register a new Kittn API key at our [developer portal](http://example.com/developers).

Kittn expects for the API key to be included in all API requests to the server in a header that looks like the following:

`Authorization: meowmeowmeow`

<aside class="notice">
You must replace <code>meowmeowmeow</code> with your personal API key.
</aside>

# Kittens

## Get All Kittens

```php
$client = new \GuzzleHttp\Client();
$response = $client->get("http://example.com/kittens/");
$body = $response->getBody();
print_r(json_decode((string) $body));
```

```python
import kittn

api = kittn.authorize('meowmeowmeow')
api.kittens.get()
```

```bash
curl "http://example.com/api/kittens"
  -H "Authorization: meowmeowmeow"
```

```javascript
const kittn = require('kittn');

let api = kittn.authorize('meowmeowmeow');
let kittens = api.kittens.get();
```

> The above command returns JSON structured like this:

```json
[
  {
    "id": 1,
    "name": "Fluffums",
    "breed": "calico",
    "fluffiness": 6,
    "cuteness": 7
  },
  {
    "id": 2,
    "name": "Max",
    "breed": "unknown",
    "fluffiness": 5,
    "cuteness": 10
  }
]
```

This endpoint retrieves all kittens.

### HTTP Request

<small class="badge badge-green">GET</small> **`http://example.com/api/kittens`**

<h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
<p>
    <code><b>include_cats</b></code>&nbsp; <i>Default: <code>false</code></i>    
    <br>
    If set to true, the result will also include cats.
</p>
<p>
    <code><b>available</b></code>&nbsp; <i>Default: <code>true</code></i>  
    <br>
    If set to false, the result will include kittens that have already been adopted.
</p>

<aside class="success">
Remember — a happy kitten is an authenticated kitten!
</aside>

## Get a Specific Kitten

```php
$client = new \GuzzleHttp\Client();
$response = $client->get('http://example.com/kittens/2');
$body = $response->getBody();
print_r(json_decode((string) $body));
```

```python
import kittn

api = kittn.authorize('meowmeowmeow')
api.kittens.get(2)
```

```bash
curl "http://example.com/api/kittens/2"
  -H "Authorization: meowmeowmeow"
```

```javascript
const kittn = require('kittn');

let api = kittn.authorize('meowmeowmeow');
let max = api.kittens.get(2);
```

> The above command returns JSON structured like this:

```json
{
  "id": 2,
  "name": "Max",
  "breed": "unknown",
  "fluffiness": 5,
  "cuteness": 10
}
```

This endpoint retrieves a specific kitten.

<aside class="warning">Inside HTML code blocks like this one, you can't use Markdown, so use <code>&lt;code&gt;</code> blocks to denote code.</aside>

### HTTP Request

<small class="badge badge-green">GET</small> **`http://example.com/kittens/<ID>`**

<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
    <code><b>ID</b></code>
    <br>
    The ID of the kitten to retrieve.
</p>

## Delete a Specific Kitten

```php
$client = new \GuzzleHttp\Client();
$response = $client->delete('http://example.com/kittens/2');
$body = $response->getBody();
print_r(json_decode((string) $body));
```

```python
import kittn

api = kittn.authorize('meowmeowmeow')
api.kittens.delete(2)
```

```bash
curl "http://example.com/api/kittens/2"
  -X DELETE
  -H "Authorization: meowmeowmeow"
```

```javascript
const kittn = require('kittn');

let api = kittn.authorize('meowmeowmeow');
let max = api.kittens.delete(2);
```

> The above command returns JSON structured like this:

```json
{
  "id": 2,
  "deleted" : ":("
}
```

This endpoint deletes a specific kitten.

### HTTP Request

<small class="badge badge-red">DELETE</small> **`http://example.com/kittens/<ID>`**

<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
    <code><b>ID</b></code>
    <br>
    The ID of the kitten to delete.
</p>

