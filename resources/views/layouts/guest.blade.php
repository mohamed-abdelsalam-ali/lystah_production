<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Lystah</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">
    
    <!-- Flowbite CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="{{ URL::asset('assets/images/favicon.ico') }}">
    <!-- Custom Styles -->
    <style>
    html, body {
        height: 100%;
    }
        body {
            font-family: 'Cairo', sans-serif;
           
        }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900">
    <div class="@container flex flex-row items-center justify-center h-full w-full @md:flex-col @sm:flex-col">

        <!-- Right Side - Company Info -->
        <div class="hidden lg:block w-1/2 bg-[#2346e6] ">
            <div class="flex flex-1 justify-center p-6 pt-24">
                <div class="max-w-lg ">
                <div class="border-1 border-white mb-6">
                    <img src="{{ asset('assets/images/Lystah-Logo.png') }}" alt="Lystah Logo" class="border border-1 h-32 object-contain w-32" />
                        </div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">نظام إدارة متكامل </h1>
                    <p class="text-xl text-gray-600">إدارة أعمالك في مكان واحد</p>
                </div>
            </div>
        </div>

         <!-- Left Side - Login Form -->
        <div class="flex flex-1 items-center justify-center px-4 sm:px-6 lg:px-8">
            <div class="bg-white max-w-sm p-8 shadow-lg space-y-2 w-full">
                
                <div class="text-center">
                    <h2 class="mb-6 text-2xl text-gray-900">تسجيل الدخول</h2>
                </div>

                {{ $slot }}
            </div>
        </div>
    </div>

    <!-- Flowbite JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
</body>
</html>