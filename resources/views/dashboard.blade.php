<x-app-layout>
    @role('admin')
        {{-- ==========================================
             ADMIN VIEW: Your original working code
             ========================================== --}}
        @php($adminHasData = $dashboardPosts->total() > 0)
        <div
            x-data="{
                ready: {{ $adminHasData ? 'false' : 'true' }},
                hasData: {{ $adminHasData ? 'true' : 'false' }},
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
            <div class="pt-10">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="flex justify-between items-center">
                            <div>
                            <h2 class="font-bold text-3xl text-gray-800 leading-tight">Admin Dashboard</h2>
                            <p class="text-gray-500 text-sm mt-1">Welcome to your content management system.</p>
                            </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('posts.published') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium shadow-sm">
                                View Published
                            </a>
                            <a href="{{ route('posts.create') }}" class="px-4 py-2 primary_bgcolor text-white rounded-lg hover:bg-blue-700 font-medium shadow-sm flex items-center">
                                <span class="mr-1">+</span> New Content
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="py-10">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-100">
                            <div class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Total Content</div>
                            <div class="text-4xl font-bold text-gray-900">
                                <span x-show="!loading" x-cloak>{{ $total }}</span>
                                <div x-show="loading" x-cloak class="h-9 w-16 bg-gray-200 rounded-xl animate-pulse"></div>
                            </div>
                        </div>
                        <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-100">
                            <div class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Published</div>
                            <div class="text-4xl font-bold text-gray-900">
                                <span x-show="!loading" x-cloak>{{ $published }}</span>
                                <div x-show="loading" x-cloak class="h-9 w-16 bg-gray-200 rounded-xl animate-pulse"></div>
                            </div>
                        </div>
                        <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-100">
                            <div class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Drafts</div>
                            <div class="text-4xl font-bold text-gray-900">
                                <span x-show="!loading" x-cloak>{{ $drafts }}</span>
                                <div x-show="loading" x-cloak class="h-9 w-16 bg-gray-200 rounded-xl animate-pulse"></div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                        <div class="p-6">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Title</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Author</th>
                                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($userDrafts->merge($userPublished) as $post) {{-- Merged for Admin View --}}
                                        @php($statusClass = $post->is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600')
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-blue-600 hover:text-blue-800 max-w-[320px] truncate">
                                                    <span x-show="!loading" x-cloak>{{ $post->title }}</span>
                                                    <span x-show="loading" x-cloak class="block h-4 w-[320px] bg-gray-200 rounded-full animate-pulse"></span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="w-32">
                                                    <span
                                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                        :class="loading ? 'bg-transparent text-transparent' : '{{ $statusClass }}'"
                                                        x-show="!loading"
                                                        x-cloak
                                                    >
                                                        {{ $post->is_published ? 'Published' : 'Draft' }}
                                                    </span>
                                                    <span x-show="loading" x-cloak class="block h-3 w-full bg-gray-200 rounded-full animate-pulse"></span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                <span x-show="!loading" x-cloak>{{ $post->user->name }}</span>
                                                <span x-show="loading" x-cloak class="block h-3 w-32 bg-gray-200 rounded-full animate-pulse"></span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                                <a href="{{ route('posts.edit', $post) }}" class="primary_color hover:text-blue-700 font-semibold">Edit</a>
                                                <button
                                                    type="button"
                                                    class="text-red-500 hover:text-red-700 font-semibold"
                                                    @click="openDelete('{{ route('posts.destroy', $post) }}', '{{ addslashes($post->title) }}')"
                                                >
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                             {{-- Pagination Footer for Admin Table --}}
                            <div class="px-6 py-4 bg-white border-t border-gray-100 flex justify-between items-center">
                                <div class="text-[13px] text-gray-400 font-medium">
                                    <span x-show="!loading" x-cloak>Showing {{ $dashboardPosts->firstItem() }}-{{ $dashboardPosts->lastItem() }} of {{ $dashboardPosts->total() }}</span>
                                    <span x-show="loading" x-cloak class="block h-3 w-52 bg-gray-200 rounded-full animate-pulse"></span>
                                </div>

                                <div class="flex items-center space-x-4">
                                    {{-- Previous Page Link --}}
                                    @if ($dashboardPosts->onFirstPage())
                                        <span class="text-[13px] text-gray-300">Prev</span>
                                    @else
                                        <a href="{{ $dashboardPosts->previousPageUrl() }}" class="text-[13px] text-gray-500 hover:text-gray-800 transition">Prev</a>
                                    @endif

                                    {{-- Page Numbers --}}
                                    <div class="flex items-center space-x-2">
                                        @foreach ($dashboardPosts->getUrlRange(1, $dashboardPosts->lastPage()) as $page => $url)
                                            @if ($page == $dashboardPosts->currentPage())
                                                <span class="w-8 h-8 flex items-center justify-center bg-blue-600 text-white rounded-lg text-[13px] font-bold shadow-sm">
                                                    <span x-show="!loading" x-cloak>{{ $page }}</span>
                                                    <span x-show="loading" x-cloak class="block h-3 w-6 bg-blue-500 rounded-full animate-pulse"></span>
                                                </span>
                                            @else
                                                <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-100 rounded-lg text-[13px] font-medium transition">
                                                    <span x-show="!loading" x-cloak>{{ $page }}</span>
                                                    <span x-show="loading" x-cloak class="block h-3 w-6 bg-gray-200 rounded-full animate-pulse"></span>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>

                                    {{-- Next Page Link --}}
                                    @if ($dashboardPosts->hasMorePages())
                                        <a href="{{ $dashboardPosts->nextPageUrl() }}" class="text-[13px] text-gray-500 hover:text-gray-800 transition">Next</a>
                                    @else
                                        <span class="text-[13px] text-gray-300">Next</span>
                                    @endif
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
        </div>
    @else
        {{-- ==========================================
             USER VIEW: Two-column specialized layout
             with in-page skeleton placeholders
             ========================================== --}}
            @php($userHasPosts = ($userDrafts->count() + $userPublished->count()) > 0)
            <div
                class="py-12 bg-[#f9fafb] min-h-screen"
                x-data="{
                    ready: {{ $userHasPosts ? 'false' : 'true' }},
                    hasData: {{ $userHasPosts ? 'true' : 'false' }},
                    get loading() { return this.hasData && !this.ready; }
                }"
                x-init="
                    if (!hasData) { ready = true; return; }
                    setTimeout(() => { ready = true; }, 500);
                "
            >
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {{-- 1. Header --}}
                    <div class="mb-8">
                        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Dashboard</h1>
                        <p class="text-gray-500 text-sm mt-1">Welcome to your content management system.</p>
                    </div>

                    {{-- 2. Stats Cards --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                        <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm">
                            <div class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2">Total Content</div>
                            <div class="text-4xl font-bold text-gray-900">
                                <span x-show="!loading" x-cloak>{{ $total }}</span>
                                <div x-show="loading" x-cloak class="h-9 w-16 bg-gray-200 rounded-xl animate-pulse"></div>
                            </div>
                        </div>
                        <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm">
                            <div class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2">Published</div>
                            <div class="text-4xl font-bold text-gray-900">
                                <span x-show="!loading" x-cloak>{{ $published }}</span>
                                <div x-show="loading" x-cloak class="h-9 w-16 bg-gray-200 rounded-xl animate-pulse"></div>
                            </div>
                        </div>
                        <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm">
                            <div class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2">Drafts</div>
                            <div class="text-4xl font-bold text-gray-900">
                                <span x-show="!loading" x-cloak>{{ $drafts }}</span>
                                <div x-show="loading" x-cloak class="h-9 w-16 bg-gray-200 rounded-xl animate-pulse"></div>
                            </div>
                        </div>
                    </div>

                    {{-- 3. Two-Column Grid --}}
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                        
                        {{-- Left Side: Drafts and Published Lists --}}
                        <div class="lg:col-span-2 space-y-8">
                            
                            {{-- Drafts Section --}}
                            @if($userDrafts->count() > 0)
                            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                                <div class="p-8 divide-y divide-gray-50">
                                    @foreach($userDrafts as $draft)
                                        <div class="py-5 flex justify-between items-center group first:pt-0 last:pb-0">
                                            <div>
                                                <div class="text-[15px] font-semibold text-blue-600 group-hover:text-blue-800">
                                                    <span x-show="!loading" x-cloak>{{ $draft->title }}</span>
                                                    <span x-show="loading" x-cloak class="block h-4 w-48 bg-gray-200 rounded-full animate-pulse"></span>
                                                </div>
                                                <div class="text-xs text-gray-400 mt-1">
                                                    <span x-show="!loading" x-cloak>Edited {{ $draft->updated_at->diffForHumans() }}</span>
                                                    <span x-show="loading" x-cloak class="block h-3 w-32 bg-gray-200 rounded-full animate-pulse"></span>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-5">
                                                <span
                                                    class="px-2.5 py-1 rounded text-[10px] font-bold uppercase tracking-tight"
                                                    :class="loading ? 'bg-transparent text-transparent border-transparent' : 'bg-gray-50 text-gray-400 border border-gray-100'"
                                                >
                                                    <span x-show="!loading" x-cloak>Draft</span>
                                                    <span x-show="loading" x-cloak class="block h-3 w-10 bg-gray-200 rounded-full animate-pulse"></span>
                                                </span>
                                                <a href="{{ route('posts.edit', $draft) }}" class="text-sm font-bold text-blue-500 hover:text-blue-700">Continue</a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @else
                                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
                                    <p class="text-gray-400 text-sm">No drafts yet.</p>
                                </div>
                            @endif

                            {{-- Published Section --}}
                            @if($userPublished->count() > 0)
                                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                                    <div class="p-8 divide-y divide-gray-50">
                                        @foreach($userPublished as $post)
                                            <div class="py-5 flex justify-between items-center group first:pt-0 last:pb-0">
                                                <div>
                                                    <div class="text-[15px] font-semibold text-blue-600 group-hover:text-blue-800">
                                                        <span x-show="!loading" x-cloak>{{ $post->title }}</span>
                                                        <span x-show="loading" x-cloak class="block h-4 w-48 bg-gray-200 rounded-full animate-pulse"></span>
                                                    </div>
                                                    <div class="text-xs text-gray-400 mt-1">
                                                        <span x-show="!loading" x-cloak>Published {{ $post->updated_at->diffForHumans() }}</span>
                                                        <span x-show="loading" x-cloak class="block h-3 w-32 bg-gray-200 rounded-full animate-pulse"></span>
                                                    </div>
                                                </div>
                                                <div class="flex items-center space-x-5">
                                                    <span
                                                        class="px-2.5 py-1 rounded text-[10px] font-bold uppercase tracking-tight"
                                                        :class="loading ? 'bg-transparent text-transparent border-transparent' : 'bg-green-50 text-green-600 border border-green-100'"
                                                    >
                                                        <span x-show="!loading" x-cloak>Published</span>
                                                        <span x-show="loading" x-cloak class="block h-3 w-12 bg-gray-200 rounded-full animate-pulse"></span>
                                                    </span>
                                                    <a href="{{ route('posts.preview', $post) }}" class="text-sm font-bold text-blue-500 hover:text-blue-700">View</a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
                                    <p class="text-gray-400 text-sm">No published content yet.</p>
                                </div>
                            @endif
                        </div>

                        {{-- Right Side: Quick Actions --}}
                        <div class="space-y-6">
                            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-3">
                                <a href="{{ route('posts.create') }}" class="w-full flex items-center justify-center py-4 bg-blue-600 text-white rounded-xl font-bold text-[14px] hover:bg-blue-700 transition">
                                    <span class="mr-2 text-lg">+</span> New Content
                                </a>
                                <a href="{{ route('posts.index', ['status' => 'draft']) }}" class="w-full flex items-center justify-center py-4 bg-white border border-gray-200 text-gray-700 rounded-xl font-bold text-[14px] hover:bg-gray-50 transition">
                                    View My Drafts
                                </a>
                                <a href="{{ route('posts.published') }}" class="w-full flex items-center justify-center py-4 bg-white border border-gray-200 text-gray-700 rounded-xl font-bold text-[14px] hover:bg-gray-50 transition">
                                    View My Published
                                </a>
                            </div>

                            <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm">
                                <p class="text-gray-400 text-xs leading-relaxed font-medium italic">
                                    Drafts stay private until you publish them. Use clear titles and keep slugs short and readable.
                                </p>
                            </div>
                        </div>
                    </div> {{-- End Grid --}}
                </div>
            </div>
    @endrole
</x-app-layout>
