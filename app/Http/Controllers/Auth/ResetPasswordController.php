<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Models\User;

class ResetPasswordController extends Controller
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
            'required' => '数据不能为空',
            'string' => '数据不合法',
            'min' => '数据不合法',
            'size' => '数据不合法',
            'regex:^((1[3,5,8][0-9])|(14[5,7])|(17[0,6,7,8])|(19[7]))\\d{8}$' => '数据不合法'
        ];
        return Validator::make($data, [
            'originalPassword' => 'required|string|min:6|max:32',
            'newPassword' => 'required|string|min:6|max:32',
            'newPasswordConfirm' => 'required|string|min:6|max:32',
            'code' => 'required|string|size:6',
            'mobile' => ['required', 'regex:/^((1[3,5,8][0-9])|(14[5,7])|(17[0,6,7,8])|(19[7]))\\d{8}$/']
        ], $message);
    }

    /**
     * 重置密码
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
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

            $originalPassword = request('originalPassword');
//            $userId = $request->getSession();
            $cookie = $request->cookie();
            var_dump($cookie);
            $session = session($cookie);
            var_dump($session);
//            if (!$userId) {
//                return response()->json([
//                    'code' => 3001,
//                    'msg' => '登录过期'
//                ]);
//            }
//            $newPassword = request('newPassword');
//            $newPasswordConfirm = request('newPasswordConfirm');
//            $code = request('code');
//            $mobile = request('mobile');
//
//            $user = User::where('id', $userId)->first();
//            if (!($password = password_verify($originalPassword, $user->password))) {   // 判断原密码是否正确
//                return response()->json([
//                    'code' => 3002,
//                    'msg' => '密码不正确'
//                ]);
//            }
//            $originalCode = session(base64_encode($mobile));
//            if ($originalCode != $code) {
//                return response()->json([
//                    'code' => 3004,
//                    'msg' => '验证码错误'
//                ]);
//            }
//            session()->put(base64_encode($mobile), null);
//            if ($newPassword != $newPasswordConfirm) {
//                return response()->json([
//                    'code' => 3003,
//                    'msg' => '密码不一致'
//                ]);
//            }
//            $user->password = password_hash($newPassword, PASSWORD_BCRYPT);
//            $user->save();
        }
    }
}

