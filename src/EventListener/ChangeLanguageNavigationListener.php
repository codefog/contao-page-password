<?php

namespace Codefog\PagePasswordBundle\EventListener;

use Codefog\PagePasswordBundle\EventSubscriber\AuthenticateSubscriber;
use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\PageModel;
use Symfony\Component\HttpFoundation\RequestStack;
use Terminal42\ChangeLanguage\Event\ChangelanguageNavigationEvent;
use Terminal42\ChangeLanguage\PageFinder;

#[AsHook('changelanguageNavigation')]
class ChangeLanguageNavigationListener
{
    public function __construct(
        private readonly PageFinder $pageFinder,
        private readonly RequestStack $requestStack,
    ) {
    }

    public function __invoke(ChangelanguageNavigationEvent $event): void
    {
        if (!$request = $this->requestStack->getMainRequest()) {
            return;
        }

        if (!$request->attributes->has(AuthenticateSubscriber::REQUEST_ATTRIBUTE)) {
            return;
        }

        if (!$sourcePage = PageModel::findPublishedById($request->attributes->get(AuthenticateSubscriber::REQUEST_ATTRIBUTE))) {
            return;
        }

        $item = $event->getNavigationItem();

        if (!$targetPage = $this->pageFinder->findAssociatedForLanguage($sourcePage, $item->getRootPage()->rootLanguage)) {
            $targetPage = $sourcePage;
        }

        $item->setTargetPage($targetPage, $item->isDirectFallback(), str_starts_with($request->getUri(), $targetPage->getAbsoluteUrl()));
    }
}
