<?php
session_start();
include 'koneksi.php';

$error = "";

// 1. PROSES LOGIN MAHASISWA BERDASARKAN NIM
if (isset($_POST['login_mhs'])) {
    $nim = mysqli_real_escape_string($koneksi, $_POST['nim']);
    
    $query = mysqli_query($koneksi, "SELECT * FROM mahasiswa WHERE nim = '$nim'");
    if (mysqli_num_rows($query) > 0) {
        $mhs = mysqli_fetch_assoc($query);
        // Simpan ID mahasiswa ke dalam session login
        $_SESSION['portal_mhs_id'] = $mhs['id'];
        header("Location: portal_mhs.php");
        exit;
    } else {
        $error = "NIM $nim tidak terdaftar dalam sistem akademik!";
    }
}

// 2. PROSES LOGOUT MAHASISWA
if (isset($_GET['logout'])) {
    session_destroy(); // Hapus semua session
    header("Location: login.php"); // Arahkan kembali ke halaman login utama
    exit;
}

// Cek apakah mahasiswa sudah login
$is_logged_in = isset($_SESSION['portal_mhs_id']);
$selected_mhs_id = $is_logged_in ? $_SESSION['portal_mhs_id'] : 0;

$detail_mhs = null;
$result_khs = null;

if ($is_logged_in) {
    // Ambil biodata mahasiswa yang sedang login
    $detail_mhs = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM mahasiswa WHERE id = '$selected_mhs_id'"));

    // Ambil transkrip nilai khusus mahasiswa tersebut
    $query_khs = "
        SELECT mk.kode_mk, mk.nama_mk, mk.sks,
               n.tugas, n.uts, n.uas, n.nilai_akhir, n.grade, n.status
        FROM mata_kuliah mk
        LEFT JOIN nilai n ON mk.id = n.matkul_id AND n.mahasiswa_id = '$selected_mhs_id'
        ORDER BY mk.kode_mk ASC
    ";
    $result_khs = mysqli_query($koneksi, $query_khs);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Portal Akademik Mahasiswa - Devakad</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
    body { font-family: 'Inter', sans-serif; }
    @media print {
      body * { visibility: hidden; }
      #printable-portal, #printable-portal * { visibility: visible; }
      #printable-portal {
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

  <?php if (!$is_logged_in): ?>
    <!-- ================= HALAMAN LOGIN MAHASISWA ================= -->
    <div class="min-h-screen w-full flex items-center justify-center p-4 bg-slate-900">
      <div class="bg-white rounded-3xl max-w-md w-full p-8 space-y-6 shadow-2xl border border-slate-800">
        <div class="text-center space-y-2">
          <div class="w-14 h-14 bg-indigo-600 text-white rounded-2xl flex items-center justify-center mx-auto shadow-lg shadow-indigo-500/30">
            <i data-lucide="graduation-cap" class="w-8 h-8"></i>
          </div>
          <h2 class="text-2xl font-extrabold text-slate-900">Portal Mahasiswa</h2>
          <p class="text-xs text-slate-500">Masukkan NIM Anda untuk melihat transkrip nilai resmi.</p>
        </div>

        <?php if ($error != ""): ?>
          <div class="bg-rose-50 border border-rose-200 text-rose-600 text-xs p-3 rounded-xl font-medium text-center">
            <?php echo $error; ?>
          </div>
        <?php endif; ?>

        <form method="POST" action="portal_mhs.php" class="space-y-4 text-xs">
          <div>
            <label class="block font-bold text-slate-700 mb-1">Nomor Induk Mahasiswa (NIM)</label>
            <input type="text" name="nim" required placeholder="Contoh: 220510101" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-mono font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none">
          </div>
          <button type="submit" name="login_mhs" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-xl font-bold transition shadow-md shadow-indigo-600/20">
            Masuk ke Portal
          </button>
        </form>

        <div class="pt-4 border-t border-slate-100 text-center">
          <a href="index.php" class="text-xs font-semibold text-slate-500 hover:text-indigo-600 transition flex items-center justify-center gap-1.5">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Dashboard Dosen
          </a>
        </div>
      </div>
    </div>

  <?php else: ?>
    <!-- ================= DASHBOARD / KHS PRIBADI MAHASISWA ================= -->
    <aside class="w-64 bg-indigo-950 text-white flex flex-col justify-between p-4 min-h-screen shrink-0">
      <div>
        <div class="flex items-center gap-3 px-3 py-4 mb-4 border-b border-indigo-900">
          <div class="bg-indigo-600 p-2 rounded-xl text-white">
            <i data-lucide="user-check" class="w-6 h-6"></i>
          </div>
          <div>
            <h1 class="font-bold text-sm leading-tight tracking-wide truncate max-w-[130px]"><?php echo $detail_mhs['nama']; ?></h1>
            <p class="text-[11px] text-indigo-300 font-mono"><?php echo $detail_mhs['nim']; ?></p>
          </div>
        </div>

        <nav class="space-y-1">
          <a href="portal_mhs.php" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-indigo-600 text-white font-medium text-sm transition">
            <i data-lucide="file-text" class="w-5 h-5"></i> Transkrip KHS Saya
          </a>
          <!-- Tombol Keluar / Ganti Akun -->
          <a href="portal_mhs.php?logout=true" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-rose-300 hover:bg-rose-950/50 hover:text-rose-200 font-medium text-sm transition mt-6 border border-rose-900/50">
            <i data-lucide="log-out" class="w-5 h-5"></i> Keluar (Logout)
          </a>
        </nav>
      </div>

      <div class="bg-indigo-900/40 p-4 rounded-2xl border border-indigo-800/50 text-center">
        <p class="text-[11px] text-indigo-200 font-semibold">POV Mahasiswa</p>
        <p class="text-[10px] text-indigo-400 mt-0.5">Akses Data Pribadi Aman</p>
      </div>
    </aside>

    <main class="flex-1 p-8 overflow-y-auto space-y-6">
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h2 class="text-2xl font-bold text-slate-900">TRANSKRIP NILAI & KHS PRIBADI</h2>
          <p class="text-xs text-slate-500">Anda masuk sebagai mahasiswa aktif. Data di bawah hanya menampilkan nilai milik Anda.</p>
        </div>
        <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold shadow-sm transition flex items-center gap-2">
          <i data-lucide="printer" class="w-4 h-4"></i> Cetak KHS PDF
        </button>
      </div>

      <!-- KHS / Transkrip Container -->
      <div id="printable-portal" class="bg-white p-8 rounded-2xl border border-slate-200 shadow-md space-y-6 max-w-4xl mx-auto text-slate-800">
        <div class="flex items-center justify-between border-b-2 border-slate-900 pb-4">
          <div>
            <h3 class="text-xl font-extrabold tracking-wide uppercase text-slate-900">KARTU HASIL STUDI (KHS)</h3>
            <p class="text-xs font-semibold text-slate-500">DEVAKAD STUDENT PORTAL - SEMESTER GANJIL 2025/2026</p>
          </div>
          <div class="text-right">
            <span class="text-xs bg-indigo-900 text-white px-3 py-1 rounded-full font-mono font-bold">STATUS: <?php echo $detail_mhs['status']; ?></span>
          </div>
        </div>

        <!-- Biodata Mahasiswa -->
        <div class="grid grid-cols-2 gap-4 text-xs bg-indigo-50/50 p-4 rounded-xl border border-indigo-100">
          <div>
            <p class="text-slate-400 font-semibold">Nama Mahasiswa:</p>
            <p class="font-bold text-slate-800 text-sm"><?php echo $detail_mhs['nama']; ?></p>
          </div>
          <div>
            <p class="text-slate-400 font-semibold">Nomor Induk Mahasiswa (NIM):</p>
            <p class="font-mono font-bold text-slate-800 text-sm"><?php echo $detail_mhs['nim']; ?></p>
          </div>
          <div>
            <p class="text-slate-400 font-semibold">Program Studi:</p>
            <p class="font-bold text-slate-800"><?php echo $detail_mhs['prodi']; ?></p>
          </div>
          <div>
            <p class="text-slate-400 font-semibold">Semester Akademik:</p>
            <p class="font-bold text-slate-800"><?php echo $detail_mhs['semester']; ?></p>
          </div>
        </div>

        <!-- Tabel Nilai Keseluruhan Mata Kuliah -->
        <table class="w-full text-left text-xs border border-slate-200">
          <thead class="bg-slate-100 text-slate-800 font-bold border-b border-slate-200">
            <tr>
              <th class="py-2.5 px-3 border-r">Kode MK</th>
              <th class="py-2.5 px-3 border-r">Mata Kuliah</th>
              <th class="py-2.5 px-3 border-r text-center">SKS</th>
              <th class="py-2.5 px-3 border-r text-center">Nilai Angka</th>
              <th class="py-2.5 px-3 border-r text-center">Grade</th>
              <th class="py-2.5 px-3 border-r text-center">Bobot</th>
              <th class="py-2.5 px-3 text-center">Mutu</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-200 font-medium">
            <?php
            $total_sks  = 0;
            $total_mutu = 0;

            while ($row = mysqli_fetch_assoc($result_khs)) {
                $sks   = intval($row['sks']);
                $akhir = $row['nilai_akhir'] !== null ? $row['nilai_akhir'] : 0;
                $grade = $row['grade'] !== null ? $row['grade'] : '-';

                $bobot = 0.0;
                if ($grade == 'A') $bobot = 4.0;
                elseif ($grade == 'B') $bobot = 3.0;
                elseif ($grade == 'C') $bobot = 2.0;
                elseif ($grade == 'D') $bobot = 1.0;

                $mutu = ($grade != '-') ? ($sks * $bobot) : 0;
                
                if ($grade != '-') {
                    $total_sks  += $sks;
                    $total_mutu += $mutu;
                }

                $gradeColor = 'text-slate-400';
                if ($grade == 'A') $gradeColor = 'text-purple-700 font-bold';
                elseif ($grade == 'B') $gradeColor = 'text-emerald-600 font-bold';
                elseif ($grade == 'C') $gradeColor = 'text-blue-600 font-bold';
                elseif ($grade == 'D') $gradeColor = 'text-amber-600 font-bold';
                elseif ($grade == 'E') $gradeColor = 'text-rose-600 font-bold';
                ?>
                <tr>
                  <td class="py-2 px-3 border-r font-mono"><?php echo $row['kode_mk']; ?></td>
                  <td class="py-2 px-3 border-r"><?php echo $row['nama_mk']; ?></td>
                  <td class="py-2 px-3 border-r text-center"><?php echo $sks; ?></td>
                  <td class="py-2 px-3 border-r text-center font-bold"><?php echo ($grade != '-') ? number_format($akhir, 1) : '-'; ?></td>
                  <td class="py-2 px-3 border-r text-center <?php echo $gradeColor; ?>"><?php echo $grade; ?></td>
                  <td class="py-2 px-3 border-r text-center"><?php echo ($grade != '-') ? number_format($bobot, 2) : '-'; ?></td>
                  <td class="py-2 px-3 text-center font-semibold"><?php echo ($grade != '-') ? number_format($mutu, 2) : '-'; ?></td>
                </tr>
                <?php
            }

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
          <div>
            <p class="text-xs text-slate-500 font-semibold">Total SKS Diambil: <span class="text-slate-800 font-bold"><?php echo $total_sks; ?> SKS</span></p>
            <p class="text-xs text-slate-500 font-semibold">Total Angka Mutu: <span class="text-slate-800 font-bold"><?php echo number_format($total_mutu, 2); ?></span></p>
          </div>
          <div class="sm:text-right">
            <p class="text-xs text-slate-500 font-semibold">Indeks Prestasi Semester (IPS):</p>
            <p class="text-2xl font-extrabold text-indigo-600">
              <?php echo number_format($ips, 2); ?>
              <span class="text-xs font-bold text-emerald-600">(<?php echo $predikat; ?>)</span>
            </p>
          </div>
        </div>
      </div>
    </main>
  <?php endif; ?>

  <script>lucide.createIcons();</script>
</body>
</html>