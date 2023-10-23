@extends('client.master')
@section('noi_dung')
    <main id="MainContent" class="content-for-layout">
        <div class="login-page mt-100">
            <div class="container" id="app">
                <form v-if="status==0" v-on:submit.prevent="mailSend()" id="formdata" class="login-form common-form mx-auto">
                    <div class="section-header mb-3">
                        <h5 class="section-heading text-center">@{{ text }}</h5>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <fieldset>
                                <label class="label">Nhập địa chỉ email</label>
                                <input type="email" name="email" v-model="email">
                                <span style="color:green">@{{alert}}</span>
                            </fieldset>
                        </div>
                        <div class="col-12 mt-3">
                            <button type="submit" class="btn-primary d-block mt-4 btn-signin">Gửi Mã Xác Nhận</button>
                        </div>
                    </div>
                </form>
                <form v-if="status==1" v-on:submit.prevent="xacNhanRs()" id="formdata1"
                    class="login-form common-form mx-auto">
                    <div class="section-header mb-3">
                        <h5 class="section-heading text-center">@{{ text }}</h5>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <fieldset>
                                <label class="label">Nhập Mã Xác Nhận</label>
                                <input v-model="ma" maxlength="6" class="text-center" type="text" name="ma">
                                <span style="color:green">@{{alert}}</span>
                            </fieldset>
                        </div>
                        <div class="col-12 mt-3">
                            <button type="submit" class="btn-primary d-block mt-4 btn-signin">Gửi Mã Xác Nhận</button>
                        </div>
                    </div>
                </form>
                <form v-if="status==2" v-on:submit.prevent="matKhauMoi()" id="formdata1"
                    class="login-form common-form mx-auto">
                    <div class="section-header mb-3">
                        <h5 class="section-heading text-center">@{{ text }}</h5>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <fieldset>
                                <label class="label">Nhập Mật Khẩu Mới</label>
                                <input v-model="newPass" class="text-center" type="text" name="newPass">
                                <span style="color:green">@{{alert}}</span>
                            </fieldset>
                        </div>
                        <div class="col-12 mt-3">
                            <button type="submit" class="btn-primary d-block mt-4 btn-signin">Đổi Mật Khẩu</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
@section('js')
    <script>
        new Vue({
            el: '#app',
            data: {
                status: 0,
                email: null,
                ma: null,
                newPass: null,
                text: 'Quên Mật Khẩu',
                alert : null,
            },
            methods: {
                matKhauMoi() {
                    var data = {
                        'email': this.email,
                        'password': this.newPass,
                        'key': this.ma,
                    };
                    axios
                        .post('/change-password', data)
                        .then((res) => {
                            if (res.data.status) {
                                toastr.success(res.data.message);
                                this.alert = "Đổi Mật Khẩu "+this.email+" Thành Công";
                                setTimeout(() => {
                                    window.location.href = "/auth";
                                }, 1300);
                            } else {
                                toastr.error(res.data.message);
                                setTimeout(() => {
                                    window.location.href = "/auth";
                                }, 1300);
                            }
                        })
                        .catch((res) => {
                            $.each(res.response.data.errors, function(k, v) {
                                toastr.error(v[0]);
                            });
                        });
                },
                xacNhanRs() {
                    var paramObj = {};
                    $.each($('#formdata1').serializeArray(), function(_, kv) {
                        if (paramObj.hasOwnProperty(kv.name)) {
                            paramObj[kv.name] = $.makeArray(paramObj[kv.name]);
                            paramObj[kv.name].push(kv.value);
                        } else {
                            paramObj[kv.name] = kv.value;
                        }
                    });
                    paramObj.email = this.email;
                    axios
                        .post('/check-reset-password', paramObj)
                        .then((res) => {
                            if (res.data.status) {
                                toastr.success(res.data.message);
                                this.text = "Nhập Mật Khẩu Mới";
                                this.status = 2;
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
                mailSend() {
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
                        .post('/mail-send-reset-password', paramObj)
                        .then((res) => {
                            if (res.data.status) {
                                toastr.success(res.data.message);
                                this.status = 1;
                                this.text = 'Nhập Mã Xác Nhận';
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
