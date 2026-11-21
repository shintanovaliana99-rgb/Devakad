<?php
include 'koneksi.php';

// 1. PROSES TAMBAH MATA KULIAH (CREATE)
if (isset($_POST['tambah'])) {
    $kode_mk = strtoupper(mysqli_real_escape_string($koneksi, $_POST['kode_mk']));
    $nama_mk = mysqli_real_escape_string($koneksi, $_POST['nama_mk']);
    $sks     = intval($_POST['sks']);

    $cek_kode = mysqli_query($koneksi, "SELECT * FROM mata_kuliah WHERE kode_mk = '$kode_mk'");
    if (mysqli_num_rows($cek_kode) > 0) {
        echo "<script>alert('Gagal! Kode Mata Kuliah $kode_mk sudah terdaftar.'); window.location='matkul.php';</script>";
    } else {
        $query = "INSERT INTO mata_kuliah (kode_mk, nama_mk, sks) VALUES ('$kode_mk', '$nama_mk', '$sks')";
        if (mysqli_query($koneksi, $query)) {
            echo "<script>alert('Mata kuliah berhasil ditambahkan ke MySQL!'); window.location='matkul.php';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan: " . mysqli_error($koneksi) . "');</script>";
        }
    }
}

// 2. PROSES EDIT MATA KULIAH (UPDATE)
if (isset($_POST['edit'])) {
    $id      = intval($_POST['id']);
    $kode_mk = strtoupper(mysqli_real_escape_string($koneksi, $_POST['kode_mk']));
    $nama_mk = mysqli_real_escape_string($koneksi, $_POST['nama_mk']);
    $sks     = intval($_POST['sks']);

    $query = "UPDATE mata_kuliah SET kode_mk='$kode_mk', nama_mk='$nama_mk', sks='$sks' WHERE id='$id'";
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Perubahan mata kuliah berhasil disimpan!'); window.location='matkul.php';</script>";
    } else {
        echo "<script>alert('Gagal mengubah data: " . mysqli_error($koneksi) . "');</script>";
    }
}

// 3. PROSES HAPUS MATA KULIAH (DELETE)
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $query = "DELETE FROM mata_kuliah WHERE id='$id'";
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Mata kuliah berhasil dihapus!'); window.location='matkul.php';</script>";
    }
}

// 4. PROSES PENCARIAN & TAMPIL DATA (READ & SEARCH)
$keyword = "";
if (isset($_GET['cari']) && $_GET['cari'] != "") {
    $keyword = mysqli_real_escape_string($koneksi, $_GET['cari']);
    $query_tampil = "SELECT * FROM mata_kuliah WHERE kode_mk LIKE '%$keyword%' OR nama_mk LIKE '%$keyword%' ORDER BY kode_mk ASC";
} else {
    $query_tampil = "SELECT * FROM mata_kuliah ORDER BY kode_mk ASC";
}
$result_mk = mysqli_query($koneksi, $query_tampil);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mata Kuliah - Devakad</title>
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
        <a href="matkul.php" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-600 text-white font-medium text-sm transition">
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
        <h2 class="text-2xl font-bold text-slate-900">DATA MATA KULIAH</h2>
        <p class="text-xs text-slate-500">Kelola daftar mata kuliah dan bobot SKS kurikulum (CRUD MySQL).</p>
      </div>
      <button onclick="toggleModal('modal-tambah', true)" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold flex items-center gap-2 shadow-sm transition">
        <i data-lucide="plus-circle" class="w-4 h-4"></i> Tambah Mata Kuliah
      </button>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
      <!-- Bar Pencarian -->
      <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <form method="GET" action="matkul.php" class="flex items-center gap-2">
          <div class="relative w-72">
            <input type="text" name="cari" value="<?php echo htmlspecialchars($keyword); ?>" placeholder="Cari Kode MK atau Nama Mata Kuliah..." class="w-full bg-slate-50 border border-slate-200 text-xs rounded-xl pl-9 pr-3 py-2 focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none">
            <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-2.5"></i>
          </div>
          <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-3.5 py-2 rounded-xl text-xs font-semibold transition">Cari</button>
          <?php if ($keyword != ""): ?>
            <a href="matkul.php" class="text-xs text-rose-500 hover:underline font-semibold ml-1">Reset</a>
          <?php endif; ?>
        </form>
        <span class="text-xs text-slate-400 font-medium">Total: <?php echo mysqli_num_rows($result_mk); ?> Mata Kuliah</span>
      </div>

      <!-- Tabel Mata Kuliah -->
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-600">
          <thead class="bg-slate-900 text-white uppercase text-[10px] tracking-wider">
            <tr>
              <th class="py-3.5 px-4 w-16">No</th>
              <th class="py-3.5 px-4 w-36">Kode MK</th>
              <th class="py-3.5 px-4">Nama Mata Kuliah</th>
              <th class="py-3.5 px-4 text-center w-28">Bobot SKS</th>
              <th class="py-3.5 px-4 text-center w-36">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <?php
            $no = 1;
            $total_sks = 0;
            if (mysqli_num_rows($result_mk) > 0) {
                while ($row = mysqli_fetch_assoc($result_mk)) {
                    $total_sks += intval($row['sks']);
                    ?>
                    <tr class="hover:bg-slate-50">
                      <td class="py-3 px-4 font-semibold"><?php echo $no++; ?></td>
                      <td class="py-3 px-4 font-mono font-bold text-blue-600"><?php echo $row['kode_mk']; ?></td>
                      <td class="py-3 px-4 font-bold text-slate-800"><?php echo $row['nama_mk']; ?></td>
                      <td class="py-3 px-4 text-center">
                        <span class="bg-slate-100 text-slate-800 px-2.5 py-1 rounded-md font-bold"><?php echo $row['sks']; ?> SKS</span>
                      </td>
                      <td class="py-3 px-4 text-center">
                        <button type="button" onclick="bukaModalEdit('<?php echo $row['id']; ?>', '<?php echo $row['kode_mk']; ?>', '<?php echo addslashes($row['nama_mk']); ?>', '<?php echo $row['sks']; ?>')" class="text-blue-600 hover:underline font-semibold mr-3">Edit</button>
                        <a href="matkul.php?hapus=<?php echo $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus mata kuliah <?php echo addslashes($row['nama_mk']); ?>?')" class="text-rose-500 hover:underline font-semibold">Hapus</a>
                      </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='5' class='py-6 text-center text-slate-400'>Data mata kuliah tidak ditemukan.</td></tr>";
            }
            ?>
          </tbody>
          <tfoot class="bg-slate-50 border-t border-slate-200 font-bold text-slate-700">
            <tr>
              <td colspan="3" class="py-3 px-4 text-right uppercase text-[11px]">Total Keseluruhan SKS:</td>
              <td class="py-3 px-4 text-center text-blue-600"><?php echo $total_sks; ?> SKS</td>
              <td></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </main>

  <!-- MODAL TAMBAH MATA KULIAH -->
  <div id="modal-tambah" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm hidden flex items-center justify-center p-4 z-50">
    <form method="POST" action="matkul.php" class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-xl">
      <h3 class="text-lg font-bold text-slate-900">Tambah Mata Kuliah Baru</h3>
      <div class="space-y-3 text-xs">
        <div>
          <label class="block font-bold text-slate-600 mb-1">Kode Mata Kuliah</label>
          <input type="text" name="kode_mk" required placeholder="Contoh: INF304" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 uppercase focus:ring-2 focus:ring-blue-500 outline-none">
        </div>
        <div>
          <label class="block font-bold text-slate-600 mb-1">Nama Mata Kuliah</label>
          <input type="text" name="nama_mk" required placeholder="Contoh: Rekayasa Perangkat Lunak" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
        </div>
        <div>
          <label class="block font-bold text-slate-600 mb-1">Jumlah SKS</label>
          <select name="sks" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="1">1 SKS</option>
            <option value="2">2 SKS</option>
            <option value="3" selected>3 SKS</option>
            <option value="4">4 SKS</option>
            <option value="6">6 SKS</option>
          </select>
        </div>
      </div>
      <div class="flex justify-end gap-2 pt-2">
        <button type="button" onclick="toggleModal('modal-tambah', false)" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-500 hover:bg-slate-100">Batal</button>
        <button type="submit" name="tambah" class="px-4 py-2 rounded-xl text-xs font-bold bg-blue-600 text-white hover:bg-blue-700">Simpan ke Database</button>
      </div>
    </form>
  </div>

  <!-- MODAL EDIT MATA KULIAH -->
  <div id="modal-edit" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm hidden flex items-center justify-center p-4 z-50">
    <form method="POST" action="matkul.php" class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-xl">
      <h3 class="text-lg font-bold text-slate-900">Edit Data Mata Kuliah</h3>
      <input type="hidden" name="id" id="edit-id">
      <div class="space-y-3 text-xs">
        <div>
          <label class="block font-bold text-slate-600 mb-1">Kode Mata Kuliah</label>
          <input type="text" name="kode_mk" id="edit-kode" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 uppercase focus:ring-2 focus:ring-blue-500 outline-none">
        </div>
        <div>
          <label class="block font-bold text-slate-600 mb-1">Nama Mata Kuliah</label>
          <input type="text" name="nama_mk" id="edit-nama" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
        </div>
        <div>
          <label class="block font-bold text-slate-600 mb-1">Jumlah SKS</label>
          <select name="sks" id="edit-sks" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="1">1 SKS</option>
            <option value="2">2 SKS</option>
            <option value="3">3 SKS</option>
            <option value="4">4 SKS</option>
            <option value="6">6 SKS</option>
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

    function bukaModalEdit(id, kode, nama, sks) {
      document.getElementById('edit-id').value = id;
      document.getElementById('edit-kode').value = kode;
      document.getElementById('edit-nama').value = nama;
      document.getElementById('edit-sks').value = sks;
      toggleModal('modal-edit', true);
    }
  </script>
</body>
</html>