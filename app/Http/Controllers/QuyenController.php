<?php

namespace App\Http\Controllers;

use App\Http\Requests\CapNhapActionRequest;
use App\Http\Requests\ThemMoiQuyenRequest;
use App\Http\Requests\XoaQuyenRequest;
use App\Models\Action;
use App\Models\Quyen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class QuyenController extends Controller
{
    public function index()
    {
        return view('admin.page.quyen.index');
    }

    public function store(ThemMoiQuyenRequest $request)
    {
        $slug = Str::slug($request->ten_quyen);
        $check = Quyen::where('slug', $slug)->first();
        if(!$check) {
            Quyen::create([
                'ten_quyen'     => $request->ten_quyen,
                'slug'          => $slug,
                'is_open'       => $request->is_open,
            ]);

            return response()->json([
                'status' => true,
                'message' => "Thêm mới quyền thành công!"
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Tên quyền đã tồn tại!"
            ]);
        }

    }

    public function getData()
    {
        $data = Quyen::all();

        return response()->json([
            'data' => $data
        ]);
    }

    public function destroy(XoaQuyenRequest $request)
    {
        $quyen = Quyen::find($request->id);
        $quyen->delete();

        return response()->json([
            'status' => true,
            'message' => "Đã xóa quyền thành công!",
        ]);
    }

    public function update(XoaQuyenRequest $request)
    {
        $data = $request->all();
        $slug = Str::slug($request->ten_quyen);
        $check = Quyen::where('slug', $slug)
                        ->where('id', '<>', $request->id)
                        ->first();
        if(!$check) {
            $quyen = Quyen::find($request->id);
            $data['slug'] = $slug;
            $quyen->update($data);

            return response()->json([
                'status' => true,
                'message' => "Thêm mới quyền thành công!"
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Tên quyền đã tồn tại!"
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => "Đã xóa quyền thành công!",
        ]);
    }

    public function getAction()
    {
        $data = Action::all();

        return response()->json([
            'data' => $data
        ]);
    }

    public function updateAction(CapNhapActionRequest $request)
    {
        $quyen = Quyen::find($request->id_quyen);
        $quyen->update([
            'list_rule' => $request->list_rule
        ]);

        return response()->json([
            'status' => true,
            'message' => "Cập Nhập Phân Quyền Thành Công!",
        ]);
    }
}
