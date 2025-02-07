<?php

use App\Enums\UserRole;
use App\Models\Imobiliaria;
use App\Models\Role;
use App\Models\User;
use App\Policies\ImobiliariaPolicy;

test('tests the update policy of imobiliaria', function () {
    $admin = User::factory()->make(['is_admin' => true]);
    $gerente = User::factory()->make();
    $imobiliaria = Imobiliaria::factory()->make();

    // Manually create the pivot model instance
    $role = new Role([
        'user_id' => $gerente->id,
        'imobiliaria_id' => $imobiliaria->id,
        'role' => UserRole::GERENTE,
    ]);

    // Attach the pivot model to the relationship in memory
    $gerente->setRelation('imobiliarias', collect([$imobiliaria]));
    $imobiliaria->setRelation('users', collect([$gerente]));

    // Manually associate the pivot attributes
    $imobiliaria->users->first()->role = $role;

    // admin test
    $result = (new ImobiliariaPolicy)->update($admin, $imobiliaria);
    expect($result)->toBeTrue('Admin cannot update imobiliaria');

    // user manager test
    $result = (new ImobiliariaPolicy)->update($gerente, $imobiliaria);
    expect($result)->toBeTrue('Manager cannot update imobiliaria');

});
