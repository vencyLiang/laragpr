<?php
/**
 * establishedDate: 2018-10
 * updateDate: 2018-11-16
 * authoredBy: vency
 */

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Log;
use App\Http\Requests\UserRequest;
use App\Ethwallet\EthRpcMethod;
use Illuminate\Support\Facades\DB;
use \Exception;

class BonusController extends Controller
{
    /**功能：用户激活时，各层级的奖金分配；
     * @param User $user
     * @return bool | null
     */
    public function generate_bonus(User $user){
        //获取该用户在其路径上的全部上级用户集合
        $parentsModel = $user->get_all_parentsModel();
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
                foreach ($parentsModel as $parentItem) {
                    if ($parentItem->invite_code == $user->up_invite_code) {
                        $totalBonus += $directBonus;
                        $parentItem->account_bonus += $directBonus;
                    } else {
                        $totalBonus += $levelBonus;
                        $parentItem->account_bonus += $levelBonus;
                    }
                    $parentItem->save();
                }
                //如果是直推用户；
            } else {
                foreach ($parentsModel as $parentItem) {
                    if ($parentItem->id == $user->pid) {
                        $totalBonus += $directBonus;
                        $parentItem->account_bonus += DIRECT_BONUS_RATIO * ACTIVATE_COST;
                    } else {
                        $totalBonus += $levelBonus;
                        $parentItem->account_bonus += LEVEL_BONUS_RATIO * ACTIVATE_COST;
                    }
                    $parentItem->save();
                }
            }
            Log::create([
                //类型：返利；
                'type' => 1,
                //触发的用户；
                'trigger_user_id' => $user->id,
                //状态：成功；
                'status' => 1,
                'running_account' => $totalBonus,
                'message' => '返利至用户(' . implode(',',$user->get_all_parentsIdArr()) .')'
            ]);
            DB::commit();
            return NULL;
        }catch (Exception $e){
            DB::rollBack();
            Log::create([
                //类型：返利；
                'type'=>1,
                //触发的用户；
                'trigger_user_id' => $user->id,
                //状态：异常；
                'status'=>0,
                //涉及的流水金额；
                'running_account' => $totalBonus,
                'message'=> $e->getMessage()
            ]);
            return false;
        }
    }

    public function  withdraw(UserRequest $request,User $user){
        $validatedData = $request->validated();
        $userWalletAddress = $validatedData['user_wallet_address'];
        $withdrawBonus = (float)$validatedData['withdraw_bonus'];
        $eth = new EthRpcMethod();
        $transferHash = $eth->eth_sendTransaction(PUBLIC_WALLET_ADDRESS, $userWalletAddress, $withdrawBonus, PUBLIC_WALLET_PWD);
        if (strlen($transferHash) == 64 && substr($transferHash, 0, 2) === '0x'){
                try {
                    DB::beginTransaction();
                    $user->bonus -= $withdrawBonus;
                    $user->save();
                    Log::create([
                        //类型：提现；
                        'type' => 2,
                        //触发的用户；
                        'trigger_user_id' => $user->id,
                        //状态：待确认；
                        'status'=> 2,
                        'running_account' => $withdrawBonus,
                        'message'=> '转账Hash值:'. $transferHash
                    ]);
                    DB::commit();
                    return json_encode(['status'=>200,'message'=>"提现成功，HASH值为:" .$transferHash ."请耐心等待，若长时间未到账，请联系客服！"]);;
                } catch (Exception $e){
                    $message = $e->getMessage();
                    DB::rollBack();
                    Log::create([
                    //类型：提现；
                    'type'=>2,
                    //触发的用户；
                    'trigger_user_id' => $user->id,
                    //状态：失败；
                    'status'=>0,
                    'running_account' => $withdrawBonus,
                    'message'=> $message
                    ]);
                    return json_encode(['status'=>302,'message'=>$message]);
                }
        }else {
                Log::create([
                //类型：提现；
                'type' => 2,
                //触发的用户；
                'trigger_user_id' => $user->id,
                //状态：失败；
                'status'=> 0,
                'running_account' => $withdrawBonus,
                'message'=> $transferHash
                ]);
            return json_encode(['status'=>302,'message'=>$transferHash]);
        }
    }

}
