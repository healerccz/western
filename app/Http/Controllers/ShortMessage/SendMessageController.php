<?php

namespace App\Http\Controllers\ShortMessage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SendMessageController extends Controller
{
    /**
     * Verification Code
     * @var integer
     */
    private  $code;

    /**
     * @var string
     */
    private $apikey;

    // 可用的手机号
    private $mobiles = [
        '15955702565',
    ];

    private $mobile;

    /**
     *
     * @var string
     */
    private $text;

    public function __construct()
    {
        $this->apikey = config('yunpian.apiKey');
    }

    private function validator($data)
    {
        $message = [
            'required'  => '数据不能为空',
            'regex:^((1[3,5,8][0-9])|(14[5,7])|(17[0,6,7,8])|(19[7]))\\d{8}$' => '数据不合法'
        ];
        return Validator::make($data, [
            'mobile'    => ['required', 'regex:/^((1[3,5,8][0-9])|(14[5,7])|(17[0,6,7,8])|(19[7]))\\d{8}$/']
        ], $message);
    }

    /**
     * create code
     * @return int
     */
    private function createCode()
    {
        return $this->code = rand(100000, 999999);
    }

    /**
     * create short message text
     * @return string
     */
    private function createText()
    {
        return $this->text = "【创享实验室】您的验证码是".$this->code."。如非本人操作，请忽略本短信";
    }

    /**
     * http request
     *
     * @return mixed
     */
    private function httpRequest()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array('Accept:text/plain;charset=utf-8',
                'Content-Type:application/x-www-form-urlencoded', 'charset=utf-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Sending single SMS
        $data = array('text' => $this->text, 'apikey' => $this->apikey, 'mobile' => $this->mobile);
        curl_setopt($ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/sms/single_send.json');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $result = curl_exec($ch);
        return $result;
    }

    /**
     * send short message
     * @param $mobile
     * @return mixed
     */
    public function sendMessage(Request $request)
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
            $this->mobile = request('mobile');
            if (!in_array($this->mobile, $this->mobiles)) {
                return response()->json([
                    'code'  => 3003,
                    'msg'   => '该手机号没有权限'
                ]);
            }
            $this->createCode();
            $this->createText();
            $result = $this->httpRequest();
            $pattern = '/"code":((-)?\d+)/';
            preg_match($pattern, $result, $match);
//            var_dump($result);
//            var_dump($match);
            if (is_array($match) && count($match) > 1) {
                $code = (int)$match[1];
                if ($code == 0) {
                    session()->put(base64_encode($this->mobile), $this->code);
                    session()->save();
                    return response()->json([
                        'code'  => 2000,
                        'data'  => ''
                    ]);
                } else if ($code == 33 || $code == 22 ||$code == 8 || $code == 9 | $code == 43) {
                    return response()->json([
                        'code'  => 3001,
                        'msg'  => '操作过频繁'
                    ]);
                }
            } else {
                return response()->json([
                    'code'  => 5000,
                    'msg'  => '未知错误'
                ]);
            }
            return ;
        }
    }
}
