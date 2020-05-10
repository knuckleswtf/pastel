<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{!! $page['title'] !!}</title>

    <link href="https://fonts.googleapis.com/css?family=PT+Sans&display=swap" rel="stylesheet">

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
        {!! get_image_tag('images/navbar.png') !!}
      </span>
</a>
<div class="tocify-wrapper">
    @if($page['logo'] != false)
    <img src="{{ $page['logo'] }}" alt="logo" class="logo" style="padding-top: 10px;" width="230px"/>
    @endif
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

    <ul id="toc">
    </ul>

    @if(isset($page['toc_footers']))
        <ul class="toc-footer" id="toc-footer">
            @foreach($page['toc_footers'] as $link)
                <li>{!! $link !!}</li>
            @endforeach
        </ul>
    @endif
        <ul class="toc-footer" id="last-updated">
            <li>Last updated: {{ $page['last_updated'] }}</li>
        </ul>
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
@isset($page['language_tabs'])
<script>
    $(function () {
        var languages = @json($page['language_tabs']);
        setupLanguages(languages);
    });
</script>
@endisset
</body>
</html>