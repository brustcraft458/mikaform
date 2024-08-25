<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ url('assets/css/form.css') }}" rel="stylesheet">
    <title>Document</title>
</head>
<body>
    <div class="background-img mix-white">
    </div>
    <form class="form" action="{{ route('register') }}" method="post">
        @csrf
        <div class="side">
            <img src="{{ url('assets/img/form-fill.jpg') }}">
        </div>
        <div class="content">
            <div class="box title">
                <h2>Register Akun</h2>
                @if (session('action_message') == 'register_fail')
                    <p class="message error">Gagal Registrasi.</p>
                @elseif (session('action_message') == 'register_fail_user_exists')
                    <p class="message error">Gagal Registrasi dikarenakan username sama.</p>
                @endif
            </div>

            @if (session('register_step') == 'page_1')
                <div class="box">
                    <label for="phone">Phone:</label><br>
                    <input type="text" id="phone" name="phone" required>
                </div>
                <div class="box">
                    <label for="username">Username:</label><br>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="box">
                    <label for="password">Password:</label><br>
                    <input type="password" id="password" name="password" required>
                </div>
            @elseif (session('register_step') == 'page_2_otp')
                <div class="box">
                    <label for="otp">Kode OTP:</label><br>
                    <input type="number" id="otp" name="otp" required>
                </div>
            @endif

            <div class="box">
                @if (session('register_step') == 'page_1')
                    <input type="submit" class="button primary" name="register_1" value="Next">
                @elseif (session('register_step') == 'page_2_otp')
                    <input type="submit" class="button primary" name="register_2_otp" value="Register">
                @endif
            </div>
            <div class="box">
                <label for="register">Sudah punya akun? <a href="{{ url('/') }}">Masuk disini</a></label>
            </div>
        </div>
    </form>
</body>
</html>
