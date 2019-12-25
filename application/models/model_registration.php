<?php

class Model_Registration extends Model
{


    static public function checkLogin($login)
    {
        if (empty($login)) return false;
        else return true;
    }

    static public function checkPassword($password)
    {
        if (empty($password)) return false;
        else return true;
    }

    public static function checkEmail($email)
    {
        if (empty($email)) return false;
        else return true;
    }

    public static function longLogin($login)
    {
        if (strlen($login) < 6) return false;
        else return true;
    }

    public static function longPassword($password)
    {
        if (strlen($password) < 6) return false;
        else return true;
    }

    public static function checkUserLogin($login)
    {
        $pdo = (new Connection())->create();
        $sql_check = 'SELECT EXISTS(SELECT login FROM users WHERE login = :login)';
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([':login' => $login]);
        if ($stmt_check->fetchColumn()) {
            return false;
        } else return true;

    }

    public static function newUser($login, $password, $email)
    {
        try {

            if (isset($login) && isset($password) && isset($email)) {
                if (Model_Registration::checkLogin($login) == false) return "Поле login пустое";
                if (Model_Registration::checkPassword($password) == false) return "Поле password пустое";
                if (Model_Registration::checkEmail($email) == false) return "Поле email пустое";
                if (Model_Registration::checkUserLogin($login) == false) return "Логин занят";
                if (!empty($login) && !empty($password) && !empty($email))
                    if (Model_Registration::longLogin($login) == false) return "Логин должен содержать минимум 6 символов";
                if (Model_Registration::longLogin($login) == false) return "Пароль должен содержать минимум 6 символов";
                $password = password_hash($password, PASSWORD_DEFAULT);
                $pdo = (new Connection())->create();
                $sql = 'INSERT INTO users(login,password,email) VALUES(:login,:password,:email)';
                $params = ['login' => $login, ':password' => $password, 'email' => $email];
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                return "Регистрация прошла успешна, ваш логин: " . $login;

            }

        } catch (PDOException $e) {
            die("Ошибка БД " . $e->getMessage());
        }
    }
}


