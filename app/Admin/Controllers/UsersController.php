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
            ->body($this->updateForm($id)->edit($id));
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
        $form = $this->updateForm($id)->edit($id);
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
        $grid->name('用户名')->editable();
        $grid->email('邮箱')->editable()->prependIcon('envelope');
        $grid->phone_num("手机号")->editable()->prependIcon('phone');
        $grid->platform_wallet_address('平台钱包地址');
        $grid->user_wallet_address('提现地址')->editable();
        $grid->up_invite_code('上级邀请码')->sortable();
        $grid->invite_code('邀请码');
        $grid->pid('上级id')->sortable();
        $grid->activation_status('激活状态')->status_color()->sortable();
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
        $show->panel()
            ->tools(function ($tools) {
                $tools->disableDelete();
            });
        $show->name('用户名');
        $show->email('邮箱');
        $show->phone_num('电话号码');
        $show->platform_wallet_address('平台钱包地址');
        $show->user_wallet_address('提现钱包地址');
        $show->up_invite_code('获邀码');
        $show->invite_code('推荐码');
        $show->pid('直接上级id');
        $show->path('层级路径');
        $show->activation_status('激活状态')->using([0 => "未激活" , 1 => "已激活"]);
        $show->register_time('注册时间');
        $show->activate_time('激活时间');
        $show->account_bonus('账户余额');
        $show->status('用户状态')->using([0 => "已封禁" , 1 => "活动用户"]);
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
        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
        });
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

    protected function updateForm($id)
    {
        $form = new Form(User::findOrFail($id));
        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
        });
        $form->text('name', '用户名')->rules('required');
        $form->email('email', '邮箱')->rules('required');
        $form->password('password', '密码')->rules('required');
        $form->mobile('phone_num', '电话号码');
        $form->text('platform_wallet_address', '平台钱包地址');
        $form->text('user_wallet_address', '提现钱包地址');
        $form->text('withdraw_password', '提现密码');
        $form->text('up_invite_code', '上级邀请码')->rules('required');
        $form->text('invite_code', '邀请码');
        if($form->model()->pid !== NULL){
            $form->display('pid', '上级id')->help('激活状态下不可更改！')->with(function ($value) {
                return "<span style= 'color:red'>$value</span>";
            });
        }else{
            $form->number('pid', '直接上级id')->help('人为控制可能会打乱层级关系而产生不可预知的后果，请确认无误后谨慎操作！');
        }
        if($form->model()->pid !== NULL){
            $form->display('pid', '上级id')->help('激活状态下不可更改！');
        }else{
            $form->text('path', '用户层级路径')->help('配合pid的path值填写，请谨慎操作！');
        }
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
