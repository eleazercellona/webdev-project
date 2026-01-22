<x-app-layout>
    <!-- Header -->
    <div class="pt-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-between items-center">
            <div>
                <h2 class="font-bold text-3xl text-gray-800">Edit Content</h2>
                <p class="text-gray-500 text-sm mt-1">
                    Update and manage your content entry.
                </p>
            </div>

            <a href="{{ route('posts.index') }}"
               class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">
                Back to Content
            </a>
        </div>
    </div>

    <!-- Body -->
    <div class="py-10" x-data="{ showDeleteModal: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <form method="POST"
                  action="{{ route('posts.update', $post) }}"
                  class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                @csrf
                @method('PUT')

                <!-- LEFT -->
                <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Content Details</h3>

                    <!-- Title -->
                    <div class="mb-4">
                        <x-input-label value="Title" />
                        <x-text-input
                            id="title"
                            class="mt-1 w-full"
                            name="title"
                            value="{{ old('title', $post->title) }}"
                            required
                        />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <!-- Slug (auto-generated) -->
                    <div class="mb-4">
                        <x-input-label value="Slug" />
                        <x-text-input
                            id="slug"
                            class="mt-1 w-full bg-gray-50"
                            name="slug"
                            value="{{ old('slug', $post->slug) }}"
                            readonly
                        />
                        <p class="text-xs text-gray-400 mt-1">Automatically generated from the title</p>
                    </div>

                    <!-- Body -->
                    <div>
                        <x-input-label value="Body" />
                        <textarea
                            name="body"
                            rows="8"
                            class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        >{{ old('body', $post->body) }}</textarea>
                        <x-input-error :messages="$errors->get('body')" class="mt-2" />
                    </div>
                </div>

                <!-- RIGHT -->
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 h-fit">
                    <h3 class="font-semibold text-gray-800 mb-4">Publish</h3>

                    <!-- Status -->
                    <div class="mb-4">
                        <x-input-label value="Status" />
                        @php($status = old('is_published', $post->is_published ? '1' : '0'))
                        <select
                            name="is_published"
                            class="mt-1 w-full border-gray-300 rounded-lg shadow-sm">
                            <option value="0" @selected($status === '0')>Draft</option>
                            <option value="1" @selected($status === '1')>Published</option>
                        </select>
                    </div>

                    <!-- Meta -->
                    <div class="text-sm text-gray-500 space-y-1 mb-4">
                        <div><strong>Author:</strong> {{ $post->user->name }}</div>
                        <div><strong>Created:</strong> {{ $post->created_at->format('M d, Y') }}</div>
                        <div><strong>Last updated:</strong> {{ $post->updated_at->format('M d, Y') }}</div>
                    </div>

                    <!-- Buttons -->
                    <button class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700">
                        Update
                    </button>

                    <a href="{{ route('posts.index') }}"
                       class="block text-center text-blue-600 mt-4 text-sm">
                        Cancel
                    </a>

                    <!-- Danger Zone -->
                    <div class="border-t mt-6 pt-4">
                        <p class="font-semibold text-gray-800">Danger Zone</p>
                        <p class="text-sm text-gray-500 mb-2">
                            Deleting will permanently remove this content.
                        </p>

                        <!-- Trigger Modal -->
                        <button
                            type="button"
                            @click="showDeleteModal = true"
                            class="text-red-600 text-sm hover:underline">
                            Delete
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- DELETE MODAL -->
        <div
            x-show="showDeleteModal"
            x-transition
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
        >
            <div
                @click.outside="showDeleteModal = false"
                class="bg-white rounded-xl shadow-xl w-full max-w-md p-6"
            >
                <div class="flex items-start gap-3">
                    <div class="text-red-500 text-xl">⚠️</div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">
                            Delete content?
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">
                            This action cannot be undone. This will permanently remove the content entry.
                        </p>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-3 mt-4 flex justify-between text-sm">
                    <span class="text-gray-500">Entry</span>
                    <span class="font-semibold text-gray-800">
                        {{ $post->title }}
                    </span>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button
                        type="button"
                        @click="showDeleteModal = false"
                        class="px-4 py-2 rounded-lg border text-gray-700 hover:bg-gray-100">
                        Cancel
                    </button>

                    <form method="POST" action="{{ route('posts.destroy', $post) }}">
                        @csrf
                        @method('DELETE')
                        <Button
                        style="color: red; border: 1px solid red"
                            class="px-4 py-2 rounded-lg bg-transparent hover:bg-red-100">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const titleInput = document.getElementById('title');
            const slugInput = document.getElementById('slug');

            if (!titleInput || !slugInput) return;

            const slugify = (value) => value
                .toString()
                .trim()
                .toLowerCase()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '')
                .replace(/--+/g, '-');

            titleInput.addEventListener('input', () => {
                slugInput.value = slugify(titleInput.value);
            });
        })();
    </script>
</x-app-layout>
