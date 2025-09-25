<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center p-4">
      <div class="modal-body">
        <img src="<?= base_url('images/Logo.png') ?>" alt="Logo" class="d-block mx-auto mb-3" style="width:50px;">
        <p id="modalDesc"></p>
        <h4 id="modalAmount" style="font-weight:bold;" class="mb-4"></h4>

        <input type="hidden" id="service_code">
        <input type="hidden" id="amount">

        <div class="d-flex flex-column justify-content-center">
          <a href="javascript:void(0)" class="text-danger fw-bold" style="text-decoration: none;" onclick="processPayment()">Ya, lanjutkan Bayar</a>
          <br>
          <a href="javascript:void(0)" class="text-secondary" style="text-decoration: none;" data-bs-dismiss="modal">Batalkan</a>
        </div>
      </div>
    </div>
  </div>
</div>
