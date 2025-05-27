<!DOCTYPE html>
<html lang="fr" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if(isset($seoData))
    <title>{{ $seoData->title }}</title>
    <meta name="description" content="{{ $seoData->description }}">
    <meta name="author" content="{{ $seoData->author }}">
    <meta name="robots" content="{{ $seoData->robots }}">
    <link rel="canonical" href="{{ $seoData->canonical_url }}" />
    @else
    <title>{{ config('app.name') }}</title>
    <meta name="description" content="{{ config('app.description', 'Description par défaut de votre site') }}">
    <meta name="robots" content="index, follow">
    @endif

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $seoData->title ?? config('app.name') }}">
    <meta property="og:description" content="{{ $seoData->description ?? config('app.description', 'Description par défaut de votre site') }}">
    <meta property="og:image" content="{{ $seoData->image ?? asset('images/default-og.jpg') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ $seoData->title ?? config('app.name') }}">
    <meta property="twitter:description" content="{{ $seoData->description ?? config('app.description', 'Description par défaut de votre site') }}">
    <meta property="twitter:image" content="{{ $seoData->image ?? asset('images/default-og.jpg') }}">

    <!-- Autres meta tags importants -->
    <meta name="format-detection" content="telephone=no">
    <meta name="theme-color" content="#ffffff">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Macondo&display=swap" rel="stylesheet">

    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="manifest" href="/favicon/site.webmanifest">
    <link rel="mask-icon" href="/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">

    {!! CookieConsent::styles() !!}

    @vite(['resources/css/app.css', 'resources/js/app.js']);

</head>

<body class="h-full">
    {!! CookieConsent::scripts() !!}
    <div class="site-wrapper">
        @include('components.nav')

        <div class="site-content">
            <!-- Content will be here -->
        </div>
    </div>