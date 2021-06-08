<?php

namespace Tests\Feature\Articles;

use Illuminate\Support\Facades\DB;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SortArticlesTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function it_can_sort_articles_by_title_asc()
    {
        $article1 = Article::factory()->create(['title' => 'C Title']);
        $article2 = Article::factory()->create(['title' => 'A Title']);
        $article3 = Article::factory()->create(['title' => 'B Title']);

        $url = route('api.v1.articles.index', ['sort' => 'title']);


        $this->getJson($url)->assertSeeInOrder([
            'A Title',
            'B Title',
            'C Title',
        ]);
    }

    /** @test */
    public function it_can_sort_articles_by_title_desc()
    {
        $article1 = Article::factory()->create(['title' => 'C Title']);
        $article2 = Article::factory()->create(['title' => 'A Title']);
        $article3 = Article::factory()->create(['title' => 'B Title']);

        $url = route('api.v1.articles.index', ['sort' => '-title']);


        $this->getJson($url)->assertSeeInOrder([
            'C Title',
            'B Title',
            'A Title',
        ]);
    }

    /** @test */
    public function it_can_sort_articles_by_title_asc_and_content()
    {
        $article1 = Article::factory()->create([
            'title' => 'C Title',
            'content' => 'B Content'
        ]);
        $article2 = Article::factory()->create([
            'title' => 'A Title',
            'content' => 'C Content'
        ]);
        $article3 = Article::factory()->create([
            'title' => 'B Title',
            'content' => 'D Content'
        ]);

        $url = route('api.v1.articles.index').'?sort=title,-content';


        $this->getJson($url)->assertSeeInOrder([
            'A Title',
            'B Title',
            'C Title',
        ]);


        $url = route('api.v1.articles.index').'?sort=-content,title';


        $this->getJson($url)->assertSeeInOrder([
            'D Content',
            'C Content',
            'B Content',
        ]);
    }

    /** @test */
    public function it_can_sort_articles_by_unknow_fields()
    {
        $article1 = Article::factory()->times(3)->create();


        $url = route('api.v1.articles.index').'?sort=unknow';


        $this->getJson($url)->assertStatus(400);
    }
}
