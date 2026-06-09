# Guru Mobile App

Mobile app Flutter khusus role guru yang terhubung ke backend Laravel pada repo ini.

## Integrasi

- Login: `POST /api/guru/login`
- Profile: `GET /api/guru/me`
- Dashboard: `GET /api/guru/dashboard`
- Evidence CRUD: `/api/guru/evidences`
- Self assessment CRUD: `/api/guru/self-assessments`
- Settings CRUD: `/api/guru/settings/*`
- Reflection: `POST /api/guru/reflections/{evaluationId}`

## Konfigurasi

Gunakan `--dart-define=API_BASE_URL=https://domain-anda.com/api` saat menjalankan app.
Untuk emulator Android lokal, gunakan:

```bash
flutter run --dart-define=API_BASE_URL=http://10.0.2.2:8000/api
```

Untuk device Android fisik via USB debugging, arahkan port backend ke device dengan:

```bash
adb reverse tcp:8000 tcp:8000
flutter run --dart-define=API_BASE_URL=http://127.0.0.1:8000/api
```

Kalau backend Laravel berjalan di port lain, ganti `8000` sesuai port yang dipakai.

## Catatan

Folder ini adalah source Flutter khusus guru. Karena Flutter SDK tidak tersedia di lingkungan kerja ini, platform folders
(`android/`, `ios/`, `web/`) belum digenerate otomatis. Jalankan `flutter create .` di folder ini pada mesin yang memiliki
Flutter untuk membuat platform runner, lalu pakai source yang ada di `lib/`.
