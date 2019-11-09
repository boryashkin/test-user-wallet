create table user (
    id integer primary key AUTO_INCREMENT,
    login varchar(30) not null,
    created_at timestamp not null DEFAULT NOW(),
    UNIQUE INDEX (login)
)
