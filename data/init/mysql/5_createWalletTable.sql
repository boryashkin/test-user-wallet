create table wallet (
    id integer primary key AUTO_INCREMENT,
    user_id integer not null,
    value decimal(15, 2) not null,
    currency_id integer not null,
    created_at timestamp not null DEFAULT NOW(),
    FOREIGN KEY (currency_id) REFERENCES currency(id),
    FOREIGN KEY (user_id) REFERENCES user(id),
    INDEX (user_id)
)
