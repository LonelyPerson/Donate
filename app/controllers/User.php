<?php

if ( ! defined('STARTED')) exit;

use \Donate\Vendor\DB;
use \Donate\Vendor\SQL;
use \Donate\Vendor\View;
use \Donate\Vendor\Settings;
use \Donate\Vendor\Input;
use \Donate\Vendor\Output;
use \Donate\Vendor\Auth;
use \Donate\Vendor\L2;
use \Donate\Vendor\Mail;
use \Donate\Vendor\Player;
use \Donate\Vendor\Session;
use \Donate\Vendor\Form;

class User {
    public function get_characters() {
        $characters = DB::get("SELECT * FROM " . SQL::get('sql.characters.characters') . " WHERE " . SQL::get('sql.characters.account_name') . " = ?", [Session::get('server_account_login')], 'server');

        $SqlObjId = SQL::get('sql.characters.obj_Id');
        $SqlCharName = SQL::get('sql.characters.char_name');

        return view('user', ['characters' => $characters, 'SqlCharName' => $SqlCharName, 'SqlObjId' => $SqlObjId]);
    }
    public function post_selectCharacter() {
        $characterName = Input::get('character_name');
        $oldChar = '<a href="javascript: void(0);" class="select-char">' . Session::get('character_name') . '</a>';

        $characters = DB::first("SELECT * FROM " . SQL::get('sql.characters.characters') . " WHERE " . SQL::get('sql.characters.account_name') . " = ? AND " . SQL::get('sql.characters.char_name') . " = ?", [Session::get('server_account_login'), $characterName], 'server');

        if ( ! $characters) {
            return Output::json(__('You have no characters'));
        }

        $charObjId = SQL::get('sql.characters.obj_Id');
        Session::put('character_obj_id', $characters->$charObjId);
        Session::put('character_name', $characterName);

        _log('Selected new character <strong>' . $characterName . '</strong>', 'user');

        return Output::json(['success' => 'ok', 'content' => __('Character selected'), 'type' => 'success', 'character_name' => $characterName, 'old_char' => $oldChar, 'view' => 'user']);
    }

    public function get_login() {
        if (Auth::isLoggedIn())
            Auth::logout();

        $servers = config('database.servers');

        view('login', ['servers' => $servers]);
    }

    public function post_login() {
        $username = Input::get('username');
        $password = Input::get('password');
        $server = Input::get('server');

        // log
        _log('Trying to sign in', 'user', $username);

        if ( ! Form::isTokenCorrect('login')) {
            return Output::json(__('Token mismatch'));
        }

        if (config('app.captcha.login')) {
            $resp = recaptcha_check_answer(config('app.captcha.secret'), $_SERVER["REMOTE_ADDR"], Input::get("recaptcha_challenge_field"), Input::get("recaptcha_response_field"));

            if ( ! $resp->is_valid)
                return Output::json(__('Bad security code'));
        }

        if ( ! $username || ! $password)
            return Output::json(__('All fields are required'));

        if (Auth::check($username, $password, $server))
            return Output::json(array('view' => 'user'));

        return Output::json(__('Login failed'));
    }
    public function post_logout() {
        _log('Logged out', 'user');

        Auth::logout();

        return Output::json(['success' => 'ok']);
    }

    public function get_recovery() {
        if (Auth::isLoggedIn())
            Auth::logout();

        $servers = config('database.servers');

        view('recovery', ['servers' => $servers]);
    }
    public function post_recovery() {
        $input = Input::get('recovery_input');
        $server = Input::get('server');
        $userId = false;

        if ( ! Form::isTokenCorrect('recovery')) {
            return Output::json(__('Token mismatch'));
        }

        if ( ! $input)
            return Output::json(__('All field are required'));

        if (strpos($input, '@') !== false) {
            $result = DB::first('SELECT * FROM users WHERE server = :server AND email = :email', [
                ':email' => $input,
                ':server' => $server
            ]);

            if ( ! $result)
                return Output::json(__('User not found'));

            $userId = $result->id;
            $email = $result->email;
        } else {
            $result = DB::first('SELECT * FROM users WHERE username = :username AND server = :server', [
                ':username' => $input,
                ':server' => $server
            ]);

            if ( ! $result)
                return Output::json(__('User not found'));

            $userId = $result->id;
            $email = $result->email;
        }

        if ($userId) {
            $result = DB::first('SELECT * FROM recovery WHERE user_id = :user_id AND active_until >= :active', [
                ':user_id' => $userId,
                ':active' => time()
            ]);

            if ($result)
                return Output::json(__('You can not change a password more than once in 12 hours'));

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

            $verifyLink = config('app.base_url') . '/verify/recovery/' . $code;

            $message = __('If you really want to get a new password in DS, click on the verification link below', [config('app.base_url')]) . '<br />';
            $message .= $verifyLink;

            Mail::send($email, __('Password change in DS confirmation'), $message);

            _log('Initiated password recovery', 'user');

            return Output::json(['content' => __('Password change confirmation sended to your email'), 'type' => 'success']);
        }
    }

    public function get_registration() {
        if (config('app.registration.enabled') == false)
            return view('login');

        if (Auth::isLoggedIn())
            Auth::logout();

        $servers = config('database.servers');

        return view('registration', ['servers' => $servers]);
    }
    public function post_registration() {
        $username = Input::get('username');
        $password = Input::get('password');
        $server = Input::get('server');

        _log('New user trying register username <strong>' . $username . '</strong>', 'user');

        if ( ! Form::isTokenCorrect('registration')) {
            return Output::json(__('Token mismatch'));
        }

        if (config('app.captcha.registration')) {
            $resp = recaptcha_check_answer(config('app.captcha.secret'), $_SERVER["REMOTE_ADDR"], Input::get("recaptcha_challenge_field"), Input::get("recaptcha_response_field"));

            if ( ! $resp->is_valid)
                return Output::json(__('Bad security code'));
        }

        if ( ! $username || ! $password)
            return Output::json(__('All fields are required'));

        if (config('app.registration.min') && mb_strlen($password) < config('app.registration.min'))
            return Output::json(__('Minimum password length should be %s characters', [config('app.registration.min')]));

        if (config('app.registration.max') && mb_strlen($password) > config('app.registration.max'))
            return Output::json(__('Maximum password length are %s characters', [config('app.registration.max')]));

        $serverResults = DB::first('SELECT * FROM ' . SQL::get('sql.accounts.accounts', Server::getID($server)) . ' WHERE ' . SQL::get('sql.accounts.login', Server::getID($server)) . ' = ?', [$username], 'server', $server, 'login');
        $loginFieldName = SQL::get('sql.accounts.login', Server::getID($server));
        if (isset($serverResults->$loginFieldName))
            return Output::json(__('Username already exists'));

        $encodedPassword = L2::hash($password, Server::getHashType($server));

        DB::query('INSERT INTO ' . SQL::get('sql.accounts.accounts', Server::getID($server)) . ' SET ' . SQL::get('sql.accounts.login', Server::getID($server)) . ' = ?, ' . SQL::get('sql.accounts.password', Server::getID($server)) . ' = ?', [$username, $encodedPassword], 'server', $server, 'login');

        _log('New user successfully registered username <strong>' . $username . '</strong>', 'user');

        return Output::json(['content' => __('Registration successfully'), type => 'success', 'success' => 'ok']);
    }

    public function post_isOnline() {
        if (Session::get('character_obj_id') && config('app.player.online_check')) {
            if (Player::isOnline(Session::get('character_obj_id'))) {
                Session::forget('character_obj_id');
                Session::forget('character_name');

                return Output::json(['type' => 'true']);
            }

            return Output::json(['type' => 'false']);
        }

        return Output::json(['type' => 'false']);
    }

    public function get_player() {
        $player = DB::first("SELECT " . SQL::get('sql.characters.level') . " FROM " . SQL::get('sql.characters.characters') . " WHERE " . SQL::get('sql.characters.obj_Id') . " = ?", [Session::get('character_obj_id')], 'server');

        $sql_level = SQL::get('sql.characters.level');
        $currentLevel = $player->$sql_level;

        return view('player', ['current_level' => $currentLevel]);
    }
    public function post_changeName() {
        $name = Input::get('new_name');
        $userBalance = Auth::user()->balance;
        $price = config('app.player.change_name.price');
        $oldName = Session::get('character_name');

        if ( ! $name)
            return Output::json(['content' => __('Please enter new username'), 'type' => 'danger', 'error' => 'ok']);

        if ($userBalance < $price)
            return Output::json(['content' => __('You do not have enough DC'), 'type' => 'danger', 'error' => 'ok']);

        if (mb_strlen($name, 'UTF-8') < config('app.player.change_name.min_chars'))
            return Output::json(['content' => __('Min. characters length are %s', [config('app.player.change_name.min_chars')]), 'type' => 'danger', 'error' => 'ok']);

        if (mb_strlen($name, 'UTF-8') > config('app.player.change_name.max_chars'))
            return Output::json(['content' => __('Max. characters length are %s', [config('app.player.change_name.max_chars')]), 'type' => 'danger', 'error' => 'ok']);

        if ( ! preg_match("/^[" . config('app.player.change_name.allowed_chars') . "]+$/i", $name))
            return Output::json(['content' => __('You entered not allowed character'), 'type' => 'danger', 'error' => 'ok']);

        $player = DB::first("SELECT * FROM " . SQL::get('sql.characters.characters') . " WHERE " . SQL::get('sql.characters.char_name') . " = ?", [$name], 'server');
        if ($player)
            return Output::json(['content' => __('Username already exists'), 'type' => 'danger', 'error' => 'ok']);

        $newUserBalance = $userBalance - $price;
        DB::query("UPDATE users SET balance = :balance WHERE id = :id", [
            ':balance' => $newUserBalance,
            ':id' => Session::get('donate_user_id')
        ]);

        DB::query("UPDATE " . SQL::get('sql.characters.characters') . " SET " . SQL::get('sql.characters.char_name') . " = ? WHERE " . SQL::get('sql.characters.obj_Id') . " = ?", [$name, Session::get('character_obj_id')], 'server');

        Session::put('character_name', $name);

        _log('Successfully changed char name from <strong>'  . $oldName . '</strong> to <strong>' . $name . '</strong>', 'user');

        return Output::json(['content' => __('Username changed successfully'), 'type' => 'success', 'success' => 'ok', 'view' => 'player']);
    }
    public function post_unstuck() {
        $userBalance = Auth::user()->balance;
        $price = config('app.player.unstuck.price');
        $loc = config('app.player.unstuck.loc');

        $ex = explode(',', $loc);
        $x = $ex[0];
        $y = $ex[1];
        $z = $ex[2];

        if ($userBalance < $price)
            return Output::json(['content' => __('You do not have enough DC'), 'type' => 'danger', 'error' => 'ok']);

        if ($price > 0) {
            $newUserBalance = $userBalance - $price;
            DB::query("UPDATE users SET balance = :balance WHERE id = :id", [
                ':balance' => $newUserBalance,
                ':id' => Session::get('donate_user_id')
            ]);
        }

        DB::query("UPDATE " . SQL::get('sql.characters.characters') . " SET " . SQL::get('sql.characters.x') . " = ?, " . SQL::get('sql.characters.y') . " = ?, " . SQL::get('sql.characters.z') . " = ? WHERE " . SQL::get('sql.characters.obj_Id') . " = ?", [$x, $y, $z, Session::get('character_obj_id')], 'server');

        _log('Successfully unstuck character <strong>'  . Session::get('character_name') . '</strong>', 'user');

        return Output::json(['content' => __('Player unstuck successfully'), 'type' => 'success', 'success' => 'ok', 'view' => 'player']);
    }
    public function post_level() {
        $userBalance = Auth::user()->balance;
        $newLevel = Input::get('level');
        $price = config('app.player.level.price');
        $delevelPrice = config('app.player.level.delevel_price');
        $minLevel = config('app.player.level.min_level');
        $maxLevel = config('app.player.level.max_level');

        if ( ! $newLevel)
            return Output::json(['content' => __('Please enter new level'), 'type' => 'danger', 'error' => 'ok']);

        if ($newLevel < $minLevel)
            return Output::json(['content' => __('Min. level are %s', [$minLevel]), 'type' => 'danger', 'error' => 'ok']);

        if ($newLevel > $maxLevel)
            return Output::json(['content' => __('Max. level are %s', [$maxLevel]), 'type' => 'danger', 'error' => 'ok']);

        $type = 'up';
        $player = DB::first("SELECT * FROM " . SQL::get('sql.characters.characters') . " WHERE " . SQL::get('sql.characters.obj_Id') . " = ?", [Session::get('character_obj_id')], 'server');

        $sql_level = SQL::get('sql.characters.level');

        $currentLevel = $player->$sql_level;

        if ($newLevel == $currentLevel)
            return Output::json(['content' => __('You entered same level'), 'type' => 'danger', 'error' => 'ok']);

        if ($newLevel < $currentLevel)
            $type = 'down';

        if ($type == 'up') {
            // level up
            $levelsCount = $newLevel - $currentLevel;
            $price = $price * $levelsCount;
        } else {
            // delevel
            $levelsCount = $currentLevel - $newLevel;
            $price = $delevelPrice * $levelsCount;
        }

        if ($userBalance < $price)
            return Output::json(['content' => __('You do not have enough DC'), 'type' => 'danger', 'error' => 'ok']);

        DB::query("UPDATE " . SQL::get('sql.characters.characters') . " SET " . SQL::get('sql.characters.level') . " = ? WHERE " . SQL::get('sql.characters.obj_Id') . " = ?", [$newLevel, Session::get('character_obj_id')], 'server');

        $newUserBalance = $userBalance - $price;
        DB::query("UPDATE users SET balance = :balance WHERE id = :id", [
            ':balance' => $newUserBalance,
            ':id' => Session::get('donate_user_id')
        ]);

        _log('Successfully changed character <strong>' . Session::get('character_name') . '</strong> level from <strong>' . $currentLevel . '</strong> to <strong>' . $newLevel . '</strong>', 'user');

        return Output::json(['content' => __('Level changed successfully'), 'type' => 'success', 'success' => 'ok', 'view' => 'player']);
    }

    public function get_verifyEmail($code) {
        if ( ! $code)
            Output::information(Language::_("Unfortunately we can't verify this email"));

        $code = base64_decode($code);

        $result = DB::first('SELECT * FROM email_verify WHERE code = :code', [
            ':code' => $code
        ]);
        if ( ! isset($result->id))
            Output::information(Language::_("Unfortunately we can't verify this email"));

        $userId = $result->user_id;

        $result = DB::first('SELECT * FROM users WHERE id = :user_id', [
            ':user_id' => $userId
        ]);
        if ( ! isset($result->id)) 
           Output::information(Language::_("Unfortunately we can't verify this email"));

        DB::query('UPDATE email_verify SET end_date = :end_date WHERE code = :code', [':code' => $code, ':end_date' => date('Y-m-d H:i:s')]);
        DB::query('UPDATE users SET email_status = :email_status WHERE id = :id', [
            ':email_status' => 1,
            ':id' => $userId
        ]);

        _log('Successfully verified email', 'user');

        Output::information(Language::_('Email verified successfully'));
    }

    public function get_verifyRecovery($code) {
        $code = base64_decode($code);

        if ( ! $code)
            Output::information(Language::_("Unfortunately we can't verify password change"));

        $result = DB::first('SELECT * FROM recovery WHERE code = :code', [
            ':code' => $code
        ]);
        if ( ! isset($result->id) || $result->active_until < time()) 
            Output::information(Language::_("Unfortunately we can't verify password change"));

        $userId = $result->user_id;
        $server = $result->server;

        $result = DB::first('SELECT * FROM users WHERE id = :user_id', [
            ':user_id' => $userId
        ]);
        if ( ! isset($result->id))
            Output::information(Language::_("Unfortunately we can't verify password change"));

        $username = $result->username;
        $email = $result->email;
        $emailStatus = $result->email_status;

        if ($emailStatus != 1)
            Output::information(Language::_("Unfortunately we can't verify password change"));

        $result = DB::first('SELECT * FROM ' . SQL::get('sql.accounts.accounts', Server::getID($server)) . ' WHERE ' . SQL::get('sql.accounts.login', Server::getID($server)) . ' = ?', [$username], 'server', $server, 'login');

        $loginFieldName = SQL::get('sql.accounts.login', Server::getID($server));

        if ( ! isset($result->$loginFieldName)) 
            Output::information(Language::_("Unfortunately we can't verify password change"));

        $password = File::randomString(6);
        $newPassword = L2::hash($password, Server::getHashType($server));

        DB::query('DELETE FROM recovery WHERE code = :code', [':code' => $code]);
        DB::query('UPDATE ' . SQL::get('sql.accounts.accounts', Server::getID($server)) . ' SET ' . SQL::get('sql.accounts.password', Server::getID($server)) . ' = ? WHERE ' . SQL::get('sql.accounts.login', Server::getID($server)) . ' = ?', [$newPassword, $username], 'server', $server, 'login');

        $message = Language::_('Your new password is: %s', [$password]);

        Mail::send($email, Language::_('Your new password'), $message);

        _log('Successfully recovered password', 'user');

        Output::information(Language::_('Password change successfully verified and new password sended to your email'));
    }

    public function post_token() {
        return Output::json(['token' => Form::token(Input::get('form'))]);
    }
}
