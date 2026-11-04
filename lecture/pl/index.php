<?php
// Taruh file ini di folder yang sama dengan file materi. Tidak perlu diedit lagi.
$judul    = 'Materi WAN & Teknologi Jaringan';
$subjudul = 'Universitas Muhammadiyah Yogyakarta';
$abaikan  = ['index.php', 'index.html', '.htaccess'];   // file yang tidak ditampilkan

$files = [];
foreach (scandir(__DIR__) as $f) {
    if ($f[0] === '.' || is_dir(__DIR__ . '/' . $f) || in_array($f, $abaikan, true)) continue;
    $path = __DIR__ . '/' . $f;
    $files[] = [
        'nama'  => $f,
        'ext'   => strtolower(pathinfo($f, PATHINFO_EXTENSION)),
        'ukuran'=> filesize($path),
        'waktu' => filemtime($path),
    ];
}
usort($files, fn($a, $b) => strnatcasecmp($a['nama'], $b['nama'])); // Module 8, 9, 10 ... urut benar

function ukuran($b) {
    foreach (['B', 'KB', 'MB', 'GB'] as $i => $u) {
        if ($b < 1024 || $i === 3) return ($i ? number_format($b, 1) : $b) . ' ' . $u;
        $b /= 1024;
    }
}
$bulan = ['','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
function tgl($t) { global $bulan; return date('j', $t) . ' ' . $bulan[(int)date('n', $t)] . ' ' . date('Y', $t); }
function h($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
$warna = ['pptx'=>'ppt','ppt'=>'ppt','pdf'=>'pdf','docx'=>'doc','doc'=>'doc','xlsx'=>'xls','xls'=>'xls','zip'=>'zip','rar'=>'zip','pkt'=>'pkt','pka'=>'pkt'];
?><!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= h($judul) ?></title>
<style>
  :root {
    --bg: #eef2f6; --panel: #ffffff; --ink: #14233a; --muted: #5d6c82;
    --line: #d5dde8; --accent: #0b6bcb; --accent-soft: #e4f0fc;
    --ppt: #d2561f; --pdf: #c42b3a; --doc: #1f5fbf; --xls: #1d8a4f; --zip: #7a5a14; --pkt: #0b7f8c; --other: #5d6c82;
  }
  @media (prefers-color-scheme: dark) {
    :root { --bg:#0e1724; --panel:#162234; --ink:#e6edf6; --muted:#93a3ba; --line:#263650; --accent:#5aa9f5; --accent-soft:#1b3350; }
  }
  * { box-sizing: border-box; }
  body { margin:0; background:var(--bg); color:var(--ink); font:16px/1.5 "Segoe UI", system-ui, -apple-system, Roboto, sans-serif; }
  .wrap { max-width: 860px; margin: 0 auto; padding: 56px 20px 80px; }
  header { margin-bottom: 28px; }
  header .kampus { color: var(--muted); font-size: .95rem; margin: 0 0 6px; }
  h1 { font-size: clamp(1.9rem, 5vw, 2.8rem); line-height: 1.1; margin: 0 0 18px; letter-spacing: -.02em; }
  .bar { display:flex; gap:12px; align-items:center; flex-wrap:wrap; }
  .search { flex:1 1 240px; padding: 12px 16px; border:1px solid var(--line); border-radius: 10px;
            background: var(--panel); color: var(--ink); font: inherit; }
  .search:focus-visible, .row:focus-visible { outline: 3px solid var(--accent); outline-offset: 2px; }
  .jumlah { color: var(--muted); font-size: .92rem; }

  .rack { background: var(--panel); border:1px solid var(--line); border-radius: 14px; overflow:hidden; }
  .row { display:grid; grid-template-columns: 56px 1fr auto; gap: 16px; align-items:center;
         padding: 14px 18px; color: inherit; text-decoration:none; border-top:1px solid var(--line); position:relative; }
  .row:first-child { border-top:0; }
  .row::before { content:""; position:absolute; left:0; top:0; bottom:0; width:4px; background: var(--c, var(--other)); }
  .row:hover { background: var(--accent-soft); }
  .badge { width:48px; height:48px; border-radius:8px; background: var(--c, var(--other)); color:#fff;
           display:grid; place-items:center; font-weight:700; font-size:.78rem; letter-spacing:.02em; }
  .nama { font-weight:600; overflow-wrap:anywhere; }
  .meta { color: var(--muted); font-size:.88rem; }
  .unduh { font-size:.9rem; font-weight:600; color: var(--accent); border:1px solid var(--accent);
           padding: 7px 14px; border-radius: 999px; white-space:nowrap; }
  .row:hover .unduh { background: var(--accent); color: #fff; }
  .ppt{--c:var(--ppt)} .pdf{--c:var(--pdf)} .doc{--c:var(--doc)} .xls{--c:var(--xls)} .zip{--c:var(--zip)} .pkt{--c:var(--pkt)}
  .kosong { padding: 40px 20px; text-align:center; color: var(--muted); }
  footer { margin-top: 22px; color: var(--muted); font-size: .88rem; }
  @media (max-width: 520px) {
    .row { grid-template-columns: 48px 1fr; }
    .unduh { grid-column: 2; justify-self:start; }
    .badge { width:42px; height:42px; }
  }
</style>
</head>
<body>
<div class="wrap">
  <header>
    <p class="kampus"><?= h($subjudul) ?></p>
    <h1><?= h($judul) ?></h1>
    <div class="bar">
      <input class="search" id="cari" type="search" placeholder="Cari materi, misalnya Module 10" aria-label="Cari materi" autocomplete="off">
      <span class="jumlah" id="jumlah"><?= count($files) ?> file</span>
    </div>
  </header>

  <main class="rack" id="rack">
    <?php if (!$files): ?>
      <div class="kosong">Belum ada file. Masukkan file ke folder ini dan halaman akan menampilkannya otomatis.</div>
    <?php endif; ?>
    <?php foreach ($files as $f): $k = $warna[$f['ext']] ?? ''; ?>
      <a class="row <?= $k ?>" href="<?= h(rawurlencode($f['nama'])) ?>" download data-nama="<?= h(strtolower($f['nama'])) ?>">
        <span class="badge"><?= h(strtoupper($f['ext'] ?: 'FILE')) ?></span>
        <span>
          <span class="nama"><?= h($f['nama']) ?></span><br>
          <span class="meta"><?= ukuran($f['ukuran']) ?> &middot; diperbarui <?= tgl($f['waktu']) ?></span>
        </span>
        <span class="unduh">Unduh</span>
      </a>
    <?php endforeach; ?>
    <div class="kosong" id="nihil" hidden>Tidak ada materi yang cocok dengan pencarian.</div>
  </main>
  <footer>Daftar ini dibuat otomatis dari isi folder.</footer>
</div>
<script>
  const cari = document.getElementById('cari'), jumlah = document.getElementById('jumlah'),
        nihil = document.getElementById('nihil'), rows = [...document.querySelectorAll('.row')];
  cari.addEventListener('input', () => {
    const q = cari.value.trim().toLowerCase();
    let n = 0;
    rows.forEach(r => { const ok = r.dataset.nama.includes(q); r.hidden = !ok; if (ok) n++; });
    jumlah.textContent = n + ' file';
    nihil.hidden = n !== 0 || rows.length === 0;
  });
</script>
</body>
</html>
