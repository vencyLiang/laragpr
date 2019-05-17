<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/15
 * Time: 22:02
 */

namespace App\Http\Controllers;
use App\Models\Article;


class ArticlesController extends Controller
{
    function articleDetail(Article $article){
        return $article;
    }
}