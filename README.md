testing out google's server-less functions

https://cloud.google.com/php/getting-started/
https://cloud.google.com/functions/docs
https://cloud.google.com/functions/docs/first-php

to set up locally:

	brew install composer
	composer require google/cloud-functions-framework
	export FUNCTION_TARGET=helloHttp
	php -S localhost:8080 vendor/bin/router.php
	curl http://localhost:8080/

to use a firestore db:

https://cloud.google.com/firestore/docs/create-database-server-client-library

	gcloud components install app-engine-php
	brew install php@7.4
	export PATH=/usr/local/opt/php@7.4/bin:$PATH
	pecl install grpc
	composer require google/cloud-firestore
	composer require "grpc/grpc:^1.38"
	composer require "google/protobuf:^3.17"
	pecl install protobuf
	(echo 'extension=grpc.so'; echo 'extension=protobuf.so') >php.ini

find your ini dir by running:

	php --ini

Then add the following to something like /usr/local/etc/php/7.4/conf.d/gcloud.ini

	extension=grpc.so
	extension=protobuf.so

deploy as a cloud function:

	gcloud functions deploy helloHttp --runtime php74 --trigger-http --allow-unauthenticated
	curl https://us-central1-php-server-less-test.cloudfunctions.net/helloHttp?name=matt
	gcloud functions logs read helloHttp
