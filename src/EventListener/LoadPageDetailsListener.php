<?php

namespace Codefog\PagePasswordBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\PageModel;

#[AsHook('loadPageDetails')]
class LoadPageDetailsListener
{
    public function __invoke(array $parentPages, PageModel $currentPage): void
    {
        if ($currentPage->passwordProtected) {
            $currentPage->passwordId = $currentPage->id;

            return;
        }

        foreach ($parentPages as $parentPage) {
            if ($parentPage->passwordProtected) {
                $currentPage->passwordId = $parentPage->id;
                $currentPage->passwordProtected = $parentPage->passwordProtected;
                $currentPage->password = $parentPage->password;
                $currentPage->passwordPage = $parentPage->passwordPage;
                break;
            }
        }
    }
}
