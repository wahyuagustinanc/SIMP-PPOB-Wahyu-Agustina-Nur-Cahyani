<?= $this->extend('Layouts/main') ?>

<?= $this->section('content') ?>

<div class="container">
    <?= $this->include('Layouts/header') ?>
    <?= $this->include('Layouts/profile_saldo') ?>

    <div class="row">
        <b>Semua Transaksi</b>
        <?php if (empty($history)): ?>
            <p>Tidak ada data transaksi.</p>
        <?php else: ?>
            <div id="history-list">
                <?php foreach ($history as $his): ?>
                    <div style="border:1px solid #ddd; border-radius:6px; padding:10px; margin-bottom:10px;">
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <span style="font-size: 1.2rem; font-weight: bold;" class="<?= ($his['transaction_type'] === 'TOPUP') ? 'text-success' : 'text-danger' ?>">
                                <?= ($his['transaction_type'] === 'TOPUP') 
                                    ? '+ Rp'.number_format($his['total_amount'],0,',','.') 
                                    : '- Rp'.number_format($his['total_amount'],0,',','.') ?>
                            </span>
                            <span class="pull-right" style="font-size: 0.8rem;">
                                <?= esc($his['description']) ?>
                            </span>
                        </div>
                        <div style="font-size:12px; margin-top:4px;" class="text-secondary">
                            <?= date('d F Y H:i', strtotime($his['created_on'])) ?> WIB
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="text-center mt-4">
                <a class="text-danger" style="text-decoration: none; cursor: pointer; font-weight: bold;" onclick="showMore(<?= $offset ?? 0 ?>, <?= $limit ?? 5 ?>)">Show more</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    function showMore(offset, limit) {
        offset = offset + limit;

        fetch(`/transaction/loadMore?limit=${limit}&offset=${offset}`)
            .then(res => res.json())
            .then(data => {
                if (data.length === 0) {
                    alert("Tidak ada data lagi.");
                    return;
                }

                let container = document.getElementById('history-list');
                data.forEach(his => {
                    let div = document.createElement('div');
                    div.className = "border rounded-lg shadow-sm p-3 mb-3 bg-white";
                    div.innerHTML = `
                        <div class="d-flex justify-content-between">
                            <span class="${(his.transaction_type === 'TOPUP') ? 'text-success' : 'text-danger'}">
                                ${(his.transaction_type === 'TOPUP') 
                                    ? '+ Rp'+Number(his.total_amount).toLocaleString('id-ID') 
                                    : '- Rp'+Number(his.total_amount).toLocaleString('id-ID')}
                            </span>
                            <span>${his.description}</span>
                        </div>
                        <div class="text-muted small">
                            ${new Date(his.created_on).toLocaleDateString('id-ID', {
                                day: '2-digit', month: 'long', year: 'numeric'
                            })} ${new Date(his.created_on).toLocaleTimeString('id-ID')} WIB
                        </div>
                    `;
                    container.appendChild(div);
                });
            })
            .catch(err => console.error(err));
    }
</script>


<?= $this->endSection() ?>