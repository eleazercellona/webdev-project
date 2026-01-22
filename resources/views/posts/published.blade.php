<x-app-layout>
    {{-- 1. Shared Header Section --}}
    <div class="pt-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-3xl text-gray-800 leading-tight">
                        @role('admin') Published (Admin) @else My Published Content @endrole
                    </h2>
                    <p class="text-gray-500 text-sm mt-1">Browse published content entries.</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('posts.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium shadow-sm">
                        Back to Content
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Filter Bar always stays visible --}}
            <form action="{{ route('posts.published') }}" method="GET" class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm mb-6 flex justify-between items-center">
                <div class="flex space-x-4">
                    <div class="inline-flex items-center px-4 py-2 border border-gray-200 rounded-lg bg-white">
                        <select name="sort" onchange="this.form.submit()" class="text-sm font-medium text-gray-700 bg-transparent border-none focus:ring-0 p-0 pr-8 cursor-pointer">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                        </select>
                    </div>
                </div>
                @if(request()->has('sort'))
                    <a href="{{ route('posts.published') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition">Clear</a>
                @endif
            </form>

            {{-- Conditional Content: Show Table only if posts exist --}}
            @if($dashboardPosts->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                    <div class="p-0">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-white">
                                    <th class="px-8 py-5 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest">Title</th>
                                    <th class="px-8 py-5 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest">Slug</th>
                                    @role('admin')
                                    <th class="px-8 py-5 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest">Author</th>
                                    @endrole
                                    <th class="px-8 py-5 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest">Updated</th>
                                    <th class="px-8 py-5 text-right text-[11px] font-bold text-gray-400 uppercase tracking-widest">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-50">
                                @foreach ($dashboardPosts as $post)
                                <tr class="hover:bg-gray-50/50 transition-colors group">
                                    <td class="px-8 py-5 text-[14px] font-medium text-blue-600 truncate max-w-[240px]">
                                        {{ $post->title }}
                                    </td>
                                    <td class="px-8 py-5 text-[13px] text-gray-400 italic">
                                        {{ $post->slug }}
                                    </td>
                                    @role('admin')
                                    <td class="px-8 py-5 text-sm font-bold text-gray-800">{{ $post->user->name }}</td>
                                    @endrole
                                    <td class="px-8 py-5 text-[13px] text-gray-400">{{ $post->updated_at->format('M d, Y') }}</td>
                                    <td class="px-8 py-5 text-right whitespace-nowrap">
                                        <a href="{{ route('posts.preview', $post) }}" class="text-[14px] font-medium text-blue-500 hover:text-blue-700 transition">Preview</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{-- Pagination Footer --}}
                        <div class="px-8 py-5 bg-white border-t border-gray-100 flex justify-between items-center">
                            <div class="text-[13px] text-gray-400 font-medium italic">
                                Showing {{ $dashboardPosts->firstItem() }}-{{ $dashboardPosts->lastItem() }} of {{ $dashboardPosts->total() }}
                            </div>
                            {{-- ... (Your pagination number boxes) ... --}}
                        </div>
                    </div>
                </div>
            @else
                {{-- Empty State: Table headers are completely removed here --}}
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                    <div class="py-20 text-center">
                        <div class="flex flex-col items-center">
                            <svg class="w-12 h-12 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-gray-400 font-medium">No published content</p>
                            <p class="text-gray-300 text-xs mt-1">Publish content entries to make them appear here.</p>
                            <a href="{{ route('posts.index') }}" class="mt-4 px-4 py-2 border border-gray-200 text-gray-700 rounded-lg text-sm font-bold shadow-sm hover:bg-gray-50 transition">
                                Back to Content
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
