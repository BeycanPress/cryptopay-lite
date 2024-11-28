<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Pages;

//Classes
use BeycanPress\CryptoPayLite\Helpers;
use BeycanPress\CryptoPayLite\PluginHero\Hook;
use BeycanPress\CryptoPayLite\PluginHero\Page;
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
        if (is_null($this->model)) {
            echo esc_html__(
                'Unfortunately there is no transaction list for this addon as no model has been registered.',
                'cryptopay'
            );
            return;
        }

        $code = isset($_GET['code']) ? $_GET['code'] : 'all';
        $status = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : null;

        if (isset($_GET['id']) && $this->model->delete(['id' => absint($_GET['id'])])) {
            Helpers::notice(esc_html__('Successfully deleted!', 'cryptopay'), 'success', true);
        }

        $params = [];

        if ('all' != $code) {
            $params['code'] = $code;
        }

        if ($status) {
            $params['status'] = $status;
        }

        Hook::callAction('transaction_page', $params, $code, $status);

        $table = (new Table([], $params))
        ->setColumns(array_filter([
            'hash'      => esc_html__('Hash', 'cryptopay'),
            'orderId'   => esc_html__('Order ID', 'cryptopay'),
            'userId'    => esc_html__('User ID', 'cryptopay'),
            'network'   => esc_html__('Network', 'cryptopay'),
            'amount'    => esc_html__('Amount', 'cryptopay'),
            'status'    => esc_html__('Status', 'cryptopay'),
            'addresses' => esc_html__('Addresses', 'cryptopay'),
            'updatedAt' => esc_html__('Updated at', 'cryptopay'),
            'createdAt' => esc_html__('Created at', 'cryptopay'),
            'delete'    => esc_html__('Delete', 'cryptopay')
        ], function ($key) {
            return !in_array($key, $this->hideColumns);
        }, ARRAY_FILTER_USE_KEY))
        ->setOptions([
            'search' => [
                'id' => 'search-box',
                'title' => esc_html__('Search...', 'cryptopay')
            ]
        ])
        ->setOrderQuery(['createdAt', 'desc'])
        ->setSortableColumns(['createdAt'])
        ->addHooks(array_merge([
            'hash' => function ($tx) {
                try {
                    if (Helpers::providerExists($tx->code)) {
                        $provider = Helpers::getProvider(TransactionType::fromObject($tx));
                        $transactionUrl = $provider->transaction($tx->hash)->getUrl();
                        return Helpers::view('components/link', [
                            'text' => $tx->hash,
                            'url' => $transactionUrl
                        ]);
                    }
                } catch (\Throwable $th) {
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
                        __('Discounted amount: ', 'cryptopay') . $amount . " " . $currency->symbol
                    ) . CPL_BR2;

                    $result .= esc_html(
                        __('Real amount: ', 'cryptopay') . $realAmount . " " . $currency->symbol
                    ) . CPL_BR2;

                    $result .= esc_html(__('Discount rate: ', 'cryptopay') . $tx->order->discountRate . "%");

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
                    $result .= CPL_BR2 . esc_html__('Sanctions source: ', 'cryptopay');
                    $result .= $tx->params->sanction->source .  ' with ' . $tx->params->sanction->api . ' API';
                }

                if (isset($tx->params->failedReason) && $tx->status == Status::FAILED->getValue()) {
                    $result .= CPL_BR2 . esc_html__('Failed reason: ', 'cryptopay');
                    $result .= $tx->params->failedReason;
                }

                return $result;
            },
            'addresses' => function ($tx) {
                if (!isset($tx->addresses)) {
                    return esc_html__('Not found!', 'cryptopay');
                }

                if (isset($tx->addresses->sender) || isset($tx->addresses->receiver)) {
                    $sender = $tx->addresses->sender ?? esc_html__('Pending...', 'cryptopay');
                    $receiver = $tx->addresses->receiver ?? esc_html__('Pending...', 'cryptopay');
                    $sender = esc_html__('Sender: ', 'cryptopay') . $sender;
                    $receiver = esc_html__('Receiver: ', 'cryptopay') . $receiver;
                } else {
                    $sender = esc_html__('Sender: ', 'cryptopay') . esc_html__('Not found!', 'cryptopay');
                    $receiver = esc_html__('Receiver: ', 'cryptopay') . esc_html__('Not found!', 'cryptopay');
                }

                $addresses = $sender . CPL_BR2 . $receiver;

                if (
                    $tx->status == Status::FAILED->getValue() &&
                    strtolower(strval($tx->addresses?->sender)) == strtolower(strval($tx->addresses?->receiver))
                ) {
                    $addresses .= CPL_BR2 . esc_html__('Warning: Sender and receiver addresses are the same! Transaction can be failed by this!', 'cryptopay'); // @phpcs:ignore
                }

                return $addresses;
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
                'codes' => [
                    'evmchains',
                ]
            ]);
        })
        ->createDataList(function ($entry, $orderQuery, $perPage, $offset) use ($params) {
            if (isset($_GET['s']) && !empty($_GET['s'])) {
                $s = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : null;
                $result = (object) $this->model->search($s, $params);
                return [$result->transactions->toArray(false), $result->count];
            } else {
                $transactions = $this->model->findBy($params, $orderQuery, $perPage, $offset);
                return [$transactions->toArray(false), $this->model->getCount($params)];
            }
        });

        Helpers::addStyle('admin.min.css');
        Helpers::viewEcho('pages/transaction-page/index', [
            'table' => $table
        ]);
    }
}
