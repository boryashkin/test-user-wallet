# Test wallet app

### Run the app

* create .env by copying .env.dist and replacing some values (will also work with .env.dist values)
    * `cp .env.dist .env`  
* start the app as `docker-compose -d`
* test if web-app works: http://localhost:9882/

### Api doc

* `GET /currencies` Currencies list
* `GET /users` Users list with wallet balance
* `POST /users` Create a user 
    * using `Content-Type: application/json`, send data as `{"wallet": {"value": 0, "currency_id": 1}, "user": {"login": "test31"}}`
    * or as usual post data (wallet[value]=0&wallet[currency_id]=1&user[login]=test1)
* `GET /wallets/{id}` Wallet info
* `POST /wallet-transactions`
    * `{"transaction": {"reason_id": 2, "value": -1, "currency_id": 1, "wallet_id": 1}}`
    * use negative value, if you need to withdraw money
* `GET /wallet-transaction-reasons`
    * dictionary of reasons
* `GET /analytic/wallet-transaction/sum-of-transactions`
    * get-parameters: `reason=refund&target_currency_id=1`