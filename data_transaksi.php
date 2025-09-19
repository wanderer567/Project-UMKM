<?php
session_start();
include 'koneksi.php';

// Ambil data transaksi + nama pelanggan (jika ada)
$query = "
    SELECT t.id_transaksi, t.id_pelanggan, t.tanggal, t.total_harga, u.nama
    FROM transaksi t
    LEFT JOIN user u ON t.id_pelanggan = u.id
    ORDER BY t.tanggal DESC, t.id_transaksi DESC
";
$result = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Data Transaksi</title>
    <style>
        /* Reset & basic */
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f6f9;
            color: #222;
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 220px;
            height: 100%;
            background: linear-gradient(180deg, #0d6efd, #0b5ed7); /* biru */
            color: #fff;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            transition: width .25s ease;
            z-index: 1000;
        }
        .sidebar h3 {
            margin: 0;
            font-size: 20px;
            text-align: center;
            letter-spacing: 0.5px;
            border-bottom: 1px solid rgba(255,255,255,0.15);
            padding-bottom: 10px;
        }
        .sidebar a {
            color: rgba(255,255,255,0.95);
            text-decoration: none;
            padding: 10px;
            border-radius: 6px;
            display: block;
            transition: background .2s;
        }
        .sidebar a:hover { background: rgba(255,255,255,0.07); }
        .sidebar .logout-btn { margin-top: auto; text-align: center; }
        .sidebar .logout-btn button {
            background: rgba(255,255,255,0.12);
            color: #fff;
            border: 1px solid rgba(255,255,255,0.12);
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
        }
        .sidebar .logout-btn button:hover {
            background: rgba(255,255,255,0.18);
        }

        /* TOGGLE (mobile) */
        .topbar {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 56px;
            background: #0d6efd;
            color: #fff;
            align-items: center;
            padding: 0 12px;
            z-index: 999;
        }
        .topbar .toggle-btn {
            background: transparent;
            border: none;
            color: #fff;
            font-size: 20px;
            cursor: pointer;
            margin-right: 12px;
        }
        .topbar h3 { margin: 0; font-size: 18px; }

        /* KONTEN UTAMA */
        .content {
            margin-left: 240px; /* memberi ruang untuk sidebar */
            padding: 24px;
            transition: margin-left .25s ease;
        }

        .card {
            background: #fff;
            border-radius: 8px;
            padding: 16px;
            box-shadow: 0 6px 18px rgba(20,20,50,0.06);
        }

        h2 { margin: 0 0 12px 0; font-size: 20px; }

        /* TABEL */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 720px; /* agar terlihat bagus di desktop */
        }
        th, td {
            padding: 10px 12px;
            border-bottom: 1px solid #e9eef6;
            text-align: left;
            vertical-align: middle;
            font-size: 14px;
        }
        th {
            background: #f8fafc;
            color: #333;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: .6px;
        }
        tr:hover td { background: #fbfdff; }

        /* small labels for total/tanggal */
        .muted { color: #6c757d; font-size: 13px; }

        /* Responsive rules */
        @media (max-width: 900px) {
            .sidebar { width: 200px; }
            .content { margin-left: 210px; padding: 16px; }
            table { min-width: 640px; }
        }

        @media (max-width: 700px) {
            /* hide fixed sidebar, show topbar toggle */
            .sidebar { display: none; }
            .topbar { display: flex; }
            .content { margin-left: 0; padding-top: 72px; }
            table { min-width: 560px; }
        }

        /* collapsed sidebar helper (JS will toggle class) */
        .sidebar.collapsed { width: 72px; }
        .sidebar.collapsed a { text-align: center; padding-left: 8px; padding-right: 8px; }
        .sidebar.collapsed h3 { display: none; }
        .content.shifted { margin-left: 92px; } /* when sidebar collapsed */
    </style>
</head>
<body>

    <!-- Topbar untuk mobile -->
    <div class="topbar" id="topbar">
        <button class="toggle-btn" id="mobileToggle">&#9776;</button>
        <h3>Anindia Kitchen - Dashboard</h3>
    </div>

    <!-- Sidebar (biru) -->
    <div class="sidebar" id="sidebar">
        <h3>Anindia Kitchen</h3>
        <a href="dashboard.php">Dashboard Admin</a>
        <a href="data_transaksi.php">Data Transaksi</a>
        <a href="data_user.php">Data User</a>

        <form action="logout.php" method="POST" class="logout-btn">
            <button type="submit" name="logout">Logout</button>
        </form>
    </div>

    <!-- Konten utama -->
    <div class="content" id="content">
        <div class="card">
            <h2>Data Transaksi</h2>
            <p class="muted">Daftar transaksi (terbaru di atas). Tabel responsif — geser ke kiri/kanan di layar kecil.</p>

            <div class="table-responsive" role="region" aria-label="Daftar Transaksi">
                <table>
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>Nama Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Total Harga (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['id_transaksi']); ?></td>
                                    <td><?= htmlspecialchars($row['nama'] ? $row['nama'] : 'Pelangan ID '.$row['id_pelanggan']); ?></td>
                                    <td><?= date('d M Y', strtotime($row['tanggal'])); ?></td>
                                    <td><?= number_format($row['total_harga'], 0, ',', '.'); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align:center; padding:24px;">Belum ada data transaksi.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div> <!-- .table-responsive -->
        </div> <!-- .card -->
    </div> <!-- .content -->

    <script>
        // Toggle sidebar collapse (desktop)
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');
        // Double-click sidebar to collapse (optional UX)
        sidebar.addEventListener('dblclick', () => {
            sidebar.classList.toggle('collapsed');
            content.classList.toggle('shifted');
        });

        // Mobile toggle
        const mobileToggle = document.getElementById('mobileToggle');
        mobileToggle && mobileToggle.addEventListener('click', () => {
            // If sidebar is hidden (mobile), toggle its display
            if (sidebar.style.display === 'block') {
                sidebar.style.display = 'none';
            } else {
                sidebar.style.display = 'block';
                sidebar.style.position = 'fixed';
                sidebar.style.top = '56px';
                sidebar.style.left = '0';
                sidebar.style.height = 'calc(100% - 56px)';
            }
        });

        // Klik luar sidebar di mobile -> tutup
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 700) {
                if (!sidebar.contains(e.target) && !document.getElementById('topbar').contains(e.target)) {
                    sidebar.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>
