<x-app-layout>
    @php($hasContent = $posts->total() > 0)
    <div
        x-data="{
            ready: {{ $hasContent ? 'false' : 'true' }},
            hasData: {{ $hasContent ? 'true' : 'false' }},
            deleteModalOpen: false,
            deleteAction: '',
            deleteTitle: '',
            get loading() { return this.hasData && !this.ready; },
            openDelete(action, title) {
                this.deleteAction = action;
                this.deleteTitle = title;
                this.deleteModalOpen = true;
            }
        }"
        x-init="
            if (!hasData) { ready = true; return; }
            setTimeout(() => { ready = true; }, 500);
        "
    >
        {{-- Header Section --}}
        <div class="pt-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="font-bold text-3xl text-gray-800 leading-tight">Content</h2>
                        <p class="text-gray-500 text-sm mt-1">Manage and organize your content entries.</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('posts.published') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium shadow-sm transition">
                            View Published
                        </a>
                        <a href="{{ route('posts.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium shadow-sm flex items-center transition">
                            <span class="mr-1">+</span> New Content
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                {{-- Filter Bar Section --}}
                <form action="{{ route('posts.index') }}" method="GET" class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm mb-6 flex justify-between items-center">
                    <div class="flex space-x-4">
                        {{-- Status Dropdown --}}
                        <div class="inline-flex items-center px-4 py-2 border border-gray-200 rounded-lg">
                            <select name="status" onchange="this.form.submit()" class="text-sm font-medium text-gray-700 bg-transparent border-none focus:ring-0 p-0 pr-8 cursor-pointer">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                        </div>

                        {{-- Sort Dropdown (Newest/Oldest) --}}
                        <div class="inline-flex items-center px-4 py-2 border border-gray-200 rounded-lg">
                            <select name="sort" onchange="this.form.submit()" class="text-sm font-medium text-gray-700 bg-transparent border-none focus:ring-0 p-0 pr-8 cursor-pointer">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                            </select>
                        </div>
                    </div>

                    {{-- The Blue Clear Button --}}
                    <a href="{{ route('posts.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition">
                        Clear
                    </a>
                </form>

                {{-- Table Card --}}
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                    <div class="p-0">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-white">
                                    <th class="px-8 py-5 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest">Title</th>
                                    <th class="px-8 py-5 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest">Slug</th>
                                    <th class="px-8 py-5 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest">Status</th>
                                    @role('admin')
                                    <th class="px-8 py-5 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest">Author</th>
                                    @endrole
                                    <th class="px-8 py-5 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest">Updated</th>
                                    <th class="px-8 py-5 text-right text-[11px] font-bold text-gray-400 uppercase tracking-widest">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-50">
                                @forelse ($posts as $post)
                                @php($statusClass = $post->is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500')
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-8 py-5 text-[14px] font-medium text-blue-600 truncate max-w-[240px]">
                                        <span x-show="!loading" x-cloak>{{ $post->title }}</span>
                                        <span x-show="loading" x-cloak class="block h-3 w-48 bg-gray-200 rounded-full animate-pulse"></span>
                                    </td>
                                    <td class="px-8 py-5 text-[13px] text-gray-400 font-normal truncate max-w-[180px]">
                                        <span x-show="!loading" x-cloak>{{ $post->slug }}</span>
                                        <span x-show="loading" x-cloak class="block h-3 w-40 bg-gray-200 rounded-full animate-pulse"></span>
                                    </td>
                                    <td class="px-8 py-5">
                                        <span
                                            class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-tight"
                                            :class="loading ? 'bg-transparent text-transparent' : '{{ $statusClass }}'"
                                        >
                                            <span x-show="!loading" x-cloak>{{ $post->is_published ? 'Published' : 'Draft' }}</span>
                                            <span x-show="loading" x-cloak class="block h-3 w-12 bg-gray-200 rounded-full animate-pulse"></span>
                                        </span>
                                    </td>
                                    @role('admin')
                                    <td class="px-8 py-5 text-sm font-bold text-gray-800">
                                        <span x-show="!loading" x-cloak>{{ $post->user->name }}</span>
                                        <span x-show="loading" x-cloak class="block h-4 w-16 bg-gray-200 rounded-full animate-pulse"></span>
                                    </td>
                                    @endrole
                                    <td class="px-8 py-5 text-[13px] text-gray-400">
                                        <span class="inline-block min-w-[96px]">
                                            <span x-show="!loading" x-cloak>{{ $post->updated_at->format('M d, Y') }}</span>
                                            <span x-show="loading" x-cloak class="block h-3 w-[0px] bg-gray-200 rounded-full animate-pulse"></span>
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-right whitespace-nowrap space-x-3">
                                        <a href="{{ route('posts.edit', $post) }}" class="text-[14px] font-semibold text-blue-500 hover:text-blue-700">Edit</a>
                                        <button
                                            type="button"
                                            class="text-[14px] font-semibold text-red-400 hover:text-red-600 transition"
                                            @click="openDelete('{{ route('posts.destroy', $post) }}', '{{ addslashes($post->title) }}')"
                                        >
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="py-20 text-center">
                                        <p class="text-gray-400 italic text-sm">No content found matching these filters.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                        {{-- Numbered Pagination Footer --}}
                        <div class="px-8 py-5 bg-white border-t border-gray-100 flex justify-between items-center">
                            <div class="text-[13px] text-gray-400 font-medium italic">
                                <span x-show="!loading" x-cloak>Showing {{ $posts->firstItem() }}-{{ $posts->lastItem() }} of {{ $posts->total() }}</span>
                                <span x-show="loading" x-cloak class="block h-3 w-52 bg-gray-200 rounded-full animate-pulse"></span>
                            </div>
                            <div class="flex items-center space-x-4">
                                @if ($posts->onFirstPage()) <span class="text-[13px] text-gray-300">Prev</span>
                                @else <a href="{{ $posts->previousPageUrl() }}" class="text-[13px] text-gray-500 font-medium transition">Prev</a> @endif
                                <div class="flex items-center space-x-2">
                                    @foreach ($posts->getUrlRange(1, $posts->lastPage()) as $page => $url)
                                        @if ($page == $posts->currentPage())
                                            <span class="w-8 h-8 flex items-center justify-center bg-blue-600 text-white rounded-lg text-[13px] font-bold shadow-sm">
                                                <span x-show="!loading" x-cloak>{{ $page }}</span>
                                                <span x-show="loading" x-cloak class="block h-3 w-6 bg-blue-500 rounded-full animate-pulse"></span>
                                            </span>
                                        @else
                                            <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-100 rounded-lg text-[13px] font-medium">
                                                <span x-show="!loading" x-cloak>{{ $page }}</span>
                                                <span x-show="loading" x-cloak class="block h-3 w-6 bg-gray-200 rounded-full animate-pulse"></span>
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                                @if ($posts->hasMorePages()) <a href="{{ $posts->nextPageUrl() }}" class="text-[13px] text-gray-500 font-medium transition">Next</a>
                                @else <span class="text-[13px] text-gray-300">Next</span> @endif
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        {{-- Delete Modal --}}
        <div
            x-show="deleteModalOpen"
            x-cloak
            x-transition
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
        >
            <div
                @click.outside="deleteModalOpen = false"
                class="bg-white rounded-xl shadow-xl w-full max-w-md p-6"
            >
                <div class="flex items-start gap-3">
                    <div class="text-red-500 text-xl">⚠️</div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Delete content?</h2>
                        <p class="text-sm text-gray-500 mt-1">
                            This action cannot be undone. This will permanently remove the content entry.
                        </p>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-3 mt-4 flex justify-between text-sm">
                    <span class="text-gray-500">Entry</span>
                    <span class="font-semibold text-gray-800" x-text="deleteTitle"></span>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button
                        type="button"
                        @click="deleteModalOpen = false"
                        class="px-4 py-2 rounded-lg border text-gray-700 hover:bg-gray-100">
                        Cancel
                    </button>

                    <form method="POST" :action="deleteAction">
                        @csrf
                        @method('DELETE')
                        <button
                            type="submit"
                            style="color: red; border: 1px solid red"
                            class="px-4 py-2 rounded-lg bg-transparent hover:bg-red-100">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
