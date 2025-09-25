<div class="modal fade" id="topupModal" tabindex="-1" aria-labelledby="topupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="width: 300px;">
        <div class="modal-content text-center p-3">
            <img src="<?= base_url('images/Logo.png') ?>" alt="Logo" class="d-block mx-auto mb-3" style="width:50px;">
            <p>Anda yakin untuk top up sebesar</p>
            <h4 id="modalNominal" class="mb-4">Rp0</h4>
            <input type="hidden" id='place_nominal'>
            <div class="d-flex flex-column justify-content-center">
                <a href="javascript:void(0)" class="text-danger fw-bold" style="text-decoration: none;" onclick="prosesTopup()">Ya, lanjutkan Bayar</a>
                <br>
                <a class="text-secondary" style="text-decoration: none; cursor: pointer;"
                    data-bs-dismiss="modal">Batal</a>
            </div>
        </div>
    </div>
</div>