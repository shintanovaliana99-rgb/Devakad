<?php
include 'koneksi.php';

// 1. PROSES TAMBAH MAHASISWA (CREATE)
if (isset($_POST['tambah'])) {
    $nim      = mysqli_real_escape_string($koneksi, $_POST['nim']);
    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $prodi    = mysqli_real_escape_string($koneksi, $_POST['prodi']);
    $semester = mysqli_real_escape_string($koneksi, $_POST['semester']);

    $cek_nim = mysqli_query($koneksi, "SELECT * FROM mahasiswa WHERE nim = '$nim'");
    if (mysqli_num_rows($cek_nim) > 0) {
        echo "<script>alert('Gagal! NIM $nim sudah terdaftar.'); window.location='mahasiswa.php';</script>";
    } else {
        $query = "INSERT INTO mahasiswa (nim, nama, prodi, semester, status) VALUES ('$nim', '$nama', '$prodi', '$semester', 'AKTIF')";
        if (mysqli_query($koneksi, $query)) {
            echo "<script>alert('Data mahasiswa berhasil ditambahkan ke MySQL!'); window.location='mahasiswa.php';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan: " . mysqli_error($koneksi) . "');</script>";
        }
    }
}

// 2. PROSES EDIT MAHASISWA (UPDATE)
if (isset($_POST['edit'])) {
    $id       = $_POST['id'];
    $nim      = mysqli_real_escape_string($koneksi, $_POST['nim']);
    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $prodi    = mysqli_real_escape_string($koneksi, $_POST['prodi']);
    $semester = mysqli_real_escape_string($koneksi, $_POST['semester']);
    $status   = mysqli_real_escape_string($koneksi, $_POST['status']);

    $query = "UPDATE mahasiswa SET nim='$nim', nama='$nama', prodi='$prodi', semester='$semester', status='$status' WHERE id='$id'";
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Perubahan data berhasil disimpan!'); window.location='mahasiswa.php';</script>";
    } else {
        echo "<script>alert('Gagal mengubah data: " . mysqli_error($koneksi) . "');</script>";
    }
}

// 3. PROSES HAPUS MAHASISWA (DELETE)
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $query = "DELETE FROM mahasiswa WHERE id='$id'";
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Data mahasiswa berhasil dihapus!'); window.location='mahasiswa.php';</script>";
    }
}

// 4. PROSES PENCARIAN & TAMPIL DATA (READ & SEARCH)
$keyword = "";
if (isset($_GET['cari']) && $_GET['cari'] != "") {
    $keyword = mysqli_real_escape_string($koneksi, $_GET['cari']);
    $query_tampil = "SELECT * FROM mahasiswa WHERE nim LIKE '%$keyword%' OR nama LIKE '%$keyword%' OR prodi LIKE '%$keyword%' ORDER BY id DESC";
} else {
    $query_tampil = "SELECT * FROM mahasiswa ORDER BY id DESC";
}
$result_mhs = mysqli_query($koneksi, $query_tampil);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Mahasiswa - Devakad</title>
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
        <a href="mahasiswa.php" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-600 text-white font-medium text-sm transition">
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
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h2 class="text-2xl font-bold text-slate-900">DATA MAHASISWA</h2>
        <p class="text-xs text-slate-500">Manajemen master data mahasiswa (CRUD MySQL).</p>
      </div>
      <button onclick="toggleModal('modal-tambah', true)" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold flex items-center gap-2 shadow-sm transition">
        <i data-lucide="user-plus" class="w-4 h-4"></i> Tambah Mahasiswa
      </button>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
      <!-- Bar Pencarian -->
      <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <form method="GET" action="mahasiswa.php" class="flex items-center gap-2">
          <div class="relative w-72">
            <input type="text" name="cari" value="<?php echo htmlspecialchars($keyword); ?>" placeholder="Cari NIM, Nama, atau Prodi..." class="w-full bg-slate-50 border border-slate-200 text-xs rounded-xl pl-9 pr-3 py-2 focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none">
            <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-2.5"></i>
          </div>
          <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-3.5 py-2 rounded-xl text-xs font-semibold transition">Cari</button>
          <?php if ($keyword != ""): ?>
            <a href="mahasiswa.php" class="text-xs text-rose-500 hover:underline font-semibold ml-1">Reset</a>
          <?php endif; ?>
        </form>
        <span class="text-xs text-slate-400 font-medium">Menampilkan <?php echo mysqli_num_rows($result_mhs); ?> data</span>
      </div>

      <!-- Tabel Data Mahasiswa -->
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-600">
          <thead class="bg-slate-900 text-white uppercase text-[10px] tracking-wider">
            <tr>
              <th class="py-3.5 px-4">No</th>
              <th class="py-3.5 px-4">NIM</th>
              <th class="py-3.5 px-4">Nama Lengkap</th>
              <th class="py-3.5 px-4">Program Studi</th>
              <th class="py-3.5 px-4 text-center">Semester</th>
              <th class="py-3.5 px-4 text-center">Status</th>
              <th class="py-3.5 px-4 text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <?php
            $no = 1;
            if (mysqli_num_rows($result_mhs) > 0) {
                while ($row = mysqli_fetch_assoc($result_mhs)) {
                    $badgeStatus = ($row['status'] == 'AKTIF') 
                        ? "<span class='bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-md font-bold text-[10px]'>AKTIF</span>"
                        : "<span class='bg-rose-100 text-rose-700 px-2 py-0.5 rounded-md font-bold text-[10px]'>NON-AKTIF</span>";
                    ?>
                    <tr class="hover:bg-slate-50">
                      <td class="py-3 px-4 font-semibold"><?php echo $no++; ?></td>
                      <td class="py-3 px-4 font-mono font-bold text-slate-700"><?php echo $row['nim']; ?></td>
                      <td class="py-3 px-4 font-bold text-slate-800"><?php echo $row['nama']; ?></td>
                      <td class="py-3 px-4"><?php echo $row['prodi']; ?></td>
                      <td class="py-3 px-4 text-center font-semibold"><?php echo $row['semester']; ?></td>
                      <td class="py-3 px-4 text-center"><?php echo $badgeStatus; ?></td>
                      <td class="py-3 px-4 text-center">
                        <button type="button" onclick="bukaModalEdit('<?php echo $row['id']; ?>', '<?php echo $row['nim']; ?>', '<?php echo addslashes($row['nama']); ?>', '<?php echo $row['prodi']; ?>', '<?php echo $row['semester']; ?>', '<?php echo $row['status']; ?>')" class="text-blue-600 hover:underline font-semibold mr-3">Edit</button>
                        <a href="mahasiswa.php?hapus=<?php echo $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus data <?php echo addslashes($row['nama']); ?>?')" class="text-rose-500 hover:underline font-semibold">Hapus</a>
                      </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='7' class='py-6 text-center text-slate-400'>Data mahasiswa tidak ditemukan.</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <!-- MODAL TAMBAH MAHASISWA -->
  <div id="modal-tambah" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm hidden flex items-center justify-center p-4 z-50">
    <form method="POST" action="mahasiswa.php" class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-xl">
      <h3 class="text-lg font-bold text-slate-900">Tambah Mahasiswa Baru</h3>
      <div class="space-y-3 text-xs">
        <div>
          <label class="block font-bold text-slate-600 mb-1">NIM</label>
          <input type="text" name="nim" required placeholder="Contoh: 220510104" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
        </div>
        <div>
          <label class="block font-bold text-slate-600 mb-1">Nama Lengkap</label>
          <input type="text" name="nama" required placeholder="Nama Mahasiswa" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
        </div>
        <div>
          <label class="block font-bold text-slate-600 mb-1">Program Studi</label>
          <select name="prodi" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="Teknik Informatika">Teknik Informatika</option>
            <option value="Sistem Informasi">Sistem Informasi</option>
            <option value="Teknologi Informasi">Teknologi Informasi</option>
            <option value="Ilmu Komputer">Ilmu Komputer</option>
          </select>
        </div>
        <div>
          <label class="block font-bold text-slate-600 mb-1">Semester</label>
          <select name="semester" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="Semester 1">Semester 1</option>
            <option value="Semester 2">Semester 2</option>
            <option value="Semester 3">Semester 3</option>
            <option value="Semester 4">Semester 4</option>
            <option value="Semester 5">Semester 5</option>
            <option value="Semester 6">Semester 6</option>
            <option value="Semester 7">Semester 7</option>
            <option value="Semester 8">Semester 8</option>
          </select>
        </div>
      </div>
      <div class="flex justify-end gap-2 pt-2">
        <button type="button" onclick="toggleModal('modal-tambah', false)" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-500 hover:bg-slate-100">Batal</button>
        <button type="submit" name="tambah" class="px-4 py-2 rounded-xl text-xs font-bold bg-blue-600 text-white hover:bg-blue-700">Simpan ke Database</button>
      </div>
    </form>
  </div>

  <!-- MODAL EDIT MAHASISWA -->
  <div id="modal-edit" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm hidden flex items-center justify-center p-4 z-50">
    <form method="POST" action="mahasiswa.php" class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-xl">
      <h3 class="text-lg font-bold text-slate-900">Edit Data Mahasiswa</h3>
      <input type="hidden" name="id" id="edit-id">
      <div class="space-y-3 text-xs">
        <div>
          <label class="block font-bold text-slate-600 mb-1">NIM</label>
          <input type="text" name="nim" id="edit-nim" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
        </div>
        <div>
          <label class="block font-bold text-slate-600 mb-1">Nama Lengkap</label>
          <input type="text" name="nama" id="edit-nama" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
        </div>
        <div>
          <label class="block font-bold text-slate-600 mb-1">Program Studi</label>
          <select name="prodi" id="edit-prodi" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="Teknik Informatika">Teknik Informatika</option>
            <option value="Sistem Informasi">Sistem Informasi</option>
            <option value="Teknologi Informasi">Teknologi Informasi</option>
            <option value="Ilmu Komputer">Ilmu Komputer</option>
          </select>
        </div>
        <div>
          <label class="block font-bold text-slate-600 mb-1">Semester</label>
          <select name="semester" id="edit-semester" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="Semester 1">Semester 1</option>
            <option value="Semester 2">Semester 2</option>
            <option value="Semester 3">Semester 3</option>
            <option value="Semester 4">Semester 4</option>
            <option value="Semester 5">Semester 5</option>
            <option value="Semester 6">Semester 6</option>
            <option value="Semester 7">Semester 7</option>
            <option value="Semester 8">Semester 8</option>
          </select>
        </div>
        <div>
          <label class="block font-bold text-slate-600 mb-1">Status</label>
          <select name="status" id="edit-status" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="AKTIF">AKTIF</option>
            <option value="NON-AKTIF">NON-AKTIF</option>
          </select>
        </div>
      </div>
      <div class="flex justify-end gap-2 pt-2">
        <button type="button" onclick="toggleModal('modal-edit', false)" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-500 hover:bg-slate-100">Batal</button>
        <button type="submit" name="edit" class="px-4 py-2 rounded-xl text-xs font-bold bg-blue-600 text-white hover:bg-blue-700">Simpan Perubahan</button>
      </div>
    </form>
  </div>

  <script>
    lucide.createIcons();

    function toggleModal(modalId, show) {
      document.getElementById(modalId).classList.toggle('hidden', !show);
    }

    function bukaModalEdit(id, nim, nama, prodi, semester, status) {
      document.getElementById('edit-id').value = id;
      document.getElementById('edit-nim').value = nim;
      document.getElementById('edit-nama').value = nama;
      document.getElementById('edit-prodi').value = prodi;
      document.getElementById('edit-semester').value = semester;
      document.getElementById('edit-status').value = status;
      toggleModal('modal-edit', true);
    }
  </script>
</body>
</html>