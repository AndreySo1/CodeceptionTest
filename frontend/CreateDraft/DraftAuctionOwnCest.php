<?php

use yii\helpers\Url;

class DraftAuctionOwnCest
{
	public function createDraftSuccess(AcceptanceTester $I){

		/*$I->amOnPage('/');
        $I->seeInTitle('Электронная торговая площадка');
        $I->click('Войти');

        $I->submitForm('#login-form', [
		    'LoginForm[username]' => 'etp000442',
		    'LoginForm[password]' => '11111111'
		], 'Войти');
		$I->waitForText('etp000442', 5, 'sup');*/ //аналог функции ниже

		$I->loginGzWebDriver('etp000159','11111111');
        $I->click('Личный кабинет');
        $I->click('Создание закупки');

        $I->seeInTitle('Создание закупки');
        $I->submitForm('form', [
		    'tenderFundsType' => 'own',
		    'tenderType' => 'auction'
		], 'Продолжить');

		$I->waitForText('Создание электронного аукциона', 5, '.page-header');

		$I->seeInTitle('Создание электронного аукциона');
		$I->click('Выбрать');
		$I->waitForText('Авиация', 5, '.closed');
		$I->click('+');
		$I->checkOption('#inds-inp-9'); //отрасль

		$nameTender = 'Самолет'.mt_rand(0,99999).'(ownFunds)';
		$I->fillField(['name' => 'title'], $nameTender); //название закупки
		$I->selectOption('select[name=hold_by]', 'customer'); //заказчик

		$I->fillField(['name' => 'request_end'], AcceptanceTester::$requestEnd); //дата окончания приема предложений
		$I->fillField(['name' => 'auctionTradeDate'], AcceptanceTester::$auctionTradeDate); //дата проведения торгов
		$I->fillField(['name' => 'participator_demand'], 'Требования123'); //требования к участникам
		$I->fillField(['name' => 'others'], 'Иные123'); //иные сведения
		
		$I->fillField('#lotDescription', 'ЛотОдин'); //название лота, возможно по ID не заполнит
		$I->pressKey('#lotDescription', \Facebook\WebDriver\WebDriverKeys::TAB, '0', '1', '1', '1', '1', '1', '1', '1', '0');//код ОКРБ
		$I->selectOption('select[id=lotSubjectType]', 'product'); //товар-услуга
		$I->fillField(['id' => 'count'], '777'); //количество
		$I->pressKey('#count',\Facebook\WebDriver\WebDriverKeys::TAB,\Facebook\WebDriver\WebDriverKeys::ENTER,\Facebook\WebDriver\WebDriverKeys::ENTER);//еденица измерения
		$I->fillField(['name' => 'lotDeliveryTermFrom'], AcceptanceTester::$lotDeliveryTerm); //поставка с
		$I->fillField(['name' => 'lotDeliveryTermTo'], AcceptanceTester::$lotDeliveryTerm); //поставка по
		$I->fillField(['id' => 'lotStartPrice'], '100'); //ориентировочная стоимость
		$I->fillField(['id' => 'lotCalcMethod'], 'аванс'); //исочник финансирования

		$I->click('#add-lot-btn');
		$I->waitForText('Новый лот', 3, '#new-lot-btn');
		$I->click('#saveDraft-btn');

		//$I->amOnPage('/tenders/drafts');
		$I->waitForText('Мои черновики', 3, '.page-header');
		$I->waitForText($nameTender, 3, '#lotsList');
		$I->seeInTitle('Мои черновики');
		$I->wait(0.5);

	}


}