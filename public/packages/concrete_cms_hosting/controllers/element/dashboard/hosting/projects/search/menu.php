<?php /** @noinspection DuplicatedCode */

namespace Concrete\Package\ConcreteCmsHosting\Controller\Element\Dashboard\Hosting\Projects\Search;

use Concrete\Core\Controller\ElementController;
use Concrete\Core\Entity\Search\Query;
use PortlandLabs\Hosting\Project\Search\SearchProvider;
use Concrete\Core\Form\Service\Form;
use Concrete\Core\Utility\Service\Url;
use Concrete\Core\Validation\CSRF\Token;

class Menu extends ElementController
{
    protected $query;
    protected $searchProvider;

    public function __construct(SearchProvider $searchProvider)
    {
        parent::__construct();
        $this->searchProvider = $searchProvider;
    }

    public function getElement()
    {
        return 'dashboard/hosting/projects/search/menu';
    }

    public function setQuery(Query $query): void
    {
        $this->query = $query;
    }

    public function view()
    {
        $itemsPerPage = (isset($this->query)) ? $this->query->getItemsPerPage() : $this->searchProvider->getItemsPerPage();
        $this->set('itemsPerPage', $itemsPerPage);
        $this->set('itemsPerPageOptions', $this->searchProvider->getItemsPerPageOptions());
        $this->set('form', $this->app->make(Form::class));
        $this->set('token', $this->app->make(Token::class));
        $this->set('urlHelper', $this->app->make(Url::class));
        $this->set('projectTypes', ['lagoon' => t('Concrete Hosting'), 'project' => t('Third Party')]);
    }

}
