<!--<script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>-->

@extends('layouts.app')
@section('content')
    <script>
        //var jQuery = jQuery.noConflict();
        function ajax_check_invite(){
            $.ajax({
                'url':"{{ route('checkinvite') }}",
                'type':"get",
                'data': {'up_invite_code':$('#up_invite_code').val()},
                'success':function (response) {
                    if (response === '0'){
                        $('#check_tip').html("<span style='color:red;'><b>邀请码无效</b></span>")
                        $("#submitBtn").attr('disabled','disabled');
                    }else{
                        $('#check_tip').html("<span style='color:green;'><b>邀请码有效</b></span>")
                        $("#submitBtn").removeAttr('disabled');
                    }
                }
            })
        }
        $(function(){
            ajax_check_invite();
            $('#up_invite_code').bind('input propertychange',function () {
                ajax_check_invite();
            })
        })
    </script>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}" aria-label="{{ __('Register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="up_invite_code" class="col-md-4 col-form-label text-md-right">{{ __('Up Invite Code') }}</label>

                            <div class="col-md-6">
                                <input id="up_invite_code" type="text" class="form-control{{ $errors->has('up_invite_code') ? ' is-invalid' : '' }}" name="up_invite_code" @if (isset($up_invite_code)) value="{{ $up_invite_code}}" @endif required>
                                <div id = 'check_tip'></div>
                                @if ($errors->has('up_invite_code'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('up_invite_code') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary"  id="submitBtn">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

