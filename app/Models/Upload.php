<?php

namespace App\Models;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class Upload extends BaseModel
{
    public function add(Request $request)
    {
        if (!$request->hasFile('file')) {
            return false;
        }
        $file = $request->file("file");
        if (!$file->isValid()) {
            return false;
        }
        $entension = $file->getClientOriginalExtension(); //上传文件的后缀.
        $fileName = date('ymdhis') . mt_rand(1000, 9999);
        $newName = $fileName . "-src." . $entension;
        $file->move(public_path('image'), $newName);
        $dst = $fileName . '-dst.' . $entension;
        Image::make(public_path('image/' . $newName))->resize(480, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save(public_path('image/' . $dst));
        return '/image/' . $dst;
    }


}
