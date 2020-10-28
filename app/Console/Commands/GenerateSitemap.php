<?php

namespace App\Console\Commands;

use App\Models\Posts;
use Backpack\PageManager\app\Models\Page;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates the sitemap of the site';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $s = Sitemap::create();

        // Posts
        foreach (Posts::all()->where('published', true) as $post) {
            $s->add(Url::create('https://verseghy-gimnazium.net/posts/'.$post->id));
        }

        // Pages
        foreach (Page::all() as $page) {
            $s->add(Url::create('https://verseghy-gimnazium.net/information/'.$page->slug));
        }

        $s->add(Url::create('https://verseghy-gimnazium.net'));
        $s->add(Url::create('https://verseghy-gimnazium.net/events'));
        $s->add(Url::create('https://verseghy-gimnazium.net/archive'));
        $s->add(Url::create('https://verseghy-gimnazium.net/canteen'));

        $s->writeToDisk('public', 'sitemap.xml');

        return 0;
    }
}
