@extends('FrontEnd.layout.headfoot')

@section('content')
<div class="container mt-5">
  <div class="card">
    <div class="card-header">
      <h5 class="mb-2">Users List</h5>
      <button class="btn btn-sm btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#addUserModal">Add User</button>
    </div>

    <div class="table-responsive text-nowrap">
      <table class="table table-hover">
        <thead>
          <tr>
            <th style="width: 80px;">Foto</th>
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
                  <img src="{{ asset('uploads/fotos/' . $user->foto) }}" alt="Foto" style="width: 100px; height: 100px; object-fit: cover;" class="rounded-square" />
                @else
                  <span class="text-muted">No photo</span>
                @endif
              </td>
              <td>{{ $user->name }}</td>
              <td>{{ $user->email }}</td>
              <td><span class="badge bg-label-primary">{{ $user->level }}</span></td>
              <td>
                <div class="dropdown">
                  <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <i class="ri ri-more-2-line"></i>
                  </button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('users.edit', $user->id) }}">
                      <i class="ri ri-pencil-line me-1"></i> Edit
                    </a>
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                      @csrf
                      @method('DELETE')
                      <button class="dropdown-item text-danger" type="submit">
                        <i class="ri ri-delete-bin-6-line me-1"></i> Delete
                      </button>
                    </form>
                  </div>
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
@endsection
