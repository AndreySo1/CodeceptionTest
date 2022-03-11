<?php

use yii\helpers\Url;
use app\helpers\text;

class RegistrationAndLoginCest
{
    public function ensureRegistrationWork(SmokeTester $I)
    {

        $I->amOnPage('/site/signup');
        $I->seeInTitle('Регистрация');
        $I->seeElement('form', ['id' => 'registration-form']);
        $unp=mt_rand(100000000,999999999);
        $I->fillField(['name' => 'SignupForm[unp]'], $unp);
        $I->click('Продолжить');
        $I->see('Защитный код', '.control-label');

        $email = '88test88@test.test';
        $pass = 'a642a77abd7d4f51bf9226ceaf891fcbb5b299b8';

        $I->fillField(['name' => 'SignupForm[full_name]'], 'Тест Тест Тест');
        $I->fillField(['name' => 'SignupForm[email]'], $email);
        $I->fillField(['name' => 'SignupForm[phone]'], '+375290000000');
        $I->fillField(['name' => 'SignupForm[password]'], '11111111');
        $I->fillField(['name' => 'SignupForm[password_repeat]'], '11111111');
        $I->fillField(['name' => 'SignupForm[captcha]'], 'Andrey88');

        $I->click('Регистрация' ,'button[type=submit]'); //при переносе кнопки в форму работает

        $I->amOnPage('/profile');
        $I->seeInCurrentUrl('/profile');
        $I->seeInTitle('Мой профиль');     

        $I->seeInDatabase('accounts', ['email'=> $email, 'password' =>$pass]);
        $accountsArr = $I->grabColumnFromDatabase('accounts', 'login', ['email'=> $email, 'password' =>$pass]);
        codecept_debug($accountsArr);
        $login = end($accountsArr);
        $I->see($login, 'sup');
        
        $I->see('Заполнение данных профиля', '.panel-title');
    }

    public function ensureLoginWork(SmokeTester $I)
    {
        $I->amOnPage('/');
        $I->seeInTitle('Электронная торговая площадка');
        $I->click('Войти', 'li');

        $I->seeInCurrentUrl('/site/login');
        $I->seeInTitle('Вход');
        $I->fillField(['name' => 'LoginForm[username]'], 'Admin');
        $I->fillField(['name' => 'LoginForm[password]'], '11111111');
        $I->click('Войти' ,'button[name=login-button]');

        $I->seeInTitle('Электронная торговая площадка');
        $I->see('Admin', 'sup');
    }

}
