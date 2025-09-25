<?= $this->extend('Layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-6 left-panel">
      <div class="form-box">
        <h5 class="mb-3 text-center">
          <img src="images/Logo.png" alt="Logo"> SIMS PPOB
        </h5>
        <h3 class="mb-4 text-center">Lengkapi data untuk membuat akun</h3>

        <?php if (session()->getFlashdata('error')): ?>
          <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
          <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <form method="post" action="/register">
          <div class="mb-3">
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-at"></i></span>
              <input type="email" name="email" class="form-control" placeholder="masukan email anda"
                required>
            </div>
          </div>
          <div class="mb-3">
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-person"></i></span>
              <input type="text" name="first_name" class="form-control" placeholder="nama depan" required>
            </div>
          </div>
          <div class="mb-3">
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-person"></i></span>
              <input type="text" name="last_name" class="form-control" placeholder="nama belakang"
                required>
            </div>
          </div>
          <div class="mb-3">
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-lock"></i></span>
              <input type="password" name="password" id="password" class="form-control"
                placeholder="buat password" required>
            </div>
          </div>
          <div class="mb-3 position-relative">
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-lock"></i></span>
              <input type="password" name="confirm_password" id="confirm_password" class="form-control"
                placeholder="konfirmasi password" oninput="validatePassword()" required>
              <small id="passwordMessage" class="text-danger position-absolute"
                style="right:0; bottom:-20px;"></small>
            </div>
          </div>
          <button type="submit" class="btn btn-red w-100">Registrasi</button>
        </form>
        <p class="mt-3 text-center">
          sudah punya akun? login
          <a href="/login" style="color:#f13c2f; text-decoration:none;">di sini</a>
        </p>
      </div>
    </div>

    <div class="col-md-6 d-none d-md-flex align-items-center justify-content-center" style="background:#fff0f0;">
      <img src="images/Illustrasi Login.png" alt="Illustration" class="img-fluid" style="max-height:1000px;">
    </div>
  </div>
</div>

<script>
  function validatePassword() {
    var password = document.getElementById("password");
    var confirm = document.getElementById("confirm_password");
    var message = document.getElementById("passwordMessage");
    if (!confirm) return;

    if (confirm.value !== password.value) {
      confirm.classList.add("is-invalid");
      message.textContent = "Password tidak sama";
    } else {
      confirm.classList.remove("is-invalid");
      message.textContent = "";
    }
  }
</script>
<?= $this->endSection() ?>