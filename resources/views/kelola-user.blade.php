@php
    function formatTimeAt($timeAt) {
        if (is_null($timeAt)) {
            return '<span class="badge bg-danger">tidak ada</span>';
        }

        $now = \Carbon\Carbon::now();
        $diffInMinutes = $now->diffInMinutes($timeAt);
        $diffInHours = $now->diffInHours($timeAt);
        $diffInDays = $now->diffInDays($timeAt);
        $diffInMonths = $now->diffInMonths($timeAt);

        $badgeClass = 'badge bg-secondary';
        $timeText = '';

        if ($diffInMinutes === 0) {
            $timeText = 'sekarang';
            $badgeClass = 'badge bg-success';
        } elseif ($diffInMinutes < 60) {
            $timeText = $diffInMinutes . ' menit yang lalu';
            if ($diffInMinutes <= 5) {
                $badgeClass = 'badge bg-success';
            }
        } elseif ($diffInHours < 24) {
            $timeText = $diffInHours . ' jam yang lalu';
        } elseif ($diffInDays < 30) {
            $timeText = $diffInDays . ' hari yang lalu';
        } else {
            $timeText = $diffInMonths . ' bulan yang lalu';
        }

        return '<span class="' . $badgeClass . '">' . $timeText . '</span>';
    }
@endphp


<!DOCTYPE html>
<html lang="en">

<head>
    @include('component.headerhead')
</head>

<body>
    <main class="d-flex flex-row">
        <!-- Sidebar -->
        @include('component.sidebar', ['selected' => 'user_manage'])

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
                    <table class="table datatable datatable-stream">
                        <thead>
                            <tr>
                                <th scope="col">Username</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Verified At</th>
                                <th scope="col">Updated At</th>
                                <th scope="col">Role</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user_list as $user)
                                <tr>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>{!! formatTimeAt($user->verified_at) !!}</td>
                                    <td>{!! formatTimeAt($user->updated_at) !!}</td>
                                    <td>
                                        <form action="{{ url('/user/manage') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="user_change_role" value="">
                                            <input type="hidden" name="id" value="{{ $user->id }}">
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
                                    <td>
                                        <form action="{{ url('/user/manage') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                            @csrf
                                            <input type="hidden" name="user_delete" value="">
                                            <input type="hidden" name="id" value="{{ $user->id }}">
                                            <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
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