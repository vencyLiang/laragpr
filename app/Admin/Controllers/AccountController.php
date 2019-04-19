<?php

namespace App\Admin\Controllers;

use App\Models\AccountRec;
use App\Http\Controllers\Controller;
use App\Models\User;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class AccountController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('账务记录')
            ->description('账户流水详情')
            ->body($this->grid());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new AccountRec);

        $grid->id('Id(点击看详情)')->modal('详情',function ($model){
            return self::detail($model)->render();
        });
        $grid->user_id('触发用户id');
        $grid->to_uid('目的用户id');
        $grid->from_address('来源地址');
        $grid->to_address('目的地址');
        $grid->num('数量');
        $grid->transfer_type('资金流动类型')->using([1 => '用户提现',2 => 'C2C转出',3 => '用户充值', 4 => '站内交易',5 =>'激活分红'])->sortable();
        $grid->fail_type('交易状态')->error()->sortable();
        $grid->is_confirmed('交易是否确认')->status_color('确认')->sortable();
        $grid->disableActions();
        $grid->disableCreation();
        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });
        $grid->filter(function (Grid\Filter $filter) {
            $filter->expand();
            //$filter->disableIdFilter();
            $filter->column(1 / 2, function ($filter) {
                $filter->where(function ($query) {
                    $input = $this->input;
                    //whereHas的第一个参数为关联的方法名；
                    $query->whereHas('user', function ($query) use ($input) {
                        $query->where('id', $input)->orWhere(function ($query) use ($input) {
                            $query->where('email', 'like', $input)->orWhere(function ($query) use ($input) {
                                $query->where('phone_num', 'like', $input);
                            });
                        });
                    });
                     /*   ->orWhere(function ($query) use ($input) {
                                    $query->whereHas('toUser', function ($query) use ($input) {
                                            $query->where('id', $input)->orWhere(function ($query) use ($input) {
                                                $query->where('email', 'like', $input)->orWhere(function ($query) use ($input) {
                                                    $query->where('phone_num', 'like', $input);
                                                });
                                            });
                                    });
                    });*/
                }, '触发用户信息', 'trigger_user')->placeholder('触发用户的ID/邮箱/电话号码');
                $filter->where(function ($query){
                    $input = $this->input;
                    $query->whereHas('toUser', function ($query) use ($input) {
                        $query->where('id', $input)->orWhere(function ($query) use ($input) {
                            $query->where('email', 'like', $input)->orWhere(function ($query) use ($input) {
                                $query->where('phone_num', 'like', $input);
                            });
                        });
                    });
                }, '目标用户信息', 'to_user')->placeholder('目标用户的ID/邮箱/电话号码');
                $filter->group('num', '流水金额', function ($group) {
                    $group->gt('大于');
                    $group->lt('小于');
                    $group->nlt('不小于');
                    $group->ngt('不大于');
                    $group->equal('等于');
                });
            });
            $filter->column(1 / 2, function ($filter) {
                $filter->where(function ($query) {
                    $input = $this->input;
                    $query->where('from_address', 'like', $input)->orWhere(function ($query) use ($input) {
                        $query->where('to_address', 'like', $input);
                    });
                }, '涉及的钱包地址', 'wallet');
                $filter->equal('transfer_type', '资金流转类型')->select([1 => '用户提现', 3 => '用户充值', 5 => '激活分红']);
                $filter->equal('is_confirmed', '是否已确认')->select([
                    0 => '已确认',
                    1 => '待确认',
                ]);
                $filter->where(function ($query) {
                    $input = $this->input;
                    if ($input) {
                        $query->whereNull('fail_type');
                    } else {
                        $query->whereNotNull('fail_type');
                    }
                }, '状态', 'success')->radio(['' => '全部', 0 => '失败', 1 => '成功']);
            });
        });
        return $grid;
    }

    protected static function detail($model){
        $show = new Show($model);
        $show->panel()->title('记录详细信息')
            ->tools(function ($tools) {
                $tools->disableDelete();
                $tools->disableEdit();
                $tools->disableList();
            });
        $show->user_id('触发用户信息')->as(function ($uid) use ($model){
            $user = $model->user;
            return "id:$uid &nbsp;&nbsp;&nbsp;&nbsp;用户名：$user->name; &nbsp;&nbsp;&nbsp;&nbsp;邮箱：$user->email;&nbsp;&nbsp;&nbsp;&nbsp;电话：$user->phone_num";
        });
        $show->to_uid('目的用户信息')->as(function ($toUid) use ($model){
            $user = User::findOrFail($toUid);
            return "id:$toUid &nbsp;&nbsp;&nbsp;&nbsp;用户名：$user->name; &nbsp;&nbsp;&nbsp;&nbsp;邮箱：$user->email;&nbsp;&nbsp;&nbsp;&nbsp;电话：$user->phone_num";
        });
        $show->from_address('来源地址');
        $show->to_address('目的地址');
        $show->num('数量');
        $show->fee('实际手续费');
        $show->hash_time('交易打包时间')->sortable();
        $show->txid_hash('交易Hash值');
        $show->block_hash('区块HasH值');
        $show->fail_type('错误类型');
        $show->err_msg('错误信息');
        $show->transfer_type('资金流动类型')->using([1 => '用户提现',2 => 'C2C转出',3 => '用户充值', 4 => '站内交易',5 => '分红返利'])->sortable();
        $show->is_confirmed('交易是否确认')->as(function ($value) {
            if ($value) {
                return "已确认";
            } else {
                return "未确认";
            }
        });

        return $show;
    }
}
