<!DOCTYPE html>
<html lang="en">

<head>
    @include('component.headerhead')
</head>

<body>
    <main class="d-flex flex-row">
        <!-- Sidebar -->
        @include('component.sidebar', ['selected' => 'form_template'])

        <!-- Dashboard -->
        <div class="container-fluid px-5 py-3">

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Kelola User</h1>
            </div>

            <!-- Content Row -->
            <div class="card shadow mb-4" style="max-height: 40rem;">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Data Formulir</h6>
                </div>

                <div class="table-responsive p-3">
                    <div class="w-50">
                        @if (session('action_message'))
                            <p class="alert alert-secondary">{{ session('action_message') }}</p>
                        @endif
                    </div>

                    <div class="mb-3">
                    </div>

                    <!-- Table with stripped rows -->
                    <table class="table datatable datatable-stream table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Username</th>
                                <th scope="col">Role</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Verified At</th>
                                <th scope="col">Tanngal Buat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user_list as $user)
                                <tr>
                                    <td>{{ $user->username }}</td>
                                    <td>
                                        <form action="{{ route('kelola-user.ubah-role', $user->id) }}" method="POST">
                                            @csrf
                                            <select name="role" onchange="this.form.submit()" class="form-select">
                                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>
                                                    Admin</option>
                                                <option value="member" {{ $user->role == 'member' ? 'selected' : '' }}>
                                                    Member</option>
                                                <option value="super_admin"
                                                    {{ $user->role == 'super_admin' ? 'selected' : '' }}>Super Admin
                                                </option>
                                            </select>
                                        </form>
                                    </td>
                                    <td>{{ $user->phone }}</td>
                                    <td>{{ $user->verified_at ? $user->verified_at : 'Not Verified' }}</td>
                                    <td>{{ $user->verified_at ? $user->updated_at : 'Not Verified' }}</td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                    <!-- End Table with stripped rows -->
                </div>

                <div>
                </div>

                <div class="card-footer py-2">
                </div>
            </div>
        </div>
    </main>

    @include('component.footerbody')
</body>

</html>
