<?php

namespace unit\models;

use app\models\activeRecord\Organizator;
use app\modules\admin\models\forms\OrganizatorForm;
use app\tests\unit\fixtures\EventFixture;
use app\tests\unit\fixtures\OrganizatorFixture;
use Codeception\Test\Unit;
use UnitTester;

class OrganizatorFormTest extends Unit
{
    protected UnitTester $tester;

    protected function _before(): void
    {
        $this->tester->haveFixtures(
            [
                'events'       => [
                    'class'    => EventFixture::class,
                    'dataFile' => codecept_data_dir() . 'event.php'
                ],
                'organizators' => [
                    'class'    => OrganizatorFixture::class,
                    'dataFile' => codecept_data_dir() . 'organizator.php'
                ]
            ]
        );
    }

    public function testCreateOrganizatorSuccess(): void
    {
        $organizatorForm = new OrganizatorForm();
        $name = 'new organizator';
        $organizatorForm->fullname = $name;
        $email = '1@mail.local';
        $organizatorForm->email = $email;
        $organizatorForm->phone = '+7-999-987-65-43';
        $organizatorForm->eventIds = ['1', '2'];

        $this->assertTrue($organizatorForm->validate());
        $result = $organizatorForm->save();

        $this->assertNotEquals(0, $result, 'Create organizator error');
        $createdOrganizator = Organizator::findOne(
            [
                'fullname' => $name,
                'email'    => $email
            ]
        );
        $this->assertNotNull($createdOrganizator, 'Created organizator not found');
    }

    public function testCreateOrganizatorNotExistedOrgError(): void
    {
        $organizatorForm = new OrganizatorForm();
        $organizatorForm->fullname = 'new organizator for test';
        $organizatorForm->email = '1@mail.local';
        $organizatorForm->phone = '+7-999-987-65-43';
        $organizatorForm->eventIds = ['1', '3'];
        $this->assertFalse($organizatorForm->validate());
        $this->assertArrayHasKey('eventIds',$organizatorForm->errors);
    }

    public function testCreateOrganizatorDuplicatedEmailError(): void
    {
        $organizatorForm = new OrganizatorForm();
        $organizatorForm->fullname = 'new organizator for test';
        $organizatorForm->email = 'coolband@mail.local';
        $organizatorForm->phone = '+7-999-987-65-43';
        $organizatorForm->eventIds = ['1'];
        $this->assertFalse($organizatorForm->validate());
        $this->assertArrayHasKey('email',$organizatorForm->errors);
        $this->assertSame($organizatorForm->errors['email'][0], 'This email already used');
    }

    public function testCreateOrganizatorBadEmailEmailError(): void
    {
        $organizatorForm = new OrganizatorForm();
        $organizatorForm->fullname = 'new organizator for test';
        $organizatorForm->email = 'coolband@mai@l.local';
        $organizatorForm->phone = '+7-999-987-65-43';
        $organizatorForm->eventIds = ['1'];
        $this->assertFalse($organizatorForm->validate());
        $this->assertArrayHasKey('email',$organizatorForm->errors);
        $this->assertSame($organizatorForm->errors['email'][0], 'email is not a valid email address.');
    }

    public function testUpdateOrganizatorSuccess(): void
    {
        $organizator = $this->tester->grabFixture('organizators', 'org1');
        $organizatorForm = new OrganizatorForm();
        $organizatorForm->id = $organizator->id;

        $expectedName = 'new organizator UPDATED';
        $organizatorForm->fullname = $expectedName;

        $email = '1@mail.local';
        $organizatorForm->email = $email;
        $organizatorForm->phone = '+7-999-987-65-43';
        $organizatorForm->eventIds = ['1'];

        $result = $organizatorForm->save();

        $this->assertNotEquals(0, $result, 'Update organizator error');
        $updatedOrganizator = Organizator::findOne($organizatorForm->id);

        $this->assertEquals($expectedName, $updatedOrganizator->fullname, 'Organizator name not updated');
        $this->assertEquals($email, $updatedOrganizator->email, 'Organizator name not updated');
        $this->assertCount(1, $updatedOrganizator->events, 'Organizator name not updated');
        $this->assertEquals(1, $updatedOrganizator->events[0]->id, 'Organizator name not updated');
    }

    public function testUpdateOrganizatorNotExistedOrgError(): void
    {
        $organizator = $this->tester->grabFixture('organizators', 'org1');
        $organizatorForm = new OrganizatorForm();
        $organizatorForm->id = $organizator->id;

        $organizatorForm->fullname = 'new organizator UPDATED';
        $organizatorForm->email = '1@mail.local';
        $organizatorForm->phone = '+7-999-987-65-43';
        $organizatorForm->eventIds = [3];

        $this->assertFalse($organizatorForm->validate());
        $this->assertArrayHasKey('eventIds',$organizatorForm->errors);
    }

    public function testUpdateOrganizatorBadEmailError(): void
    {
        $organizator = $this->tester->grabFixture('organizators', 'org1');
        $organizatorForm = new OrganizatorForm();
        $organizatorForm->id = $organizator->id;

        $organizatorForm->email = 'coolband@mai@l.local';
        $organizatorForm->phone = '+7-999-987-65-43';
        $organizatorForm->eventIds = ['1'];
        $this->assertFalse($organizatorForm->validate());
        $this->assertArrayHasKey('email',$organizatorForm->errors);
        $this->assertSame($organizatorForm->errors['email'][0], 'email is not a valid email address.');
    }

    public function testUpdateOrganizatorNotUniqueEmailError(): void
    {
        $organizator = $this->tester->grabFixture('organizators', 'org1');
        $organizatorForm = new OrganizatorForm();
        $organizatorForm->id = $organizator->id;

        $organizatorForm->email = 'club27@mail.local';
        $organizatorForm->phone = '+7-999-987-65-43';
        $organizatorForm->eventIds = ['1'];
        $this->assertFalse($organizatorForm->validate());
        $this->assertArrayHasKey('email',$organizatorForm->errors);
        $this->assertSame($organizatorForm->errors['email'][0], 'This email already used');
    }
}