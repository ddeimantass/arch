# Software Systems Architecture and Design

## Task 1

### Requirements

- Create MVC
- Business entity should contain at least 4 editable properties
- Implement validation for all editable properties in create and update scenarios
- Demonstrate usage of DI+IOC
- Create Web service as API
- Demonstrate 1 business entity creation, reading, editing, deleting, use 4 HTTP verbs (no persistence required)
- Unit tests for all API public contracts (100% coverage)
- Streaming API for reading and RPC for creation, editing, deletion
- Implement DB layer by using ORM or plain SQL with Repository pattern

### Build and run

- pull this repository
- composer install
- cp .env.dist .env
- change DB credentials in .env
- bin/console d:d:c
- bin/console d:s:u --force
- bin/console server:run 
- open this repository in browser
