<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ url('assets/css/form.css') }}" rel="stylesheet">
    <title>Kirim OTP</title>
</head>
<body>
    <div class="background-img mix-white"></div>
    <form class="form" action="{{ route('handle-forgot-password') }}" method="post">
        @csrf
        <div class="side">
            <img src="{{ url('assets/img/form-fill.jpg') }}">
        </div>
        <div class="content">
            <div class="box title">
                <h2>Kirim OTP</h2>
                @if (session('action_message'))
                    <p class="message error">{{ session('action_message') }}</p>
                @endif
            </div>

            <div class="box">
                <label for="phone">No Hp:</label><br>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required>
            </div>
            <div class="box">
                <input type="submit" class="button primary" value="Kirim OTP">
            </div>

            <div class="box">
                <label for="register">Sudah punya akun? <a href="{{ url('/') }}">Masuk disini</a></label>
            </div>
        </div>
    </form>
</body>
</html>
