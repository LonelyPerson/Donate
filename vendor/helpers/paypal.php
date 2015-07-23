<?php

if ( ! Settings::get('app.paypal.enabled')) exit('Paypal disabled');

$itemNumber = (isset($_POST['item_number']) && ! empty($_POST['item_number'])) ? $_POST['item_number'] : false;

if ( ! $itemNumber)
    exit('paypal error #1');

$result = DB::first("SELECT * FROM paypal WHERE item_number = ?", [$itemNumber]);
if ( ! isset($result->item_number))
    exit('paypal error #2');

$verified = Paypal::verify();

if ($verified) {
    $result = DB::first("SELECT * FROM paypal WHERE item_number = ?", [$itemNumber]);

    if (isset($result->item_number)) {
        if ($_POST['mc_gross'] != $result->amount) {
            DB::query("UPDATE paypal SET status = 'error #3' WHERE item_number = ?", [$itemNumber]);
            exit(0);
        }

        $txnData = DB::first("SELECT * FROM paypal WHERE txn_id = ?", [$_POST['txn_id']]);
        if (isset($txnData->item_number)) {
            DB::query("UPDATE paypal SET status = 'error #4' WHERE item_number = ?", [$itemNumber]);
            exit(0);
        }

        $buyerInfo = json_encode($_POST);

        $userData = DB::first("SELECT * FROM users WHERE id = ?", [$result->user_id]);
        $newPoints = $userData->balance + $result->points;

        DB::query("UPDATE users SET balance = :balance WHERE id = :id", [':balance' => $newPoints, ':id' => $userData->id]);
        DB::query("UPDATE paypal SET
                status = :status,
                txn_id = :txn_id,
                buyer_info = :buyer_info,
                end_date = :end_date
                WHERE item_number = :item_number", [
                    ':status' => 'ok',
                    ':txn_id' => $_POST['txn_id'],
                    ':buyer_info' => $buyerInfo,
                    ':end_date' => date('Y-m-d H:i:s'),
                    ':item_number' => $itemNumber
                ]);

        Histuar::add(Language::_('Balansas'), Language::_('Papildytas balansas per paypal sistemą. Papildymo suma: %s', [$result->points]), $userData->id);

        exit ('ok');
    }
}
