# NEXT CHAT — NEXA Enterprise R3

Baseline yang harus dipakai: **Enterprise R3 RC 5.0.0-rc3**. Jangan kembali ke Production Final R2.

Next gate tunggal: push source R3 ke `reyvo1/doipusat`, jalankan workflow **NEXA Full UAT**, lalu evaluasi hasil 7 gate. Jika merah, gunakan log/artifact gate yang gagal untuk root-cause fix; jangan melemahkan approval, period lock, RBAC, double-entry, migration, atau security agar gate lolos.

Repo lokal Ubuntu user: `~/Desktop/program/keuangan sentral`.

Bukti lokal baseline:
- 62/62 legacy functional PASS
- 35/35 enterprise domain PASS
- 33/33 enterprise advanced PASS
- architecture PASS
- 21/21 route HTTP 200
- lint PHP/JS PASS
- production fail-closed PASS

MySQL real execution belum diklaim lokal karena runtime saat build tidak menyediakan pdo_mysql/MySQL. Bukti MySQL harus datang dari GitHub Actions MySQL 8.4.
