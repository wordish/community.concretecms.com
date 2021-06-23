<?php /** @noinspection PhpInconsistentReturnPointsInspection */
/** @noinspection PhpUnused */

/**
 * @project:   ConcreteCMS Community
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace Concrete\Package\ConcreteCmsCommunity\Controller\SinglePage\Account;

use Concrete\Core\Database\Connection\Connection;
use Concrete\Core\Entity\Package;
use Concrete\Core\Http\Response;
use Concrete\Core\Http\ResponseFactory;
use Concrete\Core\Package\PackageService;
use Concrete\Core\Page\Controller\PageController;
use Concrete\Core\Search\Pagination\Pagination;
use Concrete\Core\Search\Pagination\PaginationFactory;
use Concrete\Core\Support\Facade\Url;
use Concrete\Core\User\UserInfoRepository;
use PortlandLabs\CommunityBadges\User\Point\Entry;
use PortlandLabs\CommunityBadges\User\Point\EntryList as UserPointEntryList;
use Concrete\Core\User\User;
use PortlandLabs\Community\Page\Controller\AccountPageController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Concrete\Core\Localization\Service\Date;
use Exception;

class Karma extends PageController
{
    /** @var Connection */
    protected $db;
    /** @var User */
    protected $user;
    /** @var Date $dateService */
    protected $dateService;

    public function on_start()
    {
        parent::on_start();
        $this->db = $this->app->make(Connection::class);
        $this->user = new User();
        $this->dateService = $this->app->make(Date::class);

        /** @var PackageService $packageService */
        $packageService = $this->app->make(PackageService::class);
        if (!$packageService->getByHandle("community_badges") instanceof Package) {
            /** @var ResponseFactory $responseFactory */
            $responseFactory = $this->app->make(ResponseFactory::class);
            $responseFactory->create(t("You need to install the Community Badges add-on to view this site."), Response::HTTP_OK)->send();
            $this->app->shutdown();
        }
    }

    private function getActionList()
    {
        $actionList = [];

        foreach ($this->db->fetchAll("SELECT upaID, upaName FROM UserPointActions") as $row) {
            $actionList[$row["upaID"]] = $row["upaName"];
        }

        return $actionList;
    }

    private function getMyTotalList($uID)
    {
        $myTotalList = [];

        foreach ($this->getActionList() as $actionId => $actionName) {
            $totalByAction = (int)$this->db->fetchColumn("SELECT SUM(upPoints) FROM UserPointHistory WHERE upaID = ? AND upuID = ?", [
                $actionId,
                $uID,
            ]);

            if ($totalByAction > 0) {
                $myTotalList[$actionName] = $totalByAction;
            }
        }

        return $myTotalList;
    }

    public function fetch_results($uID = null)
    {
        list($profile, $loadMoreURL) = $this->getKarmaUser($uID);
        if (!$profile) {
            throw new \Exception(t('Invalid user to retrieve karma for.'));
        }

        $results = [];
        list($filterUser, $sortList) = $this->getFilterOptions();

        $entryList = new UserPointEntryList();
        if ($filterUser == 'user') {
            $entryList->filterByUserID($profile->getUserID());
        }
        $entryList->setItemsPerPage(20);
        if ($sortList == 'recent') {
            $entryList->sortBy('uph.timestamp', 'desc');
        } else {
            // sort by points
            $entryList->sortBy('upPoints', 'desc');
        }
        /** @var PaginationFactory $factory */
        $factory = $this->app->make(PaginationFactory::class, [$this->request]);
        /** @var Pagination $pagination */
        $pagination = $factory->createPaginationObject($entryList);

        foreach ($pagination->getCurrentPageResults() as $entry) {
            $result = [];

            /** @var Entry $entry */
            $targetUser = $entry->getUserPointEntryUserObject();
            $result["avatar"] = $targetUser->getUserAvatar()->getPath();
            $result["username"] = $targetUser->getUserName();

            try {
                $date = $this->dateService->formatDateTime($entry->getUserPointEntryDateTime());
            } catch (Exception $e) {
                $date = t("n/a");
            }

            /** @noinspection HtmlUnknownTarget */
            $result["info"] = t("Awarded to %s on %s",
                sprintf(
                    "<a href=\"%s\">%s</a>",
                    (string)Url::to("/members/profile", $entry->getUserPointEntryUserID()),
                    $targetUser->getUserName()
                ),
                $date
            );

            if (is_object($entry->getUserPointEntryActionObject())) {
                $result["title"] = $entry->getUserPointEntryActionObject()->getUserPointActionName();
            } else {
                $result["title"] = t("Received Extra-Karma");
            }

            if (strlen($entry->getUserPointEntryDescription()) > 0) {
                $result["description"] = $entry->getUserPointEntryDescription();
            } else {
                $result["description"] = t("Thanks for taking the time!");
            }

            $result["points"] = number_format($entry->getUserPointEntryValue());

            $results[] = $result;
        }

        return new JsonResponse([
            "results" => $results,
            "hasNextPage" => $pagination->hasNextPage()
        ]);
    }

    private function getKarmaUser($uID = null)
    {
        $repository = $this->app->make(UserInfoRepository::class);
        $u = $this->app->make(User::class);
        $profile = null;
        $loadMoreURL = null;
        if ($uID > 0) {
            $profile = $repository->getByID($uID);
            $loadMoreURL = \URL::to('/account/karma', 'fetch_results', h($uID));
        } else {
            if ($u->isRegistered()) {
                $profile = $repository->getByID($u->getUserID());
                $loadMoreURL = \URL::to('/account/karma', 'fetch_results', $profile->getUserID());
            }
        }

        if (!empty($_SERVER['QUERY_STRING'])) {
            $loadMoreURL .= '?' . $_SERVER['QUERY_STRING'];
        }
        return [$profile, $loadMoreURL];
    }

    private function getFilterOptions()
    {
        $filterUser = 'user';
        $sortList = 'recent';
        if ($this->request->query->has('filterUser') && in_array($this->request->query->get('filterUser'),
                ['user', 'all'])) {
            $filterUser = $this->request->query->get('filterUser');
        }
        if ($this->request->query->has('sortList') && in_array($this->request->query->get('sortList'),
                ['points', 'recent'])) {
            $sortList = $this->request->query->get('sortList');
        }

        return [$filterUser, $sortList];
    }

    public function view($uID = null)
    {
        list($profile, $loadMoreURL) = $this->getKarmaUser($uID);
        if (!$profile) {
            return $this->replace('/login');
        }

        list($filterUser, $sortList) = $this->getFilterOptions();
        if ($filterUser == 'all') {
            $karmaDescriptionText = t('Karma Earned (Everyone)');
        } else {
            $karmaDescriptionText = t('Karma Earned');
        }

        $entryList = new UserPointEntryList();
        if ($filterUser == 'user') {
            $entryList->filterByUserID($profile->getUserID());
        }
        $entryList->setItemsPerPage(20);
        if ($sortList == 'recent') {
            $entryList->sortBy('uph.timestamp', 'desc');
        } else {
            // sort by points
            $entryList->sortBy('upPoints', 'desc');
        }
        /** @var PaginationFactory $factory */
        $factory = $this->app->make(PaginationFactory::class, [$this->request]);
        /** @var Pagination $pagination */
        $pagination = $factory->createPaginationObject($entryList);
        $entries = $pagination->getCurrentPageResults();

        $this->set('entries', $entries);
        $this->set('myTotalList', $this->getMyTotalList($profile->getUserID()));
        $this->set('hasNextPage', $pagination->hasNextPage());
        $this->set('loadMoreURL', $loadMoreURL);
        $this->set('profile', $profile);
        $this->set('filterUser', $filterUser);
        $this->set('sortList', $sortList);
        $this->set('karmaDescriptionText', $karmaDescriptionText);


        $this->requireAsset("javascript", "community/karma");

    }
}