<?php

use yii\helpers\Url;

class SearchTendersCest
{
	public $gzNum1 = 'auc0000002807';
	public $iceNum1 = '2021-629347';
	public $nameNum1 = 'Тыква100';
	public $companyName1 = 'Тестовая компания "Брест (+)"';
	public $companyUnp1 = '100864035';

	public $gzNum2 = 'auc0000002816';
	public $giasNum2 = '4302';
	public $nameNum2 = 'Яблоки88';

    public function searchMainForGzNum(SmokeTester $I)
    {
        $I->amOnPage('/tenders/posted');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeInTitle('Объявленные закупки');
        $I->see('Объявленные закупки', '.page-header h1');

        $I->seeElement('form', ['id' => 'filter-form']);
        $I->fillField(['name' => 'TendersSearch[num]'], $this->gzNum1);
        $I->click('#filter-form button[type=submit]');

        $I->see($this->gzNum1, 'tr[data-key=0]');
        $I->see($this->companyName1, '.word-break');
        $I->see($this->nameNum1, '.word-break a');
        $I->dontSeeElement('tr', ['data-key' => '1']);
    }

    public function searchMainForIceNum(SmokeTester $I)
    {
    	$I->amOnPage('/tenders/posted');
    	$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);

    	$I->seeElement('form', ['id' => 'filter-form']);
        $I->fillField(['name' => 'TendersSearch[iceGiasNum]'], $this->iceNum1);
        $I->click('#filter-form button[type=submit]');

        $I->see('в ИС «Тендеры»:', '.text-muted');
        $I->see($this->gzNum1, 'tr[data-key=0]');
        $I->see($this->iceNum1, 'tr[data-key=0]');
        $I->see($this->nameNum1, '.word-break a');
        $I->dontSeeElement('tr', ['data-key' => '1']);

        $I->dontSee('Ничего не найдено', '.empty');  	
    }

    public function searchMainForGiasNum(SmokeTester $I)
    {
    	$I->amOnPage('/tenders/posted');
    	$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);

    	$I->seeElement('form', ['id' => 'filter-form']);
        $I->fillField(['name' => 'TendersSearch[iceGiasNum]'], $this->giasNum2);
        $I->click('#filter-form button[type=submit]');

        $I->see('в ГИАС:', '.text-muted');
        $I->see($this->gzNum2, 'tr[data-key=0]');
        $I->see($this->giasNum2, 'tr[data-key=0]');
        $I->see($this->nameNum2, '.word-break a');
        $I->dontSeeElement('tr', ['data-key' => '1']);
    }

    public function searchMainForTenderName(SmokeTester $I)
    {
    	$I->amOnPage('/tenders/posted');
    	$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);

    	$I->seeElement('form', ['id' => 'filter-form']);
        $I->fillField(['name' => 'TendersSearch[text]'], $this->nameNum1);
        $I->click('#filter-form button[type=submit]');

        $I->see($this->nameNum1, '.word-break a');
    }

    public function searchExtensionForType(SmokeTester $I)
    {
    	$I->amOnPage('/tenders/posted');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);

        $I->seeElement('select', ['id' => 'tenderssearch-type']);
        $I->selectOption('form select[id=tenderssearch-type]', 'Запрос ценовых предложений');
        $I->click('#filter-form button[type=submit]');

        $I->see('Запрос ценовых предложений', '.grid-view td');

        $arrType = ['Электронный аукцион','Открытый конкурс','Закупка из одного источника','Конкурс с ограниченным участием','Двухэтапный конкурс'];
        foreach ($arrType as $key) { $I->dontSee($key, '.grid-view td'); };
    }

    public function searchExtensionForStatus(SmokeTester $I)
    {
    	$I->amOnPage('/tenders/posted');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);

        $I->seeElement('select', ['id' => 'tenderssearch-status']);
        $I->selectOption('form select[id=tenderssearch-status]', 'Завершен');
        $I->click('#filter-form button[type=submit]');

        $arrStatus = ['Отменен','Признан несостоявшимся','Подача предложений','Ожидание торгов','Рассмотрение предложений','Проводятся торги','Определение победителя','Подписание договора','Предварительный отбор','Утверждение конкурсных документов','Приостановлен'];

        foreach ($arrStatus as $key) { $I->dontSee($key, '.badge'); };
        $I->see('Завершен', '.badge');      
    }

    public function searchExtensionForDateRequestEnd(SmokeTester $I)
    {
    	$I->amOnPage('/tenders/posted');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);

        $I->seeElement('input', ['id' => 'tenderssearch-request_end_from']);
        $I->fillField(['name' => 'TendersSearch[request_end_from]'], '01.09.2021');
        $I->fillField(['name' => 'TendersSearch[request_end_to]'], '01.09.2021');
        $I->click('#filter-form button[type=submit]');

        $I->see('01.09.2021', '.grid-view td');
        for($i=2; $i<=9; $i++){ $I->dontSee('0'.$i.'.09.2021', '.grid-view td'); };
        for($i=10; $i<=30; $i++){ $I->dontSee($i.'.09.2021', '.grid-view td'); };
    }

	public function searchExtensionForPrice(SmokeTester $I)
    {
    	$from= 5;
    	$to= 10;

    	$I->amOnPage('/tenders/posted');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);

        $I->seeElement('input', ['id' => 'tenderssearch-price_from']);
        $I->fillField(['name' => 'TendersSearch[price_from]'], $from);
        $I->fillField(['name' => 'TendersSearch[price_to]'], $to);
        $I->click('#filter-form button[type=submit]');

        $arrPrice = $I->grabMultiple('tr td:last-child');
        //codecept_debug($arrPrice);
        foreach ($arrPrice as $key) {
        	if($key>=$from && $key<=$to){ $I->see(true); } 
        	else { return $I->see(false); }
        };
    }

    public function searchExtensionForUnpOnlyFirst(SmokeTester $I)
    {
    	$I->amOnPage('/tenders/posted');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);

        $I->seeElement('form', ['id' => 'filter-form']);
        $I->fillField(['name' => 'TendersSearch[unp]'], $this->companyUnp1);
        $I->click('#filter-form button[type=submit]');

        $element= $I->grabAttributeFrom('tbody tr:first-child', 'data-key'); // '0'-have or NULL-notHave

  		if($element == '0'){
	  		$I->click('.word-break a');
	        $I->seeInTitle('Просмотр электронного аукциона');
	        $I->seeInSource('<td>100864035</td>');
	  		}   
        }    
}
