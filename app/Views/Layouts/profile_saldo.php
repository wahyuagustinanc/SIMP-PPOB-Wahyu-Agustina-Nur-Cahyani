<?php
$show_saldo   = $balance['balance'] ?? 0;
$formatted   = number_format($show_saldo, 0, ',', '.');
$masked = str_repeat('●', strlen(preg_replace('/\D/', '', $formatted)));
?>

<div class="row mb-4">
        <div class="col-md-6">
            <img src="<?= $profile['profile_image']; ?>" alt="" width="55" height="55"><br>
            Selamat datang,<br>
            <p style="font-size: 1.5rem; font-weight: bold;">
                <?= esc($profile['first_name'] . ' ' . $profile['last_name']) ?>
            </p>
        </div>
        <div class="col-md-6">
            <div class="card bg-danger text-white p-3" style="background-image: url('<?= base_url('images/Background Saldo.png') ?>'); 
                    background-size: cover; 
                    background-position: center; 
                    border-radius: 12px;">
                <h6>Saldo anda</h6>
                <h3 id="saldoDisplay" class="fw-bold saldo-masked">Rp <?= esc($masked) ?></h3>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="ms-2" style="cursor:pointer;" onclick="lihatSaldo()">
                        Lihat Saldo
                        <i id="toggleIcon" class="bi bi-eye"></i>
                    </small>
                </div>
            </div>
        </div>
    </div>

<script>
    var isMasked = true;

    function lihatSaldo() {
        var saldoAsli = "Rp <?= esc($formatted) ?>";
        var saldoHide = "Rp <?= esc($masked) ?>";

        var placeSaldo = document.getElementById('saldoDisplay');
        var icon = document.getElementById('toggleIcon');

        if (isMasked) {
            placeSaldo.textContent = saldoAsli;
            placeSaldo.classList.remove('saldo-masked');
            icon.className = 'bi bi-eye-slash';
        } else {
            placeSaldo.textContent = saldoHide;
            placeSaldo.classList.add('saldo-masked');
            icon.className = 'bi bi-eye';
        }

        isMasked = !isMasked;
    }
</script>