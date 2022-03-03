<?php

use Google\CloudFunctions\FunctionsFramework;
use Psr\Http\Message\ServerRequestInterface;

use Google\Cloud\Firestore\FirestoreClient;

// Register the function with Functions Framework.
// This enables omitting the `FUNCTIONS_SIGNATURE_TYPE=http` environment
// variable when deploying. The `FUNCTION_TARGET` environment variable should
// match the first parameter.
FunctionsFramework::http('helloHttp', 'helloHttp');

function helloHttp(ServerRequestInterface $request): string
{
    $name = 'World';
    $body = $request->getBody()->getContents();
    if (!empty($body)) {
        $json = json_decode($body, true);
        if (json_last_error() != JSON_ERROR_NONE) {
            throw new RuntimeException(sprintf(
                'Could not parse body: %s',
                json_last_error_msg()
            ));
        }
        $name = $json['name'] ?? $name;
    }
    $queryString = $request->getQueryParams();
    $name = $queryString['name'] ?? $name;

    $db = new FirestoreClient();
    $docRef = $db->collection('php-serverless-test')->document($name);
    $snapshot = $docRef->snapshot();
    $count = 1;
    if ($snapshot->exists()) {
    	$hash = $snapshot->data();
        if (!empty($hash['count'])) {
        	$count = $hash['count'] + 1;
        }
    }
    $docRef->set([
	'name'  => $name,
	'count' => $count,
    ]);

    return sprintf('Hello, %s! %d', htmlspecialchars($name), $count);
}
