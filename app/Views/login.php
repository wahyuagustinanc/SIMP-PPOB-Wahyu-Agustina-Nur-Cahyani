<?= $this->extend('Layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 left-panel">
            <div class="form-box">
                <h5 class="mb-3" style="text-align: center;"><img src="images/Logo.png" alt="Logo"> SIMS PPOB</h5>
                <h3 class="mb-4" style="text-align: center;">Masuk atau buat akun untuk memulai</h3>

                <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>

                <form method="post" action="/doLogin">
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-at"></i></span>
                            <input type="email" name="email" class="form-control" placeholder="masukan email anda"
                                value="<?= old('email') ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" class="form-control"
                                placeholder="masukkan password anda" required>
                            <small class="text-danger position-absolute"
                                style="right:0; bottom:-20px;"><?= session()->getFlashdata('passwordError') ?></small>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-red w-100">Masuk</button>
                </form>
                <p class="mt-3" style="text-align: center;">belum punya akun? registrasi <a href="/"
                        style="color:#f13c2f; text-decoration:none;">di sini</a></p>
            </div>
        </div>

        <div class="col-md-6 d-none d-md-flex align-items-center justify-content-center" style="background:#fff0f0;">
            <img src="images/Illustrasi Login.png" alt="Illustration" class="img-fluid" style="max-height:1000px;">
        </div>
    </div>
</div>

<?= $this->endSection() ?>