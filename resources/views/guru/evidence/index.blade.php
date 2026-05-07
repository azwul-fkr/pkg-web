@extends('layouts.app')

@section('content')

<h2>Data Evidence</h2>

@if(session('success'))
    <p style="color:green;">{{ session('success') }}</p>
@endif

<a href="{{ route('guru.evidence.create') }}">+ Upload Evidence</a>

<table border="1" cellpadding="10">
    <tr>
        <th>File</th>
        <th>Mapel</th>
        <th>Kelas</th>
        <th>Tanggal</th>
        <th>Status</th>
    </tr>

    @foreach($evidences as $e)
    <tr>
        <td>
            <a href="{{ asset('storage/'.$e->file) }}" target="_blank">
                Lihat
            </a>
        </td>
        <td>{{ $e->subject }}</td>
        <td>{{ $e->class }}</td>
        <td>{{ $e->tanggal }}</td>
        <td>{{ $e->status }}</td>
    </tr>
    @endforeach

</table>

@endsection