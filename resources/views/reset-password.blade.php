<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ url('assets/css/form.css') }}" rel="stylesheet">
    <title>Buat Sandi Baru</title>
</head>
<body>
    <div class="background-img mix-white"></div>
    <form class="form" action="{{ route('reset-password') }}" method="post">
        @csrf
        <div class="side">
            <img src="{{ url('assets/img/form-fill.jpg') }}">
        </div>
        <div class="content">
            <div class="box title">
                <h2>Buat Sandi Baru</h2>
                @if (session('action_message'))
                    <p class="message error">{{ session('action_message') }}</p>
                @endif
            </div>

            <div class="box">
                <label for="otp">Kode OTP:</label><br>
                <input type="text" id="otp" name="otp" value="{{ old('otp') }}" required>
            </div>
            <div class="box">
                <label for="password">Sandi Baru:</label><br>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="box">
                <label for="password_confirmation">Konfirmasi Sandi:</label><br>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>
            <div class="box">
                <input type="submit" class="button primary" value="Reset Sandi">
            </div>

            <div class="box">
                <label for="register">Sudah punya akun? <a href="{{ url('/') }}">Masuk disini</a></label>
            </div>
        </div>
    </form>
</body>
</html>
