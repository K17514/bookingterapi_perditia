@extends('FrontEnd.layout.headfoot')

@section('content')

@php
$userLevel = auth()->check() ? auth()->user()->level : null;
@endphp

<div class="container mt-5">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Users List</h5>
      <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">Add User</button>
    </div>

    <div class="table-responsive text-nowrap">
      <table class="table table-hover">
        <thead>
          <tr>
            <th style="width: 50px;">Foto</th>
            <th>Name</th>
            <th>Email</th>
            <th>Level</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @forelse ($users as $user)
          <tr>
            <td>
              @if($user->foto)
              <img src="{{ $user->foto ? asset('beholf/public/' . $user->foto) : asset('assets/img/avatars/1.png') }}" alt="Foto"
                style="width: 50px; height: 50px; object-fit: cover;"
                class="rounded-circle" />
              @else
              <img src="uploads/fotos/default.jpg" alt="No Photo"
                style="width: 50px; height: 50px; object-fit: cover;"
                class="rounded-circle" />
              @endif
            </td>

            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td><span class="badge bg-label-primary">{{ $user->level }}</span></td>
            <td>
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="ri ri-more-2-line"></i>
                </button>
                <ul class="dropdown-menu">
                  <li>
                    <button
                      class="dropdown-item edit-user-btn"
                      data-bs-toggle="modal"
                      data-bs-target="#editUserModal"
                      data-id="{{ $user->id }}"
                      data-name="{{ $user->name }}"
                      data-email="{{ $user->email }}"
                      data-level="{{ $user->level }}"
                      data-foto="{{ asset($user->foto) }}">
                      <i class="ri ri-pencil-line me-1"></i> Edit
                    </button>
                  </li>
                  <li>
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                      @csrf
                      @method('DELETE')
                      <button class="dropdown-item text-danger" type="submit">
                        <i class="ri ri-delete-bin-6-line me-1"></i> Delete
                      </button>
                    </form>
                  </li>
                  <li>

                    @if(in_array($userLevel, [1, 2]))
                    <button
                      class="dropdown-item btn-detail-user"
                      data-bs-toggle="modal"
                      data-bs-target="#detailUserModal"
                      data-id="{{ $user->id }}"
                      data-name="{{ $user->name }}"
                      data-email="{{ $user->email }}"
                      data-level="{{ $user->level }}"
                      data-foto="{{ asset($user->foto) }}"
                      data-created_at="{{ $user->created_at }}"
                      data-created_by="{{ $user->creator->name ?? '-' }}"
                      data-updated_at="{{ $user->updated_at }}"
                      data-updated_by="{{ $user->updater->name ?? '-' }}"
                      data-deleted_at="{{ $user->deleted_at }}"
                      data-deleted_by="{{ $user->deleter->name ?? '-' }}">
                      <i class="ri ri-information-line me-1"></i> Detail
                    </button>
                    @endif
                  </li>
                </ul>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="text-center text-danger">No users found.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="card-footer">
      {{ $users->links() }}
    </div>
  </div>
</div>

@if(auth()->check() && auth()->user()->level == 1)
<div class="container mt-5">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Deleted Users</h5>
    </div>


    <div class="table-responsive text-nowrap">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Foto</th>
            <th>Name</th>
            <th>Email</th>
            <th>Level</th>
            <th>Deleted At</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($deletedUsers as $duser)
          <tr>
            <td>
              @if($duser->foto)
              <img src="{{ asset('beholf/public/' . $duser->foto) }}" alt="Foto"
                style="width: 50px; height: 50px; object-fit: cover;"
                class="rounded-circle" />
              @else
              <img src="uploads/fotos/default.jpg"" alt=" No Photo"
                style="width: 50px; height: 50px; object-fit: cover;"
                class="rounded-circle" />
              @endif
            </td>

            <td>{{ $duser->name }}</td>
            <td>{{ $duser->email }}</td>
            <td>{{ $duser->level }}</td>
            <td>{{ $duser->deleted_at->format('Y-m-d H:i:s') }}</td>
            <td>
              <div class="dropdown">
                <button
                  type="button"
                  class="btn p-0 dropdown-toggle hide-arrow"
                  data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <i class="ri ri-more-2-line"></i>
                </button>
                <ul class="dropdown-menu">
                  <li>
                    <form
                      action="{{ route('users.restore', $duser->id) }}"
                      method="POST"
                      class="m-0">
                      @csrf
                      <button
                        type="submit"
                        class="dropdown-item"
                        onclick="return confirm('Restore this user?')">
                        <i class="ri ri-arrow-go-back-line me-1"></i> Restore
                      </button>
                    </form>
                  </li>
                  <li>
                    <form
                      action="{{ route('users.forceDelete', $duser->id) }}"
                      method="POST"
                      class="m-0">
                      @csrf
                      @method('DELETE')
                      <button
                        type="submit"
                        class="dropdown-item text-danger"
                        onclick="return confirm('Delete permanently this user?')">
                        <i class="ri ri-delete-bin-6-line me-1"></i> Delete Permanently
                      </button>
                    </form>
                  </li>
                  <li>
                    <button
                      class="dropdown-item btn-detail-user"
                      data-bs-toggle="modal"
                      data-bs-target="#detailUserModal"
                      data-id="{{ $duser->id }}"
                      data-name="{{ $duser->name }}"
                      data-email="{{ $duser->email }}"
                      data-level="{{ $duser->level }}"
                      data-foto="{{ asset($duser->foto) }}"
                      data-created_at="{{ $duser->created_at }}"
                      data-created_by="{{ $duser->creator->name ?? '-' }}"
                      data-updated_at="{{ $duser->updated_at }}"
                      data-updated_by="{{ $duser->updater->name ?? '-' }}"
                      data-deleted_at="{{ $duser->deleted_at }}"
                      data-deleted_by="{{ $duser->deleter->name ?? '-' }}">
                      <i class="ri ri-information-line me-1"></i> Detail
                    </button>

                  </li>
                </ul>
              </div>
            </td>



          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center text-muted">No deleted users found.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>


    <div class="card-footer">
      {{ $deletedUsers->links('pagination::bootstrap-5') }}
    </div>
    @endif
    {{-- Detail User Modal --}}
    <div class="modal fade" id="detailUserModal" tabindex="-1" aria-labelledby="detailUserModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="detailUserModalLabel">User Detail</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="detailUserId" />

            <div class="mb-3">
              <label for="detailName" class="form-label">Name</label>
              <input type="text" id="detailName" class="form-control" readonly>
            </div>

            <div class="mb-3">
              <label for="detailEmail" class="form-label">Email</label>
              <input type="email" id="detailEmail" class="form-control" readonly>
            </div>

            <div class="mb-3">
              <label for="detailLevel" class="form-label">Level</label>
              <input type="number" id="detailLevel" class="form-control" readonly>
            </div>

            <div class="mb-3">
              <label for="detailFoto" class="form-label">Photo</label><br>
              <img id="detailFoto" src="" alt="Foto" style="max-width: 180px; height: auto; object-fit: cover; border-radius: 8px;">
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

    {{-- Add User Modal --}}
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <form class="modal-content" action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            {{-- Your Add User form fields here --}}

            <div class="mb-3">
              <label for="name" class="form-label">Name</label>
              <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
              <label for="password_confirmation" class="form-label">Confirm Password</label>
              <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <div class="mb-3">
              <label for="level" class="form-label">Level</label>
              <input type="number" name="level" class="form-control" min="1" required>
            </div>

            <div class="mb-3">
              <label for="foto" class="form-label">Photo (optional)</label>
              <input type="file" name="foto" class="form-control">
            </div>

          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save</button>
            <button type="reset" class="btn btn-warning">Reset</button>
          </div>
        </form>
      </div>
    </div>


    {{-- Edit User Modal --}}
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <form class="modal-content" id="editUserForm" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div class="modal-header">
            <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="user_id" id="editUserId" />

            <div class="mb-3">
              <label for="editName" class="form-label">Name</label>
              <input type="text" name="name" id="editName" class="form-control" required>
            </div>

            <div class="mb-3">
              <label for="editEmail" class="form-label">Email</label>
              <input type="email" name="email" id="editEmail" class="form-control" required>
            </div>

            <div class="mb-3">
              <label for="editPassword" class="form-label">Password <small>(leave blank if unchanged)</small></label>
              <input type="password" name="password" id="editPassword" class="form-control" placeholder="Enter new password">
            </div>

            <div class="mb-3">
              <label for="editLevel" class="form-label">Level</label>
              <input type="number" name="level" id="editLevel" class="form-control" min="1" required>
            </div>

            <div class="mb-3">
              <label for="editFoto" class="form-label">Photo (optional)</label>
              <input type="file" name="foto" id="editFoto" class="form-control">
              <div id="currentFoto" class="mt-2"></div>
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
      document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.edit-user-btn').forEach(button => {
          button.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const email = this.dataset.email;
            const level = this.dataset.level;
            const foto = this.dataset.foto;

            const form = document.getElementById('editUserForm');

            // Set the form action URL
            form.action = `users/${id}`;

            // Fill form fields
            document.getElementById('editUserId').value = id;
            document.getElementById('editName').value = name;
            document.getElementById('editEmail').value = email;
            document.getElementById('editLevel').value = level;
            document.getElementById('editPassword').value = '';

            const fotoPreview = document.getElementById('currentFoto');
            const basePath = "{{ asset('beholf/public') }}/"; // Laravel will render full URL
            if (foto && foto !== 'null') {
              // if foto already starts with http, use it directly; otherwise prepend basePath
              const fotoUrl = foto.startsWith('http') ? foto : basePath + foto;
              fotoPreview.innerHTML = `<img src="${fotoUrl}" alt="Current Photo" style="height: 80px; object-fit: cover;" class="rounded">`;
            } else {
              fotoPreview.innerHTML = '<small class="text-muted">No photo uploaded</small>';
            }
          });
        });
      });
      document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-detail-user').forEach(button => {
          button.addEventListener('click', function() {
            document.getElementById('detailUserId').value = this.dataset.id ?? '';
            document.getElementById('detailFoto').src = this.dataset.foto ?? 'uploads/fotos/default.jpg"';
            document.getElementById('detailName').value = this.dataset.name ?? '-';
            document.getElementById('detailEmail').value = this.dataset.email ?? '-';
            document.getElementById('detailLevel').value = this.dataset.level ?? '-';
            document.getElementById('detailCreatedAt').value = this.dataset.created_at ?? '-';
            document.getElementById('detailCreatedBy').value = this.dataset.created_by ?? '-';
            document.getElementById('detailUpdatedAt').value = this.dataset.updated_at ?? '-';
            document.getElementById('detailUpdatedBy').value = this.dataset.updated_by ?? '-';
            document.getElementById('detailDeletedAt').value = this.dataset.deleted_at ?? '-';
            document.getElementById('detailDeletedBy').value = this.dataset.deleted_by ?? '-';
          });

        });
      });
    </script>

    @endsection