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
use Concrete\Core\Events\EventDispatcher;
use Concrete\Core\Http\Client\Client;
use Concrete\Core\Http\Request;
use Concrete\Core\Http\Response;
use Concrete\Core\Logging\LoggerFactory;
use Concrete\Core\Package\PackageService;
use Concrete\Core\Routing\RedirectResponse;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\User\User;
use Concrete\Core\User\UserInfo;
use Concrete\Core\User\UserInfoRepository;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Uri;
use PortlandLabs\Community\Events\DiscourseWebhookCall;
use PortlandLabs\CommunityBadges\AwardService;
use PortlandLabs\CommunityBadges\Exceptions\AchievementAlreadyExists;
use PortlandLabs\CommunityBadges\Exceptions\InvalidBadgeType;
use PortlandLabs\CommunityBadges\Exceptions\MailTransportError;
use PortlandLabs\CommunityBadges\Exceptions\NoUserSelected;
use PortlandLabs\CommunityBadges\User\Point\Action\Action as UserPointAction;
use PortlandLabs\CommunityBadges\User\Point\Action\ActionDescription as UserPointActionDescription;
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

    public function editFormInfo()
    {
        $currentUser = new User();
        $app = Application::getFacadeApplication();
        /** @var Repository $config */
        $config = $app->make(Repository::class);
        $errorList = new ErrorList();
        $discourseEndpoint = $config->get("concrete_cms_community.discourse.endpoint");
        $discourseApiKey = $config->get("concrete_cms_community.discourse.api_key");
        $baseUrl = new Uri($discourseEndpoint);
        $client = new Client();
        $discourseUsername = "";

        $apiUrl = $baseUrl
            ->withPath(
                sprintf(
                    "/u/by-external/%s.json",
                    (string)$currentUser->getUserID()
                )
            );

        try {
            $response = $client->request("GET", $apiUrl, [
                "headers" => [
                    "Api-Key" => $discourseApiKey
                ]
            ]);

            if ($response->getStatusCode() === Response::HTTP_OK) {
                $rawResponse = $response->getBody()->getContents();
                $json = json_decode($rawResponse, true);

                if (isset($json["user"]["username"])) {
                    $discourseUsername = $json["user"]["username"];
                } else {
                    $errorList->add(t("Error while looking up the user details. Invalid payload."));
                }
            } else {
                $errorList->add(t("Error while looking up the user details. Invalid status code."));
            }

        } catch (GuzzleException $e) {
            $errorList->add(t("You need to create a user account first at forums.concretecms.org."));
        }

        if (!$errorList->has()) {
            $redirectUrl = (string)$baseUrl
                ->withPath(
                    sprintf(
                        "/u/%s/preferences/account",
                        $discourseUsername
                    )
                );

            return new RedirectResponse($redirectUrl, Response::HTTP_TEMPORARY_REDIRECT);
        } else {
            // redirect to discourse so that a user account can be created
            return new RedirectResponse($config->get("concrete_cms_community.discourse.endpoint"), Response::HTTP_TEMPORARY_REDIRECT);
        }
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
            /** @var EventDispatcher $eventDispatcher */
            $eventDispatcher = $app->make(EventDispatcher::class);

            if ($this->config->has("concrete_cms_community.discourse.endpoint")) {
                $discourseEndpoint = $this->config->get("concrete_cms_community.discourse.endpoint");

                if ($this->config->has("concrete_cms_community.discourse.signature")) {
                    $discourseSignature = $this->config->get("concrete_cms_community.discourse.signature");

                    if ($this->config->has("concrete_cms_community.discourse.api_key")) {
                        $discourseApiKey = $this->config->get("concrete_cms_community.discourse.api_key");

                        if ($this->config->has("concrete_cms_community.discourse.achievements_mapping") &&
                            is_array($this->config->get("concrete_cms_community.discourse.achievements_mapping"))) {

                            $achievementsMapping = $this->config->get("concrete_cms_community.discourse.achievements_mapping");
                            $communityPointsMapping = $this->config->get("concrete_cms_community.discourse.community_points_mapping");

                            if (0 === strpos($this->request->headers->get('Content-Type'), 'application/json')) {
                                $rawResponse = $this->request->getContent();
                                $data = json_decode($rawResponse, true);

                                if (is_array($data) || count($data) === 0) {
                                    if ($this->request->headers->has("X-Discourse-Event")) {
                                        if ($this->request->headers->has("X-Discourse-Event-Type")) {
                                            if ($this->request->headers->has("X-Discourse-Event-Signature")) {
                                                $eventName = $this->request->headers->get("X-Discourse-Event");
                                                $eventType = $this->request->headers->get("X-Discourse-Event-Type");
                                                $eventSignature = substr($this->request->headers->get("X-Discourse-Event-Signature"), 7); // 7 because of the leading "sha256="

                                                $calculatedSignature = hash_hmac('sha256', $rawResponse, $discourseSignature);

                                                if ($calculatedSignature === $eventSignature) {

                                                    if (isset($data[$eventType])) {
                                                        if (isset($data[$eventType]["external_id"])) {
                                                            /*
                                                             * Perfect in this payload the external id is given.
                                                             * No need to use the API to retrieve user details.
                                                             */

                                                            $userInfo = $this->userInfoRepository->getByID($data[$eventType]["external_id"]);

                                                        } else if (isset($data[$eventType]["user_id"])) {
                                                            $userId = $data[$eventType]["user_id"];

                                                            $this->logger->info(t("Event %s raised for discourse user with ID %s", $eventName, $userId));

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

                                                                        if (isset($json["single_sign_on_record"]["external_id"])) {
                                                                            /*
                                                                             * When discourse is running through an SSO there is no mail address
                                                                             * in the user details.
                                                                             *
                                                                             * The only details that available to get the original user account is the property
                                                                             * single_sign_on_record.external_id
                                                                             *
                                                                             * So let's check first if the property is available and if yes we use this property instead
                                                                             * of the mail address to resolve the concrete user account.
                                                                             */

                                                                            $userInfo = $this->userInfoRepository->getByID($json["single_sign_on_record"]["external_id"]);

                                                                        } else if (isset($json["email"]) && filter_var($json["email"], FILTER_VALIDATE_EMAIL)) {
                                                                            $email = $json["email"];

                                                                            $userInfo = $this->userInfoRepository->getByEmail($email);
                                                                        } else {
                                                                            $errorList->add(t("Error while looking up the user details. The received response doesn't contain not an valid email property or an external id property."));
                                                                        }
                                                                    } else {
                                                                        $errorList->add(t("Error while looking up the user details. The received response is empty."));
                                                                    }
                                                                } else {
                                                                    $errorList->add(t("Error while looking up the user details. Invalid status code received."));
                                                                }
                                                            } catch (GuzzleException $e) {
                                                                $errorList->add(t("Error while looking up the user details. Internal server error."));
                                                            }
                                                        } else {
                                                            $errorList->add(t("User id is missing.", $eventType));
                                                        }

                                                        if (isset($userInfo) && $userInfo instanceof UserInfo) {
                                                            $user = $userInfo->getUserObject();

                                                            $event = new DiscourseWebhookCall();
                                                            $event->setUser($user);
                                                            $event->setEventName($eventName);
                                                            $event->setEventType($eventType);
                                                            $event->setPayload($data);
                                                            $eventDispatcher->dispatch("on_discourse_webhook_call", $event);

                                                            if (isset($achievementsMapping[$eventName])) {
                                                                $achievementHandle = $achievementsMapping[$eventName];

                                                                try {
                                                                    $achievement = $awardService->getBadgeByHandle($achievementHandle);
                                                                } catch (Exception $e) {
                                                                    $achievement = null;
                                                                }

                                                                if (isset($achievement)) {
                                                                    try {
                                                                        $awardService->giveBadge($achievement, $user);
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
                                                                    $errorList->add(t("The achievement with the handle %s does not exists.", $achievementHandle));
                                                                }
                                                            }

                                                            if (isset($communityPointsMapping[$eventName])) {
                                                                $communityPoints = (int)$communityPointsMapping[$eventName];

                                                                if ($communityPoints > 0) {
                                                                    $userPointAction = UserPointAction::getByHandle("discourse_action");

                                                                    if (!$userPointAction instanceof UserPointAction) {
                                                                        UserPointAction::add(
                                                                            "discourse_action",
                                                                            t("Discourse Action"),
                                                                            50,
                                                                            null
                                                                        );

                                                                        $userPointAction = UserPointAction::getByHandle("discourse_action");
                                                                    }

                                                                    $userPointActionDescription = new UserPointActionDescription();
                                                                    $userPointActionDescription->setComments(t("Action: %s", $eventName));

                                                                    $userPointAction->addEntry($user, $userPointActionDescription, $communityPoints);
                                                                }
                                                            }
                                                        } else {
                                                            $errorList->add(t("The received mail address from the discourse api is not associated with an user account at this site."));
                                                        }
                                                    } else {
                                                        $errorList->add(t("Item %s is missing in payload.", $eventType));
                                                    }
                                                } else {
                                                    $errorList->add(t("The signature is invalid."));
                                                }
                                            } else {
                                                $errorList->add(t("Header X-Discourse-Event-Signature is missing."));
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
                    $errorList->add(t("You need to define a signature for the discourse integration."));
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
