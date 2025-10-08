<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Stripe Payment Integration')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .payment-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            padding: 40px;
            max-width: 764px;
            margin: 50px auto;
        }
        .form-label {
            font-weight: 600;
            color: #333;
        }
        .btn-primary {
            background: #667eea;
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background: #764ba2;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        #card-element {
            padding: 12px;
            border: 1px solid #ced4da;
            border-radius: 8px;
            background: white;
        }
        #card-errors {
            color: #dc3545;
            margin-top: 10px;
            font-size: 14px;
        }
        .spinner-border {
            display: none;
        }
        .loading .spinner-border {
            display: inline-block;
        }
        .alert {
            border-radius: 10px;
        }
    </style>

    @stack('styles')
</head>
<body>
<div class="container">
    @yield('content')
</div>

<!-- Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

@stack('scripts')
</body>
</html>
