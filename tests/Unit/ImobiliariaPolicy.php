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
    $colaborador = User::factory()->make();

    // Manually create the pivot model instance
    $managerRole = new Role([
        'user_id' => $gerente->id,
        'imobiliaria_id' => $imobiliaria->id,
        'role' => UserRole::GERENTE,
    ]);
    $colabRole = new Role([
        'user_id' => $colaborador->id,
        'imobiliaria_id' => $imobiliaria->id,
        'role' => UserRole::COLABORADOR,
    ]);

    // Attach the pivot model to the relationship in memory
    $gerente->setRelation('imobiliarias', collect([$imobiliaria]));
    $colaborador->setRelation('imobiliarias', collect([$imobiliaria]));
    $imobiliaria->setRelation('users', collect([$gerente, $colaborador]));

    // Manually associate the pivot attributes
    $colaborador->imobiliarias()->firstWhere('id', $imobiliaria->id)->role = $managerRole;
    $gerente->imobiliarias()->firstWhere('id', $imobiliaria->id)->role = $managerRole;
    $imobiliaria->users->firstWhere('id', $gerente->id)->role = $managerRole;
    $imobiliaria->users->firstWhere('id', $colaborador->id)->role = $colabRole;

    // admin test
    $policy = new ImobiliariaPolicy;
    $result = $policy->update($admin, $imobiliaria);
    expect($result)->toBeTrue('Admin cannot update imobiliaria');

    // user manager test
    $result = $policy->update($gerente, $imobiliaria);
    expect($result)->toBeTrue('Manager should update imobiliaria');

    // user colaborator test
    $result = $policy->update($colaborador, $imobiliaria);
    expect($result)->toBeFalse('Colaborador must not update imobiliaria');
});
