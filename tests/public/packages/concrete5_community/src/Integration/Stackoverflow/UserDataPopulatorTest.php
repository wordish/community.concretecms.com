<?php

namespace Concrete5\Community\Integration\Stackoverflow;

use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\User\UserInfo;
use Concrete5\Community\TestCase;
use Mockery;
use OAuth\OAuth2\Token\TokenInterface;

class UserDataPopulatorTest extends TestCase
{

    const FAKE_RESPONSE = '{"items":[{"badge_counts":{"bronze":47,"silver":15,"gold":4},"account_id":918395,"is_employee":false,"last_modified_date":1546323948,"last_access_date":1566592036,"reputation_change_year":55,"reputation_change_quarter":0,"reputation_change_month":0,"reputation_change_week":0,"reputation_change_day":0,"reputation":3994,"creation_date":1316293680,"user_type":"registered","user_id":950669,"accept_rate":100,"location":"Oregon","website_url":"","link":"https://stackoverflow.com/users/950669/korvin-szanto","profile_image":"https://i.stack.imgur.com/VX2F1.jpg?s=128&g=1","display_name":"Korvin Szanto"}],"has_more":false,"quota_max":10000,"quota_remaining":9938}';

    public function testPopulate()
    {
        $config = Mockery::mock(Repository::class);
        $config->shouldReceive('get')->with('concrete5_community::integrations.stackoverflow.key')->andReturn('foo');

        $stackoverflow = Mockery::mock(StackoverflowService::class);
        $stackoverflow->shouldReceive('request')->with('/me?site=stackoverflow&key=foo')->andReturn(self::FAKE_RESPONSE);

        $user = Mockery::mock(UserInfo::class);

        // Make sure all attributes get set properly
        $user->shouldReceive('setAttribute')->with('stackoverflow_user_id', 950669)->once();
        $user->shouldReceive('setAttribute')->with('stackoverflow_badges_bronze', 47)->once();
        $user->shouldReceive('setAttribute')->with('stackoverflow_badges_silver', 15)->once();
        $user->shouldReceive('setAttribute')->with('stackoverflow_badges_gold', 4)->once();
        $user->shouldReceive('setAttribute')->with('stackoverflow_badges', 66)->once();
        $user->shouldReceive('setAttribute')->with('stackoverflow_reputation', 3994)->once();

        $token = Mockery::mock(TokenInterface::class);
        $token->shouldReceive('getAccessToken')->once()->andReturn('FOOBAR');

        $populator = new UserDataPopulator($config, $stackoverflow);
        $this->assertTrue($populator->populate($user, $token));
    }

}
