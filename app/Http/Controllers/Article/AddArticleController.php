<?php

namespace App\Http\Controllers\Article;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Http\Models\Article;

class AddArticleController extends Controller
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
            'string'    => '数据不合法',
            'numeric'    => '数据不合法',
        ];
        return Validator::make($data, [
            'title'     => 'required|string',
            'tag'       => 'required|string',
            'type'      => 'required|string',
            'picture'   => 'required|string',
            'author'    => 'required|string',
            'postTime'  => 'required|string',
            'content'   => 'required|string',
            'nodeId'    => 'required|numeric',
        ], $message);
    }

    /**
     * create a new article
     *
     * @param array $data
     */
    protected function create(array $data)
    {
        Article::create($data);
    }

    /**
     * add a new article
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addArticle(Request $request)
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

            $data['user_id']     = session('userId');
            $data['title']      = request('title');
            $data['tag']        = request('tag');
            $data['type']       = request('type');
            $data['picture']    = request('picture');
            $data['cnt']        = 0;
            $data['author']     = request('author');
            $data['content']     = request('content');
            $data['abstract'] = request('abstract') ?? '';
            $data['node_id'] = request('nodeId');

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
