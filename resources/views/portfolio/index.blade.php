@push('meta')
<meta name="description" content="Découvrez notre portfolio de réalisations web professionnelles. Des projets web sur mesure créés avec passion et expertise.">
<meta name="keywords" content="portfolio web, réalisations web, projets web, développement web, design web">
<meta property="og:title" content="Portfolio - Nos Réalisations Web">
<meta property="og:description" content="Découvrez nos meilleurs projets web et nos réalisations digitales.">
<meta property="og:type" content="website">
@endpush

<x-header title="Portfolio - Nos Réalisations Web" />

<main class="site-content" role="main">
    <section class="portfolio-header" aria-label="Introduction du portfolio">
        <h1>Nos Dernières Réalisations</h1>
        <p>Découvrez notre collection de projets web sur mesure, créés avec passion et expertise pour répondre aux besoins uniques de nos clients.</p>
    </section>

    <section class="portfolio-gallery" aria-label="Galerie de projets">
        <div class="container mx-auto px-4">
            @if(count($portfolioItems) > 0)
            <div class="portfolio-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12" role="list">
                @foreach($portfolioItems as $item)
                <article class="portfolio-item" role="listitem" itemscope itemtype="https://schema.org/CreativeWork">
                    <div class="portfolio-media" style="aspect-ratio:16/9; background:#111; overflow:hidden;">
                        @if($item->isImage())
                        <img src="{{ asset($item->path) }}"
                            alt="{{ $item->title }}"
                            style="width:100%; height:100%; object-fit:cover; display:block;"
                            itemprop="image">
                        @else
                        <video autoplay muted loop playsinline
                            poster="{{ asset($item->path) }}"
                            style="width:100%; height:100%; object-fit:cover; display:block;"
                            aria-label="Vidéo de présentation du projet {{ $item->title }}">
                            <source src="{{ asset($item->path) }}" type="video/mp4">
                            Votre navigateur ne supporte pas la vidéo HTML5.
                        </video>
                        @endif
                    </div>
                    <div class="portfolio-caption">
                        <h3>{{ $item->title }}</h3>
                        <p>{{ $item->description }}</p>
                        @if($item->technology)
                        <div class="portfolio-technologies text-center">
                            <strong class="text-sm block mb-2">Technologies utilisées :</strong>
                            <div class="flex flex-wrap gap-2 justify-center">
                                @foreach(explode(',', $item->technology) as $tech)
                                <span class="bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-xs">{{ trim($tech) }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </article>
                @endforeach
            </div>
            @else
            <div class="portfolio-empty text-center py-12" role="alert">
                <div class="max-w-md mx-auto">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-xl text-gray-600">Aucun projet à afficher pour le moment. Revenez bientôt pour découvrir nos nouvelles réalisations !</p>
                </div>
            </div>
            @endif
        </div>
    </section>

    <section class="cta-section bg-gray-50 py-16">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4">Prêt à démarrer votre projet ?</h2>
            <p class="text-lg text-gray-600 mb-8">Transformez vos idées en réalité avec notre expertise</p>
            <a href="{{ route('contact') }}" class="inline-block bg-[#0099cc] text-white px-8 py-3 rounded-lg font-semibold hover:bg-[#0077aa] transition-colors duration-300 shadow-lg hover:shadow-xl">
                Discutons de votre projet
            </a>
        </div>
    </section>
</main>

<x-footer />