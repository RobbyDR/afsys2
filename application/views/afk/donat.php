<?php
$labels = [];
$values = [];

foreach ($rows as $r) {
    $labels[] = $r['deskripsi'];
    $values[] = (float) $r['nilai'];
}
?>

<h6 class="text-center mb-3"><?= $judul ?></h6>

<div class="form-check form-switch mb-2">
    <input class="form-check-input" type="checkbox" id="toggleValue">
    <label class="form-check-label">Tampilkan Persentase</label>
</div>

<div style="height:300px">
    <canvas id="donatChart"></canvas>
</div>

<script>
    (function() {

        const canvas = document.getElementById('donatChart');
        if (!canvas) {
            console.error('Canvas donatChart tidak ditemukan');
            return;
        }

        const labels = <?= json_encode($labels) ?>;
        const rawData = <?= json_encode($values) ?>;

        if (!labels.length || !rawData.length) {
            console.warn('Data kosong');
            return;
        }

        // destroy chart lama
        if (window.donatChartInstance) {
            window.donatChartInstance.destroy();
        }

        const ctx = canvas.getContext('2d');

        window.donatChartInstance = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: rawData,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',

                onClick: (evt, elements) => {
                    if (!elements.length) return;
                    const idx = elements[0].index;
                    console.log('Klik slice:', labels[idx]);
                },

                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });

        // toggle persen / nominal
        document.getElementById('toggleValue').addEventListener('change', e => {
            const total = rawData.reduce((a, b) => a + b, 0);

            window.donatChartInstance.data.datasets[0].data =
                e.target.checked ?
                rawData.map(v => ((v / total) * 100).toFixed(1)) :
                rawData;

            window.donatChartInstance.update();
        });

    })();
</script>