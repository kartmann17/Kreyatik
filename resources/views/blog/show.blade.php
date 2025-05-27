@push('meta')
<meta name="description" content="{{ Str::limit(strip_tags($article->content), 160) }}">
<meta name="keywords" content="blog web, article, {{ $article->title }}">
<meta property="og:title" content="{{ $article->title }} - Kréyatik Studio">
<meta property="og:description" content="{{ Str::limit(strip_tags($article->content), 160) }}">
<meta property="og:type" content="article">
@if($article->image)
<meta property="og:image" content="{{ asset('storage/' . $article->image) }}">
@endif
@endpush

<x-header title="{{ $article->title }} - Kréyatik Studio" />

<section class="bg-gradient-to-b from-gray-50 to-white py-16">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4 animate-fade-in">{{ $article->title }}</h1>
            <div class="flex items-center justify-center text-gray-500 text-sm mb-2">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Publié le {{ $article->published_at ? $article->published_at->format('d/m/Y') : 'Non publié' }}
            </div>
        </div>
        <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-xl p-8 md:p-12 animate-fade-in">
            @if($article->image)
            <div class="mb-8 rounded-xl overflow-hidden h-64 flex items-center justify-center bg-gray-100">
                <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="object-cover w-full h-full">
            </div>
            @endif
            <div class="prose prose-lg max-w-none mx-auto mb-8">
                {!! $article->content !!}
            </div>
            <div class="mt-8 pt-6 border-t border-gray-200 text-center">
                <a href="{{ route('blog') }}"
                    class="inline-flex items-center text-blue-600 hover:text-blue-800 font-semibold transition-colors duration-300">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Retour aux articles
                </a>
            </div>
        </div>
    </div>
</section>

<x-footer />