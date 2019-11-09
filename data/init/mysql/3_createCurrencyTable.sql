create table currency (
    id integer primary key AUTO_INCREMENT,
    code char(3),
    created_at timestamp DEFAULT NOW(),
    UNIQUE INDEX (code)
)
