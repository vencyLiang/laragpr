<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Validator;
class UsersController extends Controller
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
            ->header('会员列表')
            ->description('查看用户列表')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('会员详情')
            ->description('查看用户资料')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('会员编辑')
            ->description('修改用户资料')
            ->body($this->updateForm()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('会员新建')
            ->description('创建新的用户')
            ->body($this->createForm());
    }
    public function  store()
    {
        return $this->createForm()->store();
    }

    public function  update($id)
    {
        $form = $this->updateForm()->edit($id);
        return $form->update($id);
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User);
        $grid->id('Id');
        $grid->name('用户名');
        $grid->email('邮箱');
        $grid->phone_num("手机号");
        $grid->platform_wallet_address('平台钱包地址');
        $grid->user_wallet_address('提现地址');
        $grid->up_invite_code('上级邀请码');
        $grid->invite_code('邀请码');
        $grid->pid('上级id')->sortable();
        $states = [
            'on'  => ['value' => '1', 'text' => '已激活', 'color' => 'success'],
            'off' => ['value' => '0', 'text' => '未激活', 'color' => 'danger'],
        ];
        $grid->activation_status('激活状态')->switch($states)->sortable();
        $grid->register_time('注册时间')->sortable();
        $grid->activate_time('激活时间')->sortable();
        $grid->account_bonus('余额')->color('red')->sortable();
        $status = [
            'on'  => ['value' => '1', 'text' => '正常', 'color' => 'success'],
            'off' => ['value' => '0', 'text' => '禁用', 'color' => 'danger'],
        ];
        $grid->status('会员状态')->switch($status)->sortable();
        $grid->rows(function (Grid\Row $row) {
            if ($row->id % 2) {
                  // $row->setAttributes(['style' => 'background-color:skyblue;']);
            }
        });
        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });
        $grid->actions(function ($actions) {
            $actions->disableDelete();
        });
        $grid->filter(function (Grid\Filter $filter) {

            //$filter->expand();
            $filter->column(1/2, function ($filter) {
                $filter->like('name','用户名');
                $filter->like('email','邮箱');
                $filter->like('phone_num','电话号码');
                $filter->like('platform_wallet_address','平台钱包地址');
            });

            $filter->column(1/2, function ($filter) {
                $filter->equal('pid','上级id');
                $filter->like('up_invite_code','上级邀请码');
                $filter->between('register_time','注册时间')->datetime();
                $filter->between('activate_time','激活时间')->datetime();
                $filter->group('account_bonus', '账户金额',function ($group) {
                    $group->gt('大于');
                    $group->lt('小于');
                    $group->nlt('不小于');
                    $group->ngt('不大于');
                    $group->equal('等于');
                });
            });
        });
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(User::findOrFail($id));

        $show->id('Id');
        $show->name('Name');
        $show->email('Email');
        $show->password('Password');
        $show->phone_num('Phone num');
        $show->platform_wallet_address('Platform wallet address');
        $show->user_wallet_address('User wallet address');
        $show->withdraw_password('Withdraw password');
        $show->up_invite_code('Up invite code');
        $show->invite_code('Invite code');
        $show->pid('Pid');
        $show->path('Path');
        $show->activation_status('Activation status');
        $show->register_time('Register time');
        $show->activate_time('Activate time');
        $show->account_bonus('Account bonus');
        $show->remember_token('Remember token');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function createForm()
    {
        $form = new Form(new User);
        $form->text('name', '用户名')->rules('required');
        $form->email('email', '邮箱')->rules('required');
        $form->password('password', '密码');
        $form->text('phone_num', '电话号码');
        $form->text('up_invite_code', '上级邀请码');
        $form->footer(function (Form\Footer $footer){
            $footer->disableEditingCheck();
        });
        return $form;
    }

    protected function updateForm()
    {
        $form = new Form(new User);
        $form->text('name', '用户名')->rules('required');
        $form->email('email', '邮箱')->rules('required');
        $form->password('password', '密码')->rules('required');
        $form->mobile('phone_num', '电话号码');
        $form->text('platform_wallet_address', '平台钱包地址');
        $form->text('user_wallet_address', '提现钱包地址');
        $form->text('withdraw_password', '提现密码');
        $form->text('up_invite_code', '上级邀请码')->rules('required');
        $form->text('invite_code', '邀请码');
        $form->number('pid', '直接上级id')->help('人为控制可能会打乱层级关系而产生不可预知的后果，请确认无误后谨慎操作！');
        $form->text('path', '用户层级路径');
        $states = [
            'on'  => ['value' => '1', 'text' => '已激活', 'color' => 'success'],
            'off' => ['value' => '0', 'text' => '未激活', 'color' => 'danger'],
        ];
        Validator::extend('activate_status',function ($attribute, $value, $parameters, $validator) use ($form){
            return ($value === 'on'|| $form->model()->activation_status == 0);
        },'不可由激活状态更改为未激活状态');
        $form->switch('activation_status','激活状态')->states($states)->rules('activate_status')->help('非激活状态改为激活状态，单独设置无效，需同时设置pid值;由于涉及到层级与分红关系，不可由激活状态改为非激活状态');
        $form->number('account_bonus', '账户余额');
        $status = [
            'on'  => ['value' => '1', 'text' => '正常', 'color' => 'success'],
            'off' => ['value' => '0', 'text' => '禁用', 'color' => 'danger'],
        ];
        $form->switch('status','会员状态')->states($status)->help('禁用状态下不能登录，不影响层级关系和分红！');
        $form->footer(function (Form\Footer $footer){
            $footer->disableCreatingCheck();
        });
        return $form;
    }


}
