<?php

// config/auth.php

return [
    'guards' => [
        'employee' => [
        'driver' => 'session',
        'provider' => 'employees',  // Tên của provider bạn sẽ định nghĩa
    ],
    ],

   'providers' => [
    // Các providers khác...
    
    'employees' => [
        'driver' => 'eloquent',
        'model' => App\Models\Employee::class, // Thay 'Employee' bằng model của bạn
    ],
],
];


