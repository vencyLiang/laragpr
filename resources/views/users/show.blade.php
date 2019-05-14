<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href=" {{ asset('css/header.css') }}">
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
        }
        .index_header{
            height: 210px;
            background-color: #ffffff;
            box-shadow: 0px 1px 0px 0px rgba(237, 237, 237);
            text-align: center;
        }
        .index_header img{
            width: 120px;
            height: 120px;
            background-color: #b5b5b5;
            border-radius: 100%;
            margin-top: -60px;
            position: absolute;
            margin-left: -60px;
        }
        .index_header label{
            font-family: PingFang-SC-Bold;
            font-size: 24px;
            letter-spacing: 1px;
            color: #333333;
            width:150px;
            display: block;
            margin:0 auto;
            padding-top: 70px;
        }
        .index_header div{
            font-size: 14px;
            color: #999999;
            margin-top: 12px;
        }
        ._main{
            margin-top: -31px;
            margin-bottom: 4px;
        }
        ._span_cur{
            color: #333333 !important;
            border-bottom: 2px solid #333;
        }
        ._main .t_span{
            width: 426px;
            height: 32px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
        }
        ._main span{
            color: #999999;
        }
        .t_item{
            background-color: #f8faf5;
            height: 808px;
            padding-top: 20px;
        }
        ._item_div{
            background-color: #ffffff;
            border-radius: 8px;
            margin: 0 auto;
            display: none;
            padding: 0 30px;
            overflow: hidden;
        }
        .messDiv{
            display: none;
        }
        ._item_div_top{
            height: 50px;
            line-height: 50px;
            border-bottom: 1px solid #f8faf5;
        }
        ._item_div_top span{
            font-size: 16px;
            color: #333333;
            float: left;
        }
        ._item_div_top button{
            width: 80px;
            height: 30px;
            line-height: 32px;
            background-color: #94df31;
            border-radius: 4px;
            border: 0;
            font-size: 16px;
            margin-top: 10px;
            float: right;
            color: #ffffff;
            outline: none;
        }
        ._item_div_top em{
            border: 0;
            font-size: 16px;
            margin: 10px;
            color: #ff0000;
        }
        ._item_data{
            margin-top: 30px;
        }
        ._item_data>div{
            margin-bottom: 20px;
        }
        ._item_data>div label{
            color: #333333;
        }
        ._item_data>div span{
            margin-right: 40px;
            width: 65px;
            text-align: right;
            display: inline-block;
        }



        ._item_data  ._item_label{
            margin-right: 40px;
            width: 100px;
            text-align: right;
            display: inline-block;
        }
        ._item_data>div img{
            width: 36px;
            height: 36px;
            border-radius: 100%;
            vertical-align: middle;
        }
        ._item_data>div input{
            width: 255px;
            height: 32px;
            background-color: #ffffff;
            border-radius: 4px;
            border: solid 1px #ededed;
            outline: none;
            font-size: 16px;
            color: #333333;
            padding: 0 10px;
        }
        ._item_data>div select{
            width: 277px;
            height: 34px;
            background-color: #ffffff;
            border-radius: 4px;
            border: solid 1px #ededed;
            outline: none;
            color: #333333;
            font-size: 16px;
            padding-left: 10px;
        }
        ._mess{
            border-bottom: 1px solid #ededed;
            padding-bottom: 30px;
            padding-top: 44px;
        }
        ._mess_img{
            margin-right: 10px;
            width: 42px;
            float: left;
            height: 260px;
            margin-top: -20px;
        }
        ._mess img{
            width: 42px;
            height: 42px;
            background-color: #e0e0e0;
            border-radius: 100%;
            display: block;
            float: left;
        }

        ._mess_text_top{
            font-size: 16px;
            color: #999999;

        }
        ._mess_text_top span{
            margin-left: 22px;
        }
        ._mess_text_btm{

            margin-top: 12px;
            font-size: 16px;
            line-height: 28px;
            color: #333333;
        }
        ._item_div_sum{
            height: 50px;
            line-height: 50px;
            border-bottom: 1px solid #f8faf5;
            color: #b8b8b8;
        }
        ._item_div_sum span{
            font-size: 16px;
            color: #333333;
            float: left;
            margin-right: 10px;
        }
        ._item_pro{
            margin-top: 30px;
            margin-left: 20px;
        }
        ._item_pro>div{
            margin-bottom: 20px;
        }
        ._item_pro>div span{
            margin-right: 40px;
            width: 65px;
            text-align: right;
            display: inline-block;
        }
        ._item_pro>div input{
            width: 255px;
            height: 32px;
            background-color: #ffffff;
            border-radius: 4px;
            border: solid 1px #ededed;
            outline: none;
            font-size: 16px;
            color: #333333;
            padding: 0 10px;
        }
        ._item_proTip{
            font-size: 14px;
            color: #333333;
        }

        ._item_data_but button{
            font-size: 16px;
            color: #ffffff;
            border: 0;
            width: 160px;
            height: 40px;
            line-height: 40px;
            background-color: #94df31;
            border-radius: 4px;
            text-align: center;
            outline: none;
            letter-spacing: 1px;
        }
        ._detail{
            height: 628px;
            display: block;
            overflow: hidden;
        }
        ._detail thead{
            height: 50px;
            line-height: 50px;
            border-bottom: 1px solid #f8faf5;
        }
        ._detail thead th{
            font-size: 14px;
            color: #b8b8b8;
            height: 50px;
            width: 260px;
        }
        ._detail tbody tr{
            color: #333333;
            text-align: center;
            height: 62px;
        }
        ._detail tbody td:nth-child(3){
            color: #ff4a4a;
        }
        /* 分页 */
        /* 外面盒子---*/
        .page_div{
            margin: 20px;
            text-align: center;
            color: #666;
        }
        /* 页数按钮 */
        .page_div button{
            display:inline-block;
            width:30px;
            height:30px;
            line-height:28px;
            cursor:pointer;
            color:#999;
            font-size:14px;
            background-color:#fff;
            border: solid 1px #d9d9d9;
            border-radius: 2px;
            text-align:center;
            margin:0 4px;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            outline: none
        }
        .page_div button:hover{
            background-color: #ffffff;
            color: #94df31;
            border: solid 1px #94df31;
        }
        #firstPage,#lastPage,#nextPage,#prePage{
            width:50px;color:#999;
            border:1px solid #999
        }
        #nextPage,#prePage{
            width:70px
        }
        .page_div .current{
            color: #FFF;
            background-color: #94df31;
            border-radius: 2px;
            border: solid 1px #94df31;
        }
        /*button禁用*/
        .page_div button:disabled{
            opacity:.5;cursor:no-drop
        }
        #avatar_input{
            position: absolute;
            opacity:0;
            filter:alpha(opacity=0);
            z-index: 9;
            width: 120px;
            height: 120px;
            background-color: #b5b5b5;
            border-radius: 100%;
            margin-top: -60px;
            margin-left: -60px;
        }
        ._item_data .error{
            font-size:10px;
            color:red;
            margin-right: 40px;
            width: 300px;
            text-align: left;
            display: inline-block;
        }

    </style>
</head>
<body >
    @include('layouts._top')

    <!-- banner -->
    <div id="hindex_bkg"></div>
    <div class="index">
        <div class="index_header">
            <input id='avatar_input' type="file" ref="avatar" v-on:change="uploadFile">
            <img v-bind:src="avatar" id="avatar">
            <label for="avatar_input">点击更换头像</label>
            <div class="">
                直接推荐：
                <span>{{ $directNum }}</span>
                <span class="_top_right_span"></span>
                间接推荐：{{ $indirectNum }}
                <span></span>
            </div>
        </div>
        <div class="_main">
                <div class="t_span" id="t_span">
                        <span class="_span_cur">资料</span>
                        <span>消息</span>
                        <span>提现</span>
                        <span>明细</span>
                </div>
                <div class="t_item">
                    <!-- 修改资料 -->
                    <div class="_item_div" style="width: 840px;height: 530px;display:block">
                        <div class="dataDiv">
                                <div class="_item_div_top"> <span>基本信息</span> <button id="btn" style="background-color: #ff6363;">修改</button> </div>
                                <div class="_item_data">
                                    <div><span>用户名</span>  <label for="">{{ $user->name }}</label> </div>
                                    <div><span>性别</span>
                                    <label for="">{{ $user->sex }}</label>
                                    </div>
                                    <div><span>电话号码</span> <label for="">{{ $user->phone_num }}</label> </div>
                                    <div><span>邮箱</span>  <label for="">{{ $user->email }}</label></div>
                                </div>
                        </div>
                        <div class="messDiv" id="info_box">
                            <div class="_item_div_top"> <span>修改资料</span><em>@{{ validationSuccess }}</em> <button id="storeBtn" v-on:click="send">保存</button> </div>
                            <form class="_item_data" id="user_info">
                                <div><label class="_item_label" for="name">用户名</label> <input type="text" name="name" value="{{ $user->name }}" id="name" placeholder="请输入用户名"><span id="name_error" class="error">@{{ validationErr.name_error }}</span> </div>
                                <div><label class="_item_label" for="sex">性别</label>
                                    <select name="" id="sex">
                                        <option value="未知" @if($user->sex === '尚未设置') selected @endif>请选择</option>
                                        <option value="男" @if($user->sex === '男') selected @endif>男</option>
                                        <option value="女" @if($user->sex === '女') selected @endif>女</option>
                                    </select>
                                    <span id="sex_error" class="error">@{{ validationErr.sex_error }}</span>
                                </div>
                                <div><label class="_item_label" for="phone_num">电话号码</label> <input type="tel" name="phone_num" id="phone_num" value="{{ $user->phone_num }}"> <span id="phone_num_error" class="error">@{{ validationErr.phone_num_error }}</span></div>
                                <div><label class="_item_label" for="email">邮箱地址</label>  <input type="email" name="email" id="email"  placeholder="请输入邮箱" value="{{ $user->email }}"><span id="email_error" class="error">@{{ validationErr.email_error }}</span></div>
                                <div><label class="_item_label" for="password">登录密码</label> <input type="password" name="password" id="password"  placeholder="请输入新登录密码"><span id="password_error" class="error">@{{ validationErr.password_error }}</span> </div>
                                <div><label class="_item_label" for="password_confirmation">确认登录密码</label> <input type="password" name="password_confirmation" id="password_confirmation" placeholder="请确认新登录密码" ><span id="password_confirmation_error" class="error">@{{ validationErr.password_confirmation_error }}</span>  </div>
                                <div><label class="_item_label" for="update_withdraw_password">交易密码</label> <input type="password" name="update_withdraw_password" id="update_withdraw_password"  placeholder="请输入新提现密码" ><span id="update_withdraw_password_error" class="error">@{{ validationErr.update_withdraw_password_error }}</span> </div>
                                <div><label class="_item_label" for="update_withdraw_password_confirmation">确认交易密码</label> <input type="password" name="update_withdraw_password_confirmation" id="update_withdraw_password_confirmation" placeholder="请确认新提现密码" > <span id="withdraw_password_confirmation_error" class="error">@{{ validationErr.withdraw_password_confirmation_error }}</span> </div>
                            </form>
                        </div>
                    </div>


                    <!-- 消息 -->
                    <div class="_item_div" style="width: 840px;
                    height: 760px;
                    overflow: auto;">
                            <div class="_item_div_top">
                                <span>消息通知</span> <button style="
                                background-color: #ff5a5a;">清空</button>
                            </div>
                            <div class="_mess">
                                <div class="_mess_img">  <img src="{{ asset('img/timg.png') }}"></div>
                                <div  class="_mess_text">
                                    <div class="_mess_text_top"> 公告 <span>3/28  14:20</span></div>
                                    <div  class="_mess_text_btm">
                                        <p>这是你的熊小美-Midea Family图案创意设计大赛</p>
                                        <p>玩到就是赚到！！！</p>
                                        <p>快来助力，打造下一个角色IP！</p>
                                        <p>大赛截止时间：2019年4月22日24:00</p>
                                        <p>这是你的熊小美-Midea Family图案创意设计大赛</p>
                                        <p>玩到就是赚到！！！</p>
                                        <p>快来助力，打造下一个角色IP！</p>
                                        <p>大赛截止时间：2019年4月22日24:00</p>
                                    </div>
                                </div>
                            </div>
                            <div class="_mess">
                                    <div class="_mess_img">  <img src="{{ asset('img/timg.png') }}"></div>
                                    <div  class="_mess_text">
                                        <div class="_mess_text_top"> 公告 <span>3/28  14:20</span></div>
                                        <div  class="_mess_text_btm">
                                            <p>这是你的熊小美-Midea Family图案创意设计大赛</p>
                                            <p>玩到就是赚到！！！</p>
                                            <p>快来助力，打造下一个角色IP！</p>
                                            <p>大赛截止时间：2019年4月22日24:00</p>
                                            <p>这是你的熊小美-Midea Family图案创意设计大赛</p>
                                            <p>玩到就是赚到！！！</p>
                                            <p>快来助力，打造下一个角色IP！</p>
                                            <p>大赛截止时间：2019年4月22日24:00</p>
                                        </div>
                                    </div>
                            </div>
                            <div class="_mess">
                                    <div class="_mess_img">  <img src="{{ asset('img/timg.png') }}"></div>
                                    <div  class="_mess_text">
                                        <div class="_mess_text_top"> 公告 <span>3/28  14:20</span></div>
                                        <div  class="_mess_text_btm">
                                            <p>这是你的熊小美-Midea Family图案创意设计大赛</p>
                                            <p>玩到就是赚到！！！</p>
                                            <p>快来助力，打造下一个角色IP！</p>
                                            <p>大赛截止时间：2019年4月22日24:00</p>
                                            <p>这是你的熊小美-Midea Family图案创意设计大赛</p>
                                            <p>玩到就是赚到！！！</p>
                                            <p>快来助力，打造下一个角色IP！</p>
                                            <p>大赛截止时间：2019年4月22日24:00</p>
                                        </div>
                                    </div>
                                </div>

                    </div>
                    <!-- 提现 -->
                    <div class="_item_div" style="width: 840px;
                    height: 355px;">
                            <div class="_item_div_sum"><span> 余额：{{ round($user->account->{$currencySymbol . "_balance"},3) }}</span>  {{strtoupper($currencySymbol)}}</div>
                            <form class="_item_pro" id="withdraw">
                                    <div><span>提取金额</span> <input type="number" name="num" id="num" v-bind:value="allMoney"> <span style="color: #94df31;font-size:14px" v-on:click="getAllMoney">全部金额</span> </div>
                                    <div><span>提币地址</span> <input type="text" name="withdraw_address" id="withdraw_address"> </div>
                                    <div><span>交易密码</span> <input type="password" name="withdraw_password" id="withdraw_password"> </div>
                                    <div class="_item_proTip"><span></span> @{{ result }}</div>
                                    <div class="_item_data_but"><span></span> <button type="button" v-on:click="withdrawAction">提交</button> </div>
                            </form>
                    </div>
                    <!-- 明细 -->
                    <div class="_item_div"   style="width: 840px;background-color: #fafafa;
                    ">
                    <table class="_detail">
                        <thead>
                            <th>时间</th>
                            <th>来自</th>
                            <th>金额</th>
                        </thead>
                        <tbody id="recs">
                            <tr v-for = "(rec,i) in recs"  :key="rec.id">
                                <td>@{{ rec.created_at}}</td>
                                <td>@{{ rec.name }}的激活奖励</td>
                                <td>@{{ rec.num }}{{ strtoupper($currencySymbol) }}</td>
                            </tr>
                        </tbody>

                    </table>
                     <!--分页-->
                     <div id="page" class="page_div"></div>
                    </div>

                </div>
        </div>
    </div>
</body>
</html>
<script>
    var Span = document.querySelectorAll("#t_span span");
        Item = document.querySelectorAll("._item_div");
    for(var i=0;i<Span.length;i++){
        Span[i].index = i;
        Span[i].onclick = function(){
            for(var j=0;j<Item.length;j++){
                Item[j].style.display='none';
                Span[j].classList.remove('_span_cur');
            }
            Item[this.index].style.display = 'block';
            this.classList.add('_span_cur');
        }
    }
</script>
<script src="{{ asset('js/jquery.js') }}"></script>
<script type="text/javascript" src=" {{ asset('js/page.js') }}"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script>
    let pageNum;
    let totalNum;
    let totalList;
    let vm = new Vue({
        el:"#recs",
        data:{
           recs:{}
        }
    });
    axios.get("{{ route('users.recs',compact('user')) }}").then(response => {
        vm.recs = response.data.data;
        pageNum = response.data.current_page;
        totalNum = response.data.last_page;
        totalList = response.data.total;
        $("#page").paging({
            pageNum: pageNum, // 当前页面
            totalNum: totalNum, // 总页码
            totalList: totalList, // 总数量
            callback: function (num) {
                axios.get("{{ route('users.recs',compact('user')) }}" + "?page=" + num).then(response=> {
                    vm.recs = response.data.data;
                    console.log(vm.recs);
                }).catch(err =>{
                    console.log(err);
                });
            }
        });
    }).catch(err => {
        console.log(err);
    });
</script>
<script>
$("#btn").click(function(){
    $(".dataDiv").hide();
    $(".messDiv").show();
});
</script>
<script>
    let token = document.head.querySelector('meta[name="csrf-token"]');
    if (token) {
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    } else {
        console.error('CSRF token not found');
    }
</script>
<script>
    let avatar = "{{ $user->avatar }}";
    let sex = "{{ $user->sex }}";
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
    let app_avatar = new Vue({
        el:'#avatar',
        data:{
            avatar: avatar
        }
    });
    let top_avatar = new Vue({
        el:'#top_avatar',
        data:{
            avatar: avatar
        }
    });
    var app = new Vue({
        el:'#avatar_input',
        methods: {
            uploadFile() {
                let formData = new FormData();
                formData.append('picture', this.$refs.avatar.files[0]);
                formData.append('user',{{ $user->id }});
                formData.append('type','avatar');
                axios.post(
                    '/form/file_upload',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                ).then(function (response) {
                    app_avatar.$data.avatar = response.data.path;
                    top_avatar.$data.avatar = response.data.path;
                }).catch(function (error) {
                    console.log(error);
                });
            }
        }
    });
    var infoBox = new Vue({
        el:'#info_box',
        data:{
            validationErr:{
                name_error:'',
                sex_error:'',
                phone_num_error:'',
                email_error:'',
                password_error:'',
                password_confirmation_error:'',
                update_withdraw_password_error:'',
                withdraw_password_confirmation_error:'',
            },
            validationSuccess:''
        },
        methods: {
            send:function(){
                let validationError  = this.validationErr;
                for(let index in validationError){
                    validationError[index] = "";
                }
                axios.patch(
                    '/users/{{ $user->id }}',
                    $('#user_info').serialize(),
                    {
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        }
                    }
                ).then(function (response) {
                    infoBox.validationSuccess = response.data.msg;
                }).catch(function (error) {
                   let errors = error.response.data.errors;
                   for( let key in errors){
                        validationError[key+ "_error"] = errors[key].join('');
                   }
                });
            }
        }
    });
    let allMoney = "{{ round($user->account->{$currencySymbol . "_balance"},3) }}"
    var withdraw= new Vue({
        el:"#withdraw",
        data:{
            allMoney:"",
            result:""
        },
        methods:{
            getAllMoney:function () {
                withdraw.allMoney = allMoney;
            },
            withdrawAction:function () {
                axios.post(
                    '/users/{{ $user->id }}/withdraw',
                    $('#withdraw').serialize(),
                    {
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        }
                    }
                ).then(function (response) {
                   let responseObj = response.data;
                    if(responseObj.status === 200){
                        withdraw.result = responseObj.msg;
                    }else{
                        withdraw.result = "提现失败，请联系客服"
                    }
                }).catch(function (error) {
                    let errorData = error.response.data.errors;
                    for(let key in errorData){
                        withdraw.result += errorData[key].join('');
                    }
                });
            }
        }
    })

</script>



