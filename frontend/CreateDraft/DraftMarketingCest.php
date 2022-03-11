<?php

use yii\helpers\Url;

class DraftMarketingCest
{
	public function createDraftSuccess(AcceptanceTester $I){

		$I->loginGzWebDriver('etp000159','11111111');
        $I->click('Личный кабинет');
        $I->click('Создание закупки');

        $I->seeInTitle('Создание закупки');
        $I->submitForm('form', [
		    'tenderFundsType' => 'budget',
		    'tenderType' => 'marketing'
		], 'Продолжить');

		$I->waitForText('Создание запроса о предоставлении сведений в целях изучения конъюнктуры рынка для процедуры закупки из одного источника', 5, '.page-header');

		$I->seeInTitle('Создание запроса о предоставлении сведений в целях изучения конъюнктуры рынка для процедуры закупки из одного источника');
		$I->click('Выбрать');
		$I->waitForText('Авиация', 5, '.closed');
		$I->click('+');
		$I->checkOption('#inds-inp-9'); //отрасль

		$nameTender = 'Самолет'.mt_rand(0,99999);
		$I->fillField(['name' => 'title'], $nameTender); //название закупки
		$I->selectOption('select[name=hold_by]', 'customer'); //заказчик

		$I->fillField(['name' => 'request_end'], AcceptanceTester::$requestEnd); //дата окончания приема предложений
		$I->fillField(['name' => 'participator_demand'], 'Порядок предоставления123'); //Порядок предоставления
		$I->fillField(['name' => 'others'], 'ПереченьДоков123'); //перечень доков

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
		$I->fillField(['id' => 'lotCalcMethod'], 'аванс'); //Порядок оплаты
		$I->selectOption('select[id=finance_source]', '1'); //исочник финансирования

		$I->click('#add-lot-btn');
		$I->waitForText('Новый лот', 3, '#new-lot-btn');
		$I->click('#saveDraft-btn');

		$I->waitForText('Мои черновики', 3, '.page-header');
		$I->waitForText($nameTender, 3, '#lotsList');
		$I->seeInTitle('Мои черновики');
		$I->wait(0.5);

	}


}