FROM rabbitmq:3.10.7-management

RUN rabbitmqctl import_definitions /etc/rabbitmq/definitions.json

CMD ["rabbitmq-server"]
