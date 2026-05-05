<!DOCTYPE html>

<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Sistem Smart City</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

```
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Inter", sans-serif;
    }

    body {
        height: 100vh;
        background: linear-gradient(135deg, #8F00FF, #6A00CC);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-container {
        width: 100%;
        max-width: 400px;
        background: #fff;
        border-radius: 16px;
        padding: 32px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .login-header {
        text-align: center;
        margin-bottom: 24px;
    }

    .login-header h2 {
        font-size: 20px;
        font-weight: 600;
        color: #1A1D23;
    }

    .login-header p {
        font-size: 13px;
        color: #6C757D;
        margin-top: 4px;
    }

    .form-group {
        margin-bottom: 16px;
    }

    label {
        display: block;
        font-size: 12px;
        font-weight: 500;
        margin-bottom: 6px;
        color: #374151;
    }

    input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        font-size: 14px;
        outline: none;
        transition: 0.2s;
    }

    input:focus {
        border-color: #8F00FF;
        box-shadow: 0 0 0 3px rgba(143, 0, 255, 0.1);
    }

    .btn-login {
        width: 100%;
        background: #8F00FF;
        color: #fff;
        border: none;
        padding: 12px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: 0.2s;
    }

    .btn-login:hover {
        background: #7300cc;
    }

    .error {
        background: #FEE2E2;
        color: #991B1B;
        padding: 10px;
        border-radius: 8px;
        font-size: 12px;
        margin-bottom: 16px;
    }

    .footer {
        text-align: center;
        margin-top: 16px;
        font-size: 11px;
        color: #9CA3AF;
    }
</style>
```

</head>
<body>

<div class="login-container">

```
<div class="login-header">
    <h2>Smart City Dashboard</h2>
    <p>Silakan login untuk melanjutkan</p>
</div>

{{-- ERROR MESSAGE --}}
@if(session('error'))
    <div class="error">
        {{ session('error') }}
    </div>
@endif

<form method="POST" action="{{ route('login.post') }}">
    @csrf

    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required placeholder="Masukkan email">
    </div>

    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" required placeholder="Masukkan password">
    </div>

    <button type="submit" class="btn-login">
        Login
    </button>
</form>

<div class="footer">
    © {{ date('Y') }} Smart City Tangerang
</div>
```

</div>

</body>
</html>
