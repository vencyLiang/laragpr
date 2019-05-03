<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <title>链一全球公排系统</title>
    <style>
        #hindex_bkg{
            background: url({{ asset('img/banner.jpg') }}) no-repeat;
            height: 240px;
            background-position: initial;
            position: relative;
        }

        .index{
            height: 1038px;
            background-color: #f8faf5;
            text-align: center;
        }
        ._main{
            margin-top: -50px;
            position: absolute;
            width: 900px;
            height: 368px;
            background-color: #ffffff;
            border-radius: 8px;
            display: inline-block;
            margin-left: -450px;
        }
        ._pay_top{
            height: 50px;
            line-height: 50px;
            text-align: left;
            border-bottom: 1px solid #f8faf5;
        }
        ._pay_top span{
            margin-left: 20px;
            color: #333333;
        }
        ._pay_btm{
            margin: 30px;
        }
        ._pay_btm_code{
            width: 110px;
            height: 110px;
            background-color: #e0e0e0;
            float: left;
        }
        ._pay_btm_code img{
            width: 110px;
            height: 110px;
        }
        ._pay_btm_text{
            text-align: left;
            margin-left: 140px;
        }
        ._pay_btm_text>div{
            line-height: 36px;
            color: #999;

        }
        ._pay_btm_text>div span{
            color: #ff6363;
            margin-left: 25px;
        }
        ._pay_btm_text>div button{
            width: 62px;
            height: 24px;
            background-color: #ffffff;
            border-radius: 12px;
            border: solid 1px #3677fd;
            line-height: 24px;
            outline: none;
            color: #3c8fff;
            float: right;
        }
        ._pay_btm_text>div label{
            color: #333333;
            margin-left: 25px;
        }
        ._pay_btm_text>div input{
            color: #333333;
            background: none;
            border: 0;
            width: 370px;
            font-size: 16px;
            margin-left: 25px;
            outline: none;
        }
        ._pay_btm_tip{
            text-align: left;
            margin: 0 30px;
        }
        ._pay_btm_tip strong{
            font-size: 14px;
            color: #ff3535;
            line-height: 24px;

        }
        ._pay_btm_tip div{
            font-size: 14px;
            color: #999999;
            line-height: 24px;
            letter-spacing: 1px;
            display: inline;
        }
    </style>
</head>
<body >
    @include('layouts._top')

    <!-- banner -->
    <div id="hindex_bkg"></div>
    <div class="index">

        <div class="_main">
            <div class="_pay_top"><span>支付</span></div>
            <div class="_pay_btm">
                <div class="_pay_btm_code"> <img src="{{ asset('storage/qrcodes/'.$user->id .'.svg') }}" alt="二维码"> </div>
                <div class="_pay_btm_text">
                    <div> 支付金额  <span>{{ $cost }} {{ strtoupper($currencySymbol) }}</span> </div>
                    <div> 付款地址  <input name="text" id="text"  value="{{ $walletAddress }}"> <button onclick="myCopy()">复制</button> </div>
                    <div> 有效时间  <label for="">本地址&nbsp;<strong style="color:red" id="time"></strong>&nbsp;后失效</label> </div>
                </div>
            </div>
            <div class="_pay_btm_tip">
                    <strong>注意：</strong>
                    <div>您最好发送确切的货币金额。如果您将转账超过所需金额，余额将在账户中体现，您可以随时提现。但是，如果您发送的数量较少系统将无法为您激活账号，直到您发送的累计金额大于等于所需金额。支付后，请等待区块确认，如有疑问，请联系客服。</div>
                </div>
        </div>
    </div>

    <!-- banner //////////-->

</body>
</html>
<script src="{{ asset('js/jquery.js') }}"></script>
<script>
        function myCopy(){
        var ele = document.getElementById("text");
        ele.select();
        document.execCommand("Copy");
        alert('复制成功');
        }

        //倒计时总秒数量
        function timer(intDiff){
            window.setInterval(function(){
                var day=0,
                    hour=0,
                    minute=0,
                    second=0;//时间默认值
                if(intDiff > 0){
                    day = Math.floor(intDiff / (60 * 60 * 24));
                    hour = Math.floor(intDiff / (60 * 60)) - (day * 24);
                    minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);
                    second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
                }
                if (minute <= 9) minute = '0' + minute;
                if (second <= 9) second = '0' + second;
                $('#time').html( day+"天"+ hour + "小时" + minute +"分" + second + "秒");
                intDiff--;
            }, 1000);
        }
        $(function(){
            let intDiff = parseInt({{ $time }});
            timer(intDiff);
        });
</script>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script>
    let avatar = "{{ $user->avatar }}";
    let sex = "{{ $user->sex }}"
    if(!avatar){
        if(sex === '男'){
            avatar = "{{ asset('storage/avatar/male.png') }}";
        }else if(sex === '女'){
            avatar = "{{ asset('storage/avatar/female.png') }}";
        }else{
            avatar = "{{ asset('storage/avatar/unknown.png') }}";
        }
    }else{
        avatar = "{{ asset($user->avatar) }}";
    }
    let top_avatar = new Vue({
        el:'#top_avatar',
        data:{
            avatar: avatar
        }
    });
</script>