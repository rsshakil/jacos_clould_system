<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{__('messages.login_title')}}</title>
    <link rel="shortcut icon" href="{{ Config::get('app.url') . 'public/backend/images/logo/favicon.ico' }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{Config::get('app.url').'public/css/app.css'}}">
    <link rel="stylesheet" href="{{Config::get('app.url').'public/css/flag-icon.css'}}">
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous">
    </script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="{{Config::get('app.url').'public/css/my_login_css.css'}}">
</head>

<body>


    <nav class="navbar navbar-expand-lg navbar-light bg-light rounded">
        @if (App::environment()!='production')
        <div class="collapse navbar-collapse" id="navbarsExample09">
            <ul class="navbar-nav border-left flex-row ">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-nowrap px-3" href="#" id="navbarDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        @if(App::isLocale('ja'))
                        <span class="flag-icon flag-icon-jp"></span> 日本語
                        @elseif(App::isLocale('en'))
                        <span class="flag-icon flag-icon-us"></span> English
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-small" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ Config::get('app.url').'language/en' }}"><span
                                class="flag-icon flag-icon-us"></span> English</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ Config::get('app.url').'language/ja' }}"><span
                                class="flag-icon flag-icon-jp"></span> 日本語</a>
                    </div>
                </li>
            </ul>

        </div>
        @endif
    </nav>
    <div class="container">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="fadeInDown">
                    <div id="formContent">
                        <!-- Tabs Titles -->

                        <!-- Icon -->
                        <div class="fadeIn first">
                            <img src="{{ Config::get('app.url').'public/dashboard/logo/cropped-Jacos-main.png' }}"
                                id="icon" alt="User Icon" />

                        </div>
                        <!-- Login Form -->
                        {{-- @if (Session::get('message'))
                        <h4 style="width: 100%" class="text-white text-center bg-danger">{{ Session::get('message') }}</h4> <br>
                        @endif --}}
                        <form method="POST" action="{{Config::get('app.url').'login'}}" style="padding:20px;">
                            @csrf
                            <div class="form-group">
                                <label for="email" style="width: 100%"
                                    class="text-white bg-info">{{ __('messages.email') }}</label> <br>
                                <input type="email"
                                    class="form-control fadeIn second{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                    name="email" id="email" placeholder="{{ __('messages.email_text') }}"
                                    value="{{ old('email') }}" required autofocus>
                                @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert" style="display:block">
                                    <strong>{{ __('auth.u_p_incorrect') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="password" style="width: 100%"
                                    class="text-white bg-info">{{ __('messages.password') }}</label> <br>
                                <input type="password"
                                    class="form-control fadeIn second{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                    name="password" id="password" placeholder="{{ __('messages.password_text') }}"
                                    value="{{ old('password') }}" required autofocus>
                                @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert" style="display:block">
                                    <strong>{{ __('auth.u_p_incorrect') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">

                                </div>
                                <div class="col-md-6 offset-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                            {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('messages.remember_text') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 offset-md-3">
                                    <input type="submit" id="login" class="fadeIn second"
                                        value="{{ __('messages.login_text') }}">
                                </div>
                                {{-- <div class="col-md-6 offset-md-3" style="text-align: center; margin-top: -15px;">
                                    @if (Route::has('password.request'))
                                    <a class="underlineHover"
                                        href="{{Config::get('app.url').'password/reset'}}">
                                        {{ __('messages.forgot_pass_text') }}
                                    </a>
                                    @endif
                                </div> --}}
                            </div>
                        </form>

                        <!-- Remind Passowrd -->
                        <div id="formFooter">
                            <a href="" id="bookmark_save" class="fadeIn first btn btn-info text-white">{{ __('messages.save_bookmark') }}</a>
                            {{-- <button id="bookmark_save" class="fadeIn first btn btn-info text-white">{{ __('messages.save_bookmark') }}</button> --}}
                            <p>{{ __('messages.register_favorite') }}</p>
                            {{-- <button class="fadeIn second">{{ __('messages.forgot_pass_text') }}</button> --}}
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
</body>
<script language="javascript" type="text/javascript">
    $(document).ready(function(){
      $("#bookmark_save").click(function(e){
        // $("a.bookmark").click(function(e){
        e.preventDefault(); // this will prevent the anchor tag from going the user off to the link
        var bookmarkUrl = this.href;
        var bookmarkTitle = this.title;
        // console.log(bookmarkUrl);
        // console.log(bookmarkTitle);
        if (navigator.userAgent.toLowerCase().indexOf('chrome') > -1) {
//             var e = $.Event("keydown", { keyCode: 112});
// $("body").trigger(e);
            var e = jQuery.Event("keydown");
            e.which = 68;       // # F1 code value
            e.ctrlkey = true;     // control key pressed
            $(document).trigger(e);// trigger event on document

            // var evt = jQuery.Event("keypress");
            // evt.keyCode = 83; // d
            // evt.ctrlKey = true;
            // $(document).trigger(evt);
                alert("この機能はGoogle Chromeでは使用できません。アドレスバーの最後にある星マークをクリックするか、Ctrl-D（Macの場合はCommand+D）を押してブックマークを作成してください。");
        }else if (window.sidebar) { // For Mozilla Firefox Bookmark
            window.sidebar.addPanel(bookmarkTitle, bookmarkUrl,"");
        } else if( window.external || document.all) { // For IE Favorite
            window.external.AddFavorite( bookmarkUrl, bookmarkTitle);
        } else if(window.opera) { // For Opera Browsers
            $("bookmark_save").attr("href",bookmarkUrl);
            $("bookmark_save").attr("title",bookmarkTitle);
            $("bookmark_save").attr("rel","sidebar");
        }else if(document.all){// ie
             window.external.AddFavorite(bookmarkUrl, bookmarkTitle);
        }else { // for other browsers which does not support
            alert('Your browser does not support this bookmark action');
            return false;
        }
//   });

      });
    });
    </script>
</html>
