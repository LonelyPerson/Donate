<?php

class Config {
    public function get_index() {
        return View::make('settings');
    }

    public function post_save() {
        $email = Input::get('email');
        $oldPassword = Input::get('old_password');
        $newPassword = Input::get('new_password');
        $server = Session::get('active_server_id');
        $username = Session::get('server_account_login');
        $update = false;
        $emailVerify = false;
        $emailForm = '';

        // change email
        if ( ! empty($email)) {
            $emailValidationPattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';

            if (preg_match($emailValidationPattern, $email) !== 1)
                Output::json('Neteisingai sudarytas el. pašto adresas');

            $emailExists = DB::first('SELECT * FROM users WHERE email = ? AND id != ?', [$email, Session::get('donate_user_id')]);
            if ($emailExists)
                Output::json(Language::_('Toks el. pašto adresas jau naudojamas'));

            if (Auth::user()->email != $email) {
                while(true) {
                    $code = File::randomString(35);
                    $result = DB::first('SELECT * FROM email_verify WHERE code = ?', [$code]);
                    if ( ! $result)
                        break;
                }

                $result = DB::first('SELECT * FROM users WHERE id = :id', [
                    ':id' => Session::get('donate_user_id')
                ]);

                DB::query('UPDATE users SET email_status = :email_status, email = :email WHERE id = :id', [
                    ':id' => Session::get('donate_user_id'),
                    ':email' => $email,
                    ':email_status' => 2
                ]);

                DB::query('INSERT INTO email_verify SET user_id = :user_id, old_email = :old_email, new_email = :new_email, code = :code, start_date = :start_date', [
                    ':user_id' => $result->id,
                    ':old_email' => $result->email,
                    ':new_email' => $email,
                    ':code' => $code,
                    ':start_date' => date('Y-m-d H:i:s')
                ]);

                $code = base64_encode($code);

                $verifyLink = Settings::get('app.base_url') . '/index.php/email/verify/' . $code;

                $message = Language::_('Jei tikrai norite patvirtinti el. pašto adresą %s puslapyje %s paspauskite žemiau esančią patvirtinimo nuorodą', [$email, Settings::get('app.base_url')]) . '<br />';
                $message .= $verifyLink;

                Mail::send($email, Language::_('El. pašto adreso patvirtinimas'), $message);

                $update = true;
                $emailVerify = true;
                $emailForm = '<input type="text" name="email" class="form-control" placeholder="' . Language::_('El. pašto adresas') . '" disabled="disabled" value="' . Auth::user()->email . '" />
                              <span class="input-group-addon email-not-verified">
                                    <span
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="' . Language::_('El. pašto adresas laukia patvirtinimo') . '"
                                        class="glyphicon glyphicon-time"
                                        aria-hidden="true"></span>
                                </span>';
            }
        } else {
            if (Auth::user()->email && Auth::user()->email_status == 1)
                Output::json(Language::_('Būtina nurodyti el. pašto adresą'));
        }

        // change password
        if ($oldPassword) {
            if ( ! $newPassword)
                Output::json(Language::_('Būtina nurodyti naują slaptažodį'));

            $oldEncryptedPassword = L2::hash($oldPassword, Server::getHashType($server));
            $serverResults = DB::first('SELECT * FROM ' . SQL::get('sql.accounts.accounts', Server::getID($server)) . ' WHERE ' . SQL::get('sql.accounts.login', Server::getID($server)) . ' = ? AND ' . SQL::get('sql.accounts.password', Server::getID($server)) . ' = ?', [$username, $oldEncryptedPassword], 'server', $server, 'login');
            if ( ! $serverResults)
                Output::json(Language::_('Neteisingas senas slaptažodis'));

            if (Settings::get('app.settings.min_password') > 0 && strlen($newPassword) <= Settings::get('app.settings.min_password'))
                Output::json(Language::_('Minimalus slaptažodžio ilgis: %s simbolių (-ai)', [Settings::get('app.settings.min_password')]));

            $newEncryptedPassword = L2::hash($newPassword, Server::getHashType($server));

            DB::query('UPDATE ' . SQL::get('sql.accounts.accounts', Server::getID($server)) . ' SET ' . SQL::get('sql.accounts.password', Server::getID($server)) . ' = ? WHERE ' . SQL::get('sql.accounts.login', Server::getID($server)) . ' = ?', [$newEncryptedPassword, $username], 'server', $server, 'login');

            $update = true;
        }

        if ($update) {
            if ($emailVerify)
                Output::json(['content' => Language::_('Nustatymai išsaugoti, el. pašto patvirtinimo nuoroda išsiųsta Jums į el. paštą'), 'type' => 'success', 'verify-email' => 'ok', 'email_form' => $emailForm]);
            else
                Output::json(['content' => Language::_('Nustatymai išsaugoti'), 'type' => 'success']);
        } else {
            Output::json(Language::_('Nėra nustatymų kuriuos reikėtų išsaugoti'));
        }
    }
}
