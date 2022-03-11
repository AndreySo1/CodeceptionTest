<?php

use yii\helpers\Url;
use app\helpers\text;

class HomePageCest
{
    public function homePageOpen(SmokeTester $I)
    {
        $I->amOnPage('/');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeInTitle('Электронная торговая площадка');
        $I->see('С чего начать', '.col-lg-4');
    }

   public function dataCorrect(SmokeTester $I){
    	$I->amOnPage('/');
    	$month = text::localeMonth(date('n'));
        $dataRu = date('j '.$month.' Y');
        $I->see($dataRu, '#date');
    }

    public function mainMenu(SmokeTester $I){
    	$I->amOnPage('/');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeInTitle('Электронная торговая площадка');
        $I->seeElement('ul', ['id' => 'w4']);

        $arrMenu = ['Главная','Новости','Документы','Проверка браузера',/*'Инструкции',*/'Подготовка к участию',/*'Нормативные правовые акты',*//*'Программное обеспечение',*/'Реестр аккредитованных лиц','Образование','Получение ЭЦП','Реестр авторизованных УЦ','Планы закупок','Закупки','Обратная связь','Адреса и телефоны','Войти','Регистрация'];
        $arrTitle = ['Электронная торговая площадка','Новости','Документы','Проверка браузера',/*'Инструкции',*/'Подготовка к участию',/*'Нормативные правовые акты',*//*'Программное обеспечение',*/'Реестр аккредитованных лиц','Образовательные услуги','Удостоверяющий центр','Реестр авторизованных УЦ','Все планы закупок',' Объявленные закупки','Обратная связь','адреса и телефоны','Вход','Регистрация'];

        for($i=0; $i<count($arrMenu); $i++){
        	if(in_array($i, [7,8])){
        		$I->amOnPage('/');
        	};
        	$I->click($arrMenu[$i], '#w4 a');
	        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
	        $I->seeInTitle($arrTitle[$i]);
        };

    }

}
