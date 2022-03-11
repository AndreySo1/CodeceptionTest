<?php

use yii\helpers\Url;

class CreateReferenceCest
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

		$this->shortNameTender = 'Самолет'.mt_rand(0,99999);
		$I->fillField(['name' => 'SingleSourceReference[subject_description]'], $this->shortNameTender); //краткое описание
		$I->selectOption('select[id=singlesourcereference-hold_by]', 'customer'); //заказчик
		$I->fillField(['name' => 'SingleSourceReference[provision_requirements]'], 'Требование о предоставлении обеспечения123');
		$I->fillField(['name' => 'SingleSourceReference[participants_requirements]'], 'Требования к участникам123');

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

		$I->fillField(['id' => 'marketresearch-0-lot_reference_id'], '1'); //номер лота п/п
		$I->fillField(['id' => 'marketresearch-0-unp'], '12345678'); //унп поставщика
		$I->fillField(['id' => 'marketresearch-0-name'], 'Организация1'); //наименование организации
		$I->selectOption('select[id=marketresearch-0-country]', '1'); //заказчик
		$I->selectOption('select[id=marketresearch-0-region]', '7'); //заказчик
		$I->fillField(['id' => 'marketresearch-0-postindex'], '220112');
		$I->fillField(['id' => 'marketresearch-0-city'], 'Минск');
		$I->fillField(['id' => 'marketresearch-0-address'], 'проспект Победителей');
		$I->fillField(['id' => 'marketresearch-0-price'], '1');

		$I->click('#saveDraft-btn');

		$I->waitForText('Справка о проведении процедуры закупки из одного источника успешно создана', 3, '#w12-success-0');
		$I->seeInTitle('Просмотр справки ЗОИ №');
		$I->see($this->shortNameTender ,'td');
		$I->wait(0.5);
	}

	public function draftReferenceAddContract(AcceptanceTester $I){
		$I->loginGzWebDriver('etp000159','11111111');
        $I->click('Личный кабинет');
        $I->click('Мои справки ЗОИ');

        $I->waitForText('Мои справки ЗОИ', 3, '.panel-title');
        //$I->see($this->shortNameTender ,'tbody tr:first-child');
        $I->see($this->shortNameTender ,'tbody tr:first-child');
        $I->click('a[title=Просмотр]');
        $I->waitForText('Общие сведения о процедуре закупки из одного источника', 3, '.pull-left');
        $I->click('Добавить сведения о договоре');
        $I->waitForText('Создание сведения о договоре', 3, '.panel-title');

        $I->selectOption('select[id=contractsreferences-lot_id]', '1'); //номер лота
        $I->fillField(['id' => 'contractsreferences-contract_num'], '111'); //номер договора
        $I->fillField(['id' => 'contractsreferences-contract_date'], '01.11.2021'); //дата заключения
        $I->fillField(['id' => 'contractsreferences-contract_price'], '1'); //цена заключенного договора

        $I->selectOption('select[id=sellers]', '1');
        $I->checkOption('#contractsreferences-accordance_conclusion');
        
        $I->click('#addContractItemBtn');
        $I->waitForText('Добавить позицию договора', 3, '.modal-header');
        $I->selectOption('select[name=lotGiasId]', '1'); //лот
        $I->fillField(['name' => 'count'], '1'); //количество едениц
        $I->fillField(['name' => 'positionPrice'], '1'); //общая стоимость
        $I->pressKey('#itemCountries', 'а', 'ф', 'г', \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);//страна
        $I->waitForText('Афганистан (AF)', 3, '#prodCountriesList');
        $I->click('#saveContractItemBtn');
        $I->waitForText('№ строки годового плана:', 3, '.list-group-item');
        $I->fillField(['name' => 'SingleSourceReference[comment]'], 'Примечание123'); //примечание
        $I->wait(0.5);
        $I->click('Сохранить в черновик');

        $I->waitForText('Сведения о договоре успешно сохранены', 3, '#w12-success-0');
	}


}