<?php

use yii\helpers\Url;

class CreatePurchasesCest
{
	//public $linkPurchases;
	//создаем черновик
	public function createDraftPurchasesSuccess(AcceptanceTester $I){

		$I->loginGzWebDriver('etp000442','11111111');
        $I->click('Личный кабинет');
        $I->click('Разместить план закупок');
		$I->waitForText('План закупок', 5, '.panel-title');
		$I->seeInTitle('Создать план закупок');

		$I->fillField(['name' => 'Purchases[signer_descrip]'], 'Андреев Андрей Андреевич'); //сведение о лице утвердившем		
		$I->fillField(['name' => 'Purchases[date_sign]'], '01.11.2021'); //дата утверждения
		$I->selectOption('select[id=purchases-year]', '2021'); //год
		$I->click('Сохранить в черновик');
		$I->waitForText('План закупок успешно создан', 5, '#w12-success-0');

		/*$this->linkPurchases = $I->grabFromCurrentUrl();
		codecept_debug($this->linkPurchases); //ссылка конкретного плана*/ 

		$I->click('Добавить новую позицию');
		$I->waitForText('Создать позицию плана закупок', 5, '.panel-title');
		$I->fillField(['name' => 'PurchasesItems[title]'], 'Самолет одноместный'); //имя позиции
		$I->pressKey('#purchasesitems-title', \Facebook\WebDriver\WebDriverKeys::TAB, '0', '1', '1', '1', '1', '1', '1', '1', '0');//код ОКРБ
		$I->selectOption('select[id=purchasesitems-type]', 'product'); //предмет заккупки
		$I->fillField(['name' => 'PurchasesItems[val_amount]'], '100'); //ориент объем
		$I->click('#select2-purchasesitems-val_type-container');//еденица измерения
		$I->click('.select2-results__option');//еденица измерения

		$I->fillField(['id' => 'budgetcostpurchitems-0-cost'], '100'); // объем
		$I->pressKey('#budgetcostpurchitems-0-cost', \Facebook\WebDriver\WebDriverKeys::TAB, '0', '1', '0', '0', '0', '0', '0', '0', '0');//код функциональный
		$I->pressKey('#budgetcostpurchitems-0-functional_code', \Facebook\WebDriver\WebDriverKeys::TAB, '0', '0', '1');//код ведомственн
		$I->pressKey('#budgetcostpurchitems-0-department_code', \Facebook\WebDriver\WebDriverKeys::TAB, '1', '0', '0', '0', '0', '0', '0');//код экономической
		$I->pressKey('#budgetcostpurchitems-0-economic_code', \Facebook\WebDriver\WebDriverKeys::TAB, '0', '0', '1', '0', '5');//код программной
		$I->selectOption('select[id=purchasesitems-procedure_months]', '4');
		$I->click('Сохранить в черновик');
		$I->waitForText('Позиция плана закупок успешно добавлена', 5, '#w12-success-0');
		$I->click('Выйти');
	}

	//наполняем план лотами
	public function addPositionsInPurchases(AcceptanceTester $I){
		$I->loginGzWebDriver('etp000442','11111111');
        $I->click('Личный кабинет');
        $I->click('Мои планы закупок');
		$I->waitForText('Мои планы закупок', 5, '.panel-title');
		$I->dontSee('Ничего не найдено.', '.empty'); //проверяем есьт ли хоть один план
		$I->click('Просмотр');

		for($i=0; $i<3; $i++){
			$I->click('Добавить новую позицию');
			$I->waitForText('Создать позицию плана закупок', 5, '.panel-title');
			$I->fillField(['name' => 'PurchasesItems[title]'], 'Самолет'.$i); //имя позиции
			$I->pressKey('#purchasesitems-title', \Facebook\WebDriver\WebDriverKeys::TAB, '0', '1', '1', '1', '1', '1', '1', '1', '0');//код ОКРБ
			$I->selectOption('select[id=purchasesitems-type]', 'product'); //предмет заккупки
			$I->fillField(['name' => 'PurchasesItems[val_amount]'], '100'); //ориент объем
			$I->click('#select2-purchasesitems-val_type-container');//еденица измерения
			$I->click('.select2-results__option');//еденица измерения

			$I->fillField(['id' => 'budgetcostpurchitems-0-cost'], '100'); // объем
			$I->pressKey('#budgetcostpurchitems-0-cost', \Facebook\WebDriver\WebDriverKeys::TAB, '0', '1', '0', '0', '0', '0', '0', '0', '0');//код функциональный
			$I->pressKey('#budgetcostpurchitems-0-functional_code', \Facebook\WebDriver\WebDriverKeys::TAB, '0', '0', '1');//код ведомственн
			$I->pressKey('#budgetcostpurchitems-0-department_code', \Facebook\WebDriver\WebDriverKeys::TAB, '1', '0', '0', '0', '0', '0', '0');//код экономической
			$I->pressKey('#budgetcostpurchitems-0-economic_code', \Facebook\WebDriver\WebDriverKeys::TAB, '0', '0', '1', '0', '5');//код программной
			$I->selectOption('select[id=purchasesitems-procedure_months]', '4');
			$I->click('Сохранить в черновик');
			$I->waitForText('Позиция плана закупок успешно добавлена', 5, '#w12-success-0');
		};
		$I->click('Выйти');
		//$I->amOnPage($this->linkPurchases);
	}

	//удаляем созданный черновик плана
	public function deleteDraftPurchases(AcceptanceTester $I){
		$I->loginGzWebDriver('etp000442','11111111');
        $I->click('Личный кабинет');
        $I->click('Мои планы закупок');
		$I->waitForText('Мои планы закупок', 5, '.panel-title');
		$I->dontSee('Ничего не найдено.', '.empty'); //проверяем есьт ли хоть один план
		$I->click('a[title=Удалить]');
		$I->acceptPopup();
		$I->waitForText('План закупок успешно удален', 5, '#w10-success-0');
	}

}