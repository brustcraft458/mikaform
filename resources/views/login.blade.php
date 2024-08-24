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
    <form class="form" action="{{ route('login') }}" method="post">
        @csrf
        <div class="side">
            <img src="{{ url('assets/img/form-fill.jpg') }}">
        </div>
        <div class="content">
            <div class="box title">
                <h2>Login Akun</h2>
                @if (session('action_message') == 'login_fail')
                    <p class="message error">Username atau password salah.</p>
                @endif
            </div>
            <div class="box">
                <label for="username">Username:</label><br>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="box">
                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="box">
                <input type="submit" class="button primary" name="login" value="Login">
            </div>
            <div class="box">
                <label for="register">Tidak punya akun? <a href="{{ url('/register') }}">Daftar disini</a></label>
            </div>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if (session('success'))
                alert("{{ session('success') }}");
            @endif
        });
    </script>
</body>
</html>
