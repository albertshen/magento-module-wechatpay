<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Albert\Magento\WeChatPay\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Albert\Payment\Plugin\ParserPlugin;
use Albert\Payment\Plugin\Wechat\PreparePlugin;
use Albert\Payment\Plugin\Wechat\SignPlugin;
use Albert\Payment\Plugin\Wechat\WechatPublicCertsPlugin;

/**
 * A console command that lists all the existing users.
 *
 * To use this command, open a terminal window, enter into your project directory
 * and execute the following:
 *
 *     $ php bin/console app:list-users
 *
 * Check out the code of the src/Command/AddUserCommand.php file for
 * the full explanation about Symfony commands.
 *
 * See https://symfony.com/doc/current/console.html
 *
 * @author Albert Shen <albertshen1206@gmail.com>
 */
class DownloadWeChatCertCommand extends Command
{
    /**
     * @var \Magento\Framework\App\State
     */
    private $appState;

    public function __construct(
        \Magento\Framework\App\State $appState
    ) {
        $this->appState = $appState;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('payment:wechat:cert')
            ->setDescription('Download WeChatPay cert');
        parent::configure();
    }

    /**
     * This method is executed after initialize(). It usually contains the logic
     * to execute to complete this command task.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $paymentGateway = \Magento\Framework\App\ObjectManager::getInstance()->get(\Albert\Magento\WeChatPay\Model\PaymentGateway::class);
        $wechat = $paymentGateway->wechat();
        $data = $wechat->pay(
            [PreparePlugin::class, WechatPublicCertsPlugin::class, SignPlugin::class, ParserPlugin::class],
            []
        )->get('data', []);
        print_r($data);exit;
        foreach ($data as $item) {
            $certs[$item['serial_no']] = decrypt_wechat_resource($item['encrypt_certificate'], [])['ciphertext'] ?? '';
        }
        print_r($certs);
    }

}
