# Introduction and basic usage

This app will first store twitter accounts in DB with the `app:add-account --usernames "XXX"` console.
Then, use the `app:check-activity` to fetch last tweets of stores account(s) and queue to a queue manager.

Also, you can reset the stored `since_id` value that indicates last tweet checked with last `app:check-activity` console. 

RabbitMQ is already set, you will have to import definitions to send tweet contents to the exchange.

# Usage on a local installation

- Import RabbitMQ definitions: `rabbitmqctl import_definitions config/rabbitmq/definitions.json`
- Add account: `php bin/console app:add-account --usernames "twitter_account1,twitter_account2,twitter_account3"`
- Check account: `php bin/console app:check-activity`
- Reset last tweet: `php bin/console app:reset-last-tweet-id --usernames "twitter_account1,twitter_account2,twitter_account3"` 


# Usage as dockerized
Run `docker-compose up` or with your preferred env file if needed: `docker-compose --env-file .env.(local|dist|dev|prod|...) up`
You can also run the file `docker-init.sh` to load rabbitmq and database stuff at once (check the selected env file within the shell script).


- Import RabbitMQ definitions: `docker exec -it ttq_rabbitmq rabbitmqctl import_definitions /etc/rabbitmq/definitions.json`
- Add account: `docker exec -it ttq_app php bin/console app:add-account --usernames "twitter_account1,twitter_account2,twitter_account3"`
- Check account: `docker exec -it ttq_app php bin/console app:check-activity`
- Reset last tweet: `docker exec -it ttq_app php bin/console app:reset-last-tweet-id --usernames "twitter_account1,twitter_account2,twitter_account3"`
