# community.concretecms.org

This repo contains the code for all account management aspects of concretecms.org.

## Installation Instructions.

1. Clone this repo.
2. Install dependencies by running `composer install`.
3. Install concrete5, making sure to select the `concrete5_community` starting point. Here is an example of installation via the command line.

`concrete/bin/concrete5 c5:install -vvv --db-server=localhost --db-database=concrete5_community --db-username=user --db-password=password --starting-point=concrete5_community --admin-email=your@email.com --admin-password=password`
