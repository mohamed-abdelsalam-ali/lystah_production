<html lang="en">

<head>
    <link rel="stylesheet" href="stylesign.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Arimo&family=Poppins&display=swap" rel="stylesheet">
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

        <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            padding: 0;
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }

        #email,
        #password {
            display: block;
            width: 100%;
            height: calc(2.25rem + 2px);
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background: url(https://images.unsplash.com/photo-1636955669242-11b90050e9ac?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D);
            background-repeat: no-repeat;
            background-size: 110%;
            background-position: center;
        }


        .sign-in-container {
            display: flex;
            align-items: center;
            flex-direction: row-reverse;

        }

        .sign-column {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            width: 50%;
            font-size: 16px;
            height: 100vh;

        }


        .sign-in-title {
            font-weight: 600;
            font-size: 26px;
        }

        .sign-in-title {
            margin-top: 50px;
        }

        .s2 {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 50px 0;
            overflow: auto;
            background: #ffffff;
            height: 97%;
            width: 750px;
            box-shadow: 0 0 25px #00000061;
            border-radius: 8px;
        }

        .sign-in-title-alt {
            color: #000;

            padding: 10px 0;
        }

        .sign-buttons {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 12px;
            padding: 25px 0;
        }

        .login-w-button {
            display: flex;
            justify-content: center;
            align-items: center;
            text-decoration: none;
            gap: 10px;
            border: 1px solid #3676FF;
            background: #fff;
            padding: 10px;
            height: 40px;
            width: 200px;
            border-radius: 5px;
            color: #222222;
            transition: .3s ease all;
            font-size: 14px;
            box-shadow: 0 0 15px #00000020;
        }

        .login-w-button:hover {
            background: #eef4ff;
            border: 1px solid #a3c1f5;
            box-shadow: 0 0 5px #00000020;
        }

        .slice-text-c {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 0;
        }

        .slice-text {
            color: #000;
            background: #fff;
            padding: 10px;
        }

        .slicer {
            position: absolute;
            height: 1px;
            background: #e5e5e5;
            width: 500px;
            z-index: -1;
        }

        .input-container {
            display: flex;
            justify-content: center;
            margin: auto;
            gap: 20px;
            flex-direction: column;
            width: 400px;
        }

        .input-container input,
        .input-container button {
            padding: 15px;
            height: 40px;
            border-radius: 5px;
            outline: none;
            border: 1px solid #3676FF;
            box-shadow: 0 0 15px #00000020;
            transition: .3s ease all;
        }

        .input-container input:focus {
            border: 1px solid #3676FF;
            background: #eff5ff;
        }

        .input-container button {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #3676FF;
            color: #fff;
            cursor: pointer;
            transition: .3s ease all;
        }

        .input-container button:hover {
            box-shadow: 0 0 5px #00000020;
        }

        .alt-f-full {
            color: #000;
            font-size: 14px;
        }

        .alt-f {
            color: #3676FF;
            font-size: 14px;
            text-decoration: none;
        }

        .footer-s {
            position: absolute;
            bottom: 30px;
            display: flex;
            gap: 10px;

        }

        .footer-s a {
            color: #fff;
        }

        .sign-column:nth-child(2) {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-around;
            flex-direction: column;


        }

        input::placeholder {
            color: #000;

        }

        .intro-p {
            color: #252525;

            max-width: 750px;
        }

        .intro-img {

            height: 500px;
        }

        .intro-img img {
            max-width: 600px;
        }

        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }

        .canvas-logo {
            background: #fff;
            border-radius: 8px;
            width: 350px;
            box-shadow: 0 0 25px #00000061;
            margin: 10px auto;

        }

        .canvas-logo img {
            width: 100%;
        }

        .intro-title {

            font-size: 18px;
            padding: 10px 0;
            color: #fff;

        }


        .intro-title-alt {
            color: #353535;
            font-size: 16px;
        }

        .alt-f-full-ch {
            display: flex;
            align-items: center;
            font-size: 14px;
            gap: 5px;

            color: #000;
        }

        .alt-f-full-ch input {
            box-shadow: 0 0 0;
        }

        @media only screen and (max-width: 1100px) {
            body {
                background: #fff;
            }

            .s2 {
                display: flex;
                align-items: start;
                justify-content: center;
                padding: 0px;
                padding: 10px;
                padding-top: 50px;
                padding-bottom: 50px;

                background: #ffffff;
                height: auto;
                width: 100%;
                box-shadow: 0 0 0px #00000061;
                border-radius: 0px;
            }

            .sign-column:nth-child(2) {
                display: none;
            }

            .sign-column:nth-child(1) {
                width: 100% !important;
                height: auto;
            }

            .slicer {
                width: 0px !important;
            }

            .login-w-button {
                width: 100%;
            }

            .input-container {
                display: flex;
                justify-content: center;
                margin: auto;
                gap: 20px;
                flex-direction: column;
                width: 100%;
            }
        }

        @media only screen and (max-height: 675px) {
            body {
                background: #fff;
            }

            .s2 {
                display: flex;
                align-items: start;
                justify-content: center;
                padding: 0px;
                padding: 10px;
                padding-top: 50px;
                padding-bottom: 50px;

                background: #ffffff;
                height: auto;
                width: 100%;
                box-shadow: 0 0 0px #00000061;
                border-radius: 0px;
            }

            .sign-column:nth-child(2) {
                display: none;
            }

            .sign-column:nth-child(1) {
                width: 100% !important;
                height: auto;
            }

            .slicer {
                width: 0px !important;
            }

            .login-w-button {
                width: 100%;
            }

            .input-container {
                display: flex;
                justify-content: center;
                margin: auto;
                gap: 20px;
                flex-direction: column;
                width: 100%;
            }

            button {
                background-color: #dc3545 !important;
                display: block;
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="sign-in-container">
        <div class="sign-column s1">
            <div class="sign-column-face s2">
                <div class="s3">
                    <div class="sign-header-section">
                        <div class="sign-in-title">
                            Login Page
                        </div>
                        {{-- <div class="sign-in-title-alt">
                            Lorem ipsum dolor sit amet
                        </div> --}}
                    </div>
                    <div class="sign-buttons">
                        <a href="#" class="login-w-button">
                            <img width="18" height="18" src="https://img.icons8.com/color/48/google-logo.png"
                                alt="google-logo" />
                            <span>Sign in with Google</span>
                        </a>
                        <a href="#" class="login-w-button">
                            <img width="18" height="18" src="https://img.icons8.com/ios-filled/50/mac-os.png"
                                alt="mac-os" />
                            <span>Sign in with Apple</span>
                        </a>
                    </div>
                    <div class="slice-container">
                        <div class="slice-text-c">
                            <div class="slicer"></div>
                            <div class="slice-text">Or with email</div>
                        </div>
                    </div>
                    {{ $slot }}
                </div>

            </div>
        </div>

        <div class="sign-column w2">
            <div class="intro-p">
                <div class="canvas-logo">
                    {{-- <img src="{{ URL::asset('assets/EmaraLogo.png') }}" --}}
                    <img src="https://agriemara.com/images/newLogo.png"
                        alt="logo">
                </div>

                <div class="intro-content">
                    <div class="intro-title sign-in-title " style="font-size: 32px;font-weight: 700; font-family: Cairo ">
                        مجموعـــــــة شركـــــات عمــــــارة
                    </div>

                </div>
            </div>
        </div>
    </div>
</body>

</html>
