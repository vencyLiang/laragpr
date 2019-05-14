<?php

namespace App\Admin\Controllers;

use App\Models\Article;
use App\Models\Admin;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class ArticleController extends Controller
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
            ->header('文章管理')
            ->description('文章列表')
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
            ->header('文章详情')
            ->description('文章的内容详情')
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
            ->header('文章编辑')
            ->description('编辑文章内容')
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
            ->header('新增文章')
            ->description('文章内容新增')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Article);
        $grid->id('Id');
        $grid->admin_id('发布的管理员')->display(function ($admin_id) {
            return Admin::find($admin_id)->username;
        });
        $grid->title('文章标题')->sortable();
        $status = [
            'on'  => ['value' => '1', 'text' => '正常', 'color' => 'success'],
            'off' => ['value' => '0', 'text' => '禁用', 'color' => 'danger'],
        ];
        $grid->status('文章状态')->switch($status)->sortable();
        $grid->created_at('创建时间');
        $grid->disableActions();
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

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Article::findOrFail($id));



        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Article);



        return $form;
    }
}
