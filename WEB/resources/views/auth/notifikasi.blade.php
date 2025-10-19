@extends('layouts.app')
@section('title', 'Notifikasi')
@section('content')

    <div class="container py-5">
        <h2 class="mb-4">📢 Notifikasi</h2>

        @if ($notifications->isEmpty())
            <p>Tidak ada notifikasi.</p>
        @else
            <ul class="list-group">
                @foreach ($notifications as $notification)
                    <li
                        class="list-group-item d-flex justify-content-between align-items-center {{ $notification->read_at ? '' : 'list-group-item-info' }}">

                        <div>
                            <strong>{{ $notification->data['title'] ?? 'Notifikasi' }}</strong><br>{{ $notification->data['message'] ?? '' }}
                        </div>

                        <small>{{ $notification->created_at->diffForHumans() }}</small>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

@endsection
