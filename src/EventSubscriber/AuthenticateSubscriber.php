<?php

namespace Codefog\PagePasswordBundle\EventSubscriber;

use Codefog\PagePasswordBundle\Authenticator;
use Contao\CoreBundle\Exception\AccessDeniedException;
use Contao\FrontendIndex;
use Contao\PageModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AuthenticateSubscriber implements EventSubscriberInterface
{
    public const REQUEST_ATTRIBUTE = '_page_password_id';

    public function __construct(private Authenticator $authenticator)
    {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $pageModel = $request->attributes->get('pageModel');

        // Page model not available
        if (!($pageModel instanceof PageModel)) {
            return;
        }

        // Page is not protected
        if (!$this->authenticator->isPageProtected($pageModel)) {
            return;
        }

        // Visitor is authenticated
        if ($this->authenticator->isAuthenticated($pageModel)) {
            return;
        }

        // The password page does not exist
        if (!$pageModel->passwordPage || ($passwordPageModel = PageModel::findPublishedById($pageModel->passwordPage)) === null) {
            throw new AccessDeniedException();
        }

        $request->attributes->set(self::REQUEST_ATTRIBUTE, $pageModel->id);
        $event->setResponse((new FrontendIndex())->renderPage($passwordPageModel));
    }

    public static function getSubscribedEvents()
    {
        return [KernelEvents::REQUEST => 'onKernelRequest'];
    }
}
