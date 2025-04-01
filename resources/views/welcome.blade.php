<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .welcome-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="welcome-container text-center">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <h1 class="mb-3">Welcome to Jurnal Kegiatan Management</h1>
            <p class="mb-4">Silakan klik tombol di bawah untuk mulai mengisi data agenda.</p>
            
            <a href="{{ route('agenda.index') }}" class="btn btn-primary btn-lg">Next</a>
        </div>
    </div>
</body>
</html>
