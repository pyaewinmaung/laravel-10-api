<?php

namespace App\Models\Article;

interface ArticleRepositoryInterface
{
    public function get($offset,$limit);

    public function getById($id);

    public function findById($id);

    public function create($params);

    public function delete($params);

    public function update($params,$id);

    public function count();
        
}