<!DOCTYPE html>
<html lang="en">

<head>
    @include('component.headerhead')
</head>

<body>
    <main class="d-flex flex-row">
        <div class="d-none">
            <button type="button" id="form-share-button-trigger" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#form-share">Trigger</button>
        </div>
    </main>

    <!-- Form Modal -->
    <div class="modal fade form-data-share" id="form-share" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex flex-row gap-1">
                        <h5 class="modal-title rounded" id="form-share-title">{{ $title  }}</h5>
                    </div>
                </div>
                <form id="form-share" action="{{ url("/form/share/$uuid")}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div id="form-share-section">
                            @foreach ($section_list as $section)
                                <div class="form-group my-2 d-flex flex-column" id="form-share-{{$section['id']}}">
                                    @if ($section['type'] == 'payment')
                                        <div class="align-self-center p-2 shadow-sm rounded mt-4" id="form-share-{{$section['id']}}-image">
                                            <img src="{{ url('/assets/img/qristes.png') }}" alt="">
                                        </div>
                                        <div class="d-flex flex-row gap-1">
                                            <label class="col-form-label rounded" id="form-share-{{$section['id']}}-label">{{ $section['label'] }}</label>
                                        </div>
                                    @else
                                        <div class="d-flex flex-row gap-1">
                                            <label class="col-form-label rounded" id="form-share-{{$section['id']}}-label">{{ $section['label'] }}</label>
                                        </div>
                                    @endif

                                    @if ($section['type'] == 'file' || $section['type'] == 'payment')
                                        <div class="d-flex flex-row gap-2">
                                            <input type="file" class="form-control" id="form-share-{{$section['id']}}-input" name="form-share-{{$section['id']}}-input" value="">
                                        </div>
                                    @elseif ($section['type'] == 'number' || $section['type'] == 'phone')
                                        <div class="d-flex flex-row gap-2">
                                            <input type="number" class="form-control" id="form-share-{{$section['id']}}-input" value="">
                                        </div>
                                    @elseif ($section['type'] == 'email')
                                        <div class="d-flex flex-row gap-2">
                                            <input type="email" class="form-control" id="form-share-{{$section['id']}}-input" value="">
                                        </div>
                                    @elseif ($section['type'] == 'text' || $section['type'] == 'name')
                                        <div class="d-flex flex-row gap-2">
                                            <input type="text" class="form-control" id="form-share-{{$section['id']}}-input" value="">
                                        </div>
                                    @else
                                        <div></div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <div class="form-group mt-4">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="form-share-button-reset">Reset</button>
                        <button type="button" class="btn btn-primary" id="form-share-button-submit">Daftar</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- End Add Modal -->

    @include('component.footerbody')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const formModal = document.querySelector("#form-share")
            if (formModal){
                const myModal = new bootstrap.Modal(formModal, {
                    backdrop: 'static',
                    keyboard: false
                });
            }

            const formTrigger = document.querySelector("#form-share-button-trigger")
            if (formTrigger) {
                formTrigger.click()
            }
        }, { once: true });
    </script>

    @if (session('action_message') == 'form_input_success')
        <script>
            Swal.fire({
              title: "Mantap Coy!",
              html: "Terimakasih Sudah Mendaftar di<br><b>'{{ $title }}'</b>",
              icon: "success"
            });
        </script>
    @elseif (session('action_message') == 'form_input_failed')
        <script>
            Swal.fire({
              title: "Pendaftaran Gagal!",
              text: "DEBUG_ID: invalid",
              icon: "error"
            });
        </script>
    @endif
</body>

</html>