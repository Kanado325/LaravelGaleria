<x-app-layout>

@php
    $fotografo = auth()->user()->role === 'fotografo';
@endphp

{{-- Fundo da página --}}
<div class="{{ $fotografo ? 'bg-blue-50' : 'bg-gray-100' }} min-h-screen py-12">

    {{-- Área de publicação --}}
    @if($fotografo)

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-8">

            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">

                <h3 class="text-lg font-semibold text-slate-800 mb-4">
                    Área do fotógrafo: Nova Publicação
                </h3>

                <form
                    action="{{ route('photos.store') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="space-y-4"
                >

                    @csrf

                    {{-- Título --}}
                    <div>
                        <label
                            for="title"
                            class="mb-1 block text-sm font-medium text-slate-700"
                        >
                            Título da Foto
                        </label>

                        <input
                            type="text"
                            name="title"
                            id="title"
                            placeholder="Ex: Pôr do sol na praia"
                            class="w-full rounded-lg border border-slate-300
                                px-3 py-2 text-sm text-slate-800
                                placeholder-slate-400
                                focus:border-indigo-500
                                focus:outline-none
                                focus:ring-1
                                focus:ring-indigo-500"
                        >
                    </div>

                    {{-- Foto --}}
                    <div>
                        <label
                            for="photo"
                            class="mb-1 block text-sm font-medium text-slate-700"
                        >
                            Escolha a Foto
                        </label>

                        <input
                            type="file"
                            name="photo"
                            id="photo"
                            required
                            accept="image/*"
                            class="w-full text-sm text-slate-500
                                file:mr-4
                                file:rounded-md
                                file:border-0
                                file:bg-indigo-50
                                file:px-4
                                file:py-2
                                file:text-sm
                                file:font-semibold
                                file:text-indigo-700
                                hover:file:bg-indigo-100"
                        >
                    </div>

                    {{-- Botão --}}
                    <button
                        type="submit"
                        class="w-full rounded-lg bg-indigo-600
                            px-4 py-2 text-sm font-medium
                            text-white transition-colors
                            hover:bg-indigo-700
                            focus:outline-none
                            focus:ring-2
                            focus:ring-indigo-500
                            focus:ring-offset-2
                            sm:w-auto"
                    >
                        Publicar Foto
                    </button>

                </form>

            </div>

        </div>

    @endif


    {{-- Galeria --}}
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        @forelse($photos as $photo)

            @if($loop->first)
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 items-start">
            @endif


            {{-- CARD DA FOTO --}}
            <div
                class="overflow-hidden rounded-xl border border-slate-200
                    bg-white shadow-sm
                    transform-gpu
                    transition-transform duration-300 ease-out
                    hover:scale-[1.02] hover:shadow-xl
                    relative z-0 hover:z-10"
            >

                {{-- Imagem --}}
                <div class="aspect-[4/4] w-full bg-slate-100">

                    <img
                        src="{{ asset('storage/' . $photo->image_path) }}"
                        alt="{{ $photo->title }}"
                        class="h-full w-full object-cover"
                    >

                </div>


                {{-- Informações --}}
                <div class="p-4">

                    <h4 class="font-semibold text-slate-800 line-clamp-1">
                        {{ $photo->title ?? 'Sem título' }}
                    </h4>


                    <p class="mt-1 text-xs text-slate-500">
                        Foto por:

                        <span class="font-medium text-slate-700">
                            {{ $photo->user->name }}
                        </span>
                    </p>


                    {{-- Curtida --}}
                    @php
                        $curtiu = $photo->likes()
                            ->where('user_id', auth()->id())
                            ->exists();
                    @endphp

                    @if($fotografo && $photo->user_id === auth()->id())
                <form
                    action="{{ route('photos.destroy', $photo->id) }}"
                    method="POST"
                    class="mt-2"
                    onsubmit="return confirm('Tem certeza que deseja excluir esta foto?')"
                >
                    @csrf
                    @if($fotografo && $photo->user_id === auth()->id())

            <div class="mt-3 flex gap-2">
                @endif

            {{-- Editar --}}
            <a
                href="{{ route('edit', $photo->id) }}"
                class="flex-1 rounded-lg bg-indigo-50
                    px-3 py-2 text-center
                    text-sm font-medium text-indigo-600
                    transition hover:bg-indigo-100"
            >
                ✏️ Editar
            </a>

            {{-- Excluir --}}
            <form
                action="{{ route('photos.destroy', $photo->id) }}"
                method="POST"
                class="flex-1"
                onsubmit="return confirm('Tem certeza que deseja excluir esta foto?')"
            >
                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="w-full rounded-lg bg-red-50
                        px-3 py-2
                        text-sm font-medium text-red-600
                        transition hover:bg-red-100"
                >
                    🗑️ Excluir
                </button>
            </form>

        </div>

    @endif


                    <form
                        action="{{ route('photos.like', $photo->id) }}"
                        method="POST"
                        class="mt-3"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="flex items-center gap-2
                                rounded-lg px-3 py-2
                                text-sm transition
                                hover:bg-slate-100"
                        >

                            <span class="text-xl">
                                {{ $curtiu ? '❤️' : '🖤' }}
                            </span>

                            <span class="text-slate-600">
                                {{ $photo->likes_count }}
                            </span>

                            <span class="text-slate-500">
                                {{ $curtiu ? 'Curtido' : 'Curtir' }}
                            </span>

                        </button>

                    </form>

                </div>

            </div>


            @if($loop->last)
                </div>
            @endif


        @empty

            <div
                class="rounded-xl border border-dashed
                    border-slate-300 p-8
                    text-center text-slate-500"
            >
                <p>
                    Nenhuma foto cadastrada ainda.
                </p>
            </div>

        @endforelse

    </div>

</div>

</x-app-layout>