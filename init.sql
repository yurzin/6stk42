CREATE TABLE IF NOT EXISTS notes (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title      VARCHAR(255) NOT NULL,
    content    TEXT NOT NULL,
    color      VARCHAR(7) DEFAULT '#ffffff',
    is_pinned  TINYINT(1) DEFAULT 0,
    author_id  INT UNSIGNED,
    tags       JSON,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME DEFAULT NULL
);

INSERT IGNORE INTO photos (title, description, url, thumbnail, width, height, author_id, tags) VALUES
    ('Mountain Sunrise', 'Early morning in the Alps', '/uploads/photos/mountain.jpg', '/uploads/photos/thumbs/mountain.jpg', 4000, 3000, 1, '["nature","mountains"]'),
    ('City Lights', 'New York at night', '/uploads/photos/city.jpg', '/uploads/photos/thumbs/city.jpg', 5000, 3333, 2, '["city","night"]');

INSERT IGNORE INTO videos (title, description, url, thumbnail, duration, resolution, author_id, tags) VALUES
    ('Vue 3 Tutorial', 'Composable API deep dive', '/uploads/videos/vue3.mp4', '/uploads/videos/thumbs/vue3.jpg', 3720, '1920x1080', 1, '["vue","javascript"]'),
    ('Alpine Hike Vlog', 'Three days in the Swiss Alps', '/uploads/videos/hike.mp4', '/uploads/videos/thumbs/hike.jpg', 1845, '3840x2160', 2, '["travel","hiking"]');

INSERT INTO notes (title, content, color, is_pinned, author_id, tags) VALUES
    ('Project Ideas', '<p>Build a media gallery</p>', '#fef9c3', 1, 1, '["ideas","dev"]'),
    ('Meeting Notes', '<p>Discussed API design</p>', '#dbeafe', 0, 2, '["meetings"]'),
    ('Reading List', '<p>Clean Code, SICP</p>', '#dcfce7', 0, 1, '["books"]');
