<?= $this->extend('Layouts/main') ?>

<?= $this->section('content') ?>

<div class="container">
    <?= $this->include('Layouts/header') ?>
    <?= $this->include('Layouts/profile_saldo') ?>

    <div class="row text-center mb-4">
        <?php foreach ($services as $service): ?>
            <div class="col-3 col-md-1 mb-3" style="cursor:pointer;" onclick="window.location.href='/pembayaran/<?= esc($service['service_code']) ?>'">
                <img src="<?= esc($service['service_icon']) ?>" alt="<?= esc($service['service_name']) ?>"
                    class="img-fluid mb-2" style="max-height:60px;">
                <div style="font-size: 0.7rem;"><?= esc($service['service_name']) ?></div>
            </div>
        <?php endforeach; ?>
    </div>

    <h6 class="mb-3">Temukan promo menarik</h6>
    <div class="d-flex overflow-auto gap-3 pb-2">
        <?php foreach ($banners as $banner): ?>
            <div class="flex-shrink-0" style="width:250px;">
                <div class="card h-100">
                    <img src="<?= esc($banner['banner_image']) ?>" class="card-img-top"
                        alt="<?= esc($banner['banner_name']) ?>">
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?= $this->endSection() ?>