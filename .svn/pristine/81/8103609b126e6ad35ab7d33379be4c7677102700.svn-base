<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Storage;

class RequestController extends Controller
{
    public function fileUpload(Request $request,string $type='avatar')
    {   if(!$request->has('user')){
            abort(400, '非法操作');
        }
        $userId = $request->input('user');
        $user = User::find($userId);
        if(!$userId || !$user){
            abort(400, '用户不存在');
        }
        if ($request->hasFile('picture')) {
            $picture = $request->file('picture');
            if (!$picture->isValid()) {
                abort(400, '无效的上传文件');
            }
            // 文件扩展名
            $extension = $picture->getClientOriginalExtension();
            // 文件名
            $fileName = $picture->getClientOriginalName();
            // 生成新的统一格式的文件名
            $newFileName = md5($fileName . time() . mt_rand(1, 10000)) . '.' . $extension;
            // 图片保存路径
            $type = $request->input('type') ?? $type;
            $savePath = "{$type}/" . $newFileName;
            // Web 访问路径
            $webPath = 'storage/' . $savePath;
            $user->avatar = $webPath;
            $user->save();
            // 将文件保存到本地 storage/app/public/images 目录下，先判断同名文件是否已经存在，如果存在直接返回
            if (Storage::disk('public')->has($savePath)) {
                return response()->json(['path' => asset($webPath)]);
            }
            // 否则执行保存操作，保存成功将访问路径返回给调用方
            if ($picture->storePubliclyAs($type, $newFileName, ['disk' => 'public'])) {
                return response()->json(['path' => asset($webPath)]);
            }
            abort(500, '文件上传失败');
        } else {
            abort(400, '请选择要上传的文件');
        }
    }
}
