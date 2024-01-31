<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Pages;

//Classes
use BeycanPress\CryptoPayLite\Helpers;
use BeycanPress\CryptoPayLite\PluginHero\Page;
use BeycanPress\CryptoPayLite\PluginHero\Hook;
use BeycanPress\CryptoPayLite\PluginHero\Table;
use BeycanPress\CryptoPayLite\Models\AbstractTransaction;
// Types
use BeycanPress\CryptoPayLite\Types\Transaction\TransactionType;
use BeycanPress\CryptoPayLite\Types\Enums\TransactionStatus as Status;

/**
 * Order transactions page
 */
class TransactionPage extends Page
{
    /**
     * @var array<Closure>
     */
    private array $hooks;

    /**
     * @var string
     */
    public string $pageUrl;

    /**
     * @var array<string>
     */
    private static array $slugs = [];

    /**
     * @var array<string>
     */
    private array $hideColumns = [];

    /**
     * @var AbstractTransaction|null
     */
    private ?AbstractTransaction $model;

    /**
     * @param string $name
     * @param string $addon
     * @param int $priority
     * @param array<Closure> $hooks
     * @param array<string> $hideColumns
     */
    public function __construct(
        string $name,
        string $addon,
        int $priority = 10,
        array $hooks = [],
        array $hideColumns = []
    ) {
        $slug = Helpers::getProp('pluginKey') . '_' . sanitize_title($addon) . '_transactions';
        $slug = Helpers::getProp('pluginKey') . '_' . sanitize_title($addon) . '_transactions';

        if (in_array($slug, self::$slugs)) {
            throw new \Exception('This slug is already registered, please choose another slug!');
        }

        self::$slugs[] = $slug;

        $this->hooks = $hooks;
        $this->hideColumns = $hideColumns;
        $this->model = Helpers::getModelByAddon($addon);
        $this->pageUrl = admin_url('admin.php?page=' . $slug);

        parent::__construct([
            'slug' => $slug,
            'pageName' => $name,
            'priority' => $priority,
            'parent' => Helpers::getPage('HomePage')->getSlug(),
        ]);
    }

    /**
     * @return void
     */
    public function page(): void
    {
        $code = isset($_GET['code']) ? $_GET['code'] : 'all';
        $status = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : null;

        if (isset($_GET['id']) && $this->model->delete(['id' => absint($_GET['id'])])) {
            Helpers::notice(esc_html__('Successfully deleted!', 'cryptopay_lite'), 'success', true);
        }

        $params = [];

        if ($code != 'all') {
            $params['code'] = $code;
        }

        if ($status) {
            $params['status'] = $status;
        }

        Hook::callAction('transaction_page', $params, $code, $status);

        $table = (new Table([], $params))
        ->setColumns(array_filter([
            'hash'      => esc_html__('Hash', 'cryptopay_lite'),
            'orderId'   => esc_html__('Order ID', 'cryptopay_lite'),
            'userId'    => esc_html__('User ID', 'cryptopay_lite'),
            'network'   => esc_html__('Network', 'cryptopay_lite'),
            'amount'    => esc_html__('Amount', 'cryptopay_lite'),
            'status'    => esc_html__('Status', 'cryptopay_lite'),
            'addresses' => esc_html__('Addresses', 'cryptopay_lite'),
            'updatedAt' => esc_html__('Updated at', 'cryptopay_lite'),
            'createdAt' => esc_html__('Created at', 'cryptopay_lite'),
            'delete'    => esc_html__('Delete', 'cryptopay_lite')
        ], function ($key) {
            return !in_array($key, $this->hideColumns);
        }, ARRAY_FILTER_USE_KEY))
        ->setOptions([
            'search' => [
                'id' => 'search-box',
                'title' => esc_html__('Search...', 'cryptopay_lite')
            ]
        ])
        ->setOrderQuery(['createdAt', 'desc'])
        ->setSortableColumns(['createdAt'])
        ->addHooks(array_merge([
            'hash' => function ($tx) {
                if (Helpers::providerExists($tx->code)) {
                    $transaction = Helpers::getProvider(TransactionType::fromObject($tx));
                    $transactionUrl = $transaction->Transaction($tx->hash)->getUrl();
                    return Helpers::view('components/link', [
                        'text' => $tx->hash,
                        'url' => $transactionUrl
                    ]);
                }

                return $tx->hash;
            },
            'network' => function ($tx) {
                return $tx->network->name;
            },
            'amount' => function ($tx) {
                $currency = $tx->order->paymentCurrency;
                $amount = Helpers::toString($tx->order->paymentAmount, $currency->decimals);

                if (isset($tx->order->discountRate)) {
                    $realAmount = Helpers::fromPercent(
                        $tx->order->paymentAmount,
                        $tx->order->discountRate,
                        $currency->decimals
                    );
                }

                if (isset($realAmount)) {
                    $result = esc_html(
                        __('Discounted amount: ', 'cryptopay_lite') . $amount . " " . $currency->symbol
                    ) . CP_BR2;

                    $result .= esc_html(
                        __('Real amount: ', 'cryptopay_lite') . $realAmount . " " . $currency->symbol
                    ) . CP_BR2;

                    $result .= esc_html(__('Discount rate: ', 'cryptopay_lite') . $tx->order->discountRate . "%");

                    return $result;
                } else {
                    return esc_html($amount . " " . $currency->symbol);
                }
            },
            'status' => function ($tx) {
                $result = Helpers::view('components/status', [
                    'status' => str_replace('-', ' ', $tx->status)
                ]);

                if (isset($tx->params->sanction)) {
                    $result .= CP_BR2 . esc_html__('Sanctions source: ', 'cryptopay_lite');
                    $result .= $tx->params->sanction->source .  ' with ' . $tx->params->sanction->api . ' API';
                }

                return $result;
            },
            'addresses' => function ($tx) {
                if (!isset($tx->addresses)) {
                    return esc_html__('Not found!', 'cryptopay_lite');
                }

                if (isset($tx->addresses->sender) || isset($tx->addresses->receiver)) {
                    $sender = $tx->addresses->sender ?? esc_html__('Pending...', 'cryptopay_lite');
                    $receiver = $tx->addresses->receiver ?? esc_html__('Pending...', 'cryptopay_lite');
                    $sender = esc_html__('Sender: ', 'cryptopay_lite') . $sender;
                    $receiver = esc_html__('Receiver: ', 'cryptopay_lite') . $receiver;
                } else {
                    $sender = esc_html__('Sender: ', 'cryptopay_lite') . esc_html__('Not found!', 'cryptopay_lite');
                    $receiver = esc_html__('Receiver: ', 'cryptopay_lite') . esc_html__('Not found!', 'cryptopay_lite');
                }

                return $sender . CP_BR2 . $receiver;
            },
            'createdAt' => function ($tx) {
                return (new \DateTime($tx->createdAt->date))->setTimezone(
                    new \DateTimeZone(wp_timezone_string())
                )->format('d M Y H:i');
            },
            'updatedAt' => function ($tx) {
                return (new \DateTime($tx->updatedAt->date))->setTimezone(
                    new \DateTimeZone(wp_timezone_string())
                )->format('d M Y H:i');
            },
            'delete' => function ($tx) {
                if (strtolower($tx->status) == Status::PENDING->getValue()) {
                    return '';
                };
                return Helpers::view('components/delete', [
                    'url' => Helpers::getCurrentUrl() . '&id=' . $tx->id
                ]);
            }
        ], $this->hooks))
        ->addHeaderElements(function (): void {
            Helpers::viewEcho('pages/transaction-page/form', [
                'pageUrl' => $this->getUrl(),
                'codes' => Helpers::getNetworkCodes()
            ]);
        })
        ->createDataList(function () use ($params) {
            if (isset($_GET['s']) && !empty($_GET['s'])) {
                $s = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : null;
                $result = (object) $this->model->search($s, $params);
                return [$result->transactions->toArray(false), $result->count];
            } else {
                $transactions = $this->model->findBy($params, ['id', 'DESC']);
                return [$transactions->toArray(false), $transactions->count()];
            }
        });

        Helpers::addStyle('admin.min.css');
        Helpers::viewEcho('pages/transaction-page/index', [
            'table' => $table
        ]);
    }
}
