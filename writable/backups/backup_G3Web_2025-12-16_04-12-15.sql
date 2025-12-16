-- Database Backup: G3Web
-- Generated: 2025-12-16 04:12:15

SET FOREIGN_KEY_CHECKS=0;



-- Table structure for `audit_trail`
DROP TABLE IF EXISTS `audit_trail`;
CREATE TABLE `audit_trail` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL COMMENT 'User who performed the action',
  `username` varchar(100) NOT NULL COMMENT 'Username for quick reference',
  `module` varchar(100) NOT NULL COMMENT 'Module affected (inventory, orders, payments, etc)',
  `controller` varchar(100) NOT NULL COMMENT 'Controller name',
  `action` enum('CREATE','READ','UPDATE','DELETE','APPROVE','REJECT','TRANSFER','LOGIN','LOGOUT','EXPORT','IMPORT','RECONCILE','VERIFY','AUTHORIZE') NOT NULL COMMENT 'Type of action performed',
  `table_name` varchar(100) NOT NULL COMMENT 'Database table affected',
  `record_id` int(11) unsigned DEFAULT NULL COMMENT 'ID of record affected',
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Original values before change' CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'New values after change' CHECK (json_valid(`new_values`)),
  `changes_summary` text DEFAULT NULL COMMENT 'Human-readable summary of changes',
  `description` text DEFAULT NULL COMMENT 'Additional context or description',
  `status` enum('Success','Failure','Pending','Cancelled') NOT NULL DEFAULT 'Success' COMMENT 'Result of the action',
  `ip_address` varchar(50) NOT NULL COMMENT 'IP address of request',
  `user_agent` varchar(255) DEFAULT NULL COMMENT 'Browser/client user agent',
  `request_method` varchar(10) NOT NULL COMMENT 'HTTP method (GET, POST, etc)',
  `endpoint` varchar(255) NOT NULL COMMENT 'API endpoint or page accessed',
  `error_message` text DEFAULT NULL COMMENT 'Error message if action failed',
  `execution_time_ms` int(11) DEFAULT NULL COMMENT 'Action execution time in milliseconds',
  `timestamp` datetime NOT NULL COMMENT 'When action occurred',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `module` (`module`),
  KEY `action` (`action`),
  KEY `table_name` (`table_name`),
  KEY `record_id` (`record_id`),
  KEY `status` (`status`),
  KEY `timestamp` (`timestamp`),
  KEY `ip_address` (`ip_address`),
  KEY `idx_timestamp_user` (`timestamp`,`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=267 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for table `audit_trail`
INSERT INTO `audit_trail` VALUES ('1', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/dashboard', NULL, NULL, '2025-12-15 11:16:23', '2025-12-15 11:16:23');
INSERT INTO `audit_trail` VALUES ('2', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/user-accounts', NULL, NULL, '2025-12-15 11:18:00', '2025-12-15 11:18:00');
INSERT INTO `audit_trail` VALUES ('3', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/roles-permissions', NULL, NULL, '2025-12-15 11:19:24', '2025-12-15 11:19:24');
INSERT INTO `audit_trail` VALUES ('4', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/active-sessions', NULL, NULL, '2025-12-15 11:19:43', '2025-12-15 11:19:43');
INSERT INTO `audit_trail` VALUES ('5', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/security-policies', NULL, NULL, '2025-12-15 11:19:56', '2025-12-15 11:19:56');
INSERT INTO `audit_trail` VALUES ('6', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/audit-logs', NULL, NULL, '2025-12-15 11:20:00', '2025-12-15 11:20:00');
INSERT INTO `audit_trail` VALUES ('7', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/system-health', NULL, NULL, '2025-12-15 11:20:08', '2025-12-15 11:20:08');
INSERT INTO `audit_trail` VALUES ('8', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/database-management', NULL, NULL, '2025-12-15 11:20:13', '2025-12-15 11:20:13');
INSERT INTO `audit_trail` VALUES ('9', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/backup-recovery', NULL, NULL, '2025-12-15 11:20:17', '2025-12-15 11:20:17');
INSERT INTO `audit_trail` VALUES ('10', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/user-accounts', NULL, NULL, '2025-12-15 11:20:28', '2025-12-15 11:20:28');
INSERT INTO `audit_trail` VALUES ('11', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/dashboard', NULL, NULL, '2025-12-15 11:20:30', '2025-12-15 11:20:30');
INSERT INTO `audit_trail` VALUES ('12', '2', 'manager', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'manager\' accessed \'management\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/management/dashboard', NULL, NULL, '2025-12-15 11:21:00', '2025-12-15 11:21:00');
INSERT INTO `audit_trail` VALUES ('13', '2', 'manager', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'manager\' accessed \'management\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/management/dashboard', NULL, NULL, '2025-12-15 12:06:16', '2025-12-15 12:06:16');
INSERT INTO `audit_trail` VALUES ('14', '2', 'manager', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'manager\' accessed \'management\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/management/dashboard', NULL, NULL, '2025-12-15 12:06:20', '2025-12-15 12:06:20');
INSERT INTO `audit_trail` VALUES ('15', '2', 'manager', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'manager\' accessed \'management\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/management/financial_reports.php', NULL, NULL, '2025-12-15 12:11:57', '2025-12-15 12:11:57');
INSERT INTO `audit_trail` VALUES ('16', '2', 'manager', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'manager\' accessed \'management\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/management/financial-reports', NULL, NULL, '2025-12-15 12:11:57', '2025-12-15 12:11:57');
INSERT INTO `audit_trail` VALUES ('17', '2', 'manager', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'manager\' accessed \'management\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/management/financial-reports', NULL, NULL, '2025-12-15 12:14:28', '2025-12-15 12:14:28');
INSERT INTO `audit_trail` VALUES ('18', '2', 'manager', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'manager\' accessed \'management\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/management/inventory-overview', NULL, NULL, '2025-12-15 12:14:31', '2025-12-15 12:14:31');
INSERT INTO `audit_trail` VALUES ('19', '2', 'manager', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'manager\' accessed \'management\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/management/warehouse-analytics', NULL, NULL, '2025-12-15 12:14:32', '2025-12-15 12:14:32');
INSERT INTO `audit_trail` VALUES ('20', '2', 'manager', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'manager\' accessed \'management\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/management/warehouse-analytics', NULL, NULL, '2025-12-15 12:19:03', '2025-12-15 12:19:03');
INSERT INTO `audit_trail` VALUES ('21', '2', 'manager', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'manager\' accessed \'management\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/management/forecasting', NULL, NULL, '2025-12-15 12:19:08', '2025-12-15 12:19:08');
INSERT INTO `audit_trail` VALUES ('22', '2', 'manager', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'manager\' accessed \'management\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/management/performance-kpis', NULL, NULL, '2025-12-15 12:19:11', '2025-12-15 12:19:11');
INSERT INTO `audit_trail` VALUES ('23', '2', 'manager', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'manager\' accessed \'management\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/management/executive-reports', NULL, NULL, '2025-12-15 12:19:13', '2025-12-15 12:19:13');
INSERT INTO `audit_trail` VALUES ('24', '2', 'manager', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'manager\' accessed \'management\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/management/executive-reports', NULL, NULL, '2025-12-15 12:20:40', '2025-12-15 12:20:40');
INSERT INTO `audit_trail` VALUES ('25', '2', 'manager', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'manager\' accessed \'management\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/management/monthly-report', NULL, NULL, '2025-12-15 12:20:41', '2025-12-15 12:20:41');
INSERT INTO `audit_trail` VALUES ('26', '2', 'manager', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'manager\' accessed \'management\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/management/quarterly-report', NULL, NULL, '2025-12-15 12:20:50', '2025-12-15 12:20:50');
INSERT INTO `audit_trail` VALUES ('27', '2', 'manager', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'manager\' accessed \'management\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/management/financial-reports', NULL, NULL, '2025-12-15 12:20:55', '2025-12-15 12:20:55');
INSERT INTO `audit_trail` VALUES ('28', '2', 'manager', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'manager\' accessed \'management\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/management/forecasting', NULL, NULL, '2025-12-15 12:21:01', '2025-12-15 12:21:01');
INSERT INTO `audit_trail` VALUES ('29', '2', 'manager', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'manager\' accessed \'management\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/management/performance-kpis', NULL, NULL, '2025-12-15 12:21:03', '2025-12-15 12:21:03');
INSERT INTO `audit_trail` VALUES ('30', '2', 'manager', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'manager\' accessed \'management\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/management/executive-reports', NULL, NULL, '2025-12-15 12:21:05', '2025-12-15 12:21:05');
INSERT INTO `audit_trail` VALUES ('31', '2', 'manager', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'manager\' accessed \'management\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/management/inventory-overview', NULL, NULL, '2025-12-15 12:21:06', '2025-12-15 12:21:06');
INSERT INTO `audit_trail` VALUES ('32', '2', 'manager', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'manager\' accessed \'management\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/management/executive-reports', NULL, NULL, '2025-12-15 12:21:09', '2025-12-15 12:21:09');
INSERT INTO `audit_trail` VALUES ('33', '2', 'manager', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'manager\' accessed \'management\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/management/warehouse-analytics', NULL, NULL, '2025-12-15 12:21:10', '2025-12-15 12:21:10');
INSERT INTO `audit_trail` VALUES ('34', '2', 'manager', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'manager\' accessed \'management\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/management/executive-reports', NULL, NULL, '2025-12-15 12:21:12', '2025-12-15 12:21:12');
INSERT INTO `audit_trail` VALUES ('35', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/dashboard', NULL, NULL, '2025-12-15 16:31:49', '2025-12-15 16:31:49');
INSERT INTO `audit_trail` VALUES ('36', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/user-accounts', NULL, NULL, '2025-12-15 16:31:53', '2025-12-15 16:31:53');
INSERT INTO `audit_trail` VALUES ('37', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/roles-permissions', NULL, NULL, '2025-12-15 16:31:54', '2025-12-15 16:31:54');
INSERT INTO `audit_trail` VALUES ('38', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/active-sessions', NULL, NULL, '2025-12-15 16:31:55', '2025-12-15 16:31:55');
INSERT INTO `audit_trail` VALUES ('39', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/security-policies', NULL, NULL, '2025-12-15 16:31:56', '2025-12-15 16:31:56');
INSERT INTO `audit_trail` VALUES ('40', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/audit-logs', NULL, NULL, '2025-12-15 16:31:57', '2025-12-15 16:31:57');
INSERT INTO `audit_trail` VALUES ('41', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/system-health', NULL, NULL, '2025-12-15 16:31:59', '2025-12-15 16:31:59');
INSERT INTO `audit_trail` VALUES ('42', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/database-management', NULL, NULL, '2025-12-15 16:32:00', '2025-12-15 16:32:00');
INSERT INTO `audit_trail` VALUES ('43', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/backup-recovery', NULL, NULL, '2025-12-15 16:32:01', '2025-12-15 16:32:01');
INSERT INTO `audit_trail` VALUES ('44', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/settings', NULL, NULL, '2025-12-15 16:32:01', '2025-12-15 16:32:01');
INSERT INTO `audit_trail` VALUES ('45', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/dashboard', NULL, NULL, '2025-12-15 16:32:32', '2025-12-15 16:32:32');
INSERT INTO `audit_trail` VALUES ('46', '2', 'manager', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'manager\' accessed \'management\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/management/dashboard', NULL, NULL, '2025-12-15 16:33:11', '2025-12-15 16:33:11');
INSERT INTO `audit_trail` VALUES ('47', '2', 'manager', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'manager\' accessed \'management\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/management/financial-reports', NULL, NULL, '2025-12-15 16:33:15', '2025-12-15 16:33:15');
INSERT INTO `audit_trail` VALUES ('48', '2', 'manager', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'manager\' accessed \'management\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/management/financial-reports', NULL, NULL, '2025-12-15 16:33:37', '2025-12-15 16:33:37');
INSERT INTO `audit_trail` VALUES ('49', '2', 'manager', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'manager\' accessed \'management\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/management/inventory-overview', NULL, NULL, '2025-12-15 16:33:41', '2025-12-15 16:33:41');
INSERT INTO `audit_trail` VALUES ('50', '2', 'manager', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'manager\' accessed \'management\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/management/warehouse-analytics', NULL, NULL, '2025-12-15 16:33:43', '2025-12-15 16:33:43');
INSERT INTO `audit_trail` VALUES ('51', '2', 'manager', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'manager\' accessed \'management\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/management/forecasting', NULL, NULL, '2025-12-15 16:33:45', '2025-12-15 16:33:45');
INSERT INTO `audit_trail` VALUES ('52', '4', 'warehouse_staff', 'WarehouseSystem', 'warehouse-staff', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/warehouse-staff/dashboard', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/warehouse-staff/dashboard', NULL, '0', '2025-12-15 17:09:46', '2025-12-15 17:09:46');
INSERT INTO `audit_trail` VALUES ('53', '4', 'warehouse_staff', 'WarehouseSystem', 'warehouse-staff', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/warehouse-staff/dashboard', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/warehouse-staff/dashboard', NULL, '0', '2025-12-15 17:09:49', '2025-12-15 17:09:49');
INSERT INTO `audit_trail` VALUES ('54', '4', 'warehouse_staff', 'WarehouseSystem', 'warehouse-staff', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/warehouse-staff/dashboard', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/warehouse-staff/dashboard', NULL, '0', '2025-12-15 17:09:53', '2025-12-15 17:09:53');
INSERT INTO `audit_trail` VALUES ('55', '4', 'warehouse_staff', 'WarehouseSystem', 'warehouse-staff', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/warehouse-staff/dashboard', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/warehouse-staff/dashboard', NULL, '0', '2025-12-15 17:09:56', '2025-12-15 17:09:56');
INSERT INTO `audit_trail` VALUES ('56', '4', 'warehouse_staff', 'WarehouseSystem', 'warehouse-staff', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/warehouse-staff/dashboard', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/warehouse-staff/dashboard', NULL, '0', '2025-12-15 17:09:58', '2025-12-15 17:09:58');
INSERT INTO `audit_trail` VALUES ('57', '4', 'warehouse_staff', 'WarehouseSystem', 'warehouse-staff', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/warehouse-staff/dashboard', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/warehouse-staff/dashboard', NULL, '0', '2025-12-15 17:10:35', '2025-12-15 17:10:35');
INSERT INTO `audit_trail` VALUES ('58', '4', 'warehouse_staff', 'WarehouseSystem', 'warehouse-staff', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/warehouse-staff/dashboard', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/warehouse-staff/dashboard', NULL, '0', '2025-12-15 17:10:37', '2025-12-15 17:10:37');
INSERT INTO `audit_trail` VALUES ('59', '4', 'warehouse_staff', 'WarehouseSystem', 'warehouse-staff', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/warehouse-staff/dashboard', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/warehouse-staff/dashboard', NULL, '0', '2025-12-15 17:10:39', '2025-12-15 17:10:39');
INSERT INTO `audit_trail` VALUES ('60', '4', 'warehouse_staff', 'WarehouseSystem', 'warehouse-staff', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/warehouse-staff/dashboard', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/warehouse-staff/dashboard', NULL, '0', '2025-12-15 17:10:40', '2025-12-15 17:10:40');
INSERT INTO `audit_trail` VALUES ('61', '4', 'warehouse_staff', 'WarehouseSystem', 'warehouse-staff', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/warehouse-staff/dashboard', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/warehouse-staff/dashboard', NULL, '0', '2025-12-15 17:14:29', '2025-12-15 17:14:29');
INSERT INTO `audit_trail` VALUES ('62', '4', 'warehouse_staff', 'WarehouseSystem', 'warehouse-staff', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/warehouse-staff/dashboard', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/warehouse-staff/dashboard', NULL, '0', '2025-12-15 17:14:35', '2025-12-15 17:14:35');
INSERT INTO `audit_trail` VALUES ('63', '4', 'warehouse_staff', 'WarehouseSystem', 'warehouse-staff', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/warehouse-staff/dashboard', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/warehouse-staff/dashboard', NULL, '0', '2025-12-15 17:14:40', '2025-12-15 17:14:40');
INSERT INTO `audit_trail` VALUES ('64', '4', 'warehouse_staff', 'WarehouseSystem', 'warehouse-staff', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/warehouse-staff/dashboard', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/warehouse-staff/dashboard', NULL, '0', '2025-12-15 17:15:01', '2025-12-15 17:15:01');
INSERT INTO `audit_trail` VALUES ('65', '4', 'warehouse_staff', 'WarehouseSystem', 'warehouse-staff', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/warehouse-staff/dashboard', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/warehouse-staff/dashboard', NULL, '0', '2025-12-15 17:20:50', '2025-12-15 17:20:50');
INSERT INTO `audit_trail` VALUES ('66', '4', 'warehouse_staff', 'WarehouseSystem', 'warehouse-staff', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/warehouse-staff/dashboard', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/warehouse-staff/dashboard', NULL, '0', '2025-12-15 17:20:57', '2025-12-15 17:20:57');
INSERT INTO `audit_trail` VALUES ('67', '5', 'inventory_auditor', 'WarehouseSystem', 'inventory-auditor', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/inventory-auditor/dashboard', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/inventory-auditor/dashboard', NULL, '0', '2025-12-15 17:22:18', '2025-12-15 17:22:18');
INSERT INTO `audit_trail` VALUES ('68', '5', 'inventory_auditor', 'WarehouseSystem', 'inventory-auditor', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/inventory-auditor/discrepancy-review', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/inventory-auditor/discrepancy-review', NULL, '0', '2025-12-15 17:22:36', '2025-12-15 17:22:36');
INSERT INTO `audit_trail` VALUES ('69', '5', 'inventory_auditor', 'WarehouseSystem', 'inventory-auditor', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/inventory-auditor/reports', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/inventory-auditor/reports', NULL, '0', '2025-12-15 17:22:37', '2025-12-15 17:22:37');
INSERT INTO `audit_trail` VALUES ('70', '5', 'inventory_auditor', 'WarehouseSystem', 'inventory-auditor', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/inventory-auditor/dashboard', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/inventory-auditor/dashboard', NULL, '0', '2025-12-15 17:22:42', '2025-12-15 17:22:42');
INSERT INTO `audit_trail` VALUES ('71', '5', 'inventory_auditor', 'WarehouseSystem', 'inventory-auditor', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/inventory-auditor/count-sessions', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/inventory-auditor/count-sessions', NULL, '0', '2025-12-15 17:32:14', '2025-12-15 17:32:14');
INSERT INTO `audit_trail` VALUES ('72', '5', 'inventory_auditor', 'WarehouseSystem', 'inventory-auditor', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/inventory-auditor/count-sessions', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/inventory-auditor/count-sessions', NULL, '0', '2025-12-15 17:32:18', '2025-12-15 17:32:18');
INSERT INTO `audit_trail` VALUES ('73', '5', 'inventory_auditor', 'WarehouseSystem', 'inventory-auditor', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/inventory-auditor/discrepancy-review', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/inventory-auditor/discrepancy-review', NULL, '0', '2025-12-15 17:32:19', '2025-12-15 17:32:19');
INSERT INTO `audit_trail` VALUES ('74', '5', 'inventory_auditor', 'WarehouseSystem', 'inventory-auditor', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/inventory-auditor/reports', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/inventory-auditor/reports', NULL, '0', '2025-12-15 17:32:20', '2025-12-15 17:32:20');
INSERT INTO `audit_trail` VALUES ('75', '5', 'inventory_auditor', 'WarehouseSystem', 'inventory-auditor', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/inventory-auditor/dashboard', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/inventory-auditor/dashboard', NULL, '0', '2025-12-15 17:32:22', '2025-12-15 17:32:22');
INSERT INTO `audit_trail` VALUES ('76', '6', 'procurement_officer', 'WarehouseSystem', 'procurement', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/procurement/dashboard', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/procurement/dashboard', NULL, '0', '2025-12-15 17:33:43', '2025-12-15 17:33:43');
INSERT INTO `audit_trail` VALUES ('77', '6', 'procurement_officer', 'WarehouseSystem', 'procurement', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/procurement/dashboard', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/procurement/dashboard', NULL, '0', '2025-12-15 17:34:33', '2025-12-15 17:34:33');
INSERT INTO `audit_trail` VALUES ('78', '6', 'procurement_officer', 'WarehouseSystem', 'procurement', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/procurement/reports', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/procurement/reports', NULL, '0', '2025-12-15 17:34:50', '2025-12-15 17:34:50');
INSERT INTO `audit_trail` VALUES ('79', '7', 'accounts_payable', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'accounts_payable\' accessed \'accounts-payable\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/accounts-payable/dashboard', NULL, NULL, '2025-12-15 17:42:58', '2025-12-15 17:42:58');
INSERT INTO `audit_trail` VALUES ('80', '7', 'accounts_payable', 'WarehouseSystem', 'accounts-payable', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/accounts-payable/dashboard', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/accounts-payable/dashboard', NULL, '0', '2025-12-15 17:42:58', '2025-12-15 17:42:58');
INSERT INTO `audit_trail` VALUES ('81', '7', 'accounts_payable', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'accounts_payable\' accessed \'accounts-payable\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/accounts-payable/pending-invoices', NULL, NULL, '2025-12-15 17:43:00', '2025-12-15 17:43:00');
INSERT INTO `audit_trail` VALUES ('82', '7', 'accounts_payable', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'accounts_payable\' accessed \'accounts-payable\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/accounts-payable/approved-invoices', NULL, NULL, '2025-12-15 17:43:01', '2025-12-15 17:43:01');
INSERT INTO `audit_trail` VALUES ('83', '7', 'accounts_payable', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'accounts_payable\' accessed \'accounts-payable\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/accounts-payable/payment-processing', NULL, NULL, '2025-12-15 17:43:03', '2025-12-15 17:43:03');
INSERT INTO `audit_trail` VALUES ('84', '7', 'accounts_payable', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'accounts_payable\' accessed \'accounts-payable\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/accounts-payable/vendor-management', NULL, NULL, '2025-12-15 17:43:04', '2025-12-15 17:43:04');
INSERT INTO `audit_trail` VALUES ('85', '7', 'accounts_payable', 'WarehouseSystem', 'accounts-payable', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/accounts-payable/vendor-management', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/accounts-payable/vendor-management', NULL, '0', '2025-12-15 17:43:04', '2025-12-15 17:43:04');
INSERT INTO `audit_trail` VALUES ('86', '7', 'accounts_payable', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'accounts_payable\' accessed \'accounts-payable\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/accounts-payable/payment-reports', NULL, NULL, '2025-12-15 17:43:06', '2025-12-15 17:43:06');
INSERT INTO `audit_trail` VALUES ('87', '7', 'accounts_payable', 'WarehouseSystem', 'accounts-payable', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/accounts-payable/payment-reports', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/accounts-payable/payment-reports', NULL, '0', '2025-12-15 17:43:06', '2025-12-15 17:43:06');
INSERT INTO `audit_trail` VALUES ('88', '7', 'accounts_payable', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'accounts_payable\' accessed \'accounts-payable\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/accounts-payable/overdue-payments', NULL, NULL, '2025-12-15 17:43:07', '2025-12-15 17:43:07');
INSERT INTO `audit_trail` VALUES ('89', '7', 'accounts_payable', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'accounts_payable\' accessed \'accounts-payable\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/accounts-payable/audit-trail', NULL, NULL, '2025-12-15 17:43:09', '2025-12-15 17:43:09');
INSERT INTO `audit_trail` VALUES ('90', '7', 'accounts_payable', 'WarehouseSystem', 'accounts-payable', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/accounts-payable/audit-trail', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/accounts-payable/audit-trail', NULL, '0', '2025-12-15 17:43:09', '2025-12-15 17:43:09');
INSERT INTO `audit_trail` VALUES ('91', '8', 'accounts_receivable', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'accounts_receivable\' accessed \'accounts-receivable\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/accounts-receivable/dashboard', NULL, NULL, '2025-12-15 17:43:31', '2025-12-15 17:43:31');
INSERT INTO `audit_trail` VALUES ('92', '8', 'accounts_receivable', 'WarehouseSystem', 'accounts-receivable', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/accounts-receivable/dashboard', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/accounts-receivable/dashboard', NULL, '0', '2025-12-15 17:43:31', '2025-12-15 17:43:31');
INSERT INTO `audit_trail` VALUES ('93', '8', 'accounts_receivable', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'accounts_receivable\' accessed \'accounts-receivable\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/accounts-receivable/manage-invoices', NULL, NULL, '2025-12-15 17:43:34', '2025-12-15 17:43:34');
INSERT INTO `audit_trail` VALUES ('94', '8', 'accounts_receivable', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'accounts_receivable\' accessed \'accounts-receivable\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/accounts-receivable/record-payments', NULL, NULL, '2025-12-15 17:43:35', '2025-12-15 17:43:35');
INSERT INTO `audit_trail` VALUES ('95', '8', 'accounts_receivable', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'accounts_receivable\' accessed \'accounts-receivable\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/accounts-receivable/client-management', NULL, NULL, '2025-12-15 17:43:36', '2025-12-15 17:43:36');
INSERT INTO `audit_trail` VALUES ('96', '8', 'accounts_receivable', 'WarehouseSystem', 'accounts-receivable', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/accounts-receivable/client-management', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/accounts-receivable/client-management', NULL, '0', '2025-12-15 17:43:36', '2025-12-15 17:43:36');
INSERT INTO `audit_trail` VALUES ('97', '8', 'accounts_receivable', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'accounts_receivable\' accessed \'accounts-receivable\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/accounts-receivable/overdue-followups', NULL, NULL, '2025-12-15 17:43:37', '2025-12-15 17:43:37');
INSERT INTO `audit_trail` VALUES ('98', '8', 'accounts_receivable', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'accounts_receivable\' accessed \'accounts-receivable\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/accounts-receivable/reports-analytics', NULL, NULL, '2025-12-15 17:43:38', '2025-12-15 17:43:38');
INSERT INTO `audit_trail` VALUES ('99', '8', 'accounts_receivable', 'WarehouseSystem', 'accounts-receivable', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/accounts-receivable/reports-analytics', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/accounts-receivable/reports-analytics', NULL, '0', '2025-12-15 17:43:38', '2025-12-15 17:43:38');
INSERT INTO `audit_trail` VALUES ('100', '8', 'accounts_receivable', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'accounts_receivable\' accessed \'accounts-receivable\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/accounts-receivable/aging-report', NULL, NULL, '2025-12-15 17:43:39', '2025-12-15 17:43:39');
INSERT INTO `audit_trail` VALUES ('101', '8', 'accounts_receivable', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'accounts_receivable\' accessed \'accounts-receivable\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/accounts-receivable/settings', NULL, NULL, '2025-12-15 17:43:40', '2025-12-15 17:43:40');
INSERT INTO `audit_trail` VALUES ('102', '8', 'accounts_receivable', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'accounts_receivable\' accessed \'accounts-receivable\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/accounts-receivable/dashboard', NULL, NULL, '2025-12-15 17:43:41', '2025-12-15 17:43:41');
INSERT INTO `audit_trail` VALUES ('103', '8', 'accounts_receivable', 'WarehouseSystem', 'accounts-receivable', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/accounts-receivable/dashboard', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/accounts-receivable/dashboard', NULL, '0', '2025-12-15 17:43:41', '2025-12-15 17:43:41');
INSERT INTO `audit_trail` VALUES ('104', NULL, 'system', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'Attempted to access protected resource without authentication', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/warehouse-manager/dashboard', NULL, NULL, '2025-12-16 01:38:49', '2025-12-16 01:38:49');
INSERT INTO `audit_trail` VALUES ('105', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/dashboard', NULL, NULL, '2025-12-16 01:39:44', '2025-12-16 01:39:44');
INSERT INTO `audit_trail` VALUES ('106', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/dashboard', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/dashboard', NULL, '0', '2025-12-16 01:39:44', '2025-12-16 01:39:44');
INSERT INTO `audit_trail` VALUES ('107', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/roles-permissions', NULL, NULL, '2025-12-16 01:39:47', '2025-12-16 01:39:47');
INSERT INTO `audit_trail` VALUES ('108', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/roles-permissions', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/roles-permissions', NULL, '0', '2025-12-16 01:39:47', '2025-12-16 01:39:47');
INSERT INTO `audit_trail` VALUES ('109', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/active-sessions', NULL, NULL, '2025-12-16 01:39:50', '2025-12-16 01:39:50');
INSERT INTO `audit_trail` VALUES ('110', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/active-sessions', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/active-sessions', NULL, '0', '2025-12-16 01:39:50', '2025-12-16 01:39:50');
INSERT INTO `audit_trail` VALUES ('111', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/security-policies', NULL, NULL, '2025-12-16 01:39:51', '2025-12-16 01:39:51');
INSERT INTO `audit_trail` VALUES ('112', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/security-policies', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/security-policies', NULL, '0', '2025-12-16 01:39:51', '2025-12-16 01:39:51');
INSERT INTO `audit_trail` VALUES ('113', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/audit-logs', NULL, NULL, '2025-12-16 01:39:53', '2025-12-16 01:39:53');
INSERT INTO `audit_trail` VALUES ('114', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/audit-logs', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/audit-logs', NULL, '0', '2025-12-16 01:39:53', '2025-12-16 01:39:53');
INSERT INTO `audit_trail` VALUES ('115', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/system-health', NULL, NULL, '2025-12-16 01:39:55', '2025-12-16 01:39:55');
INSERT INTO `audit_trail` VALUES ('116', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/system-health', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/system-health', NULL, '0', '2025-12-16 01:39:55', '2025-12-16 01:39:55');
INSERT INTO `audit_trail` VALUES ('117', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/database-management', NULL, NULL, '2025-12-16 01:39:56', '2025-12-16 01:39:56');
INSERT INTO `audit_trail` VALUES ('118', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/database-management', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/database-management', NULL, '0', '2025-12-16 01:39:56', '2025-12-16 01:39:56');
INSERT INTO `audit_trail` VALUES ('119', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/backup-recovery', NULL, NULL, '2025-12-16 01:40:07', '2025-12-16 01:40:07');
INSERT INTO `audit_trail` VALUES ('120', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/backup-recovery', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/backup-recovery', NULL, '0', '2025-12-16 01:40:07', '2025-12-16 01:40:07');
INSERT INTO `audit_trail` VALUES ('121', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/settings', NULL, NULL, '2025-12-16 01:40:09', '2025-12-16 01:40:09');
INSERT INTO `audit_trail` VALUES ('122', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/settings', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/settings', NULL, '0', '2025-12-16 01:40:09', '2025-12-16 01:40:09');
INSERT INTO `audit_trail` VALUES ('123', '3', 'warehouse_manager', 'WarehouseSystem', 'warehouse-manager', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/warehouse-manager/dashboard', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/warehouse-manager/dashboard', NULL, '0', '2025-12-16 01:40:31', '2025-12-16 01:40:31');
INSERT INTO `audit_trail` VALUES ('124', '3', 'warehouse_manager', 'WarehouseSystem', 'warehouse-manager', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/warehouse-manager/reports', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/warehouse-manager/reports', NULL, '0', '2025-12-16 01:40:35', '2025-12-16 01:40:35');
INSERT INTO `audit_trail` VALUES ('125', '3', 'warehouse_manager', 'WarehouseSystem', 'warehouse-manager', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/warehouse-manager/reports', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/warehouse-manager/reports', NULL, '0', '2025-12-16 01:42:03', '2025-12-16 01:42:03');
INSERT INTO `audit_trail` VALUES ('126', '3', 'warehouse_manager', 'WarehouseSystem', 'warehouse-manager', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/warehouse-manager/dashboard', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/warehouse-manager/dashboard', NULL, '0', '2025-12-16 01:43:15', '2025-12-16 01:43:15');
INSERT INTO `audit_trail` VALUES ('127', '3', 'warehouse_manager', 'WarehouseSystem', 'warehouse-manager', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/warehouse-manager/dashboard', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/warehouse-manager/dashboard', NULL, '0', '2025-12-16 01:46:50', '2025-12-16 01:46:50');
INSERT INTO `audit_trail` VALUES ('128', '3', 'warehouse_manager', 'WarehouseSystem', 'warehouse-manager', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/warehouse-manager/reports', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/warehouse-manager/reports', NULL, '0', '2025-12-16 01:51:39', '2025-12-16 01:51:39');
INSERT INTO `audit_trail` VALUES ('129', '3', 'warehouse_manager', 'WarehouseSystem', 'warehouse-manager', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/warehouse-manager/dashboard', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/warehouse-manager/dashboard', NULL, '0', '2025-12-16 01:51:48', '2025-12-16 01:51:48');
INSERT INTO `audit_trail` VALUES ('130', NULL, 'system', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'Attempted to access protected resource without authentication', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'GET', 'http://localhost/WarehouseSystem/accounts-receivable/dashboard', NULL, NULL, '2025-12-16 01:53:54', '2025-12-16 01:53:54');
INSERT INTO `audit_trail` VALUES ('131', '3', 'warehouse_manager', 'WarehouseSystem', 'warehouse-manager', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/warehouse-manager/reports', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/warehouse-manager/reports', NULL, '0', '2025-12-16 01:56:48', '2025-12-16 01:56:48');
INSERT INTO `audit_trail` VALUES ('132', '3', 'warehouse_manager', 'WarehouseSystem', 'warehouse-manager', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/warehouse-manager/reports', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/warehouse-manager/reports', NULL, '0', '2025-12-16 01:58:16', '2025-12-16 01:58:16');
INSERT INTO `audit_trail` VALUES ('133', '3', 'warehouse_manager', 'WarehouseSystem', 'warehouse-manager', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/warehouse-manager/dashboard', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/warehouse-manager/dashboard', NULL, '0', '2025-12-16 02:07:02', '2025-12-16 02:07:02');
INSERT INTO `audit_trail` VALUES ('134', '3', 'warehouse_manager', 'WarehouseSystem', 'warehouse-manager', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/warehouse-manager/reports', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/warehouse-manager/reports', NULL, '0', '2025-12-16 02:07:19', '2025-12-16 02:07:19');
INSERT INTO `audit_trail` VALUES ('135', '3', 'warehouse_manager', 'WarehouseSystem', 'warehouse-manager', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/warehouse-manager/dashboard', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/warehouse-manager/dashboard', NULL, '0', '2025-12-16 02:07:27', '2025-12-16 02:07:27');
INSERT INTO `audit_trail` VALUES ('136', '3', 'warehouse_manager', 'WarehouseSystem', 'warehouse-manager', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/warehouse-manager/reports', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/warehouse-manager/reports', NULL, '0', '2025-12-16 02:10:11', '2025-12-16 02:10:11');
INSERT INTO `audit_trail` VALUES ('137', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/dashboard', NULL, NULL, '2025-12-16 03:48:25', '2025-12-16 03:48:25');
INSERT INTO `audit_trail` VALUES ('138', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/dashboard', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/dashboard', NULL, '0', '2025-12-16 03:48:25', '2025-12-16 03:48:25');
INSERT INTO `audit_trail` VALUES ('139', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/backup-recovery', NULL, NULL, '2025-12-16 03:48:31', '2025-12-16 03:48:31');
INSERT INTO `audit_trail` VALUES ('140', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/backup-recovery', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/backup-recovery', NULL, '0', '2025-12-16 03:48:31', '2025-12-16 03:48:31');
INSERT INTO `audit_trail` VALUES ('141', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/database-management', NULL, NULL, '2025-12-16 03:48:42', '2025-12-16 03:48:42');
INSERT INTO `audit_trail` VALUES ('142', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/database-management', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/database-management', NULL, '0', '2025-12-16 03:48:42', '2025-12-16 03:48:42');
INSERT INTO `audit_trail` VALUES ('143', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/system-health', NULL, NULL, '2025-12-16 03:48:52', '2025-12-16 03:48:52');
INSERT INTO `audit_trail` VALUES ('144', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/system-health', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/system-health', NULL, '0', '2025-12-16 03:48:52', '2025-12-16 03:48:52');
INSERT INTO `audit_trail` VALUES ('145', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/database-management', NULL, NULL, '2025-12-16 03:49:07', '2025-12-16 03:49:07');
INSERT INTO `audit_trail` VALUES ('146', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/database-management', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/database-management', NULL, '0', '2025-12-16 03:49:07', '2025-12-16 03:49:07');
INSERT INTO `audit_trail` VALUES ('147', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/audit-logs', NULL, NULL, '2025-12-16 03:49:16', '2025-12-16 03:49:16');
INSERT INTO `audit_trail` VALUES ('148', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/audit-logs', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/audit-logs', NULL, '0', '2025-12-16 03:49:16', '2025-12-16 03:49:16');
INSERT INTO `audit_trail` VALUES ('149', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/security-policies', NULL, NULL, '2025-12-16 03:49:29', '2025-12-16 03:49:29');
INSERT INTO `audit_trail` VALUES ('150', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/security-policies', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/security-policies', NULL, '0', '2025-12-16 03:49:29', '2025-12-16 03:49:29');
INSERT INTO `audit_trail` VALUES ('151', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/active-sessions', NULL, NULL, '2025-12-16 03:49:36', '2025-12-16 03:49:36');
INSERT INTO `audit_trail` VALUES ('152', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/active-sessions', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/active-sessions', NULL, '0', '2025-12-16 03:49:36', '2025-12-16 03:49:36');
INSERT INTO `audit_trail` VALUES ('153', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/roles-permissions', NULL, NULL, '2025-12-16 03:49:44', '2025-12-16 03:49:44');
INSERT INTO `audit_trail` VALUES ('154', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/roles-permissions', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/roles-permissions', NULL, '0', '2025-12-16 03:49:44', '2025-12-16 03:49:44');
INSERT INTO `audit_trail` VALUES ('155', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/dashboard', NULL, NULL, '2025-12-16 03:51:09', '2025-12-16 03:51:09');
INSERT INTO `audit_trail` VALUES ('156', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/dashboard', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/dashboard', NULL, '0', '2025-12-16 03:51:09', '2025-12-16 03:51:09');
INSERT INTO `audit_trail` VALUES ('157', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/user-accounts', NULL, NULL, '2025-12-16 03:51:10', '2025-12-16 03:51:10');
INSERT INTO `audit_trail` VALUES ('158', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/user-accounts', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/user-accounts', NULL, '0', '2025-12-16 03:51:10', '2025-12-16 03:51:10');
INSERT INTO `audit_trail` VALUES ('159', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/backup-recovery', NULL, NULL, '2025-12-16 03:51:39', '2025-12-16 03:51:39');
INSERT INTO `audit_trail` VALUES ('160', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/backup-recovery', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/backup-recovery', NULL, '0', '2025-12-16 03:51:39', '2025-12-16 03:51:39');
INSERT INTO `audit_trail` VALUES ('161', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/settings', NULL, NULL, '2025-12-16 03:51:43', '2025-12-16 03:51:43');
INSERT INTO `audit_trail` VALUES ('162', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/settings', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/settings', NULL, '0', '2025-12-16 03:51:43', '2025-12-16 03:51:43');
INSERT INTO `audit_trail` VALUES ('163', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/dashboard', NULL, NULL, '2025-12-16 03:51:46', '2025-12-16 03:51:46');
INSERT INTO `audit_trail` VALUES ('164', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/dashboard', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/dashboard', NULL, '0', '2025-12-16 03:51:46', '2025-12-16 03:51:46');
INSERT INTO `audit_trail` VALUES ('165', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/backup-recovery', NULL, NULL, '2025-12-16 03:52:01', '2025-12-16 03:52:01');
INSERT INTO `audit_trail` VALUES ('166', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/backup-recovery', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/backup-recovery', NULL, '0', '2025-12-16 03:52:01', '2025-12-16 03:52:01');
INSERT INTO `audit_trail` VALUES ('167', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/system-health', NULL, NULL, '2025-12-16 03:53:31', '2025-12-16 03:53:31');
INSERT INTO `audit_trail` VALUES ('168', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/system-health', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/system-health', NULL, '0', '2025-12-16 03:53:31', '2025-12-16 03:53:31');
INSERT INTO `audit_trail` VALUES ('169', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/audit-logs', NULL, NULL, '2025-12-16 03:53:44', '2025-12-16 03:53:44');
INSERT INTO `audit_trail` VALUES ('170', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/audit-logs', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/audit-logs', NULL, '0', '2025-12-16 03:53:44', '2025-12-16 03:53:44');
INSERT INTO `audit_trail` VALUES ('171', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/system-health', NULL, NULL, '2025-12-16 03:53:46', '2025-12-16 03:53:46');
INSERT INTO `audit_trail` VALUES ('172', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/system-health', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/system-health', NULL, '0', '2025-12-16 03:53:46', '2025-12-16 03:53:46');
INSERT INTO `audit_trail` VALUES ('173', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/active-sessions', NULL, NULL, '2025-12-16 03:54:17', '2025-12-16 03:54:17');
INSERT INTO `audit_trail` VALUES ('174', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/active-sessions', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/active-sessions', NULL, '0', '2025-12-16 03:54:18', '2025-12-16 03:54:18');
INSERT INTO `audit_trail` VALUES ('175', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/roles-permissions', NULL, NULL, '2025-12-16 03:54:20', '2025-12-16 03:54:20');
INSERT INTO `audit_trail` VALUES ('176', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/roles-permissions', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/roles-permissions', NULL, '0', '2025-12-16 03:54:20', '2025-12-16 03:54:20');
INSERT INTO `audit_trail` VALUES ('177', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/security-policies', NULL, NULL, '2025-12-16 03:54:21', '2025-12-16 03:54:21');
INSERT INTO `audit_trail` VALUES ('178', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/security-policies', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/security-policies', NULL, '0', '2025-12-16 03:54:21', '2025-12-16 03:54:21');
INSERT INTO `audit_trail` VALUES ('179', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/audit-logs', NULL, NULL, '2025-12-16 03:54:22', '2025-12-16 03:54:22');
INSERT INTO `audit_trail` VALUES ('180', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/audit-logs', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/audit-logs', NULL, '0', '2025-12-16 03:54:22', '2025-12-16 03:54:22');
INSERT INTO `audit_trail` VALUES ('181', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/database-management', NULL, NULL, '2025-12-16 03:54:24', '2025-12-16 03:54:24');
INSERT INTO `audit_trail` VALUES ('182', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/database-management', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/database-management', NULL, '0', '2025-12-16 03:54:24', '2025-12-16 03:54:24');
INSERT INTO `audit_trail` VALUES ('183', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/database-management', NULL, NULL, '2025-12-16 03:54:27', '2025-12-16 03:54:27');
INSERT INTO `audit_trail` VALUES ('184', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/database-management', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/database-management', NULL, '0', '2025-12-16 03:54:27', '2025-12-16 03:54:27');
INSERT INTO `audit_trail` VALUES ('185', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/database-management', NULL, NULL, '2025-12-16 03:54:35', '2025-12-16 03:54:35');
INSERT INTO `audit_trail` VALUES ('186', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/database-management', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/database-management', NULL, '0', '2025-12-16 03:54:35', '2025-12-16 03:54:35');
INSERT INTO `audit_trail` VALUES ('187', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/database-management', NULL, NULL, '2025-12-16 03:55:05', '2025-12-16 03:55:05');
INSERT INTO `audit_trail` VALUES ('188', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/database-management', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/database-management', NULL, '0', '2025-12-16 03:55:06', '2025-12-16 03:55:06');
INSERT INTO `audit_trail` VALUES ('189', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/backup-recovery', NULL, NULL, '2025-12-16 03:55:35', '2025-12-16 03:55:35');
INSERT INTO `audit_trail` VALUES ('190', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/backup-recovery', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/backup-recovery', NULL, '0', '2025-12-16 03:55:35', '2025-12-16 03:55:35');
INSERT INTO `audit_trail` VALUES ('191', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/active-sessions', NULL, NULL, '2025-12-16 03:55:55', '2025-12-16 03:55:55');
INSERT INTO `audit_trail` VALUES ('192', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/active-sessions', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/active-sessions', NULL, '0', '2025-12-16 03:55:55', '2025-12-16 03:55:55');
INSERT INTO `audit_trail` VALUES ('193', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/active-sessions', NULL, NULL, '2025-12-16 03:56:07', '2025-12-16 03:56:07');
INSERT INTO `audit_trail` VALUES ('194', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/active-sessions', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/active-sessions', NULL, '0', '2025-12-16 03:56:07', '2025-12-16 03:56:07');
INSERT INTO `audit_trail` VALUES ('195', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/active-sessions', NULL, NULL, '2025-12-16 03:56:31', '2025-12-16 03:56:31');
INSERT INTO `audit_trail` VALUES ('196', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/active-sessions', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/active-sessions', NULL, '0', '2025-12-16 03:56:31', '2025-12-16 03:56:31');
INSERT INTO `audit_trail` VALUES ('197', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/security-policies', NULL, NULL, '2025-12-16 03:57:14', '2025-12-16 03:57:14');
INSERT INTO `audit_trail` VALUES ('198', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/security-policies', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/security-policies', NULL, '0', '2025-12-16 03:57:14', '2025-12-16 03:57:14');
INSERT INTO `audit_trail` VALUES ('199', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/audit-logs', NULL, NULL, '2025-12-16 03:57:16', '2025-12-16 03:57:16');
INSERT INTO `audit_trail` VALUES ('200', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/audit-logs', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/audit-logs', NULL, '0', '2025-12-16 03:57:16', '2025-12-16 03:57:16');
INSERT INTO `audit_trail` VALUES ('201', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/system-health', NULL, NULL, '2025-12-16 03:57:18', '2025-12-16 03:57:18');
INSERT INTO `audit_trail` VALUES ('202', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/system-health', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/system-health', NULL, '0', '2025-12-16 03:57:18', '2025-12-16 03:57:18');
INSERT INTO `audit_trail` VALUES ('203', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/database-management', NULL, NULL, '2025-12-16 03:57:22', '2025-12-16 03:57:22');
INSERT INTO `audit_trail` VALUES ('204', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/database-management', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/database-management', NULL, '0', '2025-12-16 03:57:22', '2025-12-16 03:57:22');
INSERT INTO `audit_trail` VALUES ('205', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/backup-recovery', NULL, NULL, '2025-12-16 03:58:39', '2025-12-16 03:58:39');
INSERT INTO `audit_trail` VALUES ('206', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/backup-recovery', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/backup-recovery', NULL, '0', '2025-12-16 03:58:39', '2025-12-16 03:58:39');
INSERT INTO `audit_trail` VALUES ('207', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/security-policies', NULL, NULL, '2025-12-16 03:58:41', '2025-12-16 03:58:41');
INSERT INTO `audit_trail` VALUES ('208', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/security-policies', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/security-policies', NULL, '0', '2025-12-16 03:58:41', '2025-12-16 03:58:41');
INSERT INTO `audit_trail` VALUES ('209', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/active-sessions', NULL, NULL, '2025-12-16 03:58:42', '2025-12-16 03:58:42');
INSERT INTO `audit_trail` VALUES ('210', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/active-sessions', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/active-sessions', NULL, '0', '2025-12-16 03:58:42', '2025-12-16 03:58:42');
INSERT INTO `audit_trail` VALUES ('211', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/audit-logs', NULL, NULL, '2025-12-16 03:58:44', '2025-12-16 03:58:44');
INSERT INTO `audit_trail` VALUES ('212', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/audit-logs', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/audit-logs', NULL, '0', '2025-12-16 03:58:45', '2025-12-16 03:58:45');
INSERT INTO `audit_trail` VALUES ('213', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/dashboard', NULL, NULL, '2025-12-16 04:00:26', '2025-12-16 04:00:26');
INSERT INTO `audit_trail` VALUES ('214', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/dashboard', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/dashboard', NULL, '0', '2025-12-16 04:00:26', '2025-12-16 04:00:26');
INSERT INTO `audit_trail` VALUES ('215', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/dashboard', NULL, NULL, '2025-12-16 04:00:34', '2025-12-16 04:00:34');
INSERT INTO `audit_trail` VALUES ('216', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/dashboard', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/dashboard', NULL, '0', '2025-12-16 04:00:34', '2025-12-16 04:00:34');
INSERT INTO `audit_trail` VALUES ('217', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/roles-permissions', NULL, NULL, '2025-12-16 04:00:40', '2025-12-16 04:00:40');
INSERT INTO `audit_trail` VALUES ('218', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/roles-permissions', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/roles-permissions', NULL, '0', '2025-12-16 04:00:40', '2025-12-16 04:00:40');
INSERT INTO `audit_trail` VALUES ('219', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/active-sessions', NULL, NULL, '2025-12-16 04:01:29', '2025-12-16 04:01:29');
INSERT INTO `audit_trail` VALUES ('220', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/active-sessions', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/active-sessions', NULL, '0', '2025-12-16 04:01:29', '2025-12-16 04:01:29');
INSERT INTO `audit_trail` VALUES ('221', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/security-policies', NULL, NULL, '2025-12-16 04:01:50', '2025-12-16 04:01:50');
INSERT INTO `audit_trail` VALUES ('222', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/security-policies', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/security-policies', NULL, '0', '2025-12-16 04:01:50', '2025-12-16 04:01:50');
INSERT INTO `audit_trail` VALUES ('223', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/audit-logs', NULL, NULL, '2025-12-16 04:02:28', '2025-12-16 04:02:28');
INSERT INTO `audit_trail` VALUES ('224', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/audit-logs', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/audit-logs', NULL, '0', '2025-12-16 04:02:28', '2025-12-16 04:02:28');
INSERT INTO `audit_trail` VALUES ('225', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/system-health', NULL, NULL, '2025-12-16 04:03:14', '2025-12-16 04:03:14');
INSERT INTO `audit_trail` VALUES ('226', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/system-health', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/system-health', NULL, '0', '2025-12-16 04:03:14', '2025-12-16 04:03:14');
INSERT INTO `audit_trail` VALUES ('227', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/database-management', NULL, NULL, '2025-12-16 04:03:42', '2025-12-16 04:03:42');
INSERT INTO `audit_trail` VALUES ('228', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/database-management', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/database-management', NULL, '0', '2025-12-16 04:03:42', '2025-12-16 04:03:42');
INSERT INTO `audit_trail` VALUES ('229', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/backup-recovery', NULL, NULL, '2025-12-16 04:04:01', '2025-12-16 04:04:01');
INSERT INTO `audit_trail` VALUES ('230', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/backup-recovery', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/backup-recovery', NULL, '0', '2025-12-16 04:04:01', '2025-12-16 04:04:01');
INSERT INTO `audit_trail` VALUES ('231', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/roles-permissions', NULL, NULL, '2025-12-16 04:06:21', '2025-12-16 04:06:21');
INSERT INTO `audit_trail` VALUES ('232', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/roles-permissions', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/roles-permissions', NULL, '0', '2025-12-16 04:06:21', '2025-12-16 04:06:21');
INSERT INTO `audit_trail` VALUES ('233', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/active-sessions', NULL, NULL, '2025-12-16 04:07:00', '2025-12-16 04:07:00');
INSERT INTO `audit_trail` VALUES ('234', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/active-sessions', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/active-sessions', NULL, '0', '2025-12-16 04:07:00', '2025-12-16 04:07:00');
INSERT INTO `audit_trail` VALUES ('235', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/backup-recovery', NULL, NULL, '2025-12-16 04:07:21', '2025-12-16 04:07:21');
INSERT INTO `audit_trail` VALUES ('236', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/backup-recovery', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/backup-recovery', NULL, '0', '2025-12-16 04:07:21', '2025-12-16 04:07:21');
INSERT INTO `audit_trail` VALUES ('237', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/database-management', NULL, NULL, '2025-12-16 04:07:22', '2025-12-16 04:07:22');
INSERT INTO `audit_trail` VALUES ('238', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/database-management', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/database-management', NULL, '0', '2025-12-16 04:07:22', '2025-12-16 04:07:22');
INSERT INTO `audit_trail` VALUES ('239', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/roles-permissions', NULL, NULL, '2025-12-16 04:07:32', '2025-12-16 04:07:32');
INSERT INTO `audit_trail` VALUES ('240', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/roles-permissions', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/roles-permissions', NULL, '0', '2025-12-16 04:07:32', '2025-12-16 04:07:32');
INSERT INTO `audit_trail` VALUES ('241', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/backup-recovery', NULL, NULL, '2025-12-16 04:09:54', '2025-12-16 04:09:54');
INSERT INTO `audit_trail` VALUES ('242', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/backup-recovery', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/backup-recovery', NULL, '0', '2025-12-16 04:09:54', '2025-12-16 04:09:54');
INSERT INTO `audit_trail` VALUES ('243', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/backup-recovery', NULL, NULL, '2025-12-16 04:09:57', '2025-12-16 04:09:57');
INSERT INTO `audit_trail` VALUES ('244', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/backup-recovery', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/backup-recovery', NULL, '0', '2025-12-16 04:09:57', '2025-12-16 04:09:57');
INSERT INTO `audit_trail` VALUES ('245', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/database-management', NULL, NULL, '2025-12-16 04:09:58', '2025-12-16 04:09:58');
INSERT INTO `audit_trail` VALUES ('246', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/database-management', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/database-management', NULL, '0', '2025-12-16 04:09:58', '2025-12-16 04:09:58');
INSERT INTO `audit_trail` VALUES ('247', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/roles-permissions', NULL, NULL, '2025-12-16 04:10:05', '2025-12-16 04:10:05');
INSERT INTO `audit_trail` VALUES ('248', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/roles-permissions', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/roles-permissions', NULL, '0', '2025-12-16 04:10:05', '2025-12-16 04:10:05');
INSERT INTO `audit_trail` VALUES ('249', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/get-users-by-role', NULL, NULL, '2025-12-16 04:10:07', '2025-12-16 04:10:07');
INSERT INTO `audit_trail` VALUES ('250', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/get-users-by-role', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/get-users-by-role', NULL, '0', '2025-12-16 04:10:07', '2025-12-16 04:10:07');
INSERT INTO `audit_trail` VALUES ('251', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/get-users-by-role', NULL, NULL, '2025-12-16 04:10:11', '2025-12-16 04:10:11');
INSERT INTO `audit_trail` VALUES ('252', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/get-users-by-role', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/get-users-by-role', NULL, '0', '2025-12-16 04:10:11', '2025-12-16 04:10:11');
INSERT INTO `audit_trail` VALUES ('253', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/get-users-by-role', NULL, NULL, '2025-12-16 04:10:14', '2025-12-16 04:10:14');
INSERT INTO `audit_trail` VALUES ('254', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/get-users-by-role', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/get-users-by-role', NULL, '0', '2025-12-16 04:10:14', '2025-12-16 04:10:14');
INSERT INTO `audit_trail` VALUES ('255', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/user-accounts', NULL, NULL, '2025-12-16 04:10:55', '2025-12-16 04:10:55');
INSERT INTO `audit_trail` VALUES ('256', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/user-accounts', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/user-accounts', NULL, '0', '2025-12-16 04:10:56', '2025-12-16 04:10:56');
INSERT INTO `audit_trail` VALUES ('257', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'POST', 'http://localhost/WarehouseSystem/admin/toggle-user-status/7', NULL, NULL, '2025-12-16 04:11:12', '2025-12-16 04:11:12');
INSERT INTO `audit_trail` VALUES ('258', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/roles-permissions', NULL, NULL, '2025-12-16 04:11:18', '2025-12-16 04:11:18');
INSERT INTO `audit_trail` VALUES ('259', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/roles-permissions', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/roles-permissions', NULL, '0', '2025-12-16 04:11:18', '2025-12-16 04:11:18');
INSERT INTO `audit_trail` VALUES ('260', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/active-sessions', NULL, NULL, '2025-12-16 04:11:21', '2025-12-16 04:11:21');
INSERT INTO `audit_trail` VALUES ('261', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/active-sessions', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/active-sessions', NULL, '0', '2025-12-16 04:11:21', '2025-12-16 04:11:21');
INSERT INTO `audit_trail` VALUES ('262', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/active-sessions', NULL, NULL, '2025-12-16 04:11:26', '2025-12-16 04:11:26');
INSERT INTO `audit_trail` VALUES ('263', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/active-sessions', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/active-sessions', NULL, '0', '2025-12-16 04:11:26', '2025-12-16 04:11:26');
INSERT INTO `audit_trail` VALUES ('264', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/backup-recovery', NULL, NULL, '2025-12-16 04:11:55', '2025-12-16 04:11:55');
INSERT INTO `audit_trail` VALUES ('265', '1', 'admin', 'WarehouseSystem', 'admin', '', 'general', NULL, NULL, NULL, NULL, 'HTTP GET request to /WarehouseSystem/admin/backup-recovery', 'Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'GET', 'http://localhost/WarehouseSystem/admin/backup-recovery', NULL, '0', '2025-12-16 04:11:55', '2025-12-16 04:11:55');
INSERT INTO `audit_trail` VALUES ('266', '1', 'admin', 'Security', 'Security', '', '', NULL, NULL, NULL, NULL, 'User \'admin\' accessed \'admin\' area', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'POST', 'http://localhost/WarehouseSystem/admin/backup-database', NULL, NULL, '2025-12-16 04:12:15', '2025-12-16 04:12:15');



-- Table structure for `batch_tracking`
DROP TABLE IF EXISTS `batch_tracking`;
CREATE TABLE `batch_tracking` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `inventory_id` int(11) NOT NULL COMMENT 'Reference to inventory item',
  `batch_number` varchar(100) NOT NULL COMMENT 'Unique batch identifier from supplier',
  `reference_number` varchar(100) DEFAULT NULL COMMENT 'PO or receipt number',
  `manufacture_date` date DEFAULT NULL COMMENT 'Item manufacture/production date',
  `expiry_date` date DEFAULT NULL COMMENT 'Item expiration date (if applicable)',
  `days_until_expiry` int(11) DEFAULT NULL COMMENT 'Calculated days remaining until expiry',
  `quantity_received` int(11) NOT NULL COMMENT 'Total quantity for this batch',
  `quantity_available` int(11) NOT NULL COMMENT 'Quantity not yet used/sold',
  `quantity_used` int(11) NOT NULL DEFAULT 0 COMMENT 'Quantity consumed/sold from batch',
  `supplier_id` int(11) DEFAULT NULL COMMENT 'Vendor/supplier reference',
  `warehouse_id` int(11) DEFAULT NULL COMMENT 'Primary storage warehouse',
  `quality_status` enum('Active','Quarantine','Approved','Rejected','Expired','Damaged') NOT NULL DEFAULT 'Active' COMMENT 'Quality control status',
  `quality_notes` text DEFAULT NULL COMMENT 'Quality inspection notes',
  `inspected_by` int(11) DEFAULT NULL COMMENT 'Auditor who inspected batch',
  `inspection_date` datetime DEFAULT NULL COMMENT 'When batch was inspected',
  `received_date` datetime NOT NULL COMMENT 'When batch was received',
  `received_by` int(11) DEFAULT NULL COMMENT 'User who received batch',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `batch_number` (`batch_number`),
  KEY `inventory_id` (`inventory_id`),
  KEY `expiry_date` (`expiry_date`),
  KEY `quality_status` (`quality_status`),
  KEY `warehouse_id` (`warehouse_id`),
  KEY `supplier_id` (`supplier_id`),
  KEY `received_date` (`received_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



-- Table structure for `clients`
DROP TABLE IF EXISTS `clients`;
CREATE TABLE `clients` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `client_name` varchar(255) NOT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_by` varchar(100) DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for table `clients`
INSERT INTO `clients` VALUES ('1', 'ABC Construction & Development Corp.', 'Fernando Pascual', 'fernando@abcconstruction.com', '+63 917 123 4567', '123 Builder Street, Ortigas Center, Pasig City, Philippines', 'Active', 'System', NULL, '2025-12-15 05:47:06', '2025-12-15 05:47:06');
INSERT INTO `clients` VALUES ('2', 'XYZ Builders & Associates', 'Maria Ramos', 'maria@xyzbuilders.ph', '+63 917 234 5678', '456 Construction Ave, BGC, Taguig City, Philippines', 'Active', 'System', NULL, '2025-12-15 05:47:06', '2025-12-15 05:47:06');
INSERT INTO `clients` VALUES ('3', 'Metro Property Developers Inc.', 'Jose Santos', 'jose@metroprop.com', '+63 917 345 6789', '789 Development Road, Makati City, Philippines', 'Active', 'System', NULL, '2025-12-15 05:47:06', '2025-12-15 05:47:06');
INSERT INTO `clients` VALUES ('4', 'Prime Infrastructure Solutions', 'Roberto Cruz', 'roberto@primeinfra.ph', '+63 917 456 7890', '321 Infrastructure Blvd, Quezon City, Philippines', 'Active', 'System', NULL, '2025-12-15 05:47:06', '2025-12-15 05:47:06');
INSERT INTO `clients` VALUES ('5', 'Summit Engineering & Construction', 'Patricia Lim', 'patricia@summiteng.com', '+63 917 567 8901', '654 Summit Street, Mandaluyong City, Philippines', 'Active', 'System', NULL, '2025-12-15 05:47:06', '2025-12-15 05:47:06');
INSERT INTO `clients` VALUES ('6', 'Golden Gate Contracting Services', 'David Tan', 'david@goldengatecontracting.ph', '+63 917 678 9012', '987 Contract Avenue, Pasay City, Philippines', 'Active', 'System', NULL, '2025-12-15 05:47:06', '2025-12-15 05:47:06');
INSERT INTO `clients` VALUES ('7', 'Pacific Realty & Construction', 'Sarah Villanueva', 'sarah@pacificrealty.com', '+63 917 789 0123', '147 Pacific Road, Manila, Philippines', 'Active', 'System', NULL, '2025-12-15 05:47:06', '2025-12-15 05:47:06');
INSERT INTO `clients` VALUES ('8', 'Nationwide Builders Group', 'Michael Reyes', 'michael@nationwidebuilders.ph', '+63 917 890 1234', '258 Nationwide Street, Caloocan City, Philippines', 'Active', 'System', NULL, '2025-12-15 05:47:06', '2025-12-15 05:47:06');
INSERT INTO `clients` VALUES ('9', 'Innovative Housing Corporation', 'Elizabeth Garcia', 'elizabeth@innovativehousing.com', '+63 917 901 2345', '369 Housing Blvd, Muntinlupa City, Philippines', 'Active', 'System', NULL, '2025-12-15 05:47:06', '2025-12-15 05:47:06');
INSERT INTO `clients` VALUES ('10', 'Master Builders Association', 'Anthony Lopez', 'anthony@masterbuilders.ph', '+63 917 012 3456', '741 Master Street, Paranaque City, Philippines', 'Inactive', 'System', NULL, '2025-12-15 05:47:06', '2025-12-15 05:47:06');
INSERT INTO `clients` VALUES ('11', 'ABC Corporation', 'John Doe', 'john@abccorp.com', '+1-234-567-8900', '123 Business Street, Metro Manila', 'Active', 'admin', NULL, '2025-12-15 17:55:09', '2025-12-15 17:55:09');
INSERT INTO `clients` VALUES ('12', 'XYZ Industries', 'Jane Smith', 'jane@xyzind.com', '+1-234-567-8901', '456 Commerce Avenue, Makati City', 'Active', 'admin', NULL, '2025-12-15 17:55:09', '2025-12-15 17:55:09');
INSERT INTO `clients` VALUES ('13', 'Tech Solutions Inc.', 'Bob Johnson', 'bob@techsolutions.com', '+1-234-567-8902', '789 Technology Park, Quezon City', 'Active', 'admin', NULL, '2025-12-15 17:55:09', '2025-12-15 17:55:09');
INSERT INTO `clients` VALUES ('14', 'Global Trading Co.', 'Alice Brown', 'alice@globaltrading.com', '+1-234-567-8903', '321 Export Street, Pasig City', 'Active', 'admin', NULL, '2025-12-15 17:55:09', '2025-12-15 17:55:09');
INSERT INTO `clients` VALUES ('15', 'Premier Services Ltd.', 'Charlie Wilson', 'charlie@premierservices.com', '+1-234-567-8904', '654 Service Road, Mandaluyong', 'Active', 'admin', NULL, '2025-12-15 17:55:09', '2025-12-15 17:55:09');



-- Table structure for `count_details`
DROP TABLE IF EXISTS `count_details`;
CREATE TABLE `count_details` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `count_id` int(11) NOT NULL COMMENT 'Reference to physical_counts',
  `inventory_id` int(11) NOT NULL COMMENT 'Item being counted',
  `system_quantity` int(11) NOT NULL COMMENT 'Quantity recorded in system',
  `physical_quantity` int(11) NOT NULL COMMENT 'Actual quantity physically counted',
  `discrepancy` int(11) NOT NULL COMMENT 'Difference (positive or negative)',
  `discrepancy_percentage` decimal(5,2) DEFAULT NULL COMMENT 'Percentage difference',
  `has_discrepancy` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Flag if discrepancy exists',
  `discrepancy_type` enum('Overage','Shortage','None') NOT NULL DEFAULT 'None' COMMENT 'Type of discrepancy',
  `counted_by` int(11) NOT NULL COMMENT 'Staff member who counted',
  `verified_by` int(11) DEFAULT NULL COMMENT 'Auditor who verified count',
  `verification_status` enum('Pending','Verified','Approved','Rejected') NOT NULL DEFAULT 'Pending' COMMENT 'Verification status',
  `resolution_status` enum('Not Required','Pending','In Progress','Resolved','Waived') NOT NULL DEFAULT 'Not Required' COMMENT 'Status of discrepancy resolution',
  `investigation_notes` text DEFAULT NULL COMMENT 'Notes on discrepancy investigation',
  `resolution_action` varchar(255) DEFAULT NULL COMMENT 'Action taken to resolve discrepancy',
  `resolved_date` datetime DEFAULT NULL COMMENT 'When discrepancy was resolved',
  `count_timestamp` datetime NOT NULL COMMENT 'When this item was counted',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `count_id` (`count_id`),
  KEY `inventory_id` (`inventory_id`),
  KEY `has_discrepancy` (`has_discrepancy`),
  KEY `verification_status` (`verification_status`),
  KEY `resolution_status` (`resolution_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



-- Table structure for `inventory`
DROP TABLE IF EXISTS `inventory`;
CREATE TABLE `inventory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(255) NOT NULL COMMENT 'Product name',
  `sku` varchar(100) NOT NULL COMMENT 'Stock Keeping Unit',
  `category` varchar(100) NOT NULL COMMENT 'Product category',
  `description` text DEFAULT NULL COMMENT 'Product description',
  `quantity` int(11) NOT NULL DEFAULT 0 COMMENT 'Current stock quantity',
  `unit` varchar(50) NOT NULL COMMENT 'Unit of measurement (pcs, kg, liters, etc.)',
  `unit_price` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Price per unit',
  `reorder_level` int(11) NOT NULL DEFAULT 0 COMMENT 'Minimum quantity before reorder alert',
  `location` varchar(255) DEFAULT NULL COMMENT 'Storage location/warehouse',
  `status` enum('Active','Inactive','Discontinued') NOT NULL DEFAULT 'Active' COMMENT 'Item status',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sku` (`sku`),
  KEY `category` (`category`),
  KEY `status` (`status`),
  KEY `location` (`location`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for table `inventory`
INSERT INTO `inventory` VALUES ('1', 'Cement Portland', 'CEM-001', 'Construction Materials', '40kg bag of Portland cement', '500', 'bags', '250.00', '100', 'Main Warehouse', 'Active', '2025-12-15 05:10:58', '2025-12-15 05:10:58');
INSERT INTO `inventory` VALUES ('2', 'Steel Rebar 10mm', 'STL-001', 'Construction Materials', '6-meter steel rebar, 10mm diameter', '800', 'pieces', '180.00', '200', 'Main Warehouse', 'Active', '2025-12-15 05:10:58', '2025-12-15 05:10:58');
INSERT INTO `inventory` VALUES ('3', 'Plywood 4x8 Marine', 'PLY-001', 'Wood Products', '4x8 feet marine plywood, 18mm', '150', 'sheets', '850.00', '50', 'Main Warehouse', 'Active', '2025-12-15 05:10:58', '2025-12-15 05:10:58');
INSERT INTO `inventory` VALUES ('4', 'Paint White Latex', 'PNT-001', 'Painting Supplies', '4-liter can of white latex paint', '75', 'cans', '420.00', '30', 'North Distribution Center', 'Active', '2025-12-15 05:10:58', '2025-12-15 05:10:58');
INSERT INTO `inventory` VALUES ('5', 'Electrical Wire 2.0mm', 'ELC-001', 'Electrical Supplies', '100-meter roll of 2.0mm electrical wire', '45', 'rolls', '1200.00', '20', 'North Distribution Center', 'Active', '2025-12-15 05:10:58', '2025-12-15 05:10:58');
INSERT INTO `inventory` VALUES ('6', 'Gravel 3/4 inch', 'GRV-001', 'Construction Materials', 'Construction gravel, 3/4 inch', '30', 'cubic meters', '800.00', '10', 'South Warehouse', 'Active', '2025-12-15 05:10:58', '2025-12-15 05:10:58');
INSERT INTO `inventory` VALUES ('7', 'Hollow Blocks 4\"', 'BLK-001', 'Construction Materials', '4-inch hollow blocks', '5', 'pieces', '8.50', '1000', 'South Warehouse', 'Active', '2025-12-15 05:10:58', '2025-12-15 05:10:58');
INSERT INTO `inventory` VALUES ('8', 'Roofing Sheets Corrugated', 'ROF-001', 'Roofing Materials', '8-feet corrugated roofing sheets', '0', 'sheets', '380.00', '50', 'Main Warehouse', 'Active', '2025-12-15 05:10:58', '2025-12-15 05:10:58');
INSERT INTO `inventory` VALUES ('9', 'Steel Rebar 10mm Grade 40', 'STL-RB-010', 'Steel & Metal', '6-meter steel reinforcement bar, 10mm diameter, Grade 40', '2500', 'pieces', '180.00', '500', 'WH-MNL-001-B2', 'Active', '2025-12-15 05:40:22', '2025-12-15 05:40:22');
INSERT INTO `inventory` VALUES ('10', 'Steel Rebar 12mm Grade 40', 'STL-RB-012', 'Steel & Metal', '6-meter steel reinforcement bar, 12mm diameter, Grade 40', '2000', 'pieces', '220.00', '400', 'WH-MNL-001-B3', 'Active', '2025-12-15 05:40:22', '2025-12-15 05:40:22');
INSERT INTO `inventory` VALUES ('11', 'Marine Plywood 4x8 18mm', 'PLY-MAR-418', 'Wood & Plywood', '4x8 feet marine grade plywood, 18mm thickness', '800', 'sheets', '850.00', '150', 'WH-MNL-001-C1', 'Active', '2025-12-15 05:40:22', '2025-12-15 05:40:22');
INSERT INTO `inventory` VALUES ('12', 'Ordinary Plywood 4x8 12mm', 'PLY-ORD-412', 'Wood & Plywood', '4x8 feet ordinary plywood, 12mm thickness', '1200', 'sheets', '450.00', '200', 'WH-MNL-001-C2', 'Active', '2025-12-15 05:40:22', '2025-12-15 05:40:22');
INSERT INTO `inventory` VALUES ('13', 'Latex Paint White 4L', 'PNT-LAT-WHT4', 'Paint & Coatings', '4-liter can of white latex paint, interior/exterior', '500', 'cans', '480.00', '100', 'WH-QC-002-A5', 'Active', '2025-12-15 05:40:22', '2025-12-15 05:40:22');
INSERT INTO `inventory` VALUES ('14', 'Latex Paint Beige 4L', 'PNT-LAT-BEI4', 'Paint & Coatings', '4-liter can of beige latex paint, interior/exterior', '300', 'cans', '480.00', '80', 'WH-QC-002-A5', 'Active', '2025-12-15 05:40:22', '2025-12-15 05:40:22');
INSERT INTO `inventory` VALUES ('15', 'Enamel Paint Grey 1L', 'PNT-ENM-GRY1', 'Paint & Coatings', '1-liter can of grey enamel paint, quick-dry', '400', 'cans', '180.00', '100', 'WH-QC-002-A6', 'Active', '2025-12-15 05:40:22', '2025-12-15 05:40:22');
INSERT INTO `inventory` VALUES ('16', 'Common Wire Nails 2-inch', 'HW-NAIL-2IN', 'Hardware & Fasteners', '2-inch common wire nails, 1kg pack', '5000', 'kg', '85.00', '1000', 'WH-MAK-003-D1', 'Active', '2025-12-15 05:40:22', '2025-12-15 05:40:22');
INSERT INTO `inventory` VALUES ('17', 'Concrete Screws 3-inch', 'HW-SCR-CON3', 'Hardware & Fasteners', '3-inch concrete screws with anchors, box of 100', '800', 'boxes', '250.00', '150', 'WH-MAK-003-D2', 'Active', '2025-12-15 05:40:22', '2025-12-15 05:40:22');
INSERT INTO `inventory` VALUES ('18', 'Wood Screws #8 2-inch', 'HW-SCR-WD82', 'Hardware & Fasteners', '#8 x 2-inch wood screws, box of 100', '1200', 'boxes', '120.00', '250', 'WH-MAK-003-D2', 'Active', '2025-12-15 05:40:22', '2025-12-15 05:40:22');
INSERT INTO `inventory` VALUES ('19', 'PVC Pipe 1/2 inch 3m', 'PLB-PVC-05-3M', 'Plumbing Supplies', '1/2 inch PVC pipe, 3 meters length, Schedule 40', '600', 'pieces', '95.00', '150', 'WH-QC-002-E1', 'Active', '2025-12-15 05:40:22', '2025-12-15 05:40:22');
INSERT INTO `inventory` VALUES ('20', 'PVC Pipe 3/4 inch 3m', 'PLB-PVC-75-3M', 'Plumbing Supplies', '3/4 inch PVC pipe, 3 meters length, Schedule 40', '500', 'pieces', '125.00', '120', 'WH-QC-002-E1', 'Active', '2025-12-15 05:40:22', '2025-12-15 05:40:22');
INSERT INTO `inventory` VALUES ('21', 'Electrical Wire THHN 2.0mm', 'ELC-THHN-20', 'Electrical Supplies', 'THHN electrical wire 2.0mm, per meter', '10000', 'meters', '18.00', '2000', 'WH-QC-002-E5', 'Active', '2025-12-15 05:40:22', '2025-12-15 05:40:22');
INSERT INTO `inventory` VALUES ('22', 'Circuit Breaker 20A 2-pole', 'ELC-CB-20A2P', 'Electrical Supplies', '20 Ampere 2-pole circuit breaker', '150', 'pieces', '350.00', '30', 'WH-QC-002-E6', 'Active', '2025-12-15 05:40:22', '2025-12-15 05:40:22');
INSERT INTO `inventory` VALUES ('23', 'Power Drill 13mm Chuck', 'TLS-DRL-13MM', 'Tools & Equipment', 'Variable speed power drill, 13mm chuck, 550W', '25', 'pieces', '1850.00', '5', 'WH-MAK-003-F1', 'Active', '2025-12-15 05:40:22', '2025-12-15 05:40:22');
INSERT INTO `inventory` VALUES ('24', 'Measuring Tape 5m', 'TLS-TAPE-5M', 'Tools & Equipment', '5-meter measuring tape with lock', '200', 'pieces', '120.00', '50', 'WH-MAK-003-F2', 'Active', '2025-12-15 05:40:22', '2025-12-15 05:40:22');
INSERT INTO `inventory` VALUES ('25', 'Safety Helmet Hard Hat', 'SFT-HLM-001', 'Safety Equipment', 'Safety hard hat helmet, adjustable, various colors', '150', 'pieces', '180.00', '30', 'WH-MNL-001-G1', 'Active', '2025-12-15 05:40:22', '2025-12-15 05:40:22');
INSERT INTO `inventory` VALUES ('26', 'Safety Gloves Leather', 'SFT-GLV-LTH', 'Safety Equipment', 'Leather work gloves, heavy duty, per pair', '300', 'pairs', '95.00', '80', 'WH-MNL-001-G1', 'Active', '2025-12-15 05:40:22', '2025-12-15 05:40:22');
INSERT INTO `inventory` VALUES ('27', 'Concrete Sealer 4L', 'PNT-SLR-CON4', 'Paint & Coatings', '4-liter concrete sealer, water-based', '25', 'cans', '580.00', '50', 'WH-QC-002-A7', 'Active', '2025-12-15 05:40:22', '2025-12-15 05:40:22');



-- Table structure for `invoices`
DROP TABLE IF EXISTS `invoices`;
CREATE TABLE `invoices` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int(11) unsigned NOT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `issue_date` date NOT NULL,
  `due_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `status` enum('Pending','Paid','Overdue','Cancelled') NOT NULL DEFAULT 'Pending',
  `description` text DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_number` (`invoice_number`),
  KEY `client_id` (`client_id`),
  KEY `status` (`status`),
  CONSTRAINT `invoices_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for table `invoices`
INSERT INTO `invoices` VALUES ('1', '11', 'INV-202512-0001', '2025-11-15', '2025-11-30', '50000.00', 'Overdue', 'Warehouse storage services - October 2024', 'admin', NULL, '2025-12-15 17:55:09', '2025-12-15 17:55:09');
INSERT INTO `invoices` VALUES ('2', '12', 'INV-202512-0002', '2025-11-25', '2025-12-25', '75000.00', 'Pending', 'Logistics and transportation services', 'admin', NULL, '2025-12-15 17:55:09', '2025-12-15 17:55:09');
INSERT INTO `invoices` VALUES ('3', '13', 'INV-202512-0003', '2025-12-05', '2026-01-04', '120000.00', 'Pending', 'IT equipment storage and handling', 'admin', NULL, '2025-12-15 17:55:09', '2025-12-15 17:55:09');
INSERT INTO `invoices` VALUES ('4', '14', 'INV-202512-0004', '2025-10-31', '2025-11-15', '85000.00', 'Paid', 'Import/Export handling services', 'admin', NULL, '2025-12-15 17:55:09', '2025-12-15 17:55:09');
INSERT INTO `invoices` VALUES ('5', '15', 'INV-202512-0005', '2025-12-10', '2026-01-09', '95000.00', 'Pending', 'Premium warehouse services package', 'admin', NULL, '2025-12-15 17:55:09', '2025-12-15 17:55:09');
INSERT INTO `invoices` VALUES ('6', '11', 'INV-202512-0006', '2025-10-16', '2025-10-31', '60000.00', 'Paid', 'Monthly storage fees - September 2024', 'admin', NULL, '2025-12-15 17:55:09', '2025-12-15 17:55:09');



-- Table structure for `lot_tracking`
DROP TABLE IF EXISTS `lot_tracking`;
CREATE TABLE `lot_tracking` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `batch_id` int(11) NOT NULL COMMENT 'Reference to batch_tracking',
  `lot_number` varchar(100) NOT NULL COMMENT 'Unique lot number/serial within batch',
  `serial_number` varchar(100) DEFAULT NULL COMMENT 'Item serial number (if applicable)',
  `quantity` int(11) NOT NULL DEFAULT 1 COMMENT 'Quantity in this lot',
  `warehouse_id` int(11) NOT NULL COMMENT 'Current warehouse location',
  `rack_location` varchar(100) DEFAULT NULL COMMENT 'Specific location in warehouse',
  `status` enum('Available','Reserved','Sold','Transferred','Expired','Damaged') NOT NULL DEFAULT 'Available' COMMENT 'Current lot status',
  `is_allocated` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Whether lot is allocated to an order',
  `allocated_to_order_id` int(11) DEFAULT NULL COMMENT 'Order ID if allocated',
  `allocated_date` datetime DEFAULT NULL COMMENT 'When lot was allocated',
  `shipped_date` datetime DEFAULT NULL COMMENT 'When lot was shipped',
  `notes` text DEFAULT NULL COMMENT 'Additional notes',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `serial_number` (`serial_number`),
  KEY `batch_id` (`batch_id`),
  KEY `lot_number` (`lot_number`),
  KEY `warehouse_id` (`warehouse_id`),
  KEY `status` (`status`),
  KEY `allocated_to_order_id` (`allocated_to_order_id`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



-- Table structure for `migrations`
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for table `migrations`
INSERT INTO `migrations` VALUES ('1', '2024-01-01-000001', 'App\\Database\\Migrations\\CreateClientsTable', 'default', 'App', '1765771402', '1');
INSERT INTO `migrations` VALUES ('2', '2024-01-01-000002', 'App\\Database\\Migrations\\CreateInvoicesTable', 'default', 'App', '1765771402', '1');
INSERT INTO `migrations` VALUES ('3', '2024-01-01-000003', 'App\\Database\\Migrations\\CreatePaymentsTable', 'default', 'App', '1765771402', '1');
INSERT INTO `migrations` VALUES ('4', '2025-11-01-130353', 'App\\Database\\Migrations\\CreateUsersTable', 'default', 'App', '1765771403', '1');
INSERT INTO `migrations` VALUES ('5', '2025-11-11-120000', 'App\\Database\\Migrations\\CreateVendorsTable', 'default', 'App', '1765771403', '1');
INSERT INTO `migrations` VALUES ('6', '2025-11-11-120100', 'App\\Database\\Migrations\\CreateVendorInvoicesTable', 'default', 'App', '1765771403', '1');
INSERT INTO `migrations` VALUES ('7', '2025-11-11-120200', 'App\\Database\\Migrations\\CreateVendorPaymentsTable', 'default', 'App', '1765771403', '1');
INSERT INTO `migrations` VALUES ('8', '2025-11-11-121500', 'App\\Database\\Migrations\\AddStatusToUsersTable', 'default', 'App', '1765771403', '1');
INSERT INTO `migrations` VALUES ('9', '2025-12-15-000001', 'App\\Database\\Migrations\\CreateWarehouseInventoryTable', 'default', 'App', '1765771904', '2');
INSERT INTO `migrations` VALUES ('10', '2025-12-15-000002', 'App\\Database\\Migrations\\CreateBatchTrackingTable', 'default', 'App', '1765771980', '3');
INSERT INTO `migrations` VALUES ('11', '2025-12-15-000003', 'App\\Database\\Migrations\\CreateLotTrackingTable', 'default', 'App', '1765772099', '4');
INSERT INTO `migrations` VALUES ('12', '2025-12-15-000004', 'App\\Database\\Migrations\\CreatePhysicalCountsTable', 'default', 'App', '1765772372', '5');
INSERT INTO `migrations` VALUES ('13', '2025-12-15-000005', 'App\\Database\\Migrations\\CreateCountDetailsTable', 'default', 'App', '1765772373', '5');
INSERT INTO `migrations` VALUES ('14', '2025-12-15-000006', 'App\\Database\\Migrations\\CreateAuditTrailTable', 'default', 'App', '1765772373', '5');
INSERT INTO `migrations` VALUES ('15', '2025-12-15-000007', 'App\\Database\\Migrations\\AddBarcodeFieldsToInventory', 'default', 'App', '1765772447', '6');
INSERT INTO `migrations` VALUES ('16', '2025-12-15-000008', 'App\\Database\\Migrations\\CreatePurchaseRequisitionsTable', 'default', 'App', '1765772447', '6');
INSERT INTO `migrations` VALUES ('17', '2025-12-15-000009', 'App\\Database\\Migrations\\CreateRequisitionItemsTable', 'default', 'App', '1765772447', '6');
INSERT INTO `migrations` VALUES ('18', '2025-12-15-000010', 'App\\Database\\Migrations\\CreatePurchaseOrdersTable', 'default', 'App', '1765772447', '6');
INSERT INTO `migrations` VALUES ('19', '2025-12-15-000011', 'App\\Database\\Migrations\\CreatePurchaseOrderItemsTable', 'default', 'App', '1765772447', '6');
INSERT INTO `migrations` VALUES ('20', '2025-12-15-100000', 'App\\Database\\Migrations\\CreateInventoryTable', 'default', 'App', '1765775397', '7');
INSERT INTO `migrations` VALUES ('21', '2025-12-15-100001', 'App\\Database\\Migrations\\CreateWarehousesTable', 'default', 'App', '1765775397', '7');
INSERT INTO `migrations` VALUES ('22', '2025-12-15-100002', 'App\\Database\\Migrations\\CreateStockMovementsTable', 'default', 'App', '1765775397', '7');
INSERT INTO `migrations` VALUES ('23', '2025-12-15-100003', 'App\\Database\\Migrations\\CreateOrdersTable', 'default', 'App', '1765775397', '7');
INSERT INTO `migrations` VALUES ('24', '2025-12-15-100004', 'App\\Database\\Migrations\\CreateOrderItemsTable', 'default', 'App', '1765777209', '8');
INSERT INTO `migrations` VALUES ('25', '2025-12-15-100005', 'App\\Database\\Migrations\\AddForeignKeys', 'default', 'App', '1765777209', '8');



-- Table structure for `order_items`
DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL COMMENT 'Reference to orders table',
  `inventory_id` int(11) NOT NULL COMMENT 'Reference to inventory table',
  `quantity` int(11) NOT NULL COMMENT 'Quantity ordered',
  `unit_price` decimal(10,2) NOT NULL COMMENT 'Price per unit at time of order',
  `subtotal` decimal(12,2) NOT NULL COMMENT 'Line item total (quantity * unit_price)',
  `notes` text DEFAULT NULL COMMENT 'Line item specific notes',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `inventory_id` (`inventory_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for table `order_items`
INSERT INTO `order_items` VALUES ('1', '1', '1', '200', '250.00', '50000.00', NULL, '2025-12-03 05:47:06');
INSERT INTO `order_items` VALUES ('2', '1', '4', '50', '850.00', '42500.00', NULL, '2025-12-03 05:47:06');
INSERT INTO `order_items` VALUES ('3', '2', '2', '500', '180.00', '90000.00', NULL, '2025-12-08 05:47:06');
INSERT INTO `order_items` VALUES ('4', '2', '3', '200', '220.00', '44000.00', NULL, '2025-12-08 05:47:06');
INSERT INTO `order_items` VALUES ('5', '3', '1', '300', '250.00', '75000.00', NULL, '2025-12-12 05:47:06');
INSERT INTO `order_items` VALUES ('6', '3', '6', '50', '480.00', '24000.00', NULL, '2025-12-12 05:47:06');
INSERT INTO `order_items` VALUES ('7', '4', '2', '2000', '175.00', '350000.00', 'Bulk purchase', '2025-11-30 05:47:06');
INSERT INTO `order_items` VALUES ('8', '4', '3', '1000', '210.00', '210000.00', 'Bulk purchase', '2025-11-30 05:47:06');
INSERT INTO `order_items` VALUES ('9', '5', '6', '100', '0.00', '0.00', 'Transfer from Main to North DC', '2025-12-13 05:47:06');
INSERT INTO `order_items` VALUES ('10', '1', '1', '200', '250.00', '50000.00', NULL, '2025-12-03 11:13:06');
INSERT INTO `order_items` VALUES ('11', '1', '4', '50', '850.00', '42500.00', NULL, '2025-12-03 11:13:06');
INSERT INTO `order_items` VALUES ('12', '2', '2', '500', '180.00', '90000.00', NULL, '2025-12-08 11:13:06');
INSERT INTO `order_items` VALUES ('13', '2', '3', '200', '220.00', '44000.00', NULL, '2025-12-08 11:13:06');
INSERT INTO `order_items` VALUES ('14', '3', '1', '300', '250.00', '75000.00', NULL, '2025-12-12 11:13:06');
INSERT INTO `order_items` VALUES ('15', '3', '6', '50', '480.00', '24000.00', NULL, '2025-12-12 11:13:06');
INSERT INTO `order_items` VALUES ('16', '4', '2', '2000', '175.00', '350000.00', 'Bulk purchase', '2025-11-30 11:13:06');
INSERT INTO `order_items` VALUES ('17', '4', '3', '1000', '210.00', '210000.00', 'Bulk purchase', '2025-11-30 11:13:06');
INSERT INTO `order_items` VALUES ('18', '5', '6', '100', '0.00', '0.00', 'Transfer from Main to North DC', '2025-12-13 11:13:06');
INSERT INTO `order_items` VALUES ('19', '1', '1', '200', '250.00', '50000.00', NULL, '2025-12-03 17:55:09');
INSERT INTO `order_items` VALUES ('20', '1', '4', '50', '850.00', '42500.00', NULL, '2025-12-03 17:55:09');
INSERT INTO `order_items` VALUES ('21', '2', '2', '500', '180.00', '90000.00', NULL, '2025-12-08 17:55:09');
INSERT INTO `order_items` VALUES ('22', '2', '3', '200', '220.00', '44000.00', NULL, '2025-12-08 17:55:09');
INSERT INTO `order_items` VALUES ('23', '3', '1', '300', '250.00', '75000.00', NULL, '2025-12-12 17:55:09');
INSERT INTO `order_items` VALUES ('24', '3', '6', '50', '480.00', '24000.00', NULL, '2025-12-12 17:55:09');
INSERT INTO `order_items` VALUES ('25', '4', '2', '2000', '175.00', '350000.00', 'Bulk purchase', '2025-11-30 17:55:09');
INSERT INTO `order_items` VALUES ('26', '4', '3', '1000', '210.00', '210000.00', 'Bulk purchase', '2025-11-30 17:55:09');
INSERT INTO `order_items` VALUES ('27', '5', '6', '100', '0.00', '0.00', 'Transfer from Main to North DC', '2025-12-13 17:55:09');



-- Table structure for `orders`
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_number` varchar(100) NOT NULL COMMENT 'Unique order number',
  `order_type` enum('Customer','Supplier','Transfer','Internal') NOT NULL DEFAULT 'Customer' COMMENT 'Type of order',
  `customer_name` varchar(255) DEFAULT NULL COMMENT 'Customer or supplier name',
  `customer_email` varchar(255) DEFAULT NULL COMMENT 'Contact email',
  `customer_phone` varchar(50) DEFAULT NULL COMMENT 'Contact phone',
  `items` text DEFAULT NULL COMMENT 'JSON array of order items',
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT 'Total order amount',
  `status` enum('Pending','Processing','Completed','Cancelled','On Hold') NOT NULL DEFAULT 'Pending' COMMENT 'Order status',
  `priority` enum('Low','Normal','High','Urgent') NOT NULL DEFAULT 'Normal' COMMENT 'Order priority',
  `delivery_address` text DEFAULT NULL COMMENT 'Delivery address',
  `delivery_date` date DEFAULT NULL COMMENT 'Expected delivery date',
  `processed_by` int(11) DEFAULT NULL COMMENT 'User who processed the order',
  `processed_at` datetime DEFAULT NULL COMMENT 'When order was processed',
  `completed_at` datetime DEFAULT NULL COMMENT 'When order was completed',
  `notes` text DEFAULT NULL COMMENT 'Order notes',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `status` (`status`),
  KEY `order_type` (`order_type`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for table `orders`
INSERT INTO `orders` VALUES ('1', 'ORD-20251215-001', 'Customer', 'ABC Construction Company', 'orders@abcconstruction.com', '0917-123-4567', '[{\"sku\":\"CEM-001\",\"quantity\":100,\"price\":250},{\"sku\":\"STL-001\",\"quantity\":200,\"price\":180}]', '61000.00', 'Pending', 'High', '123 Project Site, Quezon City', '2025-12-18', NULL, NULL, NULL, 'Urgent delivery required', '2025-12-15 05:10:58', '2025-12-15 05:10:58');
INSERT INTO `orders` VALUES ('2', 'ORD-20251215-002', 'Customer', 'XYZ Builders Inc', 'purchasing@xyzbuilders.com', '0918-234-5678', '[{\"sku\":\"PLY-001\",\"quantity\":50,\"price\":850},{\"sku\":\"PNT-001\",\"quantity\":20,\"price\":420}]', '50900.00', 'Processing', 'Normal', '456 Site Location, Makati', '2025-12-20', NULL, NULL, NULL, 'Contact before delivery', '2025-12-14 05:10:58', '2025-12-15 05:10:58');
INSERT INTO `orders` VALUES ('3', 'ORD-2025-001', 'Customer', 'ABC Construction & Development Corp.', 'fernando@abcconstruction.com', '+63 917 123 4567', NULL, '75000.00', 'Completed', 'High', 'Project Site: Ortigas Center, Pasig City', '2025-12-20', '3', '2025-12-05 05:47:06', '2025-12-07 05:47:06', 'Urgent delivery - construction deadline', '2025-12-03 05:47:06', '2025-12-07 05:47:06');
INSERT INTO `orders` VALUES ('4', 'ORD-2025-002', 'Customer', 'XYZ Builders & Associates', 'maria@xyzbuilders.ph', '+63 917 234 5678', NULL, '125000.00', 'Processing', 'Normal', 'Project Site: BGC, Taguig City', '2025-12-22', '3', '2025-12-10 05:47:06', NULL, 'Regular delivery schedule', '2025-12-08 05:47:06', '2025-12-10 05:47:06');
INSERT INTO `orders` VALUES ('5', 'ORD-2025-003', 'Customer', 'Metro Property Developers Inc.', 'jose@metroprop.com', '+63 917 345 6789', NULL, '95000.00', 'Pending', 'Normal', 'Project Site: Makati Avenue, Makati City', '2025-12-25', NULL, NULL, NULL, 'Awaiting payment confirmation', '2025-12-12 05:47:06', '2025-12-12 05:47:06');
INSERT INTO `orders` VALUES ('6', 'ORD-2025-004', 'Supplier', 'Manila Steel Trading Corporation', 'sales@manilasteel.com.ph', '+63 2 8123 4567', NULL, '450000.00', 'Completed', 'High', 'Main Warehouse Manila', '2025-12-10', '6', '2025-12-05 05:47:06', '2025-12-10 05:47:06', 'Restocking order - steel products', '2025-11-30 05:47:06', '2025-12-10 05:47:06');
INSERT INTO `orders` VALUES ('7', 'ORD-2025-005', 'Transfer', 'Internal Transfer', NULL, NULL, NULL, '0.00', 'Processing', 'Normal', 'From Main Warehouse to North DC', '2025-12-17', '3', '2025-12-14 05:47:06', NULL, 'Warehouse balancing - paint products', '2025-12-13 05:47:06', '2025-12-14 05:47:06');



-- Table structure for `payments`
DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) unsigned NOT NULL,
  `payment_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `reference_number` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `recorded_by` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`),
  KEY `payment_date` (`payment_date`),
  CONSTRAINT `payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for table `payments`
INSERT INTO `payments` VALUES ('1', '4', '2025-11-10', '85000.00', 'Bank Transfer', 'REF-557405', 'Full payment received', 'admin', '2025-12-15 17:55:09', '2025-12-15 17:55:09');
INSERT INTO `payments` VALUES ('2', '6', '2025-11-10', '60000.00', 'Bank Transfer', 'REF-458462', 'Full payment received', 'admin', '2025-12-15 17:55:09', '2025-12-15 17:55:09');



-- Table structure for `physical_counts`
DROP TABLE IF EXISTS `physical_counts`;
CREATE TABLE `physical_counts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `count_number` varchar(100) NOT NULL COMMENT 'Reference number for this count',
  `warehouse_id` int(11) NOT NULL COMMENT 'Warehouse being counted',
  `initiated_by` int(11) NOT NULL COMMENT 'User who initiated the count',
  `count_type` enum('Full Count','Partial Count','Cycle Count','Recount') NOT NULL DEFAULT 'Full Count' COMMENT 'Type of inventory count',
  `status` enum('In Progress','Completed','Discrepancies','Verified','Closed') NOT NULL DEFAULT 'In Progress' COMMENT 'Current status of count',
  `count_start_date` datetime NOT NULL COMMENT 'When count started',
  `count_end_date` datetime DEFAULT NULL COMMENT 'When count completed',
  `total_items_counted` int(11) NOT NULL DEFAULT 0 COMMENT 'Total number of item lines counted',
  `total_discrepancies` int(11) NOT NULL DEFAULT 0 COMMENT 'Number of items with discrepancies',
  `approved_by` int(11) DEFAULT NULL COMMENT 'Supervisor/Manager who approved results',
  `approved_date` datetime DEFAULT NULL COMMENT 'When count was approved',
  `notes` text DEFAULT NULL COMMENT 'General notes about the count',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `count_number` (`count_number`),
  KEY `warehouse_id` (`warehouse_id`),
  KEY `status` (`status`),
  KEY `count_start_date` (`count_start_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



-- Table structure for `purchase_order_items`
DROP TABLE IF EXISTS `purchase_order_items`;
CREATE TABLE `purchase_order_items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `po_id` int(11) NOT NULL,
  `inventory_id` int(11) NOT NULL,
  `quantity_ordered` int(11) NOT NULL,
  `quantity_received` int(11) NOT NULL DEFAULT 0,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(12,2) NOT NULL,
  `status` enum('Pending','Partially Received','Received') NOT NULL DEFAULT 'Pending',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `po_id` (`po_id`),
  KEY `inventory_id` (`inventory_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



-- Table structure for `purchase_orders`
DROP TABLE IF EXISTS `purchase_orders`;
CREATE TABLE `purchase_orders` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `po_number` varchar(100) NOT NULL COMMENT 'Unique purchase order number',
  `requisition_id` int(11) DEFAULT NULL COMMENT 'Source requisition',
  `vendor_id` int(11) NOT NULL COMMENT 'Supplier',
  `warehouse_id` int(11) NOT NULL COMMENT 'Delivery warehouse',
  `order_date` date NOT NULL COMMENT 'Date PO was placed',
  `expected_delivery_date` date DEFAULT NULL,
  `actual_delivery_date` date DEFAULT NULL,
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` enum('Draft','Sent','Confirmed','Shipped','Partially Received','Received','Cancelled') NOT NULL DEFAULT 'Draft',
  `payment_terms` varchar(100) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `po_number` (`po_number`),
  KEY `vendor_id` (`vendor_id`),
  KEY `status` (`status`),
  KEY `order_date` (`order_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



-- Table structure for `purchase_requisitions`
DROP TABLE IF EXISTS `purchase_requisitions`;
CREATE TABLE `purchase_requisitions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `requisition_number` varchar(100) NOT NULL COMMENT 'Unique requisition number',
  `requested_by` int(11) NOT NULL COMMENT 'User who created requisition',
  `warehouse_id` int(11) NOT NULL COMMENT 'Target warehouse for items',
  `priority` enum('Low','Medium','High','Urgent') NOT NULL DEFAULT 'Medium',
  `reason` text DEFAULT NULL COMMENT 'Justification for requisition',
  `status` enum('Draft','Submitted','Approved','Rejected','Ordered','Received','Cancelled') NOT NULL DEFAULT 'Draft',
  `approved_by` int(11) DEFAULT NULL,
  `approved_date` datetime DEFAULT NULL,
  `required_date` date DEFAULT NULL COMMENT 'Date items are needed by',
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `requisition_number` (`requisition_number`),
  KEY `status` (`status`),
  KEY `requested_by` (`requested_by`),
  KEY `warehouse_id` (`warehouse_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



-- Table structure for `requisition_items`
DROP TABLE IF EXISTS `requisition_items`;
CREATE TABLE `requisition_items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `requisition_id` int(11) NOT NULL,
  `inventory_id` int(11) NOT NULL,
  `quantity_requested` int(11) NOT NULL COMMENT 'Quantity needed',
  `quantity_approved` int(11) DEFAULT NULL COMMENT 'Quantity approved (may differ)',
  `estimated_unit_price` decimal(10,2) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `requisition_id` (`requisition_id`),
  KEY `inventory_id` (`inventory_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



-- Table structure for `stock_movements`
DROP TABLE IF EXISTS `stock_movements`;
CREATE TABLE `stock_movements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL COMMENT 'Reference to inventory item',
  `warehouse_id` int(11) DEFAULT NULL COMMENT 'Reference to warehouse',
  `movement_type` varchar(50) NOT NULL COMMENT 'Type of movement: Stock In, Stock Out, Adjustment, Transfer, Return, Initial Stock',
  `quantity` int(11) NOT NULL COMMENT 'Quantity moved',
  `reference_number` varchar(100) DEFAULT NULL COMMENT 'Reference document number',
  `user_id` int(11) DEFAULT NULL COMMENT 'User who performed the movement',
  `notes` text DEFAULT NULL COMMENT 'Additional notes',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`),
  KEY `warehouse_id` (`warehouse_id`),
  KEY `movement_type` (`movement_type`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for table `stock_movements`
INSERT INTO `stock_movements` VALUES ('1', '1', '1', 'Initial Stock', '1500', 'INIT-2025-001', '3', 'Initial stock count - Portland Cement Type 1', '2025-11-15 05:47:06');
INSERT INTO `stock_movements` VALUES ('2', '2', '1', 'Initial Stock', '2500', 'INIT-2025-002', '3', 'Initial stock count - Steel Rebar 10mm', '2025-11-15 05:47:06');
INSERT INTO `stock_movements` VALUES ('3', '3', '1', 'Initial Stock', '2000', 'INIT-2025-003', '3', 'Initial stock count - Steel Rebar 12mm', '2025-11-15 05:47:06');
INSERT INTO `stock_movements` VALUES ('4', '1', '1', 'Stock In', '500', 'PO-2025-101', '4', 'Received from Philippine Cement Industries', '2025-11-30 05:47:06');
INSERT INTO `stock_movements` VALUES ('5', '4', '1', 'Stock In', '300', 'PO-2025-102', '4', 'Received from Pacific Lumber Supply', '2025-12-01 05:47:06');
INSERT INTO `stock_movements` VALUES ('6', '1', '1', 'Stock Out', '-200', 'SO-2025-001', '4', 'Delivered to ABC Construction - Order ORD-2025-001', '2025-12-05 05:47:06');
INSERT INTO `stock_movements` VALUES ('7', '2', '1', 'Stock Out', '-500', 'SO-2025-002', '4', 'Delivered to XYZ Builders - Order ORD-2025-002', '2025-12-07 05:47:06');
INSERT INTO `stock_movements` VALUES ('8', '6', '2', 'Transfer', '200', 'TRF-2025-001', '3', 'Transfer from Main Warehouse to North DC', '2025-12-10 05:47:06');
INSERT INTO `stock_movements` VALUES ('9', '9', '3', 'Adjustment', '-50', 'ADJ-2025-001', '5', 'Physical count variance - damaged goods removed', '2025-12-12 05:47:06');
INSERT INTO `stock_movements` VALUES ('10', '10', '3', 'Return', '20', 'RET-2025-001', '4', 'Customer return - unused items from project', '2025-12-13 05:47:06');
INSERT INTO `stock_movements` VALUES ('11', '1', '1', 'Initial Stock', '1500', 'INIT-2025-001', '3', 'Initial stock count - Portland Cement Type 1', '2025-11-15 11:13:06');
INSERT INTO `stock_movements` VALUES ('12', '2', '1', 'Initial Stock', '2500', 'INIT-2025-002', '3', 'Initial stock count - Steel Rebar 10mm', '2025-11-15 11:13:06');
INSERT INTO `stock_movements` VALUES ('13', '3', '1', 'Initial Stock', '2000', 'INIT-2025-003', '3', 'Initial stock count - Steel Rebar 12mm', '2025-11-15 11:13:06');
INSERT INTO `stock_movements` VALUES ('14', '1', '1', 'Stock In', '500', 'PO-2025-101', '4', 'Received from Philippine Cement Industries', '2025-11-30 11:13:06');
INSERT INTO `stock_movements` VALUES ('15', '4', '1', 'Stock In', '300', 'PO-2025-102', '4', 'Received from Pacific Lumber Supply', '2025-12-01 11:13:06');
INSERT INTO `stock_movements` VALUES ('16', '1', '1', 'Stock Out', '-200', 'SO-2025-001', '4', 'Delivered to ABC Construction - Order ORD-2025-001', '2025-12-05 11:13:06');
INSERT INTO `stock_movements` VALUES ('17', '2', '1', 'Stock Out', '-500', 'SO-2025-002', '4', 'Delivered to XYZ Builders - Order ORD-2025-002', '2025-12-07 11:13:06');
INSERT INTO `stock_movements` VALUES ('18', '6', '2', 'Transfer', '200', 'TRF-2025-001', '3', 'Transfer from Main Warehouse to North DC', '2025-12-10 11:13:06');
INSERT INTO `stock_movements` VALUES ('19', '9', '3', 'Adjustment', '-50', 'ADJ-2025-001', '5', 'Physical count variance - damaged goods removed', '2025-12-12 11:13:06');
INSERT INTO `stock_movements` VALUES ('20', '10', '3', 'Return', '20', 'RET-2025-001', '4', 'Customer return - unused items from project', '2025-12-13 11:13:06');
INSERT INTO `stock_movements` VALUES ('21', '1', '1', 'Initial Stock', '1500', 'INIT-2025-001', '3', 'Initial stock count - Portland Cement Type 1', '2025-11-15 17:55:09');
INSERT INTO `stock_movements` VALUES ('22', '2', '1', 'Initial Stock', '2500', 'INIT-2025-002', '3', 'Initial stock count - Steel Rebar 10mm', '2025-11-15 17:55:09');
INSERT INTO `stock_movements` VALUES ('23', '3', '1', 'Initial Stock', '2000', 'INIT-2025-003', '3', 'Initial stock count - Steel Rebar 12mm', '2025-11-15 17:55:09');
INSERT INTO `stock_movements` VALUES ('24', '1', '1', 'Stock In', '500', 'PO-2025-101', '4', 'Received from Philippine Cement Industries', '2025-11-30 17:55:09');
INSERT INTO `stock_movements` VALUES ('25', '4', '1', 'Stock In', '300', 'PO-2025-102', '4', 'Received from Pacific Lumber Supply', '2025-12-01 17:55:09');
INSERT INTO `stock_movements` VALUES ('26', '1', '1', 'Stock Out', '-200', 'SO-2025-001', '4', 'Delivered to ABC Construction - Order ORD-2025-001', '2025-12-05 17:55:09');
INSERT INTO `stock_movements` VALUES ('27', '2', '1', 'Stock Out', '-500', 'SO-2025-002', '4', 'Delivered to XYZ Builders - Order ORD-2025-002', '2025-12-07 17:55:09');
INSERT INTO `stock_movements` VALUES ('28', '6', '2', 'Transfer', '200', 'TRF-2025-001', '3', 'Transfer from Main Warehouse to North DC', '2025-12-10 17:55:09');
INSERT INTO `stock_movements` VALUES ('29', '9', '3', 'Adjustment', '-50', 'ADJ-2025-001', '5', 'Physical count variance - damaged goods removed', '2025-12-12 17:55:09');
INSERT INTO `stock_movements` VALUES ('30', '10', '3', 'Return', '20', 'RET-2025-001', '4', 'Customer return - unused items from project', '2025-12-13 17:55:09');



-- Table structure for `users`
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Warehouse Manager','Warehouse Staff','Inventory Auditor','Procurement Officer','Accounts Payable Clerk','Accounts Receivable Clerk','IT Administrator','Top Management','admin','user') NOT NULL DEFAULT 'user',
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for table `users`
INSERT INTO `users` VALUES ('1', 'admin', 'System Administrator', 'admin@webuild.com', '$2y$10$Gw64IHjD.8WXIGcx9M/huOop60JzwnpChj6rZQI.MLO1bcMdzq7fC', 'IT Administrator', 'Active', '2025-12-15 17:55:08', '2025-12-15 17:55:08');
INSERT INTO `users` VALUES ('2', 'manager', 'Project Manager', 'manager@webuild.com', '$2y$10$4c4iqim5j9rOlgqK9kzP5eUGOW311sNByRQ2DhZcyg52E7TwgqR5e', 'Top Management', 'Active', '2025-12-15 17:55:08', '2025-12-15 17:55:08');
INSERT INTO `users` VALUES ('3', 'warehouse_manager', 'John Smith', 'john.smith@webuild.com', '$2y$10$5P16lEkDUbEoTMU57oQ59uGBWUwVnU7giumEyFiPT9ZT8Ry6OpdhW', 'Warehouse Manager', 'Active', '2025-12-15 17:55:08', '2025-12-15 17:55:08');
INSERT INTO `users` VALUES ('4', 'warehouse_staff', 'Jane Doe', 'jane.doe@webuild.com', '$2y$10$skhAfwoF1zkGXXsabT/zeuhou5Ztki/9iRNdA3f.xx6cgKNYs6dAC', 'Warehouse Staff', 'Active', '2025-12-15 17:55:08', '2025-12-15 17:55:08');
INSERT INTO `users` VALUES ('5', 'inventory_auditor', 'Mike Johnson', 'mike.johnson@webuild.com', '$2y$10$7P6o/wLPujVdOKbwZUaD0uMy2DF3qBPv3ks0I15LliGMSHavO6Vme', 'Inventory Auditor', 'Active', '2025-12-15 17:55:08', '2025-12-15 17:55:08');
INSERT INTO `users` VALUES ('6', 'procurement_officer', 'Sarah Wilson', 'sarah.wilson@webuild.com', '$2y$10$NewBbLTQCtx59n7W3uxYCOpMVHygVK5OFuNVZTGaT59lVCFbG7gZK', 'Procurement Officer', 'Active', '2025-12-15 17:55:08', '2025-12-15 17:55:08');
INSERT INTO `users` VALUES ('7', 'accounts_payable', 'David Brown', 'david.brown@webuild.com', '$2y$10$hiHGKWyCu0hjqkEzhSy7pusvHGHocBuGwXOva3RIS5Wq9bW67RZye', 'Accounts Payable Clerk', 'Active', '2025-12-15 17:55:09', '2025-12-15 17:55:09');
INSERT INTO `users` VALUES ('8', 'accounts_receivable', 'Lisa Davis', 'lisa.davis@webuild.com', '$2y$10$psDjAW9DPLDucRnMz4A3f.q5IpGCdWxqjmc2GEQKSl2nqI0zSPo1a', 'Accounts Receivable Clerk', 'Active', '2025-12-15 17:55:09', '2025-12-15 17:55:09');
INSERT INTO `users` VALUES ('9', 'it_admin', 'Robert Garcia', 'robert.garcia@webuild.com', '$2y$10$AWvE1ALYnut.vw.aenBmdOnXJQRuzsZvrX18/U.uyNDaJG7WeOu8e', 'IT Administrator', 'Active', '2025-12-15 17:55:09', '2025-12-15 17:55:09');
INSERT INTO `users` VALUES ('10', 'top_management', 'Emily Martinez', 'emily.martinez@webuild.com', '$2y$10$Dnzy0qyXaluiZ5mWJ0ag0eeRiYSoVEUonXniNVhzJrqEGuErQYhkC', 'Top Management', 'Active', '2025-12-15 17:55:09', '2025-12-15 17:55:09');



-- Table structure for `vendor_invoices`
DROP TABLE IF EXISTS `vendor_invoices`;
CREATE TABLE `vendor_invoices` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) unsigned NOT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','Paid') NOT NULL DEFAULT 'Pending',
  `approved_by` varchar(255) DEFAULT NULL,
  `approved_date` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_number` (`invoice_number`),
  KEY `vendor_id` (`vendor_id`),
  KEY `status` (`status`),
  CONSTRAINT `vendor_invoices_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for table `vendor_invoices`
INSERT INTO `vendor_invoices` VALUES ('1', '1', 'VINV-202511-0001', '2025-12-05', '2026-01-04', '45500.00', 'Construction materials - cement, steel bars, and gravel', 'Pending', NULL, NULL, '2025-12-15 17:55:09', '2025-12-15 17:55:09');
INSERT INTO `vendor_invoices` VALUES ('2', '2', 'VINV-202511-0002', '2025-12-07', '2026-01-21', '32800.00', 'Hardware supplies - screws, nails, and power tools', 'Approved', 'accounts_payable', '2025-12-13 17:55:09', '2025-12-15 17:55:09', '2025-12-15 17:55:09');
INSERT INTO `vendor_invoices` VALUES ('3', '3', 'VINV-202511-0003', '2025-11-30', '2025-12-10', '67200.00', 'Heavy equipment rental for 3 months', 'Approved', 'admin', '2025-12-05 17:55:09', '2025-12-15 17:55:09', '2025-12-15 17:55:09');
INSERT INTO `vendor_invoices` VALUES ('4', '4', 'VINV-202511-0004', '2025-12-10', '2026-02-08', '125000.00', 'Premium steel beams and columns', 'Pending', NULL, NULL, '2025-12-15 17:55:09', '2025-12-15 17:55:09');
INSERT INTO `vendor_invoices` VALUES ('5', '5', 'VINV-202511-0005', '2025-12-12', '2026-01-11', '28900.00', 'Plumbing and electrical materials', 'Approved', 'accounts_payable', '2025-12-14 17:55:09', '2025-12-15 17:55:09', '2025-12-15 17:55:09');
INSERT INTO `vendor_invoices` VALUES ('6', '1', 'VINV-202511-0006', '2025-11-25', '2025-12-05', '52000.00', 'Concrete mix and aggregates', 'Paid', 'admin', '2025-11-27 17:55:09', '2025-12-15 17:55:09', '2025-12-15 17:55:09');



-- Table structure for `vendor_payments`
DROP TABLE IF EXISTS `vendor_payments`;
CREATE TABLE `vendor_payments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) unsigned NOT NULL,
  `payment_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `reference_number` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `processed_by` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`),
  KEY `payment_date` (`payment_date`),
  CONSTRAINT `vendor_payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `vendor_invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for table `vendor_payments`
INSERT INTO `vendor_payments` VALUES ('1', '6', '2025-12-07', '52000.00', 'Bank Transfer', 'BT-2025110301', 'Full payment via BDO', 'accounts_payable', '2025-12-15 17:55:09', '2025-12-15 17:55:09');



-- Table structure for `vendors`
DROP TABLE IF EXISTS `vendors`;
CREATE TABLE `vendors` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `vendor_name` varchar(255) NOT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `tax_id` varchar(100) DEFAULT NULL,
  `payment_terms` varchar(100) NOT NULL DEFAULT 'Net 30',
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vendor_name` (`vendor_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for table `vendors`
INSERT INTO `vendors` VALUES ('1', 'ABC Construction Supplies', 'John Martinez', 'john@abcconstruction.com', '02-1234-5678', '123 Builder St., Makati City', 'TIN-123-456-789', 'Net 30', 'Active', '2025-12-15 17:55:09', '2025-12-15 17:55:09');
INSERT INTO `vendors` VALUES ('2', 'XYZ Hardware Corp', 'Maria Santos', 'maria@xyzhardware.com', '02-9876-5432', '456 Industrial Ave., Quezon City', 'TIN-987-654-321', 'Net 45', 'Active', '2025-12-15 17:55:09', '2025-12-15 17:55:09');
INSERT INTO `vendors` VALUES ('3', 'Global Tools & Equipment', 'Roberto Cruz', 'roberto@globaltools.com', '02-5555-1234', '789 Equipment Rd., Pasig City', 'TIN-555-111-222', 'Net 30', 'Active', '2025-12-15 17:55:09', '2025-12-15 17:55:09');
INSERT INTO `vendors` VALUES ('4', 'Prime Steel Supplies', 'Ana Reyes', 'ana@primesteel.com', '02-7777-9999', '321 Steel Plaza, Mandaluyong City', 'TIN-777-888-999', 'Net 60', 'Active', '2025-12-15 17:55:09', '2025-12-15 17:55:09');
INSERT INTO `vendors` VALUES ('5', 'BuildPro Materials Inc', 'Carlos Mendoza', 'carlos@buildpro.com', '02-3333-4444', '567 Materials Way, Taguig City', 'TIN-333-444-555', 'Net 30', 'Active', '2025-12-15 17:55:09', '2025-12-15 17:55:09');



-- Table structure for `warehouse_inventory`
DROP TABLE IF EXISTS `warehouse_inventory`;
CREATE TABLE `warehouse_inventory` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `warehouse_id` int(11) NOT NULL COMMENT 'Reference to warehouses table',
  `inventory_id` int(11) NOT NULL COMMENT 'Reference to inventory table',
  `quantity` int(11) NOT NULL DEFAULT 0 COMMENT 'Current quantity at this warehouse',
  `rack_location` varchar(100) DEFAULT NULL COMMENT 'Specific rack/location within warehouse',
  `bin_number` varchar(50) DEFAULT NULL COMMENT 'Bin/shelf identifier',
  `reserved_quantity` int(11) NOT NULL DEFAULT 0 COMMENT 'Quantity reserved for pending orders',
  `available_quantity` int(11) NOT NULL DEFAULT 0 COMMENT 'Quantity available for new orders (quantity - reserved)',
  `last_counted_at` datetime DEFAULT NULL COMMENT 'Last physical inventory count date',
  `last_adjusted_by` int(11) DEFAULT NULL COMMENT 'User who last adjusted quantity',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `barcode_number` varchar(100) DEFAULT NULL COMMENT 'Barcode number (EAN/UPC)',
  `qr_code_data` varchar(500) DEFAULT NULL COMMENT 'QR code encoded data',
  `barcode_generated_at` datetime DEFAULT NULL COMMENT 'When barcode was generated',
  `barcode_enabled` tinyint(1) DEFAULT 1 COMMENT 'Whether barcode scanning is enabled',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_warehouse_inventory` (`warehouse_id`,`inventory_id`),
  UNIQUE KEY `barcode_number` (`barcode_number`),
  KEY `warehouse_id` (`warehouse_id`),
  KEY `inventory_id` (`inventory_id`),
  KEY `quantity` (`quantity`),
  KEY `available_quantity` (`available_quantity`),
  KEY `last_counted_at` (`last_counted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



-- Table structure for `warehouses`
DROP TABLE IF EXISTS `warehouses`;
CREATE TABLE `warehouses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `warehouse_name` varchar(255) NOT NULL COMMENT 'Name of the warehouse',
  `warehouse_code` varchar(50) NOT NULL COMMENT 'Unique warehouse code',
  `location` varchar(255) NOT NULL COMMENT 'City/Region location',
  `address` text DEFAULT NULL COMMENT 'Full address',
  `capacity` decimal(10,2) DEFAULT NULL COMMENT 'Warehouse capacity in square meters',
  `manager_id` int(11) DEFAULT NULL COMMENT 'Warehouse manager user ID',
  `status` enum('Active','Inactive','Maintenance') NOT NULL DEFAULT 'Active' COMMENT 'Warehouse operational status',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `warehouse_code` (`warehouse_code`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for table `warehouses`
INSERT INTO `warehouses` VALUES ('1', 'Main Warehouse', 'WH-001', 'Manila', '123 Main Street, Manila, Philippines', '5000.00', NULL, 'Active', '2025-12-15 05:10:58', '2025-12-15 05:10:58');
INSERT INTO `warehouses` VALUES ('2', 'North Distribution Center', 'WH-002', 'Quezon City', '456 North Ave, Quezon City, Philippines', '3000.00', NULL, 'Active', '2025-12-15 05:10:58', '2025-12-15 05:10:58');
INSERT INTO `warehouses` VALUES ('3', 'South Warehouse', 'WH-003', 'Makati', '789 South St, Makati, Philippines', '2500.00', NULL, 'Active', '2025-12-15 05:10:58', '2025-12-15 05:10:58');
INSERT INTO `warehouses` VALUES ('4', 'Main Warehouse Manila', 'WH-MNL-001', 'Manila', '1234 Industrial Avenue, Port Area, Manila, 1018 Philippines', '10000.00', '3', 'Active', '2025-12-15 05:40:22', '2025-12-15 05:40:22');
INSERT INTO `warehouses` VALUES ('5', 'North Distribution Center', 'WH-QC-002', 'Quezon City', '456 Commonwealth Avenue, Quezon City, 1121 Philippines', '8000.00', '3', 'Active', '2025-12-15 05:40:22', '2025-12-15 05:40:22');
INSERT INTO `warehouses` VALUES ('6', 'South Logistics Hub', 'WH-MAK-003', 'Makati', '789 EDSA, Makati City, 1200 Philippines', '6000.00', '4', 'Active', '2025-12-15 05:40:22', '2025-12-15 05:40:22');
INSERT INTO `warehouses` VALUES ('7', 'East Storage Facility', 'WH-PAS-004', 'Pasig', '321 C5 Road, Pasig City, 1600 Philippines', '5000.00', NULL, 'Maintenance', '2025-12-15 05:40:22', '2025-12-15 05:40:22');

SET FOREIGN_KEY_CHECKS=1;
