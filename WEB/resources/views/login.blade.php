<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <title>Login Page</title>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Nata+Sans:wght@100..900&display=swap");

        * {
            font-family: "Nata Sans", sans-serif;
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(to right, #e2e2e2, #c9d6ff);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            border-radius: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.35);
            position: relative;
            overflow: hidden;
            width: 768px;
            max-width: 100%;
            min-height: 480px;
        }

        .container p {
            font-size: 14px;
            line-height: 20px;
            letter-spacing: 0.3px;
            margin: 20px 0;
        }

        .container span {
            font-size: 12px;
        }

        .container a {
            color: #333;
            font-size: 13px;
            text-decoration: none;
            margin: 15px 0 10px;
        }

        .container button.login {
            background-color: #512da8;
            color: #fff;
            font-size: 12px;
            padding: 10px 45px;
            border: 1px solid transparent;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-top: 10px;
            cursor: pointer;
        }

        .container form.form-login {
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 40px;
            height: 100%;
        }

        .container input {
            background-color: #eee;
            border: none;
            margin: 8px 0;
            padding: 10px 15px;
            font-size: 13px;
            border-radius: 8px;
            width: 100%;
            outline: none;
        }

        .form-container {
            position: absolute;
            top: 0;
            height: 100%;
            transition: all 0.6s ease-in-out;
        }

        .sign-in {
            left: 0;
            width: 50%;
            z-index: 2;
        }

        .container.active .sign-in {
            transform: translateX(100%);
        }

        .toggle-container {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            overflow: hidden;
            transition: all 0.6s ease-in-out;
            border-radius: 150px 0 0 100px;
            z-index: 1000;
        }

        .container.active .toggle-container {
            transform: translateX(-100%);
            border-radius: 0 150px 100px 0;
        }

        .toggle {
            height: 100%;
            background: linear-gradient(to right, #5c6bc0, #512da8);
            color: #fff;
            position: relative;
            height: 100%;
            width: 200%;
            transform: translateX(0);
            transition: all 0.6s ease-in-out;
        }

        .toggle-panel {
            position: absolute;
            width: 50%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 30px;
            text-align: center;
            top: 0;
            transform: translateX(0);
            transition: all 0.6s ease-in-out;
        }

        .guest {
            display: block;
            color: #eee;
            font-size: 14px;
            text-decoration: none;
            background-color: #00000000;
            border: none;
            margin: auto;
            margin-top: 15px;
            cursor: pointer;
        }

        .guest:hover{
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="container" id="container">
        <div class="form-container sign-in">
            <form action="/login" method="post" class="form-login">
                @csrf
                <h1 style="margin-bottom: 1rem;">Sign In!</h1>
                <span style="margin-bottom: 0.7rem;">Use your username and password.</span>
                <input type="text" name="username" placeholder="Username" autocomplete="off">
                <input type="password" name="password" placeholder="Password" autocomplete="off">
                <button type="submit" class="login">Sign In</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel">
                    <h1>Welcome Back!</h1>
                    <p>Enter your personal details to use all of site features.</p>
                    <form action="/guest" method="post">
                        @csrf
                        <input type="hidden" name="username" value="guest">
                        <input type="hidden" name="password" value="guest">
                        <button class="guest" type="submit">Try guest account?</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if ($errors->any())
        <script>
            let errorMessages = `
            @foreach ($errors->all() as $error)
                <p style="text-align:center; margin:0;"> {{ $error }} </p>
            @endforeach
        `;

            Swal.fire({
                title: "Validation Error",
                html: errorMessages,
                icon: "error",
                confirmButtonText: "OK",
                confirmButtonColor: "#1f4ed8",
                background: "#fff",
                color: "#333"
            });
        </script>
    @endif

    @if ($message = Session::get('error'))
        <script>
            Swal.fire({
                title: "{{ $message }}",
                icon: "error",
                draggable: false
            });
        </script>
    @endif
</body>

</html>
