<?php
include 'koneksi.php';

// Ambil daftar mahasiswa untuk dropdown pemilihan KHS
$list_mhs = mysqli_query($koneksi, "SELECT * FROM mahasiswa ORDER BY nim ASC");

// Tentukan mahasiswa yang sedang dipilih (default: mahasiswa pertama)
$selected_mhs_id = isset($_GET['mhs_id']) ? intval($_GET['mhs_id']) : 0;
if ($selected_mhs_id == 0) {
    $first_mhs = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT id FROM mahasiswa ORDER BY nim ASC LIMIT 1"));
    $selected_mhs_id = $first_mhs ? $first_mhs['id'] : 0;
}

// Ambil biodata mahasiswa terpilih
$detail_mhs = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM mahasiswa WHERE id = '$selected_mhs_id'"));

// Ambil daftar mata kuliah dan nilai mahasiswa tersebut dari MySQL
$query_khs = "
       SELECT mk.kode_mk, mk.nama_mk, mk.sks,
              n.tugas, n.uts, n.uas, n.nilai_akhir, n.grade, n.status
       FROM mata_kuliah mk
       LEFT JOIN nilai n ON mk.id = n.matkul_id AND n.mahasiswa_id = '$selected_mhs_id'
       ORDER BY mk.kode_mk ASC
   ";
$result_khs = mysqli_query($koneksi, $query_khs);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cetak KHS - Devakad</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
    body { font-family: 'Inter', sans-serif; }
    @media print {
      body * { visibility: hidden; }
      #printable-khs, #printable-khs * { visibility: visible; }
      #printable-khs {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        box-shadow: none !important;
        border: none !important;
      }
    }
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
        <a href="index.php" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white font-medium text-sm transition">
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
        <a href="cetak_khs.php" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-600 text-white font-medium text-sm transition">
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
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h2 class="text-2xl font-bold text-slate-900">CETAK KHS (KARTU HASIL STUDI)</h2>
        <p class="text-xs text-slate-500">Pilih mahasiswa untuk menampilkan transkrip nilai resmi dari database MySQL.</p>
      </div>
      <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold shadow-sm transition flex items-center gap-2">
        <i data-lucide="printer" class="w-4 h-4"></i> Cetak Dokumen PDF
      </button>
    </div>

    <!-- Filter Pilih Mahasiswa -->
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm max-w-4xl mx-auto">
      <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Pilih Mahasiswa</label>
      <select onchange="window.location='cetak_khs.php?mhs_id=' + this.value" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-xs rounded-xl p-2.5 font-semibold focus:ring-2 focus:ring-blue-500 outline-none">
        <?php
        while ($mhs = mysqli_fetch_assoc($list_mhs)) {
            $selected = ($mhs['id'] == $selected_mhs_id) ? 'selected' : '';
            echo "<option value='{$mhs['id']}' $selected>{$mhs['nim']} - {$mhs['nama']} ({$mhs['prodi']})</option>";
        }
        ?>
      </select>
    </div>

    <!-- KHS Printable Paper Container -->
    <?php if ($detail_mhs): ?>
    <div id="printable-khs" class="bg-white p-8 rounded-2xl border border-slate-200 shadow-md space-y-6 max-w-4xl mx-auto text-slate-800">
      <!-- Kop Dokumen -->
      <div class="flex items-center justify-between border-b-2 border-slate-900 pb-4">
        <div>
          <h3 class="text-xl font-extrabold tracking-wide uppercase text-slate-900">KARTU HASIL STUDI (KHS)</h3>
          <p class="text-xs font-semibold text-slate-500">DEVAKAD ACADEMIC SYSTEM - TAHUN AKADEMIK 2025/2026</p>
        </div>
        <div class="text-right">
          <span class="text-xs bg-slate-900 text-white px-3 py-1 rounded-full font-mono font-bold">STATUS: <?php echo $detail_mhs['status']; ?></span>
        </div>
      </div>

      <!-- Identitas Mahasiswa -->
      <div class="grid grid-cols-2 gap-4 text-xs">
        <div>
          <p class="text-slate-400 font-semibold">Nama Mahasiswa:</p>
          <p class="font-bold text-slate-800 text-sm"><?php echo $detail_mhs['nama']; ?></p>
        </div>
        <div>
          <p class="text-slate-400 font-semibold">NIM:</p>
          <p class="font-mono font-bold text-slate-800 text-sm"><?php echo $detail_mhs['nim']; ?></p>
        </div>
        <div>
          <p class="text-slate-400 font-semibold">Program Studi:</p>
          <p class="font-bold text-slate-800"><?php echo $detail_mhs['prodi']; ?></p>
        </div>
        <div>
          <p class="text-slate-400 font-semibold">Semester:</p>
          <p class="font-bold text-slate-800"><?php echo $detail_mhs['semester']; ?></p>
        </div>
      </div>

      <!-- Tabel Nilai -->
      <table class="w-full text-left text-xs border border-slate-200">
        <thead class="bg-slate-100 text-slate-800 font-bold border-b border-slate-200">
          <tr>
            <th class="py-2.5 px-3 border-r">Kode MK</th>
            <th class="py-2.5 px-3 border-r">Mata Kuliah</th>
            <th class="py-2.5 px-3 border-r text-center">SKS</th>
            <th class="py-2.5 px-3 border-r text-center">Nilai Angka</th>
            <th class="py-2.5 px-3 border-r text-center">Grade</th>
            <th class="py-2.5 px-3 border-r text-center">Bobot</th>
            <th class="py-2.5 px-3 text-center">Mutu (SKS × Bobot)</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200 font-medium">
          <?php
          $total_sks  = 0;
          $total_mutu = 0;

          if (mysqli_num_rows($result_khs) > 0) {
              while ($row = mysqli_fetch_assoc($result_khs)) {
                  $sks   = intval($row['sks']);
                  $grade = $row['grade'];

                  // Konversi Grade ke Bobot Mutu (Skala 4.00)
                  $bobot = 0.0;
                  if ($grade == 'A') $bobot = 4.0;
                  elseif ($grade == 'B') $bobot = 3.0;
                  elseif ($grade == 'C') $bobot = 2.0;
                  elseif ($grade == 'D') $bobot = 1.0;

                  $mutu = $sks * $bobot;
                  $total_sks  += $sks;
                  $total_mutu += $mutu;

                  $gradeColor = 'text-rose-600';
                  if ($grade == 'A') $gradeColor = 'text-purple-700';
                  elseif ($grade == 'B') $gradeColor = 'text-emerald-600';
                  elseif ($grade == 'C') $gradeColor = 'text-blue-600';
                  elseif ($grade == 'D') $gradeColor = 'text-amber-600';
                  ?>
                  <tr>
                    <td class="py-2 px-3 border-r font-mono"><?php echo $row['kode_mk']; ?></td>
                    <td class="py-2 px-3 border-r"><?php echo $row['nama_mk']; ?></td>
                    <td class="py-2 px-3 border-r text-center"><?php echo $sks; ?></td>
                    <td class="py-2 px-3 border-r text-center font-bold"><?php echo number_format($row['nilai_akhir'], 1); ?></td>
                    <td class="py-2 px-3 border-r text-center font-bold <?php echo $gradeColor; ?>"><?php echo $grade; ?></td>
                    <td class="py-2 px-3 border-r text-center"><?php echo number_format($bobot, 2); ?></td>
                    <td class="py-2 px-3 text-center font-semibold"><?php echo number_format($mutu, 2); ?></td>
                  </tr>
                  <?php
              }
          } else {
              echo "<tr><td colspan='7' class='py-6 text-center text-slate-400'>Belum ada data nilai yang diinput untuk mahasiswa ini. Silakan isi di menu Input Nilai terlebih dahulu.</td></tr>";
          }

          // Hitung IPS
          $ips = ($total_sks > 0) ? ($total_mutu / $total_sks) : 0.00;
          $predikat = "Kurang";
          if ($ips >= 3.51) $predikat = "Dengan Pujian (Cumlaude)";
          elseif ($ips >= 3.01) $predikat = "Sangat Memuaskan";
          elseif ($ips >= 2.51) $predikat = "Memuaskan";
          elseif ($ips >= 2.00) $predikat = "Cukup";
          ?>
        </tbody>
      </table>

      <!-- Ringkasan IPS -->
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 bg-slate-50 p-4 rounded-xl border border-slate-200">
        <div class="space-y-1">
          <p class="text-xs text-slate-500 font-semibold">Total SKS Diambil: <span class="text-slate-800 font-bold"><?php echo $total_sks; ?> SKS</span></p>
          <p class="text-xs text-slate-500 font-semibold">Total Angka Mutu: <span class="text-slate-800 font-bold"><?php echo number_format($total_mutu, 2); ?></span></p>
        </div>
        <div class="sm:text-right">
          <p class="text-xs text-slate-500 font-semibold">Indeks Prestasi Semester (IPS):</p>
          <p class="text-2xl font-extrabold text-blue-600">
            <?php echo number_format($ips, 2); ?>
            <span class="text-xs font-bold text-emerald-600">(<?php echo $predikat; ?>)</span>
          </p>
        </div>
      </div>

      <!-- Area Tanda Tangan -->
      <div class="grid grid-cols-2 pt-6 text-xs text-slate-600">
        <div></div>
        <div class="text-center">
          <p>Medan, <?php echo date('d F Y'); ?></p>
          <p class="font-semibold mt-1">Ketua Program Studi,</p>
          <div class="h-16"></div>
          <p class="font-bold text-slate-800 underline">Dr. Koordinator Akademik, M.Kom.</p>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </main>

  <script>lucide.createIcons();</script>
</body>
</html>