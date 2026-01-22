<x-app-layout>
    <div
        class="py-12 bg-[#f9fafb] min-h-screen"
        x-data="{
            ready: false,
            hasData: true,
            get loading() { return this.hasData && !this.ready; }
        }"
        x-init="setTimeout(() => { ready = true; }, 500)"
    >
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Header with Back Button --}}
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h1 class="text-[32px] font-bold text-gray-900 tracking-tight">Content Preview</h1>
                    <p class="text-gray-500 text-sm mt-1">Read-only view of the published entry.</p>
                </div>
                <a href="{{ route('posts.published') }}" 
                   class="flex items-center px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back
                </a>
            </div>

            {{-- Main Content Card --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden p-12">
                {{-- 1. Title --}}
                <h2 class="text-[36px] font-bold text-gray-900 mb-6 border-b border-gray-50 pb-6">
                    <span x-show="!loading" x-cloak>{{ $post->title }}</span>
                    <span x-show="loading" x-cloak class="block h-10 w-3/4 bg-gray-200 rounded-lg animate-pulse"></span>
                </h2>

                {{-- 2. Metadata Bar (Author, Status, Updated) --}}
                <div class="flex items-center space-x-6 text-sm text-gray-500 mb-10 pb-6 border-b border-gray-50">
                    <div>
                        <span class="font-medium text-gray-400 uppercase tracking-wider text-[11px]">Author:</span>
                        <span class="ml-1 text-gray-700 font-semibold">
                            <span x-show="!loading" x-cloak>{{ $post->user->name }}</span>
                            <span x-show="loading" x-cloak class="inline-block h-3 w-24 bg-gray-200 rounded-full animate-pulse align-middle"></span>
                        </span>
                    </div>
                    <div class="flex items-center">
                        <span class="font-medium text-gray-400 uppercase tracking-wider text-[11px]">Status:</span>
                        <span
                            class="ml-2 px-3 py-0.5 rounded-full text-[12px] font-bold uppercase tracking-tight"
                            :class="loading ? 'bg-transparent text-transparent' : '{{ $post->is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}'"
                        >
                            <span x-show="!loading" x-cloak>{{ $post->is_published ? 'Published' : 'Draft' }}</span>
                            <span x-show="loading" x-cloak class="block h-3 w-14 bg-gray-200 rounded-full animate-pulse"></span>
                        </span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-400 uppercase tracking-wider text-[11px]">Updated:</span>
                        <span class="ml-1 text-gray-700 font-semibold">
                            <span x-show="!loading" x-cloak>{{ $post->updated_at->format('M d, Y') }}</span>
                            <span x-show="loading" x-cloak class="inline-block h-3 w-24 bg-gray-200 rounded-full animate-pulse align-middle"></span>
                        </span>
                    </div>
                </div>

                {{-- 3. THE MISSING BODY CONTENT --}}
                {{-- ... Previous Header and Metadata Bar ... --}}
                <div class="mt-8 prose prose-slate max-w-none">
                    <div class="text-[16px] text-gray-700 leading-relaxed space-y-4">
                        <div x-show="!loading" x-cloak>
                            {{-- Use $post->body to match your database column --}}
                            {!! nl2br(e($post->body)) !!}
                        </div>
                        <div x-show="loading" x-cloak class="space-y-3">
                            <div class="h-3 w-full bg-gray-200 rounded-full animate-pulse"></div>
                            <div class="h-3 w-11/12 bg-gray-200 rounded-full animate-pulse"></div>
                            <div class="h-3 w-10/12 bg-gray-200 rounded-full animate-pulse"></div>
                            <div class="h-3 w-9/12 bg-gray-200 rounded-full animate-pulse"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
