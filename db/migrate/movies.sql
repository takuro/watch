CREATE TABLE movies (
  id INTEGER PRIMARY KEY AUTOINCREMENT, 
  path TEXT,
  ext TEXT,
  title TEXT,
  description TEXT,
  year INTEGER,
  genre INTEGER,
  part_number INTEGER,
  series INTEGER,
  is_exist INTEGER,
  updated_at TEXT,
  created_at TEXT
);

create index idx_movies_path on movies(path);
create index idx_movies_is_exist on movies(is_exist);
