<?php

use yii\helpers\Url;

class CreateReference10LotCest
{
	public $shortNameTender;

	public function createDraftReferenceSuccess(AcceptanceTester $I){

		$I->loginGzWebDriver('etp000159','11111111');
        $I->click('Личный кабинет');
        $I->click('Разместить справку ЗОИ');

		$I->waitForText('Создание справки о проведении процедуры закупки из одного источника', 5, '.page-header');
		$I->seeInTitle('Создание справки о проведении процедуры закупки из одного источника');
		$I->click('Выбрать');
		$I->waitForText('Авиация', 5, '.closed');
		$I->click('+');
		$I->checkOption('#inds-inp-9'); //отрасль

		$I->fillField(['name' => 'SingleSourceReference[date_sign]'], '01.11.2021'); //дата утверждения
		$I->fillField(['name' => 'SingleSourceReference[signer_description]'], 'Андреев Андрей Андреевич'); //сведение о лице утвердившем
		$I->click('.select2-selection__rendered');
		$I->wait(0.5);	
		$I->click('#select2-singlesourcereference-type_cause-results li:first-child'); //основания выбора процедуры

		$this->shortNameTender = 'Самолет_10лотов_'.mt_rand(0,99999);
		$I->fillField(['name' => 'SingleSourceReference[subject_description]'], $this->shortNameTender); //краткое описание
		$I->selectOption('select[id=singlesourcereference-hold_by]', 'customer'); //заказчик
		$I->fillField(['name' => 'SingleSourceReference[provision_requirements]'], 'Требование о предоставлении обеспечения123');
		$I->fillField(['name' => 'SingleSourceReference[participants_requirements]'], 'Требования к участникам123');

		//добавляем лоты
		for($i=0; $i<10; $i++){
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

		//заполняем Сведения об изучении рынка
		for($i=0; $i<10; $i++){
			$I->fillField(['id' => "marketresearch-{$i}-lot_reference_id"], $i+1); //номер лота п/п
			$I->fillField(['id' => "marketresearch-{$i}-unp"], '12345678'); //унп поставщика
			$I->fillField(['id' => "marketresearch-{$i}-name"], 'Организация1'); //наименование организации
			$I->selectOption("select[id=marketresearch-{$i}-country]", '1'); //заказчик
			$I->selectOption("select[id=marketresearch-{$i}-region]", '7'); //заказчик
			$I->fillField(['id' => "marketresearch-{$i}-postindex"], '220112');
			$I->fillField(['id' => "marketresearch-{$i}-city"], 'Минск');
			$I->fillField(['id' => "marketresearch-{$i}-address"], 'проспект Победителей');
			$I->fillField(['id' => "marketresearch-{$i}-price"], '1');
			if($i<9){
				$I->click('.add-market-research');
			};		
		};

		$I->click('#saveDraft-btn');

		$I->waitForText('Справка о проведении процедуры закупки из одного источника успешно создана', 3, '#w12-success-0');
		$I->seeInTitle('Просмотр справки ЗОИ №');
		$I->see($this->shortNameTender ,'td');
		$I->wait(0.5);
	}

}