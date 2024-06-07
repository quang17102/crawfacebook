<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>KẾ HOẠCH DỊCH VỤ</title>
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
                    <p style="font-size: 14px;font-weight:bold;color:rgb(15, 22, 168)">KẾ HOẠCH CÔNG VIỆC</p>
                    <p style="font-weight:bold;color:orange">Work Plan</p>
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
    <div class="col10">
        <div class="col8">
            <p style="font-weight:bold;text-decoration: underline">KẾ HOẠCH THỰC HIỆN CÔNG VIỆC</p>
            @if (!empty($data['tasks']))
                @foreach ($data['tasks'] as $key => $info)
                    @if (!empty($info['details']))
                        <h3 style="text-decoration: underline; color:orange">{{ $info['type']['name'] }}</h3>
                        <br />
                        @foreach ($info['details'] as $task)
                            @php
                                $date = explode('-', $task['plan_date']);
                            @endphp
                            @if ($date[0] == $data['year'] && $date[1] == $data['month'])
                                <table class="tbl-plan" cellspacing="0">
                                    <tbody>
                                        <tr>
                                            @if (!empty($info['type']['image']))
                                                <td rowspan="6"><img src="{{ public_path($info['type']['image']) }}"
                                                        width="50px" height="50px" alt="">
                                                </td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td style="border-top: 0.5px solid black;border-left: 0.5px solid black;width:40px">
                                                <img src="{{ public_path('images/lich.png') }}" width="20px"
                                                    height="20px" alt="">
                                            </td>
                                            <td style="border-top: 0.5px solid black;width:40px">
                                                Lịch
                                            </td>
                                            <td style="font-weight:bold;border: 0.5px solid black;border-bottom: none;">
                                                Ngày {{ $task['plan_date'] ?? '' }} Giờ vào:
                                                {{ $task['time_in'] ?? '' }} Giờ
                                                ra: {{ $task['time_out'] ?? '' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-left: 0.5px solid black;border-bottom: none;">
                                                <img src="{{ public_path('images/staff.png') }}" width="20px"
                                                    height="20px" alt="" />
                                            </td>
                                            <td>
                                                Kỹ thuật
                                            </td>
                                            <td style="border: 0.5px solid black;border-bottom: none;">
                                                @if (!empty($task['task_staffs']))
                                                    @foreach ($task['task_staffs'] as $staff)
                                                        <p>{{ $staff['user']['staff']['name'] }} -
                                                            {{ $staff['user']['staff']['identification'] }} -
                                                            {{ $staff['user']['staff']['tel'] }}</p>
                                                    @endforeach
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-left: 0.5px solid black;"><img
                                                    src="{{ public_path('images/item.png') }}" width="20px"
                                                    height="20px" alt=""></td>
                                            <td>
                                                Thiết bị
                                            </td>
                                            <td style="border: 0.5px solid black;border-bottom: none;"></td>
                                        </tr>
                                        <tr>
                                            <td style="border-left: 0.5px solid black;"><img
                                                    src="{{ public_path('images/target.png') }}" width="20px"
                                                    height="20px" alt=""></td>
                                            <td>
                                                Đối tượng
                                            </td>
                                            <td style="border: 0.5px solid black;border-bottom: none;">
                                                @if (!empty($task['task_maps']))
                                                    <p>
                                                        @php
                                                            $targets = [];
                                                            foreach ($task['task_maps'] as $map) {
                                                                if (!in_array($map['target'], $targets)) {
                                                                    array_push($targets, $map['target']);
                                                                }
                                                            }
                                                        @endphp
                                                        {{ implode(', ', $targets) }}
                                                    </p>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-bottom: 1px solid black;border-left: 0.5px solid black;">
                                                <img src="{{ public_path('images/chem.png') }}" width="20px"
                                                    height="20px" alt="">
                                            </td>
                                            <td style="border-bottom: 1px solid black;">
                                                Thuốc
                                            </td>
                                            <td style="border: 0.5px solid black;"></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <br />
                            @endif
                        @endforeach
                        <br /><br /><br />
                    @endif
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
