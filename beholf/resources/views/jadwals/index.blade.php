@extends('FrontEnd.layout.headfoot')

@section('content')

    @php
        $userLevel = auth()->check() ? auth()->user()->level : null;
    @endphp
    <div class="container mt-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Jadwal List</h5>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addJadwalModal">Add
                    Jadwal</button>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th>Biaya</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Terapi</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($jadwals as $jadwal)
                            <tr>
                                <td>{{ $jadwal->jam_mulai }}</td>
                                <td>{{ $jadwal->jam_selesai }}</td>
                                <td>{{ $jadwal->biaya_jadwal }}</td>
                                <td>{{ $jadwal->tanggal->format('Y-m-d') }}</td>

                                <td>
                                    @if ($jadwal->status == 'available')
                                        <span class="badge bg-success">Available</span>
                                    @else
                                        <span class="badge bg-danger">Unavailable</span>
                                    @endif
                                </td>
                                <td>{{ $jadwal->terapi->name ?? 'Unknown' }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri ri-more-2-line"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <button class="dropdown-item edit-jadwal-btn" data-bs-toggle="modal"
                                                    data-bs-target="#editJadwalModal" data-id="{{ $jadwal->id }}"
                                                    data-jam_mulai="{{ $jadwal->jam_mulai }}"
                                                    data-jam_selesai="{{ $jadwal->jam_selesai }}"
                                                    data-biaya_jadwal="{{ $jadwal->biaya_jadwal }}"
                                                    data-tanggal="{{ $jadwal->tanggal->format('Y-m-d') }}"
                                                    data-status="{{ $jadwal->status }}"
                                                    data-id_terapi="{{ $jadwal->id_terapi }}"
                                                    data-terapi_name="{{ $jadwal->terapi->name ?? '' }}">
                                                    <i class="ri ri-pencil-line me-1"></i> Edit
                                                </button>

                                            </li>
                                            <li>
                                                <form action="{{ route('jadwals.destroy', $jadwal->id) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this jadwal?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="dropdown-item text-danger" type="submit">
                                                        <i class="ri ri-delete-bin-6-line me-1"></i> Delete
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item btn-detail-jadwal"
                                                    data-bs-toggle="modal" data-bs-target="#detailJadwalModal"
                                                    data-id="{{ $jadwal->id }}"
                                                    data-jam_mulai="{{ $jadwal->jam_mulai }}"
                                                    data-jam_selesai="{{ $jadwal->jam_selesai }}"
                                                    data-biaya_jadwal="{{ $jadwal->biaya_jadwal }}"
                                                    data-tanggal="{{ $jadwal->tanggal->format('Y-m-d') }}"
                                                    data-status="{{ $jadwal->status }}"
                                                    data-id_terapi="{{ $jadwal->id_terapi }}"
                                                    data-terapi_name="{{ $jadwal->terapi->name ?? '' }}"
                                                    data-created_at="{{ $jadwal->created_at }}"
                                                    data-created_by="{{ $jadwal->creator->name ?? '-' }}"
                                                    data-updated_at="{{ $jadwal->updated_at }}"
                                                    data-updated_by="{{ $jadwal->updater->name ?? '-' }}"
                                                    data-deleted_at="{{ $jadwal->deleted_at }}"
                                                    data-deleted_by="{{ $jadwal->deleter->name ?? '-' }}">
                                                    <i class="ri ri-information-line me-1"></i> Detail
                                                </button>

                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-danger">No jadwals found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
        @if (in_array($userLevel, [1, 2]))
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Deleted Jadwals</h5>
                </div>

                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Jam Mulai</th>
                                <th>Jam Selesai</th>
                                <th>Biaya</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Terapi</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($deletedJadwals as $jadwal)
                                <tr>
                                    <td>{{ $jadwal->jam_mulai }}</td>
                                    <td>{{ $jadwal->jam_selesai }}</td>
                                    <td>{{ $jadwal->biaya_jadwal }}</td>
                                    <td>{{ $jadwal->tanggal->format('Y-m-d') }}</td>
                                    <td>
                                        @if ($jadwal->status == 'available')
                                            <span class="badge bg-success">Available</span>
                                        @else
                                            <span class="badge bg-danger">Unavailable</span>
                                        @endif
                                    </td>
                                    <td>{{ $jadwal->terapi->name ?? 'Unknown' }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri ri-more-2-line"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <form action="{{ route('jadwals.restore', $jadwal->id) }}"
                                                        method="POST" class="m-0">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item"
                                                            onclick="return confirm('Restore this jadwal?')">
                                                            <i class="ri ri-arrow-go-back-line me-1"></i> Restore
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('jadwals.forceDelete', $jadwal->id) }}"
                                                        method="POST" class="m-0">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger"
                                                            onclick="return confirm('Delete permanently this jadwal?')">
                                                            <i class="ri ri-delete-bin-6-line me-1"></i> Delete Permanently
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <button type="button" class="dropdown-item btn-detail-jadwal"
                                                        data-bs-toggle="modal" data-bs-target="#detailJadwalModal"
                                                        data-id="{{ $jadwal->id }}"
                                                        data-jam_mulai="{{ $jadwal->jam_mulai }}"
                                                        data-jam_selesai="{{ $jadwal->jam_selesai }}"
                                                        data-biaya_jadwal="{{ $jadwal->biaya_jadwal }}"
                                                        data-tanggal="{{ $jadwal->tanggal->format('Y-m-d') }}"
                                                        data-status="{{ $jadwal->status }}"
                                                        data-id_terapi="{{ $jadwal->id_terapi }}"
                                                        data-terapi_name="{{ $jadwal->terapi->name ?? '' }}"
                                                        data-created_at="{{ $jadwal->created_at }}"
                                                        data-created_by="{{ $jadwal->creator->name ?? '-' }}"
                                                        data-updated_at="{{ $jadwal->updated_at }}"
                                                        data-updated_by="{{ $jadwal->updater->name ?? '-' }}"
                                                        data-deleted_at="{{ $jadwal->deleted_at }}"
                                                        data-deleted_by="{{ $jadwal->deleter->name ?? '-' }}">
                                                        <i class="ri ri-information-line me-1"></i> Detail
                                                    </button>

                                                </li>
                                            </ul>
                                        </div>
                                    </td>



                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No deleted jadwals found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    {{ $deletedJadwals->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif

        <div class="modal fade" id="detailJadwalModal" tabindex="-1" aria-labelledby="detailJadwalModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailJadwalModalLabel">Jadwal Detail</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" id="detailJadwalId" />

                        <div class="mb-3">
                            <label for="detailJamMulai" class="form-label">Jam Mulai</label>
                            <input type="time" id="detailJamMulai" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="detailJamSelesai" class="form-label">Jam Selesai</label>
                            <input type="time" id="detailJamSelesai" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="detailBiayaJadwal" class="form-label">Biaya</label>
                            <input type="text" id="detailBiayaJadwal" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="detailTanggal" class="form-label">Tanggal</label>
                            <input type="date" id="detailTanggal" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="detailStatus" class="form-label">Status</label>
                            <input type="text" id="detailStatus" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="detailTerapiName" class="form-label">Terapi</label>
                            <input type="text" id="detailTerapiName" class="form-control" readonly>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label for="detailCreatedAt" class="form-label">Created At</label>
                            <input type="text" id="detailCreatedAt" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="detailCreatedBy" class="form-label">Created By</label>
                            <input type="text" id="detailCreatedBy" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="detailUpdatedAt" class="form-label">Updated At</label>
                            <input type="text" id="detailUpdatedAt" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="detailUpdatedBy" class="form-label">Updated By</label>
                            <input type="text" id="detailUpdatedBy" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="detailDeletedAt" class="form-label">Deleted At</label>
                            <input type="text" id="detailDeletedAt" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="detailDeletedBy" class="form-label">Deleted By</label>
                            <input type="text" id="detailDeletedBy" class="form-control" readonly>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Add Jadwal Modal --}}
        <div class="modal fade" id="addJadwalModal" tabindex="-1" aria-labelledby="addJadwalModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form class="modal-content" action="{{ route('jadwals.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addJadwalModalLabel">Add Jadwal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="jam_mulai" class="form-label">Jam Mulai</label>
                                <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="jam_selesai" class="form-label">Jam Selesai</label>
                                <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="biaya_jadwal" class="form-label">Biaya</label>
                            <input type="text" name="biaya_jadwal" id="biaya_jadwal"
                                class="form-control rupiah-input" value="" placeholder="Rp 0" />

                        </div>



                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="available">Available</option>
                                <option value="unavailable">Unavailable</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="id_terapi" class="form-label">Terapi</label>

                            @if (in_array($user->level, [1, 2]))
                                {{-- Level 1 & 2: dropdown semua terapi --}}
                                <select name="id_terapi" id="id_terapi" class="form-select" required>
                                    <option value="">-- Pilih Terapi --</option>
                                    @foreach ($terapi as $t)
                                        <option value="{{ $t->id }}">{{ $t->name }}</option>
                                    @endforeach
                                </select>
                            @elseif($user->level == 3 && $terapi)
                                {{-- Level 3: hanya 1 terapi miliknya --}}
                                <input type="hidden" name="id_terapi" value="{{ $terapi->id }}">
                                <div class="form-control">{{ $terapi->name }}</div>
                            @else
                                <div class="text-danger">Tidak ada data terapi untuk akun Anda.</div>
                            @endif
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="reset" class="btn btn-warning">Reset</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Edit Jadwal Modal --}}
        <div class="modal fade" id="editJadwalModal" tabindex="-1" aria-labelledby="editJadwalModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form class="modal-content" id="editJadwalForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editJadwalModalLabel">Edit Jadwal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="jadwal_id" id="editJadwalId" />

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editJamMulai" class="form-label">Jam Mulai</label>
                                <input type="time" name="jam_mulai" id="editJamMulai" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editJamSelesai" class="form-label">Jam Selesai</label>
                                <input type="time" name="jam_selesai" id="editJamSelesai" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editBiayaJadwal" class="form-label">Biaya</label>
                                <input type="text" name="biaya_jadwal" id="editBiayaJadwal"
                                    class="form-control rupiah-input">
                            </div>
                        </div>


                        <div class="mb-3">
                            <label for="editTanggal" class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" id="editTanggal" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="editStatus" class="form-label">Status</label>
                            <select name="status" id="editStatus" class="form-select">
                                <option value="available">Available</option>
                                <option value="unavailable">Unavailable</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editIdTerapi" class="form-label">Terapi</label>
                            <input type="text" class="form-control" id="editTerapiName" readonly>
                            <input type="hidden" name="id_terapi" id="editIdTerapi" />
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="reset" class="btn btn-warning">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Edit button click listener to open modal and fill form
            document.querySelectorAll('.edit-jadwal-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const jam_mulai = this.dataset.jam_mulai;
                    const jam_selesai = this.dataset.jam_selesai;
                    const biaya_jadwal = this.dataset.biaya_jadwal;
                    const tanggal = this.dataset.tanggal;
                    const status = this.dataset.status;
                    const id_terapi = this.dataset.id_terapi;
                    const terapiName = this.dataset.terapi_name;

                    const form = document.getElementById('editJadwalForm');
                    form.action = `jadwals/${id}`;

                    document.getElementById('editJadwalId').value = id;
                    document.getElementById('editJamMulai').value = jam_mulai;
                    document.getElementById('editJamSelesai').value = jam_selesai;
                    document.getElementById('editBiayaJadwal').value = formatRupiah(biaya_jadwal
                        .toString(), 'Rp ');
                    document.getElementById('editTanggal').value = tanggal;
                    document.getElementById('editStatus').value = status;
                    document.getElementById('editIdTerapi').value = id_terapi;
                    document.getElementById('editTerapiName').value = terapiName;
                });
            });

            // Rupiah format function
            function formatRupiah(angka, prefix) {
                if (!angka) return '';
                var number_string = angka.replace(/[^,\d]/g, '').toString(),
                    split = number_string.split(','),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    var separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                return prefix == undefined ? rupiah : (rupiah ? prefix + rupiah : '');
            }

            // Format Rupiah input fields on keyup (works for both add & edit)
            document.querySelectorAll('.rupiah-input').forEach(input => {
                input.addEventListener('keyup', function(e) {
                    this.value = formatRupiah(this.value, 'Rp ');
                });
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-detail-jadwal').forEach(button => {
                button.addEventListener('click', function() {
                    document.getElementById('detailJadwalId').value = this.dataset.id ?? '';
                    document.getElementById('detailJamMulai').value = this.dataset.jam_mulai ?? '';
                    document.getElementById('detailJamSelesai').value = this.dataset.jam_selesai ??
                        '';
                    document.getElementById('detailBiayaJadwal').value = this.dataset
                        .biaya_jadwal ?? '';
                    document.getElementById('detailTanggal').value = this.dataset.tanggal ?? '';
                    document.getElementById('detailStatus').value = this.dataset.status ?? '';
                    document.getElementById('detailTerapiName').value = this.dataset.terapi_name ??
                        '';

                    document.getElementById('detailCreatedAt').value = this.dataset.created_at ??
                        '-';
                    document.getElementById('detailCreatedBy').value = this.dataset.created_by ??
                        '-';
                    document.getElementById('detailUpdatedAt').value = this.dataset.updated_at ??
                        '-';
                    document.getElementById('detailUpdatedBy').value = this.dataset.updated_by ??
                        '-';
                    document.getElementById('detailDeletedAt').value = this.dataset.deleted_at ??
                        '-';
                    document.getElementById('detailDeletedBy').value = this.dataset.deleted_by ??
                        '-';
                });
            });
        });
    </script>

@endsection
