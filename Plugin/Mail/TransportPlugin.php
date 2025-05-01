<?php
/**
 * Ebizmarts_MandrillSmtp
 *
 * @category    Ebizmarts
 * @package     Ebizmarts_MandrillSmtp
 * @author      Ebizmarts Team <info@ebizmarts.com>
 * @copyright   Ebizmarts (http://ebizmarts.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Ebizmarts\MandrillSmtp\Plugin\Mail;

use Closure;
use Ebizmarts\MandrillSmtp\Helper\Data as HelperData;
use Magento\Email\Model\Transport;
use Symfony\Component\Mailer\Transport\Smtp\Auth\LoginAuthenticator;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;

class TransportPlugin
{
    private $helper;
    private $options;

    public function __construct(
        HelperData $helper
    )
    {
        $this->helper = $helper;
    }

    public function aroundGetTransport(
        Transport $subject,
        callable $proceed
    )
    {
        if ($this->helper->isEnabled()) {
            $transport = new EsmtpTransport(HelperData::HOST, HelperData::PORT, false);
            $transport->setUsername($this->helper->getUsername());
            $transport->setPassword($this->helper->getApiKey());
            $transport->setAuthenticators([new LoginAuthenticator()]);
            return $transport;
        } else {
            return $proceed();
        }
    }

}
