<?php

return [

    'title' => 'Dashboard IoT',
    'title_prefix' => '',
    'title_postfix' => '',

    'use_ico_only' => false,
    'use_full_favicon' => false,

    'google_fonts' => [
        'allowed' => true,
    ],

    'logo' => '<b>IoT</b>Dashboard',
    'logo_img' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_alt' => 'IoT Logo',

    'auth_logo' => [
        'enabled' => false,
    ],

    'preloader' => [
        'enabled' => true,
        'mode' => 'fullscreen',
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'Loading',
            'effect' => 'animation__shake',
            'width' => 60,
            'height' => 60,
        ],
    ],

    'usermenu_enabled' => true,

    // =======================
    // LAYOUT
    // =======================
    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => true,
    'layout_fixed_navbar' => true,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    // =======================
    // STYLE
    // =======================
    'classes_body' => '',
    'classes_brand' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_topnav' => 'navbar-white navbar-light',

    // =======================
    // SIDEBAR
    // =======================
    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_nav_accordion' => true,

    // =======================
    // URL
    // =======================
    'use_route_url' => false,
    'dashboard_url' => '/dashboard',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'profile_url' => false,

    // =======================
    // MENU (SUDAH DIBERSIHKAN)
    // =======================
    'menu' => [

        ['header' => 'MAIN MENU'],

        [
            'text' => 'Dashboard',
            'url'  => '/dashboard',
            'icon' => 'fas fa-tachometer-alt',
        ],

        [
            'text' => 'Kontrol Relay',
            'url'  => '/relay',
            'icon' => 'fas fa-toggle-on',
        ],

        [
        'text' => 'Histori Sensor',
        'url'  => 'histori',
        'icon' => 'fas fa-history',
    ],
    
    ],

    // =======================
    // MENU FILTERS
    // =======================
    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    // =======================
    // PLUGINS (MINIMAL)
    // =======================
    'plugins' => [
        'Chartjs' => [
            'active' => false,
        ],
    ],

    'livewire' => false,
];
