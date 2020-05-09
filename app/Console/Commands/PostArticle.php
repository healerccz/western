<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Models\Article;
use DB;

class PostArticle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'western:postarticle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'posting article';

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
     * @return mixed
     */
    public function handle()
    {
        $articles = Article::where('is_posted', '=', 0)
            ->where('post_time', '<', date('Y-m-d h:s:i', time()))
            ->get();
        foreach ($articles as $article) {
            DB::beginTransaction();
            try {
                $article->is_posted = 1;
                $article->save();
                DB::commit();
            } catch(\Exception $e) {
                DB::rollBack();
            }

        }
    }
}
