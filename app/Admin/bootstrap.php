<?php

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */
use Encore\Admin\Grid\Column;
Encore\Admin\Form::forget(['map', 'editor']);
Column::extend('color', function ($value, $color) {
    return "<span style='color: $color'>$value</span>";
});
Column::extend('status_color', function ($value,$message) {
    if($value){
        return "<span style='color: green'>{$message[$value]}</span>";
    }else{
        return "<span style='color: red'>{$message[$value]}</span>";
    }
});
Column::extend('error', function ($value) {
    if($value === NULL){
        return "<span style='color: green'>成功</span>";
    }else{
        return "<span style='color: red'>失败</span>";
    }
});
Column::extend('prependIcon', function ($value, $icon) {

    return "<span style='color: #999;'><i class='fa fa-$icon'></i>$value</span>";

});