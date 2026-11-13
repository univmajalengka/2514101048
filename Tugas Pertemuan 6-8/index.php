
<?php
session_start();
require_once "koneksi.php";

// Fungsi menghitung persentase diskon
function hitungDiskon($totalBelanja) {
    if ($totalBelanja >= 100000) {
        return 10;
    } elseif ($totalBelanja >= 50000) {
        return 5;
    } else {
        return 0;
    }
}

// Memproses form
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $inputBelanja = trim($_POST["total_belanja"] ?? "");

    if (
        preg_match('/^[0-9]{1,3}(\.[0-9]{3})+$/', $inputBelanja) &&
        (int) str_replace(".", "", $inputBelanja) > 0
    ) {

        $totalBelanja = (int) str_replace(".", "", $inputBelanja);

        // Menghitung diskon
        $diskon = hitungDiskon($totalBelanja);

        // Menghitung nominal diskon dan total bayar
        $nominalDiskon = ($diskon / 100) * $totalBelanja;
        $totalBayar = $totalBelanja - $nominalDiskon;

        // Menyimpan transaksi ke database
        $sql = "INSERT INTO transaksi
                (total_belanja, diskon, nominal_diskon, total_bayar)
                VALUES (?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "iddd",
            $totalBelanja,
            $diskon,
            $nominalDiskon,
            $totalBayar
        );

        if ($stmt->execute()) {

            $idTransaksi = $conn->insert_id;

            $_SESSION["hasil"] = [
                "id" => $idTransaksi,
                "total_belanja" => $totalBelanja,
                "diskon" => $diskon,
                "nominal_diskon" => $nominalDiskon,
                "total_bayar" => $totalBayar
            ];

            $_SESSION["pesan"] = "Transaksi berhasil disimpan!";
            $_SESSION["jenis_pesan"] = "sukses";

        } else {
            $_SESSION["pesan"] = "Transaksi gagal disimpan!";
            $_SESSION["jenis_pesan"] = "error";
        }

        $stmt->close();

    } else {
        $_SESSION["pesan"] = "Masukkan total belanja dengan format yang benar, contoh 120.000!";
        $_SESSION["jenis_pesan"] = "error";
    }

    // Mencegah pengiriman ulang form saat halaman di-refresh
    header("Location: index.php");
    exit;
}

// Mengambil pesan
$pesan = $_SESSION["pesan"] ?? "";
$jenisPesan = $_SESSION["jenis_pesan"] ?? "";
$hasil = $_SESSION["hasil"] ?? null;

// Menghapus pesan setelah ditampilkan
unset($_SESSION["pesan"], $_SESSION["jenis_pesan"]);

// Mengambil riwayat transaksi
$riwayat = $conn->query(
    "SELECT * FROM transaksi ORDER BY id DESC LIMIT 3"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perhitungan Diskon</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

    <!-- Judul Halaman -->
    <section class="header">
        <div>
            <h1>Perhitungan Diskon</h1>
            <p>Hitung potongan harga dan lihat riwayat transaksi belanja.</p>
        </div>
    </section>

    <!-- Form dan Hasil Perhitungan -->
    <div class="calculation-layout <?= $hasil ? 'has-result' : '' ?>">

        <!-- Form Input -->
        <section class="card form-card">

            <div class="section-title">
                <h2>Hitung Diskon</h2>
                <p>Masukkan total belanja untuk menghitung diskon.</p>
            </div>

            <form method="POST" action="index.php">

                <label for="total_belanja">Total Belanja</label>

                <div class="input-group">
                    <span>Rp</span>
                    <input
                        type="text"
                        id="total_belanja"
                        name="total_belanja"
                        placeholder="Masukkan total belanja"
                        pattern="[0-9]{1,3}(\.[0-9]{3})+"
                        title="Gunakan titik sebagai pemisah ribuan, contoh 120.000"
                        required
                    >
                </div>

                <button type="submit" class="btn-hitung">
                    Hitung dan Simpan
                </button>

            </form>

            <?php if ($pesan): ?>
                <div class="pesan <?= htmlspecialchars($jenisPesan) ?>">
                    <?= htmlspecialchars($pesan) ?>
                </div>
            <?php endif; ?>

        </section>

        <!-- Hasil Perhitungan -->
        <?php if ($hasil): ?>

        <section class="card hasil-card">

            <div class="section-title">
                <h2>Hasil Perhitungan</h2>
                <p>Rincian transaksi yang baru saja dihitung.</p>
            </div>

            <div class="table-wrapper">
                <table class="tabel-hasil">
                    <tbody>
                        <tr>
                            <td>Total Belanja</td>
                            <td>
                                Rp <?= number_format($hasil["total_belanja"], 0, ',', '.') ?>
                            </td>
                        </tr>

                        <tr>
                            <td>Persentase Diskon</td>
                            <td><?= $hasil["diskon"] ?>%</td>
                        </tr>

                        <tr>
                            <td>Nominal Diskon</td>
                            <td>
                                Rp <?= number_format($hasil["nominal_diskon"], 0, ',', '.') ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="total-box">
                <p>Total Pembayaran</p>
                <h2>
                    Rp <?= number_format($hasil["total_bayar"], 0, ',', '.') ?>
                </h2>
                <span>Sudah termasuk potongan diskon</span>
            </div>

        </section>

        <?php endif; ?>

    </div>

    <!-- Riwayat Perhitungan -->
    <section class="card riwayat-card">

        <div class="section-title">
            <h2>Riwayat Perhitungan</h2>
            <p>Daftar transaksi yang telah disimpan.</p>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Total Belanja</th>
                        <th>Diskon</th>
                        <th>Nominal Diskon</th>
                        <th>Total Bayar</th>
                        <th>Tanggal dan Waktu</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if ($riwayat && $riwayat->num_rows > 0): ?>

                        <?php $no = 1; ?>

                        <?php while ($row = $riwayat->fetch_assoc()): ?>

                        <tr>
                            <td><?= $no++ ?></td>

                            <td>
                                Rp <?= number_format($row["total_belanja"], 0, ',', '.') ?>
                            </td>

                            <td>
                                <span class="badge-diskon">
                                    <?= $row["diskon"] ?>%
                                </span>
                            </td>

                            <td>
                                Rp <?= number_format($row["nominal_diskon"], 0, ',', '.') ?>
                            </td>

                            <td class="total-riwayat">
                                Rp <?= number_format($row["total_bayar"], 0, ',', '.') ?>
                            </td>

                            <td>
                                <?= date("d/m/Y H:i", strtotime($row["tanggal"])) ?>
                            </td>
                        </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="6" class="kosong">
                                Belum ada riwayat perhitungan.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>
            </table>
        </div>

    </section>

</div>

</body>
</html>

<?php
$conn->close();
?>
