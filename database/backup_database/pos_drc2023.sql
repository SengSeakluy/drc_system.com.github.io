/*
 Navicat Premium Data Transfer

 Source Server         : vagrant_projects
 Source Server Type    : MySQL
 Source Server Version : 80027 (8.0.27)
 Source Host           : 127.0.0.1:3306
 Source Schema         : pos_drc2023

 Target Server Type    : MySQL
 Target Server Version : 80027 (8.0.27)
 File Encoding         : 65001

 Date: 20/04/2023 08:38:23
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for data_rows
-- ----------------------------
DROP TABLE IF EXISTS `data_rows`;
CREATE TABLE `data_rows` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `data_type_id` int unsigned NOT NULL,
  `field` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `browse` tinyint(1) NOT NULL DEFAULT '1',
  `read` tinyint(1) NOT NULL DEFAULT '1',
  `edit` tinyint(1) NOT NULL DEFAULT '1',
  `add` tinyint(1) NOT NULL DEFAULT '1',
  `delete` tinyint(1) NOT NULL DEFAULT '1',
  `details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `order` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `data_rows_data_type_id_foreign` (`data_type_id`),
  CONSTRAINT `data_rows_data_type_id_foreign` FOREIGN KEY (`data_type_id`) REFERENCES `data_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1093 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of data_rows
-- ----------------------------
BEGIN;
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (1, 1, 'id', 'number', 'ID', 1, 0, 0, 0, 0, 0, '{}', 1);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (2, 1, 'name', 'text', 'Name', 1, 1, 1, 1, 1, 1, '{\"validation\":{\"rule\":[\"required\",\"max:100\",\"regex:\\/^[a-zA-Z0-9 _-]+$\\/\"],\"messages\":{\"required\":\"Name is required.\",\"regex\":\"Invalid input. Name can contain only character, number, hypen, underscore and space.\",\"max\":\"Name must not be greater than 100 characters.\"}}}', 3);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (3, 1, 'email', 'text', 'Email', 1, 1, 1, 1, 1, 1, '{\"validation\":{\"rule\":[\"required\",\"unique:users\",\"email\"],\"messages\":{\"required\":\"Email is required.\"}}}', 4);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (4, 1, 'password', 'password', 'Password', 1, 0, 0, 1, 1, 1, '{\"validation\":{\"rule\":[\"required\",\"min:8\"],\"messages\":{\"required\":\"Password is required.\",\"min\":\"Password must be at least 8 characters\"}}}', 5);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (5, 1, 'remember_token', 'text', 'Remember Token', 0, 0, 0, 0, 0, 0, '{}', 6);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (6, 1, 'created_at', 'timestamp', 'Created At', 0, 1, 1, 0, 0, 0, '{}', 14);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (7, 1, 'updated_at', 'timestamp', 'Updated At', 0, 0, 0, 0, 0, 0, '{}', 8);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (8, 1, 'avatar', 'image', 'Avatar', 0, 0, 1, 1, 1, 1, '{}', 17);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (9, 1, 'user_belongsto_role_relationship', 'relationship', 'Role', 1, 1, 1, 1, 1, 0, '{\"model\":\"TCG\\\\Voyager\\\\Models\\\\Role\",\"table\":\"roles\",\"type\":\"belongsTo\",\"column\":\"role_id\",\"key\":\"id\",\"label\":\"display_name\",\"pivot_table\":\"roles\",\"pivot\":\"0\",\"taggable\":\"0\"}', 10);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (10, 1, 'user_belongstomany_role_relationship', 'relationship', 'Roles', 1, 0, 1, 1, 1, 0, '{\"model\":\"TCG\\\\Voyager\\\\Models\\\\Role\",\"table\":\"roles\",\"type\":\"belongsToMany\",\"column\":\"id\",\"key\":\"id\",\"label\":\"display_name\",\"pivot_table\":\"user_roles\",\"pivot\":\"1\",\"taggable\":\"0\"}', 11);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (11, 1, 'settings', 'hidden', 'Settings', 0, 0, 0, 0, 0, 0, '{}', 12);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (12, 2, 'id', 'number', 'ID', 1, 0, 0, 0, 0, 0, NULL, 1);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (13, 2, 'name', 'text', 'Name', 1, 1, 1, 1, 1, 1, NULL, 2);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (14, 2, 'created_at', 'timestamp', 'Created At', 0, 0, 0, 0, 0, 0, NULL, 3);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (15, 2, 'updated_at', 'timestamp', 'Updated At', 0, 0, 0, 0, 0, 0, NULL, 4);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (16, 3, 'id', 'number', 'ID', 1, 0, 0, 0, 0, 0, '{}', 1);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (17, 3, 'name', 'text', 'Name', 1, 1, 1, 1, 1, 1, '{\"validation\":{\"rule\":[\"required\",\"unique:roles\",\"max:50\",\"regex:\\/^[a-zA-Z0-9 _-]+$\\/\"],\"messages\":{\"required\":\"Name is required.\",\"max\":\"Name must not be greater than 50 characters.\",\"regex\":\"Invalid input. Name can contain only character, number, hypen, underscore and space.\"}}}', 2);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (18, 3, 'created_at', 'timestamp', 'Created At', 0, 0, 1, 0, 0, 0, '{}', 11);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (19, 3, 'updated_at', 'timestamp', 'Updated At', 0, 0, 0, 0, 0, 0, '{}', 3);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (20, 3, 'display_name', 'text', 'Display Name', 1, 1, 1, 1, 1, 1, '{\"validation\":{\"rule\":[\"required\",\"unique:roles\",\"max:50\",\"regex:\\/^[a-zA-Z0-9 _-]+$\\/\"],\"messages\":{\"required\":\"Display Name is required.\",\"max\":\"Display Name must not be greater than 50 characters.\",\"regex\":\"Invalid input. Display Name can contain only character, number, hypen, underscore and space.\"}}}', 4);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (21, 1, 'role_id', 'text', 'Role', 0, 1, 1, 1, 1, 1, '{\"validation\":{\"rule\":[\"required\"],\"messages\":{\"required\":\"Default Role is required.\"}}}', 9);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (100, 14, 'id', 'text', 'Id', 1, 0, 0, 0, 0, 0, '{}', 1);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (101, 14, 'table_name', 'text', 'Table Name', 1, 1, 1, 1, 1, 1, '{}', 2);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (102, 14, 'column_name', 'text', 'Column Name', 1, 1, 1, 1, 1, 1, '{}', 3);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (103, 14, 'foreign_key', 'text', 'Foreign Key', 1, 1, 1, 1, 1, 1, '{}', 4);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (104, 14, 'locale', 'text', 'Locale', 1, 1, 1, 1, 1, 1, '{}', 5);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (105, 14, 'value', 'text', 'Value', 1, 1, 1, 1, 1, 1, '{}', 6);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (106, 14, 'created_at', 'timestamp', 'Created At', 0, 1, 1, 1, 0, 1, '{}', 7);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (107, 14, 'updated_at', 'timestamp', 'Updated At', 0, 0, 0, 0, 0, 0, '{}', 8);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (158, 1, 'email_verified_at', 'timestamp', 'Email Verified At', 0, 0, 0, 0, 0, 0, '{}', 7);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (159, 1, 'deleted_at', 'timestamp', 'Deleted At', 0, 1, 0, 0, 0, 0, '{}', 15);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (160, 1, 'merchant_id', 'text', 'Merchant Id', 0, 0, 0, 0, 0, 0, '{}', 13);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (166, 1, 'is_locked', 'radio_btn', 'Is Locked', 0, 0, 0, 0, 0, 0, '{\"checked\":true,\"options\":{\"0\":\"No\",\"1\":\"Yes\"}}', 16);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (289, 3, 'created_by', 'text', 'Created By', 0, 0, 0, 0, 0, 0, '{}', 6);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (290, 3, 'updated_by', 'text', 'Updated By', 0, 0, 0, 0, 0, 0, '{}', 7);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (292, 3, 'deleted_at', 'timestamp', 'Deleted At', 0, 1, 0, 0, 0, 0, '{}', 8);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (293, 3, 'role_belongsto_user_relationship', 'relationship', 'Created By', 0, 1, 1, 0, 0, 0, '{\"model\":\"TCG\\\\Voyager\\\\Models\\\\User\",\"table\":\"users\",\"type\":\"belongsTo\",\"column\":\"created_by\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"data_rows\",\"pivot\":\"0\",\"taggable\":\"0\"}', 9);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (424, 3, 'merchant_list', 'text', 'Merchant List', 0, 0, 0, 0, 0, 0, '{}', 10);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (425, 3, 'type', 'select_dropdown', 'Type', 1, 1, 1, 1, 1, 0, '{\"default\":\"1\",\"options\":{\"0\":\"For Technical\",\"1\":\"For Merchant\"}}', 5);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (426, 1, 'timezone', 'select_dropdown', 'Timezone', 0, 1, 1, 1, 1, 0, '{}', 15);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (428, 1, 'created_by', 'text', 'Created By', 0, 0, 0, 0, 0, 0, '{}', 16);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (429, 1, 'type', 'text', 'Type', 0, 0, 0, 0, 0, 0, '{}', 17);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (430, 1, 'integration_id', 'text', 'Integration Id', 0, 0, 0, 0, 0, 0, '{}', 18);
INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES (431, 3, 'is_z1payment_technical', 'checkbox', 'Is Z1payment Technical', 0, 0, 0, 0, 0, 0, '{}', 11);
COMMIT;

-- ----------------------------
-- Table structure for data_types
-- ----------------------------
DROP TABLE IF EXISTS `data_types`;
CREATE TABLE `data_types` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name_singular` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name_plural` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `policy_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `controller` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `generate_permissions` tinyint(1) NOT NULL DEFAULT '0',
  `server_side` tinyint NOT NULL DEFAULT '0',
  `details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `data_types_name_unique` (`name`),
  UNIQUE KEY `data_types_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of data_types
-- ----------------------------
BEGIN;
INSERT INTO `data_types` (`id`, `name`, `slug`, `display_name_singular`, `display_name_plural`, `icon`, `model_name`, `policy_name`, `controller`, `description`, `generate_permissions`, `server_side`, `details`, `created_at`, `updated_at`) VALUES (1, 'users', 'users', 'User', 'Users', 'voyager-person', 'App\\Models\\Admin\\User', 'TCG\\Voyager\\Policies\\UserPolicy', 'App\\Http\\Controllers\\Admin\\Voyager\\UsersController', NULL, 1, 1, '{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"desc\",\"default_search_key\":null,\"scope\":null}', '2021-03-24 06:17:18', '2023-03-07 07:29:47');
INSERT INTO `data_types` (`id`, `name`, `slug`, `display_name_singular`, `display_name_plural`, `icon`, `model_name`, `policy_name`, `controller`, `description`, `generate_permissions`, `server_side`, `details`, `created_at`, `updated_at`) VALUES (2, 'menus', 'menus', 'Menu', 'Menus', 'voyager-list', 'TCG\\Voyager\\Models\\Menu', NULL, '', '', 1, 0, NULL, '2021-03-24 06:17:18', '2021-03-24 06:17:18');
INSERT INTO `data_types` (`id`, `name`, `slug`, `display_name_singular`, `display_name_plural`, `icon`, `model_name`, `policy_name`, `controller`, `description`, `generate_permissions`, `server_side`, `details`, `created_at`, `updated_at`) VALUES (3, 'roles', 'roles', 'Role', 'Roles', 'voyager-lock', 'TCG\\Voyager\\Models\\Role', NULL, 'App\\Http\\Controllers\\Admin\\Voyager\\RoleController', NULL, 1, 1, '{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"desc\",\"default_search_key\":null,\"scope\":null}', '2021-03-24 06:17:18', '2023-03-07 05:30:37');
INSERT INTO `data_types` (`id`, `name`, `slug`, `display_name_singular`, `display_name_plural`, `icon`, `model_name`, `policy_name`, `controller`, `description`, `generate_permissions`, `server_side`, `details`, `created_at`, `updated_at`) VALUES (14, 'translations', 'translations', 'Translation', 'Translations', NULL, 'App\\Models\\Admin\\Translation', NULL, NULL, NULL, 1, 0, '{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}', '2021-03-25 15:26:47', '2023-03-07 06:19:24');
COMMIT;

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------
BEGIN;
INSERT INTO `failed_jobs` (`id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES (1, '056a4d2a-6496-4c94-a64f-306b720716eb', 'database', 'default', '{\"uuid\":\"056a4d2a-6496-4c94-a64f-306b720716eb\",\"displayName\":\"App\\\\Jobs\\\\UpdatePaymentTransactions\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"backoff\":null,\"timeout\":0,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UpdatePaymentTransactions\",\"command\":\"O:34:\\\"App\\\\Jobs\\\\UpdatePaymentTransactions\\\":11:{s:7:\\\"timeout\\\";i:0;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 'ParseError: syntax error, unexpected \'if\' (T_IF) in /var/www/z1mobile/app/Jobs/UpdatePaymentTransactions.php:77\nStack trace:\n#0 /var/www/z1mobile/vendor/composer/ClassLoader.php(346): Composer\\Autoload\\includeFile()\n#1 [internal function]: Composer\\Autoload\\ClassLoader->loadClass()\n#2 [internal function]: spl_autoload_call()\n#3 /var/www/z1mobile/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(95): unserialize()\n#4 /var/www/z1mobile/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(60): Illuminate\\Queue\\CallQueuedHandler->getCommand()\n#5 /var/www/z1mobile/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(98): Illuminate\\Queue\\CallQueuedHandler->call()\n#6 /var/www/z1mobile/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(410): Illuminate\\Queue\\Jobs\\Job->fire()\n#7 /var/www/z1mobile/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(360): Illuminate\\Queue\\Worker->process()\n#8 /var/www/z1mobile/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(158): Illuminate\\Queue\\Worker->runJob()\n#9 /var/www/z1mobile/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(117): Illuminate\\Queue\\Worker->daemon()\n#10 /var/www/z1mobile/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(101): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#11 /var/www/z1mobile/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#12 /var/www/z1mobile/vendor/laravel/framework/src/Illuminate/Container/Util.php(40): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#13 /var/www/z1mobile/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure()\n#14 /var/www/z1mobile/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#15 /var/www/z1mobile/vendor/laravel/framework/src/Illuminate/Container/Container.php(611): Illuminate\\Container\\BoundMethod::call()\n#16 /var/www/z1mobile/vendor/laravel/framework/src/Illuminate/Console/Command.php(136): Illuminate\\Container\\Container->call()\n#17 /var/www/z1mobile/vendor/symfony/console/Command/Command.php(256): Illuminate\\Console\\Command->execute()\n#18 /var/www/z1mobile/vendor/laravel/framework/src/Illuminate/Console/Command.php(121): Symfony\\Component\\Console\\Command\\Command->run()\n#19 /var/www/z1mobile/vendor/symfony/console/Application.php(971): Illuminate\\Console\\Command->run()\n#20 /var/www/z1mobile/vendor/symfony/console/Application.php(290): Symfony\\Component\\Console\\Application->doRunCommand()\n#21 /var/www/z1mobile/vendor/symfony/console/Application.php(166): Symfony\\Component\\Console\\Application->doRun()\n#22 /var/www/z1mobile/vendor/laravel/framework/src/Illuminate/Console/Application.php(92): Symfony\\Component\\Console\\Application->run()\n#23 /var/www/z1mobile/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(129): Illuminate\\Console\\Application->run()\n#24 /var/www/z1mobile/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle()\n#25 {main}', '2021-06-07 08:15:01');
COMMIT;

-- ----------------------------
-- Table structure for menu_items
-- ----------------------------
DROP TABLE IF EXISTS `menu_items`;
CREATE TABLE `menu_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` int unsigned DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `target` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '_self',
  `icon_class` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` int DEFAULT NULL,
  `order` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `route` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parameters` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `menu_items_menu_id_foreign` (`menu_id`),
  CONSTRAINT `menu_items_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of menu_items
-- ----------------------------
BEGIN;
INSERT INTO `menu_items` (`id`, `menu_id`, `title`, `url`, `target`, `icon_class`, `color`, `parent_id`, `order`, `created_at`, `updated_at`, `route`, `parameters`) VALUES (1, 1, 'Dashboard', '', '_self', 'voyager-boat', NULL, NULL, 2, '2021-03-24 06:17:18', '2022-11-04 02:03:39', 'voyager.dashboard', NULL);
INSERT INTO `menu_items` (`id`, `menu_id`, `title`, `url`, `target`, `icon_class`, `color`, `parent_id`, `order`, `created_at`, `updated_at`, `route`, `parameters`) VALUES (2, 1, 'Media', '', '_self', 'voyager-images', NULL, 33, 12, '2021-03-24 06:17:18', '2023-01-18 03:05:06', 'voyager.media.index', NULL);
INSERT INTO `menu_items` (`id`, `menu_id`, `title`, `url`, `target`, `icon_class`, `color`, `parent_id`, `order`, `created_at`, `updated_at`, `route`, `parameters`) VALUES (3, 1, 'Users', '', '_self', 'voyager-person', NULL, 10, 1, '2021-03-24 06:17:18', '2022-11-04 07:48:53', 'voyager.users.index', NULL);
INSERT INTO `menu_items` (`id`, `menu_id`, `title`, `url`, `target`, `icon_class`, `color`, `parent_id`, `order`, `created_at`, `updated_at`, `route`, `parameters`) VALUES (4, 1, 'Roles', '', '_self', 'voyager-lock', NULL, 10, 2, '2021-03-24 06:17:18', '2022-11-04 07:48:53', 'voyager.roles.index', NULL);
INSERT INTO `menu_items` (`id`, `menu_id`, `title`, `url`, `target`, `icon_class`, `color`, `parent_id`, `order`, `created_at`, `updated_at`, `route`, `parameters`) VALUES (6, 1, 'Menu Builder', '', '_self', 'voyager-list', NULL, 33, 11, '2021-03-24 06:17:18', '2023-01-18 03:05:01', 'voyager.menus.index', NULL);
INSERT INTO `menu_items` (`id`, `menu_id`, `title`, `url`, `target`, `icon_class`, `color`, `parent_id`, `order`, `created_at`, `updated_at`, `route`, `parameters`) VALUES (7, 1, 'Database', '', '_self', 'voyager-data', NULL, 33, 10, '2021-03-24 06:17:18', '2023-01-18 03:05:01', 'voyager.database.index', NULL);
INSERT INTO `menu_items` (`id`, `menu_id`, `title`, `url`, `target`, `icon_class`, `color`, `parent_id`, `order`, `created_at`, `updated_at`, `route`, `parameters`) VALUES (8, 1, 'Compass', '', '_self', 'voyager-compass', '#000000', 33, 15, '2021-03-24 06:17:18', '2023-01-18 03:05:12', 'voyager.compass.index', 'null');
INSERT INTO `menu_items` (`id`, `menu_id`, `title`, `url`, `target`, `icon_class`, `color`, `parent_id`, `order`, `created_at`, `updated_at`, `route`, `parameters`) VALUES (9, 1, 'BREAD', '', '_self', 'voyager-bread', NULL, 33, 14, '2021-03-24 06:17:18', '2023-01-18 03:05:06', 'voyager.bread.index', NULL);
INSERT INTO `menu_items` (`id`, `menu_id`, `title`, `url`, `target`, `icon_class`, `color`, `parent_id`, `order`, `created_at`, `updated_at`, `route`, `parameters`) VALUES (10, 1, 'Users', '', '_self', 'voyager-person', '#000000', NULL, 6, '2021-03-24 06:17:18', '2023-03-03 01:57:48', NULL, '');
INSERT INTO `menu_items` (`id`, `menu_id`, `title`, `url`, `target`, `icon_class`, `color`, `parent_id`, `order`, `created_at`, `updated_at`, `route`, `parameters`) VALUES (11, 1, 'Hooks', '', '_self', 'voyager-hook', NULL, 33, 13, '2021-03-24 06:17:18', '2023-01-18 03:05:06', 'voyager.hooks', NULL);
INSERT INTO `menu_items` (`id`, `menu_id`, `title`, `url`, `target`, `icon_class`, `color`, `parent_id`, `order`, `created_at`, `updated_at`, `route`, `parameters`) VALUES (33, 1, 'Settings', '', '_self', 'voyager-settings', '#000000', NULL, 4, '2021-03-30 08:30:37', '2023-03-03 01:57:48', NULL, '');
INSERT INTO `menu_items` (`id`, `menu_id`, `title`, `url`, `target`, `icon_class`, `color`, `parent_id`, `order`, `created_at`, `updated_at`, `route`, `parameters`) VALUES (37, 1, 'System Settings', '', '_self', 'voyager-settings', '#000000', 33, 9, '2021-04-06 07:12:33', '2023-01-18 03:05:01', 'voyager.settings.index', 'null');
INSERT INTO `menu_items` (`id`, `menu_id`, `title`, `url`, `target`, `icon_class`, `color`, `parent_id`, `order`, `created_at`, `updated_at`, `route`, `parameters`) VALUES (65, 1, 'Pricing Plans', '/admin/pricing-plan', '_self', 'voyager-dollar', '#000000', 64, 1, '2022-11-03 03:11:59', '2022-11-03 03:13:12', NULL, '');
INSERT INTO `menu_items` (`id`, `menu_id`, `title`, `url`, `target`, `icon_class`, `color`, `parent_id`, `order`, `created_at`, `updated_at`, `route`, `parameters`) VALUES (66, 1, 'Get Started', '/admin/getting-started', '_self', 'voyager-paper-plane', '#000000', NULL, 1, '2022-11-04 02:03:24', '2022-11-04 02:04:11', NULL, '');
COMMIT;

-- ----------------------------
-- Table structure for menus
-- ----------------------------
DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `menus_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of menus
-- ----------------------------
BEGIN;
INSERT INTO `menus` (`id`, `name`, `created_at`, `updated_at`) VALUES (1, 'admin', '2021-03-24 06:17:18', '2021-05-28 09:09:14');
INSERT INTO `menus` (`id`, `name`, `created_at`, `updated_at`) VALUES (2, 'Guest', '2021-03-24 14:01:54', '2021-03-24 14:01:54');
COMMIT;

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=204 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
BEGIN;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2, '2014_10_12_100000_create_password_resets_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3, '2016_01_01_000000_add_voyager_user_fields', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4, '2016_01_01_000000_create_data_types_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5, '2016_05_19_173453_create_menu_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6, '2016_10_21_190000_create_roles_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7, '2016_10_21_190000_create_settings_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8, '2016_11_30_135954_create_permission_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9, '2016_11_30_141208_create_permission_role_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10, '2016_12_26_201236_data_types__add__server_side', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11, '2017_01_13_000000_add_route_to_menu_items_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12, '2017_01_14_005015_create_translations_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13, '2017_01_15_000000_make_table_name_nullable_in_permissions_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14, '2017_03_06_000000_add_controller_to_data_types_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15, '2017_04_21_000000_add_order_to_data_rows_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16, '2017_07_05_210000_add_policyname_to_data_types_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17, '2017_08_05_000000_add_group_to_settings_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18, '2017_11_26_013050_add_user_role_relationship', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19, '2017_11_26_015000_create_user_roles_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20, '2018_03_11_000000_add_user_settings', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21, '2018_03_14_000000_add_details_to_data_types_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22, '2018_03_16_000000_make_settings_value_nullable', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (23, '2019_08_19_000000_create_failed_jobs_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (24, '2016_01_01_000000_create_pages_table', 2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (25, '2016_01_01_000000_create_posts_table', 2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (26, '2016_02_15_204651_create_categories_table', 2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (27, '2017_04_11_000000_alter_post_nullable_fields_table', 2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (32, '2021_03_25_064317_create_custom_values_table', 4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (36, '2021_03_30_053825_create_merchant_table', 7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (43, '2016_06_01_000001_create_oauth_auth_codes_table', 9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (45, '2016_06_01_000003_create_oauth_refresh_tokens_table', 11);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (46, '2016_06_01_000004_create_oauth_clients_table', 12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (47, '2016_06_01_000005_create_oauth_personal_access_clients_table', 12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (78, '2022_10_10_154242_add_customer_id_to_order_table', 24);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (79, '2022_10_12_072102_alter_merchant_table', 25);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (81, '2022_10_12_082357_create_countries_table', 27);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (88, '2022_10_13_065938_alter_carrier', 34);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (89, '2022_10_14_043007_create_developers_table', 35);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (90, '2022_10_14_043144_create_documents_table', 35);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (91, '2019_12_14_000001_create_personal_access_tokens_table', 36);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (99, '2022_10_17_041526_add_uuid_to_countries_table', 37);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (100, '2022_10_17_042434_add_uuid_to_currency_table', 37);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (111, '2022_10_18_082122_add-brand-id-developer', 41);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (113, '2022_10_19_062125_add-brand-id-order', 43);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (114, '2022_10_19_124913_alter_personal_access_token_table', 44);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (115, '2022_10_20_124344_add-more-customer', 45);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (119, '2022_10_24_080856_alter_country_column', 49);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (123, '2022_11_01_043912_create_api_logs_table', 53);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (125, '2022_11_03_054540_alter_table_developers_change_expired_at', 55);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (126, '2022_11_03_092505_drop-unique-sku-product', 56);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (132, '2022_11_14_061523_remove-order-softdelete', 62);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (148, '2022_11_15_061931_add_field_phone_to_country', 67);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (152, '2022_11_15_031059_create_merchant_fees_table', 70);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (154, '2022_11_16_085852_drop_ccy_from_merchant_fee_table', 72);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (157, '2022_11_18_014027_alter_001_currency_table', 75);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (158, '2022_11_22_024439_add-brand-id-customer', 76);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (159, '2022_11_22_055445_add-tag-product', 77);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (160, '2022_11_23_022206_alter_table_reason_remove_merchant_id', 78);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (164, '2022_11_24_081243_add-default-to-brand', 81);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (165, '2022_11_24_092612_drop-api-screct-delevepoer', 82);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (166, '2022_11_24_034625_alter_table_product_description_attribute', 83);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (169, '2022_11_24_044241_create_merchant_store_tokens_table', 84);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (170, '2022_11_28_025910_add_field_table_merchant_store_tokens', 85);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (172, '2022_12_07_072300_add_field_table_merchant_store_tokens', 87);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (173, '2022_12_07_141408_alter_001_description_product_table', 88);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (174, '2022_12_07_151358_alter_002_description_product_table', 89);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (177, '2022_12_11_032959_create_horizon_table', 92);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (180, '2022_12_16_063706_add_brand_id_to_operate_country_service', 95);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (181, '2022_12_16_065605_add_brand_id_to_merchant_store_tokens', 96);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (188, '2022_11_15_034947_create_fix_fees_table', 102);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (189, '2022_11_15_040244_create_margin_fees_table', 103);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (190, '2022_11_15_040440_create_item_fees_table', 104);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (191, '2022_10_20_133405_change_customer_id_type', 105);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (201, '2016_06_01_000002_create_oauth_access_tokens_table', 10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (203, '2023_03_07_095758_create_customers_table', 106);
COMMIT;

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of password_resets
-- ----------------------------
BEGIN;
INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES ('admin@admin.com', '$2y$10$2I4.CTWQFW7izhXc.S0C/ukldsfOk045yabVCQStkzSS6ofibXH3e', '2023-03-07 08:33:44');
COMMIT;

-- ----------------------------
-- Table structure for permission_role
-- ----------------------------
DROP TABLE IF EXISTS `permission_role`;
CREATE TABLE `permission_role` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `permission_role_permission_id_index` (`permission_id`),
  KEY `permission_role_role_id_index` (`role_id`),
  CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of permission_role
-- ----------------------------
BEGIN;
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (1, 1);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (1, 22);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (1, 66);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (1, 67);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (1, 68);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (1, 69);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (2, 1);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (3, 1);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (4, 1);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (5, 1);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (6, 1);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (6, 70);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (7, 1);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (7, 70);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (8, 1);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (8, 70);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (9, 1);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (9, 70);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (10, 1);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (10, 70);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (11, 1);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (11, 66);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (11, 70);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (12, 1);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (12, 66);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (12, 70);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (13, 1);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (13, 66);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (13, 70);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (14, 1);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (14, 66);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (14, 70);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (15, 1);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (15, 66);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (15, 70);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (16, 1);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (16, 66);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (16, 67);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (16, 68);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (16, 70);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (17, 1);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (17, 66);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (17, 67);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (17, 68);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (17, 70);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (18, 1);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (18, 66);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (18, 67);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (18, 68);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (18, 70);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (19, 1);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (19, 66);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (19, 67);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (19, 68);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (19, 70);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (20, 1);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (20, 66);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (20, 67);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (20, 68);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (20, 70);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (21, 1);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (21, 70);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (22, 1);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (22, 70);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (23, 1);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (23, 70);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (24, 1);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (24, 70);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (25, 1);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (25, 70);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (26, 1);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (62, 1);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (62, 70);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (63, 1);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (63, 70);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (64, 1);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (64, 70);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (65, 1);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (65, 70);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (66, 1);
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES (66, 70);
COMMIT;

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permissions_key_index` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=352 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of permissions
-- ----------------------------
BEGIN;
INSERT INTO `permissions` (`id`, `key`, `table_name`, `created_at`, `updated_at`) VALUES (1, 'browse_admin', NULL, '2021-03-24 06:17:18', '2021-03-24 06:17:18');
INSERT INTO `permissions` (`id`, `key`, `table_name`, `created_at`, `updated_at`) VALUES (2, 'browse_bread', NULL, '2021-03-24 06:17:18', '2021-03-24 06:17:18');
INSERT INTO `permissions` (`id`, `key`, `table_name`, `created_at`, `updated_at`) VALUES (3, 'browse_database', NULL, '2021-03-24 06:17:18', '2021-03-24 06:17:18');
INSERT INTO `permissions` (`id`, `key`, `table_name`, `created_at`, `updated_at`) VALUES (4, 'browse_media', NULL, '2021-03-24 06:17:18', '2021-03-24 06:17:18');
INSERT INTO `permissions` (`id`, `key`, `table_name`, `created_at`, `updated_at`) VALUES (5, 'browse_compass', NULL, '2021-03-24 06:17:18', '2021-03-24 06:17:18');
INSERT INTO `permissions` (`id`, `key`, `table_name`, `created_at`, `updated_at`) VALUES (6, 'browse_menus', 'menus', '2021-03-24 06:17:18', '2021-03-24 06:17:18');
INSERT INTO `permissions` (`id`, `key`, `table_name`, `created_at`, `updated_at`) VALUES (7, 'read_menus', 'menus', '2021-03-24 06:17:18', '2021-03-24 06:17:18');
INSERT INTO `permissions` (`id`, `key`, `table_name`, `created_at`, `updated_at`) VALUES (8, 'edit_menus', 'menus', '2021-03-24 06:17:18', '2021-03-24 06:17:18');
INSERT INTO `permissions` (`id`, `key`, `table_name`, `created_at`, `updated_at`) VALUES (9, 'add_menus', 'menus', '2021-03-24 06:17:18', '2021-03-24 06:17:18');
INSERT INTO `permissions` (`id`, `key`, `table_name`, `created_at`, `updated_at`) VALUES (10, 'delete_menus', 'menus', '2021-03-24 06:17:18', '2021-03-24 06:17:18');
INSERT INTO `permissions` (`id`, `key`, `table_name`, `created_at`, `updated_at`) VALUES (11, 'browse_roles', 'roles', '2021-03-24 06:17:18', '2021-03-24 06:17:18');
INSERT INTO `permissions` (`id`, `key`, `table_name`, `created_at`, `updated_at`) VALUES (12, 'read_roles', 'roles', '2021-03-24 06:17:18', '2021-03-24 06:17:18');
INSERT INTO `permissions` (`id`, `key`, `table_name`, `created_at`, `updated_at`) VALUES (13, 'edit_roles', 'roles', '2021-03-24 06:17:18', '2021-03-24 06:17:18');
INSERT INTO `permissions` (`id`, `key`, `table_name`, `created_at`, `updated_at`) VALUES (14, 'add_roles', 'roles', '2021-03-24 06:17:18', '2021-03-24 06:17:18');
INSERT INTO `permissions` (`id`, `key`, `table_name`, `created_at`, `updated_at`) VALUES (15, 'delete_roles', 'roles', '2021-03-24 06:17:18', '2021-03-24 06:17:18');
INSERT INTO `permissions` (`id`, `key`, `table_name`, `created_at`, `updated_at`) VALUES (16, 'browse_users', 'users', '2021-03-24 06:17:18', '2021-03-24 06:17:18');
INSERT INTO `permissions` (`id`, `key`, `table_name`, `created_at`, `updated_at`) VALUES (17, 'read_users', 'users', '2021-03-24 06:17:18', '2021-03-24 06:17:18');
INSERT INTO `permissions` (`id`, `key`, `table_name`, `created_at`, `updated_at`) VALUES (18, 'edit_users', 'users', '2021-03-24 06:17:18', '2021-03-24 06:17:18');
INSERT INTO `permissions` (`id`, `key`, `table_name`, `created_at`, `updated_at`) VALUES (19, 'add_users', 'users', '2021-03-24 06:17:18', '2021-03-24 06:17:18');
INSERT INTO `permissions` (`id`, `key`, `table_name`, `created_at`, `updated_at`) VALUES (20, 'delete_users', 'users', '2021-03-24 06:17:18', '2021-03-24 06:17:18');
INSERT INTO `permissions` (`id`, `key`, `table_name`, `created_at`, `updated_at`) VALUES (21, 'browse_settings', 'settings', '2021-03-24 06:17:18', '2021-03-24 06:17:18');
INSERT INTO `permissions` (`id`, `key`, `table_name`, `created_at`, `updated_at`) VALUES (22, 'read_settings', 'settings', '2021-03-24 06:17:18', '2021-03-24 06:17:18');
INSERT INTO `permissions` (`id`, `key`, `table_name`, `created_at`, `updated_at`) VALUES (23, 'edit_settings', 'settings', '2021-03-24 06:17:18', '2021-03-24 06:17:18');
INSERT INTO `permissions` (`id`, `key`, `table_name`, `created_at`, `updated_at`) VALUES (24, 'add_settings', 'settings', '2021-03-24 06:17:18', '2021-03-24 06:17:18');
INSERT INTO `permissions` (`id`, `key`, `table_name`, `created_at`, `updated_at`) VALUES (25, 'delete_settings', 'settings', '2021-03-24 06:17:18', '2021-03-24 06:17:18');
INSERT INTO `permissions` (`id`, `key`, `table_name`, `created_at`, `updated_at`) VALUES (26, 'browse_hooks', NULL, '2021-03-24 06:17:18', '2021-03-24 06:17:18');
INSERT INTO `permissions` (`id`, `key`, `table_name`, `created_at`, `updated_at`) VALUES (62, 'browse_translations', 'translations', '2021-03-25 15:26:47', '2021-03-25 15:26:47');
INSERT INTO `permissions` (`id`, `key`, `table_name`, `created_at`, `updated_at`) VALUES (63, 'read_translations', 'translations', '2021-03-25 15:26:47', '2021-03-25 15:26:47');
INSERT INTO `permissions` (`id`, `key`, `table_name`, `created_at`, `updated_at`) VALUES (64, 'edit_translations', 'translations', '2021-03-25 15:26:47', '2021-03-25 15:26:47');
INSERT INTO `permissions` (`id`, `key`, `table_name`, `created_at`, `updated_at`) VALUES (65, 'add_translations', 'translations', '2021-03-25 15:26:47', '2021-03-25 15:26:47');
INSERT INTO `permissions` (`id`, `key`, `table_name`, `created_at`, `updated_at`) VALUES (66, 'delete_translations', 'translations', '2021-03-25 15:26:47', '2021-03-25 15:26:47');
COMMIT;

-- ----------------------------
-- Table structure for personal_access_tokens
-- ----------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payload` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2905 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of personal_access_tokens
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `merchant_list` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` tinyint NOT NULL DEFAULT '0',
  `is_z1payment_technical` tinyint DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of roles
-- ----------------------------
BEGIN;
INSERT INTO `roles` (`id`, `name`, `display_name`, `created_at`, `updated_at`, `created_by`, `updated_by`, `deleted_at`, `merchant_list`, `type`, `is_z1payment_technical`) VALUES (1, 'super-admin', 'Super Administrator', '2021-03-24 06:17:18', '2023-03-07 06:28:19', NULL, 1, NULL, '[]', 0, 0);
INSERT INTO `roles` (`id`, `name`, `display_name`, `created_at`, `updated_at`, `created_by`, `updated_by`, `deleted_at`, `merchant_list`, `type`, `is_z1payment_technical`) VALUES (70, 'User', 'Normal User', '2023-03-06 07:22:54', '2023-03-07 06:28:15', 1, 1, NULL, '[]', 1, 0);
COMMIT;

-- ----------------------------
-- Table structure for settings
-- ----------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int NOT NULL DEFAULT '1',
  `group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of settings
-- ----------------------------
BEGIN;
INSERT INTO `settings` (`id`, `key`, `display_name`, `value`, `details`, `type`, `order`, `group`) VALUES (1, 'site.title', 'Site Title', 'POS DRC', '', 'text', 1, 'Site');
INSERT INTO `settings` (`id`, `key`, `display_name`, `value`, `details`, `type`, `order`, `group`) VALUES (2, 'site.description', 'Site Description', 'POS DRC', '', 'text', 2, 'Site');
INSERT INTO `settings` (`id`, `key`, `display_name`, `value`, `details`, `type`, `order`, `group`) VALUES (3, 'site.logo', 'Site Logo', 'settings/December2022/ICtcLdZR44cTNPBLmuUP.gif', '', 'image', 3, 'Site');
INSERT INTO `settings` (`id`, `key`, `display_name`, `value`, `details`, `type`, `order`, `group`) VALUES (4, 'site.google_analytics_tracking_id', 'Google Analytics Tracking ID', '131569783', '', 'text', 4, 'Site');
INSERT INTO `settings` (`id`, `key`, `display_name`, `value`, `details`, `type`, `order`, `group`) VALUES (5, 'admin.bg_image', 'Admin Background Image', 'settings/April2023/zVbGPpWgPrOtJkoYsVkk.png', '', 'image', 5, 'Admin');
INSERT INTO `settings` (`id`, `key`, `display_name`, `value`, `details`, `type`, `order`, `group`) VALUES (6, 'admin.title', 'Admin Title', 'POS DRC', '', 'text', 1, 'Admin');
INSERT INTO `settings` (`id`, `key`, `display_name`, `value`, `details`, `type`, `order`, `group`) VALUES (7, 'admin.description', 'Admin Description', NULL, '', 'text', 4, 'Admin');
INSERT INTO `settings` (`id`, `key`, `display_name`, `value`, `details`, `type`, `order`, `group`) VALUES (8, 'admin.loader', 'Admin Loader', 'settings/April2023/VP8La9zriDrgMYBc41IR.png', '', 'image', 1, 'Admin');
INSERT INTO `settings` (`id`, `key`, `display_name`, `value`, `details`, `type`, `order`, `group`) VALUES (9, 'admin.icon_image', 'Admin Icon Image', 'settings/April2023/HPmErtRSBrl05Z0b8KoH.png', '', 'image', 3, 'Admin');
INSERT INTO `settings` (`id`, `key`, `display_name`, `value`, `details`, `type`, `order`, `group`) VALUES (10, 'admin.google_analytics_client_id', 'Google Analytics Client ID (used for admin dashboard)', NULL, '', 'text', 2, 'Admin');
COMMIT;

-- ----------------------------
-- Table structure for translations
-- ----------------------------
DROP TABLE IF EXISTS `translations`;
CREATE TABLE `translations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `table_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `column_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `foreign_key` int unsigned NOT NULL,
  `locale` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `translations_table_name_column_name_foreign_key_locale_unique` (`table_name`,`column_name`,`foreign_key`,`locale`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of translations
-- ----------------------------
BEGIN;
INSERT INTO `translations` (`id`, `table_name`, `column_name`, `foreign_key`, `locale`, `value`, `created_at`, `updated_at`) VALUES (1, 'data_types', 'display_name_singular', 5, 'pt', 'Post', '2021-03-24 06:17:36', '2021-03-24 06:17:36');
INSERT INTO `translations` (`id`, `table_name`, `column_name`, `foreign_key`, `locale`, `value`, `created_at`, `updated_at`) VALUES (2, 'data_types', 'display_name_singular', 6, 'pt', 'Página', '2021-03-24 06:17:36', '2021-03-24 06:17:36');
INSERT INTO `translations` (`id`, `table_name`, `column_name`, `foreign_key`, `locale`, `value`, `created_at`, `updated_at`) VALUES (3, 'data_types', 'display_name_singular', 1, 'pt', 'Utilizador', '2021-03-24 06:17:36', '2021-03-24 06:17:36');
INSERT INTO `translations` (`id`, `table_name`, `column_name`, `foreign_key`, `locale`, `value`, `created_at`, `updated_at`) VALUES (4, 'data_types', 'display_name_singular', 4, 'pt', 'Categoria', '2021-03-24 06:17:36', '2021-03-24 06:17:36');
INSERT INTO `translations` (`id`, `table_name`, `column_name`, `foreign_key`, `locale`, `value`, `created_at`, `updated_at`) VALUES (5, 'data_types', 'display_name_singular', 2, 'pt', 'Menu', '2021-03-24 06:17:36', '2021-03-24 06:17:36');
INSERT INTO `translations` (`id`, `table_name`, `column_name`, `foreign_key`, `locale`, `value`, `created_at`, `updated_at`) VALUES (6, 'data_types', 'display_name_singular', 3, 'pt', 'Função', '2021-03-24 06:17:36', '2021-03-24 06:17:36');
INSERT INTO `translations` (`id`, `table_name`, `column_name`, `foreign_key`, `locale`, `value`, `created_at`, `updated_at`) VALUES (7, 'data_types', 'display_name_plural', 5, 'pt', 'Posts', '2021-03-24 06:17:36', '2021-03-24 06:17:36');
INSERT INTO `translations` (`id`, `table_name`, `column_name`, `foreign_key`, `locale`, `value`, `created_at`, `updated_at`) VALUES (8, 'data_types', 'display_name_plural', 6, 'pt', 'Páginas', '2021-03-24 06:17:36', '2021-03-24 06:17:36');
INSERT INTO `translations` (`id`, `table_name`, `column_name`, `foreign_key`, `locale`, `value`, `created_at`, `updated_at`) VALUES (9, 'data_types', 'display_name_plural', 1, 'pt', 'Utilizadores', '2021-03-24 06:17:36', '2021-03-24 06:17:36');
INSERT INTO `translations` (`id`, `table_name`, `column_name`, `foreign_key`, `locale`, `value`, `created_at`, `updated_at`) VALUES (10, 'data_types', 'display_name_plural', 4, 'pt', 'Categorias', '2021-03-24 06:17:36', '2021-03-24 06:17:36');
INSERT INTO `translations` (`id`, `table_name`, `column_name`, `foreign_key`, `locale`, `value`, `created_at`, `updated_at`) VALUES (11, 'data_types', 'display_name_plural', 2, 'pt', 'Menus', '2021-03-24 06:17:36', '2021-03-24 06:17:36');
INSERT INTO `translations` (`id`, `table_name`, `column_name`, `foreign_key`, `locale`, `value`, `created_at`, `updated_at`) VALUES (12, 'data_types', 'display_name_plural', 3, 'pt', 'Funções', '2021-03-24 06:17:36', '2021-03-24 06:17:36');
INSERT INTO `translations` (`id`, `table_name`, `column_name`, `foreign_key`, `locale`, `value`, `created_at`, `updated_at`) VALUES (13, 'categories', 'slug', 1, 'pt', 'categoria-1', '2021-03-24 06:17:36', '2021-03-24 06:17:36');
INSERT INTO `translations` (`id`, `table_name`, `column_name`, `foreign_key`, `locale`, `value`, `created_at`, `updated_at`) VALUES (14, 'categories', 'name', 1, 'pt', 'Categoria 1', '2021-03-24 06:17:36', '2021-03-24 06:17:36');
INSERT INTO `translations` (`id`, `table_name`, `column_name`, `foreign_key`, `locale`, `value`, `created_at`, `updated_at`) VALUES (15, 'categories', 'slug', 2, 'pt', 'categoria-2', '2021-03-24 06:17:36', '2021-03-24 06:17:36');
INSERT INTO `translations` (`id`, `table_name`, `column_name`, `foreign_key`, `locale`, `value`, `created_at`, `updated_at`) VALUES (16, 'categories', 'name', 2, 'pt', 'Categoria 2', '2021-03-24 06:17:36', '2021-03-24 06:17:36');
INSERT INTO `translations` (`id`, `table_name`, `column_name`, `foreign_key`, `locale`, `value`, `created_at`, `updated_at`) VALUES (17, 'pages', 'title', 1, 'pt', 'Olá Mundo', '2021-03-24 06:17:36', '2021-03-24 06:17:36');
INSERT INTO `translations` (`id`, `table_name`, `column_name`, `foreign_key`, `locale`, `value`, `created_at`, `updated_at`) VALUES (18, 'pages', 'slug', 1, 'pt', 'ola-mundo', '2021-03-24 06:17:36', '2021-03-24 06:17:36');
INSERT INTO `translations` (`id`, `table_name`, `column_name`, `foreign_key`, `locale`, `value`, `created_at`, `updated_at`) VALUES (19, 'pages', 'body', 1, 'pt', '<p>Olá Mundo. Scallywag grog swab Cat o\'nine tails scuttle rigging hardtack cable nipper Yellow Jack. Handsomely spirits knave lad killick landlubber or just lubber deadlights chantey pinnace crack Jennys tea cup. Provost long clothes black spot Yellow Jack bilged on her anchor league lateen sail case shot lee tackle.</p>\r\n<p>Ballast spirits fluke topmast me quarterdeck schooner landlubber or just lubber gabion belaying pin. Pinnace stern galleon starboard warp carouser to go on account dance the hempen jig jolly boat measured fer yer chains. Man-of-war fire in the hole nipperkin handsomely doubloon barkadeer Brethren of the Coast gibbet driver squiffy.</p>', '2021-03-24 06:17:36', '2021-03-24 06:17:36');
INSERT INTO `translations` (`id`, `table_name`, `column_name`, `foreign_key`, `locale`, `value`, `created_at`, `updated_at`) VALUES (20, 'menu_items', 'title', 1, 'pt', 'Painel de Controle', '2021-03-24 06:17:36', '2021-03-24 06:17:36');
INSERT INTO `translations` (`id`, `table_name`, `column_name`, `foreign_key`, `locale`, `value`, `created_at`, `updated_at`) VALUES (21, 'menu_items', 'title', 2, 'pt', 'Media', '2021-03-24 06:17:36', '2021-03-24 06:17:36');
INSERT INTO `translations` (`id`, `table_name`, `column_name`, `foreign_key`, `locale`, `value`, `created_at`, `updated_at`) VALUES (22, 'menu_items', 'title', 13, 'pt', 'Publicações', '2021-03-24 06:17:36', '2021-03-24 06:17:36');
INSERT INTO `translations` (`id`, `table_name`, `column_name`, `foreign_key`, `locale`, `value`, `created_at`, `updated_at`) VALUES (23, 'menu_items', 'title', 3, 'pt', 'Utilizadores', '2021-03-24 06:17:36', '2021-03-24 06:17:36');
INSERT INTO `translations` (`id`, `table_name`, `column_name`, `foreign_key`, `locale`, `value`, `created_at`, `updated_at`) VALUES (24, 'menu_items', 'title', 12, 'pt', 'Categorias', '2021-03-24 06:17:36', '2021-03-24 06:17:36');
INSERT INTO `translations` (`id`, `table_name`, `column_name`, `foreign_key`, `locale`, `value`, `created_at`, `updated_at`) VALUES (25, 'menu_items', 'title', 14, 'pt', 'Páginas', '2021-03-24 06:17:36', '2021-03-24 06:17:36');
INSERT INTO `translations` (`id`, `table_name`, `column_name`, `foreign_key`, `locale`, `value`, `created_at`, `updated_at`) VALUES (26, 'menu_items', 'title', 4, 'pt', 'Funções', '2021-03-24 06:17:36', '2021-03-24 06:17:36');
INSERT INTO `translations` (`id`, `table_name`, `column_name`, `foreign_key`, `locale`, `value`, `created_at`, `updated_at`) VALUES (27, 'menu_items', 'title', 5, 'pt', 'Ferramentas', '2021-03-24 06:17:36', '2021-03-24 06:17:36');
INSERT INTO `translations` (`id`, `table_name`, `column_name`, `foreign_key`, `locale`, `value`, `created_at`, `updated_at`) VALUES (28, 'menu_items', 'title', 6, 'pt', 'Menus', '2021-03-24 06:17:36', '2021-03-24 06:17:36');
INSERT INTO `translations` (`id`, `table_name`, `column_name`, `foreign_key`, `locale`, `value`, `created_at`, `updated_at`) VALUES (29, 'menu_items', 'title', 7, 'pt', 'Base de dados', '2021-03-24 06:17:36', '2021-03-24 06:17:36');
INSERT INTO `translations` (`id`, `table_name`, `column_name`, `foreign_key`, `locale`, `value`, `created_at`, `updated_at`) VALUES (30, 'menu_items', 'title', 10, 'pt', 'Configurações', '2021-03-24 06:17:36', '2021-03-24 06:17:36');
COMMIT;

-- ----------------------------
-- Table structure for user_roles
-- ----------------------------
DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE `user_roles` (
  `user_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `user_roles_user_id_index` (`user_id`),
  KEY `user_roles_role_id_index` (`role_id`),
  CONSTRAINT `user_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of user_roles
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'users/default.png',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `settings` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `merchant_id` int DEFAULT '0',
  `is_locked` tinyint DEFAULT '0',
  `timezone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `type` tinyint DEFAULT '0',
  `integration_id` int DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_id_foreign` (`role_id`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
BEGIN;
INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `avatar`, `email_verified_at`, `password`, `remember_token`, `settings`, `created_at`, `updated_at`, `deleted_at`, `merchant_id`, `is_locked`, `timezone`, `created_by`, `type`, `integration_id`) VALUES (1, 1, 'Super Administrator', 'admin@admin.com', 'users/April2023/R7DmYN7o5x5Zpy3Bi3vr.png', NULL, '$2y$10$/9vEmMEb3.ajLkl1dnKE..hXngiRaSSAz8iBndso/dn20vsNMIbdu', 'mToGmNi2QxC5vo0fBgkrcVmjbeLOqoMMMjQwY3GHseSM1qGPp3xZ0UJXSFJ1', '{\"locale\":\"en\"}', '2021-03-24 06:17:36', '2023-04-19 12:53:02', NULL, NULL, NULL, 'Asia/Bangkok', NULL, 0, 0);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
