<?php

use yii\helpers\Url;

class A1MainPageCest
{
    public function ensureThatAboutWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->seeInTitle('Электронная торговая площадка');
        $I->see('С чего начать', '.col-lg-4');
        $I->seeElement('.navbar-nav');
        $I->seeLink('Войти');   
    }
}
