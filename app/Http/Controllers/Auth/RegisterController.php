<?php

namespace App\Http\Controllers\Auth;

use App\Http\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username'      => 'required|string',
            'token'         => 'required|string'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Http\Models\User
     */
    protected function create(array $data)
    {
        return User::create($data);
    }

    /**
     * 注册用户
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
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
            $token = request('token');
            if ($token != 'creatshare.3842362') {
                return response()->json([
                    'code'  => 3002,
                    'msg'   => 'token错误'
                ]);
            }
            $username = request('username');
            $ret = User::where('username', $username)->first();
            if ($ret) {
                return response()->json([
                    'code'  => 3003,
                    'msg'   => '用户已存在'
                ]);
            }
            $this->create([
                'username'  => $username,
                'password'  => password_hash($username, PASSWORD_BCRYPT)
            ]);
            return response()->json([
                'code'  => 2000,
                'data'  => [
                    'username'  => $username,
                    'status'    => '添加成功'
                ]
            ]);
        }
    }
}
