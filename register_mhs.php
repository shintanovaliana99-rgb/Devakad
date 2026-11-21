<?php
include 'koneksi.php';

$pesan = "";
$error = "";

if (isset($_POST['daftar'])) {
    $nim      = mysqli_real_escape_string($koneksi, $_POST['nim']);
    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $prodi    = mysqli_real_escape_string($koneksi, $_POST['prodi']);
    $semester = intval($_POST['semester']);
    $status   = mysqli_real_escape_string($koneksi, $_POST['status']);
    $password = $nim; // Password otomatis disamakan dengan NIM untuk login mahasiswa

    // Cek apakah NIM sudah terdaftar sebelumnya
    $cek_nim = mysqli_query($koneksi, "SELECT * FROM mahasiswa WHERE nim = '$nim'");
    if (mysqli_num_rows($cek_nim) > 0) {
        $error = "NIM $nim sudah terdaftar di dalam sistem akademik!";
    } else {
        // Simpan data ke database
        $query = "INSERT INTO mahasiswa (nim, nama, prodi, semester, status, password) VALUES ('$nim', '$nama', '$prodi', '$semester', '$status', '$password')";
        if (mysqli_query($koneksi, $query)) {
            $pesan = "Registrasi berhasil! Data Anda sudah masuk ke sistem akademik. Silakan login menggunakan NIM Anda.";
        } else {
            $error = "Terjadi kesalahan: " . mysqli_error($koneksi);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi Mahasiswa Baru - Devakad</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>
<body class="bg-slate-900 min-h-screen flex items-center justify-center p-4">

  <div class="bg-white rounded-3xl max-w-lg w-full p-8 space-y-6 shadow-2xl border border-slate-800">
    <div class="text-center space-y-2">
      <div class="w-14 h-14 bg-indigo-600 text-white rounded-2xl flex items-center justify-center mx-auto shadow-lg shadow-indigo-500/30">
        <i data-lucide="user-plus" class="w-8 h-8"></i>
      </div>
      <h2 class="text-2xl font-extrabold text-slate-900">Registrasi Mahasiswa Baru</h2>
      <p class="text-xs text-slate-500">Isi data diri Anda dengan benar agar otomatis terdaftar di portal akademik.</p>
    </div>

    <?php if ($pesan != ""): ?>
      <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs p-3.5 rounded-xl font-medium text-center">
        <?php echo $pesan; ?>
        <div class="mt-3">
          <a href="login.php" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl font-bold inline-block transition">Menuju Halaman Login</a>
        </div>
      </div>
    <?php endif; ?>

    <?php if ($error != ""): ?>
      <div class="bg-rose-50 border border-rose-200 text-rose-600 text-xs p-3 rounded-xl font-medium text-center">
        <?php echo $error; ?>
      </div>
    <?php endif; ?>

    <?php if ($pesan == ""): ?>
    <form method="POST" action="register_mhs.php" class="space-y-4 text-xs">
      <div>
        <label class="block font-bold text-slate-700 mb-1">Nomor Induk Mahasiswa (NIM)</label>
        <input type="text" name="nim" required placeholder="Contoh: 220510115" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-mono font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none">
      </div>

      <div>
        <label class="block font-bold text-slate-700 mb-1">Nama Lengkap</label>
        <input type="text" name="nama" required placeholder="Masukkan nama lengkap beserta gelar jika ada" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-semibold text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none">
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block font-bold text-slate-700 mb-1">Program Studi</label>
          <select name="prodi" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-semibold text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none">
            <option value="Pendidikan Teknologi Informasi">Pendidikan Teknologi Informasi</option>
            <option value="Ilmu Komputer">Ilmu Komputer</option>
            <option value="Teknik Informatika">Teknik Informatika</option>
          </select>
        </div>
        <div>
          <label class="block font-bold text-slate-700 mb-1">Semester</label>
          <select name="semester" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-semibold text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none">
            <option value="1">Semester 1</option>
            <option value="3">Semester 3</option>
            <option value="5">Semester 5</option>
            <option value="7">Semester 7</option>
          </select>
        </div>
      </div>

      <div>
        <label class="block font-bold text-slate-700 mb-1">Status Akademik</label>
        <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-semibold text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none">
          <option value="Aktif">Aktif</option>
          <option value="Cuti">Cuti</option>
        </select>
      </div>

      <button type="submit" name="daftar" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-xl font-bold transition shadow-md shadow-indigo-600/20 text-sm">
        Daftar & Masukkan ke Sistem
      </button>
    </form>
    <?php endif; ?>

    <div class="pt-4 border-t border-slate-100 text-center">
      <a href="login.php" class="text-xs font-semibold text-slate-500 hover:text-indigo-600 transition flex items-center justify-center gap-1">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Sudah punya akun? Login di sini
      </a>
    </div>
  </div>

  <script>lucide.createIcons();</script>
</body>
</html>