<?php

/**
 * @project:   ConcreteCMS Community
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection SqlDialectInspection */
/** @noinspection SqlNoDataSourceInspection */
/** @noinspection PhpMissingFieldTypeInspection */

namespace Concrete\Package\ConcreteCmsCommunity\Block\MembersSearch;

use Concrete\Attribute\Select\Controller as SelectAttributeController;
use Concrete\Core\Attribute\Category\CategoryService;
use Concrete\Core\Block\BlockController;
use Concrete\Core\Database\Connection\Connection;
use Concrete\Core\Entity\Attribute\Value\Value\SelectValue;
use Concrete\Core\Entity\Express\Entity;
use Concrete\Core\Entity\Express\Entry;
use Concrete\Core\Entity\Package;
use Concrete\Core\Express\EntryList;
use Concrete\Core\Express\ObjectManager;
use Concrete\Core\Package\PackageService;
use Concrete\Core\User\UserList;

class Controller extends BlockController
{
    protected $btTable = "btMembersSearch";

    const SORT_BY_MOST_RECENT = "most_recent";
    const SORT_BY_EARLIEST_JOINED = "earliest_joined";
    const SORT_BY_COMMUNITY_LEADERS = "community_leaders";
    const SORT_BY_ACHIEVEMENTS = "achievements";

    public function getBlockTypeDescription()
    {
        return t('Integrate a members search into your page.');
    }

    public function getBlockTypeName()
    {
        return t('Members Search');
    }

    private function addOrEdit()
    {
        $availableAttributes = [];

        /** @var CategoryService $categoryService */
        $categoryService = $this->app->make(CategoryService::class);
        $userCategoryEntity = $categoryService->getByHandle("user");
        $userCategory = $userCategoryEntity->getAttributeKeyCategory();

        foreach ($userCategory->getList() as $attributeKey) {
            if ($attributeKey->getController() instanceof SelectAttributeController) {
                $akHandle = $attributeKey->getAttributeKeyHandle();
                $akName = $attributeKey->getAttributeKeyDisplayName();
                $availableAttributes[$akHandle] = $akName;
            }
        }

        $this->set("availableAttributes", $availableAttributes);
    }

    private function setDefaults()
    {
        /** @var PackageService $packageService */
        $packageService = $this->app->make(PackageService::class);
        $certificationPackageEntity = $packageService->getByHandle("certification");

        $displayCertificationFilter = $certificationPackageEntity instanceof Package;
        $certificationTestOptions = [];
        $selectedCertificationsTests = [];

        if ($displayCertificationFilter) {
            $temp = array_keys($this->request->query->get("certification", []));

            /** @var ObjectManager $objectManager */
            $objectManager = $this->app->make(ObjectManager::class);
            /** @var Entity $testEntity */
            $testEntity = $objectManager->getObjectByHandle("certification_test");

            if ($testEntity instanceof Entity) {
                $testList = new EntryList($testEntity);
                /** @var Entry[] $testEntries */
                $testEntries = $testList->getResults();

                foreach ($testEntries as $testEntry) {
                    $testId = $testEntry->getID();
                    $testName = $testEntry->getAttribute("certification_test_name");

                    $certificationTestOptions[$testId] = $testName;

                    if (in_array($testId, $temp)) {
                        $selectedCertificationsTests[] = $testId;
                    }
                }
            }
        }

        $selectedAttributeKeys = [];
        /** @var Connection $db */
        $db = $this->app->make(Connection::class);
        $rows = $db->fetchAll("SELECT akHandle from btMembersSearchEntries WHERE bID = ?", [$this->bID]);

        foreach ($rows as $row) {
            $selectedAttributeKeys[] = $row["akHandle"];
        }

        $q = $this->request->query->get("q", "");
        $certifiedOnly = $this->request->query->get('c', '1') !== '0';
        $defaultSortByOption = self::SORT_BY_ACHIEVEMENTS;
        $sortBy = $this->request->query->get("sortBy", $defaultSortByOption);

        $sortByOptions = [
//            self::SORT_BY_COMMUNITY_LEADERS => t("Community Leaders"),
            self::SORT_BY_ACHIEVEMENTS => t("Achievements"),
            self::SORT_BY_MOST_RECENT => t("Most Recent"),
            self::SORT_BY_EARLIEST_JOINED => t("Earliest Joined")
        ];

        if (!in_array($sortBy, array_keys($sortByOptions))) {
            $sortBy = $defaultSortByOption;
        }

        $this->set("q", $q);
        $this->set("certifiedOnly", $certifiedOnly);
        $this->set("sortBy", $sortBy);
        $this->set("sortByOptions", $sortByOptions);
        $this->set("displayCertificationFilter", $displayCertificationFilter);
        $this->set("certificationTestOptions", $certificationTestOptions);
        $this->set("selectedCertificationsTests", $selectedCertificationsTests);
        $this->set("selectedAttributeKeys", $selectedAttributeKeys);
    }

    public function add()
    {
        $this->addOrEdit();
        $this->set("selectedAttributeKeys", []);
    }

    public function edit()
    {
        $this->addOrEdit();

        $selectedAttributeKeys = [];
        /** @var Connection $db */
        $db = $this->app->make(Connection::class);
        $rows = $db->fetchAll("SELECT akHandle from btMembersSearchEntries WHERE bID = ?", [$this->bID]);

        foreach ($rows as $row) {
            $selectedAttributeKeys[$row["akHandle"]] = $row["akHandle"];
        }

        $this->set("selectedAttributeKeys", $selectedAttributeKeys);
    }

    public function duplicate($newBID)
    {
        parent::duplicate($newBID);
        /** @var Connection $db */
        $db = $this->app->make(Connection::class);
        $db->executeUpdate(
            "INSERT INTO btMembersSearchEntries (bID, akHandle) SELECT ?, akHandle FROM btMembersSearchEntries WHERE bID = ?",
            [
                $newBID,
                $this->bID
            ]
        );
    }

    public function delete()
    {
        /** @var Connection $db */
        $db = $this->app->make(Connection::class);
        $db->executeQuery('DELETE from btMembersSearchEntries WHERE bID = ?', [$this->bID]);
        parent::delete();
    }

    public function save($args)
    {
        /** @var Connection $db */
        $db = $this->app->make(Connection::class);

        parent::save($args);

        $db->executeQuery('DELETE from btMembersSearchEntries WHERE bID = ?', [$this->bID]);

        if (is_array($args["selectedAttributeKeys"])) {
            foreach ($args["selectedAttributeKeys"] as $akHandle) {
                $db->executeQuery('INSERT INTO btMembersSearchEntries (bID, akHandle) values(?, ?)',
                    [
                        $this->bID,
                        $akHandle
                    ]
                );
            }
        }
    }

    public function view()
    {
        $this->setDefaults();
        $sets = $this->getSets();
        $certifiedOnly = $sets['certifiedOnly'] ?? true;

        $userList = new UserList();
        $userList->setIncludeEmailInKeywordSearch(false);
        if ($sets['q'] ?? false) {
            $userList->filterByKeywords($sets['q']);
        }

        switch ($sets["sortBy"] ?? '') {
            case self::SORT_BY_ACHIEVEMENTS:
                $userList->getQueryObject()->leftJoin("u", "UserBadge", "userBadges", "u.uID = userBadges.uID");
                $userList->getQueryObject()->addSelect("COUNT(userBadges.id) AS totalBadges");
                $userList->sortBy("totalBadges", "DESC");
                break;
            case self::SORT_BY_COMMUNITY_LEADERS:
                // @todo - change this use a user attribute in the index table.
                $userList->getQueryObject()->leftJoin("u", "UserPointHistory", "userPoints", "u.uID = userPoints.upuID");
                $userList->getQueryObject()->addSelect("SUM(userPoints.upPoints) AS totalPoints");
                $userList->sortBy("totalPoints", "DESC");
                break;

            case self::SORT_BY_EARLIEST_JOINED:
                $userList->sortBy("uDateAdded", "ASC");
                break;

            case self::SORT_BY_MOST_RECENT:
                $userList->sortBy("uDateAdded", "DESC");
                break;
        }

        if ($sets["displayCertificationFilter"]) {
            $selectedTests = $sets["selectedCertificationsTests"];

            if (count($selectedTests) > 0) {
                foreach ($selectedTests as $testId) {
                    $userList->getQueryObject()->join(
                        "u",
                        "TestResult",
                        "test" . intval($testId),
                        sprintf(
                            "u.uID = test%s.uID AND test%s.passed = 1 AND test%s.exEntryID = %s",
                            intval($testId),
                            intval($testId),
                            intval($testId),
                            intval($testId)
                        )
                    );
                }
            }
        }

        if ($certifiedOnly) {
            $q = $userList->getQueryObject();
            $badges = collect($q->getConnection()->fetchAllNumeric('select id from Badge where handle like "certification_test_pass_%"'))
                ->flatten();
            $q->innerJoin('u', 'UserBadge', 'bdg', 'bdg.badgeId in (:badges) AND bdg.uID=u.uID');
            $q->setParameter('badges', $badges->toArray(), $q->getConnection()::PARAM_INT_ARRAY);
        }

        /** @noinspection PhpDeprecationInspection */
        $this->set("pagination", $userList->getPagination());
    }
}
