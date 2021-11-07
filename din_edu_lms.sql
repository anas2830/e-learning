-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 23, 2020 at 10:48 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `din_edu_lms`
--

-- --------------------------------------------------------

--
-- Table structure for table `edu_assign_courses`
--

CREATE TABLE `edu_assign_courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` int(11) NOT NULL COMMENT 'FK = edu_courses.id',
  `course_type` tinyint(4) NOT NULL COMMENT '1=Online, 0=Offline',
  `paid_status` tinyint(4) NOT NULL COMMENT '1=Paid, 0=Free',
  `price` double(8,2) NOT NULL,
  `duration` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `assign_teacher_id` int(11) NOT NULL COMMENT 'FK = edu_teachers.id',
  `publish_status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=Unpublish, 1=Publish',
  `social_share` tinyint(4) NOT NULL DEFAULT 1 COMMENT '0=No, 1=Yes',
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `valid` tinyint(4) NOT NULL COMMENT '1=Yes, 0=No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `edu_assign_course_schedules`
--

CREATE TABLE `edu_assign_course_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `assign_course_id` int(11) NOT NULL COMMENT 'FK = edu_assign_courses.id',
  `batch_no` int(11) NOT NULL COMMENT 'Batch No',
  `schedule_type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=Live, 0=Offline',
  `schedule_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `start_time` time NOT NULL,
  `start_url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `valid` tinyint(4) NOT NULL COMMENT '1=Yes, 0=No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `edu_company_work_flows`
--

CREATE TABLE `edu_company_work_flows` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '1=Active, 0=Deactive',
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `valid` tinyint(4) NOT NULL COMMENT '1=Yes, 0=No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `edu_contact_us`
--

CREATE TABLE `edu_contact_us` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `valid` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=Yes, 0=No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `edu_courses`
--

CREATE TABLE `edu_courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `course_thumb` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `course_overview` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `valid` tinyint(4) NOT NULL COMMENT '1=Yes, 0=No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `edu_course_enrolls`
--

CREATE TABLE `edu_course_enrolls` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `assign_course_id` int(11) NOT NULL COMMENT 'FK = edu_assign_courses.id',
  `trainee_id` int(11) NOT NULL COMMENT 'FK = users.id',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `valid` tinyint(4) NOT NULL COMMENT '1=Yes, 0=No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `edu_photo_galleries`
--

CREATE TABLE `edu_photo_galleries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '1=Active, 0=Deactive',
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `valid` tinyint(4) NOT NULL COMMENT '1=Yes, 0=No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `edu_provider_users`
--

CREATE TABLE `edu_provider_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified` tinyint(4) NOT NULL COMMENT '1=Yes, 0=No',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `valid` tinyint(4) NOT NULL COMMENT '1=Yes, 0=No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `edu_provider_users`
--

INSERT INTO `edu_provider_users` (`id`, `name`, `email`, `email_verified`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `deleted_at`, `status`, `valid`) VALUES
(1, 'Md.Provider', 'provider@gmail.com', 1, NULL, '$2y$10$QA2tpIzuEbbRf//TxfSj.OkRg4.4k/PiLI8TE2IpNBqJuC9A9nZH6', NULL, '2020-10-14 11:38:27', '2020-10-14 11:38:27', NULL, 'Active', 1);

-- --------------------------------------------------------

--
-- Table structure for table `edu_recent_videos`
--

CREATE TABLE `edu_recent_videos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `video_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `video_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '1=Active, 0=Deactive',
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `valid` tinyint(4) NOT NULL COMMENT '1=Yes, 0=No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `edu_students`
--

CREATE TABLE `edu_students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified` tinyint(4) NOT NULL COMMENT '1=Yes, 0=No',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `valid` tinyint(4) NOT NULL COMMENT '1=Yes, 0=No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `edu_students`
--

INSERT INTO `edu_students` (`id`, `name`, `email`, `email_verified`, `email_verified_at`, `password`, `phone`, `image`, `remember_token`, `created_by`, `created_at`, `updated_at`, `deleted_at`, `status`, `valid`) VALUES
(1, 'Md.Student', 'student@gmail.com', 1, NULL, '$2y$10$QA2tpIzuEbbRf//TxfSj.OkRg4.4k/PiLI8TE2IpNBqJuC9A9nZH6', NULL, NULL, NULL, NULL, '2020-10-14 11:38:27', '2020-10-14 11:38:27', NULL, 'Active', 1);

-- --------------------------------------------------------

--
-- Table structure for table `edu_student_reviews`
--

CREATE TABLE `edu_student_reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `review` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '1=Active, 0=Deactive',
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `valid` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=Yes, 0=No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `edu_supports`
--

CREATE TABLE `edu_supports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified` tinyint(4) NOT NULL COMMENT '1=Yes, 0=No',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `valid` tinyint(4) NOT NULL COMMENT '1=Yes, 0=No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `edu_supports`
--

INSERT INTO `edu_supports` (`id`, `name`, `email`, `email_verified`, `email_verified_at`, `password`, `phone`, `image`, `remember_token`, `created_by`, `created_at`, `updated_at`, `deleted_at`, `status`, `valid`) VALUES
(1, 'Md.Support', 'support@gmail.com', 1, NULL, '$2y$10$QA2tpIzuEbbRf//TxfSj.OkRg4.4k/PiLI8TE2IpNBqJuC9A9nZH6', NULL, NULL, NULL, NULL, '2020-10-14 11:38:27', '2020-10-14 11:38:27', NULL, 'Active', 1);

-- --------------------------------------------------------

--
-- Table structure for table `edu_teachers`
--

CREATE TABLE `edu_teachers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified` tinyint(4) NOT NULL COMMENT '1=Yes, 0=No',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `valid` tinyint(4) NOT NULL COMMENT '1=Yes, 0=No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `edu_teachers`
--

INSERT INTO `edu_teachers` (`id`, `name`, `email`, `email_verified`, `email_verified_at`, `password`, `phone`, `image`, `remember_token`, `created_by`, `created_at`, `updated_at`, `deleted_at`, `status`, `valid`) VALUES
(1, 'Md.Teacher', 'teacher@gmail.com', 1, NULL, '$2y$10$QA2tpIzuEbbRf//TxfSj.OkRg4.4k/PiLI8TE2IpNBqJuC9A9nZH6', NULL, NULL, NULL, NULL, '2020-10-14 11:38:27', '2020-10-14 11:38:27', NULL, 'Active', 1);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2020_10_14_150726_create_edu_provider_users_table', 1),
(5, '2020_10_21_141856_create_edu_student_reviews_table', 1),
(6, '2020_10_21_144006_create_edu_recent_videos_table', 1),
(7, '2020_10_21_144841_create_edu_photo_galleries_table', 1),
(8, '2020_10_21_145340_create_edu_company_work_flows_table', 1),
(9, '2020_10_21_145739_create_edu_courses_table', 1),
(10, '2020_10_21_145803_create_edu_assign_courses_table', 1),
(11, '2020_10_21_145855_create_edu_assign_course_schedules_table', 1),
(12, '2020_10_21_145949_create_edu_course_enrolls_table', 1),
(13, '2020_10_21_150049_create_edu_teachers_table', 1),
(14, '2020_10_29_175735_create_edu_contact_us_table', 1),
(15, '2020_11_23_204734_create_edu_students_table', 2),
(16, '2020_11_23_204825_create_edu_supports_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('rafikulswe@gmail.com', '$2y$10$dlMojcq.Kg9UTxddiKJSieidGqh9Uyq.ag0CaF.z74r.zj3J0Zg96', '2020-11-23 15:30:58');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `valid` tinyint(4) NOT NULL COMMENT '1=Yes, 0=No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `image`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `deleted_at`, `valid`) VALUES
(1, 'Md.Student', 'rafikulswe@gmail.com', '01729346959', NULL, NULL, '$2y$10$QA2tpIzuEbbRf//TxfSj.OkRg4.4k/PiLI8TE2IpNBqJuC9A9nZH6', 'mPai4rhXorxFiHtzKRWq5aIjdJT988WOaBvWytdX9d8EnQnfOBGM5zC5sQlo', NULL, NULL, NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `edu_assign_courses`
--
ALTER TABLE `edu_assign_courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `edu_assign_course_schedules`
--
ALTER TABLE `edu_assign_course_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `edu_company_work_flows`
--
ALTER TABLE `edu_company_work_flows`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `edu_contact_us`
--
ALTER TABLE `edu_contact_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `edu_courses`
--
ALTER TABLE `edu_courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `edu_course_enrolls`
--
ALTER TABLE `edu_course_enrolls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `edu_photo_galleries`
--
ALTER TABLE `edu_photo_galleries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `edu_provider_users`
--
ALTER TABLE `edu_provider_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `edu_provider_users_email_unique` (`email`);

--
-- Indexes for table `edu_recent_videos`
--
ALTER TABLE `edu_recent_videos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `edu_students`
--
ALTER TABLE `edu_students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `edu_student_reviews`
--
ALTER TABLE `edu_student_reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `edu_supports`
--
ALTER TABLE `edu_supports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `edu_teachers`
--
ALTER TABLE `edu_teachers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `edu_assign_courses`
--
ALTER TABLE `edu_assign_courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `edu_assign_course_schedules`
--
ALTER TABLE `edu_assign_course_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `edu_company_work_flows`
--
ALTER TABLE `edu_company_work_flows`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `edu_contact_us`
--
ALTER TABLE `edu_contact_us`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `edu_courses`
--
ALTER TABLE `edu_courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `edu_course_enrolls`
--
ALTER TABLE `edu_course_enrolls`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `edu_photo_galleries`
--
ALTER TABLE `edu_photo_galleries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `edu_provider_users`
--
ALTER TABLE `edu_provider_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `edu_recent_videos`
--
ALTER TABLE `edu_recent_videos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `edu_students`
--
ALTER TABLE `edu_students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `edu_student_reviews`
--
ALTER TABLE `edu_student_reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `edu_supports`
--
ALTER TABLE `edu_supports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `edu_teachers`
--
ALTER TABLE `edu_teachers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
