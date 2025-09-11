<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nata+Sans:wght@100..900&display=swap" rel="stylesheet">
    <style>
        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Nata Sans";
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #0040ff, #002e8b);
        }

        .login-container {
            text-align: center;
            width: 350px;
        }

        .icon {
            font-size: 70px;
            margin-bottom: 30px;
            color: #fff;
        }

        .input-box {
            width: 100%;
            margin-bottom: 15px;
            position: relative;
        }

        .input-box i {
            position: absolute;
            top: 50%;
            left: 12px;
            transform: translateY(-50%);
            color: #ccc;
            font-size: 14px;
        }

        .input-box input {
            width: 100%;
            padding: 12px 40px;
            border-radius: 4px;
            border: 1px solid #eee;
            outline: none;
            background: transparent;
            color: #fff;
            font-size: 14px;
        }

        .input-box input::placeholder {
            color: #ccc;
        }

        .btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 4px;
            background: #eee;
            color: #1f4ed8;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn:hover {
            background: #e4e4e4;
        }

        .guest {
            display: block;
            color: #ddd;
            font-size: 14px;
            text-decoration: none;
            background-color: #00000000;
            border: none;
            margin: auto;
            margin-top: 15px;
            cursor: pointer;
        }

        .guest:hover {
            text-decoration: underline;
        }

        .d-block {
            display: block;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="icon"><i class="fas fa-shopping-cart"></i></div>
        <form action="/login" method="post">
            @csrf
            <div class="input-box">
                <i class="fas fa-user"></i>
                <input type="text" placeholder="USERNAME" name="username" autocomplete="off">
            </div>
            <div class="input-box">
                <i class="fas fa-lock"></i>
                <input type="password" placeholder="PASSWORD" name="password" autocomplete="off">
            </div>
            <button class="btn" type="submit">LOGIN</button>
        </form>
        <form action="/guest" method="post">
            @csrf
            <input type="hidden" name="username" value="guest">
            <input type="hidden" name="password" value="guest">
            <button class="guest" type="submit">Try guest account?</button>
        </form>
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
