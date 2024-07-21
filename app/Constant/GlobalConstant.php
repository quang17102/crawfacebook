<?php

namespace App\Constant;

class GlobalConstant
{
    public const ACTIVE = 1;
    public const IN_ACTIVE = 0;

    public const TYPE_SCAN = 0;
    public const TYPE_FOLLOW = 1;
    public const TYPE_RUNNING = 2;

    public const ROLE_USER = 0;
    public const ROLE_ADMIN = 1;

    public const IS_OFF = 0;
    public const IS_ON = 1;
    public const IS_RESET = 2;

    public const STATUS_OK = 0;
    public const STATUS_ERROR = 1;

    public const STATUS_STOP = 0;
    public const STATUS_RUNNING = 1;

    public const UTC_HOUR = 7;

    public const LINK_TYPE = [
        '0', '1', '2'
    ];

    public const LINK_STATUS = [
        '0', '1', '2'
    ];

    public const ROLE_ALL = [
        0 => 'Số điện thoại: hiện cột SDT trong bảng comments',
        1 => 'Count: hiện các cột count trong bảng link quét và link theo dõi',
        2 => 'Link theo dõi : hiện bảng link theo dõi',
        3 => 'Cảm xúc: hiện bảng cảm xúc',
        4 => 'Hiển thị UID',
        5 => 'Hiển thị Data cuối',
        6 => 'Hiển thị cốt Ads',
    ];
    public const ROLE_PHONE = 0;
    public const ROLE_COUNT = 1;
    public const ROLE_FOLLOW = 2;
    public const ROLE_REACTION = 3;

    public const TYPE_COMMENT = 0;
    public const TYPE_DATA = 1;
    public const TYPE_REACTION = 2;

    public const LENGTH_PHONE = 25;

    public const LIMIT_LINK_HISTORY = 10;

    public const LIMIT_COMMENT = 20000;
}
