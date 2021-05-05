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
use Concrete\Core\Entity\File\Version;
use Concrete\Core\Entity\Package;
use Concrete\Core\Error\ErrorList\ErrorList;
use Concrete\Core\File\Import\FileImporter;
use Concrete\Core\File\Import\ImportException;
use Concrete\Core\File\Service\File;
use Concrete\Core\Http\Request;
use Concrete\Core\Package\PackageService;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\User\UserInfo;
use Concrete\Core\User\UserInfoRepository;
use PortlandLabs\CommunityBadges\AwardService;
use PortlandLabs\CommunityBadges\Entity\Achievement;
use PortlandLabs\CommunityBadges\Entity\Award;
use PortlandLabs\CommunityBadges\Entity\Badge;
use PortlandLabs\CommunityBadges\Exceptions\AchievementAlreadyExists;
use PortlandLabs\CommunityBadges\Exceptions\BadgeNotFound;
use PortlandLabs\CommunityBadges\Exceptions\MailTransportError;
use PortlandLabs\CommunityBadges\Exceptions\NoUserSelected;
use Symfony\Component\HttpFoundation\JsonResponse;

class Badges
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
                isset($payload["isAchievement"]) &&
                isset($payload["badge"]) &&
                is_array($payload["badge"]) &&
                isset($payload["badge"]["name"]) &&
                isset($payload["badge"]["description"]) &&
                isset($payload["badge"]["image"]) &&
                is_array($payload["badge"]["image"]) &&
                isset($payload["badge"]["image"]["name"]) &&
                isset($payload["badge"]["image"]["data"])) {

                $email = $payload["user"]["email"];

                $isAchievement = $payload["isAchievement"];
                $badgeName = $payload["badge"]["name"];
                $badgeDescription = $payload["badge"]["description"];

                $imageName = $payload["badge"]["image"]["name"];
                $imageData = $payload["badge"]["image"]["data"];

                $userInfo = $this->userInfoRepository->getByEmail($email);

                $imageDataArray = explode(',', $imageData);

                if (count($imageDataArray) === 2) {
                    $rawImageData = base64_decode($imageDataArray[1]);

                    if ($userInfo instanceof UserInfo) {
                        $userObject = $userInfo->getUserObject();

                        try {
                            $badge = $awardService->getBadgeByName($badgeName);
                        } catch (\Exception $e) {
                            $badge = null;
                        }

                        if (!$badge instanceof Badge) {
                            /*
                             * Import the badge image
                             */

                            // Create local temp file
                            $localFilename = "";

                            $tempDirectory = sys_get_temp_dir();

                            if (!$tempDirectory) {
                                $tempDirectory = $this->fileService->getTemporaryDirectory();
                            }

                            $localFilename = $tempDirectory . DIRECTORY_SEPARATOR . $imageName;

                            // Write file data
                            $this->fileService->append($localFilename, $rawImageData);

                            // Import file from local temp file
                            $badgeImageFileVersion = null;

                            try {
                                $badgeImageFileVersion = $this->fileImporter->importLocalFile($localFilename, $imageName);
                            } catch (ImportException $e) {
                                $errorList->add(t("Error while importing the file."));
                            }

                            // Delete the temp file
                            @unlink($localFilename);

                            if ($badgeImageFileVersion instanceof Version) {
                                $badgeImage = $badgeImageFileVersion = $badgeImageFileVersion->getFile();

                                // Create the badge
                                if ($isAchievement) {
                                    $badge = new Achievement();
                                } else {
                                    $badge = new Award();
                                }

                                $badge->setDescription($badgeDescription);
                                $badge->setName($badgeName);
                                $badge->setThumbnail($badgeImage);

                                $awardService->saveBadge($badge);
                            }
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
                    $errorList->add(t("Invalid image schema."));
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