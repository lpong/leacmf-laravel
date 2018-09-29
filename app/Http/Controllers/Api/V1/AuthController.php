<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Library\Y;
use App\Model\User;
use App\Service\Sms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['mobile', 'password']);
        if (!$token = auth()->attempt($credentials)) {
            return Y::json(1002, '用户名或密码错误');
        }
        return Y::json($this->respondWithToken($token));
    }

    public function register(Request $request)
    {
        $post      = $request->only(['mobile', 'code', 'password', 'nickname']);
        $validator = Validator::make($post, [
            'mobile'   => 'required|unique:users|regex:/^1[34578][0-9]{9}$/',
            'code'     => 'required|size:6',
            'password' => 'required|alpha_num|size:32',
            'nickname' => 'required|max:32',
        ]);
        if ($validator->fails()) {
            return Y::json($validator->errors());
        }

        $ret = Sms::check($post['mobile'], 'signUp', $post['code']);
        if ($ret !== true) {
            return Y::json(2010, $ret);
        }

        $post['username'] = uniqid();
        $post['password'] = bcrypt($post['password']);
        $post['status']   = 1;

        try {
            $user  = User::create($post);
            $token = auth()->login($user);
        } catch (\Exception $e) {
            return Y::json(2001, '注册失败');
        }
        return Y::json($this->respondWithToken($token));
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function user()
    {
        return Y::json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return Y::json('Successfully logged out');
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return Y::json($this->respondWithToken(auth()->refresh()));
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return array
     */
    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60,
        ];
    }
}