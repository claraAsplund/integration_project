<?php
// Auto-load Composer dependencies
require_once __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;

$apiKey = "";
$leadData = json_decode(file_get_contents(__DIR__ . '/../test/test_data.json'), true);

/**
 * Create an organization in Pipedrive.
 * @param $apiKey API token for Pipedrive.
 * @return ID of the created organization.
 * @throws Exception if the organization cannot be created.
 * */
function createOrganization($apiKey)
{

    try {
        //Initialize the HTTP client
        $client = new Client([
            'base_uri' => 'https://api.pipedrive.com/v1/'
        ]);
        //Set default organization name if not privided
        $organizationName = "Super Strøm";

        //Send the POST request to create the organization
        $response = $client->post("organizations", [
            "query" => ["api_token" => $apiKey],
            "json" => ["name" => $organizationName]
        ]);

        //Decode the JSON response to get the organization ID
        $result = json_decode($response->getBody(), true);

        //Return the orgranization ID from the response
        return $result['data']['id'];
    } catch (\Exception $e) {
        //Throw an exception if something goes wrong
        throw new \Exception("Error creating organization: " .  $e->getMessage());
    }
}

/**
 * Create a person in Pipedrive and associate with the organization.
 * @param $apiKey API token for Pipedrive.
 * @param $organizationId ID of the associated organization.
 * @param $leadData Data for the person to created.
 * @return ID of the created person.
 * @throws Exception if the person cannot be created.
 */
function createPerson($apiKey, $organizationId, $leadData)
{
    try {
        //Initialize the HTTP client
        $client = new Client([
            'base_uri' => 'https://api.pipedrive.com/v1/'
        ]);

        $contactType = [
            'Privat' => 30,
            'Borettslag' => 31,
            'Bedrift' => 32
        ];
        //Send the POST request
        $response = $client->post("persons", [
            'query' => ['api_token' => $apiKey],
            'json' => [
                'name' => $leadData['name'],
                'email' => $leadData['email'],
                'phone' => $leadData['phone'],
                'org_id' => $organizationId,
                'fd460d099264059d975249b20e071e05392f329d' => $contactType[$leadData['contact_type']]
            ]
        ]);
        //Decode and return the response
        $result = json_decode($response->getBody(), true);
        return $result['data']['id']; //Return the created person ID

    } catch (\Exception $e) {
        throw new \Exception("Error creating person: " . $e->getMessage());
    }
}

/**
 * Create a lead and link to the person and organization
 *@param $apiKey API token for Pipedrive.
 * @param $personId ID of the associated person.
 * @param $organizationId ID of the associated organization.
 * @param $leadData Data for the lead to be created.
 * @return ID of the created lead.
 * @throws Exception if the lead cannot be created.
 */
function createdLead($apiKey, $personId, $organizationId, $leadData)
{
    try {
        $client = new Client([
            'base_uri' => 'https://api.pipedrive.com/v1/'
        ]);

        $housingTypes = [
            'Enebolig' => 33,
            'Leilighet' => 34,
            'Tomannsbolig' => 35,
            'Rekkehus' => 36,
            'Hytte' => 37,
            'Annet' => 38,
        ];

        $dealType = [
            'Alle strømavtaler er aktuelle' => 39,
            'Fastpris' => 40,
            'Spotpris' => 41,
            'Kraftforvaltning' => 42,
            'Annen avtale/vet ikke' => 43
        ];

        $response = $client->post('leads', [
            'query' => ['api_token' => $apiKey],
            'json' => [
                'title' => "Lead for {$leadData['name']}",
                'person_id' => $personId,
                'organization_id' => $organizationId,
                '9cbbad3c5d83d6d258ef27db4d3784b5e0d5fd32' => $housingTypes[$leadData['housing_type']],
                '7a275c324d7fbe5ab62c9f05bfbe87dad3acc3ba' => $leadData['property_size'],
                '479370d7514958b2b4b4049c37be492f357fe7d8' => $leadData['comment'] ?? null,
                'cebe4ad7ce36c3508c3722b6e0072c6de5250586' => $dealType[$leadData['deal_type']]
            ]
        ]);
        $result = json_decode($response->getBody(), true);
        return $result['data']['id'];
    } catch (\Exception $e) {
        throw new \Exception("Error creating lead: " . $e->getMessage());
    }
}

/**
 * Function to handle the creation of organization, person, and lead
 * @param $leadData Data for the lead to be handled.
 * @param $apiKey API token for Pipedrive.
 * @return IDs of the created entities.
 * @throws Exception if any part of the process fails.
 */
function handleLead(array $leadData, $apiKey)
{
    try {
        $organizationId = createOrganization($apiKey);
        $personId = createPerson($apiKey, $organizationId, $leadData);
        $leadId = createdLead($apiKey, $personId, $organizationId, $leadData);

        //Return the created IDs
        return [
            'organization_id' => $organizationId,
            'person_id' => $personId,
            'lead_id' => $leadId
        ];
    } catch (\Exception $e) {
        throw new \Exception(("Error handling lead: " . $e->getMessage()));
    }
}

$result = handleLead($leadData, $apiKey);

//Output IDs for verification
echo "Organization ID: "  . $result['organization_id'] . "\n";
echo "Person ID: " . $result['person_id'] . "\n";
echo "Lead ID: " . $result['lead_id'] . "\n";
