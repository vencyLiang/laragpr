<?php

namespace App\Admin\Controllers;

use App\Models\UserRelation;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class GroupsController extends Controller
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
            ->header('团队')
            ->description('团队详情信息')
            ->body($this->grid());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new UserRelation);
        $grid->uid('上级id');
        $grid->name('上级用户名');
        $grid->email('上级邮箱地址');
        $grid->phone_num('上级电话号码');
        $grid->child_id('下级id');
        $grid->child_level('下级所在的层级');
        $grid->child_name('下级用户名');
        $grid->child_email('下级邮箱地址');
        $grid->child_phone_num('下级电话号码');
        $grid->child_platform_wallet_address('下级钱包地址');
        $grid->child_up_invite_code('下级获邀码');
        $grid->child_pid('下级的直接上级');
        $grid->child_path('下级的层级路径');
        $grid->child_status('下级的用户状态');
        $grid->child_register_time('下级的注册时间');
        $grid->child_activate_time('下级的激活时间');
        $grid->disableActions();
        $grid->disableCreation();
        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });
        $grid->filter(function (Grid\Filter $filter) {
            $filter->expand();
            $filter->disableIdFilter();
            $filter->equal('uid','上级id');
            $filter->like('name','上级用户名');
            $filter->like('email','上级邮箱');
            $filter->like('phone_num','上级电话');

        });
        return $grid;
    }



}
