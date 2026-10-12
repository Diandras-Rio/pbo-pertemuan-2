<?php
/**
 * Tugas Praktikum Pertemuan 2 - PBO
 * Judul: Program Perhitungan Nilai Akhir & Status Kelulusan Mahasiswa
 */

// ==========================================
// 1. INISIALISASI VARIABEL (Minimal 4 Tipe Data Berbeda)
// ==========================================
$namaMahasiswa = "";     // Tipe Data: String
$nimMahasiswa  = "";     // Tipe Data: String
$kehadiran     = 0;      // Tipe Data: Integer
$nilaiTugas    = 0.0;    // Tipe Data: Float
$nilaiUTS      = 0.0;    // Tipe Data: Float
$nilaiUAS      = 0.0;    // Tipe Data: Float
$isLulus       = false;  // Tipe Data: Boolean
$grade         = "";     // Tipe Data: String
$nilaiAkhir    = 0.0;    // Tipe Data: Float
$rekapKehadiran = [];    // Array untuk menyimpan hasil perulangan

// ==========================================
// 2. FUNCTION BUATAN SENDIRI
// ==========================================
/**
 * Function untuk menghitung Nilai Akhir berdasarkan bobot:
 * Presensi = 10%, Tugas = 20%, UTS = 30%, UAS = 40%
 */
function hitungNilaiAkhir($presensi, $tugas, $uts, $uas) {
    $persenPresensi = ($presensi / 16) * 100;
    $total = ($persenPresensi * 0.10) + ($tugas * 0.20) + ($uts * 0.30) + ($uas * 0.40);
    return $total;
}

// ==========================================
// PEMROSESAN FORM INPUT (METHOD POST)
// ==========================================
$isSubmitted = ($_SERVER["REQUEST_METHOD"] == "POST");

if ($isSubmitted) {
    // Membaca input dari form
    $namaMahasiswa = $_POST['nama'] ?? "";
    $nimMahasiswa  = $_POST['nim'] ?? "";
    $kehadiran     = (int)($_POST['kehadiran'] ?? 0);
    $nilaiTugas    = (float)($_POST['tugas'] ?? 0);
    $nilaiUTS      = (float)($_POST['uts'] ?? 0);
    $nilaiUAS      = (float)($_POST['uas'] ?? 0);

    // Memanggil Function untuk Menghitung Nilai Akhir
    $nilaiAkhir = hitungNilaiAkhir($kehadiran, $nilaiTugas, $nilaiUTS, $nilaiUAS);

    // ==========================================
    // 3. PERCABANGAN (IF / ELSEIF / ELSE)
    // ==========================================
    // Menentukan Huruf Mutu / Grade
    if ($nilaiAkhir >= 85) {
        $grade = "A";
    } elseif ($nilaiAkhir >= 75) {
        $grade = "B";
    } elseif ($nilaiAkhir >= 65) {
        $grade = "C";
    } elseif ($nilaiAkhir >= 50) {
        $grade = "D";
    } else {
        $grade = "E";
    }

    // Menentukan Status Kelulusan (Syarat: Nilai >= 65 dan Kehadiran >= 12 kali)
    if ($nilaiAkhir >= 65 && $kehadiran >= 12) {
        $isLulus = true;
    } else {
        $isLulus = false;
    }

    // ==========================================
    // 4. PERULANGAN (FOR)
    // ==========================================
    // Membuat log rekap presensi di dalam logika PHP
    for ($i = 1; $i <= $kehadiran; $i++) {
        $rekapKehadiran[] = "Pertemuan ke-" . $i . " : Hadir";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Perhitungan Nilai Mahasiswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 30px;
        }
        .container {
            max-width: 500px;
            background-color: #ffffff;
            margin: 0 auto;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333333;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 14px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555555;
        }
        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 9px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .hasil-box {
            margin-top: 25px;
            padding: 18px;
            background-color: #eef7ff;
            border-left: 5px solid #007bff;
            border-radius: 4px;
        }
        .lulus {
            color: #28a745;
            font-weight: bold;
        }
        .gagal {
            color: #dc3545;
            font-weight: bold;
        }
        ul {
            padding-left: 20px;
            margin: 5px 0;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Form Penilaian Mahasiswa</h2>
    
    <form method="POST" action="">
        <div class="form-group">
            <label for="nama">Nama Mahasiswa:</label>
            <input type="text" id="nama" name="nama" required>
        </div>

        <div class="form-group">
            <label for="nim">NIM:</label>
            <input type="text" id="nim" name="nim" required>
        </div>

        <div class="form-group">
            <label for="kehadiran">Jumlah Kehadiran (maks. 16):</label>
            <input type="number" id="kehadiran" name="kehadiran" min="0" max="16" required>
        </div>

        <div class="form-group">
            <label for="tugas">Nilai Tugas (0 - 100):</label>
            <input type="number" step="0.1" id="tugas" name="tugas" min="0" max="100" required>
        </div>

        <div class="form-group">
            <label for="uts">Nilai UTS (0 - 100):</label>
            <input type="number" step="0.1" id="uts" name="uts" min="0" max="100" required>
        </div>

        <div class="form-group">
            <label for="uas">Nilai UAS (0 - 100):</label>
            <input type="number" step="0.1" id="uas" name="uas" min="0" max="100" required>
        </div>

        <button type="submit">Hitung Nilai</button>
    </form>

    <?php if ($isSubmitted): ?>
        <div class="hasil-box">
            <h3>Hasil Evaluasi Penilaian</h3>
            <p><strong>Nama:</strong> <?= htmlspecialchars($namaMahasiswa); ?></p>
            <p><strong>NIM:</strong> <?= htmlspecialchars($nimMahasiswa); ?></p>
            <hr>
            <p><strong>Nilai Akhir:</strong> <?= number_format($nilaiAkhir, 2); ?></p>
            <p><strong>Grade:</strong> <?= $grade; ?></p>
            <p><strong>Status Kelulusan:</strong> 
                <?php if ($isLulus): ?>
                    <span class="lulus">LULUS</span>
                <?php else: ?>
                    <span class="gagal">TIDAK LULUS</span>
                <?php endif; ?>
            </p>
            <hr>

            <p><strong>Rekap Presensi Kehadiran:</strong></p>
            <ul>
                <?php foreach ($rekapKehadiran as $item): ?>
                    <li><?= $item; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>

</body>
</html>