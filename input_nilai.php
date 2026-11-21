<?php
include 'koneksi.php';

// Ambil daftar mata kuliah untuk dropdown
$list_matkul = mysqli_query($koneksi, "SELECT * FROM mata_kuliah ORDER BY id ASC");

// Tentukan mata kuliah yang sedang dipilih (default: mata kuliah pertama)
$selected_matkul_id = isset($_GET['matkul_id']) ? intval($_GET['matkul_id']) : 0;
if ($selected_matkul_id == 0) {
    $first_mk = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT id FROM mata_kuliah ORDER BY id ASC LIMIT 1"));
    $selected_matkul_id = $first_mk ? $first_mk['id'] : 0;
}

// PROSES SIMPAN / UPDATE NILAI KE MYSQL
if (isset($_POST['simpan_nilai'])) {
    $matkul_id = intval($_POST['matkul_id']);
    $mhs_ids   = $_POST['mhs_id'];
    $tugas_arr = $_POST['tugas'];
    $uts_arr   = $_POST['uts'];
    $uas_arr   = $_POST['uas'];

    for ($i = 0; $i < count($mhs_ids); $i++) {
        $mhs_id = intval($mhs_ids[$i]);
        $tugas  = floatval($tugas_arr[$i]);
        $uts    = floatval($uts_arr[$i]);
        $uas    = floatval($uas_arr[$i]);

        // Rumus Otomatis: 20% Tugas + 30% UTS + 50% UAS
        $nilai_akhir = round(($tugas * 0.20) + ($uts * 0.30) + ($uas * 0.50), 1);

        // Penentuan Grade & Status
        if ($nilai_akhir >= 85) {
            $grade = 'A'; $status = 'LULUS';
        } elseif ($nilai_akhir >= 75) {
            $grade = 'B'; $status = 'LULUS';
        } elseif ($nilai_akhir >= 65) {
            $grade = 'C'; $status = 'LULUS';
        } elseif ($nilai_akhir >= 50) {
            $grade = 'D'; $status = 'MENGULANG';
        } else {
            $grade = 'E'; $status = 'MENGULANG';
        }

        // Cek apakah data nilai mahasiswa untuk matkul ini sudah ada
        $cek = mysqli_query($koneksi, "SELECT id FROM nilai WHERE mahasiswa_id='$mhs_id' AND matkul_id='$matkul_id'");
        if (mysqli_num_rows($cek) > 0) {
            $sql = "UPDATE nilai SET tugas='$tugas', uts='$uts', uas='$uas', nilai_akhir='$nilai_akhir', grade='$grade', status='$status' 
                    WHERE mahasiswa_id='$mhs_id' AND matkul_id='$matkul_id'";
        } else {
            $sql = "INSERT INTO nilai (mahasiswa_id, matkul_id, tugas, uts, uas, nilai_akhir, grade, status) 
                    VALUES ('$mhs_id', '$matkul_id', '$tugas', '$uts', '$uas', '$nilai_akhir', '$grade', '$status')";
        }
        mysqli_query($koneksi, $sql);
    }

    echo "<script>alert('Semua perubahan nilai berhasil disimpan ke database MySQL!'); window.location='input_nilai.php?matkul_id=$matkul_id';</script>";
    exit;
}

// Ambil data mahasiswa beserta nilai yang sudah tersimpan untuk mata kuliah terpilih
$query_data = "
    SELECT m.id AS mhs_id, m.nim, m.nama, m.prodi,
           COALESCE(n.tugas, 0) AS tugas,
           COALESCE(n.uts, 0) AS uts,
           COALESCE(n.uas, 0) AS uas,
           COALESCE(n.nilai_akhir, 0) AS nilai_akhir,
           COALESCE(n.grade, 'E') AS grade,
           COALESCE(n.status, 'MENGULANG') AS status
    FROM mahasiswa m
    LEFT JOIN nilai n ON m.id = n.mahasiswa_id AND n.matkul_id = '$selected_matkul_id'
    WHERE m.status = 'AKTIF'
    ORDER BY m.nim ASC
";
$result_nilai = mysqli_query($koneksi, $query_data);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Input Nilai - Devakad</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
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
        <a href="input_nilai.php" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-600 text-white font-medium text-sm transition">
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
    <form method="POST" action="input_nilai.php?matkul_id=<?php echo $selected_matkul_id; ?>" class="space-y-6">
      <input type="hidden" name="matkul_id" value="<?php echo $selected_matkul_id; ?>">

      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h2 class="text-2xl font-bold text-slate-900">INPUT & EVALUASI NILAI</h2>
          <p class="text-xs text-slate-500">Bobot Penilaian: 20% Tugas + 30% UTS + 50% UAS (Tersimpan ke MySQL).</p>
        </div>
        <button type="submit" name="simpan_nilai" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold shadow-sm transition flex items-center gap-2">
          <i data-lucide="save" class="w-4 h-4"></i> Simpan Semua Nilai
        </button>
      </div>

      <!-- Filter Mata Kuliah -->
      <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
        <div>
          <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Pilih Mata Kuliah</label>
          <select onchange="window.location='input_nilai.php?matkul_id=' + this.value" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-xs rounded-xl p-2.5 font-semibold focus:ring-2 focus:ring-blue-500 outline-none">
            <?php
            while ($mk = mysqli_fetch_assoc($list_matkul)) {
                $selected = ($mk['id'] == $selected_matkul_id) ? 'selected' : '';
                echo "<option value='{$mk['id']}' $selected>{$mk['kode_mk']} - {$mk['nama_mk']} ({$mk['sks']} SKS)</option>";
            }
            ?>
          </select>
        </div>
        <div>
          <div class="bg-blue-50 text-blue-700 text-xs font-semibold p-2.5 rounded-xl flex items-center justify-center border border-blue-100">
            Formula Otomatis: (0.2 × Tugas) + (0.3 × UTS) + (0.5 × UAS)
          </div>
        </div>
      </div>

      <!-- Tabel Input Nilai -->
      <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs text-slate-600">
            <thead class="bg-slate-900 text-white uppercase text-[10px] tracking-wider">
              <tr>
                <th class="py-3.5 px-4">NIM</th>
                <th class="py-3.5 px-4">Nama Mahasiswa</th>
                <th class="py-3.5 px-4 text-center w-28">Tugas (20%)</th>
                <th class="py-3.5 px-4 text-center w-28">UTS (30%)</th>
                <th class="py-3.5 px-4 text-center w-28">UAS (50%)</th>
                <th class="py-3.5 px-4 text-center">Nilai Akhir</th>
                <th class="py-3.5 px-4 text-center">Grade</th>
                <th class="py-3.5 px-4 text-center">Status</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <?php
              if (mysqli_num_rows($result_nilai) > 0) {
                  $idx = 0;
                  while ($row = mysqli_fetch_assoc($result_nilai)) {
                      $idx++;
                      $grade = $row['grade'];
                      $status = $row['status'];

                      $gradeBadge = 'bg-rose-100 text-rose-700';
                      if ($grade == 'A') $gradeBadge = 'bg-purple-100 text-purple-700';
                      elseif ($grade == 'B') $gradeBadge = 'bg-emerald-100 text-emerald-700';
                      elseif ($grade == 'C') $gradeBadge = 'bg-blue-100 text-blue-700';
                      elseif ($grade == 'D') $gradeBadge = 'bg-amber-100 text-amber-700';

                      $statusBadge = ($status == 'LULUS') ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700';
                      ?>
                      <tr class="hover:bg-slate-50 transition">
                        <td class="py-3 px-4 font-mono font-bold text-slate-700">
                          <?php echo $row['nim']; ?>
                          <input type="hidden" name="mhs_id[]" value="<?php echo $row['mhs_id']; ?>">
                        </td>
                        <td class="py-3 px-4 font-bold text-slate-800"><?php echo $row['nama']; ?></td>
                        <td class="py-3 px-4">
                          <input type="number" step="0.1" min="0" max="100" name="tugas[]" value="<?php echo $row['tugas']; ?>" oninput="hitungNilai(<?php echo $idx; ?>)" id="tugas-<?php echo $idx; ?>" class="w-full text-center bg-slate-50 border border-slate-200 rounded-lg p-1.5 font-semibold focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                        </td>
                        <td class="py-3 px-4">
                          <input type="number" step="0.1" min="0" max="100" name="uts[]" value="<?php echo $row['uts']; ?>" oninput="hitungNilai(<?php echo $idx; ?>)" id="uts-<?php echo $idx; ?>" class="w-full text-center bg-slate-50 border border-slate-200 rounded-lg p-1.5 font-semibold focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                        </td>
                        <td class="py-3 px-4">
                          <input type="number" step="0.1" min="0" max="100" name="uas[]" value="<?php echo $row['uas']; ?>" oninput="hitungNilai(<?php echo $idx; ?>)" id="uas-<?php echo $idx; ?>" class="w-full text-center bg-slate-50 border border-slate-200 rounded-lg p-1.5 font-semibold focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                        </td>
                        <td class="py-3 px-4 text-center font-bold text-slate-800 text-sm" id="akhir-<?php echo $idx; ?>"><?php echo number_format($row['nilai_akhir'], 1); ?></td>
                        <td class="py-3 px-4 text-center" id="grade-<?php echo $idx; ?>">
                          <span class="<?php echo $gradeBadge; ?> px-2.5 py-1 rounded-full font-bold"><?php echo $grade; ?></span>
                        </td>
                        <td class="py-3 px-4 text-center" id="status-<?php echo $idx; ?>">
                          <span class="<?php echo $statusBadge; ?> px-2 py-0.5 rounded-md font-bold text-[10px]"><?php echo $status; ?></span>
                        </td>
                      </tr>
                      <?php
                  }
              } else {
                  echo "<tr><td colspan='8' class='py-6 text-center text-slate-400'>Belum ada mahasiswa aktif.</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </form>
  </main>

  <script>
    lucide.createIcons();

    function hitungNilai(id) {
      const tugas = parseFloat(document.getElementById(`tugas-${id}`).value) || 0;
      const uts   = parseFloat(document.getElementById(`uts-${id}`).value) || 0;
      const uas   = parseFloat(document.getElementById(`uas-${id}`).value) || 0;

      const nilaiAkhir = (tugas * 0.20) + (uts * 0.30) + (uas * 0.50);
      document.getElementById(`akhir-${id}`).innerText = nilaiAkhir.toFixed(1);

      let grade = 'E'; let gradeColor = 'bg-rose-100 text-rose-700'; let status = 'MENGULANG'; let statusColor = 'bg-rose-100 text-rose-700';
      if (nilaiAkhir >= 85) { grade = 'A'; gradeColor = 'bg-purple-100 text-purple-700'; status = 'LULUS'; statusColor = 'bg-emerald-100 text-emerald-700'; }
      else if (nilaiAkhir >= 75) { grade = 'B'; gradeColor = 'bg-emerald-100 text-emerald-700'; status = 'LULUS'; statusColor = 'bg-emerald-100 text-emerald-700'; }
      else if (nilaiAkhir >= 65) { grade = 'C'; gradeColor = 'bg-blue-100 text-blue-700'; status = 'LULUS'; statusColor = 'bg-emerald-100 text-emerald-700'; }
      else if (nilaiAkhir >= 50) { grade = 'D'; gradeColor = 'bg-amber-100 text-amber-700'; status = 'MENGULANG'; statusColor = 'bg-rose-100 text-rose-700'; }

      document.getElementById(`grade-${id}`).innerHTML = `<span class="${gradeColor} px-2.5 py-1 rounded-full font-bold">${grade}</span>`;
      document.getElementById(`status-${id}`).innerHTML = `<span class="${statusColor} px-2 py-0.5 rounded-md font-bold text-[10px]">${status}</span>`;
    }
  </script>
</body>
</html>