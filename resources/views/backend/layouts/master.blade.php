<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <?php
    $mix_js_path=mix('js/app.js');
    $mix_css_path=mix('css/app.css');

    $mix_js_path_array= explode("/",$mix_js_path);
    $mix_css_path_array= explode("/",$mix_css_path);

    if ($mix_js_path_array[1]=='public') {
         unset($mix_js_path_array[1]);
         unset($mix_css_path_array[1]);
    }

    $app_js_path=implode("/",$mix_js_path_array);
    $app_css_path=implode("/",$mix_css_path_array);
 ?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>JCS</title>
    <link rel="shortcut icon" href="{{Config::get('app.url') . 'public/backend/images/logo/favicon.ico'}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description"
        content="A high-quality &amp; free Bootstrap admin dashboard template pack that comes with lots of templates and components.">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    {{-- <link rel="stylesheet" href="{{Config::get('app.url').'/public/css/app.css'}}"> --}}
    {{-- <link rel="stylesheet" href="{{mix('css/app.css')}}"> --}}
    {{-- <link rel="stylesheet" href="{{ Config::get('app.url') }}{{mix('css/app.css')}}"> --}}
    <link rel="stylesheet" href="{{ Config::get('app.url').'public'.$app_css_path }}">

    <link rel="stylesheet" href="{{Config::get('app.url').'/public/css/flag-icon.css'}}">
    <link href="{{Config::get('app.url').'/public/dashboard/styles/shards-dashboards.1.1.0.min.css'}}" rel="stylesheet">
    <link rel="stylesheet" href="{{Config::get('app.url').'/public/dashboard/styles/extras.1.1.0.min.css'}}">
    <link rel="stylesheet"
        href="https://use.fontawesome.com/releases/v5.2.0/css/all.css"
        integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ"
        crossorigin="anonymous">
    <script src="{{Config::get('app.url').'/public/js/buttons.js'}}"></script>
    <link rel="stylesheet" href="{{Config::get('app.url').'/public/css/vue-multiselect.min.css'}}">
    <link rel="stylesheet" href="{{Config::get('app.url').'/public/css/canvas_css.css'}}">
    <link rel="stylesheet" href="{{Config::get('app.url').'/public/css/custom.css'}}">
    @include('backend.layouts.js_variable')
    <style>
        .btn-link-custom{
            font-size:15px;
            text-decoration: none !important;
            font-family: "Nunito", sans-serif !important;
        }
    </style>
</head>

<body>
    <div class="container-fluid" id="app"></div>
    <b-modal size="lg" :hide-backdrop="true" @ok="signinUser($event)" :title="table_col_modal_title"
        v-model="table_col_modal_setting" :no-enforce-focus="true">

        <div v-html="table_col_setting_list"></div>
    </b-modal>
    <script type="text/javascript">
    @auth
    window.Permissions = {!!json_encode(Auth::user()->allPermissions, true) !!};
    window.Roles = {!!json_encode(Auth::user()->allRoles, true) !!};
    window.byr = {!!json_encode(Auth::user()->ByrInfo, true) !!};
    @else
        window.Permissions = [];
        window.Roles = [];
        window.byr =[];
    @endauth

    </script>
    {{-- <script src="{{Config::get('app.url').'public/js/app.js'}}"></script> --}}
    {{-- <script src="{{ Config::get('app.url') }}{{mix('js/app.js')}}"></script> --}}
    <script src="{{ Config::get('app.url').'public'.$app_js_path }}"></script>
    <script src="{{Config::get('app.url').'/public/js/jquery-3.5.1.min.js'}}"></script>
    <script src="{{Config::get('app.url').'/public/dashboard/scripts/Chart.min.js'}}"></script>
    <script src="{{Config::get('app.url').'/public/dashboard/scripts/shards-dashboards.1.1.0.min.js'}}"></script>
    <script src="{{Config::get('app.url').'/public/js/jquery.sharrre.min.js'}}"></script>
    <script src="{{Config::get('app.url').'/public/dashboard/scripts/extras.1.1.0.min.js'}}"></script>
    <script src="{{Config::get('app.url').'/public/js/printThisLibrary/printThis.js'}}"></script>
</body>


</html>
