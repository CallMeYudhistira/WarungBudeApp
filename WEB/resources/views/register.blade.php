<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>

<body>

    <div class="container active" id="container">
        <div class="form-container sign-up">
            <form action="/register" method="post">
                @csrf
                <h1 style="margin-bottom: 1rem;">Sign Up!</h1>
                <span style="margin-bottom: 0.7rem;">Complete the field below.</span>
                <input type="text" name="name" placeholder="Name" autocomplete="off">
                <input type="number" name="phone_number" placeholder="Phone Number" autocomplete="off">
                <input type="text" name="username" placeholder="Username" autocomplete="off">
                <input type="password" name="password" placeholder="Password" autocomplete="off">
                <button type="submit" class="register">Sign Up</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-right">
                    <h1>Hello, Friend!</h1>
                    <p>Register with your personal details to use all of site features</p>
                    <a href="/login" class="link">Already have an account?</a>
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

    @if ($message = Session::get('success'))
        <script>
            Swal.fire({
                title: "{{ $message }}",
                icon: "success",
                draggable: false
            });
        </script>
    @endif
</body>

</html>
