<x-app-layout>
    <div class="py-12 bg-[#f9fafb] min-h-screen">
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
                    {{ $post->title }}
                </h2>

                {{-- 2. Metadata Bar (Author, Status, Updated) --}}
                <div class="flex items-center space-x-6 text-sm text-gray-500 mb-10 pb-6 border-b border-gray-50">
                    <div>
                        <span class="font-medium text-gray-400 uppercase tracking-wider text-[11px]">Author:</span>
                        <span class="ml-1 text-gray-700 font-semibold">{{ $post->user->name }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="font-medium text-gray-400 uppercase tracking-wider text-[11px]">Status:</span>
                        <span class="ml-2 px-3 py-0.5 bg-green-100 text-green-700 rounded-full text-[12px] font-bold uppercase tracking-tight">
                            {{ $post->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-400 uppercase tracking-wider text-[11px]">Updated:</span>
                        <span class="ml-1 text-gray-700 font-semibold">{{ $post->updated_at->format('M d, Y') }}</span>
                    </div>
                </div>

                {{-- 3. THE MISSING BODY CONTENT --}}
                {{-- ... Previous Header and Metadata Bar ... --}}
                <div class="mt-8 prose prose-slate max-w-none">
                    <div class="text-[16px] text-gray-700 leading-relaxed space-y-4">
                        {{-- Use $post->body to match your database column --}}
                        {!! nl2br(e($post->body)) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>