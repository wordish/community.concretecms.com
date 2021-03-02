# community.concretecms.com

This repo contains the code for all account management aspects of community.concretecms.com.

## Installation Instructions.

1. Clone this repo.
2. Install dependencies by running `composer install`.
3. Install concrete5, making sure to select the `concrete_cms_community` starting point. Here is an example of installation via the command line.

`concrete/bin/concrete5 c5:install -vvv --db-server=localhost --db-database=concrete5_community --db-username=user --db-password=password --starting-point=concrete_cms_community --admin-email=your@email.com --admin-password=password`


## Discourse SSO
This project includes api endpoints that support Discourse's [DiscourseConnect](https://meta.discourse.org/t/discourseconnect-official-single-sign-on-for-discourse-sso/13045) SSO API.

#### Setup
1. Set up discourse SSO (Navigate to discourse /admin/site_settings/category/login)
    1. Use `http://example.com/ccm/api/v1/discourse/connect` as the "discourse connect url"
    2. Set a good discourse connect secret, this will be the hmac password so make sure it's secure and secret
    3. Enable "enable discourse connect"
    4. Enable all auth override options:
        1. "discourse connect overrides groups"
        2. "discourse connect overrides bio"
        3. "auth overrides username"
        4. "auth overrides name"
2. Update .env to have the same secret from step 1.2

Now you should be able to log in using SSO in discourse.

#### TODO

- Implement the unused discourse connect settings
- Pass through admin and moderator groups
- Pass through any other important groups
- Wire up all remaining user metadata
