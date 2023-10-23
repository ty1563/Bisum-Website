@extends('admin.share.master_page')
@section('noi_dung')
    <div id="app">
        <div class="row">
            @php
                use Carbon\Carbon;
                $today = Carbon::now();
                $yesterday = Carbon::yesterday();
                $total['don_hang_hom_nay'] = \App\Models\ChiTietBanHang::whereDate('created_at', $today)->count();
                $total['don_hang_hom_qua'] = \App\Models\ChiTietBanHang::whereDate('created_at', $yesterday)->count();
                $total['count_member'] = \App\Models\KhachHang::count();
                $total['count_member_month'] = \App\Models\KhachHang::whereMonth('created_at', Carbon::now()->month)->count();
                $total['don_hang_thang_nay'] = \App\Models\ChiTietBanHang::whereMonth('created_at', Carbon::now()->month)->count();
                $total['don_hang_thang_truoc'] = \App\Models\ChiTietBanHang::whereMonth('created_at', Carbon::now()->month - 1)->count();
                $total['tong_doanh_thu_thang'] = number_format(\App\Models\ChiTietBanHang::whereMonth('created_at', Carbon::now()->month)->sum('thanh_tien'), 0, ',', '.') . '₫';
                $total['tong_doanh_thu_thang_truoc'] = \App\Models\ChiTietBanHang::whereMonth('created_at', Carbon::now()->month - 1)->sum('thanh_tien');
                $list_don_hang = \App\Models\DonHang::take(5)->get();
                $nhapKho = \App\Models\ChiTietHoaDonNhapKho::count('so_luong_nhap');
                $nhapKho_thang_nay = \App\Models\ChiTietHoaDonNhapKho::whereMonth('created_at', carbon::now()->month)->count('so_luong_nhap');
                $tong_tien_chua_thanh_toan = number_format(\App\Models\HoaDonNhapKho::where('tinh_trang', 0)->sum('tong_tien_hoa_don'), 0, ',', '.') . '₫';
                $tong_tien_nhap_kho = number_format(\App\Models\ChiTietHoaDonNhapKho::sum('don_gia_nhap'), 0, ',', '.') . '₫';
                if ($total['tong_doanh_thu_thang'] > 0) {
                    if ($total['tong_doanh_thu_thang_truoc'] === 0) {
                        $total['phan_tram_thang'] = '+100';
                    } else {
                        $phanTram = ($total['tong_doanh_thu_thang'] / $total['tong_doanh_thu_thang_truoc']) * 100;
                        $total['phan_tram_thang'] = $total['tong_doanh_thu_thang'] < $total['tong_doanh_thu_thang_truoc'] ? '-' . $phanTram : '+' . $phanTram;
                    }
                } else {
                    $total['phan_tram_thang'] = '-100';
                }
                if ($total['don_hang_thang_nay'] > 0) {
                    if ($total['don_hang_thang_truoc'] === 0) {
                        $total['phan_tram_don_hang'] = '+100';
                    } else {
                        $phanTram = ($total['don_hang_thang_nay'] / $total['don_hang_thang_truoc']) * 100;
                        $total['phan_tram_don_hang'] = $total['don_hang_thang_nay'] < $total['don_hang_thang_truoc'] ? '-' . $phanTram : '+' . $phanTram;
                    }
                } else {
                    $total['phan_tram_don_hang'] = '-100';
                }
                if ($total['don_hang_hom_nay'] > 0) {
                    if ($total['don_hang_hom_qua'] === 0) {
                        $total['phan_tram_ngay'] = '+100';
                    } else {
                        $phanTram = ($total['don_hang_hom_nay'] / $total['don_hang_hom_qua']) * 100;
                        $total['phan_tram_ngay'] = $total['don_hang_hom_nay'] < $total['don_hang_hom_qua'] ? '-' . $phanTram : '+' . $phanTram;
                    }
                } else {
                    $total['phan_tram_ngay'] = '-100';
                }

            @endphp

            <div class="page-content">
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
                    <div class="col">
                        <div class="card radius-10 border-start border-0 border-3 border-info">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0 text-secondary">Đơn Hàng Tháng Này</p>
                                        <h4 class="my-1 text-info">{{ $total['don_hang_thang_nay'] }} Đơn Hàng</h4>
                                        <p class="mb-0 font-13">{{ $total['phan_tram_don_hang'] }}% So với Tháng Trước</p>
                                    </div>
                                    <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto"><i
                                            class="bx bxs-cart"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card radius-10 border-start border-0 border-3 border-danger">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0 text-secondary">Doanh Thu Tháng Này</p>
                                        <h4 class="my-1 text-danger">{{ $total['tong_doanh_thu_thang'] }}</h4>
                                        <p class="mb-0 font-13">{{ $total['phan_tram_thang'] }}% So Với Tháng Trước</p>
                                    </div>
                                    <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto"><i
                                            class="bx bxs-wallet"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card radius-10 border-start border-0 border-3 border-success">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0 text-secondary">Đơn Hàng Hôm Nay</p>
                                        <h4 class="my-1 text-success">{{ $total['don_hang_hom_nay'] }} Đơn Hàng</h4>
                                        <p class="mb-0 font-13">{{ $total['phan_tram_ngay'] }}% So Với Hôm Qua</p>
                                    </div>
                                    <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                                        <i class="bx bxs-bar-chart-alt-2"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card radius-10 border-start border-0 border-3 border-warning">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0 text-secondary">Tổng Số Khách Hàng</p>
                                        <h4 class="my-1 text-warning">{{ $total['count_member'] }} Khách Hàng</h4>
                                        <p class="mb-0 font-13">{{ $total['count_member_month'] }} Khách Hàng Mới Trong
                                            Tháng</p>
                                    </div>
                                    <div class="widgets-icons-2 rounded-circle bg-gradient-blooker text-white ms-auto"><i
                                            class="bx bxs-group"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--end row-->
                <h6>Thống Kê Nhập kho</h6>
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
                    <div class="col">
                        <div class="card radius-10 overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0 text-secondary font-14">Tổng Số Lượng Nhập Kho</p>
                                        <h5 class="my-0">{{ $nhapKho }} Đơn Hàng</h5>
                                    </div>
                                    <div class="text-primary ms-auto font-30"><i class="bx bx-cart-alt"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-1" id="chart1"><canvas width="158" height="25"
                                    style="display: inline-block; width: 158px; height: 25px; vertical-align: top;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card radius-10 overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0 text-secondary font-14">Tổng Tiền Nhập Kho</p>
                                        <h5 class="my-0">{{ $tong_tien_nhap_kho }}</h5>
                                    </div>
                                    <div class="text-danger ms-auto font-30"><i class="bx bx-dollar"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-1" id="chart2"><canvas width="158" height="25"
                                    style="display: inline-block; width: 158px; height: 25px; vertical-align: top;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card radius-10 overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0 text-secondary font-14">Tổng Nhập Kho Trong Tháng</p>
                                        <h5 class="my-0">{{ $nhapKho_thang_nay }} Đơn Hàng</h5>
                                    </div>
                                    <div class="text-warning ms-auto font-30"><i class="bx bx-beer"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-1" id="chart4"><canvas width="158" height="25"
                                    style="display: inline-block; width: 158px; height: 25px; vertical-align: top;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card radius-10 overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0 text-secondary font-14">Tổng Tiền Chưa Thanh Toán</p>
                                        <h5 class="my-0">{{ $tong_tien_chua_thanh_toan }}</h5>
                                    </div>
                                    <div class="text-info ms-auto font-30"><i class="bx bx-camera-movie"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-1" id="chart5"><canvas width="158" height="25"
                                    style="display: inline-block; width: 158px; height: 25px; vertical-align: top;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-0">Đơn Hàng Gần Đây</h6>
                        </div>
                        <div class="dropdown ms-auto">
                            <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i
                                    class="bx bx-dots-horizontal-rounded font-22 text-option"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:;">Action</a>
                                </li>
                                <li><a class="dropdown-item" href="javascript:;">Another action</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="javascript:;">Something else here</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Tên</th>
                                    <th>Email</th>
                                    <th>Hash Đơn hàng</th>
                                    <th>Status</th>
                                    <th>Tổng Tiền</th>
                                    <th>Ngày Đặt</th>
                                    <th>Trạng Thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list_don_hang as $value)
                                    <tr>
                                        <td>{{ $value->ho_va_ten }}</td>
                                        <td>{{ $value->email }}</td>
                                        <td>{{ $value->hash_don_hang }}</td>
                                        @if ($value->thanh_toan === 0)
                                            <td><span class="badge bg-gradient-quepal text-white shadow-sm w-100">Thanh
                                                    Toán
                                                    Tự Động</span></td>
                                        @elseif ($value->thanh_toan === 1)
                                            <td><span class="badge bg-gradient-blooker text-white shadow-sm w-100">Nhân
                                                    Viên
                                                    Thanh Toán</span>
                                            @elseif ($value->thanh_toan === -1)
                                            <td><span class="badge bg-gradient-bloody text-white shadow-sm w-100">Chưa
                                                    Thanh
                                                    Toán</span></td>
                                        @endif
                                        @php
                                            $tong = number_format($value->tien_hang, 0, ',', '.') . '₫';
                                        @endphp
                                        <td>{{ $tong }}</td>
                                        <td>{{ $value->created_at }}</td>
                                        @if ($value->giao_hang === 2)
                                            <td>
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-gradient-quepal" role="progressbar"
                                                        style="width: 100%"></div>
                                                </div>
                                            </td>
                                        @elseif ($value->giao_hang === 1)
                                            <td>
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-gradient-blooker" role="progressbar"
                                                        style="width: 66%"></div>
                                                </div>
                                            </td>
                                        @elseif ($value->giao_hang === 0)
                                            <td>
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-gradient-bloody" role="progressbar"
                                                        style="width: 33%"></div>
                                                </div>
                                            </td>
                                        @endif

                                    </tr>
                                @endforeach
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <div class="card">
            <div class="card-body">
                <form action="/admin/thong-ke" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-5">
                            <label for="exampleDataList" class="form-label">Từ Ngày</label>
                            <input class="form-control" name="day_begin" type="date" placeholder="Type to search..."
                                value="{{ $tu_ngay }}">
                        </div>
                        <div class="col-md-5">
                            <label for="exampleDataList" class="form-label">Đến Ngày</label>
                            <input class="form-control" name="day_end" type="date" placeholder="Type to search..."
                                value="{{ $den_ngay }}">
                        </div>
                        <div class="col-md-2 mt-2">
                            <label for="exampleDataList" class="form-label"></label>
                            <button class="btn btn-success" type="submit" style="width: 100%"><i
                                    class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Bảng Thống Kê
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Tên Sản Phẩm</th>
                                    <th>Số Lượng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $key => $value)
                                    <tr>
                                        <th class="text-center align-middle">{{ $key + 1 }}</th>
                                        <td class="align-middle">{{ $value->ten_san_pham }}</td>
                                        <td class="text-center align-middle">{{ $value->so_luong }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Chart Sản Phẩm Đã Bán Ra
                </div>
                <div class="card-body">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@section('js')
    <script>
        @if (count($errors) > 0)
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('myChart');
        var lables = {!! json_encode($array_san_pham) !!};
        var datas = {!! json_encode($array_so_luong) !!};
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: lables,
                datasets: [{
                    label: 'Sản Phẩm',
                    data: datas,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
