<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaiKhoanAdminRequest;
use App\Http\Requests\DeleteTaiKhoanRequest;
use App\Models\Admin;
use App\Models\Quyen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use PhpParser\Node\Expr\FuncCall;

class AdminController extends Controller
{
    public function viewHome()
    {
        return redirect()->route('home');
    }
    public function index_form()
    {
        $check = $this->checkRule_get(12);
        if(!$check) {
            toastr()->error('Bạn không có quyền truy cập chức năng này!');
            return redirect('/admin');
        }

        $data = Admin::get(); //Admin::all();

        return view('admin.page.tai_khoan.index_form', compact('data'));
    }

    public function create_form(Request $request)
    {
        $check = $this->checkRule_post(13);
        if(!$check) {
            toastr()->error('Bạn không có quyền truy cập chức năng này!');
            return redirect('/admin/index');
        }

        $data = $request->all();

        Admin::create($data);

        return redirect('/admin/tai-khoan/index-form');
    }

    public function create_ajax(CreateTaiKhoanAdminRequest $request)
    {
        $check = $this->checkRule_post(13);
        if(!$check) {
            return response()->json([
                'status'  => false,
                'message' => 'Bạn không có quyền truy cập chức năng này!',
            ]);
        }

        $data = $request->all();
        $data['password'] = bcrypt($request->password);

        Admin::create($data);

        return response()->json([
            'status'    => true
        ]);
    }

    public function index_ajax()
    {
        $check = $this->checkRule_get(12);
        if(!$check) {
            toastr()->error('Bạn không có quyền truy cập chức năng này!');
            return redirect('/admin');
        }

        return view('admin.page.tai_khoan.index_ajax');
    }

    public function index_vue()
    {
        $check = $this->checkRule_get(12);
        if(!$check) {
            toastr()->error('Bạn không có quyền truy cập chức năng này!');
            return redirect('/admin');
        }
        $list_quyen = Quyen::all();
        return view('admin.page.tai_khoan.index_vue', compact('list_quyen'));
    }

    public function data()
    {
        $check = $this->checkRule_get(12);
        if(!$check) {
            toastr()->error('Bạn không có quyền truy cập chức năng này!');
            return redirect('/admin');
        }

        $data = Admin::leftjoin('quyens', 'quyens.id', 'admins.id_quyen')
                     ->select('admins.*', 'quyens.ten_quyen')->get();

        return response()->json([
            'data'  => $data,
        ]);
    }

    public function viewLogin()
    {
        return view('admin.login');
    }

    public function actionLogin(Request $request)
    {
        // Kiểm tra $request->email và $request->password có giống với tài khoản nào không?
        $data['email']      = $request->email;
        $data['password']   = $request->password;

        $check = Auth::guard('admin')->attempt($data); // True/False

        return response()->json([
            'status'    => $check,
        ]);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        toastr()->success('Đã Đăng xuất thành công!');
        return redirect('/admin/login');
    }

    public function update(Request $request)
    {
        $check = $this->checkRule_post(10);
        if(!$check) {
            return response()->json([
                'status'  => false,
                'message' => 'Bạn không có quyền truy cập chức năng này!',
            ]);
        }

        $data = $request->all();
        $admin = Admin::find($request->id);
        $admin->update($data);
        return response()->json([
            'status'    => true,
            'message' => 'Cập nhập tài khoản thành công!',
        ]);
    }

    public function destroy(DeleteTaiKhoanRequest $request)
    {
        $check = $this->checkRule_post(9);
        if(!$check) {
            return response()->json([
                'status'  => false,
                'message' => 'Bạn không có quyền truy cập chức năng này!',
            ]);
        }
        $admin =  Admin::find($request->id);

        $check = Auth::guard('admin')->user();
        if($admin->id == $check->id || $admin->is_master == 1) {
            return response()->json([
                'status'  => false,
                'message' => 'Bạn không thể xóa tài khoản này!',
            ]);
        }

        $admin->delete();
        return response()->json([
            'status'    => true,
            'message' => 'Xóa nhân viên thành công!',
        ]);
    }
}
