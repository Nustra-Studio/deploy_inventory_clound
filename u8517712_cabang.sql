-- Database export via SQLPro (https://www.sqlprostudio.com/)
-- Exported by jawirscript at 28-05-2024 11:47.
-- WARNING: This file may contain descructive statements such as DROPs.
-- Please ensure that you are running the script at the proper location.


-- BEGIN TABLE cabangs
DROP TABLE IF EXISTS cabangs;
CREATE TABLE `cabangs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kepala_cabang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telepon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `database` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cabangs_uuid_unique` (`uuid`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserting 4 rows into cabangs
-- Insert batch #1
INSERT INTO cabangs (id, uuid, nama, kepala_cabang, telepon, alamat, category_id, keterangan, `database`, created_at, updated_at) VALUES
(5, 'cac8cd96fd08fd4e6789906cd78777490d3fb63f282361802ae287d63d8299be', 'CB  Kedungwaru', 'Pak Bowo', '8123456790', 'Kedung Indah Jl. Dr. Wahidin Sudiro Husodo No.7, Kedung Indah, Kedungwaru, Kec. Kedungwaru, Kabupaten Tulungagung, Jawa Timur 66229', '1d5dc35e998b17261c0301940a75cbba9fc40ceea82023ee5e3274aef469f2c0', 'singkron', 'cabang_CB_1_Kedungwaru', NULL, NULL),
(10, '745fd7add152992998702f1023a04b14177a7dd087873a419c87fa483ec93129', 'CB_Bandung', 'Pak Bowo', '081231776325', 'Jalan Raya Bandung Besuki Ruko No.6, Kebonsari, Ngunggahan, Kec. Bandung, Kabupaten Tulungagung, Jawa Timur 66274', '1d5dc35e998b17261c0301940a75cbba9fc40ceea82023ee5e3274aef469f2c0', 'not_singkron', 'cabang_CB_Bandung', NULL, NULL),
(11, 'c2c5635fda9fd41c409b7ca965d3f0e1eea3fc9e12472b7b07ea77f2e262d60e', 'CB_Tamanan', 'Pak Bowo', '081235622421', 'Jl. Pahlawan Gg. IV, RT.01/RW.02, Tamanan, Kec. Tulungagung, Kabupaten Tulungagung, Jawa Timur 66217', '1d5dc35e998b17261c0301940a75cbba9fc40ceea82023ee5e3274aef469f2c0', 'not_singkron', 'cabang_CB_Tamanan', NULL, NULL),
(12, 'f12990bab95e64f3f160a65d5db8038a30efeabf8430196587fb641c7dc147a8', 'CB_Ngunut', 'Pak Bowo', '085895456427', 'Jl. Demuk No.152, Kalangan, Kec. Ngunut, Kabupaten Tulungagung, Jawa Timur 66292', '1d5dc35e998b17261c0301940a75cbba9fc40ceea82023ee5e3274aef469f2c0', 'not_singkron', 'cabang_CB_Ngunut', NULL, NULL);

-- END TABLE cabangs

