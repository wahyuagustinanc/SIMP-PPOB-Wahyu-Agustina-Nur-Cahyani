<?= $this->extend('Layouts/main') ?>

<?= $this->section('content') ?>

<div class="container">
    <?= $this->include('Layouts/header') ?>

    <div class="container text-center mt-5">
        <div class="mx-auto" style="max-width: 800px;">
            <form action="/akun/image" method="post" enctype="multipart/form-data">
                <label for="profileImage" style="cursor:pointer;">
                    <<?php
                    $url = $profile['profile_image'] ?? '';
                    if (strpos($url, 'null') !== false) {
                        $image = base_url('images/Profile Photo.png');
                    } else {
                        $image = $url;
                    }
                    ?>
                    <img src="<?= $image; ?>" alt="Profile Picture" id="profileImg" class="rounded-circle" width="120"
                        height="120">
                </label>
                <input type="file" id="profileImage" name="profile_image" style="display:none;"
                    onchange="uploadImage(this);">
            </form>

            <h4 class="mt-3" style="font-weight: bold;"><?= esc($profile['first_name'] . ' ' . $profile['last_name']) ?>
            </h4>

            <form action="/akun/update" method="post" class="mt-4">
                <div class="mb-3 text-start">
                    <label>Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-at"></i></span>
                        <input type="email" class="form-control" value="<?= esc($profile['email']) ?>" disabled>
                    </div>
                </div>
                <div class="mb-3 text-start">
                    <label>Nama Depan</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="first_name" id="first_name" class="form-control"
                            value="<?= esc($profile['first_name']) ?>" disabled>
                    </div>
                </div>
                <div class="mb-3 text-start">
                    <label>Nama Belakang</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="last_name" id="last_name" class="form-control"
                            value="<?= esc($profile['last_name']) ?>" disabled>
                    </div>
                </div>
                <button type="button" class="btn btn-danger w-100" onclick="updateAkun()">Edit Profil</button>
            </form>
            <a href="/logout" class="btn btn-outline-danger w-100 mt-3">Logout</a>
        </div>
    </div>

</div>

<script>
function updateAkun() {
    var inputs = document.querySelectorAll("input.form-control");

    inputs.forEach(input => {
        if (input.disabled) {
            input.disabled = false;
        }
    });

    var btn = document.querySelector("button.btn-danger");
    btn.innerText = "Simpan";
    btn.setAttribute("onclick", "saveAkun()");
}

function saveAkun() {
    var first_name = document.getElementById("first_name").value;
    var last_name = document.getElementById("last_name").value;
    console.log(first_name);

    fetch("/akun/update", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `first_name=${encodeURIComponent(first_name)}&last_name=${encodeURIComponent(last_name)}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 0) {
                alert("Profil berhasil diperbarui!");
                location.reload();
            } else {
                alert("Gagal update: " + (data.message || "Terjadi kesalahan"));
            }
        })
        .catch(err => {
            console.error(err);
            alert("Terjadi error saat menyimpan");
        });
}

function uploadImage(input) {
    var file = input.files[0];
    if (!file) return;

    if (file.size > 100 * 1024) {
        alert("Ukuran gambar maksimal 100KB");
        return;
    }

    var formData = new FormData();
    formData.append("profile_image", file);

    fetch("/akun/image", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (parseInt(data.status) === 0) {
                document.getElementById("profileImg").src = data.data.profile_image;
                alert("Foto profil berhasil diperbarui!");
            } else {
                alert("Gagal update: " + (data.message || "Terjadi kesalahan"));
            }
        })
        .catch(err => {
            console.error(err);
            alert("Terjadi error saat upload gambar");
        });
}
</script>

<?= $this->endSection() ?>
