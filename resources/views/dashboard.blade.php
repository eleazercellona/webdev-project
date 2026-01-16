<x-app-layout>
    @role('admin')
        {{-- ==========================================
             ADMIN VIEW: Your original working code
             ========================================== --}}
        <div class="pt-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="font-bold text-3xl text-gray-800 leading-tight">Dashboard</h2>
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
                        <div class="text-4xl font-bold text-gray-900">{{ $total }}</div>
                    </div>
                    <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-100">
                        <div class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Published</div>
                        <div class="text-4xl font-bold text-gray-900">{{ $published }}</div>
                    </div>
                    <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-100">
                        <div class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Drafts</div>
                        <div class="text-4xl font-bold text-gray-900">{{ $drafts }}</div>
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
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-blue-600 hover:text-blue-800">{{ $post->title }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $post->is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                                {{ $post->is_published ? 'Published' : 'Draft' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $post->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                            <a href="{{ route('posts.edit', $post) }}" class="primary_color hover:text-blue-700 font-semibold">Edit</a>
                                            <form method="POST" action="{{ route('posts.destroy', $post) }}" class="inline" onsubmit="return confirm('Are you sure?');">
                                                @csrf @method('DELETE')
                                                <button class="text-red-500 hover:text-red-700 font-semibold">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                         {{-- Pagination Footer for Admin Table --}}
                        <div class="px-6 py-4 bg-white border-t border-gray-100 flex justify-between items-center">
                            <div class="text-[13px] text-gray-400 font-medium">
                                Showing {{ $dashboardPosts->firstItem() }}-{{ $dashboardPosts->lastItem() }} of {{ $dashboardPosts->total() }}
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
                                            <span class="w-8 h-8 flex items-center justify-center bg-blue-600 text-white rounded-lg text-[13px] font-bold shadow-sm">{{ $page }}</span>
                                        @else
                                            <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-100 rounded-lg text-[13px] font-medium transition">{{ $page }}</a>
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
        </div>
    @else
        {{-- ==========================================
             USER VIEW: Two-column specialized layout
             ========================================== --}}
                <div class="py-12 bg-[#f9fafb] min-h-screen">
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
                            <div class="text-4xl font-bold text-gray-900">{{ $total }}</div>
                        </div>
                        <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm">
                            <div class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2">Published</div>
                            <div class="text-4xl font-bold text-gray-900">{{ $published }}</div>
                        </div>
                        <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm">
                            <div class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2">Drafts</div>
                            <div class="text-4xl font-bold text-gray-900">{{ $drafts }}</div>
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
                                                <div class="text-[15px] font-semibold text-blue-600 group-hover:text-blue-800">{{ $draft->title }}</div>
                                                <div class="text-xs text-gray-400 mt-1">Edited {{ $draft->updated_at->diffForHumans() }}</div>
                                            </div>
                                            <div class="flex items-center space-x-5">
                                                <span class="px-2.5 py-1 bg-gray-50 text-gray-400 rounded text-[10px] font-bold uppercase border border-gray-100 tracking-tight">Draft</span>
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
                                                <div class="text-[15px] font-semibold text-blue-600 group-hover:text-blue-800">{{ $post->title }}</div>
                                                <div class="text-xs text-gray-400 mt-1">Published {{ $post->updated_at->diffForHumans() }}</div>
                                            </div>
                                            <div class="flex items-center space-x-5">
                                                <span class="px-2.5 py-1 bg-green-50 text-green-600 rounded text-[10px] font-bold uppercase border border-green-100 tracking-tight">Published</span>
                                                <a href="{{ route('posts.preview', $post) }}" class="text-sm font-bold text-blue-500 hover:text-blue-700">View</a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
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