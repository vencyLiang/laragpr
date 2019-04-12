<?php

namespace App\Http\Requests;
use App\Http\Validator\BonusValidationRule;
use App\Http\Validator\WithDrawPasswordValidator;
use Illuminate\Foundation\Http\FormRequest;
use Auth;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {   //sometimes，表示存在才验证
        return [
            'name' => ['sometimes','required','between:3,25','regex:/^[A-Za-z0-9\-\_]+$/','unique:users,name,' . Auth::id()],
            'email' => ['sometimes','required','email'],
            'user_wallet_address' =>['sometimes','required'],
            'withdraw_bonus' => ['sometimes',new BonusValidationRule],
            //以下字段是设置提现密码的表单name属性值；
            'set_withdraw_password'=>['sometimes','required','between:6,12','confirmed'],
            //修改提现密码的的表单，新密码的name属性值为update_withdraw_password;
            //以下是提现时表单中提现密码对应的name属性值，以及修改提现密码时旧提现密码的name属性值
            'withdraw_password'=>['sometimes','required',new WithDrawPasswordValidator ]
        ];
    }

    public function  messages()
    {
        return [
            'name.unique' => '用户名已被占用，请重新填写',
            'name.regex' => '用户名只支持英文、数字、横杠和下划线。',
            'name.between' => '用户名必须介于 3 - 25 个字符之间。',
            'name.required' => '用户名不能为空。',
            'user_wallet_address.required' =>'提现钱包地址必填',
            'set_withdraw_password.required'=>'提现密码必填',
            'set_withdraw_password.confirmed'=>'两次提现密码不一致',
            'set_withdraw_password.between'=>'提现密码应在6-12位',
            'withdraw_password.required'=>'提现密码必填',
        ];
    }
}
