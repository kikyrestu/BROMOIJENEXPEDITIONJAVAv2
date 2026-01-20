CREATE TABLE `users` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `uuid` uuid UNIQUE COMMENT 'Security: Prevent ID enumeration',
  `name` varchar(255),
  `email` varchar(255) UNIQUE,
  `password` varchar(255),
  `role` varchar(255) COMMENT 'admin, editor',
  `remember_token` varchar(255),
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE TABLE `pages` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `title` varchar(255),
  `slug` varchar(255) UNIQUE,
  `content` json COMMENT 'Stores Elementor-lite blocks (Section type, Data, Hotspots)',
  `status` varchar(255) DEFAULT 'draft' COMMENT 'draft, published',
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE TABLE `destinations` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255),
  `slug` varchar(255) UNIQUE,
  `description` text,
  `thumbnail_path` varchar(255),
  `is_featured` boolean DEFAULT false,
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE TABLE `packages` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `destination_id` bigint,
  `name` varchar(255),
  `slug` varchar(255) UNIQUE,
  `price_start_from` decimal(15,2),
  `duration_days` int,
  `duration_nights` int,
  `itinerary` json COMMENT 'Repeater data: Day, Activity, Description',
  `inclusions` text COMMENT 'Markdown or HTML for checklist',
  `exclusions` text,
  `is_exclusive` boolean DEFAULT false,
  `wa_template_message` text COMMENT 'Custom message per package for WA Inquiry',
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE TABLE `blogs` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `author_id` bigint,
  `category_id` bigint,
  `title` varchar(255),
  `slug` varchar(255) UNIQUE,
  `body` text,
  `thumbnail_path` varchar(255),
  `view_count` int DEFAULT 0,
  `published_at` timestamp,
  `created_at` timestamp
);

CREATE TABLE `categories` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255),
  `slug` varchar(255) UNIQUE,
  `type` varchar(255) COMMENT 'blog, package'
);

CREATE TABLE `galleries` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `title` varchar(255),
  `image_path` varchar(255),
  `category` varchar(255) COMMENT 'nature, group, transport, etc',
  `sort_order` int DEFAULT 0,
  `created_at` timestamp
);

CREATE TABLE `seo_metadata` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `seoable_id` bigint COMMENT 'Polymorphic ID',
  `seoable_type` varchar(255) COMMENT 'Model: Page, Package, or Blog',
  `meta_title` varchar(255),
  `meta_description` text,
  `meta_keywords` varchar(255),
  `og_image` varchar(255),
  `canonical_url` varchar(255),
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE TABLE `hotspots` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `page_id` bigint,
  `destination_id` bigint COMMENT 'Target link',
  `x_coordinate` decimal(5,2) COMMENT 'Percentage 0-100',
  `y_coordinate` decimal(5,2) COMMENT 'Percentage 0-100',
  `label_custom` varchar(255),
  `created_at` timestamp
);

CREATE TABLE `inquiry_logs` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `package_id` bigint,
  `ip_address` varchar(255),
  `user_agent` text,
  `utm_source` varchar(255),
  `utm_medium` varchar(255),
  `utm_campaign` varchar(255),
  `referer_url` text,
  `clicked_at` timestamp
);

CREATE TABLE `price_adjustments` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `package_id` bigint,
  `title` varchar(255),
  `start_date` date,
  `end_date` date,
  `adjustment_type` varchar(255) COMMENT 'percentage, fixed',
  `amount` decimal(15,2),
  `is_active` boolean DEFAULT true
);

CREATE TABLE `page_revisions` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `page_id` bigint,
  `content_snapshot` json,
  `created_by` bigint,
  `created_at` timestamp
);

CREATE TABLE `media_assets` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `file_path` varchar(255),
  `file_type` varchar(255),
  `alt_text` varchar(255),
  `title_text` varchar(255),
  `disk` varchar(255) DEFAULT 'public',
  `created_at` timestamp
);

CREATE TABLE `settings` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `key` varchar(255) UNIQUE,
  `value` text,
  `group` varchar(255) COMMENT 'general, social, contact, api',
  `updated_at` timestamp
);

ALTER TABLE `packages` ADD FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`);

ALTER TABLE `blogs` ADD FOREIGN KEY (`author_id`) REFERENCES `users` (`id`);

ALTER TABLE `blogs` ADD FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

ALTER TABLE `hotspots` ADD FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`);

ALTER TABLE `hotspots` ADD FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`);

ALTER TABLE `inquiry_logs` ADD FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`);

ALTER TABLE `price_adjustments` ADD FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`);

ALTER TABLE `page_revisions` ADD FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`);

ALTER TABLE `page_revisions` ADD FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);
