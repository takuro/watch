CREATE TABLE sessions (
  id INTEGER PRIMARY KEY AUTOINCREMENT, 
  users_id INTEGER,
  session_id TEXT UNIQUE
);

create index idx_sessions_users_id on sessions(users_id);
create index idx_sessions_session_id on sessions(session_id);
