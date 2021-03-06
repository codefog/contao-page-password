<?php

namespace Codefog\PagePasswordBundle\EventListener;

use Codefog\PagePasswordBundle\Authenticator;
use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\PageModel;

/**
 * @Hook(value="getPageStatusIcon")
 */
class PageStatusIconListener
{
    public function __construct(private Authenticator $authenticator)
    {
    }

    public function __invoke($page, string $image): string
    {
        if (!($page instanceof PageModel)) {
            $page = PageModel::findByPk($page->id);
        }

        if ($page !== null && $this->authenticator->isPageProtected($page)) {
            $sub = 4;

            // Get the sub from the existing icon, if any
            if (str_contains($image, '_')) {
                $sub = (int) substr($image, -5, 1);

                // Bump the sub only if it does not include the password protection yet
                if ($sub < 4) {
                    $sub += 4;
                }
            }

            $image = sprintf('%s_%s.svg', $page->type, $sub);
        }

        return $image;
    }
}
