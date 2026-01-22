<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
        });

        $this->backfillSlugs();

        Schema::table('posts', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropUnique('posts_slug_unique');
            $table->dropColumn('slug');
        });
    }

    private function backfillSlugs(): void
    {
        $posts = DB::table('posts')->select('id', 'title')->get();

        foreach ($posts as $post) {
            $slug = $this->makeUniqueSlug($post->title, $post->id);
            DB::table('posts')->where('id', $post->id)->update(['slug' => $slug]);
        }
    }

    private function makeUniqueSlug(string $title, int $postId): string
    {
        $baseSlug = Str::slug($title) ?: 'post';
        $slug = $baseSlug;
        $counter = 1;

        while (
            DB::table('posts')
                ->where('slug', $slug)
                ->where('id', '!=', $postId)
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
};
