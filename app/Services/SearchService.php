<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Imobiliaria;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class SearchService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Search clients
     *
     * @param  \Illuminate\Database\Eloquent\Collection<Client>  $clients
     * @return Collection<mixed, mixed>
     */
    public function clientSearch(Collection $clients, string $search = ''): Collection
    {
        return $clients->filter(function ($client) use ($search) {
            $verdict = true;

            // data
            $name = $client->name ?? '';
            $email = $client->email ?? '';
            $cpf = $client->cpf ?? '';

            // formatted queries
            $haystack = preg_replace('/[.,]/', '', strtolower("$name $email $cpf"));
            $needle = preg_replace('/[.,]/', '', strtolower($search));

            // search filter
            $verdict = str_contains($haystack, $needle);

            return $verdict;
        })->reverse();
    }

    /**
     * Summary of userSearch
     *
     * @param  \Illuminate\Database\Eloquent\Collection<User>  $users
     * @return Collection<int, User>
     */
    public function userSearch(Collection $users, string $search, ?string $searchType)
    {
        return $users->filter(function (User $user) use ($search, $searchType) {
            $verdict = true;

            // data
            $userName = strtolower($user->name ?? '');
            $userEmail = strtolower($user->email ?? '');
            $userType = $user->is_admin ? '1' : '0';

            // formatted queries
            $haystack = "$userName $userEmail";
            $needle = strtolower($search ?? '');

            // search filter
            $verdict = str_contains($haystack, $needle);

            // type filter
            if (isset($searchType)) {
                $verdict = $verdict && $userType === $searchType;
            }

            return $verdict;
        });
    }

    public function imovelSearch(Collection $imoveis, string $search, ?string $searchStatus)
    {
        return $imoveis->filter(function ($imovel) use ($search, $searchStatus) {
            $verdict = true;

            // data
            $address = $imovel->fullAddress() ?? '';
            $value = $imovel->value ?? '';
            $iptu = $imovel->iptu ?? '';

            // formatted queries
            $haystack = strtolower("$address $value $iptu");
            $needle = strtolower($search ?? '');

            // perform search
            $verdict = str_contains($haystack, $needle);

            // category filter
            if (isset($searchStatus)) {
                $verdict = $verdict && (string) $imovel->status->value === $searchStatus;
            }

            return $verdict;
        })->reverse();
    }

    /**
     * Summary of imobiliariaSearch
     *
     * @param  \Illuminate\Database\Eloquent\Collection<Imobiliaria>  $imobiliarias
     * @return \Illuminate\Database\Eloquent\Collection<Imobiliaria>
     */
    public function imobiliariaSearch(Collection $imobiliarias, string $searchString)
    {
        return $imobiliarias->filter(function (Imobiliaria $imobiliaria) use ($searchString) {
            $verdict = true;

            // data
            $imobiliariaName = strtolower($imobiliaria->name ?? '');
            $imobiliariaEmail = strtolower($imobiliaria->email ?? '');

            // formatted queries
            $haystack = "$imobiliariaName $imobiliariaEmail";
            $needle = $searchString;

            // search filter
            $verdict = str_contains($haystack, $needle);

            return $verdict;
        });
    }
}
