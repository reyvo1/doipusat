# NEXA Full UAT — Ubuntu + GitHub

Repo lokal pengguna: `~/Desktop/program/keuangan sentral`
Repo GitHub: `https://github.com/reyvo1/doipusat`

## Gate
1. Core / Static Regression
2. MySQL / Accounting UAT
3. HTTP / Security / Reports UAT
4. Browser E2E / Visual UAT
5. Multi-Entity / High-Volume UAT
6. Production Candidate UAT Verdict

## Ubuntu: isi repo lokal dengan paket ini
Jalankan dari Terminal Ubuntu setelah ZIP diekstrak ke repo lokal.

```bash
cd ~/Desktop/program/'keuangan sentral'
git init
git branch -M main
git remote remove origin 2>/dev/null || true
git remote add origin https://github.com/reyvo1/doipusat.git
git add -A
git commit -m "NEXA Group Finance full GitHub UAT"
git push -u origin main
```

Jika GitHub meminta autentikasi, gunakan login browser/Git Credential Manager atau SSH key; jangan menaruh token di source.

## Menjalankan UAT
Buka GitHub > repo `reyvo1/doipusat` > Actions > **NEXA Full UAT** > Run workflow.

Semua gate harus hijau. Gate `Production Candidate UAT Verdict` hanya hijau jika lima gate utama sukses.

## Bukti
`Browser E2E / Visual UAT` mengunggah artifact `browser-uat-evidence` berisi screenshot dashboard dan laporan laba rugi. `HTTP / Security / Reports UAT` mengunggah log server.
