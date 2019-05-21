<?php


namespace App\Http\Controllers;
use App\Models\User;

class ApiController extends controller
{
    public function verifyInviteCode(string $inviteCode=""){
      $inviteCode =  $inviteCode ?? request()->input('invite_code');
      if(!$inviteCode){
          return ['status'=>500,'message'=>'Invite code must be provided!'];
      }
      $exist = User::where('invite_code',$inviteCode)->count();
      if($exist){
          return ['status'=>200, 'result'=>'yes'];
      }else{
          return ['status'=>200,'result'=> 'no'];
      }
    }
}