@extends('FrontEnd.layout.headfoot')

@section('content')
<div class="container mt-5">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Data Kategori</h5>
      <div>
        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addKategoriModal">Add Kategori</button>
      </div>
    </div>

    <div class="table-responsive text-nowrap">
      <table class="table table-hover table-bordered">
        <thead>
          <tr>
            <th>Nama Kategori</th>
            <th style="width: 20%;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($kategoris as $kategori)
            <tr>
              <td>{{ $kategori->name_kategori }}</td>
              <td class="text-center">
                 <div class="dropdown">
                  <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ri ri-more-2-line"></i>
                  </button>
                  <ul class="dropdown-menu">
                    <li>
                      <button
                  class="dropdown-item edit-kategori-btn"
                  data-bs-toggle="modal"
                  data-bs-target="#editKategoriModal"
                  data-id="{{ $kategori->id }}"
                  data-nama="{{ $kategori->name_kategori }}">
                   <i class="ri ri-pencil-line me-1"></i> Edit
                </button>

                    </li>
                    <li>
                      <form  action="{{ route('kategoris.destroy', $kategori->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this terapis?');">
                        @csrf
                        @method('DELETE')
                        <button class="dropdown-item text-danger" type="submit" onsubmit="return confirm('Are you sure you want to delete this kategori?');"
                        >
                          <i class="ri ri-delete-bin-6-line me-1"></i> Delete
                        </button>
                      </form>
                    </li>
                  </ul>
                </div>
            
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="2" class="text-center text-danger">No kategori found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="card-footer">
      {{ $kategoris->links() }}
    </div>
  </div>
</div>

{{-- Add Kategori Modal --}}
<div class="modal fade" id="addKategoriModal" tabindex="-1" aria-labelledby="addKategoriModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" action="{{ route('kategoris.store') }}" method="POST">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="addKategoriModalLabel">Add Kategori</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="name_kategori" class="form-label">Nama Kategori</label>
          <input type="text" name="name_kategori" id="name_kategori" class="form-control" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Save</button>
        <button type="reset" class="btn btn-warning">Reset</button>
      </div>
    </form>
  </div>
</div>

{{-- Edit Kategori Modal --}}
<div class="modal fade" id="editKategoriModal" tabindex="-1" aria-labelledby="editKategoriModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" id="editKategoriForm" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-header">
        <h5 class="modal-title" id="editKategoriModalLabel">Edit Kategori</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="kategori_id" id="editKategoriId" />
        <div class="mb-3">
          <label for="edit_name_kategori" class="form-label">Nama Kategori</label>
          <input type="text" name="name_kategori" id="edit_name_kategori" class="form-control" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Update</button>
        <button type="reset" class="btn btn-warning">Reset</button>
      </div>
    </form>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const editButtons = document.querySelectorAll('.edit-kategori-btn');
    const editForm = document.getElementById('editKategoriForm');

    editButtons.forEach(button => {
      button.addEventListener('click', function () {
        const id = this.dataset.id;
        const nama = this.dataset.nama;

        // Set form action URL
        editForm.action = `/kategoris/${id}`;

        // Fill input fields
        document.getElementById('editKategoriId').value = id;
        document.getElementById('edit_name_kategori').value = nama;
      });
    });
  });
</script>
@endsection
