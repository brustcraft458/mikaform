<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
</head>
<body>
    <form method="POST" action="{{ url('/otp/verify') }}">
        @csrf
        <input type="hidden" name="user_id" value="{{ session('user_id') }}">
        <label for="otp">Enter OTP:</label>
        <input type="text" id="otp" name="otp" required>
        <button type="submit">Verify</button>
    </form>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Alert if session has an alert message
            @if (session('alert'))
                alert('{{ session('alert') }}');
            @endif

            // Redirect if session has a redirect URL
            @if (session('redirect'))
                window.location.href = '{{ session('redirect') }}';
            @endif
        });
    </script>
</body>
</html>
