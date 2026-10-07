<?php

require __DIR__ . '/../includes/auth.php';

$page_title = "Daftar Anggota";

include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$perPage = 5;
$page = max(1, (int) ($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;

$keyword = trim($_GET['q'] ?? '');

$query = 'select=*&order=id.desc';

if ($keyword !== '') {
    $query .= '&nama=ilike.*' . rawurlencode($keyword) . '*';
}

$query .= '&limit=' . $perPage;
$query .= '&offset=' . $offset;

$result = supabaseRequest(
    'GET',
    'anggota',
    null,
    $query
);

$daftarAnggota = $result['data'] ?? [];

$countQuery = 'select=id';

if ($keyword !== '') {
    $countQuery .= '&nama=ilike.*' . rawurlencode($keyword) . '*';
}

$countResult = supabaseRequest(
    'GET',
    'anggota',
    null,
    $countQuery
);

$totalRows = count($countResult['data'] ?? []);

$totalPages = max(
    1,
    (int) ceil($totalRows / $perPage)
);
?>
        <section>
            <h2>Daftar Anggota</h2>

            <?php if ($flash): ?>
                <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
            <?php endif; ?>

            <div class="search-box">
                <form method="get" action="list.php">
                    <span>
                        <label for="search-input">Cari Nama Anggota</label><br>
                        <input type="text" id="search-input" name="q" value="<?php echo $keyword; ?>" placeholder="Ketik nama anggota...">
                    </span>
                    <button type="submit">Cari</button>
                </form>
            </div>

            <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No. Anggota</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No. HP</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($daftarAnggota)): ?>
                    <tr>
                        <td colspan="5">Tidak ada data anggota yang cocok.</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($daftarAnggota as $anggota): ?>
                        <tr>
                            <td><?php echo $anggota['no_anggota']; ?></td>
                            <td><?php echo $anggota['nama']; ?></td>
                            <td><?php echo $anggota['alamat']; ?></td>
                            <td><?php echo $anggota['no_hp']; ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo $anggota['id']; ?>" class="btn-edit">Edit</a>
                                <form class="form-hapus" method="post" action="hapus.php">
                                    <input type="hidden" name="id" value="<?php echo $anggota['id']; ?>">
                                    <button type="submit" class="btn-hapus">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>

            <nav class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="list.php?page=<?php echo $i; ?><?php echo $keyword !== '' ? '&q=' . urlencode($keyword) : ''; ?>"
                   class="<?php echo $i === $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
            </nav>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
