<?php

namespace App\Http\Controllers;

use App\Http\Requests\ThanhToanRequest;
use App\Jobs\XacNhanDonHangJob;
use App\Jobs\XacNhanJob;
use App\Models\ChiTietBanHang;
use App\Models\DonHang;
use App\Models\KhachHang;
use App\Models\Transaction;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonHangController extends Controller
{
    public function auto()
    {


        $client = new Client([
            'headers' => ['Content-Type' => 'application/json']
        ]);
        $now  = Carbon::today()->format('d/m/Y');
        $link = 'http://api.danangseafood.vn/api';
        $respone = $client->post($link, [
            'body' => json_encode(
                [
                    'begin'           => $now,
                    'end'             => $now,
                    'username'        => '0889470271',
                    'password'        => 'Vodinhquochuy@gmail1',
                    'accountNumber'   => '0651000883491'
                ]
            )
        ]);
        $res  = json_decode($respone->getBody()->getContents(), true);
        if ($res['success']) {
            foreach ($res['results'] as $key => $value) {
                $so_tien = str_replace(".", "", $value['Amount']);
                $so_tien = str_replace(",", "", $so_tien);
                if ($value['CD'] == '+') {
                    $check = Transaction::where('Reference', $value['Reference'])->first();
                    if (!$check) {
                        $str            =  $value['Description'];
                        $id_don_hang    =  0;
                        $tim   = strpos($str, "DHDZ");
                        if ($tim) {
                            $str = substr($str, $tim, 11);
                            $donHang = DonHang::where('hash_don_hang', $str)->first();
                            if ($donHang && $donHang->tong_thanh_toan <= $so_tien) {
                                $donHang->thanh_toan = 0;
                                $donHang->save();
                                $id_don_hang = $donHang->id;
                                //Gửi mail thông báo khách hàng
                                $khachHang              = KhachHang::find($donHang->id_khach_hang);
                                $info['email']          = $khachHang->email;
                                $info['ho_va_ten']      = $khachHang->ho_va_ten;
                                $info['so_tien']        = $donHang->tong_thanh_toan;
                                $info['don_hang']       = $str;
                                XacNhanJob::dispatch($info);
                            }
                        }

                        Transaction::create([
                            'tranDate'      =>  $value['tranDate'],
                            'Reference'     =>  $value['Reference'],
                            'Amount'        =>  $so_tien,
                            'Description'   =>  $value['Description'],
                            'id_don_hang'   =>  $id_don_hang,
                        ]);
                    }
                }
            }
        }
    }

    public function checkout()
    {
        $khachHang = Auth::guard('customer')->user();

        $gioHang   = ChiTietBanHang::where('id_khach_hang', $khachHang->id)
            ->where('id_don_hang', 0)
            ->first();
        if ($gioHang) {
            return view('client.checkout', compact('khachHang'));
        } else {
            toastr()->error('Không có giỏ hàng thì làm sao mà thanh toán. Khùng à!');
            return redirect('/');
        }
    }

    public function process(ThanhToanRequest $request)
    {
        // dd($request->all());
        $khachHang = Auth::guard('customer')->user();
        // Xử lý hàng còn đủ hay không ở đầu giờ bữa sau. Khi não thông minh hơn
        $donHang   = DonHang::create([
            'ho_lot'            => $request->ho_lot,
            'ten_khach'         => $request->ten_khach,
            'ho_va_ten'         => $request->ho_lot . ' ' . $request->ten_khach,
            'email'             => $request->email,
            'so_dien_thoai'     => $request->so_dien_thoai,
            'dia_chi'           => $request->dia_chi,
            'id_khach_hang'     => $khachHang->id,
            'hash_don_hang'     => 'Chưa có, tí tính nhé',
        ]);

        $gioHang  = ChiTietBanHang::where('id_khach_hang', $khachHang->id)
            ->where('id_don_hang', 0)
            ->join('san_phams', 'chi_tiet_ban_hangs.id_san_pham', 'san_phams.id')
            ->select('chi_tiet_ban_hangs.*', 'san_phams.ten_san_pham', 'san_phams.slug_san_pham', 'san_phams.hinh_anh', 'san_phams.gia_ban', 'san_phams.gia_khuyen_mai')
            ->get();

        $total = 0;
        $count_ship = 0;
        foreach ($gioHang as $key => $value) {
            $don_gia = $value->gia_ban;
            if ($value->gia_khuyen_mai > 0) {
                $don_gia = $value->gia_khuyen_mai;
            }
            $total += $don_gia * $value->so_luong;
            $count_ship += $value->so_luong;
        }
        if ($count_ship < 3) {
            $ship = 30000;
        } else {
            $ship = $count_ship * 10000;
        }

        $ship = $ship / 10000;

        $donHang->hash_don_hang     = 'DHDZ' . (2342459 + $donHang->id);
        $donHang->phi_ship          = $ship;
        $donHang->tien_hang         = $total;
        $donHang->tong_thanh_toan   = $total + $ship;
        $donHang->save();

        $info['nguoi_mua']          = $khachHang->ho_va_ten;
        $info['nguoi_nhan']         = $request->ho_lot . ' ' . $request->ten_khach;
        $info['dia_chi']            = $request->dia_chi;
        $info['email']              = $khachHang->email;
        $info['tong_tien']          = $donHang->tong_thanh_toan;
        $info['ma_don']             = $donHang->hash_don_hang;

        XacNhanDonHangJob::dispatch($info, $gioHang);

        ChiTietBanHang::where('id_khach_hang', $khachHang->id)
            ->where('id_don_hang', 0)
            ->update([
                'id_don_hang'    =>  $donHang->id
            ]);

        return response()->json([
            'status'    => 1,
            'message'   => 'Đã đặt hàng thành công!',
        ]);
    }

    public function viewDonhang()
    {
        return view('client.don_hang');
    }

    public function getDataDonHang()
    {
        $khachHang = Auth::guard('customer')->user();

        $data = DonHang::where('id_khach_hang', $khachHang->id)
            ->orderByDESC('created_at')
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function chiTietDonHang($id)
    {
        $data = ChiTietBanHang::where('id_don_hang', $id)
            ->join('san_phams', 'chi_tiet_ban_hangs.id_san_pham', 'san_phams.id')
            ->select('chi_tiet_ban_hangs.*', 'san_phams.ten_san_pham', 'san_phams.slug_san_pham', 'san_phams.hinh_anh', 'san_phams.gia_ban', 'san_phams.gia_khuyen_mai')
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function viewDH()
    {
        $check = $this->checkRule_get(31);
        if (!$check) {
            toastr()->error('Bạn không có quyền truy cập chức năng này!');
            return redirect('/admin');
        }

        return view('admin.page.danh_sach_don_hang.index');
    }
    public function getDataDonHangAdmin()
    {
        $check = $this->checkRule_get(31);
        if (!$check) {
            toastr()->error('Bạn không có quyền truy cập chức năng này!');
            return redirect('/admin');
        }

        $khachHang = Auth::guard('customer')->user();

        $data = DonHang::where('id_khach_hang', $khachHang->id)->get();

        return response()->json([
            'data' => $data
        ]);
    }
    public function chiTietDonHangAdmin($id)
    {
        $check = $this->checkRule_get(32);
        if (!$check) {
            return response()->json([
                'status'  => false,
                'message' => 'Bạn không có quyền truy cập chức năng này!',
            ]);
        }

        $data = ChiTietBanHang::where('id_don_hang', $id)
            ->join('san_phams', 'chi_tiet_ban_hangs.id_san_pham', 'san_phams.id')
            ->select('chi_tiet_ban_hangs.*', 'san_phams.ten_san_pham', 'san_phams.slug_san_pham', 'san_phams.hinh_anh', 'san_phams.gia_ban', 'san_phams.gia_khuyen_mai')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    public function changeGiaoHang(Request $request)
    {
        $check = $this->checkRule_post(34);
        if (!$check) {
            return response()->json([
                'status'  => false,
                'message' => 'Bạn không có quyền truy cập chức năng này!',
            ]);
        }

        $donHang = DonHang::find($request->id);
        if ($donHang) {
            $donHang->giao_hang = $request->giao_hang;
            $donHang->save();

            return response()->json([
                'status' => true,
                'message' => 'Đổi trạng thái thành công',
            ]);
        }
    }
}
