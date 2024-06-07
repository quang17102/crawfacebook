<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>BÁO CÁO CÔNG VIỆC</title>
    <style>
        .col9 {
            width: 90%;
            float: left;
        }

        .col8 {
            width: 80%;
            float: left;
        }

        .col7 {
            width: 70%;
            float: left;
        }

        .col6 {
            width: 60%;
            float: left;
        }

        .col5 {
            width: 50%;
            float: left;
        }

        .col4 {
            width: 40%;
            float: left;
        }

        .col3 {
            width: 30%;
            float: left;
        }

        .col2 {
            width: 20%;
            float: left;
        }

        .col1 {
            width: 10%;
            float: left;
        }

        .col10 {
            width: 100%;
            float: left;
        }

        header {
            border-bottom: 1px solid rgb(15, 22, 168);
        }

        body {
            font-size: 8px;
            font-family: DejaVu Sans, sans-serif;
            /* font-family:'Times New Roman', Times, serif; */
        }

        .tbl-plan {

            width: 100%;
        }

        .tbl-plan td,
        .tbl-plan th {
            padding: 2px 10px 2px 5px;
        }

        .tbl-plan td {
            border: 0.5px solid black;
            border-bottom: none;
            border-right: none;
        }

        .tbl-map td,
        .tbl-map th {
            border: 0.5px solid black;
        }
    </style>
</head>

<body>
    <header>
        <table style="width: 100%">
            <tbody>
                <tr style="line-height: 4px;">
                    <td>
                        <img width="200px" height="100px" src="{{ public_path('images/logo.png') }}" alt="" />
                    </td>
                    <td>
                        <p style="font-size: 14px;font-weight:bold">CÔNG TY TNHH DỊCH VỤ PESTKIL VIỆT NAM</p>
                        <p style="font-size: 14px;font-weight:bold">CHI NHÁNH: HÀ NỘI</p>
                        <p>98 Nguyễn Khiêm Ích – Trâu Quỳ - Gia Lâm – TP Hà Nội</p>
                        <p>MST: 0106021899</p>
                        <p>Số tài khoản: 1015966850-NN Ngoại thương Việt Nam (Vietcombank)</p>

                    </td>
                    <td style="align-items:center">
                        <img width="50px" height="40px" src="{{ public_path('images/phone.png') }}" alt="" />
                    </td>
                    <td style="align-items:center">
                        0986 112 486
                    </td>
                </tr>
            </tbody>
        </table>
    </header>
    <table style="width: 100%;height:200px;">
        <tbody>
            <tr>
                <td>
                    @if (!empty($data['tasks'][0]['type']['parent']['image']))
                        <img height="100px" src="{{ public_path($data['tasks'][0]['type']['parent']['image']) }}"
                            alt="" />
                    @endif
                </td>
                <td style="text-align: center;line-height:16px">
                    <p style="font-size: 14px;font-weight:bold;color:rgb(15, 22, 168)">BÁO CÁO CÔNG VIỆC</p>
                    <p style="font-weight:bold;color:orange">Work Report</p>
                </td>
            </tr>
            <tr>
                <td style="width:100px">Khách hàng</td>
                <td style="font-weight:bold;width:400px">{{ $data['customer']['name'] ?? '' }}</td>
                <td rowspan="8" style="width:100px">
                    <table style="width:200px;margin-bottom:2px;color:white;background-color:#7FA8AE">
                        <tbody>
                            <tr>
                                <td>
                                    <img src="{{ public_path('images/idcus.png') }}" width="20px" height="20px"
                                        alt="">
                                </td>
                                <td style="">
                                    &emsp;Mã khách hàng <br />&emsp;PD {{ $data['customer']['id'] ?? '' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table style="width:200px;margin-bottom:2px;color:white;background-color:#7FA8AE">
                        <tbody>
                            <tr>
                                <td>
                                    <img src="{{ public_path('images/nocontract.png') }}" width="20px" height="20px"
                                        alt="">
                                </td>
                                <td>
                                    Hợp đồng số <br />PD {{ $data['id'] ?? '' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table style="width:200px;color:white;background-color:#7FA8AE">
                        <tbody>
                            <tr>
                                <td>
                                    <img src="{{ public_path('images/calendar2.png') }}" width="20px" height="20px"
                                        alt="">
                                </td>
                                <td>
                                    Thông tin <br />Tháng {{ $data['month'] }}/{{ $data['year'] }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td>Địa chỉ</td>
                <td style="font-weight:bold;" style="color:orange;font-style:italic">
                    {{ $data['branch']['address'] ?? '' }}</td>
            </tr>
            <tr>
                <td>Điện thoại</td>
                <td style="font-weight:bold;">{{ $data['branch']['tel'] ?? '' }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td style="font-weight:bold;">{{ $data['branch']['email'] ?? '' }}</td>
            </tr>
            <tr>
                <td>Mã số thuế</td>
                <td style="font-weight:bold;">{{ $data['branch']['name'] ?? '' }}</td>
            </tr>
            <tr>
                <td>Địa chỉ sử dụng dịch vụ</td>
                <td style="font-weight:bold;">{{ $data['branch']['address'] ?? '' }}</td>
            </tr>
            <tr>
                <td>Dịch vụ</td>
                <td style="font-weight:bold;">{{ $data['branch']['name'] ?? '' }}</td>
            </tr>
            <tr>
                <td>Số địa điểm</td>
                <td style="font-weight:bold;">{{ $data['branch']['name'] ?? '' }}</td>
            </tr>
        </tbody>
    </table>
    <br>
    <div class="col10">
        <div class="col8">
            @if (!empty($data['tasks']))
                @foreach ($data['tasks'] as $key => $info)
                    <table class="tbl-plan" cellspacing="0">
                        <tbody>
                            <tr>
                                <td style="width:50px;">
                                    @if (!empty($info['type']['image']))
                                        <img src="{{ public_path($info['type']['image']) }}" width="50px"
                                            height="50px" alt="">
                                    @endif
                                </td>
                                <td colspan="2" style="border-right: 0.5px solid black;">
                                    <h3 style="text-decoration: underline; color:orange">{{ $info['type']['name'] }}
                                    </h3>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:center;">
                                    <img src="{{ public_path('images/lich.png') }}" width="35px" height="35px"
                                        alt="">
                                </td>
                                <td style="">
                                    Lịch
                                </td>
                                <td style="border-right: 0.5px solid black;">
                                    Kỳ báo cáo:.... Thời gian: {{ $data['month'] . '/' . $data['year'] }}
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:center;border-left: 0.5px solid black;border-bottom: none;">
                                    <img src="{{ public_path('images/chart.png') }}" width="35px" height="35px"
                                        alt="" />
                                </td>
                                <td>
                                    Biểu đồ xu hướng
                                </td>
                                <td
                                    style="border: 0.5px solid black;border-bottom: none;border-right: 0.5px solid black;">
                                    @if (!empty($data['image_trend_charts'][$info['id']]))
                                        <img src="{{ public_path($data['image_trend_charts'][$info['id']]) }}"
                                            alt="" />
                                    @endif
                                    @if ($data['display'] && !empty($data['image_annual_charts'][$info['id']]))
                                        <img src="{{ public_path($data['image_annual_charts'][$info['id']]) }}"
                                            alt="" />
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:center;border-left: 0.5px solid black;border-bottom: none;">
                                    <img src="{{ public_path('images/detail.png') }}" width="35px" height="35px"
                                        alt="" />
                                </td>
                                <td style="border-bottom: none;">
                                    Chi tiết
                                </td>
                                <td style="border: 0.5px solid black;border-bottom: none;">
                                    @if (!empty($info['details']))
                                        @if (!empty($info['details']))
                                            @foreach ($info['details'] as $detail)
                                                @foreach ($detail['task_maps'] as $key => $taskMaps)
                                                    <table class="tbl-map" style="width:100%" cellspacing="0">
                                                        <tbody>
                                                            <tr>
                                                                <th>Mã sơ đồ</th>
                                                                <th>Vị trí</th>
                                                                <th>Chỉ số</th>
                                                                <th>KPI</th>
                                                                <th>Kết quả</th>
                                                                <th>Ảnh KT</th>
                                                            </tr>
                                                            @foreach ($taskMaps as $taskMap)
                                                                <tr>
                                                                    <td style="width: 60px">
                                                                        {{ $taskMap['map']['code'] ?? '' }}
                                                                    </td>
                                                                    <td>{{ $taskMap['map']['position'] ?? '' }} </td>
                                                                    <td>{{ $taskMap['unit'] ?? '' }} </td>
                                                                    <td>{{ $taskMap['kpi'] ?? '' }} </td>
                                                                    <td>{{ $taskMap['result'] ?? '' }} </td>
                                                                    <td>
                                                                        @if (!empty($taskMap['image']))
                                                                            <img src="{{ public_path($taskMap['image']) }}"
                                                                                width="15px" height="15px"
                                                                                alt="abc" />
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                    @php
                                                        $keyImage = ($info['id'] ?? '') . $key;
                                                    @endphp
                                                    @if (!empty($data['image_charts'][$keyImage]))
                                                        <img src="{{ $data['image_charts'][$keyImage] }}"
                                                            alt="" style="margin-bottom: 20px" />
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align:left;">
                                    Nhận xét
                                </td>
                                <td style="border-right: 0.5px solid black;">

                                </td>
                            </tr>
                            <tr style="">
                                <td colspan="2" style="text-align:left;border-bottom: 0.5px solid black;">
                                    Đề xuất
                                </td>
                                <td style="border-bottom: 0.5px solid black;border-right: 0.5px solid black;">

                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <br /><br /><br />
                @endforeach
            @endif
        </div>
        <div class="col2" style="margin-left:10px">
            <p style="font-style: italic;">Để biết thêm thông tin và hỗ trợ khách hàng, vui lòng liên hệ </p>
            <p style="font-weight:bold;text-decoration: underline">THÔNG TIN KHÁCH HÀNG</p>
            <p>Vui lòng truy cập địa chỉ <a href="https://pestkil.com.vn/">https://pestkil.com.vn/</a> và đăng
                nhập để quản lý và theo dõi trực tuyến</p>
            <p style="font-weight:bold;text-decoration: underline">THÔNG TIN LIÊN HỆ</p>
            <p style="font-weight:bold;">Trung tâm CSKH PESTKIL VIỆT NAM</p>
            <table>
                <tbody>
                    <tr>
                        <td><img src="{{ public_path('images/building.png') }}" width="20px" height="20px"
                                alt="" /></td>
                        <td>T118 Lô đất L2 khu 31 Ha, Trâu Quỳ - Gia Lâm - HN</td>
                    </tr>
                    <tr>
                        <td><img src="{{ public_path('images/tel.png') }}" width="20px" height="20px"
                                alt="" /></td>
                        <td>0961063486 – 0838 094 888</td>
                    </tr>
                    <tr>
                        <td><img src="{{ public_path('images/email.png') }}" width="20px" height="20px"
                                alt="" /></td>
                        <td>Cskh@pestkil.com.vn </td>
                    </tr>
                    <tr>
                        <td><img src="{{ public_path('images/fb.png') }}" width="20px" height="20px"
                                alt="" /></td>
                        <td>Facebook: </td>
                    </tr>
                    <tr>
                        <td><img src="{{ public_path('images/down.png') }}" width="20px" height="20px"
                                alt="" /></td>
                        <td>Tải ứng dụng CSKH trên IOS và Android</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <br />
    <div class="col10">
        <div class="col7">
            &emsp;
        </div>
        <div class="col3" style="text-align: right">
            <p> <span style="font-weight:bold;">Lập bởi </span>Công ty TNHH dịch vụ Pestkil Việt Nam</p>
            <div style="font-weight:bold;">{{ $data['creator']['staff']['name'] ?? '' }}</div>
        </div>
    </div>
</body>

</html>
