<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /**
     * 验证数据
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data)
    {
        $message = [
            'required'  => '数据不能为空',
            'string'    => '数据不合法',
        ];
        return Validator::make($data, [
            'username'  => 'required|string',
            'password'  => 'required|string',
        ], $message);
    }

    /**
     * 管理员登录
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
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
                    'code' => $code,
                    'msg' => $errors
                ]);
            }
            $username = request('username');
            $password = request('password');
            $user = User::where('username', $username)->first();
            if (!$user) {
                return response()->json([
                    'code'  => 3001,
                    'msg'   => '用户名或密码不正确'
                ]);
            }
            if ($password = password_verify($password, $user->password)) {
                DB::table('users')
                    ->where('username', $username)
                    ->update(array(
                        'updated_at'    => Carbon::now()
                    ));
                session()->put('userId', $user->id);
                session()->save();
                return response()->json([
                    'code'  => 2000,
                    'data'  => [
                        'username'  => $username,
                        'userId'    => $user->id
                    ]
                ]);
            } else {
                return response()->json([
                    'code'  => 3001,
                    'msg'   => '用户名或密码不正确'
                ]);
            }
        }
    }
}
