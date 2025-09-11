@extends('layouts.app')
@section('title', 'Home')
@section('content')
    <div class="card" style="width: 20rem; margin: auto; margin-top: 4vh;">
        <img src="https://cdn.pixabay.com/photo/2023/06/07/05/14/streetphoto-8046254_960_720.jpg" class="card-img-top" alt="...">
        <div class="card-body">
            <p class="card-text" align="justify">Lorem ipsum dolor sit amet consectetur adipisicing elit. Possimus cupiditate quae repudiandae ad deleniti atque dolorum iste quam, quaerat optio blanditiis aut corrupti</p>
        </div>
    </div>
    @if ($message = Session::get('error'))
        <script>
            Swal.fire({
                title: "{{ $message }}",
                icon: "error",
                draggable: false
            });
        </script>
    @endif
@endsection
