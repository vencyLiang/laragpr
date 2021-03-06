<?php
namespace App\Extras\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection as Collection;
use Illuminate\Support\Facades\DB;
use App\Models\ActivationRec;
use Exception;
class Activate{

    /**功能：对满足条件的注册用户进行激活，生成邀请码，更新上下级对应关系，并生成层级路径。
     * @param User $user
     * @return bool
     */
    public static function activate(User $user){
          //设定邀请码；
          $activationInfo['invite_code'] = $user->unique ;
          //设定激活状态；
          $activationInfo['activation_status'] = '1';
          //获取激活时间；
          $activationInfo['activate_time']  = date('Y-m-d H:i:s',time());
          //如果上级邀请码为平台邀请码，则新开分区
          $up_invite_code = $user->up_invite_code;
          if($up_invite_code === play_config()->platform_invite_code){
              $activationInfo['pid'] = 0;
              $activationInfo['path'] = '0-'.$user->id;
              $activationInfo['position'] = 1;
          }else{
              //获取有相同上级激活码的用户
              $siblingsCollection = User::Where('up_invite_code',$up_invite_code)->where('activation_status','1')->get();
              $siblingsNum = $siblingsCollection->count();
              //如果用户少于三个，则插入到同一个上级用户下。
              if($siblingsNum < 3){
                  $parentInfo = User::where('invite_code',$up_invite_code)->first();
                  $activationInfo['pid'] = $parentInfo->id;
                  $activationInfo['path'] = "$parentInfo->path-$user->id";
                  $activationInfo['position'] = $siblingsNum + 1;
              }else{
                  $parentUser = self::down_position($siblingsCollection);
                  $activationInfo['pid'] = $parentUser->id;
                  $activationInfo['path'] = "$parentUser->path-$user->id";
                  $activationInfo['position'] = $parentUser->sons->count() + 1;
              }
          }
          DB::beginTransaction();
          try{
              $user->update($activationInfo);
              $upUids = $user->get_all_parentsIdArr($activationInfo['path']);
              $relationArr = [];
              $totalLevel = count($upUids);
              if(!$totalLevel){
              foreach ($upUids as $k => $uid) {
                    $relationArr[] = ['uid' => $user->id, 'up_uid' => $uid, 'level' => $totalLevel - $k ];
              }
              DB::table("user_relation")->insert($relationArr);
              }
              ActivationRec::create([
                      //触发的用户；
                      'user_id' => $user->id,
                      'type' => 1 ,
                      //状态：成功；
                      'status' => 1,
              ]);
              DB::commit();
          }catch (Exception $exception){
              DB::rollBack();
              ActivationRec::create([
                  'user_id' => $user->id,
                  //激活
                  'type' => 1 ,
                  'status' => 0,
                  'message'=> $exception->getMessage()
              ]);
              return false;
          }
          return $activationInfo['path'];
    }

    /**功能：实现用户以某邀请码激活后的滑落动作；
     * @param $collection //上级用户的下级子用户结果集。
     * @return mixed
     */
    public static function down_position(Collection $collection){
        $subItems = [];
        $subItemsCounts = [];
        foreach($collection as $item) {
            $subItemsCollection = User::where('pid', $item->id)->get();
            $subItems[$item->invite_code] = $subItemsCollection;
            $subItemsCounts[$item->invite_code] = $subItemsCollection->count();
        }
        if(max($subItemsCounts)!= ($min = min($subItemsCounts))){
            $minItemsArr = [];
            do{
               $minIndex = array_search($subItemsCounts,$min);
               if($minIndex){
                   $minItemsArr[] = array_search($subItemsCounts,$min);
                   unset($subItemsCounts[$minIndex]);
                  }
            }while($minIndex);
            $parentUser = User::where('activation_status','1')->where(function($query)use($minItemsArr){
                $query->whereIn('invite_code',$minItemsArr);})->orderBy('activate_time','asc')->offset(0)->limit(1)->first();
            return $parentUser;
        }else{
            if($min<3){
                $possiblePositionArr = array_keys($subItems);
                $parentUser = User::where('activation_status','1')->where(function($query)use($possiblePositionArr){
                    $query->whereIn('invite_code',$possiblePositionArr);})->orderBy('activate_time','asc')->offset(0)->limit(1)->first();
                return $parentUser;
              }else{
                $allSubItemsCollection = User::where('activate_status','1')->whereIn('up_invite_code',$collection->pluck('invite_code'))->get();
                return self::down_position($allSubItemsCollection);
            }
        }
    }

}