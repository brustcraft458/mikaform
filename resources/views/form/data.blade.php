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

                    <!-- hdiee -->
                    <input type="hidden" id="selected-ids" name="selected_ids" value="">

                    <!-- Table with stripped rows -->
                    <table class="table datatable datatable-stream table-striped table-hover" id="table-form-data">
                        <thead>
                            <tr>
                                <th scope="col" class="no-sort">
                                    <input type="checkbox" id="select-all">
                                </th>
                                @foreach ($label_list as $label)
                                    <th scope="col">{{$label}}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dump_list as $dump)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="row-checkbox" value="{{ $dump['id'] }}">
                                    </td>
                                    @foreach ($dump['data_list'] as $data)
                                        <td>
                                            @if ($data['type'] == 'presence')
                                                @if ($data['value'] == 0)
                                                    <span class="badge bg-danger">tidak hadir</span>
                                                @else
                                                    <span class="badge bg-primary" data-bs-toggle="modal" data-bs-target="#calendar-data-{{ $dump['id'] }}">hadir {{ $data['value'] }}x</span>
                                                @endif
                                            @else
                                                {{ $data['value'] }}
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- End Table with stripped rows -->
                </div>

                
                <div>
                    @foreach ($dump_list as $dump)
                        @foreach ($dump['data_list'] as $data)
                            <!-- Modal Calerdar -->
                            @if ($data['type'] == 'presence' && !empty($data['presence_list']))
                                <div class="modal fade" id="calendar-data-{{ $dump['id'] }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title rounded">Kalender Presensi</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div>
                                                <div class="modal-body">
                                                    <div class="form-group my-3 d-flex justify-content-center align-items-center">
                                                        {{view('component.calendar', ['presence_list' => $data['presence_list']])}}
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endforeach
                </div>

                <div class="card-footer py-2">
                </div>
            </div>
        </div>
    </main>

    @include('component.footerbody')
</body>

</html>