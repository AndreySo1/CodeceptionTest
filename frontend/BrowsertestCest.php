<?php
use yii\helpers\Url;

class BrowsertestCest
{
    public function browsertestWork(AcceptanceTester $I)
    {
        $I->amOnPage('/browsertest');
        //$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeInTitle('Проверка браузера');
        $I->see('Проверить', '#signMessage');
        $I->seeElement('select', ['id' => 'certificates']);
        $I->seeElement('option', ['value' => '0']);
        $I->selectOption('.form-group select[id=certificates]', '30.08.2021, Сидоров Иван Петрович (РУП <Тестовая компания)');
        $I->click('Проверить');
        //$I->executeJS("window.alert(arguments[0])", ['Hello world']);
        $I->waitForJS("return $.active == 0;", 30);
        $I->see('Ошибка при проверке подписи', '#errors');
        //$I->see('Проверка успешно пройдена', '#message');
    }
}
