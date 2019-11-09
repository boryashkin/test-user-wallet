create table currency_rate (
    id integer primary key auto_increment,
    currency_id integer not null ,
    to_currency_id integer not null,
    rate decimal(24,14) not null,
    created_at timestamp not null DEFAULT NOW(),
    FOREIGN KEY(currency_id) REFERENCES currency(id),
    FOREIGN KEY(to_currency_id) REFERENCES currency(id),
    INDEX (created_at,currency_id)
)

