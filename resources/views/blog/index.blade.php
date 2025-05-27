@push('meta')
<meta name="description" content="Découvrez les dernières actualités du web, les tendances digitales et les innovations technologiques qui façonnent l'avenir du web.">
<meta name="keywords" content="actualités web, tendances digitales, innovations web, tech news, développement web">
<meta property="og:title" content="Actualités Web - Kréyatik Studio">
<meta property="og:description" content="Restez informé des dernières actualités et tendances du web avec notre sélection d'articles.">
<meta property="og:type" content="website">
@endpush

<x-header title="Actualités Web - Kréyatik Studio" />

<section class="bg-gradient-to-b from-gray-50 to-white py-16">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4 animate-fade-in">Actualités Web</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Restez à jour avec les dernières actualités du web, les tendances digitales et les innovations technologiques qui façonnent l'avenir du web.</p>
        </div>

        @if(count($articles) > 0)
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            @foreach($articles as $article)
            <article class="bg-white rounded-2xl shadow-xl p-6 flex flex-col hover:scale-[1.01] transition-transform duration-300 animate-fade-in border border-gray-100">
                @if($article->image)
                <div class="mb-4 rounded-xl overflow-hidden h-48 flex items-center justify-center bg-gray-100">
                    <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="object-cover w-full h-full">
                </div>
                @endif
                <div class="flex-1 flex flex-col">
                    <div class="flex items-center text-sm text-gray-500 mb-2">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Publié le {{ $article->published_at ? $article->published_at->format('d/m/Y') : 'Non publié' }}
                    </div>
                    <h2 class="text-2xl font-bold mb-2 text-gray-800 line-clamp-2">
                        <a href="{{ route('blog.show', $article->slug) }}" class="hover:text-blue-600 transition-colors duration-300">{{ $article->title }}</a>
                    </h2>
                    <p class="text-gray-600 mb-4 line-clamp-3 flex-1">{{ Str::limit(strip_tags(html_entity_decode($article->content)), 150) }}</p>
                    <div class="mt-auto">
                        <a href="{{ route('blog.show', $article->slug) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-semibold transition-colors duration-300">
                            Lire la suite
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
        <div class="mt-12">
            {{ $articles->links() }}
        </div>
        @else
        <div class="text-center py-16">
            <div class="max-w-md mx-auto">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucun article pour le moment</h3>
                <p class="text-gray-600">Nous sommes en train de préparer de nouveaux articles passionnants sur les dernières actualités du web. Revenez bientôt pour découvrir notre sélection !</p>
            </div>
        </div>
        @endif
    </div>
</section>

<x-footer />