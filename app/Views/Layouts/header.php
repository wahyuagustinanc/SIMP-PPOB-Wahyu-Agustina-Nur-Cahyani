<header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
    <a href="/homepage"
        class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
        <img src="<?= base_url('images/Logo.png') ?>" alt="" width="30" height="30" class="me-2">
        <span class="fs-4">SIMS PPOB</span>
    </a>
    <ul class="nav nav-pills">
        <li class="nav-item">
            <a href="/topup"
                class="nav-link text-black <?= service('uri')->getSegment(1) == 'topup' ? 'active' : '' ?>">
                Top Up
            </a>
        </li>
        <li class="nav-item">
            <a href="/transaction"
                class="nav-link text-black <?= service('uri')->getSegment(1) == 'transaction' ? 'active' : '' ?>">
                Transaction
            </a>
        </li>
        <li class="nav-item">
            <a href="/akun" class="nav-link text-black <?= service('uri')->getSegment(1) == 'akun' ? 'active' : '' ?>">
                Akun
            </a>
        </li>
    </ul>
</header>