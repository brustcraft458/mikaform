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
    <div class="modal fade" id="form-share" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex flex-row gap-1">
                        <h5 class="modal-title rounded" id="form-share-title">{{ $title  }}</h5>
                    </div>
                </div>
                <form id="form-share" action="{{ url("/form/share/$uuid")}}" method="POST">
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
                                        <div class="d-flex flex-row gap-2">
                                            <input type="file" class="form-control" id="form-share-{{$section['id']}}-input" name="form-input-{{$section['id']}}" value="">
                                        </div>
                                    @else
                                        <div class="d-flex flex-row gap-1">
                                            <label class="col-form-label rounded" id="form-share-{{$section['id']}}-label">{{ $section['label'] }}</label>
                                        </div>
                                        <div class="d-flex flex-row gap-2">
                                            <input type="{{ $section['type'] }}" class="form-control" id="form-share-{{$section['id']}}-input" name="form-input-{{$section['id']}}" value="">
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <div class="form-group mt-4">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="form-share-button-reset">Reset</button>
                        <button type="submit" class="btn btn-primary" id="form-share-button-submit">Daftar</button>
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
</body>

</html>