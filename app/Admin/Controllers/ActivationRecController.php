<?php

namespace App\Admin\Controllers;

use App\Models\ActivationRec;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Widgets\Table;;

class ActivationRecController extends Controller
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
            ->header('激活相关')
            ->description('激活及其分红的记录')
            ->body($this->grid());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ActivationRec());

        $grid->id('Id');
        $grid->user_id('触发用户id')->modal('详情',function ($model){
            $modelArr = $model->user->only(['id','name','email','phone_num']);
            return new Table(['ID', '用户名','邮箱', '电话号码'],[$modelArr]);
        });
        $grid->type('事件类型')->using([1 => '激活',2 => '分红'])->sortable();
        $grid->status('状态')->status_color([1 =>'成功',0 => '失败'])->sortable();
        $grid->message('相关信息');
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
                }, '触发用户信息', 'user')->placeholder('触发用户的ID/邮箱/电话号码');
                $filter->equal('type', '事件类型')->select([
                    1 => '激活',
                    2 => '分红',
                ]);
                $filter->equal('status','状态')->radio(['' => '全部', 0 => '失败', 1 => '成功']);
            });

        return $grid;
    }

}
