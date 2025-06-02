<?php

namespace Codefog\PagePasswordBundle;

use Contao\PageModel;
use Symfony\Component\HttpFoundation\RequestStack;

class Authenticator
{
    public function __construct(private RequestStack $requestStack)
    {
    }

    public function isPageProtected(PageModel $pageModel): bool
    {
        $pageModel->loadDetails();

        return $pageModel->passwordProtected && $pageModel->password;
    }

    public function isAuthenticated(PageModel $pageModel): bool
    {
        if (!$pageModel->passwordId) {
            return false;
        }

        $session = $this->requestStack->getSession();

        if (!$session->isStarted()) {
            return false;
        }

        return $session->get($this->getSessionKey($pageModel)) === $pageModel->password;
    }

    public function authenticate(PageModel $pageModel, string $password): bool
    {
        $pageModel->loadDetails();

        if (!$pageModel->passwordId || $pageModel->password !== $password) {
            return false;
        }

        $this->requestStack->getSession()->set($this->getSessionKey($pageModel), $password);

        return true;
    }

    private function getSessionKey(PageModel $pageModel): string
    {
        return sprintf('contao-page-password-%s', $pageModel->passwordId);
    }
}
