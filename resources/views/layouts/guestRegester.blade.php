<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lystah App - Company Registration</title>
    <!-- <link rel="stylesheet" href="stylesign.css"> -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Arimo&family=Poppins:wght@300;400;500;600;700&family=Cairo:wght@200..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="{{ URL::asset('asetNew/css/buttons.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

   <style>
        :root {
            --primary-color: #3676FF;
            --primary-hover: #2a5fd1;
            --text-color: #2c3e50;
            --light-gray: #f8f9fa;
            --border-color: #e9ecef;
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.1);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
            --transition: all 0.3s ease;
        }

        * {
            box-sizing: border-box;
            padding: 0;
            margin: 0;
            font-family: 'Cairo', sans-serif;
        }

        body {
            font-family: 'Cairo', sans-serif;
            margin: 0;
            background: url(https://images.unsplash.com/photo-1636955669242-11b90050e9ac?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D);
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            color: var(--text-color);
        }

        .sign-in-container {
            display: flex;
            align-items: center;
            min-height: 100vh;
            padding: 2rem;
            flex-direction: row-reverse;
        }

        .sign-column {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
        }

        .s2 {
            background: rgba(255, 255, 255, 0.95);
            padding: 3rem;
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            width: 100%;
            max-width: 100%;
            transition: var(--transition);
            backdrop-filter: blur(10px);
        }

        .s2:hover {
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }

        .input-container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            width: 100%;
        }

        .input-container input {
            padding: 0.75rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            transition: var(--transition);
            background: var(--light-gray);
            text-align: right;
        }

        .input-container input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(54, 118, 255, 0.1);
            outline: none;
            background: #fff;
        }

        .input-container button {
            background: var(--primary-color);
            color: white;
            padding: 0.75rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .input-container button:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
        }

        .login-w-button {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 0.75rem;
            border: 2px solid var(--primary-color);
            border-radius: 8px;
            background: white;
            color: var(--primary-color);
            font-weight: 500;
            transition: var(--transition);
            text-decoration: none;
        }

        .login-w-button:hover {
            background: var(--light-gray);
            transform: translateY(-2px);
        }

        .sign-in-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .canvas-logo {
            max-width: 250px;
            margin: 0 auto 2rem;
        }

        .canvas-logo img {
            width: 100%;
            height: auto;
        }

        .intro-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 1rem;
            text-align: center;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .intro-title-alt {
            font-size: 1.1rem;
            color: #fff;
            text-align: center;
            max-width: 400px;
            margin: 0 auto;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        }

        .slice-text-c {
            position: relative;
            margin: 2rem 0;
            text-align: center;
        }

        .slice-text {
            background: white;
            padding: 0 1rem;
            color: #666;
            font-size: 0.9rem;
        }

        .slicer {
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--border-color);
            z-index: -1;
        }

        @media (max-width: 768px) {
            .sign-in-container {
                flex-direction: column;
                padding: 1rem;
            }

            .sign-column {
                width: 100%;
            }

            .s2 {
                padding: 2rem;
                margin: 1rem 0;
            }

            .intro-title {
                font-size: 2rem;
            }

            body {
                background: #fff;
            }
        }

        /* Form validation styles */
        .input-container input.error {
            border-color: #dc3545;
        }

        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            text-align: right;
        }

        /* Loading state */
        .loading {
            position: relative;
            pointer-events: none;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 1.5rem;
            height: 1.5rem;
            margin: -0.75rem 0 0 -0.75rem;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* RTL specific styles */
        .sign-column:nth-child(2) {
            text-align: right;
        }

        .input-container input::placeholder {
            text-align: right;
        }

        .footer-s {
            position: absolute;
            bottom: 30px;
            display: flex;
            gap: 10px;
            right: 30px;
        }

        .footer-s a {
            color: #fff;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="sign-in-container">
    

        <div class="sign-column w2">
        
       
            <div class="sign-column-face s2">
            @if ($message = Session::get('success'))
            <div class="alert alert-success" style="z-index: 88888 !important;" role="alert">
                {{ $message }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @elseif ($message = Session::get('error'))
                <div class="alert alert-danger " style="z-index: 88888 !important;" role="alert">
                    {{ $message }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
                <div class="s3">
                <x-subscription-warning />
                    {{ $slot }}
                </div>

            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

</body>

</html>
