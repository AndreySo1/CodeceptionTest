<?php

use yii\helpers\Url;

class BrowsertestCestPhp
{
    public function browsertestWork(SmokeTester $I)
    {
        $I->amOnPage('/browsertest');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeInTitle('Проверка браузера');
        $csrf = $I->grabAttributeFrom('meta[name=csrf-token]', 'content');
        $I->see('Проверить', '#signMessage');

        $I->seeElement('select', ['id' => 'certificates']);
        $I->seeElement('option', ['value' => '0']);
        $I->see('Не выбран');

        // отправляем запрос, не факт что он работает, нооо, если работает в самом низу меняем проверку события
        $I->sendAjaxPostRequest('http://test.local/browsertest/check', [
            '_csrf'=>$csrf,
            'certData'=>[
                '1.2.643.6.3.1.2.1'=>"1.2.643.6.3.1.2.1",
                '1.2.643.6.3.1.4.3'=>"1.2.643.6.3.1.4.3",
                '1.3.6.1.4.1.12656.104.3'=>"612345678",
                '1.3.6.1.5.5.7.3.2'=>"1.3.6.1.5.5.7.3.2",
                '1.3.6.1.5.5.7.3.4'=>"1.3.6.1.5.5.7.3.4",
                'AUTHORITY_KEYID'=>"2DD8683B2AEEA401AEB1791303A0FD8BB3D43C61",
                'Address'=>"ул. Комсомольская, д.33",
                'Company'=>"РУП «Тестовая компания»",
                'CompanyExt'=>"Республиканское унитарное предприятие «Тестовая компания»",
                'Country'=>"BY",
                'Department'=>"ДИТ",
                'EXT_KEY_USAGE'=>["1.2.643.6.3.1.2.1", "1.2.643.6.3.1.4.3", "1.3.6.1.5.5.7.3.2", "1.3.6.1.5.5.7.3.4"],
                'Email'=>"test@gmail.com",
                'FirstName'=>"Иван Петрович",
                'ISSUER_AS_STRING'=>"CN=общие данные НЦМиКЦ, EMAIL=oit@icetrade.by, O=Тест НЦМиКЦ, STREET=Пр-т Победителей 7, L=Минск, ST=Минская, C=BY",
                'LastName'=>"Сидоров",
                'Locality'=>"Минск",
                'NOT_AFTER'=>"2022-08-30 23:59:59",
                'NOT_BEFORE'=>"2021-08-30 17:48:32",
                'PUBLICKEY'=>"30820124 30818F06 092B0601 0401E270 01230381 8102B61C 81A6B2F4 DE97CFB8 FFA9CB1F 54ABA7F4 90C4C8C8 FCBB33A6 44D239C3 4EAFC54A B3A20A70 F31AE303 CA7806FB B15CB2E3 4F5B8F42 FCA545A6 A7CEB2CA 7224CE2B 52A9E7E9 69487373 326D4EAD 5DE2EB12 792893CF 51699832 4C7D622E D031A6AD 89905FF8 07124C8B E54A937F CCD78B7C 58406746 C9F4BA38 5D973F40 8C703081 8F06092B 06010401 E2700120 03818102 CFD6517B E1D55DC4 6312199D 52AC4CC4 957E25A0 C45D0FD6 C84AD16D 8FF62C24 FB5C4C07 C9E4F288 88A19944 B1D66B15 AF50D125 1CEA5A6F 98997BC5 1642B7E8 33243073 319352C9 659280DB 2D78E8F3 FD186729 7586335B F8ED2A91 153BCD34 A71D47AF D469A616 F87F59EE 4AF1D913 29ABFBCC EB936A77 013B0C24 290E8E44 ",
                'Position'=>"Главный специалист",
                'PublicKeyId'=>"1294602CC65FA436F2521222553F23A0736ABC6C",
                'SERIALNUMBER'=>"40E5B2D7BECD8EFB00000403",
                'SUBJECT_AS_STRING'=>"CN=РУП «Тестовая компания», EMAIL=test@gmail.com, OU=ДИТ, O=Республиканское унитарное предприятие «Тестовая компания», STREET=\"ул. Комсомольская, д.33\", L=Минск, C=BY, OID.2.5.4.12=Главный специалист, OID.2.5.4.4=Сидоров, OID.2.5.4.41=Иван Петрович, OID.2.5.4.45=303231726000",
                'UNP'=>"303231726000",
                'isGossuokCert'=>false,
                'personalNumber'=>"45454545645PB4"
            ],
            'hash'=>"A557F840D936616580493B6FD904FE8C8129D7716BE12D6A66AA9F025A97D554",
            'message'=>"EFBBBF454642424246373436353733373436393665363732303664363537333733363136373635",
            'result'=>"OK"]);
        $I->seeResponseCodeIs(200);
        $I->see('Не удалось подключиться сервису работы с криптографическими средствами');
        //$I->see('Проверка успешно пройдена');

        //$I->selectOption('.form-group select[id=certificates]', '30.08.2021, Сидоров Иван Петрович (РУП <Тестовая компания)');
    }
}
