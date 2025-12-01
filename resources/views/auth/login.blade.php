<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AgroPapas | Login</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    
    <style>
        .login-page {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .login-box {
            width: 400px;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 1.5rem;
        }
        .login-logo img {
            max-width: 80px;
            margin-bottom: 1rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 0.75rem;
            font-weight: 600;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <i class="fas fa-seedling fa-3x text-white mb-3"></i>
        <h1 class="text-white font-weight-bold">AgroPapas</h1>
    </div>

    <div class="card">
        <div class="card-header text-center">
            <h4 class="mb-0">
                <i class="fas fa-sign-in-alt mr-2"></i>Iniciar Sesión
            </h4>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="icon fas fa-ban"></i>
                    <ul class="mb-0 pl-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="post">
                @csrf
                <div class="input-group mb-3">
                    <input 
                        type="email" 
                        name="email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        placeholder="Correo electrónico"
                        value="{{ old('email') }}"
                        required
                        autofocus
                    >
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                
                <div class="input-group mb-3">
                    <input 
                        type="password" 
                        name="password" 
                        class="form-control @error('password') is-invalid @enderror" 
                        placeholder="Contraseña"
                        required
                    >
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-7">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">
                                Recordarme
                            </label>
                        </div>
                    </div>
                    <div class="col-5">
                        <button type="submit" class="btn btn-primary btn-block">
                            Ingresar
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer text-center text-muted">
            <small>&copy; {{ date('Y') }} AgroPapas. Sistema de Gestión.</small>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
