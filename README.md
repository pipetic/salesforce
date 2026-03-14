# pipetic/salesforce

Salesforce adapter for the [Pipetic](https://github.com/pipetic) platform. Provides OAuth 2.0 authentication and ETL data synchronisation between multi-tenant SaaS applications and Salesforce.

## Requirements

- PHP ≥ 8.0
- Composer

## Installation

```bash
composer require pipetic/salesforce
```

## Configuration

Add your Salesforce OAuth credentials to your application configuration:

```php
// config/services.php (Laravel) or equivalent
'salesforce' => [
    'oauth' => [
        'client_id'     => env('SALESFORCE_CLIENT_ID'),
        'client_secret' => env('SALESFORCE_CLIENT_SECRET'),
        'redirect_uri'  => env('SALESFORCE_REDIRECT_URI'),
    ],
],
```

## Usage

### OAuth 2.0 Authentication

```php
use Pipetic\Salesforce\Client\Actions\SalesforceCreateClientDataNode;

// 1. Create a client bound to a DataNode (stores the access token)
$client = SalesforceCreateClientDataNode::run($dataNode);

// 2. Redirect the user to Salesforce login
$authorizationUrl = $client->getAuthenticator()->getAuthorizationUrl();
header('Location: ' . $authorizationUrl);

// 3. Exchange the authorization code for an access token (in your callback route)
$client->authenticate(['code' => $_GET['code']]);
```

### Querying Records (SOQL)

```php
$response = $client->query()->query('SELECT Id, Name FROM Account LIMIT 10');

foreach ($response->getRecords() as $record) {
    echo $record['Name'] . PHP_EOL;
}

// Paginate through large result sets
while (!$response->isDone()) {
    $response = $client->query()->nextPage($response->getNextRecordsUrl());
    foreach ($response->getRecords() as $record) {
        // process record
    }
}
```

### CRUD on SObjects

```php
$sobjects = $client->sobjects();

// Create a record
$result = $sobjects->create('Account', ['Name' => 'Acme Corp', 'Phone' => '555-1234']);
$newId = $result['id'];

// Read a record
$account = $sobjects->get('Account', $newId);

// Update a record
$sobjects->update('Account', $newId, ['Phone' => '555-5678']);

// Delete a record
$sobjects->delete('Account', $newId);
```

### Token Introspection

```php
$accessToken = $client->getAccessToken();
$response = $client->oauth2Introspect()->inspect($accessToken);

if ($response->getActive()) {
    echo 'Token is active, user: ' . $response->getUsername();
}
```

## Architecture

```
src/
├── Abstract/Behaviours/       # Reusable traits (HasAccessToken, HasDataNode, HasOauthConfig)
├── Authentication/            # OAuth2 authentication & token storage
│   └── Token/                 # TokenRepositoryInterface + DataNode implementation
├── Client/                    # SalesforceClient + composable behaviours
│   ├── Actions/               # Factory actions (CreateClientBase, CreateClientDataNode)
│   └── Behaviours/            # HasAuthenticatorTrait, HasConfiguration, HasEndpoints
├── Config/                    # OauthConfig, ClientConfiguration
└── Endpoints/
    ├── Base/                  # AbstractEndpoint
    ├── Oauth2/Introspect/     # Token introspection endpoint
    └── SObjects/              # CRUD + SOQL query endpoints (ETL)
```

## Testing

```bash
composer install
vendor/bin/phpunit
```

## License

MIT

