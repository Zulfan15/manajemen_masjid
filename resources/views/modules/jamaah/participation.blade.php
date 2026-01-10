@extends('layouts.app')

@section('content')
<h1 class="text-xl font-bold mb-4">
Peserta Kegiatan: {{ $activity->nama_kegiatan }}
</h1>

<form method="POST" action="{{ route('kegiatan.peserta.store') }}">
@csrf
<input type="hidden" name="activity_id" value="{{ $activity->id }}">

@foreach($jamaahs as $j)
<div>
    <input type="checkbox" name="jamaah_ids[]" value="{{ $j->id }}">
    {{ $j->nama_lengkap }}
</div>
@endforeach

<button class="btn btn-success mt-3">Simpan Peserta</button>
</form>
@endsection
