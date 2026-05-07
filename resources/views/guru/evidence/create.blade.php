@extends('layouts.app')

@section('content')
<h2>Upload Evidence</h2>

@if ($errors->any())
    <div style="color:red;">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<form action="{{ route('guru.evidence.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <input type="text" name="subject" placeholder="Mata Pelajaran"><br><br>

    <input type="text" name="class" placeholder="Kelas"><br><br>

    <input type="date" name="tanggal"><br><br>

    <textarea name="description" placeholder="Deskripsi"></textarea><br><br>

    <input type="file" name="file"><br><br>

    <button type="submit">Upload</button>
</form>
@endsection