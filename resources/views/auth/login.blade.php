<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <title>链一全球公排系统</title>
    <style>
        /* 登录 */
        #l_bkg{
            height: 1030px;
            background-color: #f5f7f5;
            position: relative;
            text-align: center;
        }
        .login{
            background: url({{ asset('img/login.png') }}) no-repeat;
            width: 846px;
            height: 514px;
            border-radius: 1px;
            margin: 0 auto;
            margin-top: 230px;
            position: absolute;
            display: inline-block;
            margin-left: -423px;
        }
        .login_l{
            width: 210px;
            height: 46px;
            font-size: 47px;
            color: #ffffff;
            float: left;
            margin-left: 114px;
            margin-top: 228px;
        }
        .login_l img{
            width: 210px;
            height: 46px;
        }
        .login_r{
            float: right;
            margin-right: 52px;
        }
        .login_r header{
            font-size: 36px;
            color: #333333;
            margin-top: 70px;
            margin-bottom: 60px;
        }
        .upText{
            width: 288px;
            height: 42px;
            line-height: 42px;
            background-color: #f4f4f4;
            border-radius: 10px;
            border: solid 1px #e5e5e5;
            margin-bottom: 20px;
            padding: 0 24px;
            text-align: left;
        }
        .upText span{
            color: #666666;
            font-size: 14px;
        }
        .upText input{
            height: 42px;
            line-height: 42px;
            background: none;
            outline: none;
            border: 0;
            font-size: 16px;
        }

        ._up_self{
            text-align: left;
            margin-bottom: 20px;
        }
        ._up_self input{
            width: 14px;
            height: 14px;
            border-radius: 2px;
            border: solid 1px #999999;
        }
        ._up_self input{
            width: 14px;
            height: 14px;
            border-radius: 2px;
            border: solid 1px #999999;
        }
        ._up_self span{
            font-size: 14px;
            color: #666666;
            margin-left: 10px;
            vertical-align: top;
        }
        ._up_but{
            text-align: left;
        }
        ._up_but button{
            width: 336px;
            height: 42px;
            line-height: 42px;
            background-color: #83be0c;
            border-radius: 10px;
            border: 0;
            font-size: 16px;
            letter-spacing: 1px;
            color: #fff;
            outline: none;
        }
        ._up_but button:hover{
            color: #f5f7f5;
        }
        ._toggle{
            background-color: #ffffff;
            border: solid 1px #83be0c;
        }
        .invalid-feedback{
            color:red;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        }
        .nav-link{
            text-decoration: none;
        }
        .nav-link{
            color: #fff;
            background: none;
        }
        .nav-link:hover{
            color: #94df31;
        }
    </style>
</head>
<body>
    <!-- top -->
    <div class="_top">
        <div class="_top_left"> 链一<span>公排</span> </div>
        <div class="_top_right">
                <button><a class="nav-link" href="{{ route('login') }}">登录</a></button>
                <span class="_top_right_span"></span>
                <button><a class="nav-link" href="{{ route('register') }}">注册</a></button>
        </div>
    </div>
    <!-- top ////////// -->

    <!-- banner -->
    <div id="l_bkg">
        <div class="login">
           <div class="login_l"> <img src="#" alt="LOGO"> </div>
           <div class="login_r">
                <header>欢迎登录公排系统</header>
              <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                  @csrf
                  <div class="upText">
                      <span>账号：</span>
                      <input type="text" id="account" name="account" value="{{ old('account') }}" placeholder="用户名/邮箱/手机号" required autofocus>
                      @if ($errors->has('account'))
                          <div class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('account') }}</strong>
                          </div>
                      @endif
                  </div>
                  <div style="position: relative;top:10px" class="upText">
                      <span>密码：</span>
                      <input type="password" id="password" name="password" required>
                  </div>
                  @if ($errors->has('password'))
                      <div class="invalid-feedback" role="alert">
                             <strong>{{ $errors->first('password') }}</strong>
                      </div>
                  @endif
                  <div class="_up_self">
                      <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}><span>下次自动登录</span>
                  </div>
                  <div class="_up_but">
                      <button type="submit">登录</button>
                  </div>
              </form>
           </div>
        </div>
    </div>

    <!-- banner //////////-->
</body>
</html>
<script type="text/javascript" src="{{ asset('js/jquery.js') }}"></script>
<script>
  $(function() {
    $( ".upText" ).click(function() {
      $(this).addClass( "_toggle", 1000 );
      return false;
    });
  });
</script>
