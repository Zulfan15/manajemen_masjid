<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ZIS Login</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #059669;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow: hidden;
        }
        .login-container {
            min-height: 100vh;
        }
        
        .left-side {
            background: url('https://unair.ac.id/wp-content/uploads/2022/09/Foto-by-Waspada-id.jpg') no-repeat center center;
            background-size: cover;
            position: relative;
        }
        .left-side::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(180deg, rgba(5, 150, 105, 0.4) 0%, rgba(6, 78, 59, 0.8) 100%);
        }
        .left-content {
            position: relative;
            z-index: 2;
            color: white;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 3rem;
        }

        .right-side {
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-form-box {
            width: 100%;
            max-width: 410px;
            padding: 2rem;
        }

        .form-floating > .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
        }
        .form-floating > .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.1);
        }
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 14px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background-color: #047857;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(5, 150, 105, 0.4);
        }

        .brand-logo {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 2rem;
            display: inline-block;
        }
    </style>
</head>
<body>

    <div class="row g-0 login-container">
        <div class="col-lg-7 d-none d-lg-block left-side">
            <div class="left-content">
                <div>
                    <div class="fw-bold fs-4"><i class="fas fa-mosque me-2"></i>Manajemen Masjid</div>
                </div>
                <div class="mb-5">
                    <h1 class="display-4 fw-bold mb-3">Amanah Umat,<br>Tanggung Jawab Kita.</h1>
                    <p class="fs-5 opacity-75">"Dan dirikanlah shalat, tunaikanlah zakat dan ruku'lah beserta orang-orang yang ruku'." <br> (QS. Al-Baqarah: 43)</p>
                </div>
            </div>
        </div>

        <div class="col-lg-5 right-side">
            <div class="login-form-box">
                <div class="mb-3">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-2"></i> Kembali ke Dashboard Utama
                    </a>
                </div>

                <div class="mb-5">
                    <div class="brand-logo"><i class="fas fa-hand-holding-heart me-2"></i>ZIS Login</div>
                    <h4 class="fw-bold text-dark">Selamat Datang Kembali! 👋</h4>
                    <p class="text-muted">Silakan masukkan akun Amil atau Admin Anda.</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger border-0 d-flex align-items-center mb-4" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <div>Email atau password salah.</div>
                    </div>
                @endif

                <form action="{{ route('zis.login') }}" method="POST">
                    @csrf
                    
                    <div class="form-floating mb-3">
                        <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" required value="{{ old('email') }}">
                        <label for="email">Email Address</label>
                    </div>
                    
                    <div class="form-floating mb-4">
                        <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                        <label for="password">Password</label>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            Login <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
