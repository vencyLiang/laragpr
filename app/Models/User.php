<?php

namespace App\Models;

use app\coins\model\UserWalletAccount;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use Notifiable;

    protected $guarded = ['invite_code', 'activation_status'];

    protected $hidden = ['password','remember_token'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function account(){
        return $this->hasOne(UserWalletAccount::class,'uid');
    }

    public function accountFormRec(){
        return $this->hasMany(AccountRec::class,'user_id');
    }

    public function accountToRec(){
        return $this->hasMany(AccountRec::class,'to_uid');
    }

    public function activationRec(){
        return $this->hasOne(ActivationRec::class);
    }
    /**获得所有的线内后代；
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function children(){
        return $this->belongsToMany(User::class,'user_relation','up_uid','uid');
    }

    /**获得所有的线内前辈；
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function seniors(){
        return $this->belongsToMany(User::class,'user_relation','uid','up_uid');
    }

    public function becomeChild($user_ids){
        if (!is_array($user_ids)) {
            $user_ids= compact('user_ids');
        }
        $this->seniors()->sync($user_ids, false);
    }

    public function isChild($uid){
        return $this->seniors->contains($uid);
    }

    /**功能：获取当前用户所有上级用户的id数组；
     * @param  $level: 从父代开始取$level代；
     * @return array
     */
    public function  get_all_parentsIdArr($level = NULL){
        //获取层级路径字符串
        $userPath = $this->attributes['path'];
        //将层级关系转化为数组；
        $parentArr = explode('-',$userPath);
        //最后一个元素表示本身，移除；
        array_pop($parentArr);
        //第一个元素值为0，代表平台，移除
        array_shift($parentArr);
        foreach ($parentArr as $k=>$v){
            $parentArr[$k] = (int) $v;
        }
        if(!empty($level)){
            $parentArr = array_slice($parentArr,-1,$level,true);
        }
        return $parentArr;
    }


    /**功能：获取当前用户所有上级用户collection集合；
     * @param  $level: 从父代开始取$level代；
     * @return mixed
     */
    public function get_all_parentsModel($level = NULL){
        $parentArr = $this->get_all_parentsIdArr($level);
        $allParentsModel =  self::find($parentArr);
        return $allParentsModel;
    }


    /**功能：判断该当前用户是否为滑落用户；
     * @return bool
     */
    public function is_down(){
        $parentArr = $this->get_all_parentsIdArr();
        //获取上级用户id；
        $parentUserId = array_pop($parentArr);
        //获取邀请其注册的用户id；
        $sendInviteUserId = self::where('invite_code',$this->attributes['up_invite_code'])->id;
        //如果不是同一个用户
        if($parentUserId != $sendInviteUserId){
            return true;
        }else{
            return false;
        }
    }

    /**查找模型的所有下级用户的id,并按层级顺序整理成形如['level1'=>[id1,id2…]]的形式。
     * @return array
     */
    public function get_all_children(){
        //查找path中含有其id的，并排除path是其自身id的及path最后一个字符是其自身id的。
        $allChildrenCollection = self::where('path','<>',"{$this->attributes['id']}")
            ->where(
            function($query){
            $query
                ->where('path','not like','%'.$this->attributes['id'])
                ->where(function ($query){
                    $query->where('path','like','%'.$this->attributes['id'] .'%');
                });
            }
        )->get();
        if($allChildrenCollection) {
            $allChildrenPath = $allChildrenCollection->pluck('path')->all();
            $childrenOrderByLevel = [];
            foreach ($allChildrenPath as  $childrenPath) {
                $childrenPath = substr(strstr($childrenPath, "{$this->attributes['id']}"), 2);
                if (strlen($childrenPath) >= 3) {
                    $childrenPathArr = explode('-', $childrenPath);
                    foreach($childrenPathArr as $k=>$v){
                        $childrenPathArr[$k] = (int)$v;
                    }
                } else {
                    $childrenPathArr = [intval($childrenPath)];
                }
                //$allChildrenPath[$key] = $childrenPathArr;
                for($m = 0;$m <= count($childrenPathArr)-1;$m++){
                        if( ! in_array($childrenPathArr[$m], $childrenOrderByLevel['level' . ($m + 1)]) ){
                        $childrenOrderByLevel['level' . ($m + 1)][] = $childrenPathArr[$m];
                    }
                }
            }
            return $childrenOrderByLevel;
        }else{
            return [];
        }
    }
}
