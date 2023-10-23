@extends('client.master')
@section('noi_dung')
<main id="MainContent" class="content-for-layout">
    <div class="login-page mt-100">
        <div class="container">
            <form action="#" class="login-form common-form mx-auto">
                <div class="section-header mb-3">
                    <h5 class="section-heading text-center">Cập Nhật Mật Khẩu</h5>
                </div>
                <div class="row">
                    <div class="col-12">
                        <fieldset>
                            <label class="label">Mật khẩu</label>
                            <input type="password">
                        </fieldset>
                    </div>
                    <div class="col-12">
                        <fieldset>
                            <label class="label">Nhập lại mật khẩu</label>
                            <input type="password">
                        </fieldset>
                    </div>
                    <div class="col-12 mt-3">
                        <button class="btn-primary d-block mt-4 btn-signin">Đổi mật khẩu</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
@section('js')

@endsection
