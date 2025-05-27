<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-3xl font-bold mb-6">Blog</h1>

                    @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($articles as $article)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            @if($article->image)
                            <img src="{{ Storage::url($article->image) }}" alt="{{ $article->title }}" class="w-full h-48 object-cover">
                            @endif
                            <div class="p-4">
                                <h2 class="text-xl font-semibold mb-2">
                                    <a href="{{ route('articles.show', $article) }}" class="text-gray-800 hover:text-blue-600">
                                        {{ $article->title }}
                                    </a>
                                </h2>
                                <p class="text-gray-600 mb-4">
                                    {{ Str::limit(strip_tags($article->content), 150) }}
                                </p>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">
                                        Par {{ $article->user->name }}
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        {{ $article->created_at->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $articles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>