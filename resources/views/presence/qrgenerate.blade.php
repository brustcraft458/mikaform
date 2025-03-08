<!DOCTYPE html>
<html lang="en">

<head>
    @include('component.headerhead')
</head>

<body>
    <main class="d-flex flex-row">
        <div class="d-none">
            <button type="button" id="form-qr-button-trigger" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#form-qr">Trigger</button>
        </div>
    </main>

    <!-- Form Modal -->
    <div class="modal fade" id="form-qr" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex flex-row gap-1">
                        <h5 class="modal-title rounded" id="form-qr-title">QR Presensi</h5>
                    </div>
                </div>
                <form id="form-qr" action="{{ url("/presence/$uuid")}}" method="POST">
                    @csrf
                    <div>
                        <input type="hidden" name="type" value="presence">
                        <input type="hidden" name="uuid" value="{{ $uuid }}">
                    </div>
                    <div id="form-qr" class="modal-body">
                        <div id="form-qr-section">
                            <div class="form-group my-2 d-flex flex-column" id="form-qr">
                                <div class="align-self-center p-2 shadow-sm rounded mt-4" id="form-qr-image">
                                    <div id="qrcode"></div>
                                </div>
                                <div class="d-flex row align-self-center justify-content-center p-2 rounded mt-4">
                                    @if (!is_null($presence_at))
                                        <p class="text-center">Sepertinya anda sudah presensi.</p>
                                    @elseif (Auth::check() && in_array(Auth::user()->role, ['admin', 'super_admin']))
                                        <p class="text-center">Jika mengalami kendala saat presensi menggunakan kamera.</p>
                                        <input type="submit" class="btn btn-outline-primary" style="width: 200px" name="presence_admin_input" value="Hadirkan Saja">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-4">
                        </div>
                    </div>
                    <div class="modal-footer">
                    </div>
                </form>
            </div>
        </div>
    </div><!-- End Add Modal -->

    @include('component.footerbody')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const formModal = document.querySelector("#form-qr")
            if (formModal){
                const myModal = new bootstrap.Modal(formModal, {
                    backdrop: 'static',
                    keyboard: false
                });
            }

            const formTrigger = document.querySelector("#form-qr-button-trigger")
            if (formTrigger) {
                formTrigger.click()
            }

            const qrCode = document.querySelector("#qrcode")
            new ElementQRCode(qrCode, {generate: true}, {"type": "{{$type}}", "uuid": "{{$uuid}}"})
        }, { once: true });
    </script>
</body>

</html>