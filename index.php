<?php
include 'koneksi.php';

$query_mhs = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM mahasiswa");
$total_mhs = mysqli_fetch_assoc($query_mhs)['total'];

$query_mk = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM mata_kuliah");
$total_mk = mysqli_fetch_assoc($query_mk)['total'];

$query_lulus = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM nilai WHERE status = 'LULUS'");
$total_lulus = mysqli_fetch_assoc($query_lulus)['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Devakad - Sistem Informasi Monitoring Nilai</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>
<body class="bg-slate-100 min-h-screen text-slate-800 flex">

  <!-- SIDEBAR -->
  <aside class="w-64 bg-slate-900 text-white flex flex-col justify-between p-4 min-h-screen shrink-0">
    <div>
      <div class="flex items-center gap-3 px-3 py-4 mb-4 border-b border-slate-800">
        <div class="bg-blue-600 p-2 rounded-xl text-white">
          <i data-lucide="graduation-cap" class="w-6 h-6"></i>
        </div>
        <div>
          <h1 class="font-bold text-lg leading-tight tracking-wide">DEVAKAD</h1>
          <p class="text-xs text-slate-400 font-medium">Monitoring Nilai</p>
        </div>
      </div>

      <nav class="space-y-1">
        <a href="index.php" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-600 text-white font-medium text-sm transition">
          <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Overview
        </a>
        <a href="mahasiswa.php" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white font-medium text-sm transition">
          <i data-lucide="users" class="w-5 h-5"></i> Data Mahasiswa
        </a>
        <a href="matkul.php" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white font-medium text-sm transition">
          <i data-lucide="book-open" class="w-5 h-5"></i> Mata Kuliah
        </a>
        <a href="dosen.php" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white font-medium text-sm transition">
          <i data-lucide="user-cog" class="w-5 h-5"></i> Data Dosen
        </a>
        <a href="input_nilai.php" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white font-medium text-sm transition">
          <i data-lucide="bar-chart-3" class="w-5 h-5"></i> Input Nilai
        </a>
        <a href="cetak_khs.php" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white font-medium text-sm transition">
          <i data-lucide="file-text" class="w-5 h-5"></i> Cetak KHS
        </a>
        <!-- Tombol Logout Dosen -->
        <a href="login.php?logout=true" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-rose-300 hover:bg-rose-950/50 hover:text-rose-200 font-medium text-sm transition mt-4 border border-rose-900/50">
          <i data-lucide="log-out" class="w-5 h-5"></i> Keluar (Logout)
        </a>
      </nav>
    </div>

    <div class="bg-gradient-to-br from-blue-900 to-indigo-950 p-4 rounded-2xl border border-blue-800/50 text-center">
      <div class="w-10 h-10 bg-blue-500/20 rounded-full flex items-center justify-center mx-auto mb-2 text-emerald-400">
        <i data-lucide="database" class="w-5 h-5"></i>
      </div>
      <p class="text-[11px] text-emerald-300 font-semibold">MySQL Connected</p>
      <p class="text-[10px] text-slate-400 mt-0.5">Database: devakad1_db</p>
    </div>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="flex-1 p-8 overflow-y-auto space-y-6">
    <div>
      <h2 class="text-2xl font-bold text-slate-900 flex items-center gap-2">
        OVERVIEW <span class="text-xs font-semibold bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full uppercase tracking-wider">Live Database</span>
      </h2>
      <p class="text-xs text-slate-500">Ringkasan data akademik mahasiswa langsung dari server MySQL.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
      <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
        <div>
          <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Mahasiswa</p>
          <h3 class="text-3xl font-bold text-slate-800 mt-1"><?php echo $total_mhs; ?></h3>
          <p class="text-[11px] text-emerald-600 font-semibold mt-1">Tersimpan di MySQL</p>
        </div>
        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
          <i data-lucide="users" class="w-6 h-6"></i>
        </div>
      </div>

      <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
        <div>
          <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Mata Kuliah</p>
          <h3 class="text-3xl font-bold text-slate-800 mt-1"><?php echo $total_mk; ?></h3>
          <p class="text-[11px] text-blue-600 font-semibold mt-1">Kurikulum Aktif</p>
        </div>
        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center">
          <i data-lucide="book-open" class="w-6 h-6"></i>
        </div>
      </div>

      <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
        <div>
          <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Nilai Terverifikasi Lulus</p>
          <h3 class="text-3xl font-bold text-slate-800 mt-1"><?php echo $total_lulus; ?></h3>
          <p class="text-[11px] text-purple-600 font-semibold mt-1">Data Transaksi Nilai</p>
        </div>
        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
          <i data-lucide="check-circle" class="w-6 h-6"></i>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-4">Statistik Kelulusan Semester</h4>
        <div class="h-60">
          <canvas id="barChart"></canvas>
        </div>
      </div>

      <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-4">Daftar Mahasiswa Terbaru</h4>
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs text-slate-600">
            <thead class="bg-slate-50 text-slate-700 font-bold border-b">
              <tr>
                <th class="py-2.5 px-3">NIM</th>
                <th class="py-2.5 px-3">Nama</th>
                <th class="py-2.5 px-3">Prodi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <?php
              $list_mhs = mysqli_query($koneksi, "SELECT * FROM mahasiswa ORDER BY id DESC LIMIT 5");
              while ($row = mysqli_fetch_assoc($list_mhs)) {
                  echo "<tr>
                          <td class='py-2.5 px-3 font-mono font-semibold'>{$row['nim']}</td>
                          <td class='py-2.5 px-3 font-bold text-slate-800'>{$row['nama']}</td>
                          <td class='py-2.5 px-3'>{$row['prodi']}</td>
                        </tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>

  <script>
    lucide.createIcons();
    const ctx = document.getElementById('barChart').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['MPTI', 'Basis Data', 'Pemrograman Web'],
        datasets: [{
          label: 'Rata-rata Nilai',
          data: [88, 82, 85],
          backgroundColor: ['#2563eb', '#10b981', '#8b5cf6'],
          borderRadius: 6
        }]
      },
      options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, max: 100 } } }
    });
  </script>
</body>
</html>