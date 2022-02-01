# Gaming League Ranking System

A ranking application for a gaming league. The application is written for the Polish League of The 9th Age 
([available here](https://ranking.wfb-pol.org)), but can be used for any game system with minimal modifications.

The app consists of a backend written in PHP using the Symfony framework and MongoDB, and a frontend written in React.
The backend Symfony app exposes a REST API and serves the static files.

## Features

- List of players. Players can be added via API calls.
- Seasons. All tournaments are assigned to an active season and the ranking is created for each season separately.
- Tournaments. Tournaments can be added through a form from the frontend. Tournament results are added via an API. 
  Every time a tournament result is added, the ranking for that tournament's season is recalculated.
- Ranking. List of players ranked in a season. There is also a view of individual results of a player in a season. The
  ranking is created based on a set of rules created for the Polish league (number of tournaments from a given type that
  count as ranked, e.g. a players only 2 best results from team tournaments of a "master" rank are used).
- Archive rankings. List of rankings are available for each season.

## TODOs

- Add user accounts. Players should register their accounts and have control of their data.
- Add form for tournament organisers to send tournaments results.

## Contributing

If you found a bug, or would like a feature to be introduced don't hesitate to open an [issue](/issues).

If you're able, then you can open a Pull Request with the proposed changes.

If you want to do some development work there are some tools to help with this task. There is a Vagrantfile which spins
up a virtual machine and an Ansible playbook to provision it with all the necessary components to run the app locally.
Run `vagrant up` to start the virtual machine, then `vagrant ssh` to ssh into it.

From the `/vagrant/src` directory you can run these utility commands:

- `composer install` to install all the necessary backend dependencies.
- `npm install` to install all the frontend dependencies.
- `php bin/console ranking:loadDevelopmentData` to load some initial data fixtures to work with during development.
- `npm run dev` to build the frontend files.
- `php bin/console server:start 0.0.0.0` to start the development server on port 8000. You can then access it from your
  host's browser through `http://localhost:8000`.
- `php bin/phpunit` to run the test suite.
