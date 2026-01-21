<div class="container-fluid scrollarea py-3">
    <div class="row">
        <div class="col-12 mb-3 border-bottom pb-2 d-flex justify-content-between align-items-center">

            <h1 class="h3 text-white mb-0">
                <span data-feather="bar-chart-2" class="align-text-bottom"></span>
                <?= $judul ?>
            </h1>


            <div class="btn-group" role="group" aria-label="Navigasi Bulan">
                <a href="#" id="dashboard"
                    rel="noopener noreferrer"
                    class="btn btn-sm btn-outline-warning"
                    title="goto dashboard keuangan">
                    Dashboard
                </a>
                <a href="#" id="jurnal"
                    rel="noopener noreferrer"
                    class="btn btn-sm btn-outline-warning"
                    title="goto jurnal keuangan">
                    Jurnal
                </a>
                <a href="#" id="insight"
                    rel="noopener noreferrer"
                    class="btn btn-sm btn-outline-warning"
                    title="goto insight">
                    Insight
                </a>
                <a href="#" id="updaterekap"
                    rel="noopener noreferrer"
                    class="btn btn-sm btn-outline-warning"
                    title="refresh">
                    <span data-feather="refresh-cw"></span>
                </a>

            </div>
        </div>
    </div>
    <!-- ISI -->
    <div class="mb-3 d-flex align-items-center gap-2">
        <label for="scaleSelect" class="text-white">Skala:</label>
        <select id="scaleSelect" class="form-select form-select-sm w-auto">
            <option value="harian">Harian</option>
            <option value="bulanan" selected>Bulanan</option>
            <option value="tahunan">Tahunan</option>
        </select>
    </div>


    <h2>Input, Ouput, Saldo</h2>
    <canvas class="my-4 w-100" id="mainChart" width="900" height="380"></canvas>
    <h2>Posisi Keuangan 1</h2>
    <canvas class="my-4 w-100" id="akumulasiChart" width="900" height="380"></canvas>
    <h2>Posisi Keuangan 2</h2>
    <canvas class="my-4 w-100" id="akumulasi2Chart" width="900" height="380"></canvas>
</div>

<script>
    let mainChart, akumulasiChart;

    function loadChart(scale = 'bulanan') {
        fetch(`<?= site_url('afk/grafik_data') ?>/${scale}`)
            .then(r => r.json())
            .then(d => {

                // ==========================
                // DESTROY OLD CHART
                // ==========================
                if (mainChart) mainChart.destroy();
                if (akumulasiChart) akumulasiChart.destroy();

                // ==========================
                // MAIN CHART
                // ==========================
                mainChart = new Chart(document.getElementById('mainChart'), {
                    type: 'line',
                    data: {
                        labels: d.labels,
                        datasets: [{
                                label: 'Pemasukan',
                                data: d.in,
                                borderWidth: 3,
                                pointRadius: 0
                            },
                            {
                                label: 'Pengeluaran',
                                data: d.out,
                                borderWidth: 3,
                                pointRadius: 0
                            },
                            {
                                label: 'Saldo',
                                data: d.saldo,
                                borderWidth: 4,
                                borderDash: [6, 4],
                                pointRadius: 0
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        interaction: {
                            mode: 'index',
                            intersect: false
                        }
                    }
                });

                // ==========================
                // AKUMULASI CHART
                // ==========================
                akumulasiChart = new Chart(document.getElementById('akumulasiChart'), {
                    type: 'line',
                    data: {
                        labels: d.labels,
                        datasets: [{
                            label: 'Akumulasi Saldo (Dompet)',
                            data: d.akumulasi,
                            borderWidth: 4,
                            pointRadius: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        interaction: {
                            mode: 'index',
                            intersect: false
                        }
                    }
                });
            });
    }

    // ==========================
    // INIT
    // ==========================
    loadChart('bulanan');

    // ==========================
    // CHANGE SCALE
    // ==========================
    document.getElementById('scaleSelect').addEventListener('change', e => {
        loadChart(e.target.value);
    });
</script>

<script>
    let akumulasi2Chart;

    function loadAkumulasiChart(scale = 'bulanan') {
        fetch(`<?= site_url('afk/grafik_akumulasi') ?>/${scale}`)
            .then(r => r.json())
            .then(d => {

                // ==========================
                // DESTROY OLD CHART
                // ==========================
                if (akumulasi2Chart) {
                    akumulasi2Chart.destroy();
                }

                // ==========================
                // CREATE CHART
                // ==========================
                akumulasi2Chart = new Chart(
                    document.getElementById('akumulasi2Chart'), {
                        type: 'line',
                        data: {
                            labels: d.labels,
                            datasets: [{
                                label: 'Posisi Dompet',
                                data: d.akumulasi,
                                borderWidth: 4,
                                pointRadius: 0,
                                fill: false
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                mode: 'index',
                                intersect: false
                            },
                            scales: {
                                y: {
                                    beginAtZero: false,
                                    ticks: {
                                        callback: function(value) {
                                            return new Intl.NumberFormat('id-ID').format(value);
                                        }
                                    }
                                }
                            },
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        label: function(ctx) {
                                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw);
                                        }
                                    }
                                }
                            }
                        }
                    }
                );
            });
    }

    // ==========================
    // INIT
    // ==========================
    loadAkumulasiChart('bulanan');

    // ==========================
    // CHANGE SCALE (SELECT)
    // ==========================
    document.getElementById('scaleSelect').addEventListener('change', e => {
        loadAkumulasiChart(e.target.value);
    });
</script>


<script>
    $(document).ready(function() {
        $("#updaterekap").click(() => window.location.href = '<?= base_url('afk/afkupdaterekap') ?>');
        $("#insight").on("click", function() {
            window.location.href = '<?= base_url('afk/insight') ?>';
        });
        $("#jurnal").on("click", function() {
            window.location.href = '<?= base_url('afk/afkjurnal') ?>';
        });
        $("#dashboard").on("click", function() {
            window.location.href = '<?= base_url('afk') ?>';
        });
    });
</script>