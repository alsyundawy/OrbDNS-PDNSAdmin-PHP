CREATE TABLE IF NOT EXISTS activity_logs (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id INT UNSIGNED DEFAULT NULL,
  action VARCHAR(64) NOT NULL,
  target VARCHAR(255) NOT NULL DEFAULT '',
  detail TEXT DEFAULT NULL,
  ip_address VARCHAR(45) NOT NULL DEFAULT '',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_user_id (user_id),
  KEY idx_action (action),
  KEY idx_created_at (created_at),
  CONSTRAINT fk_al_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
