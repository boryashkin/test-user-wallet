WITH latest_currency_rate AS (
    SELECT id, currency_id, rate, to_currency_id, created_at, ROW_NUMBER() OVER (PARTITION BY currency_id ORDER BY created_at DESC) AS rn
    FROM currency_rate WHERE created_at >= (select max(created_at) from currency_rate)
)
SELECT
    sum(wt.value * ifnull(rate, 1))
FROM wallet_transaction wt
    left join latest_currency_rate lcr ON lcr.currency_id = wt.currency_id AND lcr.to_currency_id = :target_currency
WHERE reason_id = (select id from wallet_transaction_reason WHERE name = :reason_name)
;