<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchThongKebanHangRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'day_begin' => 'required|date',
            'day_end'   => 'required|date|after_or_equal:day_begin',
        ];
    }

    public function messages()
    {
        return [
            'day_begin.*'                 => 'Từ ngày không được để trống!',
            'day_end.*'                => 'Đến ngày không được để trống!',
        ];
    }
}
