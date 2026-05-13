<div>
    <label class="form-label">Kriteria</label>
    <select name="kriteria_id" id="{{ $prefix }}_kriteria_id" class="form-control" required>
        <option value="">-- Pilih Kriteria --</option>
        @foreach ($kriterias as $kriteria)
            <option value="{{ $kriteria->id }}">{{ $kriteria->name }}</option>
        @endforeach
    </select>
</div>

<div>
    <label class="form-label">Kode Kompetensi</label>
    <input type="text" name="kode" id="{{ $prefix }}_kode" class="form-control" placeholder="Contoh: K1.1" required>
</div>

<div>
    <label class="form-label">Nama Kompetensi</label>
    <input type="text" name="name" id="{{ $prefix }}_name" class="form-control" required>
</div>

<div>
    <label class="form-label">Bobot</label>
    <input type="number" step="0.01" name="bobot" id="{{ $prefix }}_bobot" class="form-control" required>
</div>
