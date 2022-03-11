<?php

use yii\helpers\Url;

class DraftAuction5LotCest
{
	public $nameTender;

	public function createDraftSuccess(AcceptanceTester $I){

		$I->loginGzWebDriver('etp000159','11111111');
        $I->click('Личный кабинет');
        $I->click('Создание закупки');

        $I->seeInTitle('Создание закупки');
        $I->submitForm('form', [
		    'tenderFundsType' => 'budget',
		    'tenderType' => 'auction'
		], 'Продолжить');

		$I->waitForText('Создание электронного аукциона', 5, '.page-header');

		$I->seeInTitle('Создание электронного аукциона');
		$I->click('Выбрать');
		$I->waitForText('Авиация', 5, '.closed');
		$I->click('+');
		$I->checkOption('#inds-inp-9'); //отрасль

		$this->nameTender = 'Самолет_5лотов_'.mt_rand(0,99999);
		$I->fillField(['name' => 'title'], $this->nameTender); //название закупки
		$I->selectOption('select[name=hold_by]', 'customer'); //заказчик

		$I->fillField(['name' => 'request_end'], AcceptanceTester::$requestEnd); //дата окончания приема предложений
		$I->fillField(['name' => 'participator_demand'], 'Требования123'); //требования к участникам
		$I->fillField(['name' => 'others'], 'Иные123'); //иные сведения

		/*//lot1
		$I->click('Добавить из плана');
		$I->waitForText('2021-300003606-3', 3, '.plan-item-row-11676169');
		$I->click('#add-position-11676169'); //плюс возле позиции 2021-300003606-3
		$I->fillField(['id' => 'budget-13295172'], '1'); //бюджетные средства, !!!id элемента меняется
		$I->fillField(['id' => 'own-funds'], '0'); //собственные
		$I->fillField(['id' => 'out-funds'], '0'); //бюджетные со счетов заказчика
		$I->fillField(['id' => 'position-amount'], '88'); //обьем
		$I->click('#addToPositionsList');
		$I->waitForText('№ 2021-300003606-3', 3, '#positionsToLotList');
		$I->click('#addPlanItems');
		
		$I->fillField(['name' => 'lotDeliveryTermFrom'], AcceptanceTester::$lotDeliveryTerm); //поставка с
		$I->fillField(['name' => 'lotDeliveryTermTo'], AcceptanceTester::$lotDeliveryTerm); //поставка по
		$I->fillField(['id' => 'lotCalcMethod'], 'аванс'); //способ расчета
		$I->selectOption('select[id=finance_source]', '1'); //исочник финансирования
		$I->click('#add-lot-btn');
		$I->waitForText('Новый лот', 3, '#new-lot-btn');
		*/

		for($i=0; $i<5; $i++){
			$I->click('Добавить из плана');
			$I->waitForText('2021-300003606-3', 3, '.plan-item-row-11676169');
			$I->click('#add-position-11676169'); //плюс возле позиции 2021-300003606-3
			$I->fillField(['id' => 'budget-13295172'], 1+$i); //бюджетные средства, !!!id элемента меняется
			$I->fillField(['id' => 'own-funds'], '0'); //собственные
			$I->fillField(['id' => 'out-funds'], '0'); //бюджетные со счетов заказчика
			$I->fillField(['id' => 'position-amount'], '33'); //обьем
			$I->click('#addToPositionsList');
			$I->waitForText('№ 2021-300003606-3', 3, '#positionsToLotList');
			$I->click('#addPlanItems');
			
			$I->fillField(['id' => 'lotDescription'], 'Самалет'.$i);
			$I->fillField(['name' => 'lotDeliveryTermFrom'], AcceptanceTester::$lotDeliveryTerm); //поставка с
			$I->fillField(['name' => 'lotDeliveryTermTo'], AcceptanceTester::$lotDeliveryTerm); //поставка по
			$I->fillField(['id' => 'lotCalcMethod'], 'аванс'); //способ расчета
			$I->selectOption('select[id=finance_source]', '1'); //исочник финансирования
			$I->click('#add-lot-btn');
			$I->waitForText('Новый лот', 3, '#new-lot-btn');
			$I->click('#new-lot-btn');
		};

		$I->click('#saveDraft-btn');

		$I->waitForText('Мои черновики', 3, '.page-header');
		$I->waitForText($this->nameTender, 3, '#lotsList');
		$I->seeInTitle('Мои черновики');
		$I->wait(0.5);
	}


}