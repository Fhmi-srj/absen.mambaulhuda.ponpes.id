<table class="table table-hover trash-table mb-0">
    <thead>
        <tr>
            <th width="40"><input type="checkbox" id="select-all" class="form-check-input"></th>
            <th>Nama</th>
            <th>NISN</th>
            <th>Kelas</th>
            <th>Dihapus</th>
            <th width="150">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $row)
            <tr>
                <td><input type="checkbox" class="form-check-input row-checkbox" value="{{ $row->id }}"></td>
                <td class="fw-bold">{{ $row->nama_lengkap }}</td>
                <td>{{ $row->nisn ?? '-' }}</td>
                <td>{{ $row->kelas ?? '-' }}</td>
                <td>
                    <div class="deleted-info">
                        {{ date('d/m/Y H:i', strtotime($row->deleted_at)) }}
                        @if($row->deleted_by && isset($deleters[$row->deleted_by]))
                            <br>oleh {{ $deleters[$row->deleted_by] }}
                        @endif
                    </div>
                </td>
                <td>
                    <form method="POST" action="{{ route('admin.trash.restore') }}" class="d-inline">
                        @csrf
                        <input type="hidden" name="table" value="data_induk">
                        <input type="hidden" name="id" value="{{ $row->id }}">
                        <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-undo"></i></button>
                    </form>
                    <form method="POST" action="{{ route('admin.trash.permanent-delete') }}" class="d-inline"
                        onsubmit="return confirm('Hapus permanen?')">
                        @csrf
                        <input type="hidden" name="table" value="data_induk">
                        <input type="hidden" name="id" value="{{ $row->id }}">
                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-times"></i></button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center py-4 text-muted">Tidak ada data di trash</td>
            </tr>
        @endforelse
    </tbody>
</table>