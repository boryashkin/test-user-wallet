<?php
namespace app\repositories;

use app\models\User;
use app\models\Wallet;

class UserRepository extends MysqlRepository
{
    public static $modelClass = User::class;
    public static $table = 'user';

    public function createOne(User $user)
    {
        $stmt = $this->getConnection()->prepare('INSERT INTO ' . self::$table . ' (login) VALUES (:login)');
        $stmt->bindValue(':login', $user->login);

        return $stmt->execute() ? $this->getConnection()->lastInsertId() : 0;
    }

    public function createOneWithWallet(User $user, Wallet $wallet, WalletRepository $repository)
    {
        $this->getConnection()->beginTransaction();
        $walletId = null;
        if ($userId = $this->createOne($user)) {
            $wallet->user_id = $userId;
            $walletId = $repository->createOne($wallet);
        }
        if (!$walletId) {
            $this->getConnection()->rollBack();
        } else {
            $this->getConnection()->commit();
        }

        return (bool)$walletId;
    }

    public function findAllWithWallet()
    {
        $stmt = $this->getConnection()
            ->query(
                'SELECT user.*, wallet.id as wallet_id, wallet.value as balance, '
                . ' wallet.currency_id as currency_id FROM user INNER JOIN wallet ON wallet.user_id = user.id'
            );
        $stmt->execute();

        $models = [];
        while ($model = $stmt->fetchObject(User::class)) {
            $models[$model->id] = $model;
        }

        return $models;
    }
}
