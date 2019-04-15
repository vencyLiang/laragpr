<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/24
 * Time: 13:33
 */
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class InviteCodesController extends Controller{

    protected function ajax_check_invite_code(Request $request){
        $upInviteCode = $request->up_invite_code;
        if($upInviteCode == ""){
            return 2;
        }
        $res = User::Where('invite_code',$upInviteCode)->first();
        if($res || ($upInviteCode === PLATFORM_INVITE_CODE) ){
            return 1;
        }else{
            return 0;
        }
    }

    function generate_invite_code($user_id) {
        static $source_string = 'E5FCDG3HQA4B1NOPIJ2RSTUV67MWX89KLYZ';
        $num = $user_id;
        $code = '';
        while ( $num > 0) {
            $mod = $num % 35;
            $num = ($num - $mod) / 35;
            $code = $source_string[$mod].$code;
        }
        if(empty($code[3]))
            $code = str_pad($code,4,'0',STR_PAD_LEFT);
        return $code;
    }
}