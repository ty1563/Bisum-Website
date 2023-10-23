<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchThongKebanHangRequest;
use App\Models\ChiTietBanHang;
use App\Models\DonHang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ThongKeController extends Controller
{
    public function index()
    {
        $check = $this->checkRule_get(36);
        if(!$check) {
            toastr()->error('Bạn không có quyền truy cập chức năng này!');
            return redirect('/admin');
        }

        $data = ChiTietBanHang::join('san_phams', 'san_phams.id', 'chi_tiet_ban_hangs.id_san_pham')
                                ->join('don_hangs', 'don_hangs.id', 'chi_tiet_ban_hangs.id_don_hang')
                                ->select('san_phams.ten_san_pham',
                                        DB::raw('SUM(chi_tiet_ban_hangs.so_luong) as so_luong'),
                                    )
                                ->groupBy('san_phams.ten_san_pham')
                                ->get();
        $array_san_pham = [];
        $array_so_luong = [];
        foreach ($data as $key => $value) {
            array_push($array_san_pham, $value->ten_san_pham);
            array_push($array_so_luong, $value->so_luong);

        }
        $tu_ngay = Carbon::today()->format("Y-m-d");
        $den_ngay = Carbon::today()->format("Y-m-d");
        return view('admin.page.thong_ke.index', compact('data', 'array_san_pham', 'array_so_luong', 'tu_ngay', 'den_ngay'));
    }
    public function total(Request $request){
        $total['tong_don_hang'] = ChiTietBanHang::count();
        $total['tong_doanh_thu_thang'] = ChiTietBanHang::whereMonth('created_at',Carbon::now()->month)
                                                ->sum("thanh_tien");
        return response()->json([
            'status' => true,
            'data'=>$total,
        ]);
    }
    public function search(SearchThongKebanHangRequest $request)
    {
        $check = $this->checkRule_post(37);
        if(!$check) {
            toastr()->error('Bạn không có quyền truy cập chức năng này!');
            return redirect('/admin');
        }

        $data = ChiTietBanHang::join('san_phams', 'san_phams.id', 'chi_tiet_ban_hangs.id_san_pham')
                                ->join('don_hangs', 'don_hangs.id', 'chi_tiet_ban_hangs.id_don_hang')
                                ->whereDate('don_hangs.created_at', '>=', $request->day_begin)
                                ->whereDate('don_hangs.created_at', '<=', $request->day_end)
                                ->select('san_phams.ten_san_pham',
                                        DB::raw('SUM(chi_tiet_ban_hangs.so_luong) as so_luong'),
                                    )
                                ->groupBy('san_phams.ten_san_pham')
                                ->get();
        $array_san_pham = [];
        $array_so_luong = [];
        foreach ($data as $key => $value) {
            array_push($array_san_pham, $value->ten_san_pham);
            array_push($array_so_luong, $value->so_luong);

        }
        $tu_ngay = Carbon::parse($request->day_begin)->format("Y-m-d");
        $den_ngay = Carbon::parse($request->day_end)->format("Y-m-d");
        return view('admin.page.thong_ke.index', compact('data', 'array_san_pham', 'array_so_luong', 'tu_ngay', 'den_ngay'));
    }
}
