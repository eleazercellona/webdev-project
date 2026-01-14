<x-app-layout>
    {{-- Header Section --}}
    <div class="pt-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-3xl text-gray-800 leading-tight">
                        Published
                    </h2>
                    <p class="text-gray-500 text-sm mt-1">
                        Welcome to your content management system.
                    </p>
                </div>

                <div class="flex space-x-3">
                    <button
                        class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium shadow-sm">
                        View Published
                    </button>

                    <a href="{{ route('posts.create') }}"
                        class="px-4 py-2 primary_bgcolor text-white rounded-lg hover:bg-blue-700 font-medium shadow-sm flex items-center">
                        <span class="mr-1">+</span> New Content
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Section --}}
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-100">
                    <div class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">
                        Total Content
                    </div>
                    <div class="text-4xl font-bold text-gray-900">
                        {{ $globalTotalContent }}
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-100">
                    <div class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">
                        Published
                    </div>
                    <div class="text-4xl font-bold text-gray-900">
                        {{ $globalPublishedCount }}
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-100">
                    <div class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">
                        Drafts
                    </div>
                    <div class="text-4xl font-bold text-gray-900">
                        {{ $globalDraftCount }}
                    </div>
                </div>
            </div>

            {{-- Table Section --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="p-6">

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">
                                    Title
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">
                                    Author
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">
                                    Actions
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($dashboardPosts as $post)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                            {{ $post->title }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $post->is_published
                                                ? 'bg-green-100 text-green-800'
                                                : 'bg-gray-100 text-gray-600' }}">
                                            {{ $post->is_published ? 'Published' : 'Draft' }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $post->user->name }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                        <a href="{{ route('posts.edit', $post) }}"
                                            class="primary_color hover:text-blue-700 font-semibold">
                                            Edit
                                        </a>

                                        <form method="POST"
                                            action="{{ route('posts.destroy', $post) }}"
                                            class="inline"
                                            onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                class="text-red-500 hover:text-red-700 font-semibold">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                   
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
