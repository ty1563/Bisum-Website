<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    public function run()
    {
        DB::table('admins')->delete();

        DB::table('admins')->truncate();

        DB::table('admins')->insert([
            [
                'email'             =>  'nvtnvtalone15@gmail.com',
                'password'          =>  bcrypt('123456'),
                'ho_va_ten'         =>  'Nguyễn Văn Tỵ',
                'ngay_sinh'         =>  '1999-04-01',
                'so_dien_thoai'     =>  '0905555555',
                'id_quyen'          =>   1,
                'is_master'          =>  1,
            ],
        ]);
    }
}
