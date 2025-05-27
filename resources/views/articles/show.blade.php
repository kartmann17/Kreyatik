<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                    @endif

                    <article class="prose lg:prose-xl mx-auto">
                        <h1 class="text-4xl font-bold mb-4">{{ $article->title }}</h1>

                        <div class="flex items-center text-gray-500 mb-8">
                            <span class="mr-4">Par {{ $article->user->name }}</span>
                            <span>{{ $article->created_at->format('d/m/Y') }}</span>
                        </div>

                        @if($article->image)
                        <img src="{{ Storage::url($article->image) }}" alt="{{ $article->title }}" class="w-full h-96 object-cover rounded-lg mb-8">
                        @endif

                        <div class="prose prose-lg">
                            {!! $article->content !!}
                        </div>

                        @can('update', $article)
                        <div class="mt-8 flex space-x-4">
                            <a href="{{ route('articles.edit', $article) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Modifier
                            </a>

                            <form action="{{ route('articles.destroy', $article) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">
                                    Supprimer
                                </button>
                            </form>
                        </div>
                        @endcan
                    </article>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>