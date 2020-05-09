<?php

namespace App\Http\Controllers\Article;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Models\Article;

class ModifyArticleController extends Controller
{
    /**
     * check data
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $message = [
            'required'  => '数据不能为空',
            'string'    => '数据不合法'
        ];
        return Validator::make($data, [
            'id'        => 'required|numeric',
            'title'     => 'required|string|max:64',
            'tag'       => 'required|string|max:32',
            'type'      => 'required|string|max:4',
            'picture'   => 'required|string|max:1024',
            'author'    => 'required|string|max:32',
            'abstract'  => 'present'
        ], $message);
    }

    /**
     * create a new article
     *
     * @param array $data
     */
    protected function create(array $data)
    {
//        Article::create($data);
        $article = Article::where('id', '=', $data['id'])->first();
        $article->user_id = $data['userId'];
        $article->title = $data['title'];
        $article->tag = $data['tag'];
        $article->type = $data['type'];
        $article->picture = $data['picture'];
        $article->cnt = $data['cnt'];
        $article->author = $data['author'];
        $article->abstract = $data['abstract'];
        $article->save();
    }

    /**
     * modify a new article
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function modifyArticle(Request $request)
    {
        if ($request->isMethod('post')) {

            $validator = $this->validator($request->all());
            if ($validator->fails()) {
                $errors = $validator->errors()->first();
                if ($errors == "数据不能为空") {
                    $code = 1001;
                } else if ($errors == "数据不合法") {
                    $code = 1002;
                } else {
                    $code = 5000;
                }
                return response()->json([
                    'code'  => $code,
                    'msg'   => $errors
                ]);
            }
            $data['userId']     =   1;
            $data['title']      = request('title');
            $data['tag']        = request('tag');
            $data['type']       = request('type');
            $data['picture']    = request('picture');
            $data['cnt']        = 0;
            $data['author']     = request('author');
            $data['abstract']   = request('abstract');
            $data['id']         = request('id');

//            var_dump($data);

            if (!($ret = $this->create($data))) {
                return response()->json([
                    'code'  => 2000,
                    'data'  => $data
                ]);
            } else {
                return response()->json([
                    'code'  => 5000,
                    'msg'   => '未知错误'
                ]);
            }
        }
    }
}
