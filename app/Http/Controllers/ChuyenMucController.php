<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChuyenMuc\ChuyenMucRequest;
use App\Http\Requests\ChuyenMuc\UpdateChuyenMucRequest;
use App\Models\ChuyenMuc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChuyenMucController extends Controller
{
    public function index()
    {
        $check = $this->checkRule_get(1);
        if(!$check) {
            toastr()->error('Bạn không có quyền truy cập chức năng này!');
            return redirect('/admin');
        }

        return view('admin.page.chuyen_muc.index');
    }

    public function indexVue()
    {
        $check = $this->checkRule_get(1);
        if(!$check) {
            toastr()->error('Bạn không có quyền truy cập chức năng này!');
            return redirect('/admin');
        }

        return view('admin.page.chuyen_muc.index_vue');
    }

    public function data()
    {
        $check = $this->checkRule_get(1);
        if(!$check) {
            toastr()->error('Bạn không có quyền truy cập chức năng này!');
            return redirect('/admin');
        }

        $sql  = "SELECT A.*, B.ten_chuyen_muc as ten_chuyen_muc_cha
                 FROM chuyen_mucs A LEFT JOIN chuyen_mucs B
                 on A.id_chuyen_muc_cha = B.id";
        $data = DB::select($sql);
        return response()->json([
            'list' => $data
        ]);
    }

    public function changeStatus($id)
    {
        $check = $this->checkRule_get(3);
        if(!$check) {
            return response()->json([
                'status'  => false,
                'message' => 'Bạn không có quyền truy cập chức năng này!',
            ]);
        }

        $chuyenMuc = ChuyenMuc::where('id', $id)->first();
        if($chuyenMuc){
            $chuyenMuc->tinh_trang = !$chuyenMuc->tinh_trang;
            $chuyenMuc->save();
            return response()->json([
                'status'    => true,
                'message'   => 'Đã đổi trạng thái thành công'
            ]);
        }else{
            return response()->json([
                'status'     => false,
                'message'    => 'Đã có lỗi sản phẩm không tồn tại !'
            ]);
        }
    }

    public function store(ChuyenMucRequest $request)
    {
        $check = $this->checkRule_post(2);
        if(!$check) {
            return response()->json([
                'status'  => false,
                'message' => 'Bạn không có quyền truy cập chức năng này!',
            ]);
        }

        ChuyenMuc::create([
            'ten_chuyen_muc'        => $request->ten_chuyen_muc,
            'slug_chuyen_muc'       => $request->slug_chuyen_muc,
            'tinh_trang'            => $request->tinh_trang,
            'id_chuyen_muc_cha'     => $request->id_chuyen_muc_cha,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Đã thêm mới chuyên mục thành công'
        ]);
    }

    public function doiTrangThai($id)
    {
        $check = $this->checkRule_get(3);
        if(!$check) {
            return response()->json([
                'status'  => false,
                'message' => 'Bạn không có quyền truy cập chức năng này!',
            ]);
        }
        $chuyenMuc = ChuyenMuc::where('id', $id)->first(); // ChuyenMuc::find($id);
        if($chuyenMuc) {
            $chuyenMuc->tinh_trang = !$chuyenMuc->tinh_trang;
            $chuyenMuc->save();
            return response()->json([
                'status' => true,
            ]);
        } else {
            return response()->json([
                'status' => false,
            ]);
        }
    }

    public function destroy($id)
    {
        $check = $this->checkRule_get(4);
        if(!$check) {
            return response()->json([
                'status'  => false,
                'message' => 'Bạn không có quyền truy cập chức năng này!',
            ]);
        }

        $chuyenMuc = ChuyenMuc::find($id); // ChuyenMuc::where('id', $id)->first();
        if($chuyenMuc) {
            $chuyenMuc->delete();
            return response()->json([
                'status' => true,
                'message' => 'Đã xóa chuyên mục thành công',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Đã có lỗi, chuyên mục không tồn tại !',
            ]);
        }
    }

    public function edit($id)
    {
        $check = $this->checkRule_get(5);
        if(!$check) {
            return response()->json([
                'status'  => false,
                'message' => 'Bạn không có quyền truy cập chức năng này!',
            ]);
        }

        $chuyenMuc = ChuyenMuc::find($id); // ChuyenMuc::where('id', $id)->first();
        if($chuyenMuc) {
            return response()->json([
                'status' => true,
                'data'   => $chuyenMuc,
            ]);
        } else {
            return response()->json([
                'status' => false
            ]);
        }
    }

    public function update(UpdateChuyenMucRequest $request)
    {
        $check = $this->checkRule_post(5);
        if(!$check) {
            return response()->json([
                'status'  => false,
                'message' => 'Bạn không có quyền truy cập chức năng này!',
            ]);
        }

        $chuyenMuc = ChuyenMuc::find($request->id);
        if($chuyenMuc) {
            // update và return true
            $chuyenMuc->ten_chuyen_muc        = $request->ten_chuyen_muc;
            $chuyenMuc->slug_chuyen_muc       = $request->slug_chuyen_muc;
            $chuyenMuc->tinh_trang            = $request->tinh_trang;
            $chuyenMuc->id_chuyen_muc_cha     = $request->id_chuyen_muc_cha;
            $chuyenMuc->save();

            return response()->json([
                'status'    => true,
                'message'   => 'Đã cập nhật chuyên mục thành công'
            ]);
        } else {
            return response()->json([
                'status'    => false,
                'message'   => 'Đã có lỗi, chuyên mục không tồn tại !'
            ]);
        }
    }
}
