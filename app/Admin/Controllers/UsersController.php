<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Input;

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
        return $this->updateForm()->update($id);
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
        $grid->activation_status('激活状态')->sortable();
        $grid->register_time('注册时间')->sortable();
        $grid->activate_time('激活时间')->sortable();
        $grid->account_bonus('余额')->sortable();
        $grid->rows(function (Grid\Row $row) {
            if ($row->id % 2) {
                   $row->setAttributes(['style' => 'color:red;']);
            }
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
        $form->text('name', '用户名');
        $form->email('email', '邮箱');
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
