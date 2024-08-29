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
                <h1 class="h3 mb-0 text-gray-800">Profile User</h1>
            </div>

            <!-- Content Row -->
            <div class="card shadow mb-4" style="max-height: 40rem;">
                {{-- <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Data Formulir</h6>
                </div> --}}

                <div class="table-responsive p-3">
                    <div class="w-50">
                        @if (session('action_message'))
                            <p class="alert alert-secondary">{{ session('action_message') }}</p>
                        @endif
                    </div>

                    <div class="mb-3">
                    </div>

                    <!-- Table with stripped rows -->
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Profile Details</h5>
                            @if($user)
                            <p><strong>Username:</strong> {{ $user->username }}</p>
                            <p><strong>Phone:</strong> {{ $user->phone }}</p>
                            <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
                            {{-- <p><strong>Verified At:</strong> {{ $user->verified_at ? $user->verified_at->format('d M Y H:i:s') : 'Not Verified' }}</p> --}}
                            <p><strong>OTP:</strong> {{ $user->otp ? $user->otp : 'N/A' }}</p>
                            {{-- <p><strong>Account Created At:</strong> {{ $user->created_at->format('d M Y H:i:s') }}</p> --}}
                            <p><strong>Last Updated At:</strong> {{ $user->updated_at->format('d M Y H:i:s') }}</p>
                            @else
                            <p>No user profile found. Please log in.</p>
                            @endif

                        </div>
                    </div>
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
