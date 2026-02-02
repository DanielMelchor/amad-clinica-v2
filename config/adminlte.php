<?php
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Crypt;
    $fecha_inicio_anio = Carbon::now()->startOfYear()->format('Y-m-d');
    $fecha_inicial     = Carbon::now()->startOfMonth()->format('Y-m-d');
    $fecha_final       = Carbon::now()->endOfMonth()->format('Y-m-d');

    $fecha_inicio      = Carbon::now()->startOfMonth()->format('Y-m-d');
    $fecha_fin         = Carbon::now()->endOfMonth()->format('Y-m-d');

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'title' => 'Grupo @mad',
    'title_prefix' => '@mad |',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Google Fonts
    |--------------------------------------------------------------------------
    |
    | Here you can allow or not the use of external google fonts. Disabling the
    | google fonts may be useful if your admin panel internet access is
    | restricted somehow.
    |
    | For detailed instructions you can look the google fonts section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'google_fonts' => [
        'allowed' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => '<b>Grupo</b>@mad',
    // 'logo_img' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
    'logo_img' => 'img/logo_trial_short.png',
    // 'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_class' => 'brand-image-xs img-circle elevation-4',
    // 'logo_img_xl' => null,
    'logo_img_xl' => 'img/logo_trial_large.png',
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => '',

    /*
    |--------------------------------------------------------------------------
    | Authentication Logo
    |--------------------------------------------------------------------------
    |
    | Here you can setup an alternative logo to use on your login and register
    | screens. When disabled, the admin panel logo will be used instead.
    |
    | For detailed instructions you can look the auth logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'auth_logo' => [
        'enabled' => false,
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'Auth Logo',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    |
    | Here you can change the preloader animation configuration. Currently, two
    | modes are supported: 'fullscreen' for a fullscreen preloader animation
    | and 'cwrapper' to attach the preloader animation into the content-wrapper
    | element and avoid overlapping it with the sidebars and the top navbar.
    |
    | For detailed instructions you can look the preloader section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'preloader' => [
        'enabled' => true,
        'mode' => 'fullscreen',
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'AdminLTE Preloader Image',
            'effect' => 'animation__shake',
            'width' => 60,
            'height' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => true,
    'usermenu_header_class' => 'bg-navy',
    'usermenu_image' => true,
    'usermenu_desc' => true,
    'usermenu_profile_url' => true,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => true,
    'layout_fixed_navbar' => false,
    'layout_fixed_footer' => false,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => 'card-outline card-navy',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => 'layout-footer-fixed',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-navy',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-light-warning elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => true,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => true,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => 'home',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => true,
    'disable_darkmode_routes' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Asset Bundling
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Asset Bundling option for the admin panel.
    | Currently, the next modes are supported: 'mix', 'vite' and 'vite_js_only'.
    | When using 'vite_js_only', it's expected that your CSS is imported using
    | JavaScript. Typically, in your application's 'resources/js/app.js' file.
    | If you are not using any of these, leave it as 'false'.
    |
    | For detailed instructions you can look the asset bundling section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'laravel_asset_bundling' => false,
    'laravel_css_path' => 'css/app.css',
    'laravel_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'menu' => [
        [
            'text' => 'Agenda',
            'route'  => 'nueva_agenda',
            'icon' => 'fas fa-calendar-alt',
            'topnav_right' => true,
            'can'  => 'ver-agenda'
        ],
        [
            'text' => 'Pacientes',
            'route'  => 'pacientes',
            'icon' => 'fas fa-procedures',
            'topnav_right' => true,
            'can'  => 'ver-pacientes'
        ],
        [
            'text'   => 'Admisiones',
            'route'  => 'admisiones',
            'icon'   => 'fas fa-hand-holding-medical',
            'topnav_right' => true,
            'can'    => 'administrar-procesos-admisiones',
        ],
        [
            'text'  => 'Facturación',
            'route' => 'documentos_listado',
            'icon'  => 'fas fa-file-invoice',
            'topnav_right' => true,
            'can'   => 'administrar-procesos-facturacion-facturacion'
        ],
        [
            'text'         => 'Graficos',
            'route'        => ['graficas_index', ['fecha_inicial' => $fecha_inicial, 'fecha_final' => $fecha_final]],
            'icon'         => 'fas fa-chart-pie',
            'topnav_right' => true,
            'can'          => 'ver-graficos'
        ],
        [
            'text'    => 'Administrador',
            'icon'    => 'fas fa-user-cog',
            'topnav_right' => false,
            'can'     => 'Super Admin',
            'submenu' => [
                [
                    'text'  => 'Permisos',
                    'route' => 'permiso_listado',
                    'icon'  => 'fas fa-shield-alt',
                    'can'   => 'Super Admin'
                ],
                [
                    'text'  => 'Roles',
                    'route' => 'roles_listado',
                    'icon'  => 'fas fa-user-shield',
                    'can'   => 'Super Admin'
                ],
                [
                    'text'  => 'Usuarios',
                    'route' => 'usuario_listado',
                    'icon'  => 'fas fa-user',
                    'can'   => 'Administrador'
                ],
                [
                    'text' => 'fullcalendar',
                    'route'  => 'fullcalendar',
                    'icon' => 'fas fa-calendar-alt'
                ],
            ],
        ],
        [
            'text'    => 'General',
            'icon'    => 'fas fa-h-square',
            'topnav_right' => false,
            'can'     => 'ver-generales',
            'submenu' => [
                // [
                //     'text' => 'Empresas',
                //     'route'  => 'empresas',
                //     'icon' => 'fas fa-landmark',
                //     'can'   => 'ver-empresas'
                // ],
                [
                    'text' => 'Correlativos',
                    'route'  => 'correlativos',
                    'icon' => 'fas fa-hospital',
                    'can'   => 'ver-correlativos'
                ],
                [
                    'text'    => 'Ubicación',
                    'icon'    => 'fas fa-globe-americas',
                    'topnav_right' => false,
                    // 'can'     => 'Administrador',
                    'submenu' => [
                        [
                            'text' => 'Paises',
                            'route'  => 'pais',
                            'can'  => 'ver-pais'
                        ],
                        [
                            'text' => 'Departamentos',
                            'route'  => 'departamentos',
                            'can'  => 'ver-departamento'
                        ],
                        [
                            'text' => 'Municipios',
                            'route'  => 'municipios',
                            'can'  => 'ver-municipio'
                        ],
                    ]
                ],
                [
                    'text' => 'Salas',
                    'route'  => 'salas',
                    'icon' => 'fas fa-procedures',
                    'can'   => 'ver-salas'
                ],
            ],
        ],
        [
            'text'    => 'Inventario',
            'icon'    => 'fas fa-warehouse',
            'topnav_right' => false,
            'can'     => 'ver-inventarios',
            'submenu' => [
                [
                    'text'    => 'Catalogos',
                    'icon'    => 'fas fa-globe-americas',
                    'topnav_right' => false,
                    'can'  => 'ver-catalogo-inventarios',
                    'submenu' => [
                        [
                            'text' => 'Bodegas',
                            'route'  => 'bodegas',
                            'icon' => 'fas fa-warehouse',
                            'can'   => 'ver-catalogo-inventarios-bodegas'
                        ],
                        [
                            'text'   => 'Clasifiación de Insumos',
                            'route'  => 'inv_clasificacion',
                            'icon'   => 'fas fa-pills',
                            'can'    => 'ver-catalogo-inventarios-clasificaciones',
                        ],
                        [
                            'text'   => 'Familia de Insumos',
                            'route'  => 'inv_familias',
                            'icon'   => 'fas fa-pills',
                            'can'    => 'ver-catalogo-inventarios-familias',
                        ],
                        [
                            'text'   => 'Productos',
                            'route'  => 'productos',
                            'icon'   => 'fas fa-boxes',
                            'can'    => 'ver-catalogo-inventarios-productos',
                        ],
                        [
                            'text'   => 'Proveedores',
                            'route'  => 'proveedores',
                            'icon'   => 'fas fa-truck-moving',
                            'can'    => 'ver-catalogo-inventarios-proveedores',
                        ],
                        [
                            'text' => 'Transacciones',
                            'route'  => 'invtransacciones',
                            'icon' => 'fas fa-landmark',
                            'can'    => 'ver-catalogo-inventarios-transacciones',
                        ],
                        [
                            'text' => 'Unidad Medida',
                            'route'  => 'unidadmedidas',
                            'icon' => 'fas fa-balance-scale-right',
                            'can'   => 'ver-catalogo-inventarios-unidades'
                        ],
                    ]
                ],
                [
                    'text' => 'Reportes',
                    'icon' => 'fas fa-file-pdf',
                    'topnav_right' => false,
                    'can'  => 'ver-reportes-inventarios',
                    'submenu' => [
                        [
                            'text'   => 'Disponible',
                            'route'  => 'rpt_disponible',
                            'can'    => 'ver-reporte-inventario-disponibles'
                        ],
                        [
                            'text'   => 'Kardex',
                            'route'  => 'rpt_kardex_articulos',
                            'route'      => ['rpt_kardex_articulos', ['producto_id' => 0,
                                              'fecha_inicial'             => $fecha_inicio
                                              ]
                                ],
                            'can'    => 'ver-reporte-inventario-kardex'
                        ],
                        [
                            'text'   => 'Movimiento de Articulos',
                            'route'  => ['rpt_movimiento_articulos', ['fecha_inicial' => $fecha_inicial,
                                                                      'fecha_final'   => $fecha_final
                                                                      ],
                                ],
                            'can'    => 'ver-reporte-inventario-movimientos'
                        ],
                        
                    ],
                ],
                [
                    'text'  => 'Ajustes de Inventario',
                    'route' => 'lista_ajustes',
                    'icon'  => 'fas fa-sliders-h',
                    'can'   => 'administrar-procesos-inventario-ajuste',
                ],
                [
                    'text'  => 'Compras',
                    'route' => 'lista_compras',
                    'icon'  => 'fas fa-shopping-cart',
                    'can'   => 'administrar-procesos-inventario-compra'
                ],
            ]
        ],
        [
            'text'    => 'Facturación',
            'icon'    => 'fas fa-file-alt',
            'topnav_right' => false,
            'can'  => 'ver-facturacion',
            'submenu' => [
                [
                    'text'    => 'Catalogos',
                    'icon'    => 'fas fa-globe-americas',
                    'topnav_right' => false,
                    'can'  => 'ver-catalogo-facturacion',
                    'submenu' => [
                        [
                            'text'   => 'Aseguradoras',
                            'route'  => 'aseguradoras',
                            'icon'   => 'fas fa-house-damage',
                            'can'    => 'ver-catalogo-facturacion-aseguradoras',
                        ],
                        [
                            'text'   => 'Bancos',
                            'route'  => 'bancos',
                            'icon'   => 'fas fa-piggy-bank',
                            'can'    => 'ver-catalogo-facturacion-bancos',
                        ],
                        [
                            'text' => 'Cajas',
                            'route'  => 'cajas',
                            'icon' => 'fas fa-cash-register',
                            'can'   => 'ver-catalogo-facturacion-cajas'
                        ],
                        [
                            'text'   => 'Motivos Anulación',
                            'route'  => 'motivosAnulacion',
                            'icon'   => 'fas fa-ban',
                            'can'    => 'ver-catalogo-facturacion-motivo-anulacion',
                        ],
                        [
                            'text'   => 'Motivos Rechazo',
                            'route'  => 'motivoRechazos',
                            'icon'   => 'fas fa-handshake-alt-slash',
                            'can'    => 'ver-catalogo-facturacion-motivo-rechazo',
                        ],
                        [
                            'text' => 'Tipos de Documento',
                            'route'  => 'tipodocumentos',
                            'icon' => 'fas fa-file-signature',
                            'can'   => 'ver-catalogo-facturacion-tipo_documentos'
                        ],
                    ],
                ],
                [
                    'text'  => 'Cortes',
                    'route' => 'listado_cortes',
                    'icon'  => 'fas fa-archive',
                    'can'   => 'administrar-procesos-facturacion-corte',
                ],
                [
                    'text'  => 'Notas de Crédito',
                    'route' => 'nc_listado',
                    'icon'  => 'fas fa-file-invoice',
                    'can'   => 'administrar-procesos-facturacion-nota-credito',
                ],
                [
                    'text'  => 'Notas de Débito',
                    'route' => 'nd_listado',
                    'icon'  => 'fas fa-file-invoice',
                    'can'   => 'administrar-procesos-facturacion-nota-debito',
                ],
                [
                    'text'  => 'Recibos',
                    'route' => 'recibos_listado',
                    'icon'  => 'fas fa-credit-card',
                    'can'   => 'administrar-procesos-facturacion-recibo',
                ],
            ]
        ],
        [
            'text'    => 'Medico',
            'icon'    => 'fas fa-hand-holding-medical',
            'topnav_right' => false,
            'can'  => 'ver-medicos',
            'submenu' => [
                [
                    'text'    => 'Catalogos',
                    'icon'    => 'fas fa-globe-americas',
                    'topnav_right' => false,
                    'can'  => 'ver-catalogo-medicos',
                    'submenu' => [
                        [
                            'text' => 'Dosis',
                            'route'  => 'dosis',
                            'icon' => 'fas fa-heartbeat',
                            'can'   => 'ver-catalogo-dosis'
                        ],
                        [
                            'text' => 'Especialidad Medica',
                            'route'  => 'especialidades',
                            'icon' => 'fas fa-user-nurse',
                            'can'   => 'ver-catalogo-especialidad'
                        ],
                        [
                            'text' => 'Líneas Medicas',
                            'route'  => 'lineas_medicas',
                            'icon' => 'fas fa-heartbeat',
                            'can'   => 'ver-catalogo-lineas'
                        ],
                        [
                            'text' => 'Medicos',
                            'route'  => 'medicos',
                            'icon' => 'fas fa-user-md',
                            'can'   => 'ver-catalogo-medicos-medicos'
                        ],
                        [
                            'text' => 'Partes del cuerpo',
                            'route'  => 'partes_cuerpo',
                            'icon' => 'fas fa-user-injured',
                            'can'   => 'ver-catalogo-partes'
                        ],
                    ],
                ],
            ]
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'assets/datatables/1.13.7/js/jquery.dataTables.min.js',
                ],
                // [
                //     'type' => 'js',
                //     'asset' => true,
                //     'location' => 'assets/datatables/1.13.7/js/jquery-3.7.0.js',
                // ],
                // [
                //     'type' => 'js',
                //     'asset' => true,
                //     'location' => 'assets/datatables/1.13.7/js/jquery.dataTables.min.js',
                // ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'assets/datatables/1.13.7/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'assets/datatables/1.13.7/js/dataTables.buttons.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'assets/datatables/1.13.7/js/buttons.bootstrap4.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'assets/datatables/1.13.7/js/jszip.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'assets/datatables/1.13.7/js/pdfmake.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'assets/datatables/1.13.7/js/vfs_fonts.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'assets/datatables/1.13.7/js/buttons.html5.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'assets/datatables/1.13.7/js/buttons.print.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'assets/datatables/1.13.7/js/buttons.colVis.min.js',
                ],
                // [
                //     'type' => 'css',
                //     'asset' => true,
                //     'location' => 'assets/datatables/1.13.7/css/jquery.dataTables.min.css',
                // ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'assets/datatables/1.13.7/dataTables.bootstrap4.min.css',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'assets/datatables/1.13.7/buttons.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'assets/select2-bootstrap4-theme/select2-bootstrap4.min.css',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'assets/select2/css/select2.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'assets/select2/js/select2.min.js',
                ],
            ],
        ],
        // 'Chartjs' => [
        //     'active' => false,
        //     'files' => [
        //         [
        //             'type' => 'js',
        //             'asset' => false,
        //             'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
        //         ],
        //     ],
        // ],
        'Sweetalert2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'assets/sweetalert2/sweetalert2.js',
                ],
                [
                    'type' => 'css', // <--- Agrega este bloque para el estilo
                    'asset' => true,
                    'location' => 'assets/sweetalert2/sweetalert2.min.css', // Verifica que el archivo exista en esta ruta
                ],
            ],
        ],
        // 'Pace' => [
        //     'active' => false,
        //     'files' => [
        //         [
        //             'type' => 'css',
        //             'asset' => false,
        //             'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
        //         ],
        //         [
        //             'type' => 'js',
        //             'asset' => false,
        //             'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
        //         ],
        //     ],
        // ],
        'Summernote' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/summernote/summernote-bs4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/summernote/summernote-bs4.min.css',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we change the IFrame mode configuration. Note these changes will
    | only apply to the view that extends and enable the IFrame mode.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/IFrame-Mode-Configuration
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'livewire' => false,
];
