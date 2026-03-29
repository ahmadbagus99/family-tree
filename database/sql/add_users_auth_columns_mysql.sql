-- Cadangan jika di Hostinger tidak bisa menjalankan: php artisan migrate
-- Urutan: pastikan tabel `people` sudah ada.

ALTER TABLE `users`
    ADD COLUMN `person_id` BIGINT UNSIGNED NULL AFTER `id`,
    ADD COLUMN `username` VARCHAR(255) NULL AFTER `name`,
    ADD COLUMN `is_super_admin` TINYINT(1) NOT NULL DEFAULT 0 AFTER `password`;

ALTER TABLE `users`
    ADD CONSTRAINT `users_person_id_foreign`
    FOREIGN KEY (`person_id`) REFERENCES `people` (`id`) ON DELETE SET NULL;

UPDATE `users` SET `username` = CONCAT('user-', `id`) WHERE `username` IS NULL OR `username` = '';
UPDATE `users` SET `username` = 'admin', `is_super_admin` = 1 WHERE `name` = 'admin';

ALTER TABLE `users` ADD UNIQUE KEY `users_username_unique` (`username`);
ALTER TABLE `users` ADD UNIQUE KEY `users_person_id_unique` (`person_id`);

-- Setelah deploy kode terbaru, di SSH jalankan: php artisan family:sync-login-users
-- agar username mengikuti nama Person untuk akun keluarga.
