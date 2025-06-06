<?php

namespace App\Http\Integrations\Novi\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;

class GetUsersDataFromNovi extends Request implements Paginatable
{
    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;

    public function __construct(
        protected string $email,
    ) {
    }
    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/api/members';
    }

    protected function defaultQuery(): array
    {
        return [
            'accountEmail' => $this->email
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        $userFields = config('userfields');
        $item = $response->json('Results');

        $details = null;
        $email = null;
        $openDueBalance = null;

        if (!empty($item[0])) {
            $response = $item[0];
            $openDueBalance = $response['OpenDuesBalance'];
            $email = $response['AccountEmail'];

            foreach ($userFields as $key => $value1) {
                // Vérifie si la clé existe directement dans la réponse
                if (array_key_exists($value1, $response)) {
                    $details[$key] = $response[$value1];
                } else {
                    // Vérifie si la clé existe dans les sous-tableaux
                    foreach ($response as $field => $value) {
                        if (is_array($value) && array_key_exists($value1, $value)) {
                            $details[$key] = $value[$value1];
                            break; // Arrête la boucle dès que la valeur est trouvée
                        }
                    }
                }
            }

            foreach ($details as $key => &$value) {
                // Check if the key exists in the config array
                if (array_key_exists($key, $userFields)) {
                    // If the value is null, empty, or an empty array, set it to an empty string
                    if (empty($value) || $value === "" || (is_array($value) and empty($value))) {
                        $value = "";
                    }
                    // If the value is an array with a 'Value' key, take just the 'Value'
                    if (is_array($value) && array_key_exists('Value', $value)) {
                        $value = $value['Value'];
                    }
                    // If the key is 'BillingAddress' or 'ShippingAddress', convert the array to a string
                    if (in_array($userFields[$key], ['BillingAddress', 'ShippingAddress']) && is_array($value)) {
                        $value = implode(", ", array_filter($value));
                    }
                }

                if ($userFields[$key] === 'OpenDuesBalance') {
                    $value = strval($openDueBalance) == "0" ? "0.00" : strval($openDueBalance);
                }

                // Modify the value of the "Gender" key
                if ($userFields[$key] === 'Gender') {
                    $value = ($value === 'Male') ? 9 : (($value === 'Prefer Not to Answer') ? 11 : null);
                }

                if ($userFields[$key] === 'CustomerType') {
                    $value = ($value === 'Person') ? 7 : 8;
                }

                if ($userFields[$key] === 'MemberStatus') {
                    $value = ($value === 'current') ? 9 : null;
                }
            }
            unset($value);
        }

        return [
            'email' => $email,
            'details' => $details
        ];
    }

}
