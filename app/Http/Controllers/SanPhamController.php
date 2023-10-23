<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteSanPhamRequest;
use App\Http\Requests\TaoSanPhamRequest;
use App\Http\Requests\UpdateSanPhamRequest;
use App\Models\SanPham;
use Illuminate\Http\Request;

class SanPhamController extends Controller
{
    public function chiTiet($string)
    {
        if (preg_match('/post(\d+)/', $string, $matches)) {
            $id = $matches[1];
            $value = SanPham::where('san_phams.id', $id)
                        ->join('chuyen_mucs', 'san_phams.id_chuyen_muc', 'chuyen_mucs.id')
                        ->select('san_phams.*', 'chuyen_mucs.ten_chuyen_muc')
                        ->first();

            if($value) {
                $cate = SanPham::where('id_chuyen_muc', '<>', $value->id_chuyen_muc)
                            ->orwhere('gia_ban', '<=', $value->gia_ban)
                            ->take(6)->get();

                return view('client.chi_tiet_san_pham', compact('value', 'cate'));
            } else {
                toastr()->error('Sản phẩm không tồn tại!');
                return redirect('/');
            }
        } else {
            toastr()->error('Sản phẩm không tồn tại!');
            return redirect('/');
        }
    }

    public function index_old()
    {
        return view('admin.page.san_pham.index');
    }

    public function index()
    {
        $check = $this->checkRule_get(6);
        if(!$check) {
            toastr()->error('Bạn không có quyền truy cập chức năng này!');
            return redirect('/admin');
        }

        return view('admin.page.san_pham.index_vue');
    }

    public function store(TaoSanPhamRequest $request)
    {
        $check = $this->checkRule_post(7);
        if(!$check) {
            return response()->json([
                'status'  => false,
                'message' => 'Bạn không có quyền truy cập chức năng này!',
            ]);
        }

        $data = $request->all();

        SanPham::create($data);

        return response()->json([
            'status'    => true,
            'message'   => 'Đã thêm mới sản phẩm thành công!',
        ]);
    }

    public function data()
    {
        $check = $this->checkRule_get(6);
        if(!$check) {
            toastr()->error('Bạn không có quyền truy cập chức năng này!');
            return redirect('/admin');
        }

        $data = SanPham::join('chuyen_mucs', 'san_phams.id_chuyen_muc', 'chuyen_mucs.id')
                       ->select('san_phams.*', 'chuyen_mucs.ten_chuyen_muc')
                       ->get();

        return response()->json([
            'data'  => $data,
        ]);
    }

    public function destroy(DeleteSanPhamRequest $request)
    {
        $check = $this->checkRule_post(9);
        if(!$check) {
            return response()->json([
                'status'  => false,
                'message' => 'Bạn không có quyền truy cập chức năng này!',
            ]);
        }

        SanPham::where('id', $request->id)->delete();

        return response()->json([
            'status'    => true,
            'message'   => 'Đã xóa sản phẩm thành công!',
        ]);

    }

    public function update(UpdateSanPhamRequest $request)
    {
        $check = $this->checkRule_post(10);
        if(!$check) {
            return response()->json([
                'status'  => false,
                'message' => 'Bạn không có quyền truy cập chức năng này!',
            ]);
        }

        $data    = $request->all();
        $sanPham = SanPham::find($request->id); // where('id', $request->id)->first();
        $sanPham->update($data);

        return response()->json([
            'status'    => true,
            'message'   => 'Đã cập nhật phẩm thành công!',
        ]);
    }

    public function search(Request $request)
    {
        $check = $this->checkRule_post(11);
        if(!$check) {
            return response()->json([
                'status'  => false,
                'message' => 'Bạn không có quyền truy cập chức năng này!',
            ]);
        }

        $data = SanPham::where('ten_san_pham', 'like', '%' . $request->search_sp_serve . '%')
                       ->get();

        return response()->json([
            'data'  => $data,
        ]);
    }
}
