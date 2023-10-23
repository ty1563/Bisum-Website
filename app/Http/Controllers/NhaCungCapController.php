<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateNhaCungCapRequest;
use App\Http\Requests\DeleteNhaCungCapRequest;
use App\Http\Requests\DeleteRequest;
use App\Http\Requests\UpdateNhaCungCapRequest;
use App\Models\NhaCungCap;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class NhaCungCapController extends Controller
{
    public function index()
    {
        $check = $this->checkRule_get(21);
        if(!$check) {
            toastr()->error('Bạn không có quyền truy cập chức năng này!');
            return redirect('/admin');
        }

        return view('admin.page.nha_cung_cap.index');
    }

    public function store(CreateNhaCungCapRequest $request)
    {
        $check = $this->checkRule_post(22);
        if(!$check) {
            return response()->json([
                'status'  => false,
                'message' => 'Bạn không có quyền truy cập chức năng này!',
            ]);
        }

        $data = $request->all();

        NhaCungCap::create($data);

        return response()->json([
            'status'    => true,
            'message'   => 'Đã tạo mới nhà cung cấp thành công!',
        ]);
    }

    public function data()
    {
        $check = $this->checkRule_get(21);
        if(!$check) {
            toastr()->error('Bạn không có quyền truy cập chức năng này!');
            return redirect('/admin');
        }

        $data = NhaCungCap::all();

        return response()->json([
            'data'  => $data,
        ]);
    }

    public function destroy(DeleteNhaCungCapRequest $request)
    {
        $check = $this->checkRule_post(23);
        if(!$check) {
            return response()->json([
                'status'  => false,
                'message' => 'Bạn không có quyền truy cập chức năng này!',
            ]);
        }

        $nhaCungCap = NhaCungCap::where('id', $request->id)->first();
        $nhaCungCap->delete();

        return response()->json([
            'status'    => true,
            'message'   => 'Đã xóa thành công nhà cung cấp!',
        ]);
    }

    public function update(UpdateNhaCungCapRequest $request)
    {
        $check = $this->checkRule_post(24);
        if(!$check) {
            return response()->json([
                'status'  => false,
                'message' => 'Bạn không có quyền truy cập chức năng này!',
            ]);
        }

        $data    = $request->all();
        // dd($data);
        $nhaCungCap = NhaCungCap::find($request->id);
        $nhaCungCap->update($data);

        return response()->json([
            'status'    => true,
            'message'   => 'Đã cập nhật thành công nhà cung cấp!',
        ]);
    }

    public function checkMST(Request $request)
    {
        $client = new Client([
            'headers' => [ 'Content-Type' => 'application/json' ]
        ]);
        $link = 'https://api.vietqr.io/v2/business/' . $request->mst;
        $respone = $client->get($link);

        $res  = json_decode($respone->getBody()->getContents(), true);

        if($res['code'] == '00') {
            return response()->json([
                'status'        => true,
                'message'       => 'Đã lấy được mã số thuế!',
                'ten_cong_ty'   => $res['data']['name'],
                'dia_chi'       => $res['data']['address'],
            ]);
        } else {
            return response()->json([
                'status'        => false,
                'message'       => 'Mã số thuế không tồn tại!',
            ]);
        }
    }
}
