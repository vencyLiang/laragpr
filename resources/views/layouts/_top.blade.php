<!-- top -->
<div class="_top">
    <div class="_top_left"> 链一<span>公排</span> </div>
    <div class="_top_right">
        <div >余额： @if(Auth::user()->activation_status) <span style="color: #94df31;">{{ round((Auth::user())->account->{$currencySymbol . "_balance"},3) }}{{strtoupper($currencySymbol)}}</span>@else <a style="color: red;text-decoration: none" href="{{ route('users.pay',['user'=>Auth::user()])}}">点此激活</a> @endif  </div>
        <span class="_top_right_span"></span>
        <div > <img class="_top_right_head" v-bind:src="avatar" id="top_avatar"> <span style="color: #fff;">{{ Auth::user()->name }}</span> </div>
    </div>
</div>
<!-- top ////////// -->