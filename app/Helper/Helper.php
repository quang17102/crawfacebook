<?php

namespace App\Helper;

use DateTime;

class Helper
{
    public static function getStatusContract($endtime = '')
    {
        $endtime = new DateTime($endtime);
        $now = new DateTime(now()->format('Y-m-d'));

        $interval = $endtime->diff($now)->days;
        $renderStatus  = '';
        switch (true) {
            case $interval > 0 && $interval <= 30:
                $renderStatus = '<span class="btn btn-warning">Hết hạn trong ' . $interval . ' ngày</span>';
                break;
            case $interval < 0:
                $renderStatus = '<span class="btn btn-danger">Hết hạn</span>';
                break;
            case $interval > 30:
                $renderStatus = '<span class="btn btn-success">Còn hạn</span>';
                break;
            default:
                break;
        }

        return $renderStatus;
    }
}

// <span class="btn btn-success">Còn hạn</span>
// <span class="btn btn-warning">Hết hạn trong ngày</span>
// <span class="btn btn-danger">Hết hạn</span>