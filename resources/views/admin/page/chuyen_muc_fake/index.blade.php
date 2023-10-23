@extends('admin.share.master_page')
@section('noi_dung')
    <div class="row" id="app">
        <div>
            <form id="formdata" v-on:submit.prevent="add()">
                <input type="text" name="ten_chuyen_muc" placeholder="tên chuyên mục">
                <input type="text" name="ten_nguoi_tao" placeholder="tên người tạo">
                <button type="submit">Thêm Mới</button>
            </form>
        </div>
        <table class="table table-bordered">
            <thead>
                Bảng Chuyên Mục
            </thead>
            <tbody>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">ID</th>
                    <th class="text-center">Tên chuyên mục</th>
                    <th class="text-center">Tên Người Tạo</th>
                    <th class="text-center">Action</th>
                </tr>

                @foreach ($data as $key => $value)
                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td class="text-center">{{ $value->id }}</td>
                        <td class="text-center">{{ $value->ten_chuyen_muc }}</td>
                        <td class="text-center">{{ $value->ten_nguoi_tao }}</td>
                        <td class="text-center">
                            <button v-on:click="xoa({{ $value->id }})" class="btn btn-danger">Xóa</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('js')
    <script>
        new Vue({
            el: '#app',
            data: {

            },
            created() {

            },
            methods: {
                xoa(id){
                    axios
                        .post('/admin/chuyen-muc-fake/xoa/'+id,id)
                        .then((res) => {
                         if(res.data.status){
                            toastr.success(res.data.message);
                        } else {
                            toastr.error(res.data.message);
                        }
                        })
                        .catch((res) => {
                            $.each(res.response.data.errors, function(k, v) {
                                toastr.error(v[0]);
                            });
                        });
                },
                add() {
                    var paramObj = {};
                    $.each($('#formdata').serializeArray(), function(_, kv) {
                        if (paramObj.hasOwnProperty(kv.name)) {
                            paramObj[kv.name] = $.makeArray(paramObj[kv.name]);
                            paramObj[kv.name].push(kv.value);
                        } else {
                            paramObj[kv.name] = kv.value;
                        }
                    });
                    axios
                        .post('/admin/chuyen-muc-fake/themMoi', paramObj)
                        .then((response) => {
                            if (response.data.status) {
                                toastr.success(response.data.message);
                            } else {
                                toastr.error(response.data.message);
                            }
                        })
                },

            },
        });
    </script>
@endsection
