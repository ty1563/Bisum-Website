<?php

namespace App\Http\Controllers;

use App\Http\Requests\TinTuc\CreateTinTucRequest;
use App\Http\Requests\TinTuc\DeleteTinTucRequest;
use App\Http\Requests\TinTuc\UpdateTinTucRequest;
use App\Models\TinTuc;
use Illuminate\Http\Request;

class TinTucController extends Controller
{

    public function index()
    {
        $check = $this->checkRule_get(16);
        if(!$check) {
            toastr()->error('Bạn không có quyền truy cập chức năng này!');
            return redirect('/admin');
        }

        return view('admin.page.tin_tuc.index');
    }
    public function store(CreateTinTucRequest $request)
    {
        $check = $this->checkRule_post(17);
        if(!$check) {
            return response()->json([
                'status'  => false,
                'message' => 'Bạn không có quyền truy cập chức năng này!',
            ]);
        }

        $data = $request->all();

        TinTuc::create($data);

        return response()->json([
            'status'    => true,
            'message'   => 'Đã thêm mới tin tức thành công!',
        ]);
    }
    public function data()
    {
        $check = $this->checkRule_get(16);
        if(!$check) {
            toastr()->error('Bạn không có quyền truy cập chức năng này!');
            return redirect('/admin');
        }

        $data = TinTuc::get();

        return response()->json([
            'data'  => $data,
        ]);
    }
    public function destroy(DeleteTinTucRequest $request)
    {
        $check = $this->checkRule_post(19);
        if(!$check) {
            return response()->json([
                'status'  => false,
                'message' => 'Bạn không có quyền truy cập chức năng này!',
            ]);
        }

        TinTuc::where('id', $request->id)->first()->delete();

        return response()->json([
            'status'    => true,
        ]);
    }
    public function update(UpdateTinTucRequest $request)
    {
        $check = $this->checkRule_post(20);
        if(!$check) {
            return response()->json([
                'status'  => false,
                'message' => 'Bạn không có quyền truy cập chức năng này!',
            ]);
        }

        $data = $request->all();
        $phim = TinTuc::where('id', $request->id)->first();
        $phim->update($data);

        return response()->json([
            'status'    => true,
        ]);
    }
    public function changeStatus($id)
    {
        $check = $this->checkRule_get(18);
        if(!$check) {
            return response()->json([
                'status'  => false,
                'message' => 'Bạn không có quyền truy cập chức năng này!',
            ]);
        }

        $tinTuc = TinTuc::where('id', $id)->first();

        if($tinTuc) {
            $tinTuc->trang_thai = !$tinTuc->trang_thai;
            $tinTuc->save();
            return response()->json([
                'status'  => true,
            ]);
        } else {
            return response()->json([
                'status'  => false,
                'message' => 'Tin tức không tồn tại!',
            ]);
        }
    }

    public function viewDetailBaiViet($string)
    {
        if (preg_match('/post(\d+)/', $string, $matches)) {
            $id = $matches[1];
            $value = TinTuc::where('id', $id)->first();

            if($value) {
                $list_bai_viet = TinTuc::where('loai_bai_viet', $value->loai_bai_viet)
                            ->take(6)->get();

                return view('client.detail_bai_viet', compact('value', 'list_bai_viet'));
            } else {
                toastr()->error('Sản phẩm không tồn tại!');
                return redirect('/');
            }
        } else {
            toastr()->error('Sản phẩm không tồn tại!');
            return redirect('/');
        }
    }
}
