<?php

class User {
    public function get_characters() {
        $characters = DB::get("SELECT * FROM " . SQL::get('sql.characters.characters') . " WHERE " . SQL::get('sql.characters.account_name') . " = ?", [Session::get('server_account_login')], 'server');

        $SqlObjId = SQL::get('sql.characters.obj_Id');
        $SqlCharName = SQL::get('sql.characters.char_name');

        return View::make('user', ['characters' => $characters, 'SqlCharName' => $SqlCharName, 'SqlObjId' => $SqlObjId]);
    }
    public function post_selectCharacter() {
        $characterName = Input::get('character_name');
        $oldChar = '<a href="javascript: void(0);" class="select-char">' . Session::get('character_name') . '</a>';

        $characters = DB::first("SELECT * FROM " . SQL::get('sql.characters.characters') . " WHERE " . SQL::get('sql.characters.account_name') . " = ? AND " . SQL::get('sql.characters.char_name') . " = ?", [Session::get('server_account_login'), $characterName], 'server');

        if ( ! $characters) {
            return Output::json(Language::_('Deja, bet nepavyko pasirinkti veikėjo'));
        }

        $charObjId = SQL::get('sql.characters.obj_Id');
        Session::put('character_obj_id', $characters->$charObjId);
        Session::put('character_name', $characterName);

        return Output::json(['success' => 'ok', 'content' => Language::_('Veikėjas pasirinktas'), 'type' => 'success', 'character_name' => $characterName, 'old_char' => $oldChar, 'view' => 'user']);
    }

    public function get_login() {
        if (Auth::isLoggedIn())
            Auth::logout();

        $servers = Settings::get('database.servers');

        View::make('login', ['servers' => $servers]);
    }

    public function post_login() {
        $username = Input::get('username');
        $password = Input::get('password');
        $server = Input::get('server');

        if (Settings::get('app.captcha.login')) {
            $resp = recaptcha_check_answer(Settings::get('app.captcha.secret'), $_SERVER["REMOTE_ADDR"], Input::get("recaptcha_challenge_field"), Input::get("recaptcha_response_field"));

            if ( ! $resp->is_valid)
                return Output::json(Language::_('Neteisingai įvestas apsaugos kodas'));
        }

        if ( ! $username || ! $password)
            return Output::json(Language::_('Užpildykite visus laukelius'));

        if (Auth::check($username, $password, $server))
            return Output::json(array('view' => 'user'));

        return Output::json(Language::_('Prisijungti nepavyko'));
    }
    public function post_logout() {
        Auth::logout();

        return Output::json(['success' => 'ok']);
    }

    public function get_recovery() {
        if (Auth::isLoggedIn())
            Auth::logout();

        $servers = Settings::get('database.servers');

        View::make('recovery', ['servers' => $servers]);
    }
    public function post_recovery() {
        $input = Input::get('recovery_input');
        $server = Input::get('server');
        $userId = false;

        if ( ! $input)
            return Output::json(Language::_('Užpildykite visus laukelius'));

        if (strpos($input, '@') !== false) {
            $result = DB::first('SELECT * FROM users WHERE server = :server AND email = :email', [
                ':email' => $input,
                ':server' => $server
            ]);

            if ( ! $result)
                return Output::json(Language::_('Toks el. pašto adresas nerastas'));

            $userId = $result->id;
            $email = $result->email;
        } else {
            $result = DB::first('SELECT * FROM users WHERE username = :username', [
                ':username' => $input,
                ':server' => $server
            ]);

            if ( ! $result)
                return Output::json(Language::_('Vartotojas tokiu slapyvardžių nerastas'));

            $userId = $result->id;
            $email = $result->email;
        }

        if ($userId) {
            $result = DB::first('SELECT * FROM recovery WHERE user_id = :user_id AND active_until >= :active', [
                ':user_id' => $userId,
                ':active' => time()
            ]);

            if ($result)
                return Output::json(Language::_('Negalite keisti slaptažodžio dažniau, nei kartą per 12 valandų'));

            while(true) {
                $code = File::randomString(35);
                $result = DB::first('SELECT * FROM recovery WHERE code = ?', [$code]);
                if ( ! $result)
                    break;
            }

            DB::query('INSERT INTO recovery SET user_id = :user_id, code = :code, server = :server, active_until = :active_until, add_date = :add_date', [
                ':user_id' => $userId,
                ':code' => $code,
                ':server' => $server,
                ':active_until' => strtotime("+12 hours", strtotime(date('Y-m-d H:i:s'))),
                ':add_date' => date('Y-m-d H:i:s')
            ]);

            $code = base64_encode($code);

            $verifyLink = Settings::get('app.base_url') . '/index.php/recovery/verify/' . $code;

            $message = Language::_('Jei tikrai norite gauti naują slaptažodį puslapyje paspauskite žemiau esančią patvirtinimo nuorodą', [Settings::get('app.base_url')]) . '<br />';
            $message .= $verifyLink;

            Mail::send($email, Language::_('Slaptažodžio keitimo patvirtinimas'), $message);

            return Output::json(['content' => Language::_('Slaptažodžio keitimo patvirtinimas nusiųstas Jums į el. paštą'), 'type' => 'success']);
        }
    }

    public function get_registration() {
        if (Settings::get('app.registration.enabled') == false)
            return View::make('login');

        if (Auth::isLoggedIn())
            Auth::logout();

        $servers = Settings::get('database.servers');

        return View::make('registration', ['servers' => $servers]);
    }
    public function post_registration() {
        $username = Input::get('username');
        $password = Input::get('password');
        $server = Input::get('server');

        if (Settings::get('app.captcha.registration')) {
            $resp = recaptcha_check_answer(Settings::get('app.captcha.secret'), $_SERVER["REMOTE_ADDR"], Input::get("recaptcha_challenge_field"), Input::get("recaptcha_response_field"));

            if ( ! $resp->is_valid)
                return Output::json(Language::_('Neteisingai įvestas apsaugos kodas'));
        }

        if ( ! $username || ! $password)
            return Output::json(Language::_('Užpildykite visus laukelius'));

        if (Settings::get('app.registration.min') && mb_strlen($password) < Settings::get('app.registration.min'))
            return Output::json(Language::_('Minimalus slaptažodžio ilgis turi būti: %s simboliai (-ų)', [Settings::get('app.registration.min')]));

        if (Settings::get('app.registration.max') && mb_strlen($password) > Settings::get('app.registration.max'))
            return Output::json(Language::_('Slaptažodis negali būti ilgesnis, nei: %s simboliai (-ų)', [Settings::get('app.registration.min')]));

        $serverResults = DB::first('SELECT * FROM ' . SQL::get('sql.accounts.accounts', Server::getID($server)) . ' WHERE ' . SQL::get('sql.accounts.login', Server::getID($server)) . ' = ?', [$username], 'server', $server, 'login');
        $loginFieldName = SQL::get('sql.accounts.login', Server::getID($server));
        if (isset($serverResults->$loginFieldName))
            return Output::json(Language::_('Toks vartotojas jau užregistruotas'));

        $encodedPassword = L2::hash($password, Server::getHashType($server));

        DB::query('INSERT INTO ' . SQL::get('sql.accounts.accounts', Server::getID($server)) . ' SET ' . SQL::get('sql.accounts.login', Server::getID($server)) . ' = ?, ' . SQL::get('sql.accounts.password', Server::getID($server)) . ' = ?', [$username, $encodedPassword], 'server', $server, 'login');

        return Output::json(['content' => Language::_('Registracija sėkminga'), type => 'success', 'success' => 'ok']);
    }

    public function post_isOnline() {
        if (Session::get('character_obj_id') && Settings::get('app.player.online_check')) {
            if (Player::isOnline(Session::get('character_obj_id'))) {
                Session::forget('character_obj_id');
                Session::forget('character_name');
                return Output::json(['type' => 'true']);
            }

            return Output::json(['type' => 'false']);
        }

        return Output::json(['type' => 'false']);
    }
}
