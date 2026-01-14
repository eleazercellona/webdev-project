<x-app-layout>
    <!-- Header -->
    <div class="pt-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-bold text-3xl text-gray-800">
                Create Content
            </h2>
            <p class="text-gray-500 text-sm mt-1">
                Draft and publish content entries.
            </p>
        </div>
    </div>

    <!-- Main -->
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('posts.store') }}">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <!-- LEFT: Content Details -->
                    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-6">
                            Content Details
                        </h3>

                        <!-- Title -->
                        <div class="mb-4">
                            <x-input-label for="title" value="Title" />
                            <x-text-input
                                id="title"
                                name="title"
                                type="text"
                                class="mt-1 block w-full"
                                placeholder="Enter content title"
                                required
                            />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Slug (optional but matches design) -->
                        <div class="mb-4">
                            <x-input-label for="slug" value="Slug" />
                            <x-text-input
                                id="slug"
                                name="slug"
                                type="text"
                                class="mt-1 block w-full"
                                placeholder="content-url-slug"
                            />
                            <p class="text-xs text-gray-400 mt-1">
                                URL-friendly version of the title
                            </p>
                        </div>

                        <!-- Body -->
                        <div>
                            <x-input-label for="body" value="Body" />
                            <textarea
                                id="body"
                                name="body"
                                rows="10"
                                class="mt-1 block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Write your content here..."
                                required
                            ></textarea>
                            <x-input-error :messages="$errors->get('body')" class="mt-2" />
                        </div>
                    </div>

                    <!-- RIGHT: Publish -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 h-fit">
                        <h3 class="text-lg font-semibold text-gray-800 mb-6">
                            Publish
                        </h3>

                        <!-- Status -->
                        <div class="mb-4">
                            <x-input-label for="is_published" value="Status" />
                            <select
                                id="is_published"
                                name="is_published"
                                class="mt-1 block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            >
                                <option value="0">Draft</option>
                                <option value="1">Published</option>
                            </select>
                        </div>

                        <!-- Meta info -->
                        <div class="text-sm text-gray-500 space-y-1 mb-6">
                            <div>
                                <span class="font-medium text-gray-700">Author:</span>
                                {{ auth()->user()->name }}
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Last saved:</span>
                                —
                            </div>
                        </div>

                        <!-- Buttons -->
                        <button
                            type="submit"
                            class="w-full primary_bgcolor hover:bg-blue-700 text-white font-medium py-2 rounded-lg mb-3"
                        >
                            Save
                        </button>

                        <button
                            type="submit"
                            name="save_as_draft"
                            value="1"
                            class="w-full border border-gray-300 text-gray-700 font-medium py-2 rounded-lg mb-3 hover:bg-gray-50"
                        >
                            Save as Draft
                        </button>

                        <a
                            href="{{ route('posts.index') }}"
                            class="block text-center text-blue-600 text-sm hover:underline"
                        >
                            Cancel
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>
</x-app-layout>
