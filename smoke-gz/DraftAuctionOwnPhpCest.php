<?php

use yii\helpers\Url;

class DraftAuctionOwnPhpCest
{
	public function createSuccess(SmokeTester $I){

		$reqEnd = SmokeTester::$requestEnd;
		$aucDate = SmokeTester::$auctionTradeDate;
		$lotDeliveryT = SmokeTester::$lotDeliveryTerm;

		$I->amOnPage('/');
		$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeInTitle('Электронная торговая площадка');
        $I->click('Войти', 'li');

        $I->fillField(['name' => 'LoginForm[username]'], 'etp000442');
        $I->fillField(['name' => 'LoginForm[password]'], '11111111');
        $I->click('Войти' ,'button[name=login-button]');
        $I->see('etp000442', 'sup');

        $I->click('Создание закупки', '#w8 a');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeInTitle('Создание закупки');

        $I->submitForm('form', [
		    'tenderFundsType' => 'own',
		    'tenderType' => 'auction'
		], 'Продолжить');

		$I->seeInTitle('Создание электронного аукциона');

		$csrf = $I->grabAttributeFrom('meta[name=csrf-token]', 'content');
		$nameTender='Самолет'.mt_rand(0,99999).'(ownFunds)';

		$I->checkOption('#inds-inp-9'); //отрасль
		$I->fillField(['name' => 'title'], $nameTender); //название закупки
		$I->selectOption('select[name=hold_by]', 'customer'); //заказчик
		$I->fillField(['name' => 'request_end'], $reqEnd); //дата окончания приема предложений
		$I->fillField(['name' => 'auctionTradeDate'], $aucDate); //дата проведения торгов
		$I->fillField(['name' => 'participator_demand'], 'Требования123'); //требования к участникам
		$I->fillField(['name' => 'others'], 'Иные123'); //иные сведения

		$I->fillField(['id' => 'lotDescription'], 'ЛотОдин'); //название лота, добавлен в код параметр name
		$I->fillField(['name' => 'lotOkrb'], '01.11.11.110'); //код ОКРБ
		$I->selectOption('select[id=lotSubjectType]', 'product'); //товар-услуга, добавлен в код параметр name
		$I->fillField(['id' => 'count'], '777'); //количество, добавлен в код параметр name
		$I->selectOption('select[name=unit]', '001'); //еденица измерения
		$I->fillField(['name' => 'lotDeliveryTermFrom'], $lotDeliveryT); //поставка с
		$I->fillField(['name' => 'lotDeliveryTermTo'], $lotDeliveryT); //поставка по
		$I->fillField(['id' => 'lotStartPrice'], '100'); //ориентировочная стоимость, добавлен в код параметр name
		$I->fillField(['id' => 'lotCalcMethod'], 'аванс'); //ориентировочная стоимость, добавлен в код параметр name
		$I->click('Сохранить', '#add-lot-btn');
		//все что выше просто проверяет вводятся ли данные в поля, сама инфа не будет отправлена, отправка будет через запрос ниже

		
		//не можем нажать кнопку "сохранить черновик" , поэтому отправляем запрос с данными формы напряму, имитируя нажатие, данные выше и в этом запросе должны совпадать, т.к. не важно чт омы заполняли выше, отправиться только то что в запросе ниже.
		$I->sendAjaxPostRequest('http://test.local/auction/create?funds=own', [
			'_csrf'=>$csrf,
			'id'=>"",
			'created'=>"",
			'posted'=>"",
			'type'=>"Auction",
			'operator_site'=>"http://test.local/",
			'dataOperator'=>"РУП+\"Национальный+центр+маркетинга+и+конъюнктуры+цен\"\r\n220004,+г.+Минск,+пр-т+Победителей,+7,+к.+1117\r\nУНП+101223447\r\ninfo@goszakupki.by\r\nhttp://www.goszakupki.by/",
			'industry'=>"9",
			'title'=>$nameTender,
			'hold_by'=>"customer",
			'organizer_data'=>"проводится+заказчиком",
			'organizer_contacts'=>"проводится+заказчиком",
			'organizer_salary'=>"проводится+заказчиком",
			'customer_name'=>"Индивидуальный+предприниматель+Мастеровой+Евгений+Францевич",
			'customer_address'=>"Республика+Беларусь,+Гродненская+область,+222000,+г.+Лида,+ул.+Советская+12,+кв.+7",
			'customer_unp'=>"333287369",
			'workers'=>"Мастеровой+Евгений+Францевич,++375171111112",
			'request_end'=>$reqEnd,
			'auctionTradeDate'=>$aucDate,
			'participator_demand'=>"йкцйк",
			'others'=>"цкйцк",
			'maskInit'=>"",
			'lotOkrb'=>"01.11.11.120",
			'unit'=>"001",
			'lotDeliveryTermFrom'=>$lotDeliveryT,
			'lotDeliveryTermTo'=>$lotDeliveryT,
			'pricebyprop'=>"0",
			'funds'=>"2",
			'repeated'=>"0",
			'filesData'=>"[]",
			'customers'=>"[]",
			'lotsData'=>"[{\"id\":\"1\",\"description\":\"лот1\",\"okrb\":\"01.11.11.110\",\"subject_type\":\"product\",\"count\":100,\"unit\":\"001\",\"delivery\":\"Республика+Беларусь,+Гродненская+область,+222000,+г.+Лида,+ул.+Советская+12,+кв.+7\",\"delivery_from\":\"$lotDeliveryT\",\"delivery_to\":\"$lotDeliveryT\",\"auction_date\":\"$aucDate+09:00\",\"price\":\"100\",\"pricebyprop\":\"0\",\"deposit\":0,\"contract_deposit\":0,\"calc_method\":\"йцукен\",\"finance_source\":\"14\",\"forSmallScaleBusiness\":\"0\",\"canServicePref\":0,\"auction_id\":null,\"best_price\":0,\"winner\":null,\"admission_decision\":0,\"participants\":0,\"pp_created\":0,\"bp_created\":0,\"win_protocol\":0,\"contract\":0,\"contract_with\":\"\",\"end_date\":0,\"num\":\"1\"}]",
			'lotsItemsWithBcostUp'=>"[]",
			'lotsItemsWithBcostUpdate'=>"[]",
			'submitType'=>"saveDraft"
		]);

		//проверяем что данные закупки заполнены
		$erorTenderForm=['Необходимо заполнить «Отрасль».',
					'Необходимо заполнить «Наименование организации».',
					'Необходимо заполнить «Место нахождения организации».',
					'Необходимо заполнить «УНП организации».',
					'Необходимо заполнить «Требования к участникам, включая перечень документов',
					'Необходимо заполнить «Кем проводится закупка».',
					'Необходимо заполнить «Название процедуры закупки».',
					'Не было добавлено ни одного лота',
					'Дата окончания приема предложений раньше минимально допустимой.',
					'Значение «Дата окончания приема предложений» должно быть целым числом.',
					'Минимально допустимая дата торгов'];
		foreach ($erorTenderForm as $key) {
			$I->dontSee($key);
		};

		//проверяет что данные лота заполнены
		$erorLotForm=['Необходимо заполнить «Предмет закупки».',
					'Необходимо заполнить «Способ расчетов».',
					'Необходимо заполнить «Единица измерения».',
					'Необходимо заполнить «Код предмета закупки по ОКРБ».',
					'Необходимо заполнить «Вид предмета закупки».',
					'Неверный формат значения поля «Срок поставки c»',
					'Дата начала поставки не должна быть раньше даты окончания приема предложений',
					'Неверный формат значения поля «Срок поставки по»',
					'Начальная цена должна быть числом больше 0',
					'Количество должно быть больше нуля.',
					'отсутствует в справочнике',];
		foreach ($erorLotForm as $key) {
			$I->dontSee($key);
		};

		//проверяем, что закупка с таким именем появилась в черновиках
		$I->amOnPage('/tenders/drafts');
		$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeInTitle('Мои черновики');
        $I->see($nameTender, '#lotsList');
	}

}