<?php

namespace Codefog\PagePasswordBundle\Controller\FrontendModule;

use Codefog\PagePasswordBundle\Authenticator;
use Codefog\PagePasswordBundle\EventSubscriber\AuthenticateSubscriber;
use Contao\Controller;
use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\Csrf\ContaoCsrfTokenManager;
use Contao\CoreBundle\Exception\PageNotFoundException;
use Contao\CoreBundle\ServiceAnnotation\FrontendModule;
use Contao\ModuleModel;
use Contao\PageModel;
use Contao\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @FrontendModule("page_password", category="application")
 */
class PagePasswordController extends AbstractFrontendModuleController
{
    public function __construct(
        private Authenticator $authenticator,
        private ContaoCsrfTokenManager $tokenManager,
        private TranslatorInterface $translator,
    )
    {
    }

    protected function getResponse(Template $template, ModuleModel $model, Request $request): ?Response
    {
        if (!$request->attributes->has(AuthenticateSubscriber::REQUEST_ATTRIBUTE)
            || ($sourcePageModel = PageModel::findPublishedById($request->attributes->getInt(AuthenticateSubscriber::REQUEST_ATTRIBUTE))) === null
        ) {
            throw new PageNotFoundException();
        }

        $formId = sprintf('page-password-%s', $model->id);

        if ($request->request->get('FORM_SUBMIT') === $formId) {
            $result = $this->authenticator->authenticate($sourcePageModel, $request->request->get('password'));

            if ($result) {
                Controller::reload();
            } else {
                $template->error = $this->translator->trans('MSC.pagePasswordInvalid', [], 'contao_default');
            }
        }

        $template->formId = $formId;
        $template->requestToken = $this->tokenManager->getDefaultTokenValue();

        return $template->getResponse();
    }
}
