create table wallet_transaction_reason (
    id integer primary key AUTO_INCREMENT,
    name varchar(255) not null,
    created_at timestamp not null DEFAULT NOW()
)
