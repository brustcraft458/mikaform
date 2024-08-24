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
    <form class="form" action="{{ route('register-post') }}" method="post">
        @csrf
        <div class="side">
            <img src="{{ url('assets/img/form-fill.jpg') }}">
        </div>
        <div class="content">
            <div class="box title">
                <h2>Register Akun</h2>
                @if (session('action_message') == 'register_fail')
                    <p class="message error">Gagal Registrasi.</p>
                @endif
            </div>
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
            <div class="box">
                <input type="submit" class="button primary" name="register" value="Register">
            </div>
            <div class="box">
                <label for="register">Sudah punya akun? <a href="{{ url('/') }}">Masuk disini</a></label>
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
