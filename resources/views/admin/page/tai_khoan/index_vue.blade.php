@extends('admin.share.master_page')
@section('noi_dung')
<div class="row" id="abcxyz">
    <div class="col-md-5">
        <div class="card">
            <form id="taoTaiKhoan" v-on:submit.prevent="themMoiTaiKhoan()">
                <div class="card-header">
                    Thêm Mới Tài Khoản
                </div>
                <div class="card-body">
                    <label>Họ Và Tên</label>
                    <input name="ho_va_ten" class="form-control mt-1" type="text" placeholder="Nhập vào họ và tên">
                    <label>Email</label>
                    <input name="email" class="form-control mt-1" type="email" placeholder="Nhập vào email">
                    <label>Mật Khẩu</label>
                    <input name="password" class="form-control mt-1" type="text">
                    <label>Nhập Lại Mật Khẩu</label>
                    <input name="re_password" class="form-control mt-1" type="text">
                    <label>Số Điện Thoại</label>
                    <input name="so_dien_thoai" class="form-control mt-1" type="text" placeholder="Nhập vào số điện thoại">
                    <label>Ngày Sinh</label>
                    <input name="ngay_sinh" class="form-control mt-1" type="date" placeholder="Nhập vào ngày sinh">
                    <label>Quyền</label>
                    <select name="id_quyen" class="form-control mt-1">
                        <option selected>Chọn Quyền</option>
                        @foreach ($list_quyen as $key => $value )
                            <option value="{{ $value->id }}">{{ $value->ten_quyen }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">Thêm Mới Tài Khoản</button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                Danh Sách Tài Khoản
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Họ Và Tên</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Số Điện Thoại</th>
                            <th class="text-center">Ngày Sinh</th>
                            <th class="text-center">Quyền</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(v, key) in listTK">
                            <tr>
                                <th class="text-center align-middle">@{{ key + 1 }}</th>
                                <td class="align-middle">@{{ v.ho_va_ten }}</td>
                                <td class="align-middle">@{{ v.email }}</td>
                                <td class="align-middle">@{{ v.so_dien_thoai }}</td>
                                <td class="align-middle">@{{ v.ngay_sinh }}</td>
                                <td class="align-middle">@{{ v.ten_quyen }}</td>
                                <td class="text-center">
                                    <button class="btn btn-info" v-on:click="getDetail(v)" data-bs-toggle="modal" data-bs-target="#editModal">Cập Nhật</button>
                                    <button class="btn btn-danger" v-on:click="getDetail(v)" data-bs-toggle="modal" data-bs-target="#deleteModal">Xóa Bỏ</button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            {{-- Model Xoa --}}
            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Xóa Nhân Viên</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Bạn có chắc chắn muốn xóa nhân viên: <b>"@{{ detail_admin.ho_va_ten }}"</b> này không?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button v-on:click="xoa_admin()" type="button" class="btn btn-danger" data-bs-dismiss="modal">Xác Nhận</button>
                    </div>
                </div>
                </div>
            </div>

            {{-- Model Edit --}}
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Chỉnh Sửa Nhân Viên</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="update_tai_khoan">
                            <input type="hidden" name="id" v-model="detail_admin.id">
                            <div class="col-md-12 mb-2">
                                <label>Họ Và Tên</label>
                                <input v-model="detail_admin.ho_va_ten" name="ho_va_ten" class="form-control mt-1" type="text" placeholder="Nhập vào họ và tên">
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>Email</label>
                                <input v-model="detail_admin.email" name="email" class="form-control mt-1" type="email" placeholder="Nhập vào email">
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>Số Điện Thoại</label>
                                <input v-model="detail_admin.so_dien_thoai" name="so_dien_thoai" class="form-control mt-1" type="text" placeholder="Nhập vào số điện thoại">
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>Ngày Sinh</label>
                                <input v-model="detail_admin.ngay_sinh" name="ngay_sinh" class="form-control mt-1" type="date" placeholder="Nhập vào ngày sinh">
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>Quyền</label>
                                <select v-model="detail_admin.id_quyen" name="id_quyen" class="form-control mt-1">
                                    <option selected>Chọn Quyền</option>
                                    @foreach ($list_quyen as $key => $value )
                                        <option value="{{ $value->id }}">{{ $value->ten_quyen }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button v-on:click="updateAdmin()" type="button" class="btn btn-primary" data-bs-dismiss="modal">Xác Nhận</button>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    new Vue({
        el      :   '#abcxyz',
        data    :   {
            listTK          : [],
            detail_admin    : {},
        },
        created()   {
            this.loadData();
        },
        methods :   {
            themMoiTaiKhoan()  {
                var paramObj = {};
                $.each($('#taoTaiKhoan').serializeArray(), function(_, kv) {
                    if (paramObj.hasOwnProperty(kv.name)) {
                        paramObj[kv.name] = $.makeArray(paramObj[kv.name]);
                        paramObj[kv.name].push(kv.value);
                    }
                    else {
                        paramObj[kv.name] = kv.value;
                    }
                });

                axios
                    .post('/admin/tai-khoan/create-ajax', paramObj)
                    .then((res) => {
                        if(res.data.status) {
                            toastr.success("Đã thêm mới tài khoản!");
                            $('#taoTaiKhoan').trigger("reset");
                            this.loadData();
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
            loadData() {
                axios
                    .get('/admin/tai-khoan/data')
                    .then((res) => {
                        this.listTK = res.data.data;
                    });
            },

            getDetail(value) {
                this.detail_admin = Object.assign({}, value);
            },

            updateAdmin() {
                var paramObj = {};
                $.each($('#update_tai_khoan').serializeArray(), function(_, kv) {
                    if (paramObj.hasOwnProperty(kv.name)) {
                        paramObj[kv.name] = $.makeArray(paramObj[kv.name]);
                        paramObj[kv.name].push(kv.value);
                    }
                    else {
                        paramObj[kv.name] = kv.value;
                    }
                });

                axios
                    .post('/admin/tai-khoan/update', paramObj)
                    .then((res) => {
                        if(res.data.status) {
                            toastr.success("Đã cập nhập tài khoản!");
                            this.loadData();
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

            xoa_admin() {
                axios
                    .post('/admin/tai-khoan/delete', this.detail_admin)
                    .then((res) => {
                        if(res.data.status) {
                            toastr.success("Đã xóa tài khoản thành công!");
                            this.loadData();
                        } else {
                            toastr.error(res.data.message);
                        }
                    })
                    .catch((res) => {
                        $.each(res.response.data.errors, function(k, v) {
                            toastr.error(v[0]);
                        });
                    });
            }
        },
    });
</script>
@endsection
