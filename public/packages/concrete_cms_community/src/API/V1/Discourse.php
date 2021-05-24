<?php /** @noinspection PhpMissingFieldTypeInspection */
/** @noinspection PhpUnused */

/**
 * @project:   ConcreteCMS Community
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace PortlandLabs\Community\API\V1;

use Concrete\Core\Application\EditResponse;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Entity\Package;
use Concrete\Core\Error\ErrorList\ErrorList;
use Concrete\Core\Http\Client\Client;
use Concrete\Core\Http\Request;
use Concrete\Core\Logging\LoggerFactory;
use Concrete\Core\Package\PackageService;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\User\UserInfo;
use Concrete\Core\User\UserInfoRepository;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Uri;
use PortlandLabs\CommunityBadges\AwardService;
use PortlandLabs\CommunityBadges\Exceptions\AchievementAlreadyExists;
use PortlandLabs\CommunityBadges\Exceptions\InvalidBadgeType;
use PortlandLabs\CommunityBadges\Exceptions\MailTransportError;
use PortlandLabs\CommunityBadges\Exceptions\NoUserSelected;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Exception;

class Discourse
{
    protected $request;
    protected $loggerFactory;
    /** @var LoggerInterface */
    protected $logger;
    protected $client;
    protected $config;
    protected $userInfoRepository;
    protected $packageService;

    public function __construct(
        Request $request,
        LoggerFactory $loggerFactory,
        Client $client,
        Repository $config,
        UserInfoRepository $userInfoRepository,
        PackageService $packageService
    )
    {
        $this->request = $request;
        $this->loggerFactory = $loggerFactory;
        $this->client = $client;
        $this->config = $config;
        $this->userInfoRepository = $userInfoRepository;
        $this->packageService = $packageService;
        $this->logger = $this->loggerFactory->createLogger("discourse");
    }

    public function handleWebhookEvent()
    {
        $editResponse = new EditResponse();
        $errorList = new ErrorList();

        $communityBadgesPackage = $this->packageService->getByHandle("community_badges");

        if ($communityBadgesPackage instanceof Package) {
            $app = Application::getFacadeApplication();
            /** @var AwardService $awardService */
            $awardService = $app->make(AwardService::class);

            if ($this->config->has("concrete_cms_community.discourse.endpoint")) {
                $discourseEndpoint = $this->config->get("concrete_cms_community.discourse.endpoint");

                if ($this->config->has("concrete_cms_community.discourse.api_key")) {
                    $discourseApiKey = $this->config->get("concrete_cms_community.discourse.api_key");

                    if ($this->config->has("concrete_cms_community.discourse.achievements_mapping") &&
                        is_array($this->config->get("concrete_cms_community.discourse.achievements_mapping"))) {

                        $achievementsMapping = $this->config->get("concrete_cms_community.discourse.achievements_mapping");

                        if (0 === strpos($this->request->headers->get('Content-Type'), 'application/json')) {
                            $data = json_decode($this->request->getContent(), true);

                            if (is_array($data) || count($data) === 0) {
                                if ($this->request->headers->has("X-Discourse-Event")) {
                                    if ($this->request->headers->has("X-Discourse-Event-Type")) {
                                        $eventName = $this->request->headers->get("X-Discourse-Event");
                                        $eventType = $this->request->headers->get("X-Discourse-Event-Type");

                                        if (isset($achievementsMapping[$eventName])) {
                                            $achievementHandle = $achievementsMapping[$eventName];

                                            try {
                                                $achievement = $awardService->getBadgeByHandle($achievementHandle);
                                            } catch (Exception $e) {
                                                $achievement = null;
                                            }

                                            if (isset($achievement)) {
                                                if (isset($data[$eventType])) {
                                                    if (isset($data[$eventType]["user_id"])) {
                                                        $userId = $data[$eventType]["user_id"];

                                                        $this->logger->info(t("Event %s raised for discourse user with ID #%s.", $eventName, $userId));

                                                        /*
                                                         * Resolve mail address through discourse API
                                                         *
                                                         * @see https://docs.discourse.org/#tag/Users/paths/~1admin~1users~1{id}.json/get
                                                         */

                                                        $path = sprintf("/admin/users/%s.json", $userId);

                                                        $url = new Uri($discourseEndpoint);

                                                        $url = $url
                                                            ->withPath($path);

                                                        try {
                                                            $response = $this->client->request("GET", $url, [
                                                                "headers" => [
                                                                    "Api-Key" => $discourseApiKey
                                                                ]
                                                            ]);

                                                            if ($response->getStatusCode() === JsonResponse::HTTP_OK) {
                                                                $rawResponse = $response->getBody()->getContents();
                                                                $json = json_decode($rawResponse, true);

                                                                if (is_array($json) && count($json) > 0) {
                                                                    if (isset($json["email"]) && filter_var($json["email"], FILTER_VALIDATE_EMAIL)) {
                                                                        $email = $json["email"];

                                                                        $userInfo = $this->userInfoRepository->getByEmail($email);

                                                                        if ($userInfo instanceof UserInfo) {
                                                                            $user = $userInfo->getUserObject();

                                                                            $awardService->giveBadge($achievement, $user);

                                                                            $editResponse->setMessage(t("The achievement has been successfully assigned."));

                                                                        } else {
                                                                            $errorList->add(t("The received mail address from the discourse api is not associated with an user account at this site."));
                                                                        }
                                                                    } else {
                                                                        $errorList->add(t("Error while looking up the user details. The received response doesn't contain an valid email property."));
                                                                    }
                                                                } else {
                                                                    $errorList->add(t("Error while looking up the user details. The received response is empty."));
                                                                }
                                                            } else {
                                                                $errorList->add(t("Error while looking up the user details. Invalid status code received."));
                                                            }
                                                        } catch (GuzzleException $e) {
                                                            $errorList->add(t("Error while looking up the user details. Internal server error."));
                                                        } catch (AchievementAlreadyExists $e) {
                                                            $errorList->add(t("Error while assign the achievement. The achievement already exists."));
                                                        } catch (InvalidBadgeType $e) {
                                                            $errorList->add(t("Error while assign the achievement. Invalid badge type."));
                                                        } catch (MailTransportError $e) {
                                                            $errorList->add(t("Error while assign the achievement. Mail transport error."));
                                                        } catch (NoUserSelected $e) {
                                                            $errorList->add(t("Error while assign the achievement. Invalid user."));
                                                        }
                                                    } else {
                                                        $errorList->add(t("User id is missing.", $eventType));
                                                    }
                                                } else {
                                                    $errorList->add(t("Item %s is missing in payload.", $eventType));
                                                }
                                            } else {
                                                $errorList->add(t("The achievement with the handle %s does not exists.", $achievementHandle));
                                            }
                                        } else {
                                            $errorList->add(t("There is no achievement mapped with the given event type."));
                                        }
                                    } else {
                                        $errorList->add(t("Header X-Discourse-Event-Type is missing."));
                                    }
                                } else {
                                    $errorList->add(t("Header X-Discourse-Event is missing."));
                                }
                            } else {
                                $errorList->add(t("Payload is empty."));
                            }
                        } else {
                            $errorList->add(t("Content type is invalid."));
                        }
                    } else {
                        $errorList->add(t("There are no achievements mapped with the discourse events."));
                    }

                } else {
                    $errorList->add(t("You need to define an api key for the discourse integration."));
                }
            } else {
                $errorList->add(t("You need to define an endpoint for the discourse integration."));
            }
        } else {
            $errorList->add(t("The community badges package is not installed."));
        }

        $editResponse->setError($errorList);

        $statusCode = $errorList->has() ? JsonResponse::HTTP_INTERNAL_SERVER_ERROR : JsonResponse::HTTP_OK;

        return new JsonResponse($editResponse, $statusCode);
    }
}