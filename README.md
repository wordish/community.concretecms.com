# concrete5.org Documentation Website

This repo contains the source code for [the concrete5.org community site](https://documentation.concrete5.org). It installs the shared concrete5.org theme, the new karma machine, updated forums, the centralized user portal, and more.

## Installation Instructions.

1. Clone this repo.
2. Install dependencies by running `composer install`.
3. Install concrete5, making sure to select the `concrete5_community` starting point. Here is an example of installation via the command line.

`concrete/bin/concrete5 c5:install -vvv --db-server=localhost --db-database=concrete5_community --db-username=user --db-password=password --starting-point=concrete5_community --admin-email=your@email.com --admin-password=password`
