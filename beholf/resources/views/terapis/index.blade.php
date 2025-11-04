@extends('FrontEnd.layout.headfoot')

@section('content')

@php
  $userLevel = auth()->check() ? auth()->user()->level : null;
@endphp
<div class="container mt-5">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Terapis List</h5>
      <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addTerapiModal">Add Terapis</button>
    </div>

    <div class="table-responsive text-nowrap">
      <table class="table table-hover">
        <thead>
          <tr>
            <th style="width: 80px;">Foto</th>
            <th>Name</th>
            <th>Spesialis</th>
            <th>Metode</th>
            <th>Jenis Kelamin</th>
            <th>No HP</th>
            <th>Kode Terapi</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @forelse ($terapis as $t)
            <tr>
              <td>
                @if($t->foto)
                  <img src="{{ asset('beholf/public/' . $t->foto) }}" alt="Foto" style="width: 100px; height: 100px; object-fit: cover;" class="rounded">
                @else
                  <span class="text-muted">No photo</span>
                @endif
              </td>
              <td>{{ $t->name }}</td>
              <td>{{ $t->spesialis }}</td>
              <td>{{ $t->metode_terapi }}</td>
              <td>{{ $t->jenis_kelamin }}</td>
              <td>{{ $t->no_hp }}</td>
              <td>{{ $t->kode_terapi }}</td>
              <td>
                <div class="dropdown">
                  <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ri ri-more-2-line"></i>
                  </button>
                  <ul class="dropdown-menu">
                    <li>
                      <button
                        class="dropdown-item edit-terapi-btn"
                        data-bs-toggle="modal"
                        data-bs-target="#editTerapiModal"
                        data-id="{{ $t->id }}"
                        data-name="{{ $t->name }}"
                        data-email="{{ $t->user->email }}"
                        data-spesialis="{{ $t->spesialis }}"
                        data-jenis_kelamin="{{ $t->jenis_kelamin }}"
                        data-no_hp="{{ $t->no_hp }}"
                        data-kode_terapi="{{ $t->kode_terapi }}"
                        data-deskripsi="{{ $t->deskripsi }}"
                        data-foto="{{ $t->foto ? asset($t->foto) : '' }}"
                        data-metode_terapi="{{ $t->metode_terapi }}">
                        <i class="ri ri-pencil-line me-1"></i> Edit
                      </button>
                    </li>
                    <li>
                      <form action="{{ route('terapis.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this terapis?');">
                        @csrf
                        @method('DELETE')
                        <button class="dropdown-item text-danger" type="submit">
                          <i class="ri ri-delete-bin-6-line me-1"></i> Delete
                        </button>
                      </form>
                    </li>
                      @if(in_array($userLevel, [1, 2]))
                    <li>
                      <button
                        class="dropdown-item btn-detail-terapi"
                        data-bs-toggle="modal"
                        data-bs-target="#detailTerapiModal"
                        data-id="{{ $t->id }}"
                        data-name="{{ $t->name }}"
                        data-spesialis="{{ $t->spesialis }}"
                        data-jenis_kelamin="{{ $t->jenis_kelamin }}"
                        data-no_hp="{{ $t->no_hp }}"
                        data-kode_terapi="{{ $t->kode_terapi }}"
                        data-deskripsi="{{ $t->deskripsi }}"
                        data-foto="{{ $t->foto ? asset($t->foto) : '' }}"
                        data-metode_terapi="{{ $t->metode_terapi }}"
                        data-created_at="{{ $t->created_at }}"
                        data-created_by="{{ $t->creator->name ?? '-' }}"
                        data-updated_at="{{ $t->updated_at }}"
                        data-updated_by="{{ $t->updater->name ?? '-' }}"
                        data-deleted_at="{{ $t->deleted_at }}"
                        data-deleted_by="{{ $t->deleter->name ?? '-' }}"
                      >
                        <i class="ri ri-information-line me-1"></i> Detail
                      </button>
                    </li>
                    @endif
                  </ul>
                </div>
              </td>
            </tr>
          @empty
            <tr><td colspan="7" class="text-center text-danger">No terapis found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="card-footer">
      {{ $terapis->links() }}
    </div>
</div>
@if($userLevel == 1)

<div class="card mt-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Deleted Terapi</h5>
      </div>

      <div class="table-responsive text-nowrap">
        <table class="table table-hover">
        <thead>
          <tr>
            <th>Foto</th>
            <th>Name</th>
            <th>Spesialis</th>
            <th>Metode</th>
            <th>Deleted At</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($deletedTerapis as $terapi)
            <tr>
              <td>
                @if($terapi->foto)
                  <img src="{{ asset('beholf/public/' . $terapi->foto) }}" alt="Foto" style="width: 80px; height: 80px; object-fit: cover;" />
                @else
                  <small class="text-muted">No photo</small>
                @endif
              </td>
              <td>{{ $terapi->name }}</td>
              <td>{{ $terapi->spesialis }}</td>
              <td>{{ $terapi->metode_terapi }}</td>
              <td>{{ $terapi->deleted_at->format('Y-m-d H:i:s') }}</td>
              <td>
  <div class="dropdown">
    <button
      type="button"
      class="btn p-0 dropdown-toggle hide-arrow"
      data-bs-toggle="dropdown"
      aria-expanded="false"
    >
      <i class="ri ri-more-2-line"></i>
    </button>
    <ul class="dropdown-menu">
      <li>
        <form action="{{ route('terapis.restore', $terapi->id) }}" method="POST" class="m-0">
          @csrf
          <button type="submit" class="dropdown-item" onclick="return confirm('Restore this terapi?')">
            <i class="ri ri-arrow-go-back-line me-1"></i> Restore
          </button>
        </form>
      </li>
      <li>
        <form action="{{ route('terapis.forceDelete', $terapi->id) }}" method="POST" class="m-0">
          @csrf
          @method('DELETE')
          <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Delete permanently this terapi?')">
            <i class="ri ri-delete-bin-6-line me-1"></i> Delete Permanently
          </button>
        </form>
      </li>
      <li>
        <button
          type="button"
          class="dropdown-item btn-detail-terapi"
          data-bs-toggle="modal"
          data-bs-target="#detailTerapiModal"
          data-id="{{ $terapi->id }}"
          data-name="{{ $terapi->name }}"
          data-spesialis="{{ $terapi->spesialis }}"
          data-jenis_kelamin="{{ $terapi->jenis_kelamin }}"
          data-no_hp="{{ $terapi->no_hp }}"
          data-kode_terapi="{{ $terapi->kode_terapi }}"
          data-deskripsi="{{ $terapi->deskripsi }}"
          data-foto="{{ $terapi->foto ? asset($terapi->foto) : '' }}"
          data-metode_terapi="{{ $terapi->metode_terapi }}"
          data-created_at="{{ $terapi->created_at }}"
          data-created_by="{{ $terapi->creator->name ?? '-' }}"
          data-updated_at="{{ $terapi->updated_at }}"
          data-updated_by="{{ $terapi->updater->name ?? '-' }}"
          data-deleted_at="{{ $terapi->deleted_at }}"
          data-deleted_by="{{ $terapi->deleter->name ?? '-' }}"
        >
          <i class="ri ri-information-line me-1"></i> Detail
        </button>
      </li>
    </ul>
  </div>
</td>

          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center text-muted">No deleted terapis found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
      </div>

      <div class="card-footer">
        {{ $deletedTerapis->links('pagination::bootstrap-5') }}
      </div>
    </div>
@endif

{{-- Detail Terapi Modal --}}
<div class="modal fade" id="detailTerapiModal" tabindex="-1" aria-labelledby="detailTerapiModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailTerapiModalLabel">Terapi Detail</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="detailTerapiId" />

        <div class="mb-3">
          <label for="detailTerapiName" class="form-label">Name</label>
          <input type="text" id="detailTerapiName" class="form-control" readonly>
        </div>

        <div class="mb-3">
          <label for="detailTerapiSpesialis" class="form-label">Spesialis</label>
          <input type="text" id="detailTerapiSpesialis" class="form-control" readonly>
        </div>

        <div class="mb-3">
          <label for="detailTerapiJenisKelamin" class="form-label">Jenis Kelamin</label>
          <input type="text" id="detailTerapiJenisKelamin" class="form-control" readonly>
        </div>

        <div class="mb-3">
          <label for="detailTerapiNoHp" class="form-label">No HP</label>
          <input type="text" id="detailTerapiNoHp" class="form-control" readonly>
        </div>

        <div class="mb-3">
          <label for="detailTerapiKode" class="form-label">Kode Terapi</label>
          <input type="text" id="detailTerapiKode" class="form-control" readonly>
        </div>

        <div class="mb-3">
          <label for="detailTerapiDeskripsi" class="form-label">Deskripsi</label>
          <textarea id="detailTerapiDeskripsi" class="form-control" rows="3" readonly></textarea>
        </div>

        <div class="mb-3">
          <label for="detailTerapiFoto" class="form-label">Photo</label><br>
          <img id="detailTerapiFoto" src="" alt="Foto" style="max-width: 180px; height: auto; object-fit: cover; border-radius: 8px;">
        </div>

        <hr>

        <div class="mb-3">
          <label for="detailTerapiCreatedAt" class="form-label">Created At</label>
          <input type="text" id="detailTerapiCreatedAt" class="form-control" readonly>
        </div>

        <div class="mb-3">
          <label for="detailTerapiCreatedBy" class="form-label">Created By</label>
          <input type="text" id="detailTerapiCreatedBy" class="form-control" readonly>
        </div>

        <div class="mb-3">
          <label for="detailTerapiUpdatedAt" class="form-label">Updated At</label>
          <input type="text" id="detailTerapiUpdatedAt" class="form-control" readonly>
        </div>

        <div class="mb-3">
          <label for="detailTerapiUpdatedBy" class="form-label">Updated By</label>
          <input type="text" id="detailTerapiUpdatedBy" class="form-control" readonly>
        </div>

        <div class="mb-3">
          <label for="detailTerapiDeletedAt" class="form-label">Deleted At</label>
          <input type="text" id="detailTerapiDeletedAt" class="form-control" readonly>
        </div>

        <div class="mb-3">
          <label for="detailTerapiDeletedBy" class="form-label">Deleted By</label>
          <input type="text" id="detailTerapiDeletedBy" class="form-control" readonly>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



{{-- Add Terapis Modal --}}
<div class="modal fade" id="addTerapiModal" tabindex="-1" aria-labelledby="addTerapiModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form class="modal-content" action="{{ route('terapis.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="addTerapiModalLabel">Add Terapis</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {{-- Inputs for User --}}
        <div class="mb-3">
          <label for="addName" class="form-label">Name</label>
          <input type="text" name="name" id="addName" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="addEmail" class="form-label">Email</label>
          <input type="email" name="email" id="addEmail" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="addPassword" class="form-label">Password</label>
          <input type="password" name="password" id="addPassword" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="addPasswordConfirmation" class="form-label">Confirm Password</label>
          <input type="password" name="password_confirmation" id="addPasswordConfirmation" class="form-control" required>
        </div>

        {{-- Inputs for Terapis --}}
        <div class="mb-3">
          <label for="addSpesialis" class="form-label">Spesialis</label>
          <input type="text" name="spesialis" id="addSpesialis" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="addJenisKelamin" class="form-label">Jenis Kelamin</label>
          <select name="jenis_kelamin" id="addJenisKelamin" class="form-select" required>
            <option value="" selected disabled>Select gender</option>
            <option value="laki-laki">Laki-laki</option>
            <option value="perempuan">Perempuan</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="addNoHp" class="form-label">No HP</label>
          <input type="text" name="no_hp" id="addNoHp" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="addKodeTerapi" class="form-label">Kode Terapi</label>
          <input type="text" name="kode_terapi" id="addKodeTerapi" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="addDeskripsi" class="form-label">Deskripsi</label>
          <textarea name="deskripsi" id="addDeskripsi" class="form-control" rows="3"></textarea>
        </div>
        <div class="mb-3">
          <label for="addFoto" class="form-label">Photo (optional)</label>
          <input type="file" name="foto" id="addFoto" class="form-control" accept="image/*">
        </div>
        <div class="mb-3">
          <label for="addMetodeTerapi" class="form-label">Metode Terapi</label>
          <select name="metode_terapi" id="addMetodeTerapi" class="form-select" required>
            <option value="" selected disabled>Select Method</option>
            <option value="panggilan">Panggil</option>
            <option value="lokasi">Di Lokasi</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save Terapis</button>
        <button type="reset" class="btn btn-warning">Reset</button>
      </div>
    </form>
  </div>
</div>

{{-- Edit Terapis Modal --}}
<div class="modal fade" id="editTerapiModal" tabindex="-1" aria-labelledby="editTerapiModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form class="modal-content" id="editTerapiForm" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="modal-header">
        <h5 class="modal-title" id="editTerapiModalLabel">Edit Terapis</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="terapi_id" id="editTerapiId" />

        {{-- Inputs for User --}}
        <div class="mb-3">
          <label for="editName" class="form-label">Name</label>
          <input type="text" name="name" id="editName" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="editEmail" class="form-label">Email</label>
          <input type="email" name="email" id="editEmail" class="form-control" required>
        </div>

        {{-- Password left blank to keep current password --}}
        <div class="mb-3">
          <label for="editPassword" class="form-label">Password <small>(leave blank if unchanged)</small></label>
          <input type="password" name="password" id="editPassword" class="form-control" placeholder="Enter new password if you want to change">
        </div>

        {{-- Inputs for Terapis --}}
        <div class="mb-3">
          <label for="editSpesialis" class="form-label">Spesialis</label>
          <input type="text" name="spesialis" id="editSpesialis" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="editJenisKelamin" class="form-label">Jenis Kelamin</label>
          <select name="jenis_kelamin" id="editJenisKelamin" class="form-select" required>
            <option value="laki-laki">Laki-laki</option>
            <option value="perempuan">Perempuan</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="editNoHp" class="form-label">No HP</label>
          <input type="text" name="no_hp" id="editNoHp" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="editKodeTerapi" class="form-label">Kode Terapi</label>
          <input type="text" name="kode_terapi" id="editKodeTerapi" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="editDeskripsi" class="form-label">Deskripsi</label>
          <textarea name="deskripsi" id="editDeskripsi" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
          <label for="editFoto" class="form-label">Photo (optional)</label>
          <input type="file" name="foto" id="editFoto" class="form-control" accept="image/*">
          <div id="currentFoto" class="mt-2"></div>
        </div>
        <div class="mb-3">
          <label for="editMetodeTerapi" class="form-label">Metode Terapi</label>
          <select name="metode_terapi" id="editMetodeTerapi" class="form-select" required>
            <option value="panggilan">Panggil</option>
            <option value="lokasi">Di Lokasi</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Update Terapis</button>
        <button type="reset" class="btn btn-warning">Reset</button>
      </div>
    </form>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.edit-terapi-btn').forEach(button => {
      button.addEventListener('click', function () {
        const id = this.dataset.id;
        const name = this.dataset.name;
        const email =
this.dataset.email;
const spesialis = this.dataset.spesialis;
const jenisKelamin = this.dataset.jenis_kelamin;
const noHp = this.dataset.no_hp;
const kodeTerapi = this.dataset.kode_terapi;
const deskripsi = this.dataset.deskripsi;
const foto = this.dataset.foto;
    const form = document.getElementById('editTerapiForm');
    form.action = `terapis/${id}`; // adjust route if needed

    document.getElementById('editTerapiId').value = id;
    document.getElementById('editName').value = name;
    document.getElementById('editEmail').value = email;
    document.getElementById('editSpesialis').value = spesialis;
    document.getElementById('editJenisKelamin').value = jenisKelamin;
    document.getElementById('editNoHp').value = noHp;
    document.getElementById('editKodeTerapi').value = kodeTerapi;
    document.getElementById('editDeskripsi').value = deskripsi;

    // Show photo preview
    const currentFotoDiv = document.getElementById('currentFoto');
    if (foto) {
      currentFotoDiv.innerHTML = `<img src="${foto}" alt="Current Photo" style="width:120px; height:120px; object-fit:cover; border-radius:8px;">`;
    } else {
      currentFotoDiv.innerHTML = `<span class="text-muted">No current photo</span>`;
    }
    });
  });
});

   document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.btn-detail-terapi').forEach(button => {
    button.addEventListener('click', function () {
      document.getElementById('detailTerapiId').value = this.dataset.id ?? '';
      document.getElementById('detailTerapiName').value = this.dataset.name ?? '-';
      document.getElementById('detailTerapiSpesialis').value = this.dataset.spesialis ?? '-';
      document.getElementById('detailTerapiJenisKelamin').value = this.dataset.jenis_kelamin ?? '-';
      document.getElementById('detailTerapiNoHp').value = this.dataset.no_hp ?? '-';
      document.getElementById('detailTerapiKode').value = this.dataset.kode_terapi ?? '-';
      document.getElementById('detailTerapiDeskripsi').value = this.dataset.deskripsi ?? '-';
      document.getElementById('detailTerapiFoto').src = this.dataset.foto ?? '';

      document.getElementById('detailTerapiCreatedAt').value = this.dataset.created_at ?? '-';
      document.getElementById('detailTerapiCreatedBy').value = this.dataset.created_by ?? '-';
      document.getElementById('detailTerapiUpdatedAt').value = this.dataset.updated_at ?? '-';
      document.getElementById('detailTerapiUpdatedBy').value = this.dataset.updated_by ?? '-';
      document.getElementById('detailTerapiDeletedAt').value = this.dataset.deleted_at ?? '-';
      document.getElementById('detailTerapiDeletedBy').value = this.dataset.deleted_by ?? '-';

  });
});
});
</script>

@endsection
