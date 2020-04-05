<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{!! $page['title'] ?? "API Documentation" !!}</title>

    {!! get_css_link_tag('style', 'screen') !!}
    {!! get_css_link_tag('print', 'print') !!}
    {!! get_js_script_tag('all') !!}

    {!! get_css_link_tag('highlight-darcula') !!}
    {!! get_js_script_tag('highlight.pack') !!}
    <script>hljs.initHighlightingOnLoad();</script>
</head>

<body class="" data-languages="{{ json_encode($page['language_tabs'] ?? []) }}">
<a href="#" id="nav-button">
      <span>
        NAV
        {!! get_image_tag('navbar') !!}
      </span>
</a>
<div class="toc-wrapper">
    {!! get_image_tag("logo", ['class' => 'logo']) !!}
    @isset($page['language_tabs'])
    <div class="lang-selector">
        @foreach($page['language_tabs'] as $lang)
        <a href="#" data-language-name="{{ $lang }}">{{ $lang }}</a>
        @endforeach
    </div>
    @endisset
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>
    <ul class="search-results"></ul>
    
    <ul id="toc" class="toc-list-h1">
    </ul>

    @if(isset($page['toc_footers']))
        <ul class="toc-footer">
            @foreach($page['toc_footers'] as $link)
                <li>{!! $link !!}</li>
            @endforeach
        </ul>
    @endif
</div>
<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        {!! $content !!}
    </div>
    <div class="dark-box">
        @if(isset($page['language_tabs']))
            <div class="lang-selector">
                @foreach($page['language_tabs'] as $lang)
                    <a href="#" data-language-name="{{$lang}}">{{$lang}}</a>
                @endforeach
            </div>
        @endif
    </div>
</div>
<script>
   // $(function() {
   //     //Calls the tocify method on your HTML div.
   //   //  $("#toc").tocify();
   // });
</script>
</body>
</html>