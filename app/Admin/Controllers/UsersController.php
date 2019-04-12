<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

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
            ->header('列表')
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
            ->header('详情')
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
            ->header('编辑')
            ->description('修改用户资料')
            ->body($this->form()->edit($id));
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
            ->header('新建')
            ->description('创建新的用户')
            ->body($this->form());
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
        //$grid->password('Password');
        $grid->phone_num("手机号");
        $grid->platform_wallet_address('平台钱包地址');
        $grid->user_wallet_address('提现地址');
        //$grid->withdraw_password('Withdraw password');
        $grid->up_invite_code('上级邀请码');
        $grid->invite_code('邀请码');
        $grid->pid('上级id')->sortable();
        //$grid->path('Path');
        $grid->activation_status('激活状态')->sortable();
        $grid->register_time('注册时间')->sortable();
        $grid->activate_time('激活时间')->sortable();
        $grid->account_bonus('余额')->sortable();
       // $grid->remember_token('Remember token');
       // $grid->created_at('Created at');
       // $grid->updated_at('Updated at');

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
    protected function form()
    {
        $form = new Form(new User);

        $form->text('name', 'Name');
        $form->email('email', 'Email');
        $form->password('password', 'Password');
        $form->text('phone_num', 'Phone num');
        $form->text('platform_wallet_address', 'Platform wallet address');
        $form->text('user_wallet_address', 'User wallet address');
        $form->text('withdraw_password', 'Withdraw password');
        $form->text('up_invite_code', 'Up invite code');
        $form->text('invite_code', 'Invite code');
        $form->number('pid', 'Pid');
        $form->text('path', 'Path');
        $form->text('activation_status', 'Activation status');
        $form->number('register_time', 'Register time');
        $form->number('activate_time', 'Activate time');
        $form->number('account_bonus', 'Account bonus');
        $form->text('remember_token', 'Remember token');

        return $form;
    }
}
