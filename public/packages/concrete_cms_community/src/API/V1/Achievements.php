<?php /** @noinspection PhpMissingFieldTypeInspection */

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
use Concrete\Core\File\Import\FileImporter;
use Concrete\Core\File\Service\File;
use Concrete\Core\Http\Request;
use Concrete\Core\Package\PackageService;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\User\UserInfo;
use Concrete\Core\User\UserInfoRepository;
use PortlandLabs\CommunityBadges\AwardService;
use PortlandLabs\CommunityBadges\Entity\Achievement;
use PortlandLabs\CommunityBadges\Entity\Award;
use PortlandLabs\CommunityBadges\Exceptions\AchievementAlreadyExists;
use PortlandLabs\CommunityBadges\Exceptions\MailTransportError;
use PortlandLabs\CommunityBadges\Exceptions\NoUserSelected;
use Symfony\Component\HttpFoundation\JsonResponse;

class Achievements
{
    protected $request;
    protected $userInfoRepository;
    protected $fileImporter;
    protected $fileService;
    protected $config;
    protected $packageService;

    public function __construct(
        Request $request,
        UserInfoRepository $userInfoRepository,
        FileImporter $fileImporter,
        File $fileService,
        Repository $config,
        PackageService $packageService
    )
    {
        $this->request = $request;
        $this->userInfoRepository = $userInfoRepository;
        $this->fileImporter = $fileImporter;
        $this->fileService = $fileService;
        $this->config = $config;
        $this->packageService = $packageService;
    }

    public function assign()
    {
        $editResponse = new EditResponse();
        $errorList = new ErrorList();

        $communityBadgesPackage = $this->packageService->getByHandle("community_badges");

        if ($communityBadgesPackage instanceof Package) {
            $app = Application::getFacadeApplication();
            /** @var AwardService $awardService */
            $awardService = $app->make(AwardService::class);

            $payload = json_decode($this->request->getContent(), true);

            if (isset($payload["user"]) &&
                is_array($payload["user"]) &&
                isset($payload["user"]["email"]) &&
                isset($payload["achievement"]) &&
                is_array($payload["achievement"]) &&
                isset($payload["achievement"]["handle"])) {

                $email = $payload["user"]["email"];
                $achievementHandle = $payload["achievement"]["handle"];

                $userInfo = $this->userInfoRepository->getByEmail($email);

                if ($userInfo instanceof UserInfo) {
                    $userObject = $userInfo->getUserObject();

                    try {
                        $badge = $awardService->getBadgeByHandle($achievementHandle);
                    } catch (\Exception $e) {
                        $badge = null;
                    }

                    try {
                        if ($badge instanceof Achievement) {
                            $awardService->giveAchievement($badge, $userObject);
                        } else if ($badge instanceof Award) {
                            $awardService->giveAward($badge, $userObject);
                        }

                        $editResponse->setMessage(t("Successfully assigned badge to user."));
                    } catch (AchievementAlreadyExists $e) {
                        $errorList->add(t("The user already has the achievement."));
                    } catch (MailTransportError $e) {
                        $errorList->add(t("Error while sending confirmation mail."));
                    } catch (NoUserSelected $e) {
                        $errorList->add(t("User not found."));
                    }
                } else {
                    $errorList->add(t("User not found."));
                }

            } else {
                $errorList->add(t("Invalid payload schema."));
            }
        } else {
            $errorList->add(t("Community Badges package is not installed."));
        }

        $editResponse->setError($errorList);

        return new JsonResponse($editResponse);
    }
}