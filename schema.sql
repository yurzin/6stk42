-- ============================================================
--  Schema: myapp
-- ============================================================

CREATE DATABASE IF NOT EXISTS myapp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE myapp;

-- Users (referenced by all content tables)
CREATE TABLE IF NOT EXISTS users (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    email      VARCHAR(255) NOT NULL UNIQUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Photos
CREATE TABLE IF NOT EXISTS photos (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(255) NOT NULL,
    description TEXT,
    url         VARCHAR(500) NOT NULL,
    thumbnail   VARCHAR(500),
    width       INT UNSIGNED,
    height      INT UNSIGNED,
    size        INT UNSIGNED COMMENT 'bytes',
    author_id   INT UNSIGNED,
    tags        JSON,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at  DATETIME DEFAULT NULL,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Videos
CREATE TABLE IF NOT EXISTS videos (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(255) NOT NULL,
    description TEXT,
    url         VARCHAR(500) NOT NULL,
    thumbnail   VARCHAR(500),
    duration    INT UNSIGNED COMMENT 'seconds',
    resolution  VARCHAR(20),
    size        BIGINT UNSIGNED COMMENT 'bytes',
    author_id   INT UNSIGNED,
    tags        JSON,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at  DATETIME DEFAULT NULL,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Notes
CREATE TABLE IF NOT EXISTS notes (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(255) NOT NULL,
    content     TEXT NOT NULL,
    color       VARCHAR(7) DEFAULT '#ffffff',
    is_pinned   TINYINT(1) DEFAULT 0,
    author_id   INT UNSIGNED,
    tags        JSON,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at  DATETIME DEFAULT NULL,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL
);

-- ---- Seed data -----------------------------------------------------------

INSERT INTO users (name, email) VALUES
    ('Alice Doe', 'alice@example.com'),
    ('Bob Smith', 'bob@example.com');

INSERT INTO photos (title, description, url, thumbnail, width, height, author_id, tags) VALUES
    ('Mountain Sunrise', 'Early morning in the Alps', '/uploads/photos/mountain.jpg', '/uploads/photos/thumbs/mountain.jpg', 4000, 3000, 1, '["nature","mountains","travel"]'),
    ('City Lights', 'New York at night', '/uploads/photos/city.jpg', '/uploads/photos/thumbs/city.jpg', 5000, 3333, 2, '["city","night","urban"]'),
    ('Ocean Waves', 'Pacific coast storm', '/uploads/photos/ocean.jpg', '/uploads/photos/thumbs/ocean.jpg', 3840, 2160, 1, '["ocean","nature","storm"]');

INSERT INTO videos (title, description, url, thumbnail, duration, resolution, author_id, tags) VALUES
    ('Vue 3 Tutorial', 'Composable API deep dive', '/uploads/videos/vue3.mp4', '/uploads/videos/thumbs/vue3.jpg', 3720, '1920x1080', 1, '["vue","javascript","tutorial"]'),
    ('Alpine Hike Vlog', 'Three days in the Swiss Alps', '/uploads/videos/hike.mp4', '/uploads/videos/thumbs/hike.jpg', 1845, '3840x2160', 2, '["travel","hiking","nature"]');

INSERT INTO notes (title, content, color, is_pinned, author_id, tags) VALUES
    ('Project Ideas', '<p>1. Build a media gallery\n2. Create REST API\n3. Deploy on VPS</p>', '#fef9c3', 1, 1, '["ideas","dev"]'),
    ('Meeting Notes', '<p>Discussed API design, agreed on JSON:API format for v2.</p>', '#dbeafe', 0, 2, '["meetings","api"]'),
    ('Reading List', '<p>- Clean Code\n- The Pragmatic Programmer\n- SICP</p>', '#dcfce7', 0, 1, '["books","learning"]');
