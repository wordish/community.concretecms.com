<?php
namespace Application\Controller\SinglePage;

use Concrete\Core\Attribute\Category\CategoryService;
use Concrete\Core\Attribute\Context\FrontendFormContext;
use Concrete\Core\Attribute\Form\Renderer;
use Concrete\Core\Attribute\Key\UserKey as UserAttributeKey;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Http\Response;
use Concrete\Core\Page\Controller\PageController;
use Concrete\Core\Support\Facade\UserInfo;
use Concrete\Core\User\User;
use League\Url\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;

class Register extends \Concrete\Controller\SinglePage\Register
{

    protected const SESSION_RETURN = 'ccms.return';

    public function view(): void
    {
        $request = $this->request;

        $return = $request->get('re');
        if ($return) {
            $this->storeReturnUrl($return);
        }
    }

    private function storeReturnUrl(string $return): void
    {
        $session = app('session');
        // Clear just in case
        $session->remove(self::SESSION_RETURN);

        try {
            $return = Url::createFromUrl($return);
        } catch (\RuntimeException $exception) {
            // Ignore parse errors. Only redirect to valid urls.
            return;
        }

        // Only allow .concretecms.com/org/test urls
        if (!preg_match('/^.+?\.concretecms\.(com|org|test|com\.test)/', $return->getHost())) {
            return;
        }

        $session->set(self::SESSION_RETURN, $return);
    }

    public function register_success($rcID = 0): ?RedirectResponse
    {
        $session = app('session');
        $return = $session->get(self::SESSION_RETURN);

        if ($return) {
            $session->remove(self::SESSION_RETURN);
            return $this->buildRedirect($return, Response::HTTP_TEMPORARY_REDIRECT);
        }

        return parent::register_success($rcID);
    }
}
