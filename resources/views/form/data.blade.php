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
                <h1 class="h3 mb-0 text-gray-800">Data Formulir</h1>
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
                                @foreach ($label_list as $label)
                                    <th scope="col">{{$label}}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dump_list as $dump)
                                <tr>
                                    @foreach ($dump['data_list'] as $data)
                                        <td>
                                            {{ $data['value'] }}
                                        </td>
                                    @endforeach
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