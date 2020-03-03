<?php

namespace Signifly\PivotEvents\Test;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Signifly\PivotEvents\Test\Models\Role;
use Signifly\PivotEvents\Test\Models\User;

class PivotEventsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_dispatches_pivot_attach_events()
    {
        Event::fake();

        $user = $this->createUser();
        $role = $this->createRole();

        $user->roles()->attach($role->id);

        $this->assertDatabaseHas('role_user', [
            'role_id' => $role->id,
            'user_id' => $user->id,
            'scopes' => null,
        ]);

        Event::assertDispatched('eloquent.pivotAttaching: '.User::class);
        Event::assertDispatched('eloquent.pivotAttached: '.User::class);
    }

    /** @test */
    public function it_receives_pivot_changes_for_attach_events()
    {
        $user = $this->createUser();
        $role = $this->createRole();

        $receivedRoleIds = collect();
        $this->assertCount(0, $receivedRoleIds);

        User::pivotAttaching(function ($model) use (&$receivedRoleIds) {
            $receivedRoleIds = $receivedRoleIds->merge(
                $model->getPivotChangeIds('attach', 'roles')
            );
        });

        $user->roles()->attach($role->id);

        $this->assertCount(1, $receivedRoleIds);

        $this->assertDatabaseHas('role_user', [
            'role_id' => $role->id,
            'user_id' => $user->id,
            'scopes' => null,
        ]);
    }

    /** @test */
    public function it_dispatches_pivot_detach_events()
    {
        Event::fake();

        $user = $this->createUser();
        $role = $this->createRole();

        $user->roles()->attach($role->id);
        $user->roles()->detach($role->id);

        $this->assertDatabaseMissing('role_user', [
            'role_id' => $role->id,
            'user_id' => $user->id,
        ]);

        Event::assertDispatched('eloquent.pivotDetaching: '.User::class);
        Event::assertDispatched('eloquent.pivotDetached: '.User::class);
    }

    /** @test */
    public function it_dispatches_pivot_update_events()
    {
        Event::fake();

        $user = $this->createUser();
        $role = $this->createRole();

        $user->roles()->attach($role->id, ['scopes' => 'orders,products']);

        $user->roles()->updateExistingPivot($role->id, ['scopes' => 'orders']);

        $this->assertDatabaseHas('role_user', [
            'role_id' => $role->id,
            'user_id' => $user->id,
            'scopes' => 'orders',
        ]);

        Event::assertDispatched('eloquent.pivotUpdating: '.User::class);
        Event::assertDispatched('eloquent.pivotUpdated: '.User::class);
    }

    /** @test */
    public function it_resets_pivot_changes_after_an_event_has_been_dispatched()
    {
        $user = $this->createUser();
        $roleA = $this->createRole(['name' => 'role-a']);
        $roleB = $this->createRole(['name' => 'role-b']);

        $calledTimes = 0;

        User::pivotAttached(function ($model) use (&$calledTimes) {
            $this->assertCount(1, $model->getPivotChanges());
            $calledTimes++;
        });

        $user->roles()->attach($roleA->id);
        $this->assertCount(0, $user->getPivotChanges());

        $user->roles()->attach($roleB->id);
        $this->assertCount(0, $user->getPivotChanges());

        $this->assertCount(2, $user->roles);

        $this->assertEquals(2, $calledTimes);
    }

    protected function createRole(array $overwrites = [])
    {
        return Role::create(array_merge([
            'name' => 'Admin',
        ], $overwrites));
    }

    protected function createUser(array $overwrites = [])
    {
        return User::create(array_merge([
            'name' => 'John Doe',
            'email' => 'jd@example.org',
        ], $overwrites));
    }
}
