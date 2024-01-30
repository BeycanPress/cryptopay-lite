<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Pages;

use BeycanPress\WPTable\Table;
use BeycanPress\CryptoPayLite\Services;
use BeycanPress\CryptoPayLite\PluginHero\Page;

/**
 * Order transactions page
 */
class TransactionPage extends Page
{
    /**
     * @var object
     */
    private object $model;

    /**
     * @var array<string>
     */
    private array $hooks;

    /**
     * @var array<string>
     */
    private static array $slugs = [];

    /**
     * @var string
     */
    public string $pageUrl;

    /**
     * @var array<string>
     */
    private array $excludeColumns = [];

    /**
     * @param string $name
     * @param string $addon
     * @param int $priority
     * @param array<string> $hooks
     * @param bool $confirmation
     * @param array<string> $excludeColumns
     */
    public function __construct(
        string $name,
        string $addon,
        int $priority = 10,
        array $hooks = [],
        bool $confirmation = true,
        array $excludeColumns = []
    ) {
        $slug = $this->pluginKey . '_' . sanitize_title($addon) . '_transactions';

        if (in_array($slug, self::$slugs)) {
            throw new \Exception('This slug is already registered, please choose another slug!');
        }

        self::$slugs[] = $slug;

        $this->hooks = $hooks;
        $this->confirmation = $confirmation;
        $this->excludeColumns = $excludeColumns;
        $this->model = Services::getModelByAddon($addon);
        $this->pageUrl = admin_url('admin.php?page=' . $slug);

        parent::__construct([
            'slug' => $slug,
            'pageName' => $name,
            'parent' => $this->pages->HomePage->slug,
            'priority' => $priority
        ]);
    }

    /**
     * @return void
     */
    public function page(): void
    {
        $status = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : null;

        if (isset($_GET['id']) && $this->model->delete(['id' => absint($_GET['id'])])) {
            $this->notice(esc_html__('Successfully deleted!', 'cryptopay_lite'), 'success', true);
        }

        $params = [
            'code' => 'evmBased',
        ];

        if ($status) {
            $params['status'] = $status;
        }

        $table = (new Table($this->model, $params))
        ->setColumns(array_filter([
            'hash'      => esc_html__('Hash', 'cryptopay_lite'),
            'orderId'   => esc_html__('Order ID', 'cryptopay_lite'),
            'userId'    => esc_html__('User ID', 'cryptopay_lite'),
            'network'   => esc_html__('Network', 'cryptopay_lite'),
            'amount'    => esc_html__('Amount', 'cryptopay_lite'),
            'status'    => esc_html__('Status', 'cryptopay_lite'),
            'updatedAt' => esc_html__('Updated at', 'cryptopay_lite'),
            'createdAt' => esc_html__('Created at', 'cryptopay_lite'),
            'delete'    => esc_html__('Delete', 'cryptopay_lite')
        ], function ($key) {
            return !in_array($key, $this->excludeColumns);
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
                // @phpcs:ignore
                return '<a href="' . Services::getProviderByTx($tx)->Transaction($tx->hash)->getUrl() . '" target="_blank">' . $tx->hash . '</a>';
            },
            'network' => function ($tx) {
                return json_decode($tx->network)->name;
            },
            'amount' => function ($tx) {
                $order = json_decode($tx->order);
                $currency = $order->paymentCurrency;
                $amount = Services::toString(
                    floatval($order->paymentPrice ?? $order->paymentAmount),
                    $currency->decimals
                );
                return esc_html($amount . " " . $currency->symbol);
            },
            'status' => function ($tx) {
                if ($tx->status == 'pending') {
                    return esc_html__('Pending', 'cryptopay_lite');
                } elseif ($tx->status == 'verified') {
                    return esc_html__('Verified', 'cryptopay_lite');
                } elseif ($tx->status == 'failed') {
                    return esc_html__('Failed', 'cryptopay_lite');
                }
            },
            'createdAt' => function ($tx) {
                // @phpcs:ignore
                return (new \DateTime($tx->createdAt))->setTimezone(new \DateTimeZone(wp_timezone_string()))->format('Y-m-d H:i:s');
            },
            'updatedAt' => function ($tx) {
                // @phpcs:ignore
                return (new \DateTime($tx->updatedAt))->setTimezone(new \DateTimeZone(wp_timezone_string()))->format('Y-m-d H:i:s');
            },
            'delete' => function ($tx): string {
                if (strtolower($tx->status) == 'pending') {
                    return '';
                }

                // @phpcs:ignore
                return '<a class="button" href="' . $this->getCurrentUrl() . '&id=' . $tx->id . '">' . esc_html__('Delete', 'cryptopay_lite') . '</a>';
            }
        ], $this->hooks))->addHeaderElements(function () {
            return $this->view('pages/transaction-page/form');
        })
        ->createDataList(function (object $model) use ($params) {
            if (isset($_GET['s']) && !empty($_GET['s'])) {
                $s = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : null;
                return array_values($model->search($s, $params));
            }
        });

        $this->viewEcho('pages/transaction-page/index', [
            'table' => $table
        ]);
    }
}
