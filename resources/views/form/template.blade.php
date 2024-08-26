<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" integrity="sha384-4LISF5TTJX/fLmGSxO53rV4miRxdg84mZsxmO8Rx5jGtp/LbrixFETvWa5a6sESd" crossorigin="anonymous">
    <link href="{{ url('/assets/css/additional.css')}} " rel="stylesheet">
    <title>Document</title>
</head>

<body>
    <main class="d-flex flex-row">
        <!-- Sidebar -->
        <?= view('component.sidebar') ?>
    
        <!-- Dashboard -->
        <div class="container-fluid px-5 py-3">
    
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Data Formulir</h1>
            </div>
    
            <!-- Content Row -->
            <div class="card shadow mb-4" style="max-height: 40rem;">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Template Formulir</h6>
                </div>

                <div class="table-responsive p-3">
                    <div class="w-50">
                        @if (session('action_message'))
                            <p class="alert alert-secondary">{{ session('action_message') }}</p>
                        @endif
                    </div>

                    <div class="mb-3">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#form-add">Tambah Formulir</button>
                    </div>

                    <!-- Table with stripped rows -->
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th scope="col">Judul</th>
                                <th scope="col">Aktivitas</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($form_list as $no => $form)
                                <tr>
                                    <td>
                                        <a href="/form/data/{{ $form['uuid'] }}" class="item-href">{{ $form['title'] }}</a>
                                    </td>
                                    <td>
                                        {{ $form['total_viewed'] }} <i class="bi {{ ($form == 'public') ? 'bi-people' : 'bi-lock' }}"></i> <i style="opacity: 0">ii</i> {{ $form['total_respondent'] }} <i class="bi bi-database-down"></i></i>
                                    </td>
                                    <td>
                                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#share-data-{{ $no }}"><i class="bi bi-share"></i></button>
                                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#edit-data-{{ $no }}"><i class="bi bi-pencil"></i></button>
                                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete-data-{{ $no }}"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- End Table with stripped rows -->
                </div>

                
                <div>
                        <!-- Modal Edit -->

                        <!-- Modal Hapus -->
                </div>

                <div class="card-footer py-2">
                </div>
            </div>
        </div>
    </main>

    <!-- Add Modal -->
    <div class="modal fade form-data-template" id="form-add" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex flex-row gap-1">
                        <h5 class="modal-title edit-text rounded" id="form-add-title">Judul Formulir</h5>
                        <i class="bi bi-pencil-fill fs-13px"></i>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-add" action="{{ url('/form/template')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div id="form-add-section">
                            <div class="form-group my-2 d-flex flex-column" id="form-add-s1">
                                <div class="align-self-center p-2 shadow-sm rounded mt-4 d-none" id="form-add-s1-image">
                                </div>
                                <div class="d-flex flex-row gap-1">
                                    <label class="col-form-label edit-text rounded" id="form-add-s1-label">Text 1:</label>
                                    <i class="bi bi-pencil-fill mt-1 fs-13px"></i>
                                </div>
                                <div class="d-flex flex-row gap-2">
                                    <input type="text" class="form-control" id="form-add-s1-input" value="Hello World" disabled>
                                    <select class="form-control w-50" id="form-add-s1-type">
                                        <option value="text">Text</option>
                                        <option value="number">Number</option>
                                        <option value="file">File</option>
                                        <option value="payment">Payment</option>
                                    </select>
                                    <button type="button" class="btn btn-outline-danger" id="form-add-s1-delete"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group my-3">
                            <button type="button" class="btn btn-outline-secondary mb-3 w-100" id="form-add-button-add">Add Item</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" id="form-add-button-submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- End Add Modal -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="{{ url('assets/js/main.js') }}"></script>
    <script src="{{ url('assets/js/element.js') }}"></script>
</body>

</html>