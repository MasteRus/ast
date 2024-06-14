<?php

namespace tests\unit\models;

use app\models\activeRecord\Event;
use app\modules\admin\models\forms\EventForm;
use app\tests\unit\fixtures\EventFixture;
use app\tests\unit\fixtures\OrganizatorFixture;
use Codeception\Test\Unit;
use UnitTester;

class EventFormTest extends Unit
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

    public function testCreateEventSuccess(): void
    {
        $eventForm = new EventForm();
        $eventName = 'new event';
        $eventForm->name = $eventName;
        $eventForm->description = 'Desc1';
        $eventForm->planned_date = '2024-12-31';
        $eventForm->organizatorIds = ['1', '2'];

        $this->assertTrue($eventForm->validate());
        $result = $eventForm->save();

        $this->assertNotEquals(0, $result, 'Create event error');
        $createdEvent = Event::findOne(['name' => $eventName]);
        $this->assertNotNull($createdEvent, 'Created event not found');
    }

    public function testCreateEventNotExistedOrgError(): void
    {
        $eventForm = new EventForm();
        $eventName = 'new event for test';
        $eventForm->name = $eventName;
        $eventForm->description = 'Desc1';
        $eventForm->planned_date = '2024-12-31';
        $eventForm->organizatorIds = ['1', '3'];
        $this->assertFalse($eventForm->validate());
    }

    public function testCreateEventDateError(): void
    {
        $eventForm = new EventForm();
        $eventName = 'new event for test';
        $eventForm->name = $eventName;
        $eventForm->description = 'Desc1';
        $eventForm->planned_date = '2024-13-31';
        $eventForm->organizatorIds = ['1'];
        $this->assertFalse($eventForm->validate());
    }

    public function testUpdateEventSuccess(): void
    {
        $event = $this->tester->grabFixture('events', 'event1');
        $eventForm = new EventForm();
        $eventForm->id = $event->id;

        $expectedName = 'new event UPDATED';
        $eventForm->name = $expectedName;

        $eventForm->description = 'new event desc UPDATED';
        $eventForm->planned_date = '2025-01-01';
        $eventForm->organizatorIds = [1];
        $result = $eventForm->save();

        $this->assertNotEquals(0, $result, 'Update event error');
        $updatedEvent = Event::findOne($eventForm->id);

        $this->assertEquals($expectedName, $updatedEvent->name, 'Event name not updated');
        $this->assertCount(1, $updatedEvent->organizators, 'Event name not updated');
        $this->assertEquals(1, $updatedEvent->organizators[0]->id, 'Event name not updated');
    }

    public function testUpdateEventNotExistedOrgError(): void
    {
        $event = $this->tester->grabFixture('events', 'event1');
        $eventForm = new EventForm();
        $eventForm->id = $event->id;

        $expectedName = 'new event UPDATED';
        $eventForm->name = $expectedName;

        $eventForm->description = 'new event desc UPDATED';
        $eventForm->planned_date = '2025-01-01';
        $eventForm->organizatorIds = [3];

        $this->assertFalse($eventForm->validate());
    }

    public function testUpdateEventDateError(): void
    {
        $event = $this->tester->grabFixture('events', 'event1');
        $eventForm = new EventForm();
        $eventForm->id = $event->id;

        $expectedName = 'new event UPDATED';
        $eventForm->name = $expectedName;

        $eventForm->description = 'new event desc UPDATED';
        $eventForm->planned_date = '2025-13-33';
        $eventForm->organizatorIds = [1];
        $this->assertFalse($eventForm->validate());
    }
}