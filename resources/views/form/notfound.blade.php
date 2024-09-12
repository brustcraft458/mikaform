<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Not Found</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        h1 {
            font-size: 5rem;
            margin: 0;
            color: #e74c3c;
        }
        p {
            font-size: 1.5rem;
            margin: 0.5rem 0;
        }
        a {
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>404</h1>
        <p>Mohon Maaf, formulir tidak ditemukan atau telah ditutup.</p>
        <p>Silahkan cek secara berkala atau hubungi admin.</p>
        <p><a href="{{ url('/') }}">Kembali ke halaman utama</a></p>
    </div>
</body>
</html>
