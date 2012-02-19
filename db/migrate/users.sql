CREATE TABLE users (
  id INTEGER PRIMARY KEY AUTOINCREMENT, 
  uname TEXT UNIQUE,
  password TEXT,
  salt TEXT,
  created_at TEXT
);

create index idx_users_uname on users(uname);
