<?php


use App\Controllers\IndexController;



return [
    'action' => [
        'index'                    => 'Hiện trang',
        'login'                    => 'Đăng nhập',
        'logout'                   => 'Thoát',
        'list'                     => 'Lấy danh sách nội dung cho hiện trong bảng',
        'get'                      => 'Lấy nội dung để cập nhật, xóa',
        'update'                   => 'Cập nhật nội dung',
        'delete'                   => 'Xóa nội dung',
        'history'                  => 'Xem lịch sử',
        'resetPass'                => 'Nhập lại mật khẩu',
        'import'                   => 'Nhập file Excel',
        'export'                   => 'Xuất Excel',
        'store'                    => 'Lấy Store',
        'copy'                     => 'Tạo bản sao',
        'report'                   => 'Báo cáo thông tin',
        'uploadImage'              => 'Tải ảnh lên',
        'chart'                    => 'Xem chart',
        'todb'                     => 'Sync data từ API vào DB',
        'picture'                  => 'Xem ảnh',
    ],
    'controller' => [
        IndexController::class              => 'Đăng nhập, Trang chủ',

    ],
    'icon' => [
        IndexController::class              => '',

    ],
    'group' => [
        'Cấu hình chung|default' => [
            IndexController::class,

        ],
        'Quản trị hệ thống' => [



        ],
        'Cấu hình khảo sát' => [

        ],
    ]
];
