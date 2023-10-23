<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuyenRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'id'        => 'required|exists:quyens,id',
        ];
    }

    public function messages()
    {
        return [
            'id.*'    => 'Quyền Không Tồn Tại!',
        ];
    }
}
