<?= $this->extend('Layouts/main') ?>

<?= $this->section('content') ?>

<div class="container">
    <?= $this->include('Layouts/header') ?>
    <?= $this->include('Layouts/profile_saldo') ?>

    <div class="row">
        Silahkan masukan
        <p style="font-size: 1.5rem; font-weight: bold;">Nominal Top Up</p>
        <div class="col-md-8">
            <div class="input-group mb-3">
                <span class="input-group-text"><i class="bi bi-cash"></i></span>
                <input type="text" id="place_nominal" class="form-control" placeholder="masukan nominal Top Up"
                    min="10000" max="1000000" oninput="setBtn()">
            </div>
        </div>
        <div class="col-md-4">
            <button type="button" class="btn btn-outline-secondary btn-nominal"
                onclick="setNominal(10000)">Rp10.000</button>
            <button type="button" class="btn btn-outline-secondary btn-nominal"
                onclick="setNominal(20000)">Rp20.000</button>
            <button type="button" class="btn btn-outline-secondary btn-nominal"
                onclick="setNominal(50000)">Rp50.000</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <button id="topupBtn" class="btn btn-secondary w-100" onclick="saveTopup()" disabled>Top Up</button>
        </div>
        <div class="col-md-4">
            <button type="button" class="btn btn-outline-secondary btn-nominal"
                onclick="setNominal(100000)">Rp100.000</button>
            <button type="button" class="btn btn-outline-secondary btn-nominal"
                onclick="setNominal(250000)">Rp250.000</button>
            <button type="button" class="btn btn-outline-secondary btn-nominal"
                onclick="setNominal(500000)">Rp500.000</button>
        </div>
    </div>
</div>

<?= $this->include('Partials/_modalConfirmTopup') ?>
<?= $this->include('Partials/_modalRespon') ?>

<script>
    function setNominal(nominal) {
        document.getElementById('place_nominal').value = formatRupiah(nominal);
        // document.getElementById('place_nominal').value = Number(nominal).toLocaleString('id-ID');
        setBtn();
    }

    function setBtn() {
        var input = document.getElementById('place_nominal').value;
        var btn = document.getElementById('topupBtn');

        if (input) {
            btn.disabled = false;
            btn.classList.remove('btn-secondary');
            btn.classList.add('btn-danger');
        } else {
            btn.disabled = true;
            btn.classList.remove('btn-danger');
            btn.classList.add('btn-secondary');
        }

        // input = Number(input).toLocaleString('id-ID');
        input = input.replace(/\D/g, "")
    }

    function saveTopup() {
        var nominal = document.getElementById('place_nominal').value;
        document.getElementById('modalNominal').innerText = "Rp" + formatRupiah(nominal);//nominal.toLocaleString('id-ID');

        // Buka modal
        var topupModal = new bootstrap.Modal(document.getElementById('topupModal'));
        topupModal.show();
    }

    function prosesTopup() {
        var nominal      = document.getElementById("place_nominal").value;
        
        document.getElementById("modalAmounts").innerText = "Rp" + formatRupiah(nominal);//new Intl.NumberFormat("id-ID").format(nominal);
        fetch("/doTopup", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `nominal=${encodeURIComponent(nominal)}`
        })
        .then(res => res.json())
        .then(data => {
            bootstrap.Modal.getInstance(document.getElementById('topupModal')).hide();

            var icon   = document.getElementById("responseIcon");
            var title  = document.getElementById("responseTitle");
            var msg    = document.getElementById("responseMessage");

            // berhasil 
            if (data.status === 0) {
                icon.innerHTML = '<i class="bi bi-check-circle-fill text-success"></i>';
                title.innerText = "Top up sebesar";
                msg.innerText = "berhasil!";
            // gagal
            } else {
                icon.innerHTML = '<i class="bi bi-x-circle-fill text-danger"></i>';
                title.innerText = "Top up sebesar";
                msg.innerText = "gagal";
            }

            var responseModal = new bootstrap.Modal(document.getElementById('responseModal'));
            responseModal.show();
        })
        .catch(err => {
            console.error(err);

            bootstrap.Modal.getInstance(document.getElementById('topupModal')).hide();

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