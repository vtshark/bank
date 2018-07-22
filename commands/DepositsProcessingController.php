<?php

namespace app\commands;

use app\models\Transactions;
use app\models\TransactionTypes;
use Yii;
use app\models\Deposits;
use yii\base\Module;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * Class CommissionController
 * @package app\commands
  */
class DepositsProcessingController extends Controller
{
    const TRANSACTION_COMMISSION = "commission";
    const TRANSACTION_PROFIT = "profit";
    private $currentTime;

    public function __construct(string $id, Module $module, array $config = [])
    {
        $this->currentTime = time();
        parent::__construct($id, $module, $config);
    }

    public function actionIndex() {

        $deposits = Deposits::find()->all();

        /* @var $deposit Deposits */
        foreach ($deposits as $deposit) {

            $this->profitHandler($deposit);
            $this->commissionHandler($deposit);

        }
        echo "\n" . count($deposits) . " deposits processed.";
        return ExitCode::OK;
    }

    /**
     * @param Deposits $deposit
     * @return bool|float|int
     * @throws \Throwable
     * @throws \yii\db\Exception
     */
    private function commissionHandler(Deposits $deposit) {

        $currentTime = $this->currentTime;
        // если не первый день месяца коммисию не высчитываем
        if ((int)date("d", $currentTime) != 1) {
            return false;
        }

        // коэфициент
        $ratio = 1;

        // проверка месяца создания депозита
        // если совпадает год создания депозита
        if (date("Y", $currentTime) == date("Y", $deposit->created_at)) {
            $currentMonth = date("m", $currentTime);
            $createMonth = (int)date("m", $deposit->created_at);

            if ($createMonth == $currentMonth - 1) {

                // определение количества дней до окончания месяца после создания депозита
                $diffDays = floor(($currentTime - $deposit->created_at)/86400);
                // количество дне в месяце
                $countDaysMonth = cal_days_in_month(CAL_GREGORIAN,
                    (int)date("m", $deposit->created_at),
                    (int)date("Y", $deposit->created_at));
                $ratio = $diffDays / $countDaysMonth;
            }
        }

        $amountCommission = 0;
        $amount = $deposit->amount;
        switch ($amount) {

            // Комиссия 5%, но не менее чем 50 у.е.
            case ($amount >= 0 && $amount < 1000):
                $amountCommission = ($amount/100*5);
                if ($amountCommission < 50) {
                    $amountCommission = 50;
                }
                break;

            // Комиссия 6%
            case ($amount >= 1000 && $amount < 10000):
                $amountCommission = ($amount/100*6);
                break;

            // Комиссия 7%, но не более чем 5000 у.е.
            case ($amount >= 10000):
                $amountCommission = ($amount/100*7);
                if ($amountCommission > 5000) {
                    $amountCommission = 5000;
                }
                break;
        }

        $amountCommission *= $ratio;
        $deposit->amount -= $amountCommission;
        $this->saveTransaction(self::TRANSACTION_COMMISSION, $amountCommission, $deposit);

        return $amountCommission;
    }

    /**
     * @param Deposits $deposit
     * @return float|int
     * @throws \Throwable
     * @throws \yii\db\Exception
     */
    private function profitHandler(Deposits $deposit) {
        $amountProfit = 0;
        if (date("d", $this->currentTime) == date("d", $deposit->created_at)) {
            $amountProfit = ($deposit->amount/100 * $deposit->rate);
            $deposit->amount += $amountProfit;
            $this->saveTransaction(self::TRANSACTION_PROFIT, $amountProfit, $deposit);
        }
        return $amountProfit;
    }

    /**
     * @param $transaction_type
     * @param $amount
     * @param Deposits $deposit
     * @throws \Throwable
     * @throws \yii\db\Exception
     */
    private function saveTransaction($transaction_type, $amount, Deposits $deposit) {
        /* @var $transaction_type \app\models\TransactionTypes */
        $transaction_type = TransactionTypes::find()
            ->where(['alias' => $transaction_type])->limit(1)->one();
        $db = Yii::$app->db;
        $outerTransaction = $db->beginTransaction();
        try {
            $transaction = new Transactions();
            $transaction->amount = $amount;
            $transaction->deposit_id = $deposit->id;
            $transaction->transaction_type_id = $transaction_type->id;
            $transaction->save();
            $deposit->save();
            $outerTransaction->commit();
        } catch (\Exception $e) {
            $outerTransaction->rollBack();
        } catch (\Throwable $e) {
            $outerTransaction->rollBack();
            throw $e;
        }
    }

}