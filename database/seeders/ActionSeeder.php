<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActionSeeder extends Seeder
{

    public function run()
    {
        DB::table('actions')->delete();

        DB::table('actions')->truncate();

        DB::table('actions')->insert([
            ['id' => 1, 'ten_action' => 'Xem chuyên mục'],
            ['id' => 2, 'ten_action' => 'Thêm mới chuyên mục'],
            ['id' => 3, 'ten_action' => 'Đổi trạng thái chuyên mục'],
            ['id' => 4, 'ten_action' => 'Xóa chuyên mục'],
            ['id' => 5, 'ten_action' => 'Cập nhật chuyên mục'],
            ['id' => 6, 'ten_action' => 'Xem sản phẩm'],
            ['id' => 7, 'ten_action' => 'Thêm mới sản phẩm'],
            ['id' => 8, 'ten_action' => 'Đổi trạng thái sản phẩm'],
            ['id' => 9, 'ten_action' => 'Xóa sản phẩm'],
            ['id' => 10, 'ten_action' => 'Cập nhật sản phẩm'],
            ['id' => 11, 'ten_action' => 'Tìm kiếm sản phẩm'],
            ['id' => 12, 'ten_action' => 'Xem tài khoản'],
            ['id' => 13, 'ten_action' => 'Thêm mới tài khoản'],
            ['id' => 14, 'ten_action' => 'Xóa tài khoản'],
            ['id' => 15, 'ten_action' => 'Cập nhật tài khoản'],
            ['id' => 16, 'ten_action' => 'Xem tin tức'],
            ['id' => 17, 'ten_action' => 'Thêm mới tin tức'],
            ['id' => 18, 'ten_action' => 'Đổi trạng thái tin tức'],
            ['id' => 19, 'ten_action' => 'Xóa tin tức'],
            ['id' => 20, 'ten_action' => 'Cập nhật tin tức'],
            ['id' => 21, 'ten_action' => 'Xem nhà cung cấp'],
            ['id' => 22, 'ten_action' => 'Thêm mới nhà cung cấp'],
            ['id' => 23, 'ten_action' => 'Xóa nhà cung cấp'],
            ['id' => 24, 'ten_action' => 'Cập nhật nhà cung cấp'],
            ['id' => 25, 'ten_action' => 'Xem nhập kho'],
            ['id' => 26, 'ten_action' => 'Nhập kho sản phẩm'],
            ['id' => 27, 'ten_action' => 'Cập nhật nhập kho'],
            ['id' => 28, 'ten_action' => 'Xóa nhập kho'],
            ['id' => 29, 'ten_action' => 'Xem cấu hình'],
            ['id' => 30, 'ten_action' => 'Thêm mới cấu hình'],
            ['id' => 31, 'ten_action' => 'Xem đơn hàng'],
            ['id' => 32, 'ten_action' => 'Xem chi tiết đơn hàng'],
            ['id' => 34, 'ten_action' => 'Đổi trạng thái giao hàng'],
            ['id' => 35, 'ten_action' => 'Nhập kho chính thức'],
            ['id' => 36, 'ten_action' => 'Xem thống kê'],
            ['id' => 37, 'ten_action' => 'Thống kê theo ngày'],
        ]);
    }
}
