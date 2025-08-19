-- ============================================================================
-- Core
-- ============================================================================
CREATE TABLE users (
  user_id TEXT UNIQUE,
  hash TEXT,
  PRIMARY KEY (user_id)
);

-- ============================================================================
-- Journal
-- ============================================================================
CREATE TABLE posts (
  post_id TEXT UNIQUE,
  title TEXT,
  content TEXT,
  summary TEXT,
  timestamp INTEGER,
  modified_timestamp INTEGER,
  published INTEGER DEFAULT 0,
  tags TEXT,
  PRIMARY KEY (post_id)
);

-- ============================================================================
-- Gallery
-- ============================================================================
CREATE TABLE albums (
  album_id TEXT UNIQUE,
  name TEXT,
  description TEXT,
  PRIMARY KEY (album_id)
);

CREATE TABLE images (
  image_id INTEGER UNIQUE,
  title TEXT NOT NULL,
  filename TEXT NOT NULL,
  description TEXT,
  timestamp INTEGER,
  width INTEGER DEFAULT 0,
  height INTEGER DEFAULT 0,
  PRIMARY KEY (image_id)
);

CREATE TABLE image_albums (
  image_id INTEGER NOT NULL,
  album_id TEXT NOT NULL,
  FOREIGN KEY (image_id) REFERENCES images(image_id) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (album_id) REFERENCES albums(album_id) ON UPDATE CASCADE ON DELETE CASCADE
);
