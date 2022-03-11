<?php

use yii\helpers\Url;

class DraftAndCheckSingleSourseCest
{
	public $nameTender;

	public function createDraftSuccess(AcceptanceTester $I){

		$I->loginGzWebDriver('etp000159','11111111');
        $I->click('Личный кабинет');
        $I->click('Создание закупки');

        $I->seeInTitle('Создание закупки');
        $I->submitForm('form', [
		    'tenderFundsType' => 'budget',
		    'tenderType' => 'single-source'
		], 'Продолжить');

		$I->waitForText('Создание закупки из одного источника (в электронном виде)', 5, '.page-header');

		$I->seeInTitle('Создание закупки из одного источника (в электронном виде)');
		$I->click('Выбрать');
		$I->waitForText('Авиация', 5, '.closed');
		$I->click('+');
		$I->checkOption('#inds-inp-9'); //отрасль

		$this->nameTender = 'Самолет'.mt_rand(0,99999);
		$I->fillField(['name' => 'title'], $this->nameTender); //название закупки
		$I->selectOption('select[name=hold_by]', 'customer'); //заказчик

		$I->fillField(['name' => 'request_end'], AcceptanceTester::$requestEnd); //дата окончания приема предложений
		$I->fillField(['name' => 'participator_demand'], 'Порядок предоставления123'); //требования к участникам
		$I->fillField(['name' => 'others'], 'ПереченьДоков123'); //перечень доков

		$I->click('Добавить из плана');
		$I->waitForText('2022-300003606-1', 3, '.plan-item-row-12676727');
		$I->click('#add-position-12676727'); //плюс возле позиции 2021-300003606-3
		$I->fillField(['id' => 'budget-13298612'], '1'); //бюджетные средства, !!!id элемента меняется
		$I->fillField(['id' => 'own-funds'], '0'); //собственные
		$I->fillField(['id' => 'out-funds'], '0'); //бюджетные со счетов заказчика
		$I->fillField(['id' => 'position-amount'], '11'); //обьем
		$I->click('#addToPositionsList');
		$I->waitForText('№ 2022-300003606-1', 3, '#positionsToLotList');
		$I->click('#addPlanItems');
		
		$I->fillField(['name' => 'lotDeliveryTermFrom'], AcceptanceTester::$lotDeliveryTerm); //поставка с
		$I->fillField(['name' => 'lotDeliveryTermTo'], AcceptanceTester::$lotDeliveryTerm); //поставка по
		$I->fillField(['id' => 'lotCalcMethod'], 'аванс'); //Порядок оплаты
		$I->selectOption('select[id=finance_source]', '1'); //исочник финансирования

		$I->click('#add-lot-btn');
		$I->waitForText('Новый лот', 3, '#new-lot-btn');
		$I->click('#saveDraft-btn');

		$I->waitForText('Мои черновики', 3, '.page-header');
		$I->waitForText($this->nameTender, 3, '#lotsList');
		$I->seeInTitle('Мои черновики');
		$I->wait(0.5);
	}

	public function checkFundsReserve(AcceptanceTester $I){
		$I->loginGzWebDriver('etp000159','11111111');
        $I->click('Личный кабинет');
        $I->click('Мои черновики');
        $I->waitForText('Мои черновики', 3, '.page-header');
        $I->waitForText($this->nameTender, 3, '#lotsList');

        $I->click($this->nameTender);
        $I->waitForText('Проверить резервирование средств', 3, '#checkFundsReserve-btn');
        $I->click('#checkFundsReserve-btn');

        $I->waitForText('Проверка резервирования', 3, '.page-header');

        $trLine = $I->grabMultiple('#lotsList tbody tr:first-child');
        codecept_debug($trLine);
        $includeName = strpos($trLine[0], $this->nameTender); // есть ли в строке имя тендера
        codecept_debug($includeName);

        $includeStatusReserve = strpos($trLine[0], 'Проверка резервирования'); // находится ли на резервировании
        codecept_debug($includeStatusReserve);

        if(($includeName == true) && ($includeStatusReserve == true)){
        	$I->expect('статус - Проверка резервирования');
        	$I->see('Проверка резервирования', '#lotsList');
        } else{
        	$I->see('Ошибка - статус процедуры не такой как ожидается', '#lotsList');
        };

        $I->wait(0.5);
       
	}


}