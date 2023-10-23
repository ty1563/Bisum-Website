<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteTaiKhoanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id'    =>  'required|exists:admins,id',
        ];
    }

    public function messages()
    {
        return [
            'id.*'  => 'Tài Khoản không tồn tại!',
        ];
    }
}
