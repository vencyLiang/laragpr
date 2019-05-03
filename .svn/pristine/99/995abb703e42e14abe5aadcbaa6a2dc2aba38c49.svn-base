<?php
/**
 * establishedDate: 2018-10
 * updateDate: 2018-11-16
 * authoredBy: vency
 */

namespace App\Extras\Task;
use App\Models\User;
use App\Models\ActivationRec;
use Illuminate\Support\Facades\DB;
use \Exception;

class Bonus
{
    use SystemConfig;

    /**功能：用户激活时，各层级的奖金分配；
     * @param User $user
     * @param $userPath
     * @return bool
     */
    public static function generate_bonus(User $user,$userPath  = ""){
        self::getConfig();
        //获取该用户在其路径上的全部上级用户集合
        $parentsModel = $user->get_all_parentsModel($userPath,GENERATIONS);
        //return $parentsModel;
        DB::beginTransaction();
        try {
            //初始化总流动金额；
            $totalBonus = 0;
            //直推奖金额；
            $directBonus = DIRECT_BONUS_RATIO * ACTIVATE_COST;
            //层级奖金额：
            $levelBonus = LEVEL_BONUS_RATIO * ACTIVATE_COST;
            //如果是滑落用户
            if ($user->is_down()) {
                $coinType = play_config()->currency;
                foreach ($parentsModel as $parentItem) {
                    if ($parentItem->invite_code == $user->up_invite_code) {
                        $totalBonus += $directBonus;
                        $num = $directBonus;
                    } else {
                        $totalBonus += $levelBonus;
                        $num = $levelBonus;
                    }
                    $currency = [0 => 'usdt', 1 => 'eth'];
                    $currency = $currency[play_config()->currency];
                    $fieldName = $currency."_balance";
                    $parentItem->account->$fieldName += $num;
                    $parentItem->account->save();
                    $parentItem->accountToRec()->create([
                        'user_id' => $user->id ,
                        'from_address' => $user->platform_wallet_address ,
                        'to_address' => $parentItem->platform_wallet_address ,
                        'num' => $num ,
                        'is_confirmed' => 1,
                        'coin_type' => $coinType ,
                        'transfer_type' => 5,
                        'is_self' => 1
                    ]);
                }
                //如果是直推用户；
            } else {
                $coinType = play_config()->currency;
                foreach ($parentsModel as $parentItem) {
                    if ($parentItem->id == $user->pid) {
                        $totalBonus += $directBonus;
                        $parentItem->account_bonus += DIRECT_BONUS_RATIO * ACTIVATE_COST;
                        $num = $directBonus;
                    } else {
                        $totalBonus += $levelBonus;
                        $parentItem->account_bonus += LEVEL_BONUS_RATIO * ACTIVATE_COST;
                        $num = $directBonus;
                    }
                    $parentItem->save();
                    $parentItem->accountToRec()->create([
                        'user_id' => $user->id ,
                        'from_address' => $user->platform_wallet_address ,
                        'to_address' => $parentItem->platform_wallet_address ,
                        'num' => $num ,
                        'is_confirmed' => 1,
                        'coin_type' => $coinType ,
                        'transfer_type' => 5,
                        'is_self' => 1
                    ]);
                }
            }
            DB::commit();
            return true;
        }catch (Exception $e){
            DB::rollBack();
            ActivationRec::create([
                'user_id' => $user->id,
                //返佣
                'type' => 2 ,
                'status' => 0,
                'message'=> $e->getMessage()
            ]);
            return false;
        }
    }

}
