<?php

use yii\helpers\Url;

class DeleteCest
{
	public function deleteUpDraft(SmokeTester $I){
		$I->loginGz('etp000442', '11111111');

		$I->amOnPage('/tenders/drafts');
		$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
                $I->seeInTitle('Мои черновики');
                $I->expectTo('see trash buttons');
                $trashLinks = $I->grabMultiple('.btn-xs', 'href');

        if(count($trashLinks)>0){
        	$I->amOnPage($trashLinks[0]);
        	$trashLinksUpdate = $I->grabMultiple('.btn-xs', 'href');
        	((count($trashLinksUpdate)+1) == count($trashLinks)) ? $I->expect('operation success') : $I->expect('NOT delete');
        	$I->see('Закупка успешно удалена', '#w9-success-0');  
        } else{
        	$I->expect('user not have drafts');
        	$I->see('Нет данных для отображения', '.alert-warning');
        };

	}
}