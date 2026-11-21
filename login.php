<?php
session_start();
include 'koneksi.php';

$error = "";

if (isset($_POST['login'])) {
    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']); 
    $role     = $_POST['role']; // 'mahasiswa' atau 'dosen'

    if ($role == 'mahasiswa') {
        // Validasi Login Mahasiswa berdasarkan Nama & NIM (Password)
        $query = mysqli_query($koneksi, "SELECT * FROM mahasiswa WHERE nama LIKE '%$nama%' AND nim = '$password'");
        if (mysqli_num_rows($query) > 0) {
            $mhs = mysqli_fetch_assoc($query);
            $_SESSION['portal_mhs_id'] = $mhs['id'];
            $_SESSION['role'] = 'mahasiswa';
            header("Location: portal_mhs.php");
            exit;
        } else {
            $error = "Nama lengkap atau NIM Mahasiswa salah / tidak cocok!";
        }
    } elseif ($role == 'dosen') {
        // Validasi Login Dosen berdasarkan Nama & Password dari Tabel Dosen MySQL
        $query_dosen = mysqli_query($koneksi, "SELECT * FROM dosen WHERE nama LIKE '%$nama%' AND password = '$password'");
        if (mysqli_num_rows($query_dosen) > 0) {
            $dsn = mysqli_fetch_assoc($query_dosen);
            $_SESSION['dosen_nama'] = $dsn['nama'];
            $_SESSION['role'] = 'dosen';
            header("Location: index.php");
            exit;
        } else {
            $error = "Nama Dosen atau Password salah! (Coba password: dosen123)";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Sistem Akademik - Devakad</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>
<body class="bg-slate-900 min-h-screen flex items-center justify-center p-4">

  <div class="bg-white rounded-3xl max-w-md w-full p-8 space-y-6 shadow-2xl border border-slate-800">
    <div class="text-center space-y-2">
      <div class="w-14 h-14 bg-blue-600 text-white rounded-2xl flex items-center justify-center mx-auto shadow-lg shadow-blue-500/30">
        <i data-lucide="graduation-cap" class="w-8 h-8"></i>
      </div>
      <h2 class="text-2xl font-extrabold text-slate-900">DEVAKAD LOGIN</h2>
      <p class="text-xs text-slate-500">Sistem Informasi Monitoring Nilai & Akademik</p>
    </div>

    <?php if ($error != ""): ?>
      <div class="bg-rose-50 border border-rose-200 text-rose-600 text-xs p-3 rounded-xl font-medium text-center">
        <?php echo $error; ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="login.php" class="space-y-4 text-xs">
      <div>
        <label class="block font-bold text-slate-700 mb-1">Nama Lengkap</label>
        <input type="text" name="nama" required placeholder="Masukkan nama lengkap Anda" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-semibold text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none">
      </div>

      <div>
        <label class="block font-bold text-slate-700 mb-1">Password / Kode Akses</label>
        <input type="password" name="password" required placeholder="NIM (Mahasiswa) atau Password (Dosen)" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-semibold text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none">
      </div>

      <div>
        <label class="block font-bold text-slate-700 mb-1">Masuk Sebagai</label>
        <select name="role" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-semibold text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none">
          <option value="mahasiswa">Mahasiswa</option>
          <option value="dosen">Dosen / Admin</option>
        </select>
      </div>

      <button type="submit" name="login" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-bold transition shadow-md shadow-blue-600/20 text-sm">
        Masuk Sistem
      </button>
    </form>

    <!-- Tombol Daftar Mahasiswa Baru -->
    <div class="pt-2 border-t border-slate-100 text-center">
      <p class="text-xs text-slate-500">Mahasiswa baru belum punya akun?</p>
      <a href="register_mhs.php" class="text-xs font-bold text-indigo-600 hover:underline mt-1 inline-block">
        Daftar Akun Mandiri di Sini &rarr;
      </a>
    </div>
    
    <!-- Fitur Lupa Password -->
    <div class="pt-2 text-center">
      <a href="#" onclick="alert('Silakan hubungi Bagian Akademik / Unit IT Kampus untuk melakukan reset NIM/Password Anda.')" class="text-xs font-semibold text-blue-600 hover:underline">
        Lupa password / NIM?
      </a>
    </div>
  </div>

  <script>lucide.createIcons();</script>
</body>
</html>