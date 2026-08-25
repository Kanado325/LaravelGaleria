<x-app-layout>

    <div class="min-h-screen bg-blue-50 py-12">

        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-xl shadow-sm p-6">

                <h2 class="text-xl font-semibold text-slate-800 mb-6">
                    Editar Foto
                </h2>

                {{-- Foto atual --}}
                <div class="mb-6">
                    <img
                        src="{{ asset('storage/' . $photo->image_path) }}"
                        alt="{{ $photo->title }}"
                        class="w-full max-h-[500px] object-cover rounded-lg"
                    >
                </div>

                <form
                    action="{{ route('photos.update', $photo->id) }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="space-y-5"
                >

                    @csrf
                    @method('PUT')

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
                            value="{{ old('title', $photo->title) }}"
                            class="w-full rounded-lg border border-slate-300
                                px-3 py-2 text-sm
                                focus:border-indigo-500
                                focus:outline-none
                                focus:ring-1
                                focus:ring-indigo-500"
                        >
                    </div>

                    {{-- Nova foto --}}
                    <div>
                        <label
                            for="photo"
                            class="mb-1 block text-sm font-medium text-slate-700"
                        >
                            Substituir Foto
                        </label>

                        <input
                            type="file"
                            name="photo"
                            id="photo"
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
                                file:text-indigo-700"
                        >

                        <p class="mt-1 text-xs text-slate-500">
                            Deixe vazio para manter a foto atual.
                        </p>
                    </div>

                    {{-- Botões --}}
                    <div class="flex gap-3">

                        <button
                            type="submit"
                            class="rounded-lg bg-indigo-600
                                px-4 py-2 text-sm font-medium
                                text-white
                                hover:bg-indigo-700"
                        >
                            Salvar alterações
                        </button>

                        <a
                            href="{{ route('dashboard') }}"
                            class="rounded-lg bg-slate-100
                                px-4 py-2 text-sm font-medium
                                text-slate-700
                                hover:bg-slate-200"
                        >
                            Cancelar
                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>