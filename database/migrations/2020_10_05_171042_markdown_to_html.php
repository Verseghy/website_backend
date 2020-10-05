<?php

use App\Markdown;
use App\Models\Posts;
use Illuminate\Database\Migrations\Migration;

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
}
