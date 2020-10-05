<?php

use App\Markdown;
use App\Models\Posts;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MarkdownToHtml extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $postEntries = Posts::all();

        foreach ($postEntries as $post) {
            $parser = Markdown::instance()->setBreaksEnabled(true)->setMarkupEscaped(true)->setUrlsLinked(false);

            $post->content = $post->content ? $parser->parse($post->content) : $post->content;

            $post->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('html', function (Blueprint $table) {
        });
    }
}
