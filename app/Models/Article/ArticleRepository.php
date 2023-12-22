<?php

namespace App\Models\Article;

use App\Models\Article\Article;
use Illuminate\Support\Facades\DB;

class ArticleRepository implements ArticleRepositoryInterface
{
    public function get($offset,$limit)
    {           
        $articles = Article::orderBy('id', 'desc')
        ->skip($offset)
        ->take($limit)
        ->get(['id','title','body'])->toArray();     
        return $articles;
    }

    public function count()
    {
        return Article::count();
    }

    public function getById($id)
    {
        return Article::where('id',$id)->get(['id','title','body']);
    }   

    public function findById($id)
    {
        return Article::find($id);
    }

    public function create($datas)
    {
       return Article::create($datas);
    }

    public function delete($params)
    {
        return Article::destroy($params);
    }

    public function update($params,$id)
    {
        
    }
    
}