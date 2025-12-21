<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Inventory System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .login-container { margin-top: 100px; max-width: 400px; }
        .card { border: none; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .btn-primary { background-color: #4e73df; border: none; }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center">
    <div class="login-container w-100">
        <div class="card p-4">
            <div class="text-center mb-4">
                <h3 class="fw-bold text-primary">INVENTORY LOG</h3>
                <p class="text-muted">Silakan login untuk masuk</p>
            </div>

            @if($errors->has('error'))
                <div class="alert alert-danger py-2">
                    {{ $errors->first('error') }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" id="email" placeholder="nama@email.com" required value="{{ old('email') }}">
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="********" required>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-primary p-2">Login Sekarang</button>
                </div>
            </form>
        </div>
        <div class="text-center mt-3">
            <small class="text-muted">&copy; 2025 Inventory App</small>
        </div>
    </div>
</div>

</body>
</html>