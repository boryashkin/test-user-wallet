create table wallet_transaction (
    id integer primary key AUTO_INCREMENT,
    wallet_id integer not null,
    currency_id integer not null,
    currency_rate_id integer,/* if currencies are the same - no currency_rate applies */
    value decimal(15, 2) not null,
    reason_id integer not null,
    created_at timestamp not null DEFAULT NOW(),
    FOREIGN KEY (currency_id) REFERENCES currency(id),
    FOREIGN KEY (currency_rate_id) REFERENCES currency_rate(id),
    FOREIGN KEY (wallet_id) REFERENCES wallet(id),
    FOREIGN KEY (reason_id) REFERENCES wallet_transaction_reason(id),
    INDEX (wallet_id,created_at)
)
