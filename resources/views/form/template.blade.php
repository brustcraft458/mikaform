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
                    <table class="table datatable" id="table-form-template">
                        <thead>
                            <tr>
                                <th scope="col">Judul</th>
                                <th scope="col">Aktivitas</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($form_list as $form)
                                <tr>
                                    <td>
                                        <a href="/form/data/{{ $form['uuid'] }}" class="item-href">{{ $form['title'] }}</a>
                                    </td>
                                    <td>
                                        {{ $form['total_viewed'] }} <i class="bi {{ ($form['visibility'] == 'public') ? 'bi-globe2' : 'bi-lock' }}"></i> <i style="opacity: 0">ii</i> {{ $form['total_respondent'] }} <i class="bi bi-database-down"></i>
                                    </td>
                                    <td>
                                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#more-data-{{ $form['uuid'] }}"><i class="bi bi-three-dots-vertical"></i></button>
                                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#edit-data-{{ $form['uuid'] }}"><i class="bi bi-pencil"></i></button>
                                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete-data-{{ $form['uuid'] }}"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- End Table with stripped rows -->
                </div>

                
                <div>
                    @foreach ($form_list as $form)
                        <!-- Modal Option -->
                        <div class="modal fade" id="more-data-{{ $form['uuid'] }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title rounded" id="form-add-title">Opsi Lainnya</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form id="more-data-{{$form['uuid']}}-form" action="{{ url('/form/template')}}" method="POST">
                                        @csrf
                                        <input type="hidden" name="form_option" value="">
                                        <input type="hidden" name="uuid" value="{{ $form['uuid'] }}">
                                        <div class="modal-body">
                                            <!-- Card Selection Grid -->
                                            <div class="row row-cols-1 row-cols-md-2 g-3">
                                                @if ($form['visibility'] == 'public')
                                                    <!-- Make Private Card -->
                                                    <div class="col">
                                                        <div class="card h-100 text-center card-hover"
                                                            onclick="sendFormAction('more-data-{{$form['uuid']}}-form', 'visibility', 'private')"
                                                            style="max-width: 200px; margin: auto;">
                                                            <div class="card-body">
                                                                <div class="card-icon mb-2">
                                                                    <i class="bi bi-lock fs-4"></i>
                                                                </div>
                                                                <h5 class="card-title fs-6">Make Private</h5>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Copy Link Card -->
                                                    <div class="col">
                                                        <div class="card h-100 text-center card-hover"
                                                            onclick="copyToClipboard(`{{ url('/form/share/') }}/{{ $form['uuid'] }}`)"
                                                            style="max-width: 200px; margin: auto;">
                                                            <div class="card-body">
                                                                <div class="card-icon mb-2">
                                                                    <i class="bi bi-link fs-4"></i>
                                                                </div>
                                                                <h5 class="card-title fs-6">Salin Link</h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <!-- Make Public Card -->
                                                    <div class="col">
                                                        <div class="card h-100 text-center card-hover"
                                                            onclick="sendFormAction('more-data-{{$form['uuid']}}-form', 'visibility', 'public')"
                                                            style="max-width: 200px; margin: auto;">
                                                            <div class="card-body">
                                                                <div class="card-icon mb-2">
                                                                    <i class="bi bi-unlock fs-4"></i>
                                                                </div>
                                                                <h5 class="card-title fs-6">Make Public</h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                    <!-- Send Message Card -->
                                                    <div class="col">
                                                        <div class="card h-100 text-center card-hover"
                                                            data-bs-toggle="modal" data-bs-target="#more-message-{{ $form['uuid'] }}"
                                                            style="max-width: 200px; margin: auto;">
                                                            <div class="card-body">
                                                                <div class="card-icon mb-2">
                                                                    <i class="bi bi-send fs-4"></i>
                                                                </div>
                                                                <h5 class="card-title fs-6">Kirim Pesan</h5>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Scan Presensi Card -->
                                                    <div class="col">
                                                        <div class="card h-100 text-center card-hover"
                                                            onclick="redirectToTab(`{{ url('/presence/scan/') }}/{{ $form['uuid'] }}`)"
                                                            style="max-width: 200px; margin: auto;">
                                                            <div class="card-body">
                                                                <div class="card-icon mb-2">
                                                                    <i class="bi bi-qr-code fs-4"></i>
                                                                </div>
                                                                <h5 class="card-title fs-6">Scan Presensi</h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Option Message -->
                        <div class="modal fade" id="more-message-{{ $form['uuid'] }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title rounded">Siarkan Pesan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form id="more-message-{{ $form['uuid'] }}-form" action="{{ url('/form/template')}}" method="POST">
                                        @csrf
                                        <input type="hidden" name="form_option" value="">
                                        <input type="hidden" name="uuid" value="{{ $form['uuid'] }}">
                                        <div class="modal-body">
                                            <div class="form-group my-2 d-flex flex-column">
                                                <label class="col-form-label rounded">Filter:</label>
                                                <div class="d-flex flex-row gap-2">
                                                <select class="form-control w-50" name="search_label">
                                                    <option value="none">Tidak Perlu</option>
                                                    @foreach ($form['section_list'] as $section)
                                                        <option value="{{$section['label']}}">{{$section['label']}}</option>
                                                    @endforeach
                                                </select>
                                                    <input class="form-control" type="text" name="search_text"></input>
                                                </div>
                                            </div>
                                            <div class="form-group my-2 d-flex flex-column">
                                                <label class="col-form-label rounded">Pesan:</label>
                                                <textarea class="form-control" rows="4" name="message"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Kirim</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div><!-- End Add Modal -->


                        <!-- Modal Edit -->
                        <!-- Modal Hapus -->
                    @endforeach
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
                    <input type="hidden" name="form_add" value="">
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
                                        <option value="name">Name</option>
                                        <option value="email">Email</option>
                                        <option value="number">Number</option>
                                        <option value="phone">Phone</option>
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

    @include('component.footerbody')

    <script>
        const dataTable = $('#table-form-template')
        if (dataTable) {
            new ElementDataTable(dataTable)
        }
    </script>
</body>

</html>