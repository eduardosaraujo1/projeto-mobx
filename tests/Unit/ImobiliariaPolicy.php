<?php

use App\Enums\UserRole;
use App\Models\Imobiliaria;
use App\Models\Role;
use App\Models\User;
use App\Policies\ImobiliariaPolicy;

test('tests the update policy of imobiliaria', function () {
    // Create the models in memory (not persisted)
    $admin = User::factory()->make(['is_admin' => true]);
    $gerente = User::factory()->make(['name' => 'MOCKMANAGER']);
    $colaborador = User::factory()->make(['name' => 'MOCKCOLAB']);
    $imobiliaria = Imobiliaria::factory()->make();

    // Manually assign IDs so that firstWhere() can differentiate them
    $gerente->id = 1;
    $colaborador->id = 2;
    $imobiliaria->id = 10;

    // Create the pivot role instances (simulate the pivot table records)
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

    // Instead of attaching the same $imobiliaria instance to both users,
    // clone it so each relationship can have its own pivot data.
    $imobiliariaForGerente = clone $imobiliaria;
    $imobiliariaForColaborador = clone $imobiliaria;

    // Assign each clone its corresponding pivot role (simulate the pivot data)
    $imobiliariaForGerente->role = $managerRole;
    $imobiliariaForColaborador->role = $colabRole;

    // Set the "imobiliarias" relation on each user to contain their unique clone
    $gerente->setRelation('imobiliarias', collect([$imobiliariaForGerente]));
    $colaborador->setRelation('imobiliarias', collect([$imobiliariaForColaborador]));

    // Also, set the "users" relation on the imobiliaria to simulate the inverse side.
    // Clone each user so that the pivot data remains independent.
    $gerenteClone = clone $gerente;
    $colaboradorClone = clone $colaborador;
    $gerenteClone->role = $managerRole;
    $colaboradorClone->role = $colabRole;

    $imobiliaria->setRelation('users', collect([$gerenteClone, $colaboradorClone]));

    // ASSERT RESULTS
    // admin test
    $policy = new ImobiliariaPolicy;
    $result = $policy->update($admin, $imobiliaria);
    expect($result)->toBeTrue('Admin cannot update imobiliaria');

    // user manager test
    $result = $policy->update($gerenteClone, $imobiliariaForGerente);
    expect($result)->toBeTrue('Manager should update imobiliaria');

    // user colaborator test
    $result = $policy->update($colaboradorClone, $imobiliariaForColaborador);
    expect($result)->toBeFalse('Colaborador must not update imobiliaria');
});
