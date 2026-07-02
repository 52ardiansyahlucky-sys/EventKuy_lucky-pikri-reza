# TODO

- [ ] Cek konfigurasi OpenWeather di `config/services.php` dan pastikan key/base_url sesuai dengan `WeatherService.php`.
- [ ] Perbaiki implementasi `config/services.php` supaya API weather bisa dipanggil.
- [x] Jalankan command `php artisan weather:check-upcoming --days=3` untuk verifikasi.
- [ ] Pastikan ada event+venue dengan koordinat dan event_date dalam rentang yang dicek (hasil sementara: tidak ada event dalam H-3).
- [ ] Pastikan migrasi/kolom di `weather_reports` sesuai data yang disimpan.


