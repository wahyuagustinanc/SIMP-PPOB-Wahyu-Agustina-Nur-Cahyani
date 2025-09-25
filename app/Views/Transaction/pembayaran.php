<?= $this->extend('Layouts/main') ?>

<?= $this->section('content') ?>

<div class="container">
    <?= $this->include('Layouts/header') ?>
    <?= $this->include('Layouts/profile_saldo') ?>

    <div class="row">
        <h5>Pembayaran</h5>
        <div class="col-md-8">
            <img src="<?= esc($service['service_icon']) ?>" alt="<?= esc($service['service_name']) ?>" class="img-fluid mb-2" style="max-height:30px;">
            <b><?= esc($service['service_name']) ?></b>
        </div>
    </div>
    <div class="row">
        <div>
            <div class="input-group mb-3">
                <span class="input-group-text"><i class="bi bi-cash"></i></span>
                <input type="number" id="place_nominal" class="form-control" value="<?= number_format($service['service_tariff'], 0, ',', '.')  ?>">
            </div>
        </div>
    </div>
    <div class="row">
        <div>
            <button id="btnBayar" class="btn btn-danger w-100" onclick="bayar('<?= esc($service['service_name']) ?>','<?= $service['service_tariff'] ?>')">Bayar</button>
        </div>
    </div>
</div>

<?= $this->include('Partials/_modalConfirmBayar') ?>
<?= $this->include('Partials/_modalRespon') ?>

<script>
    function bayar(nama, tarif){
        document.getElementById("service_code").value = nama;
        document.getElementById("amount").value = tarif;
        document.getElementById("modalDesc").innerText = "Beli " + nama + " senilai";
        document.getElementById("modalAmount").innerText =
            "Rp" + formatRupiah(tarif);//new Intl.NumberFormat("id-ID").format(tarif);

        var myModal = new bootstrap.Modal(document.getElementById('confirmModal'));
        myModal.show();
    }

    function processPayment() {
        var serviceCode = document.getElementById("service_code").value;
        var amount      = document.getElementById("amount").value;
        
        document.getElementById("modalAmounts").innerText = "Rp" + formatRupiah(amount);//new Intl.NumberFormat("id-ID").format(amount);
        fetch("/pembayaran/process", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `service_code=${encodeURIComponent(serviceCode)}&amount=${encodeURIComponent(amount)}`
        })
        .then(res => res.json())
        .then(data => {
            bootstrap.Modal.getInstance(document.getElementById('confirmModal')).hide();

            var icon   = document.getElementById("responseIcon");
            var title  = document.getElementById("responseTitle");
            var msg    = document.getElementById("responseMessage");

            // berhasil 
            if (data.status === 0) {
                icon.innerHTML = '<i class="bi bi-check-circle-fill text-success"></i>';
                title.innerText = "Pembayaran " + serviceCode + " sebesar";
                msg.innerText = "berhasil!";
            // gagal
            } else {
                icon.innerHTML = '<i class="bi bi-x-circle-fill text-danger"></i>';
                title.innerText = "Pembayaran " + serviceCode + " sebesar";
                msg.innerText = "gagal";
            }

            var responseModal = new bootstrap.Modal(document.getElementById('responseModal'));
            responseModal.show();
        })
        .catch(err => {
            console.error(err);

            bootstrap.Modal.getInstance(document.getElementById('confirmModal')).hide();

            var icon   = document.getElementById("responseIcon");
            var title  = document.getElementById("responseTitle");
            var msg    = document.getElementById("responseMessage");
            var btnTrx = document.getElementById("btnLihatTransaksi");

            icon.innerHTML = '<i class="bi bi-x-circle-fill text-danger"></i>';
            title.innerText = "Error!";
            msg.innerText = "Tidak bisa terhubung ke server.";
            btnTrx.classList.add("d-none");

            var responseModal = new bootstrap.Modal(document.getElementById('responseModal'));
            responseModal.show();
        });
    }

</script>


<?= $this->endSection() ?>