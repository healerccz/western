<?php

namespace App\Http\Controllers\Article;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Article;
use Illuminate\Support\Facades\DB;
class FindArticleController extends Controller
{
    /**
     * 根据文章id查询文章
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function findArticleById(Request $request)
    {
        if ($request->isMethod('get')) {
            $id = request('id');
            if (empty($id)) {
                return response()->json([
                    'code'  => 1001,
                    'msg'   => '数据不能为空'
                ]);
            }
            if ($id <= 0) {
                return response()->json([
                    'code'  => 1002,
                    'msg'   => '数据不合法'
                ]);
            }
            $article = Article::where('id', '=', $id)->first();
            if (!$article) {
                return response()->json([
                    'code'  => 2000,
                    'data'  => []
                ]);
            }
            $return['id'] = $article->id;
            $return['userId'] = $article->user_id;
            $return['title'] = $article->title;
            $return['tag'] = $article->tag;
            $return['type'] = $article->type;
            $return['cnt'] = $article->cnt;
            $return['author'] = $article->author;
            $return['abstract'] = $article->abstract;
            $return['createdAt'] = $article->created_at;
            $return['updatedAt'] = $article->updated_at;

            $article->cnt += 1;
            $article->save();

            return response()->json([
                'code'  => 2000,
                'data'  => $return
            ]);
        }
    }

    /**
     * 按文章类型查找，并分页展示
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function findArticleByType(Request $request)
    {
        if ($request->isMethod('get')) {
            $type = request('type');
            $page = request('page');
            $pageSize = request('pageSize');

            if (empty($type) || empty($page)) {
                return response()->json([
                    'code'  => 1001,
                    'msg'   => '数据不能为空'
                ]);
            }
            $pageSize = $pageSize > 0 ?: 12;
            if (!is_numeric($type) || !is_numeric($page) || !is_numeric($pageSize)) {
                return response()->json([
                    'code'  => 1002,
                    'msg'   => '数据不合法'
                ]);
            }
            $offset = ($page - 1) * $pageSize;
            $offset = $offset > 0 ?: 1;
//            $articles = DB::table('articles')
            $articles = Article::where('type', '=', $type)
                ->offset($offset)
                ->limit($pageSize)
                ->get();
            $return = array();
            for ($i = 0; $i < count($articles); ++$i) {
                $return[$i]['id'] = $articles[$i]->id;
                $return[$i]['userId'] = $articles[$i]->user_id;
                $return[$i]['title'] = $articles[$i]->title;
                $return[$i]['tag'] = $articles[$i]->tag;
                $return[$i]['type'] = $articles[$i]->type;
                $return[$i]['cnt'] = $articles[$i]->cnt;
                $return[$i]['author'] = $articles[$i]->author;
                $return[$i]['abstract'] = $articles[$i]->abstract;
                $return[$i]['createdAt'] = $articles[$i]->created_at;
                $return[$i]['updatedAt'] = $articles[$i]->updated_at;
                $articles[$i]->cnt += 1;
                $articles[$i]->save();  // 更新阅读次数
            }

            return response()->json([
                'code'  => 2000,
                'data'  => $return
            ]);
        }
    }
}
